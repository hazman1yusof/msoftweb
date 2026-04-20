@extends('layouts.main')

@section('title', 'Dr Fees Voucher Reporting')

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
            <div class="panel-heading text-center"><b>Doctor Fees Voucher</b></div>
            <div class="panel-body" style="padding: 15px 0;">

                <div class="col-md-8" style="padding-left: 10px;padding-right: 10px;">
                    <div class="panel panel-default" style="">
                        <div class="panel-body">

                            <div class='col-md-6 col-md-offset-1' style="padding-bottom: 10px;">
                                <label>Date</label>
                                <input type="date" name="dateto" id="dateto" class="form-control input-sm text-uppercase">
                            </div>

                            <div class="col-md-6 col-md-offset-1" style="padding-bottom: 10px;">
                                <label class="control-label" for="Scol">Doctor From</label>
                                <div class='input-group'>
                                    <input id="doctor_from" name="doctor_from" type="text" class="form-control input-sm" autocomplete="off" value="">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                                
                            <div class="col-md-6 col-md-offset-1">
                                <label class="control-label" for="Scol">Doctor To</label>
                                <div class='input-group'>
                                    <input id="doctor_to" name="doctor_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';

        $(document).ready(function (){
    
            var dialog_doctorFrom = new ordialog(
                'doctor_from','hisdb.doctor','#doctor_from','errorField',
                {
                    colModel: [
                        { label: 'Debtor Code', name: 'doctorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                        { label: 'Debtor Name', name: 'doctorname', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
                    ],
                    urlParam: {
                        filterCol: ['compcode','recstatus'],
                        filterVal: ['session.compcode','ACTIVE']
                    },
                    ondblClickRow: function () {
                    },
                    gridComplete: function(obj){
                        var gridname = '#'+obj.gridname;
                        if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                            $(gridname+' tr#1').click();
                            $(gridname+' tr#1').dblclick();
                        }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                            $('#'+obj.dialogname).dialog('close');
                        }
                    }
                },{
                    title:"Select Debtor Code",
                    open: function(){
                        dialog_doctorFrom.urlParam.filterCol= ['recstatus', 'compcode'],
                        dialog_doctorFrom.urlParam.filterVal= ['ACTIVE', 'session.compcode']
                    },
                    close: function(obj_){
                    },
                    after_check: function(data,self,id,fail,errorField){
                        let value = $(id).val();
                        if(value.toUpperCase() == 'ZZZ'){
                            ordialog_buang_error_shj(id,errorField);
                            if($.inArray('doctor_from',errorField)!==-1){
                                errorField.splice($.inArray('doctor_from',errorField), 1);
                            }
                        }
                    },
                    justb4refresh: function(obj_){
                        obj_.urlParam.searchCol2=[];
                        obj_.urlParam.searchVal2=[];
                    },
                    justaftrefresh: function(obj_){
                        $("#Dtext_"+obj_.unique).val('');
                    }
                },'urlParam','radio','tab'
            );
            dialog_doctorFrom.makedialog(true);
            
            var dialog_doctorTo = new ordialog(
                'doctor_to','hisdb.doctor','#doctor_to','errorField',
                {
                    colModel: [
                        { label: 'Debtor Code', name: 'doctorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                        { label: 'Debtor Name', name: 'doctorname', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
                    ],
                    urlParam: {
                        filterCol: ['compcode','recstatus'],
                        filterVal: ['session.compcode','ACTIVE']
                    },
                    ondblClickRow: function () {
                    },
                    gridComplete: function(obj){
                        var gridname = '#'+obj.gridname;
                        if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                            $(gridname+' tr#1').click();
                            $(gridname+' tr#1').dblclick();
                        }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                            $('#'+obj.dialogname).dialog('close');
                        }
                    }
                },{
                    title:"Select Debtor Code",
                    open: function(){
                        dialog_doctorTo.urlParam.filterCol= ['recstatus', 'compcode'],
                        dialog_doctorTo.urlParam.filterVal= ['ACTIVE', 'session.compcode']
                    },
                    close: function(obj_){
                    },
                    after_check: function(data,self,id,fail,errorField){
                        let value = $(id).val();
                        if(value.toUpperCase() == 'ZZZ'){
                            ordialog_buang_error_shj(id,errorField);
                            if($.inArray('doctor_to',errorField)!==-1){
                                errorField.splice($.inArray('doctor_to',errorField), 1);
                            }
                        }
                    },
                    justb4refresh: function(obj_){
                        obj_.urlParam.searchCol2=[];
                        obj_.urlParam.searchVal2=[];
                    },
                    justaftrefresh: function(obj_){
                        $("#Dtext_"+obj_.unique).val('');
                    }
                },'urlParam','radio','tab'
            );
            dialog_doctorTo.makedialog(true);

            $('#excelgen1').click(function(){
                var report = 'drfeesvoucher';
                window.open('./drfeesvoucher/table?action=download_report&reportno='+report+'&datefrom='+$('#datefrom').val()+'&dateto='+$('#dateto').val(), '_blank');
            });
        });
    </script>
@endsection