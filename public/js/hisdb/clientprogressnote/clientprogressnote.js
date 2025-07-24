
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
	
	textarea_init_clientProgNote();
	
	var fdl = new faster_detail_load();
	
	disableForm('#formClientProgNote');
	
	$("#new_clientProgNote").click(function (){
		$('#clientprognote_date_tbl tbody tr').removeClass('active');
		$('#cancel_clientProgNote').data('oper','add');
		button_state_clientProgNote('wait');
		enableForm('#formClientProgNote');
		rdonly('#formClientProgNote');
		emptyFormdata_div("#formClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote','#datetime_clientProgNote','#epistycode_clientProgNote']);
		$('#clientProgNote_datetaken').prop('disabled',false);
	});
	
	$("#edit_clientProgNote").click(function (){
		button_state_clientProgNote('wait');
		enableForm('#formClientProgNote');
		rdonly('#formClientProgNote');
		$('#clientProgNote_datetaken').prop('disabled',true);
	});
	
	$("#save_clientProgNote").click(function (){
		disableForm('#formClientProgNote');
		if($('#formClientProgNote').isValid({requiredFields: ''}, conf, true)){
			saveForm_clientProgNote(function (data){
				// $("#cancel_clientProgNote").click();
				clientprognote_date_tbl.ajax.url("./clientprogressnote/table?"+$.param(dateParam_clientprognote)).load(function (){
					clientprognote_date_tbl.rows().every(function (rowIdx, tableLoop, rowLoop){
						var currow = this.data();
						let curr_mrn = currow.mrn;
						let curr_episno = currow.episno;
						let curr_date = currow.date;
						if(curr_mrn == data.mrn && curr_episno == data.episno && curr_date == data.datetime){
							$(this.node()).addClass('active');
						}
					});
				});
			});
		}else{
			enableForm('#formClientProgNote');
			rdonly('#formClientProgNote');
		}
	});
	
	$("#cancel_clientProgNote").click(function (){
		disableForm('#formClientProgNote');
		button_state_clientProgNote($(this).data('oper'));
		$('#clientprognote_date_tbl tbody tr:eq(0)').click(); // to select first row
	});
	
	///////////////////////////////////////////Referral Letter///////////////////////////////////////////
	var oper_refletterClientProgNote = '';
	// var oper_refletterClientProgNote = 'add';
	$("#dialogFormRefClientProgNote")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				parent_close_disabled(true);
				disableForm('#form_refLetterClientProgNote');
				disableForm('#form_docNoteRefClientProgNote');
				textarea_init_clientProgNote();
				switch(oper_refletterClientProgNote) {
					case state = 'add':
						// $(this).dialog("option", "title", "Add");
						enableForm('#form_refLetterClientProgNote');
						rdonly('#form_refLetterClientProgNote');
						break;
					case state = 'edit':
						// $(this).dialog("option", "title", "Edit");
						enableForm('#form_refLetterClientProgNote');
						rdonly('#form_refLetterClientProgNote');
						break;
					case state = 'view':
						// $(this).dialog("option", "title", "View");
						disableForm('#form_refLetterClientProgNote');
						rdonly("#form_refLetterClientProgNote");
						// $(this).dialog("option", "buttons", butt2);
						break;
				}
				if(oper_refletterClientProgNote != 'view'){
					
				}
				if(oper_refletterClientProgNote != 'add'){
					
				}
			},
			close: function (event, ui){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#form_refLetterClientProgNote');
				emptyFormdata(errorField,'#form_docNoteRefClientProgNote');
				dialog_icd_ref.off();
				// $('.alert').detach();
				$('.my-alert').detach();
				if(oper_refletterClientProgNote == 'view'){
					// $(this).dialog("option", "buttons", butt1);
				}
			},
		});
	
	$("#referLetterClientProgNote").click(function (){
		// oper_refletterClientProgNote = 'add';
		$("#dialogFormRefClientProgNote").dialog("open");
		populate_refLetterClientProgNote();
	});
	
	$("#new_refLetterClientProgNote").click(function (){
		$('#cancel_refLetterClientProgNote').data('oper','add');
		button_state_refLetterClientProgNote('wait');
		enableForm('#form_refLetterClientProgNote');
		rdonly('#form_refLetterClientProgNote');
		// emptyFormdata_div("#form_refLetterClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote']);
	});
	
	$("#edit_refLetterClientProgNote").click(function (){
		button_state_refLetterClientProgNote('wait');
		enableForm('#form_refLetterClientProgNote');
		rdonly('#form_refLetterClientProgNote');
	});
	
	$("#save_refLetterClientProgNote").click(function (){
		disableForm('#form_refLetterClientProgNote');
		if($('#form_refLetterClientProgNote').isValid({requiredFields: ''}, conf, true)){
			saveForm_refLetterClientProgNote(function (data){
				// emptyFormdata_div("#form_refLetterClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote']);
				// disableForm('#form_refLetterClientProgNote');
				$('#cancel_refLetterClientProgNote').data('oper','edit');
				$("#cancel_refLetterClientProgNote").click();
			});
		}else{
			enableForm('#form_refLetterClientProgNote');
			rdonly('#form_refLetterClientProgNote');
		}
	});
	
	$("#cancel_refLetterClientProgNote").click(function (){
		// emptyFormdata_div("#form_refLetterClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote']);
		disableForm('#form_refLetterClientProgNote');
		button_state_refLetterClientProgNote($(this).data('oper'));
	});
	
});

var errorField = [];
conf = {
	modules: 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form){
		if(errorField.length > 0){
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

button_state_clientProgNote('empty');
function button_state_clientProgNote(state){
	if($('#isdoctor').val() != '1'){
		$("#toggle_clientProgNote").removeAttr('data-toggle');
		$('#cancel_clientProgNote').data('oper','add');
		$('#new_clientProgNote,#save_clientProgNote,#cancel_clientProgNote,#edit_clientProgNote').attr('disabled',true);
		return 0;
	}
	
	switch(state){
		case 'empty':
			$("#toggle_clientProgNote").removeAttr('data-toggle');
			$('#cancel_clientProgNote').data('oper','add');
			$('#new_clientProgNote,#save_clientProgNote,#cancel_clientProgNote,#edit_clientProgNote').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_clientProgNote").attr('data-toggle','collapse');
			$('#cancel_clientProgNote').data('oper','add');
			$("#new_clientProgNote,#current,#past").attr('disabled',false);
			$('#save_clientProgNote,#cancel_clientProgNote,#edit_clientProgNote').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_clientProgNote").attr('data-toggle','collapse');
			$('#cancel_clientProgNote').data('oper','edit');
			$("#edit_clientProgNote,#new_clientProgNote").attr('disabled',false);
			$('#save_clientProgNote,#cancel_clientProgNote').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_clientProgNote").attr('data-toggle','collapse');
			$("#save_clientProgNote,#cancel_clientProgNote").attr('disabled',false);
			$('#edit_clientProgNote,#new_clientProgNote').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_clientProgNote").attr('data-toggle','collapse');
			$('#new_clientProgNote,#edit_clientProgNote,#save_clientProgNote,#cancel_clientProgNote').attr('disabled',true);
			break;
	}
}

// button_state_refLetterClientProgNote('empty');
function button_state_refLetterClientProgNote(state){
	switch(state){
		case 'empty':
			$("#toggle_refLetterClientProgNote").removeAttr('data-toggle');
			$('#cancel_refLetterClientProgNote').data('oper','add');
			$('#new_refLetterClientProgNote,#save_refLetterClientProgNote,#cancel_refLetterClientProgNote,#edit_refLetterClientProgNote').attr('disabled',true);
			$('#refLetterClientProgNote_chart').attr('disabled',false);
			break;
		case 'add':
			$("#toggle_refLetterClientProgNote").attr('data-toggle','collapse');
			$('#cancel_refLetterClientProgNote').data('oper','add');
			$("#new_refLetterClientProgNote,#refLetterClientProgNote_chart").attr('disabled',false);
			$('#save_refLetterClientProgNote,#cancel_refLetterClientProgNote,#edit_refLetterClientProgNote').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_refLetterClientProgNote").attr('data-toggle','collapse');
			$('#cancel_refLetterClientProgNote').data('oper','edit');
			$("#edit_refLetterClientProgNote,#refLetterClientProgNote_chart").attr('disabled',false);
			$('#save_refLetterClientProgNote,#cancel_refLetterClientProgNote,#new_refLetterClientProgNote').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_refLetterClientProgNote").attr('data-toggle','collapse');
			$("#save_refLetterClientProgNote,#cancel_refLetterClientProgNote,#refLetterClientProgNote_chart").attr('disabled',false);
			$('#edit_refLetterClientProgNote,#new_refLetterClientProgNote').attr('disabled',true);
			break;
	}
}

var dateParam_clientprognote,doctornote_clientprognote,curr_obj_clientprognote;
//screen current patient//
function populate_clientProgNote_currpt(obj){
	curr_obj_clientprognote = obj;
	
	// emptyFormdata(errorField,"#formClientProgNote",["#mrn_clientProgNote","#episno_clientProgNote","#datetime_clientProgNote","#epistycode_clientProgNote"]);
	emptyFormdata(errorField,"#formClientProgNote",["#epistycode_clientProgNote"]);
	
	// panel header
	$('#name_show_clientProgNote').text(obj.Name);
	$('#mrn_show_clientProgNote').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_clientProgNote').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_clientProgNote').text(dob_chg(obj.DOB));
	$('#age_show_clientProgNote').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_clientProgNote').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_clientProgNote').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_clientProgNote').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_clientProgNote').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_clientProgNote').text(if_none(obj.areaDesc).toUpperCase());
	
	// formClientProgNote
	$('#mrn_clientProgNote').val(obj.MRN);
	$("#episno_clientProgNote").val(obj.Episno);
	$("#age_clientProgNote").val(dob_age(obj.DOB));
	$('#ptname_clientProgNote').val(obj.Name);
	$('#preg_clientProgNote').val(obj.pregnant);
	$('#ic_clientProgNote').val(obj.Newic);
	$('#doctorname_clientProgNote').val(obj.q_doctorname);
	
	doctornote_clientprognote = {
		action: 'get_table_clientprognote',
		mrn: obj.MRN,
		episno: obj.Episno,
		datetime: ''
	};
	
	dateParam_clientprognote = {
		action: 'get_datetime_clientprognote',
		mrn: obj.MRN,
		episno: obj.Episno
	}
	
	button_state_clientProgNote('empty');
	
    // clientprognote_date_tbl.ajax.url("./clientprogressnote/table?"+$.param(dateParam_clientprognote)).load(function (data){
	// 	emptyFormdata_div("#formClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote','#datetime_clientProgNote','#epistycode_clientProgNote']);
	// 	$('#clientprognote_date_tbl tbody tr:eq(0)').click(); // to select first row
    // });
}

function populate_refLetterClientProgNote(obj){
	// emptyFormdata(errorField,"#form_refLetterClientProgNote");
	emptyFormdata(errorField,"#form_docNoteRefClientProgNote");
	
	$("#pt_mrnClientProgNote").text($('#mrn_clientProgNote').val());
	$("#pt_nameClientProgNote").text($('#ptname_clientProgNote').val());
	
	// $("#refLetterClientProgNote_chart").attr('href','./clientprogressnote/refLetterClientProgNote_chart?mrn='+$('#mrn_clientProgNote').val()+'&episno='+$("#episno_clientProgNote").val());
	
	$("#refLetterClientProgNote_chart").click(function (){
		// window.location='./clientprogressnote/refLetterClientProgNote_chart?mrn='+$('#mrn_clientProgNote').val()+'&episno='+$("#episno_clientProgNote").val();
		window.open('./clientprogressnote/refLetterClientProgNote_chart?mrn='+$('#mrn_clientProgNote').val()+'&episno='+$("#episno_clientProgNote").val(), '_blank');
	});
	
	var urlparam = {
		action: 'get_table_refLetterClientProgNote',
		mrn: $('#mrn_clientProgNote').val(),
		episno: $("#episno_clientProgNote").val(),
		datetime: $('#datetime_clientProgNote').val(),
		reftype: 'ClientProgNote'
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: $('#mrn_clientProgNote').val(),
		episno: $("#episno_clientProgNote").val()
	};
	
	$.post("./clientprogressnote/form?"+$.param(urlparam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).done(function (data){
		if(!$.isEmptyObject(data.patreferral)){
			button_state_refLetterClientProgNote('edit');
			if(!emptyobj_(data.patreferral))autoinsert_rowdata("#form_refLetterClientProgNote",data.patreferral);
			
			if(!$.isEmptyObject(data.patreferral.reftitle)){
				$('#form_refLetterClientProgNote textarea[name=reftitle]').text(data.patreferral.reftitle); // from patreferral
			}else{
				$('#form_refLetterClientProgNote textarea[name=reftitle]').text(data.sys_reftitle); // from sysparam
			}
		}else{
			button_state_refLetterClientProgNote('add');
			$('#form_refLetterClientProgNote textarea[name=reftitle]').text(data.sys_reftitle);
		}
		$("#refadduserClientProgNote").val(data.adduser);
		
		if(!emptyobj_(data.episode))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.episode);
		if(!emptyobj_(data.pathealth))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.pathealth);
		if(!emptyobj_(data.pathistory))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.pathistory);
		// if(!emptyobj_(data.patexam))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.patexam);
		if(!emptyobj_(data.episdiag))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.episdiag);
		if(!emptyobj_(data.patprogressnote))autoinsert_rowdata("#form_docNoteRefClientProgNote",data.patprogressnote);
		// if(!emptyobj_(data.pathealth))$('#form_docNoteRefClientProgNote span#doctorcode').text(data.pathealth.doctorcode);
		textarea_init_doctornote_ref();
		
	});
}

function autoinsert_rowdata(form,rowData){
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

function saveForm_clientProgNote(callback){
	if($("#cancel_clientProgNote").data('oper') == 'edit'){
		$('#clientprognote_date_tbl').data('editing','true');
	}
	
	var saveParam = {
		action: 'save_table_clientprognote',
		oper: $("#cancel_clientProgNote").data('oper'),

	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formClientProgNote").serializeArray();
	
	values = values.concat(
		$('#formClientProgNote input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#formClientProgNote input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#formClientProgNote input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#formClientProgNote select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	$.post("./clientprogressnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').fail(function (data){
		callback(data);
	}).success(function (data){
		callback(data);
	});
}

function saveForm_refLetterClientProgNote(callback){
	var saveParam = {
		action: 'save_refLetterClientProgNote',
		oper: $("#cancel_refLetterClientProgNote").data('oper'),
		mrn: $('#mrn_clientProgNote').val(),
		episno: $("#episno_clientProgNote").val(),
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#form_refLetterClientProgNote").serializeArray();
	
	values = values.concat(
		$('#form_refLetterClientProgNote input[type=checkbox]:not(:checked)').map(
		function (){
			return {"name": this.name, "value": 0}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetterClientProgNote input[type=checkbox]:checked').map(
		function (){
			return {"name": this.name, "value": 1}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetterClientProgNote input[type=radio]:checked').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values = values.concat(
		$('#form_refLetterClientProgNote select').map(
		function (){
			return {"name": this.name, "value": this.value}
		}).get()
	);
	
	values.push({
        name: 'reftype',
        value: $('#form_refLetterClientProgNote input[name=reftype]').val()
    });

	$.post("./clientprogressnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').fail(function (data){
		callback(data);
	}).success(function (data){
		callback(data);
	});
}

var clientprognote_date_tbl = $('#clientprognote_date_tbl').DataTable({
	"ajax": "",
	"sDom": "",
	"paging": false,
	"columns": [
		{'data': 'mrn'},
		{'data': 'episno'},
		{'data': 'date', 'width': '60%'},
		{'data': 'recdatetime'},
		{'data': 'adduser'},
		{'data': 'doctorname', 'width': '30%'},
	],
	columnDefs: [
		{targets: [0, 1, 3, 4], visible: false},
	],
	"order": [[ 3, "desc" ]],
	"drawCallback": function (settings){
		if($(this).data('editing') == 'true'){
			$(this).data('editing','false') // tak perlu click kalau edit
			button_state_clientProgNote('edit');
		}else{
			$(this).find('tbody tr')[0].click();
		}
	}
});

var ajaxurl;
$('#jqGridClientProgNote_panel').on('shown.bs.collapse', function (){
	sticky_clientprognotetbl(on = true);
	clientprognote_date_tbl.ajax.url("./clientprogressnote/table?"+$.param(dateParam_clientprognote)).load(function (data){
		emptyFormdata_div("#formClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote','#datetime_clientProgNote','#epistycode_clientProgNote']);
		$('#clientprognote_date_tbl tbody tr:eq(0)').click(); // to select first row
	});
	SmoothScrollTo("#jqGridClientProgNote_panel", 500);
	textarea_init_clientProgNote();
});

$("#jqGridClientProgNote_panel").on("hide.bs.collapse", function (){
	button_state_clientProgNote('empty');
	disableForm('#formClientProgNote');
});

$('#clientprognote_date_tbl tbody').on('click', 'tr', function (){
	var data = clientprognote_date_tbl.row(this).data();
	disableForm('#formClientProgNote');
	// console.log($(this).hasClass('selected'));
	
	// if(disable_edit_date()){
	// 	return;
	// }else
	
	if(data == undefined){
		button_state_clientProgNote('add');
		
		return false;
	}
	
	// to highlight selected row
	if($(this).hasClass('selected')){
		$(this).removeClass('selected');
	}else{
		clientprognote_date_tbl.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
	}
	
	emptyFormdata_div("#formClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote','#datetime_clientProgNote','#epistycode_clientProgNote']);
	$('#clientprognote_date_tbl tbody tr').removeClass('active');
	$(this).addClass('active');
	
	if(check_same_usr_edit(data)){
		button_state_clientProgNote('edit');
	}else{
		button_state_clientProgNote('add');
	}
	
	$('#mrn_clientProgNote').val(data.mrn);
	$("#episno_clientProgNote").val(data.episno);
	$("#datetime_clientProgNote").val(data.recdatetime);
	
	doctornote_clientprognote.mrn = data.mrn;
	doctornote_clientprognote.episno = data.episno;
	doctornote_clientprognote.datetime = data.recdatetime;
	
	$.get("./clientprogressnote/table?"+$.param(doctornote_clientprognote), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data)){
			// if(!emptyobj_(data.episode))autoinsert_rowdata("#formClientProgNote",data.episode);
			if(!emptyobj_(data.patprogressnote))autoinsert_rowdata("#formClientProgNote",data.patprogressnote);
			
			textarea_init_clientProgNote();
		}else{
			
		}
	});
});

function disable_edit_date(){
	let disabled = false;
    let newact = $('#new_clientProgNote').attr('disabled');
	let data_oper = $('#cancel_clientProgNote').data('oper');
	
    if(newact == 'disabled' && data_oper == 'add'){
    	disabled = true;
    }
    return disabled;
}

function check_same_usr_edit(data){
	let same = true;
	var adduser = data.adduser;
	
	if(adduser == null){
		same = false;
	}else if(adduser.toUpperCase() != $('#curr_user_clientProgNote').val().toUpperCase()){
		same = false;
	}
	
	return same;
}

function sticky_clientprognotetbl(on){
	$(window).off('scroll');
	if(on){
		var topDistance = $('#clientprognote_date_tbl_sticky').offset().top;
		$(window).on('scroll', function (){
		    var scrollTop = $(this).scrollTop();
			var bottomDistance = $('#jqGrid_ordcom_c').offset().top;
		    if((topDistance+10) < scrollTop && (bottomDistance-280) > scrollTop){
		    	$('#clientprognote_date_tbl_sticky').addClass( "sticky_div" );
		    }else{
		    	$('#clientprognote_date_tbl_sticky').removeClass( "sticky_div" );
		    }
		});
	}else{
		$(window).off('scroll');
	}
}

function textarea_init_clientProgNote(){
	$('textarea#clientProgNote_progressnote,textarea#clientProgNote_plan,textare#clientProgNote_progressnoteRef').each(function (){
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

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight < 50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight > 300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }