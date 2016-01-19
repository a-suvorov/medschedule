@extends('layout.layout')

@section('content')
<div class="columns">
    <div class="row">
        <div class="columns small-10 small-centered">
            <a href="/" class="backurl" style="margin-bottom: 30px;display: block">&larr; Назад</a>
            <table>
                <tr>
                    <th>Пациент</th>
                    <th>Дата приема</th>
                    <th>Время приема</th>
                    <th>Платный/бесплатный</th>
                    <th>Полис</th>
                    <th>Страховая орг.</th>
                    <th>Код ЛПУ</th>
                    <th>Врач</th>
                </tr>
                 @foreach ($data as $sched)
                <tr>
                    <td>{{$sched->pacient->fam}} {{$sched->pacient->im}} {{$sched->pacient->ot}}</td>
                     <td>{{date("d.m.Y", strtotime($sched->data_priem))}}</td>
                  <td>{{ date("H:i", strtotime($sched->time_priem))}}</td>
                  <td>
                        @if ($sched->pay == 1)
                            платный
                        @else
                            бесплатный
                        @endif
                  </td>
                  <td>{{$sched->pacient->s_polis}} {{$sched->pacient->n_polis}}</td>
                  <td>{{$sched->pacient->strahovaya}}</td>
                  <td>{{$sched->pacient->kod_lpu}}</td>
                  <td>{{$sched->doctor->name}}</td>
                </tr>
                  @endforeach
            </table>
            {!!$data->render()!!}
        </div>
    </div>
</div>
@endsection
