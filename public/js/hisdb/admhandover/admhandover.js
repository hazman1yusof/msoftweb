
$(document).ready(function (){
	
	disableForm('#formAdmHandover');
	
	$("#new_admHandover").click(function (){
		button_state_admHandover('wait');
		enableForm('#formAdmHandover');
		rdonly('#formAdmHandover');
	});
	
	$("#edit_admHandover").click(function (){
		button_state_admHandover('wait');
		enableForm('#formAdmHandover');
		rdonly('#formAdmHandover');
	});
	
	$("#save_admHandover").click(function (){
		disableForm('#formAdmHandover');
		if($('#formAdmHandover').isValid({requiredFields: ''}, conf, true)){
			saveForm_admHandover(function (){
				$("#cancel_admHandover").data('oper','edit');
				$("#cancel_admHandover").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formAdmHandover');
			rdonly('#formAdmHandover');
		}
	});
	
	$("#cancel_admHandover").click(function (){
		disableForm('#formAdmHandover');
		button_state_admHandover($(this).data('oper'));
	});
	
	$("#jqGridAdmHandover_panel").on("shown.bs.collapse", function (){
		var saveParam = {
			action: 'get_table_admhandover',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn: $("#mrn_admHandover").val(),
			episno: $("#episno_admHandover").val(),
		};
		
		$.post("admhandover/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data.admhandover)){
				autoinsert_rowdata("#formAdmHandover",data.admhandover);
				autoinsert_rowdata("#formAdmHandover",data.episode);
				autoinsert_rowdata("#formAdmHandover",data.pathealth);
				autoinsert_rowdata("#formAdmHandover",data.nurshistory);
				// autoinsert_rowdata("#formAdmHandover",data.nursassessment);
				button_state_admHandover('edit');
				textarea_init_admhandover();
			}else{
				autoinsert_rowdata("#formAdmHandover",data.episode);
				autoinsert_rowdata("#formAdmHandover",data.pathealth);
				autoinsert_rowdata("#formAdmHandover",data.nurshistory);
				// autoinsert_rowdata("#formAdmHandover",data.nursassessment);
				button_state_admHandover('add');
				textarea_init_admhandover();
			}
		});
		
		SmoothScrollTo("#jqGridAdmHandover_panel", 500);
	});
	
	$("#jqGridAdmHandover_panel").on("hide.bs.collapse", function (){
		button_state_admHandover('empty');
		disableForm('#formAdmHandover');
		// $("#jqGridAdmHandover_panel > div").scrollTop(0);
	});

	$("#admhandover_report").click(function() {
		window.open('./admhandover/showpdf?mrn_admHandover='+$('#mrn_admHandover').val()+'&episno_admHandover='+$('#episno_admHandover').val(), '_blank');
	});
});

var errorField = [];
conf = {
	modules: 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form){
		if(errorField.length > 0){
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

button_state_admHandover('empty');
function button_state_admHandover(state){
	switch(state){
		case 'empty':
			$("#toggle_admHandover").removeAttr('data-toggle');
			$('#cancel_admHandover').data('oper','add');
			$('#new_admHandover,#save_admHandover,#cancel_admHandover,#edit_admHandover').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$('#cancel_admHandover').data('oper','add');
			$("#new_admHandover").attr('disabled',false);
			$('#save_admHandover,#cancel_admHandover,#edit_admHandover').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$('#cancel_admHandover').data('oper','edit');
			$("#edit_admHandover").attr('disabled',false);
			$('#save_admHandover,#cancel_admHandover,#new_admHandover').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$("#save_admHandover,#cancel_admHandover").attr('disabled',false);
			$('#edit_admHandover,#new_admHandover').attr('disabled',true);
			break;
	}
	
	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_admHandover,#save_admHandover,#cancel_admHandover,#edit_admHandover').attr('disabled',true);
	// }
}

function populate_admHandover(obj,rowdata){
	emptyFormdata(errorField,"#formAdmHandover");
	
	// panel header
	$('#name_show_admHandover').text(obj.name);
	$('#mrn_show_admHandover').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_admHandover').text(obj.sex);
	$('#dob_show_admHandover').text(dob_chg(obj.dob));
	$('#age_show_admHandover').text(obj.age+ ' (YRS)');
	$('#race_show_admHandover').text(obj.race);
	$('#religion_show_admHandover').text(if_none(obj.religion));
	$('#occupation_show_admHandover').text(if_none(obj.occupation));
	$('#citizenship_show_admHandover').text(obj.citizen);
	$('#area_show_admHandover').text(obj.area);
	
	// formAdmHandover
	$('#mrn_admHandover').val(obj.mrn);
	$("#episno_admHandover").val(obj.episno);
	
	var saveParam = {
		action: 'get_table_admhandover',
	}
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: obj.mrn,
		episno: obj.episno,
	};
	
	$.post("admhandover/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formAdmHandover",data.admhandover);
			autoinsert_rowdata("#formAdmHandover",data.episode);
			autoinsert_rowdata("#formAdmHandover",data.pathealth);
			autoinsert_rowdata("#formAdmHandover",data.nurshistory);
			// autoinsert_rowdata("#formAdmHandover",data.nursassessment);
			button_state_admHandover('edit');
		}else{
			button_state_admHandover('add');
		}

	});
}

// screen current patient //
function populate_admHandover_currpt(obj){
	emptyFormdata(errorField,"#formAdmHandover");
	
	// panel header
	$('#name_show_admHandover').text(obj.Name);
	$('#mrn_show_admHandover').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_admHandover').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_admHandover').text(dob_chg(obj.DOB));
	$('#age_show_admHandover').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_admHandover').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_admHandover').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_admHandover').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_admHandover').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_admHandover').text(if_none(obj.areaDesc).toUpperCase());
	
	// formAdmHandover
	$('#mrn_admHandover').val(obj.MRN);
	$("#episno_admHandover").val(obj.Episno);
}

function autoinsert_rowdata(form,rowData){
	$.each(rowData, function (index, value){
		var input = $(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if(input.is("[type=checkbox]")){
			if(value == 1){
				$(form+" [name='"+index+"']").prop('checked', true);
			}
		}else{
			input.val(value);
		}
	});
}

function saveForm_admHandover(callback){
	var saveParam = {
		action: 'save_table_admHandover',
		oper: $("#cancel_admHandover").data('oper')
	}
	var postobj = {
		_token: $('#csrf_token').val(),
	};
	
	values = $("#formAdmHandover").serializeArray();
	
	values = values.concat(
		$('#formAdmHandover input[type=checkbox]:not(:checked)').map(
			function (){
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formAdmHandover input[type=checkbox]:checked').map(
			function (){
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formAdmHandover input[type=radio]:checked').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formAdmHandover select').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	$.post("./admhandover/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function (data){
		
	},'json').fail(function (data){
		// alert('there is an error');
		callback();
	}).success(function (data){
		callback();
	});
}

function textarea_init_admhandover(){
	$('textarea#reasonadm,textarea#diagnosis,textarea#allergy,textarea#medHis,textarea#surgHis,textarea#rtkpcr_remark,textarea#bloodinv_remark,textarea#branula_remark,textarea#scan_remark,textarea#insurance_remark,textarea#medication_remark,textarea#consent_remark,textarea#smoking_remark,textarea#nbm_remark,textarea#report,textarea#medHis').each(function (){
		if(this.value.trim() == ''){
			this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}else{
			this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}
	}).off().on('input', function (){
		if(this.scrollHeight > 40){
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		}else{
			this.style.height = (40) + 'px';
		}
	});
}