
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var mycurrency_nursing = new currencymode(['#oralamt1', '#oralamt2', '#oralamt3', '#oralamt4', '#oralamt5', '#oralamt6', '#oralamt7', '#oralamt8', '#oralamt9', '#oralamt10', '#oralamt11', '#oralamt12', '#oralamt13', '#oralamt14', '#oralamt15', '#oralamt16', '#oralamt17', '#oralamt18', '#oralamt19', '#oralamt20', '#oralamt21', '#oralamt22', '#oralamt23', '#oralamt24', '#intraamt1', '#intraamt2', '#intraamt3', '#intraamt4', '#intraamt5', '#intraamt6', '#intraamt7', '#intraamt8', '#intraamt9', '#intraamt10', '#intraamt11', '#intraamt12', '#intraamt13', '#intraamt14', '#intraamt15', '#intraamt16', '#intraamt17', '#intraamt18', '#intraamt19', '#intraamt20', '#intraamt21', '#intraamt22', '#intraamt23', '#intraamt24', '#otheramt1', '#otheramt2', '#otheramt3', '#otheramt4', '#otheramt5', '#otheramt6', '#otheramt7', '#otheramt8', '#otheramt9', '#otheramt10', '#otheramt11', '#otheramt12', '#otheramt13', '#otheramt14', '#otheramt15', '#otheramt16', '#otheramt17', '#otheramt18', '#otheramt19', '#otheramt20', '#otheramt21', '#otheramt22', '#otheramt23', '#otheramt24', '#urineamt1', '#urineamt2', '#urineamt3', '#urineamt4', '#urineamt5', '#urineamt6', '#urineamt7', '#urineamt8', '#urineamt9', '#urineamt10', '#urineamt11', '#urineamt12', '#urineamt13', '#urineamt14', '#urineamt15', '#urineamt16', '#urineamt17', '#urineamt18', '#urineamt19', '#urineamt20', '#urineamt21', '#urineamt22', '#urineamt23', '#urineamt24', '#vomitamt1', '#vomitamt2', '#vomitamt3', '#vomitamt4', '#vomitamt5', '#vomitamt6', '#vomitamt7', '#vomitamt8', '#vomitamt9', '#vomitamt10', '#vomitamt11', '#vomitamt12', '#vomitamt13', '#vomitamt14', '#vomitamt15', '#vomitamt16', '#vomitamt17', '#vomitamt18', '#vomitamt19', '#vomitamt20', '#vomitamt21', '#vomitamt22', '#vomitamt23', '#vomitamt24', '#aspamt1', '#aspamt2', '#aspamt3', '#aspamt4', '#aspamt5', '#aspamt6', '#aspamt7', '#aspamt8', '#aspamt9', '#aspamt10', '#aspamt11', '#aspamt12', '#aspamt13', '#aspamt14', '#aspamt15', '#aspamt16', '#aspamt17', '#aspamt18', '#aspamt19', '#aspamt20', '#aspamt21', '#aspamt22', '#aspamt23', '#aspamt24', '#otherout1', '#otherout2', '#otherout3', '#otherout4', '#otherout5', '#otherout6', '#otherout7', '#otherout8', '#otherout9', '#otherout10', '#otherout11', '#otherout12', '#otherout13', '#otherout14', '#otherout15', '#otherout16', '#otherout17', '#otherout18', '#otherout19', '#otherout20', '#otherout21', '#otherout22', '#otherout23', '#otherout24']);

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

////////////////////////parameter for jqGridSlidScale url////////////////////////
var urlParam_SlidScale = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_slidScale',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    textarea_init_nursingnote();
    
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
    
    /////////////////////////////////////intakeoutput starts/////////////////////////////////////
    disableForm('#formIntake');
    
    $("#new_intake").click(function (){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
        emptyFormdata_div("#formIntake",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_intake").click(function (){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
        mycurrency_nursing.formatOnBlur();
        // dialog_mrn_edit.on();
    });
    
    $("#save_intake").click(function (){
        disableForm('#formIntake');
        if($('#formIntake').isValid({requiredFields: ''}, conf, true)){
            mycurrency_nursing.formatOff();
            saveForm_intake(function (){
                $("#cancel_intake").data('oper','edit');
                $("#cancel_intake").click();
                mycurrency_nursing.formatOn();
                // $("#jqGridPagerRefresh").click();
            });
        }else{
            enableForm('#formIntake');
            rdonly('#formIntake');
        }
    });
    
    $("#cancel_intake").click(function (){
        disableForm('#formIntake');
        button_state_intake($(this).data('oper'));
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////intakeoutput ends//////////////////////////////////////
    
    //////////////////////////////////////drug admin starts//////////////////////////////////////
    disableForm('#formDrug');
    rdonly('#formDrug');
    ///////////////////////////////////////drug admin ends///////////////////////////////////////
    
    //////////////////////////////////////treatment starts//////////////////////////////////////
    disableForm('#formTreatment');
    disableForm('#formInvestigation');
    disableForm('#formInjection');
    
    $("#new_treatment").click(function (){
        button_state_treatment('wait');
        enableForm('#formTreatment');
        rdonly('#formTreatment');
        emptyFormdata_div("#formTreatment",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        // dialog_mrn_edit.on();
    });
    
    $("#save_treatment").click(function (){
        disableForm('#formTreatment');
        if($('#formTreatment').isValid({requiredFields: ''}, conf, true)){
            saveForm_treatment(function (){
                $("#cancel_treatment").data('oper','add');
                $("#cancel_treatment").click();
                // $("#jqGridPagerRefresh").click();
                // $('#tbl_treatment').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formTreatment');
            rdonly('#formTreatment');
        }
    });
    
    $("#cancel_treatment").click(function (){
        disableForm('#formTreatment');
        button_state_treatment($(this).data('oper'));
        $('#tbl_treatment').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    
    $("#new_investigation").click(function (){
        button_state_investigation('wait');
        enableForm('#formInvestigation');
        rdonly('#formInvestigation');
        emptyFormdata_div("#formInvestigation",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        // dialog_mrn_edit.on();
    });
    
    $("#save_investigation").click(function (){
        disableForm('#formInvestigation');
        if($('#formInvestigation').isValid({requiredFields: ''}, conf, true)){
            saveForm_investigation(function (){
                $("#cancel_investigation").data('oper','add');
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
        // dialog_mrn_edit.on();
    });
    
    $("#save_injection").click(function (){
        disableForm('#formInjection');
        if($('#formInjection').isValid({requiredFields: ''}, conf, true)){
            saveForm_injection(function (){
                $("#cancel_injection").data('oper','add');
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
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    $("#jqGridNursNote_panel").on("shown.bs.collapse", function (){
        SmoothScrollTo("#jqGridNursNote_panel", 500);
        let curtype = $(this).data('curtype');
        $('#jqGridNursNote_panel_tabs.nav-tabs a#'+curtype).tab('show');
    });
    
    $("#jqGridNursNote_panel").on("hidden.bs.collapse", function (){
        button_state_progress('empty');
        button_state_intake('empty');
        button_state_treatment('empty');
        button_state_investigation('empty');
        button_state_injection('empty');
        button_state_careplan('empty');
        disableForm('#formProgress');
        disableForm('#formIntake');
        disableForm('#formTreatment');
        disableForm('#formInvestigation');
        disableForm('#formInjection');
        disableForm('#formCarePlan');
        refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'kosongkan');
        refreshGrid('#jqGridFitChart',urlParam_FitChart,'kosongkan');
        refreshGrid('#jqGridCirculation',urlParam_Circulation,'kosongkan');
        refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'kosongkan');
        $("#jqGridNursNote_panel > div").scrollTop(0);
        $("#jqGridNursNote_panel #jqGridNursNote_panel_tabs li").removeClass('active');
    });
    
    $('#jqGridNursNote_panel_tabs.nav-tabs a').on('shown.bs.tab', function (e){
        let type = $(this).data('type');
        let id = $(this).attr('id');
        $("#jqGridNursNote_panel").data('curtype',id);
        switch(type){
            case 'progress':
                var urlparam_datetime_tbl = {
                    action: 'get_table_datetime',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                datetime_tbl.ajax.url("./nursingnote/table?"+$.param(urlparam_datetime_tbl)).load(function (data){
                    emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#datetime_tbl').DataTable().ajax.reload();
                populate_progressnote_getdata();
                break;
            case 'intake':
                populate_intakeoutput_getdata();
                break;
            case 'drug':
                var urlparam_tbl_prescription = {
                    action: 'get_prescription',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    chggroup: $('#ordcomtt_phar').val(),
                }
                
                tbl_prescription.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_prescription)).load(function (data){
                    emptyFormdata_div("#formDrug",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_prescription tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_prescription').DataTable().ajax.reload();
                populate_drugadmin_getdata();
                $("#jqGridPatMedic").jqGrid('setGridWidth', Math.floor($("#jqGridPatMedic_c")[0].offsetWidth-$("#jqGridPatMedic_c")[0].offsetLeft-30));
                break;
            case 'treatment':
                var urlparam_tbl_treatment = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "TREATMENT",
                }
                
                tbl_treatment.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_treatment)).load(function (data){
                    emptyFormdata_div("#formTreatment",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_treatment tbody tr:eq(0)').click();  // to select first row
                });
                
                var urlparam_tbl_investigation = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "INVESTIGATION",
                }
                
                tbl_investigation.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_investigation)).load(function (data){
                    emptyFormdata_div("#formInvestigation",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_investigation tbody tr:eq(0)').click();  // to select first row
                });
                
                var urlparam_tbl_injection = {
                    action: 'get_datetime_treatment',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val(),
                    type: "INJECTION",
                }
                
                tbl_injection.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_injection)).load(function (data){
                    emptyFormdata_div("#formInjection",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_injection tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_treatment').DataTable().ajax.reload();
                // $('#tbl_investigation').DataTable().ajax.reload();
                // $('#tbl_injection').DataTable().ajax.reload();
                populate_treatment_getdata();
                break;
            case 'careplan':
                var urlparam_tbl_careplan_date = {
                    action: 'get_datetime_careplan',
                    mrn: $("#mrn_nursNote").val(),
                    episno: $("#episno_nursNote").val()
                }
                
                tbl_careplan_date.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_careplan_date)).load(function (data){
                    emptyFormdata_div("#formCarePlan",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
                    $('#tbl_careplan_date tbody tr:eq(0)').click();  // to select first row
                });
                
                // $('#tbl_careplan_date').DataTable().ajax.reload();
                populate_careplan_getdata();
                break;
            case 'fitchart':
                urlParam_FitChart.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_FitChart.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridFitChart',urlParam_FitChart,'add');
                
                $("#jqGridFitChart").jqGrid('setGridWidth', Math.floor($("#jqGridFitChart_c")[0].offsetWidth-$("#jqGridFitChart_c")[0].offsetLeft-30));
                populate_fitchart_getdata();
                break;
            case 'circulation':
                urlParam_Circulation.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_Circulation.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridCirculation',urlParam_Circulation,'add');
                
                $("#jqGridCirculation").jqGrid('setGridWidth', Math.floor($("#jqGridCirculation_c")[0].offsetWidth-$("#jqGridCirculation_c")[0].offsetLeft-30));
                populate_circulation_getdata();
                break;
            case 'slidScale':
                urlParam_SlidScale.filterVal[0] = $("#mrn_nursNote").val();
                urlParam_SlidScale.filterVal[1] = $("#episno_nursNote").val();
                refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'add');
                
                $("#jqGridSlidScale").jqGrid('setGridWidth', Math.floor($("#jqGridSlidScale_c")[0].offsetWidth-$("#jqGridSlidScale_c")[0].offsetLeft-30));
                // populate_slidScale_getdata();
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
        
        var saveParam={
            action: 'get_table_progress',
        }
        
        var postobj={
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
        var saveParam={
            action: 'get_table_drug',
        }
        
        var postobj={
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
        
        emptyFormdata_div("#formTreatment",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_treatment tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_treatment_getdata();
        
        var saveParam={
            action: 'get_table_treatment',
        }
        
        var postobj={
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
                autoinsert_rowdata("#formTreatment",data.treatment);
                
                button_state_treatment('add');
                textarea_init_nursingnote();
            }else{
                button_state_treatment('add');
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
        
        // populate_investigation_getdata();
        
        var saveParam={
            action: 'get_table_treatment',
        }
        
        var postobj={
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
                
                button_state_investigation('add');
                textarea_init_nursingnote();
            }else{
                button_state_investigation('add');
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
        
        // populate_injection_getdata();
        
        var saveParam={
            action: 'get_table_treatment',
        }
        
        var postobj={
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
                
                button_state_injection('add');
                textarea_init_nursingnote();
            }else{
                button_state_injection('add');
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
        
        var saveParam={
            action: 'get_table_careplan',
        }
        
        var postobj={
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
    
    ////////////////////////////////////////slidScale starts////////////////////////////////////////
    ////////////////////////////////////////jqGridSlidScale////////////////////////////////////////
    var addmore_jqgrid4 = { more:false,state:false,edit:false }
    
    $("#jqGridSlidScale").jqGrid({
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
                    custom_element: enteredtimeCustomEdit_slidScale,
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
        pager: "#jqGridPagerSlidScale",
        loadComplete: function (){
            if(addmore_jqgrid4.more == true){$('#jqGridSlidScale_iladd').click();}
            else{
                $('#jqGridSlidScale').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid4.edit = addmore_jqgrid4.more = false; // reset
            
            // calc_jq_height_onchange("jqGridSlidScale");
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridSlidScale_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerSlidScale').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerSlidScale").setSelection($("#jqGridPagerSlidScale").getDataIDs()[0]);
            }
        },
    });
    
    //////////////////////////////////myEditOptions_add_SlidScale//////////////////////////////////
    var myEditOptions_add_SlidScale = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").hide();
            
            $("#jqGridSlidScale textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridSlidScale_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridSlidScale_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            addmore_jqgrid4.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridSlidScale').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'SlidScale_save',
                });
            $("#jqGridSlidScale").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////myEditOptions_edit_SlidScale/////////////////////////////////
    var myEditOptions_edit_SlidScale = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").hide();
            
            $("#jqGridSlidScale textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridSlidScale_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridSlidScale_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridSlidScale',urlParam_SlidScale,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridSlidScale').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingnote/form?"+
                $.param({
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    action: 'SlidScale_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridSlidScale").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////jqGridPagerSlidScale/////////////////////////////////////
    $("#jqGridSlidScale").inlineNav('#jqGridPagerSlidScale', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_SlidScale
        },
        editParams: myEditOptions_edit_SlidScale
    }).jqGrid('navButtonAdd', "#jqGridPagerSlidScale", {
        id: "jqGridPagerDelete_SlidScale",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridSlidScale").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridSlidScale').idno,
                        action: 'SlidScale_del',
                    }
                    $.post("./nursingnote/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridSlidScale", urlParam_SlidScale);
                    });
                }else{
                    $("#jqGridPagerDelete_SlidScale,#jqGridPagerRefresh_SlidScale").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerSlidScale", {
        id: "jqGridPagerRefresh_SlidScale",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridSlidScale", urlParam_SlidScale);
        },
    });
    
    $("#jqGridSlidScale_ilcancel").click(function (){
        refreshGrid("#jqGridSlidScale", urlParam_SlidScale);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////
    /////////////////////////////////////////slidScale ends/////////////////////////////////////////
    
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
        { 'data': 'lastuser', 'width': '50%' },
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
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
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
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
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
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
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

button_state_intake('empty');
function button_state_intake(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_intake').data('oper','add');
            $('#new_intake,#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','add');
            $("#new_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','edit');
            $("#edit_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake,#new_intake').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_intake,#cancel_intake").attr('disabled',false);
            $('#edit_intake,#new_intake').attr('disabled',true);
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
            $('#new_treatment,#save_treatment,#cancel_treatment').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_treatment').data('oper','add');
            $("#new_treatment").attr('disabled',false);
            $('#save_treatment,#cancel_treatment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_treatment,#cancel_treatment").attr('disabled',false);
            $('#new_treatment').attr('disabled',true);
            break;
    }
}

button_state_investigation('empty');
function button_state_investigation(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_investigation').data('oper','add');
            $('#new_investigation,#save_investigation,#cancel_investigation').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_investigation').data('oper','add');
            $("#new_investigation").attr('disabled',false);
            $('#save_investigation,#cancel_investigation').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_investigation,#cancel_investigation").attr('disabled',false);
            $('#new_investigation').attr('disabled',true);
            break;
    }
}

button_state_injection('empty');
function button_state_injection(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_injection').data('oper','add');
            $('#new_injection,#save_injection,#cancel_injection').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_injection').data('oper','add');
            $("#new_injection").attr('disabled',false);
            $('#save_injection,#cancel_injection').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_injection,#cancel_injection").attr('disabled',false);
            $('#new_injection').attr('disabled',true);
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

// screen current patient //
function populate_nursingnote(obj){
    $("#jqGridNursNote_panel").collapse('hide');
    emptyFormdata(errorField,"#formProgress");
    emptyFormdata(errorField,"#formIntake");
    
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

function populate_intakeoutput_getdata(){
    emptyFormdata(errorField,"#formIntake",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_intake',
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
            autoinsert_rowdata("#formIntake",data.intakeoutput);
            
            button_state_intake('edit');
            textarea_init_nursingnote();
        }else{
            button_state_intake('add');
            textarea_init_nursingnote();
        }
    });
}

function populate_drugadmin_getdata(){
    emptyFormdata(errorField,"#formDrug",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    textarea_init_nursingnote();
}

function populate_treatment_getdata(){
    emptyFormdata(errorField,"#formTreatment",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
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
            autoinsert_rowdata("#formTreatment",data.treatment);
            
            button_state_treatment('add');
            textarea_init_nursingnote();
        }else{
            button_state_treatment('add');
            textarea_init_nursingnote();
        }
        
        if(!$.isEmptyObject(data.investigation)){
            autoinsert_rowdata("#formInvestigation",data.investigation);
            
            button_state_investigation('add');
            textarea_init_nursingnote();
        }else{
            button_state_investigation('add');
            textarea_init_nursingnote();
        }
        
        if(!$.isEmptyObject(data.injection)){
            autoinsert_rowdata("#formInjection",data.injection);
            
            button_state_injection('add');
            textarea_init_nursingnote();
        }else{
            button_state_injection('add');
            textarea_init_nursingnote();
        }
    });
}

function populate_careplan_getdata(){
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
            $("#fitchart_ward").val($('#ward_nursNote').val());
            $("#fitchart_bednum").val($('#bednum_nursNote').val());
            
            // button_state_fitchart('edit');
            textarea_init_nursingnote();
        }else{
            $("#fitchart_ward").val($('#ward_nursNote').val());
            $("#fitchart_bednum").val($('#bednum_nursNote').val());
            
            // button_state_fitchart('add');
            textarea_init_nursingnote();
        }
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
            // $("#circulation_ward").val($('#ward_nursNote').val());
            // $("#circulation_bednum").val($('#bednum_nursNote').val());
            
            // button_state_circulation('edit');
            textarea_init_nursingnote();
        }else{
            // $("#circulation_ward").val($('#ward_nursNote').val());
            // $("#circulation_bednum").val($('#bednum_nursNote').val());
            
            // button_state_circulation('add');
            textarea_init_nursingnote();
        }
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
        episno_nursNote: $('#episno_nursNote').val()
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

function saveForm_intake(callback){
    var saveParam = {
        action: 'save_table_intake',
        oper: $("#cancel_intake").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formIntake").serializeArray();
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formIntake input[type=radio]:checked').map(
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
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formTreatment").serializeArray();
    
    values = values.concat(
        $('#formTreatment input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatment input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatment input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formTreatment select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formTreatment input[type=radio]:checked').map(
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

function saveForm_investigation(callback){
    var saveParam = {
        action: 'save_table_investigation',
        oper: $("#cancel_investigation").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
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

function textarea_init_nursingnote(){
    $('textarea#airwayfreetext,textarea#frfreetext,textarea#drainfreetext,textarea#ivfreetext,textarea#assesothers,textarea#plannotes,textarea#oraltype1,textarea#oraltype2,textarea#oraltype3,textarea#oraltype4,textarea#oraltype5,textarea#oraltype6,textarea#oraltype7,textarea#oraltype8,textarea#oraltype9,textarea#oraltype10,textarea#oraltype11,textarea#oraltype12,textarea#oraltype13,textarea#oraltype14,textarea#oraltype15,textarea#oraltype16,textarea#oraltype17,textarea#oraltype18,textarea#oraltype19,textarea#oraltype20,textarea#oraltype21,textarea#oraltype22,textarea#oraltype23,textarea#oraltype24,textarea#intratype1,textarea#intratype2,textarea#intratype3,textarea#intratype4,textarea#intratype5,textarea#intratype6,textarea#intratype7,textarea#intratype8,textarea#intratype9,textarea#intratype10,textarea#intratype11,textarea#intratype12,textarea#intratype13,textarea#intratype14,textarea#intratype15,textarea#intratype16,textarea#intratype17,textarea#intratype18,textarea#intratype19,textarea#intratype20,textarea#intratype21,textarea#intratype22,textarea#intratype23,textarea#intratype24,textarea#othertype1,textarea#othertype2,textarea#othertype3,textarea#othertype4,textarea#othertype5,textarea#othertype6,textarea#othertype7,textarea#othertype8,textarea#othertype9,textarea#othertype10,textarea#othertype11,textarea#othertype12,textarea#othertype13,textarea#othertype14,textarea#othertype15,textarea#othertype16,textarea#othertype17,textarea#othertype18,textarea#othertype19,textarea#othertype20,textarea#othertype21,textarea#othertype22,textarea#othertype23,textarea#othertype24,textarea#ftxtdosage,textarea#treatment_remarks,textarea#investigation_remarks,textarea#injection_remarks,textarea#problem,textarea#problemdata,textarea#problemintincome,textarea#nursintervention,textarea#nursevaluation,textarea#fitchart_diag,textarea#circulation_diag').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight>40){
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
        case 'slidScale_time': temp = $("#jqGridSlidScale input[name='enteredtime']"); break;
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

function enteredtimeCustomEdit_slidScale(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="slidScale_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_nurs(elem, operation, value){
    if(operation == 'get') {
        // console.log($(elem).find("input").val());
        return $(elem).find("input").val();
    }
    else if(operation == 'set') {
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

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight < 50){
//         scrollHeight = 50;
//     }else if(scrollHeight > 300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }

