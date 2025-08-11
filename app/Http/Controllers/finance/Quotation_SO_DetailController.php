<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class Quotation_SO_DetailController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
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

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            case 'get_itemcode_price':
                // if(!empty($request->searchCol2)){
                //     return $this->get_itemcode_price_2($request);
                // }else{
                    return $this->get_itemcode_price($request);
                // }
            case 'get_itemcode_price_check':
                return $this->get_itemcode_price_check($request);
            case 'get_itemcode_uom':
                return $this->get_itemcode_uom($request);
            case 'get_itemcode_uom_check':
                return $this->get_itemcode_uom_check($request);
            case 'get_itemcode_uom_check_oe':
                return $this->get_itemcode_uom_check_oe($request);
            case 'get_itemcode_uom_recv':
                return $this->get_itemcode_uom_recv($request);
            case 'get_itemcode_uom_recv_check':
                return $this->get_itemcode_uom_recv_check($request);
            case 'get_itemcode_uom_recv_check_oe':
                return $this->get_itemcode_uom_recv_check_oe($request);
            case 'get_mmacode':
                return $this->get_mmacode($request);
            case 'get_billtype':
                return $this->get_billtype($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_dtl(Request $request){
        $table = DB::table('finance.salesum as ss')
                    ->select('ss.idno','ss.compcode','ss.lineno_','ss.rowno','ss.chggroup','ss.chggroup as chggroup_ori','ss.description','ss.uom','ss.uom as uom_ori','ss.uom_recv','ss.taxcode','ss.unitprice','ss.quantity','ss.billtypeperct','ss.billtypeamt','ss.taxamt','ss.discamt','ss.amount','ss.totamount','ss.recstatus','ss.qtyonhand','ss.qtydelivered')
                    // ->leftjoin('material.stockloc as st', function($join) use ($request){
                    //         $join = $join->where('st.compcode', '=', session('compcode'));
                    //         $join = $join->where('st.unit', '=', session('unit'));
                    //         $join = $join->on('st.itemcode', '=', 'bs.chggroup');
                    //         $join = $join->on('st.uomcode', '=', 'bs.uom');
                    //         $join = $join->where('st.deptcode', '=', $request->deptcode);
                    //         $join = $join->where('st.year', '=', Carbon::now('Asia/Kuala_Lumpur')->year);
                    //     })
                    ->where('ss.source','=',$request->source)
                    ->where('ss.trantype','=',$request->trantype)
                    ->where('ss.auditno','=',$request->auditno)
                    ->where('ss.compcode','=',session('compcode'))
                    ->where('ss.recstatus','<>','DELETE')
                    ->orderBy('ss.idno','desc');
        
        //////////paginate/////////
        $paginate = $table->paginate($request->rows);
        
        foreach ($paginate->items() as $key => $value) {
            $value->qty_outstanding = $value->quantity - $value->qtydelivered;
        }
        
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
   
    public function get_itemcode_price(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $billtype_obj = $this->billtype_obj_get($request);

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        // ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','pt.generic','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','pt.generic','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->where('cm.unit','=',session('unit'))
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE');
                        // ->where(function ($query) {
                        //    $query->whereNotNull('st.idno')
                        //          ->orWhere('cm.invflag', '=', 0);
                        // });

        // $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
        //                     $join = $join->on('cp.uom', '=', 'cm.uom');
        //                     if($request->from != 'chgcode_dfee'){
        //                         $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
        //                     }
        //                     $join = $join->where('cp.effdate', '<=', $entrydate);
        //                 });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'cm.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        // $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.optax', '=', 'tm.taxcode');
        //                 });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        // if(!empty($request->searchCol)){
        //     $searchCol_array = $request->searchCol;
        //     if($searchCol_array[0] == 'generic'){
        //         $table->Where('pt.'.$searchCol_array[0],'like','%'.$request->wholeword.'%');
        //     }else{
        //         $table->Where('cm.'.$searchCol_array[0],'like','%'.$request->wholeword.'%');
        //     }
        // }

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            // if($searchCol_array[0] == 'generic'){
            //     $table = $table->whereRaw("MATCH (pt.generic) AGAINST ('".$this->clean($request->wholeword)."*' IN BOOLEAN MODE)");
            // }else if($searchCol_array[0] == 'description'){
            //     $table = $table->whereRaw("MATCH (cm.description) AGAINST ('".$this->clean($request->wholeword)."*' IN BOOLEAN MODE)");
            // }else{
            //     $table->Where('cm.'.$searchCol_array[$key],'like','%'.$request->wholeword.'%');
            // }

            // dd($this->fullTextWildcards($request->wholeword));
            $wholeword = false;
            if($searchCol_array[0] == 'generic'){
                $pt = DB::table('material.product')
                            ->where('compcode', '=', session('compcode'))
                            ->where('unit', '=', session('unit'))
                            ->where('generic',$request->wholeword);
                
                if($pt->exists()){
                    $table = $table->where('pt.generic',$request->wholeword);
                    $wholeword = true;
                }

            }else if($searchCol_array[0] == 'description'){
                $pt = DB::table('hisdb.chgmast')
                            ->where('compcode', '=', session('compcode'))
                            ->where('unit', '=', session('unit'))
                            ->where('description',$request->wholeword);
                
                if($pt->exists()){
                    $table = $table->where('cm.description',$request->wholeword);
                    $wholeword = true;
                }
            }

            if(!$wholeword){
                $count = array_count_values($searchCol_array);

                foreach ($count as $key => $value) {
                    $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                    $table = $table->Where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                        foreach ($searchCol_array as $key => $value) {
                            $found = array_search($key,$occur_ar);
                            if($found !== false && trim($request->searchVal[$key]) != '%%'){//trim whitespace
                                $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                                if($searchCol_array[0] == 'generic'){
                                    $table->Where('pt.'.$searchCol_array[$key],'like',$search_);
                                }else{
                                    $table->Where('cm.'.$searchCol_array[$key],'like',$search_);
                                }
                            }
                        }
                    });
                }
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            
            $wholeword = false;
            $pt = DB::table('hisdb.chgmast')
                        ->where('compcode', '=', session('compcode'))
                        ->where('unit', '=', session('unit'))
                        ->where('chgcode',$request->wholeword);
            
            if($pt->exists()){
                $table = $table->where('cm.chgcode',$request->wholeword);
                $wholeword = true;
            }

            if(!$wholeword){
                $pt = DB::table('hisdb.chgmast')
                            ->where('compcode', '=', session('compcode'))
                            ->where('unit', '=', session('unit'))
                            ->where('description',$request->wholeword);
                
                if($pt->exists()){
                    $table = $table->where('cm.description',$request->wholeword);
                    $wholeword = true;
                }
            }

            if(!$wholeword){
                $table = $table->where(function($table) use ($request){
                    $table->orwhere('cm.chgcode','like', '%'.$request->wholeword.'%');
                    $table->orwhere('cm.description','like', '%'.$request->wholeword.'%');
                });
            }
        }

        // if(!empty($request->searchCol2)){
        //     $table = $table->where(function($table) use ($request){
        //         $table->orwhere('cm.chgcode','like', '%'.$request->wholeword.'%');
        //         $table->orwhere('cm.description','like', '%'.$request->wholeword.'%');
        //     });
        // }

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        if(!empty($request->whereNotInCol)){
            foreach ($request->whereNotInCol as $key => $value) {
                $table = $table->whereNotIn($value,explode(",",$request->whereNotInVal[$key][0]));
            }
        }

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
        }else{
            $table = $table->orderBy('cm.idno','desc');
        }

        $paginate = $table->paginate($request->rows);
        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
            $value->billty_amount = $billtype_amt_percent->amount; 
            $value->billty_percent = $billtype_amt_percent->percent_;

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno','cp.'.$cp_fld.' as price','cp.optax as taxcode','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uom)
                ->whereDate('cp.effdate', '<=', $entrydate)
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                $value->price = $chgprice_obj->price;
                $value->taxcode = $chgprice_obj->taxcode;
                $value->rate = $chgprice_obj->rate;

                // if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                //     unset($rows[$key]);
                //     continue;
                // }
            }
        }

        $rows = array_values($rows);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_price_2(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $serch_chgcode = substr($request->searchVal2[0], 1);
        $billtype_obj = $this->billtype_obj_get($request);

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','pt.generic','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->Where('cm.chgcode','like',$serch_chgcode)
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->orderBy('cm.idno','desc');
                        // ->where(function ($query) {
                        //    $query->whereNotNull('st.idno')
                        //          ->orWhere('cm.invflag', '=', 0);
                        // });

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->whereNotNull('cp.effdate');
                            $join = $join->where('cp.effdate', '<=', $entrydate);
                        });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'cm.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        $table_count = $table->count();

        if($table_count>0){
            $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->Where('cm.chgcode','like',$serch_chgcode)
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });

            $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                                $join = $join->on('cp.uom', '=', 'cm.uom');
                                if($request->from != 'chgcode_dfee'){
                                    $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                                }
                                $join = $join->where('cp.effdate', '<=', $entrydate);
                            });

            $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                                $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                                $join = $join->on('st.uomcode', '=', 'cm.uom');
                                $join = $join->where('st.compcode', '=', session('compcode'));
                                $join = $join->where('st.unit', '=', session('unit'));
                                $join = $join->where('st.deptcode', '=', $deptcode);
                                $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                            });

            $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'cm.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

            $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.optax', '=', 'tm.taxcode');
                            });
        
            $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

            if(!empty($request->filterCol)){
                foreach ($request->filterCol as $key => $value) {
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }

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
            }else{
                $table = $table->orderBy('cm.idno','desc');
            }

            $paginate = $table->paginate($request->rows);
            $rows = $paginate->items();

            foreach ($rows as $key => $value) {
                $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
                $value->billty_amount = $billtype_amt_percent->amount; 
                $value->billty_percent = $billtype_amt_percent->percent_;

                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                    ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $value->chgcode)
                    ->where('cp.uom', '=', $value->uom)
                    ->whereDate('cp.effdate', '<=', $entrydate)
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();

                    if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                        unset($rows[$key]);
                        continue;
                    }
                }
            }

            $rows = array_values($rows);

            //////////paginate/////////
            // $paginate = $table->paginate($request->rows);

            $responce = new stdClass();
            $responce->page = $paginate->currentPage();
            $responce->total = $paginate->lastPage();
            $responce->records = $paginate->total();
            // $responce->rows = $paginate->items();
            $responce->rows = $rows;
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            $responce->sql_query = $this->getQueries($table);

            return json_encode($responce);
        }else{

            $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });

            $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                                $join = $join->on('cp.uom', '=', 'cm.uom');
                                if($request->from != 'chgcode_dfee'){
                                    $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                                }
                                $join = $join->where('cp.effdate', '<=', $entrydate);
                            });

            $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                                $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                                $join = $join->on('st.uomcode', '=', 'cm.uom');
                                $join = $join->where('st.compcode', '=', session('compcode'));
                                $join = $join->where('st.unit', '=', session('unit'));
                                $join = $join->where('st.deptcode', '=', $deptcode);
                                $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                            });

            $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'cm.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

            $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.optax', '=', 'tm.taxcode');
                            });

            $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

            if(!empty($request->searchCol)){
                $searchCol_array = $request->searchCol;

                $count = array_count_values($searchCol_array);

                foreach ($count as $key => $value) {
                    $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                    $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                        foreach ($searchCol_array as $key => $value) {
                            $found = array_search($key,$occur_ar);
                            if($found !== false && trim($request->searchVal[$key]) != '%%'){
                                $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                                $table->Where('cm.'.$searchCol_array[$key],'like',$search_);
                                // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                                // $table->Where('cm.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                            }
                        }
                    });
                }
            }

            if(!empty($request->searchCol2)){
                $searchCol_array = $request->searchCol2;
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key>1) break;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });

                if(count($searchCol_array)>2){
                    $table = $table->where(function($table) use ($searchCol_array, $request){
                        foreach ($searchCol_array as $key => $value) {
                            if($key<=1) continue;
                            // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                            $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                        }
                    });
                }
            }

            if(!empty($request->filterCol)){
                foreach ($request->filterCol as $key => $value) {
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }

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
            }else{
                $table = $table->orderBy('cm.idno','desc');
            }

            $paginate = $table->paginate($request->rows);
            $rows = $paginate->items();

            foreach ($rows as $key => $value) {
                $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
                $value->billty_amount = $billtype_amt_percent->amount; 
                $value->billty_percent = $billtype_amt_percent->percent_;

                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                    ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $value->chgcode)
                    ->where('cp.uom', '=', $value->uom)
                    ->whereDate('cp.effdate', '<=', $entrydate)
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();

                    if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                        unset($rows[$key]);
                        continue;
                    }
                }
            }

            $rows = array_values($rows);

            //////////paginate/////////
            // $paginate = $table->paginate($request->rows);

            $responce = new stdClass();
            $responce->page = $paginate->currentPage();
            $responce->total = $paginate->lastPage();
            $responce->records = $paginate->total();
            // $responce->rows = $paginate->items();
            $responce->rows = $rows;
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            $responce->sql_query = $this->getQueries($table);

            return json_encode($responce);
        }

    }

    public function get_itemcode_price_check(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $chgcode = $request->chgcode;
        $uom = $request->uom;

        $table = DB::table('hisdb.chgmast as cm')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->where('cm.chgcode','=',$chgcode)
                        ->where('cm.uom','=',$uom);

        $result = $table->get()->toArray();

        // $billtype_obj = $this->billtype_obj_get($request);

        // switch ($priceuse) {
        //     case 'PRICE1':
        //         $cp_fld = 'amt1';
        //         break;
        //     case 'PRICE2':
        //         $cp_fld = 'amt2';
        //         break;
        //     case 'PRICE3':
        //         $cp_fld = 'amt3';
        //         break;
        //     default:
        //         $cp_fld = 'costprice';
        //         break;
        // }

        // $table = DB::table('hisdb.chgmast as cm')
        //                 ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
        //                 ->where('cm.compcode','=',session('compcode'))
        //                 ->where('cm.recstatus','<>','DELETE')
        //                 ->where('cm.chgcode','=',$chgcode)
        //                 ->where('cm.uom','=',$uom);
        //                 // ->where(function ($query) {
        //                 //    $query->whereNotNull('st.idno')
        //                 //          ->orWhere('cm.invflag', '=', 0);
        //                 // });

        // $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
        //                     $join = $join->on('cp.uom', '=', 'cm.uom');
        //                     if($request->from != 'chgcode_dfee'){
        //                         $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
        //                     }
        //                     $join = $join->where('cp.effdate', '<=', $entrydate);
        //                 });

        // $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
        //                     $join = $join->on('st.itemcode', '=', 'cm.chgcode');
        //                     $join = $join->on('st.uomcode', '=', 'cm.uom');
        //                     $join = $join->where('st.compcode', '=', session('compcode'));
        //                     $join = $join->where('st.unit', '=', session('unit'));
        //                     $join = $join->where('st.deptcode', '=', $deptcode);
        //                     $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
        //                 });

        // $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
        //                     $join = $join->where('pt.compcode', '=', session('compcode'));
        //                     $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
        //                     $join = $join->on('pt.uomcode', '=', 'cm.uom');
        //                     $join = $join->where('pt.unit', '=', session('unit'));
        //                 });

        // $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.optax', '=', 'tm.taxcode');
        //                 });

        // $table = $table->join('material.uom as uom', function($join){
        //                     $join = $join->on('uom.uomcode', '=', 'cm.uom')
        //                                 ->where('uom.compcode', '=', session('compcode'))
        //                                 ->where('uom.recstatus','=','ACTIVE');
        //             });

        // $result = $table->get()->toArray();

        // foreach ($result as $key => $value) {
        //     $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
        //     $value->billty_amount = $billtype_amt_percent->amount; 
        //     $value->billty_percent = $billtype_amt_percent->percent_;

        //     $chgprice_obj = DB::table('hisdb.chgprice as cp')
        //         ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
        //         ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
        //         ->where('cp.compcode', '=', session('compcode'))
        //         ->where('cp.chgcode', '=', $value->chgcode)
        //         ->where('cp.uom', '=', $value->uom)
        //         ->whereDate('cp.effdate', '<=', $entrydate)
        //         ->orderBy('cp.effdate','desc');

        //     if($chgprice_obj->exists()){
        //         $chgprice_obj = $chgprice_obj->first();

        //         if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
        //             unset($result[$key]);
        //             continue;
        //         }
        //     }
        // }

        // $table =  DB::table('hisdb.chgmast as cm')
        //             ->select('cm.chgcode','cm.description')
        //             ->where('cm.compcode',session('compcode'))
        //             ->where('cm.recstatus','<>','DELETE')
        //             ->where('cm.chgcode','=',$request->filterVal[2]);

        // $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
        //                     $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
        //                     $join = $join->whereNotNull('cp.effdate');
        //                     $join = $join->where('cp.effdate', '<=', $entrydate);
        //                 });

        // $table = $table->join('material.stockloc as st', function($join) use ($deptcode,$entrydate){
        //                     $join = $join->on('st.itemcode', '=', 'cm.chgcode');
        //                     $join = $join->where('st.compcode', '=', session('compcode'));
        //                     $join = $join->where('st.unit', '=', session('unit'));
        //                     $join = $join->where('st.deptcode', '=', $deptcode);
        //                     $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
        //                 });

        $responce = new stdClass();
        $responce->rows = $result;
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_uom(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $entrydate = $request->entrydate;
        $priceuse = $request->price;
        $billtype_obj = $this->billtype_obj_get($request);

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description as chgdesc','pt.generic','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->where('cp.effdate', '<=', $entrydate);
                        });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                        $join = $join->on('pt.uomcode', '=', 'cm.uom');
                        $join = $join->where('pt.unit', '=', session('unit'));
                    });

        $table = $table->join('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        // $table = DB::table('material.stockloc as st')
        //                     ->select('st.idno','st.idno','uom.uomcode','uom.description','uom.convfactor')
        //                     ->where('st.compcode', '=', session('compcode'))
        //                     ->where('st.unit', '=', session('unit'))
        //                     ->where('st.deptcode', '=', $deptcode)
        //                     ->where('st.itemcode', '=', $chgcode)
        //                     ->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));

        


        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            $table->Where('uom.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

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
        }else{
            $table = $table->orderBy('uom.idno','desc');
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
            $value->billty_amount = $billtype_amt_percent->amount; 
            $value->billty_percent = $billtype_amt_percent->percent_;

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uomcode)
                ->whereDate('cp.effdate', '<=', $entrydate)
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                    unset($rows[$key]);
                    continue;
                }
            }
        }


        $rows = array_values($rows);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_uom_check(Request $request){
        $deptcode = $request->deptcode;
        $chgcode = $request->chgcode;
        $uom = $request->uom;
        $entrydate = $request->entrydate;

        // $table = DB::table('material.stockloc as st')
        //                     ->select('st.idno','st.idno','uom.uomcode','uom.description','uom.convfactor')
        //                     ->where('st.compcode', '=', session('compcode'))
        //                     ->where('st.unit', '=', session('unit'))
        //                     ->where('st.deptcode', '=', $deptcode)
        //                     ->where('st.itemcode', '=', $chgcode)
        //                     ->where('st.uomcode', '=', $uom)
        //                     ->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));

        // $table = $table->join('material.uom as uom', function($join) use ($chgcode){
        //                     $join = $join->on('uom.uomcode', '=', 'st.uomcode')
        //                                 ->where('uom.compcode', '=', session('compcode'))
        //                                 ->where('uom.recstatus','=','ACTIVE');
        //             });

        
        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','uom.description','cm.uom as uomcode','uom.convfactor')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.uom','=',$uom)
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $responce = new stdClass();
        $responce->rows = $table->get();

        return json_encode($responce);
    }

    public function get_itemcode_uom_check_oe(Request $request){
        $deptcode = $request->deptcode;
        $chgcode = $request->chgcode;
        $uom = $request->uom;
        $entrydate = $request->entrydate;

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','uom.description','cm.uom as uomcode','uom.convfactor')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.uom','=',$uom)
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        // $table = DB::table('material.stockloc as st')
        //                     ->select('st.idno','st.idno','uom.uomcode','uom.description','uom.convfactor')
        //                     ->where('st.compcode', '=', session('compcode'))
        //                     ->where('st.unit', '=', session('unit'))
        //                     ->where('st.deptcode', '=', $deptcode)
        //                     ->where('st.itemcode', '=', $chgcode)
        //                     ->where('st.uomcode', '=', $uom)
        //                     ->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));

        // $table = $table->join('material.uom as uom', function($join) use ($chgcode){
        //                     $join = $join->on('uom.uomcode', '=', 'st.uomcode')
        //                                 ->where('uom.compcode', '=', session('compcode'))
        //                                 ->where('uom.recstatus','=','ACTIVE');
        //             });

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }



    public function get_itemcode_uom_recv(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $entrydate = $request->entrydate;
        $priceuse = $request->price;
        $billtype_obj = $this->billtype_obj_get($request);

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->where('cp.effdate', '<=', $entrydate);
                        });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->join('material.product as pt', function($join) use ($deptcode,$entrydate){
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                        $join = $join->on('pt.uomcode', '=', 'cm.uom');
                        $join = $join->where('pt.unit', '=', session('unit'));
                    });

        $table = $table->join('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            $table->Where('uom.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

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
        }else{
            $table = $table->orderBy('uom.idno','desc');
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
            $value->billty_amount = $billtype_amt_percent->amount; 
            $value->billty_percent = $billtype_amt_percent->percent_;

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uomcode)
                ->whereDate('cp.effdate', '<=', $entrydate)
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                    unset($rows[$key]);
                    continue;
                }
            }
        }


        $rows = array_values($rows);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_uom_recv_check(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $uom = $request->uom;
        $entrydate = $request->entrydate;

        $table = DB::table('material.stockloc as st')
                        ->select('uom.description','st.uomcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
                            ->where('st.compcode','=',session('compcode'))
                            ->where('st.unit','=',session('unit'))
                            ->where('st.deptcode','=',$deptcode)
                            ->where('st.year','=',Carbon::parse($entrydate)->format('Y'))
                            ->where('st.itemcode','=',$chgcode)
                            ->where('st.uomcode','=',$uom)
                            ->where('st.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'st.uomcode')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->join('material.product as pt', function($join) use ($deptcode,$entrydate){
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->on('pt.itemcode', '=', 'st.itemcode');
                        $join = $join->on('pt.uomcode', '=', 'st.uomcode');
                        $join = $join->where('pt.unit', '=', session('unit'));
                    });

        $responce = new stdClass();
        $responce->rows = $table->get();

        return json_encode($responce);
    }

    public function get_itemcode_uom_recv_check_oe(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $uom = $request->uom;
        $entrydate = $request->entrydate;

        $table = DB::table('material.stockloc as st')
                        ->select('st.itemcode','uom.description','st.uomcode as uomcode','pt.avgcost','uom.convfactor')
                            ->where('st.compcode','=',session('compcode'))
                            ->where('st.unit','=',session('unit'))
                            ->where('st.deptcode','=',$deptcode)
                            ->where('st.year','=',Carbon::parse($entrydate)->format('Y'))
                            ->where('st.itemcode','=',$chgcode)
                            ->where('st.uomcode','=',$uom)
                            ->where('st.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });
        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'st.uomcode')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->join('material.product as pt', function($join) use ($chgcode,$uom){
                            $join = $join->on('pt.itemcode', '=', 'st.itemcode')
                                        ->on('pt.uomcode', '=', 'st.uomcode')
                                        ->where('pt.compcode', '=', session('compcode'))
                                        ->where('pt.unit','=',session('unit'))
                                        ->where('pt.recstatus','=','ACTIVE');
                    });

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_mmacode(Request $request){
        $table = DB::table('hisdb.mmamaster as mmam')
                    ->select('mmam.mmacode','mmam.description','mmam.version','mmap.mmaconsult')
                    ->where('mmam.compcode',session('compcode'))
                    ->where('mmam.recstatus','ACTIVE')
                    ->join('hisdb.mmaprice as mmap', function($join) use ($request){
                            $join = $join->on('mmap.mmacode', '=', 'mmam.mmacode')
                                        ->on('mmap.version', '=', 'mmam.version')
                                        ->where('mmap.compcode', '=', session('compcode'));
                    });

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

    public function get_billtype(Request $request){
        $table = DB::table('hisdb.billtymst as bm')
                        ->select(
                            'bm.billtype as bm_billtype',
                            'bm.service as bm_service',
                            'bm.percent_ as bm_percent',
                            'bm.amount as bm_amount',
                            'bs.chggroup as bs_chggroup',
                            'bs.allitem as bs_allitem',
                            'bs.percent_ as bs_percent',
                            'bs.amount as bs_amount',
                            'bi.chgcode as bi_chgcode',
                            'bi.percent_ as bi_percent',
                            'bi.amount as bi_amount'
                        )
                        ->leftjoin('hisdb.billtysvc as bs', function($join){
                            $join = $join->where('bs.compcode', '=', session('compcode'));
                            $join = $join->on('bs.billtype', '=', 'bm.billtype');
                            $join = $join->where('bm.service', '=', '0');
                        })
                        ->leftjoin('hisdb.billtyitem as bi', function($join){
                            $join = $join->where('bi.compcode', '=', session('compcode'));
                            $join = $join->on('bi.billtype', '=', 'bs.billtype');
                            $join = $join->where('bs.allitem', '=', '0');
                            $join = $join->on('bi.chggroup', '=', 'bs.chggroup');
                        })
                        ->where('bm.compcode',session('compcode'))
                        ->where('bm.effdatefrom', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                        ->where(function($join){
                               $join = $join->where('bm.effdateto', '>=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                               $join = $join->orWhereNull('bm.effdateto');
                         })
                        ->where('bm.recstatus','ACTIVE')
                        ->where('bm.billtype',$request->billtype)
                        ->orderBy('bm.idno','desc');

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);
        return json_encode($responce);

    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
    }

    public function add(Request $request){
        
        $source = $request->source;
        $trantype = $request->trantype;
        $auditno = ltrim($request->auditno,"0");
        
        DB::beginTransaction();
        
        try {
            
            $salehdr = DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $salehdr = $salehdr->first();
            
            ////1. calculate rowno by recno
            $sqlln = DB::table('finance.salesum')->select('lineno_')
                        // ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('auditno','=',$auditno)
                        ->max('lineno_');
            
            $li=intval($sqlln)+1;

            // if($request->quantity > $request->qtyonhand){
            //     throw new \Exception("Qty request cant be bigger than qty on hand!",500);
            // }

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$request->chggroup)
                    ->where('uom','=',$request->uom)
                    ->first();

            $qtyonhand = 0;
            if($chgmast->invflag == '1'){
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom)
                        ->where('itemcode','=',$request->chggroup)
                        ->where('deptcode','=',$salehdr->deptcode)
                        ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                }else{
                    throw new \Exception("Stockloc not exists for item: ".$request->chggroup." dept: ".$salehdr->deptcode." uom: ".$request->uom,500);
                }

                $qtyonhand = $stockloc->qtyonhand;
            }
            
            $quantity = floatval($request->quantity);
            $amount = $request->unitprice * $quantity;
            $discamt = ($amount * (100-$request->billtypeperct) / 100) + $request->billtypeamt;
            $rate = $this->taxrate($request->taxcode);
            $taxamt = $amount * $rate / 100;
            $totamount = $amount - $discamt + $taxamt;
            
            ///2. insert detail
            DB::table('finance.salesum')
                ->insertGetId([
                    // 'auditno' => $recno, // ->OE IN
                    'auditno' => $auditno, // dari salesum.auditno
                    // 'idno' => $recno, // autogen
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'chggroup' => $request->chggroup,
                    'description' => $request->description,
                    'lineno_' => $li,
                    // 'rowno' => $li,
                    'mrn' => (!empty($salehdr->mrn))?$salehdr->mrn:null,
                    'episno' => (!empty($salehdr->episno))?$salehdr->episno:null,
                    'uom' => $request->uom,
                    'uom_recv' => $request->uom,
                    'taxcode' => $request->taxcode,
                    'unitprice' => $request->unitprice,
                    'quantity' => $request->quantity,
                    'qtyonhand' => $request->qtyonhand,
                    'qtydelivered' => '0',
                    'amount' => $request->amount, // unitprice * quantity, xde tax
                    'outamt' => $request->amount,
                    'totamount' => $request->totamount,
                    'discamt' => floatval($request->discamt),
                    'taxamt' => floatval($request->taxamt),
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'taxcode' => $request->taxcode,
                    'billtypeperct' => $request->billtypeperct,
                    'billtypeamt' => $request->billtypeamt,
                ]);
            
            // $product = DB::table('material.product')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('uomcode','=',$request->uom)
            //                 ->where('itemcode','=',$request->chggroup);
            
            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.salesum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            if($salehdr->amount != $salehdr->outamount){
                $variance = $totalAmount - $salehdr->amount;
                $outamount_after =  $salehdr->outamount + $variance;

                $new_amt = $totalAmount;
                $new_outamt = $outamount_after;
            }else{

                $new_amt = $totalAmount;
                $new_outamt = $totalAmount;
            }
            
            ///4. then update to header
            DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $new_amt,
                        'outamount' => $new_outamt,
                    ]);
            
            echo $totalAmount;
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function edit(Request $request){
        DB::beginTransaction();
        
        try {
            
            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = ltrim($request->auditno, "0");
            
            $salehdr = DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $salehdr = $salehdr->first();

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$request->chggroup)
                    ->where('uom','=',$request->uom)
                    ->first();

            if($chgmast->invflag == '1'){
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom)
                        ->where('itemcode','=',$request->chggroup)
                        ->where('deptcode','=',$salehdr->deptcode)
                        ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                }else{
                    throw new \Exception("Stockloc not exists for item: ".$request->chggroup." dept: ".$salehdr->deptcode." uom: ".$request->uom,500);
                }
            }

            // $qtyonhand = $stockloc->qtyonhand;
            $quantity = floatval($request->quantity);
            $amount = $request->unitprice * $quantity;
            $discamt = ($amount * (100-$request->billtypeperct) / 100) + $request->billtypeamt;
            $rate = $this->taxrate($request->taxcode);
            $taxamt = $amount * $rate / 100;
            $totamount = $amount - $discamt + $taxamt;

            DB::table('finance.salesum')
                    ->where('compcode','=',session('compcode'))
                    // ->where('source','=',$source)
                    // ->where('trantype','=',$trantype)
                    // ->where('billno','=',$auditno)
                    ->where('idno','=',$request->idno)
                    // ->where('rowno','=',$request->rowno)
                    ->update([
                        'unitprice' => $request->unitprice,
                        'quantity' => $quantity,
                        'qtyonhand' => $request->qtyonhand,
                        'amount' => $amount,
                        'outamt' => $amount,
                        'discamt' => floatval($discamt),
                        'taxamt' => floatval($taxamt),
                        'totamount' => floatval($totamount),
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'billtypeperct' => $request->billtypeperct,
                        'billtypeamt' => $request->billtypeamt,
                    ]);
                
            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.salesum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');
            
            ///4. then update to header
            DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount,
                    ]);
            
            DB::commit();
            
            echo $totalAmount;
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }        
    }

    public function edit_all(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = ltrim($request->auditno, "0");
            
            $salehdr = DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $salehdr = $salehdr->first();
            
            foreach ($request->dataobj as $key => $value) {
                
                // salesum lama
                // $salesum_lama = DB::table('finance.salesum')
                //             ->where('compcode','=',session('compcode'))
                //             ->where('source','=',$source)
                //             ->where('trantype','=',$trantype)
                //             ->where('auditno','=',$auditno)
                //             ->where('lineno_','=',$value['lineno_'])
                //             ->first();
                
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$value['uom'])
                        ->where('itemcode','=',$value['chggroup'])
                        ->where('deptcode','=',$salehdr->deptcode)
                        ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                        ->first();

                // if($value['quantity'] > $stockloc->qtyonhand){
                //     throw new \Exception("Qty request (".$value['quantity'].") cant be bigger than qty on hand! (".$stockloc->qtyonhand.") on itemcode: ".$value['chggroup'],500);
                // }

                ///2. update detail
                DB::table('finance.salesum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('auditno','=',$auditno)
                            ->where('lineno_','=',$value['lineno_'])
                            ->update([
                                // 'chggroup' => $value['chggroup'],
                                // 'description' => $chgmast->first()->description,
                                // 'uom' => $value['uom'],
                                // 'uom_recv' => $value['uom_recv'],
                                'taxcode' => $value['taxcode'],
                                'unitprice' => $value['unitprice'],
                                'quantity' => $value['quantity'],
                                'qtyonhand' => $stockloc->qtyonhand,
                                'qtydelivered' => $value['qtydelivered'],
                                'amount' => $value['unitprice'] * $value['quantity'],
                                'outamt' => $value['unitprice'] * $value['quantity'],
                                'discamt' => floatval($value['discamt']),
                                'taxamt' => floatval($value['taxamt']),
                                'totamount' => floatval($value['unitprice'] * $value['quantity']),
                                'lastuser' => session('username'), 
                                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);
                
                // $salesum_obj = DB::table('finance.salesum')
                //                 ->where('compcode','=',session('compcode'))
                //                 ->where('source','=',$source)
                //                 ->where('trantype','=',$trantype)
                //                 ->where('auditno','=',$auditno)
                //                 ->where('rowno','=',$value['rowno'])
                //                 ->first();

                // $product = DB::table('material.product')
                //         ->where('compcode','=',session('compcode'))
                //         ->where('uomcode','=',$value['uom'])
                //         ->where('itemcode','=',$value['chggroup']);
                
                // if($product->exists()){
                //     $stockloc = DB::table('material.stockloc')
                //             ->where('compcode','=',session('compcode'))
                //             ->where('uomcode','=',$value['uom'])
                //             ->where('itemcode','=',$value['chggroup'])
                //             ->where('deptcode','=',$salehdr->deptcode)
                //             ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                    
                //     if($stockloc->exists()){
                //         $stockloc = $stockloc->first();
                //     }else{
                //         throw new \Exception("Stockloc not exists for item: ".$value['chggroup']." dept: ".$salehdr->deptcode." uom: ".$value['uom'],500);
                //     }
                    
                //     $ivdspdt = DB::table('material.ivdspdt')
                //         ->where('compcode','=',session('compcode'))
                //         ->where('recno','=',$salesum_obj->auditno);

                //     if($edit_lain_chggroup){
                //         $ivdspdt_idno = $this->crtivdspdt($salesum_obj,$salehdr);
                //         $this->crtgltran($ivdspdt_idno,$salehdr);
                //     }else{
                //         if($ivdspdt->exists()){
                //             $this->updivdspdt($salesum_obj,$salehdr);
                //             $this->updgltran($ivdspdt->first()->idno,$salehdr);
                //         }else{
                //             $ivdspdt_idno = $this->crtivdspdt($salesum_obj,$salehdr);
                //             $this->crtgltran($ivdspdt_idno,$salehdr);
                //         }
                //     }
                // }
            }

            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.salesum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            if($salehdr->amount != $salehdr->outamount){
                $variance = $totalAmount - $salehdr->amount;
                $outamount_after =  $salehdr->outamount + $variance;

                $new_amt = $totalAmount;
                $new_outamt = $outamount_after;
            }else{

                $new_amt = $totalAmount;
                $new_outamt = $totalAmount;
            }
            
            ///4. then update to header
            DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount,
                    ]);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = $request->auditno;
            $idno = $request->idno;

            $salehdr = DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $salehdr = $salehdr->first();

            // $salesum = DB::table('finance.salesum')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('idno','=',$idno);

            // $salesum_obj = $salesum->first();

            // $chgmast_lama = DB::table('hisdb.chgmast')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('uom','=',$salesum_obj->uom)
            //         ->where('chgcode','=',$salesum_obj->chggroup)
            //         ->first();

            // if($chgmast_lama->invflag != '0'){
            //     $this->delivdspdt($salesum_obj,$salehdr);
            // }else{
            //     $this->delgltran($salesum_obj,$salehdr);
            // }

            // //pindah yang lama ke billsumlog sebelum update
            // //recstatus->delete

            // $salesum_lama = DB::table('finance.salesum')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('idno','=',$idno)
            //                 ->first();

            // $this->sysdb_log('delete',$salesum_lama,'sysdb.billsumlog');

            DB::table('finance.salesum')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$idno)
                    ->delete();

            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.salesum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

            if($salehdr->amount != $salehdr->outamount){
                $variance = $totalAmount - $salehdr->amount;
                $outamount_after =  $salehdr->outamount + $variance;

                $new_amt = $totalAmount;
                $new_outamt = $outamount_after;
            }else{

                $new_amt = $totalAmount;
                $new_outamt = $totalAmount;
            }

            ///4. then update to header
            DB::table('finance.salehdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $new_amt,
                        'outamount' => $new_outamt,
                    ]);

            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
        
    }

    public function crtivdspdt($salesum_obj,$salehdr){

        $my_uom = $salesum_obj->uom;
        $my_chggroup = $salesum_obj->chggroup;

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chggroup)
            ->where('deptcode','=',$salehdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        $convuom_recv = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$salesum_obj->uom_recv)
            ->first();
        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        $curr_netprice = $product->first()->avgcost;
        $curr_quan = $salesum_obj->quantity * ($convuom_recv / $conv_uom);
        if($stockloc->exists()){
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($salehdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$my_uom)
                                ->where('itemcode','=',$my_chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            DB::table('material.product')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('uomcode','=',$my_uom)
                ->where('itemcode','=',$my_chggroup)
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($salehdr->entrydate))
                ->where('DeptCode','=',$salehdr->deptcode)
                ->where('ItemCode','=',$my_chggroup)
                ->where('UomCode','=',$my_uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $curr_quan;
                $balqty = 0;
                foreach ($expdate_get as $value2) {
                    $balqty = $value2->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

            }else{
                //3.kalu xde Stock Expiry, buat baru
                $BalQty = -$curr_quan;

                DB::table('material.stockexp')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), 
                        'deptcode' => $salehdr->deptcode, 
                        'itemcode' => $my_chggroup, 
                        'uomcode' => $my_uom, 
                        'balqty' => $BalQty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       // 'lasttt' => 'GRN', 
                        'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                    ]);
            }


        }

        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $salesum_obj->auditno,//OE IN
            'lineno_' => 1,
            'itemcode' => $salesum_obj->chggroup,
            'uomcode' => $salesum_obj->uom,
            'txnqty' => $curr_quan,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'saleamt' => $salesum_obj->amount,
            'productcat' => $product->first()->productcat,
            'issdept' => $salehdr->deptcode,
            'reqdept' => $salehdr->deptcode,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'trantype' => 'DS',
            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
            'trxaudno' => $salesum_obj->auditno,
            'mrn' => $this->givenullifempty($salehdr->mrn),
            'episno' => $this->givenullifempty($salehdr->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;
    }

    public function updivdspdt($salesum_obj,$salehdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$salesum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->where('itemcode','=',$salesum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->where('itemcode','=',$salesum_obj->chggroup)
            ->where('deptcode','=',$salehdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        $convuom_recv = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$salesum_obj->uom_recv)
            ->first();
        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
            $curr_quan = $salesum_obj->quantity * ($convuom_recv / $conv_uom);
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($salehdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan)) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$salesum_obj->uom)
                                ->where('itemcode','=',$salesum_obj->chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($salehdr->entrydate))
                ->where('DeptCode','=',$salehdr->deptcode)
                ->where('ItemCode','=',$salesum_obj->chggroup)
                ->where('UomCode','=',$salesum_obj->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan) - floatval($curr_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                $BalQty = floatval($prev_quan) - floatval($curr_quan);

                DB::table('material.stockexp')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), 
                        'deptcode' => $salehdr->deptcode, 
                        'itemcode' => $salesum_obj->chggroup, 
                        'uomcode' => $salesum_obj->uom, 
                        'balqty' => $BalQty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       // 'lasttt' => 'GRN', 
                        'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                    ]);
            }

        }

        $ivdspdt_arr = [
            'txnqty' => $curr_quan,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'saleamt' => $salesum_obj->amount,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        // pindah ke ivdspdtlog
        // recstatus->update
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$salesum_obj->auditno)
                        ->first();

        $this->sysdb_log('update',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$salesum_obj->auditno)
            ->update($ivdspdt_arr);
    }

    public function delivdspdt($salesum_obj,$salehdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$salesum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->where('itemcode','=',$salesum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$salesum_obj->uom)
            ->where('itemcode','=',$salesum_obj->chggroup)
            ->where('deptcode','=',$salehdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($salehdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $product
                ->update([
                    'qtyonhand' => $new_qoh,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($salehdr->entrydate))
                ->where('DeptCode','=',$salehdr->deptcode)
                ->where('ItemCode','=',$salesum_obj->chggroup)
                ->where('UomCode','=',$salesum_obj->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                $BalQty = $curr_quan;

                DB::table('material.stockexp')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), 
                        'deptcode' => $salehdr->deptcode, 
                        'itemcode' => $salesum_obj->chggroup, 
                        'uomcode' => $salesum_obj->uom, 
                        'balqty' => $BalQty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       // 'lasttt' => 'GRN', 
                        'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                    ]);
            }

        }

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$salesum_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt_lama->first()->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$salesum_obj->auditno)
                ->delete();
        }

        // pindah ke ivdspdtlog
        // recstatus->delete
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$salesum_obj->auditno)
                        ->first();

        $this->sysdb_log('delete',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$salesum_obj->auditno)
            ->delete();
    }

    public function crtgltran($ivdspdt_idno,$salehdr){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $ivdspdt->itemcode)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$salehdr->deptcode)
            ->first();
        //utk debit accountcode
        $row_cat = DB::table('material.category')
            ->select('stockacct','cosacct')
            ->where('compcode','=',session('compcode'))
            ->where('catcode','=',$product_obj->productcat)
            ->first();

        $drcostcode = $row_dept->costcode;
        $dracc = $row_cat->cosacct;
        $crcostcode = $row_dept->costcode;
        $cracc = $row_cat->stockacct;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $ivdspdt->recno,//billsum auditno
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $ivdspdt->uomcode,
                'description' => $ivdspdt->itemcode, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $ivdspdt->amount
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $ivdspdt->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcostcode)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $ivdspdt->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function updgltran($ivdspdt_idno,$salehdr){
        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$ivdspdt->recno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $ivdspdt->amount
            ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

        }else{
            throw new \Exception("Gltran doesnt exists");
        }
    }

    public function delgltran($salesum_obj,$salehdr){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$salesum_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            // $gltran->update([
            //     'amount' => $ivdspdt->amount
            // ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$salesum_obj->auditno)
                ->delete();
        }
    }

    public function crtgltran_notinv($salesum_obj,$salehdr){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $salesum_obj->chggroup)
            ->where('uomcode','=', $salesum_obj->uom)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$salehdr->deptcode)
            ->first();
        //utk debit accountcode
        $row_cat = DB::table('material.category')
            ->select('stockacct','cosacct')
            ->where('compcode','=',session('compcode'))
            ->where('catcode','=',$product_obj->productcat)
            ->first();

        $drcostcode = $row_dept->costcode;
        $dracc = $row_cat->cosacct;
        $crcostcode = $row_dept->costcode;
        $cracc = $row_cat->stockacct;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $salesum_obj->auditno,
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $salesum_obj->uom,
                'description' => $salesum_obj->chggroup, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $salesum_obj->amount
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $salesum_obj->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $salesum_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcostcode)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $salesum_obj->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$salesum_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function updgltran_notinv($salesum_obj,$salehdr){

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$salesum_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $ivdspdt->amount
            ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $salesum_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $salesum_obj->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $salesum_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$salesum_obj->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

        }else{
            throw new \Exception("Gltran doesnt exists");
        }
    }

    public function billtype_obj_get(Request $request){
        $billtype_obj = new stdClass();

        $billtymst = DB::table('hisdb.billtymst')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$request->billtype);

        if($billtymst->exists()){
            $billtype_obj->billtype = $billtymst->first();
            $billtype_obj->svc = [];

            $billtysvc = DB::table('hisdb.billtysvc')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$request->billtype);

            if($billtysvc->exists()){
                foreach ($billtysvc->get() as $key => $value) {
                    $billtysvc_obj = new stdClass();
                    $billtysvc_obj->chggroup = $value->chggroup;
                    $billtysvc_obj->svc = $value;

                    $billtyitem = DB::table('hisdb.billtyitem')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('billtype','=',$value->billtype)
                                    ->where('chggroup','=',$value->chggroup);

                    if($billtyitem->exists()){
                        $billtysvc_obj->item = $billtyitem->get()->toArray(); 
                    }
                    array_push($billtype_obj->svc, $billtysvc_obj);
                }
            }

            return $billtype_obj;

        }else{
            throw new \Exception("Wrong billtype");
        }
    }

    public function get_billtype_amt_percent($billtype_obj,$loop_item){
        $billtype_amt_percent = new stdClass();
        $billtype_amt_percent->amount = (empty($billtype_obj->billtype->amount))?0:$billtype_obj->billtype->amount;
        $billtype_amt_percent->percent_ = (empty($billtype_obj->billtype->percent_))?0:$billtype_obj->billtype->percent_;

        if(count($billtype_obj->svc) > 0){

            foreach ($billtype_obj->svc as $key_svc => $svc_obj) {
                if($svc_obj->chggroup == $loop_item->chggroup){
                    $billtype_amt_percent->amount = (empty($svc_obj->svc->amount))?0:$svc_obj->svc->amount;
                    $billtype_amt_percent->percent_ = (empty($svc_obj->svc->percent_))?0:$svc_obj->svc->percent_;

                    if(count($svc_obj->item) > 0){
                        foreach ($svc_obj->item as $key_item => $item_obj){
                            if($item_obj->chgcode == $loop_item->chgcode){
                                $billtype_amt_percent->amount = (empty($item_obj->amount))?0:$item_obj->amount;
                                $billtype_amt_percent->percent_ = (empty($item_obj->percent_))?0:$item_obj->percent_;
                                break;
                            }
                        }
                        break;
                    }
                    break;
                }
            }
        }
        return $billtype_amt_percent;
    }

    public function sysdb_log($oper,$array,$log_table){
        $array_lama = (array)$array;
        $array_lama['logstatus'] = $oper;

        DB::table($log_table)
                ->insert($array_lama);
    }

}

