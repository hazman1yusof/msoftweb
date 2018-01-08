
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;
		$(document).ready(function () {
			
			var editor = new Simditor({
				textarea: $('#contains'),
				toolbar:[
					'title',
					'bold',
					'italic',
					'underline',
					'strikethrough',
					'fontScale',
					'color',
					'ol',         
					'ul',             
					'blockquote',
					'code',           
					'table',
					'link',
					'image',
					'hr',             
					'indent',
					'outdent',
					'alignment',
					]
			});
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
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
			
			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 7/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
					dialogHandler('sysdb.users','msto',['username','name'],'Send To');
				},
				buttons :butt1,
			  });
			
			function getGridField(grid){
				var arr=[];
				$.each($(grid).jqGrid ('getGridParam', 'colModel'), function( index, value ) {
					arr.push(value.name);
				});
				return arr;
			}
			
			var urlParam={
				action:'get_table_default',
				field: '',
				except:['msfrom'],
				table_name:'sysdb.compose',
				table_id:'sysno'
			}
			
			$("#jqGrid").jqGrid({
				url: '../../../assets/php/entry.php?'+$.param(urlParam),
				datatype: "json",
				 colModel: [
					{ label: 'sysno', name: 'sysno', width: 90, hidden: true},
					{ label: 'compcode', name: 'compcode', width: 90, hidden: true},
					{ label: 'Type', name: 'type', width: 90, canSearch: true },
					{ label: 'Msg To', name: 'msto', width: 90, canSearch: true },
					{ label: 'Title', name: 'title', width: 90 },
					{ label: 'contains', name: 'contains', width: 90, hidden: true },
					{ label: 'dateFrom', name: 'dateFrom', width: 90 },
					{ label: 'dateTo', name: 'dateTo', width: 90 },
					{ label: 'readMsg:', name: 'readMsg', width: 90 , hidden: true},
					{ label: 'imgLoc', name: 'imgLoc', width: 90 ,hidden: true},
					{ label: 'upddate', name: 'upddate', width: 90 , hidden: true},
					{ label: 'upduser:', name: 'upduser', width: 90 , hidden: true},
					{ label: 'adddate', name: 'adddate', width: 90 , hidden: true},
					{ label: 'adduser', name: 'adduser', width: 90 , hidden: true},
					{ label: 'deldate:', name: 'deldate', width: 90 , hidden: true},
					{ label: 'deluser', name: 'deluser', width: 90 , hidden: true},
					{ label: 'recstatus:', name: 'recstatus', width: 90 , hidden: true},
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
					refreshGrid("#jqGrid")
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
						saveFormdata('del',{'debtorcode':selRowId});
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
					dialogHandler('sysdb.users','msto',['username','name'],$('#msto').val());
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
					dialogHandler('sysdb.users','msto',['username','name'],$('#msto').val());
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
					}else if(input.is("textarea")){
						editor.setValue(value);
					}else{
						input.val(value);
					}
				});
			}
			
			function frozeOnEdit(form){
				$(form+' input [frozeOnEdit]').prop("readonly",true);
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
					table_name:'sysdb.compose',
					table_id:'sysno'
				};
				$.post( "../../../assets/php/entry.php?"+$.param(param), $( "#formdata" ).serialize()+'&'+$.param(obj) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText);
				}).success(function(data){
					$('#dialogForm').dialog('close');
					editedRow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					refreshGrid("#jqGrid");
				});
			}
			
			function errorText(text,id){
				$( "#formdata"+id ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
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
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(urlParam)+'&Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
						}
					});
				});
				$("#"+id).on("blur", function(){
					checkInput(table,id,cols,$( "#"+id ).val());
				});
			}
			
			function checkInput(table,id,field,value){
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
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
					Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(paramD)+'&Scol='+Dcol+'&Stext='+Dtext}).trigger('reloadGrid');
			}
		});
		
 