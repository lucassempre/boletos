<?php

namespace App\Application\Actions\Validar;

use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;

use App\Infrastructure\Models\Processamento;
use Exception;

class ValidarAction
{
    protected FileRepositoryInterface $fileRepository;
    protected ProcessamentoRepositoryInterface $processamentoRepository;

    public function __construct(FileRepositoryInterface $fileRepository,
                                ProcessamentoRepositoryInterface $processamentoRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->processamentoRepository = $processamentoRepository;
    }

    public function execute(Processamento $processamento): void
    {
        $file_uuid = $this->getUuid($processamento);
        $hash = $this->hashFile($this->download($file_uuid));
        $this->unico($hash);
        $this->updateOperacaoHashFile($processamento->uuid, $hash);
    }

    private function unico(string $hash): void
    {
        if($this->processamentoRepository->hasHash( $hash))
            throw new Exception('Arquivo já processado');
    }

    private function updateOperacaoHashFile(string $processamento_uuid, string $hash): void
    {
        $this->processamentoRepository->updateHash($hash, $processamento_uuid);
    }

    private function download(string $operacao_uuid): string
    {
        return $this->fileRepository->downloadLocal($operacao_uuid);
    }
    private function hashFile(string $arquivo): ?string
    {
        return md5_file($arquivo);
    }

    protected function getUuid(Processamento $processamento): string
    {
        return $processamento->operacao()->value('file') ?? throw new Exception('Processamento sem identificação');
    }

}
