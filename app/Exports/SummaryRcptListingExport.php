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
use DateTime;
use Carbon\Carbon;

class SummaryRcptListingExport implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto)
    {
        
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $this->dbacthdr = DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->first();
        
    }
    
    public function collection()
    {
        
        $dbacthdr_date = DB::table('debtor.dbacthdr as db')
                        ->select(
                            'db.debtorcode as debtorcode',
                            'dm.name as name',
                            'db.entrydate as entrydate',
                            'db.auditno as auditno',
                            'db.amount as amount',
                            'db.outamount as outamount',
                            'db.paymode as paymode',
                            'db.recstatus as recstatus'
                        )
                        ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                        ->whereBetween('entrydate',[$this->datefr,$this->dateto])
                        ->get();
        
        return $dbacthdr_date;

        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    ->where('tillcode',$request->tillcode)
                    ->where('tillno',$request->tillno)
                    ->first();
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt')
                ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype as dm_debtortype', 'dt.description as dt_description')
                ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->where('dh.compcode','=',session('compcode'))
                ->where('dh.tillno','=',$request->tillno)
                ->get();
        
        $db_dbacthdr = DB::table('debtor.dbacthdr as db')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.tillcode',$request->tillcode)
                    ->where('db.tillno',$request->tillno)
                    // ->where('db.hdrtype','A')
                    ->join('debtor.paymode as pm', function($join) use ($request){
                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                    ->where('pm.source','AR')
                                    ->where('pm.compcode',session('compcode'));
                    });
        
        if($db_dbacthdr->exists()){
              $sum_cash = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            $sum_cash = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_chq = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CHEQUE')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_card = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CARD')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_bank = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','BANK')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_all = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->sum('amount');
            
            $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CASH')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_chq_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CHEQUE')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_card_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CARD')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_bank_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','BANK')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_all_ref = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RF'])
                        ->sum('amount');
        }
    }
    
    public function headings(): array
    {
        
        return [
            'entrydate','paymode',
        ];
        
    }
    
    public function columnWidths(): array
    {
        
        return [
            'A' => 15,
            'B' => 40,
            'C' => 25,
            'D' => 15,
            'E' => 15,
            'F' => 25,
            'G' => 15,
            'H' => 15,
        ];
        
    }
    
    public function columnFormats(): array
    {
        
        return [
           'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
        
    }
    
    public function registerEvents(): array
    {
        
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // set up a style array for cell formatting
                $style_header = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];
                
                $style_address = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ]
                ];
                
                $style_datetime = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ];
                
                $style_columnheader = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];
                
                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 6);
                
                ///// assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE :');
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                $event->sheet->setCellValue('A2','PRINTED TIME :');
                $event->sheet->setCellValue('B2', Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                $event->sheet->setCellValue('A3','PRINTED BY :');
                $event->sheet->setCellValue('B3', session('username'));
                $event->sheet->setCellValue('C1','SUMMARY RECEIPT LISTING');
                $event->sheet->setCellValue('C2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('F1',$this->comp->name);
                $event->sheet->setCellValue('F2',$this->comp->address1);
                $event->sheet->setCellValue('F3',$this->comp->address2);
                $event->sheet->setCellValue('F4',$this->comp->address3);
                $event->sheet->setCellValue('F5',$this->comp->address4);
                $event->sheet->setCellValue('A7','DATE');
                $event->sheet->setCellValue('A8',$this->dbacthdr->entrydate);
                $event->sheet->setCellValue('B7','CASH');
                $event->sheet->setCellValue('C7','CARD');
                $event->sheet->setCellValue('D7','CHEQUE');
                $event->sheet->setCellValue('E7','TOTAL');
                
                ///// assign cell styles
                $event->sheet->getStyle('A1:A3')->applyFromArray($style_datetime);
                $event->sheet->getStyle('C1:C2')->applyFromArray($style_header);
                $event->sheet->getStyle('F1:F5')->applyFromArray($style_address);
                $event->sheet->getStyle('A7:H7')->applyFromArray($style_columnheader);
                
            },
        ];
        
    }
    
}
