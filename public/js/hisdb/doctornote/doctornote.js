
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridAddNotes url/////////////////////////////////////////////////
var urlParam_AddNotes = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'hisdb.pathealthadd',
	table_id: 'idno',
	filterCol:['mrn','episno'],
	filterVal:['',''],
}

$(document).ready(function () {
	
	$("#jqGrid_trans_doctornote_ref").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid_trans_doctornote").jqGrid('getGridParam','colModel'),
		autowidth: false,
		width: 900,
		height: 80,
		rowNum: 30,
		pager:'#jqGrid_trans_doctornote_refPager',
		viewrecords: true,
		loadonce:false,
		scroll: true,
	});
	
	textare_init_doctornote();
	
	// $('textarea#clinicnote,textarea#pmh,textarea#drugh,textarea#allergyh,textarea#socialh,textarea#fmh,textarea#examination,textarea#diagfinal,textarea#plan_').each(function () {
	// 	this.setAttribute('style', 'height:' + (38) + 'px;min-height:'+ (38) +'px;overflow-y:hidden;');
	// }).on('input', function () {
	// 	this.style.height = 'auto';
	// 	this.style.height = (this.scrollHeight) + 'px';
	// });
	
	var fdl = new faster_detail_load();
	
	disableForm('#formDoctorNote');
	
	$("#new_doctorNote").click(function(){
		$('#docnote_date_tbl tbody tr').removeClass('active');
		$('#cancel_doctorNote').data('oper','add');
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
		hide_tran_button(false);
	
	});
	
	$("#edit_doctorNote").click(function(){
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// dialog_mrn_edit.on();
	
	});
	
	$("#save_doctorNote").click(function(){
		disableForm('#formDoctorNote');
		if( $('#formDoctorNote').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_doctorNote(function(data){
				$("#cancel_doctorNote").click();
				docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(){
					docnote_date_tbl.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
						var currow = this.data();
						if(currow.idno == data.idno){
							$(this.node()).addClass('active');
						}
					});
				});
			});
		}else{
			enableForm('#formDoctorNote');
			rdonly('#formDoctorNote');
		}
	
	});
	
	$("#cancel_doctorNote").click(function(){
		disableForm('#formDoctorNote');
		button_state_doctorNote($(this).data('oper'));
		// dialog_mrn_edit.off();
		$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
		$("#current,#past").attr('disabled',false);
	
	});
	
	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});
	
	// bmi calculator
	$("#formDoctorNote input[name='height'], #formDoctorNote input[name='weight']").on('change',function() {
		getBMI();
	});
	
	$("#form_docNoteRef input[name='height_ref'], #form_docNoteRef input[name='weight_ref']").on('change',function() {
		getBMI_ref();
	});
	// bmi calculator ends
	
	// change diagnosis value
	$('#icdcode').change(function() {
		$('#diagfinal').val($('#icdcode').val());
	});
	
	///////////////////////////////////////////Referral Letter///////////////////////////////////////////
	var oper_refletter='';
	// var oper_refletter='add';
	$("#dialogForm")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				parent_close_disabled(true);
				disableForm('#form_refLetter');
				disableForm('#form_docNoteRef');
				textare_init_doctornote();
				// dialog_icd_ref.check(errorField);
				// refreshGrid("#jqGrid_trans_doctornote", urlParam_trans);
				switch(oper_refletter) {
					case state = 'add':
						// $( this ).dialog( "option", "title", "Add" );
						enableForm('#form_refLetter');
						rdonly('#form_refLetter');
						break;
					case state = 'edit':
						// $( this ).dialog( "option", "title", "Edit" );
						enableForm('#form_refLetter');
						rdonly('#form_refLetter');
						break;
					case state = 'view':
						// $( this ).dialog( "option", "title", "View" );
						disableForm('#form_refLetter');
						rdonly("#form_refLetter");
						// $(this).dialog("option", "buttons",butt2);
						break;
				}
				if(oper_refletter!='view'){
					
				}
				if(oper_refletter!='add'){
					
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#form_refLetter');
				emptyFormdata(errorField,'#form_docNoteRef');
				dialog_icd_ref.off();
				// $('.alert').detach();
				$('.my-alert').detach();
				if(oper_refletter=='view'){
					// $(this).dialog("option", "buttons",butt1);
				}
			},
		});
	
	$("#referLetter").click(function(){
		// oper_refletter='add';
		$("#dialogForm").dialog("open");
		populate_refLetter();
	});

	$("#dialog_medc")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
            	epno_medc_init();
			},
			close: function( event, ui ) {

			},
		});

	$("#doctornote_medc").click(function(){
		// oper_refletter='add';
		$("#dialog_medc").dialog("open");
	});
	
	$("#new_refLetter").click(function(){
		$('#cancel_refLetter').data('oper','add');
		button_state_refLetter('wait');
		enableForm('#form_refLetter');
		rdonly('#form_refLetter');
		// emptyFormdata_div("#form_refLetter",['#mrn_doctorNote','#episno_doctorNote']);
	
	});
	
	$("#edit_refLetter").click(function(){
		button_state_refLetter('wait');
		enableForm('#form_refLetter');
		rdonly('#form_refLetter');
	
	});
	
	$("#save_refLetter").click(function(){
		disableForm('#form_refLetter');
		if( $('#form_refLetter').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_refLetter(function(data){
				// emptyFormdata_div("#form_refLetter",['#mrn_doctorNote','#episno_doctorNote']);
				// disableForm('#form_refLetter');
				$('#cancel_refLetter').data('oper','edit');
				$("#cancel_refLetter").click();
			});
		}else{
			enableForm('#form_refLetter');
			rdonly('#form_refLetter');
		}
		
	});
	
	$("#cancel_refLetter").click(function(){
		// emptyFormdata_div("#form_refLetter",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#form_refLetter');
		button_state_refLetter($(this).data('oper'));
	
	});

	$("#dialog_bpgraph")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
            	bpgraph_init();
			},
			close: function( event, ui ) {

			},
		});
	
	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotes///////////////////////////////////////////
	$("#jqGridAddNotes").jqGrid({
		datatype: "local",
		editurl: "./doctornote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true },
			{ label: 'Note', name: 'additionalnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 5} },
			{ label: 'Doctor Code', name: 'doctorcode', width: 50, hidden:false },
			{ label: 'Date', name: 'adddate', width: 50, hidden:false },
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
		pager: "#jqGridPagerAddNotes",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridAddNotes_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			
			calc_jq_height_onchange("jqGridAddNotes");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotes_iledit").click();
		},
	});
	
	////////////////////////////////////////////////////myEditOptions////////////////////////////////////////////////////
	var myEditOptions_add = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("input[name='additionalnote']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotes_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			
		},
		aftersavefunc: function (rowid, response, options) {
			// addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGridAddNotes').jqGrid ('getRowData', rowid);
			console.log(data);
			
			let editurl = "./doctornote/form?"+
				$.param({
					episno:$('#episno_doctorNote').val(),
					mrn:$('#mrn_doctorNote').val(),
					action: 'doctornote_save',
				});
			$("#jqGridAddNotes").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	///////////////////////////////////////////////jqGridPagerAddNotes///////////////////////////////////////////////
	$("#jqGridAddNotes").inlineNav('#jqGridPagerAddNotes', {
		add: true,
		edit: false,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
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
	// 	onClickButton: function () {
	// 		selRowId = $("#jqGridAddNotes").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		} else {
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if (result == true) {
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'doctornote_save',
	// 					idno: selrowData('#jqGridAddNotes').idno,
	// 				}
	// 				$.post( "./doctornote/form?"+$.param(param),{oper:'del'}, function( data ){
	// 				}).fail(function (data) {
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data) {
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
		onClickButton: function () {
			refreshGrid("#jqGridAddNotes", urlParam_AddNotes);
		},
	});
	
	/////////////////////////////////////////////////end grid/////////////////////////////////////////////////
	
});

//bmi calculator
function getBMI() {
	var height = parseFloat($("#formDoctorNote input[name='height']").val());
	var weight = parseFloat($("#formDoctorNote input[name='weight']").val());
	
	var myBMI = (weight / height / height) * 10000;
	
	var bmi = myBMI.toFixed(2);
	
	if (isNaN(bmi)) bmi = 0;
	
	$("#formDoctorNote input[name='bmi']").val((bmi));
}

function getBMI_ref() {
	var height_ref = parseFloat($("#form_docNoteRef input[name='height_ref']").val());
	var weight_ref = parseFloat($("#form_docNoteRef input[name='weight_ref']").val());
	
	// console.log(height_ref);
	// console.log(weight_ref);
	
	var myBMI_ref = (weight_ref / height_ref / height_ref) * 10000;
	
	var bmi_ref = myBMI_ref.toFixed(2);
	
	if (isNaN(bmi_ref)) bmi_ref = 0;
	
	$("#form_docNoteRef input[name='bmi_ref']").val((bmi_ref));
}

//to disable all input fields except additional note
function disableOtherFields() {
	// var fieldsNotToBeDisabled = new Array("additionalnote");
	
	// $("form input").filter(function(index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);
	
	// $("form textarea").filter(function(index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);
	
	$('#remarks, #clinicnote, #pmh, #drugh, #allergyh, #socialh, #fmh, #followuptime, #followupdate, #examination, #diagfinal, #icdcode, #plan_, #height, #weight, #bp_sys1, #bp_dias2, #pulse, #temperature, #respiration').prop('disabled',true);
}

//to enable fields when choose current
function enableFields() {
	$('#remarks, #clinicnote, #pmh, #drugh, #allergyh, #socialh, #fmh, #followuptime, #followupdate, #examination, #diagfinal, #icdcode, #plan_, #height, #weight, #bp_sys1, #bp_dias2, #pulse, #temperature, #respiration').prop('disabled',false);
}

var errorField = [];
conf = {
	modules : 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form) {
		if (errorField.length > 0) {
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

var dialog_icd = new ordialog(
	'icdcode',['hisdb.diagtab AS dt','sysdb.sysparam AS sp'],"#formDoctorNote input[name='icdcode']",errorField,
	{
		colModel:[
			{ label:'ICD Code',name:'icdcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true },
			{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true },
		],
		urlParam: {
			url : "./doctornote/table",
			filterCol:['sp.compcode'],
			filterVal:['session.compcode'],
		},
		ondblClickRow:function(){
			let data = selrowData('#'+dialog_icd.gridname);
			$("#diagfinal").val(data['icdcode'] + " " + data['description']);
			$('#plan_').focus();
			// document.getElementById("diagfinal").value = document.getElementById("icdcode").value; //copy data to Diagnosis
		},
		gridComplete: function(obj){
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
		title:"Select ICD",
		open: function(){
			dialog_icd.urlParam.url = "./doctornote/table";
			dialog_icd.urlParam.action = "dialog_icd";
			dialog_icd.urlParam.table_name = ['hisdb.diagtab AS dt','sysdb.sysparam AS sp'];
			dialog_icd.urlParam.join_type = ['LEFT JOIN'];
			dialog_icd.urlParam.join_onCol = ['dt.type'];
			dialog_icd.urlParam.join_onVal = ['sp.pvalue1'];
			dialog_icd.urlParam.fixPost="true";
			dialog_icd.urlParam.table_id="none_";
			dialog_icd.urlParam.filterCol=['sp.compcode','sp.source', 'sp.trantype'];
			dialog_icd.urlParam.filterVal=['session.compcode', 'MR', 'ICD' ];
		}
	},'urlParam','radio','tab'
);
dialog_icd.makedialog();

var dialog_icd_ref = new ordialog(
	'icdcode_ref',['hisdb.diagtab AS dt','sysdb.sysparam AS sp'],"#form_docNoteRef input[name='icdcode_ref']",errorField,
	{
		colModel:[
			{ label:'ICD Code',name:'icdcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true },
			{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true },
		],
		urlParam: {
			url : "./doctornote/table",
			filterCol:['sp.compcode'],
			filterVal:['session.compcode'],
		},
		ondblClickRow:function(){
			let data = selrowData('#'+dialog_icd_ref.gridname);
			$("#diagfinal_ref").val(data['icdcode'] + " " + data['description']);
			$('#plan_ref').focus();
			// document.getElementById("diagfinal_ref").value = document.getElementById("icdcode_ref").value; //copy data to Diagnosis
		},
		gridComplete: function(obj){
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
		title:"Select ICD",
		open: function(){
			dialog_icd_ref.urlParam.url = "./doctornote/table";
			dialog_icd_ref.urlParam.action = "dialog_icd";
			dialog_icd_ref.urlParam.table_name = ['hisdb.diagtab AS dt','sysdb.sysparam AS sp'];
			dialog_icd_ref.urlParam.join_type = ['LEFT JOIN'];
			dialog_icd_ref.urlParam.join_onCol = ['dt.type'];
			dialog_icd_ref.urlParam.join_onVal = ['sp.pvalue1'];
			dialog_icd_ref.urlParam.fixPost="true";
			dialog_icd_ref.urlParam.table_id="none_";
			dialog_icd_ref.urlParam.filterCol=['sp.compcode','sp.source', 'sp.trantype'];
			dialog_icd_ref.urlParam.filterVal=['session.compcode', 'MR', 'ICD' ];
		}
	},'urlParam','radio','tab'
);
dialog_icd_ref.makedialog();

button_state_doctorNote('empty');
function button_state_doctorNote(state){
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

// button_state_refLetter('empty');
function button_state_refLetter(state){
	switch(state){
		case 'empty':
			$("#toggle_refLetter").removeAttr('data-toggle');
			$('#cancel_refLetter').data('oper','add');
			$('#new_refLetter,#save_refLetter,#cancel_refLetter,#edit_refLetter').attr('disabled',true);
			$('#pdfgen1').attr('disabled',false);
			break;
		case 'add':
			$("#toggle_refLetter").attr('data-toggle','collapse');
			$('#cancel_refLetter').data('oper','add');
			$("#new_refLetter,#pdfgen1").attr('disabled',false);
			$('#save_refLetter,#cancel_refLetter,#edit_refLetter').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_refLetter").attr('data-toggle','collapse');
			$('#cancel_refLetter').data('oper','edit');
			$("#edit_refLetter,#pdfgen1").attr('disabled',false);
			$('#save_refLetter,#cancel_refLetter,#new_refLetter').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_refLetter").attr('data-toggle','collapse');
			$("#save_refLetter,#cancel_refLetter,#pdfgen1").attr('disabled',false);
			$('#edit_refLetter,#new_refLetter').attr('disabled',true);
			break;
	}
	
}

var dateParam_docnote,doctornote_docnote,curr_obj;
//screen bedmanagement//
function populate_doctorNote(obj,rowdata){
	curr_obj=obj;
	
	emptyFormdata(errorField,"#formDoctorNote");
	
	//panel header
	$('#name_show_doctorNote').text(obj.name);
	$('#mrn_show_doctorNote').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_doctorNote').text(obj.sex);
	$('#dob_show_doctorNote').text(dob_chg(obj.dob));
	$('#age_show_doctorNote').text(obj.age+ ' (YRS)');
	$('#race_show_doctorNote').text(obj.race);
	$('#religion_show_doctorNote').text(if_none(obj.religion));
	$('#occupation_show_doctorNote').text(if_none(obj.occupation));
	$('#citizenship_show_doctorNote').text(obj.citizen);
	$('#area_show_doctorNote').text(obj.area);
	
	//formDoctorNote
	$('#mrn_doctorNote').val(obj.mrn);
	$("#episno_doctorNote").val(obj.episno);
	
	// check if its current
	on_toggling_curr_past(obj);
	
	urlParam_AddNotes.filterVal[0] = obj.mrn;
	urlParam_AddNotes.filterVal[1] = obj.episno;
	
	doctornote_docnote={
		action:'get_table_doctornote',
		mrn:obj.mrn,
		episno:obj.episno,
		recorddate:''
	};
	
    button_state_doctorNote('add');
}

//screen current patient//
function populate_doctorNote_currpt(obj){
	curr_obj=obj;
	
	emptyFormdata(errorField,"#formDoctorNote");
	
	//panel header
	$('#name_show_doctorNote').text(obj.Name);
	$('#mrn_show_doctorNote').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_doctorNote').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_doctorNote').text(dob_chg(obj.DOB));
	$('#age_show_doctorNote').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_doctorNote').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_doctorNote').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_doctorNote').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_doctorNote').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_doctorNote').text(if_none(obj.areaDesc).toUpperCase());
	
	//formDoctorNote
	$('#mrn_doctorNote').val(obj.MRN);
	$("#episno_doctorNote").val(obj.Episno);
	$('#ptname_doctorNote').val(obj.Name);
	
	on_toggling_curr_past(obj);
	
	urlParam_AddNotes.filterVal[0] = obj.MRN;
	urlParam_AddNotes.filterVal[1] = obj.Episno;
	
	doctornote_docnote={
		action:'get_table_doctornote',
		mrn:obj.MRN,
		episno:obj.Episno,
		recorddate:''
	};
	
	button_state_doctorNote('add');
	
    // docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
	// 	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
	// 	$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
    // });
}

function populate_refLetter(obj){
	// emptyFormdata(errorField,"#form_refLetter");
	emptyFormdata(errorField,"#form_docNoteRef");
	
	$("#pt_mrn").text($('#mrn_doctorNote').val());
	$("#pt_name").text($('#ptname_doctorNote').val());
	
	// $("#pdfgen1").attr('href','./doctornote/showpdf?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val());
	
	$("#pdfgen1").click(function() {
		// window.location='./doctornote/showpdf?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val();
		window.open('./doctornote/showpdf?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val(), '_blank');
	});
	
	var urlparam={
		action:'get_table_refLetter',
		mrn:$('#mrn_doctorNote').val(),
		episno:$("#episno_doctorNote").val(),
		recorddate:$('#recorddate_doctorNote').val()
	}
	
	var postobj={
		_token : $('#csrf_token').val(),
		mrn:$('#mrn_doctorNote').val(),
		episno:$("#episno_doctorNote").val()
	};
	
	$.post( "./doctornote/form?"+$.param(urlparam), $.param(postobj), function( data ) {
		
	},'json').fail(function(data) {
		alert('there is an error');
	}).done(function(data){
		if(!$.isEmptyObject(data.patreferral)){
			button_state_refLetter('edit');
			if(!emptyobj_(data.patreferral))autoinsert_rowdata("#form_refLetter",data.patreferral);
			
			if(!$.isEmptyObject(data.patreferral.reftitle)){
				$('#form_refLetter textarea[name=reftitle]').text(data.patreferral.reftitle);	// from patreferral
			}else{
				$('#form_refLetter textarea[name=reftitle]').text(data.sys_reftitle);	// from sysparam
			}
		}else{
			button_state_refLetter('add');
			$('#form_refLetter textarea[name=reftitle]').text(data.sys_reftitle);
		}
		$("#refadduser").val(data.adduser);
		
		textare_init_doctornote();
		if(!emptyobj_(data.episode))autoinsert_rowdata("#form_docNoteRef",data.episode);
		if(!emptyobj_(data.pathealth))autoinsert_rowdata("#form_docNoteRef",data.pathealth);
		if(!emptyobj_(data.pathistory))autoinsert_rowdata("#form_docNoteRef",data.pathistory);
		if(!emptyobj_(data.patexam))autoinsert_rowdata("#form_docNoteRef",data.patexam);
		if(!emptyobj_(data.episdiag))autoinsert_rowdata("#form_docNoteRef",data.episdiag);
		// if(!emptyobj_(data.pathealth))$('#form_docNoteRef span#doctorcode').text(data.pathealth.doctorcode);
		getBMI_ref();
		
		refreshGrid("#jqGrid_trans_doctornote_ref", urlParam_trans);
		// $("#jqGrid_trans_doctornote_ref").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_doctornote_ref_c")[0].offsetWidth-$("#jqGrid_trans_doctornote_ref_c")[0].offsetLeft));
	});
}

//screen emergency//
function populate_doctorNote_emergency(obj,rowdata){
	curr_obj=obj;
	
	emptyFormdata(errorField,"#formDoctorNote");
	
	//panel header
	$('#name_show_doctorNote').text(obj.a_pat_name);
	$('#mrn_show_doctorNote').text(("0000000" + obj.a_mrn).slice(-7));
	$('#sex_show_doctorNote').text(obj.sex);
	$('#dob_show_doctorNote').text(dob_chg(obj.dob));
	$('#age_show_doctorNote').text(obj.age+ ' (YRS)');
	$('#race_show_doctorNote').text(obj.race);
	$('#religion_show_doctorNote').text(if_none(obj.religion));
	$('#occupation_show_doctorNote').text(if_none(obj.occupation));
	$('#citizenship_show_doctorNote').text(obj.citizen);
	$('#area_show_doctorNote').text(obj.area);
	
	//formDoctorNote
	$('#mrn_doctorNote').val(obj.a_mrn);
	$("#episno_doctorNote").val(obj.a_Episno);
	
	// check if its current
	on_toggling_curr_past(obj);
	
	urlParam_AddNotes.filterVal[0] = obj.a_mrn;
	urlParam_AddNotes.filterVal[1] = obj.a_Episno;
	
	doctornote_docnote={
		action:'get_table_doctornote',
		mrn:obj.a_mrn,
		episno:obj.a_Episno,
		recorddate:''
	};
	
    button_state_doctorNote('add');
}

function on_toggling_curr_past(obj = curr_obj){
	var addnotes = document.getElementById("addnotes");
	
	if (document.getElementById("current").checked){
		dateParam_docnote={
			action:'get_table_date_curr',
			mrn:obj.MRN,
			episno:obj.Episno
		}
		
		addnotes.style.display = "none";
		enableFields();
		button_state_doctorNote('add'); //enable balik button
		// datable_medication.clear().draw();
	}else if(document.getElementById("past").checked){
		dateParam_docnote={
			action:'get_table_date_past',
			mrn:obj.MRN,
		}
		
		addnotes.style.display = "block";
		disableOtherFields();
		button_state_doctorNote('disableAll'); //disable all buttons
		$('#jqGridPagerRefresh_addnotes').click();
	}
}

function autoinsert_rowdata(form,rowData){
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if(input.is("[type=checkbox]")){
			if(value==1){
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
	var saveParam={
		action:'save_table_doctornote',
		oper:$("#cancel_doctorNote").data('oper')
	}
	var postobj={
		_token : $('#csrf_token').val(),
		// sex_edit : $('#sex_edit').val(),
		// idtype_edit : $('#idtype_edit').val()
	
	};
	
	values = $("#formDoctorNote").serializeArray();
	
	values = values.concat(
		$('#formDoctorNote input[type=checkbox]:not(:checked)').map(
		function() {
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote input[type=checkbox]:checked').map(
		function() {
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote input[type=radio]:checked').map(
		function() {
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formDoctorNote select').map(
		function() {
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	$.post( "./doctornote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
		
	},'json').fail(function(data) {
		callback(data);
	}).success(function(data){
		callback(data);
	});
}

function saveForm_refLetter(callback){
	var saveParam={
		action:'save_refLetter',
		oper:$("#cancel_refLetter").data('oper'),
		mrn:$('#mrn_doctorNote').val(),
		episno:$("#episno_doctorNote").val(),
	}
	var postobj={
		_token : $('#csrf_token').val(),
		// sex_edit : $('#sex_edit').val(),
		// idtype_edit : $('#idtype_edit').val()
	
	};
	
	values = $("#form_refLetter").serializeArray();
	
	values = values.concat(
		$('#form_refLetter input[type=checkbox]:not(:checked)').map(
		function() {
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetter input[type=checkbox]:checked').map(
		function() {
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetter input[type=radio]:checked').map(
		function() {
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetter select').map(
		function() {
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	$.post( "./doctornote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
		
	},'json').fail(function(data) {
		callback(data);
	}).success(function(data){
		callback(data);
	});
}

// function getdata_refLetter(){
//     var urlparam={
//         action:'get_table_refLetter',
//     }
    
//     var postobj={
//         _token : $('#csrf_token').val(),
//         mrn:$('#mrn_doctorNote').val(),
//         episno:$("#episno_doctorNote").val()
//     };
    
//     $.post( "./doctornote/form?"+$.param(urlparam), $.param(postobj), function( data ) {
        
//     },'json').fail(function(data) {
//         alert('there is an error');
//     }).done(function(data){
//         if(!$.isEmptyObject(data)){
//             button_state_refLetter('edit');
//         }else{
//             button_state_refLetter('add');
//         }
//     });
// }

var docnote_date_tbl = $('#docnote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging":false,
	"columns": [
		{'data': 'mrn'},
		{'data': 'episno'},
		{'data': 'date', 'width': '60%'},
		{'data': 'adduser'},
		{'data': 'doctorname', 'width': '30%'},
	]
	,columnDefs: [
		{ targets: [0, 1, 3], visible: false},
	],
	// "order": [[ 2, "desc" ]],
	"drawCallback": function( settings ) {
		$(this).find('tbody tr')[0].click();
	}
});

// var datable_medication = $('#medication_tbl').DataTable({
// 	"ajax": "",
// 	"sDom": "",
// 	"responsive": true,
// 	"paging":false,
// 	"columns": [
// 		{data: 'chg_desc', 'width': '30%'},
// 		{data: 'quantity'},
// 		{data: 'remarks'},
// 		{data: 'dos_code'},
// 		{data: 'fre_code'},
// 		{data: 'ins_code'},
// 		{data: 'dru_code'},
// 	]
// });

var ajaxurl;
$('#jqGridDoctorNote_panel').on('shown.bs.collapse', function () {
	sticky_docnotetbl(on=true);
    docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
		$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
    });
	SmoothScrollTo("#jqGridDoctorNote_panel", 500);
	$("#jqGrid_trans_doctornote").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_doctornote_c")[0].offsetWidth-$("#jqGrid_trans_doctornote_c")[0].offsetLeft));	
	textare_init_doctornote();
	urlParam_trans.mrn=$('#mrn_doctorNote').val();
	urlParam_trans.episno=$('#episno_doctorNote').val();
	curpage_tran = null;
	refreshGrid("#jqGrid_trans_doctornote", urlParam_trans);
	
});

//to reload date table on radio btn click
$("input[name=toggle_type]").on('click', function () {
	event.stopPropagation();
	on_toggling_curr_past(curr_obj);
	docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
		$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
    });
	$("#jqGridAddNotes").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotes_c")[0].offsetWidth-$("#jqGridAddNotes_c")[0].offsetLeft));
});

$('#docnote_date_tbl tbody').on('click', 'tr', function () {
	var data = docnote_date_tbl.row( this ).data();
	// console.log($(this).hasClass('selected'));
	
	// if(disable_edit_date()){
	// 	return;
	// }else
	
	if(data == undefined){
		return;
	}
	
	//to highlight selected row
	if($(this).hasClass('selected')) {
		$(this).removeClass('selected');
	}else {
		docnote_date_tbl.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
	}
	
	// console.log($('input[name="toggle_type"]:checked').val());
	
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
	$('#docnote_date_tbl tbody tr').removeClass('active');
	$(this).addClass('active');
	
	if(check_same_usr_edit(data)){
		button_state_doctorNote('edit');
		
		if(document.getElementById("past").checked){
			button_state_doctorNote('disableAll');
		}
	}else{
		button_state_doctorNote('add');
		
		if(document.getElementById("past").checked){
			button_state_doctorNote('disableAll');
		}
	}
	
	$('#mrn_doctorNote').val(data.mrn);
	$("#episno_doctorNote").val(data.episno);
	$("#recorddate_doctorNote").val(data.date);
	
	doctornote_docnote.mrn = data.mrn;
	doctornote_docnote.episno = data.episno;
	doctornote_docnote.recorddate = data.date;
	
	$.get( "./doctornote/table?"+$.param(doctornote_docnote), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			if(!emptyobj_(data.episode))autoinsert_rowdata("#formDoctorNote",data.episode);
			if(!emptyobj_(data.pathealth))autoinsert_rowdata("#formDoctorNote",data.pathealth);
			if(!emptyobj_(data.pathealth))$('#formDoctorNote span#doctorcode').text(data.pathealth.doctorcode);
			if(!emptyobj_(data.pathistory))autoinsert_rowdata("#formDoctorNote",data.pathistory);
			if(!emptyobj_(data.patexam))autoinsert_rowdata("#formDoctorNote",data.patexam);
			if(!emptyobj_(data.episdiag))autoinsert_rowdata("#formDoctorNote",data.episdiag);
			if(!emptyobj_(data.pathealthadd))autoinsert_rowdata("#formDoctorNote",data.pathealthadd);
			refreshGrid('#jqGridAddNotes',urlParam_AddNotes,'add_notes');
			getBMI();
			textare_init_doctornote();
			
			// datable_medication.clear().draw();
			// datable_medication.rows.add(data.transaction.rows).draw();
		}
	});
	
});

function disable_edit_date(){
	let disabled = false;
    let newact = $('#new_doctorNote').attr('disabled');
	let data_oper = $('#cancel_doctorNote').data('oper');
	
    if(newact == 'disabled' && data_oper == 'add'){
    	disabled = true;
    }
    return disabled;
}

function check_same_usr_edit(data){
	let same = true;
	var adduser = data.adduser;
	
	if(adduser == null){
		same =false;
	}else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
		same = false;
	}
	
	return same;
}

function sticky_docnotetbl(on){
	$(window).off('scroll');
	if(on){
		var topDistance = $('#docnote_date_tbl_sticky').offset().top;
		$(window).on('scroll', function() {
		    var scrollTop = $(this).scrollTop();
			var bottomDistance = $('#jqGrid_ordcom_c').offset().top;
		    if((topDistance+10) < scrollTop && (bottomDistance-280)>scrollTop){
		    	$('#docnote_date_tbl_sticky').addClass( "sticky_div" );
		    }else{
		    	$('#docnote_date_tbl_sticky').removeClass( "sticky_div" );
		    }
		});
	}else{
		$(window).off('scroll');
	}
	
}

function textare_init_doctornote(){
	$('textarea#clinicnote,textarea#pmh,textarea#drugh,textarea#allergyh,textarea#socialh,textarea#fmh,textarea#examination,textarea#diagfinal,textarea#plan_').each(function () {
		if(this.value.trim() == ''){
			this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}else{
			this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
		}
	}).off().on('input', function () {
		if(this.scrollHeight>40){
	  		this.style.height = 'auto';
	  		this.style.height = (this.scrollHeight) + 'px';
		}else{
	  		this.style.height = (40) + 'px';
		}
	});
}

function bpgraph_init(){
	fetchdata_bpgrah(true);
}

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight<50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight>300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }