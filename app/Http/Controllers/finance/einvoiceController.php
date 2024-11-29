<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APEnquiryExport;
use Maatwebsite\Excel\Facades\Excel;

class einvoiceController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.einvoice.einvoice');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'acctent_sales':
                return $this->acctent_sales($request);
            case 'acctent_cost':
                return $this->acctent_cost($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            // case 'add':
            //     return $this->defaultAdd($request);
            // case 'edit':
            //     return $this->defaultEdit($request);
            // case 'del':
            //     return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        $table = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.invno','db.mrn','db.episno','db.debtorcode','db.amount','db.entrydate','pm.Name','dm.name as dbname')
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                            $join = $join->where('pm.compcode', '=', session('compcode'));
                            $join = $join->on('pm.mrn', '=', 'db.mrn');
                        })
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode', '=', session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                        })
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->where('db.trantype','IN')
                        ->where('db.mrn','!=','0')
                        ->where('db.episno','!=','0');

        if(!empty($request->viewonly)){
            $table = $table->where('auditno',$request->auditno)
                           ->where('lineno_',$request->lineno_);
        }

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'Name'){
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('pm.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('db.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }
        
        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
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

    public function acctent_sales(Request $request){
        $invno = $request->invno;
        $auditno = $request->auditno;
        $lineno_ = $request->lineno_;
        $debtorname = $request->dbname;
        if(empty($invno) || empty($lineno_)){
            abort(403, 'billno Not Exist');
        }
        $array_show = [];

        $gltran_debit = DB::table('finance.gltran as gl')
                        ->select('gl.postdate as date','gl.dracc as account','gm.description as accountname','gl.amount as debit')
                        ->leftJoin('finance.glmasref as gm', function($join) use ($request){
                            $join = $join->where('gm.compcode', '=', session('compcode'));
                            $join = $join->on('gm.glaccno', '=', 'gl.dracc');
                        })
                        ->where('gl.compcode',session('compcode'))
                        ->where('gl.auditno',$invno)
                        ->where('gl.lineno_',$lineno_)
                        ->where('gl.source','PB')
                        ->where('gl.trantype','IN')
                        ->get();

        foreach ($gltran_debit as $key => $value) {
            $value->description = $debtorname;
            $value->credit='';
            array_push($array_show,$value);
        }

        $billdet = DB::table('hisdb.billdet as bd')
                        ->select('gl.postdate as date','cm.description','gm.description as accountname','gl.cracc as account','gl.amount as credit')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', 'bd.chgcode');
                            $join = $join->on('cm.uom', 'bd.uom');
                        })
                        ->join('finance.gltran as gl', function($join) use ($request){
                            $join = $join->where('bd.compcode', '=', session('compcode'));
                            $join = $join->on('gl.auditno', 'bd.auditno');
                            $join = $join->on('gl.lineno_', 'bd.lineno_');
                            $join = $join->where('gl.source','OE');
                            $join = $join->where('gl.trantype','IN');
                        })
                        ->leftJoin('finance.glmasref as gm', function($join) use ($request){
                            $join = $join->where('gm.compcode', '=', session('compcode'));
                            $join = $join->on('gm.glaccno', '=', 'gl.cracc');
                        })
                        ->where('bd.compcode', '=', session('compcode'))
                        ->where('bd.invno', $invno)
                        ->where('bd.lineno_',$lineno_)
                        ->get();

        foreach ($billdet as $key => $value) {
            $value->debit='';
            array_push($array_show,$value);
        }

        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = count($array_show);
        $responce->rows = $array_show;
        
        return json_encode($responce);
    }

    public function acctent_cost(Request $request){
        $invno = $request->invno;
        $auditno = $request->auditno;
        $lineno_ = $request->lineno_;
        $debtorname = $request->dbname;
        if(empty($invno) || empty($lineno_)){
            abort(403, 'billno Not Exist');
        }
        $array_show = [];

        $billdet = DB::table('hisdb.billdet as bd')
                        ->select('gl.postdate as date','cm.description','gm_cr.description as accountname_cr','gm_dr.description as accountname_dr','gl.cracc as cr_account','gl.dracc as db_account','gl.amount as amount')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', 'bd.chgcode');
                            $join = $join->on('cm.uom', 'bd.uom');
                        })
                        ->join('finance.gltran as gl', function($join) use ($request){
                            $join = $join->where('bd.compcode', '=', session('compcode'));
                            $join = $join->on('gl.auditno', 'bd.auditno');
                            $join = $join->on('gl.lineno_', 'bd.lineno_');
                            $join = $join->where('gl.source','IV');
                            $join = $join->where('gl.trantype','DS');
                        })
                        ->leftJoin('finance.glmasref as gm_cr', function($join) use ($request){
                            $join = $join->where('gm_cr.compcode', '=', session('compcode'));
                            $join = $join->on('gm_cr.glaccno', '=', 'gl.cracc');
                        })
                        ->leftJoin('finance.glmasref as gm_dr', function($join) use ($request){
                            $join = $join->where('gm_dr.compcode', '=', session('compcode'));
                            $join = $join->on('gm_dr.glaccno', '=', 'gl.dracc');
                        })
                        ->leftJoin('material.ivdspdt as iv', function($join) use ($request){
                            $join = $join->where('iv.compcode', '=', session('compcode'));
                            $join = $join->on('iv.recno', '=', 'bd.auditno');
                        })
                        ->where('bd.compcode', '=', session('compcode'))
                        ->where('bd.invno', $invno)
                        ->where('bd.lineno_',$lineno_)
                        ->get();

        foreach ($billdet as $key => $value) {
            $obj_new = new stdClass();
            $obj_new->date = $value->date;
            $obj_new->description = $value->description;
            $obj_new->account = $value->db_account;
            $obj_new->accountname = $value->accountname_dr;
            $obj_new->credit = '';
            $obj_new->debit = $value->amount;
            array_push($array_show,$obj_new);

            $obj_new2 = new stdClass();
            $obj_new2->date = $value->date;
            $obj_new2->description = '';
            $obj_new2->account = $value->cr_account;
            $obj_new2->accountname = $value->accountname_cr;
            $obj_new2->credit = $value->amount;
            $obj_new2->debit = '';
            array_push($array_show,$obj_new2);
        }
        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = count($array_show);
        $responce->rows = $array_show;
        
        return json_encode($responce);
    }
}