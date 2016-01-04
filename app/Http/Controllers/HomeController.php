<?php namespace App\Http\Controllers;

use App\Pacient;
use App\PacientManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

    public function index(){
        if (Session::has("user_id") || (Auth::check())){
            $data = array();
            if (Auth::check()) {  // вход выполнен в административную часть - то роль админа получаем пользователя из Users
                $data["is_admin"] = true;
            }
            else {
                $data["is_admin"] = false; // полаем пользователя из Pacients
                $pacient = Pacient::find(Session::get("user_id"));
                $data["user_fullname"] = implode(" ", array($pacient->fam, $pacient->im, $pacient->ot)); //объединяем в одну строку
            };





            /*
             * здесь передаем данные пациента и получаем данные по врачам
             */
            return view("home", ["data" => $data]);
        }
        else return view("auth");
    }

    public function login(Request $request){
        $arRes = array();
        $pacient = new Pacient([
            'fam' => mb_strtoupper($request->input("fam")),
            'im' => mb_strtoupper($request->input("im")),
            'ot' => mb_strtoupper($request->input("ot")),
            'dr' => $request->input("dr")
        ]);

        $PacientManager = App::make("App\PacientManager"); //внедрение зависимости создаем класс и автоматом связываем реализацию с интерфейсом через биндинг

        $info = $PacientManager->getPacientInfo($pacient); // получение данных пациента
        $arRes["result"] = "false";
        $arRes["error"][] = "Человек с такими данными не найден в базе ОМС";
        if ($info){
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
            echo json_encode($arRes);
        };

    }

    public function logout(){
        Session::forget("user_id");
        Auth::logout();
        return redirect("/");
    }

}
