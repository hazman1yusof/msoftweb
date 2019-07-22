
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
				{label:'Dept Code',name:'itemcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
				{label:'groupcode',name:'groupcode',hidden:true},
				{label:'productcat',name:'productcat',hidden:true}
			],
			ondblClickRow:function(){
				data = selrowData('#'+dialog_itemcode.gridname);
				productcat=data.productcat;
				groupcode=data.groupcode;
				description=data.description;
				Class=data.Class;
			}	
		},{
			title:"Select Item Code",
			open: function(){

				var gc2 = $('#groupcode2').val();
				var Class2 = $('#Class2').val();
				dialog_itemcode.urlParam.filterCol = ['groupcode', 'Class','recstatus','compcode'];
				dialog_itemcode.urlParam.filterVal = [ gc2, Class2,'A','session.compcode'];

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
			ondblClickRow:function(){
			}	
		},{
			title:"Select UOM Code",
			open: function(){
				dialog_uomcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_uomcode.urlParam.filterVal = [ 'A','session.compcode'];	
			}
		},'urlParam'
	);
	dialog_uomcode.makedialog();


	var dialog_pouom = new ordialog(
		'pouom','material.uom','#pouom',errorField,
		{	colModel:[
				{label:'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			ondblClickRow:function(){
			}	
		},{
			title:"Select PO UOM",
			open: function(){
				dialog_pouom.urlParam.filterCol = ['recstatus','compcode'];
				dialog_pouom.urlParam.filterVal = [ 'A','session.compcode'];	
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
			ondblClickRow:function(){
			}	
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_suppcode.urlParam.filterVal = [ 'A','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_suppcode.makedialog();

	var dialog_mstore = new ordialog(
		'mstore','sysdb.department','#mstore',errorField,
		{	colModel:[
				{label:'Dept Code',name:'deptcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			ondblClickRow:function(){
			}	
		},{
			title:"Select Main Store",
			open: function(){
				dialog_mstore.urlParam.filterCol = ['mainstore','recstatus','compcode','sector'];
				dialog_mstore.urlParam.filterVal = ['1','A','session.compcode','session.unit'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_mstore.makedialog();

	var dialog_subcategory = new ordialog(
		'subcatcode','material.subcategory','#subcatcode',errorField,
		{	colModel:[
				{label:'Dept Code',name:'subcatcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			ondblClickRow:function(){
			}	
		},{
			title:"Select Sub Category",
			open: function(){
				dialog_subcategory.urlParam.filterCol = ['recstatus','compcode'];
				dialog_subcategory.urlParam.filterVal = [ 'A','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_subcategory.makedialog();

	var dialog_taxCode = new ordialog(
		'TaxCode','hisdb.taxmast','#TaxCode',errorField,
		{	colModel:[
				{label:'Tax Code',name:'taxcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			ondblClickRow:function(){
			}	
		},{
			title:"Select Tax Code",
			open: function(){
				dialog_taxCode.urlParam.filterCol=['recstatus','taxtype','compcode'];
				dialog_taxCode.urlParam.filterVal=['A','Input','session.compcode'];
			}
		},'urlParam'
	);
	dialog_taxCode.makedialog();

	////////////////////////////////////start dialog////////////////////////////////////////////////////

	var butt1=[{
		id: "Save",
		text: "Save",click: function() {
			radbuts.check();
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
				urlParam.filterCol=['groupcode', 'Class'];
				urlParam.filterVal=[$('#groupcode2').val(), $('#Class2').val()];
				refreshGrid('#jqGrid',urlParam);
			}
		}
	},{
		id: "Cancel",
		text: "Cancel",click: function() {
			emptyFormdata(errorField,'#formdataSearch');
			emptyFormdata(errorField,'#formdata');
			forCancelAndExit();
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
				dialog_itemcode.on();
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
			refreshGrid('#jqGrid',urlParam);

			dialog_pouom.off();
			dialog_suppcode.off();
			dialog_mstore.off();
			dialog_subcategory.off();
			dialog_taxCode.off();

			$('#formdata .alert').detach();
			$("#formdata a").off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
			
			forCancelAndExit();
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
		saveip:'true'
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
			{ label: 'Product Category', name: 'productcat', width: 40, sorttype: 'text', classes: 'wrap'  },
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
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatter, unformat:unformat,  cellattr: function(rowid, cellvalue)
			{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
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
	function formatter(cellvalue, options, rowObject){
		if(cellvalue == 'A'){
			return "Active";
		}
		if(cellvalue == 'D') { 
			return "Deactive";
		}
	}

	function  unformat(cellvalue, options){
		if(cellvalue == 'Active'){
			return "Active";
		}
		if(cellvalue == 'Deactive') { 
			return "Deactive";
		}
	}

	function readonlyRTTrue(){
		$('#formdata input[rdonly]').prop("readonly",true);
		$('#formdata  input[type=radio]').prop("disabled",true);
	}

	function readonlyRTFalse(){
		$('#formdata input[rdonly]').prop("readonly",false);
		$('#formdata  input[type=radio]').prop("disabled",false);
	}

	function whenAdd() {
		$('#formdataSearch').show();
		$("#Save").hide();

		$("#formdata label[for=itemcode]").hide();
		$("#itemcode_parent").hide();
		$("#formdata label[for=description]").hide();
		$("#description_parent").hide();
		$("#formdata label[for=uomcode]").hide();
		$("#uomcode_parent").hide();
	}

	function whenEdit() {
		$('#formdataSearch').hide();
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
				$(":radio[name="+value+"]:not(:checked)").hide();
				$(":radio[name="+value+"]:not(:checked)").parent('label').hide();
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
		$("#formdata [name=groupcode][value='"+gc2+"']").prop('checked', true);
		$("#formdata [name=Class][value='"+Class2+"']").prop('checked', true);
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
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selrowData('#jqGrid').idno});
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
			$('#formdataSearch').hide();
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
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno', 'adduser', 'adddate', 'upduser', 'upddate', 'recstatus', 'computerid', 'ipaddress']);

	////////////////////////////////////////////////////////addNewProduct ///////////////////////////////
	var adpsaveParam={
		action:'save_table_default',
		url:'product/form',
		field:['itemcode','description','groupcode', 'productcat', 'Class', 'computerid', 'ipaddress'],
		oper:'add',
		table_name:'material.productmaster',
		table_id:'itemcode',
		saveip:'true'
	};

	var addNew=[{
		id: 'addnp',
		text: "Add New",click: function() {
			$("#addNewProductDialog" ).dialog( "open" );

			$("#adpFormdata [name=groupcode][value='"+gc2+"']").prop('checked', true);
			$("#adpFormdata [name=Class][value='"+Class2+"']").prop('checked', true);
			$('#adpFormdata [type=radio]:not(:checked)').hide();
			$('#adpFormdata [type=radio]:not(:checked)').parent('label').hide();

			if(gc2=="Stock"){
				var dialog_cat = new ordialog(
					'productcatAddNew','material.category','#productcatAddNew',errorField,
					{	colModel:[
							{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
						],
						ondblClickRow:function(){
						}	
					},{
						title:"Select Product Category",
						open: function(){
							var gc2 = $('#groupcode2').val();
							dialog_cat.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_cat.urlParam.filterVal=['Stock', 'PO', 'A'];
						}
					},'urlParam'
				);
				dialog_cat.makedialog();
				dialog_cat.on();

			} else if(gc2=="Asset") {
				var dialog_cat = new ordialog(
					'productcatAddNew','finance.facode','#productcatAddNew',errorField,
					{	colModel:[
							{label:'Category Code',name:'assetcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						ondblClickRow:function(){
						}	
					},{
						title:"Select Product Category",
						open: function(){
							dialog_cat.urlParam.filterCol=['recstatus'];
							dialog_cat.urlParam.filterVal=['A'];
						}
					},'urlParam'
				);
				dialog_cat.makedialog();
				dialog_cat.on();

			} else if(gc2=="Others") {
				var dialog_cat = new ordialog(
					'productcatAddNew','material.category','#productcatAddNew',errorField,
					{	colModel:[
							{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
						],
						ondblClickRow:function(){
						}	
					},{
						title:"Select Product Category",
						open: function(){
							dialog_cat.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_cat.urlParam.filterVal=['Other', 'PO', 'A'];
						}
					},'urlParam'
				);
				dialog_cat.makedialog();
				dialog_cat.on();
			}
		}
	}];

	$( "#"+dialog_itemcode.dialogname ).dialog( "option", "buttons", addNew);

	var addNew2=[{
		text: "Save",click: function() {
			if( $('#adpFormdata').isValid({requiredFields: ''}, {}, true) ) {
				saveFormdata("#"+dialog_itemcode.dialogname,"#addNewProductDialog","#adpFormdata",'add',adpsaveParam,dialog_itemcode.urlParam);
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
		},
		close: function( event, ui ) {
			emptyFormdata([],'#adpFormdata');
			$('.alert').detach();
			$("#adpFormdata a").off();
		},
		buttons :addNew2,
	});

});
