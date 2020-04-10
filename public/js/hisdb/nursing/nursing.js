
$(document).ready(function () {

	// disableForm('#formTriageInfo, #formActDaily, #formTriPhysical');

	disableForm('#formTriageInfo');
	disableForm('#formActDaily');
	disableForm('#formTriPhysical');

	$("#new_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ti").click(function(){
		disableForm('#formTriageInfo');
		saveForm_ti(function(){
			$("#cancel_ti").data('oper','edit');
			$("#cancel_ti").click();
		});

	});

	$("#cancel_ti").click(function(){
		disableForm('#formTriageInfo');
		button_state_ti($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	$("#new_ad").click(function(){
		button_state_ad('wait');
		enableForm('#formActDaily');
		rdonly('#formActDaily');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_ad").click(function(){
		button_state_ad('wait');
		enableForm('#formActDaily');
		rdonly('#formActDaily');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ad").click(function(){
		disableForm('#formActDaily');
		saveForm_ad(function(){
			$("#cancel_ad").data('oper','edit_ad');
			$("#cancel_ad").click();
		});

	});

	$("#cancel_ad").click(function(){
		disableForm('#formActDaily');
		button_state_ad($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	$("#new_tpa").click(function(){
		button_state_tpa('wait');
		enableForm('#formTriPhysical');
		rdonly('#formTriPhysical');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_tpa").click(function(){
		button_state_tpa('wait');
		enableForm('#formTriPhysical');
		rdonly('#formTriPhysical');
		// dialog_mrn_edit.on();
		
	});

	$("#save_tpa").click(function(){
		disableForm('#formTriPhysical');
		saveForm_tpa(function(){
			$("#cancel_tpa").data('oper','edit_tpa');
			$("#cancel_tpa").click();
		});

	});

	$("#cancel_tpa").click(function(){
		disableForm('#formTriPhysical');
		button_state_tpa($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

});

var errorField_nursing = [];
conf_nursing = {
	modules : 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form) {
		if (errorField_nursing.length > 0) {
			return {
				element: $(errorField_nursing[0]),
				message: ''
			}
		}
	},
};

button_state_ti('empty');
function button_state_ti(state){
	switch(state){
		case 'empty':
			$("#toggle_ti").removeAttr('data-toggle');
			$('#cancel_ti').data('oper','add');
			$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#cancel_ti').data('oper','add');
			$("#new_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#cancel_ti').data('oper','edit');
			$("#edit_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#new_ti').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_ti").attr('data-toggle','collapse');
			$("#save_ti,#cancel_ti").attr('disabled',false);
			$('#edit_ti,#new_ti').attr('disabled',true);
			break;
	}

	if(!moment(gldatepicker_date).isSame(moment(), 'day')){
		$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
	}
}

button_state_ad('empty');
function button_state_ad(state){
	switch(state){
		case 'empty':
			$("#toggle_ad").removeAttr('data-toggle');
			$('#cancel_ad').data('oper','add_ad');
			$('#new_ad,#save_ad,#cancel_ad,#edit_ad').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_ad").attr('data-toggle','collapse');
			$('#cancel_ad').data('oper','add_ad');
			$("#new_ad").attr('disabled',false);
			$('#save_ad,#cancel_ad,#edit_ad').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_ad").attr('data-toggle','collapse');
			$('#cancel_ad').data('oper','edit_ad');
			$("#edit_ad").attr('disabled',false);
			$('#save_ad,#cancel_ad,#new_ad').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_ad").attr('data-toggle','collapse');
			$("#save_ad,#cancel_ad").attr('disabled',false);
			$('#edit_ad,#new_ad').attr('disabled',true);
			break;
	}

	if(!moment(gldatepicker_date).isSame(moment(), 'day')){
		$('#new_ad,#save_ad,#cancel_ad,#edit_ad').attr('disabled',true);
	}
}

button_state_tpa('empty');
function button_state_tpa(state){
	switch(state){
		case 'empty':
			$("#toggle_tpa").removeAttr('data-toggle');
			$('#cancel_tpa').data('oper','add_tpa');
			$('#new_tpa,#save_tpa,#cancel_tpa,#edit_tpa').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_tpa").attr('data-toggle','collapse');
			$('#cancel_tpa').data('oper','add_tpa');
			$("#new_tpa").attr('disabled',false);
			$('#save_tpa,#cancel_tpa,#edit_tpa').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_tpa").attr('data-toggle','collapse');
			$('#cancel_tpa').data('oper','edit_tpa');
			$("#edit_tpa").attr('disabled',false);
			$('#save_tpa,#cancel_tpa,#new_tpa').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_tpa").attr('data-toggle','collapse');
			$("#save_tpa,#cancel_tpa").attr('disabled',false);
			$('#edit_tpa,#new_tpa').attr('disabled',true);
			break;
	}

	if(!moment(gldatepicker_date).isSame(moment(), 'day')){
		$('#new_tpa,#save_tpa,#cancel_tpa,#edit_tpa').attr('disabled',true);
	}
}

function populate_formNursing(obj,rowdata){

	//panel header
	$('#name_show_ti, #name_show_ad, #name_show_tpa').text(obj.a_pat_name);
	$('#newic_show_ti, #newic_show_ad, #newic_show_tpa').text(obj.newic);
	$('#sex_show_ti, #sex_show_ad, #sex_show_tpa').text(obj.sex);
	$('#age_show_ti, #age_show_ad, #age_show_tpa').text(obj.age+ 'YRS');
	$('#race_show_ti, #race_show_ad, #race_show_tpa').text(obj.race);	
	button_state_ti('add');
	button_state_ad('add');
	button_state_tpa('add');

	//formTriageInfo
	$("#mrn_edit_ti, #mrn_edit_ad, #mrn_edit_tpa").val(obj.a_mrn);
	$("#reg_date").val(obj.reg_date);

	if(rowdata.nurse != undefined){
		autoinsert_rowdata("#formTriageInfo",rowdata.nurse);
		button_state_ti('edit');
	}
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

function empty_formNursing(){

	$('#name_show_ti, #name_show_ad, #name_show_tpa').text('');
	$('#newic_show_ti, #newic_show_ad, #newic_show_tpa').text('');
	$('#sex_show_ti, #sex_show_ad, #sex_show_tpa').text('');
	$('#age_show_ti, #age_show_ad, #age_show_tpa').text('');
	$('#race_show_ti, #race_show_ad, #race_show_tpa').text('');	
	button_state_ti('empty');
	button_state_ad('empty');
	button_state_tpa('empty');
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	disableForm('#formTriageInfo');
	emptyFormdata(errorField_nursing,'#formTriageInfo')

	disableForm('#formActDaily');
	emptyFormdata(errorField_nursing,'#formActDaily')

	disableForm('#formTriPhysical');
	emptyFormdata(errorField_nursing,'#formTriPhysical')

}


function saveForm_ti(callback){
	var saveParam={
        action:'save_table_ti',
        oper:$("#cancel_ti").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formTriageInfo").serializeArray();

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );

    $.post( "/nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_ad(callback){
	var saveParam={
        action:'save_table_ad',
        oper:$("#cancel_ad").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formActDaily").serializeArray();

    values = values.concat(
        $('#formActDaily input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formActDaily input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );

    $.post( "/nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_tpa(callback){
	var saveParam={
        action:'save_table_tpa',
        oper:$("#cancel_tpa").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formTriPhysical").serializeArray();

    values = values.concat(
        $('#formTriPhysical input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formTriPhysical input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );

    $.post( "/nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}