<?php

namespace App\Application\Jobs;

use App\Application\Actions\Operacao\InsertAction;
use App\Infrastructure\Models\Processamento;
use App\Infrastructure\Repositories\StatusRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ImportarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Processamento $processamento;
    /**
     * Cria uma nova instância do job.
     *
     * @return void
     */
    public function __construct(Processamento $processamento)
    {
        $this->processamento = $processamento;
    }

    /**
     * Executa o job.
     *
     * @return void
     */
    public function handle(InsertAction $action)
    {
        $jobs = $action->execute($this->processamento);
        $processamento_uuid = $this->processamento->uuid;
        Bus::batch($jobs)->finally(function (Batch $batch) use ($processamento_uuid){
            Bus::chain([
                new GerarBoletoJob($processamento_uuid),
                new EnviarBoletoJob($processamento_uuid),
            ])->onQueue('app_01_alta')->onQueue('app_01_alta')->dispatch();
        })->onQueue('app_01_alta')->dispatch();
    }

    public function failed(?Throwable $exception): void
    {
        (new StatusRepository())->create([
            'processamento_uuid' =>  $this->processamento->uuid,
            'status' => 'Invalido',
            'status_descricao' => json_encode([
                'error' => $exception->getMessage(),
                'detalhe' => $exception
            ]),
        ]);
    }
}
