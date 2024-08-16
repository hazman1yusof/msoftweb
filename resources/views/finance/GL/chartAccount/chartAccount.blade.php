@extends('layouts.main')

@section('title', 'Chart Account')

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
	#TableGlmasTran{
		background-color: rgba(250, 252, 246, 1);
	}
	#TableGlmasTran tr:hover{
		background-color: white !important;
	}
	#TableGlmasTran_c td:first-child,.dataTables_scrollHead th:first-child{
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
	#TableGlmasTran_c tr:hover td:first-child{
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#TableGlmasdtl td:nth-child(2),#TableGlmasdtl th:nth-child(2){
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
	<div class='row'>
		<div class='col-md-12' style="padding:0 0 15px 0;">
			<div class="form-group"> 
			  <div class="col-md-7">
			  	<label class="control-label" for="glaccountSearch">GL Account</label>  
	  			<div class='input-group'>
					<input id="glaccountSearch" name="glaccountSearch" type="text" class="form-control input-sm uppercase" autocomplete="off"/>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
					<span class="help-block"></span>
			  </div>
			  <div class="col-md-2">
			  	<label class="control-label" for="year">Year</label>  
			  	<select id='year' name='year' class="form-control input-sm"></select>
              </div>
			  <div class="col-md-1">
				<button type="button" id="search" class="btn btn-primary" style="position:absolute;top:17px">Search</button>
              </div>
             </div>
		</div>

    	<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    					<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>

		<div class='panel panel-default'>
            <div class="panel-body">
            <table id="addChartAcc" class ="table table-bordered">
	                	<thead>
					      <tr>
					         <th>Period</th>
					         <th>Actual</th>
					         <th>Budget</th>
					         <th>Variance</th>
					      </tr>
					    </thead>
					    <tbody>
						    <tr id="td1">
						    	<th scope="row">1</th>
						      		<td>
						      			<input id="actamount1" name="actamount1" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="bdgamount1" name="bdgamount1" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount1" name="varamount1" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						   	<tr id="td2">
						      	<th scope="row">2</th>
							  		<td>
						      			<input id="actamount2" name="actamount2" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount2" name="bdgamount2" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount2" name="varamount2" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						  	<tr id="td3">
						      <th scope="row">3</th>
                                    <td>
						      		    <input id="actamount3" name="actamount3" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount3" name="bdgamount3" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount3" name="varamount3" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td4">
						      	<th scope="row">4</th>
                                    <td>
						      			<input id="actamount4" name="actamount4" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount4" name="bdgamount4" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount4" name="varamount4" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td5">
						      	<th scope="row">5</th>
                                    <td>
						      			<input id="actamount5" name="actamount5" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount5" name="bdgamount5" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount5" name="varamount5" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td6">
						      	<th scope="row">6</th>
                                    <td>
						      			<input id="actamount6" name="actamount6" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount6" name="bdgamount6" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount6" name="varamount6" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td7">
						      	<th scope="row">7</th>
                                    <td>
						      			<input id="actamount7" name="actamount7" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount7" name="bdgamount7" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount7" name="varamount7" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td8">
						      	<th scope="row">8</th>
                                    <td>
						      			<input id="actamount8" name="actamount8" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount8" name="bdgamount8" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount8" name="varamount8" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td9">
						      	<th scope="row">9</th>
                                    <td>
						      			<input id="actamount9" name="actamount9" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount9" name="bdgamount9" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount9" name="varamount9" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td10">
						      	<th scope="row">10</th>
                                    <td>
						      			<input id="actamount10" name="actamount10" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount10" name="bdgamount10" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount10" name="varamount10" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td11">
						      	<th scope="row">11</th>
                                    <td>
						      			<input id="actamount11" name="actamount11" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount11" name="bdgamount11" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount11" name="varamount11" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>

						    <tr id="td12">
						      	<th scope="row">12</th>
                                    <td>
						      			<input id="actamount12" name="actamount12" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="bdgamount12" name="bdgamount12" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="varamount12" name="varamount12" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>
                            <tr id="td13">
						      	<th scope="row">Total</th>
                                    <td>
						      			<input id="totalActual" name="totalActual" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="totalBdg" name="totalBdg" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
                                        <input id="totalVar" name="totalVar" type="text" class="form-control" data-validation="required" rdonly>
						      		</td>
						    </tr>
						</tbody>
	                </table>
            </div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
<!-- 
	<div id="dialogForm" title="Viewing Detail">
		@include('finance.GL.glmasdtl.paymentVoucher_glmasdtl')
		@include('finance.GL.glmasdtl.SalesOrder_glmasdtl')
	</div> -->

@endsection

@section('scripts')

	<script src="js/finance/GL/chartAccount/chartAccount.js"></script>
	<script src="plugins/datatables/js/jquery.datatables.min.js"></script>
	
@endsection