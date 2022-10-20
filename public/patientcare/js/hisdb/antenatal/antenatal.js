
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridPrevObstetrics url/////////////////////////////////////////////////
var urlParam_PrevObstetrics = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.antenatal_history',
	table_id: 'idno',
	filterCol:['mrn'],
	filterVal:[''],
}

/////////////////////parameter for jqGridCurrPregnancy url/////////////////////////////////////////////////
var urlParam_CurrPregnancy = {
	action: 'CurrPregnancy',
	url: 'antenatal/table',
	filterCol:['mrn','episno'],
	filterVal:['',''],
}

/////////////////////parameter for jqGridObstetricsUltrasound url/////////////////////////////////////////////////
var urlParam_ObstetricsUltrasound = {
	action: 'ObstetricsUltrasound',
	url: 'antenatal/table',
	filterCol:['mrn'],
	filterVal:[''],
}

$(document).ready(function () {

	var fdl = new faster_detail_load();

	disableForm('#formAntenatal');
	disableForm('#formPregnancy');
	disableForm('#formUltrasound');

	// formAntenatal
	$("#new_antenatal").click(function(){
		button_state_antenatal('wait_antenatal');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_antenatal").click(function(){
		button_state_antenatal('wait_antenatal');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#save_antenatal").click(function(){
		disableForm('#formAntenatal');
		if( $('#formAntenatal').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_antenatal(function(){
				$("#cancel_antenatal").data('oper','edit_antenatal');
				$("#cancel_antenatal").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formAntenatal');
			rdonly('#formAntenatal');
		}

	});

	$("#cancel_antenatal").click(function(){
		disableForm('#formAntenatal');
		button_state_antenatal($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// formPregnancy
	$("#new_pregnancy").click(function(){
		button_state_antenatal('wait_pregnancy');
		enableForm('#formPregnancy');
		rdonly('#formPregnancy');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_pregnancy").click(function(){
		button_state_antenatal('wait_pregnancy');
		enableForm('#formPregnancy');
		rdonly('#formPregnancy');
		// dialog_mrn_edit.on();
		
	});

	$("#save_pregnancy").click(function(){
		disableForm('#formPregnancy');
		if( $('#formPregnancy').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_pregnancy(function(){
				$("#cancel_pregnancy").data('oper','edit_pregnancy');
				$("#cancel_pregnancy").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formPregnancy');
			rdonly('#formPregnancy');
		}

	});

	$("#cancel_pregnancy").click(function(){
		disableForm('#formPregnancy');
		button_state_antenatal($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// formUltrasound
	$("#new_ultrasound").click(function(){
		button_state_antenatal('wait_ultrasound');
		enableForm('#formUltrasound');
		rdonly('#formUltrasound');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_ultrasound").click(function(){
		button_state_antenatal('wait_ultrasound');
		enableForm('#formUltrasound');
		rdonly('#formUltrasound');
		// dialog_mrn_edit.on();
		
	});

	$("#save_ultrasound").click(function(){
		disableForm('#formUltrasound');
		if( $('#formUltrasound').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_ultrasound(function(){
				$("#cancel_ultrasound").data('oper','edit_ultrasound');
				$("#cancel_ultrasound").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formUltrasound');
			rdonly('#formUltrasound');
		}

	});

	$("#cancel_ultrasound").click(function(){
		disableForm('#formUltrasound');
		button_state_antenatal($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// to format number input to two decimal places (0.00)
	$(".floatNumberField").change(function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
	});

	// to autocheck the checkbox bila fill in textarea
	$("#cerebrum_text").on("keyup blur", function () {
        $(".is_cerebrum").prop("checked", this.value !== "");
	});

	$("#pellucidum_text").on("keyup blur", function () {
        $(".pellucidum").prop("checked", this.value !== "");
	});

	$("#falx_text").on("keyup blur", function () {
        $(".falx").prop("checked", this.value !== "");
	});

	$("#cerebellum_text").on("keyup blur", function () {
        $(".cerebellum").prop("checked", this.value !== "");
	});

	$("#lowerlip_text").on("keyup blur", function () {
        $(".lowerlip").prop("checked", this.value !== "");
	});

	$("#nose_text").on("keyup blur", function () {
        $(".nose").prop("checked", this.value !== "");
	});

	$("#righteyes_text").on("keyup blur", function () {
		$(".righteyes").prop("checked", this.value !== "");
	});

	$("#lefteyes_text").on("keyup blur", function () {
		$(".lefteyes").prop("checked", this.value !== "");
	});

	$("#chestwall_text").on("keyup blur", function () {
		$(".chestwall").prop("checked", this.value !== "");
	});

	$("#fourchamber_text").on("keyup blur", function () {
		$(".fourchamber").prop("checked", this.value !== "");
	});

	$("#cordinsert_text").on("keyup blur", function () {
		$(".cordinsert").prop("checked", this.value !== "");
	});

	$("#rightkidney_text").on("keyup blur", function () {
		$(".rightkidney").prop("checked", this.value !== "");
	});

	$("#leftkidney_text").on("keyup blur", function () {
		$(".leftkidney").prop("checked", this.value !== "");
	});

	$("#bladder_text").on("keyup blur", function () {
		$(".bladder").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends

	/////////////////////parameter for saving url/////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}

	/////////////////////////////////// jqGridPrevObstetrics ///////////////////////////////////////////////////
	$("#jqGridPrevObstetrics").jqGrid({
		datatype: "local",
		editurl: "./antenatal/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'Year', name: 'year', classes: 'wrap', width: 80, editable: true},
			{ label: 'Gestation', name: 'gestation', classes: 'wrap', width: 100, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:gestationCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Place of Delivery', name: 'placedeliver', classes: 'wrap', width: 100, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Labour/Delivery', name: 'lab_del', width: 100, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"Labour:Labour;Delivery:Delivery"
				}
			},
			{ label: 'Purperium', name: 'purperium', classes: 'wrap', width: 100, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Weight', name: 'weight', classes: 'wrap', width: 100, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:prev_weightCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Sex', name: 'sex', width: 100, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"M:Male;F:Female;U:Unknown"
				}
			},
			// { label: 'Breast Fed', name: 'breastfed', classes: 'wrap', width: 100, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Breast Fed', name: 'breastfed', width: 100, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"Yes:Yes;No:No"
				}
			},
			{ label: 'Comments', name: 'comments', classes: 'wrap', width: 100, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPagerPrevObstetrics",
		loadComplete: function(){		
			$("#jqGridPrevObstetrics").setSelection($("#jqGridPrevObstetrics").getDataIDs()[0]);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPrevObstetrics_iledit").click();
		},
	});

	jQuery("#jqGridPrevObstetrics").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
		  {startColumnName: 'weight', numberOfColumns: 4, titleText: 'Child'}
		]
	});

	//////////////////////////////////////////myEditOptions_add_PrevObstetrics////////////////////////////////////////////////
	var myEditOptions_add_PrevObstetrics = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();

			$("input[name='comments']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridPrevObstetrics_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'add_prevObstetrics');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'add_prevObstetrics');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridPrevObstetrics').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					// episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'prevObstetrics_save',
				});
			$("#jqGridPrevObstetrics").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit_PrevObstetrics////////////////////////////////////////////////
	var myEditOptions_edit_PrevObstetrics = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='comments']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridPrevObstetrics_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'edit_prevObstetrics');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'edit_prevObstetrics');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridPrevObstetrics').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					// episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'prevObstetrics_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridPrevObstetrics").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerPrevObstetrics////////////////////////////////////////////////
	$("#jqGridPrevObstetrics").inlineNav('#jqGridPagerPrevObstetrics', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_PrevObstetrics
		},
		editParams: myEditOptions_edit_PrevObstetrics
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerPrevObstetrics", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function () {
	// 		selRowId = $("#jqGridPrevObstetrics").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		} else {
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if (result == true) {
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'prevObstetrics_save',
	// 					idno: selrowData('#jqGridPrevObstetrics').idno,
	// 				}
	// 				$.post( "./antenatal/form?"+$.param(param),{oper:'del'}, function( data ){
	// 				}).fail(function (data) {
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data) {
	// 					refreshGrid("#jqGridPrevObstetrics", urlParam_PrevObstetrics);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerPrevObstetrics", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridPrevObstetrics", urlParam_PrevObstetrics);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	/////////////////////////////////// jqGridCurrPregnancy ///////////////////////////////////////////////////
	$("#jqGridCurrPregnancy").jqGrid({
		datatype: "local",
		editurl: "./antenatal/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'pregnan_id', name: 'pregnan_idno', width:10, hidden: true},
			{ label: 'Date', name: 'date', width: 150, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							  changeYear: true,
							  onSelect : function(){
								  $(this).focus();
							  }
						});
					}
				}
			},
			{ label: 'Report', name: 'report', classes: 'wrap', width: 250, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;", rows: 5}},
			{ label: 'POA/POG', name: 'poa_pog', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:poaORpogCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Uterine Size', name: 'uterinesize', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:uterineSizeCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Albumin', name: 'albumin', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:albuminCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Sugar', name: 'sugar', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:sugarCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Weight', name: 'weight', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:weightCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Blood Pressure', name: 'bp_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:bpCustomEdit,
					custom_value:galGridCustomValue2 	
				}
			},
			{ label: 'bp_sys1', name: 'bp_sys1', hidden: true },
			{ label: 'bp_dias2', name: 'bp_dias2', hidden: true },
			{ label: 'Hb', name: 'hb', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:hbCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'Oedema', name: 'oedema', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Lie', name: 'lie', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Pres', name: 'pres', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'FHR', name: 'fhr', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:fhrCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'FM', name: 'fm', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
		],
		scroll: false,
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPagerCurrPregnancy",
		loadComplete: function(){
			$("#jqGridCurrPregnancy").setSelection($("#jqGridCurrPregnancy").getDataIDs()[0]);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridCurrPregnancy_iledit").click();
		},
	});

	jQuery("#jqGridCurrPregnancy").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
		  {startColumnName: 'poaORpog', numberOfColumns: 8, titleText: 'Tests'},
		  {startColumnName: 'fetusLie', numberOfColumns: 4, titleText: 'Examination (Fetus)'}
		]
	});

	//////////////////////////////////////////myEditOptions_add_CurrPregnancy////////////////////////////////////////////////
	var myEditOptions_add_CurrPregnancy = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();

			$("input[name='fm']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridCurrPregnancy_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_currPregnancy');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_currPregnancy');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridCurrPregnancy').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'currPregnancy_save',
				});
			$("#jqGridCurrPregnancy").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit_CurrPregnancy////////////////////////////////////////////////
	var myEditOptions_edit_CurrPregnancy = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='fm']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridCurrPregnancy_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'edit_currPregnancy');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'edit_currPregnancy');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridCurrPregnancy').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'currPregnancy_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridCurrPregnancy").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerCurrPregnancy////////////////////////////////////////////////
	$("#jqGridCurrPregnancy").inlineNav('#jqGridPagerCurrPregnancy', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_CurrPregnancy
		},
		editParams: myEditOptions_edit_CurrPregnancy
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerCurrPregnancy", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function () {
	// 		selRowId = $("#jqGridCurrPregnancy").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		} else {
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if (result == true) {
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'currPregnancy_save',
	// 					idno: selrowData('#jqGridCurrPregnancy').idno,
	// 				}
	// 				$.post( "./antenatal/form?"+$.param(param),{oper:'del'}, function( data ){
	// 				}).fail(function (data) {
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data) {
	// 					refreshGrid("#jqGridCurrPregnancy", urlParam_CurrPregnancy);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerCurrPregnancy", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridCurrPregnancy", urlParam_CurrPregnancy);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	/////////////////////////////////// jqGridObstetricsUltrasound ///////////////////////////////////////////////////
	$("#jqGridObstetricsUltrasound").jqGrid({
		datatype: "local",
		editurl: "./antenatal/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'Date', name: 'date', width: 150, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							  changeYear: true,
							  onSelect : function(){
								  $(this).focus();
							  }
						});
					}
				}
			},
			{ label: 'POA', name: 'poa', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:poaCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'POG (SCAN)', name: 'pog', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:pogCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'CRL', name: 'crl_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:crlCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#crl_w').keyup(function(event) {
									get_crl();
								});
							
								$('#crl_d').keyup(function(event) {
									get_crl();
								});
							}
						}
					]
				}
			},
			{ label: 'crl', name: 'crl', hidden: true },
			{ label: 'crl_w', name: 'crl_w', hidden: true },
			{ label: 'crl_d', name: 'crl_d', hidden: true },
			{ label: 'BPD', name: 'bpd_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:bpdCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#bpd_w').keyup(function(event) {
									get_bpd();
								});
							
								$('#bpd_d').keyup(function(event) {
									get_bpd();
								});
							}
						}
					] 	
				}
			},
			{ label: 'bpd', name: 'bpd', hidden: true },
			{ label: 'bpd_w', name: 'bpd_w', hidden: true },
			{ label: 'bpd_d', name: 'bpd_d', hidden: true },
			{ label: 'HC', name: 'hc_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:hcCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#hc_w').keyup(function(event) {
									get_hc();
								});
							
								$('#hc_d').keyup(function(event) {
									get_hc();
								});
							}
						}
					]  	
				}
			},
			{ label: 'hc', name: 'hc', hidden: true },
			{ label: 'hc_w', name: 'hc_w', hidden: true },
			{ label: 'hc_d', name: 'hc_d', hidden: true },
			{ label: 'AC', name: 'ac_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:acCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#ac_w').keyup(function(event) {
									get_ac();
								});
							
								$('#ac_d').keyup(function(event) {
									get_ac();
								});
							}
						}
					] 	
				}
			},
			{ label: 'ac', name: 'ac', hidden: true },
			{ label: 'ac_w', name: 'ac_w', hidden: true },
			{ label: 'ac_d', name: 'ac_d', hidden: true },
			{ label: 'FL', name: 'fl_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:flCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#fl_w').keyup(function(event) {
									get_fl();
								});
							
								$('#fl_d').keyup(function(event) {
									get_fl();
								});
							}
						}
					] 	
				}
			},
			{ label: 'fl', name: 'fl', hidden: true },
			{ label: 'fl_w', name: 'fl_w', hidden: true },
			{ label: 'fl_d', name: 'fl_d', hidden: true },
			{ label: 'ATD', name: 'atd_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:atdCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#atd_w').keyup(function(event) {
									get_atd();
								});
							
								$('#atd_d').keyup(function(event) {
									get_atd();
								});
							}
						}
					] 	
				}
			},
			{ label: 'atd', name: 'atd', hidden: true },
			{ label: 'atd_w', name: 'atd_w', hidden: true },
			{ label: 'atd_d', name: 'atd_d', hidden: true },
			{ label: 'ALD', name: 'ald_', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:aldCustomEdit,
					custom_value:galGridCustomValue2,
					dataEvents: [
						{  type: 'change',
						   fn: function(e) {
								$('#ald_w').keyup(function(event) {
									get_ald();
								});
							
								$('#ald_d').keyup(function(event) {
									get_ald();
								});
							}
						}
					] 	
				}
			},
			{ label: 'ald', name: 'ald', hidden: true },
			{ label: 'ald_w', name: 'ald_w', hidden: true },
			{ label: 'ald_d', name: 'ald_d', hidden: true },
			{ label: 'EFBW', name: 'efbw', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:efbwCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'AFI', name: 'afi', classes: 'wrap', width: 150, editable: true, edittype:'custom', 
				editoptions:
				{ 	custom_element:afiCustomEdit,
					custom_value:galGridCustomValue 	
				}
			},
			{ label: 'PRES', name: 'pres', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'PLACENTA', name: 'placenta', classes: 'wrap', width: 150, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
		],
		scroll: false,
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPagerObstetricsUltrasound",
		onSelectRow:function(rowid, selected){
			let data =selrowData('#jqGridObstetricsUltrasound');
			populate_ultrasound(data);
		},
		loadComplete: function(){
			$("#jqGridObstetricsUltrasound").setSelection($("#jqGridObstetricsUltrasound").getDataIDs()[0]);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridObstetricsUltrasound_iledit").click();
		},
	});

	jQuery("#jqGridObstetricsUltrasound").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
		  {startColumnName: 'crl_', numberOfColumns: 28, titleText: 'mm = W + D'}
		]
	});

	//////////////////////////////////////////myEditOptions_add_ObstetricsUltrasound////////////////////////////////////////////////
	var myEditOptions_add_ObstetricsUltrasound = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();

			$("input[name='placenta']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridObstetricsUltrasound_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'add_obstetricsUltrasound');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'add_obstetricsUltrasound');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridObstetricsUltrasound').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					// episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'obstetricsUltrasound_save',
				});
			$("#jqGridObstetricsUltrasound").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit_ObstetricsUltrasound////////////////////////////////////////////////
	var myEditOptions_edit_ObstetricsUltrasound = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='placenta']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridObstetricsUltrasound_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'edit_obstetricsUltrasound');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'edit_obstetricsUltrasound');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridObstetricsUltrasound').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					// episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'obstetricsUltrasound_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridObstetricsUltrasound").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerObstetricsUltrasound////////////////////////////////////////////////
	$("#jqGridObstetricsUltrasound").inlineNav('#jqGridPagerObstetricsUltrasound', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_ObstetricsUltrasound
		},
		editParams: myEditOptions_edit_ObstetricsUltrasound
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerObstetricsUltrasound", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function () {
	// 		selRowId = $("#jqGridObstetricsUltrasound").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		} else {
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if (result == true) {
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'obstetricsUltrasound_save',
	// 					idno: selrowData('#jqGridObstetricsUltrasound').idno,
	// 				}
	// 				$.post( "./antenatal/form?"+$.param(param),{oper:'del'}, function( data ){
	// 				}).fail(function (data) {
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data) {
	// 					refreshGrid("#jqGridObstetricsUltrasound", urlParam_ObstetricsUltrasound);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerObstetricsUltrasound", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridObstetricsUltrasound", urlParam_ObstetricsUltrasound);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////////////////////////////////////////custom edits//////////////////////////////////////////////
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	function galGridCustomValue2 (elem, operation, value){
		if(operation == 'get') {
			var inpgrp = $(elem).find("input").get();
			var val_array=[];
			inpgrp.forEach(function(e,i){
				val_array.push($(e).val());
			});

			return val_array;
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	// jqGridPrevObstetrics starts
	function gestationCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="gestation" name="gestation" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="gestation" name="gestation" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}
	}

	function prev_weightCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>kg</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>kg</span>
					</div>`);
		}
	}
	// jqGridPrevObstetrics ends

	// jqGridCurrPregnancy ends
	function poaORpogCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="poa_pog" name="poa_pog" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="poa_pog" name="poa_pog" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}
	}

	function uterineSizeCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="uterinesize" name="uterinesize" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>cm</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="uterinesize" name="uterinesize" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>cm</span>
					</div>`);
		}
	}

	function albuminCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="albumin" name="albumin" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>g/dL</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="albumin" name="albumin" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>g/dL</span>
					</div>`);
		}
	}

	function sugarCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="sugar" name="sugar" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>mg/dL</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="sugar" name="sugar" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>mg/dL</span>
					</div>`);
		}
	}

	function weightCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>kg</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>kg</span>
					</div>`);
		}
	}

	function bpCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.bp_sys1+`'>
						<input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.bp_dias2+`'>
						<span class="input-group-addon" style='padding:2px;'>mm<br>Hg</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>mm<br>Hg</span>
					</div>`);
		}
	}

	function hbCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="hb" name="hb" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>gm%</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="hb" name="hb" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>gm%</span>
					</div>`);
		}
	}

	function fhrCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="fhr" name="fhr" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>bpm</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="fhr" name="fhr" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>bpm</span>
					</div>`);
		}
	}
	// jqGridCurrPregnancy ends

	// jqGridObstetricsUltrasound starts
	function poaCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
					<input id="poa" name="poa" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" value='`+val+`'>
					<span class="input-group-addon" style='padding:2px;'>weeks</span>
				</div>`);
		}else{
			return $(`<div class="input-group">
					<input id="poa" name="poa" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
					<span class="input-group-addon" style='padding:2px;'>weeks</span>
				</div>`);
		}
	}

	function pogCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="pog" name="pog" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="pog" name="pog" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
						<span class="input-group-addon" style='padding:2px;'>weeks</span>
					</div>`);
		}
	}
	  
	function crlCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
					<div class="input-group">
						<input id="crl" name="crl" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.crl+`'>
						<span class="input-group-addon" style='padding:2px;'>mm</span>
					</div>
					<small class="w-100" style="padding-left:60px">=</small>
					<div class="input-group">
						<input id="crl_w" name="crl_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false" value='`+data.crl_w+`'>
						<span class="input-group-addon" style='padding:2px;'>W</span>
					</div>
					<small class="w-100" style="padding-left:60px">+</small>
					<div class="input-group">
						<input id="crl_d" name="crl_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.crl_d+`'>
						<span class="input-group-addon" style='padding:2px;'>D</span>
					</div>
				</div>`);
		}else{
			return $(`<div class="input-group">
					<div class="input-group">
						<input id="crl" name="crl" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>mm</span>
					</div>
					<small class="w-100" style="padding-left:60px">=</small>
					<div class="input-group">
						<input id="crl_w" name="crl_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>W</span>
					</div>
					<small class="w-100" style="padding-left:60px">+</small>
					<div class="input-group">
						<input id="crl_d" name="crl_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>D</span>
					</div>
				</div>`);
		}
		
	}

	function bpdCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="bpd" name="bpd" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.bpd+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="bpd_w" name="bpd_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.bpd_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="bpd_d" name="bpd_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.bpd_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="bpd" name="bpd" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="bpd_w" name="bpd_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="bpd_d" name="bpd_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function hcCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="hc" name="hc" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.hc+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="hc_w" name="hc_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.hc_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="hc_d" name="hc_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.hc_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="hc" name="hc" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="hc_w" name="hc_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="hc_d" name="hc_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function acCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="ac" name="ac" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ac+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="ac_w" name="ac_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ac_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="ac_d" name="ac_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ac_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="ac" name="ac" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="ac_w" name="ac_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="ac_d" name="ac_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function flCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="fl" name="fl" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.fl+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="fl_w" name="fl_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.fl_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="fl_d" name="fl_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.fl_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="fl" name="fl" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="fl_w" name="fl_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="fl_d" name="fl_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function atdCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="atd" name="atd" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.atd+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="atd_w" name="atd_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.atd_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="atd_d" name="atd_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.atd_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="atd" name="atd" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="atd_w" name="atd_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="atd_d" name="atd_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function aldCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			var data = $('#jqGridObstetricsUltrasound').jqGrid('getRowData', opt.rowId);
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="ald" name="ald" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ald+`'>
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="ald_w" name="ald_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ald_w+`'>
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="ald_d" name="ald_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+data.ald_d+`'>
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<div class="input-group">
							<input id="ald" name="ald" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>mm</span>
						</div>
						<small class="w-100" style="padding-left:60px">=</small>
						<div class="input-group">
							<input id="ald_w" name="ald_w" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>W</span>
						</div>
						<small class="w-100" style="padding-left:60px">+</small>
						<div class="input-group">
							<input id="ald_d" name="ald_d" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
							<span class="input-group-addon" style='padding:2px;'>D</span>
						</div>
					</div>`);
		}
	}

	function efbwCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="efbw" name="efbw" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>gm</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="efbw" name="efbw" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>gm</span>
					</div>`);
		}
	}

	function afiCustomEdit(val, opt) {
		var oper = getjqcust_oper(opt);

		if(oper == 'edit'){
			return $(`<div class="input-group">
						<input id="afi" name="afi" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;" value='`+val+`'>
						<span class="input-group-addon" style='padding:2px;'>cm</span>
					</div>`);
		}else{
			return $(`<div class="input-group">
						<input id="afi" name="afi" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
						<span class="input-group-addon" style='padding:2px;'>cm</span>
					</div>`);
		}
	}
	// jqGridObstetricsUltrasound ends

	function get_crl() {
		var crl_w = parseFloat($("#crl_w").val());
		var crl_d = parseFloat($("#crl_d").val());
	
		var crl = crl_w + crl_d;
	
		if (isNaN(crl)) crl = 0;
	
		$('#crl').val((crl));
	}

	function get_bpd() {
		var bpd_w = parseFloat($("#bpd_w").val());
		var bpd_d = parseFloat($("#bpd_d").val());
	
		var bpd = bpd_w + bpd_d;
	
		if (isNaN(bpd)) bpd = 0;
	
		$('#bpd').val((bpd));
	}

	function get_hc() {
		var hc_w = parseFloat($("#hc_w").val());
		var hc_d = parseFloat($("#hc_d").val());
	
		var hc = hc_w + hc_d;
	
		if (isNaN(hc)) hc = 0;
	
		$('#hc').val((hc));
	}

	function get_ac() {
		var ac_w = parseFloat($("#ac_w").val());
		var ac_d = parseFloat($("#ac_d").val());
	
		var ac = ac_w + ac_d;
	
		if (isNaN(ac)) ac = 0;
	
		$('#ac').val((ac));
	}

	function get_fl() {
		var fl_w = parseFloat($("#fl_w").val());
		var fl_d = parseFloat($("#fl_d").val());
	
		var fl = fl_w + fl_d;
	
		if (isNaN(fl)) fl = 0;
	
		$('#fl').val((fl));
	}

	function get_atd() {
		var atd_w = parseFloat($("#atd_w").val());
		var atd_d = parseFloat($("#atd_d").val());
	
		var atd = atd_w + atd_d;
	
		if (isNaN(atd)) atd = 0;
	
		$('#atd').val((atd));
	}

	function get_ald() {
		var ald_w = parseFloat($("#ald_w").val());
		var ald_d = parseFloat($("#ald_d").val());
	
		var ald = ald_w + ald_d;
	
		if (isNaN(ald)) ald = 0;
	
		$('#ald').val((ald));
	}
	//////////////////////////////////////////////custom edits ends//////////////////////////////////////////////

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

var dialog_bloodGroup= new ordialog(
	'blood_grp','hisdb.bloodgroup',"#formAntenatal input[name='blood_grp']",errorField,
	{	colModel:[
			{label:'Blood Code',name:'bloodcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
			filterCol:['compcode', 'recstatus'],
			filterVal:['session.compcode', 'ACTIVE']
		},
		ondblClickRow: function () {
			$('#height').focus();
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$('#height').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},
	{
		title:"Select Blood Code",
		open: function(){
			dialog_bloodGroup.urlParam.filterCol=['compcode', 'recstatus'];
			dialog_bloodGroup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			
		}
	},'urlParam','radio','tab',false
);
dialog_bloodGroup.makedialog(true);

button_state_antenatal('empty_antenatal');
button_state_antenatal('empty_pregnancy');
button_state_antenatal('empty_ultrasound');
function button_state_antenatal(state){
	switch(state){
		case 'empty_antenatal':
			$("#toggle_antenatal").removeAttr('data-toggle');
			$('#cancel_antenatal').data('oper','add_antenatal');
			$('#new_antenatal,#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'add_antenatal':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','add_antenatal');
			$("#new_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'edit_antenatal':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','edit_antenatal');
			$("#edit_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#new_antenatal').attr('disabled',true);
			break;
		case 'wait_antenatal':
			dialog_bloodGroup.on();
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$("#save_antenatal,#cancel_antenatal").attr('disabled',false);
			$('#edit_antenatal,#new_antenatal').attr('disabled',true);
			break;
		case 'empty_pregnancy':
			$("#toggle_antenatal").removeAttr('data-toggle');
			$('#cancel_pregnancy').data('oper','add_pregnancy');
			$('#new_pregnancy,#save_pregnancy,#cancel_pregnancy,#edit_pregnancy').attr('disabled',true);
			break;
		case 'add_pregnancy':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_pregnancy').data('oper','add_pregnancy');
			$("#new_pregnancy").attr('disabled',false);
			$('#save_pregnancy,#cancel_pregnancy,#edit_pregnancy').attr('disabled',true);
			break;
		case 'edit_pregnancy':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_pregnancy').data('oper','edit_pregnancy');
			$("#edit_pregnancy").attr('disabled',false);
			$('#save_pregnancy,#cancel_pregnancy,#new_pregnancy').attr('disabled',true);
			break;
		case 'wait_pregnancy':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$("#save_pregnancy,#cancel_pregnancy").attr('disabled',false);
			$('#edit_pregnancy,#new_pregnancy').attr('disabled',true);
			break;
		case 'empty_ultrasound':
			$("#toggle_antenatal").removeAttr('data-toggle');
			$('#cancel_ultrasound').data('oper','add_ultrasound');
			$('#new_ultrasound,#save_ultrasound,#cancel_ultrasound,#edit_ultrasound').attr('disabled',true);
			break;
		case 'add_ultrasound':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_ultrasound').data('oper','add_ultrasound');
			$("#new_ultrasound").attr('disabled',false);
			$('#save_ultrasound,#cancel_ultrasound,#edit_ultrasound').attr('disabled',true);
			break;
		case 'edit_ultrasound':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_ultrasound').data('oper','edit_ultrasound');
			$("#edit_ultrasound").attr('disabled',false);
			$('#save_ultrasound,#cancel_ultrasound,#new_ultrasound').attr('disabled',true);
			break;
		case 'wait_ultrasound':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$("#save_ultrasound,#cancel_ultrasound").attr('disabled',false);
			$('#edit_ultrasound,#new_ultrasound').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_antenatal(obj){	
	emptyFormdata(errorField,"#formAntenatal");
	emptyFormdata(errorField,"#formPregnancy");
	emptyFormdata(errorField,"#formUltrasound");

	//panel header
	$('#name_show_antenatal').text(obj.Name);
	$('#mrn_show_antenatal').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_antenatal').text((obj.Sex).toUpperCase());
	$('#dob_show_antenatal').text(dob_chg(obj.DOB));
	$('#age_show_antenatal').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_antenatal').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_antenatal').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_antenatal').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_antenatal').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_antenatal').text(if_none(obj.areaDesc).toUpperCase());

	//formAntenatal
	$('#mrn_antenatal').val(obj.MRN);
	$("#episno_antenatal").val(obj.Episno);

	//formPregnancy
	$('#mrn_pregnancy').val(obj.MRN);
	$("#episno_pregnancy").val(obj.Episno);

	//formUltrasound
	$('#mrn_ultrasound').val(obj.MRN);
	$("#episno_ultrasound").val(obj.Episno);

	// PREVIOUS OBSTETRICS HISTORY
	urlParam_PrevObstetrics.filterVal[0] = obj.MRN;

	// CURRENT PREGNANCY
	urlParam_CurrPregnancy.filterVal[0] = obj.MRN;
	urlParam_CurrPregnancy.filterVal[1] = obj.Episno;

	// OBSTETRICS ULTRASOUND SCAN
	urlParam_ObstetricsUltrasound.filterVal[0] = obj.MRN;

	var saveParam={
        action:'get_table_antenatal',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.MRN,
    	episno:obj.Episno
    };

    $.post( "./antenatal/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata_antenatal("#formAntenatal",data.antenatal);
			autoinsert_rowdata_antenatal("#formPregnancy",data.pregnancy);
			// autoinsert_rowdata_antenatal("#formUltrasound",data.antenatal_ultrasound);
			refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'add_prevObstetrics');
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_currPregnancy');
			refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'add_obstetricsUltrasound');
			button_state_antenatal('edit_antenatal');
			button_state_antenatal('edit_pregnancy');
			// button_state_antenatal('edit_ultrasound');
        }else{
			button_state_antenatal('add_antenatal');
			button_state_antenatal('add_pregnancy');
			// button_state_antenatal('add_ultrasound');
        }

	});
	
}

function populate_ultrasound(obj){
	emptyFormdata(errorField,"#formUltrasound");

	$('#mrn_ultrasound').val(obj.mrn);
	$("form#formUltrasound input[name='date']").val(moment(obj.date,"DD/MM/YYYY").format("YYYY-MM-DD"));

	let param = {
		action: 'get_table_ultrasound',
		url: 'antenatal/table',
		mrn: obj.mrn,
		date: moment(obj.date,"DD/MM/YYYY").format("YYYY-MM-DD"),
	}

	$.get("./antenatal/table?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data.rows)){
			autoinsert_rowdata_antenatal("#formUltrasound",data.rows);
			button_state_antenatal('edit_ultrasound');
		}
	});
}

function autoinsert_rowdata_antenatal(form,rowData){
	console.log(rowData);
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

function saveForm_antenatal(callback){
	var saveParam={
        action:'save_table_antenatal',
        oper:$("#cancel_antenatal").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formAntenatal").serializeArray();
	
	values = values.concat(
        $('#formAntenatal input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formAntenatal input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formAntenatal input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formAntenatal select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./antenatal/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_pregnancy(callback){
	var saveParam={
        action:'save_table_pregnancy',
        oper:$("#cancel_pregnancy").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formPregnancy").serializeArray();
	
	values = values.concat(
        $('#formPregnancy input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formPregnancy input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formPregnancy input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formPregnancy select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./antenatal/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_ultrasound(callback){
	var saveParam={
        action:'save_table_ultrasound',
        oper:$("#cancel_ultrasound").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formUltrasound").serializeArray();
	
	values = values.concat(
        $('#formUltrasound input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formUltrasound input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formUltrasound input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formUltrasound select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./antenatal/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





