<?php

use PHPUnit\Framework\TestCase;
use App\Application\Actions\Operacao\InsertAction;
use App\Application\Jobs\InsertJob;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use App\Infrastructure\Models\Processamento;
use Illuminate\Support\Str;

class InsertActionTest extends TestCase
{
    private FileRepositoryInterface $fileRepository;
    private ProcessamentoRepositoryInterface $processamentoRepository;
    private OperacaoRepositoryInterface $operacaoRepository;
    private InsertAction $insertAction;

    protected function setUp(): void
    {
        $this->fileRepository = $this->createMock(FileRepositoryInterface::class);
        $this->processamentoRepository = $this->createMock(ProcessamentoRepositoryInterface::class);
        $this->operacaoRepository = $this->createMock(OperacaoRepositoryInterface::class);

        $this->insertAction = new InsertAction(
            $this->fileRepository,
            $this->processamentoRepository,
            $this->operacaoRepository
        );
    }

    public function testExecute()
    {
        $processamento = $this->createMock(Processamento::class);
        $uuid = (string) Str::uuid();

        $processamento->method('__get')->with('uuid')->willReturn($uuid);

        $operacaoMock = new class {
            public function value($key)
            {
                return $key === 'file' ? '555' : null;
            }
        };

        $processamento
            ->method('operacao')
            ->willReturn($operacaoMock);

        $tempDir = sys_get_temp_dir();
        $filePath = $tempDir . '/testfile.csv';

        $csvData = "name,governmentId,email,debtAmount,debtDueDate,debtId\n" .
            "John Doe,123456789,email@example.com,100.50,2024-12-31,1\n" .
            "Jane Doe,987654321,janedoe@example.com,200.75,2025-01-15,2\n";

        file_put_contents($filePath, $csvData);

        $this->fileRepository
            ->expects($this->once())
            ->method('downloadLocal')
            ->willReturn($filePath);


        $jobs = $this->insertAction->execute($processamento);

        $this->assertIsArray($jobs);
        $this->assertCount(1, $jobs);

        // Verificando conteúdo do job
        /** @var InsertJob $job */
        $job = $jobs[0];
        $this->assertInstanceOf(InsertJob::class, $job);
        unlink($filePath);
    }

    public function testchunkExecute()
    {
        $processamento = $this->createMock(Processamento::class);
        $uuid = (string) Str::uuid();

        $processamento->method('__get')->with('uuid')->willReturn($uuid);

        $operacaoMock = new class {
            public function value($key)
            {
                return $key === 'file' ? 'path/to/testfile.csv' : null;
            }
        };

        $processamento
            ->method('operacao')
            ->willReturn($operacaoMock);

        $tempDir = sys_get_temp_dir();
        $filePath = $tempDir . '/testfile.csv';

        $csvData = "name,governmentId,email,debtAmount,debtDueDate,debtId\n" .
            "John Doe,123456789,email@example.com,100.50,2024-12-31,1\n" .
            "Jane Doe,987654321,janedoe@example.com,200.75,2025-01-15,2\n";

        file_put_contents($filePath, $csvData);

        $this->fileRepository
            ->expects($this->once())
            ->method('downloadLocal')
            ->willReturn($filePath);


        $jobs = $this->insertAction->execute($processamento);

        $this->assertIsArray($jobs);
        $this->assertCount(1, $jobs);

        // Verificando conteúdo do job
        /** @var InsertJob $job */
        $job = $jobs[0];
        $this->assertInstanceOf(InsertJob::class, $job);
        unlink($filePath);
    }

    public function testExecuteWithLargeDataset()
    {
        $uuid = (string) Str::uuid();
        $processamento = $this->createMock(Processamento::class);
        $processamento->method('__get')->with('uuid')->willReturn($uuid);
        $operacaoMock = new class {
            public function value($key)
            {
                return $key === 'file' ? 'path/to/testfile.csv' : null;
            }
        };

        $processamento
            ->method('operacao')
            ->willReturn($operacaoMock);

        $tempDir = sys_get_temp_dir();
        $filePath = $tempDir . '/testfile.csv';

        $csvData = "name,governmentId,email,debtAmount,debtDueDate,debtId\n";
        for ($i = 1; $i <= 5500; $i++) {
            $csvData .= "Person $i,12345678$i,email$i@example.com,100.50,2024-12-31,$i\n";
        }
        file_put_contents($filePath, $csvData);

        $this->fileRepository
            ->expects($this->once())
            ->method('downloadLocal')
            ->willReturn($filePath);

        $jobs = $this->insertAction->execute($processamento);

        $this->assertIsArray($jobs);
        $this->assertCount(2, $jobs);

        foreach ($jobs as $index => $job) {
            $this->assertInstanceOf(InsertJob::class, $job);

            $reflection = new ReflectionClass($job);
            $boletosProperty = $reflection->getProperty('boletos');
            $boletosProperty->setAccessible(true);
            $boletos = $boletosProperty->getValue($job);

            $expectedCount = ($index === 0) ? 5000 : 500;
            $this->assertCount($expectedCount, $boletos);

            $firstBoleto = $boletos[0];
            $this->assertEquals($uuid, $firstBoleto['processamento_uuid']);
            $this->assertEquals("Person " . (($index * 5000) + 1), $firstBoleto['name']);
        }

        unlink($filePath);
    }
}
