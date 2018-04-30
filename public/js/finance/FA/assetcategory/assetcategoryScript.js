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
			// dialog_assettype=new makeDialog('finance.fatype','#assettype',['assettype','description'], 'Type');

			// dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description','costcode'], 'Department');

			// dialog_glassetccode=new makeDialog('finance.costcenter','#glassetccode',['costcode','description'], 'Asset');
			// dialog_glasset=new makeDialog('finance.glmasref','#glasset',['glaccno','description'], 'Asset');

			// dialog_gldepccode=new makeDialog('finance.costcenter','#gldepccode',['costcode','description'], 'Depreciation');
			// dialog_gldep=new makeDialog('finance.glmasref','#gldep',['glaccno','description'], 'Depreciation');

			// dialog_glprovccode=new makeDialog('finance.costcenter','#glprovccode',['costcode','description'], 'Provision for Depr');
			// dialog_glprovdep=new makeDialog('finance.glmasref','#glprovdep',['glaccno','description'], 'Provision for Depr');

			// dialog_glglossccode=new makeDialog('finance.costcenter','#glglossccode',['costcode','description'], 'Gain');
			// dialog_glgainloss=new makeDialog('finance.glmasref','#glgainloss',['glaccno','description'], 'Gain');

			// dialog_glrevccode=new makeDialog('finance.costcenter','#glrevccode',['costcode','description'], 'Loss');
			// dialog_glrevaluation=new makeDialog('finance.glmasref','#glrevaluation',['glaccno','description'], 'Loss');


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
					parent_close_disabled(true);
					toggleFormData('#jqGrid','#formdata');
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							rdonly("#formdata");
							hideOne("#formdata");
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
							dialog_assettype.on();

						} if(oper!='add'){
							// toggleFormData('#jqGrid','#formdata');
							dialog_assettype.check(errorField);
							
							
						}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('#formdata .alert').detach();
					dialog_assettype.off();
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
					{ label: 'Description', name: 'description', width: 40, sorttype: 'text',canSearch: true, classes: 'wrap'  },
					{ label: 'Type', name: 'assettype', width: 80, sorttype: 'text', classes: 'wrap', hidden:true},
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'assetcode':selRowId});
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
					$("#formdata :input[name='tagnextno']").val("1");
					$("#formdata :input[name='method']").val("Straight-Line");
					$("#formdata :input[name='residualvalue']").val("1");
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


			  var dialog_assettype = new ordialog(
				'assettype','finance.fatype','#assettype',errorField,
				{	colModel:[
						{label:'Asset Type',name:'assettype',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Asset Type",
					open: function(){
						dialog_assettype.urlParam.filterCol=['recstatus'],
						dialog_assettype.urlParam.filterVal=['A']
					}
				},'urlParam'
			);
			dialog_assettype.makedialog();

		});