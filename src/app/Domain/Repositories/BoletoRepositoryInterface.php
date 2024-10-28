<?php

namespace App\Domain\Repositories;

interface BoletoRepositoryInterface extends BaseRepositoryInterface
{
    public function updateStatus(string $boleto_uuid, string $status): bool;

    public function upSert(array $boletos, array $columns = ['debtId']): bool;

}
