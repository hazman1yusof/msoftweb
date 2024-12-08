
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
	
	$('.nav-tabs a').on('shown.bs.tab', function(e){
		let trantype = $(this).data('trantype');
		$('#searchForm input[name=Stext]').val('');
		urlParam_divs.searchCol=null;
		urlParam_divs.searchVal=null;
		switch(trantype){
			case 'DIVS':
				refreshGrid('#jqGrid_divs', urlParam_divs);
				populateSelect2('#jqGrid_divs', '#searchForm');
				searchClick2('#jqGrid_divs', '#searchForm', urlParam_divs);
				$("#jqGrid_divs").jqGrid ('setGridWidth', Math.floor($("#tab-divs")[0].offsetWidth-$("#tab-divs")[0].offsetLeft));
				break;
			case 'UNIT':
				refreshGrid("#jqGrid_divunit",urlParam_divs);
				populateSelect2('#jqGrid_unit', '#searchForm');
				searchClick2('#jqGrid_unit', '#searchForm', urlParam_unit);
				// refreshGrid("#jqGrid_unit", urlParam_unit);
				$("#jqGrid_divunit").jqGrid ('setGridWidth', Math.floor($("#tab-unit")[0].offsetWidth-$("#tab-unit")[0].offsetLeft)/2);
				$("#jqGrid_unit").jqGrid ('setGridWidth', Math.floor($("#tab-unit")[0].offsetWidth-$("#tab-unit")[0].offsetLeft));
				break;
			case 'DEPT':
				refreshGrid("#jqGrid_divdept",urlParam_divs);
				populateSelect2('#jqGrid_dept','#searchForm');
				searchClick2('#jqGrid_dept','#searchForm',urlParam_dept);
				// refreshGrid("#jqGrid_unitdept",urlParam_unit);
				// refreshGrid("#jqGrid_dept",urlParam_dept);
				$("#jqGrid_divdept").jqGrid ('setGridWidth', Math.floor($("#tab-dept")[0].offsetWidth-$("#tab-dept")[0].offsetLeft)/2);
				$("#jqGrid_unitdept").jqGrid ('setGridWidth', Math.floor($("#tab-dept")[0].offsetWidth-$("#tab-dept")[0].offsetLeft)/2);
				$("#jqGrid_dept").jqGrid ('setGridWidth', Math.floor($("#tab-dept")[0].offsetWidth-$("#tab-dept")[0].offsetLeft));
				break;
		}
	});
	
	//////////////////////////////////////////////////////////////////////////////////////
	var fdl = new faster_detail_load();
	var err_reroll_divs = new err_reroll('#jqGrid_divs',['regioncode', 'description']);
	var err_reroll_unit = new err_reroll('#jqGrid_unit',['sectorcode', 'description', 'regioncode']);
	
	///////////////////////////////object for dialog handler///////////////////////////////
	//////////////////////////////////////unit starts//////////////////////////////////////
	var dialog_regioncode = new ordialog(
		'regioncode','sysdb.region',"#jqGrid_unit input[name='regioncode']",errorField,
		{
			colModel: [
				{ label: 'Region Code', name: 'regioncode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');regioncode
				}
			}
		},{
			title:"Select Region Code",
			open: function(){
				dialog_regioncode.urlParam.filterCol= ['compcode','recstatus'],
				dialog_regioncode.urlParam.filterVal= ['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_regioncode.makedialog(true);
	///////////////////////////////////////unit ends///////////////////////////////////////
	
	///////////////////////////////////department starts///////////////////////////////////
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
				dialog_costcode.urlParam.filterCol= ['compcode','recstatus'],
				dialog_costcode.urlParam.filterVal= ['session.compcode','ACTIVE']
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
				dialog_sector.urlParam.filterCol= ['compcode','regioncode','recstatus'],
				dialog_sector.urlParam.filterVal= ['session.compcode',$("#formdata_dept :input[name='region']").val(),'ACTIVE']
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
				dialog_region.urlParam.filterCol= ['compcode','recstatus'],
				dialog_region.urlParam.filterVal= ['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_region.makedialog(true);
	////////////////////////////////////department ends////////////////////////////////////
	
	//////////////////////////////////////start dialog//////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			radbuts.check();
			if( $('#formdata_dept').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid_dept","#dialogForm_dept","#formdata_dept",oper,saveParam_dept,urlParam_dept);
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
	$("#dialogForm_dept")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				parent_close_disabled(true);
				switch(oper) {
					case state = 'add':
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#formdata_dept');
						rdonly('#formdata_dept');
						hideOne('#formdata_dept');
						break;
					case state = 'edit':
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata_dept');
						frozeOnEdit("#dialogForm_dept");
						$('#formdata_dept :input[hideOne]').show();
						break;
					case state = 'view':
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata_dept');
						$('#formdata_dept :input[hideOne]').show();
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
				emptyFormdata(errorField,'#formdata_dept');
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
	
	//////////////////////////////////////division starts//////////////////////////////////////
	//////////////////////////////////parameter for jqgrid url//////////////////////////////////
	var urlParam_divs = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'sysdb.region',
		table_id: 'regioncode',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
		sort_idno: true
	}
	
	///////////////////////////////////parameter for saving url///////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	$("#jqGrid_divs").jqGrid({
		datatype: "local",
		editurl: "./region/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 5, hidden: true, key: true },
			{ label: 'idno', name: 'idno', width: 5, hidden: true, key: true },
			{ label: 'Section Code', name: 'regioncode', width: 30, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap', canSearch: true, checked: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Add User', name: 'adduser', width: 40, hidden: false },
			{ label: 'Add Date', name: 'adddate', width: 50, hidden: false },
			{ label: 'Upd User', name: 'upduser', width: 40, hidden: false },
			{ label: 'Upd Date', name: 'upddate', width: 50, hidden: false },
			{ label: 'Computer ID', name: 'computerid', width: 40, hidden: false },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true },
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', hidden: false, editable: true, edittype: "select", formatter: 'select',
				editoptions:{ value: "ACTIVE:ACTIVE;DEACTIVE:DEACTIVE" },
				cellattr: function(rowid, cellvalue) {
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				},
			},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager_divs",
		onSelectRow:function(rowid, selected){
			if(!err_reroll_divs.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_divs_iladd').click();
			}else if($('#jqGrid_divs').data('lastselrow') == 'none'){
				$("#jqGrid_divs").setSelection($("#jqGrid_divs").getDataIDs()[0]);
			}else{
				$("#jqGrid_divs").setSelection($('#jqGrid_divs').data('lastselrow'));
				$('#jqGrid_divs tr#' + $('#jqGrid_divs').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll_divs.error == true){
				err_reroll_divs.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_divs_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			if($('#jqGrid_divs').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid_divs").setSelection($("#jqGrid_divs").getDataIDs()[0]);
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	function check_cust_rules_divs(rowid){
		var chk = ['regioncode','description'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid_divs input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid_divs input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid_divs input[name='"+e+"']",false);
			}
		})
	}
	
	/////////////////////////////////////////myEditOptions/////////////////////////////////////////
	var myEditOptions_divs = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid_divs').data('lastselrow','none');
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").hide();
			$("input[name='description']").keydown(function(e) {	//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_divs_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_divs_ilsave').click();
			});
			$("#jqGrid_divs input[type='text']").on('focus',function(){
				$("#jqGrid_divs input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid_divs input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_divs',urlParam_divs,'add');
			errorField.length=0;
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			// $('#p_error').text(response.responseText);
			err_reroll_divs.old_data = data.request;
			err_reroll_divs.error = true;
			err_reroll_divs.errormsg = data.errormsg;
			refreshGrid('#jqGrid_divs',urlParam_divs,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid_divs').jqGrid ('getRowData', rowid);
			console.log(data);
			
			check_cust_rules_divs();
			let editurl = "./region/form?"+
				$.param({
					action: 'region_save',
				});
			$("#jqGrid_divs").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_divs',urlParam_divs,'add');
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	var myEditOptions_edit_divs = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid_divs').data('lastselrow',rowid);
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").hide();
			$("input[name='regioncode']").attr('disabled','disabled');
			$("input[name='description']").keydown(function(e) {	//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_divs_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_divs_ilsave').click();
			});
			$("#jqGrid_divs input[type='text']").on('focus',function(){
				$("#jqGrid_divs input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid_divs input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_divs',urlParam_divs,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid_divs',urlParam_divs,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid_divs').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./region/form?"+
				$.param({
					action: 'region_save',
				});
			$("#jqGrid_divs").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_divs',urlParam_divs,'edit');
			$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	////////////////////////////////////////start grid pager////////////////////////////////////////
	$("#jqGrid_divs").inlineNav('#jqGridPager_divs', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_divs
		},
		editParams: myEditOptions_edit_divs
	}).jqGrid('navButtonAdd', "#jqGridPager_divs", {
		id: "jqGridPagerDelete_divs",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid_divs").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'region_save',
								regioncode: $('#regioncode').val(),
								idno: selrowData('#jqGrid_divs').idno,
							}
							$.post( "./region/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid_divs", urlParam_divs);
							});
						}else{
							$("#jqGridPagerDelete_divs,#jqGridPagerRefresh_divs").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_divs", {
		id: "jqGridPagerRefresh_divs",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_divs", urlParam_divs);
		},
	});
	//////////////////////////////////////////end grid//////////////////////////////////////////
	
	///////////////////////handle searching, its radio button and toggle ///////////////////////
	// toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid_divs', '#searchForm');
	searchClick2('#jqGrid_divs', '#searchForm', urlParam_divs);
	
	////////////////////////add field into param, refresh grid if needed////////////////////////
	addParamField('#jqGrid_divs', true, urlParam_divs);
	// addParamField('#jqGrid_divs', false, saveParam, ['idno','adduser','adddate','upduser','upddate','recstatus']);
	///////////////////////////////////////division ends///////////////////////////////////////
	
	////////////////////////////////////////unit starts////////////////////////////////////////
	///////////////////////////////////////jqGrid_divunit///////////////////////////////////////
	$("#jqGrid_divunit").jqGrid({
		datatype: "local",
		editurl: "./region/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 5, hidden: true, key: true },
			{ label: 'idno', name: 'idno', width: 5, hidden: true, key: true },
			{ label: 'Section Code', name: 'regioncode', width: 30, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap', canSearch: true, checked: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Add User', name: 'adduser', width: 40, hidden: true },
			{ label: 'Add Date', name: 'adddate', width: 50, hidden: true },
			{ label: 'Upd User', name: 'upduser', width: 40, hidden: true },
			{ label: 'Upd Date', name: 'upddate', width: 50, hidden: true },
			{ label: 'Computer ID', name: 'computerid', width: 40, hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true },
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', hidden: true, editable: true, edittype: "select", formatter: 'select',
				editoptions:{ value: "ACTIVE:ACTIVE;DEACTIVE:DEACTIVE" },
				cellattr: function(rowid, cellvalue) {
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				},
			},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 150,
		rowNum: 30,
		pager: "#jqGridPager_divunit",
		onSelectRow:function(rowid, selected){
			if(!err_reroll_divs.error)$('#p_error').text('');   //hilangkan error msj after save
			
			urlParam_unit.filterVal[1]=selrowData("#jqGrid_divunit").regioncode;
			refreshGrid("#jqGrid_unit",urlParam_unit);
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_divunit_iladd').click();
			}else if($('#jqGrid_divunit').data('lastselrow') == 'none'){
				$("#jqGrid_divunit").setSelection($("#jqGrid_divunit").getDataIDs()[0]);
			}else{
				$("#jqGrid_divunit").setSelection($('#jqGrid_divunit').data('lastselrow'));
				$('#jqGrid_divunit tr#' + $('#jqGrid_divunit').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll_divs.error == true){
				err_reroll_divs.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_divunit_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			if($('#jqGrid_divunit').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid_divunit").setSelection($("#jqGrid_divunit").getDataIDs()[0]);
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	/////////////////////////////////////start grid pager/////////////////////////////////////
	$("#jqGrid_divunit").jqGrid('navGrid','#jqGridPager_divunit',{
		view:false, edit:false, add:false, del:false, search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_divunit",urlParam_divs);
		},
	});
	
	//////////////////////////////////parameter for jqgrid url//////////////////////////////////
	var urlParam_unit = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'sysdb.sector',
		table_id: 'sectorcode',
		filterCol: ['compcode','regioncode'],
		filterVal: ['session.compcode',''],
		sort_idno: true
	}
	
	//////////////////////////////////parameter for saving url//////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	$("#jqGrid_unit").jqGrid({
		datatype: "local",
		editurl: "./unit/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 10, hidden: true, key: true },
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'Unit', name: 'sectorcode', width: 30, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap', canSearch: true, checked: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Section', name: 'regioncode', width: 40, classes: 'wrap', editable: true, editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element:unitCustomEdit,
					custom_value:galGridCustomValue
				},
			},
			{ label: 'Add User', name: 'adduser', width: 40, hidden: false },
			{ label: 'Add Date', name: 'adddate', width: 50, hidden: false },
			{ label: 'Upd User', name: 'upduser', width: 40, hidden: false },
			{ label: 'Upd Date', name: 'upddate', width: 50, hidden: false },
			{ label: 'Computer ID', name: 'computerid', width: 40, hidden: false },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true },
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', hidden: false, editable: true, edittype: "select", formatter: 'select',
				editoptions: { value: "ACTIVE:ACTIVE;DEACTIVE:DEACTIVE" },
				cellattr: function(rowid, cellvalue) {
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				},
			},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 150,
		rowNum: 30,
		pager: "#jqGridPager_unit",
		onSelectRow:function(rowid, selected){
			if(!err_reroll_unit.error)$('#p_error').text('');	//hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_unit_iladd').click();
			}else if($('#jqGrid_unit').data('lastselrow') == 'none'){
				$("#jqGrid_unit").setSelection($("#jqGrid_unit").getDataIDs()[0]);
			}else{
				$("#jqGrid_unit").setSelection($('#jqGrid_unit').data('lastselrow'));
				$('#jqGrid_unit tr#' + $('#jqGrid_unit').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false;	//reset
			if(err_reroll_unit.error == true){
				err_reroll_unit.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_unit_iledit").click();
			$('#p_error').text('');	//hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			if($('#jqGrid_unit').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid_unit").setSelection($("#jqGrid_unit").getDataIDs()[0]);
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	function check_cust_rules_unit(rowid){
		var chk = ['sectorcode','description'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid_unit input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid_unit input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid_unit input[name='"+e+"']",false);
			}
		})
	}
	
	/////////////////////////////////////////myEditOptions/////////////////////////////////////////
	var myEditOptions_unit = {
		keys: true,
		extraparam:	{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid_unit').data('lastselrow','none');
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").hide();
			
			dialog_regioncode.on();
			
			$("input[name='recstatus']").keydown(function(e) {	//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_unit_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_unit_ilsave').click();
			});
			$("#jqGrid_unit input[type='text']").on('focus',function(){
				$("#jqGrid_unit input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid_unit input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	//only addmore after save inline
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_unit',urlParam_unit,'add');
			errorField.length=0;
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			// $('#p_error').text(response.responseText);
			err_reroll_unit.old_data = data.request;
			err_reroll_unit.error = true;
			err_reroll_unit.errormsg = data.errormsg;
			refreshGrid('#jqGrid_unit',urlParam_unit,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid_unit').jqGrid ('getRowData', rowid);
			console.log(data);
			
			check_cust_rules_unit();
			let editurl = "./unit/form?"+
				$.param({
					action: 'unit_save',
				});
			$("#jqGrid_unit").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_unit',urlParam_unit,'add');
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	var myEditOptions_edit_unit = {
		keys: true,
		extraparam:	{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid_unit').data('lastselrow',rowid);
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").hide();
			
			dialog_regioncode.on();
			
			$("input[name='sectorcode']").attr('disabled','disabled');
			$("input[name='description']").keydown(function(e) {	//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_unit_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_unit_ilsave').click();
			});
			$("#jqGrid_unit input[type='text']").on('focus',function(){
				$("#jqGrid_unit input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid_unit input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	//only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_unit',urlParam_unit,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid_unit',urlParam_unit,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid_unit').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			check_cust_rules_unit();
			let editurl = "./unit/form?"+
				$.param({
					action: 'unit_save',
				});
			$("#jqGrid_unit").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_unit',urlParam_unit,'edit');
			$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	//////////////////////////////////////start grid pager//////////////////////////////////////
	$("#jqGrid_unit").inlineNav('#jqGridPager_unit', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_unit
		},
		editParams: myEditOptions_edit_unit
	}).jqGrid('navButtonAdd', "#jqGridPager_unit", {
		id: "jqGridPagerDelete_unit",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid_unit").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'unit_save',
								regioncode: $('#regioncode').val(),
								idno: selrowData('#jqGrid_unit').idno,
							}
							$.post( "./unit/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid_unit", urlParam_unit);
							});
						}else{
							$("#jqGridPagerDelete_unit,#jqGridPagerRefresh_unit").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_unit", {
		id: "jqGridPagerRefresh_unit",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_unit", urlParam_unit);
		},
	});
	//////////////////////////////////////////end grid//////////////////////////////////////////
	
	///////////////////////handle searching, its radio button and toggle ///////////////////////
	// toogleSearch('#sbut1', '#searchForm', 'on');
	
	////////////////////////add field into param, refresh grid if needed////////////////////////
	addParamField('#jqGrid_divunit', true, urlParam_divs);
	addParamField('#jqGrid_unit', true, urlParam_unit);
	// addParamField('#jqGrid_unit', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus', 'computerid', 'ipaddress']);
	/////////////////////////////////////////unit ends/////////////////////////////////////////
	
	/////////////////////////////////////department starts/////////////////////////////////////
	///////////////////////////////////////jqGrid_divdept///////////////////////////////////////
	$("#jqGrid_divdept").jqGrid({
		datatype: "local",
		editurl: "./region/form",
		colModel: $("#jqGrid_divunit").jqGrid('getGridParam','colModel'),
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 150,
		rowNum: 30,
		pager: "#jqGridPager_divdept",
		onSelectRow:function(rowid, selected){
			if(!err_reroll_divs.error)$('#p_error').text('');   //hilangkan error msj after save
			
			urlParam_unit.filterVal[1]=selrowData("#jqGrid_divdept").regioncode;
			refreshGrid("#jqGrid_unitdept",urlParam_unit);
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_divdept_iladd').click();
			}else if($('#jqGrid_divdept').data('lastselrow') == 'none'){
				$("#jqGrid_divdept").setSelection($("#jqGrid_divdept").getDataIDs()[0]);
			}else{
				$("#jqGrid_divdept").setSelection($('#jqGrid_divdept').data('lastselrow'));
				$('#jqGrid_divdept tr#' + $('#jqGrid_divdept').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll_divs.error == true){
				err_reroll_divs.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_divdept_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			if($('#jqGrid_divdept').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid_divdept").setSelection($("#jqGrid_divdept").getDataIDs()[0]);
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	/////////////////////////////////////start grid pager/////////////////////////////////////
	$("#jqGrid_divdept").jqGrid('navGrid','#jqGridPager_divdept',{
		view:false, edit:false, add:false, del:false, search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_divdept",urlParam_divs);
		},
	});
	
	//////////////////////////////////////jqGrid_unitdept//////////////////////////////////////
	$("#jqGrid_unitdept").jqGrid({
		datatype: "local",
		editurl: "./unit/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 10, hidden: true, key: true },
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'Unit', name: 'sectorcode', width: 30, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap', canSearch: true, checked: true, editable: true, editrules: { required: true },
				editoptions: {
					style: "text-transform: uppercase"
				}
			},
			// { label: 'Section', name: 'regioncode', width: 40, classes: 'wrap', editable: true, editrules: { required: true, custom: true, custom_func: cust_rules },
			// 	formatter: showdetail, edittype: 'custom', hidden: true,
			// 	editoptions: {
			// 		custom_element:unitCustomEdit,
			// 		custom_value:galGridCustomValue
			// 	},
			// },
			{ label: 'Section', name: 'regioncode', width: 40, hidden: true },
			{ label: 'Add User', name: 'adduser', width: 40, hidden: true },
			{ label: 'Add Date', name: 'adddate', width: 50, hidden: true },
			{ label: 'Upd User', name: 'upduser', width: 40, hidden: true },
			{ label: 'Upd Date', name: 'upddate', width: 50, hidden: true },
			{ label: 'Computer ID', name: 'computerid', width: 40, hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true },
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', hidden: true, editable: true, edittype: "select", formatter: 'select',
				editoptions: { value: "ACTIVE:ACTIVE;DEACTIVE:DEACTIVE" },
				cellattr: function(rowid, cellvalue) {
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				},
			},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 150,
		rowNum: 30,
		pager: "#jqGridPager_unitdept",
		onSelectRow:function(rowid, selected){
			if(!err_reroll_unit.error)$('#p_error').text('');	//hilangkan error msj after save
			
			urlParam_dept.filterVal[1]=selrowData("#jqGrid_unitdept").regioncode;
			urlParam_dept.filterVal[2]=selrowData("#jqGrid_unitdept").sectorcode;
			refreshGrid("#jqGrid_dept",urlParam_dept);
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_unitdept_iladd').click();
			}else if($('#jqGrid_unitdept').data('lastselrow') == 'none'){
				$("#jqGrid_unitdept").setSelection($("#jqGrid_unitdept").getDataIDs()[0]);
			}else{
				$("#jqGrid_unitdept").setSelection($('#jqGrid_unitdept').data('lastselrow'));
				$('#jqGrid_unitdept tr#' + $('#jqGrid_unitdept').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false;	//reset
			if(err_reroll_unit.error == true){
				err_reroll_unit.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_unitdept_iledit").click();
			$('#p_error').text('');	//hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			if($('#jqGrid_unitdept').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid_unitdept").setSelection($("#jqGrid_unitdept").getDataIDs()[0]);
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
	});
	
	/////////////////////////////////////start grid pager/////////////////////////////////////
	$("#jqGrid_unitdept").jqGrid('navGrid','#jqGridPager_unitdept',{
		view:false, edit:false, add:false, del:false, search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_unitdept",urlParam_unit);
		},
	});
	
	////////////////////////////////////////jqGrid_dept////////////////////////////////////////
	/////////////////////////////////parameter for jqgrid url/////////////////////////////////
	var urlParam_dept={
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'sysdb.department',
		table_id: 'deptcode',
		filterCol: ['compcode','region','sector'],
		filterVal: ['session.compcode','',''],
		sort_idno: true,
	}
	
	/////////////////////////////////parameter for saving url/////////////////////////////////
	var saveParam_dept={
		action: 'save_table_default',
		url:  './department/form',
		field: '',
		oper: oper,
		table_name: 'sysdb.department',
		table_id: 'deptcode',
		saveip: 'true',
		checkduplicate: 'true'
	};
	
	$("#jqGrid_dept").jqGrid({
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
		pager: "#jqGridPager_dept",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager_dept td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid_dept").setSelection($("#jqGrid_dept").getDataIDs()[0]);
			}
			
			$('#'+$("#jqGrid_dept").jqGrid ('getGridParam', 'selrow')).focus();
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
	$("#jqGrid_dept").jqGrid('navGrid',"#jqGridPager_dept",{
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_dept",urlParam_dept);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_dept",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid_dept").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata_dept');
			}else{
				// saveFormdata("#jqGrid_dept","#dialogForm_dept","#formdata_dept",'del',saveParam_dept,urlParam_dept, null, {'deptcode':selRowId});
				saveFormdata("#jqGrid_dept","#dialogForm_dept","#formdata_dept",'del',saveParam_dept,urlParam_dept,{'idno':selrowData('#jqGrid_dept').idno});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_dept",{
		caption:"",cursor: "pointer",position: "first",
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid_dept").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid_dept","#dialogForm_dept","#formdata_dept",selRowId,'view', '');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_dept",{
		caption:"",cursor: "pointer",position: "first",
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid_dept").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid_dept","#dialogForm_dept","#formdata_dept",selRowId,'edit', '');
			recstatusDisable();
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager_dept",{
		caption:"",cursor: "pointer",position: "first",
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm_dept" ).dialog( "open" );
		},
	});
	////////////////////////////////////////////end grid////////////////////////////////////////////
	
	//////////////////////////handle searching, its radio button and toggle//////////////////////////
	// toogleSearch('#sbut1','#searchForm','on');
	
	//////////////////////////add field into param, refresh grid if needed//////////////////////////
	addParamField('#jqGrid_divdept',true,urlParam_divs);
	addParamField('#jqGrid_unitdept',true,urlParam_unit);
	addParamField('#jqGrid_dept',true,urlParam_dept);
	addParamField('#jqGrid_dept',false,saveParam_dept,['idno','compcode', 'computerid', 'ipaddress','adduser','adddate','upduser','upddate','recstatus']);
	/////////////////////////////////////////department ends/////////////////////////////////////////
	
	///////////////////////////////////////////cust_rules///////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Section':temp=$('#regioncode');break;	// jqGrid_unit
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'regioncode':field=['regioncode','description'];table="sysdb.region";case_='regioncode';break;	// jqGrid_unit
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;	// jqGrid_dept
			case 'costcode':field=['costcode','description'];table="finance.costcenter";case_='costcode';break;	// jqGrid_dept
		}
		var param={action:'input_check',url:'./util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('department',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
		
		return cellvalue;
	}
	
	function unformat_showdetail(cellvalue, options, rowObject){
		return $(rowObject).attr('title');
	}
	
	// jqGrid_unit
	function unitCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid_unit" optid="'+opt.id+'" id="'+opt.id+'" name="regioncode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
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
	
	$("#jqGrid_divs").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-30));
	$("#jqGrid_divunit").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-850));
	$("#jqGrid_unit").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-30));
	$("#jqGrid_divdept").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-850));
	$("#jqGrid_unitdept").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-850));
	$("#jqGrid_dept").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dept_c")[0].offsetWidth-$("#jqGrid_dept_c")[0].offsetLeft-30));
	
	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll=function(){
			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();
			
			this.data_array.forEach(function(item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
	}
});
		