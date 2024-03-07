@extends('layouts.main')

@section('title', 'Department')

@section('style')
	input.uppercase {
		text-transform: uppercase;
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
					</div>
				</div>
			</fieldset>
		</form>
		
		<div class="panel panel-default">
			<div class="panel-heading">Department Setup Header</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="dialogForm" title="Add Form">
		<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			<input type="hidden" name="idno">
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="deptcode">Department</label>
				<div class="col-md-4">
					<input id="deptcode" name="deptcode" type="text" maxlength="30" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="description">Description</label>
				<div class="col-md-8">
					<input id="description" name="description" type="text" maxlength="100" class="form-control input-sm uppercase" data-validation="required">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="costcode">Cost Center</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="costcode" name="costcode" type="text" class="form-control input-sm uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<label class="col-md-2 control-label" for="category">Category Of</label>
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="category" value='HOSPITAL' data-validation="required">Hospital</label>
					<label class="radio-inline"><input type="radio" name="category" value='CLINIC'>Clinic</label>
					<label class="radio-inline"><input type="radio" name="category" value='OTHERS'>Others</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="region">Region</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="region" name="region" type="text" class="form-control input-sm uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<label class="col-md-2 control-label" for="sector">Unit</label>
				<div class="col-md-3">
					<div class='input-group'>
						<input id="sector" name="sector" type="text" class="form-control input-sm uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="chgdept">Charge Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="chgdept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="chgdept" value='0'>No</label>
				</div>
				
				<label class="col-md-2 control-label" for="purdept">Purchase Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="purdept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="purdept" value='0'>No</label>
				</div>
				
				<label class="col-md-2 control-label" for="admdept">Admit Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="admdept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="admdept" value='0'>No</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="warddept">Ward Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="warddept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="warddept" value='0'>No</label>
				</div>

				<label class="col-md-2 control-label" for="regdept">Register Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="regdept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="regdept" value='0'>No</label>
				</div>
				
				<label class="col-md-2 control-label" for="dispdept">Dispense Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="dispdept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="dispdept" value='0'>No</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="storedept">Store Dept</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="storedept" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="storedept" value='0'>No</label>
				</div>
				
				<label class="col-md-2 control-label" for="recstatus">Record Status</label>
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE'>Deactive</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>
				<div class="col-md-2">
					<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="upduser">Last Entered</label>
				<div class="col-md-2">
					<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="adddate">Created Date</label>
				<div class="col-md-2">
					<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>
				<div class="col-md-2">
					<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="computerid">Computer Id</label>
				<div class="col-md-2">
					<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
				
				<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>
				<div class="col-md-2">
					<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
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
	<script src="js/finance/GL/department/department.js"></script>
@endsection