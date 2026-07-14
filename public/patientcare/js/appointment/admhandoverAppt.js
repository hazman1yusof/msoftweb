$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

///////////////////////////////////parameter for jqGridNoteAdmHandover url///////////////////////////////////
var urlParam_NoteAdmHandover = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','ADMISSION HANDOVER'],
}

$(document).ready(function (){
	
	$('#tab_admHandoverAppt').on('show.bs.collapse', function (){
		return check_if_user_selected();
	});
	
	$('#tab_admHandoverAppt').on('shown.bs.collapse', function (){
		SmoothScrollTo('#tab_admHandoverAppt', 300);
		$("#jqGridNoteAdmHandover").jqGrid ('setGridWidth', Math.floor($("#jqGridNoteAdmHandover_c")[0].offsetWidth-$("#jqGridNoteAdmHandover_c")[0].offsetLeft-14));
		
		var urlparam = {
			action: 'get_table_admhandoverAppt',
		}
		
		var postobj = {
			_token: $('#_token').val(),
			mrn: $("#mrn_admHandover").val(),
			episno: $("#episno_admHandover").val()
		};
		
		$.post("./ptcare_admhandoverAppt/form?"+$.param(urlparam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).done(function (data){
			if(!$.isEmptyObject(data.admhandover)){
				if(!emptyobj_(data.admhandover))autoinsert_rowdata("#formAdmHandoverAppt",data.admhandover);
				if(!emptyobj_(data.episode))autoinsert_rowdata("#formAdmHandoverAppt",data.episode);
				if(!emptyobj_(data.nurshistory))autoinsert_rowdata("#formAdmHandoverAppt",data.nurshistory);
				if(!emptyobj_(data.pathealth))autoinsert_rowdata("#formAdmHandoverAppt",data.pathealth);
				if(!emptyobj_(data.nursassessment))autoinsert_rowdata("#formAdmHandoverAppt",data.nursassessment);
				refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'addNotes_admHandoverAppt');
				// button_state_admHandoverAppt('edit');
				button_state_admHandoverAppt('empty');
			}else{
				if(!emptyobj_(data.nurshistory))autoinsert_rowdata("#formAdmHandoverAppt",data.nurshistory);
				if(!emptyobj_(data.nursassessment))autoinsert_rowdata("#formAdmHandoverAppt",data.nursassessment);
				refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'kosongkan');
				button_state_admHandoverAppt('add');
			}
			textarea_init_admhandoverAppt();
		});
	});
	
	disableForm('#formAdmHandoverAppt');
	
	$("#new_admHandoverAppt").click(function (){
		button_state_admHandoverAppt('wait');
		enableForm('#formAdmHandoverAppt');
		rdonly('#formAdmHandoverAppt');
	});
	
	$("#edit_admHandoverAppt").click(function (){
		button_state_admHandoverAppt('wait');
		enableForm('#formAdmHandoverAppt');
		rdonly('#formAdmHandoverAppt');
	});
	
	$("#save_admHandoverAppt").click(function (){
		if( $('#formAdmHandoverAppt').isValid({requiredFields: ''}, conf, true) ) {
			readonlyForm('#formAdmHandoverAppt');
			saveForm_admHandoverAppt(function (){
				$("#cancel_admHandoverAppt").data('oper','edit');
				$("#cancel_admHandoverAppt").click();
			});
		}else{
			enableForm('#formAdmHandoverAppt');
			rdonly('#formAdmHandoverAppt');
		}
	});
	
	$("#cancel_admHandoverAppt").click(function (){
		disableForm('#formAdmHandoverAppt');
		button_state_admHandoverAppt($(this).data('oper'));
	});
	
	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function (){
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});
	
	// to autocheck the checkbox bila fill in textarea
	$("#drugs_remarks").on("keyup blur", function (){
		$("#allergydrugs").prop("checked", this.value !== "");
	});
	
	$("#plaster_remarks").on("keyup blur", function (){
		$("#allergyplaster").prop("checked", this.value !== "");
	});
	
	$("#food_remarks").on("keyup blur", function (){
		$("#allergyfood").prop("checked", this.value !== "");
	});
	
	$("#environment_remarks").on("keyup blur", function (){
		$("#allergyenvironment").prop("checked", this.value !== "");
	});
	
	$("#others_remarks").on("keyup blur", function (){
		$("#allergyothers").prop("checked", this.value !== "");
	});
	
	$("#unknown_remarks").on("keyup blur", function (){
		$("#allergyunknown").prop("checked", this.value !== "");
	});
	
	$("#none_remarks").on("keyup blur", function (){
		$("#allergynone").prop("checked", this.value !== "");
	});
	
	//////////////////////////////////////////print button starts//////////////////////////////////////////
	$("#admhandoverAppt_report").click(function (){
		window.open('./admhandover/showpdf?mrn_admHandover='+$('#mrn_admHandover').val()+'&episno_admHandover='+$('#episno_admHandover').val(), '_blank');
	});
	
	////////////////////////////////////////parameter for saving url////////////////////////////////////////
	var addmore_jqgrid = {more:false,state:false,edit:false}
	
	//////////////////////////////////////////jqGridNoteAdmHandover//////////////////////////////////////////
	$("#jqGridNoteAdmHandover").jqGrid({
		datatype: "local",
		editurl: "/ptcare_admhandoverAppt/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		scroll: true,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerNoteAdmHandover",
		loadComplete: function (){
			if(addmore_jqgrid.more == true){$('#jqGridNoteAdmHandover_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridNoteAdmHandover_iledit").click();
		},
	});
	
	//////////////////////////////////////////////myEditOptions//////////////////////////////////////////////
	var myEditOptions_add = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_noteAdmHandover,#jqGridPagerRefresh_noteAdmHandover").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridNoteAdmHandover_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGridNoteAdmHandover_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgrid.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'addNotes_admHandoverAppt');
			errorField.length = 0;
			$("#jqGridPagerDelete_noteAdmHandover,#jqGridPagerRefresh_noteAdmHandover").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'addNotes_admHandoverAppt');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridNoteAdmHandover').jqGrid('getRowData', rowid);
			
			let editurl = "/ptcare_admhandoverAppt/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_admHandover').val(),
					mrn: $('#mrn_admHandover').val(),
					action: 'admHandoverAppt_save',
				});
			$("#jqGridNoteAdmHandover").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_noteAdmHandover,#jqGridPagerRefresh_noteAdmHandover").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	////////////////////////////////////////jqGridPagerNoteAdmHandover////////////////////////////////////////
	$("#jqGridNoteAdmHandover").inlineNav('#jqGridPagerNoteAdmHandover', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerNoteAdmHandover", {
	// 	id: "jqGridPagerDelete_noteAdmHandover",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function (){
	// 		selRowId = $("#jqGridNoteAdmHandover").jqGrid('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			alert('Please select row');
	// 		}else{
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if(result == true){
	// 				param = {
	// 					_token: $("#_token").val(),
	// 					action: 'admHandoverAppt_save',
	// 					idno: selrowData('#jqGridNoteAdmHandover').idno,
	// 				}
					
	// 				$.post("/ptcare_admhandoverAppt/form?"+$.param(param), {oper:'del'}, function (data){
						
	// 				}).fail(function (data){
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data){
	// 					refreshGrid("#jqGridNoteAdmHandover", urlParam_NoteAdmHandover);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete_noteAdmHandover,#jqGridPagerRefresh_noteAdmHandover").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerNoteAdmHandover", {
		id: "jqGridPagerRefresh_noteAdmHandover",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridNoteAdmHandover", urlParam_NoteAdmHandover);
		},
	});
	/////////////////////////////////////////////////end grid/////////////////////////////////////////////////
	
});

var errorField = [];
conf = {
	modules: 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form){
		if(errorField.length > 0){
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

button_state_admHandoverAppt('empty');
function button_state_admHandoverAppt(state){
	switch(state){
		case 'empty':
			$("#toggle_admHandoverAppt").removeAttr('data-toggle');
			$('#cancel_admHandoverAppt').data('oper','add');
			$('#new_admHandoverAppt,#save_admHandoverAppt,#cancel_admHandoverAppt,#edit_admHandoverAppt').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_admHandoverAppt").attr('data-toggle','collapse');
			$('#cancel_admHandoverAppt').data('oper','add');
			$("#new_admHandoverAppt").attr('disabled',false);
			$('#save_admHandoverAppt,#cancel_admHandoverAppt,#edit_admHandoverAppt').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_admHandoverAppt").attr('data-toggle','collapse');
			$('#cancel_admHandoverAppt').data('oper','edit');
			$("#edit_admHandoverAppt").attr('disabled',false);
			$('#save_admHandoverAppt,#cancel_admHandoverAppt,#new_admHandoverAppt').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_admHandoverAppt").attr('data-toggle','collapse');
			$("#save_admHandoverAppt,#cancel_admHandoverAppt").attr('disabled',false);
			$('#edit_admHandoverAppt,#new_admHandoverAppt').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_admhandoverAppt_currpt(obj){
	emptyFormdata(errorField,"#formAdmHandoverAppt");
	// panel header
	$('#name_show_admHandover').text(obj.Name);
	$('#mrn_show_admHandover').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_admHandover').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_admHandover').text(dob_chg(obj.DOB));
	$('#age_show_admHandover').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_admHandover').text(if_none(obj.RaceCode).toUpperCase());
	$('#religion_show_admHandover').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_admHandover').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_admHandover').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_admHandover').text(if_none(obj.AreaCode).toUpperCase());
	$('#payer_show_admHandoverAppt').text(obj.payer);
	
	$("#mrn_admHandover").val(obj.MRN);
	$("#episno_admHandover").val(obj.Episno);
	
	urlParam_NoteAdmHandover.filterVal[0] = obj.MRN;
	urlParam_NoteAdmHandover.filterVal[1] = obj.Episno;
	urlParam_NoteAdmHandover.filterVal[2] = 'ADMISSION HANDOVER';
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input = $(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value == 1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else if(input.is("textarea")){
            if(value !== null){
                let newval = value.replaceAll("</br>",'\n');
                input.val(newval);
            }
        }else{
            input.val(value);
        }
    });
}

function empty_admhandoverAppt_ptcare(){

	emptyFormdata('#formAdmHandoverAppt')

	button_state_admHandoverAppt('empty');
	$('#name_show_admHandover').text('');
	$('#mrn_show_admHandover').text('');
	$('#sex_show_admHandover').text('');
	$('#dob_show_admHandover').text('');
	$('#age_show_admHandover').text('');
	$('#race_show_admHandover').text('');
	$('#religion_show_admHandover').text('');
	$('#occupation_show_admHandover').text('');
	$('#citizenship_show_admHandover').text('');
	$('#area_show_admHandover').text('');
	$('#payer_show_admHandoverAppt').text('');

	$('#mrn_admHandover').val('');
	$("#episno_admHandover").val('');
}

function saveForm_admHandoverAppt(callback){
	var saveParam = {
		action: 'save_table_admHandoverAppt',
		oper: $("#cancel_admHandoverAppt").data('oper')
	}
	
	var postobj = {
		_token: $('#_token').val(),
	};

    var values = $("#formAdmHandoverAppt").serializeArray();

    values = values.concat(
        $('#formAdmHandoverAppt input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formAdmHandoverAppt input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formAdmHandoverAppt input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formAdmHandoverAppt select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
	);

    // values = values.concat(
    //     $('#formAdmHandoverAppt input[type=radio]:checked').map(
    //     function (){
    //         return {"name": this.name, "value": this.value}
    //     }).get()
    // );

    $.post("./ptcare_admhandoverAppt/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).done(function (data){
        callback();
    });
}

function textarea_init_admhandoverAppt(){
	$('textarea#reasonadm,textarea#diagnosis,textarea#medicalhistory,textarea#surgicalhistory,textarea#drugs_remarks,textarea#plaster_remarks,textarea#food_remarks,textarea#environment_remarks,textarea#others_remarks,textarea#unknown_remarks,textarea#none_remarks,textarea#rtkpcr_remark,textarea#branula_remark,textarea#scan_remark,textarea#insurance_remark','textarea#medication_remark,textarea#consent_remark,textarea#smoking_remark,textarea#nbm_remark,textarea#report').each(function (){
		if(this.value.trim() == ''){
			this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}else{
			this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}
	}).off().on('input', function (){
		if(this.scrollHeight > 40){
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		}else{
			this.style.height = (40) + 'px';
		}
	});
}

function getdata_admHandoverAppt(){
	var urlparam = {
		action: 'get_table_admhandoverAppt',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		mrn: $("#mrn_admHandover").val(),
		episno: $("#episno_admHandover").val()
	};
	
	$.post("./ptcare_admhandoverAppt/form?"+$.param(urlparam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).done(function (data){
		if(!$.isEmptyObject(data.admhandover)){
			if(!emptyobj_(data.admhandover))autoinsert_rowdata("#formAdmHandoverAppt",data.admhandover);
			if(!emptyobj_(data.episode))autoinsert_rowdata("#formAdmHandoverAppt",data.episode);
			if(!emptyobj_(data.nurshistory))autoinsert_rowdata("#formAdmHandoverAppt",data.nurshistory);
			if(!emptyobj_(data.pathealth))autoinsert_rowdata("#formAdmHandoverAppt",data.pathealth);
			if(!emptyobj_(data.nursassessment))autoinsert_rowdata("#formAdmHandoverAppt",data.nursassessment);
			refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'addNotes_admHandoverAppt');
			button_state_admHandoverAppt('edit');
			button_state_admHandoverAppt('empty');
		}else{
			if(!emptyobj_(data.nurshistory))autoinsert_rowdata("#formAdmHandoverAppt",data.nurshistory);
			if(!emptyobj_(data.nursassessment))autoinsert_rowdata("#formAdmHandoverAppt",data.nursassessment);
			refreshGrid('#jqGridNoteAdmHandover',urlParam_NoteAdmHandover,'kosongkan');
			button_state_admHandoverAppt('add');
		}
		textarea_init_admhandoverAppt();
	});
}