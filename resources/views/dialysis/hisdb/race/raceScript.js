
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 9/10 * $(window).width(),
				modal: true,
			});
			
			$.validate({
				language : {
					requiredFields: ''
				},
			});
			
			var errorField=[];
			conf = {
				onValidate : function($form) {
					if(errorField.length>0){
						return {
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
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
			
			/*var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
					if(oper!='view'){
					dialogHandler('finance.costcenter','ccode',['costcode','description'], 'Cost Code');
					dialogHandler('finance.glmasref','glaccno',['glaccount','description'],'GL Account');
					}
				},
				
				close: function( event, ui ) {
					$('.alert').detach(); //padam error bila reopen
					$("#formdata a").off(); //button bila view tak boleh bukak
				},
				
				buttons :butt1,
			  });*/
			
			
			var urlParam={
				action:'get_table_default',
				field:'',
				except:['adduser','adddate','upduser','upddate','deluser','deldate', 'recstatus'],
				table_name:'hisdb.racecode',
				table_id:'Code'
			}
			
			$("#jqGrid").jqGrid({
				url: '../../../../assets/php/entry.php?'+$.param(urlParam)+'&source='+$('#source2').val(), 
				datatype: "json",
				 colModel: [
					{ label: 'No', name: 'sysno', width: 5, sorttype: 'number',hidden:true},
					{ label: 'Comp', name: 'Comp', hidden:true},						
					{ label: 'Code', name: 'Code', width: 10, classes: 'wrap', canSearch: true, checked:true, editable: true, 
							editrules:{required: true}, 
							editoptions: {maxlength: 2},
						},
					{ label: 'Description', name: 'Description', classes: 'wrap', canSearch: true, width: 80, editable: true,
							editrules:{required: true}, 
							editoptions: { maxlength: 100},
					},
					{ label: 'createdBy', name: 'createdBy', width: 80,hidden:true},
					{ label: 'createdDate', name: 'createdDate', width: 90,hidden:true},
					{ label: 'Lastupdate', name: 'Lastupdate', width: 80,hidden:true},
					{ label: 'LastUser', name: 'LastUser', width: 90,hidden:true},
					{ label: 'RecStatus', name: 'RecStatus', width: 80,hidden:true},
					{ label: 'UpdUser', name: 'UpdUser', width: 80,hidden:true},
					{ label: 'UpdDate', name: 'UpdDate', width: 90,hidden:true},
				], 
				],
				
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}
				},
				
			});
			
			function refreshGrid(grid){
				$(grid).jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
			}
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					view:false,edit:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid("#jqGrid");
					},
				},{},{},
				{	afterSubmit : function( data, postdata, oper){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];
					},
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText;
					}
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper='del';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata();
					}else{
						saveFormdata('del',{'paymode':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'view');
					checkInput('finance.costcenter','ccode',['costcode','description'],$('#ccode').val());
					checkInput('finance.glmasref','glaccno',['glaccount','description'], $('#glaccno').val());
					
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
					checkInput('finance.costcenter','ccode',['costcode','description'],$('#ccode').val());
					checkInput('finance.glmasref','glaccno',['glaccount','description'], $('#glaccno').val());
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
					$( "#dialogForm" ).dialog( "open" );
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
			}
			
			function enableForm(formName){
				$('textarea').prop("readonly",false);
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
						enableForm('#dialogForm');
						frozeOnEdit("#dialogForm");
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
			
			function frozeOnEdit(form){
				$(form+' input[frozeOnEdit]').prop("readonly",true);
			}
			
			function emptyFormdata(){
				errorField.length=0;
				$('#formdata').trigger('reset');
				$('.help-block').html('');
			}
			
			function saveFormdata(oper,obj){
				if(obj==null){
					obj={null:'null'};
				}
				var param={
					action:'save_table_default',
					oper:oper,
					table_name:'debtor.paymode',
					table_id:'paymode'
				};
				
				$.post( "../../../../assets/php/entry.php?"+$.param(param), $( "#formdata" ).serialize()+'&'+$.param(obj) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText);
				}).success(function(data){
					$('#dialogForm').dialog('close');
					editedRow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					refreshGrid("#jqGrid");
				});
			}
			
			function errorText(text){
				$( ".ui-dialog-buttonpane" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 100, classes: 'pointer', canSearch:true}, 
					{ label: 'Description', name: 'desc', width: 200, classes: 'pointer', canSearch:true},
				
		
				],
				width: 680,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$( "#gridDialog" ).jqGrid( "clearGridData", true );
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
						if(value['checked']){
							$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
						}
						else{
							$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
						}
					}
				});
			}
			
			$('#Stext').keyup(function() {
				delay(function(){
					search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Scol').change(function(){
				search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
			});
			
			function search(Stext,Scol){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(urlParam)+'&Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#gridDialog" ).jqGrid( "clearGridData", true );
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					//$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
					$.each(cols, function( index, value ) {
						if(value['checked'])	{
							$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' checked value='"+value+"' checked>"+value+"</input></label>" );
						}
						else{
							$("#Dcol").append( "<label class='radio-inline'><input type='radio' name='dcolr2' value='"+value+"' checked>"+value+"</input></label>" );
						}
					});
				});
				$("#"+id).on("blur", function(){
					checkInput(table,id,cols,$( "#"+id ).val());
				});
			}
			
			function checkInput(table,id,field,value){
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						var index = errorField.indexOf(id);
						if (index > -1) {
							errorField.splice(index, 1);
						}
						$( "#"+id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						errorField.push(id);
						$( "#"+id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( "#"+id ).removeClass( "valid" ).addClass( "error" );
						$( "#"+id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
					}
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr2]:checked').val());
					
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr2]:checked').val());
				
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)+'&Scol='+Dcol+'&Stext='+Dtext}).trigger('reloadGrid');
			}
			
		});
		