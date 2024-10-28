<?php

namespace App\Application\Jobs;

use App\Application\Actions\Boleto\CriarAction;
use App\Infrastructure\Repositories\StatusRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GerarBoletoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $retryAfter = 2;


    protected string $processamento_uuid;

    public function __construct(string $processamento_uuid)
    {
        $this->processamento_uuid = $processamento_uuid;
    }

    /**
     * Executa o job.
     *
     * @return void
     */
    public function handle(CriarAction $action)
    {
        $action->execute($this->processamento_uuid);
    }

    public function failed(?Throwable $exception): void
    {
        (new StatusRepository())->create([
            'processamento_uuid' =>  $this->processamento_uuid,
            'status' => 'Invalido',
            'status_descricao' => json_encode([
                'error' => $exception->getMessage(),
                'detalhe' => $exception
            ]),
        ]);
    }
}
