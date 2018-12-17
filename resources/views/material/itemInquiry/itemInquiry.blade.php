@extends('layouts.main')

@section('title', 'Item Enquiry')
@section('style')
	.num{
		width:20px;
	}
	.mybtn{
		float: right;
		display: none;
	}
	.bg-primary .mybtn{
		display:block;
	}
	.dataTables_scroll table{
		background-color: rgba(221, 221, 221, 0.3);
	}
	#TableDetailMovement td:first-child,.dataTables_scrollHead th:first-child{
		border-top-color:white !important;
		border-top-style:solid !important;
		border-top-width:1px !important;
		border-left-color:white;
		border-left-style:solid;
		border-left-width:1px;
		border-bottom-color:white;
		border-bottom-style:solid;
		border-bottom-width:1px;
		cursor:pointer;
		background-color: white;
	}
	#TableDetailMovement tr:hover td:first-child{
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#detailMovement{
		margin: 0.2%;position: absolute;right: 20px; bottom:5px;
	}
	
@endsection

@section('body')

	<div class='row'>
		<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">

		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass"  style="padding:0 0 0 15px">
					<div name='Scol' style='font-weight:bold'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

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

 		<div class="panel panel-default">
		    <div class="panel-body">

		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
        			<div id="jqGridPager3">
        			</div>
            		<button type="button" id='detailMovement' class='btn btn-info btn-sm'>Detail Movement</button>	
        		</div>

        	</div>

		</div>
    </div>

<!---*********************************** VIEW DETAIL MOVEMENT ************************************************** -->
	<div id="detailMovementDialog" title="View Detail Movement" >
		<div id='detailMovement_c' class=''>
			<table class="table table-hover  table-bordered" id='TableDetailMovement'>
				<thead>
					<tr>
						<th> </th>
						<th>Transaction Date</th>
						<th>Trantype</th>
						<th>Transaction Description</th>
						<th>Qty In</th>
						<th>Qty Out</th>
						<th>Balance Quantity</th>
						<th>Unit Cost</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

@endsection

@section('scripts')

	<script src="js/material/itemInquiry/itemInquiry.js"></script>
	<script src="plugins/datatables/js/jquery.datatables.min.js"></script>

@endsection