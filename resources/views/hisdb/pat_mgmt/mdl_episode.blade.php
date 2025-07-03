<!-- Large modal -->
<div id="editEpisode" class="modal fade" data-backdrop="false" data-keyboard="false" role="dialog" aria-labelledby="editEpisode" aria-hidden="true" style="display: none; z-index: 110;">
    <div class="modal-dialog modal-lg">
        <input type="hidden" name="rowid" id="epis_rowid">
        <input type="hidden" name="idno" id="epis_idno">
        <input type="hidden" name="mrn_episode" id="mrn_episode">
        <input type="hidden" name="episode_oper" id="episode_oper" value="add">
        <input type="hidden" name="apptidno" id="apptidno_epis">
        <input type="hidden" name="preepisidno_epis" id="preepisidno_epis">
        
        <div class="modal-content">
            <div class="modal-header label-info form-horizontal" style="position: sticky; top: 0px; z-index: 3;">
                <form id="epis_header">
                    <button type="button" class="" data-dismiss="modal" aria-label="Close" 
                    style="z-index: 100; 
                        right: 10px; 
                        color: white; 
                        position: absolute; 
                        background: #d34242; 
                        border-radius: 5px;">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true" style="top: 3px;"></span>
                    </button>
                    
                    <div class="form-group">
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-1">
                            <small for="txt_epis_no">MRN</small>
                            <input class="form-control input-sm" id="txt_epis_mrn" name="txt_epis_mrn" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-1">
                            <small for="txt_epis_no">EPISODE</small>
                            <input class="form-control input-sm" id="txt_epis_no" name="txt_epis_no" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-1">
                            <small for="txt_epis_iPesakit">IPESAKIT</small>
                            <input class="form-control input-sm" id="txt_epis_iPesakit" name="txt_epis_iPesakit" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-1">
                            <small for="txt_epis_type">TYPE: </small>
                            <input id="txt_epis_type" name="txt_epis_type" placeholder="" type="text" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-6">
                            <small for="txt_epis_type">NAME: </small>
                            <br/><big id="txt_epis_name" class="epis_name_big"></big></b>
                        </div>
                        <div class="col-sm-1">
                            <small for="txt_epis_date">DATE: </small>
                            <input class="form-control" name="txt_epis_date" id="txt_epis_date" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-1">
                            <small for="txt_epis_time">TIME: </small>
                            <input class="form-control" name="txt_epis_time" id="txt_epis_time" placeholder="" type="text" readonly>
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <div class="col-md-offset-1 col-md-8">
                            NAME:  <b><big id="txt_epis_name"></big></b>
                        </div>
                    </div> -->
                </form>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- episode -->
                        <div class="panel panel-default" style="position: relative;">
                            <div class="panel-heading clearfix" id="toggle_tabEpisode" data-toggle="collapse" data-target="#tabEpisode">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong><span id="episode_title_text"></span> EPISODE</strong></h5>
                                </div>
                            </div>
                            <div id="tabEpisode" class="panel-collapse collapse in">
                                <form id="form_episode" name="form_episode" autocomplete="off">
                                    <div class="panel-body form-horizontal">
                                        <!-- Tab content begin -->
                                        <div class="form-group">
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_dept">Registration Department</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_dept" required tabindex=1>
                                                    <input type="hidden" id="hid_epis_dept" name="regdept"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_dept" data-toggle="modal" onclick_xguna="pop_item_select('epis_dept');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_source">Registration Source</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_epis_source" id="txt_epis_source" required tabindex=2>
                                                    <input type="hidden" id="hid_epis_source" name=""/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_source" data-toggle="modal" onclick_xguna="pop_item_select('epis_source');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_case">Case</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_epis_case" id="txt_epis_case" required tabindex=3>
                                                    <input type="hidden" id="hid_epis_case" name="case_code"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_case" data-toggle="modal" onclick_xguna="pop_item_select('epis_case');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_doctor">Doctor</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_epis_doctor" id="txt_epis_doctor" required tabindex=4>
                                                    <input type="hidden" id="hid_epis_doctor" name="admdoctor"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_doctor" data-toggle="modal" onclick_xguna="pop_item_select('epis_doctor');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            @if(request()->get('epistycode') == 'IP')
                                            <div class="col-md-offset-1 col-md-4">
                                                <small for="txt_epis_bed">ACCOMODATION : BED</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txt_epis_bed" id="txt_epis_bed">
                                                    <input type="hidden" id="hid_epis_bed" name="admbed"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_bed"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <small for="txt_epis_bed">WARD</small>
                                                <input type="text" class="form-control form-mandatory" name="txt_epis_ward" id="txt_epis_ward" disabled="">
                                            </div>
                                            <div class="col-md-1">
                                                <small for="txt_epis_bed">ROOM</small>
                                                <input type="text" class="form-control form-mandatory" name="txt_epis_room" id="txt_epis_room" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="txt_epis_bed">BED TYPE</small>
                                                <input type="text" class="form-control form-mandatory" name="txt_epis_bedtype" id="txt_epis_bedtype" disabled>
                                            </div>
                                            @endif
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_fin">Financial Class</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_fin" name="txt_epis_fin" required>
                                                    <input type="hidden" id="hid_epis_fin" name="pay_type"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_fin" data-toggle="modal" onclick_xguna="pop_item_select('epis_fin');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="cmb_epis_pay_mode">Pay Mode</small>
                                                <select id="cmb_epis_pay_mode" name="pyrmode" class="form-control form-disabled" required>
                                                    <option value='CASH'>Cash</option>
                                                    <option value='CARD'>Card</option>
                                                    <option value='WAITING GL'>Waiting GL</option>
                                                    <option value='OPEN CARD'>Open Card</option>
                                                    <option value='PWD'>Consultant Guarantee (PWD)</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_payer">Payer</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_epis_payer" id="txt_epis_payer" required>
                                                    <input type="hidden" id="hid_epis_payer" name="payercode"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_epis_payer"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_bill_type">Bill Type</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_epis_bill_type" id="txt_epis_bill_type" required>
                                                    <input type="hidden" id="hid_epis_bill_type" name="bill_type"/>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_bill_type_info"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_refno">Reference No</small>
                                                <div class="input-group">
                                                    <input id="txt_epis_refno" type="text" name="txt_epis_refno" class="form-control form-mandatory">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_refno_info"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-offset-1 col-md-10">
                                                <small for="txt_epis_our_refno">Our Reference No</small>
                                                <input id="txt_epis_our_refno" name="txt_epis_our_refno" type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group" style="padding-top: 30px !important;">
                                            <div class="col-md-offset-1 col-md-2">
                                                <small for="rad_epis_fee">Admin Fee</small>
                                                <div class="panel-body" style="padding: 3px !important;">
                                                    <small class="radio-inline"><input type="radio" value="1" name="rad_epis_fee" id="rad_epis_fee_yes" checked>Yes</small>
                                                    <small class="radio-inline"><input type="radio" value="0" name="rad_epis_fee" id="rad_epis_fee_no">No</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <small for="txt_epis_queno">Queue No</small>
                                                <input id="txt_epis_queno" type="text" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <small for="txt_epis_type">Case Type</small>
                                                <select id="cmb_epis_case_maturity" name="cmb_epis_case_maturity" class="form-control input-sm form-mandatory" required>
                                                    <option value="">- Select -</option>
                                                    <option value="1">New Case</option>
                                                    <option value="2">Follow Up</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <small for="txt_epis_type">Pregnancy</small>
                                                <select id="cmb_epis_pregnancy" name="cmb_epis_pregnancy" class="form-control input-sm form-mandatory" required>
                                                    <option value="">- Select -</option>
                                                    <option value="Pregnant">Pregnant</option>
                                                    <option value="Non-Pregnant" selected>Non-Pregnant</option>
                                                </select>
                                            </div>
                                            <div class="pull-right" style="margin-top: 10px; margin-right: 25px;">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success" id="btn_save_episode">Save changes</button>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- doctor -->
                        <div class="panel panel-default" style="position: relative;" id="div_doctor">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabDoctor" data-toggle="collapse" data-target="#tabDoctor">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>DOCTOR</strong></h5>
                                </div>
                            </div>
                            <div id="tabDoctor" class="panel-collapse collapse">
                                <div class="panel-body form-horizontal">
                                    <div class="col-xs-6">
                                        <div id="jqGrid_doctor_c">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                <table id="jqGrid_doctor" class="table table-striped"></table>
                                                <div id="jqGridPager_doctor"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" id="form_doc">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_doc">
                                                    <button type="button" class="btn btn-default" id="add_doc">
                                                        <span class="fa fa-plus-square-o fa-lg"></span> Add 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_doc">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_doc">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_doc">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <small for="doc_no">No.</small>
                                                <input id="doc_no" name="doc_no" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="doc_doctorcode">Doctor</small>
                                                <div class='input-group'>
                                                    <input id="doc_doctorcode" name="doc_doctorcode" type="text" class="form-control uppercase">
                                                    <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="doc_doctorname">Doctor Name</small>
                                                <input id="doc_doctorname" name="doc_doctorname" data-validation="required" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-12">
                                                <small for="doc_discipline">Discipline</small>
                                                <input id="doc_discipline" name="doc_discipline" data-validation="required" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="doc_date">Date</small>
                                                <input id="doc_date" name="doc_date" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="doc_time">Time</small>
                                                <input id="doc_time" name="doc_time" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="doc_status">Status</small>
                                                <select id="doc_status" name="doc_status" class="form-control">
                                                    <option value="Admitting" selected>Admitting</option>
                                                    <option value="Referring">Referring</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="doc_status">Add User</small>
                                                <input id="doc_adduser" name="doc_adduser" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="doc_status">Add Date</small>
                                                <input id="doc_adddate" name="doc_adddate" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="doc_status">Computerid</small>
                                                <input id="doc_computerid" name="doc_computerid" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- bed -->
                        @if(request()->get('epistycode') == 'IP')
                        <div class="panel panel-default" style="position: relative;" id="div_bed">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabBed" data-toggle="collapse" data-target="#tabBed">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>BED ALLOCATION</strong></h5>
                                </div>
                            </div>
                            <div id="tabBed" class="panel-collapse collapse">
                                <div class="panel-body form-horizontal">
                                    <div class="col-xs-6">
                                        <div id="jqGrid_bed_c">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                <table id="jqGrid_bed" class="table table-striped"></table>
                                                <div id="jqGridPager_bed"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" id="form_bed">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_bed">
                                                    <button type="button" class="btn btn-default" id="add_bed">
                                                        <span class="fa fa-plus-square-o fa-lg"></span> Transfer 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_bed">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_bed">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="bed_date">Date</small>
                                                <input id="bed_date" name="bed_date" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="bed_time">Time</small>
                                                <input id="bed_time" name="bed_time" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="doc_doctorcode">Bed No.</small>
                                                <div class='input-group'>
                                                    <input id="bed_bednum" name="bed_bednum" type="text" class="form-control uppercase">
                                                    <a class='input-group-addon btn btn-info' id="bed_bednum_a"><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="bed_bedtype">Bed Type</small>
                                                <input id="bed_bedtype" name="bed_bedtype" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="bed_room">Room</small>
                                                <input id="bed_room" name="bed_room" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                            <div class="col-md-8">
                                                <small for="bed_ward">Ward</small>
                                                <input id="bed_ward" name="bed_ward" type="text" class="form-control" data-validation="required" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="bed_status">Status</small>
                                                <select id="bed_status" name="bed_status" class="form-control">
                                                    <option value="OCCUPIED" selected>Occupied</option>
                                                    <option value="ISOLATED">Isolated</option>
                                                    <option value="MAINTENANCE">Maintenance</option>
                                                    <option value="HOUSEKEEPING">Housekeeping</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="bed_isolate">Isolated</small>
                                                <select id="bed_isolate" name="bed_isolate" class="form-control">
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="bed_lodger">Lodger</small>
                                                <div class='input-group'>
                                                    <input id="bed_lodger" name="bed_lodger" type="text" class="form-control uppercase">
                                                    <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- NOK -->
                        <div class="panel panel-default" style="position: relative;" id="div_nok">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabNok" data-toggle="collapse" data-target="#tabNok">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>NEXT OF KIN</strong></h5>
                                </div>
                            </div>
                            <div id="tabNok" class="panel-collapse collapse">
                                <div class="panel-body form-horizontal">
                                    <div class="col-xs-6">
                                        <div id="jqGrid_nok_c">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                <table id="jqGrid_nok" class="table table-striped"></table>
                                                <div id="jqGridPager_nok"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" id="form_nok">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_nok">
                                                    <button type="button" class="btn btn-default" id="add_nok">
                                                        <span class="fa fa-plus-square-o fa-lg"></span> Add 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_nok">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="save_nok">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_nok">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input id="nok_idno" name="nok_idno" type="hidden">
                                        
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="nok_name">Name</small>
                                                <input id="nok_name" name="nok_name" type="text" class="form-control" data-validation="required">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="nok_addr1">Address</small>
                                                <input id="nok_addr1" name="nok_addr1" type="text" class="form-control" data-validation="required" style="margin-bottom: 2px;">
                                                <input id="nok_addr2" name="nok_addr2" type="text" class="form-control" style="margin-bottom: 2px;">
                                                <input id="nok_addr3" name="nok_addr3" type="text" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_postcode">Postcode</small>
                                                <input id="nok_postcode" name="nok_postcode" type="text" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_telh">Tel (H)</small>
                                                <input id="nok_telh" name="nok_telh" type="text" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_telo">Tel (O)</small>
                                                <input id="nok_telo" name="nok_telo" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_relate">Relationship</small>
                                                <div class='input-group'>
                                                    <input id="nok_relate" name="nok_relate" type="text" class="form-control uppercase" data-validation="required">
                                                    <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="nok_telhp">Tel (H/P)</small>
                                                <input id="nok_telhp" name="nok_telhp" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-2">
                                                <small for="nok_ext">Ext</small>
                                                <input id="nok_ext" name="nok_ext" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_computerid">Computer ID</small>
                                                <input id="nok_computerid" name="nok_computerid" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_lastuser">Last User</small>
                                                <input id="nok_lastuser" name="nok_lastuser" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_lastupdate">Last Update</small>
                                                <input id="nok_lastupdate" name="nok_lastupdate" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- payer -->
                        <div class="panel panel-default" style="position: relative;" id="div_payer">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabPayer" data-toggle="collapse" data-target="#tabPayer">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>COVERAGE</strong></h5>
                                </div>
                            </div>
                            <div id="tabPayer" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="col-xs-12">
                                        <div id="jqGrid_epno_payer_c">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                <table id="jqGrid_epno_payer" class="table table-striped"></table>
                                                <div id="jqGridPager_epno_payer"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="col-xs-12" id="form_epno_payer" autocomplete="off">
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <small for="lineno_epno_payer">Payer No</small>
                                                <input id="lineno_epno_payer" name="lineno" type="text" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-2" style="padding: 0px 5px;">
                                                <small for="">MRN</small>
                                                <input id="mrn_epno_payer" name="mrn" type="text" class="form-control" data-validation="required" readonly>
                                            </div>
                                            <div class="col-md-2" style="padding: 0px 5px;">
                                                <small for="">Episode</small>
                                                <input id="episno_epno_payer" name="episno" type="text" class="form-control" data-validation="required" readonly>
                                            </div>
                                            <div class="col-md-1" style="padding: 0px 5px;">
                                                <small for="">Type</small>
                                                <input id="epistycode_epno_payer" name="epistycode" type="text" class="form-control" data-validation="required" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." id="btn_grp_edit_epno_payer">
                                                    <button type="button" class="btn btn-default" id="add_epno_payer">
                                                        <span class="fa fa-plus-square-o fa-lg"></span> Add 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_epno_payer">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="save_epno_payer">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_epno_payer">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input id="idno_epno_payer" name="idno" type="hidden">
                                        
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <small for="name">Name</small>
                                                <input id="name_epno_payer" name="name" type="text" class="form-control" data-validation="required" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-4">
                                                <small for="payercode_epno_payer">Payer Code</small>
                                                <div class='input-group'>
                                                    <input id="payercode_epno_payer" name="payercode" type="text" class="form-control uppercase" data-validation="required">
                                                    <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-xs-8">
                                                <small for="billtype_epno_payer">&nbsp;</small>
                                                <input id="payercode_desc_epno_payer" name="payercode_desc" type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-4">
                                                <small for="pyrlmtamt_epno_payer">Limit Amount</small>
                                                <input id="pyrlmtamt_epno_payer" name="pyrlmtamt" type="text" class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                <small for="pay_type_epno_payer">Fin Class</small>
                                                <input id="pay_type_epno_payer" name="pay_type" type="text" class="form-control">
                                            </div>
                                            <div class="col-xs-2">
                                                <small for="allgroup_epno_payer">All Group</small>
                                                <select name="allgroup" id="allgroup_epno_payer" class="form-control">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-2">
                                                <small for="allgroup_epno_payer">&nbsp;</small>
                                                <button type="button" id="except_epno_payer" class="btn btn-default" style="display: block;">Coverage</button>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-6">
                                                <small for="refno_epno_payer">Reference No</small>
                                                <div class='input-group'>
                                                    <input id="refno_epno_payer" name="refno" type="text" class="form-control uppercase">
                                                    <a class='input-group-addon btn btn-info' id="refno_epno_payer_btn"><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-xs-6">
                                                <small for="ourrefno_epno_payer">Our Reference</small>
                                                <input id="ourrefno_epno_payer" name="ourrefno" type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-4">
                                                <small for="computerid_epno_payer">Computer ID</small>
                                                <input id="computerid_epno_payer" name="computerid" type="text" class="form-control" readonly>
                                            </div>
                                            <div class="col-xs-4">
                                                <small for="lastuser_epno_payer">Last User</small>
                                                <input id="lastuser_epno_payer" name="lastuser" type="text" class="form-control" readonly>
                                            </div>
                                            <div class="col-xs-4">
                                                <small for="lastupdate_epno_payer">Last Update</small>
                                                <input id="lastupdate_epno_payer" name="lastupdate" type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Deposit -->
                        <div class="panel panel-default" style="position: relative;" id="div_deposit">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabDeposit" data-toggle="collapse" data-target="#tabDeposit">
                                <i class="fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <i class="fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;"></i>
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>DEPOSIT</strong></h5>
                                </div>
                            </div>
                            <div id="tabDeposit" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="col-xs-12">
                                        <div id="jqGrid_deposit_c">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                <table id="jqGrid_deposit" class="table table-striped"></table>
                                                <div id="jqGridPager_deposit"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>

<div id="mdl_epis_pay_mode" class="modal fade" role="dialog" tabindex="-1" role="dialog" data-backdrop="static" style="display: none; z-index: 130;">
    <div class="modal-dialog smallmodal">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>PAYER INFORMATION</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_debtor" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th data-column-id="debtor_type">Debtor Type</th>
                                <th data-column-id="debtor_code">Debtor Code</th>
                                <th data-column-id="debtor_name">Name</th>
                                <th data-column-id="debtor_description">Name</th>
                                <th data-column-id="debtor_debtortycode">Name</th>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <button id="btngurantor" type="button" class="btn btn-primary">Guarantor</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--button type="button" class="btn btn-success">Save</button-->
                </div>
            </div>
        </form>
    </div>
</div>

<div id="bs-guarantor" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog smallmodal">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>PR - GUARANTOR</b></p>
                </div>
                <form id="frm_guarantor">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <small for="input-title">PERSON RESPONSIBLE DETAILS</small>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-md-2">
                                            <small for="input-title">PR CODE</small>
                                            <input class="form-control" id="txt_grtr_prcode" placeholder="" type="text">
                                        </div>
                                        <div class="col-md-4">
                                            <small for="input-mrn">NAME</small>
                                            <input class="form-control" id="txt_grtr_name" placeholder="" type="text">
                                        </div>
                                        <div class="col-md-3">
                                            <small for="input-title">RELATIONSHIP</small>
                                            <!--input class="form-control" id="txt_grtr_relation" placeholder="" type="text" onclick_xguna="pop_item_select('grtr_relation');">
                                            <input type="text" id="hid_grtr_relation" /-->
                                            <select id="cmb_grtr_relation" name="cmb_grtr_relation" class="form-control form-mandatory">
                                                <option value="">- Select relationship -</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <small for="input-mrn">CREDIT LIMIT</small>
                                            <input class="form-control" id="txt_grtr_credit_limit" placeholder="" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <small for="input-title">HOME ADDRESS</small>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <input class="form-control" id="txt_grtr_hadd1" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_hadd2" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_hadd3" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_hadd4" placeholder="" type="text"><br />
                                        <div class="col-md-6">
                                            <small for="input-title">TEL</small>
                                            <input class="form-control" id="txt_grtr_htel" placeholder="" type="text">
                                        </div>
                                        <div class="col-md-6">
                                            <small for="input-title">FAX</small>
                                            <input class="form-control" id="txt_grtr_hfax" placeholder="" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small for="input-title">OFFICE ADDRESS</small>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <input class="form-control" id="txt_grtr_oadd1" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_oadd2" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_oadd3" placeholder="" type="text"><br />
                                        <input class="form-control" id="txt_grtr_oadd4" placeholder="" type="text"><br />
                                        <div class="col-md-6">
                                            <small for="input-title">TEL</small>
                                            <input class="form-control" id="txt_grtr_otel" placeholder="" type="text">
                                        </div>
                                        <div class="col-md-6">
                                            <small for="input-title">FAX</small>
                                            <input class="form-control" id="txt_grtr_ofax" placeholder="" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button id="btngurantorclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btngurantorcommit" type="button" class="btn btn-success">Commit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_bill_type" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog smallmodal">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>BILL TYPE</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_billtype" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th data-column-id="col_bill_type">Type</th>
                                <th data-column-id="col_bill_desc">Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--button type="button" class="btn btn-success">Save</button-->
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_reference" class="modal fade" role="dialog" data-backdrop="static" style="z-index: 111 !important;">
    <div class="modal-dialog mediummodal">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>GL REFERENCE</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_reference" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Payer</th>
                                <th>Name</th>
                                <th>GL Type</th>
                                <th>Staff ID</th>
                                <th>Ref No</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Our Ref No</th>
                                <th>childno</th>
                                <th>episno</th>
                                <th>medcase</th>
                                <th>mrn</th>
                                <th>relatecode</th>
                                <th>remark</th>
                                <th>startdate</th>
                                <th>enddate</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="btn_epis_view_gl" type="button" class="btn btn-info" disabled>VIEW GL</button>
                    <button id="btn_epis_detail_gl" type="button" class="btn btn-info" disabled>GL DETAIL</button>
                    <button id="btn_epis_new_gl" type="button" class="btn btn-info">NEW GL</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_glet" class="modal fade" role="dialog" data-backdrop="static" style="z-index: 112 !important;">
    <div class="modal-dialog mediummodal" style="margin-top: 1% !important;">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>GL Detail</b></p>
                </div>
                <div class="modal-body">
                    <div class="row" id="glet_row">
                        <div class="row" style="background: aliceblue; 
                            margin-bottom: 10px; 
                            border-radius: 5px; 
                            border: solid 1px #b2dbff; 
                            padding-top: 5px;">
                            <div class='col-md-12' style="padding: 0;">
                                <div class="col-md-3">
                                    <small for="input-title">MRN</small>
                                    <input class="form-control" id="glet_mrn" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">Name</small>
                                    <input class="form-control" id="glet_name" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-3">
                                    <small for="input-title">Episode</small>
                                    <input class="form-control" id="glet_episno" placeholder="" type="text" readonly>
                                </div>
                            </div>
                            <div class='col-md-12' style="padding: 0;">
                                <div class="col-md-3">
                                    <small for="input-title">Payer Code</small>
                                    <input class="form-control" id="glet_payercode" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">Payer</small>
                                    <input class="form-control" id="glet_payercode_desc" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-3">
                                    <small for="input-title">Total Limit</small>
                                    <input class="form-control" id="glet_totlimit" placeholder="" type="text" readonly>
                                </div>
                            </div>
                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                <div class="col-md-3">
                                    <small for="input-title">All Group</small>
                                    <input class="form-control" id="glet_allgroup" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-9">
                                    <small for="input-title">Ref No.</small>
                                    <input class="form-control" id="glet_refno" placeholder="" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-12' style="padding: 0 0 15px 0;">
                            <table id="jqGrid_gletdept" class="table table-striped"></table>
                            <div id="jqGridPager_gletdept"></div>
                        </div>
                        <div class='col-md-12' style="padding: 0 0 15px 0;">
                            <table id="jqGrid_gletitem" class="table table-striped"></table>
                            <div id="jqGridPager_gletitem"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_new_gl" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog mediummodal">
        <form class="form-horizontal" id="glform">
            <div class="modal-content">
                <div class="modal-header label-info" style="height: 32px; padding: 8px 30px;">
                    <b style="float: left;" id="newgl-textmrn"></b>
                    <b style="float: left; padding-left: 10px;" id="newgl-textname"></b>
                    <b style="float: right;">GURANTEE LETTER ENTRY</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="mycss">
                                <legend>Corporate Info:</legend>
                                
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-staffid">STAFF ID</small>
                                        <input class="form-control form-mandatory" id="newgl-staffid" name="newgl-staffid" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-7">
                                        <small for="newgl-corpcomp">Name</small>
                                        <input class="form-control form-mandatory" id="newgl-name" name="newgl-name" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-1">
                                        <small for="newgl-childno">CHILD NO</small>
                                        <input name="newgl-childno" id="newgl-childno" class="form-control" placeholder="" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-corpcomp">Company Code</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_corpcomp" id="txt_newgl_corpcomp" required>
                                            <input type="hidden" name="hid_newgl_corpcomp" id="hid_newgl_corpcomp" value=""/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_corpcomp" data-toggle="modal" onclick_xguna="pop_item_select('newgl_corpcomp');"><span class="fa fa-ellipsis-h"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-occupcode">OCCUPATION</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_occupcode" id="txt_newgl_occupcode">
                                            <input type="hidden" name="hid_newgl_occupcode" id="hid_newgl_occupcode" value=""/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_occupcode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_occupcode');"><span class="fa fa-ellipsis-h"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-relatecode">RELATIONSHIP</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_relatecode" id="txt_newgl_relatecode">
                                            <input type="hidden" name="hid_newgl_relatecode" id="hid_newgl_relatecode" value=""/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_relatecode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_relatecode');"><span class="fa fa-ellipsis-h"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <ul class="nav nav-tabs" id="select_gl_tab" style="margin-bottom: 10px;">
                                <li class="active"><a href="#" data-toggle="tab" id="newgl_default_tab">Multi Volume</a></li>
                                <li><a href="Multi Date" data-toggle="tab">Multi Date</a></li>
                                <li><a href="Open" data-toggle="tab">Open</a></li>
                                <li><a href="Single Use" data-toggle="tab">Single Use</a></li>
                                <!-- <li><a href="Limit Amount" data-toggle="tab">Limit Amount</a></li>
                                <li><a href="Monthly Amount" data-toggle="tab">Monthly Amount</a></li> -->
                            </ul>
                            
                            <input type="hidden" id="newgl-gltype" name="newgl-gltype">
                            
                            <div class="form-group">
                                <div class="col-md-4" id="newgl-effdate_div">
                                    <small for="newgl-effdate">EFFECTIVE DATE:</small>
                                    <input class="form-control form-mandatory" id="newgl-effdate" name="newgl-effdate" placeholder="" type="date" required>
                                </div>
                                <div class="col-md-4" id="newgl-expdate_div">
                                    <small for="newgl-expdate">EXPIRY DATE:</small>
                                    <input class="form-control form-mandatory" id="newgl-expdate" name="newgl-expdate" placeholder="" type="Date" required>
                                </div>
                                <div class="col-md-4" id="newgl-visitno_div">
                                    <small for="newgl-visitno">VISIT NO</small>
                                    <input class="form-control" id="newgl-visitno" name="newgl-visitno" placeholder="" type="text">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6">
                                    <small for="newgl-case">CASE</small>
                                    <input class="form-control form-mandatory" id="newgl-case" name="newgl-case" placeholder="" type="text" required>
                                </div>
                                <div class="col-md-6">
                                    <small for="newgl-refno">REFERENCE NO</small>
                                    <input class="form-control form-mandatory" id="newgl-refno" name="newgl-refno" placeholder="" type="text" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6">
                                    <small for="newgl-ourrefno">OUR REFERENCE</small>
                                    <input class="form-control" id="newgl-ourrefno" name="newgl-ourrefno" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <small for="newgl-remark">REMARK</small>
                                    <input class="form-control" id="newgl-remark" name="newgl-remark" placeholder="" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnglclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btnglsave" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_new_panel" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog smallmodal">
        <form class="form-horizontal" id="panelform">
            <div class="modal-content">
                <div class="modal-header label-info" style="height: 32px; padding: 8px 30px;">
                    <b style="float: left;" id="newpanel-textmrn"></b>
                    <b style="float: left; padding-left: 10px;" id="newpanel-textname"></b>
                    <b style="float: right;">PANEL INFORMATION</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <small for="newpanel-corpcomp">Panel Code</small>
                            <div class="input-group">
                                <input type="text" class="form-control form-mandatory" name="txt_newpanel_corpcomp" id="txt_newpanel_corpcomp" required>
                                <input type="hidden" name="hid_newpanel_corpcomp" id="hid_newpanel_corpcomp" value=""/>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" id="btn_newpanel_corpcomp" data-toggle="modal"><span class="fa fa-ellipsis-h"></span></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <small for="newpanel-corpcomp">STAFF NAME</small>
                            <input class="form-control form-mandatory" id="newpanel-name" name="newpanel-name" placeholder="" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <small for="newpanel-staffid">STAFF ID</small>
                            <input class="form-control form-mandatory" id="newpanel-staffid" name="newpanel-staffid" placeholder="" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <small for="newpanel-relatecode">RELATIONSHIP</small>
                            <div class="input-group">
                                <input type="text" class="form-control" name="txt_newpanel_relatecode" id="txt_newpanel_relatecode">
                                <input type="hidden" name="hid_newpanel_relatecode" id="hid_newpanel_relatecode" value=""/>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" id="btn_newpanel_relatecode" data-toggle="modal" onclick_xguna="pop_item_select('newpanel_relatecode');"><span class="fa fa-ellipsis-h"></span></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <small for="newpanel-case">CASE</small>
                            <input class="form-control" id="newpanel-case" name="newpanel-case" placeholder="" type="text">
                        </div>
                        <div class="col-md-12">
                            <small for="newpanel-refno">REFERENCE NO</small>
                            <input class="form-control form-mandatory" id="newpanel-refno" name="newpanel-refno" placeholder="" type="text" required>
                        </div>
                        <div class="col-md-12">
                            <small for="newpanel-refno">DEPARTMENT</small>
                            <input class="form-control form-mandatory" id="newpanel-deptcode" name="newpanel-deptcode" placeholder="" type="text">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnpanelclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btnpanelsave" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="dialogForm_deposit" title="Add Form" style="display: none;">
    <form style='width: 99%;' id='formdata_deposit' autocomplete="off">
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
        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
        
        <div class='col-md-6'>
            <div class='panel panel-info'>
                <div class="panel-heading">Select either Receipt or Deposit</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" name="optradio" value='receipt' dsabled>Receipt</label>
                        <label class="radio-inline"><input type="radio" name="optradio" value='deposit' checked dsabled>Deposit</label>
                    </div>
                    <div id="sysparam_dep_c" class="form-group">
                        <table id="sysparam_dep" class="table table-striped"></table>
                        <div id="sysparampg"></div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group">
                        <div class='col-md-2 minuspad-15'>
                            <label>Trantype: </label><input id="dbacthdr_trantype" name="dbacthdr_trantype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                        </div>
                        <div class='col-md-10'>
                            <label>Description: </label><input id="dbacthdr_PymtDescription" name="dbacthdr_PymtDescription" type="text" class="form-control input-sm" rdonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class='col-md-6'>
            <div class='panel panel-info'>
                <div class="panel-heading">Choose Payer</div>
                <div class="panel-body">
                    <div class="col-md-12 minuspad-15">
                        <label class="control-label" for="dbacthdr_payercode">Payer</label>
                        <div class='input-group'>
                            <input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
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
                    <label class="control-label" for="dbacthdr_debtortype">Receipt Number</label>
                    <input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>
                    
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
                        <div class="col-md-4 minuspad-15" id="dbacthdr_episno_div">
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
            <!-- <div class='clearfix'></div> -->
            <hr>
        </div>
    </form>
    <!--end formdata-->
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
                                    <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                </div>
                                
                                <label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label>
                                <div class='col-md-4'>
                                    <input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label>
                                <div class='col-md-4'>
                                    <input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm" value="0.00">
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
                            <div id="g_paymodecard_dep_c" class='col-md-4 minuspad-15'>
                                <table id="g_paymodecard_dep" class="table table-striped"></table>
                                <div id="pg_paymodecard"></div>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label>
                                    <div class='col-md-9'>
                                        <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly  data-validation="required" data-validation-error-msg="Please Enter Value" class="form-control input-sm text-uppercase">
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="g_paycard_c" class='col-md-4 minuspad-15'>
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
                                                <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                            </td>
                                            <td>
                                                <input id="paycard_description" name="paycard_description" type="text" rdonly class="form-control input-sm text-uppercase">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> -->
                            <div class='col-md-8'>
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label class="control-label" for="dbacthdr_amount">Payment</label>
                                        <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
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
                                        <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label class="control-label" for="dbacthdr_authno">Authorization No.</label>
                                        <div class=''>
                                            <input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm text-uppercase">
                                        </div>
                                    </div>
                                    
                                    <div class='col-md-6'>
                                        <label class="control-label" for="dbacthdr_expdate">Expiry Date</label>
                                        <div class=''>
                                            <input id="dbacthdr_expdate" name="dbacthdr_expdate" type="month" class="form-control input-sm">
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
                                    <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
                                </div>
                                
                                <label class="control-label col-md-2" for="dbacthdr_amount">Payment</label>
                                <div class='col-md-4'>
                                    <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
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
                                    <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="tab-debit" class="tab-pane fade">
                        <form id='f_tab-debit' autocomplete="off">
                            </br>
                            <div id="g_paymodebank_dep_c" class='col-md-4 minuspad-15'>
                                <table id="g_paymodebank_dep" class="table table-striped"></table>
                                <div id="pg_paymodebank"></div>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label>
                                    <div class='col-md-9'>
                                        <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="g_paybank_c" class='col-md-4 minuspad-15'>
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
                                                <input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
                                            </td>
                                            <td>
                                                <input id="paybank_description" name="paybank_description" type="text" rdonly class="form-control input-sm text-uppercase">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> -->
                            <div class='col-md-8'>
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label class="control-label" for="dbacthdr_entrydate">Transaction Date</label>
                                        <input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="myformgroup">
                                    <div class='col-md-6'>
                                        <label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label>
                                        <input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00">
                                    </div>
                                    
                                    <div class='col-md-6'>
                                        <label class="control-label" for="dbacthdr_amount">Payment</label>
                                        <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
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
                                        <input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
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
                            <div id="g_forex_dep_c" class='col-md-4 minuspad-15'>
                                <table id="g_forex_dep" class="table table-striped"></table>
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
                                        <input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
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
                                        <input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm" value="0.00">
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

<div id="tilldet" title="Select Till" style="display: none;">
    <form class='form-horizontal' style='width: 99%;' id='formTillDet' autocomplete="off">
        <div class="form-group">
            <label class="col-md-2 control-label" for="cashier">Cashier</label>
            <div class="col-md-10">
                <input id="cashier" name="cashier" type="text" class="form-control input-sm" readonly="readonly" value="{{Session::get('username')}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="tilldetTillcode">Till</label>
            <div class="col-md-10">
                <div class='input-group'>
                    <input id="tilldetTillcode" name="tilldetTillcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="openamt">Open Amount</label>
            <div class="col-md-2">
                <input id="openamt" name="openamt" type="text" class="form-control input-sm">
            </div>
        </div>
    </form>
</div>