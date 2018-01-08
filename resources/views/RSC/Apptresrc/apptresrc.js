
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
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////
									
			////////////////////////////////////start dialog///////////////////////////////////////
			$('.nav-tabs a').on('shown.bs.tab', function(){
				$($(this).attr('grid')).jqGrid ('setGridWidth', $($(this).attr('grid')+"_c")[0].offsetWidth);			
			})			

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
					}
					if(oper!='view'){
					}
					if(oper!='add'){
					}
				},
				close: function( event, ui ) {
					emptyFormdata('#formdata');
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
				table_name:['hisdb.apptresrc'],
				table_id:'resourcecode',
				filterCol:['type'],
				filterVal:['doc'],
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [

				{ label: 'Compcode', name: 'compcode', hidden:true},	
					{ label: 'type', name: 'type', width: 90 , hidden: true},							
					{ label: 'Doctor Code', name: 'resourcecode', width: 20, checked:true, canSearch: true},						
					{ label: 'Description', name: 'description', width: 80, canSearch: true, classes: 'wrap'},					
					{ label: 'Comment', name: 'comment', width: 10, classes: 'wrap', hidden: true},
					{ label: 'Add Date', name: 'adddate', width: 80, classes: 'wrap' , hidden:true},
					{ label: 'Add User', name: 'adduser', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'Update Date', name: 'upddate', width: 90, classes: 'wrap' , hidden:true},										
					{ label: 'Interval Time (minute)', name: 'intervalTime', width: 20},
					{ label: 'Record Status', name: 'recstatus', width: 30, classes: 'wrap',formatter: formatterstatus, unformat: unformat,
					cellattr: function(rowid, cellvalue){
						return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''},},
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
					$("#jqGridPager td[title='View Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});  

////////////////////formatter status////////////////////////////////////////
				function formatterstatus(cellvalue, option, rowObject){
					if (cellvalue == 'A'){
						return 'Active';
					}

					if (cellvalue == 'D'){
						return 'Deactive';
					}

				}

			////////////////////unformatter status////////////////////////////////////////
				function unformat(cellvalue, option, rowObject){
					if (cellvalue == 'Active'){
						return 'A';
					}

					if (cellvalue == 'Deactive'){
						return 'D';
					}

				}

			addParamField('#jqGrid',true,urlParam); 
			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
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
	
			});  

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////


			///////////////////////////////////resource/////////////////////////////////////////
				var urlParam2={
				action:'get_table_default',
				field:'',
				table_name:['hisdb.apptresrc'],
				table_id:'resourcecode',
				filterCol:['type'],
				filterVal:['rsc'],
			}
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'hisdb.apptresrc',
				table_id:'resourcecode'
			};

			$("#g_resource").jqGrid({
				datatype: "local",
				 colModel: [
				 { label: 'type', name: 'type', width: 90 , hidden: true},
					{label: 'Resource Code', name: 'resourcecode', width: 10, checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 10, canSearch: true},
					{ label: 'Comment', name: 'comment', width: 10, classes: 'wrap', hidden: true},											
					{ label: 'Interval Time (minute)', name: 'intervalTime', width: 2, classes: 'wrap'},
				],
		                multiSort: true,
						viewrecords: true,
						loadonce:false,
						width: 900,
						height: 350,
						rowNum: 30,
						pager: "#jqGridPager2",
					ondblClickRow: function(rowid, iRow, iCol, e){
							$("#g_resource td[title='View Selected Row']").click();
						},
						gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
			});

			$("#g_resource").jqGrid({stringResult: true,searchOnEnter : false});
			addParamField('#g_resource',true,urlParam2); 
			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#g_resource").jqGrid('navGrid','#jqGridPager2',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#g_resource",urlParam2);
				},
	
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#g_resource").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#g_resource","#dialogForm","#formdata",selRowId,'view');
				},

			
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper='del';
					selRowId = $("#g_resource").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('#formdata');
					}else{
						saveFormdata("#g_resource","#dialogForm","#formdata",'del',saveParam,urlParam2,{'resourcecode':selRowId});
					}
				},
			
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#g_resource").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#g_resource","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
	
			});  

			///////////////////////////////////////////////////////////////ot////////////////////////////////// 

			var urlParam3={
				action:'get_table_default',
				field:'',
				table_name:['hisdb.apptresrc'],
				table_id:'resourcecode',
				filterCol:['type'],
				filterVal:['ot'],
			}
			var saveParam3={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'hisdb.apptresrc',
				table_id:'resourcecode'
			};

			$("#g_ot").jqGrid({
				datatype: "local",
				 colModel: [
				 { label: 'type', name: 'type', width: 90 , hidden: true},
					{label: 'Operation Theater Code', name: 'resourcecode', width: 10, checked:true, canSearch: true},
					{label: 'Description', name: 'description', width: 10, canSearch: true},
					{ label: 'Comment', name: 'comment', width: 10, classes: 'wrap',hidden: true},							
					{ label: 'Interval Time (minute)', name: 'intervalTime', width: 2, classes: 'wrap'},
				],
		        multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager3",
				ondblClickRow: function(rowid, iRow, iCol, e){
							$("#g_ot td[title='View Selected Row']").click();
						},
						gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
			});
			

			$("#g_ot").jqGrid({stringResult: false,searchOnEnter : false});
			addParamField('#g_ot',true,urlParam3);

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#g_ot").jqGrid('navGrid','#jqGridPager3',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#g_ot",urlParam3);
				},
	
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#g_ot").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#g_ot","#dialogForm","#formdata",selRowId,'view');
				},

				}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					oper='del';
					selRowId = $("#g_ot").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata('#formdata');
					}else{
						saveFormdata("#g_ot","#dialogForm","#formdata",'del',saveParam3,urlParam3,{'resourcecode':selRowId});
					}
				},
			
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#g_ot").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#g_ot","#dialogForm","#formdata",selRowId,'edit');
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
	
			});
				//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['depamt']); 

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut2','#searchForm2','on');
			populateSelect('#g_resource','#searchForm2');
			searchClick('#g_resource','#searchForm2',urlParam2);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#g_resource',true,urlParam2);
			addParamField('#g_resource',false,saveParam,['depamt']); 

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			toogleSearch('#sbut3','#searchForm3','on');
			populateSelect('#g_ot','#searchForm3');
			searchClick('#g_ot','#searchForm3',urlParam3);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#g_ot',true,urlParam3);
			addParamField('#g_ot',false,saveParam,['depamt']); 

});
		