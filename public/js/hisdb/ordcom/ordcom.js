

var urlParam_ordcom={
	action:'ordcom_table',
	url:'./ordcom/table',
	mrn:'',
	episno:''
};
var fdl_ordcom = new faster_detail_load();
var myfail_msg = new fail_msg_func();
var errorField = [];

$(document).ready(function(){
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				console.log(errorField);
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
		editurl: "SalesOrderDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'rowno', name: 'rowno', width: 50, classes: 'wrap', editable: false, hidden: true },
			{
				label: 'Item Code', name: 'chggroup', width: 200, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 180, classes: 'wrap', editable: false, editoptions: { readonly: "readonly" }, hidden:true },
			{
				label: 'UOM Code', name: 'uom', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit,
					custom_value: galGridCustomValue
				},
			},{
				label: 'Tax', name: 'taxcode', width: 100, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Unit Price', name: 'unitprice', width: 100, classes: 'wrap txnum', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{
				label: 'Quantity', name: 'quantity', width: 100, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{
				label: 'Quantity on Hand', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{ label: 'Total Amount <br>Before Tax', name: 'amount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{
				label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{
				label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{ label: 'Discount Amount', name: 'discamt', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{
				label: 'Tax Amount', name: 'taxamt', width: 100, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules: { required: true },editoptions:{readonly: "readonly"},
			},
			{ label: 'Total Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key:true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: false,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'idno',
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

	var myEditOptions_ordcom = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			alert('asd');
			myfail_msg.clear_fail;
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_chggroup.on();
			dialog_uomcode.on();
			dialog_uom_recv.on();
			dialog_tax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='billtypeperct']","#jqGrid2 input[name='billtypeamt']","#jqGrid2 input[name='totamount']","#jqGrid2 input[name='amount']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyonhand']","#jqGrid2 input[name='quantity']"]);
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='quantity']").on('keyup',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
			$("#jqGrid2 input[name='unitprice']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			// $("#jqGrid2 input[name='quantity']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='billtypeamt'],#jqGrid2 input[name='quantity'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);

			$("#jqGrid2 input[name='qtyonhand']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9'){
					delay(function(){
						$('#jqGrid2_ilsave').click();
						addmore_jqgrid2.state = true;
					}, 500 );
				}
			});

			calc_jq_height_onchange("jqGrid_ordcom");

		},
		aftersavefunc: function (rowid, response, options) {
			$('#db_amount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
		beforeSaveRow: function (options, rowid) {
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="quantity"]').val()) <= 0)return false;

			if(myfail_msg.fail_msg_array.length>0){
				return false;
			}

			let editurl = "./SalesOrderDetail/form?"+
				$.param({
					action: 'saleord_detail_save',
					source: 'PB',
					trantype:'IN',
					auditno: $('#db_auditno').val(),
					// discamt: $("#jqGrid2 input[name='discamt']").val(),
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			myfail_msg.clear_fail;
			errorField.length=0;
			// delay(function(){
			// 	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			// }, 500 );
			hideatdialogForm(false);
	    },
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

});

var dialog_chggroup = new ordialog(
	'chggroup',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid2 input[name='chggroup']",errorField,
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
			{label: 'billty_amount',name:'billty_amount',hidden:true},
			{label: 'billty_percent',name:'billty_percent',hidden:true},
			
		],
		urlParam: {
				url:"./SalesOrderDetail/table",
				action: 'get_itemcode_price',
				url_chk: './SalesOrderDetail/table',
				action_chk: 'get_itemcode_price_check',
				entrydate : $('#db_entrydate').val(),
				billtype : $('#db_hdrtype').val(),
				filterCol:['deptcode','price'],
				filterVal:[$('#db_deptcode').val(),$('#pricebilltype').val()]
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

			let data=selrowData('#'+dialog_chggroup.gridname);

			$("#jqGrid2 #"+id_optid+"_chggroup").data('st_idno',data['st_idno']);
			$("#jqGrid2 #"+id_optid+"_chggroup").data('invflag',data['invflag']);
			$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
			$("#jqGrid2 #"+id_optid+"_chggroup").data('pt_idno',data['pt_idno']);
			$('#'+dialog_chggroup.gridname).data('fail_msg','');

			if(data.invflag == '1' && data.pt_idno == ''){
				myerrorIt_only2('input#'+id_optid+'_chggroup',true);
				let name = 'noprod_'+id_optid;
				let fail_msg = 'Item not available in product master, please check';
				myfail_msg.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chggroup').text('');

				ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

				$("#jqGrid2 #"+id_optid+"_taxcode").val('');
				$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
				$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid2 #"+id_optid+"_quantity").val('');
				$("#jqGrid2 #"+id_optid+"_uom").val('');
				$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
				$("#jqGrid2 #"+id_optid+"_unitprice").val('');
				$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			}else if(data.invflag == '1' && data.st_idno == ""){
				myerrorIt_only2('input#'+id_optid+'_chggroup',true);
				let name = 'nostock_'+id_optid;
				let fail_msg = 'Item not available in store dept '+$('#db_deptcode').parent().next('span.help-block').text()+', please check';	
				myfail_msg.add_fail({id:name,msg:fail_msg});
				$('span#'+id_optid+'_chggroup').text('');
				
				ordialog_buang_error_shj("#jqGrid2 #"+id_optid+"_taxcode",errorField);

				$("#jqGrid2 #"+id_optid+"_taxcode").val('');
				$("#jqGrid2 #"+id_optid+"_uom_rate").val('');
				$("#jqGrid2 #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid2 #"+id_optid+"_quantity").val('');
				$("#jqGrid2 #"+id_optid+"_uom").val('');
				$("#jqGrid2 #"+id_optid+"_uom_recv").val('');
				$("#jqGrid2 #"+id_optid+"_unitprice").val('');
				$("#jqGrid2 #"+id_optid+"_billtypeperct").val('');
				$("#jqGrid2 #"+id_optid+"_billtypeamt").val('');
			}else{
				myfail_msg.del_fail({id:'noprod_'+id_optid});
				myfail_msg.del_fail({id:'nostock_'+id_optid});

				$("#jqGrid2 #"+id_optid+"_chggroup").val(data['chgcode']);
				$("#jqGrid2 #"+id_optid+"_taxcode").val(data['taxcode']);
				$("#jqGrid2 #"+id_optid+"_uom_rate").val(data['rate']);
				$("#jqGrid2 #"+id_optid+"_qtyonhand").val(data['qtyonhand']);
				$("#jqGrid2 #"+id_optid+"_quantity").val('');
				$("#jqGrid2 #"+id_optid+"_uom").val(data['uom']);
				$("#jqGrid2 #"+id_optid+"_uom_recv").val(data['uom']);
				$("#jqGrid2 #"+id_optid+"_unitprice").val(data['price']);
				$("#jqGrid2 #"+id_optid+"_billtypeperct").val(data['billty_percent']);
				$("#jqGrid2 #"+id_optid+"_billtypeamt").val(data['billty_amount']);

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
				$("#jqGrid2 input[name='quantity']").focus().select();
			}
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			dialog_chggroup.urlParam.url = "./SalesOrderDetail/table";
			dialog_chggroup.urlParam.action = 'get_itemcode_price';
			dialog_chggroup.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_chggroup.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chggroup.urlParam.filterCol = ['deptcode','price'];
			dialog_chggroup.urlParam.filterVal = [$('#db_deptcode').val(),$('#pricebilltype').val()];
			dialog_chggroup.urlParam.entrydate = $('#db_entrydate').val();
			dialog_chggroup.urlParam.billtype = $('#db_hdrtype').val();

		},
		close: function(obj){
			$("#jqGrid2 input[name='quantity']").focus().select();
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chggroup.makedialog(false);

var dialog_uomcode = new ordialog(
	'uom',['material.uom AS u'],"#jqGrid2 input[name='uom']",errorField,
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
			$("#jqGrid2 input#"+id_optid+"_uom").val(data.uomcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid2 input[name='qty']").focus();
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
			dialog_uomcode.urlParam.filterVal = [$("#jqGrid2 #"+id_optid+"_chggroup").val(),$('#pricebilltype').val()];
			dialog_uomcode.urlParam.entrydate = $('#db_entrydate').val();
			dialog_uomcode.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_chggroup").val();
			dialog_uomcode.urlParam.deptcode = $('#db_deptcode').val();
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
	'uom_recv',['material.uom AS u'],"#jqGrid2 input[name='uom_recv']",errorField,
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
			$("#jqGrid2 input#"+id_optid+"_uom_recv").val(data.uomcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid2 input[name='qty']").focus();
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
	'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
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
			$("#jqGrid2 #"+id_optid+"_uom_rate").val(data['rate']);
			$("#jqGrid2 input#"+id_optid+"_taxcode").val(data.taxcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid2 input[name='taxamt']").focus();
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

function itemcodeCustomEdit(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="chggroup" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
}
function uomcodeCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>
		<span><input id="`+opt.id+`_discamt" name="discamt" type="hidden"></span>
		<span><input id="`+opt.id+`_rate" name="rate" type="hidden"></span>`);
}
function uom_recvCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
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
	if(saveallrow == 'saveallrow'){	
		$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll,#saveDetailLabel").hide();	
		$("#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_pagerCancelAll").show();	
	}else if(hide){	
		$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll,#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_pagerCancelAll").hide();	
		$("#saveDetailLabel").show();	
	}else{	
		$("#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGrid_ordcom_pagerDelete,#jqGrid_ordcom_pagerEditAll").show();	
		$("#saveDetailLabel,#jqGrid_ordcom_pagerSaveAll,#jqGrid_ordcom_iledit,#jqGrid_ordcom_pagerCancelAll").hide();	
	}	
}

function showdetail(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
		case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
		case 'db_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_payercode';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

	fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
	
	if(cellvalue == null)cellvalue = " ";
	return cellvalue;
}

function cust_rules(value, name) {
	var temp=null;
	switch (name) {
		case 'Item Code': temp = $("#jqGrid2 input[name='chggroup']"); break;
		case 'UOM Code': temp = $("#jqGrid2 input[name='uom']"); break;
		case 'PO UOM': 
			temp = $("#jqGrid2 input[name='pouom']"); 
			var text = $( temp ).parent().siblings( ".help-block" ).text();
			if(text == 'Invalid Code'){
				return [false,"Please enter valid "+name+" value"];
			}

			break;
		case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']"); break;
		case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']"); break;
		case 'Quantity Request': temp = $("#jqGrid2 input[name='quantity']");break;
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