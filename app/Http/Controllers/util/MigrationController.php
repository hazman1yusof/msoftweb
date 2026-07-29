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
}