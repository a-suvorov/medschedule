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
        //$parser = $this->parser;
        return IParser($this->parser)::getPacientInfo($pacient);
    }
} 