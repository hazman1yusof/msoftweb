<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;
use Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Jobs\SendEmailPR;

class TestController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        // $this->middleware('auth');
        // $this->duplicateCode = "bloodcode";
    }

    public function show(Request $request)
    {   
        return view('test.test2');

    }

    public function test(Request $request){
        $product = DB::table('material.product')
                        ->where('compcode','9A')
                        ->where('itemcode','LIKE','65%');

        if($product->exists()){
            $product = $product->get();

            foreach ($product as $key => $value) {
                $chgmast = DB::table('hisdb.chgmast')
                                    ->where('compcode','9A')
                                    ->where('chgcode',$value->itemcode);


                if($chgmast->exists()){
                    DB::table('hisdb.chgmast')
                        ->where('compcode','9A')
                        ->where('chgcode',$value->itemcode)
                        ->update([
                            'uom' => $value->uomcode
                        ]);
                }             
            }
        }
    }

    public function excel(Request $request)
    {   
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }

    public function show2(Request $request)
    {   
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }

        $purreqhd = DB::table('material.purreqhd')
            ->where('recno','=',$recno)
            ->first();

        $purreqdt = DB::table('material.purreqdt AS prdt', 'material.productmaster AS p', 'material.uom as u')
            ->select('prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'prdt.itemcode', '=', 'p.itemcode')
            ->leftJoin('material.uom as u', 'prdt.uomcode', '=', 'u.uomcode')
            ->where('recno','=',$recno)
            ->get();
            // dd($purreqdt);

        $totamount_expld = explode(".", (float)$purreqhd->totamount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        return view('test.testpdf2',compact('purreqhd','purreqdt','totamt_bm'));

    }

    public function pdf(Request $request)
    {   
        // dd($request);
        // return view('test.testexpdateloop');
        $pdf = PDF::loadView('test.testpdf');
        return $pdf->stream();      
        // return $pdf->download('invoice.pdf');

    }

    public function test_grid(Request $request)
    {   
        return view('test.test_grid');

    }



    public function pdf2(Request $request)
    {   

        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }

        $purreqhd = DB::table('material.purreqhd')
            ->where('recno','=',$recno)
            ->first();

        $purreqdt = DB::table('material.purreqdt AS prdt', 'material.productmaster AS p', 'material.uom as u')
            ->select('prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'prdt.itemcode', '=', 'p.itemcode')
            ->leftJoin('material.uom as u', 'prdt.uomcode', '=', 'u.uomcode')
            ->where('recno','=',$recno)
            ->get();
            // dd($purreqdt);

        $totamount_expld = explode(".", (float)$purreqhd->totamount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        // dd($request);
        // return view('test.testexpdateloop');
        $pdf = PDF::loadView('test.testpdf2',compact('purreqhd','purreqdt','totamt_bm'));
        return $pdf->stream();      
        // return $pdf->download('invoice.pdf');

    }

    public function form(Request $request)
    {   
        $trandate = $request->date;
        $quan = $request->quan;


        $expdate_obj = DB::table('test.test')
                        ->where('expdate','<=',$expdate)
                        ->orderBy('expdate', 'asc')
                        ->get();

        foreach ($expdate_obj as $value) {
            $curr_quan = $value->quan;
            if($quan-$curr_quan>0){

                $quan = $quan-$curr_quan;
                DB::table('test.test')
                    ->where('id','=',$value->id)
                    ->update([
                        'quan' => '0'
                    ]);
            
            }else{

                $curr_quan = $curr_quan-$quan;
                DB::table('test.test')
                    ->where('id','=',$value->id)
                    ->update([
                        'quan' => $curr_quan
                    ]);
                    
                break;
            }


        }


    }

    public function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'LAPAN', 'SEMBILAN', 'SEPULUH', 'SEBELAS',
            'DUA BELAS', 'TIGA BELAS', 'EMPAT BELAS', 'LIMA BELAS', 'ENAM BELAS', 'TUJUH BELAS', 'LAPAN BELAS', 'SEMBILAN BELAS'
        );
        $list2 = array('', 'SEPULUH', 'DUA PULUH', 'TIGA PULUH', 'EMPAT PULUH', 'LIMA PULUH', 'ENAM PULUH', 'TUJUH PULUH', 'LAPAN PULUH', 'SEMBILAN PULUH', 'SERATUS');
        $list3 = array('', 'RIBU', 'JUTA', 'BILLION', 'TRILLION', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' RATUS' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

    public function show_email(Request $request)
    {   
        return view('email.show_email');

    }

    public function send_email(Request $request)
    {   

        $data = new stdClass();
        $data->status = 'SUPPORT';
        $data->deptcode = 'A1001';
        $data->recno = 34;

        SendEmailPR::dispatch($data);
    }

    public function testcalander(Request $request)
    {   
        return view('test.testcalander');

    }

    


}