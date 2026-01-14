@extends('layouts.main')

@section('title', 'DO Posted report')

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
        .mycontainer .mybtnpdf{
            background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
            color: #af2525;
            border-color: #af2525;
            margin-bottom: 5px;
        }
        .mycontainer .mybtnxls{
            background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
            color: darkgreen;
            border-color: darkgreen;
            margin-bottom: 20px;
        }
        .mycontainer .btnvl {
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
    </style>
@endsection

@section('body')
    <input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
    <div class="container mycontainer">
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 450px;">
            <form method="get" id="genreport">
                @if(Request::get('scope') == 'noinvoice')
                <h4>DO Posted Report - No Invoice</h4>
                @else
                <h4>DO Posted Report</h4>
                @endif
                <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7>
                
                <div style="width: 900px;margin: 0 auto;">
                     <div class="col-md-7">
                        <div class="col-md-12" style="padding-top: 30px;padding-bottom: 30px;">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Date From</label>
                                    <input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Date To</label>
                                    <input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-md-6" style="margin-top: 10px;">
                                    <label class="control-label" for="Scol">Delivery Dept From</label> 
                                    <div class='input-group'> 
                                        <input id="dept_from" name="dept_from" type="text" class="form-control input-sm" autocomplete="off" value="ZZZ">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px;">
                                    <label class="control-label" for="Scol">Delivery Dept To</label>  
                                    <div class='input-group'>
                                        <input id="dept_to" name="dept_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-md-6" style="margin-top: 10px;">
                                    <label class="control-label" for="Scol">Status</label> 
                                    <select name="recstatus" id="recstatus" class="form-control input-sm" >
                                      <option value="ALL">ALL</option>
                                      <option value="POSTED" selected>POSTED</option>
                                      <option value="OPEN">OPEN</option>
                                      <option value="CANCELLED">CANCELLED</option>
                                    </select>
                                </div>
                            </div>


                        </div> 
                    </div>
                    
                    <div class="col-md-4" style="margin-left: 50px;">
                        <div class="panel panel-default" style="height: 110px;">
                            <div class="panel-body">
                                <div class='col-md-12 btnform' style="padding: 20px 0px">
                                    <fieldset>
                                        <!-- <legend>Stock Sheet :</legend> --><!-- 
                                        <button name="ARAgeing_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen1">
                                            <span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
                                        </button> -->
                                        <button name="ARAgeing_xls" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
                                            <span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        var errorField = [];
        $(document).ready(function () {
            var dept_from = new ordialog(
                'dept_from','sysdb.department','#dept_from','errorField',
                {   
                    colModel:[
                        {label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
                        {label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
                        {label:'Unit',name:'sector', hidden:true},
                    ],
                    urlParam: {
                        filterCol:['storedept', 'recstatus','compcode'],//,'sector'
                        filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
                    },
                    sortname:'deptcode',
                    sortorder:'asc',
                    ondblClickRow: function () {
                        let data = selrowData('#' + dept_from.gridname);

                        $('#dept_to').val(data.deptcode);
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
                    title:"Select Department",
                    open: function(){
                        dept_from.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
                        dept_from.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
                    },
                    close: function(obj_){
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
            dept_from.makedialog(true);

            var dept_to = new ordialog(
                'dept_to','sysdb.department','#dept_to',errorField,
                {   
                    colModel:[
                        {label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
                        {label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
                        {label:'Unit',name:'sector', hidden:true},
                    ],
                    urlParam: {
                        filterCol:['storedept', 'recstatus','compcode'],//,'sector'
                        filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
                    },
                    sortname:'deptcode',
                    sortorder:'asc',
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
                    title:"Select Department",
                    open: function(){
                        dept_to.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
                        dept_to.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
                    },
                    close: function(obj_){
                    },
                    after_check: function(data,self,id,fail,errorField){
                        let value = $(id).val();
                        if(value.toUpperCase() == 'ZZZ'){
                            ordialog_buang_error_shj(id,errorField);
                            if($.inArray('dept_to',errorField)!==-1){
                                errorField.splice($.inArray('dept_to',errorField), 1);
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
            dept_to.makedialog(true);
        });
        $("#excelgen1").click(function() {
            let action = 'do_posted_report';

            if($('#scope').val() == 'noinvoice'){
                action = 'do_posted_report_no_invoice';
            }

            window.open('./deliveryOrder/table?action='+action+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&dept_from='+$("#dept_from").val()+'&dept_to='+$("#dept_to").val()+'&recstatus='+$("#recstatus").val(), "_blank");
        });
    </script>
@endsection