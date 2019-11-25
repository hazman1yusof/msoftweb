@extends('layouts.main')

@section('title', 'Authorization Detail')

@section('body')


	<div class='row'>
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">

		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
						  	<label class="control-label" for="Scol">Search By : </label>  
						  		<select id='Scol' class="form-control input-sm"></select>
			            </div>
	                    <div class="col-md-5">
	                        <label class="control-label"></label>  
							<input id="searchText" name="searchText" type="text" class="form-control input-sm" autocomplete="off"/>
				  		</div>
			        </div>
				</div>
			</fieldset> 
		</form>

		<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    				<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>
		

	@endsection

@section('scripts')

	<script src="js/material/AuthorizationDetail/authorizationDtl.js"></script>

@endsection