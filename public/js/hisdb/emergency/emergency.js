
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

	  $("#regBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					$("#registerform").dialog("open");

            });

	var oper;
	$("#registerform")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#registerformdata');
						rdonly("#registerformdata");
						hideOne("#registerformdata");
						rdonly("#registerform");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#registerformdata');
						frozeOnEdit("#registerform");
						rdonly("#registerformdata");
						rdonly("#registerform");
						$('#registerformdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#registerformdata');
						$('#registerformdata :input[hideOne]').show();
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
				emptyFormdata(errorField, '#registerformdata');
				//$('.alert').detach();
				$('#registerformdata .alert').detach();
				$("#registerformdata a").off();
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
		fixPost: 'true',
		field: ['q.queueno', 'q.MRN', 'q.Episno','p.Name'],
		table_name: ['hisdb.queue AS q','hisdb.pat_mast AS p'],
		join_type: ['LEFT JOIN'],
		join_onCol: ['q.MRN'],
		join_onVal: ['p.MRN'],
		
	}

	///////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/race/form',
		field: '',
		oper: oper,
		table_name: 'hisdb.racecode',
		table_id: 'Code',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'q_compcode', width: 5, hidden: true },
			{
				label: 'MRN', name: 'q_MRN', width: 20, classes: 'wrap', canSearch: true, checked: true, editable: true,
				editrules: { required: true },
				editoptions: { maxlength: 2 },
			},
			{ label: 'Episode No', name: 'q_Episno', width: 20 ,classes: 'wrap' },
			{ label: 'Queue No', name: 'q_queueno', width: 20 ,classes: 'wrap' },
			{ label: 'Time', name: 'q_reg_time', width: 20 ,classes: 'wrap' },
			{ label: 'Name', name: 'p_Name', width: 20 ,classes: 'wrap' },
			// { label: 'Payer', name: 'q_', width: 20 ,classes: 'wrap' },
			{ label: 'Doctor', name: 'q_admdoctor', width: 20 ,classes: 'wrap' },
			{ label: 'Status', name: 'p_recstatus', width: 20 ,classes: 'wrap' },

		],
		autowidth: true,
		multiSort: true,
		// sortname: 'queueno',
		// sortorder: 'desc',
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

	////////////////////formatter status////////////////////////////////////////
	function formatterstatus(cellvalue, option, rowObject) {
		if (cellvalue == 'A') {
			return 'Active';
		}

		if (cellvalue == 'D') {
			return 'Deactive';
		}

	}

	////////////////////unformatter status////////////////////////////////////////
	function unformat(cellvalue, option, rowObject) {
		if (cellvalue == 'Active') {
			return 'Active';
		}

		if (cellvalue == 'Deactive') {
			return 'Deactive';
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

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','adduser','adddate','upduser','upddate','recstatus']);

	 $(function () {
	          $('#mydate').glDatePicker({
	          	showAlways: true,
	           });

	      });
});
