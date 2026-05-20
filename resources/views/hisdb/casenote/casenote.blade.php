@extends('layouts.main')

@section('title', 'Case Note')

@section('style')
    
    input.uppercase {
        text-transform: uppercase;
    }
    
@endsection

@section('body')
@include('hisdb.pat_mgmt.mdl_patient_MR')
@include('hisdb.pat_mgmt.itemselector_MR')
    
    <!--***************************** Search + table *****************************-->
    <div class='row'>
        <form id="showForm" class="formclass" style='width: 55%; position: relative; float: left;'>
            <div class='col-md-12' style="padding: 15px 15px;">
                <b>NAME: <span id="name_show_casenote"></span></b><br>
                MRN: <span id="mrn_show_casenote"></span>
                SEX: <span id="sex_show_casenote"></span>
                DOB: <span id="dob_show_casenote"></span>
                AGE: <span id="age_show_casenote"></span>
                RACE: <span id="race_show_casenote"></span>
                RELIGION: <span id="religion_show_casenote"></span><br>
                OCCUPATION: <span id="occupation_show_casenote"></span>
                CITIZENSHIP: <span id="citizenship_show_casenote"></span>
                AREA: <span id="area_show_casenote"></span>
            </div>
            <div class='col-md-12' style="padding: 15px 15px;">
                <button type="button" class="btn btn-primary btn-sm" id="profile">Profile</button>
            </div>
        </form>

        <form id="searchForm" class="formclass" style='width: 40%; position: relative; float: right;' onkeydown="return event.key != 'Enter';">
            <fieldset>
                <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                
                <div class='col-md-12' style="padding: 0 0 15px 0;">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label" for="Scol">Search By : </label>
                            <select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
                        </div>
                        
                        <div class="col-md-5" style="margin-top: 4px;">
                            <label class="control-label"></label>
                            <input style="display: none;" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
                            
                            <div id="patient_text">
                                <div class='input-group'>
                                    <input id="patient_search" name="patient_search" type="text" maxlength="12" class="form-control input-sm">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span id="patient_search_hb" class="help-block"></span>
                            </div>
                            
                            <div id="dob_text" class="form-inline" style="display: none;">
                                <input id="dob" type="date" placeholder="DATE" class="form-control text-uppercase">
                                <button type="button" class="btn btn-primary btn-sm" id="dob_search">SEARCH</button>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    
    <div class='row'>
        <div class="panel panel-default">
            <div class="panel-heading">Case Note</div>
            <div class="panel-body">
                <div class='col-md-12' style="padding: 0 0 15px 0;">
                    <table id="jqGrid" class="table table-striped"></table>
                    <div id="jqGridPager"></div>
                </div>
            </div>
        </div>
    </div>
    <!--*************************** End Search + table ***************************-->
    
@endsection

@section('scripts')
    
    <script type="text/javascript">
        $(document).ready(function (){
            if(!$("table#jqGrid").is("[tabindex]")){
                $("#jqGrid").bind("jqGridGridComplete", function (){
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
                $(this).keydown(function (e){
                    var code = e.keyCode || e.which;
                    if (code == '9'){
                        e.preventDefault();
                        $('input[name=Stext]').focus();
                    }
                });
                
                $(this).keyup(function (e){
                    var code = e.keyCode || e.which;
                    if (code == '13'){
                        $("table#jqGrid").data('enter',true);
                    }
                });
            }
        });
    </script>
    
    <script src="js/hisdb/casenote/casenote.js"></script>
    <script src="js/hisdb/pat_mgmt/pat_nok_MR.js"></script>
	<script src="js/hisdb/pat_mgmt/pat_emr_MR.js"></script>
	<script src="js/hisdb/pat_mgmt/textfield_modal_MR.js"></script>
	<script src="js/hisdb/pat_mgmt/landing_MR.js?v=1.10"></script>
    <script src="js/myjs/modal-fix.js"></script>
	<script src="js/hisdb/pat_mgmt/biodata_MR.js?v=1.1"></script>
    <script>$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="plugins/form-validator/jquery.form-validator.min.js"></script>
	
	<script src="plugins/flot/jquery.flot.js"></script>
	<script src="plugins/flot/jquery.flot.errorbars.js"></script>
	<script src="plugins/flot/jquery.flot.navigate.js"></script>
	<script src="plugins/flot/jquery.flot.crosshair.js"></script>
	<script src="plugins/flot/jquery.flot.symbol.js"></script>
@endsection