var urlParam_epno_coverage = {
	action:'pat_enq_payer',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

$(document).ready(function () {

	var errorField_epno_coverage = [];
	conf_epno_coverage = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_epno_coverage.length > 0) {
				return {
					element: $(errorField_epno_coverage[0]),
					message: ''
				}
			}
		},
	};

	$("#jqGrid_epno_coverage").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'No', name: 'lineno', width: 30 },
            { label: 'Payer', name: 'payercode', width: 80  },
            { label: 'Name', name: 'payercode_desc', width: 200  },
            { label: 'Fin Class', name: 'pay_type' , width: 50 },
            { label: 'Limit Amt.', name: 'pyrlmtamt' , width: 100 ,formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
            { label: 'Balance Amt.', name: 'totbal' , width: 100 ,formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
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
            { label: 'ourrefno', name: 'ourrefno' , hidden: true },
            { label: 'computerid', name: 'computerid' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 100, 
		rowNum: 30,
		// pager: "#jqGridPager_epno_coverage",
		onSelectRow:function(rowid, selected){
			populate_epno_coverage(selrowData("#jqGrid_epno_coverage"));
			let rowdata = selrowData("#jqGrid_epno_coverage");

			if(rowdata.pay_type == 'PT'){
				button_state_epno_coverage('add');
			}else{
				if(rowdata.allgroup == 0){
					$('#except_epno_coverage').attr('disabled',false);
				}
			}
		},
		loadComplete: function(){
			emptyFormdata_div('#form_epno_coverage',['#mrn_epno_coverage','#episno_epno_coverage','#epistycode_epno_coverage','#name_epno_coverage']);
			$('#jqGrid_epno_coverage_ilsave,#jqGrid_epno_coverage_ilcancel').hide();

			let reccount = $('#jqGrid_epno_coverage').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_epno_coverage('add_edit');
			}else{
				button_state_epno_coverage('add');
			}
			$('#except_epno_coverage').attr('disabled',true);
			$("#jqGrid_epno_coverage").setSelection($("#jqGrid_epno_coverage").getDataIDs()[0]);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	// $("#jqGrid_epno_coverage").inlineNav('#jqGridPager_epno_coverage', {edit:false,add:false,del:false,search:false,
	// 	restoreAfterSelect: false
	// }).jqGrid('navButtonAdd', "#jqGridPager_epno_coverage", {
	// 	id: "jqGridPagerRefresh",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-refresh",
	// 	title: "Refresh Table",
	// 	onClickButton: function () {
	// 		refreshGrid("#jqGrid_epno_coverage", urlParam_epno_coverage);
	// 	},
	// });

	$("#tabcoverage").on("shown.bs.collapse", function(){
		var lastrowdata = getrow_bootgrid();
		$("#jqGrid_epno_coverage").jqGrid ('setGridWidth', Math.floor($("#jqGrid_epno_coverage_c")[0].offsetWidth-$("#jqGrid_epno_coverage_c")[0].offsetLeft-0));
		$('#jqGridPager_gletitem_left > table').eq(0).hide();
		urlParam_epno_coverage.mrn = lastrowdata.MRN;
		urlParam_epno_coverage.episno = lastrowdata.Episno;
		urlParam_gletdept.mrn = lastrowdata.MRN;
		urlParam_gletdept.episno = lastrowdata.Episno;
		urlParam_gletitem.mrn = lastrowdata.MRN;
		urlParam_gletitem.episno = lastrowdata.Episno;

		refreshGrid("#jqGrid_epno_coverage", urlParam_epno_coverage);
		$('#mrn_epno_coverage').val(lastrowdata.MRN);
		$('#episno_epno_coverage').val(lastrowdata.Episno);
		$('#epistycode_epno_coverage').val(lastrowdata.epistycode);
		$('#name_epno_coverage').val(lastrowdata.Name);

		$("#mdl_reference").data('from','payer');
    	$("#refno_epno_coverage_btn").off('click',btn_refno_info_onclick);
		$("#refno_epno_coverage_btn").on('click',btn_refno_info_onclick);
	});

	var epno_coverage_payercode = new ordialog(
		'epno_coverage_payercode', 'debtor.debtormast', '#payercode_epno_coverage', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'debtortype', name: 'debtortype', width: 2, hidden:true },
			],
			urlParam: {
				url:'./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_coverage').val(),
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let selrow = selrowData('#'+epno_coverage_payercode.gridname);
				$(epno_coverage_payercode.textfield).parent().next().html('');
				$('#payercode_desc_epno_coverage').val(selrow.name);
				$('#pay_type_epno_coverage').val(selrow.debtortype);

			}
		},{
			title: "Select Payer Code",
			open: function () {
				epno_coverage_payercode.urlParam.url='./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_coverage').val();
				epno_coverage_payercode.urlParam.filterCol=['compcode','recstatus'];
				epno_coverage_payercode.urlParam.filterVal=['session.compcode','ACTIVE'];


				$('div[aria-describedby="otherdialog_epno_coverage_payercode"]').css("z-index", "132");
				$('div.ui-widget-overlay.ui-front').css("z-index", "131");
			}
		},'urlParam','radio','tab'
	);
	epno_coverage_payercode.makedialog(false);
	
	$("#add_epno_coverage").click(function(){
		emptyFormdata_div('#form_epno_coverage',['#mrn_epno_coverage','#episno_epno_coverage','#epistycode_epno_coverage','#name_epno_coverage']);
		button_state_epno_coverage('wait');
		enableForm('#form_epno_coverage',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		epno_coverage_payercode.on();
		$("#save_epno_coverage").data('oper','add');
		$('#pyrlmtamt_epno_coverage').val(9999999.99);
		$('#allgroup_epno_coverage').val(1);

		var rows = $('#jqGrid_epno_coverage').getGridParam("reccount");
		$('#lineno_epno_coverage').val(rows+1);
	});

	$("#edit_epno_coverage").click(function(){
		let selrow = $('#jqGrid_epno_coverage').jqGrid ('getGridParam', 'selrow');
		if(selrow == null){
			alert('Select payer first!');
		}else{
			button_state_epno_coverage('wait');
			enableForm('#form_epno_coverage',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
			epno_coverage_payercode.on();
			$("#save_epno_coverage").data('oper','edit');
		}
	});

	$("#save_epno_coverage").click(function(){
		disableForm('#form_epno_coverage');
		if( $('#form_epno_coverage').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_epno_coverage(function(){
				refreshGrid("#jqGrid_epno_coverage", urlParam_epno_coverage);
			});
		}else{
			enableForm('#form_epno_coverage',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		}

	});

	function saveForm_epno_coverage(callback){

        var serializedForm = $("#form_epno_coverage").serializeArray();

	    serializedForm = serializedForm.concat(
	        $('#form_epno_coverage select').map(
	        function() {
	            return {"name": this.name, "value": this.value}
	        }).get()
		);

	    var obj={
	    	action:'save_payer',
	        oper:$("#save_epno_coverage").data('oper'),
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

	$("#cancel_epno_coverage").click(function(){
		button_state_epno_coverage('empty');
		disableForm('#form_epno_payer');
		epno_coverage_payercode.off();

		emptyFormdata_div('#form_epno_coverage',['#mrn_epno_coverage','#episno_epno_coverage','#epistycode_epno_coverage','#name_epno_coverage']);
		refreshGrid("#jqGrid_epno_coverage", urlParam_epno_coverage);
	});

	disableForm('#form_epno_coverage');
	button_state_epno_coverage('add');
	function button_state_epno_coverage(state){
		switch(state){
			case 'empty':
				$('#add_epno_coverage,#edit_epno_coverage,#save_epno_coverage,#cancel_epno_coverage').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_epno_coverage,#edit_epno_coverage").attr('disabled',false);
				$('#save_epno_coverage,#cancel_epno_coverage').attr('disabled',true);
				break;
			case 'add':
				$("#add_epno_coverage").attr('disabled',false);
				$('#edit_epno_coverage,#save_epno_coverage,#cancel_epno_coverage').attr('disabled',true);
				break;
			case 'wait':
				$("#save_epno_coverage,#cancel_epno_coverage").attr('disabled',false);
				$('#add_epno_coverage,#edit_epno_coverage').attr('disabled',true);
				break;
		}
	}

	function populate_epno_coverage(obj){
		var form = '#form_epno_coverage';
		var except = [];

		$.each(obj, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if( except != undefined && except.indexOf(index) === -1){
				input.val(decodeEntities(value));
			}
		});

		$('#pyrlmtamt_epno_coverage').val(numeral($('#pyrlmtamt_epno_coverage').val()).format('0,0.00'));
	}
		
	$('#except_epno_coverage').click(function(){
		$("#mdl_glet").off("shown.bs.modal");

		if(selrowData('#jqGrid_epno_coverage').allgroup == 0){
			$("#mdl_glet").on("shown.bs.modal", function(){
				var epno_coverage = selrowData("#jqGrid_epno_coverage");
				$('#glet_mrn').val(padzero7(epno_coverage.mrn));
				$('#glet_name').val(epno_coverage.name);
				$('#glet_episno').val(padzero7(epno_coverage.episno));
				$('#glet_payercode').val(epno_coverage.payercode);
				$('#glet_payercode_desc').val(epno_coverage.payercode_desc);
				$('#glet_totlimit').val(numeral(epno_coverage.pyrlmtamt).format('0,0.00'));
				$('#glet_allgroup').val(allgroupformat2(epno_coverage.allgroup));
				$('#glet_refno').val(epno_coverage.refno);
				$("#jqGrid_gletdept").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
				$("#jqGrid_gletitem").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
				
				refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
			});

        	$('#mdl_glet').modal('show');
		}
	});

});