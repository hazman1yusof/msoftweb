
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

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

	$('textarea#medicalhistory,textarea#surgicalhistory,textarea#currentmedication,textarea#drugs_remarks,textarea#food_remarks,textarea#others_remarks,textarea#br_breathingdesc,textarea#br_coughdesc,textarea#br_smokedesc,textarea#ed_eatdrinkdesc').each(function () {
		this.setAttribute('style', 'height:' + (38) + 'px;min-height:'+ (38) +'px;overflow-y:hidden;');
	}).on('input', function () {
		this.style.height = 'auto';
		this.style.height = (this.scrollHeight) + 'px';
	});

	var fdl = new faster_detail_load();

	// disableForm('#formTriageInfo, #formActDaily, #formTriPhysical');

	$('#tab_triage').on('shown.bs.collapse', function () {
		SmoothScrollTo('#tab_triage', 300);
		$("#jqGridAddNotesTriage").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesTriage_c")[0].offsetWidth-$("#jqGridAddNotesTriage_c")[0].offsetLeft-25));
	});

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

	$('#formTriageInfo').form({
	    fields: {
			admwardtime : 'empty',
			reg_date : 'empty',
			admreason : 'empty'
	    }
	});

	$("#save_ti").click(function(){
		if( $('#formTriageInfo').isValid({requiredFields: ''}, conf, true) ) {
			readonlyForm('#formTriageInfo');
			saveForm_ti(function(){
				unreadonlyForm('#formTriageInfo');
				rdonly('#formTriageInfo');
				$("#cancel_ti").data('oper','edit');
				$("#cancel_ti").click();
			});

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

	$("#food_remarks").on("keyup blur", function () {
        $("#allergyfood").prop("checked", this.value !== "");
	});

	$("#others_remarks").on("keyup blur", function () {
        $("#allergyothers").prop("checked", this.value !== "");
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
		scroll: true,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesTriage",
		onSelectRow:function(rowid, selected){
			calc_jq_height_onchange("jqGridAddNotesTriage");
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridAddNotesTriage_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			
			calc_jq_height_onchange("jqGridAddNotesTriage");
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
			console.log(data);

			let editurl = "./nursing/form?"+
				$.param({
					episno:$('#episno_ti').val(),
					mrn:$('#mrn_ti').val(),
					action: 'addNotesTriage_save',
					_token: $("#_token").val()
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
				}).done(function(data){
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

		emptyFormdata(errorField,"#formTriageInfo",['#mrn_ti','#episno_ti']);
	    $('#nursing_date_tbl tbody tr').removeClass('active');
	    $(this).addClass('active');

	    var saveParam={
	        action:'get_table_triage',
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	mrn:data.mrn,
	    	episno:data.episno,
	    	arrival_date:data.date
	    };

	    loader_nursing(true);
	    $.post( "./nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).done(function(data){
	    	loader_nursing(false);
	    	if(!$.isEmptyObject(data)){
	    		$('#reg_date').val(data.triage_regdate);
	    		if(data.triage != undefined){
					autoinsert_rowdata("#formTriageInfo",data.triage);
	    			button_state_ti('edit');
	    		}else{
	    			button_state_ti('add');
	    		}

	    		if(data.triage_gen != undefined){
					autoinsert_rowdata("#formTriageInfo",data.triage_gen);
	    		}
	    		if(data.triage_nurshistory != undefined){
					autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
	    		}

				refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'addNotes_triage');


	        }else{
				button_state_ti('disableAll');
				refreshGrid('#jqGridAddNotesTriage',urlParam_AddNotesTriage,'kosongkan');
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

$("input[name=toggle_type_nurse]").on('change', function () {
	event.stopPropagation();
	on_toggling_curr_past_nurse();
	nursing_date_tbl.ajax.url( "./nursing/table?"+$.param(dateParam_nurse) ).load(function(data){
		emptyFormdata(errorField,"#formTriageInfo",['#mrn_ti','#episno_ti']);
    });
	$("#jqGridAddNotes").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotes_c")[0].offsetWidth-$("#jqGridAddNotes_c")[0].offsetLeft-25));
});

function on_toggling_curr_past_nurse(){
	if (document.getElementById("current_nurse").checked){
		dateParam_nurse={
			action:'get_table_date_curr',
			mrn:$('#mrn_ti').val(),
			episno:$('#episno_ti').val(),
			date:$('#sel_date').val()
		}
		
	}else if(document.getElementById("past_nurse").checked){
		dateParam_nurse={
			action:'get_table_date_past',
			mrn:$('#mrn_ti').val(),
		}
	}
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

//screen current patient//
function populate_triage_currpt(obj){
	emptyFormdata(errorField,"#formTriageInfo",['#mrn_ti','#episno_ti']);
	//panel header
	$('#name_show_triage').text(obj.Name);
	$('#mrn_show_triage').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_triage').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_triage').text(dob_chg(obj.DOB));
	$('#age_show_triage').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_triage').text(if_none(obj.RaceCode).toUpperCase());
	$('#religion_show_triage').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_triage').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_triage').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_triage').text(if_none(obj.AreaCode).toUpperCase());

	$("#mrn_ti").val(obj.MRN);
	$("#episno_ti").val(obj.Episno);

	//table additional info
	urlParam_AddNotesTriage.filterVal[0] = obj.MRN;
	urlParam_AddNotesTriage.filterVal[1] = obj.Episno;
	urlParam_AddNotesTriage.filterVal[2] = 'TRIAGE';

	// document.getElementById('showTriage_curpt').style.display = 'inline';

    button_state_ti('add');
    on_toggling_curr_past_nurse();

    nursing_date_tbl.ajax.url( "./nursing/table?"+$.param(dateParam_nurse) ).load(function(data){
		emptyFormdata(errorField,"#formTriageInfo",['#mrn_ti','#episno_ti']);
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

	emptyFormdata('#formTriageInfo')

	button_state_ti('empty');
	$('#name_show_triage').text('');
	$('#mrn_show_triage').text('');
	$('#sex_show_triage').text('');
	$('#dob_show_triage').text('');
	$('#age_show_triage').text('');
	$('#race_show_triage').text('');
	$('#religion_show_triage').text('');
	$('#occupation_show_triage').text('');
	$('#citizenship_show_triage').text('');
	$('#area_show_triage').text('');


	$('#mrn_ti').val('');
	$("#episno_ti").val('');


}

function saveForm_ti(callback){
	loader_nursing(true);
	var saveParam={
        action:'save_table_ti',
        oper:$("#cancel_ti").data('oper')
    }
    var postobj={
    	_token : $('#_token').val(),
    };

    var values = $("#formTriageInfo").serializeArray();

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
		loader_nursing(false);
        callback();
    }).done(function(data){
		loader_nursing(false);
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
    }).done(function(data){
        callback();
    });
}

// var examination_nursing = new examination();
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

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}

function loader_nursing(load){
	if(load){
		$('#loader_nursing').addClass('active');
	}else{
		$('#loader_nursing').removeClass('active');
	}
}
