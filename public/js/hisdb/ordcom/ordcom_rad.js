
var urlParam_rad={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_rad').val(),
	mrn:'',
	episno:''
};
var myfail_msg_rad = new fail_msg_func('div#fail_msg_rad');
var mycurrency_rad =new currencymode([]);
var mycurrency_np_rad =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_rad").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_rad,
					custom_value: galGridCustomValue_rad
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rad },
				formatter: showdetail_rad,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_rad,
					custom_value: galGridCustomValue_rad
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rad },
				formatter: showdetail_rad,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_rad,
					custom_value: galGridCustomValue_rad
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rad },
				formatter: showdetail_rad,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_rad,
					custom_value: galGridCustomValue_rad
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_rad },
				formatter: showdetail_rad,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_rad,
					custom_value: galGridCustomValue_rad
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_rad },
				formatter: showdetail_rad,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_rad,
					custom_value: galGridCustomValue_rad
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
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Net<br>Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter_rad,
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
		pager: "#jqGrid_rad_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_rad",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_rad.clear_fail();
			if($("#jqGrid_rad").data('lastselrow')==undefined||$("#jqGrid_rad").data('lastselrow')==null){
				$("#jqGrid_rad").setSelection($("#jqGrid_rad").getDataIDs()[0]);
			}else{
				$("#jqGrid_rad").setSelection($("#jqGrid_rad").data('lastselrow'));
			}
			$("#jqGrid_rad").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_rad.clear_fail();

			let justsave = $("#jqGrid_rad").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_rad_iladd').click();
				}, 500 );
			}
			$("#jqGrid_rad").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_rad_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			$('#jqGrid_rad_iledit,#jqGrid_rad_pagerDelete').hide();
			if($('#jqGrid_rad_iladd').hasClass('ui-disabled')){
				$('#jqGrid_rad_iledit,#jqGrid_rad_pagerDelete').hide();
			}else if(selrowData('#jqGrid_rad').trxtype == 'OE' || selrowData('#jqGrid_rad').trxtype == 'PK'){
				$('#jqGrid_rad_iledit,#jqGrid_rad_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_rad').trxtype != 'PD'){
				$('#jqGrid_rad_iledit').click();
			}
		}
    });
	jqgrid_label_align_right("#jqGrid_rad");
	
	$("#jqGrid_rad").inlineNav('#jqGrid_rad_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_rad
		},
		editParams: myEditOptions_rad_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_rad_pager", {	
		id: "jqGrid_rad_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_rad").jqGrid('getGridParam', 'selrow');	
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
						id: selrowData('#jqGrid_rad').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_rad", urlParam_rad);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_rad", urlParam_rad);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_rad_pager", {	
		id: "jqGrid_rad_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_rad", urlParam_rad);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_rad_pager", {	
		id: "jqGrid_rad_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_rad", urlParam_rad);
		},	
	});

});
	
var myEditOptions_rad = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_rad").data('lastselrow',rowid);
		errorField.length=0;
		myfail_msg_rad.clear_fail();
		$("#jqGrid_rad input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").hide();

		$("#jqGrid_rad input[name='deptcode']").val($("#raddept_dflt").val());
		dialog_deptcode_rad.on();
		dialog_deptcode_rad.id_optid = rowid;
		dialog_deptcode_rad.check(errorField,rowid+"_deptcode","jqGrid_rad",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_rad.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_rad.on();
		dialog_chgcode_rad.on();
		dialog_uomcode_rad.on();
		dialog_uom_recv_rad.on();
		dialog_tax_rad.on();
		// dialog_dosage_rad.on();
		// dialog_frequency_rad.on();
		// dialog_instruction_rad.on();
		// dialog_drugindicator_rad.on();
		mycurrency_rad.array.length = 0;
		mycurrency_np_rad.array.length = 0;
		Array.prototype.push.apply(mycurrency_rad.array, ["#jqGrid_rad input[name='totamount']","#jqGrid_rad input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_rad.array, ["#jqGrid_rad input[name='quantity']"]);
		
		mycurrency_rad.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_rad.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_rad input[name='quantity']").on('keyup',{currency: [mycurrency_rad,mycurrency_np_rad]},calculate_line_totgst_and_totamt_rad);
		$("#jqGrid_rad input[name='quantity']").on('blur',{currency: [mycurrency_rad,mycurrency_np_rad]},calculate_line_totgst_and_totamt_rad);

		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_rad input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_rad input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_rad_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    	// $("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_rad.formatOff();
		mycurrency_np_rad.formatOff();

		if(parseInt($('#jqGrid_rad input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_rad.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_rad_code").val(),
				// frequency: $("#frequency_rad_code").val(),
				// addinstruction: $("#instruction_rad_code").val(),
				// drugindicator: $("#drugindicator_rad_code").val(),
				taxamount: $("#jqGrid_rad input[name='taxamount']").val(),
				unitprce: $("#jqGrid_rad input[name='unitprce']").val(),
				// totamount: $("#jqGrid_rad input[name='totamount']").val(),
			});
		$("#jqGrid_rad").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_rad").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
		myfail_msg_rad.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_rad')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_rad_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_rad").data('lastselrow',rowid);
		var selrowdata = $('#jqGrid_rad').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_rad.clear_fail();
		$("#jqGrid_rad input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").hide();

		dialog_deptcode_rad.on();
		dialog_deptcode_rad.id_optid = rowid;
		dialog_deptcode_rad.check(errorField,rowid+"_deptcode","jqGrid_rad",null,null,null );

		dialog_chgcode_rad.on();
		dialog_chgcode_rad.id_optid = rowid;
		dialog_chgcode_rad.check(errorField,rowid+"_chgcode","jqGrid_rad",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rad input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rad').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rad input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_rad input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_rad input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_rad.on();
		dialog_uomcode_rad.id_optid = rowid;
		dialog_uomcode_rad.check(errorField,rowid+"_uom","jqGrid_rad",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rad input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rad').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rad input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_rad.on();
		dialog_uom_recv_rad.id_optid = rowid;
		dialog_uom_recv_rad.check(errorField,rowid+"_uom_recv","jqGrid_rad",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_rad input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_rad').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rad input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_rad input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_rad.on();
		dialog_tax_rad.id_optid = rowid;
		dialog_tax_rad.check(errorField,rowid+"_taxcode","jqGrid_rad",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_rad #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_rad.on();
		// dialog_frequency_rad.on();
		// dialog_instruction_rad.on();
		// dialog_drugindicator_rad.on();

		mycurrency_rad.array.length = 0;
		mycurrency_np_rad.array.length = 0;
		Array.prototype.push.apply(mycurrency_rad.array, ["#jqGrid_rad input[name='totamount']","#jqGrid_rad input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_rad.array, ["#jqGrid_rad input[name='quantity']"]);
		
		mycurrency_rad.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_rad.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_rad input[name='quantity']").on('keyup',{currency: [mycurrency_rad,mycurrency_np_rad]},calculate_line_totgst_and_totamt_rad);
		$("#jqGrid_rad input[name='quantity']").on('blur',{currency: [mycurrency_rad,mycurrency_np_rad]},calculate_line_totgst_and_totamt_rad);

		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_rad input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_rad input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_rad.off();
		// dialog_frequency_rad.off();
		// dialog_instruction_rad.off();
		// dialog_drugindicator_rad.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    	// $("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_rad.formatOff();
		mycurrency_np_rad.formatOff();

		if(parseInt($('#jqGrid_rad input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_rad.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_rad_code").val(),
				// frequency: $("#frequency_rad_code").val(),
				// addinstruction: $("#instruction_rad_code").val(),
				// drugindicator: $("#drugindicator_rad_code").val(),
				taxamount: $("#jqGrid_rad input[name='taxamount']").val(),
				unitprce: $("#jqGrid_rad input[name='unitprce']").val(),
				// totamount: $("#jqGrid_rad input[name='totamount']").val(),
			});
		$("#jqGrid_rad").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_rad.off();
		// dialog_frequency_rad.off();
		// dialog_instruction_rad.off();
		// dialog_drugindicator_rad.off();
    	$("#jqGrid_rad_pagerRefresh,#jqGrid_rad_pagerDelete").show();
		myfail_msg_rad.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_rad')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_rad",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_rad',urlParam_rad,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_rad(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_rad.add_fail({
			id:'quantity',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_rad.del_fail({
			id:'quantity',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_rad #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_rad #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_rad #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_rad.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_rad.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_rad.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_rad.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_rad #"+id_optid+"_quantity",
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

	var discamt = calc_discamt_main($('#ordcomtt_rad').val(),$("#jqGrid_rad #"+id_optid+"_chgcode").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_rad('#jqgrid_detail_rad_taxamt',taxamount);
	// write_detail_rad('#jqgrid_detail_rad_discamt',discamt);
	
	var id="#jqGrid_rad #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_rad = new ordialog(
	'deptcode_rad',['sysdb.department'],"#jqGrid_rad input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_rad.gridname);
			dialog_chgcode_rad.urlParam.deptcode = data.deptcode;
			dialog_uomcode_rad.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_rad.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_rad.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode_rad.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_rad.textfield)			//lepas close dialog focus on next textfield 
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
dialog_deptcode_rad.makedialog(false);

var dialog_chgcode_rad = new ordialog(
	'chgcode_rad',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_rad input[name='chgcode']",errorField,
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
				filterVal : [$('#ordcomtt_rad').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_rad.del_fail({
				id:'quantity',
				textfld:"#jqGrid_rad #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_rad.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_rad #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_rad.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_rad #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_rad.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_rad.gridname);

			$("#jqGrid_rad #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_rad #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_rad #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rad #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_rad.urlParam.uom = data['uom'];

			dialog_uomcode_rad.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_rad.urlParam.uom = data['uom'];
			$("#jqGrid_rad #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_rad.id_optid = id_optid;
			dialog_uomcode_rad.skipfdl = true;
			dialog_uomcode_rad.check(errorField,id_optid+"_uom","jqGrid_rad",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_rad input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_rad.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_rad.urlParam.uom = data['uom'];
			$("#jqGrid_rad #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_rad.id_optid = id_optid;
			dialog_uom_recv_rad.skipfdl = true;
			dialog_uom_recv_rad.check(errorField,id_optid+"_uom_recv","jqGrid_rad",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_rad.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_rad input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_rad input[name='qtyonhand']").val(retdata['qtyonhand']);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_rad.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_rad #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_rad input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_rad input[name='deptcode']").val(),
							});

							$("#jqGrid_rad #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_rad #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_rad #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_rad #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_rad #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_rad #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_rad #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_rad.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rad input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_rad.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_rad.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_rad.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_rad.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_rad.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
			dialog_chgcode_rad.urlParam.price = 'PRICE2';
			dialog_chgcode_rad.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
			dialog_chgcode_rad.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_rad.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
			dialog_chgcode_rad.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_rad.urlParam.filterVal = [$('#ordcomtt_rad').val()];
		},
		close: function(obj){
			$("#jqGrid_rad input[name='quantity']").focus().select();
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
dialog_chgcode_rad.makedialog(false);

var dialog_uomcode_rad = new ordialog(
	'uom_rad',['material.uom AS u'],"#jqGrid_rad input[name='uom']",errorField,
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
					filterVal : [$('#ordcomtt_rad').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_rad.del_fail({id:'noprod_'+id_optid});
			myfail_msg_rad.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_rad.gridname);
			dialog_chgcode_rad.urlParam.uom = data['uom'];

			$("#jqGrid_rad #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_rad #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_rad #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rad #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_rad #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_rad #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_rad #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_rad #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_rad #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_rad #"+id_optid+"_quantity").val('');

			dialog_tax_rad.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rad input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_rad input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_rad] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_rad.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_rad.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_rad.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_rad.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_rad.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
			dialog_uomcode_rad.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
			dialog_uomcode_rad.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
			dialog_uomcode_rad.urlParam.uom = $("#jqGrid_rad input[name='uom']").val();
			dialog_uomcode_rad.urlParam.price = 'PRICE2';
			dialog_uomcode_rad.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_rad.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_rad.urlParam.filterVal = [$('#ordcomtt_rad').val()];
		},
		close: function(){
			$("#jqGrid_rad input[name='quantity']").focus().select();
			// $(dialog_uomcode_rad.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uomcode_rad.makedialog(false);

var dialog_uom_recv_rad = new ordialog(
	'uom_recv_rad',['material.uom AS u'],"#jqGrid_rad input[name='uom_recv']",errorField,
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
					deptcode : $("#raddept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_rad').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_rad.gridname);

			myfail_msg_rad.del_fail({id:'noprod_'+id_optid});
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_rad.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_rad #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_rad input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_rad input[name='deptcode']").val(),
				});

				$("#jqGrid_rad #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_rad #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_rad #"+id_optid+"_quantity").val('');
				$("#jqGrid_rad #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_rad input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_rad.urlParam.url = "./ordcom/table";
			dialog_uom_recv_rad.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_rad.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_rad.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_rad.urlParam.entrydate = $("#jqGrid_rad input[name='trxdate']").val();
			dialog_uom_recv_rad.urlParam.chgcode = $("#jqGrid_rad input[name='chgcode']").val();
			dialog_uom_recv_rad.urlParam.deptcode = $("#jqGrid_rad input[name='deptcode']").val();
			dialog_uom_recv_rad.urlParam.price = 'PRICE2';
			dialog_uom_recv_rad.urlParam.uom = $("#jqGrid_rad input[name='uom_recv']").val();
			dialog_uom_recv_rad.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_rad.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_rad.urlParam.filterVal = [$('#ordcomtt_rad').val()];
		},
		close: function(){
			$("#jqGrid_rad input[name='quantity']").focus().select();
			// $(dialog_uomcode_rad.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uom_recv_rad.makedialog(false);

var dialog_tax_rad = new ordialog(
	'taxcode_rad',['hisdb.taxmast'],"#jqGrid_rad input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_rad.gridname);
			$("#jqGrid_rad #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_rad input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_rad.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_rad.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_rad.textfield)			//lepas close dialog focus on next textfield 
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
dialog_tax_rad.makedialog(false);

function trxdateCustomEdit_rad(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_rad" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_rad(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_rad(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_rad(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_rad(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_rad(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_rad(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_rad(val,opt){
	var myreturn = `<label class='oe_rad_label'>Dose</label><div class="oe_rad_div input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rad_label'>Frequency</label><div class="oe_rad_div input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rad_label'>Instruction</label><div class="oe_rad_div input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_rad_label'>Indicator</label><div class="oe_rad_div input-group"><input autocomplete="off" jqgrid="jqGrid_rad" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_rad (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_rad(cellvalue, options, rowObject){
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
	calc_jq_height_onchange("jqGrid_rad",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_rad(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_rad input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_rad input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_rad input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_rad input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_rad input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_rad input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_rad input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}