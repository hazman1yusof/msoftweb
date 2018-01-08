<script>
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
		
			$("#effectdate").datepicker({
				dateFormat: 'dd-mm-yy',
			});
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				//width: 600,
				width: 7/10 * $(window).width(),
				modal: true,
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
			
			
			$.validate({
				modules : 'date',
				language : {
					requiredFields: ''
				},
			});
			
			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
						dialogHandler('costcenter','actdebccode',['costcode','description'],'Actual Cost');
						dialogHandler('glmasref','actdebglacc',['glaccount','description'], 'Actual Account');
						dialogHandler('costcenter','depccode',['costcode','description'], 'Deposit Cost');
						dialogHandler('glmasref','depglacc',['glaccount','description'], 'Deposit Account');
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
			  });
			
			
			$("#jqGrid").jqGrid({
				url: 'debtorTypeTbl.php',
				editurl: 'debtorTypeSave.php',
				datatype: "json",
				 colModel: [
					{label: 'Compcode', name: 'compcode', sorttype: 'number', hidden:true},
					
					{label: 'Financial Class', name: 'debtortycode', width: 90 , classes: 'wrap' , canSearch: true,  checked:true, editable: true, 
					editrules:{required: true},
					formoptions:{rowpos: 1, colpos: 1}},		
											
					{label: 'Description', name: 'description', width: 90, classes: 'wrap' , canSearch: true, editable: true, 
					editrules:{required: true},
					formoptions:{rowpos: 2, colpos: 1}},
							
					{label: 'Effect Date', name: 'effectdate', width: 90 , hidden: true, editable: true},
					
					{label: 'Actual Cost Code', name: 'actdebccode', width: 90 , classes: 'wrap' , hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
					
					{label: 'Actual Account', name: 'actdebglacc', width: 90 , classes: 'wrap' , hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
					
					{label: 'Deposit Cost Code', name: 'depccode', width: 90 , classes: 'wrap' ,hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
					
					{label: 'Deposit Account', name: 'depglacc', width: 90 , classes: 'wrap' , hidden:true,  editable: true , 
					editrules: { required: true, edithidden: true , hidedlg: true},
					},
				
					{label: 'Last User', name: 'lastuser', width: 90,classes: 'wrap' , hidden:true},
					
					{label: 'Last Update', name: 'lastupdate', width: 90, classes: 'wrap' , hidden:true},
					
					{label: 'Reg Fees', name: 'regfees', width: 90,classes: 'wrap' , hidden:true},
					
					{label: 'Deposit Received From Patient', name: 'typegrp', width: 90 , classes: 'wrap' , hidden: true, editable: true,
					},
					
					{label: 'Update Payer Name', name: 'updpayername', width: 90, classes: 'wrap' , hidden:true},
					
					{label: 'Update Episode', name: 'updepisode', width: 90, classes: 'wrap' , hidden:true}, 
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: true,
				width: 780,
				height: 350,
				rowNum: 30,
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
					$("#debtortycode").prop("readonly",true);
					
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
				enableForm('#dialogForm');
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
					}
				});
			}
			
			
			
			function emptyFormdata(){
				$('#formdata').trigger('reset');
				$('.help-block').html('');
			}
			
			function saveFormdata(oper){
				$.post( "debtorTypeSave.php", $( "#formdata" ).serialize()+'&'+$.param({ 'oper': oper }) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText);
				}).success(function(data){
					$('#dialogForm').dialog('close');
					editedRow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				});
			}
			
			function errorText(text){
				$( "#formdata" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;	                     </a><strong>Error!</strong> "+text+"</div>");
			}
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 50, classes: 'pointer', checked:true}, 
					{ label: 'Description', name: 'desc', width: 200, classes: 'pointer'},
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
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'debtorTypeTbl.php?Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
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
						if(value['checked'])	{
							$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' checked value='"+value+"' checked>"+value+"</input></label>" );
						}
						else{
							$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' value='"+value+"' checked>"+value+"</input></label>" );
						}
					});
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('input:radio[name=dcolr2]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				console.log($('input:radio[name=dcolr]:checked').val());
				Dsearch($('#Dtext').val(),$('input:radio [name=dcolr2]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../getDialog.php?table='+Dtable+'&cols='+Dcols+'&Dcol='+Dcol+'&Dtext='+Dtext}).trigger('reloadGrid');
			}
			
			$('#menu').metisMenu();
			
		});

	</script>