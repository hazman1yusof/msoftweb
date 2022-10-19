
$(document).ready(function () {

	disableForm('#registerformdata_edit');

	$("#edit_rfde").click(function(){
		button_state_rfde('edit');
		enableForm('#registerformdata_edit');
		rdonly('#registerformdata_edit');
		// dialog_mrn_edit.on();
		dialog_race_edit.on();
		dialog_financeclass_edit.on();
		dialog_payer_edit.on();
		dialog_billtype_edit.on();
		dialog_doctor_edit.on();
		
	});

	$("#save_rfde").click(function(){
		disableForm('#registerformdata_edit');
		saveForm_edit(function(){
			$("#cancel_rfde").click();
		});

	});

	$("#cancel_rfde").click(function(){
		disableForm('#registerformdata_edit');
		button_state_rfde('init');
		// dialog_mrn_edit.off();
		dialog_race_edit.off();
		dialog_financeclass_edit.off();
		dialog_payer_edit.off();
		dialog_billtype_edit.off();
		dialog_doctor_edit.off();

	});

	var dialog_mrn_edit = new ordialog(
		'mrn_edit', ['hisdb.pat_mast AS pt','hisdb.racecode AS rc'], "#registerform_edit input[name='mrn_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'MRN', name: 'pt_MRN', width: 50, classes: 'pointer', formatter: padzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'pt_Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'Newic', name: 'pt_Newic', width: 100, classes: 'pointer', canSearch: true},
				{	label: 'DOB', name: 'pt_DOB', width: 50, classes: 'pointer', canSearch: true},
				{	label: 'CardID', name: 'pt_idnumber', width: 50, classes: 'pointer', canSearch: true},
				{	label: 'Oldic', name: 'pt_Oldic', width: 200, classes: 'pointer',hidden:true},
				{	label: 'RaceCode', name: 'pt_RaceCode', width: 200, classes: 'pointer',hidden:true},
				{	label: 'Description', name: 'rc_description', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam: {
				fixPost:"true",
				table_id : "none_",
				filterCol : ['pt.compcode'],
				filterVal : ['9A'],
				join_type : ['LEFT JOIN'],
				join_onCol : ['pt.RaceCode'],
				join_onVal : ['rc.code'],
				join_filterCol : [['pt.compcode on = '],[]],
				join_filterVal : [['rc.compcode'],[]],
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn_edit.gridname);
				$("#registerform_edit input[name='MRN_edit']").val(data['pt_MRN']);
				$("#registerform_edit input[name='patname_edit']").val(data['pt_Name']);
				$("#registerform_edit input[name='Newic_edit']").val(data['pt_Newic']);
				$("#registerform_edit input[name='DOB_edit']").val(data['pt_DOB']);
				$("#registerform_edit input[name='Oldic_edit']").val(data['pt_Oldic']);
				$("#registerform_edit input[name='idnumber_edit']").val(data['pt_idnumber']);
				$("#registerform_edit input[name='race_edit']").val(data['pt_RaceCode']);
				$("#registerform_edit input[name='description_race_edit']").val(data['rc_description']);
				$(dialog_mrn_edit.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select MRN",
			open: function () {
				dialog_mrn_edit.urlParam.fixPost="true";
				dialog_mrn_edit.urlParam.table_id = "none_";
				dialog_mrn_edit.urlParam.filterCol = ['pt.compcode'];
				dialog_mrn_edit.urlParam.filterVal = ['9A'];
				dialog_mrn_edit.urlParam.join_type = ['LEFT JOIN'];
				dialog_mrn_edit.urlParam.join_onCol = ['pt.RaceCode'];
				dialog_mrn_edit.urlParam.join_onVal = ['rc.code'];
				dialog_mrn_edit.urlParam.join_filterCol = [['pt.compcode on ='],[]];
				dialog_mrn_edit.urlParam.join_filterVal = [['rc.compcode'],[]];
			},
		}, 'none','dropdown','tab'
	);
	dialog_mrn_edit.makedialog(false);

	var dialog_race_edit = new ordialog(
		'race_edit', 'hisdb.racecode', "#registerform_edit input[name='race_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'Race', name: 'code', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']

			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_race_edit.gridname);
				$("#registerform_edit input[name='description_race_edit']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_race_edit.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Race",
			open: function () {
				dialog_race_edit.urlParam.filterCol = ['compcode'];
				dialog_race_edit.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
	);
	dialog_race_edit.makedialog(false);

    var dialog_financeclass_edit = new ordialog(
		'financeclass_edit', 'debtor.debtortype', "#registerform_edit input[name='financeclass_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'Debtor', name: 'debtortycode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']
				
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_financeclass_edit.gridname);
				if(data['debtortycode'] == "PT"){
					$("#payername_edit").val($('#patname_edit').val());
					$("#payer_edit").val($('#mrn_edit').val());
					$("#registerform_edit input[name='fName_edit']").val(data['description']);
				}else{
					let data = selrowData('#' + dialog_financeclass_edit.gridname);
					$("#registerform_edit input[name='fName_edit']").val(data['description']);
				}
				$(dialog_financeclass_edit.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Financial Class",
			open: function () {
				dialog_financeclass_edit.urlParam.filterCol = ['compcode'];
				dialog_financeclass_edit.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
	);
	dialog_financeclass_edit.makedialog(false);

	var dialog_payer_edit = new ordialog(
		'payer_edit', 'debtor.debtormast', "#registerform_edit input[name='payer_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'Debtor Code', name: 'debtorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Debtor Name', name: 'name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'debtortype', name: 'debtortype', width: 200, classes: 'pointer',hidden:true},
				{	label: 'recstatus', name: 'recstatus', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam:{
				
				filterCol : ['debtortype','compcode'],
				filterVal : ['PT','9A']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_payer_edit.gridname);
				$("#registerform_edit input[name='payername_edit']").val(data['name']);
				$("#registerform_edit input[name='paytype_edit']").val(data['debtortype']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_payer_edit.textfield).parent().next().text(" ");

				if(data['recstatus'] == 'suspend'){
					var dialog = bootbox.dialog({
					    message: '<h2 class="text-center">Selected Payer are suspended</h2>',
					    className: 'alertmodal'
					});
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Payer",
			open: function () {
				var financeclass = $('#financeclass_edit').val();
				if( financeclass == 'PT'){
					dialog_payer_edit.urlParam.filterCol = ['debtortype','compcode'];
					dialog_payer_edit.urlParam.filterVal = ['PT','9A'];
				}else if( financeclass == 'CORP'){
					dialog_payer_edit.urlParam.filterCol = ['debtortype','compcode'];
					dialog_payer_edit.urlParam.filterVal = ['CORP','9A'];
				}else{
					dialog_payer_edit.urlParam.filterCol = ['compcode'];
					dialog_payer_edit.urlParam.filterVal = ['9A'];
				}
			},
		}, 'none','radio','tab'
	);
	dialog_payer_edit.makedialog(false);

	var dialog_billtype_edit = new ordialog(
		'billtype_edit', 'hisdb.billtymst', "#registerform_edit input[name='billtype_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'Bill Type', name: 'billtype', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam:{

				filterCol : ['compcode','opprice'],
				filterVal : ['9A','1']
				
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_billtype_edit.gridname);
				$("#registerform_edit input[name='description_bt_edit']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_billtype_edit.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Bill Type",
			open: function () {
				dialog_billtype_edit.urlParam.filterCol = ['compcode','opprice'];
				dialog_billtype_edit.urlParam.filterVal = ['9A','1'];
			},
		},'none','radio','tab'
	);
	dialog_billtype_edit.makedialog(false);

	var dialog_doctor_edit = new ordialog(
		'doctor_edit', 'hisdb.doctor', "#registerform_edit input[name='doctor_edit']", errorField_rfde,
		{
			colModel: [
				{	label: 'Doctor Code', name: 'doctorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Name', name: 'doctorname', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']
				
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_doctor_edit.gridname);
				$("#registerform_edit input[name='docname_edit']").val(data['doctorname']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_doctor_edit.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Doctor",
			open: function () {
				dialog_doctor_edit.urlParam.filterCol = ['compcode'];
				dialog_doctor_edit.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
	);
	dialog_doctor_edit.makedialog(false);

});


var errorField_rfde = [];
conf_rfde = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_rfde.length > 0) {
				return {
					element: $(errorField_rfde[0]),
					message: ''
				}
			}
		},
	};

button_state_rfde('empty');
function button_state_rfde(state){

	switch(state){
		case 'empty':
			$("#toggle_rfde").removeAttr('data-toggle');
			$("#edit_rfde,#save_rfde,#cancel_rfde").attr('disabled',true);
			break;
		case 'init':
			$("#toggle_rfde").attr('data-toggle','collapse');
			$("#edit_rfde").attr('disabled',false);
			$('#save_rfde,#cancel_rfde').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_rfde").attr('data-toggle','collapse');
			$("#save_rfde,#cancel_rfde").attr('disabled',false);
			$('#edit_rfde').attr('disabled',true);
			break;
	}
	if(!moment(gldatepicker_date).isSame(moment(), 'day')){
		$("#edit_rfde,#save_rfde,#cancel_rfde").attr('disabled',true);
	}
}

function populate_registerformdata_edit(obj){

	//panel header
	$('#name_show').text(obj.a_pat_name);
	$('#mrn_show').text(("0000000" + obj.a_mrn).slice(-7));
	$('#sex_show').text(obj.sex);
	$('#dob_show').text(dob_chg(obj.dob));
	$('#age_show').text(obj.age+ ' (YRS)');
	$('#race_show').text(obj.race);
	$('#religion_show').text(if_none(obj.religion));
	$('#occupation_show').text(if_none(obj.occupation));
	$('#citizenship_show').text(obj.citizen);
	$('#area_show').text(obj.area);
	
	//the form edit
	$("#apptbookidno_edit").val(obj.a_idno);
	$("#mrn_edit").val(obj.a_mrn);
	$("#patname_edit").val(obj.a_pat_name);
	$("#idtype_edit").val(obj.idtype);
	$("#Newic_edit").val(obj.newic);
	$("#Oldic_edit").val(obj.oldic);
	$("#DOB_edit").val(obj.dob);
	$("#idnumber_edit").val(obj.idnumber);
	$("#sex_edit").val(obj.sex);
	$("#race_edit").val(obj.racecode);
	$("#description_race_edit").val(obj.race);
	$("#financeclass_edit").val(obj.pay_type);
	$("#fName_edit").val(obj.pay_type_desc);
	// $("#payer_edit").val(obj.a_mrn);
	// $("#payername_edit").val(obj.a_mrn);
	$("#episno_edit").val(obj.a_Episno);
	$("#billtype_edit").val(obj.billtype);
	$("#description_bt_edit").val(obj.billtype_desc);
	$("#doctor_edit").val(obj.admdoctor);
	$("#docname_edit").val(obj.admdoctor_desc);

	button_state_rfde('edit');

}

function empty_registerformdata_edit(){

	$('#name_show').text('');
	$('#newic_show').text('');
	$('#sex_show').text('');
	$('#age_show').text('');
	$('#race_show').text('');	
	$("#cancel_rfde").click();
	button_state_rfde('empty')

	disableForm('#registerformdata_edit');
	emptyFormdata(errorField_rfde,'#registerformdata_edit')
}

function saveForm_edit(callback){
	var saveParam={
        action:'save_table_default',oper:'edit'
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	sex_edit : $('#sex_edit').val(),
    	idtype_edit : $('#idtype_edit').val()

    };

    $.post( "/emergency/form?"+$.param(saveParam), $("#registerformdata_edit").serialize()+'&'+$.param(postobj) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

