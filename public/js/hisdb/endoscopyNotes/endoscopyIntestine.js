$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

///////////////////////////////////parameter for jqGridAddNotesEndoIntestine url///////////////////////////////////
var urlParam_AddNotesEndoIntestine = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','ENDO_INTESTINE'],
}

$(document).ready(function (){
    
    textarea_init_endoscopyIntestine();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formEndoscopyIntestine');
    
    $("#new_endoscopyIntestine").click(function (){
        $('#cancel_endoscopyIntestine').data('oper','add');
        button_state_endoscopyIntestine('wait');
        enableForm('#formEndoscopyIntestine');
        rdonly('#formEndoscopyIntestine');
        // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_otMain','#episno_otMain']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_endoscopyIntestine").click(function (){
        button_state_endoscopyIntestine('wait');
        enableForm('#formEndoscopyIntestine');
        rdonly('#formEndoscopyIntestine');
        // dialog_mrn_edit.on();
    });
    
    $("#save_endoscopyIntestine").click(function (){
        if($('#formEndoscopyIntestine').isValid({requiredFields: ''}, conf, true)){
            saveForm_endoscopyIntestine(function (data){
                // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_otMain','#episno_otMain']);
                disableForm('#formEndoscopyIntestine');
            });
        }else{
            enableForm('#formEndoscopyIntestine');
            rdonly('#formEndoscopyIntestine');
        }
    });
    
    $("#cancel_endoscopyIntestine").click(function (){
        // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_otMain','#episno_otMain']);
        disableForm('#formEndoscopyIntestine');
        button_state_endoscopyIntestine($(this).data('oper'));
        getdata_endoscopyIntestine();
        // dialog_mrn_edit.off();
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
    
    //////////////////////////////////////////body diagram starts//////////////////////////////////////////
    $('a.ui.card.bodydia_endoscopyIntestine').click(function (){
        let mrn = $('#mrn_otMain').val();
        let episno = $('#episno_otMain').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_endoscopyIntestine').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=endoscopyIntestine', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ///////////////////////////////////////////body diagram ends///////////////////////////////////////////
    
    $("#endoscopyIntestine_chart").click(function (){
        window.open('./endoscopyNotes/endoscopyintestine_chart?mrn='+$('#mrn_otMain').val()+'&episno='+$("#episno_otMain").val()+'&age='+$("#age_otMain").val()+'&type=INTESTINE', '_blank');
    });
    
    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridEndoIntestine = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotesEndoIntestine///////////////////////////////////////////
	$("#jqGridAddNotesEndoIntestine").jqGrid({
		datatype: "local",
		editurl: "/endoscopyNotes/form",
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
		pager: "#jqGridPagerAddNotesEndoIntestine",
		loadComplete: function (){
			if(addmore_jqgridEndoIntestine.more == true){$('#jqGridAddNotesEndoIntestine_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridEndoIntestine.edit = addmore_jqgridEndoIntestine.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotesEndoIntestine_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_addEndoIntestine = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesEndoIntestine_ilsave').click();
				// addmore_jqgridEndoIntestine.state = true;
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridEndoIntestine.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesEndoIntestine',urlParam_AddNotesEndoIntestine,'add_endoIntestine_save');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesEndoIntestine',urlParam_AddNotesEndoIntestine,'add_endoIntestine_save');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotesEndoIntestine').jqGrid('getRowData', rowid);
			
			let editurl = "/endoscopyNotes/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_otMain').val(),
					mrn: $('#mrn_otMain').val(),
					action: 'add_endoIntestine_save',
				});
			$("#jqGridAddNotesEndoIntestine").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotesEndoIntestine/////////////////////////////////////////
	$("#jqGridAddNotesEndoIntestine").inlineNav('#jqGridPagerAddNotesEndoIntestine', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addEndoIntestine
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesEndoIntestine", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesEndoIntestine", urlParam_AddNotesEndoIntestine);
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

button_state_endoscopyIntestine('empty');
function button_state_endoscopyIntestine(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_endoscopyNotes").removeAttr('data-toggle');
            $('#cancel_endoscopyIntestine').data('oper','add');
            $('#new_endoscopyIntestine,#save_endoscopyIntestine,#cancel_endoscopyIntestine,#edit_endoscopyIntestine,#endoscopyIntestine_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyIntestine').data('oper','add');
            $("#new_endoscopyIntestine").attr('disabled',false);
            $('#save_endoscopyIntestine,#cancel_endoscopyIntestine,#edit_endoscopyIntestine').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyIntestine').data('oper','edit');
            $("#edit_endoscopyIntestine,#endoscopyIntestine_chart").attr('disabled',false);
            $('#save_endoscopyIntestine,#cancel_endoscopyIntestine,#new_endoscopyIntestine').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $("#save_endoscopyIntestine,#cancel_endoscopyIntestine").attr('disabled',false);
            $('#edit_endoscopyIntestine,#new_endoscopyIntestine,#endoscopyIntestine_chart').attr('disabled',true);
            break;
    }
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

function saveForm_endoscopyIntestine(callback){
    let oper = $("#cancel_endoscopyIntestine").data('oper');
    var saveParam = {
        action: 'save_table_endoscopyIntestine',
        oper: oper,
        mrn: $('#mrn_otMain').val(),
        episno: $("#episno_otMain").val(),
        age: $("#age_otMain").val()
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formEndoscopyIntestine").serializeArray();
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./endoscopyNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_endoscopyIntestine('edit');
    }).fail(function (data){
        callback(data);
        button_state_endoscopyIntestine($(this).data('oper'));
    });
}

function textarea_init_endoscopyIntestine(){
    $('textarea#endoscopyIntestine_perRectum,textarea#endoscopyIntestine_otherIllness,textarea#endoscopyIntestine_endosFindings,textarea#endoscopyIntestine_biopsy,textarea#endoscopyIntestine_otherProcedure,textarea#endoscopyIntestine_endosImpression,textarea#endoscopyIntestine_remarks').each(function (){
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

function getdata_endoscopyIntestine(){
    var urlparam = {
        action: 'get_table_endoscopyIntestine',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_otMain').val(),
        episno: $("#episno_otMain").val()
    };
    
    $.post("./endoscopyNotes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.endoscopyintestine)){
            autoinsert_rowdata("#formEndoscopyIntestine",data.endoscopyintestine);
            button_state_endoscopyIntestine('edit');
            refreshGrid('#jqGridAddNotesEndoIntestine',urlParam_AddNotesEndoIntestine,'add_endoIntestine_save');
            $('#endoscopyIntestine_chart').attr('disabled',false);
        }else{
            button_state_endoscopyIntestine('add');
            refreshGrid('#jqGridAddNotesEndoIntestine',urlParam_AddNotesEndoIntestine,'kosongkan');
            $('#endoscopyIntestine_chart').attr('disabled',true);
        }
        
        $("#endoscopyIntestine_iPesakit").val(data.iPesakit);
        textarea_init_endoscopyIntestine();
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