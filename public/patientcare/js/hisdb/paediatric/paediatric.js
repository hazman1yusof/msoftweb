
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

/////////////////////parameter for jqGridMedicalSurgical url/////////////////////////////////////////////////
var urlParam_MedicalSurgical = {
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

	disableForm('#formPaediatric');

	$("#new_paediatric").click(function(){
		button_state_paediatric('wait');
		enableForm('#formPaediatric');
		rdonly('#formPaediatric');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_paediatric").click(function(){
		button_state_paediatric('wait');
		enableForm('#formPaediatric');
		rdonly('#formPaediatric');
		// dialog_mrn_edit.on();
		
	});

	$("#save_paediatric").click(function(){
		disableForm('#formPaediatric');
		if( $('#formPaediatric').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_paediatric(function(){
				$("#cancel_paediatric").data('oper','edit');
				$("#cancel_paediatric").click();
				// $("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#formPaediatric');
			rdonly('#formPaediatric');
		}

	});

	$("#cancel_paediatric").click(function(){
		disableForm('#formPaediatric');
		button_state_paediatric($(this).data('oper'));
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
	$("#specify1_text").on("keyup blur", function () {
        $("#specify1").prop("checked", this.value !== "");
	});

	$("#specify2_text").on("keyup blur", function () {
        $("#specify2").prop("checked", this.value !== "");
	});

	$("#specify3_text").on("keyup blur", function () {
        $("#specify3").prop("checked", this.value !== "");
	});
	// to autocheck the checkbox bila fill in textarea ends

	/////////////////////parameter for saving url/////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}

	/////////////////////////////////// jqGridMedicalSurgical ///////////////////////////////////////////////////
	$("#jqGridMedicalSurgical").jqGrid({
		datatype: "local",
		editurl: "./paediatric/form",
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
			{ label: 'Disease', name: 'disease', classes: 'wrap', width: 120, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Treatment', name: 'treatment', classes: 'wrap', width: 120, editable: true, editoptions: {style: "text-transform: none" }},
			{ label: 'Note', name: 'note', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: {style: "width: -webkit-fill-available;" ,rows: 5}},
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
		pager: "#jqGridPagerMedicalSurgical",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGridMedicalSurgical_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridMedicalSurgical_iledit").click();
		},
	});

	//////////////////////////////////////////myEditOptions////////////////////////////////////////////////
	var myEditOptions_add_MedicalSurgical = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();

			$("input[name='note']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridMedicalSurgical_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridMedicalSurgical',urlParam_MedicalSurgical,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridMedicalSurgical',urlParam_MedicalSurgical,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridMedicalSurgical').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "./paediatric/form?"+
				$.param({
					episno:$('#episno_paediatric').val(),
					mrn:$('#mrn_paediatric').val(),
					action: 'paediatric_save',
				});
			$("#jqGridMedicalSurgical").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////myEditOptions_edit////////////////////////////////////////////////
	var myEditOptions_edit_MedicalSurgical = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			// dialog_examTriage.on();
			
			// $("input[name='grpcode']").attr('disabled','disabled');
			$("input[name='note']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridMedicalSurgical_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridMedicalSurgical',urlParam_MedicalSurgical,'add_exam');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridMedicalSurgical',urlParam_MedicalSurgical,'add_exam');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			let data = $('#jqGridMedicalSurgical').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./paediatric/form?"+
				$.param({
					episno:$('#episno_paediatric').val(),
					mrn:$('#mrn_paediatric').val(),
					action: 'paediatric_edit',
					_token: $("#csrf_token").val()
				});
			$("#jqGridMedicalSurgical").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////////////////////jqGridPagerMedicalSurgical////////////////////////////////////////////////
	$("#jqGridMedicalSurgical").inlineNav('#jqGridPagerMedicalSurgical', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_MedicalSurgical
		},
		editParams: myEditOptions_edit_MedicalSurgical
	}).jqGrid('navButtonAdd', "#jqGridPagerMedicalSurgical", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGridMedicalSurgical").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
			} else {
				var result = confirm("Are you sure you want to delete this row?");
				if (result == true) {
					param = {
						_token: $("#csrf_token").val(),
						action: 'paediatric_save',
						idno: selrowData('#jqGridMedicalSurgical').idno,
					}
					$.post( "./paediatric/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function (data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function (data) {
						refreshGrid("#jqGridMedicalSurgical", urlParam_MedicalSurgical);
					});
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				}
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerMedicalSurgical", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridMedicalSurgical", urlParam_MedicalSurgical);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

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

var dialog_bloodgroup_child= new ordialog(
	'bloodgroup_child','hisdb.bloodgroup',"#formPaediatric input[name='bloodgroup_child']",errorField,
	{	colModel:[
			{label:'Blood Code',name:'bloodcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
			filterCol:['compcode', 'recstatus'],
			filterVal:['session.compcode', 'ACTIVE']
		},
		ondblClickRow: function () {
			$('#rhesus').focus();
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$('#rhesus').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},
	{
		title:"Select Blood Code",
		open: function(){
			dialog_bloodgroup_child.urlParam.filterCol=['compcode', 'recstatus'];
			dialog_bloodgroup_child.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			
		}
	},'urlParam','radio','tab',false
);
dialog_bloodgroup_child.makedialog(true);

var dialog_bloodgroup_mother= new ordialog(
	'bloodgroup_mother','hisdb.bloodgroup',"#formPaediatric input[name='bloodgroup_mother']",errorField,
	{	colModel:[
			{label:'Blood Code',name:'bloodcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
			filterCol:['compcode', 'recstatus'],
			filterVal:['session.compcode', 'ACTIVE']
		},
		ondblClickRow: function () {
			$('#breastFed').focus();
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$('#breastFed').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},
	{
		title:"Select Blood Code",
		open: function(){
			dialog_bloodgroup_mother.urlParam.filterCol=['compcode', 'recstatus'];
			dialog_bloodgroup_mother.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			
		}
	},'urlParam','radio','tab',false
);
dialog_bloodgroup_mother.makedialog(true);

// button_state_paediatric('empty');
function button_state_paediatric(state){
	switch(state){
		case 'empty':
			$("#toggle_paediatric").removeAttr('data-toggle');
			$('#cancel_paediatric').data('oper','add');
			$('#new_paediatric,#save_paediatric,#cancel_paediatric,#edit_paediatric').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_paediatric").attr('data-toggle','collapse');
			$('#cancel_paediatric').data('oper','add');
			$("#new_paediatric").attr('disabled',false);
			$('#save_paediatric,#cancel_paediatric,#edit_paediatric').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_paediatric").attr('data-toggle','collapse');
			$('#cancel_paediatric').data('oper','edit');
			$("#edit_paediatric").attr('disabled',false);
			$('#save_paediatric,#cancel_paediatric,#new_paediatric').attr('disabled',true);
			break;
		case 'wait':
			dialog_bloodgroup_child.on();
			dialog_bloodgroup_mother.on();
			$("#toggle_paediatric").attr('data-toggle','collapse');
			$("#save_paediatric,#cancel_paediatric").attr('disabled',false);
			$('#edit_paediatric,#new_paediatric').attr('disabled',true);
			break;
	}
}

//screen current patient//
function populate_paediatric(obj){	
	emptyFormdata(errorField,"#formPaediatric");

	//panel header
	$('#name_show_paediatric').text(obj.Name);
	$('#mrn_show_paediatric').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_paediatric').text((obj.Sex).toUpperCase());
	$('#dob_show_paediatric').text(dob_chg(obj.DOB));
	$('#age_show_paediatric').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_paediatric').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_paediatric').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_paediatric').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_paediatric').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_paediatric').text(if_none(obj.areaDesc).toUpperCase());

	//formPaediatric
	$('#mrn_paediatric').val(obj.MRN);
	$("#episno_paediatric").val(obj.Episno);

	// var saveParam={
    //     action:'get_table_paediatric',
    // }
    // var postobj={
    // 	_token : $('#csrf_token').val(),
    // 	mrn:obj.MRN,
    // 	episno:obj.Episno
    // };

    // $.post( "./paediatric/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    // },'json').fail(function(data) {
    //     alert('there is an error');
    // }).success(function(data){
    // 	if(!$.isEmptyObject(data)){
	// 		// autoinsert_rowdata_paediatric("#formPaediatric",data.an_pathistory);
	// 		// autoinsert_rowdata_paediatric("#formPaediatric",data.an_pathealth);
	// 		button_state_paediatric('edit');
    //     }else{
	// 		button_state_paediatric('add');
    //     }

	// });
	
}

function autoinsert_rowdata_paediatric(form,rowData){
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

function saveForm_paediatric(callback){
	var saveParam={
        action:'save_table_paediatric',
        oper:$("#cancel_paediatric").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	// sex_edit : $('#sex_edit').val(),
    	// idtype_edit : $('#idtype_edit').val()

    };

	values = $("#formPaediatric").serializeArray();
	
	values = values.concat(
        $('#formPaediatric input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#formPaediatric input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#formPaediatric input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#formPaediatric select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "./paediatric/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}





