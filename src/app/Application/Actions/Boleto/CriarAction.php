<?php

namespace App\Application\Actions\Boleto;

use App\Domain\Repositories\BoletoRepositoryInterface;

class CriarAction
{
    protected BoletoRepositoryInterface $repository;

    public function __construct(BoletoRepositoryInterface $repository,)
    {
        $this->repository = $repository;
    }

    public function execute(string $processamento_uuid)
    {
        $this->repository->updateStatusByProcessamento($processamento_uuid, 'Boleto Gerado');
    }

}
