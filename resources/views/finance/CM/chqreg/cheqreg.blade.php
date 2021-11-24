@extends('layouts.main')

@section('title', 'Cheque Register')

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

.collapsed ~ .panel-body {
  padding: 0;
}

.clearfix {
	overflow: auto;
}

@endsection

@section('body')


	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					<div style="position:absolute;bottom:0;right:0;">
						
					</div>
				</div>

			 </fieldset> 
		</form>

    	<div class="panel panel-default">
		<div class="panel-heading">Cheque Register Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel panel-default" id="gridCheqRegDetail_c" style="position: relative;">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel1">
				<b><span id="bankname"></span></b>
				<i class="fa fa-angle-double-up" style="font-size:24px"></i>
    			<i class="fa fa-angle-double-down" style="font-size:24px"></i>
    			<b><span id="bankname"></span></b><br>Cheque Detail <br> </div>
    			
    			<div style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;color: red">
						<p id="p_error"></p>
					</div>
				<div id="jqGrid3_panel1" class="panel-collapse collapse">
					<div class="panel-body">
					    <div class='col-md-12' style="padding:0 0 15px 0">
			            	<table id="gridCheqRegDetail" class="table table-striped"></table>
			            		<div id="jqGridPager2"></div>
			        	</div>
					</div>
				</div>	
		</div>

    </div>

	<!-------------------------------- End Search + table ------------------>

	@endsection

@section('scripts')

	<script src="js/finance/CM/chqreg/cheqreg.js"></script>

@endsection