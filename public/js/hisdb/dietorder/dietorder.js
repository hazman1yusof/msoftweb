
$(document).ready(function () {

	disableForm('#formDietOrder');

	$("#new_dietOrder").click(function(){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dietOrder").click(function(){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
		
	});

	$("#save_dietOrder").click(function(){
		disableForm('#formDietOrder');
		if( $('#formDietOrder').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dietOrder(function(){
				$("#cancel_dietOrder").data('oper','edit');
				$("#cancel_dietOrder").click();
				$('#refresh_jqGrid').click();
			});
		}else{
			enableForm('#formDietOrder');
			rdonly('#formDietOrder');
		}

	});

	$("#cancel_dietOrder").click(function(){
		disableForm('#formDietOrder');
		button_state_dietOrder($(this).data('oper'));
		// dialog_mrn_edit.off();

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

// button_state_dietOrder('empty');
function button_state_dietOrder(state){
	switch(state){
		case 'empty':
			$("#toggle_dietOrder").removeAttr('data-toggle');
			$('#cancel_dietOrder').data('oper','add');
			$('#new_dietOrder,#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$('#cancel_dietOrder').data('oper','add');
			$("#new_dietOrder").attr('disabled',false);
			$('#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$('#cancel_dietOrder').data('oper','edit');
			$("#edit_dietOrder").attr('disabled',false);
			$('#save_dietOrder,#cancel_dietOrder,#new_dietOrder').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$("#save_dietOrder,#cancel_dietOrder").attr('disabled',false);
			$('#edit_dietOrder,#new_dietOrder').attr('disabled',true);
			break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_dietOrder,#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
	// }
}


