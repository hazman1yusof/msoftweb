
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

////////////////////////////parameter for jqGridExam url////////////////////////////
var urlParam_Exam = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nurassesexam',
	table_id: 'idno',
	filterCol: ['mrn','episno','location'],
	filterVal: ['','','WARD'],
}

$(document).ready(function () {
	
	textare_init_nursAssessment();
	
	// $('textarea#nurs_admreason,textarea#nurs_medicalhistory,textarea#nurs_surgicalhistory,textarea#nurs_familymedicalhist,textarea#nurs_currentmedication,textarea#nurs_diagnosis,textarea#nurs_drugs_remarks,textarea#nurs_plaster_remarks,textarea#nurs_food_remarks,textarea#nurs_environment_remarks,textarea#nurs_others_remarks,textarea#nurs_unknown_remarks,textarea#nurs_none_remarks,textarea#nurs_br_breathingdesc,textarea#nurs_br_coughdesc,textarea#nurs_br_smokedesc,textarea#nurs_ed_eatdrinkdesc,textarea#nurs_eb_bowelmovedesc,textarea#nurs_bl_urinedesc,textarea#nurs_bl_urinefreq,textarea#nurs_pa_notes').each(function () {
	// 	this.setAttribute('style', 'height:' + (38) + 'px;min-height:'+ (38) +'px;overflow-y:hidden;');
	// }).on('input', function () {
	// 	this.style.height = 'auto';
	// 	this.style.height = (this.scrollHeight) + 'px';
	// });
	
	var fdl = new faster_detail_load();
	
	disableForm('#formWard');
	
	$("#new_ward").click(function(){
		button_state_ward('wait');
		enableForm('#formWard');
		rdonly('#formWard');
		// dialog_mrn_edit.on();
		
	});
	
	$("#edit_ward").click(function(){
		button_state_ward('wait');
		enableForm('#formWard');
		rdonly('#formWard');
		// dialog_mrn_edit.on();
		
	});
	
	$("#save_ward").click(function(){
		disableForm('#formWard');
		if( $('#formWard').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_ward(function(){
				$("#cancel_ward").data('oper','edit');
				$("#cancel_ward").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formWard');
			rdonly('#formWard');
		}
		
	});
	
	$("#cancel_ward").click(function(){
		disableForm('#formWard');
		button_state_ward($(this).data('oper'));
		examination_ward.empty().off();
		// dialog_mrn_edit.off();
		
	});
	
	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});
	
	// to autocheck the checkbox bila fill in textarea
	$("#nurs_drugs_remarks").on("keyup blur", function () {
		$("#nurs_allergydrugs").prop("checked", this.value !== "");
	});
	
	$("#nurs_plaster_remarks").on("keyup blur", function () {
		$("#nurs_allergyplaster").prop("checked", this.value !== "");
	});
	
	$("#nurs_food_remarks").on("keyup blur", function () {
		$("#nurs_allergyfood").prop("checked", this.value !== "");
	});
	
	$("#nurs_environment_remarks").on("keyup blur", function () {
		$("#nurs_allergyenvironment").prop("checked", this.value !== "");
	});
	
	$("#nurs_others_remarks").on("keyup blur", function () {
		$("#nurs_allergyothers").prop("checked", this.value !== "");
	});
	
	$("#nurs_unknown_remarks").on("keyup blur", function () {
		$("#nurs_allergyunknown").prop("checked", this.value !== "");
	});
	
	$("#nurs_none_remarks").on("keyup blur", function () {
		$("#nurs_allergynone").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends
	
	$("#jqGridWard_panel").on("show.bs.collapse", function(){
		$("#jqGridExam").jqGrid ('setGridWidth', Math.floor($("#jqGridWard_c")[0].offsetWidth-$("#jqGridWard_c")[0].offsetLeft-248));
	});
	
	$("#jqGridWard_panel").on("hide.bs.collapse", function(){
		button_state_ward('empty');
		disableForm('#formWard');
		$("#jqGridWard_panel > div").scrollTop(0);
	});
	
	$('#jqGridWard_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo("#jqGridWard_panel", 500);
		populate_nursAssessment_currpt_getdata();
	});
	
	/////////////////////////////////////parameter for saving url/////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	////////////////////////////////////////////jqGridExam////////////////////////////////////////////
	$("#jqGridExam").jqGrid({
		datatype: "local",
		editurl: "./wardpanel/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'Exam', name: 'exam', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules }, formatter: showdetail, edittype:'custom',
				editoptions:
				{	custom_element:examCustomEdit,
					custom_value:galGridCustomValue
				},
			},
			{ label: 'Note', name: 'examnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPagerExam",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridExam_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
			
			calc_jq_height_onchange("jqGridExam");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridExam_iledit").click();
		},
	});
	
	////////////////////////////////////myEditOptions_add_examWard////////////////////////////////////
	var myEditOptions_add_examWard = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			
			dialog_exam.on();
			
			$("input[name='examnote']").keydown(function(e) {	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridExam_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true;	// only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridExam',urlParam_Exam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridExam',urlParam_Exam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGridExam').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./wardpanel/form?"+
				$.param({
					episno: $('#episno_ward').val(),
					mrn: $('#mrn_ward').val(),
					action: 'wardpanel_save',
				});
			$("#jqGridExam").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	////////////////////////////////////myEditOptions_edit_examWard////////////////////////////////////
	var myEditOptions_edit_examWard = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			
			dialog_exam.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='examnote']").keydown(function(e) {	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridExam_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	// only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridExam',urlParam_Exam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridExam',urlParam_Exam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGridExam').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./wardpanel/form?"+
				$.param({
					episno: $('#episno_ward').val(),
					mrn: $('#mrn_ward').val(),
					action: 'wardpanel_edit',
				});
			$("#jqGridExam").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	//////////////////////////////////////////jqGridPagerExam//////////////////////////////////////////
	$("#jqGridExam").inlineNav('#jqGridPagerExam', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_examWard
		},
		editParams: myEditOptions_edit_examWard
	}).jqGrid('navButtonAdd', "#jqGridPagerExam", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGridExam").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#csrf_token").val(),
								action: 'wardpanel_save',
								idno: selrowData('#jqGridExam').idno,
							}
							$.post( "./wardpanel/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGridExam", urlParam_Exam);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerExam", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridExam", urlParam_Exam);
		},
	});
	/////////////////////////////////////////////end grid/////////////////////////////////////////////
	
	////////////////////////////////////////////cust_rules////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Exam':temp=$("input[name='exam']");break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'exam':field=['examcode','description'];table="nursing.examination";case_='exam';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('wardpanel',options,param,case_,cellvalue);
		
		return cellvalue;
	}
	
	function examCustomEdit(val, opt) {
		// console.log(val)
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGridExam" optid="'+opt.id+'" id="'+opt.id+'" name="exam" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	var dialog_exam = new ordialog(
		'exam','nursing.examination',"#jqGridExam input[name='exam']",errorField,
		{
			colModel: [
				{ label: 'Exam Code', name: 'examcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode'],
				filterVal: ['session.compcode']
			},
			ondblClickRow:function(){
				// $('#optax').focus();
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
			title:"Select Exam",
			open: function(){
				dialog_exam.urlParam.filterCol = ['compcode'];
				dialog_exam.urlParam.filterVal = ['session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_exam.makedialog();
	
	$("#dialognewexamForm")
		.dialog({
			width: 4/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				emptyFormdata_div("#dialognewexamForm");
			},
			close: function(event,ui){
				refreshGrid('#'+dialog_exam.gridname,dialog_exam.urlParam);
			},
			buttons: [{
				text: "Save",click: function() {
					var saveParam={
						action: 'more_exam_save',
					}
					var postobj={
						_token: $('#csrf_token').val(),
						examcode: $('#examcode').val(),
						description: $('#description').val()
					};
				
					$.post( './wardpanel/form?'+$.param(saveParam), postobj , function( data ) {
						
					}).fail(function(data) {
					}).success(function(data){
						$("#dialognewexamForm").dialog('close');
					});
				}
			},
			{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}]
		});
	$('#otherdialog_exam').append('<button type="button" id="exambut_add_new" class="btn btn-sm">Add New Exam</button>');
	$("#exambut_add_new").click(function(){
		$("#dialognewexamForm").dialog('open');
	});

});

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

button_state_ward('empty');
function button_state_ward(state){
	switch(state){
		case 'empty':
			$("#toggle_ward").removeAttr('data-toggle');
			$('#cancel_ward').data('oper','add');
			$('#new_ward,#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_ward").attr('data-toggle','collapse');
			$('#cancel_ward').data('oper','add');
			$("#new_ward").attr('disabled',false);
			$('#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_ward").attr('data-toggle','collapse');
			$('#cancel_ward').data('oper','edit');
			$("#edit_ward").attr('disabled',false);
			$('#save_ward,#cancel_ward,#new_ward').attr('disabled',true);
			break;
		case 'wait':
			dialog_col_ward.on();
			examination_ward.on().enable();
			$("#toggle_ward").attr('data-toggle','collapse');
			$("#save_ward,#cancel_ward").attr('disabled',false);
			$('#edit_ward,#new_ward').attr('disabled',true);
			break;
	}
	
	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_ward,#save_ward,#cancel_ward,#edit_ward').attr('disabled',true);
	// }
}

// screen bed management //
function populate_nursAssessment(obj,rowdata){
	emptyFormdata(errorField,"#formWard");
	
	// panel header
	$('#name_show_ward').text(obj.name);
	$('#mrn_show_ward').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_ward').text(obj.sex);
	$('#dob_show_ward').text(dob_chg(obj.dob));
	$('#age_show_ward').text(obj.age+ ' (YRS)');
	$('#race_show_ward').text(obj.race);
	$('#religion_show_ward').text(if_none(obj.religion));
	$('#occupation_show_ward').text(if_none(obj.occupation));
	$('#citizenship_show_ward').text(obj.citizen);
	$('#area_show_ward').text(obj.area);
	
	// formWard
	$('#mrn_ward').val(obj.mrn);
	$("#episno_ward").val(obj.episno);
	
	var saveParam={
		action: 'get_table_ward',
	}
	
	var postobj={
		_token: $('#csrf_token').val(),
		mrn: obj.mrn,
		episno: obj.episno
	};
	
	$.post( "./wardpanel/form?"+$.param(saveParam), $.param(postobj), function( data ) {
		
	},'json').fail(function(data) {
		alert('there is an error');
	}).success(function(data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formWard",data.ward);
			autoinsert_rowdata("#formWard",data.ward_gen);
			autoinsert_rowdata("#formWard",data.ward_regdate);
			autoinsert_rowdata("#formWard",data.ward_history);
			
			// autoinsert_rowdata("#formWard",data.ward_exm);
			if(!$.isEmptyObject(data.ward_exm)){
				urlParam_Exam.filterVal[0] = obj.mrn;
				urlParam_Exam.filterVal[1] = obj.episno;
				refreshGrid('#jqGridExam',urlParam_Exam,'add');
				// examination_ward.empty();
				// examination_ward.examarray = data.ward_exm;
				// examination_ward.loadexam().disable();
			}
			
			button_state_ward('edit');
			textare_init_nursAssessment();
		}else{
			button_state_ward('add');
			autoinsert_rowdata("#formWard",data.ward_regdate);
			// examination_ward.empty();
			textare_init_nursAssessment();
		}
	});
}

// screen current patient //
function populate_nursAssessment_currpt(obj){
	$("#jqGridWard_panel").collapse('hide');
	emptyFormdata(errorField,"#formWard");
	tri_color_set('empty');
	
	// panel header
	$('#name_show_ward').text(obj.Name);
	$('#mrn_show_ward').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_ward').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_ward').text(dob_chg(obj.DOB));
	$('#age_show_ward').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_ward').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_ward').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_ward').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_ward').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_ward').text(if_none(obj.areaDesc).toUpperCase());
	
	$("#mrn_ward").val(obj.MRN);
	$("#episno_ward").val(obj.Episno);
	
	// table examination
	urlParam_Exam.filterVal[0] = obj.MRN;
	urlParam_Exam.filterVal[1] = obj.Episno;
	// urlParam_Exam.filterVal[2] = 'WARD';
}

function populate_nursAssessment_currpt_getdata(){
	emptyFormdata(errorField,"#formWard",["#mrn_ward","#episno_ward"]);
	tri_color_set('empty');
	
	var saveParam={
		action: 'get_table_ward',
	}
	
	var postobj={
		_token: $('#csrf_token').val(),
		mrn: $("#mrn_ward").val(),
		episno: $("#episno_ward").val()
	};
	
	$.post( "./wardpanel/form?"+$.param(saveParam), $.param(postobj), function( data ) {
		
	},'json').fail(function(data) {
		alert('there is an error');
	}).success(function(data){
		if(!$.isEmptyObject(data.ward)){
			autoinsert_rowdata("#formWard",data.ward);
			autoinsert_rowdata("#formWard",data.ward_gen);
			autoinsert_rowdata("#formWard",data.ward_regdate);
			autoinsert_rowdata("#formWard",data.ward_history);
			
			// autoinsert_rowdata("#formWard",data.ward_exm);
			// if(!$.isEmptyObject(data.ward_exm)){
				urlParam_Exam.filterVal[0] = $("#mrn_ward").val();
				urlParam_Exam.filterVal[1] = $("#episno_ward").val();
				refreshGrid('#jqGridExam',urlParam_Exam,'add');
				// examination_ward.empty();
				// examination_ward.examarray = data.ward_exm;
				// examination_ward.loadexam().disable();
			// }
			
			button_state_ward('edit');
			textare_init_nursAssessment();
			tri_color_set();
		}else{
			button_state_ward('add');
			autoinsert_rowdata("#formWard",data.ward_regdate);
			refreshGrid('#jqGridExam',urlParam_Exam,'kosongkan');
			// examination_ward.empty();
			textare_init_nursAssessment();
		}
		
		if(!emptyobj_(data.ward_history))autoinsert_rowdata("#formWard",data.ward_history);
	});
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
		}else{
			input.val(value);
		}
	});
}

function saveForm_ward(callback){
	var saveParam={
		action: 'save_table_ward',
		oper: $("#cancel_ward").data('oper')
	}
	
	var postobj={
		_token: $('#csrf_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formWard").serializeArray();
	
	values = values.concat(
		$('#formWard input[type=checkbox]:not(:checked)').map(
			function() {
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formWard input[type=checkbox]:checked').map(
			function() {
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formWard input[type=radio]:checked').map(
			function() {
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formWard select').map(
			function() {
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	// values = values.concat(
	// 	$('#formWard input[type=radio]:checked').map(
	// 		function() {
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	$.post( "./wardpanel/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
		
	},'json').fail(function(data) {
		// alert('there is an error');
		callback();
	}).success(function(data){
		callback();
	});
}

var dialog_col_ward = new ordialog(
	'ward_tri_col','sysdb.sysparam',"#formWard input[name='triagecolor']",errorField,
	{
		colModel: [
			{ label: 'Color', name: 'colorcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', hidden: true, canSearch: false, or_search: true },
		],
		urlParam: {
			url: './sysparam_triage_color',
			filterCol: ['recstatus','compcode'],
			filterVal: ['ACTIVE', 'session.compcode']
		},
		ondblClickRow:function(event){
			$(dialog_col_ward.textfield).val(selrowData("#"+dialog_col_ward.gridname)['description']);
			$(dialog_col_ward.textfield)
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_col_ward.gridname)['description'] );
			
			$(dialog_col_ward.textfield).next()
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_col_ward.gridname)['description'] );
		},
		onSelectRow:function(rowid, selected){
			$('#'+dialog_col_ward.gridname+' tr#'+rowid).dblclick();
			// $(dialog_col_ward.textfield).val(selrowData("#"+dialog_col_ward.gridname)['description']);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		},
		loadComplete: function(data,obj){
			$("input[type='radio'][name='colorcode_select']").click(function(){
				let self = this;
				delay(function(){
					$(self).parent().click();
				}, 100 );
			});
			
			var gridname = '#'+obj.gridname;
			var ids = $(gridname).jqGrid("getDataIDs"), l = ids.length, i, rowid, status;
			for (i = 0; i < l; i++) {
				rowid = ids[i];
				colorcode = $(gridname).jqGrid("getCell", rowid, "description");
				
				$(gridname+' tr#' + rowid).addClass(colorcode);
			}
		}
	},{
		title:"Select Bed Status",
		open: function(){
			dialog_col_ward.urlParam.filterCol = ['recstatus','compcode'];
			dialog_col_ward.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
		},
		width:5/10 * $(window).width()
	},'urlParam','radio','tab','table'
);
dialog_col_ward.makedialog();

function tri_color_set(empty){
	if(empty == 'empty'){
		$(dialog_col_ward.textfield).removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
		
		$(dialog_col_ward.textfield).next().removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
	}
	
	var color = $(dialog_col_ward.textfield).val();
	$(dialog_col_ward.textfield)
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
	
	$(dialog_col_ward.textfield).next()
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
}

var examination_ward = new examination();
function examination(){
	this.examarray=[];
	this.on=function(){
		$("#ward_exam_plus").on('click',{data:this},addexam);
		return this;
	}
	
	this.empty=function(){
		this.examarray.length=0;
		$("#ward_exam_div").html('');
		return this;
	}
	
	this.off=function(){
		$("#ward_exam_plus").off('click',addexam);
		return this;
	}
	
	this.disable=function(){
		disableForm('#ward_exam_div');
		return this;
	}
	
	this.enable=function(){
		enableForm('#ward_exam_div');
		return this;
	}
	
	this.loadexam = function(){
		this.examarray.forEach(function(item, index){
			$("#ward_exam_div").append(`
				<hr>
				<div class="form-group">
					<input type="hidden" name="examidno_`+index+`" value="`+item.idno+`">
					<div class="col-md-2">Exam</div>
					<div class="col-md-10">
						<select class="form-select form-control" name="examsel_`+index+`" id="exam_`+index+`" >
							<option value="General">General</option>
							<option value="Head" >Head</option>
							<option value="Neck" >Neck</option>
							<option value="Throat" >Throat</option>
							<option value="Abdomen" >Abdomen</option>
							<option value="Eye" >Eye</option>
							<option value="Lungs" >Lungs</option>
							<option value="Neuro" >Neuro</option>
							<option value="Limbs" >Limbs</option>
							<option value="Chest" >Chest</option>
							<option value="BACK" >BACK</option>
							<option value="Heart" >Heart</option>
							<option value="Skin" >Skin</option>
							<option value="Musculosketel" >Musculosketel</option>
							<option value="Neurological" >Neurological</option>
							<option value="stomach" >stomach</option>
							<option value="middle finger" >middle finger</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2">Note</div>
					<div class="col-md-10">
						<textarea class="form-control input-sm uppercase" rows="5"  name="examnote_`+index+`" id="examnote_`+index+`" >`+item.examnote+`</textarea>
					</div>
				</div>
			`);
			
			$("#exam_"+index).val(item.exam);
		});
		return this;
	}
	
	function addexam(event){
		var obj = event.data.data;
		var currentid = 0;
		if(obj.examarray.length==0){
			obj.examarray.push(0);
			currentid = 0;
		}else{
			currentid = obj.examarray.length;
			obj.examarray.push(obj.examarray.length);
		}
		
		$("#ward_exam_div").append(`
			<hr>
			<div class="form-group">
				<input type="hidden" name="examidno_`+currentid+`" value="0">
				<div class="col-md-2">Exam</div>
				<div class="col-md-10">
					<select class="form-select form-control" name="examsel_`+currentid+`" id="exam_`+currentid+`">
						<option value="General" selected="selected" >General</option>
						<option value="Head" >Head</option>
						<option value="Neck" >Neck</option>
						<option value="Throat" >Throat</option>
						<option value="Abdomen" >Abdomen</option>
						<option value="Eye" >Eye</option>
						<option value="Lungs" >Lungs</option>
						<option value="Neuro" >Neuro</option>
						<option value="Limbs" >Limbs</option>
						<option value="Chest" >Chest</option>
						<option value="BACK" >BACK</option>
						<option value="Heart" >Heart</option>
						<option value="Skin" >Skin</option>
						<option value="Musculosketel" >Musculosketel</option>
						<option value="Neurological" >Neurological</option>
						<option value="stomach" >stomach</option>
						<option value="middle finger" >middle finger</option>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-2">Note</div>
				<div class="col-md-10">
					<textarea class="form-control input-sm uppercase" rows="5"  name="examnote_`+currentid+`" id="examnote_`+currentid+`"></textarea>
				</div>
			</div>
		`);
	}
}

function textare_init_nursAssessment(){
	$('textarea#nurs_admreason,textarea#nurs_medicalhistory,textarea#nurs_surgicalhistory,textarea#nurs_familymedicalhist,textarea#nurs_currentmedication,textarea#nurs_diagnosis,textarea#nurs_drugs_remarks,textarea#nurs_plaster_remarks,textarea#nurs_food_remarks,textarea#nurs_environment_remarks,textarea#nurs_others_remarks,textarea#nurs_unknown_remarks,textarea#nurs_none_remarks,textarea#nurs_br_breathingdesc,textarea#nurs_br_coughdesc,textarea#nurs_br_smokedesc,textarea#nurs_ed_eatdrinkdesc,textarea#nurs_eb_bowelmovedesc,textarea#nurs_bl_urinedesc,textarea#nurs_bl_urinefreq,textarea#nurs_pa_notes').each(function () {
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

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight<50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight>300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }

