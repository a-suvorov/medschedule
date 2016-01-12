@if ($schedule)
        <ul class="small-block-grid-7 schedule-table__week">
            @foreach ($schedule as $sch)
            <li class="schedule-table__day-list">
              <span class="schedule-table__header">{!!$sch["header"]!!}</span>
              @if (isset($sch["data"]))
              <ul>
                @foreach ($sch["data"] as $priem)
                  
                  @if ($priem->pacient_id)
                  <li class="red schedule-table__cell">
                      {{$priem->time_priem}}
                      @if ($priem->pay == 1)
                        <span class="pay_flag">(платный)</span>
                      @endif
                  </li>
                  @else
                  <li class="green schedule-table__cell">
                      <a href="javascript:void(0)" data-sched-id="{{$priem->id}}">
                          {{$priem->time_priem}}
                          @if ($priem->pay == 1)
                          <span class="pay_flag">(платный)</span>
                          @endif
                      </a></li>
                  @endif
                @endforeach
              </ul>
              @endif
            </li>
            @endforeach
          </ul>
@else
    <div class="schedule-message">Извините, на текущий период записаться к данному специалисту через Интернет невозможно</div>
@endif