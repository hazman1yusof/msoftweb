@extends('layouts.main')

@section('title', 'Stock Location')

@section('body')

	<div class='row'>

		<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">

		<form class='form-horizontal' style='width:99%' id='formdataSearch'>
		<!-- <div class='col-md-12' style="padding:0 0 15px 0;"> -->
			<div class="form-group"> 
			  	<div class="col-md-3">
			  		<label class="control-label" for="itemcode">Item Code</label>  
	  				<div class='input-group'>
						<input id="itemcodeS" name="itemcode" type="text" class="form-control input-sm text-uppercase"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  				</div>
					<span class="help-block"></span>
			  	</div>

			  	<div class="col-md-2">
			  		<label class="control-label" for="uomcode">UOM Code</label>  
			  			<div class='input-group'>
						<input id="uomcodeS" name="uomcode" type="text" class="form-control input-sm text-uppercase"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  				</div>
					<span class="help-block"></span>
              	</div>

              	<div class="col-md-1">
              	 	<label class="control-label" for="year">Year</label>
				  		<select id="select_year" name="select_year" class="form-control input-sm">
				  			@foreach($year as $obj)
							<option value='{{ $obj->year }}'>{{ $obj->year }}</option>
							@endforeach
				  		</select>
				</div> 

			  	<div class="col-md-1">
					<button type="button" id="search" class="btn btn-primary" style="position:absolute;top:17px">Search</button>
					<button type="button" id="cancel" class="btn btn-primary" style="position:absolute;top:17px;left:90px">Cancel</button>
              	</div>
            </div>
		<!-- </div> -->
		</form>
		
    	<div class="panel panel-default">
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
				
				{{ csrf_field() }}
				<input id="idno" name="idno" type="hidden">
				<input id="uomcode" name="uomcode" type="hidden">
				<input id="qtyonhand" name="qtyonhand" type="hidden" value="0.00">
				<input id="year" name="year" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">
				<input id="unit" name="unit" type="hidden"  value="{{ Session::get('unit') }}">


				<div class="form-group">

				  	<label class="col-md-2 control-label" for="deptcode">Dept. Code</label>  
				  	<div class="col-md-3">
						  <div class='input-group'>
							<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  </div>
						  <span class="help-block"></span>
					</div>

					<label class="col-md-2 control-label" for="rackno">Itemcode</label>  
					<div class="col-md-3">
						  <div>
						  	<input id="itemcode" name="itemcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" rdonly>
						  </div>
						  <span class="help-block"></span>
					</div>
                </div>
                  
                <div class="form-group">
				 	<label class="col-md-2 control-label" for="stocktxntype">Transaction Type</label>  
				  	<div class="col-md-3">
				    	<label class="radio-inline"><input type="radio" name="stocktxntype" value='TR'>Stock</label>
                    	<label class="radio-inline"><input type="radio" name="stocktxntype" value='IS'>Expenses</label>				
                	</div>

					<label class="col-md-2 control-label" for="uomcode">UOM Code</label>  
					<div class="col-md-3">
						  <div>
						  	<input id="uomcode" name="uomcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" rdonly>
						  </div>
						  <span class="help-block"></span>
					</div>
				</div>

				<!-- <div class="form-group" >
				 	<label class="col-md-2 control-label" for="frozen">Freeze</label>  
				  	<div class="col-md-3">
					  	<label class="radio-inline"><input type="radio" name="frozen" value='1' disabled>Yes</label>
						<label class="radio-inline"><input type="radio" name="frozen" value='0' disabled>No</label>			
                	</div>
				</div> -->
                  
                  <div class="form-group" hidden = 'true'>
				  <label class="col-md-2 control-label" for="disptype">Dispensing Type</label>  
				   <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="disptype" id="TRDS" value='DS'>DS</label>
                    <label class="radio-inline"><input type="radio" name="disptype" id="ISDS1" value='DS1'>DS1</label>				
                </div>
				</div>
                  
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="rackno">Rack No</label>  
				  <div class="col-md-3">
				  	<input id="rackno" name="rackno" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>
				
				  <label class="col-md-2 control-label" for="bincode">Bin Code</label>  
				  <div class="col-md-3">
				  	<input id="bincode" name="bincode" type="text" maxlength="30" class="form-control input-sm text-uppercase">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="minqty">Maximum Stock Qty</label>  
                  <div class="col-md-3">
				  	<input id="minqty" name="minqty" type="text" class="form-control input-sm" >
				  </div>
                 
				  <label class="col-md-2 control-label" for="maxqty">Minimum Stock Qty</label>  
                  <div class="col-md-3">
				  	<input id="maxqty" name="maxqty" type="text" class="form-control input-sm" >
				  </div>
                </div>
                  
                <div class="form-group">
				  <label class="col-md-2 control-label" for="reordlevel">Reorder Level</label>  
                  <div class="col-md-3">
				  	<input id="reordlevel" name="reordlevel" type="text" class="form-control input-sm" >
				  </div>
                 
				  <label class="col-md-2 control-label" for="reordqty">Reorder Quantity</label>  
                  <div class="col-md-3">
				  	<input id="reordqty" name="reordqty" type="text" class="form-control input-sm">
				  </div>
                </div>

                <div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>
			</form>
		</div>

	@endsection

@section('scripts')

	<script src="js/material/Stock Location/stockLoc.js"></script>

@endsection