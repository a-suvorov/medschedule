@extends('layout\layout')

@section('content')
<div class="columns">
    <div class="row">
        <div class="columns small-10 small-centered">
            <a href="/" class="backurl" style="margin-bottom: 30px;display: block">&larr; Назад</a>
        </div>
    </div>
    <div class="row">
        <div class="columns small-5 small-offset-1">
            <table style="width: 100%">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Специализация</th>
                    <th></th>
                </tr>
                @foreach ($doctors as $doctor)
                <tr>
                    <td>{{$doctor->id}}</td>
                    <td>{{$doctor->name}}</td>
                    <td>{{$doctor->spec->name}}</td>
                    <td class="action_td">
                        <form>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" value="{{$doctor->id}}" name="del_doctor_id"/>
                            <input name="del_doctor" type="submit" value="удалить" class="button del-button alert"/>
                        </form>
                    </td>
                </tr>

                  @endforeach
            </table>

            {!!$doctors->render()!!}

        </div>
        <div class="columns small-4 small-offset-1 end">
            <form method="post">
                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                <select name="spec_list" class="spec_list" id="" size="10">
                    @foreach ($spec as $s)
                        <option value="{{$s->id}}">{{$s->name}}</option>
                    @endforeach
                </select>
                <input name="del_spec" type="submit" value="удалить" class="button del-button alert"/>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="columns small-5 small-offset-1">
            <div class="add-doctor-form">
                <form action="" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                    <label for="add-doctor__name">Имя врача</label>
                    <input type="text" name="new_doctor_name" id="add-doctor__name"/>
                    <label for="add-doctor__spec">Специализация врача</label>
                    <select name="new_spec_id" id="add-doctor__spec">
                        @foreach ($spec as $s)
                        <option value="{{$s->id}}">{{$s->name}}</option>
                        @endforeach
                    </select>
                    <input name="add_doctor" type="submit" value="Добавить" class="button add-button success"/>
                </form>
            </div>
        </div>
        <div class="columns small-4 small-offset-1 end">
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                <label for="add-spec__name">Название специализации</label>
                <input type="text" name="new_spec_name" id="add-spec__name"/>
                <input name="add_spec" type="submit" value="Добавить" class="button add-button success"/>
            </form>
        </div>
    </div>
</div>
@endsection
