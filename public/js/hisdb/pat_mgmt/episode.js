$(document).ready(function() {
    $('#btn_epis_bed').click(function(){
        if(!$('#txt_epis_bed').is(':disabled')){
            $('#mdl_accomodation').data('openfor','episode');
            $('#mdl_accomodation').modal('show')
        }
    });

});

$('#editEpisode').on('shown.bs.modal', function (e) {
    parent_close_disabled(true);
    $('#txt_epis_dept').focus();
});

$('#editEpisode').on('hidden.bs.modal', function (e) {
    $('#form_episode').find("label.error").detach();
    $("#form_episode").find('.has-error').removeClass("has-error");
    $("#form_episode").find('.error').removeClass("error");
    $("#form_episode").find('.has-success').removeClass("has-success");
    $("#form_episode").find('.valid').removeClass("valid");
    $("#form_episode").find('.myerror').removeClass("myerror");

    $(this)
        .find("input,textarea,select")
        .val('')
        .end()
        .find("input[type=checkbox], input[type=radio]")
        .prop("checked", "")
        .end(); //this for clearing input after hide modal
    $("#tabDoctor,#tabBed,#tabNok,#tabPayer,#tabDeposit").collapse("hide");
    parent_close_disabled(false);
});

function get_default_value(mrn){
    let obj_param = {
           action:'get_default_value',
           mrn:mrn,
           userdeptcode:$('#userdeptcode').val()
       };

    $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(!$.isEmptyObject(data)){
            if(data.data != 'nothing'){

                if(data.data.newcaseP == 1){
                    $('#cmb_epis_case_maturity').val(1);
                    $('#cmb_epis_pregnancy').val("Pregnant");
                }else if(data.data.newcaseNP == 1){
                    $('#cmb_epis_case_maturity').val(1);
                    $('#cmb_epis_pregnancy').val("Non-Pregnant");
                }else if(data.data.followupP == 1){
                    $('#cmb_epis_case_maturity').val(2);
                    $('#cmb_epis_pregnancy').val("Pregnant");
                }else if(data.data.followupP == 1){
                    $('#cmb_epis_case_maturity').val(2);
                    $('#cmb_epis_pregnancy').val("Non-Pregnant");
                }
                
                $("#txt_epis_source").val(data.data.adm_desc);
                $("#hid_epis_source").val(data.data.admsrccode);
                $("#txt_epis_case").val(data.data.cas_desc);
                $("#hid_epis_case").val(data.data.case_code);
                $("#txt_admdoctor").val(data.data.admdoctor_desc);
                $("#hid_admdoctor").val(data.data.admdoctor);
                $("#txt_attndoctor").val(data.data.attndoctor_desc);
                $("#hid_attndoctor").val(data.data.attndoctor);
                $("#hid_epis_fin").val(data.data.pay_type);
                $("#txt_epis_fin").val(data.data.dbty_desc).change();
                $("#cmb_epis_pay_mode").val(data.data.pyrmode);
                $("#txt_epis_payer").val(data.data.dbms_name);
                $("#hid_epis_payer").val(data.data.payer);
                $("#txt_epis_bill_type").val(data.data.bmst_desc);
                $("#hid_epis_bill_type").val(data.data.billtype);

            }
        }
    });
}

function check_debtormast_exists(rowid,kosong){

    if(!kosong){
        $('#txt_epis_payer').val($("#txt_epis_name").text());
        $('#hid_epis_payer').val($('input[type="hidden"]#mrn_episode').val());
    }else{
        $('#txt_epis_payer').val('');
        $('#hid_epis_payer').val('');
    }

    //check debtormast, kalau ada sama dgn mrn, paste
    // let obj_param = {
    //        action:'check_debtormast',
    //        mrn:$('input[type="hidden"]#mrn_episode').val(),
    //        mrn_trailzero:('0000000' + $('input[type="hidden"]#mrn_episode').val()).slice(-7),
    //    };

    // $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
    // },'json').done(function(data) {
    //     if(!$.isEmptyObject(data)){
    //        $('#txt_epis_payer').val(data.data.name);
    //        $('#hid_epis_payer').val(data.data.debtorcode);
           
    //     }else{

    //        $('#txt_epis_payer').val($("#txt_epis_name").val());
    //        $('#hid_epis_payer').val($('input[type="hidden"]#mrn_episode').val());
    //     }
    // });
}

function get_epis_other_data(episno){
    let obj_param = {
           action:'get_epis_other_data',
           mrn:$('#mrn_episode').val(),
           epistycode:$('#epistycode').val(),
           episno:episno,
       };

    $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(data.episode != null){

            var episdata = data.episode;
            var epispayer = data.epispayer;

            $('#hid_epis_fin').val(episdata.pay_type);
            $('#txt_epis_fin').val(episdata.dbty_desc).change();
            $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
            $('#cmb_epis_pay_mode').val(episdata.pyrmode.toUpperCase());
            $('#txt_epis_payer').val(episdata.dbms_name);
            $('#hid_epis_payer').val(episdata.payer);
            $('#txt_epis_bill_type').val(episdata.bmst_desc);
            $('#hid_epis_bill_type').val(episdata.billtype);
        }

        if(data.bed != null){
            var bed =  data.bed;
            $('#txt_epis_bed').val(bed.bednum);
            $('#txt_epis_ward').val(bed.ward);
            $('#txt_epis_room').val(bed.room);
            $('#txt_epis_bedtype').val(bed.bedtype);
        }
    });
}

function get_billtype_default(mrn){
    let obj_param = {
           action:'get_billtype_default',
       };

    $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(!$.isEmptyObject(data)){
            if($('#txt_epis_type').val() == 'IP'){
                $("form#form_episode input[name='bill_type']").val(data.data.pvalue1);
            }else{
                $("form#form_episode input[name='bill_type']").val(data.data.pvalue2);
            }

            epis_desc_show.write_desc();
        }
    });
}

function populate_episode(rowid,rowdata){
    disableEpisode(true);

    $('#mrn_episode').val(rowdata.MRN);
    $('#epis_rowid').val(rowid);
    $('#txt_epis_name').text(rowdata.Name);
    $('#txt_epis_mrn').val(('0000000' + rowdata.MRN).slice(-7));
    $('#txt_epis_type').val($("#epistycode").val());
    $('#txt_epis_date').val(moment().format('DD/MM/YYYY'));
    $('#txt_epis_time').val(moment().format('hh:mm:ss'));
    $('#btn_epis_payer').data('mrn',$(this).data("mrn"));
    $('#txt_epis_iPesakit').val(rowdata.iPesakit);

    $('#cmb_epis_pregnancy').prop("disabled", false);
    if(rowdata.Sex == "M"){
        $('#cmb_epis_pregnancy').val('Non-Pregnant');
        $('#cmb_epis_pregnancy').prop("disabled", true);
    }else if(rowdata.Sex == null){
        if(rowdata.Newic != null && rowdata.Newic.length>11){
            var lastchar = rowdata.Newic.at(-1);
            if(parseInt(lastchar)%2 != 0){
                $('#cmb_epis_pregnancy').val('Non-Pregnant');
                $('#cmb_epis_pregnancy').prop("disabled", true);
            }
        }
    }

    var episno_ = parseInt(rowdata.Episno);
    if(isNaN(episno_)){
        episno_ = 0;
    }
    
    $('#txt_epis_bed').prop('disabled',false);
    if(rowdata.PatStatus == 1){
        $('#episode_title_text').text('EDIT CURRENT');
        $("#episode_oper").val('edit');
        $('#txt_epis_no').val(parseInt(episno_));
        populate_episode_by_mrn_episno(rowdata.MRN,rowdata.Episno);
        $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().show();
    }else{
        $('#episode_title_text').text('ADD NEW');
        $("#episode_oper").val('add');
        $('#txt_epis_no').val(parseInt(episno_) + 1);
        $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().hide();

        $('#hid_epis_dept').val($('#userdeptcode').val());
        $('#txt_epis_dept').val($('#userdeptdesc').val());

        get_epis_other_data(episno_);

        // $('#txt_epis_dept').blur();

        // get_billtype_default(rowdata.MRN);
        // get_default_value(rowdata.MRN);
    }
}

 // *************************** episode ******************************

$('#form_episode').validate({
    errorPlacement: function(error, element) {
        error.insertAfter( element.closest(".input-group") );
    }
});

$('#btn_save_episode').click(function(){
    if(check_if_paymode_kena_gl() && $('#epis_header').valid() && $('#form_episode').valid() && $('#form_episode input.myerror').length<=0){
        if(check_if_paymode_kena_panel()){
            add_episode();
        }
    }
});

$("#btnpanelsave").on('click',function(){
    if($('#panelform').valid()){
        $("#btnpanelsave").prop('disabled',true);
        add_episode();
    }
});

$('#txt_epis_fin').change(function (e){
    var iregin = $('#hid_epis_fin').val();
    if (iregin == '0' || iregin == '') {
        disableEpisode(true);
    } else {  
        disableEpisode(false);
        $('#cmb_epis_pay_mode').empty();

        $('#txt_epis_payer').prop('disabled',false);
        let pay_mode_arr = [];
        if (iregin == 'PT'){
            $('#txt_epis_payer').prop('disabled',true);
            pay_mode_arr = ['CASH','CARD','OPEN CARD','CONSULTANT GUARANTEE (PWD)'];
            check_debtormast_exists($('#epis_rowid').val(),false);
            // $('#txt_epis_bill_type').val($('#billtype_def_desc').val());
            // $('#hid_epis_bill_type').val($('#billtype_def_code').val());
        }else if(iregin == 'PR'){
            pay_mode_arr = ['CASH','CARD','OPEN CARD','CONSULTANT GUARANTEE (PWD)'];
            check_debtormast_exists($('#epis_rowid').val(),false);
            // $('#txt_epis_bill_type').val($('#billtype_def_desc').val());
            // $('#hid_epis_bill_type').val($('#billtype_def_code').val());
        }else{
            pay_mode_arr = ['PANEL', 'GUARANTEE LETTER', 'WAITING GL'];
            // $('#txt_epis_bill_type').val($('#billtype_def_desc').val());
            // $('#hid_epis_bill_type').val($('#billtype_def_code').val());
        }

        $.each(pay_mode_arr, function(i,val){
            if(val == 'GUARANTEE LETTER'){
                $("#cmb_epis_pay_mode").append(
                    $('<option selected></option>')
                        .val(val)
                        .html(val)
                );
            }else{
                $("#cmb_epis_pay_mode").append(
                    $('<option></option>')
                        .val(val)
                        .html(val)
                );
            }
            
        });

    }
});

var debtor_table =
    $('#tbl_epis_debtor').DataTable( {
        "columns": [
                    {'data': 'debtortype'}, 
                    {'data': 'debtorcode' }, 
                    {'data': 'name'},
                    {'data': 'description' }, 
                    {'data': 'debtortycode' }, 
                ],
        columnDefs: [ {
            targets: [3,4],
            visible: false
        } ],
        "fnDrawCallback": function( oSettings ) {
            let search = $('#tbl_epis_debtor').data('search');
            let iscomplete = $('#tbl_epis_debtor').data('iscomplete');

            if(search == true){
                $('#tbl_epis_debtor').data('search',false)
                debtor_table.search($('#txt_epis_payer').val()).draw();
            }

            if($('#tbl_epis_debtor').data('iscomplete') == true){
                if(debtor_table.page.info().recordsDisplay == 1){
                    $('#tbl_epis_debtor tbody tr:eq(0)').dblclick();
                }
            }
        },
        "fnInitComplete": function(oSettings, json) {
            $('#tbl_epis_debtor').data('search',false);
            $('#tbl_epis_debtor').data('iscomplete',true);
        }
    } );

function epis_payer_onclick(event){
    $('#btngurantor').show();

    let oper = event.data.data;

    if(oper == 'tab' && event.key == "Tab"){
        $('#mdl_epis_pay_mode').modal('show');
        $('#tbl_epis_debtor').data('search',true);

        if($('#hid_epis_fin').val() == 'PT'  || $('#hid_epis_fin').val() == 'PR'){
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=1&epistycode='+$('#epistycode').val() ).load();
        }else{
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=2&epistycode='+$('#epistycode').val() ).load();
        }

    }else if(oper == 'click'){
        $('#mdl_epis_pay_mode').modal('show');
        if($('#hid_epis_fin').val() == 'PT'  || $('#hid_epis_fin').val() == 'PR'){
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=1&epistycode='+$('#epistycode').val() ).load();
        }else{
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=2&epistycode='+$('#epistycode').val() ).load();
        }

    }
        
}

$('#tbl_epis_debtor').on('dblclick', 'tr', function () {
    let debtor_item = debtor_table.row( this ).data();
    $('#hid_epis_payer').val(debtor_item["debtorcode"]);
    $('#txt_epis_payer').val(debtor_item["name"]);
    $('#hid_epis_bill_type').val(debtor_item["billtype"]);
    $('#txt_epis_bill_type').val(debtor_item["billtype_desc"]);
    $('#mdl_epis_pay_mode').modal('hide');

    if($('#hid_epis_fin').val() != 'PT' || $('#hid_epis_fin').val() != 'PR'){
        $('#txt_epis_fin').val(debtor_item["description"]);
        $('#hid_epis_fin').val(debtor_item["debtortycode"]);
    }
    $('#txt_epis_refno').focus();
});

var billtype_table = 
        $('#tbl_epis_billtype').DataTable( {
            "columns": [
                {'data': 'billtype'}, 
                {'data': 'description' },
            ],
            "fnDrawCallback": function( oSettings ) {
                let search = $('#tbl_epis_billtype').data('search');
                let iscomplete = $('#tbl_epis_billtype').data('iscomplete');

                if(search == true){
                    $('#tbl_epis_billtype').data('search',false)
                    billtype_table.search($('#txt_epis_bill_type').val()).draw();
                }

                if($('#tbl_epis_billtype').data('iscomplete') == true){
                    if(billtype_table.page.info().recordsDisplay == 1){
                        $('#tbl_epis_billtype tbody tr:eq(0)').dblclick();
                    }
                }
            },
            "fnInitComplete": function(oSettings, json) {
                $('#tbl_epis_billtype').data('search',false);
                $('#tbl_epis_billtype').data('iscomplete',true);
            }
        });

$('#tbl_epis_billtype').on('dblclick', 'tr', function (){
    let billtype_item = billtype_table.row( this ).data();              
    $('#hid_epis_bill_type').val(billtype_item["billtype"]);
    $('#txt_epis_bill_type').val(billtype_item["description"]);
    $('#mdl_bill_type').modal('hide');
});

function bill_type_info_onclick(event){
    let oper = event.data.data;
    if(oper == 'tab' && event.key == "Tab"){
        $('#tbl_epis_billtype').data('search',true);
        $('#mdl_bill_type').modal('show');
        billtype_table.ajax.url( 'pat_mast/get_entry?action=get_billtype_list&type=' + $('#txt_epis_type').val() ).load();

    }else if(oper == 'click'){
        $('#mdl_bill_type').modal('show');
        billtype_table.ajax.url( 'pat_mast/get_entry?action=get_billtype_list&type=' + $('#txt_epis_type').val() ).load();

    }
}

$( "#btngurantor").click(function() {
    $('#bs-guarantor').modal('show');
});

$( "#btngurantorclose").click(function() {
    $('#mdl_epis_pay_mode').modal('show');
});

// $( "#btngurantorcommit").click(add_guarantor);

var refno_object = null;

// $("#btn_refno_info").on('click',btn_refno_info_onclick);


function btn_refno_info_onclick(event){
    $('#mdl_reference').data('target_id',$(event.currentTarget).attr('id'));
    if(refno_object == null){
        refno_object = new refno_class();
        refno_object.show_mdl();
    }else{
        refno_object.show_mdl();
    }
}

function disableEpisode(status) {

    $("#txt_epis_bill_type").off('click',bill_type_info_onclick);
    $("#btn_bill_type_info").off('keydown',bill_type_info_onclick);
    $("#txt_epis_payer").on('keydown',{data:'tab'},epis_payer_onclick);
    $("#btn_epis_payer").off('click',{data:'click'},epis_payer_onclick);
    $("#btn_refno_info").off('click',btn_refno_info_onclick);

    if(status == false){
        $("#txt_epis_bill_type").on('keydown',{data:'tab'},bill_type_info_onclick);
        $("#btn_bill_type_info").on('click',{data:'click'},bill_type_info_onclick);
        $("#txt_epis_payer").on('keydown',{data:'tab'},epis_payer_onclick);
        $("#btn_epis_payer").on('click',{data:'click'},epis_payer_onclick);
        $("#btn_refno_info").on('click',btn_refno_info_onclick);
        $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
    }

    $('#txt_epis_payer').prop("disabled",status);
    $('#txt_epis_bill_type').prop("disabled",status);
    $('#rad_epis_fee_yes').prop("disabled",status);
    $('#rad_epis_fee_no').prop("disabled",status);

    $('#txt_epis_refno').prop("disabled",status);
    $('#txt_epis_our_refno').prop("disabled",status);

    $('#txt_epis_refno2').prop("disabled",status);
    $('#txt_epis_our_refno2').prop("disabled",status);


    $('#regque').prop("disabled",status);
}

function add_episode()
{    
    var episoper = $("#episode_oper").val();
    var epismrn = $('#mrn_episode').val();
    var episno = $("#txt_epis_no").val();
    var epistype = $("#txt_epis_type").val();
    var epismaturity = $("#cmb_epis_case_maturity").val();
    var episdate = moment($("#txt_epis_date").val(), 'DD/MM/Y').format('Y-MM-DD');
    var epistime = moment($("#txt_epis_time").val(), 'hh:mm:ss').format('hh:mm');
    var episdept = $("#hid_epis_dept").val();
    var epissrc = $("#hid_epis_source").val();
    var episcase = $("#hid_epis_case").val();
    var episdoctor = $("#hid_epis_doctor").val();
    var episfin = $("#hid_epis_fin").val();
    var epispay = $("#cmb_epis_pay_mode").val();
    var epispayer = $("#hid_epis_payer").val();
    var episbilltype = $("#hid_epis_bill_type").val();
    var episrefno = $("#txt_epis_refno").val();
    var episourrefno = $("#txt_epis_our_refno").val();
    var epispreg = $('#cmb_epis_pregnancy').val();
    var episfee = $('input[name=rad_epis_fee]:checked').val();
    var episbed = $('#txt_epis_bed').val();
    var _token = $('#csrf_token').val();
    var apptidno = $("#apptidno_epis").val();
    var preepisidno = $("#preepisidno_epis").val();

    let obj =  { 
                episoper: episoper,
                epis_mrn: epismrn,
                epis_no : episno,
                epis_type : epistype, 
                epis_maturity : epismaturity,
                epis_date : episdate,
                epis_time : epistime,
                epis_dept : episdept,
                epis_src : epissrc,
                epis_case : episcase,
                epis_doctor : episdoctor,
                epis_fin : episfin,
                epis_pay : epispay,
                epis_payer : epispayer,
                epis_billtype : episbilltype,
                epis_refno : episrefno,
                epis_ourrefno : episourrefno,
                epis_preg : epispreg,
                epis_fee : episfee,
                epis_bed : episbed,
                apptidno : apptidno,
                preepisidno : preepisidno,
                _token: _token
              }

    if($('#cmb_epis_pay_mode').val() == 'PANEL'){
        obj.newpanel_corpcomp = $('#hid_newpanel_corpcomp').val();
        obj.newpanel_name = $('#newpanel-name').val(); 
        obj.newpanel_staffid = $('#newpanel-staffid').val(); 
        obj.newpanel_relatecode = $('#hid_newpanel_relatecode').val(); 
        obj.newpanel_case = $('#newpanel-case').val(); 
        obj.epis_refno = $('#newpanel-refno').val();  
        obj.newpanel_deptcode = $('#newpanel-deptcode').val(); 
    }

    $.post( "pat_mast/save_episode", obj , function( data ) {
        
    }).fail(function(data) {
        if(data.responseJSON.message=='dept_wrong'){
            $('#txt_epis_dept').focus();
            myerrorIt_only('#txt_epis_dept',true);
        }
    }).success(function(data){
        $('#editEpisode').modal('hide');
        $("#load_from_addupd").data('info','true');
        $("#load_from_addupd").data('oper','edit');
        $("#grid-command-buttons").bootgrid('reload');
        $("#tabpreepis").collapse("hide");
    });
}

function populate_episode_by_mrn_episno(mrn,episno,form){
    var param={
        action:'get_episode_by_mrn',
        field:"*",
        mrn:mrn,
        episno:episno
    };

    $.get( "episode/get_episode_by_mrn?"+$.param(param), function( data ) {

    },'json').done(function(data) {

        if(!$.isEmptyObject(data)){
            var episdata = data.episode;
            var epispayer = data.epispayer;
            var debtormast = data.debtormast;
            var bed = data.bed;

            if(episdata.newcaseP == 1){
                $('#cmb_epis_case_maturity').val(1);
                $('#cmb_epis_pregnancy').val("Pregnant");
            }else if(episdata.newcaseNP == 1){
                $('#cmb_epis_case_maturity').val(1);
                $('#cmb_epis_pregnancy').val("Non-Pregnant");
            }else if(episdata.followupP == 1){
                $('#cmb_epis_case_maturity').val(2);
                $('#cmb_epis_pregnancy').val("Pregnant");
            }else if(episdata.followupP == 1){
                $('#cmb_epis_case_maturity').val(2);
                $('#cmb_epis_pregnancy').val("Non-Pregnant");
            }

            // $('#cmb_epis_pregnancy').val();
            $('#txt_epis_date').val(episdata.reg_date);
            $('#txt_epis_time').val(episdata.reg_time);
            $('#txt_epis_no').val(episdata.episno);
            $('#txt_epis_type').val(episdata.epistycode);
            $('#txt_epis_iPesakit').val(episdata.iPesakit);

            $('#txt_epis_dept').val(episdata.reg_desc);
            $('#hid_epis_dept').val(episdata.regdept);
            $('#txt_epis_source').val(episdata.adm_desc);
            $('#hid_epis_source').val(episdata.admsrccode);
            $('#txt_epis_case').val(episdata.cas_desc);
            $('#hid_epis_case').val(episdata.case_code);
            $('#txt_epis_doctor').val(episdata.doc_desc);
            $('#hid_epis_doctor').val(episdata.admdoctor);
            $('#hid_epis_fin').val(episdata.pay_type);
            $('#txt_epis_fin').val(episdata.dbty_desc).change();
            if($('#epistycode').val() == 'IP' && bed != null){
                $("#txt_epis_bed").val(bed.ward);
                $("#txt_epis_ward").val(bed.ward);
                $("#txt_epis_room").val(bed.room);
                $("#txt_epis_bedtype").val(bed.description);
                $('#txt_epis_bed').prop('disabled',true);
            }
            $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
            $('#cmb_epis_pay_mode').val(episdata.pyrmode.toUpperCase());
            $('#txt_epis_payer').val(debtormast.name);
            $('#hid_epis_payer').val(debtormast.debtorcode);
            $('#txt_epis_bill_type').val(episdata.bmst_desc);
            $('#hid_epis_bill_type').val(episdata.billtype);
            $('#txt_epis_refno').val(data.txt_epis_refno);
            $('#txt_epis_our_refno').val(data.txt_epis_our_refno);
            // $('#txt_epis_queno').val();

            // $('#txt_epis_fin').change();

            // epis_desc_show.write_desc();
        }else{
            alert('MRN not found')
        }

    }).error(function(data){

    });

}
// populatecombo_gl();
function populatecombo_gl(){

    var urloccupation = 'pat_mast/get_entry?action=get_patient_occupation';
    loadlist($('select#newgl-occupcode').get(0),urloccupation,'occupcode','description');

    var urlRel = 'pat_mast/get_entry?action=get_patient_relationship';
    loadlist($('select#newgl-relatecode').get(0),urlRel,'relationshipcode','description');
}

function loading_desc_epis(obj){ //loading description dia sebab save code dia je
    this.code_fields=obj;
    this.regdept={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.regsource={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.case={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.doctor={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.epis_bed={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.epis_fin={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.epis_payer={code:'debtorcode',desc:'name'};//data simpan dekat dalam ni
    this.bill_type={code:'billtype',desc:'description'};//data simpan dekat dalam ni
    this.bed_dept={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.occupation={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.bed_ward={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.newgl_occupcode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.newgl_relatecode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.newgl_corpcomp={code:'debtorcode',desc:'name'};//data simpan dekat dalam ni
    this.load_desc = function(){
        load_for_desc(this,'regdept','pat_mast/get_entry?action=get_reg_dept');
        load_for_desc(this,'regsource','pat_mast/get_entry?action=get_reg_source');
        load_for_desc(this,'case','pat_mast/get_entry?action=get_reg_case');
        load_for_desc(this,'doctor','pat_mast/get_entry?action=get_reg_doctor');
        load_for_desc(this,'epis_bed','pat_mast/get_entry?action=get_reg_bed');
        load_for_desc(this,'epis_fin','pat_mast/get_entry?action=get_reg_fin');
        load_for_desc(this,'epis_payer','pat_mast/get_entry?action=get_debtor_list');
        load_for_desc(this,'bill_type','pat_mast/get_entry?action=get_billtype_list');
        load_for_desc(this,'bed_dept','pat_mast/get_entry?action=get_bed_type');
        load_for_desc(this,'bed_ward','pat_mast/get_entry?action=get_bed_ward');
        load_for_desc(this,'newgl_occupcode','pat_mast/get_entry?action=get_patient_occupation');
        load_for_desc(this,'newgl_relatecode','pat_mast/get_entry?action=get_patient_relationship');
        load_for_desc(this,'newgl_corpcomp','pat_mast/get_entry?action=get_debtor_list&type=newgl');
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


function accomodation_selecter(){
    var accomodation_table = null;

    accomodation_table = $('#accomodation_table').DataTable( {
        "ajax": "pat_mast/get_entry?action=accomodation_table",
        "paging":false,
        "columns": [
            {'data': 'desc_bt'},
            {'data': 'bednum'},
            {'data': 'desc_d'},
            {'data': 'room'},
            {'data': 'occup'},
            {'data': 'bedtype'},
            {'data': 'ward'},
        ],
        order: [[5, 'asc'],[6, 'asc']],
        columnDefs: [ {
            targets: [0,5,6],
            visible: false
        } ],
        rowGroup: {
            dataSrc: [ "desc_bt" ],
            startRender: function ( rows, group ) {
                return group + `<i class="arrow fa fa-angle-double-down"></i>`;
            }
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass( data['desc_bt'] );
            if(data.occup != 'VACANT'){
                $(row).addClass('disabled');
            }
        },
        "initComplete": function(settings, json) {
            let opt_bt = opt_ward = "";
            epis_desc_show.bed_dept.data.forEach(function(e,i){
                opt_bt+=`<option value="`+e.description+`">`+e.description+`</option>`;
            });
            epis_desc_show.bed_ward.data.forEach(function(e,i){
                opt_ward+=`<option value="`+e.description+`">`+e.description+`</option>`;
            });
            $("#accomodation_table_filter").html(`
                <label >Bed Type: <input type="radio" data-seltype="bt" id="search_bed_bedtype" name="search_bed" checked></label>;&nbsp;
                <label >Ward: <input type="radio" data-seltype="d" id="search_bed_ward" name="search_bed"></label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <select data-dtbid="0" id="search_bed_select_bed_dept" name="search_bed_select" class="form-control">
                      <option value="">-- Select --</option>
                      `+opt_bt+`
                    </select>

                    <select data-dtbid="2" id="search_bed_select_ward" name="search_bed_select" class="form-control" style="display:none;">
                      <option value="">-- Select --</option>
                      `+opt_ward+`
                    </select>
                </label>
                `
            );

            $('input[type="radio"][name="search_bed"]').on('click',function(){
                let seltype = $(this).data('seltype');
                if(seltype == 'bt'){
                    $("#search_bed_select_bed_dept").show();
                    $("#search_bed_select_ward").hide();
                }else{
                    $("#search_bed_select_bed_dept").hide();
                    $("#search_bed_select_ward").show();
                }
            });

            $("select[name='search_bed_select']").on('change',function(){
                // accomodation_table.columns( $(this).data('dtbid') ).search( this.value ).draw();
                accomodation_table.search( this.value ).draw();
            });
        }
    } );

    $('#accomodation_table tbody').on('dblclick', 'tr', function () {    
        let item = accomodation_table.row( this ).data();
        let openfor = $('#mdl_accomodation').data('openfor');

        if(item != undefined && item['occup'] == 'VACANT' && openfor == 'episode'){
            $('#txt_epis_bed').val(item["bednum"]);
            $('#txt_epis_ward').val(item["ward"]);
            $('#txt_epis_room').val(item["room"]);
            $('#txt_epis_bedtype').val(item["bedtype"]);
                
            $('#mdl_accomodation').modal('hide');
            $('#txt_epis_fin').focus();
        }else if(item != undefined && item['occup'] == 'VACANT' && openfor == 'bed'){
            $('#bed_bednum').val(item["bednum"]);
            $('#bed_ward').val(item["ward"]);
            $('#bed_room').val(item["room"]);
            $('#bed_bedtype').val(item["bedtype"]);
            $('#bed_status').val('OCCUPIED');
            $('#bed_isolate').val('0');

            $('#mdl_accomodation').modal('hide');
        }

        
    });

    $('#accomodation_table tbody').on('click', 'tr.dtrg-group', function () {    
        let bedtype = $(this).children().text();
        let bedtype_ = bedtype.split(" ");
        bedtype = bedtype_[0];
        if($(this).data('_hidden') == undefined || $(this).data('_hidden') == 'show'){
            $("#accomodation_table tbody tr."+bedtype).hide();
            $(this).data('_hidden','hide');
        }else if($(this).data('_hidden') == 'hide'){
            $("#accomodation_table tbody tr."+bedtype).show();
            $(this).data('_hidden','show');
        }
    });

    $("#mdl_accomodation").on('hidden.bs.modal', function () {
        $('#accomodation_table tbody').off('hidden.bs.modal');
        $('#accomodation_table tbody').off('click');
        $('#accomodation_table tbody').off('dblclick');
        accomodation_table.destroy();
    });
}

function pad(pad, str, padLeft) {
    if (typeof str === 'undefined') 
        return pad;
    if (padLeft) {
        return (pad + str).slice(-pad.length);
    } else {
        return (str + pad).substring(0, pad.length);
    }
}

function refno_class(){
    var self = this;
    var selrowdata = null;
    $("#btn_epis_new_gl").click(function() {
        $('#mdl_new_gl').data('oper','add');
        $('#btnglsave').show();
        $('#mdl_new_gl').modal('show');
    });

    $("#btn_epis_view_gl").click(function() {
        $('#mdl_new_gl').data('oper','edit');
        $('#btnglsave').hide();
        $('#mdl_new_gl').modal('show');
    });

    $("#btnglclose").click(function() {
        $('#glform').find("label").detach();
        $("#glform").trigger('reset');
        $("#glform").find('.has-error').removeClass("has-error");
        $("#glform").find('.error').removeClass("error");
        $("#glform").find('.has-success').removeClass("has-success");
        $("#glform").find('.valid').removeClass("valid");
        self.show_mdl();
    });

    this.refno_table = $('#tbl_epis_reference').DataTable( {
        // "ajax": "/pat_mast/get_entry?action=get_refno_list&debtorcode=" + $('#hid_epis_payer').val() + "&mrn=" + $('#mrn_episode').val(),
        "columns": [
                    {'data': 'debtorcode' ,'width':'20%'},
                    {'data': 'name' ,'width':'30%'},
                    {'data': 'gltype' ,'width':'10%'},
                    {'data': 'staffid','width':'10%' },
                    {'data': 'refno','width':'10%' },
                    {'data': 'startdate','width':'10%' },
                    {'data': 'enddate','width':'10%' },
                    {'data': 'ourrefno' ,'visible': false},
                    {'data': 'childno' , 'visible': false, 'searchable':false}, 
                    {'data': 'episno' , 'visible': false, 'searchable':false},
                    {'data': 'medcase' , 'visible': false, 'searchable':false},
                    {'data': 'mrn' , 'visible': false, 'searchable':false},
                    {'data': 'relatecode' , 'visible': false, 'searchable':false},
                    {'data': 'remark' , 'visible': false, 'searchable':false},
                    {'data': 'startdate' , 'visible': false, 'searchable':false},
                    {'data': 'enddate' , 'visible': false, 'searchable':false},
            ],
    });

    this.show_mdl = function(first = false){
        $('#mdl_reference').modal('show');
        $('#btn_epis_view_gl').prop('disabled',true);
        this.refno_table.ajax.url("pat_mast/get_entry?action=get_refno_list&debtorcode=" + $('#payercode_epno_payer').val() + "&mrn=" + $('input[type="hidden"]#mrn_episode').val()).load();
    }

    $('#tbl_epis_reference').on('dblclick', 'tr', function () {
        let refno_item = self.refno_table.row( this ).data();
        let target_id = $('#mdl_reference').data('target_id');

        if(target_id == 'btn_refno_info'){
            $('#txt_epis_our_refno').val(refno_item["ourrefno"]);
            $('#txt_epis_refno').val(refno_item["refno"]);
        }else if(target_id == 'refno_epno_payer_btn'){
            $('#ourrefno_epno_payer').val(refno_item["ourrefno"]);
            $('#refno_epno_payer').val(refno_item["refno"]);
        }
        
        $('#mdl_reference').modal('hide');
    });

    $('#tbl_epis_reference').on('click', 'tr', function () {
        let refno_item = self.refno_table.row( this ).data();
        selrowdata = refno_item;
        $('#btn_epis_view_gl').prop('disabled',false);
        
        $('#tbl_epis_reference tr').removeClass('active');
        $(this).addClass('active');
    });

    $('#mdl_reference').on('shown.bs.modal', function (e) {
        self.refno_table.columns.adjust().draw();
    });

    $('#mdl_new_gl').on('shown.bs.modal', function (e) {
        let oper = $('#mdl_new_gl').data('oper');
        $('#newgl-textmrn').text($('#txt_epis_mrn').val());
        $('#newgl-textname').text($('#txt_epis_name').text());
        $('#newgl-gltype').val('Multi Volume');
        $('#select_gl_tab li:first-child a').tab('show');
        $('#txt_newgl_corpcomp').val($('#txt_epis_payer').val());
        $('#hid_newgl_corpcomp').val($('#hid_epis_payer').val());

        if(oper=='edit'){
            autoinsert_rowdata_gl(selrowdata);
        }else if(oper=='add'){
            loadcorpstaff_gl(selrowdata);
        }
        onchg_gltype();
    });

    $('#select_gl_tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let selected_tab = $(e.target).text();
        $('#newgl-gltype').val(selected_tab);
        onchg_gltype();
    });

    function onchg_gltype(){
        let selected_tab = $('#newgl-gltype').val();
        $('#newgl-effdate').off('change');
        $('#newgl-effdate,#newgl-expdate').val('');
        $('#newgl-expdate').prop('readonly',false);
        $('#newgl-visitno_div,#newgl-expdate_div,#newgl-effdate_div').show();
        $('#newgl-effdate_div,#newgl-expdate_div,#newgl-visitno_div').removeClass('form-mandatory');

        switch(selected_tab){
            case 'Multi Volume':
                $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
                $('#newgl-effdate,#newgl-visitno').prop('required',true).addClass('form-mandatory');
                $('#newgl-expdate').prop('required',false);
                $('#newgl-expdate_div').hide();
                break;
            case 'Multi Date':
                $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
                $('#newgl-effdate,#newgl-expdate').prop('required',true).addClass('form-mandatory');
                $('#newgl-visitno').prop('required',false);
                $('#newgl-visitno_div').hide();
                break;
            case 'Open':
                $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
                $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
                $('#newgl-visitno,#newgl-expdate').prop('required',false);
                $('#newgl-visitno_div,#newgl-expdate_div').hide();
                break;
            case 'Single Use':
                $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
                $('#newgl-expdate').prop('readonly',true);
                $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
                $('#newgl-visitno').prop('required',false);
                $('#newgl-visitno_div').hide();

                $('#newgl-effdate').on('change',function(){
                    $('#newgl-expdate').val($(this).val());
                });

                break;
            case 'Limit Amount':
                $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
                break;
            case 'Monthly Amount':
                $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
                break;
        }
    }

    $("#btnglsave").on('click',function(){
        if($('#glform').valid()){
            $("#btnglsave").prop('disabled',true);

            var _token = $('#csrf_token').val();
            let serializedForm = $( "#glform" ).serializeArray();
            let obj = {
                'debtorcode':$('#hid_epis_payer').val(),
                'mrn':$('#mrn_episode').val(),
                '_token': _token,
                'episno': $('#txt_epis_no').val(),
            };
            
            $.post('pat_mast/save_gl', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                
            },'json').fail(function(data) {
                alert(data.responseText);
                $("#btnglsave").prop('disabled',false);
            }).success(function(data){

                let target_id = $('#mdl_reference').data('target_id');
                if(target_id == 'btn_refno_info'){
                    $('#txt_epis_our_refno').val(data.ourrefno);
                    $('#txt_epis_refno').val(data.refno);
                }else if(target_id == 'refno_epno_payer_btn'){
                    $('#ourrefno_epno_payer').val(data.ourrefno);
                    $('#refno_epno_payer').val(data.refno);
                }

                $('#mdl_new_gl').modal('hide');
                $('#mdl_reference').modal('hide');
                $("#btnglsave").prop('disabled',false);

                $("#glform").trigger('reset');
            });
        }
    });

}

$("#mdl_item_selector,#mdl_epis_pay_mode,#mdl_reference,#mdl_new_gl,#mdl_bill_type,#mdl_new_panel,#mdl_glet").on('show.bs.modal', function () {
    $(this).eq(0).css('z-index','120');
});

function check_if_paymode_kena_gl(){
    if($('#cmb_epis_pay_mode').val() == 'GUARANTEE LETTER'){
        if($('#txt_epis_refno').val().trim() == ''){
            alert('Reference No. cant empty if patient using GL');
            return false;
        }
    }
    return true;
}

function check_if_paymode_kena_panel(){
    if($('#cmb_epis_pay_mode').val() == 'PANEL'){
        $('#newpanel-textmrn').text($('#txt_epis_mrn').val());
        $('#newpanel-textname').text($('#txt_epis_name').text());
        $('#txt_newpanel_corpcomp').val($('#txt_epis_payer').val());
        $('#hid_newpanel_corpcomp').val($('#hid_epis_payer').val());

        loadcorpstaff_gl(true);
        $('#mdl_new_panel').modal('show');
        return false;
    }
    return true;
}

function autoinsert_rowdata_gl(selrowdata){
    $('#newgl-staffid').val(selrowdata.staffid);
    $('#newgl-name').val(selrowdata.name);
    $('#txt_newgl_corpcomp').val(selrowdata.debtor_name);
    $('#hid_newgl_corpcomp').val(selrowdata.debtorcode);
    $('#txt_newgl_occupcode').val(selrowdata.occup_desc);
    $('#hid_newgl_occupcode').val(selrowdata.occupcode);
    $('#txt_newgl_relatecode').val(selrowdata.relate_desc);
    $('#hid_newgl_relatecode').val(selrowdata.relatecode);
    $('#newgl-childno').val(selrowdata.childno);
    $('#newgl-gltype').val(selrowdata.gltype);
    $('#newgl-effdate').val(selrowdata.startdate);
    $('#newgl-expdate').val(selrowdata.enddate);
    $('#newgl-visitno').val(selrowdata.visitno);
    $('#newgl-case').val(selrowdata.case);
    $('#newgl-refno').val(selrowdata.refno);
    $('#newgl-ourrefno').val(selrowdata.ourrefno);
    $('#newgl-remark').val(selrowdata.remark);


    $('#select_gl_tab a[href="'+selrowdata.gltype+'"]').tab('show');
}

function loadcorpstaff_gl(panel=false){
    let obj_param = {
           action:'loadcorpstaff',
           mrn:parseInt($('#txt_epis_mrn').val()),
           episno:parseInt($('#txt_epis_no').val()),
           oper:$("#episode_oper").val(),
           panel:panel,
       };

    $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
    },'json').done(function(data) {
        if(data.data != null){
            if(panel){
                $('#newpanel-staffid').val(data.data.staffid);
                $('#newpanel-name').val(data.data.name);
                $('#txt_newpanel_corpcomp').val(data.data.debtor_name);
                $('#hid_newpanel_corpcomp').val(data.data.debtorcode);
                $('#txt_newpanel_relatecode').val(data.data.relate_desc);
                $('#hid_newpanel_relatecode').val(data.data.relatecode);
                $('#newpanel-refno').val(data.data.refno);
                $('#newpanel-case').val(data.data.remark);
                $('#newpanel-deptcode').val(data.data.deptcode);
            }else{
                $('#newgl-staffid').val(data.data.staffid);
                $('#newgl-name').val(data.data.name);
                $('#txt_newgl_corpcomp').val(data.data.debtor_name);
                $('#hid_newgl_corpcomp').val(data.data.debtorcode);
                $('#txt_newgl_occupcode').val(data.data.occup_desc);
                $('#hid_newgl_occupcode').val(data.data.occupcode);
                $('#txt_newgl_relatecode').val(data.data.relate_desc);
                $('#hid_newgl_relatecode').val(data.data.relatecode);
                $('#newgl-childno').val(data.data.childno);
            }
        }
    });
}


