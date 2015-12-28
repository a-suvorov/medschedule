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


Route::any("/", function(){
    $pacient = new Pacient(['fam'=>"Суворов",'im'=>"Александр",'ot'=>"Юрьевич",'dr'=>"1986-04-15"]);
    $PacientManager = App::make("App\PacientManager"); //внедрение зависимости создаем класс и автоматом связываем реализацию с интерфейсом через биндинг

    $curPacient =  $PacientManager->getPacientIfExist($pacient); // получаем текущего пациента если есть
    if ($curPacient)  {$pacient = $curPacient; echo "true";} // будем обновлять данные пациента если он уже есть в БД.
    $info = $PacientManager->getPacientInfo($pacient); // получение данных пациента
    $pacient->fill($info);
    $pacient->save();
});

