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
     <link rel="stylesheet" type="text/css" href="../../js/searchCSS/stylesSearch.css">
     
	<style>
		.wrap{
			word-wrap: break-word;
		}
		.ui-th-column{
			word-wrap: break-word;
			white-space: normal !important;
			vertical-align: top !important;
		}
		
		.radio-inline+.radio-inline {
			margin-left: 0;
		}
		
		.radio-inline {
			margin-right: 10px;
		}
	</style>
    
	<script>
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
			});
			
			$( "#dialog2" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
			});
			
			$.validate({
				modules : 'date',
				language : {
					requiredFields: ''
				},
			});
			
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata(oper);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];
			
			var butt2=[{
				text: "Close",click: function() {
					$(this).dialog('close');
			}}]
			
			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 8/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
					if(oper!='view'){
					dialogHandler2('sysdb.department','deptcode',['deptcode','description'],'Department');
					}
				},
				close: function( event, ui ) {
					$("#formdata a").off();
				},
				buttons : butt1,
			  })
			  .dialogExtend({
				"closable" : true,
			  });
			
			
			$("#jqGrid").jqGrid({
				url: 'deliveryDeptTbl.php',
				editurl: 'deliveryDeptSave.php',
				datatype: "json",
				 colModel: [
				 
					{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
					
					{label: 'Delivery Store', name: 'deptcode', width: 90, classes: 'wrap', canSearch: true,                    checked:true, editable: true, editrules:{ required: true}}, 
					
					{label: 'Description', name: 'description', width: 90, classes: 'wrap', canSearch: true, 
					editable: true, editrules:{ required: true}},
					
					{label: 'Address', name: 'addr1', width: 90 , classes: 'wrap', hidden: true, editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 2', name: 'addr2', width: 90, classes: 'wrap', hidden: true, editable:true, 					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 3', name: 'addr3', width: 90, classes: 'wrap', hidden: true, editable: true,                   editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 4', name: 'addr4', width: 90, classes: 'wrap', hidden: true, editable: true,                   editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Telephone No', name: 'tel', width: 90, classes: 'wrap', editable: true ,
					editrules:{ required: true}}, 
					
					{label: 'Fax No', name: 'fax', width: 90, classes: 'wrap', editable: true ,
					editrules:{ required: true}}, 
					
					{label: 'General Telephone', name: 'generaltel', width: 90, classes: 'wrap', hidden: true,                    editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'General Fax', name: 'generalfax', width: 90, classes: 'wrap', hidden: true,                    editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Contact Person', name: 'contactper', width: 90, classes: 'wrap', hidden: true,                    editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'recstatus', name: 'recstatus', width: 90, classes: 'wrap', hidden: true, editable: true},
					
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true, editable: true},
					
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true, editable: true},
					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true, editable: true},
					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true, editable: true},
					
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce:false,
				//loadonce: true,
				width: 900,
				height: 350,
				rowNum: 30,
				//rownumbers: true,
				
				pager: "#jqGridPager",
				onPaging: function(pgButton){
				},
				gridComplete: function(){
					if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}
				},
			});
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					view:false,edit:false,add:false,del:true,search:false,
					beforeRefresh: function(){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
					},
				},
				// options for the Edit Dialog
				{},
				// options for the Add Dialog
				{},
				// options for the Delete Dailog
				{	afterSubmit : function( data, postdata, oper){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];
					},
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText;
					}
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'view');
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'edit');
					enableForm('#dialogForm');
					$("#deptcode").prop("readonly",true);
					
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
					$( "#dialogForm" ).dialog( "option", "title", "Add" );
					$("#dialogForm").dialog( "open" );
					enableForm('#dialogForm');

				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});
			
			function disableForm(formName){
				$('texarea').prop("readonly",true);
				$(formName+' input').prop("readonly",true);
				$(formName+' input[type=radio]').prop("disabled",true);
				//$(".ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable").hide();
				//$('.ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable').attr("disabled", true);				
				//$(formName+' a.input-group-addon btn btn-primary').hide("disabled",true);
				//$('.input-group-addon btn btn-primary.a').parent().find('.ion-more').hide();
			}
			
			function enableForm(formName){
				$('textarea').prop("readonly",true);
				$(formName+' input').prop("readonly",false);
				$(formName+' input[type=radio]').prop("disabled",false);
			}
					
			function populateFormdata(selRowId,state){
				if(!selRowId){
					alert('Please select row');
					return emptyFormdata();
				}
				switch(state) {
					case state = 'edit':
						$( "#dialogForm" ).dialog( "option", "title", "Edit" );
						$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
						break;
					case state = 'view':
						disableForm('#dialogForm');
						$( "#dialogForm" ).dialog( "option", "title", "View" );
						$( "#dialogForm" ).dialog( "option", "buttons", butt2 );
						//$('.input-group-addon btn btn-primary').attr("disabled", true);	
						break;
					default:
				}
				$("#dialogForm").dialog( "open" );
				rowData = $("#jqGrid").jqGrid ('getRowData', selRowId);
				$.each(rowData, function( index, value ) {
					var input=$("[name='"+index+"']");
					if(input.is("[type=radio]")){
						$("[name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
						console.log(index +'-'+value);
					}
				});
			}
			
			function emptyFormdata(){
				$('#formdata').trigger('reset');
				$('.help-block').html('');
			}
			
			function saveFormdata(oper){
				$.post( "deliveryDeptSave.php", $( "#formdata" ).serialize()+'&'+$.param({ 'oper': oper }) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText);
				}).success(function(data){
					$('#dialogForm').dialog('close');
					editedRow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				});
			}
			
			function errorText(text){
				$( "#formdata" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			
			populateSelect();
			function populateSelect(){
				$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['checked'])	{
						$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
						}
						else	{
							$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
						}
					}
				});
			}
			
			$('#Stext').keyup(function() {
				delay(function(){
					//search($('#Stext').val(),$('#Scol').val());
					search($('#Stext').val(),$('input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Scol').change(function(){
				//search($('#Stext').val(),$('#Scol').val());
				search($('#Stext').val(),$('input:radio[name=dcolr]:checked').val());
			});
			
			function search(Stext,Scol){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'deliveryDeptTbl.php?Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			////******************gridDialog2   
			var selText2,Dtable2,Dcols2;
			$("#gridDialog2").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 40, sorttype: 'number', checked:true}, 
					{ label: 'Description', name: 'description', width: 70},
				//],
				//width: 550,
				//viewrecords: true,
				//loadonce: true,
                //multiSort: true,
				//rowNum: 30,
				//pager: "#gridDialogPager2",
				//ondblClickRow: function(rowid, iRow, iCol, e){
					//var data=$("#gridDialog2").jqGrid ('getRowData', rowid);
					//$("#gridDialog2").jqGrid("clearGridData", true);
					//$( "#dialog2" ).dialog( "close" );
					//$('#'+selText2).val(rowid);
					//$('#'+selText2).focus();
					//$('#'+selText2).parent().next().html(data['description']);
				//},
				
				],
				width: 550,
				viewrecords: true,
				loadonce: true,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog2").jqGrid ('getRowData', rowid);
					$( "#dialog2" ).dialog( "close" );
					if(selText2=='deptcode' ){
						$('#description').val(data['description']);
					}
					$('#'+selText2).val(rowid);
					$('#'+selText2).focus();
					$('#'+selText2).parent().next().html(data['description']);
				},
				
			});
			
			function dialogHandler2(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText2=id,Dtable2=table,Dcols2=cols,
					$("#gridDialog2").jqGrid("clearGridData", true);
					$( "#dialog2" ).dialog( "open" );
					$( "#dialog2" ).dialog( "option", "title", title );
					$("#gridDialog2").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+table+'&cols='+cols}).trigger('reloadGrid');
					$('#Dtext2').val('');$('#Dcol2').html('');
					$.each(cols, function( index, value ) {
						if(value['checked'])	{
							$("#Dcol2").append( "<label class='radio-inline'><input type='radio' name='dcolr3' checked value='"+value+"' checked>"+value+"</input></label>" );
							//$("#Dcol2" ).append( "<label class='radio-inline'><input type='radio' name='dcolr3' value='"+value+"' >"+value+"</input></label>" );
						}
						else{
							$("#Dcol2").append( "<label class='radio-inline'><input type='radio' name='dcolr3' value='"+value+"' checked>"+value+"</input></label>" );
							//$("#Dcol2" ).append( "<label class='radio-inline'><input type='radio' name='dcolr3' checked value='"+value+"' >"+value+"</input></label>" );
						}
					});
				});
			}
			
			$('#Dtext2').keyup(function() {
				delay(function(){
					Dsearch2($('#Dtext2').val(),$('input:radio[name=dcolr3]:checked').val());
				}, 500 );
			});
			
			$('#Dcol2').change(function(){
				Dsearch2($('#Dtext2').val(),$('input:radio[name=dcolr3]:checked').val());
			});
			
			function Dsearch2(Dtext2,Dcol2){
				$("#gridDialog2").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+Dtable2+'&cols='+Dcols2+'&Dcol='+Dcol2+'&Dtext='+Dtext2}).trigger('reloadGrid');
			}
			 
			//////////////////////////////////////////////////////////////////
			
			//***********************menu
			$('#menu').metisMenu();
			
			
		});
		
	</script>
    
    <meta charset="utf-8" />
    <title>Material - Delivery Department</title>
</head>
<body>
	
	  
	<!-- </h1><div class="container" style="margin-bottom:1em">
		 <div class='row'>
			<form class="form-horizontal">
				<fieldset>
				<legend><h2>Delivery Department <small>Form</small></h2></legend>

				
				  </fieldset>
            </form>
         </div> -->
            
            <div class="container" style="margin-bottom:1em">
		<div class='row'></div>
        	<form id="searchForm" style='width:99%'>
            	<fieldset>
                	<div id="searchInContainer">
                    	<div id="Scol">Search By : </div>
                        </div>
                    <div style="padding-left: 65px;margin-top: 25px;padding-right: 60%;"><input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase"></div>
                 </fieldset>  
            </form>
        
        <br>
		
		<div class='row'>
			<div class='col-md-12'>
				<table id="jqGrid" class="table table-striped"></table>
				<div id="jqGridPager"></div>
			</div>
		</div>
        
        <div id="dialogForm" title="Dialog Form" >
        	<form class='form-horizontal' style='width:99%' id='formdata'>
            
				<div class="form-group">
				  <label class="col-md-2 control-label" for="deptcode">Delivery Store</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="deptcode" name="deptcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
        
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="addr1">Address</label>  
				  <div class="col-md-8">
				  <input id="addr1" name="addr1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr2" name="addr2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr3" name="addr3" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="addr4" name="addr4" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="tel">Telephone</label>  
				  <div class="col-md-4">
				  <input id="tel" name="tel" type="text" class="form-control input-sm" data-validation="required">
				 </div>
                 
                  <label class="col-md-2 control-label" for="generaltel">General Telephone</label>  
                  <div class="col-md-4">
				  <input id="generaltel" name="generaltel" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                  </div>
				
				 
				 <div class="form-group">
                 <label class="col-md-2 control-label" for="fax">Fax</label>  
				  <div class="col-md-4">
				  <input id="fax" name="fax" type="text" class="form-control input-sm" data-validation="required">
				</div>
				
				  
				  <label class="col-md-2 control-label" for="generalfax">General Fax</label>  
                  <div class="col-md-4">
				  <input id="generalfax" name="generalfax" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                  </div>
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="contactper">Contact Person</label>  
                  <div class="col-md-8">
				  <input id="contactper" name="contactper" type="text" class="form-control input-sm" data-validation="required">
				  </div>
                  </div>
                 
				 
                
                <div class="form-group">
				  <div class="col-md-10">
				  <input id="description" name="description" type="hidden" class="form-control input-sm" data-validation="required">
				  </div>
				</div>            
                
		
		<div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  </div>
				</div>
                
			</form>
		</div>
		
		 <div id="dialog" title="title">
         	 <form id="searchForm" style="width:99%">
				<fieldset>
                    <div id="searchInContainer">
                    	Search By : <div id="Dcol" style="float:right; margin-right: 87px;"></div>
                   
                   		<input  style="float:left; margin-left: 73px;" id="Dtext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
                   </div>
				</fieldset>
			</form>
            
			<div class='col-xs-12' align="center">
            <br>
				<table id="gridDialog" class="table table-striped"></table>
				<div id="gridDialogPager"></div>
			</div>
		</div>
        
        <div id="dialog2" title="title">
        	 <form id="searchForm" style="width:99%">
				<fieldset>
                    <div id="searchInContainer">
                    	Search By : <div id="Dcol2" style="float:right; margin-right: 80px;"></div>
                   
                   		<input  style="float:left; margin-left: 73px;" id="Dtext2" type="search" placeholder="Search here ..." class="form-control text-uppercase">
                   </div>
				</fieldset>
			</form>
            
			<div class='col-xs-12' align="center">
            <br>
				<table id="gridDialog2" class="table table-striped"></table>
				<div id="gridDialogPager2"></div>
			</div>
		</div>
         
         <!-------------------------- TestingAlert ------------------------------------->
         <div id="dialogAlert" title="Alert">
            <p id="dialogText"></p>
        </div>
        
	</div><!--/.container -->

    
</body>
</html>