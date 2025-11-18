<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Response;

class updPNLAccount extends defaultController
{   

    public function __construct(){
        // $this->middleware('auth');
    }

    public function show(Request $request){
        return view('other.updPNLAccount.updPNLAccount');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'process':
                return $this->process($request);
            default:
                return 'error happen..';
        }
    }

    public function process(Request $request){
        DB::beginTransaction();

        try {
            $monthfrom = $request->monthfrom;
            $monthto = $request->monthto;
            $year = $request->year;

            for ($i = $monthfrom; $i <= $monthto; $i++) {
                $month = $i;

                $sum = DB::table('finance.glmasdtl as gm')
                            ->join('finance.glmasref as gr', function($join){
                                $join = $join->on('gr.glaccno', '=', 'gm.glaccount')
                                            ->where('gr.compcode','=',session('compcode'))
                                            ->whereIn('gr.acttype',['E','R']);
                            })
                            ->where('gm.year',$year)
                            ->where('gm.compcode',session('compcode'))
                            ->sum('actamount'.$month);

                $glmasdtl = DB::table('finance.glmasdtl')
                                ->where('compcode',session('compcode'))
                                ->where('year','2025')
                                ->where('glaccount','50050102');

                if($glmasdtl->exists()){
                    DB::table('finance.glmasdtl')
                        ->where('compcode',session('compcode'))
                        ->where('year',$year)
                        ->where('glaccount','50050102')
                        ->update([
                            'actamount'.$month => $sum
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'year' => $year,
                            'glaccount' => '50050102',
                            'recstatus' => 'ACTIVE',
                            'actamount'.$month => $sum
                        ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }
}