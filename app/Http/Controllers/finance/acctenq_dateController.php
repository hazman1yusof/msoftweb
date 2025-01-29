<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;

class acctenq_dateController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.acctenq_date.acctenq_date');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request){
        switch($request->action){
            case 'getdata';
                return $this->getdata($request);
            case 'get_auditno_forsrc';
                return $this->get_auditno_forsrc($request);
        }
    }

    public function getdata(Request $request){
        $gltran = DB::table('finance.gltran')
                        ->select('source','trantype','auditno','postdate','description','reference','cracc','dracc','amount')
                        ->where(function($gltran) use ($request){
                            $gltran->orwhere('dracc','=', $request->glaccount);
                            $gltran->orwhere('cracc','=', $request->glaccount);
                        })
                        ->where('postdate', '>=', $request->fromdate)
                        ->where('postdate', '<=', $request->todate)
                        ->orderBy('postdate', 'desc')->get();

        foreach ($gltran as $key => $value) {
            if($value->dracc == $request->glaccount){
                $value->acccode = $value->cracc;
                $value->cramount = 0;
                $value->dramount = $value->amount;
            }else{
                $value->acccode = $value->dracc;
                $value->cramount = $value->amount;
                $value->dramount = 0;
            }
        }

        $responce = new stdClass();
        $responce->rows = $gltran;

        return json_encode($responce);
    }

    public function get_auditno_forsrc(Request $request){

        if($request->source == 'PB' && $request->trantype == 'IN'){
            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$request->source)
                            ->where('trantype',$request->trantype)
                            ->where('invno',$request->auditno)
                            ->first();

            $responce = new stdClass();
            $responce->dbacthdr = $dbacthdr;

            return json_encode($responce);
        }
    }
}