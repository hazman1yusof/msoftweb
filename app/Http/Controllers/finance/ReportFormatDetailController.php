<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ReportFormatDetailController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }
    
    public function chgDate($date){
        
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
        
    }
    
    public function get_table_dtl(Request $request){
        
        $table = DB::table('finance.glrptfmt')
                ->where('rptname','=',$request->rptname)
                ->where('compcode','=',session('compcode'));

        if(!empty($request->sidx)){

            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $rptname = $request->rptname;
            
            // header
            // $glrpthdr = DB::table('finance.glrpthdr')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('rptname','=',$rptname);
            
            // $glrpthdr_obj = $glrpthdr->first();
            
            // detail
            // $glrptfmt = DB::table('finance.glrptfmt')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('rptname','=',$rptname);
            
            // if($glrptfmt->exists()){
            //     $count = $glrptfmt->count();
            //     $lineno_ = $count + 1;
            //     $glrptfmt_obj = $glrptfmt->first();
            // }else{
            //     $lineno_ = 1;
            // }
            
            // 2. insert detail
            DB::table('finance.glrptfmt')
                ->insert([
                    'compcode' => session('compcode'),
                    'rptname' => strtoupper($rptname),
                    'lineno_' => $request->lineno_,
                    'printflag' => strtoupper($request->printflag),
                    'rowdef' => $request->rowdef,
                    'code' => strtoupper($request->code),
                    'note' => $request->note,
                    'description' => $request->description,
                    'formula' => $request->formula,
                    'costcodefr' => $request->costcodefr,
                    'costcodeto' => $request->costcodeto,
                    'revsign' => $request->revsign,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // 1. update detail
            DB::table('finance.glrptfmt')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'lineno_' => $request->lineno_,
                    'printflag' => strtoupper($request->printflag),
                    'rowdef' => $request->rowdef,
                    'code' => strtoupper($request->code),
                    'note' => $request->note,
                    'description' => $request->description,
                    'formula' => $request->formula,
                    'costcodefr' => $request->costcodefr,
                    'costcodeto' => $request->costcodeto,
                    'revsign' => $request->revsign,
                    'upduser'=> session('username'),
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_all(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach ($request->dataobj as $key => $value) {
                
                // 1. update detail
                DB::table('finance.glrptfmt')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'lineno_' => $value['lineno_'],
                        'printflag' => strtoupper($value['printflag']),
                        'rowdef' => $value['rowdef'],
                        'code' => strtoupper($value['code']),
                        'note' => $value['note'],
                        'description' => $value['description'],
                        'formula' => $value['formula'],
                        'costcodefr' => $value['costcodefr'],
                        'costcodeto' => $value['costcodeto'],
                        'revsign' => $value['revsign'],
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error'.$e, 500);
            
        }
        
    }
    
    public function del(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // 1. update detail
            DB::table('finance.glrptfmt')
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

