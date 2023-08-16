

var urlParam_ordcom={
	action:'ordcom_table',
	url:'./ordcom/table',
	mrn:'',
	episno:''
};
var fdl_ordcom = new faster_detail_load();
var myfail_msg = new fail_msg_func('div#fail_msg_ordcom');
var mycurrency2 =new currencymode([]);
var mycurrency_np =new currencymode([],true);
var errorField = [];

$(document).ready(function(){
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				show_errors(errorField,'#formdata');
				return [{
					element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message: ' '
				}]
			}
		},
	};

	$("#jqGrid_ordcom_panel").on("shown.bs.collapse", function(){
		SmoothScrollTo("#jqGrid_ordcom_panel", 500)
		hideatdialogForm(false);
		refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		$("#jqGrid_ordcom").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
	});

	$("#jqGrid_ordcom").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit,
					custom_value: galGridCustomValue
				},
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Unit Price', name: 'unitprce', width: 100, classes: 'wrap txnum', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{
				label: 'Cost Price', name: 'cost_price', width: 100, classes: 'wrap txnum', align: 'right',
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
			// { label: 'Discount Amount', name: 'discamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Tax Amount', name: 'taxamount', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Total Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},{
				label: 'Dose', name: 'remark', width: 200, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules },
				edittype: 'custom', editoptions:
				{
					custom_element: remarkCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
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
		pager: "#jqGrid_ordcom_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_ordcom");
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg.clear_fail;
		},
		afterShowForm: function (rowid) {
		}
    });
	
	$("#jqGrid_ordcom").inlineNav('#jqGrid_ordcom_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_ordcom
		},
		editParams: myEditOptions_ordcom,
			
	}).jqGrid('navButtonAdd', "#jqGrid_ordcom_pager", {	
		id: "jqGrid_ordcom_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_ordcom").jqGrid('getGridParam', 'selrow');	
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
			// 				// 	// mrn: selrowData('#jqGrid_ordcom').mrn,	
			// 				// }	
			// 				// $.post( "./ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){	
			// 				// }).fail(function (data) {	
			// 				// 	$('#p_error').text(data.responseText);	
			// 				// }).done(function (data) {	
			// 				// 	refreshGrid("#jqGrid_ordcom", urlParam_ordcom);	
			// 				// });	
			// 			}else{	
			// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
			// 			}	
			// 		}	
			// 	});	
			// }	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_ordcom_pager", {	
		id: "jqGrid_ordcom_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {	
			oper = 'add_ordcom'	
			refreshGrid("#jqGrid_ordcom", urlParam_ordcom);	
		},	
	});

});
	
var myEditOptions_ordcom = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		myfail_msg.clear_fail;
		$("#jqGrid_ordcom input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_ordcom_pagerRefresh,#jqGrid_ordcom_pagerDelete").hide();

		dialog_deptcode.on();
		dialog_chgcode.on();
		dialog_uomcode.on();
		dialog_uom_recv.on();
		dialog_tax.on();
		dialog_dosage.on();
		dialog_frequency.on();
		dialog_instruction.on();
		dialog_drugindicator.on();

		unsaved = false;
		mycurrency2.array.length = 0;
		mycurrency_np.array.length = 0;
		Array.prototype.push.apply(mycurrency2.array, ["#jqGrid_ordcom input[name='unitprce']","#jqGrid_ordcom input[name='billtypeperct']","#jqGrid_ordcom input[name='billtypeamt']","#jqGrid_ordcom input[name='totamount']","#jqGrid_ordcom input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid_ordcom input[name='qtyonhand']","#jqGrid_ordcom input[name='quantity']"]);
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_ordcom input[name='unitprce'],#jqGrid_ordcom input[name='quantity']").on('keyup',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
		$("#jqGrid_ordcom input[name='unitprce']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

		// $("#jqGrid_ordcom input[name='qtyonhand']").keydown(function(e) {//when click tab at totamount, auto save
		// 	var code = e.keyCode || e.which;
		// 	if (code == '9'){
		// 		delay(function(){
		// 			$('#jqGrid_ordcom_ilsave').click();
		// 			addmore_jqGrid_ordcom.state = true;
		// 		}, 500 );
		// 	}
		// });

		calc_jq_height_onchange("jqGrid_ordcom");
		$("#jqGrid_ordcom input[name='chgcode']").focus();
	},
	aftersavefunc: function (rowid, response, options) {
    	//state true maksudnyer ada isi, tak kosong
		refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
    	$("#jqGrid_ordcom_pagerRefresh,#jqGrid_ordcom_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
    	$("#jqGrid_ordcom_pagerRefresh,#jqGrid_ordcom_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency2.formatOff();
		mycurrency_np.formatOff();

		if(parseInt($('#jqGrid_ordcom input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg.fail_msg_array.length>0){
			return false;
		}

		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[$('#lastrowid').val()];

		console.log($("#jqGrid_ordcom input[name='drugindicator']"));
		console.log($("#jqGrid_ordcom input[name='drugindicator']").val());
		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry_pharmacy',
				mrn: rowdata.MRN,
				episode: rowdata.Episno,
			    dosage: $("#jqGrid_ordcom input[name='dosage']").val(),
				frequency: $("#jqGrid_ordcom input[name='frequency']").val(),
				instruction: $("#jqGrid_ordcom input[name='instruction']").val(),
				drugindicator: $("#jqGrid_ordcom input[name='drugindicator']").val(),
				taxamt: $("#jqGrid_ordcom input[name='taxamt']").val(),
				discamt: $("#jqGrid_ordcom input[name='discamt']").val(),
				totamount: $("#jqGrid_ordcom input[name='totamount']").val(),
				// discamt: $("#jqGrid_ordcom input[name='discamt']").val(),
			});
		$("#jqGrid_ordcom").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_ordcom_pagerRefresh,#jqGrid_ordcom_pagerDelete").show();
		myfail_msg.clear_fail;
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_ordcom')[0]);
		// }, 500 );
		hideatdialogForm(false);
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

function calculate_line_totgst_and_totamt(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});
	// mycurrency_np.formatOff();

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg.add_fail({
			id:'quantity',
			textfld:"#jqGrid_ordcom #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg.del_fail({
			id:'quantity',
			textfld:"#jqGrid_ordcom #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let st_idno = $("#jqGrid_ordcom #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<quantity && st_idno!=''){
		myfail_msg.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_ordcom #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}else{
		myfail_msg.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_ordcom #"+id_optid+"_quantity",
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
	var discamt = ((unitprce*quantity) * billtypeperct / 100) + billtypeamt;

	let taxamt = amount * rate / 100;

	var totamount = amount - discamt + taxamt;

	$("#"+id_optid+"_taxamt").val(taxamt);
	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_totamount").val(totamount);
	$("#"+id_optid+"_amount").val(amount);
	
	var id="#jqGrid_ordcom #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
	// event.data.currency.formatOn();//change format to currency on each calculation
	// mycurrency.formatOn();
	// mycurrency_np.formatOn();

	// fixPositionsOfFrozenDivs.call($('#jqGrid_ordcom')[0]);
}

var dialog_chgcode = new ordialog(
	'chgcode_ordcom',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_ordcom input[name='chgcode']",errorField,
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
			
		],
		urlParam: {
				url:"./SalesOrderDetail/table",
				action: 'get_itemcode_price',
				url_chk: './SalesOrderDetail/table',
				action_chk: 'get_itemcode_price_check',
				entrydate : moment().format('YYYY-MM-DD'),
				billtype : $('#billtype_def_code').val(),
				deptcode : $("#userdeptcode").val(),
				pricece : 'PRICE2'
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			myfail_msg.del_fail({id:'noprod_'+id_optid});
			myfail_msg.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_chgcode.gridname);

			$("#jqGrid_ordcom #"+id_optid+"_chgcode").data('st_idno',data['st_idno']);
			$("#jqGrid_ordcom #"+id_optid+"_chgcode").data('invflag',data['invflag']);
			$("#jqGrid_ordcom #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_ordcom #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_ordcom #"+id_optid+"_chgcode").data('avgcost',data['avgcost']);
			$('#'+dialog_chgcode.gridname).data('fail_msg','');

			if(data.invflag == '1' && data.pt_idno == ''){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'noprod_'+id_optid;
				let fail_msg = 'Item not available in product master, please check';
				myfail_msg.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');

				ordialog_buang_error_shj("#jqGrid_ordcom #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_ordcom #"+id_optid+"_taxcode").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_ordcom #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_ordcom #"+id_optid+"_quantity").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_ordcom #"+id_optid+"_unitprce").val('');
				$("#jqGrid_ordcom #"+id_optid+"_cost_price").val('');
				$("#jqGrid_ordcom #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_ordcom #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			}else if(data.invflag == '1' && data.st_idno == ""){
				myerrorIt_only2('input#'+id_optid+'_chgcode',true);
				let name = 'nostock_'+id_optid;
				let fail_msg = 'Item not available in store dept '+$("#jqGrid_ordcom #"+id_optid+"_deptcode").parent().next('span.help-block').text()+', please check';	
				myfail_msg.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chgcode').text('');
				
				ordialog_buang_error_shj("#jqGrid_ordcom #"+id_optid+"_taxcode",errorField);

				$("#jqGrid_ordcom #"+id_optid+"_taxcode").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom_rate").val('');
				$("#jqGrid_ordcom #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_ordcom #"+id_optid+"_quantity").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom_recv").val('');
				$("#jqGrid_ordcom #"+id_optid+"_unitprce").val('');
				$("#jqGrid_ordcom #"+id_optid+"_cost_price").val('');
				$("#jqGrid_ordcom #"+id_optid+"_billtypeperct").val('');
				$("#jqGrid_ordcom #"+id_optid+"_billtypeamt").val('');
			}else{
				myfail_msg.del_fail({id:'noprod_'+id_optid});
				myfail_msg.del_fail({id:'nostock_'+id_optid});

				$("#jqGrid_ordcom #"+id_optid+"_chgcode").val(data['chgcode']);
				$("#jqGrid_ordcom #"+id_optid+"_taxcode").val(data['taxcode']);
				$("#jqGrid_ordcom #"+id_optid+"_uom_rate").val(data['rate']);
				$("#jqGrid_ordcom #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
				$("#jqGrid_ordcom #"+id_optid+"_quantity").val('');
				$("#jqGrid_ordcom #"+id_optid+"_uom").val(data['uom']);
				$("#jqGrid_ordcom #"+id_optid+"_uom_recv").val(data['uom']);
				$("#jqGrid_ordcom #"+id_optid+"_unitprce").val(data['price']);
				$("#jqGrid_ordcom #"+id_optid+"_cost_price").val(data['avgcost']);
				$("#jqGrid_ordcom #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid_ordcom #"+id_optid+"_billtypeamt").val(data['billty_amount']);

				// dialog_uomcode.check(errorField);
				// dialog_uom_recv.check(errorField);
				dialog_tax.check(errorField);
				mycurrency2.formatOn();
			}

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_ordcom input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode.urlParam.action = 'get_itemcode_price';
			dialog_chgcode.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_chgcode.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode.urlParam.deptcode = $("#jqGrid_ordcom input[name='deptcode']").val();
			dialog_chgcode.urlParam.price = 'PRICE2';
			dialog_chgcode.urlParam.entrydate = moment().format('YYYY-MM-DD');
			dialog_chgcode.urlParam.billtype = $('#billtype_def_code').val();

		},
		close: function(obj){
			$("#jqGrid_ordcom input[name='quantity']").focus().select();
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode.makedialog(false);

var dialog_uomcode = new ordialog(
	'uom',['material.uom AS u'],"#jqGrid_ordcom input[name='uom']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE'],
					url:"./SalesOrderDetail/table",
					action: 'get_itemcode_uom',
					entrydate : $('#db_entrydate').val()
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uomcode.gridname);
			$("#jqGrid_ordcom input#"+id_optid+"_uom").val(data.uomcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_ordcom input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let id_optid = obj_.id_optid;

			dialog_uomcode.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode.urlParam.action_chk = "get_itemcode_uom_check";
			dialog_uomcode.urlParam.filterCol = ['chgcode','price'];
			dialog_uomcode.urlParam.filterVal = [$("#jqGrid_ordcom #"+id_optid+"_chgcode").val(),$('#pricebilltype').val()];
			dialog_uomcode.urlParam.entrydate = $('#db_entrydate').val();
			dialog_uomcode.urlParam.chgcode = $("#jqGrid_ordcom #"+id_optid+"_chgcode").val();
			dialog_uomcode.urlParam.deptcode = $("#jqGrid_ordcom #"+id_optid+"_deptcode").val();
			dialog_uomcode.urlParam.filterCol=['compcode','recstatus'];
			dialog_uomcode.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uomcode.makedialog(false);

var dialog_uom_recv = new ordialog(
	'uom_recv',['material.uom AS u'],"#jqGrid_ordcom input[name='uom_recv']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
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

			let data=selrowData('#'+dialog_uom_recv.gridname);
			$("#jqGrid_ordcom input#"+id_optid+"_uom_recv").val(data.uomcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_ordcom input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv.urlParam.filterCol=['compcode','recstatus'];
			dialog_uom_recv.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uom_recv.makedialog(false);

var dialog_tax = new ordialog(
	'taxcode',['hisdb.taxmast'],"#jqGrid_ordcom input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax.gridname);
			$("#jqGrid_ordcom #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_ordcom input#"+id_optid+"_taxcode").val(data.taxcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_ordcom input[name='taxamt']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){

			dialog_tax.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax.makedialog(false);

var dialog_deptcode = new ordialog(
	'deptcode',['sysdb.department'],"#jqGrid_ordcom input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode.gridname);
			dialog_chgcode.urlParam.deptcode = data.deptcode;
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_ordcom input[name='taxamt']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){

			dialog_deptcode.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode.makedialog(false);

var dialog_dosage = new ordialog(
	'dosage_ordcom',['hisdb.dose'],"#jqGrid_ordcom input[name='dosage']",errorField,
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
			let data=selrowData('#'+dialog_dosage.gridname);
			$(dialog_dosage.textfield).val(data.dosedesc);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_dosage.urlParam.filterCol=['compcode','recstatus'];
			dialog_dosage.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_dosage.makedialog(false);

var dialog_frequency = new ordialog(
	'freq_ordcom',['hisdb.freq'],"#jqGrid_ordcom input[name='frequency']",errorField,
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
			let data=selrowData('#'+dialog_frequency.gridname);
			$(dialog_frequency.textfield).val(data.freqdesc);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_frequency.urlParam.filterCol=['compcode','recstatus'];
			dialog_frequency.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_frequency.makedialog(false);

var dialog_instruction = new ordialog(
	'instruction_ordcom',['hisdb.instruction'],"#jqGrid_ordcom input[name='instruction']",errorField,
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
			let data=selrowData('#'+dialog_instruction.gridname);
			$(dialog_instruction.textfield).val(data.description);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_instruction.urlParam.filterCol=['compcode','recstatus'];
			dialog_instruction.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_instruction.makedialog(false);

var dialog_drugindicator = new ordialog(
	'drugindicator_ordcom',['hisdb.drugindicator'],"#jqGrid_ordcom input[name='drugindicator']",errorField,
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
			let data=selrowData('#'+dialog_drugindicator.gridname);
			$(dialog_drugindicator.textfield).val(data.description);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		open:function(obj_){
			dialog_drugindicator.urlParam.filterCol=['compcode','recstatus'];
			dialog_drugindicator.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_drugindicator.makedialog(false);

//screen current patient//
function populate_ordcom_currpt(obj){
	//panel header	
	$('#name_show_ordcom').text(if_none(obj.Name));
	$('#mrn_show_ordcom').text(if_none(("0000000" + obj.MRN).slice(-7)));
	$('#sex_show_ordcom').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_ordcom').text(dob_chg(obj.DOB));
	$('#age_show_ordcom').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_ordcom').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_ordcom').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_ordcom').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_ordcom').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_ordcom').text(if_none(obj.areaDesc).toUpperCase());

	//formordcom	
	$('#mrn_ordcom').val(obj.MRN);	
	$("#episno_ordcom").val(obj.Episno);
	urlParam_ordcom.mrn = obj.MRN;
	urlParam_ordcom.episno = obj.Episno;
	
}

function trxdateCustomEdit(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_ordcom" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='billtypeamt' id='`+id_optid+`_billtypeamt'>`;
	myreturn += `<input type='hidden' name='discamt' id='`+id_optid+`_discamt'>`;
	myreturn += `<input type='hidden' name='taxamt' id='`+id_optid+`_taxamt'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_uom_rate'></div>`;

	return $(myreturn);
}
function uomcodeCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	if(val.trim() == ''){
		val = $('#userdeptcode').val();
	}
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit(val,opt){
	var myreturn = `<label>Dose</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Frequency</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Instruction</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Indicator</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function hideatdialogForm(hide,saveallrow){	
	// if(saveallrow == 'saveallrow'){	
	// 	$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll,#saveDetailLabel").hide();	
	// 	$("#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_pagerCancelAll").show();	
	// }else if(hide){	
	// 	$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll,#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_pagerCancelAll").hide();	
	// 	$("#saveDetailLabel").show();	
	// }else{	
	// 	$("#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll").show();	
	// 	$("#saveDetailLabel,#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_iledit,#jqGrid_ordcom_pagerCancelAll").hide();	
	// }	
}

function showdetail(cellvalue, options, rowObject){
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

function cust_rules(value, name) {
	var temp=null;
	switch (name) {
		case 'Item Code': temp = $("#jqGrid_ordcom input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_ordcom input[name='uom']"); break;
		case 'PO UOM': 
			temp = $("#jqGrid_ordcom input[name='pouom']"); 
			var text = $( temp ).parent().siblings( ".help-block" ).text();
			if(text == 'Invalid Code'){
				return [false,"Please enter valid "+name+" value"];
			}

			break;
		case 'Price Code': temp = $("#jqGrid_ordcom input[name='pricecode']"); break;
		case 'Tax Code': temp = $("#jqGrid_ordcom input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_ordcom input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}

function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue == '1') {
		return `<span class="fa fa-check"></span>`;
	}else{
		return '';
	}
}

function unformatstatus_tick2(cellvalue, option, rowObject) {
	if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
		return '1';
	}else{
		return '0';
	}
}

function fail_msg_func(fail_msg_div=null){
	this.fail_msg_div = (fail_msg_div!=null)?fail_msg_div:'div#fail_msg';
	this.fail_msg_array=[];
	this.add_fail=function(fail_msg){
		let found=false;
		this.fail_msg_array.forEach(function(e,i){
			if(e.id == fail_msg.id){
				e.msg=fail_msg.msg;
				found=true;
			}
		});
		if(!found){
			this.fail_msg_array.push(fail_msg);
		}
		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				return false;
			}
			return true;
		});

		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.fail_msg_array = new_msg_array;
		this.pop_fail();
	}
	this.clear_fail=function(){
		this.fail_msg_array=[];
		this.pop_fail();
	}
	this.pop_fail=function(){
		var self=this;
		$(self.fail_msg_div).html('');
		this.fail_msg_array.forEach(function(e,i){
			$(self.fail_msg_div).append("<li>"+e.msg+"</li>");
		});
	}
}