<?php

namespace App\Http\Controllers;

use App\religion;
use Illuminate\Http\Request;
use stdClass;

class ReligionController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('setup.religion.religion');
    }

    public function table(Request $request)
    {   

        if(!empty($request->searchCol)){
            $religion = religion::where($request->searchCol[0],'like',$request->searchVal[0])
                            ->paginate($request->rows);
        }else{
            $religion = religion::paginate($request->rows);
        }

        $responce = new stdClass();
        $responce->page = $religion->currentPage();
        $responce->total = $religion->lastPage();
        $responce->records = $religion->total();
        $responce->rows = $religion->items();

        return json_encode($responce);
    }

    public function form(Request $request)
    {
        
    }
}
