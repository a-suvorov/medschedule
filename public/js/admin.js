$(document).ready(function(){

$.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            weekHeader: 'Нед',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };


    $(".doctors-list__link").on('click', function(){
        if ($(".admin-bar__doctor-id").length > 0){
             $(".admin-bar__doctor-id").val($(this).attr("data-id"));
        }
    })

	  $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
	  );

      $(".message_data_priem").datepicker();

	  $(".admin-bar__input-date").datepicker({
              onSelect: function(dateText) {
                //alert("Selected date: " + dateText + "; input's current value: " + this.value);
                $(".schedule-table").html("<img style='width:200px; margin: 0 auto;' src='../images/loading.gif'>")
                /*********************************/
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });
                $.ajax({
                            type: "POST",
                            url: "/getschedule",
                            data: "doctor_id="+$link.attr("data-id")+
                                  "&date_priem="+dateText,
                            success: function(data){
                                $(".schedule-table").html(data);
                            }
                        }
                );
                /********************************/

               }
            }
        );



	  $('.admin-bar__input-time').mask('00:00');
      $('.message_time_priem').mask('00:00');

})

