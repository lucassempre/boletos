<?php

namespace App\Application\Actions\Files;

use App\Domain\Repositories\FileRepositoryInterface;

class DownloadAction
{
    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function execute(string $uuid): ?string
    {
        return $this->fileRepository->download($uuid);
    }

}
