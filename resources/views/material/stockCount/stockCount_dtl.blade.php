@extends('layouts.main')

@section('title', 'Stock Count Detail')
@section('style')

	#dialogForm{
		padding:15px;
	}

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

@endsection

@section('body')
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
                                        <input id="srcdept" name="srcdept" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{$header->srcdept}}">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
							  	</div>
							
							<label class="col-md-2 control-label" for="rackno">Rack No</label>
								<div class="col-md-2">
                                    <div class='input-group'>
                                        <input id="rackno" name="rackno" type="text" maxlength="12" class="form-control input-sm text-uppercase" value="{{$header->srcdept}}" readonly='readonly'>
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
							  	</div>
							
                            <!-- <label class="col-md-2 control-label" for="rackno">Rack No</label>  
                                <div class="col-md-2">
                                    <input id="rackno" name="rackno" type="text" maxlength="11" class="form-control input-sm" >
                                </div>	 -->

                            <label class="col-md-2 control-label" for="docno">Document No</label>  
					  			<div class="col-md-2">
									<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" value="{{$header->docno}}" readonly='readonly'>
					  			</div> 	
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="itemfrom">Item Code From</label>
								<div class="col-md-2">
								  <div class='input-group'>
									<input id="itemfrom" name="itemfrom" type="text" maxlength="12" class="form-control input-sm text-uppercase" value="{{$header->itemfrom}}">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
								  <span class="help-block"></span>
							  </div>

                            <label class="col-md-2 control-label" for="itemto">Item Code To</label>
                                <div class="col-md-2">
                                    <div class='input-group'>
                                    <input id="itemto" name="itemto" type="text" maxlength="12" class="form-control input-sm text-uppercase" value="{{$header->itemto}}">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
							
                            <label class="col-md-2 control-label" for="recno">Record No</label>  
					  			<div class="col-md-2">
									<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" readonly='readonly' value="{{$header->recno}}">
					  			</div> 
					    </div>
							  	
						<div class="form-group">
				    		<label class="col-md-2 control-label" for="remarks">Remarks</label> 
					    		<div class="col-md-6"> 
					    			<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" data-validation="required" data-validation-error-msg="Please Enter Value">{{$header->remarks}}</textarea>
					    		</div>

                            <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
						        <div class="col-md-2">
                                    <input id="recstatus" name="recstatus" type="text" class="form-control input-sm" value="{{$header->recstatus}}" readonly='readonly'>
                                </div>
				   	    </div>

                           <div class="form-group data_info">
                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="frzdate">Freeze Date</label>  
                                    <input id="frzdate" name="frzdate" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->recstatus}}"> 
									<!-- value="{{Carbon\Carbon::now()->format('d-m-Y')}}"  -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="frztime">Freeze Time</label>
                                    <input id="frztime" name="frztime" type="time" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->frztime}}">
									<!-- value='{{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format("h:i A")}}' -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="respersonid">Freeze User</label>  
                                    <input id="respersonid" name="respersonid" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->respersonid}}">
									<!-- value="{{Session::get('username')}}"  -->
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="upduser">Posted By</label>
                                    <input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->upduser}}">
                                </div>

								<div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="phycntdate">Phy. Count Date</label>
                                    <input id="phycntdate" name="phycntdate" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->phycntdate}}">
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="phycnttime">Phy. Count Time</label>
                                    <input id="phycnttime" name="phycnttime" type="time" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->phycnttime}}">
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="adduser">Phy. Count user</label>  
                                    <input id="adduser" name="adduser" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->adduser}}">
                                </div>

                                <div class="col-md-3 minuspad-13">
                                    <label class="control-label" for="upddate">Posted Date</label>
                                    <input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" readonly='readonly' value="{{$header->upddate}}">
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
@endsection

@section('scripts')
<script>
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	$(document).ready(function () {
		$('input:not(#count_Text),select,textarea').prop('readonly',true);
		var fdl = new faster_detail_load();

		var urlParam2={
			action:'get_table_default',
			url:'util/get_table_default',
			table_name:['material.phycntdt'],
			table_id:'idno',
			filterCol:['recno', 'compcode'],
			filterVal:['{{$header->recno}}', 'session.compcode']
		};

		var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
		////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
		$("#jqGrid2").jqGrid({
			datatype: "local",
			editurl: "./stockCountDetail/form",
			colModel: [
			 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			 	{ label: 'idno', name: 'idno',hidden:true},
			 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:false, hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 30, classes: 'wrap', editable:false},
				{ label: 'Item Code', name: 'itemcode', width: 180, classes: 'wrap', editable:false,formatter: showdetail,},
				{ label: 'UOM Code', name: 'uomcode', width: 60, classes: 'wrap', editable:false,formatter: showdetail,},
				{ label: 'Unit Cost', name: 'unitcost', width: 80, align: 'right', classes: 'wrap'},
				{ label: 'System<br>Quantity', name: 'thyqty', width: 80, align: 'right', classes: 'wrap'},
	            { label: 'Physical<br>Quantity', name: 'phyqty', width: 80, align: 'right', classes: 'wrap'},
	            { label: 'Variance<br>Quantity', name: 'vrqty', width: 80, align: 'right', classes: 'wrap'},
				{ label: 'Expiry Date', name: 'expdate', width: 80, classes: 'wrap',formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},},
				{ label: 'Batch No', name: 'batchno', width: 80, classes: 'wrap'},
			],
			autowidth: false,
			shrinkToFit: true,
			multiSort: true,
			viewrecords: true,
			loadonce:false,
			width: 1150,
			height: 200,
			rowNum: 100,
			sortname: 'lineno_',
			sortorder: "desc",
			pager: "#jqGridPager2",
			loadComplete: function(){
			},
			gridComplete: function(){
				fdl.set_array().reset();
				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			},
			afterShowForm: function (rowid) {
			},
			beforeSubmit: function(postdata, rowid){ 
		 	}
		});

		////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
		jqgrid_label_align_right("#jqGrid2");

		addParamField('#jqGrid2',true,urlParam2,['vrqty']);

		count_Search("#jqGrid2",urlParam2);
		function count_Search(grid,urlParam){
			$("#count_Text").on( "keyup", function() {
				delay(function(){
					search(grid,$("#count_Text").val(),$("#count_Col").val(),urlParam);
				}, 500 );
				urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
			});

			$("#count_Col").on( "change", function() {
				search(grid,$("#count_Text").val(),$("#count_Col").val(),urlParam);
				urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
			});
		}
	});

	function showdetail(cellvalue, options, rowObject){
		var field,table,  case_;
		switch(options.colModel.name){
			case 'srcdept':field=['deptcode','description'];table="sysdb.department";case_='srcdept';break;
			case 'itemfrom':field=['itemcode','description'];table="material.product";case_='itemfrom';break;
			case 'itemto':field=['itemcode','description'];table="material.product";case_='itemto';break;

			case 'itemcode':field=['itemcode','description'];table="material.product";case_='itemcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('stockCount',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}
</script>
@endsection