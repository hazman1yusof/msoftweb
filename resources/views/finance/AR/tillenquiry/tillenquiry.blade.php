@extends('layouts.main')

@section('title', 'Till Enquiry')

@section('style')
    .num{
        width:20px;
    }
    
    .mybtn{
        float: right;
        display: none;
    }
    
    .bg-primary .mybtn{
        display:block;
    }
    
    input.uppercase {
        text-transform: uppercase;
    }
    
    .panel-heading.collapsed .fa-angle-double-up,
    .panel-heading .fa-angle-double-down {
        display: none;
    }
    
    .panel-heading.collapsed .fa-angle-double-down,
    .panel-heading .fa-angle-double-up {
        display: inline-block;
    }
    
    i.fa {
        cursor: pointer;
        float: right;
        <!-- margin-right: 5px; -->
    }
    
    .collapsed ~ .panel-body {
        padding: 0;
    }
    
    .clearfix {
        overflow: auto;
    }
@endsection

@section('body')
    <!--***************************** Search + table *****************************-->
    <div class='row'>
        <form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
            <fieldset>
                <input id="getYear" name="getYear" type="hidden"  value="{{Carbon\Carbon::now()->year}}">
                
                <div class='col-md-12' style="padding:0 0 15px 0;">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label" for="Scol">Search By : </label>
                            <select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
                        </div>
                        
                        <div class="col-md-5" style="margin-top: 4px;">
                            <label class="control-label"></label>
                            <input style="display:none" name="Stext" type="search" placeholder="Search Here ..." class="form-control text-uppercase" tabindex="2">
                            
                            <div id="tillcode_text">
                                <div class='input-group'>
                                    <input id="tillcode_search" name="tillcode_search" type="text" maxlength="12" class="form-control input-sm">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span id="tillcode_search_hb" class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
        
        <div class="panel panel-default">
            <div class="panel-heading">Till Registration
                <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
                <a class='pull-right pointer text-primary' id='print_excel' href="" target="_blank" style="padding-right: 20px;"><span class='fa fa-file-excel-o'></span> Excel </a>
            </div>
            <div class="panel-body">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid" class="table table-striped"></table>
                    <div id="jqGridPager"></div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default" style="position: relative;" id="jqGridTillDetl_c">
            <div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTillDetl_panel">
                <b>TILL CODE: </b><span id="tillcode1_show"></span> &nbsp;
                <b>CASHIER: </b><span id="cashier1_show"></span><br>
                <b>OPEN DATE: </b><span id="opendate1_show"></span> &nbsp;
                <b>OPEN TIME: </b><span id="opentime1_show"></span><br>
                
                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 35px;">
                    <h5>Till Detail</h5>
                </div>
            </div>
            <div id="jqGridTillDetl_panel" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class='col-md-12' style="padding:0 0 15px 0">
                        <table id="jqGridTillDetl" class="table table-striped"></table>
                        <div id="jqGridPagerTillDetl"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default" style="position: relative;" id="jqGridSummary_c">
            <div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridSummary_panel">
                <b>TILL CODE: </b><span id="tillcode2_show"></span> &nbsp;
                <b>CASHIER: </b><span id="cashier2_show"></span><br>
                <b>OPEN DATE: </b><span id="opendate2_show"></span> &nbsp;
                <b>OPEN TIME: </b><span id="opentime2_show"></span><br>
                
                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 35px;">
                    <h5>Summary</h5>
                </div>
            </div>
            <div id="jqGridSummary_panel" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class='col-md-12'>
                        <div class='panel panel-info' style="margin: auto;width: 60%;padding: 10px;">
                            <div class="panel-body">
                                <div class="col-md-4  col-md-offset-1" style="text-align: center;margin-bottom: 10px;padding-left: 60px;"><h6><b>Amount Collected: </b></h6></div>
                                <div class="col-md-7" style="text-align: center;margin-bottom: 10px;"><h6><b>Amount Refund: </b></h6></div>
                                <div class=""></div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="CashCollected">Cash</label>
                                    <div class="col-md-3">
                                        <input id="CashCollected" name="CashCollected" type="text" class="form-control input-sm" readonly>
                                        <!-- value="@if(!empty($sum_cash)){{$sum_cash}}@else{{number_format(0.00,2)}}@endif"  -->
                                    </div>
                                    
                                    <label class="col-md-2 control-label" for="CashRefund">Cash</label>
                                    <div class="col-md-3">
                                        <input id="CashRefund" name="CashRefund" type="text" class="form-control input-sm" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="ChequeCollected">Cheque</label>
                                    <div class="col-md-3">
                                        <input id="ChequeCollected" name="ChequeCollected" type="text" class="form-control input-sm" readonly>
                                        <!-- value="@if(!empty($sum_chq)){{$sum_chq}}@else{{number_format(0.00,2)}}@endif"  -->
                                    </div>
                                    
                                    <label class="col-md-2 control-label" for="ChequeRefund">Cheque</label>
                                    <div class="col-md-3">
                                        <input id="ChequeRefund" name="ChequeRefund" type="text" class="form-control input-sm" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="CardCollected">Card</label>
                                    <div class="col-md-3">
                                        <input id="CardCollected" name="CardCollected" type="text" class="form-control input-sm" readonly>
                                        <!-- value="@if(!empty($sum_card)){{$sum_card}}@else{{number_format(0.00,2)}}@endif"  -->
                                    </div>
                                    
                                    <label class="col-md-2 control-label" for="CardRefund">Card</label>
                                    <div class="col-md-3">
                                        <input id="CardRefund" name="CardRefund" type="text" class="form-control input-sm" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="DebitCollected">Auto Debit</label>
                                    <div class="col-md-3">
                                        <input id="DebitCollected" name="DebitCollected" type="text" class="form-control input-sm" readonly>
                                        <!-- value="@if(!empty($sum_bank)){{$sum_bank}}@else{{number_format(0.00,2)}}@endif"  -->
                                    </div>
                                    
                                    <label class="col-md-2 control-label" for="DebitRefund">Auto Debit</label>
                                    <div class="col-md-3">
                                        <input id="DebitRefund" name="DebitRefund" type="text" class="form-control input-sm" value="0.00" readonly>
                                    </div>
                                    <br><br>
                                </div>
                                
                                <!-- <div class="form-group">
                                    <div class='col-md-10' style="padding-right: 0px;">
                                        <div class="panel panel-info"><br>
                                            <div class="col-md-4  col-md-offset-1" style="text-align: center;margin-bottom: 10px;padding-left: 60px;"><h6><b>Denomination: </b></h6></div><br><br>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm100">RM 100</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm100" type="number" class="form-control input-sm" value="0" data-bill='100'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm100" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm50">RM 50</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm50" type="number" class="form-control input-sm" value="0"  data-bill='50'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm50" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm20">RM 20</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm20" type="number" class="form-control input-sm" value="0"  data-bill='20'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm20" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm10">RM 10</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm10" type="number" class="form-control input-sm" value="0"  data-bill='10'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm10" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm5">RM 5</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm5" type="number" class="form-control input-sm" value="0"  data-bill='5'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm5" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="rm1">RM 1</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilrm1" type="number" class="form-control input-sm" value="0"  data-bill='1'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalrm1" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="cents">CENTS</label>
                                                    <label class="col-md-1 control-label" for="darab">X</label>
                                                    
                                                    <div class="col-md-3">
                                                        <input name="bilcents" type="number" class="form-control input-sm" value="0.00"  data-bill='1'>
                                                    </div>
                                                    
                                                    <label class="col-md-1 control-label" for="abortus">=</label>
                                                    <div class="col-md-3">
                                                        <input name="totalcents" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-7 control-label" for="grandTotal">TOTAL</label>
                                                    <div class="col-md-4">
                                                        <input name="grandTotal" type="number" class="form-control input-sm" value="0.00" readonly>
                                                    </div>
                                                </div>
                                            </div>								
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class='col-md-12' style="padding:0 0 15px 0">
                        <table id="jqGridSummary" class="table table-striped"></table>
                        <div id="jqGridPagerSummary"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!--***************************** End Search + table *****************************-->
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            if(!$("table#jqGrid").is("[tabindex]")){
                $("#jqGrid").bind("jqGridGridComplete", function () {
                    $("table#jqGrid").attr('tabindex',3);
                    $("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',4);
                    $("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
                    if($('table#jqGrid').data('enter')){
                        $('td#input_jqGridPager input.ui-pg-input.form-control').focus();
                        $("table#jqGrid").data('enter',false);
                    }
                });
            }
            
            function onfocus_pageof(){
                $(this).keydown(function(e){
                    var code = e.keyCode || e.which;
                    if (code == '9'){
                        e.preventDefault();
                        $('input[name=Stext]').focus();
                    }
                });
                
                $(this).keyup(function(e) {
                    var code = e.keyCode || e.which;
                    if (code == '13'){
                        $("table#jqGrid").data('enter',true);
                    }
                });
            }
        });
    </script>
    <script src="js/finance/AR/tillenquiry/tillenquiry.js?v=1.1"></script>
@endsection