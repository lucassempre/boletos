<?php

namespace App\Infrastructure\Controllers;

use App\Application\Actions\Boleto\ListAction;
use App\Application\Actions\Boleto\ShowAction;
use App\Application\Actions\Boleto\StatusAction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class BoletoController extends BaseController
{
    public function list(ListAction $action)
    {
        try {
            $action->execute();
            return response()->json(['message' =>  'Operação em fila de processamento'], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao processar operação: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao processar operação'], 404);
        }
    }

    public function show($uuid, ShowAction $action)
    {
        try {
            $action->execute($uuid);
            return response()->json(['message' =>  'Operação em fila de processamento'], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao processar operação: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao processar operação'], 404);
        }
    }

    public function status(string $status, StatusAction $action)
    {
        try {
            $action->execute($status);
            return response()->json(['message' =>  'Operação em fila de processamento'], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao processar operação: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao processar operação'], 404);
        }
    }
}
