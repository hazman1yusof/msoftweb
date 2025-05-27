<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

    class JournalEntryController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.journalEntry.journalEntry');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'showpdf':
                return $this->showpdf($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $table = DB::table('finance.gljnlhdr AS h')
                    ->select(
                        'h.idno AS gljnlhdr_idno',
                        'h.compcode AS gljnlhdr_compcode',
                        'h.source AS gljnlhdr_source',
                        'h.trantype AS gljnlhdr_trantype',
                        'h.auditno AS gljnlhdr_auditno',
                        'h.docno AS gljnlhdr_docno',
                        'h.description AS gljnlhdr_description',
                        'h.year AS gljnlhdr_year',
                        'h.period AS gljnlhdr_period',
                        'h.different AS gljnlhdr_different',
                        'h.creditAmt AS gljnlhdr_creditAmt',
                        'h.debitAmt AS gljnlhdr_debitAmt',
                        'h.recstatus AS gljnlhdr_recstatus',
                        'h.docdate AS gljnlhdr_docdate',
                        'h.postdate AS gljnlhdr_postdate',
                        'h.adduser AS gljnlhdr_adduser',
                        'h.adddate AS gljnlhdr_adddate',
                        'h.upduser AS gljnlhdr_upduser',
                        'h.upddate AS gljnlhdr_upddate',
                        'h.unit AS gljnlhdr_unit'
                    )
                    ->where('h.compcode','=',session('compcode'))
                    ->where('h.source','=','GL')
                    ->where('h.trantype','=','JNL');

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'gljnlhdr_description'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('h.description','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'gljnlhdr_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('h.auditno','like',$request->searchVal[0]);
                    });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                    });
            }
        }

        if(!empty($request->sidx)){

            $pieces = explode(", ", $request->sidx .' '. $request->sord);

            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"h.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('h.idno','DESC');
        }

        $paginate = $table->paginate($request->rows);

        //////////paginate/////////

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);

    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }
        try {

            DB::beginTransaction();

            $auditno = $this->recno('GL', 'JNL');

            $table = DB::table("finance.gljnlhdr");
            
            $array_insert = [
                'auditno' => $auditno,                
                'source' => $request->gljnlhdr_source,
                'trantype' => $request->gljnlhdr_trantype,
                'description' => strtoupper($request->gljnlhdr_description),
                'docdate' => $request->gljnlhdr_docdate,
                'postdate' => $request->gljnlhdr_docdate,
                'year' => $request->gljnlhdr_year,
                'period' => $request->gljnlhdr_period,
                'docno' => str_pad($auditno,8,"0",STR_PAD_LEFT),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'unit' => session('unit'),
            ];
            
            // dd($array_insert);
            $idno = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function edit(Request $request){
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        DB::beginTransaction();

        $table = DB::table("finance.gljnlhdr");

        $array_update = [
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            'source' => $request->gljnlhdr_source,
            'trantype' => $request->gljnlhdr_trantype,
            'description' => strtoupper($request->gljnlhdr_description),
            'docdate' => $request->gljnlhdr_docdate,
            'postdate' => $request->gljnlhdr_docdate,
            'year' => $request->gljnlhdr_year,
            'period' => $request->gljnlhdr_period,
            'recstatus' => 'OPEN',
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
        ];

        foreach ($field as $key => $value) {
            if($value == 'remarks' || $value == 'document' || $value == 'outamt' || $value == 'outamount'){
                continue;
            }
            $array_update[$value] = $request[$request->field[$key]];
        }

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            $responce = new stdClass();
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function posted(Request $request){
        DB::beginTransaction();
        try {


            foreach ($request->idno_array as $auditno){

                $apacthdr = DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=',$auditno)
                    ->first();

                $apactdtl = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$apacthdr->source)
                    ->where('trantype','=',$apacthdr->trantype)
                    ->where('auditno','=', $auditno);

                $yearperiod = defaultController::getyearperiod_($apacthdr->postdate);
                    if($yearperiod->status == 'C'){
                        throw new \Exception('Auditno: '.$apacthdr->auditno.' Period already close, year: '.$yearperiod->year.' month: '.$yearperiod->period.' status: '.$yearperiod->status, 500);
                    }
                $this->check_outamt($apacthdr,$apactdtl);

                $this->gltran($auditno);

                if($apactdtl->exists()){ 
                    foreach ($apactdtl->get() as $value) {
                        DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','POSTED')
                            ->where('delordno','=',$value->document)
                            ->update(['invoiceno'=>$apacthdr->document]);
                    }
                }

                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=',$auditno)
                    ->update([
                        'recstatus' => 'POSTED',
                        'recdate' => $apacthdr->postdate,
                        'postuser' => session('username'),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
      
    public function cancel(Request $request){
        DB::beginTransaction();

        try {

            $apacthdr = DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=',$request->auditno)
                    ->first();

            if($apacthdr->recstatus = 'POSTED'){
                $delordhd = DB::table('material.delordhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','POSTED')
                        ->where('invoiceno','=',$apacthdr->document)
                        ->update([
                            'invoiceno' => null
                        ]);

                $apactdtl = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=', $request->auditno)
                    ->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                $this->gltran_cancel($request->auditno);

                DB::table('finance.apacthdr')
                    ->where('auditno','=',$request->auditno)
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }else if($apacthdr->recstatus = 'OPEN'){

                $delordhd = DB::table('material.delordhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','POSTED')
                        ->where('invoiceno','=',$apacthdr->document)
                        ->update([
                            'invoiceno' => null
                        ]);

                $apactdtl = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=', $request->auditno)
                    ->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                DB::table('finance.apacthdr')
                    ->where('auditno','=',$request->auditno)
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

            
               
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
    }

    public function showpdf(Request $request){
        $idno = $request->idno;
        if(!$idno){
            abort(404);
        }

        $gljnlhdr = DB::table("finance.gljnlhdr")
                        ->where('compcode',session('compcode'))
                        ->where('idno',$request->idno)
                        ->first();


        $gljnldtl = DB::table('finance.gljnldtl')
                        ->where('compcode',session('compcode'))
                        ->where('auditno',$gljnlhdr->auditno)
                        ->get();

        $summ_acc = $gljnldtl->unique('glaccount');


        foreach ($summ_acc as $obj_acc) {

            $glmasref = DB::table('finance.glmasref')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->where('glaccno',$obj_acc->glaccount)
                            ->first();
            $obj_acc->description = $glmasref->description;
        }


        foreach ($gljnldtl as $obj_dtl) {
            foreach ($summ_acc as $obj_acc) {
                if($obj_acc->glaccount == $obj_dtl->glaccount){
                    if($obj_dtl->drcrsign == 'DR'){
                        $obj_acc->amount_add = $obj_acc->amount_add + $obj_dtl->amount;
                    }else{
                        $obj_acc->amount_add = $obj_acc->amount_add + $obj_dtl->amount;
                    }
                }
            }
        }

        // $debtormast = collect($array_report)->unique('debtorcode');

        // dd($gljnlhdr);

        return view('finance.GL.journalEntry.journalEntry_pdfmake',compact('gljnlhdr','gljnldtl','summ_acc')); 
    }
}
