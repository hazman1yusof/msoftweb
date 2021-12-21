@extends('layouts.main')

@section('title', 'Authorization Detail')

@section('body')


	<div class='row'>
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">

		<form id="searchForm" class="formclass" style='width:99%' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
						  	<label class="control-label" for="Scol">Search By : </label>  
						  		<select id='Scol' class="form-control input-sm" tabindex="1"></select>
			            </div>
	                    <div class="col-md-5">
	                        <label class="control-label"></label>  
							<input id="Stext" name="Stext" type="text" class="form-control input-sm" autocomplete="off" tabindex="2"/>
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
		
	<!-- ***************End Search + table ********************* -->
	
	<div id="dialogForm" title="Authorization Detail" >
        	<form class='form-horizontal' style='width:99%' id='formdata'>
			
				{{ csrf_field() }}
				<input type="hidden" id="dtl_idno" name="dtl_idno">
				<!-- <input type="hidden" id="authorid" name="authorid"> -->
            
            	<div class="form-group">
				 	<label class="col-md-2 control-label" for="dtl_authorid">User ID</label>  
				  		<div class="col-md-2">
				  			<div class='input-group'>
								<input id="dtl_authorid" name="dtl_authorid" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					 		<span class="help-block"></span>
				  		</div>

				  	<label class="col-md-2 control-label" for="dtl_deptcode">Department</label>  
				  		<div class="col-md-2">
					  		<div class='input-group'>
								<input id="dtl_deptcode" name="dtl_deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					 		<span class="help-block"></span>
				  		</div>	
                </div>
            
            	<div class="form-group">
					<label class="col-md-2 control-label" for="dtl_recstatus">Authorize Status</label>  
						<div class="col-md-2">
							<select id="dtl_recstatus" name="dtl_recstatus" class="form-control input-sm">
							    <option value="SUPPORT">SUPPORT</option>
							    <option value="VERIFIED">VERIFIED</option>
							    <option value="APPROVED">APPROVED</option>
							</select>
						</div>	

					<label class="col-md-2 control-label" for="dtl_trantype">Type</label>  
						<div class="col-md-2">
							<select id="dtl_trantype" name="dtl_trantype" class="form-control input-sm">
							    <option value="PR">Purchase Request</option>
							    <option value="PO">Purchase Order</option>
							</select>
						</div>
                </div>		

                <div class="form-group">
				  <label class="col-md-2 control-label" for="dtl_cando">Recstatus</label> 
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="dtl_cando" value='ACTIVE' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="dtl_cando" value='DEACTIVE' >Deactive</label>
				  </div>
				</div> 
                
                <div class="form-group">
				  	<label class="col-md-2 control-label" for="dtl_minlimit">Min Limit</label>  
				  		<div class="col-md-2">
				  			<input id="dtl_minlimit" name="dtl_minlimit" type="text" class="form-control input-sm" value="1.00" rdonly>
				  		</div>

				  	<label class="col-md-2 control-label" for="dtl_maxlimit">Max Limit</label>  
				  		<div class="col-md-2">
				  			<input id="dtl_maxlimit" name="dtl_maxlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required">
				  		</div>
                </div>
			</form>
		</div>
		<!------------------------------end authdtl---------------------------------->	
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
	<script src="js/material/AuthorizationDetail/authorizationDtl.js"></script>

@endsection