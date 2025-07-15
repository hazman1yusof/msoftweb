
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formSixMinWalking');
    disableForm('#formBergBalanceTest');
    disableForm('#formMusculoAssessment');
    disableForm('#formPosturalAssessment');
    disableForm('#formOswestryQuest');
    disableForm('#formCardiorespAssessment');
    disableForm('#formNeuroAssessment');
    disableForm('#formMotorScale');
    disableForm('#formSpinalCord');
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    // to limit to two decimal places (onkeypress)
    $(document).on('keydown', 'input[pattern]', function (e){
        var input = $(this);
        var oldVal = input.val();
        var regex = new RegExp(input.attr('pattern'), 'g');
        
        setTimeout(function (){
            var newVal = input.val();
            if(!regex.test(newVal)){
                input.val(oldVal);
            }
        }, 0);
    });
    
    $('#physioTabs .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'sixMinWalking':
                var urlparam_tbl_sixMinWalking = {
                    action: 'get_datetime_sixMinWalking',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_sixMinWalking_date.ajax.url("./sixMinWalking/table?"+$.param(urlparam_tbl_sixMinWalking)).load(function (data){
                    emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
                    $('#tbl_sixMinWalking_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_sixMinWalking_date').DataTable().ajax.reload();
                getdata_sixMinWalking();
                break;
            case 'bergBalanceTest':
                var urlparam_tbl_bergBalanceTest = {
                    action: 'get_datetime_bergBalanceTest',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_bergBalanceTest_date.ajax.url("./bergBalanceTest/table?"+$.param(urlparam_tbl_bergBalanceTest)).load(function (data){
                    emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
                    $('#tbl_bergBalanceTest_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_bergBalanceTest_date').DataTable().ajax.reload();
                getdata_bergBalanceTest();
                break;
            case 'musculoAssessment':
                var urlparam_tbl_musculoAssessment = {
                    action: 'get_datetime_musculoAssessment',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_musculoAssessment_date.ajax.url("./musculoAssessment/table?"+$.param(urlparam_tbl_musculoAssessment)).load(function (data){
                    emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
                    $('#tbl_musculoAssessment_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_musculoAssessment_date').DataTable().ajax.reload();
                getdata_musculoAssessment();
                break;
            case 'posturalAssessment':
                var urlparam_tbl_posturalAssessment = {
                    action: 'get_datetime_posturalAssessment',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_posturalAssessment_date.ajax.url("./posturalAssessment/table?"+$.param(urlparam_tbl_posturalAssessment)).load(function (data){
                    emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
                    $('#tbl_posturalAssessment_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_posturalAssessment_date').DataTable().ajax.reload();
                getdata_posturalAssessment();
                break;
            case 'oswestryQuest':
                var urlparam_tbl_oswestryQuest = {
                    action: 'get_datetime_oswestryQuest',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_oswestryQuest_date.ajax.url("./oswestryQuest/table?"+$.param(urlparam_tbl_oswestryQuest)).load(function (data){
                    emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
                    $('#tbl_oswestryQuest_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_oswestryQuest_date').DataTable().ajax.reload();
                getdata_oswestryQuest();
                break;
            case 'cardiorespAssessment':
                var urlparam_tbl_cardiorespAssessment = {
                    action: 'get_datetime_cardiorespAssessment',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_cardiorespAssessment_date.ajax.url("./cardiorespAssessment/table?"+$.param(urlparam_tbl_cardiorespAssessment)).load(function (data){
                    emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
                    $('#tbl_cardiorespAssessment_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_cardiorespAssessment_date').DataTable().ajax.reload();
                getdata_cardiorespAssessment();
                break;
            case 'neuroAssessment':
                var urlparam_tbl_neuroAssessment = {
                    action: 'get_datetime_neuroAssessment',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_neuroAssessment_date.ajax.url("./neuroAssessment/table?"+$.param(urlparam_tbl_neuroAssessment)).load(function (data){
                    emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
                    $('#tbl_neuroAssessment_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_neuroAssessment_date').DataTable().ajax.reload();
                getdata_neuroAssessment();
                break;
            case 'motorScale':
                var urlparam_tbl_motorScale = {
                    action: 'get_datetime_motorScale',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_motorScale_date.ajax.url("./motorScale/table?"+$.param(urlparam_tbl_motorScale)).load(function (data){
                    emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
                    $('#tbl_motorScale_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_motorScale_date').DataTable().ajax.reload();
                getdata_motorScale();
                break;
            case 'spinalCord':
                var urlparam_tbl_spinalCord = {
                    action: 'get_datetime_spinalCord',
                    mrn: $("#mrn_physio").val(),
                    episno: $("#episno_physio").val()
                }
                
                tbl_spinalCord_date.ajax.url("./spinalCord/table?"+$.param(urlparam_tbl_spinalCord)).load(function (data){
                    emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
                    $('#tbl_spinalCord_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_spinalCord_date').DataTable().ajax.reload();
                getdata_spinalCord();
                break;
        }
    }});
    
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

function empty_physio(){
    emptyFormdata_div("#formPhysiotherapy");
    emptyFormdata_div("#formSixMinWalking");
    emptyFormdata_div("#formBergBalanceTest");
    emptyFormdata_div("#formMusculoAssessment");
    emptyFormdata_div("#formPosturalAssessment");
    emptyFormdata_div("#formOswestryQuest");
    emptyFormdata_div("#formCardiorespAssessment");
    emptyFormdata_div("#formNeuroAssessment");
    emptyFormdata_div("#formMotorScale");
    emptyFormdata_div("#formSpinalCord");
    button_state_sixMinWalking('empty');
    button_state_bergBalanceTest('empty');
    button_state_musculoAssessment('empty');
    button_state_posturalAssessment('empty');
    button_state_oswestryQuest('empty');
    button_state_cardiorespAssessment('empty');
    button_state_neuroAssessment('empty');
    button_state_motorScale('empty');
    button_state_spinalCord('empty');
    
    // panel header
    $('#name_show_physio').text('');
    $('#mrn_show_physio').text('');
    $('#sex_show_physio').text('');
    $('#dob_show_physio').text('');
    $('#age_show_physio').text('');
    $('#race_show_physio').text('');
    $('#religion_show_physio').text('');
    $('#occupation_show_physio').text('');
    $('#citizenship_show_physio').text('');
    $('#area_show_physio').text('');
    
    // formPhysiotherapy
    $('#mrn_physio').val('');
    $("#episno_physio").val('');
}

function populate_physio(obj){
    emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
    emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
    
    // panel header
    $('#name_show_physio').text(obj.Name);
    $('#mrn_show_physio').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_physio').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_physio').text(dob_chg(obj.DOB));
    $('#age_show_physio').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_physio').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_physio').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_physio').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_physio').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_physio').text(if_none(obj.AreaCode).toUpperCase());
    
    // formPhysiotherapy
    $('#mrn_physio').val(obj.MRN);
    $("#episno_physio").val(obj.Episno);
    $("#age_physio").val(dob_age(obj.DOB));
    
    $("#tab_physio").collapse('hide');
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

$('#tab_physio').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_physio', 300, 114);
    $('#physioTabs .top.menu .item').tab('change tab','sixMinWalking');
    
    // to load first tab
    var urlparam_tbl_sixMinWalking = {
        action: 'get_datetime_sixMinWalking',
        mrn: $("#mrn_physio").val(),
        episno: $("#episno_physio").val()
    }
    
    tbl_sixMinWalking_date.ajax.url("./sixMinWalking/table?"+$.param(urlparam_tbl_sixMinWalking)).load(function (data){
        emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
        $('#tbl_sixMinWalking_date tbody tr:eq(0)').click(); // to select first row
    });
    
    getdata_sixMinWalking();
    
    // if($('#mrn_physio').val() != ''){
    //     getdata_sixMinWalking();
    //     getdata_bergBalanceTest();
    //     getdata_musculoAssessment();
    //     getdata_posturalAssessment();
    //     getdata_oswestryQuest();
    //     getdata_cardiorespAssessment();
    //     getdata_neuroAssessment();
    //     getdata_motorScale();
    //     getdata_spinalCord();
    // }
});

$('#tab_physio').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formPhysiotherapy",['#mrn_physio','#episno_physio']);
    button_state_sixMinWalking('empty');
    button_state_bergBalanceTest('empty');
    button_state_musculoAssessment('empty');
    button_state_posturalAssessment('empty');
    button_state_oswestryQuest('empty');
    button_state_cardiorespAssessment('empty');
    button_state_neuroAssessment('empty');
    button_state_motorScale('empty');
    button_state_spinalCord('empty');
});

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == undefined){
        return false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        return false;
    }
    
    return same;
}