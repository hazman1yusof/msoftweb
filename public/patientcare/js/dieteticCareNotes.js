
$(document).ready(function () {

	disableForm('#formDieteticCareNotes');
	disableForm('#formDieteticCareNotes_fup');

	$("button.refreshbtn_diet").click(function(){
		populate_dieteticCareNotes_currpt(selrowData('#jqGrid'));
	});

	$("#new_dieteticCareNotes").click(function(){
		$('#stats_diet').text('ATTEND');
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dieteticCareNotes").click(function(){
		$('#stats_diet').text('ATTEND');
		button_state_dieteticCareNotes('wait');
		enableForm('#formDieteticCareNotes');
		rdonly('#formDieteticCareNotes');
		// disableFields_dieteticCareNotes();
		// dialog_mrn_edit.on();
		
	});

	$("#save_dieteticCareNotes").click(function(){
		// disableForm('#formDieteticCareNotes');
		if( $('#formDieteticCareNotes').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dieteticCareNotes(function(){
				$("#cancel_dieteticCareNotes").data('oper','edit');
				$("#cancel_dieteticCareNotes").click();
				$('#stats_diet').text('SEEN');
				button_state_dieteticCareNotes('edit');
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
		$("#cancel_dieteticCareNotes_fup").data('oper','add_fup');
		button_state_dieteticCareNotes('wait_fup');
		emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
		enableForm('#formDieteticCareNotes_fup');
		rdonly('#formDieteticCareNotes_fup');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_dieteticCareNotes_fup").click(function(){
		$("#cancel_dieteticCareNotes_fup").data('oper','edit_fup');
		button_state_dieteticCareNotes('wait_fup');
		enableForm('#formDieteticCareNotes_fup');
		rdonly('#formDieteticCareNotes_fup');
		// disableFields_dieteticCareNotes();
		// dialog_mrn_edit.on();
		
	});

	$("#save_dieteticCareNotes_fup").click(function(){
		var urlparam_dietetic_date_tbl={
			action:'get_table_date_dietetic',
			mrn:$("#mrn_dieteticCareNotes_fup").val(),
			episno:$("#episno_dieteticCareNotes_fup").val(),
		}

		// disableForm('#formDieteticCareNotes_fup');
		if( $('#formDieteticCareNotes_fup').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_dieteticCareNotes_fup(function(){
				$("#cancel_dieteticCareNotes_fup").data('oper','edit_fup');
				$("#cancel_dieteticCareNotes_fup").click();
				dietetic_date_tbl.ajax.url( "./ptcare_dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
					emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
					// $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
			    });
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDieteticCareNotes_fup');
			rdonly('#formDieteticCareNotes_fup');
		}

	});

	$("#cancel_dieteticCareNotes_fup").click(function(){
		disableForm('#formDieteticCareNotes_fup');
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

	//bmi calculator
	$('#ncase_height').keyup(function(event) {
		getBMI_ncase();
	});

	$('#ncase_weight').keyup(function(event) {
		getBMI_ncase();
	});

	$('#fup_height').keyup(function(event) {
		getBMI_fup();
	});

	$('#fup_weight').keyup(function(event) {
		getBMI_fup();
	});
	//bmi calculator ends
	$("#tab_diet").on("show.bs.collapse", function(){
		return check_if_user_selected();
	});

	$("#tab_diet").on("shown.bs.collapse", function(){
		SmoothScrollTo("#tab_diet", 300);
		$("#jqGrid_trans_diet").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_diet_c")[0].offsetWidth-$("#jqGrid_trans_diet_c")[0].offsetLeft-14));


		var urlParam={
	        action:'get_table_dieteticCareNotes',
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	mrn:$("#mrn_dieteticCareNotes_fup").val(),
	    	episno:$("#episno_dieteticCareNotes_fup").val()
	    };

	    $.post( "./ptcare_dieteticCareNotes/form?"+$.param(urlParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).done(function(data){
	    	if(!$.isEmptyObject(data)){
				autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietncase);
				button_state_dieteticCareNotes('edit');
				getBMI_ncase();
	        }else{
				button_state_dieteticCareNotes('add');
	        }

		});

		var urlparam_dietetic_date_tbl={
			action:'get_table_date_dietetic',
			mrn:$("#mrn_dieteticCareNotes_fup").val(),
			episno:$("#episno_dieteticCareNotes_fup").val(),
		}

	    dietetic_date_tbl.ajax.url( "./ptcare_dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
			emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
			// $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
	    });

	  //   dietetic_date_tbl.ajax.url( "./dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
			// emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
			// // $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
	  //   });
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

		disableForm('#formDieteticCareNotes_fup');
		emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
	    $('#dietetic_date_tbl tbody tr').removeClass('active');
	    $(this).addClass('active');

	    var saveParam={
	        action:'get_table_dieteticCareNotes_fup',
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	mrn:data.mrn,
	    	episno:data.episno,
	    	date:data.date
	    };

	    $.post( "./ptcare_dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).done(function(data){
	    	if(!$.isEmptyObject(data)){
				autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes_fup",data.patdietfup);
				button_state_dieteticCareNotes('edit_fup');
				getBMI_fup();
				// disableFields_dieteticCareNotes();
	        }else{
				button_state_dieteticCareNotes('add_fup');
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
    	if(settings.aoData.length>0){
    	$(this).find('tbody tr')[0].click();
    	}else{
    		if(selrowData('#jqGrid').length != 0){
    			button_state_dieteticCareNotes('add_fup');
    		}
    	}
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

//bmi calculator
function getBMI_ncase() {
    var ncase_height = parseFloat($("#ncase_height").val());
    var ncase_weight = parseFloat($("#ncase_weight").val());

	var ncase_myBMI = (ncase_weight / ncase_height / ncase_height) * 10000;

    var ncase_bmi = ncase_myBMI.toFixed(2);
    var ncase_ibw = ncase_bmi * Math.pow(ncase_height/100, 2)

    if (isNaN(ncase_bmi)) ncase_bmi = 0;
    if (isNaN(ncase_ibw)) ncase_ibw = 0;

    $('#ncase_bmi').val(ncase_bmi);
    $('#ncase_ibw').val(ncase_ibw.toFixed(2));
}

function getBMI_fup() {
    var fup_height = parseFloat($("#fup_height").val());
    var fup_weight = parseFloat($("#fup_weight").val());

	var fup_myBMI = (fup_weight / fup_height / fup_height) * 10000;

    var fup_bmi = fup_myBMI.toFixed(2);
    var fup_ibw = fup_bmi * Math.pow(fup_height/100, 2)

    if (isNaN(fup_bmi)) fup_bmi = 0;
    if (isNaN(fup_ibw)) fup_ibw = 0;

    $('#fup_bmi').val(fup_bmi);
    $('#fup_ibw').val(fup_ibw.toFixed(2));
}
//bmi calculator ends

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
	empty_transaction_diet('add');
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
			hide_tran_button_diet(false);
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
		case 'empty_fup':
			$("#toggle_dieteticCareNotes").removeAttr('data-toggle');
			$('#cancel_dieteticCareNotes_fup').data('oper','add_fup');
			$('#new_dieteticCareNotes_fup,#save_dieteticCareNotes_fup,#cancel_dieteticCareNotes_fup,#edit_dieteticCareNotes_fup').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_dieteticCareNotes_currpt(obj){
	emptyFormdata_div("#formDieteticCareNotes");
	emptyFormdata_div("#formDieteticCareNotes_fup");
	
	// panel header
	$('#name_show_dieteticCareNotes').text(obj.Name);
	$('#mrn_show_dieteticCareNotes').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dieteticCareNotes').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_dieteticCareNotes').text(dob_chg(obj.DOB));
	$('#age_show_dieteticCareNotes').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dieteticCareNotes').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dieteticCareNotes').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_dieteticCareNotes').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_dieteticCareNotes').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_dieteticCareNotes').text(if_none(obj.AreaCode).toUpperCase());
	$('#stats_diet').text(obj.stats_diet.toUpperCase());
	
	// formDieteticCareNotes
	$('#mrn_dieteticCareNotes,#mrn_dieteticCareNotes_fup').val(obj.MRN);
	$("#episno_dieteticCareNotes,#episno_dieteticCareNotes_fup").val(obj.Episno);
	
	$('#stats_diet').show();
	
	$("#tab_diet").collapse('hide');
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
    	_token : $('#_token').val(),
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

    $.post( "./ptcare_dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).done(function(data){
        callback();
    });
}

function saveForm_dieteticCareNotes_fup(callback){
	var saveParam={
        action:'save_table_dieteticCareNotes_fup',
        oper:$("#cancel_dieteticCareNotes_fup").data('oper'),
    }
    var postobj={
    	_token : $('#_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formDieteticCareNotes_fup").serializeArray();
	
	values = values.concat(
        $('#formDieteticCareNotes_fup input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes_fup input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formDieteticCareNotes_fup input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formDieteticCareNotes_fup select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./ptcare_dieteticCareNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).done(function(data){
        callback();
    });
}

function empty_dietcarenote(){
	emptyFormdata_div("#formDieteticCareNotes");
	emptyFormdata_div("#formDieteticCareNotes_fup");
	button_state_dieteticCareNotes('empty');
	button_state_dieteticCareNotes('empty_fup');

	//panel header
	$('#name_show_dieteticCareNotes').text('');
	$('#mrn_show_dieteticCareNotes').text('');
	$('#sex_show_dieteticCareNotes').text('');
	$('#dob_show_dieteticCareNotes').text('');
	$('#age_show_dieteticCareNotes').text('');
	$('#race_show_dieteticCareNotes').text('');
	$('#religion_show_dieteticCareNotes').text('');
	$('#occupation_show_dieteticCareNotes').text('');
	$('#citizenship_show_dieteticCareNotes').text('');
	$('#area_show_dieteticCareNotes').text('');

	//formDieteticCareNotes
	$('#mrn_dieteticCareNotes,#mrn_dieteticCareNotes_fup').val('');
	$("#episno_dieteticCareNotes,#episno_dieteticCareNotes_fup").val('');

	dietetic_date_tbl.clear().draw();
}






