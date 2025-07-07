
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    textare_init_oper_team();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_oper_team');
    
    $("#new_oper_team").click(function (){
        $('#cancel_oper_team').data('oper','add');
        button_state_oper_team('wait');
        enableForm('#form_oper_team');
        rdonly('#form_oper_team');
        // emptyFormdata_div("#form_oper_team",['#mrn_oper_team','#episno_oper_team']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_oper_team").click(function (){
        button_state_oper_team('wait');
        enableForm('#form_oper_team');
        rdonly('#form_oper_team');
        // dialog_mrn_edit.on();
    });
    
    $("#save_oper_team").click(function (){
        if($('#form_oper_team').isValid({requiredFields: ''}, conf, true)){
            saveForm_oper_team(function (data){
                // emptyFormdata_div("#form_oper_team",['#mrn_oper_team','#episno_oper_team']);
                disableForm('#form_oper_team');
            });
        }else{
            enableForm('#form_oper_team');
            rdonly('#form_oper_team');
        }
    });
    
    $("#cancel_oper_team").click(function (){
        // emptyFormdata_div("#form_oper_team",['#mrn_oper_team','#episno_oper_team']);
        disableForm('#form_oper_team');
        button_state_oper_team($(this).data('oper'));
        getdata_oper_team();
        // dialog_mrn_edit.off();
    });
    
    // Op site marked
    $("#operteam_opSite_na").click(function (){
        if($('#operteam_opSite_na').is(":checked")){
            $("input[name='opSite_mark']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='opSite_mark']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_opSite_na").prop("checked", false);
        }
    })
    // Op site marked ends
    
    // GA machine and defib machine
    $("#operteam_machine_na").click(function (){
        if($('#operteam_machine_na').is(":checked")){
            $("input[name='machine_check']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='machine_check']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_machine_na").prop("checked", false);
        }
    })
    // GA machine and defib machine ends
    
    // Haemodynamic monitor
    $("#operteam_monitor_na").click(function (){
        if($('#operteam_monitor_na').is(":checked")){
            $("input[name='monitor_on']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='monitor_on']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_monitor_na").prop("checked", false);
        }
    })
    // Haemodynamic monitor ends
    
    // Difficult airway/aspiration risk
    $("#operteam_difficultAirway_na").click(function (){
        if($('#operteam_difficultAirway_na').is(":checked")){
            $("input[name='difficultAirway']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='difficultAirway']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_difficultAirway_na").prop("checked", false);
        }
    })
    // Difficult airway/aspiration risk ends
    
    // Any GXM/GSH
    $("#operteam_gxmgsh_na").click(function (){
        if($('#operteam_gxmgsh_na').is(":checked")){
            $("input[name='gxmgsh']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='gxmgsh']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_gxmgsh_na").prop("checked", false);
        }
    })
    // Any GXM/GSH ends
    
    // Antibiotic prophylaxis
    $("#operteam_antibioProphy_na").click(function (){
        if($('#operteam_antibioProphy_na').is(":checked")){
            $("input[name='antibioProphy']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='antibioProphy']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_antibioProphy_na").prop("checked", false);
        }
    })
    // Antibiotic prophylaxis ends
    
    // Essential imaging
    $("#operteam_displayImg_na").click(function (){
        if($('#operteam_displayImg_na').is(":checked")){
            $("input[name='displayImg']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='displayImg']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_displayImg_na").prop("checked", false);
        }
    })
    // Essential imaging ends
    
    // Specimen(s) to be labelled
    $("#operteam_specimenlabel_na").click(function (){
        if($('#operteam_specimenlabel_na').is(":checked")){
            $("input[name='specimenlabel']").each(function (){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='specimenlabel']").click(function (){
        if($(this).is(':checked')){
            $("#operteam_specimenlabel_na").prop("checked", false);
        }
    })
    // Specimen(s) to be labelled ends
    
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

button_state_oper_team('empty');
function button_state_oper_team(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_oper_team").removeAttr('data-toggle');
            $('#cancel_oper_team').data('oper','add');
            $('#new_oper_team,#save_oper_team,#cancel_oper_team,#edit_oper_team').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_oper_team").attr('data-toggle','collapse');
            $('#cancel_oper_team').data('oper','add');
            $("#new_oper_team").attr('disabled',false);
            $('#save_oper_team,#cancel_oper_team,#edit_oper_team').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_oper_team").attr('data-toggle','collapse');
            $('#cancel_oper_team').data('oper','edit');
            $("#edit_oper_team").attr('disabled',false);
            $('#save_oper_team,#cancel_oper_team,#new_oper_team').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_oper_team").attr('data-toggle','collapse');
            $("#save_oper_team,#cancel_oper_team").attr('disabled',false);
            $('#edit_oper_team,#new_oper_team').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_oper_team").attr('data-toggle','collapse');
            $('#new_oper_team,#edit_oper_team,#save_oper_team,#cancel_oper_team').attr('disabled',true);
            break;
    }
}

function empty_oper_team(){
    emptyFormdata_div("#form_oper_team");
    button_state_oper_team('empty');
    
    // panel header
    $('#name_show_oper_team').text('');
    $('#mrn_show_oper_team').text('');
    $('#icpssprt_show_oper_team').text('');
    $('#sex_show_oper_team').text('');
    $('#height_show_oper_team').text('');
    $('#weight_show_oper_team').text('');
    $('#dob_show_oper_team').text('');
    $('#age_show_oper_team').text('');
    $('#race_show_oper_team').text('');
    $('#religion_show_oper_team').text('');
    $('#occupation_show_oper_team').text('');
    $('#citizenship_show_oper_team').text('');
    $('#area_show_oper_team').text('');
    $('#ward_show_oper_team').text('');
    $('#bednum_show_oper_team').text('');
    $('#oproom_show_oper_team').text('');
    $('#diagnosis_show_oper_team').text('');
    $('#procedure_show_oper_team').text('');
    $('#unit_show_oper_team').text('');
    $('#type_show_oper_team').text('');
    
    // form_oper_team
    $('#mrn_oper_team').val('');
    $("#episno_oper_team").val('');
}

function populate_oper_team(obj){
    // panel header
    $('#name_show_oper_team').text(obj.pat_name);
    $('#mrn_show_oper_team').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_oper_team').text(obj.icnum);
    $('#sex_show_oper_team').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_oper_team').text(obj.height+' (CM)');
    $('#weight_show_oper_team').text(obj.weight+' (KG)');
    $('#dob_show_oper_team').text(dob_chg(obj.DOB));
    $('#age_show_oper_team').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_oper_team').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_oper_team').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_oper_team').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_oper_team').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_oper_team').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_oper_team').text(obj.ward);
    $('#bednum_show_oper_team').text(obj.bednum);
    $('#oproom_show_oper_team').text(obj.ot_description);
    $('#diagnosis_show_oper_team').text(obj.appt_diag);
    $('#procedure_show_oper_team').text(obj.appt_prcdure);
    $('#unit_show_oper_team').text(obj.op_unit);
    $('#type_show_oper_team').text(obj.oper_type);
    
    // form_oper_team
    $('#mrn_oper_team').val(obj.mrn);
    $("#episno_oper_team").val(obj.latest_episno);
    
    $("#tab_oper_team").collapse('hide');
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

function saveForm_oper_team(callback){
    let oper = $("#cancel_oper_team").data('oper');
    var saveParam = {
        action: 'save_table_oper_team',
        oper: oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#form_oper_team").serializeArray();
    
    values = values.concat(
        $('#form_oper_team input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_oper_team input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_oper_team input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_oper_team select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./oper_team/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_oper_team('edit');
    }).fail(function (data){
        callback(data);
        button_state_oper_team($(this).data('oper'));
    });
}

function textare_init_oper_team(){
    $('textarea#operteam_allergy_remark,textarea#operteam_surgeonReview_remark,textarea#operteam_relative_remark').each(function (){
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

$('#tab_oper_team').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_oper_team', 300, 114);
    
    if($('#mrn_oper_team').val() != ''){
        getdata_oper_team();
    }
});

$('#tab_oper_team').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_oper_team",['#mrn_oper_team','#episno_oper_team']);
    button_state_oper_team('empty');
});

function getdata_oper_team(){
    var urlparam = {
        action: 'get_table_oper_team',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_oper_team').val(),
        episno: $("#episno_oper_team").val()
    };
    
    $.post("./oper_team/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_oper_team('edit');
            autoinsert_rowdata("#form_oper_team",data.otteam);
            if(!emptyobj_(data.iPesakit))$("#operteam_iPesakit").val(data.iPesakit);
        }else{
            button_state_oper_team('add');
        }
        
        textare_init_oper_team();
    });
}

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