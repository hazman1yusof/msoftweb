
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
$.formUtils.loadModules('logic', null, function () {
	$.validate({}); // ini perlu kalu nak ada 'data-validation-optional-if-answered'
});

var gldatepicker_date;
$(document).ready(function () {
	// $("body").show();
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
			gldatepicker_date = date;
			urlParam.apptdatefr = moment(date).format('YYYY-MM-DD');
			if(moment(date).isSame(moment(), 'day')){
				$('#regBtn').attr('disabled',false);
			}else{
				$('#regBtn').attr('disabled',true);
			}
			refreshGrid("#jqGrid", urlParam);
			empty_registerformdata_edit();
			empty_formNursing();
			sel_idno = 0;
	    }
	}).glDatePicker(true);



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
					//element : $('#'+errorField[0]),
					message: ''
				}
			}
		},
	};

	$('input').on('beforeValidation', function(value, lang, config) {
		if($(this).attr('data-validation-optional-if-answered')!=''){
			console.log($(this).attr('data-validation-optional-if-answered'));
		}
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

			 var Newic = $('#Newic');
             var Oldic = $('#Oldic');

            if(Newic.val() == '' && Oldic.val() == '') {
            	// alert('Fill out new ic or old ic fields');
            }
			    //         else if(Newic.val() == '') {
			    //         alert('Oldic, please...');
			    //         }
			    //         else if(Oldic.val() == '') {
			    //         alert('Newic, please...');      
			    //         }
			    //         else {
			    //         alert('Yay!');
			    // }   
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
		url: '/emergency/table',
	 	apptdatefr:moment(gldatepicker.options.selectedDate).format('YYYY-MM-DD')
	}

	///////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/emergency/form',
		field: '',
		oper: oper,
	};

	var sel_idno = 0;
	var jqGrid_rowdata = null;
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'a_idno', width: 5, hidden: true, key:true },
			{ label: 'compcode', name: 'a_compcode', width: 5, hidden: true },
			{ label: 'MRN', name: 'a_mrn', width: 12, classes: 'wrap', formatter: padzero, unformat: unpadzero, canSearch: true, checked: true,  },
			{ label: 'Epis. No', name: 'a_Episno', width: 10 ,canSearch: true,classes: 'wrap' },
			{ label: 'IC No', name: 'a_icnum', width: 18 ,classes: 'wrap' , hidden: true },
			{ label: 'Registered Date', name: 'reg_date', width: 15 ,classes: 'wrap' },
			{ label: 'Registered Time', name: 'reg_time', width: 15 ,classes: 'wrap' },
			{ label: 'Name', name: 'a_pat_name', width: 30 ,canSearch: true,classes: 'wrap' },
			{ label: 'Doctor', name: 'd_doctorname', width: 20 ,classes: 'wrap' },
			{ label: 'Status', name: 'a_episstatus', width: 10 ,classes: 'wrap',hidden:true },

			{ label: 'Doctor', name: 'newic', hidden: true },
			{ label: 'Doctor', name: 'id_type', hidden: true },
			{ label: 'Doctor', name: 'oldic', hidden: true },
			{ label: 'Doctor', name: 'dob', hidden: true },
			{ label: 'Doctor', name: 'idnumber', hidden: true },
			{ label: 'Doctor', name: 'sex', hidden: true },
			{ label: 'Doctor', name: 'racecode', hidden: true },
			{ label: 'Doctor', name: 'race', hidden: true },
			{ label: 'Doctor', name: 'age', hidden: true },

			{ label: 'Doctor', name: 'pay_type', hidden: true },
			{ label: 'Doctor', name: 'pay_type_desc', hidden: true },
			{ label: 'Doctor', name: 'billtype', hidden: true },
			{ label: 'Doctor', name: 'billtype_desc', hidden: true },
			{ label: 'Doctor', name: 'admdoctor', hidden: true },
			{ label: 'Doctor', name: 'admdoctor_desc', hidden: true },

			{ label: 'religion', name: 'religion', hidden: true },
			{ label: 'occupation', name: 'occupation', hidden: true },
			{ label: 'citizen', name: 'citizen', hidden: true },
			{ label: 'area', name: 'area', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 365,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			// refreshGrid("#formTriageInfo", urlParam);
			// $("#formTriageInfo").trigger('reloadGrid');

			var retdata = jqGrid_rowdata.find(function(obj){
				if(obj.a_idno == rowid){
					return true;
				}
			});

			sel_idno = rowid;

			$('#biodata_but_emergency').data('bio_from_grid',selrowData("#jqGrid"));
			$('#episode_but_emergency').data('bio_from_grid',selrowData("#jqGrid"));

			empty_registerformdata_edit();
			empty_formNursing();
			
			populate_registerformdata_edit(selrowData("#jqGrid"));
			populate_formNursing(selrowData("#jqGrid"),retdata);
			populate_doctorNote_emergency(selrowData("#jqGrid"),retdata);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		loadComplete: function(data){
			jqGrid_rowdata = data.rows;
			let reccount = $('#jqGrid').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				empty_registerformdata_edit();
				empty_formNursing();
			}else{
				set_grid_color();
			}


			if (sel_idno == 0) {
				$('#' + $("#jqGrid").getDataIDs()[0]).click();
			}else{
				$('#'+sel_idno).click();
			}

			// document.getElementById('showTriage_curpt').style.display = 'inline'; //hide and show heading details dekat triage
		}
	});

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam,['reg_date','reg_time','newic','id_type','oldic','dob','sex','racecode','race','age','pay_type', 'pay_type_desc' ,'billtype' ,'billtype_desc' ,'admdoctor','admdoctor_desc']);

	function set_grid_color(){
		var rows = $("#jqGrid").getDataIDs();
	    for (var i = 0; i < rows.length; i++){
	    	let data = $("#jqGrid").jqGrid ('getRowData', rows[i]);
	    	switch(data.a_episstatus){
	    		case '':
    				$("#jqGrid").jqGrid('setRowData',rows[i],false, {background:$('#CurrentPTcolor').val()});
	    			break
	    		case 'cancel':
    				$("#jqGrid").jqGrid('setRowData',rows[i],false, {background:$('#CancelPTcolor').val()});
	    			break
	    		case 'discharge':
    				$("#jqGrid").jqGrid('setRowData',rows[i],false, {background:$('#DiscPTcolor').val()});
	    			break
	    		default:
	    			break;
	    	}
		}
	}
   
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
			urlParam: {
				fixPost:"true",
				table_id : "none_",
				filterCol : ['pt.compcode'],
				filterVal : ['9A'],
				join_type : ['LEFT JOIN'],
				join_onCol : ['pt.RaceCode'],
				join_onVal : ['rc.code'],
				join_filterCol : [['pt.compcode on = '],[]],
				join_filterVal : [['rc.compcode'],[]],
			},
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
				$(dialog_mrn.textfield).parent().next().text(" ");
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
		}, 'none','dropdown','tab'
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
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']

			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_race.gridname);
				$("#registerform input[name='description_race']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_race.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Race",
			open: function () {
				dialog_race.urlParam.filterCol = ['compcode'];
				dialog_race.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
	);
	dialog_race.makedialog(true);

    var dialog_financeclass = new ordialog(
		'financeclass', 'debtor.debtortype', "#registerform input[name='financeclass']", errorField,
		{
			colModel: [
				{	label: 'Debtor', name: 'debtortycode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']
				
			},
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
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Financial Class",
			open: function () {
				dialog_financeclass.urlParam.filterCol = ['compcode'];
				dialog_financeclass.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
	);
	dialog_financeclass.makedialog(true);

	var dialog_payer = new ordialog(
		'payer', 'debtor.debtormast', "#registerform input[name='payer']", errorField,
		{
			colModel: [
				{	label: 'Debtor Code', name: 'debtorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{	label: 'Debtor Name', name: 'name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'debtortype', name: 'debtortype', width: 200, classes: 'pointer',hidden:true},
				{	label: 'recstatus', name: 'recstatus', width: 200, classes: 'pointer',hidden:true},
				// {	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam:{
				
				filterCol : ['debtortype','compcode'],
				filterVal : ['PT','9A']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_payer.gridname);
				$("#registerform input[name='payername']").val(data['name']);
				$("#registerform input[name='paytype']").val(data['debtortype']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_payer.textfield).parent().next().text(" ");

				if(data['recstatus'] == 'suspend'){
					var dialog = bootbox.dialog({
					    message: '<h2 class="text-center">Selected Payer are suspended</h2>',
					    className: 'alertmodal'
					});
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Payer",
			open: function () {
				var financeclass = $('#financeclass').val();
				if( financeclass == 'PT'){
					dialog_payer.urlParam.filterCol = ['debtortype','compcode'];
					dialog_payer.urlParam.filterVal = ['PT','9A'];
				}else if( financeclass == 'CORP'){
					dialog_payer.urlParam.filterCol = ['debtortype','compcode'];
					dialog_payer.urlParam.filterVal = ['CORP','9A'];
				}else{
					dialog_payer.urlParam.filterCol = ['compcode'];
					dialog_payer.urlParam.filterVal = ['9A'];
				}
			},
		}, 'none','radio','tab'
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
			urlParam:{

				filterCol : ['compcode','opprice'],
				filterVal : ['9A','1']
				
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_billtype.gridname);
				$("#registerform input[name='description_bt']").val(data['description']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_billtype.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Bill Type",
			open: function () {
				dialog_billtype.urlParam.filterCol = ['compcode','opprice'];
				dialog_billtype.urlParam.filterVal = ['9A','1'];
			},
		},'none','radio','tab'
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
			urlParam:{

				filterCol : ['compcode'],
				filterVal : ['9A']
				
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_doctor.gridname);
				$("#registerform input[name='docname']").val(data['doctorname']);
				// $("#addForm input[name='telh']").val(data['telh']);
				// $("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_doctor.textfield).parent().next().text(" ");
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},
		{
			title: "Select Doctor",
			open: function () {
				dialog_doctor.urlParam.filterCol = ['compcode'];
				dialog_doctor.urlParam.filterVal = ['9A'];
			},
		},'none','radio','tab'
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
		$.post( "/emergency/form",{action:'save_table_default',oper:'savecolor',columncolor:columncolor,color:color,_token:$('#csrf_token').val()} , function( data ) {
	
		}).success(function(data){
			set_grid_color();
		});
	}
	////////////////////////////////////////////////////////////////////////

	$('#patname').blur(function(){
		var value = $("#mrn").val();
		if(value == '' || value == '00000000'){
			$('#mrn').val('00000000');
			$('#financeclass').val('PR');
			$('#fName').val('PERSON RESPONSIBLE');
			$('#payer').val('CASH');
			$('#payername').val('CASH');
			// $('#payer').val('00000000');
			// $('#payername').val('unknown');
		}
	});

	//////////////// start pasal biodata//////////////////////////
	$('#btn_register_patient').off('click',default_click_register);
	$('#btn_reg_proceed').off('click',default_click_proceed);

	$("#biodata_but_emergency").click(function(){

		var data = $(this).data('bio_from_grid');

		if(data==undefined){
			alert('no patient biodata selected');
		}else{

			var oper = 'edit';
			// populatecombo1();
	        $('#mdl_patient_info').modal({backdrop: "static"});
	        $("#btn_register_patient").data("oper",oper);

			populate_data_from_mrn(data.a_mrn,"#frm_patient_info");
		}

	});

	$('#btn_register_patient').on('click',function(){
		var data = $("#biodata_but_emergency").data('bio_from_grid');
        var apptbook_idno = data.a_idno;

        if($('#frm_patient_info').valid()){
            if($(this).data('oper') == 'add'){
                check_existing_patient(save_patient_apptrsc,{
                	"action":"apptrsc",
                	"param":['add',null,null,apptbook_idno]
                });
            }else{
	            let mrn =  $('#txt_pat_mrn').val();
	            let idno =  $('#txt_pat_idno').val();
                save_patient_apptrsc('edit',idno,mrn,apptbook_idno);
            }
        }
    });

    $('#btn_reg_proceed').on('click',function(){ /// sepatutnya takkan run function ni
		var data = $("#biodata_but_emergency").data('bio_from_grid');
        var apptbook_idno = data.idno;
        var checkedbox = $("#tbl_existing_record input[type='checkbox']:checked");

        if(checkedbox.closest("td").next().length>0){
            let mrn = checkedbox.data("mrn");
            let idno = checkedbox.data("idno");
            save_patient_apptrsc('edit',idno,mrn,apptbook_idno);
        }else{
            save_patient_apptrsc('add',null,null,apptbook_idno);
        }
    });

 	function save_patient_apptrsc(oper,idno,mrn="nothing",apptbook_idno){
 		var saveParam={
            action:'save_patient',
            field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','OccupCode','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno','first_visit_date','last_visit_date'],
            oper:oper,
            table_name:'hisdb.pat_mast',
            table_id:'idno',
            sysparam:null
        },_token = $('#csrf_token').val();

        if(oper=='add'){
            saveParam.sysparam = {source:'HIS',trantype:'MRN',useOn:'MRN'};
            var postobj = {_token:_token,apptbook_idno:apptbook_idno};
        }else if(oper == 'edit'){
            var postobj = {_token:_token,idno:idno,apptbook_idno:apptbook_idno,MRN:mrn};
        }

        $.post( "/emergency/form?"+$.param(saveParam), $("#frm_patient_info").serialize()+'&'+$.param(postobj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('there is an error');
        }).success(function(data){
            $('#mdl_patient_info').modal('hide');
            $('#mdl_existing_record').modal('hide');
			refreshGrid('#jqGrid',urlParam);
        });
 	}
	//////////////////////end pasal biodata/////////////////////////

	/////////////////start pasal episode//////////////////

	$('#episode_but_emergency').click(function(){
		var data = $(this).data('bio_from_grid');
		var form = '#episode_form';

		if(data==undefined){
			alert('no patient selected');
			return false;
		}

		var param={
            action:'get_value_default',
            field:"*",
            table_name:'hisdb.episode',
            table_id:'_none',
            filterCol:['compcode','mrn','episno'],filterVal:['session.compcode',data.a_mrn,data.a_Episno]
        };

        $.get( "/util/get_value_default?"+$.param(param), function( data ) {

        },'json').done(function(data) {

            if(data.rows.length > 0){

            	fail = false;
				if(data.rows[0].epistycode!='OP'){
					alert('This Patient was Registered as '+data.rows[0].epistycode);
					fail = true;
				}

                if(!fail){ 
                	$.each(data.rows[0], function( index, value ) {
	                    var input=$(form+" [name='"+index+"']");

	                    if(input.is("[type=radio]")){
	                        $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
	                    }else{
	                        input.val(value);
	                    }
	                    desc_show_epi.write_desc();
	                });
			        $('#editEpisode').modal({backdrop: "static"});
			        $('#editEpisode').modal('show');
				}
               

            }else{
                alert('MRN not found')
            }

        }).error(function(data){

        });
		// var episode_param={
		// 	action:'get_value_default',
		// 	table_name:'hisdb.episode',
		// 	url:'util/get_value_default',
		// 	field:['*'],
		// 	filterCol:['compcode','mrn','episno','reg_date'],
		// 	filterVal:['session.compcode',data.a_mrn,data.a_Episno,moment().format('YYYY-MM-DD')]
		// }

		// var fail = true;
		// $.get( episode_param.url+"?"+$.param(episode_param), function( data ) {
			
		// },'json').done(function(data) {
		// 	if(!$.isEmptyObject(data.rows[0])){
		// 		fail = false;
		// 		if(data.rows[0].epistycode!='OP'){
		// 			alert('This Patient was Registered as '+data.rows[0].epistycode);
		// 			fail = true;
		// 		}

		// 		if(!fail){
		// 			// populate_patient_episode(data.rows[0]);
		// 	        $('#editEpisode').modal({backdrop: "static"});
		// 	        $('#editEpisode').modal('show');
		// 		}
		// 	}
		// });

	});

	$("#jqGridTriageInfo_panel").on("show.bs.collapse", function(){
		$("#jqGridExamTriage").jqGrid ('setGridWidth', Math.floor($("#jqGridTriageInfo_c")[0].offsetWidth-$("#jqGridTriageInfo_c")[0].offsetLeft-228));
		$("#jqGridAddNotesTriage").jqGrid ('setGridWidth', Math.floor($("#jqGridTriageInfo_c")[0].offsetWidth-$("#jqGridTriageInfo_c")[0].offsetLeft-228));
	});
	
});
