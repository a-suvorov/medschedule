<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:14
 */

namespace App\Parsers;


use App\Pacient;

class TestParser implements  IParser {
    //private static $omsUrl = "http://omsmurm.ru/Home/SearchPolicy";
/*
    static private function isTruePolis($res){
        return mb_stripos($res, "действующий") === false ? false : true ;
    }
*/

    static function getPacientInfo(Pacient $pacient){
        return [
            "n_polis" => "нового образца",//trim(strip_tags($matches_value["value"][2])),
            "s_polis" => "123456789",//trim(strip_tags($matches_value["value"][1])),
            "kod_lpu" => "050",//trim(strip_tags($matches_value["value"][5])),
            "strahovaya" => "ОМС"//trim(strip_tags($matches_value["value"][0]))
        ];
    }
} 