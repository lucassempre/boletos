<?php

namespace Tests\Feature;

use App\Application\Actions\Operacao\ProcessarAction;
use App\Application\Jobs\ProcessarJob;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class ProcessarActionTest extends TestCase
{
    public function testProcessarExecute()
    {
        Queue::fake();

        $fileRepository = Mockery::mock(FileRepositoryInterface::class);
        $operacaoRepository = Mockery::mock(OperacaoRepositoryInterface::class);
        $processamentoRepository = Mockery::mock(ProcessamentoRepositoryInterface::class);

        $arquivoUuid = 'test-uuid';

        $operacaoRepository->shouldReceive('create')->once()->andReturn((object) ['uuid' => $arquivoUuid]);
        $processamentoRepository->shouldReceive('create')->once()->andReturn((object) ['uuid' => 'processamento-uuid']);

        $action = new ProcessarAction($fileRepository, $processamentoRepository, $operacaoRepository);
        $action->execute($arquivoUuid);

        Queue::assertPushed(ProcessarJob::class, function ($job) {
            return $job->processamento->uuid === 'processamento-uuid';
        });
    }
}
