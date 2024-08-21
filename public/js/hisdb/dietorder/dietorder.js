
$(document).ready(function (){
	
	// $('textarea#remark,textarea#remarkkitchen').each(function (){
	// 	this.setAttribute('style', 'height:' + (38) + 'px;min-height:'+ (38) +'px;overflow-y:hidden;');
	// }).on('input', function (){
	// 	this.style.height = 'auto';
	// 	this.style.height = (this.scrollHeight) + 'px';
	// });
	
	disableForm('#formDietOrder');
	
	$("#new_dietOrder").click(function (){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
	});
	
	$("#edit_dietOrder").click(function (){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
	});
	
	$("#save_dietOrder").click(function (){
		disableForm('#formDietOrder');
		if($('#formDietOrder').isValid({requiredFields: ''}, conf, true)){
			saveForm_dietOrder(function (){
				$("#cancel_dietOrder").data('oper','edit');
				$("#cancel_dietOrder").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDietOrder');
			rdonly('#formDietOrder');
		}
	});
	
	$("#cancel_dietOrder").click(function (){
		disableForm('#formDietOrder');
		button_state_dietOrder($(this).data('oper'));
		// dialog_mrn_edit.off();
	});
	
	// Mode of Feeding
	// Radio button with different name but a single selection
	$("input[id=feedingmode]").prop("checked", false);
	$("input[id=feedingmode]:first").prop("checked", true);
	
	$("input[id=feedingmode]").click(function (event){
		$("input[id=feedingmode]").prop("checked", false);
		$(this).prop("checked", true);
		feedingCheck();
		// event.preventDefault();
	});
	
	$("#jqGridDietOrder_panel").on("shown.bs.collapse", function (){
		var saveParam = {
			action: 'get_table_dietorder',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn: $("#mrn_dietOrder").val(),
			episno: $("#episno_dietOrder").val(),
		};
		
		$.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data.dietorder)){
				autoinsert_rowdata("#formDietOrder",data.dietorder);
				autoinsert_rowdata("#formDietOrder",data.episode);
				button_state_dietOrder('edit');
				yesnoCheck();
				feedingCheck();
				textarea_init_dietorder();
			}else{
				autoinsert_rowdata("#formDietOrder",data.episode);
				button_state_dietOrder('add');
				textarea_init_dietorder();
			}
		});
		
		SmoothScrollTo("#jqGridDietOrder_panel", 500);
	});
	
	$("#jqGridDietOrder_panel").on("hide.bs.collapse", function (){
		button_state_dietOrder('empty');
		disableForm('#formDietOrder');
		// $("#jqGridDietOrder_panel > div").scrollTop(0);
	});
	
	$("#preview").click(function (){
		// window.location='./dietorder/table?action=dietorder_preview&mrn='+$('#mrn_dietOrder').val()+'&episno='+$("#episno_dietOrder").val();
		
		window.open('./dietorder/table?action=dietorder_preview&epistycode='+$('#epistycode').val(), '_blank');
	});
	
});

// hide show No of Lodger
function yesnoCheck(){
	if(document.getElementById('yesCheck').checked){
		document.getElementById('ifYes').style.display = 'inline-block';
	}
	else document.getElementById('ifYes').style.display = 'none';
}

// hide show order list
function feedingCheck(){
	if(document.getElementsByName('oral')[0].checked){
		document.getElementById('ifOral').style.display = 'block';
	}
	else document.getElementById('ifOral').style.display = 'none';
}
// hide show order list ends

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

button_state_dietOrder('empty');
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

function populate_dietOrder(obj,rowdata){
	emptyFormdata(errorField,"#formDietOrder");
	
	// panel header
	$('#name_show_dietOrder').text(obj.name);
	$('#mrn_show_dietOrder').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_dietOrder').text(obj.sex);
	$('#dob_show_dietOrder').text(dob_chg(obj.dob));
	$('#age_show_dietOrder').text(obj.age+ ' (YRS)');
	$('#race_show_dietOrder').text(obj.race);
	$('#religion_show_dietOrder').text(if_none(obj.religion));
	$('#occupation_show_dietOrder').text(if_none(obj.occupation));
	$('#citizenship_show_dietOrder').text(obj.citizen);
	$('#area_show_dietOrder').text(obj.area);
	
	// formDietOrder
	$('#mrn_dietOrder').val(obj.mrn);
	$("#episno_dietOrder").val(obj.episno);
	
	var saveParam = {
		action: 'get_table_dietorder',
	}
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: obj.mrn,
		episno: obj.episno
	};
	
	$.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formDietOrder",data.dietorder);
			autoinsert_rowdata("#formDietOrder",data.episode);
			button_state_dietOrder('edit');
			yesnoCheck();
			feedingCheck();
		}else{
			button_state_dietOrder('add');
		}
	});
}

// screen current patient //
function populate_dietOrder_currpt(obj){
	emptyFormdata(errorField,"#formDietOrder");
	
	// panel header
	$('#name_show_dietOrder').text(obj.Name);
	$('#mrn_show_dietOrder').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dietOrder').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_dietOrder').text(dob_chg(obj.DOB));
	$('#age_show_dietOrder').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dietOrder').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dietOrder').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_dietOrder').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_dietOrder').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_dietOrder').text(if_none(obj.areaDesc).toUpperCase());
	
	// formDietOrder
	$('#mrn_dietOrder').val(obj.MRN);
	$("#episno_dietOrder").val(obj.Episno);
	
	// var saveParam = {
	// 	action: 'get_table_dietorder',
	// }
	// var postobj = {
	// 	_token: $('#csrf_token').val(),
	// 	mrn: obj.MRN,
	// 	episno: obj.Episno
	// };
	
	// $.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	// },'json').fail(function (data){
	// 	alert('there is an error');
	// }).success(function (data){
	// 	if(!$.isEmptyObject(data)){
	// 		autoinsert_rowdata("#formDietOrder",data.dietorder);
	// 		autoinsert_rowdata("#formDietOrder",data.episode);
	// 		button_state_dietOrder('edit');
	// 		yesnoCheck();
	// 		feedingCheck();
	// 	}else{
	// 		button_state_dietOrder('add');
	// 	}
	// });
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

function saveForm_dietOrder(callback){
	var saveParam = {
		action: 'save_table_dietOrder',
		oper: $("#cancel_dietOrder").data('oper')
	}
	var postobj = {
		_token: $('#csrf_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formDietOrder").serializeArray();
	
	values = values.concat(
		$('#formDietOrder input[type=checkbox]:not(:checked)').map(
			function (){
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder input[type=checkbox]:checked').map(
			function (){
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder input[type=radio]:checked').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder select').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	$.post("./dietorder/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function (data){
		
	},'json').fail(function (data){
		// alert('there is an error');
		callback();
	}).success(function (data){
		callback();
	});
}

function textarea_init_dietorder(){
	$('textarea#remark,textarea#remarkkitchen').each(function (){
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