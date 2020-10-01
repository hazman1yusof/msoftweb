
$(document).ready(function () {

	disableForm('#formDoctorNote');

	$("#new_doctorNote").click(function(){
    	$('#docnote_date_tbl tbody tr').removeClass('active');
		$('#cancel_doctorNote').data('oper','add');
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_doctorNote").click(function(){
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// dialog_mrn_edit.on();
		
	});

	$("#save_doctorNote").click(function(){
		disableForm('#formDoctorNote');
		if( $('#formDoctorNote').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_doctorNote(function(){
				$("#cancel_doctorNote").click();
    			docnote_date_tbl.ajax.url( "/doctornote/table?"+$.param(dateParam_docnote) ).load();
			});
		}else{
			enableForm('#formDoctorNote');
			rdonly('#formDoctorNote');
		}

	});

	$("#cancel_doctorNote").click(function(){
		disableForm('#formDoctorNote');
		button_state_doctorNote($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	// to limit to two decimal places (onkeypress)
	$(document).on('keydown', 'input[pattern]', function(e){
		var input = $(this);
		var oldVal = input.val();
		var regex = new RegExp(input.attr('pattern'), 'g');
	  
		setTimeout(function(){
			var newVal = input.val();
			if(!regex.test(newVal)){
				input.val(oldVal); 
		  	}
		}, 0);
	});
	
	//bmi calculator
	$('#height').keyup(function(event) {
		getBMI();
	});

	$('#weight').keyup(function(event) {
		getBMI();
	});
	//bmi calculator ends
	
});

//bmi calculator
function getBMI() {
    var height = parseFloat($("#height").val());
    var weight = parseFloat($("#weight").val());

	var myBMI = (weight / height / height) * 10000;

    var bmi = myBMI.toFixed(2);

    if (isNaN(bmi)) bmi = 0;

    $('#bmi').val((bmi));
}

var errorField = [];
conf = {
	modules : 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form) {
		if (errorField.length > 0) {
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

// button_state_doctorNote('empty');
function button_state_doctorNote(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_doctorNote').data('oper','add');
			$('#new_doctorNote,#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_doctorNote').data('oper','add');
			$("#new_doctorNote").attr('disabled',false);
			$('#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_doctorNote').data('oper','edit');
			$("#edit_doctorNote,#new_doctorNote").attr('disabled',false);
			$('#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_doctorNote,#cancel_doctorNote").attr('disabled',false);
			$('#edit_doctorNote,#new_doctorNote').attr('disabled',true);
			break;
		// case 'docnote':
		// 	$("#toggle_doctorNote").attr('data-toggle','collapse');
		// 	$('#cancel_doctorNote').data('oper','add');
		// 	$("#new_doctorNote").attr('disabled',false);
		// 	$('#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
		// 	break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_doctorNote,#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
	// }
}

var dateParam_docnote,doctornote_docnote;
function populate_doctorNote(obj,rowdata){
	
	emptyFormdata(errorField,"#formDoctorNote");

	//panel header
	$('#name_show_doctorNote').text(obj.name);
	$('#mrn_show_doctorNote').text(obj.mrn);

	//formDoctorNote
	$('#mrn_doctorNote').val(obj.mrn);
	$("#episno_doctorNote").val(obj.episno);

    doctornote_docnote={
    	action:'get_table_doctornote',
    	mrn:obj.mrn,
    	episno:obj.episno

    };

    dateParam_docnote={
        action:'get_table_date',
    	mrn:obj.mrn,
    	episno:obj.episno
    }

    button_state_doctorNote('add');
}

function autoinsert_rowdata_doctorNote(form,rowData){
	$.each(rowData, function( index, value ) {
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

function saveForm_doctorNote(callback){
	var saveParam={
        action:'save_table_doctornote',
        oper:$("#cancel_doctorNote").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDoctorNote").serializeArray();
	
	values = values.concat(
        $('#formDoctorNote input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDoctorNote input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDoctorNote input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDoctorNote select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "/doctornote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

var docnote_date_tbl = $('#docnote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging":false,
    "columns": [
        {'data': 'idno'},
        {'data': 'date', 'width': '100%'},
    ],
    columnDefs: [ {
        targets: [0],
        visible: false
    } ],
    order: [[0, 'desc']],
});

var ajaxurl;
$('#jqGridDoctorNote_panel').on('show.bs.collapse', function () {
    docnote_date_tbl.ajax.url( "/doctornote/table?"+$.param(dateParam_docnote) ).load();
});

$('#docnote_date_tbl tbody').on('click', 'tr', function () { 
    if(disable_edit_date()){
    	return;
    }
    $('#docnote_date_tbl tbody tr').removeClass('active');
    $(this).addClass('active');

    button_state_doctorNote('edit');

    $.get( "/doctornote/table?"+$.param(doctornote_docnote), function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			console.log(data);
		}
	});

});

function disable_edit_date(){
	let disabled = false;
    let newact = $('#new_doctorNote').attr('disabled');
    let data_oper = $('#cancel_doctorNote').data('oper');

    if(newact == 'disabled' && data_oper == 'add'){
    	disabled = true;
    }
    console.log(disabled);
    return disabled;
}