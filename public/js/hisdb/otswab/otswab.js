
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

//////////////////////////////////parameter for jqGrid url//////////////////////////////////
var urlParam = {
    action: 'get_table_default',
    url: './util/get_table_default',
    field: '',
    table_name: 'nursing.otswab',
    table_id: 'idno',
    filterCol:['compcode','mrn','episno'],
    filterVal:['session.compcode','',''],
}

$(document).ready(function () {

    // textare_init_otswab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_otswab');
    
    $("#new_otswab").click(function(){
        $('#cancel_otswab').data('oper','add');
        button_state_otswab('wait');
        enableForm('#form_otswab');
        rdonly('#form_otswab');
        // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
        // dialog_mrn_edit.on();
        
    });
    
    $("#edit_otswab").click(function(){
        button_state_otswab('wait');
        enableForm('#form_otswab');
        rdonly('#form_otswab');
        // dialog_mrn_edit.on();
        
    });
    
    $("#save_otswab").click(function(){
        if( $('#form_otswab').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_otswab(function(data){
                // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
                disableForm('#form_otswab');
                
            });
        }else{
            enableForm('#form_otswab');
            rdonly('#form_otswab');
        }
        
    });
    
    $("#cancel_otswab").click(function(){
        // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
        disableForm('#form_otswab');
        button_state_otswab($(this).data('oper'));
        getdata_otswab();
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
    
    ////////////////////////////////////////parameter for saving url////////////////////////////////////////
    var addmore_jqgrid={more:false,state:false,edit:false}
    
    /////////////////////////////////////////////////jqGrid/////////////////////////////////////////////////
    $("#jqGrid").jqGrid({
        datatype: "local",
        editurl: "./otswab/form",
        colModel: [
            { label: 'idno', name: 'idno', width:10, hidden: true, key:true },
            { label: 'compcode', name: 'compcode', width:10, hidden: true },
            { label: 'mrn', name: 'mrn', width:10, hidden: true },
            { label: 'episno', name: 'episno', width:10, hidden: true },
            { label: 'Items', name: 'items', width: 30, editable: true },
            { label: 'Initial Count', name: 'count_initial', width: 15, editable: true },
            { label: 'Additional', name: 'add_1', width: 20, editable: true },
            { label: '1st Count', name: 'count_1st', width: 15, editable: true },
            { label: 'Additional', name: 'add_2', width: 20, editable: true },
            { label: '2nd Count', name: 'count_2nd', width: 15, editable: true },
            { label: 'Additional', name: 'add_3', width: 20, editable: true },
            { label: 'Final Count', name: 'count_final', width: 15, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden:true },
            { label: 'adddate', name: 'adddate', width: 50, hidden:true },
        ],
        autowidth: true,
        multiSort: true,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 900,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPager",
        loadComplete: function(){
            if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
            else{
                $('#jqGrid2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
            
            // calc_jq_height_onchange("jqGrid");
        },
        ondblClickRow: function(rowid, iRow, iCol, e){
            $("#jqGrid_iledit").click();
        },
    });
    
    ///////////////////////////////////////////////////myEditOptions_add///////////////////////////////////////////////////
    var myEditOptions_add = {
        keys: true,
        extraparam:{
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid) {
            $("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
            
            $("input[name='final_count']").keydown(function(e) {//when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_ilsave').click();
                // addmore_jqgrid.state = true;
                // $('#jqGrid_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options) {
            // addmore_jqgrid.more=true; //only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid',urlParam,'add_jqgrid');
            errorField.length=0;
            $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
        },
        errorfunc: function(rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid',urlParam,'add_jqgrid');
        },
        beforeSaveRow: function (options, rowid) {
            $('#p_error').text('');
            
            let data = $('#jqGrid').jqGrid ('getRowData', rowid);
            
            let editurl = "./otswab/form?"+
                $.param({
                    episno:$('#episno_otswab').val(),
                    mrn:$('#mrn_otswab').val(),
                    action: 'addJqgrid_save',
                });
            $("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
            $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
        },
        errorTextFormat: function (data) {
            alert(data);
        }
    };
    
    ///////////////////////////////////////////////////jqGridPager///////////////////////////////////////////////////
    $("#jqGrid").inlineNav('#jqGridPager', {
        add: true,
        edit: true,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add
        },
        editParams: myEditOptions_add
    })
    // .jqGrid('navButtonAdd', "#jqGridPager", {
    //     id: "jqGridPagerDelete",
    //     caption: "", cursor: "pointer", position: "last",
    //     buttonicon: "glyphicon glyphicon-trash",
    //     title: "Delete Selected Row",
    //     onClickButton: function () {
    //         selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
    //         if (!selRowId) {
    //             alert('Please select row');
    //         } else {
    //             var result = confirm("Are you sure you want to delete this row?");
    //             if (result == true) {
    //                 param = {
    //                     _token: $("#csrf_token").val(),
    //                     action: 'addJqgrid_save',
    //                     idno: selrowData('#jqGrid').idno,
    //                 }
    //                 $.post( "./otswab/form?"+$.param(param),{oper:'del'}, function( data ){
                        
    //                 }).fail(function (data) {
    //                     //////////////////errorText(dialog,data.responseText);
    //                 }).done(function (data) {
    //                     refreshGrid("#jqGrid", urlParam);
    //                 });
    //             }else{
    //                 $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
    //             }
    //         }
    //     },
    // })
    .jqGrid('navButtonAdd', "#jqGridPager", {
        id: "jqGridPagerRefresh",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function () {
            refreshGrid("#jqGrid", urlParam);
        },
    });
    
    ///////////////////////////////////////////////////jqGrid ends///////////////////////////////////////////////////
    
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

button_state_otswab('empty');
function button_state_otswab(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_otswab").removeAttr('data-toggle');
            $('#cancel_otswab').data('oper','add');
            $('#new_otswab,#save_otswab,#cancel_otswab,#edit_otswab').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#cancel_otswab').data('oper','add');
            $("#new_otswab").attr('disabled',false);
            $('#save_otswab,#cancel_otswab,#edit_otswab').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#cancel_otswab').data('oper','edit');
            $("#edit_otswab").attr('disabled',false);
            $('#save_otswab,#cancel_otswab,#new_otswab').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $("#save_otswab,#cancel_otswab").attr('disabled',false);
            $('#edit_otswab,#new_otswab').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#new_otswab,#edit_otswab,#save_otswab,#cancel_otswab').attr('disabled',true);
            break;
    }
}

function empty_otswab(){
    emptyFormdata_div("#form_otswab");
    button_state_otswab('empty');
    
    // panel header
    $('#name_show_otswab').text('');
    $('#mrn_show_otswab').text('');
    $('#icpssprt_show_otswab').text('');
    $('#sex_show_otswab').text('');
    $('#height_show_otswab').text('');
    $('#weight_show_otswab').text('');
    $('#dob_show_otswab').text('');
    $('#age_show_otswab').text('');
    $('#race_show_otswab').text('');
    $('#religion_show_otswab').text('');
    $('#occupation_show_otswab').text('');
    $('#citizenship_show_otswab').text('');
    $('#area_show_otswab').text('');
    $('#ward_show_otswab').text('');
    $('#diagnosis_show_otswab').text('');
    $('#procedure_show_otswab').text('');
    $('#unit_show_otswab').text('');
    
    // form_otswab
    $('#mrn_otswab').val('');
    $("#episno_otswab").val('');
}

function populate_otswab(obj){
    // panel header
    $('#name_show_otswab').text(obj.pat_name);
    $('#mrn_show_otswab').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_otswab').text(obj.icnum);
    $('#sex_show_otswab').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_otswab').text(obj.height+' (CM)');
    $('#weight_show_otswab').text(obj.weight+' (KG)');
    $('#dob_show_otswab').text(dob_chg(obj.DOB));
    $('#age_show_otswab').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_otswab').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_otswab').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_otswab').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_otswab').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_otswab').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_otswab').text(obj.ward);
    $('#diagnosis_show_otswab').text(obj.appt_diag);
    $('#procedure_show_otswab').text(obj.appt_prcdure);
    $('#unit_show_otswab').text(obj.op_unit);
    
    // form_otswab
    $('#mrn_otswab').val(obj.mrn);
    $("#episno_otswab").val(obj.latest_episno);
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

function saveForm_otswab(callback){
    let oper = $("#cancel_otswab").data('oper');
    var saveParam={
        action:'save_table_otswab',
        oper:oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj={
        _token : $('#_token').val(),
        // sex_edit : $('#sex_edit').val(),
        // idtype_edit : $('#idtype_edit').val()
    };
    
    values = $("#form_otswab").serializeArray();
    
    values = values.concat(
        $('#form_otswab input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post( "./otswab/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').done(function(data) {
        callback(data);
        button_state_otswab('edit');
    }).fail(function(data){
        callback(data);
        button_state_otswab($(this).data('oper'));
    });
}

function textare_init_otswab(){
    $('textarea#basicset,textarea#spplmtryset,textarea#issue_occur,textarea#actual_oper,textarea#specimensent').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function () {
        if(this.scrollHeight>40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

$('#tab_otswab').on('shown.bs.collapse', function () {
    SmoothScrollTo('#tab_otswab', 300);
    $("#jqGrid").jqGrid ('setGridWidth', Math.floor($("#jqGrid_c")[0].offsetWidth-$("#jqGrid_c")[0].offsetLeft-14));
    
    if($('#mrn_otswab').val() != ''){
        getdata_otswab();
    }
});

$('#tab_otswab').on('hide.bs.collapse', function () {
    emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
    button_state_otswab('empty');
});

function getdata_otswab(){
    var urlparam={
        action:'get_table_otswab',
    }
    
    var postobj={
        _token : $('#_token').val(),
        mrn:$('#mrn_otswab').val(),
        episno:$("#episno_otswab").val()
    };
    
    $.post( "./otswab/form?"+$.param(urlparam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).done(function(data){
        if(!$.isEmptyObject(data)){
            button_state_otswab('edit');
            autoinsert_rowdata("#form_otswab",data.otswab);
            // textare_init_otswab();
            // refreshGrid('#jqGrid',urlParam,'add_jqgrid');
        }else{
            button_state_otswab('add');
            // textare_init_otswab();
            // refreshGrid('#jqGrid',urlParam,'add_jqgrid');
        }
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
