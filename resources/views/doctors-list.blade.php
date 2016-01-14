@extends('layout\layout')

@section('content')
<div class="row">
    <div class="columns small-10 small-centered">
        <a href="/" class="backurl" style="margin-bottom: 30px;display: block">&larr; Назад</a>
    </div>
</div>
<div class="row">
    <div class="columns small-6 small-offset-1">
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
                        <input type="hidden" value="{{$doctor->id}}" name="del_doctor_id"/>
                        <input name="del_doctor" type="submit" value="удалить" class="button del-button alert"/>
                    </form>
                </td>
            </tr>

              @endforeach
        </table>
        {!!$doctors->render()!!}

    </div>
    <div class="columns small-4 end">
        <select name="spec_list" class="spec_list" id="">
            @foreach ($spec as $s)
                <option value="{{$s->id}}">{{$s->name}}</option>
            @endforeach
        </select>

    </div>
</div>
@endsection
