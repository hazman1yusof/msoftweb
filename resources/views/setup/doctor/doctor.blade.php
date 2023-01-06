@extends('layouts.main')

@section('title', 'Doctor Setup')

@section('style')

#detail {
		
	border-bottom: 1px solid transparent;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	transform-origin: 0 50%;
	transform: rotate(-90deg);
	white-space: nowrap;
	display: block;
	position: absolute;
	bottom: 0;
	left: 3%;
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

.clearfix {
	overflow: auto;
}

@endsection

@section('body')

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
			<div class="panel-heading">Doctor
				<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>

		<div class="panel-group">
			<div class="panel panel-default" id="jqGrid2_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid2_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Doctor Contribution
				</div>
				<div id="jqGrid2_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid2" class="table table-striped"></table>
								<div id="jqGridPager2"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>

			{{ csrf_field() }}
			<input type="hidden" name="idno">

			<div class='col-md-12'>
				<div class='panel panel-info'>
					<div id="detail" class="panel-heading" style="padding: 10px 139px"><b>PERSONAL DETAILS</b></div>
					<div class="panel-body">			
						<div class="prevnext btn-group pull-right"></div>
		
						<div class="form-group">
							<label class="col-md-2 control-label" for="doctorcode">Doctor Code</label>  
							<div class="col-md-4">
								<input id="doctorcode" name="doctorcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
							</div>

							<label class="col-md-2 control-label" for="doctype">Doctor Type</label>  
							<div class="col-md-4">
								<div class='input-group'>
									<input id="doctype" name="doctype" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div> 
						</div>

						<div class="form-group">					
							<label class="col-md-2 control-label" for="doctorname">Doctor Name</label>  
							<div class="col-md-10">
								<input id="doctorname" name="doctorname" type="text" maxlength="200" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="department">Login ID</label>  
							<div class="col-md-4">
								<input id="loginid" name="loginid" type="text" maxlength="200" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="department">Costcenter</label>  
							<div class="col-md-4">
								<div class='input-group'>
									<input id="department" name="department" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						
							<label class="col-md-2 control-label" for="specialitycode">Speciality</label>  
							<div class="col-md-4">
								<div class='input-group'>
									<input id="specialitycode" name="specialitycode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="disciplinecode">Discipline</label>  
							<div class="col-md-4">
								<div class='input-group'>
									<input id="disciplinecode" name="disciplinecode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
					
							<label class="col-md-2 control-label" for="creditorcode">Creditor</label>  
							<div class="col-md-4">
								<div class='input-group'>
									<input id="creditorcode" name="creditorcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="classcode">Class</label>  
							<div class="col-md-4">
								<table>
									<tr>							
										<td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" data-validation-error-msg="Please Enter Value" value='CO' checked="">Consultant</label></td>
										<td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" data-validation-error-msg="Please Enter Value" value='MO'>Medical Officer</label></td>							
									</tr>						
									<tr>
										<td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" data-validation-error-msg="Please Enter Value" value='PH'>Pharmacist</label></td>
										<td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" data-validation-error-msg="Please Enter Value" value='PHY'>Physiotherapist</label></td>                             
									</tr>
								</table>				
							</div>
					
							<label class="col-md-2 control-label" for="resigndate">Resign Date</label>  
							<div class="col-md-4">
								<input id="resigndate" name="resigndate" type="date" maxlength="30" class="form-control input-sm">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="mmcid">Malaysian Medical Council (MMC) ID</label>  
							<div class="col-md-4">
								<input id="mmcid" name="mmcid" type="text" maxlength="30" class="form-control input-sm text-uppercase">
							</div>

							<label class="col-md-2 control-label" for="apcid">Advanced Practice Clinician (APC) ID</label>  
							<div class="col-md-4">
								<input id="apcid" name="apcid" type="text" maxlength="30" class="form-control input-sm text-uppercase">
							</div> 
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="admright">Admission Right</label>  
							<div class="col-md-4">
								<label class="radio-inline"><input type="radio" name="admright" value='1' data-validation="required" checked="">Yes</label>
								<label class="radio-inline"><input type="radio" name="admright" value='0' data-validation="">No</label>
							</div>
						
							<label class="col-md-2 control-label" for="appointment">Appointment</label>  
							<div class="col-md-4">
								<label class="radio-inline"><input type="radio" name="appointment" value='1' data-validation="required" checked="">Yes</label>
								<label class="radio-inline"><input type="radio" name="appointment" value='0' data-validation="">No</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="intervaltime">Interval Time</label>  
							<div class="col-md-4">
								<div class="input-group">
									<input id="intervaltime" name="intervaltime" type="text" maxlength="30" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
									<span class="input-group-addon">minutes</span>
								</div>
							</div>

							<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
							<div class="col-md-4">
								<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
								<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='col-md-12'>
				<div class='panel panel-info'>
					<div id="detail" class="panel-heading" style="padding: 10px 130px"><b>CONTACT ADDRESS</b></div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="company">Company</label>  
							<div class="col-md-8">
								<input id="company" name="company" type="text" maxlength="100" class="form-control input-sm text-uppercase">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="address1">Address</label>  
							<div class="col-md-8">
								<input id="address1" name="address1" type="text" class="form-control input-sm text-uppercase">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input id="address2" name="address2" type="text" class="form-control input-sm text-uppercase">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input id="address3" name="address3" type="text" class="form-control input-sm text-uppercase">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="postcode">Postcode</label>  
							<div class="col-md-3">
								<input id="postcode" name="postcode" type="text" class="form-control input-sm" >
							</div>
						
							<label class="col-md-2 control-label" for="statecode">State</label>  
							<div class="col-md-3">
								<input id="statecode" name="statecode" type="text" class="form-control input-sm text-uppercase">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="countrycode">Country</label>  
							<div class="col-md-3">
								<input id="countrycode" name="countrycode" type="text" class="form-control input-sm text-uppercase">
							</div>
						
							<label class="col-md-2 control-label" for="gstno">GST No</label>  
							<div class="col-md-3">
								<input id="gstno" name="gstno" type="text" class="form-control input-sm text-uppercase">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="res_tel">Home</label>  
							<div class="col-md-3">
								<input id="res_tel" name="res_tel" type="text" class="form-control input-sm">
							</div>
						
							<label class="col-md-2 control-label" for="tel_hp">H/Phone</label>  
							<div class="col-md-3">
								<input id="tel_hp" name="tel_hp" type="text" class="form-control input-sm">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="off_tel">Office</label>  
							<div class="col-md-3">
								<input id="off_tel" name="off_tel" type="text" class="form-control input-sm text-uppercase">
							</div>
						
							<label class="col-md-2 control-label" for="operationtheatre">Operation Theatre (OT)</label>  
							<div class="col-md-3">
								<label class="radio-inline"><input type="radio" name="operationtheatre" value='1'  checked="">Yes</label>
								<label class="radio-inline"><input type="radio" name="operationtheatre" value='0' >No</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		
			<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>  
				<div class="col-md-3">
					<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
				<div class="col-md-3">
					<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="adddate">Created Date</label>  
				<div class="col-md-3">
					<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
				<div class="col-md-3">
					<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
				<div class="col-md-3">
					<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" frozeOnEdit rdonly>
				</div>

				<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
				<div class="col-md-3">
					<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit rdonly>
				</div>
			</div>

		</form>
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
	
	<script src="js/setup/doctor/doctorScript.js"></script>
	
@endsection