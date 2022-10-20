
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridExamTriage url/////////////////////////////////////////////////
var urlParam_ExamTriage = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nurassesexam',
	table_id: 'idno',
	filterCol:['mrn','episno','location'],
	filterVal:['','','TRIAGE'],
}

/////////////////////parameter for jqGridAddNotesTriage url/////////////////////////////////////////////////
var urlParam_AddNotesTriage = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.triage_addnotes',
	table_id: 'idno',
	filterCol:['mrn','episno','location'],
	filterVal:['','','TRIAGE'],
}

$(document).ready(function () {

	var fdl = new faster_detail_load();

	// disableForm('#formTriageInfo, #formActDaily, #formTriPhysical');

	disableForm('#formTriageInfo');

	$("#new_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ti").click(function(){
		disableForm('#formTriageInfo');
		if( $('#formTriageInfo').isValid({requiredFields: ''}, conf, true) ) {
			var page_screen = $('#page_screen').val();
			console.log(page_screen);
			if(page_screen == 'patmast'){
				saveForm_patmast(function(){
					$("#cancel_ti").data('oper','edit');
					$("#cancel_ti").click();
					$('#refresh_jqGrid').click();
				});
			}else{
				saveForm_ti(function(){
					$("#cancel_ti").data('oper','edit');
					$("#cancel_ti").click();
					$('#refresh_jqGrid').click();
				});
			}

		}else{
			enableForm('#formTriageInfo');
			rdonly('#formTriageInfo');
		}

	});

	$("#cancel_ti").click(function(){
		disableForm('#formTriageInfo');
		button_state_ti($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});	

	// to autocheck the checkbox bila fill in textarea
	$("#drugs_remarks").on("keyup blur", function () {
        $("#allergydrugs").prop("checked", this.value !== "");
	});

	$("#plaster_remarks").on("keyup blur", function () {
        $("#allergyplaster").prop("checked", this.value !== "");
	});

	$("#food_remarks").on("keyup blur", function () {
        $("#allergyfood").prop("checked", this.value !== "");
	});

	$("#environment_remarks").on("keyup blur", function () {
        $("#allergyenvironment").prop("checked", this.value !== "");
	});

	$("#others_remarks").on("keyup blur", function () {
        $("#allergyothers").prop("checked", this.value !== "");
	});

	$("#unknown_remarks").on("keyup blur", function () {
        $("#allergyunknown").prop("checked", this.value !== "");
	});

	$("#none_remarks").on("keyup blur", function () {
        $("#allergynone").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends

    $("#jqGridTriageInfo_panel").on("show.bs.collapse", function(){
        $("#jqGridExamTriage").jqGrid ('setGridWidth', Math.floor($("#jqGridTriageInfo_c")[0].offsetWidth-$("#jqGridTriageInfo_c")[0].offsetLeft-248));
        $("#jqGridAddNotesTriage").jqGrid ('setGridWidth', Math.floor($("#jqGridTriageInfo_c")[0].offsetWidth-$("#jqGridTriageInfo_c")[0].offsetLeft-248));

        var urlaram_nursing_date_tbl={
			action:'get_table_date_past',
			mrn:$("#mrn_ti").val(),
		}

	    nursing_date_tbl.ajax.url( "./doctornote/table?"+$.param(urlaram_nursing_date_tbl) ).load(function(data){
			emptyFormdata_div("#formTriageInfo",['#mrn_ti','#episno_ti']);
			$('#nursing_date_tbl tbody tr:eq(0)').click();	//to select first row
	    });
    });

    $("#jqGridTriageInfo_panel").on("hide.bs.collapse", function(){
    	$("#jqGridTriageInfo_panel > div").scrollTop(0);
    });

	$('#jqGridTriageInfo_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo("#jqGridTriageInfo_panel", 500)	
		sticky_docnotetbl(on=true);
	});

	$('#jqGridTriageInfo_panel').on('hidden.bs.collapse', function () {
		sticky_docnotetbl(on=true);
	});

	/////////////////////parameter for saving url/////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}

	/////////////////////////////////// jqGridExamTriage ///////////////////////////////////////////////////
	$("#jqGridExamTriage").jqGrid({
		datatype: "local",
		editurl: "./nursing/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'Exam', name: 'exam', width: 80,classes: 'wrap', editable:true,
				editrules:{custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:examTriageCustomEdit,
						   custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Note', name: 'examnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 5}},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
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
		pager: "#jqGridPagerExamTriage",
		loadComplete: function(){
			if(window.location.pathname == '/bedmanagement'){
				// to hide pager
				$('#jqGridPagerExamTriage').html('');
				$('#jqGridPagerAddNotesTriage').html('');
			}
			if(addmore_jqgrid.more == true){$('#jqGridExamTriage_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridExamTriage_iledit").click();
		},
	});

	//////////////////////////////////////////myEditOptions////////////////////////////////////////////////
	var myEditOptions_add_ExamTriage = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_examTriage.on();

			$("input[name='examnote']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridExamTriage_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridExamTriage').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./nursing/form?"+
				$.param({
					episno:$('#episno_ti').val(),
					mrn:$('#mrn_ti').val(),
					action: 'nursing_save',
				});
			$("#jqGridExamTriage").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit////////////////////////////////////////////////
	var myEditOptions_edit_ExamTriage = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_examTriage.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='examnote']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridExamTriage_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridExamTriage').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./nursing/form?"+
				$.param({
					episno:$('#episno_ti').val(),
					mrn:$('#mrn_ti').val(),
					action: 'nursing_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridExamTriage").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerExamTriage////////////////////////////////////////////////
	$("#jqGridExamTriage").inlineNav('#jqGridPagerExamTriage', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_ExamTriage
		},
		editParams: myEditOptions_edit_ExamTriage
	}).jqGrid('navButtonAdd', "#jqGridPagerExamTriage", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGridExamTriage").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
			} else {
				var result = confirm("Are you sure you want to delete this row?");
				if (result == true) {
					param = {
						_token: $("#csrf_token").val(),
						action: 'nursing_save',
						idno: selrowData('#jqGridExamTriage').idno,
					}
					$.post( "./nursing/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function (data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function (data) {
						refreshGrid("#jqGridExamTriage", urlParam_ExamTriage);
					});
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				}
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerExamTriage", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridExamTriage", urlParam_ExamTriage);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	/////////////////////////////////// jqGridAddNotesTriage ///////////////////////////////////////////////////
	$("#jqGridAddNotesTriage").jqGrid({
		datatype: "local",
		editurl: "./nursing/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'Note', name: 'additionalnote', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 5}},
			{ label: 'Entered by', name: 'adduser', width: 50, hidden:false},
			{ label: 'Date', name: 'adddate', width: 50, hidden:false},
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
		pager: "#jqGridPagerAddNotesTriage",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridAddNotesTriage_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesTriage_iledit").click();
		},
	});

	//////////////////////////////////////////myEditOptions_add////////////////////////////////////////////////
	var myEditOptions_add_AddNotesTriage = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete_addnotestriage,#jqGridPagerRefresh_addnotestriage").hide();

			$("input[name='additionalnote']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesTriage_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			// addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
			errorField.length=0;
			$("#jqGridPagerDelete_addnotestriage,#jqGridPagerRefresh_addnotestriage").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');

			let data = $('#jqGridAddNotesTriage').jqGrid ('getRowData', rowid);
			let editurl = "./nursing/form?"+
				$.param({
					episno:$('#episno_ti').val(),
					mrn:$('#mrn_ti').val(),
					action: 'addNotesTriage_save',
					_token: $("#csrf_token").val()
				});
			$("#jqGridAddNotesTriage").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete_addnotestriage,#jqGridPagerRefresh_addnotestriage").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerAddNotesTriage////////////////////////////////////////////////
	$("#jqGridAddNotesTriage").inlineNav('#jqGridPagerAddNotesTriage', {
		add: true,
		edit: false,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_AddNotesTriage
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerAddNotesTriage", {
	// 	id: "jqGridPagerDelete_addnotestriage",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function () {
	// 		selRowId = $("#jqGridAddNotesTriage").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		} else {
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if (result == true) {
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'addNotesTriage_save',
	// 					idno: selrowData('#jqGridAddNotesTriage').idno,
	// 				}
	// 				$.post( "./nursing/form?"+$.param(param),{oper:'del'}, function( data ){
	// 				}).fail(function (data) {
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data) {
	// 					refreshGrid("#jqGridAddNotesTriage", urlParam_AddNotesTriage);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete_addnotestriage,#jqGridPagerRefresh_addnotestriage").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotesTriage", {
		id: "jqGridPagerRefresh_addnotestriage",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridAddNotesTriage", urlParam_AddNotesTriage);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
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

		fdl.get_array('nursing',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	function examTriageCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGridExam" optid="'+opt.id+'" id="'+opt.id+'" name="exam" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0" readonly><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	var dialog_examTriage = new ordialog(
		'examTriage','nursing.examination',"#jqGridExamTriage input[name='exam']",errorField,
		{	colModel:[
				{label:'Exam Code',name:'examcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
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
				dialog_examTriage.urlParam.filterCol = ['compcode'];
				dialog_examTriage.urlParam.filterVal = ['session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_examTriage.makedialog();

	$("#dialognewexamFormTriage")
	  	.dialog({
		width: 4/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			
		},
		close: function(event,ui){
			refreshGrid('#'+dialog_examTriage.gridname,dialog_examTriage.urlParam);
		},
		buttons: [{
			text: "Save",click: function() {
				var saveParam={
			        action:'more_examTriage_save',
			    }
			    var postobj={
			    	_token : $('#csrf_token').val(),
			    	examcode : $('#examcodes').val(),
			    	description : $('#descriptions').val(),
			    };

				$.post( './nursing/form?'+$.param(saveParam), postobj , function( data ) {
		
				}).fail(function(data) {
				}).success(function(data){
					$("#dialognewexamFormTriage").dialog('close');
				});
			}
		},
		{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});
	$('#otherdialog_examTriage').append('<button type="button" id="exambut_add_newTriage" class="btn btn-sm">Add New Exam</button>');
	$("#exambut_add_newTriage").click(function(){
		$("#dialognewexamFormTriage").dialog('open');
	});


	$('#nursing_date_tbl tbody').on('click', 'tr', function () { 
	    var data = nursing_date_tbl.row( this ).data();

		if(data == undefined){
			return;
		}

		//to highlight selected row
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		}else {
			nursing_date_tbl.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}

		emptyFormdata_div("#formTriageInfo",['#mrn_ti','#episno_ti']);
	    $('#nursing_date_tbl tbody tr').removeClass('active');
	    $(this).addClass('active');

	    var saveParam={
	        action:'get_table_triage',
	    }
	    var postobj={
	    	_token : $('#csrf_token').val(),
	    	mrn:data.mrn,
	    	episno:data.episno
	    };

	    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).success(function(data){
	    	if(!$.isEmptyObject(data)){
				autoinsert_rowdata("#formTriageInfo",data.triage);
				autoinsert_rowdata("#formTriageInfo",data.triage_gen);
				autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
				autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
				$('#formTriageInfo span#adduser').text(data.triage_gen.adduser);
				refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
				refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
				button_state_ti('disableAll');
	        }else{
				button_state_ti('disableAll');
				refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'kosongkan');
				refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'kosongkan');
				examination_nursing.empty();
				autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
	        }

	    });

	});


});

var nursing_date_tbl = $('#nursing_date_tbl').DataTable({
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
    "drawCallback": function( settings ) {
    	$(this).find('tbody tr')[0].click();
    }
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

button_state_ti('empty');
function button_state_ti(state){
	switch(state){
		case 'empty':
			$("#toggle_ti").removeAttr('data-toggle');
			$('#cancel_ti').data('oper','add');
			$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#cancel_ti').data('oper','add');
			$("#new_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#cancel_ti').data('oper','edit');
			$("#edit_ti").attr('disabled',false);
			$('#save_ti,#cancel_ti,#new_ti').attr('disabled',true);
			break;
		case 'wait':
			dialog_tri_col.on();
			examination_nursing.on().enable();
			$("#toggle_ti").attr('data-toggle','collapse');
			$("#save_ti,#cancel_ti").attr('disabled',false);
			$('#edit_ti,#new_ti').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
	// }
}

// screen emergency //
function populate_formNursing(obj,rowdata){
	//panel header
	$('#name_show_triage').text(obj.a_pat_name);
	$('#mrn_show_triage').text(("0000000" + obj.a_mrn).slice(-7));
	$('#sex_show_triage').text(obj.sex);
	$('#dob_show_triage').text(dob_chg(obj.dob));
	$('#age_show_triage').text(obj.age+ ' (YRS)');
	$('#race_show_triage').text(obj.race);
	$('#religion_show_triage').text(if_none(obj.religion));
	$('#occupation_show_triage').text(if_none(obj.occupation));
	$('#citizenship_show_triage').text(obj.citizen);
	$('#area_show_triage').text(obj.area);
	
	button_state_ti('add');

	//formTriageInfo
	$("#mrn_ti").val(obj.a_mrn);
	$("#episno_ti").val(obj.a_Episno);
	$("#reg_date").val(obj.reg_date);
	tri_color_set('empty');

	// table examination
	urlParam_ExamTriage.filterVal[0] = obj.a_mrn;
	urlParam_ExamTriage.filterVal[1] = obj.a_Episno;
	urlParam_ExamTriage.filterVal[2] = 'TRIAGE';

	//table additional info
	urlParam_AddNotesTriage.filterVal[0] = obj.a_mrn;
	urlParam_AddNotesTriage.filterVal[1] = obj.a_Episno;
	urlParam_AddNotesTriage.filterVal[2] = 'TRIAGE';

	// document.getElementById('showTriage_curpt').style.display = 'inline'; 

	if(rowdata.nurse != undefined){
		autoinsert_rowdata("#formTriageInfo",rowdata.nurse);
		tri_color_set();
		button_state_ti('edit');
	}

	if(rowdata.nurse_gen != undefined){
		autoinsert_rowdata("#formTriageInfo",rowdata.nurse_gen);
		$('#formTriageInfo span#adduser').text(data.nurse_gen.adduser);
		button_state_ti('edit');

		// autoinsert_rowdata("#formTriageInfo",rowdata.nurse_gen);
		// button_state_ti('edit');
	}

	if(rowdata.nurse_exm != undefined){
		refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
		refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
		// var newrowdata = $.extend(true,{}, rowdata);
		// examination_nursing.empty();
		// examination_nursing.examarray = newrowdata.nurse_exm;
		// examination_nursing.loadexam().off().disable();
	}else{
		refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
		refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
		// examination_nursing.empty().off().disable();
	}
}

//screen bed management//
function populate_triage(obj,rowdata){

	emptyFormdata(errorField,"#formTriageInfo");

	//panel header
	$('#name_show_triage').text(obj.name);
	$('#mrn_show_triage').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_triage').text(obj.sex);
	$('#dob_show_triage').text(dob_chg(obj.dob));
	$('#age_show_triage').text(obj.age+ ' (YRS)');
	$('#race_show_triage').text(obj.race);
	$('#religion_show_triage').text(if_none(obj.religion));
	$('#occupation_show_triage').text(if_none(obj.occupation));
	$('#citizenship_show_triage').text(obj.citizen);
	$('#area_show_triage').text(obj.area);

	$("#mrn_ti").val(obj.MRN);
	$("#episno_ti").val(obj.Episno);

	// table examination
	urlParam_ExamTriage.filterVal[0] = obj.mrn;
	urlParam_ExamTriage.filterVal[1] = obj.episno;
	urlParam_ExamTriage.filterVal[2] = 'TRIAGE';

	//table additional info
	urlParam_AddNotesTriage.filterVal[0] = obj.mrn;
	urlParam_AddNotesTriage.filterVal[1] = obj.episno;
	urlParam_AddNotesTriage.filterVal[2] = 'TRIAGE';

	// document.getElementById('showTriage_bedmgmt').style.display = 'inline';

	var saveParam={
        action:'get_table_triage',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.mrn,
    	episno:obj.episno

    };

    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formTriageInfo",data.triage);
			autoinsert_rowdata("#formTriageInfo",data.triage_gen);
			autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
			autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
			$('#formTriageInfo span#adduser').text(data.triage_gen.adduser);
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
			button_state_ti('disableAll');
        }else{
			button_state_ti('disableAll');
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'kosongkan');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'kosongkan');
			examination_nursing.empty();
			autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
        }

    });
	
}

//screen current patient//
function populate_triage_currpt(obj){
	emptyFormdata(errorField,"#formTriageInfo");
	//panel header
	$('#name_show_triage').text(obj.Name);
	$('#mrn_show_triage').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_triage').text((obj.Sex).toUpperCase());
	$('#dob_show_triage').text(dob_chg(obj.DOB));
	$('#age_show_triage').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_triage').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_triage').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_triage').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_triage').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_triage').text(if_none(obj.areaDesc).toUpperCase());

	$("#mrn_ti").val(obj.MRN);
	$("#episno_ti").val(obj.Episno);

	// table examination
	urlParam_ExamTriage.filterVal[0] = obj.MRN;
	urlParam_ExamTriage.filterVal[1] = obj.Episno;
	urlParam_ExamTriage.filterVal[2] = 'TRIAGE';

	//table additional info
	urlParam_AddNotesTriage.filterVal[0] = obj.MRN;
	urlParam_AddNotesTriage.filterVal[1] = obj.Episno;
	urlParam_AddNotesTriage.filterVal[2] = 'TRIAGE';

	// document.getElementById('showTriage_curpt').style.display = 'inline';

	var saveParam={
        action:'get_table_triage',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			if(!emptyobj_(data.triage))autoinsert_rowdata("#formTriageInfo",data.triage);
			if(!emptyobj_(data.triage_gen))autoinsert_rowdata("#formTriageInfo",data.triage_gen);
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
			if(!emptyobj_(data.triage_nurshistory))autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
			if(!emptyobj_(data.triage_gen))$('#formTriageInfo span#adduser').text(data.triage_gen.adduser);
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
			button_state_ti('edit');
        }else{
			button_state_ti('add');
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'kosongkan');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'kosongkan');
			examination_nursing.empty();
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
        }

    });
	
}

//screen case note//
function populate_triage_casenote(obj){
	emptyFormdata(errorField,"#formTriageInfo");
	//panel header
	$('#name_show_triage').text(obj.Name);
	$('#mrn_show_triage').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_triage').text((obj.Sex).toUpperCase());
	$('#dob_show_triage').text(dob_chg(obj.DOB));
	$('#age_show_triage').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_triage').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_triage').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_triage').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_triage').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_triage').text(if_none(obj.areaDesc).toUpperCase());

	$("#mrn_ti").val(obj.MRN);
	$("#episno_ti").val(obj.Episno);

	// table examination
	urlParam_ExamTriage.filterVal[0] = obj.MRN;
	urlParam_ExamTriage.filterVal[1] = obj.Episno;
	urlParam_ExamTriage.filterVal[2] = 'TRIAGE';

	//table additional info
	urlParam_AddNotesTriage.filterVal[0] = obj.MRN;
	urlParam_AddNotesTriage.filterVal[1] = obj.Episno;
	urlParam_AddNotesTriage.filterVal[2] = 'TRIAGE';

	// document.getElementById('showTriage_curpt').style.display = 'inline';

	var saveParam={
        action:'get_table_triage',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formTriageInfo",data.triage);
			autoinsert_rowdata("#formTriageInfo",data.triage_gen);
			autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
			autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
			$('#formTriageInfo span#adduser').text(data.triage_gen.adduser);
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'add_exam');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');
			button_state_ti('disableAll');
        }else{
			button_state_ti('disableAll');
			refreshGrid('#jqGridExamTriage',urlParam_ExamTriage,'kosongkan');
			refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'kosongkan');
			examination_nursing.empty();
			autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
        }

    });

    var urlaram_nursing_date_tbl={
		action:'get_table_date_past',
		mrn:$("#mrn_ti").val(),
	}

    nursing_date_tbl.ajax.url( "./doctornote/table?"+$.param(urlaram_nursing_date_tbl) ).load(function(data){
		emptyFormdata_div("#formTriageInfo",['#mrn_ti','#episno_ti']);
		$('#nursing_date_tbl tbody tr:eq(0)').click();	//to select first row
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

function empty_formNursing(){
	
	tri_color_set('empty');
	$('#name_show_ti').text('');
	$('#newic_show_ti').text('');
	$('#sex_show_ti').text('');
	$('#age_show_ti').text('');
	$('#race_show_ti').text('');	
	button_state_ti('empty');
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	disableForm('#formTriageInfo');
	emptyFormdata(errorField,'#formTriageInfo')
	examination_nursing.empty().off().disable();
	dialog_tri_col.off();

}

function saveForm_ti(callback){
	var saveParam={
        action:'save_table_ti',
        oper:$("#cancel_ti").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formTriageInfo").serializeArray();

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formTriageInfo input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formTriageInfo select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    // values = values.concat(
    //     $('#formTriageInfo input[type=radio]:checked').map(
    //     function() {
    //         return {"name": this.name, "value": this.value}
    //     }).get()
    // );

    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_patmast(callback){
	var saveParam={
        action:'save_table_triage',
        oper:$("#cancel_ti").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

    values = $("#formTriageInfo").serializeArray();

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formTriageInfo input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formTriageInfo input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formTriageInfo select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    // values = values.concat(
    //     $('#formTriageInfo input[type=radio]:checked').map(
    //     function() {
    //         return {"name": this.name, "value": this.value}
    //     }).get()
    // );

    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}


var dialog_tri_col = new ordialog(
	'tri_col','sysdb.sysparam',"#triagecolor",errorField,
	{	colModel:
		[
			{label:'Color',name:'colorcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
		],
		urlParam: {
			url:'./sysparam_triage_color',
			filterCol:['recstatus','compcode'],
			filterVal:['ACTIVE', 'session.compcode']
			},
		ondblClickRow:function(event){

			$(dialog_tri_col.textfield).val(selrowData("#"+dialog_tri_col.gridname)['description']);
			$(dialog_tri_col.textfield)
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_col.gridname)['description'] );

			$(dialog_tri_col.textfield).next()
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_col.gridname)['description'] );

		},
		onSelectRow:function(rowid, selected){
			$('#'+dialog_tri_col.gridname+' tr#'+rowid).dblclick();
			// $(dialog_tri_col.textfield).val(selrowData("#"+dialog_tri_col.gridname)['description']);

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

			$( "table#othergrid_tri_col tr:nth-child(2)" ).addClass('red')
			$( "table#othergrid_tri_col tr:nth-child(3)" ).addClass('yellow')
			$( "table#othergrid_tri_col tr:nth-child(4)" ).addClass('green')
		}
	},{
		title:"Select Bed Status",
		open: function(){
			dialog_tri_col.urlParam.filterCol = ['recstatus','compcode'];
			dialog_tri_col.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
		},
		width:5/10 * $(window).width()
	},'urlParam','radio','tab','table'
);
dialog_tri_col.makedialog();

function tri_color_set(empty){
	if(empty == 'empty'){
		$(dialog_tri_col.textfield).removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );

		$(dialog_tri_col.textfield).next().removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
	}

	var color = $(dialog_tri_col.textfield).val();
	$(dialog_tri_col.textfield)
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );

	$(dialog_tri_col.textfield).next()
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
}


var examination_nursing = new examination();
function examination(){
	this.examarray=[];
	this.on=function(){
		$("#exam_plus").on('click',{data:this},addexam);
		return this;
	}

	this.empty=function(){
		this.examarray.length=0;
		$("#exam_div").html('');
		return this;
	}

	this.off=function(){
		$("#exam_plus").off('click',addexam);
		return this;
	}

	this.disable=function(){
		disableForm('#exam_div');
		return this;
	}

	this.enable=function(){
		enableForm('#exam_div');
		return this;
	}

	this.loadexam = function(){
		this.examarray.forEach(function(item, index){
			$("#exam_div").append(`
				<hr>
				<div class="form-group">
					<input type="hidden" name="examidno_`+index+`" value="`+item.idno+`">
					<div class="col-md-2">Exam</div>
					<div class="col-md-10">
						<select class="form-select form-control" name="examsel_`+index+`" id="exam_`+index+`">
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
						<textarea class="form-control input-sm uppercase" rows="5"  name="examnote_`+index+`" id="examnote_`+index+`">`+item.examnote+`</textarea>
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

		$("#exam_div").append(`
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

