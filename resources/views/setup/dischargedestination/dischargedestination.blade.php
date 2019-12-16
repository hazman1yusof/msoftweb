@extends('layouts.main')

@section('title', 'Discharge Destination Setup')

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
		    <div class="panel-heading">Discharge Destination Header</div>
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
			<div class="panel-heading">Discharge Destination</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="idno" name="idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="code">Discharge Code</label>  
						<div class="col-md-3">
							<input id="code" name="code" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						</div>
                    </div>

                    <div class="form-group">
						<label class="col-md-2 control-label" for="discharge">Description</label>  
						<div class="col-md-3">
							<input id="discharge" name="discharge" type="text" class="form-control input-sm uppercase" data-validation="required">
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

	<script src="js/setup/dischargedestination/dischargedestination.js"></script>
	
@endsection