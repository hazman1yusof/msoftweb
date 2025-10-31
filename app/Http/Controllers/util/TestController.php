<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mail;

use App\Jobs\SendEmailPV;
use App\Mail\sendmaildefault;

class TestController extends defaultController
{   

    public function __construct(){

    }

    public function show(Request $request){
        $datefrom = $request->datefrom;
        if($datefrom == null){
            $datefrom = '2025-05-01';
        }

        $dateto = $request->dateto;
        if($dateto == null){
            $dateto = '2025-05-31';
        }

        $apacthdr = DB::table('finance.apacthdr as ap')
                        ->select('ap.source','ap.trantype','ap.auditno','ap.postdate','ap.source','ap.amount','ap.outamount')
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.source','AP')
                        ->where('ap.trantype','IN')
                        ->where('ap.recstatus','POSTED')
                        ->whereDate('ap.postdate','>=',$datefrom)
                        ->whereDate('ap.postdate','<=',$dateto)
                        ->get();

        $array = [];
        foreach ($apacthdr as $obj) {
            $osamt = $obj->amount;

            $apalloc = DB::table('finance.apalloc')
                        ->where('compcode',session('compcode'))
                        ->where('refsource',$obj->source)
                        ->where('reftrantype',$obj->trantype)
                        ->where('refauditno',$obj->auditno)
                        ->where('recstatus', 'POSTED')
                        ->get();

            foreach ($apalloc as $obj_alloc){
                $osamt = $osamt - $obj_alloc->allocamount;
            }

            if(!$this->floatEquals($obj->outamount,$osamt)){
                $obj->osamt_alloc = $osamt;
                array_push($array, $obj);
            }
        }

        return view('test.test2',compact('array'));
    }

    public function form(Request $request){
        switch($request->action){
            case 'merge_pdf':
                return $this->merge_pdf($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request){  
        switch($request->action){
            case 'test_email':
                return $this->test_email($request);
            case 'check_netmvqty_netmvval_allitem':
                return $this->check_netmvqty_netmvval_allitem($request);
            case 'btlkan_thqty_stocktake':
                return $this->btlkan_thqty_stocktake($request);
            case 'check_qtyonhand_versus_netmvqty':
                return $this->check_qtyonhand_versus_netmvqty($request);
            case 'bill_vs_mrn':
                return $this->bill_vs_mrn($request);
            case 'check_product_chgmast_stockloc_xsama_uom':
                return $this->check_product_chgmast_stockloc_xsama_uom($request);
            case 'compare_stocktake_csv':
                return $this->compare_stocktake_csv($request);
            case 'compare_product_csv':
                return $this->compare_product_csv($request);
            case 'compare_product_np_csv':
                return $this->compare_product_np_csv($request);
            case 'tukar_uom_product_csv':
                return $this->tukar_uom_product_csv($request);
            case 'tukar_uom_product_np_csv':
                return $this->tukar_uom_product_np_csv($request);
            case 'tukar_semua_ivtxntdt_idspdt_uombaru':
                return $this->tukar_semua_ivtxntdt_idspdt_uombaru($request);
            case 'betulkan_uom_billsum':
                return $this->betulkan_uom_billsum($request);
            case 'compare_stockbalance_report_vs_pnl':
                return $this->compare_stockbalance_report_vs_pnl($request);
            case 'post_apadhoc':
                return $this->post_apadhoc($request);
            case 'itemcode_avgcost':
                return $this->itemcode_avgcost($request);
            case 'itemcode_avgcost_ivdspdt':
                return $this->itemcode_avgcost_ivdspdt($request);
            case 'betulkan_stockexp_semua':
                return $this->betulkan_stockexp_semua($request);
            case 'len_len':
                return $this->len_len($request);
            case 'apalloc_osamt_in':
                return $this->apalloc_osamt_in($request);
            case 'apalloc_osamt_cn':
                return $this->apalloc_osamt_cn($request);
            // case 'betulkan_stockloc_2025':
            //     return $this->betulkan_stockloc_2025($request);
            // case 'netmvval_from_netmvqty':
            //     return $this->netmvval_from_netmvqty($request);
            // case 'cr8_acctmaster':
            //     return $this->cr8_acctmaster($request);
            // case 'recondb_ledger':
            //     return $this->recondb_ledger($request);
            case 'display_glmasref_xde':
                return $this->display_glmasref_xde($request);
            case 'display_glmasref_header':
                return $this->display_glmasref_header($request);

                
            case 'gltran_step1':
                return $this->gltran_step1($request);
            case 'gltran_step2':
                return $this->gltran_step2($request);
            case 'gltran_step3':
                return $this->gltran_step3($request);
            case 'gltran_step4':
                return $this->gltran_step4($request);
            case 'gltran_poliklinik':
                return $this->gltran_poliklinik($request);
            // case 'stockloc_total':
            //     return $this->stockloc_total($request);
            // case 'dballoc_2bl_betulkn':
            //     return $this->dballoc_2bl_betulkn($request);
            // case 'betulkan_uom_kh_stockloc':
            //     return $this->betulkan_uom_kh_stockloc($request);
            // case 'betulkan_uom_kh_product':
            //     return $this->betulkan_uom_kh_product($request);
            // case 'betulkan_uom_kh_stockexp':
            //     return $this->betulkan_uom_kh_stockexp($request);
            // case 'betulkan_apacthdr':
            //     return $this->betulkan_apacthdr($request);
            // case 'tukar_uom':
            //     return $this->tukar_uom($request);
            // case 'update_chgprice':
            //     return $this->update_chgprice($request);
            // case 'betulkandb':
            //     return $this->betulkandb($request);
            // case 'betulkan_ivtxndt':
            //     return $this->betulkan_ivtxndt($request);
            // case 'msdemo_chgprice':
            //     return $this->msdemo_chgprice($request);
            // case 'tunjuk_doctorcode':
            //     return $this->tunjuk_doctorcode($request);
            case 'dmmc_einvoice_amik_invalid':
                return $this->dmmc_einvoice_amik_invalid($request);
            default:
                return 'error happen..';
        }
    }

    public function chgmast_invflag_tukar_dari_product(Request $request){
        // $chgmast = DB::table('hisdb.chgmast as cm')
        //             ->where('cm.compcode','9A')
        //             ->where('cm.chggroup','25')
        //             ->where('cm.invflag','1')
        //             ->whereNull('p.idno')
        //             ->leftjoin('material.product as p', function($join) use ($request){
        //                 $join = $join->on('p.itemcode', '=', 'cm.chgcode')
        //                               ->where('p.compcode','9A');
        //             })
        //             ->update([
        //                 'invflag' => 0
        //             ]);

        // dd($chgmast->count());
    }

    public function load_discipline(Request $request){
        $disc_table=[
            ['8001','ANA','Anaesthesiology'],
            ['8002','AUDIO','Audiology'],
            ['8003','BAR','Bariatric Surgery'],
            ['8004','CAD','Cardiothorasic'],
            ['8005','CLI','Clinical Psycology'],
            ['8006','COL','Colorectal'],
            ['8007','DER','Dermatology'],
            ['8008','DIE','Dietician'],
            ['8009','ED','EMERGENCY PHYSICION'],
            ['8010','END','Endocrinology'],
            ['8011','ENT','Ear Nose and Throat'],
            ['8012','GAS','Gastroenterology'],
            ['8013','GER','Geriatric'],
            ['8014','GP','General Practitioner'],
            ['8015','HDS','Haemodialysis'],
            ['8016','INF','Infection Disease'],
            ['8017','INM','Internal Medicine'],
            ['8018','MAX','MAXILOFACIAL'],
            ['8019','MED','Medical'],
            ['8020','MO','Medical Officer'],
            ['8021','NEP','Nephrology'],
            ['8022','NONE','NONE / OTC'],
            ['8023','O&G','Obstetrics & Gynaecology'],
            ['8024','ONC','Oncology'],
            ['8025','OPT','Opthalmology'],
            ['8026','ORT','Orthopedic'],
            ['8027','OTH','Others'],
            ['8028','PAE','Paediatrics'],
            ['8029','PAT','Pathology'],
            ['8030','PHA','Pharmacist'],
            ['8031','PHR','Pharmacology'],
            ['8032','PHY','physicion'],
            ['8033','PHYSIO','PHYSIOTHERAPY'],
            ['8034','PLA','Plastic Surgery'],
            ['8035','PRI','Primary Care'],
            ['8036','PSI','Physiotherapist'],
            ['8037','PSY','Psychiatrist'],
            ['8038','PUB','Public Health'],
            ['8039','RAD','Radiology'],
            ['8040','REH','Rehab'],
            ['8041','RES','Respiratory'],
            ['8042','RGP','Radiographer'],
            ['8043','RHE','Rheumatology'],
            ['8044','SAT','Speech / Audio Therapist'],
            ['8045','SUR','Surgery'],
            ['8046','URO','Urology'],
            ['8047','VAS','Vascular Surgery']
        ];

        foreach ($disc_table as $key => $value) {
            $ccode=$value[0];
            $deptcode=$value[1];
            $desc=$value[2];

            DB::beginTransaction();

            try {
                $exist = DB::table('hisdb.discipline')
                            ->where('compcode','9A')
                            ->where('code',$deptcode)
                            ->exists();

                if(!$exist){
                    DB::table('hisdb.discipline')
                        ->insert([
                            'compcode' => '9A',
                            'code' => $deptcode,
                            'description' => $desc,
                            'deptcode' => $deptcode,
                            'recstatus' => 'ACTIVE',
                        ]);
                }else{
                    dump('discipline exist: '.$deptcode);
                }


                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                report($e);

                dd('Error'.$e);
            }
        }
    }

    public function insert_phst(Request $request){

        DB::beginTransaction();

        try {

            $stockloc = DB::table('material.stockloc')
                            ->where('compcode','9A')
                            ->where('deptcode','PHAR')
                            ->where('year','2024')
                            ->get();

            foreach ($stockloc as $key => $value) {
                $exist = DB::table('material.stockloc')
                            ->where('compcode','9A')
                            ->where('deptcode','PHST')
                            ->where('year',$value->year)
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->exists();

                if(!$exist){
                    DB::table('material.stockloc')
                        ->insert([
                            'compcode' => $value->compcode,
                            'deptcode' => 'PHST',
                            'itemcode' => $value->itemcode,
                            'uomcode' => $value->uomcode,
                            'bincode' => $value->bincode,
                            'year' => $value->year,
                            'stocktxntype' => $value->stocktxntype,
                            'disptype' => $value->disptype,
                            'minqty' => $value->minqty,
                            'maxqty' => $value->maxqty,
                            'reordlevel' => $value->reordlevel,
                            'reordqty' => $value->reordqty,
                            'lastissdate' => $value->lastissdate,
                            'frozen' => $value->frozen,
                            'adduser' => $value->adduser,
                            'adddate' => $value->adddate,
                            'upduser' => $value->upduser,
                            'upddate' => $value->upddate,
                            'recstatus' => $value->recstatus,
                            'deluser' => $value->deluser,
                            'deldate' => $value->deldate,
                            'unit' => $value->unit
                        ]);
                    }else{
                        dump($value->itemcode.'exists');
                    }
                
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function debtortype_xde(Request $request){
        DB::beginTransaction();

        try {

            $debtormast = DB::table('debtor.debtormast as dm')
                            ->where('dm.compcode','9A')
                            ->select('dm.debtortype')
                            ->distinct();

            foreach ($debtormast->get() as $value) {
                $debtortype = DB::table('debtor.debtortype as dy')
                            ->select('dy.description')
                            ->where('dy.compcode','9A')
                            ->where('dy.debtortycode',$value->debtortype);

                if(!$debtortype->exists()){
                    dump($value->debtortype);
                }
            }

            dd('done');

            // DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function set_class(Request $request){
        $product = DB::table('material.product')
                    ->select('product.idno','product.itemcode','product.description','product.productcat','product.Class as p_class','category.Class as c_class')
                    ->leftJoin('material.category', function($join){
                        $join = $join->where('category.compcode','9B');
                        $join = $join->on('category.catcode','product.productcat');
                    })
                    ->where('product.compcode','9B')
                    ->get();

        foreach ($product as $key => $value) {
            if(empty($value->p_class)){
                DB::table('material.product')
                    ->where('compcode','9B')
                    ->where('idno',$value->idno)
                    ->update([
                        'Class' => $value->c_class
                    ]);
            }
        }
    }

    public function set_stockloc_unit(Request $request){
        $stockloc = DB::table('material.stockloc')
                    ->select('stockloc.unit','department.sector','stockloc.idno')
                    ->join('sysdb.department', function($join){
                        $join = $join->where('department.compcode','9B');
                        $join = $join->on('department.deptcode','stockloc.deptcode');
                    })
                    ->where('stockloc.compcode','9B')
                    ->get();

        foreach ($stockloc as $key => $value) {
            if(empty($value->unit)){
                DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('idno',$value->idno)
                    ->update([
                        'unit' => $value->sector
                    ]);
            }
        }
    }

    public function test_alert_auth(Request $request){
        $queuepr = DB::table('material.queuepr as qpr')
                    ->select('adtl.authorid','prhd.recno','prhd.reqdept','prhd.purreqno','prhd.purreqdt','prhd.recstatus','prhd.totamount','prhd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PR')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.recstatus','qpr.trantype')
                            ->where(function ($query) {
                                $query->on('adtl.deptcode','qpr.deptcode')
                                      ->orWhere('adtl.deptcode', 'ALL');
                            });
                    })
                    ->join('material.purreqhd as prhd', function($join) use ($request){
                        $join = $join
                            ->where('prhd.compcode',session('compcode'))
                            ->on('prhd.recno','qpr.recno')
                            ->on('prhd.recstatus','qpr.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('prhd.totamount','>=','adtl.minlimit')
                                    ->on('prhd.totamount','<', 'adtl.maxlimit');
                            });;
                    })
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype','<>','DONE')
                    ->get();

        dump($queuepr);
        // dd($this->getQueries($queuepr));

        // $authdtl = DB::table('material.authdtl as adtl')
        //             ->select('qpr.recno','qpr.deptcode',)
        //             ->leftJoin('material.queuepr as qpr', function($join) use ($request){
        //                 $join = $join
        //                             ->where('qpr.trantype','<>','DONE')
        //                             ->on('qpr.trantype','adtl.recstatus')
        //                             ->where('qpr.compcode','=',session('compcode'));
        //             })
        //             ->where('adtl.compcode',session('compcode'))
        //             ->where('adtl.authorid',session('username'))
        //             ->where('adtl.trantype','PR')
        //             ->where('adtl.cando','ACTIVE')
        //             ->get();
        // dump($authdtl);



        $authdtl = DB::table('material.authdtl')
                ->where('compcode',session('compcode'))
                ->where('authorid',session('username'))
                ->where('trantype','PR')
                ->where('cando','ACTIVE')
                ->get();

        dump($authdtl);


        $queuepr = DB::table('material.queuepr')
                ->where('compcode',session('compcode'))
                ->where('trantype','<>','DONE')
                ->get();

        dd($queuepr);
    }

    public function test_glmasdtl(Request $request){
        $glmasdtl = DB::table('test.newcostcode')
                            ->get();

        foreach ($glmasdtl as $obj) {
            $exist = DB::table('finance.costcenter')
                        ->where('compcode','9A')
                        ->where('costcode',$obj->code)
                        ->exists();

            if($exist){
                dump('Skipping - '.$obj->code.' already exists');
            }else{
                dump('Insert - '.$obj->code);
                DB::table('finance.costcenter')
                    ->insert([
                        'compcode' => '9A',
                        'costcode' => $obj->code,
                        'description' => $obj->desc,
                        'recstatus' => 'ACTIVE',
                    ]);
            }
        }
    }

    public function merge_pdf(Request $request){
        Storage::disk('pdf_merge')->put($request->merge_key.'_'.$request->lineno_.'.pdf',base64_decode($request->base64));
        DB::table('sysdb.pdf_merge')
            ->insert([
                'compcode' => session('compcode'),
                'merge_key' => $request->merge_key,
                'lineno_' => $request->lineno_,
            ]);
    }

    public function get_merge_pdf(Request $request){
        $merge_key = $request->merge_key;
        $pdf_merge = DB::table('sysdb.pdf_merge')
                        ->where('compcode',session('compcode'))
                        ->where('merge_key',$merge_key);

        if($pdf_merge->exists()){
            $pdf_merge = $pdf_merge->get();
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;

            foreach ($pdf_merge as $obj) {
                $pdf->addPDF(public_path().'/uploads/pdf_merge/'.$merge_key.'_'.$obj->lineno_.'.pdf', 'all');
            }
        }

        $pdf->merge('browser', public_path() . '/uploads/pdf_merge/'.$merge_key.'.pdf', 'P');
    }

    public function update_productmaster(Request $request){
        DB::beginTransaction();

        try {
            
            $productmaster = DB::table('temp.productmaster')
                            ->where('compcode','9B')
                            ->get();

            foreach ($productmaster as $obj) {
                $duplicate = DB::table('temp.productmaster')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj->itemcode)
                            ->get();

                if($duplicate->count() > 1){
                    $del = DB::table('temp.productmaster')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj->itemcode)
                            ->first();
                    DB::table('temp.productmaster')
                        ->where('idno',$del->idno)
                        ->update([
                            'compcode' => 'XX'
                        ]);
                    echo  nl2br("del idno : ".$del->idno."\n");
                }
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function update_stockexp(Request $request){
        DB::beginTransaction();

        try {
            
            $stockloc = DB::table('temp.stockloc')
                            ->where('compcode','9B')
                            ->get();

            foreach ($stockloc as $obj) {
                $stockexp = DB::table('temp.stockexp')
                            ->where('unit',$obj->unit)
                            ->where('compcode',$obj->compcode)
                            ->where('year',$obj->year)
                            ->where('deptcode',$obj->deptcode)
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode);

                if(!$stockexp->exists()){
                    DB::table('temp.stockexp')
                        ->insert([
                            'compcode' => $obj->compcode,
                            'deptcode' => $obj->deptcode,
                            'itemcode' => $obj->itemcode,
                            'uomcode' => $obj->uomcode,
                            'expdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'batchno' => 'OB',
                            'balqty' => $obj->qtyonhand,
                            'adduser' => 'SYSTEM',
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'addtime' => $obj->compcode,
                            // 'upduser' => $obj->compcode,
                            // 'upddate' => $obj->compcode,
                            // 'updtime' => $obj->compcode,
                            // 'lasttt' => $obj->compcode,
                            'year' => Carbon::now("Asia/Kuala_Lumpur")->year,
                            'unit' => $obj->unit,
                        ]);
                }
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function del_stockexp(Request $request){
        DB::beginTransaction();

        try {
            
            $stockloc = DB::table('temp.stockloc')
                            ->where('compcode','9B')
                            ->where('unit',"W'HOUSE")
                            ->get();

            // dd($stockloc);
            $i = 1;
            foreach ($stockloc as $obj) {

                $stockexp = DB::table('temp.stockexp')
                                ->where('unit',$obj->unit)
                                ->where('compcode',$obj->compcode)
                                ->where('year',$obj->year)
                                ->where('deptcode',$obj->deptcode)
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode','!=',$obj->uomcode);

                if($stockexp->exists()){
                    $stockexp_f = $stockexp->first();

                    DB::table('temp.stockexp')
                                ->where('unit',$obj->unit)
                                ->where('compcode',$obj->compcode)
                                ->where('year',$obj->year)
                                ->where('deptcode',$obj->deptcode)
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode','=',$stockexp_f->uomcode)
                                ->delete();


                    echo nl2br("$i. upd : $obj->itemcode | $obj->uomcode | $obj->unit | uomcode delete: $stockexp_f->uomcode\n");
                    $i++;
                }

            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function upd_stockexp_unit(Request $request){
        DB::beginTransaction();

        try {
            
            $stockloc = DB::table('temp.stockloc')
                            ->where('compcode','9B')
                            ->where('unit',"W'HOUSE")
                            ->get();

            // dd($stockloc);
            $i = 1;
            foreach ($stockloc as $obj) {

                $stockexp = DB::table('temp.stockexp')
                                ->whereNull('unit')
                                ->where('compcode',$obj->compcode)
                                ->where('year',$obj->year)
                                ->where('deptcode',$obj->deptcode)
                                ->where('itemcode',$obj->itemcode);
                                // ->where('uomcode','!=',$obj->uomcode);

                if($stockexp->exists()){
                    $stockexp_f = $stockexp->first();

                    DB::table('temp.stockexp')
                                // ->where('unit',$obj->unit)
                                ->where('compcode',$obj->compcode)
                                ->where('year',$obj->year)
                                ->where('deptcode',$obj->deptcode)
                                ->where('itemcode',$obj->itemcode)
                                // ->where('uomcode','=',$stockexp_f->uomcode)
                                ->update([
                                    'unit' => $obj->unit
                                ]);


                    echo nl2br("$i. upd : $obj->itemcode | $obj->uomcode | $obj->unit \n");
                    $i++;
                }

            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function update_stockloc(Request $request){
        DB::beginTransaction();

        try {
            
            $stockloc = DB::table('temp.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode','KW000102')
                            ->whereNull('unit')
                            ->get();

            // dd($stockloc);

            foreach ($stockloc as $obj) {

                $product = DB::table('temp.product')
                                ->where('compcode','9B')
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode',$obj->uomcode)
                                ->whereNotNull('unit');

                dd($product->first());

                if($product->exists()){
                    DB::table('temp.stockloc')
                        ->where('idno',$obj->idno)
                        ->update([
                            'unit' => $product->first()->unit
                        ]);

                    echo nl2br("upd idno : ".$obj->idno."\n");
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function update_stockloc_uomcode(Request $request){
        DB::beginTransaction();
        if(empty($request->itemcode)){
            dd('no itemcode');
        }

        try {
            
            $product = DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('unit',"W'HOUSE")
                            ->whereIn('itemcode',[$request->itemcode])
                            ->get();

            // dd($product);
            $i = 1;
            foreach ($product as $obj) {

                // $stockloc = DB::table('material.stockloc')
                //                 ->where('compcode','9B')
                //                 ->where('itemcode',$obj->itemcode)
                //                 ->where('uomcode',$obj->uomcode)
                //                 ->where('unit',"W'HOUSE");

                // if($stockloc->exists()){
                //     continue;
                // }

                $stockloc = DB::table('material.stockloc')
                                ->where('compcode','9B')
                                ->where('uomcode',$obj->uomcode)
                                ->where('unit',"W'HOUSE")
                                ->where('deptcode',"FKWSTR")
                                ->where('itemcode',$obj->itemcode);

                if($stockloc->exists()){
                    $stockloc_first = $stockloc->first();
                    // DB::table('material.stockloc')
                    //         ->where('compcode','9B')
                    //         ->where('uomcode',$obj->uomcode)
                    //         ->where('unit',"W'HOUSE")
                    //         ->where('deptcode',"FKWSTR")
                    //         ->where('itemcode',$obj->itemcode)
                    //         ->update([
                    //             'uomcode' => $obj->uomcode,
                    //             'unit' => $obj->unit
                    //         ]);

                    $balqty = $stockloc_first->netmvqty1 + $stockloc_first->netmvqty2 + $stockloc_first->netmvqty3 + $stockloc_first->netmvqty4 + $stockloc_first->netmvqty5 + $stockloc_first->netmvqty6 + $stockloc_first->netmvqty7 + $stockloc_first->netmvqty8;

                    DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            // ->where('deptcode',"FKWSTR")
                            ->update([
                                'qtyonhand' => $balqty
                            ]);


                    $stockexp = DB::table('material.stockexp')
                                ->where('unit',$obj->unit)
                                ->where('compcode',$obj->compcode)
                                ->where('year',$stockloc_first->year)
                                ->where('deptcode',$stockloc_first->deptcode)
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode',$obj->uomcode);

                    if(!$stockexp->exists()){
                        DB::table('material.stockexp')
                            ->insert([
                                'compcode' => $obj->compcode,
                                'deptcode' => $stockloc_first->deptcode,
                                'itemcode' => $obj->itemcode,
                                'uomcode' => $obj->uomcode,
                                'expdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'batchno' => 'OB',
                                'balqty' => $balqty,
                                'adduser' => 'SYSTEM',
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                // 'addtime' => $obj->compcode,
                                // 'upduser' => $obj->compcode,
                                // 'upddate' => $obj->compcode,
                                // 'updtime' => $obj->compcode,
                                // 'lasttt' => $obj->compcode,
                                'year' => Carbon::now("Asia/Kuala_Lumpur")->year,
                                'unit' => $obj->unit,
                            ]);
                    }
                    
                }

                echo nl2br("$i. upd : $obj->itemcode | $obj->uomcode | $obj->unit | qtyonhand: $balqty\n");
                $i++;

            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function test_email(Request $request){
        // $trantype = 'VERIFIED';
        // $recno = '145';
        
        // $qpv = DB::table('finance.queuepv as qpv')
        //             ->select('qpv.trantype','prdtl.authorid','ap.pvno','qpv.recno','ap.recdate','qpv.recstatus','ap.amount','ap.payto','ap.adduser','users.email')
        //             ->join('finance.permissiondtl as prdtl', function($join){
        //                 $join = $join
        //                     ->where('prdtl.compcode',session('compcode'))
        //                     // ->where('adtl.authorid',session('username'))
        //                     ->where('prdtl.trantype','PV')
        //                     ->where('prdtl.cando','ACTIVE')
        //                     // ->on('adtl.prtype','qpo.prtype')
        //                     ->on('prdtl.recstatus','qpv.trantype');
        //             })
        //             ->join('finance.apacthdr as ap', function($join){
        //                 $join = $join
        //                     ->where('ap.compcode',session('compcode'))
        //                     ->where('ap.trantype','PV')
        //                     ->on('ap.auditno','qpv.recno')
        //                     ->on('ap.recstatus','qpv.recstatus')
        //                     ->where(function ($query) {
        //                         $query
        //                             ->on('ap.amount','>=','prdtl.minlimit')
        //                             ->on('ap.amount','<=', 'prdtl.maxlimit');
        //                     });;
        //             })
        //             ->join('sysdb.users as users', function($join){
        //                 $join = $join
        //                     ->where('users.compcode',session('compcode'))
        //                     ->where('users.email','HAZMAN.YUSOF@GMAIL.COM')
        //                     ->on('users.username','prdtl.authorid');
        //             })
        //             ->where('qpv.compcode',session('compcode'))
        //             ->where('qpv.trantype',$trantype)
        //             ->where('qpv.recno',$recno)
        //             ->get();

        $data = new stdClass();
        $data->email = 'HAZMAN.YUSOF@GMAIL.COM';
        $data->trantype = 'PV';
        $data->recno = '111';
        $data->recstatus = 'ACTIVE';
        $data->authorid = 'HAZMAN';
        $data->payto = 'HAZMAN';
        $data->recdate = '2025-01-01';
        $data->adduser = 'HAZMAN';

        $array = [];
        array_push($array, $data);
        $collection = collect($array);
                    
        SendEmailPV::dispatch($collection);
    }

    public function update_supplier(Request $request){
        DB::beginTransaction();
        try {
            
            $supplier = DB::table('temp.supplier')
                            ->get();

            $i = 1;
            foreach ($supplier as $obj) {
                $ori_supplier = DB::table('material.supplier')
                                    ->where('compcode','9B')
                                    ->where('suppcode',$obj->SuppCode);

                if(!$ori_supplier->exists()){
                    DB::table('material.supplier')
                        ->insert([
                            'CompCode' => $obj->CompCode,
                            'SuppCode' => $obj->SuppCode,
                            'SuppGroup' => $obj->SuppGroup,
                            'Name' => $obj->Name,
                            'ContPers' => $obj->ContPers,
                            'Addr1' => $obj->Addr1,
                            'Addr2' => $obj->Addr2,
                            'Addr3' => $obj->Addr3,
                            'Addr4' => $obj->Addr4,
                            'TelNo' => $obj->TelNo,
                            'Faxno' => $obj->Faxno,
                            'TermOthers' => $obj->TermOthers,
                            'TermNonDisp' => $obj->TermNonDisp,
                            'TermDisp' => $obj->TermDisp,
                            'CostCode' => $obj->CostCode,
                            'GlAccNo' => $obj->GlAccNo,
                            'OutAmt' => $obj->OutAmt,
                            'AccNo' => $obj->AccNo,
                            'AddUser' => $obj->AddUser,
                            'AddDate' => $obj->AddDate,
                            'UpdUser' => $obj->UpdUser,
                            'UpdDate' => $obj->UpdDate,
                            'DelUser' => $obj->DelUser,
                            'DelDate' => $obj->DelDate,
                            'DepAmt' => $obj->DepAmt,
                            'MiscAmt' => $obj->MiscAmt,
                            'SuppFlg' => $obj->SuppFlg,
                            'Advccode' => $obj->Advccode,
                            'AdvGlaccno' => $obj->AdvGlaccno,
                            'recstatus' => $obj->recstatus,
                            'computerid' => $obj->computerid,
                            'ipaddress' => $obj->ipaddress,
                            'lastcomputerid' => $obj->lastcomputerid,
                            'lastipaddress' => $obj->lastipaddress,
                            'GSTID' => $obj->GSTID,
                            'CompRegNo' => $obj->CompRegNo,
                            'TermDays' => $obj->TermDays,
                            'TINNo' => $obj->TINNo,
                        ]);

                    echo nl2br("$i. masuk supplier: $obj->SuppCode , $obj->Name \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function update_chgmast(Request $request){
        DB::beginTransaction();
        try {
            
            $chgmast = DB::table('temp.chgmast')
                            ->where('unit','IMP')
                            ->get();

            $i = 1;
            foreach ($chgmast as $obj) {
                $chgprice = DB::table('temp.chgprice')
                                    ->where('compcode',$obj->compcode)
                                    // ->whereNu('unit',$obj->unit)
                                    ->where('chgcode',$obj->chgcode)
                                    ->where('uom',$obj->uom);

                if($chgprice->exists()){
                    DB::table('temp.chgprice')
                        ->where('compcode',$obj->compcode)
                        // ->whereNu('unit',$obj->unit)
                        ->where('chgcode',$obj->chgcode)
                        ->where('uom',$obj->uom)
                        ->update([
                            'unit' => $obj->unit,
                        ]);

                    echo nl2br("$i. update chgprice: $obj->chgcode \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function update_chgprice(Request $request){
        DB::beginTransaction();
        try {
            
            $chgmast = DB::table('temp.chgmast')
                            ->get();

            $i = 1;
            foreach ($chgmast as $obj) {
                $chgprice = DB::table('temp.chgprice')
                                    ->where('compcode',$obj->compcode)
                                    ->whereNull('unit')
                                    ->where('chgcode',$obj->chgcode)
                                    ->where('uom',$obj->uom);

                if($chgprice->exists()){
                    DB::table('temp.chgprice')
                        ->where('compcode',$obj->compcode)
                        ->whereNull('unit')
                        ->where('chgcode',$obj->chgcode)
                        ->where('uom',$obj->uom)
                        ->update([
                            'unit' => $obj->unit,
                        ]);

                    echo nl2br("$i. update chgprice: $obj->chgcode \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_purreqhd_2(Request $request){
        DB::beginTransaction();
        try {
            
            $purreqhd = DB::table('temp.purreqhd')
                            ->get();

            $i = 1;
            foreach ($purreqhd as $obj) {
                // $dbactdtl = DB::table('debtor.dbactdtl')
                //                     ->where('source','PB')
                //                     ->where('trantype','IN');

                $purreqdt = DB::table('temp.purreqdt')
                                    ->where('compcode','9B')
                                    ->where('recno',$obj->recno);

                if($purreqdt->exists()){
                    $totamount = DB::table('temp.purreqdt')
                                    ->where('compcode','9B')
                                    ->where('recno',$obj->recno)
                                    ->sum('totamount');

                    DB::table('temp.purreqhd')
                                    ->where('compcode','9B')
                                    ->where('idno',$obj->idno)
                                    ->update([
                                        'totamount' => $totamount
                                    ]);                

                    echo nl2br("$i. update purreqhd: $totamount \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_purreqhd(Request $request){
        DB::beginTransaction();
        try {
            
            $purreqdt = DB::table('material.purreqdt')
                            ->get();

            $i = 1;
            foreach ($purreqdt as $obj) {
                // $dbactdtl = DB::table('debtor.dbactdtl')
                //                     ->where('source','PB')
                //                     ->where('trantype','IN');

                $purreqhd = DB::table('material.purreqhd')
                                    ->where('recno',$obj->recno);

                if(!$purreqhd->exists()){

                    $amount = DB::table('material.purreqdt')
                                    ->where('recno',$obj->recno)
                                    ->sum('totamount');

                    if($obj->unit != 'MRS'){
                        $prdept = $obj->unit;
                    }else{
                        $prdept = 'PCS';
                    }

                    DB::table('material.purreqhd')
                        ->insert([
                            'compcode' => '9B',
                            'reqdept' => $obj->reqdept,
                            'purreqno' => $obj->purreqno,
                            'purreqdt' => Carbon::parse($obj->adddate)->format('Y-m-d'),
                            'recno' => $obj->recno,
                            // 'reqpersonid' => $obj->,
                            'prdept' => $prdept,
                            // 'authpersonid' => $obj->,
                            // 'authdate' => $obj->,
                            'remarks' => $obj->remarks,
                            'recstatus' => $obj->recstatus,
                            'subamount' => $obj->amount,
                            // 'amtdisc' => $obj->,
                            // 'perdisc' => $obj->,
                            'totamount' => $obj->amount,
                            'adduser' => 'system',
                            // 'adddate' => $obj->,
                            'upduser' => 'system',
                            // 'upddate' => $obj->,
                            // 'cancelby' => $obj->,
                            // 'canceldate' => $obj->,
                            // 'reopenby' => $obj->,
                            // 'reopendate' => $obj->,
                            // 'suppcode' => $obj->suppcode,
                            'purordno' => $obj->purordno,
                            // 'prortdisc' => $obj->,
                            'unit' => $obj->unit,
                            'trantype' => 'PR',
                            // 'TaxAmt' => $obj->,
                            'requestby' => $obj->adduser,
                            'requestdate' => $obj->adddate,
                            'supportby' => 'system',
                            'supportdate' => $obj->adddate,
                            'verifiedby' => 'system',
                            'verifieddate' => $obj->adddate,
                            'approvedby' => 'system',
                            'approveddate' => $obj->adddate,
                            'prtype' => 'OTHERS',
                            // 'assetno' => $obj->,
                        ]);

                    echo nl2br("$i. update purreqhd: $obj->recno \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_purordhd(Request $request){
        DB::beginTransaction();
        try {
            
            $purorddt = DB::table('material.purorddt')
                            ->get();

            $i = 1;
            foreach ($purorddt as $obj) {

                $purordhd = DB::table('material.purordhd')
                                    ->where('recno',$obj->recno);

                if(!$purordhd->exists()){

                    $amount = DB::table('material.purorddt')
                                    ->where('recno',$obj->recno)
                                    ->sum('totamount');

                    if($obj->unit != "W'HOUSE"){
                        $prtype = 'STOCK';
                    }else{
                        $prtype = 'OTHERS';
                    }

                    DB::table('material.purordhd')
                        ->insert([
                             'recno' => $obj->recno,
                             'prdept' => $obj->prdept,
                             'purordno' => $obj->purordno,
                             'compcode' => '9B',
                             'reqdept' => $obj->reqdept,
                             'purreqno' => $obj->purreqno,
                             'deldept' => $obj->prdept,
                             'purdate' => Carbon::parse($obj->adddate)->format('Y-m-d'),
                             // 'expecteddate' => $obj->,
                             // 'expirydate' => $obj->,
                             'suppcode' => $obj->suppcode,
                             // 'credcode' => $obj->,
                             // 'termdays' => $obj->,
                             'subamount' => $amount,
                             // 'amtdisc' => $obj->,
                             // 'perdisc' => $obj->,
                             'totamount' => $amount,
                             // 'taxclaimable' => $obj->,
                             // 'isspersonid' => $obj->,
                             // 'issdate' => $obj->,
                             // 'authpersonid' => $obj->,
                             // 'authdate' => $obj->,
                             'remarks' => $obj->remarks,
                             'recstatus' => $obj->recstatus,
                             'adduser' => 'SYSTEM',
                             'adddate' => $obj->adddate,
                             // 'upduser' => $obj->,
                             // 'upddate' => $obj->,
                             // 'assflg' => $obj->,
                             // 'potype' => $obj->,
                             // 'delordno' => $obj->,
                             // 'expflg' => $obj->,
                             // 'prortdisc' => $obj->,
                             // 'cancelby' => $obj->,
                             // 'canceldate' => $obj->,
                             // 'reopenby' => $obj->,
                             // 'reopendate' => $obj->,
                             // 'TaxAmt' => $obj->,
                             // 'postedby' => $obj->,
                             // 'postdate' => $obj->,
                             'unit' => $obj->unit,
                             'trantype' => 'PO',
                             'requestby' => $obj->adduser,
                             'requestdate' => $obj->adddate,
                             'supportby' => 'SYSTEM',
                             'supportdate' => $obj->adddate,
                             'verifiedby' => 'SYSTEM',
                             'verifieddate' => $obj->adddate,
                             'approvedby' => 'SYSTEM',
                             'approveddate' => $obj->adddate,
                             // 'support_remark' => $obj->,
                             // 'verified_remark' => $obj->,
                             // 'approved_remark' => $obj->,
                             // 'cancelled_remark' => $obj->,
                             'prtype' => $prtype,
                             // 'assetno' => $obj->,
                        ]);

                    echo nl2br("$i. update purordhd: $obj->recno \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_delorddt(Request $request){
        DB::beginTransaction();
        try {
            
            $ivtxndt = DB::table('material.ivtxndt')
                            ->get();

            $i = 1;
            foreach ($ivtxndt as $obj) {
                DB::table('material.delorddt')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'recno' => $obj->recno,
                        'lineno_' => $obj->lineno_,
                        'pricecode' => 'IV',
                        'itemcode' => $obj->itemcode,
                        'uomcode' => $obj->uomcode,
                        'pouom' => $obj->uomcode,
                        // 'suppcode' => $obj->,
                        'trandate' => $obj->adddate,
                        // 'deldept' => $obj->,
                        // 'deliverydate' => $obj->,
                        // 'qtytag' => $obj->,
                        'unitprice' => $obj->netprice,
                        // 'amtdisc' => $obj->,
                        // 'perdisc' => $obj->,
                        // 'prortdisc' => $obj->,
                        // 'amtslstax' => $obj->,
                        // 'perslstax' => $obj->,
                        'netunitprice' => $obj->netprice,
                        'remarks' => $obj->remarks,
                        'expdate' => $obj->expdate,
                        'batchno' => $obj->batchno,
                        // 'sitemcode' => $obj->,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        // 'upduser' => $obj->,
                        // 'upddate' => $obj->,
                        // 'discflg' => $obj->,
                        // 'discval' => $obj->,
                        'qtyorder' => $obj->txnqty,
                        'qtydelivered' => $obj->txnqty,
                        // 'qtyoutstand' => $obj->,
                        // 'productcat' => $obj->,
                        // 'draccno' => $obj->,
                        // 'drccode' => $obj->,
                        // 'craccno' => $obj->,
                        // 'crccode' => $obj->,
                        // 'source' => $obj->,
                        // 'updtime' => $obj->,
                        'polineno' => $obj->lineno_,
                        // 'itemmargin' => $obj->,
                        'amount' => $obj->amount,
                        // 'deluser' => $obj->,
                        // 'deldate' => $obj->,
                        'recstatus' => 'POSTED',
                        // 'taxcode' => $obj->,
                        'totamount' => $obj->amount,
                        // 'qtyreturned' => $obj->,
                        // 'rem_but' => $obj->,
                        'unit' => $obj->unit,
                        // 'prdept' => $obj->,
                        // 'srcdocno' => $obj->,
                        // 'kkmappno' => $obj->,
                    ]);

                echo nl2br("$i. update delorddt: $obj->recno \n");
                $i++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_apacthdr(Request $request){
        DB::beginTransaction();
        try {
            
            $apalloc = DB::table('finance.apalloc')
                            ->get();

            $i = 1;
            foreach ($apalloc as $obj) {

                // $apacthdr_al = DB::table('finance.apacthdr')
                //             ->where('source','AP')
                //             ->where('trantype','AL')
                //             ->where('auditno',$obj->refauditno);

                $apacthdr_pv = DB::table('finance.apacthdr')
                            ->where('source','AP')
                            ->where('trantype','PV')
                            ->where('auditno',$obj->docauditno);

                if($apacthdr_pv->exists()){

                    $amount = DB::table('finance.apalloc')
                            ->where('docsource','AP')
                            ->where('doctrantype','PV')
                            ->where('docauditno',$obj->docauditno)
                            ->sum('allocamount');

                    DB::table('finance.apacthdr')
                        ->where('source','AP')
                        ->where('trantype','PV')
                        ->where('auditno',$obj->docauditno)
                        ->update([
                            'amount' => $amount,
                            'outamount' => 0,
                        ]);

                    echo nl2br("$i. update apacthdr: $obj->docauditno \n");
                    $i++;

                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_glmasdtl(Request $request){
        DB::beginTransaction();
        try {
            
            $glmasdtl = DB::table('finance.glmasdtl')
                            ->where('compcode','9B')
                            ->get();

            $i = 1;
            foreach ($glmasdtl as $obj) {
                $db = DB::table('finance.gltran')
                            ->where('compcode','9B')
                            ->where('year','2024')
                            ->where('period','10')
                            ->where('drcostcode',$obj->costcode)
                            ->where('dracc',$obj->glaccount)
                            ->sum('amount');

                $cr = DB::table('finance.gltran')
                            ->where('compcode','9B')
                            ->where('year','2024')
                            ->where('period','10')
                            ->where('crcostcode',$obj->costcode)
                            ->where('cracc',$obj->glaccount)
                            ->sum('amount');

                $tot = $db-$cr;


                DB::table('finance.glmasdtl')
                            ->where('compcode','9B')
                            ->where('costcode',$obj->costcode)
                            ->where('glaccount',$obj->glaccount)
                            ->where('year','2024')
                            ->update([
                                'actamount10' => $tot
                            ]);

            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_queuepr(Request $request){
        DB::beginTransaction();
        try {
            
            $purordhd = DB::table('material.purreqhd')
                            ->where('recstatus','PREPARED')
                            ->orWhere('recstatus','SUPPORT')
                            ->orWhere('recstatus','VERIFIED')
                            ->get();

            $i = 1;
            foreach ($purordhd as $obj) {

                if($obj->recstatus == 'PREPARED'){
                    $trantype = 'SUPPORT';
                }else if($obj->recstatus == 'SUPPORT'){
                    $trantype = 'VERIFIED';
                }else{
                    $trantype = 'APPROVED';
                }

                DB::table('material.queuepr')
                    ->insert([
                        'compcode' => '9A',
                        // 'idno' => $obj->,
                        'recno' => $obj->recno,
                        'AuthorisedID' => 'SYSTEM',
                        'deptcode' => $obj->reqdept,
                        'recstatus' => $obj->recstatus,
                        'trantype' => $trantype,
                        // 'adduser' => $obj->,
                        // 'adddate' => $obj->,
                        // 'upduser' => $obj->,
                        // 'upddate' => $obj->,
                        'prtype' => $obj->prtype,
                    ]);

                echo nl2br("$i. update queuepr: $obj->recno \n");
                $i++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_stockloc(Request $request){
        DB::beginTransaction();
        try {
            
            $stockloc = DB::table('temp.stockloc')
                            ->get();

            $i = 1;
            foreach ($stockloc as $obj) {

                // if(!$product->exists()){
                //     dump('Product doesnt exists: '.$obj->itemcode.' , '.$obj->uomcode);
                // }

                $stockloc = DB::table('material.stockloc')
                                ->where('compcode','9B')
                                ->where('deptcode','FKWSTR')
                                ->where('unit',"W'HOUSE")
                                ->where('itemcode',$obj->itemcode);
                                // ->where('uomcode',$obj->uomcode);

                if($stockloc->exists()){
                    DB::table('material.stockloc')
                                ->where('compcode','9B')
                                ->where('deptcode','FKWSTR')
                                ->where('unit',"W'HOUSE")
                                ->where('itemcode',$obj->itemcode)
                                // ->where('uomcode',$obj->uomcode)
                                ->update([
                                    'uomcode' => $obj->uomcode,
                                    'netmvqty1' => 0,
                                    'netmvqty2' => 0,
                                    'netmvqty3' => 0,
                                    'netmvqty4' => 0,
                                    'netmvqty5' => 0,
                                    'netmvqty6' => 0,
                                    'netmvqty7' => 0,
                                    'netmvqty8' => 0,
                                    'netmvqty9' => $obj->netmvqty9,
                                    'netmvval1' => 0,
                                    'netmvval2' => 0,
                                    'netmvval3' => 0,
                                    'netmvval4' => 0,
                                    'netmvval5' => 0,
                                    'netmvval6' => 0,
                                    'netmvval7' => 0,
                                    'netmvval8' => 0,
                                    'netmvval9' => $obj->netmvval9,
                                    'qtyonhand' => $obj->qtyonhand,
                                ]);

                }else{
                    DB::table('material.stockloc')
                                ->insert([
                                    'compcode' => '9B',
                                    'deptcode' => 'FKWSTR',
                                    'itemcode' => $obj->itemcode,
                                    'uomcode' => $obj->uomcode,
                                    'bincode' => $obj->bincode,
                                    'rackno' => $obj->rackno,
                                    'year' => $obj->year,
                                    'openbalqty' => 0,
                                    'openbalval' => 0,
                                    'netmvqty1' => 0,
                                    'netmvqty2' => 0,
                                    'netmvqty3' => 0,
                                    'netmvqty4' => 0,
                                    'netmvqty5' => 0,
                                    'netmvqty6' => 0,
                                    'netmvqty7' => 0,
                                    'netmvqty8' => 0,
                                    'netmvqty9' => $obj->netmvqty9,
                                    'netmvqty10' => 0,
                                    'netmvqty11' => 0,
                                    'netmvqty12' => 0,
                                    'netmvval1' => 0,
                                    'netmvval2' => 0,
                                    'netmvval3' => 0,
                                    'netmvval4' => 0,
                                    'netmvval5' => 0,
                                    'netmvval6' => 0,
                                    'netmvval7' => 0,
                                    'netmvval8' => 0,
                                    'netmvval9' => $obj->netmvval9,
                                    'netmvval10' => 0,
                                    'netmvval11' => 0,
                                    'netmvval12' => 0,
                                    'stocktxntype' => $obj->stocktxntype,
                                    'disptype' => $obj->disptype,
                                    'qtyonhand' => $obj->qtyonhand,
                                    'minqty' => $obj->minqty,
                                    'maxqty' => $obj->maxqty,
                                    'reordlevel' => $obj->reordlevel,
                                    'reordqty' => $obj->reordqty,
                                    'lastissdate' => $obj->lastissdate,
                                    'recstatus' => 'ACTIVE',
                                    'unit' => "W'HOUSE",
                                ]);

                }

                echo nl2br("$i. update stockexp: $obj->qtyonhand \n");
                $i++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_rakanniaga(Request $request){
        DB::beginTransaction();
        try {
            
            $rakanniaga = DB::table('temp.rakanniaga')
                            ->get();

            $i = 1;
            foreach ($rakanniaga as $obj) {
                $chgprice = DB::table('temp.chgprice')
                                ->where('compcode','9B')
                                ->where('chgcode',$obj->itemcode)
                                ->orderBy('effdate','desc');

                if($chgprice->exists()){
                    $chgprice = $chgprice->first();
                    DB::table('temp.chgprice')
                        ->where('idno',$chgprice->idno)
                        ->update([
                            'amt1' => $obj->price,
                            'amt2' => $obj->price
                        ]);

                    echo nl2br("$i. update chgprice price: $obj->price \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_billsum(Request $request){
        DB::beginTransaction();
        try {
            
            $billsum = DB::table('temp.billsum')
                            ->where('compcode','9B')
                            ->where('recstatus','OPEN')
                            ->get();

            $i = 1;
            foreach ($billsum as $obj) {
                $rakanniaga = DB::table('temp.rakanniaga')
                                ->where('itemcode',$obj->chggroup);

                if($rakanniaga->exists()){
                    $rakanniaga = $rakanniaga->first();
                    DB::table('temp.billsum')
                        ->where('idno',$obj->idno)
                        ->update([
                            'unitprice' => $rakanniaga->price,
                            'amount' => $rakanniaga->price * $obj->quantity,
                            'outamt' => $rakanniaga->price * $obj->quantity,
                            'totamount' => $rakanniaga->price * $obj->quantity
                        ]);

                    echo nl2br("$i. update billsum netprice: $rakanniaga->price \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb(Request $request){
        DB::beginTransaction();
        try {
            
            $salehdr = DB::table('temp.salehdr')
                            ->where('compcode','9B')
                            ->where('unit',"W'HOUSE")
                            ->where('recstatus','!=','COMPLETED')
                            ->get();

            $i = 1;
            foreach ($salehdr as $obj) {
                $sum = DB::table('temp.salesum')
                                ->where('auditno',$obj->auditno)
                                ->where('recstatus','!=','DELETE')
                                ->sum('totamount');

                // if($chgprice->exists()){
                    // $chgprice = $chgprice->first();
                    DB::table('temp.salehdr')
                        ->where('idno',$obj->idno)
                        ->update([
                            'amount' => $sum,
                            'outamount' => $sum
                        ]);

                    echo nl2br("$i. update salehdr amount: $sum \n");
                    $i++;
                // }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkandb_product_chgflg(Request $request){
        DB::beginTransaction();
        try {
            
            $product = DB::table('temp.product')
                            ->where('compcode','9B')
                            ->get();

            $i = 1;
            foreach ($product as $obj) {
                $chgmast = DB::table('temp.chgmast')
                                ->where('compcode','9B')
                                ->where('chgcode',$obj->itemcode);

                if($chgmast->exists()){
                    DB::table('temp.product')
                        ->where('idno',$obj->idno)
                        ->update([
                            'chgflag' => 1
                        ]);

                    echo nl2br("$i. chgflg: $obj->itemcode \n");
                    $i++;
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function tukar_uom(Request $request){
        DB::beginTransaction();
        try {

            $itemcode = $request->itemcode;
            $uomcode = $request->uomcode;

            if(empty($itemcode) || empty($uomcode)){
                dd('try again');
            }
            
            DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('hisdb.chgmast')
                            ->where('compcode','9B')
                            ->where('chgcode',$itemcode)
                            ->update([
                                'uom' => $uomcode
                            ]);
            
            DB::table('hisdb.chgprice')
                            ->where('compcode','9B')
                            ->where('chgcode',$itemcode)
                            ->update([
                                'uom' => $uomcode
                            ]);
            
            DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('material.stockexp')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('finance.salesum')
                            ->where('compcode','9B')
                            ->where('chggroup',$itemcode)
                            ->update([
                                'uom' => $uomcode,
                                'uom_recv' => $uomcode
                            ]);
            
            DB::table('material.ivtmpdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('material.delorddt')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('material.purorddt')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('material.ivreqdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'uomcode' => $uomcode,
                                'pouom' => $uomcode
                            ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkan_ivtxndt(Request $request){
        DB::beginTransaction();
        try {

            $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->whereDate('adddate','<=','2024-09-30')
                            ->get();

            $ivtxndt_ = $ivtxndt->unique('itemcode');

            foreach ($ivtxndt_ as $obj) {
                $itemcode=$obj->itemcode;
                $deptcode=$obj->deptcode;
                $uomcode=$obj->uomcode;

                $stockloc = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('deptcode',$deptcode)
                            ->where('itemcode',$itemcode);

                $sumqty_plus_1 = DB::table('material.ivtxndt')
                                ->where('compcode','9B')
                                ->where('trantype','GRN')
                                ->whereDate('adddate','<=','2024-09-30')
                                ->where('itemcode',$itemcode)
                                ->sum('txnqty');

                $sumqty_plus_2 = DB::table('material.ivtxndt')
                                ->where('compcode','9B')
                                ->where('trantype','TUI')
                                ->whereDate('adddate','<=','2024-09-30')
                                ->where('itemcode',$itemcode)
                                ->sum('txnqty');

                $sumqty_minus_1 = DB::table('material.ivtxndt')
                                ->where('compcode','9B')
                                ->where('trantype','TUO')
                                ->whereDate('adddate','<=','2024-09-30')
                                ->where('itemcode',$itemcode)
                                ->sum('txnqty');

                $sumqty_minus_2 = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->whereDate('adddate','<=','2024-09-30')
                            ->where('itemcode',$itemcode)
                            ->sum('txnqty');

                $sumqty = $sumqty_plus_1 + $sumqty_plus_2 - $sumqty_minus_1 - $sumqty_minus_2;

                if($stockloc->exists()){
                    DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->where('deptcode',$deptcode)
                            ->update([
                                'netmvqty9' => $sumqty
                            ]);

                    $stockloc = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('deptcode',$deptcode)
                            ->where('itemcode',$itemcode)
                            ->first();

                    $qtyonhand = floatval($stockloc->netmvqty9) + floatval($stockloc->netmvqty10) + floatval($stockloc->netmvqty11);

                    DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->where('deptcode',$deptcode)
                            ->update([
                                'qtyonhand' => $qtyonhand
                            ]);

                    DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->update([
                                'qtyonhand' => $qtyonhand
                            ]);

                    $sum_stockexp = DB::table('material.stockexp')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->sum('balqty');

                    $baki = $qtyonhand - $sum_stockexp;
                    if($baki == 0){
                        continue;
                    }else if($baki > 0){
                        $stockexp_f = DB::table('material.stockexp')
                            ->where('compcode','9B')
                            ->where('itemcode',$itemcode)
                            ->first();

                        DB::table('material.stockexp')
                                ->where('compcode','9B')
                                ->where('itemcode',$itemcode)
                                ->where('idno',$stockexp_f->idno)
                                ->update([
                                    'balqty' => $baki + $stockexp_f->balqty,
                                ]);

                    }else if($baki < 0){
                        $baki = $baki * -1;

                        $stockexp = DB::table('material.stockexp')
                                ->where('compcode','9B')
                                ->where('itemcode',$itemcode)
                                ->get();

                        foreach ($stockexp as $obj) {
                            $baki = $baki - $obj->balqty;
                            if($baki = 0){
                                DB::table('material.stockexp')
                                    ->where('idno',$obj->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$itemcode)
                                    ->update([
                                        'balqty' => 0
                                    ]);

                                continue;
                            }else if($baki > 0){
                                DB::table('material.stockexp')
                                    ->where('idno',$obj->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$itemcode)
                                    ->update([
                                        'balqty' => 0
                                    ]);
                            }else if($baki < 0){
                                DB::table('material.stockexp')
                                    ->where('idno',$obj->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$itemcode)
                                    ->update([
                                        'balqty' => $baki*-1
                                    ]);
                                continue;
                            }
                        }
                    }
                }

            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkan_tick(Request $request){
        DB::beginTransaction();
        try {
                $product = DB::table('material.product')
                                ->where('compcode','9B');

                foreach ($product as $obj) {
                    
                }
           

            // DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function tambah_apacthdr(){
        DB::beginTransaction();
        try {
                $apactdtl = DB::table('finance.apactdtl')
                                ->where('compcode','9B')
                                ->whereNotNull('document')
                                ->whereDate('addDate','<=','2024-10-14')
                                ->get();
                $x=1;
                foreach ($apactdtl as $obj) {
                    $apacthdr = DB::table('finance.apacthdr')
                                ->where('compcode','9B')
                                // ->where('document',$obj->document)
                                ->where('auditno',$obj->auditno);

                    if(!$apacthdr->exists()){
                        $gltran = DB::table('finance.gltran')
                                ->where('compcode','9B')
                                ->where('auditno',$obj->auditno);

                        if($gltran->exists()){
                            $x++;
                            $gltran = $gltran->first();

                            $pos = strpos($gltran->description, '</br>');
                            $suppcode = substr($gltran->description,0,$pos);
                            dump($x.'-'.$obj->auditno.' deptcode '.$obj->deptcode.' suppcode '.$suppcode);

                            DB::table('finance.apacthdr')
                                ->insert([
                                    'compcode' => '9B',
                                    'source' => 'AP',
                                    'trantype' => 'IN',
                                    'doctype' => 'Supplier',
                                    'auditno' => $obj->auditno,
                                    'document' => $obj->document,
                                    'suppcode' => $suppcode,
                                    'payto' => $suppcode,
                                    'suppgroup' => 'TR',
                                    // 'bankcode' => $obj->,
                                    // 'paymode' => $obj->,
                                    // 'cheqno' => $obj->,
                                    // 'cheqdate' => $obj->,
                                    'actdate' => $obj->adddate,
                                    'recdate' => $obj->adddate,
                                    'category' => '50030519',
                                    'amount' => $gltran->amount,
                                    'outamount' => $gltran->amount,
                                    // 'remarks' => $obj->remarks,
                                    // 'postflag' => $obj->,
                                    // 'doctorflag' => $obj->,
                                    // 'stat' => $obj->,
                                    // 'entryuser' => $obj->,
                                    // 'entrytime' => $obj->,
                                    'upduser' => $obj->adduser,
                                    'upddate' => $obj->adddate,
                                    // 'conversion' => $obj->,
                                    // 'srcfrom' => $obj->,
                                    // 'srcto' => $obj->,
                                    'deptcode' => $obj->deptcode,
                                    // 'reconflg' => $obj->,
                                    // 'effectdatefr' => $obj->,
                                    // 'effectdateto' => $obj->,
                                    // 'frequency' => $obj->,
                                    // 'refsource' => $obj->,
                                    // 'reftrantype' => $obj->,
                                    // 'refauditno' => $obj->,
                                    // 'pvno' => $obj->,
                                    // 'entrydate' => $obj->,
                                    'recstatus' => 'POSTED',
                                    'adduser' => $obj->adduser,
                                    'adddate' => $obj->adddate,
                                    // 'reference' => $obj->,
                                    // 'TaxClaimable' => $obj->,
                                    'unit' => $obj->unit,
                                    // 'allocdate' => $obj->,
                                    'postuser' => $obj->adduser,
                                    'postdate' => $obj->adddate,
                                    'unallocated' => 1,
                                    'requestby' => 'SYSTEM',
                                    'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    // 'request_remark' => $obj->,
                                    // 'supportby' => $obj->,
                                    // 'supportdate' => $obj->,
                                    // 'support_remark' => $obj->,
                                    // 'verifiedby' => $obj->,
                                    // 'verifieddate' => $obj->,
                                    // 'verified_remark' => $obj->,
                                    // 'approvedby' => $obj->,
                                    // 'approveddate' => $obj->,
                                    // 'approved_remark' => $obj->,
                                    // 'cancelby' => $obj->,
                                    // 'canceldate' => $obj->,
                                    // 'cancelled_remark' => $obj->,
                                    // 'bankaccno' => $obj->,
                                ]);
                        }
                    }
                }   
        
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkan_apacthdr(){
        DB::beginTransaction();
        try {
                $apactdtl = DB::table('finance.apactdtl')
                                ->where('compcode','9B')
                                ->whereIn('document',['1186010455'])
                                // ->whereDate('addDate','<=','2024-10-14')
                                ->get();
                $x=1;
                foreach ($apactdtl as $obj) {
                    $apacthdr = DB::table('finance.apacthdr')
                                ->where('compcode','9B')
                                // ->where('document',$obj->document)
                                ->where('auditno',$obj->auditno);

                    if(!$apacthdr->exists()){
                        $gltran = DB::table('finance.gltran')
                                ->where('compcode','9B')
                                ->where('auditno',$obj->auditno);

                        if(!$gltran->exists()){
                            $x++;
                            $dohd = DB::table('material.delordhd')
                                ->where('compcode','9B')
                                ->where('invoiceno',$obj->document)
                                ->first();

                            $suppcode = $dohd->suppcode;

                            DB::table('finance.apacthdr')
                                ->insert([
                                    'compcode' => '9B',
                                    'source' => 'AP',
                                    'trantype' => 'IN',
                                    'doctype' => 'Supplier',
                                    'auditno' => $obj->auditno,
                                    'document' => $obj->document,
                                    'suppcode' => $suppcode,
                                    'payto' => $suppcode,
                                    'suppgroup' => 'TR',
                                    // 'bankcode' => $obj->,
                                    // 'paymode' => $obj->,
                                    // 'cheqno' => $obj->,
                                    // 'cheqdate' => $obj->,
                                    'actdate' => $obj->adddate,
                                    'recdate' => $obj->adddate,
                                    'category' => '50030519',
                                    'amount' => $obj->amount,
                                    'outamount' => $obj->amount,
                                    // 'remarks' => $obj->remarks,
                                    // 'postflag' => $obj->,
                                    // 'doctorflag' => $obj->,
                                    // 'stat' => $obj->,
                                    // 'entryuser' => $obj->,
                                    // 'entrytime' => $obj->,
                                    'upduser' => $obj->adduser,
                                    'upddate' => $obj->adddate,
                                    // 'conversion' => $obj->,
                                    // 'srcfrom' => $obj->,
                                    // 'srcto' => $obj->,
                                    'deptcode' => $obj->deptcode,
                                    // 'reconflg' => $obj->,
                                    // 'effectdatefr' => $obj->,
                                    // 'effectdateto' => $obj->,
                                    // 'frequency' => $obj->,
                                    // 'refsource' => $obj->,
                                    // 'reftrantype' => $obj->,
                                    // 'refauditno' => $obj->,
                                    // 'pvno' => $obj->,
                                    // 'entrydate' => $obj->,
                                    'recstatus' => 'POSTED',
                                    'adduser' => $obj->adduser,
                                    'adddate' => $obj->adddate,
                                    // 'reference' => $obj->,
                                    // 'TaxClaimable' => $obj->,
                                    'unit' => $obj->unit,
                                    // 'allocdate' => $obj->,
                                    'postuser' => $obj->adduser,
                                    'postdate' => $obj->adddate,
                                    'unallocated' => 1,
                                    'requestby' => 'SYSTEM',
                                    'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    // 'request_remark' => $obj->,
                                    // 'supportby' => $obj->,
                                    // 'supportdate' => $obj->,
                                    // 'support_remark' => $obj->,
                                    // 'verifiedby' => $obj->,
                                    // 'verifieddate' => $obj->,
                                    // 'verified_remark' => $obj->,
                                    // 'approvedby' => $obj->,
                                    // 'approveddate' => $obj->,
                                    // 'approved_remark' => $obj->,
                                    // 'cancelby' => $obj->,
                                    // 'canceldate' => $obj->,
                                    // 'cancelled_remark' => $obj->,
                                    // 'bankaccno' => $obj->,
                                ]);

                            $debit_obj = $this->gltran_fromdept($obj->deptcode,'50030519');
                            $credit_obj = $this->gltran_fromsupp($suppcode);
                            $yearperiod = $this->getyearperiod(Carbon::parse($obj->adddate)->format('Y-m-d'));
                            
                            DB::table('finance.gltran')
                                ->insert([
                                    'compcode' => '9B',
                                    'adduser' => $obj->adduser,
                                    'adddate' => $obj->adddate,
                                    'auditno' => $obj->auditno,
                                    'lineno_' => 1,
                                    'source' => 'AP',
                                    'trantype' => 'IN',
                                    'reference' => $obj->document,
                                    'postdate' => $obj->adddate,
                                    'description' => $suppcode, //suppliercode + suppliername
                                    'postdate' => $obj->adddate,
                                    'year' => $yearperiod->year,
                                    'period' => $yearperiod->period,
                                    'drcostcode' => $debit_obj->drcostcode,
                                    'dracc' => $debit_obj->draccno,
                                    'crcostcode' => $credit_obj->costcode,
                                    'cracc' => $credit_obj->glaccno,
                                    'amount' => $obj->amount,
                                    'idno' => null
                                ]);

                                $this->init_glmastdtl(
                                    $debit_obj->drcostcode,//drcostcode
                                    $debit_obj->draccno,//dracc
                                    $credit_obj->costcode,//crcostcode
                                    $credit_obj->glaccno,//cracc
                                    $yearperiod,
                                    $obj->amount
                                );
                        }
                    }
                }   
        
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function gltran_fromsupp($suppcode){

        $obj = DB::table("material.supplier")
                ->select('costcode','glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();

        return $obj;
    }

    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcc,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcc)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function msdemo_chgprice(){
        $chgmast = DB::table('hisdb.chgmast')
                ->where('compcode','9A')
                ->whereIn('chggroup',['30','39','38','35'])
                ->get();

        foreach ($chgmast as $obj) {
            $obj2 = DB::table('test.chgprice')
                ->where('compcode','9A')
                ->where('chgcode',$obj->chgcode);

            if($obj2->exists()){
                $obj2 = $obj2->first();

                DB::table('hisdb.chgprice')
                    ->insert([
                        'lineno_' => $obj2->lineno_,
                        'compcode' => '9B',
                        'chgcode' => $obj2->chgcode,
                        'uom' => $obj2->uom,
                        'effdate' => '2022-07-01',
                        'minamt' => $obj2->minamt,
                        'amt1' => $obj2->amt1,
                        'amt2' => $obj2->amt2,
                        'amt3' => $obj2->amt3,
                        'iptax' => $obj2->iptax,
                        'optax' => $obj2->optax,
                        'maxamt' => $obj2->maxamt,
                        'costprice' => $obj2->costprice,
                        'lastuser' => $obj2->lastuser,
                        'lastupdate' => $obj2->lastupdate,
                        'lastfield' => $obj2->lastfield,
                        'unit' => $obj2->unit,
                        'adduser' => $obj2->adduser,
                        'adddate' => $obj2->adddate,
                        'autopull' => $obj2->autopull,
                        'addchg' => $obj2->addchg,
                        'pkgstatus' => $obj2->pkgstatus,
                        'recstatus' => $obj2->recstatus,
                        'deluser' => $obj2->deluser,
                        'deldate' => $obj2->deldate,
                        'lastcomputerid' => $obj2->lastcomputerid,
                        'lastipaddress' => $obj2->lastipaddress,
                    ]);
            }
        }
    }


    public function betulkan_uom_kh_product(){
        $chgmast = DB::table('hisdb.chgmast')
                    ->where('compcode','9B')
                    ->where('unit','KHEALTH')
                    ->get();

        foreach ($chgmast as $obj_cm) {
            $product = DB::table('material.product')
                            // ->where('unit','KHEALTH')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj_cm->chgcode)
                            ->where('uomcode','!=',$obj_cm->uom);

            if($product->exists()){
                $product = $product->first();
                dump($product->itemcode);

                DB::table('material.product')
                    ->where('idno',$product->idno)
                    ->update([
                        'unit' => 'KHEALTH',
                        'uomcode' => $obj_cm->uom
                    ]);
            }
        }
    }

    public function betulkan_uom_kh_stockloc(){
        $chgmast = DB::table('hisdb.chgmast')
                    ->where('compcode','9B')
                    ->where('unit','KHEALTH')
                    ->get();

        foreach ($chgmast as $obj_cm) {
            $stockloc = DB::table('material.stockloc')
                            // ->where('unit','KHEALTH')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj_cm->chgcode)
                            ->where('uomcode','!=',$obj_cm->uom);

            if($stockloc->exists()){
                $stockloc = $stockloc->first();
                dump($stockloc->itemcode);
                DB::table('material.stockloc')
                    ->where('idno',$stockloc->idno)
                    ->update([
                        'unit' => 'KHEALTH',
                        'uomcode' => $obj_cm->uom
                    ]);
            }
        }
    }

    public function betulkan_uom_kh_stockexp(){
        $stockloc = DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('unit','KHEALTH')
                    ->get();

        foreach ($stockloc as $obj_cm) {
            $stockexp = DB::table('material.stockexp')
                            // ->where('unit','KHEALTH')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj_cm->itemcode)
                            ->where('uomcode','!=',$obj_cm->uomcode);
                            
            if($stockexp->exists()){
                $stockexp = $stockexp->first();
                dump($stockexp->itemcode);
                DB::table('material.stockexp')
                    ->where('idno',$stockexp->idno)
                    ->update([
                        'unit' => 'KHEALTH',
                        'uomcode' => $obj_cm->uomcode
                    ]);
            }
        }
    }

    public function test_barcode(){
        return view('test.test');
    }

    public function betulkan_quotation(){
        $salehdr = DB::table('finance.salehdr')
                    ->where('compcode','9B')
                    ->get();

        foreach ($salehdr as $obj) {
            if($obj->amount != $obj->outamount){
                DB::table('finance.salehdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$obj->idno)
                        ->update([
                            'outamount' => $obj->amount,
                        ]);
            }
        }
    }

    public function tunjuk_doctorcode(){
        $chgmast = DB::table('hisdb.chgmast as c')
                            ->where('c.compcode',session('compcode'))
                            ->where('c.chggroup','DF')
                            ->whereNotNull('c.costcode')
                            // ->limit(1000)
                            ->get();

        $show= [];
        foreach ($chgmast as $obj) {
            $doctor = DB::table('hisdb.doctor')
                            ->where('compcode',session('compcode'))
                            ->where('doctorcode',$obj->costcode);

            if(!$doctor->exists()){
                array_push($show, $obj->costcode.' , '.$obj->brandname);
            }

        }

        $unique = array_unique($show);

        dd($unique);

    }

    public function set_drcontrib(){
        DB::beginTransaction();

        try {
            $doctor = DB::table('test.chgmast')
                        ->where('compcode','9B')
                        ->get();

            foreach ($doctor as $key => $value) {
                $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode','9B')
                                ->where('costcode',$value->doctorcode);

                if(!$chgmast->exists()){
                    DB::table('hisdb.doctor')
                        ->where('idno',$value->idno)
                        ->where('compcode','9B')
                        ->update([
                            'compcode' => 'XX'
                        ]);

                    dump('DEL doctor -> '.$value->doctorcode);
                }

                // foreach ($chgmast as $key2 => $value2) {
                //     DB::table('debtor.drcontrib')
                //         ->insert([
                //             'lineno_' => 1,
                //             'compcode' => '9B',
                //             'drcode' => $value->doctorcode,
                //             'chgcode' => $value2->chgcode,
                //             'effdate' => '2024-01-01',
                //             'drprcnt' => 90,
                //             'amount' => 0,
                //             'epistype' => 'OP',
                //             'stfamount' => 90,
                //             'stfpercent' => 0,
                //             'corpprcnt' => 0,
                //             'corpamt' => 0,
                //             'unit' => session('unit')
                //         ]);

                //     DB::table('debtor.drcontrib')
                //         ->insert([
                //             'lineno_' => 1,
                //             'compcode' => '9B',
                //             'drcode' => $value->doctorcode,
                //             'chgcode' => $value2->chgcode,
                //             'effdate' => '2024-01-01',
                //             'drprcnt' => 90,
                //             'amount' => 0,
                //             'epistype' => 'IP',
                //             'stfamount' => 90,
                //             'stfpercent' => 0,
                //             'corpprcnt' => 0,
                //             'corpamt' => 0,
                //             'unit' => session('unit')
                //         ]);

                //     dump('Added: Doctor: '.$value->doctorcode.' --> chgcode: '.$value2->chgcode);
                // }
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }

    public function add_radiology(){
        $chgmast = DB::table('test.chgmast')
            ->where('chggroup','00')
            ->get();

        foreach ($chgmast as $key => $value) {
             DB::table('temp.chgmast')
                    ->insert([
                        'compcode' => '9B',
                        'unit' => "W'HOUSE",
                        'chgcode' => $value->chgcode,
                        'description' => $value->description,
                        'brandname' => $value->brandname,
                        'revcode' => $value->revcode,
                        'uom' => $value->uom,
                        'packqty' => $value->packqty,
                        'invflag' => $value->invflag,
                        'overwrite' => $value->overwrite,
                        'buom' => $value->buom,
                        'adduser' => $value->adduser,
                        'adddate' => $value->adddate,
                        'lastuser' => $value->lastuser,
                        'lastupdate' => $value->lastupdate,
                        'upduser' => $value->upduser,
                        'upddate' => $value->upddate,
                        'deluser' => $value->deluser,
                        'deldate' => $value->deldate,
                        'recstatus' => $value->recstatus,
                        'lastfield' => $value->lastfield,
                        'doctorstat' => $value->doctorstat,
                        'chgtype' => $value->chgtype,
                        'chggroup' => $value->chggroup,
                        'qflag' => $value->qflag,
                        'costcode' => $value->costcode,
                        'chgflag' => $value->chgflag,
                        'ipacccode' => $value->ipacccode,
                        'opacccode' => $value->opacccode,
                        'revdept' => $value->revdept,
                        'chgclass' => $value->chgclass,
                        'costdept' => $value->costdept,
                        'invgroup' => $value->invgroup,
                        'apprccode' => $value->apprccode,
                        'appracct' => $value->appracct,
                        'active' => $value->active,
                        'constype' => $value->constype,
                        'dosage' => $value->dosage,
                        'druggrcode' => $value->druggrcode,
                        'subgroup' => $value->subgroup,
                        'stockcode' => $value->stockcode,
                        'seqno' => $value->seqno,
                        'instruction' => $value->instruction,
                        'freqcode' => $value->freqcode,
                        'durationcode' => $value->durationcode,
                        'strength' => $value->strength,
                        'durqty' => $value->durqty,
                        'freqqty' => $value->freqqty,
                        'doseqty' => $value->doseqty,
                        'dosecode' => $value->dosecode,
                        'barcode' => $value->barcode,
                        'computerid' => $value->computerid,
                        'ipaddress' => $value->ipaddress,
                        'lastcomputerid' => $value->lastcomputerid,
                        'lastipaddress' => $value->lastipaddress,
                        'auto' => $value->auto,
                        'micerra' => $value->micerra,
                    ]);
        }
    }

    public function add_radiology2(){
        $chgprice = DB::table('test.chgprice')
            ->where('compcode','11A')
            ->where('chgcode','like','10%')
            ->whereNull('lineno_')
            ->get();

        foreach ($chgprice as $key => $value) {
             DB::table('temp.chgprice')
                    ->insert([
                        'lineno_' => $value->lineno_,
                        'compcode' => '9B',
                        'chgcode' => $value->chgcode,
                        'uom' => $value->uom,
                        'effdate' => $value->effdate,
                        'minamt' => $value->minamt,
                        'amt1' => $value->amt1,
                        'amt2' => $value->amt2,
                        'amt3' => $value->amt3,
                        'iptax' => $value->iptax,
                        'optax' => $value->optax,
                        'maxamt' => $value->maxamt,
                        'costprice' => $value->costprice,
                        'lastuser' => $value->lastuser,
                        'lastupdate' => $value->lastupdate,
                        'lastfield' => $value->lastfield,
                        'unit' => "W'HOUSE",
                        'adduser' => $value->adduser,
                        'adddate' => $value->adddate,
                        'autopull' => $value->autopull,
                        'addchg' => $value->addchg,
                        'pkgstatus' => $value->pkgstatus,
                        'recstatus' => $value->recstatus,
                        'deluser' => $value->deluser,
                        'deldate' => $value->deldate,
                        'lastcomputerid' => $value->lastcomputerid,
                        'lastipaddress' => $value->lastipaddress
                    ]);
        }
    }

    public function betulkan_blk_billsum(){
        $mrn=$request->mrn;
        $episno=$request->episno;

        $chargetrx = DB::table('hisdb.chargetrx')
            ->where('compcode','9B')
            ->where('mrn',$mrn)
            ->where('episno',$episno)
            ->get();

        foreach ($chargetrx as $key => $value) {
             DB::table('temp.chgprice')
                    ->insert([
                        'lineno_' => $value->lineno_,
                        'compcode' => '9B',
                        'chgcode' => $value->chgcode,
                        'uom' => $value->uom,
                        'effdate' => $value->effdate,
                        'minamt' => $value->minamt,
                        'amt1' => $value->amt1,
                        'amt2' => $value->amt2,
                        'amt3' => $value->amt3,
                        'iptax' => $value->iptax,
                        'optax' => $value->optax,
                        'maxamt' => $value->maxamt,
                        'costprice' => $value->costprice,
                        'lastuser' => $value->lastuser,
                        'lastupdate' => $value->lastupdate,
                        'lastfield' => $value->lastfield,
                        'unit' => "W'HOUSE",
                        'adduser' => $value->adduser,
                        'adddate' => $value->adddate,
                        'autopull' => $value->autopull,
                        'addchg' => $value->addchg,
                        'pkgstatus' => $value->pkgstatus,
                        'recstatus' => $value->recstatus,
                        'deluser' => $value->deluser,
                        'deldate' => $value->deldate,
                        'lastcomputerid' => $value->lastcomputerid,
                        'lastipaddress' => $value->lastipaddress
                    ]);
        }
    }

    public function kh_productmaster(){
        $table = DB::table('temp.productmaster')
                ->get();

        foreach ($table as $obj) {
            $search = DB::table('material.productmaster')
                    ->where('itemcode',$obj->itemcode);

            if(!$search->exists()){
                DB::table('material.productmaster')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'unit' => $obj->unit,
                        'itemcode' => $obj->itemcode,
                        'description' => $obj->description,
                        'groupcode' => $obj->groupcode,
                        'productcat' => $obj->productcat,
                        'avgcost' => $obj->avgcost,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'recstatus' => $obj->recstatus,
                        'deluser' => $obj->deluser,
                        'deldate' => $obj->deldate,
                        'Class' => $obj->Class
                    ]);
            }

        }
    }

    public function kh_product_latest(){
        $table = DB::table('temp.khealth_stocktake')
                ->get();

        foreach ($table as $index => $obj) {
            $search = DB::table('temp.chgprice')
                    ->where('chgcode',$obj->itemcode);

            if(!$search->exists()){
                dump('insert -> '.$obj->itemcode);

            }else{
                $cont = $index+1;
                dump($cont.'. update -> '.$obj->itemcode);
                $search = $search->first();

                DB::table('temp.chgprice')
                    ->where('idno',$search->idno)
                    ->update([
                        'compcode' => '9B',
                        'unit' => 'khealth',
                        // 'qtyonhand' => $obj->qty,
                        // 'netmvqty12' => $obj->qty,
                        'uom' => $obj->uom
                    ]);
            }

        }
    }

    public function kh_chgmast(){
        $table = DB::table('temp.chgmast')
                ->get();

        foreach ($table as $obj) {
            $search = DB::table('hisdb.chgmast')
                    ->where('chgcode',$obj->chgcode);

            if(!$search->exists()){
                dump('insert -> '.$obj->chgcode);
                DB::table('hisdb.chgmast')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'unit' => $obj->unit,
                        'chgcode' => $obj->chgcode,
                        'description' => $obj->description,
                        'brandname' => $obj->brandname,
                        'revcode' => $obj->revcode,
                        'uom' => $obj->uom,
                        'packqty' => $obj->packqty,
                        'invflag' => $obj->invflag,
                        'overwrite' => $obj->overwrite,
                        'buom' => $obj->buom,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'lastuser' => $obj->lastuser,
                        'lastupdate' => $obj->lastupdate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'deluser' => $obj->deluser,
                        'deldate' => $obj->deldate,
                        'recstatus' => $obj->recstatus,
                        'lastfield' => $obj->lastfield,
                        'doctorstat' => $obj->doctorstat,
                        'chgtype' => $obj->chgtype,
                        'chggroup' => $obj->chggroup,
                        'qflag' => $obj->qflag,
                        'costcode' => $obj->costcode,
                        'chgflag' => $obj->chgflag,
                        'ipacccode' => $obj->ipacccode,
                        'opacccode' => $obj->opacccode,
                        'revdept' => $obj->revdept,
                        'chgclass' => $obj->chgclass,
                        'costdept' => $obj->costdept,
                        'invgroup' => $obj->invgroup,
                        'apprccode' => $obj->apprccode,
                        'appracct' => $obj->appracct,
                        'active' => $obj->active,
                        'constype' => $obj->constype,
                        'dosage' => $obj->dosage,
                        'druggrcode' => $obj->druggrcode,
                        'subgroup' => $obj->subgroup,
                        'stockcode' => $obj->stockcode,
                        'seqno' => $obj->seqno,
                        'instruction' => $obj->instruction,
                        'freqcode' => $obj->freqcode,
                        'durationcode' => $obj->durationcode,
                        'strength' => $obj->strength,
                        'durqty' => $obj->durqty,
                        'freqqty' => $obj->freqqty,
                        'doseqty' => $obj->doseqty,
                        'dosecode' => $obj->dosecode,
                        'barcode' => $obj->barcode,
                        'computerid' => $obj->computerid,
                        'ipaddress' => $obj->ipaddress,
                        'lastcomputerid' => $obj->lastcomputerid,
                        'lastipaddress' => $obj->lastipaddress,
                        'auto' => $obj->auto,
                        'micerra' => $obj->micerra,
                    ]);
            }

        }
    }

    public function kh_chgprice(){
        $table = DB::table('temp.chgprice')
                ->get();

        foreach ($table as $obj) {
            $search = DB::table('hisdb.chgprice')
                    ->where('chgcode',$obj->chgcode);

            if(!$search->exists()){
                dump('insert -> '.$obj->chgcode);
                $table2 = DB::table('temp.chgprice')
                            ->where('chgcode',$obj->chgcode)
                            ->get();

                foreach ($table2 as $obj2) {
                    DB::table('hisdb.chgprice')
                        ->insert([
                            'lineno_' => $obj->lineno_,
                            'compcode' => $obj->compcode,
                            'chgcode' => $obj->chgcode,
                            'uom' => $obj->uom,
                            'effdate' => $obj->effdate,
                            'minamt' => $obj->minamt,
                            'amt1' => $obj->amt1,
                            'amt2' => $obj->amt2,
                            'amt3' => $obj->amt3,
                            'iptax' => $obj->iptax,
                            'optax' => $obj->optax,
                            'maxamt' => $obj->maxamt,
                            'costprice' => $obj->costprice,
                            'lastuser' => $obj->lastuser,
                            'lastupdate' => $obj->lastupdate,
                            'lastfield' => $obj->lastfield,
                            'unit' => $obj->unit,
                            'adduser' => $obj->adduser,
                            'adddate' => $obj->adddate,
                            'autopull' => $obj->autopull,
                            'addchg' => $obj->addchg,
                            'pkgstatus' => $obj->pkgstatus,
                            'recstatus' => $obj->recstatus,
                            'deluser' => $obj->deluser,
                            'deldate' => $obj->deldate,
                            'lastcomputerid' => $obj->lastcomputerid,
                            'lastipaddress' => $obj->lastipaddress
                        ]);
                }
            }

        }
    }
    
    public function kh_stockloc(){
        $table = DB::table('temp.stockloc')
                ->get();

        foreach ($table as $obj) {
            $search = DB::table('material.stockloc')
                            ->where('itemcode',$obj->itemcode)
                            ->where('unit',"khealth");

            if($search->exists()){
                dump('update -> '.$obj->itemcode);

                $balqty = $obj->netmvqty1 + $obj->netmvqty2 + $obj->netmvqty3 + $obj->netmvqty4 + $obj->netmvqty5 + $obj->netmvqty6 + $obj->netmvqty7 + $obj->netmvqty8 + $obj->netmvqty9 + $obj->netmvqty10 + $obj->netmvqty11 + $obj->netmvqty12;

                DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('unit',"khealth")
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'qtyonhand' => $balqty,
                                'netmvqty12' => $balqty
                            ]);

                $stockexp = DB::table('material.stockexp')
                                ->where('itemcode',$obj->itemcode)
                                ->where('unit',"khealth");

                if($stockexp->exists()){
                    DB::table('material.stockexp')
                                ->where('itemcode',$obj->itemcode)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                }else{
                    DB::table('material.stockexp')
                        ->insert([
                            'deptcode' => 'KHEALTH',
                            'itemcode' => $obj->itemcode,
                            'uomcode' => $obj->uomcode,
                            'balqty' => $balqty,
                            'year' => '2024',
                            'unit' => 'KHEALTH'
                        ]);
                }

            }else{
                dump('insert -> '.$obj->itemcode);

                $balqty = $obj->netmvqty1 + $obj->netmvqty2 + $obj->netmvqty3 + $obj->netmvqty4 + $obj->netmvqty5 + $obj->netmvqty6 + $obj->netmvqty7 + $obj->netmvqty8 + $obj->netmvqty9 + $obj->netmvqty10 + $obj->netmvqty11 + $obj->netmvqty12;

                DB::table('material.stockloc')
                            ->insert([
                                'compcode' => $obj->compcode,
                                'deptcode' => $obj->deptcode,
                                'itemcode' => $obj->itemcode,
                                'uomcode' => $obj->uomcode,
                                'bincode' => $obj->bincode,
                                'rackno' => $obj->rackno,
                                'year' => $obj->year,
                                'openbalqty' => $obj->openbalqty,
                                'openbalval' => $obj->openbalval,
                                'netmvqty12' => $balqty,
                                'netmvval1' => $obj->netmvval1,
                                'netmvval2' => $obj->netmvval2,
                                'netmvval3' => $obj->netmvval3,
                                'netmvval4' => $obj->netmvval4,
                                'netmvval5' => $obj->netmvval5,
                                'netmvval6' => $obj->netmvval6,
                                'netmvval7' => $obj->netmvval7,
                                'netmvval8' => $obj->netmvval8,
                                'netmvval9' => $obj->netmvval9,
                                'netmvval10' => $obj->netmvval10,
                                'netmvval11' => $obj->netmvval11,
                                'netmvval12' => $obj->netmvval12,
                                'stocktxntype' => $obj->stocktxntype,
                                'disptype' => $obj->disptype,
                                'qtyonhand' => $balqty,
                                'minqty' => $obj->minqty,
                                'maxqty' => $obj->maxqty,
                                'reordlevel' => $obj->reordlevel,
                                'reordqty' => $obj->reordqty,
                                'lastissdate' => $obj->lastissdate,
                                'frozen' => $obj->frozen,
                                'adduser' => $obj->adduser,
                                'adddate' => $obj->adddate,
                                'upduser' => $obj->upduser,
                                'upddate' => $obj->upddate,
                                'cntdocno' => $obj->cntdocno,
                                'fix_uom' => $obj->fix_uom,
                                'locavgcs' => $obj->locavgcs,
                                'lstfrzdt' => $obj->lstfrzdt,
                                'lstfrztm' => $obj->lstfrztm,
                                'frzqty' => $obj->frzqty,
                                'recstatus' => $obj->recstatus,
                                'deluser' => $obj->deluser,
                                'deldate' => $obj->deldate,
                                'computerid' => $obj->computerid,
                                'ipaddress' => $obj->ipaddress,
                                'lastcomputerid' => $obj->lastcomputerid,
                                'lastipaddress' => $obj->lastipaddress,
                                'unit' => 'KHEALTH'
                            ]);

                $stockexp = DB::table('material.stockexp')
                                ->where('itemcode',$obj->itemcode)
                                ->where('unit',"khealth");

                if($stockexp->exists()){
                    DB::table('material.stockexp')
                                ->where('itemcode',$obj->itemcode)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                }else{
                    DB::table('material.stockexp')
                        ->insert([
                            'deptcode' => 'KHEALTH',
                            'itemcode' => $obj->itemcode,
                            'uomcode' => $obj->uomcode,
                            'balqty' => $balqty,
                            'year' => '2024',
                            'unit' => 'KHEALTH'
                        ]);
                }
            }

        }
    }

    public function stocktake_imp_header(){

        $request_no = $this->request_no('PHYCNT', 'FKWSTR');
        $recno = $this->recno('IV','PHYCNT');

        $table = DB::table("material.phycnthd");

        $array_insert = [
            'docno' => $request_no,
            'recno' => $recno,
            'srcdept' => 'FKWSTR',
            'itemfrom' => '',
            'itemto' => '',
            'frzdate' => '2024-12-16',//freeze date
            'frztime' => '11:00:00',//freeze time
            'phycntdate' => Carbon::now("Asia/Kuala_Lumpur"),
            'phycnttime' => Carbon::now("Asia/Kuala_Lumpur"),
            'respersonid' => session('username'), //freeze user
            'remarks' => '-',
            'rackno' => '',
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        $idno = $table->insertGetId($array_insert);

        print_r($idno);
    }

    public function stocktake_imp_dtl(Request $request){
        $idno=$request->idno;
        // $from=5001;
        // $to=10000;
        $table = DB::table('temp.fkwstr_stocktake')
                    ->get();

        $phycnthd =  DB::table('material.phycnthd')
                        ->where('idno',$idno)
                        ->first();

        foreach ($table as $key => $value) {
            $myitemcode=$value->itemcode;
            $myuom=$value->uom;
            $thyqty=$value->sys_qty;
            $phyqty=$value->qty;

            $stockloc = DB::table('material.product as p')
                        ->select('p.avgcost','s.qtyonhand')
                        ->join('material.stockloc as s', function($join){
                            $join = $join->on('p.itemcode', '=', 's.itemcode')
                                          ->where('s.compcode','9B')
                                          ->where('s.deptcode','FKWSTR');
                        })
                        ->where('p.itemcode',$myitemcode)
                        ->where('p.compcode','9B');

            if(!$stockloc->exists()){
                dump('stockloc takde - '.$myitemcode);
            }else{
                $stockloc = $stockloc->first();

                DB::table('material.phycntdt')
                    ->insert([
                        'compcode' => session('compcode'),
                        'srcdept' => $phycnthd->srcdept,
                        'phycntdate' => $phycnthd->phycntdate,
                        'phycnttime' => $phycnthd->phycnttime,
                        'lineno_' => $key,
                        'itemcode' => $myitemcode,
                        'uomcode' => $myuom,
                        'adduser' => 'system',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'unitcost' => $stockloc->avgcost,
                        'thyqty' => $thyqty,
                        'phyqty' => $phyqty,
                        'recno' => $phycnthd->recno,
                        // 'expdate' => $value->expdate,
                        'frzdate' => $phycnthd->frzdate,
                        'frztime' => $phycnthd->frztime,
                        // 'batchno' => $value->batchno,
                    ]);
            }
        }
    }

    // public function stocktake_imp_dtl2(){

    //     $table = DB::table('material.phycntdt')
    //                 ->where('recno',14)
    //                 ->get();

    //     foreach ($table as $value) {
    //         $product = DB::table('material.product')
    //                         ->where('compcode','9B')
    //                         ->where('itemcode',$value->itemcode)
    //                         ->where('uomcode',$value->uomcode);
    //         if(!$product->exists()){
    //             dump('not exists product -> '.$value->itemcode.' - '.$value->uomcode);
    //         }
    //     }
    // }

    public function stockloc_JTR_header(Request $request){
        $dept = $request->dept;

        if($dept == 'IMP'){
            $dept='IMP';
            $unit='IMP';
        }else if($dept == 'FKWSTR'){
            $dept='FKWSTR';
            $unit="W'HOUSE";
        }else if($dept == 'KHEALTH'){
            $dept='KHEALTH';
            $unit='KHEALTH';
        }else{
            dd('no dept');
        }

        $request_no = $this->request_no('JTR',$dept);
        $recno = $this->recno('IV','IT');

        DB::table("material.ivtxnhd")
                    ->insert([
                        'compcode' => '9B',
                        'recno' => $recno,
                        'source' => 'IV',
                        // 'reference' => ,
                        'txndept' => $dept,
                        'trantype' => 'JTR',
                        'docno' => $request_no,
                        // 'srcdocno' => ,
                        // 'sndrcvtype' => ,
                        // 'sndrcv' => ,
                        'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'datesupret' => ,
                        // 'dateactret' => ,
                        'trantime' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'ivreqno' => ,
                        // 'amount' => ,
                        // 'respersonid' => ,
                        // 'remarks' => ,
                        'recstatus' => 'POSTED',
                        'adduser' => 'system',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'upduser' => ,
                        // 'upddate' => ,
                        // 'updtime' => ,
                        // 'postedby' => 'system',
                        // 'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'unit' => $unit,
                        // 'requestby' => ,
                        // 'requestdate' => ,
                        // 'supportby' => ,
                        // 'supportdate' => ,
                        // 'support_remark' => ,
                        // 'verifiedby' => ,
                        // 'verifieddate' => ,
                        // 'verified_remark' => ,
                        // 'approvedby' => ,
                        // 'approveddate' => ,
                        // 'approved_remark' => ,
                        // 'cancelby' => ,
                        // 'canceldate' => ,
                        // 'cancelled_remark' => ,
                    ]);

        print_r($recno);
    }

    public function stockloc_JTR(Request $request){
        $dept = $request->dept;

        if($dept == 'IMP'){
            $dept='IMP';
            $unit='IMP';
        }else if($dept == 'FKWSTR'){
            $dept='FKWSTR';
            $unit="W'HOUSE";
        }else if($dept == 'KHEALTH'){
            $dept='KHEALTH';
            $unit='KHEALTH';
        }else{
            dd('no dept');
        }

        $month=$request->month;
        $year=$request->year;
        $recno=$request->recno;
        if(empty($recno) || !isset($request->recno)){
            dd('no recno');
        }
        if(empty($month) || !isset($request->month)){
            dd('no month');
        }
        if(empty($year) || !isset($request->year)){
            dd('no year');
        }

        DB::beginTransaction();

        try {

            $stockloc = DB::table('material.stockloc as s')
                            ->select('s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','p.avgcost')
                            ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode')
                                              ->where('p.avgcost','!=',0)
                                              ->where('p.compcode','9B');
                            })
                            ->where('s.compcode','9B')
                            ->where('s.deptcode',$dept)
                            ->where('s.year',$year)
                            // ->where('s.itemcode','KW001303')
                            ->get();

            $x=0;
            foreach ($stockloc as $obj) {
                $array_obj = (array)$obj;

                $get_bal = $this->get_bal($array_obj,$month);
                // dump($get_bal);
                $variance = floatval($get_bal->variance);

                if($variance != 0){
                    $x = $x + 1;
                    DB::table('material.ivtxndt')
                            ->insert([
                                'compcode' => '9B', 
                                'recno' => $recno, 
                                'lineno_' => $x, 
                                'itemcode' => $obj->itemcode, 
                                'uomcode' => $obj->uomcode,
                                // 'uomcoderecv' => $value->uomcoderecv,  
                                'txnqty' => 0, 
                                'netprice' => $variance, 
                                'adduser' => 'system', 
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                                // 'upduser' => $value->upduser, 
                                // 'upddate' => $value->upddate, 
                                'TranType' => 'JTR',
                                'deptcode'  => $dept,
                                // 'productcat' => $productcat, 
                                // 'draccno' => $draccno, 
                                // 'drccode' => $drccode, 
                                // 'craccno' => $craccno, 
                                // 'crccode' => $crccode, 
                                // 'expdate' => $value->expdate, 
                                // 'qtyonhand' => $value->qtyonhand,
                                // 'qtyonhandrecv' => $value->qtyonhandrecv,  
                                // 'batchno' => $value->batchno, 
                                // 'amount' => $value->amount, 
                                'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
                                // 'sndrcv' => $ivtmphd->sndrcv,
                                'unit' => $dept,
                            ]);

                    $NetMvVal = $array_obj['netmvval'.$month] + $variance;
                    DB::table('material.StockLoc')
                                    // ->where('StockLoc.unit','=',$unit_)
                                    ->where('StockLoc.CompCode','=',session('compcode'))
                                    ->where('StockLoc.idno','=',$obj->idno)
                                    // ->where('StockLoc.DeptCode','=',$value->srcdept)
                                    // ->where('StockLoc.ItemCode','=',$value->itemcode)
                                    // ->where('StockLoc.Year','=', defaultController::toYear($phycntdate))
                                    // ->where('StockLoc.UomCode','=',$value->uomcode)
                                    ->update([
                                        // 'QtyOnHand' => $QtyOnHand,
                                        // 'NetMvQty'.$month => $NetMvQty, 
                                        'NetMvVal'.$month => $NetMvVal
                                    ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }

    public function get_bal($array_obj,$period){
        $open_balqty = $array_obj['openbalqty'];
        $close_balqty = $array_obj['openbalqty'];
        $open_balval = $array_obj['openbalval'];
        $close_balval = $array_obj['openbalval'];
        $until = intval($period) - 1;

        for ($from = 1; $from <= $until; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = $open_balval + $array_obj['netmvval'.$from];
        }

        for ($from = 1; $from <= intval($period); $from++) { 
            $close_balqty = $close_balqty + $array_obj['netmvqty'.$from];
            $close_balval = $close_balval + $array_obj['netmvval'.$from];
        }

        $actual_balval = $array_obj['avgcost'] * $close_balqty;

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        $responce->close_balqty = $close_balqty;
        $responce->close_balval = $close_balval;
        $responce->avgcost = $array_obj['avgcost'];
        $responce->actua_balval = $actual_balval;
        $responce->variance =  - $close_balval - $actual_balval;
        return $responce;
    }

    public function betulkan_stockloc_kh(){
        $phycnt = DB::table('temp.khealth_stocktake')
                        // ->where('compcode','9B')
                        // ->where('recno','13')
                        // ->whereIn('itemcode',['2227A','2228A','7613033844713','9557327001575','9557327001582','PANTS L','PANTS M','PANTS XL','T6CFN','TENA XL'])
                        // ->where('itemcode','TRUTH')
                        // ->where('trantype','PHYCNT')
                        // ->where('deptcode','KHEALTH')
                        ->get();

        // $phycnt = $phycnt->unique('itemcode');
        $x=1;
        foreach ($phycnt as $obj) {
            if($obj->freezeqty != $obj->qty){
                dump($x.'. '.$obj->itemcode);
                $x++;

                // $phyqty = DB::table('material.phycntdt')
                //             ->where('itemcode',$obj->itemcode)
                //             ->where('compcode','9B')
                //             ->where('recno','13')
                //             ->sum('phyqty');

                // $bekap_stockloc = DB::table('bekap_material.stockloc')
                //                     ->where('itemcode',$obj->itemcode)
                //                     ->where('uomcode',$obj->uomcode)
                //                     ->where('deptcode','KHEALTH')
                //                     ->first();

                // $bekap_stockexp = DB::table('bekap_material.stockexp')
                //                     ->where('itemcode',$obj->itemcode)
                //                     ->where('uomcode',$obj->uomcode)
                //                     ->first();

                DB::table('material.product')
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->update([
                            'qtyonhand' => $obj->qty
                        ]);

                DB::table('material.stockloc')
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->where('deptcode','KHEALTH')
                        ->update([
                            'qtyonhand' => $obj->qty,
                            // 'netmvqty12' => $bekap_stockloc->netmvqty12,
                            // 'netmvval12' => $bekap_stockloc->netmvval12
                        ]);

                // DB::table('material.stockexp')
                //         ->where('itemcode',$obj->itemcode)
                //         ->where('uomcode',$obj->uomcode)
                //         ->where('expdate',Carbon::createFromFormat($obj->expdate,'d/m/Y')->format('Y-m-d'))
                //         ->where('batchno',$obj->batchno)
                //         ->update([
                //             'balqty' => $obj->qty
                //         ]);
            }
        }
    }

    public function upd_glmastdtl_1(){
        $costcode = '1007';
        $glaccount = '50020105'; // 20010025-credit

        $gltrandr = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('year','2024')
                            ->where('period','12')
                            ->where('drcostcode',$costcode)
                            ->where('dracc',$glaccount)
                            ->sum('amount');
        dump($gltrandr);

        $gltrancr = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('year','2024')
                            ->where('period','12')
                            ->where('crcostcode',$costcode)
                            ->where('cracc',$glaccount)
                            ->sum('amount');
        dump($gltrancr);

        $calc = $gltrandr - $gltrancr;

        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$costcode)
                            ->where('glaccount','=',$glaccount)
                            ->where('year','=','2024')
                            ->update([
                                // 'upduser' => session('username'),
                                // 'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount12' => $calc,
                                // 'recstatus' => 'ACTIVE'
                            ]);
    }

    public function upd_glmastdtl_2(){
        $costcode = '1007';
        $glaccount = '20010025'; // 20010025-credit

        $gltrandr = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('year','2024')
                            ->where('period','12')
                            ->where('drcostcode',$costcode)
                            ->where('dracc',$glaccount)
                            ->sum('amount');
        dump($gltrandr);

        $gltrancr = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('year','2024')
                            ->where('period','12')
                            ->where('crcostcode',$costcode)
                            ->where('cracc',$glaccount)
                            ->sum('amount');
        dump($gltrancr);

        $calc = $gltrandr - $gltrancr;

        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$costcode)
                            ->where('glaccount','=',$glaccount)
                            ->where('year','=','2024')
                            ->update([
                                // 'upduser' => session('username'),
                                // 'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount12' => $calc,
                                // 'recstatus' => 'ACTIVE'
                            ]);
    }

    public function chk_if_got(){
        $phycnt = DB::table('temp.khealth_stocktake')
                        // ->where('compcode','9B')
                        // ->where('recno','13')
                        // ->whereIn('itemcode',['2227A','2228A','7613033844713','9557327001575','9557327001582','PANTS L','PANTS M','PANTS XL','T6CFN','TENA XL'])
                        // ->where('itemcode','TRUTH')
                        // ->where('trantype','PHYCNT')
                        // ->where('deptcode','KHEALTH')
                        ->get();

        $phycnt = $phycnt->unique('itemcode');

        $x=1;
        foreach ($phycnt as $obj) {
            if($obj->freezeqty != $obj->qty){
                dump($x.'. '.$obj->itemcode);
                $x++;
                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj->itemcode)
                            ->where('adddate','>=','2024-12-01')
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->where('itemcode',$obj->itemcode)
                            ->where('trandate','>=','2024-12-01')
                            ->sum('txnqty');
                $add = $ivtxndt;

                $all = $add - $minus;

                $product = DB::table('material.product')
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode',$obj->uomcode)
                                ->first();

                $netmvqty11 = $product->qtyonhand - $all;
                // dump($product->qtyonhand);
                // dump($all);
                // dump($netmvqty11);

                DB::table('material.stockloc')
                        ->where('itemcode',$obj->itemcode)
                        ->where('deptcode','KHEALTH')
                        ->update([
                            'netmvqty1' => 0,
                            'netmvqty2' => 0,
                            'netmvqty3' => 0,
                            'netmvqty4' => 0,
                            'netmvqty5' => 0,
                            'netmvqty6' => 0,
                            'netmvqty7' => 0,
                            'netmvqty8' => 0,
                            'netmvqty9' => 0,
                            'netmvqty10' => 0,
                            'netmvval1' => 0,
                            'netmvval2' => 0,
                            'netmvval3' => 0,
                            'netmvval4' => 0,
                            'netmvval5' => 0,
                            'netmvval6' => 0,
                            'netmvval7' => 0,
                            'netmvval8' => 0,
                            'netmvval9' => 0,
                            'netmvval10' => 0,
                            'netmvval11' => $netmvqty11 * $product->avgcost,
                            'netmvqty11' => $netmvqty11
                        ]);
            }
        }
    }

    public function btlkn_productkh(){
        $phycnt = DB::table('temp.khealth_stocktake')
                        // ->where('compcode','9B')
                        // ->where('recno','13')
                        // ->whereIn('itemcode',['2227A','2228A','7613033844713','9557327001575','9557327001582','PANTS L','PANTS M','PANTS XL','T6CFN','TENA XL'])
                        // ->where('itemcode','TRUTH')
                        // ->where('trantype','PHYCNT')
                        // ->where('deptcode','KHEALTH')
                        ->get();

        // $phycnt = $phycnt->unique('itemcode');

        $x=1;
        foreach ($phycnt as $obj) {
            $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('itemcode',$obj->itemcode)
                        ->where('adddate','>=','2024-12-23')
                        ->sum('txnqty');

            if($ivdspdt > 0){
                dump($x.'-'.$obj->itemcode.' txnqty: '.$ivdspdt);

                $product = DB::table('material.product')
                                    ->where('itemcode',$obj->itemcode)
                                    ->where('uomcode',$obj->uomcode)
                                    ->first();

                $stockloc = DB::table('material.stockloc')
                                    ->where('itemcode',$obj->itemcode)
                                    ->where('uomcode',$obj->uomcode)
                                    ->where('deptcode','KHEALTH')
                                    ->first();

                $stockexp = DB::table('material.stockexp')
                                    ->where('itemcode',$obj->itemcode)
                                    ->where('uomcode',$obj->uomcode)
                                    ->first();

                DB::table('material.product')
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->update([
                            'qtyonhand' => $product->qtyonhand - $ivdspdt
                        ]);

                DB::table('material.stockloc')
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->where('deptcode','KHEALTH')
                        ->update([
                            'qtyonhand' => $stockloc->qtyonhand - $ivdspdt,
                            'netmvqty12' => $stockloc->netmvqty12 - $ivdspdt,
                        ]);

                DB::table('material.stockexp')
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->update([
                            'balqty' => $stockexp->balqty - $ivdspdt
                        ]);

                $x++;
            }
        }
    }

    public function btlkn_qtymv12(){
        $phycnt = DB::table('temp.khealth_stocktake')
                        // ->where('compcode','9B')
                        // ->where('recno','13')
                        // ->whereIn('itemcode',['2227A','2228A','7613033844713','9557327001575','9557327001582','PANTS L','PANTS M','PANTS XL','T6CFN','TENA XL'])
                        // ->where('itemcode','TRUTH')
                        // ->where('trantype','PHYCNT')
                        // ->where('deptcode','KHEALTH')
                        ->get();

        $phycnt = $phycnt->unique('itemcode');

        $x=1;
        foreach ($phycnt as $obj) {
                dump($x.'-'.$obj->itemcode);

                $product = DB::table('material.product')
                                    ->where('itemcode',$obj->itemcode)
                                    ->where('uomcode',$obj->uomcode)
                                    ->first();

                $stockloc = DB::table('material.stockloc')
                                    ->where('itemcode',$obj->itemcode)
                                    ->where('uomcode',$obj->uomcode)
                                    ->where('deptcode','KHEALTH');
                                    
                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                    $netmvqty12 = $stockloc->qtyonhand - $stockloc->netmvqty11;

                    $stockloc = DB::table('material.stockloc')
                                        ->where('itemcode',$obj->itemcode)
                                        ->where('uomcode',$obj->uomcode)
                                        ->where('deptcode','KHEALTH')
                                        ->update([
                                            'netmvqty12' => $netmvqty12
                                        ]);
                }
        }
    }

    public function betulkan_stockexp_semua_chk(Request $request){
        $unit = $request->unit;
        $deptcode = $request->deptcode;
        $year=$request->year;

        $stockexp = DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('unit',$unit)
                            // ->where('deptcode',$deptcode)
                            ->get();

        foreach ($stockexp as $key => $value) {
            $stockexp = DB::table('material.stockexp')
                            ->where('itemcode',$value->itemcode)
                            ->where('compcode','9B')
                            ->where('deptcode',$deptcode);

            $stockloc = DB::table('material.stockloc')
                            ->where('itemcode',$value->itemcode)
                            ->where('compcode','9B')
                            ->where('year',$year)
                            ->where('deptcode',$deptcode);

            if($stockloc->exists() && $stockexp->exists()){
                $balqty = $stockexp->sum('balqty');
                $qtyonhand = $stockloc->first()->qtyonhand;

                if($balqty != $qtyonhand){
                    dump($value->itemcode.' -> stockexp sum: '.$balqty.' ----- stockloc: '.$qtyonhand);
                }
            }
        }
    }

    public function betulkan_stockexp_semua(Request $request){

        $year=2025;
        $deptcode = $request->deptcode;

        if(strtoupper($deptcode) == 'IMP'){
            $deptcode='IMP';
            $unit='IMP';
        }else if(strtoupper($deptcode) == 'FKWSTR'){
            $deptcode='FKWSTR';
            $unit="W'HOUSE";
        }else if(strtoupper($deptcode) == 'KHEALTH'){
            $deptcode='KHEALTH';
            $unit='KHEALTH';
        }else{
            dd('no dept');
        }

        $stockexp = DB::table('material.product')
                            ->where('compcode','9B')
                            ->where('unit',$unit)
                            // ->where('itemcode','1416FR')
                            // ->where('deptcode',$deptcode)
                            ->get();

        $x = 1;
        foreach ($stockexp as $key => $value) {
            $stockexp = DB::table('material.stockexp')
                            ->where('itemcode',$value->itemcode)
                            ->where('compcode','9B')
                            ->where('deptcode',$deptcode);

            $stockloc = DB::table('material.stockloc')
                            ->where('itemcode',$value->itemcode)
                            ->where('compcode','9B')
                            ->where('year',$year)
                            ->where('deptcode',$deptcode);

            if($stockloc->exists() && $stockexp->exists()){
                $balqty = $stockexp->sum('balqty');
                $qtyonhand = $stockloc->first()->qtyonhand;

                if($balqty != $qtyonhand){
                    dump($x.'. '.$value->itemcode.' -> stockexp sum: '.$balqty.' ----- stockloc: '.$qtyonhand);
                    $x++;

                    //1.
                    if($qtyonhand>$balqty){
                        $var = $qtyonhand - $balqty;

                        $stockexp_chg = DB::table('material.stockexp')
                                            ->where('compcode','9B')
                                            ->where('itemcode',$value->itemcode)
                                            ->where('deptcode',$deptcode)
                                            ->orderBy('idno','desc')
                                            ->first();

                        DB::table('material.stockexp')
                                    ->where('idno',$stockexp_chg->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$value->itemcode)
                                    ->update([
                                        'balqty' => $stockexp_chg->balqty + $var
                                    ]);

                        $chg = $stockexp_chg->balqty + $var;
                        dump('change stockexp '.$value->itemcode.' to '.$chg);

                    }else if($qtyonhand<$balqty){
                        $stockexp_chg = DB::table('material.stockexp')
                                            ->where('compcode','9B')
                                            ->where('itemcode',$value->itemcode)
                                            ->where('deptcode',$deptcode)
                                            ->orderBy('idno','desc')
                                            ->get();

                        $baki = $qtyonhand;
                        $zerorise = 0;
                        foreach ($stockexp_chg as $obj) {
                            $baki = $baki - $obj->balqty;
                            if($zerorise == 1){
                                DB::table('material.stockexp')
                                    ->where('idno',$obj->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$value->itemcode)
                                    ->update([
                                        'balqty' => 0
                                    ]);
                                dump('change stockexp '.$value->itemcode.' to 0');
                            }else{
                                if($baki == 0){
                                    $zerorise = 1;
                                    // DB::table('material.stockexp')
                                    //     ->where('idno',$obj->idno)
                                    //     ->where('compcode','9B')
                                    //     ->where('itemcode',$itemcode)
                                    //     ->update([
                                    //         'balqty' => 0
                                    //     ]);

                                    // continue;
                                }else if($baki > 0){
                                    // DB::table('material.stockexp')
                                    //     ->where('idno',$obj->idno)
                                    //     ->where('compcode','9B')
                                    //     ->where('itemcode',$itemcode)
                                    //     ->update([
                                    //         'balqty' => 0
                                    //     ]);
                                }else if($baki < 0){
                                    DB::table('material.stockexp')
                                        ->where('idno',$obj->idno)
                                        ->where('compcode','9B')
                                        ->where('itemcode',$value->itemcode)
                                        ->update([
                                            'balqty' => $baki + $obj->balqty
                                        ]);
                                    $chg = $baki + $obj->balqty;
                                    dump('change stockexp '.$value->itemcode.' to '.$chg);
                                    $zerorise = 1;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function btlkn_imp_1(){
        $product = DB::table('material.product')
                        ->where('compcode','9B')
                        ->where('unit','=','IMP')
                        ->whereColumn('avgcost','!=','currprice')
                        // ->where('trantype','PHYCNT')
                        // ->where('deptcode','KHEALTH')
                        ->get();

        foreach ($product as $obj_p) {
            $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('issdept','IMP')
                        ->where('trandate','<=','2024-12-31')
                        ->where('itemcode',$obj_p->itemcode)
                        ->get();

            foreach ($ivdspdt as $obj_i) {

                $netprice = $obj_p->currprice;
                $totalcost = $netprice * $obj_i->txnqty;

                DB::table('material.ivdspdt')
                    ->where('compcode','9B')
                    ->where('idno',$obj_i->idno)
                    ->update([
                        'netprice' => $netprice,
                        'amount' => $totalcost
                    ]);
            }
        }
    }

    public function btlkn_imp_1_phycnt(Request $request){

        $phycnt = DB::table('material.ivtxndt')
                    ->where('compcode','9B')
                    ->where('deptcode','IMP')
                    ->where('trantype','phycnt')
                    ->where('recno','14')
                    ->get();

        foreach ($phycnt as $obj_p) {

            $product = DB::table('material.product')
                        ->where('compcode','9B')
                        ->where('itemcode','=',$obj_p->itemcode)
                        ->first();

            $netprice = $product->currprice;
            $total = $netprice * $obj_p->txnqty;

                DB::table('material.ivtxndt')
                    ->where('compcode','9B')
                    ->where('idno',$obj_p->idno)
                    ->update([
                        'netprice' => $netprice,
                        'amount' => $total,
                        'totamount' => $total
                    ]);
        }

        //JTR
        DB::table('material.ivtxndt')
                    ->where('compcode','9B')
                    ->where('trantype','JTR')
                    ->where('deptcode','IMP')
                    ->update(['compcode'=>'XX']);
    }

    public function btlkn_imp_2(Request $request){

        $from=$request->from;
        $to=$request->from+3000;

        $stockloc = DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('deptcode','IMP')
                    ->where('year','2024')
                    ->orderBy('idno', 'DESC')
                    ->offset($from)
                    ->limit($to)
                    ->get();

        foreach ($stockloc as $key => $value) {

            // $product = DB::table('material.product')
            //                 ->where('compcode','9B')
            //                 ->where('itemcode',$obj->itemcode)
            //                 ->where('uomcode',$obj->uomcode)
            //                 ->first();

            $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('txnqty');
            $minus = $ivdspdt;

            $ivtxndt = DB::table('material.ivtxndt')
                        ->whereIn('trantype',['GRN','AI','PHYCNT','TUI'])
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('txnqty');
            $add = $ivtxndt;

            $ivtxndt_2 = DB::table('material.ivtxndt')
                        ->whereIn('trantype',['TUO','AO'])
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('txnqty');
            $minus_2 = $ivtxndt_2;

            $all = $add - $minus - $minus_2;

            $ivdspdt2 = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('amount');
            $minus2 = $ivdspdt2;

            $ivtxndt2 = DB::table('material.ivtxndt')
                        ->whereIn('trantype',['GRN','AI','PHYCNT','TUI'])
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('amount');
            $add2 = $ivtxndt2;

            $ivtxndt2_2 = DB::table('material.ivtxndt')
                        ->whereIn('trantype',['TUO','AO'])
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-12-01')
                        ->where('trandate','<=','2024-12-31')
                        ->sum('amount');
            $minus2_2 = $ivtxndt2_2;

            $all2 = $add2 - $minus2 - $minus2_2;

            // $netmvqty11 = $product->qtyonhand - $all;
            // dump($product->qtyonhand);
            // dump($all);
            // dump($netmvqty11);

            DB::table('material.stockloc')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('deptcode','IMP')
                        ->where('year','2024')
                        ->update([
                            'openbalqty' => 0,
                            'openbalval' => 0,
                            'netmvqty1' => 0,
                            'netmvqty2' => 0,
                            'netmvqty3' => 0,
                            'netmvqty4' => 0,
                            'netmvqty5' => 0,
                            'netmvqty6' => 0,
                            'netmvqty7' => 0,
                            'netmvqty8' => 0,
                            'netmvqty12' => $all,
                            'netmvval1' => 0,
                            'netmvval2' => 0,
                            'netmvval3' => 0,
                            'netmvval4' => 0,
                            'netmvval5' => 0,
                            'netmvval6' => 0,
                            'netmvval7' => 0,
                            'netmvval8' => 0,
                            'netmvval12' => $all2,
                        ]);
        }
    }

    public function btlkn_imp_3(Request $request){

        $from=$request->from;
        $to=$request->from+3000;

        $stockloc = DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('deptcode','IMP')
                    ->where('year','2024')
                    ->orderBy('idno', 'DESC')
                    ->offset($from)
                    ->limit($to)
                    ->get();

        foreach ($stockloc as $key => $value) {

            // $product = DB::table('material.product')
            //                 ->where('compcode','9B')
            //                 ->where('itemcode',$obj->itemcode)
            //                 ->where('uomcode',$obj->uomcode)
            //                 ->first();

            $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-11-01')
                        ->where('trandate','<=','2024-11-30')
                        ->sum('txnqty');
            $minus = $ivdspdt;

            $ivtxndt = DB::table('material.ivtxndt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-11-01')
                        ->where('trandate','<=','2024-11-30')
                        ->sum('txnqty');
            $add = $ivtxndt;

            $all = $add - $minus;

            $ivdspdt2 = DB::table('material.ivdspdt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-11-01')
                        ->where('trandate','<=','2024-11-30')
                        ->sum('amount');
            $minus2 = $ivdspdt2;

            $ivtxndt2 = DB::table('material.ivtxndt')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('trandate','>=','2024-11-01')
                        ->where('trandate','<=','2024-11-30')
                        ->sum('amount');
            $add2 = $ivtxndt2;

            $all2 = $add2 - $minus2;

            // $netmvqty11 = $product->qtyonhand - $all;
            // dump($product->qtyonhand);
            // dump($all);
            // dump($netmvqty11);

            DB::table('material.stockloc')
                        ->where('compcode','9B')
                        ->where('itemcode',$value->itemcode)
                        ->where('deptcode','IMP')
                        ->where('year','2024')
                        ->update([
                            'openbalqty' => 0,
                            'openbalval' => 0,
                            'netmvqty1' => 0,
                            'netmvqty2' => 0,
                            'netmvqty3' => 0,
                            'netmvqty4' => 0,
                            'netmvqty5' => 0,
                            'netmvqty6' => 0,
                            'netmvqty7' => 0,
                            'netmvqty8' => 0,
                            'netmvqty11' => $all,
                            'netmvval1' => 0,
                            'netmvval2' => 0,
                            'netmvval3' => 0,
                            'netmvval4' => 0,
                            'netmvval5' => 0,
                            'netmvval6' => 0,
                            'netmvval7' => 0,
                            'netmvval8' => 0,
                            'netmvval11' => $all2,
                        ]);
        }
    }

    public function test_date_zulu(){
        $date = Carbon::now("Etc/Zulu");
        dd($date->format('H:i:s'));
    }

    public function btlkan_stockloc_open(Request $request){
        $type = $request->type;

        if($type == 'imp'){
            $table_used = 'temp.imp_stockloc';
            $deptcode = 'imp';
        }else if($type == 'khealth'){
            $table_used = 'temp.khealth_stockloc';
            $deptcode = 'khealth';
        }else if($type == 'fkwstr'){
            $table_used = 'temp.fkwstr_stockloc';
            $deptcode = 'fkwstr';
        }else{
            dd('salah wei xde type');
        }

        $table = DB::table($table_used)->get();

        foreach ($table as $key => $value) {
            // dd($value);
            $s_NetMvQty1 = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('year','2025')
                            ->where('deptcode',$deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->sum('NetMvQty1');

            $s_NetMvVal1 = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('year','2025')
                            ->where('deptcode',$deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->sum('NetMvVal1');

            DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('deptcode',$deptcode)
                    ->where('year','2025')
                    // ->where('unit',$unit)
                    ->where('itemcode',$value->itemcode)
                    ->update([
                        'openbalqty' => $value->closeqty,
                        'openbalval' => $value->closeamt,
                        'qtyonhand' => $value->closeqty + $s_NetMvQty1,
                        // 'NetMvVal1' => $value->closeamt + $s_NetMvVal1,
                    ]);

            DB::table('material.product')
                    ->where('compcode','9B')
                    // ->where('year','2025')
                    // ->where('unit',$unit)
                    ->where('itemcode',$value->itemcode)
                    ->update([
                        'qtyonhand' => $value->closeqty + $s_NetMvQty1,
                        // 'openbalval' => $value->closeamt,
                        // 'NetMvQty1' => $value->closeqty + $s_NetMvQty1,
                        // 'NetMvVal1' => $value->closeamt + $s_NetMvVal1,
                    ]);
        }
    }

    public function btlkan_stockloc_open2(Request $request){
        $type = $request->type;

        if($type == 'imp'){
            $table_used = 'temp.imp_stockloc';
            $unit = 'imp';
        }else if($type == 'khealth'){
            $table_used = 'temp.khealth_stockloc';
        }else if($type == 'fkwstr'){
            $table_used = 'temp.fkwstr_stockloc';
        }else{
            dd('salah wei xde type');
        }

        $table = DB::table($table_used)->get();

        foreach ($table as $key => $value) {
            // dd($value);
            // $s_NetMvQty1 = DB::table('material.stockloc')
            //                 ->where('compcode','9B')
            //                 ->where('year','2025')
            //                 ->where('itemcode',$value->itemcode);

            // $s_NetMvVal1 = DB::table('material.stockloc')
            //                 ->where('compcode','9B')
            //                 ->where('year','2025')
            //                 ->where('itemcode',$value->itemcode);

            $stockloc_ = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('year','2025')
                            ->where('itemcode',$value->itemcode);

            foreach ($variable as $key => $value) {
                // code...
            }

            DB::table('material.stockloc')
                    ->where('compcode','9B')
                    ->where('year','2025')
                    // ->where('unit',$unit)
                    ->where('itemcode',$value->itemcode)
                    ->update([
                        'openbalqty' => $value->closeqty,
                        'openbalval' => $value->closeamt,
                        // 'NetMvQty1' => $value->closeqty + $s_NetMvQty1,
                        // 'NetMvVal1' => $value->closeamt + $s_NetMvVal1,
                    ]);
        }
    }

    public function recon_DO(Request $request){
        $recno = $request->recno;

        $delordhd_obj = DB::table('material.delordhd')
            ->where('compcode', session('compcode'))
            ->where('recno', $recno)
            ->first();

        $delordt = DB::table('material.delorddt')
            ->where('compcode', session('compcode'))
            ->where('recno', $recno)
            ->get();

        foreach ($delordt as $key => $value) {
            $yearperiod = defaultController::getyearperiod_($delordhd_obj->trandate);

            //tengok product category
            $product_obj = DB::table('material.product')
                ->where('compcode','=', $value->compcode)
                // ->where('unit','=', $deldept_unit)
                ->where('itemcode','=', $value->itemcode)
                ->first();

            //amik department,category dgn sysparam pvalue1 dgn pvalue2
            //utk debit costcode
            if(strtoupper($product_obj->groupcode) == "STOCK" || strtoupper($product_obj->groupcode) == "OTHERS" || strtoupper($product_obj->groupcode) == "CONSIGNMENT" ){
                $row_dept = DB::table('sysdb.department')
                    ->select('costcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$delordhd_obj->deldept)
                    ->first();
                //utk debit accountcode
                $row_cat = DB::table('material.category')
                    ->select('stockacct')
                    ->where('compcode','=',session('compcode'))
                    ->where('catcode','=',$productcat)
                    ->first();

                $drcostcode = $row_dept->costcode;
                $dracc = $row_cat->stockacct;

                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

            }else if(strtoupper($product_obj->groupcode) == "ASSET"){
                $facode = DB::table('finance.facode')
                    ->where('compcode','=', $value->compcode)
                    ->where('assetcode','=', $product_obj->productcat)
                    ->first();

                $drcostcode = $facode->glassetccode;
                $dracc = $facode->glasset;
                
                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

            }else{
                throw new \Exception("Item at delorddt doesn't have groupcode at table product");
            }

            if(strtoupper($product_obj->groupcode) == "STOCK"){
                $source_ = 'IV';
            }else{
                $source_ = 'DO';
            }

            $gltran = DB::table('finance.gltran')
                            ->where('compcode',session('compcode'))
                            ->where('source',$source_)
                            ->where('trantype',$delordhd_obj->trantype)
                            ->where('auditno',$value->recno)
                            ->where('lineno_',$value->lineno_);

            if($gltran->exists()){
                dump('gltran exists');
                continue;
            }

            dump('make new gltran');

            //1. buat gltran
            DB::table('finance.gltran')
                ->insert([
                    'compcode' => $value->compcode,
                    'adduser' => $value->adduser,
                    'adddate' => $value->adddate,
                    'auditno' => $value->recno,
                    'lineno_' => $value->lineno_,
                    'source' => $source_, //kalau stock 'IV', lain dari stock 'DO'
                    'trantype' => $delordhd_obj->trantype,
                    'reference' => $delordhd_obj->deldept .' '. str_pad($delordhd_obj->docno,7,"0",STR_PAD_LEFT),
                    'description' => $value->itemcode.'</br>'.$product_obj->description, 
                    'postdate' => $delordhd_obj->trandate,
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $drcostcode,
                    'dracc' => $dracc,
                    'crcostcode' => $row_sysparam->pvalue1,
                    'cracc' => $row_sysparam->pvalue2,
                    'amount' => $value->amount,
                    'idno' => $delordhd_obj->deldept .' '. $delordhd_obj->docno
                ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  defaultController::isGltranExist_($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $value->amount + $gltranAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $value->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$row_sysparam->pvalue1)
                    ->where('glaccount','=',$row_sysparam->pvalue2)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $row_sysparam->pvalue1,
                        'glaccount' => $row_sysparam->pvalue2,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$value->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }
        }
    }

    public function ivtmphd_ivtxnhd_recno(Request $request){

        DB::beginTransaction();

        try {
            $ivtmphd = DB::table('material.ivtmphd')
                        ->where('compcode',session('compcode'))
                        ->where('trandate','>=','2025-01-01')
                        ->where('trandate','<=','2025-01-31')
                        ->get();


            foreach ($ivtmphd as $key => $value) {
                $newrecno = str_pad($value->recno, 6, "400000", STR_PAD_LEFT);

                DB::table('material.ivtmphd')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        ->where('source',$value->source)
                        ->where('trantype',$value->trantype)
                        ->update([
                            'recno' => $newrecno
                        ]);

                DB::table('material.ivtmpdt')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        // ->where('source',$value->source)
                        // ->where('trantype',$value->trantype)
                        ->update([
                            'recno' => $newrecno
                        ]);

                $ivtxnhd = DB::table('material.ivtxnhd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('source',$value->source)
                            ->where('trantype',$value->trantype);

                if($ivtxnhd->exists()){

                    DB::table('material.ivtxnhd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('source',$value->source)
                            ->where('trantype',$value->trantype)
                            ->update([
                                'recno' => $newrecno
                            ]);

                    DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('trantype',$value->trantype)
                            ->update([
                                'recno' => $newrecno
                            ]);
                }

                echo nl2br("$key. New Record No. $newrecno -> $value->trantype - $value->source - $value->recno \n");
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }       
    }

    public function delordhd_ivtxnhd_recno(Request $request){

        DB::beginTransaction();

        try {
            $delordhd = DB::table('material.delordhd')
                            ->where('compcode',session('compcode'))
                            ->where('trandate','>=','2025-01-01')
                            ->where('trandate','<=','2025-01-31')
                            ->get();

            foreach ($delordhd as $key => $value) {
                $newrecno = str_pad($value->recno, 6, "300000", STR_PAD_LEFT);

                DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        ->where('trantype',$value->trantype)
                        ->update([
                            'recno' => $newrecno
                        ]);

                DB::table('material.delorddt')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        // ->where('trantype',$value->trantype)
                        ->update([
                            'recno' => $newrecno
                        ]);

                $ivtxnhd = DB::table('material.ivtxnhd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('source','IV')
                            ->where('trantype',$value->trantype);

                if($ivtxnhd->exists()){
                    DB::table('material.ivtxnhd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('source','IV')
                            ->where('trantype',$value->trantype)
                            ->update([
                                'recno' => $newrecno
                            ]);

                    DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$value->recno)
                            ->where('trantype',$value->trantype)
                            ->update([
                                'recno' => $newrecno
                            ]);
                }

                echo nl2br("$key. New Record No. $newrecno -> $value->trantype - IV - $value->recno \n");
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }               
    }

    public function betulkan_ivtxndt_dr_do(Request $request){
        DB::beginTransaction();

        try {
            $delorddt = DB::table('material.delorddt')
                            ->where('compcode',session('compcode'))
                            ->whereColumn('pouom','!=','uomcode')
                            ->where('trandate','>=','2025-02-01')
                            ->where('trandate','<=','2025-02-28')
                            ->where('recstatus','POSTED')
                            ->get();

            $key=0;
            foreach ($delorddt as $key => $value) {
                $key++;
                $ivtxndt = DB::table('material.ivtxndt')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        ->where('lineno_',$value->lineno_)
                        ->where('itemcode',$value->itemcode);

                if($ivtxndt->exists()){
                    DB::table('material.ivtxndt')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$value->recno)
                        ->where('lineno_',$value->lineno_)
                        ->where('itemcode',$value->itemcode)
                        ->update([
                            'uomcode' => $value->pouom,
                            'uomcoderecv' => $value->uomcode,
                            'txnqty' => $value->qtydelivered,
                            'netprice' => $value->netunitprice,
                            'amount' => $value->amount,
                            'totamount' => $value->amount
                        ]);

                echo nl2br("$key. update ivtxndt itemcode: $value->itemcode, recno: $value->recno, lineno_: $value->lineno_ \n");

                }else{
                    dump($value);
                    dd($value->itemcode.' not exists');
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }   
    }

    public  function betulkan_stockloc_2025(Request $request){
        DB::beginTransaction();
        $year = 2025;
        $step = $request->step;

        try {
            $temp_stockloc = DB::table('temp.temp_stock')
                                ->get();

            if($step == 1){
                foreach ($temp_stockloc as $key => $value) {
                    $stockloc = DB::table('material.stockloc')
                                    ->where('compcode',session('compcode'))
                                    ->where('deptcode',$value->dept)
                                    ->where('itemcode',$value->itemcode)
                                    ->where('year',$year);

                    if($stockloc->exists()){
                        DB::table('material.stockloc')
                                    ->where('compcode',session('compcode'))
                                    ->where('deptcode',$value->dept)
                                    ->where('itemcode',$value->itemcode)
                                    ->where('year',$year)
                                    ->update([
                                        'openbalqty' => $value->openbalqty,
                                        'openbalval' => $value->openbalval,
                                        'netmvqty1' => $value->netmvqty1,
                                        'netmvqty2' => $value->netmvqty2,
                                        'netmvval1' => $value->netmvval1,
                                        'netmvval2' => $value->netmvval2
                                    ]);

                        echo nl2br("$key. Update stockloc $value->itemcode - $value->dept \n");
                    }
                }
            }else if($step == 2){
                $stockloc_new = DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('year',$year)
                                ->get();

                foreach ($stockloc_new as $key => $value) {
                    $qtyonhand = $value->openbalqty + $value->netmvqty1 + $value->netmvqty2 + $value->netmvqty3;

                    DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$value->deptcode)
                                ->where('itemcode',$value->itemcode)
                                ->where('year',$year)
                                ->update([
                                    'qtyonhand' => $qtyonhand
                                ]);

                    echo nl2br("$key. Update stockloc $value->itemcode - $value->deptcode  - qtyonhand: $qtyonhand \n");
                }
            }else if($step == 3){
                $product = DB::table('temp.temp_stock')
                                ->offset($request->offset)
                                ->limit($request->limit)
                                ->get();

                foreach ($product as $key => $value) {
                    $qtyonhand = DB::table('material.stockloc')
                                    ->where('compcode',session('compcode'))
                                    ->where('itemcode',$value->itemcode)
                                    ->where('year',$year)
                                    ->sum('qtyonhand');

                    DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                // ->where('uomcode',$value->uomcode)
                                ->update([
                                    'qtyonhand' => $qtyonhand
                                ]);

                    echo nl2br("$key. Update product $value->itemcode - qtyonhand: $qtyonhand \n");
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }               
    }

    public function check_product_qtyonhand_sama_dgn_stockloc_qtyonhand(Request $request){
        DB::beginTransaction();

        try {
            $unit = $request->unit;
            $product = DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                ->where('unit',$unit)
                                ->get();

            foreach ($product as $key => $value) {
                $qtyonhand = DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('year','2025')
                                ->sum('qtyonhand');

                if($qtyonhand != $value->qtyonhand){
                    echo nl2br("$key. qtyonhand not same $value->itemcode - qtyonhand(p - s): $qtyonhand - $value->qtyonhand \n");

                    DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                // ->where('uomcode',$value->uomcode)
                                ->update([
                                    'qtyonhand' => $qtyonhand
                                ]);

                    echo nl2br("$key. Update product $value->itemcode - qtyonhand: $qtyonhand \n");
                }

            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }    
        
    }

    public function check_avgcost_divert_too_much(Request $request){
        $unit = $request->unit;

        $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',$unit)
                            ->get();
 // && ((float)$value->avgcost != (float)$value->currprice)
        $i = 1;
        foreach ($product as $key => $value) {
            if(!empty((float)$value->currprice)){
                $n = (float)$value->avgcost;
                $a = (float)$value->currprice - 10;
                $b = (float)$value->currprice + 10;
                if(($n-$a)*($n-$b) > 0){
                    echo nl2br("$i. $value->itemcode avgcost problem $value->avgcost - currprice: $value->currprice \n");
                    $i++;
                }
                // if (!in_array((float)$value->avgcost, range((float)$value->currprice - 10, (float)$value->currprice + 10))) {
                // }
            }
        }
    }

    public function netmvval_from_netmvqty(Request $request){

        DB::beginTransaction();

        try {
            $unit = $request->unit;

            $product = DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                ->where('unit',$unit)
                                ->get();
            $i = 1;
            foreach ($product as $key => $value) {
                $stockloc = DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('year','2025');

                if($stockloc->exists()){
                    $stockloc = $stockloc->first();

                    $avgcost = $value->avgcost;

                    $netmvval3 = $stockloc->netmvqty3 * $avgcost;

                    if($netmvval3 != $stockloc->netmvval3){

                        echo nl2br("$i. $value->itemcode netmvval3 not same: suppose: $netmvval3 - real: $stockloc->netmvval3  \n");

                        DB::table('material.stockloc')
                                    ->where('compcode',session('compcode'))
                                    ->where('itemcode',$value->itemcode)
                                    ->where('year','2025')
                                    ->update([
                                        'netmvval3' => $netmvval3
                                    ]);
                        $i++;
                    }
                }

            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }   
    }

    public function delete_stockloc_terlebih(Request $request){
        DB::beginTransaction();
        $unit = $request->unit;

        try {
            $stockloc = DB::table('material.stockloc')
                            ->where('deptcode','=',$unit)
                            ->where('compcode',session('compcode'))
                            ->where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'))
                            ->get();

            foreach ($stockloc as $key => $value) {
                $stockloc = DB::table('material.stockloc')
                                ->where('itemcode','=',$value->itemcode)
                                ->where('deptcode','=',$unit)
                                ->where('compcode',session('compcode'))
                                ->where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'));

                if($stockloc->count() > 1){
                    $stockloc_ = $stockloc->first();

                    DB::table('material.stockloc')
                                ->where('itemcode','=',$value->itemcode)
                                ->where('deptcode','=',$unit)
                                ->where('compcode',session('compcode'))
                                ->where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'))
                                ->where('idno','!=',$stockloc_->idno)
                                ->update([
                                    'compcode' => 'CC'
                                ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }      
    }

    public  function betulkan_poli_qtyonhand(Request $request){
        DB::beginTransaction();
        $unit = $request->unit;

        try {
            $stockloc = DB::table('material.stockloc')
                            ->where('deptcode','=',$unit)
                            ->where('compcode',session('compcode'))
                            ->where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'))
                            ->get();

            foreach ($stockloc as $key => $value) {
                $txnqty = DB::table('material.ivtxndt')
                                ->where('itemcode','=',$value->itemcode)
                                ->where('deptcode','=',$unit)
                                ->where('compcode',session('compcode'))
                                ->where('trandate','>','2025-01-01')
                                ->sum('txnqty');

                DB::table('material.stockloc')
                            ->where('itemcode','=',$value->itemcode)
                            ->where('deptcode','=',$unit)
                            ->where('compcode',session('compcode'))
                            ->where('year',Carbon::now('Asia/Kuala_Lumpur')->format('Y'))
                            ->update([
                                'qtyonhand' => $txnqty
                            ]);

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }               
    }

    public function betulkan_semula_imp_stockcount(Request $request){
        DB::beginTransaction();

        try {
            $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno','34')
                            ->where('trantype','phycnt')
                            ->where('deptcode','imp')
                            ->get();

            $phycnthd = DB::table('material.phycnthd')
                            ->where('compcode',session('compcode'))
                            ->where('idno','30')
                            ->first();

            foreach ($ivtxndt as $key => $value) {
                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('issdept','IMP');

                $phycntdt = DB::table('material.phycntdt')
                            ->where('compcode',session('compcode'))
                            ->where('recno','34')
                            ->where('itemcode',$value->itemcode)
                            ->first();

                $thyqty = $phycntdt->thyqty;
                $phyqty = $phycntdt->phyqty;

                $dspqty = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('issdept','IMP')
                            ->where('trandate','>=',$phycnthd->frzdate)
                            ->where('updtime','>=',$phycnthd->frztime)
                            ->sum('txnqty');

                $dspqty_minus = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('issdept','IMP')
                            ->where('trandate','=',$phycnthd->phycntdate)
                            ->sum('txnqty');

                $vrqty =  floatval($phyqty) - floatval($thyqty) + Floatval($dspqty);

                DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$value->idno)
                            ->update([
                                'txnqty' => $vrqty
                            ]);

                $newqtyonhand = $phyqty;
                $product_obj = DB::table('material.product')
                    ->where('product.unit','=','imp')
                    ->where('product.compcode','=',session('compcode'))
                    ->where('product.itemcode','=',$value->itemcode)
                    ->where('product.uomcode','=',$value->uomcode)
                    ->update([
                        'qtyonhand' => $newqtyonhand,
                        // 'avgcost' => $newAvgCost,
                    ]);

                DB::table('material.stockloc')
                    // ->where('product.unit','=','imp')
                    ->where('stockloc.compcode','=',session('compcode'))
                    ->where('stockloc.itemcode','=',$value->itemcode)
                    ->where('stockloc.deptcode','=','IMP')
                    ->update([
                        'qtyonhand' => $newqtyonhand,
                        // 'avgcost' => $newAvgCost,
                    ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }          
    }

    public function kira_blk_netmvqty(Request $request){
        DB::beginTransaction();

        try {
            $from=$request->from;
            $to=$request->from+3000;

            $stockloc = DB::table('material.stockloc')
                        ->where('compcode','9B')
                        ->where('deptcode','IMP')
                        ->where('year','2025')
                        ->orderBy('idno', 'DESC')
                        ->where('itemcode','71021339')
                        ->offset($from)
                        ->limit($to)
                        ->get();

            foreach ($stockloc as $key => $value) {

                // $product = DB::table('material.product')
                //                 ->where('compcode','9B')
                //                 ->where('itemcode',$obj->itemcode)
                //                 ->where('uomcode',$obj->uomcode)
                //                 ->first();

                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('issdept','IMP')
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('deptcode','IMP')
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('txnqty');
                $add = $ivtxndt;


                $all = floatval($add) - floatval($minus);

                $ivdspdt2 = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('issdept','IMP')
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('amount');
                $minus2 = $ivdspdt2;

                $ivtxndt2 = DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('deptcode','IMP')
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('amount');
                $add2 = $ivtxndt2;

                $all2 = floatval($add2) - floatval($minus2);

                // $netmvqty11 = $product->qtyonhand - $all;
                // dump($product->qtyonhand);
                // dump($all);
                // dump($netmvqty11);

                DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('deptcode','IMP')
                            ->where('year','2025')
                            ->update([
                                'netmvqty3' => $all,
                                'netmvval3' => $all2,
                            ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }              
    }

    public function kira_netmvqty_netmvval_peritem(Request $request){
        DB::beginTransaction();

        try {

            $itemcode=$request->itemcode;
            $deptcode=$request->deptcode;
            $period=intval($request->period);

            $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
            $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');

            $stockloc = DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('itemcode',$itemcode)
                        ->where('deptcode',$deptcode)
                        ->where('year','2025')
                        ->orderBy('idno', 'DESC')
                        ->get();

            foreach ($stockloc as $key => $value) {
                $value_array = (array)$value;

                // $product = DB::table('material.product')
                //                 ->where('compcode','9B')
                //                 ->where('itemcode',$obj->itemcode)
                //                 ->where('uomcode',$obj->uomcode)
                //                 ->first();

                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->get();

                $add = 0;
                // $add2 = 0;
                foreach ($ivtxndt as $key => $value) {
                    $ivtxntype = DB::table('material.ivtxntype')
                                        ->where('compcode',session('compcode'))
                                        ->where('trantype',$value->trantype)
                                        ->first();

                    $crdbfl = $ivtxntype->crdbfl;

                    if($crdbfl == 'In'){
                        $add = $add + $value->txnqty;
                        // $add2 = $add2 + $value->amount;
                    }else{
                        $add = $add - $value->txnqty;
                        // $add2 = $add2 - $value->amount;
                    }
                }

                $all = $add - $minus;

                // $ivdspdt2 = DB::table('material.ivdspdt')
                //             ->where('compcode',session('compcode'))
                //             ->where('itemcode',$value->itemcode)
                //             ->where('trandate','>=',$day_start)
                //             ->where('trandate','<=',$day_end)
                //             ->sum('amount');
                // $minus2 = $ivdspdt2;

                // $all2 = $add2 - $minus2;

                // $netmvqty11 = $product->qtyonhand - $all;
                // dump($product->qtyonhand);
                // dump($all);
                // dump($netmvqty11);

                // DB::table('material.stockloc')
                //             ->where('compcode',session('compcode'))
                //             ->where('itemcode',$itemcode)
                //             ->where('deptcode',$deptcode)
                //             ->where('year','2025')
                //             ->update([
                //                 'netmvqty'.$period => $all,
                //                 // 'netmvval'.$period => $all2
                //             ]);

                // if($value_array['netmvqty'.$period] != $all){
                    dump('SAVED netmvqty'.$period.' => '.$value_array['netmvqty'.$period]);
                    dump('REAL netmvqty'.$period.' => '.$all);
                // }

                // dump($value_array['netmvqty'.$period] != $all);
                // dump('netmvval'.$period.' => '.$all2);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function ivtxndt_10s(Request $request){
        DB::beginTransaction();

        try {
            $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','PHYCNT')
                            ->where('trandate','>','2025-03-01')
                            ->where('deptcode','FKWSTR')
                            ->get();

            foreach ($ivtxndt as $key => $value) {
                if(abs($value->totamount) > 200){
                    $totamount = $value->totamount;
                    $txnqty = $value->txnqty;
                    $netprice = $value->netprice;

                    $net10 = $netprice * 10;
                    $new_unitcost = $net10 / $txnqty;

                    DB::table('material.ivtxndt')
                                ->where('idno',$value->idno)
                                ->update([
                                    'netprice' => $new_unitcost,
                                    'totamount' => $net10,
                                    'amount' => $net10
                                ]);
                }
            }
        
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function ivtxndt_10s_peritem(Request $request){
        DB::beginTransaction();

        try {
            $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','PHYCNT')
                            ->where('trandate','>','2025-03-01')
                            ->where('deptcode','FKWSTR')
                            ->get();

            foreach ($ivtxndt as $key => $value) {
                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->get();

                $add = 0;
                $add2 = 0;
                foreach ($ivtxndt as $key => $value) {
                    $ivtxntype = DB::table('material.ivtxntype')
                                        ->where('compcode','9B')
                                        ->where('trantype',$value->trantype)
                                        ->first();

                    $crdbfl = $ivtxntype->crdbfl;

                    if($crdbfl == 'In'){
                        $add = $add + $value->txnqty;
                        $add2 = $add2 + $value->amount;
                    }else{
                        $add = $add - $value->txnqty;
                        $add2 = $add2 - $value->amount;
                    }
                }

                $all = $add - $minus;

                $ivdspdt2 = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=','2025-03-01')
                            ->where('trandate','<=','2025-03-31')
                            ->sum('amount');
                $minus2 = $ivdspdt2;

                $all2 = $add2 - $minus2;

                // $netmvqty11 = $product->qtyonhand - $all;
                // dump($product->qtyonhand);
                // dump($all);
                // dump($netmvqty11);

                DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('itemcode',$value->itemcode)
                            ->where('deptcode',$value->deptcode)
                            ->where('year','2025')
                            ->update([
                                'netmvqty3' => $all,
                                'netmvval3' => $all2
                            ]);
            }
        
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function update_imp_ivdspdt_negetive(Request $request){
        DB::beginTransaction();

        try {
            $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','9B')
                            ->where('itemcode','71003540')
                            ->where('trandate','>=','2025-03-01')
                            ->get();

            foreach ($ivdspdt as $key => $value) {
                $txnqty = $value->txnqty;
                $netprice = $value->netprice;

                $newnetprice = 12;
                $newamount = $txnqty * 12;


                DB::table('material.ivdspdt')
                        ->where('idno',$value->idno)
                        ->update([
                            'netprice' => $newnetprice,
                            'amount' => $newamount,
                        ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function cr8_acctmaster(Request $request){

        DB::beginTransaction();

        try {
            $acctmaster = DB::table('recondb.acctmaster')
                            ->where('compcode','9B')
                            ->get();

            foreach ($acctmaster as $key => $value) {

                $glmasdtl = DB::table('finance.glmasdtl')
                            ->where('compcode','9B')
                            ->where('year',$value->year)
                            ->where('glaccount',$value->glaccount)
                            ->where('costcode',$value->costcode);

                if(!$glmasdtl->exists()){
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $value->costcode,
                            'glaccount' => $value->glaccount,
                            'year' => $value->year,
                            'adduser' => 'SYSTEM',
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function recondb_ledger(Request $request){
        $arconvert = DB::table('recondb.arconvert')
                        ->where('idno',32)
                        ->get();

        foreach ($arconvert as $obj) {
            $newamt = str_replace(',', '', $obj->amount);
            dump($newamt);
        }
    }

    public function display_glmasref_xde(Request $request){
        if(empty($request->period)){
            dd('no request period');
        }
        $period = $request->period;
        $glmasdtl = DB::table('finance.glmasdtl')
                        ->where('compcode',session('compcode'))
                        // ->where('actamount'.$period,'<>',0)
                        ->where('year','2025')
                        ->get();

        $glmasdtl = collect($glmasdtl)->unique('glaccount');

        $notin=[];
        foreach ($glmasdtl as $obj) {
            $obj_ = (array)$obj;
            $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->where('glaccno',$obj->glaccount)
                        ->exists();

            if(!$glmasref){
                array_push($notin, $obj->glaccount.' - '.$obj_['actamount'.$period]);
            }
        }

        dd($notin);
    }

    public function display_glmasref_header(Request $request){
        if(empty($request->period)){
            dd('no request period');
        }
        $period = $request->period;
        $glmasdtl = DB::table('finance.glmasdtl')
                        ->where('compcode',session('compcode'))
                        // ->where('actamount'.$period,'<>',0)
                        ->where('year','2025')
                        ->get();

        $glmasdtl = collect($glmasdtl)->unique('glaccount');

        $notin=[];
        foreach ($glmasdtl as $obj) {
            $obj_ = (array)$obj;
            $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->where('acttype','H')
                        ->where('glaccno',$obj->glaccount)
                        ->exists();

            if($glmasref){
                array_push($notin, $obj->glaccount.' - '.$obj_['actamount'.$period]);
            }
        }

        dd($notin);
    }

    public function betulkan_dbacthdr(Request $request){

        DB::beginTransaction();

        try {
            $dballoc = DB::table('debtor.dballoc')
                            ->where('compcode',session('compcode'))
                            ->where('docsource','pb')
                            ->where('doctrantype','rc')
                            ->where('docauditno','590966')
                            ->get();

            foreach ($dballoc as $obj) {
                $dbacthdr_ = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source',$obj->refsource)
                                ->where('trantype',$obj->reftrantype)
                                ->where('auditno',$obj->refauditno)
                                ->first();

                DB::table('debtor.dbacthdr')
                        ->where('idno',$dbacthdr_->idno)
                        ->update([
                            'outamount' => $dbacthdr_->amount
                        ]);

                DB::table('debtor.dballoc')
                        ->where('idno',$obj->idno)
                        ->update([
                            'allocsts' => 'DEACTIVE'
                        ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }
    
    public function gltran_step1(Request $request){
        DB::beginTransaction();
        $period = $request->period;
        $last_period = $request->period - 1;
        if(empty($period)){
            dd('no PERIOD');
        }
        $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
        $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');
        $auditno = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('ymd');

        try {

            DB::table('finance.gltran')
                        ->where('compcode',session('compcode'))
                        ->where('year','2025')
                        ->where('period',$period)
                        ->where('source','IV')
                        ->where('trantype','OB')
                        ->update([
                            'compcode'=>'xx',
                            'upduser' =>'SYSTEM_AR',
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

            $glmasdtl = DB::table('finance.glmasdtl')
                            ->where('compcode',session('compcode'))
                            ->where('year','2025')
                            ->where('glaccount','20010052')
                            ->get();

            $x = 1;
            foreach ($glmasdtl as $obj) {
                $obj_ = (array)$obj;
                $amount = $obj_['actamount'.$last_period];

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'adduser' => 'system_ar96',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $auditno,
                        'lineno_' => $x,
                        'source' => 'IV',
                        'trantype' => 'OB',
                        // 'reference' => $obj->document,
                        'postdate' => $day_start,
                        'description' => 'Opening Stock', //suppliercode + suppliername
                        'year' => 2025,
                        'period' => $period,
                        'drcostcode' => $obj->costcode,
                        'dracc' => '20010040',
                        'crcostcode' => $obj->costcode,
                        'cracc' => '20010041',
                        'amount' => $amount,
                    ]);

                $x = $x + 1; 

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function gltran_step2(Request $request){
        // DB::beginTransaction();
        $period = $request->period;
        if(empty($period)){
            dd('no PERIOD');
        }
        $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
        $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');

        try {

            DB::table('finance.gltran')
                        ->where('compcode','9b')
                        ->where('year','2025')
                        ->where('period',$period)
                        ->where('source','iv')
                        ->whereIn('trantype',['GRN','GRT'])
                        ->update([
                            'compcode'=>'xx',
                            'upduser' =>'SYSTEM_AR',
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

            DB::table('finance.gltran')
                        ->where('compcode','9b')
                        ->where('year','2025')
                        ->where('period',$period)
                        ->where('source','do')
                        ->whereIn('trantype',['GRN','GRT'])
                        ->update([
                            'compcode'=>'xx',
                            'upduser' =>'SYSTEM_AR',
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

            $delorddt = DB::table('material.delorddt as dt')
                            ->select('dt.recno','dt.lineno_','dt.pricecode','dt.itemcode','dt.uomcode','dt.pouom','dt.suppcode','dt.trandate','dh.deldept','dt.deliverydate','dt.qtytag','dt.unitprice','dt.amtdisc','dt.perdisc','dt.prortdisc','dt.amtslstax','dt.perslstax','dt.netunitprice','dt.remarks','dt.qtyorder','dt.qtydelivered','dt.qtyoutstand','dt.productcat','dt.draccno','dt.drccode','dt.craccno','dt.crccode','dt.source','dt.updtime','dt.polineno','dt.itemmargin','dt.amount','dt.deluser','dt.deldate','dt.recstatus','dt.taxcode','dt.totamount','dt.qtyreturned','dh.postdate','dh.trantype','dh.docno')
                            ->where('dt.compcode','9b')
                            ->join('material.delordhd as dh', function($join) use ($day_start,$day_end){
                                $join = $join->on('dh.recno', '=', 'dt.recno')
                                              ->where('dh.recstatus','POSTED')
                                              ->whereDate('dh.postdate','>=',$day_start)
                                              ->whereDate('dh.postdate','<=',$day_end)
                                              // ->whereIn('dh.deldept',['IMP','KHEALTH','FKWSTR'])
                                              ->where('dh.compcode','9b');
                            })
                            ->get();

            // dd($this->getQueries($delorddt));

            foreach ($delorddt as $obj) {
                $product_obj = DB::table('material.product')
                        ->where('compcode','=', '9b')
                        // ->where('unit','=', $unit_)
                        ->where('itemcode','=', $obj->itemcode)
                        ->first();

                if(strtoupper($product_obj->groupcode) == "STOCK" || strtoupper($product_obj->groupcode) == "OTHERS" || strtoupper($product_obj->groupcode) == "CONSIGNMENT" ){
                    $row_dept = DB::table('sysdb.department')
                        ->select('costcode')
                        ->where('compcode','=',session('compcode'))
                        ->where('deptcode','=',$obj->deldept)
                        ->first();
                    //utk debit accountcode
                    $row_cat = DB::table('material.category')
                        ->where('compcode','=',session('compcode'))
                        ->where('catcode','=',$product_obj->productcat)
                        ->first();

                    $drcostcode = $row_dept->costcode;
                    if(strtoupper($product_obj->groupcode) == "STOCK"){
                        $dracc = '20010042';
                    }else{
                        $dracc = $row_cat->stockacct;
                    }

                    if(strtoupper($product_obj->groupcode) == "CONSIGNMENT"){
                        $dracc = $row_cat->ConsignAcct;
                    }

                }else if(strtoupper($product_obj->groupcode) == "ASSET"){
                    $facode = DB::table('finance.facode')
                        ->where('compcode','=', session('compcode'))
                        ->where('assetcode','=', $product_obj->productcat)
                        ->first();

                    $drcostcode = $facode->glassetccode;
                    $dracc = $facode->glasset;

                }else{
                    throw new \Exception("Item at delorddt doesn't have groupcode at table product");
                }

                if(strtoupper($product_obj->groupcode) == "STOCK"){
                    $source_ = 'IV';
                }else if(strtoupper($product_obj->groupcode) == "CONSIGNMENT"){
                    $source_ = 'DO';
                }else{
                    $source_ = 'DO';
                }

                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

                $crcostcode = $drcostcode; //crcc sama dg drcc
                $cracc = $row_sysparam->pvalue2;

                if($obj->trantype == 'GRT'){
                    $amount = -$obj->amount;
                }else{
                    $amount = $obj->amount;
                }

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => '9b',
                        'adduser' => 'system_ar96',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $obj->recno,
                        'lineno_' => $obj->lineno_,
                        'source' => $source_, //kalau stock 'IV', lain dari stock 'DO'
                        'trantype' => $obj->trantype,
                        'reference' => $obj->deldept .' '. str_pad($obj->docno,7,"0",STR_PAD_LEFT),
                        'description' => $obj->itemcode, 
                        'postdate' => $obj->postdate,
                        'year' => '2025',
                        'period' => $period,
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => round($amount, 2),
                        'idno' => $obj->deldept .' '. $obj->docno
                    ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function gltran_step3(Request $request){
        DB::beginTransaction();
        $period = $request->period;
        if(empty($period)){
            dd('no PERIOD');
        }

        $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
        $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');

        try {

            DB::table('finance.gltran')
                        ->where('compcode','9b')
                        ->where('year','2025')
                        ->where('period',$period)
                        ->where('source','iv')
                        ->whereIn('trantype',['tui','tuo'])
                        ->update([
                            'compcode'=>'xx',
                            'upduser' =>'SYSTEM_AR',
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

            $ivtxndt = DB::table('material.ivtxndt as ivdt')
                            ->select('ivdt.compcode','ivdt.recno','ivdt.lineno_','ivdt.itemcode','ivdt.uomcode','ivdt.uomcoderecv','ivdt.txnqty','ivdt.netprice','ivdt.adduser','ivdt.adddate','ivdt.upduser','ivdt.upddate','ivdt.productcat','ivdt.draccno','ivdt.drccode','ivdt.craccno','ivdt.crccode','ivdt.updtime','ivdt.expdate','ivdt.remarks','ivdt.qtyonhand','ivdt.qtyonhandrecv','ivdt.batchno','ivdt.amount','ivdt.deptcode','ivdt.gstamount','ivdt.totamount','ivdt.recstatus','ivdt.reopen','ivdt.unit','ivdt.vrqty','ivhd.trandate','ivhd.source','ivhd.trantype','ivhd.txndept','ivhd.docno','ivhd.sndrcv')
                            ->where('ivdt.compcode','9b')
                            ->join('material.IvTxnHd as ivhd', function($join) use ($day_start,$day_end){
                                $join = $join->on('ivhd.recno', '=', 'ivdt.recno')
                                              ->on('ivhd.trantype', '=', 'ivdt.trantype')
                                              ->whereDate('ivhd.trandate','>=',$day_start)
                                              ->whereDate('ivhd.trandate','<=',$day_end)
                                              ->where('ivhd.source','iv')
                                              ->whereIn('ivhd.trantype',['tui','tuo'])
                                              ->where('ivhd.compcode','9b');
                            })
                            ->get();

            foreach ($ivtxndt as $obj) {

                $dept_obj = DB::table('sysdb.department')
                    ->where('department.compcode','=',session('compcode'))
                    ->where('department.deptcode','=',$obj->txndept)
                    ->first();

                $sndrcv_obj = DB::table('sysdb.department')
                    ->where('department.compcode','=',session('compcode'))
                    ->where('department.deptcode','=',$obj->sndrcv);

                if(!$sndrcv_obj->exists()){
                    dd('dept sndrcv not exist : '.$obj->sndrcv);
                }

                $sndrcv_obj = $sndrcv_obj->first();

                if($obj->trantype=='TUI'){
                    $drccode=$dept_obj->costcode; 
                    $draccno='20010026'; 
                    $crccode=$sndrcv_obj->costcode;
                    $craccno='20010026'; 

                }else if($obj->trantype=='TUO'){
                    $drccode=$sndrcv_obj->costcode ;
                    $draccno='20010026'; 
                    $crccode=$dept_obj->costcode; 
                    $craccno='20010026'; 

                }

                DB::table('finance.gltran')
                        ->insert([
                            'compcode' => '9b',
                            'adduser' => 'system_ar96',
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $obj->recno,
                            'lineno_' => $obj->lineno_,
                            'source' => $obj->source,
                            'trantype' => $obj->trantype,
                            'reference' => $obj->txndept .' '. $obj->docno,
                            'description' => $obj->sndrcv,
                            'postdate' => $obj->trandate,
                            'year' => '2025',
                            'period' => $period,
                            'drcostcode' => $drccode,
                            'dracc' => $draccno,
                            'crcostcode' => $crccode,
                            'cracc' => $craccno,
                            'amount' => round($obj->amount, 2),
                            'idno' => $obj->itemcode
                        ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function gltran_step4(Request $request){//ni buat dkt python
        DB::beginTransaction();
        dd('buat dkt python -> gltran_stockloc.py');

        try {

            $stockloc = DB::table('material.stockloc')
                            ->where('compcode','9b')
                            ->where('year','2025')
                            // ->where('glaccount','20010052')
                            ->whereIn('deptcode',["FKWSTR",'IMP','khealth'])
                            ->get();

            $x = 1;
            foreach ($stockloc as $obj) {
                $amount = $obj->netmvval1 + $obj->netmvval2 + $obj->netmvval3 + $obj->netmvval4 + $obj->netmvval5; 


                $dept_obj = DB::table('sysdb.department')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$obj->deptcode);

                if(!$dept_obj->exists()){
                    dd($obj->deptcode.' not exists');
                }

                $dept_obj = $dept_obj->first();

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => '9B',
                        'adduser' => 'system_ar96',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => '250531',
                        'lineno_' => $x,
                        'source' => 'IV',
                        'trantype' => 'CB',
                        'reference' => $obj->itemcode,
                        'postdate' => '2025-05-31',
                        'description' => 'Monthly Opening and Closing Stock Update', //suppliercode + suppliername
                        'year' => 2025,
                        'period' => 5,
                        'idno' => $obj->deptcode,
                        'drcostcode' => $dept_obj->costcode,
                        'dracc' => '20010052',
                        'crcostcode' => $dept_obj->costcode,
                        'cracc' => '20010050',
                        'amount' => $obj->openbalval + $obj->netmvval1 + $obj->netmvval2 + $obj->netmvval3 + $obj->netmvval4 + $obj->netmvval5,
                    ]);
                $x = $x + 1;

            }

            // DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function stockloc_total(Request $request){

        $stockloc = DB::table('material.stockloc')
                        ->where('compcode','9b')
                        ->where('year','2025')
                        ->where('stocktxntype','tr')
                        ->whereIn('unit',["W'HOUSE",'IMP','khealth'])
                        ->get();

        $totamt_stock = 0;
        foreach ($stockloc as $obj) {
            $amount = $obj->netmvval5;
            $totamt_stock = $totamt_stock + $amount;
        }

        dd($totamt_stock);
    }

    public function netmv_stockloc_btlkn(Request $request){

        $stockloc = DB::table('material.stockloc')
                        ->where('compcode','9b')
                        ->where('year','2025')
                        ->whereIn('itemcode',['KW001413','KW001414','KW001415','KW001416','KW001417','KW001418','KW001419','KW001420','KW001421','KW001422','KW001423','KW001424','KW001425','KW001426','KW001427','KW001428','KW001429','KW001430','KW001431','KW001432','KW001433','KW001434','KW001435','KW001436','KW001437','KW001438','KW001445','KW001446','KW001451','KW001452','KW001453','KW001454'])
                        ->update([
                            'netmvqty5' => 0,
                            'netmvval5' => 0
                        ]);
    }

    public function create_prod_kaluxde(Request $request){
        DB::beginTransaction();

        try {

            $product = DB::table('recondb.temp_product')
                            ->whereIn('unit',['KHEALTH','IMP',"W'HOUSE"])
                            ->get();

            $x = 1;
            foreach ($product as $obj) {
                $exists = DB::table('material.product')
                                ->where('itemcode',$obj->itemcode)
                                ->where('unit',$obj->unit)
                                ->where('compcode',session('compcode'))
                                ->exists();

                if(!$exists){
                    DB::table('material.product')
                            ->insert([
                                'compcode' => session('compcode'),
                                'unit' => $obj->unit,
                                'itemcode' => $obj->itemcode,
                                'description' => $obj->description,
                                'uomcode' => $obj->uomcode,
                                'groupcode' => $obj->groupcode,
                                'productcat' => $obj->productcat,
                                'avgcost' => $obj->avgcost,
                                'currprice' => $obj->currprice,
                                'qtyonhand' => $obj->qtyonhand,
                                'adduser' => 'SYSTEM_AR',
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'recstatus' => 'ACTIVE',
                                'generic' => $obj->generic,
                                'Consignment' => 0,
                            ]);

                    dump($obj->itemcode.' - '.$obj->description);
                }

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function dbacthistory(Request $request){
        DB::beginTransaction();

        try {
            $auditno = $request->auditno;

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode','xx')
                            ->where('source','PB')
                            ->where('trantype','RD')
                            ->where('auditno',$auditno)
                            ->first();

            $auditno = $this->defaultSysparam('PB','RD');

            $patmast = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('mrn',$dbacthdr->mrn)
                            ->first();

            DB::table('recondb.dbacthistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => $dbacthdr->source,
                        'trantype' => $dbacthdr->trantype,
                        'auditno' => $auditno,
                        'lineno_' => $dbacthdr->lineno_,
                        'amount' => $dbacthdr->outamount,
                        'outamount' => $dbacthdr->outamount,
                        'recstatus' => $dbacthdr->recstatus,
                        'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'entrytime' => $dbacthdr->entrytime,
                        'entryuser' => $dbacthdr->entryuser,
                        'reference' => $dbacthdr->reference,
                        'recptno' => $dbacthdr->recptno,
                        'paymode' => $dbacthdr->paymode,
                        'tillcode' => $dbacthdr->tillcode,
                        'tillno' => $dbacthdr->tillno,
                        'debtortype' => $dbacthdr->debtortype,
                        'debtorcode' => $dbacthdr->debtorcode,
                        'payercode' => $dbacthdr->payercode,
                        'billdebtor' => $dbacthdr->billdebtor,
                        'remark' => $dbacthdr->remark,
                        'mrn' => $patmast->NewMrn,
                        'episno' => $dbacthdr->episno,
                        'authno' => $dbacthdr->authno,
                        'expdate' => $dbacthdr->expdate,
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser' => 'SYSTEM',
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => 'SYSTEM',
                        'deldate' => $dbacthdr->deldate,
                        'deluser' => $dbacthdr->deluser,
                        'epistype' => $dbacthdr->epistype,
                        'cbflag' => $dbacthdr->cbflag,
                        'conversion' => $dbacthdr->conversion,
                        'payername' => $dbacthdr->payername,
                        'hdrtype' => $dbacthdr->hdrtype,
                        'currency' => $dbacthdr->currency,
                        'rate' => $dbacthdr->rate,
                        'unit' => $dbacthdr->unit,
                        'invno' => $dbacthdr->invno,
                        'paytype' => $dbacthdr->paytype,
                        'bankcharges' => $dbacthdr->bankcharges,
                        'RCCASHbalance' => $dbacthdr->RCCASHbalance,
                        'RCOSbalance' => $dbacthdr->RCOSbalance,
                        'RCFinalbalance' => $dbacthdr->RCFinalbalance,
                        'PymtDescription' => $dbacthdr->PymtDescription,
                        'orderno' => $dbacthdr->orderno,
                        'ponum' => $dbacthdr->ponum,
                        'podate' => $dbacthdr->podate,
                        'termdays' => $dbacthdr->termdays,
                        'termmode' => $dbacthdr->termmode,
                        'deptcode' => $dbacthdr->deptcode,
                        'posteddate' => $dbacthdr->posteddate,
                        'approvedby' => $dbacthdr->approvedby,
                        'approveddate' => $dbacthdr->approveddate,
                        'approved_remark' => $dbacthdr->approved_remark,
                        'unallocated' => $dbacthdr->unallocated,
                        'datesend' => $dbacthdr->datesend,
                        'quoteno' => $dbacthdr->quoteno,
                        'preparedby' => $dbacthdr->preparedby,
                        'prepareddate' => $dbacthdr->prepareddate,
                        'cancelby' => $dbacthdr->cancelby,
                        'canceldate' => $dbacthdr->canceldate,
                        'cancelled_remark' => $dbacthdr->cancelled_remark,
                        'pointofsales' => $dbacthdr->pointofsales,
                        'doctorcode' => $dbacthdr->doctorcode,
                        'LHDNStatus' => $dbacthdr->LHDNStatus,
                        'LHDNSubID' => $dbacthdr->LHDNSubID,
                        'LHDNCodeNo' => $dbacthdr->LHDNCodeNo,
                        'LHDNDocID' => $dbacthdr->LHDNDocID,
                        'LHDNSubBy' => $dbacthdr->LHDNSubBy,
                        'category' => $dbacthdr->category,
                        'categorydept' => $dbacthdr->categorydept,
                    ]);

            DB::table('debtor.dbacthdr')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => $dbacthdr->source,
                        'trantype' => $dbacthdr->trantype,
                        'auditno' => $auditno,
                        'lineno_' => $dbacthdr->lineno_,
                        'amount' => $dbacthdr->outamount,
                        'outamount' => $dbacthdr->outamount,
                        'recstatus' => $dbacthdr->recstatus,
                        'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'entrytime' => $dbacthdr->entrytime,
                        'entryuser' => $dbacthdr->entryuser,
                        'reference' => $dbacthdr->reference,
                        'recptno' => $dbacthdr->recptno,
                        'paymode' => $dbacthdr->paymode,
                        'tillcode' => $dbacthdr->tillcode,
                        'tillno' => $dbacthdr->tillno,
                        'debtortype' => $dbacthdr->debtortype,
                        'debtorcode' => $dbacthdr->debtorcode,
                        'payercode' => $dbacthdr->payercode,
                        'billdebtor' => $dbacthdr->billdebtor,
                        'remark' => $dbacthdr->remark,
                        'mrn' => $patmast->NewMrn,
                        'episno' => $dbacthdr->episno,
                        'authno' => $dbacthdr->authno,
                        'expdate' => $dbacthdr->expdate,
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser' => 'SYSTEM',
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => 'SYSTEM',
                        'deldate' => $dbacthdr->deldate,
                        'deluser' => $dbacthdr->deluser,
                        'epistype' => $dbacthdr->epistype,
                        'cbflag' => $dbacthdr->cbflag,
                        'conversion' => $dbacthdr->conversion,
                        'payername' => $dbacthdr->payername,
                        'hdrtype' => $dbacthdr->hdrtype,
                        'currency' => $dbacthdr->currency,
                        'rate' => $dbacthdr->rate,
                        'unit' => $dbacthdr->unit,
                        'invno' => $dbacthdr->invno,
                        'paytype' => $dbacthdr->paytype,
                        'bankcharges' => $dbacthdr->bankcharges,
                        'RCCASHbalance' => $dbacthdr->RCCASHbalance,
                        'RCOSbalance' => $dbacthdr->RCOSbalance,
                        'RCFinalbalance' => $dbacthdr->RCFinalbalance,
                        'PymtDescription' => $dbacthdr->PymtDescription,
                        'orderno' => $dbacthdr->orderno,
                        'ponum' => $dbacthdr->ponum,
                        'podate' => $dbacthdr->podate,
                        'termdays' => $dbacthdr->termdays,
                        'termmode' => $dbacthdr->termmode,
                        'deptcode' => $dbacthdr->deptcode,
                        'posteddate' => $dbacthdr->posteddate,
                        'approvedby' => $dbacthdr->approvedby,
                        'approveddate' => $dbacthdr->approveddate,
                        'approved_remark' => $dbacthdr->approved_remark,
                        'unallocated' => $dbacthdr->unallocated,
                        'datesend' => $dbacthdr->datesend,
                        'quoteno' => $dbacthdr->quoteno,
                        'preparedby' => $dbacthdr->preparedby,
                        'prepareddate' => $dbacthdr->prepareddate,
                        'cancelby' => $dbacthdr->cancelby,
                        'canceldate' => $dbacthdr->canceldate,
                        'cancelled_remark' => $dbacthdr->cancelled_remark,
                        'pointofsales' => $dbacthdr->pointofsales,
                        'doctorcode' => $dbacthdr->doctorcode,
                        'LHDNStatus' => $dbacthdr->LHDNStatus,
                        'LHDNSubID' => $dbacthdr->LHDNSubID,
                        'LHDNCodeNo' => $dbacthdr->LHDNCodeNo,
                        'LHDNDocID' => $dbacthdr->LHDNDocID,
                        'LHDNSubBy' => $dbacthdr->LHDNSubBy,
                        'category' => $dbacthdr->category,
                        'categorydept' => $dbacthdr->categorydept,
                    ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_fromdebtormast($payercode){
        $obj = DB::table('debtor.debtormast')
                ->select('actdebglacc','actdebccode','depccode','depglacc')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$payercode);

        if(!$obj->exists()){
            dd($payercode);
        }else{
            $obj = $obj->first();
        }

        return $obj;
    }

    public function gltran_frompaymode($paymode){
        $obj = DB::table('debtor.paymode')
                ->select('glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AR')
                ->where('paymode','=',$paymode)
                ->first();

        return $obj;
    }

    public function gltran_poliklinik(Request $request){
        DB::beginTransaction();

        try {

            $poliklinik = DB::table('recondb.poliklinik')
                                // ->where('compcode',session('compcode'))
                                ->where('source','PB')
                                ->where('trantype','IN')
                                ->get();

            foreach ($poliklinik as $dbacthdr_obj) {

                $posteddate = Carbon::createFromFormat('d/m/y',$dbacthdr_obj->date)->format('Y-m-d');

                $yearperiod = defaultController::getyearperiod_($posteddate);

                $dept_obj = $this->gltran_fromdept($dbacthdr_obj->tillcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                $drcostcode = $debtormast_obj->actdebccode;
                $dracc = $debtormast_obj->actdebglacc;
                $crcostcode = $dept_obj->costcode;
                $cracc = '10020540';

                $gltran = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->where('lineno_','=',1);

                if($gltran->exists()){
                    throw new \Exception("gltran already exists",500);
                }

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'auditno' => $dbacthdr_obj->auditno,
                        'lineno_' => 1,
                        'source' => 'PB',
                        'trantype' => $dbacthdr_obj->trantype,
                        'reference' => $dbacthdr_obj->reference,
                        'description' => $dbacthdr_obj->oldmrn,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => $dbacthdr_obj->amount,
                        'postdate' => $posteddate,
                        'adduser' => 'SYSTEM',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'idno' => null
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function gltran_dbacthistory(Request $request){
        DB::beginTransaction();

        try {

            $dbacthistory = DB::table('recondb.dbacthistory')
                                ->where('compcode',session('compcode'))
                                ->where('source','PB')
                                ->where('trantype','RD')
                                ->get();

            foreach ($dbacthistory as $dbacthdr_obj) {

                $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->posteddate);

                $dept_obj = $this->gltran_fromdept($dbacthdr_obj->deptcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);
                $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                
                $crcostcode = $debtormast_obj->depccode;
                $cracc = $debtormast_obj->depglacc;
                $drcostcode = $dept_obj->costcode;
                $dracc = $paymode_obj->glaccno;

                $gltran = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->where('lineno_','=',1);

                if($gltran->exists()){
                    throw new \Exception("gltran already exists",500);
                }

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'auditno' => $dbacthdr_obj->auditno,
                        'lineno_' => 1,
                        'source' => 'PB',
                        'trantype' => $dbacthdr_obj->trantype,
                        'reference' => $dbacthdr_obj->reference,
                        'description' => $dbacthdr_obj->recptno,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => $dbacthdr_obj->amount,
                        'postdate' => $dbacthdr_obj->posteddate,
                        'adduser' => 'SYSTEM',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'idno' => null
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function update_gltran_posteddate(Request $request){
        DB::beginTransaction();

        try {
            $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno as db_idno','cb.idno as cb_idno','cb.postdate','db.posteddate','db.entrydate')
                        ->join('finance.cbtran as cb', function($join){
                            $join = $join
                                        ->where('cb.compcode',session('compcode'))
                                        ->on('db.source','cb.source')
                                        ->on('db.trantype','cb.trantype')
                                        ->on('db.auditno','cb.auditno');
                        })
                        ->where('db.compcode',session('compcode'))
                        ->whereDate('db.posteddate','>=','2025-05-01')
                        ->whereColumn('db.posteddate','!=','db.entrydate')
                        ->get();

            foreach ($dbacthdr as $obj) {
                DB::table('finance.cbtran')
                    ->where('idno',$obj->cb_idno)
                    ->update([
                        'postdate' => $obj->entrydate
                    ]);

                DB::table('debtor.dbacthdr')
                    ->where('idno',$obj->db_idno)
                    ->update([
                        'posteddate' => $obj->entrydate
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function bankrecon_cbtran(Request $request){
        DB::beginTransaction();

        try {
            $bankrecadd = DB::table('recondb.bankrecadd')
                        ->get();

            foreach ($bankrecadd as $obj) {
                $cbtran = DB::table('finance.cbtran')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

                if(!$cbtran->exists()){

                    $explode = explode('/', $obj->date);
                    $newdate = $explode[2].'-'.$explode[1].'-'.$explode[0];
                    $year = intval($explode[2]);
                    $period = intval($explode[1]);

                    DB::table('finance.cbtran')
                        ->insert([  
                            'compcode' => session('compcode'), 
                            'bankcode' => 'MBBUKMM', 
                            'source' => $obj->source, 
                            'trantype' => $obj->trantype, 
                            'auditno' => $obj->auditno, 
                            'postdate' => $newdate,
                            'year' => $year, 
                            'period' => $period, 
                            'cheqno' => $obj->cheqno, 
                            'amount' => $obj->amount, 
                            'remarks' => $obj->remark, 
                            'upduser' => 'SYSTEM_BR', 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            // 'reference' => 'Receipt Payment :'. ' ' .$request->dbacthdr_payername, 
                            'recstatus' => 'ACTIVE' 
                        ]);
                }
            }

            $bankrecsubtract = DB::table('recondb.bankrecsubtract')
                        ->get();

            foreach ($bankrecsubtract as $obj) {
                $cbtran = DB::table('finance.cbtran')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

                if(!$cbtran->exists()){

                    $explode = explode('/', $obj->date);
                    $newdate = $explode[2].'-'.$explode[1].'-'.$explode[0];
                    $year = intval($explode[2]);
                    $period = intval($explode[1]);

                    DB::table('finance.cbtran')
                        ->insert([  
                            'compcode' => session('compcode'), 
                            'bankcode' => 'MBBUKMM', 
                            'source' => $obj->source, 
                            'trantype' => $obj->trantype, 
                            'auditno' => $obj->auditno, 
                            'postdate' => $newdate,
                            'year' => $year, 
                            'period' => $period, 
                            'cheqno' => $obj->cheqno, 
                            'amount' => $obj->amount, 
                            'remarks' => $obj->remark, 
                            'upduser' => 'SYSTEM_BR', 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            // 'reference' => 'Receipt Payment :'. ' ' .$request->dbacthdr_payername, 
                            'recstatus' => 'ACTIVE' 
                        ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function cbtran_todel(Request $request){
        DB::beginTransaction();

        try {
            $todel = DB::table('recondb.cbtran_todel')
                        ->get();

            foreach ($todel as $obj) {
                $cbtran = DB::table('finance.cbtran')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

                if($cbtran->exists()){
                    DB::table('finance.cbtran')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno)
                            ->update([
                                'compcode' => 'xx'
                            ]);
                    dump('del: '.$obj->auditno);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function migratepoli(Request $request){
        DB::beginTransaction();

        try {
            $migratepoli = DB::table('recondb.migratepoli')
                        ->get();

            foreach ($migratepoli as $obj) {
                $cbtran = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

                if($cbtran->exists()){
                    DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno)
                            ->update([
                                'tillcode' => $obj->poliklinik
                            ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function grtstatus(Request $request){
        DB::beginTransaction();

        try {
            $grt = DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('trantype','grt')
                        ->where('recstatus','POSTED')
                        ->get();

            foreach ($grt as $obj) {
                $grn = DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('trantype','grn')
                        ->where('prdept',$obj->prdept)
                        ->where('docno',$obj->srcdocno);

                if($grn->exists()){
                    $grn = $grn->first();
                    $purord = DB::table('material.purordhd')
                            ->where('compcode',session('compcode'))
                            ->where('prdept',$grn->prdept)
                            ->where('purordno',$grn->srcdocno);

                    if($purord->exists()){
                        DB::table('material.purordhd')
                            ->where('compcode',session('compcode'))
                            ->where('prdept',$grn->prdept)
                            ->where('purordno',$grn->srcdocno)
                            ->update([
                                'recstatus' => 'PARTIAL'
                            ]);
                    }
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function gltran_jnl(Request $request){
        DB::beginTransaction();

        try {
            $gljnlhdr = DB::table('finance.gljnlhdr')
                        ->where('compcode',session('compcode'))
                        ->where('docdate','>=','2025-05-01')
                        ->where('docdate','<=','2025-05-31')
                        ->where('recstatus','POSTED')
                        ->get();

            foreach ($gljnlhdr as $obj) {
                $gltran = DB::table('finance.gltran')
                        ->where('compcode',session('compcode'))
                        ->where('trantype','JNL')
                        ->where('source','GL')
                        ->where('auditno',$obj->auditno);

                if($gltran->exists()){
                        DB::table('finance.gltran')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','JNL')
                            ->where('source','GL')
                            ->where('auditno',$obj->auditno)
                            ->update([
                                'postdate' => $obj->postdate,
                                'year' => '2025',
                                'period' => '5'
                            ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function nama_pbin(Request $request){
        DB::beginTransaction();

        try {
            $arconvert = DB::table('recondb.arconvert')
                        ->where('oldmrn','=','')
                        ->where('source','PB')
                        ->where('trantype','IN')
                        ->get();

            foreach ($arconvert as $obj) {
                DB::table('debtor.dbacthdr')
                    ->where('compcode',session('compcode'))
                    ->where('source',$obj->source)
                    ->where('trantype',$obj->trantype)
                    ->where('auditno',$obj->auditno)
                    ->update([
                        'payername' => $obj->name
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function cbdtl_(Request $request){
        DB::beginTransaction();

        try {

            $cbdtl = DB::table('finance.cbdtl')
                            ->where('compcode',session('compcode'))
                            ->where('source','CM')
                            ->whereIn('trantype',['BS','BD','BQ'])
                            ->get();

            foreach ($cbdtl as $obj) {
                DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->refsrc)
                            ->where('trantype',$obj->reftrantype)
                            ->where('auditno',$obj->refauditno)
                            ->update([
                                'cbflag' => 1
                            ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function allocation_btlkn(Request $request){
        DB::beginTransaction();

        try {
            $dballoc = DB::table('debtor.dballoc')
                        ->where('compcode',session('compcode'))
                        ->where('docsource','pb')
                        ->where('doctrantype','cn')
                        ->where('docauditno','5200070')
                        ->get();

            $auditno = [];
            foreach ($dballoc as $obj) {
                if (in_array($obj->refauditno, $auditno)) {
                    DB::table('debtor.dballoc')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$obj->idno)
                        ->update([
                            'compcode' => 'xx'
                        ]);
                }else{
                    array_push($auditno,$obj->refauditno);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function qtyonhandxsama(Request $request){
        DB::beginTransaction();

        try {
            $product = DB::table('material.product as p')
                            ->select('p.itemcode','p.qtyonhand as qty_p','s.qtyonhand as qty_s')
                            ->where('p.qtyonhand','!=','s.qtyonhand')
                            ->where('p.compcode',session('compcode'))
                            ->where('p.unit',"w'house")
                            ->join('material.stockloc as s', function($join) use ($request){
                                $join = $join->on('s.itemcode','p.itemcode')
                                              ->on('s.uomcode','p.uomcode')
                                              ->on('p.qtyonhand','!=','s.qtyonhand')
                                              ->where('s.unit',"w'house")
                                              ->where('s.deptcode','FKWSTR')
                                              ->where('s.year','2025')
                                              ->where('s.compcode',session('compcode'));
                            })
                            ->get();

            foreach ($product as $obj) {
                $diff = intval($obj->qty_p) - intval($obj->qty_s);
                $obj->diff = $diff;

                // $ivtxndt = DB::table('material.ivtxndt')
                //             ->where('trantype','PHYCNT')
                //             ->where('compcode',session('compcode'))
                //             ->where('recno','5204211')
                //             ->where('itemcode',$obj->itemcode);

                // if(!$ivtxndt->exists()){
                //     throw new \Exception("ivtxndt xde: ".$obj->itemcode);
                // }
                // $ivtxndt =  $ivtxndt->first();

                // $real = $ivtxndt->txnqty - $diff;
                // $realamt = $real * $ivtxndt->netprice;

                // DB::table('material.ivtxndt')
                //             ->where('trantype','PHYCNT')
                //             ->where('compcode',session('compcode'))
                //             ->where('recno','5204211')
                //             ->where('itemcode',$obj->itemcode)
                //             ->update([
                //                 'txnqty' => $real,
                //                 'amount' => $realamt,
                //                 'totamount' => $realamt
                //             ]);
            }

            DB::commit();
            dd($product);

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function faregister_dept(Request $request){
        DB::beginTransaction();

        try {

            $faregister = DB::table('finance.faregister')
                        ->where('compcode',session('compcode'))
                        ->get();

            foreach ($faregister as $obj) {
                $faregister_ = DB::table('recondb.faregister_dept')
                                    ->where('assetno',$obj->assetno);

                if($faregister_->exists()){
                    $faregister_ = $faregister_->first();

                    DB::table('finance.faregister')
                        ->where('compcode',session('compcode'))
                        ->where('assetno',$obj->assetno)
                        ->update([
                            'deptcode' => $faregister_->deptcode
                        ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function gltran_fa_load(Request $request){
        DB::beginTransaction();

        try {

            $gltran_fa = DB::table('recondb.gltran_fa')
                        ->get();

            foreach ($gltran_fa as $obj) {
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'auditno' => $obj->auditno,
                        'lineno_' => $obj->lineno_,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'reference' => $obj->reference,
                        'description' => $obj->description,
                        'year' => $obj->year,
                        'period' => $obj->period,
                        'drcostcode' => $obj->drcostcode,
                        'crcostcode' => $obj->crcostcode,
                        'dracc' => $obj->dracc,
                        'cracc' => $obj->cracc,
                        'amount' => $obj->amount,
                        'idno' => $obj->idno,
                        'postdate' => $obj->postdate,
                        'adduser' => 'system_1807',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function rd_mrn_tukar(Request $request){
        DB::beginTransaction();

        try {

            $dbacthdr = DB::table('debtor.dbacthdr')
                            // ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype','RD')
                            ->get();

            foreach ($dbacthdr as $obj) {
                $pat_mast = DB::table('hisdb.pat_mast')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$obj->mrn);

                if($pat_mast->exists()){

                    $pat_mast = $pat_mast->first();

                    DB::table('debtor.dbacthdr')
                        ->where('idno',$obj->idno)
                        ->update([
                            'mrn'=>$pat_mast->NewMrn
                        ]);
                }else{
                    dump($obj->mrn);
                }

            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function dballoc_2bl_betulkn(Request $request){
        DB::beginTransaction();

        $auditno = ['0077489','0077490','0077491','0077492','0077493','0077494','0077495','0077496','0077497','0077498','0077499','0077500','0077501','0077502','0077503','0077504','0077505','0077506','0077507','0077508','0077509','0077510','0077511','0077512','0077513','0077514','0077515','0077516','0077517','0077518','0077519','0077520','0077521','0077522','0077523','0077524','0077525','0077526','0077527','0077528','0077529','0077530','0077531'];

        try {

            $dballoc = DB::table('debtor.dballoc')
                            ->where('compcode',session('compcode'))
                            ->where('refsource','PB')
                            ->where('reftrantype','IN')
                            ->whereIn('refauditno',$auditno)
                            ->get();

            $dbl=[];
            foreach ($dballoc as $obj) {
                // dump($obj);
                $dballoc2 = DB::table('debtor.dballoc')
                            ->where('compcode',session('compcode'))
                            ->where('refsource','PB')
                            ->where('reftrantype','IN')
                            ->where('refauditno',$obj->refauditno)
                            ->orderBy('adddate','desc')
                            ->first();

                if(!in_array($obj->refauditno, $dbl)){
                    DB::table('debtor.dballoc')
                            // ->where('compcode',session('compcode'))
                            // ->where('refsource','PB')
                            // ->where('reftrantype','IN')
                            // ->where('refauditno',$obj->refauditno)
                            ->where('idno',$dballoc2->idno)
                            // ->first();
                            ->update([
                                'compcode' => 'XX'
                            ]);
                }

                array_push($dbl, $obj->refauditno);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function netmvqty_betulkan(Request $request){
        DB::beginTransaction();
        $dept = 'fkwstr';
        $year = 2025;
        $month = 7;
        $itemcode = ['KW000136','KW000158','KW000342','KW001013','KW001377','KW001171','KW001404','KW000244','KW000361'];

        try {

            $stockloc = DB::table('material.stockloc as s')
                            ->select('s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','p.avgcost')
                            ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode')
                                              ->where('p.avgcost','!=',0)
                                              ->where('p.compcode',session('compcode'));
                            })
                            ->where('s.compcode',session('compcode'))
                            ->where('s.deptcode',$dept)
                            ->where('s.year',$year)
                            ->whereIn('s.itemcode',$itemcode)
                            ->get();

            $x=1;
            foreach ($stockloc as $obj) {
                $array_obj = (array)$obj;
                $qtyonhand = $array_obj['qtyonhand'];

                $open_balqty = $array_obj['openbalqty'];

                $until = intval($month);


                for ($from = 1; $from <= $until; $from++) { 
                    $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
                }

                if($open_balqty != $qtyonhand){
                    dump($x.' - '.$array_obj['itemcode'].' qtyonhand:'.$qtyonhand.' real:'.$open_balqty);
                    $x = $x + 1;
                    $var = $qtyonhand-$open_balqty;

                    $netmvqty7 = $array_obj['netmvqty7'] + $var;


                    DB::table('material.stockloc as s')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$array_obj['idno'])
                            ->update([
                                'netmvqty7' => $netmvqty7
                            ]);
                }


            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function check_netmvqty_netmvval_allitem(Request $request){
        DB::beginTransaction();

        try {
            $ret_array = [];
            $class_ = new stdClass();

            $deptcode=$request->deptcode;
            if(empty($deptcode)){
                return view('test.test_netmvqty_calc',compact('ret_array'));
                dd('no deptcode');
            }
            $period=intval($request->period);
            if(empty($period)){
                return view('test.test_netmvqty_calc',compact('ret_array'));
                dd('no period');
            }

            $commit=intval($request->commit);
            if(empty($commit)){
                $commit = 0;
            }
            dd($commit);

            $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
            $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');

            $stockloc = DB::table('material.stockloc as s')
                        ->select('s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->where('s.compcode',session('compcode'))
                        // ->where('itemcode',$itemcode)
                        ->join('material.product as p', function($join) use ($request){
                            $join = $join->on('p.itemcode', '=', 's.itemcode')
                                            ->on('p.uomcode','=','s.uomcode')
                                            ->where('p.recstatus','=','ACTIVE')
                                            ->where('p.compcode',session('compcode'));
                        })
                        ->where('s.deptcode',$deptcode)
                        ->where('s.year','2025')
                        ->orderBy('s.idno', 'DESC')
                        ->get();

            $x = 1;
            foreach ($stockloc as $key => $value) {
                $value_array = (array)$value;

                // $product = DB::table('material.product')
                //                 ->where('compcode','9B')
                //                 ->where('itemcode',$obj->itemcode)
                //                 ->where('uomcode',$obj->uomcode)
                //                 ->first();

                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->get();

                $add = 0;
                $add2 = 0;
                foreach ($ivtxndt as $key => $value) {
                    $ivtxntype = DB::table('material.ivtxntype')
                                        ->where('compcode',session('compcode'))
                                        ->where('trantype',$value->trantype)
                                        ->first();

                    $crdbfl = $ivtxntype->crdbfl;

                    if($crdbfl == 'In'){
                        $add = $add + $value->txnqty;
                        $add2 = $add2 + $value->amount;
                    }else{
                        $add = $add - $value->txnqty;
                        $add2 = $add2 - $value->amount;
                    }
                }

                $all = $add - $minus;

                $ivdspdt2 = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->sum('amount');
                $minus2 = $ivdspdt2;

                $all2 = $add2 - $minus2;
                $all2 = round($all2,2);

                if(!$this->floatEquals($value_array['netmvqty'.$period], $all) || !$this->floatEquals($value_array['netmvval'.$period], $all2)){

                    $class_->itemcode = 'HAZMAN.YUSOF@GMAIL.COM';
                    $class_->system_qty = 'PV';
                    $class_->real_qty = '111';
                    $class_->system_val = 'PV';
                    $class_->real_val = '111';
                    array_push($array, $obj);

                    dump($x.'. '.$value->itemcode.' -> SAVED netmvqty'.$period.' => '.$value_array['netmvqty'.$period].' -> SAVED netmvval'.$period.' => '.$value_array['netmvval'.$period] );
                    dump($x.'. '.$value->itemcode.' -> REAL netmvqty'.$period.' => '.$all.' -> REAL netmvval => '.$all2);
                    $x++;

                    if(intval($period) > 5){
                        $updarr = [
                                'netmvqty'.$period => $all,
                                'netmvval'.$period => $all2
                            ];
                    }else{
                        $updarr = [
                                'netmvqty'.$period => $all,
                                // 'netmvval'.$period => $all2
                            ];
                    }

                    DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('deptcode',$value->deptcode)
                            ->where('year','2025')
                            ->update($updarr);
                }

                // dump($value_array['netmvqty'.$period] != $all);
                // dump('netmvval'.$period.' => '.$all2);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function btlkan_thqty_stocktake(Request $request){
        DB::beginTransaction();

        try {
            $deptcode='IMP';
            $period=9;
            $day_start = '2025-09-01';
            $day_end = '2025-09-20';
            $day_now = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');

            $phycntdt = DB::table('material.phycntdt')
                            ->where('compcode',session('compcode'))
                            ->where('recno','5205667')
                            ->get();

            foreach ($phycntdt as $obj) {

                $stockloc = DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('itemcode',$obj->itemcode)
                        ->where('uomcode',$obj->uomcode)
                        ->where('deptcode',$deptcode)
                        ->where('year','2025')
                        ->first();

                $openbalqty = $stockloc->openbalqty+$stockloc->netmvqty1+$stockloc->netmvqty2+$stockloc->netmvqty3+$stockloc->netmvqty4+$stockloc->netmvqty5+$stockloc->netmvqty6+$stockloc->netmvqty7+$stockloc->netmvqty8;
                // dd($openbalqty);

                $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->sum('txnqty');
                $minus = $ivdspdt;

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','!=','PHYCNT')
                            ->where('itemcode',$obj->itemcode)
                            ->where('trandate','>=',$day_start)
                            ->where('trandate','<=',$day_end)
                            ->get();

                $add = 0;
                foreach ($ivtxndt as $key => $value) {
                    $ivtxntype = DB::table('material.ivtxntype')
                                        ->where('compcode',session('compcode'))
                                        ->where('trantype',$value->trantype)
                                        ->first();

                    $crdbfl = $ivtxntype->crdbfl;

                    if($crdbfl == 'In'){
                        $add = $add + $value->txnqty;
                    }else{
                        $add = $add - $value->txnqty;
                    }
                }

                $all = $add - $minus;
                $real_thqty = $openbalqty + $all;
                $vrqty = $obj->phyqty - $real_thqty;

                DB::table('material.phycntdt')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$obj->idno)
                            ->update([
                                'thyqty' => $real_thqty,
                                'vrqty' => $vrqty
                            ]);

                $amount = round($vrqty * floatval($obj->unitcost), 2);
                DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$obj->recno)
                            ->where('lineno_',$obj->lineno_)
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            ->update([
                                'txnqty' => $vrqty,
                                'amount' => $amount
                            ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function check_qtyonhand_versus_netmvqty(Request $request){
        DB::beginTransaction();

        try {
            $deptcode=$request->deptcode;
            if(empty($deptcode)){
                dd('no deptcode');
            }
            $year=2025;

            $stockloc = DB::table('material.stockloc as s')
                        ->select('s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->where('s.compcode',session('compcode'))
                        // ->where('itemcode',$itemcode)
                        ->join('material.product as p', function($join) use ($request){
                            $join = $join->on('p.itemcode', '=', 's.itemcode')
                                            ->on('p.uomcode','=','s.uomcode')
                                            ->where('p.recstatus','=','ACTIVE')
                                            ->where('p.compcode',session('compcode'));
                        })
                        ->where('s.deptcode',$deptcode)
                        ->where('s.year','2025')
                        ->orderBy('s.idno', 'DESC')
                        ->get();

            $x=1;
            foreach ($stockloc as $obj) {
                $qtyonhand = $obj->qtyonhand;
                $real_qtyonhand = $obj->openbalqty + $obj->netmvqty1 + $obj->netmvqty2 + $obj->netmvqty3 + $obj->netmvqty4 + $obj->netmvqty5 + $obj->netmvqty6 + $obj->netmvqty7 + $obj->netmvqty8 + $obj->netmvqty9 + $obj->netmvqty10 + $obj->netmvqty11 + $obj->netmvqty12;
                if($qtyonhand != $real_qtyonhand){
                    dump($x.'. '.$obj->itemcode.' => '.$qtyonhand.' vs Real '.$real_qtyonhand);
                    $x++;

                    if($request->commit == 1){
                        DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode',$obj->uomcode)
                                ->where('deptcode',$deptcode)
                                ->where('year','2025')
                                ->update([
                                    'qtyonhand' => $real_qtyonhand
                                ]);

                        DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$obj->itemcode)
                                ->where('uomcode',$obj->uomcode)
                                ->update([
                                    'qtyonhand' => $real_qtyonhand
                                ]);
                    }
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function check_product_chgmast_stockloc_xsama_uom(Request $request){
        DB::beginTransaction();

        try {

            $year=2025;
            $dept = $request->dept;

            if($dept == 'IMP'){
                $dept='IMP';
                $unit='IMP';
            }else if($dept == 'FKWSTR'){
                $dept='FKWSTR';
                $unit="W'HOUSE";
            }else if($dept == 'KHEALTH'){
                $dept='KHEALTH';
                $unit='KHEALTH';
            }else{
                dd('no dept');
            }

            $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',$unit)
                            ->get();

            $x = 1;
            foreach ($product as $p_obj) {
                $stockloc = DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$p_obj->itemcode)
                                ->where('deptcode',$dept)
                                ->where('uomcode','!=',$p_obj->uomcode);

                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                    dump($p_obj->itemcode.' - '.$p_obj->uomcode.' => stockloc UOM : '.$stockloc->uomcode);

                    $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno','5204211')
                            ->where('itemcode',$obj->itemcode);

                    if($ivtxndt->exists()){
                        dump($p_obj->itemcode.' - '.$p_obj->uomcode.' => ada dkt PHYCNT : '.$ivtxndt->uomcode);
                    }
                }

                $chgmast = DB::table('material.chgmast')
                                ->where('compcode',session('compcode'))
                                ->where('chgcode',$p_obj->itemcode)
                                ->where('uom','!=',$p_obj->uomcode);

                if($chgmast->exists()){
                    $chgmast = $chgmast->first();
                    dump($p_obj->itemcode.' - '.$p_obj->uomcode.' => chgmast UOM : '.$chgmast->uom);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function bill_vs_mrn(Request $request){
        DB::beginTransaction();

        try {
            $bill_vs_mrn = DB::table('recondb.bill_vs_mrn')
                            ->get();

            foreach ($bill_vs_mrn as $obj) {
                $dbacthdr = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source','PB')
                                ->where('trantype','IN')
                                ->where('auditno',$obj->auditno)
                                ->update([
                                    'mrn' => $obj->mrn
                                ]);

                $pat_mast = DB::table('hisdb.pat_mast')
                                ->where('compcode',session('compcode'))
                                ->where('newmrn',$obj->mrn);

                if(!$pat_mast->exists()){
                    $mrn_ = $this->recno('HIS','MRN');
                    DB::table('hisdb.pat_mast')
                        ->insert([
                            'CompCode' => session('compcode'),
                            'MRN' => $mrn_,
                            'Name' => strtoupper($obj->name),
                            'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur"),
                            'Active' => 1,
                            'AddUser' => 'SYSTEM',
                            'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'LastUser' => 'SYSTEM',
                            'NewMrn' => strtoupper($obj->mrn),
                            'PatClass' => 'HIS'
                        ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function compare_stocktake_csv(Request $request){
        DB::beginTransaction();

        try {

            $stocktake_csv = DB::table('recondb.stocktake_csv')
                            ->get();

            foreach ($stocktake_csv as $obj) {
                $phycntdt = DB::table('material.phycntdt')
                                ->where('compcode',session('compcode'))
                                ->where('recno','5204211')
                                ->where('itemcode',$obj->itemcode)
                                ->where('phyqty','!=',$obj->phycnt);

                if($phycntdt->exists()){
                    DB::table('material.phycntdt')
                                ->where('compcode',session('compcode'))
                                ->where('recno','5204211')
                                ->where('itemcode',$obj->itemcode)
                                ->update([
                                    'phyqty' => $obj->phycnt
                                ]);

                    dump($obj->itemcode);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function tukar_uom_product_csv(Request $request){
        DB::beginTransaction();

        try {

            DB::table('material.productmaster')
                ->where('compcode',session('compcode'))
                ->where('unit',"W'HOUSE")
                ->update([
                    'recstatus' => 'DEACTIVE'
                ]);
            DB::table('material.product')
                ->where('compcode',session('compcode'))
                ->where('unit',"W'HOUSE")
                ->update([
                    'recstatus' => 'DEACTIVE'
                ]);
            DB::table('hisdb.chgmast')
                ->where('compcode',session('compcode'))
                ->where('unit',"W'HOUSE")
                ->update([
                    'recstatus' => 'DEACTIVE'
                ]);
            DB::table('material.stockloc')
                ->where('compcode',session('compcode'))
                ->where('unit',"W'HOUSE")
                ->update([
                    'recstatus' => 'DEACTIVE'
                ]);

            $fkwstr = DB::table('recondb.fkwstr_pharmacy_allitem as p')
                            ->select('p.idno','p.itemcode','p.uomcode','u.convfactor')
                            ->leftjoin('material.uom as u', function($join) use ($request){
                                        $join = $join->on('p.uomcode', '=', 'u.uomcode')
                                                      ->where('u.compcode',session('compcode'));
                                    })
                            ->get();

            foreach ($fkwstr as $obj) {
                DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('hisdb.chgmast')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode)
                            ->update([
                                'uom' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('hisdb.chgprice')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode)
                            ->update([
                                'uom' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->where('deptcode','FKWSTR')
                            ->update([
                                'uomcode' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function compare_product_csv(Request $request){
        DB::beginTransaction();

        try {

            $fkwstr = DB::table('recondb.fkwstr_pharmacy_allitem as p')
                            ->select('p.idno','p.itemcode','p.uomcode','u.convfactor')
                            ->leftjoin('material.uom as u', function($join) use ($request){
                                        $join = $join->on('p.uomcode', '=', 'u.uomcode')
                                                      ->where('u.compcode',session('compcode'));
                                    })
                            ->get();

            foreach ($fkwstr as $obj) {

                $productmaster = DB::table('material.productmaster')
                            ->where('compcode',session('compcode'))
                            // ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode);

                if(!$productmaster->exists()){
                    dump('xde productmaster : '.$obj->itemcode);
                }

                $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode);

                if(!$product->exists()){
                    dump('xde product : '.$obj->itemcode);
                }
               
                $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode);
                            // ->where('uom',$obj->uomcode);

                if(!$chgmast->exists()){
                    dump('xde chgmast : '.$obj->itemcode);
                }
                $stockloc = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('year',"2025")
                            ->where('itemcode',$obj->itemcode)
                            // ->where('uomcode',$obj->uomcode)
                            ->where('deptcode','FKWSTR');

                if(!$stockloc->exists()){
                    dump('xde stockloc : '.$obj->itemcode);
                }

                //check lebih dari satu
                $productmaster = DB::table('material.productmaster')
                            ->where('compcode',session('compcode'))
                            // ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->count();

                if($productmaster > 1){
                    dump('2bl productmaster : '.$obj->itemcode);
                }

                $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->count();

                if($product > 1){
                    dump('2bl product : '.$obj->itemcode);
                }
               
                $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode)
                            ->count();
                            // ->where('uom',$obj->uomcode);

                if($chgmast > 1){
                    dump('2bl chgmast : '.$obj->itemcode);
                }
                $stockloc = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->where('year',"2025")
                            // ->where('uomcode',$obj->uomcode)
                            ->where('deptcode','FKWSTR')
                            ->count();

                if($stockloc > 1){
                    dump('2bl stockloc : '.$obj->itemcode);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function compare_product_np_csv(Request $request){
        DB::beginTransaction();

        try {

            $fkwstr = DB::table('recondb.fkwstr_nonpharmacy_itemcode as p')
                            ->select('p.idno','p.itemcode','p.uomcode','u.convfactor')
                            ->leftjoin('material.uom as u', function($join) use ($request){
                                        $join = $join->on('p.uomcode', '=', 'u.uomcode')
                                                      ->where('u.compcode',session('compcode'));
                                    })
                            ->get();

            foreach ($fkwstr as $obj) {

                $uomcode = DB::table('material.uom')
                            // ->where('compcode',session('compcode'))
                            ->where('uomcode',$obj->uomcode);

                if(!$uomcode->exists()){
                    dump('xde uomcode : '.$obj->uomcode);
                }

                $productmaster = DB::table('material.productmaster')
                            // ->where('unit',"W'HOUSE")
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode);

                if(!$productmaster->exists()){
                    dump('xde productmaster : '.$obj->itemcode);
                }
                $product = DB::table('material.product')
                            ->where('unit',"W'HOUSE")
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode);
                            // ->where('uomcode',$obj->uomcode);

                if(!$product->exists()){
                    dump('xde product : '.$obj->itemcode);
                }
                $chgmast = DB::table('hisdb.chgmast')
                            ->where('unit',"W'HOUSE")
                            ->where('compcode',session('compcode'))
                            ->where('chgcode',$obj->itemcode);
                            // ->where('uom',$obj->uomcode);

                if(!$chgmast->exists()){
                    dump('xde chgmast : '.$obj->itemcode);
                }
                $stockloc = DB::table('material.stockloc')
                            ->where('unit',"W'HOUSE")
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            // ->where('uomcode',$obj->uomcode)
                            ->where('deptcode','FKWSTR');

                if(!$stockloc->exists()){
                    dump('xde stockloc : '.$obj->itemcode);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function tukar_uom_product_np_csv(Request $request){
        DB::beginTransaction();

        try {

            $fkwstr = DB::table('recondb.fkwstr_nonpharmacy_itemcode as p')
                            ->select('p.idno','p.itemcode','p.uomcode','u.convfactor')
                            ->leftjoin('material.uom as u', function($join) use ($request){
                                        $join = $join->on('p.uomcode', '=', 'u.uomcode')
                                                      ->where('u.compcode',session('compcode'));
                                    })
                            ->get();

            foreach ($fkwstr as $obj) {
                DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('hisdb.chgmast')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode)
                            ->update([
                                'uom' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('hisdb.chgprice')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('chgcode',$obj->itemcode)
                            ->update([
                                'uom' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);

                DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',"W'HOUSE")
                            ->where('itemcode',$obj->itemcode)
                            ->where('deptcode','FKWSTR')
                            ->update([
                                'uomcode' => $obj->uomcode,
                                'recstatus' => 'ACTIVE'
                            ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function chk_phycntdt_xde_ivtxndt(Request $request){
        DB::beginTransaction();

        try {

            $phycntdt = DB::table('material.phycntdt')
                            ->where('compcode',session('compcode'))
                            ->where('recno','5204211')
                            ->where('vrqty',0)
                            ->get();

            foreach ($phycntdt as $obj) {
                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$obj->recno)
                            ->where('lineno_',$obj->lineno_)
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode);

                if($ivtxndt->exists()){
                    dump($obj->itemcode);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function tukar_semua_ivtxntdt_idspdt_uombaru(Request $request){
        DB::beginTransaction();

        try {

            $fkwstr = DB::table('recondb.fkwstr_semua_itemcode as p')
                            ->select('p.idno','p.itemcode','p.uomcode','u.convfactor')
                            ->leftjoin('material.uom as u', function($join) use ($request){
                                        $join = $join->on('p.uomcode', '=', 'u.uomcode')
                                                      ->where('u.compcode',session('compcode'));
                                    })
                            ->get();

            foreach ($fkwstr as $obj) {
                $uomcode = preg_replace('/\s+/', '', $obj->uomcode);

                DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
                            // ->where('uomcode',$uomcode);

                DB::table('material.ivdspdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);

                DB::table('material.delorddt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);

                DB::table('material.purorddt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);

                DB::table('material.ivtmpdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);

                DB::table('material.ivreqdt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode,
                                'pouom' => $uomcode
                            ]);

            
            DB::table('material.stockexp')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->update([
                                'uomcode' => $uomcode
                            ]);
            
            DB::table('finance.salesum')
                            ->where('compcode',session('compcode'))
                            ->where('chggroup',$obj->itemcode)
                            ->update([
                                'uom' => $uomcode,
                                'uom_recv' => $obj->uomcode
                            ]);

            }

        DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function betulkan_uom_billsum(Request $request){
        DB::beginTransaction();

        try {

            $billsum = DB::table('debtor.billsum')
                            ->where('lastupdate','>','2025-07-01')
                            ->get();

            foreach ($billsum as $obj) {
                $uom_recv = preg_replace('/\s+/', '', $obj->uom_recv);

                 DB::table('debtor.billsum')
                            ->where('idno',$obj->idno)
                            ->update([
                                'uom_recv' => $uom_recv
                            ]);
            }

        DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function dmmc_einvoice_amik_invalid(Request $request){

        $log_link = DB::table('einvoice.log_link')
                        ->where('status','INVALID')
                        ->get();

        foreach ($log_link as $obj) {
            $log_qr = DB::table('einvoice.log_qr')
                        ->where('invno',$obj->invno)
                        ->orderBy('addate','desc')
                        ->first();

            $myresponse = json_decode($log_qr->myresponse);

            $text = 'overallStatus = '.$myresponse->overallStatus.', ';

            if(count($myresponse->documentSummary) != 0){
                $array_ = $myresponse->documentSummary[0];

                $text .= 'issuerTin = '.$array_->issuerTin.', ';

                $text .= 'issuerName = '.$array_->issuerName.', ';

                $text .= 'receiverId = '.$array_->receiverId.', ';

                $text .= 'dateTimeIssued = '.$array_->dateTimeIssued.', ';

                $text .= 'dateTimeReceived = '.$array_->dateTimeReceived.', ';

                $text .= 'dateTimeValidated = '.$array_->dateTimeValidated.', ';

                $text .= 'totalPayableAmount = '.$array_->totalPayableAmount;
            }

            DB::table('einvoice.log_link')
                        ->where('idno',$obj->idno)
                        ->update([
                            'message' => $text
                        ]);
        }
    }

    public function table_unique(Request $request){
        $table_name= 'material.delordhd';

        $duplicates = DB::table('material.delordhd as a')
                        ->join(DB::raw("(SELECT recno, compcode
                                         FROM material.delordhd
                                         GROUP BY recno, compcode
                                         HAVING COUNT(*) > 1) as b"), function($join) {
                            $join->on('a.recno', '=', 'b.recno')
                                 ->on('a.lineno', '=', 'b.lineno');
                        })
                        ->orderBy('a.recno')
                        ->orderBy('a.lineno')
                        ->get();
    }

    public function compare_stockbalance_report_vs_pnl(Request $request){

        $year = '2025';
        $period = $request->period;
        $dept = $request->dept;

        if(strtoupper($dept) == 'IMP'){
            $dept='IMP';
            $unit='IMP';
        }else if(strtoupper($dept) == 'FKWSTR'){
            $dept='FKWSTR';
            $unit="W'HOUSE";
        }else if(strtoupper($dept) == 'KHEALTH'){
            $dept='KHEALTH';
            $unit='KHEALTH';
        }else{
            dd('no dept');
        }

        $stockloc = DB::table('material.stockloc as s')
                        ->select('s.unit','p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','d.description as dept_desc','sc.description as unit_desc','gl.amount as gl_amount')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                // $join = $join->on('p.uomcode', '=', 's.uomcode');
                                // $join = $join->where('p.recstatus', '=', 'ACTIVE');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                $join = $join->where('p.groupcode', '=', 'STOCK');
                                $join = $join->on('p.unit', '=', 's.unit');
                            })
                        ->leftjoin('sysdb.department as d', function($join){
                            $join = $join->on('d.deptcode', '=', 's.deptcode');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('d.compcode', '=', session('compcode'));
                        })
                        ->leftjoin('sysdb.sector as sc', function($join){
                            $join = $join->on('sc.sectorcode', '=', 's.unit');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('sc.compcode', '=', session('compcode'));
                        })
                        ->leftjoin('finance.gltran as gl', function($join) use ($year,$period,$dept){
                            $join = $join->on('gl.reference', '=', 's.itemcode');
                            $join = $join->where('gl.year', '=', $year);
                            $join = $join->where('gl.period', '=', $period);
                            $join = $join->where('gl.idno', '=', $dept);
                            $join = $join->where('gl.compcode', '=', session('compcode'));
                        });
        $stockloc = $stockloc
                    ->where('s.compcode',session('compcode'))
                    ->where('s.stocktxntype','TR')
                    ->where('s.unit',$unit)
                    ->where('s.deptcode',$dept);

        $stockloc = $stockloc->where('s.compcode',session('compcode'))
                    ->where('s.year', '=', $year)
                    ->orderBy('s.deptcode', 'ASC')
                    ->orderBy('s.itemcode', 'ASC')
                    ->get();

        foreach ($stockloc as $obj) {

            $array_obj = (array)$obj;
            $close_balval = round($array_obj['openbalval'], 2);

            for ($from = 1; $from <= intval($period); $from++) { 
                $close_balval = round($close_balval, 2) + round($array_obj['netmvval'.$from], 2);
            }

            if(abs($array_obj['gl_amount'] - $close_balval) > 0.00001){
                dump($array_obj['itemcode'].' - gl_amount: '.$array_obj['gl_amount'].' - closebal_calc: '.$close_balval);
            }

        }
    }

    public function newic_pm_ke_dm(Request $request){
        $pm = DB::table('hisdb.pat_mast')
                ->where('compcode',session('compcode'))
                ->get();

        foreach ($pm as $value) {
            if(!empty($value->NewMrn)){

                $dm = DB::table('debtor.debtormast')
                        ->where('compcode',session('compcode'))
                        ->where('debtorcode',$value->NewMrn);

                if($dm->exists()){
                    dump($value->Newic);
                    DB::table('debtor.debtormast')
                        ->where('compcode',session('compcode'))
                        ->where('debtorcode',$value->NewMrn)
                        ->update([
                            'newic' => $value->Newic
                        ]);
                }
            }
        }
    }

    public function statecode_upd(Request $request){
        $dm = DB::table('debtor.debtormast')
                ->where('compcode',session('compcode'))
                ->whereNotNull('postcode')
                ->get();

        foreach ($dm as $obj) {
            $postcode = DB::table('hisdb.postcode')
                        ->where('compcode',session('compcode'))
                        ->where('postcode',$obj->postcode);

            if($postcode->exists()){
                $postcode=$postcode->first();

                $state = DB::table('hisdb.state')
                        ->where('compcode',session('compcode'))
                        ->where('description',strtoupper($postcode->statecode));

                if($state->exists()){
                    $state = $state->first();
                     DB::table('debtor.debtormast')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$obj->idno)
                            ->update([
                                'statecode' => $state->StateCode
                            ]);

                    echo  nl2br($state->StateCode."\n");
                }

            }

        }
    }

    public function len_len(Request $request){
        $dbacthistory = DB::table('recondb.dbacthistory')
                        ->get();

        foreach($dbacthistory as $obj){
            $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('newmrn',$obj->mrn);

            if(!$pat_mast->exists()){
                $mrn_ = $this->recno('HIS','MRN');
                DB::table('hisdb.pat_mast')
                    ->insert([
                        'CompCode' => session('compcode'),
                        'MRN' => $mrn_,
                        'Name' => strtoupper($obj->payername),
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur"),
                        'Active' => 1,
                        'AddUser' => 'SYSTEM',
                        'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => 'SYSTEM',
                        'NewMrn' => strtoupper($obj->mrn),
                        'PatClass' => 'HIS'
                    ]);

                dump('insert mrn');
            }


            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

            if(!$dbacthdr->exists()){

                DB::table('debtor.dbacthdr')
                        ->insert([
                            'compcode' => $obj->compcode,
                            'source' => $obj->source,
                            'trantype' => $obj->trantype,
                            'auditno' => $obj->auditno,
                            'lineno_' => $obj->lineno_,
                            'amount' => $obj->amount,
                            'outamount' => $obj->outamount,
                            'entrydate' => $obj->entrydate,
                            'reference' => $obj->reference,
                            'debtorcode' => $obj->debtorcode,
                            'payercode' => $obj->payercode,
                            'billdebtor' => $obj->billdebtor,
                            'mrn' => $obj->mrn,
                            'episno' => $obj->episno,
                            'payername' => $obj->payername,
                        ]);

                dump('insert dbacthdr');
            }
        }
    }

    public function itemcode_avgcost(Request $request){
        $dm = DB::table('recondb.itemcode_avgcost')
                ->get();

        foreach ($dm as $obj) {
            $product = DB::table('material.product')
                        ->where('compcode',session('compcode'))
                        ->where('itemcode',$obj->itemcode)
                        ->get();

            DB::table('material.product')
                    ->where('compcode',session('compcode'))
                    ->where('itemcode',$obj->itemcode)
                    ->update([
                        'avgcost' => $obj->avgcost
                    ]);
        }
    }

    public function staff_adv(Request $request){

        $compcode = $request->compcode;
        $month = $request->period;
        $year = $request->year;

        $firstDay = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $lastDay = Carbon::createFromDate($year, $month, 1)->endOfMonth()->startOfDay();

        $dballoc = DB::table('finance.dballoc')
                        ->where('compcode',$compcode)
                        ->where('docsource','PB')
                        ->where('doctrantype','PD')
                        ->where('recstatus','POSTED')
                        ->where('allocdate','>=',$firstDay)
                        ->where('allocdate','<=',$lastDay)
                        ->get();

        foreach ($apalloc as $obj) {
            $debtormast = DB::table('debtor.debtormast')
                            ->where('compcode',$compcode)
                            ->where('suppcode',$obj->suppcode)
                            ->first();

            $gltran = DB::table('finance.gltran')
                            ->where('compcode',$compcode)
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno)
                            ->where('lineno_',$obj->lineno_);

            if($gltran->exists()){
                DB::table('finance.gltran')
                    ->where('compcode',$compcode)
                    ->where('source',$obj->source)
                    ->where('trantype',$obj->trantype)
                    ->where('auditno',$obj->auditno)
                    ->where('lineno_',$obj->lineno_)
                    ->update([
                        'compcode' => $compcode,
                        'adduser' => 'SYSTEM',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $obj->auditno,
                        'lineno_' => $obj->lineno_,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'reference' => $obj->recptno,
                        'description' => 'ALLOCATION DEPOSIT',
                        'postdate' => $obj->allocdate,
                        'year' => $year,
                        'period' => $month,
                        'drcostcode' => $debtormast->depccode,
                        'dracc' => $debtormast->depglacc,
                        'crcostcode' => $debtormast->actdebccode,
                        'cracc' => $debtormast->actdebglacc,
                        'amount' => $obj->amount
                    ]);

            }else{
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $compcode,
                        'adduser' => 'SYSTEM',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $obj->auditno,
                        'lineno_' => $obj->lineno_,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'reference' => $obj->recptno,
                        'description' => 'ALLOCATION DEPOSIT',
                        'postdate' => $obj->allocdate,
                        'year' => $year,
                        'period' => $month,
                        'drcostcode' => $debtormast->depccode,
                        'dracc' => $debtormast->depglacc,
                        'crcostcode' => $debtormast->actdebccode,
                        'cracc' => $debtormast->actdebglacc,
                        'amount' => $obj->amount
                    ]);
            }
        }
    }

    public function gljnlhdr_add(Request $request){
        $header = DB::table('recondb.gljnlhdr')
                ->get();

        foreach ($header as $obj) {
            $gljnlhdr = DB::table('finance.gljnlhdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno);

            if($gljnlhdr->exists()){
                DB::table('finance.gljnlhdr')
                    ->where('compcode',session('compcode'))
                    ->where('source',$obj->source)
                    ->where('trantype',$obj->trantype)
                    ->where('auditno',$obj->auditno)
                    ->update([
                        'compcode' => $obj->compcode,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'auditno' => $obj->auditno,
                        'docno' => $obj->docno,
                        'description' => $obj->description,
                        'year' => $obj->year,
                        'period' => $obj->period,
                        'different' => $obj->different,
                        'recstatus' => $obj->recstatus,
                        'docdate' => $obj->docdate,
                        'postdate' => $obj->postdate,
                        'nprefid' => $obj->nprefid,
                        'lastuser' => $obj->lastuser,
                        'lastdate' => $obj->lastdate,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'unit' => $obj->unit,
                        'creditAmt' => $obj->creditAmt,
                        'debitAmt' => $obj->debitAmt,
                    ]);
            }else{
                DB::table('finance.gljnlhdr')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'auditno' => $obj->auditno,
                        'docno' => $obj->docno,
                        'description' => $obj->description,
                        'year' => $obj->year,
                        'period' => $obj->period,
                        'different' => $obj->different,
                        'recstatus' => $obj->recstatus,
                        'docdate' => $obj->docdate,
                        'postdate' => $obj->postdate,
                        'nprefid' => $obj->nprefid,
                        'lastuser' => $obj->lastuser,
                        'lastdate' => $obj->lastdate,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'unit' => $obj->unit,
                        'creditAmt' => $obj->creditAmt,
                        'debitAmt' => $obj->debitAmt,
                    ]);
            }
        }

        $detail = DB::table('recondb.gljnldtl')
                ->get();

        foreach ($detail as $obj) {
            $gljnldtl = DB::table('finance.gljnldtl')
                            ->where('compcode',session('compcode'))
                            ->where('source',$obj->source)
                            ->where('trantype',$obj->trantype)
                            ->where('auditno',$obj->auditno)
                            ->where('lineno_',$obj->lineno_);

            if($gljnldtl->exists()){
                DB::table('finance.gljnldtl')
                    ->where('compcode',session('compcode'))
                    ->where('source',$obj->source)
                    ->where('trantype',$obj->trantype)
                    ->where('auditno',$obj->auditno)
                    ->where('lineno_',$obj->lineno_)
                    ->update([
                        'compcode' => $obj->compcode,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'auditno' => $obj->auditno,
                        'docno' => $obj->docno,
                        'lineno_' => $obj->lineno_,
                        'costcode' => $obj->costcode,
                        'glaccount' => $obj->glaccount,
                        'drcrsign' => $obj->drcrsign,
                        'amount' => $obj->amount,
                        'lastuser' => $obj->lastuser,
                        'lastdate' => $obj->lastdate,
                        'description' => $obj->description,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'recstatus' => $obj->recstatus,
                        'unit' => $obj->unit,
                    ]);
            }else{
                DB::table('finance.gljnldtl')
                    ->insert([
                        'compcode' => $obj->compcode,
                        'source' => $obj->source,
                        'trantype' => $obj->trantype,
                        'auditno' => $obj->auditno,
                        'docno' => $obj->docno,
                        'lineno_' => $obj->lineno_,
                        'costcode' => $obj->costcode,
                        'glaccount' => $obj->glaccount,
                        'drcrsign' => $obj->drcrsign,
                        'amount' => $obj->amount,
                        'lastuser' => $obj->lastuser,
                        'lastdate' => $obj->lastdate,
                        'description' => $obj->description,
                        'adduser' => $obj->adduser,
                        'adddate' => $obj->adddate,
                        'upduser' => $obj->upduser,
                        'upddate' => $obj->upddate,
                        'recstatus' => $obj->recstatus,
                        'unit' => $obj->unit,
                    ]);
            }
        }
    }

    public function gltran_fromsupp2($suppcode){

        $obj = DB::table("material.supplier")
                ->select('costcode','glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();

        return $obj;
    }

    public function gltran_fromdept_others($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_fromcategory($catcode){
        $obj = DB::table('material.category')
                ->select('expacct')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CR')
                ->where('catcode','=',$catcode)
                ->first();

        return $obj;
    }

    public function post_apadhoc(){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','AP')
                            ->where('trantype','=','DN')
                            ->where('auditno','=','5200020')
                            ->first();

        $apactdtl_obj = DB::table('finance.apactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$apacthdr_obj->source)
                            ->where('trantype','=',$apacthdr_obj->trantype)
                            ->where('auditno','=',$apacthdr_obj->auditno);

        if($apactdtl_obj->exists()){
            $apactdtl_get = $apactdtl_obj->get();

            foreach ($apactdtl_get as $key => $value){
                $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

                $category_obj = $this->gltran_fromcategory($value->category);
                $dept_obj = $this->gltran_fromdept_others($value->deptcode);
                $supp_obj = $this->gltran_fromsupp2($apacthdr_obj->payto);

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $apacthdr_obj->compcode,
                        'auditno' => $apacthdr_obj->auditno,
                        'lineno_' => $key+1,
                        'source' => $apacthdr_obj->source,
                        'trantype' => $apacthdr_obj->trantype,
                        'reference' => $value->document,
                        'description' => $apacthdr_obj->remarks,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $dept_obj->costcode,
                        'dracc' => $category_obj->expacct,
                        'crcostcode' => $supp_obj->costcode,
                        'cracc' => $supp_obj->glaccno,
                        'amount' => $value->amount,
                        'postdate' => $apacthdr_obj->postdate,
                        'adduser' => $apacthdr_obj->adduser,
                        'adddate' => $apacthdr_obj->adddate,
                        'idno' => null
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$category_obj->expacct,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$category_obj->expacct)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $value->amount + $gltranAmount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $dept_obj->costcode,
                            'glaccount' => $category_obj->expacct,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($supp_obj->costcode,$supp_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$supp_obj->costcode)
                        ->where('glaccount','=',$supp_obj->glaccno)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $supp_obj->costcode,
                            'glaccount' => $supp_obj->glaccno,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => - $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            }

        }
    }

    public function apalloc_osamt_in(Request $request){
        $commit = $request->commit;
        if($commit == null){
            $commit = false;
        }else{
            $commit = true;
        }
        
        $datefrom = $request->datefrom;
        if($datefrom == null){
            $datefrom = '2025-05-01';
        }

        $dateto = $request->dateto;
        if($dateto == null){
            $dateto = '2025-05-31';
        }

        $apacthdr = DB::table('finance.apacthdr as ap')
                        ->select('ap.idno','ap.source','ap.trantype','ap.auditno','ap.postdate','ap.source','ap.amount','ap.outamount')
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.source','AP')
                        ->where('ap.trantype','IN')
                        ->where('ap.recstatus','POSTED')
                        ->whereDate('ap.postdate','>=',$datefrom)
                        ->whereDate('ap.postdate','<=',$dateto)
                        ->get();

        $array = [];
        foreach ($apacthdr as $obj) {
            $osamt = $obj->amount;

            $apalloc = DB::table('finance.apalloc')
                        ->where('compcode',session('compcode'))
                        ->where('refsource',$obj->source)
                        ->where('reftrantype',$obj->trantype)
                        ->where('refauditno',$obj->auditno)
                        ->where('recstatus', 'POSTED')
                        ->get();

            foreach ($apalloc as $obj_alloc){
                $osamt = $osamt - $obj_alloc->allocamount;
            }

            if(!$this->floatEquals($obj->outamount,$osamt)){
                if($commit){
                    $obj->osamt_alloc = $osamt;
                    DB::table('finance.apacthdr as ap')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$obj->idno)
                        ->update([
                            'outamount' => $osamt
                        ]);
                }else{
                    $obj->osamt_alloc = $osamt;
                    array_push($array, $obj);
                }
            }
        }

        if(!$commit){
            return view('test.test2',compact('array'));
        }
    }

    public function apalloc_osamt_cn(Request $request){
        $commit = $request->commit;
        if($commit == null){
            $commit = false;
        }else{
            $commit = true;
        }

        $datefrom = $request->datefrom;
        if($datefrom == null){
            $datefrom = '2025-05-01';
        }

        $dateto = $request->dateto;
        if($dateto == null){
            $dateto = '2025-05-31';
        }

        $apacthdr = DB::table('finance.apacthdr as ap')
                        ->select('ap.idno','ap.source','ap.trantype','ap.auditno','ap.postdate','ap.source','ap.amount','ap.outamount')
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.source','AP')
                        ->where('ap.trantype','CN')
                        ->where('ap.recstatus','POSTED')
                        ->whereDate('ap.postdate','>=',$datefrom)
                        ->whereDate('ap.postdate','<=',$dateto)
                        ->get();

        $array = [];
        foreach ($apacthdr as $obj) {
            $osamt = $obj->amount;

            $apalloc = DB::table('finance.apalloc')
                        ->where('compcode',session('compcode'))
                        ->where('docsource',$obj->source)
                        ->where('doctrantype',$obj->trantype)
                        ->where('docauditno',$obj->auditno)
                        ->where('recstatus', 'POSTED')
                        ->get();

            foreach ($apalloc as $obj_alloc){
                $osamt = $osamt - $obj_alloc->allocamount;
            }

            if(!$this->floatEquals($obj->outamount,$osamt)){
                if($commit){
                    $obj->osamt_alloc = $osamt;
                    DB::table('finance.apacthdr as ap')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$obj->idno)
                        ->update([
                            'outamount' => $osamt
                        ]);
                }else{
                    $obj->osamt_alloc = $osamt;
                    array_push($array, $obj);
                }
            }
        }

        if(!$commit){
            return view('test.test2',compact('array'));
        }
    }

}