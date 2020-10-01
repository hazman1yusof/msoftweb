@extends('layouts.main')

@section('title', 'Asset Register')

@section('style')
	.noti{
		color: rgb(185, 74, 72);
	}
	.heading_panel_{
		font-size: larger !important;
	    font-weight: bold !important;
	    color: #333 !important;
	    background-color: #bad5ec !important;
	    border-color: ##8eb0ce !important;

	}

	.button_custom_hide{
		color: #333 !important;
		background-color: #bad5ec !important;
		border-color: #ffffff !important;
	}

	.button_custom_tag{
		color: #333 !important;
		border-color: #ffffff !important;
	}
@endsection

@section('body')
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
					  		<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

						<div class="col-md-5">
							<label class="control-label"></label>  
							<input  name="Stext" seltext='true' type="search" placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="search_assetcode_" style="display:none">
								<div class='input-group'>
									<input id="search_assetcode" name="search_assetcode" type="text" maxlength="12" class="form-control input-sm" seltext='false'>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-md-offset-2 pull-right" style="padding-top: 0; text-align: end;">
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-primary button_custom_hide' >Show Selection Item</button>
					<button type="button" id='taggingNoButton' class='btn btn-info button_custom_tag' >Generate Tagging No</button>
				</div>
			 </fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display:none">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>

    	<div class="panel panel-default">
    		<div class="panel-heading heading_panel_">List of Asset Register</div>
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    				<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>

    </div>
	<!-------------------------------- End Search + table ------------------>
	<!---secondform-->
	
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<div id="dialogForm" title="Add Form">
		<form class="form-horizontal" style='width: 99%' id="formdata">
		
			<input type='hidden' id='lineno_' name='lineno_'>
			<input type='hidden' id='recno' name='recno'>
			<input type='hidden' id='idno' name='idno'>
			<input type='hidden' id='description' name='description'>

			{{ csrf_field() }}
			<div class="form-group">
				<label class="col-md-2 control-label" for="assetcode">Category</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input id="assetcode" name="assetcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
				<label class="col-md-2 control-label" for="assettype">Type</label>  
				  	<div class="col-md-2">
				  		<input id="assettype" name="assettype" type="text" maxlength="100" class="form-control input-sm" rdonly>
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="deptcode">Department</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
				<label class="col-md-2 control-label" for="loccode">Location</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input id="loccode" name="loccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
			</div>
			<hr>

			<div class="form-group">
				<label class="col-md-2 control-label" for="regtype">Register Type</label>  
					<div class="col-md-4" data-validation="required">
						<label class="radio-inline"><input type="radio" name="regtype" value='PO'>Purchase Order</label >
						<label class="radio-inline"><input type="radio" name="regtype" value='DIRECT' checked="checked" >Direct</label>
				  	</div>
			</div>

			<div id='disableGroup'>
				<div class="form-group">
					<label class="col-md-2 control-label" for="suppcode">Supplier</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="suppcode" name="suppcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					<label class="col-md-2 control-label" for="delordno">Delivery Order No.</label>
						<div class="col-md-3">
							<div class="input-group">
								<input id="delordno" name="delordno" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary' id="delordno_btn"><span class='fa fa-ellipsis-h' id="delordno_dh"></span></a>
							</div>
							<span id="dn" class="help-block"></span>
						</div>
				</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="invno">Invoice No</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="invno" name="invno" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<!-- <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a> -->
							</div>
							<!-- <span class="help-block"></span> -->
						</div>

					<label class="col-md-2 control-label" for="delorddate">Delivery Order Date</label>
						<div class="col-md-3">
							<input id="delorddate" name="delorddate" type="date" class="form-control input-sm" 	data-validation="required" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="invdate">Invoice Date</label>  
						<div class="col-md-3">
							<input id="invdate" name="invdate" type="date" class="form-control input-sm" data-validation="required" placeholder="YYYY-MM-DD">
						</div>
					
					<label class="col-md-2 control-label" for="docno">GRN No</label>  
						<div class="col-md-3">
							<input id="docno" name="docno" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						</div>
				</div>

				<div class="form-group">

					<div id="itemcode_div">
					<label class="col-md-2 control-label" for="itemcode">Item Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="itemcode" name="itemcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>

					<div id="itemcode_direct_div">
					<label class="col-md-2 control-label" for="itemcode">Item Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="itemcode_direct" name="itemcode" type="text" class="form-control input-sm text-uppercase" >
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>


					<label class="col-md-2 control-label" for="uomcode">UOM Code</label>
						<div class="col-md-3">
							<div class='input-group'>
								<input id="uomcode" name="uomcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="purordno">Purchase No.</label>
						<div class="col-md-3">
							<input id="purordno" type="text" name="purordno" class="form-control input-sm text-uppercase">
						</div>
					<label class="col-md-2 control-label" for="purdate">Purchase Date</label>
						<div class="col-md-3">
							<input id="purdate" type="date" name="purdate" class="form-control input-sm" data-validation="required">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="purprice">Unit Price</label>  
						<div class="col-md-3">
							<input id="purprice" name="purprice" maxlength="12" class="form-control input-sm" value="0.00" data-validation="required">  
					 	</div>
					<label class="col-md-2 control-label" for="qty">Quantity</label>  
						<div class="col-md-3">
							<input id="qty" name="qty" maxlength="12" class="form-control input-sm" data-validation="required"> 
					 	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="currentcost">Current Cost</label>  
						<div class="col-md-3">
							<input id="currentcost" name="currentcost" maxlength="12" class="form-control input-sm" value="0.00" readonly>  
					 	</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="col-md-2 control-label" for="individualtag">Individual Tagging</label>  
					  <div class="col-md-4" >
						<label class="radio-inline"><input type="radio" name="individualtag" data-validation="required" value='Y' >Yes</label>
						<label class="radio-inline"><input type="radio" name="individualtag" value='N' checked >No</label>
					  </div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="method">Method</label>  
						<div class="col-md-3">
							<input id="method" type="text" name="method" maxlength="15" class="form-control input-sm text-uppercase">
						</div>
					<label class="col-md-2 control-label" for="rvalue">Residual Value</label>  
						<div class="col-md-3">
								<input id="rvalue" type="text" name="rvalue" maxlength="12" class="form-control input-sm">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="statdate">Start Date</label>  
						<div class="col-md-3">
							<input id="statdate" name="statdate" type="date" class="form-control input-sm">
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="origcost">Original Cost</label>  
						<div class="col-md-3">
							<input id="origcost" name="origcost" maxlength="12" class="form-control input-sm"  readonly>  
					 	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="lstytddep">Accum.(Prev Year)</label>  
						<div class="col-md-3">
							<input id="lstytddep" name="lstytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required">
						</div>
					<label class="col-md-2 control-label" for="recstatus">Status</label>
						<div class="col-md-4">
							<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="cuytddep">Accum.(Y-T-D)</label>  
						<div class="col-md-3">
							<input id="cuytddep" name="cuytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required">
						</div>
					<label class="col-md-2 control-label" for="trantype">Tran Type</label>
						<div class="col-md-3">
							<input id="trantype" type="text" name="trantype" class="form-control input-sm" value="ADDITIONAL" rdonly>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="nbv">N-B-V</label>
						<div class="col-md-3">
							<input id="nbv" type="text" name="nbv" maxlength="12" class="form-control input-sm" readonly>
						</div>
					<label class="col-md-2 control-label" for="trandate">Post Date</label>  
						<div class="col-md-3">
							<input id="trandate" name="trandate" type="date" class="form-control input-sm" >
						</div>
				</div>

				<hr>
			</div>
		</form>

		<div class="panel-body">
			<div class="noti"><ol></ol>
			</div>
		</div>
	</div>
	@endsection


@section('scripts')

	<script src="js/finance/FA/assetregister/assetregisterScript.js"></script>
	
@endsection