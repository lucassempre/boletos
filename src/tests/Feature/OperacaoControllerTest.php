<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Application\Jobs\ProcessarJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;

class OperacaoControllerTest extends TestCase
{
    public function testProcessarEndpoint()
    {
        // Mock da fila
        Queue::fake();

        // UUID simulado para o arquivo
        $arquivoUuid = (string) Str::uuid();

        // Chamando o endpoint
        $response = $this->postJson("/api/processar/{$arquivoUuid}");

        // Verificando resposta
        $response->assertStatus(201)
            ->assertJson(['message' => 'Operação em fila de processamento']);

        // Verificando que o ProcessarJob foi enviado para a fila
        Queue::assertPushed(ProcessarJob::class, function ($job) use ($arquivoUuid) {
            // Usando Reflection para acessar a propriedade protegida
            $reflection = new \ReflectionClass($job);
            $property = $reflection->getProperty('processamento');
            $property->setAccessible(true);

            $processamento = $property->getValue($job);
            return $processamento->operacao->file === $arquivoUuid;
        });
    }
}
