@extends('layouts.main')

@section('title', 'Cheque List')

@section('style')

 .panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
  display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
  display: inline-block;
}

i.fa {
  cursor: pointer;
  float: right;
 <!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
  padding: 0;
}

.clearfix {
	overflow: auto;
}

.ui-menu-item {
    font-size: 11px;
}

.ui-autocomplete {
   	font-size: 11px;
}
       
@endsection

@section('body')


	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">
					<div style="position:absolute;bottom:0;right:0;">
						
					</div>
				</div>

			 </fieldset> 
		</form>

    	<div class="panel panel-default">
		<div class="panel-heading">Cheque List Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel panel-default" id="gridCheqListDetail_c" style="position: relative;">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel1">

				<i class="fa fa-angle-double-up" style="font-size:24px"></i>
    			<i class="fa fa-angle-double-down" style="font-size:24px"></i>
    			<b><span id="bankname"></span></b><br>Cheque Detail <br> </div>
				<div id="jqGrid3_panel1" class="panel-collapse collapse">
					<div class="panel-body">
					    <div class='col-md-12' style="padding:0 0 15px 0">
			            	<table id="gridCheqListDetail" class="table table-striped"></table>
			            		<div id="jqGridPager2"></div>
			        	</div>
					</div>
				</div>	
		</div>

    </div>

	<!-------------------------------- End Search + table ------------------>

	@endsection

@section('scripts')

	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex', 2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex', 3);
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
	<script src="js/finance/CM/chqlist/cheqlist.js"></script>

@endsection