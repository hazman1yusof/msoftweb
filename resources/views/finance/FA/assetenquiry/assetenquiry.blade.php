@extends('layouts.main')

@section('title', 'Asset Enquiry')

@section('style')

	.panel-heading.collapsed .fa-angle-double-up,
	.panel-heading .fa-angle-double-down {
	display: none;
	}

	.panel-heading.collapsed .fa-angle-double-down,
	.panel-heading .fa-angle-double-up {
	display: inline-block;
	}

	.panel-heading i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
	}

	.panel-heading div i {
			position: relative;
			line-height: 1;
			top: -10px;
		}

	<!-- /* The sticky class is added to the header with JS when it reaches its scroll position */ -->
	.sticky {
		z-index: 100;
		position: fixed;
		top: 0;
		width: 100%
	}

	.clearfix {
		overflow: auto;
	}

	input.uppercase {
		text-transform: uppercase;
	}

	.justify {
		text-align: -webkit-center;
	}

	row.error td { background-color: red; }

	i.arrow {
		cursor: pointer;
		float: right;
		<!--  margin-right: 5px; -->
	}

	.position i {
		position: relative;
		line-height: 1;
		top: -10px;
	}

	.desc_show{
		overflow:hidden;
		max-height: 16px;
		display: block;
		max-width: 60%;
	}
@endsection

@section('body')

	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol' style='font-weight:bold'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">

					<div  id="assetcode_depan_div" style="display:none">
						<div class='input-group'>
							<input id="assetcode_depan" name="assetcode_depan" type="text" maxlength="12" class="form-control input-sm" placeholder="Asset Code">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
					<div  id="assettype_depan_div" style="display:none">
						<div class='input-group'>
							<input id="assettype_depan" name="assettype_depan" type="text" maxlength="12" class="form-control input-sm" placeholder="Asset Type">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>

				</div>
				<div class="col-md-3 col-md-offset-9" style="padding-top: 0; text-align: end;">
					<!-- asalnya button nama 'History' tukar to 'Movement'-->
					<!-- <button type="button" id='histbut' class='btn btn-info' >Movement</button>  -->
				</div>
			</fieldset>
		</form>
		
		<div class="panel panel-default">
			<div class="panel-heading">Asset Enquiry Header
				<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span>Print</a>
			</div>
			<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    					<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>
	</div>

		<div class='row'>
		<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed position"  style="position: sticky;top: 0px;z-index: 3;">
				<b>Asset No: <span id="category_show_SerialAE"></span></b>
				<b> - <span id="assetno_show_SerialAE"></span><br><span class="desc_show" id="description_show_SerialAE"></span><a id="seemore_show_SerialAE" style="display: none" data-show='false'>see more</a></b>

				<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid3_panel"></i>
				<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid3_panel"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 10px;">
					<h5>Asset Serial List</h5>
				</div>

				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
						id="btn_grp_edit_SerialAE"
						style="position: absolute;
								padding: 0 0 0 0;
								right: 40px;
								top: 15px;" 
					>
				</div>	
			</div>

			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
    		</div>
  		</div>
	</div>

	<div class='row'>
			@include('finance.FA.assetenquiry.assetenquiryDtl2')
	</div>

	<div class='row'>
			@include('finance.FA.assettransfer2.assettransfer2')
	</div>

	<div class='row'>
		<div class="panel panel-default" style="position: relative;" id="jqGrid2_c">
			<div class="panel-heading clearfix collapsed position"  style="position: sticky;top: 0px;z-index: 3;">
				<b>Asset No: <span id="category_show_movementAE"></span></b>
				<b> - <span id="assetno_show_movementAE"></span><br><span class="desc_show" id="description_show_movementAE"></span><a id="seemore_show_movementAE" style="display: none" data-show='false'>see more</a></b>

				<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid2_panel"></i>
				<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid2_panel"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 10px;">
					<h5>Asset Movement Header</h5>
				</div>

				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
						id="btn_grp_edit_movementAE"
						style="position: absolute;
								padding: 0 0 0 0;
								right: 40px;
								top: 15px;" 
					>
						<!-- <button type="button" class="btn btn-default" id="edit_movementAE">
							<span class="fa fa-edit fa-lg"></span> Transfer
						</button>  <button type="button" class="btn btn-default" data-oper='add' id="save_movementAE">
							<span class="fa fa-save fa-lg"></span> Save
						</button>
						<button type="button" class="btn btn-default" id="cancel_movementAE" >
							<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
						</button> -->
					</div>	
			</div>

			<div id="jqGrid2_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>
				</div>
    		</div>
  		</div>
	</div>

	<!-------------------------------- End Search + table ------------------>
	
	<!-------------------------------- Asset Enquiry Detail (inside) ---------------------->
	
	<div id="dialogForm" title="Edit Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
		{{ csrf_field() }}
              
			<div class="prevnext btn-group pull-right"></div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="assetcode">Category</label>
						<div class="col-md-2">
							<input id="assetcode" name="assetcode" type="text" class="form-control input-sm text-uppercase" data-validation="required"  frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="assettype">Type</label>  
						<div class="col-md-2">
							<input id="assettype" name="assettype" type="text" maxlength="100" class="form-control input-sm text-uppercase" rdonly frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="assetno">NO</label>  
						<div class="col-md-2">
							<input id="assetno" name="assetno" type="text" maxlength="100" class="form-control input-sm text-uppercase" rdonly frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="description">Description</label>  
						<div class="col-md-6">
							<textarea class="form-control input-sm text-uppercase" name="description" rows="4" cols="55" id="description"></textarea>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="deptcode">Department</label>
						<div class="col-md-2">
							<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="loccode">Location</label>
						<div class="col-md-2">
							<input id="loccode" name="loccode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="col-md-2 control-label" for="suppcode">Supplier</label>
						<div class="col-md-2">
							<input id="suppcode" name="suppcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="delordno">Delivery Order No.</label>
						<div class="col-md-2">
							<input id="delordno" name="delordno" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="invno">Invoice No.</label>
						<div class="col-md-2">
							<input id="invno" name="invno" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="delorddate">Delivery Order Date</label>
						<div class="col-md-2">
							<input id="delorddate" name="delorddate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="invdate">Invoice Date</label>  
						<div class="col-md-2">
							<input id="invdate" name="invdate" type="text" class="form-control input-sm" 	data-validation="required" placeholder="YYYY-MM-DD" frozeOnEdit>
						</div>
				</div>
			
				<div class="form-group">
					<label class="col-md-2 control-label" for="purordno">Purchase No.</label>
						<div class="col-md-2">
							<input id="purordno" type="text" name="purordno" class="form-control input-sm text-uppercase" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="purdate">Purchase Date</label>
						<div class="col-md-2">
							<input id="purdate" type="text" name="purdate" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="purprice">Price</label>  
						<div class="col-md-2">
							<input id="purprice" name="purprice" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>  
						</div>
					<label class="col-md-2 control-label" for="qty">Quantity</label>  
						<div class="col-md-2">
							<input id="qty" name="qty" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit> 
						</div>
				</div>
				
				<hr>		

				<div class="form-group">
					<label class="col-md-2 control-label" for="serialno">Serial No</label>  
						<div class="col-md-2">
							<input id="serialno" type="text" name="serialno" maxlength="12" class="form-control input-sm text-uppercase">
						</div>
					<label class="col-md-2 control-label" for="lotno">Lot No</label>  
						<div class="col-md-2">
							<input id="lotno" type="text" name="lotno" maxlength="12" class="form-control input-sm text-uppercase">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="casisno">Casis No</label>  
						<div class="col-md-2">
							<input id="casisno" name="casisno" type="text" class="form-control input-sm text-uppercase">
						</div>
					<label class="col-md-2 control-label" for="engineno">Engine No</label>  
						<div class="col-md-2">
							<input id="engineno" name="engineno" maxlength="12" class="form-control input-sm text-uppercase" data-sanitize="required" >  
						</div>
				</div>
		
				<hr>

				<div class="form-group">
					<label class="col-md-2 control-label" for="method">Method</label>  
						<div class="col-md-2">
							<input id="method" type="text" name="method" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="residualvalue">Residual Value</label>  
						<div class="col-md-2">
							<input id="residualvalue" type="text" name="residualvalue" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="statdate">Start Date</label>  
						<div class="col-md-2">
							<input id="statdate" name="statdate" type="text" class="form-control input-sm"  frozeOnEdit>
						</div>

						<label class="col-md-2 control-label" for="rate">Rate (%p.a)</label>  
						<div class="col-md-2">
							<input id="rate" type="text" name="rate" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="origcost">Cost</label>  
						<div class="col-md-2">
							<input id="origcost" name="origcost" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" frozeOnEdit>  
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="lstytddep">Accum.(Prev Year)</label>  
						<div class="col-md-2">
							<input id="lstytddep" name="lstytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="recstatus">Status</label>
						<div class="col-md-2">
							<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>ACTIVE</label>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="cuytddep">Accum.(Y-T-D)</label>  
						<div class="col-md-2">
							<input id="cuytddep" name="cuytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" frozeOnEdit>
						</div>
					<label class="col-md-2 control-label" for="trantype">Tran Type</label>
						<div class="col-md-2">
							<input id="trantype" type="text" name="trantype" class="form-control input-sm" value="ADDITIONAL" rdonly frozeOnEdit>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="nbv">N-B-V</label>
						<div class="col-md-2">
							<input id="nbv" type="text" name="nbv" maxlength="12" class="form-control input-sm" rdonly>
						</div>
						
					<label class="col-md-2 control-label" for="trandate">Post Date</label>  
						<div class="col-md-2">
							<input id="trandate" name="trandate" type="text" class="form-control input-sm" 	 frozeOnEdit>
						</div>
				</div>
			</div>
			<hr>
		</form>
	</div>
	<!-------------------------------- Asset Enquiry Detail (inside) ---------------------->

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

	<script src="js/finance/FA/assetenquiry/assetenquiryScript.js?v=1.2"></script>
	<script src="js/finance/FA/assetenquiry/assetenquiryDtl2Script.js"></script>	
	<script src="js/finance/FA/assettransfer2/assettransfer2Script.js"></script>	

@endsection
	