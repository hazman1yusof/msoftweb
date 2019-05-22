<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;

class ReviewController extends defaultController
{   

    var $table;

    public function __construct()
    {

    }

    public function review(Request $request)
    {   
        return view('hisdb.review.review');
    }


    public function upload(Request $request)
    {   
        return view('hisdb.upload.upload');
    }

    
    public function thumbnail($folder,$image_path){

        if($folder == 'pat_enq'){ //image
            $img = Image::make('uploads/'.$folder.'/'.$image_path)->resize(96, 96);
        }else if($folder == 'application'){
            switch($image_path){
                case 'pdf': $img = Image::make('uploads/pat_enq/pdf_icon.png')->resize(96, 96); break;
            }
        }else if($folder == 'video'){

        }else if($folder == 'audio'){

        }

        return $img->response();
    }

    public function download($folder,$image_path){
        $file = public_path()."\\uploads\\".$folder."\\".$image_path;
        // dump($file);
        return Response::download($file);
    }


    public function form(Request $request)
    {   
        $type = $request->file('file')->getClientMimeType();
        $file_path = $request->file('file')->store('pat_enq', 'public_uploads');
        DB::table('hisdb.patresult')
            ->insert([
                'compcode' => '-',
                'attachmentfile' => $file_path,
                'adduser' => 'system',
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'mrn' => $request->mrn,
                'type' => $type,
                'trxdate' => $request->trxdate
            ]);
    }

}