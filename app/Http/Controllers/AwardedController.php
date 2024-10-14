<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageErrors;
use App\Http\Core\PersonResponse;
use App\Models\Awarded;
use Illuminate\Http\Request;

class AwardedController extends Controller
{
    protected $MessageErrors;
    protected $PersonResponse;

    public function __construct(MessageErrors $MessageErrors, PersonResponse $PersonResponse)
    {
        $this->MessageErrors = $MessageErrors;
        $this->PersonResponse = $PersonResponse;
    }

    public function index()
    {
        try {
            $Awarded = Awarded::paginate();
            return $this->PersonResponse->returnResponsePaginate($Awarded);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function show($id)
    {
        try {
            $name = Awarded::find($id);
            return $this->PersonResponse->returnResponseArray([$name]);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $name = Awarded::create($request->all());
            return response()->json(["message" => "Registro criado com sucesso", "data" => env("APP_DEBUG") ? $name : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $name = Awarded::find($id);
            $name->update($request->all());
            return response()->json(["message" => "Registro atualizado com sucesso", "data" => env("APP_DEBUG") ? $name : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function destroy($id)
    {
        try {
            $name = Awarded::find($id);
            $name->delete(); // Use delete() em vez de destroy()
            return response()->json(["message" => "Registro excluído com sucesso", "data" => env("APP_DEBUG") ? $name : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
}
