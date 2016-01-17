К врачу {{$sched->doctor->name}} ({{$sched->doctor->spec->name}}) записался новый пациент:<br>
<b>Дата приема:</b> {{date("d.m.Y",strtotime($sched->data_priem))}}<br>
<b>Время приема:</b> {{date("H:i", strtotime($sched->time_priem))}}<br>
<b>ФИО:</b> {{$sched->pacient->fam}} {{$sched->pacient->im}} {{$sched->pacient->ot}}<br>
<b>Дата рождения:</b> {{date("d.m.Y",strtotime($sched->pacient->dr))}}<br>
<b>Код ЛПУ:</b> {{$sched->pacient->kod_lpu}}<br>
<b>Номер полиса:</b> {{$sched->pacient->n_polis}}<br>
<b>Серия полиса:</b> {{$sched->pacient->s_polis}}<br>
<b>Страховая компания:</b> {{$sched->pacient->strahovaya}}<br>

