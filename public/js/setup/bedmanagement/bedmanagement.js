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

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};
	
	var fdl = new faster_detail_load();
	$("#jqGrid3_c").hide();

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Bed Type':temp=$('#bedtype');break;
			case 'Ward':temp=$('#ward');break;
			case 'Status':temp=$('#occup');break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bedmanagement',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	function occupCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="occup" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function bedTypeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="bedtype" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function wardCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="ward" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table',
		url: '/bedmanagement/table',
		field: '',
		table_name: ['hisdb.bed as b'],
		table_id: 'b_compcode',
		sort_idno: true,
		filterCol:['b.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/bedmanagement/form",
		colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'Bed No', name: 'bednum', width: 10, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Bed Type', name: 'bedtype', width: 5, canSearch: true, editable: true, editrules: { required: true }, formatter: showdetail, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Bed Type', name: 'bedtype', width: 15, classes: 'wrap', editable:true, canSearch: true,
			editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{  custom_element:bedTypeCustomEdit,
					custom_value:galGridCustomValue 	
					},
			},
			// { label: 'Status', name: 'occup', width: 5, canSearch: true, formatter: formatteroccup, unformat: unformatoccup, classes: 'wrap'},
			{ label: 'Status', name: 'occup', width: 22, classes: 'wrap', canSearch: true, editable: true,formatter:occup,unformat:occup_unformat, editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
						{  custom_element:occupCustomEdit,
						custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Room', name: 'room', width: 10, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Ward', name: 'ward', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Ward', name: 'ward', width: 15 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:wardCustomEdit,
						custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Tel Ext', name: 'tel_ext', width: 8, canSearch: true, checked: true, editable: true, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Tel Ext', name: 'tel_ext', width: 10, canSearch: true, editable: true, edittype:"select", editrules: { required: true }, editoptions: {value:'TRUE:TRUE;FALSE:FALSE' },formatter:truefalseFormatter,unformat:truefalseUNFormatter},
			{ label: 'Statistic', name: 'statistic', width: 15, canSearch: true, editable: true, edittype:"select", editrules: { required: true }, editoptions: {value:'TRUE:TRUE;FALSE:FALSE' },formatter:truefalseFormatter,unformat:truefalseUNFormatter},
			{ label: 'MRN', name: 'mrn', width: 8, canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: ' ', name: 'episno', width: 5, canSearch: true},
			{ label: 'Patient Name', name: 'name', width: 40, canSearch: true, classes: 'wrap'},
            { label: 'Record Status', name: 'recstatus', width: 15, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
			editoptions:{
				value:"A:ACTIVE;D:DEACTIVE"},
				cellattr: function(rowid, cellvalue)
						{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'lastuser', name: 'lastuser', width: 90, hidden:true},
			{ label: 'lastupdate', name: 'lastupdate', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
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
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			populate_formbedm(selrowData("#jqGrid"));

			if (rowid != null) {
				rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid3', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table, #jqGrid3_c").hide();

				if (rowData['mrn'] != 000000) {
					refreshGrid('#jqGrid3', urlParam2);
					$("#pg_jqGridPager3 table, #jqGrid3_c").show();
					$("#jqGridPagerDelete").hide();
					$("#jqGrid_iledit").hide();
				}
				else if (rowData['mrn'] == 000000) {
					refreshGrid('#jqGrid3', urlParam2);
					$("#jqGridPagerDelete").show();
					$("#jqGrid_iledit").show();
				}

			}

		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			if(selrowData("#jqGrid").recstatus == "D")  /////if recstatus = D, nak whole row ni berubah color /////////////////////////////////////////////////
			{
				return rowcolor();
				
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			if (rowid != null) {
				rowData = $('#jqGrid').jqGrid('getRowData', rowid);

				if (rowData['mrn'] != 000000) {
					$("#jqGridPagerDelete").hide();
					$("#jqGrid_iledit").hide();
				}
				else if (rowData['mrn'] == 000000) {
					refreshGrid('#jqGrid3', urlParam2);
					$("#jqGrid_iledit").click();
					$("#jqGridPagerDelete").show();
					$("#jqGrid_iledit").show();
				}

			}
			// $("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
			empty_formbedm();
		},
	});

	function padzero(cellvalue, options, rowObject){
		let padzero = 6, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	function occup(cellvalue, options, rowObject){
		switch(cellvalue.trim()){
			case 'OCCUPIED': return '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED';break;
			case 'VACANT': return '<i class="fa fa-ban" aria-hidden="true"></i> VACANT';break;
			case 'HOUSEKEEPING': return '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING';break;
			case 'MAINTENANCE': return '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE';break;
			case 'ISOLATED': return '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED';break;
			default: return cellvalue;break;
		}
	}

	function occup_unformat(cellvalue, options, rowObject){
		switch(cellvalue){
			case '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED': return 'OCCUPIED';break;
			case '<i class="fa fa-ban" aria-hidden="true"></i> VACANT': return 'VACANT';break;
			case '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING': return 'HOUSEKEEPING';break;
			case '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE': return 'MAINTENANCE';break;
			case '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED': return 'ISOLATED';break;
			default: return cellvalue;break;
		}
	}

	statistics();
	function statistics(){
		$.get( "/bedmanagement/statistic", function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$('#stat_vacant').text(data.vacant);
				$('#stat_occupied').text(data.occupied);
				$('#stat_housekeeping').text(data.housekeeping);
				$('#stat_maintenance').text(data.maintenance);
				$('#stat_isolated').text(data.isolated);
			}
		});

	}

	////////////////////formatter status////////////////////////////////////////
	function rowcolor(cellvalue, option, rowObject) {
		if (cellvalue == 'A') {
			return 'Active';
		}else if (cellvalue == 'D') {
			return 'Deactive' ? 'class="alert alert-danger"': '';
		}
	}


	// ////////////////////formatter status////////////////////////////////////////
	// function formatteroccup(cellvalue, option, rowObject) {
	// 	if (cellvalue == '1') {
	// 		return 'OCCUPIED';
	// 	}else if (cellvalue == '0') {
	// 		return 'VACANT';
	// 	}else{
	// 		return 'VACANT';
	// 	}
	// }

	// ////////////////////unformatter status////////////////////////////////////////
	// function unformatoccup(cellvalue, option, rowObject) {
	// 	if (cellvalue == 'OCCUPIED') {
	// 		return '1';
	// 	}else if (cellvalue == 'VACANT') {
	// 		return '0';
	// 	}else{
	// 		return '0';
	// 	}
	// }

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);

			let editurl = "/bedmanagement/form?"+
				$.param({
					action: 'bedmanagement_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			$("input[name='bednum']").attr('disabled','disabled');
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam2,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);

			let editurl = "/bedmanagement/form?"+
				$.param({
					action: 'bedmanagement_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};


	$("#jqGrid").inlineNav('#jqGridPager', {
		add: false,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
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
								action: 'bedmanagement_save',
								Code: $('#Code').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "/bedmanagement/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid", urlParam);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	var dialog_bedtype = new ordialog(
		'bedtype','hisdb.bedtype',"#jqGrid input[name='bedtype']",errorField,
		{	colModel:[
				{label:'Bedtype',name:'bedtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
					},
			ondblClickRow:function(){
				$('#occup').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#occup').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Bed Type",
			open: function(){
				dialog_bedtype.urlParam.filterCol = ['recstatus','compcode'];
				dialog_bedtype.urlParam.filterVal = ['A', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_bedtype.makedialog();

	var dialog_ward = new ordialog(
		'ward','sysdb.department',"#jqGrid input[name='ward']",errorField,
		{	colModel:[
				{label:'Ward',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','warddept'],
				filterVal:['A', 'session.compcode','1']
					},
			ondblClickRow:function(){
				$('#tel_ext').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#tel_ext').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Ward Type",
			open: function(){
				dialog_ward.urlParam.filterCol = ['recstatus','compcode','warddept'];
				dialog_ward.urlParam.filterVal = ['A', 'session.compcode','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_ward.makedialog();

	var dialog_occup = new ordialog(
		'occup','sysdb.department',"#jqGrid input[name='occup']",errorField,
		{	colModel:
			[
				{label:'Bed Status',name:'bedcode',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
			],
			urlParam: {
				url:'./sysparam_bed_status',
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_occup.textfield).val(selrowData("#"+dialog_occup.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#room').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bed Stattus",
			open: function(){
				dialog_occup.urlParam.filterCol = ['recstatus','compcode'];
				dialog_occup.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_occup.makedialog();

	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		table_name: 'hisdb.bedalloc',
		table_id: 'idno',
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

	$("#jqGrid3").jqGrid({
		datatype: "local",
		editurl: "/mma/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Start Date', name: 'asdate', width: 5, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
								changeYear: true,
						});
					}
				}
			},
			{ label: 'Start Time', name: 'astime', width: 5, frozen:true, classes: 'wrap', editable:false},
            { label: 'Bed No', name: 'bednum', width: 7, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Room', name: 'room', width: 10, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Bed Type', name: 'bedtype', width: 15, classes: 'wrap', editable:true, canSearch: true},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid3_iladd').click();}
			else{
				$('#jqGrid3').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		gridComplete: function(){

			// fdl.set_array().reset();
			// if(!hide_init){
			// 	hide_init=1;
			// 	hideatdialogForm_jqGrid3(false);
			// }
		}
	});
	// var hide_init=0;

	//////////////////////////////////////////myEditOptions2/////////////////////////////////////////////

	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").hide();

			// dialog_dtliptax.on();
			// dialog_dtloptax.on();

			dialog_occup.on();

			// unsaved = false;
			// mycurrency2.array.length = 0;
			// Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='feesconsult']","#jqGrid3 input[name='feessurgeon']","#jqGrid3 input[name='feesanaes']"]);

			// mycurrency2.formatOnBlur();//make field to currency on leave cursor

	//      	$("input[name='dtl_maxlimit']").keydown(function(e) {//when click tab at document, auto save
			// 	var code = e.keyCode || e.which;
			// 	if (code == '9')$('#jqGrid2_ilsave').click();
			// })
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3Delete,#jqGridPager3Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
			let editurl = "/mma/form?"+
				$.param({
					action: 'mma_save',
					oper: 'add',
					// chgcode: selrowData('#jqGrid').cm_chgcode,//$('#cm_chgcode').val(),
					// uom: selrowData('#jqGrid').cm_uom//$('#cm_uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			// hideatdialogForm_jqGrid3(false);
		}
	};

	//////////////////////////////////////////pager jqgrid3/////////////////////////////////////////////

	$("#jqGrid3").inlineNav('#jqGridPager3',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions2
		},
		editParams: myEditOptions2
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if(result == true){
							param={
								action: 'bedmanagement_save',
								idno: selrowData('#jqGrid3').idno,

							}
							$.post( "/bedmanagement/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGrid3",urlParam2);
							});
						}else{
							$("#jqGridPager3EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		// onClickButton: function(){
		// 	mycurrency2.array.length = 0;
		// 	var ids = $("#jqGrid3").jqGrid('getDataIDs');
		// 	for (var i = 0; i < ids.length; i++) {

		// 		$("#jqGrid3").jqGrid('editRow',ids[i]);

		// 		Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_feesconsult","#"+ids[i]+"_feessurgeon","#"+ids[i]+"_feesanaes"]);
		// 	}
		// 	mycurrency2.formatOnBlur();
		// 	// onall_editfunc();
		// 	// hideatdialogForm_jqGrid3(true,'saveallrow');
		// },
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid3").jqGrid('getDataIDs');

			var jqgrid3_data = [];
			mycurrency2.formatOff();
			// for (var i = 0; i < ids.length; i++) {

			// 	var data = $('#jqGrid3').jqGrid('getRowData',ids[i]);
			// 	var obj = 
			// 	{
			// 		'idno' : data.idno,
			// 		'effectdate' : $("#jqGrid3 input#"+ids[i]+"_effectdate").val(),
			// 		'mmacode' : $("#jqGrid3 input#"+ids[i]+"_mmacode").val(),
			// 		'version' : $("#jqGrid3 input#"+ids[i]+"_version").val(),
			// 		'mmaconsult' : $("#jqGrid3 input#"+ids[i]+"_mmaconsult").val(),
			// 		'mmasurgeon' : $("#jqGrid3 input#"+ids[i]+"_mmasurgeon").val(),
			// 		'mmaanaes' : $("#jqGrid3 input#"+ids[i]+"_mmaanaes").val(),
			// 		'feesconsult' : $("#jqGrid3 input#"+ids[i]+"_feesconsult").val(),
			// 		'feessurgeon' : $("#jqGrid3 input#"+ids[i]+"_feessurgeon").val(),
			// 		'feesanaes' : $("#jqGrid3 input#"+ids[i]+"_feesanaes").val(),
			// 	}

			// 	jqgrid3_data.push(obj);
			// }

			var param={
				action: 'bedmanagement_save',
				_token: $("#_token").val()
			}

			$.post( "/bedmanagement/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid3_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				// hideatdialogForm_jqGrid3(false);
				refreshGrid("#jqGrid3",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			// hideatdialogForm_jqGrid3(false);
			refreshGrid("#jqGrid3",urlParam2);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		id: "jqGridPager3Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid3", urlParam2);
		},
	});

	//////////////////////////////////////end grid 2/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam, ['mrn', 'episno', 'name']);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
});


function populate_formbedm(obj){

	//panel header
	$('#name_show').text(obj.name_show);
	$('#bednum_show').text(obj.bednum_show);	
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();
}

function empty_formbedm(){

	$('#name_show').text('');
	$('#bednum_show').text('');
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").hide();
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	// disableForm('#formMMA');
	// emptyFormdata(errorField_MMA,'#formMMA')
}
