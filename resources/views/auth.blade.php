<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Система записи к врачу через Интернет - Больница КНЦ РАН</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,300italic,600,600italic,700,700italic&subset=latin,latin-ext,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="bower_components/modernizr/modernizr.js"></script>
  </head>
  <body>
    <div class="main-wrapper">
      <div class="login-form">
        <form>
          <h1 class="main-h1">Система записи к врачу<br>Больница КНЦ РАН</h1>
          <div class="information">
            Пожалуйста, перед использованием системы ознакомьтесь с нашими <a href="#" data-reveal-id="rules">правилами</a>
          </div>
          <div class="login-form__data" >
            <input name="fam" class="login-form__fam" placeholder="Фамилия" value="" type="text">
            <input name="im" class="login-form__im" placeholder="Имя" value="" type="text">
            <input name="ot" class="login-form__ot" placeholder="Отчество" value="" type="text">
            <input name="dr" class="login-form__dr" placeholder="Дата рождения" value="" type="text">
            <input name="phone" class="login-form__phone" class="login-form__phone" placeholder="Моб. телефон" value="" type="text">
            <input name="_token" class="login-form__token" type="hidden" value="{{ csrf_token() }}">
            <div class="pers-info">
              <input class="pers-info__checkbox" placeholder="" value="" name="accept" id="accept" type="checkbox">
              <label class="pers-info__label" for="accept">С правилами ознакомлен и согласен</label>
            </div>
            <div class="login-form__error"></div>
          </div>
          <a href="javascript:void(0)" class="login-form__enter">Войти</a>
        </form>
      </div>
    </div>

    <div class="rules reveal-modal medium" id="rules" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
        <span class="rules-title">Правила работы с системой</span>
        <ul class="rules-list">
          <li class="rules-list__item">Используя систему, Вы соглашаетесь на обработку персональных данных в лечебном учреждении</li>
          <li class="rules-list__item">Предварительная запись к врачу осуществляется только при плановом посещении. Если у Вас есть неотложные показания, следует обращаться в регистратуру поликлиники</li>
          <li class="rules-list__item">Предварительная запись к врачу через интернет осуществляется на текущую неделю и доступна начиная с воскресенья предыдущей недели</li>
          <li class="rules-list__item">Указанный Вами при входе в систему телефон должен быть действующим. Телефон может быть использован для связи с пациентом, для подтверждения записи.</li>
          <li class="rules-list__item">
            Необходимо не менее чем за 20 минут до начала приема обратиться в регистратуру, подтвердив свое намерение и предъявив полис и паспорт, получить талон на прием
          </li>
          <li class="rules-list__item">Если во время заполнения данных на сайте эти данные не совпали с данными в базе ОМС или полис оказался недействующим, то, к сожалению, Вы не сможете записаться по Интернету. В этом случае необходимо обратиться в регистратуру поликлиники</li>
          <li class="rules-list__item">В случае, если Вы по каким либо причинам не сможете прийти на приём к врачу в назначенное время, необходимо сообщить об этом в регистратуру по тел. 79-390
          </li> 
        </ul>

        <a class="button rules__ok close-reveal-modal" aria-label="Close">Ok</a>

        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
    </div>
    <div class="loading-bg"><img class="loading-img" src="images/loading.gif"></div>
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
