
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){

    //////////////////////////////////////upload file/////////////////////////////////////////
    $("#click").on("click",function(){
        $("#file").click();
    });

    $('#file').on("change", function(){
        let filename = $(this).val();
        uploadfile();
    });

    $('#ot_mmse_file tbody').on('click', 'tr', function () {
    	$('#ot_mmse_file tr').removeClass('active');
    	$(this).addClass('active');
    });

    $('#ot_mmse_file tbody').on('dblclick', 'tr', function () {
    	$('#ot_mmse_file tr').removeClass('active');
    	$(this).addClass('active');
        mmse_data = mmsetbl.row( this ).data();
        oper = 'edit';
    });
    
    //////////////////////////////////////mmse starts//////////////////////////////////////

    disableForm('#formOccupTherapyMMSE');
    
    $("#new_mmse").click(function (){
        button_state_mmse('wait');
        enableForm('#formOccupTherapyMMSE');
        rdonly('#formOccupTherapyMMSE');
        emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);

        mmsetbl.clear().draw();
        document.getElementById("idno_mmse").value = "";
    });
    
    $("#edit_mmse").click(function (){
        button_state_mmse('wait');
        enableForm('#formOccupTherapyMMSE');
        rdonly('#formOccupTherapyMMSE');
        $("#dateofexam").attr("readonly", true);

    });
    
    $("#save_mmse").click(function (){
        disableForm('#formOccupTherapyMMSE');
        if($('#formOccupTherapyMMSE').isValid({requiredFields: ''}, conf, true)){
            saveForm_mmse(function (data){
                $("#cancel_mmse").data('oper','edit');
                $("#cancel_mmse").click();
            });
        }else{
            enableForm('#formOccupTherapyMMSE');
            rdonly('#formOccupTherapyMMSE');
        }
    });
    
    $("#cancel_mmse").click(function (){
        disableForm('#formOccupTherapyMMSE');
        button_state_mmse($(this).data('oper'));
        $('#datetimeMMSE_tbl').DataTable().ajax.reload(); 
    });

    //////////////////////////////////////mmse ends//////////////////////////////////////

    //////////////////////////////////////moca starts//////////////////////////////////////

    disableForm('#formOccupTherapyMOCA');
    
    $("#new_moca").click(function (){
        button_state_moca('wait');
        enableForm('#formOccupTherapyMOCA');
        rdonly('#formOccupTherapyMOCA');
        emptyFormdata_div("#formOccupTherapyMOCA",['#mrn_occupTherapy','#episno_occupTherapy']);

        document.getElementById("idno_moca").value = "";
    });
    
    $("#edit_moca").click(function (){
        button_state_moca('wait');
        enableForm('#formOccupTherapyMOCA');
        rdonly('#formOccupTherapyMOCA');
        $("#dateAssessment").attr("readonly", true);

    });
    
    $("#save_moca").click(function (){
        disableForm('#formOccupTherapyMOCA');
        if($('#formOccupTherapyMOCA').isValid({requiredFields: ''}, conf, true)){
            saveForm_moca(function (data){
                $("#cancel_moca").data('oper','edit');
                $("#cancel_moca").click();
            });
        }else{
            enableForm('#formOccupTherapyMOCA');
            rdonly('#formOccupTherapyMOCA');
        }
    });
    
    $("#cancel_moca").click(function (){
        disableForm('#formOccupTherapyMOCA');
        button_state_moca($(this).data('oper'));
        $('#datetimeMOCA_tbl').DataTable().ajax.reload();            
    });

    //////////////////////////////////////mmse ends//////////////////////////////////////

    /////////////////////////////////////////print button starts/////////////////////////////////////////
    $("#mmse_chart").click(function (){
        window.open('./occupTherapy_cognitive/mmse_chart?mrn='+$('#mrn_occupTherapy').val()+'&episno='+$("#episno_occupTherapy").val()+'&dateofexam='+$("#dateofexam").val(), '_blank');
    });

     $("#moca_chart").click(function (){
        window.open('./occupTherapy_cognitive/moca_chart?mrn='+$('#mrn_occupTherapy').val()+'&episno='+$("#episno_occupTherapy").val()+'&dateAssessment='+$("#dateAssessment").val(), '_blank');
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

    ////////////////////////////////////////mmse starts////////////////////////////////////////
    $('#datetimeMMSE_tbl tbody').on('click', 'tr', function (){
        var data = datetimeMMSE_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimeMMSE_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);
        $('#datetimeMMSE_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_mmse").val(data.idno);
        
        $('#ot_mmse_file').DataTable().ajax.url('./occupTherapy_cognitive/table?action=ot_mmse_file&idno_mmse='+data.idno).load();
        
        var saveParam={
            action: 'get_table_mmse',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyMMSE",data.mmse);

                button_state_mmse('edit');
            }else{
                button_state_mmse('add');
            }
        });
    });

    ////////////////////////////////////////moca starts////////////////////////////////////////
    $('#datetimeMOCA_tbl tbody').on('click', 'tr', function (){
        var data = datetimeMOCA_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimeMOCA_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOccupTherapyMOCA",['#mrn_occupTherapy','#episno_occupTherapy']);
        $('#datetimeMOCA_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_moca").val(data.idno);
        
        var saveParam={
            action: 'get_table_moca',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyMOCA",data.moca);

                button_state_moca('edit');
            }else{
                button_state_moca('add');
            }
        });
    });
    
});

var mmse_data = null;
var oper = null;
var mmsetbl = $('#ot_mmse_file').DataTable( {
	columns: [
		{'data': 'idno'},
        {'data': 'idno_mmse'},
    	{'data': 'compcode'},
        {'data': 'mrn'},
        {'data': 'episno'},
    	{'data': 'filename'},
    	{'data': 'path'},
	],
    columnDefs: [
		{targets: [1,2,3,4,5,6], orderable: false },
        {targets: [0,1,2,3,4], visible: false},
        {targets: 6,
        	createdCell: function (td, cellData, rowData, row, col) {
                console.log(rowData)
				$(td).html(`<a class="ui circular blue2 button centre floated all_attach" href="../hisweb/uploads/`+rowData.path+`" target="_blank">OPEN</a>`);
   			}
   		}
    ],
    sDom: 't',
    ajax: './occupTherapy_cognitive/table?action=ot_mmse_file&idno_mmse='+$('#idno_mmse').val()
});

function uploadfile(){
	var formData = new FormData();
	formData.append('file', $('#file')[0].files[0]);
	formData.append('_token', $("#_token").val());
    
	if($('#idno_mmse').val() != ''){
		formData.append('idno', $("#idno_mmse").val());
	}

	$.ajax({
	  	url: './occupTherapy_cognitive/form?action=uploadfile',
		type: 'POST',
		data: formData,
		dataType: 'json', 
		async: false,
		cache: false,
		contentType: false,
		enctype: 'multipart/form-data',
		processData: false,
	}).done(function(msg) {
		// make_all_attachment(msg.all_attach);
    	// $('#idno_mmse').val(msg.idno);
        $('#ot_mmse_file').DataTable().ajax.url('./occupTherapy_cognitive/table?action=ot_mmse_file&idno_mmse='+$('#idno_mmse').val()).load();
  	});
}

function make_all_attachment(all_attach){
	$('#all_attach').html('');

	all_attach.forEach(function(o,i){
		$('#all_attach').append(`<a class="ui circular blue2 button all_attach" target="_blank" href="./uploads/`+o.path+`">`+o.filename+`</a>`);
	});
}

///////////////////////calculate tot mmse////////////////////////////
function findTotalMMSE(){
    var arr = document.getElementsByClassName('amountMMSE');
    var tot = 0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('tot_mmse').value = tot;
}

///////////////////////calculate tot moca////////////////////////////
function findTotalMOCA(){
    // var edu = parseInt($('#education').val());
    var arr = document.getElementsByClassName('amountMOCA');
    var tot = 0;

    // if (edu <= 12){
    //     for(var i=0;i<arr.length;i++){
    //         if(!isNaN(parseInt(arr[i].value))){
    //             tot += parseInt(arr[i].value);
    //         }
    //     }
    //     document.getElementById('tot_moca').value = tot+1;
    // } else {
        for(var i=0;i<arr.length;i++){
            if(parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        document.getElementById('tot_moca').value = tot;
    // }

}

/////////////////////mmse starts/////////////////////
var datetimeMMSE_tbl = $('#datetimeMMSE_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateofexam', 'width': '10%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////mmse ends//////////////////////

/////////////////////moca starts/////////////////////
var datetimeMOCA_tbl = $('#datetimeMOCA_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateAssessment', 'width': '10%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////moca ends//////////////////////

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

button_state_mmse('empty');
function button_state_mmse(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_mmse').data('oper','add');
            $('#new_mmse,#save_mmse,#cancel_mmse,#edit_mmse,#mmse_chart,#click').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_mmse').data('oper','add');
            $("#new_mmse").attr('disabled',false);
            $('#save_mmse,#cancel_mmse,#edit_mmse,#click').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_mmse').data('oper','edit');
            $("#new_mmse,#edit_mmse,#mmse_chart,#click").attr('disabled',false);
            $('#save_mmse,#cancel_mmse').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_mmse,#cancel_mmse").attr('disabled',false);
            $('#edit_mmse,#new_mmse,#click').attr('disabled',true);
            break;
    }
}

button_state_moca('empty');
function button_state_moca(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_moca').data('oper','add');
            $('#new_moca,#save_moca,#cancel_moca,#edit_moca,#moca_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_moca').data('oper','add');
            $("#new_moca").attr('disabled',false);
            $('#save_moca,#cancel_moca,#edit_moca').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_moca').data('oper','edit');
            $("#new_moca,#edit_moca,#moca_chart").attr('disabled',false);
            $('#save_moca,#cancel_moca').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_moca,#cancel_moca").attr('disabled',false);
            $('#edit_moca,#new_moca').attr('disabled',true);
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

function saveForm_mmse(callback){
    let oper = $("#cancel_mmse").data('oper');
    var saveParam = {
        action: 'save_table_mmse',
        oper: oper
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
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
    };
    
    values = $("#formOccupTherapyMMSE").serializeArray();
    
    values = values.concat(
        $('#formOccupTherapyMMSE input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMMSE input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMMSE input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMMSE select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
       
        callback(data);
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}

function saveForm_moca(callback){
    let oper = $("#cancel_moca").data('oper');
    var saveParam = {
        action: 'save_table_moca',
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
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
    };
    
    values = $("#formOccupTherapyMOCA").serializeArray();
    
    values = values.concat(
        $('#formOccupTherapyMOCA input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMOCA input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMOCA input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyMOCA select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}

function populate_mmse_getdata(){
    // console.log('populate');
    disableForm('#formOccupTherapyMMSE');
    emptyFormdata(errorField,"#formOccupTherapyMMSE",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var saveParam = {
        action: 'get_table_mmse',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),

    };
    
    $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formOccupTherapyMMSE",data.mmse);
            button_state_mmse('edit');
        }else{
            button_state_mmse('add');
        }
    });
}

function getdata_mmse(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyMMSE",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var urlparam = {
        action: 'get_table_mmse',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_cognitive/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_mmse('edit');
            autoinsert_rowdata("#formOccupTherapyMMSE",data.mmse);
        }else{
            button_state_mmse('add');
        }
    });
}

function populate_moca_getdata(){
    // console.log('populate');
    disableForm('#formOccupTherapyMOCA');
    emptyFormdata(errorField,"#formOccupTherapyMOCA",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var saveParam = {
        action: 'get_table_moca',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_cognitive/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formOccupTherapyMOCA",data.moca);
            button_state_moca('edit');
        }else{
            button_state_moca('add');
        }
    });
}

function getdata_moca(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyMOCA",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var urlparam = {
        action: 'get_table_moca',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_cognitive/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_moca('edit');
            autoinsert_rowdata("#formOccupTherapyMOCA",data.moca);
        }else{
            button_state_moca('add');
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