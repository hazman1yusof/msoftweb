
$(document).ready(function () {

	disableForm('#formAntenatal');

	$("#new_antenatal").click(function(){
		button_state_antenatal('wait');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_antenatal").click(function(){
		button_state_antenatal('wait');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#save_antenatal").click(function(){
		disableForm('#formAntenatal');
		if( $('#formAntenatal').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_antenatal(function(){
				$("#cancel_antenatal").data('oper','edit');
				$("#cancel_antenatal").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formAntenatal');
			rdonly('#formAntenatal');
		}

	});

	$("#cancel_antenatal").click(function(){
		disableForm('#formAntenatal');
		button_state_antenatal($(this).data('oper'));
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

button_state_antenatal('empty');
function button_state_antenatal(state){
	switch(state){
		case 'empty':
			$("#toggle_antenatal").removeAttr('data-toggle');
			$('#cancel_antenatal').data('oper','add');
			$('#new_antenatal,#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','add');
			$("#new_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','edit');
			$("#edit_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#new_antenatal').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$("#save_antenatal,#cancel_antenatal").attr('disabled',false);
			$('#edit_antenatal,#new_antenatal').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_antenatal(obj){	
	emptyFormdata(errorField,"#formAntenatal");

	//panel header
	$('#name_show_antenatal').text(obj.Name);
	$('#mrn_show_antenatal').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_antenatal').text((obj.Sex).toUpperCase());
	$('#dob_show_antenatal').text(dob_chg(obj.DOB));
	$('#age_show_antenatal').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_antenatal').text((obj.raceDesc).toUpperCase());
	$('#religion_show_antenatal').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_antenatal').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_antenatal').text((obj.cityDesc).toUpperCase());
	$('#area_show_antenatal').text((obj.areaDesc).toUpperCase());

	//formAntenatal
	$('#mrn_antenatal').val(obj.MRN);
	$("#episno_antenatal").val(obj.Episno);

	var saveParam={
        action:'get_table_antenatal',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "antenatal/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata_antenatal("#formAntenatal",data.an_pathistory);
			autoinsert_rowdata_antenatal("#formAntenatal",data.an_pathealth);
			button_state_antenatal('edit');
        }else{
			button_state_antenatal('add');
        }

	});
	
}

function autoinsert_rowdata_antenatal(form,rowData){
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

function saveForm_antenatal(callback){
	var saveParam={
        action:'save_table_antenatal',
        oper:$("#cancel_antenatal").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formAntenatal").serializeArray();
	
	values = values.concat(
        $('#formAntenatal input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formAntenatal input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formAntenatal input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formAntenatal select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "/antenatal/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





