
var urlParam_lab={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_lab').val(),
	mrn:'',
	episno:''
};
var myfail_msg_lab = new fail_msg_func('div#fail_msg_lab');
var mycurrency_lab =new currencymode([]);
var mycurrency_np_lab =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_lab").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_lab,
					custom_value: galGridCustomValue_lab
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_lab },
				formatter: showdetail_lab,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_lab,
					custom_value: galGridCustomValue_lab
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_lab },
				formatter: showdetail_lab,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_lab,
					custom_value: galGridCustomValue_lab
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_lab },
				formatter: showdetail_lab,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_lab,
					custom_value: galGridCustomValue_lab
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_lab },
				formatter: showdetail_lab,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_lab,
					custom_value: galGridCustomValue_lab
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_lab },
				formatter: showdetail_lab,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_lab,
					custom_value: galGridCustomValue_lab
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
				formatter:totamountFormatter_lab,
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
		pager: "#jqGrid_lab_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_lab",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_lab.clear_fail();
			if($("#jqGrid_lab").data('lastselrow')==undefined||$("#jqGrid_lab").data('lastselrow')==null){
				$("#jqGrid_lab").setSelection($("#jqGrid_lab").getDataIDs()[0]);
			}else{
				$("#jqGrid_lab").setSelection($("#jqGrid_lab").data('lastselrow'));
			}
			$("#jqGrid_lab").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_lab.clear_fail();

			let justsave = $("#jqGrid_lab").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_lab_iladd').click();
				}, 500 );
			}
			$("#jqGrid_lab").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_lab_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			$('#jqGrid_lab_iledit,#jqGrid_lab_pagerDelete').hide();
			if($('#jqGrid_lab_iladd').hasClass('ui-disabled')){
				$('#jqGrid_lab_iledit,#jqGrid_lab_pagerDelete').hide();
			}else if(selrowData('#jqGrid_lab').trxtype == 'OE' || selrowData('#jqGrid_lab').trxtype == 'PK'){
				$('#jqGrid_lab_iledit,#jqGrid_lab_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_lab').trxtype != 'PD'){
				$('#jqGrid_lab_iledit').click();
			}
		}
    });
	jqgrid_label_align_right("#jqGrid_lab");
	
	$("#jqGrid_lab").inlineNav('#jqGrid_lab_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_lab
		},
		editParams: myEditOptions_lab_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_lab_pager", {	
		id: "jqGrid_lab_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_lab").jqGrid('getGridParam', 'selrow');	
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
						id: selrowData('#jqGrid_lab').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_lab", urlParam_lab);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_lab", urlParam_lab);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_lab_pager", {	
		id: "jqGrid_lab_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_lab", urlParam_lab);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_lab_pager", {	
		id: "jqGrid_lab_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_lab", urlParam_lab);
		},	
	});

});
	
var myEditOptions_lab = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_lab").data('lastselrow',rowid);
		errorField.length=0;
		myfail_msg_lab.clear_fail();
		$("#jqGrid_lab input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").hide();

		$("#jqGrid_lab input[name='deptcode']").val($("#labdept_dflt").val());
		dialog_deptcode_lab.on();
		dialog_deptcode_lab.id_optid = rowid;
		dialog_deptcode_lab.check(errorField,rowid+"_deptcode","jqGrid_lab",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_lab.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_lab.on();
		dialog_chgcode_lab.on();
		dialog_uomcode_lab.on();
		dialog_uom_recv_lab.on();
		dialog_tax_lab.on();

		$(dialog_uomcode_lab.textfield).prop('readonly',true);
		$(dialog_uom_recv_lab.textfield).prop('readonly',true);

		// dialog_dosage_lab.on();
		// dialog_frequency_lab.on();
		// dialog_instruction_lab.on();
		// dialog_drugindicator_lab.on();
		mycurrency_lab.array.length = 0;
		mycurrency_np_lab.array.length = 0;
		Array.prototype.push.apply(mycurrency_lab.array, ["#jqGrid_lab input[name='totamount']","#jqGrid_lab input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_lab.array, ["#jqGrid_lab input[name='quantity']"]);
		
		mycurrency_lab.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_lab.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_lab input[name='quantity']").on('keyup',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);
		$("#jqGrid_lab input[name='quantity']").on('blur',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);

		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_lab input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_lab input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_lab_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    	// $("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_lab.formatOff();
		mycurrency_np_lab.formatOff();

		if(parseInt($('#jqGrid_lab input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_lab.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_lab_code").val(),
				// frequency: $("#frequency_lab_code").val(),
				// addinstruction: $("#instruction_lab_code").val(),
				// drugindicator: $("#drugindicator_lab_code").val(),
				taxamount: $("#jqGrid_lab input[name='taxamount']").val(),
				unitprce: $("#jqGrid_lab input[name='unitprce']").val(),
				// totamount: $("#jqGrid_lab input[name='totamount']").val(),
			});
		$("#jqGrid_lab").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_lab").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
		myfail_msg_lab.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_lab')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_lab_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_lab").data('lastselrow',rowid);
		var selrowdata = $('#jqGrid_lab').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_lab.clear_fail();
		$("#jqGrid_lab input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").hide();

		dialog_deptcode_lab.on();
		dialog_deptcode_lab.id_optid = rowid;
		dialog_deptcode_lab.check(errorField,rowid+"_deptcode","jqGrid_lab",null,null,null );

		dialog_chgcode_lab.on();
		dialog_chgcode_lab.id_optid = rowid;
		dialog_chgcode_lab.check(errorField,rowid+"_chgcode","jqGrid_lab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_lab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_lab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_lab input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_lab input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_lab input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_lab.on();
		dialog_uomcode_lab.id_optid = rowid;
		dialog_uomcode_lab.check(errorField,rowid+"_uom","jqGrid_lab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_lab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_lab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_lab input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_lab.on();
		dialog_uom_recv_lab.id_optid = rowid;
		dialog_uom_recv_lab.check(errorField,rowid+"_uom_recv","jqGrid_lab",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_lab input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_lab').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_lab input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_lab input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_lab.on();
		dialog_tax_lab.id_optid = rowid;
		dialog_tax_lab.check(errorField,rowid+"_taxcode","jqGrid_lab",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_lab #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_lab.on();
		// dialog_frequency_lab.on();
		// dialog_instruction_lab.on();
		// dialog_drugindicator_lab.on();

		mycurrency_lab.array.length = 0;
		mycurrency_np_lab.array.length = 0;
		Array.prototype.push.apply(mycurrency_lab.array, ["#jqGrid_lab input[name='totamount']","#jqGrid_lab input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_lab.array, ["#jqGrid_lab input[name='quantity']"]);
		
		mycurrency_lab.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_lab.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_lab input[name='quantity']").on('keyup',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);
		$("#jqGrid_lab input[name='quantity']").on('blur',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);

		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_lab input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_lab input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_lab.off();
		// dialog_frequency_lab.off();
		// dialog_instruction_lab.off();
		// dialog_drugindicator_lab.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    	// $("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_lab.formatOff();
		mycurrency_np_lab.formatOff();

		if(parseInt($('#jqGrid_lab input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_lab.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_lab_code").val(),
				// frequency: $("#frequency_lab_code").val(),
				// addinstruction: $("#instruction_lab_code").val(),
				// drugindicator: $("#drugindicator_lab_code").val(),
				taxamount: $("#jqGrid_lab input[name='taxamount']").val(),
				unitprce: $("#jqGrid_lab input[name='unitprce']").val(),
				// totamount: $("#jqGrid_lab input[name='totamount']").val(),
			});
		$("#jqGrid_lab").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_lab.off();
		// dialog_frequency_lab.off();
		// dialog_instruction_lab.off();
		// dialog_drugindicator_lab.off();
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
		myfail_msg_lab.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_lab')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_lab",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_lab',urlParam_lab,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_lab(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_lab.add_fail({
			id:'quantity',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_lab.del_fail({
			id:'quantity',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_lab #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_lab #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_lab #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_lab.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_lab.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_lab.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_lab.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
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

	var discamt = calc_discamt_main($('#ordcomtt_lab').val(),$("#jqGrid_lab #"+id_optid+"_chgcode").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_lab('#jqgrid_detail_lab_taxamt',taxamount);
	// write_detail_lab('#jqgrid_detail_lab_discamt',discamt);
	
	var id="#jqGrid_lab #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_lab = new ordialog(
	'deptcode_lab',['sysdb.department'],"#jqGrid_lab input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_lab.gridname);
			dialog_chgcode_lab.urlParam.deptcode = data.deptcode;
			dialog_uomcode_lab.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_lab.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_lab.urlParam.filterCol=['compcode','recstatus','chgdept'];
			dialog_deptcode_lab.urlParam.filterVal=['session.compcode','ACTIVE','1'];
		},
		close: function(){
			// $(dialog_deptcode_lab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_deptcode_lab.makedialog(false);

var dialog_chgcode_lab = new ordialog(
	'chgcode_lab',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_lab input[name='chgcode']",errorField,
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
				deptcode : $("#labdept_dflt").val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_lab').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_lab.del_fail({
				id:'quantity',
				textfld:"#jqGrid_lab #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_lab.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_lab #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_lab.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_lab #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_lab.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_lab.gridname);

			$("#jqGrid_lab #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_lab #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_lab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_lab #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_lab.urlParam.uom = data['uom'];

			dialog_uomcode_lab.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_lab.urlParam.uom = data['uom'];
			$("#jqGrid_lab #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_lab.id_optid = id_optid;
			dialog_uomcode_lab.skipfdl = true;
			dialog_uomcode_lab.check(errorField,id_optid+"_uom","jqGrid_lab",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_lab input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_lab.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_lab.urlParam.uom = data['uom'];
			$("#jqGrid_lab #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_lab.id_optid = id_optid;
			dialog_uom_recv_lab.skipfdl = true;
			dialog_uom_recv_lab.check(errorField,id_optid+"_uom_recv","jqGrid_lab",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_lab.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_lab input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_lab input[name='qtyonhand']").val(retdata['qtyonhand']);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_lab.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_lab #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_lab input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_lab input[name='deptcode']").val(),
							});

							$("#jqGrid_lab #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_lab #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_lab #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_lab #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_lab #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_lab #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_lab #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_lab.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_lab input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_lab.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_lab.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_lab.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_lab.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_lab.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
			dialog_chgcode_lab.urlParam.price = 'PRICE2';
			dialog_chgcode_lab.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
			dialog_chgcode_lab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_lab.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
			dialog_chgcode_lab.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_lab.urlParam.filterVal = [$('#ordcomtt_lab').val()];
		},
		close: function(obj){
			$("#jqGrid_lab input[name='quantity']").focus().select();
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
dialog_chgcode_lab.makedialog(false);

var dialog_uomcode_lab = new ordialog(
	'uom_lab',['material.uom AS u'],"#jqGrid_lab input[name='uom']",errorField,
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
					deptcode : $("#labdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_lab').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_lab.del_fail({id:'noprod_'+id_optid});
			myfail_msg_lab.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_lab.gridname);
			dialog_chgcode_lab.urlParam.uom = data['uom'];

			$("#jqGrid_lab #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_lab #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_lab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_lab #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_lab #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_lab #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_lab #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_lab #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_lab #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_lab #"+id_optid+"_quantity").val('');

			dialog_tax_lab.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_lab input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_lab input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_lab] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_lab.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_lab.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_lab.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_lab.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_lab.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
			dialog_uomcode_lab.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
			dialog_uomcode_lab.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
			dialog_uomcode_lab.urlParam.uom = $("#jqGrid_lab input[name='uom']").val();
			dialog_uomcode_lab.urlParam.price = 'PRICE2';
			dialog_uomcode_lab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_lab.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_lab.urlParam.filterVal = [$('#ordcomtt_lab').val()];
		},
		close: function(){
			$("#jqGrid_lab input[name='quantity']").focus().select();
			// $(dialog_uomcode_lab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uomcode_lab.makedialog(false);

var dialog_uom_recv_lab = new ordialog(
	'uom_recv_lab',['material.uom AS u'],"#jqGrid_lab input[name='uom_recv']",errorField,
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
					deptcode : $("#labdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_lab').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_lab.gridname);

			myfail_msg_lab.del_fail({id:'noprod_'+id_optid});
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_lab.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_lab #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_lab input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_lab input[name='deptcode']").val(),
				});

				$("#jqGrid_lab #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_lab #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_lab #"+id_optid+"_quantity").val('');
				$("#jqGrid_lab #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_lab input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_lab.urlParam.url = "./ordcom/table";
			dialog_uom_recv_lab.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_lab.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_lab.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_lab.urlParam.entrydate = $("#jqGrid_lab input[name='trxdate']").val();
			dialog_uom_recv_lab.urlParam.chgcode = $("#jqGrid_lab input[name='chgcode']").val();
			dialog_uom_recv_lab.urlParam.deptcode = $("#jqGrid_lab input[name='deptcode']").val();
			dialog_uom_recv_lab.urlParam.price = 'PRICE2';
			dialog_uom_recv_lab.urlParam.uom = $("#jqGrid_lab input[name='uom_recv']").val();
			dialog_uom_recv_lab.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_lab.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_lab.urlParam.filterVal = [$('#ordcomtt_lab').val()];
		},
		close: function(){
			$("#jqGrid_lab input[name='quantity']").focus().select();
			// $(dialog_uomcode_lab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uom_recv_lab.makedialog(false);

var dialog_tax_lab = new ordialog(
	'taxcode_lab',['hisdb.taxmast'],"#jqGrid_lab input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_lab.gridname);
			$("#jqGrid_lab #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_lab input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_lab.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_lab.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_lab.textfield)			//lepas close dialog focus on next textfield 
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
dialog_tax_lab.makedialog(false);

function trxdateCustomEdit_lab(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_lab" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_lab(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	// myreturn += `<input type='hidden' name='unitprce' id='`+id_optid+`_unitprce'>`;
	myreturn += `<div><input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_lab(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_lab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_lab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_lab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_lab(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_lab(val,opt){
	var myreturn = `<label class='oe_lab_label'>Dose</label><div class="oe_lab_div input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_lab_label'>Frequency</label><div class="oe_lab_div input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_lab_label'>Instruction</label><div class="oe_lab_div input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_lab_label'>Indicator</label><div class="oe_lab_div input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_lab (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_lab(cellvalue, options, rowObject){
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
	calc_jq_height_onchange("jqGrid_lab",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_lab(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_lab input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_lab input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_lab input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_lab input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_lab input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_lab input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_lab input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}