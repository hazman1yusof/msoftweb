@extends('layouts.main')

@section('title', 'GL Master')

@section('style')

input.uppercase {
	text-transform: uppercase;
}
@endsection

@section('body')

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
						</div>

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

        <div class="panel panel-default">
		    <div class="panel-heading">GL Master Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
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
	<script src="js/finance/GL/GLMaster/GLMaster.js?v=1.3"></script>
@endsection