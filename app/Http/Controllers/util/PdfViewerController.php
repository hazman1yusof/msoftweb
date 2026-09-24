<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PdfViewerController extends defaultController
{   

    public function __construct(){
        // $this->middleware('auth');
    }

    public function page(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type = $request->type;
        $filename = $type."_".$mrn."_".$episno."_".$entereddate.".pdf";
        $attachment_path = \config('get_config.ATTACHMENT_PATH');

        if(Storage::disk('pdfViewer_uploads')->exists('attachment/'.$filename)) {
            $file_url = 'pdf/attachment/'.$filename;
        }else{
            $file_url = 'pdf/blank/'.$request->type.'.pdf';
        }

        return redirect('/PdfViewer2?file='.$file_url.'&mrn='.$mrn.'&episno='.$episno.'&entereddate='.$entereddate.'&type='.$type);
    }

    public function redirect(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type = $request->type;

        return view('other.pdf.pdf',compact('mrn','episno','entereddate','type'));
    }

    public function savePdf(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type = $request->type;

        /*
         * Check PDF
         */
        if (!$request->hasFile('pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'PDF file not received.'
            ], 400);
        }

        /*
         * Check PNG
         */
        if (!$request->hasFile('png')) {
            return response()->json([
                'success' => false,
                'message' => 'PNG file not received.'
            ], 400);
        }

        $pdf = $request->file('pdf');
        $png = $request->file('png');

        $filename_pdf = $type."_".$mrn."_".$episno."_".$entereddate.".pdf";
        $filename_png = $type."_".$mrn."_".$episno."_".$entereddate.".png";
        
        // dd($filename_pdf);

        $file_path_pdf = $pdf->storeAs('attachment',$filename_pdf,'pdfViewer_uploads');
        $file_path_png = $png->storeAs('attachment',$filename_png,'pdfViewer_uploads');

        return response()->json([
            'success' => true,
            'message' => 'PDF saved successfully.',
        ]);
    }

    public function pngView(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type = $request->type;
        $filename = $type."_".$mrn."_".$episno."_".$entereddate.".png";
        $attachment_path = \config('get_config.ATTACHMENT_PATH');

        if(empty($mrn)){
            $file_url = $attachment_path.'/pdf/blank/'.$request->type.'.png';

            $img = Image::make($file_url);

        }else if(Storage::disk('pdfViewer_uploads')->exists('attachment/'.$filename)) {
            $file_url = $attachment_path.'/pdf/attachment/'.$filename;

            $img = Image::make($file_url);
        }else{
            $file_url = $attachment_path.'/pdf/blank/'.$request->type.'.png';

            $img = Image::make($file_url);
        }

        return $img->response();
    }

}