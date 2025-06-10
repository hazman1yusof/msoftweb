$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $("button.refreshbtn_admhandover").click(function (){
    //     empty_admhandover_ptcare();
    //     populate_admhandover_currpt(selrowData('#jqGrid'));
    // });
    // populate_admhandover_currpt(selrowData('#jqGrid'));

    disableForm('#formAdmHandover');

    // $("#new_admHandover").click(function (){
	// 	button_state_admHandover('wait');
	// 	enableForm('#formAdmHandover');
	// 	rdonly('#formAdmHandover');
	// });
    
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
				button_state_admHandover('edit');

			});
		}
	});

    $("#cancel_admHandover").click(function (){
		disableForm('#formAdmHandover');
		button_state_admHandover($(this).data('oper'));
	});

	/////////////////////////////////print button starts////////////////////////////////////////////

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
			$('#save_admHandover,#cancel_admHandover,#edit_admHandover,#admhandover_report').attr('disabled',true);
			break;
		// case 'add':
		// 	$("#toggle_admHandover").attr('data-toggle','collapse');
		// 	$('#cancel_admHandover').data('oper','add');
		// 	$("#new_admHandover").attr('disabled',false);
		// 	$('#save_admHandover,#cancel_admHandover,#edit_admHandover').attr('disabled',true);
		// 	break;
		case 'edit':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$('#cancel_admHandover').data('oper','edit');
			$("#edit_admHandover").attr('disabled',false);
			$('#save_admHandover,#cancel_admHandover').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_admHandover").attr('data-toggle','collapse');
			$("#save_admHandover,#cancel_admHandover,#admhandover_report").attr('disabled',false);
			$('#edit_admHandover').attr('disabled',true);
			break;
	}
	
}

function empty_admhandover_ptcare(obj){
    emptyFormdata(errorField,"#formAdmHandover");
    
    // panel header
    $('#name_show_admHandover').text('');
    $('#mrn_show_admHandover').text('');
    $('#sex_show_admHandover').text('');
    $('#dob_show_admHandover').text('');
    $('#age_show_admHandover').text('');
    $('#race_show_admHandover').text('');
    $('#religion_show_admHandover').text('');
	$('#occupation_show_admHandover').text('');
    $('#citizenship_show_admHandover').text('');
    $('#area_show_admhandover').text('');
    
    // formAdmHandover
    $('#mrn_admHandover').val('');
    $("#episno_admHandover").val('');
}

function populate_admhandover_currpt(obj){
    emptyFormdata(errorField,"#formAdmHandover");
    
    // panel header
    $('#name_show_admHandover').text(obj.Name);
	$('#mrn_show_admHandover').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_admHandover').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_admHandover').text(dob_chg(obj.DOB));
	$('#age_show_admHandover').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_admHandover').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_admHandover').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_admHandover').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_admHandover').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_admHandover').text(if_none(obj.AreaCode).toUpperCase());
    
    // formAdmHandover
    $("#mrn_admHandover").val(obj.MRN);
	$("#episno_admHandover").val(obj.Episno);
}

function populate_admhandover_getdata(){
    emptyFormdata(errorField,"#formAdmHandover",["#mrn_admHandover","#episno_admHandover"]);
    
    var saveParam = {
        action: 'get_table_admhandover',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_admHandover").val(),
        episno: $("#episno_admHandover").val()
    };
    
    $.get("./ptcare_admhandover/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formAdmHandover",data.admhandover);
			autoinsert_rowdata("#formAdmHandover",data.episode);
			autoinsert_rowdata("#formAdmHandover",data.nurshistory);
			autoinsert_rowdata("#formAdmHandover",data.pathealth);      
            button_state_admHandover('edit');      
        }else{
        }
        
    });
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
        }else if(input.is("textarea")){
            if(value !== null){
                let newval = value.replaceAll("</br>",'\n');
                input.val(newval);
            }
        }else{
            input.val(value);
        }
    });
}

function saveForm_admHandover(callback){
	var saveParam = {
		action: 'save_table_admHandover',
		oper: $("#cancel_admHandover").data('oper'),
		mrn: $("#mrn_admHandover").val(),
        episno: $("#episno_admHandover").val()
	}
	var postobj = {
		_token: $('#_token').val(),
		takeoverby: $("#takeoverby").val(),

	};
	
	// values = $("#formAdmHandover").serializeArray();
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=checkbox]:not(:checked)').map(
	// 		function (){
	// 			return {"name": this.name, "value": 0}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=checkbox]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": 1}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover input[type=radio]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	// values = values.concat(
	// 	$('#formAdmHandover select').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	$.post("./ptcare_admhandover/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		callback(data);
		// button_state_admHandover('empty');      
		populate_admhandover_getdata();

	}).fail(function (data){
		callback(data);
	});
}

$('#tab_admHandover').on('shown.bs.collapse', function (){
    // populate_admhandover_currpt(selrowData('#jqGrid'));
    SmoothScrollTo("#tab_admHandover", 500);
    populate_admhandover_getdata();
    rdonly('#formAdmHandover');

});

$("#tab_admHandover").on("hide.bs.collapse", function (){
    disableForm('#formAdmHandover');
});

// to format number input to two decimal places (0.00)
$(".floatNumberField").change(function() {
	$(this).val(parseFloat($(this).val()).toFixed(2));
});	

// to autocheck the checkbox bila fill in textarea
$("#drugs_remarks").on("keyup blur", function () {
	$("#allergydrugs").prop("checked", this.value !== "");
});

$("#food_remarks").on("keyup blur", function () {
	$("#allergyfood").prop("checked", this.value !== "");
});

$("#others_remarks").on("keyup blur", function () {
	$("#allergyothers").prop("checked", this.value !== "");
});

$("#environment_remarks").on("keyup blur", function () {
	$("#allergyenvironment").prop("checked", this.value !== "");
});

$("#plaster_remarks").on("keyup blur", function () {
	$("#allergyplaster").prop("checked", this.value !== "");
});

$("#unknown_remarks").on("keyup blur", function () {
	$("#allergyunknown").prop("checked", this.value !== "");
});

$("#none_remarks").on("keyup blur", function () {
	$("#allergynone").prop("checked", this.value !== "");
});


