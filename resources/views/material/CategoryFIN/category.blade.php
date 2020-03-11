@extends('layouts.main')

@section('title', 'Category Finance')

@section('style')

input.uppercase {
	text-transform: uppercase;
}
@endsection

@section('body')

<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">


				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">
						</div>

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

		 <div class="panel panel-default">
		    <div class="panel-heading">Category Finance Setup Header</div>
		    <div class="panel-body">

		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped">
            			<input type="hidden" name="idno">
						<input id="source2" name="source" type="text" value="{{$_GET['source']}}">	
						<input id="cattype" name="cattype" type="text" value="{{$_GET['cattype']}}">
							
						<input id="stockacct" name="stockacct" type="hidden">
						<input id="cosacct" name="cosacct" type="hidden">
						<input id="adjacct" name="adjacct" type="hidden">
						<input id="woffacct" name="woffacct" type="hidden">
						<input id="loanacct" name="loanacct" type="hidden">
            			
            		</table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>
    </div>
		
		
	@endsection

@section('scripts')

	<script src="js/material/CategoryFIN/category.js"></script>

@endsection