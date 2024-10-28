<?php

namespace App\Infrastructure\Controllers;

use App\Application\Actions\Files\{DeleteAction, DownloadAction, ListAction, UploadAction};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class FileController extends BaseController
{
    public function upload(Request $request, UploadAction $action)
    {
        try {
            $request->validate(['file' => 'required|file',]);
            return response()->json(['message' => $action->execute($request->file('file'))], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' =>   $e->getMessage()], 500);
        } catch (Throwable $e) {
            Log::error('Erro no upload de objetos S3: ' . $e->getMessage());
            return response()->json(['message' =>  'Ocorreu um erro durante o upload'], 500);
        }
    }

    public function download($uuid, DownloadAction $action)
    {
        try {
            return response()->json(['message' => $action->execute($uuid)], 200);
        } catch (Throwable $e) {
            Log::error('Erro ao gerar link de objetos S3: ' . $e->getMessage());
            return response()->json(['message' => 'Arquivo não encontrado'], 404);
        }
    }

    public function delete($uuid, DeleteAction $action)
    {
        try {
            $action->execute($uuid);
            return response()->json(['message' =>  'Arquivo deletado com sucesso'], 201);
        } catch (Throwable $e) {
            Log::error('Erro ao deletar objetos S3: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao deletar o arquivo'], 404);
        }
    }

    public function list(ListAction $action)
    {
        try {
            return response()->json(['message' =>  $action->execute()], 200);
        } catch (Throwable $e) {
            Log::error('Erro ao listar objetos S3: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar arquivos'], 404);
        }
    }
}
