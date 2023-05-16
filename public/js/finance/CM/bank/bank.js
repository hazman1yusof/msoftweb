$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
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

	var fdl = new faster_detail_load();

	////////////////////object for dialog handler//////////////////

	var dialog_depccode = new ordialog(
		'glccode','finance.costcenter','#glccode',errorField,
		{	colModel:[
				{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
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
			title:"Select Deposit Cost",
			open: function(){
				dialog_depccode.urlParam.filterCol=['compcode','recstatus'],
				dialog_depccode.urlParam.filterVal=['session.compcode','ACTIVE']
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
			title:"Select Deposit GL Account",
			open: function(){
				dialog_depglacc.urlParam.filterCol=['compcode','recstatus'],
				dialog_depglacc.urlParam.filterVal=['session.compcode','ACTIVE']
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
		table_name:'finance.bank',
		table_id:'bankcode',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'bank/form',
		field:'',
		oper:oper,
		table_name:'finance.bank',
		table_id:'bankcode',
		saveip:'true',
		checkduplicate:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{ label: 'compcode', name: 'compcode',  hidden:true},					
			{ label: 'Bank Code', name: 'bankcode', width: 20, classes: 'wrap' , canSearch: true},					
			{ label: 'Bank Name', name: 'bankname', width: 40 , classes: 'wrap', checked:true, canSearch: true },					
			{ label: 'Address', name: 'address1', width: 60, classes: 'wrap',  formatter:formatterAddress, unformat: unformatAddress},					
			{ label: 'address2', name: 'address2', width: 90 , classes: 'wrap' ,  hidden:true},					
			{ label: 'address3', name: 'address3', width: 90, classes: 'wrap' , hidden:true},					
			{ label: 'postcode:', name: 'postcode', width: 90, classes: 'wrap' , hidden:true},					
			{ label: 'State Code', name: 'statecode', width: 90, classes: 'wrap' ,  hidden:true},					
			{ label: 'Country', name: 'country', width: 90 , classes: 'wrap' ,  hidden:true},					
			{ label: 'Tel No', name: 'telno', width: 30 , classes: 'wrap' },					
			{ label: 'Fax No', name: 'faxno', width: 90, classes: 'wrap' ,  hidden:true},					
			{ label: 'Contact Person', name: 'contact', width: 90, hidden:true,},					
			{ label: 'Bank Account No', name: 'bankaccount', classes: 'wrap' , width: 40},					
			{ label: 'Clearing Days', name: 'clearday', classes: 'wrap' , width: 90, hidden:true},					
			{ label: 'Effect Date:', name: 'effectdate', classes: 'wrap' , width: 90,  hidden:true},					
			{ label: 'Gl Account No', name: 'glaccno', classes: 'wrap' , width: 40, formatter: showdetail, unformat:un_showdetail},					
			{ label: 'Cost Center', name: 'glccode', classes: 'wrap' , width: 30, formatter: showdetail, unformat:un_showdetail},					
			{ label: 'Petty Cash', name: 'pctype', width: 90, hidden:true},					
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
			{ label: 'Open Balance', name: 'openbal', width: 90,  hidden:true},	
			{ label: 'Last PV No', name: 'lastpvno', width: 30 , classes: 'wrap' },								
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{label: 'idno', name: 'idno', hidden: true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 300,
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

	/////////////////formatter address///////////////////////////
	function formatterAddress (cellvalue, options, rowObject){
		let add1 = rowObject.address1;
		let add2 = rowObject.address2;
		let add3 = rowObject.address3;
		let postcode = rowObject.postcode;
		let state = rowObject.statecode;
		let fulladdress =  add1 + '</br> ' + add2 + '</br> ' + add3 + ' </br>' + postcode + ' </br>' + state;
		return fulladdress;
	}

	function decodeEntities(s){
		var str, temp= document.createElement('p');
		temp.innerHTML= s;
		str= temp.textContent || temp.innerText;
		temp=null;
		return str;
	}

	function unformatAddress (cellvalue, options, rowObject){
		let fulladdress = $(rowObject).html();
		let addr1 = fulladdress.slice(0, fulladdress.search("[<]"));
		addr1 = decodeEntities(addr1);
		return addr1;
	}

	//////////////////////////////////////xxformatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'glccode':field=['costcode','description'];table="finance.costcenter";case_='glccode';break;
			case 'glaccno':field=['glaccno','description'];table="finance.glmasref";case_='glaccno';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bank',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
		
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
	
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);
});	