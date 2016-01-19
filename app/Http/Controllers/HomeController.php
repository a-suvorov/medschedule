<?php namespace App\Http\Controllers;

use App\Classes\Utils;
use App\Doctor;
use App\Events\PeopleWriteToVisit;
use App\Handlers\SystemListern;
use App\Pacient;
use App\PacientManager;
use App\Schedule;
use App\Specialization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        $subscriber = new SystemListern();
        Event::subscribe($subscriber);
		//$this->middleware('auth');
	}

    /*
     * вход администратора
     */
    public function admin(Request $request){
        $error = "";
        if ($request->has("enter")){
            Auth::attempt(['login' => $request->input("login"), 'password' => $request->input("password")]);
            /*User::create([
                'login' => $request->input("login"),
                'password' => bcrypt($request->input("password")),
            ]);*/
        }

        if (Auth::check()){
             return redirect("/");
        }
        else{
            return view("authadmin", ["error" => $error]);
        }

    }

    public function index(Request $request){
        if (Session::has("user_id") || (Auth::check())){
            $errorMessage = "";
            $successMessage = "";
            if (Auth::check()) {  //только для админа

                /*
                 * Обновление даты/времени приема если есть
                 */
                if ($request->has("update_priem")){
                    $updateSched = Schedule::find($request->input("sched_id"));
                    $updateSched->data_priem = date("Y-m-d", strtotime($request->input("message_data_priem")));
                    $updateSched->time_priem = $request->input("message_time_priem");
                    $updateSched->save();
                    $successMessage="Расписание успешно обновлено";
                }
                /*
                 * Удаление приема
                 */
                if ($request->has("del_priem")){
                    $delSched = Schedule::find($request->input("sched_id"));
                    $delSched->delete();
                    $successMessage="Время приема удалено";
                }

                /*
                 * Добавление нового приема если пришел запрос
                 */

                if ($request->has("add_datatime")){
                    $validator = Validator::make(
                        $request->all(),
                        ["data_priem"=> "required", "time_priem"=> "required", "doctor_id"=> "required"]
                    );
                    if ($validator->fails()){
                        $errorMessage = "Не все поля заполнены или невыбран врач";
                    }
                    else {
                        $schedLine = new Schedule();
                        $schedLine->data_priem =  date("Y-m-d", strtotime($request->input("data_priem")));
                        $schedLine->time_priem = $request->input("time_priem");
                        $schedLine->doctor_id = $request->input("doctor_id");
                        if ($request->has("pay")) $schedLine->pay = 1;
                        $schedLine->save();
                        $successMessage = "В расписание врача ".$schedLine->doctor->name." добавлен прием на дату ".date("d.m.Y", strtotime($schedLine->data_priem))." время {$schedLine->time_priem}";
                    }
                }

            }  // end if (Auth::check())


            /************************************************/
            /*
             * Запись нового пациента, если пришел запрос
             */

            if ($request->has("save")){
                if (!Auth::check()){
                    $sched = Schedule::find($request->input('sched_id'));
                    if (!($sched->pacient_id)) {
                        $sched->pacient_id = $request->input('user_id');
                        $sched->save();
                        $successMessage = "Запись к врачу {$sched->doctor->name} на дату ".date("d.m.Y", strtotime($sched->data_priem))." в {$sched->time_priem} часов успешно произведена";
                        Event::fire(new PeopleWriteToVisit($sched));
                    }
                    else {
                        $errorMessage = "Запись невозможна.";
                    }
                }
                else {
                    $errorMessage = "Вы вошли как администратор запись невозможна";
                }

            }
            /*****************************************************/
            $data = array();
            if (Auth::check()) {  // вход выполнен в административную часть - то роль админа получаем пользователя из Users

                $data["is_admin"] = true;
                $user = Auth::user();
                $data["user_id"] = $user->id;
                $data["user_fullname"] = $user->fullname;
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
            if (is_numeric($info)) $arRes["error"][] = Utils::getErrorMessageFromCode($info);
                else $arRes["error"][] = "Человек с такими данными не найден в базе ОМС";
            echo json_encode($arRes);
        };

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
            $curdate = date("Y-m-d");
            if ((Auth::check()) && $request->has("date_priem")) $curdate = date("Y-m-d", strtotime($request->input("date_priem")));
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


    public function getPeopleList(){
        if (Auth::check()){
            $peoples = Schedule::where("pacient_id","!=","null")->orderBy('data_priem', 'DESC')->paginate(15);
            return view("peoples-list",["data" => $peoples]);
        }
    }

    public function getDoctorsInfoList(Request $request){
        if (Auth::check()){

                /*
                 * Добавление врача в БД
                 */
                if ($request->has("add_doctor")){
                    $newDoc = new Doctor();
                    $newDoc->name = $request->input("new_doctor_name");
                    $newDoc->spec_id = $request->input("new_spec_id");
                    $newDoc->save();
                }

                /*
                 * Удаление врача из БД
                 */

                if ($request->has("del_doctor")){
                    $delDoc = Doctor::find($request->input("del_doctor_id"));
                    $delDoc->delete();
                }

                /*
                 * Добавление специализации в БД
                 */
                if ($request->has("add_spec")){
                    $newSpec = new Specialization();
                    $newSpec->name = $request->input("new_spec_name");
                    //echo "true";
                    //exit;
                    $newSpec->save();
                }

                /*
                 * Удаление специализации из БД
                 */
                if ($request->has("del_spec")){
                    /*echo "id";
                    echo $request->input("spec_list");
                    exit;*/
                    $delSpec = Specialization::find($request->input("spec_list"));
                    $delSpec->delete();
                }


            //$doctors = Doctor::paginate(15);
            $doctors = Doctor::paginate(15);
            $spec = Specialization::all();

            $user = Auth::user();
            $data["user_id"] = $user->id;
            $data["user_fullname"] = $user->fullname;

           /* foreach ($doctors as $doc){
                print_r($doc->spec);
            }*/
            //print_r($doctors);
            //exit;
            return view("doctors-list",["doctors" => $doctors, "spec" => $spec, "data"=>$data]);
        }
    }

    public function getDoctorsList(){

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

        $maxLength = 0; //Максимальное кол-во приемов в день
        foreach ($scheds as $sched){
            $datatime_priem = strtotime($sched->data_priem);
            $dm_priem = date("d.m", $datatime_priem);
            switch (date("w", $datatime_priem)){
                case 1:
                    $week[1]["data"][] = $sched;
                    if (count($week[1]["data"]) > $maxLength) $maxLength = count($week[1]["data"]);
                    break; //понедельник
                case 2:
                    $week[2]["data"][] = $sched;
                    if (count($week[2]["data"]) > $maxLength) $maxLength = count($week[2]["data"]);
                    break; //вторник
                case 3:
                    $week[3]["data"][] = $sched;
                    if (count($week[3]["data"]) > $maxLength) $maxLength = count($week[3]["data"]);
                    break; //среда
                case 4:
                    $week[4]["data"][] = $sched;
                    if (count($week[4]["data"]) > $maxLength) $maxLength = count($week[4]["data"]);
                    break; //четверг
                case 5:
                    $week[5]["data"][] = $sched;
                    if (count($week[5]["data"]) > $maxLength) $maxLength = count($week[5]["data"]);
                    break; //пятница
                case 6:
                    $week[6]["data"][] = $sched;
                    if (count($week[6]["data"]) > $maxLength) $maxLength = count($week[6]["data"]);
                    break; //суббота
                case 0:
                    $week[7]["data"][] = $sched;
                    if (count($week[7]["data"]) > $maxLength) $maxLength = count($week[7]["data"]);
                    break; //воскресенье
            }
        }
        /************Дополняем массивы до указанных размеров для красоты*******************/
        for ($i=1; $i<=7; $i++){
            if (!isset($week[$i]["data"])) $week[$i]["data"] = array();
            $week[$i]["data"] = array_pad($week[$i]["data"], $maxLength, null);
        }


        return $week;
    }

}
