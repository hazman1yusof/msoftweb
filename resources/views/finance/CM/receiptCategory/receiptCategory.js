
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
			
			////////////////////object for dialog handler//////////////////
			
			dialog_stockacct=new makeDialog('material.category','#stockacct',['catcode','description'], 'Stock Account');
			dialog_cosacct=new makeDialog('material.category','#cosacct',['catcode','description'], 'COS Account');
			dialog_adjacct=new makeDialog('material.category','#adjacct',['catcode','description'], 'Adjusment Account');
			dialog_woffacct=new makeDialog('material.category','#woffacct',['catcode','description'], 'Write Off Account');
			dialog_expacct=new makeDialog('material.category','#expacct',['catcode','description'], 'Expenses Account');
			dialog_loanacct=new makeDialog('material.category','#loanacct',['catcode','description'], 'Loan Account');	
						
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
						dialog_stockacct.handler();
						dialog_cosacct.handler();
						dialog_adjacct.handler();
						dialog_woffacct.handler();
						dialog_expacct.handler();
						dialog_loanacct.handler();
					}
					if(oper!='add'){
						dialog_stockacct.check();
						dialog_cosacct.check();
						dialog_adjacct.check();
						dialog_woffacct.check();
						dialog_expacct.check();
						dialog_loanacct.check();
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
				table_name:['material.category'],
				table_id:'catcode',
				filterCol:['source'],
				filterVal:[$('#source2').val()]
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.category',
				table_id:'catcode'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [

				 {label: 'Compcode', name: 'compcode', width: 90 , hidden: true},
					{label: 'Category Code', name: 'catcode', width: 45, checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 200, classes: 'wrap', canSearch: true},					
					{label: 'Category Type', name: 'cattype', width: 90 , hidden: true},					
					{label: 'Source', name: 'source', width: 90 , hidden: true},					
					{label: 'Stock Account', name: 'stockacct', width: 90 ,  hidden: true},					
					{label: 'COS Account', name: 'cosacct', width: 90, hidden: true,},					
					{label: 'Adjustment Account', name: 'adjacct', width: 90, hidden: true},					
					{label: 'Write Off Account', name: 'woffacct', width: 90, hidden: true},					
					{label: 'Expenses Account', name: 'expacct', width: 90, hidden: true},					
					{label: 'Loan Account', name: 'loanacct', width: 90, hidden: true},					
					{label: 'PO Validate', name: 'povalidate', width: 90, hidden: true},					
					{label: 'accrualacc', name: 'accrualacc', width: 90, hidden: true},					
					{label: 'stktakeadjacct', name: 'stktakeadjacct', width: 90, hidden: true},					
					{label: 'Created By', name: 'adduser', width: 90 },					
					{label: 'Date Created', name: 'adddate', width: 90 },					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true},					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true},
					{label: 'deluser', name: 'deluser', width: 90 , hidden: true},					
					{label: 'deldate', name: 'deldate', width: 90 , hidden: true},					
					{label: 'Status', name: 'recstatus', width: 90, classes: 'wrap',cellattr: function(rowid, cellvalue)
					{return cellvalue == 'D' ? ' class="alert alert-danger"' : ''},}
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'catcode':selRowId});
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
		