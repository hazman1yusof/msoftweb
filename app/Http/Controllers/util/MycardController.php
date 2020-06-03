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
        // $process = new Process('C:\laragon\www\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        $process = new Process('C:\xampp\htdocs\msoftweb\app\Http\Controllers\util\runmycard.bat');
        $process->run();

        // executes after the command finishes
        if ($process->isSuccessful()) {
            // throw new ProcessFailedException($process);
            $contents = File::get(storage_path('app\mycard\mykad32bit\mykad.txt'));
            dd($contents);
        }else{
            dd('error');
        }

        // echo $process->getOutput();


    }

}