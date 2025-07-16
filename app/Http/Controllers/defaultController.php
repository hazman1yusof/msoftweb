<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

abstract class defaultController extends Controller{

    public function __construct(){
        
    }

    public function default_duplicate($table,$duplicateCode,$duplicateValue){//guna table id, tak fixpost
        return DB::table($table)->where('compcode',session('compcode'))->where($duplicateCode,'=',$duplicateValue)->count();
    }

    public function defaultSetter(Request $request){
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

    public function fixPost(array $column,$rep){ // ni nak replace underscore jadi dot pastu letak AS original(yang ada underscore)
        $temp=[];
        foreach($column as $value){
            $pos = strpos($value, "_");
            if ($pos !== false) {
                $newstring = substr_replace($value, ".", $pos, strlen("."));
                $newstring = $newstring.' AS '.$value;
                array_push($temp,$newstring);
            }
        }   
        return $temp;
    }

    public function fixPost2(array $column){ // ni nak buang lagsung underscore
        $temp=[];
        foreach($column as $value){
            array_push($temp, substr(strstr($value,'_'),1));
        }   
        return $temp;
    }

    public function fixPost3(array $column){ // ni nak replace underscore jadi dot
        $temp=[];
        foreach($column as $value){
            $pos = strpos($value, "_");
            if ($pos !== false) {
                array_push($temp, substr_replace($value, ".", $pos, strlen(".")));
            }
        }   
        return $temp;
    }

    public function index_of_occurance($val,$array) {
        $occ_idx = [];
        foreach($array as $key => $value){
            if($value == $val){
                array_push($occ_idx, $key);
            }
        }   
        return $occ_idx;
    }

    public function defaultGetter(Request $request){

        //////////make table/////////////
        if(is_array($request->table_name)){
            $table =  DB::table($request->table_name[0]);
        }else{
            $table =  DB::table($request->table_name);
        }

        ///////////select field////////
        if(!empty($request->field)){
            if(!empty($request->fixPost)){
                $table = $table->select($this->fixPost($request->field,"_"));
            }else{
                $table = $table->select($request->field);
            }
        }

        //////////join//////////
        // join_onCol : ['a.resourcecode'],
        // join_onVal : ['d.doctorcode'],
        // join_filterCol : [['a.compcode on =',...]],
        // join_filterVal : [['d.compcode',...]]
        if(!empty($request->join_onCol)){
            foreach ($request->join_onCol as $key => $value) {
                if(empty($request->join_filterCol)){ //ni nak check kalu ada AND lepas JOIN ON

                    if($request->join_type[$key] == 'LEFT JOIN'){

                        $table = $table->leftJoin($request->table_name[$key+1], $request->join_onCol[$key], '=', $request->join_onVal[$key]);

                    }else{

                        $table = $table->join($request->table_name[$key+1], $request->join_onCol[$key], '=', $request->join_onVal[$key]);
                    }

                }else{

                   if($request->join_type[$key] == 'LEFT JOIN'){

                        $table = $table->leftJoin($request->table_name[$key+1], function($join) use ($request,$key){
                            $join = $join->on($request->join_onCol[$key], '=', $request->join_onVal[$key]);
                            
                            if(isset($request->join_filterCol[$key])){
                                foreach ($request->join_filterCol[$key] as $key2 => $value2) {
                                    $pieces = explode(' ', $value2);
                                    if($pieces[1] == 'on'){
                                        $join = $join->on($pieces[0],$pieces[2],$request->join_filterVal[$key][$key2]);
                                    }else{

                                        $in_pieces = explode(".", $request->join_filterVal[$key][$key2], 2);
                                        if($in_pieces[0] == 'session'){
                                            $join = $join->where($pieces[0],'=',session($in_pieces[1]));
                                        }else{
                                            $join = $join->where($pieces[0],'=',$request->join_filterVal[$key][$key2]);
                                        }

                                        // $join = $join->where($pieces[0],'=',$request->join_filterVal[$key][$key2]);
                                    }
                                }
                            }
                            
                        });

                    }else{

                        $table = $table->join($request->table_name[$key+1], function($join) use ($request,$key){
                            $join = $join->on($request->join_onCol[$key], '=', $request->join_onVal[$key]);

                            if(isset($request->join_filterCol[$key])){
                                foreach ($request->join_filterCol[$key] as $key2 => $value2) {
                                    $pieces = explode(' ', $value2);
                                    if($pieces[1] == 'on'){
                                        $join = $join->on($pieces[0],$pieces[2],$request->join_filterVal[$key][$key2]);
                                    }else{
                                        $join = $join->where($pieces[0],$pieces[2],$request->join_filterVal[$key][$key2]);
                                    }
                                }
                            }
                            
                        });

                    }
                }
            }
        }

        //////////where////////// 

        // filterCol:['trandate','trandate']
        // filterVal:['<.10-01-2000','>.10-12-2000']
        // filterVal:['null.null']
        
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
                }else if($pieces[0] == '<>'){
                    $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                }else if($pieces[0] == '>'){
                    $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
                }else if($pieces[0] == '>='){
                    $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
                }else if($pieces[0] == '<'){
                    $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
                }else if($pieces[0] == '<='){
                    $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
                }else if($pieces[0] == 'on'){
                    $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
                }else if($pieces[0] == 'null'){
                    $table = $table->whereNull($request->filterCol[$key]);
                }else if($pieces[0] == 'notnull'){
                    $table = $table->whereNotNull($request->filterCol[$key]);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }

        //////////where in //////////
        // WhereInCol:['groupcode'],
        // WhereInVal:[['10','70','35','30']]
        if(!empty($request->WhereInCol[0])){
            foreach ($request->WhereInCol as $key => $value) {
                $table = $table->whereIn($value,$request->WhereInVal[$key]);
            }
        }

        /////////where not in///////
        // whereNotIn:['groupcode'],
        // whereNotInVal:[['10','70','35','30']]
        if(!empty($request->whereNotInCol[0])){
            foreach ($request->whereNotInCol as $key => $value) {
                $table = $table->whereNotIn($value,$request->whereNotInVal[$key]);
            }
        }  

        /////////searching/////////
        if(!empty($request->searchCol) && !empty($request->searchVal)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }

            $wholeword = false;
            if(!empty($searchCol_array[0])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[0],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[0],$request->wholeword);
                    $wholeword = true;
                }
            }

            if(!$wholeword && !empty($searchCol_array[1])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[1],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[1],$request->wholeword);
                    $wholeword = true;
                }
            }

            // $table->Where($searchCol_array[0],'like','%'.$request->wholeword.'%');

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false && trim($request->searchVal[$key]) != '%%'){//trim whitespace
                            $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            $table->Where($searchCol_array[$key],'like',$search_);
                        }
                    }
                });
            }
            
        }

        // if(!empty($request->searchCol)){
        //     if(!empty($request->fixPost)){
        //         $searchCol_array = $this->fixPost3($request->searchCol);
        //     }else{
        //         $searchCol_array = $request->searchCol;
        //     }

        //     foreach ($searchCol_array as $key => $value) {
        //         $table = $table->orWhere($searchCol_array[$key],'like',$request->searchVal[$key]);
        //     }
        // }

        /////////searching 2///////// ni search utk ordialog
        if(!empty($request->searchCol2)){

            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol2);
            }else{
                $searchCol_array = $request->searchCol2;
            }

            $wholeword = false;
            if(!empty($searchCol_array[0])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[0],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[0],$request->wholeword);
                    $wholeword = true;
                }
            }

            if(!$wholeword && !empty($searchCol_array[1])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[1],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[1],$request->wholeword);
                    $wholeword = true;
                }
            }

            // $searchCol_array_1 = $searchCol_array_2 = $searchVal_array_1 = $searchVal_array_2 = [];

            // foreach ($searchCol_array as $key => $value) {
            //     if(($key+1)%2){
            //         array_push($searchCol_array_1, $searchCol_array[$key]);
            //         array_push($searchVal_array_1, $request->searchVal2[$key]);
            //     }else{
            //         array_push($searchCol_array_2, $searchCol_array[$key]);
            //         array_push($searchVal_array_2, $request->searchVal2[$key]);
            //     }
            // }
            if(!$wholeword){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key>1) break;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });

                if(count($searchCol_array)>2){
                    $table = $table->where(function($table) use ($searchCol_array, $request){
                        foreach ($searchCol_array as $key => $value) {
                            if($key<=1) continue;
                            $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        }
                    });
                }
            }
            
        }

        // if(!empty($request->searchCol2)){

        //     $table = $table->where(function($query) use ($request){

        //         if(!empty($request->fixPost)){
        //             $searchCol_array = $this->fixPost3($request->searchCol2);
        //         }else{
        //             $searchCol_array = $request->searchCol2;
        //         }

        //         foreach ($searchCol_array as $key => $value) {
        //             $query = $query->orWhere($searchCol_array[$key],'like',$request->searchVal2[$key]);
        //         }
        //     });
        // }

        //////////ordering///////// ['expdate asc','idno desc']
        if(!empty($request->sortby)){

            if(!empty($request->fixPost)){
                $sortby_array = $this->fixPost3($request->sortby);
            }else{
                $sortby_array = $request->sortby;
            }

            foreach ($sortby_array as $key => $value) {
                $pieces = explode(" ", $sortby_array[$key]);
                $table = $table->orderBy($pieces[0], $pieces[1]);
            }
        }else if(!empty($request->sidx)){

            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        return $table;

    }

    public function get_table_default(Request $request){
        $table = $this->defaultGetter($request);

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        // dd(json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE));

        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function get_value_default(Request $request){
        $table = $this->defaultGetter($request);

        //limit and offset
        if(!empty($request->offset))$table = $table->offset($request->offset);
        if(!empty($request->limit))$table = $table->limit($request->limit);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function defaultAdd(Request $request){

        DB::enableQueryLog();
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = $request[$request->idnoUse];
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        try {

            if(!empty($request->checkduplicate) && $this->default_duplicate( ///check duplicate
                $request->table_name,
                $request->table_id,
                $request[$request->table_id]
            )){
                throw new \Exception($request->table_id.' '.$request[$request->table_id].' already exist', 500);
            };

            DB::beginTransaction();

            $table = DB::table($request->table_name);

            $array_insert = [
            	'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
            ];

            foreach ($field as $key => $value) {
                $field_value = strtoupper($request[$request->field[$key]]);

                if($field_value == null){
                    continue;
                }
                $array_insert[$value] = strtoupper($request[$request->field[$key]]);
            }

            if(session()->has('computerid')){
                $array_insert['computerid'] = session('computerid');
            }

            $table->insert($array_insert);
            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function defaultEdit(Request $request){

        DB::enableQueryLog();
        DB::beginTransaction();

        $table = DB::table($request->table_name);

        $array_update = [
        	'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'ACTIVE'
        ];

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = $request[$request->idnoUse];
        }else{
            $field = $request->field;
            $idno = $request->idno;
        }

        foreach ($field as $key => $value) {
            $field_value = strtoupper($request[$request->field[$key]]);

            if($field_value == null ){
                $field_value = NULL;
            }else{
                $field_value = $field_value;
            }

        	$array_update[$value] = $field_value;
        }

        if(session()->has('computerid')){
            $array_update['lastcomputerid'] = session('computerid');
        }

        try {


            //////////where//////////
            $table = $table->where('idno','=',$idno);


            if(!empty($request->filterCol)){ ///this are not suppose to be used, we already have unique idno
                foreach ($request->filterCol as $key => $value) {
                    $pieces = explode(".", $request->filterVal[$key], 2);
                    if($pieces[0] == 'session'){
                        $table = $table->where($request->filterCol[$key],'=',session('compcode'));
                    }else{
                        $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                    }
                }
            }

            // dd($array_update);
            $table->update($array_update);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function defaultDel(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        try {

            $table = $table->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'DEACTIVE',
            ]);
            
            if(session()->has('computerid')){
                $array_update['lastcomputerid'] = session('computerid');
            }

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function defaultSysparam($source,$trantype){//tambah dulu baru bagi, start 0, dpt 1

        //1. get pvalue 1
        $pvalue1 = DB::table('sysdb.sysparam')->select('pvalue1')
            ->where('compcode',session('compcode'))
            ->where('source', '=', $source)
            ->where('trantype', '=', $trantype)->first();
        
        //2. add 1 into the value
        $pvalue1 = intval($pvalue1->pvalue1) + 1;

        //3. update the value
        DB::table('sysdb.sysparam')
            ->where('compcode',session('compcode'))
            ->where('source', '=', $source)
            ->where('trantype', '=', $trantype)
            ->update(array('pvalue1' => $pvalue1));

        //4. return pvalue1
        return $pvalue1;
    }

    public function defaultTill($tillcode,$field){

        //1. get pvalue 1
        $till = DB::table('debtor.till')
            ->select($field)
            ->where('compcode', '=', session('compcode'))
            ->where('tillcode', '=', $tillcode)->first();

        $till_ = (array)$till;
        
        //2. add 1 into the value
        $lastvalue = intval($till_[$field]) + 1;

        //3. update the value
        DB::table('debtor.till')->where('compcode', '=', session('compcode'))->where('tillcode', '=', $tillcode)
        ->update(array($field => $lastvalue));

        //4. return pvalue1
        return $lastvalue;
    }

    public function null_date($date){
        if($date == '0000-00-00'){
            return null;
        }else{
            return $date;
        }
    }

    // public function defaultlineno($table,$lineno){
    //     $lineno=$this->lineno;
    //     $first=false;
    //     $select = DB::table()

    //     $prepare="SELECT COUNT({$lineno['useOn']}) AS count FROM {$this->table} WHERE compcode=? 
    //         AND {$lineno['useOn']} = '{$lineno['useVal']}' ";

    //     if(!empty($this->filterCol)){
    //         $prepare.=$this->filter($first);
    //     }
    //     $arrayValue = [$this->compcode];
    //     $arrayValue = (!empty($this->filterCol)) ? $this->arrayValueFilter($arrayValue) : $arrayValue;
    //     /////////////////check sytax//////////////////////////////
    //     //echo $prepare;print_r($arrayValue);
    //     //echo $this->readableSyntax($prepare,$arrayValue);
    //     //////////////////////////////////////////////////////////
    //     $sth=$this->db->prepare($prepare);
    //     if(!$sth->execute($arrayValue)){throw new Exception('error');}
    //     $row = $sth->fetch(PDO::FETCH_ASSOC);

    //     return intval($row['count']) + 1;
    // }

    public function taxrate($taxcode){
        if(empty($taxcode)){
            return 0;
        }
        $tax = DB::table('hisdb.taxmast')
                    ->where('compcode',session('compcode'))
                    ->where('taxcode',$taxcode);

        if(!$tax->exists()){
            return 0;
        }else{
            return $tax->first()->rate;
        }
    }

    public function request_no($trantype,$dept){
        $seqno = DB::table('material.sequence')
                ->select('seqno')
                ->where('compcode',session('compcode'))
                ->where('trantype','=',$trantype)
                ->where('dept','=',$dept)
                ->where('recstatus','=', 'ACTIVE');
                
        if(!$seqno->exists()){
            throw new \Exception("Sequence Number for dept $dept is not available (trantype: <b>$trantype</b> , dept: <b>$dept</b>)", 500);
        }

        $seqno = $seqno->first();

        DB::table('material.sequence')
            ->where('trantype','=',$trantype)
            ->where('compcode',session('compcode'))
            ->where('dept','=',$dept)
            ->update(['seqno' => intval($seqno->seqno) + 1]);
        
        return $seqno->seqno;
    }

    public function recno($source,$trantype){//sysparam pvalue 1 start dgn 1, bagi dulu baru tambah 1
        $pvalue1 = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->select('pvalue1')
                    ->where('source','=',$source)->where('trantype','=',$trantype);

        if(!$pvalue1->exists()){
            throw new \Exception("Sysparam for source $source and trantype $trantype is not available");
        }

        $pvalue1 = $pvalue1->first();

        DB::table('sysdb.sysparam')
            ->where('compcode',session('compcode'))
            ->where('source','=',$source)->where('trantype','=',$trantype)
            ->update(['pvalue1' => intval($pvalue1->pvalue1) + 1]);
            
        return $pvalue1->pvalue1;
    }

    //nak check glmasdtl exist ke tak utk sekian costcode, glaccount, year, period
    //kalu jumpa dia return true, pastu simpan actamount{month} dkt global variable gltranAmount
    public function isGltranExist($ccode,$glcode,$year,$period){
        $pvalue1 = DB::table('finance.glmasdtl')
                ->select("glaccount","actamount".$period)
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('costcode','=',$ccode)
                ->where('glaccount','=',$glcode);

        if($pvalue1->exists()){
            $pvalue1 = $pvalue1->first();
            $pvalue1 = (array)$pvalue1;

            if(is_null($pvalue1["actamount".$period])){
                $this->gltranAmount = 0.00;
                return 0.00;
            }else{
                $this->gltranAmount = $pvalue1["actamount".$period];
                return $pvalue1["actamount".$period];
            }

            return true;
        }else{

            return false;
        }
    }

    //nak check glmasdtl exist ke tak utk sekian costcode, glaccount, year, period
    //kalu jumpa dia return true, pastu simpan actamount{month} dkt global variable gltranAmount
    public static function isGltranExist_($ccode,$glcode,$year,$period){

        $pvalue1 = DB::table('finance.glmasdtl')
                ->select("glaccount","actamount".$period)
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('costcode','=',$ccode)
                ->where('glaccount','=',$glcode);


        if($pvalue1->exists()){
            $pvalue1 = $pvalue1->first();
            $pvalue1 = (array)$pvalue1;

            if(is_null($pvalue1["actamount".$period])){
                return 0.00;
            }else{
                return $pvalue1["actamount".$period];
            }


        }else{
            return false;
        }

    }
    
    public function isCBtranExist($bankcode,$year,$period){

        $cbtran = DB::table('finance.bankdtl')
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('bankcode','=',$bankcode);

        if($cbtran->exists()){
            $cbtran_get = (array)$cbtran->first();
            $this->cbtranAmount = $cbtran_get["actamount".$period];
        }

        return $cbtran->exists();
    }

    public function getCbtranTotamt($bankcode, $year, $period){
        $cbtranamt = DB::table('finance.cbtran')
                    ->where('compcode', '=', session('compcode'))
                    ->where('bankcode', '=', $bankcode)
                    ->where('year', '=', $year)
                    ->where('period', '=', $period)
                  /*  ->first();*/
                    ->sum('amount');
                
        $responce = new stdClass();
        $responce->amount = $cbtranamt;
        
        return $responce;
    }

    public static function toYear($date){
        $carbon = new Carbon($date);
        return $carbon->year;
    }

    public static function toMonth($date){
        $carbon = new Carbon($date);
        return $carbon->month;
    }

    public static function getyearperiod_($date){
        $period = DB::table('sysdb.period')
            ->where('compcode','=',session('compcode'))
            ->get();

        $seldate = new DateTime($date);

        foreach ($period as $value) {
            $arrvalue = (array)$value;

            $year= $value->year;
            $period=0;

            for($x=1;$x<=12;$x++){
                $period = $x;

                $datefr = new DateTime($arrvalue['datefr'.$x]);
                $dateto = new DateTime($arrvalue['dateto'.$x]);
                $status = $arrvalue['periodstatus'.$x];
                if (($datefr <= $seldate) &&  ($dateto >= $seldate)){
                    $responce = new stdClass();
                    $responce->year = $year;
                    $responce->period = $period;
                    $responce->status = $status;
                    $responce->datefr = $arrvalue['datefr'.$x];
                    $responce->dateto = $arrvalue['dateto'.$x];
                    return $responce;
                }
            }
        }
    }

    public function getyearperiod($date){
        $period = DB::table('sysdb.period')
            ->where('compcode','=',session('compcode'))
            ->get();

        $seldate = new DateTime($date);

        foreach ($period as $value) {
            $arrvalue = (array)$value;

            $year= $value->year;
            $period=0;

            for($x=1;$x<=12;$x++){
                $period = $x;

                $datefr = new DateTime($arrvalue['datefr'.$x]);
                $dateto = new DateTime($arrvalue['dateto'.$x]);
                $status = $arrvalue['periodstatus'.$x];
                if (($datefr <= $seldate) &&  ($dateto >= $seldate)){
                    $responce = new stdClass();
                    $responce->year = $year;
                    $responce->period = $period;
                    $responce->status = $status;
                    $responce->datefr = $arrvalue['datefr'.$x];
                    $responce->dateto = $arrvalue['dateto'.$x];
                    return $responce;
                }
            }
        }
    }

    public static function mydump($obj,$line='null'){
        dump([
            $line,
            $obj->toSql(),
            $obj->getBindings()
        ]);

    }

    public static function turn_date($from_date,$from_format='d/m/Y'){

        if(!str_contains($from_date, '/')){
            return $from_date;
        }

        if(empty($from_date)){
            return null;
        }
        $carbon = Carbon::createFromFormat($from_format,$from_date);
        return $carbon;
    }

    public static function begins_search_if($col_arr,$search_col,$search_val){
        $found=false;
        foreach($col_arr as $col_str){
            if(str_contains($search_col, $col_str)) {
                $found=true;break;
            }
        }  

        if($found){
            if ($search_val[0] === '%')
                $search_val = substr($search_val, 1);
        }

        return $search_val;
    }

    public function clean($string) {
        if(empty($string)){
            return null;
        }

        return preg_replace('/[^A-Za-z0-9\s]/', '', $string); // Removes special chars.
    }

    public function convertNumberToWordBM($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $neg_val = false;
        if($num < 0){
            $num = $num * -1;
            $neg_val = true;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'LAPAN', 'SEMBILAN', 'SEPULUH', 'SEBELAS',
            'DUA BELAS', 'TIGA BELAS', 'EMPAT BELAS', 'LIMA BELAS', 'ENAM BELAS', 'TUJUH BELAS', 'LAPAN BELAS', 'SEMBILAN BELAS'
        );
        $list2 = array('', 'SEPULUH', 'DUA PULUH', 'TIGA PULUH', 'EMPAT PULUH', 'LIMA PULUH', 'ENAM PULUH', 'TUJUH PULUH', 'LAPAN PULUH', 'SEMBILAN PULUH', 'SERATUS');
        $list3 = array('', 'RIBU', 'JUTA', 'BILLION', 'TRILLION', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' RATUS' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        if($neg_val){
            return 'NEGATIVE '.implode(' ', $words);
        }else{
            return implode(' ', $words);
        }
        // return implode(' ', $words);
    }

    public function convertNumberToWordENG($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $neg_val = false;
        if($num < 0){
            $num = $num * -1;
            $neg_val = true;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN', 'ELEVEN',
            'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'
        );
        $list2 = array('', 'TENTH', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY', 'HUNDRED');
        $list3 = array('', 'THOUSAND', 'MILLION', 'BILLION', 'TRILLION', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? '' .$list1[$hundreds].' HUNDRED' .' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? '' . $list1[$tens] .' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = '' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = '' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? '' . $list3[$levels] .' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        if($neg_val){
            return 'NEGATIVE '.implode(' ', $words);
        }else{
            return implode(' ', $words);
        }
    }

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }

    public static function givenullifempty($obj){
        if(empty($obj)){
            return null;
        }else{
            return $obj;
        }
    }

    public function rmv_nonascii_char($string){
        return preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $string);
    }

    public function mypaginate($table,$rows){
        dd($table);
    }

    public function getAmount($money){
        // $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        // $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        // $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        // $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        // $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '', $money);
    }


}
