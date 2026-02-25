@extends('layouts.main')

@section('title', 'Charge Master Setup')

@section('style')

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

fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}

input.uppercase {
	text-transform: uppercase;
}

span.error_pkgmast {
    position: absolute;
    right: 200px;
    top: 15px;
    color: darkred;
}

.mycontainer .mybtnpdf{
	background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 80%) !important;
	color: #af2525;
	border-color: #af2525;
	margin-bottom: 5px;
}

.mycontainer .mybtnxls{
	background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 80%) !important;
	color: darkgreen;
	border-color: darkgreen;
	margin-bottom: 5px;
}

.ui-jqgrid-bdiv{
	overflow-x: hidden !important;
}

@endsection

@section('body')
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		              	</div>

					  	<div class="col-md-5" style="padding-right: 0px;">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div  id="show_chggroup" style="display:none">
								<div class='input-group'>
									<input id="cg_chggroup" name="cg_chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_chgtype" style="display:none">
								<div class='input-group'>
									<input id="ct_chgtype" name="ct_chgtype" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="col-md-1" style="padding-left: 0px;">
							<div id="div_chggroup" style="padding-left: 0px;padding-right: 40px;display:none">
								<label class="control-label"></label>
								<a class='form-control btn btn-primary' id="btn_chggroup"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<div id="div_chgtype" style="padding-left: 0px;padding-right: 40px;display:none;margin: 0px">
								<label class="control-label"></label>
								<a class='form-control btn btn-primary' id="btn_chgtype"><span class='fa fa-ellipsis-h'></span></a>
							</div>
						</div>
		            </div>
				</div>
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">Charge Master Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excel'>
					<span class='fa fa-print'></span> Charge Price List 
				</a>
			</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

	    <!-- <div class="panel panel-default">
		    <div class="panel-heading">Charge Price</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
	            	<table id="jqGrid3" class="table table-striped"></table>
	            	<div id="jqGridPager3"></div>
	    		</div>
		    </div>
		</div>  -->

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGrid3_c">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Charge Price
    			</div>
    			<div id="jqGrid3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata3' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid3" class="table table-striped"></table>
								<div id="jqGridPager3"></div>
							</div>
						</form>
					</div>
    			</div>
  			</div>
		</div>

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGridPkg3_c">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGridPkg3_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Charge Price
    			</div>
    			<div id="jqGridPkg3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdataPkg3' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGridPkg3" class="table table-striped"></table>
								<div id="jqGridPagerPkg3"></div>
							</div>
						</form>
					</div>
    			</div>
  			</div>
		</div>

		<div class='click_row' id="click_row">
			<label class="control-label">Package Code</label>
			<span id="showpkgcode" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row" style="width: 350px;">
			<label class="control-label">Description</label>
			<span id="showpkgdesc" style="display: block; white-space: nowrap;">&nbsp</span>
		</div>

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGrid4_c" style="position:relative;">
    			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid4_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Package Deal Maintenance Details
    				<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excelPkg'>
						<span class='fa fa-print'></span> Package Deal
					</a>
				</div>
    			<div id="jqGrid4_panel" class="panel-collapse collapse">
    				<span class="error_pkgmast"></span>
					<div class="panel-body">
						<form id='formdata4' class='form-vertical' style='width:99%'>
						{{ csrf_field() }}
					
							<input id="pkgcode" name="pkgcode" type="hidden">
							<input id="description" name="description" type="hidden">

							<!--< label class="col-md-1 control-label" for="autopull">AutoPull</label> 
								<div class="col-md-3">
									<label class="radio-inline"><input type="radio" name="autopull" value='1' disabled>Yes</label>
									<label class="radio-inline"><input type="radio" name="autopull" value='0' disabled>No</label>
								</div>

							<label class="col-md-1 control-label" for="addchg">Charge If More</label> 
								<div class="col-md-3">
									<label class="radio-inline"><input type="radio" name="addchg" value='1' disabled>Yes</label>
									<label class="radio-inline"><input type="radio" name="addchg" value='0' disabled>No</label>
								</div>
							<label class="col-md-2 control-label" for="addchg" style="float:right;">
								Total: <input type="input" name="grandtot1" id="grandtot1" disabled="disabled">
							</label> 
							<br></br> -->
							
							<label class="col-md-2 control-label" for="grandtot1" style="float:right;">
								Total: <input type="input" name="grandtot1" id="grandtot1" disabled="disabled">
							</label> 
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid4" class="table table-striped"></table>
								<div id="jqGridPager4"></div>
							</div>
						</form>
					</div>
    			</div>
  			</div>
		</div>

    </div>
	<!-- ***************End Search + table ********************* -->

	<!------------------------- Price List dialog search -------------------->
	<div id="priceListDialog" title="Price List">
		<input id="priceList" type="hidden" class="form-control input-sm" readonly>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata_priceList'>
					<input type="hidden" name="action" >

						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label" for="Scol">Chg. Group From</label> 
									<div class='input-group'> 
										<input id="chggroup_from" name="chggroup_from" type="text" class="form-control input-sm" autocomplete="off" value="">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
							</div>

							<div class="col-md-6">
							<label class="control-label" for="Scol">Chg. Group To</label>  
								<div class='input-group'>
									<input id="chggroup_to" name="chggroup_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label" for="Scol">Chg. Code From</label> 
									<div class='input-group'> 
										<input id="chgcode_from" name="chgcode_from" type="text" class="form-control input-sm" autocomplete="off" value="">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
							</div>

							<div class="col-md-6">
							<label class="control-label" for="Scol">Chg. Code To</label>  
								<div class='input-group'>
									<input id="chgcode_to" name="chgcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>
			</div>
	</div>

	<!------------------------- Package Deal dialog search -------------------->
	<div id="pkgDealDialog" title="Package Deals Listing">
		<div class="container mycontainer">
			<input id="pkgDeal" type="hidden" class="form-control input-sm" readonly>
			<div class="panel-body"  style="width: 250px;text-align: center">
				<form id='formdata_pkgDeal'>
					
					<input id="pkgcodePkg" name="pkgcodePkg" type="hidden">
					<input id="effectdate" name="effectdate" type="hidden">

					<button name="pdfgenPkg" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgenPkg">
						<span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
					</button>
					<button name="excelPkg" type="button" class="mybtn btn btn-sm mybtnxls" id="excelPkg">
						<span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
					</button>
				</form>
			</div>
		</div>
	</div>
	
	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Master</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="idno" name="idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="chgcode">Charge Code</label>  
						<div class="col-md-3">
							<input id="chgcode" name="chgcode" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						</div>

						<label class="col-md-2 control-label" for="uom">UOM Code</label>
						<div class="col-md-3">
			  				<div class="input-group">
								<input id="uom" name="uom" type="text" class="form-control input-sm text-uppercase" rdonly="">
								<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
			  				</div>
			  					<span class="help-block"></span>
						</div>
					</div>   

					<div class="form-group">
						<label class="col-md-2 control-label" for="description">Description</label>  
						<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm uppercase" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="brandname">Generic</label>  
						<div class="col-md-3">
							<input id="brandname" name="brandname" type="text" class="form-control input-sm uppercase">
						</div>
					</div>


					<div class="form-group">
						<label class="col-md-2 control-label" for="barcode">Bar Code</label>  
						<div class="col-md-3">
							<input id="barcode" name="barcode" type="text" class="form-control input-sm uppercase">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="chgclass">Class Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="chgclass" name="chgclass" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="control-label col-md-2" for="constype">Consultation Type</label>  
						<div class="col-md-3">
							<select class="form-control col-md-4" id='constype' name='constype' data-validation="">
								<option value='A'>Anaestetics</option>
								<option value='C'>Consultation</option>
								<option value='S'>Surgeon</option>
								<option value='' selected>None</option>
							</select> 
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="chggroup">Charge Group</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="chggroup" name="chggroup" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="chgtype">Charge Type</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="chgtype" name="chgtype" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
						</div>
					</div>

					<hr>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="invflag">Inv Flag</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="invflag" value='1'>Yes</label>
							<label class="radio-inline"><input type="radio" name="invflag" value='0'>No</label>
						</div>

						<label class="col-md-2 control-label" for="invflag">UOM Product</label>  
						<div class="col-md-3">
							<input id="uom_product" name="uom_product" type="text" class="form-control input-sm text-uppercase" readonly>
						</div>
					</div>
					
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Inventory</legend>

						<div class="form-group">
							<label class="col-md-2 control-label" for="packqty">Packing</label>  
							<div class="col-md-3">
								<input id="packqty" name="packqty" type="text" class="form-control input-sm uppercase" rdonly>
							</div>

							<label class="col-md-2 control-label" for="druggrcode">Drug Group Code</label>  
							<div class="col-md-3">
								<input id="druggrcode" name="druggrcode" type="text" class="form-control input-sm uppercase" rdonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="subgroup">Sub Group</label>  
							<div class="col-md-3">
								<input id="subgroup" name="subgroup" type="text" class="form-control input-sm uppercase" rdonly>
							</div>

							<label class="col-md-2 control-label" for="stockcode">Stock Code</label>  
							<div class="col-md-3">
								<input id="stockcode" name="stockcode" type="text" class="form-control input-sm uppercase" rdonly>
							</div>
						</div>
					</fieldset>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="invgroup">Inv. Group</label>
						<div class="col-md-3">
							<select class="form-control col-md-4" id='invgroup' name='invgroup' data-validation="required">
								<option value='' selected="selected">Select one</option>
								<option value='CC'>Charge Code</option>
								<option value='CG'>Charge Group</option>
								<option value='CT'>Charge Type</option>
								<option value='DC'>Doctor</option>
							</select> 
						</div>

						<label class="col-md-2 control-label" for="costcode">Doctor Code</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="costcode" name="costcode" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="revcode">Revenue Dept. Code</label>  
						<div class="col-md-3">
							<div class='input-group'>
								<input id="revcode" name="revcode" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="seqno">Sequence No</label>  
						<div class="col-md-3">
							<input id="seqno" name="seqno" type="text" class="form-control input-sm uppercase">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="overwrite">Price Overwrite</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="overwrite" value='1' checked>Yes</label>
							<label class="radio-inline"><input type="radio" name="overwrite" value='0' >No</label>
						</div>

						<label class="col-md-2 control-label" for="doctorstat">Doctor</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="doctorstat" value='1' checked>Yes</label>
							<label class="radio-inline"><input type="radio" name="doctorstat" value='0' >No</label>
						</div>
					</div>

					<hr>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="upduser">Last User</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" class="form-control input-sm uppercase" rdonly>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Update</label>  
						<div class="col-md-3">
							<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm uppercase" rdonly>
						</div>
					</div> 
					

					<div class="form-group">
						<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
							<input id="computerid" name="computerid" type="text" class="form-control input-sm uppercase" rdonly>
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid"> Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm uppercase" rdonly>
						</div>
					</div> 
				</form>
			</div>
		</div>
			
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Price Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>

					<div id="jqGridPkg2_c" class='col-md-12'>
						<table id="jqGridPkg2" class="table table-striped"></table>
						<div id="jqGridPagerPkg2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>

		<!-- <div class='panel panel-info'>
			<div class="panel-heading">Charge Price Detail</div>
			<div class="panel-body">
				<form id='formdataPkg2' class='form-vertical' style='width:99%'>
					<div id="jqGridPkg2_c" class='col-md-12'>
						<table id="jqGridPkg2" class="table table-striped"></table>
						<div id="jqGridPagerPkg2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>	 -->
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
	<script src="js/setup/chargemaster/chargemaster.js?v=1.1"></script>
	
@endsection



		