
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
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
			if(errorField.length>0){console.log(errorField);
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
	var unit_used = $('#unit_used').val();
	var fdl = new faster_detail_load();

	/////////////////////////////////////////////////////////object for dialog handler//////////////////

	$("#dialog_barcode")
	  .dialog({
		width: 2/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {

		},
		close: function( event, ui ) {

		}
	  });

    $('#print_barcode').click(function(){
        $('#dialog_barcode').dialog('open');
    });

    $('#barcode_print').click(function(){
	    window.open('./product/table?action=print_barcode&itemcodefrom='+$('#itemcode_from_barcode').val()+'&itemcodeto='+$('#itemcode_to_barcode').val()+'&pages='+$('#barcode_pages').val()+'&groupcode='+gc2+'&Class='+Class2);
    });

	var itemcode_from_barcode = new ordialog(
		'itemcode_from_barcode','material.product','#itemcode_from_barcode','errorField',
		{	
			colModel:[
				{label:'Item Code',name:'itemcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['groupcode', 'Class','recstatus','compcode','unit'],
				filterVal:[gc2, Class2,'ACTIVE','session.compcode',unit_used]
			},
			sortname:'itemcode',
			sortorder:'asc',
			ondblClickRow: function () {
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
		},{
			title:"Select Item From",
			open: function(){
				itemcode_from_barcode.urlParam.filterCol=['groupcode', 'Class','recstatus','compcode','unit'];//,'sector'
				itemcode_from_barcode.urlParam.filterVal=[gc2, Class2,'ACTIVE','session.compcode',unit_used];//, 'session.unit'
			},
			close: function(obj_){
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','notab'
	);
	itemcode_from_barcode.makedialog(true);

	var itemcode_to_barcode = new ordialog(
		'itemcode_to_barcode','material.product','#itemcode_to_barcode','errorField',
		{	
			colModel:[
				{label:'Item Code',name:'itemcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['groupcode', 'Class','recstatus','compcode','unit'],
				filterVal:[gc2, Class2,'ACTIVE','session.compcode',unit_used]
			},
			sortname:'itemcode',
			sortorder:'asc',
			ondblClickRow: function () {
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
		},{
			title:"Select Item To",
			open: function(){
				itemcode_to_barcode.urlParam.filterCol=['groupcode', 'Class','recstatus','compcode','unit'];//,'sector'
				itemcode_to_barcode.urlParam.filterVal=[gc2, Class2,'ACTIVE','session.compcode',unit_used];//, 'session.unit'
			},
			close: function(obj_){
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','notab'
	);
	itemcode_to_barcode.makedialog(true);

	var dialog_itemcode = new ordialog(
		'itemcodesearch','material.productmaster','#itemcodesearch',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
				{label:'groupcode',name:'groupcode',hidden:false},
				{label:'Category',name:'productcat',hidden:false,canSearch:true},
				{label:'Unit',name:'unit',hidden:false,canSearch:false}
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
		},'urlParam','radio','tab',false
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
			min_search_length:1,
			open: function(){
				dialog_uomcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_uomcode.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','tab',false
	);
	dialog_uomcode.makedialog();

	var dialog_uomcode_addnew = new ordialog(
		'dialog_uomcode_addnew','material.uom','#uomcodeAddNew',errorField,
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
			min_search_length:1,
			open: function(){
				dialog_uomcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_uomcode.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','tab',false
	);
	dialog_uomcode_addnew.makedialog(false);

	var dialog_subcategory = new ordialog(
		'subcatcode','material.subcategory','#subcatcode','errorField',
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
		},'urlParam','radio','tab'
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
				filterCol:['storedept','recstatus','compcode','sector'],
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
				dialog_mstore.urlParam.filterCol = ['storedept','recstatus','compcode','sector'];
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

	var dialog_chggroup = new ordialog(
		'chggroup', 'hisdb.chggroup', '#cm_chggroup', 'errorField',
		{
			colModel: [
				{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			}
		},{
			title: "Select Group Code",
			open: function () {
				dialog_chggroup.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chggroup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab',false
	);
	dialog_chggroup.makedialog(true);

	var dialog_chgtype = new ordialog(
		'chgtype', 'hisdb.chgtype', '#cm_chgtype', 'errorField',
		{
			colModel: [
				{ label: 'Charge Type', name: 'chgtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true,  or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			}
		},{
			title: "Select Charge Type",
			open: function () {
				dialog_chgtype.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chgtype.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab',false
	);
	dialog_chgtype.makedialog(true);

	var dialog_chgclass= new ordialog(
		'cm_chgclass','hisdb.chgclass','#cm_chgclass',errorField,
		{	colModel:[
				{label:'Class Code',name:'classcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
		},
		{
			title:"Select Class Code",
			open: function(){
				dialog_chgclass.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chgclass.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_chgclass.makedialog(true);

	var dialog_dosage= new ordialog(
		'cm_dosecode','hisdb.dose','#cm_dosecode',errorField,
		{	colModel:[
				{label:'Code',name:'dosecode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'dosedesc',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
		},
		{
			title:"Select Dosage",
			open: function(){
				dialog_dosage.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_dosage.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_dosage.makedialog(true);


	var dialog_frequency= new ordialog(
		'cm_freqcode','hisdb.freq','#cm_freqcode',errorField,
		{	colModel:[
				{label:'Code',name:'freqcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'freqdesc',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
		},
		{
			title:"Select Frequency",
			open: function(){
				dialog_frequency.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_frequency.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_frequency.makedialog(true);


	var dialog_instruction= new ordialog(
		'cm_instruction','hisdb.instruction','#cm_instruction',errorField,
		{	colModel:[
				{label:'Code',name:'inscode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
		},
		{
			title:"Select Instruction",
			open: function(){
				dialog_instruction.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_instruction.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_instruction.makedialog(true);

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

			}else if($('#groupcode2').val()=="Consignment"){
				dialog_cat_selection = new ordialog(
					'dialog_cat_selection','material.category','#dialog_cat_selection',errorField,
					{	colModel:[
							{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						urlParam: {
							filterCol:['cattype', 'source', 'recstatus'],
							filterVal:['Consignment', 'PO', 'ACTIVE']
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
							dialog_cat_selection.urlParam.filterVal=['Consignment', 'PO', 'ACTIVE',$('#Class2').val()];
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
			var openchgprice = false;
			if($("input[type='radio'][name='chgflag'][value='1']").is(":checked")){
				openchgprice = true;
			}
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,function(){
					if(openchgprice){
						$("#addNewChgprice").dialog('open');
					}
				});
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

	var oper = 'add';
	$("#dialogForm")
	.dialog({ 
	width: 9/10 * $(window).width(),
	modal: true,
	autoOpen: false,
	open: function( event, ui ) {
		parent_close_disabled(true);
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));

		switch(oper) {
			case state = 'add':
				$( this ).dialog( "option", "title", "Add" );
				enableForm('#formdata');
				hideOne('#formdata');
				rdonly("#dialogForm");
				$('#formdataSearch input[rdonly]').prop("readonly",false);
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
			dialog_uomcode.on();
			dialog_pouom.on();
			dialog_suppcode.on();
			dialog_mstore.on();
			dialog_subcategory.on();
			dialog_taxCode.on();
			dialog_chgclass.on();
			dialog_chgtype.on();
			dialog_chggroup.on();
			dialog_dosage.on();
			dialog_frequency.on();
			dialog_instruction.on();
		}if(oper!='add'){
			showing_charges_fieldset();
			dialog_pouom.check(errorField);
			dialog_suppcode.check(errorField);
			dialog_mstore.check(errorField);
			// dialog_subcategory.check(errorField);
			dialog_taxCode.check(errorField);
		}if(oper == 'add') {
			dialog_itemcode.on();
			dialog_pouom.off();
			dialog_suppcode.off();
			dialog_mstore.off();
			dialog_subcategory.off();
			dialog_taxCode.off();
			dialog_chgclass.off();
			dialog_chgtype.off();
			dialog_chggroup.off();
			dialog_dosage.off();
			dialog_frequency.off();
			dialog_instruction.off();
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
		dialog_chgclass.off();
		dialog_chgtype.off();
		dialog_chggroup.off();
		dialog_dosage.off();
		dialog_frequency.off();
		dialog_instruction.off();

		$('.my-alert').detach();
		if(oper=='view'){
			$(this).dialog("option", "buttons",butt1);
		}

		if(oper == "add"){
			$("#cancelBut").click();
		}
	},
	buttons :butt1,
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_product',
		url:'./product/table',
		filterCol:['groupcode', 'Class'],
		filterVal:[$('#groupcode2').val(), $('#Class2').val()]
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'product/form',
		field:'',
		oper:oper,
		table_name:'material.product',
		table_id:'itemcode',
		saveip:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			//{ label: 'compcode', name: 'compcode', hidden: true},
		 	{ label: 'Unit', name: 'unit', width: 25, sorttype: 'text', classes: 'wrap'  },
			{ label: 'Item Code', name: 'itemcode', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true},
			{ label: 'Item Description', name: 'description', width: 80, sorttype: 'text', classes: 'wrap', checked:true,canSearch: true  },
			{ label: 'Uom Code', name: 'uomcode', width: 30, sorttype: 'text', classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Qty On Hand', name: 'qtyonhand', width: 40, classes: 'wrap',hidden:false,align: 'right'},
			{ label: 'Average Cost', name: 'avgcost', width: 40 ,align: 'right'},
			{ label: 'Current Cost', name: 'currprice', width: 40 ,align: 'right'},
			{ label: 'Group Code', name: 'groupcode', width: 40, sorttype: 'text', classes: 'wrap'  },
			{ label: 'Class', name: 'Class', width: 40, sorttype: 'text', classes: 'wrap', hidden:true   },
			{ label: 'Product Category', name: 'productcat', width: 40, sorttype: 'text', classes: 'wrap' ,canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Charge', name: 'chgflag', width: 25, formatter:formatter, unformat:unformat, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td'  },
			{ label: 'Supplier Code', name: 'suppcode',  hidden:true, width: 40, sorttype: 'text', classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Generic', name: 'generic', width: 40, sorttype: 'text', classes: 'wrap',canSearch: true},
			{ label: 'actavgcost', name: 'actavgcost', width: 50, hidden:true },
			{ label: 'bonqty', name: 'bonqty', width: 50, hidden:true },
			{ label: 'rpkitem', name: 'rpkitem', width: 50, hidden:true },
			{ label: 'minqty', name: 'minqty', width: 50, hidden:true },
			{ label: 'maxqty', name: 'maxqty', width: 50, hidden:true },
			{ label: 'reordlevel', name: 'reordlevel', width: 50, hidden:true },
			{ label: 'reordqty', name: 'reordqty', width: 50, hidden:true },
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
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
			{ label: 'cm_packqty', name: 'cm_packqty', width: 50, hidden:true },
			{ label: 'cm_druggrcode', name: 'cm_druggrcode', width: 50, hidden:true },
			{ label: 'cm_subgroup', name: 'cm_subgroup', width: 50, hidden:true },
			{ label: 'cm_stockcode', name: 'cm_stockcode', width: 50, hidden:true },
			{ label: 'cm_chgclass', name: 'cm_chgclass', width: 50, hidden:true },
			{ label: 'cm_chggroup', name: 'cm_chggroup', width: 50, hidden:true },
			{ label: 'cm_chgtype', name: 'cm_chgtype', width: 50, hidden:true },
			{ label: 'cm_invgroup', name: 'cm_invgroup', width: 50, hidden:true },
			{ label: 'cm_instruction', name: 'cm_instruction', width: 50, hidden:true },
			{ label: 'cm_freqcode', name: 'cm_freqcode', width: 50, hidden:true },
			{ label: 'cm_dosecode', name: 'cm_dosecode', width: 50, hidden:true },
			{ label: 'idno', name: 'idno', hidden: true},
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
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

			let chgflg = selrowData("#jqGrid").chgflag;

			if(selrowData('#jqGrid').chgflag == '1'){
				urlParam2.filterVal[2]=selrowData("#jqGrid").itemcode;
				urlParam2.filterVal[3]=selrowData("#jqGrid").uomcode;

				refreshGrid("#jqGrid3",urlParam2,'add');
				hideatdialogForm_jqGrid3(false);
				$("#jqGrid3_c").show();
				$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
				$("#print_barcode").show();

				// $("#print_barcode").attr('href','./product/table?action=print_barcode&itemcode='+selrowData("#jqGrid").itemcode);

			} else {
				$("#jqGrid3_c").hide();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();

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
					$('#formdata input[name=computerid]').prop("readonly",true);
					$('#itemcodesearch').prop("readonly",true);
					$('#uomcodesearch').prop("readonly",true);

					getgcforAdd();
				}
			}
		},
		
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	////////////////////unformatter status////////////////////////////////////////
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

	function formatter(cellvalue, options, rowObject){
		return parseInt(cellvalue) ? "Yes" : "No";
	}

	function unformat(cellvalue, options){
		//return parseInt(cellvalue) ? "Yes" : "No";

		if (cellvalue == 'Yes') {
			return "1";
		}
		else {
			return "0";
		}
	}
	////////////////////////////formatter//////////////////////////////////////////////////////////

	function readonlyRTTrue(){
		$('#formdata input[rdonly]').prop("readonly",true);
		$('#formdata input[type=radio]').prop("disabled",true);
	}

	function readonlyRTFalse(){
		$('#formdata input[rdonly]').prop("readonly",false);
		$('#formdata input[type=radio]').prop("disabled",false);
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
			$("#itemcode_hidden").val($('#itemcodesearch').val());
			$("#uomcode_hidden").val($('#uomcodesearch').val());
			urlParam2.filterVal[2]=$('#itemcodesearch').val();
			urlParam2.filterVal[3]=$('#uomcodesearch').val();

			dialog_itemcode.off();
			dialog_uomcode.off();

			dialog_pouom.on();
			dialog_suppcode.on();
			dialog_mstore.on();
			// dialog_subcategory.on();
			dialog_taxCode.on();
			dialog_chgclass.on();
			dialog_chgtype.on();
			dialog_chggroup.on();
			dialog_dosage.on();
			dialog_frequency.on();
			dialog_instruction.on();

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

			get_product_detail();
			$("#formdata input[name='TaxCode']").val('EP');
			dialog_taxCode.check(errorField);
		}
	});

	$("#cancelBut").click(function(){
		emptyFormdata(errorField,'#formdataSearch');
		emptyFormdata(errorField,'#formdata');
		forCancelAndExit();
		$("#itemcodesearch").focus();
	});

	function showing_charges_fieldset(){
		if($("input[type='radio'][name='chgflag'][value='1']").is(":checked")){
			$('#charges_fieldset').show();

			var param={
				action:'get_charges_from_product',
				url: './product/table',
				chgcode:$('#itemcode').val(),
				uomcode:$('#uomcode').val()
			}
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.chgmast)){
					$('#cm_packqty').val(data.chgmast.packqty);
					$('#cm_druggrcode').val(data.chgmast.druggrcode);
					$('#cm_subgroup').val(data.chgmast.subgroup);
					$('#cm_stockcode').val(data.chgmast.stockcode);
					$('#cm_chgclass').val(data.chgmast.chgclass);
					$('#cm_dosecode').val(data.chgmast.dosecode);
					$('#cm_chggroup').val(data.chgmast.chggroup);
					$('#cm_freqcode').val(data.chgmast.freqcode);
					$('#cm_chgtype').val(data.chgmast.chgtype);
					$('#cm_instruction').val(data.chgmast.instruction);
					$('#cm_invgroup').val(data.chgmast.invgroup);

					dialog_chgclass.check(errorField);
					dialog_chgtype.check(errorField);
					dialog_chggroup.check(errorField);
					dialog_dosage.check(errorField);
					dialog_frequency.check(errorField);
					dialog_instruction.check(errorField);
				}
			});


		}else{
			$('#charges_fieldset').hide();
		}
	}

	$("input[type='radio'][name='chgflag']").on('change', function(){
		showing_charges_fieldset();
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

	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'productcat':
				if($('#groupcode2') == "Asset"){
					field=['assetcode','description'];
					table="finance.facode";
					case_='productcat';
				}else{
					field=['catcode','description'];
					table="material.category";
					case_='productcat';
				}

				break;
				
			case 'suppcode':field=['SuppCode','Name'];table="material.supplier";case_='suppcode';break;
			case 'iptax':field=['taxcode','description'];table="hisdb.taxmast";case_='iptax';break;
			case 'optax': field = ['taxcode', 'description']; table = "hisdb.taxmast";case_='optax';break;
			case 'uom': field = ['uomcode', 'description']; table = "material.uom";case_='uom';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('product',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
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
				if(parseInt(selrowData('#jqGrid').qtyonhand) > 0){
					alert('Cannot deactive product with quantity on hand')
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, {'idno':selrowData('#jqGrid').idno});
				}
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
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
			recstatusDisable();
			$("#uomcode_hidden").val(selrowData("#jqGrid").uomcode);
			$('#itemcode_hidden').val(selrowData("#jqGrid").itemcode);
			urlParam2.filterVal[2]=selrowData("#jqGrid").itemcode;
			urlParam2.filterVal[3]=selrowData("#jqGrid").uomcode;
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
				case 'Consignment': $("#div_product_infront_others").show(); break;
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
	addParamField('#jqGrid',false,saveParam,['idno', 'adduser', 'adddate', 'upduser', 'upddate', 'recstatus', 'computerid']);

	////////////////////////////////////////////////////////addNewProduct ///////////////////////////////
	var adpsaveParam={
		action:'save_productmaster',
		url:'product/form',
		field:['itemcode','description','groupcode', 'productcat', 'Class', 'computerid'],
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

	dialog_cat = null;
	$("#addNewProductDialog")
		.dialog({
		width: 6/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			rdonly("#addNewProductDialog");

			dialog_uomcode_addnew.on();
			if($('#groupcode2').val()=="Stock" && $('#othergrid_productcatAddNew1').length == 0){
				if(dialog_cat == null){
					let dialog_cat = new ordialog(
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
								dialog_cat.urlParam.filterCol=['cattype', 'source', 'recstatus'];
								dialog_cat.urlParam.filterVal=['Stock', 'PO', 'ACTIVE'];
							}
						},'urlParam'
					);
					dialog_cat.makedialog();
					dialog_cat.on();
				}

			} else if($('#groupcode2').val()=="Asset" && $('#othergrid_productcatAddNew2').length == 0) {
				if(dialog_cat == null){
					let dialog_cat = new ordialog(
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
								dialog_cat.urlParam.filterCol=['recstatus'];
								dialog_cat.urlParam.filterVal=['ACTIVE'];
							}
						},'urlParam'
					);
					dialog_cat.makedialog();
					dialog_cat.on();
				}
			} else if($('#groupcode2').val()=="Others" && $('#othergrid_productcatAddNew3').length == 0) {
				if(dialog_cat == null){
					let dialog_cat = new ordialog(
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
								dialog_cat.urlParam.filterCol=['cattype', 'source', 'recstatus'];
								dialog_cat.urlParam.filterVal=['Other', 'PO', 'ACTIVE'];
							}
						},'urlParam'
					);
					dialog_cat.makedialog();
					dialog_cat.on();
				}
			} else if($('#groupcode2').val()=="Consignment" && $('#othergrid_productcatAddNew4').length == 0){
				if(dialog_cat == null){
					let dialog_cat = new ordialog(
						'productcatAddNew4','material.category','#productcatAddNew_consign',errorField,
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
								// var gc2 = $('#groupcode2').val();
								dialog_cat.urlParam.filterCol=['cattype', 'source', 'recstatus'];
								dialog_cat.urlParam.filterVal=['Stock', 'PO', 'ACTIVE'];
							}
						},'urlParam'
					);
					dialog_cat.makedialog();
					dialog_cat.on();
				}

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

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Inpatient Tax':temp=$("input[name='iptax']");break;
			case 'Outpatient Tax':temp=$("input[name='optax']");break;
			case 'Charge Code':temp=$("input[name='chgcode']");break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	var mycurrency2 =new currencymode([]);
	var addmore_jqgrid2={more:false,state:false,edit:false}
	var urlParam2={
			action:'get_table_default',
			url:'util/get_table_default',
			field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.optax','cp.adduser','cp.adddate','cp.chgcode','cm.chgcode','cp.idno','cp.autopull','cp.addchg','cp.pkgstatus','cp.recstatus','cp.uom'],
			table_name:['hisdb.chgprice AS cp', 'hisdb.chgmast AS cm'],
			table_id:'lineno_',
			join_type:['LEFT JOIN'],
			join_onCol:['cp.chgcode'],
			join_onVal:['cm.chgcode'],
	        join_filterCol : [['cm.uom on =']],
	        join_filterVal : [['cp.uom']],
			filterCol:['cp.compcode','cp.unit','cp.chgcode','cp.uom'],
			filterVal:['session.compcode','session.unit','','']
		};


	$("#addNewChgprice").dialog({
		width: 8/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			refreshGrid('#jqGrid2',urlParam2,'add');
			hideatdialogForm(false);
		},
		close: function(event, ui){
		}
	});

	$("#jqGrid2").jqGrid({
			datatype: "local",
			editurl: "./chargemasterDetail/form",
			colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
				{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
	                    dataInit: function (element) {
	                        $(element).datepicker({
	                            id: 'expdate_datePicker',
	                            dateFormat: 'dd/mm/yy',
	                            minDate: "dateToday",
	                            showOn: 'focus',
	                            changeMonth: true,
			  					changeYear: true,
			  					onSelect : function(){
			  						$(this).focus();
			  					}
	                        });
	                    }
	                }
				},
				{ label: 'Inpatient Tax', name: 'iptax', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:iptaxCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				},
				{ label: 'Outpatient Tax', name: 'optax', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:optaxCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				},
				{ label: 'Price 1', name: 'amt1', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 2', name: 'amt2', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Price 3', name: 'amt3', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'Cost Price', name: 'costprice', width: 150, align: 'right', classes: 'wrap', editable:true,
					edittype:"text",
					editoptions:{
						maxlength: 100,
					},
				},
				{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
	                    dataInit: function (element) {
	                        $(element).attr('disabled','true');
	                        $(element).val($('#uomcode_hidden').val());
	                    }
				}},
				{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
	                    dataInit: function (element) {
	                    	if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
	                        $(element).attr('disabled','true');
	                    }
				}},
				{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true}
			],
			autowidth: true,
			shrinkToFit: true,
			multiSort: true,
			viewrecords: true,
			loadonce:false,
			width: 1150,
			height: 200,
			rowNum: 30,
			sortname: 'idno',
			sortorder: "desc",
			pager: "#jqGridPager2",
			loadComplete: function(){
				if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
				else{
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}

				addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			},
			gridComplete: function(){
				fdl.set_array().reset();
				
			},
			beforeSubmit: function(postdata, rowid){ 
				// dialog_deptcodedtl.check(errorField);
		 	}
		});

		function iptaxCustomEdit(val, opt) {
			val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		function optaxCustomEdit(val, opt) {
			val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}

		//////////////////////////////////////myEditOptions for jqGrid2/////////////////////////////////////////

		var myEditOptions = {
	        keys: true,
	        extraparam:{
			    "_token": $("#_token").val()
	        },
	        oneditfunc: function (rowid) {

	        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

	        	dialog_optax.on();
				dialog_iptax.on();

	        	unsaved = false;
				mycurrency2.array.length = 0;
				Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amt1']","#jqGrid2 input[name='amt2']","#jqGrid2 input[name='amt3']","#jqGrid2 input[name='costprice']"]);

				mycurrency2.formatOnBlur();//make field to currency on leave cursor

	        	$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid2_ilsave').click();
				})
	        },
	        aftersavefunc: function (rowid, response, options) {
	        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
	        }, 
	        errorfunc: function(rowid,response){
				$(".noti").text(response.responseText);
	        	// alert();
	        	refreshGrid('#jqGrid2',urlParam2,'add');
		    	$("#jqGridPager2Delete").show();
	        },
	        beforeSaveRow: function(options, rowid) {

	        	//if(errorField.length>0)return false;  

				let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
				let editurl = "./chargemasterDetail/form?"+
					$.param({
						action: 'chargemasterDetail_save',
						oper: 'add',
						chgcode: $('#itemcode_hidden').val(),
						uom: $('#uomcode_hidden').val(),
						_token: $("#_token").val()

						// authorid:$('#authorid').val()
					});
				$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
	        },
	        afterrestorefunc : function( response ) {
				hideatdialogForm(false);
		    }
	    };

		//////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////

		$("#jqGrid2").inlineNav('#jqGridPager2',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions
			},
			editParams: myEditOptions
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2Delete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
				if(!selRowId){
					bootbox.alert('Please select row');
				}else{
					bootbox.confirm({
					    message: "Are you sure you want to delete this row?",
					    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					    },
					    callback: function (result) {
					    	if(result == true){
					    		param={
					    			action: 'chargemasterDetail_save',
									idno: selrowData('#jqGrid2').idno,

					    		}
					    		$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#jqGrid2",urlParam2);
								});
					    	}else{
	        					$("#jqGridPager2EditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2EditAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-th-list",
			title:"Edit All Row",
			onClickButton: function(){
				mycurrency2.array.length = 0;
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
			    for (var i = 0; i < ids.length; i++) {

			        $("#jqGrid2").jqGrid('editRow',ids[i]);

			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			    }
			    mycurrency2.formatOnBlur();
				hideatdialogForm(true,'saveallrow');
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2SaveAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-download-alt",
			title:"Save All Row",
			onClickButton: function(){
				var ids = $("#jqGrid2").jqGrid('getDataIDs');

				var jqgrid2_data = [];
				mycurrency2.formatOff();
			    for (var i = 0; i < ids.length; i++) {

					var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
			    	var obj = 
			    	{
			    		'idno' : data.idno,
			    		'effdate' : $("#jqGrid2 input#"+ids[i]+"_effdate").val(),
						'amt1' : $("#jqGrid2 input#"+ids[i]+"_amt1").val(),
						'amt2' : $("#jqGrid2 input#"+ids[i]+"_amt2").val(),
						'amt3' : $("#jqGrid2 input#"+ids[i]+"_amt3").val(),
						'costprice' : $("#jqGrid2 input#"+ids[i]+"_costprice").val(),
						'iptax' : $("#jqGrid2 input#"+ids[i]+"_iptax").val(),
						'optax' : $("#jqGrid2 input#"+ids[i]+"_optax").val()
			    	}

			    	jqgrid2_data.push(obj);
			    }

				var param={
	    			action: 'chargemasterDetail_save',
					_token: $("#_token").val()
	    		}

	    		$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
				}).fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					hideatdialogForm(false);
					refreshGrid("#jqGrid2",urlParam2);
				});
			},	
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "jqGridPager2CancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			},	
		});

		var dialog_iptax = new ordialog(
			'iptax','hisdb.taxmast',"#jqGrid2 input[name='iptax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['ACTIVE', 'session.compcode','Output']
						},
				ondblClickRow:function(){
					$('#optax').focus();
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
			},{
				title:"Select Tax Master",
				open: function(){
					dialog_iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_iptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
				},
				close: function(){
					$("#jqGrid2 input[name='optax']").focus();
				}
			},'urlParam','radio','tab'
		);
				
		dialog_iptax.makedialog();

		var dialog_optax = new ordialog(
			'optax','hisdb.taxmast',"#jqGrid2 input[name='optax']",errorField,
			{	colModel:[
					{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
					{label:'Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
					filterCol:['recstatus','compcode','taxtype'],
					filterVal:['ACTIVE', 'session.compcode','Output']
						},
				ondblClickRow:function(){
					$('#amt1').focus();
				},
				gridComplete: function(obj){
							var gridname = '#'+obj.gridname;
							if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
								$(gridname+' tr#1').click();
								$(gridname+' tr#1').dblclick();
								$('#amt1').focus();
							}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
								$('#'+obj.dialogname).dialog('close');
							}
						}
			},{
				title:"Select Tax Master",
				open: function(){
					dialog_optax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
					dialog_optax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
				},
				close: function(){
					$("#jqGrid2 input[name='amt1']").focus();
				}
			},'urlParam','radio','tab'
		);
		dialog_optax.makedialog();

		function hideatdialogForm(hide,saveallrow){
			if(saveallrow == 'saveallrow'){
				$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
				$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();

			}else if(hide){
				$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
				$("#saveDetailLabel").show();
			}else{
				$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
				$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
			}
			
		}

	function onall_editfunc(jqgrid="none"){
		dialog_dtliptax.on();
		dialog_dtloptax.on();

		mycurrency2.formatOnBlur();//make field to currency on leave cursor

	}
	////////////////////////////////////////////////jqgrid3 charge price//////////////////////////////////////////////
	var addmore_jqgrid3={more:false,state:true,edit:false}

	$("#jqGrid3").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Inpatient Tax', name: 'iptax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:iptax3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Outpatient Tax', name: 'optax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:optax3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Price 1', name: 'amt1', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 2', name: 'amt2', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 3', name: 'amt3', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Cost Price', name: 'costprice', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},				
			{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
					dataInit: function (element) {
						$(element).attr('disabled','true');
						$(element).val(selrowData('#jqGrid').uomcode);
					}
			}},
			{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
					dataInit: function (element) {
						if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
						$(element).attr('disabled','true');
					}
			}},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},

		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(){
			if(addmore_jqgrid3.more == true){$('#jqGrid3_iladd').click();}
			else{
				$('#jqGrid3').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid3.edit = addmore_jqgrid3.more = false; //reset
		},
		gridComplete: function(){

			fdl.set_array().reset();
			if(!hide_init){
				hide_init=1;
				hideatdialogForm_jqGrid3(false);
			}
		}
	});
	var hide_init=0;

	jqgrid_label_align_right("#jqGrid3");

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	//////////////////////////////////////////myEditOptions2 for Charge Price/////////////////////////////////////////////
	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").hide();

			dialog_dtliptax.on();
			dialog_dtloptax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='amt1']","#jqGrid3 input[name='amt2']","#jqGrid3 input[name='amt3']","#jqGrid3 input[name='costprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid3_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid3.state==true)addmore_jqgrid3.more=true; //only addmore after save inline
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert(response.responseText);
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3Delete,#jqGridPager3Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: selrowData('#jqGrid').itemcode,//$('#chgcode').val(),
					uom: selrowData('#jqGrid').uomcode//$('#uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGrid3(false);
		}
	};

	//////////////////////////////////////////pager jqgrid3/////////////////////////////////////////////
	$("#jqGrid3").inlineNav('#jqGridPager3',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions2
		},
		editParams: myEditOptions2
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if(result == true){
							param={
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGrid3').idno,

							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGrid3",urlParam2);
							});
						}else{
							$("#jqGridPager3EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid3").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGrid3").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			}
			mycurrency2.formatOnBlur();
			onall_editfunc();
			hideatdialogForm_jqGrid3(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid3").jqGrid('getDataIDs');

			var jqgrid3_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid3').jqGrid('getRowData',ids[i]);
				var obj = 
				{
					'idno' : data.idno,
					'effdate' : $("#jqGrid3 input#"+ids[i]+"_effdate").val(),
					'amt1' : $("#jqGrid3 input#"+ids[i]+"_amt1").val(),
					'amt2' : $("#jqGrid3 input#"+ids[i]+"_amt2").val(),
					'amt3' : $("#jqGrid3 input#"+ids[i]+"_amt3").val(),
					'costprice' : $("#jqGrid3 input#"+ids[i]+"_costprice").val(),
					'iptax' : $("#jqGrid3 input#"+ids[i]+"_iptax").val(),
					'optax' : $("#jqGrid3 input#"+ids[i]+"_optax").val()
				}

				jqgrid3_data.push(obj);
			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val()
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid3_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGrid3(false);
				refreshGrid("#jqGrid3",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGrid3(false);
			refreshGrid("#jqGrid3",urlParam2);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		id: "jqGridPager3Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid3", urlParam2);
		},
	});

	var dialog_dtliptax = new ordialog(
		'dtl_iptax','hisdb.taxmast',"#jqGrid3 input[name='iptax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#dtl_optax').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#dtl_optax').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_dtliptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_dtliptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid3 input[name='optax']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtliptax.makedialog();

	var dialog_dtloptax = new ordialog(
		'dtl_optax','hisdb.taxmast',"#jqGrid3 input[name='optax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#amt1').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#amt1').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_dtloptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_dtloptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid3 input[name='amt1']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtloptax.makedialog();

	///chargeprice///
	function hideatdialogForm_jqGrid3(hide,saveallrow){
		if(saveallrow == 'saveallrow'){

			$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").hide();
			$("#jqGridPager3SaveAll,#jqGridPager3CancelAll").show();
		}else if(hide){

			$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3SaveAll,#jqGridPager3CancelAll,#jqGridPager3Refresh").hide();
		}else{

			$("#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").show();
			$("#jqGridPager3SaveAll,#jqGrid3_iledit,#jqGridPager3CancelAll").hide();
		}
		
	}

	function iptax3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function optax3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

});

function get_product_detail(){
	var param={
		action:'get_product_detail',
		url:'./product/table',
		itemcode:$('#itemcodesearch').val(),
		uomcode:$('#uomcodesearch').val(),
		groupcode:$('#groupcode2').val(),
		Class:$('#Class2').val()
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			if(data.error == true){
				alert(data.msg);
				disableForm('#formdata');
			}else{
				$("#formdata :input[name='description']").val(data.productmaster.description);
				$("#formdata :input[name='productcat']").val(data.productmaster.productcat);
				
				$("#formdata [name=groupcode][value='"+data.productmaster.groupcode+"']").prop('checked', true);
				$("#formdata [name=Class][value='"+data.productmaster.Class+"']").prop('checked', true);
			}
		}
	});
}