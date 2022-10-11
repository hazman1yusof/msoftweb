
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
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

			var fdl = new faster_detail_load();

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						if($("form#formdata [name='appointment'][value='1']").is(":checked")){
							if($('#intervaltime').val().trim() == ''){
								alert('interval time required if appointment is true');
							}else{
								saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
							}
						}else{
							saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
					toggleFormData('#jqGrid','#formdata');
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							$('#intervaltime').val(60);
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
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
						dialog_doctype.on();
						dialog_department.on();
						dialog_speciality.on();
						dialog_discipline.on();
						dialog_creditor.on();
					}
					if(oper!='add'){
						dialog_doctype.check(errorField);
						dialog_department.check(errorField);
						dialog_speciality.check(errorField);
						dialog_discipline.check(errorField);
						dialog_creditor.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('#formdata .alert').detach();
					dialog_doctype.off();
					dialog_department.off();
					dialog_speciality.off();
					dialog_discipline.off();
					dialog_creditor.off();
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
				field: '',
				table_name:'hisdb.doctor',
				table_id:'doctorcode',
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'doctor_save',
				url:'./doctor/form',
				field:'',
				oper:oper,
				table_name:'hisdb.doctor',
				table_id:'doctorcode',
				saveip:'true',
				checkduplicate:'true'
				//sysparam: {source: 'doc'} 
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'compcode', name: 'compcode', width: 90 , hidden: true, classes: 'wrap'},
					{label: 'Doctor Code', name: 'doctorcode', width: 40, canSearch:true, classes: 'wrap'},
					{label: 'Doctor Name', name: 'doctorname', width: 90, canSearch:true , classes: 'wrap', checked:true},
					{label: 'Login ID', name: 'loginid', width: 30, canSearch:true, classes: 'wrap'},
					{label: 'Costcenter', name: 'department', width: 90 , hidden: true, classes: 'wrap'},
					{label: 'Discipline Code', name: 'disciplinecode', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
					{label: 'Speciality Code', name: 'specialitycode', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
					{label: 'Doctor Type', name: 'doctype', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
					{label: 'Creditor', name: 'creditorcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Resign Date', name: 'resigndate', width: 90 , classes: 'wrap',hidden: true},
					{label: 'idno', name: 'idno', width: 90, classes: 'wrap',hidden: true},
					{label: 'Class', name: 'classcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Admission Right', name: 'admright', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Appointment', name: 'appointment', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Company', name: 'company', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Address', name: 'address1', width: 90 , classes: 'wrap',hidden: true},
					{label: 'address2', name: 'address2', width: 90 , classes: 'wrap',hidden: true},
					{label: 'address3', name: 'address3', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Postcode', name: 'postcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'State', name: 'statecode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Country', name: 'countrycode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'GST No', name: 'gstno', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Home', name: 'res_tel', width: 90 , classes: 'wrap',hidden: true},
					{label: 'H/Phone', name: 'tel_hp', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Office', name: 'off_tel', width: 90 , classes: 'wrap',hidden: true},	
					{label: 'Operation Theatre (OT)', name: 'operationtheatre', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', formatterstatus:formatterstatus, unformatstatus:unformatstatus, 
							cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
					},
					{label: 'Interval Time', name: 'intervaltime', width: 90 , classes: 'wrap',hidden: true},
					{label: 'mmcid', name: 'mmcid', width: 90 , classes: 'wrap',hidden: true},
					{label: 'apcid', name: 'apcid', width: 90 , classes: 'wrap',hidden: true},
					{label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
				

				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				sortname:'idno',
				sortorder:'desc',
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
					fdl.set_array().reset();
				},
				
				
			});

			//////////////////////////// STATUS FORMATTER /////////////////////////////////////////////////
			
			function formatterstatus(cellvalue, options, rowObject) {
				if (cellvalue == 'ACTIVE') {
					return "ACTIVE";
				}
				if (cellvalue == 'DEACTIVE') {
					return "DEACTIVE";
				}
			}
	
			function unformatstatus(cellvalue, options) {
				if (cellvalue == 'ACTIVE') {
					return "ACTIVE";
				}
				if (cellvalue == 'DEACTIVE') {
					return "DEACTIVE";
				}
			}

			function showdetail(cellvalue, options, rowObject){
				var field,table, case_;
				switch(options.colModel.name){
					case 'disciplinecode':field=['code','description'];table="hisdb.discipline";case_='disciplinecode';break;
					case 'specialitycode':field=['specialitycode','description'];table="hisdb.speciality";case_='specialitycode';break;
					case 'doctype':field=['statuscode','description'];table="hisdb.docstatus";case_='doctype';break;
				}
				var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
			
				fdl.get_array('doctorScript',options,param,case_,cellvalue);
				
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
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{ 'idno': selrowData('#jqGrid').idno,'doctorcode': selrowData('#jqGrid').doctorcode });
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
			populateSelect2('#jqGrid','#searchForm');
			searchClick2('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);


			////////////////////object for dialog handler//////////////////
		
			var dialog_doctype = new ordialog(
				'docstatus','hisdb.docstatus','#doctype',errorField,
				{	colModel:[
						{label:'Status Code',name:'statuscode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#doctorname').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#doctorname').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Doctor Type",
					open: function(){
						dialog_doctype.urlParam.filterCol=['recstatus'],
						dialog_doctype.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_doctype.makedialog();

			var dialog_department = new ordialog(
				'costcenter','finance.costcenter','#department',errorField,
				{	colModel:[
						{label:'Cost Code',name:'costcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#specialitycode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#specialitycode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Department",
					open: function(){
						dialog_department.urlParam.filterCol=['recstatus'],
						dialog_department.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_department.makedialog();


			var dialog_speciality = new ordialog(
				'speciality','hisdb.speciality','#specialitycode',errorField,
				{	colModel:[
						{label:'Speciality Code',name:'specialitycode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#disciplinecode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#disciplinecode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Speciality",
					open: function(){
						dialog_speciality.urlParam.filterCol=['recstatus'],
						dialog_speciality.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_speciality.makedialog();
			
			var dialog_discipline = new ordialog(
				'discipline','hisdb.discipline','#disciplinecode',errorField,
				{	colModel:[
						{label:'Discipline Code',name:'code',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#creditorcode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#creditorcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Discipline",
					open: function(){
						dialog_discipline.urlParam.filterCol=['recstatus'],
						dialog_discipline.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_discipline.makedialog();
			
			var dialog_creditor = new ordialog(
				'supplier','material.supplier','#creditorcode',errorField,
				{	colModel:[
						{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Supplier Name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#classcode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#classcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Transaction Department",
					open: function(){
						dialog_creditor.urlParam.filterCol=['recstatus'],
						dialog_creditor.urlParam.filterVal=['ACTIVE']
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_creditor.makedialog();

		});
		