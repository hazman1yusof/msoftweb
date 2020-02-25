
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

	$("#edit_ti, #edit_ad, #edit_tpa").click(function(){
		button_state_nursing('edit');
		enableForm('#formTriageInfo');
		enableForm('#formActDaily');
		enableForm('#formTriPhysical');		
		rdonly('#formTriageInfo');
		rdonly('#formActDaily');
		rdonly('#formTriPhysical');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ti, #save_ad, #save_tpa").click(function(){
		disableForm('#formTriageInfo');
		disableForm('#formActDaily');
		disableForm('#formTriPhysical');
		saveForm_edit(function(){
			$("#cancel_ti, #cancel_ad, #cancel_tpa").click();
		});

	});

	$("#cancel_ti, #cancel_ad, #cancel_tpa").click(function(){
		disableForm('#formTriageInfo');
		disableForm('#formActDaily');
		disableForm('#formTriPhysical');
		button_state_nursing('init');
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

button_state_nursing('init');
function button_state_nursing(state){
	switch(state){
		case 'init':
			$("#edit_ti, #edit_ad, #edit_tpa").attr('disabled',false);
			$('#save_ti, #cancel_ti, #save_ad, #cancel_ad, #save_tpa, #cancel_tpa').attr('disabled',true);
			break;
		case 'edit':
			$("#save_ti, #cancel_ti, #save_ad, #cancel_ad, #save_tpa, #cancel_tpa").attr('disabled',false);
			$('#edit_ti, #edit_ad, #edit_tpa').attr('disabled',true);
			break;
	}
}

function populate_formNursing(obj){

	//panel header
	$('#name_show_ti, #name_show_ad, #name_show_tpa').text(obj.a_pat_name);
	$('#newic_show_ti, #newic_show_ad, #newic_show_tpa').text(obj.newic);
	$('#sex_show_ti, #sex_show_ad, #sex_show_tpa').text(obj.sex);
	$('#age_show_ti, #age_show_ad, #age_show_tpa').text(obj.age);
	$('#race_show_ti, #race_show_ad, #race_show_tpa').text(obj.race);	
	$("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();
	
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