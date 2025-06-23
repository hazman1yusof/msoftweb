
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_spinalCord();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formSpinalCord');
    
    $("#new_spinalCord").click(function (){
        $('#cancel_spinalCord').data('oper','add');
        button_state_spinalCord('wait');
        enableForm('#formSpinalCord');
        rdonly('#formSpinalCord');
        emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_spinalCord").value = "";
        // dialog_mrn_edit.on();
        // $('#movementScore').prop('disabled',true);
    });
    
    $("#edit_spinalCord").click(function (){
        button_state_spinalCord('wait');
        enableForm('#formSpinalCord');
        rdonly('#formSpinalCord');
        // dialog_mrn_edit.on();
        // $('#movementScore').prop('disabled',true);
    });
    
    $("#save_spinalCord").click(function (){
        if($('#formSpinalCord').isValid({requiredFields: ''}, conf, true)){
            saveForm_spinalCord(function (data){
                $("#cancel_spinalCord").data('oper','edit');
                $("#cancel_spinalCord").click();
                // emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
                disableForm('#formSpinalCord');
            });
        }else{
            enableForm('#formSpinalCord');
            rdonly('#formSpinalCord');
        }
    });
    
    $("#cancel_spinalCord").click(function (){
        // emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
        disableForm('#formSpinalCord');
        button_state_spinalCord($(this).data('oper'));
        $('#tbl_spinalCord_date').DataTable().ajax.reload();
        getdata_spinalCord();
        // dialog_mrn_edit.off();
    });
    
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
    
    ///////////////////////////////////////spinalCord starts///////////////////////////////////////
    $('#tbl_spinalCord_date tbody').on('click', 'tr', function (){
        var data = tbl_spinalCord_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_spinalCord_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formSpinalCord",['#mrn_physio','#episno_physio']);
        $('#tbl_spinalCord_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_spinalCord('edit');
        }else{
            button_state_spinalCord('add');
        }
        
        // getdata_spinalCord();
        $("#idno_spinalCord").val(data.idno);
        
        var saveParam = {
            action: 'get_table_spinalCord',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./spinalCord/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.spinalcord)){
                autoinsert_rowdata("#formSpinalCord",data.spinalcord);
                // button_state_spinalCord('edit');
            }else{
                // button_state_spinalCord('add');
            }
            
            // textarea_init_spinalCord();
        });
    });
    ////////////////////////////////////////spinalCord ends////////////////////////////////////////
    
    ///////////////////////////////////////////////////////////////////////////////////////////////
    function calculate_spinalCord(){
        var ltr = document.getElementsByClassName('ltr');
        var ppr = document.getElementsByClassName('ppr');
        var motorR = document.getElementsByClassName('motorR');
        var uer = document.getElementsByClassName('uer');
        var ler = document.getElementsByClassName('ler');
        var ltl = document.getElementsByClassName('ltl');
        var ppl = document.getElementsByClassName('ppl');
        var motorL = document.getElementsByClassName('motorL');
        var uel = document.getElementsByClassName('uel');
        var lel = document.getElementsByClassName('lel');
        var ltrTot = 0;
        var pprTot = 0;
        var motorRTot = 0;
        var uerTot = 0;
        var lerTot = 0;
        var ltlTot = 0;
        var pplTot = 0;
        var motorLTot = 0;
        var uelTot = 0;
        var lelTot = 0;
        
        for(var i = 0; i < ltr.length; i++){
            if(parseInt(ltr[i].value))
            ltrTot += parseInt(ltr[i].value);
        }
        
        for(var i = 0; i < ppr.length; i++){
            if(parseInt(ppr[i].value))
            pprTot += parseInt(ppr[i].value);
        }
        
        for(var i = 0; i < motorR.length; i++){
            if(parseInt(motorR[i].value))
            motorRTot += parseInt(motorR[i].value);
        }
        
        for(var i = 0; i < uer.length; i++){
            if(parseInt(uer[i].value))
            uerTot += parseInt(uer[i].value);
        }
        
        for(var i = 0; i < ler.length; i++){
            if(parseInt(ler[i].value))
            lerTot += parseInt(ler[i].value);
        }
        
        for(var i = 0; i < ltl.length; i++){
            if(parseInt(ltl[i].value))
            ltlTot += parseInt(ltl[i].value);
        }
        
        for(var i = 0; i < ppl.length; i++){
            if(parseInt(ppl[i].value))
            pplTot += parseInt(ppl[i].value);
        }
        
        for(var i = 0; i < motorL.length; i++){
            if(parseInt(motorL[i].value))
            motorLTot += parseInt(motorL[i].value);
        }
        
        for(var i = 0; i < uel.length; i++){
            if(parseInt(uel[i].value))
            uelTot += parseInt(uel[i].value);
        }
        
        for(var i = 0; i < lel.length; i++){
            if(parseInt(lel[i].value))
            lelTot += parseInt(lel[i].value);
        }
        
        $("#formSpinalCord input[name=ltrTotal]").val(ltrTot);
        $("#formSpinalCord input[name=pprTotal]").val(pprTot);
        $("#formSpinalCord input[name=motorRTotal]").val(motorRTot);
        $("#formSpinalCord input[name=uer]").val(uerTot);
        $("#formSpinalCord input[name=ler]").val(lerTot);
        $("#formSpinalCord input[name=ltlTotal]").val(ltlTot);
        $("#formSpinalCord input[name=pplTotal]").val(pplTot);
        $("#formSpinalCord input[name=motorLTotal]").val(motorLTot);
        $("#formSpinalCord input[name=uel]").val(uelTot);
        $("#formSpinalCord input[name=lel]").val(lelTot);
        
        // MOTOR SUBSCORES
        var uemsTot = uerTot + uelTot;
        var lemsTot = lerTot + lelTot;
        
        $("#formSpinalCord input[name=uemsTotal]").val(uemsTot);
        $("#formSpinalCord input[name=lemsTotal]").val(lemsTot);
        
        // SENSORY SUBSCORES
        var ltTot = ltrTot + ltlTot;
        var ppTot = pprTot + pplTot;
        
        $("#formSpinalCord input[name=ltr]").val(ltrTot);
        $("#formSpinalCord input[name=ltl]").val(ltlTot);
        $("#formSpinalCord input[name=ltTotal]").val(ltTot);
        
        $("#formSpinalCord input[name=ppr]").val(pprTot);
        $("#formSpinalCord input[name=ppl]").val(pplTot);
        $("#formSpinalCord input[name=ppTotal]").val(ppTot);
    }
    
    $(".calc_spinalCord").change(function (){
        calculate_spinalCord();
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////
    
});

///////////////////////spinalCord starts///////////////////////
var tbl_spinalCord_date = $('#tbl_spinalCord_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'entereddate', 'width': '25%' },
        { 'data': 'dt' },
        { 'data': 'adduser', 'width': '50%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4], visible: false },
    ],
    order: [[4, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
////////////////////////spinalCord ends////////////////////////

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

button_state_spinalCord('empty');
function button_state_spinalCord(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_spinalCord').data('oper','add');
            $('#new_spinalCord,#save_spinalCord,#cancel_spinalCord,#edit_spinalCord').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_spinalCord').data('oper','add');
            $("#new_spinalCord").attr('disabled',false);
            $('#save_spinalCord,#cancel_spinalCord,#edit_spinalCord').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_spinalCord').data('oper','edit');
            $("#new_spinalCord,#edit_spinalCord").attr('disabled',false);
            $('#save_spinalCord,#cancel_spinalCord').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_spinalCord,#cancel_spinalCord").attr('disabled',false);
            $('#edit_spinalCord,#new_spinalCord').attr('disabled',true);
            break;
    }
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

function saveForm_spinalCord(callback){
    let oper = $("#cancel_spinalCord").data('oper');
    var saveParam = {
        action: 'save_table_spinalCord',
        oper: oper,
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val(),
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
    
    values = $("#formSpinalCord").serializeArray();
    
    values = values.concat(
        $('#formSpinalCord input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formSpinalCord input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSpinalCord input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formSpinalCord select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./spinalCord/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_spinalCord('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_spinalCord($(this).data('oper'));
    });
}

function textarea_init_spinalCord(){
    $('textarea#spinalCord_comments').each(function (){
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

function getdata_spinalCord(){
    var urlparam = {
        action: 'get_table_spinalCord',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./spinalCord/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formSpinalCord",data.spinalcord);
            button_state_spinalCord('edit');
        }else{
            button_state_spinalCord('add');
        }
        
        // textarea_init_spinalCord();
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