
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	
	// $("button.refreshbtn_triage").click(function (){
	// 	empty_formNursing();
	// 	populate_triage_currpt(selrowData('#jqGrid'));
	// });
	
	var fdl = new faster_detail_load();

    disableForm('#formAdmhandover');

	$('#tab_admhandover').on('show.bs.collapse', function () {
		return check_if_user_selected();
	});

	$('#tab_admhandover').on('shown.bs.collapse', function () {

		SmoothScrollTo('#tab_admhandover', 300);

		var saveParam={
      	action:'get_table_admhandover',
    }

    var postobj={
    	_token : $('#_token').val(),
    	mrn:$("#mrn_admhandover").val(),
    	episno:$("#episno_admhandover").val()
    };

    $.post( "./ptcare_admhandover/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).done(function(data){
    	if(!$.isEmptyObject(data.admhandover)){
			if(!emptyobj_(data.admhandover))autoinsert_rowdata("#formAdmhandover",data.admhandover);
			if(!emptyobj_(data.episode))autoinsert_rowdata("#formAdmhandover",data.episode);
			if(!emptyobj_(data.nurshistory))autoinsert_rowdata("#formAdmhandover",data.nurshistory);
			if(!emptyobj_(data.pathealth))autoinsert_rowdata("#formAdmhandover",data.pathealth);
        }else{
		
			if(!emptyobj_(data.admhandover))autoinsert_rowdata("#formAdmhandover",data.admhandover);
        }

    });
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

    $("#jqGridTriageInfo_panel").on("hide.bs.collapse", function(){
    	$("#jqGridTriageInfo_panel > div").scrollTop(0);
    });

	$('#jqGridTriageInfo_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo("#jqGridTriageInfo_panel", 500)	
		sticky_docnotetbl(on=true);
	});

	$('#jqGridTriageInfo_panel').on('hidden.bs.collapse', function () {
		sticky_docnotetbl(on=true);
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

//screen current patient//
function populate_admhandover_currpt(obj){
	emptyFormdata(errorField,"#formAdmhandover");
	//panel header
	$('#name_show_admhandover').text(obj.Name);
	$('#mrn_show_admhandover').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_admhandover').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_admhandover').text(dob_chg(obj.DOB));
	$('#age_show_admhandover').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_admhandover').text(if_none(obj.RaceCode).toUpperCase());
	$('#religion_show_admhandover').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_admhandover').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_admhandover').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_admhandover').text(if_none(obj.AreaCode).toUpperCase());

	$("#mrn_admhandover").val(obj.MRN);
	$("#episno_admhandover").val(obj.Episno);
	
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




