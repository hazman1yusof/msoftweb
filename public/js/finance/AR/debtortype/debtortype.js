
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});

	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

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
					$('#formdata :input[hideOne]').show();
					break;
			}
			if(oper!='view'){
				dialog_costcode.on();
				dialog_glaccount.on();
				dialog_depccode.on();
				dialog_depglacc.on();
			}
			if(oper!='add'){
				//FormData('#jqGrid','#formdata');
				dialog_costcode.check(errorField);
				dialog_glaccount.check(errorField);
				dialog_depccode.check(errorField);
				dialog_depglacc.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			dialog_costcode.off();
			dialog_glaccount.off();
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
		field: '',
		table_name:'debtor.debtortype',
		table_id:'debtortycode',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./debtortype/form',
		field:'',
		oper:oper,
		table_name:'debtor.debtortype',
		table_id:'debtortycode',
		saveip:'true',
		checkduplicate:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
		 	{label: 'idno', name: 'idno', width: 90 , hidden: true},
		 	{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
			{label: 'Financial Class', name: 'debtortycode', width: 25, canSearch:true},
			{label: 'Description', name: 'description', width: 90, canSearch:true, checked: true},
			{label: 'Actual Cost', name: 'actdebccode', width: 40, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Actual Account', name: 'actdebglacc', width: 60, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Deposit Cost', name: 'depccode', width: 40, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Deposit Account', name: 'depglacc', width: 60, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'adduser', name: 'adduser', width: 20, hidden:true},
			{ label: 'adddate', name: 'adddate', width: 20, hidden:true},
			{ label: 'upduser', name: 'upduser', width: 20, hidden:true},
			{ label: 'upddate', name: 'upddate', width: 20, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', hidden:true},
			{ label: 'computerid', name: 'computerid', hidden:true},
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
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
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
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
	
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'actdebccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'actdebglacc':field=['glaccno','description'];table="finance.glmasref";break;
			case 'depccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'depglacc':field=['glaccno','name'];table="finance.glmasref";break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('debtortype',options,param,case_,cellvalue);
		return cellvalue;
	}

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);


	////////////////////////////////////ordialog/////////////////////////////////////////////////////////
	var dialog_costcode = new ordialog(
		'actdebccode','finance.costcenter','#actdebccode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
		},
		ondblClickRow: function () {
			$('#actdebglacc').focus();
		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#actdebglacc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}

		},{
			title:"Select Actual Cost",
			open: function(){
				dialog_costcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_costcode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_costcode.makedialog(true);

	var dialog_glaccount = new ordialog(
		'actdebglacc','finance.glmasref','#actdebglacc',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
		},
		ondblClickRow: function () {
			$('#depccode').focus();
		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#depccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}

		},{
			title:"Select Actual Account",
			open: function(){
				dialog_glaccount.urlParam.filterCol=['compcode','recstatus'],
				dialog_glaccount.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_glaccount.makedialog(true);

	var dialog_depccode = new ordialog(
		'depccode','finance.costcenter','#depccode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
		},
		ondblClickRow: function () {
			$('#depglacc').focus();
		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#depglacc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Deposit Cost",
			open: function(){
				dialog_depccode.urlParam.filterCol=['compcode','recstatus'],
				dialog_depccode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_depccode.makedialog(true);

	var dialog_depglacc = new ordialog(
		'depglacc','finance.glmasref','#depglacc',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
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
			title:"Select Deposit Account",
			open: function(){
				dialog_depglacc.urlParam.filterCol=['compcode','recstatus'],
				dialog_depglacc.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_depglacc.makedialog(true);

});
		