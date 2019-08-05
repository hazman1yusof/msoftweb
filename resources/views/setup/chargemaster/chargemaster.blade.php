@extends('layouts.main')

@section('title', 'Charge Master Setup')

@section('style')

.fa-angle-double-down {
	float: right;
}

.clearfix {
	overflow: auto;
}

@endsection

@section('body')

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="show_chggroup" style="display:none">
								<div class='input-group'>
									<input id="chggroup" name="chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_chgtype" style="display:none">
								<div class='input-group'>
									<input id="chgtype" name="chgtype" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

					  	<div class="col-md-1" id="div_chggroup" style="padding-left: 30px;padding-right: 30px;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_chggroup"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
					  	<div class="col-md-1" id="div_chgtype" style="padding-left: 30px;padding-right: 30px;display:none">
							<label class="control-label"></label>
							<a class='form-control btn btn-primary' id="btn_chgtype"><span class='fa fa-ellipsis-h'></span></a>
					  	</div>
		            </div>
				</div>

				<!-- <div class="col-md-2">
					<label class="control-label" for="Status">Status</label>  
					<select id="Status" name="Status" class="form-control input-sm">
						<option value="All" selected>ALL</option>
						<option value="Open">OPEN</option>
						<option value="Confirmed">CONFIRMED</option>
						<option value="Posted">POSTED</option>
						<option value="Cancelled">CANCELLED</option>
					</select>
	            </div>

	            <div class="col-md-2">
			  		<label class="control-label" for="trandept">Purchase Dept</label> 
					<select id='trandept' class="form-control input-sm">
				      	<option value="All" selected>ALL</option>
					</select>
				</div>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button>
				</div> -->
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">Charge Master Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

        <!-- <div class='click_row'>
        	<label class="control-label">Record No</label>
        	<span id="recnodepan" style="display: block;">&nbsp</span>
        </div>
        <div class='click_row'>
			<label class="control-label">Purchase Dept</label>
        	<span id="prdeptdepan" style="display: block;">&nbsp</span>
        </div> -->

	    <!-- <div class="panel panel-default">
		    <div class="panel-heading">Charge Price</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
	            	<table id="jqGrid3" class="table table-striped"></table>
	            	<div id="jqGridPager3"></div>
	    		</div>
		    </div>
		</div>  -->

		<!-- accordion sample -->
		<!-- <div class="panel panel-default">
			<div class="accordion" id="accordionExample">
				<div class="card">
					<div class="card-header" id="headingThree">
						<h2 class="mb-0">
							<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Collapsible Group Item #3
							</button>
						</h2>
					</div>
					<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
						<div class="card-body">
							Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
						</div>
					</div>
				</div>
			</div>
		</div> -->
		<!-- accordion sample -->

		<!-- <div class="panel panel-default">
			<div class="accordion" id="grid3">
				<div class="card">
					<div class="card-header panel-heading" id="JqGrid3">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseJqGrid3" aria-expanded="false" aria-controls="collapseJqGrid3">
							Charge Price
						</button>
					</div>
					<div id="collapseJqGrid3" class="collapse" aria-labelledby="JqGrid3" data-parent="#grid3">
						<div class="panel-body card-body">
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid3" class="table table-striped"></table>
								<div id="jqGridPager3"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>  -->

		<!-- <div class="panel panel-default">
			<div class="panel-heading" id="JqGrid3">
				<button class="btn btn-link panel-title collapsed" type="button" data-toggle="collapse" data-target="#collapseJqGrid3" aria-expanded="false" aria-controls="collapseJqGrid3">
					Charge Price 
				</button>
			</div>
			<div id="collapseJqGrid3" class="collapse" aria-labelledby="JqGrid3">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>
		</div>  -->

		<!-- accordion2 sample -->
		<!-- <div class="panel-group">
  			<div class="panel panel-default">
    			<div class="panel-heading">
      				<h4 class="panel-title">
      					<a data-toggle="collapse" href="#collapse1">Collapsible panel</a>
      				</h4>
    			</div>
    			<div id="collapse1" class="panel-collapse collapse">
					<div class="panel-body">Panel Body</div>
					<div class="panel-footer">Panel Footer</div>
    			</div>
  			</div>
		</div> -->
		<!-- accordion2 sample -->

		<div class="panel-group">
  			<div class="panel panel-default">
    			<div class="panel-heading clearfix" data-toggle="collapse" href="#collapse1">
					Charge Price <i class="fa fa-angle-double-down" style="font-size:24px"></i> 
    			</div>
    			<div id="collapse1" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">
							<table id="jqGrid3" class="table table-striped"></table>
							<div id="jqGridPager3"></div>
						</div>
					</div>
    			</div>
  			</div>
		</div>

    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Master</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="cm_idno" name="cm_idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgcode">Charge Code</label>  
						<div class="col-md-3">
							<input id="cm_chgcode" name="cm_chgcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
						</div>

						<label class="col-md-2 control-label" for="cm_description">Description</label>  
						<div class="col-md-3">
							<input id="cm_description" name="cm_description" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>   

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_barcode">Bar Code</label>  
						<div class="col-md-3">
							<input id="cm_barcode" name="cm_barcode" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="cm_generic">Generic</label>  
						<div class="col-md-3">
							<input id="cm_generic" name="cm_generic" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<!-- <hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgclass">Class Code</label>  
						<div class="col-md-3">
							<input id="cm_chgclass" name="cm_chgclass" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="control-label col-md-2" for="cm_constype">Consultation Type</label>  
						<div class="col-md-3">
							<select class="form-control col-md-4" id='cm_constype' name='cm_constype'>
								<option value='1'>Anaestetics</option>
								<option value='2'>Consultation</option>
								<option value='3'>Surgeon</option>
							</select> 
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chggroup">Charge Group</label>  
						<div class="col-md-3">
							<input id="cm_chggroup" name="cm_chggroup" type="text" class="form-control input-sm" data-validation="number, required">
						</div>

						<label class="col-md-2 control-label" for="cm_chgtype">Charge Type</label>  
						<div class="col-md-3">
							<input id="cm_chgtype" name="cm_chgtype" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="UOM">UOM</label>  
						<div class="col-md-3">
							<input id="UOM" name="UOM" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="invitem">Inventory Item</label>  
						<div class="col-md-3">
							<input id="invitem" name="invitem" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="Packing">Packing</label>  
						<div class="col-md-3">
							<input id="Packing" name="Packing" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="cm_recstatus">Record Status</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='A' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='D' >Deactive</label>
						</div>
					</div> -->

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgclass">Class Code</label>  
						<div class="col-md-3">
							<input id="cm_chgclass" name="cm_chgclass" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="control-label col-md-2" for="cm_constype">Consultation Type</label>  
						<div class="col-md-3">
							<select class="form-control col-md-4" id='cm_constype' name='cm_constype'>
								<option value='1'>Anaestetics</option>
								<option value='2'>Consultation</option>
								<option value='3'>Surgeon</option>
							</select> 
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chggroup">Charge Group</label>  
						<div class="col-md-3">
							<input id="cm_chggroup" name="cm_chggroup" type="text" class="form-control input-sm" data-validation="number, required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_chgtype">Charge Type</label>  
						<div class="col-md-3">
							<input id="cm_chgtype" name="cm_chgtype" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="cm_recstatus">Record Status</label>  
						<div class="col-md-3">
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='A' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="cm_recstatus" value='D' >Deactive</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="UOM">UOM</label>  
						<div class="col-md-2">
							<input id="UOM" name="UOM" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="invitem">Inventory Item</label>  
						<div class="col-md-2">
							<input id="invitem" name="invitem" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="Packing">Packing</label>  
						<div class="col-md-2">
							<input id="Packing" name="Packing" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="druggrpcode">Drug Group Code</label>  
						<div class="col-md-3">
							<input id="druggrpcode" name="druggrpcode" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="subgrp">Sub Group</label>  
						<div class="col-md-3">
							<input id="subgrp" name="subgrp" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="invgrp">Inv. Group</label>  
						<div class="col-md-3">
							<input id="invgrp" name="invgrp" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="doccode">Doctor Code</label>  
						<div class="col-md-3">
							<input id="doccode" name="doccode" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="rvndeptcode">Revenue Dept. Code</label>  
						<div class="col-md-3">
							<input id="rvndeptcode" name="rvndeptcode" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="seqno">Sequence No</label>  
						<div class="col-md-3">
							<input id="seqno" name="seqno" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="stockcode">Stock Code</label>  
						<div class="col-md-3">
							<input id="stockcode" name="stockcode" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="ipacccode">IP Acc. Code</label>  
						<div class="col-md-3">
							<input id="ipacccode" name="ipacccode" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="opacccode">OP Acc. Code</label>  
						<div class="col-md-3">
							<input id="opacccode" name="opacccode" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="appracccode">Appr Acc. Code</label>  
						<div class="col-md-3">
							<input id="appracccode" name="appracccode" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="apprcostcode">Appr Cost Code</label>  
						<div class="col-md-3">
							<input id="apprcostcode" name="apprcostcode" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<hr>

					<div class="form-group">
						<label class="col-md-2 control-label" for="priceactive">Price Active</label>  
						<div class="col-md-3">
							<input id="priceactive" name="priceactive" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="Queue">Queue</label>  
						<div class="col-md-3">
							<input id="Queue" name="Queue" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="priceovr">Price Overwrite</label>  
						<div class="col-md-3">
							<input id="priceovr" name="priceovr" type="text" class="form-control input-sm" data-validation="required">
						</div>

						<label class="col-md-2 control-label" for="Doctor">Doctor</label>  
						<div class="col-md-3">
							<input id="Doctor" name="Doctor" type="text" class="form-control input-sm" data-validation="required">
						</div>
					</div>

					<hr>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_upduser">Last User</label>  
						<div class="col-md-3">
							<input id="cm_upduser" name="cm_upduser" type="text" class="form-control input-sm" rdonly>
						</div>

						<label class="col-md-2 control-label" for="cm_upddate">Last Update</label>  
						<div class="col-md-3">
							<input id="cm_upddate" name="cm_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
						</div>
					</div> 
					

					<div class="form-group">
						<label class="col-md-2 control-label" for="cm_lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
							<input id="cm_lastcomputerid" name="cm_lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly>
						</div>

						<label class="col-md-2 control-label" for="cm_lastipaddress">IP Address</label>  
						<div class="col-md-3">
							<input id="cm_lastipaddress" name="cm_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly>
						</div>
					</div> 
				</form>
			</div>
		</div>
			
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Price Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<input id="gstpercent" name="gstpercent" type="hidden">
					<input id="convfactoruomcodetrdept" name="convfactoruomcodetrdept" type="hidden" value='1'>
					<input id="convfactoruomcoderecv" name="convfactoruomcoderecv" type="hidden" value='1'>

					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			</div>
		</div>	
	</div>

@endsection


@section('scripts')

	<script src="js/setup/chargemaster/chargemaster.js"></script>
	
@endsection