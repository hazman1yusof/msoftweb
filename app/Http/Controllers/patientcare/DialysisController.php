<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;

class DialysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){ 
        // dd(Auth::user());

        // $navbar = $this->navbar();

        // // $emergency = DB::table('episode')
        // //                 ->whereMonth('reg_date', '=', now()->month)
        // //                 ->get();

        // // $events = $this->getEvent($emergency);

        // if(!empty($request->username)){
        //     $user = DB::table('users')
        //             ->where('username','=',$request->username);
        //     if($user->exists()){
        //         $user = User::where('username',$request->username);
        //         Auth::login($user->first());
        //     }
        // }
        return view('dialysis');
    }

    public function dialysis_event(Request $request){
        $emergency = DB::table('hisdb.episode')
                        ->whereRaw(
                          "(reg_date >= ? AND reg_date <= ?)", 
                          [
                             $request->start, 
                             $request->end
                         ])->get();
        
        $events = [];

        for ($i=1; $i <= 31; $i++) {
            $days = 0;
            $reg_date;
            foreach ($emergency as $key => $value) {
                $day = Carbon::createFromFormat('Y-m-d',$value->reg_date);
                if($day->day == $i){
                    $reg_date = $value->reg_date;
                    $days++;
                }
            }
            if($days != 0){
                $event = new stdClass();
                $event->title = $days.' patients';
                $event->start = $reg_date;
                array_push($events, $event);
            }
        }

        return $events;

    }

    public function get_data_dialysis(Request $request){

        switch ($request->action) {
            case 'get_dia_monthly':
                return $this->get_dia_monthly($request);
                break;

            case 'get_dia_weekly':
                return $this->get_dia_weekly($request);
                break;

            case 'get_dia_daily':
                return $this->get_dia_daily($request);
                break;
        }
        
    }

    public function get_dia_monthly(Request $request){
        $post = [];
        if(!empty($request->date)){
            $carbon = new Carbon($request->date);

            $post = DB::table('hisdb.dialysis')
                    ->where('mrn','=',$request->mrn)
                    ->whereYear('start_date', '=', $carbon->year)
                    ->whereMonth('start_date', '=', $carbon->month)
                    ->get();
        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function get_dia_weekly(Request $request){
        $post = [];
        if(!empty($request->datefrom)){
            $datefrom = new Carbon($request->datefrom);
            $dateto = new Carbon($request->dateto);

            $post = DB::table('hisdb.dialysis')
                    ->where('mrn','=',$request->mrn)
                    ->whereBetween('start_date', [$datefrom, $dateto])
                    ->take(3)
                    ->get();
        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function get_dia_daily(Request $request){
        $post = [];
        if(!empty($request->date)){
            $carbon = new Carbon($request->date);

            $post = DB::table('hisdb.dialysis')
                    ->where('mrn','=',$request->mrn)
                    ->whereDate('start_date', '=', $carbon)
                    ->get();
        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function save_dialysis(Request $request){

        $table = DB::table('hisdb.dialysis');
        try {
            if($request->oper == 'add'){
                $array_insert = [
                    'mrn'=>$request->mrn,
                    'episno'=>$request->episno,
                    'start_date'=>new Carbon($request->seldate)
                ];

                foreach ($_POST as $key => $value) {
                    if(!empty($value)){
                        $array_insert[$key] = $value;
                    }
                }
        
                $table->insert($array_insert);

            }else if($request->oper == 'edit'){
                $table
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

                $array_update = [];

                foreach ($_POST as $key => $value) {
                    if(!empty($value)){
                        $array_update[$key] = $value;
                    }
                }
        
                $table->update($array_update);
            }

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function transaction_save(Request $request){
        try {
            $table = DB::table('hisdb.chargetrx');

            if($request->oper == 'edit'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('auditno','=',$request->t_auditno);

                $array_edit = [
                    'chgcode' => $request->t_chgcode,
                    'quantity' => $request->t_quantity,
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->update($array_edit);
            }else{
                $array_insert = [
                    'compcode' => '9A',
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => $request->trxdate,
                    'chgcode' => $request->t_chgcode,
                    'billflag' => '0',
                    'isudept' => $request->isudept,
                    'quantity' => $request->t_quantity,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->insert($array_insert);
            }

            

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function change_status(Request $request){
        try {
            $table = DB::table('hisdb.episode')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update([
                            'ordercomplete' => 1,
                        ]);
            

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public static function mydump($obj,$line='null'){
        dd([
            $line,
            $obj->toSql(),
            $obj->getBindings()
        ]);

    }

}
