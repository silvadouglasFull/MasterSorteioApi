<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageErrors;
use App\Http\Core\PersonResponse;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    protected $MessageErrors;
    protected $PersonResponse;
    function __construct(MessageErrors $MessageErrors, PersonResponse $PersonResponse)
    {
        $this->MessageErrors = $MessageErrors;
        $this->PersonResponse = $PersonResponse;
    }

    public function index()
    {
        try {
            $Form = Form::paginate();
            return $this->PersonResponse->returnResponsePaginate($Form);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function show($id)
    {
        try {
            $Form = Form::find($id);
            return $this->PersonResponse->returnResponseArray([$Form]);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $Form = Form::create($request->all());
            return response()->json(["message" => "Registro criado com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $Form = Form::find($id);
            $Form->update($request->all());
            return response()->json(["message" => "Registro atualizado com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function destroy($id)
    {
        try {
            $Form = Form::find($id);
            Form::destroy($id);
            return response()->json(["message" => "Registro excluído com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
}
