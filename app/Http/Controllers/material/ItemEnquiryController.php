<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use stdClass;

class ItemEnquiryController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        // $this->detailMovement($request);
        return view('material.itemInquiry.itemInquiry');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'detailMovement':
                return $this->detailMovement($request);
            default:
                return 'error happen..';
        }
    }

    public function detailMovement(Request $request){
        $det_mov_deptcode = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.deptcode','d.sndrcv')
                ->leftJoin('material.ivtxnhd as h', 'd.recno', '=', 'h.recno')
                ->leftJoin('material.ivtxntype as t', 'd.trantype', '=', 't.trantype')
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$request->itemcode)
                ->where('d.deptcode','=',$request->deptcode)
                ->where('d.uomcode','=',$request->uomcode)
                ->where('d.trandate','>=',$request->trandate_from)
                ->where('d.trandate','<=',$request->trandate_to)
                ->orderBy('d.adddate', 'asc')
                ->get();

        $det_mov_deptcode = $det_mov_deptcode->each(function ($item, $key) {
            $item->det_mov = 'deptcode';
        });
        


        $det_mov_sndrcv = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.deptcode','d.sndrcv')
                ->leftJoin('material.ivtxnhd as h', 'd.recno', '=', 'h.recno')
                ->leftJoin('material.ivtxntype as t', 'd.trantype', '=', 't.trantype')
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$request->itemcode)
                ->where('d.sndrcv','=',$request->deptcode)
                ->where('d.uomcode','=',$request->uomcode)
                ->where('d.trandate','>=',$request->trandate_from)
                ->where('d.trandate','<=',$request->trandate_to)
                ->orderBy('d.adddate', 'asc')
                ->get();

        $det_mov_sndrcv = $det_mov_sndrcv->each(function ($item, $key) {
            $item->det_mov = 'sndrcv';
        });

        $merged = $det_mov_deptcode->merge($det_mov_sndrcv);
        $merged = $merged->sortBy(function($col){
                        return $col;
                    })->values()->all();

        $responce = new stdClass();
        $responce->rows = $merged;
        // dd($merged);

        return json_encode($responce);
    }
}