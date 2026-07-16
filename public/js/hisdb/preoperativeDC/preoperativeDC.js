$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

///////////////////////////////////parameter for jqGridAddNotesPreopDC url///////////////////////////////////
var urlParam_AddNotesPreopDC = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','PREOPERATIVEDC'],
}

$(document).ready(function (){
    
    textarea_init_preoperativeDC();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_preoperativeDC');
    
    $("#new_preoperativeDC").click(function (){
        $('#cancel_preoperativeDC').data('oper','add');
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_otMain','#episno_otMain']);
    });
    
    $("#edit_preoperativeDC").click(function (){
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
    });
    
    $("#save_preoperativeDC").click(function (){
        if($('#form_preoperativeDC').isValid({requiredFields: ''}, conf, true)){
            saveForm_preoperativeDC(function (data){
                // emptyFormdata_div("#form_preoperativeDC",['#mrn_otMain','#episno_otMain']);
                disableForm('#form_preoperativeDC');
            });
        }else{
            enableForm('#form_preoperativeDC');
            rdonly('#form_preoperativeDC');
        }
    });
    
    $("#cancel_preoperativeDC").click(function (){
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_otMain','#episno_otMain']);
        disableForm('#form_preoperativeDC');
        button_state_preoperativeDC($(this).data('oper'));
        getdata_preoperativeDC();
    });
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    // to limit to two decimal places (onkeypress)
    $(document).on('keydown', 'input[pattern]', function (e){
        var input = $(this);
        var oldVal = input.val();
        var regex = new RegExp(input.attr('pattern'), 'g');
        
        setTimeout(function (){
            var newVal = input.val();
            if(!regex.test(newVal)){
                input.val(oldVal);
            }
        }, 0);
    });
    
    // to calculate hours utilized
    $("#fasted_time_from,#fasted_time_until").on('change',function (){
        var startTime = moment($('#fasted_time_from').val(),'hh:mm:ss');
        var endTime = moment($('#fasted_time_until').val(),'hh:mm:ss');
        
        let duration = endTime.diff(startTime,'hours');
        $("#fasted_hours").val(duration);
    });

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridPreopDC = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotesPreopDC///////////////////////////////////////////
	$("#jqGridAddNotesPreopDC").jqGrid({
		datatype: "local",
		editurl: "/preoperativeDC/form",
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
		pager: "#jqGridPagerAddNotesPreopDC",
		loadComplete: function (){
			if(addmore_jqgridPreopDC.more == true){$('#jqGridAddNotesPreopDC_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridPreopDC.edit = addmore_jqgridPreopDC.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotesPreopDC_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_addPreopDC = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesPreopDC_ilsave').click();
				// addmore_jqgridPreopDC.state = true;
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridPreopDC.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesPreopDC',urlParam_AddNotesPreopDC,'add_preopDC_save');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesPreopDC',urlParam_AddNotesPreopDC,'add_preopDC_save');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotesPreopDC').jqGrid('getRowData', rowid);
			
			let editurl = "/preoperativeDC/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_otMain').val(),
					mrn: $('#mrn_otMain').val(),
					action: 'add_preopDC_save',
				});
			$("#jqGridAddNotesPreopDC").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotesPreopDC/////////////////////////////////////////
	$("#jqGridAddNotesPreopDC").inlineNav('#jqGridPagerAddNotesPreopDC', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addPreopDC
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesPreopDC", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesPreopDC", urlParam_AddNotesPreopDC);
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

button_state_preoperativeDC('empty');
function button_state_preoperativeDC(state){
    switch(state){
        case 'empty':
            $("#toggle_preoperativeDC").removeAttr('data-toggle');
            $('#cancel_preoperativeDC').data('oper','add');
            $('#new_preoperativeDC,#save_preoperativeDC,#cancel_preoperativeDC,#edit_preoperativeDC').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','add');
            $("#new_preoperativeDC").attr('disabled',false);
            $('#save_preoperativeDC,#cancel_preoperativeDC,#edit_preoperativeDC').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','edit');
            $("#edit_preoperativeDC").attr('disabled',false);
            $('#save_preoperativeDC,#cancel_preoperativeDC,#new_preoperativeDC').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $("#save_preoperativeDC,#cancel_preoperativeDC").attr('disabled',false);
            $('#edit_preoperativeDC,#new_preoperativeDC').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#new_preoperativeDC,#edit_preoperativeDC,#save_preoperativeDC,#cancel_preoperativeDC').attr('disabled',true);
            break;
    }
}

function empty_preoperativeDC(){
    emptyFormdata_div("#form_preoperativeDC");
    button_state_preoperativeDC('empty');
    
    // panel header
    // $('#name_show_preoperativeDC').text('');
    // $('#mrn_show_preoperativeDC').text('');
    // $('#icpssprt_show_preoperativeDC').text('');
    // $('#sex_show_preoperativeDC').text('');
    // $('#height_show_preoperativeDC').text('');
    // $('#weight_show_preoperativeDC').text('');
    // $('#dob_show_preoperativeDC').text('');
    // $('#age_show_preoperativeDC').text('');
    // $('#race_show_preoperativeDC').text('');
    // $('#religion_show_preoperativeDC').text('');
    // $('#occupation_show_preoperativeDC').text('');
    // $('#citizenship_show_preoperativeDC').text('');
    // $('#area_show_preoperativeDC').text('');
    // $('#ward_show_preoperativeDC').text('');
    // $('#bednum_show_preoperativeDC').text('');
    // $('#oproom_show_preoperativeDC').text('');
    // $('#diagnosis_show_preoperativeDC').text('');
    // $('#procedure_show_preoperativeDC').text('');
    // $('#unit_show_preoperativeDC').text('');
    // $('#type_show_preoperativeDC').text('');
    
    // form_preoperativeDC
    $('#mrn_otMain').val('');
    $("#episno_otMain").val('');
}

function populate_preoperativeDC(obj){
    // panel header
    // $('#name_show_preoperativeDC').text(obj.pat_name);
    // $('#mrn_show_preoperativeDC').text(("0000000" + obj.mrn).slice(-7));
    // $('#icpssprt_show_preoperativeDC').text(obj.icnum);
    // $('#sex_show_preoperativeDC').text(if_none(obj.Sex).toUpperCase());
    // $('#height_show_preoperativeDC').text(obj.height+' (CM)');
    // $('#weight_show_preoperativeDC').text(obj.weight+' (KG)');
    // $('#dob_show_preoperativeDC').text(dob_chg(obj.DOB));
    // $('#age_show_preoperativeDC').text(dob_age(obj.DOB)+' (YRS)');
    // $('#race_show_preoperativeDC').text(if_none(obj.RaceCode).toUpperCase());
    // $('#religion_show_preoperativeDC').text(if_none(obj.Religion).toUpperCase());
    // $('#occupation_show_preoperativeDC').text(if_none(obj.OccupCode).toUpperCase());
    // $('#citizenship_show_preoperativeDC').text(if_none(obj.Citizencode).toUpperCase());
    // $('#area_show_preoperativeDC').text(if_none(obj.AreaCode).toUpperCase());
    // $('#ward_show_preoperativeDC').text(obj.ward);
    // $('#bednum_show_preoperativeDC').text(obj.bednum);
    // $('#oproom_show_preoperativeDC').text(obj.ot_description);
    // $('#diagnosis_show_preoperativeDC').text(obj.appt_diag);
    // $('#procedure_show_preoperativeDC').text(obj.appt_prcdure);
    // $('#unit_show_preoperativeDC').text(obj.op_unit);
    // $('#type_show_preoperativeDC').text(obj.oper_type);
    
    // form_preoperativeDC
    $('#mrn_otMain').val(obj.mrn);
    $("#episno_otMain").val(obj.latest_episno);

    ////jqGridAddNotesPreopDC
    urlParam_AddNotesPreopDC.filterVal[0] = obj.mrn;
	urlParam_AddNotesPreopDC.filterVal[1] = obj.latest_episno;
	urlParam_AddNotesPreopDC.filterVal[2] = 'PREOPERATIVEDC';
    
    // $("#tab_preoperativeDC").collapse('hide');
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

function saveForm_preoperativeDC(callback){
    let oper = $("#cancel_preoperativeDC").data('oper');
    var saveParam = {
        action: 'save_table_preoperativeDC',
        oper: oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        saveParam.sel_date = $('#sel_date').val();
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn_otMain: $('#mrn_otMain').val(),
        episno_otMain: $('#episno_otMain').val(),
    };
    
    values = $("#form_preoperativeDC").serializeArray();
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./preoperativeDC/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_preoperativeDC('edit');
    }).fail(function (data){
        callback(data);
        button_state_preoperativeDC($(this).data('oper'));
    });
}

function textarea_init_preoperativeDC(){
    $('textarea#idBracelet_remarks,textarea#operSite_remarks,textarea#fasted_remarks,textarea#consentValid_remarks,textarea#consentAnaest_remarks,textarea#otGown_remarks,textarea#shaving_remarks,textarea#bowelPrep_remarks,textarea#bladder_remarks,textarea#dentures_remarks,textarea#lensImpSpec_remarks,textarea#nailVarnish_remarks,textarea#hairClips_remarks,textarea#valuables_remarks,textarea#ivFluids_remarks,textarea#premedGiven_remarks,textarea#medChart_remarks,textarea#caseNote_remarks,textarea#oldNotes_remarks,textarea#ptBelongings_remarks,textarea#allergies_remarks,textarea#medLegalCase_remarks,textarea#checkedBy_remarks,textarea#checkedDate_remarks,textarea#bloodTest_remarks,textarea#grpCrossMatch_remarks,textarea#ecg_remarks,textarea#xray_remarks,textarea#ctg_remarks,textarea#others_remarks,textarea#completedBy_remarks').each(function (){
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

$('#tab_preoperativeDC').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_preoperativeDC', 300,114);
    
    if($('#mrn_otMain').val() != ''){
        getdata_preoperativeDC();
    }
});

$('#tab_preoperativeDC').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_preoperativeDC",['#mrn_otMain','#episno_otMain']);
    button_state_preoperativeDC('empty');
});

function getdata_preoperativeDC(){
    var urlparam = {
        action: 'get_table_preoperativeDC',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_otMain').val(),
        episno: $("#episno_otMain").val()
    };
    
    $.post("./preoperativeDC/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.preopdc)){
            button_state_preoperativeDC('edit');
            // autoinsert_rowdata("#form_preoperativeDC",data.otmanage);
            autoinsert_rowdata("#form_preoperativeDC",data.preopdc);
            refreshGrid('#jqGridAddNotesPreopDC',urlParam_AddNotesPreopDC,'add_preopDC_save');
        }else{
            button_state_preoperativeDC('add');
            refreshGrid('#jqGridAddNotesPreopDC',urlParam_AddNotesPreopDC,'kosongkan');
        }
        
        if(!emptyobj_(data.iPesakit))$("#preopDC_iPesakit").val(data.iPesakit);
        textarea_init_preoperativeDC();
    });
}

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == undefined){
        return false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        return false;
    }
    
    return same;
}