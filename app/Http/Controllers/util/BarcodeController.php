<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class BarcodeController extends defaultController
{   

    public function __construct()
    {

    }

    public function show(Request $request)
    {   
        return view('test.barcode');
    }


    public function form(Request $request)
    {   
        $barcode = $request->itemcode;
        $desc = $request->desc;
        $contents = 
        "[DATA1]
PRICE=RM 88.54

BARCODE=".$barcode."

NAME=".$desc."

OTH=price are inclusive 6% GST";

        Storage::put('StockLabel\pharmacy.ini', $contents);


        $process = new Process('C:\laragon\www\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        // $process = new Process('C:\xampp\htdocs\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();


    }

    public function print(Request $request){
        $delorddt_obj = DB::table('material.delorddt')
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('delorddt.unit','=',session('unit'))
                ->where('delorddt.recno','=',$request->recno)
                ->where('delorddt.recstatus','!=','DELETE')
                ->get();
        $contents = "";

        foreach ($delorddt_obj as $key => $value) {
            $product = DB::table('material.product')
                ->where('product.compcode','=',session('compcode'))
                ->where('product.unit','=',session('unit'))
                ->where('product.itemcode','=',$value->itemcode)
                ->first();

            $cont = $key+1;

            $contents .= "
[DATA".$cont."]
PRICE=RM 88.54

BARCODE=".$value->itemcode."

NAME=".$product->description."

OTH=price are inclusive 6% GST";
        }
        
        Storage::put('StockLabel\pharmacy.ini', $contents);


        $process = new Process('C:\laragon\www\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        // $process = new Process('C:\xampp\htdocs\msoftweb\app\Http\Controllers\util\runbarcode.bat');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();


    }

}