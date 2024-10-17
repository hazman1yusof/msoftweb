<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CategoryInvController extends defaultController
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
        return view('material.categoryINV.category');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {
            $category = DB::table('material.category')
                            ->where('compcode',session('compcode'))
                            ->where('cattype',strtoupper($request->cattype))
                            ->where('Class',strtoupper($request->class))
                            ->where('catcode',$request->catcode);

            if($category->exists()){
                throw new \Exception('Category already exist', 500);
            }

            DB::table('material.category')
                ->insert([  
                    'compcode' => session('compcode'),
                    'catcode' => strtoupper($request->catcode) ,
                    'description' => strtoupper($request->description) ,
                    'cattype' => strtoupper($request->cattype),
                    'source' => strtoupper($request->source) ,
                    'stockacct' => $request->stockacct ,
                    'cosacct' => $request->cosacct ,
                    'adjacct' => $request->adjacct ,
                    'woffacct' => $request->woffacct ,
                    'expacct' => $request->expacct ,
                    'loanacct' => $request->loanacct ,
                    'povalidate' => $request->povalidate ,
                    'ConsignAcct' => $request->ConsignAcct,
                    // 'accrualacc' => $request-> ,
                    // 'stktakeadjacct' => $request-> ,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur") ,
                    'recstatus' => 'ACTIVE' ,
                    'computerid' => session('computerid') ,
                    'lastcomputerid' => session('computerid') ,
                    'Class' => strtoupper($request->class),
                    // 'ConsignAcct' => $request-> ,
                ]);

            $request->checkduplicate = 0;

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('material.category')
                    ->where('idno',$request->idno)
                    ->update([  
                        // 'compcode' => session('compcode'),
                        // 'catcode' => $request->catcode ,
                        'description' => $request->description ,
                        // 'cattype' => $request->cattype,
                        // 'source' => $request->source ,
                        'stockacct' => $request->stockacct ,
                        'cosacct' => $request->cosacct ,
                        'adjacct' => $request->adjacct ,
                        'woffacct' => $request->woffacct ,
                        'expacct' => $request->expacct ,
                        'loanacct' => $request->loanacct ,
                        'ConsignAcct' => $request->ConsignAcct,
                        'povalidate' => $request->povalidate ,
                        // 'accrualacc' => $request-> ,
                        // 'stktakeadjacct' => $request-> ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur") ,
                        'recstatus' => 'ACTIVE' ,
                        // 'computerid' => session('computerid') ,
                        'lastcomputerid' => session('computerid') ,
                        // 'Class' => $request->class ,
                        // 'ConsignAcct' => $request-> ,
                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('material.category')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }
}