<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DateTime;
use Carbon\Carbon;
use stdClass;

class bankReconExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($idno)
    {   
        $this->idno = $idno;
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 80,
            'D' => 20,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 30,
        ];
    }
    
    public function view(): View
    {
        $cbhdr = DB::table('finance.cbrechdr')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$this->idno)
                        ->first();

        $recdate = Carbon::createFromFormat('Y-m-d',$cbhdr->recdate)->format('d-m-Y');
        $compname = $this->comp->name;
        $bank = DB::table('finance.bank')
                        ->where('compcode',session('compcode'))
                        ->where('bankcode',$cbhdr->bankcode)
                        ->first();
        $bankname = $bank->bankname;


        $cbdtl = DB::table('finance.cbrecdtl as cbdt')
                    ->where('cbdt.compcode', '=', session('compcode'))
                    ->where('cbdt.auditno','=', $cbhdr->auditno)
                    ->get();

        $db_tot = DB::table('finance.cbrecdtl AS cbdt')
                    ->where('cbdt.compcode','=', session('compcode'))
                    ->where('cbdt.auditno','=', $cbhdr->auditno)
                    ->where('cbdt.amount','>', 0)
                    ->sum('amount');

        $cr_tot = DB::table('finance.cbrecdtl AS cbdt')
                    ->where('cbdt.compcode','=', session('compcode'))
                    ->where('cbdt.auditno','=', $cbhdr->auditno)
                    ->where('cbdt.amount','<', 0)
                    ->sum('amount');

        $bs_bal = $cbhdr->openamt;

        $yymm = Carbon::createFromFormat('Y-m-d',$cbhdr->recdate)->endOfMonth()->format('ym');

        $currentbal = DB::table('finance.bankstmt')
                            ->where('compcode',session('compcode'))
                            ->where('yymm','<=',$yymm)
                            ->where('bankcode',$cbhdr->bankcode)
                            ->sum('currentbal');

        $adjust = DB::table('finance.bankstmt')
                            ->where('compcode',session('compcode'))
                            ->where('yymm','<=',$yymm)
                            ->where('bankcode',$cbhdr->bankcode)
                            ->sum('adjustamt');

        $cb_bal = round($currentbal + $adjust, 2);
        $un_amt = $bs_bal - $cb_bal;

        foreach ($cbdtl as $key => $value) {
            switch($value->refsrc){
                case 'AP':
                    if($value->reftrantype == 'PV' || $value->reftrantype == 'PV'){
                        $apacthdr = DB::table('finance.apacthdr as ap')
                                        ->select('su.Name as suppname')
                                        ->leftJoin('material.supplier as su', function($join){
                                            $join = $join->on('su.suppcode', '=', 'ap.suppcode')
                                                        ->where('su.compcode','=',session('compcode'));
                                        })
                                        ->where('ap.compcode',session('compcode'))
                                        ->where('ap.source',$value->refsrc)
                                        ->where('ap.trantype',$value->reftrantype)
                                        ->where('ap.auditno',$value->refauditno);

                        if($apacthdr->exists()){
                            $value->reference = $apacthdr->first()->suppname;
                        }
                    }
                    break;
                case 'PB':
                    if($value->reftrantype == 'RC' || $value->reftrantype == 'RD' || $value->reftrantype == 'RF'){
                        $dbacthdr = DB::table('debtor.dbacthdr as db')
                                        ->select('dm.Name as name')
                                        ->leftJoin('debtor.debtormast as dm', function($join){
                                            $join = $join->on('dm.debtorcode', '=', 'db.payercode')
                                                        ->where('dm.compcode','=',session('compcode'));
                                        })
                                        ->where('db.compcode',session('compcode'))
                                        ->where('db.source',$value->refsrc)
                                        ->where('db.trantype',$value->reftrantype)
                                        ->where('db.auditno',$value->refauditno);

                        if($dbacthdr->exists()){
                            $value->reference = $dbacthdr->first()->name;
                        }
                    }
                case 'CM':
                    if($value->reftrantype == 'DP'){
                        $apacthdr = DB::table('finance.apacthdr as ap')
                                        ->select('su.Name as suppname')
                                        ->leftJoin('material.supplier as su', function($join){
                                            $join = $join->on('su.suppcode', '=', 'ap.suppcode')
                                                        ->where('su.compcode','=',session('compcode'));
                                        })
                                        ->where('ap.compcode',session('compcode'))
                                        ->where('ap.source',$value->refsrc)
                                        ->where('ap.trantype',$value->reftrantype)
                                        ->where('ap.auditno',$value->refauditno);

                        if($apacthdr->exists()){
                            $value->reference = $apacthdr->first()->suppname;
                        }
                    }
            }
        }

        $cb_tran1 = DB::table('finance.cbtran AS cb')
                            ->where('cb.compcode',session('compcode'))
                            ->where('cb.reconstatus','!=', 1)
                            ->where('cb.bankcode','=', $cbhdr->bankcode)
                            ->where('cb.postdate','<=', $cbhdr->recdate)
                            ->get();

        $cb_tran2 = DB::table('finance.cbtran AS cb')
                            ->where('cb.compcode',session('compcode'))
                            ->where('cb.reconstatus', 1)
                            ->where('cb.bankcode','=', $cbhdr->bankcode)
                            ->where('cb.recondate','>', $cbhdr->recdate)
                            ->where('cb.postdate','<=', $cbhdr->recdate)
                            ->get();

        // $cb_tran = $cb_tran1->merge($cb_tran2);
        $cb_tran_ = $cb_tran1->merge($cb_tran2);


        $bankrecondel = DB::table('finance.bankrecondel as bd')
                            ->get();

        if(count($bankrecondel) > 0){
            $cb_tran = [];
            foreach ($cb_tran_ as $objcb) {
                foreach ($bankrecondel as $objbd) {
                    if($objcb->source == $objbd->source && $objcb->trantype == $objbd->trantype && $objcb->auditno == $objbd->auditno){
                        if($objbd->amount_swap > 0){
                            $objcb->amount = $objbd->amount_swap;
                            array_push($cb_tran, $objcb);
                            break;
                        }
                    }else{
                        array_push($cb_tran, $objcb);
                        break;
                    }
                }
            }
        }else{
            $cb_tran = $cb_tran_;
        }
        
        return view('finance.CM.bankRecon.bankReconExcel', compact('recdate','compname','bankname','cbdtl','db_tot','cr_tot','bs_bal','cb_bal','un_amt','cb_tran'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\Bank Recon"."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
}
