<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageErrors;
use App\Http\Core\PersonResponse;
use App\Models\Awarded;
use App\Models\Form;
use Carbon\Carbon;
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
            $Awarded = Awarded::find($id);
            return $this->PersonResponse->returnResponseArray([$Awarded]);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'awd_doc' => 'required|numeric',
            ];
            if ($this->validate($request, $rules)) {
                $awd_doc = intval($request->awd_doc);
                $Awarded = Awarded::create([
                    'awd_doc' => $awd_doc
                ]);
                return response()->json(["message" => "Registro criado com sucesso", "data" => env("APP_DEBUG") ? $Awarded : ""], 200);
            }
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'awd_doc' => 'required|numeric',
            ];
            if ($this->validate($request, $rules)) {
                $awd_doc = intval($request->awd_doc);
                $Forms = Form::find($awd_doc);
                $Awarded = Awarded::find($id);
                if (!isset($Awarded) || !isset($Forms)) {
                    return response()->json(["message" => "Não encontramos o registro solicitado"], 420);
                }
                $Awarded->update([
                    'awd_doc' => $awd_doc
                ]);
                return response()->json(["message" => "Registro atualizado com sucesso", "data" => env("APP_DEBUG") ? $Awarded : ""], 200);
            }
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }

    public function destroy($id)
    {
        try {
            $Awarded = Awarded::find($id);
            $Awarded->delete(); // Use delete() em vez de destroy()
            return response()->json(["message" => "Registro excluído com sucesso", "data" => env("APP_DEBUG") ? $Awarded : ""], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
    public function wasAwarded()
    {
        try {
            $number = Awarded::where([[
                "awd_was_awd",
                "<>",
                1,
            ]])->count();
            $multiples = 1; // Base para múltiplos
            $max = 5000; // qtd de ganhadores * 1000
            $isMultiplie = false;
            for ($i = $multiples; $i <= $max; $i += $multiples) {
                if ($number % $i == 0) {
                    $isMultiplie = true;
                } else {
                    $isMultiplie = false;
                }
            }
            if (!$isMultiplie) {
                return response()->json(["message" => "Ainda não há registros suficiente para sortear um ganhador"], 420);
            }
            $Awarded = Awarded::select(
                'forms.form_doc',
                'forms.form_number',
                'forms.created_at',
                'forms.updated_at',
                'awarded.awd_id',
                'awarded.awd_was_awd',
                'awarded.awarded_at'
            )->where([
                [
                    "awd_was_awd",
                    "<>",
                    1,
                ]
            ])
                ->join(
                    "forms",
                    "forms.form_id",
                    "=",
                    "awarded.awd_doc"
                )
                ->inRandomOrder()
                ->limit(1)
                ->first();
            if (!isset($Awarded->awd_id)) {
                return response()->json(["message" => "Por enquanto não há ganhadores"], 200);
            }
            $awd_id = $Awarded->awd_id;
            Awarded::where(
                "awd_id",
                "=",
                $awd_id
            )->update([
                "awd_was_awd" => 1,
                "updated_at" => Carbon::now(env("APP_TIMEZONE")),
                "awarded_at" => Carbon::now(env("APP_TIMEZONE"))
            ]);
            $Awarded = Awarded::select(
                'forms.form_doc',
                'forms.form_number',
                'forms.form_email',
                'forms.created_at',
                'forms.updated_at',
                'awarded.awd_doc',
                'awarded.awd_was_awd',
                'awarded.awarded_at'
            )->where([
                [
                    "awd_id",
                    "=",
                    $awd_id
                ]
            ])
                ->join(
                    "forms",
                    "forms.form_id",
                    "=",
                    "awarded.awd_doc"
                )->first();
            return $this->PersonResponse->returnResponseArray([$Awarded], 200);
        } catch (\Throwable $th) {
            return $this->MessageErrors->getMessageError($th);
        }
    }
}
