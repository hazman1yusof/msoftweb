<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\CardReceiptExport;
use Maatwebsite\Excel\Facades\Excel;

class drcontribController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    var $auditno;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.drcontrib.drcontrib');
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'process_contrib':
                return $this->process_contrib($request);
            default:
                return 'error happen..';
        }
    }
    
    public function process_contrib(Request $request){
        DB::beginTransaction();

        try {
            $billdet = DB::table('hisdb.billdet as bd')
                            ->select('bd.idno','bd.auditno','bd.compcode','bd.mrn','bd.episno','bd.epistype','bd.trxtype','bd.docref','bd.trxdate','bd.chgcode','bd.counter','bd.billcode','bd.costcd','bd.revcd','bd.mmacode','bd.billdate','bd.billtype','bd.doctorcode','bd.chg_class','bd.unitprce','bd.quantity','bd.amount','bd.trxtime','bd.chggroup','bd.dracccode','bd.cracccode','bd.taxamount','bd.billno','bd.invno','bd.rowno','bd.uom','bd.billtime','bd.invgroup','bd.reqdept','bd.issdept','bd.lineno_','bd.billflag','bd.invcode','bd.arprocess','bd.discamt','bd.disccode','bd.pkgcode','bd.splitflag','bd.taxcode','bd.adduser','bd.adddate','bd.lastuser','bd.lastupdate','bd.recstatus','bd.taxflag','bd.discflag','bd.invdesc','ep.epistycode')
                            ->where('bd.compcode',session('compcode'))
                            ->where('bd.chgclass','C')
                            ->whereDate('bd.billdate','>=',$request->datefrom)
                            ->whereDate('bd.billdate','<=',$request->dateto)
                            ->leftJoin('hisdb.episode as ep', function($join){
                                $join = $join->on('ep.mrn','bd.mrn')
                                             ->on('ep.episno','bd.episno')
                                             ->where('ep.compcode',session('compcode'));
                            });

            foreach ($billdet as $obj_bd) {
                $drcontrib = DB::table('hisdb.drcontrib')
                                ->where('compcode',session('compcode'))
                                ->where('drcode',$obj_bd->doctorcode)
                                ->where('epistycode',$obj_bd->epistycode)
                                ->where('chgcode',$obj_bd->chgcode)
                                ->whereDate('effdate','<=',$obj_bd->billdate)
                                ->orderBy('effdate','DESC');

                $drcontrib = $drcontrib->first();

                if($obj_bd->amount > 0){
                    $drpamount = ($obj_bd->amount * $drcontrib->drprcnt / 100) + $drcontrib->amount;
                }else if($obj_bd->amount < 0){
                    $drpamount = ($obj_bd->amount * $drcontrib->drprcnt / 100) + $drcontrib->amount;
                    $drpamount = $drpamount * -1;
                }

                if($obj_bd->amount != 0){
                    DB::table('debtor.drtran')
                            ->insert([
                                'compcode' => session('compcode'),
                                // 'source' => $obj-> ,
                                // 'trantype' => $obj-> ,
                                'mrn' => $obj_bd->mrn ,
                                'episno' => $obj_bd->episno ,
                                'billno' => $obj_bd->billno ,
                                'lineno_' => $obj_bd->lineno_ ,
                                'auditno' => $obj_bd->auditno ,
                                'drcode' => $obj_bd->doctorcode ,
                                'epistype' => $obj_bd->epistycode ,
                                'trandate' => Carbon::now("Asia/Kuala_Lumpur") ,
                                // 'invsrc' => $obj_bd-> ,
                                // 'invtrtype' => $obj_bd-> ,
                                'billdate' => $obj_bd->billdate ,
                                // 'drrefno' => $obj_bd-> ,
                                'chgcode' => $obj_bd->chgcode ,
                                'chgtrxdate' => $obj_bd->adddate ,
                                'chgamount' => $obj_bd->amount ,
                                'chgoutamt' => $obj_bd->amount ,
                                // 'invamount' => $obj_bd-> ,
                                'drappamt' => $drpamount,
                                'drappoutamt' => $drpamount,
                                // 'drapppaid' => $obj_bd-> ,
                                // 'drcontamt' => $obj_bd-> ,
                                // 'drprcnt' => $obj_bd-> ,
                                'lastuser' => session('username'),
                                'lastupddate' => Carbon::now("Asia/Kuala_Lumpur") ,
                                'effectdate' => $drcontrib->effdate ,
                                // 'drstfamt' => $obj_bd-> ,
                                // 'drstfprcnt' => $obj_bd-> ,
                                // 'debtorcode' => $obj_bd-> ,
                                // 'consultflag' => $obj_bd-> ,
                                // 'totincome' => $obj_bd-> ,
                                // 'drprcnt1' => $obj_bd-> ,
                                // 'dramt1' => $obj_bd-> ,
                                // 'drprcnt2' => $obj_bd-> ,
                                // 'dramt2' => $obj_bd-> ,
                                // 'invcode' => $obj_bd-> ,
                                'chggroup' => $obj_bd->chggroup ,
                                // 'fullypaid' => $obj_bd-> ,
                            ]);
                }
            }

            // DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
    
    public function showExcel(Request $request){
        return Excel::download(new CardReceiptExport($request->datefr,$request->dateto,$request->tillcode,$request->tillno), 'CardReceiptExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    ->where('tillcode',$request->tillcode)
                    ->where('tillno',$request->tillno)
                    ->first();
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt')
                    ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate','dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype as dm_debtortype', 'dt.description as dt_description')
                    ->leftJoin('debtor.debtormast as dm', function ($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.debtortype as dt', function ($join) use ($request){
                        $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                    ->where('dt.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.paytype', '=', '#F_TAB-CARD')
                    ->whereIn('dh.trantype',['RD','RC'])
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->orderBy('dh.entrydate','ASC')
                    ->get();
        // dd($dbacthdr);
        
        $paymode = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.paymode')
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.paytype', '=', '#F_TAB-CARD')
                    ->whereIn('dh.trantype',['RD','RC'])
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->distinct('dh.paymode');
        $paymode = $paymode->get(['dh.paymode']);
        
        $totalAmount = $dbacthdr->sum('amount');
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.cardReceipt_Report.cardReceipt_Report_pdfmake',compact('dbacthdr','paymode','totamt_eng','totalAmount'));
        
    }
    
}