
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
			},{
				label: 'UOM Code', name: 'uom', hidden:true
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
		pager: "#jqGrid_lab_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_lab");
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_lab.clear_fail;
		},
		afterShowForm: function (rowid) {
		}
    });
	
	$("#jqGrid_lab").inlineNav('#jqGrid_lab_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_lab
		},
		editParams: myEditOptions_lab,
			
	}).jqGrid('navButtonAdd', "#jqGrid_lab_pager", {	
		id: "jqGrid_lab_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_lab").jqGrid('getGridParam', 'selrow');	
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
			// 				// 	// mrn: selrowData('#jqGrid_lab').mrn,	
			// 				// }	
			// 				// $.post( "./ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){	
			// 				// }).fail(function (data) {	
			// 				// 	$('#p_error').text(data.responseText);	
			// 				// }).done(function (data) {	
			// 				// 	refreshGrid("#jqGrid_lab", urlParam_lab);	
			// 				// });	
			// 			}else{	
			// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
			// 			}	
			// 		}	
			// 	});	
			// }	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_lab_pager", {	
		id: "jqGrid_lab_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_lab", urlParam_lab);	
		},	
	});

});
	
var myEditOptions_lab = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		myfail_msg_lab.clear_fail;
		$("#jqGrid_lab input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").hide();

		dialog_deptcode_lab.on();
		dialog_chgcode_lab.on();
		dialog_tax_lab.on();
		mycurrency_lab.array.length = 0;
		mycurrency_np_lab.array.length = 0;
		Array.prototype.push.apply(mycurrency_lab.array, ["#jqGrid_lab input[name='unitprce']","#jqGrid_lab input[name='billtypeperct']","#jqGrid_lab input[name='billtypeamt']","#jqGrid_lab input[name='totamount']","#jqGrid_lab input[name='amount']","#jqGrid_lab input[name='taxamount']","#jqGrid_lab input[name='discamount']"]);
		Array.prototype.push.apply(mycurrency_np_lab.array, ["#jqGrid_lab input[name='qtyonhand']","#jqGrid_lab input[name='quantity']"]);
		
		mycurrency_lab.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_lab.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_lab input[name='unitprce'],#jqGrid_lab input[name='quantity']").on('keyup',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);
		$("#jqGrid_lab input[name='unitprce'],#jqGrid_lab input[name='quantity']").on('blur',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_line_totgst_and_totamt_lab);
		// $("#jqGrid_lab input[name='unitprce'],#jqGrid_lab input[name='quantity']").on('blur',{currency: [mycurrency_lab,mycurrency_np_lab]},calculate_conversion_factor);

		// $("#jqGrid_lab input[name='qtyonhand']").keydown(function(e) {//when click tab at totamount, auto save
		// 	var code = e.keyCode || e.which;
		// 	if (code == '9'){
		// 		delay(function(){
		// 			$('#jqGrid_lab_ilsave').click();
		// 			addmore_jqGrid_lab.state = true;
		// 		}, 500 );
		// 	}
		// });

		calc_jq_height_onchange("jqGrid_lab",true);
		$("#jqGrid_lab input[name='chgcode']").focus();
	},
	aftersavefunc: function (rowid, response, options) {
    	//state true maksudnyer ada isi, tak kosong
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

		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[$('#lastrowid').val()];

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    uom: $("#jqGrid_lab input[name='uom']").val()
				// taxamount: $("#jqGrid_lab input[name='taxamount']").val(),
				// discamount: $("#jqGrid_lab input[name='discamount']").val(),
				// totamount: $("#jqGrid_lab input[name='totamount']").val(),
			});
		$("#jqGrid_lab").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_lab_pagerRefresh,#jqGrid_lab_pagerDelete").show();
		myfail_msg_lab.clear_fail;
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_lab')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_lab",true);
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

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let st_idno = $("#jqGrid_lab #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<quantity && st_idno!=''){
		myfail_msg_lab.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}else{
		myfail_msg_lab.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_lab #"+id_optid+"_quantity",
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
	
	var id="#jqGrid_lab #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_chgcode_lab = new ordialog(
	'chgcode_lab',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_lab input[name='chgcode']",errorField,
	{	colModel:
		[
			{label: 'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label: 'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Inventory',name:'invflag',width:100,hidden:true},
			{label: 'UOM',name:'uom',width:100,classes:'pointer',},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100,classes:'pointer',hidden:true},
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

			let data=selrowData('#'+dialog_chgcode_lab.gridname);

			$("#jqGrid_lab #"+id_optid+"_chgcode").data('st_idno',data['st_idno']);
			$("#jqGrid_lab #"+id_optid+"_chgcode").data('invflag',data['invflag']);
			$("#jqGrid_lab #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_lab #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_lab #"+id_optid+"_chgcode").data('avgcost',data['avgcost']);
			$("#jqGrid_lab #"+id_optid+"_chgcode").data('convfactor',data['convfactor']);
			$('#'+dialog_chgcode_lab.gridname).data('fail_msg','');

			$("#jqGrid_lab #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_lab #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_lab #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_lab #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_lab #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
			$("#jqGrid_lab #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
			$("#jqGrid_lab #"+id_optid+"_quantity").val('');
			$("#jqGrid_lab #"+id_optid+"_uom").val(data['uom']);
			$("#jqGrid_lab #"+id_optid+"_uom_recv").val(data['uom']);
			$("#jqGrid_lab #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_lab #"+id_optid+"_cost_price").val(data['avgcost']);
			$("#jqGrid_lab #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_lab #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			// dialog_uomcode_lab.check(errorField);
			// dialog_uom_recv_lab.check(errorField);
			dialog_tax_lab.check(errorField);
			mycurrency_lab.formatOn();

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
			dialog_chgcode_lab.urlParam.url_chk = "./SalesOrderDetail/table";
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
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_lab.makedialog(false);

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
			$("#jqGrid_lab #"+id_optid+"_uom_rate").val(data['rate']);
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
		open:function(obj_){

			dialog_tax_lab.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_lab.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_lab.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax_lab.makedialog(false);

var dialog_deptcode_lab = new ordialog(
	'deptcode_lab',['sysdb.department'],"#jqGrid_lab input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_lab.gridname);
			dialog_chgcode_lab.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_lab.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode_lab.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_lab.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode_lab.makedialog(false);

function trxdateCustomEdit_lab(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_lab" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_lab(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='billtypeamt' id='`+id_optid+`_billtypeamt'>`;
	myreturn += `<input type='hidden' name='uom' id='`+id_optid+`_uom'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_uom_rate'></div>`;

	return $(myreturn);
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
	if(val.trim() == ''){
		val = $('#userdeptcode').val();
	}
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_lab(val,opt){
	var myreturn = `<label>Dose</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Frequency</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Instruction</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Indicator</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_lab" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

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
	return cellvalue;
}

function cust_rules_lab(value, name) {
	var temp=null;
	switch (name) {
		case 'Item Code': temp = $("#jqGrid_lab input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_lab input[name='uom']"); break;
		case 'PO UOM': 
			temp = $("#jqGrid_lab input[name='pouom']"); 
			var text = $( temp ).parent().siblings( ".help-block" ).text();
			if(text == 'Invalid Code'){
				return [false,"Please enter valid "+name+" value"];
			}

			break;
		case 'Price Code': temp = $("#jqGrid_lab input[name='pricecode']"); break;
		case 'Tax Code': temp = $("#jqGrid_lab input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_lab input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}