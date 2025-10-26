
var urlParam_phys={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_phys').val(),
	mrn:'',
	episno:''
};
var myfail_msg_phys = new fail_msg_func('div#fail_msg_phys');
var mycurrency_phys =new currencymode([]);
var mycurrency_np_phys =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_phys").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_phys,
					custom_value: galGridCustomValue_phys
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phys },
				formatter: showdetail_phys,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_phys,
					custom_value: galGridCustomValue_phys
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phys },
				formatter: showdetail_phys,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_phys,
					custom_value: galGridCustomValue_phys
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phys },
				formatter: showdetail_phys,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_phys,
					custom_value: galGridCustomValue_phys
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phys },
				formatter: showdetail_phys,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_phys,
					custom_value: galGridCustomValue_phys
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_phys },
				formatter: showdetail_phys,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_phys,
					custom_value: galGridCustomValue_phys
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
				formatter:abscurrency,
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Discount<br>Amount', name: 'discamt', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:abscurrency,unformat:abscurrency_unformat,
				editrules:{required: true},editoptions:{readonly: "readonly"}},
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Net<br>Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter_phys,
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
		pager: "#jqGrid_phys_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_phys",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_phys.clear_fail();
			if($("#jqGrid_phys").data('lastselrow')==undefined||$("#jqGrid_phys").data('lastselrow')==null){
				$("#jqGrid_phys").setSelection($("#jqGrid_phys").getDataIDs()[0]);
			}else{
				$("#jqGrid_phys").setSelection($("#jqGrid_phys").data('lastselrow'));
			}
			$("#jqGrid_phys").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_phys.clear_fail();
			let justsave = $("#jqGrid_phys").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_phys_iladd').click();
				}, 500 );
			}
			$("#jqGrid_phys").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_phys_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){
			$('#jqGrid_phys_iledit,#jqGrid_phys_pagerDelete').hide();
			if($('#jqGrid_phys_iladd').hasClass('ui-disabled')){
				$('#jqGrid_phys_iledit,#jqGrid_phys_pagerDelete').hide();
			}else if(selrowData('#jqGrid_phys').trxtype == 'OE' || selrowData('#jqGrid_phys').trxtype == 'PK'){
				$('#jqGrid_phys_iledit,#jqGrid_phys_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_phys').trxtype != 'PD'){
				$('#jqGrid_phys_iledit').click();
			};
		}
    });
	jqgrid_label_align_right("#jqGrid_phys");
	
	$("#jqGrid_phys").inlineNav('#jqGrid_phys_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_phys
		},
		editParams: myEditOptions_phys_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_phys_pager", {	
		id: "jqGrid_phys_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_phys").jqGrid('getGridParam', 'selrow');	
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
						id: selrowData('#jqGrid_phys').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_phys", urlParam_phys);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_phys", urlParam_phys);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_phys_pager", {	
		id: "jqGrid_phys_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_phys", urlParam_phys);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_phys_pager", {	
		id: "jqGrid_phys_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_phys", urlParam_phys);
		},	
	});

});
	
var myEditOptions_phys = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_phys").data('lastselrow',rowid);
		errorField.length=0;
		myfail_msg_phys.clear_fail();
		$("#jqGrid_phys input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").hide();

		$("#jqGrid_phys input[name='deptcode']").val($("#physdept_dflt").val());
		dialog_deptcode_phys.on();
		dialog_deptcode_phys.id_optid = rowid;
		dialog_deptcode_phys.check(errorField,rowid+"_deptcode","jqGrid_phys",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_phys.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_deptcode_phys.on();
		dialog_chgcode_phys.on();
		dialog_uomcode_phys.on();
		dialog_uom_recv_phys.on();
		dialog_tax_phys.on();
		// dialog_dosage_phys.on();
		// dialog_frequency_phys.on();
		// dialog_instruction_phys.on();
		// dialog_drugindicator_phys.on();
		mycurrency_phys.array.length = 0;
		mycurrency_np_phys.array.length = 0;
		Array.prototype.push.apply(mycurrency_phys.array, ["#jqGrid_phys input[name='totamount']","#jqGrid_phys input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_phys.array, ["#jqGrid_phys input[name='quantity']"]);
		
		mycurrency_phys.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_phys.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_phys input[name='quantity']").on('keyup',{currency: [mycurrency_phys,mycurrency_np_phys]},calculate_line_totgst_and_totamt_phys);
		$("#jqGrid_phys input[name='quantity']").on('blur',{currency: [mycurrency_phys,mycurrency_np_phys]},calculate_line_totgst_and_totamt_phys);

		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);

		$("#jqGrid_phys input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_phys input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_phys_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    	// $("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_phys.formatOff();
		mycurrency_np_phys.formatOff();

		if(parseInt($('#jqGrid_phys input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_phys.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_phys_code").val(),
				// frequency: $("#frequency_phys_code").val(),
				// addinstruction: $("#instruction_phys_code").val(),
				// drugindicator: $("#drugindicator_phys_code").val(),
				taxamount: $("#jqGrid_phys input[name='taxamount']").val(),
				unitprce: $("#jqGrid_phys input[name='unitprce']").val(),
				// totamount: $("#jqGrid_phys input[name='totamount']").val(),
			});
		$("#jqGrid_phys").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_phys").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
		myfail_msg_phys.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_phys')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_phys_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_phys").data('lastselrow',rowid)
		var selrowdata = $('#jqGrid_phys').jqGrid ('getRowData', rowid);
		// write_detail_dosage(selrowdata,true);

		myfail_msg_phys.clear_fail();
		$("#jqGrid_phys input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").hide();

		dialog_deptcode_phys.on();
		dialog_deptcode_phys.id_optid = rowid;
		dialog_deptcode_phys.check(errorField,rowid+"_deptcode","jqGrid_phys",null,null,null );

		dialog_chgcode_phys.on();
		dialog_chgcode_phys.id_optid = rowid;
		dialog_chgcode_phys.check(errorField,rowid+"_chgcode","jqGrid_phys",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phys input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_phys').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phys input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_phys input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_phys input[name='uom_rate']").val(retdata['rate']);
	        	}
	        }
	    );

		dialog_uomcode_phys.on();
		dialog_uomcode_phys.id_optid = rowid;
		dialog_uomcode_phys.check(errorField,rowid+"_uom","jqGrid_phys",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phys input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_phys').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phys input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_phys.on();
		dialog_uom_recv_phys.id_optid = rowid;
		dialog_uom_recv_phys.check(errorField,rowid+"_uom_recv","jqGrid_phys",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phys input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_phys').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phys input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_phys input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_phys.on();
		dialog_tax_phys.id_optid = rowid;
		dialog_tax_phys.check(errorField,rowid+"_taxcode","jqGrid_phys",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phys #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_phys.on();
		// dialog_frequency_phys.on();
		// dialog_instruction_phys.on();
		// dialog_drugindicator_phys.on();

		mycurrency_phys.array.length = 0;
		mycurrency_np_phys.array.length = 0;
		Array.prototype.push.apply(mycurrency_phys.array, ["#jqGrid_phys input[name='totamount']","#jqGrid_phys input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_phys.array, ["#jqGrid_phys input[name='quantity']"]);
		
		mycurrency_phys.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_phys.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_phys input[name='quantity']").on('keyup',{currency: [mycurrency_phys,mycurrency_np_phys]},calculate_line_totgst_and_totamt_phys);
		$("#jqGrid_phys input[name='quantity']").on('blur',{currency: [mycurrency_phys,mycurrency_np_phys]},calculate_line_totgst_and_totamt_phys);

		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_phys input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_phys input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_phys.off();
		// dialog_frequency_phys.off();
		// dialog_instruction_phys.off();
		// dialog_drugindicator_phys.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    	// $("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_phys.formatOff();
		mycurrency_np_phys.formatOff();

		if(parseInt($('#jqGrid_phys input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_phys.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    // ftxtdosage: $("#dosage_phys_code").val(),
				// frequency: $("#frequency_phys_code").val(),
				// addinstruction: $("#instruction_phys_code").val(),
				// drugindicator: $("#drugindicator_phys_code").val(),
				taxamount: $("#jqGrid_phys input[name='taxamount']").val(),
				unitprce: $("#jqGrid_phys input[name='unitprce']").val(),
				// totamount: $("#jqGrid_phys input[name='totamount']").val(),
			});
		$("#jqGrid_phys").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_phys.off();
		// dialog_frequency_phys.off();
		// dialog_instruction_phys.off();
		// dialog_drugindicator_phys.off();
    	$("#jqGrid_phys_pagerRefresh,#jqGrid_phys_pagerDelete").show();
		myfail_msg_phys.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_phys')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_phys",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phys',urlParam_phys,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_phys(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_phys.add_fail({
			id:'quantity',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_phys.del_fail({
			id:'quantity',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_phys #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_phys #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_phys #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_phys.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_phys.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_phys.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_phys.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_phys #"+id_optid+"_quantity",
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

	$("#"+id_optid+"_discamt").val(numeral(discamt).format('0,0.00'));
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	// write_detail_phys('#jqgrid_detail_phys_taxamt',taxamount);
	// write_detail_phys('#jqgrid_detail_phys_discamt',discamt);
	
	var id="#jqGrid_phys #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_phys = new ordialog(
	'deptcode_phys',['sysdb.department'],"#jqGrid_phys input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_phys.gridname);
			dialog_chgcode_phys.urlParam.deptcode = data.deptcode;
			dialog_uomcode_phys.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_phys.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_phys.urlParam.filterCol=['compcode','recstatus','chgdept'];
			dialog_deptcode_phys.urlParam.filterVal=['session.compcode','ACTIVE','1'];
		},
		close: function(){
			// $(dialog_deptcode_phys.textfield)			//lepas close dialog focus on next textfield 
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
dialog_deptcode_phys.makedialog(false);

var dialog_chgcode_phys = new ordialog(
	'chgcode_phys',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_phys input[name='chgcode']",errorField,
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
				deptcode : $("#physdept_dflt").val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_phys').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_phys.del_fail({
				id:'quantity',
				textfld:"#jqGrid_phys #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_phys.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_phys #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_phys.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_phys #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_phys.del_fail({id:'noprod_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_phys.gridname);

			$("#jqGrid_phys #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_phys #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_phys #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phys #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_phys.urlParam.uom = data['uom'];

			dialog_uomcode_phys.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_phys.urlParam.uom = data['uom'];
			$("#jqGrid_phys #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_phys.id_optid = id_optid;
			dialog_uomcode_phys.skipfdl = true;
			dialog_uomcode_phys.check(errorField,id_optid+"_uom","jqGrid_phys",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_phys input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_phys.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_phys.urlParam.uom = data['uom'];
			$("#jqGrid_phys #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_phys.id_optid = id_optid;
			dialog_uom_recv_phys.skipfdl = true;
			dialog_uom_recv_phys.check(errorField,id_optid+"_uom_recv","jqGrid_phys",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_phys.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_phys input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_phys input[name='qtyonhand']").val(retdata['qtyonhand']);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_phys.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_phys #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_phys input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_phys input[name='deptcode']").val(),
							});

							$("#jqGrid_phys #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_phys #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_phys #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_phys #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_phys #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_phys #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_phys #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_phys.check(errorField);

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phys input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_phys.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_phys.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_phys.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_phys.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_phys.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
			dialog_chgcode_phys.urlParam.price = 'PRICE2';
			dialog_chgcode_phys.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
			dialog_chgcode_phys.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_phys.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
			dialog_chgcode_phys.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_phys.urlParam.filterVal = [$('#ordcomtt_phys').val()];
		},
		close: function(obj){
			$("#jqGrid_phys input[name='quantity']").focus().select();
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
dialog_chgcode_phys.makedialog(false);

var dialog_uomcode_phys = new ordialog(
	'uom_phys',['material.uom AS u'],"#jqGrid_phys input[name='uom']",errorField,
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
					deptcode : $("#physdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_phys').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_phys.del_fail({id:'noprod_'+id_optid});
			myfail_msg_phys.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_phys.gridname);
			dialog_chgcode_phys.urlParam.uom = data['uom'];

			$("#jqGrid_phys #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_phys #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_phys #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phys #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_phys #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_phys #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_phys #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_phys #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_phys #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_phys #"+id_optid+"_quantity").val('');

			dialog_tax_phys.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phys input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_phys input[name=chgcode]").val();
			$('div[role=dialog][aria-describedby=otherdialog_uom_phys] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+')');

			let id_optid = obj_.id_optid;

			dialog_uomcode_phys.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_phys.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_phys.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_phys.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_phys.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
			dialog_uomcode_phys.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
			dialog_uomcode_phys.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
			dialog_uomcode_phys.urlParam.uom = $("#jqGrid_phys input[name='uom']").val();
			dialog_uomcode_phys.urlParam.price = 'PRICE2';
			dialog_uomcode_phys.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_phys.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_phys.urlParam.filterVal = [$('#ordcomtt_phys').val()];
		},
		close: function(){
			$("#jqGrid_phys input[name='quantity']").focus().select();
			// $(dialog_uomcode_phys.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uomcode_phys.makedialog(false);

var dialog_uom_recv_phys = new ordialog(
	'uom_recv_phys',['material.uom AS u'],"#jqGrid_phys input[name='uom_recv']",errorField,
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
					deptcode : $("#physdept_dflt").val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_phys').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_phys.gridname);

			myfail_msg_phys.del_fail({id:'noprod_'+id_optid});
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_phys.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_phys #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_phys input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_phys input[name='deptcode']").val(),
				});

				$("#jqGrid_phys #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phys #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phys #"+id_optid+"_quantity").val('');
				$("#jqGrid_phys #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phys input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_phys.urlParam.url = "./ordcom/table";
			dialog_uom_recv_phys.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_phys.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_phys.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_phys.urlParam.entrydate = $("#jqGrid_phys input[name='trxdate']").val();
			dialog_uom_recv_phys.urlParam.chgcode = $("#jqGrid_phys input[name='chgcode']").val();
			dialog_uom_recv_phys.urlParam.deptcode = $("#jqGrid_phys input[name='deptcode']").val();
			dialog_uom_recv_phys.urlParam.price = 'PRICE2';
			dialog_uom_recv_phys.urlParam.uom = $("#jqGrid_phys input[name='uom_recv']").val();
			dialog_uom_recv_phys.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_phys.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_phys.urlParam.filterVal = [$('#ordcomtt_phys').val()];
		},
		close: function(){
			$("#jqGrid_phys input[name='quantity']").focus().select();
			// $(dialog_uomcode_phys.textfield)			//lepas close dialog focus on next textfield 
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
dialog_uom_recv_phys.makedialog(false);

var dialog_tax_phys = new ordialog(
	'taxcode_phys',['hisdb.taxmast'],"#jqGrid_phys input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_phys.gridname);
			$("#jqGrid_phys #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phys input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_phys.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_phys.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_phys.textfield)			//lepas close dialog focus on next textfield 
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
dialog_tax_phys.makedialog(false);

function trxdateCustomEdit_phys(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_phys" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_phys(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='uom_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function totamountFormatter_phys(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_phys(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_phys(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_phys(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_phys(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_phys(val,opt){
	var myreturn = `<label class='oe_phys_label'>Dose</label><div class="oe_phys_div input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phys_label'>Frequency</label><div class="oe_phys_div input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phys_label'>Instruction</label><div class="oe_phys_div input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phys_label'>Indicator</label><div class="oe_phys_div input-group"><input autocomplete="off" jqgrid="jqGrid_phys" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_phys (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_phys(cellvalue, options, rowObject){
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
	calc_jq_height_onchange("jqGrid_phys",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_phys(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_phys input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_phys input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_phys input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_phys input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_phys input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_phys input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_phys input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}