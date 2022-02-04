<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class MycardController extends defaultController
{   

    public function __construct()
    {

    }

    public function get_data(Request $request)
    {   
        // dd(public_path());
        // $process = new Process('C:\laragon\www\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        // $process = new Process(public_path().'\mykad\runmycard.bat');
        $process = Process::fromShellCommandline(public_path().'\mykad\runmycard.bat');
        $process->run();

        // // executes after the command finishes
        // if ($process->isSuccessful()) {
        //     // throw new ProcessFailedException($process);
        //     $contents = File::get(public_path('\mykad\mykad.txt'));
        //     if($contents == 'Invalid license key. Please contact your vendor.'){
                
        //         $responce = new stdClass();
        //         $responce->status = 'failed';
        //         $responce->reason = 'Invalid license key. Please contact your vendor.';
        //     }else{
        //         $array_mycard = explode("|",$contents);
                
        //         $responce = new stdClass();
        //         $respoce->status = 'success';
        //         $responce->ic = $array_mycard[0];
        //         $responce->dob = $array_mycard[1];
        //         $responce->birthplace = $array_mycard[2];
        //         $responce->name = $array_mycard[3];
        //         $responce->oldic = $array_mycard[4];
        //         $responce->religion = $array_mycard[5];
        //         $responce->sex = $array_mycard[6];
        //         $responce->race = $array_mycard[7];
        //         $responce->addr1 = $array_mycard[8];
        //         $responce->addr2 = $array_mycard[9];
        //         $responce->addr3 = $array_mycard[10];
        //         $responce->postcode = $array_mycard[11];
        //         $responce->city = $array_mycard[12];
        //         $responce->state = $array_mycard[13];
        //     }
        // }else{
        //     $responce = new stdClass();
        //     $responce->status = 'failed';
        //     $responce->reason = 'Other';
        // }

        // return json_encode($responce);
    }


    public function mykadFP(Request $request)
    {  
        return view('hisdb.mykadfp.mykadfp');
    }


    public function mykadfp_store(Request $request)
    {  
        if($request->type == 'mykad'){
            // $txt = $_POST['icnum']."|".
            //         $_POST['dob']."|".
            //         $_POST['name']."|".
            //         $_POST['religion']."|".
            //         $_POST['gender']."|".
            //         $_POST['race']."|".
            //         $_POST['address1']."|".
            //         $_POST['address2']."|".
            //         $_POST['address3']."|".
            //         $_POST['postcode']."|".
            //         $_POST['city']."|".
            //         $_POST['state']."|".
            //         $_POST['citizenship']."|".
            //         $_POST['birthplace']."|";

            // $data = base64_decode($_POST['base64']);

            // file_put_contents('C:\cmas\mykad\mykadfp\myphotov1.jpg', $data);
            dd('storing');
        }
    }

}