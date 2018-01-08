
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
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


			////////////////////object for dialog handler//////////////////
			dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Delivery Store');

			//////to hide department dialog handler during edit/////////////

			function disableDelStore() {
				$("#1").addClass("hidden");
				$("#2").removeClass("hidden")
			}

			///////////////////////////////////////////////////////////////
		
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
					switch(oper) {
						case state = 'add':
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							rdonly("#formdata");
							hideOne("#formdata");
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							disableDelStore();
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#formdata");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							$('#formdata :input[hideOne]').show();
							break;
					}

					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
						dialog_deptcode.handler(errorField);
						
					}
					if(oper!='add'){
						dialog_deptcode.check(errorField);
					}
					
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
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
				table_name:'material.deldept',
				table_id:'deptcode',
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.deldept',
				table_id:'deptcode',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{ label: 'idno', name: 'idno', width: 30,hidden:true },
					{ label: 'compcode', name: 'compcode', width: 90, hidden: true},
					{label: 'Delivery Store', name: 'deptcode', width: 90, classes: 'wrap', canSearch: true, checked: true}, 
					{label: 'Description', name: 'description', width: 90, classes: 'wrap', canSearch: true},
					{label: 'Address', name: 'addr1', width: 90, classes: 'wrap', hidden: true},
					{label: 'Address 2', name: 'addr2', width: 90, classes: 'wrap', hidden: true},
					{label: 'Address 3', name: 'addr3', width: 90, classes: 'wrap', hidden: true},
					{label: 'Address 4', name: 'addr4', width: 90, classes: 'wrap', hidden: true},
					{label: 'Telephone No', name: 'tel', width: 90, classes: 'wrap'}, 
					{label: 'Fax No', name: 'fax', width: 90, classes: 'wrap'}, 
					{label: 'General Telephone', name: 'generaltel', width: 90, classes: 'wrap', hidden: true},
					{label: 'General Fax', name: 'generalfax', width: 90, classes: 'wrap', hidden: true},
					{label: 'Contact Person', name: 'contactper', width: 90, classes: 'wrap', hidden: true},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{label: 'deluser', name: 'deluser', width: 90, hidden: true},
					{label: 'deldate', name: 'deldate', width: 90, hidden: true},
					{label: 'Record Status', name: 'recstatus', width: 80, hidden: true, formatter: formatterstatus,
					unformat: unformat, cellattr: function(rowid, cellvalue){
						return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''},},
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
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
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
						return 'Active';
					}

					if (cellvalue == 'Active'){
						return 'Active';
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'deptcode':selRowId});
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
			
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno', 'adduser', 'adddate', 'computerid', 'ipaddress']);
		});
		