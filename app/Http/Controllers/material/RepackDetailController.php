<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class RepackDetailController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            case 'get_deptcodedtl':
                return $this->get_deptcodedtl($request);
            case 'get_itemcodedtl':
                return $this->get_itemcodedtl($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }
    
    public function chgDate($date){
        
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
        
    }
    
    public function get_table_dtl(Request $request){
        
        $table = DB::table('material.repackdt')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'));

        if(!empty($request->sidx)){
            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $repackhd = DB::table("material.repackhd")
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->first();

            $recno = $repackhd->recno;
            $outqty = $repackhd->outqty;
          
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.repackdt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;
            
            // 2. insert detail
            DB::table('material.repackdt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'deptcode' => strtoupper($request->deptcode),
                    'olditemcode' => $request->olditemcode,
                    'uomcode' => strtoupper($request->uomcode),
                    'inpqty' => $request->inpqty,
                    'avgcost' => floatval($request->avgcost),
                    'amount' => $request->avgcost * $request->inpqty,
                   // 'trandate' => $request->trandate,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',

                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.repackdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');
            
            ///4. then update to header
            DB::table('material.repackhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'amount' => $totalAmount, 
                    'avgcost'=> $totalAmount / $outqty,
                ]);

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->recno = $recno;

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // 1. update detail
            DB::table('finance.glrptfmt')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'lineno_' => $request->lineno_,
                    'printflag' => strtoupper($request->printflag),
                    'rowdef' => $request->rowdef,
                    'code' => strtoupper($request->code),
                    'note' => $request->note,
                    'description' => $request->description,
                    'formula' => $request->formula,
                    'costcodefr' => $request->costcodefr,
                    'costcodeto' => $request->costcodeto,
                    'revsign' => $request->revsign,
                    'upduser'=> session('username'),
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_all(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach ($request->dataobj as $key => $value) {
                
                // 1. update detail
                DB::table('finance.glrptfmt')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'lineno_' => $value['lineno_'],
                        'printflag' => strtoupper($value['printflag']),
                        'rowdef' => $value['rowdef'],
                        'code' => strtoupper($value['code']),
                        'note' => $value['note'],
                        'description' => $value['description'],
                        'formula' => $value['formula'],
                        'costcodefr' => $value['costcodefr'],
                        'costcodeto' => $value['costcodeto'],
                        'revsign' => $value['revsign'],
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error'.$e, 500);
            
        }
        
    }
    
    public function del(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('finance.glrptfmt')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function get_deptcodedtl(Request $request){


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

    public function get_itemcodedtl(Request $request){


        $table = DB::table('material.stockloc as s')
                    ->select('s.itemcode as s_itemcode', 'p.description as p_description', 's.uomcode as s_uomcode', 'p.uomcode as p_uomcode', 'p.avgcost as p_avgcost')
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

