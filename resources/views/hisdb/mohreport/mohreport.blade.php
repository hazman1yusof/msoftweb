@extends('layouts.main')

@section('title', 'MOH Reporting')

@section('css')
    <style>
        table.reporttable th{
            border:none;
            text-align: right;
            padding-right: 20px;
        }
        table.reporttable td{
            padding:5px;
        }
        
        /* body{
            background: #00808024 !important;
        }
        .container.mycontainer{
            padding-top: 5%;
        }
        .mycontainer .panel-default {
            border-color: #9bb7b7 !important;
        }
        .mycontainer .panel-default > .panel-heading {
            background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
            font-weight: bold;
        } */
        .mybtnpdf{
            background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
            color: #af2525;
            border-color: #af2525;
            margin-bottom: 5px;
        }
        .mybtnxls{
            background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
            color: darkgreen;
            border-color: darkgreen;
            margin-bottom: 20px;
        }
        .btnvl {
            border-left: 1px solid #386e6e;
            width: 0px;
            padding: 0px;
            height: 32px;
            cursor: unset;
            margin: 0px 7px;
        }
        legend{
            margin-bottom: 5px !important;
            font-size: 12px !important;
            font-weight:bold;
        }
        .btnform .btn{
            width: -webkit-fill-available !important;
        }
        .mylabel{
            width: 400px;
            display: block;
            padding: 10px;
        }
        .mylabel input[type=radio]{
            float: right;
        }
    </style>
@endsection

@section('body')
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

    <div class='' style="margin: auto; width: 70%; padding-top: 20px;">
        <div class="panel panel-info">
            <div class="panel-heading text-center"><b>MOH Reporting</b></div>
            <div class="panel-body" style="padding: 15px 0;">

                <div class="col-md-8" style="padding-left: 10px;padding-right: 10px;">
                    <div class="panel panel-default" style="">
                        <div class="panel-body">
                            <label for="option1" class="mylabel">1. In Patient Returns (PS 101)    
                                <input type="radio" id="option1" name="choice" value="1" checked>
                            </label>
                            <label for="option2" class="mylabel">2. DayCare/Health Screening of Foreign Patient (PS 102)
                                <input type="radio" id="option2" name="choice" value="2">
                            </label>
                            <label for="option3" class="mylabel">3. Monthly Report on Foreign Patient (PS 202)
                                <input type="radio" id="option3" name="choice" value="3">
                            </label>
                            <label for="option4" class="mylabel">4. Monthly Report on Foreign Patient, Health Tourist (PS 203)
                                <input type="radio" id="option4" name="choice" value="4">
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" style="padding-left: 0px;padding-right: 10px;">
                    <div class="panel panel-default" style="">
                        <div class="panel-body">
                            <div class='col-md-12 btnform' >
                                <fieldset>
                                    <button type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1" style="margin-bottom: 0;">
                                        <span class="fa fa-file-excel-o fa-lg"></span> Download Report
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default" style="">
                        <div class="panel-body">
                            <div class='col-md-6' >
                                <label>Date From</label>
                                <input type="date" name="datefrom" id="datefrom" class="form-control input-sm text-uppercase">
                            </div>
                            <div class='col-md-6' >
                                <label>Date To</label>
                                <input type="date" name="dateto" id="dateto" class="form-control input-sm text-uppercase">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function (){
            $('#excelgen1').click(function(){

                var report = $('input:radio[name=choice]:checked').val()

                if(report != undefined){
                    window.open('./mohreport/table?action=download_report&reportno='+report+'&datefrom='+$('#datefrom').val()+'&dateto='+$('#dateto').val(), '_blank');
                }
            });
        });
    </script>
@endsection