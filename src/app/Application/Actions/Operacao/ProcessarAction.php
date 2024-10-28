<?php

namespace App\Application\Actions\Operacao;

use App\Application\Jobs\ProcessarJob;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use Illuminate\Support\Facades\Queue;

class ProcessarAction
{
    protected FileRepositoryInterface $fileRepository;
    protected ProcessamentoRepositoryInterface $processamentoRepository;
    protected OperacaoRepositoryInterface $operacaoRepository;

    public function __construct(FileRepositoryInterface $fileRepository,
                                ProcessamentoRepositoryInterface $processamentoRepository,
                                OperacaoRepositoryInterface $operacaoRepository
    )
    {
        $this->fileRepository = $fileRepository;
        $this->processamentoRepository = $processamentoRepository;
        $this->operacaoRepository = $operacaoRepository;
    }

    public function execute(string $operacao_uuid)
    {

        $operacao = $this->operacaoRepository->create(['file' => $operacao_uuid]);
        $processamento = $this->processamentoRepository->create(['operacao_uuid'=> $operacao->uuid]);

        Queue::pushOn('app_01_alta', new ProcessarJob($processamento));
    }

}
