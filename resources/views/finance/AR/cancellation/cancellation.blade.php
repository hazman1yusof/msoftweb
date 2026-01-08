@extends('layouts.main')

@section('title', 'Cancellation')

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
    
    <!-- allocation -->
    #gridAllo_c input[type='text'][rowid]{
        height: 30%;
        padding: 4px 12px 4px 12px;
    }
    #alloText{width:9%;}#alloText{width:60%;}#alloCol{width: 30%;}
    #alloCol, #alloText{
        display: inline-block;
        height: 70%;
        padding: 4px 12px 4px 12px;
    }
    #alloSearch{
        border-style: solid;
        border-width: 0px 1px 1px 1px;
        padding-top: 5px;
        padding-bottom: 5px;
        border-radius: 0px 0px 5px 5px;
        background-color: #f8f8f8;
        border-color: #e7e7e7;
    }
    <!-- allocation ends -->
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
                            <select id="Scol" name="Scol" class="form-control input-sm" tabindex="1"> 
                                <option value="db_debtorcode">Debtor Code</option> 
                                <option value="db_auditno" selected>Audit No</option> 
                                <option value="db_recptno">Document No</option> 
                                <option value="db_entrydate">Date</option> 
                                <option value="db_mrn">MRN</option> 
                                <option value="db_deptcode">Department</option> 
                            </select>
                        </div>
                        
                        <div class="col-md-5" style="margin-top: 4px;">
                            <label class="control-label"></label>
                            <input name="Stext" type="search" placeholder="Search Here ..." class="form-control text-uppercase" tabindex="2">
                            
                            <div id="customer_text" style="display:none">
                                <div class='input-group'>
                                    <input id="customer_search" name="customer_search" type="text" maxlength="12" class="form-control input-sm">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span id="customer_search_hb" class="help-block"></span>
                            </div>
                            
                            <div id="department_text" style="display:none">
                                <div class='input-group'>
                                    <input id="department_search" name="department_search" type="text" maxlength="12" class="form-control input-sm">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span id="department_search_hb" class="help-block"></span>
                            </div>
                            
                            <div id="docuDate_text" class="form-inline" style="display:none">
                                FROM DATE <input id="docuDate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
                                TO DATE <input id="docuDate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase">
                                <button type="button" class="btn btn-primary btn-sm" id="docuDate_search">SEARCH</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="col-md-2">
                    <label class="control-label" for="Status">Status</label>
                    <select id="Status" name="Status" class="form-control input-sm">
                        <option value="All" selected>ALL</option>
                        <option value="Open">OPEN</option>
                        <option value="Posted">POSTED</option>
                        <option value="Cancelled">CANCELLED</option>
                    </select>
                </div> -->
            </fieldset>
        </form>
        
        <div class="panel panel-default" id="jqGrid_cancel_c">
            <div class="panel-heading">Cancellation</div>
            <div class="panel-body">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" id="cancel_navtab_rc" href="#tab-rc" aria-expanded="true" data-trantype='RC'>Receipt</a></li>
                        <li><a data-toggle="tab" id="cancel_navtab_rd" href="#tab-rd" data-trantype='RD'>Deposit</a></li>
                        <li><a data-toggle="tab" id="cancel_navtab_rf" href="#tab-rf" data-trantype='RF'>Refund</a></li>
                    </ul>
                    <div class="tab-content" style="padding: 10px 5px;">
                        <input id="trantype" name="trantype" type="hidden">
                        <div id="tab-rc" class="active in tab-pane fade">
                            <div id="fail_msg_rc" class="fail_msg"></div>
                            <div class='col-md-12' style="padding:0 0 15px 0" autocomplete="off">
                                <table id="jqGrid_rc" class="table table-striped"></table>
                                <div id="jqGridPager_rc"></div>
                            </div>
                        </div>
                        <div id="tab-rd" class="tab-pane fade">
                            <div id="fail_msg_rd" class="fail_msg"></div>
                            <div class='col-md-12' style="padding:0 0 15px 0">
                                <table id="jqGrid_rd" class="table table-striped"></table>
                                <div id="jqGridPager_rd"></div>
                            </div>
                        </div>
                        <div id="tab-rf" class="tab-pane fade">
                            <div id="fail_msg_rf" class="fail_msg"></div>
                            <div class='col-md-12' style="padding:0 0 15px 0">
                                <table id="jqGrid_rf" class="table table-striped"></table>
                                <div id="jqGridPager_rf"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--***************************** End Search + table *****************************-->
    
    <!--***************************** View Form for Receipt *****************************-->
    <div id="dialogForm_RC" title="Receipt">
        <div class='panel panel-info'>
            <div class="panel-heading">Receipt
                <!-- <a class='pull-right pointer text-primary' id='pdfgen2' href="" target="_blank"><span class='fa fa-print'></span>Print Receipt</a> -->
            </div>
            <div class="panel-body" style="position: relative;padding-bottom: 0px !important">
                <form style='width:99%' id='formdata_RC' autocomplete="off">
                    {{ csrf_field() }}
                    <input type='hidden' name='dbacthdr_source' value='PB'>
                    <input type='hidden' name='dbacthdr_tillno'>
                    <input type='hidden' name='dbacthdr_tillcode'>
                    <input type='hidden' name='dbacthdr_hdrtype'>
                    <input type='hidden' name='dbacthdr_paytype' id='dbacthdr_paytype'>
                    <input type='hidden' name='dbacthdr_auditno'>
                    <input type='hidden' name='updpayername'>
                    <input type='hidden' name='updepisode'>
                    <input type='hidden' name='dbacthdr_lineno_' value='1'>
                    <input type='hidden' name='dbacthdr_epistype'>
                    <input type='hidden' name='dbacthdr_billdebtor'>
                    <input type='hidden' name='dbacthdr_debtorcode'>
                    <input type='hidden' name='dbacthdr_lastrcnumber'>
                    <input type='hidden' name='dbacthdr_drcostcode'>
                    <input type='hidden' name='dbacthdr_crcostcode'>
                    <input type='hidden' name='dbacthdr_dracc'>
                    <input type='hidden' name='dbacthdr_cracc'>
                    <input type='hidden' name='dbacthdr_idno'>
                    <input type='hidden' name='dbacthdr_currency' value='RM'>
                    <input type='hidden' name='postdate'>
                    <input type='hidden' name='dbacthdr_RCOSbalance'>
                    <input type='hidden' name='dbacthdr_units'>
                    
                    <div class='col-md-6'>
                        <div class='panel panel-info'>
                            <div class="panel-heading">Select either Receipt or Deposit</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" name="optradio" value='receipt' checked>Receipt</label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value='deposit'>Deposit</label>
                                </div>
                                <div id="sysparam_c" class="form-group">
                                    <table id="sysparam" class="table table-striped"></table>
                                    <div id="sysparampg"></div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class='col-md-2 minuspad-15'>
                                        <label>Trantype: </label><input id="dbacthdr_trantype" name="dbacthdr_trantype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                    </div>
                                    
                                    <div class='col-md-10 '>
                                        <label>Description: </label><input id="dbacthdr_PymtDescription" name="dbacthdr_PymtDescription" type="text" class="form-control input-sm" rdonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='col-md-6'>
                        <div class='panel panel-info'>
                            <div class="panel-heading">Choose Payer Code</div>
                            <div class="panel-body">
                                <div class="col-md-12 minuspad-15">
                                    <label class="control-label" for="dbacthdr_payercode">Payer Code</label>
                                    <div class='input-group'>
                                        <input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"/>
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 minuspad-15">
                                    <label class="control-label" for="dbacthdr_payername">Payer Name</label>
                                    <div class=''>
                                        <input id="dbacthdr_payername" name="dbacthdr_payername" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 minuspad-15">
                                    <label class="control-label" for="dbacthdr_debtortype">Financial Class</label>
                                    <div class=''>
                                        <input id="dbacthdr_debtortype" name="dbacthdr_debtortype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                                
                                <div class='clearfix'></div>
                                <hr>
                                
                                <div class="col-md-12 minuspad-15">
                                    <label class="control-label" for="dbacthdr_debtortype">Receipt Number</label>
                                    <input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>
                                </div>
                                
                                <div id='divMrnEpisode'>
                                    <div class="col-md-8 minuspad-15">
                                        <label class="control-label" for="dbacthdr_mrn">MRN</label>
                                        <div class="">
                                            <div class='input-group'>
                                                <input id="dbacthdr_mrn" name="dbacthdr_mrn" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 minuspad-15">
                                        <label class="control-label" for="dbacthdr_episode">Episode</label>
                                        <div class="">
                                            <div class=''>
                                                <input id="dbacthdr_episno" name="dbacthdr_episno" type="text" class="form-control input-sm" rdonly>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label class="control-label col-md-1" for="dbacthdr_remark">Remark</label>
                            <div class='col-md-11'>
                                <input id="dbacthdr_remark" name="dbacthdr_remark" type="text" class="form-control input-sm text-uppercase">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-1" for="cancel_remark_RC" style="margin-top: 10px;">Cancel Remark</label>
                            <div class='col-md-11'>
                                <input id="cancel_remark_RC" name="cancel_remark_RC" type="text" class="form-control input-sm text-uppercase" style="margin-top: 10px;">
                            </div>
                        </div>
                        <div class='clearfix'></div>
                        <hr>
                    </div>
                </form>
                <div class='col-md-12'>
                    <div class='panel panel-info'>
                        <div class="panel-heading">Choose type of exchange</div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li><a data-toggle="tab" href="#tab-cash" form='#f_tab-cash'>Cash</a></li>
                                <li><a data-toggle="tab" href="#tab-card" form='#f_tab-card'>Card</a></li>
                                <li><a data-toggle="tab" href="#tab-cheque" form='#f_tab-cheque'>Cheque</a></li>
                                <li><a data-toggle="tab" href="#tab-debit" form='#f_tab-debit'>Auto Debit</a></li>
                                <li><a data-toggle="tab" href="#tab-forex" form='#f_tab-forex'>Forex</a></li>
                            </ul>
                            
                            <div class="tab-content">
                                <div id="tab-cash" class="tab-pane fade form-horizontal">
                                    <form id='f_tab-cash' autocomplete="off">
                                        <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
                                        <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
                                        </br>
                                        <div class="myformgroup">
                                            <label class="control-label col-md-2" for="dbacthdr_amount">Payment</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                            </div>
                                            
                                            <label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                            
                                            <label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="tab-card" class="tab-pane fade">
                                    <form id='f_tab-card' autocomplete="off">
                                        <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
                                        </br>
                                        <div id="g_paymodecard_c" class='col-md-4 minuspad-15'>
                                            <table id="g_paymodecard" class="table table-striped"></table>
                                            <div id="pg_paymodecard"></div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode: </label>
                                                <div class='col-md-9'>
                                                    <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly  data-validation="required" data-validation-error-msg="Please Enter Value" class="form-control input-sm text-uppercase">
                                                </div>
                                            </div>
                                            <!-- <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Pay Mode</th>
                                                        <th scope="col">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                                        </td>
                                                        <td>
                                                            <input id="paycard_description" name="paycard_description" type="text" rdonly class="form-control input-sm text-uppercase">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table> -->
                                        </div>
                                        <div class='col-md-8'>
                                            <div class="form-group">
                                                <div class='col-md-4'>
                                                    <label class="control-label" for="dbacthdr_amount">Payment</label>
                                                    <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 minuspad-15 form-group">
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_outamount">Outstanding</label>
                                                    <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label>
                                                    <input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='col-md-12'>
                                                    <label class="control-label" for="dbacthdr_reference">Reference</label>
                                                    <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" rdonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_authno">Authorization No.</label>
                                                    <div class=''>
                                                        <input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm text-uppercase" rdonly>
                                                    </div>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_expdate">Expiry Date</label>
                                                    <div class=''>
                                                        <input id="dbacthdr_expdate" name="dbacthdr_expdate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="tab-cheque" class="tab-pane fade form-horizontal">
                                    <form id='f_tab-cheque' autocomplete="off">
                                        <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CHEQUE">
                                        </br>
                                        <div class="myformgroup">
                                            <label class="control-label col-md-2" for="dbacthdr_entrydate">Transaction Date</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>
                                            </div>
                                            
                                            <label class="control-label col-md-2" for="dbacthdr_amount">Payment</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                            
                                            <label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label>
                                            <div class='col-md-4'>
                                                <input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2" for="dbacthdr_reference">Reference</label>
                                            <div class='col-md-8'>
                                                <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="tab-debit" class="tab-pane fade">
                                    <form id='f_tab-debit' autocomplete="off">
                                        </br>
                                        <div id="g_paymodebank_c" class='col-md-4 minuspad-15'>
                                            <table id="g_paymodebank" class="table table-striped"></table>
                                            <div id="pg_paymodebank"></div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label>
                                                <div class='col-md-9'>
                                                    <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                </div>
                                            </div>
                                            <!-- <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Pay Mode</th>
                                                        <th scope="col">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                                        </td>
                                                        <td>
                                                            <input id="paybank_description" name="paybank_description" type="text" rdonly class="form-control input-sm text-uppercase">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table> -->
                                        </div>
                                        <div class='col-md-8'>
                                            <div class="form-group">
                                                <div class='col-md-4'>
                                                    <label class="control-label" for="dbacthdr_entrydate">Transaction Date</label>
                                                    <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" rdonly>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 minuspad-15 myformgroup">
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label>
                                                    <input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_amount">Payment</label>
                                                    <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label>
                                                    <input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_outamount">Outstanding</label>
                                                    <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class='col-md-12'>
                                                    <label class="control-label" for="dbacthdr_reference">Reference</label>
                                                    <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="tab-forex" class="tab-pane fade">
                                    <form id='f_tab-forex' autocomplete="off">
                                        <input id="dbacthdr_currency" name="dbacthdr_currency" type="hidden">
                                        <input id="dbacthdr_rate" name="dbacthdr_rate" type="hidden">
                                        <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
                                        </br>
                                        <div id="g_forex_c" class='col-md-4 minuspad-15'>
                                            <table id="g_forex" class="table table-striped"></table>
                                            <div id="pg_forex"></div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label>
                                                <div class='col-md-9'>
                                                    <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" rdonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-8'>
                                            <div class="myformgroup">
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_outamount">Outstanding</label>
                                                    <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label>
                                                    <input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                            </div>
                                            <div class="myformgroup">
                                                <div class='col-md-4'>
                                                    <label class="control-label" for="rm">Currency</label>
                                                    <input id="rm" name="rm" type="text" value='RM' class="form-control input-sm" rdonly>
                                                </div>
                                                <div class='col-md-8'>
                                                    <label class="control-label" for="dbacthdr_amount">Amount</label>
                                                    <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="myformgroup">
                                                <div class='col-md-4'>
                                                    <label class="control-label" for="curroth">Currency</label>
                                                    <input id="curroth" name="curroth" type="text" class="form-control input-sm text-uppercase" rdonly>
                                                </div>
                                                <div class='col-md-8'>
                                                    <label class="control-label" for="dbacthdr_amount2">Amount</label>
                                                    <input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm" value="0.00" rdonly>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="dialog_allocation" title="Allocation">
        <div class='col-md-12' id='gridAlloc_c' style="padding:0">
            <table id="jqGridAlloc" class="table table-striped"></table>
            <div id="jqGridPagerAlloc"></div>
        </div>
    </div>
    <!--***************************** End View Form for Receipt *****************************-->
    
    <!--***************************** View Form for Refund *****************************-->
    <div id="dialogForm_RF" title="Refund">
        <form style='width:99%' id='formdata_RF' autocomplete="off">
            <input type='hidden' name='dbacthdr_source' value='PB'>
            <input type='hidden' name='dbacthdr_tillno'>
            <input type='hidden' name='dbacthdr_tillcode'>
            <input type='hidden' name='dbacthdr_hdrtype'>
            <input type='hidden' name='db_paytype' id='db_paytype'>
            <input type='hidden' name='dbacthdr_auditno'>
            <input type='hidden' name='updpayername'> 
            <input type='hidden' name='updepisode'>
            <input type='hidden' name='dbacthdr_lineno_' value='1'> 
            <input type='hidden' name='dbacthdr_epistype'>
            <input type='hidden' name='dbacthdr_billdebtor'>
            <input type='hidden' name='dbacthdr_debtorcode'>
            <input type='hidden' name='dbacthdr_debtortype'>
            <input type='hidden' name='dbacthdr_payername'>
            <input type='hidden' name='dbacthdr_lastrcnumber'>
            <input type='hidden' name='dbacthdr_drcostcode'>
            <input type='hidden' name='dbacthdr_crcostcode'>
            <input type='hidden' name='dbacthdr_dracc'>
            <input type='hidden' name='dbacthdr_cracc'>
            <input type='hidden' name='dbacthdr_idno'>
            <input type='hidden' name='dbacthdr_currency' value='RM'>
            <input type='hidden' name='postdate'>
            <input type='hidden' name='dbacthdr_RCOSbalance'>
            <input type='hidden' name='dbacthdr_units'>
            <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
            
            <div class='col-md-12' style="padding:15px 5px">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1" for="dbacthdr_recptno">Refund No</label>
                    <div class="col-md-2">
                        <input id="dbacthdr_recptno" name="db_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>
                    </div>
                    
                    <label class="control-label col-md-1" for="dbacthdr_payercode">Payer</label>
                    <div class="col-md-4">
                        <div class='input-group'>
                            <input id="dbacthdr_payercode" name="db_payercode" type="text" class="form-control input-sm text-uppercase">
                            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                        </div>
                        <span class="help-block"></span>
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1" for="dbacthdr_remark">Remark</label>
                    <div class="col-md-8"> 
                        <textarea class="form-control input-sm text-uppercase" name="db_remark" rows="3" cols="55" id="dbacthdr_remark"> </textarea>
                    </div>
                </div>
            </div>
            
            <div class='col-md-12'>
                <div class='panel panel-info'>
                    <div class="panel-heading">Choose type of exchange</div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li><a data-toggle="tab" href="#tab-cash-rf" form='#f_tab-cash'>Cash</a></li>
                            <li><a data-toggle="tab" href="#tab-card-rf" form='#f_tab-card'>Card</a></li>
                            <li><a data-toggle="tab" href="#tab-cheque-rf" form='#f_tab-cheque'>Cheque</a></li>
                            <li><a data-toggle="tab" href="#tab-debit-rf" form='#f_tab-debit'>Auto Debit</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div id="tab-cash-rf" class="tab-pane fade form-horizontal">
                                <form id='f_tab-cash' autocomplete="off">
                                    <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
                                    <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
                                    </br>
                                    <div class="myformgroup">
                                        <label class="control-label col-md-2" for="dbacthdr_amount">Allocation Amount</label> 
                                        <div class='col-md-3'> 
                                            <input id="dbacthdr_amount" name="db_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                        </div>
                                        
                                        <!-- <label class="control-label col-md-2" for="dbacthdr_allocamt">Allocation Amount</label> 
                                        <div class='col-md-3'> 
                                            <input id="dbacthdr_allocamt" name="db_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
                                        </div> -->
                                    </div>
                                </form>
                            </div>
                            <div id="tab-card-rf" class="tab-pane fade">
                                <form id='f_tab-card' autocomplete="off">
                                    <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
                                    </br>
                                    <!-- <div id="g_paymodecard_c" class='col-md-4 col-md-offset-8 minuspad-15'>
                                        <table id="g_paymodecard" class="table table-striped"></table>
                                        <div id="pg_paymodecard"></div>
                                        <hr>
                                    </div> -->
                                    <div id="g_paycard_c" class='col-md-4 minuspad-15'>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Pay Mode</th>
                                                    <th scope="col">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input id="dbacthdr_paymode" name="db_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                                    </td>
                                                    <td>
                                                        <input id="paycard_description" name="db_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><hr>
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode: </label> 
                                            <div class='col-md-9'> 
                                                <input id="dbacthdr_paymode" name="db_paymode" type="text" rdonly  data-validation="required" data-validation-error-msg="Please Enter Value" class="form-control input-sm text-uppercase">
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class="form-group">
                                            <div class='col-md-4'> 
                                                <label class="control-label" for="dbacthdr_amount">Refund Amount</label> 
                                                <input id="dbacthdr_amount" name="  " type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <div class='col-md-12'>
                                                <label class="control-label" for="dbacthdr_reference">Reference</label> 
                                                <input id="dbacthdr_reference" name="db_reference" type="text" class="form-control input-sm text-uppercase">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='col-md-6'>
                                                <label class="control-label" for="dbacthdr_authno">Authorization No.</label> 
                                                <div class=''> 
                                                    <input id="dbacthdr_authno" name="db_authno" type="text" class="form-control input-sm text-uppercase">
                                                </div>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class="control-label" for="dbacthdr_expdate">Expiry Date</label> 
                                                <div class=''> 
                                                    <input id="dbacthdr_expdate" name="db_expdate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-cheque-rf" class="tab-pane fade form-horizontal">
                                <form id='f_tab-cheque' autocomplete="off">
                                    <input id="dbacthdr_paymode" name="db_paymode" type="hidden" value="CHEQUE">
                                    </br>
                                    <div class="myformgroup">
                                        <label class="control-label col-md-2" for="dbacthdr_entrydate">Transaction Date</label> 
                                        <div class='col-md-4'> 
                                            <input id="dbacthdr_entrydate" name="db_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                    <div class="myformgroup">
                                        <label class="control-label col-md-2" for="dbacthdr_amount">Refund Amount</label> 
                                        <div class='col-md-3'> 
                                            <input id="dbacthdr_amount" name="db_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                        </div>
                                        <label class="control-label col-md-2" for="dbacthdr_allocamt">Allocation Amount</label> 
                                        <div class='col-md-3'> 
                                            <input id="dbacthdr_allocamt" name="db_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2" for="dbacthdr_reference">Reference</label> 
                                        <div class='col-md-8'> 
                                            <input id="dbacthdr_reference" name="db_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-debit-rf" class="tab-pane fade">
                                <form id='f_tab-debit' autocomplete="off">
                                    </br>
                                    <div id="g_paymodebank_c" class='col-md-4 minuspad-15'>
                                        <table id="g_paymodebank" class="table table-striped"></table>
                                        <div id="pg_paymodebank"></div>
                                        <hr>
                                        <div class="form-group">
                                            <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label> 
                                            <div class='col-md-9'> 
                                                <input id="dbacthdr_paymode" name="db_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="g_paybank_c" class='col-md-4 minuspad-15'>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Pay Mode</th>
                                                    <th scope="col">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input id="dbacthdr_paymode" name="db_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                                    </td>
                                                    <td>
                                                        <input id="paybank_description" name="paybank_description" type="text" rdonly class="form-control input-sm text-uppercase">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class="form-group">
                                            <div class='col-md-4'> 
                                                <label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
                                                <input id="dbacthdr_entrydate" name="db_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="myformgroup">
                                            <div class='col-md-6'> 
                                                <label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
                                                <input id="dbacthdr_bankcharges" name="db_bankcharges" type="text" class="form-control input-sm" value="0.00">
                                            </div>
                                            <div class='col-md-6'> 
                                                <label class="control-label" for="dbacthdr_amount">Payment</label> 
                                                <input id="dbacthdr_amount" name="db_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                            </div>
                                        </div>
                                        <div class="myformgroup">
                                            <div class='col-md-6'> 
                                                <label class="control-label" for="dbacthdr_allocamt">Allocation Amount</label> 
                                                <input id="dbacthdr_allocamt" name="db_allocamt" type="text" class="form-control input-sm" value="0.00" rdonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='col-md-12'> 
                                                <label class="control-label" for="dbacthdr_reference">Reference</label> 
                                                <input id="dbacthdr_reference" name="db_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='col-md-12' id='gridAllo_c' style="padding:0">
                <hr>
                <table id="gridAllo" class="table table-striped"></table>
                <div id="pagerAllo"></div>
            </div>
            
            <div class="col-md-10 col-md-offset-1" id="alloSearch">
                <label class="control-label" id='alloLabel'>Search</label>
                <input id="alloText" type="text" class="form-control input-sm">
                <select class="form-control" id="alloCol">
                    <option value="invno" >invoice no</option>
                    <option value="auditno" >auditno</option>
                    <option value="mrn" >mrn</option>
                    <option value="recptno" >docno</option>
                    <option value="newic" >newic</option>
                    <option value="staffid" >staffid</option>
                    <option value="batchno" >batchno</option>
                </select>
            </div>
        </form>
        <!--end formdata-->
    </div>
    <!--***************************** End View Form for Refund *****************************-->
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
    <script src="js/finance/AR/cancellation/cancellation.js?v=1.5"></script>
@endsection