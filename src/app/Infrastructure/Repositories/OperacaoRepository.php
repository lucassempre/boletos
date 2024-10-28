<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Infrastructure\Models\Operacao;


class OperacaoRepository extends BaseRepository implements OperacaoRepositoryInterface
{
    public function model(): string
    {
        return Operacao::class;
    }
}
