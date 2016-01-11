<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Specialization extends Model{
    protected $table = 'spec';

    public function doctors(){
       return $this->hasMany("App\Doctor", "spec_id");
    }
}