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
				width: 7/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					
					emptyFormdata();
					if((oper!='view') && (oper!='edit')){
					dialogHandler('sysdb.users','authorid',['username','name','password','deptcode'], 'Author ID');
					}
				},
				buttons :
					[{
						text: "Save",click: function() {
							if( $('#formdata').isValid({requiredFields: ''}, {}, true) ) {
								saveFormdata(oper);
							}
						}
					},{
						text: "Cancel",click: function() {
							$(this).dialog('close');
						}
					}]
			  })
			  .dialogExtend({
				"closable" : true,
			  });
			
			
			$("#jqGrid").jqGrid({
				url: 'authorizationTbl.php',
				editurl: 'authorizationSave.php',
				datatype: "json",
				 colModel: [
				 
					{label: 'Compcode', name: 'compcode', sorttype: 'number', hidden:true},
					
					{label: 'Author ID', name: 'authorid', width: 90 ,  classes: 'wrap' , checked:true, canSearch: 
					true,editable: true, 
					editrules:{required: true},
					formoptions:{rowpos: 1, colpos: 1}},		
											
					{label: 'Name', name: 'name', width: 90,  classes: 'wrap' , canSearch: true,  editable: true, 
					editrules:{required: true},
					formoptions:{rowpos: 2, colpos: 1}},
							
					{label: 'Password', name: 'password', width: 90 ,  classes: 'wrap' , hidden: true, editable: true},
					
					{label: 'Department Code', name: 'deptcode', width: 90 ,  classes: 'wrap' , editable: true, 
					editrules:{required: true},
					},
					
					{label: 'Active', name: 'active', width: 90 ,   editable: true, 
					editrules:{required: true},
					},
					
					{label: 'adddate', name: 'adddate', width: 90 , hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
					
					{label: 'adduser', name: 'adduser', width: 90 , hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
				
					{label: 'upduser', name: 'upduser', width: 90,hidden:true},
					
					{label: 'upddate', name: 'upddate', width: 90,hidden:true},
				
				
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
					$("#authorid").prop("readonly",true);
					
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
					//$("#glaccount").prop("readonly",false);
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});
			
			function disableForm(formName){
				$('texarea').prop("readonly",true);
				$(formName+' input').prop("readonly",true);
				$(formName+' input[type=radio]').prop("disabled",true);
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
			
			
			function dialogHandler(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					//$(".ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle").css("color","blue");
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+table+'&cols='+cols}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					$.each(cols, function( index, value ) {
						$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value+"' >"+value+"</input></label>" );
					});
				});
			}
			
			function saveFormdata(oper){
				$.post( "authorizationSave.php", $( "#formdata" ).serialize()+'&'+$.param({ 'oper': oper }) , function( data ) {
					
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
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'username', name: 'username', width: 80, hidden:true},
					{ label: 'name', name: 'name', width: 50, checked:true, canSearch:true}, 
					{ label: 'password', name: 'password', width: 80, canSearch:true},
					{ label: 'deptcode', name: 'deptcode', width: 80, hidden:true},
				],
				width: 550,
				//height: 180,
				viewrecords: true,
				loadonce: true,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$( "#dialog" ).dialog( "close" );
					$('#'+selText).val(rowid);
					$('#name').val(data['name']);
					$('#password').val(data['password']);
					$('#deptcode').val(data['deptcode']);
					$('#'+selText).focus();
				},
				
			});
			
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
					search($('#Stext').val(),$('input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Scol').change(function(){
				search($('#Stext').val(),$('input:radio[name=dcolr]:checked').val());
			});
			
			function search(Stext,Scol){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'authorizationTbl.php?Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			function dialogHandler(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+table+'&cols='+cols}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					cols=$('#gridDialog').jqGrid('getGridParam','colModel');
					$.each(cols, function( index, value ) {
						if(value['canSearch']){
							if(!value['checked'])	{
								$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' value='"+value['name']+"' checked>"+value['name']+"</input></label>" );
							}else	{
								$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' value='"+value['name']+"' >"+value['name']+"</input></label>" );
							}
						}
						
					});
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					//Dsearch($('#Dtext').val(),$('#Dcol').val());
					Dsearch($('#Dtext').val(),$('input:radio[name=dcolr2]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				//console.log($('input:radio[name=dcolr]:checked').val());
				//Dsearch($('#Dtext').val(),$('#Dcol').val());
				Dsearch($('#Dtext').val(),$('input:radio [name=dcolr2]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+Dtable+'&cols='+Dcols+'&Dcol='+Dcol+'&Dtext='+Dtext}).trigger('reloadGrid');
			}
			  
			////////////////////////////////// menu  ////////////////////////////////
			$('#menu').metisMenu();
			
			
		});

 </script>