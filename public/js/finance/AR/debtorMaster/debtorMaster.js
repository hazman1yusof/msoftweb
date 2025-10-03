$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function (){
	$("body").show();
	//////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function ($form){
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};
	
	//////////////////////////////////////////////////////////////
	var mycurrency = new currencymode(['#creditlimit','#creditterm']);
	var fdl = new faster_detail_load();
	
	//////////////////object for dialog handler//////////////////
	var dialog_debtortype = new ordialog(
		'debtortype','debtor.debtortype','#debtortype',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'debtortycode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'actdebccode', name: 'actdebccode', hidden: true },
				{ label: 'actdebglacc', name: 'actdebglacc', hidden: true },
				{ label: 'depccode', name: 'depccode', hidden: true },
				{ label: 'depglacc', name: 'depglacc', hidden: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				var dataobj = selrowData('#'+dialog_debtortype.gridname);
				$('#actdebccode').val(dataobj['actdebccode']);
				$('#actdebglacc').val(dataobj['actdebglacc']);
				$('#depccode').val(dataobj['depccode']);
				$('#depglacc').val(dataobj['depglacc']);
				$('#name').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#name').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Financial Class",
			open: function (){
				dialog_debtortype.urlParam.filterCol= ['compcode','recstatus'],
				dialog_debtortype.urlParam.filterVal= ['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_debtortype.makedialog(true);
	
	var dialog_statecode = new ordialog(
		'statecode','hisdb.state','#statecode',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'statecode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Bill Type IP",
			open: function (){
				dialog_statecode.urlParam.filterCol= ['compcode','recstatus'],
				dialog_statecode.urlParam.filterVal= ['session.compcode','ACTIVE']
			},
			close: function(){
				$('#teloffice').focus();
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_statecode.makedialog(true);
	
	var dialog_billtype = new ordialog(
		'billtype','hisdb.billtymst','#billtype',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				$('#billtypeop').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#billtypeop').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Bill Type IP",
			open: function (){
				dialog_billtype.urlParam.filterCol= ['compcode','recstatus'],
				dialog_billtype.urlParam.filterVal= ['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_billtype.makedialog(true);
	
	var dialog_billtypeop = new ordialog(
		'billtypeop','hisdb.billtymst','#billtypeop',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				$('#coverageip').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#coverageip').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Bill Type OP",
			open: function (){
				dialog_billtypeop.urlParam.filterCol= ['compcode','recstatus'],
				dialog_billtypeop.urlParam.filterVal= ['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_billtypeop.makedialog(true);
	
	////////////////////////////////////start dialog////////////////////////////////////
	var butt1=[{
		text: "Save", click: function (){
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ){
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},{
		text: "Cancel", click: function (){
			$(this).dialog('close');
		}
	}];
	
	var butt2=[{
		text: "Close", click: function (){
			$(this).dialog('close');
		}
	}];
	
	var oper = 'add';
	$("#dialogForm")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				parent_close_disabled(true);
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				// toggleFormData('#jqGrid','#formdata',oper);
				switch(oper) {
					case state = 'add':
						dialog_debtortype.on();
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
					dialog_billtype.on();
					dialog_billtypeop.on();
					dialog_statecode.on();
				}
				if(oper!='add'){
					dialog_debtortype.check(errorField);
					dialog_billtype.check(errorField);
					dialog_billtypeop.check(errorField);
					dialog_statecode.check(errorField);
				}
			},
			close: function (event, ui){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				$('.my-alert').detach();
				dialog_debtortype.off();
				dialog_billtype.off();
				dialog_billtypeop.off();
				dialog_statecode.off();
				if(oper=='view'){
					$(this).dialog("option", "buttons",butt1);
				}
			},
			buttons: butt1,
		});
	/////////////////////////////////////end dialog/////////////////////////////////////
	
	//////////////////////////////parameter for jqgrid url//////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.debtormast',
		table_id: 'debtorcode',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
		sort_idno: true,
	}
	
	//////////////////////////////parameter for saving url//////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: 'debtorMaster/form',
		field: '',
		oper: oper,
		table_name: 'debtor.debtormast',
		table_id: 'debtorcode',
		saveip: 'true',
		checkduplicate: 'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 90, hidden: true },
			{ label: 'Code', name: 'debtorcode', width: 50, canSearch: true },
			{ label: 'Name', name: 'name', width: 150, classes: 'wrap', checked: true, canSearch: true },
			{ label: 'Financial <br> Class', name: 'debtortype', editable: true, classes: 'wrap', width: 50, formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Address', name: 'address1', hidden: true },
			{ label: 'Address 2', name: 'address2', hidden: true },
			{ label: 'Address 3', name: 'address3', hidden: true },
			{ label: 'Address 4', name: 'address4', hidden: true },
			{ label: 'PostCode', name: 'postcode', hidden: true },
			{ label: 'State Code', name: 'statecode', hidden: true },
			{ label: 'Country', name: 'countrycode', hidden: true },
			{ label: 'Contact', name: 'contact', hidden: true },
			{ label: 'Position', name: 'position', hidden: true },
			{ label: 'Tel.Office', name: 'teloffice', hidden: true },
			{ label: 'Fax', name: 'fax', hidden: true },
			{ label: 'Email', name: 'email', hidden: true },
			{ label: 'Bill Type IP', name: 'billtype', hidden: true },
			{ label: 'Bill Type OP', name: 'billtypeop', hidden: true },
			{ label: 'Outamt', name: 'outamt', hidden: true },
			{ label: 'Deposit <br> Amount', name: 'depamt', width: 50, align: 'right' },
			{ label: 'Credit Limit', name: 'creditlimit', hidden: true },
			{ label: 'Actual <br> Cost Center', name: 'actdebccode', width: 60, formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Actual <br> GL Acct', name: 'actdebglacc', width: 90, formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Deposit <br> Cost Center', name: 'depccode', width: 90, formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Deposit <br> GL Acct', name: 'depglacc', width: 90, formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Otherccode', name: 'otherccode', hidden: true },
			{ label: 'Otheracct', name: 'otheracct', hidden: true },
			{ label: 'Debtor Group', name: 'debtorgroup', hidden: true },
			{ label: 'Credit Control Group', name: 'crgroup', hidden: true },
			{ label: 'Bank Acc. No', name: 'accno', hidden: true },
			{ label: 'Othertel', name: 'othertel', hidden: true },
			{ label: 'Request GL', name: 'requestgl', hidden: true },
			{ label: 'Credit Term', name: 'creditterm', hidden: true },
			{ label: 'Coverage IP', name: 'coverageip', hidden: true },
			{ label: 'Coverage OP', name: 'coverageop', hidden: true },
			{ label: 'newic', name: 'newic', hidden: true },
			{ label: 'tinid', name: 'tinid', hidden: true },
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			// { label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'payto', name: 'payto', width: 90, hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true },
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true },
			{ label: 'Status', name: 'recstatus', width: 50, classes: 'wrap',
				cellattr: function (rowid, cellvalue){
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				},
			},
			{ label: 'Date Created', name: 'adddate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		sortname: 'idno',
		sortorder: 'desc',
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function (){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			
			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'debtortype':field=['debtortycode','description'];table="debtor.debtortype";case_='suppcode';break;
			case 'actdebccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'actdebglacc':field=['glaccno','description'];table="finance.glmasref";break;
			case 'depccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'depglacc':field=['glaccno','description'];table="finance.glmasref";case_='depglacc';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('debtorMaster',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
		
		return cellvalue;
	}
	
	function unformat_showdetail(cellvalue, options, rowObject){
		return $(rowObject).attr('title');
	}
	
	////////////////////////////////////////start grid pager////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function (){
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function (){
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
			// recstatusDisable();
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function (){
			oper = 'add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});
	////////////////////////////////////////////end grid////////////////////////////////////////////
	
	/////////////////////////handle searching, its radio button and toggle/////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);
	
	//////////////////////////add field into param, refresh grid if needed//////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['depamt','idno','compcode','adduser','adddate','upduser','upddate','computerid','ipaddress']);
	
	// $('#excelgen1').click(function (){
	// 	window.location='./debtorMaster/showExcel?compcode='+selrowData('#jqGrid').compcode;
	// });
	
	// $('#pdfgen1').click(function (){
	// 	window.open('./debtorMaster/showpdf?compcode='+selrowData('#jqGrid').compcode, '_blank');
	// });
});
		