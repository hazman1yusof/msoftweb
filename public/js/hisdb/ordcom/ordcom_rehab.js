
var urlParam_rehab={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_rehab').val(),
	mrn:'',
	episno:''
};
var myfail_msg_rehab = new fail_msg_func('div#fail_msg_rehab');
var mycurrency_rehab =new currencymode([]);
var mycurrency_np_rehab =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_rehab").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rehab },
				formatter: showdetail_rehab,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rehab },
				formatter: showdetail_rehab,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rehab },
				formatter: showdetail_rehab,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rehab },
				formatter: showdetail_rehab,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_rehab },
				formatter: showdetail_rehab,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_rehab,
					custom_value: galGridCustomValue_rehab
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
				formatter:abscurrency,unformat:abscurrency_unformat,
				editrules:{required: true},editoptions:{readonly: "readonly"}},
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Net<br>Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter_rehab,
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{label: 'Dosage', name: 'remark', hidden: true },
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
		pager: "#jqGrid_rehab_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_rehab",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_rehab.clear_fail();
			if($("#jqGrid_rehab").data('lastselrow')==undefined||$("#jqGrid_rehab").data('lastselrow')==null){
				$("#jqGrid_rehab").setSelection($("#jqGrid_rehab").getDataIDs()[0]);
			}else{
				$("#jqGrid_rehab").setSelection($("#jqGrid_rehab").data('lastselrow'));
			}
			$("#jqGrid_rehab").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_rehab.clear_fail();

			let justsave = $("#jqGrid_rehab").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_rehab_iladd').click();
				}, 500 );
			}
			$("#jqGrid_rehab").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_rehab_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			$('#jqGrid_rehab_iledit,#jqGrid_rehab_pagerDelete').hide();
			if($('#jqGrid_rehab_iladd').hasClass('ui-disabled')){
				$('#jqGrid_rehab_iledit,#jqGrid_rehab_pagerDelete').hide();
			}else if(selrowData('#jqGrid_rehab').trxtype == 'OE' || selrowData('#jqGrid_rehab').trxtype == 'PK'){
				$('#jqGrid_rehab_iledit,#jqGrid_rehab_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_rehab').trxtype != 'PD'){
				$('#jqGrid_rehab_iledit').click();
			}
		}
    });
	jqgrid_label_align_right("#jqGrid_rehab");
	
	$("#jqGrid_rehab").inlineNav('#jqGrid_rehab_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_rehab
		},
		editParams: myEditOptions_rehab_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_rehab_pager", {	
		id: "jqGrid_rehab_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_rehab").jqGrid('getGridParam', 'selrow');	
			if (!selRowId) {	
				alert('Please select row');
			} else {

				if (confirm("Are you sure you want to delete this row?") == true) {
				    let urlparam = {	
						action: 'order_entry',	
						oper: 'del',	
					};
					let urlobj={
						oper:'del',
						_token: $("#csrf_token").val(),
						id: selrowData('#jqGrid_rehab').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_rehab", urlParam_rehab);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_rehab", urlParam_rehab);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_rehab_pager", {	
		id: "jqGrid_rehab_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_rehab", urlParam_rehab);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_rehab_pager", {	
		id: "jqGrid_rehab_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_rehab", urlParam_rehab);
		},	
	});

});
	
var myEditOptions_rehab = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_rehab").data('lastselrow',rowid);
		errorField.length=0;
		myfail_msg_rehab.clear_fail();
		$("#jqGrid_rehab input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").hide();

		$("#jqGrid_rehab input[name='deptcode']").val($("#rehabdept_dflt").val());
		dialog_deptcode_rehab.on();
		dialog_deptcode_rehab.id_optid = rowid;
		dialog_deptcode_rehab.check(errorField,rowid+"_deptcode","jqGrid_rehab",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_rehab.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_rehab.on();
		dialog_chgcode_rehab.on();
		dialog_uomcode_rehab.on();
		dialog_uom_recv_rehab.on();
		dialog_tax_rehab.on();
		// dialog_dosage_rehab.on();
		// dialog_frequency_rehab.on();
		// dialog_instruction_rehab.on();
		// dialog_drugindicator_rehab.on();
		mycurrency_rehab.array.length = 0;
		mycurrency_np_rehab.array.length = 0;
		Array.prototype.push.apply(mycurrency_rehab.array, ["#jqGrid_rehab input[name='totamount']","#jqGrid_rehab input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_rehab.array, ["#jqGrid_rehab input[name='quantity']"]);
		
		mycurrency_rehab.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_rehab.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_rehab input[name='quantity']").on('keyup',{currency: [mycurrency_rehab,mycurrency_np_rehab]},calculate_line_totgst_and_totamt_rehab);
		$("#jqGrid_rehab input[name='quantity']").on('blur',{currency: [mycurrency_rehab,mycurrency_np_rehab]},calculate_line_totgst_and_totamt_rehab);

		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_rehab input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_rehab input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_rehab_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    	// $("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_rehab.formatOff();
		mycurrency_np_rehab.formatOff();

		if(parseInt($('#jqGrid_rehab input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_rehab.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_rehab_code").val(),
				// frequency: $("#frequency_rehab_code").val(),
				// addinstruction: $("#instruction_rehab_code").val(),
				// drugindicator: $("#drugindicator_rehab_code").val(),
				taxamount: $("#jqGrid_rehab input[name='taxamount']").val(),
				unitprce: $("#jqGrid_rehab input[name='unitprce']").val(),
				// totamount: $("#jqGrid_rehab input[name='totamount']").val(),
			});
		$("#jqGrid_rehab").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_rehab").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
		myfail_msg_rehab.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_rehab')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_rehab_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_rehab").data('lastselrow',rowid);
		var selrowdata = $('#jqGrid_rehab').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_rehab.clear_fail();
		$("#jqGrid_rehab input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").hide();

		dialog_deptcode_rehab.on();
		dialog_deptcode_rehab.id_optid = rowid;
		dialog_deptcode_rehab.check(errorField,rowid+"_deptcode","jqGrid_rehab",null,null,null );

		dialog_chgcode_rehab.on();
		dialog_chgcode_rehab.id_optid = rowid;
		dialog_chgcode_rehab.check(errorField,rowid+"_chgcode","jqGrid_rehab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rehab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rehab input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_rehab input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_rehab input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_rehab.on();
		dialog_uomcode_rehab.id_optid = rowid;
		dialog_uomcode_rehab.check(errorField,rowid+"_uom","jqGrid_rehab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rehab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rehab input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_rehab.on();
		dialog_uom_recv_rehab.id_optid = rowid;
		dialog_uom_recv_rehab.check(errorField,rowid+"_uom_recv","jqGrid_rehab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rehab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rehab input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_rehab input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_rehab.on();
		dialog_tax_rehab.id_optid = rowid;
		dialog_tax_rehab.check(errorField,rowid+"_taxcode","jqGrid_rehab",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rehab #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_rehab.on();
		// dialog_frequency_rehab.on();
		// dialog_instruction_rehab.on();
		// dialog_drugindicator_rehab.on();

		mycurrency_rehab.array.length = 0;
		mycurrency_np_rehab.array.length = 0;
		Array.prototype.push.apply(mycurrency_rehab.array, ["#jqGrid_rehab input[name='totamount']","#jqGrid_rehab input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_rehab.array, ["#jqGrid_rehab input[name='quantity']"]);
		
		mycurrency_rehab.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_rehab.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_rehab input[name='quantity']").on('keyup',{currency: [mycurrency_rehab,mycurrency_np_rehab]},calculate_line_totgst_and_totamt_rehab);
		$("#jqGrid_rehab input[name='quantity']").on('blur',{currency: [mycurrency_rehab,mycurrency_np_rehab]},calculate_line_totgst_and_totamt_rehab);

		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_rehab input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_rehab input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_rehab.off();
		// dialog_frequency_rehab.off();
		// dialog_instruction_rehab.off();
		// dialog_drugindicator_rehab.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    	// $("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_rehab.formatOff();
		mycurrency_np_rehab.formatOff();

		if(parseInt($('#jqGrid_rehab input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_rehab.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_rehab_code").val(),
				// frequency: $("#frequency_rehab_code").val(),
				// addinstruction: $("#instruction_rehab_code").val(),
				// drugindicator: $("#drugindicator_rehab_code").val(),
				taxamount: $("#jqGrid_rehab input[name='taxamount']").val(),
				unitprce: $("#jqGrid_rehab input[name='unitprce']").val(),
				// totamount: $("#jqGrid_rehab input[name='totamount']").val(),
			});
		$("#jqGrid_rehab").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_rehab.off();
		// dialog_frequency_rehab.off();
		// dialog_instruction_rehab.off();
		// dialog_drugindicator_rehab.off();
    	$("#jqGrid_rehab_pagerRefresh,#jqGrid_rehab_pagerDelete").show();
		myfail_msg_rehab.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_rehab')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rehab',urlParam_rehab,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_rehab(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_rehab.add_fail({
			id:'quantity',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_rehab.del_fail({
			id:'quantity',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_rehab #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_rehab #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_rehab #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_rehab.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_rehab.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_rehab.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_rehab.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	}

	let unitprce = parseFloat($("#"+id_optid+"_unitprce").val());
	let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
	let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
	let rate =  parseFloat($("#"+id_optid+"_tax_rate").val());
	if(isNaN(rate)){
		rate = 0;
	}

	var discamt = calc_discamt_main($('#ordcomtt_rehab').val(),$("#jqGrid_rad #"+id_optid+"_rehab").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(numeral(discamt).format('0,0.00'));
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_rehab('#jqgrid_detail_rehab_taxamt',taxamount);
	// write_detail_rehab('#jqgrid_detail_rehab_discamt',discamt);
	
	var id="#jqGrid_rehab #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_rehab = new ordialog(
	'deptcode_rehab',['sysdb.department'],"#jqGrid_rehab input[name='deptcode']",errorField,
	{	colModel:
		[
			{label:'Department Code', name:'deptcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus','chgdept'],
					filterVal:['session.compcode','ACTIVE','1']
				},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			let data=selrowData('#'+dialog_deptcode_rehab.gridname);
			dialog_chgcode_rehab.urlParam.deptcode = data.deptcode;
			dialog_uomcode_rehab.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_rehab.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_rehab.urlParam.filterCol=['compcode','recstatus','chgdept'];
			dialog_deptcode_rehab.urlParam.filterVal=['session.compcode','ACTIVE','1'];
		},
		close: function(){
			// $(dialog_deptcode_rehab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_deptcode_rehab.makedialog(false);

var dialog_chgcode_rehab = new ordialog(
	'chgcode_rehab',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_rehab input[name='chgcode']",errorField,
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
				deptcode : $("#rehabdept_dflt").val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_rehab').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_rehab.del_fail({
				id:'quantity',
				textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_rehab.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_rehab.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_rehab #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_rehab.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_rehab.gridname);

			$("#jqGrid_rehab #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_rehab #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_rehab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rehab #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_rehab.urlParam.uom = data['uom'];

			dialog_uomcode_rehab.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_rehab.urlParam.uom = data['uom'];
			$("#jqGrid_rehab #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_rehab.id_optid = id_optid;
			dialog_uomcode_rehab.skipfdl = true;
			dialog_uomcode_rehab.check(errorField,id_optid+"_uom","jqGrid_rehab",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_rehab input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_rehab.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_rehab.urlParam.uom = data['uom'];
			$("#jqGrid_rehab #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_rehab.id_optid = id_optid;
			dialog_uom_recv_rehab.skipfdl = true;
			dialog_uom_recv_rehab.check(errorField,id_optid+"_uom_recv","jqGrid_rehab",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_rehab.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_rehab input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_rehab input[name='qtyonhand']").val(retdata['qtyonhand']);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_rehab.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_rehab #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_rehab input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_rehab input[name='deptcode']").val(),
							});

							$("#jqGrid_rehab #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_rehab #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_rehab #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_rehab #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_rehab #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_rehab #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_rehab #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_rehab.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rehab input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_rehab.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_rehab.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_rehab.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_rehab.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_rehab.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
			dialog_chgcode_rehab.urlParam.price = 'PRICE2';
			dialog_chgcode_rehab.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
			dialog_chgcode_rehab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_rehab.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
			dialog_chgcode_rehab.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_rehab.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
		},
		close: function(obj){
			$("#jqGrid_rehab input[name='quantity']").focus().select();
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
dialog_chgcode_rehab.makedialog(false);

var dialog_uomcode_rehab = new ordialog(
	'uom_rehab',['material.uom AS u'],"#jqGrid_rehab input[name='uom']",errorField,
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
					deptcode : $("#rehabdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_rehab').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_rehab.del_fail({id:'noprod_'+id_optid});
			myfail_msg_rehab.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_rehab.gridname);
			dialog_chgcode_rehab.urlParam.uom = data['uom'];

			$("#jqGrid_rehab #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_rehab #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_rehab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rehab #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_rehab #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_rehab #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_rehab #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_rehab #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_rehab #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_rehab #"+id_optid+"_quantity").val('');

			dialog_tax_rehab.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rehab input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_rehab input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_rehab] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_rehab.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_rehab.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_rehab.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_rehab.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_rehab.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
			dialog_uomcode_rehab.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
			dialog_uomcode_rehab.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
			dialog_uomcode_rehab.urlParam.uom = $("#jqGrid_rehab input[name='uom']").val();
			dialog_uomcode_rehab.urlParam.price = 'PRICE2';
			dialog_uomcode_rehab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_rehab.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_rehab.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
		},
		close: function(){
			$("#jqGrid_rehab input[name='quantity']").focus().select();
			// $(dialog_uomcode_rehab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uomcode_rehab.makedialog(false);

var dialog_uom_recv_rehab = new ordialog(
	'uom_recv_rehab',['material.uom AS u'],"#jqGrid_rehab input[name='uom_recv']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label:'Inventory',name:'invflag',hidden:true},
			{label:'Charge Code',name:'chgcode',hidden:true},
			{label:'UOM',name:'uom',hidden:true},
			{label:'Quantity On Hand',hidden:true},
			{label:'Price',name:'price',hidden:true},
			{label:'Tax',name:'taxcode',hidden:true},
			{label:'rate',name:'rate',hidden:true},
			{label:'st_idno',name:'st_idno',hidden:true},
			{label:'pt_idno',name:'pt_idno',hidden:true},
			{label:'avgcost',name:'avgcost',hidden:true},
			{label:'billty_amount',name:'billty_amount',hidden:true},
			{label:'billty_percent',name:'billty_percent',hidden:true},
			{label:'convfactor',name:'convfactor',hidden:true},
			{label:'qtyonhand',name:'qtyonhand',hidden:true},
		],
		urlParam: {
					url:"./ordcom/table",
					url_chk:"./ordcom/table",
					action: 'get_itemcode_uom_recv',
					action_chk: 'get_itemcode_uom_recv_check',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $("#rehabdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_rehab').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_rehab.gridname);

			myfail_msg_rehab.del_fail({id:'noprod_'+id_optid});
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_rehab.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_rehab #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_rehab input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_rehab input[name='deptcode']").val(),
				});

				$("#jqGrid_rehab #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_rehab #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_rehab #"+id_optid+"_quantity").val('');
				$("#jqGrid_rehab #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rehab input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_rehab.urlParam.url = "./ordcom/table";
			dialog_uom_recv_rehab.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_rehab.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_rehab.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_rehab.urlParam.entrydate = $("#jqGrid_rehab input[name='trxdate']").val();
			dialog_uom_recv_rehab.urlParam.chgcode = $("#jqGrid_rehab input[name='chgcode']").val();
			dialog_uom_recv_rehab.urlParam.deptcode = $("#jqGrid_rehab input[name='deptcode']").val();
			dialog_uom_recv_rehab.urlParam.price = 'PRICE2';
			dialog_uom_recv_rehab.urlParam.uom = $("#jqGrid_rehab input[name='uom_recv']").val();
			dialog_uom_recv_rehab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_rehab.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_rehab.urlParam.filterVal = [$('#ordcomtt_rehab').val()];
		},
		close: function(){
			$("#jqGrid_rehab input[name='quantity']").focus().select();
			// $(dialog_uomcode_rehab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uom_recv_rehab.makedialog(false);

var dialog_tax_rehab = new ordialog(
	'taxcode_rehab',['hisdb.taxmast'],"#jqGrid_rehab input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_rehab.gridname);
			$("#jqGrid_rehab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rehab input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_rehab.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_rehab.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_rehab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_tax_rehab.makedialog(false);

function trxdateCustomEdit_rehab(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_rehab" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_rehab(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='billtypeamt' id='`+id_optid+`_billtypeamt'>`;
	myreturn += `<input type='hidden' name='taxamount' id='`+id_optid+`_taxamount'>`;
	// myreturn += `<input type='hidden' name='unitprce' id='`+id_optid+`_unitprce'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_rehab(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_rehab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_rehab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_rehab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_rehab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_rehab(val,opt){
	var myreturn = `<label class='oe_rehab_label'>Dose</label><div class="oe_rehab_div input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rehab_label'>Frequency</label><div class="oe_rehab_div input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rehab_label'>Instruction</label><div class="oe_rehab_div input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rehab_label'>Indicator</label><div class="oe_rehab_div input-group"><input autocomplete="off" jqgrid="jqGrid_rehab" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_rehab (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_rehab(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	if(cellvalue != null && cellvalue.trim() != ''){
		fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
	}
	
	if(cellvalue == null)cellvalue = " ";
	calc_jq_height_onchange("jqGrid_rehab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_rehab(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_rehab input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_rehab input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_rehab input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_rehab input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_rehab input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_rehab input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_rehab input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}