@extends('layouts.main')

@section('title', 'JTR')

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
		<div class="panel-heading">Debtor Type Setup Header</div>
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
			<input type="hidden" name="idno">

			<div class="form-group">
			  <label class="col-md-3 control-label" for="yearmonth">Year - Month</label>  
			  <div class="col-md-8">
			  <input id="yearmonth" name="yearmonth" type="month" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
			  </div>
            </div>
            
			<div class="form-group">
				<label class="col-md-3 control-label" for="deptcode">Deptcode</label>  
				<div class="col-md-8">
				  <div class='input-group'>
					<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
				  </div>
				  <span class="help-block"></span>
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
		<script src="js/finance/CM/jtr/jtr.js"></script>

		
@endsection