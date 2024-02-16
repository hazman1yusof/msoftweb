<div class="row">
	<div class="panel-body form-horizontal">
        <div class="col-md-6">
            <div id="jqGrid_nok_pat_c">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid_nok_pat" class="table table-striped"></table>
                    <div id="jqGridPager_nok_pat"></div>
                </div>
            </div>
        </div>
        <form class="col-md-6" id="form_nok_pat" autocomplete="off">
            <div class="form-group">
                <div class="col-md-12">
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_nok_pat">
                    <button type="button" class="btn btn-default" id="add_nok_pat">
                        <span class="fa fa-plus-square-o fa-lg"></span> Add
                    </button>
                    <button type="button" class="btn btn-default" id="edit_nok_pat">
                        <span class="fa fa-edit fa-lg"></span> Edit
                    </button>
                    <button type="button" class="btn btn-default" id="save_nok_pat">
                        <span class="fa fa-save fa-lg"></span> Save
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_nok_pat" >
                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                    </button>
                </div></div>
            </div>

            <input id="nok_idno_pat" name="nok_idno_pat" type="hidden">

            <div class="form-group">
                <div class="col-md-12">
                    <small for="nok_name_pat">Name</small>
                    <input id="nok_name_pat" name="nok_name_pat" type="text" class="form-control" data-validation="required">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <small for="nok_addr1_pat">Address</small>
                    <input id="nok_addr1_pat" name="nok_addr1_pat" type="text" class="form-control" style="margin-bottom: 2px">
                    <input id="nok_addr2_pat" name="nok_addr2_pat" type="text" class="form-control" style="margin-bottom: 2px">
                    <input id="nok_addr3_pat" name="nok_addr3_pat" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <small for="nok_postcode_pat">Postcode</small>
                    <input id="nok_postcode_pat" name="nok_postcode_pat" type="text" class="form-control">
                </div>

                <div class="col-md-4">
                    <small for="nok_telh_pat">Tel (H)</small>
                    <input id="nok_telh_pat" name="nok_telh_pat" type="text" class="form-control">
                </div>

                <div class="col-md-4">
                    <small for="nok_telo_pat">Tel (O)</small>
                    <input id="nok_telo_pat" name="nok_telo_pat" type="text" class="form-control" rdonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <small for="nok_relate_pat">Relationship</small>
                    <div class='input-group'>
                        <input id="nok_relate_pat" name="nok_relate_pat" type="text" class="form-control uppercase" data-validation="required">
                        <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                    </div>
                    <span class="help-block"></span>
                </div>
                <div class="col-md-6">
                    <small for="nok_telhp_pat">Tel (H/P)</small>
                    <input id="nok_telhp_pat" name="nok_telhp_pat" type="text" class="form-control" rdonly>
                </div>
                <div class="col-md-2">
                    <small for="nok_ext_pat">Ext</small>
                    <input id="nok_ext_pat" name="nok_ext_pat" type="text" class="form-control"  rdonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <small for="nok_computerid">Computer ID</small>
                    <input id="nok_computerid" name="nok_computerid" type="text" class="form-control"  rdonly>
                </div>
                <div class="col-md-4">
                    <small for="nok_lastuser">Last User</small>
                    <input id="nok_lastuser" name="nok_lastuser" type="text" class="form-control"  rdonly>
                </div>
                <div class="col-md-4">
                    <small for="nok_lastupdate">Last Update</small>
                    <input id="nok_lastupdate" name="nok_lastupdate" type="text" class="form-control"  rdonly>
                </div>
            </div>

        </form>
    </div>
</div>