<?php

namespace App\Http\Controllers;

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
        return view('preregister');
    }


    public function prereg(Request $request){

    	if($request->select == 'ic'){
	    	$validatedData = $request->validate([
		        'ic' => 'required|max:20|min:10',
		    ]);
    	}else{
	    	$validatedData = $request->validate([
		        'idnumber' => 'required|max:20|min:5',
		    ]);
    	}

	    DB::beginTransaction();
        
        try {

        	if($request->select == 'ic'){
	        	$pat_mast = DB::table('hisdb.pat_mast')
	        				->where('Active','1')
		    				->where('Newic','=', $request->ic);

		    	if($pat_mast->exists()){
			    	$pat_mast_obj = $pat_mast->first();
			    	
			    }else{
			    	// pleae register at counter alert
			    	return redirect()->back()->withErrors('No I/C in Database, please register at the counter first');
			    }
        	}else{
	        	$pat_mast = DB::table('hisdb.pat_mast')
	        				->where('Active','1')
		    				->where('idnumber','=', $request->idnumber);

		    	if($pat_mast->exists()){
			    	$pat_mast_obj = $pat_mast->first();
			    	
			    }else{
			    	// pleae register at counter alert
			    	return redirect()->back()->withErrors('No passport / idnumber in Database, please register at the counter first');
			    }
        	}

		    $mrn = $pat_mast_obj->MRN;
		    $episno = $pat_mast_obj->Episno;

		    if(intval($episno) < 1){
		    	return redirect()->back()->withErrors('Episode not registered yet, please register at the counter first');
		    }

            $table = DB::table('hisdb.dialysis_episode');

            //check if date,mrn,episno duplicate
            $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                ->where('compcode','13A')
                                ->where('mrn',$mrn)
                                ->where('episno',$episno)
                                ->whereDate('arrival_date',Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

            if(!$dialysis_epis->exists()){
                $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                ->where('compcode','13A')
                                ->where('mrn',$mrn)
                                ->where('episno',$episno);

                if($dialysis_epis->exists()){
                    $lineno_ = intval($dialysis_epis->max('lineno_')) + 1;

                    $dialysis_epis_latest = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode','13A')
                                    ->where('mrn',$mrn)
                                    ->where('episno',$episno)
                                    ->where('lineno_',intval($dialysis_epis->max('lineno_')));

                    $mcrstat = $dialysis_epis_latest->first()->mcrstat;
                    $mcrtype = $dialysis_epis_latest->first()->mcrtype; 
                    $hdstat = $dialysis_epis_latest->first()->hdstat;
                    $packagecode = $dialysis_epis_latest->first()->packagecode;
                }else{
                    $lineno_ = 1;
                    $mcrstat = 0;
                    $mcrtype = null;
                    $hdstat = 0;
                    $packagecode = 'EPO';
                }

                $array_insert = [
                    'compcode'=>'13A',
                    'mrn'=>$mrn,
                    'episno'=>$episno,
                    'lineno_'=>$lineno_,
                    'mcrstat'=>$mcrstat,
                    'hdstat'=>$hdstat,
                    'arrival_date'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'arrival_time'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'packagecode'=>$packagecode,
                    'mcrtype'=>$mcrtype,
                    'order'=>0,
                    'complete'=>0
                ];
        
                $latest_idno = $table->insertGetId($array_insert);

                DB::table('hisdb.episode')
                    ->where('compcode','13A')
                    ->where('mrn',$mrn)
                    ->where('episno',$episno)
                    ->update([
                        'lastarrivalno' => $latest_idno,
                        'lastarrivaldate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastarrivaltime' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }else{
    			return redirect()->back()->withSuccess("You already registered today");
            }

            DB::commit();

    		return redirect()->back()->withSuccess("Thank you, you have succesfully registered");

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

	   //  $pre_episode = DB::table('hisdb.pre_episode')
	   //  				->whereDate('adddate', '=', Carbon::now("Asia/Kuala_Lumpur"))
	   //  				->where('Newic','=', $request->ic);

	   // 	if($pre_episode->exists()){
	   //  	return redirect()->back()->withSuccess('You already registered today');
    // 	}

	   //  $pat_mast = DB::table('hisdb.pat_mast')
	   //  				->where('Newic','=', $request->ic);

	    

	   //  if($pat_mast->exists()){
	   //  	$pat_mast_obj = $pat_mast->first();

	   //  	$add_array = [
				// 'compcode' => '9A',
				// 'mrn' => $pat_mast_obj->MRN,
				// 'episno' => $pat_mast_obj->Episno +1,
				// 'Name' => $pat_mast_obj->Name,
				// 'telhp' => $pat_mast_obj->telhp,
				// 'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
				// 'apptdate' => Carbon::now("Asia/Kuala_Lumpur"),
				// 'episactive' => 0,
				// 'Newic' => $request->ic,
		  //   ];

	   //  	$last_episode  = DB::table('hisdb.episode')
	   //  						->where('mrn',$pat_mast_obj->MRN)
	   //  						->where('episno',$pat_mast_obj->Episno);

	   //  	if($last_episode->exists()){
	   //  		$last_episode = $last_episode->first();
	   //  		$tomerge = [
	   //  			'regdept' => $last_episode->regdept,
	   //  			'case_code' => $last_episode->case_code,
	   //  			'admdoctor' => $last_episode->admdoctor,
	   //  			'pay_type' => $last_episode->pay_type,
	   //  			'pyrmode' => $last_episode->pyrmode,
	   //  			'billtype' => $last_episode->billtype,
	   //  			'newcaseP' => $last_episode->newcaseP,
	   //  			'newcaseNP' => $last_episode->newcaseNP,
	   //  			'followupP' => $last_episode->followupP,
	   //  			'followupNP' => $last_episode->followupNP
	   //  		];

	   //  		$add_array = array_merge($add_array, $tomerge);
	   //  	}
	   //  }else{
	   //  	// pleae register at counter alert
	   //  	return redirect()->back()->withErrors('No I/C in Database, please register at the counter first');
	   //  }

	   //  DB::table('hisdb.pre_episode')->insert($add_array);

    // 	return redirect()->back()->withSuccess("Thank you, you have succesfully registered");

    }
}