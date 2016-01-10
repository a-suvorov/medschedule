<?php namespace App\Http\Controllers;

use App\Pacient;
use App\PacientManager;
use App\Schedule;
use App\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

    public function index(Request $request){
        if (Session::has("user_id") || (Auth::check())){
            /*
             * Запись нового пациента, если пришел запрос
             */
            $errorMessage = "";
            $successMessage = "";
            if ($request->has("save")){
                $sched = Schedule::find($request->input('sched_id'));
                if (!($sched->pacient_id)) {
                    $sched->pacient_id = $request->input('user_id');
                    $sched->save();
                    $successMessage = "Запись к врачу {$sched->doctor->name} на дату ".date("d.m.Y", strtotime($sched->data_priem))." в {$sched->time_priem} часов успешно произведена";
                }
                else {
                    $errorMessage = "Запись невозможна.";
                }
            }
            /*****************************************************/
            $data = array();
            if (Auth::check()) {  // вход выполнен в административную часть - то роль админа получаем пользователя из Users
                $data["is_admin"] = true;
            }
            else {
                $data["is_admin"] = false; // полаем пользователя из Pacients
                $pacient = Pacient::find(Session::get("user_id"));
                $data["user_id"] = $pacient->id;
                $data["user_fullname"] = implode(" ", array($pacient->fam, $pacient->im, $pacient->ot)); //объединяем в одну строку
            };
            /*
             * получаем список специализаций и "привязанных" к ним врачей
             */
            $specializations = Specialization::all();
            foreach ($specializations as $spec){
                $doctors = $spec->doctors;
                if ($doctors->toArray()) $data["doctors"][$spec->name] = $doctors;
            }

            /*
             * здесь передаем данные пациента и получаем данные по врачам
             */
            return view("home", ["data" => $data,"success" => $successMessage, "error" => $errorMessage]);
        }
        else return view("auth");
    }

    public function login(Request $request){
        $arRes = array();
        $pacient = new Pacient([
            'fam' => mb_strtoupper($request->input("fam")),
            'im' => mb_strtoupper($request->input("im")),
            'ot' => mb_strtoupper($request->input("ot")),
            'dr' => date("Y-m-d",strtotime($request->input("dr")))
        ]);

        $PacientManager = App::make("App\PacientManager"); //внедрение зависимости создаем класс и автоматом связываем реализацию с интерфейсом через биндинг

        $info = $PacientManager->getPacientInfo($pacient); // получение данных пациента
        $arRes["result"] = "false";

        //echo $info;
        if (is_array($info)){
            $curPacient =  $PacientManager->getPacientIfExist($info["n_polis"]); // получаем текущего пациента если есть
            if ($curPacient)  {$pacient = $curPacient; } // будем обновлять данные пациента если он уже есть в БД.
            $pacient->fill($info); //Обновляем данные о пациенте в БД
            $pacient->phone = $request->input("phone");
            $pacient->save();

            Session::put("user_id", $pacient->id);
            $arRes["result"] = "true";
            echo json_encode($arRes);
        }
        else {
            if (is_numeric($info)) $arRes["error"][] = $this->getErrorMessageFromCode($info);
                else $arRes["error"][] = "Человек с такими данными не найден в базе ОМС";
            echo json_encode($arRes);
        };

    }

    private function getErrorMessageFromCode($code){
    //echo $code;
    $error_codes=array(
    1 => 'CURLE_UNSUPPORTED_PROTOCOL',
    2 => 'CURLE_FAILED_INIT',
    3 => 'CURLE_URL_MALFORMAT',
    4 => 'CURLE_URL_MALFORMAT_USER',
    5 => 'CURLE_COULDNT_RESOLVE_PROXY',
    6 => 'CURLE_COULDNT_RESOLVE_HOST',
    7 => 'CURLE_COULDNT_CONNECT',
    8 => 'CURLE_FTP_WEIRD_SERVER_REPLY',
    9 => 'CURLE_REMOTE_ACCESS_DENIED',
    11 => 'CURLE_FTP_WEIRD_PASS_REPLY',
    13 => 'CURLE_FTP_WEIRD_PASV_REPLY',
    14=>'CURLE_FTP_WEIRD_227_FORMAT',
    15 => 'CURLE_FTP_CANT_GET_HOST',
    17 => 'CURLE_FTP_COULDNT_SET_TYPE',
    18 => 'CURLE_PARTIAL_FILE',
    19 => 'CURLE_FTP_COULDNT_RETR_FILE',
    21 => 'CURLE_QUOTE_ERROR',
    22 => 'CURLE_HTTP_RETURNED_ERROR',
    23 => 'CURLE_WRITE_ERROR',
    25 => 'CURLE_UPLOAD_FAILED',
    26 => 'CURLE_READ_ERROR',
    27 => 'CURLE_OUT_OF_MEMORY',
    28 => 'Извините, база ОМС на данный момент недоступна.<br> Попробуйте зайти позже.',
    30 => 'CURLE_FTP_PORT_FAILED',
    31 => 'CURLE_FTP_COULDNT_USE_REST',
    33 => 'CURLE_RANGE_ERROR',
    34 => 'CURLE_HTTP_POST_ERROR',
    35 => 'CURLE_SSL_CONNECT_ERROR',
    36 => 'CURLE_BAD_DOWNLOAD_RESUME',
    37 => 'CURLE_FILE_COULDNT_READ_FILE',
    38 => 'CURLE_LDAP_CANNOT_BIND',
    39 => 'CURLE_LDAP_SEARCH_FAILED',
    41 => 'CURLE_FUNCTION_NOT_FOUND',
    42 => 'CURLE_ABORTED_BY_CALLBACK',
    43 => 'CURLE_BAD_FUNCTION_ARGUMENT',
    45 => 'CURLE_INTERFACE_FAILED',
    47 => 'CURLE_TOO_MANY_REDIRECTS',
    48 => 'CURLE_UNKNOWN_TELNET_OPTION',
    49 => 'CURLE_TELNET_OPTION_SYNTAX',
    51 => 'CURLE_PEER_FAILED_VERIFICATION',
    52 => 'CURLE_GOT_NOTHING',
    53 => 'CURLE_SSL_ENGINE_NOTFOUND',
    54 => 'CURLE_SSL_ENGINE_SETFAILED',
    55 => 'CURLE_SEND_ERROR',
    56 => 'CURLE_RECV_ERROR',
    58 => 'CURLE_SSL_CERTPROBLEM',
    59 => 'CURLE_SSL_CIPHER',
    60 => 'CURLE_SSL_CACERT',
    61 => 'CURLE_BAD_CONTENT_ENCODING',
    62 => 'CURLE_LDAP_INVALID_URL',
    63 => 'CURLE_FILESIZE_EXCEEDED',
    64 => 'CURLE_USE_SSL_FAILED',
    65 => 'CURLE_SEND_FAIL_REWIND',
    66 => 'CURLE_SSL_ENGINE_INITFAILED',
    67 => 'CURLE_LOGIN_DENIED',
    68 => 'CURLE_TFTP_NOTFOUND',
    69 => 'CURLE_TFTP_PERM',
    70 => 'CURLE_REMOTE_DISK_FULL',
    71 => 'CURLE_TFTP_ILLEGAL',
    72 => 'CURLE_TFTP_UNKNOWNID',
    73 => 'CURLE_REMOTE_FILE_EXISTS',
    74 => 'CURLE_TFTP_NOSUCHUSER',
    75 => 'CURLE_CONV_FAILED',
    76 => 'CURLE_CONV_REQD',
    77 => 'CURLE_SSL_CACERT_BADFILE',
    78 => 'CURLE_REMOTE_FILE_NOT_FOUND',
    79 => 'CURLE_SSH',
    80 => 'CURLE_SSL_SHUTDOWN_FAILED',
    81 => 'CURLE_AGAIN',
    82 => 'CURLE_SSL_CRL_BADFILE',
    83 => 'CURLE_SSL_ISSUER_ERROR',
    84 => 'CURLE_FTP_PRET_FAILED',
    84 => 'CURLE_FTP_PRET_FAILED',
    85 => 'CURLE_RTSP_CSEQ_ERROR',
    86 => 'CURLE_RTSP_SESSION_ERROR',
    87 => 'CURLE_FTP_BAD_FILE_LIST',
    88 => 'CURLE_CHUNK_FAILED');

        return $error_codes[$code];


    }


    public function logout(){
        Session::forget("user_id");
        Auth::logout();
        return redirect("/");
    }

    public function getSchedule(Request $request){
        /*
         * проверяем возможен ли доступ, авторизирован ли пользователь
         */
        if (Session::has("user_id") || Auth::check()){
            /*
             * здесь делаем выборку расписания и отдаем шаблон
             */
            $doctor_id = $request->input("doctor_id");
            $curdate =  Auth::check() ? date("Y-m-d", strtotime($request->input("date_priem"))) : date("Y-m-d");
            /*
             * лучаем даты начала и конца недели и делаем выборку из БД
             */

            $scheds = Schedule::where("doctor_id",$doctor_id)
                                ->whereBetween('data_priem',$this->getStartEndWeek($curdate))
                                ->orderBy('data_priem')
                                ->orderBy('time_priem')
                                ->get();
            /*
             * если расписание есть, то формируем массив удобный для вывода в шаблон
             */
            $result = ($scheds->toArray()) ? $formatedSchedule = $this->formatSchedule($scheds, $curdate): array();
            return view("schedule",["schedule" => $result]);
        }
    }

    private function getStartEndWeek($curdate){
        $curdate = strtotime($curdate);
        if (date("w",$curdate) != 1) $startWeek= date("Y-m-d" , strtotime("last Monday",$curdate));
                                     else $startWeek= date("Y-m-d" , $curdate);
        $endWeek= date("Y-m-d" , strtotime("Sunday",$curdate));
        /*
         * Если воскресенье, то прибавляем 7 дней, чтобы отобразить следующую неделю
         */
        if (date("w",$curdate) == 0){
            $startWeek = date("Y-m-d" , strtotime($startWeek . "+7 day"));
            $endWeek = date("Y-m-d" , strtotime($endWeek . "+7 day"));
        }
        return [$startWeek, $endWeek];
    }

    private function formatSchedule($scheds, $curdate){
        $week = array();
        $maxHeaders = $this->getStartEndWeek($curdate);
        $startDate = $maxHeaders[0];
        for ($i = 1; $i <= 7; $i++){
            switch ($i){
                case 1: $week[$i]["header"] =  "Пн<br>"; break;
                case 2: $week[$i]["header"] =  "Вт<br>"; break;
                case 3: $week[$i]["header"] =  "Ср<br>"; break;
                case 4: $week[$i]["header"] =  "Чт<br>"; break;
                case 5: $week[$i]["header"] =  "Пт<br>"; break;
                case 6: $week[$i]["header"] =  "Сб<br>"; break;
                case 7: $week[$i]["header"] =  "Вс<br>"; break;
            }
//            echo $startDate."<br>";
            $week[$i]["header"] .= date("d.m", strtotime($startDate));
            $startDate = date("Y-m-d",strtotime($startDate."+1 day"));
        }
        foreach ($scheds as $sched){
            $datatime_priem = strtotime($sched->data_priem);
            $dm_priem = date("d.m", $datatime_priem);
            switch (date("w", $datatime_priem)){
                case 1:
                    $week[1]["data"][] = $sched;
                    break; //понедельник
                case 2:
                    $week[2]["data"][] = $sched;
                    break; //вторник
                case 3:
                    $week[3]["data"][] = $sched;
                    break; //среда
                case 4:
                    $week[4]["data"][] = $sched;
                    break; //четверг
                case 5:
                    $week[5]["data"][] = $sched;
                    break; //пятница
                case 6:
                    $week[6]["data"][] = $sched;
                    break; //суббота
                case 0:
                    $week[7]["data"][] = $sched;
                    break; //воскресенье
            }
        }
        return $week;
    }

}
