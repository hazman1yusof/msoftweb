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
            case 'get_deptcode':
                return $this->get_deptcode($request);
            case 'get_itemcode':
                return $this->get_itemcode($request);
            default:
                return 'error happen..';
        }
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
                'deptcode' => $request->deptcode,
                'newitemcode' => $request->newitemcode,
                'uomcode' => $request->uomcode,
                'outqty' => $request->outqty,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
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
            
            DB::table("finance.glrpthdr")
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

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            
            $count = array_count_values($searchCol_array);
            // dump($count);
            
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
                    ->select('s.itemcode as s_itemcode', 'p.description as p_description', 's.uomcode as s_uomcode')
                    ->join('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 's.itemcode')
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

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            
            $count = array_count_values($searchCol_array);
            // dump($count);
            
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