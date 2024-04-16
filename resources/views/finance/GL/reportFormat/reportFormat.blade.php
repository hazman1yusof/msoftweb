@extends('layouts.main')

@section('title', 'Report Format')

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
        
        <div class="panel panel-default">
            <div class="panel-heading"> Report Format
                <!-- <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a> -->
            </div>
            <div class="panel-body">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid" class="table table-striped"></table>
                    <div id="jqGridPager"></div>
                </div>
            </div>
        </div>
    </div>
    <!--***************************** End Search + table *****************************-->
	
	<div id="dialogForm" title="Add Form">
		<div class='panel panel-info'>
			<div class="panel-heading">Report Format Header</div>
			<div class="panel-body" style="position: relative;padding-bottom: 0px !important">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input id="idno" name="idno" type="hidden">
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="rptname">Report Name</label>
						<div class="col-md-3">
							<input id="rptname" name="rptname" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm" data-validation="required">
						</div>
						
						<label class="col-md-2 control-label" for="description">Description</label>
						<div class="col-md-3">
							<input id="description" name="description" type="text" class="form-control input-sm text-uppercase" class="form-control input-sm">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="rpttype">Category</label>
						<div class="col-md-3">
							<select class="form-control col-md-4" id='rpttype' name='rpttype' data-validation="">
								<option value='BALANCE SHEET'>BALANCE SHEET</option>
								<option value='PROFIT & LOSS (DETAIL)'>PROFIT & LOSS (DETAIL)</option>
								<option value='CASH FLOW'>CASH FLOW</option>
							</select>
						</div>
					</div>
				</form>
				
				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>
		
		<div class='panel panel-info'>
			<div class="panel-heading">Report Format Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">
					
					<div id="jqGrid2_c" class='col-md-12'>
						<table id="jqGrid2" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>
				</form>
			</div>
			
			<!-- <div class="panel-body">
				<div class="noti" style="color:red"></div>
			</div> -->
		</div>
		
		<!-- </div> -->
	</div>
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
	<script src="js/finance/GL/reportFormat/reportFormat.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
@endsection