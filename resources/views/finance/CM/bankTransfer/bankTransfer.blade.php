@extends('layouts.main')

@section('title', 'Bank Transfer')

@section('body')

	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

<!--***************************** Search + table ******************-->
	 
<div class='row'>
	<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
		<fieldset>
			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
						  	<label class="control-label" for="Scol">Search By : </label>  
						  		<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
			            </div>

						<div class="col-md-5">
						  	<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
						</div>

			        </div>
				</div>


				<div id="div_for_but_post" class="col-md-10 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>
		</fieldset> 
	</form>

		<div class="panel panel-default">
		    <div class="panel-heading">Bank Transfer</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            				<div id="jqGridPager"></div>
        			</div>
		    	</div>
			</div>
		</div>

	<!-- ***************End Search + table ********************* -->
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:100%' id='formdata'>
				{{ csrf_field() }}
				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading"><b>CREDIT</b></div>
							<div class="panel-body">

								<div class="prevnext btn-group pull-right"></div>
									<input id="source" name="source" type="hidden">
									<input id="trantype" name="trantype" type="hidden">
									<input type="hidden" name="auditno" id="auditno"></ins>

									<div class="form-group">
										<label class="col-md-2 control-label" for="auditno">Audit No</label>  
											<div class="col-md-3">
												<input id="auditno" name="auditno" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit rdonly>
											</div>
									
										<label class="col-md-2 control-label" for="pvno">Payment No</label>  
									  		<div class="col-md-3">
									  			<input id="pvno" name="pvno" type="text" maxlength="40" class="form-control input-sm text-uppercase" rdonly>
									  		</div>
					                </div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="actdate" name="actdate" type="date"  maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>"> 
										  		</div>
											</div>
										
										<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="paymode" name="paymode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
										  			<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  		</div>
										   		<span class="help-block"></span>
											</div>
									</div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
											<div class="col-md-3" id="bankcode_parent">
										 		<div class='input-group'>
													<input id="bankcode" name="bankcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
													<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  		</div>
												<span id='bc' class="help-block"></span>
											</div>
										
									  	<label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
						  					<div class="col-md-3" id="cheqno_parent">
							 					<div class='input-group'>
													<input id="cheqno" name="cheqno" type="text" class="form-control input-sm text-uppercase">
													<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
							  					</div>
							 					<span id='cn' class="help-block"></span>
		                      				</div>
					    			</div>


									<div class="form-group">
										<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
											<div class="col-md-3">
										  		<div class='input-group'>
													<input id="cheqdate" name="cheqdate" type="date" maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
										  		</div>
										 	</div>

										<label class="col-md-2 control-label" for="amount">Amount</label>  
											<div class="col-md-3">
												<input id="amount" name="amount" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
											</div>
									</div>
								</div>
							</div>
						</div>


				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading"><b>DEBIT</b></div>
							<div class="panel-body">


								<div class="form-group">
									<label class="col-md-2 control-label" for="payto">Bank Code</label>  
										<div class="col-md-3">
										  <div class='input-group'>
											<input id="payto" name="payto" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required"/>
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
										</div>		
								</div>

								<div class="form-group">
										<label class="col-md-2 control-label" for="remarks">Remarks</label>  
											<div class="col-md-8">
												<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="300" id="remarks"></textarea>
											</div>
								</div>	

							</div>
						</div>
					</div>
				</div>

			</form>
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
	<script src="js/finance/CM/bankTransfer/bankTransfer.js"></script>
@endsection