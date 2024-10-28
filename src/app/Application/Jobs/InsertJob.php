<?php

namespace App\Application\Jobs;

use App\Infrastructure\Models\Boleto;
use App\Infrastructure\Repositories\StatusRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Throwable;

class InsertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $timeout = 360;

    public $tries = 3;
    protected array $boletos;
    protected string $processamento_uuid;

    public function __construct(array $boletos, string $processamento_uuid)
    {
        $this->boletos = $boletos;
        $this->processamento_uuid = $processamento_uuid;
    }

    /**
     * Executa o job.
     *
     * @return void
     */
    public function handle()
    {
        Boleto::upSert($this->boletos, ['debtId']);
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
