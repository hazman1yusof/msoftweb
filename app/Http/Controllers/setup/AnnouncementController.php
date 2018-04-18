<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class AnnouncementController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "code";
    }

    public function show(Request $request)
    {   
        return view('setup.marital.marital');
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

    public function generate(Request $request){
        $ret_str="
            <div id='myCarousel' class='carousel slide' data-ride='carousel'>
                <ol class='carousel-indicators'>";

        $SQLcnt = DB::table('sysdb.compose')
            ->whereRaw("type='announcement' AND NOW() BETWEEN dateFrom AND dateTo and compcode=?",[session('compcode')]);

        $count=$SQLcnt->count();
        
        for($x=0;$x<intval($count);$x++){
            $ret_str.="
                    <li data-target='#myCarousel' data-slide-to='{$x}' ";
            if($x==0)$ret_str.="class='active'";
            $ret_str.="></li>";
        }
        $ret_str.="
            </ol>
            <div class='carousel-inner' role='listbox'>";
        
        $announcement_obj = $SQLcnt->get();
        
        $x=0;
        foreach ($announcement_obj as $key => $row) {
            $ret_str.="
                <div class='item";
            if($x==0)$ret_str.=" active";
            $ret_str.="'>";
            
            $ret_str.="
                <img src='{$row->imgLoc}'>";
            $ret_str.="
                    <div class='container'>
                        <div class='carousel-caption'>";
            $ret_str.="
                            <h1>{$row->title}</h1>
                                {$row->contains}
                        </div>
                    </div>
                </div>";
            $x++;
        }
        
        $ret_str.="
            </div>
              <a class='left carousel-control' href='#myCarousel' role='button' data-slide='prev'>
                <span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>
                <span class='sr-only'>Previous</span>
              </a>
              <a class='right carousel-control' href='#myCarousel' role='button' data-slide='next'>
                <span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span>
                <span class='sr-only'>Next</span>
              </a>
            </div>";
        
    
        return '{"res":' .  json_encode($ret_str) . '}';
    }
}