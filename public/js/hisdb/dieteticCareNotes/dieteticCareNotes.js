
$(document).ready(function () {

	disableForm('#formDieteticCareNotes');

	$("#new_dieteticCareNotes").click(function(){
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dieteticCareNotes").click(function(){
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// dialog_mrn_edit.on();
		
	});

	$("#save_dieteticCareNotes").click(function(){
		disableForm('#formDieteticCareNotes');
		if( $('#formDieteticCareNotes').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dieteticCareNotes(function(){
				$("#cancel_dieteticCareNotes").data('oper','edit');
				$("#cancel_dieteticCareNotes").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDieteticCareNotes');
			rdonly('#formDieteticCareNotes');
		}

	});

	$("#cancel_dieteticCareNotes").click(function(){
		disableForm('#formDieteticCareNotes');
		button_state_dieteticCareNotes($(this).data('oper'));
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

});

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

button_state_dieteticCareNotes('empty');
function button_state_dieteticCareNotes(state){
	switch(state){
		case 'empty':
			$("#toggle_dieteticCareNotes").removeAttr('data-toggle');
			$('#cancel_dieteticCareNotes').data('oper','add');
			$('#new_dieteticCareNotes,#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes').data('oper','add');
			$("#new_dieteticCareNotes").attr('disabled',false);
			$('#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes').data('oper','edit');
			$("#edit_dieteticCareNotes").attr('disabled',false);
			$('#save_dieteticCareNotes,#cancel_dieteticCareNotes,#new_dieteticCareNotes').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$("#save_dieteticCareNotes,#cancel_dieteticCareNotes").attr('disabled',false);
			$('#edit_dieteticCareNotes,#new_dieteticCareNotes').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_dieteticCareNotes_currpt(obj){	
	emptyFormdata(errorField,"#formDieteticCareNotes");

	//panel header
	$('#name_show_dieteticCareNotes').text(obj.Name);
	$('#mrn_show_dieteticCareNotes').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dieteticCareNotes').text((obj.Sex).toUpperCase());
	$('#dob_show_dieteticCareNotes').text(dob_chg(obj.DOB));
	$('#age_show_dieteticCareNotes').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dieteticCareNotes').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dieteticCareNotes').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_dieteticCareNotes').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_dieteticCareNotes').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_dieteticCareNotes').text(if_none(obj.areaDesc).toUpperCase());

	//formDieteticCareNotes
	$('#mrn_dieteticCareNotes').val(obj.MRN);
	$("#episno_dieteticCareNotes").val(obj.Episno);

	var saveParam={
        action:'get_table_dieteticCareNotes',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			// autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.an_pathistory);
			// autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.an_pathealth);
			button_state_dieteticCareNotes('edit');
        }else{
			button_state_dieteticCareNotes('add');
        }

	});
	
}

function autoinsert_rowdata_dieteticCareNotes(form,rowData){
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

function saveForm_dieteticCareNotes(callback){
	var saveParam={
        action:'save_table_dieteticCareNotes',
        oper:$("#cancel_dieteticCareNotes").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDieteticCareNotes").serializeArray();
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





