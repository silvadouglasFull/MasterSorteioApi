<?php

namespace App\Http\Middleware;

use Carbon\Carbon;

class GenerateDynamicKey
{
    public function generate()
    {
        $now = Carbon::now(env("APP_TIMEZONE"))->format("m-y-d");
        $APP_KEY = env('APP_KEY');
        $Authentication = md5($APP_KEY . $now);
        return $Authentication;
    }
}
