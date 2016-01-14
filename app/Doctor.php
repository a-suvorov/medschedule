<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Doctor extends Model{
    protected $fillable = ['name','description','fullname'];
    public function spec()
    {
        return $this->belongsTo('App\Specialization');

    }

    public function schedules(){
        return $this->hasMany("App\Schedule");
    }
} 