<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class icdController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "icdcode";
    }

    public function show(Request $request)
    {   
        return view('setup.icd.icd');
    }

    public function table(Request $request)
    {   
        $icdver = DB::table('sysdb.sysparam')
                        ->select('pvalue1')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','MR')
                        ->where('trantype','=','ICD')
                        ->first();

        $table = DB::table('hisdb.diagtab')
                    ->where('type','=',$icdver->pvalue1)
                    ->orderBy('idno','asc');

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);
            // dump($count);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {//ini baru
            $value->description = $value->description;
            if(mb_strlen($value->description)>120){

                $time = time() + $key;

                $value->description = mb_substr($value->description,0,120).'<span id="dots_'.$time.'" style="display: inline;"> ... </span><span id="more_'.$time.'" style="display: none;">'.mb_substr($value->description,120).'</span><a id="moreBtn_'.$time.'" style="color: #337ab7 !important;" >Read more</a>';

                $value->callback_param = [
                    'dots_'.$time,'more_'.$time,'moreBtn_'.$time
                ];
            }
            
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $diagtab = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',strtoupper($request->icdcode));

            $type = DB::table('sysdb.sysparam')
                            ->where('source','=',"MR")
                            ->where('trantype','=',"ICD")
                            ->first();

            if($diagtab->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.diagtab')
                ->insert([
                    'compcode' => session('compcode'),  
                    'icdcode' => strtoupper($request->icdcode),
                    'description' => strtoupper($request->description),
                    "type" => strtoupper($request->type),
                    'recstatus' => strtoupper($request->recstatus),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.diagtab')
                ->where('idno','=',$request->idno)
                ->update([  
                    'description' => strtoupper($request->description),
                    'recstatus' => strtoupper($request->recstatus),                    
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.diagtab')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'lastuser' => strtoupper(session('username')),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
