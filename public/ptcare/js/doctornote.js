
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridAddNotes url/////////////////////////////////////////////////
var urlParam_AddNotes = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'hisdb.pathealthadd',
	table_id: 'idno',
	filterCol:['mrn','episno'],
	filterVal:['',''],
}

$(document).ready(function () {

	var fdl = new faster_detail_load();

	disableForm('#formDoctorNote',['toggle_type']);

	$("button.refreshbtn_doctornote").click(function(){
		populate_currDoctorNote(selrowData('#jqGrid'));
	});

	$("#new_doctorNote").click(function(){
    	// $('#docnote_date_tbl tbody tr').removeClass('active');
		$('#cancel_doctorNote').data('oper','add');
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
    	// emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// dialog_mrn_edit.on();
		
	});

	$("#edit_doctorNote").click(function(){
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
		// dialog_mrn_edit.on();
		
	});

	$("#save_doctorNote").click(function(){
		if( $('#formDoctorNote').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_doctorNote(function(data){
				emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
				disableForm('#formDoctorNote',['toggle_type']);
    			docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(){

    			});
			});
		}else{
			enableForm('#formDoctorNote');
			rdonly('#formDoctorNote');
		}

	});

	$("#cancel_doctorNote").click(function(){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		disableForm('#formDoctorNote',['toggle_type']);
		button_state_doctorNote($(this).data('oper'));
		// dialog_mrn_edit.off();
		// $('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	// to limit to two decimal places (onkeypress)
	$(document).on('keydown', 'input[pattern]', function(e){
		var input = $(this);
		var oldVal = input.val();
		var regex = new RegExp(input.attr('pattern'), 'g');
	  
		setTimeout(function(){
			var newVal = input.val();
			if(!regex.test(newVal)){
				input.val(oldVal); 
		  	}
		}, 0);
	});

	//bmi calculator
	$('form#formDoctorNote #height').blur(function(event) {
		getBMI();
	});

	$('form#formDoctorNote #weight').blur(function(event) {
		getBMI();
	});
	//bmi calculator ends

	// change diagnosis value
	$('#icdcode').change(function() {
		$('#diagfinal').val($('#icdcode').val());
	});

	/////////////////////parameter for saving url/////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}

	///////////////////////////////////jqGridAddNotes///////////////////////////////////////////////////
	$("#jqGridAddNotes").jqGrid({
		datatype: "local",
		editurl: "/doctornote/form",
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
		scroll: true,
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
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotes_iledit").click();
		},
	});

	//////////////////////////////////////////myEditOptions////////////////////////////////////////////////
	var myEditOptions_add = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
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

			let editurl = "/doctornote/form?"+
				$.param({
    				_token : $('#_token').val(),
					episno:$('#episno_doctorNote_past').val(),
					mrn:$('#mrn_doctorNote_past').val(),
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

	//////////////////////////////////////////jqGridPagerAddNotes////////////////////////////////////////////////
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
	// 				$.post( "/doctornote/form?"+$.param(param),{oper:'del'}, function( data ){
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

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
});

//bmi calculator
function getBMI() {
    var height = parseFloat($("form#formDoctorNote #height").val());
    var weight = parseFloat($("form#formDoctorNote #weight").val());

	var myBMI = (weight / height / height) * 10000;

    var bmi = myBMI.toFixed(2);

    if (isNaN(bmi)) bmi = 0;

    $('form#formDoctorNote #bmi').val((bmi));
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
	{	colModel:[
			{label:'ICD Code',name:'icdcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
		],
		urlParam: {
			url : "doctornote/table",
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
			dialog_icd.urlParam.url = "doctornote/table";
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
			$("#edit_doctorNote").attr('disabled',false);
			$('#save_doctorNote,#cancel_doctorNote,#new_doctorNote').attr('disabled',true);
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

var dateParam_docnote,doctornote_docnote,curr_obj;

function empty_currDoctorNote(){
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
	button_state_doctorNote('empty');

	//panel header
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

	//formDoctorNote
	$('#mrn_doctorNote').val('');
	$("#episno_doctorNote").val('');

	docnote_date_tbl.clear().draw();
}

//screen current patient//
function populate_currDoctorNote(obj){
	curr_obj=obj;
	
	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);

	//panel header
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

	//formDoctorNote
	$('#mrn_doctorNote').val(obj.MRN);
	$("#episno_doctorNote").val(obj.Episno);

	on_toggling_curr_past(obj);

	urlParam_AddNotes.filterVal[0] = obj.MRN;
	urlParam_AddNotes.filterVal[1] = obj.Episno;

	doctornote_docnote={
		action:'get_table_doctornote_div',
		mrn:obj.MRN,
		episno:obj.Episno,
		recorddate:''
	};

    button_state_doctorNote('add');

    docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// $('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
    });

}

function on_toggling_curr_past(obj = curr_obj){
	var addnotes = document.getElementById("addnotes");

	if (document.getElementById("current").checked){
		dateParam_docnote={
			action:'get_table_date_curr',
			mrn:obj.MRN,
			episno:obj.Episno,
			date:$('#sel_date').val()
		}
		$('#primary_icd_form,#followup_form').show();
		
		addnotes.style.display = "none";
		// enableFields();
		// $("#new_doctorNote").attr('disabled',false);
		// datable_medication.clear().draw();
	}else if(document.getElementById("past").checked){
		dateParam_docnote={
			action:'get_table_date_past',
			mrn:obj.MRN,
		}
		$('#primary_icd_form,#followup_form').hide();

		addnotes.style.display = "block";
		disableOtherFields();
		button_state_doctorNote('disableAll'); //disable all buttons
		$('#jqGridPagerRefresh_addnotes').click();
	}
}

function autoinsert_rowdata_doctorNote(form,rowData){
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
	let oper = $("#cancel_doctorNote").data('oper');
	var saveParam={
        action:'save_table_doctornote',
        oper:oper,
    }

    if(oper == 'add'){
    	saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
		var row = docnote_date_tbl.row('.active').data();
    	saveParam.sel_date = $('#sel_date').val();
    	saveParam.recordtime = row.recordtime;
    }

    var postobj={
    	_token : $('#_token').val(),
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
        
    },'json').done(function(data) {
        callback(data);
    }).fail(function(data){
        callback(data);
    });
}

var docnote_date_tbl = $('#docnote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging":false,
    "columns": [
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'date', 'width': '100%'},
        {'data': 'adduser'},
        {'data': 'adddate'},
        {'data': 'recordtime'},
        {'data': 'type'},
    ]
    ,columnDefs: [
        { targets: [0, 1, 3, 4, 5, 6], visible: false},
    ],
    "drawCallback": function( settings ) {
    	if(settings.aoData.length>0){
    		$(this).find('tbody tr')[0].click();
    	}else{
    		// button_state_doctorNote('add');
    	}
    }
});

$('#tab_doctornote').on('shown.bs.collapse', function () {
	SmoothScrollTo('#tab_doctornote', 300);
	// datable_medication.columns.adjust();
	$('div#docnote_date_tbl_sticky').show();
	$("#jqGrid_trans").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));

});

$('#tab_doctornote').on('hide.bs.collapse', function () {
	$('div#docnote_date_tbl_sticky').hide();
});

//to reload date table on radio btn click
$("input[name=toggle_type]").on('change', function () {
	event.stopPropagation();
	on_toggling_curr_past(curr_obj);
	docnote_date_tbl.ajax.url( "./doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// $('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
    });
	$("#jqGridAddNotes").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotes_c")[0].offsetWidth-$("#jqGridAddNotes_c")[0].offsetLeft));
});

$('#docnote_date_tbl tbody').on('click', 'tr', function () { 
    var data = docnote_date_tbl.row( this ).data();

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

    $.get( "./doctornote/table?"+$.param(doctornote_docnote), function( data ) {
			
	},'json').done(function(data) {
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
		url:'./doctornote/table',
		isudept:'CLINIC',
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
