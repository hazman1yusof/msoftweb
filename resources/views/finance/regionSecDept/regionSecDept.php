<?php 
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
?>
<!DOCTYPE html>

<html lang="en">
<head>
	<script type="text/ecmascript" src="../../js/jquery.min.js"></script> 
    <script type="text/ecmascript" src="../../js/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="../../js/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="../../js/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/AccordionMenu/dist/metisMenu.min.js"></script>
    <script type="text/ecmascript" src="../../js/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="../../js/form-validator/jquery.form-validator.min.js"></script>
    <script type="text/ecmascript" src="../../js/jquery.dialogextend.js"></script>
	
	
    <link rel="stylesheet" href="../../js/form-validator/theme-default.css" />
	<link rel="stylesheet" href="../../js/jquery-ui-1.11.4.custom/jquery-ui.css">
	<link rel="stylesheet" href="../../js/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../js/ionicons-2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="../../js/AccordionMenu/dist/metisMenu.min.css"> 
	<link rel="stylesheet" href="../../js/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="../../js/jasny-bootstrap/css/jasny-bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" media="screen" href="../../js/css/trirand/ui.jqgrid-bootstrap.css" />
	<script>
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		$(document).ready(function () {
		
			$("#effectdate").datepicker({
				dateFormat: 'dd-mm-yy',
			});
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 9/10 * $(window).width(),
				height: 410
			});
			
			$.validate({
				modules : 'date',
				language : {
					requiredFields: ''
				},
			});
			
			var operRegion;
			$("#dialogFormRegion")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					$('button').focus();
					emptyFormdata('Region');
				},
				buttons :
					[{
						text: "Save",click: function() {
							if( $('#formdataRegion').isValid({requiredFields: ''}, {}, true) ) {
								var oper=operRegion,id='Region',saveURL='regionSave.php';
								saveFormdata(oper,id,saveURL);
							}
						}
					},{
						text: "Cancel",click: function() {
							$(this).dialog('close');
						}
					}]
			  });
			  
			var operSector;
			$("#dialogFormSector")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					$('button').focus();
					emptyFormdata('Sector');
				},
				buttons :
					[{
						text: "Save",click: function() {
							if( $('#formdataSector').isValid({requiredFields: ''}, {}, true) ) {
								var oper=operSector,id='Sector',saveURL='sectorSave.php';
								saveFormdata(oper,id,saveURL);
							}
						}
					},{
						text: "Cancel",click: function() {
							$(this).dialog('close');
						}
					}]
			  });
			  
			var operDept;
			$("#dialogFormDept")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					$('button').focus();
					emptyFormdata('Dept');
				},
				buttons :
					[{
						text: "Save",click: function() {
							if( $('#formdataDept').isValid({requiredFields: ''}, {}, true) ) {
								var oper=operDept,id='Dept',saveURL='sectorSave.php';
								saveFormdata(oper,id,saveURL);
							}
						}
					},{
						text: "Cancel",click: function() {
							$(this).dialog('close');
						}
					}]
			  });
			
			$("#gridRegion").jqGrid({
				url: 'regionTbl.php',
				editurl: 'regionSave.php',
				datatype: "json",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 90 },
					{ label: 'regioncode', name: 'regioncode', width: 90, canSearch: true },
					{ label: 'description', name: 'description', width: 90, canSearch: true },
					{ label: 'adddate', name: 'adddate', width: 90 },
					{ label: 'adduser', name: 'adduser', width: 90 },
					{ label: 'upddate', name: 'upddate', width: 90 },
					{ label: 'upduser:', name: 'upduser', width: 90 },
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: true,
				width: 780,
				height: 250,
				rowNum: 30,
				pager: "#gridRegionPager",
				onSelectRow: function(rowid){
					$('#gridSector').jqGrid('setGridParam',{datatype:'json',url:'sectorTbl.php?regioncode='+rowid}).trigger('reloadGrid');
				}
			});
			
			$("#gridSector").jqGrid({
				editurl: 'sectorSave.php',
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 90 },
					{ label: 'sectorcode', name: 'sectorcode', width: 90, canSearch: true },
					{ label: 'regioncode', name: 'regioncode', width: 90 },
					{ label: 'description', name: 'description', width: 90, canSearch: true },
					{ label: 'adddate', name: 'adddate', width: 90 },
					{ label: 'adduser', name: 'adduser', width: 90 },
					{ label: 'upddate', name: 'upddate', width: 90 },
					{ label: 'upduser', name: 'upduser', width: 90 },
					{ label: 'lastinvno', name: 'lastinvno', width: 90 },
					{ label: 'mrnsrc', name: 'mrnsrc', width: 90 },
					{ label: 'mrntrantype', name: 'mrntrantype', width: 90 },
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: true,
				width: 780,
				height: 250,
				rowNum: 30,
				pager: "#gridSectorPager",
				onSelectRow: function(rowid){
					selRowId = $("#gridRegion").jqGrid ('getGridParam', 'selrow');
					$('#gridDept').jqGrid('setGridParam',{datatype:'json',url:'deptTbl.php?sector='+rowid+'region='+selRowId}).trigger('reloadGrid');
				}
			});
			
			$("#gridDept").jqGrid({
				editurl: 'deptSave.php',
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 90 },
					{ label: 'regioncode', name: 'regioncode', width: 90 },
					{ label: 'description', name: 'description', width: 90, canSearch: true },
					{ label: 'adddate', name: 'adddate', width: 90 },
					{ label: 'adduser', name: 'adduser', width: 90 },
					{ label: 'upddate', name: 'upddate', width: 90 },
					{ label: 'upduser:', name: 'upduser', width: 90 },
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: true,
				width: 780,
				height: 250,
				rowNum: 30,
				pager: "#gridDeptPager",
				onPaging: function(pgButton){
				},
			});
			
			$("#gridRegion").jqGrid('navGrid','#gridRegionPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						$("#gridRegion").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
				}
			).jqGrid('navButtonAdd',"#gridRegionPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					operRegion='add';
					$("#dialogFormRegion").dialog( "open" );
				}, 
				position: "last", 
				title:"Add New Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridRegionPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					operRegion='edit';
					selRowId = $("#gridRegion").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Region');
				}, 
				position: "last", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridRegionPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					operRegion='view';
					selRowId = $("#gridRegion").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Region','view');
				}, 
				position: "last", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridRegionPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					operRegion='del';
					selRowId = $("#gridRegion").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('Region');
					}else{
						$.post('regionSave.php',{'oper':'del','regioncode':selRowId}).success(function(data){
							$('#gridRegion').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						});
					}
				}, 
				position: "last", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			});
			
			$("#gridSector").jqGrid('navGrid','#gridSectorPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						$("#gridSector").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
				}
			).jqGrid('navButtonAdd',"#gridSectorPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					operSector='add';
					$("#dialogFormSector").dialog( "open" );
				}, 
				position: "last", 
				title:"Add New Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridSectorPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					operSector='edit';
					selRowId = $("#gridSector").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Sector');
				}, 
				position: "last", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridSectorPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					operSector='view';
					selRowId = $("#gridSector").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Sector','view');
				}, 
				position: "last", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridSectorPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					operSector='del';
					selRowId = $("#gridSector").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('Sector');
					}else{
						$.post('regionSave.php',{'oper':'del','sectorcode':selRowId}).success(function(data){
							$('#gridSector').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						});
					}
				}, 
				position: "last", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			});
			
			$("#gridDept").jqGrid('navGrid','#gridDeptPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						$("#gridRegion").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
				}
			).jqGrid('navButtonAdd',"#gridDeptPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					operRegion='add';
					$("#dialogFormDept").dialog( "open" );
				}, 
				position: "last", 
				title:"Add New Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridDeptPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					operRegion='edit';
					selRowId = $("#gridDept").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Dept');
				}, 
				position: "last", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridDeptPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					operRegion='view';
					selRowId = $("#gridDept").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'Dept','view');
				}, 
				position: "last", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#gridDeptPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					operRegion='del';
					selRowId = $("#gridDept").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('Region');
					}else{
						$.post('regionSave.php',{'oper':'del','deptcode':selRowId}).success(function(data){
							$('#gridDept').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						});
					}
				}, 
				position: "last", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			});
			
			function populateFormdata(selRowId,id,oper){
				if(!selRowId){
					alert('Please select row');
					return emptyFormdata(id);
				}
				if(oper=='view'){
					$("#formdata"+id+" input, textarea, option").prop('disabled', true);
				}else{
					$("#formdata"+id+" input, textarea, option").prop('disabled', false);
				}
				$('#dialogForm'+id).dialog( "open" );
				rowData = $("#grid"+id).jqGrid ('getRowData', selRowId);
				$.each(rowData, function( index, value ) {
					var input=$("#formdata"+id+" [name='"+index+"']");
					if(input.is("#formdata"+id+" [type=radio]")&&input.attr('name')==index){
						input.prop('checked', true);
					}else{
						input.val(value);
					}
				});
			}
			
			function emptyFormdata(id){
				$('#formdata'+id).trigger('reset');
				$('.help-block').html('');
			}
			
			function saveFormdata(oper,id,saveUrl){
				$.post( saveUrl, $( "#formdata"+id ).serialize()+'&'+$.param({ 'oper': oper }) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText,id);
				}).success(function(data){
					$('#dialogForm'+id).dialog('close');
					$('#grid'+id).jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				});
			}
			
			function errorText(text,id){
				$( "#formdata"+id ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 50, classes: 'pointer'}, 
					{ label: 'Description', name: 'desc', width: 200, classes: 'pointer'},
					{ label: 'compcode', name: 'compcode', width: 50, classes: 'pointer'},
				],
				width: 550,
				height: 180,
				viewrecords: true,
				loadonce: true,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$( "#dialog" ).dialog( "close" );
					$('#bankname').val(data['compcode']);
					$('#'+selText).val(rowid);
					$('#'+selText).focus();
					$('#'+selText).parent().next().html(data['desc']);
				},
				
			});
			
			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			
			populateSelect(['Region','Sector','Dept']);
			function populateSelect(array){
				$.each(array,function(i,v){					
					$.each($("#grid"+v).jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							$("#Scol"+v ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' >"+value['label']+"</input></label>" );
						}
					});
				});
			}
			
			$('#Stext').keyup(function() {
				delay(function(){
					search($('#Stext').val(),$('#Scol').val());
				}, 500 );
			});
			
			$('#Scol').change(function(){
				search($('#Stext').val(),$('#Scol').val());
			});
			
			function search(Stext,Scol){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'bankTbl.php?Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			function dialogHandler(table,id,cols){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+table+'&cols='+cols}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					$.each(cols, function( index, value ) {
						$("#Dcol" ).append( "<option value='"+value+"' >"+value+"</option>" );
					});
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#Dcol').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#Dcol').val());
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+Dtable+'&cols='+Dcols+'&Dcol='+Dcol+'&Dtext='+Dtext}).trigger('reloadGrid');
			}
			
			$('#menu').metisMenu();
			
		});

 </script>
 
<style>
	.row{
		border-radius: 5px;
		padding: 5px;
		margin:5px;
		border: 1px solid rgba(0, 0, 0, 0);
	}
	.row:hover{
		background: rgba(0, 0, 0, 0.02);
		border: 1px solid rgba(0, 0, 0, 0.1);
	}
	.container{
		width:100%;
		padding:0;
		margin:0;
	}
	.pointer {
		cursor: pointer;
	}
	::-webkit-scrollbar{
	  width: 6px;  /* for vertical scrollbars */
	  height: 6px; /* for horizontal scrollbars */
	}
	::-webkit-scrollbar-track{
	  background: rgba(0, 0, 0, 0.1);
	}
	::-webkit-scrollbar-thumb{
	  background: rgba(0, 0, 0, 0.5);
	}
</style>
 
    <meta charset="utf-8" />
    <title>Region Sector Department</title>
</head>
<body>
	  
	<div class="container" style="margin-bottom:1em">
		
		<div class='row'>
			<form class="form-horizontal">
				<fieldset>
				<legend><h2>Region <small>Form</small></h2></legend>

				<div class="col-md-6 form-group">
					<label class="col-md-3 control-label" for="searchinput">Search Input</label>
					<div class="col-md-9">
						<input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					</div>
				</div>
				
				<div class="col-md-6 form-group">
					<label class="col-md-3 control-label" for="selectbasic">Search For</label>
					<div id="ScolRegion" class='col-md-9 form-group'>
					</div>
				</div>
				
				</fieldset>
			</form>
			<div class='col-md-12'>
				<table id="gridRegion" class="table table-striped"></table>
				<div id="gridRegionPager"></div>
			</div>
		</div>
		
		<div class='row'>
			<form class="form-horizontal">
				<fieldset>
				<legend><h2>Sector <small>Form</small></h2></legend>

				<div class="col-md-6 form-group">
					<label class="col-md-3 control-label" for="searchinput">Search Input</label>
					<div class="col-md-9">
						<input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					</div>
				</div>
				
				<div class="col-md-6 form-group">
				<label class="col-md-3 control-label" for="selectbasic">Search For</label>
					<div id="ScolSector" class='col-md-9 form-group'>
					</div>
				</div>
				
				</fieldset>
			</form>
			<div class='col-md-12'>
				<table id="gridSector" class="table table-striped"></table>
				<div id="gridSectorPager"></div>
			</div>
		</div>
		
		<div class='row'>
			<form class="form-horizontal">
				<fieldset>
				<legend><h2>Department <small>Form</small></h2></legend>

				<div class="col-md-6 form-group">
					<label class="col-md-3 control-label" for="searchinput">Search Input</label>
					<div class="col-md-9">
						<input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					</div>
				</div>
				
				<div class="col-md-6 form-group">
				<label class="col-md-3 control-label" for="selectbasic">Search For</label>
					<div id="ScolDept" class='col-md-9 form-group'>
					</div>
				</div>
				
				</fieldset>
			</form>
			<div class='col-md-12'>
				<table id="gridDept" class="table table-striped"></table>
				<div id="gridDeptPager"></div>
			</div>
		</div>
		
		<div id="dialogFormRegion" title="Dialog Form" >
			<form class='form-horizontal' style='width:99%' id='formdataRegion'>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="regioncode">Region Code</label>  
				  <div class="col-md-10">
					<input id="regioncode" name="regioncode" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-10">
					<input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
			</form>
		</div>
		
		<div id="dialogFormSector" title="Dialog Form" >
			<form class='form-horizontal' style='width:99%' id='formdataSector'>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="sectorcode">Sector Code</label>  
				  <div class="col-md-10">
					<input id="sectorcode" name="sectorcode" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="regioncode">Region Code</label>  
				  <div class="col-md-10">
					<input id="regioncode" name="regioncode" type="text" disabled='true' class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-10">
					<input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastinvo">Last Invoice</label>  
				  <div class="col-md-10">
					<input id="lastinvo" name="lastinvo" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="mrnsrc">MRN Src</label>  
				  <div class="col-md-10">
					<input id="mrnsrc" name="mrnsrc" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="mrntrantype">MRN Type</label>  
				  <div class="col-md-10">
					<input id="mrntrantype" name="mrntrantype" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
			</form>
		</div>
		
		<div id="dialogFormDept" title="Dialog Form" >
			<form class='form-horizontal' style='width:99%' id='formdataDept'>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="regioncode">Region Code</label>  
				  <div class="col-md-10">
					<input id="regioncode" name="regioncode" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-10">
					<input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
			</form>
		</div>
		
		<div id="dialog" title="title">
			<div class='col-md-12'>
				<table id="gridDialog" class="table table-striped"></table>
				<div id="gridDialogPager"></div>
			</div>
			
			<form class="form-horizontal col-md-12" style="background-color:gainsboro;margin-top:5px;border-radius:5px"><br>
				<div class='col-md-8 form-group'>
					<input id="Dtext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				
				<div class='col-md-4 form-group'>
					<select id="Dcol" class="form-control "></select>
				</div>
			</form>
			
		</div>
	</div><!--/.container-->

    
</body>
</html>