<?php

namespace App\Http\Controllers\rehab;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class OccupTherapyNotesController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.occupTherapy.occupTherapy_notes');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeNotes':
                return $this->get_table_datetimeNotes($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_notes':
                switch($request->oper){
                    case 'add':
                        return $this->add_notes($request);
                    case 'edit':
                        return $this->edit_notes($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_notes':
                return $this->get_table_notes($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table_datetimeNotes(Request $request){
        
        $responce = new stdClass();
        
        $notes_obj = DB::table('hisdb.ot_notes')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($notes_obj->exists()){
            $notes_obj = $notes_obj->get();
            
            $data = [];
            
            foreach($notes_obj as $key => $value){
                if(!empty($value->dateNotes)){
                    $date['dateNotes'] =  Carbon::createFromFormat('Y-m-d', $value->dateNotes)->format('d-m-Y');
                }else{
                    $date['dateNotes'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_notes(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_notes')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateNotes' => $request->dateNotes,
                        'notes' => $request->notes,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_notes(Request $request){
        
        DB::beginTransaction();
        
        try {

            $notes = DB::table('hisdb.ot_notes')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('dateNotes','=',$request->dateNotes);
        
            if(!empty($request->idno_notes)){
                DB::table('hisdb.ot_notes')                    
                ->where('idno','=',$request->idno_notes)
                    ->update([
                        'dateNotes' => $request->dateNotes,
                        'notes' => $request->notes,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{

                if($notes->exists()){
                    return response('Date already exist.');
                }

                DB::table('hisdb.ot_notes')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateNotes' => $request->dateNotes,
                        'notes' => $request->notes,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }

            $queries = DB::getQueryLog();
            
            DB::commit();

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_notes(Request $request){
        
        $notes_obj = DB::table('hisdb.ot_notes')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($notes_obj->exists()){
            $notes_obj = $notes_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $notes_obj->dateNotes)->format('Y-m-d');

            $responce->notes = $notes_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function notes_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateNotes = $request->dateNotes;

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $notes = DB::table('hisdb.ot_notes as n')
                ->select('n.mrn','n.episno','n.dateNotes','n.notes','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','n.mrn');
                    $join = $join->on('pm.Episno','=','n.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('n.compcode','=',session('compcode'))
                ->where('n.mrn','=',$mrn)
                ->where('n.episno','=',$episno)
                ->where('n.dateNotes','=',$dateNotes)
                ->first();
        // dd($notes);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.occupTherapy.notesChart_pdfmake',compact('notes'));
        
    }
    
}