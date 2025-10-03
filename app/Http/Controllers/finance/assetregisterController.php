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
                $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$request->itemcode);

                if(!$product->exists()){
                    return response('Itemcode not Exists', 500);
                }

                return $this->defaultAdd($request);
            case 'edit':
                $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$request->itemcode);

                if(!$product->exists()){
                    return response('Itemcode not Exists', 500);
                }

                return $this->defaultEdit($request);
            case 'del':
                return $this->asset_delete($request);
                // return $this->defaultDel($request);
            case 'gen_tagno_single':
                return $this->gen_tagno_single($request);
            case 'gen_tagno':
                return $this->gen_tagno($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request){
        switch($request->from){
            case 'delordno':
                $delordhd = DB::table('material.delordhd as dohd')
                        ->select(
                            'dohd.delordno as dohd_delordno',
                            'dohd.suppcode as dohd_suppcode',
                            'dohd.recno as dohd_recno',
                            'dohd.deliverydate as dohd_deliverydate',
                            'dohd.prdept as dohd_prdept',
                            'dohd.docno as dohd_docno',
                            'dohd.invoiceno as dohd_invoiceno',
                            'dohd.trandate as dohd_trandate',
                            'dohd.deldept as dohd_deldept',                          
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
                $chunk_array = [];
                foreach ($chunk as $key => $value) {
                    array_push($chunk_array, $value);
                }

                $responce = new stdClass();
                $responce->rows = $chunk_array;
                return json_encode($responce);


            default:
                $table = $this->defaultGetter($request);

                $paginate = $table->paginate($request->rows);

                foreach ($paginate->items() as $key => $value) {//ini baru

                    $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$value->itemcode);

                    if($product->exists()){
                        $product = $product->first();
                        $value->itemcode_desc = $product->description;
                        $value->description_show = $value->description;
                        if(mb_strlen($value->description_show)>80){

                            $time = time() + $key;

                            $value->description_show = mb_substr($value->description_show,0,80).'<span id="dots_'.$time.'" style="display: inline;">...</span><span id="more_'.$time.'" style="display: none;">'.mb_substr($value->description_show,80).'</span><a id="moreBtn_'.$time.'" style="color: #337ab7 !important;" >Read more</a>';

                            $value->callback_param = [
                                'dots_'.$time,'more_'.$time,'moreBtn_'.$time
                            ];
                        }
                    }
                    
                }

                $responce = new stdClass();
                $responce->page = $paginate->currentPage();
                $responce->total = $paginate->lastPage();
                $responce->records = $paginate->total();
                $responce->rows = $paginate->items();
                $responce->sql = $table->toSql();
                $responce->sql_bind = $table->getBindings();

                return json_encode($responce);

        }      
    }

    // public function gen_tagno_single(Request $request){

    //     DB::beginTransaction();

    //     try {
    //         ////1. select facode tagnextno
    //         $fatemp = DB::table('finance.fatemp')
    //                     ->where('idno','=',$idno)
    //                     ->first();

    //         $product = DB::tale('material.product')
    //                         ->where('compcode','=',session('compcode'))
    //                         ->where('itemcode','=',$fatemp->itemcode)
    //                         ->first();

    //         $facode = DB::table('finance.facode')->select('tagnextno')
    //                         ->where('compcode','=',session('compcode'))
    //                         ->where('assettype', '=', $fatemp->assettype)
    //                         ->where('method', '=', $facode->method)
    //                         ->where('residualvalue', '=', $facode->residualvalue)
    //                         ->first();
                            
    //         $tagnextno_counter = intval($facode->tagnextno)+1;
    //         $assetno = str_pad($facode->tagnextno,6,"0",STR_PAD_LEFT);

    //         ////2. insert into faregister
    //         for ($x=1; $x <= $fatemp->qty; $x++) { 
    //             if($x == 1){
    //                 DB::table('finance.faregister')
    //                     ->insert([
    //                         'assetcode' => $fatemp->assetcode,
    //                         'assettype' => $fatemp->assettype,
    //                         'assetno' => $assetno, // got padding
    //                         'itemcode' => $fatemp->itemcode,
    //                         'description' => $fatemp->itemcode.'-'.$product->description.'\n'.$fatemp->description,
    //                         'lineno_' => $fatemp->lineno_,
    //                         'deptcode' => $fatemp->deptcode,
    //                         'loccode' => $fatemp->loccode,
    //                         'suppcode' => $fatemp->suppcode,
    //                         'purordno' => $fatemp->purordno,
    //                         'delordno'  => $fatemp->delordno,
    //                         'delorddate' => $fatemp->delorddate,
    //                         'invno' => $fatemp->invno,
    //                         'invdate' => $fatemp->invdate,
    //                         'purdate' => $fatemp->purdate,
    //                         'purprice' => $fatemp->purprice,
    //                         'origcost' => $fatemp->origcost,
    //                         'currentcost' => $fatemp->currentcost,
    //                         'qty' => $fatemp->qty,
    //                         'lstytddep' => $fatemp->lstytddep,
    //                         'cuytddep' => $fatemp->cuytddep,
    //                         'recstatus' => $fatemp->recstatus,
    //                         'individualtag' => $fatemp->individualtag,
    //                         'startdepdate' =>$fatemp->statdate,
    //                         'statdate' => $fatemp->statdate,
    //                         'trantype' => $fatemp->trantype,
    //                         'nprefid' => $fatemp->nprefid,
    //                         'trandate' => $fatemp->trandate,
    //                         'method' => $facode->method,
    //                         'residualvalue' => $facode->residualvalue,
    //                         // 'nbv' => $fatemp->nbv,
    //                         'compcode' => session('compcode'),
    //                         'adduser' => strtoupper(session('username')),
    //                         'adddate' => Carbon::now("Asia/Kuala_Lumpur")
    //                     ]);
    //             }else{
    //                     DB::table('finance.facompnt')
    //                         ->insert([
    //                             'assetcode' => $fatemp->assetcode,
    //                             'assettype' => $fatemp->assettype,
    //                             'assetno' => $assetno, // got padding
    //                             'assetlineno' => $x,
    //                             'deptcode' => $fatemp->deptcode,
    //                             'loccode' => $fatemp->loccode,
    //                             'qty' => $fatemp->qty,
    //                             'condition' => null,
    //                             'expdate' => null,
    //                             'brand' => null,
    //                             'model' => null,
    //                             'equipmentname' => null,
    //                             'trackingno' => null,
    //                             'bem_no' => null,
    //                             'ppmschedule' => null,
    //                             'compcode' => session('compcode'),
    //                             'adduser' => strtoupper(session('username')),
    //                             'adddate' => Carbon::now("Asia/Kuala_Lumpur")
    //                         ]);
    //             }
    //         }

    //         ////delete from fatemp
    //         DB::table('finance.fatemp')
    //                 ->where('idno','=',$idno)
    //                 ->delete();

    //         ////4. update facode tagnextno
    //         DB::table('finance.facode')->select('tagnextno')
    //             ->where('compcode','=',session('compcode'))
    //             ->where('assettype', '=', $fatemp->assettype)
    //             ->update([
    //                 'tagnextno' => $tagnextno_counter
    //             ]);

    //         //update qtytag at delorddt

    //         if($fatemp->regtype == 'PO'){
    //             $delordhd = DB::table('material.delordhd')
    //                 ->where('compcode','=',session('compcode'))
    //                 ->where('delordno','=',$fatemp->delordno)
    //                 ->first();

    //             DB::table('material.delorddt')
    //                 ->where('compcode','=',session('compcode'))
    //                 ->where('recno','=',$delordhd->recno)
    //                 ->where('itemcode','=',$fatemp->itemcode)
    //                 ->where('lineno_','=',$fatemp->lineno_)
    //                 ->update([
    //                     'qtytag' => $fatemp->qty
    //                 ]);
    //         }

    //         dump(DB::getQueryLog());
            
    //         // DB::commit();

    //     } catch (\Exception $e) {
    //         DB::rollback();
            
    //         return response('Error'.$e, 500);
    //     }
        
    // }

    public function gen_tagno(Request $request){

        DB::beginTransaction();

        try {
            foreach ($request->idno_array as $value){

                ////1. select facode tagnextno
                $fatemp = DB::table('finance.fatemp')
                            ->where('idno','=',$value)
                            ->first();

                $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$fatemp->itemcode)
                            ->first();

                $facode = DB::table('finance.facode')->select('tagnextno')
                                ->where('compcode','=',session('compcode'))
                                ->where('assetcode', '=', $fatemp->assetcode)
                                ->where('assettype', '=', $fatemp->assettype);
                                // ->where('method', '=', $fatemp->method)
                                // ->where('residualvalue', '=', $fatemp->residualvalue);

                if($facode->exists()){
                    $facode = $facode->first();
                }else{
                    // $tagnextno_counter = 2;
                    // $assetno = str_pad(1,6,"0",STR_PAD_LEFT);
                    throw new Exception("facode not exist", 1);
                }

                if($fatemp->individualtag == 'N'){

                    $tagnextno_counter = intval($facode->tagnextno)+1;
                    $assetno = str_pad($facode->tagnextno,5,"0",STR_PAD_LEFT);

                    $this->crt_fareg_notindv($fatemp,$product,$assetno);
                }else if($fatemp->individualtag == 'Y'){

                    $tagnextno_counter = intval($facode->tagnextno)+intval($fatemp->qty);

                    $this->crt_fareg_indv($fatemp,$product,$facode->tagnextno);
                }else{
                    throw new Exception("individualtag not exist", 1);
                }

                ////delete from fatemp
                DB::table('finance.fatemp')
                        ->where('idno','=',$value)
                        ->delete();

                ////4. update facode tagnextno
                DB::table('finance.facode')->select('tagnextno')
                    ->where('compcode','=',session('compcode'))
                    ->where('assetcode', '=', $fatemp->assetcode)
                    ->where('assettype', '=', $fatemp->assettype)
                    ->update([
                        'tagnextno' => $tagnextno_counter
                    ]);

                //update qtytag at delorddt

                if($fatemp->regtype == 'PO'){
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

    public function crt_fareg_notindv($fatemp,$product,$assetno){
        for ($x=1; $x <= $fatemp->qty; $x++) { 
            if($x == 1){
                ////2. insert into faregister
                DB::table('finance.faregister')
                    ->insert([
                        'assetcode' => $fatemp->assetcode,
                        'assettype' => $fatemp->assettype,
                        'assetno' => $fatemp->assetcode.$assetno, // got padding
                        'description' => $fatemp->description,
                        'dolineno' => $fatemp->lineno_,
                        'deptcode' => $fatemp->deptcode,
                        'loccode' => $fatemp->loccode,
                        'currdeptcode' => $fatemp->deptcode,
                        'currloccode' => $fatemp->loccode,
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
                        'method' => $fatemp->method,
                        'residualvalue' => $fatemp->residualvalue,
                        // 'nbv' => $fatemp->nbv,
                        'condition' => null,
                        'expdate' => null,
                        'brand' => null,
                        'model' => null,
                        'equipmentname' => null,
                        'trackingno' => null,
                        'bem_no' => null,
                        'ppmschedule' => null,
                        'compcode' => session('compcode'),
                        'adduser' => strtoupper(session('username')),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }else{
                // DB::table('finance.facompnt')
                //     ->insert([
                //         'assetcode' => $fatemp->assetcode,
                //         'assettype' => $fatemp->assettype,
                //         'assetno' => $assetno, // got padding
                //         'assetlineno' => $x,
                //         'deptcode' => $fatemp->deptcode,
                //         'loccode' => $fatemp->loccode,
                //         'currdeptcode' => $fatemp->deptcode,
                //         'currloccode' => $fatemp->loccode,
                //         'qty' => $fatemp->qty,
                //         'condition' => null,
                //         'expdate' => null,
                //         'brand' => null,
                //         'model' => null,
                //         'equipmentname' => null,
                //         'trackingno' => null,
                //         'bem_no' => null,
                //         'ppmschedule' => null,
                //         'compcode' => session('compcode'),
                //         'adduser' => strtoupper(session('username')),
                //         'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                //     ]);
            }
            
        }
    }

    public function crt_fareg_indv($fatemp,$product,$tagnextno){
        ////2. insert into faregister
        for ($x=0; $x < $fatemp->qty; $x++) { 
            $assetno = str_pad(intval($tagnextno)+$x,5,"0",STR_PAD_LEFT);
            $purprice = floatval($fatemp->purprice) / intval($fatemp->qty);
            $origcost = floatval($fatemp->origcost) / intval($fatemp->qty);
            $currentcost = floatval($fatemp->currentcost) / intval($fatemp->qty);
            DB::table('finance.faregister')
                ->insert([
                    'assetcode' => $fatemp->assetcode,
                    'assettype' => $fatemp->assettype,
                    'assetno' => $fatemp->assetcode.$assetno, // got padding
                    'description' => $fatemp->description,
                    'dolineno' => $fatemp->lineno_,
                    'deptcode' => $fatemp->deptcode,
                    'loccode' => $fatemp->loccode,
                    'currdeptcode' => $fatemp->deptcode,
                    'currloccode' => $fatemp->loccode,
                    'suppcode' => $fatemp->suppcode,
                    'purordno' => $fatemp->purordno,
                    'delordno'  => $fatemp->delordno,
                    'delorddate' => $fatemp->delorddate,
                    'itemcode' => $fatemp->itemcode,
                    'invno' => $fatemp->invno,
                    'invdate' => $fatemp->invdate,
                    'purdate' => $fatemp->purdate,
                    'purprice' => $purprice,
                    'origcost' => $origcost,
                    'currentcost' => $currentcost,
                    'qty' => 1,
                    'lstytddep' => $fatemp->lstytddep,
                    'cuytddep' => $fatemp->cuytddep,
                    'recstatus' => $fatemp->recstatus,
                    'individualtag' => $fatemp->individualtag,
                    'startdepdate' =>$fatemp->statdate,
                    'statdate' => $fatemp->statdate,
                    'trantype' => $fatemp->trantype,
                    'nprefid' => $fatemp->nprefid,
                    'trandate' => $fatemp->trandate,
                    'method' => $fatemp->method,
                    'residualvalue' => $fatemp->residualvalue,
                    // 'nbv' => $fatemp->nbv,
                    'condition' => null,
                    'expdate' => null,
                    'brand' => null,
                    'model' => null,
                    'equipmentname' => null,
                    'trackingno' => null,
                    'bem_no' => null,
                    'ppmschedule' => null,
                    'compcode' => session('compcode'),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
        }
    }
}
