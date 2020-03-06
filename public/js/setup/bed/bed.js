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

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Bed Type':temp=$('#bedtype');break;
			case 'Ward':temp=$('#ward');break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			//case 'room':field=['room','description'];table="hisdb.episode";case_='room';break;
			case 'bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bed',options,param,case_,cellvalue);
		
		return cellvalue;
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
		url: '/bed/table',
		field: '',
		table_name: ['hisdb.bed AS b', 'hisdb.episode AS c'],
		table_id: 'b_compcode',
		sort_idno: true,
		fixPost: 'true',
		join_type:['LEFT JOIN'],
		join_onCol:['b.bednum'],
		join_onVal:['c.room'],
		filterCol:['b.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/bed/form",
		colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'Bed No', name: 'bednum', width: 3, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Bed Type', name: 'bedtype', width: 5, canSearch: true, editable: true, editrules: { required: true }, formatter: showdetail, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Bed Type', name: 'bedtype', width: 5, align: 'right' , classes: 'wrap', editable:true, canSearch: true,
			editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{  custom_element:bedTypeCustomEdit,
					custom_value:galGridCustomValue 	
					},
		},
			// { label: 'Status', name: 'occup', width: 5, canSearch: true, formatter: formatteroccup, unformat: unformatoccup, classes: 'wrap'},
			{ label: 'Status', name: 'occup', width: 5, classes: 'wrap', canSearch: true, editable: true, edittype:"select",formatter:'select', 
			editoptions:{
				value:"OCCUPIED:OCCUPIED;VACANT:VACANT;HOUSEKEEPING:HOUSEKEEPING;MAINTENANCE:MAINTENANCE;ISOLATED:ISOLATED"
			}},
			{ label: 'Room', name: 'room', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},

			// { label: 'Ward', name: 'ward', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Ward', name: 'ward', width: 5, align: 'right' , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:wardCustomEdit,
						custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Tel Ext', name: 'tel_ext', width: 3, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Statistic', name: 'statistic', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'MRN', name: 'mrn', width: 3, canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: 'Episode No', name: 'episno', width: 5, canSearch: true},
			{ label: 'Patient Name', name: 'name', width: 40, canSearch: true, classes: 'wrap'},
            { label: 'Record Status', name: 'recstatus', width: 5, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
			editoptions:{
				value:"A:ACTIVE;D:DEACTIVE"
			}},
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
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
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

			let editurl = "/bed/form?"+
				$.param({
					action: 'bed_save',
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
			// console.log(data);

			let editurl = "/bed/form?"+
				$.param({
					action: 'bed_save',
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
		add: true,
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
								action: 'bed_save',
								Code: $('#Code').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "/bed/form?"+$.param(param),{oper:'del'}, function( data ){
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
				{label:'Bedtype',name:'bedtype',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
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
				{label:'Ward',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
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

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
});
