
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
					message: ' '
				}
			}
		},
	};
    var Epistycode = $('#Epistycode').val();
	////////////////////////////////////start dialog///////////////////////////////////////
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
    
     $("#adjustment_but_currentPt").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					$("#adjustmentform").dialog("open");

     });


	var oper;
	$("#adjustmentform")
		.dialog({
			width: 6 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Adjustment");
						enableForm('#adjustmentform');
						hideOne('#adjustmentformdata');
						rdonly("#adjustmentform");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#adjustmentformdata');
						frozeOnEdit("#adjustmentform");
						rdonly("#adjustmentform");
						$('#adjustmentformdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#adjustmentformdata');
						$('#adjustmentformdata :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
						//dialog_dept.handler(errorField);
					}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#adjustmentformdata');
				//$('.alert').detach();
				$('#adjustmentformdata .alert').detach();
				$("#adjustmentformdata a").off();
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
		table_name: 'hisdb.queue',
		// table_id: 'areacode',
		sort_idno: true,
		filterCol:['epistycode'],
		filterVal:[ $('#Epistycode').val()]

	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/currentPt/form',
		field: '',
		oper: oper,
		table_name: 'hisdb.queue',
		// table_id: 'areacode',
		// saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'MRN', name: 'mrn', width: 15, classes: 'wrap', canSearch: true, checked: true, },
			{ label: 'Episode No', name: 'episno', width: 10, classes: 'wrap'},
			{ label: 'Name', name: 'name', width: 30, classes: 'wrap' },
			{ label: 'New IC', name: 'newic', width: 20, classes: 'wrap' },
			{ label: 'Birth Date', name: 'dob', width: 20, classes: 'wrap' },
			{ label: 'Sex', name: 'sex', width: 10, classes: 'wrap' },
			{ label: 'Epistycode', name: 'epistycode',hidden: true },
			{ label: 'Handphone No', name: 'telhp', width: 20, classes: 'wrap' },
			{ label: 'Home No', name: 'telh', width: 20, classes: 'wrap' },
			{ label: 'idno', name: 'idno', hidden: true },
			
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			// $('#biodata_but_emergency').data('bio_from_grid',selrowData("#jqGrid"));
			// $('#episode_but_currentPt').data('bio_from_grid',selrowData("#jqGrid"));
		},
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

	////////////////////////////formatter//////////////////////////////////////////////////////////
	function formatter(cellvalue, options, rowObject) {
		if (cellvalue == 'A') {
			return "Active";
		}
		if (cellvalue == 'D') {
			return "Deactive";
		}
	}

	function unformat(cellvalue, options) {
		if (cellvalue == 'Active') {
			return "Active";
		}
		if (cellvalue == 'Deactive') {
			return "Deactive";
		}
	}
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
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null,  { 'idno': selrowData('#jqGrid').idno });
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
    var urlParam2 = {
		action: 'get_table_default',
		url: '/util/get_table_default',
		field: '',
		table_name: 'hisdb.pat_mast',
		// table_id: 'areacode',
		sort_idno: true,
	}

	$("#detail").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Home Address', name: 'Address1', width: 100, classes: 'wrap', canSearch: true, checked: true, },
			
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		height: 124,
		rowNum: 30,
		width: 700,
		pager: "#jqGridPager2",
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
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	toogleSearch('#sbut2','#searchForm2','off');
	populateSelect('#detail','#searchForm2');
	searchClick('#detail','#searchForm2',urlParam2);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
	$("#pg_jqGridPager2 table").hide();
});
