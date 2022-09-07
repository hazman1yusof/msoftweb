<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use File;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class MycardController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mykadFP(Request $request)
    {  
        return view('hisdb.mykadfp.mykadfp');
    }

    public function mykadfp_store(Request $request){

        $company = DB::table('sysdb.company')
                    ->where('compcode',session('compcode'))
                    ->first();

        $path_txt = $company->mykadfolder."\mykad".Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d').'.txt';
        $path_img = $company->mykadfolder."\myphoto\\".$request->icnum.".png";

        $myfile = fopen($path_txt, "a") or die("Unable to open file!");

        $text = $request->name.'|'.$request->icnum.'|'.$request->gender.'|'.$request->dob.'|'.$request->birthplace.'|'.$request->race.'|'.$request->religion.'|'.$request->address1.'|'.$request->address2.'|'.$request->address3.'|'.$request->city.'|'.$request->state.'|'.$request->postcode;

        fwrite($myfile, "\n".$text);
        fclose($myfile);


        $img = base64_decode($request->base64);
        file_put_contents($path_img, $img);
    }

}