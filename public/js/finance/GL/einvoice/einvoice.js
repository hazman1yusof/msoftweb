
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	$('body').click(function(){
		myfail_msg.clear_fail();
		myfail_msg_verify.clear_fail();
		myfail_msg_dm.clear_fail();
	});

	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	var myfail_msg_verify = new fail_msg_func('div#fail_msg_verifytin');
	var myfail_msg_dm = new fail_msg_func('div#fail_msg_dm');
	page_to_view_only($('#viewonly').val());

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		url:'./einvoice/table',
		action:'maintable',
		unit:$('#unit').val(),
	}

	$('#unit').change(function(){
		urlParam.unit = $('#unit').val();

		refreshGrid("#jqGrid", urlParam);
	});

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true, key:true},	
			{ label: 'compcode', name: 'compcode', hidden:true},					
			{ label: 'source', name: 'source', hidden:true},
			{ label: 'TT', name: 'trantype', width: 25, classes: 'wrap'},
			{ label: 'Bill No', name: 'auditno', width: 40, canSearch: true, checked:true},
			{ label: 'Invoice No.', name: 'invno',width: 40},
			{ label: 'MRN', name: 'mrn', width: 30, canSearch: true},
			{ label: 'Episno', name: 'episno', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Patient Name', name: 'Name', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'New IC', name: 'newic', width: 50},
			{ label: 'Debtor Code', name: 'debtorcode', width: 50, canSearch: true},
			{ label: 'Debtor Name', name: 'dbname', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'TIN', name: 'tinid', width: 50},
			{ label: 'Amount', name: 'amount', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
			{ label: 'Bill Date', name: 'entrydate', width: 40, canSearch: true},
			{ label: 'Submit By', name: 'LHDNSubBy', width: 40},
			{ label: 'Status', name: 'LHDNStatus', width: 40},
			{ label: 'newic', name: 'newic', hidden:true},
			{ label: 'tinid', name: 'tinid', hidden:true},
			{ label: 'Line No', name: 'lineno_',hidden:true},
			{ label: 'url', name: 'url',hidden:true},
			{ label: 'address1', name: 'address1', hidden:true},
			{ label: 'address2', name: 'address2', hidden:true},
			{ label: 'address3', name: 'address3', hidden:true},
			{ label: 'postcode', name: 'postcode', hidden:true},
			{ label: 'teloffice', name: 'teloffice', hidden:true},
			{ label: 'statecode', name: 'statecode', hidden:true},
			// { label: ' ', name: 'Checkbox',sortable:false, width: 25,align: "center", formatter: formatterCheckbox },
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			urlParam_acctent.invno = selrowData('#jqGrid').invno;
			urlParam_acctent.lineno_ = selrowData('#jqGrid').lineno_;
			urlParam_acctent.auditno = selrowData('#jqGrid').auditno;
			urlParam_acctent.dbname = selrowData('#jqGrid').dbname;
			// refreshGrid("#gridacctent", urlParam_acctent);
			$("#jqGrid").data('lastselrow',rowid);
		},
		loadComplete: function(){
			if($('#jqGrid').data('lastselrow') == 'none' || $('#jqGrid').data('lastselrow') == undefined){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($('#jqGrid').data('lastselrow'));
				$('#jqGrid tr#' + $('#jqGrid').data('lastselrow')).focus();
			}
			$("#jqGridPager #jqGrid_ilsave").hide();
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
			$("#jqGridPager #jqGrid_ilsave").hide();

			// $('input[type=checkbox].cbsel_jqgrid').on( "click", function() {
			// 	if($(this).prop("checked")){
			// 		$("input[type='checkbox'].cbsel_jqgrid").prop("checked", false);
			// 		$(this).prop("checked", true);
			// 	}else{
			// 		$("input[type='checkbox'].cbsel_jqgrid").prop("checked", false);
			// 	}
			// });
		},
	});

	jqgrid_label_align_right("#jqGrid");

	function check_cust_rules(rowid){
		var chk = ['costcode','description'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid input[name='"+e+"']",false);
			}
		})
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: false,
		edit: false,
		cancel: false,
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	$('#reprint_bill').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		window.open('./ordcom/table?action=final_bill_invoice&mrn='+mrn+'&episno='+episno, '_blank');
	});

	$('#reprint__summbill').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		window.open('./ordcom/table?action=showpdf_summ_final&mrn='+mrn+'&episno='+episno, '_blank');
	});


	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1','#searchForm','on');
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	if($('#viewonly').val() == 'viewonly'){
		addParamField('#jqGrid',false,urlParam);
		urlParam.viewonly = 'viewonly';
		urlParam.auditno = $('#viewonly_auditno').val();
		urlParam.lineno_ = $('#viewonly_lineno_').val();
	}else{
		addParamField('#jqGrid',true,urlParam);
	}

	$("#acctent_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#acctent_panel",100);
		$("#gridacctent").jqGrid ('setGridWidth', Math.floor($("#acctent_c")[0].offsetWidth-$("#acctent_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridacctent",false,parseInt($('#acctent_c').prop('clientHeight'))-150);
		refreshGrid("#gridacctent", urlParam_acctent);
	});

	var urlParam_acctent ={
		url:'./reprintBill/table',
		action:'acctent_sales',
		invno:'',
		lineno_:'',
	}

	$("#gridacctent").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true, key:true},	
			{ label: 'compcode', name: 'compcode', hidden:true},
			{ label: 'Date', name: 'date', width: 30, classes: 'wrap',formatter:dateFormatter_},
			{ label: 'Description', name: 'description', width: 150, classes: 'wrap', canSearch: true},
			{ label: 'Account', name: 'account', width: 50, classes: 'wrap', hidden:true},
			{ label: 'Account Name', name: 'accountname', width: 100, classes: 'wrap'},
			{ label: 'Debit', name: 'debit', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
			{ label: 'Credit', name: 'credit', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:true,
		paging:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 3000,
		pager: "#jqGridPageracctent",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
			if($('#gridacctent').data('lastselrow') == 'none'){
				$("#gridacctent").setSelection($("#gridacctent").getDataIDs()[0]);
			}else{
				$("#gridacctent").setSelection($('#gridacctent').data('lastselrow'));
				$('#gridacctent tr#' + $('#gridacctent').data('lastselrow')).focus();
			}
			calc_jq_height_onchange("gridacctent",false,parseInt($('#acctent_c').prop('clientHeight'))-150);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
			if($('#gridacctent').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#gridacctent").setSelection($("#gridacctent").getDataIDs()[0]);
			}

			// $.each('input[type=checkbox].cbsel_jqgrid', function( index, value ) {
			// 	console.log(index);
			// });
		},
	});

	jqgrid_label_align_right("#gridacctent");

	$("#gridacctent").inlineNav('#jqGridPageracctent', {
		add: false,
		edit: false,
		cancel: false,
	}).jqGrid('navButtonAdd', "#jqGridPageracctent", {
		id: "jqGridPageracctentRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#gridacctent", urlParam_acctent);
		},
	});

	$('#Scol').on('change', whenchangetodate);

	function whenchangetodate(){
		$('#actdate_from, #actdate_to').val('');
		if($('#Scol').val() == 'entrydate'){
			$("input[name='Stext']").hide("fast");
			$("#actdate_text").show("fast");
		}else{
			$("input[name='Stext']").show("fast");
			$("#actdate_text").hide("fast");
			// $("input[name='Stext']").off('change', searchbydate);
		}
	}

	$('#actdate_search').click(function(){
		urlParam.searchCol = ['entrydate'];
		urlParam.datefrom = $('#actdate_from').val();
		urlParam.dateto = $('#actdate_to').val();

		refreshGrid("#jqGrid", urlParam);
	});

	$('#a_sales_acctent,#a_cost_acctent').click(function(){
		if($(this).data('type') == 'sales'){
			$('#acctent_title_span').text('Sales');
			urlParam_acctent.action = 'acctent_sales';
			refreshGrid("#gridacctent", urlParam_acctent);
		}else{
			$('#acctent_title_span').text('Cost');
			urlParam_acctent.action = 'acctent_cost';
			refreshGrid("#gridacctent", urlParam_acctent);
		}
	});

	$('#printinvoice').click(function(){
		window.open(selrowData('#jqGrid').url, "_blank");
	});

	$("#dialog_verifytin")
	  .dialog({
		width: 5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			myfail_msg_verify.clear_fail();
			let data = selrowData('#jqGrid');

			$('#mrn').val(data.mrn);
			$('#newic').val(data.newic);
			$('#dbname').val(data.dbname);
			$('#tinid').val(data.tinid);
			if($('#dialog_verifytin').data('submit_einvoice') == 'true'){
				$('#submit_einvoice').show();
				$('#save_verifytin').hide();
			}else{
				$('#save_verifytin').show();
				$('#submit_einvoice').hide();
			}
		},
		close: function( event, ui ) {
			myfail_msg_verify.clear_fail();
			$('#mrn').val('');
			$('#newic').val('');
			$('#dbname').val('');
			$('#tinid').val('');
			refreshGrid("#jqGrid", urlParam);
			$('#dialog_verifytin').data('submit_einvoice','false');
		}
	  });

	$('#verifytin').click(function(){
        $('#dialog_verifytin').dialog('open');
	});

	$('#check_verifytin').click(function(){
		$('#check_verifytin,#save_verifytin').attr('disabled',true);

		if($('#newic').val() == ''){
			alert('New IC is needed');
			$('#newic').focus();
			$('#check_verifytin,#save_verifytin').attr('disabled',false);
			return 0;
		}

		let seldata = selrowData('#jqGrid');

		if(seldata.debtortype == 'PT' || seldata.debtortype == 'PR'){
			return 0;
		}

		var param={
			action:'check_verifytin',
			url: './einvoice/table',
			debtorcode:seldata.debtorcode,
			newic:$('#newic').val()
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		}).fail(function(data) {
			console.log(data);
			$('#check_verifytin,#save_verifytin').attr('disabled',false);
		}).success(function(data) {
			$('#tinid').val(data);
			$('#check_verifytin,#save_verifytin').attr('disabled',false);
		});
	});

	$('#save_verifytin').click(function(){
		$('#check_verifytin,#save_verifytin').attr('disabled',true);

		let seldata = selrowData('#jqGrid');

		var param={
			action:'save_verifytin',
			url: './einvoice/table',
			debtorcode:seldata.debtorcode,
			newic:$('#newic').val(),
			tinid:$('#tinid').val()
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		}).fail(function(data) {
			console.log(data);
			$('#check_verifytin,#save_verifytin').attr('disabled',false);
		}).success(function(data) {
			console.log(data);
			$('#check_verifytin,#save_verifytin').attr('disabled',false);
			$("#dialog_verifytin").dialog('close');
		});
	});

	$("#dialog_user_login")
	  .dialog({
		width: 3/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			myfail_msg.clear_fail();
		},
		close: function( event, ui ) {
			myfail_msg.clear_fail();
			$('#username_login').val('');
			$('#password_login').val('');
		}
	  });

    $('#btn_open_dialog_login').click(function(){
        $('#dialog_user_login').dialog('open');
    });

    $('#login_submit').click(function(){
		myfail_msg.clear_fail();
    	if($('#formdata_login').isValid({requiredFields:''},conf,true)){
	    	$('#login_submit').attr('disabled',true);

			let seldata = selrowData('#jqGrid');

			var param={
				action:'login_submit',
				url: './einvoice/table',
				username:$('#username_login').val(),
				password:$('#password_login').val()
			}
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			}).fail(function(data) {
				myfail_msg.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
				$('#login_submit').attr('disabled',false);
			}).success(function(data) {

				$('#login_submit').attr('disabled',false);
				$("#dialog_user_login").dialog('close');
				$('#dialog_verifytin').data('submit_einvoice','true');
	        	$('#verifytin').click();
			});
    	}
	});

	$('#submit_einvoice').click(function(){
		myfail_msg_verify.clear_fail();
		$('#submit_einvoice').attr('disabled',true);

		let seldata = selrowData('#jqGrid');

		var param={
			action:'einvoice_submit',
			url: './einvoice/table',
			idno:seldata.idno,
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		}).fail(function(data) {
			myfail_msg_verify.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			$('#submit_einvoice').attr('disabled',false);
		}).success(function(data) {

			window.open('./einvoice/table?action=einvoice_show&idno='+seldata.idno, '_blank');
			$('#submit_einvoice').attr('disabled',false);
			$("#dialog_verifytin").dialog('close');
		});
	});

	$("#dialog_debtormast")
	  .dialog({
		width: 8/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			myfail_msg_dm.clear_fail();
			let data = selrowData('#jqGrid');

			$('#payercode_dm').val(data.debtorcode);
			$('#payername_dm').val(data.dbname);
			$('#address1_dm').val(data.address1);
			$('#address2_dm').val(data.address2);
			$('#address3_dm').val(data.address3);
			$('#postcode_dm').val(data.postcode);
			$('#telhp_dm').val(data.teloffice);
			$('#statecode_dm').val(data.statecode);

		},
		close: function( event, ui ) {
			refreshGrid("#jqGrid", urlParam);
			myfail_msg_dm.clear_fail();

			$('#payercode_dm').val('');
			$('#payername_dm').val('');
			$('#address1_dm').val('');
			$('#address2_dm').val('');
			$('#address3_dm').val('');
			$('#postcode_dm').val('');
			$('#telhp_dm').val('');
			$('#statecode_dm').val('');
		}
	  });

	$('#debtormast_edit').click(function(){
		$("#dialog_debtormast").dialog('open');
	});

	$('#save_dm').click(function(){
		myfail_msg_dm.clear_fail();

    	if($('#formdata_dm').isValid({requiredFields:''},conf,true)){
    		$('#save_dm').attr('disabled',true);

			let seldata = selrowData('#jqGrid');

			var param={
				action:'einvoice_save_dm',
				url: './einvoice/table',
				payercode_dm:$('#payercode_dm').val(),
				payername_dm:$('#payername_dm').val(),
				address1_dm:$('#address1_dm').val(),
				address2_dm:$('#address2_dm').val(),
				address3_dm:$('#address3_dm').val(),
				postcode_dm:$('#postcode_dm').val(),
				telhp_dm:$('#telhp_dm').val(),
			}
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			}).fail(function(data) {
				myfail_msg_dm.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
				$('#save_dm').attr('disabled',false);
			}).success(function(data) {

				$('#save_dm').attr('disabled',false);
				$("#dialog_debtormast").dialog('close');
			});
    	}
	});

});



function dateFormatter_(cellvalue, options, rowObject){
	return moment(cellvalue, 'YYYY-MM-DD HH:mm:ss').format("DD-MM-YYYY");
}
function formatterCheckbox(cellvalue, options, rowObject){
	return "<input type='checkbox' class='cbsel_jqgrid' name='checkbox_selection' id='checkbox_selection_"+rowObject['idno']+"' data-idno='"+rowObject['idno']+"' data-rowid='"+options.rowId+"'>";
}