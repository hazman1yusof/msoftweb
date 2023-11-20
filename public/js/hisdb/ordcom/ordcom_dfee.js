
var urlParam_dfee={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#ordcomtt_dfee').val(),
	mrn:'',
	episno:''
};
var myfail_msg_dfee = new fail_msg_func('div#fail_msg_dfee');
var mycurrency_dfee =new currencymode([]);
var mycurrency_np_dfee =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_dfee").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Date', name: 'trxdate', width: 100, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_dfee },
				formatter: showdetail_dfee,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_dfee },
				formatter: showdetail_dfee,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},{
				label: 'Doctor', name: 'brandname', width: 100, classes: 'wrap', editable: true,
				editrules: { required: false },editoptions:{readonly: "readonly"}
			},{
				label: 'UOM Code', name: 'uom', hidden:true
			},{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_dfee },
				formatter: showdetail_dfee,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},
			{
				label: 'Unit Price', name: 'unitprce', width: 90, classes: 'wrap txnum', align: 'right',
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
			{ label: 'Total Amount <br>Before Tax', name: 'amount', width: 90, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'MMA Code', name: 'mmacode', width: 90, align: 'left', classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_dfee },
				formatter: showdetail_dfee,
				edittype: 'custom', editoptions:
				{
					custom_element: mmacodeCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Discount<br>Amount', name: 'discamount', width: 90, align: 'right', classes: 'wrap txnum', editable:true,
			// 	formatter:'currency',formatoptions:{thousandsSeparator: ",",},
			// 	editrules:{required: true},editoptions:{readonly: "readonly"},
			// },
			{ label: 'Tax<br>Amount', name: 'taxamount', width: 90, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Total<br>Amount', name: 'totamount', width: 90, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},{
				label: 'Note', name: 'remarks', hidden: true
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
		pager: "#jqGrid_dfee_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_dfee");
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_dfee.clear_fail;
		},
		afterShowForm: function (rowid) {
		},
		ondblClickRow: function(rowId) {
			$('#jqGrid_dfee_iledit').click();
		}
    });
	
	$("#jqGrid_dfee").inlineNav('#jqGrid_dfee_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_dfee
		},
		editParams: myEditOptions_dfee,
			
	}).jqGrid('navButtonAdd', "#jqGrid_dfee_pager", {	
		id: "jqGrid_dfee_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_dfee").jqGrid('getGridParam', 'selrow');	
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
			// 				// 	// mrn: selrowData('#jqGrid_dfee').mrn,	
			// 				// }	
			// 				// $.post( "./ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){	
			// 				// }).fail(function (data) {	
			// 				// 	$('#p_error').text(data.responseText);	
			// 				// }).done(function (data) {	
			// 				// 	refreshGrid("#jqGrid_dfee", urlParam_dfee);	
			// 				// });	
			// 			}else{	
			// 				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
			// 			}	
			// 		}	
			// 	});	
			// }	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_dfee_pager", {	
		id: "jqGrid_dfee_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_dfee", urlParam_dfee);	
		},	
	});

});
	
var myEditOptions_dfee = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		myfail_msg_dfee.clear_fail;
		$("#jqGrid_dfee input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").hide();

		dialog_deptcode_dfee.on();
		dialog_chgcode_dfee.on();
		dialog_tax_dfee.on();
		dialog_mmacode_dfee.on();
		mycurrency_dfee.array.length = 0;
		mycurrency_np_dfee.array.length = 0;
		Array.prototype.push.apply(mycurrency_dfee.array, ["#jqGrid_dfee input[name='unitprce']","#jqGrid_dfee input[name='billtypeperct']","#jqGrid_dfee input[name='billtypeamt']","#jqGrid_dfee input[name='totamount']","#jqGrid_dfee input[name='amount']","#jqGrid_dfee input[name='taxamount']","#jqGrid_dfee input[name='discamount']"]);
		Array.prototype.push.apply(mycurrency_np_dfee.array, ["#jqGrid_dfee input[name='qtyonhand']","#jqGrid_dfee input[name='quantity']"]);
		
		mycurrency_dfee.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_dfee.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_dfee input[name='unitprce'],#jqGrid_dfee input[name='quantity']").on('keyup',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);
		$("#jqGrid_dfee input[name='unitprce'],#jqGrid_dfee input[name='quantity']").on('blur',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);
		// $("#jqGrid_dfee input[name='unitprce'],#jqGrid_dfee input[name='quantity']").on('blur',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_conversion_factor);

		// $("#jqGrid_dfee input[name='qtyonhand']").keydown(function(e) {//when click tab at totamount, auto save
		// 	var code = e.keyCode || e.which;
		// 	if (code == '9'){
		// 		delay(function(){
		// 			$('#jqGrid_dfee_ilsave').click();
		// 			addmore_jqGrid_dfee.state = true;
		// 		}, 500 );
		// 	}
		// });

		calc_jq_height_onchange("jqGrid_dfee",true);
		$("#jqGrid_dfee input[name='chgcode']").focus();
		dialog_deptcode_dfee.check(errorField);
	},
	aftersavefunc: function (rowid, response, options) {
    	//state true maksudnyer ada isi, tak kosong
		refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
    	// $("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_dfee.formatOff();
		mycurrency_np_dfee.formatOff();

		if(parseInt($('#jqGrid_dfee input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_dfee.fail_msg_array.length>0){
			return false;
		}

		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[$('#lastrowid').val()];

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    uom: $("#jqGrid_dfee input[name='uom']").val()
				// taxamount: $("#jqGrid_dfee input[name='taxamount']").val(),
				// discamount: $("#jqGrid_dfee input[name='discamount']").val(),
				// totamount: $("#jqGrid_dfee input[name='totamount']").val(),
			});
		$("#jqGrid_dfee").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
		myfail_msg_dfee.clear_fail;
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_dfee')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_dfee",true);
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_dfee(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity<=0 || quantity==''){
		myfail_msg_dfee.add_fail({
			id:'quantity',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_dfee.del_fail({
			id:'quantity',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let st_idno = $("#jqGrid_dfee #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<quantity && st_idno!=''){
		myfail_msg_dfee.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}else{
		myfail_msg_dfee.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Quantity must be greater than quantity on hand",
		});
	}


	let unitprce = parseFloat($("#"+id_optid+"_unitprce").val());
	// let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
	let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
	let rate =  parseFloat($("#"+id_optid+"_uom_rate").val());
	if(isNaN(rate)){
		rate = 0;
	}

	var amount = (unitprce*quantity);
	// var discamount = ((unitprce*quantity) * billtypeperct / 100) + billtypeamt;

	let taxamount = amount * rate / 100;

	var totamount = amount - discamount + taxamount;

	$("#"+id_optid+"_taxamount").val(taxamount);
	// $("#"+id_optid+"_discamount").val(discamount);
	$("#"+id_optid+"_totamount").val(totamount);
	$("#"+id_optid+"_amount").val(amount);
	
	var id="#jqGrid_dfee #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_chgcode_dfee = new ordialog(
	'chgcode_dfee',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_dfee input[name='chgcode']",errorField,
	{	colModel:
		[
			{label: 'Charge Code',name:'chgcode',width:100,classes:'pointer',canSearch:true,or_search:true},
			{label: 'Description',name:'description',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Inventory',name:'invflag',width:100,hidden:true},
			{label: 'brandname',name:'brandname',width:200},
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
			{label: 'overwrite',name:'overwrite',hidden:true},
			{label: 'convfactor',name:'convfactor',hidden:true},
			{label: 'constype',name:'constype',hidden:true},
			{label: 'revcode',name:'revcode',hidden:true},
			
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
				filterVal : [$('#ordcomtt_dfee').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			myfail_msg_dfee.del_fail({id:'noprod_'+id_optid});
			myfail_msg_dfee.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_dfee.gridname);

			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('st_idno',data['st_idno']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('invflag',data['invflag']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('pt_idno',data['pt_idno']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('avgcost',data['avgcost']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('constype',data['constype']);
			$("#jqGrid_dfee #"+id_optid+"_chgcode").data('convfactor',data['convfactor']);
			$('#'+dialog_chgcode_dfee.gridname).data('fail_msg','');
			$("#jqGrid_dfee #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_dfee #"+id_optid+"_brandname").val(data['brandname']);
			$("#jqGrid_dfee #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_dfee #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_dfee #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
			$("#jqGrid_dfee #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
			$("#jqGrid_dfee #"+id_optid+"_quantity").val('');
			$("#jqGrid_dfee #"+id_optid+"_uom").val(data['uom']);
			$("#jqGrid_dfee #"+id_optid+"_uom_recv").val(data['uom']);
			$("#jqGrid_dfee #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_dfee #"+id_optid+"_cost_price").val(data['avgcost']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			if(data['overwrite'] == '1'){
				$("#jqGrid_dfee #"+id_optid+"_unitprce").prop('readonly',false);
			}else{
				$("#jqGrid_dfee #"+id_optid+"_unitprce").prop('readonly',true);
			}

			if(data['revcode'].trim() != ''){
				$("#jqGrid_dfee #"+id_optid+"_deptcode").val(data['revcode']);

				dialog_deptcode_dfee.check(errorField);
			}

			// dialog_uomcode_dfee.check(errorField);
			// dialog_uom_recv_dfee.check(errorField);
			dialog_tax_dfee.check(errorField);
			mycurrency_dfee.formatOn();

		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_dfee input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_dfee.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_dfee.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_dfee.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_chgcode_dfee.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_dfee.urlParam.deptcode = $("#jqGrid_dfee input[name='deptcode']").val();
			dialog_chgcode_dfee.urlParam.price = 'PRICE2';
			dialog_chgcode_dfee.urlParam.entrydate = $("#jqGrid_dfee input[name='trxdate']").val();
			dialog_chgcode_dfee.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_dfee.urlParam.chgcode = $("#jqGrid_dfee input[name='chgcode']").val();
			dialog_chgcode_dfee.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_dfee.urlParam.filterVal = [$('#ordcomtt_dfee').val()];
		},
		close: function(obj_){
			let id_optid = obj_.id_optid;
			if($("#jqGrid_dfee #"+id_optid+"_unitprce").prop('readonly') == false){
				$("#jqGrid_dfee input[name='unitprce']").focus().select();
			}else{
				$("#jqGrid_dfee input[name='quantity']").focus().select();
			}
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_dfee.makedialog(false);

var dialog_tax_dfee = new ordialog(
	'taxcode_dfee',['hisdb.taxmast'],"#jqGrid_dfee input[name='taxcode']",errorField,
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
			let data=selrowData('#'+dialog_tax_dfee.gridname);
			$("#jqGrid_dfee #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_dfee input#"+id_optid+"_taxcode").val(data.taxcode);
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

			dialog_tax_dfee.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_dfee.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax_dfee.makedialog(false);

var dialog_mmacode_dfee = new ordialog(
	'mmacode_dfee',['hisdb.mmamaster'],"#jqGrid_dfee input[name='mmacode']",errorField,
	{	colModel:
		[
			{label:'MMA Code', name:'mmacode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
			{label:'Version', name:'version', width:100, classes:'pointer'},
			{label:'MMA Consult', name:'mmaconsult', width:100, classes:'pointer', align: 'right',formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
			{label:'MMA Surgeon', name:'mmasurgeon', width:100, classes:'pointer', align: 'right',formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
			{label:'MMA Anaes', name:'mmaanaes', width:100, classes:'pointer', align: 'right',formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
				
		],
		urlParam: {
					url:"./SalesOrderDetail/table",
					action: 'get_mmacode',
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
			let data=selrowData('#'+dialog_mmacode_dfee.gridname);
			$("#jqGrid_dfee input#"+id_optid+"_mmacode").val(data.mmacode);

			var constype = $("#jqGrid_dfee #"+id_optid+"_chgcode").data('constype');
			if(constype != undefined && constype.toUpperCase() == 'A'){
				$("#jqGrid_dfee input#"+id_optid+"_unitprce").val(data.mmaanaes);
			}else if(constype != undefined && constype.toUpperCase() == 'C'){
				$("#jqGrid_dfee input#"+id_optid+"_unitprce").val(data.mmaconsult);
			}else{
				$("#jqGrid_dfee input#"+id_optid+"_unitprce").val(data.mmasurgeon);
			}
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
		title:"Select MMA Code For Item",
		open:function(obj_){
			dialog_mmacode_dfee.urlParam.url = "./SalesOrderDetail/table";
			dialog_mmacode_dfee.urlParam.action = 'get_mmacode';
			dialog_mmacode_dfee.urlParam.filterCol=['compcode','recstatus'];
			dialog_mmacode_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_tax_dfee.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_mmacode_dfee.makedialog(false);

var dialog_deptcode_dfee = new ordialog(
	'deptcode_dfee',['sysdb.department'],"#jqGrid_dfee input[name='deptcode']",errorField,
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
			let data=selrowData('#'+dialog_deptcode_dfee.gridname);
			dialog_chgcode_dfee.urlParam.deptcode = data.deptcode;
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

			dialog_deptcode_dfee.urlParam.filterCol=['compcode','recstatus'];
			dialog_deptcode_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			// $(dialog_deptcode_dfee.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode_dfee.makedialog(false);

function trxdateCustomEdit_dfee(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_dfee" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_dfee(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	myreturn += `<div><input type='hidden' name='billtypeperct' id='`+id_optid+`_billtypeperct'>`;
	myreturn += `<input type='hidden' name='billtypeamt' id='`+id_optid+`_billtypeamt'>`;
	myreturn += `<input type='hidden' name='uom' id='`+id_optid+`_uom'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_uom_rate'></div>`;

	return $(myreturn);
}
function uomcodeCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function mmacodeCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="mmacode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	if(val.trim() == ''){
		val = $('#userdeptcode').val();
	}
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_dfee(val,opt){
	var myreturn = `<label>Dose</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Frequency</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Instruction</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label>Indicator</label><div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_dfee (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_dfee(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
		case 'mmacode':field=['mmacode','description'];table="hisdb.mmamaster";case_='mmacode';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	if(cellvalue != null && cellvalue.trim() != ''){
		fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
	}
	
	if(cellvalue == null)cellvalue = " ";
	return cellvalue;
}

function cust_rules_dfee(value, name) {
	var temp=null;
	switch (name) {
		case 'Item Code': temp = $("#jqGrid_dfee input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_dfee input[name='uom']"); break;
		case 'PO UOM': 
			temp = $("#jqGrid_dfee input[name='pouom']"); 
			var text = $( temp ).parent().siblings( ".help-block" ).text();
			if(text == 'Invalid Code'){
				return [false,"Please enter valid "+name+" value"];
			}

			break;
		case 'Price Code': temp = $("#jqGrid_dfee input[name='pricecode']"); break;
		case 'Tax Code': temp = $("#jqGrid_dfee input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_dfee input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}