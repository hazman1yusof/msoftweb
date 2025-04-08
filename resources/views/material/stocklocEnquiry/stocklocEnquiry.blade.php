@extends('layouts.main')

@section('title', 'Stock Location Enquiry')

@section('body')


	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="{{ $lastperiod->year }}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div  id="show_dept" style="display:none">
								<div class='input-group'>
									<input id="dept_search" name="dept_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="dept_search_hb" class="help-block"></span>
							</div>
						</div>

						<!-- <div class="col-md-1" style="padding-left: 0px;">
							<div id="div_deptsearch" style="padding-left: 0px;padding-right: 40px;display:none">
								<label class="control-label"></label>
								<a class='form-control btn btn-primary' id="btn_deptsearch"><span class='fa fa-ellipsis-h'></span></a>
							</div>
						</div> -->

						<div class="col-md-2">
							<label class="control-label" for="Scol">Year : </label>  
					  		<select id='Syear' name='Syear' class="form-control input-sm" tabindex="1"></select>
		              	</div>
						
						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
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
					      			<input id="openbalqty" name="openbalqty" type="text"class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="openbalval" name="openbalval" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						   	<tr id="details_tr2">
						      	<th scope="row">Jan</th>
						  		<td>
					      			<input id="netmvqty1" name="netmvqty1" type="text"class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval1" name="netmvval1" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						  	<tr id="details_tr3">
						      	<th scope="row">Feb</th>
					      		<td>
					      			<input id="netmvqty2" name="netmvqty2" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval2" name="netmvval2" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						    <tr id="details_tr4">
						      	<th scope="row">Mar</th>
					      		<td>
					      			<input id="netmvqty3" name="netmvqty3" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval3" name="netmvval3" type="text"class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						    <tr id="details_tr5">
						      	<th scope="row">Apr</th>
					      		<td>
					      			<input id="netmvqty4" name="netmvqty4" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval4" name="netmvval4" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						    <tr id="details_tr6">
						      	<th scope="row">May</th>
					     		<td>
					      			<input id="netmvqty5" name="netmvqty5" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval5" name="netmvval5" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
						    </tr>

						    <tr id="details_tr7">
						      	<th scope="row">June</th>
					      		<td>
					      			<input id="netmvqty6" name="netmvqty6" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
					      		</td>
					      		<td>
					      			<input id="netmvval6" name="netmvval6" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right"> 
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
						      			<input id="netmvqty7" name="netmvqty7" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval7" name="netmvval7" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr9">
						      	<th scope="row">Aug</th>
						      		<td>
						      			<input id="netmvqty8" name="netmvqty8" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval8" name="netmvval8" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr10">
						      	<th scope="row">Sept</th>
						      		<td>
						      			<input id="netmvqty9" name="netmvqty9" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval9" name="netmvval9" type="text"class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr11">
						      	<th scope="row">Oct</th>
						      		<td>
						      			<input id="netmvqty10" name="netmvqty10" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval10" name="netmvval10" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr12">
						      	<th scope="row">Nov</th>
						      		<td>
						      			<input id="netmvqty11" name="netmvqty11" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval11" name="netmvval11" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						     <tr id="details_tr13">
						      	<th scope="row">Dec</th>
						      		<td>
						      			<input id="netmvqty12" name="netmvqty12" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="netmvval12" name="netmvval12" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>

						    <tr id="details_tr14">
						      	<th scope="row">Accum</th>
						      		<td>
						      			<input id="accumqty" name="accumqty" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		<td>
						      			<input id="accumval" name="accumval" type="text" class="form-control input-sm" readonly="readonly" style="text-align: right">
						      		</td>
						      		
						    </tr>
						</tbody>
		            </table>
		    	</div>
			</div>
		</div>

	@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',4);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}

				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
			
		});
	</script>
	<script src="js/material/stocklocEnquiry/stocklocEnquiry.js?v=1.1"></script>

@endsection