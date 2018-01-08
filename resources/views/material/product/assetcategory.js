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

		
			
		/*	$.get("#formdata", "#jqGrid", function() {
				var gc2 = $('#groupcode2').val();
				//alert(gc2);

						if(gc2.toLowerCase() == 'Stock'.toLowerCase()) {
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
						} else if(gc2.toLowerCase() == 'Asset'.toLowerCase()) {
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeOther']").hide();
								$(":radio[id='groupcodeOther']").parent('label').hide();
						} else if(gc2.toLowerCase() == 'Other'.toLowerCase()) {
								$("#formdata :input[id='groupcodeStock']").hide();
								$(":radio[id='groupcodeStock']").parent('label').hide();
								$("#formdata :input[id='groupcodeAsset']").hide();
								$(":radio[id='groupcodeAsset']").parent('label').hide();
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
			*/	
			////////////////////object for dialog handler//////////////////
			dialog_assettype=new makeDialog('finance.fatype','#assettype',['assettype','description'], 'Type');

			dialog_deptcode=new makeDialog('hisdb.department','#deptcode',['deptcode','description'], 'Department');

			dialog_glassetccode=new makeDialog('finance.costcenter','#glassetccode',['costcode','description'], 'Asset');
			dialog_glasset=new makeDialog('finance.glmasref','#glasset',['glaccount','description'], 'Asset');

			dialog_gldepccode=new makeDialog('finance.costcenter','#gldepccode',['costcode','description'], 'Depreciation');
			dialog_gldep=new makeDialog('finance.glmasref','#gldep',['glaccount','description'], 'Depreciation');

			dialog_glprovccode=new makeDialog('finance.costcenter','#glprovccode',['costcode','description'], 'Provision for Depr');
			dialog_glprovdep=new makeDialog('finance.glmasref','#glprovdep',['glaccount','description'], 'Provision for Depr');

			dialog_glglossccode=new makeDialog('finance.costcenter','#glglossccode',['costcode','description'], 'Gain');
			dialog_glgainloss=new makeDialog('finance.glmasref','#glgainloss',['glaccount','description'], 'Gain');

			dialog_glrevccode=new makeDialog('finance.costcenter','#glrevccode',['costcode','description'], 'Loss');
			dialog_glrevaluation=new makeDialog('finance.glmasref','#glrevaluation',['glaccount','description'], 'Loss');



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
						} if(oper!='view'){
							
							dialog_assettype.handler(errorField);
							dialog_deptcode.handler(errorField);
							dialog_glassetccode.handler(errorField);
							dialog_glasset.handler(errorField);
							dialog_gldepccode.handler(errorField);
							dialog_gldep.handler(errorField);
							dialog_glprovccode.handler(errorField);
							dialog_glprovdep.handler(errorField);
							dialog_glglossccode.handler(errorField);
							dialog_glgainloss.handler(errorField);
							dialog_glrevccode.handler(errorField);
							dialog_glrevaluation.handler(errorField);

						} if(oper!='add'){
							toggleFormData('#jqGrid','#formdata');
							
							dialog_assettype.handler(errorField);
							dialog_deptcode.handler(errorField);
							dialog_glassetccode.handler(errorField);
							dialog_glasset.handler(errorField);
							dialog_gldepccode.handler(errorField);
							dialog_glprovccode.handler(errorField);
							dialog_glprovdep.handler(errorField);
							dialog_glglossccode.handler(errorField);
							dialog_glgainloss.handler(errorField);
							dialog_glrevccode.handler(errorField);
							dialog_glrevaluation.handler(errorField);
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
				table_name:'finance.facode',
				table_id:'assetcode',
				filterCol:null,
				filterVal:null,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.facode',
				table_id:'assetcode'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
					{ label: 'Category', name: 'assetcode', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true, checked: true},
					{ label: 'Description', name: 'description', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'Type', name: 'assettype', width: 80, sorttype: 'text', classes: 'wrap', canSearch: true, hidden:true},
					{ label: 'Rate (%p.a)', name: 'rate', width: 50 },
					{ label: 'Department', name: 'deptcode', width: 40, sorttype: 'text', classes: 'wrap'  },
					{ label: 'Tagging Next No.', name: 'tagnextno', width: 40, sorttype: 'text', classes: 'wrap',hidden:true  },
					{ label: 'Basis', name: 'method', width: 40, sorttype: 'text', classes: 'wrap', hidden:true  },
					{ label: 'Residual Value', name: 'residualvalue', width: 50, hidden:true },
					{ label: 'Asset Code', name: 'glassetccode', width: 50, hidden:true },
					{ label: 'Asset', name: 'glasset', width: 50, hidden:true },
					{ label: 'Depreciation Code', name: 'gldepccode', width: 50, hidden:true },
					{ label: 'Depreciation', name: 'gldep', width: 50, hidden:true },
					{ label: 'Provision for Depriciation Code', name: 'glprovccode', width: 50, hidden:true },
					{ label: 'Provision for Depr', name: 'glprovdep', width: 50, hidden:true },
					{ label: 'Gain Code', name: 'glglossccode', width: 50, hidden:true },
					{ label: 'Gain', name: 'glgainloss', width: 50, hidden:true },
					{ label: 'Loss Code', name: 'glrevccode', width: 50, hidden:true },
					{ label: 'Loss', name: 'glrevaluation', width: 50, hidden:true },
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

		/*	///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
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
			}     */
			///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////
		});