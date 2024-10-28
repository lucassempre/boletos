<?php

namespace App\Domain\Repositories;

interface ProcessamentoRepositoryInterface extends BaseRepositoryInterface
{
    public function updateHash(string $hash, string $processamento_uuid): bool;
}
