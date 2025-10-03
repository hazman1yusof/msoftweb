
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
		myfail_msg_dm.clear_fail();
	});

	var fdl = new faster_detail_load();
	var myfail_msg_dm = new fail_msg_func('div#fail_msg_dm');

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		url:'./einvoice/table',
		action:'maintable_ip',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true,key:true},
			{ label: 'CompCode', name: 'CompCode', hidden: true},
			{ label: 'MRN', name: 'NewMrn',width:5, canSearch: true, checked:true},
			{ label: 'Name', name: 'Name',width:14, canSearch: true},
			{ label: 'New I/C', name: 'Newic',width:8, canSearch: true},
			{ label: 'Address 1', name: 'Address1',width:20 },
			{ label: 'Address 2', name: 'Address2',width:20 },
			{ label: 'Address 3', name: 'Address3',width:20 },
			{ label: 'Postcode', name: 'Postcode',width:5 },
			{ label: 'citycode', name: 'citycode', hidden: true},
			{ label: 'AreaCode', name: 'AreaCode', hidden: true},
			{ label: 'StateCode', name: 'StateCode', hidden: true},
			{ label: 'CountryCode', name: 'CountryCode', hidden: true},
			{ label: 'telh', name: 'telh', hidden: true},
			{ label: 'telhp', name: 'telhp', hidden: true},
			{ label: 'MRN', name: 'MRN', hidden: true},
			{ label: 'Episno', name: 'Episno', hidden: true},
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

	$("#dialog_debtormast")
	  .dialog({
		width: 8/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			myfail_msg_dm.clear_fail();
			let data = selrowData('#jqGrid');

			$('#payercode_dm').val(data.NewMrn);
			$('#payername_dm').val(data.Name);
			$('#address1_dm').val(data.Address1);
			$('#address2_dm').val(data.Address2);
			$('#address3_dm').val(data.Address3);
			$('#postcode_dm').val(data.Postcode);
			$('#telhp_dm').val(data.telhp);
			$('#statecode_dm').val(data.StateCode);

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

	$('#print_ip').click(function(){
		window.open('./einvoice/table?action=print_implant_patient', '_blank');
	});

});