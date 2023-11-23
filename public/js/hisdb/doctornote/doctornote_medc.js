$(document).ready(function () {

    $('#btn_epno_mclt').click(function(){
        epno_medc_btnstate('default');
        $('#form_medc').hide();
        $('#mclist_medc').show();

        let rowid = $("#grid-command-buttons tr.justbc").data("rowId");
        let getCurrentRow = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];

        mclist_table.ajax.url( "./pat_enq/table?action=mc_list&mrn="+getCurrentRow.MRN).load();
        $('#mclist_table').css('width','100%');
    });

    $('#btn_epno_gomc').click(function(){
        emptyFormdata_div('#form_medc',['#form_medc input[name="name"]','#form_medc input[name="serialno"]']);
        epno_medc_btnstate('add_edit');
        $('#mclist_medc').hide();
        $('#form_medc').show();
    });

    $('#btn_epno_save').click(function(){
        save_medc();
    });

    $('#btn_epno_canl').click(function(){
        emptyFormdata_div('#form_medc',['#form_medc input[name="name"]','#form_medc input[name="serialno"]']);
        epno_medc_btnstate('default');
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let href = $(e.target).eq(0).attr('href');
    });

    $('#form_medc [name=datefrom]').on('blur',function(){
        let mccnt=$('#form_medc [name=mccnt]').val();
        let datefrom=$('#form_medc [name=datefrom]').val();

        let dateto = moment(datefrom).add(mccnt-1, "days");
        $('#form_medc [name=dateto]').val(moment(dateto).format('YYYY-MM-DD'));
    });

    $('#form_medc [name=mccnt]').on('change',function(){
        let mccnt=$('#form_medc [name=mccnt]').val();
        let datefrom=$('#form_medc [name=datefrom]').val();

        let dateto = moment(datefrom).add(mccnt-1, "days");
        $('#form_medc [name=dateto]').val(moment(dateto).format('YYYY-MM-DD'));
    });

    var mclist_table = $('#mclist_table').DataTable({
        "ajax": "",
        "sDom": "",
        "paging":false,
        "columns": [
                {'data': 'idno'},
                {'data': 'datefrom'},
                {'data': 'dateto'},
                {'data': 'mrn'},
                {'data': 'episno'},
                {'data': 'adduser'},
                {'data': 'adddate'},
            ]
    });

    $('#mclist_table tbody').on('click', 'tr', function () { 
        var data = mclist_table.row( this ).data();
        if(data == undefined){
            return;
        }
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
        }else {
            mclist_table.$('tr.active').removeClass('active');
            $(this).addClass('active');
        }
    });


    $('#mclist_table tbody').on('dblclick', 'tr', function () {
        var data = mclist_table.row( this ).data();
        window.open("./pat_enq/table?action=show_mc&idno="+data.idno);
    });
});

function epno_medc_btnstate(state){
    switch(state){
        case 'all_disabled':
            $('#btn_epno_mclt').prop('disabled',true);
            $('#btn_epno_gomc').prop('disabled',true);
            $('#btn_epno_save').prop('disabled',true);
            $('#btn_epno_canl').prop('disabled',true);
            // disableForm('#form_medc');
            break;
        case 'default':
            $('#btn_epno_mclt').prop('disabled',false);
            $('#btn_epno_gomc').prop('disabled',false);
            $('#btn_epno_save').prop('disabled',true);
            $('#btn_epno_canl').prop('disabled',true);
            disableForm('#form_medc');
            break;
        case 'add_edit':
            $('#btn_epno_mclt').prop('disabled',true);
            $('#btn_epno_gomc').prop('disabled',true);
            $('#btn_epno_save').prop('disabled',false);
            $('#btn_epno_canl').prop('disabled',false);
            enableForm('#form_medc',['name','serialno']);
            break;
    }
}

function epno_medc_init(){
    let bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
    let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
    var lastrowdata = getrow_bootgrid(bootgrid_last_rowid,rows);

    mc_last_serialno(lastrowdata);
    emptyFormdata_div('#form_medc',['#form_medc input[name="name"]','#form_medc input[name="serialno"]']);
    epno_medc_btnstate('default');
    $('#form_medc input[name="name"]').val(lastrowdata.Name);
}

function save_medc(){
    if($('#form_medc').valid()){
        epno_medc_btnstate('all_disabled');

        let bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
        let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
        var lastrowdata = getrow_bootgrid(bootgrid_last_rowid,rows);

        var _token = $('#csrf_token').val();
        let serializedForm = $("#form_medc").serializeArray();
        let obj = {
            'action': 'save_mc',
            'debtorcode':$('#hid_epis_payer').val(),
            'mrn':lastrowdata.MRN,
            'episno': lastrowdata.Episno,
            '_token': _token,
        };
        
        $.post('./pat_enq/form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('ERROR');
            epno_medc_btnstate('default');
        }).done(function(data){
            epno_medc_btnstate('default');
            window.open("./pat_enq/table?action=show_mc&idno="+data.idno);
        });
    }
}

function mc_last_serialno(lastrowdata){
    var param={
        action:'get_serialno',
        url: './pat_enq/table'
    }
    $.get( param.url+"?"+$.param(param), function( data ) {
        
    },'json').done(function(data) {
        $('#form_medc input[name="serialno"]').val(pad('0000',parseInt(data.serialno)+1,true));
    });
}