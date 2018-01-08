
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		$(document).ready(function () {
			$("body").show();
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
			dialog_glaccount=new makeDialog('finance.glmasref','#glaccount',['glaccno','description'],'GL Account');
			dialog_costcode=new makeDialog('finance.costcenter','#costcode',['costcode','description'], 'Cost Center');

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
					inputCtrl("#dialogForm","#formdata",oper,butt2)
					if(oper!='view'){
						dialog_glaccount.handler(errorField);
						dialog_costcode.handler(errorField);
					}
					if(oper!='add'){
						dialog_glaccount.check(errorField);
						dialog_costcode.check(errorField);
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
				table_name:'debtor.forexmaster',
				table_id:'forexcode'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'debtor.forexmaster',
				table_id:'forexcode',
				sort_idno:true,

			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'Forex Code', name: 'forexcode', width: 90, canSearch:true, checked:true},
					{label: 'Description', name: 'description', width: 90, canSearch:true },
					{label: 'Cost Code', name: 'costcode', width: 90 },
					{label: 'GL Account', name: 'glaccount', width: 90 },
					{label: 'Add User', name: 'adduser', width: 90 },
					{label: 'Add Date', name: 'adddate', width: 90 }
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 108,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				onSelectRow: function(rowid){
					urlParam2.filterVal=[selrowData("#jqGrid").forexcode];
					$('#formdata2 #forexcode').val(selrowData("#jqGrid").forexcode);
					refreshGrid("#jqGrid2",urlParam2);
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam);
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
			addParamField('#jqGrid',false,saveParam,['idno']);


			////////////////////////////////////// START FOREX  ////////////////////////////////////////////////////

			////////////////////////////////////start dialog///////////////////////////////////////
			var butt12=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid2","#dialogForm2","#formdata2",oper2,saveParam2,urlParam2);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];

			var oper2;
			$("#dialogForm2")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					inputCtrl("#dialogForm2","#formdata2",oper2,butt2)
					if(oper2!='view'){
					}
					if(oper2!='add'){
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata2',['#formdata2 #forexcode']);
					$('.alert').detach();
					$("#formdata2 a").off();
					if(oper2=='view'){
						$(this).dialog("option", "buttons",butt12);
					}
				},
				buttons :butt12,
			  });
			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam2={
				action:'get_table_default',
				field:'',
				table_name:'debtor.forex',
				table_id:'idno',
				filterCol:['forexcode'],
				filterVal:'',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam2={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'debtor.forex',
				table_id:'idno',
				skipduplicate:true,
			};
			
			$("#jqGrid2").jqGrid({
				datatype: "local",
				colModel: [
					{label: 'idno', name: 'idno', hidden:true},
					{label: 'Forex Code', name: 'forexcode', width: 90, canSearch:true, checked:true},
					{label: 'Rate', name: 'rate', width: 90, canSearch:true },
					{label: 'Effective Date', name: 'effdate', width: 90 },
					{label: 'Add User', name: 'adduser', width: 90 },
					{label: 'Add Date', name: 'adddate', width: 90 }
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 220,
				rowNum: 30,
				pager: "#jqGridPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#jqGrid2").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid2").jqGrid ('getGridParam', 'selrow')).focus();
				},
				
			});

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid2").jqGrid('navGrid','#jqGridPager2',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid2",urlParam2);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper2='del';
					selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#formdata2');
					}else{
						saveFormdata("#jqGrid2","#dialogForm2","#formdata2",'del',saveParam2,urlParam2);
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper2='view';
					selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid2","#dialogForm2","#formdata2",selRowId,'view');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper2='edit';
					selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid2","#dialogForm2","#formdata2",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper2='add';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row at top table');
						return emptyFormdata(errorField,'#formdata2');
					}else{
						$( "#dialogForm2" ).dialog( "open" );
					}
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid2','#searchForm2');
			searchClick('#jqGrid2','#searchForm2',urlParam2);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid2',false,urlParam2);
			addParamField('#jqGrid2',false,saveParam2,['idno']);
		});
		