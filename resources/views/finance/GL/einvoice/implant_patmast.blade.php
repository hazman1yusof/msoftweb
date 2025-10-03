@extends('layouts.main')

@section('title', 'List of Implant Patient')

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
						</div>

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  </div>
		      </div>
				</div>

			</fieldset> 
		</form>

    <div class="panel panel-default">
	    <div class="panel-heading" style="position:relative;">List of Implant Patient
				<!-- <a class='pull-right pointer text-primary' style="padding-left: 25px" id='debtormast_edit'>
			    Edit Patient
				</a> -->
				<a class='pull-right pointer text-primary' style="padding-left: 25px" id='print_ip'>
			    Print
				</a>
	    </div>
	    <div class="panel-body">
	    	<div class='col-md-12' style="padding:0 0 15px 0">
          		<table id="jqGrid" class="table table-striped"></table>
          		<div id="jqGridPager"></div>
      		</div>
	    </div>
		</div>

		<div id="dialog_debtormast" title="Debtormast">
			<div class="panel-body" style="position: relative;padding:0px;">
			<form autocomplete="off" id="formdata_dm">
				<div id="fail_msg_dm" style="color: darkred;"></div>
				<div class="col-md-4" style="padding-top:5px">
				  	<label class="control-label" for="payercode_dm">MRN</label>  
						<input id="payercode_dm" name="payercode_dm" type="text" class="form-control input-sm text-uppercase" autocomplete="off" readonly>
				</div>
				<div class="col-md-8" style="padding-top:5px">
				  	<label class="control-label" for="payername_dm">Name</label>  
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
	<script src="js/finance/GL/einvoice/implant_patmast.js"></script>
@endsection