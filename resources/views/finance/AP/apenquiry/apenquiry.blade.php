@extends('layouts.main')

@section('title', 'AP Enquiry')

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
	

	input.uppercase {
  		text-transform: uppercase;
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
                </fieldset> 
		</form>    

            <div class="panel panel-default">
                <div class="panel-heading">AP Enquiry Header</div>
                    <div class="panel-body">
                        <div class='col-md-12' style="padding:0 0 15px 0">
                            <table id="jqGrid" class="table table-striped"></table>
                            <div id="jqGridPager"></div>
                        </div>
                    </div>
            </div>
		</div>

		<!-- <div class='panel panel-default'>
		<div class='panel-body'>
			<div class='col-xs-2'>
				<table class="table table-hover" id='TableGlmasdtl'>
				<thead>
					<tr>
						<th>Month</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class='num'>1</td>
						<td id='glmasdtl_actamount1' period='1'><span></span></td>
					</tr>
					<tr>
						<td class='num'>2</td>
						<td id='glmasdtl_actamount2' period='2'><span></span></td>
					</tr>
					<tr>
						<td class='num'>3</td>
						<td id='glmasdtl_actamount3' period='3'><span></span></td>
					</tr>
					<tr>
						<td class='num'>4</td>
						<td id='glmasdtl_actamount4' period='4'><span></span></td>
					</tr>
					<tr>
						<td class='num'>5</td>
						<td id='glmasdtl_actamount5' period='5'><span></span></td>
					</tr>
					<tr>
						<td class='num'>6</td>
						<td id='glmasdtl_actamount6' period='6'><span></span></td>
					</tr>
					<tr>
						<td class='num'>7</td>
						<td id='glmasdtl_actamount7' period='7'><span></span></td>
					</tr>
					<tr>
						<td class='num'>8</td>
						<td id='glmasdtl_actamount8' period='8'><span></span></td>
					</tr>
					<tr>
						<td class='num'>9</td>
						<td id='glmasdtl_actamount9' period='9'><span></span></td>
					</tr>
					<tr>
						<td class='num'>10</td>
						<td id='glmasdtl_actamount10' period='10'><span></span></td>
					</tr>
					<tr>
						<td class='num'>11</td>
						<td id='glmasdtl_actamount11' period='11'><span></span></td>
					</tr>
					<tr>
						<td class='num'>12</td>
						<td id='glmasdtl_actamount12' period='12'><span></span></td>
					</tr>

					<tr>
						<th>Total:</th>
						<td id='fd_total'></td>
					</tr>

					<tr>
						<th>Balance:</th>
						<td id='fd_balance'></td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id='TableGlmasTran_c' class='col-xs-10 modalx'>
				<table class="table table-hover  table-bordered" id='TableGlmasTran'>
				<thead>
					<tr>
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
					</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
			</div>
		</div>
		</div> -->
    </div>
	<!-------------------------------- End Search + table ------------------>

	<div id="dialogForm" title="Viewing Detail"></div>

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

	<script src="js/finance/AP/apenquiry/apenquiry.js"></script>
	<!-- <script src="plugins/datatables/js/jquery.datatables.min.js"></script> -->
	
@endsection