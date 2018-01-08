
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']","input[name='si_lastcomputerid']","input[name='si_lastipaddress']","input[name='si_computerid']","input[name='si_ipaddress']","input[name='sb_lastcomputerid']","input[name='sb_lastipaddress']","input[name='sb_computerid']","input[name='sb_ipaddress']");
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
						return {
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////

			////////////////////object for dialog handler//////////////////
			dialog_SuppGroup=new makeDialog('material.suppgroup','#SuppGroup',['suppgroup','description'], 'Supplier Group');
			dialog_CostCode=new makeDialog('finance.costcenter','#CostCode',['costcode','description'], 'Cost Code');
			dialog_GlAccNo=new makeDialog('finance.glmasref','#GlAccNo',['glaccno','description'], 'Gl Account No');

			var mycurrency =new currencymode(['#TermDisp', '#TermNonDisp', '#TermOthers', '#si_purqty', '#si_unitprice', '#si_perdiscount', '#si_amtdisc', '#si_perslstax', '#si_amtslstax', '#sb_bonqty']);

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
					}else{
						mycurrency.formatOn();
					}
				}
			},{
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
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							hideOne('#formdata');
							rdonly('#formdata');
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$('#formdata :input[hideOne]').show();
							rdonly('#formdata');
							break;
						case state = 'view':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']","input[name='computerid']","input[name='ipaddress']");
						dialog_SuppGroup.handler(errorField);
						dialog_CostCode.handler(errorField);
						dialog_GlAccNo.handler(errorField);
					}
					if(oper!='add'){
						dialog_SuppGroup.check(errorField);
						dialog_CostCode.check(errorField);
						dialog_GlAccNo.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata');
					parent_close_disabled(false);
					//$('.alert').detach();
					$('#formdata .alert').detach();
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
				field:'',
				table_name:'material.supplier',
				table_id:'SuppCode',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.supplier',
				table_id:'SuppCode',
				saveip:'true'
			};
			
			//////////////////////////start grid/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Supplier Code', name: 'SuppCode', width: 35 , sorttype: 'text', classes: 'wrap', canSearch: true, checked:true},	
					{ label: 'Supplier Name', name: 'Name', width: 100, editable: true, classes: 'wrap', canSearch: true },					
					{ label: 'Supplier Group', name: 'SuppGroup', width: 35, editable: true, classes: 'wrap' },
					{ label: 'Cont Pers', name: 'ContPers', width: 90, hidden: true},
					{ label: 'Addr1', name: 'Addr1', width: 30, hidden: true}, 
					{ label: 'Addr2', name: 'Addr2', width: 90, hidden:true},
					{ label: 'Addr3', name: 'Addr3', width: 80,hidden:true},
					{ label: 'Addr4', name: 'Addr4', width: 90,hidden:true},
					{ label: 'Tel No', name: 'TelNo', width: 80,hidden:true},
					{ label: 'Fax No', name: 'Faxno', width: 90,hidden:true},
					{ label: 'Term Others', name: 'TermOthers', width: 30, editable: true, classes: 'wrap' }, 
					{ label: 'TermNonDisp', name: 'TermNonDisp', width: 35, editable: true, classes: 'wrap' },
					{ label: 'Term Disp', name: 'TermDisp', width: 30, editable: true, classes: 'wrap' },
					{ label: 'Cost Code', name: 'CostCode', width: 30, editable: true, classes: 'wrap' },
					{ label: 'Gl Account No', name: 'GlAccNo', width: 35, editable: true, classes: 'wrap' },
					{ label: 'AccNo', name: 'AccNo', width: 80, hidden: true, editable: true},
					{ label: 'DepAmt', name: 'DepAmt', width: 80, hidden: true},
					{ label: 'MiscAmt', name: 'MiscAmt', width: 80, hidden: true},
					{ label: 'Supply Goods', name: 'SuppFlg', width: 80, editable: true, classes: 'wrap', hidden: true},
					{ label: 'Advccode', name: 'Advccode', width: 80, hidden: true, editable: true},
					{ label: 'AdvGlaccno', name: 'AdvGlaccno', width: 80, hidden: true, editable: true},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{label: 'idno', name: 'idno', hidden: true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true, classes: 'wrap'},
				],
				autowidth:true,
				viewrecords: true,
				multiSort: true,
				loadonce: false,
				width: 900,
				height: 100,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						urlParam_suppitems.filterVal[0]=selrowData("#jqGrid").SuppCode; 
						saveParam_suppitems.filterVal[0]=selrowData("#jqGrid").SuppCode; 
						urlParam_suppbonus.filterVal[1]=selrowData("#jqGrid").SuppCode;
						saveParam_suppbonus.filterVal[0]=selrowData("#jqGrid").SuppCode;
						//$("#Fsuppitems :input[name='billtype']").val(selrowData("#jqGrid").billtype);
						$("#Fsuppitems :input[name='si_suppcode']").val(selrowData("#jqGrid").SuppCode);
						refreshGrid('#gridSuppitems',urlParam_suppitems);
						$('#gridSuppBonus').jqGrid('clearGridData');
						$("#pg_jqGridPager3 table").hide();
						$("#pg_jqGridPager2 table").show();
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

			
			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				id: "jqGridPagerglyphicon-trash",
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper='del';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#formdata');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'SuppCode':selRowId});
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
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					
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
			
			//toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','adduser','adddate', 'ipaddress', 'computerid']);


			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// suppitems //////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			dialog_pricecode=new makeDialog('material.pricesource',"#Fsuppitems :input[name='si_pricecode']",['pricecode','description'], 'Price Code');
			dialog_itemcode=new makeDialog('material.product',"#Fsuppitems :input[name='si_itemcode']",['itemcode','description'], 'Item Code');
			dialog_uomcode=new makeDialog('material.uom',"#Fsuppitems :input[name='si_uomcode']",['uomcode','description'], 'UOM Code');

			var buttItem1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
					if( $('#Fsuppitems').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",oper_suppitems,saveParam_suppitems,urlParam_suppitems,'#searchForm2');
					}else{
						mycurrency.formatOn();
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];

			var oper_suppitems;
			$("#Dsuppitems")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper_suppitems) {
						case state = 'add':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#Fsuppitems');
							hideOne('#Fsuppitems');
							rdonly('#Fsuppitems');
							$(this).dialog("option", "buttons",buttItem1);
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#Fsuppitems');
							frozeOnEdit("#Dsuppitems");
							$('#Fsuppitems :input[hideOne]').show();
							rdonly('#Fsuppitems');
							$(this).dialog("option", "buttons",buttItem1);
							break;
						case state = 'view':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#Fsuppitems');
							$('#Fsuppitems :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper_suppitems=='add'){
						dialog_pricecode.handler(errorField);
						dialog_itemcode.handler(errorField);
						dialog_uomcode.handler(errorField);
					}
					if(oper_suppitems == 'edit' && $('#gridSuppBonus').jqGrid('getGridParam', 'reccount') < 1){
						dialog_pricecode.handler(errorField);
						dialog_itemcode.handler(errorField);
						dialog_uomcode.handler(errorField);
					}
					if(oper_suppitems == 'edit' && $('#gridSuppBonus').jqGrid('getGridParam', 'reccount') > 1){
						$("#Fsuppitems :input[name*='si_pricecode']").prop("readonly",true);
						$("#Fsuppitems :input[name*='si_itemcode']").prop("readonly",true);
						$("#Fsuppitems :input[name*='si_uomcode']").prop("readonly",true);
						$("#Fsuppitems :input[name*='si_purqty']").prop("readonly",true);
					}
					if(oper_suppitems!='add'){
						dialog_pricecode.check(errorField);
						dialog_itemcode.check(errorField);
						dialog_uomcode.check(errorField);
					}
					if (oper_suppitems != 'view') {
						set_compid_from_storage("input[name='si_lastcomputerid']","input[name='si_lastipaddress']","input[name='si_computerid']","input[name='si_ipaddress']");
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#Fsuppitems');
					$('#Fsuppitems .alert').detach();
					//$('.alert').detach();
					$("#Fsuppitems a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttItem1);
					}
				},
				buttons :buttItem1,
			  });
			
			/////////////////////parameter for jqgrid url SVC/////////////////////////////////////////////////
			var urlParam_suppitems={
				action:'get_table_default',
				field:'',
				fixPost:'true',//replace underscore with dot
				table_name:['material.suppitems si','material.product p'],
				table_id:'si_lineno_',
				join_type:['LEFT JOIN'],
				join_onCol:['si.itemcode'],
				join_onVal:['p.itemcode'],
				filterCol:['si.SuppCode','si.compcode','p.compcode'],
				filterVal:['','session.company','session.company'],//suppcode set when click supplier grid
				sort_idno:true,
			}

			var saveParam_suppitems={
				action:'save_table_default',
				field:'',
				oper:oper_suppitems,
				table_name:'material.suppitems',//for save_table_default, use only 1 table
				fixPost:'true',//throw out dot in the field name
				table_id:'si_lineno_',
				filterCol:['suppcode'],
				filterVal:[''],//suppcode set when click supplier grid
				lineno:{useOn:'lineno_'},
				saveip:'true'
			};

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$("#gridSuppitems").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Supplier Code', name: 'si_suppcode', width: 30, hidden: true},
				 	{ label: 'no', name: 'si_lineno_', width: 50, sorttype: 'number', hidden: true,}, // 
				 	{ label: 'Item Code', name: 'si_itemcode', width: 40, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Item Description', name: 'p_description', width: 90, sorttype: 'text', classes: 'wrap', canSearch: true},
					{ label: 'Price Code', name: 'si_pricecode', width: 30, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Uom Code', name: 'si_uomcode', width: 30, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Unit Price', name: 'si_unitprice', width: 30, sorttype: 'float', editable: true, classes: 'wrap',formatter:'currency'},
					{ label: 'Purchase Quantity', name: 'si_purqty', width: 40, sorttype: 'float', editable: true, classes: 'wrap',formatter:'currency'},
					{ label: 'Percentage of Discount', name: 'si_perdiscount', width: 30,  hidden: true},
					{ label: 'Amount Discount', name: 'si_amtdisc', width: 30,  hidden: true},
					{ label: 'Amount Sales Tax', name: 'si_amtslstax', width: 30,  hidden: true},
					{ label: 'Percentage of Sales Tax', name: 'si_perslstax', width: 30,  hidden: true},
					{ label: 'Expiry Date', name: 'si_expirydate', width: 30,  hidden: true},
					{ label: "Item Code at Supplier's Site", name: 'si_sitemcode', width: 30,  hidden: true},
					{ label: 'Record Status', name: 'si_recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{label: 'No', name: 'si_idno', width: 50, hidden: true},
					{ label: 'adduser', name: 'si_adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'si_adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'si_upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'si_upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'idno', name: 'si_idno', width: 90, hidden:true},
					{ label: 'computerid', name: 'si_computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'si_ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'si_lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'lastipaddress', name: 'si_lastipaddress', width: 90, hidden:true, classes: 'wrap'},
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
				hidegrid: false,
				caption: caption('searchForm2','Items Supplied By the Supplier'),
				pager: "#jqGridPager2",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#gridSuppitems").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#gridSuppitems").jqGrid ('getGridParam', 'selrow')).focus();

					/////////////////////////////// reccount ////////////////////////////
					
					if($("#gridSuppitems").getGridParam("reccount") >= 1){
						$("#jqGridPagerglyphicon-trash").hide();
					} 

					if($("#gridSuppitems").getGridParam("reccount") < 1){
						$("#jqGridPagerglyphicon-trash").show()
					}
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						rowData = $('#gridSuppitems').jqGrid ('getRowData', rowid);
						//console.log(rowData.svc_billtype);
						urlParam_suppbonus.filterVal[0]=selrowData("#gridSuppitems").si_itemcode; 

						$("#Fsuppbonus :input[name*='sb_suppcode']").val(selrowData("#gridSuppitems").si_suppcode);
						$("#Fsuppbonus :input[name*='sb_pricecode']").val(selrowData("#gridSuppitems").si_pricecode);
						$("#Fsuppbonus :input[name*='sb_itemcode']").val(selrowData("#gridSuppitems").si_itemcode);
						$("#Fsuppbonus :input[name*='sb_uomcode']").val(selrowData("#gridSuppitems").si_uomcode);
						$("#Fsuppbonus :input[name*='sb_purqty']").val(selrowData("#gridSuppitems").si_purqty);
						refreshGrid('#gridSuppBonus',urlParam_suppbonus);
						$("#pg_jqGridPager3 table").show();
					}
				},
				
			});
			
			$("#gridSuppitems").jqGrid('navGrid','#jqGridPager2',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#gridSuppitems",urlParam_suppitems);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				id:"jqGridPager2glyphicon-trash",
				onClickButton: function(){
					oper_suppitems='del';
					var selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#Fsuppitems');
					}else{
						saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",'del',saveParam_suppitems,urlParam_suppitems,null,{'si_lineno_':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper_suppitems='view';
					selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",selRowId,'view');
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper_suppitems='edit';
					selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",selRowId,'edit');


				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper_suppitems='add';
					$( "#Dsuppitems" ).dialog( "open" );
					//$('#Fsuppitems :input[name=si_lineno_]').hide();
					//$("#Fsuppitems :input[name*='SuppCode']").val(selrowData('#jqGrid').SuppCode);
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});

			addParamField('#gridSuppitems',false,urlParam_suppitems);
			addParamField('#gridSuppitems',false,saveParam_suppitems,["p_description", "si_idno", "si_adduser", "si_adddate", "si_computerid", 'si_ipaddress']);

			populateSelect('#gridSuppitems','#searchForm2');
			searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// suppbonus //////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			dialog_bonpricecode=new makeDialog('material.pricesource',"#Fsuppbonus :input[name='sb_bonpricecode']",['pricecode','description'], 'Bonus Price Code');
			dialog_bonitemcode=new makeDialog('material.product',"#Fsuppbonus :input[name='sb_bonitemcode']",['itemcode','description'], 'Bonus Item Code');
			dialog_bonuomcode=new makeDialog('material.uom',"#Fsuppbonus :input[name='sb_bonuomcode']",['uomcode','description'], 'Bonus UOM Code');
			
			var buttbonus1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
					if( $('#Fsuppbonus').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",oper_suppbonus,saveParam_suppbonus,urlParam_suppbonus);
					}else{
						mycurrency.formatOn();
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];

			var oper_suppbonus;
			$("#Dsuppbonus")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					switch(oper_suppbonus) {
						case state = 'add':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#Fsuppbonus');
							rdonly('#Fsuppbonus');
							hideOne('#Fsuppbonus');
							$(this).dialog("option", "buttons",buttbonus1);
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#Fsuppbonus');
							frozeOnEdit("#Dsuppbonus");
							rdonly('#Fsuppbonus');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",buttbonus1);
							break;
						case state = 'view':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#Fsuppbonus');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper_suppbonus!='view'){
						set_compid_from_storage("input[name='sb_lastcomputerid']","input[name='sb_lastipaddress']","input[name='sb_computerid']","input[name='sb_ipaddress']");
						dialog_bonpricecode.handler(errorField);
						dialog_bonitemcode.handler(errorField);
						dialog_bonuomcode.handler(errorField);
					}
					if(oper_suppbonus!='add'){
						dialog_bonpricecode.check(errorField);
						dialog_bonitemcode.check(errorField);
						dialog_bonuomcode.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#Fsuppbonus');
					parent_close_disabled(false);
					//$('.alert').detach();
					$('#Fsuppbonus .alert').detach();
					$("#Fsuppbonus a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttbonus1);
					}
				},
				buttons :buttbonus1,
			  });
			/////////////////////parameter for jqgrid url Item/////////////////////////////////////////////////
			var urlParam_suppbonus={
				action:'get_table_default',
				field:'',
				fixPost:'true',//replace underscore with dot
				table_name:['material.suppbonus sb','material.product p'],
				table_id:'sb_lineno_',
				join_type:['LEFT JOIN'],
				join_onCol:['sb.bonitemcode'],
				join_onVal:['p.itemcode'],
				filterCol:['sb.itemcode', 'sb.suppcode',  'sb.compcode', 'p.compcode'],
				filterVal:['', '', 'session.company', 'session.company'],
				sort_idno:true,
			}

			var saveParam_suppbonus={
				action:'save_table_default',
				field:'',
				oper:oper_suppitems,
				table_name:'material.suppbonus',//for save_table_default, use only 1 table
				fixPost:'true',//throw out dot in the field name
				table_id:'sb_lineno_',
				filterCol:['suppcode'],
				filterVal:[''],//suppcode set when click supplier grid
				lineno:{useOn:'lineno_'},
				saveip:'true'
			}

			//////////////////////////////////////////////////////////////////////////////////////////////////

			$("#gridSuppBonus").jqGrid({
				datatype: "local",
				 colModel: [
				 	{ label: 'Supplier Code', name: 'sb_suppcode', width: 50, hidden: true},
				 	{ label: 'no', name: 'sb_lineno_', width: 50, hidden: true},
				 	{ label: 'itemcode', name: 'sb_itemcode', width: 50, hidden: true},
					{ label: 'Price Code', name: 'sb_pricecode', width: 30, hidden: true},
					{ label: 'uomcode', name: 'sb_uomcode', width: 50, hidden: true},
					{ label: 'purqty', name: 'sb_purqty', width: 50, hidden: true},
					{ label: 'bonpricecode', name: 'sb_bonpricecode', width: 50, hidden: true},
				 	{ label: 'Bonus Item Code', name: 'sb_bonitemcode', width: 50, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Item Description', name: 'p_description', width: 30, classes: 'wrap', canSearch: true},
					{ label: 'Bonus UOM Code', name: 'sb_bonuomcode', width: 30, classes: 'wrap'},
					{ label: 'Bonus Quantity', name: 'sb_bonqty', width: 30, classes: 'wrap', formatter:'currency'}, 
					{ label: "Supplier's Item Code", name: 'sb_bonsitemcode', width: 30, classes: 'wrap'},
					{ label: 'Record Status', name: 'sb_recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{label: 'No', name: 'sb_idno', width: 50, hidden: true},
					{ label: 'adduser', name: 'sb_adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'sb_adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'sb_upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'sb_upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'idno', name: 'sb_idno', width: 90, hidden:true},
					{ label: 'computerid', name: 'sb_computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'sb_ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'sb_lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'lastipaddress', name: 'sb_lastipaddress', width: 90, hidden:true, classes: 'wrap'},
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
				hidegrid: false,
				caption: caption('searchForm3','Bonus Items Given by the Supplier for the item'),
				pager: "#jqGridPager3",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager3 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#gridSuppBonus").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#gridSuppBonus").jqGrid ('getGridParam', 'selrow')).focus();

					/////////////////////////////// reccount ////////////////////////////
					if($("#gridSuppBonus").getGridParam("reccount") >= 1){
						$("#jqGridPager2glyphicon-trash").hide();
					} 

					if($("#gridSuppBonus").getGridParam("reccount") < 1){
						$("#jqGridPager2glyphicon-trash").show()
					}
					
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						rowData = $('#gridSuppBonus').jqGrid ('getRowData', rowid);
						//console.log(rowData['svc.billtype']);
					}
				},
			});
			
			$("#gridSuppBonus").jqGrid('navGrid','#jqGridPager3',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#gridSuppBonus",urlParam_suppbonus);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper_suppitems='del';
					var selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#Fsuppbonus');
					}else{
						saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",'del',saveParam_suppbonus,urlParam_suppbonus,null,{'sb_lineno_':selRowId});
						//saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",'del',saveParam_suppbonus,{"chgcode":selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper_suppbonus='view';
					selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",selRowId,'view');
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper_suppbonus='edit';
					selRowId = $("#gridSuppBonus").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",selRowId,'edit');
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(rowid, selected){
					oper_suppbonus='add';
					$( "#Dsuppbonus" ).dialog( "open" );
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});

			addParamField('#gridSuppBonus',false,urlParam_suppbonus);
			addParamField('#gridSuppBonus',false,saveParam_suppbonus,["p_description", "sb_idno", "sb_adduser","sb_adddate", "sb_computerid", "sb_ipaddress"]);

			populateSelect('#gridSuppBonus','#searchForm3');
			searchClick('#gridSuppBonus','#searchForm3',urlParam_suppbonus);

			/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
			$("#pg_jqGridPager2 table").hide();
			$("#pg_jqGridPager3 table").hide();

});