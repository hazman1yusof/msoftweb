<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction
	$scol;
	$stext='';
	if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
		$scol=$_GET['Scol'];
		$stext=$_GET['Stext'];
	}
	
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	$total_pages;
	$count;
	$start;
	
	$responce = new stdClass();
	$table='material.suppgroup';
	$column=getAllColumnFromTable(['']);
	$columnid='suppgroup';
	
	function getPagerInfo(){
		global $mysqli,$table,$page,$limit,$sidx,$sord,$total_pages,$count,$start,$scol,$stext,$columnid,$compcode,$user;
		
		if(!$sidx) $sidx = $columnid;
		
		if(isset($scol) && $stext != ''){
			$sql = "SELECT COUNT(*) AS count FROM $table 
				WHERE compcode='$compcode' AND {$_GET['Scol']} 
				LIKE '%{$_GET['Stext']}%'";
		}else{
			$sql = "SELECT COUNT(*) AS count FROM $table WHERE compcode='$compcode'";
		}
		
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$count = $row['count'];
		
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages && $count>0 ) {$page=$total_pages;}
		$start = $limit*$page - $limit;
	}
	
	function getAllColumnFromTable(array $except){//get all column field
		global $mysqli,$table;
		$SQL = "SHOW COLUMNS FROM $table";
		$temp=array();
		
		$result = $mysqli->query($SQL);if (!$result) { echo $mysqli->error; }
		
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$key=array_search($row['Field'], $except);
			if($key>-1){
			}else{
				array_push($temp,$row['Field']);
			}
		}
		return $temp;
	}
	
	function cellArray($row){
		global $column;
		$temp=array();
		for($x=0;$x<count($column);$x++){
			array_push($temp,$row[$column[$x]]);
		}
		return $temp;
	}
	
	getPagerInfo();
	
	if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
		$addSql='';
		
		$parts = explode(' ', $stext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.=" AND {$scol} like '%{$parts[$partsLength]}%' ";
		}
		
		$SQL = "SELECT * FROM $table 
			WHERE compcode='$compcode'".$addSql." 
			ORDER BY $sidx $sord LIMIT $start , $limit";
	}else{
		$SQL = "SELECT * FROM $table WHERE compcode='$compcode' ORDER BY $sidx $sord LIMIT $start , $limit";
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row[$columnid];
		$responce->rows[$i]['cell']=cellArray($row);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>