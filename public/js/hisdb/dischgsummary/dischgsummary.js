
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
				$('#refresh_jqGrid').click();
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

// button_state_dischgSummary('empty');
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





