<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class ReportFormatController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "rptname";
    }
    
    public function show(Request $request)
    {
        return view('finance.GL.reportFormat.reportFormat');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'Errors happen';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        $table = DB::table("finance.glrpthdr");
        
        try {
            
            $glrpthdr = DB::table("finance.glrpthdr")
                        ->where('compcode','=',session('compcode'))
                        ->where('rptname','=',$request->rptname);
            
            if($glrpthdr->exists()){
                throw new \Exception("record duplicate");
            }
            
            $array_insert = [
                'compcode' => session('compcode'),
                'rptname' => strtoupper($request->rptname),
                'description' => strtoupper($request->description),
                'rpttype' => $request->rpttype,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            ];
            
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);
            
            $responce = new stdClass();
            $responce->idno = $idno;
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        $table = DB::table("finance.glrpthdr");
        
        $array_update = [
            'rptname' => strtoupper($request->rptname),
            'description' => strtoupper($request->description),
            'rpttype' => $request->rpttype,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
        ];
        
        try {
            
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);
            
            $responce = new stdClass();
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function del(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table("finance.glrpthdr")
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
}