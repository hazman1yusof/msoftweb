
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		language: {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					//element : $('#'+errorField[0]),
					message: ' '
				}
			}
		},
	};
	//////////////////////////////////////////////////////////////

	var butt1 = [{
		text: "Save", click: function () {
			if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", oper, saveParam, urlParam);
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var butt2 = [{
		text: "Close", click: function () {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				//toggleFormData('#jqGrid','#formdata');
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						rdonly("#formdata");
						hideOne("#formdata");
						rdonly("#dialogForm");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						rdonly("#formdata");
						rdonly("#dialogForm");
						$('#formdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$('#formdata :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if(oper!='view'){
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
					dialog_regioncode.on();
				}
				if(oper!='add'){
					dialog_regioncode.check(errorField);
				}
				
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				//$('.alert').detach();
				$('#formdata .alert').detach();
				$("#formdata a").off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", butt1);
				}
			},
			buttons: butt1,
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: '/util/get_table_default',
		field: '',
		table_name: 'sysdb.sector',
		table_id: 'sectorcode',
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/sector/form',
		field: '',
		oper: oper,
		table_name: 'sysdb.sector',
		table_id: 'sectorcode',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 5, hidden: true },
			{ label: 'Unit', name: 'sectorcode', width: 20, classes: 'wrap', canSearch: true},
			{ label: 'Description', name: 'description', width: 80, classes: 'wrap', canSearch: true,checked:true,},
			{ label: 'Section', name: 'regioncode', width: 50, classes: 'wrap'},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
			},
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
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},

	});

	
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
				//return emptyFormdata('#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'idno': selrowData('#jqGrid').idno });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
			recstatusDisable();
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	var dialog_regioncode = new ordialog(
		'regioncode','sysdb.region','#regioncode',errorField,
		{	colModel:[
				{label:'Region Code',name:'regioncode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
				urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
				},
				ondblClickRow: function () {
					$('#recstatus').focus();
				},
				gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Region Code",
			open: function(){
				dialog_regioncode.urlParam.filterCol=['compcode','recstatus'],
				dialog_regioncode.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_regioncode.makedialog(true);
	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','adduser','adddate','upduser','upddate','recstatus']);
});
