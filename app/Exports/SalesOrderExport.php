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

class SalesOrderExport implements  FromView, WithEvents, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($datefr,$dateto,$deptcode,$scope)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->deptcode = $deptcode;
        $this->scope = $scope;
        $this->dbacthdr_len=0;

        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,    
            'C' => 15,
            'D' => 15,   
            'E' => 40,  
            'F' => 40,
            'G' => 15,   
                 
        ];
    }

    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d H:i:s');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d H:i:s');
        $deptcode = $this->deptcode;
        $scope = $this->scope;
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.invno', 'dh.posteddate','dh.mrn', 'dh.deptcode', 'dh.amount', 'dm.debtorcode as dm_debtorcode', 'dm.name as debtorname')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    // ->leftJoin('hisdb.pat_mast as pm', function($join){
                    //     $join = $join->on("pm.newmrn", '=', 'dh.mrn');    
                    //     $join = $join->where("pm.compcode", '=', session('compcode'));
                    // })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source','=','PB')
                    ->whereIn('dh.trantype',['IN'])
                    ->where('dh.recstatus','=', 'POSTED')
                    ->whereBetween('dh.posteddate', [$datefr, $dateto]);

                    if($scope == 'POLI'){
                        $dbacthdr = $dbacthdr
                                        ->where('dh.unit','POLIS15');
                    }else{
                        if(!empty($deptcode)){
                            $dbacthdr = $dbacthdr
                                        ->where('dh.deptcode', '=', $deptcode);
                        }
                    }
                    $dbacthdr = $dbacthdr
                                ->where('dh.amount','!=','0')
                                ->get();
                    
        $totalAmount = $dbacthdr->sum('amount');

        $title = "SALES";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        foreach ($dbacthdr as $obj) {
            $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('newmrn',$obj->mrn);

            if($pat_mast->exists()){
                $pat_mast = $pat_mast->first();
                $obj->pm_name = $pat_mast->Name;
            }else{
                $obj->pm_name = '';
            }
        }
        
        return view('finance.SalesOrder_Report.SalesOrder_Report_excel',compact('dbacthdr', 'totalAmount', 'company', 'title'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSALES REPORT"."\n".sprintf('FROM DATE %s TO DATE %s', Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y')).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:K')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                $event->sheet->getStyle('D')->setQuotePrefix(true);
            },
        ];
    }

}
