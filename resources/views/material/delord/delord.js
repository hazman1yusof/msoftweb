		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var bc;

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
			dialog_PurReqNo=new makeDialog('material.purreghd','#purreqno',['reqdept','totamount'], 'Purchase Request');
			dialog_reqdept=new makeDialog('sysdb.department','#reqdept',['deptcode','description'], 'Department Code');
			dialog_DelDept=new makeDialog('sysdb.department','#deldept',['deptcode','description'], 'Department Code');
			dialog_SuppCode=new makeDialog('material.supplier','#suppcode',['suppcode','name'], 'Supplier Code');

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
					$("#gridSuppitems").jqGrid ('setGridWidth', Math.floor($("#gridSuppitems_c")[0].offsetWidth-$("#gridSuppitems_c")[0].offsetLeft));
					toggleFormData('#jqGrid','#formdata',oper);
					switch(oper) {
						case state = 'add':
							var trf = $("#gridSuppitems tbody:first tr:first")[0];
							$("#gridSuppitems tbody:first").empty().append(trf);
							$("#jqGrid").jqGrid('resetSelection');
							$( this ).dialog( "option", "title", "Add DO" );
							enableForm('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit DO" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View DO" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_PurReqNo.handler(errorField);
						dialog_reqdept.handler(errorField);
						dialog_DelDept.handler(errorField);
						dialog_SuppCode.handler(errorField);

					}
					if(oper!='add'){
						dialog_PurReqNo.check(errorField);
						dialog_reqdept.check(errorField);
						dialog_DelDept.check(errorField);
						dialog_SuppCode.handler(errorField);
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
				table_name:'material.delordhd',
				table_id:'recno'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:['prdept','purreqno','reqdept','deldept','suppcode','termdays','purdate','expecteddate','remarks'],
				oper:oper,
				table_name:'material.purordhd',
				table_id:'recno'
			};

			//////////////////////////start grid/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Record', name: 'recno', sorttype: 'number', width: 5,checked:true},
					{ label: 'Do No', name: 'delordno', width: 5, canSearch:true},
					{ label: 'Purchase Dept', name: 'prdept', width: 10, canSearch:true},
					{ label: 'Supplier', name: 'suppcode', width: 10},
					{ label: 'Invoice No', name: 'invoiceno', width: 10},
					{ label: 'Delivery Date', name: 'deldate', width: 10},
					{ label: 'Total', name: 'totamount', width: 10},	
					{ label: 'Status', name: 'impflg', width: 10},	
					{ label: 'docno', name: 'docno', hidden: true},	
					{ label: 'srcdocno', name: 'srcdocno', hidden: true},	
					{ label: 'deldept', name: 'deldept', hidden: true},	
					{ label: 'subamount', name: 'subamount', hidden: true},	
					{ label: 'amtdisc', name: 'amtdisc', hidden: true},	
					{ label: 'perdisc', name: 'perdisc', hidden: true},	
					{ label: 'trandate', name: 'trandate', hidden: true},	
					{ label: 'trantime', name: 'trantime', hidden: true},	
					{ label: 'respersonid', name: 'respersonid', hidden: true},	
					{ label: 'checkpersonid', name: 'checkpersonid', hidden: true},	
					{ label: 'checkdate', name: 'checkdate', hidden: true},	
					{ label: 'suppperson', name: 'suppperson', hidden: true},	
					{ label: 'recstatus', name: 'recstatus', hidden: true},	
					{ label: 'remarks', name: 'remarks', hidden: true},	
					{ label: 'reason', name: 'reason', hidden: true},	
					{ label: 'rtnflg', name: 'rtnflg', hidden: true},	
					{ label: 'reqdept', name: 'reqdept', hidden: true},	
					{ label: 'credcode', name: 'credcode', hidden: true},	
					{ label: 'allocdate', name: 'allocdate', hidden: true},	
					{ label: 'postdate', name: 'postdate', hidden: true}
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				height: 124,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						urlParam_suppitems.filterVal[0]=rowid;//supp item grid for suppcode
						saveParam_suppitems.filterVal[0]=rowid;
						refreshGrid('#gridSuppitems',urlParam_suppitems);
						$("#pg_jqGridPager2 table").show();
					}
				},
			});
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				edit:false,view:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					
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
				title:"Add New Header", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
			});

			$("#jqGrid").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'recno', numberOfColumns: 7, titleText: 'Delivery Order Header'},
			  ]
			});

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////// suppitems //////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			var oper_suppitems;
			/**var urlParam_suppitems={
				action:'get_table_default',
				field:[lineno_, pricecode,itemcode,uomcode,unitprice,remarks,qtyorder],
				table_name:['material.suppitems si','material.product p'],
				table_id:'lineno_',
				join_type:['LEFT JOIN'],
				join_onCol:['si.itemcode'],
				join_onVal:['p.itemcode'],
				filterCol:['si.SuppCode','si.compcode','p.compcode'],
				filterVal:['','session.company','session.company'],//suppcode set when click supplier grid
			}
**/

			var urlParam_suppitems={
				action:'get_table_default',
				field:['recno','lineno_', 'pricecode','itemcode','uomcode','unitprice','remarks','qtyorder'],
				table_name:['material.purorddt'],
				table_id:'recno',
				filterCol:['linkid'],
				filterVal:[''],//suppcode set when click supplier grid
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

			function galGridCustomEdit(val,opt)
			{  return $('<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" data-validation="required" style="width=50% !important;float:LEFT;"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>');
			}
			function galGridCustomValue (elm,p,v)
			{	
			 if (p=='get')
			  { alert ('get');
			     return $(elem).val();
			  } else
			  {	alert ('set');
			  }
			}

			$("#gridSuppitems").jqGrid({
				datatype: "local",
				 colModel: [
				 	{ label: 'recno', name: 'recno', width: 10, sorttype: 'text', editable: true, classes: 'wrap',hidden:true},
				 	{ label: 'Line', name: 'lineno_', width: 10, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Price Code', name: 'pricecode', width: 10, sorttype: 'text',editable: true,  classes: 'wrap', canSearch: true,edittype:'custom',
						editoptions:
					    {  custom_element:galGridCustomEdit,
					       custom_value:galGridCustomValue 	
					    },},
					{ label: 'Item Code', name: 'itemcode', width: 50, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Uom Code', name: 'uomcode', width: 10, sorttype: 'text', editable: true, classes: 'wrap'},
					{ label: 'Unit Price', name: 'unitprice', width: 20, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'Remarks', name: 'remarks', width: 50, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'Quantity', name: 'qtyorder', width: 10, sorttype: 'float', editable: true, classes: 'wrap'},				
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
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
				},
				
			});

			$("#gridSuppitems").jqGrid('inlineNav','#jqGridPager2',{	
				edit:false,
				add:true,

			});
			

			addParamField('#gridSuppitems',false,urlParam_suppitems);

			populateSelect('#gridSuppitems','#searchForm2');
			searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);
			toogleSearch('#sbut2','#searchForm2','off');

			$("#pg_jqGridPager2 table").hide();
		});
		