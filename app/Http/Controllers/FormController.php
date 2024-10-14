<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageErrors;
use App\Http\Core\PersonResponse;
use App\Models\Awarded;
use App\Models\Form;
use App\Services\stringHandless;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormController extends Controller
{
    protected $MessageErrors;
    protected $PersonResponse;
    protected $stringHandless;
    function __construct(MessageErrors $MessageErrors, PersonResponse $PersonResponse, stringHandless $stringHandless)
    {
        $this->MessageErrors = $MessageErrors;
        $this->PersonResponse = $PersonResponse;
        $this->stringHandless = $stringHandless;
    }

    public function index()
    {
        try {
            $Form = Form::select(
                'forms.form_doc',
                'forms.form_number',
                'forms.created_at',
                'forms.updated_at',
                'awarded.awd_doc',
                'awarded.awarded_at',
                'awarded.awd_was_awd',
                'forms.form_email'
            )->where("form_doc", "<>", "")
                ->join(
                    "awarded",
                    "awarded.awd_doc",
                    "=",
                    "forms.form_id"
                )
                ->paginate();
            return $this->PersonResponse->returnResponsePaginate($Form);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function show($id)
    {
        try {
            $Form = Form::select(
                'forms.form_doc',
                'forms.form_number',
                'forms.created_at',
                'forms.updated_at',
                'awarded.awd_doc',
                'awarded.awarded_at',
                'awarded.awd_was_awd',
                'forms.form_email'
            )->where([
                ["forms.form_doc", "<>", ""],
                ["forms.sform_id", "=", $id]
            ])
                ->join(
                    "awarded",
                    "awarded.awd_doc",
                    "=",
                    "forms.form_id"
                )->get();
            return $this->PersonResponse->returnResponse($Form);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                "form_doc" => "required|string|min:11|max:14",
                "form_email" => "required|string|min:5|max:100"
            ];
            if ($this->validate($request, $rules)) {
                $form_number = Form::where("form_doc", "<>", "")->count() + 1;
                $form_doc = $this->stringHandless->mask_CPF_CNPJ($request->form_doc);
                $form_email = strtolower($request->form_email);
                $Form = Form::select("form_doc")->where("form_doc", "=", $form_doc)->first();
                if (isset($Form->form_doc)) {
                    return response()->json(["message" => "Já se encontra o registro na tabele, nada será salvo"], 420);
                }
                $Form = Form::create([
                    "form_number" => $form_number,
                    "form_doc" => $form_doc,
                    "form_email" => $form_email,
                ]);
                $awd_doc = intval($Form->form_id);
                Awarded::create([
                    'awd_doc' => $awd_doc
                ]);
                return response()->json(["message" => "Registro criado com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
            }
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                "form_doc" => "required|string|min:11|max:14",
                "form_email" => "required|string|min:5|max:100",
            ];
            if ($this->validate($request, $rules)) {
                $Form = Form::find($id);
                if (!isset($Form)) {
                    return response()->json(["message" => "Não encontramos o registro solicitado"], 420);
                }
                $form_doc = $this->stringHandless->mask_CPF_CNPJ($request->form_doc);
                $form_email = strtolower($request->form_email);
                $Form->update([
                    "form_doc" => $form_doc,
                    "form_email" => $form_email,
                    "updated_at" => Carbon::now(env("APP_TIMEZONE"))
                ]);
                return response()->json(["message" => "Registro atualizado com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
            }
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function destroy($id)
    {
        try {
            $Form = Form::find($id);
            Awarded::where("awarded.awd_doc", $id)->delete();
            Form::destroy($id);
            return response()->json(["message" => "Registro excluído com sucesso", "data" => env("APP_DEBUG") === true ? $Form : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
    public function count()
    {
        try {
            $count = Form::join(
                "awarded",
                "awarded.awd_doc",
                "=",
                "forms.form_id"
            )->count();
            return $this->PersonResponse->returnResponseArray([
                "count_forms" => $count
            ]);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
}
