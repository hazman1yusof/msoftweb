
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
            	if(!selRowId){
            		alert('Please select patient');
            	}else{
					$("#adjustmentform").dialog("open");
            	}
     });

    /////////////////start pasal episode//////////////////

	$('#episode_but_currentPt').click(function(){
		var data = $(this).data('bio_from_grid');
		var form = '#episode_form';

		if(data==undefined){
			alert('no patient selected');
			return false;
		}

		var param={
            action:'get_value_default',
            field:"*",
            table_name:'hisdb.episode',
            table_id:'_none',
            filterCol:['compcode','mrn','episno'],
            filterVal:['session.company',data.mrn,data.episno]
        };

        $.get( "/util/get_value_default?"+$.param(param), function( data ) {

        },'json').done(function(data) {

            if(data.rows.length > 0){

            	fail = false;
				if(data.rows[0].epistycode!='OP'){
					alert('This Patient was Registered as '+data.rows[0].epistycode);
					fail = true;
				}

                if(!fail){ 
                	$.each(data.rows[0], function( index, value ) {
	                    var input=$(form+" [name='"+index+"']");

	                    if(input.is("[type=radio]")){
	                        $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
	                    }else{
	                        input.val(value);
	                    }
	                    desc_show_epi.write_desc();
	                });
			        $('#editEpisode').modal({backdrop: "static"});
			        $('#editEpisode').modal('show');
				}
               

            }else{
                alert('MRN not found')
            }

        }).error(function(data){

        }); 
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
			{ label: 'MRN', name: 'mrn', width: 15, classes: 'wrap', canSearch: true },
			{ label: 'Episode No', name: 'episno', width: 10, classes: 'wrap'},
			{ label: 'Name', name: 'name', width: 30, classes: 'wrap', canSearch: true },
			{ label: 'New IC', name: 'newic', width: 20, classes: 'wrap', canSearch: true },
			{ label: 'Birth Date', name: 'dob', width: 20, classes: 'wrap', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
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

			$('#episode_but_currentPt').data('bio_from_grid',selrowData("#jqGrid"));
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
	 function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				// refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			// refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['selected']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
			searchClick2('#jqGrid','#searchForm',urlParam);
		});
	}
	$('#Scol').on('change', whenchangetodate);
	// $('#Status').on('change', searchChange);
	function whenchangetodate() {
		if($('#Scol').val()=='dob'){
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
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
	populateSelect('#jqGrid','#searchForm');

	// toogleSearch('#sbut1', '#searchForm', 'on');
	// populateSelect('#jqGrid', '#searchForm');
	// searchClick('#jqGrid', '#searchForm', urlParam);

	// toogleSearch('#sbut2','#searchForm2','off');
	// populateSelect('#detail','#searchForm2');
	// searchClick('#detail','#searchForm2',urlParam2);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
	$("#pg_jqGridPager2 table").hide();
});
