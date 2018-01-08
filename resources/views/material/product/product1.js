
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
			dialog_uomcode=new makeDialog('material.uom','#uomcode',['uomcode','description'], 'UOM Code');
			dialog_pouom=new makeDialog('material.uom','#pouom',['uomcode','description'], 'UOM Code');
			dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'] , 'Supplier Code');
			dialog_mstore=new makeDialog('sysdb.department','#mstore',['deptcode','description'], 'Main Store');


			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					var groupcode = $("input[name=groupcode]:checked").val();
				     if(!groupcode){
				         alert('Group Code Not Selected');
				     }
					var itemtype = $("input[name=itemtype]:checked").val();
				     if(!itemtype){
				         alert('Item Type Not Selected');
				     }
					var reuse = $("input[name=reuse]:checked").val();
				     if(!reuse){
				         alert('Reuse Not Selected');
				     }
				     var rpkitem = $("input[name=rpkitem]:checked").val();
				     if(!rpkitem){
				         alert('Repack Item Not Selected');
				     }

				     var tagging = $("input[name=tagging]:checked").val();
				     if(!tagging){
				         alert('Tagging Not Selected');
				     }

				     var expdtflg = $("input[name=expdtflg]:checked").val();
				     if(!expdtflg){
				         alert('Expiry Date Not Selected');
				     }

				     var chgflag = $("input[name=chgflag]:checked").val();
				     if(!chgflag){
				         alert('Charge Not Selected');
				     }

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
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							$("label[for=productcat]").hide();
							disableC();
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
						dialog_uomcode.handler(errorField);
						dialog_pouom.handler(errorField);
						dialog_suppcode.handler(errorField);
						dialog_mstore.handler(errorField);
						
					}
					if(oper!='add'){
						toggleFormData('#jqGrid','#formdata');
						dialog_uomcode.check(errorField);
						dialog_pouom.check(errorField);
						dialog_suppcode.check(errorField);
						dialog_mstore.check(errorField);
						dialog_cat.check(errorField);
						dialog_cat2.check(errorField);
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
				table_name:'material.product',
				table_id:'itemcode',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.product',
				table_id:'itemcode'
			};

			////////////////////////////////////////////////////////////////////////////////////////////

			function phpCategory() {
				$("label[for=productcat]").show();
				$("#productcat").show();
				$("#2").removeClass("hidden");
				$("#3").addClass("hidden");
			}

			function disableC() {
				$("#productcat").hide();
				$("#2").addClass("hidden");
				$("#3").removeClass("hidden")
			}

			$("input[name=groupcode]:radio").on('change',  function(){
					groupcode = $("input[name=groupcode]:checked").val()
					//alert(groupcode);

					if(groupcode == 'Asset') {
						//alert("1");
						dialog_cat=new makeDialog('finance.facode','#productcat',['assetcode','description'], 'Category');
						dialog_cat.handler(errorField);
						phpCategory()
					}else if(groupcode == 'Stock') {
						//alert("2");
						phpCategory()
						dialog_cat=new makeDialog('material.category','#productcat',['catcode','description'], 'Category');
						dialog_cat.handler(errorField);
					} else if(groupcode == 'Other') {
						//alert("2");
						phpCategory()
						dialog_cat=new makeDialog('material.category','#productcat',['catcode','description'], 'Category');
						dialog_cat.handler(errorField);
					}


			});

			/////////////////////////////// jQgrid /////////////////////////////////////////////////////
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
					{ label: 'Item Code', name: 'itemcode', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true, checked: true},
					{ label: 'Item Description', name: 'description', width: 80, sorttype: 'text', classes: 'wrap', canSearch: true  },
					{ label: 'Uom Code', name: 'uomcode', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'Group Code', name: 'groupcode', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'Product Category', name: 'productcat', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'Supplier Code', name: 'suppcode', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'avgcost', name: 'avgcost', width: 50, hidden:true },
					{ label: 'actavgcost', name: 'actavgcost', width: 50, hidden:true },
					{ label: 'currprice', name: 'currprice', width: 50, hidden:true },
					{ label: 'qtyonhand', name: 'qtyonhand', width: 50, hidden:true },
					{ label: 'bonqty', name: 'bonqty', width: 50, hidden:true },
					{ label: 'rpkitem', name: 'rpkitem', width: 50, hidden:true },
					{ label: 'minqty', name: 'minqty', width: 50, hidden:true },
					{ label: 'maxqty', name: 'maxqty', width: 50, hidden:true },
					{ label: 'reordlevel', name: 'reordlevel', width: 50, hidden:true },
					{ label: 'reordqty', name: 'reordqty', width: 50, hidden:true },
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatter, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{ label: 'chgflag', name: 'chgflag', width: 50, hidden:true },
					{ label: 'subcatcode', name: 'subcatcode', width: 50, hidden:true },
					{ label: 'expdtflg', name: 'expdtflg', width: 50, hidden:true },
					{ label: 'mstore', name: 'mstore', width: 50, hidden:true },
					{ label: 'costmargin', name: 'costmargin', width: 50, hidden:true },
					{ label: 'pouom', name: 'pouom', width: 50, hidden:true },
					{ label: 'reuse', name: 'reuse', width: 50, hidden:true },
					{ label: 'trqty', name: 'trqty', width: 50, hidden:true },
					{ label: 'deactivedate', name: 'deactivedate', width: 50, hidden:true },
					{ label: 'tagging', name: 'tagging', width: 50, hidden:true },
					{ label: 'itemtype', name: 'itemtype', width: 50, hidden:true },
					{ label: 'generic', name: 'generic', width: 50, hidden:true },
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'itemcode':selRowId});
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
			addParamField('#jqGrid',false,saveParam);

			///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.check=checkInput;
			}

			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
					if(selText=='#mstore'){
						paramD.filterCol=['mainstore'];
						paramD.filterVal=['1'];
					}else if(selText=='#productcat') {
						var groupcode = $("input[name=groupcode]:checked").val()
						if(groupcode == 'Stock'){
							paramD.filterCol=['cattype', 'source'];
							paramD.filterVal=['Stock', 'PO'];
						}else if(groupcode == 'Other') {
							paramD.filterCol=['cattype', 'source'];
							paramD.filterVal=['Other', 'PO'];
						}else{
						paramD.filterCol=null;
						paramD.filterVal=null;
						}
					}else{
						paramD.filterCol=null;
						paramD.filterVal=null;
					}
				},
				close: function( event, ui ){
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer'},
				],
				width: 500,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
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
					self.check();
				});
			}
			
			function checkInput(){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						var index = errorField.indexOf(id);
						if (index > -1) {
							errorField.splice(index, 1);
						}
						$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						errorField.push(id);
						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
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