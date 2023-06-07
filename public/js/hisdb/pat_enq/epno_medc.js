$(document).ready(function () {
    $('#my_a_enot').click(function(){
        var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
        if(selrow != null){
            $('#mdl_ep_note').modal('show');
            epno_medc_init();
        }else{
            alert('Please select episode first')
        }
    });

    $('#btn_epno_mclt').click(function(){
        epno_medc_btnstate('default');
    });

    $('#btn_epno_gomc').click(function(){
        emptyFormdata_div('#form_medc',['#form_medc input[name="name"]']);
        epno_medc_btnstate('add_edit');
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
    })
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