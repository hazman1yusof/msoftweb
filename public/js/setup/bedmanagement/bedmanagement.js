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
	$("#jqGrid_trf_c").hide();

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Bed Type':temp=$('#b_bedtype');break;
			case 'Ward':temp=$('#b_ward');break;
			case 'Status':temp=$('#b_occup');break;
			case 'Statistic':temp=$('#b_statistic');break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'b_bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'b_ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
			case 'q_admdoctor': field = ['doctorcode', 'doctorname']; table = "hisdb.doctor";case_='doccode';break;
			case 'ba_ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bedmanagement',options,param,case_,cellvalue);
		
		if(cellvalue==null)return "";
		return cellvalue;
	}

	function occupCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="b_occup" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function bedTypeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="b_bedtype" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function wardCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="b_ward" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function statCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="b_statistic" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		fixPost:'true',
		table_name: ['hisdb.bed as b', 'hisdb.queue as q'],
		join_type:['LEFT JOIN'],
		join_onCol:['b.bednum'],
		join_onVal:['q.bed'],
		join_filterCol:[['q.deptcode in =','q.compcode on =']],
		join_filterVal:[['ALL','b.compcode']],
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
            { label: 'compcode', name: 'b_compcode', hidden: true },
            { label: 'Bed No', name: 'b_bednum', width: 10, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Bed Type', name: 'b_bedtype', width: 5, canSearch: true, editable: true, editrules: { required: true }, formatter: showdetail, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Bed Type', name: 'b_bedtype', width: 15, classes: 'wrap', editable:true, canSearch: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{	custom_element:bedTypeCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			// { label: 'Status', name: 'b_occup', width: 5, canSearch: true, formatter: formatteroccup, unformat: unformatoccup, classes: 'wrap'},
			{ label: 'Status', name: 'b_occup', width: 22, classes: 'wrap', canSearch: true, editable: true,formatter:occup,unformat:occup_unformat, editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ 	custom_element:occupCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Room', name: 'b_room', width: 10, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Ward', name: 'b_ward', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Ward', name: 'b_ward', width: 15 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{ 	custom_element:wardCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Tel Ext', name: 'b_tel_ext', width: 8, canSearch: true, checked: true, editable: true, editoptions: {style: "text-transform: uppercase" }},
			//{ label: 'Statistic', name: 'b_statistic', width: 15, canSearch: true, editable: true, edittype:"select", editrules: { required: true }, editoptions: {value:'TRUE:TRUE;FALSE:FALSE' },formatter:truefalseFormatter,unformat:truefalseUNFormatter},
			{ label: 'Statistic', name: 'b_statistic', width: 15, classes: 'wrap', canSearch: true, editable: true,editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ 	custom_element:statCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'MRN', name: 'q_mrn', width: 8, canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: ' ', name: 'q_episno', width: 5},
			{ label: 'Patient Name', name: 'q_name', width: 40, canSearch: true, classes: 'wrap'},
			{ label: 'Doctor Code', name: 'q_admdoctor', width: 15, canSearch: true, formatter: showdetail},
            { label: 'Record Status', name: 'b_recstatus', width: 15, classes: 'wrap', hidden:true, editable: true, edittype:"select",formatter:'select', 
				editoptions:{
				value:"A:ACTIVE;D:DEACTIVE"},
				cellattr: function(rowid, cellvalue)
						{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
			{ label: 'id', name: 'b_idno', width:10, hidden: true, key:true},
			{ label: 'adduser', name: 'b_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'b_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'b_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'b_upddate', width: 90, hidden: true },
			{ label: 'lastuser', name: 'b_lastuser', width: 90, hidden:true},
			{ label: 'lastupdate', name: 'b_lastupdate', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'b_lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'b_lastipaddress', width: 90, hidden:true},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'b_idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350, 
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){

			if (rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid_trf', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table, #jqGrid_trf_c").hide();

				if(rowData['q_mrn'] != '') {
					urlParam2.filterVal[0] = selrowData('#jqGrid').q_mrn;
					refreshGrid('#jqGrid_trf', urlParam2);
					$("#pg_jqGridPager3 table, #jqGrid_trf_c").show();
					$("#jqGridPagerDelete").hide();
					$("#jqGrid_iledit").hide();
				}else if (rowData['q_mrn'] == '') {
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
			if(selrowData("#jqGrid").b_recstatus == "D")  /////if recstatus = D, nak whole row ni berubah color /////////////////////////////////////////////////
			{
				return rowcolor();
				
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			if (rowid != null) {
				rowData = $('#jqGrid').jqGrid('getRowData', rowid);

				if (rowData['b_mrn'] != 000000) {
					$("#jqGridPagerDelete").hide();
					$("#jqGrid_iledit").hide();
				}
				else if (rowData['b_mrn'] == 000000) {
					refreshGrid('#jqGrid_trf', urlParam2);
					$("#jqGrid_iledit").click();
					$("#jqGridPagerDelete").show();
					$("#jqGrid_iledit").show();
				}
			}
			// $("#jqGrid_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			fdl.set_array().reset();
			statistics();
			empty_form_trf();
		},
	});

	function padzero(cellvalue, options, rowObject){
		if(cellvalue == null){
			return "";
		}
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
			case 'VACANT': return '<img src="img/bedonly.png" height="10" width="14"></img> VACANT';break;
			case 'HOUSEKEEPING': return '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING';break;
			case 'MAINTENANCE': return '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE';break;
			case 'ISOLATED': return '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED';break;
			case 'RESERVE': return '<i class="fa fa-ban" aria-hidden="true"></i> RESERVE';break;
			default: return cellvalue;break;
		}
	}

	function occup_unformat(cellvalue, options, rowObject){
		switch(cellvalue){
			case '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED': return 'OCCUPIED';break;
			case '<img src="img/bedonly.png" height="10" width="14"></img> VACANT': return 'VACANT';break;
			case '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING': return 'HOUSEKEEPING';break;
			case '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE': return 'MAINTENANCE';break;
			case '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED': return 'ISOLATED';break;
			case '<i class="fa fa-ban" aria-hidden="true"></i> RESERVE': return 'RESERVE';break;			
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
				$('#stat_active').text(data.active);
				$('#stat_deactive').text(data.deactive);
				$('#stat_reserve').text(data.reserve);
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
			dialog_stat.on();
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
			dialog_stat.on();
			$("input[name='b_bednum']").attr('disabled','disabled');
			$("select[name='b_recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
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
		'b_bedtype','hisdb.bedtype',"#jqGrid input[name='b_bedtype']",errorField,
		{	colModel:[
				{label:'Bedtype',name:'bedtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
					},
			ondblClickRow:function(){
				$('#b_occup').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#b_occup').focus();
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
		'b_ward','sysdb.department',"#jqGrid input[name='b_ward']",errorField,
		{	colModel:[
				{label:'Ward',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','warddept'],
				filterVal:['A', 'session.compcode','1']
					},
			ondblClickRow:function(){
				$('#b_tel_ext').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#b_tel_ext').focus();
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
		'b_occup','sysdb.department',"#jqGrid input[name='b_occup']",errorField,
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
					$('#b_room').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bed Status",
			open: function(){
				dialog_occup.urlParam.filterCol = ['recstatus','compcode'];
				dialog_occup.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_occup.makedialog();

	var dialog_stat = new ordialog(
		'b_statistic','hisdb.bed',"#jqGrid input[name='b_statistic']",errorField,
		{	colModel:
			[
				{label:'Statistic',name:'stat',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
				//{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
			],
			urlParam: {
				url:'./sysparam_stat',
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_stat.textfield).val(selrowData("#"+dialog_stat.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#b_mrn').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Statistic",
			open: function(){
				dialog_stat.urlParam.filterCol = ['recstatus','compcode'];
				dialog_stat.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:5/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_stat.makedialog();	

	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		fixPost:'true',
		table_name: ['hisdb.bedalloc AS ba','hisdb.bed AS b'],
		join_type:['LEFT JOIN'],
		join_onCol:['b.bednum'],
		join_onVal:['ba.bednum'],
		join_filterCol:[['b.compcode on =']],
		join_filterVal:[['ba.compcode']],
		filterCol:['ba.mrn','ba.compcode'],
		filterVal:['','session.compcode'],
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu

	////////////////////////////////////////////////jqgrid_trf//////////////////////////////////////////////

	$("#jqGrid_trf").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'ba_compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Start Date', name: 'ba_asdate', width: 5, classes: 'wrap'},
			{ label: 'Start Time', name: 'ba_astime', width: 5, classes: 'wrap'},
            { label: 'Bed No', name: 'ba_bednum', width: 7},
			{ label: 'Room', name: 'ba_room', width: 10},
			{ label: 'Bed Type', name: 'b_bedtype', width: 15, classes: 'wrap', formatter: showdetail},
			{ label: 'Ward', name: 'ba_ward', width: 15, classes: 'wrap', formatter: showdetail},
			{ label: 'idno', name: 'ba_idno', width: 20, hidden:true},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'ba_idno',
		sortorder: 'desc',
		pager: "#jqGridPager3",
		onSelectRow:function(rowid, selected){
			populate_form_trf(selrowData("#jqGrid_trf"));
		},
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid_trf_iladd').click();}
			else{
				$('#jqGrid_trf').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		gridComplete: function(){

			// fdl.set_array().reset();
			// if(!hide_init){
			// 	hide_init=1;
			// 	hideatdialogForm_jqGrid_trf(false);
			// }
		}
	});

	//////////////////////////////////////end grid 2/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam,);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
	addParamField('#jqGrid_trf', false, urlParam2);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);

	$("#jqGrid_trf_panel").on("show.bs.collapse", function(){
		$("#jqGrid_trf").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trf_c")[0].offsetWidth-$("#jqGrid_trf_c")[0].offsetLeft-28));
	});

	/////////////////////////////transer/////////////////////////

	var dialog_bed_trf = new ordialog(
		'trf_bednum','hisdb.bed','#trf_bednum',errorField,
		{	colModel:[
	            { label: 'Bed No', name: 'bednum',classes:'pointer', width: 7,canSearch:true,checked:true,or_search:true},
	            { label: 'Ward', name: 'ward',classes:'pointer', width: 7,canSearch:true,or_search:true},
				{ label: 'Room', name: 'room',classes:'pointer', width: 10,canSearch:true,or_search:true},
				{ label: 'Bed Type', name: 'bedtype',classes:'pointer', width: 15, classes: 'wrap',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','occup'],
				filterVal:['session.compcode','A','<>.OCCUPIED']
			},
			ondblClickRow:function(){
				$(dialog_bed_trf.textfield).parent().next().html('');
				let data=selrowData('#'+dialog_bed_trf.gridname);
				$('#trf_room').val(data.room);
				$('#trf_ward').val(data.ward);
				$('#trf_bedtype').val(data.bedtype);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_purdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Type",
			open: function(){
				dialog_bed_trf.urlParam.filterCol=['compcode','recstatus','occup'];
				dialog_bed_trf.urlParam.filterVal=['session.compcode','A','<>.OCCUPIED'];
			}
		},'urlParam','radio','tab'
	);
	dialog_bed_trf.makedialog();

	var dialog_lodger_trf = new ordialog(
		'chgcode','hisdb.chgmast',"#trf_lodger",errorField,
		{	colModel:[
				{label:'Chargecode',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
					},
			ondblClickRow:function(){

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#tel_ext').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Chargecode",
			open: function(){
				dialog_lodger_trf.urlParam.filterCol = ['recstatus','compcode'];
				dialog_lodger_trf.urlParam.filterVal = ['A', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_lodger_trf.makedialog();

	button_state_trf('empty');
	function button_state_trf(state){
		switch(state){
			case 'empty':
				disableForm('#form_trf');
				$("#toggle_trf").removeAttr('data-toggle');
				$('#save_trf').data('oper','add');
				$('#save_trf,#cancel_trf,#edit_trf').attr('disabled',true);
				break;
			case 'edit':
				disableForm('#form_trf');
				$("#toggle_trf").attr('data-toggle','collapse');
				$('#save_trf').data('oper','edit');
				$("#edit_trf").attr('disabled',false);
				$('#save_trf,#cancel_trf').attr('disabled',true);
				dialog_lodger_trf.off();
				dialog_bed_trf.off();
				break;
			case 'wait':
				enableForm('#form_trf',['ba_asdate','ba_astime','ba_bednum','ba_ward','ba_room','b_bedtype','trf_aedate','trf_aetime','trf_room','trf_ward','trf_bedtype']);
				$("#toggle_trf").attr('data-toggle','collapse');
				$("#save_trf,#cancel_trf").attr('disabled',false);
				$('#edit_trf').attr('disabled',true);
				dialog_lodger_trf.on();
				dialog_bed_trf.on();
				break;
		}
	}

	$("#edit_trf").click(function(){
		button_state_trf('wait');
	});

	$("#save_trf").click(function(){
		disableForm('#form_trf');
		if( $('#form_trf').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_trf(function(){
				$("#cancel_trf").click();
				refreshGrid('#jqGrid_trf', urlParam2);
			});
		}else{
			enableForm('#form_trf');
		}
	});

	$("#cancel_trf").click(function(){
		button_state_trf($('#save_trf').data('oper'));
	});

	function populate_form_trf(rowdata){
		$('#name_show').text(selrowData("#jqGrid").b_name);
		$('#bednum_show').text(selrowData("#jqGrid").b_bednum);	
		autoinsert_rowdata("#form_trf",rowdata);
		$('input[name=trf_aedate]').val(moment().format('YYYY-MM-DD'));
		$('input[name=trf_aetime]').val(moment().format('HH:mm:ss'));
		button_state_trf('edit');
	}

	function empty_form_trf(){
		$('#name_show').text('');
		$('#bednum_show').text('');
		button_state_trf('empty');
	}

	function autoinsert_rowdata(form,rowData){
		$.each(rowData, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if(input.is("[type=checkbox]")){
				if(value==1){
					$(form+" [name='"+index+"']").prop('checked', true);
				}
			}else{
				input.val(value);
			}
		});
	}

	function saveForm_trf(){
		
	}

	function saveForm_trf(callback){
		var saveParam={
	        oper:'transfer_form'
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	name: selrowData("#jqGrid").q_name,
	    	mrn : selrowData("#jqGrid").q_mrn,
			episno : selrowData("#jqGrid").q_episno,
			b_idno : selrowData("#jqGrid").b_idno,
	    };

	    var values = $("#form_trf").serializeArray();


	    $.post( "/bedmanagement/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}
});