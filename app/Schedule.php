<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.12.15
 * Time: 23:00
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Schedule extends Model{
    //protected $fillable = ['name','description','fullname'];
    protected $table = "sched";
    public function doctor()
    {
        return $this->belongsTo('App\Doctor');
    }

    public function pacient()
    {
        return $this->belongsTo('App\Pacient');
    }

} 