<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

use App\Jobs\SendEmailPR;

class TestController extends defaultController
{   

    public function __construct()
    {

    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'chgmast_invflag_tukar_dari_product':
                return $this->chgmast_invflag_tukar_dari_product($request);
            case 'load_discipline':
                return $this->load_discipline($request);
            case 'insert_phst':
                return $this->insert_phst($request);
            case 'debtortype_xde':
                return $this->debtortype_xde($request);
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

                // $exist = DB::table('sysdb.department')
                //             ->where('compcode','9A')
                //             ->where('deptcode',$deptcode)
                //             ->exists();

                // if(!$exist){
                //     DB::table('sysdb.department')
                //         ->insert([
                //             'compcode' => '9A',
                //             'deptcode' => $deptcode,
                //             'description' => $desc,
                //             'costcode' => $ccode,
                //             'purdept' => 0,
                //             'regdept' => 0,
                //             'chgdept' => 1,
                //             'warddept' => 0,
                //             'admdept' => 0,
                //             'dispdept' => 0,
                //             'sector' => 'NORTHA',
                //             'region' => 'CENTRAL',
                //             'storedept' => 0,
                //             'category' => 'HOSPITAL'
                //         ]);
                // }else{
                //     dump('dept exist: '.$deptcode);
                // }

                // $exist = DB::table('finance.costcenter')
                //             ->where('compcode','9A')
                //             ->where('costcode',$ccode)
                //             ->exists();

                // if(!$exist){
                //     DB::table('finance.costcenter')
                //         ->insert([
                //             'compcode' => '9A',
                //             'costcode' => $ccode,
                //             'description' => $desc,
                //             'recstatus' => 'ACTIVE'
                //         ]);
                // }else{
                //     dump('costcode exist: '.$deptcode);
                // }

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

}