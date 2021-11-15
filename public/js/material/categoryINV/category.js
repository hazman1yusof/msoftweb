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
			//////////////////////////////////////////////////////////////

			var fdl = new faster_detail_load();
						
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

			var oper = 'add';
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
					$('.my-alert').detach();
					dialog_stockacct.off();
					dialog_cosacct.off();
					dialog_adjacct.off();
					dialog_woffacct.off();
					dialog_expacct.off();
					dialog_loanacct.off();
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
				url:'util/get_table_default',
				field:'',
				table_name:'material.category',
				table_id:'catcode',
				filterCol:['source', 'cattype','class'],
				filterVal:[$('#source').val(), $('#cattype').val(), $('#class').val()],
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'categoryinv/form',
				field:'',
				oper:oper,
				table_name:'material.category',
				table_id:'catcode',
				saveip:'true',
				checkduplicate: 'true'
			};

			//////////////////////////////////////////////////////////////////////////////////////////////


			//////////////////////////////// jQgrid /////////////////////////////////////////////////////
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{label: 'Compcode', name: 'compcode', width: 90 , hidden: true},
					{label: 'Category Code', name: 'catcode', width: 38, classes: 'wrap', checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 80, classes: 'wrap', canSearch: true},					
					{label: 'Category Type', name: 'cattype', width: 90 , hidden: true},					
					{label: 'Source', name: 'source', width: 90 , hidden: true},								
					{label: 'Class', name: 'class', width: 90 , hidden: true},				
					{label: 'Stock <br> Account', name: 'stockacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'COS <br> Account', name: 'cosacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'Adjustment <br> Account', name: 'adjacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'Write Off <br> Account', name: 'woffacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'Expenses <br> Account', name: 'expacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'Loan <br> Account', name: 'loanacct', width: 70, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},					
					{label: 'PO Validate', name: 'povalidate', width: 30, classes: 'wrap', formatter:formatter, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },					
					{label: 'accrualacc', name: 'accrualacc', width: 90, hidden: true},					
					{label: 'stktakeadjacct', name: 'stktakeadjacct', width: 90, hidden: true},					
					{label: 'adduser', name: 'adduser', width: 90 , hidden: true},					
					{label: 'adddate', name: 'adddate', width: 90 , hidden: true},					
					{label: 'upduser', name: 'upduser', width: 90 , hidden: true},					
					{label: 'upddate', name: 'upddate', width: 90 , hidden: true},
					{label: 'deluser', name: 'deluser', width: 90 , hidden: true},					
					{label: 'deldate', name: 'deldate', width: 90 , hidden: true},					
					{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
					},
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
				sortname: 'idno',
				sortorder: 'desc',
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
					fdl.set_array().reset();
				},
				
			});

			////////////////////////formatter tick///////////////////////////////////////////////////////////
			function formatterstatus_tick2(cellvalue, option, rowObject) {
				if (cellvalue == '1') {
					return `<span class="fa fa-check"></span>`;
				}else{
					return '';
				}
			}
		
		
			function unformatstatus_tick2(cellvalue, option, rowObject) {
				if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
					return '1';
				}else{
					return '0';
				}
			}

			function formatter(cellvalue, options, rowObject){
				return parseInt(cellvalue) ? "Yes" : "No";
			}

			function unformat(cellvalue, options){
				//return parseInt(cellvalue) ? "Yes" : "No";

				if (cellvalue == 'Yes') {
					return "1";
				}
				else {
					return "0";
				}
			}

			//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
			function showdetail(cellvalue, options, rowObject){
				var field,table,case_;
				switch(options.colModel.name){
					case 'stockacct':field=['glaccno','description'];table="finance.glmasref";case_='stockacct';break;
					case 'cosacct':field=['glaccno','description'];table="finance.glmasref";case_='cosacct';break;
					case 'adjacct':field=['glaccno','description'];table="finance.glmasref";case_='adjacct';break;
					case 'woffacct':field=['glaccno','description'];table="finance.glmasref";case_='woffacct';break;
					case 'expacct':field=['glaccno','description'];table="finance.glmasref";case_='expacct';break;
					case 'loanacct':field=['glaccno','description'];table="finance.glmasref";case_='loanacct';break;
				}
				var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

				fdl.get_array('category',options,param,case_,cellvalue);
				// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
				
				return cellvalue;
			}

			function unformat_showdetail(cellvalue, options, rowObject){
				return $(rowObject).attr('title');
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
						saveFormdata("#jqGrid","#dialogForm","#formdata", oper,saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
					recstatusDisable();
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
			addParamField('#jqGrid',false,saveParam,['idno', 'computerid', 'ipaddress', 'adduser', 'adddate', 'upduser', 'upddate', 'recstatus']);

			////////////////////////////////////////////////////ordialog////////////////////////////////////////		
			var dialog_stockacct = new ordialog(
				'stockacct','finance.glmasref','#stockacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#woffacct').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#woffacct').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
				},{
					title:"Select Stock Account",
					open: function(){
						dialog_stockacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_stockacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam','radio', 'tab'
			);
			dialog_stockacct.makedialog();

			var dialog_cosacct = new ordialog(
				'cosacct','finance.glmasref','#cosacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#expacct').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#expacct').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
				},{
					title:"Select COS Account",
					open: function(){
						dialog_cosacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_cosacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam','radio','tab'
			);
			dialog_cosacct.makedialog();

			var dialog_adjacct = new ordialog(
				'adjacct','finance.glmasref','#adjacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#loanacct').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#loanacct').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Adjustment Account",
					open: function(){
						dialog_adjacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_adjacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_adjacct.makedialog();

			var dialog_woffacct = new ordialog(
				'woffacct','finance.glmasref','#woffacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
						],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#cosacct').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#cosacct').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Write Off Account",
					open: function(){
						dialog_woffacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_woffacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam','radio', 'tab'
			);
			dialog_woffacct.makedialog();

			var dialog_expacct = new ordialog(
				'expacct','finance.glmasref','#expacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#adjacct').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#adjacct').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Expenses Account",
					open: function(){
						dialog_expacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_expacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_expacct.makedialog();

			var dialog_loanacct = new ordialog(
				'loanacct','finance.glmasref','#loanacct',errorField,
				{	colModel:[
						{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#povalidate').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#povalidate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
				},{
					title:"Select Loan Account",
					open: function(){
						dialog_loanacct.urlParam.filterCol=['recstatus', 'compcode'],
						dialog_loanacct.urlParam.filterVal=['ACTIVE','session.compcode']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_loanacct.makedialog();
});
		