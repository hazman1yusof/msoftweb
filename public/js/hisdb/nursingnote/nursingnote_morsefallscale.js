$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

/////////////////////////////parameter for jqGridAddNotesMorseFallScale url/////////////////////////////
var urlParam_AddNotesMorseFallScale = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','MORSE_FALL_SCALE'],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    textarea_init_morsefallscale();
    
    /////////////////////////////////////morsefallscale starts/////////////////////////////////////
    disableForm('#formMorseFallScale');
    
    $("#new_morsefallscale").click(function (){
        get_default_morsefallscale();
        button_state_morsefallscale('wait');
        enableForm('#formMorseFallScale');
        rdonly('#formMorseFallScale');
        emptyFormdata_div("#formMorseFallScale",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#morsefallscale_ward','#morsefallscale_diag','#morsefallscale_admdate']);
        $("#morsefallscale_datetaken").val(moment().format('YYYY-MM-DD'));
        document.getElementById("idno_morsefallscale").value = "";
        $("#morsefallscale_diag").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_morsefallscale").click(function (){
        button_state_morsefallscale('wait');
        enableForm('#formMorseFallScale');
        rdonly('#formMorseFallScale');
        $("#morsefallscale_diag,#morsefallscale_datetaken").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_morsefallscale").click(function (){
        disableForm('#formMorseFallScale');
        if($('#formMorseFallScale').isValid({requiredFields: ''}, conf, true)){
            saveForm_morsefallscale(function (){
                $("#cancel_morsefallscale").data('oper','edit');
                $("#cancel_morsefallscale").click();
                // $("#jqGridPagerRefresh").click();
            });
        }else{
            enableForm('#formMorseFallScale');
            rdonly('#formMorseFallScale');
        }
    });
    
    $("#cancel_morsefallscale").click(function (){
        disableForm('#formMorseFallScale');
        button_state_morsefallscale($(this).data('oper'));
        $('#tbl_morsefallscale_date').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////morsefallscale ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    /////////////////////////////////////morsefallscale starts/////////////////////////////////////
    $('#tbl_morsefallscale_date tbody').on('click', 'tr', function (){
        var data = tbl_morsefallscale_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{
            tbl_morsefallscale_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formMorseFallScale",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#morsefallscale_ward','#morsefallscale_diag','#morsefallscale_admdate']);
        $('#tbl_morsefallscale_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_morsefallscale('edit');
        }else{
            button_state_morsefallscale('add');
        }
        $('#morsefallscale_chart').attr('disabled',false);
        
        // populate_morsefallscale_getdata();
        $("#idno_morsefallscale").val(data.idno);
        
        var saveParam = {
            action: 'get_table_morsefallscale',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./morsefallscale/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data.morsefallscale)){
                autoinsert_rowdata("#formMorseFallScale",data.morsefallscale);
                
                // button_state_morsefallscale('edit');
            }else{
                // button_state_morsefallscale('add');
            }
            
            $("#morsefallscale_ward").val($('#ward_nursNote').val());
            $("#morsefallscale_diag").val(data.diagnosis);
            $("#morsefallscale_admdate").val(data.reg_date);
            textarea_init_morsefallscale();
        });
    });
    //////////////////////////////////////morsefallscale ends//////////////////////////////////////
    
    function calculate_morsefallscale(){
        var score = 0;
        $(".calc_morsefallscale:checked").each(function (){
            score+=parseInt($(this).val());
        });
        $("#formMorseFallScale input[name=totalScore]").val(score);
    }
    
    $(".calc_morsefallscale").change(function (){
        calculate_morsefallscale();
    });

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridMorseFallScale = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesMorseFallScale///////////////////////////////////////
	$("#jqGridAddNotesMorseFallScale").jqGrid({
		datatype: "local",
		editurl: "./morsefallscale/form",
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
		pager: "#jqGridPagerAddNotesMorseFallScale",
		loadComplete: function (){
			if(addmore_jqgridMorseFallScale.more == true){$('#jqGridAddNotesMorseFallScale_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridMorseFallScale.edit = addmore_jqgridMorseFallScale.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesMorseFallScale");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesMorseFallScale_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addMorseFallScale = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursingED,#jqGridPagerRefresh_addnoteNursingED").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesMorseFallScale_ilsave').click();
				// addmore_jqgridMorseFallScale.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridMorseFallScale.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesMorseFallScale',urlParam_AddNotesMorseFallScale,'add_notesMorseFallScale');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursingED,#jqGridPagerRefresh_addnoteNursingED").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesMorseFallScale',urlParam_AddNotesMorseFallScale,'add_notesMorseFallScale');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesMorseFallScale').jqGrid ('getRowData', rowid);
			
			let editurl = "./morsefallscale/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesMorseFallScale_save',
				});
			$("#jqGridAddNotesMorseFallScale").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursingED,#jqGridPagerRefresh_addnoteNursingED").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesMorseFallScale/////////////////////////////////////
	$("#jqGridAddNotesMorseFallScale").inlineNav('#jqGridPagerAddNotesMorseFallScale', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addMorseFallScale
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesMorseFallScale", {
		id: "jqGridPagerRefresh_addnoteNursingED",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesMorseFallScale", urlParam_AddNotesMorseFallScale);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
    
});

/////////////////////morsefallscale starts/////////////////////
var tbl_morsefallscale_date = $('#tbl_morsefallscale_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetaken', 'width': '25%' },
        { 'data': 'timetaken', 'width': '25%' },
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'dt' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 6], visible: false },
    ],
    order: [[6, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////morsefallscale ends//////////////////////

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

button_state_morsefallscale('empty');
function button_state_morsefallscale(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_morsefallscale').data('oper','add');
            $('#new_morsefallscale,#save_morsefallscale,#cancel_morsefallscale,#edit_morsefallscale,#morsefallscale_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_morsefallscale').data('oper','add');
            $("#new_morsefallscale").attr('disabled',false);
            $('#save_morsefallscale,#cancel_morsefallscale,#edit_morsefallscale').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_morsefallscale').data('oper','edit');
            $("#new_morsefallscale,#edit_morsefallscale").attr('disabled',false);
            $('#save_morsefallscale,#cancel_morsefallscale').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_morsefallscale,#cancel_morsefallscale").attr('disabled',false);
            $('#edit_morsefallscale,#new_morsefallscale,#morsefallscale_chart').attr('disabled',true);
            break;
    }
}

function populate_morsefallscale_getdata(){
    disableForm('#formMorseFallScale');
    emptyFormdata(errorField,"#formMorseFallScale",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#morsefallscale_ward","#morsefallscale_diag","#morsefallscale_admdate"]);
    
    var saveParam = {
        action: 'get_table_morsefallscale',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./morsefallscale/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data.morsefallscale)){
            autoinsert_rowdata("#formMorseFallScale",data.morsefallscale);
            
            button_state_morsefallscale('edit');
            $('#morsefallscale_chart').attr('disabled',false);
        }else{
            button_state_morsefallscale('add');
            $('#morsefallscale_chart').attr('disabled',true);
        }
        
        $("#morsefallscale_ward").val($('#ward_nursNote').val());
        $("#morsefallscale_diag").val(data.diagnosis);
        $("#morsefallscale_admdate").val(data.reg_date);
        textarea_init_morsefallscale();
    });
}

function get_default_morsefallscale(){
    disableForm('#formMorseFallScale');
    emptyFormdata(errorField,"#formMorseFallScale",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#morsefallscale_ward","#morsefallscale_diag","#morsefallscale_admdate"]);
    
    var saveParam = {
        action: 'get_table_morsefallscale',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./morsefallscale/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        // if(!$.isEmptyObject(data.morsefallscale)){
        //     autoinsert_rowdata("#formMorseFallScale",data.morsefallscale);
            
        //     button_state_morsefallscale('edit');
        // }else{
        //     button_state_morsefallscale('add');
        // }
        
        $("#morsefallscale_ward").val($('#ward_nursNote').val());
        $("#morsefallscale_diag").val(data.diagnosis);
        $("#morsefallscale_admdate").val(data.reg_date);
        textarea_init_morsefallscale();
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
        }else{
            input.val(value);
        }
    });
}

function saveForm_morsefallscale(callback){
    var saveParam = {
        action: 'save_table_morsefallscale',
        oper: $("#cancel_morsefallscale").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formMorseFallScale").serializeArray();
    
    values = values.concat(
        $('#formMorseFallScale input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formMorseFallScale input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formMorseFallScale input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formMorseFallScale select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formMorseFallScale input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./morsefallscale/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // $('#p_error_morsefallscale').text(data.responseText);
        // alert(data.responseText);
        
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function textarea_init_morsefallscale(){
    $('textarea#morsefallscale_diag').each(function () {
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

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == null){
        same = false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        same = false;
    }
    
    return same;
}

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight < 50){
//         scrollHeight = 50;
//     }else if(scrollHeight > 300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }