<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.12.15
 * Time: 21:36
 */

namespace App;


use App\Parsers\IParser;

class PacientManager {
    private $parser;
    function __construct(IParser $parser){
        $this->parser = $parser;
    }

    function getPacientInfo(Pacient $pacient){
        $parser = $this->parser;
        return $parser::getPacientInfo($pacient);
    }

    /*
     * Проверяем записывался ли уже данный пациент ранее
     */
    function  getPacientIfExist($num_polis){
        $resultPacient =  Pacient::where("n_polis",$num_polis)->first();
                                    //->where("im","=",$pacient->im)
                                    //->where("ot","=",$pacient->ot)
                                    //->where("dr","=",$pacient->dr)

        return ($resultPacient) ? $resultPacient : false;

    }
} 