@extends('layout\layout')

@section('content')
      <div class="columns medium-3">
        <ul class="doctors-list">
         @foreach ($data["doctors"] as $key => $doctors)
            <li class="doctors-list__group">
                <span class="doctors-list__group-title">{{$key}}</span>
                <ul class="doctors-list__group-list">
                @foreach ($doctors as $doctor)
                             <li class="doctors-list__item"><a href="javascript:void(0)" data-id="{{$doctor->id}}" class="doctors-list__link">{{$doctor->name}}</a></li>
                    @endforeach

                </ul>
            </li>
         @endforeach

        </ul>
      </div>
      <div class="columns medium-8 medium-offset-1">
        <div class="page-title">Расписание врача <span class="page-title__name"></span></div>
        @if ($data["is_admin"])
        <div class="admin-bar">
          <form action="" method="post">
              <div class="admin-bar__date">Выбор даты: <input name="data_priem" class="admin-bar__input-date" type="text"></div>
              <div class="admin-bar__time">Введите время: <input name="time_priem" class="admin-bar__input-time" type="text" placeholder="__:__"></div>
              <input type="hidden" name="doctor_id" class="admin-bar__doctor-id" value=""/>
              <input type="hidden" name="_token" class="" value="{{csrf_token()}}"/>
              <input class="button success add_datatime" name="add_datatime" value="Добавить" type="submit">
          </form>
        </div>
        @endif
        <div class="description">
          <div class="description-inner">
            <div class="green description__block"></div><span class="description__block2"> - Время свободно, можно записаться</span>
          </div>
          <div class="description-inner">
            <div class="red description__block"></div><span class="description__block2"> - Время занято, запись невозможна</span>
          </div>
          <div class="description-inner">
            <div class="gray description__block"></div><span class="description__block2"> -  Записи нет</span>
          </div>
          
        </div>

          @if ($success)
              <div data-alert class="alert-box success radius">
                  {{$success}}
                  <a href="#" class="close">&times;</a>
              </div>
          @endif

          @if ($error)
              <div data-alert class="alert-box alert radius">
                  {{$error}}
                  <a href="#" class="close">&times;</a>
              </div>
          @endif
        <div class="schedule-table">
            <div class="schedule-message">
                Приветствуем Вас в онлайн системе записи к врачу через Интернет.<br>На данный момент Вы не выбрали врача, к которому хотите записаться.<br>
                Для отображения расписания приема требуемого специалиста, выберете его слевой стороны.
            </div>
        </div>
      </div>

    @if ($data["is_admin"])
    <div id="message" class="message reveal-modal small"  data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <form action="" method="post">
            <h2>Информация о приеме</h2>
            <input type="hidden" name="priem_id" value="">
            <label for="">Дата</label>
            <input type="text" name="data_priem" value="">
            <label for="">Время</label>
            <input type="text" name="time_priem" value="">
            <input type="submit" class="button success" value="Обновить" name="update_priem"/>
            <input type="submit" class="button alert" value="Удалить" name="del_priem"/>
        </form>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    @else
    <div id="message" class="message reveal-modal tiny"  data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <form action="" method="post">
            <div class="message__content">
                Хотите записаться на прием<br> к <span class="message__doctor"></span> в <span class="message__time"></span> часов
                <input type="hidden" name="sched_id" value=""/>
                <input type="hidden" name="user_id" value=""/>
                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
            </div>
            <div class="message__action">
                <input type="submit" name="save" href="javascript:void(0)" class="button message__ok success green" value="Да">
                <a href="javascript:void(0)" class="button message__cancel success green">Нет</a>
            </div>
        </form>
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    @endif
@endsection