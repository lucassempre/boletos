<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\FileRepositoryInterface;
use Aws\Command as AwsCommand;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

class S3FileRepository implements FileRepositoryInterface
{
    protected S3Client $s3Client;
    protected string $bucket;
    protected $mimeTypes;

    public function __construct()
    {
        $this->s3Client = new S3Client(config('aws.s3'));
        $this->bucket = config('aws.s3.bucket');
        $this->mimeTypes = new MimeTypes();
    }

    public function upload(UploadedFile $uploadedFile): ?string
    {
        $uuid = (string) Str::uuid();
        $filename = $uploadedFile->getClientOriginalName();
        $type = $uploadedFile->getClientMimeType();
        $stream = fopen($uploadedFile->getPathname(), 'r');

        return (new MultipartUploader($this->s3Client, $stream, [
            'bucket'   => $this->bucket,
            'key'      => $uuid,
            'before_initiate' => function (AwsCommand $command) use ($type, $filename){
                $command['ContentType'] = $type;
                $command['ContentDisposition'] = 'attachment; filename="'.$filename.'"';
            },
        ]))->upload()->count() ? $uuid : '';
    }

    public function download(string $uuid): ?string
    {
        $command = $this->s3Client->getCommand('GetObject',
            [
                'Bucket' => $this->bucket,
                'Key'    => $uuid,
            ]
        );
        $request = $this->s3Client->createPresignedRequest($command, '+60 minutes');
        return (string) $request->getUri();
    }

    public function delete(string $uuid): bool
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $uuid,
            ]
        );
        return true;
    }

    public function list(): array
    {
        $result = $this->s3Client->listObjectsV2([
            'Bucket' => $this->bucket,
            ]
        );

        $files = [];

        if (isset($result['Contents'])) {
            foreach ($result['Contents'] as $object) {
                $files[] = [
                    'uuid'         => data_get($object, 'Key'),
                    'size'         => data_get($object, 'Size'),
                ];
            }
        }

        return $files;
    }

    public function downloadLocal(string $key, string $toPath = 'operacao/'): string
    {
        $objetoS3 = $this->s3Client->getObject(['Bucket' => $this->bucket, 'Key' => $key]);
        $extensions = $this->mimeTypes->getExtensions(data_get($objetoS3,'ContentType'));
        $fileContent = data_get($objetoS3,'Body')->getContents();

        $localPath = $toPath . $key . '.' . $extensions[0];

        Storage::disk('local')->makeDirectory(dirname($localPath));
        Storage::disk('local')->put($localPath, $fileContent);

        return Storage::disk('local')->path($localPath);


    }
}
