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

class acctenq_dateExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($glaccount,$fromdate,$todate)
    {   
        $this->glaccount = $glaccount;
        $this->fromdate = $fromdate;
        $this->todate = $todate;
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 18,
            'H' => 60,
            'I' => 18,
            'J' => 18,
            'K' => 18,
            'L' => 18,
        ];
    }
    
    public function view(): View
    {
        $glaccount = $this->glaccount;
        $fromdate = $this->fromdate;
        $todate = $this->todate;
        $compname = $this->comp->name;

        // $glmasref = DB::table('finance.gmasref')
        //                 ->where('compcode')

        $table = DB::table('finance.gltran as gl')
                        ->select('gl.id','gl.source','gl.trantype','gl.auditno','gl.postdate','gl.description','gl.reference','gl.drcostcode','gl.crcostcode','gl.cracc','gl.dracc','gl.amount','glcr.description as acctname_cr','gldr.description as acctname_dr')
                        ->where(function($table) use ($glaccount){
                            $table->orwhere('gl.dracc','=', $glaccount);
                            $table->orwhere('gl.cracc','=', $glaccount);
                        })
                        ->leftJoin('finance.glmasref as glcr', function($join){
                            $join = $join->on('glcr.glaccno', '=', 'gl.cracc')
                                            ->where('glcr.compcode','=',session('compcode'));
                        })
                        ->leftJoin('finance.glmasref as gldr', function($join){
                            $join = $join->on('gldr.glaccno', '=', 'gl.dracc')
                                            ->where('gldr.compcode','=',session('compcode'));
                        })
                        ->where('gl.amount','!=','0')
                        ->where('gl.postdate', '>=', $this->fromdate)
                        ->where('gl.postdate', '<=', $this->todate)
                        ->where('gl.compcode', session('compcode'))
                        ->orderBy('gl.postdate', 'asc')
                        ->get();

        $same_acc = [];
        foreach ($table as $key => $value) {

            if($value->dracc == $this->glaccount){
                $value->acccode = $value->cracc;
                $value->costcode = $value->crcostcode;
                $value->costcode_ = $value->drcostcode;
                $value->cramount = 0;
                $value->dramount = $value->amount;
                $value->acctname = $value->acctname_cr;
            }else{
                $value->acccode = $value->dracc;
                $value->costcode = $value->drcostcode;
                $value->costcode_ = $value->crcostcode;
                $value->cramount = $value->amount;
                $value->dramount = 0;
                $value->acctname = $value->acctname_dr;
            }

            if($value->dracc == $value->cracc){
                array_push($same_acc, clone $value);
            }

            switch ($value->source) {
                case 'OE':
                    $data = $this->oe_data($value);
                    break;
                case 'PB':
                    $data = $this->pb_data($value);
                    break;
                case 'AP':
                    $data = $this->ap_data($value);
                    break;
                case 'CM':
                    $data = $this->cm_data($value);
                    break;
                default:
                    $data = $this->oth_data($value);
                    break;
            }
        }

        foreach ($same_acc as $obj) {
            $obj->cramount = $obj->amount;
            $obj->dramount = 0;
            $table = $table->merge([$obj]);
        }

        return view('finance.GL.acctenq_date.acctenq_dateExcel', compact('table','glaccount','compname','fromdate','todate'));
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

    public function oe_data($obj){
        $responce = new stdClass();
        $obj->reference = 'INV-'.$obj->reference;

        $billsum = DB::table('debtor.billsum as bs')
                        ->select('bs.chggroup','ch.description')
                        ->leftJoin('hisdb.chgmast as ch', function($join){
                            $join = $join->on('ch.chgcode', '=', 'bs.chggroup')
                                            ->where('ch.compcode','=',session('compcode'));
                        })
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.auditno',$obj->auditno);

        if($billsum->exists()){
            $billsum = $billsum->first();
            $obj->description = $billsum->description;
            $responce->desc = $billsum->description;
            $obj->reference = 'INV-'.$obj->reference;
        }

        return $responce;
    }

    public function pb_data($obj){
        $responce = new stdClass();

        if($obj->trantype == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','IN')
                            ->where('dbh.auditno','=',$obj->auditno);
                            
            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'DN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','DN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'DN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'CN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','CN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'CN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'RC'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RC')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RD'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RD')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RF'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RF')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }
    }
    
    public function ap_data($obj){
        if($obj->trantype == 'PD'){

            $apacthdr = DB::table('finance.apacthdr as ap')
                            ->select('ap.suppcode','s.name')
                            ->leftJoin('material.supplier as s', function($join){
                                $join = $join->on('s.suppcode', '=', 'ap.suppcode')
                                                ->where('s.compcode','=',session('compcode'));
                            })
                            ->where('ap.compcode',session('compcode'))
                            ->where('ap.source','=',$obj->source)
                            ->where('ap.trantype','=',$obj->trantype)
                            ->where('ap.auditno','=',$obj->auditno);

            if($apacthdr->exists()){
                $apacthdr = $apacthdr->first();
                // $obj->description = $dbacthdr->payercode;
                $responce->desc = $apacthdr->name;
                $obj->reference = $apacthdr->name;
            }

            return $responce;

        }else{
            return 0;
        }
    }
    
    public function cm_data($obj){
        return 0;
    }
    
    public function oth_data($obj){
        $responce = new stdClass();

        // $exp1 = explode('</br>', $obj->description);
        // $exp2 = explode(' ', $obj->reference);

        $obj->description = $obj->description;
        $responce->desc = $obj->description;
        $responce->refe = $obj->reference;

        return $responce;
    }
    
}
