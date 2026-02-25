
var urlParam_diet={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_diet').val(),
	mrn:'',
	episno:''
};
var myfail_msg_diet = new fail_msg_func('div#fail_msg_diet');
var mycurrency_diet =new currencymode([]);
var mycurrency_np_diet =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_diet").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_diet,
					custom_value: galGridCustomValue_diet
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_diet },
				formatter: showdetail_diet,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_diet,
					custom_value: galGridCustomValue_diet
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_diet },
				formatter: showdetail_diet,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_diet,
					custom_value: galGridCustomValue_diet
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_diet },
				formatter: showdetail_diet,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_diet,
					custom_value: galGridCustomValue_diet
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_diet },
				formatter: showdetail_diet,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_diet,
					custom_value: galGridCustomValue_diet
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_diet },
				formatter: showdetail_diet,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_diet,
					custom_value: galGridCustomValue_diet
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
				formatter:totamountFormatter_diet,
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
		pager: "#jqGrid_diet_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_diet",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_diet.clear_fail();
			if($("#jqGrid_diet").data('lastselrow')==undefined||$("#jqGrid_diet").data('lastselrow')==null){
				$("#jqGrid_diet").setSelection($("#jqGrid_diet").getDataIDs()[0]);
			}else{
				$("#jqGrid_diet").setSelection($("#jqGrid_diet").data('lastselrow'));
			}
			$("#jqGrid_diet").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_diet.clear_fail();

			let justsave = $("#jqGrid_diet").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_diet_iladd').click();
				}, 500 );
			}
			$("#jqGrid_diet").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_diet_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			$('#jqGrid_diet_iledit,#jqGrid_diet_pagerDelete').hide();
			if($('#jqGrid_diet_iladd').hasClass('ui-disabled')){
				$('#jqGrid_diet_iledit,#jqGrid_diet_pagerDelete').hide();
			}else if(selrowData('#jqGrid_diet').trxtype == 'OE' || selrowData('#jqGrid_diet').trxtype == 'PK'){
				$('#jqGrid_diet_iledit,#jqGrid_diet_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_diet').trxtype != 'PD'){
				$('#jqGrid_diet_iledit').click();
			}
		}
    });
	jqgrid_label_align_right("#jqGrid_diet");
	
	$("#jqGrid_diet").inlineNav('#jqGrid_diet_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_diet
		},
		editParams: myEditOptions_diet_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_diet_pager", {	
		id: "jqGrid_diet_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_diet").jqGrid('getGridParam', 'selrow');	
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
						id: selrowData('#jqGrid_diet').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_diet", urlParam_diet);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_diet", urlParam_diet);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_diet_pager", {	
		id: "jqGrid_diet_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_diet", urlParam_diet);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_diet_pager", {	
		id: "jqGrid_diet_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_diet", urlParam_diet);
		},	
	});

});
	
var myEditOptions_diet = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_diet").data('lastselrow',rowid);
		errorField.length=0;
		myfail_msg_diet.clear_fail();
		$("#jqGrid_diet input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").hide();

		$("#jqGrid_diet input[name='deptcode']").val($("#dietdept_dflt").val());
		dialog_deptcode_diet.on();
		dialog_deptcode_diet.id_optid = rowid;
		dialog_deptcode_diet.check(errorField,rowid+"_deptcode","jqGrid_diet",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_diet.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_diet.on();
		dialog_chgcode_diet.on();
		dialog_uomcode_diet.on();
		dialog_uom_recv_diet.on();
		dialog_tax_diet.on();
		// dialog_dosage_diet.on();
		// dialog_frequency_diet.on();
		// dialog_instruction_diet.on();
		// dialog_drugindicator_diet.on();
		mycurrency_diet.array.length = 0;
		mycurrency_np_diet.array.length = 0;
		Array.prototype.push.apply(mycurrency_diet.array, ["#jqGrid_diet input[name='totamount']","#jqGrid_diet input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_diet.array, ["#jqGrid_diet input[name='quantity']"]);
		
		mycurrency_diet.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_diet.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_diet input[name='quantity']").on('keyup',{currency: [mycurrency_diet,mycurrency_np_diet]},calculate_line_totgst_and_totamt_diet);
		$("#jqGrid_diet input[name='quantity']").on('blur',{currency: [mycurrency_diet,mycurrency_np_diet]},calculate_line_totgst_and_totamt_diet);

		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_diet input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_diet input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_diet_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    	// $("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_diet.formatOff();
		mycurrency_np_diet.formatOff();

		if(parseInt($('#jqGrid_diet input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_diet.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_diet_code").val(),
				// frequency: $("#frequency_diet_code").val(),
				// addinstruction: $("#instruction_diet_code").val(),
				// drugindicator: $("#drugindicator_diet_code").val(),
				taxamount: $("#jqGrid_diet input[name='taxamount']").val(),
				unitprce: $("#jqGrid_diet input[name='unitprce']").val(),
				// totamount: $("#jqGrid_diet input[name='totamount']").val(),
			});
		$("#jqGrid_diet").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_diet").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
		myfail_msg_diet.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_diet')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_diet_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_diet").data('lastselrow',rowid);
		var selrowdata = $('#jqGrid_diet').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_diet.clear_fail();
		$("#jqGrid_diet input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").hide();

		dialog_deptcode_diet.on();
		dialog_deptcode_diet.id_optid = rowid;
		dialog_deptcode_diet.check(errorField,rowid+"_deptcode","jqGrid_diet",null,null,null );

		dialog_chgcode_diet.on();
		dialog_chgcode_diet.id_optid = rowid;
		dialog_chgcode_diet.check(errorField,rowid+"_chgcode","jqGrid_diet",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_diet input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_diet').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_diet input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_diet input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_diet input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_diet.on();
		dialog_uomcode_diet.id_optid = rowid;
		dialog_uomcode_diet.check(errorField,rowid+"_uom","jqGrid_diet",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_diet input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_diet').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_diet input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_diet.on();
		dialog_uom_recv_diet.id_optid = rowid;
		dialog_uom_recv_diet.check(errorField,rowid+"_uom_recv","jqGrid_diet",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_diet input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_diet').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_diet input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_diet input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_diet.on();
		dialog_tax_diet.id_optid = rowid;
		dialog_tax_diet.check(errorField,rowid+"_taxcode","jqGrid_diet",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_diet #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_diet.on();
		// dialog_frequency_diet.on();
		// dialog_instruction_diet.on();
		// dialog_drugindicator_diet.on();

		mycurrency_diet.array.length = 0;
		mycurrency_np_diet.array.length = 0;
		Array.prototype.push.apply(mycurrency_diet.array, ["#jqGrid_diet input[name='totamount']","#jqGrid_diet input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_diet.array, ["#jqGrid_diet input[name='quantity']"]);
		
		mycurrency_diet.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_diet.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_diet input[name='quantity']").on('keyup',{currency: [mycurrency_diet,mycurrency_np_diet]},calculate_line_totgst_and_totamt_diet);
		$("#jqGrid_diet input[name='quantity']").on('blur',{currency: [mycurrency_diet,mycurrency_np_diet]},calculate_line_totgst_and_totamt_diet);

		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_diet input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_diet input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_diet.off();
		// dialog_frequency_diet.off();
		// dialog_instruction_diet.off();
		// dialog_drugindicator_diet.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    	// $("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_diet.formatOff();
		mycurrency_np_diet.formatOff();

		if(parseInt($('#jqGrid_diet input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_diet.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_diet_code").val(),
				// frequency: $("#frequency_diet_code").val(),
				// addinstruction: $("#instruction_diet_code").val(),
				// drugindicator: $("#drugindicator_diet_code").val(),
				taxamount: $("#jqGrid_diet input[name='taxamount']").val(),
				unitprce: $("#jqGrid_diet input[name='unitprce']").val(),
				// totamount: $("#jqGrid_diet input[name='totamount']").val(),
			});
		$("#jqGrid_diet").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_diet.off();
		// dialog_frequency_diet.off();
		// dialog_instruction_diet.off();
		// dialog_drugindicator_diet.off();
    	$("#jqGrid_diet_pagerRefresh,#jqGrid_diet_pagerDelete").show();
		myfail_msg_diet.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_diet')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_diet',urlParam_diet,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_diet(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_diet.add_fail({
			id:'quantity',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_diet.del_fail({
			id:'quantity',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_diet #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_diet #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_diet #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_diet.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_diet.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_diet.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_diet.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_diet #"+id_optid+"_quantity",
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

	var discamt = calc_discamt_main($('#ordcomtt_diet').val(),$("#jqGrid_diet #"+id_optid+"_chgcode").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_diet('#jqgrid_detail_diet_taxamt',taxamount);
	// write_detail_diet('#jqgrid_detail_diet_discamt',discamt);
	
	var id="#jqGrid_diet #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_diet = new ordialog(
	'deptcode_diet',['sysdb.department'],"#jqGrid_diet input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_diet.gridname);
			dialog_chgcode_diet.urlParam.deptcode = data.deptcode;
			dialog_uomcode_diet.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_diet.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_diet.urlParam.filterCol=['compcode','recstatus','chgdept'];
			dialog_deptcode_diet.urlParam.filterVal=['session.compcode','ACTIVE','1'];
		},
		close: function(){
			// $(dialog_deptcode_diet.textfield)			//lepas close dialog focus on next textfield 
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
dialog_deptcode_diet.makedialog(false);

var dialog_chgcode_diet = new ordialog(
	'chgcode_diet',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_diet input[name='chgcode']",errorField,
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
				deptcode : $("#dietdept_dflt").val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_diet').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_diet.del_fail({
				id:'quantity',
				textfld:"#jqGrid_diet #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_diet.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_diet #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_diet.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_diet #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_diet.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_diet.gridname);

			$("#jqGrid_diet #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_diet #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_diet #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_diet #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_diet.urlParam.uom = data['uom'];

			dialog_uomcode_diet.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_diet.urlParam.uom = data['uom'];
			$("#jqGrid_diet #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_diet.id_optid = id_optid;
			dialog_uomcode_diet.skipfdl = true;
			dialog_uomcode_diet.check(errorField,id_optid+"_uom","jqGrid_diet",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_diet input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_diet.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_diet.urlParam.uom = data['uom'];
			$("#jqGrid_diet #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_diet.id_optid = id_optid;
			dialog_uom_recv_diet.skipfdl = true;
			dialog_uom_recv_diet.check(errorField,id_optid+"_uom_recv","jqGrid_diet",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_diet.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_diet input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_diet input[name='qtyonhand']").val(retdata['qtyonhand']);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_diet.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_diet #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_diet input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_diet input[name='deptcode']").val(),
							});

							$("#jqGrid_diet #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_diet #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_diet #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_diet #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_diet #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_diet #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_diet #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_diet #"+id_optid+"_quantity").val(1).trigger('blur');

			dialog_tax_diet.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_diet input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_diet.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_diet.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_diet.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_diet.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_diet.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
			dialog_chgcode_diet.urlParam.price = 'PRICE2';
			dialog_chgcode_diet.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
			dialog_chgcode_diet.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_diet.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
			dialog_chgcode_diet.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_diet.urlParam.filterVal = [$('#ordcomtt_diet').val()];
		},
		close: function(obj){
			$("#jqGrid_diet input[name='quantity']").focus().select();
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
dialog_chgcode_diet.makedialog(false);

var dialog_uomcode_diet = new ordialog(
	'uom_diet',['material.uom AS u'],"#jqGrid_diet input[name='uom']",errorField,
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
					deptcode : $("#dietdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_diet').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_diet.del_fail({id:'noprod_'+id_optid});
			myfail_msg_diet.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_diet.gridname);
			dialog_chgcode_diet.urlParam.uom = data['uom'];

			$("#jqGrid_diet #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_diet #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_diet #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_diet #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_diet #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_diet #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_diet #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_diet #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_diet #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_diet #"+id_optid+"_quantity").val('');

			dialog_tax_diet.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_diet input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_diet input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_diet] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_diet.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_diet.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_diet.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_diet.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_diet.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
			dialog_uomcode_diet.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
			dialog_uomcode_diet.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
			dialog_uomcode_diet.urlParam.uom = $("#jqGrid_diet input[name='uom']").val();
			dialog_uomcode_diet.urlParam.price = 'PRICE2';
			dialog_uomcode_diet.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_diet.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_diet.urlParam.filterVal = [$('#ordcomtt_diet').val()];
		},
		close: function(){
			$("#jqGrid_diet input[name='quantity']").focus().select();
			// $(dialog_uomcode_diet.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uomcode_diet.makedialog(false);

var dialog_uom_recv_diet = new ordialog(
	'uom_recv_diet',['material.uom AS u'],"#jqGrid_diet input[name='uom_recv']",errorField,
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
					deptcode : $("#dietdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_diet').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_diet.gridname);

			myfail_msg_diet.del_fail({id:'noprod_'+id_optid});
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_diet.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_diet #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_diet input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_diet input[name='deptcode']").val(),
				});

				$("#jqGrid_diet #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_diet #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_diet #"+id_optid+"_quantity").val('');
				$("#jqGrid_diet #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_diet input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_diet.urlParam.url = "./ordcom/table";
			dialog_uom_recv_diet.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_diet.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_diet.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_diet.urlParam.entrydate = $("#jqGrid_diet input[name='trxdate']").val();
			dialog_uom_recv_diet.urlParam.chgcode = $("#jqGrid_diet input[name='chgcode']").val();
			dialog_uom_recv_diet.urlParam.deptcode = $("#jqGrid_diet input[name='deptcode']").val();
			dialog_uom_recv_diet.urlParam.price = 'PRICE2';
			dialog_uom_recv_diet.urlParam.uom = $("#jqGrid_diet input[name='uom_recv']").val();
			dialog_uom_recv_diet.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_diet.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_diet.urlParam.filterVal = [$('#ordcomtt_diet').val()];
		},
		close: function(){
			$("#jqGrid_diet input[name='quantity']").focus().select();
			// $(dialog_uomcode_diet.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uom_recv_diet.makedialog(false);

var dialog_tax_diet = new ordialog(
	'taxcode_diet',['hisdb.taxmast'],"#jqGrid_diet input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_diet.gridname);
			$("#jqGrid_diet #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_diet input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_diet.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_diet.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_diet.textfield)			//lepas close dialog focus on next textfield 
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
dialog_tax_diet.makedialog(false);

function trxdateCustomEdit_diet(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_diet" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_diet(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	// myreturn += `<input type='hidden' name='unitprce' id='`+id_optid+`_unitprce'>`;
	myreturn += `<div><input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_diet(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_diet(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_diet(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_diet(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_diet(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_diet(val,opt){
	var myreturn = `<label class='oe_diet_label'>Dose</label><div class="oe_diet_div input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_diet_label'>Frequency</label><div class="oe_diet_div input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_diet_label'>Instruction</label><div class="oe_diet_div input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_diet_label'>Indicator</label><div class="oe_diet_div input-group"><input autocomplete="off" jqgrid="jqGrid_diet" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_diet (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_diet(cellvalue, options, rowObject){
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
	calc_jq_height_onchange("jqGrid_diet",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_diet(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_diet input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_diet input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_diet input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_diet input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_diet input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_diet input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_diet input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}