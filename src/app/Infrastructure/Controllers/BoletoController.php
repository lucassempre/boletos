<?php

namespace App\Infrastructure\Controllers;

use App\Application\Actions\Boleto\ListAction;
use App\Application\Actions\Boleto\ShowAction;
use App\Application\Actions\Boleto\StatusAction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class BoletoController extends BaseController
{
    public function list(Request $request, ListAction $action)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 15);

        try {
            return response()->json(['message' =>  $action->execute($page, $limit)], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao exibir Boleto: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao exibir Boleto'], 404);
        }
    }

    public function show($uuid, ShowAction $action)
    {
        try {
            return response()->json(['message' =>  $action->execute($uuid)], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao exibir Boleto: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao exibir Boleto'], 404);
        }
    }

    public function status(string $status, StatusAction $action)
    {
        try {
            return response()->json(['message' =>  $action->execute($status)], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao exibir Boleto: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao exibir Boleto'], 404);
        }
    }
}
