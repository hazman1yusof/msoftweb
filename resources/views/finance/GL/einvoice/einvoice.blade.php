@extends('layouts.main')

@section('title', 'E-invoice')

@section('style')

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
 <!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
  padding: 0;
}
@endsection

@section('body')

<input id="viewonly" name="viewonly" type="hidden" value="{{Request::get('viewonly')}}">
@if(Request::get('viewonly') == 'viewonly')
<input id="viewonly_auditno" type="hidden" value="{{Request::get('auditno')}}">
<input id="viewonly_lineno_" type="hidden" value="{{Request::get('lineno_')}}">
@endif

<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  	<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		        </div>

					  <div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
						
							<div id="actdate_text" class="form-inline" style="display:none">
								FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="actdate_search">SEARCH</button>
							</div>
						</div>

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  </div>
		      </div>
				</div>

				<div class="col-md-2" id="unitsearch">
            <label class="control-label" for="unit">Unit</label> 
            <select id='unit' class="form-control input-sm">
                <option value="ALL">ALL</option>
                <option value="IMP" selected>IMP</option>
                <option value="W'HOUSE">W'HOUSE</option>
                <option value="KHEALTH">KHEALTH</option>
            </select>
        </div>
			</fieldset> 
		</form>
        <div class="panel panel-default">
		    <div class="panel-heading" style="position:relative;">Reprint Bill
					<a class='pull-right pointer text-primary' style="padding-left: 25px" id='printinvoice'>
				    Print Invoice
					</a>
					<a class='pull-right pointer text-primary' style="padding-left: 25px" id='verifytin'>
				    Verify TIN
					</a>
					<a class='pull-right pointer text-primary' style="padding-left: 25px" id='debtormast_edit'>
				    Address
					</a>
					<a class='pull-right btn btn-sm btn-primary'  id='btn_open_dialog_login' style="
						position: absolute;
		    		top: 3px;
		    		right: 250px;">
		    	Submit</a>
		    </div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="acctent_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#acctent_panel" id="panel_acctent">
				<b>Account Entries</b>
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
			</div>
			<div id="acctent_panel" class="panel-collapse collapse">
				<div class="panel-body" style="min-height: 50vh;">
					<b>Account Entries - <span id="acctent_title_span">Sales</span></b>
					<div class='pull-right pointer text-primary' style="padding-right:20px">
						<b>
							<a style="padding-right: 10px" id='a_sales_acctent' data-type='sales'> Sales </a>
						</b> | 
						<b>
							<a style="padding-left: 10px" id='a_cost_acctent' data-type='cost'> Cost </a>
						</b>
					</div>
					<div class='col-md-12' style="padding:15px 0 15px 0">
						<table id="gridacctent" class="table table-striped"></table>
						<div id="jqGridPageracctent"></div>
					</div>
				</div>
			</div>	
		</div> 

		<div id="dialog_verifytin" title="Verify TIN" data-submit_einvoice='false'>
			<div class="panel-body" style="position: relative;padding:0px;">
				<div id="fail_msg_verifytin" style="color: darkred;"></div>
				<div class="col-md-4" style="padding-top:5px">
				  	<label class="control-label" for="invno">Invoice No.</label>  
						<input id="invno" name="invno" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-4" style="padding-top:5px">
				  	<label class="control-label" for="mrn">MRN</label>  
						<input id="mrn" name="mrn" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-6" style="padding-top:5px">
				  	<label class="control-label" for="newic">Newic</label>  
						<input id="newic" name="newic" type="text" class="form-control input-sm text-uppercase" autocomplete="off">
				</div>
				<div class="col-md-10" style="padding-top:5px">
				  	<label class="control-label" for="dbname">Name</label>  
						<input id="dbname" name="dbname" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-10" style="padding-top:5px">
				  	<label class="control-label" for="tinid">Tin ID</label>  
						<div class='input-group'>
							<input id="tinid" name="tinid" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="">
							<a class='input-group-addon btn btn-primary' id="check_verifytin">Verify TIN</a>
						</div>
				</div>
				<div class="col-md-12" style="padding-top:5px">
					<button class="btn btn-primary pull-right" id="save_verifytin" style="margin-top: 10px;">Save</button>
					<button class="btn btn-primary pull-right" id="submit_einvoice" style="margin-top: 10px;">Submit E-invoice</button>
				</div>
			</div>
		</div>

		<div id="dialog_user_login" title="E-invoice Authorisation" >
			<form autocomplete="off" id="formdata_login">
			<div class="panel-body" style="position: relative;padding:0px;">
				<div id="fail_msg" style="color: darkred;"></div>
				<div class="col-md-12" style="padding-top:5px">
				  	<label class="control-label" for="username_login">Username</label>  
						<input id="username_login" name="username_login" type="text" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-12" style="padding-top:5px">
				  	<label class="control-label" for="password_login">Password</label>  
						<input id="password_login" name="password_login" type="password" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-12" style="padding-top:5px">
					<button class="btn btn-primary pull-right" id="login_submit">Submit</button>
				</div>
			</div>
			</form>
		</div>

		<div id="dialog_debtormast" title="Debtormast">
			<div class="panel-body" style="position: relative;padding:0px;">
			<form autocomplete="off" id="formdata_dm">
				<div id="fail_msg_dm" style="color: darkred;"></div>
				<div class="col-md-4" style="padding-top:5px">
				  	<label class="control-label" for="payercode_dm">Payercode</label>  
						<input id="payercode_dm" name="payercode_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-8" style="padding-top:5px">
				  	<label class="control-label" for="payername_dm">Payer Name</label>  
						<input id="payername_dm" name="payername_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-12" style="padding-top:5px">
				  	<label class="control-label" for="address1_dm">Address 1</label>  
						<input id="address1_dm" name="address1_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-12" style="padding-top:5px">
				  	<label class="control-label" for="address2_dm">Address 2</label>  
						<input id="address2_dm" name="address2_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-10" style="padding-top:5px">
				  	<label class="control-label" for="address3_dm">Address 3</label>  
						<input id="address3_dm" name="address3_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" >
				</div>
				<div class="col-md-2" style="padding-top:5px">
				  	<label class="control-label" for="statecode_dm">State Code</label>  
						<input id="statecode_dm" name="statecode_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-4" style="padding-top:5px">
				  	<label class="control-label" for="postcode_dm">Postcode</label>  
						<input id="postcode_dm" name="postcode_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-8" style="padding-top:5px">
				  	<label class="control-label" for="telhp_dm">Telephone</label>  
						<input id="telhp_dm" name="telhp_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" data-validation="required">
				</div>
				<div class="col-md-12" style="padding-top:5px">
					<button class="btn btn-primary pull-right" id="save_dm" style="margin-top: 10px;">Save</button>
				</div>
			</form>
			</div>
		</div>

   </div>

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
	<script src="js/finance/GL/einvoice/einvoice.js?v=1.2"></script>
@endsection