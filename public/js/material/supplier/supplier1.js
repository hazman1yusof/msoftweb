
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			
			/////////////////////////validation//////////////////////////
			$.validate({
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
			dialog_GlAccNo=new makeDialog('finance.glmasref','#GlAccNo',['glaccount','description'], 'Gl Account No');

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
					toggleFormData('#jqGrid','#formdata',oper);
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
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
					$('.alert').detach();
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
				table_id:'SuppCode'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.supplier',
				table_id:'SuppCode'
			};
			
			//////////////////////////start grid/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Supplier Code', name: 'SuppCode', width: 35 , sorttype: 'text', classes: 'wrap', canSearch: true, checked:true
					},						
					{ label: 'Supplier Group', name: 'SuppGroup', width: 35, editable: true, classes: 'wrap' },
					{ label: 'Supplier Name', name: 'Name', width: 100, editable: true, classes: 'wrap', canSearch: true },
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
					{ label: 'recstatus', name: 'recstatus', width: 80, hidden: true,},
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
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						rowData = $('#jqGrid').jqGrid ('getRowData', rowid);
						urlParam_suppitems.filterVal[0]=rowData['SuppCode']; 

						//urlParam_suppitems.filterVal[0]=rowid;//supp item grid for suppcode
						saveParam_suppitems.filterVal[0]=rowid;
						urlParam_suppbonus.filterVal[0]=rowid;//supp bonus grid for suppcode
						saveParam_suppbonus.filterVal[0]=rowid;
						$("#Fsuppitems :input[name='si.suppcode']").val(rowData['SuppCode']);
						//$("#Fsuppitems :input[name='si.suppcode']").val(rowid);
						refreshGrid('#gridSuppitems',urlParam_suppitems);
						$("#pg_jqGridPager2 table").show();
					}
				},
				
			});
			
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'SuppCode':selRowId});
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
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);
			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// suppitems //////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			dialog_pricecode=new makeDialog('material.pricesource',"#Fsuppitems :input[name='si.pricecode']",['pricecode','description'], 'Price Code');
			dialog_itemcode=new makeDialog('material.product',"#Fsuppitems :input[name='si.itemcode']",['itemcode','description'], 'Item Code');
			dialog_uomcode=new makeDialog('material.uom',"#Fsuppitems :input[name='si.uomcode']",['uomcode','description'], 'UOM Code');

			var buttItem1=[{
				text: "Save",click: function() {
					if( $('#Fsuppitems').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",oper_suppitems,saveParam_suppitems,urlParam_suppitems);
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
					//toggleFormData('#gridSuppitems','#Fsuppitems',oper);
					switch(oper_suppitems) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#Fsuppitems');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#Fsuppitems');
							frozeOnEdit("#Dsuppitems");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#Fsuppitems');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper_suppitems!='view'){
						dialog_pricecode.handler(errorField);
						dialog_itemcode.handler(errorField);
						dialog_uomcode.handler(errorField);
					}
					if(oper_suppitems!='add'){
						dialog_pricecode.check(errorField);
						dialog_itemcode.check(errorField);
						dialog_uomcode.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#Fsuppitems');
					$('.alert').detach();
					$("#Fsuppitems a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttItem1);
					}
				},
				buttons :buttItem1,
			  });
			
			var urlParam_suppitems={
				action:'get_table_default',
				field:'',
				table_name:['material.suppitems si','material.product p'],
				table_id:'lineno_',
				join_type:['LEFT JOIN'],
				join_onCol:['si.itemcode'],
				join_onVal:['p.itemcode'],
				filterCol:['si.SuppCode','si.compcode','p.compcode'],
				filterVal:['','session.company','session.company'],//suppcode set when click supplier grid
			}

			var saveParam_suppitems={
				action:'save_table_default',
				field:'',
				oper:oper_suppitems,
				table_name:'material.suppitems',//for save_table_default, use only 1 table
				fixPost:'true',//throw out dot in the field name
				table_id:'lineno_',
				filterCol:['suppcode'],
				filterVal:[''],//suppcode set when click supplier grid
				lineno:{useOn:'lineno_'},
			};

			$("#gridSuppitems").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Supplier Code', name: 'si.suppcode', width: 30, hidden: true},
				 	{ label: 'no', name: 'si.lineno_', width: 50, hidden: true, sorttype: 'number'},
				 	{ label: 'Item Code', name: 'si.itemcode', width: 40, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Item Description', name: 'p.description', width: 90, sorttype: 'text', classes: 'wrap', canSearch: true},
					{ label: 'Price Code', name: 'si.pricecode', width: 30, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Uom Code', name: 'si.uomcode', width: 30, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Unit Price', name: 'si.unitprice', width: 30, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'Purchase Quantity', name: 'si.purqty', width: 40, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'Percentage of Discount', name: 'si.perdiscount', width: 30,  hidden: true},
					{ label: 'Amount Discount', name: 'si.amtdisc', width: 30,  hidden: true},
					{ label: 'Amount Sales Tax', name: 'si.amtslstax', width: 30,  hidden: true},
					{ label: 'Percentage of Sales Tax', name: 'si.perslstax', width: 30,  hidden: true},
					{ label: 'Expiry Date', name: 'si.expirydate', width: 30,  hidden: true},
					{ label: "Item Code at Supplier's Site", name: 'si.sitemcode', width: 30,  hidden: true},
					{ label: 'recstatus', name: 'si.recstatus', width: 30, hidden: true},
					
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
					/*if(editedRow!=0){
						$("#gridSuppitems").jqGrid('setSelection',editedRow,false);
					}*/
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						rowData = $('#gridSuppitems').jqGrid ('getRowData', rowid);
						urlParam_suppbonus.filterVal[1]=rowData['si.itemcode']; //set itemcode for suppbonusitem grid
						//set hidden field
						$("#Fsuppbonus :input[name='sb.suppcode']").val(rowData['si.suppcode']);
						$("#Fsuppbonus :input[name='sb.pricecode']").val(rowData['si.pricecode']);
						$("#Fsuppbonus :input[name='sb.itemcode']").val(rowData['si.itemcode']);
						$("#Fsuppbonus :input[name='sb.uomcode']").val(rowData['si.uomcode']);
						$("#Fsuppbonus :input[name='sb.purqty']").val(rowData['si.purqty']);
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
				onClickButton: function(){
					oper_suppitems='del';
					var selRowId = $("#gridSuppitems").jqGrid ('getGridParam', 'selrow');
					var suppcode = $("#jqgrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#Fsuppitems');
					}else{
						saveFormdata("#gridSuppitems","#Dsuppitems","#Fsuppitems",'del',saveParam,{lineno_:selRowId});
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
					$("#Fsuppitems :input[name*='si.suppcode']").val(selrowData('#jqGrid').SuppCode);
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});

			addParamField('#gridSuppitems',false,urlParam_suppitems);
			addParamField('#gridSuppitems',false,saveParam_suppitems,["p.description"]);

			populateSelect('#gridSuppitems','#searchForm2');
			searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);


			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// suppbonus //////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			dialog_bonpricecode=new makeDialog('material.pricesource',"#Fsuppbonus :input[name='sb.bonpricecode']",['pricecode','description'], 'Bonus Price Code');
			dialog_bonitemcode=new makeDialog('material.product',"#Fsuppbonus :input[name='sb.bonitemcode']",['itemcode','description'], 'Bonus Item Code');
			dialog_bonuomcode=new makeDialog('material.uom',"#Fsuppbonus :input[name='sb.bonuomcode']",['uomcode','description'], 'Bonus UOM Code');

			var buttbonus1=[{
				text: "Save",click: function() {
					if( $('#Fsuppbonus').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",oper_suppbonus,saveParam_suppbonus,urlParam_suppbonus);
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
					toggleFormData('#gridSuppBonus','#Fsuppbonus',oper);
					switch(oper_suppbonus) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#Fsuppbonus');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#Fsuppbonus');
							frozeOnEdit("#Dsuppbonus");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#Fsuppbonus');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper_suppbonus!='view'){
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
					$('.alert').detach();
					$("#Fsuppbonus a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttbonus1);
					}
				},
				buttons :buttbonus1,
			  });
			
			var urlParam_suppbonus={
				action:'get_table_default',
				field:'',
				table_name:['material.suppbonus sb','material.product p'],
				table_id:'lineno_',
				join_type:['LEFT JOIN'],
				join_onCol:['sb.itemcode'],
				join_onVal:['p.itemcode'],
				filterCol:[
					'sb.suppcode','sb.itemcode',
					'sb.compcode','p.compcode'],
				filterVal:[
					'','',//suppcode set when click supplieritem grid, itemcode set when click supplier item gird
					'session.company','session.company'],
				}

			var saveParam_suppbonus={
				action:'save_table_default',
				field:'',
				oper:oper_suppitems,
				table_name:'material.suppbonus',//for save_table_default, use only 1 table
				fixPost:'true',//throw out dot in the field name
				table_id:'lineno_',
				filterCol:['suppcode'],
				filterVal:[''],//suppcode set when click supplier grid
				lineno:{useOn:'lineno_'},
			};

			$("#gridSuppBonus").jqGrid({
				datatype: "local",
				 colModel: [
				 	{ label: 'Supplier Code', name: 'sb.suppcode', width: 50, hidden: true},
					{ label: 'Price Code', name: 'sb.pricecode', width: 30, hidden: true},
					{ label: 'no', name: 'sb.lineno_', width: 50, hidden: true, sorttype: 'number'},
					{ label: 'uomcode', name: 'sb.uomcode', width: 50, hidden: true},
					{ label: 'purqty', name: 'sb.purqty', width: 50, hidden: true},
					{ label: 'bonpricecode', name: 'sb.bonpricecode', width: 50, hidden: true},
				 	{ label: 'Item Code', name: 'sb.bonitemcode', width: 50, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Item Description', name: 'p.description', width: 30, classes: 'wrap', canSearch: true},
					{ label: 'Bonus UOM Code', name: 'sb.bonuomcode', width: 30, classes: 'wrap'},
					{ label: 'Bonus Quantity', name: 'sb.bonqty', width: 30, classes: 'wrap'}, 
					{ label: 'itemcode', name: 'sb.itemcode', width: 50, hidden: true},
					{ label: "Supplier's Item Code", name: 'sb.bonsitemcode', width: 30, classes: 'wrap'},
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
				caption: caption('searchForm3','Bonus Items Given by the Supplier for the item'),
				pager: "#jqGridPager3",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager3 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#gridSuppitems").jqGrid('setSelection',editedRow,false);
					}*/
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
						return emptyFormdata(errorField,'#Fsuppitems');
					}else{
						saveFormdata("#gridSuppBonus","#Dsuppbonus","#Fsuppbonus",'del',saveParam,{lineno_:selRowId});
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
				onClickButton: function(){
					oper_suppbonus='add';
					$( "#Dsuppbonus" ).dialog( "open" );
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});

			addParamField('#gridSuppBonus',false,urlParam_suppbonus);
			addParamField('#gridSuppBonus',false,saveParam_suppbonus,["p.description"]);

			populateSelect('#gridSuppBonus','#searchForm3');
			searchClick('#gridSuppBonus','#searchForm3',urlParam_suppbonus);

			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
			$("#pg_jqGridPager2 table").hide();
			$("#pg_jqGridPager3 table").hide();
			
		});
		