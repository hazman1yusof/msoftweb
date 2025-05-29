
$(document).ready(function () {
	$('.menu .item').tab();
	$('.ui.radio.checkbox.pastcurr').checkbox({
		onChange: function() {
			var type = $(this).val();
	    	var dateParam_phys={
				action:'get_table_date_phys',
				type:type,
				mrn:$('#mrn_phys').val(),
				episno:$("#episno_phys").val(),
			}

		    phys_date_tbl.ajax.url( "./ptcare_phys/table?"+$.param(dateParam_phys) ).load(function(data){
				// emptyFormdata_div("#formphys",['#mrn_phys','#episno_phys']);
				// $('#phys_date_tbl tbody tr:eq(0)').click();	//to select first row
		    });
	    }
	});

	$("button.refreshbtn_phys").click(function(){
		populate_phys(selrowData('#jqGrid'));
	});

	$('a.ui.card.bodydia').click(function(){
		let mrn = $('#mrn_phys').val();
		let episno = $("#episno_phys").val();
		let type = $(this).data('type');
		let istablet = $(window).width() <= 1024;

		if(mrn.trim() == '' || episno.trim() == '' || type.trim() == ''){
			alert('Please choose Patient First');
		}else if($('#save_phys').prop('disabled')){
			alert('Edit this patient first');
		}else{
			if(istablet){
				let filename = type+'_'+mrn+'_'+episno+'.pdf';
				let url = $('#urltodiagram').val() + filename;
				var win = window.open(url, '_blank');
			}else{
				var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=phys', '_blank');
			}

			if (win) {
			    win.focus();
			} else {
			    alert('Please allow popups for this website');
			}
		}
		
	});
	
	$('.ui.checkbox.rehab').checkbox({
		onChecked: function() {
			$('#category_phys').val('Rehabilitation');
			$('#category_phys_ncase').val('Rehabilitation');
			if($('.ui.checkbox.phys').checkbox('is checked')){
				$('.ui.checkbox.phys').checkbox('set unchecked');
			}
			phys_phase('rehab');
	    },
	    onUnchecked: function() {
			$('#category_phys').val('Physioteraphy');
			$('#category_phys_ncase').val('Physioteraphy');
			if($('.ui.checkbox.phys').checkbox('is unchecked')){
				$('.ui.checkbox.phys').checkbox('set checked');
			}
			phys_phase('phys');
	    },
	});
	$('.ui.checkbox.phys').checkbox({
		onChecked: function() {
			$('#category_phys').val('Physioteraphy');
			$('#category_phys_ncase').val('Physioteraphy');
			if($('.ui.checkbox.rehab').checkbox('is checked')){
				$('.ui.checkbox.rehab').checkbox('set unchecked');
			}
			phys_phase('phys');
	    },
	    onUnchecked: function() {
			$('#category_phys').val('Rehabilitation');
			$('#category_phys_ncase').val('Rehabilitation');
			if($('.ui.checkbox.rehab').checkbox('is unchecked')){
				$('.ui.checkbox.rehab').checkbox('set checked');
			}
			phys_phase('rehab');
	    },
	});

	function phys_phase(phase){
		if(phase == 'rehab'){
			$('#stats_rehab').show();
			$('#stats_physio').hide();
			$('a.item[data-tab=subass]').click();
			$('a.item[data-tab=bodydiag]').hide();
		}else{
			$('#stats_physio').show();
			$('#stats_rehab').hide();
			$('a.item[data-tab=bodydiag]').show();
		}
	}

	$('.ui.checkbox.referdiet').checkbox({
		onChecked: function() {
			$('#referdiet_phys').val('yes');
			$('#referdiet_ncase').val('yes');
	    },
	    onUnchecked: function() {
			$('#referdiet_phys').val('no');
			$('#referdiet_ncase').val('no');
	    },

	});
	
	$('#tab_phys').on('show.bs.collapse', function (){
		return check_if_user_selected();
	})
	
	$('#tab_phys').on('shown.bs.collapse', function (){
		SmoothScrollTo('#tab_phys', 300);
		
		var postobj = {
			_token: $('#_token').val(),
			mrn: $('#mrn_phys').val(),
			episno: $("#episno_phys").val()
		};
		
		var dateParam_phys = {
			action: 'get_table_date_phys',
			type: 'Current',
			mrn: $('#mrn_phys').val(),
			episno: $("#episno_phys").val(),
			date: $('#sel_date').val()
		}
		
		phys_date_tbl.ajax.url("./ptcare_phys/table?"+$.param(dateParam_phys)).load();
		
		var phys_ncase_urlparam = {
			action: 'get_table_phys_ncase'
		};
		
		var postobj = {
			_token: $('#_token').val(),
			mrn: $('#mrn_phys').val(),
			episno: $("#episno_phys").val(),
		};
		
		$.post("./ptcare_phys/form?"+$.param(phys_ncase_urlparam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).done(function (data){
			if(!$.isEmptyObject(data.patrehab_ncase)){
				autoinsert_rowdata_phys_ncase("#formphys_ncase",data.patrehab_ncase);
				autoinsert_rowdata_phys_ncase("#formphys_ncase",data.pat_physio);
				button_state_phys_ncase('edit');
			}else{
				autoinsert_rowdata_phys_ncase("#formphys_ncase",data.pat_physio);
				button_state_phys_ncase('add');
			}
		});
	});
	
	// $('input.hidden[type=checkbox]').change(function(){
	// 	if($(this).val('Rehabilitation')){
	// 		if($(this).is(':checked')){
	// 			console.log($('input.hidden[type=checkbox][value=Physioteraphy]'))
	// 			$('input.hidden[type=checkbox][value=Physioteraphy]').prop('checked', true);
	// 		}
	// 	}else{
	// 		if($(this).is(':checked')){
	// 			$('input.hidden[type=checkbox][value=Rehabilitation]').prop('checked', true);
	// 		}
	// 	}
	// });

	disableForm('#formphys');

	// $("#new_phys").click(function(){
	// 	button_state_phys('wait');
	// 	enableForm('#formphys');
	// 	rdonly('#formphys');
	// 	// dialog_mrn_edit.on();
		
	// });

	// $("#edit_phys").click(function(){
	// 	button_state_phys('wait');
	// 	enableForm('#formphys');
	// 	rdonly('#formphys');
	// 	// disableFields_phys();
	// 	// dialog_mrn_edit.on();
		
	// });

	// $("#save_phys").click(function(){
	// 	// disableForm('#formphys');

	// 	if($('#category_phys').val().trim() == "" ){
	// 		alert('Please select either Rehabilitation or Physioteraphy');
	// 	}else if( $('#formphys').isValid({requiredFields: ''}, conf, true) ) {
	// 		saveForm_phys(function(){
	// 			$("#cancel_phys").data('oper','edit');
	// 			$("#cancel_phys").click();
	// 			button_state_phys('edit');
	// 			var dateParam_phys={
	// 				action:'get_table_date_phys',
	// 				type:$('.ui.radio.checkbox').val(),
	// 				mrn:$('#mrn_phys').val(),
	// 				episno:$("#episno_phys").val(),
	// 			}

	// 		    phys_date_tbl.ajax.url( "./phys/table?"+$.param(dateParam_phys) ).load(function(data){
	// 				// emptyFormdata_div("#formphys",['#mrn_phys','#episno_phys']);
	// 				// $('#phys_date_tbl tbody tr:eq(0)').click();	//to select first row
	// 		    });
	// 		});
	// 	}else{
	// 		enableForm('#formphys');
	// 		rdonly('#formphys');
	// 	}

	// });

	// $("#cancel_phys").click(function(){
	// 	disableForm('#formphys');
	// 	button_state_phys($(this).data('oper'));
	// 	// dialog_mrn_edit.off();

	// });

	// button_state_phys('empty');

	$('#phys_date_tbl tbody').on('click', 'tr', function () { 
	    var data = phys_date_tbl.row( this ).data();

		if(data == undefined){
			return;
		}

		//to highlight selected row
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		}else {
			phys_date_tbl.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}

		disableForm('#formphys');
		emptyFormdata_div("#formphys",['#mrn_phys','#episno_phys']);
	    $('#phys_date_tbl tbody tr').removeClass('active');
	    $(this).addClass('active');

	    var urlParam={
	        action:'get_table_phys',
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	mrn:data.mrn,
	    	episno:data.episno,
	    };

	    $.post( "./ptcare_phys/form?"+$.param(urlParam), $.param(postobj), function( data ) {
	        
	    },'json').fail(function(data) {
	        alert('there is an error');
	    }).done(function(data){
	    	if(!$.isEmptyObject(data)){
	    		if(!$.isEmptyObject(data.patrehab)){
					autoinsert_rowdata_phys("#formphys",data.patrehab);
	    		}
	    		if(!$.isEmptyObject(data.patrehabncase)){
					autoinsert_rowdata_phys("#formphys",data.patrehabncase);
	    		}
				// button_state_phys('edit');
	        }else{
				// button_state_phys('add');
	        }

	    });

	});
});

function saveForm_phys(callback){
	var saveParam={
        action:'save_table_phys',
        oper:$("#cancel_phys").data('oper')
    }

    var postobj={
    	_token : $('#_token').val(),
    };

	values = $("#formphys").serializeArray();

    $.post( "./ptcare_phys/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).done(function(data){
        callback();
    });
}

// function button_state_phys(state){
// 	switch(state){
// 		case 'empty':
// 			// $("#toggle_phys").removeAttr('data-toggle');
// 			$('#cancel_phys').data('oper','add');
// 			$('#new_phys,#save_phys,#cancel_phys,#edit_phys').attr('disabled',true);
// 			break;
// 		case 'add':
// 			// $("#toggle_phys").attr('data-toggle','collapse');
// 			$('#cancel_phys').data('oper','add');
// 			$("#new_phys").attr('disabled',false);
// 			$('#save_phys,#cancel_phys,#edit_phys').attr('disabled',true);
// 			break;
// 		case 'edit':
// 			// $("#toggle_phys").attr('data-toggle','collapse');
// 			$('#cancel_phys').data('oper','edit');
// 			$("#edit_phys").attr('disabled',false);
// 			$('#save_phys,#cancel_phys,#new_phys').attr('disabled',true);
// 			break;
// 		case 'wait':
// 			// $("#toggle_phys").attr('data-toggle','collapse');
// 			$("#save_phys,#cancel_phys").attr('disabled',false);
// 			$('#edit_phys,#new_phys').attr('disabled',true);
// 			break;
// 	}

// }

var phys_date_tbl = $('#phys_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging":false,
    "columns": [
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'date', 'width': '60%'},
        {'data': 'adduser'},
    ]
    ,columnDefs: [
        { targets: [0, 1, 3], visible: false},
    ],
    "drawCallback": function( settings ) {
    	if(settings.aoData.length>0){
    		$(this).find('tbody tr')[0].click();
    	}else{
    		if(selrowData('#jqGrid').length != 0){
    			// button_state_phys('add');
    		}
    	}
    }
});

function empty_currphys(){
	emptyFormdata_div("#formphys",['#mrn_phys','#episno_phys']);
	empty_currphys_ncase();
	$('.ui.checkbox.box').checkbox('set unchecked');
	// button_state_phys('empty');

	//panel header
	$('#name_show_phys').text('');
	$('#mrn_show_phys').text('');
	$('#sex_show_phys').text('');
	$('#dob_show_phys').text('');
	$('#age_show_phys').text('');
	$('#race_show_phys').text('');
	$('#religion_show_phys').text('');
	$('#occupation_show_phys').text('');
	$('#citizenship_show_phys').text('');
	$('#area_show_phys').text('');

	//formphys
	$('#mrn_phys').val('');
	$("#episno_phys").val('');

	phys_date_tbl.clear().draw();
}

function populate_phys(obj){
	emptyFormdata_div("#formphys",['#mrn_phys','#episno_phys']);
	$('.ui.checkbox.box').checkbox('set unchecked');
	
	// panel header
	$('#name_show_phys').text(obj.Name);
	$('#mrn_show_phys').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_phys').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_phys').text(dob_chg(obj.DOB));
	$('#age_show_phys').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_phys').text(if_none(obj.RaceCode).toUpperCase());
	$('#religion_show_phys').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_phys').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_phys').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_phys').text(if_none(obj.AreaCode).toUpperCase());
	$('#stats_rehab').text(obj.stats_rehab.toUpperCase());
	$('#stats_physio').text(obj.stats_physio.toUpperCase());
	
	// formphys
	$('#mrn_phys').val(obj.MRN);
	$("#episno_phys").val(obj.Episno);
	
	populate_phys_ncase(obj);
	
	$("#tab_phys").collapse('hide');
}

function autoinsert_rowdata_phys(form,rowData){
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