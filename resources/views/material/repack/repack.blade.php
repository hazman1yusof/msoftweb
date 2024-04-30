@extends('layouts.main')

@section('title', 'Repack')

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
        <!-- margin-right: 5px; -->
    }
    
    .collapsed ~ .panel-body {
        padding: 0;
    }
    
    .clearfix {
        overflow: auto;
    }
@endsection

@section('body')
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
    
    <!--***************************** Search + table *****************************-->
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
					
					<div class="col-md-5" style="margin-top: 4px;">
						<label class="control-label"></label>
						<input name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
					</div>
				</div>
			</div>
		</fieldset>
    </form>

	<!-- <form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
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
									<input id="deptcode_search" name="deptcode_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="deptcode_search_hb" class="help-block"></span>
							</div>

							<div id="trandate_text" class="form-inline" style="display:none">
								FROM DATE <input id="trandate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="trandate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="trandate_search">SEARCH</button>
							</div>
							
						</div>
		            </div>
				</div>

				<!-- <div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div> -->

				<!-- <div id="div_for_but_post" class="col-md-8 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button 
					type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="posted" 
						style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope').' ALL'}}
						@endif
					</button>

					<button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button>
				</div> -->

			</fieldset> 
		</form>

        
        <div class="panel panel-default">
            <div class="panel-heading"> Repack Output
                <!-- <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a> -->
            </div>
            <div class="panel-body">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid" class="table table-striped"></table>
                    <div id="jqGridPager"></div>
                </div>
            </div>
        </div>
		
		<div class="panel-group">
			<div class="panel panel-default" id="jqGrid2_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid2_panel">
					<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i>Repack Input
				</div>
				<div id="jqGrid2_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<label class="col-md-3 control-label" for="grandtot" style="float:right;">
								Grand Total: <input type="input" name="grandtot" id="grandtot" disabled="disabled">
							</label> 
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid2" class="table table-striped"></table>
								<div id="jqGridPager2"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div> -->
    <!--***************************** End Search + table *****************************-->
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
	<script src="js/material/repack/repack.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
@endsection