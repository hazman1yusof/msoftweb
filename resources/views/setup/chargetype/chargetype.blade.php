@extends('layouts.main')

@section('title', 'Charge Type Setup')

@section('style')

input.uppercase {
	text-transform: uppercase;
}

@endsection

@section('body')

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
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div  id="show_chggroup_div" style="display:none">
								<div class='input-group'>
									<input id="show_chggroup" seltext='false' name="show_chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">Charge Type Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Charge Type</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="idno" name="idno" type="hidden">
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="chgtype">Charge Type</label>  
						<div class="col-md-3">
							<input id="chgtype" name="chgtype" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						</div>
					
						<label class="col-md-2 control-label" for="description">Description</label>  
						<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm uppercase" data-validation="required">
						</div>
					</div>
			                
					<div class="form-group">
						<label class="col-md-2 control-label" for="seqno">Sequence Number</label>  
						<div class="col-md-3">
							<input id="seqno" name="seqno" type="text" class="form-control input-sm uppercase">
						</div>

						<label class="col-md-2 control-label" for="chggroup">Charge Group</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="chggroup" name="chggroup" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-md-2 control-label" for="ipdept">IP Dept</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="ipdept" name="ipdept" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					
						<label class="col-md-2 control-label" for="opdept">OP Dept</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="opdept" name="opdept" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="ipacccode">IP Account</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="ipacccode" name="ipacccode" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="opacccode">OP Account</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="opacccode" name="opacccode" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<!-- <label class="col-md-2 control-label" for="opacc">OP Account</label>  
						<div class="col-md-3" style="padding:0px 0px;">
							<div class="col-md-3">
								<input id="opacc" name="opacc" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
							</div>
							<div class="col-md-9">
								<input id="opacc" name="opacc" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
							</div>
						</div> -->
					</div>  

					<div class="form-group">
						<label class="col-md-2 control-label" for="otcacccode">OTC Account</label>   
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="otcacccode" name="otcacccode" type="text" maxlength="12" class="form-control input-sm uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>

						<label class="col-md-2 control-label" for="invcategory">Inventory Category</label>  
						<div class="col-md-3" >
							<div class='input-group'>
								<input id="invcategory" name="invcategory" type="text" maxlength="12" class="form-control input-sm uppercase">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class="help-block"></span>
						</div>
					</div>  

					<div class="form-group">
						<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						<div class="col-md-6">
							<label class="radio-inline"><input id="recstatus" type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-md-2 control-label" for="upduser">Last User</label>  
						<div class="col-md-3">
							<input id="upduser" name="upduser" type="text" class="form-control input-sm uppercase" rdonly>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Update</label>  
						<div class="col-md-3">
							<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm uppercase" rdonly>
						</div>
					</div> 
					

					<div class="form-group">
						<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
							<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm uppercase" rdonly >
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" rdonly >
						</div>
					</div>  
				</form>
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
	
	<script src="js/setup/chargetype/chargetype.js"></script>
	
@endsection