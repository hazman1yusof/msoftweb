<?php

namespace App\Http\Controllers\ukmsc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APEnquiryExportv2;
use Maatwebsite\Excel\Facades\Excel;

class ukmsc_analyticController extends defaultController
{   

    public function show(Request $request)
    {   
        // return view('finance.AP.apenquiry.apenquiry');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'upload_doctoranalytic':
                return $this->upload_doctoranalytic($request);
            case 'upload_doctormaster':
                return $this->upload_doctormaster($request);
            default:
                return 'error happen..';
        }
    }

    public function upload_doctoranalytic(Request $request){
        $payload = $request->all();

        // Fire-and-forget to Python
        Http::timeout(2)->post(
            'http://localhost:5000/api/doctoranalytic',
            $payload
        );

        return response()->json([
            'status' => 'queued'
        ]);
    }

    public function upload_doctormaster(Request $request){
        $payload = $request->all();

        // Fire-and-forget to Python
        Http::timeout(2)->post(
            'http://localhost:5000/api/doctormaster',
            $payload
        );

        return response()->json([
            'status' => 'queued'
        ]);
    }

}