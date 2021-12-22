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
        return view('material.product.product');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                if($request->action == 'save_productmaster'){
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
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_product':
                return $this->get_table_product($request);
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
                             'p.ipaddress as ipaddress',
                             'p.lastcomputerid as lastcomputerid',
                             'p.lastipaddress as lastipaddress',
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
                    ->where('p.unit','=',session('unit'));

        $table = $table->leftjoin('hisdb.chgmast as cm', function($join){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->where('cm.unit','=',session('unit'));
                            $join = $join->on('cm.chgcode', '=', 'p.itemcode');
                            $join = $join->on('cm.uom', '=', 'p.uomcode');
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
                            $table->orwhere('p.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
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

    public function save_productmaster(Request $request)
    {   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();

            $table = DB::table('material.product');

            $array_insert = [
                'itemcode' => $request->itemcode,
                'description' => strtoupper($request->description),
                'groupcode' => strtoupper($request->groupcode),
                'uomcode' => 'PC',
                'productcat' => $request->productcat,
                'Class' => $request->Class,
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'computerid' => $request->computerid,
                'ipaddress' => $request->ipaddress,
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

                $array_insert = [
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'productcat' => $request->productcat,
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->generic),
                    'groupcode' => strtoupper($request->groupcode),
                    'Class' => $request->Class,
                    'unit' => session('unit'),
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
                    'computerid' => $request->computerid,
                    'ipaddress' => $request->ipaddress,
                ];

                DB::table('material.product')->insert($array_insert);

                dd(session('unit'));
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
                    'computerid' => $request->cm_computerid, 
                    'ipaddress' => $request->cm_ipaddress, 
                    'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                    'lastipaddress' => strtoupper($request->cm_lastipaddress),
                ];

                DB::table('hisdb.chgmast')->insert($array_insert);

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

    public function edit(Request $request)
    {   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();
            try {

                //1. update product 
                $table = DB::table('material.product')->where('idno','=',$request->idno);
                $array_update = [
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->generic),
                    'groupcode' => strtoupper($request->groupcode),
                    'Class' => $request->Class,
                    'unit' => session('unit'),
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

                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => $request->computerid,
                    'ipaddress' => $request->ipaddress,
                ];
                $table->update($array_update);

                //2. update chgmast 
                $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode','=',session('compcode'))
                            ->where('unit','=',session('unit'))
                            ->where('chgcode','=',$request->itemcode)
                            ->where('uom','=',$request->uomcode);

                if($chgmast->exists()){
                    $array_update = [
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
                        'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                        'lastipaddress' => strtoupper($request->cm_lastipaddress),
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
                        'computerid' => $request->cm_computerid, 
                        'ipaddress' => $request->cm_ipaddress, 
                        'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                        'lastipaddress' => strtoupper($request->cm_lastipaddress),
                    ];

                    DB::table('hisdb.chgmast')->insert($array_insert);
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
}