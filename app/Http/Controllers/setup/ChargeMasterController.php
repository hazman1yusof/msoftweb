<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;
use App\Exports\ChargeMasterExport;
use App\Exports\PackageDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class ChargeMasterController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "chgcode";
    }
    
    public function show(Request $request)
    {
        return view('setup.chargemaster.chargemaster');
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'add_pkgmast':
                return $this->add_pkgmast($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function showExcel(Request $request){
        return Excel::download(new ChargeMasterExport($request->chggroup_from,$request->chggroup_to,$request->chgcode_from,$request->chgcode_to), 'ChargePriceList.xlsx');
    }

    public function showExcelPkg(Request $request){
        return Excel::download(new PackageDetailExport($request->pkgcodePkg,$request->effectdate), 'PackageDetail.xlsx');
    }

    public function showpdf(Request $request){

        $chggroup_from = $request->chggroup_from;
        if(empty($request->chggroup_from)){
            $chggroup_from = '%';
        } 
        $chggroup_to = $request->chggroup_to;

        $chgcode_from = $request->chgcode_from;
        if(empty($request->chgcode_from)){
            $chgcode_from = '%';
        }
        $chgcode_to = $request->chgcode_to;

        $chgmast = DB::table('hisdb.chgmast as cm')
                ->select('cm.idno', 'cm.compcode', 'cm.unit', 'cm.chgcode', 'cm.description', 'cm.uom as uom_cm', 'cm.packqty', 'cm.recstatus', 'cm.chgtype', 'cm.chggroup', 'cm.chgclass', 'cp.idno as cp_idno','cp.uom as uom_cp','cp.amt1', 'cp.effdate', 'cp.amt2', 'cp.amt3', 'cp.costprice', 'ct.description as ct_desc', 'cg.grpcode', 'cg.description as cg_desc', 'p.uomcode as uom_p')
                ->join('hisdb.chgprice as cp', function($join) {
                    $join = $join->on('cp.chgcode', '=', 'cm.chgcode')
                                ->on('cp.uom', '=', 'cm.uom')
                                ->where('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
                                ->where('cp.compcode', '=', session('compcode'))
                                ->where('cp.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chgtype as ct', function($join) {
                    $join = $join->on('ct.chgtype', '=', 'cm.chgtype')
                                ->where('ct.compcode', '=', session('compcode'))
                                ->where('ct.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chggroup as cg', function($join) {
                    $join = $join->on('cg.grpcode', '=', 'cm.chggroup')
                                ->where('cg.compcode', '=', session('compcode'))
                                ->where('cg.recstatus', '=', 'ACTIVE');
                })
                ->join('material.product AS p', function($join) {
                    $join = $join->on('p.uomcode', '=', 'cm.uom')
                                ->on('p.itemcode', '=', 'cm.chgcode')
                                ->where('p.compcode', '=', session('compcode'));
                })
                ->where('cm.compcode','=',session('compcode'))
                ->where('cm.recstatus', '=', 'ACTIVE')
                ->whereBetween('cm.chggroup', [$chggroup_from, $chggroup_to.'%'])
                ->whereBetween('cm.chgcode', [$chgcode_from, $chgcode_to.'%'])
                ->orderBy('cp.idno','DESC')
                ->get();
        
            //dd($chgmast);
        $array_report = [];

        $chgcode_ = null;
        foreach ($chgmast as $key => $value){
            if($chgcode_ == $value->chgcode){
                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                        ->select('cp.chgcode','cp.idno as cp_idno','cp.uom as uom_cp','cp.amt1', 'cp.effdate', 'cp.amt2', 'cp.amt3', 'cp.costprice')
                        ->where('cp.compcode', '=', session('compcode'))
                        ->where('cp.chgcode', '=', $value->chgcode)
                        ->where('cp.uom', '=', $value->uom_cm)
                        ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
                        ->orderBy('cp.effdate','desc');

                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->cp_idno != $chgprice_obj->cp_idno){
                    // unset($chgmast[$key]);
                    continue;
                }
            }
            $chgcode_=$value->chgcode;
            array_push($array_report, $value);

        }

        $chggroup = collect($array_report)->unique('chggroup');
        $chgtype = collect($array_report)->unique('chgtype');
        
        //dd($chggroup);

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->chggroup_from = $request->chggroup_from;
        $header->chggroup_to = $request->chggroup_to;
        $header->chgcode_from = $request->chgcode_from;
        $header->chgcode_to = $request->chgcode_to;
        $header->compname = $company->name;

        return view('setup.chargemaster.chargemaster_pdfmake',compact('header', 'chggroup', 'chgtype', 'array_report'));
        
    }

    public function showpdfPkg(Request $request){

        $pkgcodePkg = $request->pkgcodePkg;
        $effectdate = Carbon::createFromFormat('d/m/Y', $request->effectdate)->format('Y-m-d');

        //dd($effectdate);
        $chgmast = DB::table('hisdb.chgmast')
                    ->select('compcode', 'chgcode', 'description')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode', '=', $pkgcodePkg)
                    ->where('recstatus', '=', 'ACTIVE')
                    ->first();
       //dd($chgmast);
        $pkgdet = DB::table('hisdb.pkgdet as pd')
                ->select('pd.idno', 'pd.compcode', 'pd.pkgcode', 'pd.recstatus', 'pd.effectdate', 'pd.quantity', 'pd.chgcode', 'cp.effdate', 'cm.description as cc_desc')
                ->join('hisdb.chgprice as cp', function($join) {
                    $join = $join->on('cp.chgcode', '=', 'pd.pkgcode')
                                ->on('cp.effdate', '=', 'pd.effectdate')
                                ->where('cp.compcode', '=', session('compcode'))
                                ->where('cp.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chgmast as cm', function($join) {
                    $join = $join->on('cm.chgcode', '=', 'pd.chgcode')
                                ->where('cm.compcode', '=', session('compcode'))
                                ->where('cm.recstatus', '=', 'ACTIVE');
                })
                ->where('pd.compcode','=',session('compcode'))
                ->where('pd.pkgcode', '=', $pkgcodePkg)
                ->where('pd.effectdate', '=', $effectdate)
                ->where('pd.recstatus', '=', 'ACTIVE')
                ->orderBy('pd.idno','DESC')
                ->get();
        
        //dd($pkgdet);

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->pkgcodePkg = $request->pkgcodePkg;
        $header->compname = $company->name;

        return view('setup.chargemaster.pkgDetail_pdfmake',compact('header', 'pkgdet', 'chgmast'));
        
    }

    public function maintable(Request $request){

        $table = DB::table('hisdb.chgmast AS cm')
                    ->select( 'cm.idno','cm.compcode','cm.unit','cm.chgcode','cm.description','cm.brandname','cm.revcode','cm.uom','cm.packqty','cm.invflag','cm.overwrite','cm.buom','cm.adduser','cm.adddate','cm.lastuser','cm.lastupdate','cm.upduser','cm.upddate','cm.deluser','cm.deldate','cm.recstatus','cm.lastfield','cm.doctorstat','cm.chgtype','cm.chggroup','cm.qflag','cm.costcode','cm.chgflag','cm.ipacccode','cm.opacccode','cm.revdept','cm.chgclass','cm.costdept','cm.invgroup','cm.apprccode','cm.appracct','cm.active','cm.constype','cm.dosage','cm.druggrcode','cm.subgroup','cm.stockcode','cm.seqno','cm.instruction','cm.freqcode','cm.durationcode','cm.strength','cm.durqty','cm.freqqty','cm.doseqty','cm.dosecode','cm.barcode','cm.computerid','cm.ipaddress','cm.lastcomputerid','cm.lastipaddress','cc.description as cc_description','cg.description as cg_description','ct.description as ct_description','p.uomcode as uom_product')
                    ->where('cm.compcode','=',session('compcode'))
                    ->where('cm.unit','=',session('unit'));

        $table = $table->leftjoin('hisdb.chgclass AS cc', function($join){
                            $join = $join->where('cc.compcode', '=', session('compcode'));
                            $join = $join->on('cc.classcode', '=', 'cm.chgclass');
                        });

        $table = $table->leftjoin('hisdb.chggroup AS cg', function($join){
                            $join = $join->where('cg.compcode', '=', session('compcode'));
                            $join = $join->on('cg.grpcode', '=', 'cm.chggroup');
                        });

        $table = $table->leftjoin('hisdb.chgtype AS ct', function($join){
                            $join = $join->where('ct.compcode', '=', session('compcode'));
                            $join = $join->on('ct.chgtype', '=', 'cm.chgtype');
                        });

        $table = $table->leftjoin('material.product AS p', function($join){
                            $join = $join->where('p.compcode', '=', session('compcode'));
                            $join = $join->on('p.uomcode', '=', 'cm.uom');
                            $join = $join->on('p.itemcode', '=', 'cm.chgcode');
                            $join = $join->where('p.unit', '=', session('unit'));
                        });

        // foreach ($table->get() as $key => $value) {
        //     $chgmast = DB::table('hisdb.chgmast')
        //                     ->where('compcode',session('compcode'))
        //                     ->where('chgcode',$value->itemcode);


        //     if($chgmast->exists()){
        //         $table->get()->put('chgclass', 'class');
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
                            $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            //begins search only
                            $table->orwhere('cm.'.$searchCol_array[$key],'like',$search_);
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
            $table = $table->orderBy('cm.idno','desc');
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
    
    public function add(Request $request){
        
        DB::beginTransaction();
        DB::enableQueryLog();
        
        try {
            
            $duplicate = DB::table('hisdb.chgmast')->where('chgcode','=',$request->chgcode);
            
            if($duplicate->exists()){
                throw new \Exception('chgcode already exist', 500);
            }
            
            if($request->chgtype == 'PKG' || $request->chgtype == 'pkg'){
                
                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->chgcode))
                    ->update([
                        'pkgstatus' => 1,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
                
            }else{
                
                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->chgcode))
                    ->update([
                        'pkgstatus' => 0,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
                
            }
            
            DB::table('hisdb.chgmast')
                ->insert([
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'chgcode' => strtoupper($request->chgcode),
                    'description' => strtoupper($request->description),
                    'barcode' => strtoupper($request->barcode),
                    'brandname' => strtoupper($request->brandname),
                    'chgclass' => $request->chgclass,
                    'constype' => strtoupper($request->constype),
                    'chggroup' => $request->chggroup,
                    'chgtype' => $request->chgtype,
                    'recstatus' => 'ACTIVE',
                    'uom' => $request->uom,
                    'invflag' => $request->invflag,
                    'packqty' => $request->packqty,
                    'druggrcode' => strtoupper($request->druggrcode),
                    'subgroup' => strtoupper($request->subgroup),
                    'stockcode' => strtoupper($request->stockcode),
                    'invgroup' => strtoupper($request->invgroup),
                    'costcode' => $request->costcode, 
                    'revcode' => $request->revcode, 
                    'seqno' => $request->seqno,
                    'overwrite' => $request->overwrite, 
                    'doctorstat' => $request->doctorstat, 
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                    'lastcomputerid' => session('computerid'),
                ]);
            
            $queries = DB::getQueryLog();
            
            $responce = new stdClass();
            $responce->queries = $queries;
            $responce->computerid = session('computerid');
            echo json_encode($responce);
            
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
        
        }
    
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        DB::enableQueryLog();
        
        try {
            
            if($request->chgtype == 'PKG' || $request->chgtype == 'pkg'){
                
                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->chgcode))
                    ->update([
                        'pkgstatus' => 1,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
                
            }else{
                
                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->chgcode))
                    ->update([
                        'pkgstatus' => 0,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
                
            }
            
            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->idno)
                ->update([
                    'chgcode' => strtoupper($request->chgcode),
                    'description' => strtoupper($request->description),
                    'barcode' => strtoupper($request->barcode),
                    'brandname' => strtoupper($request->brandname),
                    'chgclass' => $request->chgclass,
                    'constype' => strtoupper($request->constype),
                    'chggroup' => $request->chggroup,
                    'chgtype' => $request->chgtype,
                    'recstatus' => 'ACTIVE',
                    'uom' => strtoupper($request->uom),
                    'invflag' => $request->invflag,
                    'packqty' => $request->packqty,
                    'druggrcode' => strtoupper($request->druggrcode),
                    'subgroup' => strtoupper($request->subgroup),
                    'stockcode' => strtoupper($request->stockcode),
                    'invgroup' => strtoupper($request->invgroup),
                    'costcode' => $request->costcode, 
                    'revcode' => $request->revcode, 
                    'seqno' => $request->seqno,
                    'overwrite' => $request->overwrite, 
                    'doctorstat' => $request->doctorstat,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => session('computerid'),
                ]);
            
            $queries = DB::getQueryLog();
            
            $responce = new stdClass();
            $responce->queries = $queries;
            $responce->lastcomputerid = session('computerid');
            echo json_encode($responce);
            
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
        
        }
    
    }
    
    public function del(Request $request){
        
        DB::beginTransaction();
        DB::enableQueryLog();
        
        try {
            
            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->idno)
                ->update([
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DEACTIVE',
                    'computerid' => session('computerid')
                ]);
            
            $queries = DB::getQueryLog();
            
            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);
            
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
        
        }
        
    }
    
    public function chgpricelatest(Request $request){
        // $table = DB::table('hisdb.chgmast as cm')
        //             ->join('hisdb.chgprice as cp', function($join) {
        //                 $join = $join->on('cp.chgcode', '=', 'cm.chgcode')
        //                             ->on('cp.uom', '=', 'cm.uom')
        //                             ->where('cp.compcode', '=', session('compcode'))
        //                             ->where('cp.effdate', '<=', Carbon::now());
        //             });

        // if(!empty($request->searchCol)){
        //     $searchCol_array = $request->searchCol;

        //     $count = array_count_values($searchCol_array);
        //     // dump($count);

        //     foreach ($count as $key => $value) {
        //         $occur_ar = $this->index_of_occurance($key,$searchCol_array);

        //         $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
        //             foreach ($searchCol_array as $key => $value) {
        //                 $found = array_search($key,$occur_ar);
        //                 if($found !== false){
        //                     $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
        //                 }
        //             }
        //         });
        //     }
        // }

        // if(!empty($request->searchCol2)){

        //     $table = $table->where(function($query) use ($request){
        //         $searchCol_array = $request->searchCol2;

        //         foreach ($searchCol_array as $key => $value) {
        //             $query = $query->orWhere($searchCol_array[$key],'like',$request->searchVal2[$key]);
        //         }
        //     });
        // }

        // $table = $table
        //         ->where('cm.recstatus','=','ACTIVE')
        //         ->where('cm.compcode','=',session('compcode'));

        // $paginate = $table->paginate($request->rows);

        // $rows = $paginate->items();

        // foreach ($rows as $key => $value) {
        //     // $chgprice = DB::table('hisdb.chgprice')
        //     //             ->where('compcode','=',session('compcode'))
        //     //             ->where('chgcode','=',$value->chgcode)
        //     //             ->where('uom','=',$value->uom)
        //     //             ->whereDate('effdate', '<=', Carbon::now())
        //     //             ->orderBy('effdate', 'DESC');

        //     // if($chgprice->exists()){
        //     //     $chgprice_get = $chgprice->first();
        //     //     $value->chgprice_amt1 = $chgprice_get->amt1;
        //     //     $value->chgprice_amt2 = $chgprice_get->amt2;
        //     //     $value->chgprice_amt3 = $chgprice_get->amt3;
        //     //     // $value->chgprice_iptax = $chgprice_get->iptax;
        //     //     // $value->chgprice_optax = $chgprice_get->optax;
        //     // }else{
        //     //     $value->chgprice_amt1 = "";
        //     //     $value->chgprice_amt2 = "";
        //     //     $value->chgprice_amt3 = "";
        //     //     // $value->chgprice_iptax = "";
        //     //     // $value->chgprice_optax = "";
        //     // }
        //     $chgprice_obj = DB::table('hisdb.chgprice as cp')
        //         ->where('cp.compcode', '=', session('compcode'))
        //         ->where('cp.chgcode', '=', $value->chgcode)
        //         ->where('cp.uom', '=', $value->uom)
        //         ->whereDate('cp.effdate', '<=', Carbon::now())
        //         ->orderBy('cp.effdate','desc');

        //     if($chgprice_obj->exists()){
        //         $chgprice_obj = $chgprice_obj->first();

        //         if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
        //             unset($rows[$key]);
        //             continue;
        //         }
        //     }
        // }
        
        // $rows = array_values($rows);

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode','st.deptcode','cp.idno','cp.amt1')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE');

        $table = $table->join('hisdb.chgprice as cp', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            $join = $join->where('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                        });

        $table = $table->leftjoin('material.stockloc as st', function($join){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join){
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                        $join = $join->on('pt.uomcode', '=', 'cm.uom');
                        $join = $join->where('pt.unit', '=', session('unit'));
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

        // if(!empty($request->filterCol)){
        //     foreach ($request->filterCol as $key => $value) {
        //         $table = $table->where('cm.'.$request->filterCol[$key],'=',$request->filterVal[$key]);
        //     }
        // }

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
            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uom)
                ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
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

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);
        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function add_pkgmast(Request $request){
        try {
            DB::beginTransaction();


            // $chgmast = DB::table("hisdb.chgmast")
            //                 ->where('cm.idno', '=', $request->idno);

            // $chgprice = DB::table('hisdb.chgprice as cp')
            //                 ->select('cp.autopull','cp.addchg','cm.chgcode','cm.description','cp.effdate','cp.amt1')
            //                 ->join('hisdb.chgmast as cm', function($join) use ($request){
            //                         $join = $join->where('cm.compcode', '=', session('compcode'));
            //                         $join = $join->where('cm.idno', '=', $request->idno);
            //                         $join = $join->on('cm.chgcode', '=', 'cp.chgcode');
            //                         $join = $join->on('cm.uom', '=', 'cp.uom');
            //                     })
            //                 ->where('cp.compcode', '=', session('compcode'))
            //                 ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
            //                 ->orderBy('cp.effdate','desc');

            $chgprice = DB::table('hisdb.chgprice as cp')
                            ->select('cp.autopull','cp.addchg','cm.chgcode','cm.description','cp.effdate','cp.amt1')
                            ->join('hisdb.chgmast as cm', function($join) use ($request){
                                    $join = $join->where('cm.compcode', '=', session('compcode'));
                                    $join = $join->on('cm.chgcode', '=', 'cp.chgcode');
                                    $join = $join->on('cm.uom', '=', 'cp.uom');
                                })
                            ->where('cp.compcode', '=', session('compcode'))
                            ->where('cp.idno',$request->idno);


            if(!$chgprice->exists()){
                throw new \Exception('chgmast not exist', 500);
            }
            $chgprice = $chgprice->first();

            $pkgmast = DB::table('hisdb.pkgmast')
                            ->where('compcode', '=', session('compcode'))
                            ->where('pkgcode', $chgprice->chgcode)
                            ->where('effectDate', $chgprice->effdate);

            if($pkgmast->exists()){

                $array_update = [
                    'autopull' => $chgprice->autopull,
                    'addchg' => $chgprice->addchg,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                ];

                DB::table('hisdb.pkgmast')
                            ->where('pkgcode', $chgprice->chgcode)
                            ->where('effectDate', $chgprice->effdate)
                            ->update($array_update);

                $idno = $pkgmast->first()->idno;

            }else{
                $array_insert = [
                    'pkgcode' => strtoupper($chgprice->chgcode),
                    'description' => strtoupper($chgprice->description),
                    'effectDate' => $chgprice->effdate,
                    'price' => $chgprice->amt1,
                    'autopull' => $chgprice->autopull,
                    'addchg' => $chgprice->addchg,
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                ];

                $idno = DB::table('hisdb.pkgmast')
                            ->insertGetId($array_insert);
            }

            $responce = new stdClass();
            $responce->idno = $idno;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

}