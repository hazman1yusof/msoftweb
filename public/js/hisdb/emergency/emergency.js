
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'logic',
		language: {
			requiredFields: ''
		}
	});

	function calanderposition(){
		var width = Math.floor($("#colmd_outer")[0].offsetWidth - $("#colmd_outer")[0].offsetLeft);
		$('#mydate_glpd').css('width',width);
		$('#mydate_glpd').css('height',width);
	}
	calanderposition();

	var gldatepicker = $('#mydate').glDatePicker({
		zIndex: 0,
		showAlways: true,
		onClick: function(target, cell, date, data) {
			urlParam.filterVal[0] = moment(date).format('YYYY-MM-DD');
			refreshGrid("#jqGrid", urlParam);
	    }
	}).glDatePicker(true);

	var errorField = [];
	conf = {
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					//element : $('#'+errorField[0]),
					message: ''
				}
			}
		},
	};

	$('input').on('beforeValidation', function(value, lang, config) {

    });

	//////////////////////////////////////////////////////////////

	$('#Newic').blur(function(){
        if($(this).val() != ''){
            let newrplc = $(this).val().replace(/-/g, "");
            $(this).val(newrplc);//untuk buang hyphen lepas tulis i/c
            let first6dig = $(this).val().substring(0,6);
            let lastdig = $(this).val().substr(-1, 1);
            let dobval = turntoappropriatetime(first6dig);
            var gender = turntoappropriategender(lastdig)? 'F':'M';
            $("#DOB").val(dobval);//utk auto letak dob lepas tulis i/c\
            $("#sex").val(gender);//utk auto letak gender lepas tulis i/c\
        }
    })

    function turntoappropriategender(digit){
    	if(digit % 2 === 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    function turntoappropriatetime(moments){
        let year = moments.substring(0,2);
        let month = moments.substring(2,4);
        let day = moments.substring(4,6);
        let yearnow = String(moment().get('year')).substring(2,4);
        if(parseInt(yearnow)<=parseInt(year)){
            year = "19".concat(year);
        }else{
            year = "20".concat(year);
        }
        return moment(year+month+day, "YYYYMMDD").format("YYYY-MM-DD");
    }

	var butt1 = [{
		text: "Save", click: function () {
			if ($('#registerformdata').isValid({ requiredFields: '' }, conf, true)) {
				saveFormdata("#jqGrid", "#registerform", "#registerformdata", oper, saveParam, urlParam);
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var butt2 = [{
		text: "Close", click: function () {
			$(this).dialog('close');
		}
	}];

	  $("#regBtn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					$("#registerform").dialog("open");

            });

	var oper='add';
	$("#registerform")
		.dialog({
			width: 8 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#registerformdata');
						rdonly("#registerformdata");
						hideOne("#registerformdata");
						rdonly("#registerform");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#registerformdata');
						frozeOnEdit("#registerform");
						rdonly("#registerformdata");
						rdonly("#registerform");
						$('#registerformdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#registerformdata');
						$('#registerformdata :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
						//dialog_dept.handler(errorField);
					}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#registerformdata');
				//$('.alert').detach();
				$('#registerformdata .alert').detach();
				// $("#registerformdata a").off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", butt1);
				}
			},
			buttons: butt1,
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
		

	var urlParam = {
		action: 'get_table_default',
		url: '/util/get_table_default',
		fixPost: 'true',
		field: ['e.MRN', 'e.Episno','p.Name','p.Newic'],
		table_name: ['hisdb.apptbook AS a','hisdb.episode AS e','hisdb.pat_mast AS p','hisdb.doctor as d'],
		join_type: ['INNER JOIN','INNER JOIN','INNER JOIN'],
		join_onCol: ['a.episno','a.mrn','a.loccode'],
		join_onVal: ['e.episno','p.mrn','d.doctorcode'],
		join_filterCol: [['e.mrn on =','e.compcode on ='],['p.compcode on ='],['d.compcode on =']],
		join_filterVal: [['a.mrn','a.compcode'],['a.compcode'],['a.compcode']],
		filterCol:['a.apptdatefr','a.type'],
	 	filterVal:[ moment(gldatepicker.options.selectedDate).format('YYYY-MM-DD HH:mm:ss'),'ED']
	}

	///////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/emergency/form',
		field: '',
		oper: oper,
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'e_compcode', width: 5, hidden: true },
			{ label: 'patmast_idno', name: 'p_idno', width: 5, hidden: true },
			{ label: 'MRN', name: 'e_MRN', width: 10, classes: 'wrap', formatter: padzero, unformat: unpadzero, canSearch: true, checked: true,  },
			{ label: 'Episode No', name: 'e_Episno', width: 20 ,canSearch: true,classes: 'wrap',hidden:true },
			{ label: 'IC No', name: 'p_Newic', width: 15 ,classes: 'wrap' },
			{ label: 'Registered Date', name: 'e_reg_date', width: 15 ,classes: 'wrap' },
			{ label: 'Registered Time', name: 'e_reg_time', width: 15 ,classes: 'wrap' },
			{ label: 'Name', name: 'p_Name', width: 30 ,canSearch: true,classes: 'wrap' },
			// { label: 'Payer', name: 'q_', width: 20 ,classes: 'wrap' },
			{ label: 'Doctor', name: 'd_doctorname', width: 20 ,classes: 'wrap' },
			{ label: 'Status', name: 'e_episstatus', width: 10 ,classes: 'wrap',hidden:true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		onSelectRow:function(rowid,status,e){
			var data=selrowData('#jqGrid');
			$('#span_biodata_label').text(' Biodata MRN: '+data.e_MRN);
			$('#biodata_but_emergency').data('mrn',data.e_MRN);
			$('#btn_register_patient').data('idno',data.p_idno);
		},
		gridComplete: function () {
		},

	});
   
    var dialog_mrn = new ordialog(
		'mrn', ['hisdb.pat_mast AS pt','hisdb.racecode AS rc'], "#registerform input[name='mrn']", errorField,
		{
			colModel: [
				{	label: 'MRN', name: 'pt_MRN', width: 50, classes: 'pointer', formatter: padzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'pt_Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'Newic', name: 'pt_Newic', width: 100, classes: 'pointer', canSearch: true},
				{	label: 'DOB', name: 'pt_DOB', width: 50, classes: 'pointer', canSearch: true},
				{	label: 'CardID', name: 'pt_idnumber', width: 50, classes: 'pointer', canSearch: true},
				{	label: 'Oldic', name: 'pt_Oldic', width: 200, classes: 'pointer',hidden:true},
				{	label: 'RaceCode', name: 'pt_RaceCode', width: 200, classes: 'pointer',hidden:true},
				{	label: 'Description', name: 'rc_description', width: 200, classes: 'pointer',hidden:true},
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#registerform input[name='MRN']").val(data['pt_MRN']);
				$("#registerform input[name='patname']").val(data['pt_Name']);
				$("#registerform input[name='Newic']").val(data['pt_Newic']);
				$("#registerform input[name='DOB']").val(data['pt_DOB']);
				$("#registerform input[name='Oldic']").val(data['pt_Oldic']);
				$("#registerform input[name='idnumber']").val(data['pt_idnumber']);
				$("#registerform input[name='race']").val(data['pt_RaceCode']);
				$("#registerform input[name='description_race']").val(data['rc_description']);
				$(dialog_race.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select MRN",
			open: function () {
				dialog_mrn.urlParam.fixPost="true";
				dialog_mrn.urlParam.table_id = "none_";
				dialog_mrn.urlParam.filterCol = ['pt.compcode'];
				dialog_mrn.urlParam.filterVal = ['9A'];
				dialog_mrn.urlParam.join_type = ['LEFT JOIN'];
				dialog_mrn.urlParam.join_onCol = ['pt.RaceCode'];
				dialog_mrn.urlParam.join_onVal = ['rc.code'];
				dialog_mrn.urlParam.join_filterCol = [['pt.compcode on ='],[]];
				dialog_mrn.urlParam.join_filterVal = [['rc.compcode'],[]];
			},
		}, 'none','dropdown'
	);
	dialog_mrn.makedialog(true);

	var dialog_race = new ordialog(
		'race', 'hisdb.racecode', "#registerform input[name='race']", errorField,
		{
			colModel: [
				{	label: 'Race', name: 'code', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_race.gridname);
				$("#registerform input[name='description_race']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_race.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Race",
			open: function () {
				dialog_race.urlParam.filterCol = ['compcode'];
				dialog_race.urlParam.filterVal = ['9A'];
			},
		}, 'none'
	);
	dialog_race.makedialog(true);

    var dialog_financeclass = new ordialog(
		'financeclass', 'debtor.debtortype', "#registerform input[name='financeclass']", errorField,
		{
			colModel: [
				{	label: 'Debtor', name: 'debtortycode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_financeclass.gridname);
				if(data['debtortycode'] == "PT"){
					$("#payername").val($('#patname').val());
					$("#payer").val($('#mrn').val());
					$("#registerform input[name='fName']").val(data['description']);
				}else{
					let data = selrowData('#' + dialog_financeclass.gridname);
					$("#registerform input[name='fName']").val(data['description']);
				}
				$(dialog_financeclass.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Financial Class",
			open: function () {
				dialog_financeclass.urlParam.filterCol = ['compcode'];
				dialog_financeclass.urlParam.filterVal = ['9A'];
			},
		}, 'none'
	);
	dialog_financeclass.makedialog(true);

	var dialog_payer = new ordialog(
		'payer', ['debtor.debtormast','debtor.debtortype'], "#registerform input[name='payer']", errorField,
		{
			colModel: [
				{	label: 'Debtor Code', name: 'debtormast.debtorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Debtor Name', name: 'debtormast.name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'debtortype', name: 'debtortype.debtortycode', width: 100, classes: 'pointer'},
				{	label: 'debtortype_name', name: 'debtortype.description', width: 100, classes: 'pointer',hidden:true},
				{	label: 'recstatus', name: 'debtormast.recstatus', width: 100, classes: 'pointer'},
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_payer.gridname);
				$("#registerform input[name='payername']").val(data['name']);
				$("#registerform input[name='paytype']").val(data['debtortype']);
				$("#financeclass").val(data['debtortype']);
				$("#fName").val(data['telhp']);
				$(dialog_payer.textfield).parent().next().text(" ");

				if(data['recstatus'] == 'suspend'){
					var dialog = bootbox.dialog({
					    message: '<h2 class="text-center">Selected Payer are suspended</h2>',
					    className: 'alertmodal'
					});
				}
			}
		},
		{
			title: "Select Payer",
			open: function () {
				var financeclass = $('#financeclass').val();
				
				dialog_payer.urlParam.join_type = ['LEFT JOIN'];
				dialog_payer.urlParam.join_onCol = ['debtormast.debtortype'];
				dialog_payer.urlParam.join_onVal = ['debtortype.debtortycode'];
				dialog_payer.urlParam.filterCol = ['debtormast.compcode'];
				dialog_payer.urlParam.filterVal = ['debtormast.9A'];
			},
		}, 'none'
	);
	dialog_payer.makedialog(true);

	var dialog_billtype = new ordialog(
		'billtype', 'hisdb.billtymst', "#registerform input[name='billtype']", errorField,
		{
			colModel: [
				{	label: 'Bill Type', name: 'billtype', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_billtype.gridname);
				$("#registerform input[name='description_bt']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_billtype.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Bill Type",
			open: function () {
				dialog_billtype.urlParam.filterCol = ['compcode'];
				dialog_billtype.urlParam.filterVal = ['9A'];
			},
		}, 'none'
	);
	dialog_billtype.makedialog(true);

	var dialog_doctor = new ordialog(
		'doctor', 'hisdb.doctor', "#registerform input[name='doctor']", errorField,
		{
			colModel: [
				{	label: 'Doctor Code', name: 'doctorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Name', name: 'doctorname', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				// {	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_doctor.gridname);
				$("#registerform input[name='docname']").val(data['doctorname']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_doctor.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Doctor",
			open: function () {
				dialog_doctor.urlParam.filterCol = ['compcode'];
				dialog_doctor.urlParam.filterVal = ['9A'];
			},
		}, 'none'
	);
	dialog_doctor.makedialog(true);
	////////////////////formatter status////////////////////////////////////////
	function formatterstatus(cellvalue, option, rowObject) {
		if (cellvalue == 'A') {
			return 'Active';
		}

		if (cellvalue == 'D') {
			return 'Deactive';
		}

	}

	////////////////////unformatter status////////////////////////////////////////
	function unformat(cellvalue, option, rowObject) {
		if (cellvalue == 'Active') {
			return 'Active';
		}

		if (cellvalue == 'Deactive') {
			return 'Deactive';
		}

	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','adduser','adddate','upduser','upddate','recstatus']);

	//////////////////////////// background color//////////////////////////////
	$(".colorpointer").click(function(){
		var column = $(this).data('color');
		$('#'+column).click();
	});

	$('.bg_color').change(function(){
		var columncolor = $(this).attr('name');
		$('#btn_'+columncolor).css('background-color',$(this).val());
		savecolor(columncolor,$(this).val());
	});

	setColor();
	function setColor(){
		$('.bg_color').each(function(){
			var column = $(this).attr('name');
			$('#btn_'+column).css('background-color',$(this).val());
		});
	}

	function savecolor(columncolor,color){
		$.post( "/emergency/form",{oper:'savecolor',columncolor:columncolor,color:color,_token:$('#csrf_token').val()} , function( data ) {
	
		}).success(function(data){

		});
	}
	////////////////////////////////////////////////////////////////////////

	$('#patname').blur(function(){
		var value = $(this).val();
		if(value == 'unknown'){
			$('#mrn').val('00000000');
			$('#financeclass').val('PR');
			$('#fName').val('PERSON RESPONSIBLE');
			$('#payer').val('CA');
			$('#payername').val('CASH');
		}
	});

    populatecombo1();

    $("#biodata_but_emergency").click(function(){
    	var mrn = $(this).data('mrn');
    	get_biodata(mrn);
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","edit");
    });

	function get_biodata(mrn){
		$.getJSON('pat_mast/get_entry?action=get_pat',{mrn:mrn},function(data)
        {
            populate_pat_mast(data.data);
        });
	}

	function populate_pat_mast(data){
		var form = '#frm_patient_info';
		$.each(data, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else{
				input.val(value);
			}
		});

        desc_show.write_desc();
       	$('#txt_pat_age').val(gettheage($('#txt_pat_dob').val()));
	}

});
