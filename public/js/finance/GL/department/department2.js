
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	//////////////////////validation//////////////////////
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
	
	//////////////////////////////////////////////////////////////////////////////////////
	var fdl = new faster_detail_load();
	
	///////////////////////////////object for dialog handler///////////////////////////////
	var dialog_costcode = new ordialog(
		'costcode','finance.costcenter','#costcode',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'costcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#category').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#category').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Cost Center",
			open: function(){
				dialog_costcode.urlParam.filterCol= ['recstatus'],
				dialog_costcode.urlParam.filterVal= ['ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_costcode.makedialog(true);
	
	var dialog_sector = new ordialog(
		'sector','sysdb.sector','#sector',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'sectorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#chgdept').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#chgdept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Sector",
			open: function(){
				dialog_sector.urlParam.filterCol= ['regioncode','recstatus'],
				dialog_sector.urlParam.filterVal= [$("#formdata :input[name='region']").val(),'ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_sector.makedialog(true);
	
	var dialog_region = new ordialog(
		'region','sysdb.region','#region',errorField,
		{
			colModel: [
				{ label: 'Code', name: 'regioncode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#sector').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#sector').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Region",
			open: function(){
				dialog_region.urlParam.filterCol= ['recstatus'],
				dialog_region.urlParam.filterVal= ['ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_region.makedialog(true);
	
	//////////////////////////////////////start dialog//////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			radbuts.check();
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
						rdonly('#formdata');
						hideOne('#formdata');
						break;
					case state = 'edit':
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
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
					dialog_costcode.on();
					dialog_sector.on();
					dialog_region.on();
				}
				if(oper!='add'){
					dialog_costcode.check(errorField);
					dialog_sector.check(errorField);
					dialog_region.check(errorField);
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				// $('.alert').detach();
				$('.my-alert').detach();
				dialog_costcode.off();
				dialog_sector.off();
				dialog_region.off();
				if(oper=='view'){
					$(this).dialog("option", "buttons",butt1);
				}
			},
			buttons :butt1,
		});
	////////////////////////////////////////end dialog////////////////////////////////////////
	
	/////////////////////////////////parameter for jqgrid url/////////////////////////////////
	var urlParam={
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'sysdb.department',
		table_id: 'deptcode',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
		sort_idno: true,
	}
	
	/////////////////////////////////parameter for saving url/////////////////////////////////
	var saveParam={
		action: 'save_table_default',
		url:  './department/form',
		field: '',
		oper: oper,
		table_name: 'sysdb.department',
		table_id: 'deptcode',
		saveip: 'true',
		checkduplicate: 'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Department', name: 'deptcode', width: 30, classes: 'wrap', canSearch: true },
			{ label: 'Description', name: 'description', width: 80, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Cost Code', name: 'costcode', width: 40, classes: 'wrap', formatter: showdetail, unformat: unformat_showdetail },
			{ label: 'Unit', name: 'sector', width: 30, hidden: false, classes: 'wrap' },
			{ label: 'Purchase', name: 'purdept', width: 25, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Register', name: 'regdept', width: 20, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Charge', name: 'chgdept', width: 20, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Ward', name: 'warddept', width: 20, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Admission', name: 'admdept', width: 25, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Dispense', name: 'dispdept', width: 25, formatter:formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Store', name: 'storedept', width: 20, formatter: formatterstatus_tick2, unformat: unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Category', name: 'category', width: 30, classes: 'wrap' },
			{ label: 'Region', name: 'region', hidden: true, classes: 'wrap' },
			{ label: 'adduser', name: 'adduser', width: 50, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 50, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 50, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 50, hidden: true, classes: 'wrap' },
			{ label: 'Status', name: 'recstatus', width: 25, classes: 'wrap', cellattr: function(rowid, cellvalue) {
				return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
			{ label: 'idno', name: 'idno', hidden:  true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
		],
		autowidth:true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
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
	
	function checkradiobutton(radiobuttons){
		this.radiobuttons=radiobuttons;
		this.check = function(){
			$.each(this.radiobuttons, function( index, value ) {
				var checked = $("input[name="+value+"]:checked").val();
				// alert(itemtype);
				if(!checked){
					$("label[for="+value+"]").css('color', 'red');
					$(":radio[name='"+value+"']").parent('label').css('color', 'red');
				}else{
					$("label[for="+value+"]").css('color', '#444444');
					$(":radio[name='"+value+"']").parent('label').css('color', '#444444');
				}
			});
		}
	}
	
	var radbuts=new checkradiobutton(['category','chgdept','purdept','admdept','warddept','regdept', 'dispdept', 'storedept']);
	
	///////////////////////////////////////start grid pager///////////////////////////////////////
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
				// saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'deptcode':selRowId});
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
	////////////////////////////////////////////end grid////////////////////////////////////////////
	
	//////////////////////////handle searching, its radio button and toggle//////////////////////////
	// toogleSearch('#sbut1','#searchForm','on');
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);
	
	//////////////////////////add field into param, refresh grid if needed//////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode', 'computerid', 'ipaddress','adduser','adddate','upduser','upddate','recstatus']);
	
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'costcode':field=['costcode','description'];table="finance.costcenter";case_='costcode';break;
		}
		var param={action:'input_check',url:'./util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('department',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
		
		return cellvalue;
	}
	
	function unformat_showdetail(cellvalue, options, rowObject){
		return $(rowObject).attr('title');
	}
	
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
});
		