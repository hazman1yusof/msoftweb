<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\itemMov_xlsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ItemMovReportController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.itemMovReport.ItemMovReport');
    }

    public function pdf(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'itemMovFast_pdf':
                return $this->itemMovFast_pdf($request);
            case 'itemMovSlow_pdf':
                return $this->itemMovSlow_pdf($request);
            default:
                return 'error happen..';
        }
    }

    public function excel(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'itemMovFast_excel':
                return $this->itemMovFast_excel($request);
            case 'itemMovSlow_excel':
                return $this->itemMovSlow_excel($request);
            default:
                return 'error happen..';
        }
    }

    public function itemMovFast_pdf(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $date_from = $request->datefrom;
        $date_to = $request->dateto;

        $stockloc = DB::table('material.stockloc as s')
                        ->select('p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                // $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                // $join = $join->where('p.unit', '=', session('unit'));
                            })
                        ->where('s.compcode',session('compcode'));
                        // ->where('s.unit',session('unit'));

        $stockloc = $stockloc->where('s.deptcode',$dept_from);

        $stockloc = $stockloc->where('s.year', $this->toYear($date_to));

        $ivdspdt_array=[];
        foreach ($stockloc->get() as $key => $value) {

            $ivdspdt = DB::table('material.ivdspdt as ivdt')
                        ->where('ivdt.issdept',$value->deptcode)
                        ->where('ivdt.itemcode',$value->itemcode)
                        ->where('ivdt.uomcode',$value->uomcode)
                        ->where('ivdt.compcode',session('compcode'))
                        ->whereDate('trandate', '>=', $date_from)
                        ->whereDate('trandate', '<=', $date_to);

            if(!$ivdspdt->exists()){
                continue;
            }else{

                $array_obj = (array)$value;
                $get_bal = $this->get_bal($array_obj,$this->toMonth($date_to));
                $qtyonhand = $get_bal->close_balqty;
                $qtyonhandval = $get_bal->close_balval;

                $disp_qty = 0;
                $disp_cost = 0;
                $disp_saleamt = 0;

                foreach ($ivdspdt->get() as $key_ivdspdt => $value_ivdspdt){
                    $disp_qty = floatval($disp_qty) + floatval($value_ivdspdt->txnqty);
                    $disp_cost = floatval($disp_cost) + floatval($value_ivdspdt->amount);
                    $disp_saleamt = floatval($disp_saleamt) + floatval($value_ivdspdt->saleamt);
                }

                $topush= [
                    'itemcode' => $value->itemcode,
                    'description' => $value->description,
                    'uomcode' => $value->uomcode,
                    'qtyonhand' => $qtyonhand,
                    'qtyonhandval' => $qtyonhandval,
                    'disp_qty' => $disp_qty,
                    'disp_cost' => $disp_cost,
                    'disp_saleamt' => $disp_saleamt,
                ];

                array_push($ivdspdt_array,$topush);
            }
        }

        usort($ivdspdt_array, function($a, $b){
            return floatval($a['disp_qty']) < floatval($b['disp_qty']);
        });

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->title = 'Fast Moving Item';
        $header->compname = $company->name;
        $header->printby = session('username');
        $header->deptfrom = $dept_from;
        $header->deptto = $dept_to;
        $header->datefrom = $date_from;
        $header->dateto = $date_to;
        
        return view('material.itemMovReport.itemMovFast_pdfmake',compact('ivdspdt_array','header'));
    }

    public function itemMovSlow_pdf(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $date_from = $request->datefrom;
        $date_to = $request->dateto;

        $stockloc = DB::table('material.stockloc as s')
                        ->select('p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                // $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                // $join = $join->where('p.unit', '=', session('unit'));
                            })
                        ->where('s.compcode',session('compcode'));
                        // ->where('s.unit',session('unit'));

        $stockloc = $stockloc->where('s.deptcode',$dept_from);

        $stockloc = $stockloc->where('s.year', $this->toYear($date_to));

        // dd($stockloc->get());

        $ivdspdt_array=[];
        foreach ($stockloc->get() as $key => $value) {

            $ivdspdt = DB::table('material.ivdspdt as ivdt')
                        ->where('ivdt.issdept',$value->deptcode)
                        ->where('ivdt.itemcode',$value->itemcode)
                        ->where('ivdt.uomcode',$value->uomcode)
                        ->where('ivdt.compcode',session('compcode'))
                        ->whereDate('trandate', '>=', $date_from)
                        ->whereDate('trandate', '<=', $date_to);

            if(!$ivdspdt->exists()){
                continue;
            }else{

                $array_obj = (array)$value;
                $get_bal = $this->get_bal($array_obj,$this->toMonth($date_to));
                $qtyonhand = $get_bal->close_balqty;
                $qtyonhandval = $get_bal->close_balval;

                $disp_qty = 0;
                $disp_cost = 0;
                $disp_saleamt = 0;

                foreach ($ivdspdt->get() as $key_ivdspdt => $value_ivdspdt){
                    $disp_qty = floatval($disp_qty) + floatval($value_ivdspdt->txnqty);
                    $disp_cost = floatval($disp_cost) + floatval($value_ivdspdt->amount);
                    $disp_saleamt = floatval($disp_saleamt) + floatval($value_ivdspdt->saleamt);
                }

                $topush= [
                    'itemcode' => $value->itemcode,
                    'description' => $value->description,
                    'uomcode' => $value->uomcode,
                    'qtyonhand' => $qtyonhand,
                    'qtyonhandval' => $qtyonhandval,
                    'disp_qty' => $disp_qty,
                    'disp_cost' => $disp_cost,
                    'disp_saleamt' => $disp_saleamt,
                ];

                array_push($ivdspdt_array,$topush);
            }
        }
        // dd($ivdspdt_array);

        usort($ivdspdt_array, function($a, $b){
            return floatval($a['disp_qty']) > floatval($b['disp_qty']);
        });


        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->title = 'Slow Moving Item';
        $header->compname = $company->name;
        $header->printby = session('username');
        $header->deptfrom = $dept_from;
        $header->deptto = $dept_to;
        $header->datefrom = $date_from;
        $header->dateto = $date_to;
        
        return view('material.itemMovReport.itemMovFast_pdfmake',compact('ivdspdt_array','header'));
    }

    public function itemMovFast_excel(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_from' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $type = 'fast';
        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $date_from = $request->datefrom;
        $date_to = $request->dateto;

        return Excel::download(new itemMov_xlsExport($type,$dept_from,$dept_to,$date_from,$date_to), 'itemMovFast_excel.xlsx');
    }

    public function itemMovSlow_excel(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_from' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $type = 'slow';
        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $date_from = $request->datefrom;
        $date_to = $request->dateto;

        return Excel::download(new itemMov_xlsExport($type,$dept_from,$dept_to,$date_from,$date_to), 'itemMovSlow_excel.xlsx');
    }

    public function get_bal($array_obj,$period){
        $open_balqty = $array_obj['openbalqty'];
        $close_balqty = 0;
        $open_balval = $array_obj['openbalval'];
        $close_balval = 0;
        $until = intval($period) - 1;

        for ($from = 1; $from <= $until; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = $open_balval + $array_obj['netmvval'.$from];
        }

        for ($from = 1; $from <= intval($period); $from++) { 
            $close_balqty = $close_balqty + $array_obj['netmvqty'.$from];
            $close_balval = $close_balval + $array_obj['netmvval'.$from];
        }

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        $responce->close_balqty = $close_balqty;
        $responce->close_balval = $close_balval;
        return $responce;
    }
}

