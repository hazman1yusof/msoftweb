@extends('layouts.main')

@section('title', 'Bed Management Setup')

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

input.uppercase {
	text-transform: uppercase;
}

@endsection

@section('body')

	<!--***************************** Search + table ******************-->
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
		    <div class="panel-heading">Bed Management Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel-group">
			<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
					<b>Name: <span id="name_show"></span></b><br>
						Bed No: <span id="bednum_show"></span>
						<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
						<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
						<br>
							<button type="button" class="btn btn-info">Transfer</button>
						</br>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Bed Occupancy Detail</h5>
						</div>		
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
    </div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')

	<script src="js/setup/bedmanagement/bedmanagement.js"></script>
	
@endsection