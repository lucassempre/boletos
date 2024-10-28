<?php

namespace App\Application\Actions\Boleto;

use App\Domain\Repositories\BoletoRepositoryInterface;

class ListAction
{
    protected BoletoRepositoryInterface $boletoRepository;

    public function __construct(BoletoRepositoryInterface $boletoRepository)
    {
        $this->boletoRepository = $boletoRepository;
    }

    public function execute(int $page, int $limit): array
    {
        return $this->boletoRepository->page($page, $limit);
    }

}
