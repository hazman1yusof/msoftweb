@extends('layouts.main')

@section('title', 'Delivery Department')

@section('body')

	<!-------------------------------- Search + table ---------------------->
		<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass">
					<div name='Scol'>Search By : </div>
				</div>

				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">
				</div>
			</fieldset> 
		</form>
		
		<div class="panel panel-default">
		<div class="panel-heading">Delivery Department Header</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>
	</div>

	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			<div class='col-md-12'>

				{{ csrf_field() }}
				<input type="hidden" name="idno">
			 	
				<div class="form-group">
				  <label class="col-md-2 control-label" for="deptcode">Delivery Store</label>  
				  <div class="col-md-3">
					<div class='input-group'>
							<input id="deptcode" name="deptcode" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
							<a class='input-group-addon btn btn-primary' id="1"><span class='fa fa-ellipsis-h' id="2"></span></a>
					</div>
						<span class="help-block"></span>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="description">Description</label>  
						<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm text-uppercase">
						</div>
				</div>

                <div class="form-group">
					<label class="col-md-2 control-label" for="addr1">Address</label>  
						<div class="col-md-8">
							<input id="addr1" name="addr1" type="text" class="form-control input-sm text-uppercase">
						</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input id="addr2" name="addr2" type="text" class="form-control input-sm text-uppercase">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input id="addr3" name="addr3" type="text" class="form-control input-sm text-uppercase">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input id="addr4" name="addr4" type="text" class="form-control input-sm text-uppercase">
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-md-2 control-label" for="tel">Telephone</label>  
						<div class="col-md-3">
							<input id="tel" name="tel" type="text" class="form-control input-sm" >
						</div>
				
					<label class="col-md-2 control-label" for="generaltel">General Telephone</label>  
						<div class="col-md-3">
							<input id="generaltel" name="generaltel" type="text" class="form-control input-sm">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="fax">Fax</label>  
						<div class="col-md-3">
							<input id="fax" name="fax" type="text" class="form-control input-sm" >
						</div>
				
					<label class="col-md-2 control-label" for="generalfax">General Fax</label>  
						<div class="col-md-3">
							<input id="generalfax" name="generalfax" type="text" class="form-control input-sm" >
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="contactper">Contact Person</label>  
						<div class="col-md-3">
							<input id="contactper" name="contactper" type="text" class="form-control input-sm text-uppercase">
						</div>
				
						<label class="col-md-2 control-label" for="email">Email</label>  
							<div class="col-md-3">
								<input id="email" name="email" type="text" class="form-control input-sm">
							</div>
              	</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
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
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm"  frozeOnEdit hideOne>
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
					$("table#jqGrid").attr('tabindex', 2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex', 3);
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

	<script src="js/material/Delivery Department/deliveryDeptScript.js"></script>

@endsection