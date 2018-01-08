
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			/////////////////////////validation//////////////////////////
			$.validate({
				modules : 'sanitize',
				language : {
					requiredFields: ''
				}
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
			/*var getUrlParameter = function getUrlParameter(sParam) {
			    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			        sURLVariables = sPageURL.split('&'),
			        sParameterName,
			        i;

					    for (i = 0; i < sURLVariables.length; i++) {
					        sParameterName = sURLVariables[i].split('=');

					        if (sParameterName[0] === sParam) {
					            return sParameterName[1] === undefined ? true : sParameterName[1];
					        } else{
					        	return sParameterName[0] === undefined ? true : sParameterName[0];
					        }
					    }
			};

			$.get("#formdata", '#jqGrid', function() {				
					var gc = getUrlParameter('groupcode');
					alert(gc);
					console.log(gc);

					urlParam.table_name='material.product';
					urlParam.table_id='itemcode';
					//urlParam.field=['itemcode','description','groupcode'];
					urlParam.filterCol=['groupcode'];
					urlParam.filterVal=[$('#groupcode2').val()];
					refreshGrid('#jqgrid',urlParam);

						if(gc.toLowerCase() == 'Stock'.toLowerCase()) {
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
						} else if(gc.toLowerCase() == 'Asset'.toLowerCase()) {
								$("#formdata :input[id='groupcodeStock']").hide();
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
						} else if(gc.toLowerCase() == 'Other'.toLowerCase()) {
								$("#formdata :input[id='groupcodeStock']").hide();
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
						} else  {
							urlParam.table_name='material.product';
							urlParam.table_id='itemcode';
							urlParam.field=['itemcode','description','groupcode'];
							urlParam.filterCol=null;
							urlParam.filterVal=null;
							refreshGrid('#jqGrid',urlParam);
							console.log(urlParam);

						}
			});*/

			$.get("#formdata", "#jqGrid", function() {
				var gc2 = $('#groupcode2').val();
				//alert(gc2);

						if(gc2.toLowerCase() == 'Stock'.toLowerCase()) {
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
								$("#groupcodeStock").prop("checked", true);
								dialog_cat=new makeDialog('material.category','#productcat',['catcode','description'], 'Category');
						} else if(gc2.toLowerCase() == 'Asset'.toLowerCase()) {
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
								$("#groupcodeAsset").prop("checked", true);
								dialog_cat=new makeDialog('finance.facode','#productcat',['assetcode','description'], 'Category');
						} else if(gc2.toLowerCase() == 'Other'.toLowerCase()) {
								$("#formdata :input[id='groupcodeStock']").hide();
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
								$("#groupcodeOther").prop("checked", true);
								dialog_cat=new makeDialog('material.category','#productcat',['catcode','description'], 'Category');
						} else {
							//$('#formdata :input[hideOne]').hide();
							//alert("fff");
							urlParam.table_name='material.product';
							urlParam.table_id='itemcode';
							urlParam.field=['itemcode','description','groupcode'];
							urlParam.filterCol=null;
							urlParam.filterVal=null;
							refreshGrid('#jqGrid',urlParam);
							//alert("cs");
							console.log(urlParam);

						}

			});

			/*
			if(gc == null){
				var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'material.product',
				table_id:'itemcode',
				filterCol:['groupcode'],
				filterVal:[$('#groupcode2').val()]
			}}
			else{
				var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'material.product',
				table_id:'itemcode',
				filterCol:['groupcode'],
				filterVal:[$('#groupcode2').val()]
			}
			}

			*/




			////////////////////object for dialog handler//////////////////
			dialog_uomcode=new makeDialog('material.uom','#uomcode',['uomcode','description'], 'UOM Code');
			dialog_pouom=new makeDialog('material.uom','#pouom',['uomcode','description'], 'UOM Code');
			dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'] , 'Supplier Code');
			dialog_mstore=new makeDialog('sysdb.department','#mstore',['deptcode','description'], 'Main Store');
			dialog_subcategory=new makeDialog('material.subcategory','#subcatcode',['subcatcode','description'], 'Sub Category');


			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					
					//////////////////////////////itemtype/////////////////////////////////////
					var itemtype = $("input[name=itemtype]:checked").val();
					//alert(itemtype);
				    if(!itemtype){
				     	$("label[for=itemtype]").css('color', 'red');
				     	$(":radio[name='itemtype']").parent('label').css('color', 'red');
					}
					else{
						$("label[for=itemtype]").css('color', '#444444');
						$(":radio[name='itemtype']").parent('label').css('color', '#444444');
					}

					//////////////////////////////reuse/////////////////////////////////////
					var reuse = $("input[name=reuse]:checked").val();
				    if(!reuse){
				        $("label[for=reuse]").css('color', 'red');
				     	$(":radio[name='reuse']").parent('label').css('color', 'red');
					}else{
						$("label[for=reuse]").css('color', '#444444');
						$(":radio[name='reuse']").parent('label').css('color', '#444444');
					}

					//////////////////////////////rpkitem/////////////////////////////////////

				     var rpkitem = $("input[name=rpkitem]:checked").val();
				     if(!rpkitem){
				        $("label[for=rpkitem]").css('color', 'red');
				     	$(":radio[name='rpkitem']").parent('label').css('color', 'red');
					}else{
						$("label[for=rpkitem]").css('color', '#444444');
						$(":radio[name='rpkitem']").parent('label').css('color', '#444444');
					}

					//////////////////////////////tagging/////////////////////////////////////
				     var tagging = $("input[name=tagging]:checked").val();
				     if(!tagging){
				        $("label[for=tagging]").css('color', 'red');
				     	$(":radio[name='tagging']").parent('label').css('color', 'red');
					}else{
						$("label[for=tagging]").css('color', '#444444');
						$(":radio[name='tagging']").parent('label').css('color', '#444444');
					}

					//////////////////////////////expdtflg/////////////////////////////////////
				     var expdtflg = $("input[name=expdtflg]:checked").val();
				     if(!expdtflg){
				        $("label[for=expdtflg]").css('color', 'red');
				     	$(":radio[name='expdtflg']").parent('label').css('color', 'red');
					}else{
						$("label[for=expdtflg]").css('color', '#444444');
						$(":radio[name='expdtflg']").parent('label').css('color', '#444444');
					}

					//////////////////////////////chgflag/////////////////////////////////////
				     var chgflag = $("input[name=chgflag]:checked").val();
				     if(!chgflag){
				         $("label[for=chgflag]").css('color', 'red');
				     	$(":radio[name='chgflag']").parent('label').css('color', 'red');
					}else{
						$("label[for=chgflag]").css('color', '#444444');
						$(":radio[name='chgflag']").parent('label').css('color', '#444444');
					}

				     ////////////////////////////////////////////////////////////////////////

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
							getgcforAdd();
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
						} if(oper!='view'){
							dialog_uomcode.handler(errorField);
							dialog_pouom.handler(errorField);
							dialog_suppcode.handler(errorField);
							dialog_mstore.handler(errorField);
							dialog_cat.handler(errorField);
							dialog_subcategory.handler(errorField);
						} if(oper!='add'){
							toggleFormData('#jqGrid','#formdata');
							dialog_uomcode.check(errorField);
							dialog_pouom.check(errorField);
							dialog_suppcode.check(errorField);
							dialog_mstore.check(errorField);
							dialog_cat.check(errorField);
							dialog_subcategory.check(errorField);
						}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata');
					$('.alert').detach();
					$("#formdata a").off();
					textcolourradio();
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
				filterCol:['groupcode'],
				filterVal:[$('#groupcode2').val()]
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.product',
				table_id:'itemcode'
			};
			
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
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', formatter:formatter, unformat:unformat,  cellattr: function(rowid, cellvalue)
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
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
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
					return "A";
				}
				if(cellvalue == 'Deactive') { 
					return "D";
				}
			}

			function getgcforAdd() {
				var gc2 = $('#groupcode2').val();
				if (gc2.toLowerCase() == 'Stock'.toLowerCase()) {
					$("#groupcodeStock").prop("checked", true);
				} else if(gc2.toLowerCase() == 'Asset'.toLowerCase()) {
					$("#groupcodeAsset").prop("checked", true);
				} else if(gc2.toLowerCase() == 'Other'.toLowerCase()) {
					$("#groupcodeOther").prop("checked", true);
				}

			}

			function textcolourradio() {
				$("label[for=itemtype]").css('color', '#444444');
				$(":radio[name='itemtype']").parent('label').css('color', '#444444');
				$("label[for=reuse]").css('color', '#444444');
				$(":radio[name='reuse']").parent('label').css('color', '#444444');
				$("label[for=rpkitem]").css('color', '#444444');
				$(":radio[name='rpkitem']").parent('label').css('color', '#444444');
				$("label[for=tagging]").css('color', '#444444');
				$(":radio[name='tagging']").parent('label').css('color', '#444444');
				$("label[for=expdtflg]").css('color', '#444444');
				$(":radio[name='expdtflg']").parent('label').css('color', '#444444');
				$("label[for=chgflag]").css('color', '#444444');
				$(":radio[name='chgflag']").parent('label').css('color', '#444444');
			}
			

			/*$('#subcatcode').on('click',  function(){
				//subcatcode = $('#subcatcode').val();
				//alert(subcatcode);
				if( !$('#subcatcode').val()) {
					alert("vs");
					$('#subcatcode').parent().siblings( ".help-block" ).hide();
					$('#subcatcode').parent().removeClass( "has-error" );
					$('#subcatcode').removeClass( "error" );
				}
				
			});*/
			


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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'itemcode':selRowId});
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
			//$("label[for='groupcode']").hide();
	//		$("groupcodeStock").hide();

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
						var gc2 = $('#groupcode2').val();
						if(gc2.toLowerCase() == 'Stock'.toLowerCase()){
							paramD.filterCol=['cattype', 'source'];
							paramD.filterVal=['Stock', 'PO'];
						}else if(gc2.toLowerCase() == 'Other'.toLowerCase()) {
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
			function dialogHandler(errorField){
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
					self.check(errorField);
				});
			}
			
			function checkInput(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
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