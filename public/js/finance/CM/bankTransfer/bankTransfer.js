
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
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};
	//////////////////////////////////////////////////////////////

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount']);
	var fdl = new faster_detail_load();

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#actdate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog///////////////////////////////////////

	var butt1=[{
		id: "saveBut", 
		text: "Save",click: function() {
			unsaved = false;
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
				if($('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					if ($("#formdata :input[name='payto']").val() === $("#formdata :input[name='bankcode']").val()) {
						bootbox.alert("Bank Code Credit cannot be same with Bank Code Debit");
					}
					else {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
					}
				}else{
					mycurrency.formatOn();
				}
			}


	},{
		id: "canBut",
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	/////////////////////////start dialog//////////////////////////////////////////	
	var oper = 'add';
	var unsaved = false;
	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					rdonly("#formdata");
					hideOne("#formdata");
					$("#saveBut").show();
					$("#canBut").show();
					setpaymodeused();
				break;

				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					setpaymodeused('edit');
				break;

				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$(this).dialog("option", "buttons",butt2);
					$('#formdata :input[hideOne]').show();
					setpaymodeused('view');
				break;
			}
			if(oper!='view'){
				set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
				dialog_paymode.on();
				dialog_bankcodefrom.on();
				dialog_bankcodeto.on();
			}
			if(oper!='add'){
				dialog_paymode.check(errorField);
				dialog_bankcodefrom.check(errorField);
				dialog_bankcodeto.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			dialog_paymode.off();
			dialog_bankcodefrom.off();
			dialog_bankcodeto.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		// action:'get_table_default',
		// url:'util/get_table_default',
		// field:'',
		// table_name:'finance.apacthdr',
		// table_id:'auditno',
		// filterCol: ['source', 'trantype', 'compcode'],
		// filterVal: ['CM', 'FT', 'session.compcode'],
		// sort_idno: true
		action:'maintable',
		url:'./bankTransfer/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'ftheader_save',
		url:'bankTransfer/form',
		field:'',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'auditno',
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
			{label: 'Bank Code From', name: 'bankcode', width: 35, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{label: 'Bank Code To', name: 'payto', width: 35, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{label: 'Payment No', name: 'pvno', width: 40, hidden: true, classes: 'wrap'},
			{label: 'Transfer Date', name: 'actdate', width: 25, canSearch:true, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
			{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency', align:'right'},
			{label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap'},
			{label: 'Status', name: 'recstatus', width: 30, classes: 'wrap'},
			{label: 'Entered By', name: 'adduser', width: 30, classes: 'wrap'},
			{label: 'Entered Date', name: 'adddate', width: 30, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{label: 'Paymode', name: 'paymode', width: 30, classes: 'wrap', canSearch:false, formatter: showdetail,unformat:un_showdetail},
			{label: 'Cheq No', name: 'cheqno', width: 30, classes: 'wrap', formatter:formatterCheqnno, unformat:unformatterCheqnno},
			{ label: 'unit', name: 'unit', width: 40, hidden:'true'},
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
				$("#saveBut").show();
				$("#canBut").show();
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();

			if($('#jqGrid').data('inputfocus') == 'bankcode_search'){
				$("#bankcode_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#bankcode_search_hb').text('');
				removeValidationClass(['#bankcode_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		onSelectRow: function(rowid, selected) {
			let recstatus = selrowData("#jqGrid").recstatus;
			if(recstatus=='OPEN'){
				$('#but_cancel_jq,#but_post_jq').show();
				
			}else if(recstatus=="POSTED"){
				$('#but_post_jq').hide();
				$('#but_cancel_jq').show();
			}else if (recstatus == "CANCELLED"){
				$('#but_cancel_jq,#but_post_jq').hide();
				
			}

			urlParam3.filterVal[3] = selrowData("#jqGrid").idno;
			refreshGrid("#jqGrid3",urlParam3);
			
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},
		
	});
	
	///////////////////////////////detail luar///////////////////////////////////
	var urlParam3={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.apacthdr',
		table_id:'idno',
		filterCol: ['source', 'trantype', 'compcode','idno'],
		filterVal: ['CM', 'FT', 'session.compcode',],
	}

	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: [
		 	{label: 'compcode', name: 'compcode', width: 10 , hidden: true,  classes: 'wrap'},
		 	{label: 'idno', name: 'idno', width: 10 , hidden: true,  classes: 'wrap'},
		 	{label: 'source', name: 'source', width: 10, hidden: true, classes: 'wrap'},
		 	{label: 'trantype', name: 'trantype', width: 10, hidden: true, classes: 'wrap'},
			{label: 'Audit No', name: 'auditno', width: 5, classes: 'wrap' },
			{label: 'Payment No', name: 'pvno', width: 20, classes: 'wrap'},
			{label: 'Transfer Date', name: 'actdate', width: 20, classes: 'wrap'},
			{label: 'Bank Code To', name: 'payto', width: 80, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
			{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency', align:'right'},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager3",
		onSelectRow:function(rowid, selected){
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid3");
		}				
	});
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////searching/////////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#actdate_search').on('click', searchDate);

	function whenchangetodate() {
		bankcode_search.off();
		$('#bankcode_search,#actdate_from,#actdate_to').val('');
		$('#bankcode_search_hb').text('');
		urlParam.filterdate = null;
		removeValidationClass(['#bankcode_search']);
		if($('#Scol').val()=='actdate'){
			$("input[name='Stext'], #bankcode_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'bankcode' || $('#Scol').val() == 'payto'){
			$("input[name='Stext'],#actdate_text").hide("fast");
			$("#bankcode_text").show("fast");
			bankcode_search.on();
		} else {
			$("#bankcode_text,#actdate_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
	}

	////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy() {
		$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
			if (value['canSearch']) {
				if (value['selected']) {
					$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
				} else {
					$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
				}
			}
			searchClick2('#jqGrid', '#searchForm', urlParam);
		});
	}

	function searchDate(){
		urlParam.filterdate = [$('#actdate_from').val(),$('#actdate_to').val()];
		refreshGrid('#jqGrid',urlParam);
	}

	function searchChange(){
		var arrtemp = [$('#Status option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ap.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	var bankcode_search = new ordialog(
		'bankcode_search', 'finance.bank', '#bankcode_search', 'errorField',
		{
			colModel: [
				{ label: 'Bank Code', name: 'bankcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'bankname', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + bankcode_search.gridname).bankcode;

				if($('#Scol').val() == 'bankcode'){
					urlParam.searchCol=["ap.bankcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'payto'){
					urlParam.searchCol=["ap.payto"];
					urlParam.searchVal=[data];
				}
				refreshGrid('#jqGrid', urlParam);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Bankcode",
			open: function () {
				bankcode_search.urlParam.filterCol = ['compcode', 'recstatus'];
				bankcode_search.urlParam.filterVal = ['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	bankcode_search.makedialog(true);
	$('#bankcode_search').on('keyup',ifnullsearch);

	function ifnullsearch(){
		if($('#bankcode_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','bankcode_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}

	////////////////////formatter status////////////////////////////////////////
		
	function formatterCheqnno  (cellValue, options, rowObject) {
		//return rowObject[9] != "CHEQUE" ? "&nbsp;" : $.jgrid.htmlEncode(cellValue);
		return rowObject[15] != "CHEQUE" ? "<span cheqno='"+cellValue+"'></span>" : "<span cheqno='"+cellValue+"'>"+cellValue+"</span>";

	}

	function unformatterCheqnno (cellValue, options, rowObject) {
		return $(rowObject).find('span').attr('cheqno');
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'paymode':field=['paymode','description'];table="debtor.paymode";break;
			case 'payto':field=['bankcode','bankname'];table="finance.bank";break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('bankTransfer',options,param,case_,cellvalue);
		
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
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'auditno':selrowData('#jqGrid').auditno});
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
			$( "#formdata :input[name='source']" ).val( "CM" );
			$( "#formdata :input[name='trantype']" ).val( "FT" );

		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	populateSelect2('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress', 'auditno','pvno','trantype','source']);

	addParamField('#jqGrid3',false,urlParam);

	$("#but_post_jq").click(function(){
		var idno = selrowData('#jqGrid').idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = "posted";

		$.post( '/bankTransfer/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			// $('#p_error').text(data.responseText);
		}).success(function(data){
			
		});

	});


	////////////////////object for dialog handler//////////////////

	var dialog_paymode = new ordialog(
		'paymode','debtor.paymode','#paymode',errorField,
		{	colModel:[
				{label:'Pay Mode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source'],
				filterVal:['session.compcode','ACTIVE', 'CM']
			},
			ondblClickRow: function () {
				$('#bankcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#bankcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Payment",
			open: function(){
				dialog_paymode.urlParam.filterCol=['compcode','recstatus', 'source'],
				dialog_paymode.urlParam.filterVal=['session.compcode','ACTIVE', 'CM']
			},
			close: function(){
				setpaymodeused();
			}
		},'urlParam','radio','tab'
	);
	dialog_paymode.makedialog(true);

	var dialog_bankcodefrom = new ordialog(
		'bankcode','finance.bank','#bankcode',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#cheqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcodefrom.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodefrom.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodefrom.makedialog(true);

	var dialog_bankcodeto = new ordialog(
		'payto','finance.bank','#payto',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#remarks').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code Pay To",
			open: function(){
				dialog_bankcodeto.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodeto.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodeto.makedialog(true);

	var dialog_cheqno = new ordialog(
		'cheqno','finance.chqtran','#cheqno',errorField,
		{	colModel:[
				{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				{label:'bankcode',name:'bankcode',width:200,classes:'pointer',hidden:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','OPEN']
			},
			ondblClickRow: function () {
				$('#cheqdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Cheque No",
			open: function(){
				dialog_cheqno.urlParam.filterCol=['compcode','recstatus', 'bankcode'],
				dialog_cheqno.urlParam.filterVal=['session.compcode','OPEN', $('#bankcode').val()]
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_cheqno.makedialog(true);

	function setpaymodeused(oper='add'){
		var paymode = $("#paymode").val();
		dialog_cheqno.off();
		$('#chg_div,#cheqno_a,#chq_div').hide();
		if(paymode == "CHEQUE"){
			$('#chg_div,#cheqno_a,#chq_div').show();
			$('#chg_label').text('Cheque No');
			dialog_cheqno.on();
			if(oper != 'add'){
				dialog_cheqno.check(errorField);
			}
		}else if (paymode == "CASH"){
			$('#chg_label').text('');
		}else if (paymode == "BD"){
			$('#chg_div').show();
			$('#chg_label').text('BD No');
		}else if (paymode == "TT"){
			$('#chg_div').show();
			$('#chg_label').text('TT No');
		}
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}

		