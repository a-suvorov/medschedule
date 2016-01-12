// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();


$(document).ready(function(){
	/*Телефон на главной*/
	 $('.login-form__phone').mask('(000) 000-00-00');
	 $('.login-form__dr').mask('00.00.0000');
	/*********************/
	/*******Загружаем расписание изменяем интрефейс(цвета выделения и т.д.)********/
	$(".doctors-list__link").on('click', function(){
		$link = $(this);
		$(".doctors-list__item").removeClass("active");
		$link.parent("li").addClass("active");
		$(".page-title__name").html($link.html());
		$(".schedule-table").html("<img style='width:200px; margin: 0 auto;' src='../images/loading.gif'>")

		/*
		 * Запускаем ajax для получения расписания
		 */
		 $.ajaxSetup({
	        headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
	    });
		 $.ajax({
                    type: "POST",
                    url: "/getschedule",
                    data: "doctor_id="+$link.attr("data-id"),
                   	success: function(data){
                        $(".schedule-table").html(data);
                    }
                }
			);
		
	})
	/*******************************************************************************/
	/*************************Окно с подтверждением записи**************************/
	$('body').on("click", ".green a", function() {
		$(".message__doctor").html($(".doctors-list__item.active a").html());
		$(".message__time").html($(this).html());
		$("input[name='sched_id']").val( $(this).attr('data-sched-id') );
		$("input[name='user_id']").val( $('.username').attr('data-user-id') );
		
		$('#message').foundation('reveal','open');
	});
	/*******************************************************************************/
	$(".message__ok, .message__cancel").on("click", function(){
		if ($(this).hasClass("message__ok")) {
			//alert("отправляем запрос на добавление");
		}
		else {
			$('#message').foundation('reveal','close');
		};
	})

	/********Вход с систему*********/
	$(".login-form__enter").on('click', function(){
		$(".login-form__error").html('');
        var error = "";
		error = checkField();
		//console.log(error);
		if (error != ""){
            $(".loading-bg").hide();
            $(".login-form__error").html(error.join("<br>"));
        }
		else {
            $(".loading-bg").show();
			$.ajax({
                    type: "POST",
                    url: "/getpacient",
                    data: "fam="+$(".login-form__fam").val()+
                          "&im="+$(".login-form__im").val()+
                          "&ot="+$(".login-form__ot").val()+
                          "&dr="+$(".login-form__dr").val()+
                          "&phone="+$(".login-form__phone").val()+
                          "&_token="+$(".login-form__token").val()+
                          "&rules-checkbox="+$(".pers-info__checkbox").is(":checked"),
                    success: function(data){
                        msg = $.parseJSON(data);
                        if (msg['result'] == "true") window.location.reload();
                        else {
                            $message = "";
                            //получаем все сообщения об ошибках
                            msg['error'].forEach(function(element, index, array){
                                $message += element + "<br>";
                            });
                            $(".login-form__error").html($message);
                            $(".loading-bg").hide();
                        }
                    }
                }
			);
        }  //else

	})
	/******************************/
})
/*Проверка полей на стороне клиента*/
function checkField(){
	var error = [];
	if (!$(".login-form__fam").val()) error.push("Не заполнена Фамилия");
	if (!$(".login-form__im").val()) error.push("Не заполнено Имя");
	if (!$(".login-form__ot").val()) error.push("Не заполнено Отчество");
	if (!$(".login-form__dr").val()) error.push("Не заполнена дата рождения");
	if (!$(".login-form__phone").val()) error.push("Не заполнен телефон");
	if (!$(".pers-info__checkbox").is(":checked")) error.push("Не приняты правила сервиса"); 
	return error;
}