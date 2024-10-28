<?php

namespace App\Application\Actions\Boleto;

use App\Domain\Repositories\BoletoRepositoryInterface;

class StatusAction
{
    protected BoletoRepositoryInterface $boletoRepository;

    public function __construct(BoletoRepositoryInterface $boletoRepository)
    {
        $this->$boletoRepository = $boletoRepository;
    }

    public function execute(string $status): array
    {
        return $this->boletoRepository->findByStatus($status);
    }

}
