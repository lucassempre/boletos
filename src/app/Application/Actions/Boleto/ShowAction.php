<?php

namespace App\Application\Actions\Boleto;

use App\Domain\Repositories\BoletoRepositoryInterface;

class ShowAction
{
    protected BoletoRepositoryInterface $boletoRepository;

    public function __construct(BoletoRepositoryInterface $boletoRepository)
    {
        $this->$boletoRepository = $boletoRepository;
    }

    public function execute(string $uuid): ?string
    {
        return $this->boletoRepository->find($uuid);
    }

}
