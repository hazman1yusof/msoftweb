<?php

namespace App\Http\Controllers\dialysis;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;
use Session;
use App\Exports\PatmastExport;
use App\Exports\pat_monthly;
use Maatwebsite\Excel\Facades\Excel;

class eisController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $centers = $this->get_maiwp_center_dept();  

        if(!empty($request->changedept)){

            $department = DB::table('sysdb.department')
                            ->where('compcode', session('compcode'))
                            ->where('deptcode', $request->changedept);

            if($department->exists()){
                $request->session()->put('dept', $department->first()->deptcode);
                $request->session()->put('dept_desc', $department->first()->description);
            }
        }

        return view('eis.eis',compact('centers'));
    }

	public function reveis(Request $request)
    {
        return view('eis.reveis');
    }

    public function table(Request $request){
        switch ($request->action) {
            case 'get_json_pivot_epis':
                return $this->get_json_pivot_epis($request);
                break;
            case 'get_json_pivot_rev':
                return $this->get_json_pivot_rev($request);
                break;
            case 'get_month':
                return $this->get_month($request);
                break;
            case 'get_patmast':
                return $this->get_patmast($request);
                break;
            default:
                # code...
                break;
        }
    }

    public function form(Request $request){
        switch ($request->action) {
            case 'patmast_excel':
                return $this->patmast_excel($request);
                break;
            case 'pat_monthly':
                return $this->pat_monthly($request);
                break;
            default:
                # code...
                break;
        }
    }

    public function get_patmast(Request $request){
        $pat_mast = DB::table('hisdb.pat_mast as p')
                    ->select('p.mrn','p.Sex','p.RaceCode','p.Religion','p.Citizencode','p.AreaCode','p.Postcode','e.regdept','e.admdoctor','e.attndoctor','e.pay_type','epy.payercode')
                    ->leftJoin('hisdb.episode as e', function($join) use ($request){
                        $join = $join->on('e.mrn', '=', 'p.mrn')
                                    ->on('e.episno','=','p.episno')
                                    ->where('e.compcode','13A');
                    })
                    ->leftJoin('hisdb.epispayer as epy', function($join) use ($request){
                        $join = $join->on('epy.mrn', '=', 'p.mrn')
                                    ->on('epy.episno','=','p.episno')
                                    ->where('epy.compcode','13A');
                    })
                    ->where('p.active','=','1')
                    ->where('p.compcode','=','13A')
                    ->get();


        $responce = new stdClass();
        $responce->data = $pat_mast;

        echo json_encode($responce);
    }

    public function get_json_pivot_epis(Request $request){
        // DB::enableQueryLog();
        $dt = Carbon::now("Asia/Kuala_Lumpur");
        $year = [$dt->year];
        $datetype = $request->datetype;
        if(!empty($request->dbtosearch)){
            $dbtosearch = explode(",", $request->dbtosearch);
        }else{
            $dbtosearch = [];
        }
        foreach ($dbtosearch as $value) {
            $date_ = explode("-", $value);
            if(!in_array($date_[0],$year)){
                array_push($year,$date_[0]);
            }
            
        }
        $object = new stdClass();
        foreach ($year as $value) {
            $date_ = explode("-", $value);
            // $month = ($value == $dt->year)?['M'.str_pad($dt->month, 2, '0', STR_PAD_LEFT)]:[];
            $month = [];
            foreach ($dbtosearch as $value2) {
                $date_ = explode("-", $value2);
                if($date_[0] == $value){
                    array_push($month,'M'.$date_[1]);
                }
            }
            $object->$value = $month;
        }

        $all_collection = collect();
        foreach ($object as $key => $value) {
            $pateis = DB::table('hisdb.pateis_epis')
                    ->select('units','epistype','gender','race','religion','payertype','regdept','admdoctor','admdate','discdate','admsrc','docdiscipline','docspeciality','agerange','citizen','area','postcode','placename','patient','state','country','year','quarter','month','datetype')
                    ->where('datetype','=',$datetype)
                    ->where('year','=','Y'.$key)
                    ->whereIn('month',$value);
                
            $all_collection = $all_collection->merge($pateis->get());
        }

        $responce = new stdClass();
        $responce->queries = $this->getQueries($pateis);
        $responce->data = $all_collection;

        echo json_encode($responce);



        // $object = (object) ['property' => 'Here we go'];
        // $datefrom = new Carbon($request->datefrom);
        // $dateto = new Carbon($request->dateto);
        // $dateto = $dateto->day($dateto->daysInMonth);

        // $init = $request->init;
        // $pateis = DB::table('pateis_epis')
        //             ->select('units','epistype','gender','race','religion','payertype','regdept','admdoctor','admdate','admsrc','docdiscipline','docspeciality','agerange','citizen','area','postcode','placename','state','country','year','quarter','month','datetype')
        //             ->where('datetype','=',$datetype)
        //             ->whereBetween('admdate', [$datefrom, $dateto]);
        // if($init == 'init'){
        //     $dt = Carbon::now("Asia/Kuala_Lumpur");
        //     $pateis = $pateis->where('year','=','Y'.$dt->year);
        //     $pateis = $pateis->where('month','=','M'.str_pad($dt->month, 2, '0', STR_PAD_LEFT));
        // }else{
        // }
        // $pateis = $pateis;

        // $queries = DB::getQueryLog();
    }

    public function get_json_pivot_rev(Request $request){
        $dt = Carbon::now("Asia/Kuala_Lumpur");
        $year = [$dt->year];
        $datetype = $request->datetype;
        if(!empty($request->dbtosearch)){
            $dbtosearch = explode(",", $request->dbtosearch);
        }else{
            $dbtosearch = [];
        }
        foreach ($dbtosearch as $value) {
            $date_ = explode("-", $value);
            if(!in_array($date_[0],$year)){
                array_push($year,$date_[0]);
            }
            
        }
        $object = new stdClass();
        foreach ($year as $value) {
            $date_ = explode("-", $value);
            // $month = ($value == $dt->year)?['M'.str_pad($dt->month, 2, '0', STR_PAD_LEFT)]:[];
            $month = [];
            foreach ($dbtosearch as $value2) {
                $date_ = explode("-", $value2);
                if($date_[0] == $value){
                    array_push($month,'M'.$date_[1]);
                }
            }
            $object->$value = $month;
        }

        $all_collection = collect();
        foreach ($object as $key => $value) {
            $pateis = DB::table('hisdb.pateis_rev')
                    ->select('units','epistype','chgcode','chgdesc','groupdesc','typedesc','quantity','unitprice','amount','month','quarter','year','regdate','disdate','datetype')
                    ->where('datetype','=',$datetype)
                    ->where('year','=','Y'.$key)
                    ->whereIn('month',$value);
                
            $all_collection = $all_collection->merge($pateis->get());
        }

        $responce = new stdClass();
        $responce->queries = $this->getQueries($pateis);
        $responce->data = $all_collection;

        echo json_encode($responce);
    }

    public function dashboard(Request $request){
        $month = 6;
        $year = 2021;
        $ip_rev = DB::table('hisdb.patsumepis')
                    ->where('month','=',$month)
                    ->where('year','=',$year)
                    ->where('patient','=',"IP")
                    ->where('type','=',"REV")
                    ->first();

        $op_rev = DB::table('hisdb.patsumepis')
                    ->where('month','=',$month)
                    ->where('year','=',$year)
                    ->where('patient','=',"OP")
                    ->where('type','=',"REV")
                    ->first();

        $ip_epis = DB::table('hisdb.patsumepis')
                    ->where('month','=',$month)
                    ->where('year','=',$year)
                    ->where('patient','=',"IP")
                    ->where('type','=',"epis")
                    ->first();

        $op_epis = DB::table('hisdb.patsumepis')
                    ->where('month','=',$month)
                    ->where('year','=',$year)
                    ->where('patient','=',"OP")
                    ->where('type','=',"epis")
                    ->first();

        $ip_month = [$ip_rev->week1,$ip_rev->week2,$ip_rev->week3,$ip_rev->week4];
        $op_month = [$op_rev->week1,$op_rev->week2,$op_rev->week3,$op_rev->week4];

        $ip_month_epis =  [$ip_epis->week1,$ip_epis->week2,$ip_epis->week3,$ip_epis->week4];
        $op_month_epis = [$op_epis->week1,$op_epis->week2,$op_epis->week3,$op_epis->week4];

        $groupdesc_ = DB::table('hisdb.pateis_rev')->distinct()->get(['groupdesc']);

        $groupdesc = [];
        $groupdesc_val_op = [];
        $groupdesc_val_ip = [];
        $groupdesc_cnt_op = [];
        $groupdesc_cnt_ip = [];
        $groupdesc_val = [];

        $patsumrev = DB::table('hisdb.patsumrev')
                        ->where('month','=',$month)
                        ->where('year','=',$year)
                        ->get();

        foreach ($patsumrev as $key => $value) {
            array_push($groupdesc,$value->group);
            array_push($groupdesc_val_op,$value->opsum);
            array_push($groupdesc_val_ip,$value->ipsum);
            array_push($groupdesc_cnt_op,$value->opcnt);
            array_push($groupdesc_cnt_ip,$value->ipcnt);
            array_push($groupdesc_val,$value->totalsum);
        }

        return view('eis.dashboard',compact('ip_month','op_month','ip_month_epis','op_month_epis','groupdesc','groupdesc_val_op','groupdesc_val_ip','groupdesc_cnt_op','groupdesc_cnt_ip','groupdesc_val'));
    }

    public function patmast_excel(Request $request){
        return Excel::download(new PatmastExport(), 'PatmastList.xlsx');
    }

    public function pat_monthly(Request $request){

        $excel_name = 'Patient Month-'.$request->month.' Year-'.$request->year.' '.$request->pat_name;
        $excel_name = $excel_name.' '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i:s');

        return Excel::download(new pat_monthly($request), $excel_name.'.xlsx');
    }
}
