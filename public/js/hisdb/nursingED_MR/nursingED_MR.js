$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

/////////////////////////////parameter for jqGridAddNotesNursingED url/////////////////////////////
var urlParam_AddNotesNursingED = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSING_ED'],
}

$(document).ready(function (){
	
	textare_init_triageED();
	
	var fdl = new faster_detail_load();
	
	disableForm('#formTriageInfoED');
	
	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function (){
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});
	
	// to autocheck the checkbox bila fill in textarea
	$("#drugs_remarks").on("keyup blur", function (){
		$("#allergydrugs").prop("checked", this.value !== "");
	});

	$("#food_remarks").on("keyup blur", function (){
		$("#allergyfood").prop("checked", this.value !== "");
	});
	
	$("#others_remarks").on("keyup blur", function (){
		$("#allergyothers").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends
	
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

	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursingED = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesNursingED///////////////////////////////////////
	$("#jqGridAddNotesNursingED").jqGrid({
		datatype: "local",
		editurl: "./nursingED_MR/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesNursingED",
		loadComplete: function (){
			if(addmore_jqgridNursingED.more == true){$('#jqGridAddNotesNursingED_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursingED.edit = addmore_jqgridNursingED.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesNursingED");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursingED_iledit").click();
		},
	});

	/////////////////////////////////////jqGridPagerAddNotesNursingED/////////////////////////////////////
	$("#jqGridAddNotesNursingED").inlineNav('#jqGridPagerAddNotesNursingED', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursingED", {
		id: "jqGridPagerRefresh_addnoteNursingED",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursingED", urlParam_AddNotesNursingED);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////

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
		
	// formTriageInfoED
	$("#mrn_tiED").val(obj.a_mrn);
	$("#episno_tiED").val(obj.a_Episno);
	$("#age_show_triageED").val(dob_age(obj.DOB));
	$("#reg_dateNursED").val(obj.reg_date);
	tri_color_setED('empty');
	changeTextInputColor('empty');
	
}

// screen current patient //
function populate_triageED_currpt(obj){
	$("#jqGridTriageInfoED_panel").collapse('hide');
	
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
	$("#reg_dateNursED").val(obj.reg_date);

	////jqGridAddNotesNursingED
	urlParam_AddNotesNursingED.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursingED.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursingED.filterVal[2] = 'NURSING_ED';
	
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
	
	$.post("./nursingED_MR/form?"+$.param(urlparam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!emptyobj_(data.triage)){
			if(!emptyobj_(data.triage))autoinsert_rowdata("#formTriageInfoED",data.triage);
			if(!emptyobj_(data.triage_gen))autoinsert_rowdata("#formTriageInfoED",data.triage_gen);
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfoED",data.triage_regdate);
			if(!emptyobj_(data.triage_gen))$('#formTriageInfoED span#adduser').text(data.triage_gen.adduser);
			if(!emptyobj_(data.triage_nurshistory))autoinsert_rowdata("#formTriageInfoED",data.triage_nurshistory);
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'add_notes');
			textare_init_triageED();
			dialog_tri_colED.check('errorField');
			tri_color_setED();
			changeTextInputColor();
			
		}else{
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'add_notes');
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
	
	disableForm('#formTriageInfoED');
	emptyFormdata(errorField,'#formTriageInfoED')
	dialog_tri_colED.off();
}

var dialog_tri_colED = new ordialog(
	'tri_colED','sysdb.sysparam',"#formTriageInfoED input[name='triagecolor']",errorField,
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
			
			$( "table#othergrid_tri_colED tr:nth-child(2)" ).addClass('red')
			$( "table#othergrid_tri_colED tr:nth-child(3)" ).addClass('yellow')
			$( "table#othergrid_tri_colED tr:nth-child(4)" ).addClass('green')
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

