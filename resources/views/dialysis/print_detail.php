<?php
include('config.php');
session_start();
require('fpdf/fpdf.php');



//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$pdf = new FPDF('P','mm','A4');

$pdf->AddPage();

//Image( file name , x position , y position , width [optional] , height [optional] )



//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);



//Cell(width , height , text , border , end line , [align] )

//$pdf->Cell(130 ,5,'MEDICSOFT',0,0);
//$pdf->Cell(59 ,5,'MEDICSOFT',0,1);//end of line



$query=mysqli_query($con,"select * from prescription where id = '".$_GET['id']."'");
        while($row=mysqli_fetch_array($query)){
//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(35 ,5,'MRN:',0,0);
$pdf->Cell(100 ,5,$row['mrn'],0,1);//end of line


$pdf->Cell(35 ,5,'Name:',0,0);
$pdf->Cell(100 ,5,$row['name'],0,1);//end of line

$pdf->Cell(35 ,5,'Epis Type:  ',0,0);
$pdf->Cell(100 ,5,$row['freqcode'],0,1);//end of line

$pdf->Cell(35 ,5,'Adm Date/Time:',0,0);
$pdf->Cell(100 ,5,$row['date'],0,1);//end of line

$pdf->Cell(35 ,5,'Sex/Race/DOB:',0,0);
$pdf->Cell(100 ,5,$row[''],0,1);//end of line

$pdf->Cell(35 ,5,'PS. No.:',0,0);
$pdf->Cell(100 ,5,$row[''],0,1);//end of line

$pdf->Cell(35 ,5,'War/Bed:',0,0);
$pdf->Cell(100 ,5,$row[''],0,1);//end of line


//make a dummy empty cell as a vertical spacer
$pdf->Cell(45 ,5,'',0,1);//end of line

//billing
$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'=========================================================================================',0,1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'TRX Date           :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5, $row['date'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Charge Code     :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5, $row['chgcode'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Description         :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['description'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Qty                     :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['qty'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Dosage              :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['dosecode'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Frequency          :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['freqdescription'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Duration             :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['duration'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Instruction          :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(90 ,5,$row['inst_description'],0,1);
$pdf->Cell(100 ,2,'',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(45 ,5,'Doctor                :',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(120 ,5,$row['doctor'],0,1);


//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line
$pdf->SetFont('Arial','B',10);
//$pdf->Cell(182,4,"Print Date : ".date("D-d/m/Y"),0,0,'R');
//set font to arial, bold, 14pt

$pdf->Cell(189 ,10,'',0,1);//end of line
$pdf->SetFont('Arial','B',10);

$pdf->Cell(150 ,5,'***** END OF REPORTS *****',0,1);
//$pdf->Cell(59 ,5,'APARAT KEAMANAN',0,1);//end of line


}

$pdf->Output("Report prescription.pdf","I");
?>