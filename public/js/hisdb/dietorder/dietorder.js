$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

/////////////////////////////parameter for jqGridAddNotesDietOrder url/////////////////////////////
var urlParam_AddNotesDietOrder = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','DIET_ORDER'],
}

$(document).ready(function (){
	
	// $('textarea#remark,textarea#remarkkitchen').each(function (){
	// 	this.setAttribute('style', 'height:' + (38) + 'px;min-height:'+ (38) +'px;overflow-y:hidden;');
	// }).on('input', function (){
	// 	this.style.height = 'auto';
	// 	this.style.height = (this.scrollHeight) + 'px';
	// });
	
	disableForm('#formDietOrder');
	
	$("#new_dietOrder").click(function (){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
	});
	
	$("#edit_dietOrder").click(function (){
		button_state_dietOrder('wait');
		enableForm('#formDietOrder');
		rdonly('#formDietOrder');
		// dialog_mrn_edit.on();
	});
	
	$("#save_dietOrder").click(function (){
		disableForm('#formDietOrder');
		if($('#formDietOrder').isValid({requiredFields: ''}, conf, true)){
			saveForm_dietOrder(function (){
				$("#cancel_dietOrder").data('oper','edit');
				$("#cancel_dietOrder").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formDietOrder');
			rdonly('#formDietOrder');
		}
	});
	
	$("#cancel_dietOrder").click(function (){
		disableForm('#formDietOrder');
		button_state_dietOrder($(this).data('oper'));
		// dialog_mrn_edit.off();
	});
	
	// Mode of Feeding
	// Radio button with different name but a single selection
	$("input[id=feedingmode]").prop("checked", false);
	$("input[id=feedingmode]:first").prop("checked", true);
	
	$("input[id=feedingmode]").click(function (event){
		$("input[id=feedingmode]").prop("checked", false);
		$(this).prop("checked", true);
		feedingCheck();
		// event.preventDefault();
	});
	
	$("#jqGridDietOrder_panel").on("shown.bs.collapse", function (){
		var saveParam = {
			action: 'get_table_dietorder',
		}
		var postobj = {
			_token: $('#csrf_token').val(),
			mrn: $("#mrn_dietOrder").val(),
			episno: $("#episno_dietOrder").val(),
		};
		
		$.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
			
		},'json').fail(function (data){
			alert('there is an error');
		}).success(function (data){
			if(!$.isEmptyObject(data.dietorder)){
				autoinsert_rowdata("#formDietOrder",data.dietorder);
				autoinsert_rowdata("#formDietOrder",data.episode);
				button_state_dietOrder('disableAll');
				yesnoCheck();
				feedingCheck();
				textarea_init_dietorder();
			}else{
				autoinsert_rowdata("#formDietOrder",data.episode);
				button_state_dietOrder('add');
				textarea_init_dietorder();
			}
		});
		
		SmoothScrollTo("#jqGridDietOrder_panel", 500);
	});
	
	$("#jqGridDietOrder_panel").on("hide.bs.collapse", function (){
		button_state_dietOrder('empty');
		disableForm('#formDietOrder');
		// $("#jqGridDietOrder_panel > div").scrollTop(0);
	});
	
	$("#preview").click(function (){
		// window.location='./dietorder/table?action=dietorder_preview&mrn='+$('#mrn_dietOrder').val()+'&episno='+$("#episno_dietOrder").val();
		
		window.open('./dietorder/table?action=dietorder_preview&epistycode='+$('#epistycode').val(), '_blank');
	});

	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridDietOrder = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesDietOrder///////////////////////////////////////
	$("#jqGridAddNotesDietOrder").jqGrid({
		datatype: "local",
		editurl: "./dietorder/form",
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
		pager: "#jqGridPagerAddNotesDietOrder",
		loadComplete: function (){
			if(addmore_jqgridDietOrder.more == true){$('#jqGridAddNotesDietOrder_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridDietOrder.edit = addmore_jqgridDietOrder.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesDietOrder");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesDietOrder_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addDietOrder = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesDietOrder,#jqGridPagerRefresh_addnoteDietOrder").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesDietOrder_ilsave').click();
				// addmore_jqgridDietOrder.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridDietOrder.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesDietOrder',urlParam_AddNotesDietOrder,'add_notesDietOrder');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesDietOrder,#jqGridPagerRefresh_addnoteDietOrder").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesDietOrder',urlParam_AddNotesDietOrder,'add_notesDietOrder');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesDietOrder').jqGrid ('getRowData', rowid);
			
			let editurl = "./dietorder/form?"+
				$.param({
					episno: $('#episno_wardMain').val(),
					mrn: $('#mrn_wardMain').val(),
					action: 'addNotesDietOrder_save',
				});
			$("#jqGridAddNotesDietOrder").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesDietOrder,#jqGridPagerRefresh_addnoteDietOrder").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesDietOrder/////////////////////////////////////
	$("#jqGridAddNotesDietOrder").inlineNav('#jqGridPagerAddNotesDietOrder', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addDietOrder
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesDietOrder", {
		id: "jqGridPagerRefresh_addnoteDietOrder",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesDietOrder", urlParam_AddNotesDietOrder);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
});

// hide show No of Lodger
function yesnoCheck(){
	if(document.getElementById('yesCheck').checked){
		document.getElementById('ifYes').style.display = 'inline-block';
	}
	else document.getElementById('ifYes').style.display = 'none';
}

// hide show order list
function feedingCheck(){
	if(document.getElementsByName('oral')[0].checked){
		document.getElementById('ifOral').style.display = 'block';
	}
	else document.getElementById('ifOral').style.display = 'none';
}
// hide show order list ends

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

button_state_dietOrder('empty');
function button_state_dietOrder(state){
	switch(state){
		case 'empty':
			$("#toggle_dietOrder").removeAttr('data-toggle');
			$('#cancel_dietOrder').data('oper','add');
			$('#new_dietOrder,#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$('#cancel_dietOrder').data('oper','add');
			$("#new_dietOrder").attr('disabled',false);
			$('#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$('#cancel_dietOrder').data('oper','edit');
			$("#edit_dietOrder").attr('disabled',false);
			$('#save_dietOrder,#cancel_dietOrder,#new_dietOrder').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$("#save_dietOrder,#cancel_dietOrder").attr('disabled',false);
			$('#edit_dietOrder,#new_dietOrder').attr('disabled',true);
			break;
		case 'disableAll':
			$("#toggle_dietOrder").attr('data-toggle','collapse');
			$('#new_dietOrder,#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
			break;
	}
	
	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_dietOrder,#save_dietOrder,#cancel_dietOrder,#edit_dietOrder').attr('disabled',true);
	// }
}

function populate_dietOrder(obj,rowdata){
	emptyFormdata(errorField,"#formDietOrder");
	
	// panel header
	$('#name_show_dietOrder').text(obj.name);
	$('#mrn_show_dietOrder').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_dietOrder').text(obj.sex);
	$('#dob_show_dietOrder').text(dob_chg(obj.dob));
	$('#age_show_dietOrder').text(obj.age+ ' (YRS)');
	$('#race_show_dietOrder').text(obj.race);
	$('#religion_show_dietOrder').text(if_none(obj.religion));
	$('#occupation_show_dietOrder').text(if_none(obj.occupation));
	$('#citizenship_show_dietOrder').text(obj.citizen);
	$('#area_show_dietOrder').text(obj.area);
	
	// formDietOrder
	$('#mrn_dietOrder').val(obj.mrn);
	$("#episno_dietOrder").val(obj.episno);
	
	var saveParam = {
		action: 'get_table_dietorder',
	}
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: obj.mrn,
		episno: obj.episno
	};
	
	$.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formDietOrder",data.dietorder);
			autoinsert_rowdata("#formDietOrder",data.episode);
			$("#dietorder_diagnosis").val(data.diagnosis);
			button_state_dietOrder('empty');
			yesnoCheck();
			feedingCheck();
		}else{
			button_state_dietOrder('add');
			$("#dietorder_diagnosis").val(data.diagnosis);
		}
		$("#dietorder_diagnosis").val(data.diagnosis);

	});
}

// screen current patient //
function populate_dietOrder_currpt(obj){
	// emptyFormdata(errorField,"#formDietOrder");
	
	// panel header
	$('#name_show_dietOrder').text(obj.Name);
	$('#mrn_show_dietOrder').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_dietOrder').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_dietOrder').text(dob_chg(obj.DOB));
	$('#age_show_dietOrder').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_dietOrder').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_dietOrder').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_dietOrder').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_dietOrder').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_dietOrder').text(if_none(obj.areaDesc).toUpperCase());
	
	// formDietOrder
	$('#mrn_dietOrder').val(obj.MRN);
	$("#episno_dietOrder").val(obj.Episno);

	////jqGridAddNotesDietOrder
	urlParam_AddNotesDietOrder.filterVal[0] = obj.MRN;
	urlParam_AddNotesDietOrder.filterVal[1] = obj.Episno;
	urlParam_AddNotesDietOrder.filterVal[2] = 'DIET_ORDER';
	
	// var saveParam = {
	// 	action: 'get_table_dietorder',
	// }
	// var postobj = {
	// 	_token: $('#csrf_token').val(),
	// 	mrn: obj.MRN,
	// 	episno: obj.Episno
	// };
	
	// $.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	// },'json').fail(function (data){
	// 	alert('there is an error');
	// }).success(function (data){
	// 	if(!$.isEmptyObject(data)){
	// 		autoinsert_rowdata("#formDietOrder",data.dietorder);
	// 		autoinsert_rowdata("#formDietOrder",data.episode);
	// 		button_state_dietOrder('edit');
	// 		yesnoCheck();
	// 		feedingCheck();
	// 	}else{
	// 		button_state_dietOrder('add');
	// 	}
	// });
}

function populate_dietOrder_getdata(obj){
	emptyFormdata(errorField,"#formDietOrder");
	
	var saveParam = {
		action: 'get_table_dietorder',
	}
	
	var postobj = {
		_token: $('#csrf_token').val(),
		mrn: $("#mrn_wardMain").val(),
		episno: $("#episno_wardMain").val(),
	};
	
	$.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
		
	},'json').fail(function (data){
		alert('there is an error');
	}).success(function (data){
		if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formDietOrder",data.dietorder);
			autoinsert_rowdata("#formDietOrder",data.episode);
			$("#dietorder_diagnosis").val(data.diagnosis);
			button_state_dietOrder('empty');
			yesnoCheck();
			feedingCheck();
			textarea_init_dietorder();
		}else{
			autoinsert_rowdata("#formDietOrder",data.episode);
			$("#dietorder_diagnosis").val(data.diagnosis);
			button_state_dietOrder('add');
			textarea_init_dietorder();
		}

		$("#dietorder_diagnosis").val(data.diagnosis);
	});

	////jqGridAddNotesDietOrder
	urlParam_AddNotesDietOrder.filterVal[0] = $("#mrn_wardMain").val();
	urlParam_AddNotesDietOrder.filterVal[1] = $("#episno_wardMain").val();
	urlParam_AddNotesDietOrder.filterVal[2] = 'DIET_ORDER';

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

function saveForm_dietOrder(callback){
	var saveParam = {
		action: 'save_table_dietOrder',
		oper: $("#cancel_dietOrder").data('oper'),
		mrn: $("#mrn_wardMain").val(),
		episno: $("#episno_wardMain").val(),
	}
	var postobj = {
		_token: $('#csrf_token').val(),
		// sex_edit: $('#sex_edit').val(),
		// idtype_edit: $('#idtype_edit').val()
	};
	
	values = $("#formDietOrder").serializeArray();
	
	values = values.concat(
		$('#formDietOrder input[type=checkbox]:not(:checked)').map(
			function (){
				return {"name": this.name, "value": 0}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder input[type=checkbox]:checked').map(
			function (){
				return {"name": this.name, "value": 1}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder input[type=radio]:checked').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	values = values.concat(
		$('#formDietOrder select').map(
			function (){
				return {"name": this.name, "value": this.value}
			}).get()
	);
	
	$.post("./dietorder/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function (data){
		
	},'json').fail(function (data){
		// alert('there is an error');
		callback();
	}).success(function (data){
		callback();
	});
}

function textarea_init_dietorder(){
	$('textarea#remark,textarea#remarkkitchen').each(function (){
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