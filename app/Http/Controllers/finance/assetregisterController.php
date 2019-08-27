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

    public function table(Request $request){

        $delordhd = DB::table('material.delordhd as dohd')
                        ->select(
                            'dohd.delordno as dohd_delordno',
                            'dohd.suppcode as dohd_suppcode',
                            'dohd.recno as dohd_recno',
                            'dohd.deliverydate as dohd_deliverydate',
                            'dohd.docno as dohd_docno',
                            'dohd.invoiceno as dohd_invoiceno',
                            'dohd.trandate as dohd_trandate',
                            'ap.actdate as ap_actdate')
                        ->leftJoin('finance.apacthdr as ap','dohd.invoiceno','=','ap.document')
                        ->where('dohd.compcode','=', session('compcode'))
                        ->whereNotNull('dohd.invoiceno')
                        ->where('dohd.suppcode','=',$request->suppcode)
                        ->get();
        foreach ($delordhd as $key => $value) {
            $delorddt = DB::table('material.delorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$value->dohd_recno)
                            ->whereColumn('qtytag','<','qtydelivered');
                            // ->where(function($query) use ($request){
                            //     $query = $query->whereNotNull('qtytag');
                            //     $query = $query->where('qtytag','<','qtydelivered');
                            // });

            // dump($delorddt->get());
            if(!$delorddt->exists()){
                $delordhd->forget($key);
            }
        }

        if($delordhd->count() == 1){
            // dump($delordhd);
            $chunk = [$delordhd->first()];
        }else{

            $chunk = $delordhd->forPage($request->page,$request->rows);
        }

        $responce = new stdClass();
        $responce->rows = $chunk;

        // dump($delordhd);

        return json_encode($responce);

        
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

                //update qtytag at delorddt
                $delordhd = DB::table('material.delordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$fatemp->delordno)
                    ->first();

                DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$delordhd->recno)
                    ->where('itemcode','=',$fatemp->itemcode)
                    ->where('lineno_','=',$fatemp->lineno_)
                    ->update([
                        'qtytag' => $fatemp->qty
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
