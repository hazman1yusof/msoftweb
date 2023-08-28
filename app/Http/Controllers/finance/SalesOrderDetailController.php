<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class SalesOrderDetailController extends defaultController
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
                if(!empty($request->searchCol2)){
                    return $this->get_itemcode_price_2($request);
                }else{
                    return $this->get_itemcode_price($request);
                }
            case 'get_itemcode_price_check':
                return $this->get_itemcode_price_check($request);
            case 'get_itemcode_uom':
                return $this->get_itemcode_uom($request);
            case 'get_itemcode_uom_check':
                return $this->get_itemcode_uom_check($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_dtl(Request $request){
        $table = DB::table('debtor.billsum as bs')
                    ->select('bs.idno','bs.compcode','bs.lineno_','bs.rowno','bs.chggroup','bs.description','bs.uom','bs.uom_recv','bs.taxcode','bs.unitprice','bs.quantity','bs.billtypeperct','bs.billtypeamt','bs.taxamt','bs.discamt','bs.amount','bs.totamount','bs.recstatus','bs.qtyonhand')
                    // ->leftjoin('material.stockloc as st', function($join) use ($request){
                    //         $join = $join->where('st.compcode', '=', session('compcode'));
                    //         $join = $join->where('st.unit', '=', session('unit'));
                    //         $join = $join->on('st.itemcode', '=', 'bs.chggroup');
                    //         $join = $join->on('st.uomcode', '=', 'bs.uom');
                    //         $join = $join->where('st.deptcode', '=', $request->deptcode);
                    //         $join = $join->where('st.year', '=', Carbon::now('Asia/Kuala_Lumpur')->year);
                    //     })
                    ->where('bs.source','=',$request->source)
                    ->where('bs.trantype','=',$request->trantype)
                    ->where('bs.billno','=',$request->billno)
                    ->where('bs.compcode','=',session('compcode'))
                    ->where('bs.recstatus','<>','DELETE')
                    ->orderBy('bs.idno','desc');

        //////////paginate/////////
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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
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
                            $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
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
        // dd($paginate);
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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
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
                            $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
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
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
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
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
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

        $table =  DB::table('hisdb.chgmast as cm')
                    ->select('cm.chgcode','cm.description')
                    ->where('cm.compcode',session('compcode'))
                    ->where('cm.recstatus','<>','DELETE')
                    ->where('cm.chgcode','=',$request->filterVal[2]);

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            $join = $join->whereNotNull('cp.effdate');
                            $join = $join->where('cp.effdate', '<=', $entrydate);
                        });

        $table = $table->join('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $responce = new stdClass();
        $responce->rows = $table->get();

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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor')
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
                            $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
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
        $entrydate = $request->entrydate;

        $table = DB::table('material.stockloc as st')
                            ->select('st.idno','st.idno','uom.uomcode','uom.description','uom.convfactor')
                            ->where('st.compcode', '=', session('compcode'))
                            ->where('st.unit', '=', session('unit'))
                            ->where('st.deptcode', '=', $deptcode)
                            ->where('st.itemcode', '=', $chgcode)
                            ->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));

        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $responce = new stdClass();
        $responce->rows = $table->get();

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
        
        $recno = $this->recno('OE','IN');
        
        DB::beginTransaction();
        
        try {
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $dbacthdr = $dbacthdr->first();
            
            ////1. calculate rowno by recno
            $sqlln = DB::table('debtor.billsum')->select('rowno')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('billno','=',$auditno)
                        ->count('rowno');
            
            $li=intval($sqlln)+1;
            
            ///2. insert detail
            $insertGetId = DB::table('debtor.billsum')
                ->insertGetId([
                    'auditno' => $recno, //->OE IN
                    'billno' => $auditno, // dari dbacthdr.auditno
                    // 'idno' => $recno, //autogen
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'chggroup' => $request->chggroup,
                    'description' => $request->description,
                    'lineno_' => '1',
                    'rowno' => $li,
                    'mrn' => (!empty($dbacthdr->mrn))?$dbacthdr->mrn:null,
                    'episno' => (!empty($dbacthdr->episno))?$dbacthdr->episno:null,
                    'uom' => $request->uom,
                    'uom_recv' => $request->uom_recv,
                    'taxcode' => $request->taxcode,
                    'unitprice' => $request->unitprice,
                    'quantity' => $request->quantity,
                    'qtyonhand' => $request->qtyonhand,
                    'amount' => $request->amount, //unitprice * quantity, xde tax
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
            
            $billsum_obj = db::table('debtor.billsum')
                            ->where('compcode',session('compcode'))
                            ->where('idno', '=', $insertGetId)
                            ->first();
            
            $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$request->uom)
                            ->where('itemcode','=',$request->chggroup);
            
            if($product->exists()){
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom)
                        ->where('itemcode','=',$request->chggroup)
                        ->where('deptcode','=',$dbacthdr->deptcode)
                        ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                
                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                }else{
                    throw new \Exception("Stockloc not exists for item: ".$billsum_obj->chggroup." dept: ".$dbacthdr->deptcode." uom: ".$billsum_obj->uom,500);
                }
                
                $ivdspdt = DB::table('material.ivdspdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$billsum_obj->auditno);
                
                if($ivdspdt->exists()){
                    $this->updivdspdt($billsum_obj,$dbacthdr);
                    $this->updgltran($ivdspdt->first()->idno,$dbacthdr);
                }else{
                    $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$dbacthdr);
                    $this->crtgltran($ivdspdt_idno,$dbacthdr);
                }
            }
            
            ///3. calculate total amount from detail
            $totalAmount = DB::table('debtor.billsum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('billno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');
            
            ///4. then update to header
            
            
            DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount,
                    ]);
            
            echo $totalAmount;
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
        
    }

    public function edit_all(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = ltrim($request->auditno, "0");
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $dbacthdr = $dbacthdr->first();
            
            foreach ($request->dataobj as $key => $value) {
                
                //billsum lama
                $billsum_lama = DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('billno','=',$auditno)
                            ->where('rowno','=',$value['rowno'])
                            ->first();
                
                $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode','=',session('compcode'))
                            ->where('uom','=',$value['uom'])
                            ->where('chgcode','=',$value['chggroup']);

                ///2. update detail
                if($billsum_lama->chggroup != $value['chggroup'] || $billsum_lama->uom != $value['uom']){

                    $edit_lain_chggroup = true;
                
                    $product_lama = DB::table('hisdb.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$billsum_lama->uom)
                            ->where('itemcode','=',$billsum_lama->chggroup);

                    if($product_lama->exists()){
                        $this->delivdspdt($billsum_lama,$dbacthdr);
                    }

                    $billsum_lama = DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('billno','=',$auditno)
                            ->where('rowno','=',$value['rowno'])
                            ->first();

                    $this->sysdb_log('update',$billsum_lama,'sysdb.billsumlog');

                    DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('billno','=',$auditno)
                            ->where('rowno','=',$value['rowno'])
                            ->update([
                                'chggroup' => $value['chggroup'],
                                'description' => $chgmast->first()->description,
                                'uom' => $value['uom'],
                                'uom_recv' => $value['uom_recv'],
                                'taxcode' => $value['taxcode'],
                                'unitprice' => $value['unitprice'],
                                'quantity' => $value['quantity'],
                                'qtyonhand' => $value['qtyonhand'],
                                'amount' => $value['amount'],
                                'outamt' => $value['amount'],
                                'discamt' => floatval($value['discamt']),
                                'taxamt' => floatval($value['taxamt']),
                                'totamount' => floatval($value['totamount']),
                                'lastuser' => session('username'), 
                                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);


                }else{

                    $edit_lain_chggroup = false;

                    //pindah yang lama ke billsumlog sebelum update
                    //recstatus->update

                    $billsum_lama = DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('billno','=',$auditno)
                            ->where('rowno','=',$value['rowno'])
                            ->first();

                    $this->sysdb_log('update',$billsum_lama,'sysdb.billsumlog');

                    DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('billno','=',$auditno)
                            ->where('rowno','=',$value['rowno'])
                            ->update([
                                'unitprice' => $value['unitprice'],
                                'quantity' => $value['quantity'],
                                'qtyonhand' => $value['qtyonhand'],
                                'amount' => $value['amount'],
                                'outamt' => $value['amount'],
                                'discamt' => floatval($value['discamt']),
                                'taxamt' => floatval($value['taxamt']),
                                'totamount' => floatval($value['totamount']),
                                'lastuser' => session('username'), 
                                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                // 'billtypeperct' => $value['billtypeperct'],
                                // 'billtypeamt' => $value['billtypeamt'],
                            ]);
                }
                
                $billsum_obj = DB::table('debtor.billsum')
                                ->where('compcode','=',session('compcode'))
                                ->where('source','=',$source)
                                ->where('trantype','=',$trantype)
                                ->where('billno','=',$auditno)
                                ->where('rowno','=',$value['rowno'])
                                ->first();

                $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$value['uom'])
                        ->where('itemcode','=',$value['chggroup']);
                
                if($product->exists()){
                    $stockloc = DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$value['uom'])
                            ->where('itemcode','=',$value['chggroup'])
                            ->where('deptcode','=',$dbacthdr->deptcode)
                            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                    
                    if($stockloc->exists()){
                        $stockloc = $stockloc->first();
                    }else{
                        throw new \Exception("Stockloc not exists for item: ".$value['chggroup']." dept: ".$dbacthdr->deptcode." uom: ".$value['uom'],500);
                    }
                    
                    $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$billsum_obj->auditno);

                    if($edit_lain_chggroup){
                        $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$dbacthdr);
                        $this->crtgltran($ivdspdt_idno,$dbacthdr);
                    }else{
                        if($ivdspdt->exists()){
                            $this->updivdspdt($billsum_obj,$dbacthdr);
                            $this->updgltran($ivdspdt->first()->idno,$dbacthdr);
                        }else{
                            $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$dbacthdr);
                            $this->crtgltran($ivdspdt_idno,$dbacthdr);
                        }
                    }
                }
                
                ///3. calculate total amount from detail
                $totalAmount = DB::table('debtor.billsum')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('billno','=',$auditno)
                        ->where('recstatus','!=','DELETE')
                        ->sum('totamount');
                
                ///4. then update to header
                DB::table('debtor.dbacthdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('auditno','=',$auditno)
                        ->update([
                            'amount' => $totalAmount,
                        ]);
                
            }
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
            
        }
        
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = $request->auditno;
            $idno = $request->idno;

            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $dbacthdr = $dbacthdr->first();

            $billsum = DB::table('debtor.billsum')
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$idno);

            $billsum_obj = $billsum->first();

            $chgmast_lama = DB::table('hisdb.chgmast')
                    ->where('compcode','=',session('compcode'))
                    ->where('uom','=',$billsum_obj->uom)
                    ->where('chgcode','=',$billsum_obj->chggroup)
                    ->first();

            if($chgmast_lama->invflag != '0'){
                $this->delivdspdt($billsum_obj,$dbacthdr);
            }else{
                $this->delgltran($billsum_obj,$dbacthdr);
            }

            //pindah yang lama ke billsumlog sebelum update
            //recstatus->delete

            $billsum_lama = DB::table('debtor.billsum')
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$idno)
                            ->first();

            $this->sysdb_log('delete',$billsum_lama,'sysdb.billsumlog');

            DB::table('debtor.billsum')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$idno)
                    ->delete();

            ///3. calculate total amount from detail
            $totalAmount = DB::table('debtor.billsum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('billno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

            ///4. then update to header
            DB::table('debtor.dbacthdr')
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

            return response($e, 500);
        }
        
    }

    public function crtivdspdt($billsum_obj,$dbacthdr){

        $my_uom = $billsum_obj->uom;
        $my_chggroup = $billsum_obj->chggroup;

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
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        $convuom_recv = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom_recv)
            ->first();
        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        $curr_netprice = $product->first()->avgcost;
        $curr_quan = $billsum_obj->quantity * ($convuom_recv / $conv_uom);
        if($stockloc->exists()){
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
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
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
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
                throw new \Exception("No stockexp");
            }


        }

        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->auditno,//OE IN
            'lineno_' => 1,
            'itemcode' => $billsum_obj->chggroup,
            'uomcode' => $billsum_obj->uom,
            'txnqty' => $curr_quan,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'productcat' => $product->first()->productcat,
            'issdept' => $dbacthdr->deptcode,
            'reqdept' => $dbacthdr->deptcode,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'trantype' => 'DS',
            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
            'trxaudno' => $billsum_obj->auditno,
            'mrn' => $this->givenullifempty($dbacthdr->mrn),
            'episno' => $this->givenullifempty($dbacthdr->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;
    }

    public function updivdspdt($billsum_obj,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        $convuom_recv = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom_recv)
            ->first();
        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
            $curr_quan = $billsum_obj->quantity * ($convuom_recv / $conv_uom);
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
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
                                ->where('uomcode','=',$billsum_obj->uom)
                                ->where('itemcode','=',$billsum_obj->chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
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
                throw new \Exception("No stockloc");
            }

        }

        $ivdspdt_arr = [
            'txnqty' => $curr_quan,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        // pindah ke ivdspdtlog
        // recstatus->update
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$billsum_obj->auditno)
                        ->first();

        $this->sysdb_log('update',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno)
            ->update($ivdspdt_arr);
    }

    public function delivdspdt($billsum_obj,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
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
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
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
                throw new \Exception("No stockexp");
            }

        }

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$billsum_obj->auditno);

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
                ->where('auditno','=',$billsum_obj->auditno)
                ->delete();
        }

        // pindah ke ivdspdtlog
        // recstatus->delete
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$billsum_obj->auditno)
                        ->first();

        $this->sysdb_log('delete',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno)
            ->delete();
    }

    public function crtgltran($ivdspdt_idno,$dbacthdr){
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
            ->where('deptcode','=',$dbacthdr->deptcode)
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

    public function updgltran($ivdspdt_idno,$dbacthdr){
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

    public function delgltran($billsum_obj,$dbacthdr){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$billsum_obj->auditno);

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
                ->where('auditno','=',$billsum_obj->auditno)
                ->delete();
        }
    }

    public function crtgltran_notinv($billsum_obj,$dbacthdr){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $billsum_obj->chggroup)
            ->where('uomcode','=', $billsum_obj->uom)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$dbacthdr->deptcode)
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
                'auditno' => $billsum_obj->auditno,
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $billsum_obj->uom,
                'description' => $billsum_obj->chggroup, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $billsum_obj->amount
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
                    'actamount'.$yearperiod->period => $billsum_obj->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $billsum_obj->amount,
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
                    'actamount'.$yearperiod->period => $gltranAmount - $billsum_obj->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$billsum_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function updgltran_notinv($billsum_obj,$dbacthdr){

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$billsum_obj->auditno);

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
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $billsum_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $billsum_obj->amount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $billsum_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$billsum_obj->amount,
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

