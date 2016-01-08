<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Pacient extends Model{
    protected $fillable = ['fam','im','ot','dr','phone','n_polis','s_polis','kod_lpu','strahovaya'];

    public function schedules(){
        return $this->hasMany("App\Doctor", "spec_id");
    }
} 