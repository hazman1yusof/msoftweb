
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			/////////////////////////validation//////////////////////////
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

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
				}
			}];

			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						
					}
					if(oper!='add'){
						
					}
				},
				close: function( event, ui ) {
					emptyFormdata('#formdata');
					$('.alert').detach();
					$("#formdata a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'hisdb.agreement',
				table_id:'AgreementID'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'hisdb.agreement',
				table_id:'AgreementID'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Compcode', name: 'compcode', hidden:true},	
										
					{ label: 'Agreement ID', name: 'AgreementID', width: 10, checked:true, canSearch: true},
						
					{ label: 'Agreement No', name: 'AgreementNo', width: 10, canSearch: true},

					{ label: 'Status', name: 'Status', width: 80, hidden:true, classes: 'wrap' ,  editable: true},
					
					{ label: 'Agreement Date', name: 'AgreementDate', width: 80, classes: 'wrap' , hidden:true,  editable: true},
					
					{ label: 'Join Date', name: 'JoinDate', width: 90, classes: 'wrap' , hidden:true,  editable: true},
					
					{ label: 'Exp Date', name: 'expdate', width: 80, classes: 'wrap' ,  hidden:true,  editable: true},
					
					{ label: 'Pkg Code', name: 'pkgcode', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'Add Date', name: 'adddate', width: 80, classes: 'wrap' , hidden:true},
					{ label: 'Add User', name: 'adduser', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'Add Date', name: 'upddate', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'Add User', name: 'upduser', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'remarks', name: 'remarks', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'record status', name: 'recstatus', width: 90, hidden:true, classes: 'wrap',cellattr: function(rowid, cellvalue){
						return cellvalue == 'D' ? ' class="alert alert-danger"' : ''},},					
					{ label: 'del user', name: 'deluser', classes: 'wrap' , width: 90,hidden:true},
					{ label: 'add date', name: 'adddate', classes: 'wrap' , width: 80,hidden:true},
					{ label: 'del date', name: 'deldate', classes: 'wrap' , width: 90,hidden:true},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper='del';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'AgreementID':selRowId});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['depamt']);
		});
		