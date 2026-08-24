<?php

namespace App\Http\Controllers\stringling;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;
use Illuminate\Support\Facades\Http;

class StringlingMainController extends defaultController
{
    
    public function __construct(){
        $this->middleware('auth');
        $this->baseUrl = \config('get_config.STIRLING_PDF_URL');
    }

    public function show(){
        dd($this->baseUrl);
    }

    public function table(Request $request){
        switch($request->action){
            case 'process':
                return $this->process($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        switch($request->action){
            case 'process':
                return $this->process($request);
            default:
                return 'error happen..';
        }
    }

    public function process(){
        $ATTACHMENT_PATH = \config('get_config.ATTACHMENT_PATH');

        $pdfPath = $ATTACHMENT_PATH.'/uploads/pdf_merge/8n2usBqWSMyTeLgFYZyy_1.pdf';

        if (!file_exists($pdfPath)) {
            throw new \Exception('PDF file not found.');
        }

        $response = Http::attach(
            'fileInput',
            fopen($pdfPath, 'r'),
            'invoice.pdf'
        )->post($this->baseUrl.'/api/v1/misc/compress-pdf');

        if ($response->successful()) {
            return response($response->body())
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'inline; filename="invoice-compressed.pdf"'
                );
        }

        return response()->json([
            'success' => false,
            'message' => $response->body()
        ], $response->status());
    }
    
}