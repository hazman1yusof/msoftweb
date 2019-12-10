
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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
			//////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////

			var dialog_depccode = new ordialog(
				'glccode','finance.costcenter','#glccode',errorField,
				{	colModel:[
						{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#glaccno').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glaccno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Deposit Cost",
					open: function(){
						dialog_depccode.urlParam.filterCol=['compcode','recstatus'],
						dialog_depccode.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_depccode.makedialog(true);


			var dialog_depglacc = new ordialog(
				'glaccno','finance.glmasref','#glaccno',errorField,
				{	colModel:[
						{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Deposit GL Account",
					open: function(){
						dialog_depglacc.urlParam.filterCol=['compcode','recstatus'],
						dialog_depglacc.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_depglacc.makedialog(true);

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
							rdonly("#formdata");
							hideOne("#formdata");
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#formdata");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							$('#formdata :input[hideOne]').show();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_depccode.on();
						dialog_depglacc.on();
						
					}
					if(oper!='add'){
						dialog_depccode.check(errorField);
						dialog_depglacc.check(errorField);
					
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('.my-alert').detach();
					dialog_depccode.off();
					dialog_depglacc.off();
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
				table_name:'finance.apacthdr',
				table_id:'auditno',
				filterCol: ['source', 'trantype'],
				filterVal: ['CM', 'FT'],
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'bankTransfer/form',
				field:'',
				oper:oper,
				table_name:'finance.apacthdr',
				table_id:'auditno',
				sysparam: {source: 'CM', trantype: 'FT', useOn: 'auditno'},
				sysparam2: {source: 'HIS', trantype: 'PV', useOn: 'pvno'},
				saveip:'true',
				checkduplicate:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'compcode', name: 'compcode', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'idno', name: 'idno', width: 10 , hidden: true,  classes: 'wrap'},
				 	{label: 'source', name: 'source', width: 10, hidden: true, classes: 'wrap'},
				 	{label: 'trantype', name: 'trantype', width: 10, hidden: true, classes: 'wrap'},
					{label: 'Audit No', name: 'auditno', width: 27, classes: 'wrap'},
					{label: 'Payment No', name: 'pvno', width: 40, hidden: true, classes: 'wrap'},
					{label: 'Transfer Date', name: 'actdate', width: 25, canSearch:true, checked:true, classes: 'wrap'},
					{label: 'Bank Code From', name: 'bankcode', width: 35, classes: 'wrap'},
					{label: 'Bank Code To', name: 'payto', width: 35, classes: 'wrap'},
					{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
					{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency'},
					{label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap'},
					{label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', formatter:formatterPost,},
					{label: 'Entered By', name: 'adduser', width: 30, classes: 'wrap'},
					{label: 'Entered Date', name: 'adddate', width: 30, classes: 'wrap'},
					{label: 'Paymode', name: 'paymode', width: 30, classes: 'wrap'},
					{label: 'Cheq No', name: 'cheqno', width: 30, classes: 'wrap', formatter:formatterCheqnno, unformat:unformatterCheqnno},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname:'idno',
				sortorder:'desc',
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
/*
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
			}*/

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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
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
			addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	
		});
		