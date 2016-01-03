<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:14
 */

namespace App\Parsers;


use App\Pacient;

class OmsParser implements  IParser {
    private static $omsUrl = "http://omsmurm.ru/Home/SearchPolicy";

    static private function isTruePolis($res){
        return mb_stripos($res, "действующий") === false ? false : true ;
    }

    static private function getPacientData($res){
        //preg_match_all('/<tr>[\s\S]*<td class="fieldName">[\s\S]*<\/td>[\s\S]*<td>(?<value>[\s\S]*)<\/td>[\s\S]*<\/tr>/U',$res,$matches);
        //preg_match_all('/<tr>[\s\S]{1,3}<td class="fieldName"[\s\S]{0,30}>[\s\S]*<\/td>[\s\S]{1,3}<td>(?<value>[\s\S]*)<\/td>/U',$res,$matches);
        preg_match_all('/<table>[\s\S]*<\/table>/U',$res,$matches);
        $dataTable = $matches[0][2];
        preg_match_all('/<td[\s\S]*>[\s\S]*<\/td>[\s\S]*<td>(?<value>[\s\S]*)<\/td>/U',$dataTable, $matches_value);
        //print_r($matches_value["value"]);
        return [
                "n_polis" => trim(strip_tags($matches_value["value"][2])),
                "s_polis" => trim(strip_tags($matches_value["value"][1])),
                "kod_lpu" => trim(strip_tags($matches_value["value"][5])),
                "strahovaya" => trim(strip_tags($matches_value["value"][0]))
        ];
    }

    static function getPacientInfo(Pacient $pacient){
        $curl = curl_init(); //инициализация сеанса
        curl_setopt($curl, CURLOPT_URL, self::$omsUrl); //урл сайта к которому обращаемся
        curl_setopt($curl, CURLOPT_HEADER, 1); //выводим заголовки
        curl_setopt($curl, CURLOPT_POST, 1); //передача данных методом POST
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //теперь curl вернет нам ответ, а не выведет
        curl_setopt($curl, CURLOPT_POSTFIELDS, //тут переменные которые будут переданы методом POST
            array (
                'SecondName' => $pacient->fam,
                'FirstName' => $pacient->im,
                'MiddleName' => $pacient->ot,
                "searchType" => "2",  //говорит о том что ищем по ФИО
                'Birthday' => '15.04.1986'
                 //это на случай если на сайте, к которому обращаемся проверяется была ли нажата кнопка submit, а не была ли оправлена форма
            ));
        curl_setopt($curl, CURLOPT_USERAGENT, 'MSIE 5'); //эта строчка как-бы говорит: "я не скрипт, я IE5" :)
        curl_setopt ($curl, CURLOPT_REFERER, "http://ya.ru"); //а вдруг там проверяют наличие рефера
        $res = curl_exec($curl);
        //echo $res;
        if (self::isTruePolis($res)){
            $data = self::getPacientData($res);
            //$pacient->fill($data);
            //print_r($data)
            return $data;
        }
        else return false;
    }
} 