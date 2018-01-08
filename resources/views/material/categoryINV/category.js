
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
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
							element : $(errorField[0]),
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
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							hideOne('#formdata');
							rdonly("#dialogForm");
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$('#formdata :input[hideOne]').show();
							rdonly("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']","input[name='computerid']","input[name='ipaddress']");
						dialog_stockacct.on();
						dialog_cosacct.on();
						dialog_adjacct.on();
						dialog_woffacct.on();
						dialog_expacct.on();
						dialog_loanacct.on();
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
					emptyFormdata(errorField,'#formdata');
					parent_close_disabled(false);
					//$('.alert').detach();
					$('#formdata .alert').detach();
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
				table_name:'material.category',
				table_id:'catcode',
				filterCol:['source', 'cattype'],
				filterVal:[$('#source2').val(), $('#cattype2').val()],
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.category',
				table_id:'catcode',
				saveip:'true'
			};

			//////////////////////////////////////////////////////////////////////////////////////////////


			//////////////////////////////// jQgrid /////////////////////////////////////////////////////
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{label: 'Compcode', name: 'compcode', width: 90 , hidden: true},
					{label: 'Category Code', name: 'catcode', width: 70, classes: 'wrap', checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 100, classes: 'wrap', canSearch: true},					
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
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true},					
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true},					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true},					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true},
					{label: 'deluser', name: 'deluser', width: 90 , hidden: true},					
					{label: 'deldate', name: 'deldate', width: 90 , hidden: true},					
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatter, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{label: 'idno', name: 'idno', hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
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
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
				
			});

			////////////////////////////formatter//////////////////////////////////////////////////////////
			function formatter(cellvalue, options, rowObject){
				if(cellvalue == 'A'){
					return "Active";
				}
				if(cellvalue == 'D') { 
					return "Deactive";
				}
			}


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
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata", oper,saveParam,urlParam,null,{'catcode':selRowId});
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
			addParamField('#jqGrid',false,saveParam,['idno', 'computerid', 'ipaddress', 'adduser', 'adddate']);

			////////////////////////////////////////////////////ordialog////////////////////////////////////////		
			var dialog_stockacct = new ordialog(
				'stockacct','finance.glmasref','#stockacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select Stock Account",
					open: function(){
						
					}
				}
			);
			dialog_stockacct.makedialog();

			var dialog_cosacct = new ordialog(
				'cosacct','finance.glmasref','#cosacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select COS Account",
					open: function(){
						
					}
				}
			);
			dialog_cosacct.makedialog();

			var dialog_adjacct = new ordialog(
				'adjacct','finance.glmasref','#adjacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select Adjusment Account",
					open: function(){
						
					}
				}
			);
			dialog_adjacct.makedialog();

			var dialog_woffacct = new ordialog(
				'woffacct','finance.glmasref','#woffacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select Write Off Account",
					open: function(){
						
					}
				}
			);
			dialog_woffacct.makedialog();

			var dialog_expacct = new ordialog(
				'expacct','finance.glmasref','#expacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select Expenses Account",
					open: function(){
						
					}
				}
			);
			dialog_expacct.makedialog();

			var dialog_loanacct = new ordialog(
				'loanacct','finance.glmasref','#loanacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
					ondblClickRow:function(){
					}	
				},{
					title:"Select Loan Account",
					open: function(){
						
					}
				}
			);
			dialog_loanacct.makedialog();
});
		