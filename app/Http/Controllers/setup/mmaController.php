<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class mmaController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "mmacode";
    }

    public function show(Request $request)
    {   
        return view('setup.mma.mma');
    }

    public function table(Request $request)
    {   
        // $mmaver = DB::table('sysdb.sysparam')
        //                 ->select('pvalue1')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','MR')
        //                 ->where('trantype','=','ICD')
        //                 ->first();

        $table = DB::table('hisdb.mmamaster')
                    // ->where('version','=',$version->pvalue1)
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

            $mmamaster = DB::table('hisdb.mmamaster')
                            ->where('mmacode','=',$request->mmacode);

            $type = DB::table('sysdb.sysparam')
                            ->where('source','=',"MR")
                            ->where('trantype','=',"MMAVER")
                            ->first();

            if($mmamaster->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.mmamaster')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mmacode' => strtoupper($request->mmacode),
                    'description' => strtoupper($request->description),
                    "version" => $type->pvalue1,
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {//ini baru
            $value->remarks_show = $value->remarks;
            if(mb_strlen($value->remarks)>120){
        
                $time = time() + $key;
        
                $value->remarks_show = mb_substr($value->remarks_show,0,120).'<span id="dots_'.$time.'" style="display: inline;">...</span><span id="more_'.$time.'" style="display: none;">'.mb_substr($value->remarks_show,120).'</span><a id="moreBtn_'.$time.'" style="color: #337ab7 !important;" >Read more</a>';
        
                $value->callback_param = [
                    'dots_'.$time,'more_'.$time,'moreBtn_'.$time
                ];
            }
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.mmamaster')
                ->where('idno','=',$request->idno)
                ->update([  
                    'mmacode' => strtoupper($request->mmacode),
                    'description' => strtoupper($request->description),
                    'version' => $type->pvalue1,
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
        DB::table('hisdb.mmamaster')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
