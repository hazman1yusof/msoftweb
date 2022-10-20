<!-- Large modal -->
<div id="editEpisode" class="modal fade" data-backdrop="false" data-keyboard="false" role="dialog" aria-labelledby="editEpisode" aria-hidden="true" style="display: none; z-index: 110">
    <div class="modal-dialog modal-lg">
        <input type="hidden" name="rowid" id="epis_rowid">
        <input type="hidden" name="idno" id="epis_idno">
        <input type="hidden" name="mrn_episode" id="mrn_episode">
        <input type="hidden" name="episode_oper" id="episode_oper" value="add">
        <input  type="hidden" name="apptidno" id="apptidno_epis">
        <div class="modal-content">
            <div class="modal-header label-info form-horizontal" style="position: sticky;top: 0px;z-index: 3;"><form id="epis_header">
                <button type="button" class="" data-dismiss="modal" aria-label="Close" style="float: right;
                            color: white;
                            background: #d34242;
                            border-radius: 5px;">
                      <span class="glyphicon glyphicon-remove" aria-hidden="true" style="top: 3px;"></span>
                    </button>
                <div class="form-group ">
                </div>
                <div class="form-group ">
                    <div class="col-sm-1">
                        <small  for="txt_epis_no">EPISODE</small>
                        <input class="form-control input-sm" id="txt_epis_no" name="txt_epis_no" placeholder="" type="text" readonly>
                    </div>
                    <div class="col-sm-1">
                        <small for="txt_epis_type">TYPE: </small>
                        <input id="txt_epis_type" name="txt_epis_type" placeholder="" type="text" class="form-control input-sm" readonly>
                    </div>

                    <div class="col-sm-3" style="display: none;">
                        <small for="txt_epis_type">Case</small>
                        <select id="cmb_epis_case_maturity" name="cmb_epis_case_maturity" class="form-control input-sm form-mandatory" required>
                            <option value="">- Select -</option>
                            <option value="1">New Case</option>
                            <option value="2">Follow Up</option>
                        </select>
                    </div>

                    <div class="col-sm-3" style="display: none;">
                        <small for="txt_epis_type">Pregnancy</small>
                        <select id="cmb_epis_pregnancy" name="cmb_epis_pregnancy" class="form-control input-sm form-mandatory" required>  
                            <option value="">- Select -</option>
                            <option value="Pregnant">Pregnant</option>
                            <option value="Non-Pregnant" selected>Non-Pregnant</option>
                        </select>
                    </div>

                    <div class="col-sm-8">
                        <div class="col-sm-8">
                            <br>
                           NAME:  <b><big id="txt_epis_name"></big></b>
                        </div>
                        <div class="col-sm-4">
                            <br>
                           MRN:  <b><big id="txt_epis_mrn"></big></b>
                        </div>
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

                <!-- <div class="form-group ">
                    <div class="col-md-offset-4 col-md-3">
                       NAME:  <b><big id="txt_epis_name"></big></b>
                    </div>
                    <div class="col-md-3">
                       MRN:  <b><big id="txt_epis_mrn"></big></b>
                    </div>
                </div> -->
            </form></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- episode -->
                        <div class="panel panel-default" style="position: relative;"> 
                            <div class="panel-heading clearfix" id="toggle_tabEpisode" data-toggle="collapse" data-target="#tabEpisode">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong><span id="spanepistxt"></span>EPISODE</strong></h5>
                            </div> 
                            </div>

                            <div id="tabEpisode" class="panel-collapse collapse in">
                            <form id="form_episode" name="form_episode" >
                            <div class="panel-body form-horizontal">
                                <!-- Tab content begin -->
                                <div class="form-group">
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_dept">Registration Department</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" id="txt_epis_dept" required tabindex=1>
                                            <input type="hidden" id="hid_epis_dept" name="regdept" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_epis_dept" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_dept');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>                                          
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_source">Registration Source</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_epis_source" id="txt_epis_source" required tabindex=2>
                                            <input type="hidden" id="hid_epis_source" name="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_epis_source" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_source');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_case">Case </small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_epis_case" id="txt_epis_case" required tabindex=3>
                                            <input type="hidden" id="hid_epis_case" name="case_code"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_epis_case" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_case');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_doctor">Doctor</small>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="doc_nephro">Nephrologist</span>
                                            <input type="text" class="form-control form-mandatory" name="txt_admdoctor" id="txt_admdoctor" required tabindex=4>
                                            <input type="hidden" id="hid_admdoctor" name="admdoctor"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_admdoctor" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('admdoctor');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                        <div class="input-group" style="padding-top: 5px;">
                                            <span class="input-group-addon" id="doc_pic">PIC &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>
                                            <input type="text" class="form-control form-mandatory" name="txt_attndoctor" id="txt_attndoctor" required tabindex=4>
                                            <input type="hidden" id="hid_attndoctor" name="attndoctor"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_attndoctor" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('attndoctor');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    @if (request()->get('epistycode') == 'IP')
                                    <div class="col-md-offset-1 col-md-4">
                                        <small for="txt_epis_bed">ACCOMODATION : BED</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_epis_bed" id="txt_epis_bed">
                                            <input type="hidden" id="hid_epis_bed" name="admbed"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_epis_bed" data-toggle="modal" data-target="#mdl_accomodation"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <small for="txt_epis_bed">WARD</small>
                                        <input type="text" class="form-control form-mandatory" name="txt_epis_ward" id="txt_epis_ward" disabled="">
                                    </div>
                                    <div class="col-md-2">
                                        <small for="txt_epis_bed">ROOM</small>
                                        <input type="text" class="form-control form-mandatory" name="txt_epis_room" id="txt_epis_room" disabled>
                                    </div>
                                    <div class="col-md-2">
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
                                                <button type="button" class="btn btn-info" id="btn_epis_fin" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_fin');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="cmb_epis_pay_mode">Pay Mode </small>
                                        <select id="cmb_epis_pay_mode" name="pyrmode" class="form-control form-disabled"  required>
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
                                        <small for="txt_epis_payer">Payer </small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_epis_payer" id="txt_epis_payer" required>
                                            <input type="hidden" id="hid_epis_payer" name="payercode"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_epis_payer"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_bill_type">Bill Type </small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_epis_bill_type" id="txt_epis_bill_type" required>
                                            <input type="hidden" id="hid_epis_bill_type" name="bill_type"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_bill_type_info" ><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>                                        
                                <!-- <div class="form-group">
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_refno">Reference No</small>
                                        <div class="input-group">
                                            <input id="txt_epis_refno" type="text" name="txt_epis_refno" class="form-control form-mandatory">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_refno_info" ><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-10">
                                        <small for="txt_epis_our_refno">Our Reference No</small>
                                        <input id="txt_epis_our_refno" name="txt_epis_our_refno" type="text" class="form-control"  readonly>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group">
                                    <div class="col-md-offset-1 col-md-4">
                                        <small for="rad_epis_fee">Admin Fee</small>
                                        <div class="panel-body">
                                            <small class="radio-inline"><input type="radio" value="1" name="rad_epis_fee" id="rad_epis_fee_yes" checked>Yes</small>
                                            <small class="radio-inline"><input type="radio" value="0" name="rad_epis_fee" id="rad_epis_fee_no">No</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <small for="txt_epis_queno">Queue No</small>
                                        <input id="txt_epis_queno"  type="text" class="form-control">
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <div class="col-md-offset-8 col-md-4">
                                    <div class="pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="btn_save_episode" data-savelocation="{{env('APP_ENV')}}">Save changes</button>
                                    </div>
                                    </div>
                                </div>

                            </div>
                            </form>
                            </div>
                        </div>

                        <!-- HEMODIALYSIS -->
                        <div class="panel panel-default" style="position: relative;" id="div_dialysis">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabdialysis" data-toggle="collapse" data-target="#tabdialysis">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>DIALYSIS ARRIVAL</strong></h5>
                                </div> 
                            </div>

                            <div id="tabdialysis" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-xs-6">
                                    <div id="jqGrid_dialysis_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
                                            <table id="jqGrid_dialysis" class="table table-striped"></table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6" id="form_dialysis">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_doc" >
                                            <!-- <button type="button" class="btn btn-default" id="add_dialysis">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Add Arrival
                                            </button> -->
                                            <button type="button" class="btn btn-default" id="edit_dialysis">
                                                <span class="fa fa-edit fa-lg"></span> Edit Arrival
                                            </button>
                                            <button type="button" class="btn btn-default" id="del_dialysis" style="background: #cd4040;color: white;text-shadow: 0 1px 0 #cd4040;">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Delete Arrival
                                            </button>
                                            <button type="button" class="btn btn-default" data-oper='add' id="save_dialysis">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_dialysis" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
                                    </div>

                                    <div class="form-group">
                                        <input id="dialysis_idno" name="dialysis_idno" type="hidden">
                                        <div class="col-md-2">
                                            <small for="dialysis_no">Sequence No.</small>
                                            <input id="dialysis_no" name="dialysis_no" type="text" class="form-control" data-validation="required" rdonly>
                                        </div>
                                        <div class="col-md-10">
                                            <small for="dialysis_pkgcode">Dialysis Type</small>
                                            <select name="dialysis_pkgcode" id="dialysis_pkgcode" data-validation="required" class="form-control">
                                              <option value="EPO">EPO</option>
                                              <option value="MICERRA">MICERRA</option>
                                              <option value="MANUAL">MANUAL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <small for="dialysis_date">Arrival Date</small>
                                            <input id="dialysis_date" name="dialysis_date" type="date" class="form-control" data-validation="required">
                                        </div>
                                        <div class="col-md-6">
                                            <small for="dialysis_time">Arrival Time</small>
                                            <input id="dialysis_time" name="dialysis_time" type="time" class="form-control" data-validation="required">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- doctor -->
                        <!-- <div class="panel panel-default" style="position: relative;" id="div_doctor">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabDoctor" data-toggle="collapse" data-target="#tabDoctor">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>DOCTOR</strong></h5>
                                </div> 
                            </div>

                            <div id="tabDoctor" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-xs-6">
                                    <div id="jqGrid_doctor_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
                                            <table id="jqGrid_doctor" class="table table-striped"></table>
                                            <div id="jqGridPager_doctor"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6" id="form_doc">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_doc">
                                            <button type="button" class="btn btn-default" id="add_doc">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Add
                                            </button>
                                            <button type="button" class="btn btn-default" id="edit_doc">
                                                <span class="fa fa-edit fa-lg"></span> Edit
                                            </button>
                                            <button type="button" class="btn btn-default" data-oper='add' id="save_doc">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_doc" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
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
                                </div>
                            </div>
                            </div>
                        </div> -->

                        <!-- bed -->
                        @if (request()->get('epistycode') == 'IP')
                        <div class="panel panel-default" style="position: relative;" id="div_bed">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabBed" data-toggle="collapse" data-target="#tabBed">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>BED ALLOCATION</strong></h5>
                            </div> 
                            </div>

                            <div id="tabBed" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-xs-6">
                                    <div id="jqGrid_bed_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
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
                                                <span class="fa fa-plus-square-o fa-lg"></span> Assign
                                            </button>
                                            <button type="button" class="btn btn-default" data-oper='add' id="save_bed">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_bed" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
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
                                                <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
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
                                              <option value="VACANT">Vacant</option>
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
                        <!-- <div class="panel panel-default" style="position: relative;" id="div_nok">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabNok" data-toggle="collapse" data-target="#tabNok">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>NEXT OF KIN</strong></h5>
                            </div> 
                            </div>

                            <div id="tabNok" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-xs-6">
                                    <div id="jqGrid_nok_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
                                            <table id="jqGrid_nok" class="table table-striped"></table>
                                            <div id="jqGridPager_nok"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6" id="form_nok">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_nok">
                                            <button type="button" class="btn btn-default" id="add_nok">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Add
                                            </button>
                                            <button type="button" class="btn btn-default" id="edit_nok">
                                                <span class="fa fa-edit fa-lg"></span> Edit
                                            </button>
                                            <button type="button" class="btn btn-default" id="save_nok">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_nok" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
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
                                            <input id="nok_addr1" name="nok_addr1" type="text" class="form-control" data-validation="required" style="margin-bottom: 2px">
                                            <input id="nok_addr2" name="nok_addr2" type="text" class="form-control" style="margin-bottom: 2px">
                                            <input id="nok_addr3" name="nok_addr3" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <small for="nok_postcode">Postcode</small>
                                            <input id="nok_postcode" name="nok_postcode" type="text" class="form-control" data-validation="required">
                                        </div>

                                        <div class="col-md-offset-3 col-md-6">
                                            <small for="nok_telh">Tel (H)</small>
                                            <input id="nok_telh" name="nok_telh" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <small for="nok_relate">Relationship</small>
                                            <div class='input-group'>
                                                <input id="nok_relate" name="nok_relate" type="text" class="form-control uppercase">
                                                <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="col-md-6">
                                            <small for="nok_telo">Tel (O)</small>
                                            <input id="nok_telo" name="nok_telo" type="text" class="form-control" data-validation="required" rdonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <small for="nok_telhp">Tel (H/P)</small>
                                            <input id="nok_telhp" name="nok_telhp" type="text" class="form-control" data-validation="required" rdonly>
                                        </div>

                                        <div class="col-md-offset-3 col-md-3">
                                            <small for="nok_ext">Ext</small>
                                            <input id="nok_ext" name="nok_ext" type="text" class="form-control" data-validation="required" rdonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div> -->

                        <!-- payer -->
                        <!-- <div class="panel panel-default" style="position: relative;" id="div_payer">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabPayer" data-toggle="collapse" data-target="#tabPayer">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>PAYER</strong></h5>
                            </div> 
                            </div>

                            <div id="tabPayer" class="panel-collapse collapse">
                            <div class="panel-body">

                            </div>
                            </div>
                        </div> -->

                        <!-- deposit -->
                        <!-- <div class="panel panel-default" style="position: relative;" id="div_deposit">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabDeposit" data-toggle="collapse" data-target="#tabDeposit">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>DEPOSIT</strong></h5>
                            </div> 
                            </div>

                            <div id="tabDeposit" class="panel-collapse collapse">
                            <div class="panel-body">

                            </div>
                            </div>
                        </div> -->
                
                    </div>
                </div>
            </div>

            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>

<div id="mdl_epis_pay_mode" class="modal fade" role="dialog" tabindex="-1" role="dialog" data-backdrop="static" style="display: none; z-index: 130">
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
                            <th data-column-id="debtor_type" >Debtor Type</th>
                            <th data-column-id="debtor_code" >Debtor Code</th>
                            <th data-column-id="debtor_name" >Name</th>
                            <th data-column-id="debtor_billtype" >billtype</th>
                            <th data-column-id="debtor_billtype" >billtype desc</th>
                        </tr>
                        </thead>

                    </table>
                    <br />
                    <!-- <button id="btngurantor" type="button" class="btn btn-primary" >Guarantor</button> -->
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
                    <p align="center"><b>PR - GUARANTOR</b> </p>
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
                                            <!--input class="form-control" id="txt_grtr_relation" placeholder="" type="text" onclick="Global.pop_item_select('grtr_relation');">
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
                    <button id="btngurantorcommit"  type="button" class="btn btn-success">Commit</button>
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
                            <th data-column-id="col_bill_type" >Type</th>
                            <th data-column-id="col_bill_desc" >Description</th>
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
    <div class="modal-dialog smallmodal">
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
                            <th data-column-id="mrn" >Debtor Code</th>
                            <th data-column-id="mrn" >Name</th>
                            <th data-column-id="mrn" >GL Type</th>
                            <th data-column-id="mrn" >Staff ID</th>
                            <th data-column-id="mrn" >Ref No</th>
                            <th data-column-id="mrn" >Our Ref No</th>
                            <th data-column-id="mrn" >childno</th>
                            <th data-column-id="mrn" >episno</th>
                            <th data-column-id="mrn" >medcase</th>
                            <th data-column-id="mrn" >mrn</th>
                            <th data-column-id="mrn" >relatecode</th>
                            <th data-column-id="mrn" >remark</th>
                            <th data-column-id="mrn" >startdate</th>
                            <th data-column-id="mrn" >enddate</th>
                        </tr>
                        </thead>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" disabled>VIEW GL</button>
                    <button type="button" class="btn btn-info" disabled>GL DETAIL</button>
                    <button id="btn_epis_new_gl" type="button" class="btn btn-info" >NEW GL</button>
                    <button type="button" class="btn btn-info" disabled>OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_new_gl" class="modal fade" tabindex="-1" role="dialog" >
    <div class="modal-dialog smallmodal">
        <form class="form-horizontal" id="glform">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <p align="center"><b>GURANTEE LETTER ENTRY</b></p>
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
                                    <div class="col-md-7 col-md-offset-1">
                                        <small for="newgl-corpcomp">Name</small>
                                        <input class="form-control form-mandatory" id="newgl-name" name="newgl-name" placeholder="" type="text" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-corpcomp">Company Code</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_corpcomp" id="txt_newgl_corpcomp">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_corpcomp" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('newgl_corpcomp');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-md-offset-1">
                                        <small for="newgl-corpcomp">Company Name</small>
                                        <input type="text" class="form-control form-mandatory" name="hid_newgl_corpcomp" id="hid_newgl_corpcomp" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-occupcode">OCCUPATION</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_occupcode" id="txt_newgl_occupcode">
                                            <input type="hidden" name="hid_newgl_occupcode" id="hid_newgl_occupcode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_occupcode" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('newgl_occupcode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="col-md-4 col-md-offset-1">
                                        <small for="newgl-relatecode">RELATIONSHIP</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_relatecode" id="txt_newgl_relatecode">
                                            <input type="hidden" name="hid_newgl_relatecode" id="hid_newgl_relatecode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_relatecode" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('newgl_relatecode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-md-offset-1">
                                        <small for="newgl-childno">CHILD NO</small>
                                        <input name="newgl-childno" id="newgl-childno" class="form-control form-mandatory" placeholder="" type="text" required>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mycss">
                            <legend>GL Info:</legend>
                                <ul class="nav nav-tabs" id="select_gl_tab" style="margin-bottom: 10px;">
                                    <li class="active"><a href="#" data-toggle="tab" id="newgl_default_tab">Multi Volume</a></li>
                                    <li><a href="#" data-toggle="tab">Multi Date</a></li>
                                    <li><a href="#" data-toggle="tab">Open</a></li>
                                    <li><a href="#" data-toggle="tab">Single Use</a></li>
                                    <li><a href="#" data-toggle="tab">Limit Amount</a></li>
                                    <li><a href="#" data-toggle="tab">Monthly Amount</a></li>
                                </ul>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-gltype">GL TYPE</small>
                                        <select id="newgl-gltype" name="newgl-gltype" class="form-control form-mandatory" readonly required>
                                            <option value="Multi Volume">Multi Volume</option>
                                            <option value="Multi Date">Multi Date</option>
                                            <option value="Open">Open</option>
                                            <option value="Single Use">Single Use</option>
                                            <option value="Limit Amount">Limit Amount</option>
                                            <option value="Monthly Amount">Monthly Amount</option>
                                            <option value="Single Use">Single Use</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-effdate">EFFECTIVE DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl-effdate" name="newgl-effdate" placeholder="" type="date" required>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-expdate">EXPIRY DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl-expdate" name="newgl-expdate" placeholder="" type="Date" required>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-visitno">VISIT NO</small>
                                        <input class="form-control" id="newgl-visitno" name="newgl-visitno" placeholder="" type="text" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-case">CASE</small>
                                        <input class="form-control form-mandatory" id="newgl-case" name="newgl-case" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-refno">REFERENCE NO</small>
                                        <input class="form-control" id="newgl-refno" name="newgl-refno" placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-ourrefno">OUR REFERENCE</small>
                                        <input class="form-control" id="newgl-ourrefno" name="newgl-ourrefno" placeholder="" type="text">
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-remark">REMARK</small>
                                        <input class="form-control" id="newgl-remark" name="newgl-remark" placeholder="" type="text">
                                    </div>
                                </div>
                            </fieldset>

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
