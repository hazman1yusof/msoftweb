
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridObstetricsUltraScan url/////////////////////////////////////////////////
var urlParam_ObstetricsUltraScan = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: '',
	table_id: 'idno',
	filterCol:['mrn','episno'],
	filterVal:['',''],
}

/////////////////////parameter for jqGridCurrPregnancy url/////////////////////////////////////////////////
var urlParam_CurrPregnancy = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: '',
	table_id: 'idno',
	filterCol:['mrn','episno'],
	filterVal:['',''],
}

$(document).ready(function () {

	var fdl = new faster_detail_load();

	disableForm('#formAntenatal');

	$("#new_antenatal").click(function(){
		button_state_antenatal('wait');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_antenatal").click(function(){
		button_state_antenatal('wait');
		enableForm('#formAntenatal');
		rdonly('#formAntenatal');
		// dialog_mrn_edit.on();
		
	});

	$("#save_antenatal").click(function(){
		disableForm('#formAntenatal');
		if( $('#formAntenatal').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_antenatal(function(){
				$("#cancel_antenatal").data('oper','edit');
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

	// to autocheck the checkbox bila fill in textarea
	$("#cerebrum_text").on("keyup blur", function () {
        $(".cerebrum").prop("checked", this.value !== "");
	});

	$("#cavumSeptumPellucidum_text").on("keyup blur", function () {
        $(".cavumSeptumPellucidum").prop("checked", this.value !== "");
	});

	$("#falxCerebellum_text").on("keyup blur", function () {
        $(".falxCerebellum").prop("checked", this.value !== "");
	});

	$("#cerebellum_text").on("keyup blur", function () {
        $(".cerebellum").prop("checked", this.value !== "");
	});

	$("#lowerLip_text").on("keyup blur", function () {
        $(".lowerLip").prop("checked", this.value !== "");
	});

	$("#nose_text").on("keyup blur", function () {
        $(".nose").prop("checked", this.value !== "");
	});

	$("#rightEyes_text").on("keyup blur", function () {
		$(".rightEyes").prop("checked", this.value !== "");
	});

	$("#leftEyes_text").on("keyup blur", function () {
		$(".leftEyes").prop("checked", this.value !== "");
	});

	$("#anteriorChestWall_text").on("keyup blur", function () {
		$(".anteriorChestWall").prop("checked", this.value !== "");
	});

	$("#fourChamberView_text").on("keyup blur", function () {
		$(".fourChamberView").prop("checked", this.value !== "");
	});

	$("#cordInsertion_text").on("keyup blur", function () {
		$(".cordInsertion").prop("checked", this.value !== "");
	});

	$("#rightKidney_text").on("keyup blur", function () {
		$(".rightKidney").prop("checked", this.value !== "");
	});

	$("#leftKidney_text").on("keyup blur", function () {
		$(".leftKidney").prop("checked", this.value !== "");
	});

	$("#bladder_text").on("keyup blur", function () {
		$(".bladder").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends

	/////////////////////parameter for saving url/////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}

	/////////////////////////////////// jqGridObstetricsUltraScan ///////////////////////////////////////////////////
	$("#jqGridObstetricsUltraScan").jqGrid({
		datatype: "local",
		editurl: "./antenatal/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'Date', name: 'date', width: 60, classes: 'wrap', editable:true,
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
			{ label: 'POA', name: 'poa', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'POG (SCAN)', name: 'pog', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'CRL', name: 'crl', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'BPD', name: 'bpd', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'HC', name: 'hc', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'AC', name: 'ac', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'FL', name: 'fl', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'ATD', name: 'atd', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'ALD', name: 'ald', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'EFBW', name: 'efbw', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'AFI', name: 'afi', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'PRES', name: 'pres', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'PLACENTA', name: 'placenta', classes: 'wrap', width: 60, editable: true, editoptions: {style: "text-transform: none" }},
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
		pager: "#jqGridPagerObstetricsUltraScan",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridObstetricsUltraScan_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridObstetricsUltraScan_iledit").click();
		},
	});

	jQuery("#jqGridObstetricsUltraScan").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
		  {startColumnName: 'crl', numberOfColumns: 7, titleText: 'mm = W + D'}
		]
	});

	//////////////////////////////////////////myEditOptions_add_ObstetricsUltraScan////////////////////////////////////////////////
	var myEditOptions_add_ObstetricsUltraScan = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();

			$("input[name='placenta']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridObstetricsUltraScan_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridObstetricsUltraScan',urlParam_ObstetricsUltraScan,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridObstetricsUltraScan',urlParam_ObstetricsUltraScan,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridObstetricsUltraScan').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'obstetricsUltraScan_save',
				});
			$("#jqGridObstetricsUltraScan").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit_ObstetricsUltraScan////////////////////////////////////////////////
	var myEditOptions_edit_ObstetricsUltraScan = {
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
				if (code == '9')$('#jqGridObstetricsUltraScan_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridObstetricsUltraScan',urlParam_ObstetricsUltraScan,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridObstetricsUltraScan',urlParam_ObstetricsUltraScan,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridObstetricsUltraScan').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./antenatal/form?"+
				$.param({
					episno:$('#episno_antenatal').val(),
					mrn:$('#mrn_antenatal').val(),
					action: 'obstetricsUltraScan_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridObstetricsUltraScan").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerObstetricsUltraScan////////////////////////////////////////////////
	$("#jqGridObstetricsUltraScan").inlineNav('#jqGridPagerObstetricsUltraScan', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_ObstetricsUltraScan
		},
		editParams: myEditOptions_edit_ObstetricsUltraScan
	}).jqGrid('navButtonAdd', "#jqGridPagerObstetricsUltraScan", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGridObstetricsUltraScan").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
			} else {
				var result = confirm("Are you sure you want to delete this row?");
				if (result == true) {
					param = {
						_token: $("#csrf_token").val(),
						action: 'obstetricsUltraScan_save',
						idno: selrowData('#jqGridObstetricsUltraScan').idno,
					}
					$.post( "./antenatal/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function (data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function (data) {
						refreshGrid("#jqGridObstetricsUltraScan", urlParam_ObstetricsUltraScan);
					});
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				}
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerObstetricsUltraScan", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridObstetricsUltraScan", urlParam_ObstetricsUltraScan);
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
			{ label: 'Date', name: 'date', width: 100, classes: 'wrap', editable:true,
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
			{ label: 'Report', name: 'report', classes: 'wrap', width: 220, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 5}},
			{ label: 'POA/POG', name: 'poaORpog', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Uterine Size', name: 'uterineSize', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Albumin/Protein', name: 'albumin', classes: 'wrap', width: 90, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Sugar', name: 'sugar', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Weight', name: 'weight', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }, edittype:'custom',	editoptions:
					{ 	custom_element:weightCustomEdit,
						custom_value:galGridCustomValue 	
					}},
			{ label: 'Blood Pressure', name: 'bp_sys', classes: 'wrap', width: 90, editable: true, formatter: combineCol, editoptions: {style: "text-transform: none" },
				edittype:'custom',	editoptions:
					{ 	custom_element:bp_sysCustomEdit,
						custom_value:galGridCustomValue 	
					}},
			{ label: 'Blood Pressure', name: 'bp_dias', classes: 'wrap', width: 90, editable: true, hidden:true, editoptions: {style: "text-transform: none" }},
			{ label: 'Hb', name: 'hb', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Oedema', name: 'oedema', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Lie', name: 'fetusLie', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Pres', name: 'fetusPres', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'FHR', name: 'fetusHeartRate', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'FM', name: 'fetalMovement', classes: 'wrap', width: 80, editable: true, editoptions: {style: "text-transform: none" }},
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
			if(addmore_jqgrid.more == true){$('#jqGridCurrPregnancy_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridCurrPregnancy_iledit").click();
		},
	});

	jQuery("#jqGridCurrPregnancy").jqGrid('setGroupHeaders', {
		useColSpanStyle: true, 
		groupHeaders:[
		  {startColumnName: 'poaORpog', numberOfColumns: 9, titleText: 'Tests'},
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

			$("input[name='fetalMovement']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridCurrPregnancy_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_exam');
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
			$("input[name='fetalMovement']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridCurrPregnancy_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add_exam');
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
	}).jqGrid('navButtonAdd', "#jqGridPagerCurrPregnancy", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGridCurrPregnancy").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
			} else {
				var result = confirm("Are you sure you want to delete this row?");
				if (result == true) {
					param = {
						_token: $("#csrf_token").val(),
						action: 'currPregnancy_save',
						idno: selrowData('#jqGridCurrPregnancy').idno,
					}
					$.post( "./antenatal/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function (data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function (data) {
						refreshGrid("#jqGridCurrPregnancy", urlParam_CurrPregnancy);
					});
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				}
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerCurrPregnancy", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridCurrPregnancy", urlParam_CurrPregnancy);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	function weightCustomEdit(val, opt) {

		return $(`<div class="input-group">
					<input id="ncase_weight" name="ncase_weight" type="number" class="" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
					<span class="input-group-addon">kg</span>
				</div>`);
	}

	function bp_sysCustomEdit(val, opt) {
		// val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group">
		            <input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
		            <input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onkeypress="if(this.value.length==6) return false;">
		            <span class="input-group-addon" style='padding:2px;'>mm<br>Hg</span>
		        </div>`);
	}

	function combineCol(cellvalue, options, rowObject){
		// return cellvalue + ' ' + rowObject.bp_sys + ' ' + rowObject.bp_dias;
		return ``
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

var dialog_bloodGroup= new ordialog(
	'anr_bloodgroup','hisdb.bloodgroup',"#formAntenatal input[name='anr_bloodgroup']",errorField,
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

button_state_antenatal('empty');
function button_state_antenatal(state){
	switch(state){
		case 'empty':
			$("#toggle_antenatal").removeAttr('data-toggle');
			$('#cancel_antenatal').data('oper','add');
			$('#new_antenatal,#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','add');
			$("#new_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#edit_antenatal').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$('#cancel_antenatal').data('oper','edit');
			$("#edit_antenatal").attr('disabled',false);
			$('#save_antenatal,#cancel_antenatal,#new_antenatal').attr('disabled',true);
			break;
		case 'wait':
			dialog_bloodGroup.on();
			$("#toggle_antenatal").attr('data-toggle','collapse');
			$("#save_antenatal,#cancel_antenatal").attr('disabled',false);
			$('#edit_antenatal,#new_antenatal').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_antenatal(obj){	
	emptyFormdata(errorField,"#formAntenatal");

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
			autoinsert_rowdata_antenatal("#formAntenatal",data.an_pathistory);
			autoinsert_rowdata_antenatal("#formAntenatal",data.an_pathealth);
			button_state_antenatal('edit');
        }else{
			button_state_antenatal('add');
        }

	});
	
}

function autoinsert_rowdata_antenatal(form,rowData){
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





