	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	var editedRow=0;

	$(document).ready(function () {
		$('body').show();
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
					if(errorField.length>0){
						console.log(errorField);
						return {
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};
		//////////////////////////////////////////////////////////////
			
	////////////////////////////////////start dialog///////////////////////////////////////

	var mycurrency =new currencymode(['#origcost','#currentcost', '#purprice']);
	var fdl = new faster_detail_load();
	var cbselect = new checkbox_selection("#jqGrid","Checkbox");

	var dialog_assetcode= new ordialog(
		'assetcode','finance.facode','#assetcode',errorField,
		{	colModel:[
				{label:'Asset Code',name:'assetcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Asset Type',name:'assettype',width:100,classes:'pointer',hidden:true},
				{label:'Method',name:'method',width:100,classes:'pointer',hidden:true},
				{label:'Residual Value',name:'residualvalue',width:100,classes:'pointer',hidden:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_assetcode.gridname);
				$('#assettype').val(data['assettype']);		
				$('#method').val(data['method']);
				$('#rvalue').val(data['residualvalue']);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#deptcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Category",
			open: function(){
				dialog_assetcode.urlParam.filterCol=['compcode'];
				dialog_assetcode.urlParam.filterVal=['session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_assetcode.makedialog();

	var dialog_deptcode= new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
			    {label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#loccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode'],
				dialog_deptcode.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog();

	var dialog_loccode= new ordialog(
		'loccode','sysdb.location','#loccode',errorField,
		{	colModel:[
				{label:'Location Code',name:'loccode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			sortname:'idno',
			sortorder:'desc',
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#regtype').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Location",
			open: function(){
				dialog_loccode.urlParam.filterCol=['compcode'];
				dialog_loccode.urlParam.filterVal=['session.compcode'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_loccode.makedialog();		

	var dialog_suppcode= new ordialog(
		'suppcode','material.supplier','#suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'name',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_suppcode.gridname);
				dialog_invno.urlParam.filterCol=['compcode','suppcode'];
				dialog_invno.urlParam.filterVal=['session.compcode',data.suppcode];
				dialog_delordno.urlParam.filterVal[2]=data.suppcode;
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#delordno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Supplier",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['compcode'],
				dialog_suppcode.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcode.makedialog();

	var dialog_delordno= new ordialog(
		'delordno',['material.delordhd as dohd','finance.apacthdr as ap'],'#delordno',errorField,
		{	colModel:[
				{label:'Delivery Order No',name:'dohd_delordno',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Supplier Code',name:'dohd_suppcode',width:300,classes:'pointer',canSearch:true,or_search:true},
				{label:'dohd_recno',name:'dohd_recno',width:100,classes:'pointer',hidden:true},
				{label:'Delivery Date',name:'dohd_deliverydate',width:100,classes:'pointer',hidden:true},
				{label:'Document No',name:'dohd_docno',width:100,classes:'pointer',hidden:true},
				{label:'Invoice No',name:'dohd_invoiceno',width:100,classes:'pointer',hidden:false},
				{label:'Transaction Date',name:'dohd_trandate',width:100,classes:'pointer',hidden:false},
				{label:'AP Actual Date',name:'ap_actdate',width:100,classes:'pointer',hidden:false},
			],
			urlParam: {
				filterCol:['dohd.compcode','dohd.invoiceno','dohd.suppcode'],
				filterVal:['session.compcode','<>.NULL', $("#suppcode").val()]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_delordno.gridname);
				$('#delorddate').val(data['dohd_deliverydate']);		
				$('#docno').val(data['dohd_docno']);
				$('#purdate').val(data['dohd_trandate']);
				$('#suppcode').val(data['dohd_suppcode']);
				$('#invno').val(data['dohd_invoiceno']);
				$('#invdate').val(data['ap_actdate']);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#itemcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Delordno",
			open: function(){
				dialog_delordno.urlParam.filterCol=['dohd.compcode','dohd.invoiceno','dohd.suppcode'];
				dialog_delordno.urlParam.filterVal=['session.compcode','<>.NULL', $("#suppcode").val()];
				dialog_delordno.urlParam.fixPost = "true";
				dialog_delordno.urlParam.table_id = "none_";
				dialog_delordno.urlParam.join_type = ['LEFT JOIN'];
				dialog_delordno.urlParam.join_onCol = ['dohd.invoiceno'];
				dialog_delordno.urlParam.join_onVal = ['ap.document'];
				
				dialog_delordno.urlParam.url = "/assetregister/table";
				dialog_delordno.urlParam.suppcode = $("#suppcode").val();
			}
		},'urlParam','radio','tab'
	);
	dialog_delordno.makedialog();

	var dialog_invno= new ordialog(
		'invno','finance.apacthdr','#invno',errorField,
		{	colModel:[
			    {label:'Invoice No',name:'document',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Supplier Code',name:'suppcode',width:300,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','source','trantype','suppcode','document','recstatus'],
				filterVal:['session.compcode','AP','IN', $("#suppcode").val(),$("#invno").val(),'POSTED']
			},
			ondblClickRow: function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#delorddate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select invno",
			open: function(){
				dialog_invno.urlParam.filterCol=['compcode','source','trantype','suppcode','document','recstatus'];
				dialog_invno.urlParam.filterVal=['session.compcode','AP','IN', $("#suppcode").val(),$("#invno").val(),'POSTED']
			}
		},'urlParam','radio','tab'
	);
	dialog_invno.makedialog();

	var dialog_itemcode= new ordialog(
		'itemcode',['material.delorddt as dodt','material.productmaster as p'],'#itemcode',errorField,
		{	colModel:[
				{label:'Item Code',name:'dodt_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'p_description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'dodt_uomcode',name:'dodt_uomcode',width:100,classes:'pointer',hidden:true},
				{label:'Dodt Remarks',name:'dodt_remarks',width:100,classes:'pointer',hidden:false},
				{label:'dodt_qtydelivered',name:'dodt_qtydelivered',width:100,classes:'pointer',hidden:true},
				{label:'dodt_unitprice',name:'dodt_unitprice',width:100,classes:'pointer',hidden:true},
				{label:'dodt_amount',name:'dodt_amount',width:100,classes:'pointer',hidden:true},
				{label:'dodt_srcdocno',name:'dodt_srcdocno',width:100,classes:'pointer',hidden:true},
				{label:'dodt_lineno_',name:'dodt_lineno_',width:100,classes:'pointer',hidden:true},				
			],
			urlParam: {
				filterCol:['dodt.compcode','dodt.recno'],
				filterVal:['session.compcode','']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode.gridname);
				$('#uomcode').val(data['dodt_uomcode']);
				$('#purordno').val(data['dodt_srcdocno']);
				$('#purprice').val(data['dodt_unitprice']);
				$('#qty').val(data['dodt_qtydelivered']);
				$('#currentcost').val(data['dodt_amount']);
				$('#lineno_').val(data['dodt_lineno_']);
				$('#description').val(data['dodt_itemcode'] + ' ' + data['dodt_remarks']);
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
			title:"Select Itemcode",
			open: function(){
				dialog_itemcode.urlParam.filterCol=['dodt.compcode','dodt.recno'];
				dialog_itemcode.urlParam.filterVal=['session.compcode',selrowData('#'+dialog_delordno.gridname).dohd_recno];
				dialog_itemcode.urlParam.fixPost = "true";
				dialog_itemcode.urlParam.table_id = "none_";
				dialog_itemcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol = ['dodt.itemcode'];
				dialog_itemcode.urlParam.join_onVal = ['p.itemcode'];
			}
		},'urlParam','tab'
	);
	dialog_itemcode.makedialog();

	var dialog_itemcode_direct= new ordialog(
		'itemcode_direct',['material.product'],'#itemcode_direct',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'uomcode',name:'uomcode',width:100,classes:'pointer',hidden:true},
				{label:'currprice',name:'currprice',width:100,classes:'pointer',hidden:true},			
			],
			urlParam:{
				filterCol:['dodt.compcode','dodt.recno'],
				filterVal:['session.compcode',selrowData('#'+dialog_delordno.gridname).dohd_recno]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode_direct.gridname);
				$('#uomcode').val(data['uomcode']);
				$('#purordno').val(data['dodt_srcdocno']);
				$('#purprice').val(data['currprice']);
				$('#description').val(data['itemcode']);
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
			title:"Select Itemcode",
			open: function(){
			}
		},'urlParam','tab'
	);
	dialog_itemcode_direct.makedialog();

	var dialog_uomcode= new ordialog(
		'uomcode','material.product','#uomcode',errorField,
		{	colModel:[
				{label:'UOM Code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},		
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			ondblClickRow: function(){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select uomcode",
			open: function(){
				dialog_uomcode.urlParam.filterCol=['compcode'],
				dialog_uomcode.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_uomcode.makedialog();


	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( checkdate_asset(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},
	{
		text: "Cancel",click: function() {
			$(this).dialog('close');
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
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch(oper) {
					case state = 'add':
						//mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#formdata');
						ToggleDisableForm();
						rdonly("#dialogForm");
						enableField();
						break;
					case state = 'edit':
						//mycurrency.formatOnBlur();
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						rdonly("#dialogForm");
						if($("#dialogForm input[name='regtype']").val() == 'P'){
							disableField();
						}else{
							enableField();
						}
						break;
					case state = 'view':
						//mycurrency.formatOn();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata');
						$(this).dialog("option", "buttons",butt2);
						getmethod_and_res(selrowData("#jqGrid").assetcode);
						//getNVB();
						//getOrigCost();
						break;
				}
				if(oper!='view'){
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
					dialog_assetcode.on();
					dialog_deptcode.on();
					dialog_loccode.on();
					dialog_suppcode.on();
					dialog_itemcode.on();
					dialog_itemcode_direct.on();
					dialog_uomcode.on();
					dialog_delordno.on();
					dialog_invno.off();
				}
				if(oper!='add'){
					dialog_itemcode.check(errorField);
					dialog_itemcode_direct.check(errorField);
					dialog_uomcode.check(errorField);
					dialog_delordno.check(errorField);
					dialog_assetcode.check(errorField);
					dialog_suppcode.check(errorField);
					dialog_deptcode.check(errorField);
					dialog_loccode.check(errorField);
					dialog_invno.check(errorField);
				}
			},
			close: function( event, ui ) {
				emptyFormdata(errorField,'#formdata');
				$(".noti ol").empty();
				$("#formdata a").off();
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
			url: '/util/get_table_default',
			field:'',
			table_name:'finance.fatemp',	
		}

		/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'save_table_default',
			field:'',
			url:'assetregister/form',
			oper:oper,
			table_name:'finance.fatemp',
			noduplicate: 'true'
		};
		
		$("#jqGrid").jqGrid({
			datatype: "local",
			 colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
				{ label: 'Idno', name: 'idno', width: 8, sorttype: 'text', classes: 'wrap', hidden:true, key: true}, 
				{ label: 'Asset Type', name: 'assettype', width: 13, sorttype: 'text', classes: 'wrap'},
				{ label: 'Category', name: 'assetcode', width: 16, sorttype: 'text', classes: 'wrap', canSearch: true},
				{ label: 'Department', name: 'deptcode', width: 15, sorttype: 'text', classes: 'wrap'},			
				{ label: 'Location', name: 'loccode', width: 40, sorttype: 'text', classes: 'wrap', hidden:true},				
				{ label: 'Supplier', name: 'suppcode', width: 40, sorttype: 'text', classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail},	
				{ label: 'DO No', name:'delordno',width: 13, sorttype:'text', classes:'wrap'},					
				{ label: 'Invoice No', name:'invno', width: 20,sorttype:'text', classes:'wrap', canSearch: true},
				{ label: 'Purchase Order No', name:'purordno',width: 20, sorttype:'text', classes:'wrap', hidden:true},
				{ label: 'Item Code', name: 'itemcode', width: 11, sorttype: 'text', classes: 'wrap', canSearch: true},
				{ label: 'UOM Code', name: 'uomcode', width: 15, sorttype: 'text', classes: 'wrap', hidden: true},
				{ label: 'Regtype', name: 'regtype', width: 11, sorttype: 'text', classes: 'wrap', formatter:regtypeformat,unformat:regtypeunformat},	
				{ label: 'Description', name: 'description', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true, selected: true},
				{ label: 'DO Date', name:'delorddate', width: 20, classes:'wrap',formatter:dateFormatter, unformat:dateUNFormatter, hidden:true},
				{ label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', formatter:dateFormatter, unformat:dateUNFormatter, hidden:true},
				{ label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
				{ label: 'Purchase Date', name:'purdate', width: 16, classes:'wrap', formatter:dateFormatter, unformat:dateUNFormatter},
				{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
				{ label: 'Original Cost', name:'origcost', width: 20, classes:'wrap', hidden:true},
				{ label: 'Current Cost', name:'currentcost', width:15, classes:'wrap', hidden:false, align: 'right', formatter: 'currency' },
				{ label: 'Quantity', name:'qty', width:20, classes:'wrap', hidden:true},
				{ label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
				{ label: 'Delivery Order Line No', name:'lineno_', width:20, classes:'wrap', hidden:true},
				//method
				//residual value
				{ label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, unformat:dateUNFormatter, hidden:true},
				{ label: 'Post Date', name:'trandate', width:20, classes:'wrap', formatter:dateFormatter, unformat:dateUNFormatter, hidden:true},
				//accumprev
				{ label: 'Accum Prev', name:'lstytddep', width:20, classes:'wrap', hidden:true},
				//accumytd
				{ label: 'Accum YTD', name:'cuytddep', width:20, classes:'wrap', hidden:true},
				//nbv
				{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:true,
				formatter: formatter, unformat: unformat, cellattr: function (rowid, cellvalue)
				{ return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },},
				{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},
				{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },

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
			ondblClickRow: function(rowid, iRow, iCol, e){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			},
			gridComplete: function(){
				if (oper == 'add') {
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				}

				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
				fdl.set_array().reset();

				cbselect.checkbox_function_on();
				cbselect.refresh_seltbl();
			}
				
		});

		//////////////////////////// STATUS FORMATTER /////////////////////////////////////////////////
		function regtypeformat(cellvalue, options, rowObject) {
			if (cellvalue == 'P') {
				return "PO";
			}
			if (cellvalue == 'D') {
				return "Direct";
			}
		}

		function regtypeunformat(cellvalue, options) {
			if (cellvalue == 'P') {
				return "PO";
			}
			if (cellvalue == 'D') {
				return "Direct";
			}
		}

		function formatter(cellvalue, options, rowObject) {
			if (cellvalue == 'A') {
				return "Active";
			}
			if (cellvalue == 'D') {
				return "Deactive";
			}
		}

		function unformat(cellvalue, options) {
			if (cellvalue == 'Active') {
				return "Active";
			}
			if (cellvalue == 'Deactive') {
				return "Deactive";
			}
		}

		//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
		function showdetail(cellvalue, options, rowObject){
			var field,table,case_;
			switch(options.colModel.name){
				case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
				
			}
			var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

			fdl.get_array('assetregister',options,param,case_,cellvalue);
			// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
			
			return cellvalue;
		}

		function unformat_showdetail(cellvalue, options, rowObject){
			return $(rowObject).attr('title');
		}


		////////////////////// set label jqGrid right ////////////////////////////////////////////////////
		jqgrid_label_align_right("#jqGrid");

		///////////////////////// REGISTER TYPE SELECTION/////////////////////////////////////
		/////// if the function chosen is P, certain field will be disabled //////////////////
		$("input[name=regtype]:radio").on('change', function(){
			regtype  = $("input[name=regtype]:checked").val();
			if(regtype == 'P'){
				disableField();
			}else if(regtype == 'D') {
				enableField();
			}
		});

		//////// if the function chosen is P ////////////////////////////////////  
		function disableField() {
			dialog_itemcode_direct.off();
			$('#itemcode_direct_div').hide();
			$('#itemcode_direct').prop('disabled',true);

			dialog_itemcode.on();
			$('#itemcode_div').show();
			$('#itemcode').prop('disabled',false);

			dialog_delordno.on();
			dialog_suppcode.on();
			dialog_invno.off();
			dialog_uomcode.on();

			$("#invno").prop('readonly',true);
			$("#suppcode").prop('readonly',false);
			$("#delorddate").prop('readonly',true);
			$("#uomcode").prop('readonly',true);
			$("#invdate").prop('readonly',true);
			$("#docno").prop('readonly',true);
			$("#purordno").prop('readonly',true);
			$("#purdate").prop('readonly',true);
			$("#purprice").prop('readonly',true);
			$("#origcost").prop('readonly',true);
			$("#currentcost").prop('readonly',true);
			$("#nbv").prop('readonly',true);
			$("#qty").prop('readonly',true);
			$("#delordno_btn").show();
			// $("#delordno_dh").show();
			$("#dn").show();
		}
		//////// if the function chosen is D ////////////////////////////////////  
		function enableField() {
			dialog_itemcode_direct.on();
			$('#itemcode_direct_div').show();
			$('#itemcode_direct').prop('disabled',false);

			dialog_itemcode.off();
			$('#itemcode_div').hide();
			$('#itemcode').prop('disabled',true);

			dialog_delordno.off();
			dialog_suppcode.on();
			dialog_invno.off();
			dialog_uomcode.on();

			$("#invno").prop('readonly',false);
			$("#suppcode").prop('readonly',false);
			$("#itemcode").prop('readonly',false);
			$("#uomcode").prop('readonly',false);
			$("#delorddate").prop('readonly',false);
			$("#invdate").prop('readonly',false);
			$("#docno").prop('readonly',false);
			$("#purordno").prop('readonly',false);
			$("#purdate").prop('readonly',false);
			$("#purprice").prop('readonly',false);
			$("#origcost").prop('readonly',true);
			$("#currentcost").prop('readonly',true);
			$("#nbv").prop('readonly',true);
			$("#qty").prop('readonly',false);
			$("#delordno_btn").hide();
			// $("#delordno_dh").hide();
			$("#dn").hide();
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
			buttonicon:"glyphicon glyphicon-info-sign",
			title:"View Selected Row",  
			onClickButton: function(){
				oper='view';
				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				if(selrowData('#jqGrid').regtype == 'P'){
					disableField();
				}else if(selrowData('#jqGrid').regtype == 'D') {
					enableField();
					
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first",  
			buttonicon:"glyphicon glyphicon-edit",
			title:"Edit Selected Row",  
			onClickButton: function(){
				oper='edit';
				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				if(selrowData('#jqGrid').regtype == 'P'){
					disableField();
				}else if(selrowData('#jqGrid').regtype == 'D') {
					enableField();
					
				}
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

		/////////////////////////////populate data for dropdown search By////////////////////////////
		searchBy();
		function searchBy(){
			$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
				if(value['canSearch']){
					if(value['selected']){
						$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
					}else{
						$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
					}
				}
			});
			searchClickChange('#jqGrid','#searchForm',urlParam);
		}

		function searchClickChange(grid,form,urlParam){
			$(form+' [name=Stext]').on( "keyup", function() {
				delay(function(){
					search('#jqGrid',$('#searchForm input[seltext=true]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
				}, 500 );
			});
		}

		$('#Scol').on('change', scolChange);

		function scolChange() {
			if($('#Scol').val()=='assetcode'){
				$("input[name='Stext'],#search_assetcode_").hide("fast");
				$("#search_assetcode_").show("fast");

				$("#search_assetcode").attr('seltext',true);
				$("input[name='Stext']").attr('seltext',false);
			} else {
				$("input[name='Stext']").show("fast");
				$("#search_assetcode_").hide("fast");

				$("input[name='Stext']").attr('type', 'text');
				$("input[name='Stext']").velocity({ width: "100%" });
				
				$("#search_assetcode").attr('seltext',false);
				$("input[name='Stext']").attr('seltext',true);
			}
			
			search('#jqGrid',$('#searchForm input[seltext=true]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
		}
		
		//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		populateSelect('#jqGrid','#searchForm');
		// searchClick2('#jqGrid','#searchForm',urlParam);

		//////////add field into param, refresh grid if needed////////////////////////////////////////////////
		addParamField('#jqGrid',true,urlParam,['Checkbox','nbv']);
		addParamField('#jqGrid',false,saveParam,['idno','adduser','adddate','upduser','upddate','compcode','recstatus','assetreg_status','nbv','Checkbox']);
		
		$("#delorddate,#invdate,#delorddate").blur(checkdate_asset);

		function checkdate_asset(nkreturn=false){
			var delorddate = $('#delorddate').val();
			var invdate = $('#invdate').val();
			var purdate = $('#purdate').val();

			$(".noti ol").empty();
			var failmsg=[];

			if(moment(invdate).isBefore(delorddate)){
				failmsg.push("Invoice date cannot be lower than Delivery Order date");
			}

			if(moment(purdate).isAfter(invdate) || moment(purdate).isAfter(delorddate) ){
				failmsg.push("Purchase date cannot be greater than Invoice date and Delivery Order date");
			}

			if(failmsg.length){
				failmsg.forEach(function(element){
					$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
				});
				if(nkreturn)return false;
			}else{
				if(nkreturn)return true;
			}

		}

		function ToggleDisableForm(disable=true){
			if(disable){
				disableForm('#disableGroup');
				$('#disableGroup input').on('click',alert_toogle);
			}else{
				enableForm('#disableGroup');
				$("#currentcost").prop("readonly",true);
				$("#nbv").prop('readonly',true);
				$('#disableGroup input').off('click',alert_toogle);
			}
		}

		function alert_toogle(){
			let element = "Choose 'Register Type' either Purchase Order or Direct";
			$(".noti ol").empty();
			$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
		}

		$("input[type='radio'][name='regtype']").on('click',regtype_choose);

		function regtype_choose(){
			ToggleDisableForm(false);
			$(".noti ol").empty();
			$( '#disableGroup input' ).each(function() {
				if ( $(this).hasClass('error') || $(this).closest("div").hasClass('has-error') ){
					$(this).removeClass('error');
					$(this).closest("div .has-error").removeClass('has-error');
					$(this).css("border-color","");
				}
			});
			$('#disableGroup .help-block').html('');
		}

		$("#purprice,#qty").blur(getcurentcost);
		function getcurentcost(event) { 
			mycurrency.formatOff()
	        let purprice = parseFloat($("#purprice").val());
	        let qty = parseFloat($("#qty").val());

	        var currentcost = (purprice * qty);

	        $("#currentcost, #origcost").val(currentcost);
			mycurrency.formatOn()
		}

		$("#origcost,#lstytddep,#cuytddep").blur(getNVB);
		function getNVB(event) { 
			mycurrency.formatOff()
	        let origcost = parseFloat($("#origcost").val());
	        let lstytddep = parseFloat($("#lstytddep").val());
	        let cuytddep = parseFloat($("#cuytddep").val());

	        var nbv = (origcost - lstytddep - cuytddep);

	        $("#nbv").val(nbv);
			mycurrency.formatOn()
		}


		$('#taggingNoButton').click(gneratetagno);
		function gneratetagno(){
			var idno_array = [];
		
			idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');

			obj={};
			obj.idno_array = idno_array;
			obj.oper = 'gen_tagno';
			obj._token = $('#_token').val();
			
			$.post( '/assetregister/form', obj , function( data ) {
				refreshGrid('#jqGrid', urlParam);
			}).fail(function(data) {

			}).success(function(data){
				
			});

		}

		var search_assetcode= new ordialog(
			'search_assetcode','finance.facode','#search_assetcode',errorField,
			{	colModel:[
					{label:'Assetcode',name:'assetcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'AssetType',name:'assettype',width:100,classes:'pointer',hidden:true},
					{label:'Method',name:'method',width:100,classes:'pointer',hidden:true},
					{label:'Residualvalue',name:'residualvalue',width:100,classes:'pointer',hidden:true},
				],
				urlParam: {
					filterCol:['compcode',"assetcode"],
					filterVal:['session.compcode','']
				},
				ondblClickRow:function(){
					let data=selrowData('#'+search_assetcode.gridname);
					urlParam.searchCol=["assetcode"];
					urlParam.searchVal=[data.assetcode];
					refreshGrid('#jqGrid', urlParam);
				}
			},
			{
				title:"Select Category",
				open: function(){
					search_assetcode.urlParam.filterCol=['compcode'];
					search_assetcode.urlParam.filterVal=['session.compcode'];
				}
			},'urlParam','radio','tab'
		);
		search_assetcode.makedialog();
		search_assetcode.on();

		function formatterCheckbox(cellvalue, options, rowObject){
			let idno = cbselect.idno;
			let recstatus = cbselect.recstatus;
			if(options.gid == "jqGrid"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}else if(options.gid != "jqGrid"){
				return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
			}else{
				return ' ';
			}
		}

		$("#jqGrid_selection").jqGrid({
			datatype: "local",
			colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
			shrinkToFit: false,
			autowidth:true,
			multiSort: true,
			viewrecords: true,
			sortname: 'lineno_',
			sortorder: "desc",
			gridComplete: function(){
				
			},
		})
		jqgrid_label_align_right("#jqGrid_selection");
		cbselect.on();//on lepas jqgrid

	
	});