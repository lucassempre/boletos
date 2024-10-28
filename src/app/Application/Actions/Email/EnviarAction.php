<?php

namespace App\Application\Actions\Email;

use App\Domain\Repositories\BoletoRepositoryInterface;

class EnviarAction
{
    protected BoletoRepositoryInterface $repository;

    public function __construct(BoletoRepositoryInterface $repository,)
    {
        $this->repository = $repository;
    }

    public function execute(string $processamento_uuid)
    {
        $this->repository->updateStatusByProcessamento($processamento_uuid, 'Boleto Enviado');
    }

}
