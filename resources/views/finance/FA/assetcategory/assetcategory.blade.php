@extends('layouts.main')

@section('title', 'Category Setup')

@section('body')
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass">
					<div name='Scol'> Search By : </div>
				</div>

				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
				</div>

				<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					<p id="p_error"></p>
				</div>
			</fieldset> 
		</form>
		
		<div class="panel panel-default">
			<div class="panel-heading">Asset Category Setup Header</div>
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
			<div class="prevnext btn-group pull-right">
				{{ csrf_field() }}
				<input type="hidden" name="idno">
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="assetcode">Category</label>  
				<div class="col-md-2">
                    <input id="assetcode" name="assetcode" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                </div>
					   
				<label class="col-md-3 control-label" for="description" hidden="">Description</label>  
                <div class="col-md-5">
                    <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase">
                </div>
			</div>
                
            <div class="form-group">
				<label class="col-md-3 control-label" for="assettype">Type</label>  
				<div class="col-md-2">
					<div class='input-group'>
						<input id="assettype" name="assettype" type="text" maxlength="8" class="form-control input-sm text-uppercase" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div> 
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="deptcode">Department</label>  
				<div class="col-md-2">
					<div class='input-group'>
						<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
                <label class="col-md-3 control-label" for="tagnextno">Tagging Next No.</label>  
                <div class="col-md-2">
                    <input id="tagnextno" name="tagnextno" type="text" maxlength="100" class="form-control input-sm" data-validation="required" rdonly>
                </div>
			</div>

			<div class="form-group">
                <label class="col-md-3 control-label" for="depreciation">DEPRECIATION</label>  
            </div>

			<div class="form-group">
                <label class="col-md-3 control-label" for="method">Basis</label>  
                <div class="col-md-2">
                    <input id="method" name="method" type="text" maxlength="100" class="form-control input-sm" data-validation="required" rdonly>
                </div>
			</div>

			<div class="form-group">
		        <label class="col-md-3 control-label" for="rate">Rate (%p.a)</label>  
		        <div class="col-md-2">
		            <div class="input-group">
		                <input id="rate" name="rate" maxlength="30" class="form-control input-sm" input type ="text" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required">
		            </div>
		        </div>
            </div>

			<div class="form-group">
                <label class="col-md-3 control-label" for="residualvalue">Residual Value</label>  
                <div class="col-md-2">
                    <input id="residualvalue" name="residualvalue" type="number" maxlength="100" class="form-control input-sm" data-validation="required"  value = "1">
                </div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="recstatus">Record Status</label>  
				<div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>ACTIVE</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >DEACTIVE</label>
				</div>
			</div>   

        <!-- <div class="form-group">
				  				<label class="col-md-3 control-label" for="recstatus">Record Status</label>  
				 						 <div class="col-md-2">
										 <input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  					 </div>
				</div>				 -->

			<div class="form-group">
                <label class="col-md-3 control-label" for="description"></label>
				<label class="col-md-2 control-label" for="description">Cost Code</label>
				<label class="col-md-2 control-label" for="description">Account Code</label>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="glassetccode">Asset</label>  
				<div class="col-md-2">
					<div class='input-group'>
						<input id="glassetccode" name="glassetccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<div class="col-md-2">
					<div class='input-group'>
						<input id="glasset" name="glasset" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="gldepccode">Depreciation Code</label>  
				<div class="col-md-2">
					<div class='input-group'>
						<input id="gldepccode" name="gldepccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				  
				<label class="col-md-3 control-label" for="gldep" hidden="">Depreciation</label>  
                    <div class="col-md-2">
					  	<div class='input-group'>
							<input id="gldep" name="gldep" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<span class="help-block"></span>
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label" for="glprovccode">Provision for Depriciation Code</label>  
				  	<div class="col-md-2">
					  	<div class='input-group'>
							<input id="glprovccode" name="glprovccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
				
				<label class="col-md-3 control-label" for="glprovdep" hidden="">Provision for Depriciation</label>
                    <div class="col-md-2">
					  	<div class='input-group'>
							<input id="glprovdep" name="glprovdep" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<span class="help-block"></span>
				  	</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-3 control-label" for="glglossccode">Gain Code</label>  
				 	<div class="col-md-2">
					  	<div class='input-group'>
							<input id="glglossccode" name="glglossccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<span class="help-block"></span>
				  	</div>
				  
					<label class="col-md-3 control-label" for="glgainloss" hidden="">Gain</label>  
                    <div class="col-md-2">
					  	<div class='input-group'>
							<input id="glgainloss" name="glgainloss" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<span class="help-block"></span>
				  	</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-3 control-label" for="glrevccode">Loss Code</label>  
				  	<div class="col-md-2">
					  	<div class='input-group'>
							<input id="glrevccode" name="glrevccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<span class="help-block"></span>
				  	</div>
				  
					<label class="col-md-3 control-label" for="glrevaluation" hidden="">Loss</label> 
                      	<div class="col-md-2">
					  		<div class='input-group'>
								<input id="glrevaluation" name="glrevaluation" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
					<div class="col-md-3">
						<input id="computerid" name="computerid" type="text" class="form-control input-sm" hideOne frozeOnEdit>
					</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" hideOne frozeOnEdit>
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

	<script src="js/finance/FA/assetcategory/assetcategoryScript.js?v=1.1"></script>
	
@endsection