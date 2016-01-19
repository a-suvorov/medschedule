<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.01.16
 * Time: 15:57
 */

namespace App\Handlers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SystemListern {
    public function PeopleWriteToVisit($event){
        $sched = $event->sched;
        Mail::send('emails.new_visit', array('sched' => $sched), function($message){
            $message->to('reg@medknc.ru')->subject('Записался новый пациент');
            $message->to('admin@medknc.ru')->subject('Записался новый пациент');
        });
        //var_dump($event);
    }

    public function subscribe($events){
        $events->listen('App\Events\PeopleWriteToVisit', 'App\Handlers\SystemListern@PeopleWriteToVisit');
        //$events->listen('App\Events\UserLoggedOut', 'UserEventHandler@onUserLogout');
    }
} 