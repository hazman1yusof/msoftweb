<?php
	class EditTable{
		
		protected $db;
		
		var $oper;
		var $table;
		var $column;
		var $columnid;
		var $user;
		var $compcode;
		var $post;
		var $filterCol;
		var $filterVal;
		var $sysparam;
		var $lineno;
		var $fixPost;
		var $skipduplicate;
		
		public function __construct(PDO $db,$get,$post){
			include_once('sschecker.php');
			//set operation
			$this->oper = $get['oper'];
			//skip duplicate
			if(isset($get['skipduplicate'])){
				$this->skipduplicate = $get['skipduplicate'];	
			}
			//set connection
			$this->db = $db;
			//set table
			$this->table = $get['table_name'];
			//throw away the dot in post field, because table is in array
			if(isset($get['fixPost']) && $get['fixPost'] == 'true' ){
				$this->fixPost = true;
			}else{
				$this->fixPost = false;
			}
			$this->post=$post;
			//set tableid
			$this->columnid = $get['table_id'];
			//set column
			if(isset($get['field']) && !empty($get['field'])){
				$this->column=$this->pushSomeIntoArray($get['field']);
			}
			//set sysparam if any
			////////////////if(isset($get['sysparam']) && !empty($get['sysparam']))$this->sysparam = $get['sysparam'];
			//set lineno if any
			///////////////////if(isset($get['lineno']) && !empty($get['lineno']))$this->lineno = $get['lineno'];
			//set filter if any
			if(!empty($get['filterCol'])){
				$this->filterCol=$get['filterCol'];
				$this->filterVal=$get['filterVal'];
			}
			//set others
			$this->user = $_SESSION['username'];
			$this->compcode = $_SESSION['company'];
		}

		private function fixPost(array $column,$rep,$isPost){
			$temp=[];
			if(!$isPost){
				foreach($column as $value){
					array_push($temp, substr(strstr($value, $rep),1));
				}	
			}else{
				foreach($column as $key => $value){
					$newkey = substr(strstr($key, $rep),1);
					$temp[$newkey] = $value;
				}
			}
			return $temp;
		}

		private function pushSomeIntoArray(array $column){
			switch($this->oper){
				case 'add':
					$arr=['compcode','adduser','adddate'];
					break;
				case 'edit':
					$arr=['compcode','upduser','upddate'];
					break;
				/*case 'del':
					$arr=['compcode','recstatus','deluser','deldate'];
					break;*/
				default:
					break;
			}

			if($this->fixPost){
				$this->post = $this->fixPost($this->post,'_',true);
				$column = $this->fixPost($column,'_',false);
				$this->columnid = substr(strstr($this->columnid, '_'),1);
			}

			foreach($arr as $value){
				if(!in_array($value, $column)){
					array_push($column, $value);
				}
			}
			return $column;
		}
		
		private function getAllColumnFromTable(array $except){//get all column field
			$SQL = "SHOW COLUMNS FROM {$this->table}";
			$temp=array();
			$result = $this->db->query($SQL);if (!$result) { print_r($this->db->errorInfo()); }
			
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$key=array_search($row['Field'], $except);
				if($key>-1){
				}else{
					array_push($temp,$row['Field']);
				}
			}
			return $temp;
		}
		
		private function chgDate($date){
			$newstr=explode("-", $date);
			return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
		}
		
		private function duplicate($code,$table,$codetext){
			$sqlDuplicate="select $code from $table where $code = '$codetext'";
			$result = $this->db->query($sqlDuplicate);if (!$result) { print_r($this->db->errorInfo()); }
			return $result->rowCount();
		}
		
		private function arrayValue(array $fixColName,array $fixColValue,$del){
			$column=$this->column;$temp = array();
			
			if($del){
			
				for($x=0;$x<count($fixColName);$x++){
					if($fixColValue[$x] == 'NOW()')continue;
					array_push($temp,$fixColValue[$x]);
				}
				
			}else{
				
				for($x=0;$x<count($column);$x++){
					$key=array_search($column[$x], $fixColName);
					if($column[$x] == 'sysno')continue;
					
					if($key>-1){
						if($fixColValue[$key] == 'NOW()') continue;
						array_push($temp,$fixColValue[$key]);
					}else if(isset($this->post[$column[$x]])){
						if(isset($this->post[$column[$x]]) && $this->post[$column[$x]] == 'NOW()') continue;
						array_push($temp,$this->post[$column[$x]]);
					}else{
						array_push($temp,NULL);
					}
				}
			
			}
			return $temp;
		}

		private function arrayValueFilter(array $fixColValue){
			$filter=$this->filterVal;$temp = $fixColValue;
			
			foreach ($filter as $key => $value) {
				if (strpos($value, 'session.') !== false) {
					$pieces = explode(".", $value);
					if(isset($_SESSION[$pieces[1]]))array_push($temp,$_SESSION[$pieces[1]]);
				}else if(strpos($value, 'skip.') !== false || $value == 'IS NULL' || $value == 'NOW()'){
					continue;
				}else{
					array_push($temp,$value);
				}
			}
			return $temp;
		}

		private function filter($first){
			$addSql='';
			if(!isset($this->filterCol) || empty($this->filterCol)){
				return $addSql;
			}else{
				foreach ($this->filterCol as $key => $col) {
					$val=$this->filterVal[$key];
					if (strpos($val, 'skip.') !== false) {
						$pieces = explode(".", $val, 2);
						$addSql.="AND {$col} = {$pieces[1]} ";
					}else if($val == 'IS NULL'){
						$addSql.="AND {$col} IS NULL ";
					}else if($val == 'NOW'){
						$addSql.="AND {$col} = NOW() ";
					}else{
						$addSql.="AND {$col} = ? ";
					}
				}
				if($first){
					return strstr($addSql,' ');
				}else{
					return $addSql;
				}
			}
		}

		private function autoSyntaxAdd(array $fixColName,array $fixColValue){
			$column=$this->column;$table=$this->table;

			$string='INSERT INTO '.$table.' (';
			
			for($x=0;$x<count($column);$x++){
				if($column[$x]=='sysno')continue;

				$string.=$column[$x].',';
			}
			
			$string=rtrim($string,',');
			$string.=') VALUES (';
				
			for($x=0;$x<count($column);$x++){
			
				$key=array_search($column[$x], $fixColName);
				if($column[$x]=='sysno')continue;

				//fix cant add NOW()to prepare statement
				if($key>-1 && $fixColValue[$key] == 'NOW()'){
					$string.="NOW(),";
				}else if(isset($this->post[$column[$x]]) && $this->post[$column[$x]] == 'NOW()'){
					$string.="NOW(),";
				}else{
					$string.="?,";
				}
			}
			
			$string=rtrim($string,',');
			$string.=')';
			
			return $string;
		}
		
		private function autoSyntaxUpd(array $fixColName,array $fixColValue,$needFilter){
			$column=$this->column;$first=false;
			
			$string='UPDATE '.$this->table.' SET ';
			
			for($x=0;$x<count($column);$x++){
				$key=array_search($column[$x], $fixColName);
				if($column[$x]=='sysno')continue;
				
				//fix cant add NOW()to prepare statement
				if($key>-1 && $fixColValue[$key] == 'NOW()'){
					$string.=$fixColName[$key].' = NOW(),';
				}else if(isset($this->post[$column[$x]]) && $this->post[$column[$x]] == 'NOW()'){
					$string.=$column[$x].' = NOW(),';
				}else{
					$string.=$column[$x].' = ?,';
				}
				
			}
			$string=rtrim($string,',');
			$string.=" WHERE ".$this->columnid." = ? AND compcode = ? ";
			
			if($needFilter){
				$string.=$this->filter($first);	
			}
			return $string;
		}
		
		private function autoSyntaxDel(array $fixColName,array $fixColValue,$needFilter){
			$string='UPDATE '.$this->table.' SET ';$first=false;
			
			for($x=0;$x<count($fixColName);$x++){
				if($fixColValue[$x] == 'NOW()'){
					$string.=$fixColName[$x].' = NOW(),';
				}else{
					$string.=$fixColName[$x].' = ?,';
				}
			}
			$string=rtrim($string,',');
			$string.=" WHERE ".$this->columnid." = ? AND compcode = ? ";
			
			if($needFilter){
				$string.=$this->filter($first);	
			}
			return $string;
		}
		
		/*private function sysparam(){
			$sysparam = $this->sysparam;
			
			$sqlSysparam="SELECT pvalue1 FROM sysdb.sysparam WHERE source = '{$sysparam['source']}' AND trantype = '{$sysparam['trantype']}'";
			$result = $this->db->query($sqlSysparam);if (!$result) { print_r($this->db->errorInfo()); }
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			$pvalue1=intval($row['pvalue1'])+1;
			
			$sqlSysparam="UPDATE sysdb.sysparam SET pvalue1 = '{$pvalue1}' WHERE source = '{$sysparam['source']}' AND trantype = '{$sysparam['trantype']}'";
			
			$this->db->query($sqlSysparam);if (!$result) { print_r($this->db->errorInfo()); }
			
			return $pvalue1;
		}*/

		/*private function lineno(){
			$lineno=$this->lineno;
			$first=false;
			$prepare="SELECT COUNT({$lineno['useOn']}) AS count FROM {$this->table} WHERE compcode=? ";

			if(!empty($this->filterCol)){
				$prepare.=$this->filter($first);
			}
			$arrayValue = [$this->compcode];
			$arrayValue = (!empty($this->filterCol)) ? $this->arrayValueFilter($arrayValue) : $arrayValue;
			/////////////////check sytax//////////////////////////////
			//echo $prepare;print_r($arrayValue);
			//echo $this->readableSyntax($prepare,$arrayValue);
			//////////////////////////////////////////////////////////
			$sth=$this->db->prepare($prepare);
			if(!$sth->execute($arrayValue)){throw new Exception('error');}
			$row = $sth->fetch(PDO::FETCH_ASSOC);

			return intval($row['count']) + 1;
		}*/

		private function readableSyntax($prepare,array $arrayValue){
			foreach($arrayValue as $val){
				$prepare=preg_replace("/\?/", "'".$val."'", $prepare,1);
			}
			return $prepare."\r\n";
		}
		
		public function edit_table($commit){

			try{
				if($commit){
					$this->db->beginTransaction();
				}
				
				if($this->oper=='add'){
				
					$addarrField=['compcode','adduser','adddate'];//extra field
					$addarrValue=[$this->compcode,$this->user,'NOW()'];
					
					/*if(!empty($this->sysparam)){
						array_push($addarrField,$this->sysparam['useOn']);
						array_push($addarrValue,$this->sysparam());
					}

					if(!empty($this->lineno)){
						array_push($addarrField,$this->lineno['useOn']);
						array_push($addarrValue,$this->lineno());
					}*/
				
					$prepare=$this->autoSyntaxAdd($addarrField,$addarrValue);
					$arrayValue=$this->arrayValue($addarrField,$addarrValue,false);
					
				}else if($this->oper=='edit'){
				
					$updarrField=['compcode','upduser','upddate'];//extra field
					$updarrValue=[$this->compcode,$this->user,'NOW()'];
					
					$prepare = $this->autoSyntaxUpd($updarrField,$updarrValue,!empty($this->filterCol));

					$arrayValue = $this->arrayValue($updarrField,$updarrValue,false);
					array_push($arrayValue,$this->post[$this->columnid],$this->compcode);
					$arrayValue = (!empty($this->filterCol)) ? $this->arrayValueFilter($arrayValue) : $arrayValue;
					
				}/*else if($this->oper=='del'){
				
					$delarrField=['compcode','deluser','deldate','recstatus'];//extra field
					$delarrValue=[$this->compcode,$this->user,'NOW()','D'];
				
					$prepare = $this->autoSyntaxDel($delarrField,$delarrValue,!empty($this->filterCol));
					
					$arrayValue = $this->arrayValue($delarrField,$delarrValue,true);
					array_push($arrayValue,$this->post[$this->columnid],$this->compcode);
					$arrayValue = (!empty($this->filterCol)) ? $this->arrayValueFilter($arrayValue) : $arrayValue;
					
				}*/
				/////////////////check sytax//////////////////////////////
				//echo $prepare;print_r($arrayValue);
				//echo $this->readableSyntax($prepare,$arrayValue);
				//////////////////////////////////////////////////////////

			
				
				if($this->columnid!='sysno' && $this->oper=='add' && !$this->skipduplicate && !empty($this->post[$this->columnid]) && $this->duplicate($this->columnid,$this->table,$this->post[$this->columnid])){
					throw new Exception('Duplicate key');
				}
				
				$sth=$this->db->prepare($prepare);
				if (!$sth->execute($arrayValue)) {
					throw new Exception($this->readableSyntax($prepare,$arrayValue));
				}

				if($commit){
					$this->db->commit();
				}
				echo '{"msg":"success"}';
				
			}catch( Exception $e ){
				$this->db->rollback();
				http_response_code(400);
				echo $e->getMessage();
				
			}
		}
		
	}
	
?>