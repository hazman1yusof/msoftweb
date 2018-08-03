@extends('layouts.main')

@section('title', 'Stock Location Enquiry')

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



		<div class="panel panel-default">
    		<div class="panel-body">
			    <div class='col-md-6' >
					<table id="details" class ="table table-bordered" > 
			        	<thead>
					      <tr>
					         <th></th>
					         <th>Quantity Movement</th>
					         <th>Value Movement</th>
					         
					      </tr>
					    </thead>

					    <tbody>
						    <tr id="details_tr1">
						    	<th scope="row">Opening</th>
					      		<td>
					      			<input id="openbalqty" name="openbalqty" type="text"class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="openbalval" name="openbalval" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						   	<tr id="details_tr2">
						      	<th scope="row">Jan</th>
						  		<td>
					      			<input id="netmvqty1" name="netmvqty1" type="text"class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval1" name="netmvval1" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						  	<tr id="details_tr3">
						      	<th scope="row">Feb</th>
					      		<td>
					      			<input id="netmvqty2" name="netmvqty2" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval2" name="netmvval2" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						    <tr id="details_tr4">
						      	<th scope="row">Mar</th>
					      		<td>
					      			<input id="netmvqty3" name="netmvqty3" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval3" name="netmvval3" type="text"class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						    <tr id="details_tr5">
						      	<th scope="row">Apr</th>
					      		<td>
					      			<input id="netmvqty4" name="netmvqty4" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval4" name="netmvval4" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						    <tr id="details_tr6">
						      	<th scope="row">May</th>
					     		<td>
					      			<input id="netmvqty5" name="netmvqty5" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval5" name="netmvval5" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>

						    <tr id="details_tr7">
						      	<th scope="row">June</th>
					      		<td>
					      			<input id="netmvqty6" name="netmvqty6" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
					      		<td>
					      			<input id="netmvval6" name="netmvval6" type="text" class="form-control input-sm" readonly="readonly">
					      		</td>
						    </tr>
						</tbody>
			        </table>
				</div>

			    <div class='col-md-6' >
					<table id="details" class ="table table-bordered" >

		            	<thead>
					      <tr>
					         <th></th>
					         <th>Quantity Movement</th>
					         <th>Value Movement</th>
					         
					      </tr>
					    </thead>

					    <tbody>
						   

						    <tr id="details_tr8">
						      	<th scope="row">Jul</th>
						      		<td>
						      			<input id="netmvqty7" name="netmvqty7" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval7" name="netmvval7" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr9">
						      	<th scope="row">Aug</th>
						      		<td>
						      			<input id="netmvqty8" name="netmvqty8" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval8" name="netmvval8" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr10">
						      	<th scope="row">Sept</th>
						      		<td>
						      			<input id="netmvqty9" name="netmvqty9" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval9" name="netmvval9" type="text"class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr11">
						      	<th scope="row">Oct</th>
						      		<td>
						      			<input id="netmvqty10" name="netmvqty10" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval10" name="netmvval10" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr12">
						      	<th scope="row">Nov</th>
						      		<td>
						      			<input id="netmvqty11" name="netmvqty11" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval11" name="netmvval11" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						     <tr id="details_tr13">
						      	<th scope="row">Dec</th>
						      		<td>
						      			<input id="netmvqty12" name="netmvqty12" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="netmvval12" name="netmvval12" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr14">
						      	<th scope="row">Accum</th>
						      		<td>
						      			<input id="accumqty" name="accumqty" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		<td>
						      			<input id="accumval" name="accumval" type="text" class="form-control input-sm" readonly="readonly">
						      		</td>
						      		
						    </tr>
						</tbody>
		            </table>
		    	</div>
			</div>
		</div>

	@endsection

@section('scripts')

	<script src="js/material/stocklocEnquiry/stocklocEnquiry.js"></script>

@endsection