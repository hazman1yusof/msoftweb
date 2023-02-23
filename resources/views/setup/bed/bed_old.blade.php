@extends('layouts.main')

@section('title', 'Bed Setup')

@section('style')

.panel-heading i.fa {
		cursor: pointer;
		float: right;
		<!--  margin-right: 5px; -->
	}

.position i {
	position: relative;
	line-height: 1;
	top: -10px;
}	

input.uppercase {
	text-transform: uppercase;
}

.justify {
	text-align: -webkit-center;
}


@endsection

@section('body')

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
							<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
						
							<div  id="show_bedtype" style="display:none">
								<div class='input-group'>
									<input id="s_bedtype" name="s_bedtype" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>

							<div  id="show_occup" style="display:none">
								<div class='input-group'>
									<input id="occup" name="occup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>														
						</div>

						<div class="col-md-1" style="padding-left: 0px;">
							<div id="div_bedtype" style="padding-left: 0px;max-width: 45px;display:none">
								<label class="control-label"></label>
								<a class='form-control btn btn-primary' id="btn_bedtype"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<div id="div_occup" style="padding-left: 0px;max-width: 45px;display:none;margin: 0px">
								<label class="control-label"></label>
								<a class='form-control btn btn-primary' id="btn_occup"><span class='fa fa-ellipsis-h'></span></a>
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
		    <div class="panel-heading">Bed Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->

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
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="plugins/bootstrap-select-1.13.9/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="plugins/bootstrap-select-1.13.9/dist/js/bootstrap-select.min.js"></script>
	<script src="js/setup/bed/bed.js"></script>
@endsection