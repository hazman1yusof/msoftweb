<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class RepackController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "rptname";
    }
    
    public function show(Request $request)
    {
        return view('material.repack.repack');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_deptcode':
                return $this->get_deptcode($request);
            case 'get_itemcode':
                return $this->get_itemcode($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        
        $table = DB::table('material.repackhd AS r')
                    ->select(
                        'r.compcode AS r_compcode',
                        'r.idno AS r_idno',
                        'r.recno AS r_recno',
                        'r.deptcode AS r_deptcode',
                        'r.docno AS r_docno',
                        'r.newitemcode AS r_newitemcode',
                        'r.respersonid AS r_respersonid',
                        'r.outqty AS r_outqty',
                        'r.recstatus AS r_recstatus',
                        'r.avgcost AS r_avgcost',
                        'r.trandate AS r_trandate',
                        'r.trantime AS r_trantime',
                        'r.trandate AS r_trandate',
                        'r.amount AS r_amount',
                        'r.uomcode AS r_uomcode',
                        'r.adduser AS r_adduser',
                        'r.adddate AS r_adddate',
                        'r.upduser AS r_upduser',
                        'r.upddate AS r_upddate'                  
                    )
                    ->where('r.compcode',session('compcode'));
        
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
                }else if($pieces[0] == '<>'){
                    $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                }else if($pieces[0] == '>'){
                    $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
                }else if($pieces[0] == '>='){
                    $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
                }else if($pieces[0] == '<'){
                    $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
                }else if($pieces[0] == '<='){
                    $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
                }else if($pieces[0] == 'on'){
                    $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
                }else if($pieces[0] == 'null'){
                    $table = $table->whereNull($request->filterCol[$key]);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where($request->filterCol[$key],'=',r::raw($pieces[1]));
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }
        
        if(!empty($request->fromdate)){
            $table = $table->where('r.entrydate','>=',$request->fromdate);
            $table = $table->where('r.entrydate','<=',$request->todate);
        }
        
        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }
            
            $count = array_count_values($searchCol_array);
            // dump($request->searchCol);
            
            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }
        
        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"r.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('r.idno','DESC');
        }
        
       $paginate = $table->paginate($request->rows);
       
        // foreach ($paginate->items() as $key => $value) {
        //     $dbactdtl = DB::table('debtor.dbactdtl')
        //                 ->where('source','=',$value->db_source)
        //                 ->where('trantype','=',$value->db_trantype)
        //                 ->where('auditno','=',$value->db_auditno);
        
        //     if($dbactdtl->exists()){
        //         $value->dbactdtl_outamt = $dbactdtl->sum('amount');
        //     }else{
        //         $value->dbactdtl_outamt = $value->dbacthdr_outamount;
        //     }
        
        //     // $apalloc = DB::table('finance.apalloc')
        //     //             ->select('allocdate')
        //     //             ->where('refsource','=',$value->dbacthdr_source)
        //     //             ->where('reftrantype','=',$value->dbacthdr_trantype)
        //     //             ->where('refauditno','=',$value->dbacthdr_auditno)
        //     //             ->where('recstatus','!=','CANCELLED')
        //     //             ->orderBy('idno', 'desc');
        
        //     // if($apalloc->exists()){
        //     //     $value->apalloc_allocdate = $apalloc->first()->allocdate;
        //     // }else{
        //     //     $value->apalloc_allocdate = '';
        //     // }
        // }
        
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
            default:
                return 'Errors happen';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
                
        try {

            $request_no = $this->request_no('RP', $request->deptcode);
            $recno = $this->recno('IV','RP');

            $table = DB::table("material.repackhd");

            $array_insert = [
                'compcode' => session('compcode'),
                'docno' => $request_no,
                'recno' => $recno,
                'trandate' => Carbon::createFromFormat('d/m/Y', $request->trandate)->format('Y-m-d'),
                'trantime' => Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s'),
                'deptcode' => $request->deptcode,
                'newitemcode' => $request->newitemcode,
                'uomcode' => $request->uomcode,
                'outqty' => $request->outqty,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN'
            ];
            
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);
            
            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        // $docno = DB::table('material.repackhd')
        //         ->select('recno')
        //         ->where('compcode','=',session('compcode'))
        //         ->where('recno','=',$request->recno)->first();
        
        // if($docno->recno == $request->recno){
        // }

        DB::beginTransaction();
        
        $table = DB::table("material.repackhd");
        
        $array_update = [
            'trandate' => Carbon::createFromFormat('d/m/Y', $request->trandate)->format('Y-m-d'),
            'trantime' => Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s'),
            'deptcode' => $request->deptcode,
            'newitemcode' => $request->newitemcode,
            'uomcode' => $request->uomcode,
            'outqty' => $request->outqty,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
        ];
        
        try {
            
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);
            
            $responce = new stdClass();
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function del(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table("material.repackhd")
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function get_deptcode(Request $request){


        $table = DB::table('material.stockloc as s')
                    ->select('s.deptcode as s_deptcode', 'd.description as d_description')
                    ->join('sysdb.department as d', function($join){
                        $join = $join->on('d.deptcode', '=', 's.deptcode')
                                    ->where('d.compcode', '=', session('compcode'));
                    })
                    ->where('s.recstatus','=','ACTIVE')
                    ->where('s.compcode','=',session('compcode'))
                    ->where('s.year', '=', $request->filterVal[2])
                    ->whereNotNull('s.deptcode')
                    ->where('s.deptcode','<>','')
                    ->distinct('s.deptcode');

        // $table = $table->get(['s.deptcode', 'd.deptcode', 'd.description']);

        //dd($table);

        /////////searching/////////
        if(!empty($request->searchCol) && !empty($request->searchVal)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }
            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false && trim($request->searchVal[$key]) != '%%'){//trim whitespace
                            $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            $table->Where($searchCol_array[$key],'like',$search_);
                        }
                    }
                });
            }
        }

        /////////searching 2///////// ni search utk ordialog
        if(!empty($request->searchCol2)){

            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol2);
            }else{
                $searchCol_array = $request->searchCol2;
            }

            // $searchCol_array_1 = $searchCol_array_2 = $searchVal_array_1 = $searchVal_array_2 = [];

            // foreach ($searchCol_array as $key => $value) {
            //     if(($key+1)%2){
            //         array_push($searchCol_array_1, $searchCol_array[$key]);
            //         array_push($searchVal_array_1, $request->searchVal2[$key]);
            //     }else{
            //         array_push($searchCol_array_2, $searchCol_array[$key]);
            //         array_push($searchVal_array_2, $request->searchVal2[$key]);
            //     }
            // }
            
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }
        
        $paginate = $table->paginate($request->rows);
        
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

    public function get_itemcode(Request $request){


        $table = DB::table('material.stockloc as s')
                    ->select('s.itemcode as s_itemcode', 'p.description as p_description', 's.uomcode as s_uomcode', 'p.uomcode as p_uomcode')
                    ->join('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 's.itemcode')
                                    ->on('p.uomcode', '=', 's.uomcode')
                                    ->where('p.compcode', '=', session('compcode'));
                    })
                    ->where('s.recstatus','=','ACTIVE')
                    ->where('s.compcode','=',session('compcode'))
                    ->where('s.deptcode', '=', $request->filterVal[0])
                    ->where('s.year', '=', $request->filterVal[3])
                    // ->whereNotNull('s.deptcode')
                    // ->where('s.deptcode','<>','')
                    ->distinct('s.itemcode');

        // $table = $table->get(['s.deptcode', 'd.deptcode', 'd.description']);

        //dd($table);

        /////////searching/////////
        if(!empty($request->searchCol) && !empty($request->searchVal)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }
            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false && trim($request->searchVal[$key]) != '%%'){//trim whitespace
                            $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            $table->Where($searchCol_array[$key],'like',$search_);
                        }
                    }
                });
            }
        }
        
        /////////searching 2///////// ni search utk ordialog
        if(!empty($request->searchCol2)){

            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol2);
            }else{
                $searchCol_array = $request->searchCol2;
            }

            // $searchCol_array_1 = $searchCol_array_2 = $searchVal_array_1 = $searchVal_array_2 = [];

            // foreach ($searchCol_array as $key => $value) {
            //     if(($key+1)%2){
            //         array_push($searchCol_array_1, $searchCol_array[$key]);
            //         array_push($searchVal_array_1, $request->searchVal2[$key]);
            //     }else{
            //         array_push($searchCol_array_2, $searchCol_array[$key]);
            //         array_push($searchVal_array_2, $request->searchVal2[$key]);
            //     }
            // }
            
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }
        
        $paginate = $table->paginate($request->rows);
        
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
    
}