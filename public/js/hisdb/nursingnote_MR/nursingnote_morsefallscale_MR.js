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
        
        $.post("./morsefallscale_MR/form?"+$.param(saveParam), $.param(postobj), function (data){
            
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
		editurl: "./morsefallscale_MR/form",
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
	
	/////////////////////////////////////jqGridPagerAddNotesMorseFallScale/////////////////////////////////////
	$("#jqGridAddNotesMorseFallScale").inlineNav('#jqGridPagerAddNotesMorseFallScale', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
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
    
    $.post("./morsefallscale_MR/form?"+$.param(saveParam), $.param(postobj), function (data){
        
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
    
    $.post("./morsefallscale_MR/form?"+$.param(saveParam), $.param(postobj), function (data){
        
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