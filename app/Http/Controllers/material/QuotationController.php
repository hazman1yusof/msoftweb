<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\invtran_util;

class QuotationController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.Quote.Quote');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_all_attachment_request':
                return $this->get_all_attachment_request($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'uploadfile':
                return $this->uploadfile($request);
            case 'add':
                return $this->add($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        $table = DB::table('material.quotehdr')
                    ->where('compcode',session('compcode'))
                    ->get();

        foreach ($table as $key => $value) {
            $all_attach = DB::table('material.quotedtl')
                ->where('hdr_idno',$value->idno)
                ->get();

            $value->all_attach = $all_attach;
        }

        $responce = new stdClass();
        $responce->data = $table;
        return json_encode($responce);

    }

    public function uploadfile_2(Request $request){
        $type = $request->file('file')->getClientMimeType();
        $filename = $request->file('file')->getClientOriginalName();
        $file_path = $request->file('file')->store('quote', 'public_uploads');

        
        $responce = new stdClass();
        $responce->file_path = $file_path;
        return json_encode($responce);
    }

    public function uploadfile(Request $request){
        $type = $request->file('file')->getClientMimeType();
        $filename = $request->file('file')->getClientOriginalName();
        $file_path = $request->file('file')->store('quote', 'public_uploads');

        if(empty($request->hdr_idno)){
            $hdr_idno =  DB::table('material.quotehdr')
                            ->insertGetId([
                                'compcode' => 'DD',
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);

            DB::table('material.quotedtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'hdr_idno' => $hdr_idno,
                    'attachment' => $file_path,
                    'filename' => $filename,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $responce = new stdClass();
            $responce->hdr_idno = $hdr_idno;
            $responce->all_attach = $this->get_all_attachment($hdr_idno);
            return json_encode($responce);

        }else{
            DB::table('material.quotedtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'hdr_idno' => $request->hdr_idno,
                    'attachment' => $file_path,
                    'filename' => $filename,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $responce = new stdClass();
            $responce->hdr_idno = $request->hdr_idno;
            $responce->all_attach = $this->get_all_attachment($request->hdr_idno);
            return json_encode($responce);
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        $table = DB::table("material.quotehdr");

        try {

            if(empty($request->idno)){
                $array_insert = [
                    'compcode' => session('compcode'),
                    'subject' => $request->subject,
                    'particulars' => $request->particulars,
                    'dept' => session('deptcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->insert($array_insert);
            }else{
                $array_insert = [
                    'compcode' => session('compcode'),
                    'subject' => $request->subject,
                    'particulars' => $request->particulars,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->where('idno',$request->idno)
                        ->update($array_insert);
            }
    
            $responce = new stdClass();
            $responce->status = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function get_all_attachment($hdr_idno){

        $quotedtl = DB::table('material.quotedtl')
            ->where('hdr_idno',$hdr_idno)
            ->get();

        return $quotedtl;
    }

    public function get_all_attachment_request(Request $request){

        $all_attach = DB::table('material.quotedtl')
            ->where('hdr_idno',$request->hdr_idno)
            ->get();

        $responce = new stdClass();
        $responce->all_attach = $all_attach;
        echo json_encode($responce);
    }
}

