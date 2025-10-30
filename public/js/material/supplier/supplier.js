
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

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
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	//////////////////////////////////////////////////////////////

	var fdl = new faster_detail_load();
	var myattachment = new attachment_page("supplierAP","#jqGrid","idno");

	////////////////////object for dialog handler//////////////////
	var dialog_SuppGroup = new ordialog(
		'SuppGroup','material.suppgroup','#SuppGroup',errorField,
		{	colModel:[
				{label:'Code',name:'suppgroup',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'costcode',name:'costcode',width:100,classes:'pointer',hidden:true},
				{label:'glaccno',name:'glaccno',width:100,classes:'pointer',hidden:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					let data=selrowData('#'+dialog_SuppGroup.gridname);
					$('#CostCode').focus();
					$('#CostCode').val(data['costcode']);
					$('#GlAccNo').val(data['glaccno']);
					dialog_CostCode.check(errorField,'CostCode');
					dialog_GlAccNo.check(errorField,'GlAccNo');
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#CostCode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Supplier Group",
			open: function(){
				dialog_SuppGroup.urlParam.filterCol=['compcode','recstatus'],
				dialog_SuppGroup.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_SuppGroup.makedialog(true);

	var dialog_CostCode = new ordialog(
		'CostCode','finance.costcenter','#CostCode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					$('#GlAccNo').focus();
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
			title:"Select Cost Code",
			open: function(){
				dialog_CostCode.urlParam.filterCol=['compcode','recstatus'],
				dialog_CostCode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio', 'tab'
	);

	dialog_CostCode.makedialog(true);

	var dialog_advCostCode = new ordialog(
		'Advccode','finance.costcenter','#Advccode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					$('#AdvGlaccno').focus();
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
			title:"Select Cost Code",
			open: function(){
				dialog_advCostCode.urlParam.filterCol=['compcode','recstatus'],
				dialog_advCostCode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio', 'tab'
	);
	dialog_advCostCode.makedialog(true);
	
	var dialog_GlAccNo = new ordialog(
		'GlAccNo','finance.glmasref','#GlAccNo',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
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
			title:"Select Gl Account No",
			open: function(){
				dialog_GlAccNo.urlParam.filterCol=['compcode','recstatus'],
				dialog_GlAccNo.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio','tab'
	);
	dialog_GlAccNo.makedialog(true);

	var dialog_advGlAccNo = new ordialog(
		'AdvGlaccno','finance.glmasref','#AdvGlaccno',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
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
			title:"Select Gl Account No",
			open: function(){
				dialog_advGlAccNo.urlParam.filterCol=['compcode','recstatus'],
				dialog_advGlAccNo.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio','tab'
	);
	dialog_advGlAccNo.makedialog(true);

	var mycurrency =new currencymode(['#TermDisp', '#TermNonDisp', '#TermOthers', '#si_purqty', '#si_unitprice', '#si_perdiscount', '#si_amtdisc', '#si_perslstax', '#si_amtslstax', '#sb_bonqty']);

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}else{
				mycurrency.formatOn();
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
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					hideOne('#formdata');
					$('#CostCode, #GlAccNo').prop("readonly",true);
					rdonly('#formdata');
					break;
				case state = 'edit':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					$('#formdata :input[hideOne]').show();
					rdonly('#formdata');
					break;
				case state = 'view':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper!='view'){
				dialog_SuppGroup.on();
				dialog_CostCode.on();
				dialog_advCostCode.on();
				dialog_GlAccNo.on();
				dialog_advGlAccNo.on();

			}
			if(oper!='add'){
				dialog_SuppGroup.check(errorField);
				dialog_CostCode.check(errorField);
				dialog_advCostCode.check(errorField);
				dialog_GlAccNo.check(errorField);
				dialog_advGlAccNo.check(errorField);
			}
		},
		close: function( event, ui ) {
			emptyFormdata(errorField,'#formdata');
			parent_close_disabled(false);
			$('.my-alert').detach();
			dialog_SuppGroup.off();
			dialog_CostCode.off();
			dialog_advCostCode.off();
			dialog_GlAccNo.off();
			dialog_advGlAccNo.off();
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
		filterCol:['compcode'],
		filterVal:['session.compcode'],
		table_name:'material.supplier',
		table_id:'SuppCode',
		sort_idno:true,
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'supplier/form',
		field:'',
		oper:oper,
		table_name:'material.supplier',
		table_id:'SuppCode',
		saveip:'true',
		checkduplicate:'true'
	};
	
	//////////////////////////start grid/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'compcode', name: 'compcode', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Supplier Code', name: 'SuppCode', width: 35 , sorttype: 'text', classes: 'wrap', canSearch: true},
			{ label: 'Supplier Name', name: 'Name', width: 100, editable: true, classes: 'wrap',checked:true, canSearch: true },
			{ label: 'Supplier Group', name: 'SuppGroup', width: 35, editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail,canSearch: true},
			{ label: 'Cont Pers', name: 'ContPers', width: 90, hidden: true},
			{ label: 'Addr1', name: 'Addr1', width: 30, hidden: true}, 
			{ label: 'Addr2', name: 'Addr2', width: 90, hidden:true},
			{ label: 'Addr3', name: 'Addr3', width: 80,hidden:true},
			{ label: 'Addr4', name: 'Addr4', width: 90,hidden:true},
			{ label: 'Tel No', name: 'TelNo', width: 80,hidden:true},
			{ label: 'Fax No', name: 'Faxno', width: 90,hidden:true},
			{ label: 'Payment Terms ', name: 'TermDays', width: 30, align: 'right', hidden:false, classes: 'wrap'},
			{ label: 'Term Others', name: 'TermOthers', width: 30, align: 'right',editable: true, classes: 'wrap', hidden:true }, 
			{ label: 'TermNonDisp', name: 'TermNonDisp', width: 35, align: 'right',editable: true, classes: 'wrap', hidden:true},
			{ label: 'Term Disp', name: 'TermDisp', width: 30, align: 'right',editable: true, classes: 'wrap', hidden:true},
			{ label: 'Cost Code', name: 'CostCode', width: 30, editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Gl Account No', name: 'GlAccNo', width: 35, editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Advance Cost Code', name: 'Advccode', width: 30, editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Advance GL Account No', name: 'AdvGlaccno', width: 35, editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{ label: 'AccNo', name: 'AccNo', width: 80, hidden: true, editable: true},
			{ label: 'DepAmt', name: 'DepAmt', width: 80, hidden: true},
			{ label: 'MiscAmt', name: 'MiscAmt', width: 80, hidden: true},
			{ label: 'Supply Goods', name: 'SuppFlg', width: 80, editable: true, classes: 'wrap', hidden: true},
			{ label: 'Advccode', name: 'Advccode', width: 80, hidden: true, editable: true},
			{ label: 'AdvGlaccno', name: 'AdvGlaccno', width: 80, hidden: true, editable: true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'GSTID', name: 'GSTID', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'TINNo', name: 'TINNo', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'CompRegNo', name: 'CompRegNo', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'indvNewic', name: 'indvNewic', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'indvOtherno', name: 'indvOtherno', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'idno', name: 'idno', hidden: true},
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
		],
		autowidth:true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
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
			populate_form(selrowData("#jqGrid"));

		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_suppitems.filterVal[0]=selrowData("#jqGrid").SuppCode; 
				// saveParam_suppitems.filterVal[0]=selrowData("#jqGrid").SuppCode; 
				urlParam_suppbonus.filterVal[1]=selrowData("#jqGrid").SuppCode;
				// saveParam_suppbonus.filterVal[0]=selrowData("#jqGrid").SuppCode;
				//$("#Fsuppitems :input[name='billtype']").val(selrowData("#jqGrid").billtype);
				$("#Fsuppitems :input[name='si_suppcode']").val(selrowData("#jqGrid").SuppCode);
				refreshGrid('#gridSuppitems',urlParam_suppitems);
				populate_form(selrowData("#jqGrid"));
				$('#gridSuppBonus').jqGrid('clearGridData');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}
			
			$("#pdfgen1").attr('href','./supplier/showpdf?');

			$("#pdfgen_excel").attr('href','./supplier/showExcel?');
		},
		
	});

	//////////////////////////////////////xxformatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'SuppGroup':field=['SuppGroup','description'];table="material.SuppGroup";case_='SuppGroup';break;
			case 'CostCode':field=['costcode','description'];table="finance.costcenter";case_='CostCode';break;
			case 'GlAccNo':field=['glaccno','description'];table="finance.glmasref";case_='GlAccNo';break;
			case 'Advccode':field=['costcode','description'];table="finance.costcenter";case_='Advccode';break;
			case 'AdvGlaccno':field=['glaccno','description'];table="finance.glmasref";case_='AdvGlaccno';break;

			//suppitem//
			case 'si_pricecode':field=['pricecode','description'];table="material.pricesource";case_='si_pricecode';break;			
			case 'si_uomcode':field=['uomcode','description'];table="material.uom";case_='si_uomcode';break;

			//suppbonus//
			case 'sb_bonuomcode':field=['uomcode','description'];table="material.uom";case_='sb_bonuomcode';break;
			case 'sb_bonsitemcode':field=['itemcode','description'];table="material.product";case_='sb_bonsitemcode';break;

		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('supplier',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
		
		if(cellvalue==null)return "";
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
		id: "jqGridPagerglyphicon-trash",
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

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	//toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid']);
	console.log(saveParam);


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// suppitems //////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	var dialog_pricecode = new ordialog(
		'si_pricecode','material.pricesource',"#Fsuppitems :input[name='si_pricecode']",errorField,
		{	colModel:[
				{label:'Code',name:'pricecode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
				urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					$('#si_itemcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#si_itemcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Price Code",
			open: function(){
				dialog_pricecode.urlParam.filterCol=['compcode','recstatus'],
				dialog_pricecode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_pricecode.makedialog(true);

	var dialog_itemcode = new ordialog(
		'si_itemcode','material.product',"#Fsuppitems :input[name='si_itemcode']",errorField,
		{	colModel:[
				{label:'Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					$('#si_uomcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#si_uomcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Supplier Group",
			open: function(){
				dialog_itemcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_itemcode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_itemcode.makedialog(true);

	var dialog_uomcode = new ordialog(
		'si_uomcode','material.uom',"#Fsuppitems :input[name='si_uomcode']",errorField,
		{	colModel:[
				{label:'Code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
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
			title:"Select UOM Code",
			open: function(){
				dialog_uomcode.urlParam.filterCol=['recstatus'],
				dialog_uomcode.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_uomcode.makedialog(true);

	var buttItem1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#Fsuppitems').isValid({requiredFields: ''}, {}, true) ) {
				saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",oper_suppitems,saveParam_suppitems,urlParam_suppitems);//,'#searchForm2'
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper_suppitems;
	$("#Dsuppitems")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper_suppitems) {
				case state = 'add':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#Fsuppitems');
					hideOne('#Fsuppitems');
					rdonly('#Fsuppitems');
					$(this).dialog("option", "buttons",buttItem1);
					break;
				case state = 'edit':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#Fsuppitems');
					frozeOnEdit("#Dsuppitems");
					$('#Fsuppitems :input[hideOne]').show();
					rdonly('#Fsuppitems');
					$(this).dialog("option", "buttons",buttItem1);
					break;
				case state = 'view':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#Fsuppitems');
					$('#Fsuppitems :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper_suppitems=='add'){
				dialog_pricecode.on();
				dialog_itemcode.on();
				dialog_uomcode.on();
			}
			if(oper_suppitems == 'edit' && $('#gridSuppBonus').jqGrid('getGridParam', 'reccount') < 1){
				dialog_pricecode.on();
				dialog_itemcode.on();
				dialog_uomcode.on();
			}
			if(oper_suppitems == 'edit' && $('#gridSuppBonus').jqGrid('getGridParam', 'reccount') > 1){
				$("#Fsuppitems :input[name*='si_pricecode']").prop("readonly",true);
				$("#Fsuppitems :input[name*='si_itemcode']").prop("readonly",true);
				$("#Fsuppitems :input[name*='si_uomcode']").prop("readonly",true);
				$("#Fsuppitems :input[name*='si_purqty']").prop("readonly",true);
			}
			if(oper_suppitems!='add'){
				dialog_pricecode.check(errorField);
				dialog_itemcode.check(errorField);
				dialog_uomcode.check(errorField);
			}
			if (oper_suppitems != 'view') {
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#Fsuppitems');
			$('#Fsuppitems .alert').detach();
			dialog_pricecode.off();
			dialog_itemcode.off();
			dialog_uomcode.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",buttItem1);
			}
		},
		buttons :buttItem1,
	  });
	
	/////////////////////parameter for jqgrid url SVC/////////////////////////////////////////////////
	var urlParam_suppitems={
		action:'get_table_default',
		field:'',
		url:'util/get_table_default',
		fixPost:'true',//replace underscore with dot
		table_name:['material.suppitems as si','material.product as p'],
		table_id:'si_lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['si.itemcode'],
		join_onVal:['p.itemcode'],
		filterCol:['si.SuppCode','si.compcode','p.compcode'],
		filterVal:['','session.compcode','session.compcode'],//suppcode set when click supplier grid
		sort_idno:true,
	}

	var saveParam_suppitems={
		action:'save_table_default',
		url:'supplier/form',
		field:'',
		oper:oper_suppitems,
		table_name:'material.suppitems',//for save_table_default, use only 1 table
		fixPost:'true',//throw out dot in the field name
		idnoUse:'si_idno',
		table_id:'itemcode',
		// noduplicate:true,
		// filterCol:['suppcode'],
		// filterVal:[''],//suppcode set when click supplier grid
		lineno:{useOn:'lineno_'},
		saveip:'true'
	};

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$("#gridSuppitems").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'compcode', name: 'compcode', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Supplier Code', name: 'si_suppcode', width: 100, hidden: true},
		 	{ label: 'no', name: 'si_lineno_', width: 50, sorttype: 'number', hidden: true,}, // 
		 	{ label: 'Item Code', name: 'si_itemcode', width: 150, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true},
			{ label: 'Item Description', name: 'p_description', width: 400, sorttype: 'text', classes: 'wrap', checked:true,canSearch: true},
			{ label: 'Price Code', name: 'si_pricecode', width: 200, sorttype: 'text', editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Uom Code', name: 'si_uomcode', width: 100, sorttype: 'text', editable: true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Unit Price', name: 'si_unitprice', width: 200, align: 'right', sorttype: 'float', editable: true, classes: 'wrap',formatter:'currency'},
			{ label: 'Purchase Quantity', name: 'si_purqty', width: 200, align: 'right', sorttype: 'float', editable: true, classes: 'wrap',formatter:'currency'},
			{ label: 'Percentage of Discount', name: 'si_perdiscount', width: 100,  hidden: true},
			{ label: 'Amount Discount', name: 'si_amtdisc', width: 30,  hidden: true},
			{ label: 'Amount Sales Tax', name: 'si_amtslstax', width: 30,  hidden: true},
			{ label: 'Percentage of Sales Tax', name: 'si_perslstax', width: 30,  hidden: true},
			{ label: 'Expiry Date', name: 'si_expirydate', width: 30,  hidden: true},
			{ label: "Item Code at Supplier's Site", name: 'si_sitemcode', width: 30,  hidden: true},
			{ label: 'Status', name: 'si_recstatus', width: 200, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{label: 'No', name: 'si_idno', width: 50, hidden: true},
			{ label: 'adduser', name: 'si_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'si_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'si_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'si_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'computerid', name: 'si_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'si_lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
		],
		viewrecords: true,
		//shrinkToFit: true,
		autowidth:true,
        multiSort: true,
		loadonce:false,
		width: 900,
		height: 200,
		rowNum: 30,
		hidegrid: false,
		caption: caption('searchForm2','Items Supplied By the Supplier'),
		pager: "#jqGridPager2",
		onPaging: function(pgButton){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager2 td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#gridSuppitems").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#gridSuppitems").jqGrid ('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			
			if($("#gridSuppitems").getGridParam("reccount") >= 1){
				$("#jqGridPagerglyphicon-trash").hide();
			} 

			if($("#gridSuppitems").getGridParam("reccount") < 1){
				$("#jqGridPagerglyphicon-trash").show()
			}
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#gridSuppitems').jqGrid ('getRowData', rowid);
				//console.log(rowData.svc_billtype);
				urlParam_suppbonus.filterVal[0]=selrowData("#gridSuppitems").si_itemcode; 

				$("#Fsuppbonus :input[name*='sb_suppcode']").val(selrowData("#gridSuppitems").si_suppcode);
				$("#Fsuppbonus :input[name*='sb_pricecode']").val(selrowData("#gridSuppitems").si_pricecode);
				$("#Fsuppbonus :input[name*='sb_itemcode']").val(selrowData("#gridSuppitems").si_itemcode);
				$("#Fsuppbonus :input[name*='sb_uomcode']").val(selrowData("#gridSuppitems").si_uomcode);
				$("#Fsuppbonus :input[name*='sb_purqty']").val(selrowData("#gridSuppitems").si_purqty);
				refreshGrid('#gridSuppBonus',urlParam_suppbonus);
				$("#pg_jqGridPager3 table").show();
			}
		},
		
	});
	
	$("#gridSuppitems").jqGrid('navGrid','#jqGridPager2',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridSuppitems",urlParam_suppitems);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-trash", 
		id:"jqGridPager2glyphicon-trash",
		onClickButton: function(){
			oper_suppitems='del';
			var selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#Fsuppitems');
			}else{
				saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",'del',saveParam_suppitems,urlParam_suppitems,null,{'idno':selRowId});
			}
		}, 
		position: "first", 
		title:"Delete Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-info-sign", 
		onClickButton: function(){
			oper_suppitems='view';
			selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",selRowId,'view', '');
		}, 
		position: "first", 
		title:"View Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-edit", 
		onClickButton: function(){
			oper_suppitems='edit';
			selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",selRowId,'edit', '');
			recstatusDisable();
		}, 
		position: "first", 
		title:"Edit Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-plus", 
		onClickButton: function(){
			oper_suppitems='add';
			$( "#Dsuppitems" ).dialog( "open" );
			//$('#Fsuppitems :input[name=si_lineno_]').hide();
			//$("#Fsuppitems :input[name*='SuppCode']").val(selrowData('#jqGrid').SuppCode);
		}, 
		position: "first", 
		title:"Add New Row", 
		cursor: "pointer"
	});

	addParamField('#gridSuppitems',false,urlParam_suppitems);
	addParamField('#gridSuppitems',false,saveParam_suppitems,["p_description", "si_idno", "si_adduser", "si_adddate", "si_upduser", "si_upddate", "si_computerid", 'si_recstatus']);

	populateSelect('#gridSuppitems','#searchForm2');
	searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// suppbonus //////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var dialog_bonpricecode = new ordialog(
		'sb_bonpricecode','material.pricesource',"#Fsuppbonus :input[name='sb_bonpricecode']",errorField,
		{	colModel:[
				{label:'Code',name:'pricecode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
			},
				ondblClickRow: function () {
					$('#sb_bonitemcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#sb_bonitemcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Bonus Price Code",
			open: function(){
				dialog_bonpricecode.urlParam.filterCol=['recstatus'],
				dialog_bonpricecode.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_bonpricecode.makedialog(true);

	var dialog_bonitemcode = new ordialog(
		'sb_bonitemcode','material.product',"#Fsuppbonus :input[name='sb_bonitemcode']",errorField,
		{	colModel:[
				{label:'Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
			},
				ondblClickRow: function () {
					$('#sb_bonuomcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#sb_bonuomcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Bonus Item Code",
			open: function(){
				dialog_bonitemcode.urlParam.filterCol=['recstatus'],
				dialog_bonitemcode.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_bonitemcode.makedialog(true);

	var dialog_bonuomcode = new ordialog(
		'sb_bonuomcode','material.uom',"#Fsuppbonus :input[name='sb_bonuomcode']",errorField,
		{	colModel:[
				{label:'Code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
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
			title:"Select Bonus UOM Code",
			open: function(){
				dialog_bonuomcode.urlParam.filterCol=['recstatus'],
				dialog_bonuomcode.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_bonuomcode.makedialog(true);
	
	var buttbonus1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#Fsuppbonus').isValid({requiredFields: ''}, {}, true) ) {
				saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",oper_suppbonus,saveParam_suppbonus,urlParam_suppbonus);
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper_suppbonus;
	$("#Dsuppbonus")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper_suppbonus) {
				case state = 'add':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#Fsuppbonus');
					rdonly('#Fsuppbonus');
					hideOne('#Fsuppbonus');
					$(this).dialog("option", "buttons",buttbonus1);
					break;
				case state = 'edit':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#Fsuppbonus');
					frozeOnEdit("#Dsuppbonus");
					rdonly('#Fsuppbonus');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",buttbonus1);
					break;
				case state = 'view':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#Fsuppbonus');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper_suppbonus!='view'){
				dialog_bonpricecode.on();
				dialog_bonitemcode.on();
				dialog_bonuomcode.on();
			}
			if(oper_suppbonus!='add'){
				dialog_bonpricecode.check(errorField);
				dialog_bonitemcode.check(errorField);
				dialog_bonuomcode.check(errorField);
			}
		},
		close: function( event, ui ) {
			emptyFormdata(errorField,'#Fsuppbonus');
			parent_close_disabled(false);
			//$('.alert').detach();
			$('#Fsuppbonus .alert').detach();
			dialog_bonpricecode.off();
			dialog_bonitemcode.off();
			dialog_bonuomcode.off();
			$("#Fsuppbonus a").off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",buttbonus1);
			}
		},
		buttons :buttbonus1,
	  });
	/////////////////////parameter for jqgrid url Item/////////////////////////////////////////////////
	var urlParam_suppbonus={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		fixPost:'true',//replace underscore with dot
		table_name:['material.suppbonus as sb','material.product as p'],
		table_id:'sb_lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['sb.bonitemcode'],
		join_onVal:['p.itemcode'],
		filterCol:['sb.itemcode', 'sb.suppcode',  'sb.compcode', 'p.compcode'],
		filterVal:['', '', 'session.compcode', 'session.compcode'],
		sort_idno:true,
	}

	var saveParam_suppbonus={
		action:'save_table_default',
		url:'supplier/form',
		field:'',
		oper:oper_suppitems,
		table_name:'material.suppbonus',//for save_table_default, use only 1 table
		fixPost:'true',//throw out dot in the field name
		idnoUse:'sb_idno',
		// filterCol:['suppcode'],
		// filterVal:[''],//suppcode set when click supplier grid
		noduplicate:true,
		lineno:{useOn:'lineno_'},
		saveip:'true'
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////

	$("#gridSuppBonus").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'compcode', name: 'compcode', width: 90, hidden:true, classes: 'wrap'},
		 	{ label: 'Supplier Code', name: 'sb_suppcode', width: 50, hidden: true},
		 	{ label: 'no', name: 'sb_lineno_', width: 50, hidden: true},
		 	{ label: 'itemcode', name: 'sb_itemcode', width: 50, hidden: true},
			{ label: 'Price Code', name: 'sb_pricecode', width: 30, hidden: true},
			{ label: 'uomcode', name: 'sb_uomcode', width: 50, hidden: true},
			{ label: 'purqty', name: 'sb_purqty', width: 50, hidden: true},
			{ label: 'bonpricecode', name: 'sb_bonpricecode', width: 50, hidden: true},
		 	{ label: 'Bonus Item Code', name: 'sb_bonitemcode', width: 200, classes: 'wrap', canSearch: true},
			{ label: 'Item Description', name: 'p_description', width: 400, classes: 'wrap', checked:true,canSearch: true},
			{ label: 'Bonus UOM Code', name: 'sb_bonuomcode', width: 200, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Bonus Quantity', name: 'sb_bonqty', width: 200, align: 'right', classes: 'wrap', formatter:'currency'}, 
			{ label: "Supplier's Item Code", name: 'sb_bonsitemcode', width: 200, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Status', name: 'sb_recstatus', width: 200, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{label: 'No', name: 'sb_idno', width: 50, hidden: true},
			{ label: 'adduser', name: 'sb_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'sb_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'sb_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'sb_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'idno', name: 'sb_idno', width: 90, hidden:true},
			{ label: 'computerid', name: 'sb_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'sb_lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
		],
		viewrecords: true,
		shrinkToFit: true,
		autowidth:true,
        multiSort: true,
		loadonce:false,
		width: 900,
		height: 100,
		rowNum: 30,
		hidegrid: false,
		caption: caption('searchForm3','Bonus Items Given by the Supplier for the item'),
		pager: "#jqGridPager3",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager3 td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#gridSuppBonus").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#gridSuppBonus").jqGrid ('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			if($("#gridSuppBonus").getGridParam("reccount") >= 1){
				$("#jqGridPager2glyphicon-trash").hide();
			} 

			if($("#gridSuppBonus").getGridParam("reccount") < 1){
				$("#jqGridPager2glyphicon-trash").show()
			}
			
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#gridSuppBonus').jqGrid ('getRowData', rowid);
			}
		},
	});
	
	$("#gridSuppBonus").jqGrid('navGrid','#jqGridPager3',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridSuppBonus",urlParam_suppbonus);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-trash", 
		onClickButton: function(){
			oper_suppitems='del';
			var selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#Fsuppbonus');
			}else{
				saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",'del',saveParam_suppbonus,urlParam_suppbonus,null,{'idno':selRowId});
			}
		}, 
		position: "first", 
		title:"Delete Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-info-sign", 
		onClickButton: function(){
			oper_suppbonus='view';
			selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",selRowId,'view', '');
		}, 
		position: "first", 
		title:"View Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-edit", 
		onClickButton: function(){
			oper_suppbonus='edit';
			selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",selRowId,'edit', '');
			recstatusDisable();
		}, 
		position: "first", 
		title:"Edit Selected Row", 
		cursor: "pointer"
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		caption:"", 
		buttonicon:"glyphicon glyphicon-plus", 
		onClickButton: function(rowid, selected){
			oper_suppbonus='add';
			$( "#Dsuppbonus" ).dialog( "open" );
		}, 
		position: "first", 
		title:"Add New Row", 
		cursor: "pointer"
	});

	addParamField('#gridSuppBonus',false,urlParam_suppbonus);
	addParamField('#gridSuppBonus',false,saveParam_suppbonus,["p_description", "sb_idno", "sb_adduser","sb_adddate", "sb_upduser","sb_upddate", "sb_computerid", "sb_recstatus"]);

	populateSelect('#gridSuppBonus','#searchForm3');
	searchClick('#gridSuppBonus','#searchForm3',urlParam_suppbonus);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridSuppitems").jqGrid ('setGridWidth', Math.floor($("#gridSuppitems_c")[0].offsetWidth-$("#gridSuppitems_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_panel2").on("show.bs.collapse", function(){
		$("#gridSuppBonus").jqGrid ('setGridWidth', Math.floor($("#gridSuppBonus_c")[0].offsetWidth-$("#gridSuppBonus_c")[0].offsetLeft-28));
	});

});

function populate_form(obj){

	//panel header
	$('#suppItemCode_show').text(obj.SuppCode);
	$('#suppItemname_show').text(obj.Name);

	$('#suppBonCode_show').text(obj.SuppCode);
	$('#suppBonName_show').text(obj.Name);

	$('#attachCode_show').text(obj.SuppCode);
	$('#attachName_show').text(obj.Name);
	
}

function empty_form(){

	$('#suppcode_show').text('');
	$('#suppname_show').text('');

}