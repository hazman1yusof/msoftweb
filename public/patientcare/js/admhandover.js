$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $("button.refreshbtn_admhandover").click(function (){
    //     empty_admhandover_ptcare();
    //     populate_admhandover_currpt(selrowData('#jqGrid'));
    // });
    // populate_admhandover_currpt(selrowData('#jqGrid'));

    disableForm('#formAdmhandover');
    
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

function empty_admhandover_ptcare(obj){
    emptyFormdata(errorField,"#formAdmhandover");
    
    // panel header
    $('#name_show_admhandover').text('');
    $('#mrn_show_admhandover').text('');
    $('#sex_show_admhandover').text('');
    $('#dob_show_admhandover').text('');
    $('#age_show_admhandover').text('');
    $('#race_show_requestFor').text('');
    $('#race_show_admhandover').text('');
    $('#religion_show_admhandover').text('');
	$('#occupation_show_admhandover').text('');
    $('#citizenship_show_admhandover').text('');
    $('#area_show_admhandover').text('');
    
    // formAdmhandover
    $('#mrn_admhandover').val('');
    $("#episno_admhandover").val('');
}

function populate_admhandover_currpt(obj){
    emptyFormdata(errorField,"#formAdmhandover");
    
    // panel header
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
    
    // formAdmhandover
    $("#mrn_admhandover").val(obj.MRN);
	$("#episno_admhandover").val(obj.Episno);
}

function populate_admhandover_getdata(){
    emptyFormdata(errorField,"#formAdmhandover",["#mrn_admhandover","#episno_admhandover"]);
    
    var saveParam = {
        action: 'get_table_admhandover',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_admhandover").val(),
        episno: $("#episno_admhandover").val()
    };
    
    $.get("./ptcare_admhandover/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formAdmhandover",data.admhandover);
			autoinsert_rowdata("#formAdmhandover",data.episode);
			autoinsert_rowdata("#formAdmhandover",data.nurshistory);
			autoinsert_rowdata("#formAdmhandover",data.pathealth);            
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

$('#tab_admhandover').on('shown.bs.collapse', function (){
    populate_admhandover_currpt(selrowData('#jqGrid'));
    SmoothScrollTo("#tab_admhandover", 500);
    populate_admhandover_getdata();
});

$("#tab_admhandover").on("hide.bs.collapse", function (){
    disableForm('#formAdmhandover');
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


