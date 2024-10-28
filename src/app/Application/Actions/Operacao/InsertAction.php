<?php

namespace App\Application\Actions\Operacao;

use App\Application\Jobs\InsertJob;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use App\Infrastructure\Models\Processamento;
use Illuminate\Support\Str;

class InsertAction
{
    protected FileRepositoryInterface $fileRepository;
    protected ProcessamentoRepositoryInterface $processamentoRepository;
    protected OperacaoRepositoryInterface $operacaoRepository;

    public function __construct(FileRepositoryInterface          $fileRepository,
                                ProcessamentoRepositoryInterface $processamentoRepository,
                                OperacaoRepositoryInterface      $operacaoRepository
    )
    {
        $this->fileRepository = $fileRepository;
        $this->processamentoRepository = $processamentoRepository;
        $this->operacaoRepository = $operacaoRepository;
    }

    public function execute(Processamento $processamento): array
    {

        $file = $this->download($processamento->operacao()->value('file'));

        $handle = fopen($file, 'r');
        fgetcsv($handle, 0, ',');

        $lote = [];
        $jobs = [];

        while (!feof($handle)) {
            $linha = fgetcsv($handle, 0, ',');

            if ($linha === false) {
                continue;
            }

            $dados = [
                'uuid' => Str::uuid()->toString(),
                'name' => $linha[0],
                'governmentId' => $linha[1],
                'email' => $linha[2],
                'debtAmount' => $linha[3],
                'debtDueDate' => $linha[4],
                'debtId' => $linha[5],
                'processamento_uuid' => $processamento->uuid,
            ];

            $lote[] = $dados;

            if (count($lote) >= 5000) {
                $jobs[] = new InsertJob($lote, $processamento->uuid);
                $lote = [];
            }
        }

        if (count($lote) > 0) {
            $jobs[] = new InsertJob($lote, $processamento->uuid);
        }

        fclose($handle);

        return  $jobs;

    }

    private function download(string $file): string
    {
        return $this->fileRepository->downloadLocal($file);
    }
}
