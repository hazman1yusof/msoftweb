$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

/////////////////////////////parameter for jqGridAddNotesGlasgow url/////////////////////////////
var urlParam_AddNotesGlasgow = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','GLASGOW_COMA_SCALE'],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    /////////////////////////////////////glasgow_MR Coma Scale starts/////////////////////////////////////
    disableForm('#formGlasgow');
    //////////////////////////////////////glasgow_MR Coma Scale ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    ////////////////////////////////////////glasgow_MR Coma Scale starts////////////////////////////////////////
    $('#datetimegcs_tbl tbody').on('click', 'tr', function (){
        var data = datetimegcs_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimegcs_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formGlasgow",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimegcs_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_glasgow").val(data.idno);
        
        var saveParam = {
            action: 'get_table_glasgow',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno
        };
        
        $.post("./glasgow_MR/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formGlasgow",data.glasgow);
                $("#gcs_date").val(data.date);
                
                button_state_glasgow('edit');
                // textarea_init_nursingnote();
            }else{
                button_state_glasgow('add');
                // textarea_init_nursingnote();
            }
        });
    });
    /////////////////////////////////////////glasgow_MR Coma Scale ends/////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridGlasgow = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesGlasgow///////////////////////////////////////
	$("#jqGridAddNotesGlasgow").jqGrid({
		datatype: "local",
		editurl: "./glasgow_MR/form",
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
		pager: "#jqGridPagerAddNotesGlasgow",
		loadComplete: function (){
			if(addmore_jqgridGlasgow.more == true){$('#jqGridAddNotesGlasgow_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridGlasgow.edit = addmore_jqgridGlasgow.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesGlasgow");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesGlasgow_iledit").click();
		},
	});
	
	/////////////////////////////////////jqGridPagerAddNotesGlasgow/////////////////////////////////////
	$("#jqGridAddNotesGlasgow").inlineNav('#jqGridPagerAddNotesGlasgow', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesGlasgow", {
		id: "jqGridPagerRefresh_addnoteGlasgow",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesGlasgow", urlParam_AddNotesGlasgow);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////    
});

/////////////////////glasgow_MR Coma Scale starts/////////////////////
var datetimegcs_tbl = $('#datetimegcs_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'gcs_date', 'width': '20%' },
        { 'data': 'gcs_time', 'width': '20%' },
        { 'data': 'adduser', 'width': '40%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////glasgow_MR Coma Scale ends//////////////////////

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_glasgow('empty');
function button_state_glasgow(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_glasgow').data('oper','add');
            $('#new_glasgow,#save_glasgow,#cancel_glasgow,#edit_glasgow').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_glasgow').data('oper','add');
            $("#new_glasgow").attr('disabled',false);
            $('#save_glasgow,#cancel_glasgow,#edit_glasgow').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_glasgow').data('oper','edit');
            $("#new_glasgow,#edit_glasgow").attr('disabled',false);
            $('#save_glasgow,#cancel_glasgow').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_glasgow,#cancel_glasgow").attr('disabled',false);
            $('#edit_glasgow,#new_glasgow').attr('disabled',true);
            break;
    }
}

function populate_glasgow_getdata(){
    disableForm('#formGlasgow');
    emptyFormdata(errorField,"#formGlasgow",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_glasgow',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./glasgow_MR/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formGlasgow",data.glasgow);
            $("#gcs_date").val(data.date);
            
            button_state_glasgow('edit');
            // textarea_init_nursingnote();
        }else{
            button_state_glasgow('add');
            // textarea_init_nursingnote();
        }
    });
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input=$(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value==1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else{
            input.val(value);
        }
    });
}

