<!-- Large modal -->
<div id="editEpisode" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editEpisode" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-warning">
                    <!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
                    <div class="form-group has-error">
                        <div class="col-sm-2">
                            <small  for="txt_epis_no">EPISODE NO:</small>
                            <input class="form-control " id="txt_epis_no" placeholder="" type="text" readonly>
                            <!--                                    <small class="help-block text-center">REGISTRATION MRN</small>-->
                        </div>
                        <div class="col-sm-6">
                            <small for="txt_epis_type">TYPE: </small>
                            <div class="input-group">
                                <input id="txt_epis_type" placeholder="" type="text" class="form-control" style="width:50px;" readonly>
                                <span class="input-group-addon" style="background-color:transparent; border-color: transparent" style="width:40px;">&mdash;</span>
                                <select id="cmb_epis_case_maturity" name="cmb_epis_case_maturity" class="form-control form-mandatory" style="width:300px;">
                                    <option value="">- Select -</option>
                                    <option value="1">New Case</option>
                                    <option value="2">Follow Up</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <small for="txt_epis_date">DATE: </small>
                            <input class="form-control" id="txt_epis_date" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-2">
                            <small for="txt_epis_time">TIME: </small>
                            <input class="form-control" id="txt_epis_time" placeholder="" type="text" readonly>
                        </div>


                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabs-left" role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tabEpisode" role="tab" data-toggle="tab" aria-expanded="true">Episode</a></li>
                                    <li role="presentation" class=""><a href="#tabDoctor" role="tab" data-toggle="tab" aria-expanded="false">Doctor</a></li>
                                    <li role="presentation" class=""><a href="#tabBed" role="tab" data-toggle="tab" aria-expanded="false">Bed Allocation</a></li>
                                    <li role="presentation" class=""><a href="#tabNext" role="tab" data-toggle="tab" aria-expanded="false">Next of Kin</a></li>
                                    <li role="presentation" class=""><a href="#tabPayer" role="tab" data-toggle="tab" aria-expanded="false">Payer</a></li>
                                    <li role="presentation" class=""><a href="#tabDeposit" role="tab" data-toggle="tab" aria-expanded="false">Deposit</a></li>
                                </ul>

                                <div class="tab-content">

                                    <div role="tabpanel" class="tab-pane fade active in" id="tabEpisode">
                                        <!-- Tab content begin -->
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <small for="txt_epis_dept">Registration Department</small>
                                                <!--select id="cmb_epis_dept" name="cmb_epis_dept" class="form-control form-mandatory">
                                                    <option value="">- Select Department -</option>
                                                </select-->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_dept">
                                                    <input type="hidden" id="hid_epis_dept" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_dept" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_dept');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>                                          
                                            <div class="col-md-10">
                                                <small for="txt_epis_source">Registration Source</small>
                                                <!--select id="cmb_epis_source" name="cmb_epis_source" class="form-control form-mandatory">
                                                    <option value="">- Select Source -</option>
                                                </select-->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_source">
                                                    <input type="hidden" id="hid_epis_source" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_source" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_source');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="txt_epis_case">Case </small>
                                                <!--select id="cmb_epis_case" name="cmb_epis_case" class="form-control form-mandatory">
                                                    <option value="">- Select Case -</option>
                                                </select-->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_case">
                                                    <input type="hidden" id="hid_epis_case" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_case" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_case');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="txt_epis_doctor">Doctor</small>
                                                <!--select id="cmb_epis_doctor" name="cmb_epis_doctor" class="form-control form-mandatory">
                                                    <option value="">- Select Doctor -</option>
                                                </select-->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_doctor">
                                                    <input type="hidden" id="hid_epis_doctor" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_doctor" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_doctor');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="txt_epis_fin">Financial Class</small>
                                                <!--select id="cmb_epis_fin" name="cmb_epis_fin" class="form-control form-mandatory">
                                                    <option value="">- Select Class -</option>
                                                </select-->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_fin" name="txt_epis_fin">
                                                    <input type="hidden" id="hid_epis_fin" name="hid_epis_fin" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_fin" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('epis_fin');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="cmb_epis_pay_mode">Pay Mode </small>
                                                <select id="cmb_epis_pay_mode" name="cmb_epis_pay_mode" class="form-control form-disabled">
                                                    <option value="">- Select Pay Mode -</option>
                                                    <option value='CASH'>Cash</option>
                                                    <option value='CARD'>Card</option>
                                                    <option value='WAITING GL'>Waiting GL</option>
                                                    <option value='OPEN CARD'>Open Card</option>
                                                    <option value='PWD'>Consultant Guarantee (PWD)</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <small for="txt_epis_payer">Payer </small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_payer">
                                                    <input type="hidden" id="hid_epis_payer" value="PURI" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_epis_payer"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="txt_epis_bill_type">Bill Type </small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" id="txt_epis_bill_type">
                                                    <input type="hidden" id="hid_epis_bill_type" value="STD" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_bill_type_info" ><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="form-group">
                                            <div class="col-md-10">
                                                <small for="txt_epis_refno">Reference No</small>
                                                <div class="input-group">
                                                    <input id="txt_epis_refno" type="text" class="form-control form-mandatory" value="REF-123456789">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_refno_info" ><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <small for="txt_epis_our_refno">Our Reference No</small>
                                                <div class="input-group">
                                                    <input id="txt_epis_our_refno" type="text" class="form-control" value="OURREF-123456789" readonly>
                                                    <!--span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_our_refno_info" ><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">                                        
                                            <div class="col-md-4">
                                                <small for="rad_epis_pregnancy">Status</small>
                                                <div class="panel panel-default">
                                                    <div class="panel-body checkbox">
                                                        <small class="checkbox checkbox-inline"><input type="radio" value="1" name="rad_epis_pregnancy" id="rad_epis_pregnancy_yes">Pregnant</small>
                                                        <small class="checkbox checkbox-inline"><input type="radio" value="0" name="rad_epis_pregnancy" id="rad_epis_pregnancy_no">Non Pregnant</small>
                                                    </div>
                                                </div>
                                            </div>                                     
                                            <div class="col-md-4">
                                                <small for="rad_epis_fee">Admin Fee</small>
                                                <div class="panel panel-default">
                                                    <div class="panel-body checkbox">
                                                        <small class="radio radio-inline"><input type="radio" value="1" name="rad_epis_fee" id="rad_epis_fee_yes" checked>Yes</small>
                                                        <small class="radio radio-inline"><input type="radio" value="0" name="rad_epis_fee" id="rad_epis_fee_no">No</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <small for="txt_epis_queno">Queue No</small>
                                                <input id="txt_epis_queno"  type="text" class="form-control">
                                            </div>
                                        </div>                                       

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabDoctor">
                                        <!-- Tab content begin -->
                                        <p>Development mode</p>
                                        <!-- Tab content end -->
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabBed">
                                        <!-- Tab content begin -->
                                        <p>Development mode</p>
                                        <!-- Tab content end -->
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabNext">
                                        <!-- Tab content begin -->
                                        <p>Development mode</p>
                                        <!-- Tab content end -->
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabPayer">
                                        <!-- Tab content begin -->
                                        <p>Development mode</p>
                                        <!-- Tab content end -->
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabDeposit">
                                        <!-- Tab content begin -->
                                        <p>Development mode</p>
                                        <!-- Tab content end -->
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_save_episode">Save changes</button>
                </div>
            </div>
        </form>


    </div>
</div>

<div id="mdl_new_gl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <p align="center"><b>GURANTEE LETTER ENTRY</b></p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <small for="input-title">STAFF ID</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">

                                </div>
                                <div class="col-md-8">
                                    <small for="input-mrn">COMPANY</small>
                                    <select id="input-ictype" name="input-ictype" class="form-control">
                                        <option value="">- Select Company -</option>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <small for="input-title">OCCUPATION</small>
                                    <select id="occupcode" name="id_type" class="form-control has-error">
                                        <option value="">- Select Occupation -</option>
                                    </select>

                                </div>
                                <div class="col-md-4">
                                    <small for="input-title">RELATIONSHIP</small>
                                    <select id="input-ictype" name="input-ictype" class="form-control">
                                        <option value="">- Select Relationship -</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <small for="input-mrn">CHILD NO</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <small for="input-title">NAME</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">GL TYPE</small>
                                    <select id="input-ictype" name="input-ictype" class="form-control">
                                        <option value="">- Select GL Type -</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <small for="input-title">EFFECTIVE DATE:</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                                <div class="col-md-4">
                                    <small for="input-mrn">EXPIRY DATE:</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text" disabled>
                                </div>
                                <div class="col-md-4">
                                    <small for="input-mrn">VISIT NO</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <small for="input-title">CASE</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">REFERENCE NO</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <small for="input-title">OUR REFERENCE</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">REMARK</small>
                                    <input class="form-control" id="input-mrn" placeholder="" type="text">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnglclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btnglsave" type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                </div>
            </div>
        </form>


    </div>
</div>

<div id="bs-guarantor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-primary">
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

<div id="mdl_epis_pay_mode_1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-success">
                    <p align="center"><b>PAYER INFORMATION</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_debtor1" class="table table-striped" width="100%">
                        <thead>
                        <tr>
                            <th data-column-id="debtor_type" >Debtor Type</th>
                            <th data-column-id="debtor_code" >Debtor Code</th>
                            <th data-column-id="debtor_name" >Debtor Name</th>
                        </tr>
                        </thead>

                    </table>
                    <br />
                    <button id="btngurantor" type="button" class="btn btn-primary" >Guarantor</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--button type="button" class="btn btn-success">Save</button-->
                </div>
            </div>
        </form>


    </div>
</div>
<div id="mdl_epis_pay_mode_2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <p align="center"><b>Payer Information</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_debtor2" class="table table-striped" width="100%">
                        <thead>
                        <tr>
                            <th data-column-id="debtor_code" >Debtor Code</th>
                            <th data-column-id="debtor_name" >Debtor Name</th>
                            <th data-column-id="description" >Description</th>
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

<div id="mdl_bill_type" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-success">
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


<div id="mdl_reference" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <p align="center"><b>GL REFERENCE</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_reference" class="table table-striped" width="100%">
                        <thead>
                        <tr>
                            <th data-column-id="mrn" >Staff ID</th>
                            <th data-column-id="mrn" >Debtor Code</th>
                            <th data-column-id="mrn" >Name</th>
                            <th data-column-id="mrn" >Our Reference</th>
                            <th data-column-id="mrn" >Reference No</th>
                        </tr>
                        </thead>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" disabled>VIEW GL</button>
                    <button type="button" class="btn btn-warning" disabled>GL DETAIL</button>
                    <button id="btn_epis_new_gl" type="button" class="btn btn-warning" >NEW GL</button>
                    <button type="button" class="btn btn-warning" disabled>OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</div>