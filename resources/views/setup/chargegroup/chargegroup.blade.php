@extends('layouts.main')

@section('title', 'Charge Group Setup')

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

							<!-- <div  id="show_grpcode" style="display:none">
								<div class='input-group'>
									<input id="grpcode" name="grpcode" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div> -->

							<!-- <div  id="show_chgtype" style="display:none">
								<div class='input-group'>
									<input id="chgtype" name="chgtype" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div> -->
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
		    <div class="panel-heading">Charge Group Header</div>
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
	    		</div>'
		    </div>
		</div>  -->

    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Group
					</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							{{ csrf_field() }}
							<input id="idno" name="idno" type="hidden">
						

							<div class="form-group">
							  	<label class="col-md-2 control-label" for="grpcode">Group Code</label>  
			                    <div class="col-md-3">
			                    	<input id="grpcode" name="grpcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
			                    </div>
							
			                	<label class="col-md-2 control-label" for="description">Description</label>  
			                    <div class="col-md-3">
			                    	<input id="description" name="description" type="text" class="form-control input-sm" data-validation="number, required">
			                    </div>
							</div>
			                
			                <!-- <div class="form-group">
			                	<label class="col-md-2 control-label" for="classlevel">Class Level</label>  
			                	<div class="col-md-6">
			                		<input id="classlevel" name="classlevel" type="text" class="form-control input-sm" data-validation="required">
			                	</div>
							</div>     -->
			                
							<div class="form-group">
								<label class="col-md-2 control-label" for="lastuser">Last User</label>  
								<div class="col-md-3">
									<input id="lastuser" name="lastuser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
								</div>

								<label class="col-md-2 control-label" for="lastupdate">Last Update</label>  
								<div class="col-md-3">
									<input id="lastupdate" name="lastupdate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
								</div>
							</div> 

                            <div class="form-group">
			                	<label class="col-md-2 control-label" for="seqno">Sequence Number</label>  
			                	<div class="col-md-6">
			                		<input id="seqno" name="seqno" type="text" class="form-control input-sm" data-validation="required">
			                	</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-md-2 control-label" for="seqno">Sequence Number</label>  
								<div class="col-md-3">
									<input id="seqno" name="seqno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
								</div>

								<label class="col-md-2 control-label" for="chggroup">Charge Group</label>  
								<div class="col-md-3">
									<input id="chggroup" name="chggroup" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
								</div>
							</div>   -->

							<div class="form-group">
								<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
								<div class="col-md-3">
									<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" rdonly >
								</div>

								<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
								<div class="col-md-3">
									<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly >
								</div>
							</div>  
					</form>
				</div>
			</div>
			
			<!-- <div class='panel panel-info'>
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
						<div class="noti" style="font-size: bold; color: red"><ol></ol>
						</div>
					</div>
			</div>		 -->
			
		</div>

@endsection


@section('scripts')

	<script src="js/setup/chargegroup/chargegroup.js"></script>
	
@endsection