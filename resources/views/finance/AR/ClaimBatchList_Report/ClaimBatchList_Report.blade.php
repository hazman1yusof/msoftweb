@extends('layouts.main')

@section('title', 'Claim Batch Listing')

@section('style')
    body{
        background: #00808024 !important;
    }
    .container.mycontainer{
        padding-top: 2%;
    }
    .mycontainer .panel-default{
        border-color: #9bb7b7 !important;
    }
    .mycontainer .panel-default > .panel-heading{
        background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
        font-weight: bold;
    }
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
    .mycontainer .btnvl{
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
        font-weight: bold;
    }
    .btnform .btn{
        width: -webkit-fill-available !important;
    }
@endsection('style')

@section('body')
    <div class="container mycontainer">
        <div class="row">
            <div class="col-md-9" style="margin-left: 130px;">
                <div class="panel panel-default">
                    <div class="panel-heading">This program will print the cover letter
                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                            id="btn_grp_edit"
                            style="position: absolute;
                                    padding: 0 0 0 0;
                                    right: 40px;
                                    top: 5px;">
                            <button type="button" class="btn btn-default" id="edit">
                                <span class="fa fa-edit fa-lg"></span> Edit
                            </button>
                            <button type="button" class="btn btn-default" data-oper='add' id="save">
                                <span class="fa fa-save fa-lg"></span> Save
                            </button>
                            <button type="button" class="btn btn-default" id="cancel">
                                <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel
                            </button>
                            <button type="button" class="btn btn-default" id="excel">
                                <span class="fa fa-file-excel-o fa-lg"></span> Excel
                            </button>
                            <!-- <button type="button" class="mybtn btn btn-default" name="ClaimBatchList_xls" id="excel">
                                <span class="fa fa-file-excel-o fa-lg"></span> Test
                            </button> -->
                        </div>
                    </div>
                    <div class="panel-body">
                        <form class='form-horizontal' style='width:99%' id='formdata'>
                            <input type="hidden" name="action">
                            <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-2 control-label" for="date1">Date</label>
                                    <div class="col-md-4">
                                        <input id="date1" name="date1" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-2 control-label" for="epis_type">Episode Type</label>
                                    <div class="col-md-4">
                                        <select id="epis_type" class="form-control">
                                            <option value="All" selected>All</option>
                                            <option value="Out Patient">Out Patient</option>
                                            <option value="In-Patient">In-Patient</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-2 control-label" for="debtorcode_from">Debtor From</label>
                                    <div class="col-md-4">
                                        <div class='input-group'>
                                            <input id="debtorcode_from" name="debtorcode_from" type="text" class="form-control input-sm" autocomplete="off" value="">
                                            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-2 control-label" for="debtorcode_to">Debtor Code</label>
                                    <div class="col-md-4">
                                        <div class='input-group'>
                                            <input id="debtorcode_to" name="debtorcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                            <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <input id="title" name="title" type="text" class="form-control input-sm" style="font-weight: bold;">
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 10px;">
                                        <textarea id="content" name="content" type="text" class="form-control input-sm" rows="10"></textarea>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 10px;">
                                        <input id="sign_off" name="sign_off" type="text" class="form-control input-sm" style="font-weight: bold;" value="Yours faithfully,">
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 10px;">
                                        <label class="col-md-2 control-label" for="officer">Officer</label>
                                        <div class="col-md-5">
                                            <input id="officer" name="officer" type="text" class="form-control input-sm" style="font-weight: bold;">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 10px;">
                                        <label class="col-md-2 control-label" for="designation">Designation</label>
                                        <div class="col-md-5">
                                            <input id="designation" name="designation" type="text" class="form-control input-sm" style="font-weight: bold;">
                                        </div>
                                        <div class="pull-right">
                                            <!-- <button name="ClaimBatchList_Report" type="button" class="btn btn-default btn-sm" id="pdfgen1">
                                                Cover Letter
                                            </button> -->
                                            <button name="ClaimBatchList_pdf" type="button" class="mybtn btn btn-default btn-sm">
                                                Cover Letter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="ExcelDialog" title="Setting Fields">
        <form class='form-horizontal' style='width: 99%' id='formSetting'>
            <input type="hidden" name="action">
            
            <div class="col-md-4" style="padding-left: 0px;">
                <div class="panel panel-default">
                    <div class="panel-heading" style="height: 38px;padding-top: 3px;">
                        <button class="btn btn-default btn-sm" type="button" id="refresh_fields" style="float: right;">
                            <span class="icon glyphicon glyphicon-refresh"></span>
                        </button>
                    </div>
                    <div class="panel-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 65%">Fields</th>
                                    <th scope="col">Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="seqno" value="1">
                                        <label class="form-check-label" for="seqno">Seq No.</label>
                                    </td>
                                    <td><input id="seqno_size" name="seqno_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="billno" value="1">
                                        <label class="form-check-label" for="billno">Bill No.</label>
                                    </td>
                                    <td><input id="billno_size" name="billno_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="billdate" value="1">
                                        <label class="form-check-label" for="billdate">Bill Date</label>
                                    </td>
                                    <td><input id="billdate_size" name="billdate_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="staffID" value="1">
                                        <label class="form-check-label" for="staffID">Staff ID</label>
                                    </td>
                                    <td><input id="staffID_size" name="staffID_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="staffname" value="1">
                                        <label class="form-check-label" for="staffname">Staff Name</label>
                                    </td>
                                    <td><input id="staffname_size" name="staffname_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="dept" value="1">
                                        <label class="form-check-label" for="dept">Department</label>
                                    </td>
                                    <td><input id="dept_size" name="dept_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="glrefno" value="1">
                                        <label class="form-check-label" for="glrefno">GL Ref No.</label>
                                    </td>
                                    <td><input id="glrefno_size" name="glrefno_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="patname" value="1">
                                        <label class="form-check-label" for="patname">Patient Name</label>
                                    </td>
                                    <td><input id="patname_size" name="patname_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="relationship" value="1">
                                        <label class="form-check-label" for="relationship">Relationship</label>
                                    </td>
                                    <td><input id="relationship_size" name="relationship_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="membership" value="1">
                                        <label class="form-check-label" for="membership">Membership</label>
                                    </td>
                                    <td><input id="membership_size" name="membership_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="epistype" value="1">
                                        <label class="form-check-label" for="epistype">Episode Type</label>
                                    </td>
                                    <td><input id="epistype_size" name="epistype_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="regdate" value="1">
                                        <label class="form-check-label" for="regdate">Register Date</label>
                                    </td>
                                    <td><input id="regdate_size" name="regdate_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="regtime" value="1">
                                        <label class="form-check-label" for="regtime">Register Time</label>
                                    </td>
                                    <td><input id="regtime_size" name="regtime_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="dischgdate" value="1">
                                        <label class="form-check-label" for="dischgdate">Discharge Date</label>
                                    </td>
                                    <td><input id="dischgdate_size" name="dischgdate_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="dischgtime" value="1">
                                        <label class="form-check-label" for="dischgtime">Discharge Time</label>
                                    </td>
                                    <td><input id="dischgtime_size" name="dischgtime_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="doctorname" value="1">
                                        <label class="form-check-label" for="doctorname">Doctor Name</label>
                                    </td>
                                    <td><input id="doctorname_size" name="doctorname_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="diagnosis" value="1">
                                        <label class="form-check-label" for="diagnosis">Diagnosis</label>
                                    </td>
                                    <td><input id="diagnosis_size" name="diagnosis_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="mcserialno" value="1">
                                        <label class="form-check-label" for="mcserialno">MC Serial No.</label>
                                    </td>
                                    <td><input id="mcserialno_size" name="mcserialno_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="mcdatefrom" value="1">
                                        <label class="form-check-label" for="mcdatefrom">MC Date From</label>
                                    </td>
                                    <td><input id="mcdatefrom_size" name="mcdatefrom_size" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" id="fields" name="mcdateto" value="1">
                                        <label class="form-check-label" for="mcdateto">MC Date To</label>
                                    </td>
                                    <td><input id="mcdateto_size" name="mcdateto_size" type="text" class="form-control input-sm"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4" style="padding-left: 0px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Selected Fields</div>
                    <div class="panel-body" style="height: 982px;">
                        <textarea class="form-control" id="selectedFields" rows="54" readonly></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4" style="padding-left: 0px;padding-right: 0px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Generate/View</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ourRef">
                                        <label class="form-check-label" for="ourRef">Our Reference</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin: auto;width: 60%;">
                                <button name="ClaimBatchList_xls" type="button" class="mybtn btn btn-default btn-sm">
                                    Export to Excel
                                </button>
                            </div>
                            
                            <div style="margin: auto;width: 60%;padding-top: 10px;">
                                <button type="button" class="btn btn-default btn-sm" id="exit_dialog" style="width: 103px;">Exit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="js/finance/AR/ClaimBatchList_Report/ClaimBatchList_Report.js"></script>
@endsection