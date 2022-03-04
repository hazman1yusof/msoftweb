
$(document).ready(function () {

	disableForm('#formDieteticCareNotes');

	$("#new_dieteticCareNotes").click(function(){
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dieteticCareNotes").click(function(){
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// disableFields_dieteticCareNotes();
		// dialog_mrn_edit.on();
		
	});

	$("#save_dieteticCareNotes").click(function(){
		disableForm('#formDieteticCareNotes');
		if( $('#formDieteticCareNotes').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dieteticCareNotes(function(){
				$("#cancel_dieteticCareNotes").data('oper','edit');
				$("#cancel_dieteticCareNotes").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDieteticCareNotes');
			rdonly('#formDieteticCareNotes');
		}

	});

	$("#cancel_dieteticCareNotes").click(function(){
		disableForm('#formDieteticCareNotes');
		button_state_dieteticCareNotes($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	$("#new_dieteticCareNotes_fup").click(function(){
		button_state_dieteticCareNotes('wait_fup');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dieteticCareNotes_fup").click(function(){
		button_state_dieteticCareNotes('wait_fup');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// disableFields_dieteticCareNotes();
		// dialog_mrn_edit.on();
		
	});

	$("#save_dieteticCareNotes_fup").click(function(){
		disableForm('#formDieteticCareNotes');
		if( $('#formDieteticCareNotes').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dieteticCareNotes_fup(function(){
				$("#cancel_dieteticCareNotes_fup").data('oper','edit_fup');
				$("#cancel_dieteticCareNotes_fup").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDieteticCareNotes');
			rdonly('#formDieteticCareNotes');
		}

	});

	$("#cancel_dieteticCareNotes_fup").click(function(){
		disableForm('#formDieteticCareNotes');
		button_state_dieteticCareNotes($(this).data('oper'));
		// dialog_mrn_edit.off();

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

	$("#jqGridDieteticCareNotes_panel").on("shown.bs.collapse", function(){
        var urlparam_dietetic_date_tbl={
			action:'get_table_date_dietetic',
			mrn:$("#mrn_dieteticCareNotes").val(),
			episno:$("#episno_dieteticCareNotes").val(),
		}

	    dietetic_date_tbl.ajax.url( "./dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
			emptyFormdata_div("#formDieteticCareNotes",['#mrn_dieteticCareNotes','#episno_dieteticCareNotes']);
			// $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
	    });
		SmoothScrollTo("#jqGridDieteticCareNotes_panel", 500)	
	});
	
	$('#dietetic_date_tbl tbody').on('click', 'tr', function () { 
	    var data = dietetic_date_tbl.row( this ).data();

		if(data == undefined){
			return;
		}

		//to highlight selected row
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		}else {
			dietetic_date_tbl.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}

		emptyFormdata_div("#formDieteticCareNotes",['#mrn_dieteticCareNotes','#episno_dieteticCareNotes']);
	    $('#dietetic_date_tbl tbody tr').removeClass('active');
	    $(this).addClass('active');

	    var saveParam={
	        action:'get_table_dieteticCareNotes',
	    }
	    var postobj={
	    	_token : $('#csrf_token').val(),
	    	mrn:data.mrn,
	    	episno:data.episno
	    };

	    $.post( "./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).success(function(data){
	    	if(!$.isEmptyObject(data)){
				autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietncase);
				autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietfup);
				button_state_dieteticCareNotes('edit');
				disableFields_dieteticCareNotes();
	        }else{
				button_state_dieteticCareNotes('add');
	        }

	    });

	});

});

var dietetic_date_tbl = $('#dietetic_date_tbl').DataTable({
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

// to disable all fields except those in follow up
function disableFields_dieteticCareNotes() {
	// var fieldsNotToBeDisabled = new Array("additionalnote");

	// $("form input").filter(function(index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);

	// $("form textarea").filter(function(index){
	// 	return fieldsNotToBeDisabled.indexOf($(this).attr("name"))<0;
	// }).prop("disabled", true);

	$('#ncase_medical_his,#ncase_surgical_his,#ncase_fam_medical_his,#ncase_history,#ncase_diagnosis,#ncase_intervention,#ncase_temperature,#ncase_pulse,#ncase_respiration,#ncase_bp_sys1,#ncase_bp_dias2,#ncase_height,#ncase_weight,#ncase_gxt,#ncase_painscore').prop('disabled',true);
}

button_state_dieteticCareNotes('empty');
function button_state_dieteticCareNotes(state){
	switch(state){
		case 'empty':
			$("#toggle_dieteticCareNotes").removeAttr('data-toggle');
			$('#cancel_dieteticCareNotes').data('oper','add');
			$('#new_dieteticCareNotes,#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes').data('oper','add');
			$("#new_dieteticCareNotes").attr('disabled',false);
			$('#save_dieteticCareNotes,#cancel_dieteticCareNotes,#edit_dieteticCareNotes').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes').data('oper','edit');
			$("#edit_dieteticCareNotes").attr('disabled',false);
			$('#save_dieteticCareNotes,#cancel_dieteticCareNotes,#new_dieteticCareNotes').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$("#save_dieteticCareNotes,#cancel_dieteticCareNotes").attr('disabled',false);
			$('#edit_dieteticCareNotes,#new_dieteticCareNotes').attr('disabled',true);
			break;
		case 'add_fup':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes_fup').data('oper','add_fup');
			$("#new_dieteticCareNotes_fup").attr('disabled',false);
			$('#save_dieteticCareNotes_fup,#cancel_dieteticCareNotes_fup,#edit_dieteticCareNotes_fup').attr('disabled',true);
			break;
		case 'edit_fup':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#cancel_dieteticCareNotes_fup').data('oper','edit_fup');
			$("#edit_dieteticCareNotes_fup").attr('disabled',false);
			$('#save_dieteticCareNotes_fup,#cancel_dieteticCareNotes_fup,#new_dieteticCareNotes_fup').attr('disabled',true);
			break;
		case 'wait_fup':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$("#save_dieteticCareNotes_fup,#cancel_dieteticCareNotes_fup").attr('disabled',false);
			$('#edit_dieteticCareNotes_fup,#new_dieteticCareNotes_fup').attr('disabled',true);
			break;
		case 'disable_ncase':
			$("#toggle_dieteticCareNotes").attr('data-toggle','collapse');
			$('#edit_dieteticCareNotes,#new_dieteticCareNotes,#save_dieteticCareNotes,#cancel_dieteticCareNotes').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_dieteticCareNotes_currpt(obj){	
	emptyFormdata(errorField,"#formDieteticCareNotes");

	//panel header
	$('#name_show_dieteticCareNotes').text(obj.Name);
	$('#mrn_show_dieteticCareNotes').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dieteticCareNotes').text((obj.Sex).toUpperCase());
	$('#dob_show_dieteticCareNotes').text(dob_chg(obj.DOB));
	$('#age_show_dieteticCareNotes').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dieteticCareNotes').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dieteticCareNotes').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_dieteticCareNotes').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_dieteticCareNotes').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_dieteticCareNotes').text(if_none(obj.areaDesc).toUpperCase());

	//formDieteticCareNotes
	$('#mrn_dieteticCareNotes').val(obj.MRN);
	$("#episno_dieteticCareNotes").val(obj.Episno);

	var saveParam={
        action:'get_table_dieteticCareNotes',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietncase);
			autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietfup);
			button_state_dieteticCareNotes('disable_ncase');
			button_state_dieteticCareNotes('edit_fup');
			disableFields_dieteticCareNotes();
        }else{
			button_state_dieteticCareNotes('add');
			button_state_dieteticCareNotes('add_fup');
        }

	});

	var urlparam_dietetic_date_tbl={
		action:'get_table_date_dietetic',
		mrn:$("#mrn_dieteticCareNotes").val(),
		episno:$("#episno_dieteticCareNotes").val(),
	}

    dietetic_date_tbl.ajax.url( "./dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
		emptyFormdata_div("#formDieteticCareNotes",['#mrn_dieteticCareNotes','#episno_dieteticCareNotes']);
		// $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
    });
	
}

function autoinsert_rowdata_dieteticCareNotes(form,rowData){
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

function saveForm_dieteticCareNotes(callback){
	var saveParam={
        action:'save_table_dieteticCareNotes',
        oper:$("#cancel_dieteticCareNotes").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDieteticCareNotes").serializeArray();
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_dieteticCareNotes_fup(callback){
	var saveParam={
        action:'save_table_dieteticCareNotes_fup',
        oper:$("#cancel_dieteticCareNotes_fup").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDieteticCareNotes").serializeArray();
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDieteticCareNotes input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





