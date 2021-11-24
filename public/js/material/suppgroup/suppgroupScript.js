
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
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////


			////////////////////object for dialog handler//////////////////
			var dialog_costcode = new ordialog(
				'costcode','finance.costcenter','#costcode',errorField,
				{	colModel:[
						{label:'Cost Code',name:'costcode',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
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
					title:"Select Cost Code",
					open: function(){
						dialog_costcode.urlParam.filterCol=['recstatus'],
						dialog_costcode.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam','radio', 'tab'
			);
			dialog_costcode.makedialog();

			var dialog_glaccno = new ordialog(
				'glaccno','finance.glmasref','#glaccno',errorField,
				{	colModel:[
						{label:'Account No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#advccode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#advccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select GL Account No",
					open: function(){
						dialog_glaccno.urlParam.filterCol=['recstatus'],
						dialog_glaccno.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_glaccno.makedialog();

			var dialog_advccode = new ordialog(
				'advccode','finance.costcenter','#advccode',errorField,
				{	colModel:[
						{label:'Cost Code',name:'costcode',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#advglaccno').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#advglaccno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
				},{
					title:"Select Advance Cost Code",
					open: function(){
						dialog_advccode.urlParam.filterCol=['recstatus'],
						dialog_advccode.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_advccode.makedialog();

			var dialog_advglaccno = new ordialog(
				'advglaccno','finance.glmasref','#advglaccno',errorField,
				{	colModel:[
						{label:'Account No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#recstatus').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
				},{
					title:"Select Advance Account No",
					open: function(){
						dialog_advglaccno.urlParam.filterCol=['recstatus'],
						dialog_advglaccno.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_advglaccno.makedialog();

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
							break;
					}

					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
						dialog_costcode.on();
						dialog_glaccno.on();
						dialog_advccode.on();
						dialog_advglaccno.on();
						
					}
					if(oper!='add'){
						dialog_costcode.check(errorField);
						dialog_glaccno.check(errorField);
						dialog_advccode.check(errorField);
						dialog_advglaccno.check(errorField);
					}
					
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('.my-alert').detach();
					dialog_costcode.off();
					dialog_glaccno.off();
					dialog_advccode.off();
					dialog_advglaccno.off();
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
				table_name:'material.suppgroup',
				table_id:'suppgroup',
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'suppgroup/form',
				field:'',
				oper:oper,
				table_name:'material.suppgroup',
				table_id:'suppgroup',
				saveip:'true',
				checkduplicate:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{ label: 'idno', name: 'idno', width: 30,hidden:true },
					{ label: 'Comp Code', name: 'compcode', hidden:true},
					{ label: 'Supplier Group', name: 'suppgroup', width: 50, sorttype: 'text', classes: 'wrap', canSearch: true},
					{ label: 'Description', name: 'description', width: 100, sorttype: 'text', classes: 'wrap', checked:true,canSearch: true},
					{ label: 'Cost Code', name: 'costcode', width: 60, sorttype: 'number', classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
					{ label: 'Gl Account No', name: 'glaccno', width: 60, sorttype: 'number', classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
					{ label: 'Del User', name: 'deluser', width: 80,hidden:true},
					{ label: 'Del Date', name: 'deldate', width: 90,hidden:true},
					{ label: 'Advance Cost Code', name: 'advccode', width: 80, sorttype: 'number', editable: true, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
					{ label: 'Advance Account No', name: 'advglaccno', width: 80, sorttype: 'number', classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
					{ label: 'Status', name: 'recstatus', width: 80, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
					},
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
					$("#searchForm input[name=Stext]").focus();
					fdl.set_array().reset();
				},
				
			});

			function formatter(cellvalue, options, rowObject){
				return parseInt(cellvalue) ? "Yes" : "No";
			}

			function unformat(cellvalue, options){
				//return parseInt(cellvalue) ? "Yes" : "No";

				if ((cellvalue) == 'Yes') {
					return "1";
				}
				else {
					return "0";
				}
			}

			function showdetail(cellvalue, options, rowObject){
				var field,table, case_;
				switch(options.colModel.name){
					case 'costcode':field=['costcode','description'];table="finance.costcenter";case_='costcode';break;
					case 'glaccno':field=['glaccno','description'];table="finance.glmasref";case_='glaccno';break;
					case 'advccode':field=['costcode','description'];table="finance.costcenter";case_='advccode';break;
					case 'advglaccno':field=['glaccno','description'];table="finance.glmasref";case_='advglaccno';break;
				}
				var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
			
				fdl.get_array('suppgroupScript',options,param,case_,cellvalue);
				
				if(cellvalue == null)cellvalue = " ";
				return cellvalue;
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
						emptyFormdata(errorField,'#formdata');
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
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','computerid', 'ipaddress', 'adduser', 'adddate', 'upddate', 'upduser', 'recstatus']);
		});
		