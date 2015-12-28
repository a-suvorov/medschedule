<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 22:54
 */
/*
 *  Класс по проверки данных пациента на достоверность (может браться из БД или внешнего ресурса)
 */

namespace App\Parsers;


use App\Pacient;

interface IParser {
    static function getPacientInfo(Pacient $pacient);
}