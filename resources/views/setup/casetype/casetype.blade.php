@extends('layouts.main')

@section('title', 'Case Type Setup')

@section('style')

input.uppercase {
	text-transform: uppercase;
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
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="show_chggroup_div" style="display:none">
								<div class='input-group'>
									<input id="show_chggroup" seltext='false' name="show_chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
		            </div>
				</div>
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">Case Type Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Case Type</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="idno" name="idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="case_code">Case Code</label>  
						<div class="col-md-3">
							<input id="case_code" name="case_code" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						</div>
                    </div>

                    <div class="form-group">
						<label class="col-md-2 control-label" for="description">Description</label>  
						<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm uppercase" data-validation="required">
						</div>
					</div>

					<div class="form-group">
					<label class="col-md-2 control-label" for="grpcasetype">Source Type</label>
						<div class="col-md-2">
						<select id='grpcasetype' class="form-control input-sm" data-validation="required">
							<option value="" selected>Please Choose</option>
	      					<option value="DELIVERY">DELIVERY</option>
		        			<option value="REGISTER">REGISTER</option>
			    			</select>
						</div>
					</div>	

					<!-- <div class="form-group">
						<label class="col-md-2 control-label" for="lastuser">Last User</label>  
						<div class="col-md-3">
							<input id="lastuser" name="lastuser" type="text" class="form-control input-sm uppercase" rdonly>
						</div>

						<label class="col-md-2 control-label" for="lastupdate">Last Update</label>  
						<div class="col-md-3">
							<input id="lastupdate" name="lastupdate" type="text" maxlength="30" class="form-control input-sm uppercase" rdonly>
						</div>
					</div>  -->
					

					<!-- <div class="form-group">
						<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm uppercase" data-validation="required" rdonly >
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" rdonly >
						</div>
					</div>   -->
				</form>
			</div>
		</div>		
	</div>

@endsection


@section('scripts')

	<script src="js/setup/casetype/casetype.js"></script>
	
@endsection