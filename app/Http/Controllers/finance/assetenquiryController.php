<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class assetenquiryController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "assetcode";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assetenquiry.assetenquiry');
    }

    public function form(Request $request)
    {   
        if($request->action == 'comp_edit'){
            return $this->comp_edit($request);
        }

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

    public function table(Request $request)
    {  
        switch($request->action){
            case 'get_table':
                return $this->get_table($request);
            case 'writeoff_act':
                return $this->writeoff_act($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table(Request $request){
        // $table = $this->defaultGetter($request);

        $table = DB::table('finance.faregister as fa')
                    ->select('fa.idno','fa.compcode','fa.assetcode','fa.assettype','fa.assetno','fa.assetlineno','fa.description','fa.serialno','fa.lotno','fa.casisno','fa.engineno','fa.deptcode','fa.loccode','fa.suppcode','fa.purordno','fa.delordno','fa.delorddate','fa.dolineno','fa.itemcode','fa.invno','fa.invdate','fa.purdate','fa.purprice','fa.origcost','fa.insval','fa.qty','fa.startdepdate','fa.currentcost','fa.lstytddep','fa.cuytddep','fa.recstatus','fa.individualtag','fa.statdate','fa.trantype','fa.trandate','fa.lstdepdate','fa.nprefid','fa.adduser','fa.adddate','fa.upduser','fa.upddate','fa.regtype','fa.nbv','fa.method','fa.currdeptcode','fa.currloccode','fa.condition','fa.expdate','fa.brand','fa.model','fa.equipmentname','fa.trackingno','fa.bem_no','fa.ppmschedule','fa.lastcomputerid','fc.residualvalue','fc.rate')
                    ->leftJoin('finance.facode as fc', function ($join){
                        $join = $join->on('fc.assetcode','=','fa.assetcode')
                                    ->where('fc.compcode','=',session('compcode'));
                    })
                    ->where('fa.compcode',session('compcode'));

        if(!empty($request->searchCol)){
            $table = $table->Where(function ($table) use ($request){
                    $table->Where('fa.'.$request->searchCol[0],'like',$request->searchVal[0]);
            });
        }
        
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces) == 1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
        }

        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {//ini baru
            $value->description_show = $value->description;
            if(mb_strlen($value->description_show)>80){

                $time = time() + $key;

                $value->description_show = mb_substr($value->description_show,0,80).'<span id="dots_'.$time.'" style="display: inline;">...</span><span id="more_'.$time.'" style="display: none;">'.mb_substr($value->description_show,80).'</span><a id="moreBtn_'.$time.'" style="color: #337ab7 !important;" >Read more</a>';

                $value->callback_param = [
                    'dots_'.$time,'more_'.$time,'moreBtn_'.$time
                ];
            }

            $fatran_amount = DB::table('finance.fatran')
                                ->where('compcode',session('compcode'))
                                ->where('trantype','DEP')
                                ->where('assetno',$value->assetno)
                                ->where('trandate','<=',Carbon::now("Asia/Kuala_Lumpur"))
                                ->sum('amount');

            $value->dep_calc = $fatran_amount;
            $value->nbv_calc = $value->origcost - $fatran_amount;
            
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

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('finance.faregister')
                ->where('idno','=',$request->idno)
                ->update([  
                    'idno' => $request->idno,
                    'assetcode' => $request->assetcode,
                    'assettype' => $request->assettype,
                    'assetno' => $request->assetno,
                    'description' => $request->description,
                    'serialno' => $request->serialno,
                    'lotno' => $request->lotno,
                    'casisno' => $request->casisno,
                    'engineno' => $request->engineno,
                    'deptcode' => $request->deptcode,
                    'loccode' => $request->loccode,
                    'suppcode' => $request->suppcode,
                    'purordno' => $request->purordno,
                    'delordno' => $request->delordno,
                    'delorddate' => $request->delorddate,
                    'assetlineno' => $request->assetlineno,
                    'itemcode' => $request->itemcode,
                    'invno' => $request->invno,
                    'invdate' => $request->invdate,
                    'purdate' => $request->purdate,
                    'purprice' => $request->purprice,
                    'origcost' => $request->origcost,
                    'insval' => $request->insval,
                    'qty' => $request->qty,
                    'currentcost' => $request->currentcost,
                    'regtype' => $request->regtype,
                    'nprefid' => $request->nprefid,
                    'trandate' => $request->trandate,
                    'trantype' => $request->trantype,
                    'statdate' => $request->statdate,
                    'individualtag' => $request->individualtag,
                    'cuytddep' => $request->cuytddep,
                    'recstatus' => $request->recstatus,
                    'lstytddep' => $request->lstytddep,
                    'lstdepdate' => $request->lstdepdate,
                    'startdepdate' => $request->startdepdate,
                    'method' => $facode->method,
                    'residualvalue' => $facode->residualvalue,
                    'nbv' => $request->nbv,

                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),    
                    'recstatus' => strtoupper($request->recstatus),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress)
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();
        try {

            DB::table('finance.faregister')
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'DEACTIVE',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    //asset serial list @ FA COMPONENT//
    public function comp_edit(Request $request){
        DB::beginTransaction();
        try {

            DB::table('finance.facompnt')
                ->where('idno','=',$request->idno)
                ->update([  
                    'loccode' => $request->loccode,
                    'deptcode' => $request->deptcode,
                    'trackingno' => $request->trackingno,
                    'bem_no' => $request->bem_no,
                    'ppmschedule' => $request->ppmschedule
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function writeoff_act(Request $request){
        DB::beginTransaction();
        try {

            $date_wo = $request->date_wo;
            $remarks_wo = $request->remarks_wo;
            $nbv_wo = $request->nbv_wo;
            $faregister = DB::table('finance.faregister')
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$request->idno_h)
                            ->first();

            $facode = DB::table('finance.facode')
                            ->where('compcode',session('compcode'))
                            ->where('assetcode',$faregister->assetcode)
                            ->first();

            $origcost = $faregister->origcost;
            $accummulated = DB::table('finance.fatran')
                                ->where('compcode',session('compcode'))
                                ->where('trantype','DEP')
                                ->where('assetno',$faregister->assetno)
                                ->where('trandate','<=',$date_wo)
                                ->sum('amount');
            $nbv = $origcost - $accummulated;

            if($faregister->recstatus != 'ACTIVE'){
                throw new \Exception("asset is already deactive");
            }

            $recno = $this->recno('FA','WOF');

            DB::table('finance.faregister')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$request->idno_h)
                    ->update([
                        'recstatus' => 'DEACTIVE',
                        'trantype' => 'WOF',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            DB::table('finance.fatran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'assetcode' => $faregister->assetcode,
                        'assettype' => $faregister->assettype,
                        'auditno' => $recno,
                        'assetno' => $faregister->assetno,
                        'deptcode' => $faregister->deptcode,
                        'olddeptcode' => $faregister->deptcode,
                        'trantype' => 'WOF',
                        'trandate' => $date_wo,
                        // 'compntdate' => $faregister->,
                        'reference' => $remarks_wo,
                        // 'oldassetno' => $faregister->,
                        'curloccode' => $faregister->loccode,
                        // 'oldloccode' => $faregister->,
                        // 'debtorcode' => $faregister->,
                        'amount' => $nbv,
                        // 'adjstatus' => $faregister->,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'ACTIVE',
                    ]);

            $yearperiod = $this->getyearperiod($date_wo);

            DB::table('finance.gltran')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'auditno' => $recno,
                    'lineno_' => 1,
                    'source' => 'FA', //kalau stock 'IV', lain dari stock 'DO'
                    'trantype' => 'WOF',
                    'reference' => $remarks_wo,
                    'description' => "POSTING FROM WRITE-OFF(FA) Tagging No: ".$faregister->assetno, 
                    'postdate' => $date_wo,
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $facode->glrevccode,
                    'dracc' => $facode->glrevaluation,
                    'crcostcode' => $facode->glassetccode,
                    'cracc' => $facode->glasset,
                    'amount' => $faregister->origcost
                ]);

            $this->init_glmastdtl(
                        $facode->glrevccode,//drcostcode
                        $facode->glrevaluation,//dracc
                        $facode->glassetccode,//crcostcode
                        $facode->glasset,//cracc
                        $yearperiod,
                        $faregister->origcost
                    );

            DB::table('finance.gltran')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'auditno' => $recno,
                    'lineno_' => 2,
                    'source' => 'FA', //kalau stock 'IV', lain dari stock 'DO'
                    'trantype' => 'WOF',
                    'reference' => $remarks_wo,
                    'description' => "POSTING FROM WRITE-OFF(FA) Tagging No: ".$faregister->assetno, 
                    'postdate' => $date_wo,
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $facode->glprovccode,
                    'dracc' => $facode->glprovdep,
                    'crcostcode' => $facode->glrevccode,
                    'cracc' => $facode->glrevaluation,
                    'amount' => $nbv
                ]);

            $this->init_glmastdtl(
                        $facode->glprovccode,//drcostcode
                        $facode->glprovdep,//dracc
                        $facode->glrevccode,//crcostcode
                        $facode->glrevaluation,//cracc
                        $yearperiod,
                        $nbv
                    );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
    
    public function showpdf(Request $request){
        
        $assetno = $request->assetno;
        if(!$assetno){
            abort(404);
        }
        
        $faregister = DB::table('finance.faregister as fr')
                    ->select('fr.idno','fr.compcode','fr.assetcode','fr.assettype','fr.assetno','fr.assetlineno','fr.description','fr.serialno','fr.lotno','fr.casisno','fr.engineno','fr.deptcode','fr.loccode','fr.suppcode','fr.purordno','fr.delordno','fr.delorddate','fr.dolineno','fr.itemcode','fr.invno','fr.invdate','fr.purdate','fr.purprice','fr.origcost','fr.insval','fr.qty','fr.startdepdate','fr.currentcost','fr.lstytddep','fr.cuytddep','fr.recstatus','fr.individualtag','fr.statdate','fr.trantype','fr.trandate','fr.lstdepdate','fr.nprefid','fr.adduser','fr.adddate','fr.upduser','fr.upddate','fr.regtype','fr.nbv','fr.method','fr.residualvalue','fr.currdeptcode','fr.currloccode','fr.condition','fr.expdate','fr.brand','fr.model','fr.equipmentname','fr.trackingno','fr.bem_no','fr.ppmschedule','fr.lastcomputerid','fc.description as category_description','ft.description as type_description','s.Name as supplier_name','d.description as dept_description')
                    ->leftJoin('finance.facode as fc', function ($join){
                        $join = $join->on('fc.assetcode','=','fr.assetcode')
                                    ->where('fc.compcode','=',session('compcode'));
                    })
                    ->leftJoin('finance.fatype as ft', function ($join){
                        $join = $join->on('ft.assettype','=','fr.assettype')
                                    ->where('ft.compcode','=',session('compcode'));
                    })
                    ->leftJoin('material.supplier as s', function ($join){
                        $join = $join->on('s.SuppCode','=','fr.suppcode')
                                    ->where('s.CompCode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.department as d', function ($join){
                        $join = $join->on('d.deptcode','=','fr.currdeptcode')
                                    ->where('d.compcode','=',session('compcode'));
                    })
                    ->where('fr.compcode',session('compcode'))
                    ->where('fr.assetno',$assetno)
                    ->first();
        // dd($faregister);
        
        $movement = DB::table('finance.fatran as ft')
                    ->select('ft.assetcode','ft.assettype','ft.assetno','ft.auditno','ft.trandate','ft.trantype','ft.amount','ft.deptcode','ft.olddeptcode','ft.curloccode','ft.oldloccode','ft.idno','l.description as loc_description')
                    ->leftJoin('finance.faregister AS fr', function ($join){
                        $join = $join->on('fr.assetcode','=','ft.assetcode')
                                    ->where('fr.assettype','=','ft.assettype')
                                    ->where('fr.assetno','=','ft.assetno')
                                    ->where('fr.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.location as l', function ($join){
                        $join = $join->on('l.loccode','=','ft.curloccode')
                                    ->where('l.compcode','=',session('compcode'));
                    })
                    ->where('ft.compcode',session('compcode'))
                    ->where('ft.assetno',$assetno)
                    ->get();
        // dd($movement);
        
        $curloccode = DB::table('finance.fatran as ft')
                    ->leftJoin('sysdb.location as l', function ($join){
                        $join = $join->on('l.loccode','=','ft.curloccode')
                                    ->where('l.compcode','=',session('compcode'));
                    })
                    ->where('ft.trantype','TRF')
                    ->where('ft.compcode',session('compcode'))
                    ->where('ft.assetno',$assetno)
                    ->orderBy('ft.trandate','ASC')
                    ->get();
                    // ->pluck('ft.curloccode')
                    // ->toArray();
        // dd($curloccode);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.FA.assetenquiry.assetenquiry_pdfmake', compact('faregister','movement','curloccode','company'));
    }

    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = $this->isGltranExist($crcc,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcc)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }
    
}
