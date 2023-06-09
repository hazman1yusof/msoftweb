
<div class="panel panel-default" style="position: relative;">
	<div class="panel-heading clearfix collapsed position" id="toggle_episodelist" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_episodelist"></span></b><br>
		MRN: <span id="mrn_show_episodelist"></span>
        SEX: <span id="sex_show_episodelist"></span>
        DOB: <span id="dob_show_episodelist"></span>
        AGE: <span id="age_show_episodelist"></span>
        RACE: <span id="race_show_episodelist"></span>
        RELIGION: <span id="religion_show_episodelist"></span><br>
        OCCUPATION: <span id="occupation_show_episodelist"></span>
        CITIZENSHIP: <span id="citizenship_show_episodelist"></span>
        AREA: <span id="area_show_episodelist"></span> 

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 60px; top: 15px;">
			<h5><b>Episode List</b></h5>
		</div>
	</div>
	<div id="episodelist_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv paneldiv_lightblue" >
			<div class='col-md-12' style="padding:0 0 15px 0" id="jqGrid_episodelist_c">
				<nav class="navbar navbar-default" style="border-radius: 100px;">
				  <div class="container-fluid my_cf" >
				    <div class="navbar-header">
					<ul class="nav navbar-nav myhover">    	
			        	<li class=""><a class="my_a a_bro" id="my_a_chgs">Charges</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_enot">Episode Notes</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_payr">Payer</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_nokn">Next Of Kin</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_gtlr">Guarantee Letter</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_panl">Panel</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_form">Forms</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_barc">Bar Code</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_enqu">Enquiry</a></li>
			        	<li class=""><a class="my_a" id="my_a_bdal">Bed Allocate</a></li>
			       	</ul>
				    </div>
				  </div>
				</nav>

				<div class="col-md-6" style="padding: 15px 0px 5px 0px; float: right;" id="eplist_searchform">
                    <input name="Stext" class="form-control" placeholder="Search Episode.. " type="text" style="max-width:200px;float: right;">
                    <select name="Scol" class="form-control" style="float: right;max-width: 200px;margin-right: 10px;"></select>
                </div>

				<div class="col-md-12" style="padding: 5px 0px;">
	                <table id="jqGrid_episodelist" class="table table-striped"></table>
	                <div id="jqGrid_episodelistPager"></div>
                </div>
			</div>
		</div>
	</div>	
</div>

<div id="mdl_new_gl" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog mediummodal">
        <form class="form-horizontal" id="glform">
            <div class="modal-content">
                <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                    <b style="float: left;" id="newgl-textmrn"></b>
                    <b style="float: left;padding-left: 10px;" id="newgl-textname"></b>
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
                                        <input class="form-control form-mandatory" id="newgl-staffid" name="newgl-staffid" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-7">
                                        <small for="newgl-corpcomp">Name</small>
                                        <input class="form-control form-mandatory" id="newgl-name" name="newgl-name" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <small for="newgl-childno">CHILD NO</small>
                                        <input name="newgl-childno" id="newgl-childno" class="form-control" placeholder="" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-corpcomp">Company Code</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_corpcomp" id="txt_newgl_corpcomp" required readonly>
                                            <input type="hidden" name="hid_newgl_corpcomp" id="hid_newgl_corpcomp" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_corpcomp" data-toggle="modal" onclick_xguna="pop_item_select('newgl_corpcomp');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <small for="newgl-occupcode">OCCUPATION</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_occupcode" id="txt_newgl_occupcode" readonly>
                                            <input type="hidden" name="hid_newgl_occupcode" id="hid_newgl_occupcode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_occupcode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_occupcode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-relatecode">RELATIONSHIP</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_relatecode" id="txt_newgl_relatecode" readonly>
                                            <input type="hidden" name="hid_newgl_relatecode" id="hid_newgl_relatecode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_relatecode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_relatecode');"><span class="fa fa-ellipsis-h"></span> </button>
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
                                        <input class="form-control form-mandatory" id="newgl-effdate" name="newgl-effdate" placeholder="" type="date" required readonly>
                                    </div>
                                    <div class="col-md-4" id="newgl-expdate_div">
                                        <small for="newgl-expdate">EXPIRY DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl-expdate" name="newgl-expdate" placeholder="" type="date" required readonly>
                                    </div>
                                    <div class="col-md-4" id="newgl-visitno_div">
                                        <small for="newgl-visitno">VISIT NO</small>
                                        <input class="form-control" id="newgl-visitno" name="newgl-visitno" placeholder="" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-case">CASE</small>
                                        <input class="form-control form-mandatory" id="newgl-case" name="newgl-case" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-refno">REFERENCE NO</small>
                                        <input class="form-control form-mandatory" id="newgl-refno" name="newgl-refno" placeholder="" type="text" required readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-ourrefno">OUR REFERENCE</small>
                                        <input class="form-control" id="newgl-ourrefno" name="newgl-ourrefno" placeholder="" type="text" readonly >
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-remark">REMARK</small>
                                        <input class="form-control" id="newgl-remark" name="newgl-remark" placeholder="" type="text" readonly>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnglclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_ep_note" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog mediummodal">
		<div class="modal-content">
			<div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                <b style="float: right;">Episode Notes</b>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a href="#epno_regs" role="tab" data-toggle="tab">Registration</a>
                        </li>
                        <li role="presentation">
                            <a href="#epno_doci" role="tab" data-toggle="tab">Doctor Info</a>
                        </li>
                        <li role="presentation">
                            <a href="#epno_disc" role="tab" data-toggle="tab">Discharge</a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#epno_medc" role="tab" data-toggle="tab">Medical Cert</a>
                        </li>
                        <li role="presentation">
                            <a href="#epno_edoc" role="tab" data-toggle="tab">E-Document</a>
                        </li>
                      </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane" id="epno_regs"></div>
                        <div role="tabpanel" class="tab-pane" id="epno_doci"></div>
                        <div role="tabpanel" class="tab-pane" id="epno_disc"></div>
                        <div role="tabpanel" class="tab-pane active" id="epno_medc">
                            @include('hisdb.pat_enq.epno_medc')
                        </div>
                        <div role="tabpanel" class="tab-pane" id="epno_edoc"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="epno_close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
		</div>
	</div>
     
</div>