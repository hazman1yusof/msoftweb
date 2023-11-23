$(document).ready(function () {
    $('#my_a_mc').click(function(){
        var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
        if(selrow != null){
            $('#mdl_mc').modal('show');
            epno_medc_init();
        }else{
            alert('Please select episode first')
        }
    });

    $('#btn_epno_mclt').click(function(){
        epno_medc_btnstate('default');
        $('#form_medc').hide();
        $('#mclist_medc').show();
        mclist_table.ajax.url( "./pat_enq/table?action=mc_list&mrn="+bootgrid_last_row.MRN).load();
        $('#mclist_table').css('width','100%');
    });

    $('#btn_epno_gomc').click(function(){
        emptyFormdata_div('#form_medc',['#form_medc input[name="name"]']);
        epno_medc_btnstate('add_edit');
        $('#mclist_medc').hide();
        $('#form_medc').show();
    });

    $('#btn_epno_save').click(function(){
        save_medc();
    });

    $('#btn_epno_canl').click(function(){
        emptyFormdata_div('#form_medc',['#form_medc input[name="name"]']);
        epno_medc_btnstate('default');
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let href = $(e.target).eq(0).attr('href');
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
    mc_last_serialno();
    emptyFormdata_div('#form_medc',['#form_medc input[name="name"]']);
    epno_medc_btnstate('default');
    $('#form_medc input[name="name"]').val($('#name_show_episodelist').text());
}

function save_medc(){
    if($('#form_medc').valid()){
        epno_medc_btnstate('all_disabled');

        var _token = $('#csrf_token').val();
        let serializedForm = $("#form_medc").serializeArray();
        let obj = {
            'action': 'save_mc',
            'debtorcode':$('#hid_epis_payer').val(),
            'mrn':bootgrid_last_row.MRN,
            'episno': bootgrid_last_row.Episno,
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
        action:'get_value_default',
        url: './util/get_value_default',
        field:['idno'],
        table_name:'hisdb.patmc',
        filterCol:['compcode','mrn'],
        filterVal:['session.compcode',bootgrid_last_row.MRN]
    }
    $.get( param.url+"?"+$.param(param), function( data ) {
        
    },'json').done(function(data) {
        if(!$.isEmptyObject(data.rows)){
            $('#form_medc input[name="serialno"]').val(pad('0000',parseInt(data.rows[0].idno)+1,true));
        }else{
            $('#form_medc input[name="serialno"]').val(pad('0000','1',true));
        }
    });
}