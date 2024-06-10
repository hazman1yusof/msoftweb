<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\ClaimBatchListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ClaimBatchList_ReportController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.ClaimBatchList_Report.ClaimBatchList_Report');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->oper){
            case 'edit':
                return $this->edit($request);
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table':
                return $this->get_table($request);
            default:
                return 'error happen..';
        }
    }
    
    public function report(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'ClaimBatchList_pdf':
                return $this->ClaimBatchList_pdf($request);
            case 'ClaimBatchList_xls':
                return $this->ClaimBatchList_xls($request);
            default:
                return 'error happen..';
        }
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            $sysparam1 = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','coverletter');
            
            if(!$sysparam1->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'AR',
                        'trantype' => 'coverletter',
                        'description' => $request->title,
                        'pvalue1' => $request->content,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $sysparam1
                    ->update([
                        'description' => $request->title,
                        'pvalue1' => $request->content,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $sysparam2 = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','PIC');
            
            if(!$sysparam2->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'AR',
                        'trantype' => 'PIC',
                        'pvalue1' => $request->officer,
                        'pvalue2' => $request->designation,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $sysparam2
                    ->update([
                        'pvalue1' => $request->officer,
                        'pvalue2' => $request->designation,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table(Request $request){
        
        $sysparam1_obj = DB::table('sysdb.sysparam')
                        ->select('description as title','pvalue1 as content')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','coverletter');
        
        $sysparam2_obj = DB::table('sysdb.sysparam')
                        ->select('pvalue1 as officer','pvalue2 as designation')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','PIC');
        
        $responce = new stdClass();
        
        if($sysparam1_obj->exists()){
            $sysparam1_obj = $sysparam1_obj->first();
            $responce->sysparam1 = $sysparam1_obj;
        }
        
        if($sysparam2_obj->exists()){
            $sysparam2_obj = $sysparam2_obj->first();
            $responce->sysparam2 = $sysparam2_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function ClaimBatchList_pdf(Request $request){
        $validator = Validator::make($request->all(), [
            'date1' => 'required',
            'debtorcode_to' => 'required',
        ]);
        
        if($validator->fails()){
            abort(404);
        }
        
        $date1 = $request->date1;
        $epis_type = $request->epis_type;
        $debtorcode_to = $request->debtorcode_to;
        $title = $request->title;
        $content = $request->content;
        $officer = $request->officer;
        $designation = $request->designation;
        
        // $sysparam1_obj = DB::table('sysdb.sysparam')
        //                 ->select('description as title','pvalue1 as content')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','coverletter')
        //                 ->first();
        
        // $sysparam2_obj = DB::table('sysdb.sysparam')
        //                 ->select('pvalue1 as officer','pvalue2 as designation')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','PIC')
        //                 ->first();
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$debtorcode_to)
                    ->first();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->date1 = $date1;
        $header->epis_type = $epis_type;
        $header->debtorcode_to = $debtorcode_to;
        $header->title = $title;
        $header->content = $content;
        $header->officer = $officer;
        $header->designation = $designation;
        
        return view('finance.AR.ClaimBatchList_Report.ClaimBatchList_Report_pdfmake',compact('date1','epis_type','title','content','officer','designation','debtormast','company','header'));
    }
    
    public function ClaimBatchList_xls(Request $request){
        $validator = Validator::make($request->all(), [
            'date1' => 'required',
            'debtorcode_to' => 'required',
        ]);
        
        if($validator->fails()){
            abort(404);
        }
        
        $date1 = $request->date1;
        $epis_type = $request->epis_type;
        $debtorcode_to = $request->debtorcode_to;
        $title = $request->title;
        $content = $request->content;
        $officer = $request->officer;
        $designation = $request->designation;
        
        return Excel::download(new ClaimBatchListExport($date1,$epis_type,$debtorcode_to,$title,$content,$officer,$designation), 'Claim Batch Listing.xlsx');
    }
    
}

