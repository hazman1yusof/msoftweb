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

use App\Jobs\SendEmailPR;
use App\Mail\sendmaildefault;

class TestController extends defaultController
{   

    public function __construct(){

    }

    public function show(Request $request){
        // $pdf = new \Clegginabox\PDFMerger\PDFMerger;
        // $pdf->addPDF(public_path() . '/uploads/pdf_merge/pdf1.pdf', 'all');
        // $pdf->addPDF(public_path() . '/uploads/pdf_merge/pdf2.pdf', 'all');

        // $pdf->merge('file', public_path() . '/uploads/pdf_merge/merge_pdf.pdf', 'P');

        return view('test.test');
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
            // case 'chgmast_invflag_tukar_dari_product':
            //     return $this->chgmast_invflag_tukar_dari_product($request);
            // case 'load_discipline':
            //     return $this->load_discipline($request);
            // case 'insert_phst':
            //     return $this->insert_phst($request);
            // case 'debtortype_xde':
            //     return $this->debtortype_xde($request);
            // case 'set_class':
            //     return $this->set_class($request);
            // case 'set_stockloc_unit':
            //     return $this->set_stockloc_unit($request);
            // case 'test_alert_auth': //dah xperlu
            //     return $this->test_alert_auth($request);
            // case 'test_glmasdtl':
            //     return $this->test_glmasdtl($request);
            // case 'get_merge_pdf':
            //     return $this->get_merge_pdf($request);
            // case 'update_stockloc_uomcode':
            //     return $this->update_stockloc_uomcode($request);
            // case 'update_productmaster':
                // return $this->update_productmaster($request);
            // case 'update_stockexp':
            //     return $this->update_stockexp($request);
            // case 'del_stockexp':
            //     return $this->del_stockexp($request);
            // case 'test_email':
            //     return $this->test_email($request);
            // case 'update_supplier':
            //     return $this->update_supplier($request);
            // case 'update_chgmast':
            //     return $this->update_chgmast($request);
            case 'update_chgprice':
                return $this->update_chgprice($request);
            case 'betulkandb':
                return $this->betulkandb($request);
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
        $recipient = "hazman.yusof@gmail.com";
        Mail::to($recipient)->send(new sendmaildefault());
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

    public function betulkandb_dbacthdr(Request $request){
        DB::beginTransaction();
        try {
            
            $billsum = DB::table('debtor.billsum')
                            ->get();

            $i = 1;
            foreach ($billsum as $obj) {
                // $dbactdtl = DB::table('debtor.dbactdtl')
                //                     ->where('source','PB')
                //                     ->where('trantype','IN');

                $dbacthdr = DB::table('debtor.dbacthdr')
                                    ->where('source','PB')
                                    ->where('trantype','IN')
                                    ->where('auditno',$obj->billno);

                if(!$dbacthdr->exists()){

                    $amount = DB::table('debtor.billsum')
                                ->where('source','PB')
                                ->where('trantype','IN')
                                ->where('billno',$obj->billno)
                                ->sum('amount');


                    DB::table('debtor.dbacthdr')
                        ->insert([
                            'compcode' => '9B',
                            'source' => 'PB',
                            'trantype' => 'IN',
                            'auditno' => $obj->billno,
                            'lineno_' => 1,
                            'amount' => $amount,
                            'outamount' => $amount,
                            'recstatus' => $obj->recstatus,
                            'entrydate' => Carbon::parse($obj->lastupdate)->format('Y-m-d'),
                            // 'entrytime' => ,
                            // 'entryuser' => ,
                            // 'reference' => ,
                            // 'recptno' => ,
                            'paymode' => 'CASH',
                            // 'tillcode' => ,
                            // 'tillno' => ,
                            // 'debtortype' => ,
                            'debtorcode' => $obj->mrn,
                            'payercode' => $obj->mrn,
                            // 'billdebtor' => ,
                            // 'remark' => ,
                            'mrn' => $obj->mrn,
                            // 'episno' => ,
                            // 'authno' => ,
                            // 'expdate' => ,
                            // 'adddate' => ,
                            // 'adduser' => ,
                            // 'upddate' => ,
                            // 'upduser' => ,
                            // 'deldate' => ,
                            // 'deluser' => ,
                            // 'epistype' => ,
                            // 'cbflag' => ,
                            // 'conversion' => ,
                            // 'payername' => ,
                            'hdrtype' => 'OP',
                            // 'currency' => ,
                            // 'rate' => ,
                            'unit' => 'IMP',
                            // 'invno' => ,
                            'paytype' => '#F_TAB-CASH',
                            // 'bankcharges' => ,
                            // 'RCCASHbalance' => ,
                            // 'RCOSbalance' => ,
                            // 'RCFinalbalance' => ,
                            // 'PymtDescription' => ,
                            // 'orderno' => ,
                            // 'ponum' => ,
                            // 'podate' => ,
                            'termdays' => '30',
                            'termmode' => 'DAYS',
                            'deptcode' => 'IMP',
                            // 'posteddate' => ,
                            // 'approvedby' => ,
                            // 'approveddate' => ,
                            // 'approved_remark' => ,
                            // 'unallocated' => ,
                            // 'datesend' => ,
                            // 'quoteno' => ,
                            // 'preparedby' => ,
                            // 'prepareddate' => ,
                            // 'cancelby' => ,
                            // 'canceldate' => ,
                            // 'cancelled_remark' => ,
                            'pointofsales' => 0,
                            'doctorcode' => 'GP',
                        ]);

                    echo nl2br("$i. update dbacthdr: $obj->billno \n");
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

                if(!$apacthdr_pv->exists()){

                    $amount = DB::table('finance.apactdtl')
                            ->where('source','AP')
                            ->where('trantype','PV')
                            ->where('auditno',$obj->docauditno)
                            ->sum('amount');

                    DB::table('finance.apacthdr')
                        ->insert([
                            'compcode' => '9B',
                            'source' => 'AP',
                            'trantype' => 'PV',
                            // 'doctype' => 'Others',
                            'auditno' => $obj->docauditno,
                            'document' => $obj->reference,
                            'suppcode' => $obj->suppcode,
                            'payto' => $obj->suppcode,
                            'suppgroup' => 'TR',
                            // 'bankcode' => $obj->,
                            // 'paymode' => $obj->,
                            // 'cheqno' => $obj->,
                            // 'cheqdate' => $obj->,
                            'actdate' => $obj->lastupdate,
                            'recdate' => $obj->lastupdate,
                            // 'category' => $apactdtl->category,
                            'amount' => $amount,
                            'outamount' => 0,
                            'remarks' => $obj->remarks,
                            // 'postflag' => $obj->,
                            // 'doctorflag' => $obj->,
                            // 'stat' => $obj->,
                            // 'entryuser' => $obj->,
                            // 'entrytime' => $obj->,
                            // 'upduser' => $obj->,
                            // 'upddate' => $obj->,
                            // 'conversion' => $obj->,
                            // 'srcfrom' => $obj->,
                            // 'srcto' => $obj->,
                            // 'deptcode' => $apactdtl->deptcode,
                            // 'reconflg' => $obj->,
                            // 'effectdatefr' => $obj->,
                            // 'effectdateto' => $obj->,
                            // 'frequency' => $obj->,
                            // 'refsource' => $obj->,
                            // 'reftrantype' => $obj->,
                            // 'refauditno' => $obj->,
                            // 'pvno' => $obj->,
                            // 'entrydate' => $obj->,
                            'recstatus' => $obj->recstatus,
                            // 'adduser' => $obj->,
                            // 'adddate' => $obj->,
                            // 'reference' => $obj->,
                            // 'TaxClaimable' => $obj->,
                            'unit' => $obj->unit,
                            // 'allocdate' => $obj->,
                            // 'postuser' => $obj->,
                            // 'postdate' => $obj->,
                            // 'unallocated' => $obj->,
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

                echo nl2br("$i. update apacthdr: $obj->docauditno \n");
                $i++;
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

    public function betulkandb_purreqhd(Request $request){
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

    public function betulkandb(Request $request){
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
    
}