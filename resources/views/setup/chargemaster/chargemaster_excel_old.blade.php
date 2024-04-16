@extends('layouts.excellayout')

@section('title','Charge Master')

@section('style')

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

$sheet -> getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$sheet->getPageMargins()->setTop(1);

$sheet ->getPageMargins()->setRight(0.75);

$sheet ->getPageMargins()->setLeft(0.75);

$sheet ->getPageMargins()->setBottom(1);

@endsection





@section('body')

header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition: attachment;filename="Charge Master.xlsx"');

header('Cache-Control: max-age=0');

$writer->save('php://output');

@endsection