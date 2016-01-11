<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{--<meta name="_token" content="{{ csrf_token()}}"/>---}}
    <title>Система записи к врачу через Интернет - Больница КНЦ РАН</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href='https://yastatic.net/jquery-ui/1.11.2/themes/ui-lightness/jquery-ui.min.css' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,300italic,600,600italic,700,700italic&subset=latin,latin-ext,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="bower_components/modernizr/modernizr.js"></script>
  </head>
  <body>
    <div class="row header">
      <div class="columns">
        <a class="left" href="/">Больница КНЦ РАН - система записи к врачу</a>
        <div class="right">
          <span class="username" data-user-id="{{$data['user_id']}}">{{ $data["user_fullname"] }}</span>
          &nbsp;(<a class="exit" href="/logout">Выход</a>)
        </div>
        
      </div>
    </div>
    <div class="row content">

      @yield('content')

    </div>


    <div class="row footer">
      <div class="columns copyright">
        &copy; Больница КНЦ РАН
      </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <script src="js/jquery.mask.min.js"></script>
    <script src="https://yastatic.net/jquery-ui/1.11.2/jquery-ui.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/admin.js"></script>
  </body>
</html>

