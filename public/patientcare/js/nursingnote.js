
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $("button.refreshbtn_nursNote").click(function (){
    //     empty_nursingnote_ptcare();
    //     populate_nursingnote_ptcare(selrowData('#jqGrid'));
    // });
    
    $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formNursNote');
    
    ////////////////////////////////////////////progressnote starts////////////////////////////////////////////
    disableForm('#formProgress');
    
    $("#new_progress").click(function (){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        // document.getElementById("idno_progress").value = "";
    });
    
    $("#edit_progress").click(function (){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        $("#datetaken,#timetaken").attr("readonly", true);
    });

    $("#save_progress").click(function (){
        disableForm('#formProgress');
        if($('#formProgress').isValid({requiredFields: ''}, conf, true)){
            saveForm_progress(function (){
                $("#cancel_progress").data('oper','edit');
                $("#cancel_progress").click();
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress');
            rdonly('#formProgress');
        }
    });
    
    $("#cancel_progress").click(function (){
        disableForm('#formProgress');
        button_state_progress($(this).data('oper'));
        // $('#datetime_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////////////progressnote ends//////////////////////////////////////////////  
    
    // $tabs = $('#requestFor .menu .item');
    
    // $tabs.tab({
    //     onVisible: function (tabPath){
    //         console.log("test");
    //     }
    // });
    
    // $tabs.first().tab('change tab', 'otbookReqFor');
    
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

button_state_progress('empty');
function button_state_progress(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_progress').data('oper','add');
            $('#new_progress,#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','add');
            $("#new_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','edit');
            $("#new_progress,#edit_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress,#cancel_progress").attr('disabled',false);
            $('#edit_progress,#new_progress').attr('disabled',true);
            break;
    }
}

function empty_nursingnote_ptcare(obj){
    emptyFormdata(errorField,"#formNursNote");
    
    // panel header
    $('#name_show_nursNote').text('');
    $('#mrn_show_nursNote').text('');
    $('#sex_show_nursNote').text('');
    $('#dob_show_nursNote').text('');
    $('#age_show_nursNote').text('');
    $('#race_show_nursNote').text('');
    $('#religion_show_nursNote').text('');
    $('#occupation_show_nursNote').text('');
    $('#citizenship_show_nursNote').text('');
    $('#area_show_nursNote').text('');
    
    // formNursNote
    $('#mrn_nursNote').val('');
    $("#episno_nursNote").val('');
    $('#ptname_nursNote').val('');
    $('#preg_nursNote').val('');
    $('#ic_nursNote').val('');
    $('#doctorname_nursNote').val('');
}

function populate_nursingnote_ptcare(obj){
    emptyFormdata(errorField,"#formNursNote");
    
    // panel header
    $('#name_show_nursNote').text(obj.Name);
    $('#mrn_show_nursNote').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_nursNote').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_nursNote').text(dob_chg(obj.DOB));
    $('#age_show_nursNote').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_nursNote').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_nursNote').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_nursNote').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_nursNote').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_nursNote').text(if_none(obj.areaDesc).toUpperCase());
    
    // formNursNote
    $('#mrn_nursNote').val(obj.MRN);
    $("#episno_nursNote").val(obj.Episno);
    $("#doctor_nursNote").val(dob_age(obj.DOB));
    $('#ptname_nursNote').val(obj.Name);
    $('#preg_nursNote').val(obj.pregnant);
    $('#ic_nursNote').val(obj.Newic);
    $('#doctorname_nursNote').val(obj.doctorname);
}

function populate_progressnote_getdata(){
    emptyFormdata(errorField,"#formProgress",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_progress',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_otbook").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.get("./ptcare_nursingnote/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress",data.nurshandover);
            $("#datetaken").val(data.date);
            
            button_state_progress('edit');
        }else{
            button_state_progress('add');
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

function saveForm_progress(callback){
    var saveParam = {
        action: 'save_table_progress',
        oper: $("#cancel_progress").data('oper'),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_nursNote').val(),
        episno: $("#episno_nursNote").val(),
        epistycode: $("#epistycode").val()
    };
    
    values = $("#formProgress").serializeArray();
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

$('#tab_nursNote').on('shown.bs.collapse', function (){
    SmoothScrollTo("#tab_nursNote", 500);
    
    // let curtype = $(this).data('curtype');
    // $('#requestFor.menu a#'+curtype).tab('show');
    
    populate_progressnote_getdata();
});

$("#tab_nursNote").on("hide.bs.collapse", function (){
    // button_state_requestFor('empty');
    disableForm('#formNursNote');
    disableForm('#formProgress');
});

function textarea_init_otbookReqFor(){
    $('textarea#otReqFor_remarks').each(function (){
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


