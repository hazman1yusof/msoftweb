<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;
use Illuminate\Support\Facades\Storage;

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
                return $this->default_page($request);
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
            case 'get_merge_pdf':
                return $this->get_merge_pdf($request);
            case 'merge_pdf_with_attachment':
                return $this->merge_pdf_with_attachment($request);
            default:
                return $this->default_data($request);
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
            case 'merge_pdf':
                return $this->merge_pdf($request);
            default:
                return $this->default_form($request);
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
        $file_path = $request->file('file')->store('attachment', \config('get_config.ATTACHMENT_UPLOAD'));
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
        $attachment_path = \config('get_config.ATTACHMENT_PATH');

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
        $attachment_path = \config('get_config.ATTACHMENT_PATH');

        $file = $attachment_path."\\uploads\\".$folder."\\".$image_path;
        // dump($file);
        return Response::download($file,$request->filename);
    }

    public function merge_pdf(Request $request){
        Storage::disk('pdf_merge')->put($request->merge_key.'_'.$request->lineno_.'.pdf',base64_decode($request->base64));
        DB::table('sysdb.pdf_merge')
            ->insert([
                'compcode' => session('compcode'),
                'merge_key' => $request->merge_key,
                'lineno_' => $request->lineno_,
            ]);
    }

    public function get_merge_pdf(Request $request){//guna database bila merge antara document
        $merge_key = $request->merge_key;
        $pdf_merge = DB::table('sysdb.pdf_merge')
                        ->where('compcode',session('compcode'))
                        ->where('merge_key',$merge_key);

        if($pdf_merge->exists()){
            $pdf_merge = $pdf_merge->get();
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;

            foreach ($pdf_merge as $obj) {
                $pdf->addPDF(public_path().'/uploads/pdf_merge/'.$merge_key.'_'.$obj->lineno_.'.pdf', 'all');
            }
        }

        $filesForDelete = array_filter(glob(public_path().'/uploads/pdf_merge/*'), function($file) use ($merge_key) {
            if(str_contains($file, $merge_key)){
                return false;
            }
            unlink($file);
            return true;
        });
        $pdf->merge('browser', public_path() . '/uploads/pdf_merge/'.$merge_key.'.pdf', 'P');
    }

    public function merge_pdf_with_attachment(Request $request){
        $merge_key = $request->merge_key;
        $pdf_merge = DB::table('sysdb.pdf_merge')
                        ->where('compcode',session('compcode'))
                        ->where('merge_key',$merge_key);

        if($pdf_merge->exists()){
            $pdf_merge = $pdf_merge->get();
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;

            foreach ($pdf_merge as $obj) {
                $pdf->addPDF(public_path().'/uploads/pdf_merge/'.$merge_key.'_'.$obj->lineno_.'.pdf', 'all');
            }
        }
        
        foreach ($request->attach_array as $attach) {
            $pdf->addPDF(public_path().'/uploads/'.$attach, 'all');
        }
        $pdf->merge('browser', public_path() . '/uploads/pdf_merge/'.$merge_key.'_merged.pdf', 'P');
    }

}