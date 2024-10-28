<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use App\Infrastructure\Models\Processamento;


class ProcessamentoRepository extends BaseRepository implements ProcessamentoRepositoryInterface
{
    public function model(): string
    {
        return Processamento::class;
    }
    public function updateHash(string $hash, string $processamento_uuid): bool
    {

        return $this->model->where('uuid', $processamento_uuid)->update(['hash_file' => $hash]);
    }

    public function hasHash(string $hash): bool
    {
        return $this->model->where('hash_file', $hash)->count();
    }

}
