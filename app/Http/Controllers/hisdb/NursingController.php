<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class NursingController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function show(Request $request)
    {   
        return view('hisdb.nursing.nursing');
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
            default:
                return 'error happen..';
        }
    }

    // public function table(Request $request)
    // {
    //     foreach ($rows as $key => $value) {
    //         $patmast_obj = DB::table('hisdb.pat_mast AS p')
    //                     ->select(['p.Newic','p.id_type','p.oldic','p.dob','p.idnumber', 'p.racecode', 'r.description', 'p.sex', 'p.DOB'])
    //                     ->join('hisdb.racecode AS r', function($join) use ($request){
    //                         $join = $join->on('r.code','=','p.racecode');
    //                         $join = $join->on('r.compcode','=','p.compcode');
    //                     })
    //                     ->where('p.mrn','=',$value->a_mrn)
    //                     ->where('p.compcode','=',session('compcode'))
    //                     ->first();

    //         // dump($patmast_obj->toSql());
    //         // dd($patmast_obj->getBindings());

    //         $rows[$key]->newic = $patmast_obj->Newic;
    //         $rows[$key]->racecode = $patmast_obj->racecode;
    //         $rows[$key]->race = $patmast_obj->description;
    //         $rows[$key]->sex = $patmast_obj->sex;
    //         $rows[$key]->id_type = $patmast_obj->id_type;
    //         $rows[$key]->oldic = $patmast_obj->oldic;
    //         $rows[$key]->dob = $patmast_obj->dob;
    //         $rows[$key]->idnumber = $patmast_obj->idnumber;
    //         $rows[$key]->age = Carbon::parse($patmast_obj->DOB)->age;
    //     }
    // }
}