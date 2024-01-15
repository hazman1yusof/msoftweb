@extends('layouts.main')

@section('title', 'Asset Transfer')

@section('style')
	.noti{
		color: rgb(185, 74, 72);
	}

	i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
	}

	.collapsed ~ .panel-body {
	padding: 0;
	}

	.clearfix {
		overflow: auto;
	}
@endsection

@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
						<div name='Scol' style='font-weight:bold'>	Search By : </div> 			
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">					
				</div>
				<div class="col-md-3 col-md-offset-9" style="padding-top: 0; text-align: end;">
					<button type="button" id='writeoffButn' class='btn btn-info' >Write-Off</button> 
				</div>
			 </fieldset> 
		</form>

		<div class="panel panel-default">
			<div class="panel-heading">Asset Write-Off Header</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div>	
	</div>
		<!-------------------------------- End Search + table ------------------>
	
	

	<div id="dialogForm" title="Transfer Form">	
		{{ csrf_field() }}
		<form class='form-horizontal' style='width:89%' id='formdata'>
			<div class="prevnext btn-group pull-right">
				<input id="source" name="source" type="hidden">
				<input id="trantype" name="trantype" type="hidden">
				<input id="auditno" name="auditno" type="hidden">
				<input id="assetcode" name="assetcode" type="hidden">
				<input id="assettype" name="assettype" type="hidden">
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="assetno">Tagging No</label>
				<div class="col-md-2">
					<input id="assetno" name="assetno" type="text" class="form-control input-sm" frozeOnEdit>
				</div>
				<label class="col-md-3 control-label" for="description">Description</label>
				<div class="col-md-4">
					<input type="text" name="description" id="description" class="form-control input-sm" frozeOnEdit>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="currdeptcode">Current Department</label>
					<div class="col-md-3">
							<input type="text" name="currdeptcode" id="currdeptcode" class="form-control input-sm"  frozeOnEdit>
					</div>
				<label class="col-md-2 control-label" for="currloccode">Current Location</label>
					<div class="col-md-3">
							<input type="text" name="currloccode" id="currloccode" class="form-control input-sm" frozeOnEdit>
					</div>
			</div>

			<hr>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="trandate">Cost</label>
				<div class="col-md-2">
					<input type="text" name="cost" id="cost" class="form-control input-sm" data-validation="required">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="trandate">Accumulated</label>
				<div class="col-md-2">
					<input type="text" name="acuum" id="acuum" class="form-control input-sm" data-validation="required">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="trandate">NBV</label>
				<div class="col-md-2">
					<input type="text" name="nbv" id="nbv" class="form-control input-sm" data-validation="required">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="trandate">Date</label>
				<div class="col-md-2">
					<input type="date" name="date" id="date" class="form-control input-sm" data-validation="required">
				</div>

				<label class="col-md-2 control-label" for="trandate">Reason</label>
				<div class="col-md-6">
					<textarea class="form-control input-sm text-uppercase" name="reason" rows="4" cols="55" id="reason" data-validation="required"> </textarea>
				</div>
			</div>						
		</form>				
	</div>

	<!------------------- Second Pop Up Form-------------------------------->
		
	<div id="msgBox" title="Message Box" style="display:none">
		<p>Are you sure you want to transfer asset</p>
		<ul>
			<li>Item Code: <span name='itemcode' ></span></li>
			<li>Description: <span name='description' ></span></li>
		</ul>	

		
	</div>


	<!--///////////////////End Form /////////////////////////-->
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
		
	<script src="js/finance/FA/assetWriteOff/assetWriteOff.js"></script>
	
@endsection