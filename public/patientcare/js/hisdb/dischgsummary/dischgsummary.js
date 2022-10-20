
$(document).ready(function () {

	disableForm('#formDischgSummary');

	$("#new_dischgSummary").click(function(){
		button_state_dischgSummary('wait');
		enableForm('#formDischgSummary');
		rdonly('#formDischgSummary');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dischgSummary").click(function(){
		button_state_dischgSummary('wait');
		enableForm('#formDischgSummary');
		rdonly('#formDischgSummary');
		// dialog_mrn_edit.on();
		
	});

	$("#save_dischgSummary").click(function(){
		disableForm('#formDischgSummary');
		if( $('#formDischgSummary').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dischgSummary(function(){
				$("#cancel_dischgSummary").data('oper','edit');
				$("#cancel_dischgSummary").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDischgSummary');
			rdonly('#formDischgSummary');
		}

	});

	$("#cancel_dischgSummary").click(function(){
		disableForm('#formDischgSummary');
		button_state_dischgSummary($(this).data('oper'));
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

button_state_dischgSummary('empty');
function button_state_dischgSummary(state){
	switch(state){
		case 'empty':
			$("#toggle_dischgSummary").removeAttr('data-toggle');
			$('#cancel_dischgSummary').data('oper','add');
			$('#new_dischgSummary,#save_dischgSummary,#cancel_dischgSummary,#edit_dischgSummary').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_dischgSummary").attr('data-toggle','collapse');
			$('#cancel_dischgSummary').data('oper','add');
			$("#new_dischgSummary").attr('disabled',false);
			$('#save_dischgSummary,#cancel_dischgSummary,#edit_dischgSummary').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_dischgSummary").attr('data-toggle','collapse');
			$('#cancel_dischgSummary').data('oper','edit');
			$("#edit_dischgSummary").attr('disabled',false);
			$('#save_dischgSummary,#cancel_dischgSummary,#new_dischgSummary').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_dischgSummary").attr('data-toggle','collapse');
			$("#save_dischgSummary,#cancel_dischgSummary").attr('disabled',false);
			$('#edit_dischgSummary,#new_dischgSummary').attr('disabled',true);
			break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_dischgSummary,#save_dischgSummary,#cancel_dischgSummary,#edit_dischgSummary').attr('disabled',true);
	// }
}

function populate_dischgSummary(obj,rowdata){
	
	emptyFormdata(errorField,"#formDischgSummary");

	//panel header
	$('#name_show_dischgSummary').text(obj.name);
	$('#mrn_show_dischgSummary').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_dischgSummary').text(obj.sex);
	$('#dob_show_dischgSummary').text(dob_chg(obj.dob));
	$('#age_show_dischgSummary').text(obj.age+ ' (YRS)');
	$('#race_show_dischgSummary').text(obj.race);
	$('#religion_show_dischgSummary').text(if_none(obj.religion));
	$('#occupation_show_dischgSummary').text(if_none(obj.occupation));
	$('#citizenship_show_dischgSummary').text(obj.citizen);
	$('#area_show_dischgSummary').text(obj.area);

	//formDischgSummary
	$('#mrn_dischgSummary').val(obj.mrn);
	$("#episno_dischgSummary").val(obj.episno);

	var saveParam={
        action:'get_table_dischgSummary',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.mrn,
    	episno:obj.episno

    };

    $.post( "./dischgsummary/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata_dischg("#formDischgSummary",data.dischgSummary);
			button_state_dischgSummary('edit');
        }else{
			button_state_dischgSummary('add');
        }

    });
	
}

function autoinsert_rowdata_dischg(form,rowData){
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

function saveForm_dischgSummary(callback){
	var saveParam={
        action:'save_table_dischgSummary',
        oper:$("#cancel_dischgSummary").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDischgSummary").serializeArray();
	
	values = values.concat(
        $('#formDischgSummary input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDischgSummary input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDischgSummary input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDischgSummary select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./dischgsummary/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





