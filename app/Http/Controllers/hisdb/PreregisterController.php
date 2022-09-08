<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PreregisterController extends defaultController
{
    //
    public function __construct(){
    }

    public function show(Request $request)
    {   
        return view('hisdb.pat_mgmt.preregister');
    }


    public function prereg(Request $request){

    	$validatedData = $request->validate([
	        // $request->select => 'required',
	        'ic' => 'required|max:20|min:10',
	        // 'name' => 'required',
	    ]);

	    // if($request->select = 'ic'){
	    // 	$where_field = "Newic";
	    // }else{
	    // 	$where_field = "telhp";
	    // }

	    $pre_episode = DB::table('hisdb.pre_episode')
	    				->whereDate('adddate', '=', Carbon::now("Asia/Kuala_Lumpur"))
	    				->where('Newic','=', $request->ic);

	   	if($pre_episode->exists()){
	    	return redirect()->back()->withSuccess('You already registered today');
    	}

	    $pat_mast = DB::table('hisdb.pat_mast')
	    				->where('Newic','=', $request->ic);

	    

	    if($pat_mast->exists()){
	    	$pat_mast_obj = $pat_mast->first();

	    	$add_array = [
				'compcode' => '9A',
				'mrn' => $pat_mast_obj->MRN,
				'episno' => $pat_mast_obj->Episno +1,
				'Name' => $pat_mast_obj->Name,
				'telhp' => $pat_mast_obj->telhp,
				'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
				'apptdate' => Carbon::now("Asia/Kuala_Lumpur"),
				'episactive' => 0,
				'Newic' => $request->ic,
		    ];

	    	$last_episode  = DB::table('hisdb.episode')
	    						->where('mrn',$pat_mast_obj->MRN)
	    						->where('episno',$pat_mast_obj->Episno);

	    	if($last_episode->exists()){
	    		$last_episode = $last_episode->first();
	    		$tomerge = [
	    			'regdept' => $last_episode->regdept,
	    			'case_code' => $last_episode->case_code,
	    			'admdoctor' => $last_episode->admdoctor,
	    			'pay_type' => $last_episode->pay_type,
	    			'pyrmode' => $last_episode->pyrmode,
	    			'billtype' => $last_episode->billtype,
	    			'newcaseP' => $last_episode->newcaseP,
	    			'newcaseNP' => $last_episode->newcaseNP,
	    			'followupP' => $last_episode->followupP,
	    			'followupNP' => $last_episode->followupNP
	    		];

	    		$add_array = array_merge($add_array, $tomerge);
	    	}
	    }else{
	    	// pleae register at counter alert
	    	return redirect()->back()->withErrors('No I/C in Database, please register at the counter first');
	    }

	    DB::table('hisdb.pre_episode')->insert($add_array);

    	return redirect()->back()->withSuccess("Thank you, you have succesfully registered");

    }
}