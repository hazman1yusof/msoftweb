
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

	$("#edit_ti").click(function(){
		button_state_ti('edit');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ti").click(function(){
		disableForm('#formTriageInfo');
		saveForm_edit(function(){
			$("#cancel_ti").click();
		});

	});

	$("#cancel_ti").click(function(){
		disableForm('#formTriageInfo');
		button_state_ti('init');
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

button_state_ti('init');
function button_state_ti(state){
	switch(state){
		case 'init':
			$("#edit_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti').attr('disabled',true);
			break;
		case 'edit':
			$("#save_ti,#cancel_ti").attr('disabled',false);
			$('#edit_ti').attr('disabled',true);
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

function populate_formNursing(obj){

	//panel header Triage Information
	$('#name_show_ti').text(obj.a_pat_name);
	$('#newic_show_ti').text(obj.newic);
	$('#sex_show_ti').text(obj.sex);
	$('#age_show_ti').text(obj.age);
	$('#race_show_ti').text(obj.race);	
	$("#btn_grp_edit_ti").show();

	//panel header Activities of Daily Living
	$('#name_show_ad').text(obj.a_pat_name);
	$('#newic_show_ad').text(obj.newic);
	$('#sex_show_ad').text(obj.sex);
	$('#age_show_ad').text(obj.age);
	$('#race_show_ad').text(obj.race);	
	$("#btn_grp_edit_ad").show();

	//panel header Triage Physical Assessment
	$('#name_show_tpa').text(obj.a_pat_name);
	$('#newic_show_tpa').text(obj.newic);
	$('#sex_show_tpa').text(obj.sex);
	$('#age_show_tpa').text(obj.age);
	$('#race_show_tpa').text(obj.race);	
	$("#btn_grp_edit_tpa").show();
	
}

function empty_formNursing(){

	//Triage Information
	$('#name_show_ti').text('');
	$('#newic_show_ti').text('');
	$('#sex_show_ti').text('');
	$('#age_show_ti').text('');
	$('#race_show_ti').text('');	
	$("#btn_grp_edit_ti").hide();
	$("#cancel_ti").click();

	disableForm('#formTriageInfo');
	emptyFormdata(errorField_nursing,'#formTriageInfo')

	//Activities of Daily Living
	$('#name_show_ad').text('');
	$('#newic_show_ad').text('');
	$('#sex_show_ad').text('');
	$('#age_show_ad').text('');
	$('#race_show_ad').text('');	
	$("#btn_grp_edit_ad").hide();
	$("#cancel_ad").click();

	disableForm('#formActDaily');
	emptyFormdata(errorField_nursing,'#formActDaily')

	//Triage Physical Assessment
	$('#name_show_tpa').text('');
	$('#newic_show_tpa').text('');
	$('#sex_show_tpa').text('');
	$('#age_show_tpa').text('');
	$('#race_show_tpa').text('');	
	$("#btn_grp_edit_tpa").hide();
	$("#cancel_tpa").click();

	disableForm('#formTriPhysical');
	emptyFormdata(errorField_nursing,'#formTriPhysical')

}