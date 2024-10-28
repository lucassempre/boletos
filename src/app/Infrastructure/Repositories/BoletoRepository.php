<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\BoletoRepositoryInterface;
use App\Infrastructure\Models\Boleto;

class BoletoRepository extends BaseRepository implements BoletoRepositoryInterface
{
    public function model(): string
    {
        return Boleto::class;
    }


    public function updateStatus(string $boleto_uuid, string $status): bool
    {
        return $this->find($boleto_uuid)->update(['status' => $status]);
    }

    public function updateStatusByProcessamento(string $processamento_uuid, string $status): bool
    {
        return $this->model->where('processamento_uuid', $processamento_uuid)->update(['status' => $status]);
    }

    public function upSert(array $boletos, array $column = ['debtId']): bool
    {
        return $this->model->upSert($boletos, $column);
    }

    public function findByStatus(string $status): array
    {
        return $this->model->where(['status', $status]);
    }
}
