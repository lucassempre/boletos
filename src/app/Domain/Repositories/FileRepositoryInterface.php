<?php

namespace App\Domain\Repositories;

use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    public function upload(UploadedFile $uploadedFile): ?string;
    public function download(string $uuid): ?string;
    public function downloadLocal(string $uuid): ?string;
    public function delete(string $uuid): bool;
    public function list(): array;
}
