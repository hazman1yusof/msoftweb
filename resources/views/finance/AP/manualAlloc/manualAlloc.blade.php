@extends('layouts.main')

@section('title', 'Manual Allocation')

@section('style')

div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

@endsection

@section('body')

<!-------------------------------- Search + table ---------------------->
<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
			<input id="getYear" name="getYear" type="hidden"  value="{{Carbon\Carbon::now()->year}}">

			<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  	<div class="col-md-2">
					  		<label class="control-label" for="Scol">Search By : </label>  
					  			<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		             	</div>

						 <div class="col-md-5">
					  		<label class="control-label"></label>  
							<input style="display:none" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div id="creditor_text">
								<div class='input-group'>
									<input id="creditor_search" name="creditor_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="creditor_search_hb" class="help-block"></span>
							</div>

							<div id="actdate_text" class="form-inline" style="display:none">
								FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="actdate_search">SEARCH</button>
							</div>
							
						</div>
		            </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

			</div>
			</fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display:none">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>
		 
		<div class="panel panel-default">
		    <div class="panel-heading">Manual Allocation Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div> 
          
    </div>
<!-------------------------------- End Search + table ------------------>

<div id="dialogForm" title="Create Allocation">
	<form style="width:99%" id='formdata' autocomplete="off">
		
		<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
		
		<div class='col-md-12'>
			<div class='panel panel-info'>
				<div class="panel-heading">Manual Allocation</div>
					<div class="panel-body">
						<div class="form-group">
								<div class="col-md-3">
							<label class="" for="apacthdr_source">Source</label> 
									<select id="apacthdr_source" name=apacthdr_source class="form-control" data-validation="required">
										<option value="AP">Account Payable</option>
										<option value="DF">Doctor Fee</option>
									</select>
								</div>
								<div class="col-md-3">
							<label class="" for="apacthdr_trantype">Trantype</label> 
									<select id="apacthdr_trantype" name=apacthdr_trantype class="form-control" data-validation="required">
										<option value="PD">Payment Deposit</option>
										<option value="CN">Credit Note</option>
									</select>
								</div>
							<div class="col-md-3"> 
							<label class="" for="suppcode_search">Supplier</label>
								<div class='input-group'>
									<input id="suppcode_search" name="suppcode_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="suppcode_search_hb" class="help-block"></span>
							</div>
						</div>
					</div>

					<div class="panel panel-default" id="manualAllochdr_c">
						<div class="panel-body">
							<form id='formdata_manualAlloc' class='form-vertical'>
								<div class='col-md-12' style="padding:0 0 15px 0">
									<table id="manualAllochdr" class="table table-striped"></table>
									<div id="manualAllocpg"></div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class='panel panel-info' id="manualAllocdtl_c">
					<div class="panel-heading">Manual Allocation Detail</div>
						<div class="panel-body">
							<div id="fail_msg"></div>
							<form id='formdata_manualAllocdtl' class='form-vertical' style='width:99%'>
								<div class='col-md-12' style="padding:0 0 15px 0">
									<table id="manualAllocdtl" class="table table-striped"></table>
									<div id="manualAllocdtlpg"></div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

@endsection

@section('scripts')
	
	<script src="js/finance/AP/manualAlloc/manualAlloc.js?v=1.3"></script>
	
@endsection