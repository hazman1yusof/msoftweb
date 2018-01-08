
    <?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='debtor.debtortype';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	$responce = new stdClass();
	
	if(isset($_GET['Scol']) && $_GET['Stext'] == 'debtortycode'){
		$SQL = "SELECT * FROM $table 
				WHERE {$_GET['Scol']}    
			LIKE '{$_GET['Stext']}%' 
			ORDER BY debtortycode ASC";
			//WHERE recstatus = 'A' AND {$_GET['Scol']}
			
		}
			
	else if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
/*		$SQL = "SELECT * FROM $table 
				WHERE {$_GET['Scol']}    
			LIKE '{$_GET['Stext']}%' 
			ORDER BY debtortycode ASC";*/
			
		$addSql='';
		$searchcol=$_GET['Scol'];
		$searchStext=$_GET['Stext'];
		
		$parts = explode(' ', $searchStext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.=" AND {$_GET['Scol']} like '%{$parts[$partsLength]}%' ";
		}
		$SQL = "SELECT * FROM $table 
			WHERE compcode='$compcode'".$addSql.
			"ORDER BY debtortycode ASC";
	
			//WHERE recstatus = 'A' AND {$_GET['Scol']}
	}else{
		$SQL = "SELECT * FROM $table ORDER BY debtortycode ASC";
		
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
    
    
    
    
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['debtortycode'];
		$responce->rows[$i]['cell']=array($row['compcode'],
		$row['debtortycode'],
		$row['description'],
		$row['effectdate'],
		$row['depccode'], 
		$row['depglacc'],
		$row['actdebccode'],
		$row['actdebglacc'],
		$row['lastuser'],
		$row['lastupdate'],  
	    $row['regfees'], 
		$row['typegrp'],
		$row['updpayername'], 
		$row['updepisode'],
		$row['recstatus']
		);
			  
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>