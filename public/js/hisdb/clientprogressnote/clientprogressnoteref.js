
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    textarea_init_clientProgNoteRef();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formClientProgNoteRef');
    
    $("#new_clientProgNoteRef").click(function (){
        $('#docalloc_tbl tbody tr').removeClass('active');
        $('#clientprognoteref_date_tbl tbody tr').removeClass('active');
        $('#cancel_clientProgNoteRef').data('oper','add');
        button_state_clientProgNoteRef('wait');
        enableForm('#formClientProgNoteRef');
        rdonly('#formClientProgNoteRef');
        emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
        $('#clientProgNoteRef_datetaken').prop('disabled',false);
    });
    
    $("#edit_clientProgNoteRef").click(function (){
        button_state_clientProgNoteRef('wait');
        enableForm('#formClientProgNoteRef');
        rdonly('#formClientProgNoteRef');
        $('#clientProgNoteRef_datetaken').prop('disabled',true);
    });
    
    $("#save_clientProgNoteRef").click(function (){
        disableForm('#formClientProgNoteRef');
        if($('#formClientProgNoteRef').isValid({requiredFields: ''}, conf, true)){
            saveForm_clientProgNoteRef(function (data){
                // $("#cancel_clientProgNoteRef").click();
                clientprognoteref_date_tbl.ajax.url("./clientprogressnoteref/table?"+$.param(dateParam_clientprognoteref)).load(function (){
                    clientprognoteref_date_tbl.rows().every(function (rowIdx, tableLoop, rowLoop){
                        var currow = this.data();
                        let curr_mrn = currow.mrn;
                        let curr_episno = currow.episno;
                        let curr_date = currow.date;
                        if(curr_mrn == data.mrn && curr_episno == data.episno && curr_date == data.datetime){
                            $(this.node()).addClass('active');
                        }
                    });
                });
            });
        }else{
            enableForm('#formClientProgNoteRef');
            rdonly('#formClientProgNoteRef');
        }
    });
    
    $("#cancel_clientProgNoteRef").click(function (){
        disableForm('#formClientProgNoteRef');
        button_state_clientProgNoteRef($(this).data('oper'));
        $('#docalloc_tbl tbody tr:eq(0)').click(); // to select first row
        $('#clientprognoteref_date_tbl tbody tr:eq(0)').click(); // to select first row
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

button_state_clientProgNoteRef('empty');
function button_state_clientProgNoteRef(state){
    if($('#isdoctor').val() != '1'){
        $("#toggle_clientProgNoteRef").removeAttr('data-toggle');
        $('#cancel_clientProgNoteRef').data('oper','add');
        $('#new_clientProgNoteRef,#save_clientProgNoteRef,#cancel_clientProgNoteRef,#edit_clientProgNoteRef').attr('disabled',true);
        return 0;
    }
    
    switch(state){
        case 'empty':
            $("#toggle_clientProgNoteRef").removeAttr('data-toggle');
            $('#cancel_clientProgNoteRef').data('oper','add');
            $('#new_clientProgNoteRef,#save_clientProgNoteRef,#cancel_clientProgNoteRef,#edit_clientProgNoteRef').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_clientProgNoteRef").attr('data-toggle','collapse');
            $('#cancel_clientProgNoteRef').data('oper','add');
            $("#new_clientProgNoteRef,#current,#past").attr('disabled',false);
            $('#save_clientProgNoteRef,#cancel_clientProgNoteRef,#edit_clientProgNoteRef').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_clientProgNoteRef").attr('data-toggle','collapse');
            $('#cancel_clientProgNoteRef').data('oper','edit');
            $("#edit_clientProgNoteRef,#new_clientProgNoteRef").attr('disabled',false);
            $('#save_clientProgNoteRef,#cancel_clientProgNoteRef').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_clientProgNoteRef").attr('data-toggle','collapse');
            $("#save_clientProgNoteRef,#cancel_clientProgNoteRef").attr('disabled',false);
            $('#edit_clientProgNoteRef,#new_clientProgNoteRef').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_clientProgNoteRef").attr('data-toggle','collapse');
            $('#new_clientProgNoteRef,#edit_clientProgNoteRef,#save_clientProgNoteRef,#cancel_clientProgNoteRef').attr('disabled',true);
            break;
    }
}

var dateParam_clientprognoteref,doctornote_clientprognoteref,curr_obj_clientprognoteref;
//screen current patient//
function populate_clientProgNoteRef_currpt(obj){
    curr_obj_clientprognoteref = obj;
    
    // emptyFormdata(errorField,"#formClientProgNoteRef",["#mrn_clientProgNoteRef","#episno_clientProgNoteRef","#datetime_clientProgNoteRef","#epistycode_clientProgNoteRef","#refdoctor_clientProgNoteRef"]);
    emptyFormdata(errorField,"#formClientProgNoteRef",["#epistycode_clientProgNoteRef"]);
    
    // panel header
    $('#name_show_clientProgNoteRef').text(obj.Name);
    $('#mrn_show_clientProgNoteRef').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_clientProgNoteRef').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_clientProgNoteRef').text(dob_chg(obj.DOB));
    $('#age_show_clientProgNoteRef').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_clientProgNoteRef').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_clientProgNoteRef').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_clientProgNoteRef').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_clientProgNoteRef').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_clientProgNoteRef').text(if_none(obj.areaDesc).toUpperCase());
    
    // formClientProgNoteRef
    $('#mrn_clientProgNoteRef').val(obj.MRN);
    $("#episno_clientProgNoteRef").val(obj.Episno);
    $("#age_clientProgNoteRef").val(dob_age(obj.DOB));
    $('#ptname_clientProgNoteRef').val(obj.Name);
    $('#preg_clientProgNoteRef').val(obj.pregnant);
    $('#ic_clientProgNoteRef').val(obj.Newic);
    $('#doctorname_clientProgNoteRef').val(obj.q_doctorname);
    
    doctornote_clientprognoteref = {
        action: 'get_table_clientprognoteref',
        mrn: obj.MRN,
        episno: obj.Episno,
        datetime: '',
        doctorcode: '',
    };
    
    docalloc_clientprognoteref = {
        action: 'get_docalloc_clientprognoteref',
        mrn: obj.MRN,
        episno: obj.Episno
    }
    
    dateParam_clientprognoteref = {
        action: 'get_datetime_clientprognoteref',
        mrn: obj.MRN,
        episno: obj.Episno,
        doctorcode: '',
    }
    
    button_state_clientProgNoteRef('empty');
    
    // clientprognoteref_date_tbl.ajax.url("./clientprogressnoteref/table?"+$.param(dateParam_clientprognoteref)).load(function (data){
    // 	emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
    // 	$('#clientprognoteref_date_tbl tbody tr:eq(0)').click(); // to select first row
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

function saveForm_clientProgNoteRef(callback){
    if($("#cancel_clientProgNoteRef").data('oper') == 'edit'){
        $('#clientprognoteref_date_tbl').data('editing','true');
    }
    
    var saveParam = {
        action: 'save_table_clientprognoteref',
        oper: $("#cancel_clientProgNoteRef").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formClientProgNoteRef").serializeArray();
    
    values = values.concat(
        $('#formClientProgNoteRef input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formClientProgNoteRef input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formClientProgNoteRef input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formClientProgNoteRef select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./clientprogressnoteref/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        callback(data);
    }).success(function (data){
        callback(data);
    });
}

var docalloc_tbl = $('#docalloc_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'AllocNo'},
        {'data': 'doctorname', 'width': '60%'},
        {'data': 'DoctorCode'},
    ],
    columnDefs: [
        {targets: [0, 1, 2, 4], visible: false},
    ],
    "order": [[ 3, "desc" ]],
    "drawCallback": function (settings){
        if($(this).data('editing') == 'true'){
            $(this).data('editing','false') // tak perlu click kalau edit
            button_state_clientProgNoteRef('edit');
        }else{
            $(this).find('tbody tr')[0].click();
        }
    }
});

var clientprognoteref_date_tbl = $('#clientprognoteref_date_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'date', 'width': '60%'},
        {'data': 'recdatetime'},
        {'data': 'adduser'},
        {'data': 'doctorname', 'width': '30%'},
        {'data': 'doctorcode'},
    ],
    columnDefs: [
        {targets: [0, 1, 3, 4, 6], visible: false},
    ],
    "order": [[ 3, "desc" ]],
    "drawCallback": function (settings){
        if($(this).data('editing') == 'true'){
            $(this).data('editing','false') // tak perlu click kalau edit
            button_state_clientProgNoteRef('edit');
        }else{
            $(this).find('tbody tr')[0].click();
        }
    }
});

var ajaxurl;
$('#jqGridClientProgNoteRef_panel').on('shown.bs.collapse', function (){
    sticky_clientprognotereftbl(on = true);
    docalloc_tbl.ajax.url("./clientprogressnoteref/table?"+$.param(docalloc_clientprognoteref)).load(function (data){
        emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
        $('#docalloc_tbl tbody tr:eq(0)').click(); // to select first row
    });
    SmoothScrollTo("#jqGridClientProgNoteRef_panel", 500);
    textarea_init_clientProgNoteRef();
});

$("#jqGridClientProgNoteRef_panel").on("hide.bs.collapse", function (){
    button_state_clientProgNoteRef('empty');
    disableForm('#formClientProgNoteRef');
});

$('#docalloc_tbl tbody').on('click', 'tr', function (){
    var data = docalloc_tbl.row(this).data();
    // disableForm('#formClientProgNoteRef');
    // console.log($(this).hasClass('selected'));
    
    // if(disable_edit_date()){
    // 	return;
    // }else
    
    if(data == undefined){
        // button_state_clientProgNoteRef('add');
        
        return false;
    }
    
    // to highlight selected row
    if($(this).hasClass('selected')){
        $(this).removeClass('selected');
    }else{
        docalloc_tbl.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
    
    // emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
    $('#docalloc_tbl tbody tr').removeClass('active');
    $(this).addClass('active');
    
    $("#refdoctor_clientProgNoteRef").val(data.DoctorCode);
    
    dateParam_clientprognoteref.mrn = data.mrn;
    dateParam_clientprognoteref.episno = data.episno;
    dateParam_clientprognoteref.doctorcode = data.DoctorCode;
    
    clientprognoteref_date_tbl.ajax.url("./clientprogressnoteref/table?"+$.param(dateParam_clientprognoteref)).load(function (data){
        emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
        $('#clientprognoteref_date_tbl tbody tr:eq(0)').click(); // to select first row
    });
});

$('#clientprognoteref_date_tbl tbody').on('click', 'tr', function (){
    var data = clientprognoteref_date_tbl.row(this).data();
    disableForm('#formClientProgNoteRef');
    // console.log($(this).hasClass('selected'));
    
    // if(disable_edit_date()){
    // 	return;
    // }else
    
    if(data == undefined){
        button_state_clientProgNoteRef('add');
        
        return false;
    }
    
    // to highlight selected row
    if($(this).hasClass('selected')){
        $(this).removeClass('selected');
    }else{
        clientprognoteref_date_tbl.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
    
    emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
    $('#clientprognoteref_date_tbl tbody tr').removeClass('active');
    $(this).addClass('active');
    
    if(check_same_usr_edit(data)){
        button_state_clientProgNoteRef('edit');
    }else{
        button_state_clientProgNoteRef('add');
    }
    
    $('#mrn_clientProgNoteRef').val(data.mrn);
    $("#episno_clientProgNoteRef").val(data.episno);
    $("#datetime_clientProgNoteRef").val(data.recdatetime);
    
    doctornote_clientprognoteref.mrn = data.mrn;
    doctornote_clientprognoteref.episno = data.episno;
    doctornote_clientprognoteref.datetime = data.recdatetime;
    doctornote_clientprognoteref.doctorcode = data.doctorcode;
    
    $.get("./clientprogressnoteref/table?"+$.param(doctornote_clientprognoteref), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            // if(!emptyobj_(data.episode))autoinsert_rowdata("#formClientProgNoteRef",data.episode);
            if(!emptyobj_(data.patprogressnote))autoinsert_rowdata("#formClientProgNoteRef",data.patprogressnote);
            
            textarea_init_clientProgNoteRef();
        }else{
            
        }
    });
});

function disable_edit_date(){
    let disabled = false;
    let newact = $('#new_clientProgNoteRef').attr('disabled');
    let data_oper = $('#cancel_clientProgNoteRef').data('oper');
    
    if(newact == 'disabled' && data_oper == 'add'){
        disabled = true;
    }
    return disabled;
}

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == null){
        same = false;
    }else if(adduser.toUpperCase() != $('#curr_user_clientProgNoteRef').val().toUpperCase()){
        same = false;
    }
    
    return same;
}

function sticky_clientprognotereftbl(on){
    $(window).off('scroll');
    if(on){
        var topDistance = $('#clientprognoteref_date_tbl_sticky').offset().top;
        $(window).on('scroll', function (){
            var scrollTop = $(this).scrollTop();
            var bottomDistance = $('#jqGrid_ordcom_c').offset().top;
            if((topDistance+10) < scrollTop && (bottomDistance-280) > scrollTop){
                $('#clientprognoteref_date_tbl_sticky').addClass( "sticky_div" );
            }else{
                $('#clientprognoteref_date_tbl_sticky').removeClass( "sticky_div" );
            }
        });
    }else{
        $(window).off('scroll');
    }
}

function textarea_init_clientProgNoteRef(){
    $('textarea#clientProgNoteRef_progressnote,textarea#clientProgNote_plan').each(function (){
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

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight < 50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight > 300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }