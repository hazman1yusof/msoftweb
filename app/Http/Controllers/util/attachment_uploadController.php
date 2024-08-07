<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;

class attachment_uploadController extends defaultController
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function page(Request $request)
    {   
        switch($request->page){
            case 'invoiceap':
                return $this->default_page($request);
            case 'purchaseorder':
                return $this->default_page($request);
            case 'purchaserequest':
                return $this->default_page($request);
            default:
                abort(404);
        }
    }

    public function table(Request $request)
    {   
        switch($request->page){
            case 'invoiceap':
                return $this->default_data($request);
            case 'purchaseorder':
                return $this->default_data($request);
            case 'purchaserequest':
                return $this->default_data($request);
            default:
                abort(404);
        }
    }

    public function form(Request $request)
    {   
        switch($request->page){
            case 'invoiceap':
                return $this->default_form($request);
            case 'purchaseorder':
                return $this->default_form($request);
            case 'purchaserequest':
                return $this->default_form($request);
            default:
                abort(404);
        }
    }
    
    public function default_page(Request $request)
    {   
        return view('other.attachment_upload.attachment_upload');
    }

    public function default_data(Request $request)
    {   
        $table = DB::table('finance.attachment')
                ->where('compcode','=',session('compcode'))
                ->where('page','=',$request->page)
                ->where('auditno','=',$request->idno);

        $responce = new stdClass();
        $responce->rows = $table->get();

        return json_encode($responce);
    }

    public function default_form(Request $request)
    {   
        $type = $request->file('file')->getClientMimeType();
        if(!empty($request->rename)){
            $filename = $request->rename;
        }else{
            $filename = $request->file('file')->getClientOriginalName();
        }
        $file_path = $request->file('file')->store('attachment', 'attachment_uploads');
        DB::table('finance.attachment')
            ->insert([
                'compcode' => session('compcode'),
                'resulttext' => $filename,
                'attachmentfile' => $file_path,
                'adduser' => 'system',
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'page' => $request->page,
                'auditno' => $request->idno,
                'type' => $type,
                'trxdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);

        return redirect()->back();
    }

    public function upload(Request $request)
    {   
        $user = DB::table('hisdb.pat_mast')->where('mrn','=',$request->mrn)->first();
        return view('hisdb.upload.upload',compact('user'));
    }

    
    public function thumbnail($folder,$image_path){
        $attachment_path = 'C:\laragon\www\medicare';

        if($folder == 'attachment'){ //image
            $img = Image::make($attachment_path.'/uploads/'.$folder.'/'.$image_path)->resize(64, 64);
        }else if($folder == 'application'){
            switch($image_path){
                case 'pdf': $img = Image::make($attachment_path.'/uploads/pat_enq/pdf_icon.png')->resize(64, 64); break;
                case 'msword': $img = Image::make($attachment_path.'/uploads/pat_enq/word_icon.png')->resize(64, 64); break;
                case 'powerpoint': $img = Image::make($attachment_path.'/uploads/pat_enq/powerpoint_icon.png')->resize(64, 64); break;
                case 'excel': $img = Image::make($attachment_path.'/uploads/pat_enq/excel_icon.png')->resize(64, 64); break;
            }
        }else if($folder == 'video'){
            switch($image_path){
                case 'video': $img = Image::make($attachment_path.'/uploads/pat_enq/video-icon.png')->resize(64, 64); break;
            }
        }else if($folder == 'audio'){
            switch($image_path){
                case 'audio': $img = Image::make($attachment_path.'/uploads/pat_enq/audio-icon.png')->resize(64, 64); break;
            }
        }else if($folder == 'text'){
            switch($image_path){
                case 'notepad': $img = Image::make($attachment_path.'/uploads/pat_enq/notepad_icon.png')->resize(64, 64); break;
            }
        }else{

        }

        return $img->response();
    }

    public function download(Request $request,$folder,$image_path){
        $attachment_path = 'C:\laragon\www\medicare';

        $file = $attachment_path."\\uploads\\".$folder."\\".$image_path;
        // dump($file);
        return Response::download($file,$request->filename);
    }


    // public function form(Request $request)
    // {   
    //     $type = $request->file('file')->getClientMimeType();
    //     $filename = $request->file('file')->getClientOriginalName();
    //     $file_path = $request->file('file')->store('pat_enq', 'public_uploads');
    //     DB::table('hisdb.patresult')
    //         ->insert([
    //             'compcode' => '-',
    //             'resulttext' => $filename,
    //             'attachmentfile' => $file_path,
    //             'adduser' => 'system',
    //             'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
    //             'mrn' => $request->mrn,
    //             'type' => $type,
    //             'trxdate' => $request->trxdate
    //         ]);
    // }

}