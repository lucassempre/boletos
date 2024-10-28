<?php

namespace App\Application\Jobs;

use App\Application\Actions\Validar\ValidarAction;
use App\Infrastructure\Models\Processamento;
use App\Infrastructure\Repositories\StatusRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ValidarJob implements ShouldQueue
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
    public function handle(ValidarAction $action)
    {
        $action->execute($this->processamento);
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
