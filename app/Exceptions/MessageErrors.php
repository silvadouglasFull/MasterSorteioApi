<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageErrors
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getMessageError($th)
    {
        try {
            $errors = [];
            $statusErros = 500;
            if ($th instanceof NotFoundHttpException) {
                return response()->json(['mesage' => 'Não encontramos a funcionalidade solicitada'], 404);
            }
            if (isset($th->status)) {
                $statusErros = $th->status;
            }
            if (!isset($th->response)) {
                foreach ($th as $value) {
                    if (is_array($value) || is_object($value)) {
                        foreach ($value as $v) {
                            array_push($errors, $v);
                        }
                    } else {
                        $errors = [
                            "Não foi posível completar sua solicitação"
                        ];
                    }
                }
            } else {
                foreach ($th->response->original as $value) {
                    foreach ($value as $v) {
                        array_push($errors, $v);
                    }
                }
            }
            Log::error(json_encode($th, JSON_UNESCAPED_UNICODE));
            if (count($errors) === 0) {
                return response()->json([
                    "message" => 'Não foi possível completar sua solicitação, por favor entre em contato com seu administrador',
                    "infoError" => env("APP_DEBUG") ? $th : ''
                ], $statusErros);
            }
            return response()->json([
                "message" => implode(" ", $errors),
                "infoError" => env("APP_DEBUG") ? $th : ''

            ], $statusErros);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => 'Não foi possível completar sua solicitação, por favor entre em contato com seu administrador',
                "infoError" => env("APP_DEBUG") ? $th : ''
            ], 500);
        }
    }
}
