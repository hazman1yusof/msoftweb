@extends('layouts.main')

@section('title', 'Stock Count')
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
#count_Text{width:9%;}#count_Text{width:60%;}#count_Col{width: 30%;}
#count_Col, #count_Text{
	display: inline-block;
	height: 70%;
	padding: 4px 12px 4px 12px;
}
#count_Search{
	border-style: solid;
	border-width: 0px 1px 1px 1px;
	padding-top: 5px;
	padding-bottom: 5px;
	border-radius: 0px 0px 5px 5px;
	background-color: #f8f8f8;
	border-color: #e7e7e7;
}
div#fail_msg{
  padding-left: 40px;
  padding-bottom: 10px;
  color: darkred;
}

@endsection

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="scope" name="scope" type="hidden" value="{{$scope}}">
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

							<div  id="tunjukname" style="display:none">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						
						</div>

		      </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

	            <div class="col-md-2">
			  		<label class="control-label" for="trandept">Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
				      		@foreach($trandept as $dept_)
				      			@if($dept_->deptcode == Session::get('deptcode'))
				      			<option value="{{$dept_->deptcode}}" selected>{{$dept_->deptcode}}</option>
				      			@else
				      			<option value="{{$dept_->deptcode}}">{{$dept_->deptcode}}</option>
				      			@endif
				      		@endforeach
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;">

					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>

					<span id="error_infront" style="color: red"></span>

					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>

					<?php 
						if($scope == 'POSTER'){
							$data_oper='posted';
						}else if($scope == 'COUNTER'){
							$data_oper='counted';
						}
					?>

					<button 
						type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$data_oper}}" 
						style="display: none;">
						{{strtoupper($data_oper)}}
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display:none">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>

    <div class="panel panel-default">
    	<div class="panel-heading">Stock Count Header
    		<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excel' href="" target="_blank" >
				<span class='fa fa-file-excel-o fa-lg'></span> Download Excel 
			</a>
			<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #a35252;" id='pdfgen1' href="" target="_blank">
				<span class='fa fa-file-pdf-o fa-lg'></span> Print PDF
			</a>
			<a class='pull-right pointer text-primary' style="padding-left: 30px;" id='pdfupd'>
				<span class='fa fa-upload fa-lg'></span> Upload Excel
			</a>
			<!-- <a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excel_import' href="" target="_blank">
				<span class='fa fa-file-pdf-o fa-lg'></span> Import Excel
			</a> -->
		</div>
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
        				<table id="jqGrid" class="table table-striped"></table>
        					<div id="jqGridPager"></div>
    				</div>
    		</div>
		</div>

		 <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>DOCUMENT NO: </b><span id="docno_show"></span><br>
				<b>DEPARTMENT: </b><span id="srcdept_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Stock Count Detail</h5>
				</div>
			</div>
			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Stock Count Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen2' href="" target="_blank">
					<span class='fa fa-print'></span> Print 
				</a>			
			</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
						{{ csrf_field() }}
						<input id="source" name="source" type="hidden">
						<input id="idno" name="idno" type="hidden">
						<input id="crdbfl" name="crdbfl" type="hidden">
						<input id="isstype" name="isstype" type="hidden">
						<input id="referral" name="referral" type="hidden">

						<div class="form-group">
							<label class="col-md-2 control-label" for="srcdept">Stock Department</label>
								<div class="col-md-2">
                                    <div class='input-group'>
                                        <input id="srcdept" name="srcdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
							  	</div>
							
							<label class="col-md-2 control-label" for="rackno">Rack No</label>
								<div class="col-md-2">
                                    <div class='input-group'>
                                        <input id="rackno" name="rackno" type="text" maxlength="12" class="form-control input-sm text-uppercase" rdonly>
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
							  	</div>
							
                            <!-- <label class="col-md-2 control-label" for="rackno">Rack No</label>  
                                <div class="col-md-2">
                                    <input id="rackno" name="rackno" type="text" maxlength="11" class="form-control input-sm" >
                                </div>	 -->

                            <label class="col-md-2 control-label" for="docno">Document No</label>  
					  			<div class="col-md-2">
									<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" rdonly>
					  			</div> 	
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="itemfrom">Item Code From</label>
								<div class="col-md-2">
								  <div class='input-group'>
									<input id="itemfrom" name="itemfrom" type="text" maxlength="12" class="form-control input-sm text-uppercase">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
								  <span class="help-block"></span>
							  </div>

                            <label class="col-md-2 control-label" for="itemto">Item Code To</label>
                                <div class="col-md-2">
                                    <div class='input-group'>
                                    <input id="itemto" name="itemto" type="text" maxlength="12" class="form-control input-sm text-uppercase">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
							
                            <label class="col-md-2 control-label" for="recno">Record No</label>  
					  			<div class="col-md-2">
									<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
					  			</div> 
					    </div>
							  	
						<div class="form-group">
				    		<label class="col-md-2 control-label" for="remarks">Remarks</label> 
					    		<div class="col-md-6"> 
					    			<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
					    		</div>

                            <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						        <div class="col-md-2">
                                    <input id="recstatus" name="recstatus" type="text" class="form-control input-sm" value = "OPEN" rdonly>
                                </div>
				   	    </div>

                           <div class="form-group data_info">
                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="frzdate">Freeze Date</label>  
                                    <input id="frzdate" name="frzdate" type="text" maxlength="30" class="form-control input-sm" rdonly> 
									<!-- value="{{Carbon\Carbon::now()->format('d-m-Y')}}"  -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="frztime">Freeze Time</label>
                                    <input id="frztime" name="frztime" type="time" maxlength="30" class="form-control input-sm" rdonly>
									<!-- value='{{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format("h:i A")}}' -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="respersonid">Freeze User</label>  
                                    <input id="respersonid" name="respersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
									<!-- value="{{Session::get('username')}}"  -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="upduser">Posted By</label>
                                    <input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="phycntdate">Phy. Count Date</label>
                                    <input id="phycntdate" name="phycntdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="phycnttime">Phy. Count Time</label>
                                    <input id="phycnttime" name="phycnttime" type="time" maxlength="30" class="form-control input-sm" rdonly>
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="adduser">Phy. Count user</label>  
                                    <input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="upddate">Posted Date</label>
                                    <input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
                                </div>
						</div>
						<!-- <button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Save</button>
						<button type="button" id='generate' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Generate</button> -->
				</form>
			</div>
		</div>
			
		<div class='panel panel-info'>
			<div class="panel-heading">Stock Freeze Detail</div>
			<div class="panel-body">
				<form id='formdata2' class='form-vertical' style='width:99%'>
					<div id="fail_msg"></div>
					<div id="jqGrid2_c" class='col-md-12'  style="overflow-y: hidden;overflow-x: hidden;height: calc(100vh - 95px);">
						<table id="jqGrid2" class="table table-striped"></table>
			            <div id="jqGridPager2"></div>
						<div class="col-md-10 col-md-offset-1" id="count_Search">
							<label class="control-label" id='count_Label'>Search</label>
							<input id="count_Text" type="text" class="form-control input-sm">
							<select class="form-control" id="count_Col">
								<option value="itemcode" >Item code</option>
							</select>
						</div>
					</div>
				</form>
			</div>

			<div class="panel-body">
				<div class="noti" style="font-size: bold; color: red"><ol></ol>
				</div>
			</div>
		</div>
	</div>

	<div id="upload_dialog" title="Upload File">
		<div class='panel panel-info'>
			<div class="panel-body" style="position: relative;">
				<form id="formContent" method="post" enctype="multipart/form-data" style="text-align: center;">
		  			{{csrf_field()}}
		  			<input type="hidden" name="action" value="import_excel">
		  			<input type="hidden" name="recno" id="recno_upld">
		    		<input type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="
			    		border-radius: 5px;
					    border: lightblue solid 1px;
					    padding: 10px;
					    margin: auto;
					    margin-bottom: 10px;">
	    			<button id="uploadbutton" type="submit" class="btn btn-primary btn-sm">Upload</button>
	    			<div>
	    				<p style="padding-top: 10px;
					    color: darkgreen;
					    font-size: larger;
					    display: none;" id="warn_upld" data-def_txt="Please wait while the file are being process"></p>
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
	<script src="js/material/stockCount/stockCount.js?v=1.11"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection