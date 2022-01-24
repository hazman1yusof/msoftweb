<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DiagnoseController extends defaultController
{   

    public function post(Request $request)
    {   
        
        // print_r($request->post());

        try {

            foreach ($request->post() as $key => $value) {
                switch ($key) {
                    case 'diagnose':
                        $this->diagnose($value);
                        break;
                    case 'patexam':
                        $this->patexam($value);
                        break;
                    case 'pathealth':
                        $this->pathealth($value);
                        break;
                    case 'pathistory':
                        $this->pathistory($value);
                        break;
                    case 'charges':
                        $this->charges($value);
                        break;
                    case 'patmast':
                        $this->patmast($value);
                        break;
                    case 'episode':
                        $this->episode($value);
                        break;
                }
            }
            echo 'success';

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
        }
    }

    public function diagnose($value)
    { 

        $diagnose_arr = $value;

        foreach ($diagnose_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }

            $episode_obj = DB::table('hisdb.episode')->where('mrn','=',$array['mrn'])->where('episno','=',$array['episno']);

            if($episode_obj->exists()){
                $episode_obj->update($array);
            }
        }

    }

    public function patexam($value)
    { 
        $patexam_arr = $value;

        foreach ($patexam_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }

            $episode_obj = DB::table('hisdb.patexam')->where('mrn','=',$array['mrn'])->where('episno','=',$array['episno']);

            if($episode_obj->exists()){
                $episode_obj->update($array);
            }else{
                $episode_obj->insert($array);
            }
        }
    }

    public function pathealth($value)
    { 
        
        $pathealth_arr = $value;

        foreach ($pathealth_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }

            $episode_obj = DB::table('hisdb.pathealth')->where('mrn','=',$array['mrn'])->where('episno','=',$array['episno']);

            if($episode_obj->exists()){
                $episode_obj->update($array);
            }else{
                $episode_obj->insert($array);
            }
        }
    }

    public function pathistory($value)
    { 
        
        $pathistory_arr = $value;

        foreach ($pathistory_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }

            $episode_obj = DB::table('hisdb.pathistory')->where('mrn','=',$array['mrn']);

            if($episode_obj->exists()){
                $episode_obj->update($array);
            }else{
                $episode_obj->insert($array);
            }
        }
    }

    public function charges($value)
    { 
        
        $chargetrx_arr = $value;

        foreach ($chargetrx_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }

            $chargetrx_obj = DB::table('hisdb.chargetrx')->where('auditno','=',$array['auditno']);

            if($chargetrx_obj->exists()){
                $chargetrx_obj->update($array);
            }else{
                $chargetrx_obj->insert($array);
            }
        }
    }

    public static function turn_date($from_date,$from_format='d/m/Y'){
        $carbon = Carbon::createFromFormat($from_format,$from_date);
        return $carbon;
    }

    public function emergency(Request $request){

        $getid = $request->getid;
        $iniarray = parse_ini_file(public_path().'/uploadini/'.$getid."_patmast.ini", true, 0);

        foreach ($iniarray as $key => $value) {
            $value = $this->turn_into_appro_array_patmast($value);

            $patmast_obj = DB::table('hisdb.pat_mast')->where('mrn','=',$value['MRN']);

            if($patmast_obj->exists()){
                $patmast_obj->update($value);
                $lastid = $patmast_obj->first()->idno;
            }else{
                $lastid = DB::table('hisdb.pat_mast')->insertGetId($value);
            }

            
            $users_obj = DB::table('sysdb.users')->where('username','=',$value['MRN']);  
            
            if(!$users_obj->exists()){
                DB::table('sysdb.users')->insert([
                    'compcode' => '9A',
                    'username' => $value['MRN'],
                    'password' => $value['MRN'],
                    'name' => $value['Name'],
                    'groupid' => 'patient'
                ]);
            }

        }
        $patmast = DB::table('pat_mast')->where('idno','=',$lastid)->first();

        $iniarray = parse_ini_file(public_path().'/uploadini/'.$getid."_episode.ini", true, 0);

        foreach ($iniarray as $key => $value) {
            $value = $this->turn_into_appro_array($value);

            $episode_obj = DB::table('hisdb.episode')->where('mrn','=',$value['mrn'])->where('episno','=',$value['episno']);

            if($episode_obj->exists()){
                $episode_obj->update($value);
                $lastid = $episode_obj->first()->idno;
            }else{
                $lastid = DB::table('hisdb.episode')->insertGetId($value);
            }

        }
        $episode = DB::table('hisdb.episode')->where('idno','=',$lastid)->first();

        return view('patmast.emergency',compact('episode','patmast'));

    }

    public function patmast($value)
    { 
         $patmast_arr = $value;

        foreach ($patmast_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }
            $array = $this->turn_into_appro_array_patmast($array);

            $patmast_obj = DB::table('hisdb.pat_mast')->where('mrn','=',$array['mrn']);

            if($patmast_obj->exists()){
                $patmast_obj->update($array);
            }else{
                DB::table('hisdb.pat_mast')->insert($array);
            }
        }
    }

    public function turn_into_appro_array_patmast($array){
        $int_array = ['MRN','Episno','Postcode','Century','Accum_chg','Accum_Paid','first_visit_date','last_visit_date','Reg_Date','last_episno','FirstIpEpisNo','FirstOpEpisNo','AddDate','Lastupdate','NewMrn','pPostCode','DeceasedDate','upddate','idno'];
        $date_array = ['DOB','first_visit_date','Reg_Date','last_visit_date','AddDate','Lastupdate','DeceasedDate','upddate'];

        foreach ($array as $key => $value) {
            // if(in_array($key, $date_array) && !empty($value)){
            //  $array[$key] = $this->turn_date($value);
            // }
            if(in_array($key, $int_array) && empty($value)){
                unset($array[$key]);
            }
        }
        // dd($array);

        return $array;
    }

    public function episode($value)
    { 
        $episode_arr = $value;

        foreach ($episode_arr as $obj) {
            $array = [];

            foreach ($obj as $data) {
                $array[$data[0]] = $data[1];
            }
            $array = $this->turn_into_appro_array_episode($array);

            $episode_obj = DB::table('hisdb.episode')->where('mrn','=',$array['mrn'])->where('episno','=',$array['episno']);

            if($episode_obj->exists()){
                $episode_obj->update($array);
            }else{
                DB::table('hisdb.episode')->insert($array);
            }
        }
    }

    public function turn_into_appro_array_episode($array){
        $int_array = ['episno','depositreq','deposit','adddate','reg_date','dischargedate','allocdoc','allocbed','allocnok','allocicd','allocpayer','lastupdate','conversion','newcaseNP','newcaseP','followupP','followupNP','PkgAutoNo','PkgAutoNo','AdminFees','idno'];
        $date_array = ['adddate','reg_date','dischargedate','lastupdate','Lastupdate','DeceasedDate','upddate'];
        foreach ($array as $key => $value) {
            // if(in_array($key, $date_array) && !empty($value)){
            //  $array[$key] = $this->turn_date($value);
            // }
            if(in_array($key, $int_array) && empty($value)){
                unset($array[$key]);
            }
        }

        return $array;
    }


    public function test(Request $request)
    { 
        $queue = DB::table('hisdb.queue')
                        ->where('compcode','=','9A')
                        ->get();

        foreach ($queue as $key => $value) {
            $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',$value->compcode)
                        ->where('mrn','=',$value->mrn)
                        ->where('episno','=',$value->episno);

            if(!$episode->exists()){
                DB::table('hisdb.queue')
                        ->where('compcode','=','9A')
                        ->where('mrn','=',$value->mrn)
                        ->where('episno','=',$value->episno)
                        ->delete();
            }
        }
    }

}