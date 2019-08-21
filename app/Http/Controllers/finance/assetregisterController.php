<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;
use Carbon\Carbon;

class assetregisterController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "assetregister";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assetregister.assetregister');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->asset_delete($request);
                // return $this->defaultDel($request);
            case 'gen_tagno':
                return $this->gen_tagno($request);
            default:
                return 'error happen..';
        }
    }

    public function gen_tagno(Request $request){

        DB::beginTransaction();

        try {
            foreach ($request->idno_array as $value){

                ////1. select facode tagnextno
                $fatemp = DB::table('finance.fatemp')
                            ->where('idno','=',$value)
                            ->first();

                $facode = DB::table('finance.facode')->select('tagnextno')
                                ->where('compcode','=',session('compcode'))
                                ->where('assettype', '=', $fatemp->assettype)
                                ->first();
                                
                $tagnextno_counter = intval($facode->tagnextno)+1;
                $assetno = str_pad($facode->tagnextno,6,"0",STR_PAD_LEFT);


                ////2. insert into faregister
                DB::table('finance.faregister')
                    ->insert([
                        'assetcode' => $fatemp->assetcode,
                        'assettype' => $fatemp->assettype,
                        'assetno' => $assetno, // got padding
                        'description' => $fatemp->description,
                        'deptcode' => $fatemp->deptcode,
                        'loccode' => $fatemp->loccode,
                        'suppcode' => $fatemp->suppcode,
                        'purordno' => $fatemp->purordno,
                        'delordno'  => $fatemp->delordno,
                        'delorddate' => $fatemp->delorddate,
                        'itemcode' => $fatemp->itemcode,
                        'invno' => $fatemp->invno,
                        'invdate' => $fatemp->invdate,
                        'purdate' => $fatemp->purdate,
                        'purprice' => $fatemp->purprice,
                        'origcost' => $fatemp->origcost,
                        'currentcost' => $fatemp->currentcost,
                        'qty' => $fatemp->qty,
                        'lstytddep' => $fatemp->lstytddep,
                        'cuytddep' => $fatemp->cuytddep,
                        'recstatus' => $fatemp->recstatus,
                        'individualtag' => $fatemp->individualtag,
                        'startdepdate' =>$fatemp->statdate,
                        'statdate' => $fatemp->statdate,
                        'trantype' => $fatemp->trantype,
                        'nprefid' => $fatemp->nprefid,
                        'trandate' => $fatemp->trandate,
                        'compcode' => session('compcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                ////delete from fatemp
                DB::table('finance.fatemp')
                        ->where('idno','=',$value)
                        ->delete();

                ////4. update facode tagnextno
                DB::table('finance.facode')->select('tagnextno')
                    ->where('compcode','=',session('compcode'))
                    ->where('assettype', '=', $fatemp->assettype)
                    ->update([
                        'tagnextno' => $tagnextno_counter
                    ]);

            }


            dump(DB::getQueryLog());
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            
            return response('Error'.$e, 500);
        }
        
    }

    public function asset_delete(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        try {

            $table = $table->where('idno','=',$request->idno);
            $table->delete();

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response('Error'.$e, 500);
        }

    }
}
