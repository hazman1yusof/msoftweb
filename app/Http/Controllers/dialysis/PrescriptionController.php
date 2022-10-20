<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;
use Auth;
use App\Models\SuratMasuk;

class PrescriptionController extends Controller
{   
    
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $table_pat_mast = DB::table('hisdb.pat_mast as pm')
                            ->select(
                                'pm.mrn',
                                'pm.episno',
                                'e.admdoctor'
                            )
                            ->where('pm.mrn','=',Auth::user()->mrn)
                            ->leftJoin('hisdb.episode as e', function($join) use ($request){
                                $join = $join->on('e.mrn', '=', 'pm.mrn');
                                $join = $join->on('e.episno', '=', 'pm.episno');
                            })
                            ->first();

        $table_sysdb = DB::table('sysdb.sysparam')
                            ->where('source','=','OE')
                            ->where('trantype','=','PHAR')
                            ->first();

        // $table_prescription = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
        //                     ->select('trx.auditno',
        //                         'trx.idno as id',
        //                         'trx.chgcode as chg_code',
        //                         'trx.quantity',
        //                         'trx.trxdate',
        //                         'trx.remarks',
        //                         'trx.instruction as ins_code',
        //                         'trx.doscode as dos_code',
        //                         'trx.frequency as fre_code',
        //                         'trx.drugindicator as dru_code',

        //                         'chgmast.description as chg_desc',
        //                         'instruction.description as ins_desc',
        //                         'dose.dosedesc as dos_desc',
        //                         'freq.freqdesc as fre_desc',
        //                         'drugindicator.drugindcode as dru_desc')

        //                     ->where('trx.mrn' ,'=', Auth::user()->mrn)
        //                     // ->where('trx.chggroup' ,'=', $table_sysdb->pvalue1)
        //                     ->where('trx.compcode','=',session('compcode'))
        //                     ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
        //                     ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
        //                     ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
        //                     ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
        //                     ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator')
        //                     ->orderby('trxdate','desc')
        //                     ->paginate(5);

            $table_prescription = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
                                ->select(
                                    'trx.id as id',
                                    'trx.chgcode as chg_code',
                                    'trx.quantity',
                                    'trx.remarks',
                                    'trx.instruction as ins_code',
                                    'trx.doscode as dos_code',
                                    'trx.frequency as fre_code',
                                    'trx.drugindicator as dru_code',

                                    'chgmast.description as chg_desc',
                                    'instruction.description as ins_desc',
                                    'dose.dosedesc as dos_desc',
                                    'freq.freqdesc as fre_desc',
                                    'drugindicator.drugindcode as dru_desc')

                                ->where('trx.mrn' ,'=', Auth::user()->mrn)
                                ->where('trx.chggroup' ,'=', $table_sysdb->pvalue1);
                                // ->where('trx.compcode','=',session('compcode'));

            // if($request->isudept != 'CLINIC'){
            //     $table_prescription->where('trx.isudept','=',$request->isudept);
            // }

            $table_prescription = $table_prescription
                                ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
                                ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
                                ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
                                ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
                                ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator')
                                ->orderBy('trx.id','desc')
                                ->paginate(5);


            foreach ($table_prescription->items() as $key => $value) {
                $value->admdoctor = $table_pat_mast->admdoctor;
            }

        return view('prescription', compact('table_prescription','table_pat_mast'));
    }

    public function detail($id,Request $request)
    {

        return view('pres_detail');
    }

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }

}