
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;
var dateParam_docnote,doctornote_docnote,curr_obj;

///////////////////////////////////parameter for jqGridAddNotes url///////////////////////////////////
var urlParam_AddNotes = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'hisdb.pathealthadd',
	table_id: 'idno',
	filterCol: ['mrn','episno'],
	filterVal: ['',''],
}

$(document).ready(function (){
	
	// $('.menu .item').tab();
	
	var fdl = new faster_detail_load();
	
	disableForm('#formDoctorNote',['toggle_type']);
	
	$("button.refreshbtn_doctornote").click(function (){
		populate_currDoctorNote(selrowData('#jqGrid'));
	});
	
	$("#new_doctorNote").click(function (){
		// $('#docnote_date_tbl tbody tr').removeClass('active');
		$('#cancel_doctorNote').data('oper','add');
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// dialog_mrn_edit.on();
	});
	
	$("#edit_doctorNote").click(function (){
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// dialog_mrn_edit.on();
	});
	
	$("#save_doctorNote").click(function (){
		if($('#formDoctorNote').isValid({requiredFields: ''}, conf, true)){
			saveForm_doctorNote(function (data){
				emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
				disableForm('#formDoctorNote',['toggle_type']);
				docnote_date_tbl.ajax.url("./ptcare_doctornote/table?"+$.param(dateParam_docnote)).load(function (){
					
				});
			});
		}else{
			enableForm('#formDoctorNote');
			rdonly('#formDoctorNote');
		}
	});
	
	$("#cancel_doctorNote").click(function (){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formDoctorNote',['toggle_type']);
		button_state_doctorNote($(this).data('oper'));
		// dialog_mrn_edit.off();
		// $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
	});
	
	////////////////////////////////////////////otbook starts////////////////////////////////////////////
	disableForm('#formOTBook');
	
	$("#new_otbook").click(function (){
		get_default_otbook();
		$('#cancel_otbook').data('oper','add');
		button_state_otbook('wait');
		enableForm('#formOTBook');
		rdonly('#formOTBook');
		emptyFormdata_div("#formOTBook",['#mrn_doctorNote','#episno_doctorNote','#ot_doctorname']);
	});
	
	$("#edit_otbook").click(function (){
		button_state_otbook('wait');
		enableForm('#formOTBook');
		rdonly('#formOTBook');
	});
	
	$("#save_otbook").click(function (){
		disableForm('#formOTBook');
		if($('#formOTBook').isValid({requiredFields: ''}, conf, true)){
			saveForm_otbook(function (data){
				// emptyFormdata_div("#formOTBook",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#formOTBook');
				$('#cancel_otbook').data('oper','edit');
				$("#cancel_otbook").click();
				populate_otbook_getdata();
			});
		}else{
			enableForm('#formOTBook');
			rdonly('#formOTBook');
		}
	});
	
	$("#cancel_otbook").click(function (){
		// emptyFormdata_div("#formOTBook",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formOTBook');
		button_state_otbook($(this).data('oper'));
	});
	//////////////////////////////////////////////otbook ends//////////////////////////////////////////////
	
	///////////////////////////////////////////radClinic starts///////////////////////////////////////////
	disableForm('#formRadClinic');
	
	$("#new_radClinic").click(function (){
		get_default_radClinic();
		$('#cancel_radClinic').data('oper','add');
		button_state_radClinic('wait');
		enableForm('#formRadClinic');
		rdonly('#formRadClinic');
		emptyFormdata_div("#formRadClinic",['#mrn_doctorNote','#episno_doctorNote']);
		// $('#clinicaldata').prop('disabled',true);
	});
	
	$("#edit_radClinic").click(function (){
		button_state_radClinic('wait');
		enableForm('#formRadClinic');
		rdonly('#formRadClinic');
		// $('#clinicaldata').prop('disabled',true);
	});
	
	$("#save_radClinic").click(function (){
		disableForm('#formRadClinic');
		if($('#formRadClinic').isValid({requiredFields: ''}, conf, true)){
			saveForm_radClinic(function (data){
				// emptyFormdata_div("#formRadClinic",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#formRadClinic');
				$('#cancel_radClinic').data('oper','edit');
				$("#cancel_radClinic").click();
				populate_radClinic_getdata();
			});
		}else{
			enableForm('#formRadClinic');
			rdonly('#formRadClinic');
		}
	});
	
	$("#cancel_radClinic").click(function (){
		// emptyFormdata_div("#formRadClinic",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formRadClinic');
		button_state_radClinic($(this).data('oper'));
	});
	////////////////////////////////////////////radClinic ends////////////////////////////////////////////

	//////////////////////////////////////////////mri starts//////////////////////////////////////////////
	disableForm('#formMRI');
	
	$("#new_mri").click(function (){
		get_default_mri();
		$('#cancel_mri').data('oper','add');
		button_state_mri('wait');
		enableForm('#formMRI');
		rdonly('#formMRI');
		emptyFormdata_div("#formMRI",['#mrn_doctorNote','#episno_doctorNote']);
	});
	
	$("#edit_mri").click(function (){
		button_state_mri('wait');
		enableForm('#formMRI');
		rdonly('#formMRI');
	});
	
	$("#save_mri").click(function (){
		disableForm('#formMRI');
		if($('#formMRI').isValid({requiredFields: ''}, conf, true)){
			saveForm_mri(function (data){
				// emptyFormdata_div("#formMRI",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#formMRI');
				$('#cancel_mri').data('oper','edit');
				$("#cancel_mri").click();
				populate_mri_getdata();
			});
		}else{
			enableForm('#formMRI');
			rdonly('#formMRI');
		}
	});
	
	$("#cancel_mri").click(function (){
		// emptyFormdata_div("#formMRI",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formMRI');
		button_state_mri($(this).data('oper'));
	});
	
	$("#accept_mri").click(function (){
		radiographer_accept();
	});
	///////////////////////////////////////////////mri ends///////////////////////////////////////////////
	
	////////////////////////////////////////////physio starts////////////////////////////////////////////
	disableForm('#formPhysio');
	
	$("#new_physio").click(function (){
		$('#cancel_physio').data('oper','add');
		button_state_physio('wait');
		enableForm('#formPhysio');
		rdonly('#formPhysio');
		emptyFormdata_div("#formPhysio",['#mrn_doctorNote','#episno_doctorNote','#phy_doctorname']);
	});
	
	$("#edit_physio").click(function (){
		button_state_physio('wait');
		enableForm('#formPhysio');
		rdonly('#formPhysio');
	});
	
	$("#save_physio").click(function (){
		disableForm('#formPhysio');
		if($('#formPhysio').isValid({requiredFields: ''}, conf, true)){
			saveForm_physio(function (data){
				// emptyFormdata_div("#formPhysio",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#formPhysio');
				$('#cancel_physio').data('oper','edit');
				$("#cancel_physio").click();
				populate_physio_getdata();
			});
		}else{
			enableForm('#formPhysio');
			rdonly('#formPhysio');
		}
	});
	
	$("#cancel_physio").click(function (){
		// emptyFormdata_div("#formPhysio",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formPhysio');
		button_state_physio($(this).data('oper'));
	});
	/////////////////////////////////////////////physio ends/////////////////////////////////////////////
	
	///////////////////////////////////////////dressing starts///////////////////////////////////////////
	disableForm('#formDressing');
	
	$("#new_dressing").click(function (){
		$('#cancel_dressing').data('oper','add');
		button_state_dressing('wait');
		enableForm('#formDressing');
		rdonly('#formDressing');
		emptyFormdata_div("#formDressing",['#mrn_doctorNote','#episno_doctorNote','#dressing_patientname','#patientnric','#dressing_doctorname']);
	});
	
	$("#edit_dressing").click(function (){
		button_state_dressing('wait');
		enableForm('#formDressing');
		rdonly('#formDressing');
	});
	
	$("#save_dressing").click(function (){
		disableForm('#formDressing');
		if($('#formDressing').isValid({requiredFields: ''}, conf, true)){
			saveForm_dressing(function (data){
				// emptyFormdata_div("#formDressing",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#formDressing');
				$('#cancel_dressing').data('oper','edit');
				$("#cancel_dressing").click();
				populate_dressing_getdata();
			});
		}else{
			enableForm('#formDressing');
			rdonly('#formDressing');
		}
	});
	
	$("#cancel_dressing").click(function (){
		// emptyFormdata_div("#formDressing",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formDressing');
		button_state_dressing($(this).data('oper'));
	});
	////////////////////////////////////////////dressing ends////////////////////////////////////////////
	
	/////////////////////////////////////////print button starts/////////////////////////////////////////
	$("#otbook_chart").click(function (){
		window.open('./doctornote/otbook_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
	});
	
	$("#radClinic_chart").click(function (){
		window.open('./doctornote/radClinic_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&age='+$("#age_doctorNote").val(), '_blank');
	});
	
	$("#mri_chart").click(function (){
		window.open('./doctornote/mri_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
	});
	
	$("#physio_chart").click(function (){
		window.open('./doctornote/physio_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
	});
	
	$("#dressing_chart").click(function (){
		window.open('./doctornote/dressing_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
	});
	//////////////////////////////////////////print button ends//////////////////////////////////////////
	
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
	
	// bmi calculator
	$('form#formDoctorNote #height').blur(function (event){
		getBMI();
	});
	
	$('form#formDoctorNote #weight').blur(function (event){
		getBMI();
	});
	// bmi calculator ends
	
	// change diagnosis value
	$('#icdcode').change(function (){
		$('#diagfinal').val($('#icdcode').val());
	});
	
	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgrid = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotes///////////////////////////////////////////
	$("#jqGridAddNotes").jqGrid({
		datatype: "local",
		editurl: "/doctornote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'Note', name: 'additionalnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		scroll: true,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotes",
		loadComplete: function (){
			if(addmore_jqgrid.more == true){$('#jqGridAddNotes_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotes_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_add = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("input[name='additionalnote']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotes_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgrid.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotes').jqGrid('getRowData', rowid);
			
			let editurl = "/doctornote/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_doctorNote_past').val(),
					mrn: $('#mrn_doctorNote_past').val(),
					action: 'doctornote_save',
				});
			$("#jqGridAddNotes").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotes/////////////////////////////////////////
	$("#jqGridAddNotes").inlineNav('#jqGridPagerAddNotes', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerAddNotes", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function (){
	// 		selRowId = $("#jqGridAddNotes").jqGrid('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			alert('Please select row');
	// 		}else{
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if(result == true){
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'doctornote_save',
	// 					idno: selrowData('#jqGridAddNotes').idno,
	// 				}
					
	// 				$.post("/doctornote/form?"+$.param(param), {oper:'del'}, function (data){
						
	// 				}).fail(function (data){
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data){
	// 					refreshGrid("#jqGridAddNotes", urlParam_AddNotes);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotes", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotes", urlParam_AddNotes);
		},
	});
	///////////////////////////////////////////////end grid///////////////////////////////////////////////
	
	//////////////////////////////////////////body diagram starts//////////////////////////////////////////
	$('a.ui.card.bodydia_doctornote').click(function (){
		let mrn = $('#mrn_doctorNote').val();
		let type = $(this).data('type');
		let istablet = $(window).width() <= 1024;
		
		if(mrn.trim() == '' || type.trim() == ''){
			alert('Please choose Patient First');
		}else if($('#save_doctorNote').prop('disabled')){
			alert('Edit this patient first');
		}else{
			if(istablet){
				let filename = type+'_'+mrn+'_.pdf';
				let url = $('#urltodiagram').val() + filename;
				var win = window.open(url, '_blank');
			}else{
				var win = window.open('http://localhost:8080/foxitweb/public/pdf?mrn='+mrn+'&episno=&type='+type+'&from=doctornote', '_blank');
			}
			
			if(win){
				win.focus();
			}else{
				alert('Please allow popups for this website');
			}
		}
	});
	///////////////////////////////////////////body diagram ends///////////////////////////////////////////
	
	$('#doctor_requestFor .top.menu .item').tab({'onVisible': function (){
		let tab = $(this).data('tab');
		// console.log(tab);
		
		switch(tab){
			case 'otbook':
				populate_otbook_getdata();
				// textarea_init_otbook();
				break;
			case 'rad':
				break;
			case 'physio':
				populate_physio_getdata();
				// textarea_init_physio();
				break;
			case 'dressing':
				populate_dressing_getdata();
				// textarea_init_dressing();
				break;
		}
	}});
	
	$('#doctor_radiology .top.menu .item').tab({'onVisible': function (){
		let tab = $(this).data('tab');
		// console.log(tab);
		
		switch(tab){
			case 'radClinic':
				populate_radClinic_getdata();
				// textarea_init_radClinic();
				break;
			case 'mri':
				populate_mri_getdata();
				// textarea_init_mri();
				break;
		}
	}});
	
});

// bmi calculator
function getBMI(){
	var height = parseFloat($("form#formDoctorNote #height").val());
	var weight = parseFloat($("form#formDoctorNote #weight").val());
	
	var myBMI = (weight / height / height) * 10000;
	
	var bmi = myBMI.toFixed(2);
	
	if (isNaN(bmi)) bmi = 0;
	
	$('form#formDoctorNote #bmi').val((bmi));
}

// to disable all input fields except additional note
function disableOtherFields(){
	// var fieldsNotToBeDisabled = new Array("additionalnote");
	
	// $("form input").filter(function (index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);
	
	// $("form textarea").filter(function (index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);
	
	$('#remarks, #clinicnote, #pmh, #drugh, #allergyh, #socialh, #fmh, #followuptime, #followupdate, #examination, #diagfinal, #icdcode, #plan_, #height, #weight, #bp_sys1, #bp_dias2, #pulse, #temperature, #respiration').prop('disabled',true);
}

// to enable fields when choose current
function enableFields(){
	$('#remarks, #clinicnote, #pmh, #drugh, #allergyh, #socialh, #fmh, #followuptime, #followupdate, #examination, #diagfinal, #icdcode, #plan_, #height, #weight, #bp_sys1, #bp_dias2, #pulse, #temperature, #respiration').prop('disabled',false);
}

var errorField = [];
conf = {
	modules: 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form){
		if(errorField.length > 0){
			return{
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

var dialog_icd = new ordialog(
	'icdcode',['hisdb.diagtab AS dt','sysdb.sysparam AS sp'],"#formDoctorNote input[name='icdcode']",errorField,
	{
		colModel: [
			{ label: 'ICD Code', name: 'icdcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
		],
		urlParam: {
			url: "ptcare_doctornote/table",
			filterCol: ['sp.compcode'],
			filterVal: ['session.compcode'],
		},
		ondblClickRow: function (){
			let data = selrowData('#'+dialog_icd.gridname);
			$("#diagfinal").val(data['icdcode'] + " " + data['description']);
			$('#plan_').focus();
			// document.getElementById("diagfinal").value = document.getElementById("icdcode").value; // copy data to Diagnosis
		},
		gridComplete: function (obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				// $('#optax').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},{
		title: "Select ICD",
		open: function (){
			dialog_icd.urlParam.url = "ptcare_doctornote/table";
			dialog_icd.urlParam.action = "dialog_icd";
			dialog_icd.urlParam.table_name = ['hisdb.diagtab AS dt','sysdb.sysparam AS sp'];
			dialog_icd.urlParam.join_type = ['LEFT JOIN'];
			dialog_icd.urlParam.join_onCol = ['dt.type'];
			dialog_icd.urlParam.join_onVal = ['sp.pvalue1'];
			dialog_icd.urlParam.fixPost = "true";
			dialog_icd.urlParam.table_id = "none_";
			dialog_icd.urlParam.filterCol = ['sp.compcode','sp.source', 'sp.trantype'];
			dialog_icd.urlParam.filterVal = ['session.compcode', 'MR', 'ICD' ];
		}
	},'urlParam','radio','tab'
);
dialog_icd.makedialog();

button_state_doctorNote('empty');
function button_state_doctorNote(state){
	empty_transaction('add');
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_doctorNote').data('oper','add');
			$('#new_doctorNote,#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_doctorNote').data('oper','add');
			$("#new_doctorNote,#current,#past").attr('disabled',false);
			$('#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_doctorNote').data('oper','edit');
			$("#edit_doctorNote,#new_doctorNote").attr('disabled',false);
			$('#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
			break;
		case 'wait':
			hide_tran_button(false);
			dialog_icd.on();
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_doctorNote,#cancel_doctorNote").attr('disabled',false);
			$('#edit_doctorNote,#new_doctorNote').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#new_doctorNote,#edit_doctorNote,#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
			break;
		// case 'docnote':
		// 	$("#toggle_doctorNote").attr('data-toggle','collapse');
		// 	$('#cancel_doctorNote').data('oper','add');
		// 	$("#new_doctorNote").attr('disabled',false);
		// 	$('#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
		// 	break;
	}
}

button_state_otbook('empty');
function button_state_otbook(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_otbook').data('oper','add');
			$('#new_otbook,#save_otbook,#cancel_otbook,#edit_otbook').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_otbook').data('oper','add');
			$("#new_otbook").attr('disabled',false);
			$('#save_otbook,#cancel_otbook,#edit_otbook').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_otbook').data('oper','edit');
			$("#edit_otbook").attr('disabled',false);
			$('#save_otbook,#cancel_otbook,#new_otbook').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_otbook,#cancel_otbook").attr('disabled',false);
			$('#edit_otbook,#new_otbook').attr('disabled',true);
			break;
	}
}

button_state_radClinic('empty');
function button_state_radClinic(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_radClinic').data('oper','add');
			$('#new_radClinic,#save_radClinic,#cancel_radClinic,#edit_radClinic').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_radClinic').data('oper','add');
			$("#new_radClinic").attr('disabled',false);
			$('#save_radClinic,#cancel_radClinic,#edit_radClinic').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_radClinic').data('oper','edit');
			$("#edit_radClinic").attr('disabled',false);
			$('#save_radClinic,#cancel_radClinic,#new_radClinic').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_radClinic,#cancel_radClinic").attr('disabled',false);
			$('#edit_radClinic,#new_radClinic').attr('disabled',true);
			break;
	}
}

button_state_mri('empty');
function button_state_mri(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_mri').data('oper','add');
			$('#new_mri,#save_mri,#cancel_mri,#edit_mri,#accept_mri').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_mri').data('oper','add');
			$("#new_mri").attr('disabled',false);
			$('#save_mri,#cancel_mri,#edit_mri,#accept_mri').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_mri').data('oper','edit');
			$("#edit_mri,#accept_mri").attr('disabled',false);
			$('#save_mri,#cancel_mri,#new_mri').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_mri,#cancel_mri").attr('disabled',false);
			$('#edit_mri,#new_mri,#accept_mri').attr('disabled',true);
			break;
	}
}

button_state_physio('empty');
function button_state_physio(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_physio').data('oper','add');
			$('#new_physio,#save_physio,#cancel_physio,#edit_physio').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_physio').data('oper','add');
			$("#new_physio").attr('disabled',false);
			$('#save_physio,#cancel_physio,#edit_physio').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_physio').data('oper','edit');
			$("#edit_physio").attr('disabled',false);
			$('#save_physio,#cancel_physio,#new_physio').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_physio,#cancel_physio").attr('disabled',false);
			$('#edit_physio,#new_physio').attr('disabled',true);
			break;
	}
}

button_state_dressing('empty');
function button_state_dressing(state){
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_dressing').data('oper','add');
			$('#new_dressing,#save_dressing,#cancel_dressing,#edit_dressing').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_dressing').data('oper','add');
			$("#new_dressing").attr('disabled',false);
			$('#save_dressing,#cancel_dressing,#edit_dressing').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_dressing').data('oper','edit');
			$("#edit_dressing").attr('disabled',false);
			$('#save_dressing,#cancel_dressing,#new_dressing').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$("#save_dressing,#cancel_dressing").attr('disabled',false);
			$('#edit_dressing,#new_dressing').attr('disabled',true);
			break;
	}
}

function empty_currDoctorNote(){
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
	button_state_doctorNote('empty');
	
	// panel header
	$('#name_show_doctorNote').text('');
	$('#mrn_show_doctorNote').text('');
	$('#sex_show_doctorNote').text('');
	$('#dob_show_doctorNote').text('');
	$('#age_show_doctorNote').text('');
	$('#race_show_doctorNote').text('');
	$('#religion_show_doctorNote').text('');
	$('#occupation_show_doctorNote').text('');
	$('#citizenship_show_doctorNote').text('');
	$('#area_show_doctorNote').text('');
	
	// formDoctorNote
	$('#mrn_doctorNote').val('');
	$("#episno_doctorNote").val('');
	
	docnote_date_tbl.clear().draw();
}

//screen current patient//
function populate_currDoctorNote(obj){
	curr_obj = obj;
	
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
	
	// panel header
	$('#name_show_doctorNote').text(obj.Name);
	$('#mrn_show_doctorNote').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_doctorNote').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_doctorNote').text(dob_chg(obj.DOB));
	$('#age_show_doctorNote').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_doctorNote').text(if_none(obj.RaceCode).toUpperCase());
	$('#religion_show_doctorNote').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_doctorNote').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_doctorNote').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_doctorNote').text(if_none(obj.AreaCode).toUpperCase());
	
	// formDoctorNote
	$('#mrn_doctorNote').val(obj.MRN);
	$("#episno_doctorNote").val(obj.Episno);
	$("#age_doctorNote").val(dob_age(obj.DOB));
	$('#ptname_doctorNote').val(obj.Name);
	// $('#preg_doctorNote').val(obj.pregnant);
	$('#ic_doctorNote').val(obj.Newic);
	$('#doctorname_doctorNote').val(obj.doctorname);
	
	on_toggling_curr_past(obj);
	
	urlParam_AddNotes.filterVal[0] = obj.MRN;
	urlParam_AddNotes.filterVal[1] = obj.Episno;
	
	doctornote_docnote = {
		action: 'get_table_doctornote_div',
		mrn: obj.MRN,
		episno: obj.Episno,
		recorddate: ''
	};
	
	$("#tab_doctornote").collapse('hide');
	button_state_doctorNote('disableAll');
}

function populate_otbook_getdata(){
	emptyFormdata(errorField,"#formOTBook",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_otbook',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_otbook").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val()
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.pat_otbook)){
			autoinsert_rowdata("#formOTBook",data.pat_otbook);
			autoinsert_rowdata("#formOTBook",data.nurshandover);
			autoinsert_rowdata("#formOTBook",data.nurshistory);
			
			if(!emptyobj_(data.pat_otbook.ot_doctorname)){
				$("#ot_doctorname").val(data.pat_otbook.ot_doctorname);
			}else{
				$("#ot_doctorname").val($('#doctorname_doctorNote').val());
			}
			
			button_state_otbook('edit');
		}else{
			autoinsert_rowdata("#formOTBook",data.nurshandover);
			autoinsert_rowdata("#formOTBook",data.nurshistory);
			// by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
			$("#ot_doctorname").val($('#doctorname_doctorNote').val());
			
			button_state_otbook('add');
		}
		
		// textarea_init_otbook();
		toggle_reqtype();
	});
}

function get_default_otbook(){
	emptyFormdata(errorField,"#formOTBook",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_otbook',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_otbook").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val()
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.pat_otbook)){
			autoinsert_rowdata("#formOTBook",data.pat_otbook);
			autoinsert_rowdata("#formOTBook",data.nurshandover);
			autoinsert_rowdata("#formOTBook",data.nurshistory);
			
			if(!emptyobj_(data.pat_otbook.ot_doctorname)){
				$("#ot_doctorname").val(data.pat_otbook.ot_doctorname);
			}else{
				$("#ot_doctorname").val($('#doctorname_doctorNote').val());
			}
		}else{
			autoinsert_rowdata("#formOTBook",data.nurshandover);
			autoinsert_rowdata("#formOTBook",data.nurshistory);
			// by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
			$("#ot_doctorname").val($('#doctorname_doctorNote').val());
		}
		
		// textarea_init_otbook();
		toggle_reqtype();
	});
}

function populate_radClinic_getdata(){
	emptyFormdata(errorField,"#formRadClinic",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_radClinic',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_radClinic").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val(),
		recorddate: $("#recorddate_doctorNote").val(),
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.pat_radiology)){
			autoinsert_rowdata("#formRadClinic",data.pat_radiology);
			
			button_state_radClinic('edit');
		}else{
			button_state_radClinic('add');
		}
		
		// if(!emptyobj_(data.rad_weight))$("#rad_weight").val(data.rad_weight);
		// $("#rad_pregnant").val($('#preg_doctorNote').val());
		if(!emptyobj_(data.rad_allergy))$("#rad_allergy").val(data.rad_allergy);
		// $("#radClinic_doctorname").val($('#doctorname_doctorNote').val());
		
		pregnant = document.getElementById("pregnant");
		not_pregnant = document.getElementById("not_pregnant");
		if(data.pregnant == 1){
			pregnant.checked = true;
		}else{
			not_pregnant.checked = true;
		}
		
		// textarea_init_radClinic();
	});
}

function get_default_radClinic(){
	emptyFormdata(errorField,"#formRadClinic",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_radClinic',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_radClinic").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val(),
		recorddate: $("#recorddate_doctorNote").val(),
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formRadClinic",data.pat_radiology);
		}else{
			
		}
		
		// if(!emptyobj_(data.rad_weight))$("#rad_weight").val(data.rad_weight);
		// $("#rad_pregnant").val($('#preg_doctorNote').val());
		if(!emptyobj_(data.rad_allergy))$("#rad_allergy").val(data.rad_allergy);
		// $("#radClinic_doctorname").val($('#doctorname_doctorNote').val());
		
		pregnant = document.getElementById("pregnant");
		not_pregnant = document.getElementById("not_pregnant");
		if(data.pregnant == 1){
			pregnant.checked = true;
		}else{
			not_pregnant.checked = true;
		}
		
		// textarea_init_radClinic();
	});
}

function populate_mri_getdata(){
	emptyFormdata(errorField,"#formMRI",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_mri',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_mri").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val(),
		// recorddate: $("#recorddate_doctorNote").val(),
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.pat_mri)){
			autoinsert_rowdata("#formMRI",data.pat_mri);
			
			if(!emptyobj_(data.pat_mri.mri_doctorname)){
				$("#mri_doctorname").val(data.pat_mri.mri_doctorname);
			}else{
				$("#mri_doctorname").val($('#doctorname_doctorNote').val());
			}
			
			// if(!emptyobj_(data.pat_mri.radiographer)){
			// 	button_state_mri('empty');
			// }else{
				button_state_mri('edit');
			// }
		}else{
			// by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
			$("#mri_doctorname").val($('#doctorname_doctorNote').val());
			
			button_state_mri('add');
		}
		
		// if(!emptyobj_(data.mri_weight))$("#mri_weight").val(data.mri_weight);
		$("#mri_patientname").val($('#ptname_doctorNote').val());
		// textarea_init_mri();
	});
}

function get_default_mri(){
	emptyFormdata(errorField,"#formMRI",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_mri',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_mri").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val(),
		// recorddate: $("#recorddate_doctorNote").val(),
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formMRI",data.pat_mri);
			
			if(!emptyobj_(data.pat_mri.mri_doctorname)){
				$("#mri_doctorname").val(data.pat_mri.mri_doctorname);
			}else{
				$("#mri_doctorname").val($('#doctorname_doctorNote').val());
			}
		}else{
			// by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
			$("#mri_doctorname").val($('#doctorname_doctorNote').val());
		}
		
		// if(!emptyobj_(data.mri_weight))$("#mri_weight").val(data.mri_weight);
		$("#mri_patientname").val($('#ptname_doctorNote').val());
		textarea_init_mri();
	});
}

function radiographer_accept(){
	// bootbox.confirm({
	// 	message: "Are you sure you want to accept?",
	// 	buttons: { confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
	// 	callback: function (result){
	// 		if(result == true){
	// 			var saveParam = {
	// 				action: 'accept_mri',
	// 				mrn: $('#mrn_doctorNote').val(),
	// 				episno: $("#episno_doctorNote").val(),
	// 			}
				
	// 			var postobj = {
	// 				_token: $('#_token').val(),
	// 			};
				
	// 			$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj), function (data){
					
	// 			},'json').done(function (data){
	// 				callback(data);
	// 			});
	// 		}else{
				
	// 		}
	// 	}
	// });
	
	var result = confirm("Are you sure you want to accept?");
	if(result == true){
		var saveParam = {
			action: 'accept_mri',
			mrn: $('#mrn_doctorNote').val(),
			episno: $("#episno_doctorNote").val(),
		}
		
		var postobj = {
			_token: $('#_token').val(),
		};
		
		$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').done(function (data){
			// callback(data);
			button_state_mri('empty');
			get_default_mri();
		});
	}else{
		
	}
}

function populate_physio_getdata(){
	emptyFormdata(errorField,"#formPhysio",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_physio',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_physio").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val()
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formPhysio",data.pat_physio);
			
			button_state_physio('edit');
		}else{
			button_state_physio('add');
		}
		
		// $("#phy_doctorname").val($('#doctorname_doctorNote').val());
		// textarea_init_physio();
	});
}

function populate_dressing_getdata(){
	emptyFormdata(errorField,"#formDressing",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_dressing',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// idno: $("#idno_dressing").val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val()
	};
	
	$.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formDressing",data.pat_dressing);
			
			button_state_dressing('edit');
		}else{
			button_state_dressing('add');
		}
		
		$("#dressing_patientname").val($('#ptname_doctorNote').val());
		$("#patientnric").val($('#ic_doctorNote').val());
		// $("#dressing_doctorname").val($('#doctorname_doctorNote').val());
		// textarea_init_dressing();
	});
}

function populate_admhandover_getdata(){
	emptyFormdata(errorField,"#formAdmhandover",["#mrn_doctorNote","#episno_doctorNote"]);
	
	var saveParam = {
		action: 'get_table_admhandover',
	}
	
	var postobj = {
		_token: $('#_token').val(),
		mrn: $("#mrn_doctorNote").val(),
		episno: $("#episno_doctorNote").val()
	};
	
	$.get("./ptcare_admhandover/table?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formAdmhandover",data.admhandover);
			autoinsert_rowdata("#formAdmhandover",data.episode);
			autoinsert_rowdata("#formAdmhandover",data.nurshistory);
			autoinsert_rowdata("#formAdmhandover",data.pathealth);

		}else{
		}
	});
}

function on_toggling_curr_past(obj = curr_obj){
	var addnotes = document.getElementById("addnotes");
	
	if($('.pastcurr').find('[name="toggle_type"]:checked').val() == 'current'){
		dateParam_docnote = {
			action: 'get_table_date_curr',
			mrn: obj.MRN,
			episno: obj.Episno,
			date: $('#sel_date').val()
		}
		$('#primary_icd_form,#followup_form').show();
		
		addnotes.style.display = "none";
		enableFields();
		button_state_doctorNote('add'); // enable balik button
		// $("#new_doctorNote").attr('disabled',false);
		// datable_medication.clear().draw();
	}else if($('.pastcurr').find('[name="toggle_type"]:checked').val() == 'past'){
		dateParam_docnote = {
			action: 'get_table_date_past',
			mrn: obj.MRN,
		}
		$('#primary_icd_form,#followup_form').hide();
		
		addnotes.style.display = "block";
		disableOtherFields();
		button_state_doctorNote('disableAll'); // disable all buttons
		$('#jqGridPagerRefresh_addnotes').click();
	}
}

function autoinsert_rowdata_doctorNote(form,rowData){
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

function saveForm_doctorNote(callback){
	let oper = $("#cancel_doctorNote").data('oper');
	var saveParam = {
		action: 'save_table_doctornote',
		oper: oper,
	}
	
	if(oper == 'add'){
		saveParam.sel_date = $('#sel_date').val();
	}else if(oper == 'edit'){
		var row = docnote_date_tbl.row('.active').data();
		saveParam.sel_date = $('#sel_date').val();
		saveParam.recordtime = row.recordtime;
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formDoctorNote").serializeArray();
	
	values = values.concat(
		$('#formDoctorNote input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	$.post("./ptcare_doctornote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

function saveForm_otbook(callback){
	var saveParam = {
		action: 'save_otbook',
		oper: $("#cancel_otbook").data('oper'),
		mrn: $('#mrn_doctorNote').val(),
		episno: $("#episno_doctorNote").val(),
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formOTBook").serializeArray();
	
	values = values.concat(
		$('#formOTBook input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formOTBook input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formOTBook input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formOTBook select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
		name: 'op_date',
		value: $('#formOTBook input[name=op_date]').val()
	})
	values.push({
		name: 'oper_type',
		value: $('#formOTBook input[name=oper_type]').val()
	})
	values.push({
		name: 'ot_diagnosis',
		value: $('#formOTBook textarea[name=ot_diagnosis]').val()
	})
	values.push({
		name: 'ot_diagnosedby',
		value: $('#formOTBook input[name=ot_diagnosedby]').val()
	})
	values.push({
		name: 'ot_remarks',
		value: $('#formOTBook textarea[name=ot_remarks]').val()
	})
	values.push({
		name: 'ot_doctorname',
		value: $('#formOTBook input[name=ot_doctorname]').val()
	})
	values.push({
		name: 'ot_lastuser',
		value: $('#formOTBook input[name=ot_lastuser]').val()
	})
	
	$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

function saveForm_radClinic(callback){
	var saveParam = {
		action: 'save_radClinic',
		oper: $("#cancel_radClinic").data('oper'),
		mrn: $('#mrn_doctorNote').val(),
		episno: $("#episno_doctorNote").val(),
		recorddate: $("#recorddate_doctorNote").val(),
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formRadClinic").serializeArray();
	
	values = values.concat(
		$('#formRadClinic input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formRadClinic input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formRadClinic input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formRadClinic select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
		name: 'rad_weight',
		value: $('#formRadClinic input[name=rad_weight]').val()
	})
	values.push({
		name: 'rad_allergy',
		value: $('#formRadClinic textarea[name=rad_allergy]').val()
	})
	values.push({
		name: 'xray_date',
		value: $('#formRadClinic input[name=xray_date]').val()
	})
	values.push({
		name: 'xray_remark',
		value: $('#formRadClinic textarea[name=xray_remark]').val()
	})
	values.push({
		name: 'mri_date',
		value: $('#formRadClinic input[name=mri_date]').val()
	})
	values.push({
		name: 'mri_remark',
		value: $('#formRadClinic textarea[name=mri_remark]').val()
	})
	values.push({
		name: 'angio_date',
		value: $('#formRadClinic input[name=angio_date]').val()
	})
	values.push({
		name: 'angio_remark',
		value: $('#formRadClinic textarea[name=angio_remark]').val()
	})
	values.push({
		name: 'ultrasound_date',
		value: $('#formRadClinic input[name=ultrasound_date]').val()
	})
	values.push({
		name: 'ultrasound_remark',
		value: $('#formRadClinic textarea[name=ultrasound_remark]').val()
	})
	values.push({
		name: 'ct_date',
		value: $('#formRadClinic input[name=ct_date]').val()
	})
	values.push({
		name: 'ct_remark',
		value: $('#formRadClinic textarea[name=ct_remark]').val()
	})
	values.push({
		name: 'fluroscopy_date',
		value: $('#formRadClinic input[name=fluroscopy_date]').val()
	})
	values.push({
		name: 'fluroscopy_remark',
		value: $('#formRadClinic textarea[name=fluroscopy_remark]').val()
	})
	values.push({
		name: 'mammogram_date',
		value: $('#formRadClinic input[name=mammogram_date]').val()
	})
	values.push({
		name: 'mammogram_remark',
		value: $('#formRadClinic textarea[name=mammogram_remark]').val()
	})
	values.push({
		name: 'bmd_date',
		value: $('#formRadClinic input[name=bmd_date]').val()
	})
	values.push({
		name: 'bmd_remark',
		value: $('#formRadClinic textarea[name=bmd_remark]').val()
	})
	values.push({
		name: 'clinicaldata',
		value: $('#formRadClinic textarea[name=clinicaldata]').val()
	})
	values.push({
		name: 'radClinic_doctorname',
		value: $('#formRadClinic input[name=radClinic_doctorname]').val()
	})
	values.push({
		name: 'rad_note',
		value: $('#formRadClinic textarea[name=rad_note]').val()
	})
	values.push({
		name: 'radClinic_radiologist',
		value: $('#formRadClinic input[name=radClinic_radiologist]').val()
	})
	
	$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

function saveForm_mri(callback){
	var saveParam = {
		action: 'save_mri',
		oper: $("#cancel_mri").data('oper'),
		mrn: $('#mrn_doctorNote').val(),
		episno: $("#episno_doctorNote").val(),
		// recorddate: $("#recorddate_doctorNote").val(),
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formMRI").serializeArray();
	
	values = values.concat(
		$('#formMRI input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formMRI input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formMRI input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formMRI select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
		name: 'mri_weight',
		value: $('#formMRI input[name=mri_weight]').val()
	})
	values.push({
		name: 'mri_entereddate',
		value: $('#formMRI input[name=mri_entereddate]').val()
	})
	values.push({
		name: 'prosvalve_rmk',
		value: $('#formMRI textarea[name=prosvalve_rmk]').val()
	})
	values.push({
		name: 'oper3mth_remark',
		value: $('#formMRI textarea[name=oper3mth_remark]').val()
	})
	values.push({
		name: 'bloodurea',
		value: $('#formMRI input[name=bloodurea]').val()
	})
	values.push({
		name: 'serum_creatinine',
		value: $('#formMRI input[name=serum_creatinine]').val()
	})
	values.push({
		name: 'mri_doctorname',
		value: $('#formMRI input[name=mri_doctorname]').val()
	})
	values.push({
		name: 'mri_radiologist',
		value: $('#formMRI input[name=mri_radiologist]').val()
	})
	values.push({
		name: 'radiographer',
		value: $('#formMRI input[name=radiographer]').val()
	})
	values.push({
		name: 'mri_lastuser',
		value: $('#formMRI input[name=mri_lastuser]').val()
	})
	
	$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

function saveForm_physio(callback){
	var saveParam = {
		action: 'save_physio',
		oper: $("#cancel_physio").data('oper'),
		mrn: $('#mrn_doctorNote').val(),
		episno: $("#episno_doctorNote").val(),
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formPhysio").serializeArray();
	
	values = values.concat(
		$('#formPhysio input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formPhysio input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formPhysio input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formPhysio select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
		name: 'req_date',
		value: $('#formPhysio input[name=req_date]').val()
	})
	values.push({
		name: 'clinic_diag',
		value: $('#formPhysio textarea[name=clinic_diag]').val()
	})
	values.push({
		name: 'findings',
		value: $('#formPhysio textarea[name=findings]').val()
	})
	values.push({
		name: 'phy_treatment',
		value: $('#formPhysio textarea[name=phy_treatment]').val()
	})
	values.push({
		name: 'phy_doctorname',
		value: $('#formPhysio input[name=phy_doctorname]').val()
	})
	values.push({
		name: 'phy_lastuser',
		value: $('#formPhysio input[name=phy_lastuser]').val()
	})
	
	$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

function saveForm_dressing(callback){
	var saveParam = {
		action: 'save_dressing',
		oper: $("#cancel_dressing").data('oper'),
		mrn: $('#mrn_doctorNote').val(),
		episno: $("#episno_doctorNote").val(),
	}
	
	var postobj = {
		_token: $('#_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formDressing").serializeArray();
	
	values = values.concat(
		$('#formDressing input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formDressing input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formDressing input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formDressing select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
		name: 'od_dressing',
		value: $('#formDressing input[name=od_dressing]').val()
	})
	values.push({
		name: 'bd_dressing',
		value: $('#formDressing input[name=bd_dressing]').val()
	})
	values.push({
		name: 'eod_dressing',
		value: $('#formDressing input[name=eod_dressing]').val()
	})
	values.push({
		name: 'others_dressing',
		value: $('#formDressing input[name=others_dressing]').val()
	})
	values.push({
		name: 'others_name',
		value: $('#formDressing input[name=others_name]').val()
	})
	values.push({
		name: 'solution',
		value: $('#formDressing textarea[name=solution]').val()
	})
	values.push({
		name: 'dressing_doctorname',
		value: $('#formDressing input[name=dressing_doctorname]').val()
	})
	values.push({
		name: 'dressing_lastuser',
		value: $('#formDressing input[name=dressing_lastuser]').val()
	})
	
	$.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').done(function (data){
		callback(data);
	}).fail(function (data){
		callback(data);
	});
}

var docnote_date_tbl = $('#docnote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging": false,
	"columns": [
		{'data': 'mrn'},
		{'data': 'episno'},
		{'data': 'date', 'width': '100%'},
		{'data': 'adduser'},
		{'data': 'adddate'},
		{'data': 'recordtime'},
		{'data': 'doctorname'},
	],
	columnDefs: [
		{ targets: [0, 1, 3, 4, 5, 6], visible: false },
	],
	"drawCallback": function (settings){
		if(settings.aoData.length > 0){
			$(this).find('tbody tr')[0].click();
		}else{
			button_state_doctorNote('add');
		}
	}
});

$('#tab_doctornote').on('show.bs.collapse', function (){
	return check_if_user_selected();
});

$('#tab_doctornote').on('shown.bs.collapse', function (){
	SmoothScrollTo('#tab_doctornote', 200);
	// datable_medication.columns.adjust();
	$('div#docnote_date_tbl_sticky').show();
	$("#jqGrid_trans").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
	
	docnote_date_tbl.ajax.url("./ptcare_doctornote/table?"+$.param(dateParam_docnote)).load(function (data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
	});
	refreshGrid("#jqGrid_trans", urlParam_trans);
	populate_otbook_getdata();
	populate_radClinic_getdata();
	populate_mri_getdata();
	populate_physio_getdata();
	populate_dressing_getdata();
	populate_admhandover_getdata();
});

$('#tab_doctornote').on('hide.bs.collapse', function (){
	$('div#docnote_date_tbl_sticky').hide();
	disableForm('#formOTBook');
	disableForm('#formRadClinic');
	disableForm('#formMRI');
	disableForm('#formPhysio');
	disableForm('#formDressing');
	disableForm('#formAdmhandover');

});

// to reload date table on radio btn click
$("input[name=toggle_type]").on('change', function (){
	event.stopPropagation();
	on_toggling_curr_past(curr_obj);
	docnote_date_tbl.ajax.url("./ptcare_doctornote/table?"+$.param(dateParam_docnote)).load(function (data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
	});
	$("#jqGridAddNotes").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotes_c")[0].offsetWidth-$("#jqGridAddNotes_c")[0].offsetLeft));
});

$('#docnote_date_tbl tbody').on('click', 'tr', function (){ 
	var data = docnote_date_tbl.row(this).data();
	
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
	$('#docnote_date_tbl tbody tr').removeClass('active');
	$(this).addClass('active');
	
	doctornote_docnote.adddate = data.adddate;
	doctornote_docnote.recordtime = data.recordtime;
	doctornote_docnote.mrn = data.mrn;
	doctornote_docnote.episno = data.episno;
	
	urlParam_AddNotes.filterVal[0] = data.mrn;
	urlParam_AddNotes.filterVal[1] = data.episno;
	
	$('#mrn_doctorNote_past').val(data.mrn);
	$('#episno_doctorNote_past').val(data.episno);
	$('#recorddate_doctorNote').val(data.date);
	
	$.get("./ptcare_doctornote/table?"+$.param(doctornote_docnote), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.episode);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathealth);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathistory);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.patexam);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.episdiag);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathealthadd);
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
			getBMI();
			
			if(data.pathealth == undefined){
				button_state_doctorNote('add');
			}else{
				button_state_doctorNote('edit');
			}
		}
	});
	
	var urlParam_trans = {
		url: './ptcare_doctornote/table',
		isudept: 'CLINIC',
		action: 'get_transaction_table',
		mrn: data.mrn,
		episno: data.episno
	}
	
	refreshGrid("#jqGrid_trans", urlParam_trans);
});

function check_same_usr_edit(data){
	let same = true;
	var adduser = data.adduser;
	
	if(adduser == undefined){
		return false
	}else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
		return false;
	}
	
	return same;
}

function textarea_init_otbook(){
	$('textarea#ot_remarks').each(function (){
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

function textarea_init_radClinic(){
	$('textarea#rad_allergy,textarea#xray_remark,textarea#mri_remark,textarea#angio_remark,textarea#ultrasound_remark,textarea#ct_remark,textarea#fluroscopy_remark,textarea#mammogram_remark,textarea#bmd_remark,textarea#clinicaldata,textarea#rad_note').each(function (){
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

function textarea_init_mri(){
	$('textarea#prosvalve_rmk,textarea#oper3mth_remark').each(function (){
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

function textarea_init_physio(){
	$('textarea#clinic_diag,textarea#findings,textarea#phy_treatment').each(function (){
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

function textarea_init_dressing(){
	$('textarea#solution').each(function (){
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

$("#formOTBook input[name=req_type]").on('click', function (){
	toggle_reqtype();
});

function toggle_reqtype(){
	if(document.getElementById("type_ward").checked){
		$('#Bed_div').show();
		$('#OT_div').hide();
	}else if(document.getElementById("type_ot").checked){
		$('#Bed_div').hide();
		$('#OT_div').show();
	}
}