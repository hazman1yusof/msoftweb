
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {

    textare_init_preoperative();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_preoperativeDC');
    
    $("#new_preoperativeDC").click(function(){
        $('#cancel_preoperativeDC').data('oper','add');
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
        // dialog_mrn_edit.on();
        
    });
    
    $("#edit_preoperativeDC").click(function(){
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
        // dialog_mrn_edit.on();
        
    });
    
    $("#save_preoperative").click(function(){
        if( $('#form_preoperativeDC').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_preoperativeDC(function(data){
                // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
                disableForm('#form_preoperativeDC');
                
            });
        }else{
            enableForm('#form_preoperativeDC');
            rdonly('#form_preoperativeDC');
        }
        
    });
    
    $("#cancel_preoperativeDC").click(function(){
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
        disableForm('#form_preoperativeDC');
        button_state_preoperativeDC($(this).data('oper'));
        getdata_preoperative();
        // dialog_mrn_edit.off();
        
    });
    
    // $("#side_op_na").change(function(){
    //     $('input[name="side_op_mark"]').removeAttr("checked");
    // });
    
    $("#side_op_na").click(function(){
        if($('#side_op_na').is(":checked")){
            $("input[name='side_op_mark']").each(function(){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='side_op_mark']").click(function(){
        if($(this).is(':checked')){
            $("#side_op_na").prop("checked", false);
        }
    })
    
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

button_state_preoperativeDC('empty');
function button_state_preoperativeDC(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_preoperative").removeAttr('data-toggle');
            $('#cancel_preoperativeDC').data('oper','add');
            $('#new_preoperativeDC,#save_preoperative,#cancel_preoperativeDC,#edit_preoperativeDC,#btn_anaesthetist,#btn_surgeon,#btn_asstsurgeon').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','add');
            $("#new_preoperativeDC").attr('disabled',false);
            $('#save_preoperative,#cancel_preoperativeDC,#edit_preoperativeDC,#btn_anaesthetist,#btn_surgeon,#btn_asstsurgeon').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','edit');
            $("#edit_preoperativeDC").attr('disabled',false);
            $('#save_preoperative,#cancel_preoperativeDC,#new_preoperativeDC,#btn_anaesthetist,#btn_surgeon,#btn_asstsurgeon').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $("#save_preoperative,#cancel_preoperativeDC,#btn_anaesthetist,#btn_surgeon,#btn_asstsurgeon").attr('disabled',false);
            $('#edit_preoperativeDC,#new_preoperativeDC').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#new_preoperativeDC,#edit_preoperativeDC,#save_preoperative,#cancel_preoperativeDC').attr('disabled',true);
            break;
    }
}

function empty_preoperativeDC(){
    emptyFormdata_div("#form_preoperativeDC");
    button_state_preoperativeDC('empty');
    
    // panel header
    $('#name_show_preoperativeDC').text('');
    $('#mrn_show_preoperativeDC').text('');
    $('#icpssprt_show_preoperativeDC').text('');
    $('#sex_show_preoperativeDC').text('');
    $('#height_show_preoperativeDC').text('');
    $('#weight_show_preoperativeDC').text('');
    $('#dob_show_preoperativeDC').text('');
    $('#age_show_preoperativeDC').text('');
    $('#race_show_preoperativeDC').text('');
    $('#religion_show_preoperativeDC').text('');
    $('#occupation_show_preoperativeDC').text('');
    $('#citizenship_show_preoperativeDC').text('');
    $('#area_show_preoperativeDC').text('');
    $('#ward_show_preoperativeDC').text('');
    $('#bednum_show_preoperativeDC').text('');
    $('#oproom_show_preoperativeDC').text('');
    $('#diagnosis_show_preoperativeDC').text('');
    $('#procedure_show_preoperativeDC').text('');
    $('#unit_show_preoperativeDC').text('');
    $('#type_show_preoperativeDC').text('');
    
    // form_preoperativeDC
    $('#mrn_preoperativeDC').val('');
    $("#episno_preoperativeDC").val('');
}

function populate_preoperativeDC(obj){
    // panel header
    $('#name_show_preoperativeDC').text(obj.pat_name);
    $('#mrn_show_preoperativeDC').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_preoperativeDC').text(obj.icnum);
    $('#sex_show_preoperativeDC').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_preoperativeDC').text(obj.height+' (CM)');
    $('#weight_show_preoperativeDC').text(obj.weight+' (KG)');
    $('#dob_show_preoperativeDC').text(dob_chg(obj.DOB));
    $('#age_show_preoperativeDC').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_preoperativeDC').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_preoperativeDC').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_preoperativeDC').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_preoperativeDC').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_preoperativeDC').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_preoperativeDC').text(obj.ward);
    $('#bednum_show_preoperativeDC').text(obj.bednum);
    $('#oproom_show_preoperativeDC').text(obj.ot_description);
    $('#diagnosis_show_preoperativeDC').text(obj.appt_diag);
    $('#procedure_show_preoperativeDC').text(obj.appt_prcdure);
    $('#unit_show_preoperativeDC').text(obj.op_unit);
    $('#type_show_preoperativeDC').text(obj.oper_type);
    
    // form_preoperativeDC
    $('#mrn_preoperativeDC').val(obj.mrn);
    $("#episno_preoperativeDC").val(obj.latest_episno);
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

function saveForm_preoperativeDC(callback){
    let oper = $("#cancel_preoperativeDC").data('oper');
    var saveParam={
        action:'save_table_preoperative',
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
    
    values = $("#form_preoperativeDC").serializeArray();
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post( "./preoperativeDC/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').done(function(data) {
        callback(data);
        button_state_preoperativeDC('edit');
    }).fail(function(data){
        callback(data);
        button_state_preoperativeDC($(this).data('oper'));
    });
}

function textare_init_preoperative(){
    $('textarea#pat_remark,textarea#cons_remark,textarea#check_side_remark,textarea#side_op_remark,textarea#lastmeal_remark,textarea#check_item_remark,textarea#allergies_remark,textarea#implant_remark,textarea#premed_remark,textarea#blood_remark,textarea#casenotes_remark,textarea#oldnotes_remark,textarea#imaging_remark,textarea#vs_remark,textarea#others_remark,textarea#imprtnt_issues').each(function () {
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

$('#tab_preoperative').on('shown.bs.collapse', function () {
    SmoothScrollTo('#tab_preoperative', 300,114);
    
    if($('#mrn_preoperativeDC').val() != ''){
        getdata_preoperative();
    }
});

$('#tab_preoperative').on('hide.bs.collapse', function () {
    emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
    button_state_preoperativeDC('empty');
});

function getdata_preoperative(){
    var urlparam={
        action:'get_table_preoperative',
    }
    
    var postobj={
        _token : $('#_token').val(),
        mrn:$('#mrn_preoperativeDC').val(),
        episno:$("#episno_preoperativeDC").val()
    };
    
    $.post( "./preoperativeDC/form?"+$.param(urlparam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).done(function(data){
        if(!$.isEmptyObject(data)){
            button_state_preoperativeDC('edit');
            autoinsert_rowdata("#form_preoperativeDC",data.preop);
            textare_init_preoperative();
        }else{
            button_state_preoperativeDC('add');
            textare_init_preoperative();
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

var textfield_modal = new textfield_modal();
textfield_modal.ontabbing();
textfield_modal.checking();
textfield_modal.clicking();

function textfield_modal(){
    this.textfield_array = ['#info_anaesthetist','#info_surgeon','#info_asstsurgeon'];
    
    this.ontabbing = function(){
        $("#info_anaesthetist,#info_surgeon,#info_asstsurgeon").on('keydown',{data:this},onTab);
    }
    
    this.checking = function(){
        $("#info_anaesthetist,#info_surgeon,#info_asstsurgeon").on('blur',{data:this},onCheck);
    }
    
    this.clicking = function(){
        $("#btn_anaesthetist,#btn_surgeon,#btn_asstsurgeon").on('click',{data:this},onClick);
    }
    
    this.dontcheck = false;
    
    function onTab(event){
        var obj = event.data.data;
        var textfield = $(event.currentTarget);
        var id_ = textfield.attr('id');
        var id_use = id_.substring(id_.indexOf("_")+1);
        
        if(event.key == "Tab" && textfield.val() != ""){
            $('#mdl_item_selector').modal('show');
            pop_item_select(id_use,true,textfield.val(),obj);
            obj.dontcheck = true;
        }
    }
    
    function onClick(event){
        var obj = event.data.data;
        var textfield = $(event.currentTarget);
        var id_ = textfield.attr('id');
        var id_use = id_.substring(id_.indexOf("_")+1);
        
        $('#mdl_item_selector').modal('show');
        pop_item_select(id_use,false,textfield.val(),obj);
        obj.dontcheck = true;
    }
    
    function get_url(type){
        let act = null;
        switch (type){
            case "anaesthetist":
                act = "get_reg_doctor";
                break;
            case "surgeon":
                act = "get_reg_doctor";
                break;
            case "asstsurgeon":
                act = "get_reg_doctor";
                break;
        }
        return act;
    }
    
    function onCheck(event){
        var obj = event.data.data;
        if(!obj.dontcheck){
            var textfield = $(event.currentTarget);
            var search = textfield.val();
            var id_ = textfield.attr('id');
            var id_use = id_.substring(id_.indexOf("_")+1);
            
            var act = get_url(id_use);
            if(search.trim() != ""){
                $.get( "./preoperativeDC/get_entry?action="+act+"&search="+search, function( data ) {
                    
                },'json').done(function(data) {
                    if(!$.isEmptyObject(data) && data.data!=null){
                        myerrorIt_only('#'+id_,false);
                    }else{
                        myerrorIt_only('#'+id_,true);
                    }
                });
            }
        }
        obj.dontcheck = false;
    }
    
    function pop_item_select(type,ontab=false,text_val,obj){
        var act = null;
        var id = id;
        var rowid = rowid;
        var selecter = null;
        var title="Item selector";
        var mdl = null;
        var text_val = $('input#'+id).val();
        
        act = get_url(type);
        
        $('#mdl_item_selector').modal({
            'closable':false,
            onHidden : function(){
                $('#tbl_item_select').html('');
                selecter.destroy();
            },
        }).modal('show');
        $('body,#mdl_item_selector').addClass('scrolling');
        
        selecter = $('#tbl_item_select').DataTable( {
                "ajax": "./preoperativeDC/get_entry?action=" + act,
                "ordering": false,
                "lengthChange": false,
                "info": true,
                "pagingType" : "numbers",
                "search": {
                    "smart": true,
                    "search": text_val
                },
                "columns": [
                    {'data': 'code'},
                    {'data': 'description'}
                ],
                "columnDefs": [ {
                    "width": "20%",
                    "targets": 0,
                    "data": "code",
                    "render": function ( data, type, row, meta ) {
                        return data;
                    }
                } ],
                "initComplete": function(oSettings, json) {
                    delay(function(){
                        $('div.dataTables_filter input', selecter.table().container()).get(0).focus();
                    }, 10 );
                },
        });
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        $('#tbl_item_select tbody').on('dblclick', 'tr', function () {
            item = selecter.row( this ).data();
            $('input[name=desc_'+type+']').val(item["description"]);
            $('input[name=info_'+type+']').val(item["code"]);
            $('#mdl_item_selector').modal('hide');
        });
    }
}

function loading_desc_epis(obj){ //loading description dia sebab save code dia je
    this.code_fields=obj;
    this.anaesthetist={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.surgeon={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.asstsurgeon={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.load_desc = function(){
        load_for_desc(this,'anaesthetist','preoperativeDC/get_entry?action=get_reg_doctor');
        load_for_desc(this,'surgeon','preoperativeDC/get_entry?action=get_reg_doctor');
        load_for_desc(this,'asstsurgeon','preoperativeDC/get_entry?action=get_reg_doctor');
    }
    
    function load_for_desc(selobj,id,url){
        let storage_name = 'fastload_bio_'+id;
        let storage_obj = localStorage.getItem(storage_name);
        
        if(!storage_obj){
            $.ajaxSetup({async: false});
            $.get( url, function( data ) {
                
            },'json').done(function(data) {
                if(!$.isEmptyObject(data)){
                    selobj[id].data = data.data;
                    
                    let desc = data.data;
                    let now = moment();
                    
                    var json = JSON.stringify({
                        'description':desc,
                        'timestamp': now
                    });
                    
                    localStorage.setItem(storage_name,json);
                }
            });
        }else{
            let obj_stored = {
                'json':JSON.parse(storage_obj),
            }
            
            selobj[id].data = obj_stored.json.description
            
            //remove storage after 7 days
            let moment_stored = obj_stored.json.timestamp;
            if(moment().diff(moment(moment_stored),'days') > 7){
                localStorage.removeItem(storage_name);
            }
        }
    }
    
    this.write_desc = function(){
        self=this;
        obj.forEach(function(elem){
            if($(elem.code).val().trim() != ""){
                $(elem.desc).val(self.get_desc($(elem.code).val(),elem.id,elem.desc));
            }
        });
    }
    
    this.get_desc = function(search_code,id,inp){
        let code_ = this[id].code;
        let desc_ = this[id].desc;
        let retdata="";
        
        retdata = this[id].data.find(function(obj){
            return obj[code_] == search_code;
        });
        
        if(retdata == undefined){
            if(search_code.trim() != ''){
                myerrorIt_only(inp,true);
                return search_code;
            }else{
                myerrorIt_only(inp,false);
                return '';
            }
        }else{
            return retdata[desc_];
        }
    }
}
