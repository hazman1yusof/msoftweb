$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    //////////////////////////////////////barthel starts//////////////////////////////////////

    disableForm('#formOccupTherapyBarthel');
    
    $("#new_barthel").click(function (){
        $('#cancel_barthel').data('oper','add');
        button_state_barthel('wait');
        enableForm('#formOccupTherapyBarthel');
        rdonly('#formOccupTherapyBarthel');
        emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);

        document.getElementById("idno_barthel").value = "";
    });
    
    $("#edit_barthel").click(function (){
        button_state_barthel('wait');
        enableForm('#formOccupTherapyBarthel');
        rdonly('#formOccupTherapyBarthel');
        $("#dateAssessment, #timeAssessment").attr("readonly", true);

    });
    
    $("#save_barthel").click(function (){
        disableForm('#formOccupTherapyBarthel');
        if($('#formOccupTherapyBarthel').isValid({requiredFields: ''}, conf, true)){
            saveForm_barthel(function (data){
                $("#cancel_barthel").data('oper','edit');
                $("#cancel_barthel").click();
                $('#datetimeBarthel_tbl').DataTable().ajax.reload();            
            });
        }else{
            enableForm('#formOccupTherapyBarthel');
            rdonly('#formOccupTherapyBarthel');
        }
    });
    
    $("#cancel_barthel").click(function (){
        // emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);
        disableForm('#formOccupTherapyBarthel');
        button_state_barthel($(this).data('oper'));
        // getdata_barthel();
        // dialog_mrn_edit.off();
    });

    //////////////////////////////////////barthel ends//////////////////////////////////////
    
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

    ////////////////////////////////////////barthel starts////////////////////////////////////////
    $('#datetimeBarthel_tbl tbody').on('click', 'tr', function (){
        var data = datetimeBarthel_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimeBarthel_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);
        $('#datetimeBarthel_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_barthel").val(data.idno);
        
        var saveParam={
            action: 'get_table_barthel',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_barthel/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyBarthel",data.barthel);

                button_state_barthel('edit');
            }else{
                button_state_barthel('add');
            }
        });
    });

    function calc_tot_score(){
		var score = 0;
		$(".score:checked").each(function(){
			score+=parseInt($(this).val(),10);
		});
		$("input[name=tot_score]").val(score)
	}
	$().ready(function(){
		$(".score").change(function(){
			calc_tot_score();
            interpretation();
		});
	});

    function interpretation(){
		var score = $("input[name=tot_score]").val();
		
        if ((score >= 0) && (score <= 20)) {
            $("input[name=interpretation]").val('TOTAL DEPENDENCE')

        } else if ((score >= 21) && (score <= 60)) {
            $("input[name=interpretation]").val('SEVERE DEPENDENCE')

        } else if ((score >= 61) && (score <= 90)) {
            $("input[name=interpretation]").val('MODERATE DEPENDENCE')

        } else if ((score >= 91) && (score <= 99)) {
            $("input[name=interpretation]").val('SLIGHT DEPENDENCE')

        } else {
            $("input[name=interpretation]").val('INDEPENDENCE')

        }
	}
	
});

/////////////////////barthel starts/////////////////////
var datetimeBarthel_tbl = $('#datetimeBarthel_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateAssessment', 'width': '20%' },
        { 'data': 'timeAssessment', 'width': '20%' },

    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////barthel ends//////////////////////

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

button_state_barthel('empty');
function button_state_barthel(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_barthel').data('oper','add');
            $('#new_barthel,#save_barthel,#cancel_barthel,#edit_barthel').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_barthel').data('oper','add');
            $("#new_barthel").attr('disabled',false);
            $('#save_barthel,#cancel_barthel,#edit_barthel').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_barthel').data('oper','edit');
            $("#edit_barthel").attr('disabled',false);
            $('#save_barthel,#cancel_barthel,#new_barthel').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_barthel,#cancel_barthel").attr('disabled',false);
            $('#edit_barthel,#new_barthel').attr('disabled',true);
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

function saveForm_barthel(callback){
    let oper = $("#cancel_barthel").data('oper');
    var saveParam = {
        action: 'save_table_barthel',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
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
    
    values = $("#formOccupTherapyBarthel").serializeArray();
    
    values = values.concat(
        $('#formOccupTherapyBarthel input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyBarthel input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyBarthel input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyBarthel select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./occupTherapy_barthel/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_barthel('edit');
    }).fail(function (data){
        callback(data);
        button_state_barthel($(this).data('oper'));
    });
}

function populate_barthel_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyBarthel",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var saveParam = {
        action: 'get_table_barthel',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_barthel/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_barthel('edit');
            autoinsert_rowdata("#formOccupTherapyBarthel",data.barthel);
        }else{
            button_state_barthel('add');
        }
    });
}

function getdata_barthel(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyBarthel",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var urlparam = {
        action: 'get_table_barthel',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_barthel/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_barthel('edit');
            autoinsert_rowdata("#formOccupTherapyBarthel",data.barthel);
        }else{
            button_state_barthel('add');
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