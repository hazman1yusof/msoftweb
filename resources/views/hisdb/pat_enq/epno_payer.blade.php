<div class="row">
	<div class="panel-body form-horizontal">
        <div class="col-xs-12">
            <div id="jqGrid_epno_payer_c">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid_epno_payer" class="table table-striped"></table>
                    <div id="jqGridPager_epno_payer"></div>
                </div>
            </div>
        </div>
        <form class="col-xs-12" id="form_epno_payer" autocomplete="off">
            <div class="form-group">
                <div class="col-md-2">
                    <small for="">MRN</small>
                    <input id="mrn_epno_payer" name="mrn" type="text" class="form-control" data-validation="required" readonly>
                </div>
                <div class="col-md-2">
                    <small for="">Episode</small>
                    <input id="episno_epno_payer" name="episno" type="text" class="form-control" data-validation="required" readonly>
                </div>
                <div class="col-md-2">
                    <small for="">Type</small>
                    <input id="epistycode_epno_payer" name="epistycode" type="text" class="form-control" data-validation="required" readonly>
                </div>
                <div class="col-md-6">
                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_epno_payer">
                        <button type="button" class="btn btn-default" id="add_epno_payer">
                            <span class="fa fa-plus-square-o fa-lg"></span> Add
                        </button>
                        <button type="button" class="btn btn-default" id="edit_epno_payer">
                            <span class="fa fa-edit fa-lg"></span> Edit
                        </button>
                        <button type="button" class="btn btn-default" id="save_epno_payer">
                            <span class="fa fa-save fa-lg"></span> Save
                        </button>
                        <button type="button" class="btn btn-default" id="cancel_epno_payer" >
                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                        </button>
                    </div>
                </div>
            </div>

            <input id="idno_epno_payer" name="idno" type="hidden">

            <div class="form-group">
                <div class="col-md-12">
                    <small for="name">Name</small>
                    <input id="name_epno_payer" name="name" type="text" class="form-control" data-validation="required" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <small for="payercode_epno_payer">Payer Code</small>
                    <div class='input-group'>
                        <input id="payercode_epno_payer" name="payercode" type="text" class="form-control uppercase" required>
                        <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                    </div>
                    <span class="help-block"></span>
                </div>

                <div class="col-md-8">
                    <small for="billtype_epno_payer">&nbsp;</small>
                    <input id="payercode_desc_epno_payer" name="payercode_desc" type="text" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <small for="lineno_epno_payer">Payer No</small>
                    <input id="lineno_epno_payer" name="lineno" type="text" class="form-control" readonly>
                </div>

                <div class="col-md-8">
                    <small for="pay_type_epno_payer">Fin Class</small>
                    <input id="pay_type_epno_payer" name="pay_type" type="text" class="form-control">
                </div>
            </div>

            <!-- <div class="form-group">
                <div class="col-md-4">
                    <small for="billtype_epno_payer">Bill Type</small>
                    <input id="billtype_epno_payer" name="billtype" type="text" class="form-control">
                </div>

                <div class="col-md-8">
                    <small for="billtype_epno_payer">&nbsp;</small>
                    <input id="billtype_desc_epno_payer" name="billtype_desc" type="text" class="form-control" readonly>
                </div>
            </div> -->

            <div class="form-group">
                <div class="col-md-4">
                    <small for="pyrlmtamt_epno_payer">Limit Amount</small>
                    <input id="pyrlmtamt_epno_payer" name="pyrlmtamt" type="text" class="form-control">
                </div>

                <div class="col-md-2 col-md-4">
                    <small for="allgroup_epno_payer">All Group</small>
                    <select name="allgroup" id="allgroup_epno_payer" class="form-control">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <small for="allgroup_epno_payer">&nbsp;</small>
                    <button type="button" id="except_epno_payer" class="btn btn-default" style="display: block;">Exception</button>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    <small for="refno_epno_payer">Reference No</small>
                    <div class='input-group'>
                        <input id="refno_epno_payer" name="refno" type="text" class="form-control uppercase">
                        <a class='input-group-addon btn btn-info' id="refno_epno_payer_btn"><span class='fa fa-ellipsis-h'></span></a>
                    </div>
                    <span class="help-block"></span>
                </div>
                <div class="col-md-6">
                    <small for="ourrefno_epno_payer">Our Reference</small>
                    <input id="ourrefno_epno_payer" name="ourrefno" type="text" class="form-control" readonly>
                </div>
            </div>

        </form>
    </div>
</div>