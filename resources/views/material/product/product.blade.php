@extends('layouts.main')

@section('title', 'Product Setup')

@section('style')
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
@endsection

@section('body')
	<div class='row'>
		<input type="hidden" name="unit_used" id="unit_used" value="{{ $unit_used }}">
		<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="itemcode_hidden" id="itemcode_hidden">
		<input type="hidden" name="uomcode_hidden" id="uomcode_hidden">
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;" onkeydown="return event.key != 'Enter';">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"  tabindex="1"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase"  tabindex="2">

							<div  id="show_product_infront_asset" style="display:none">
								<div class='input-group'>
									<input id="product_infront_asset" name="product_infront_asset" type="text" maxlength="222" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_product_infront_stock" style="display:none">
								<div class='input-group'>
									<input id="product_infront_stock" name="product_infront_stock" type="text" maxlength="222" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_product_infront_others" style="display:none">
								<div class='input-group'>
									<input id="product_infront_others" name="product_infront_others" type="text" maxlength="222" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

						</div>

					  	<div class="col-md-1" id="div_product_infront_asset" style="width: fit-content;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_product_infront_asset"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<div class="col-md-1" id="div_product_infront_stock" style="width: fit-content;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_product_infront_stock"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<div class="col-md-1" id="div_product_infront_others" style="width: fit-content;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_product_infront_others"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

		<div class="panel panel-default">
		    <div class="panel-heading">
		    	Product Header
				<a class='pull-right pointer text-primary' id='print_barcode'> Print Barcode</a>
		    </div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
	        		<table id="jqGrid" class="table table-striped"></table>
	        		<div id="jqGridPager"></div>
	    		</div>
		    </div>
		</div>

		<div class="panel-group">
  			<div class="panel panel-default" id="jqGrid3_c">
    			<div class="panel-heading"  href="#jqGrid3_panel">
					Charge Price<!-- <i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Charge Price -->
    			</div>
    			<div id="jqGrid3_panel" >
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
	</div>

	<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">
	<input id="groupcode2" name="groupcode" type="hidden" value="{{ $_GET['groupcode'] }}">

	<div id="dialogForm" title="Add Form" >
		<div class='col-md-12' id="formdataSearch_div">
			<div class='panel panel-info'>
				<div class="panel-heading"></div>
				<div class="panel-body">
					<form id='formdataSearch' class='form-horizontal' style='width:99%'>
						<div class="col-md-4">
						  	<label class="control-label" for="itemcode">Item Code</label>  
				  			<div class='input-group'>
								<input id="itemcodesearch" name="itemcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
				  			</div>
								<span class="help-block"></span>
						</div>
						  
						<div class="col-md-4">
						  	<label class="control-label" for="uomcode">UOM Code</label>  
				  				<div class='input-group' id="uomcodesearch_parent">
									<input id="uomcodesearch" name="uomcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
				  				</div>
									<span class="help-block"></span>
						</div>

						<div class="col-md-1">
							<button type="button" id="searchBut" class="btn btn-primary" style="position:absolute;top:23px">Create</button>
							<button type="button" id="cancelBut" class="btn btn-primary" style="position:absolute;top:23px;left:90px">Cancel</button>
			            </div>
					</form>
				</div>
			</div>
		</div>

		<div class='col-md-12'>
			<div class='panel panel-info'>
				<div class="panel-heading"></div>
				<div class="panel-body">
					<form id='formdata' class='form-horizontal' style='width:99%'>
						<input id="idno" name="idno" type="hidden">
						<input id="unit" name="unit" type="hidden" value="{{ Session::get('unit') }}">

						{{ csrf_field() }}
						<div class="form-group">
						  	<label class="col-md-2 control-label" for="itemcode">Item Code</label>  
						  		<div class="col-md-2" id="itemcode_parent">
									<input id="itemcode" name="itemcode" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						 		 </div>

						 	<label class="col-md-3 control-label" for="description">Item Description</label>  
						  		<div class="col-md-5" id="description_parent">
						  			<input id="description" name="description" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required">
						  		</div>
					    </div>

						<div class="form-group">                   
                  			<label class="col-md-2 control-label" for="generic">Generic Name</label>  
				 				<div class="col-md-3">
				  					<input id="generic" name="generic" type="text" maxlength="222" class="form-control input-sm text-uppercase" rdonly>
				  				</div>

				  			<label class="col-md-2 control-label" for="uomcode">UOM Code</label>  
						  		<div class="col-md-2" id="uomcode_parent">
									<input id="uomcode" name="uomcode" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						 		 </div>
						</div>

						<div class="form-group">
                   			<label class="col-md-2 control-label" for="groupcode">Group Code</label>  
				  				<div class="col-md-4">
									<label class="radio-inline"><input type="radio" name="groupcode" value='Stock' data-validation="required">Stock</label>
									<label class="radio-inline"><input type="radio" name="groupcode" value='Asset' data-validation="">Asset</label>
                    				<label class="radio-inline"><input type="radio" name="groupcode" value='Others' data-validation="">Others</label>
                    				<label class="radio-inline"><input type="radio" name="groupcode" value='Consignment' data-validation="">Consignment</label>

				 				</div>

                  			<label class="col-md-1 control-label" for="productcat">Product Category</label>  
				  				<div class="col-md-3" id="uomcode_parent">
									<input id="productcat" name="productcat" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit rdonly>
						 		 </div>
						</div>
                
                		<div class="form-group">
				 			<label class="col-md-2 control-label" for="subcatcode">Sub Category</label>  
				  				<div class="col-md-3">
				  					<div class='input-group'>
				  						<input id="subcatcode" name="subcatcode" type="text" maxlength="222" class="form-control input-sm text-uppercase" rdonly>
				  						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  				</div>
					  					<span class="help-block"></span>
				 		 		</div>
                  
                  			<label class="col-md-2 control-label" for="pouom">PO UOM</label>  
				  				<div class="col-md-3">
					  				<div class='input-group'>
										<input id="pouom" name="pouom" type="text" class="form-control input-sm text-uppercase" rdonly>
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  				</div>
					  					<span class="help-block"></span>
								</div>
						</div>

						<div class="form-group">
						  	<label class="col-md-2 control-label" for="suppcode">Supplier Code</label>  
						  		<div class="col-md-3">
							  		<div class='input-group'>
										<input id="suppcode" name="suppcode" type="text" class="form-control input-sm text-uppercase" rdonly>
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							  			<span class="help-block"></span>
						  		</div>
                  
                  			<label class="col-md-2 control-label" for="mstore">Main Store</label>  
				 				<div class="col-md-3">
					  				<div class='input-group'>
										<input id="mstore" name="mstore" type="text" class="form-control input-sm text-uppercase" rdonly>
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  				</div>
					  					<span class="help-block"></span>
				  				</div>
              			</div>

              			<div class="form-group">
						  	<label class="col-md-2 control-label" for="TaxCode">Tax Code</label>  
						  		<div class="col-md-3">
							  		<div class='input-group'>
										<input id="TaxCode" name="TaxCode" type="text" class="form-control input-sm text-uppercase" rdonly data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  		</div>
							  			<span class="help-block"></span>
						  		</div>
              			</div>
                
                 		<div class="form-group">
				  				<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  					<div class="col-md-3">
										<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
										<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
									</div>

				  			<div class="form-group">
			                	<label class="col-md-2 control-label" for="Class">Class</label>  
									<div class="col-md-3">
										<label class="radio-inline"><input type="radio" name="Class" value='Pharmacy'>Pharmacy</label>
										<label class="radio-inline"><input type="radio" name="Class" value='Non-Pharmacy'>Non-Pharmacy</label>
										<label class="radio-inline"><input type="radio" name="Class" value='Others'>Others</label>
										<label class="radio-inline"><input type="radio" name="Class" value='Asset'>Asset</label>
										<label class="radio-inline"><input type="radio" name="Class" value='Consignment'>Consignment</label>
									</div>
							</div> 
						</div>

                		@if (request()->get('groupcode') != 'Asset')
            			<hr> 
            			
            			<div class="form-group">
				  			<label class="col-md-3 control-label" for="minqty">Min Stock Qty</label>  
				  				<div class="col-md-2">
				  					<input id="minqty" name="minqty" type="text" maxlength="222" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0" rdonly>
				  				</div>
                  
                  			<label class="col-md-3 control-label" for="maxqty">Max Stock Qty</label>  
				  				<div class="col-md-2">
				  					<input id="maxqty" name="maxqty" type="text" maxlength="222" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0" rdonly>
				  				</div>
						</div>
                
		                <div class="form-group">
						  	<label class="col-md-3 control-label" for="reordlevel">Record Level</label>  
						  		<div class="col-md-2">
						  			<input id="reordlevel" name="reordlevel" type="text" maxlength="222" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0" rdonly>
						  		</div>
		                  
		                  	<label class="col-md-3 control-label" for="reordqty">Reoder Qty</label>  
						  		<div class="col-md-2">
						  			<input id="reordqty" name="reordqty" type="text" maxlength="222" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0" rdonly>
						  		</div>
						</div>

						<div class="form-group" hideOne>
                  			<label class="col-md-3 control-label" for="qtyonhand">Qty On Hand</label>  
				  				<div class="col-md-2">
									<input id="qtyonhand" name="qtyonhand" type="text" class="form-control input-sm" frozeOnEdit>
               	 
				  				</div> 

			  				<label class="col-md-3 control-label" for="avgcost">Average Cost</label>  
				  				<div class="col-md-2">
									<input id="avgcost" name="avgcost" type="text" class="form-control input-sm" frozeOnEdit>
	           	 
				  				</div> 
						</div>

						<div class="form-group" hideOne>
                  			<label class="col-md-3 control-label" for="currprice">Current Price</label>  
				  				<div class="col-md-2">
									<input id="currprice" name="currprice" type="text" class="form-control input-sm" frozeOnEdit>
               	 
				  				</div>
						</div>
                
                		<hr>
		                <div class="form-group">
						  	<label class="col-md-2 control-label" for="reuse">Reuse</label>  
						  		<div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="reuse" value='1' data-validation="required">Yes</label>
									<label class="radio-inline"><input type="radio" name="reuse" value='0' checked>No</label>
						  		</div>
						  
						  	<label class="col-md-1 control-label" for="rpkitem">Repack Item</label>  
						  		<div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="rpkitem" value='1' data-validation="required">Yes</label>
									<label class="radio-inline"><input type="radio" name="rpkitem" value='0' checked>No</label>
						  		</div>
		                  
		                  	<label class="col-md-2 control-label" for="tagging">Tagging</label>  
						  		<div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="tagging" value='1' data-validation="required">Yes</label>
									<label class="radio-inline"><input type="radio" name="tagging" value='0' checked>No</label>
						  		</div>
						</div>
                
		                <div class="form-group">
						  	<label class="col-md-2 control-label" for="expdtflg">Expiry Date</label>  
						  		<div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="expdtflg" value='1' data-validation="required" checked>Yes</label>
									<label class="radio-inline"><input type="radio" name="expdtflg" value='0'>No</label>
						  		</div>
						  
						  	<label class="col-md-1 control-label" for="chgflag">Charge</label>  
						  		<div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="chgflag" value='1' data-validation="required">Yes</label>
									<label class="radio-inline"><input type="radio" name="chgflag" value='0'>No</label>
						  		</div>

						   	<label class="col-md-2 control-label" for="itemtype">Item Type</label>  
						  		<div class="col-md-3">
									<label class="radio-inline"><input type="radio" name="itemtype" value='NON-POISON' data-validation="required" checked>Non-poison</label>
									<label class="radio-inline"><input type="radio" name="itemtype" value='POISON'>Poison</label>
						  		</div>
						</div>
						@endif


						<!-----charges---->

						<fieldset class="scheduler-border" id="charges_fieldset" style="display:none;">
							<legend class="scheduler-border">Charges Menu</legend>

							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_packqty">Packing</label>  
								<div class="col-md-3">
									<input id="cm_packqty" name="cm_packqty" type="text" class="form-control input-sm uppercase" rdonly="" readonly="">
								</div>

								<label class="col-md-2 control-label" for="cm_druggrcode">Drug Group Code</label>  
								<div class="col-md-3">
									<input id="cm_druggrcode" name="cm_druggrcode" type="text" class="form-control input-sm uppercase" rdonly="" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_subgroup">Sub Group</label>  
								<div class="col-md-3">
									<input id="cm_subgroup" name="cm_subgroup" type="text" class="form-control input-sm uppercase" rdonly="" readonly="">
								</div>

								<label class="col-md-2 control-label" for="cm_stockcode">Stock Code</label>  
								<div class="col-md-3">
									<input id="cm_stockcode" name="cm_stockcode" type="text" class="form-control input-sm uppercase" rdonly="" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_chgclass">Class Code</label>
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_chgclass" name="cm_chgclass" type="text" maxlength="222" class="form-control input-sm uppercase" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<label class="col-md-2 control-label" for="cm_dosecode">Dosage</label>
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_dosecode" name="cm_dosecode" type="text" maxlength="222" class="form-control input-sm uppercase">
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_chggroup">Charge Group</label>  
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_chggroup" name="cm_chggroup" type="text" maxlength="222" class="form-control input-sm uppercase" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<label class="col-md-2 control-label" for="cm_freqcode">Frequency</label>
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_freqcode" name="cm_freqcode" type="text" maxlength="222" class="form-control input-sm uppercase" >
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_chgtype">Charge Type</label>
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_chgtype" name="cm_chgtype" type="text" maxlength="222" class="form-control input-sm uppercase" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<label class="col-md-2 control-label" for="cm_instruction">Instruction</label>
								<div class="col-md-3">
									<div class="input-group">
										<input id="cm_instruction" name="cm_instruction" type="text" maxlength="222" class="form-control input-sm uppercase" >
										<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
									</div>
									<span class="help-block"></span>
								</div>

							</div>


							<div class="form-group">
								<label class="col-md-2 control-label" for="cm_invgroup">Inv. Group</label>
								<div class="col-md-3">
									<select class="form-control col-md-4" id="cm_invgroup" name="cm_invgroup" data-validation="required">
										<option value="" selected="selected">Select one</option>
										<option value="CC">Charge Code</option>
										<option value="CG">Charge Group</option>
										<option value="CT">Charge Type</option>
										<option value="DC">Doctor</option>
									</select> 
								</div>

							</div>



						</fieldset>

						<!----charges----> 

						<div class="form-group" hideOne>
							<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
								<div class="col-md-3">
								  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit>
								</div>

							<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
								<div class="col-md-3">
									<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="222" class="form-control input-sm"  frozeOnEdit>
								  	</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="dialog_barcode" title="Print barcode Form" >
		<div class="panel-body" style="position: relative;">
			<div class="col-md-12">
			  	<label class="control-label" for="itemcode_from_barcode">Item Code From</label>  
	  			<div class='input-group'>
					<input id="itemcode_from_barcode" name="itemcode_from_barcode" type="text" class="form-control input-sm text-uppercase">
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
					<span class="help-block"></span>
			</div>
			<div class="col-md-12">
			  	<label class="control-label" for="itemcode_to_barcode">Item Code To</label>  
	  			<div class='input-group'>
					<input id="itemcode_to_barcode" name="itemcode_to_barcode" type="text" class="form-control input-sm text-uppercase" >
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
					<span class="help-block"></span>
			</div>
			<div class="col-md-9">
			  	<label class="control-label" for="barcode_pages">Count</label>  
				<input id="barcode_pages" name="barcode_pages" type="number" maxlength="2" class="form-control input-sm text-uppercase" value="1">
			</div>
			<div class="col-md-3">
			  	<label class="control-label" for="">&nbsp;</label>  
				<button class="btn btn-primary" id="barcode_print">Print</button>
			</div>
		</div>
	</div>

	<!---*********************************** ADD NEW PRODUCT ************************************************** -->
		<div id="addNewProductDialog" title="Add New Product" >
			<form class='form-horizontal' style='width:99%' id='adpFormdata'>

				{{ csrf_field() }}

				<div class="form-group">
                	<label class="col-md-2 control-label" for="itemcode">Item Code</label>  
                    	<div class="col-md-3">
                      		<input id="itemcodeAddNew" name="itemcode" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
                      	</div>


                	<label class="col-md-2 control-label" for="uomcodeAddNew">UOM Code</label> 
                	<div class="col-md-3">
	  				<div class='input-group'>
						<input id="uomcodeAddNew" name="uomcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  				</div>
					<span class="help-block"></span>
					</div>
				</div>

				<div class="form-group">
				</div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="description">Description</label>  
                      <div class="col-md-8">
                      <input id="description" name="description" type="text" maxlength="222" class="form-control input-sm text-uppercase" data-validation="required">
                      </div>
				</div>

				<div class="form-group">
                   	<label class="col-md-2 control-label" for="groupcode">Group Code</label>  
				  		<div class="col-md-3">
							<label class="radio-inline"><input type="radio" id="groupcodeStock" name="groupcode" value='Stock' data-validation="required">Stock</label>
							<label class="radio-inline"><input type="radio" id="groupcodeAsset" name="groupcode" value='Asset' data-validation="">Asset</label>
                    		<label class="radio-inline"><input type="radio" id="groupcodeOther" name="groupcode" value='Others' data-validation="">Others</label>
							<label class="radio-inline"><input type="radio" id="groupcodeConsignment" name="groupcode" value='Consignment' data-validation="">Consignment</label>
				  </div>

                  <label class="col-md-2 control-label" for="productcat">Product Category</label>  
				  	<div class="col-md-3">
				  		<div class='input-group'>

                			@if (request()->get('groupcode') == 'Stock')
				  			<input id="productcatAddNew_stock" name="productcat" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
				  				<a class='input-group-addon btn btn-primary' id="2"><span class='fa fa-ellipsis-h' id-="3"></span></a>
				  			@elseif (request()->get('groupcode') == 'Asset')
				  			<input id="productcatAddNew_asset" name="productcat" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
				  				<a class='input-group-addon btn btn-primary' id="2"><span class='fa fa-ellipsis-h' id-="3"></span></a>
				  			@elseif (request()->get('groupcode') == 'Others')
				  			<input id="productcatAddNew_other" name="productcat" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
				  				<a class='input-group-addon btn btn-primary' id="2"><span class='fa fa-ellipsis-h' id-="3"></span></a>
							@else (request()->get('groupcode') == 'Consignment')
				  			<input id="productcatAddNew_consign" name="productcat" type="text" class="form-control input-sm text-uppercase" data-validation="required" rdonly>
				  				<a class='input-group-addon btn btn-primary' id="2"><span class='fa fa-ellipsis-h' id-="3"></span></a>
				  			@endif

					  	</div>
					  <span class="help-block"></span>
                      
				  </div>
				</div>

				<div class="form-group">
                	<label class="col-md-2 control-label" for="Class">Class</label>  
						<div class="col-md-5">
							<label class="radio-inline"><input type="radio" name="Class" id="p" value='Pharmacy'>Pharmacy</label>
							<label class="radio-inline"><input type="radio" name="Class" id="np" value='Non-Pharmacy'>Non-Pharmacy</label>
							<label class="radio-inline"><input type="radio" name="Class" id="o" value='Others'>Others</label>
							<label class="radio-inline"><input type="radio" name="Class" id="a" value='Asset'>Asset</label>
							<label class="radio-inline"><input type="radio" name="Class" id="c" value='Consignment'>Consignment</label>

						</div>
				</div>
<!-- 
				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
						</div>

					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
					  	<div class="col-md-3">
							<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
					  	</div>
				</div>  
 -->
			</form>
		</div>

	<!---*********************************** ADD NEW CHARGE PRICE ************************************************** -->
		<div id="addNewChgprice" title="Add New Charge Price" >
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
			<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
		</div>

	@endsection

@section('scripts')
	<script type="text/javascript">
		var pricelabel = [
			@foreach($pricelabel as $p)
				'{{$p}}',
			@endforeach
		];
		
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
	<script src="js/material/product/product.js?v=1.8"></script>

@endsection