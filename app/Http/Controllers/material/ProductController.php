<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ProductController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        if(!in_array(strtoupper($request->groupcode), ['ASSET','OTHERS'])){
            $unit_used = session('unit');
        }else{
            $unit_used = 'all';
        }

        return view('material.product.product',compact('unit_used'));
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                if($request->action == 'save_productmaster'){
                    $exists = DB::table('material.productmaster')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$request->itemcode)
                            ->exists();

                    if($exists){
                        return response('Error itemcode for productmaster already exists', 500);
                    }

                    return $this->save_productmaster($request);
                }else{
                    $exists = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$request->itemcode)
                            ->where('uomcode','=',$request->uomcode)
                            ->where('unit','=',session('unit'))
                            ->exists();

                    if($exists){
                        return response('Error itemcode and uom already exists', 500);
                    }
                    return $this->add($request);
                }
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_product':
                return $this->get_table_product($request);
            case 'get_charges_from_product':
                return $this->get_charges_from_product($request);
            case 'get_product_detail':
                return $this->get_product_detail($request);
            case 'print_barcode':
                return $this->print_barcode($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_product(Request $request){
        $groupcode = $request->filterVal[0];
        $Class = $request->filterVal[1];

        $table = DB::table('material.product as p')
                    ->select( 'p.unit as unit',
                             'p.itemcode as itemcode',
                             'p.description as description',
                             'p.uomcode as uomcode',
                             'p.qtyonhand as qtyonhand',
                             'p.groupcode as groupcode',
                             'p.Class as Class',
                             'p.productcat as productcat',
                             'p.suppcode as suppcode',
                             'p.avgcost as avgcost',
                             'p.actavgcost as actavgcost',
                             'p.currprice as currprice',
                             'p.bonqty as bonqty',
                             'p.rpkitem as rpkitem',
                             'p.minqty as minqty',
                             'p.maxqty as maxqty',
                             'p.reordlevel as reordlevel',
                             'p.reordqty as reordqty',
                             'p.recstatus as recstatus',
                             'p.chgflag as chgflag',
                             'p.subcatcode as subcatcode',
                             'p.expdtflg as expdtflg',
                             'p.mstore as mstore',
                             'p.costmargin as costmargin',
                             'p.pouom as pouom',
                             'p.reuse as reuse',
                             'p.TaxCode as TaxCode',
                             'p.trqty as trqty',
                             'p.deactivedate as deactivedate',
                             'p.tagging as tagging',
                             'p.itemtype as itemtype',
                             'p.generic as generic',
                             'p.idno as idno',
                             'p.computerid as computerid',
                             'p.lastcomputerid as lastcomputerid',
                             'cm.chgclass as cm_chgclass',
                             'cm.chggroup as cm_chggroup',
                             'cm.chgtype as cm_chgtype',
                             'cm.packqty as cm_packqty',
                             'cm.druggrcode as cm_druggrcode',
                             'cm.subgroup as cm_subgroup',
                             'cm.stockcode as cm_stockcode',
                             'cm.invgroup as cm_invgroup',
                             'cm.dosecode as cm_dosecode',
                             'cm.freqcode as cm_freqcode',
                             'cm.instruction as cm_instruction')
                    ->where('p.compcode','=',session('compcode'))
                    ->where('p.recstatus','=','ACTIVE')
                    ->where('p.Class','=',$Class)
                    ->where('p.groupcode','=',$groupcode);

        if(!in_array(strtoupper($groupcode), ['ASSET','OTHERS'])){
            $table = $table->where('p.unit','=',session('unit'));
        }

        $table = $table->leftjoin('hisdb.chgmast as cm', function($join) use ($groupcode){
                            $join = $join->where('cm.compcode', '=', 'p.compcode');
                            $join = $join->on('cm.chgcode', '=', 'p.itemcode');
                            $join = $join->on('cm.uom', '=', 'p.uomcode');
                            if(!in_array(strtoupper($groupcode), ['ASSET','OTHERS'])){
                                $join = $join->where('cm.unit','=',session('unit'));
                            }
                        });

        // foreach ($table->get() as $key => $value) {
        //     $chgmast = DB::table('hisdb.chgmast')
        //                     ->where('compcode',session('compcode'))
        //                     ->where('chgcode',$value->itemcode);


        //     if($chgmast->exists()){
        //         $table->get()->put('cm_chgclass', 'class');
        //     }
        // }

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false && trim($request->searchVal[$key]) != '%%'){//trim whitespace
                            $search_ = $this->begins_search_if(['itemcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            //begins search only
                            $table->orwhere('p.'.$searchCol_array[$key],'like',$search_);
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
                    $table->orwhere('p.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        $table->orwhere('p.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->sidx)){
            
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
            $table = $table->orderBy('p.idno','desc');
        }


        //////////paginate/////////
        // $mypaginate = $this->mypaginate($table,$request->rows);
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function get_charges_from_product(Request $request){

        $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('chgcode',$request->chgcode)
                        ->where('uom',$request->uomcode)
                        ->first();

        $responce = new stdClass();
        $responce->chgmast = $chgmast;
        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function get_product_detail(Request $request){

        $responce = new stdClass();

        $product = DB::table('material.product')
                        ->where('compcode',session('compcode'))
                        ->where('unit',session('unit'))
                        ->where('recstatus','ACTIVE')
                        ->where('groupcode',$request->groupcode)
                        ->where('Class',$request->Class)
                        ->where('itemcode',$request->itemcode)
                        ->where('uomcode',$request->uomcode);

        if($product->exists()){
            $responce->error = true;
            $responce->msg = 'Product Already Exists';
            return json_encode($responce);
        }else{

            $productmaster = DB::table('material.productmaster')
                        ->where('compcode',session('compcode'))
                        // ->where('unit',session('unit'))
                        ->where('recstatus','ACTIVE')
                        ->where('groupcode',$request->groupcode)
                        ->where('Class',$request->Class)
                        ->where('itemcode',$request->itemcode);

            if($productmaster->exists()){
                $productmaster_first = $productmaster->first();
                $responce->error = false;
                $responce->productmaster = $productmaster_first;

                return json_encode($responce);
            }else{
                $responce->error = true;
                $responce->msg = 'Productmaster Not Exists';
                return json_encode($responce);
            }

        }



    }

    public function save_productmaster(Request $request)
    {   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();

            $table = DB::table('material.productmaster');

            $array_insert = [
                'itemcode' => $request->itemcode,
                'description' => strtoupper($request->description),
                'groupcode' => strtoupper($request->groupcode),
                // 'uomcode' => 'PC',
                'productcat' => $request->productcat,
                'Class' => $request->Class,
                // 'unit' => session('unit'),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'computerid' => session('computerid'),
            ];

            $er = $this->add($request);

            if(!empty($er)){
                return $er;
            }

            try {


                $table->insert($array_insert);

                $responce = new stdClass();
                $responce->sql = $table->toSql();
                $responce->sql_bind = $table->getBindings();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response($e->getMessage(), 500);
            }
        // }
    }

    public function add(Request $request)
    {   
            DB::beginTransaction();

            try {

                if(strtoupper($request->groupcode) == 'STOCK' || strtoupper($request->groupcode) == 'CONSIGNMENT'){
                    $unit_ = session('unit');
                }else{
                    $unit_ = 'ALL';
                }

                $array_insert = [
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'productcat' => $request->productcat,
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->generic),
                    'groupcode' => strtoupper($request->groupcode),
                    'Class' => $request->Class,
                    'unit' => $unit_,
                    'compcode' => session('compcode'),
                    'subcatcode' => strtoupper($request->subcatcode),
                    'pouom' => strtoupper($request->pouom),
                    'suppcode' => strtoupper($request->suppcode),
                    'mstore' => strtoupper($request->mstore),
                    'TaxCode' => strtoupper($request->TaxCode),
                    'minqty' => $request->minqty,
                    'maxqty' => $request->maxqty,
                    'reordlevel' => $request->reordlevel,
                    'reordqty' => $request->reordqty,
                    'reuse' => $request->reuse,
                    'rpkitem' => $request->rpkitem,
                    'tagging' => $request->tagging,
                    'expdtflg' => $request->expdtflg,
                    'chgflag' => $request->chgflag,
                    'itemtype' => $request->itemtype,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                    'computerid' => session('computerid'),
                ];

                DB::table('material.product')->insert($array_insert);


                $stockloc = DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('deptcode','=',session('deptcode'))
                            ->where('itemcode','=',$request->itemcode)
                            ->where('uomcode','=',$request->uomcode)
                            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                            ->where('unit','=',session('unit'));

                if(!$stockloc->exists()){

                    if(strtoupper($request->groupcode) == 'CONSIGNMENT'){
                        $stocktxntype = 'IS';
                        $disptype = 'DS1';
                    }else{
                        $stocktxntype = 'TR';
                        $disptype = 'DS';
                    }

                    DB::table('material.stockloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'deptcode' => session('deptcode'),
                            'itemcode' => $request->itemcode,
                            'uomcode' => $request->uomcode,
                            'year' => Carbon::now("Asia/Kuala_Lumpur")->format('Y'),
                            'stocktxntype' => $stocktxntype,
                            'disptype' => $disptype,
                            // 'frozen' => $request->frozen,
                            // 'disptype' => $request->disptype,
                            // 'minqty' => $request->minqty,
                            // 'maxqty' => $request->maxqty,
                            // 'reordlevel' => $request->reordlevel,
                            // 'reordqty' => $request->reordqty,
                            'recstatus' => 'ACTIVE',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'computerid' => session('computerid'),
                        ]);
                }


                if($request->chgflag == 1){
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('chgcode','=',$request->itemcode)
                                ->where('uom','=',$request->uomcode);

                    if($chgmast->exists()){
                        $array_update = [
                            'description' => strtoupper($request->description),
                            'chgclass' => $request->cm_chgclass,
                            'chggroup' => $request->cm_chggroup,
                            'chgtype' => $request->cm_chgtype,
                            'packqty' => $request->cm_packqty,
                            'druggrcode' => strtoupper($request->cm_druggrcode),
                            'subgroup' => strtoupper($request->cm_subgroup),
                            'stockcode' => strtoupper($request->cm_stockcode),
                            'invgroup' => strtoupper($request->cm_invgroup),
                            'dosecode' => $request->cm_dosecode,
                            'freqcode' => $request->cm_freqcode,
                            'instruction' => $request->cm_instruction,
                            'invflag' => 1,

                            // 'barcode' => strtoupper($request->cm_barcode),
                            // 'constype' => strtoupper($request->cm_constype),
                            // 'invflag' => $request->cm_invflag,
                            // 'costcode' => $request->cm_costcode, 
                            // 'revcode' => $request->cm_revcode, 
                            // 'seqno' => $request->cm_seqno,

                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastcomputerid' => session('computerid'),
                        ];
                        $chgmast->update($array_update);
                    }else{
                        $array_insert = [
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'chgcode' => $request->itemcode,
                            'uom' => $request->uomcode,
                            'description' => strtoupper($request->description),
                            'brandname' => strtoupper($request->generic),
                            'chgclass' => $request->cm_chgclass,
                            'chggroup' => $request->cm_chggroup,
                            'chgtype' => $request->cm_chgtype,
                            'recstatus' => 'ACTIVE',
                            'packqty' => $request->cm_packqty,
                            'druggrcode' => strtoupper($request->cm_druggrcode),
                            'subgroup' => strtoupper($request->cm_subgroup),
                            'stockcode' => strtoupper($request->cm_stockcode),
                            'invgroup' => strtoupper($request->cm_invgroup),
                            'dosecode' => $request->cm_dosecode,
                            'freqcode' => $request->cm_freqcode,
                            'instruction' => $request->cm_instruction,
                            'invflag' => 1,

                            // 'barcode' => strtoupper($request->cm_barcode),
                            // 'constype' => strtoupper($request->cm_constype),
                            // 'invflag' => $request->cm_invflag,
                            // 'costcode' => $request->cm_costcode, 
                            // 'revcode' => $request->cm_revcode, 
                            // 'seqno' => $request->cm_seqno,

                            'overwrite' => 0, 
                            'doctorstat' => 0, 
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'computerid' => session('computerid'),
                            'lastcomputerid' => session('computerid'),
                        ];

                        DB::table('hisdb.chgmast')->insert($array_insert);
                    }

                }

                
                $responce = new stdClass();
                $queries = DB::getQueryLog();
                $responce->queries = $queries;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response($e->getMessage(), 500);
            }
    }

    public function edit(Request $request){   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();
            try {

                if(strtoupper($request->groupcode) == 'STOCK' || strtoupper($request->groupcode) == 'CONSIGNMENT'){
                    $unit_ = session('unit');
                }else{
                    $unit_ = 'ALL';
                }

                //1. update product 
                $table = DB::table('material.product')->where('idno','=',$request->idno);
                $array_update = [
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->generic),
                    'groupcode' => strtoupper($request->groupcode),
                    'Class' => strtoupper($request->Class),
                    'unit' => $unit_,
                    'compcode' => session('compcode'),
                    'subcatcode' => strtoupper($request->subcatcode),
                    'pouom' => strtoupper($request->pouom),
                    'suppcode' => strtoupper($request->suppcode),
                    'mstore' => strtoupper($request->mstore),
                    'TaxCode' => strtoupper($request->TaxCode),
                    'minqty' => $request->minqty,
                    'maxqty' => $request->maxqty,
                    'reordlevel' => $request->reordlevel,
                    'reordqty' => $request->reordqty,
                    'reuse' => $request->reuse,
                    'rpkitem' => $request->rpkitem,
                    'tagging' => $request->tagging,
                    'expdtflg' => $request->expdtflg,
                    'chgflag' => $request->chgflag,
                    'itemtype' => $request->itemtype,
                    'recstatus' => 'ACTIVE',

                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => session('computerid'),
                ];
                $table->update($array_update);

                if($request->chgflag == 1){

                    //2. update chgmast 
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('chgcode','=',$request->itemcode)
                                ->where('uom','=',$request->uomcode);

                    if($chgmast->exists()){
                        $array_update = [
                            'description' => strtoupper($request->description),
                            'chgclass' => $request->cm_chgclass,
                            'chggroup' => $request->cm_chggroup,
                            'chgtype' => $request->cm_chgtype,
                            'packqty' => $request->cm_packqty,
                            'druggrcode' => strtoupper($request->cm_druggrcode),
                            'subgroup' => strtoupper($request->cm_subgroup),
                            'stockcode' => strtoupper($request->cm_stockcode),
                            'invgroup' => strtoupper($request->cm_invgroup),
                            'dosecode' => $request->cm_dosecode,
                            'freqcode' => $request->cm_freqcode,
                            'instruction' => $request->cm_instruction,
                            'invflag' => 1,

                            // 'barcode' => strtoupper($request->cm_barcode),
                            // 'constype' => strtoupper($request->cm_constype),
                            // 'invflag' => $request->cm_invflag,
                            // 'costcode' => $request->cm_costcode, 
                            // 'revcode' => $request->cm_revcode, 
                            // 'seqno' => $request->cm_seqno,
                            'recstatus' => 'ACTIVE',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastcomputerid' => session('computerid'),
                        ];
                        $chgmast->update($array_update);
                    }else{
                        $array_insert = [
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'chgcode' => $request->itemcode,
                            'uom' => $request->uomcode,
                            'description' => strtoupper($request->description),
                            'brandname' => strtoupper($request->generic),
                            'chgclass' => $request->cm_chgclass,
                            'chggroup' => $request->cm_chggroup,
                            'chgtype' => $request->cm_chgtype,
                            'recstatus' => 'ACTIVE',
                            'packqty' => $request->cm_packqty,
                            'druggrcode' => strtoupper($request->cm_druggrcode),
                            'subgroup' => strtoupper($request->cm_subgroup),
                            'stockcode' => strtoupper($request->cm_stockcode),
                            'invgroup' => strtoupper($request->cm_invgroup),
                            'dosecode' => $request->cm_dosecode,
                            'freqcode' => $request->cm_freqcode,
                            'instruction' => $request->cm_instruction,
                            'invflag' => 1,

                            // 'barcode' => strtoupper($request->cm_barcode),
                            // 'constype' => strtoupper($request->cm_constype),
                            // 'invflag' => $request->cm_invflag,
                            // 'costcode' => $request->cm_costcode, 
                            // 'revcode' => $request->cm_revcode, 
                            // 'seqno' => $request->cm_seqno,

                            'overwrite' => 0, 
                            'doctorstat' => 0, 
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'computerid' => session('computerid'),
                            'lastcomputerid' => session('computerid'),
                        ];

                        DB::table('hisdb.chgmast')->insert($array_insert);
                    }

                }

                // $responce = new stdClass();
                // $responce->sql = $table->toSql();
                // $responce->sql_bind = $table->getBindings();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response($e->getMessage(), 500);
            }
        // }
    }

    public function del(Request $request){   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();
            try {

                //1. update product 
                $table = DB::table('material.product')->where('idno','=',$request->idno);
                $array_update = [
                    'recstatus' => 'DEACTIVE',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => session('computerid'),
                ];
                $table->update($array_update);

                if($request->chgflag == 1){

                    //2. update chgmast 
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('chgcode','=',$request->itemcode)
                                ->where('uom','=',$request->uomcode);

                    if($chgmast->exists()){
                        $array_update = [
                            'deluser' => session('username'),
                            'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'DEACTIVE',
                            'computerid' => session('computerid')
                        ];
                        $chgmast->update($array_update);
                    }
                }

                $responce = new stdClass();
                $responce->sql = $table->toSql();
                $responce->sql_bind = $table->getBindings();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response($e->getMessage(), 500);
            }
        // }
    }

    public function print_barcode(Request $request){
        $pages = $request->pages;
        $itemcodefrom = $request->itemcodefrom;
        if(empty($itemcodefrom)){
            $itemcodefrom = '%';
        }
        $itemcodeto = $request->itemcodeto;
        $groupcode = $request->groupcode;
        $Class = $request->Class;

        $product = DB::table('material.product as p')
                    ->select('itemcode','description')
                    ->where('p.compcode',session('compcode'))
                    ->where('p.recstatus','=','ACTIVE')
                    ->where('p.Class','=',$Class)
                    ->where('p.groupcode','=',$groupcode)
                    ->whereBetween('p.itemcode',[$itemcodefrom,$itemcodeto.'%']);

        if(!in_array(strtoupper($groupcode), ['ASSET','OTHERS'])){
            $product = $product->where('p.unit','=',session('unit'));
        }

        // dd($this->getQueries($product));

        $product = $product->get();

        $product = $product->unique('itemcode');

        foreach ($product as $key => $value) {
            $value->itemcode = str_replace(' ', '_', $value->itemcode);
        }

        // dd($product);

        return view('material.product.print_barcode',compact('product','pages'));
    }
}