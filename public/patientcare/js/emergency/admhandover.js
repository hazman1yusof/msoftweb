$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

///////////////////////////////////parameter for jqGridAddNotesAdmHandoverED url///////////////////////////////////
var urlParam_AddNotesAdmHandoverED = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','ADMISSION HANDOVER'],
}

$(document).ready(function (){
    
    // $("button.refreshbtn_admhandover").click(function (){
    //     empty_admhandover_ptcare();
    //     populate_admhandover_currpt(selrowData('#jqGrid'));
    // });
    // populate_admhandover_currpt(selrowData('#jqGrid'));

    disableForm('#formAdmHandover');

    // $("#new_admHandover").click(function (){
	// 	button_state_admHandover('wait');
	// 	enableForm('#formAdmHandover');
	// 	rdonly('#formAdmHandover');
	// });
    
    $("#edit_admHandover").click(function (){
		button_state_admHandover('wait');
		enableForm('#formAdmHandover');
		rdonly('#formAdmHandover');
	});

    $("#save_admHandover").click(function (){
		disableForm('#formAdmHandover');
		if($('#formAdmHandover').isValid({requiredFields: ''}, conf, true)){
			saveForm_admHandover(function (){
				$("#cancel_admHandover").data('oper','edit');
				$("#cancel_admHandover").click();
				button_state_admHandover('edit');

			});
		}
	});

    $("#cancel_admHandover").click(function (){
		disableForm('#formAdmHandover');
		button_state_admHandover($(this).data('oper'));
	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});	

	// to autocheck the checkbox bila fill in textarea
	$("#drugs_remarks").on("keyup blur", function () {
		$("#allergydrugs").prop("checked", this.value !== "");
	});

	$("#food_remarks").on("keyup blur", function () {
		$("#allergyfood").prop("checked", this.value !== "");
	});

	$("#others_remarks").on("keyup blur", function () {
		$("#allergyothers").prop("checked", this.value !== "");
	});

	$("#environment_remarks").on("keyup blur", function () {
		$("#allergyenvironment").prop("checked", this.value !== "");
	});

	$("#plaster_remarks").on("keyup blur", function () {
		$("#allergyplaster").prop("checked", this.value !== "");
	});

	$("#unknown_remarks").on("keyup blur", function () {
		$("#allergyunknown").prop("checked", this.value !== "");
	});

	$("#none_remarks").on("keyup blur", function () {
		$("#allergynone").prop("checked", this.value !== "");
	});

	/////////////////////////////////print button starts////////////////////////////////////////////

	$("#admhandover_report").click(function() {
		window.open('./admhandover/showpdf?mrn_emergencyMain='+$('#mrn_emergencyMain').val()+'&episno_emergencyMain='+$('#episno_emergencyMain').val(), '_blank');
	});

	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridAdmHandoverED = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotesAdmHandoverED///////////////////////////////////////////
	$("#jqGridAddNotesAdmHandoverED").jqGrid({
		datatype: "local",
		editurl: "/ptcare_admhandover/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
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
		width: 700,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesAdmHandoverED",
		loadComplete: function (){
			if(addmore_jqgridAdmHandoverED.more == true){$('#jqGridAddNotesAdmHandoverED_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridAdmHandoverED.edit = addmore_jqgridAdmHandoverED.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotesAdmHandoverED_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_addAdmHandoverED = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesAdmHandoverED_ilsave').click();
				// addmore_jqgridAdmHandoverED.state = true;
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridAdmHandoverED.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesAdmHandoverED',urlParam_AddNotesAdmHandoverED,'add_AdmHandoverED_save');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesAdmHandoverED',urlParam_AddNotesAdmHandoverED,'add_AdmHandoverED_save');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotesAdmHandoverED').jqGrid('getRowData', rowid);
			
			let editurl = "/ptcare_admhandover/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_emergencyMain').val(),
					mrn: $('#mrn_emergencyMain').val(),
					action: 'add_AdmHandoverED_save',
				});
			$("#jqGridAddNotesAdmHandoverED").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotesAdmHandoverED/////////////////////////////////////////
	$("#jqGridAddNotesAdmHandoverED").inlineNav('#jqGridPagerAddNotesAdmHandoverED', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addAdmHandoverED
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerAddNotesAdmHandoverED", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function (){
	// 		selRowId = $("#jqGridAddNotesAdmHandoverED").jqGrid('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			alert('Please select row');
	// 		}else{
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if(result == true){
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'addNotes_save',
	// 					idno: selrowData('#jqGridAddNotesAdmHandoverED').idno,
	// 				}
					
	// 				$.post("/ptcare_admhandover/form?"+$.param(param), {oper:'del'}, function (data){
						
	// 				}).fail(function (data){
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data){
	// 					refreshGrid("#jqGridAddNotesAdmHandoverED", urlParam_AddNotesAdmHandoverED);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotesAdmHandoverED", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesAdmHandoverED", urlParam_AddNotesAdmHandoverED);
		},
	});
	///////////////////////////////////////////////end grid///////////////////////////////////////////////
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

button_state_admHandover('empty');
function button_state_admHandover(state){
	switch(state){
		case 'empty':
			$("#toggle_admHandover").removeAttr('data-toggle');
			$('#cancel_admHandover').data('oper','add');
			$('#save_admHandover,#cancel_admHandover,#edit_admHandover,#admhandover_report').attr('disabled',true);
			break;
		// case 'add':
		// 	$("#toggle_admHandover").attr('data-toggle','collapse');
		// 	$('#cancel_admHandover').data('oper','add');
		// 	$("#new_admHandover").attr('disabled',false);
		// 	$('#save_admHandover,#cancel_admHandover,#edit_admHandover').attr('disabled',true);
		// 	break;
		case 'edit':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$('#cancel_admHandover').data('oper','edit');
			$("#edit_admHandover").attr('disabled',false);
			$('#save_admHandover,#cancel_admHandover').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$("#save_admHandover,#cancel_admHandover,#admhandover_report").attr('disabled',false);
			$('#edit_admHandover').attr('disabled',true);
			break;
	}
	
}

function empty_admhandover_ptcare(obj){
    emptyFormdata(errorField,"#formAdmHandover");
    
    // panel header
    // $('#name_show_admHandover').text('');
    // $('#mrn_show_admHandover').text('');
    // $('#sex_show_admHandover').text('');
    // $('#dob_show_admHandover').text('');
    // $('#age_show_admHandover').text('');
    // $('#race_show_admHandover').text('');
    // $('#religion_show_admHandover').text('');
	// $('#occupation_show_admHandover').text('');
    // $('#citizenship_show_admHandover').text('');
    // $('#area_show_admhandover').text('');
    
    // formAdmHandover
    $('#mrn_emergencyMain').val('');
    $("#episno_emergencyMain").val('');
}

function populate_admhandover_currpt(obj){
    emptyFormdata(errorField,"#formAdmHandover");
    
    // panel header
    // $('#name_show_admHandover').text(obj.Name);
	// $('#mrn_show_admHandover').text(("0000000" + obj.MRN).slice(-7));
	// $('#sex_show_admHandover').text(if_none(obj.Sex).toUpperCase());
	// $('#dob_show_admHandover').text(dob_chg(obj.DOB));
	// $('#age_show_admHandover').text(dob_age(obj.DOB)+' (YRS)');
	// $('#race_show_admHandover').text(if_none(obj.raceDesc).toUpperCase());
	// $('#religion_show_admHandover').text(if_none(obj.religion).toUpperCase());
	// $('#occupation_show_admHandover').text(if_none(obj.OccupCode).toUpperCase());
	// $('#citizenship_show_admHandover').text(if_none(obj.Citizencode).toUpperCase());
	// $('#area_show_admHandover').text(if_none(obj.AreaCode).toUpperCase());
    
    // formAdmHandover
    $("#mrn_emergencyMain").val(obj.MRN);
	$("#episno_emergencyMain").val(obj.Episno);
}

function populate_admhandover_getdata(){
    emptyFormdata(errorField,"#formAdmHandover",["#mrn_emergencyMain","#episno_emergencyMain"]);
    
    var saveParam = {
        action: 'get_table_admhandover',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_emergencyMain").val(),
        episno: $("#episno_emergencyMain").val()
    };
    
    $.get("./ptcare_admhandover/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formAdmHandover",data.admhandover);
			autoinsert_rowdata("#formAdmHandover",data.episode);
			autoinsert_rowdata("#formAdmHandover",data.nurshistory);
			autoinsert_rowdata("#formAdmHandover",data.pathealth);      
			refreshGrid('#jqGridAddNotesAdmHandoverED',urlParam_AddNotesAdmHandoverED,'add_AdmHandoverED_save');
            button_state_admHandover('edit');      
        }else{
        }
		textarea_init_admhandover();

    });
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

function saveForm_admHandover(callback){
	var saveParam = {
		action: 'save_table_admHandover',
		oper: $("#cancel_admHandover").data('oper'),
		mrn: $("#mrn_emergencyMain").val(),
        episno: $("#episno_emergencyMain").val()
	}
	var postobj = {
		_token: $('#_token').val(),
		takeoverby: $("#takeoverby").val(),

	};
	
	// values = $("#formAdmHandover").serializeArray();
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=checkbox]:not(:checked)').map(
	// 		function (){
	// 			return {"name": this.name, "value": 0}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=checkbox]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": 1}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=radio]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover select').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	$.post("./ptcare_admhandover/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		callback(data);
		// button_state_admHandover('empty');      
		populate_admhandover_getdata();

	}).fail(function (data){
		callback(data);
	});
}

$('#tab_admHandover').on('shown.bs.collapse', function (){
    // populate_admhandover_currpt(selrowData('#jqGrid'));
    SmoothScrollTo("#tab_admHandover", 500);
    populate_admhandover_getdata();
    rdonly('#formAdmHandover');

});

$("#tab_admHandover").on("hide.bs.collapse", function (){
    disableForm('#formAdmHandover');
});

function textarea_init_admhandover(){
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

// to disable all input fields except takeoverby
function disableOtherField(){
	
	$('#dateofadm, #reasonadm, #diagnosis, #weights, #medicalhistory, #surgicalhistory, #allergydrugs, #drugs_remarks, #allergyplaster, #plaster_remarks, #allergyfood, #food_remarks, #allergyenvironment, #environment_remarks, #allergyothers, #others_remarks, #allergyunknown, #unknown_remarks, #allergynone, #none_remarks, #rtkpcr_remark, #bloodinv_remark, #branula_remark, #scan_remark, #insurance_remark, #medication_remark, #consent_remark, #smoking_remark, #nbm_remark, #report, #allergynone, #passoverby').prop('disabled',true);

}

