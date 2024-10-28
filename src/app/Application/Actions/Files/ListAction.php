<?php

namespace App\Application\Actions\Files;

use App\Domain\Repositories\FileRepositoryInterface;

class ListAction
{
    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function execute(): array
    {
        return $this->fileRepository->list();
    }

}
