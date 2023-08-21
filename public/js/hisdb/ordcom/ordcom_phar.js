
var urlParam_phar={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_phar').val(),
	mrn:'',
	episno:''
};
var myfail_msg_phar = new fail_msg_func('div#fail_msg_phar');
var mycurrency_phar =new currencymode([]);
var mycurrency_np_phar =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_phar").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Cost Price', name: 'cost_price', width: 100, classes: 'wrap txnum', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{
				label: 'Unit Price', name: 'unitprce', width: 100, classes: 'wrap txnum', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{
				label: 'Quantity', name: 'quantity', width: 80, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{ label: 'Total Amount <br>Before Tax', name: 'amount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Discount<br>Amount', name: 'discamount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Tax<br>Amount', name: 'taxamount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Total<br>Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},{
				label: 'Dosage', name: 'remark', width: 200, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_phar },formatter:remark_formatter,unformat:remark_unformatter,
				edittype: 'custom', editoptions:
				{
					custom_element: remarkCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'drugindicator', name: 'drugindicator', width: 80, classes: 'wrap', hidden: true },
			{ label: 'frequency', name: 'frequency', width: 80, classes: 'wrap', hidden: true },
			{ label: 'ftxtdosage', name: 'ftxtdosage', width: 80, classes: 'wrap', hidden: true },
			{ label: 'addinstruction', name: 'addinstruction', width: 80, classes: 'wrap', hidden: true },
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
		pager: "#jqGrid_phar_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_phar");
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_phar.clear_fail;
		},
		afterShowForm: function (rowid) {
		}
    });
	
	$("#jqGrid_phar").inlineNav('#jqGrid_phar_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_phar
		},
		editParams: myEditOptions_phar,
			
	}).jqGrid('navButtonAdd', "#jqGrid_phar_pager", {	
		id: "jqGrid_phar_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_phar").jqGrid('getGridParam', 'selrow');	
			// if (!selRowId) {	
			// 	bootbox.alert('Please select row');	
			// } else {	
			// 	bootbox.confirm({	
			// 		message: "Are you sure you want to delete this row?",	
			// 		buttons: {	
			// 			confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }	
			// 		},	
			// 		callback: function (result) {	
			// 			if (result == true) {	
			// 				// param = {	
			// 				// 	_token: $("#_token").val(),	
			// 				// 	action: 'saveForm_ordcom',	
			// 				// 	// cheqno: $('#cheqno').val(),	
			// 				// 	// mrn: selrowData('#jqGrid_phar').mrn,	
			// 				// }	
			// 				// $.post( "./ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){	
			// 				// }).fail(function (data) {	
			// 				// 	$('#p_error').text(data.responseText);	
			// 				// }).done(function (data) {	
			// 				// 	refreshGrid("#jqGrid_phar", urlParam_phar);	
			// 				// });	
			// 			}else{	
			// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
			// 			}	
			// 		}	
			// 	});	
			// }	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_phar_pager", {	
		id: "jqGrid_phar_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_phar", urlParam_phar);	
		},	
	});

});
	
var myEditOptions_phar = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		myfail_msg_phar.clear_fail;
		$("#jqGrid_phar input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").hide();

		dialog_deptcode_phar.on();
		dialog_chgcode_phar.on();
		dialog_uomcode_phar.on();
		dialog_uom_recv_phar.on();
		dialog_tax_phar.on();
		dialog_dosage_phar.on();
		dialog_frequency_phar.on();
		dialog_instruction_phar.on();
		dialog_drugindicator_phar.on();
		mycurrency_phar.array.length = 0;
		mycurrency_np_phar.array.length = 0;
		Array.prototype.push.apply(mycurrency_phar.array, ["#jqGrid_phar input[name='unitprce']","#jqGrid_phar input[name='billtypeperct']","#jqGrid_phar input[name='billtypeamt']","#jqGrid_phar input[name='totamount']","#jqGrid_phar input[name='amount']","#jqGrid_phar input[name='taxamount']","#jqGrid_phar input[name='discamount']"]);
		Array.prototype.push.apply(mycurrency_np_phar.array, ["#jqGrid_phar input[name='qtyonhand']","#jqGrid_phar input[name='quantity']"]);
		
		mycurrency_phar.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_phar.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_phar input[name='unitprce'],#jqGrid_phar input[name='quantity']").on('keyup',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);
		$("#jqGrid_phar input[name='unitprce'],#jqGrid_phar input[name='quantity']").on('blur',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);
		$("#jqGrid_phar input[name='unitprce'],#jqGrid_phar input[name='quantity']").on('blur',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_conversion_factor);

		// $("#jqGrid_phar input[name='qtyonhand']").keydown(function(e) {//when click tab at totamount, auto save
		// 	var code = e.keyCode || e.which;
		// 	if (code == '9'){
		// 		delay(function(){
		// 			$('#jqGrid_phar_ilsave').click();
		// 			addmore_jqGrid_phar.state = true;
		// 		}, 500 );
		// 	}
		// });

		calc_jq_height_onchange("jqGrid_phar",true);
		$("#jqGrid_phar input[name='chgcode']").focus();
	},
	aftersavefunc: function (rowid, response, options) {
    	//state true maksudnyer ada isi, tak kosong
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	// $("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_phar.formatOff();
		mycurrency_np_phar.formatOff();

		if(parseInt($('#jqGrid_phar input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_phar.fail_msg_array.length>0){
			return false;
		}

		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[$('#lastrowid').val()];

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    ftxtdosage: $("#jqGrid_phar input[name='dosage']").val(),
				frequency: $("#jqGrid_phar input[name='frequency']").val(),
				addinstruction: $("#jqGrid_phar input[name='instruction']").val(),
				drugindicator: $("#jqGrid_phar input[name='drugindicator']").val(),
				// taxamount: $("#jqGrid_phar input[name='taxamount']").val(),
				// discamount: $("#jqGrid_phar input[name='discamount']").val(),
				// totamount: $("#jqGrid_phar input[name='totamount']").val(),
			});
		$("#jqGrid_phar").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		myfail_msg_phar.clear_fail;
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_phar')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_phar",true);
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_phar(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_phar.add_fail({
			id:'quantity',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_phar.del_fail({
			id:'quantity',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let st_idno = $("#jqGrid_phar #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<quantity && st_idno!=''){
		myfail_msg_phar.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}else{
		myfail_msg_phar.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}


	let unitprce = parseFloat($("#"+id_optid+"_unitprce").val());
	let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
	let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
	let rate =  parseFloat($("#"+id_optid+"_uom_rate").val());
	if(isNaN(rate)){
		rate = 0;
	}

	var amount = (unitprce*quantity);
	var discamount = ((unitprce*quantity) * billtypeperct / 100) + billtypeamt;

	let taxamount = amount * rate / 100;

	var totamount = amount - discamount + taxamount;

	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_discamount").val(discamount);
	$("#"+id_optid+"_totamount").val(totamount);
	$("#"+id_optid+"_amount").val(amount);
	
	var id="#jqGrid_phar #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

function calculate_conversion_factor(event) {
	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));

	let convfactor_uom = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val());
	let convfactor_pouom = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val());
	let qtydelivered = parseFloat($("#jqGrid2 #"+id_optid+"_qtydelivered").val());

	var balconv = convfactor_pouom*qtydelivered%convfactor_uom;
	if (balconv  == 0) {
		myfail_msg_phar.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Recv",
		});
	} else {
		myfail_msg_phar.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Recv",
		});
	}
}

var dialog_chgcode_phar = new ordialog(
	'chgcode_phar',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_phar input[name='chgcode']",errorField,
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
		urlParam: {
				url:"./SalesOrderDetail/table",
				action: 'get_itemcode_price',
				url_chk: './SalesOrderDetail/table',
				action_chk: 'get_itemcode_price_check',
				entrydate : moment().format('YYYY-MM-DD'),
				billtype : $('#billtype_def_code').val(),
				deptcode : $("#userdeptcode").val(),
				price : 'PRICE2',
				filterCol : ['cm.chggroup'],
				filterVal : [$('#ordcomtt_phar').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
			myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_phar.gridname);

			$("#jqGrid_phar #"+id_optid+"_chgcode").data('st_idno',data['st_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('invflag',data['invflag']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('avgcost',data['avgcost']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('convfactor',data['convfactor']);
			$('#'+dialog_chgcode_phar.gridname).data('fail_msg','');

			if(data.invflag == '1' && (data.pt_idno == '' || data.pt_idno == null)){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'noprod_'+id_optid;
				let fail_msg = 'Item not available in product master, please check';
				myfail_msg_phar.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');

				ordialog_buang_error_shj("#jqGrid_phar #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_phar #"+id_optid+"_taxcode").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_unitprce").val('');
				$("#jqGrid_phar #"+id_optid+"_cost_price").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			}else if(data.invflag == '1' && (data.st_idno == "" || data.st_idno == null)){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'nostock_'+id_optid;
				let fail_msg = 'Item not available in store dept '+$("#jqGrid_phar #"+id_optid+"_deptcode").parent().next('span.help-block').text()+', please check';	
				myfail_msg_phar.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');
				
				ordialog_buang_error_shj("#jqGrid_phar #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_phar #"+id_optid+"_taxcode").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_unitprce").val('');
				$("#jqGrid_phar #"+id_optid+"_cost_price").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val('');
			}else{
				myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
				myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

				$("#jqGrid_phar #"+id_optid+"_chgcode").val(data['chgcode']);
				$("#jqGrid_phar #"+id_optid+"_taxcode").val(data['taxcode']);
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val(data['rate']);
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val(data['convfactor']);
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val(data['uom']);
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val(data['uom']);
				$("#jqGrid_phar #"+id_optid+"_unitprce").val(data['price']);
				$("#jqGrid_phar #"+id_optid+"_cost_price").val(data['avgcost']);
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);

				// dialog_uomcode_phar.check(errorField);
				// dialog_uom_recv_phar.check(errorField);
				dialog_tax_phar.check(errorField);
				mycurrency_phar.formatOn();
			}

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phar input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_phar.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_phar.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_phar.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_chgcode_phar.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_phar.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
			dialog_chgcode_phar.urlParam.price = 'PRICE2';
			dialog_chgcode_phar.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
			dialog_chgcode_phar.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_phar.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
			dialog_chgcode_phar.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_phar.urlParam.filterVal = [$('#ordcomtt_phar').val()];
		},
		close: function(obj){
			$("#jqGrid_phar input[name='quantity']").focus().select();
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_phar.makedialog(false);

var dialog_uomcode_phar = new ordialog(
	'uom_phar',['material.uom AS u'],"#jqGrid_phar input[name='uom']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Charge Code',name:'chgcode',hidden:true},
			{label: 'Inventory',name:'invflag',hidden:true},
			{label: 'Quantity On Hand',hidden:true},
			{label: 'Price',name:'price',hidden:true},
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
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE'],
					url:"./SalesOrderDetail/table",
					url_chk:"./SalesOrderDetail/table",
					action: 'get_itemcode_uom',
					action_chk: 'get_itemcode_uom_check',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $("#userdeptcode").val(),
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#ordcomtt_phar').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
			myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_phar.gridname);

			$("#jqGrid_phar #"+id_optid+"_chgcode").data('st_idno',data['st_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('invflag',data['invflag']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('avgcost',data['avgcost']);
			$("#jqGrid_phar #"+id_optid+"_chgcode").data('convfactor',data['convfactor']);
			$('#'+dialog_uomcode_phar.gridname).data('fail_msg','');

			if(data.invflag == '1' && (data.pt_idno == '' || data.pt_idno == null)){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'noprod_'+id_optid;
				let fail_msg = 'Item not available in product master, please check';
				myfail_msg_phar.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');

				ordialog_buang_error_shj("#jqGrid_phar #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_phar #"+id_optid+"_taxcode").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_unitprce").val('');
				$("#jqGrid_phar #"+id_optid+"_cost_price").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			}else if(data.invflag == '1' && (data.st_idno == "" || data.st_idno == null)){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'nostock_'+id_optid;
				let fail_msg = 'Item not available in store dept '+$("#jqGrid_phar #"+id_optid+"_deptcode").parent().next('span.help-block').text()+', please check';	
				myfail_msg_phar.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');
				
				ordialog_buang_error_shj("#jqGrid_phar #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_phar #"+id_optid+"_taxcode").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val('');
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_unitprce").val('');
				$("#jqGrid_phar #"+id_optid+"_cost_price").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val('');
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val('');
			}else{
				myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
				myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

				$("#jqGrid_phar #"+id_optid+"_chgcode").val(data['chgcode']);
				$("#jqGrid_phar #"+id_optid+"_taxcode").val(data['taxcode']);
				$("#jqGrid_phar #"+id_optid+"_uom_rate").val(data['rate']);
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val(data['convfactor']);
				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
				// $("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_uom").val(data['uomcode']);
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val(data['uomcode']);
				$("#jqGrid_phar #"+id_optid+"_unitprce").val(data['price']);
				$("#jqGrid_phar #"+id_optid+"_cost_price").val(data['avgcost']);
				$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);

				// dialog_uomcode_phar.check(errorField);
				// dialog_uom_recv_phar.check(errorField);
				dialog_tax_phar.check(errorField);
				mycurrency_phar.formatOn();
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phar input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let id_optid = obj_.id_optid;

			dialog_uomcode_phar.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_phar.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_phar.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_phar.urlParam.action_chk = "get_itemcode_uom_check";
			dialog_uomcode_phar.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
			dialog_uomcode_phar.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
			dialog_uomcode_phar.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
			dialog_uomcode_phar.urlParam.price = 'PRICE2';
			dialog_uomcode_phar.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_phar.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_phar.urlParam.filterVal = [$('#ordcomtt_phar').val()];
		},
		close: function(){
			// $(dialog_uomcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uomcode_phar.makedialog(false);

var dialog_uom_recv_phar = new ordialog(
	'uom_recv_phar',['material.uom AS u'],"#jqGrid_phar input[name='uom_recv']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label:'convfactor',name:'convfactor',hidden:true},
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

			let data=selrowData('#'+dialog_uom_recv_phar.gridname);
			$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val(data.convfactor);
			$("#jqGrid_phar input#"+id_optid+"_uom_recv").val(data.uomcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phar input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_uom_recv_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_uomcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uom_recv_phar.makedialog(false);

var dialog_tax_phar = new ordialog(
	'taxcode_phar',['hisdb.taxmast'],"#jqGrid_phar input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_phar.gridname);
			$("#jqGrid_phar #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_phar input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax_phar.makedialog(false);

var dialog_deptcode_phar = new ordialog(
	'deptcode_phar',['sysdb.department'],"#jqGrid_phar input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_phar.gridname);
			dialog_chgcode_phar.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode_phar.makedialog(false);

var dialog_dosage_phar = new ordialog(
	'dosage_phar',['hisdb.dose'],"#jqGrid_phar input[name='dosage']",errorField,
	{	colModel:
		[
			{label:'Dosage Code', name:'dosecode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'dosedesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
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
			let data=selrowData('#'+dialog_dosage_phar.gridname);
			$(dialog_dosage_phar.textfield).val(data.dosedesc);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_dosage_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_dosage_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_dosage_phar.makedialog(false);

var dialog_frequency_phar = new ordialog(
	'freq_phar',['hisdb.freq'],"#jqGrid_phar input[name='frequency']",errorField,
	{	colModel:
		[
			{label:'Frequency Code', name:'freqcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'freqdesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
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
			let data=selrowData('#'+dialog_frequency_phar.gridname);
			$(dialog_frequency_phar.textfield).val(data.freqdesc);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_frequency_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_frequency_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_frequency_phar.makedialog(false);

var dialog_instruction_phar = new ordialog(
	'instruction_phar',['hisdb.instruction'],"#jqGrid_phar input[name='instruction']",errorField,
	{	colModel:
		[
			{label:'Dosage Code', name:'inscode', width:200, classes:'pointer', canSearch:true, or_search:true},
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
			let data=selrowData('#'+dialog_instruction_phar.gridname);
			$(dialog_instruction_phar.textfield).val(data.description);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_instruction_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_instruction_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_instruction_phar.makedialog(false);

var dialog_drugindicator_phar = new ordialog(
	'drugindicator_phar',['hisdb.drugindicator'],"#jqGrid_phar input[name='drugindicator']",errorField,
	{	colModel:
		[
			{label:'Dosage Code', name:'drugindcode', width:200, classes:'pointer', canSearch:true, or_search:true},
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
			let data=selrowData('#'+dialog_drugindicator_phar.gridname);
			$(dialog_drugindicator_phar.textfield).val(data.description);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_drugindicator_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_drugindicator_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_drugindicator_phar.makedialog(false);

function trxdateCustomEdit_phar(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_phar" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_phar(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='billtypeamt' id='`+id_optid+`_billtypeamt'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_uom_rate'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

	return $(myreturn);
}
function uomcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	if(val.trim() == ''){
		val = $('#userdeptcode').val();
	}
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_phar(val,opt){
	var myreturn = `<label>Dose</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Frequency</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Instruction</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Indicator</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_phar (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_phar(cellvalue, options, rowObject){
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
	return cellvalue;
}

function cust_rules_phar(value, name) {
	var temp=null;
	switch (name) {
		case 'Item Code': temp = $("#jqGrid_phar input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_phar input[name='uom']"); break;
		case 'PO UOM': 
			temp = $("#jqGrid_phar input[name='pouom']"); 
			var text = $( temp ).parent().siblings( ".help-block" ).text();
			if(text == 'Invalid Code'){
				return [false,"Please enter valid "+name+" value"];
			}

			break;
		case 'Price Code': temp = $("#jqGrid_phar input[name='pricecode']"); break;
		case 'Tax Code': temp = $("#jqGrid_phar input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_phar input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}