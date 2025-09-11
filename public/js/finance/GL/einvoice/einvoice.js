
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

	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	page_to_view_only($('#viewonly').val());

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		url:'./einvoice/table',
		action:'maintable',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true, key:true},	
			{ label: 'compcode', name: 'compcode', hidden:true},					
			{ label: 'source', name: 'source', hidden:true},
			{ label: 'TT', name: 'trantype', width: 25, classes: 'wrap'},
			{ label: 'Bill No', name: 'auditno', width: 50, classes: 'wrap', canSearch: true, checked:true},
			{ label: 'Line No', name: 'lineno_', width: 30, classes: 'wrap',hidden:true},
			{ label: 'Invoice No.', name: 'invno',width: 50},
			{ label: 'MRN', name: 'mrn', width: 30, classes: 'wrap', canSearch: true},
			{ label: 'Episno', name: 'episno', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Patient Name', name: 'Name', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'Debtor Code', name: 'debtorcode', width: 50, classes: 'wrap', canSearch: true},
			{ label: 'Debtor Name', name: 'dbname', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'Amount', name: 'amount', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
			{ label: 'Bill Date', name: 'entrydate', width: 40, canSearch: true},
			{ label: 'Submit By', name: 'LHDNSubBy', width: 40},
			{ label: 'Status', name: 'LHDNStatus', width: 40},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 25,align: "center", formatter: formatterCheckbox },
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
		},
		loadComplete: function(){
			if($('#jqGrid').data('lastselrow') == 'none'){
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
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$("#jqGridPager #jqGrid_ilsave").hide();

			console.log($);
			$('input[type=checkbox].cbsel_jqgrid').on( "click", function() {
				$()
			});
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

	$("#dialog_user_login")
	  .dialog({
		width: 3/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
			myfail_msg.clear_fail();
			$('#username_login').val('');
			$('#password_login').val('');
			refreshGrid("#jqGrid", urlParam);
		}
	  });

    $('#btn_open_dialog_login').click(function(){
		if($("input[type='checkbox'][name='checkbox_selection']:checked").length == 0){
			alert('Please select at least 1 Invoice');
		}else{
        	$('#dialog_user_login').dialog('open')
		}
    });

    $('#login_submit').click(function(){
    	$('#login_submit').prop('disabled',true);
		myfail_msg.clear_fail();
		var idno_array = [];
		$("input[type='checkbox'][name='checkbox_selection']:checked").each(function( index ){
			idno_array.push($(this).data('idno'));
		});
        var param={
			action: 'submit_einvoice',
			idno: selrowData('#jqGrid').idno,
			username: $('#username_login').val(),
			password: $('#password_login').val(),
			_token: $("#_token").val(),
			idno_array: idno_array
		}
		$.post( "./einvoice/form",param, function( data ){
		}).fail(function(data) {
			myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
    		$('#login_submit').prop('disabled',false);
			//////////////////errorText(dialog,data.responseText);
		}).done(function(data){
    		$('#login_submit').prop('disabled',false);
    		var param={
    			idno_array: idno_array
    		}

			window.open('./einvoice/table?action=show_result&'+$.param(param), '_blank');
        	$('#dialog_user_login').dialog('close')
		});
	});

});

function dateFormatter_(cellvalue, options, rowObject){
	return moment(cellvalue, 'YYYY-MM-DD HH:mm:ss').format("DD-MM-YYYY");
}
function formatterCheckbox(cellvalue, options, rowObject){
	return "<input type='checkbox' class='cbsel_jqgrid' name='checkbox_selection' id='checkbox_selection_"+rowObject['idno']+"' data-idno='"+rowObject['idno']+"' data-rowid='"+options.rowId+"'>";
}