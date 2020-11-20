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
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
						<div name='Scol' style='font-weight:bold'>	Search By : </div> 			
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">					
				</div>
				<div class="col-md-3 col-md-offset-9" style="padding-top: 0; text-align: end;">
					<button type="button" id='transferButn' class='btn btn-info' >Transfer</button> 
				</div>
			 </fieldset> 
		</form>
	</div>
		<!-------------------------------- End Search + table ------------------>
	
	<div class="panel panel-default">
		<div class="panel-heading">Asset Transfer Header</div>
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
			</div>
		</div>

		<div id="dialogForm" title="Transfer Form">	
		{{ csrf_field() }}
			<form class='form-horizontal' style='width:89%' >
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
								<input type="text" name="currdeptcode" id="currdeptcode" class="form-control input-sm" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="currloccode">Current Location</label>
						<div class="col-md-3">
								<input type="text" name="currloccode" id="currloccode" class="form-control input-sm" frozeOnEdit>
						</div>
				</div>
			</form>

			<hr>
			<form class='form-horizontal' style='width:89%' id='formdata'>
				<div class="form-group">
					<label class="col-md-2 control-label" for="trandate">Transfer Date</label>
					<div class="col-md-2">
						<input type="date" name="trandate" id="trandate" class="form-control input-sm" data-validation="required">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="deptcode">New Department</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="text" name="deptcode" id="deptcode" class="form-control input-sm" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>

					<label class="col-md-2 control-label" for="loccode">New Location</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="text" name="loccode" id="loccode" class="form-control input-sm" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
				</div>						
			</form>				
		</div>
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

	<script src="js/finance/FA/assettransfer/assettransferScript.js"></script>
	
@endsection