
var urlParam_pkg={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_pkg').val(),
	mrn:'',
	episno:''
};
var myfail_msg_pkg = new fail_msg_func('div#fail_msg_pkg');
var mycurrency_pkg =new currencymode([]);
var mycurrency_np_pkg =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_pkg").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 80, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_pkg },
				formatter: showdetail_pkg,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_pkg },
				formatter: showdetail_pkg,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{
				label: 'Doctor Code', name: 'doctorcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: false, custom: true, custom_func: cust_rules_pkg },
				formatter: showdetail_pkg,
				edittype: 'custom', editoptions:
				{
					custom_element: doctorcodeCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_pkg },
				formatter: showdetail_pkg,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_pkg },
				formatter: showdetail_pkg,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_pkg,
					custom_value: galGridCustomValue_pkg
				},
			},
			{label: 'Cost<br>Price', name: 'cost_price', hidden: true },
			{ label: 'Unit<br>Price', name: 'unitprce', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{
				label: 'Quantity', name: 'quantity', width: 60, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{ label: 'Total<br>Amount', name: 'amount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Discount<br>Amount', name: 'discamt', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:abscurrency,
				editrules:{required: true},editoptions:{readonly: "readonly"}},
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Net<br>Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter_pkg,
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Dosage', name: 'remark', hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'drugindicator', name: 'drugindicator', width: 80, classes: 'wrap', hidden: true },
			{ label: 'frequency', name: 'frequency', width: 80, classes: 'wrap', hidden: true },
			{ label: 'ftxtdosage', name: 'ftxtdosage', width: 80, classes: 'wrap', hidden: true },
			{ label: 'addinstruction', name: 'addinstruction', width: 80, classes: 'wrap', hidden: true },
			{ label: 'drugindicator_desc', name: 'drugindicator_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'frequency_desc', name: 'frequency_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'ftxtdosage_desc', name: 'ftxtdosage_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'addinstruction_desc', name: 'addinstruction_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'id', name: 'id', width: 10, hidden: true, key:true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: false,
		viewrecords: true,
		loadonce: false,
		width: 1500,
		height: 200,
		rowNum: 10,
		sortname: 'id',
		sortorder: "desc",
		pager: "#jqGrid_pkg_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_pkg",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_pkg.clear_fail();
			if($("#jqGrid_pkg").data('lastselrow')==undefined||$("#jqGrid_pkg").data('lastselrow')==null){
				$("#jqGrid_pkg").setSelection($("#jqGrid_pkg").getDataIDs()[0]);
			}else{
				$("#jqGrid_pkg").setSelection($("#jqGrid_pkg").data('lastselrow'));
			}
			$("#jqGrid_pkg").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_pkg.clear_fail();

			let justsave = $("#jqGrid_pkg").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_pkg_iladd').click();
				}, 500 );
			}
			$("#jqGrid_pkg").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_pkg_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			var selrowdata = $('#jqGrid_pkg').jqGrid ('getRowData', rowid);

			// write_detail_pkg([
			// 	{span:'#jqgrid_detail_pkg_chgcode',value:selrowdata.chgcode},
			// 	{span:'#jqgrid_detail_pkg_chgcode_desc',value:selrowdata.chgcode},
			// 	{span:'#jqgrid_detail_pkg_dept',value:selrowdata.deptcode},
			// 	{span:'#jqgrid_detail_pkg_cost_price',value:selrowdata.cost_price},
			// 	{span:'#jqgrid_detail_pkg_unitprice',value:selrowdata.unitprce},
			// 	{span:'#jqgrid_detail_pkg_discamt',value:selrowdata.discamt},
			// 	{span:'#jqgrid_detail_pkg_taxamt',value:selrowdata.taxamount},
			// ]);

			// write_detail_dosage(selrowdata);
		},
		ondblClickRow: function(rowId) {
			$('#jqGrid_pkg_iledit').click();
			$("#jqGrid_pkg").data('lastselrow',rowId);
		},
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
	    	var selrowdata = $('#jqGrid_pkg').jqGrid ('getRowData', row_id);
			var subgrid_table_id = subgrid_id+"_t";

			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
			$("#"+subgrid_table_id).jqGrid({
				url:"./ordcom/table?action=ordcom_table_pkgdet&id="+selrowdata.id,
				datatype: "json",
				colModel: [
					{ label: 'Package Code', name: 'pkgcode', width: 140, classes: 'wrap',formatter: showdetail_pkg},
					{ label: 'Item Code', name: 'chgcode', width: 140, classes: 'wrap',formatter: showdetail_pkg},
					{ label: 'UOM Code', name: 'uom', width: 60, classes: 'wrap',formatter: showdetail_pkg},
					{ label: 'Dept. Code', name: 'issdept', width: 60, classes: 'wrap',formatter: showdetail_pkg},
					{ label: 'Quantity', name: 'pkgqty', width: 60, align: 'right', classes: 'wrap txnum',
						formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
					},
					{ label: 'Quantity Used', name: 'qtyused', width: 60, align: 'right', classes: 'wrap txnum',
						formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
					},
					{ label: 'Quantity Balance', name: 'qtybal', width: 60, align: 'right', classes: 'wrap txnum',
						formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
					},
					{ label: 'id', name: 'id', width: 10, hidden: true, key:true },
				],
				height: '100%',
				width: '100%',
				rowNum:100,
				sortname: 'id',
				sortorder: "desc",
				loadComplete: function(data){
					fdl_ordcom.set_array().reset();
					calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
					$("#"+subgrid_table_id).jqGrid('resizeGrid');
				},
	       	});
	   }
    });
	jqgrid_label_align_right("#jqGrid_pkg");
	
	$("#jqGrid_pkg").inlineNav('#jqGrid_pkg_pager', {
		add: true,
		edit: false,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_pkg
		},
		editParams: myEditOptions_pkg_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_pkg_pager", {	
		id: "jqGrid_pkg_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_pkg").jqGrid('getGridParam', 'selrow');	
			if (!selRowId) {	
				alert('Please select row');
			} else {

				if (confirm("Are you sure you want to delete this row?") == true) {
				    let urlparam = {	
						action: 'order_entry_pkg',	
						oper: 'del',	
					};
					let urlobj={
						oper:'del',
						_token: $("#csrf_token").val(),
						id: selrowData('#jqGrid_pkg').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_pkg", urlParam_pkg);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_pkg", urlParam_pkg);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_pkg_pager", {	
		id: "jqGrid_pkg_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_pkg", urlParam_pkg);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_pkg_pager", {	
		id: "jqGrid_pkg_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_pkg", urlParam_pkg);
		},	
	});

});
	
var myEditOptions_pkg = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		set_userdeptcode('pkg');
		errorField.length=0;
		myfail_msg_pkg.clear_fail();
		$("#jqGrid_pkg input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").hide();

		$("#jqGrid_pkg input[name='deptcode']").val($("#pkgdept_dflt").val());
		dialog_deptcode_pkg.on();
		dialog_deptcode_pkg.id_optid = rowid;
		dialog_deptcode_pkg.check(errorField,rowid+"_deptcode","jqGrid_pkg",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_pkg.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_pkg.on();
		dialog_chgcode_pkg.on();
		dialog_uomcode_pkg.on();
		// dialog_uom_recv_pkg.on();
		dialog_tax_pkg.on();
		dialog_doctorcode_pkg.on();
		// dialog_dosage_pkg.on();
		// dialog_frequency_pkg.on();
		// dialog_instruction_pkg.on();
		// dialog_drugindicator_pkg.on();
		mycurrency_pkg.array.length = 0;
		mycurrency_np_pkg.array.length = 0;
		Array.prototype.push.apply(mycurrency_pkg.array, ["#jqGrid_pkg input[name='totamount']","#jqGrid_pkg input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_pkg.array, ["#jqGrid_pkg input[name='quantity']"]);
		
		mycurrency_pkg.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_pkg.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_pkg input[name='quantity']").on('keyup',{currency: [mycurrency_pkg,mycurrency_np_pkg]},calculate_line_totgst_and_totamt_pkg);
		$("#jqGrid_pkg input[name='quantity']").on('blur',{currency: [mycurrency_pkg,mycurrency_np_pkg]},calculate_line_totgst_and_totamt_pkg);

		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_pkg input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_pkg input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_pkg_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    	// $("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_pkg.formatOff();
		mycurrency_np_pkg.formatOff();

		if(parseInt($('#jqGrid_pkg input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_pkg.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry_pkg',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_pkg_code").val(),
				// frequency: $("#frequency_pkg_code").val(),
				// addinstruction: $("#instruction_pkg_code").val(),
				// drugindicator: $("#drugindicator_pkg_code").val(),
				taxamount: $("#jqGrid_pkg input[name='taxamount']").val(),
				unitprce: $("#jqGrid_pkg input[name='unitprce']").val(),
				// totamount: $("#jqGrid_pkg input[name='totamount']").val(),
			});
		$("#jqGrid_pkg").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_pkg").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
		myfail_msg_pkg.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_pkg')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_pkg_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		var selrowdata = $('#jqGrid_pkg').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_pkg.clear_fail();
		$("#jqGrid_pkg input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").hide();

		dialog_deptcode_pkg.on();
		dialog_deptcode_pkg.id_optid = rowid;
		dialog_deptcode_pkg.check(errorField,rowid+"_deptcode","jqGrid_pkg",null,null,null );

		dialog_chgcode_pkg.on();
		dialog_chgcode_pkg.id_optid = rowid;
		dialog_chgcode_pkg.check(errorField,rowid+"_chgcode","jqGrid_pkg",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_pkg input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_pkg input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_pkg input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_pkg input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_pkg.on();
		dialog_uomcode_pkg.id_optid = rowid;
		dialog_uomcode_pkg.check(errorField,rowid+"_uom","jqGrid_pkg",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_pkg input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_pkg input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		// dialog_uom_recv_pkg.on();
		// dialog_uom_recv_pkg.id_optid = rowid;
		// dialog_uom_recv_pkg.check(errorField,rowid+"_uom_recv","jqGrid_pkg",null,
        // 	function(self){
		// 		self.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
		// 		self.urlParam.price = 'PRICE2';
		// 		self.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
		// 		self.urlParam.billtype = $('#billtype_def_code').val();
		// 		self.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
		// 		self.urlParam.uom = $("#jqGrid_pkg input[name='uom']").val();
		// 		self.urlParam.filterCol = ['cm.chggroup'];
		// 		self.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
	    //     },function(data,self,id,fail){
	    //     	if(data.rows != undefined && data.rows.length>0){
	    //     		var retdata = data.rows[0];
		// 			$("#jqGrid_pkg input[name='convfactor_uom_recv']").val(retdata['convfactor']);
		// 			$("#jqGrid_pkg input[name='qtyonhand']").val(retdata['qtyonhand']);
	    //     	}
	    //     }
	    // );

		dialog_tax_pkg.on();
		dialog_tax_pkg.id_optid = rowid;
		dialog_tax_pkg.check(errorField,rowid+"_taxcode","jqGrid_pkg",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_pkg #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

	    dialog_doctorcode_pkg.on();
		dialog_doctorcode_pkg.id_optid = rowid;
		// dialog_doctorcode_pkg.check(errorField);

		// dialog_dosage_pkg.on();
		// dialog_frequency_pkg.on();
		// dialog_instruction_pkg.on();
		// dialog_drugindicator_pkg.on();

		mycurrency_pkg.array.length = 0;
		mycurrency_np_pkg.array.length = 0;
		Array.prototype.push.apply(mycurrency_pkg.array, ["#jqGrid_pkg input[name='totamount']","#jqGrid_pkg input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_pkg.array, ["#jqGrid_pkg input[name='quantity']"]);
		
		mycurrency_pkg.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_pkg.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_pkg input[name='quantity']").on('keyup',{currency: [mycurrency_pkg,mycurrency_np_pkg]},calculate_line_totgst_and_totamt_pkg);
		$("#jqGrid_pkg input[name='quantity']").on('blur',{currency: [mycurrency_pkg,mycurrency_np_pkg]},calculate_line_totgst_and_totamt_pkg);

		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_pkg input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_pkg input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_pkg.off();
		// dialog_frequency_pkg.off();
		// dialog_instruction_pkg.off();
		// dialog_drugindicator_pkg.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    	// $("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_pkg.formatOff();
		mycurrency_np_pkg.formatOff();

		if(parseInt($('#jqGrid_pkg input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_pkg.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry_pkg',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_pkg_code").val(),
				// frequency: $("#frequency_pkg_code").val(),
				// addinstruction: $("#instruction_pkg_code").val(),
				// drugindicator: $("#drugindicator_pkg_code").val(),
				taxamount: $("#jqGrid_pkg input[name='taxamount']").val(),
				unitprce: $("#jqGrid_pkg input[name='unitprce']").val(),
				// totamount: $("#jqGrid_pkg input[name='totamount']").val(),
			});
		$("#jqGrid_pkg").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_pkg.off();
		// dialog_frequency_pkg.off();
		// dialog_instruction_pkg.off();
		// dialog_drugindicator_pkg.off();
    	$("#jqGrid_pkg_pagerRefresh,#jqGrid_pkg_pagerDelete").show();
		myfail_msg_pkg.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_pkg')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_pkg",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_pkg',urlParam_pkg,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_pkg(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_pkg.add_fail({
			id:'quantity',
			textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_pkg.del_fail({
			id:'quantity',
			textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_pkg #"+id_optid+"_convfactor_uom").val());
	// let convfactor_uom_recv = parseFloat($("#jqGrid_pkg #"+id_optid+"_convfactor_uom_recv").val());
	// var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_pkg #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_pkg.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_pkg.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	// if (balconv != 0) {
	// 	myfail_msg_pkg.add_fail({
	// 		id:'convfactor',
	// 		textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
	// 		msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
	// 	});
	// } else {
	// 	myfail_msg_pkg.del_fail({
	// 		id:'convfactor',
	// 		textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
	// 		msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
	// 	});
	// }

	let unitprce = parseFloat($("#"+id_optid+"_unitprce").val());
	let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
	let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
	let rate =  parseFloat($("#"+id_optid+"_tax_rate").val());
	if(isNaN(rate)){
		rate = 0;
	}

	var discamt = calc_discamt_main($('#ordcomtt_pkg').val(),$("#jqGrid_pkg #"+id_optid+"_chgcode").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_pkg('#jqgrid_detail_pkg_taxamt',taxamount);
	// write_detail_pkg('#jqgrid_detail_pkg_discamt',discamt);
	
	var id="#jqGrid_pkg #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_pkg = new ordialog(
	'deptcode_pkg',['sysdb.department'],"#jqGrid_pkg input[name='deptcode']",errorField,
	{	colModel:
		[
			{label:'Department Code', name:'deptcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			let data=selrowData('#'+dialog_deptcode_pkg.gridname);
			dialog_chgcode_pkg.urlParam.deptcode = data.deptcode;
			dialog_uomcode_pkg.urlParam.deptcode = data.deptcode;
			// dialog_uom_recv_pkg.urlParam.deptcode = data.deptcode;
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){

			dialog_deptcode_pkg.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode_pkg.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_pkg.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode_pkg.makedialog(false);

var dialog_chgcode_pkg = new ordialog(
	'chgcode_pkg',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_pkg input[name='chgcode']",errorField,
	{	colModel:
		[
			{label: 'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label: 'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
			{label: 'UOM',name:'uom',width:100,classes:'pointer',},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100,classes:'pointer',},
			{label: 'Price',name:'price',width:100,classes:'pointer'},
			{label: 'Tax',name:'taxcode',width:100,classes:'pointer'},
			{label: 'rate',name:'rate',hidden:true},
			{label: 'st_idno',name:'st_idno',hidden:true},
			{label: 'pt_idno',name:'pt_idno',hidden:true},
			{label: 'avgcost',name:'avgcost',hidden:true},
			{label: 'billty_amount',name:'billty_amount',hidden:true},
			{label: 'billty_percent',name:'billty_percent',hidden:true},
			{label: 'convfactor',name:'convfactor',hidden:true},
			
		],
		sortname: 'cm.uom',
		sortorder: 'asc',
		urlParam: {
				url:"./SalesOrderDetail/table",
				action: 'get_itemcode_price',
				url_chk: './ordocom/table',
				action_chk: 'get_itemcode_price_check',
				price : 'PRICE2',
				entrydate : moment().format('YYYY-MM-DD'),
				billtype : $('#billtype_def_code').val(),
				deptcode : $("#raddept_dflt").val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_pkg').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_pkg.del_fail({
				id:'quantity',
				textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_pkg.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_pkg.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_pkg #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_pkg.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_pkg.gridname);

			$("#jqGrid_pkg #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_pkg #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_pkg #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_pkg #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_pkg.urlParam.uom = data['uom'];

			dialog_uomcode_pkg.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_pkg.urlParam.uom = data['uom'];
			$("#jqGrid_pkg #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_pkg.id_optid = id_optid;
			dialog_uomcode_pkg.skipfdl = true;
			dialog_uomcode_pkg.check(errorField,id_optid+"_uom","jqGrid_pkg",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_pkg input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			// dialog_uom_recv_pkg.urlParam.chgcode = data['chgcode'];
			// dialog_uom_recv_pkg.urlParam.uom = data['uom'];
			// $("#jqGrid_pkg #"+id_optid+"_uom_recv").val(data['uom']);
			// dialog_uom_recv_pkg.id_optid = id_optid;
			// dialog_uom_recv_pkg.skipfdl = true;
			// dialog_uom_recv_pkg.check(errorField,id_optid+"_uom_recv","jqGrid_pkg",null,
	        // 	function(self){
			// 		self.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
			// 		self.urlParam.price = 'PRICE2';
			// 		self.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
		    //     },
	        // 	function(data,self,id,fail){
			// 		myfail_msg_pkg.del_fail({id:'nostock_'+self.id_optid});

		    //     	if(data.rows != undefined && data.rows.length>0){
		    //     		var retdata = data.rows[0];
			// 			$("#jqGrid_pkg input[name='convfactor_uom_recv']").val(retdata['convfactor']);
			// 			$("#jqGrid_pkg input[name='qtyonhand']").val(retdata['qtyonhand']);
			// 			if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
			// 				myfail_msg_pkg.add_fail({
			// 					id:'nostock_'+self.id_optid,
			// 					textfld:"#jqGrid_pkg #"+self.id_optid+"_uom_recv",
			// 					msg:'Selected Item ('+$("#jqGrid_pkg input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_pkg input[name='deptcode']").val(),
			// 				});

			// 				$("#jqGrid_pkg #"+self.id_optid+"_convfactor_uom_recv").val('');
			// 				$("#jqGrid_pkg #"+self.id_optid+"_qtyonhand").val('');
			// 				$("#jqGrid_pkg #"+self.id_optid+"_quantity").val('');
			// 				$("#jqGrid_pkg #"+self.id_optid+"_cost_price").val('');

			// 			}
		    //     	}
		    //     }
		    // );

			$("#jqGrid_pkg #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_pkg #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_pkg #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_pkg.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_pkg input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_pkg.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_pkg.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_pkg.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_pkg.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_pkg.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
			dialog_chgcode_pkg.urlParam.price = 'PRICE2';
			dialog_chgcode_pkg.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
			dialog_chgcode_pkg.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_pkg.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
			dialog_chgcode_pkg.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_pkg.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
		},
		close: function(obj){
			$("#jqGrid_pkg input[name='quantity']").focus().select();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_pkg.makedialog(false);

var dialog_uomcode_pkg = new ordialog(
	'uom_pkg',['material.uom AS u'],"#jqGrid_pkg input[name='uom']",errorField,
	{	colModel:
		[
			{label: 'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
			{label: 'UOM Description',name:'description',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Charge Code',name:'chgcode',width:100},
			{label: 'Charge Description',name:'chgdesc',width:300},
			{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100},
			{label: 'Price',name:'price',width:100},
			{label: 'Tax',name:'taxcode',hidden:true},
			{label: 'rate',name:'rate',hidden:true},
			{label: 'st_idno',name:'st_idno',hidden:true},
			{label: 'pt_idno',name:'pt_idno',hidden:true},
			{label: 'avgcost',name:'avgcost',hidden:true},
			{label: 'billty_amount',name:'billty_amount',hidden:true},
			{label: 'billty_percent',name:'billty_percent',hidden:true},
			{label: 'convfactor',name:'convfactor',hidden:true},
		],
		urlParam: {
					url:"./SalesOrderDetail/table",
					url_chk:"./SalesOrderDetail/table",
					action: 'get_itemcode_uom',
					action_chk: 'get_itemcode_uom_check_oe',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $("#raddept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_pkg').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_pkg.del_fail({id:'noprod_'+id_optid});
			myfail_msg_pkg.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_pkg.gridname);
			dialog_chgcode_pkg.urlParam.uom = data['uom'];

			$("#jqGrid_pkg #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_pkg #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_pkg #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_pkg #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_pkg #"+id_optid+"_uom").val(data['uomcode']);
			// if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
			// 	$("#jqGrid_pkg #"+id_optid+"_uom_recv").val(data['uomcode']);
			// }
			$("#jqGrid_pkg #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_pkg #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_pkg #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_pkg #"+id_optid+"_quantity").val('');

			dialog_tax_pkg.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_pkg input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_pkg input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_pkg] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_pkg.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_pkg.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_pkg.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_pkg.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_pkg.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
			dialog_uomcode_pkg.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
			dialog_uomcode_pkg.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
			dialog_uomcode_pkg.urlParam.uom = $("#jqGrid_pkg input[name='uom']").val();
			dialog_uomcode_pkg.urlParam.price = 'PRICE2';
			dialog_uomcode_pkg.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_pkg.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_pkg.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
		},
		close: function(){
			$("#jqGrid_pkg input[name='quantity']").focus().select();
			// $(dialog_uomcode_pkg.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uomcode_pkg.makedialog(false);

var dialog_doctorcode_pkg = new ordialog(
	'doctorcode_pkg',['hisdb.doctor'],"#jqGrid_pkg input[name='doctorcode']",errorField,
	{	colModel:
		[
			{label:'Doctor Code', name:'doctorcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Doctor Name', name:'doctorname', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			// dialog_uom_recv_pkg.urlParam.deptcode = data.deptcode;
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){

			dialog_doctorcode_pkg.urlParam.filterCol=['compcode','recstatus'];
			dialog_doctorcode_pkg.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_pkg.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_doctorcode_pkg.makedialog(false);

// var dialog_uom_recv_pkg = new ordialog(
// 	'uom_recv_pkg',['material.uom AS u'],"#jqGrid_pkg input[name='uom_recv']",errorField,
// 	{	colModel:
// 		[
// 			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
// 			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
// 			{label:'Inventory',name:'invflag',hidden:true},
// 			{label:'Charge Code',name:'chgcode',hidden:true},
// 			{label:'UOM',name:'uom',hidden:true},
// 			{label:'Quantity On Hand',hidden:true},
// 			{label:'Price',name:'price',hidden:true},
// 			{label:'Tax',name:'taxcode',hidden:true},
// 			{label:'rate',name:'rate',hidden:true},
// 			{label:'st_idno',name:'st_idno',hidden:true},
// 			{label:'pt_idno',name:'pt_idno',hidden:true},
// 			{label:'avgcost',name:'avgcost',hidden:true},
// 			{label:'billty_amount',name:'billty_amount',hidden:true},
// 			{label:'billty_percent',name:'billty_percent',hidden:true},
// 			{label:'convfactor',name:'convfactor',hidden:true},
// 			{label:'qtyonhand',name:'qtyonhand',hidden:true},
// 		],
// 		urlParam: {
// 					url:"./ordcom/table",
// 					url_chk:"./ordcom/table",
// 					action: 'get_itemcode_uom_recv',
// 					action_chk: 'get_itemcode_uom_recv_check',
// 					entrydate : moment().format('YYYY-MM-DD'),
// 					deptcode : $("#raddept_dflt").val(),
// 					chgcode : null,
// 					uom:null,
// 					billtype : $('#billtype_def_code').val(),
// 					price : 'PRICE2',
// 					filterCol : ['cm.chggroup'],
// 					filterVal : [$('#ordcomtt_pkg').val()],
// 				},
// 		ondblClickRow:function(event){

// 			if(event.type == 'keydown'){

// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}else{

// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}

// 			let data=selrowData('#'+dialog_uom_recv_pkg.gridname);

// 			myfail_msg_pkg.del_fail({id:'noprod_'+id_optid});
// 			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
// 				myfail_msg_pkg.add_fail({
// 					id:'nostock_'+id_optid,
// 					textfld:"#jqGrid_pkg #"+id_optid+"_uom_recv",
// 					msg:'Selected Item ('+$("#jqGrid_pkg input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_pkg input[name='deptcode']").val(),
// 				});

// 				$("#jqGrid_pkg #"+id_optid+"_convfactor_uom_recv").val('');
// 				$("#jqGrid_pkg #"+id_optid+"_qtyonhand").val('');
// 				$("#jqGrid_pkg #"+id_optid+"_quantity").val('');
// 				$("#jqGrid_pkg #"+id_optid+"_cost_price").val('');
// 			}
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 				$("#jqGrid_pkg input[name='qty']").focus();
// 				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
// 			}
// 		}
		
// 	},{
// 		title:"Select UOM Code For Item",
// 		open:function(obj_){
// 			dialog_uom_recv_pkg.urlParam.url = "./ordcom/table";
// 			dialog_uom_recv_pkg.urlParam.action = 'get_itemcode_uom_recv';
// 			dialog_uom_recv_pkg.urlParam.url_chk = "./ordcom/table";
// 			dialog_uom_recv_pkg.urlParam.action_chk = "get_itemcode_uom_recv_check";
// 			dialog_uom_recv_pkg.urlParam.entrydate = $("#jqGrid_pkg input[name='trxdate']").val();
// 			dialog_uom_recv_pkg.urlParam.chgcode = $("#jqGrid_pkg input[name='chgcode']").val();
// 			dialog_uom_recv_pkg.urlParam.deptcode = $("#jqGrid_pkg input[name='deptcode']").val();
// 			dialog_uom_recv_pkg.urlParam.price = 'PRICE2';
// 			dialog_uom_recv_pkg.urlParam.uom = $("#jqGrid_pkg input[name='uom_recv']").val();
// 			dialog_uom_recv_pkg.urlParam.billtype = $('#billtype_def_code').val();
// 			dialog_uom_recv_pkg.urlParam.filterCol = ['cm.chggroup'];
// 			dialog_uom_recv_pkg.urlParam.filterVal = [$('#ordcomtt_pkg').val()];
// 		},
// 		close: function(){
// 			$("#jqGrid_pkg input[name='quantity']").focus().select();
// 			// $(dialog_uomcode_pkg.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		},
// 		justb4refresh: function(obj_){
// 			obj_.urlParam.searchCol2=[];
// 			obj_.urlParam.searchVal2=[];
// 		},
// 		justaftrefresh: function(obj_){
// 			$("#Dtext_"+obj_.unique).val('');
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_uom_recv_pkg.makedialog(false);

var dialog_tax_pkg = new ordialog(
	'taxcode_pkg',['hisdb.taxmast'],"#jqGrid_pkg input[name='taxcode']",errorField,
	{	colModel:
		[
			{label:'Tax Code', name:'taxcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
			{label:'Rate', name:'rate', width:100, classes:'pointer'},
		],
		urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			let data=selrowData('#'+dialog_tax_pkg.gridname);
			$("#jqGrid_pkg #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_pkg input#"+id_optid+"_taxcode").val(data.taxcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		check_take_all_field : true,
		open:function(obj_){

			dialog_tax_pkg.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_pkg.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_pkg.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax_pkg.makedialog(false);

function trxdateCustomEdit_pkg(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_pkg" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_pkg(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_pkg(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_pkg(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_pkg(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_pkg(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_pkg(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function doctorcodeCustomEdit_pkg(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="doctorcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_pkg(val,opt){
	var myreturn = `<label class='oe_pkg_label'>Dose</label><div class="oe_pkg_div input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_pkg_label'>Frequency</label><div class="oe_pkg_div input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_pkg_label'>Instruction</label><div class="oe_pkg_div input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_pkg_label'>Indicator</label><div class="oe_pkg_div input-group"><input autocomplete="off" jqgrid="jqGrid_pkg" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_pkg (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_pkg(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		case 'pkgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
		case 'doctorcode':field=['doctorcode','doctorname'];table="hisdb.doctor";case_='deptcode';break;
		case 'issdept':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	if(cellvalue != null && cellvalue.trim() != ''){
		fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
	}
	
	if(cellvalue == null)cellvalue = " ";
	calc_jq_height_onchange("jqGrid_pkg",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_pkg(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_pkg input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_pkg input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_pkg input[name='uom']"); break;
		case 'Doctor Code': temp = $("#jqGrid_pkg input[name='doctorcode']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_pkg input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_pkg input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_pkg input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_pkg input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}