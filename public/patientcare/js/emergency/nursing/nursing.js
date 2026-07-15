$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

///////////////////////////////////parameter for jqGridAddNotesNursingED url///////////////////////////////////
var urlParam_AddNotesNursingED = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','NURSING_ED'],
}

$(document).ready(function () {
	
	var fdl = new faster_detail_load();
	var radbuts = new checkradiobutton(['gsc_eye','gsc_verbal','gsc_motor','painscore']);

	$('#tab_triage').on('show.bs.collapse', function () {
		return check_if_user_selected();
	});

	$('#tab_triage').on('shown.bs.collapse', function () {

		SmoothScrollTo('#tab_triage', 300);
		
	});

	disableForm('#formTriageInfo');

	$("#new_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		
	});

	$("#edit_ti").click(function(){
		button_state_ti('wait');
		enableForm('#formTriageInfo');
		rdonly('#formTriageInfo');
		
	});

	$('#formTriageInfo').form({
	    fields: {
			admwardtime : 'empty',
			reg_date : 'empty',
			admreason : 'empty'
	    }
	});

	$("#save_ti").click(function(){
		radbuts.check();
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
		radbuts.reset();
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

	function glasgow_coma_scale(){
		var score = 0;
		$(".calc:checked").each(function(){
			score+=parseInt($(this).val(),10);
		});
		$("#formTriageInfo input[name=totgsc]").val(score)
	}
	$().ready(function(){
		$(".calc").change(function(){
			glasgow_coma_scale();
		});
	});

	$("#formTriageInfo input[name=vs_bp_sys1],#formTriageInfo input[name=vs_bp_dias2]").on('change',function (){
		var age = $('#age_emergencyMain').val();
		var vs_bp_sys1 = $("#formTriageInfo input[name=vs_bp_sys1]").val();
		var vs_bp_dias2 = $("#formTriageInfo input[name=vs_bp_dias2]").val();

		if (age >= 18) {
			// Adult cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");

			} else {
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
			}
		} else if ((age <= 17) && (age >=1)){
			// Pediatric cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");
			} else {
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
			}
		} else {
			// Neonatal cases
			if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");
			} else {
				$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
				$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
			}
		}
	});

	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridNursingED = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotesNursingED///////////////////////////////////////////
	$("#jqGridAddNotesNursingED").jqGrid({
		datatype: "local",
		editurl: "/ptcare_nursing/form",
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
		scroll: true,
		width: 700,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesNursingED",
		loadComplete: function (){
			if(addmore_jqgridNursingED.more == true){$('#jqGridAddNotesNursingED_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridNursingED.edit = addmore_jqgridNursingED.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotesNursingED_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_addNursingED = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesNursingED_ilsave').click();
				// addmore_jqgridNursingED.state = true;
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridNursingED.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'add_notes');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'add_notes');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotesNursingED').jqGrid('getRowData', rowid);
			
			let editurl = "/ptcare_nursing/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_emergencyMain').val(),
					mrn: $('#mrn_emergencyMain').val(),
					action: 'addNotes_save',
				});
			$("#jqGridAddNotesNursingED").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotesNursingED/////////////////////////////////////////
	$("#jqGridAddNotesNursingED").inlineNav('#jqGridPagerAddNotesNursingED', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addNursingED
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursingED", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function (){
	// 		selRowId = $("#jqGridAddNotesNursingED").jqGrid('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			alert('Please select row');
	// 		}else{
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if(result == true){
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'addNotes_save',
	// 					idno: selrowData('#jqGridAddNotesNursingED').idno,
	// 				}
					
	// 				$.post("/doctornote/form?"+$.param(param), {oper:'del'}, function (data){
						
	// 				}).fail(function (data){
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data){
	// 					refreshGrid("#jqGridAddNotesNursingED", urlParam_AddNotesNursingED);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotesNursingED", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesNursingED", urlParam_AddNotesNursingED);
		},
	});
	///////////////////////////////////////////////end grid///////////////////////////////////////////////
});

function changeTextInputColor(empty){
	if(empty == 'empty'){
		$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
		$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");

		$("#formTriageInfo input[name=vs_bp_sys1]").next().removeClass("red");
		$("#formTriageInfo input[name=vs_bp_dias2]").next().removeClass("red");
		
	}
	
	var age = $('#age_emergencyMain').val();
	var vs_bp_sys1 = $("#formTriageInfo input[name=vs_bp_sys1]").val();
	var vs_bp_dias2 = $("#formTriageInfo input[name=vs_bp_dias2]").val();

	if (age >= 18) {
		// Adult cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");

		} else {
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
		}
	} else if ((age <= 17) && (age >=1)){
		// Pediatric cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");
		} else {
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
		}
	} else {
		// Neonatal cases
		if ((vs_bp_sys1 >= 130) && (vs_bp_dias2 >= 90)){
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').addClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').addClass("red");
		} else {
			$("#formTriageInfo input[name=vs_bp_sys1]").parent('div').removeClass("red");
			$("#formTriageInfo input[name=vs_bp_dias2]").parent('div').removeClass("red");
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
			dialog_tri_col.on();
			// examination_nursing.on().enable();
			$("#toggle_ti").attr('data-toggle','collapse');
			$("#save_ti,#cancel_ti").attr('disabled',false);
			$('#edit_ti,#new_ti').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_ti").attr('data-toggle','collapse');
			$('#new_ti,#save_ti,#cancel_ti,#edit_ti').attr('disabled',true);
			break;
	}

}

//screen current patient//
function populate_triage_currpt(obj){
	emptyFormdata(errorField,"#formTriageInfo");
	tri_color_set();
	changeTextInputColor();
	
	// panel header
	// $('#name_show_triage').text(obj.Name);
	// $('#mrn_show_triage').text(("0000000" + obj.MRN).slice(-7));
	// $('#sex_show_triage').text(if_none(obj.Sex).toUpperCase());
	// $('#dob_show_triage').text(dob_chg(obj.DOB));
	// $('#age_emergencyMain').text(dob_age(obj.DOB)+' (YRS)');
	// $('#race_show_triage').text(if_none(obj.raceDesc).toUpperCase());
	// $('#religion_show_triage').text(if_none(obj.religion).toUpperCase());
	// $('#occupation_show_triage').text(if_none(obj.OccupCode).toUpperCase());
	// $('#citizenship_show_triage').text(if_none(obj.Citizencode).toUpperCase());
	// $('#area_show_triage').text(if_none(obj.AreaCode).toUpperCase());
	
	$("#mrn_emergencyMain").val(obj.MRN);
	$("#episno_emergencyMain").val(obj.Episno);
	$("#age_emergencyMain").val(dob_age(obj.DOB));

	////jqGridAddNotesNursingED
	urlParam_AddNotesNursingED.filterVal[0] = obj.MRN;
	urlParam_AddNotesNursingED.filterVal[1] = obj.Episno;
	urlParam_AddNotesNursingED.filterVal[2] = 'NURSING_ED';

	$("#tab_triage").collapse('hide');
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
	tri_color_set('empty');
	changeTextInputColor('empty');
	// $('#name_show_triage').text('');
	// $('#mrn_show_triage').text('');
	// $('#sex_show_triage').text('');
	// $('#dob_show_triage').text('');
	// $('#age_emergencyMain').text('');
	// $('#race_show_triage').text('');
	// $('#religion_show_triage').text('');
	// $('#occupation_show_triage').text('');
	// $('#citizenship_show_triage').text('');
	// $('#area_show_triage').text('');

	$('#mrn_emergencyMain').val('');
	$("#episno_emergencyMain").val('');

}

function saveForm_ti(callback){
	var saveParam={
        action:'save_table_ti',
        oper:$("#cancel_ti").data('oper'),
		mrn_emergencyMain:$("#mrn_emergencyMain").val(),
		episno_emergencyMain:$("#episno_emergencyMain").val()
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

    $.post( "./ptcare_nursing/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).done(function(data){
        callback();
    });
}

function getdata_nursing(){

	var saveParam={
		action:'get_table_triage',
	}

	var postobj={
		_token : $('#_token').val(),
		mrn:$("#mrn_emergencyMain").val(),
		episno:$("#episno_emergencyMain").val()
	};

	$.post( "./ptcare_nursing/form?"+$.param(saveParam), $.param(postobj), function( data ) {
		
	},'json').fail(function(data) {
		alert('there is an error');
	}).done(function(data){
		if(!$.isEmptyObject(data.triage)){
			if(!emptyobj_(data.triage))autoinsert_rowdata("#formTriageInfo",data.triage);
			if(!emptyobj_(data.triage_gen))autoinsert_rowdata("#formTriageInfo",data.triage_gen);
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
			if(!emptyobj_(data.triage_nurshistory))autoinsert_rowdata("#formTriageInfo",data.triage_nurshistory);
			if(!emptyobj_(data.triage_gen))$('#formTriageInfo span#adduser').text(data.triage_gen.adduser);
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'add_notes');
			// button_state_ti('edit');
			button_state_ti('empty');
			tri_color_set();
			changeTextInputColor();
		}else{
			button_state_ti('add');
			refreshGrid('#jqGridAddNotesNursingED',urlParam_AddNotesNursingED,'kosongkan');
			tri_color_set('empty');
			changeTextInputColor('empty');
			if(!emptyobj_(data.triage_regdate))autoinsert_rowdata("#formTriageInfo",data.triage_regdate);
		}

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
			url:'./ptcare_sysparam_triage_color',
			url_chk: './ptcare_sysparam_triage_color_chk',
			filterCol:['recstatus','compcode'],
			filterVal:['ACTIVE', 'session.compcode']
		},
		ondblClickRow:function(event){
			$(dialog_tri_col.textfield).val(selrowData("#"+dialog_tri_col.gridname)['description']);
			$(dialog_tri_col.textfield).parent('div')
							.removeClass( "red" )
							.removeClass( "yellow" )
							.removeClass( "green" )
							.addClass( selrowData("#"+dialog_tri_col.gridname)['description'] );
			
			$(dialog_tri_col.textfield).parent('div').next()
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
			
			$( "table#othergrid_tri_col tr:nth-child(2)" ).parent('div').addClass('red')
			$( "table#othergrid_tri_col tr:nth-child(3)" ).parent('div').addClass('yellow')
			$( "table#othergrid_tri_col tr:nth-child(4)" ).parent('div').addClass('green')
		}
	},{
		title:"Select Triage",
		open: function(){
			dialog_tri_col.urlParam.filterCol = ['recstatus','compcode'];
			dialog_tri_col.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
		},
		after_check:function(data,self,id,fail){
			if(!fail){
				let desc = data.rows[0].description;
				$(self.textfield).val(desc);
				$(self.textfield).parent('div')
								.removeClass( "red" )
								.removeClass( "yellow" )
								.removeClass( "green" )
								.addClass(desc);
				
				$(self.textfield).parent('div').next()
								.removeClass( "red" )
								.removeClass( "yellow" )
								.removeClass( "green" )
								.addClass(desc);
				// $(self.textfield).parent().next('span.help-block').text('');
			}
		},
		width:5/10 * $(window).width()
	},'urlParam','radio','tab','table'
);
dialog_tri_col.makedialog();

function tri_color_set(empty){
	if(empty == 'empty'){
		$(dialog_tri_col.textfield).parent('div').removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );

		$(dialog_tri_col.textfield).parent('div').next().removeClass( "red" ).removeClass( "yellow" ).removeClass( "green" );
	}

	var color = $(dialog_tri_col.textfield).val();
	$(dialog_tri_col.textfield).parent('div')
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );

	$(dialog_tri_col.textfield).parent('div').next()
					.removeClass( "red" )
					.removeClass( "yellow" )
					.removeClass( "green" )
					.addClass( color );
}




