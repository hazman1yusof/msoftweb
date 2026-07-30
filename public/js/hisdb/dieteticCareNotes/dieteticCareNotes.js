$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

/////////////////////////////parameter for jqGridAddNotesDieteticCareNotes url/////////////////////////////
var urlParam_AddNotesDieteticCareNotes = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','DIETETIC_CARE_NOTES'],
}

$(document).ready(function (){
    
    //////////////////////////////////////dietNote starts//////////////////////////////////////

    disableForm('#formDieteticCareNotes');
    
    $("#new_dieteticCareNotes").click(function (){
        button_state_dieteticCareNotes('wait');
        enableForm('#formDieteticCareNotes');
        rdonly('#formDieteticCareNotes');
        emptyFormdata_div("#formDieteticCareNotes",['#mrn_wardMain','#episno_wardMain']);
        $("#dietNote_datetaken").val(moment().format('YYYY-MM-DD'));
        document.getElementById("idno_dieteticCareNotes").value = "";
    });
    
    $("#edit_dieteticCareNotes").click(function (){
        button_state_dieteticCareNotes('wait');
        enableForm('#formDieteticCareNotes');
        rdonly('#formDieteticCareNotes');
    });
    
    $("#save_dieteticCareNotes").click(function (){
        disableForm('#formDieteticCareNotes');
        if($('#formDieteticCareNotes').isValid({requiredFields: ''}, conf, true)){
            saveForm_dieteticCareNotes(function (data){
                $("#cancel_dieteticCareNotes").data('oper','edit');
                $("#cancel_dieteticCareNotes").click();
            });
        }else{
            enableForm('#formDieteticCareNotes');
            rdonly('#formDieteticCareNotes');
        }
    });
    
    $("#cancel_dieteticCareNotes").click(function (){
        disableForm('#formDieteticCareNotes');
        button_state_dieteticCareNotes($(this).data('oper'));
        $('#dietNote_date_tbl').DataTable().ajax.reload();            
    });

    //////////////////////////////////////dietNote ends//////////////////////////////////////

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

    ////////////////////////////////////////dietNote starts////////////////////////////////////////
    $('#dietNote_date_tbl tbody').on('click', 'tr', function (){
        var data = dietNote_date_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            dietNote_date_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formDieteticCareNotes",['#mrn_wardMain','#episno_wardMain']);
        $('#dietNote_date_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_dieteticCareNotes").val(data.idno);
        
        var saveParam={
            action: 'get_table_dieteticCareNotes',
        }
        
        var postobj={
			_token: $('#csrf_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formDieteticCareNotes",data.patdietncase);

                button_state_dieteticCareNotes('add');
            }else{
                button_state_dieteticCareNotes('add');
            }
        });
    });

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridDieteticCareNotes = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesDieteticCareNotes///////////////////////////////////////
	$("#jqGridAddNotesDieteticCareNotes").jqGrid({
		datatype: "local",
		editurl: "./dieteticCareNotes/form",
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
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesDieteticCareNotes",
		loadComplete: function (){
			if(addmore_jqgridDieteticCareNotes.more == true){$('#jqGridAddNotesDieteticCareNotes_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridDieteticCareNotes.edit = addmore_jqgridDieteticCareNotes.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesDieteticCareNotes");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesDieteticCareNotes_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addDieteticCareNotes = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesDieteticCareNotes,#jqGridPagerRefresh_addnoteDieteticCareNotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesDieteticCareNotes_ilsave').click();
				// addmore_jqgridDieteticCareNotes.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridDieteticCareNotes.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesDieteticCareNotes',urlParam_AddNotesDieteticCareNotes,'add_notesDieteticCareNotes');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesDieteticCareNotes,#jqGridPagerRefresh_addnoteDieteticCareNotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesDieteticCareNotes',urlParam_AddNotesDieteticCareNotes,'add_notesDieteticCareNotes');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesDieteticCareNotes').jqGrid ('getRowData', rowid);
			
			let editurl = "./dieteticCareNotes/form?"+
				$.param({
					episno: $('#episno_wardMain').val(),
					mrn: $('#mrn_wardMain').val(),
					action: 'addNotesDieteticCareNotes_save',
				});
			$("#jqGridAddNotesDieteticCareNotes").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesDieteticCareNotes,#jqGridPagerRefresh_addnoteDieteticCareNotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesDieteticCareNotes/////////////////////////////////////
	$("#jqGridAddNotesDieteticCareNotes").inlineNav('#jqGridPagerAddNotesDieteticCareNotes', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addDieteticCareNotes
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesDieteticCareNotes", {
		id: "jqGridPagerRefresh_addnoteDieteticCareNotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesDieteticCareNotes", urlParam_AddNotesDieteticCareNotes);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
});

/////////////////////dietNote starts/////////////////////
var dietNote_date_tbl = $('#dietNote_date_tbl').DataTable({
    "ajax": "",
	"sDom": "",
	"paging": false,
	"columns": [
		{'data': 'idno'},
		{'data': 'mrn'},
		{'data': 'episno'},
		{'data': 'datetaken', 'width': '20%'},
		{'data': 'timetaken', 'width': '20%'},
		{'data': 'adduser','width': '60%'},
	],
	columnDefs: [
		{targets: [0,1,2], visible: false},
	],
	"order": [[ 3, "desc" ]],
	"drawCallback": function (settings){
		$(this).find('tbody tr')[0].click();
	}
});
//////////////////////dietNote ends//////////////////////

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

button_state_dieteticCareNotes('empty');
function button_state_dieteticCareNotes(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_dieteticCareNotes').data('oper','add');
            $('#new_dieteticCareNotes,#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_dieteticCareNotes').data('oper','add');
            $("#new_dieteticCareNotes").attr('disabled',false);
            $('#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_dieteticCareNotes').data('oper','edit');
            $("#edit_dieteticCareNotes,#new_dieteticCareNotes").attr('disabled',false);
            $('#save_dieteticCareNotes,#cancel_dieteticCareNotes').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_dieteticCareNotes,#cancel_dieteticCareNotes").attr('disabled',false);
            $('#edit_dieteticCareNotes,#new_dieteticCareNotes').attr('disabled',true);
            break;
    }
}

function populate_dieteticCareNotes_currpt(obj){
	console.log('populate');
    disableForm('#formDieteticCareNotes');
    emptyFormdata(errorField,"#formDieteticCareNotes");

   	$('#name_show_dieteticCareNotes').text(obj.Name);
	$('#mrn_show_dieteticCareNotes').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dieteticCareNotes').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_dieteticCareNotes').text(dob_chg(obj.DOB));
	$('#age_show_dieteticCareNotes').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dieteticCareNotes').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dieteticCareNotes').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_dieteticCareNotes').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_dieteticCareNotes').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_dieteticCareNotes').text(if_none(obj.areaDesc).toUpperCase());
	
	// formDieteticCareNotes
	$('#mrn_wardMain').val(obj.MRN);
	$("#episno_wardMain").val(obj.Episno);
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

function saveForm_dieteticCareNotes(callback){
    let oper = $("#cancel_dieteticCareNotes").data('oper');
    var saveParam = {
        action: 'save_table_dieteticCareNotes',
        oper: oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj = {
		_token: $('#csrf_token').val(),
        mrn: $('#mrn_wardMain').val(),
        episno: $("#episno_wardMain").val(),
    };
    
    values = $("#formDieteticCareNotes").serializeArray();
    
    values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formDieteticCareNotes input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formDieteticCareNotes select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').success(function (data){
        callback(data);
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}


function getdata_dietNote(){
    // console.log('populate');
    emptyFormdata(errorField,"#formDieteticCareNotes",["#mrn_wardMain","#episno_wardMain"]);

    var urlparam = {
        action: 'get_table_dieteticCareNotes',
    }
    
    var postobj = {
		_token: $('#csrf_token').val(),
        mrn: $('#mrn_wardMain').val(),
        episno: $("#episno_wardMain").val()
    };
    
    $.post("./dieteticCareNotes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            button_state_dieteticCareNotes('add');
            autoinsert_rowdata("#formDieteticCareNotes",data.patdietncase);
        }else{
            button_state_dieteticCareNotes('add');
        }
    });

    ////jqGridAddNotesDieteticCareNotes
	urlParam_AddNotesDieteticCareNotes.filterVal[0] = $("#mrn_wardMain").val();
	urlParam_AddNotesDieteticCareNotes.filterVal[1] = $("#episno_wardMain").val();
	urlParam_AddNotesDieteticCareNotes.filterVal[2] = 'DIETETIC_CARE_NOTES';
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