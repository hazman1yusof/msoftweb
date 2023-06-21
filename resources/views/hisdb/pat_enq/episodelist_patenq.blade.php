
<div class="panel panel-default" style="position: relative;">
	<div class="panel-heading clearfix collapsed position" id="toggle_episodelist" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span class="popspan" id="name_show_episodelist"></span></b><br>
		MRN: <span class="popspan" id="mrn_show_episodelist"></span>
        SEX: <span class="popspan" id="sex_show_episodelist"></span>
        DOB: <span class="popspan" id="dob_show_episodelist"></span>
        AGE: <span class="popspan" id="age_show_episodelist"></span>
        RACE: <span class="popspan" id="race_show_episodelist"></span>
        RELIGION: <span class="popspan" id="religion_show_episodelist"></span><br>
        OCCUPATION: <span class="popspan" id="occupation_show_episodelist"></span>
        CITIZENSHIP: <span class="popspan" id="citizenship_show_episodelist"></span>
        AREA: <span class="popspan" id="area_show_episodelist"></span> 

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 60px; top: 15px;">
			<h5><b>Episode</b></h5>
		</div>
	</div>
	<div id="episodelist_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv paneldiv_lightblue" >
			<div class='col-md-12' style="padding:0 0 15px 0" id="jqGrid_episodelist_c">
				<nav class="navbar navbar-default" style="border-radius: 100px;">
				  <div class="container-fluid my_cf" >
				    <div class="navbar-header">
					<ul class="nav navbar-nav myhover">    	
                        <li class=""><a class="my_a a_bro" id="my_a_dtin">Doctor Info</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_chgs">Charges</a></li>
			        	<!-- <li class=""><a class="my_a a_bro" id="my_a_enot">Episode Notes</a></li> -->
			        	<li class=""><a class="my_a a_bro" id="my_a_payr">Payer</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_nokn">Next Of Kin</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_gtlr">Guarantee Letter</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_panl">Panel</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_form">Forms</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_barc">Bar Code</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_enqu">Enquiry</a></li>
			        	<li class=""><a class="my_a a_bro" id="my_a_bdal">Bed Allocate</a></li>
                        <li class=""><a class="my_a" id="my_a_mc">MC</a></li>
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

<div id="mdl_new_gl_epno" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog mediummodal">
        <button class="glyphicon glyphicon-remove close_icon" aria-hidden="true" type="button" data-dismiss="modal"></button>
        <form class="form-horizontal" id="glform">
            <div class="modal-content">
                <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                    <b style="float: left;" id="newgl_epno-textmrn"></b>
                    <b style="float: left;padding-left: 10px;" id="newgl_epno-textname"></b>
                    <b style="float: right;">GURANTEE LETTER ENTRY</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="mycss">
                            <legend>Corporate Info:</legend>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl_epno-staffid">STAFF ID</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-staffid" name="newgl_epno-staffid" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-7">
                                        <small for="newgl_epno-corpcomp">Name</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-name" name="newgl_epno-name" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <small for="newgl_epno-childno">CHILD NO</small>
                                        <input name="newgl_epno-childno" id="newgl_epno-childno" class="form-control" placeholder="" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl_epno-corpcomp">Company Code</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_epno_corpcomp" id="txt_newgl_epno_corpcomp" required readonly>
                                            <input type="hidden" name="hid_newgl_epno_corpcomp" id="hid_newgl_epno_corpcomp" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_epno_corpcomp" data-toggle="modal" onclick_xguna="pop_item_select('newgl_epno_corpcomp');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <small for="newgl_epno-occupcode">OCCUPATION</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_epno_occupcode" id="txt_newgl_epno_occupcode" readonly>
                                            <input type="hidden" name="hid_newgl_epno_occupcode" id="hid_newgl_epno_occupcode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_epno_occupcode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_epno_occupcode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl_epno-relatecode">RELATIONSHIP</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_epno_relatecode" id="txt_newgl_epno_relatecode" readonly>
                                            <input type="hidden" name="hid_newgl_epno_relatecode" id="hid_newgl_epno_relatecode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_epno_relatecode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_epno_relatecode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                                <ul class="nav nav-tabs" id="select_gl_tab" style="margin-bottom: 10px;">
                                    <li class="active"><a href="#" data-toggle="tab" id="newgl_epno_default_tab">Multi Volume</a></li>
                                    <li><a href="Multi Date" data-toggle="tab">Multi Date</a></li>
                                    <li><a href="Open" data-toggle="tab">Open</a></li>
                                    <li><a href="Single Use" data-toggle="tab">Single Use</a></li>
                                    <!-- <li><a href="Limit Amount" data-toggle="tab">Limit Amount</a></li>
                                    <li><a href="Monthly Amount" data-toggle="tab">Monthly Amount</a></li> -->
                                </ul>
                                <input type="hidden" id="newgl_epno-gltype" name="newgl_epno-gltype">
                                <div class="form-group">
                                    <div class="col-md-4" id="newgl_epno-effdate_div">
                                        <small for="newgl_epno-effdate">EFFECTIVE DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-effdate" name="newgl_epno-effdate" placeholder="" type="date" required readonly>
                                    </div>
                                    <div class="col-md-4" id="newgl_epno-expdate_div">
                                        <small for="newgl_epno-expdate">EXPIRY DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-expdate" name="newgl_epno-expdate" placeholder="" type="date" required readonly>
                                    </div>
                                    <div class="col-md-4" id="newgl_epno-visitno_div">
                                        <small for="newgl_epno-visitno">VISIT NO</small>
                                        <input class="form-control" id="newgl_epno-visitno" name="newgl_epno-visitno" placeholder="" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl_epno-case">CASE</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-case" name="newgl_epno-case" placeholder="" type="text" required readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl_epno-refno">REFERENCE NO</small>
                                        <input class="form-control form-mandatory" id="newgl_epno-refno" name="newgl_epno-refno" placeholder="" type="text" required readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl_epno-ourrefno">OUR REFERENCE</small>
                                        <input class="form-control" id="newgl_epno-ourrefno" name="newgl_epno-ourrefno" placeholder="" type="text" readonly >
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl_epno-remark">REMARK</small>
                                        <input class="form-control" id="newgl_epno-remark" name="newgl_epno-remark" placeholder="" type="text" readonly>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_mc" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog mediummodal">
        <button class="glyphicon glyphicon-remove close_icon" aria-hidden="true" type="button" data-dismiss="modal"></button>
		<div class="modal-content">
			<div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                <b>Medical Certificate</b>
            </div>
            <div class="modal-body">
                @include('hisdb.pat_enq.epno_medc')
            </div>
		</div>
	</div>
</div>

<div id="mdl_nok" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog mediummodal">
        <button class="glyphicon glyphicon-remove close_icon" aria-hidden="true" type="button" data-dismiss="modal"></button>
        <div class="modal-content">
            <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                <b>Next Of Kin</b>
            </div>
            <div class="modal-body">
                @include('hisdb.pat_enq.epno_nok')
            </div>
        </div>
    </div>
</div>

<div id="mdl_payer" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog smallmedmodal">
        <button class="glyphicon glyphicon-remove close_icon" aria-hidden="true" type="button" data-dismiss="modal"></button>
        <div class="modal-content">
            <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                <b>Payer</b>
            </div>
            <div class="modal-body" style="padding: 0px 10px;">
                @include('hisdb.pat_enq.epno_payer')
            </div>
        </div>
    </div>
</div>

<div id="mdl_docinfo" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog mediummodal">
        <button class="glyphicon glyphicon-remove close_icon" aria-hidden="true" type="button" data-dismiss="modal"></button>
        <div class="modal-content">
            <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                <b>Doctor Info <span id="spanttl-docinfo"></span></b>
            </div>
            <div class="modal-body" style="padding: 0px 10px;">
                @include('hisdb.pat_enq.epno_docinfo')
            </div>
        </div>
    </div>
</div>