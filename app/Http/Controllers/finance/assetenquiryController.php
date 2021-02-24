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
            
            default:
                return 'error happen..';
        }
    }

    public function get_table(Request $request){
        $table = $this->defaultGetter($request);

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
                    'lineno_' => $request->lineno_,
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
}
