<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.ivtxntype';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	$responce = new stdClass();
	
	if(isset($_GET['Scol']) && $_GET['Stext'] == 'sysno'){
		$SQL = "SELECT sysno,compcode,trantype,description,isstype,
				CASE WHEN trbyiv = 1 THEN 'Yes' ELSE 'No' END AS trbyiv,
				CASE WHEN updqty = 1 THEN 'Yes' ELSE 'No' END AS updqty,
				crdbfl,
				CASE WHEN updamt = 1 THEN 'Yes' ELSE 'No' END AS updamt, 
				accttype, recstatus,  adduser,adddate, upduser, upddate
				FROM $table 
				WHERE recstatus = 'A' AND {$_GET['Scol']}    
				LIKE '{$_GET['Stext']}%' 
				ORDER BY sysno ASC";
		}
			
	else if(isset($_GET['Scol']) && $_GET['Stext'] != ''){			
		$addSql='';
		$searchcol=$_GET['Scol'];
		$searchStext=$_GET['Stext'];
		
		$parts = explode(' ', $searchStext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.=" AND {$_GET['Scol']} like '%{$parts[$partsLength]}%' ";
		}
		$SQL = "SELECT sysno,compcode,trantype,description,isstype,
				CASE WHEN trbyiv = 1 THEN 'Yes' ELSE 'No' END AS trbyiv,
				CASE WHEN updqty = 1 THEN 'Yes' ELSE 'No' END AS updqty,
				crdbfl,
				CASE WHEN updamt = 1 THEN 'Yes' ELSE 'No' END AS updamt, 
				accttype, recstatus,  adduser,adddate, upduser, upddate 
				FROM $table 
				WHERE  recstatus = 'A' AND compcode='$compcode'".$addSql.
				"ORDER BY sysno ASC";
	}else{
		$SQL = "SELECT sysno,compcode,trantype,description,isstype,
				CASE WHEN trbyiv = 1 THEN 'Yes' ELSE 'No' END AS trbyiv,
				CASE WHEN updqty = 1 THEN 'Yes' ELSE 'No' END AS updqty,
				crdbfl,
				CASE WHEN updamt = 1 THEN 'Yes' ELSE 'No' END AS updamt, 
				accttype, recstatus,  adduser,adddate, upduser, upddate     
				FROM $table WHERE recstatus ='A' ORDER BY sysno ASC";
	}
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['sysno'];
		$responce->rows[$i]['cell']=array($row['sysno'], 
		$row['compcode'],
		$row['trantype'], 
		$row['description'],
		$row['isstype'],
		$row['trbyiv'],
		$row['updqty'], 
		$row['crdbfl'],
		$row['updamt'],
		$row['accttype'],
		$row['recstatus'],
		$row['adduser'],
	    $row['adddate'],
		$row['upduser'],
		$row['upddate']
	     );
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>