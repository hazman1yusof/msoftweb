<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class LabresultController extends defaultController
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){
        $centers = $this->get_maiwp_center_dept();

        if(!empty($request->changedept)){

            $department = DB::table('sysdb.department')
                            ->where('compcode', session('compcode'))
                            ->where('deptcode', $request->changedept);

            if($department->exists()){
                $request->session()->put('dept', $department->first()->deptcode);
                $request->session()->put('dept_desc', $department->first()->description);
            }
        }

        return view('labresult',compact('centers'));
    }

    public function table(Request $request){   
        switch($request->action){
            case 'previewdata':
                return $this->previewdata($request);

            default:
                return 'error happen..';
        }
    }

    public function previewdata(Request $request)
    {
        $table = DB::table('hisdb.labresult')
                            ->where('compcode', session('compcode'))
                            ->where('loginid','=',$request->loginid);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function previewvideo($auditno)
    {   
        $video = DB::table('hisdb.labresult')
                        ->where('compcode', session('compcode'))
                        ->where('auditno','=',$auditno)->first();
        return view('previewvideo',compact('video'));
    }

    public function form(Request $request)
    {   
        $type = $request->file('file')->getClientMimeType();
        if(!empty($request->rename)){
            if(strpos($request->rename, '.') !== false) {
                $strpos = strpos($request->rename, '.');
                $filename = substr($request->rename,0,$strpos);
            }else{
                $filename = $request->rename;
            }
        }else{
            $filename = $request->file('file')->getClientOriginalName();
        }

        $file_path = $request->file('file')->store('pat_enq','public_uploads');
        DB::table('hisdb.labresult')
            ->insert([
                'compcode' => session('compcode'),
                'resulttext' => $filename,
                'attachmentfile' => $file_path,
                'adduser' => 'system',
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'loginid' => session('username'),
                'type' => $type,
                'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
            ]);

        return redirect()->back();
    }

}