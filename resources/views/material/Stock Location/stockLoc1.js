
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
						switch(oper){
							case 'add':
								saveFormdata(oper,{itemcode:$('#itemcode').val()});
								break;
							case 'edit':
								saveFormdata(oper,{itemcode:$('#itemcode').val(),sysno:selectedRow("#jqGrid")});
								break;
						}
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
			
			dialogHandler('material.product','itemcode',['itemcode','description'],'Item');
			
			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 7/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
					dialogHandler('sysdb.department','deptcode',['deptcode','description'],'Department');
					dialogHandler('material.uom','uomcode',['uomcode','description'],'UOM');
				},
				buttons :butt1,
			  });
			
			var urlParam={
				action:'get_table_default',
				field:'',
				except:['sysno','netmvqty2','netmvqty3','netmvqty4','netmvqty5','netmvqty6','netmvqty7','netmvqty8','netmvqty9','netmvqty10','netmvqty11','netmvqty12','netmvval2','netmvval3','netmvval4','netmvval5','netmvval6','netmvval7','netmvval8','netmvval9','netmvval10','netmvval11','netmvval12'],
				table_name:'material.stockloc',
				table_id:'sysno',
				filter:{}
			}
			
			$("#jqGrid").jqGrid({
				url: '',
				datatype: "local",
				 colModel: [
				 
					{label: 'compcode', name: 'compcode', width: 10 , hidden: true},
					
					{label: 'Department Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
					editrules:{ required: true}}, 
					
					{label: 'Item Code', name: 'itemcode', width: 90 , classes: 'wrap', hidden: true, editable: true},
					
					{label: 'UOM Code', name: 'uomcode', width: 90 , classes: 'wrap', editable: true,
					editrules:{ required: true}}, 
					
					{label: 'Bin Code', name: 'bincode', width: 50 , classes: 'wrap', hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Rack No', name: 'rackno', width: 50 , classes: 'wrap', hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'year', name: 'year', width: 90 , hidden: true, editable: true},
					
					{label: 'openbalqty', name: 'openbalqty', width: 90 , hidden: true, editable: true},
					
					{label: 'openbalval', name: 'openbalval', width: 90 , hidden: true, editable: true},
					
					{label: 'netmvqty1', name: 'netmvqty1', width: 90 , hidden: true, editable: true},
					
					{label: 'netmvval1', name: 'netmvval1', width: 90 , hidden: true, editable: true},
					
					{label: 'Tran Type', name: 'stocktxntype', width: 50 , classes: 'wrap', editable: true ,
					editrules:{ required: true}},
						
					{label: 'Disp Type', name: 'disptype', width: 50 , classes: 'wrap', editable: true ,
					editrules:{ required: true}}, 
					
					{label: 'qtyonhand', name: 'qtyonhand', width: 90 , hidden: true, editable: true},
					
					{label: 'Min Stock Qty', name: 'minqty', width: 60 , classes: 'wrap', editable: true ,
					editrules:{ required: true}},
									
					{label: 'Max Stock Qty', name: 'maxqty', width: 60 , classes: 'wrap', editable: true ,
					editrules:{ required: true}},
					
					{label: 'Reorder Level', name: 'reordlevel', width: 60 , classes: 'wrap', editable: true ,
					editrules:{ required: true}},
					
					{label: 'Reorder Quantity', name: 'reordqty', width: 60 , classes: 'wrap', editable: true ,
					editrules:{ required: true}},     
					
					{label: 'lastissdate', name: 'lastissdate', width: 90 , hidden: true, editable: true},
					
					{label: 'frozen', name: 'frozen', width: 90 , hidden: true, editable: true},
					
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true, editable: true},
					
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true, editable: true},
					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true, editable: true},
					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true, editable: true},
					
					{label: 'cntdocno', name: 'cntdocno', width: 90 , hidden: true, editable: true},
					
					{label: 'fix_uom', name: 'fix_uom', width: 90 , hidden: true, editable: true},
					
					{label: 'locavgcs', name: 'locavgcs', width: 90 , hidden: true, editable: true},
					
					{label: 'lstfrzdt', name: 'lstfrzdt', width: 90 , hidden: true, editable: true},
					
					{label: 'lstfrztm', name: 'lstfrztm', width: 90 , hidden: true, editable: true},
					
					{label: 'frzqty', name: 'frzqty', width: 90 , hidden: true, editable: true},
					
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
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
			
			function selectedRow(grid){
				return $(grid).jqGrid ('getGridParam', 'selrow');
			}
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					edit:false,view:false,add:false,del:false,search:false,
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
						saveFormdata('del',{'sysno':selRowId});
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
					checkInput('sysdb.department','deptcode',['deptcode','description'],$('#deptcode').val());
					checkInput('material.uom','uomcode',['uomcode','description'],$('#uomcode').val());
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
					checkInput('sysdb.department','deptcode',['deptcode','description'],$('#deptcode').val());
					checkInput('material.uom','uomcode',['uomcode','description'],$('#uomcode').val());
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper='add';
					if( $('#itemcode').val() != '') {
						$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
						$( "#dialogForm" ).dialog( "option", "title", "Add" );
						$( "#dialogForm" ).dialog( "open" );
						enableForm('#dialogForm');
					}
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
					table_name:'material.stockloc',
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
			
			function errorText(text){
				$( "#formdata" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 50, classes: 'pointer', canSearch:true}, 
					{ label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch:true},
				],
				width: 680,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$( "#dialog" ).dialog( "close" );
					if(selText=='#itemcode'){
						urlParam.filter = {itemcode:rowid};
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(urlParam)}).trigger('reloadGrid');			
					}
					$('#'+selText).val(rowid);
					$('#'+selText).focus();
					$('#'+selText).parent().next().html(data['description']);
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
			
			var paramD={action:'get_table_default',table_name:'',field:'',table_id:''};
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
			
			$( "input:radio" ).click(function() {
				if($(this).attr('value')=='Transfer'){
					$("input:radio[value='TR Item']").prop('checked', true);
				}
			    if($(this).attr('value')=='Issue'){
					$("input:radio[value='IS Item']").prop('checked', true);
				}
			});
			
		});
	