@extends('layouts.main')

@section('title', 'GL Enquiry By Date')

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
	#TableGlmasTran td{
		padding: 5px !important;
	}
	#TableGlmasTran{
		background-color: rgba(250, 252, 246, 1);
	}
	#TableGlmasTran tr:hover{
		background-color: white !important;
	}
	#TableGlmasTran_c td:first-child,.dataTables_scrollHead th:first-child,#TableGlmasTran_c td:nth-child(2),.dataTables_scrollHead th:nth-child(2){
		border-top-color:white !important;
		border-top-style:solid !important;
		border-top-width:1px !important;
		border-left-color:white;
		border-left-style:solid;
		border-left-width:1px;
		cursor:pointer;
		background-color: white;
	}
	#TableGlmasTran_c tr:hover td:first-child,#TableGlmasTran_c tr:hover td:nth-child(2){
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#TableGlmasdtl td:nth-child(3),#TableGlmasdtl th:nth-child(3){
		text-align:right;padding-right: 15px;
	}
	.textalignright,#TableGlmasTran_filter { text-align:right !important; }
	.textalignright div { padding-right: 5px; }
	.numericCol{
		text-align : right;
	}
	.bg-info{
		background-color: white;
	}

	input.uppercase {
  		text-transform: uppercase;
	}

@endsection

@section('body')

	<!-------------------------------- Search + table ---------------------->
	<input id="jobdone" name="jobdone" type="hidden" value="{{$jobdone}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class='row'>
		<div class='col-md-12' style="padding:0 0 15px 0;">
			<form id="searchform" name="searchform" class="col-md-12"> 
			  <div class="col-md-5">
			  	<label class="control-label" for="glaccount">GL Account</label>
	  			<div class='input-group'>
					<input id="glaccount" name="glaccount" type="text" class="form-control input-sm uppercase" autocomplete="off" data-validation="required"/>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
					<span class="help-block"></span>
			  </div>

			  <div class="col-md-2">
			  	<label class="control-label" for="year">From</label>  
				<input id="fromdate" name="fromdate" type="date" class="form-control input-sm uppercase" autocomplete="off" data-validation="required" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-01')}}" />
              </div>


			  <div class="col-md-2">
			  	<label class="control-label" for="year">to</label>  
				<input id="todate" name="todate" type="date" class="form-control input-sm uppercase" autocomplete="off" data-validation="required" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-t')}}" />
              </div>

			  <div class="col-md-1">
				<button type="button" id="search" class="btn btn-primary" style="position:absolute;top:17px">Search</button>
              </div>
              
			  <div class="col-md-1">
			  	@if($jobdone == 'false')
              		<button type="button" class="btn btn-primary" id="print_process" style="position:absolute;top:17px">Processing.. <i class="fa fa-refresh fa-spin fa-fw"></button disabled="disabled">
			  	@else
              		<button type="button" class="btn btn-primary" id="print_process" style="position:absolute;top:17px"> Process Excel </button>
			  	@endif
              </div>

	            <a class="pull-right pointer text-primary" style="position: absolute;
				    right: 40px;
				    bottom: -33px;
				    z-index: 1000;" id="pdfgen1" target="_blank">
			    <span class="fa fa-print"></span> Download last Excel 
				</a>

            </form>
            <br/>
		</div>

		<div class='panel panel-default'>
			<div class='panel-body'>
				<div id='TableGlmasTran_c' class='col-xs-12 modalx'>
					<table class="table table-hover  table-bordered" id='TableGlmasTran'>
					<thead>
						<tr>
							<th> </th>
							<th> </th>
							<th>Src</th>
							<th>TT</th>
							<th>No</th>
							<th>Post Date</th>
							<th>Description</th>
							<th>Reference</th>
							<th>Account Code</th>
							<th>DR Amount</th>
							<th>CR Amount</th>
							<th>id</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>

	<div id="dialogForm" title="Viewing Detail"></div>

	<div id="open_detail_dialog" title="Detail View" style="padding:0">
		<div class='col-md-12' style="padding:0" >
			<iframe id='open_detail_iframe' src='' style="height: calc(100vh - 100px);width: 100%; border: none;"></iframe>
			<!-- guna nama 'open_detail_iframe' dialog utk semua detail view, utk tak error dkt parent_close_disabled -->
		</div>
	</div>


	@endsection


@section('scripts')

	<script src="js/finance/GL/acctenq_date/acctenq_date.js?v=1.3"></script>
	<script src="plugins/datatables/js/jquery.datatables.min.js"></script>
	
@endsection