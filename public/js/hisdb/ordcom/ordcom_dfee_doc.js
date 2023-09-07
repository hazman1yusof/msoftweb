
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
			{ label: 'Date', name: 'trxdate', width: 80, classes: 'wrap',editable:true,
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
				label: 'Item Code', name: 'chgcode', width: 140, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_dfee },
				formatter: showdetail_dfee,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_dfee,
					custom_value: galGridCustomValue_dfee
				},
			},
			{ label: 'UOM Code', name: 'uom', hidden:true},
			{ label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', hidden:true},
			{ label: 'Tax', name: 'taxcode', hidden:true},
			{ label: 'Cost<br>Price', name: 'cost_price', hidden: true },
			{ label: 'Unit<br>Price', name: 'unitprce', hidden: true },
			{
				label: 'Quantity', name: 'quantity', width: 50, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{ label: 'Total<br>Amount', name: 'amount', width: 60, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Discount<br>Amount', name: 'discamount', hidden: true },
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Net<br>Amount', name: 'totamount', width: 60, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter,
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Dosage', name: 'remark', hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'Dosage', name: 'ftxtdosage', hidden: true },
			{ label: 'Frequency', name: 'frequency', hidden: true },
			{ label: 'Instruction', name: 'addinstruction', hidden: true },
			{ label: 'Drug Indicator', name: 'drugindicator', hidden: true },
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
		pager: "#jqGrid_dfee_pager",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_dfee",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-160);
			myfail_msg_dfee.clear_fail();
			if($("#jqGrid_dfee").data('lastselrow')==undefined||$("#jqGrid_dfee").data('lastselrow')==null){
				$("#jqGrid_dfee").setSelection($("#jqGrid_dfee").getDataIDs()[0]);
			}else{
				$("#jqGrid_dfee").setSelection($("#jqGrid_dfee").data('lastselrow'));
			}
			$("#jqGrid_dfee").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_dfee.clear_fail();
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
			if($('#jqGrid_dfee_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		onSelectRow:function(rowid){

		},
		ondblClickRow: function(rowId) {
			$('#jqGrid_dfee_iledit').click();
			$("#jqGrid_dfee").data('lastselrow',rowId);
		}
    });
	jqgrid_label_align_right("#jqGrid_dfee");
	
	$("#jqGrid_dfee").inlineNav('#jqGrid_dfee_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_dfee
		},
		editParams: myEditOptions_dfee_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_dfee_pager", {	
		id: "jqGrid_dfee_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_dfee").jqGrid('getGridParam', 'selrow');	
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
						id: selrowData('#jqGrid_dfee').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_dfee", urlParam_dfee);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_dfee", urlParam_dfee);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
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
		var selrowdata = $('#jqGrid_dfee').jqGrid ('getRowData', rowid);
		errorField.length=0;
		myfail_msg_dfee.clear_fail();
		$("#jqGrid_dfee input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").hide();

		dialog_deptcode_dfee.on();
		dialog_chgcode_dfee.on();
		// dialog_dosage_dfee.on();
		// dialog_frequency_dfee.on();
		// dialog_instruction_dfee.on();
		// dialog_drugindicator_dfee.on();
		mycurrency_dfee.array.length = 0;
		mycurrency_np_dfee.array.length = 0;
		Array.prototype.push.apply(mycurrency_dfee.array, ["#jqGrid_dfee input[name='totamount']","#jqGrid_dfee input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_dfee.array, ["#jqGrid_dfee input[name='quantity']"]);
		
		mycurrency_dfee.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_dfee.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_dfee input[name='quantity']").on('keyup',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);
		$("#jqGrid_dfee input[name='quantity']").on('blur',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);

		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
	},
	aftersavefunc: function (rowid, response, options) {
		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
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

		let justbc = $("#grid-command-buttons tr.justbc").data("rowId");
		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[justbc];

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
				uom: $("#jqGrid_dfee input[name='uom']").val(),
				uom_recv: $("#jqGrid_dfee input[name='uom_recv']").val(),
			    // ftxtdosage: $("#dosage_dfee_code").val(),
				// frequency: $("#frequency_dfee_code").val(),
				// addinstruction: $("#instruction_dfee_code").val(),
				// drugindicator: $("#drugindicator_dfee_code").val(),
				taxamount: $("#jqGrid_dfee input[name='taxamount']").val(),
				discamount: $("#jqGrid_dfee input[name='discamount']").val(),
				unitprce: $("#jqGrid_dfee input[name='unitprce']").val(),
				// totamount: $("#jqGrid_dfee input[name='totamount']").val(),
			});
		$("#jqGrid_dfee").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
		myfail_msg_dfee.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_dfee')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
		refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_dfee_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		var selrowdata = $('#jqGrid_dfee').jqGrid ('getRowData', rowid);
		myfail_msg_dfee.clear_fail();
		$("#jqGrid_dfee input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").hide();
		dialog_deptcode_dfee.on();
		dialog_deptcode_dfee.id_optid = rowid;
		dialog_deptcode_dfee.check(errorField,rowid+"_deptcode","jqGrid_dfee",null,null,null );

		dialog_chgcode_dfee.on();
		dialog_chgcode_dfee.id_optid = rowid;
		dialog_chgcode_dfee.check(errorField,rowid+"_chgcode","jqGrid_dfee",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_dfee input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_dfee input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_dfee input[name='chgcode']").val();
				self.urlParam.uom = selrowdata.uom;
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#ordcomtt_dfee').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_dfee input[name='billtypeperct']").val(retdata['billty_percent']);
					$("#jqGrid_dfee input[name='billtypeamt']").val(retdata['billty_amount']);
					$("#jqGrid_dfee input[name='uom_rate']").val(retdata['rate']);
					$("#jqGrid_dfee input[name='convfactor_uom']").val(retdata['convfactor']);
					$("#jqGrid_dfee input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_dfee input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );
	    $("#jqGrid_dfee input[name='taxamount']").val(ret_parsefloat(selrowdata.taxamount));
	    $("#jqGrid_dfee input[name='discamount']").val(ret_parsefloat(selrowdata.discamount));
	    $("#jqGrid_dfee input[name='billtypeamt']").val(ret_parsefloat(selrowdata.billtypeamt));
	    $("#jqGrid_dfee input[name='unitprce']").val(selrowdata.unitprce);
	    $("#jqGrid_dfee input[name='uom']").val(selrowdata.uom);
	    $("#jqGrid_dfee input[name='uom_recv']").val(selrowdata.uom_recv);

		// dialog_dosage_dfee.on();
		// dialog_dosage_dfee.id_optid = rowid;
		// dialog_dosage_dfee.check(errorField,rowid+"_ftxtdosage","jqGrid_dfee",null,null,null );

		// dialog_frequency_dfee.on();
		// dialog_frequency_dfee.id_optid = rowid;
		// dialog_frequency_dfee.check(errorField,rowid+"_frequency","jqGrid_dfee",null,null,null );

		// dialog_instruction_dfee.on();
		// dialog_instruction_dfee.id_optid = rowid;
		// dialog_instruction_dfee.check(errorField,rowid+"_addinstruction","jqGrid_dfee",null,null,null );

		// dialog_drugindicator_dfee.on();
		// dialog_drugindicator_dfee.id_optid = rowid;
		// dialog_drugindicator_dfee.check(errorField,rowid+"_drugindicator","jqGrid_dfee",null,null,null );


		mycurrency_dfee.array.length = 0;
		mycurrency_np_dfee.array.length = 0;
		Array.prototype.push.apply(mycurrency_dfee.array, ["#jqGrid_dfee input[name='totamount']","#jqGrid_dfee input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_dfee.array, ["#jqGrid_dfee input[name='quantity']"]);
		
		mycurrency_dfee.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_dfee.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid_dfee input[name='quantity']").on('keyup',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);
		$("#jqGrid_dfee input[name='quantity']").on('blur',{currency: [mycurrency_dfee,mycurrency_np_dfee]},calculate_line_totgst_and_totamt_dfee);

		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
		// $("#jqGrid_dfee input[name='chgcode']").focus();
	},
	aftersavefunc: function (rowid, response, options) {
		// dialog_dosage_dfee.off();
		// dialog_frequency_dfee.off();
		// dialog_instruction_dfee.off();
		// dialog_drugindicator_dfee.off();
		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
		refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_dfee.off();
		// dialog_frequency_dfee.off();
		// dialog_instruction_dfee.off();
		// dialog_drugindicator_dfee.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
    	// $("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
		console.log(errorField);
    	if(errorField.length>0)return false;
		mycurrency_dfee.formatOff();
		mycurrency_np_dfee.formatOff();

		if(parseInt($('#jqGrid_dfee input[name="quantity"]').val()) <= 0)return false;

		if(myfail_msg_dfee.fail_msg_array.length>0){
			return false;
		}

		let justbc = $("#grid-command-buttons tr.justbc").data("rowId");
		let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[justbc];

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
				uom: $("#jqGrid_dfee input[name='uom']").val(),
				uom_recv: $("#jqGrid_dfee input[name='uom_recv']").val(),
			    // ftxtdosage: $("#dosage_dfee_code").val(),
				// frequency: $("#frequency_dfee_code").val(),
				// addinstruction: $("#instruction_dfee_code").val(),
				// drugindicator: $("#drugindicator_dfee_code").val(),
				taxamount: $("#jqGrid_dfee input[name='taxamount']").val(),
				discamount: $("#jqGrid_dfee input[name='discamount']").val(),
				unitprce: $("#jqGrid_dfee input[name='unitprce']").val(),
				// totamount: $("#jqGrid_dfee input[name='totamount']").val(),
			});
		$("#jqGrid_dfee").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_dfee.off();
		// dialog_frequency_dfee.off();
		// dialog_instruction_dfee.off();
		// dialog_drugindicator_dfee.off();
    	$("#jqGrid_dfee_pagerRefresh,#jqGrid_dfee_pagerDelete").show();
		myfail_msg_dfee.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_dfee')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_dfee",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-100);
		refreshGrid('#jqGrid_dfee',urlParam_dfee,'add');
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
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_dfee.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_dfee #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val());

	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	if (balconv != 0) {
		myfail_msg_dfee.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_dfee.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_dfee #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
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
				price : 'PRICE2',
				entrydate : moment().format('YYYY-MM-DD'),
				billtype : $('#billtype_def_code').val(),
				uom : $("#jqGrid_dfee input[name='uom']").val(),
				deptcode : $("#userdeptcode").val(),
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
			dialog_chgcode_dfee.urlParam.uom = data['uom'];
			dialog_chgcode_dfee.urlParam.chgcode = data['chgcode'];

			$("#jqGrid_dfee #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_dfee #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_dfee #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_dfee #"+id_optid+"_uom").val(data['uom']);
			$("#jqGrid_dfee #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
			$("#jqGrid_dfee #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_dfee #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
			$("#jqGrid_dfee #"+id_optid+"_quantity").val('');

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
			dialog_chgcode_dfee.urlParam.uom = $("#jqGrid_dfee input[name='uom']").val();
			dialog_chgcode_dfee.urlParam.chgcode = $("#jqGrid_dfee input[name='chgcode']").val();
			dialog_chgcode_dfee.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_dfee.urlParam.filterVal = [$('#ordcomtt_dfee').val()];
		},
		close: function(obj){
			$("#jqGrid_dfee input[name='uom_recv']").focus().select();
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_dfee.makedialog(false);

var dialog_uomcode_dfee = new ordialog(
	'uom_dfee',['material.uom AS u'],"#jqGrid_dfee input[name='uom']",errorField,
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

			let data=selrowData('#'+dialog_uomcode_dfee.gridname);

			$("#jqGrid_dfee #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_dfee #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_dfee #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid_dfee #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_dfee #"+id_optid+"_uom").val(data['uomcode']);
			$("#jqGrid_dfee #"+id_optid+"_unitprce").val(data['price']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_dfee #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_dfee #"+id_optid+"_quantity").val('');

			dialog_tax_dfee.check(errorField);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_dfee input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let id_optid = obj_.id_optid;

			dialog_uomcode_dfee.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_dfee.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_dfee.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_dfee.urlParam.action_chk = "get_itemcode_uom_check";
			dialog_uomcode_dfee.urlParam.entrydate = $("#jqGrid_dfee input[name='trxdate']").val();
			dialog_uomcode_dfee.urlParam.chgcode = $("#jqGrid_dfee input[name='chgcode']").val();
			dialog_uomcode_dfee.urlParam.deptcode = $("#jqGrid_dfee input[name='deptcode']").val();
			dialog_uomcode_dfee.urlParam.price = 'PRICE2';
			dialog_uomcode_dfee.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_dfee.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_dfee.urlParam.filterVal = [$('#ordcomtt_dfee').val()];
		},
		close: function(){
			$("#jqGrid_dfee input[name='uom_recv']").focus().select();
			// $(dialog_uomcode_dfee.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uomcode_dfee.makedialog(false);

var dialog_uom_recv_dfee = new ordialog(
	'uom_recv_dfee',['material.uom AS u'],"#jqGrid_dfee input[name='uom_recv']",errorField,
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
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE'],
					url:"./SalesOrderDetail/table",
					url_chk:"./SalesOrderDetail/table",
					action: 'get_itemcode_uom_recv',
					action_chk: 'get_itemcode_uom_recv_check',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $("#userdeptcode").val(),
					billtype : $('#billtype_def_code').val(),
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

			let data=selrowData('#'+dialog_uom_recv_dfee.gridname);

			myfail_msg_dfee.del_fail({id:'noprod_'+id_optid});
			myfail_msg_dfee.del_fail({id:'nostock_'+id_optid});

			if(data.invflag == '1' && (data.pt_idno == '' || data.pt_idno == null)){
				myfail_msg_dfee.add_fail({
					id:'noprod_dfee',
					textfld:"#jqGrid_dfee #"+id_optid+"_uom_recv",
					msg:'Item Item not available in product master, please check',
				});

				$("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_dfee #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_dfee #"+id_optid+"_quantity").val('');
				$("#jqGrid_dfee #"+id_optid+"_cost_price").val('');

			}else if(data.invflag == '1' && (data.st_idno == "" || data.st_idno == null)){
				myfail_msg_dfee.add_fail({
					id:'nostock_dfee',
					textfld:"#jqGrid_dfee #"+id_optid+"_uom_recv",
					msg:'Item not available in store dept '+$("#jqGrid_dfee #"+id_optid+"_deptcode").parent().next('span.help-block').text()+', please check',
				});

				$("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_dfee #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_dfee #"+id_optid+"_quantity").val('');
				$("#jqGrid_dfee #"+id_optid+"_cost_price").val('');
			}else{
				myfail_msg_dfee.del_fail({id:'noprod_dfee'});
				myfail_msg_dfee.del_fail({id:'nostock_dfee'});

				$("#jqGrid_dfee #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
				$("#jqGrid_dfee #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
				$("#jqGrid_dfee #"+id_optid+"_quantity").val('');
				$("#jqGrid_dfee #"+id_optid+"_uom_recv").val(data['uomcode']);
				$("#jqGrid_dfee #"+id_optid+"_cost_price").val(data['avgcost']);
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_dfee input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			dialog_uom_recv_dfee.urlParam.url = "./SalesOrderDetail/table";
			dialog_uom_recv_dfee.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_dfee.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uom_recv_dfee.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_dfee.urlParam.entrydate = $("#jqGrid_dfee input[name='trxdate']").val();
			dialog_uom_recv_dfee.urlParam.chgcode = $("#jqGrid_dfee input[name='chgcode']").val();
			dialog_uom_recv_dfee.urlParam.deptcode = $("#jqGrid_dfee input[name='deptcode']").val();
			dialog_uom_recv_dfee.urlParam.price = 'PRICE2';
			dialog_uom_recv_dfee.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_dfee.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_dfee.urlParam.filterVal = [$('#ordcomtt_dfee').val()];
		},
		close: function(){
			$("#jqGrid_dfee input[name='quantity']").focus().select();
			// $(dialog_uomcode_dfee.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uom_recv_dfee.makedialog(false);

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

// var dialog_dosage_dfee = new ordialog(
// 	'dosage_dfee',['hisdb.dose'],"#jqGrid_dfee input[name='dosage']",errorField,
// 	{	colModel:
// 		[
// 			{label:'Dosage Code', name:'dosecode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'dosedesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			let data=selrowData('#'+dialog_dosage_dfee.gridname);
// 			// $(dialog_dosage_dfee.textfield).val(data.dosedesc);
// 			// $(dialog_dosage_dfee.textfield+'_code').val(data.dosecode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_dosage_dfee.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_dosage_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_dfee.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_dosage_dfee.makedialog(false);

// var dialog_frequency_dfee = new ordialog(
// 	'freq_dfee',['hisdb.freq'],"#jqGrid_dfee input[name='frequency']",errorField,
// 	{	colModel:
// 		[
// 			{label:'Frequency Code', name:'freqcode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'freqdesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			let data=selrowData('#'+dialog_frequency_dfee.gridname);
// 			// $(dialog_frequency_dfee.textfield).val(data.freqdesc);
// 			// $(dialog_frequency_dfee.textfield+'_code').val(data.freqcode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_frequency_dfee.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_frequency_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_dfee.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_frequency_dfee.makedialog(false);

// var dialog_instruction_dfee = new ordialog(
// 	'instruction_dfee',['hisdb.instruction'],"#jqGrid_dfee input[name='instruction']",errorField,
// 	{	colModel:
// 		[
// 			{label:'Dosage Code', name:'inscode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			let data=selrowData('#'+dialog_instruction_dfee.gridname);
// 			// $(dialog_instruction_dfee.textfield).val(data.description);
// 			// $(dialog_instruction_dfee.textfield+'_code').val(data.inscode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_instruction_dfee.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_instruction_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_dfee.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_instruction_dfee.makedialog(false);

// var dialog_drugindicator_dfee = new ordialog(
// 	'drugindicator_dfee',['hisdb.drugindicator'],"#jqGrid_dfee input[name='drugindicator']",errorField,
// 	{	colModel:
// 		[
// 			{label:'Dosage Code', name:'drugindcode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			let data=selrowData('#'+dialog_drugindicator_dfee.gridname);
// 			// $(dialog_drugindicator_dfee.textfield).val(data.description);
// 			// $(dialog_drugindicator_dfee.textfield+'_code').val(data.drugindcode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_drugindicator_dfee.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_drugindicator_dfee.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_dfee.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_drugindicator_dfee.makedialog(false);

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
	myreturn += `<input type='hidden' name='discamount' id='`+id_optid+`_discamount'>`;
	myreturn += `<input type='hidden' name='taxamount' id='`+id_optid+`_taxamount'>`;
	myreturn += `<input type='hidden' name='unitprce' id='`+id_optid+`_unitprce'>`;
	myreturn += `<input type='hidden' name='uom_rate' id='`+id_optid+`_uom_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='uom' id='`+id_optid+`_uom'>`;
	myreturn += `<input type='hidden' name='uom_recv' id='`+id_optid+`_uom_recv'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;

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
function totamountFormatter(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) - ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function deptcodeCustomEdit_dfee(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	if(val.trim() == ''){
		val = $('#userdeptcode').val();
	}
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function dosageCustomEdit_dfee(val,opt){
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function frequencyCustomEdit_dfee(val,opt){
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function instructionCustomEdit_dfee(val,opt){
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function drugindicatorCustomEdit_dfee(val,opt){
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_dfee(val,opt){
	var myreturn = `<label class='oe_dfee_label'>Dose</label><div class="oe_dfee_div input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_dfee_label'>Frequency</label><div class="oe_dfee_div input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_dfee_label'>Instruction</label><div class="oe_dfee_div input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_dfee_label'>Indicator</label><div class="oe_dfee_div input-group"><input autocomplete="off" jqgrid="jqGrid_dfee" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}
function ret_parsefloat(int){
	if(!isNaN(parseFloat(int))){
		return parseFloat(int);
	}else{
		return 0;
	}
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
		case 'drugindicator':field=['drugindcode','description'];table="hisdb.drugindicator";case_='drugindicator';break;
		case 'frequency':field=['freqcode','freqdesc'];table="hisdb.freq";case_='deptcode';break;
		case 'ftxtdosage':field=['dosecode','dosedesc'];table="hisdb.dose";case_='dose';break;
		case 'addinstruction':field=['inscode','description'];table="hisdb.instruction";case_='instruction';break;
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
		case 'Dept. Code': temp = $("#jqGrid_dfee input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_dfee input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_dfee input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_dfee input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_dfee input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_dfee input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_dfee input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}