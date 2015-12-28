<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Pacient;
use App\PacientManager;
use App\Parsers\OmsParser;

Route::get('/', function(){
    $pacient = new Pacient(['fam'=>"Суворов",'im'=>"Александр",'ot'=>"Юрьевич",'dr'=>"1986.04.15"]);

    $PacientManager = App::make("PacientManager"); //внедрение зависимости создаем класс и автоматом связываем реализацию с интерфейсом через биндинг
    $info = $PacientManager->getPacientInfo($pacient);
    $pacient->fill($info);
    $pacient->save();
});

/*Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/
