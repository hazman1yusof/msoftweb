$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
	
	textare_init_triageED();
	
	var fdl = new faster_detail_load();
	
	disableForm('#formTriageInfoED');
	
	$("#new_tiED").click(function (){
		button_state_tiED('wait');
		enableForm('#formTriageInfoED');
		rdonly('#formTriageInfoED');
	});
	
	$("#edit_tiED").click(function (){
		button_state_tiED('wait');
		enableForm('#formTriageInfoED');
		rdonly('#formTriageInfoED');
		$("#admwardtime").attr("readonly", true);
	});
	
	$("#save_tiED").click(function (){
		disableForm('#formTriageInfoED');
		if($('#formTriageInfoED').isValid({requiredFields: ''}, conf, true)){
            saveForm_tiED(function (){
                $("#cancel_tiED").data('oper','edit');
                $("#cancel_tiED").click();
            });
		}else{
			enableForm('#formTriageInfoED');
			rdonly('#formTriageInfoED');
		}
	});
	
	$("#cancel_tiED").click(function (){
		disableForm('#formTriageInfoED');
		button_state_tiED($(this).data('oper'));
	});
	
	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function (){
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});
	
	// to autocheck the checkbox bila fill in textarea
	$("#drugs_remarks").on("keyup blur", function (){
		$("#allergydrugs").prop("checked", this.value !== "");
	});
	
	// $("#plaster_remarks").on("keyup blur", function (){
	// 	$("#allergyplaster").prop("checked", this.value !== "");
	// });
	
	$("#food_remarks").on("keyup blur", function (){
		$("#allergyfood").prop("checked", this.value !== "");
	});
	
	// $("#environment_remarks").on("keyup blur", function (){
	// 	$("#allergyenvironment").prop("checked", this.value !== "");
	// });
	
	$("#others_remarks").on("keyup blur", function (){
		$("#allergyothers").prop("checked", this.value !== "");
	});
	
	// $("#unknown_remarks").on("keyup blur", function (){
	// 	$("#allergyunknown").prop("checked", this.value !== "");
	// });
	
	// $("#none_remarks").on("keyup blur", function (){
	// 	$("#allergynone").prop("checked", this.value !== "");
	// });
	// to autocheck the checkbox bila fill in textarea ends
	
	$("#jqGridTriageInfoED_panel").on("show.bs.collapse", function (){
	});
	
	$("#jqGridTriageInfoED_panel").on("hide.bs.collapse", function (){
		button_state_tiED('empty');
		disableForm('#formTriageInfoED');
		$("#jqGridTriageInfoED_panel > div").scrollTop(0);
	});
	
	$('#jqGridTriageInfoED_panel').on('shown.bs.collapse', function (){
		SmoothScrollTo("#jqGridTriageInfoED_panel", 500);
		populate_triageED_currpt_getdata();
	});
	
	$('#jqGridTriageInfoED_panel').on('hidden.bs.collapse', function (){
	});

	function glasgow_coma_scale(){
		var score = 0;
		$(".calc:checked").each(function(){
			score+=parseInt($(this).val(),10);
		});
		$("#formTriageInfoED input[name=totgsc]").val(score)
	}
	$().ready(function(){
		$(".calc").change(function(){
			glasgow_coma_scale()
		});
	});


	$(".changeTextInputColorBP").on('change',function (){
		var age = $('#age_show_triageED').val();
		var vs_bp_sys1 = $("#formTriageInfoED input[name=vs_bp_sys1]").val();
		var vs_bp_dias2 = $("#formTriageInfoED input[name=vs_bp_dias2]").val();
	
		if (age >= 18) {
			// Adult cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				// console.log('high');
				$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");

			} else {
				$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
			}
		} else if ((age <= 17) && (age >=1)){
			// Pediatric cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");
			} else {
				$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
			}
		} else {
			// Neonatal cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");
			} else {
				$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
				$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
			}
		}
	});

});

function changeTextInputColor(empty){
	if(empty == 'empty'){
		$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
		$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");

		$("#formTriageInfoED input[name=vs_bp_sys1]").next().removeClass("red");
		$("#formTriageInfoED input[name=vs_bp_dias2]").next().removeClass("red");
		
	}
	
	var age = $('#age_show_triageED').val();
	var vs_bp_sys1 = $("#formTriageInfoED input[name=vs_bp_sys1]").val();
	var vs_bp_dias2 = $("#formTriageInfoED input[name=vs_bp_dias2]").val();

	if (age >= 18) {
		// Adult cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");

		} else {
			$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
		}
	} else if ((age <= 17) && (age >=1)){
		// Pediatric cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");
		} else {
			$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
		}
	} else {
		// Neonatal cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfoED input[name=vs_bp_sys1]").addClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").addClass("red");
		} else {
			$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
			$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");
		}
	}
}

var errorField = [];
conf = {
	modules: 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form){
		if(errorField.length > 0){
			return{
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

button_state_tiED('empty');
function button_state_tiED(state){
	switch(state){
		case 'empty':
			$("#toggle_tiED").removeAttr('data-toggle');
			$('#cancel_tiED').data('oper','add');
			$('#new_tiED,#save_tiED,#cancel_tiED,#edit_tiED').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_tiED").attr('data-toggle','collapse');
			$('#cancel_tiED').data('oper','add');
			$("#new_tiED").attr('disabled',false);
			$('#save_tiED,#cancel_tiED,#edit_tiED').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_tiED").attr('data-toggle','collapse');
			$('#cancel_tiED').data('oper','edit');
			$("#edit_tiED").attr('disabled',false);
			$('#save_tiED,#cancel_tiED,#new_tiED').attr('disabled',true);
			break;
		case 'wait':
			dialog_tri_colED.on();
			$("#toggle_tiED").attr('data-toggle','collapse');
			$("#save_tiED,#cancel_tiED").attr('disabled',false);
			$('#edit_tiED,#new_tiED').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_tiED").attr('data-toggle','collapse');
			$('#new_tiED,#save_tiED,#cancel_tiED,#edit_tiED').attr('disabled',true);
			break;
	}
}

// screen emergency //
function populate_formNursingED(obj,rowdata){
	// panel header
	$('#name_show_triageED').text(obj.a_pat_name);
	$('#mrn_show_triageED').text(("0000000" + obj.a_mrn).slice(-7));
	$('#sex_show_triageED').text(obj.sex);
	$('#dob_show_triageED').text(dob_chg(obj.dob));
	$('#age_show_triageED').text(obj.age+ ' (YRS)');
	$('#race_show_triageED').text(obj.race);
	$('#religion_show_triageED').text(if_none(obj.religion));
	$('#occupation_show_triageED').text(if_none(obj.occupation));
	$('#citizenship_show_triageED').text(obj.citizen);
	$('#area_show_triageED').text(obj.area);
	
	button_state_tiED('add');
	
	// formTriageInfoED
	$("#mrn_tiED").val(obj.a_mrn);
	$("#episno_tiED").val(obj.a_Episno);
	$("#age_show_triageED").val(dob_age(obj.DOB));
	$("#reg_date").val(obj.reg_date);
	tri_color_setED('empty');
	changeTextInputColor('empty');
	
}

// screen bed management //
function populate_triageED(obj,rowdata){
	emptyFormdata(errorField,"#formTriageInfoED");
	
	// panel header
	$('#name_show_triageED').text(obj.name);
	$('#mrn_show_triageED').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_triageED').text(obj.sex);
	$('#dob_show_triageED').text(dob_chg(obj.dob));
	$('#age_show_triageED').text(obj.age+ ' (YRS)');
	$('#race_show_triageED').text(obj.race);
	$('#religion_show_triageED').text(if_none(obj.religion));
	$('#occupation_show_triageED').text(if_none(obj.occupation));
	$('#citizenship_show_triageED').text(obj.citizen);
	$('#area_show_triageED').text(obj.area);
	
	$("#mrn_tiED").val(obj.MRN);
	$("#episno_tiED").val(obj.Episno);
	$("#age_show_triageED").val(dob_age(obj.DOB));

	var saveParam = {
		action: 'get_table_triageED',
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: obj.mrn,
		episno: obj.episno
	};
	
	$.post("./nursingED/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formTriageInfoED",data.triage);
			autoinsert_rowdata("#formTriageInfoED",data.triage_gen);
			autoinsert_rowdata("#formTriageInfoED",data.triage_regdate);
			autoinsert_rowdata("#formTriageInfoED",data.triage_nurshistory);
			$('#formTriageInfoED span#adduser').text(data.triage_gen.adduser);
			button_state_tiED('disableAll');
			textare_init_triageED();
		}else{
			button_state_tiED('disableAll');
			$('#formTriageInfoED span#adduser').text('');
			autoinsert_rowdata("#formTriageInfoED",data.triage_regdate);
			textare_init_triageED();
		}
	});
}

// screen current patient //
function populate_triageED_currpt(obj){
	$("#jqGridTriageInfoED_panel").collapse('hide');
	button_state_tiED('empty');
	
	// panel header
	$('#name_show_triageED').text(obj.Name);
	$('#mrn_show_triageED').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_triageED').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_triageED').text(dob_chg(obj.DOB));
	$('#age_show_triageED').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_triageED').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_triageED').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_triageED').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_triageED').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_triageED').text(if_none(obj.areaDesc).toUpperCase());
	
	$("#mrn_tiED").val(obj.MRN);
	$("#episno_tiED").val(obj.Episno);
    $("#age_show_triageED").val(dob_age(obj.DOB));
	
}

function populate_triageED_currpt_getdata(){
	emptyFormdata(errorField,"#formTriageInfoED",["#mrn_tiED","#episno_tiED"]);
	$(dialog_tri_colED.textfield).removeClass("red").removeClass("yellow").removeClass("green");
	$(dialog_tri_colED.textfield).next().removeClass("red").removeClass("yellow").removeClass("green");

	$("#formTriageInfoED input[name=vs_bp_sys1]").removeClass("red");
	$("#formTriageInfoED input[name=vs_bp_dias2]").removeClass("red");

	$("#formTriageInfoED input[name=vs_bp_sys1]").next().removeClass("red");
	$("#formTriageInfoED input[name=vs_bp_dias2]").next().removeClass("red");

	
	var urlparam = {
		action: 'get_table_triageED',
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: $("#mrn_tiED").val(),
		episno: $("#episno_tiED").val(),
		epistycode: $("#epistycode").val()
	};
	
	$.post("./nursingED/form?"+$.param(urlparam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!emptyobj_(data.triage)){
			if(!emptyobj_(data.triage))autoinsert_rowdata("#formTriageInfoED",data.triage);
			if(!emptyobj_(data.triage_gen))autoinsert_rowdata("#formTriageInfoED",data.triage_gen);
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfoED",data.triage_regdate);
			if(!emptyobj_(data.triage_gen))$('#formTriageInfoED span#adduser').text(data.triage_gen.adduser);
			if(!emptyobj_(data.triage_nurshistory))autoinsert_rowdata("#formTriageInfoED",data.triage_nurshistory);
			button_state_tiED('edit');
			textare_init_triageED();
			dialog_tri_colED.check('errorField');
			tri_color_setED();
			changeTextInputColor();
			
		}else{
			button_state_tiED('add');
			$('#formTriageInfoED span#adduser').text('');
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfoED",data.triage_regdate);
			textare_init_triageED();
		}
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
		}else{
			input.val(value);
		}
	});
}

function empty_formNursingED(){
	tri_color_setED('empty');
	changeTextInputColor('empty');
	$('#name_show_tiED').text('');
	$('#newic_show_tiED').text('');
	$('#sex_show_tiED').text('');
	$('#age_show_tiED').text('');
	$('#race_show_tiED').text('');
	button_state_tiED('empty');
	// $("#cancel_tiED, #cancel_ad, #cancel_tpa").click();
	
	disableForm('#formTriageInfoED');
	emptyFormdata(errorField,'#formTriageInfoED')
	dialog_tri_colED.off();
}

function saveForm_tiED(callback){
	var saveParam = {
		action: 'save_table_ti',
		oper: $("#cancel_tiED").data('oper')
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
	};
	
	values = $("#formTriageInfoED").serializeArray();
	
	values = values.concat(
		$('#formTriageInfoED input[type=checkbox]:not(:checked)').map(
			function (){
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED input[type=checkbox]:checked').map(
			function (){
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED input[type=radio]:checked').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED select').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	// values = values.concat(
	// 	$('#formTriageInfoED input[type=radio]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	$.post("./nursingED/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').fail(function (data){
		// alert('there is an error');
		callback();
	}).success(function (data){
		callback();
	});
}

function saveForm_tiED(callback){
	var saveParam = {
		action: 'save_table_triage',
		oper: $("#cancel_tiED").data('oper')
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
	};
	
	values = $("#formTriageInfoED").serializeArray();
	
	values = values.concat(
		$('#formTriageInfoED input[type=checkbox]:not(:checked)').map(
			function (){
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED input[type=checkbox]:checked').map(
			function (){
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED input[type=radio]:checked').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formTriageInfoED select').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	// values = values.concat(
	// 	$('#formTriageInfoED input[type=radio]:checked').map(
	// 		function (){
	// 			return {"name": this.name, "value": this.value}
	// 		}).get()
	// );
	
	$.post("./nursingED/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
		
	},'json').fail(function (data){
		// alert('there is an error');
		callback();
	}).success(function (data){
		callback();
	});
}

var dialog_tri_colED = new ordialog(
	'tri_col','sysdb.sysparam',"#formTriageInfoED input[name='triagecolor']",errorField,
	{
		colModel: [
			{ label: 'Color', name: 'colorcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', hidden: true, canSearch: false, or_search: true },
		],
		urlParam: {
			url: './sysparam_triage_color',
			url_chk: './sysparam_triage_color_chk',
			filterCol: ['recstatus','compcode'],
			filterVal: ['ACTIVE', 'session.compcode']
		},
		ondblClickRow: function (event){
			$(dialog_tri_colED.textfield).val(selrowData("#"+dialog_tri_colED.gridname)['description']);
			$(dialog_tri_colED.textfield)
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_colED.gridname)['description'] );
			
			$(dialog_tri_colED.textfield).next()
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_colED.gridname)['description'] );
			$(dialog_tri_colED.textfield).parent().next('span.help-block').text('');
		},
		onSelectRow: function (rowid, selected){
			$('#'+dialog_tri_colED.gridname+' tr#'+rowid).dblclick();
			// $(dialog_tri_colED.textfield).val(selrowData("#"+dialog_tri_colED.gridname)['description']);
		},
		gridComplete: function (obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		},
		loadComplete: function (data,obj){
			$("input[type='radio'][name='colorcode_select']").click(function (){
				let self = this;
				delay(function (){
					$(self).parent().click();
				}, 100 );
			});
			
			$( "table#othergrid_tri_col tr:nth-child(2)" ).addClass('red')
			$( "table#othergrid_tri_col tr:nth-child(3)" ).addClass('yellow')
			$( "table#othergrid_tri_col tr:nth-child(4)" ).addClass('green')
		}
	},{
		title: "Select Triage",
		open: function (){
			dialog_tri_colED.urlParam.filterCol = ['recstatus','compcode'];
			dialog_tri_colED.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
		},
		after_check: function (data,self,id,fail){
			if(!fail){
				let desc = data.rows[0].description;
				$(self.textfield).val(desc);
				$(self.textfield)
								.removeClass( "red" )
								.removeClass( "yellow" )
								.removeClass( "green" )
								.addClass(desc);
				
				$(self.textfield).next()
								.removeClass( "red" )
								.removeClass( "yellow" )
								.removeClass( "green" )
								.addClass(desc);
				$(self.textfield).parent().next('span.help-block').text('');
			}
		},
		width: 5/10 * $(window).width()
	},'urlParam','radio','tab','table'
);
dialog_tri_colED.makedialog();

function tri_color_setED(empty){
	if(empty == 'empty'){
		$(dialog_tri_colED.textfield).removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
		
		$(dialog_tri_colED.textfield).next().removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
	}
	
	var color = $(dialog_tri_colED.textfield).val();
	$(dialog_tri_colED.textfield)
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
	
	$(dialog_tri_colED.textfield).next()
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
}

function textare_init_triageED(){
	$('textarea#admreason,textarea#currentmedication,textarea#drugs_remarks,textarea#food_remarks,textarea#others_remarks,textarea#tpa_medication_note,textarea#pi_labinv_remarks,textarea#pi_bloodprod_remarks,textarea#pi_diaginv_remarks,textarea#mos_ivfluids_remarks,textarea#mos_oxygen_remarks,textarea#mos_woundprep_remarks').each(function (){
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
// 	if(scrollHeight<50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight>300){
// 		scrollHeight = scrollHeight - 50;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }

