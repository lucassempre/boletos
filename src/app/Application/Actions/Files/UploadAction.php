<?php

namespace App\Application\Actions\Files;

use App\Domain\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;

class UploadAction
{
    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function execute(UploadedFile $uploadedFile): ?string
    {
        return $this->fileRepository->upload($uploadedFile);
    }
}
