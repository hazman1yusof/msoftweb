
$(document).ready(function () {

	disableForm('#formDoctorNote');

	$("#new_doctorNote").click(function(){
    	$('#docnote_date_tbl tbody tr').removeClass('active');
		$('#cancel_doctorNote').data('oper','add');
		button_state_doctorNote('wait');
		enableForm('#formDoctorNote');
		rdonly('#formDoctorNote');
    	emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		// dialog_mrn_edit.on();
		
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
    			docnote_date_tbl.ajax.url( "/doctornote/table?"+$.param(dateParam_docnote) ).load(function(){
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
	$('#height').keyup(function(event) {
		getBMI();
	});

	$('#weight').keyup(function(event) {
		getBMI();
	});
	//bmi calculator ends

	// change diagnosis value
	$('#icdcode').change(function() {
		$('#diagfinal').val($('#icdcode').val());
	});

	// otherwise the radio button inside panel-heading wont work
	$("input[name=toggle_type]").click(function(event){
		event.stopPropagation();
	});

});

//bmi calculator
function getBMI() {
    var height = parseFloat($("#height").val());
    var weight = parseFloat($("#weight").val());

	var myBMI = (weight / height / height) * 10000;

    var bmi = myBMI.toFixed(2);

    if (isNaN(bmi)) bmi = 0;

    $('#bmi').val((bmi));
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
	switch(state){
		case 'empty':
			$("#toggle_doctorNote").removeAttr('data-toggle');
			$('#cancel_doctorNote').data('oper','add');
			$('#new_doctorNote,#save_doctorNote,#cancel_doctorNote,#edit_doctorNote').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_doctorNote").attr('data-toggle','collapse');
			$('#cancel_doctorNote').data('oper','add');
			$("#new_doctorNote").attr('disabled',false);
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
		// case 'docnote':
		// 	$("#toggle_doctorNote").attr('data-toggle','collapse');
		// 	$('#cancel_doctorNote').data('oper','add');
		// 	$("#new_doctorNote").attr('disabled',false);
		// 	$('#save_doctorNote,#cancel_doctorNote').attr('disabled',true);
		// 	break;
	}

}

var dateParam_docnote,doctornote_docnote;
function populate_doctorNote(obj,rowdata){
	
	emptyFormdata(errorField,"#formDoctorNote");

	//panel header
	$('#name_show_doctorNote').text(obj.name);
	$('#mrn_show_doctorNote').text(obj.mrn);

	//formDoctorNote
	$('#mrn_doctorNote').val(obj.mrn);
	$("#episno_doctorNote").val(obj.episno);

	var addnotes = document.getElementById("addnotes");
  
	// check if its current
	if (document.getElementById("current").checked){
		dateParam_docnote={
			action:'get_table_date',
			mrn:obj.MRN,
			episno:obj.Episno
		}
		
		addnotes.style.display = "none";
		enableFields();
	} 
	// check if its past history
	if (document.getElementById("past").checked){
		dateParam_docnote={
			action:'get_table_date_past',
			mrn:obj.MRN,
		}

		addnotes.style.display = "block";
		disableOtherFields();
	}

	doctornote_docnote={
		action:'get_table_doctornote',
		mrn:obj.MRN,
		episno:obj.Episno,
		recorddate:''
	};

    button_state_doctorNote('add');
}

//screen current patient//
function populate_currDoctorNote(obj){
	
	emptyFormdata(errorField,"#formDoctorNote");

	//panel header
	$('#name_show_doctorNote').text(obj.Name);
	$('#mrn_show_doctorNote').text(("0000000" + obj.MRN).slice(-7));

	//formDoctorNote
	$('#mrn_doctorNote').val(obj.MRN);
	$("#episno_doctorNote").val(obj.Episno);

	var addnotes = document.getElementById("addnotes");
  
	// check if its current
	if (document.getElementById("current").checked){
		dateParam_docnote={
			action:'get_table_date',
			mrn:obj.MRN,
			episno:obj.Episno
		}
		
		addnotes.style.display = "none";
		enableFields()
	} 
	// check if its past history
	if (document.getElementById("past").checked){
		dateParam_docnote={
			action:'get_table_date_past',
			mrn:obj.MRN,
		}

		addnotes.style.display = "block";
		disableOtherFields();
	}

	doctornote_docnote={
		action:'get_table_doctornote',
		mrn:obj.MRN,
		episno:obj.Episno,
		recorddate:''
	};

    button_state_doctorNote('add');
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

    $.post( "/doctornote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        callback(data);
    }).success(function(data){
        callback(data);
    });
}

var docnote_date_tbl = $('#docnote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging":false,
    "columns": [
        {'data': 'idno'},
        {'data': 'date', 'width': '100%'},
    ],
    columnDefs: [ {
        targets: [0],
        visible: false
    } ],
    order: [[0, 'desc']],
    drawCallback: function(settings, json) {
    	// console.log(json);
    	// if ($(this).find('tbody tr').length<=0) {
     //    	$(this).find('tbody tr:first').first().hide();
    	// }

	}
});

var ajaxurl;
$('#jqGridDoctorNote_panel').on('show.bs.collapse', function () {
    docnote_date_tbl.ajax.url( "/doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
		$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row

		//to reload date table on radio btn click
		$("input[name=toggle_type]").click(function(event){
			docnote_date_tbl.ajax.reload();
		});
    });
});

$('#docnote_date_tbl tbody').on('click', 'tr', function () { 
    if(disable_edit_date()){
    	return;
	}
	
	//to highlight selected row
	if ( $(this).hasClass('selected') ) {
		$(this).removeClass('selected');
	}
	else {
		docnote_date_tbl.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
	}

    emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
    $('#docnote_date_tbl tbody tr').removeClass('active');
    $(this).addClass('active');

    button_state_doctorNote('edit');
    var data = docnote_date_tbl.row( this ).data();
    doctornote_docnote.recorddate = data.date;

    $.get( "/doctornote/table?"+$.param(doctornote_docnote), function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.episode);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathealth);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathistory);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.patexam);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.episdiag);
			autoinsert_rowdata_doctorNote("#formDoctorNote",data.pathealthadd);
			getBMI();
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