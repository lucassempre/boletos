<?php

namespace App\Infrastructure\Controllers;

use App\Application\Actions\Operacao\ProcessarAction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class OperacaoController extends BaseController
{
    public function processar($arquivo_uuid, ProcessarAction $action)
    {
        try {
            $action->execute($arquivo_uuid);
            return response()->json(['message' =>  'Operação em fila de processamento'], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao processar operação: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao processar operação'], 404);
        }
    }
}
