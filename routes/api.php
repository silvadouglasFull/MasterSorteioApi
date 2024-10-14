<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Exceptions\MessageErrors;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get("/key", function () {
    $MessageError = new MessageErrors();
    $Controller = new Controller($MessageError);
    $key = $Controller->getKey();
    return $key;
});
$APP_VERSION = env("APP_VERSION");
$prefix = "api_v" . substr($APP_VERSION, 0, 3);
$router->group(["prefix" => $prefix], function ($router) {
    $router->get('/Form', 'FormController@index');
    $router->get('/Form/count', 'FormController@count');
    $router->get('/Form/{id}', 'FormController@show');
    $router->post('/Form', 'FormController@store');
    $router->put('/Form/{id}', 'FormController@update');
    $router->delete('/Form/{id}', 'FormController@destroy');
    $router->get('/Awarded', 'AwardedController@index');
    $router->get('/Awarded/wasAwarded', 'AwardedController@wasAwarded');
    $router->get('/Awarded/{id}', 'AwardedController@show');
    $router->post('/Awarded', 'AwardedController@store');
    $router->put('/Awarded/{id}', 'AwardedController@update');
    $router->delete('/Awarded/{id}', 'AwardedController@destroy');
});
