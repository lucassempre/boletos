<?php

namespace App\Application\Actions\Files;

use App\Domain\Repositories\FileRepositoryInterface;

class DeleteAction
{
    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function execute(string $uuid): bool
    {
        return $this->fileRepository->delete($uuid);
    }
}
