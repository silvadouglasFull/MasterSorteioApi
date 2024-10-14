<?php

namespace App\Http\Controllers;

use App\Http\Middleware\GenerateDynamicKey;
use App\Exceptions\MessageErrors;
use Laravel\Lumen\Routing\Controller as BaseController;


class Controller extends BaseController
{
    /**
     * The Authentication guard factory instance.
     */
    protected $Authentication;
    protected $messageErrors;
    public function __construct(MessageErrors $messageErrors)
    {
        $this->Authentication = new GenerateDynamicKey();
        $this->messageErrors = $messageErrors;
    }
    public function getKey()
    {
        if (!env("APP_DEBUG")) {
            return;
        }
        $AuthenticationMD5 = $this->Authentication->generate();
        return response()->json(['message' => $AuthenticationMD5], 200);
    }
    public function statusconection()
    {
        return response()->json(['message' => "is conection"], 200);
    }
}
