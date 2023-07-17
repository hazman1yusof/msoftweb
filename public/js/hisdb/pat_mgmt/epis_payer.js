var urlParam_epno_payer = {
	action:'pat_enq_payer',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

var urlParam_gletdept = {
	action:'gletdept',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

var urlParam_gletitem = {
	action:'gletitem',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

$(document).ready(function () {

	$('#my_a_payr').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			$('#mdl_payer').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	var errorField_epno_payer = [];
	conf_epno_payer = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_epno_payer.length > 0) {
				return {
					element: $(errorField_epno_payer[0]),
					message: ''
				}
			}
		},
	};

	$("#jqGrid_epno_payer").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'No', name: 'lineno', width: 30 },
            { label: 'Payer', name: 'payercode', width: 80  },
            { label: 'Name', name: 'payercode_desc', width: 200  },
            { label: 'Fin Class', name: 'pay_type' , width: 50 },
            { label: 'Limit Amt.', name: 'pyrlmtamt' , width: 100 },
            { label: 'All Group', name: 'allgroup' , width: 50, formatter: allgroupformat, unformat: allgroupunformat },
            { label: 'billtype_desc', name: 'billtype_desc' , hidden: true },
            { label: 'idno', name: 'idno', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'name', name: 'name', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'epistycode', name: 'epistycode', hidden: true },
            { label: 'pyrmode', name: 'pyrmode' , hidden: true },
            { label: 'alldept', name: 'alldept' , hidden: true },
            { label: 'lastupdate', name: 'lastupdate' , hidden: true },
            { label: 'lastuser', name: 'lastuser' , hidden: true },
            { label: 'billtype', name: 'billtype' , hidden: true },
            { label: 'refno', name: 'refno' , hidden: true },
            { label: 'computerid', name: 'computerid' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_epno_payer",
		onSelectRow:function(rowid, selected){
			populate_epno_payer(selrowData("#jqGrid_epno_payer"));
		},
		loadComplete: function(){
			emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
			$('#jqGrid_epno_payer_ilsave,#jqGrid_epno_payer_ilcancel').hide();

			let reccount = $('#jqGrid_epno_payer').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_epno_payer('add_edit');
			}else{
				button_state_epno_payer('add');
			}
			$("#jqGrid_epno_payer").setSelection($("#jqGrid_epno_payer").getDataIDs()[0]);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_gletdept").jqGrid({
		datatype: "local",
		colModel: [			
			{label:'grpcode', name:'grpcode'},
			{label:'allitem', name:'allitem'},
			{label:'grplimit', name:'grplimit'},
			{label:'inditemlimit', name:'inditemlimit'},
			{label:'compcode', name:'compcode', hidden:true},
			{label:'payercode', name:'payercode', hidden:true},
			{label:'mrn', name:'mrn', hidden:true},
			{label:'episno', name:'episno', hidden:true},
			{label:'deptcode', name:'deptcode', hidden:true},
			{label:'deptlimit', name:'deptlimit', hidden:true},
			{label:'deptbal', name:'deptbal', hidden:true},
			{label:'grpbal', name:'grpbal', hidden:true},
			{label:'lastupdate', name:'lastupdate', hidden:true},
			{label:'lastuser', name:'lastuser', hidden:true},
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_gletdept",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_gletitem").jqGrid({
		datatype: "local",
		colModel: [
			{label:'chgcode', name:'chgcode'},
			{label:'totitemlimit', name:'totitemlimit'},
			{label:'compcode', name:'compcode',hidden:true},
			{label:'payercode', name:'payercode',hidden:true},
			{label:'mrn', name:'mrn',hidden:true},
			{label:'episno', name:'episno',hidden:true},
			{label:'deptcode', name:'deptcode',hidden:true},
			{label:'grpcode', name:'grpcode',hidden:true},
			{label:'totitembal', name:'totitembal',hidden:true},
			{label:'lastupdate', name:'lastupdate',hidden:true},
			{label:'lastuser', name:'lastuser',hidden:true},
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_gletitem",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_epno_payer").inlineNav('#jqGridPager_epno_payer', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_epno_payer", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		},
	});

	$("#tabPayer").on("shown.bs.collapse", function(){
		$("#jqGrid_epno_payer").jqGrid ('setGridWidth', Math.floor($("#jqGrid_epno_payer_c")[0].offsetWidth-$("#jqGrid_epno_payer_c")[0].offsetLeft-0));
		urlParam_epno_payer.mrn = $("#mrn_episode").val();
		urlParam_epno_payer.episno = $("#txt_epis_no").val();
		urlParam_gletdept.mrn = $("#mrn_episode").val();
		urlParam_gletdept.episno = $("#txt_epis_no").val();
		urlParam_gletitem.mrn = $("#mrn_episode").val();
		urlParam_gletitem.episno = $("#txt_epis_no").val();

		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		$('#mrn_epno_payer').val($("#mrn_episode").val());
		$('#episno_epno_payer').val($("#txt_epis_no").val());
		$('#epistycode_epno_payer').val(selrowData('#jqGrid_episodelist').epistycode);
		$('#name_epno_payer').val($('#txt_epis_name').text());

		$("#mdl_reference").data('from','payer');
    	$("#refno_epno_payer_btn").off('click',btn_refno_info_onclick);
		$("#refno_epno_payer_btn").on('click',btn_refno_info_onclick);
	});

	var epno_payer_payercode = new ordialog(
		'epno_payer_payercode', 'debtor.debtormast', '#payercode_epno_payer', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'debtortype', name: 'debtortype', width: 2, hidden:true },
			],
			urlParam: {
				url:'./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val()
			},
			ondblClickRow: function () {
				let selrow = selrowData('#'+epno_payer_payercode.gridname);
				$(epno_payer_payercode.textfield).parent().next().html('');
				$('#payercode_desc_epno_payer').val(selrow.name);
				$('#pay_type_epno_payer').val(selrow.debtortype);

			}
		},{
			title: "Select Payer Code",
			open: function () {
				epno_payer_payercode.urlParam.url='./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val();


				$('div[aria-describedby="otherdialog_epno_payer_payercode"]').css("z-index", "1200");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1100");
			}
		},'urlParam','radio','tab'
	);
	epno_payer_payercode.makedialog(false);
	
	$("#add_epno_payer").click(function(){
		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		button_state_epno_payer('wait');
		enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		epno_payer_payercode.on();
		$("#save_epno_payer").data('oper','add');
		$('#pyrlmtamt_epno_payer').val(9999999.99);
		$('#allgroup_epno_payer').val(1);

		var rows = $('#jqGrid_epno_payer').getGridParam("reccount");
		$('#lineno_epno_payer').val(rows+1);
	});

	$("#edit_epno_payer").click(function(){
		let selrow = $('#jqGrid_epno_payer').jqGrid ('getGridParam', 'selrow');
		if(selrow == null){
			alert('Select payer first!');
		}else{
			button_state_epno_payer('wait');
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
			epno_payer_payercode.on();
			$("#save_epno_payer").data('oper','edit');
		}
	});

	$("#save_epno_payer").click(function(){
		disableForm('#form_epno_payer');
		if( $('#form_epno_payer').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_epno_payer(function(){
				refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
			});
		}else{
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		}

	});

	function saveForm_epno_payer(callback){

        var serializedForm = $("#form_epno_payer").serializeArray();

	    serializedForm = serializedForm.concat(
	        $('#form_epno_payer select').map(
	        function() {
	            return {"name": this.name, "value": this.value}
	        }).get()
		);

	    var obj={
	    	action:'save_payer',
	        oper:$("#save_epno_payer").data('oper'),
	    	_token : $('#csrf_token').val()
	    };

	    $.post( "./pat_enq/form", $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_epno_payer").click(function(){
		button_state_epno_payer('empty');
		disableForm('#form_epno_payer');
		epno_payer_payercode.off();

		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
	});

	disableForm('#form_epno_payer');
	button_state_epno_payer('add');
	function button_state_epno_payer(state){
		switch(state){
			case 'empty':
				$('#add_epno_payer,#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_epno_payer,#edit_epno_payer").attr('disabled',false);
				$('#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add':
				$("#add_epno_payer").attr('disabled',false);
				$('#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'wait':
				$("#save_epno_payer,#cancel_epno_payer").attr('disabled',false);
				$('#add_epno_payer,#edit_epno_payer').attr('disabled',true);
				break;
		}
	}

	function populate_epno_payer(obj){
		var form = '#form_epno_payer';
		var except = [];

		$.each(obj, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if( except != undefined && except.indexOf(index) === -1){
				input.val(decodeEntities(value));
			}
		});
	}
		
	$('#except_epno_payer').click(function(){
        $('#mdl_glet').modal('show');
	})

	$("#mdl_glet").on("shown.bs.modal", function(){
		var epno_payer = selrowData("#jqGrid_epno_payer");
		console.log(epno_payer)
		$('#glet_mrn').val(epno_payer.mrn);
		$('#glet_name').val(epno_payer.Name);
		$('#glet_episno').val(epno_payer.episno);
		$('#glet_payercode').val(epno_payer.payercode);
		$('#glet_payercode_desc').val(epno_payer.payercode_desc);
		$('#glet_totlimit').val(epno_payer.pyrlmtamt);
		$('#glet_allgroup').val(epno_payer.allgroup);
		$('#glet_refno').val(epno_payer.refno);
		$("#jqGrid_gletdept").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
		$("#jqGrid_gletitem").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
		
	});

});

function allgroupformat(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return '<span data-orig='+cellvalue+'>Yes</span>';
	}else{
		return '<span data-orig='+cellvalue+'>No</span>';
	}
}

function allgroupunformat(cellvalue, options, rowObject){
	return $(rowObject).find('span').data('orig');
}

// var textfield_modal = new textfield_modal();
// textfield_modal.ontabbing();
// textfield_modal.checking();
// textfield_modal.clicking();