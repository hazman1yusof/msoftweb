@extends('layouts.main')

@section('title', 'Till Registration')

@section('body')

	
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
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
		    <div class="panel-heading">Till Registration Header</div>
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

				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="tillcode">Till Code</label>  
				  <div class="col-md-4">
				  <input id="tillcode" name="tillcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
				  </div>
                </div>
				
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="40" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
				  </div>
                </div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="dept">Department</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="dept" name="dept" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="effectdate">Effective Date</label>  
					<div class="col-md-3">
					<input id="effectdate" name="effectdate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
					<!-- <input id="effectdate" name="effectdate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>"> -->

					</div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="defopenamt">Default Open Amount</label>  
				  <div class="col-md-3">
				  <input id="defopenamt" name="defopenamt" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float" data-validation-error-msg="Please Enter Value">
				  </div>
				  
				  <label class="col-md-2 control-label" for="tillstatus">Till Status</label>  
					<div class="col-md-3">
					<input id="tillstatus" name="tillstatus" type="text" class="form-control input-sm" value="C">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastrcnumber">Last No. Receipt</label>  
				  <div class="col-md-3">
				  <input id="lastrcnumber" name="lastrcnumber" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				  <label class="col-md-2 control-label" for="lastrefundno">Last No. Refund</label>  
					<div class="col-md-3">
					<input id="lastrefundno" name="lastrefundno" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				</div>
				
				<div class="form-group">
					<label class="col-md-2 control-label" for="lastcrnoteno">Last No. Credit note</label>  
						<div class="col-md-3">
							<input id="lastcrnoteno" name="lastcrnoteno" type="text" class="form-control input-sm" value="0000000001"/>
						</div>
				  
				  	<label class="col-md-2 control-label" for="lastinnumber">Last No. In</label>  
						<div class="col-md-3">
							<input id="lastinnumber" name="lastinnumber" type="text" class="form-control input-sm" value="0000000001">
				  		</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" frozeOnEdit hideOne >
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
					$("table#jqGrid").attr('tabindex',2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',3);
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

	<script src="js/finance/AR/till/till.js"></script>
@endsection