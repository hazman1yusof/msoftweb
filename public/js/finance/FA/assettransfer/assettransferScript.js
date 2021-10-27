$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
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

	/////////////////////////////START NEW DIALOG///////////////////////////////////////////
	var dialog_deptcode= new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
			    {label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#loccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode'],
				dialog_deptcode.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog();

	var dialog_loccode= new ordialog(
		'loccode','sysdb.location','#loccode',errorField,
		{	colModel:[
				{label:'Location Code',name:'loccode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			sortname:'idno',
			sortorder:'desc',
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Location",
			open: function(){
				dialog_loccode.urlParam.filterCol=['compcode'];
				dialog_loccode.urlParam.filterVal=['session.compcode'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_loccode.makedialog();
	/////////////////Object for Dialog Handler////////////////////////////////////////////////////

		// //department
		// 	dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
		// //location
		// 	dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
		
	////////////////////////////////////start dialog///////////////////////////////////////
		
		var butt1=[{
			text: "Save",click: function() {
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,{idno:selrowData('#jqGrid').idno});

					saveOnly(saveParam2,
					{
						'deptcode' : $("#deptcode").val(),
						'olddeptcode' : $("#currdeptcode").val(),
						'trantype' : 'TRF',
						'trandate' : $("#trandate").val(),
						'curloccode' : $("#loccode").val(),
						'oldloccode' : $("#currloccode").val(),
						'assetno' : $("#assetno").val(),
						'assetcode' : $("#assetcode").val(),
						'assettype' : $("#assettype").val()

					});
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
						rdonly("#dialogForm");
						hideOne('#formdata');
						break;
					case state = 'edit':
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						$("#loccode").val('');
						$("#deptcode").val('');
						break;
					case state = 'view':
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata');
						$(this).dialog("option", "buttons",butt2);
						break;
				}
				if(oper!='view'){
					dialog_deptcode.on();
					dialog_loccode.on();
				}
				if(oper!='add'){
					//dialog_deptcode.check(errorField);
					//dialog_loccode.check(errorField);
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				//$('.alert').detach();
				$('.my-alert').detach();
				$("#formdata a").off();
				if(oper=='view'){
					$(this).dialog("option", "buttons",butt1);
				}
			},
			buttons :butt1,
			});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url: '/util/get_table_default',
		field:'',
		table_name:'finance.faregister',
		table_id:'idno',
		sort_idno:true,
		filterCol:['recstatus'],
		filterVal:['ACTIVE'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		field:['deptcode','loccode','trandate'],
		url:'assettransfer/form',
		oper:oper,
		table_name:'finance.faregister',
		table_id:'idno'
	};

	var saveParam2={
		action:'save_table_default',
		field:['auditno','deptcode','olddeptcode','trantype','trandate','curloccode','oldloccode','assetno','assetcode','assettype'],
		oper:'add',
		table_name:'finance.fatran',
		table_id:'auditno',
		sysparam: {source: 'FA', trantype: 'TRF', useOn: 'auditno'},
	};

	$("#jqGrid").jqGrid({
		datatype: "local",	
			colModel: [
			{label: 'Tagging No', name: 'assetno', width: 10, canSearch: true, checked: true},
			{label: 'Item Code', name:'itemcode', width: 20, classes: 'wrap', formatter: showdetail,unformat:un_showdetail },
			{label: 'Category', name: 'assetcode', width: 20, classes: 'wrap', canSearch: true,checked:true, formatter: showdetail,unformat:un_showdetail},
			{label: 'Type', name:'assettype', width: 20, classes: 'wrap', canSearch: true, checked:true, formatter: showdetail,unformat:un_showdetail},
			{label: 'Department', name:'deptcode', width: 20, classes: 'wrap', formatter: showdetail,unformat:un_showdetail },
			{label: 'Location', name:'loccode', width: 20, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Description', name:'description', width: 40, classes: 'wrap'},
			{label: 'idno', name: 'idno', hidden: true},
			{label: 'Transfer Date', name:'trandate', formatter:dateFormatter, hidden:true},
			{label: 'Add User', name:'adduser', width:20, classes:'wrap',  hidden:true},
			{label: 'Add Date', name:'adddate', width:20, classes:'wrap',  hidden:true},
			],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 350,
		rowNum: 30,
		multiselect:false,
		pager: "#jqGridPager",
		onSelectRow: function(){
			$('#currdeptcode').val(selrowData('#jqGrid').deptcode);
			$('#currloccode').val(selrowData('#jqGrid').loccode);
			$('#assetno').val(selrowData('#jqGrid').assetno);
			$('#description').val(selrowData('#jqGrid').description);
			$('#assetcode').val(selrowData('#jqGrid').assetcode);
			$('#assettype').val(selrowData('#jqGrid').assettype);
		},
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

	////////////////////////////////////////////////////////////////////////////////////////
	
	////////////////////////////// DATE FORMATTER ////////////////////////////////////////

	function dateFormatter(cellvalue, options, rowObject){
		return moment(cellvalue).format("DD-MM-YYYY");
	}

	
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'itemcode':field=['itemcode','description'];table="material.productmaster";case_='itemcode';break;
			case 'assetcode':field=['assetcode','description'];table="finance.facode";case_='assetcode';break;
			case 'assettype':field=['assettype','description'];table="finance.fatype";case_='assettype';break;
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'loccode':field=['loccode','description'];table="sysdb.location";case_='loccode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('assettransferScript',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////
	$("#msgBox").dialog({
		autoOpen : false, 
		modal : true,
		width: 3/10 * $(window).width(),
		buttons: [{
			text: "OK",click: function() {
				$(this).dialog('close');
				oper='edit';
				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				$('#trandateNew').attr('max', moment().format('D-M-YYYY'));
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	$("#transferButn").click(function(){
		var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		if(!selRowId){
			alert('Please select row');
		}else{
			$("span[name='itemcode']").text(selrowData('#jqGrid').itemcode);
			$("span[name='description']").text(selrowData('#jqGrid').description);
			
			$("#msgBox").dialog("open");
		}
	});
	/////////////////////////////////////////////////////////////////////////////////////////////////
      
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
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
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	function saveOnly(saveParam,obj){
		$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $.param(obj));
	}
});

