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

use App\Exports\check_cbtran_xde;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendEmailPV;
use App\Mail\sendmaildefault;

class MigrationController extends defaultController
{   

    public function __construct(){

    }

    public function table(Request $request){  
        switch($request->action){
            case 'chgmast_migrate':
                return $this->chgmast_migrate($request);
            case 'chgprice_migrate':
                return $this->chgprice_migrate($request);
            case 'docapptsession':
                return $this->docapptsession($request);
            default:
                return 'error happen..';
        }
    }

    public function chgmast_migrate(Request $request){
        DB::beginTransaction();

        try {

            $chgmast_m = DB::table('migration.chgmast')
                                ->get();

            foreach ($chgmast_m as $obj) {
                $exist = DB::table('hisdb.chgmast')
                            ->where('compcode','10A')
                            ->where('chgcode',$obj->chgcode)
                            ->exists();

                if(!$exist){
                    DB::table('hisdb.chgmast')
                        ->insert([
                            'compcode' => '10A',
                            'unit' => 'IMSC',
                            'chgcode' => $obj->chgcode,
                            'description' => $obj->description,
                            'brandname' => $obj->brandname,
                            'revcode' => null,
                            'uom' => $obj->uom,
                            'packqty' => null,
                            'invflag' => 0,
                            'overwrite' => 0,
                            'buom' => null,
                            'adduser' => 'SYSTEM',
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => null,
                            'lastupdate' => null,
                            'upduser' => null,
                            'upddate' => null,
                            'deluser' => null,
                            'deldate' => null,
                            'recstatus' => 'ACTIVE',
                            'lastfield' => null,
                            'doctorstat' => null,
                            'chgtype' => $obj->chgtype,
                            'chggroup' => $obj->chggroup,
                            'qflag' => 0,
                            'costcode' => null,
                            'chgflag' => 0,
                            'ipacccode' => null,
                            'opacccode' => null,
                            'revdept' => null,
                            'chgclass' => $obj->chgclass,
                            'costdept' => null,
                            'invgroup' => $obj->invgroup,
                            'apprccode' => null,
                            'appracct' => null,
                            'active' => 1,
                            'constype' => null,
                            'dosage' => null,
                            'druggrcode' => null,
                            'subgroup' => null,
                            'stockcode' => null,
                            'seqno' => null,
                            'instruction' => null,
                            'freqcode' => null,
                            'durationcode' => null,
                            'strength' => null,
                            'durqty' => 0,
                            'freqqty' => 0,
                            'doseqty' => null,
                            'dosecode' => null,
                            'barcode' => null,
                            'computerid' => null,
                            'ipaddress' => null,
                            'lastcomputerid' => null,
                            'lastipaddress' => null,
                            'auto' => null,
                            'micerra' => null,
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

    public function chgprice_migrate(Request $request){
        DB::beginTransaction();

        try {

            $chgmast_m = DB::table('migration.chgprice')
                                ->get();

            $x = 1;
            foreach ($chgmast_m as $obj) {
                $exist = DB::table('hisdb.chgprice')
                            ->where('compcode','10A')
                            ->where('chgcode',$obj->chgcode)
                            ->exists();

                if(!$exist){

                    $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode','10A')
                            ->where('chgcode',$obj->chgcode);

                    if($chgmast->exists()){
                        $chgmast = $chgmast->first();
                        $uom = $chgmast->uom;
                    }else{
                        $uom = null;
                    }

                    DB::table('hisdb.chgprice')
                        ->insert([
                            'lineno_' => $x,
                            'compcode' => '10A',
                            'chgcode' => $obj->chgcode,
                            'uom' => $uom,
                            'effdate' => $obj->effdate,
                            'minamt' => null,
                            'amt1' => $obj->amt1,
                            'amt2' => $obj->amt2,
                            'amt3' => $obj->amt3,
                            'iptax' => 'ES',
                            'optax' => 'ES',
                            'maxamt' => 0,
                            'costprice' => null,
                            'lastuser' => null,
                            'lastupdate' => null,
                            'lastfield' => null,
                            'unit' => 'IMSC',
                            'adduser' => 'SYSTEM',
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'autopull' => null,
                            'addchg' => null,
                            'pkgstatus' => null,
                            'recstatus' => 'ACTIVE',
                            'deluser' => null,
                            'deldate' => null,
                            'lastcomputerid' => null,
                            'lastipaddress' => null,
                        ]);
                    $x++;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }
    }

    public function docapptsession(Request $request){
        DB::beginTransaction();

        try {
            $doctor = DB::table('hisdb.doctor')
                    ->where('compcode',session('compcode'))
                    ->get();

            foreach ($doctor as $obj) {
                $apptresrc = DB::table('hisdb.apptresrc')
                                ->where('compcode',session('compcode'))
                                ->where('resourcecode',$obj->doctorcode);

                if(!$apptresrc->exists()){
                    DB::table('hisdb.apptresrc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'resourcecode' => $obj->doctorcode,
                            'description' => $obj->doctorname,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now(),
                            'recstatus' => 'ACTIVE',
                            'TYPE' => 'DOC',
                            'intervaltime' => 30.00
                        ]);
                }else{
                    DB::table('hisdb.apptresrc')
                        ->where('compcode',session('compcode'))
                        ->where('resourcecode',$obj->doctorcode)
                        ->update([
                            'description' => $obj->doctorname,
                            'recstatus' => 'ACTIVE',
                            'TYPE' => 'DOC',
                            'intervaltime' => 30.00
                        ]);
                }


                $apptsession = DB::table('hisdb.apptsession')
                                    ->where('compcode',session('compcode'))
                                    ->where('doctorcode',$obj->doctorcode);

                $days = ['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'];
                $timefr1 = '08:00:00';
                $timeto1 = '12:00:00';
                $timefr2 = '14:00:00';
                $timeto2 = '17:00:00';

                if(!$apptsession->exists()){

                    foreach ($days as $day) {
                        DB::table('hisdb.apptsession')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now(),
                                'recstatus' =>'A',
                                'doctorcode' => $obj->doctorcode,
                                'days' => $day,
                                'timefr1' => $timefr1,
                                'timeto1' => $timeto1,
                                'timefr2' => $timefr2,
                                'timeto2' => $timeto2,
                                'status' => 'True',
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
}