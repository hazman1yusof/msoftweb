$(document).ready(function () {

	disableForm('#formEnquiryDtl2');

	// $("#new_EnquiryDtl2").click(function(){
	// 	button_state_EnquiryDtl2('wait');
	// 	enableForm('#formEnquiryDtl2');
	// 	rdonly('#formEnquiryDtl2');
	// 	// dialog_mrn_edit.on();
		
	// });

	$("#edit_EnquiryDtl2").click(function(){
		button_state_EnquiryDtl2('wait');
		enableForm('#formEnquiryDtl2');
		frozeOnEdit('#formEnquiryDtl2');
		// dialog_mrn_edit.on();	
	});

	$("#save_EnquiryDtl2").click(function(){
		disableForm('#formEnquiryDtl2');
		if( $('#formEnquiryDtl2').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_EnquiryDtl2(function(){
				$("#cancel_EnquiryDtl2").data('oper','edit');
				$("#cancel_EnquiryDtl2").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formEnquiryDtl2');
			frozeOnEdit('#formEnquiryDtl2');
		}
	});

	$("#cancel_EnquiryDtl2").click(function(){
		disableForm('#formEnquiryDtl2');
		button_state_EnquiryDtl2('edit');
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

button_state_EnquiryDtl2('edit');
function button_state_EnquiryDtl2(state){
	switch(state){
		case 'empty':
			$("#toggle_EnquiryDtl2").removeAttr('data-toggle');
			$('#cancel_EnquiryDtl2').data('oper','add');
			$('#new_EnquiryDtl2,#save_EnquiryDtl2,#cancel_EnquiryDtl2,#edit_EnquiryDtl2').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_EnquiryDtl2").attr('data-toggle','collapse');
			$('#cancel_EnquiryDtl2').data('oper','add');
			$("#new_EnquiryDtl2").attr('disabled',false);
			$('#save_EnquiryDtl2,#cancel_EnquiryDtl2,#edit_EnquiryDtl2').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_EnquiryDtl2").attr('data-toggle','collapse');
			$('#cancel_EnquiryDtl2').data('oper','edit');
			$("#edit_EnquiryDtl2").attr('disabled',false);
			$('#save_EnquiryDtl2,#cancel_EnquiryDtl2,#new_EnquiryDtl2').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_EnquiryDtl2").attr('data-toggle','collapse');
			$("#save_EnquiryDtl2,#cancel_EnquiryDtl2").attr('disabled',false);
			$('#edit_EnquiryDtl2,#new_EnquiryDtl2').attr('disabled',true);
			break;
	}
}

function populate_EnquiryDtl2(obj,rowdata){

	emptyFormdata(errorField,"#formEnquiryDtl2");
	
	//panel header
	$('#category_show_transferFA').text(obj.assetcode);
	$('#description_show_transferFA').text(obj.description);
	$('#assetno_show_transferFA').text(obj.assetno);

	//formEnquiryDtl2
	$('#description_transferFA').val(obj.description);
	$("#category_show_transferFA").val(obj.assetcode);
	$("#assetno_show_transferFA").val(obj.assetno);

	var saveParam={
        action:'get_table_EnquiryDtl2',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	delordno:obj.delordno,
    	assetno:obj.assetno

    };

    $.post( "/EnquiryDtl2/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formEnquiryDtl2",data.EnquiryDtl2);
			autoinsert_rowdata("#formEnquiryDtl2",data.faregister);
			button_state_EnquiryDtl2('edit');
			// yesnoCheck();
			// feedingCheck();
        }else{
			button_state_EnquiryDtl2('add');
        }

    });
	
}

function autoinsert_rowdata(form,rowData){
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

function saveForm_EnquiryDtl2(callback){
	var saveParam={
        action:'save_table_EnquiryDtl2',
        oper:$("#cancel_EnquiryDtl2").data('oper')
    }
    var postobj={
    	// _token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formEnquiryDtl2").serializeArray();

    $.post( "/assetenquiryDtl2/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}


