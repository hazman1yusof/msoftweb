$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var mycurrency_nursing = new currencymode(['#oralamt1', '#oralamt2', '#oralamt3', '#oralamt4', '#oralamt5', '#oralamt6', '#oralamt7', '#oralamt8', '#oralamt9', '#oralamt10', '#oralamt11', '#oralamt12', '#oralamt13', '#oralamt14', '#oralamt15', '#oralamt16', '#oralamt17', '#oralamt18', '#oralamt19', '#oralamt20', '#oralamt21', '#oralamt22', '#oralamt23', '#oralamt24', '#intraamt1', '#intraamt2', '#intraamt3', '#intraamt4', '#intraamt5', '#intraamt6', '#intraamt7', '#intraamt8', '#intraamt9', '#intraamt10', '#intraamt11', '#intraamt12', '#intraamt13', '#intraamt14', '#intraamt15', '#intraamt16', '#intraamt17', '#intraamt18', '#intraamt19', '#intraamt20', '#intraamt21', '#intraamt22', '#intraamt23', '#intraamt24', '#otheramt1', '#otheramt2', '#otheramt3', '#otheramt4', '#otheramt5', '#otheramt6', '#otheramt7', '#otheramt8', '#otheramt9', '#otheramt10', '#otheramt11', '#otheramt12', '#otheramt13', '#otheramt14', '#otheramt15', '#otheramt16', '#otheramt17', '#otheramt18', '#otheramt19', '#otheramt20', '#otheramt21', '#otheramt22', '#otheramt23', '#otheramt24', '#urineamt1', '#urineamt2', '#urineamt3', '#urineamt4', '#urineamt5', '#urineamt6', '#urineamt7', '#urineamt8', '#urineamt9', '#urineamt10', '#urineamt11', '#urineamt12', '#urineamt13', '#urineamt14', '#urineamt15', '#urineamt16', '#urineamt17', '#urineamt18', '#urineamt19', '#urineamt20', '#urineamt21', '#urineamt22', '#urineamt23', '#urineamt24', '#vomitamt1', '#vomitamt2', '#vomitamt3', '#vomitamt4', '#vomitamt5', '#vomitamt6', '#vomitamt7', '#vomitamt8', '#vomitamt9', '#vomitamt10', '#vomitamt11', '#vomitamt12', '#vomitamt13', '#vomitamt14', '#vomitamt15', '#vomitamt16', '#vomitamt17', '#vomitamt18', '#vomitamt19', '#vomitamt20', '#vomitamt21', '#vomitamt22', '#vomitamt23', '#vomitamt24', '#aspamt1', '#aspamt2', '#aspamt3', '#aspamt4', '#aspamt5', '#aspamt6', '#aspamt7', '#aspamt8', '#aspamt9', '#aspamt10', '#aspamt11', '#aspamt12', '#aspamt13', '#aspamt14', '#aspamt15', '#aspamt16', '#aspamt17', '#aspamt18', '#aspamt19', '#aspamt20', '#aspamt21', '#aspamt22', '#aspamt23', '#aspamt24', '#otherout1', '#otherout2', '#otherout3', '#otherout4', '#otherout5', '#otherout6', '#otherout7', '#otherout8', '#otherout9', '#otherout10', '#otherout11', '#otherout12', '#otherout13', '#otherout14', '#otherout15', '#otherout16', '#otherout17', '#otherout18', '#otherout19', '#otherout20', '#otherout21', '#otherout22', '#otherout23', '#otherout24']);

/////////////////////////parameter for jqGridTreatment url/////////////////////////
var urlParam_Treatment = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_treatment',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanTreatment url/////////////////////////////
var urlParam_AddNotesNursActPlanTreatment = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_TREATMENT'],
}

///////////////////////parameter for jqGridObservation url///////////////////////
var urlParam_Observation = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_observation',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanObservation url/////////////////////////////
var urlParam_AddNotesNursActPlanObservation = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_OBSERVATION'],
}

///////////////////////parameter for jqGridFeeding url///////////////////////
var urlParam_Feeding = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_feeding',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanFeeding url/////////////////////////////
var urlParam_AddNotesNursActPlanFeeding = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_FEEDING'],
}

//////////////////////parameter for jqGridImgDiag url//////////////////////
var urlParam_ImgDiag = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_imgdiag',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanImgDiag url/////////////////////////////
var urlParam_AddNotesNursActPlanImgDiag = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_IMGDIAG'],
}

//////////////////////parameter for jqGridBloodTrans url//////////////////////
var urlParam_BloodTrans = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_bloodtrans',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanBloodTrans url/////////////////////////////
var urlParam_AddNotesNursActPlanBloodTrans = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_BLOODTRANS'],
}

//////////////////////parameter for jqGridExams url//////////////////////
var urlParam_Exams = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_exam',
    table_id: 'idno',
    filterCol: ['mrn','episno'],
    filterVal: ['',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanExams url/////////////////////////////
var urlParam_AddNotesNursActPlanExams = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSACTPLAN_EXAMS'],
}

//////////////////////parameter for jqGridProcedure url//////////////////////
var urlParam_Procedure = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursactplan_procedure',
    table_id: 'idno',
    filterCol: ['mrn','episno','prodType'],
    filterVal: ['','',''],
}

/////////////////////////////parameter for jqGridAddNotesNursActPlanProcedure url/////////////////////////////
var urlParam_AddNotesNursActPlanProcedure = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type','prodType'],
	filterVal: ['','','NURSACTPLAN_PROCEDURE',''],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    textarea_init_nursingActionPlan();

    /////////////////////////////////////header starts/////////////////////////////////////
    disableForm('#formHeader');

    $("#new_header").click(function (){
        button_state_header('wait');
        enableForm('#formHeader');
        rdonly('#formHeader');
    });
    
    $("#edit_header").click(function (){
        button_state_header('wait');
        enableForm('#formHeader');
        rdonly('#formHeader');
    });
    
    $("#save_header").click(function (){
        disableForm('#formHeader');
        if($('#formHeader').isValid({requiredFields: ''}, conf, true)){
            saveForm_header(function (){
                $("#cancel_header").data('oper','edit');
                $("#cancel_header").click();
            });
        }else{
            enableForm('#formHeader');
            rdonly('#formHeader');
        }
    });
    
    $("#cancel_header").click(function (){
        disableForm('#formHeader');
        button_state_header($(this).data('oper'));

    });
    //////////////////////////////////////header ends//////////////////////////////////////
    
    ////////////////////////////////////print button starts////////////////////////////////////
    $("#treatment_chart").click(function (){
        window.open('./nursingActionPlan/treatment_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#observation_chart").click(function (){
        window.open('./nursingActionPlan/observation_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#feeding_chart").click(function (){
        window.open('./nursingActionPlan/feeding_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#imgDiag_chart").click(function (){
        window.open('./nursingActionPlan/imgDiag_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#bloodTrans_chart").click(function (){
        window.open('./nursingActionPlan/bloodTrans_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#exams_chart").click(function (){
        window.open('./nursingActionPlan/exams_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });

    $("#procedure_chart").click(function (){
        window.open('./nursingActionPlan/procedure_chart?mrn='+$('#mrn_nursActionPlan').val()+'&episno='+$("#episno_nursActionPlan").val()+'&age='+$("#age_nursActionPlan").val(), '_blank');
    });
    /////////////////////////////////////print button ends/////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    $("#jqGridNursActionPlan_panel").on("shown.bs.collapse", function (){
        var saveParam = {
			action: 'get_table_formHeader',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn_nursActionPlan: $("#mrn_nursActionPlan").val(),
			episno_nursActionPlan: $("#episno_nursActionPlan").val(),
		};
		
		$.post("nursingActionPlan/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formHeader",data.episode);
				autoinsert_rowdata("#formHeader",data.header);
				button_state_header('empty');
				textarea_init_nursingActionPlan();

			}else{
                autoinsert_rowdata("#formHeader",data.episode);
				autoinsert_rowdata("#formHeader",data.header);
				button_state_header('add');
				textarea_init_nursingActionPlan();
                emptyFormdata(errorField,'#formHeader');
			}
		});
        // populate_header_getdata();
        SmoothScrollTo("#jqGridNursActionPlan_panel", 500);
        let curtype = $(this).data('curtype');
        $('#jqGridNursActionPlan_panel_tabs.nav-tabs a#'+curtype).tab('show');
    });
    
    $("#jqGridNursActionPlan_panel").on("hidden.bs.collapse", function (){
        button_state_header('empty');
		disableForm('#formHeader');

        refreshGrid('#jqGridTreatment',urlParam_Treatment,'kosongkan');
        refreshGrid('#jqGridObservation',urlParam_Observation,'kosongkan');
        refreshGrid('#jqGridFeeding',urlParam_Feeding,'kosongkan');
        refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'kosongkan');
        refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'kosongkan');
        refreshGrid('#jqGridExams',urlParam_Exams,'kosongkan');
        refreshGrid('#jqGridProcedure',urlParam_Procedure,'kosongkan');

        $("#jqGridNursActionPlan_panel > div").scrollTop(0);
        $("#jqGridNursActionPlan_panel #jqGridNursActionPlan_panel_tabs li").removeClass('active');
    });
    
    $('#jqGridNursActionPlan_panel_tabs.nav-tabs a').on('shown.bs.tab', function (e){
        let type = $(this).data('type');
        let id = $(this).attr('id');
        $("#jqGridNursActionPlan_panel").data('curtype',id);
        switch(type){
            
            case 'treatment':
                urlParam_Treatment.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Treatment.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridTreatment',urlParam_Treatment,'add'); 
                refreshGrid('#jqGridAddNotesNursActPlanTreatment',urlParam_AddNotesNursActPlanTreatment,'add_notesNursActPlanTreatment'); 
                $("#jqGridTreatment").jqGrid('setGridWidth', Math.floor($("#jqGridTreatment_c")[0].offsetWidth-$("#jqGridTreatment_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanTreatment").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanTreatment_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanTreatment_c")[0].offsetLeft-30));
                break;
            case 'observation':
                urlParam_Observation.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Observation.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridObservation',urlParam_Observation,'add');
                refreshGrid('#jqGridAddNotesNursActPlanObservation',urlParam_AddNotesNursActPlanObservation,'add_notesNursActPlanObservation');
                $("#jqGridObservation").jqGrid('setGridWidth', Math.floor($("#jqGridObservation_c")[0].offsetWidth-$("#jqGridObservation_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanObservation").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanObservation_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanObservation_c")[0].offsetLeft-30));
                break;
            case 'feeding':
                urlParam_Feeding.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Feeding.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridFeeding',urlParam_Feeding,'add');
                refreshGrid('#jqGridAddNotesNursActPlanFeeding',urlParam_AddNotesNursActPlanFeeding,'add_notesNursActPlanFeeding');
                $("#jqGridFeeding").jqGrid('setGridWidth', Math.floor($("#jqGridFeeding_c")[0].offsetWidth-$("#jqGridFeeding_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanFeeding").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanFeeding_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanFeeding_c")[0].offsetLeft-30));
                break;
            case 'imgDiag':
                urlParam_ImgDiag.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_ImgDiag.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'add');
                refreshGrid('#jqGridAddNotesNursActPlanImgDiag',urlParam_AddNotesNursActPlanImgDiag,'add_notesNursActPlanImgDiag');
                $("#jqGridImgDiag").jqGrid('setGridWidth', Math.floor($("#jqGridImgDiag_c")[0].offsetWidth-$("#jqGridImgDiag_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanImgDiag").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanImgDiag_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanImgDiag_c")[0].offsetLeft-30));
                break;
            case 'bloodTrans':
                urlParam_BloodTrans.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_BloodTrans.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'add');
                refreshGrid('#jqGridAddNotesNursActPlanBloodTrans',urlParam_AddNotesNursActPlanBloodTrans,'add_notesNursActPlanBloodTrans');
                $("#jqGridBloodTrans").jqGrid('setGridWidth', Math.floor($("#jqGridBloodTrans_c")[0].offsetWidth-$("#jqGridBloodTrans_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanBloodTrans").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanBloodTrans_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanBloodTrans_c")[0].offsetLeft-30));
                break;
            case 'exams':
                urlParam_Exams.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Exams.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridExams',urlParam_Exams,'add');
                refreshGrid('#jqGridAddNotesNursActPlanExams',urlParam_AddNotesNursActPlanExams,'add_notesNursActPlanExams');
                $("#jqGridExams").jqGrid('setGridWidth', Math.floor($("#jqGridExams_c")[0].offsetWidth-$("#jqGridExams_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanExams").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanExams_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanExams_c")[0].offsetLeft-30));
                break;
            case 'procedure':
                urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                urlParam_Procedure.filterVal[2] = 'artLine';
                refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'artLine';
                refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');

                $('#prod').text('ARTERIAL LINE');
                $('#addNotes_title').text('ADDITIONAL NOTES ARTERIAL LINE');

                $('input[name="ptype"]:radio').on('change', function() {
                    let ptype  = $("#formProcedure input[type=radio]:checked").val();
                    // console.log(type);

                    switch(ptype){
                        case 'artLine':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'artLine';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'artLine';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
                            
                            $('#prod').text('ARTERIAL LINE');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR ARTERIAL LINE');
                            break;
                        
                        case 'CVP':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'CVP';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'CVP';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
                            
                            $('#prod').text('CVP');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR CVP');
                            break;

                        case 'venLine':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'venLine';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'venLine';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
                            
                            $('#prod').text('VENOUS LINE');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR VENOUS LINE');
                            break;

                        case 'ETT':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'ETT';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'ETT';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
                            
                            $('#prod').text('ETT');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR ETT');
                            break;

                        case 'CBD':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'CBD';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'CBD';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
                            
                            $('#prod').text('CBD');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR CBD');
                            break;

                        case 'STO':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'STO';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'STO';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');

                            $('#prod').text('STO');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR STO');
                            break;

                        case 'woundIns':
                            urlParam_Procedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_Procedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_Procedure.filterVal[2] = 'woundIns';
                            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');

                            urlParam_AddNotesNursActPlanProcedure.filterVal[0] = $("#mrn_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[1] = $("#episno_nursActionPlan").val();
                            urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
                            urlParam_AddNotesNursActPlanProcedure.filterVal[3] = 'woundIns';
                            refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');

                            $('#prod').text('WOUND INSPECTION');
                            $('#addNotes_title').text('ADDITIONAL NOTES FOR WOUND INSPECTION');
                            break;
                    }

                });

                $("#jqGridProcedure").jqGrid('setGridWidth', Math.floor($("#jqGridProcedure_c")[0].offsetWidth-$("#jqGridProcedure_c")[0].offsetLeft-30));
                $("#jqGridAddNotesNursActPlanProcedure").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesNursActPlanProcedure_c")[0].offsetWidth-$("#jqGridAddNotesNursActPlanProcedure_c")[0].offsetLeft-30));

                break;
                
        }
    });
    
    /////////////////////////////////////////Treatment starts/////////////////////////////////////////
    /////////////////////////////////////////jqGridTreatment/////////////////////////////////////////
    var addmore_jqgrid2 = { more:false,state:false,edit:false }
    
    $("#jqGridTreatment").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Start Date', name: 'startdate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Treatment', name: 'treatment', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'End Date', name: 'enddate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'enddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

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
        pager: "#jqGridPagerTreatment",
        loadComplete: function (){
            if(addmore_jqgrid2.more == true){$('#jqGridTreatment_iladd').click();}
            else{
                $('#jqGridTreatment').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid2.edit = addmore_jqgrid2.more = false; // reset
            
            // calc_jq_height_onchange("jqGridTreatment");
            
            if($("#jqGridTreatment").data('lastselrow') == undefined){
                $("#jqGridTreatment").setSelection($("#jqGridTreatment").getDataIDs()[0]);
            }else{
                $("#jqGridTreatment").setSelection($("#jqGridTreatment").data('lastselrow'));
                delay(function (){
                    $('#jqGridTreatment tr#'+$("#jqGridTreatment").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridTreatment_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerTreatment').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerTreatment").setSelection($("#jqGridPagerTreatment").getDataIDs()[0]);
            }
        },
    });
    
    ///////////////////////////////////myEditOptions_add_Treatment///////////////////////////////////
    var myEditOptions_add_Treatment = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").hide();
            
            $("#jqGridTreatment input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridTreatment_ilsave').click();
                // addmore_jqgrid2.state = true;
                // $('#jqGridTreatment_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
            addmore_jqgrid2.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridTreatment',urlParam_Treatment,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").show();
        },
        errorfunc: function (rowid,response){
            refreshGrid('#jqGridTreatment',urlParam_Treatment,'add');
        },
        beforeSaveRow: function (options, rowid){
            
            let data = $('#jqGridTreatment').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Treatment_save',
                });
            $("#jqGridTreatment").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////myEditOptions_edit_Treatment//////////////////////////////////
    var myEditOptions_edit_Treatment = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").hide();
            
            $("#jqGridTreatment input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridTreatment_ilsave').click();
                // addmore_jqgrid2.state = true;
                // $('#jqGridTreatment_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridTreatment',urlParam_Treatment,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").show();
        },
        errorfunc: function (rowid,response){
            // $('#p_error').text(response.responseText);
            alert(response.responseText);
            refreshGrid('#jqGridTreatment',urlParam_Treatment,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridTreatment').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Treatment_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridTreatment").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////////jqGridPagerTreatment//////////////////////////////////////
    $("#jqGridTreatment").inlineNav('#jqGridPagerTreatment', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Treatment
        },
        editParams: myEditOptions_edit_Treatment
    }).jqGrid('navButtonAdd', "#jqGridPagerTreatment", {
        id: "jqGridPagerDelete_Treatment",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridTreatment").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridTreatment').idno,
                        action: 'Treatment_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridTreatment", urlParam_Treatment);
                    });
                }else{
                    $("#jqGridPagerDelete_Treatment,#jqGridPagerRefresh_Treatment").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerTreatment", {
        id: "jqGridPagerRefresh_Treatment",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridTreatment", urlParam_Treatment);
        },
    });
    
    $("#jqGridTreatment_ilcancel").click(function (){
        refreshGrid("#jqGridTreatment", urlParam_Treatment);
    });
    ////////////////////////////////////////////end grid////////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanTreatment = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanTreatment///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanTreatment").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanTreatment",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanTreatment.more == true){$('#jqGridAddNotesNursActPlanTreatment_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanTreatment.edit = addmore_jqgridNursActPlanTreatment.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanTreatment");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanTreatment_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanTreatment = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanTreatment,#jqGridPagerRefresh_addnoteNursActPlanTreatment").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanTreatment_ilsave').click();
				// addmore_jqgridNursActPlanTreatment.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanTreatment.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanTreatment',urlParam_AddNotesNursActPlanTreatment,'add_notesNursActPlanTreatment');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanTreatment,#jqGridPagerRefresh_addnoteNursActPlanTreatment").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanTreatment',urlParam_AddNotesNursActPlanTreatment,'add_notesNursActPlanTreatment');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanTreatment').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanTreatment_save',
				});
			$("#jqGridAddNotesNursActPlanTreatment").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanTreatment,#jqGridPagerRefresh_addnoteNursActPlanTreatment").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanTreatment/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanTreatment").inlineNav('#jqGridPagerAddNotesNursActPlanTreatment', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanTreatment
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanTreatment", {
		id: "jqGridPagerRefresh_addnoteNursActPlanTreatment",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanTreatment", urlParam_AddNotesNursActPlanTreatment);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////

    //////////////////////////////////////////Treatment ends//////////////////////////////////////////
    
    ///////////////////////////////////////observation starts///////////////////////////////////////
    ///////////////////////////////////////jqGridObservation///////////////////////////////////////
    var addmore_jqgrid3 = { more:false,state:false,edit:false }
    
    $("#jqGridObservation").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Start Date', name: 'startdate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Observation', name: 'observation', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'End Date', name: 'enddate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'enddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },
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
        pager: "#jqGridPagerObservation",
        loadComplete: function (){
            if(addmore_jqgrid3.more == true){$('#jqGridObservation_iladd').click();}
            else{
                $('#jqGridObservation').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid3.edit = addmore_jqgrid3.more = false; // reset
            
            // calc_jq_height_onchange("jqGridObservation");
            
            if($("#jqGridObservation").data('lastselrow') == undefined){
                $("#jqGridObservation").setSelection($("#jqGridObservation").getDataIDs()[0]);
            }else{
                $("#jqGridObservation").setSelection($("#jqGridObservation").data('lastselrow'));
                delay(function (){
                    $('#jqGridObservation tr#'+$("#jqGridObservation").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridObservation_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerObservation').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerObservation").setSelection($("#jqGridPagerObservation").getDataIDs()[0]);
            }
        },
    });
    
    /////////////////////////////////myEditOptions_add_Observation/////////////////////////////////
    var myEditOptions_add_Observation = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").hide();
            
            $("#jqGridObservation input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridObservation_ilsave').click();
                // addmore_jqgrid3.state = true;
                // $('#jqGridObservation_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid3.state == true)addmore_jqgrid3.more = true; // only addmore after save inline
            addmore_jqgrid3.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridObservation',urlParam_Observation,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridObservation',urlParam_Observation,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridObservation').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Observation_save',
                });
            $("#jqGridObservation").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_Observation////////////////////////////////
    var myEditOptions_edit_Observation = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").hide();
            
            $("#jqGridObservation input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridObservation_ilsave').click();
                // addmore_jqgrid3.state = true;
                // $('#jqGridObservation_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid3.state == true)addmore_jqgrid3.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridObservation',urlParam_Observation,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridObservation',urlParam_Observation,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridObservation').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Observation_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridObservation").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerObservation////////////////////////////////////
    $("#jqGridObservation").inlineNav('#jqGridPagerObservation', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Observation
        },
        editParams: myEditOptions_edit_Observation
    }).jqGrid('navButtonAdd', "#jqGridPagerObservation", {
        id: "jqGridPagerDelete_Observation",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridObservation").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridObservation').idno,
                        action: 'Observation_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridObservation", urlParam_Observation);
                    });
                }else{
                    $("#jqGridPagerDelete_Observation,#jqGridPagerRefresh_Observation").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerObservation", {
        id: "jqGridPagerRefresh_Observation",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridObservation", urlParam_Observation);
        },
    });
    
    $("#jqGridObservation_ilcancel").click(function (){
        refreshGrid("#jqGridObservation", urlParam_Observation);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanObservation = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanObservation///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanObservation").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanObservation",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanObservation.more == true){$('#jqGridAddNotesNursActPlanObservation_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanObservation.edit = addmore_jqgridNursActPlanObservation.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanObservation");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanObservation_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanObservation = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanObservation,#jqGridPagerRefresh_addnoteNursActPlanObservation").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanObservation_ilsave').click();
				// addmore_jqgridNursActPlanObservation.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanObservation.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanObservation',urlParam_AddNotesNursActPlanObservation,'add_notesNursActPlanObservation');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanObservation,#jqGridPagerRefresh_addnoteNursActPlanObservation").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanObservation',urlParam_AddNotesNursActPlanObservation,'add_notesNursActPlanObservation');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanObservation').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanObservation_save',
				});
			$("#jqGridAddNotesNursActPlanObservation").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanObservation,#jqGridPagerRefresh_addnoteNursActPlanObservation").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanObservation/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanObservation").inlineNav('#jqGridPagerAddNotesNursActPlanObservation', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanObservation
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanObservation", {
		id: "jqGridPagerRefresh_addnoteNursActPlanObservation",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanObservation", urlParam_AddNotesNursActPlanObservation);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////

    ////////////////////////////////////////Observation ends////////////////////////////////////////
    
    //////////////////////////////////////feeding starts//////////////////////////////////////
    //////////////////////////////////////jqGridFeeding//////////////////////////////////////
    var addmore_jqgrid4 = { more:false,state:false,edit:false }
    
    $("#jqGridFeeding").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Start Date', name: 'startdate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Feeding', name: 'feeding', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'End Date', name: 'enddate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'enddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

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
        pager: "#jqGridPagerFeeding",
        loadComplete: function (){
            if(addmore_jqgrid4.more == true){$('#jqGridFeeding_iladd').click();}
            else{
                $('#jqGridFeeding').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid4.edit = addmore_jqgrid4.more = false; // reset
            
            // calc_jq_height_onchange("jqGridFeeding");
            
            if($("#jqGridFeeding").data('lastselrow') == undefined){
                $("#jqGridFeeding").setSelection($("#jqGridFeeding").getDataIDs()[0]);
            }else{
                $("#jqGridFeeding").setSelection($("#jqGridFeeding").data('lastselrow'));
                delay(function (){
                    $('#jqGridFeeding tr#'+$("#jqGridFeeding").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridFeeding_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerFeeding').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerFeeding").setSelection($("#jqGridPagerFeeding").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_Feeding////////////////////////////////
    var myEditOptions_add_Feeding = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").hide();
            
            $("#jqGridFeeding input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridFeeding_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridFeeding_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            addmore_jqgrid4.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridFeeding',urlParam_Feeding,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridFeeding',urlParam_Feeding,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridFeeding').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Feeding_save',
                });
            $("#jqGridFeeding").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ///////////////////////////////myEditOptions_edit_Feeding///////////////////////////////
    var myEditOptions_edit_Feeding = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").hide();
            
            $("#jqGridFeeding input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridFeeding_ilsave').click();
                // addmore_jqgrid4.state = true;
                // $('#jqGridFeeding_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid4.state == true)addmore_jqgrid4.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridFeeding',urlParam_Feeding,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridFeeding',urlParam_Feeding,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridFeeding').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Feeding_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridFeeding").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ///////////////////////////////////jqGridPagerFeeding///////////////////////////////////
    $("#jqGridFeeding").inlineNav('#jqGridPagerFeeding', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Feeding
        },
        editParams: myEditOptions_edit_Feeding
    }).jqGrid('navButtonAdd', "#jqGridPagerFeeding", {
        id: "jqGridPagerDelete_Feeding",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridFeeding").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridFeeding').idno,
                        action: 'Feeding_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridFeeding", urlParam_Feeding);
                    });
                }else{
                    $("#jqGridPagerDelete_Feeding,#jqGridPagerRefresh_Feeding").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerFeeding", {
        id: "jqGridPagerRefresh_Feeding",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridFeeding", urlParam_Feeding);
        },
    });
    
    $("#jqGridFeeding_ilcancel").click(function (){
        refreshGrid("#jqGridFeeding", urlParam_Feeding);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanFeeding = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanFeeding///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanFeeding").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanFeeding",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanFeeding.more == true){$('#jqGridAddNotesNursActPlanFeeding_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanFeeding.edit = addmore_jqgridNursActPlanFeeding.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanFeeding");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanFeeding_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanFeeding = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanFeeding,#jqGridPagerRefresh_addnoteNursActPlanFeeding").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanFeeding_ilsave').click();
				// addmore_jqgridNursActPlanFeeding.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanFeeding.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanFeeding',urlParam_AddNotesNursActPlanFeeding,'add_notesNursActPlanFeeding');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanFeeding,#jqGridPagerRefresh_addnoteNursActPlanFeeding").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanFeeding',urlParam_AddNotesNursActPlanFeeding,'add_notesNursActPlanFeeding');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanFeeding').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanFeeding_save',
				});
			$("#jqGridAddNotesNursActPlanFeeding").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanFeeding,#jqGridPagerRefresh_addnoteNursActPlanFeeding").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanFeeding/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanFeeding").inlineNav('#jqGridPagerAddNotesNursActPlanFeeding', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanFeeding
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanFeeding", {
		id: "jqGridPagerRefresh_addnoteNursActPlanFeeding",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanFeeding", urlParam_AddNotesNursActPlanFeeding);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
    ///////////////////////////////////////Feeding ends///////////////////////////////////////
    
    //////////////////////////////////////ImgDiag starts//////////////////////////////////////
    //////////////////////////////////////jqGridImgDiag//////////////////////////////////////
    var addmore_jqgrid5 = { more:false,state:false,edit:false }
    
    $("#jqGridImgDiag").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Date', name: 'startdate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Imaging & Diagnostic', name: 'imgdiag', classes: 'wrap', width: 60, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 60, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            // { label: 'Dateline', name: 'dateline', width: 50, classes: 'wrap', editable: true,
            //     formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
            //     editoptions: {
            //         dataInit: function (element){
            //             $(element).datepicker({
            //                 id: 'dateline_datePicker',
            //                 dateFormat: 'dd-mm-yy',
            //                 minDate: "dateToday",
            //                 showOn: 'focus',
            //                 changeMonth: true,
            //                 changeYear: true,
            //                 onSelect : function (){
            //                     $(this).focus();
            //                 }
            //             });
            //         }
            //     }
            // },
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

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
        pager: "#jqGridPagerImgDiag",
        loadComplete: function (){
            if(addmore_jqgrid5.more == true){$('#jqGridImgDiag_iladd').click();}
            else{
                $('#jqGridImgDiag').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid5.edit = addmore_jqgrid5.more = false; // reset
            
            // calc_jq_height_onchange("jqGridImgDiag");
            
            if($("#jqGridImgDiag").data('lastselrow') == undefined){
                $("#jqGridImgDiag").setSelection($("#jqGridImgDiag").getDataIDs()[0]);
            }else{
                $("#jqGridImgDiag").setSelection($("#jqGridImgDiag").data('lastselrow'));
                delay(function (){
                    $('#jqGridImgDiag tr#'+$("#jqGridImgDiag").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridImgDiag_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerImgDiag').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerImgDiag").setSelection($("#jqGridPagerImgDiag").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_ImgDiag////////////////////////////////
    var myEditOptions_add_ImgDiag = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").hide();
            
            $("#jqGridImgDiag textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridImgDiag_ilsave').click();
                // addmore_jqgrid5.state = true;
                // $('#jqGridImgDiag_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid5.state == true)addmore_jqgrid5.more = true; // only addmore after save inline
            addmore_jqgrid5.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridImgDiag').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'ImgDiag_save',
                });
            $("#jqGridImgDiag").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_ImgDiag////////////////////////////////
    var myEditOptions_edit_ImgDiag = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").hide();
            
            $("#jqGridImgDiag textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridImgDiag_ilsave').click();
                // addmore_jqgrid5.state = true;
                // $('#jqGridImgDiag_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid5.state == true)addmore_jqgrid5.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridImgDiag',urlParam_ImgDiag,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridImgDiag').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'ImgDiag_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridImgDiag").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerImgDiag////////////////////////////////////
    $("#jqGridImgDiag").inlineNav('#jqGridPagerImgDiag', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_ImgDiag
        },
        editParams: myEditOptions_edit_ImgDiag
    }).jqGrid('navButtonAdd', "#jqGridPagerImgDiag", {
        id: "jqGridPagerDelete_ImgDiag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridImgDiag").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridImgDiag').idno,
                        action: 'ImgDiag_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridImgDiag", urlParam_ImgDiag);
                    });
                }else{
                    $("#jqGridPagerDelete_ImgDiag,#jqGridPagerRefresh_ImgDiag").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerImgDiag", {
        id: "jqGridPagerRefresh_ImgDiag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridImgDiag", urlParam_ImgDiag);
        },
    });
    
    $("#jqGridImgDiag_ilcancel").click(function (){
        refreshGrid("#jqGridImgDiag", urlParam_ImgDiag);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanImgDiag = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanImgDiag///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanImgDiag").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanImgDiag",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanImgDiag.more == true){$('#jqGridAddNotesNursActPlanImgDiag_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanImgDiag.edit = addmore_jqgridNursActPlanImgDiag.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanImgDiag");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanImgDiag_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanImgDiag = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanImgDiag,#jqGridPagerRefresh_addnoteNursActPlanImgDiag").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanImgDiag_ilsave').click();
				// addmore_jqgridNursActPlanImgDiag.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanImgDiag.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanImgDiag',urlParam_AddNotesNursActPlanImgDiag,'add_notesNursActPlanImgDiag');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanImgDiag,#jqGridPagerRefresh_addnoteNursActPlanImgDiag").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanImgDiag',urlParam_AddNotesNursActPlanImgDiag,'add_notesNursActPlanImgDiag');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanImgDiag').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanImgDiag_save',
				});
			$("#jqGridAddNotesNursActPlanImgDiag").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanImgDiag,#jqGridPagerRefresh_addnoteNursActPlanImgDiag").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanImgDiag/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanImgDiag").inlineNav('#jqGridPagerAddNotesNursActPlanImgDiag', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanImgDiag
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanImgDiag", {
		id: "jqGridPagerRefresh_addnoteNursActPlanImgDiag",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanImgDiag", urlParam_AddNotesNursActPlanImgDiag);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////

    ///////////////////////////////////////ImgDiag ends///////////////////////////////////////
    
    //////////////////////////////////////bloodTrans starts//////////////////////////////////////
    //////////////////////////////////////jqGridBloodTrans//////////////////////////////////////
    var addmore_jqgrid6 = { more:false,state:false,edit:false }
    
    $("#jqGridBloodTrans").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Date', name: 'startdate', width: 40, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Pack Cell', name: 'packcell', width: 40, editable: true },
            { label: 'Whole Body', name: 'wholebody', width: 40, editable: true },
            { label: 'Platlet', name: 'platlet', width: 40, editable: true },
            { label: 'FFP', name: 'ffp', width: 40, editable: true },
            { label: 'Remarks', name: 'remarks', classes: 'wrap', width: 60, editable: true, edittype: "textarea",
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
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

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
        pager: "#jqGridPagerBloodTrans",
        loadComplete: function (){
            if(addmore_jqgrid6.more == true){$('#jqGridBloodTrans_iladd').click();}
            else{
                $('#jqGridBloodTrans').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid6.edit = addmore_jqgrid6.more = false; // reset
            
            // calc_jq_height_onchange("jqGridBloodTrans");
            
            if($("#jqGridBloodTrans").data('lastselrow') == undefined){
                $("#jqGridBloodTrans").setSelection($("#jqGridBloodTrans").getDataIDs()[0]);
            }else{
                $("#jqGridBloodTrans").setSelection($("#jqGridBloodTrans").data('lastselrow'));
                delay(function (){
                    $('#jqGridBloodTrans tr#'+$("#jqGridBloodTrans").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridBloodTrans_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerBloodTrans').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerBloodTrans").setSelection($("#jqGridPagerBloodTrans").getDataIDs()[0]);
            }
        },
    });
    
    ////////////////////////////////myEditOptions_add_BloodTrans////////////////////////////////
    var myEditOptions_add_BloodTrans = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").hide();
            
            $("#jqGridBloodTrans textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBloodTrans_ilsave').click();
                // addmore_jqgrid6.state = true;
                // $('#jqGridBloodTrans_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid6.state == true)addmore_jqgrid6.more = true; // only addmore after save inline
            addmore_jqgrid6.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridBloodTrans').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'BloodTrans_save',
                });
            $("#jqGridBloodTrans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////myEditOptions_edit_BloodTrans////////////////////////////////
    var myEditOptions_edit_BloodTrans = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").hide();
            
            $("#jqGridBloodTrans textarea[name='remarks']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridBloodTrans_ilsave').click();
                // addmore_jqgrid6.state = true;
                // $('#jqGridBloodTrans_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid6.state == true)addmore_jqgrid6.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridBloodTrans',urlParam_BloodTrans,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridBloodTrans').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'BloodTrans_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridBloodTrans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////jqGridPagerBloodTrans////////////////////////////////////
    $("#jqGridBloodTrans").inlineNav('#jqGridPagerBloodTrans', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_BloodTrans
        },
        editParams: myEditOptions_edit_BloodTrans
    }).jqGrid('navButtonAdd', "#jqGridPagerBloodTrans", {
        id: "jqGridPagerDelete_BloodTrans",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridBloodTrans").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridBloodTrans').idno,
                        action: 'BloodTrans_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridBloodTrans", urlParam_BloodTrans);
                    });
                }else{
                    $("#jqGridPagerDelete_BloodTrans,#jqGridPagerRefresh_BloodTrans").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerBloodTrans", {
        id: "jqGridPagerRefresh_BloodTrans",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridBloodTrans", urlParam_BloodTrans);
        },
    });
    
    $("#jqGridBloodTrans_ilcancel").click(function (){
        refreshGrid("#jqGridBloodTrans", urlParam_BloodTrans);
    });
    ///////////////////////////////////////////end grid///////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanBloodTrans = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanBloodTrans///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanBloodTrans").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanBloodTrans",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanBloodTrans.more == true){$('#jqGridAddNotesNursActPlanBloodTrans_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanBloodTrans.edit = addmore_jqgridNursActPlanBloodTrans.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanBloodTrans");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanBloodTrans_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanBloodTrans = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanBloodTrans,#jqGridPagerRefresh_addnoteNursActPlanBloodTrans").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanBloodTrans_ilsave').click();
				// addmore_jqgridNursActPlanBloodTrans.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanBloodTrans.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanBloodTrans',urlParam_AddNotesNursActPlanBloodTrans,'add_notesNursActPlanBloodTrans');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanBloodTrans,#jqGridPagerRefresh_addnoteNursActPlanBloodTrans").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanBloodTrans',urlParam_AddNotesNursActPlanBloodTrans,'add_notesNursActPlanBloodTrans');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanBloodTrans').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanBloodTrans_save',
				});
			$("#jqGridAddNotesNursActPlanBloodTrans").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanBloodTrans,#jqGridPagerRefresh_addnoteNursActPlanBloodTrans").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanBloodTrans/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanBloodTrans").inlineNav('#jqGridPagerAddNotesNursActPlanBloodTrans', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanBloodTrans
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanBloodTrans", {
		id: "jqGridPagerRefresh_addnoteNursActPlanBloodTrans",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanBloodTrans", urlParam_AddNotesNursActPlanBloodTrans);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
    ///////////////////////////////////////BloodTrans ends///////////////////////////////////////

    /////////////////////////////////////////Exams starts/////////////////////////////////////////
    /////////////////////////////////////////jqGridExams/////////////////////////////////////////
    var addmore_jqgrid7 = { more:false,state:false,edit:false }
    
    $("#jqGridExams").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Date', name: 'startdate', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Exam', name: 'exam', classes: 'wrap', width: 70, editable: true, edittype: "textarea",
                editoptions: {
                    style: "width: -webkit-fill-available;",
                    rows: 5
                }
            },
            { label: 'Dateline', name: 'dateline', width: 50, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'dateline_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

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
        pager: "#jqGridPagerExams",
        loadComplete: function (){
            if(addmore_jqgrid7.more == true){$('#jqGridExams_iladd').click();}
            else{
                $('#jqGridExams').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid7.edit = addmore_jqgrid7.more = false; // reset
            
            // calc_jq_height_onchange("jqGridExams");
            
            if($("#jqGridExams").data('lastselrow') == undefined){
                $("#jqGridExams").setSelection($("#jqGridExams").getDataIDs()[0]);
            }else{
                $("#jqGridExams").setSelection($("#jqGridExams").data('lastselrow'));
                delay(function (){
                    $('#jqGridExams tr#'+$("#jqGridExams").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridExams_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerExams').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerExams").setSelection($("#jqGridPagerExams").getDataIDs()[0]);
            }
        },
    });
    
    ///////////////////////////////////myEditOptions_add_Exams///////////////////////////////////
    var myEditOptions_add_Exams = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").hide();
            
            $("#jqGridExams input[name='dateline']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridExams_ilsave').click();
                // addmore_jqgrid7.state = true;
                // $('#jqGridExams_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid7.state == true)addmore_jqgrid7.more = true; // only addmore after save inline
            addmore_jqgrid7.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridExams',urlParam_Exams,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridExams',urlParam_Exams,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridExams').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Exams_save',
                });
            $("#jqGridExams").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////myEditOptions_edit_Exams//////////////////////////////////
    var myEditOptions_edit_Exams = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").hide();
            
            $("#jqGridExams input[name='dateline']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridExams_ilsave').click();
                // addmore_jqgrid7.state = true;
                // $('#jqGridExams_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid7.state == true)addmore_jqgrid7.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridExams',urlParam_Exams,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridExams',urlParam_Exams,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridExams').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    action: 'Exams_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridExams").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////////jqGridPagerExams//////////////////////////////////////
    $("#jqGridExams").inlineNav('#jqGridPagerExams', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Exams
        },
        editParams: myEditOptions_edit_Exams
    }).jqGrid('navButtonAdd', "#jqGridPagerExams", {
        id: "jqGridPagerDelete_Exams",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridExams").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridExams').idno,
                        action: 'Exams_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridExams", urlParam_Exams);
                    });
                }else{
                    $("#jqGridPagerDelete_Exams,#jqGridPagerRefresh_Exams").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerExams", {
        id: "jqGridPagerRefresh_Exams",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridExams", urlParam_Exams);
        },
    });
    
    $("#jqGridExams_ilcancel").click(function (){
        refreshGrid("#jqGridExams", urlParam_Exams);
    });
    ////////////////////////////////////////////end grid////////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanExams = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanExams///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanExams").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursActPlanExams",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanExams.more == true){$('#jqGridAddNotesNursActPlanExams_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanExams.edit = addmore_jqgridNursActPlanExams.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanExams");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanExams_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanExams = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanExams,#jqGridPagerRefresh_addnoteNursActPlanExams").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanExams_ilsave').click();
				// addmore_jqgridNursActPlanExams.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanExams.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanExams',urlParam_AddNotesNursActPlanExams,'add_notesNursActPlanExams');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanExams,#jqGridPagerRefresh_addnoteNursActPlanExams").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanExams',urlParam_AddNotesNursActPlanExams,'add_notesNursActPlanExams');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanExams').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
					action: 'addNotesNursActPlanExams_save',
				});
			$("#jqGridAddNotesNursActPlanExams").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanExams,#jqGridPagerRefresh_addnoteNursActPlanExams").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanExams/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanExams").inlineNav('#jqGridPagerAddNotesNursActPlanExams', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanExams
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanExams", {
		id: "jqGridPagerRefresh_addnoteNursActPlanExams",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanExams", urlParam_AddNotesNursActPlanExams);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
    //////////////////////////////////////////Exam ends//////////////////////////////////////////
    
    /////////////////////////////////////////Procedure starts/////////////////////////////////////////
    /////////////////////////////////////////jqGridProcedure/////////////////////////////////////////
    var addmore_jqgrid8 = { more:false,state:false,edit:false }
    
    $("#jqGridProcedure").jqGrid({
        datatype: "local",
        editurl: "./nursingActionPlan/form",
        colModel: [
            { label: 'Start Date', name: 'startdate', width: 30, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Size', name: 'size', width: 30, editable: true },
            { label: 'End Date', name: 'enddate', width: 30, classes: 'wrap', editable: true,
                formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'enddate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: new Date($("#reg_date").val()),
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
            { label: 'Entered By', name: 'adduser', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'prodType', name: 'prodType', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

        ],
        autowidth: true,
        multiSort: true,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 400,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerProcedure",
        loadComplete: function (){
            if(addmore_jqgrid8.more == true){$('#jqGridProcedure_iladd').click();}
            else{
                $('#jqGridProcedure').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid8.edit = addmore_jqgrid8.more = false; // reset
            
            // calc_jq_height_onchange("jqGridProcedure");
            
            if($("#jqGridProcedure").data('lastselrow') == undefined){
                $("#jqGridProcedure").setSelection($("#jqGridProcedure").getDataIDs()[0]);
            }else{
                $("#jqGridProcedure").setSelection($("#jqGridProcedure").data('lastselrow'));
                delay(function (){
                    $('#jqGridProcedure tr#'+$("#jqGridProcedure").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridProcedure_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerProcedure').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerProcedure").setSelection($("#jqGridPagerProcedure").getDataIDs()[0]);
            }
        },
    });
    
    ///////////////////////////////////myEditOptions_add_Procedure///////////////////////////////////
    var myEditOptions_add_Procedure = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").hide();
            
            $("#jqGridProcedure input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridProcedure_ilsave').click();
                // addmore_jqgrid8.state = true;
                // $('#jqGridProcedure_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // if(addmore_jqgrid8.state == true)addmore_jqgrid8.more = true; // only addmore after save inline
            addmore_jqgrid8.more = true; // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');
            errorField.length = 0;
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridProcedure',urlParam_Procedure,'add');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridProcedure').jqGrid ('getRowData', rowid);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn_nursActionPlan: $('#mrn_nursActionPlan').val(),
                    episno_nursActionPlan: $('#episno_nursActionPlan').val(),
                    prodType: $("#formProcedure input[type=radio]:checked").val(),
                    action: 'Procedure_save',
                });                    

            $("#jqGridProcedure").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////myEditOptions_edit_Procedure//////////////////////////////////
    var myEditOptions_edit_Procedure = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").hide();
            
            $("#jqGridProcedure input[name='enddate']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridProcedure_ilsave').click();
                // addmore_jqgrid8.state = true;
                // $('#jqGridProcedure_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid8.state == true)addmore_jqgrid8.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridProcedure',urlParam_Procedure,'edit');
            errorField.length = 0;
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridProcedure',urlParam_Procedure,'edit');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            // if(errorField.length > 0){console.log(errorField);return false;}
            
            let data = $('#jqGridProcedure').jqGrid ('getRowData', rowid);
            // console.log(data);
            
            let editurl = "./nursingActionPlan/form?"+
                $.param({
                    mrn: $('#mrn_nursActionPlan').val(),
                    episno: $('#episno_nursActionPlan').val(),
                    prodType: $('#prodType').val(),
                    action: 'Procedure_edit',
                    _token: $("#csrf_token").val()
                });
            $("#jqGridProcedure").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    //////////////////////////////////////jqGridPagerProcedure//////////////////////////////////////
    $("#jqGridProcedure").inlineNav('#jqGridPagerProcedure', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Procedure
        },
        editParams: myEditOptions_edit_Procedure
    }).jqGrid('navButtonAdd', "#jqGridPagerProcedure", {
        id: "jqGridPagerDelete_Procedure",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridProcedure").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridProcedure').idno,
                        action: 'Procedure_del',
                    }
                    $.post("./nursingActionPlan/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        alert(data.responseText);
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridProcedure", urlParam_Procedure);
                    });
                }else{
                    $("#jqGridPagerDelete_Procedure,#jqGridPagerRefresh_Procedure").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerProcedure", {
        id: "jqGridPagerRefresh_Procedure",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridProcedure", urlParam_Procedure);
        },
    });
    
    $("#jqGridProcedure_ilcancel").click(function (){
        refreshGrid("#jqGridProcedure", urlParam_Procedure);
    });
    ////////////////////////////////////////////end grid////////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursActPlanProcedure = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursActPlanProcedure///////////////////////////////////////
	$("#jqGridAddNotesNursActPlanProcedure").jqGrid({
		datatype: "local",
		editurl: "./nursingActionPlan/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
            { label: 'prodType', name: 'prodType', hidden: true },
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
		pager: "#jqGridPagerAddNotesNursActPlanProcedure",
		loadComplete: function (){
			if(addmore_jqgridNursActPlanProcedure.more == true){$('#jqGridAddNotesNursActPlanProcedure_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursActPlanProcedure.edit = addmore_jqgridNursActPlanProcedure.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursActPlanProcedure");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursActPlanProcedure_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addNursActPlanProcedure = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesNursActPlanProcedure,#jqGridPagerRefresh_addnoteNursActPlanProcedure").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursActPlanProcedure_ilsave').click();
				// addmore_jqgridNursActPlanProcedure.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursActPlanProcedure.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesNursActPlanProcedure,#jqGridPagerRefresh_addnoteNursActPlanProcedure").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursActPlanProcedure',urlParam_AddNotesNursActPlanProcedure,'add_notesNursActPlanProcedure');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesNursActPlanProcedure').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingActionPlan/form?"+
				$.param({
					episno: $('#episno_nursActionPlan').val(),
					mrn: $('#mrn_nursActionPlan').val(),
                    prodType: $("#formProcedure input[type=radio]:checked").val(),
					action: 'addNotesNursActPlanProcedure_save',
				});
			$("#jqGridAddNotesNursActPlanProcedure").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesNursActPlanProcedure,#jqGridPagerRefresh_addnoteNursActPlanProcedure").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesNursActPlanProcedure/////////////////////////////////////
	$("#jqGridAddNotesNursActPlanProcedure").inlineNav('#jqGridPagerAddNotesNursActPlanProcedure', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursActPlanProcedure
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursActPlanProcedure", {
		id: "jqGridPagerRefresh_addnoteNursActPlanProcedure",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursActPlanProcedure", urlParam_AddNotesNursActPlanProcedure);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
    //////////////////////////////////////////Procedure ends//////////////////////////////////////////

    var dialog_icd = new ordialog(
		'icd','hisdb.diagtab',"#formHeader input[name='icd']",errorField,
		{
			colModel: [
				{ label: 'Code', name: 'icdcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['type'],
				filterVal: ['icd-10'],
			},
			ondblClickRow: function (){
                let data=selrowData('#'+dialog_icd.gridname);
				$("#formHeader textarea[name='icd_desc']").val(data['description']);
			},
            gridComplete: function(obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                    $('#rhesus').focus();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    $('#'+obj.dialogname).dialog('close');
                }
            }
		},
		{
			title: "Select ICD Code",
			open: function (){
				dialog_icd.urlParam.filterCol = ['type'];
				dialog_icd.urlParam.filterVal = ['icd-10'];
			},
		},'urlParam','radio','tab'
	);
	dialog_icd.makedialog(true);
});

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

button_state_header('empty');
function button_state_header(state){
    switch(state){
        case 'empty':
            $("#toggle_nursActionPlan").removeAttr('data-toggle');
            $('#cancel_header').data('oper','add');
            $('#new_header,#save_header,#cancel_header,#edit_header').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursActionPlan").attr('data-toggle','collapse');
            $('#cancel_header').data('oper','add');
            $("#new_header").attr('disabled',false);
            $('#save_header,#cancel_header,#edit_header').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursActionPlan").attr('data-toggle','collapse');
            $('#cancel_header').data('oper','edit');
            $("#edit_header").attr('disabled',false);
            $('#save_header,#cancel_header,#new_header').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursActionPlan").attr('data-toggle','collapse');
            $("#save_header,#cancel_header").attr('disabled',false);
            $('#edit_header,#new_header').attr('disabled',true);
            break;
        case 'disableAll':
			$("#toggle_nursActionPlan").attr('data-toggle','collapse');
			$('#new_header,#save_header,#cancel_header,#edit_tiED').attr('disabled',true);
			break;
    }
}

// screen current patient //
function populate_nursingActionPlan(obj){
    // $("#jqGridNursActionPlan_panel").collapse('hide');

    // panel header
    $('#name_show_nursActionPlan').text(obj.Name);
    $('#mrn_show_nursActionPlan').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_nursActionPlan').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_nursActionPlan').text(dob_chg(obj.DOB));
    $('#age_show_nursActionPlan').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_nursActionPlan').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_nursActionPlan').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_nursActionPlan').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_nursActionPlan').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_nursActionPlan').text(if_none(obj.areaDesc).toUpperCase());
    
    $("#mrn_nursActionPlan").val(obj.MRN);
    $("#episno_nursActionPlan").val(obj.Episno);
    $("#doctor_nursActionPlan").val(obj.q_doctorname);
    $("#ward_nursActionPlan").val(obj.ward);
    $("#bednum_nursActionPlan").val(obj.bednum);
    $("#age_nursActionPlan").val(dob_age(obj.DOB));
    $("#reg_date").val(obj.reg_date);

    ////jqGridAddNotesNursActPlanTreatment
	urlParam_AddNotesNursActPlanTreatment.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanTreatment.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanTreatment.filterVal[2] = 'NURSACTPLAN_TREATMENT';

    ////jqGridAddNotesNursActPlanObservation
	urlParam_AddNotesNursActPlanObservation.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanObservation.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanObservation.filterVal[2] = 'NURSACTPLAN_OBSERVATION';

    ////jqGridAddNotesNursActPlanFeeding
	urlParam_AddNotesNursActPlanFeeding.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanFeeding.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanFeeding.filterVal[2] = 'NURSACTPLAN_FEEDING';

    ////jqGridAddNotesNursActPlanImgDiag
	urlParam_AddNotesNursActPlanImgDiag.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanImgDiag.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanImgDiag.filterVal[2] = 'NURSACTPLAN_IMGDIAG';

    ////jqGridAddNotesNursActPlanBloodTrans
	urlParam_AddNotesNursActPlanBloodTrans.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanBloodTrans.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanBloodTrans.filterVal[2] = 'NURSACTPLAN_BLOODTRANS';

    ////jqGridAddNotesNursActPlanExams
	urlParam_AddNotesNursActPlanExams.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanExams.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanExams.filterVal[2] = 'NURSACTPLAN_EXAMS';

    ////jqGridAddNotesNursActPlanProcedure
	urlParam_AddNotesNursActPlanProcedure.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursActPlanProcedure.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursActPlanProcedure.filterVal[2] = 'NURSACTPLAN_PROCEDURE';
    urlParam_AddNotesNursActPlanProcedure.filterVal[3] = obj.prodType;
}

function populate_header_getdata(){
    emptyFormdata(errorField,"#formHeader");
    
    var saveParam = {
        action: 'get_table_formHeader',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursActionPlan").val(),
        episno: $("#episno_nursActionPlan").val()
    };
    
    $.post("./nursingActionPlan/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formHeader",data.episode);
            autoinsert_rowdata("#formHeader",data.header);
            button_state_header('empty');

        }else{
            button_state_header('add');

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

function saveForm_header(callback){
    var saveParam = {
        action: 'save_table_header',
        oper: $("#cancel_header").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursActionPlan: $('#mrn_nursActionPlan').val(),
        episno_nursActionPlan: $('#episno_nursActionPlan').val()
    };
    
    values = $("#formHeader").serializeArray();
    
    values = values.concat(
        $('#formHeader input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formHeader input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formHeader input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formHeader select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formHeader input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingActionPlan/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function textarea_init_nursingActionPlan(){
    $('textarea#operation,textarea#diagnosis').each(function () {
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

function galGridCustomValue_nurs(elem, operation, value){
    if(operation == 'get') {
        // console.log($(elem).find("input").val());
        return $(elem).find("input").val();
    }
    else if(operation == 'set') {
        $('input',elem).val(value);
    }
}

