
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){console.log(errorField[0]);
				return {

					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	/////////////////////////////////////////////////////////Get GROUPCODE AND Class /////////////////////////////
	var gc2 = $('#groupcode2').val();
	var Class2 = $('#Class2').val();

	/////////////////////////////////////////////////////////object for dialog handler//////////////////
	var dialog_itemcode = new ordialog(
		'itemcodesearch','material.productmaster','#itemcodesearch',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
				{label:'groupcode',name:'groupcode',hidden:true},
				{label:'Category',name:'productcat',hidden:false,canSearch:true}
			],
			urlParam: {
				filterCol:['groupcode', 'Class','recstatus','compcode'],
				filterVal:[gc2, Class2,'ACTIVE','session.compcode']
			},
			sortname:'idno',
			sortorder:'desc',
			ondblClickRow:function(){
				data = selrowData('#'+dialog_itemcode.gridname);
				productcat=data.productcat;
				groupcode=data.groupcode;
				description=data.description;
				Class=data.Class;
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#uomcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Item Code",
			open: function(){
				var gc2 = $('#groupcode2').val();
				var Class2 = $('#Class2').val();
				dialog_itemcode.urlParam.filterCol = ['groupcode', 'Class','recstatus','compcode'];
				dialog_itemcode.urlParam.filterVal = [ gc2, Class2,'ACTIVE','session.compcode'];

				$('#Dcol_itemcodesearch input[type="radio"][value="productcat"]').on('click',dialog_cat_selection_event);
				$('#Dcol_itemcodesearch input[type="radio"]:not([value="productcat"])').on('click',function(){
					$('#dialog_cat_selection_div').hide();
					$('#productcatAddNew_asset').val('');
				});

			}
		},'urlParam'
	);
	dialog_itemcode.makedialog();

	var dialog_uomcode = new ordialog(
		'uomcodesearch','material.uom','#uomcodesearch',errorField,
		{	colModel:[
				{label:'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}	
		},
		{
			title:"Select UOM Code",
			open: function(){
				dialog_uomcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_uomcode.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam'
	);
	dialog_uomcode.makedialog();

	var dialog_subcategory = new ordialog(
		'subcatcode','material.subcategory','#subcatcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'subcatcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#pouom').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}		
		},
		{
			title:"Select Sub Category",
			open: function(){
				dialog_subcategory.urlParam.filterCol = ['recstatus','compcode'];
				dialog_subcategory.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_subcategory.makedialog();

	var dialog_pouom = new ordialog(
		'pouom','material.uom','#pouom',errorField,
		{	colModel:[
				{label:'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#suppcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}		
		},
		{
			title:"Select PO UOM",
			open: function(){
				dialog_pouom.urlParam.filterCol = ['recstatus','compcode'];
				dialog_pouom.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_pouom.makedialog();

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#mstore').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_suppcode.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_suppcode.makedialog();

	var dialog_mstore = new ordialog(
		'mstore','sysdb.department','#mstore',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['mainstore','recstatus','compcode','sector'],
				filterVal:['1','ACTIVE','session.compcode','session.unit']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#TaxCode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Main Store",
			open: function(){
				dialog_mstore.urlParam.filterCol = ['mainstore','recstatus','compcode','sector'];
				dialog_mstore.urlParam.filterVal = ['1','ACTIVE','session.compcode','session.unit'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_mstore.makedialog();

	var dialog_taxCode = new ordialog(
		'TaxCode','hisdb.taxmast','#TaxCode',errorField,
		{	colModel:[
				{label:'Tax Code',name:'taxcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','taxtype','compcode'],
				filterVal:['ACTIVE','Input','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#recstatus').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Tax Code",
			open: function(){
				dialog_taxCode.urlParam.filterCol=['recstatus','taxtype','compcode'];
				dialog_taxCode.urlParam.filterVal=['ACTIVE','Input','session.compcode'];
			}
		},'urlParam','radio','notab',false
	);
	dialog_taxCode.makedialog();

	$('#btn_product_infront_asset').on( "click", function() {
		$('#product_infront_asset ~ a').click();
	});
	var dialog_product_infront_asset = new ordialog(
		'product_infront_asset','finance.facode','#product_infront_asset',errorField,
		{	colModel:[
				{label:'Category Code',name:'assetcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:[ 'recstatus'],
				filterVal:[ 'ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_product_infront_asset.gridname).assetcode;
				$("#searchForm input[name='Stext']").val($('#product_infront_asset').val());

				urlParam.searchCol=["productcat"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},
		{
			title:"Select Category Code",
			open: function(){
				dialog_product_infront_asset.urlParam.filterCol=['recstatus'];
				dialog_product_infront_asset.urlParam.filterVal=['ACTIVE'];
			}
		},'urlParam','radio','notab',false
	);
	dialog_product_infront_asset.makedialog(true);

	$('#btn_product_infront_stock').on( "click", function() {
		$('#product_infront_stock ~ a').click();
	});
	var dialog_product_infront_stock = new ordialog(
		'product_infront_stock','material.category','#product_infront_stock',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['cattype', 'source', 'recstatus'],
				filterVal:['Stock', 'PO', 'ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_product_infront_stock.gridname).catcode;
				$("#searchForm input[name='Stext']").val($('#product_infront_stock').val());

				urlParam.searchCol=["productcat"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},
		{
			title:"Select Category Code",
			open: function(){
				dialog_product_infront_stock.urlParam.filterCol=['cattype', 'source', 'recstatus'];
				dialog_product_infront_stock.urlParam.filterVal=['Stock', 'PO', 'ACTIVE'];
			}
		},'urlParam','radio','notab',false
	);
	dialog_product_infront_stock.makedialog(true);

	$('#btn_product_infront_others').on( "click", function() {
		$('#product_infront_others ~ a').click();
	});
	var dialog_product_infront_others = new ordialog(
		'product_infront_others','material.category','#product_infront_others',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['cattype', 'source', 'recstatus'],
				filterVal:['Other', 'PO', 'ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_product_infront_others.gridname).catcode;
				$("#searchForm input[name='Stext']").val($('#product_infront_others').val());

				urlParam.searchCol=["productcat"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},
		{
			title:"Select Category Code",
			open: function(){
				dialog_product_infront_others.urlParam.filterCol=['cattype', 'source', 'recstatus'];
				dialog_product_infront_others.urlParam.filterVal=['Other', 'PO', 'ACTIVE'];
			}
		},'urlParam','radio','notab',false
	);
	dialog_product_infront_others.makedialog(true);



	function dialog_cat_selection_event(){
		$('#dialog_cat_selection_div').show();
		if($('#dialog_cat_selection_div').length == 0){
			let dialog_cat_selection = null;
			if($('#groupcode2').val()=="Stock"){
				dialog_cat_selection = new ordialog(
					'dialog_cat_selection','material.category','#dialog_cat_selection',errorField,
					{	colModel:[
							{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						urlParam: {
							filterCol:['cattype', 'source', 'recstatus'],
							filterVal:['Stock', 'PO', 'ACTIVE']
						},
						ondblClickRow:function(){
							let data = selrowData('#' + dialog_cat_selection.gridname);
							$("#Dtext_itemcodesearch").val(data.catcode);

							dialog_itemcode.urlParam.searchCol=["productcat"];
							dialog_itemcode.urlParam.searchVal=[data.catcode];

							refreshGrid("#"+dialog_itemcode.gridname,dialog_itemcode.urlParam);
						}	
					},{
						title:"Select Product Category",
						open: function(){
							dialog_cat_selection.urlParam.filterCol=['cattype', 'source', 'recstatus','class'];
							dialog_cat_selection.urlParam.filterVal=['Stock', 'PO', 'ACTIVE',$('#Class2').val()];
						}
					},'urlParam'
				);
				dialog_cat_selection.makedialog(false);

			}else if($('#groupcode2').val()=="Asset") {

			    dialog_cat_selection = new ordialog(
					'dialog_cat_selection','finance.facode','#dialog_cat_selection',errorField,
					{	colModel:[
							{label:'Category Code',name:'assetcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						urlParam: {
							filterCol:[ 'recstatus'],
							filterVal:[ 'ACTIVE']
						},
						ondblClickRow:function(){
							let data = selrowData('#' + dialog_cat_selection.gridname);
							$("#Dtext_itemcodesearch").val(data.assetcode);

							dialog_itemcode.urlParam.searchCol=["productcat"];
							dialog_itemcode.urlParam.searchVal=[data.assetcode];

							refreshGrid("#"+dialog_itemcode.gridname,dialog_itemcode.urlParam);
						}	
					},{
						title:"Select Product Category",
						open: function(){
							dialog_cat_selection.urlParam.filterCol=['recstatus'];
							dialog_cat_selection.urlParam.filterVal=['ACTIVE',];
						}
					},'urlParam'
				);
				dialog_cat_selection.makedialog(false);

			}else if($('#groupcode2').val()=="Others") {

				dialog_cat_selection = new ordialog(
					'dialog_cat_selection','material.category','#dialog_cat_selection',errorField,
					{	colModel:[
							{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						urlParam: {
							filterCol:['cattype', 'source', 'recstatus'],
							filterVal:['Other', 'PO', 'ACTIVE']
						},
						ondblClickRow:function(){
							let data = selrowData('#' + dialog_cat_selection.gridname);
							$("#Dtext_itemcodesearch").val(data.catcode);

							dialog_itemcode.urlParam.searchCol=["productcat"];
							dialog_itemcode.urlParam.searchVal=[data.catcode];

							refreshGrid("#"+dialog_itemcode.gridname,dialog_itemcode.urlParam);
						}	
					},{
						title:"Select Product Category",
						open: function(){
							dialog_cat_selection.urlParam.filterCol=['cattype', 'source', 'recstatus','class'];
							dialog_cat_selection.urlParam.filterVal=['Other', 'PO', 'ACTIVE',$('#Class2').val()];
						}
					},'urlParam'
				);
				dialog_cat_selection.makedialog(false);
			}

			$("#Dparentdiv_itemcodesearch").after(`
				<div class='form-group' style='width:25%' id='dialog_cat_selection_div'>
					<div style="padding-left: 30px;padding-right: 30px;display:block">
						<label class="control-label"></label>
						<a class='form-control btn btn-primary' id="btn_dialog_cat_selection"><span class='fa fa-ellipsis-h'></span></a>
				  	</div>

				  	<div  id="show_dialog_cat_selection" style="display:none">
						<div class='input-group'>
							<input id="dialog_cat_selection" name="dialog_cat_selection" type="text" maxlength="12" class="form-control input-sm">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>

			  	</div>
			`);

			dialog_cat_selection.on();

			$('#btn_dialog_cat_selection').click(function(){
				$('#dialog_cat_selection ~ a').click();
			});
		}
		
	}

	////////////////////////////////////start dialog////////////////////////////////////////////////////

	var butt1=[{
		id: "Save",
		text: "Save",click: function() {
			radbuts.check();
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
				urlParam.filterCol=['groupcode', 'Class'];
				urlParam.filterVal=[$('#groupcode2').val(), $('#Class2').val()];
				refreshGrid('#jqGrid',urlParam,oper);
			}
		}
	},{
		id: "Cancel",
		text: "Cancel",click: function() {
			emptyFormdata(errorField,'#formdataSearch');
			emptyFormdata(errorField,'#formdata');
			$("#itemcodesearch").focus();
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					hideOne('#formdata');
					rdonly("#dialogForm");
					readonlyRTTrue();
					whenAdd();
					$("#Cancel").hide();
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					$('#formdata [hideOne]').show();
					//rdonly("#dialogForm");
					whenEdit();
					getgcforAdd();
					errorField.length=0;
					$("#Cancel").hide();
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$('#formdata [hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					whenEdit();
					$("#Cancel").hide();
					hiderad.check();
					break;
			}
			if(oper!='view'){
				set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
				dialog_uomcode.on();
				dialog_pouom.on();
				dialog_suppcode.on();
				dialog_mstore.on();
				dialog_subcategory.on();
				dialog_taxCode.on();
			}if(oper!='add'){
				dialog_pouom.check(errorField);
				dialog_suppcode.check(errorField);
				dialog_mstore.check(errorField);
				dialog_subcategory.check(errorField);
				dialog_taxCode.check(errorField);		 
			}if(oper == 'add') {
				dialog_itemcode.on();
				dialog_pouom.off();
				dialog_suppcode.off();
				dialog_mstore.off();
				dialog_subcategory.off();
				dialog_taxCode.off();
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdataSearch');

			urlParam.filterCol=['groupcode', 'Class'];
			urlParam.filterVal=[$('#groupcode2').val(), $('#Class2').val()];
			refreshGrid('#jqGrid',urlParam,oper);

			dialog_itemcode.off();
			dialog_pouom.off();
			dialog_suppcode.off();
			dialog_mstore.off();
			dialog_subcategory.off();
			dialog_taxCode.off();

			$('.my-alert').detach();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		table_name:'material.product',
		field:'',
		table_id:'idno',
		sort_idno:true,
		filterCol:['groupcode', 'Class','unit','compcode'],
		filterVal:[$('#groupcode2').val(), $('#Class2').val(),'session.unit','session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'product/form',
		noduplicate:true,
		field:'',
		oper:oper,
		table_name:'material.product',
		table_id:'idno',
		saveip:'true',
		checkduplicate:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
		 	{ label: 'Unit', name: 'unit', width: 20, sorttype: 'text', classes: 'wrap'  },
			{ label: 'Item Code', name: 'itemcode', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true},
			{ label: 'Item Description', name: 'description', width: 80, sorttype: 'text', classes: 'wrap', checked:true,canSearch: true  },
			{ label: 'Uom Code', name: 'uomcode', width: 30, sorttype: 'text', classes: 'wrap'  },
			{ label: 'Group Code', name: 'groupcode', width: 30, sorttype: 'text', classes: 'wrap'  },
			{ label: 'Class', name: 'Class', width: 40, sorttype: 'text', classes: 'wrap', hidden:true   },
			{ label: 'Product Category', name: 'productcat', width: 40, sorttype: 'text', classes: 'wrap' ,canSearch: true },
			{ label: 'Supplier Code', name: 'suppcode', width: 40, sorttype: 'text', classes: 'wrap'  },
			{ label: 'avgcost', name: 'avgcost', width: 50, hidden:true },
			{ label: 'actavgcost', name: 'actavgcost', width: 50, hidden:true },
			{ label: 'currprice', name: 'currprice', width: 40, hidden:true },
			{ label: 'Qty On Hand', name: 'qtyonhand', width: 40, classes: 'wrap',hidden:true},
			{ label: 'bonqty', name: 'bonqty', width: 50, hidden:true },
			{ label: 'rpkitem', name: 'rpkitem', width: 50, hidden:true },
			{ label: 'minqty', name: 'minqty', width: 50, hidden:true },
			{ label: 'maxqty', name: 'maxqty', width: 50, hidden:true },
			{ label: 'reordlevel', name: 'reordlevel', width: 50, hidden:true },
			{ label: 'reordqty', name: 'reordqty', width: 50, hidden:true },
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'chgflag', name: 'chgflag', width: 50, hidden:true },
			{ label: 'subcatcode', name: 'subcatcode', width: 50, hidden:true },
			{ label: 'expdtflg', name: 'expdtflg', width: 50, hidden:true },
			{ label: 'mstore', name: 'mstore', width: 50, hidden:true },
			{ label: 'costmargin', name: 'costmargin', width: 50, hidden:true },
			{ label: 'pouom', name: 'pouom', width: 50, hidden:true },
			{ label: 'reuse', name: 'reuse', width: 50, hidden:true },
			{ label: 'Tax Code', name: 'TaxCode', width: 50, hidden:true },
			{ label: 'trqty', name: 'trqty', width: 50, hidden:true },
			{ label: 'deactivedate', name: 'deactivedate', width: 50, hidden:true },
			{ label: 'tagging', name: 'tagging', width: 50, hidden:true },
			{ label: 'itemtype', name: 'itemtype', width: 50, hidden:true },
			{ label: 'generic', name: 'generic', width: 50, hidden:true },
			{ label: 'idno', name: 'idno', hidden: true},
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname: 'idno',
		sortorder: 'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			idno=rowid;
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();

			if(searched){
				populateFormdata("#jqGrid","#dialogForm","#formdata", idno,'view');
				searched = false;

				if($("#jqGrid").getGridParam("reccount") >= 1){
					$("#Save").hide();
					alert("Data Already Exist");
					emptyFormdata(errorField,'#formdata');
					forCancelAndExit();
					//$("#itemcodesearch").focus();
					//readonlyRTTrue();
					//dialog_mstore.offHandler();
				}
				
				if($("#jqGrid").getGridParam("reccount") < 1){
					readonlyRTFalse();
					$("#Save").show();
					$('#formdata input[name=productcat]').prop("readonly",true);
					$('#formdata input[name=lastcomputerid]').prop("readonly",true);
					$('#formdata input[name=lastipaddress]').prop("readonly",true);
					$('#formdata input[name=computerid]').prop("readonly",true);
					$('#formdata input[name=ipaddress]').prop("readonly",true);
					$('#itemcodesearch').prop("readonly",true);
					$('#uomcodesearch').prop("readonly",true);

					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
					getgcforAdd();
				}
			}
		},
		
	});

	////////////////////////////formatter//////////////////////////////////////////////////////////

	function readonlyRTTrue(){
		$('#formdata input[rdonly]').prop("readonly",true);
		$('#formdata  input[type=radio]').prop("disabled",true);
	}

	function readonlyRTFalse(){
		$('#formdata input[rdonly]').prop("readonly",false);
		$('#formdata  input[type=radio]').prop("disabled",false);
	}

	function whenAdd() {
		$('#formdataSearch_div').show();
		$("#Save").hide();

		$("#formdata label[for=itemcode]").hide();
		$("#itemcode_parent").hide();
		$("#formdata label[for=description]").hide();
		$("#description_parent").hide();
		$("#formdata label[for=uomcode]").hide();
		$("#uomcode_parent").hide();
	}

	function whenEdit() {
		$('#formdataSearch_div').hide();
		$("#Save").show();

		$("#formdata label[for=itemcode]").show();
		$("#itemcode_parent").show();
		$("#formdata label[for=description]").show();
		$("#description_parent").show();
		$("#formdata label[for=uomcode]").show();
		$("#uomcode_parent").show();
	}

	function checkradiobutton(radiobuttons){
		this.radiobuttons=radiobuttons;
		this.check = function(){
			$.each(this.radiobuttons, function( index, value ) {
				var checked = $("input[name="+value+"]:checked").val();
			    if(!checked){
			     	$("label[for="+value+"]").css('color', 'red');
			     	$(":radio[name='"+value+"']").parent('label').css('color', 'red');
				}else{
					$("label[for="+value+"]").css('color', '#444444');
					$(":radio[name='"+value+"']").parent('label').css('color', '#444444');
				}
			});
		}
	}

	var radbuts=new checkradiobutton(['itemtype','reuse','rpkitem','tagging','expdtflg','chgflag']);

	function textcolourradio(textcolour){
		this.textcolour=textcolour;
		this.check = function(){
			$.each(this.textcolour, function( index, value ) {
				$("label[for="+value+"]").css('color', '#444444');
				$(":radio[name="+value+"]").parent('label').css('color', '#444444');
			});
		}
	}

	var textCol=new textcolourradio(['itemtype','reuse','rpkitem','tagging','expdtflg','chgflag']);

	function hideradio(hideradioButton){
		this.hideradioButton=hideradioButton;
		this.check = function(){
			$.each(this.hideradioButton, function( index, value ) {
				$("#formdata :radio[name="+value+"]:not(:checked)").hide();
				$("#formdata :radio[name="+value+"]:not(:checked)").parent('label').hide();
			});
		}
	}

	var hiderad=new hideradio(['groupcode','Class']);

	var searched = false,productcat,groupcode,description,Class;
	$("#searchBut").click(function(){
		$("#generic").focus();
		if( $('#formdataSearch').isValid({requiredFields: ''}, conf, true) ) {
			emptyFormdata(errorField,'#formdata');
			$('#formdataSearch input[rdonly]').prop("readonly",true);
			$("#searchBut").prop("disabled",true);

			dialog_itemcode.off();
			dialog_uomcode.off();

			dialog_pouom.on();
			dialog_suppcode.on();
			dialog_mstore.on();
			dialog_subcategory.on();
			dialog_taxCode.on();

			urlParam.filterCol = ['itemcode','uomcode'];
			urlParam.filterVal = [$('#itemcodesearch').val(),$('#uomcodesearch').val()];
			
			searched = true;

			refreshGrid('#jqGrid',urlParam);

			$("#formdata :input[name='itemcode']").val($("#itemcodesearch").val());
			$("#formdata :input[name='uomcode']").val($("#uomcodesearch").val());

			$("#formdata :input[name='description']").val(description);
			$("#formdata :input[name='productcat']").val(productcat);
			
			$("#formdata [name=groupcode][value='"+groupcode+"']").prop('checked', true);
			$("#formdata [name=Class][value='"+Class+"']").prop('checked', true);
		}
	});

	$("#cancelBut").click(function(){
		emptyFormdata(errorField,'#formdataSearch');
		emptyFormdata(errorField,'#formdata');
		forCancelAndExit();
		$("#itemcodesearch").focus();
	});

	function getgcforAdd() {
		$("#formdata [name=groupcode][value='"+$('#groupcode2').val()+"']").prop('checked', true);
		$("#formdata [name=Class][value='"+$('#Class2').val()+"']").prop('checked', true);
		hiderad.check();

	}

	function forCancelAndExit(){
		$('#formdataSearch input[rdonly]').prop("readonly",false);
		readonlyRTTrue();
		$("#searchBut").prop("disabled",false);
		dialog_itemcode.on();
		dialog_uomcode.on();
		textCol.check();
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, {'idno':selrowData('#jqGrid').idno});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		id:"glyphicon-info-sign",
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
			recstatusDisable();
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	// populateSelect('#jqGrid','#searchForm');
	// searchClick('#jqGrid','#searchForm',urlParam);

	/////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['checked']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
		});
	}

	$('#Scol').on('change', scolChange);

	function scolChange() {
		if($('#Scol').val()=='productcat'){
			switch($('#groupcode2').val()) {
				case 'Asset': $("#div_product_infront_asset").show(); break;
				case 'Stock': $("#div_product_infront_stock").show(); break;
				case 'Others': $("#div_product_infront_others").show(); break;
			}
		} else {
			$("#div_product_infront_asset,#div_product_infront_stock,#div_product_infront_others").hide();
		}
	}

	searchClick_('#jqGrid','#searchForm',urlParam);

	function searchClick_(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		});
	}

	$('#searchForm [name=Stext]').on( "keyup", function() {
		$("#product_infront_asset").val($(this).val());
	});

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno', 'adduser', 'adddate', 'upduser', 'upddate', 'recstatus', 'computerid', 'ipaddress']);

	////////////////////////////////////////////////////////addNewProduct ///////////////////////////////
	var adpsaveParam={
		action:'save_productmaster',
		url:'product/form',
		field:['itemcode','description','groupcode', 'productcat', 'Class', 'computerid', 'ipaddress'],
		oper:'add',
		table_name:'material.productmaster',
		table_id:'itemcode',
		saveip:'true',
		checkduplicate:'true'
	};

	var addNew=[{
		id: 'addnp',
		text: "Add New",
		click: function() {
			$("#addNewProductDialog" ).dialog( "open" );

			$("#adpFormdata [name=groupcode][value='"+$('#groupcode2').val()+"']").prop('checked', true).show();
			$("#adpFormdata [name=Class][value='"+$('#Class2').val()+"']").prop('checked', true).show();
			$('#adpFormdata [type=radio]:not(:checked)').hide();
			$('#adpFormdata [type=radio]:not(:checked)').parent('label').hide();
			$('#productcatAddNew_asset').val(selrowData('#' + dialog_itemcode.gridname).productcat);
		}
	}];

	$( "#"+dialog_itemcode.dialogname ).dialog( "option", "buttons", addNew);

	var addNew2=[{
		text: "Save",click: function() {
			if( $('#adpFormdata').isValid({requiredFields: ''}, {}, true) ) {
				saveFormdata(
					"#"+dialog_itemcode.gridname,
					"#addNewProductDialog",
					"#adpFormdata",
					'add',
					adpsaveParam,dialog_itemcode.urlParam,
					{},
					function(){
						$("#"+dialog_itemcode.dialogname ).dialog( "close" );
						$("#dialogForm").dialog( "close" );
						delay(function(){
							errorField.length = 0;
							$("#jqGridPager td[title='Edit Selected Row']").click();
						}, 500 );
					});
			}
		}
	},{
		text: "Cancel",click: function() {
			$("#addNewProductDialog").dialog('close');
		}
	}];

	$("#addNewProductDialog")
		.dialog({
		width: 6/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			rdonly("#addNewProductDialog");
			set_compid_from_storage("input[name='computerid']", "input[name='ipaddress']");

			if($('#groupcode2').val()=="Stock" && $('#othergrid_productcatAddNew1').length == 0){
					let dialog_cat1 = new ordialog(
						'productcatAddNew1','material.category','#productcatAddNew_stock',errorField,
						{	colModel:[
								{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
								{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
							],
							urlParam: {
								filterCol:['cattype', 'source', 'recstatus'],
								filterVal:['Stock', 'PO', 'ACTIVE']
							},
							ondblClickRow:function(){
							}	
						},{
							title:"Select Product Category",
							open: function(){
								var gc2 = $('#groupcode2').val();
								dialog_cat1.urlParam.filterCol=['cattype', 'source', 'recstatus','class'];
								dialog_cat1.urlParam.filterVal=['Stock', 'PO', 'ACTIVE',$('#Class2').val()];
							}
						},'urlParam'
					);
					dialog_cat1.makedialog();
					dialog_cat1.on();

				} else if($('#groupcode2').val()=="Asset" && $('#othergrid_productcatAddNew2').length == 0) {

					let dialog_cat2 = new ordialog(
						'productcatAddNew2','finance.facode','#productcatAddNew_asset',errorField,
						{	colModel:[
								{label:'Category Code',name:'assetcode',width:100,classes:'pointer',canSearch:true,or_search:true},
								{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
							],
							urlParam: {
								filterCol:[ 'recstatus'],
								filterVal:[ 'ACTIVE']
							},
							ondblClickRow:function(){
							}	
						},{
							title:"Select Product Category",
							open: function(){
								dialog_cat2.urlParam.filterCol=['recstatus'];
								dialog_cat2.urlParam.filterVal=['ACTIVE'];
							}
						},'urlParam'
					);
					dialog_cat2.makedialog();
					dialog_cat2.on();

				} else if($('#groupcode2').val()=="Others" && $('#othergrid_productcatAddNew3').length == 0) {
					let dialog_cat3 = new ordialog(
						'productcatAddNew3','material.category','#productcatAddNew_other',errorField,
						{	colModel:[
								{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
								{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
							],
							urlParam: {
								filterCol:['cattype', 'source', 'recstatus'],
								filterVal:['Other', 'PO', 'ACTIVE']
							},
							ondblClickRow:function(){
							}	
						},{
							title:"Select Product Category",
							open: function(){
								dialog_cat3.urlParam.filterCol=['cattype', 'source', 'recstatus','class'];
								dialog_cat3.urlParam.filterVal=['Other', 'PO', 'ACTIVE',$('#Class2').val()];
							}
						},'urlParam'
					);
					dialog_cat3.makedialog();
					dialog_cat3.on();
				}
		},
		close: function( event, ui ) {
			emptyFormdata([],'#adpFormdata');
			$('.alert').detach();
			// $("#adpFormdata a").off();

			// dialog_cat1.off();
			// dialog_cat2.off();
			// dialog_cat3.off();
		},
		buttons :addNew2,
	});

});
