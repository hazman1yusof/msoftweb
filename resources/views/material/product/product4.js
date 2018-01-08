
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
					if(errorField.length>0){
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
			dialog_itemcode=new makeDialog('material.productmaster','#itemcodesearch',['itemcode','description', 'groupcode', 'productcat', 'Class'], 'Item Code');
			dialog_uomcode=new makeDialog('material.uom','#uomcodesearch',['uomcode','description'], 'UOM Code');

			dialog_pouom=new makeDialog('material.uom','#pouom',['uomcode','description'], 'PO UOM');
			dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'] , 'Supplier Code');
			dialog_mstore=new makeDialog('sysdb.department','#mstore',['deptcode','description'], 'Main Store');
			dialog_subcategory=new makeDialog('material.subcategory','#subcatcode',['subcatcode','description'], 'Sub Category');
			dialog_taxCode=new makeDialog('hisdb.taxmast','#TaxCode',['taxcode','description'], 'Tax Code');

			dialog_cat=new makeDialog('material.category','#productcatAddNew',['catcode','description'], 'Product Category');
			dialog_cat.handler(errorField);
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
							//rdonly("#dialogForm");
							whenEdit();
							getgcforAdd();
							$("#Cancel").hide();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							whenEdit();
							$("#Cancel").hide();
							hiderad.check();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_itemcode.handler(errorField);
						dialog_uomcode.handler(errorField);
						
						dialog_pouom.handler(errorField);
						dialog_suppcode.handler(errorField);
						dialog_mstore.handler(errorField);
						//dialog_cat.handler(errorField);
						dialog_subcategory.handler(errorField);
						dialog_taxCode.handler(errorField);

						//dialog_cat2.handler(errorField);
					}
					if(oper!='add'){
						dialog_pouom.check(errorField);
						dialog_suppcode.check(errorField);
						dialog_mstore.check(errorField);
						//dialog_cat.check(errorField);
						//dialog_cat.offHandler();
						dialog_subcategory.check(errorField);
						dialog_taxCode.check(errorField);		 
					}
					if(oper == 'add') {
						dialog_pouom.offHandler();
						dialog_suppcode.offHandler();
						dialog_mstore.offHandler();
						//dialog_cat.offHandler();
						dialog_subcategory.offHandler();
						dialog_taxCode.offHandler();
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');

					//$("#sss input").val('');
					emptyFormdata(errorField,'#formdataSearch');
					emptyFormdata(errorField,'#formdata');
					//$('.alert').detach();
					urlParam.filterCol=['groupcode', 'Class'];
					urlParam.filterVal=[$('#groupcode2').val(), $('#Class2').val()];
					refreshGrid('#jqGrid',urlParam);


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
				table_name:'material.product',
				field:'',
				table_id:'idno',
				sort_idno:true,
				filterCol:['groupcode', 'Class'],
				filterVal:[$('#groupcode2').val(), $('#Class2').val()]
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.product',
				table_id:'idno',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Item Code', name: 'itemcode', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true, checked: true},
					{ label: 'Item Description', name: 'description', width: 80, sorttype: 'text', classes: 'wrap', canSearch: true  },
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
					{label: 'idno', name: 'idno', hidden: true},

					{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
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
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}
					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					if(searched){
						populateFormdata("#jqGrid","#dialogForm","#formdata", idno,'view');
						searched = false;
						//console.log($("#jqGrid").getGridParam("reccount"));
						//console.log($("#jqGrid").getGridParam("reccount") < 1)

						if($("#jqGrid").getGridParam("reccount") >= 1){
							$("#Save").hide();
							alert("Data Already Exist");
							//emptyFormdata(errorField,'#formdataSearch');
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
						//alert(itemtype);
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
						//console.log(value);
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

				dialog_itemcode.offHandler();
				dialog_uomcode.offHandler();
				dialog_pouom.handler(errorField);
				dialog_suppcode.handler(errorField);
				dialog_mstore.handler(errorField);
				//dialog_cat.handler(errorField);
				dialog_subcategory.handler(errorField);
				dialog_taxCode.handler(errorField);

				urlParam.filterCol = ['itemcode','uomcode'];
				urlParam.filterVal = [$('#itemcodesearch').val(),$('#uomcodesearch').val()];
				
				searched = true;

				refreshGrid('#jqGrid',urlParam);

				$("#formdata :input[name='itemcode']").val($("#itemcodesearch").val());
				$("#formdata :input[name='uomcode']").val($("#uomcodesearch").val());

				$("#formdata :input[name='description']").val(description);
				$("#formdata :input[name='productcat']").val(productcat);
				

				//dialog_cat.offHandler();
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
				dialog_itemcode.handler(errorField);
				dialog_uomcode.handler(errorField);
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selRowId});
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
			addParamField('#jqGrid',false,saveParam,['idno']);

			////////////////////////////////////////////////////////addNewProduct ///////////////////////////////
			var adpsaveParam={
				action:'save_table_default',
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
							dialog_cat.updateField('material.category','#productcatAddNew',['catcode','description'], 'Product Category');
							dialog_cat.offHandler();
							dialog_cat.handler(errorField);
						} else if(gc2=="Asset") {
							dialog_cat.updateField('finance.facode','#productcatAddNew',['assetcode','description'], 'Product Category');
							dialog_cat.offHandler();
							dialog_cat.handler(errorField);
						} else if(gc2=="Others") {
							dialog_cat.updateField('material.category','#productcatAddNew',['catcode','description'], 'Product Category');
							dialog_cat.offHandler();
							dialog_cat.handler(errorField);
						}
				}
			}];


			var addNew2=[{
				text: "Save",click: function() {
					if( $('#adpFormdata').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata('#gridDialog',"#addNewProductDialog","#adpFormdata",'add',adpsaveParam,paramD);
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
			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.offHandler=function(){
					$( this.id+" ~ a" ).off();
				}
				this.check=checkInput;
				this.updateField=function(table,id,cols,title){
					this.table=table;
					this.id=id;
					this.cols=cols;
					this.title=title;
					//console.log(this);
				}
			}

			$("#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
					if(selText=='#mstore'){
						paramD.filterCol=['mainstore', 'recstatus'];
						paramD.filterVal=['1', 'A'];
					}else if(selText=='#itemcodesearch') {
						var gc2 = $('#groupcode2').val();
						var Class2 = $('#Class2').val();
							paramD.filterCol=['groupcode', 'Class', 'recstatus'];
							paramD.filterVal=[gc2, Class2, 'A'];
					}else if(selText=='#productcatAddNew') {
						var gc2 = $('#groupcode2').val();
						if(gc2.toLowerCase() == 'Stock'.toLowerCase()){
							paramD.filterCol=['cattype', 'source', 'recstatus'];
							paramD.filterVal=['Stock', 'PO', 'A'];
						}else if(gc2.toLowerCase() == 'Others'.toLowerCase()) {
							paramD.filterCol=['cattype', 'source', 'recstatus'];
							paramD.filterVal=['Other', 'PO', 'A'];
						}else{
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
						}
					}else if(selText == "#TaxCode"){
						paramD.filterCol=['recstatus','taxtype'];
						paramD.filterVal=['A','Input'];
					}else{
						paramD.filterCol=['recstatus'];
						paramD.filterVal=['A'];
					}
				},
				close: function( event, ui ){
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
				buttons :addNew,
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 30,  classes: 'pointer', canSearch:true,checked:true, classes: 'wrap'},
					{ label: 'Description', name: 'description', width: 70, canSearch:true, classes: 'pointer', classes: 'wrap'},
					{ label: 'Group Code', name: 'groupcode', width: 30, classes: 'pointer', classes: 'wrap'},
					{ label: 'Product Category', name: 'productcat', width: 30, classes: 'pointer', classes: 'wrap'},
					{ label: 'Class', name: 'Class', width: 40, classes: 'pointer', classes: 'wrap'},
				],
				width: 450,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				shrinkToFit: true,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['description']);

					if(selText=="#itemcodesearch"){
						productcat=data.productcat;
						groupcode=data.groupcode;
						description=data.description;
						Class=data.Class;
					} 
				},
				
			});

			

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:'',
				sort_idno:true};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);

					if(selText == "#itemcodesearch"){
						$("#addnp").show();
						$("#gridDialog").jqGrid('showCol', 'groupcode');
						$("#gridDialog").jqGrid('showCol', 'productcat');
						$("#gridDialog").jqGrid('showCol', 'Class');
					}else{
						$("#addnp").hide();
						$("#gridDialog").jqGrid('hideCol', 'groupcode');
						$("#gridDialog").jqGrid('hideCol', 'productcat');
						$("#gridDialog").jqGrid('hideCol', 'Class');
					}

					$("#dialog").dialog( "open" );
					$("#dialog").dialog( "option", "title", title );


					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							if(value['checked']){
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+value['label']+"</input></label>" );
							}else{
								$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
							}
						}
					});
				});
				$(id).on("blur", function(){
					self.check(errorField);
				});
			}
			
			function checkInput(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {

				},'json').done(function(data) {
					if(data.msg=='success'){
						if(id== "#itemcodesearch") {
							//console.log("success");
							dialog_uomcode.handler(errorField);
							//$("#uomcodesearch_parent").show();
						}
						if(!searched && id=="#itemcodesearch"){
							productcat=data.row.productcat;
							groupcode=data.row.groupcode;
							description=data.row.description;
							Class=data.row.Class;
						}
						if($.inArray(id,errorField)!==-1){
							errorField.splice($.inArray(id,errorField), 1);
						}
						$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
						$( id ).removeClass( "error" ).addClass( "valid" );
						$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
						$( id ).parent().siblings( ".help-block" ).show();
					}else if(data.msg=='fail'){
						if((id == '#subcatcode') && ($('#subcatcode').val()== "")) {
								$( id ).parent().removeClass( "has-success" ).removeClass( "has-error" );
								$( id ).removeClass( "valid" ).removeClass( "error" );
								$( id ).parent().siblings( ".help-block" ).hide();
						}else if((id == '#mstore') && ($('#mstore').val()== "")) {
								$( id ).parent().removeClass( "has-success" ).removeClass( "has-error" );
								$( id ).removeClass( "valid" ).removeClass( "error" );
								$( id ).parent().siblings( ".help-block" ).hide();
						}else if((id == '#pouom') && ($('#pouom').val()== "")) {
								$( id ).parent().removeClass( "has-success" ).removeClass( "has-error" );
								$( id ).removeClass( "valid" ).removeClass( "error" );
								$( id ).parent().siblings( ".help-block" ).hide();
						}else if((id == '#suppcode') && ($('#suppcode').val()== "")) {
								$( id ).parent().removeClass( "has-success" ).removeClass( "has-error" );
								$( id ).removeClass( "valid" ).removeClass( "error" );
								$( id ).parent().siblings( ".help-block" ).hide();
						}else{
							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
							if($.inArray(id,errorField)===-1){
								errorField.push(id);
							}
						}

						if(id== "#itemcodesearch") {
							//console.log("fail");
							dialog_uomcode.offHandler();
							//$("#uomcodesearch_parent").hide();
						}
					}
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				paramD.searchCol=null;
				paramD.searchVal=null;
				Dtext=Dtext.trim();
				if(Dtext != ''){
					var split = Dtext.split(" "),searchCol=[],searchVal=[];
					$.each(split, function( index, value ) {
						searchCol.push(Dcol);
						searchVal.push('%'+value+'%');
					});
					paramD.searchCol=searchCol;
					paramD.searchVal=searchVal;
				}
				refreshGrid("#gridDialog",paramD);
			}
			///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////

		});
