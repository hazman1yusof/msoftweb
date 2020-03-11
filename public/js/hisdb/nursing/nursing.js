
$(document).ready(function () {

	////////////////////////////////pass parameter in url////////////////////////////////

	//url: http://msoftweb.test/emergency?name_show=SAFIAH%20MD%20SALLEH&newic_show=430307015232&sex_show=F&age_show=20&race_show=MALAY

	function getQueryVariable(variable) {
		var query = window.location.search.substring(1);
		var parms = query.split('&');
		for (var i = 0; i < parms.length; i++) {
			var pos = parms[i].indexOf('=');
			if (pos > 0 && variable == parms[i].substring(0, pos)) {
				return parms[i].substring(pos + 1);;
			}
		}
		return "";
	}

	getQueryVariable("name_show, newic_show, sex_show, age_show, race_show");

	$(function () {
		$('#name_show').text(getQueryVariable('name_show'))
		$('#newic_show').text(getQueryVariable('newic_show'))
		$('#sex_show').text(getQueryVariable('sex_show'))
		$('#age_show').text(getQueryVariable('age_show'))
		$('#race_show').text(getQueryVariable('race_show'))
	});

	////////////////////////////////////////end////////////////////////////////////////

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

	$("#edit_ad").click(function(){
		button_state_ad('edit');
		enableForm('#formActDaily');
		rdonly('#formActDaily');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ad").click(function(){
		disableForm('#formActDaily');
		saveForm_edit(function(){
			$("#cancel_ad").click();
		});

	});

	$("#cancel_ad").click(function(){
		disableForm('#formActDaily');
		button_state_ad('init');
		// dialog_mrn_edit.off();

	});

	$("#edit_tpa").click(function(){
		button_state_tpa('edit');
		enableForm('#formTriPhysical');
		rdonly('#formTriPhysical');
		// dialog_mrn_edit.on();
		
	});

	$("#save_tpa").click(function(){
		disableForm('#formTriPhysical');
		saveForm_edit(function(){
			$("#cancel_tpa").click();
		});

	});

	$("#cancel_tpa").click(function(){
		disableForm('#formTriPhysical');
		button_state_tpa('init');
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

button_state_ti('add');
function button_state_ti(state){
	switch(state){
		case 'add':
			$('#cancel_ti').data('oper','add');
			$("#new_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
		case 'edit':
			$('#cancel_ti').data('oper','edit');
			$("#edit_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#new_ti').attr('disabled',true);
			break;
		case 'wait':
			$("#save_ti,#cancel_ti").attr('disabled',false);
			$('#edit_ti,#new_ti').attr('disabled',true);
			break;
	}
}

button_state_ad('init');
function button_state_ad(state){
	switch(state){
		case 'init':
			$("#edit_ad").attr('disabled',false);
			$('#save_ad,#cancel_ad').attr('disabled',true);
			break;
		case 'edit':
			$("#save_ad,#cancel_ad").attr('disabled',false);
			$('#edit_ad').attr('disabled',true);
			break;
	}
}

button_state_tpa('init');
function button_state_tpa(state){
	switch(state){
		case 'init':
			$("#edit_tpa").attr('disabled',false);
			$('#save_tpa,#cancel_tpa').attr('disabled',true);
			break;
		case 'edit':
			$("#save_tpa,#cancel_tpa").attr('disabled',false);
			$('#edit_tpa').attr('disabled',true);
			break;
	}
}

function populate_formNursing(obj,rowdata){

	//panel header
	$('#name_show_ti, #name_show_ad, #name_show_tpa').text(obj.a_pat_name);
	$('#newic_show_ti, #newic_show_ad, #newic_show_tpa').text(obj.newic);
	$('#sex_show_ti, #sex_show_ad, #sex_show_tpa').text(obj.sex);
	$('#age_show_ti, #age_show_ad, #age_show_tpa').text(obj.age);
	$('#race_show_ti, #race_show_ad, #race_show_tpa').text(obj.race);	
	$("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();

	//formTriageInfo
	$("#mrn_edit_ti").val(obj.a_mrn);
	$("#reg_date").val(obj.reg_date);

	if(rowdata.nurse != undefined){
		autoinsert_rowdata("#formTriageInfo",rowdata.nurse);
		button_state_ti('edit');
	}else{
		button_state_ti('add');
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
	$("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").hide();
	$("#cancel_ti, #cancel_ad, #cancel_tpa").click();

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