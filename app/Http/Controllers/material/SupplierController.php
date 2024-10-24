<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\SupplierExport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('material.supplier.supplier');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        $table = DB::table('material.supplier');
        $recno = $this->recno('AP','suppcode');

        try {

            $array_update = [
                'CompCode' => session('compcode'),
                'SuppCode' => 'TCM'.str_pad($recno, 4, '0', STR_PAD_LEFT),
                'SuppGroup' => $request->SuppGroup,
                'Name' => $request->Name,
                'ContPers' => $request->ContPers,
                'Addr1' => $request->Addr1,
                'Addr2' => $request->Addr2,
                'Addr3' => $request->Addr3,
                'Addr4' => $request->Addr4,
                'TelNo' => $request->TelNo,
                'Faxno' => $request->Faxno,
                'TermOthers' => $request->TermOthers,
                'TermNonDisp' => $request->TermNonDisp,
                'TermDisp' => $request->TermDisp,
                'CostCode' => $request->CostCode,
                'GlAccNo' => $request->GlAccNo,
                'OutAmt' => $request->OutAmt,
                'AccNo' => $request->AccNo,
                'AddUser' => session('username'),
                'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                'UpdUser' => session('username'),
                'UpdDate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'DelUser' => $request->,
                // 'DelDate' => $request->,
                'DepAmt' => 0.00,
                'MiscAmt' => 0.00,
                'SuppFlg' => 1,
                'Advccode' => $request->Advccode,
                'AdvGlaccno' => $request->AdvGlaccno,
                'recstatus' => 'ACTIVE',
                'computerid' => session('computerid'),
                // 'ipaddress' => $request->,
                'lastcomputerid' => session('computerid'),
                // 'lastipaddress' => $request->,
                'GSTID' => $request->GSTID,
                'CompRegNo' => $request->CompRegNo,
                'TermDays' => $request->TermDays,
                'indvNewic' => $request->indvNewic,
                'indvOtherno' => $request->indvOtherno,
                'TINNo' => $request->TINNo
            ];

            // dd($array_update);
            $table->insert($array_update);

            $responce = new stdClass();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }


    public function showpdf(Request $request){
        $supp_code = DB::table('material.supplier')
            ->where('compcode','=',session('compcode'))
            ->where('recstatus', '=', 'ACTIVE')
            ->orderBy('suppcode', 'ASC')
            ->get();

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('material.supplier.supplier_pdfmake',compact('supp_code','company'));
        
    }

    public function showExcel(Request $request){
        return Excel::download(new SupplierExport, 'SupplierReport.xlsx');
    }
}