
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

/////////////////////////parameter for jqGridPatMedic url/////////////////////////
var urlParam_PatMedic = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'hisdb.patmedication',
    table_id: 'idno',
    filterCol: ['mrn','episno','auditno','chgcode'],
    filterVal: ['','','',''],
}

/////////////////////////parameter for jqGridFitChart url/////////////////////////
var urlParam_FitChart = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_fitchart',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

///////////////////////parameter for jqGridCirculation url///////////////////////
var urlParam_Circulation = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_circulation',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

///////////////////////parameter for jqGridSlidingScale url///////////////////////
var urlParam_SlidingScale = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_slidingscale',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

//////////////////////parameter for jqGridOthersChart1&2 url//////////////////////
var urlParam_OthersChart = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_othersdtl',
    table_id: 'idno',
    filterCol: ['mrn','episno','tabtitle'],
    filterVal: ['','',''],
}

//////////////////////parameter for bladder shift url//////////////////////
var urlParam_Bladder = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_bladder',
    table_id: 'idno',
    filterCol: ['mrn','episno','shift'],
    filterVal: ['','',''],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    textarea_init_nursingnote();
    textarea_init_intake();
    
    /////////////////////////////////////progressnote starts/////////////////////////////////////
    disableForm('#formProgress');
    
    $("#new_progress").click(function (){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_progress").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_progress").click(function (){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        $("#datetaken,#timetaken").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_progress").click(function (){
        disableForm('#formProgress');
        if($('#formProgress').isValid({requiredFields: ''}, conf, true)){
            saveForm_progress(function (){
                $("#cancel_progress").data('oper','edit');
                $("#cancel_progress").click();
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress');
            rdonly('#formProgress');
        }
    });
    
    $("#cancel_progress").click(function (){
        disableForm('#formProgress');
        button_state_progress($(this).data('oper'));
        $('#datetime_tbl').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////progressnote ends//////////////////////////////////////
    
    //////////////////////////////////////drug admin starts//////////////////////////////////////
    disableForm('#formDrug');
    rdonly('#formDrug');
    ///////////////////////////////////////drug admin ends///////////////////////////////////////
    
    //////////////////////////////////////treatment starts//////////////////////////////////////
    disableForm('#formTreatmentP');
    disableForm('#formInvestigation');
    disableForm('#formInjection');
    
    $("#new_treatment").click(function (){
        button_state_treatment('wait');
        enableForm('#formTreatmentP');
        rdonly('#formTreatmentP');
        emptyFormdata_div("#formTreatmentP",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("tr_idno").value = "";
        document.getElementById("tr_adduser").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_treatment").click(function (){
        button_state_treatment('wait');
        enableForm('#formTreatmentP');
        rdonly('#formTreatmentP');
        $("#tr_entereddate,#treatment_adduser").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_treatment").click(function (){
        disableForm('#formTreatmentP');
        if($('#formTreatmentP').isValid({requiredFields: ''}, conf, true)){
            saveForm_treatment(function (){
                $("#cancel_treatment").data('oper','edit');
                $("#cancel_treatment").click();
                // $("#jqGridPagerRefresh").click();
                // $('#tbl_treatment').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formTreatmentP');
            rdonly('#formTreatmentP');
        }
    });
    
    $("#cancel_treatment").click(function (){
        disableForm('#formTreatmentP');
        button_state_treatment($(this).data('oper'));
        $('#tbl_treatment').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    
    $("#new_investigation").click(function (){
        button_state_investigation('wait');
        enableForm('#formInvestigation');
        rdonly('#formInvestigation');
        emptyFormdata_div("#formInvestigation",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("inv_idno").value = "";
        document.getElementById("inv_adduser").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_investigation").click(function (){
        button_state_investigation('wait');
        enableForm('#formInvestigation');
        rdonly('#formInvestigation');
        $("#inv_entereddate,#investigation_adduser").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_investigation").click(function (){
        disableForm('#formInvestigation');
        if($('#formInvestigation').isValid({requiredFields: ''}, conf, true)){
            saveForm_investigation(function (){
                $("#cancel_investigation").data('oper','edit');
                $("#cancel_investigation").click();
                // $("#jqGridPagerRefresh").click();
                // $('#tbl_investigation').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formInvestigation');
            rdonly('#formInvestigation');
        }
    });
    
    $("#cancel_investigation").click(function (){
        disableForm('#formInvestigation');
        button_state_investigation($(this).data('oper'));
        $('#tbl_investigation').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    
    $("#new_injection").click(function (){
        button_state_injection('wait');
        enableForm('#formInjection');
        rdonly('#formInjection');
        emptyFormdata_div("#formInjection",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("inj_idno").value = "";
        document.getElementById("inj_adduser").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_injection").click(function (){
        button_state_injection('wait');
        enableForm('#formInjection');
        rdonly('#formInjection');
        $("#inj_entereddate,#injection_adduser").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_injection").click(function (){
        disableForm('#formInjection');
        if($('#formInjection').isValid({requiredFields: ''}, conf, true)){
            saveForm_injection(function (){
                $("#cancel_injection").data('oper','edit');
                $("#cancel_injection").click();
                // $("#jqGridPagerRefresh").click();
                // $('#tbl_injection').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formInjection');
            rdonly('#formInjection');
        }
    });
    
    $("#cancel_injection").click(function (){
        disableForm('#formInjection');
        button_state_injection($(this).data('oper'));
        $('#tbl_injection').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    ///////////////////////////////////////treatment ends///////////////////////////////////////
    
    //////////////////////////////////////careplan starts//////////////////////////////////////
    disableForm('#formCarePlan');
    
    $("#new_careplan").click(function (){
        button_state_careplan('wait');
        enableForm('#formCarePlan');
        rdonly('#formCarePlan');
        emptyFormdata_div("#formCarePlan",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        // dialog_mrn_edit.on();
    });
    
    $("#save_careplan").click(function (){
        disableForm('#formCarePlan');
        if($('#formCarePlan').isValid({requiredFields: ''}, conf, true)){
            saveForm_careplan(function (){
                $("#cancel_careplan").data('oper','add');
                $("#cancel_careplan").click();
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formCarePlan');
            rdonly('#formCarePlan');
        }
    });
    
    $("#cancel_careplan").click(function (){
        disableForm('#formCarePlan');
        button_state_careplan($(this).data('oper'));
        $('#tbl_careplan_date').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    ///////////////////////////////////////careplan ends///////////////////////////////////////
    
    ////////////////////////////////////othersChart1 starts////////////////////////////////////
    disableForm('#formOthersChart1');
    
    $("#new_othersChart1").click(function (){
        get_default_othersChart1();
        button_state_othersChart1('wait');
        enableForm('#formOthersChart1');
        rdonly('#formOthersChart1');
        emptyFormdata_div("#formOthersChart1",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#othersChart1_tabtitle']);
        $('#othersChart1_diag').prop('disabled',true);
    });
    
    $("#edit_othersChart1").click(function (){
        button_state_othersChart1('wait');
        enableForm('#formOthersChart1');
        rdonly('#formOthersChart1');
        $('#othersChart1_diag').prop('disabled',true);
    });
    
    $("#save_othersChart1").click(function (){
        disableForm('#formOthersChart1');
        if($('#formOthersChart1').isValid({requiredFields: ''}, conf, true)){
            saveForm_othersChart1(function (){
                $("#cancel_othersChart1").data('oper','edit');
                $("#cancel_othersChart1").click();
                $("#jqGridPagerRefresh_OthersChart1").click();
                $('#p_error').text(''); // remove error text
            });
        }else{
            enableForm('#formOthersChart1');
            rdonly('#formOthersChart1');
        }
    });
    
    $("#cancel_othersChart1").click(function (){
        disableForm('#formOthersChart1');
        button_state_othersChart1($(this).data('oper'));
    });
    /////////////////////////////////////othersChart1 ends/////////////////////////////////////
    
    ////////////////////////////////////othersChart2 starts////////////////////////////////////
    disableForm('#formOthersChart2');
    
    $("#new_othersChart2").click(function (){
        get_default_othersChart2();
        button_state_othersChart2('wait');
        enableForm('#formOthersChart2');
        rdonly('#formOthersChart2');
        emptyFormdata_div("#formOthersChart2",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#othersChart2_tabtitle']);
        $('#othersChart2_diag').prop('disabled',true);
    });
    
    $("#edit_othersChart2").click(function (){
        button_state_othersChart2('wait');
        enableForm('#formOthersChart2');
        rdonly('#formOthersChart2');
        $('#othersChart2_diag').prop('disabled',true);
    });
    
    $("#save_othersChart2").click(function (){
        disableForm('#formOthersChart2');
        if($('#formOthersChart2').isValid({requiredFields: ''}, conf, true)){
            saveForm_othersChart2(function (){
                $("#cancel_othersChart2").data('oper','edit');
                $("#cancel_othersChart2").click();
                $("#jqGridPagerRefresh_OthersChart2").click();
                $('#p_error2').text(''); // remove error text
            });
        }else{
            enableForm('#formOthersChart2');
            rdonly('#formOthersChart2');
        }
    });
    
    $("#cancel_othersChart2").click(function (){
        disableForm('#formOthersChart2');
        button_state_othersChart2($(this).data('oper'));
    });
    /////////////////////////////////////othersChart2 ends/////////////////////////////////////
    
    ////////////////////////////////////upload file starts////////////////////////////////////
    $("#invChart_click").on("click", function (){
        $("#invChrt_file").click();
    });
    
    $('#invChrt_file').on("change", function (){
        let filename = $(this).val();
        uploadfile();
    });
    
    $('#invChart_file tbody').on('click', 'tr', function (){
        $('#invChart_file tr').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#invChart_file tbody').on('dblclick', 'tr', function (){
        $('#invChart_file tr').removeClass('active');
        $(this).addClass('active');
        invChart_data = invChart_tbl.row( this ).data();
        oper = 'edit';
    });
    /////////////////////////////////////upload file ends/////////////////////////////////////
    
    ///////////////////////////////////InvChartDialog starts///////////////////////////////////
    $("#InvChartDialog").dialog({
        autoOpen: false,
        width: 5/10 * $(window).width(),
        modal: true,
        open: function (){
            // dialog_debtorFrom.on();
            // dialog_debtorTo.on();
            parent_close_disabled(true);
        },
        close: function (event, ui){
            // dialog_debtorFrom.off();
            // dialog_debtorTo.off();
            parent_close_disabled(false);
        },
        buttons: [{
            text: "Print Chart", click: function (){
                window.open('./nursingnote/invChart_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val(), '_blank');
                // window.location='./nursingnote/invChart_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val();
            }
        },{
            text: "Cancel", click: function (){
                $(this).dialog('close');
            }
        }],
    });
    ////////////////////////////////////InvChartDialog ends////////////////////////////////////
    
    ////////////////////////////////MorseFallScaleDialog starts////////////////////////////////
    $("#MorseFallScaleDialog").dialog({
        autoOpen: false,
        width: 5/10 * $(window).width(),
        modal: true,
        open: function (){
            // dialog_debtorFrom.on();
            // dialog_debtorTo.on();
            parent_close_disabled(true);
        },
        close: function (event, ui){
            // dialog_debtorFrom.off();
            // dialog_debtorTo.off();
            parent_close_disabled(false);
        },
        buttons: [{
            text: "Print Chart", click: function (){
                window.open('./nursingnote/morsefallscale_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&datefr='+$("#morsefallscale_datefr").val()+'&dateto='+$("#morsefallscale_dateto").val()+'&age='+$("#age_nursNote").val(), '_blank');
                // window.location='./nursingnote/morsefallscale_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val();
            }
        },{
            text: "Cancel", click: function (){
                $(this).dialog('close');
            }
        }],
    });
    /////////////////////////////////MorseFallScaleDialog ends/////////////////////////////////
    
    ////////////////////////////////////print button starts////////////////////////////////////
    $("#invChart_chart").click(function (){
        $("#InvChartDialog").dialog("open");
    });
    
    $("#fitchart_chart").click(function (){
        window.open('./nursingnote/fitchart_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
    });
    
    $("#circulation_chart").click(function (){
        window.open('./nursingnote/circulation_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&age='+$("#age_nursNote").val(), '_blank');
    });
    
    $("#slidingScale_chart").click(function (){
        window.open('./nursingnote/slidingScale_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
    });
    
    $("#othersChart1_chart").click(function (){
        window.open('./nursingnote/othersChart_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&tabtitle='+$("#othersChart1_tabtitle").val(), '_blank');
    });
    
    $("#othersChart2_chart").click(function (){
        window.open('./nursingnote/othersChart_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&tabtitle='+$("#othersChart2_tabtitle").val(), '_blank');
    });
    
    $("#bladder_chart").click(function (){
        window.open('./nursingnote/bladder_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
    });
    
    $("#morsefallscale_chart").click(function (){
        $("#MorseFallScaleDialog").dialog("open");
    });
    /////////////////////////////////////print button ends/////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    $("#jqGridNursNote_panel").on("shown.bs.collapse", function (){
        SmoothScrollTo("#jqGridNursNote_panel", 500);
        
        let curtype = $(this).data('curtype');
        if($('#epistycode').val() == 'IP'){
            $('#jqGridNursNote_panel_tabs.nav-tabs a#'+curtype).tab('show');
        }else if($('#epistycode').val() == 'OP'){
            $('#jqGridNursNote_panel_tabs.nav-tabs a#'+'navtab_progress').tab('show');
        }
    });
    
    $("#jqGridNursNote_panel").on("hidden.bs.collapse", function (){
        button_state_progress('empty');
        button_state_intake('empty');
        button_state_treatment('empty');
        button_state_investigation('empty');
        button_state_injection('empty');
        button_state_careplan('empty');
        button_state_othersChart1('empty');
        button_state_othersChart2('empty');
        button_state_glasgow('empty');
        button_state_pivc('empty');
        
        disableForm('#formProgress');
        disableForm('#formIntake');
        disableForm('#formTreatmentP');
        disableForm('#formInvestigation');
        disableForm('#formInjection');
        disableForm('#formCarePlan');
        disableForm('#formOthersChart1');
        disableForm('#formOthersChart2');
        disableForm('#formGlasgow');
        disableForm('#formPivc');
        
        refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'kosongkan');
        refreshGrid('#jqGridFitChart',urlParam_FitChart,'kosongkan');
        refreshGrid('#jqGridCirculation',urlParam_Circulation,'kosongkan');
        refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'kosongkan');
        refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'kosongkan');
        refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'kosongkan');
        refreshGrid('#jqGridBladder1',urlParam_Bladder,'kosongkan');
        refreshGrid('#jqGridBladder2',urlParam_Bladder,'kosongkan');
        refreshGrid('#jqGridBladder3',urlParam_Bladder,'kosongkan');
        refreshGrid('#jqGridThrombo',urlParam_Thrombo,'kosongkan');
        
        $("#jqGridNursNote_panel > div").scrollTop(0);
        $("#jqGridNursNote_panel #jqGridNursNote_panel_tabs li").removeClass('active');
    });
    
    $('#jqGridNursNote_panel_tabs.nav-tabs a').on('shown.bs.tab', function (e){
        let type = $(this).data('type');
        let id = $(this).attr('id');
        $("#jqGridNursNote_panel").data('curtype',id);
        switch(type){
            case 'invChart':
                // reload first tab dulu
                $('#invChart_file').DataTable().ajax.url('./nursingnote/table?action=invChart_file&mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()).load();
                populate_invHeader_getdata();
                
                var urlparam_tbl_invcat_FBC = {
                    action: 'get_invcat',
                    inv_code: 'FBC',
                }
                
                tbl_invcat_FBC.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_FBC)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeFBC']);
                    $('#tbl_invcat_FBC tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_invcat_FBC').DataTable().ajax.reload();
                $("#jqGridInvChart_FBC").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartFBC_c")[0].offsetWidth-$("#jqGridInvChartFBC_c")[0].offsetLeft-58));
                break;
            case 'progress':
                populate_progressnote_getdata();
                
                var urlparam_datetime_tbl = {
                    action: 'get_table_datetime',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                datetime_tbl.ajax.url("./nursingnote/table?"+$.param(urlparam_datetime_tbl)).load(function (data){
                    emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetime_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#datetime_tbl').DataTable().ajax.reload();
                break;
            case 'intake':
                populate_intakeoutput_getdata();
                
                var urlparam_tbl_intake_date = {
                    action: 'get_datetime_intake',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                tbl_intake_date.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_intake_date)).load(function (data){
                    emptyFormdata_div("#formIntake",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_intake_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_intake_date').DataTable().ajax.reload();
                
                $('#jqGridNursNote_intake_tabs.nav-tabs a').on('shown.bs.tab', function (e){
                    let shift = $(this).data('shift');
                    switch(shift){
                        case 'first':
                            textarea_init_intake();
                            break;
                        case 'second':
                            textarea_init_intake();
                            break;
                        case 'third':
                            textarea_init_intake();
                            break;
                    }
                });
                break;
            case 'drug':
                populate_drugadmin_getdata();
                
                var urlparam_tbl_prescription = {
                    action: 'get_prescription',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    chggroup: $('#ordcomtt_phar').val(),
                }
                
                tbl_prescription.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_prescription)).load(function (data){
                    emptyFormdata_div("#formDrug",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_prescription tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_prescription').DataTable().ajax.reload();
                $("#jqGridPatMedic").jqGrid('setGridWidth', Math.floor($("#jqGridPatMedic_c")[0].offsetWidth-$("#jqGridPatMedic_c")[0].offsetLeft-30));
                break;
            case 'treatment':
                populate_treatment_getdata();
                
                var urlparam_tbl_treatment = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "TREATMENT",
                }
                
                tbl_treatment.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_treatment)).load(function (data){
                    emptyFormdata_div("#formTreatmentP",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_treatment tbody tr:eq(0)').click(); // to select first row
                });
                
                var urlparam_tbl_investigation = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "INVESTIGATION",
                }
                
                tbl_investigation.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_investigation)).load(function (data){
                    emptyFormdata_div("#formInvestigation",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_investigation tbody tr:eq(0)').click(); // to select first row
                });
                
                var urlparam_tbl_injection = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "INJECTION",
                }
                
                tbl_injection.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_injection)).load(function (data){
                    emptyFormdata_div("#formInjection",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_injection tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_treatment').DataTable().ajax.reload();
                // $('#tbl_investigation').DataTable().ajax.reload();
                // $('#tbl_injection').DataTable().ajax.reload();
                break;
            case 'careplan':
                populate_careplan_getdata();
                
                var urlparam_tbl_careplan_date = {
                    action: 'get_datetime_careplan',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                tbl_careplan_date.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_careplan_date)).load(function (data){
                    emptyFormdata_div("#formCarePlan",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_careplan_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_careplan_date').DataTable().ajax.reload();
                break;
            case 'fitchart':
                populate_fitchart_getdata();
                
                urlParam_FitChart.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_FitChart.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridFitChart',urlParam_FitChart,'add');
                
                $("#jqGridFitChart").jqGrid('setGridWidth', Math.floor($("#jqGridFitChart_c")[0].offsetWidth-$("#jqGridFitChart_c")[0].offsetLeft-30));
                break;
            case 'circulation':
                populate_circulation_getdata();
                
                urlParam_Circulation.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_Circulation.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridCirculation',urlParam_Circulation,'add');
                
                $("#jqGridCirculation").jqGrid('setGridWidth', Math.floor($("#jqGridCirculation_c")[0].offsetWidth-$("#jqGridCirculation_c")[0].offsetLeft-30));
                break;
            case 'slidingScale':
                // populate_slidingScale_getdata();
                
                urlParam_SlidingScale.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_SlidingScale.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'add');
                
                $("#jqGridSlidingScale").jqGrid('setGridWidth', Math.floor($("#jqGridSlidingScale_c")[0].offsetWidth-$("#jqGridSlidingScale_c")[0].offsetLeft-30));
                break;
            case 'othersChart1':
                populate_othersChart1_getdata();
                
                urlParam_OthersChart.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_OthersChart.filterVal[1] = $("#episno_nursNote").val();
                urlParam_OthersChart.filterVal[2] = $("#othersChart1_tabtitle").val();
                
                refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'add');
                
                $("#jqGridOthersChart1").jqGrid('setGridWidth', Math.floor($("#jqGridOthersChart1_c")[0].offsetWidth-$("#jqGridOthersChart1_c")[0].offsetLeft-30));
                break;
            case 'othersChart2':
                populate_othersChart2_getdata();
                
                urlParam_OthersChart.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_OthersChart.filterVal[1] = $("#episno_nursNote").val();
                urlParam_OthersChart.filterVal[2] = $("#othersChart2_tabtitle").val();
                
                refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'add');
                
                $("#jqGridOthersChart2").jqGrid('setGridWidth', Math.floor($("#jqGridOthersChart2_c")[0].offsetWidth-$("#jqGridOthersChart2_c")[0].offsetLeft-30));
                break;
            case 'bladder':
                get_total_IO1();
                
                $("#jqGridBladder1").jqGrid('setGridWidth', Math.floor($("#jqGridBladder_c_1")[0].offsetWidth-$("#jqGridBladder_c_1")[0].offsetLeft-30));
                urlParam_Bladder.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_Bladder.filterVal[1] = $("#episno_nursNote").val();
                urlParam_Bladder.filterVal[2] = $("#firstShift").val();
                refreshGrid('#jqGridBladder1',urlParam_Bladder,'add');
                
                $('#jqGridNursNote_bladder_tabs.nav-tabs a').on('shown.bs.tab', function (e){
                    let type = $(this).data('type');
                    console.log(type);
                    switch(type){
                        case 'firstShift':
                            get_total_IO1();
                            urlParam_Bladder.filterVal[0] = $("#mrn_nursNote").val();
                            urlParam_Bladder.filterVal[1] = $("#episno_nursNote").val();
                            urlParam_Bladder.filterVal[2] = $("#firstShift").val();
                            refreshGrid('#jqGridBladder1',urlParam_Bladder,'add');
                            $("#jqGridBladder1").jqGrid('setGridWidth', Math.floor($("#jqGridBladder_c_1")[0].offsetWidth-$("#jqGridBladder_c_1")[0].offsetLeft-30));
                            break;
                        case 'secondShift':
                            get_total_IO2();
                            urlParam_Bladder.filterVal[0] = $("#mrn_nursNote").val();
                            urlParam_Bladder.filterVal[1] = $("#episno_nursNote").val();
                            urlParam_Bladder.filterVal[2] = $("#secondShift").val();
                            refreshGrid('#jqGridBladder2',urlParam_Bladder,'add');
                            $("#jqGridBladder2").jqGrid('setGridWidth', Math.floor($("#jqGridBladder_c_2")[0].offsetWidth-$("#jqGridBladder_c_2")[0].offsetLeft-30));
                            break;
                        case 'thirdShift':
                            get_total_IO3();
                            urlParam_Bladder.filterVal[0] = $("#mrn_nursNote").val();
                            urlParam_Bladder.filterVal[1] = $("#episno_nursNote").val();
                            urlParam_Bladder.filterVal[2] = $("#thirdShift").val();
                            refreshGrid('#jqGridBladder3',urlParam_Bladder,'add');
                            $("#jqGridBladder3").jqGrid('setGridWidth', Math.floor($("#jqGridBladder_c_3")[0].offsetWidth-$("#jqGridBladder_c_3")[0].offsetLeft-30));
                            break;
                    }
                });
                break;
            case 'gcs':
                populate_glasgow_getdata();
                
                var urlparam_datetimegcs_tbl = {
                    action: 'get_table_datetimeGCS',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                datetimegcs_tbl.ajax.url("./glasgow/table?"+$.param(urlparam_datetimegcs_tbl)).load(function (data){
                    emptyFormdata_div("#formGlasgow",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetimegcs_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#datetimegcs_tbl').DataTable().ajax.reload();
                break;
            case 'pivc':
                populate_pivc_getdata();
                
                var urlparam_datetimepivc_tbl = {
                    action: 'get_table_datetimePIVC',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                datetimepivc_tbl.ajax.url("./pivc/table?"+$.param(urlparam_datetimepivc_tbl)).load(function (data){
                    emptyFormdata_div("#formPivc",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetimepivc_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#datetimepivc_tbl').DataTable().ajax.reload();
                break;
            case 'morsefallscale':
                populate_morsefallscale_getdata();
                
                var urlparam_tbl_morsefallscale = {
                    action: 'get_datetime_morsefallscale',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                tbl_morsefallscale_date.ajax.url("./morsefallscale/table?"+$.param(urlparam_tbl_morsefallscale)).load(function (data){
                    emptyFormdata_div("#formMorseFallScale",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#morsefallscale_ward','#morsefallscale_diag','#morsefallscale_admdate']);
                    $('#tbl_morsefallscale_date tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_morsefallscale_date').DataTable().ajax.reload();
                break;
            case 'thrombo':
                populate_thrombo_getdata();
                
                var urlparam_datetimethrombo_tbl = {
                    action: 'get_table_datetimeThrombo',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                datetimethrombo_tbl.ajax.url("./thrombophlebitis/table?"+$.param(urlparam_datetimethrombo_tbl)).load(function (data){
                    emptyFormdata_div("#formThrombo",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetimethrombo_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                $("#jqGridThrombo").jqGrid('setGridWidth', Math.floor($("#jqGridThrombo_c")[0].offsetWidth-$("#jqGridThrombo_c")[0].offsetLeft-30));
                break;
        }
    });
    
    $('#jqGridNursNote_inv_tabs.nav-tabs a').on('shown.bs.tab', function (e){
        let type = $(this).data('type');
        switch(type){
            case 'FBC':
                var urlparam_tbl_invcat_FBC = {
                    action: 'get_invcat',
                    inv_code: 'FBC',
                }
                
                tbl_invcat_FBC.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_FBC)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeFBC']);
                    $('#tbl_invcat_FBC tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_FBC').DataTable().ajax.reload();
                $("#jqGridInvChart_FBC").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartFBC_c")[0].offsetWidth-$("#jqGridInvChartFBC_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_FBC',urlParam_FBC,'add');
                break;
            case 'Coag':
                var urlparam_tbl_invcat_Coag = {
                    action: 'get_invcat',
                    inv_code: 'Coag',
                }
                
                tbl_invcat_Coag.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_Coag)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCoag']);
                    $('#tbl_invcat_Coag tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_Coag').DataTable().ajax.reload();
                $("#jqGridInvChart_Coag").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartCoag_c")[0].offsetWidth-$("#jqGridInvChartCoag_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'add');
                break;
            case 'RP':
                var urlparam_tbl_invcat_RP = {
                    action: 'get_invcat',
                    inv_code: 'RP',
                }
                
                tbl_invcat_RP.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_RP)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeRP']);
                    $('#tbl_invcat_RP tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_RP').DataTable().ajax.reload();
                $("#jqGridInvChart_RP").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartRP_c")[0].offsetWidth-$("#jqGridInvChartRP_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_RP',urlParam_RP,'add');
                break;
            case 'LFT':
                var urlparam_tbl_invcat_LFT = {
                    action: 'get_invcat',
                    inv_code: 'LFT',
                }
                
                tbl_invcat_LFT.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_LFT)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeLFT']);
                    $('#tbl_invcat_LFT tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_LFT').DataTable().ajax.reload();
                $("#jqGridInvChart_LFT").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartLFT_c")[0].offsetWidth-$("#jqGridInvChartLFT_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_LFT',urlParam_LFT,'add');
                break;
            case 'Elect':
                var urlparam_tbl_invcat_Elect = {
                    action: 'get_invcat',
                    inv_code: 'Elect',
                }
                
                tbl_invcat_Elect.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_Elect)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeElect']);
                    $('#tbl_invcat_Elect tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_Elect').DataTable().ajax.reload();
                $("#jqGridInvChart_Elect").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartElect_c")[0].offsetWidth-$("#jqGridInvChartElect_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_Elect',urlParam_Elect,'add');
                break;
            case 'ABGVBG':
                var urlparam_tbl_invcat_ABGVBG = {
                    action: 'get_invcat',
                    inv_code: 'ABG/VBG',
                }
                
                tbl_invcat_ABGVBG.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_ABGVBG)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeABGVBG']);
                    $('#tbl_invcat_ABGVBG tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_ABGVBG').DataTable().ajax.reload();
                $("#jqGridInvChart_ABGVBG").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartABGVBG_c")[0].offsetWidth-$("#jqGridInvChartABGVBG_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_ABGVBG',urlParam_ABGVBG,'add');
                break;
            case 'UFEME':
                var urlparam_tbl_invcat_UFEME = {
                    action: 'get_invcat',
                    inv_code: 'UFEME',
                }
                
                tbl_invcat_UFEME.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_UFEME)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeUFEME']);
                    $('#tbl_invcat_UFEME tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_UFEME').DataTable().ajax.reload();
                $("#jqGridInvChart_UFEME").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartUFEME_c")[0].offsetWidth-$("#jqGridInvChartUFEME_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'add');
                break;
            case 'CE':
                var urlparam_tbl_invcat_CE = {
                    action: 'get_invcat',
                    inv_code: 'CE',
                }
                
                tbl_invcat_CE.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_CE)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCE']);
                    $('#tbl_invcat_CE tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_CE').DataTable().ajax.reload();
                $("#jqGridInvChart_CE").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartCE_c")[0].offsetWidth-$("#jqGridInvChartCE_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_CE',urlParam_CE,'add');
                break;
            case 'CS':
                var urlparam_tbl_invcat_CS = {
                    action: 'get_invcat',
                    inv_code: 'C&S',
                }
                
                tbl_invcat_CS.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_CS)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCS']);
                    $('#tbl_invcat_CS tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_invcat_CS').DataTable().ajax.reload();
                $("#jqGridInvChart_CS").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartCS_c")[0].offsetWidth-$("#jqGridInvChartCS_c")[0].offsetLeft-58));
                refreshGrid('#jqGridInvChart_CS',urlParam_CS,'add');
                break;
        }
    });
    
    ////////////////////////////////////////progressnote starts////////////////////////////////////////
    $('#datetime_tbl tbody').on('click', 'tr', function (){
        var data = datetime_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetime_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetime_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_progressnote_getdata();
        $("#idno_progress").val(data.idno);
        
        var saveParam = {
            action: 'get_table_progress',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formProgress",data.nurshandover);
                $("#datetaken").val(data.date);
                
                button_state_progress('edit');
                textarea_init_nursingnote();
            }else{
                button_state_progress('add');
                textarea_init_nursingnote();
            }
        });
    });
    /////////////////////////////////////////progressnote ends/////////////////////////////////////////
    
    /////////////////////////////////////////drug admin starts/////////////////////////////////////////
    $('#tbl_prescription tbody').on('click', 'tr', function (){
        var data = tbl_prescription.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_prescription.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formDrug",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_prescription tbody tr').removeClass('active');
        $(this).addClass('active');
        
        populate_drugadmin_getdata();
        $("#trx_auditno").val(data.auditno);
        $("#trx_chgcode").val(data.chgcode);
        $("#trx_quantity").val(data.quantity);
        // $("#ftxtdosage").val(data.ftxtdosage);
        $("#dosage_nursNote").val(data.doscode_desc);
        $("#frequency_nursNote").val(data.frequency_desc);
        $("#instruction_nursNote").val(data.addinstruction_desc);
        $("#drugindicator_nursNote").val(data.drugindicator_desc);
        $("#doc_name").val($("#doctor_nursNote").val());
        textarea_init_nursingnote();
        get_total_qty();
        
        // jqGridPatMedic
        urlParam_PatMedic.filterVal[0] = data.mrn;
        urlParam_PatMedic.filterVal[1] = data.episno;
        urlParam_PatMedic.filterVal[2] = data.auditno;
        urlParam_PatMedic.filterVal[3] = data.chgcode;
        refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'add');
        
        // var saveParam={
        //     action: 'get_table_drug',
        // }
        
        // var postobj={
        //     _token: $('#csrf_token').val(),
        //     mrn: $("#mrn_nursNote").val(),
        //     episno: $("#episno_nursNote").val(),
        //     auditno: $("#trx_auditno").val(),
        //     chgcode: $("#trx_chgcode").val()
        // };
        
        // $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        // },'json').fail(function (data){
        //     alert('there is an error');
        // }).success(function (data){
        //     if(!$.isEmptyObject(data)){
        //         // autoinsert_rowdata("#formDrug",data.patmedication);
        //         $("#tot_qty").val(data.total_qty);
        //     }else{
                
        //     }
        // });
    });
    
    $("#tbl_prescription_refresh").click(function (){
        $('#tbl_prescription').DataTable().ajax.reload();
    });
    
    //////////////////////////jqGridPatMedic//////////////////////////
    var addmore_jqgrid = { more:false,state:false,edit:false }
    
    $("#jqGridPatMedic").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_nurs,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Failure', name: 'failure', width: 54, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
                editoptions: {
                    value: "None:None;Fasting:Fasting;Refused:Refused;OMIT:OMIT;On Home Leave:On Home Leave;Unable to take:Unable to take;Transfer to other ward:Transfer to other ward;Withhold:Withhold"
                }
            },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Quantity', name: 'qty', width: 35, editable: true, editrules: { required: true } },
            { label: 'Entered<br>By', name: 'enteredby', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'auditno', name: 'auditno', hidden: true },
            { label: 'chgcode', name: 'chgcode', hidden: true },
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
        pager: "#jqGridPagerPatMedic",
        loadComplete: function (){
            if(addmore_jqgrid.more == true){$('#jqGridPatMedic_iladd').click();}
            else{
                $('#jqGrid2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
            
            // calc_jq_height_onchange("jqGridPatMedic");
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridPatMedic_iledit").click();
        },
    });
    
    ////////////////////myEditOptions_add_PatMedic////////////////////
    var myEditOptions_add_PatMedic = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerRefresh_patMedic").hide();
            
            $("#jqGridPatMedic input[name='qty']").on('blur',calculate_total_qty);
            
            $("input[name='qty']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridPatMedic_ilsave').click();
                // addmore_jqgrid.state = true;
                // $('#jqGrid_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // addmore_jqgrid.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'add');
            errorField.length = 0;
            $("#jqGridPagerRefresh_patMedic").show();
            get_total_qty();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            var trx_qty = $("#trx_quantity").val();
            var grid_qty = $("#tot_qty").val();
            
            if(parseFloat(grid_qty) > parseFloat(trx_qty)){
                alert('Please check the quantity.');
                return false;
            }
            
            let data = $('#jqGridPatMedic').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    auditno: $('#trx_auditno').val(),
                    chgcode: $('#trx_chgcode').val(),
                    action: 'patMedic_save',
                });
            $("#jqGridPatMedic").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerRefresh_patMedic").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////jqGridPagerPatMedic////////////////////////
    $("#jqGridPatMedic").inlineNav('#jqGridPagerPatMedic', {
        add: true,
        edit: false,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_PatMedic
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerPatMedic", {
        id: "jqGridPagerRefresh_patMedic",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridPatMedic", urlParam_PatMedic);
        },
    });
    
    $("#jqGridPatMedic_ilcancel").click(function (){
        get_total_qty(); // refresh balik total quantity
    });
    
    function calculate_total_qty(){
        var rowids = $('#jqGridPatMedic').jqGrid('getDataIDs');
        var total_qty = 0;
        
        rowids.forEach(function (e,i){
            let quantity = $('#jqGridPatMedic input#'+e+'_qty').val();
            if(quantity != undefined){
                total_qty = parseFloat(total_qty)+parseFloat(quantity);
            }else{
                let rowdata = $('#jqGridPatMedic').jqGrid ('getRowData',e);
                total_qty = parseFloat(total_qty)+parseFloat(rowdata.qty);
            }
        });
        
        if(!isNaN(total_qty)){
            $('#tot_qty').val(numeral(total_qty).format('0,0.00'));
        }
    }
    
    function get_total_qty(){
        var saveParam = {
            action: 'get_table_drug',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            mrn: $("#mrn_nursNote").val(),
            episno: $("#episno_nursNote").val(),
            auditno: $("#trx_auditno").val(),
            chgcode: $("#trx_chgcode").val()
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                // autoinsert_rowdata("#formDrug",data.patmedication);
                $("#tot_qty").val(data.total_qty);
            }else{
                
            }
        });
    }
    //////////////////////////////////////////drug admin ends//////////////////////////////////////////
    
    /////////////////////////////////////////treatment starts/////////////////////////////////////////
    $('#tbl_treatment tbody').on('click', 'tr', function (){
        var data = tbl_treatment.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_treatment.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formTreatmentP",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_treatment tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_treatment('edit');
        }else{
            button_state_treatment('add');
        }
        
        // populate_treatment_getdata();
        $("#tr_idno").val(data.idno);
        $("#tr_adduser").val(data.adduser);
        
        var saveParam = {
            action: 'get_table_treatment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formTreatmentP",data.treatment);
                
                // button_state_treatment('edit');
                textarea_init_nursingnote();
            }else{
                // button_state_treatment('add');
                textarea_init_nursingnote();
            }
        });
    });
    
    $('#tbl_investigation tbody').on('click', 'tr', function (){
        var data = tbl_investigation.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_investigation.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvestigation",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_investigation tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_investigation('edit');
        }else{
            button_state_investigation('add');
        }
        
        // populate_investigation_getdata();
        $("#inv_idno").val(data.idno);
        $("#inv_adduser").val(data.adduser);
        
        var saveParam = {
            action: 'get_table_treatment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formInvestigation",data.investigation);
                
                // button_state_investigation('edit');
                textarea_init_nursingnote();
            }else{
                // button_state_investigation('add');
                textarea_init_nursingnote();
            }
        });
    });
    
    $('#tbl_injection tbody').on('click', 'tr', function (){
        var data = tbl_injection.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_injection.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInjection",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_injection tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_injection('edit');
        }else{
            button_state_injection('add');
        }
        
        // populate_injection_getdata();
        $("#inj_idno").val(data.idno);
        $("#inj_adduser").val(data.adduser);
        
        var saveParam = {
            action: 'get_table_treatment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formInjection",data.injection);
                
                // button_state_injection('edit');
                textarea_init_nursingnote();
            }else{
                // button_state_injection('add');
                textarea_init_nursingnote();
            }
        });
    });
    //////////////////////////////////////////treatment ends//////////////////////////////////////////
    
    /////////////////////////////////////////careplan starts/////////////////////////////////////////
    $('#tbl_careplan_date tbody').on('click', 'tr', function (){
        var data = tbl_careplan_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_careplan_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formCarePlan",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_careplan_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_careplan_getdata();
        
        var saveParam = {
            action: 'get_table_careplan',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formCarePlan",data.nurscareplan);
                
                button_state_careplan('add');
                textarea_init_nursingnote();
            }else{
                button_state_careplan('add');
                textarea_init_nursingnote();
            }
        });
    });
    //////////////////////////////////////////careplan ends//////////////////////////////////////////
    
    /////////////////////////////////////////InvChart starts/////////////////////////////////////////
    ///////////////////////////////////////////////FBC///////////////////////////////////////////////
    $('#tbl_invcat_FBC tbody').on('click', 'tr', function (){
        var data = tbl_invcat_FBC.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_FBC.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeFBC']);
        $('#tbl_invcat_FBC tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeFBC").val(data.inv_code);
        $("#inv_catFBC").val(data.inv_cat);
        
        urlParam_FBC.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_FBC.filterVal[1] = $("#episno_nursNote").val();
        urlParam_FBC.filterVal[2] = data.inv_code;
        urlParam_FBC.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_FBC',urlParam_FBC,'add');
        
        // var saveParam = {
        //     action: 'get_inv_table',
        // }
        
        // var postobj = {
        //     _token: $('#csrf_token').val(),
        //     idno: data.idno,
        //     inv_code: data.inv_code,
        // };
        
        // $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        // },'json').fail(function (data){
        //     alert('there is an error');
        // }).success(function (data){
        //     if(!$.isEmptyObject(data)){
        //         autoinsert_rowdata("#formInvChart",data.nurs_invest_cat);
        //     }else{
        //     }
        // });
    });
    
    //////////////////////////////////////////////Coag//////////////////////////////////////////////
    $('#tbl_invcat_Coag tbody').on('click', 'tr', function (){
        var data = tbl_invcat_Coag.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_Coag.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCoag']);
        $('#tbl_invcat_Coag tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeCoag").val(data.inv_code);
        $("#inv_catCoag").val(data.inv_cat);
        
        urlParam_Coag.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_Coag.filterVal[1] = $("#episno_nursNote").val();
        urlParam_Coag.filterVal[2] = data.inv_code;
        urlParam_Coag.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'add');
    });
    
    ///////////////////////////////////////////////RP///////////////////////////////////////////////
    $('#tbl_invcat_RP tbody').on('click', 'tr', function (){
        var data = tbl_invcat_RP.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_RP.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeRP']);
        $('#tbl_invcat_RP tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeRP").val(data.inv_code);
        $("#inv_catRP").val(data.inv_cat);
        
        urlParam_RP.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_RP.filterVal[1] = $("#episno_nursNote").val();
        urlParam_RP.filterVal[2] = data.inv_code;
        urlParam_RP.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_RP',urlParam_RP,'add');
    });
    
    //////////////////////////////////////////////LFT//////////////////////////////////////////////
    $('#tbl_invcat_LFT tbody').on('click', 'tr', function (){
        var data = tbl_invcat_LFT.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_LFT.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeLFT']);
        $('#tbl_invcat_LFT tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeLFT").val(data.inv_code);
        $("#inv_catLFT").val(data.inv_cat);
        
        urlParam_LFT.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_LFT.filterVal[1] = $("#episno_nursNote").val();
        urlParam_LFT.filterVal[2] = data.inv_code;
        urlParam_LFT.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_LFT',urlParam_LFT,'add');
    });
    
    /////////////////////////////////////////////Elect/////////////////////////////////////////////
    $('#tbl_invcat_Elect tbody').on('click', 'tr', function (){
        var data = tbl_invcat_Elect.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_Elect.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeElect']);
        $('#tbl_invcat_Elect tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeElect").val(data.inv_code);
        $("#inv_catElect").val(data.inv_cat);
        
        urlParam_Elect.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_Elect.filterVal[1] = $("#episno_nursNote").val();
        urlParam_Elect.filterVal[2] = data.inv_code;
        urlParam_Elect.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_Elect',urlParam_Elect,'add');
    });
    
    ////////////////////////////////////////////ABG/VBG////////////////////////////////////////////
    $('#tbl_invcat_ABGVBG tbody').on('click', 'tr', function (){
        var data = tbl_invcat_ABGVBG.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_ABGVBG.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeABGVBG']);
        $('#tbl_invcat_ABGVBG tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeABGVBG").val(data.inv_code);
        $("#inv_catABGVBG").val(data.inv_cat);
        
        urlParam_ABGVBG.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_ABGVBG.filterVal[1] = $("#episno_nursNote").val();
        urlParam_ABGVBG.filterVal[2] = data.inv_code;
        urlParam_ABGVBG.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_ABGVBG',urlParam_ABGVBG,'add');
    });
    
    /////////////////////////////////////////////UFEME/////////////////////////////////////////////
    $('#tbl_invcat_UFEME tbody').on('click', 'tr', function (){
        var data = tbl_invcat_UFEME.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_UFEME.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeUFEME']);
        $('#tbl_invcat_UFEME tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeUFEME").val(data.inv_code);
        $("#inv_catUFEME").val(data.inv_cat);
        
        urlParam_UFEME.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_UFEME.filterVal[1] = $("#episno_nursNote").val();
        urlParam_UFEME.filterVal[2] = data.inv_code;
        urlParam_UFEME.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'add');
    });
    
    ///////////////////////////////////////////////CE///////////////////////////////////////////////
    $('#tbl_invcat_CE tbody').on('click', 'tr', function (){
        var data = tbl_invcat_CE.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_CE.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCE']);
        $('#tbl_invcat_CE tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeCE").val(data.inv_code);
        $("#inv_catCE").val(data.inv_cat);
        
        urlParam_CE.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_CE.filterVal[1] = $("#episno_nursNote").val();
        urlParam_CE.filterVal[2] = data.inv_code;
        urlParam_CE.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_CE',urlParam_CE,'add');
    });
    
    ///////////////////////////////////////////////CS///////////////////////////////////////////////
    $('#tbl_invcat_CS tbody').on('click', 'tr', function (){
        var data = tbl_invcat_CS.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_invcat_CS.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeCS']);
        $('#tbl_invcat_CS tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#inv_codeCS").val(data.inv_code);
        $("#inv_catCS").val(data.inv_cat);
        
        urlParam_CS.filterVal[0] = $("#mrn_nursNote").val();
        urlParam_CS.filterVal[1] = $("#episno_nursNote").val();
        urlParam_CS.filterVal[2] = data.inv_code;
        urlParam_CS.filterVal[3] = data.inv_cat;
        refreshGrid('#jqGridInvChart_CS',urlParam_CS,'add');
    });
    //////////////////////////////////////////InvChart ends//////////////////////////////////////////
    
    /////////////////////////////////////////fitchart starts/////////////////////////////////////////
    /////////////////////////////////////////jqGridFitChart/////////////////////////////////////////
    var addmore_jqgrid2 = { more:false,state:false,edit:false }
    
    $("#jqGridFitChart").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_fitchart,
                    custom_value: galGridCustomValue_nurs
                }
            },
            // { label: 'Fit', name: 'fit', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
            //     editoptions: {
            //         style: "width: -webkit-fill-available;",
            //         rows: 5
            //     }
            // },
            { label: 'Fit', name: 'fit', width: 30, editable: true,
                editoptions: {
                    style: "text-transform: none;",
                }
            },
            { label: 'Duration', name: 'duration', width: 30, editable: true },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            // { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
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
        pager: "#jqGridPagerFitChart",
        loadComplete: function (){
            if(addmore_jqgrid2.more == true){$('#jqGridFitChart_iladd').click();}
            else{
                $('#jqGridFitChart').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid2.edit = addmore_jqgrid2.more = false; // reset
            
            // calc_jq_height_onchange("jqGridFitChart");
            
            if($("#jqGridFitChart").data('lastselrow') == undefined){
                $("#jqGridFitChart").setSelection($("#jqGridFitChart").getDataIDs()[0]);
            }else{
                $("#jqGridFitChart").setSelection($("#jqGridFitChart").data('lastselrow'));
                delay(function (){
                    $('#jqGridFitChart tr#'+$("#jqGridFitChart").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridFitChart_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerFitChart').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerFitChart").setSelection($("#jqGridPagerFitChart").getDataIDs()[0]);
            }
        },
    });
    
    ///////////////////////////////////myEditOptions_add_FitChart///////////////////////////////////
    var myEditOptions_add_FitChart = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").hide();
            
            $("#jqGridFitChart textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridFitChart_ilsave').click();
                // addmore_jqgrid2.state = true;
                // $('#jqGridFitChart_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
            addmore_jqgrid2.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridFitChart',urlParam_FitChart,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridFitChart',urlParam_FitChart,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridFitChart').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'FitChart_save',
                });
            $("#jqGridFitChart").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////myEditOptions_edit_FitChart//////////////////////////////////
    var myEditOptions_edit_FitChart = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").hide();
            
            $("#jqGridFitChart textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridFitChart_ilsave').click();
                // addmore_jqgrid2.state = true;
                // $('#jqGridFitChart_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridFitChart',urlParam_FitChart,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridFitChart',urlParam_FitChart,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridFitChart').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'FitChart_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridFitChart").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////////jqGridPagerFitChart//////////////////////////////////////
    $("#jqGridFitChart").inlineNav('#jqGridPagerFitChart', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_FitChart
        },
        editParams: myEditOptions_edit_FitChart
    }).jqGrid('navButtonAdd', "#jqGridPagerFitChart", {
        id: "jqGridPagerDelete_FitChart",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridFitChart").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridFitChart').idno,
                        action: 'FitChart_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridFitChart", urlParam_FitChart);
                    });
                }else{
                    $("#jqGridPagerDelete_FitChart,#jqGridPagerRefresh_FitChart").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerFitChart", {
        id: "jqGridPagerRefresh_FitChart",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridFitChart", urlParam_FitChart);
        },
    });
    
    $("#jqGridFitChart_ilcancel").click(function (){
        refreshGrid("#jqGridFitChart", urlParam_FitChart);
    });
    ////////////////////////////////////////////end grid////////////////////////////////////////////
    //////////////////////////////////////////fitchart ends//////////////////////////////////////////
    
    ///////////////////////////////////////circulation starts///////////////////////////////////////
    ///////////////////////////////////////jqGridCirculation///////////////////////////////////////
    var addmore_jqgrid3 = { more:false,state:false,edit:false }
    
    $("#jqGridCirculation").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_circulation,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Capillary Refill', name: 'capillary', width: 50, editable: true,
                editoptions: {
                    style: "text-transform: none;",
                }
            },
            { label: 'Skin Temp', name: 'skintemp', width: 40, editable: true },
            { label: 'Pulse', name: 'pulse', width: 40, editable: true },
            { label: 'Movement', name: 'movement', width: 50, editable: true,
                editoptions: {
                    style: "text-transform: none;",
                }
            },
            { label: 'Sensation', name: 'sensation', width: 50, editable: true,
                editoptions: {
                    style: "text-transform: none;",
                }
            },
            { label: 'Oedema', name: 'oedema', width: 50, editable: true,
                editoptions: {
                    style: "text-transform: none;",
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            // { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
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
        pager: "#jqGridPagerCirculation",
        loadComplete: function (){
            if(addmore_jqgrid3.more == true){$('#jqGridCirculation_iladd').click();}
            else{
                $('#jqGridCirculation').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid3.edit = addmore_jqgrid3.more = false; // reset
            
            // calc_jq_height_onchange("jqGridCirculation");
            
            if($("#jqGridCirculation").data('lastselrow') == undefined){
                $("#jqGridCirculation").setSelection($("#jqGridCirculation").getDataIDs()[0]);
            }else{
                $("#jqGridCirculation").setSelection($("#jqGridCirculation").data('lastselrow'));
                delay(function (){
                    $('#jqGridCirculation tr#'+$("#jqGridCirculation").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridCirculation_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerCirculation').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerCirculation").setSelection($("#jqGridPagerCirculation").getDataIDs()[0]);
            }
        },
    });
    
    /////////////////////////////////myEditOptions_add_Circulation/////////////////////////////////
    var myEditOptions_add_Circulation = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").hide();
            
            $("#jqGridCirculation input[name='oedema']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridCirculation_ilsave').click();
                // addmore_jqgrid3.state = true;
                // $('#jqGridCirculation_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid3.state == true)addmore_jqgrid3.more = true; // only addmore after save inline
            addmore_jqgrid3.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridCirculation',urlParam_Circulation,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridCirculation',urlParam_Circulation,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridCirculation').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'Circulation_save',
                });
            $("#jqGridCirculation").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_Circulation////////////////////////////////
    var myEditOptions_edit_Circulation = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").hide();
            
            $("#jqGridCirculation input[name='oedema']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridCirculation_ilsave').click();
                // addmore_jqgrid3.state = true;
                // $('#jqGridCirculation_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid3.state == true)addmore_jqgrid3.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridCirculation',urlParam_Circulation,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridCirculation',urlParam_Circulation,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridCirculation').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'Circulation_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridCirculation").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerCirculation////////////////////////////////////
    $("#jqGridCirculation").inlineNav('#jqGridPagerCirculation', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Circulation
        },
        editParams: myEditOptions_edit_Circulation
    }).jqGrid('navButtonAdd', "#jqGridPagerCirculation", {
        id: "jqGridPagerDelete_Circulation",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridCirculation").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridCirculation').idno,
                        action: 'Circulation_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridCirculation", urlParam_Circulation);
                    });
                }else{
                    $("#jqGridPagerDelete_Circulation,#jqGridPagerRefresh_Circulation").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerCirculation", {
        id: "jqGridPagerRefresh_Circulation",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridCirculation", urlParam_Circulation);
        },
    });
    
    $("#jqGridCirculation_ilcancel").click(function (){
        refreshGrid("#jqGridCirculation", urlParam_Circulation);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ////////////////////////////////////////circulation ends////////////////////////////////////////
    
    //////////////////////////////////////slidingScale starts//////////////////////////////////////
    //////////////////////////////////////jqGridSlidingScale//////////////////////////////////////
    var addmore_jqgrid4 = { more:false,state:false,edit:false }
    
    $("#jqGridSlidingScale").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_slidingScale,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Dextrostix', name: 'dextrostix', width: 40, editable: true },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            // { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
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
        pager: "#jqGridPagerSlidingScale",
        loadComplete: function (){
            if(addmore_jqgrid4.more == true){$('#jqGridSlidingScale_iladd').click();}
            else{
                $('#jqGridSlidingScale').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid4.edit = addmore_jqgrid4.more = false; // reset
            
            // calc_jq_height_onchange("jqGridSlidingScale");
            
            if($("#jqGridSlidingScale").data('lastselrow') == undefined){
                $("#jqGridSlidingScale").setSelection($("#jqGridSlidingScale").getDataIDs()[0]);
            }else{
                $("#jqGridSlidingScale").setSelection($("#jqGridSlidingScale").data('lastselrow'));
                delay(function (){
                    $('#jqGridSlidingScale tr#'+$("#jqGridSlidingScale").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridSlidingScale_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerSlidingScale').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerSlidingScale").setSelection($("#jqGridPagerSlidingScale").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_SlidingScale////////////////////////////////
    var myEditOptions_add_SlidingScale = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").hide();
            
            $("#jqGridSlidingScale textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridSlidingScale_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridSlidingScale_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            addmore_jqgrid4.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridSlidingScale').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'SlidingScale_save',
                });
            $("#jqGridSlidingScale").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ///////////////////////////////myEditOptions_edit_SlidingScale///////////////////////////////
    var myEditOptions_edit_SlidingScale = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").hide();
            
            $("#jqGridSlidingScale textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridSlidingScale_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridSlidingScale_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridSlidingScale',urlParam_SlidingScale,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridSlidingScale').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'SlidingScale_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridSlidingScale").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ///////////////////////////////////jqGridPagerSlidingScale///////////////////////////////////
    $("#jqGridSlidingScale").inlineNav('#jqGridPagerSlidingScale', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_SlidingScale
        },
        editParams: myEditOptions_edit_SlidingScale
    }).jqGrid('navButtonAdd', "#jqGridPagerSlidingScale", {
        id: "jqGridPagerDelete_SlidingScale",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridSlidingScale").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridSlidingScale').idno,
                        action: 'SlidingScale_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridSlidingScale", urlParam_SlidingScale);
                    });
                }else{
                    $("#jqGridPagerDelete_SlidingScale,#jqGridPagerRefresh_SlidingScale").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerSlidingScale", {
        id: "jqGridPagerRefresh_SlidingScale",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridSlidingScale", urlParam_SlidingScale);
        },
    });
    
    $("#jqGridSlidingScale_ilcancel").click(function (){
        refreshGrid("#jqGridSlidingScale", urlParam_SlidingScale);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////slidingScale ends///////////////////////////////////////
    
    //////////////////////////////////////othersChart1 starts//////////////////////////////////////
    //////////////////////////////////////jqGridOthersChart1//////////////////////////////////////
    var addmore_jqgrid5 = { more:false,state:false,edit:false }
    
    $("#jqGridOthersChart1").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_othersChart1,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'title', name: 'title', hidden: true },
            // { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
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
        pager: "#jqGridPagerOthersChart1",
        loadComplete: function (){
            if(addmore_jqgrid5.more == true){$('#jqGridOthersChart1_iladd').click();}
            else{
                $('#jqGridOthersChart1').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid5.edit = addmore_jqgrid5.more = false; // reset
            
            // calc_jq_height_onchange("jqGridOthersChart1");
            
            if($("#jqGridOthersChart1").data('lastselrow') == undefined){
                $("#jqGridOthersChart1").setSelection($("#jqGridOthersChart1").getDataIDs()[0]);
            }else{
                $("#jqGridOthersChart1").setSelection($("#jqGridOthersChart1").data('lastselrow'));
                delay(function (){
                    $('#jqGridOthersChart1 tr#'+$("#jqGridOthersChart1").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridOthersChart1_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerOthersChart1').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerOthersChart1").setSelection($("#jqGridPagerOthersChart1").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_OthersChart1////////////////////////////////
    var myEditOptions_add_OthersChart1 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").hide();
            
            $("#jqGridOthersChart1 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridOthersChart1_ilsave').click();
                // addmore_jqgrid5.state = true;
                // $('#jqGridOthersChart1_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid5.state == true)addmore_jqgrid5.more = true; // only addmore after save inline
            addmore_jqgrid5.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridOthersChart1').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    tabtitle: $('#othersChart1_tabtitle').val(),
                    action: 'OthersChart_save',
                });
            $("#jqGridOthersChart1").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_OthersChart1////////////////////////////////
    var myEditOptions_edit_OthersChart1 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").hide();
            
            $("#jqGridOthersChart1 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridOthersChart1_ilsave').click();
                // addmore_jqgrid5.state = true;
                // $('#jqGridOthersChart1_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid5.state == true)addmore_jqgrid5.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridOthersChart1',urlParam_OthersChart,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridOthersChart1').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'OthersChart_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridOthersChart1").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerOthersChart1////////////////////////////////////
    $("#jqGridOthersChart1").inlineNav('#jqGridPagerOthersChart1', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_OthersChart1
        },
        editParams: myEditOptions_edit_OthersChart1
    }).jqGrid('navButtonAdd', "#jqGridPagerOthersChart1", {
        id: "jqGridPagerDelete_OthersChart1",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridOthersChart1").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridOthersChart1').idno,
                        action: 'OthersChart_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridOthersChart1", urlParam_OthersChart);
                    });
                }else{
                    $("#jqGridPagerDelete_OthersChart1,#jqGridPagerRefresh_OthersChart1").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerOthersChart1", {
        id: "jqGridPagerRefresh_OthersChart1",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridOthersChart1", urlParam_OthersChart);
        },
    });
    
    $("#jqGridOthersChart1_ilcancel").click(function (){
        refreshGrid("#jqGridOthersChart1", urlParam_OthersChart);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////othersChart1 ends///////////////////////////////////////
    
    //////////////////////////////////////othersChart2 starts//////////////////////////////////////
    //////////////////////////////////////jqGridOthersChart2//////////////////////////////////////
    var addmore_jqgrid6 = { more:false,state:false,edit:false }
    
    $("#jqGridOthersChart2").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_othersChart2,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'title', name: 'title', hidden: true },
            // { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
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
        pager: "#jqGridPagerOthersChart2",
        loadComplete: function (){
            if(addmore_jqgrid6.more == true){$('#jqGridOthersChart2_iladd').click();}
            else{
                $('#jqGridOthersChart2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid6.edit = addmore_jqgrid6.more = false; // reset
            
            // calc_jq_height_onchange("jqGridOthersChart2");
            
            if($("#jqGridOthersChart2").data('lastselrow') == undefined){
                $("#jqGridOthersChart2").setSelection($("#jqGridOthersChart2").getDataIDs()[0]);
            }else{
                $("#jqGridOthersChart2").setSelection($("#jqGridOthersChart2").data('lastselrow'));
                delay(function (){
                    $('#jqGridOthersChart2 tr#'+$("#jqGridOthersChart2").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridOthersChart2_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerOthersChart2').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerOthersChart2").setSelection($("#jqGridPagerOthersChart2").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_OthersChart2////////////////////////////////
    var myEditOptions_add_OthersChart2 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").hide();
            
            $("#jqGridOthersChart2 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridOthersChart2_ilsave').click();
                // addmore_jqgrid6.state = true;
                // $('#jqGridOthersChart2_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid6.state == true)addmore_jqgrid6.more = true; // only addmore after save inline
            addmore_jqgrid6.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            
            let data = $('#jqGridOthersChart2').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    tabtitle: $('#othersChart2_tabtitle').val(),
                    action: 'OthersChart_save',
                });
            $("#jqGridOthersChart2").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_OthersChart2////////////////////////////////
    var myEditOptions_edit_OthersChart2 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").hide();
            
            $("#jqGridOthersChart2 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridOthersChart2_ilsave').click();
                // addmore_jqgrid6.state = true;
                // $('#jqGridOthersChart2_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid6.state == true)addmore_jqgrid6.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridOthersChart2',urlParam_OthersChart,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridOthersChart2').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'OthersChart_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridOthersChart2").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerOthersChart2////////////////////////////////////
    $("#jqGridOthersChart2").inlineNav('#jqGridPagerOthersChart2', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_OthersChart2
        },
        editParams: myEditOptions_edit_OthersChart2
    }).jqGrid('navButtonAdd', "#jqGridPagerOthersChart2", {
        id: "jqGridPagerDelete_OthersChart2",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridOthersChart2").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridOthersChart2').idno,
                        action: 'OthersChart_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridOthersChart2", urlParam_OthersChart);
                    });
                }else{
                    $("#jqGridPagerDelete_OthersChart2,#jqGridPagerRefresh_OthersChart2").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerOthersChart2", {
        id: "jqGridPagerRefresh_OthersChart2",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridOthersChart2", urlParam_OthersChart);
        },
    });
    
    $("#jqGridOthersChart2_ilcancel").click(function (){
        refreshGrid("#jqGridOthersChart2", urlParam_OthersChart);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////othersChart2 ends///////////////////////////////////////
    
    //////////////////////////////////////bladder 1 starts//////////////////////////////////////
    //////////////////////////////////////jqGridBladder1//////////////////////////////////////
    var addmore_jqgrid7 = { more:false,state:false,edit:false }
    
    $("#jqGridBladder1").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_bladder1,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Input', name: 'input', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Output', name: 'output', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '+ve', name: 'positive', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '-ve', name: 'negative', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'shift', name: 'shift', hidden: true },

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
        pager: "#jqGridPagerBladder1",
        loadComplete: function (){
            if(addmore_jqgrid7.more == true){$('#jqGridBladder1_iladd').click();}
            else{
                $('#jqGridBladder1').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid7.edit = addmore_jqgrid7.more = false; // reset
            
            // calc_jq_height_onchange("jqGridBladder1");
            
            if($("#jqGridBladder1").data('lastselrow') == undefined){
                $("#jqGridBladder1").setSelection($("#jqGridBladder1").getDataIDs()[0]);
            }else{
                $("#jqGridBladder1").setSelection($("#jqGridBladder1").data('lastselrow'));
                delay(function (){
                    $('#jqGridBladder1 tr#'+$("#jqGridBladder1").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridBladder1_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerBladder1').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerBladder1").setSelection($("#jqGridPagerBladder1").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_Bladder1////////////////////////////////
    var myEditOptions_add_Bladder1 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").hide();

            $("#jqGridBladder1 input[name='input']").on('blur',calculate_total_input1);
            $("#jqGridBladder1 input[name='output']").on('blur',calculate_total_output1);
            
            $("#jqGridBladder1 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder1_ilsave').click();
                // addmore_jqgrid7.state = true;
                // $('#jqGridBladder1_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid7.state == true)addmore_jqgrid7.more = true; // only addmore after save inline
            addmore_jqgrid7.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder1',urlParam_Bladder,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").show();
            get_total_IO1();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder1',urlParam_Bladder,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            
            let data = $('#jqGridBladder1').jqGrid ('getRowData', rowid);
            // console.log(data);

            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    firstShift: $('#firstShift').val(),
                    action: 'Bladder_save',
                });
            $("#jqGridBladder1").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_Bladder1////////////////////////////////
    var myEditOptions_edit_Bladder1 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").hide();
            
            $("#jqGridBladder1 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder1_ilsave').click();
                // addmore_jqgrid7.state = true;
                // $('#jqGridBladder1_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid7.state == true)addmore_jqgrid7.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder1',urlParam_Bladder,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder1',urlParam_Bladder,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridBladder1').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    firstShift: $('#firstShift').val(),
                    action: 'Bladder_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridBladder1").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerBladder1////////////////////////////////////
    $("#jqGridBladder1").inlineNav('#jqGridPagerBladder1', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Bladder1
        },
        editParams: myEditOptions_edit_Bladder1
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder1", {
        id: "jqGridPagerDelete_Bladder1",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridBladder1").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridBladder1').idno,
                        action: 'Bladder_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridBladder1", urlParam_Bladder);
                    });
                }else{
                    $("#jqGridPagerDelete_Bladder1,#jqGridPagerRefresh_Bladder1").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder1", {
        id: "jqGridPagerRefresh_Bladder1",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridBladder1", urlParam_Bladder);
        },
    });
    
    $("#jqGridPagerBladder1_ilcancel").click(function (){
        // refreshGrid("#jqGridBladder1", urlParam_Bladder);
        get_total_IO1();
    });

    function calculate_total_input1(){
        var rowids = $('#jqGridBladder1').jqGrid('getDataIDs');
        var total_input1 = 0;
        
        rowids.forEach(function (e,i){
            let input1 = $('#jqGridBladder1 input#'+e+'_input').val();
            if(input1 != undefined){
                total_input1 = parseFloat(total_input1)+parseFloat(input1);
            }else{
                let rowdata = $('#jqGridBladder1').jqGrid ('getRowData',e);
                total_input1 = parseFloat(total_input1)+parseFloat(rowdata.input);
            }
        });
        
        if(!isNaN(total_input1)){
            $('#tot_input1').val(numeral(total_input1).format('0,0.00'));
        }
    }

    function calculate_total_output1(){
        var rowids = $('#jqGridBladder1').jqGrid('getDataIDs');
        var total_output1 = 0;
        
        rowids.forEach(function (e,i){
            let output1 = $('#jqGridBladder1 input#'+e+'_output').val();
            if(output1 != undefined){
                total_output1 = parseFloat(total_output1)+parseFloat(output1);
            }else{
                let rowdata = $('#jqGridBladder1').jqGrid ('getRowData',e);
                total_output1 = parseFloat(total_output1)+parseFloat(rowdata.output);
            }
        });
        
        if(!isNaN(total_output1)){
            $('#tot_output1').val(numeral(total_output1).format('0,0.00'));
        }
    }

    function get_total_IO1(){
        var saveParam={
            action: 'get_table_bladder1',
        }
        
        var postobj={
            _token: $('#csrf_token').val(),
            mrn_nursNote: $("#mrn_nursNote").val(),
            episno_nursNote: $("#episno_nursNote").val(),
            firstShift: $("#firstShift").val()
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                $("#tot_input1").val(data.total_input1);
                $("#tot_output1").val(data.total_output1);
            }else{
                
            }
        });
    }
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////bladder 1 ends///////////////////////////////////////

    //////////////////////////////////////bladder 2 starts//////////////////////////////////////
    //////////////////////////////////////jqGridBladder2//////////////////////////////////////
    var addmore_jqgrid8 = { more:false,state:false,edit:false }
    
    $("#jqGridBladder2").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_bladder2,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Input', name: 'input', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Output', name: 'output', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '+ve', name: 'positive', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '-ve', name: 'negative', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'shift', name: 'shift', hidden: true },

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
        pager: "#jqGridPagerBladder2",
        loadComplete: function (){
            if(addmore_jqgrid8.more == true){$('#jqGridBladder2_iladd').click();}
            else{
                $('#jqGridBladder2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid8.edit = addmore_jqgrid8.more = false; // reset
            
            // calc_jq_height_onchange("jqGridBladder2");
            
            if($("#jqGridBladder2").data('lastselrow') == undefined){
                $("#jqGridBladder2").setSelection($("#jqGridBladder2").getDataIDs()[0]);
            }else{
                $("#jqGridBladder2").setSelection($("#jqGridBladder2").data('lastselrow'));
                delay(function (){
                    $('#jqGridBladder2 tr#'+$("#jqGridBladder2").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridBladder2_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerBladder2').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerBladder2").setSelection($("#jqGridPagerBladder2").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_Bladder1////////////////////////////////
    var myEditOptions_add_Bladder2 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").hide();

            $("#jqGridBladder2 input[name='input']").on('blur',calculate_total_input2);
            $("#jqGridBladder2 input[name='output']").on('blur',calculate_total_output2);
            
            $("#jqGridBladder2 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder2_ilsave').click();
                // addmore_jqgrid8.state = true;
                // $('#jqGridBladder2_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid8.state == true)addmore_jqgrid8.more = true; // only addmore after save inline
            addmore_jqgrid8.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder2',urlParam_Bladder,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").show();
            get_total_IO2();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder2',urlParam_Bladder,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            
            let data = $('#jqGridBladder2').jqGrid ('getRowData', rowid);
            // console.log(data);

            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    secondShift: $('#secondShift').val(),
                    action: 'Bladder_save',
                });
            $("#jqGridBladder2").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_Bladder2////////////////////////////////
    var myEditOptions_edit_Bladder2 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").hide();
            
            $("#jqGridBladder2 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder2_ilsave').click();
                // addmore_jqgrid8.state = true;
                // $('#jqGridBladder2_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid8.state == true)addmore_jqgrid8.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder2',urlParam_Bladder,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder2',urlParam_Bladder,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridBladder2').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    secondShift: $('#secondShift').val(),
                    action: 'Bladder_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridBladder2").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerBladder2////////////////////////////////////
    $("#jqGridBladder2").inlineNav('#jqGridPagerBladder2', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Bladder2
        },
        editParams: myEditOptions_edit_Bladder2
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder2", {
        id: "jqGridPagerDelete_Bladder2",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridBladder2").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridBladder2').idno,
                        action: 'Bladder_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridBladder2", urlParam_Bladder);
                    });
                }else{
                    $("#jqGridPagerDelete_Bladder2,#jqGridPagerRefresh_Bladder2").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder2", {
        id: "jqGridPagerRefresh_Bladder2",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridBladder2", urlParam_Bladder);
        },
    });
    
    $("#jqGridPagerBladder2_ilcancel").click(function (){
        //refreshGrid("#jqGridBladder2", urlParam_Bladder);
        get_total_IO2();
    });

    function calculate_total_input2(){
        var rowids = $('#jqGridBladder2').jqGrid('getDataIDs');
        var total_input2 = 0;
        
        rowids.forEach(function (e,i){
            let input2 = $('#jqGridBladder2 input#'+e+'_input').val();
            if(input2 != undefined){
                total_input2 = parseFloat(total_input2)+parseFloat(input2);
            }else{
                let rowdata = $('#jqGridBladder2').jqGrid ('getRowData',e);
                total_input2 = parseFloat(total_input2)+parseFloat(rowdata.input);
            }
        });
        
        if(!isNaN(total_input2)){
            $('#tot_input2').val(numeral(total_input2).format('0,0.00'));
        }
    }

    function calculate_total_output2(){
        var rowids = $('#jqGridBladder2').jqGrid('getDataIDs');
        var total_output2 = 0;
        
        rowids.forEach(function (e,i){
            let output2 = $('#jqGridBladder2 input#'+e+'_output').val();
            if(output2 != undefined){
                total_output2 = parseFloat(total_output2)+parseFloat(output2);
            }else{
                let rowdata = $('#jqGridBladder2').jqGrid ('getRowData',e);
                total_output2 = parseFloat(total_output2)+parseFloat(rowdata.output);
            }
        });
        
        if(!isNaN(total_output2)){
            $('#tot_output2').val(numeral(total_output2).format('0,0.00'));
        }
    }

    function get_total_IO2(){
        var saveParam={
            action: 'get_table_bladder2',
        }
        
        var postobj={
            _token: $('#csrf_token').val(),
            mrn_nursNote: $("#mrn_nursNote").val(),
            episno_nursNote: $("#episno_nursNote").val(),
            secondShift: $("#secondShift").val()
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                $("#tot_input2").val(data.total_input2);
                $("#tot_output2").val(data.total_output2);
            }else{
                
            }
        });
    }
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////bladder 2 ends///////////////////////////////////////
    
    //////////////////////////////////////bladder 3 starts//////////////////////////////////////
    //////////////////////////////////////jqGridBladder3//////////////////////////////////////
    var addmore_jqgrid9 = { more:false,state:false,edit:false }
    
    $("#jqGridBladder3").jqGrid({
        datatype: "local",
        editurl: "./nursingnote/form",
        colModel: [
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'entereddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_nurs },
                formatter: showdetail_nurs, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_bladder3,
                    custom_value: galGridCustomValue_nurs
                }
            },
            { label: 'Input', name: 'input', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Output', name: 'output', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '+ve', name: 'positive', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: '-ve', name: 'negative', width: 50, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'shift', name: 'shift', hidden: true },

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
        pager: "#jqGridPagerBladder3",
        loadComplete: function (){
            if(addmore_jqgrid9.more == true){$('#jqGridBladder3_iladd').click();}
            else{
                $('#jqGridBladder3').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid9.edit = addmore_jqgrid9.more = false; // reset
            
            // calc_jq_height_onchange("jqGridBladder3");
            
            if($("#jqGridBladder3").data('lastselrow') == undefined){
                $("#jqGridBladder3").setSelection($("#jqGridBladder3").getDataIDs()[0]);
            }else{
                $("#jqGridBladder3").setSelection($("#jqGridBladder3").data('lastselrow'));
                delay(function (){
                    $('#jqGridBladder3 tr#'+$("#jqGridBladder3").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridBladder3_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerBladder3').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerBladder3").setSelection($("#jqGridPagerBladder3").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_Bladder3////////////////////////////////
    var myEditOptions_add_Bladder3 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").hide();

            $("#jqGridBladder3 input[name='input']").on('blur',calculate_total_input3);
            $("#jqGridBladder3 input[name='output']").on('blur',calculate_total_output3);
            
            $("#jqGridBladder3 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder3_ilsave').click();
                // addmore_jqgrid9.state = true;
                // $('#jqGridBladder3_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid9.state == true)addmore_jqgrid9.more = true; // only addmore after save inline
            addmore_jqgrid9.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder3',urlParam_Bladder,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").show();
            get_total_IO3();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder3',urlParam_Bladder,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            
            let data = $('#jqGridBladder3').jqGrid ('getRowData', rowid);
            // console.log(data);

            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    thirdShift: $('#thirdShift').val(),
                    action: 'Bladder_save',
                });
            $("#jqGridBladder3").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_Bladder3////////////////////////////////
    var myEditOptions_edit_Bladder3 = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").hide();
            
            $("#jqGridBladder3 textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBladder3_ilsave').click();
                // addmore_jqgrid9.state = true;
                // $('#jqGridBladder3_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid9.state == true)addmore_jqgrid9.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBladder3',urlParam_Bladder,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error2').text(response.responseText);
            refreshGrid('#jqGridBladder3',urlParam_Bladder,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error2').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridBladder3').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn_nursNote: $('#mrn_nursNote').val(),
                    episno_nursNote: $('#episno_nursNote').val(),
                    thirdShift: $('#thirdShift').val(),
                    action: 'Bladder_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridBladder3").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerBladder3////////////////////////////////////
    $("#jqGridBladder3").inlineNav('#jqGridPagerBladder3', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Bladder3
        },
        editParams: myEditOptions_edit_Bladder3
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder3", {
        id: "jqGridPagerDelete_Bladder3",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridBladder3").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridBladder3').idno,
                        action: 'Bladder_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridBladder3", urlParam_Bladder);
                    });
                }else{
                    $("#jqGridPagerDelete_Bladder3,#jqGridPagerRefresh_Bladder3").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerBladder3", {
        id: "jqGridPagerRefresh_Bladder3",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridBladder3", urlParam_Bladder);
        },
    });
    
    $("#jqGridPagerBladder3_ilcancel").click(function (){
        // refreshGrid("#jqGridBladder3", urlParam_Bladder);
        get_total_IO3();
    });

    function calculate_total_input3(){
        var rowids = $('#jqGridBladder3').jqGrid('getDataIDs');
        var total_input3 = 0;
        
        rowids.forEach(function (e,i){
            let input3 = $('#jqGridBladder3 input#'+e+'_input').val();
            if(input3 != undefined){
                total_input3 = parseFloat(total_input3)+parseFloat(input3);
            }else{
                let rowdata = $('#jqGridBladder3').jqGrid ('getRowData',e);
                total_input3 = parseFloat(total_input3)+parseFloat(rowdata.input);
            }
        });
        
        if(!isNaN(total_input3)){
            $('#tot_input3').val(numeral(total_input3).format('0,0.00'));
        }
    }

    function calculate_total_output3(){
        var rowids = $('#jqGridBladder3').jqGrid('getDataIDs');
        var total_output3 = 0;
        
        rowids.forEach(function (e,i){
            let output3 = $('#jqGridBladder3 input#'+e+'_output').val();
            if(output3 != undefined){
                total_output3 = parseFloat(total_output3)+parseFloat(output3);
            }else{
                let rowdata = $('#jqGridBladder3').jqGrid ('getRowData',e);
                total_output3 = parseFloat(total_output3)+parseFloat(rowdata.output);
            }
        });
        
        if(!isNaN(total_output3)){
            $('#tot_output3').val(numeral(total_output3).format('0,0.00'));
        }
    }

    function get_total_IO3(){
        var saveParam={
            action: 'get_table_bladder3',
        }
        
        var postobj={
            _token: $('#csrf_token').val(),
            mrn_nursNote: $("#mrn_nursNote").val(),
            episno_nursNote: $("#episno_nursNote").val(),
            thirdShift: $("#thirdShift").val()
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                $("#tot_input3").val(data.total_input3);
                $("#tot_output3").val(data.total_output3);
            }else{
                
            }
        });
    }
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    ///////////////////////////////////////bladder 3 ends////////////////////////////////////////
});

/////////////////////progressnote starts/////////////////////
var datetime_tbl = $('#datetime_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetaken', 'width': '25%' },
        { 'data': 'timetaken', 'width': '25%' },
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'location', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////progressnote ends//////////////////////

//////////////////////drug admin starts//////////////////////
var tbl_prescription = $('#tbl_prescription').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'auditno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'chgcode', 'width': '25%' },
        { 'data': 'description', 'width': '65%' },
        { 'data': 'quantity', 'width': '10%' },
        { 'data': 'doscode' },
        { 'data': 'doscode_desc' },
        { 'data': 'frequency' },
        { 'data': 'frequency_desc' },
        { 'data': 'ftxtdosage' },
        { 'data': 'addinstruction' },
        { 'data': 'addinstruction_desc' },
        { 'data': 'drugindicator' },
        { 'data': 'drugindicator_desc' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 6, 7, 8, 9, 10, 11, 12, 13, 14], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////drug admin ends///////////////////////

//////////////////////treatment starts//////////////////////
var tbl_treatment = $('#tbl_treatment').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetime', 'width': '50%' },
        { 'data': 'adduser' },
        { 'data': 'dt' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4, 5], visible: false },
    ],
    order: [[5, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_investigation = $('#tbl_investigation').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetime', 'width': '50%' },
        { 'data': 'adduser' },
        { 'data': 'dt' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4, 5], visible: false },
    ],
    order: [[5, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_injection = $('#tbl_injection').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetime', 'width': '50%' },
        { 'data': 'adduser' },
        { 'data': 'dt' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4, 5], visible: false },
    ],
    order: [[5, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////treatment ends///////////////////////

//////////////////////careplan starts//////////////////////
var tbl_careplan_date = $('#tbl_careplan_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'entereddate', 'width': '25%' },
        { 'data': 'enteredtime', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////careplan ends///////////////////////

//////////////////////InvChart starts//////////////////////
var invChart_data = null;
var oper = null;
var invChart_tbl = $('#invChart_file').DataTable({
    columns: [
        { 'data': 'idno', "width": "10%" },
        { 'data': 'compcode' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'filename', "width": "100%" },
        { 'data': 'path', "width": "40%" },
    ],
    columnDefs: [
        { targets: [1,2,3,4,5], orderable: false },
        { targets: [0,1,2,3], visible: false },
        { targets: 5, 
            createdCell: function (td, cellData, rowData, row, col){
                console.log(rowData)
                $(td).html(`<a class="ui circular blue2 button right floated invChart_allAttach" href="../hisweb/uploads/`+rowData.path+`" target="_blank">OPEN</a>`);
            }
        }
    ],
    sDom: 't',
    ajax: './nursingnote/table?action=invChart_file&mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()
});

function uploadfile(){
    var formData = new FormData();
    formData.append('file', $('#invChrt_file')[0].files[0]);
    formData.append('_token', $("#csrf_token").val());
    
    if($('#mrn_nursNote').val() != ''){
        formData.append('mrn', $("#mrn_nursNote").val());
    }
    
    if($('#episno_nursNote').val() != ''){
        formData.append('episno', $("#episno_nursNote").val());
    }
    
    $.ajax({
        url: './nursingnote/form?action=uploadfile',
        type: 'POST',
        data: formData,
        dataType: 'json',
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
    }).done(function (msg){
        // make_all_attachment(msg.invChart_allAttach);
        // $('#idno_mmse').val(msg.idno);
        $('#invChart_file').DataTable().ajax.url('./nursingnote/table?action=invChart_file&mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()).load();
    });
}

function make_all_attachment(invChart_allAttach){
    $('#invChart_allAttach').html('');
    
    invChart_allAttach.forEach(function (o,i){
        $('#invChart_allAttach').append(`<a class="ui circular blue2 button invChart_allAttach" target="_blank" href="./uploads/`+o.path+`">`+o.filename+`</a>`);
    });
}

var tbl_invcat_FBC = $('#tbl_invcat_FBC').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_Coag = $('#tbl_invcat_Coag').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_RP = $('#tbl_invcat_RP').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_LFT = $('#tbl_invcat_LFT').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_Elect = $('#tbl_invcat_Elect').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_ABGVBG = $('#tbl_invcat_ABGVBG').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_UFEME = $('#tbl_invcat_UFEME').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_CE = $('#tbl_invcat_CE').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});

var tbl_invcat_CS = $('#tbl_invcat_CS').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'inv_code' },
        { 'data': 'inv_cat', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1], visible: false },
    ],
    order: [[0, 'asc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////InvChart ends///////////////////////

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_progress('empty');
function button_state_progress(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_progress').data('oper','add');
            $('#new_progress,#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','add');
            $("#new_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','edit');
            $("#new_progress,#edit_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress,#cancel_progress").attr('disabled',false);
            $('#edit_progress,#new_progress').attr('disabled',true);
            break;
    }
}

//////////////////////////////////////treatment starts//////////////////////////////////////
button_state_treatment('empty');
function button_state_treatment(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_treatment').data('oper','add');
            $('#new_treatment,#save_treatment,#cancel_treatment,#edit_treatment').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_treatment').data('oper','add');
            $("#new_treatment").attr('disabled',false);
            $('#save_treatment,#cancel_treatment,#edit_treatment').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_treatment').data('oper','edit');
            $("#new_treatment,#edit_treatment").attr('disabled',false);
            $('#save_treatment,#cancel_treatment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_treatment,#cancel_treatment").attr('disabled',false);
            $('#new_treatment,#edit_treatment').attr('disabled',true);
            break;
    }
}

button_state_investigation('empty');
function button_state_investigation(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_investigation').data('oper','add');
            $('#new_investigation,#save_investigation,#cancel_investigation,#edit_investigation').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_investigation').data('oper','add');
            $("#new_investigation").attr('disabled',false);
            $('#save_investigation,#cancel_investigation,#edit_investigation').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_investigation').data('oper','edit');
            $("#new_investigation,#edit_investigation").attr('disabled',false);
            $('#save_investigation,#cancel_investigation').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_investigation,#cancel_investigation").attr('disabled',false);
            $('#new_investigation,#edit_investigation').attr('disabled',true);
            break;
    }
}

button_state_injection('empty');
function button_state_injection(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_injection').data('oper','add');
            $('#new_injection,#save_injection,#cancel_injection,#edit_injection').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_injection').data('oper','add');
            $("#new_injection").attr('disabled',false);
            $('#save_injection,#cancel_injection,#edit_injection').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_injection').data('oper','edit');
            $("#new_injection,#edit_injection").attr('disabled',false);
            $('#save_injection,#cancel_injection').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_injection,#cancel_injection").attr('disabled',false);
            $('#new_injection,#edit_injection').attr('disabled',true);
            break;
    }
}
///////////////////////////////////////treatment ends///////////////////////////////////////

button_state_careplan('empty');
function button_state_careplan(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_careplan').data('oper','add');
            $('#new_careplan,#save_careplan,#cancel_careplan').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_careplan').data('oper','add');
            $("#new_careplan").attr('disabled',false);
            $('#save_careplan,#cancel_careplan').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_careplan,#cancel_careplan").attr('disabled',false);
            $('#new_careplan').attr('disabled',true);
            break;
    }
}

button_state_othersChart1('empty');
function button_state_othersChart1(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_othersChart1').data('oper','add');
            $('#new_othersChart1,#save_othersChart1,#cancel_othersChart1,#edit_othersChart1').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_othersChart1').data('oper','add');
            $("#new_othersChart1").attr('disabled',false);
            $('#save_othersChart1,#cancel_othersChart1,#edit_othersChart1').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_othersChart1').data('oper','edit');
            $("#edit_othersChart1").attr('disabled',false);
            $('#save_othersChart1,#cancel_othersChart1').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_othersChart1,#cancel_othersChart1").attr('disabled',false);
            $('#edit_othersChart1,#new_othersChart1').attr('disabled',true);
            break;
    }
}

button_state_othersChart2('empty');
function button_state_othersChart2(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_othersChart2').data('oper','add');
            $('#new_othersChart2,#save_othersChart2,#cancel_othersChart2,#edit_othersChart2').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_othersChart2').data('oper','add');
            $("#new_othersChart2").attr('disabled',false);
            $('#save_othersChart2,#cancel_othersChart2,#edit_othersChart2').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_othersChart2').data('oper','edit');
            $("#edit_othersChart2").attr('disabled',false);
            $('#save_othersChart2,#cancel_othersChart2').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_othersChart2,#cancel_othersChart2").attr('disabled',false);
            $('#edit_othersChart2,#new_othersChart2').attr('disabled',true);
            break;
    }
}

// screen current patient //
function populate_nursingnote(obj){
    $("#jqGridNursNote_panel").collapse('hide');
    emptyFormdata(errorField,"#formProgress");
    emptyFormdata(errorField,"#formIntake");
    emptyFormdata(errorField,"#formGlasgow");
    emptyFormdata(errorField,"#formPivc");
    
    // panel header
    $('#name_show_nursNote').text(obj.Name);
    $('#mrn_show_nursNote').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_nursNote').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_nursNote').text(dob_chg(obj.DOB));
    $('#age_show_nursNote').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_nursNote').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_nursNote').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_nursNote').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_nursNote').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_nursNote').text(if_none(obj.areaDesc).toUpperCase());
    
    $("#mrn_nursNote").val(obj.MRN);
    $("#episno_nursNote").val(obj.Episno);
    $("#doctor_nursNote").val(obj.q_doctorname);
    $("#ward_nursNote").val(obj.ward);
    $("#bednum_nursNote").val(obj.bednum);
    $("#age_nursNote").val(dob_age(obj.DOB));

    $("#bladder_ward").val($('#ward_nursNote').val());
    $("#bladder_bednum").val($('#bednum_nursNote').val());

    // $("#tot_input").val(obj.total_all_i);
    
    // var urlparam_datetime_tbl = {
    //     action: 'get_table_datetime',
    //     mrn: $("#mrn_nursNote").val(),
    //     episno: $("#episno_nursNote").val()
    // }
    
    // datetime_tbl.ajax.url("./nursingnote/table?"+$.param(urlparam_datetime_tbl)).load(function (data){
    //     emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
    //     $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
    // });
}

function populate_progressnote_getdata(){
    disableForm('#formProgress');
    emptyFormdata(errorField,"#formProgress",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_progress',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_progress").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress",data.nurshandover);
            $("#datetaken").val(data.date);
            
            button_state_progress('edit');
            textarea_init_nursingnote();
        }else{
            button_state_progress('add');
            textarea_init_nursingnote();
        }
    });
}

function populate_drugadmin_getdata(){
    emptyFormdata(errorField,"#formDrug",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    textarea_init_nursingnote();
}

function populate_treatment_getdata(){
    disableForm('#formTreatmentP');
    disableForm('#formInvestigation');
    disableForm('#formInjection');
    
    emptyFormdata(errorField,"#formTreatmentP",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    emptyFormdata(errorField,"#formInvestigation",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    emptyFormdata(errorField,"#formInjection",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_treatment',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data.treatment)){
            autoinsert_rowdata("#formTreatmentP",data.treatment);
            
            button_state_treatment('edit');
            textarea_init_nursingnote();
        }else{
            button_state_treatment('add');
            textarea_init_nursingnote();
        }
        
        if(!$.isEmptyObject(data.investigation)){
            autoinsert_rowdata("#formInvestigation",data.investigation);
            
            button_state_investigation('edit');
            textarea_init_nursingnote();
        }else{
            button_state_investigation('add');
            textarea_init_nursingnote();
        }
        
        if(!$.isEmptyObject(data.injection)){
            autoinsert_rowdata("#formInjection",data.injection);
            
            button_state_injection('edit');
            textarea_init_nursingnote();
        }else{
            button_state_injection('add');
            textarea_init_nursingnote();
        }
    });
}

function populate_careplan_getdata(){
    disableForm('#formCarePlan');
    emptyFormdata(errorField,"#formCarePlan",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_careplan',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_progress").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formCarePlan",data.nurscareplan);
            
            button_state_careplan('add');
            textarea_init_nursingnote();
        }else{
            button_state_careplan('add');
            textarea_init_nursingnote();
        }
    });
}

function populate_fitchart_getdata(){
    emptyFormdata(errorField,"#formFitChart",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_formFitChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_fitchart").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            // autoinsert_rowdata("#formFitChart",data.diagnosis);
            $("#fitchart_diag").val(data.diagnosis);
            
            // button_state_fitchart('edit');
        }else{
            // button_state_fitchart('add');
        }
        
        $("#fitchart_ward").val($('#ward_nursNote').val());
        $("#fitchart_bednum").val($('#bednum_nursNote').val());
        textarea_init_nursingnote();
    });
}

function populate_circulation_getdata(){
    emptyFormdata(errorField,"#formCirculation",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_formFitChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_circulation").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            // autoinsert_rowdata("#formCirculation",data.diagnosis);
            $("#circulation_diag").val(data.diagnosis);
            
            // button_state_circulation('edit');
        }else{
            // button_state_circulation('add');
        }
        
        // $("#circulation_ward").val($('#ward_nursNote').val());
        // $("#circulation_bednum").val($('#bednum_nursNote').val());
        textarea_init_nursingnote();
    });
}

function populate_othersChart1_getdata(){
    emptyFormdata(errorField,"#formOthersChart1",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#othersChart1_tabtitle"]);
    
    var saveParam = {
        action: 'get_table_formOthersChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_othersChart1").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
        tabtitle: $("#othersChart1_tabtitle").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data.title)){
            // autoinsert_rowdata("#formOthersChart1",data.title);
            $("#othersChart1_title").val(data.title);
            
            button_state_othersChart1('edit');
        }else{
            button_state_othersChart1('add');
        }
        
        $("#othersChart1_ward").val($('#ward_nursNote').val());
        $("#othersChart1_bednum").val($('#bednum_nursNote').val());
        $("#othersChart1_diag").val(data.diagnosis);
        textarea_init_nursingnote();
    });
}

function get_default_othersChart1(){
    emptyFormdata(errorField,"#formOthersChart1",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#othersChart1_tabtitle"]);
    
    var saveParam = {
        action: 'get_table_formOthersChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_othersChart1").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
        tabtitle: $("#othersChart1_tabtitle").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        // if(!$.isEmptyObject(data.title)){
        //     // autoinsert_rowdata("#formOthersChart1",data.title);
        //     $("#othersChart1_title").val(data.title);
            
        //     button_state_othersChart1('edit');
        // }else{
        //     button_state_othersChart1('add');
        // }
        
        $("#othersChart1_ward").val($('#ward_nursNote').val());
        $("#othersChart1_bednum").val($('#bednum_nursNote').val());
        $("#othersChart1_diag").val(data.diagnosis);
        textarea_init_nursingnote();
    });
}

function populate_othersChart2_getdata(){
    emptyFormdata(errorField,"#formOthersChart2",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#othersChart2_tabtitle"]);
    
    var saveParam = {
        action: 'get_table_formOthersChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_othersChart2").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
        tabtitle: $("#othersChart2_tabtitle").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data.title)){
            // autoinsert_rowdata("#formOthersChart2",data.title);
            $("#othersChart2_title").val(data.title);
            
            button_state_othersChart2('edit');
        }else{
            button_state_othersChart2('add');
        }
        
        $("#othersChart2_ward").val($('#ward_nursNote').val());
        $("#othersChart2_bednum").val($('#bednum_nursNote').val());
        $("#othersChart2_diag").val(data.diagnosis);
        textarea_init_nursingnote();
    });
}

function get_default_othersChart2(){
    emptyFormdata(errorField,"#formOthersChart2",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar","#othersChart2_tabtitle"]);
    
    var saveParam = {
        action: 'get_table_formOthersChart',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // idno: $("#idno_othersChart2").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
        tabtitle: $("#othersChart2_tabtitle").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        // if(!$.isEmptyObject(data.title)){
        //     // autoinsert_rowdata("#formOthersChart2",data.title);
        //     $("#othersChart2_title").val(data.title);
            
        //     button_state_othersChart2('edit');
        // }else{
        //     button_state_othersChart2('add');
        // }
        
        $("#othersChart2_ward").val($('#ward_nursNote').val());
        $("#othersChart2_bednum").val($('#bednum_nursNote').val());
        $("#othersChart2_diag").val(data.diagnosis);
        textarea_init_nursingnote();
    });
}

function populate_invHeader_getdata(){
    emptyFormdata(errorField,"#formInvHeader");
    
    var saveParam = {
        action: 'get_table_formInvHeader',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formInvHeader",data.episode);
        }else{
        }
        // $("#reg_date").val(data.episode);
    });
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input=$(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value==1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else{
            input.val(value);
        }
    });
    mycurrency_nursing.formatOn();
}

function saveForm_progress(callback){
    var saveParam = {
        action: 'save_table_progress',
        oper: $("#cancel_progress").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
        epistycode: $("#epistycode").val()
    };
    
    values = $("#formProgress").serializeArray();
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formProgress input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

////////////////////////////////////////////////////treatment starts////////////////////////////////////////////////////
function saveForm_treatment(callback){
    var saveParam = {
        action: 'save_table_treatment',
        oper: $("#cancel_treatment").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        tr_idno: $('#tr_idno').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formTreatmentP").serializeArray();
    
    values = values.concat(
        $('#formTreatmentP input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatmentP input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatmentP input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatmentP select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formTreatmentP input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    // values.push({
    //     name: 'tr_entereddate',
    //     value: $('#formTreatmentP input[name=tr_entereddate]').val()
    // })
    // values.push({
    //     name: 'tr_enteredtime',
    //     value: $('#formTreatmentP input[name=tr_enteredtime]').val()
    // })
    // values.push({
    //     name: 'treatment_remarks',
    //     value: $('#formTreatmentP textarea[name=treatment_remarks]').val()
    // })
    // values.push({
    //     name: 'treatment_adduser',
    //     value: $('#formTreatmentP input[name=treatment_adduser]').val()
    // })
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function saveForm_investigation(callback){
    var saveParam = {
        action: 'save_table_investigation',
        oper: $("#cancel_investigation").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        inv_idno: $('#inv_idno').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formInvestigation").serializeArray();
    
    values = values.concat(
        $('#formInvestigation input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formInvestigation input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formInvestigation input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formInvestigation select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formInvestigation input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function saveForm_injection(callback){
    var saveParam = {
        action: 'save_table_injection',
        oper: $("#cancel_injection").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        inj_idno: $('#inj_idno').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formInjection").serializeArray();
    
    values = values.concat(
        $('#formInjection input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formInjection input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formInjection input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formInjection select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formInjection input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}
/////////////////////////////////////////////////////treatment ends/////////////////////////////////////////////////////

function saveForm_careplan(callback){
    var saveParam = {
        action: 'save_table_careplan',
        oper: $("#cancel_careplan").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formCarePlan").serializeArray();
    
    values = values.concat(
        $('#formCarePlan input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formCarePlan input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formCarePlan input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formCarePlan select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formCarePlan input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function saveForm_othersChart1(callback){
    var saveParam = {
        action: 'save_table_othersChart',
        oper: $("#cancel_othersChart1").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formOthersChart1").serializeArray();
    
    values = values.concat(
        $('#formOthersChart1 input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart1 input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart1 input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart1 select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formOthersChart1 input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function saveForm_othersChart2(callback){
    var saveParam = {
        action: 'save_table_othersChart',
        oper: $("#cancel_othersChart2").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formOthersChart2").serializeArray();
    
    values = values.concat(
        $('#formOthersChart2 input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart2 input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart2 input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formOthersChart2 select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formOthersChart2 input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function textarea_init_nursingnote(){
    $('textarea#airwayfreetext,textarea#frfreetext,textarea#drainfreetext,textarea#ivfreetext,textarea#assesothers,textarea#plannotes,textarea#ftxtdosage,textarea#treatment_remarks,textarea#investigation_remarks,textarea#injection_remarks,textarea#problem,textarea#problemdata,textarea#problemintincome,textarea#nursintervention,textarea#nursevaluation,textarea#fitchart_diag,textarea#circulation_diag,textarea#othersChart1_diag,textarea#othersChart2_diag').each(function () {
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

function cust_rules_nurs(value, name){
    var temp = null;
    switch(name){
        case 'Time': temp = $("#jqGridPatMedic input[name='enteredtime']"); break;
        case 'fitchart_time': temp = $("#jqGridFitChart input[name='enteredtime']"); break;
        case 'circulation_time': temp = $("#jqGridCirculation input[name='enteredtime']"); break;
        case 'slidingScale_time': temp = $("#jqGridSlidingScale input[name='enteredtime']"); break;
        case 'othersChart1_time': temp = $("#jqGridOthersChart1 input[name='enteredtime']"); break;
        case 'othersChart2_time': temp = $("#jqGridOthersChart2 input[name='enteredtime']"); break;
        case 'bladder1_time': temp = $("#jqGridBladder1 input[name='enteredtime']"); break;
        case 'bladder2_time': temp = $("#jqGridBladder2 input[name='enteredtime']"); break;
        case 'bladder3_time': temp = $("#jqGridBladder3 input[name='enteredtime']"); break;
    }
    if(temp == null) return [true,''];
    return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}

function showdetail_nurs(cellvalue, options, rowObject){
    // var field, table, case_;
    // switch(options.colModel.name){
    //     case 'chgcode': field = ['chgcode','description']; table = "hisdb.chgmast"; case_ = 'chgcode'; break;
    //     case 'uom': field = ['uomcode','description']; table = "material.uom"; case_ = 'uom'; break;
    //     case 'uom_recv': field = ['uomcode','description']; table = "material.uom"; case_ = 'uom'; break;
    //     case 'taxcode': field = ['taxcode','description']; table = "hisdb.taxmast"; case_ = 'taxcode'; break;
    //     case 'deptcode': field = ['deptcode','description']; table = "sysdb.department"; case_ = 'deptcode'; break;
    // }
    // var param = {action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
    
    // if(cellvalue != null && cellvalue.trim() != ''){
    //     fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
    // }
    
    // if(cellvalue == null)cellvalue = " ";
    return cellvalue;
}

function enteredtimeCustomEdit_nurs(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_fitchart(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="fitchart_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_circulation(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="circulation_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_slidingScale(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="slidingScale_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_othersChart1(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="othersChart1_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_othersChart2(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="othersChart2_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_bladder1(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="bladder1_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_bladder2(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="bladder2_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function enteredtimeCustomEdit_bladder3(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="bladder3_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_nurs(elem, operation, value){
    if(operation == 'get'){
        // console.log($(elem).find("input").val());
        return $(elem).find("input").val();
    }
    else if(operation == 'set'){
        $('input',elem).val(value);
    }
}

var dialog_dosage_nursNote = new ordialog(
    'dosage_nursNote',['hisdb.dose'],"#formDrug input[name='dosage']",'errorField',
    {
        colModel: [
            { label: 'Dosage Code', name: 'dosecode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Description', name: 'dosedesc', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (event){
            // if(event.type == 'keydown'){
            //     var optid = $(event.currentTarget).get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }else{
            //     var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }
            
            let data = selrowData('#'+dialog_dosage_nursNote.gridname);
            $(dialog_dosage_nursNote.textfield).val(data.dosedesc);
            $('#dosage_nursNote_code').val(data.dosecode);
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }
        }
    },{
        title: "Select Dosage Code",
        open: function (obj_){
            dialog_dosage_nursNote.urlParam.filterCol = ['compcode','recstatus'];
            dialog_dosage_nursNote.urlParam.filterVal = ['session.compcode','ACTIVE'];
        },
        close: function (){
            // $(dialog_deptcode_phar.textfield)   // lepas close dialog focus on next textfield
            //     .closest('td')                  // utk dialog dalam jqgrid jer
            //     .next()
            //     .find("input[type=text]").focus();
        }
    },'urlParam', 'radio', 'tab'
);
dialog_dosage_nursNote.makedialog(false);

var dialog_frequency_nursNote = new ordialog(
    'frequency_nursNote',['hisdb.freq'],"#formDrug input[name='frequency']",'errorField',
    {
        colModel: [
            { label: 'Frequency Code', name: 'freqcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Description', name: 'freqdesc', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (event){
            // if(event.type == 'keydown'){
            //     var optid = $(event.currentTarget).get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }else{
            //     var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }
            
            let data = selrowData('#'+dialog_frequency_nursNote.gridname);
            $(dialog_frequency_nursNote.textfield).val(data.freqdesc);
            $('#frequency_nursNote_code').val(data.freqcode);
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }
        }
    },{
        title: "Select Frequency Code",
        open: function (obj_){
            dialog_frequency_nursNote.urlParam.filterCol = ['compcode','recstatus'];
            dialog_frequency_nursNote.urlParam.filterVal = ['session.compcode','ACTIVE'];
        },
        close: function (){
            // $(dialog_deptcode_phar.textfield)   // lepas close dialog focus on next textfield
            //     .closest('td')                  // utk dialog dalam jqgrid jer
            //     .next()
            //     .find("input[type=text]").focus();
        }
    },'urlParam', 'radio', 'tab'
);
dialog_frequency_nursNote.makedialog(false);

var dialog_instruction_nursNote = new ordialog(
    'instruction_nursNote',['hisdb.instruction'],"#formDrug input[name='instruction']",'errorField',
    {
        colModel: [
            { label: 'Instruction Code', name: 'inscode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (event){
            // if(event.type == 'keydown'){
            //     var optid = $(event.currentTarget).get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }else{
            //     var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }
            
            let data = selrowData('#'+dialog_instruction_nursNote.gridname);
            $(dialog_instruction_nursNote.textfield).val(data.description);
            $('#instruction_nursNote_code').val(data.inscode);
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }
        }
    },{
        title: "Select Instruction Code",
        open: function (obj_){
            dialog_instruction_nursNote.urlParam.filterCol = ['compcode','recstatus'];
            dialog_instruction_nursNote.urlParam.filterVal = ['session.compcode','ACTIVE'];
        },
        close: function (){
            // $(dialog_deptcode_phar.textfield)   // lepas close dialog focus on next textfield
            //     .closest('td')                  // utk dialog dalam jqgrid jer
            //     .next()
            //     .find("input[type=text]").focus();
        }
    },'urlParam', 'radio', 'tab'
);
dialog_instruction_nursNote.makedialog(false);

var dialog_drugindicator_nursNote = new ordialog(
    'drugindicator_nursNote',['hisdb.drugindicator'],"#formDrug input[name='drugindicator']",'errorField',
    {
        colModel: [
            { label: 'Indicator Code', name: 'drugindcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (event){
            // if(event.type == 'keydown'){
            //     var optid = $(event.currentTarget).get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }else{
            //     var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
            //     var id_optid = optid.substring(0,optid.search("_"));
            // }
            
            let data = selrowData('#'+dialog_drugindicator_nursNote.gridname);
            $(dialog_drugindicator_nursNote.textfield).val(data.description);
            $('#drugindicator_nursNote_code').val(data.drugindcode);
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }
        }
    },{
        title: "Select Indicator Code",
        open: function (obj_){
            dialog_drugindicator_nursNote.urlParam.filterCol= ['compcode','recstatus'];
            dialog_drugindicator_nursNote.urlParam.filterVal= ['session.compcode','ACTIVE'];
        },
        close: function (){
            // $(dialog_deptcode_phar.textfield)   // lepas close dialog focus on next textfield
            //     .closest('td')                  // utk dialog dalam jqgrid jer
            //     .next()
            //     .find("input[type=text]").focus();
        }
    },'urlParam', 'radio', 'tab'
);
dialog_drugindicator_nursNote.makedialog(false);

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == null){
        same = false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        same = false;
    }
    
    return same;
}

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight < 50){
//         scrollHeight = 50;
//     }else if(scrollHeight > 300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }