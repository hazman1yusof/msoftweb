		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var bc;
//fix for jqgrid 
//http://stackoverflow.com/questions/30795713/uncaught-typeerror-cannot-read-property-errcap-of-undefined
$.jgrid.getRegional = function(inst, param, def_val) {
    try{
        if (inst instanceof jQuery){
            inst = inst.get(0);
        }
    }catch(e){}
    var ret;
    if(def_val !== undefined) {
        return def_val;
    }
    if(inst.p && inst.p.regional && $.jgrid.regional) {
            ret = $.jgrid.getAccessor( $.jgrid.regional[inst.p.regional] || {}, param);
    }
    if(ret === undefined ) {
        ret = $.jgrid.getAccessor( $.jgrid, param);
    }
    return ret;
}
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
					//$('#gridSuppitems').jqGrid('GridUnload');
					//$("#gridSuppitems").jqGrid("clearGridData", true).trigger("reloadGrid");
					//$("#gridSuppitems").jqGrid('reloadGrid');
					toggleFormData('#jqGrid','#formdata',oper);
					switch(oper) {
						case state = 'add':
							var trf = $("#gridSuppitems tbody:first tr:first")[0];
							$("#gridSuppitems tbody:first").empty().append(trf);
							$("#jqGrid").jqGrid('resetSelection');
							$( this ).dialog( "option", "title", "Add PO" );
							enableForm('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit PO" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View PO" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_PurReqNo.handler(errorField);
						dialog_reqdept.handler(errorField);
						dialog_DelDept.handler(errorField);
						dialog_SuppCode.handler(errorField);
						//dialog_pricecode.handler(errorField);

					}
					if(oper!='add'){
						dialog_PurReqNo.check(errorField);
						dialog_reqdept.check(errorField);
						dialog_DelDept.check(errorField);
						dialog_SuppCode.check(errorField);
						//dialog_pricecode.check(errorField);
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
				//field:['tillcode','description','dept','effectdate','defopenamt'],['recno','purordno','prdept','purdate','totamount','expflg']
				table_name:'material.purordhd',
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
					{ label: 'Record', name: 'recno', sorttype: 'number', width: 100,checked:true},
					{ label: 'PO No', name: 'purordno', width: 100, canSearch:true},
					{ label: 'Purchase Dept', name: 'prdept', width: 150, canSearch:true},
					{ label: 'Date', name: 'purdate', width: 150},
					{ label: 'Total', name: 'totamount', width: 150},	
					{ label: 'Status', name: 'expflg', width: 150},
					{ label: 'reqdept', name: 'reqdept' , hidden: true},
					{ label: 'deldept', name: 'deldept' , hidden: true},
					{ label: 'expecteddate', name: 'expecteddate' , hidden: true},
					{ label: 'expirydate', name: 'expirydate' , hidden: true},
					{ label: 'suppcode', name: 'suppcode' , hidden: true},
					{ label: 'credcode', name: 'credcode' , hidden: true},
					{ label: 'termdays', name: 'termdays' , hidden: true},
					{ label: 'subamount', name: 'subamount' , hidden: true},
					{ label: 'amtdisc', name: 'amtdisc' , hidden: true},
					{ label: 'perdisc', name: 'perdisc' , hidden: true},
					{ label: 'isspersonid', name: 'isspersonid' , hidden: true},
					{ label: 'issdate', name: 'issdate' , hidden: true},
					{ label: 'authpersonid', name: 'authpersonid' , hidden: true},
					{ label: 'authdate', name: 'authdate' , hidden: true},
					{ label: 'remarks', name: 'remarks' , hidden: true},
					{ label: 'assflg', name: 'assflg' , hidden: true},
					{ label: 'potype', name: 'potype' , hidden: true},
					{ label: 'purreqno', name: 'purreqno' , hidden: true}
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
						refreshGrid('#gridSuppitems',urlParam_suppitems);
						//$("#pg_jqGridPager2 table").show();
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
				{startColumnName: 'recno', numberOfColumns: 6, titleText: 'Purchase Order Header'},
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
			var oper2;
			var oper_suppitems;

			var urlParam_suppitems={
				action:'get_table_default',
				field:['recno','lineno_', 'pricecode','itemcode','uomcode','unitprice','remarks','qtyorder'],
				table_name:['material.purorddt'],
				table_id:'recno',
				filterCol:['linkid'],
				filterVal:[''],//suppcode set when click supplier grid
			}

			$("#gridSuppitems").jqGrid({
				editurl:"purodrdtlsave.php",
				datatype: "local",
				 colModel: [
				 	{ label: 'recno', name: 'recno', width: 30, sorttype: 'text', editable: true, classes: 'wrap',hidden:true},
				 	{ label: 'Line', name: 'lineno_', width: 50, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Price Code', name: 'pricecode', width: 90, sorttype: 'text', editable: true, classes: 'wrap', canSearch: true,edittype:'custom',	editoptions:
					    {  custom_element:galGridCustomEdit,
					       custom_value:galGridCustomValue 	
					    },
					},
					{ label: 'Item Code', name: 'itemcode', width: 250, sorttype: 'text', editable: true, classes: 'wrap',edittype:'custom',	editoptions:
					    {  custom_element:itemcodeCustomEdit,
					       custom_value:galGridCustomValue 	
					    },
					},
					{ label: 'Uom Code', name: 'uomcode', width: 90, sorttype: 'text', editable: true, classes: 'wrap',edittype:'custom',	editoptions:
					    {  custom_element:uomcodeCustomEdit,
					       custom_value:galGridCustomValue 	
					    },
					},
					{ label: 'Unit Price', name: 'unitprice', width: 80, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'Remarks', name: 'remarks', width: 350, sorttype: 'float', edittype:'textarea', editable: true, classes: 'wrap'},
					{ label: 'Quantity', name: 'qtyorder', width: 70, sorttype: 'float', editable: true, classes: 'wrap'},
					{ label: 'linkid', name: 'linkid', editable: true,hidden:true},
					{ label: 'amtdisc', name: 'amtdisc', hidden:true},
					{ label: 'perdisc', name: 'perdisc', hidden:true},
					{ label: 'amtslstax', name: 'amtslstax', hidden:true},
					{ label: 'perslstax', name: 'perslstax', hidden:true},
					{ label: 'sitemcode', name: 'sitemcode', hidden:true},
					{ label: 'discflg', name: 'discflg', hidden:true},
					{ label: 'discval', name: 'discval', hidden:true},
					{ label: 'qtydelivered', name: 'qtydelivered', hidden:true},
					{ label: 'expflg', name: 'expflg', hidden:true}		
				],
				viewrecords: true,
				//autowidth:false,
				shrinkToFit: false,
                multiSort: true,
				loadonce:false,
				//width: 900,
				height: 100,
				rowNum: 30,
				pager: "#jqGridPager2",
			});
			
			

			$("#gridSuppitems").jqGrid('inlineNav','#jqGridPager2',{	
				edit:true,
				add:true
			});

			////others
			$("#gridSuppitems_iladd").click(function() {
				dialog_pricecode=new makeDialog('material.pricesource','#pricecode',['pricecode','description'], 'Price Code');
				dialog_itemcode=new makeDialog('material.product','#itemcode',['itemcode','description'], 'Item Code');
				dialog_uomcode=new makeDialog('material.uom','#uomcode',['uomcode','description'], 'UOM Code');

				dialog_pricecode.handler(errorField);
				dialog_itemcode.handler(errorField);
				dialog_uomcode.handler(errorField);
				$("input[id*='_linkid']").val($("#recno").val());
				$("input[id*='_qtyorder']").keydown(function(e) {
					//console.log('keydown called');
					var code = e.keyCode || e.which;
						if (code == '9') { // -->for tab
							$('#gridSuppitems_ilsave').click();
						}
				 });
				if(oper=='add'){
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);						
					}
					else{
						//$("#gridSuppitems_ilcancel").click();					
						return false;
					}
				}
				else{
					//$("#gridSuppitems_ilcancel").click();					
					return false;
				}
			});


			///////custom input/////
			function galGridCustomEdit(val,opt)
			{  		
				return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function itemcodeCustomEdit(val,opt)
			{  		
				return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function uomcodeCustomEdit(val,opt)
			{  		
				return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}


			function galGridCustomValue (elem, operation, value)
			{	
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}
			//
			/**$("#gridSuppitems").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'lineno_', numberOfColumns: 7, titleText: 'Purchase Order Details'},
			  ]
			});**/

			addParamField('#gridSuppitems',false,urlParam_suppitems);

			populateSelect('#gridSuppitems','#searchForm2');
			searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);
			//toogleSearch('#sbut2','#searchForm2','off');

			//$("#pg_jqGridPager2 table").hide();
		});
		