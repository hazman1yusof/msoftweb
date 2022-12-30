
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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

			var fdl = new faster_detail_load();

			////////////////////object for dialog handler//////////////////

			var dialog_depccode = new ordialog(
				'depccode','finance.costcenter','#depccode',errorField,
				{	colModel:[
						{label:'Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#depglacc').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#depglacc').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Deposit Cost",
					open: function(){
						dialog_depccode.urlParam.filterCol=['compcode','recstatus'],
						dialog_depccode.urlParam.filterVal=['session.compcode','ACTIVE']
					}
				},'urlParam','radio','tab'
			);
			dialog_depccode.makedialog(true);


			var dialog_depglacc = new ordialog(
				'depglacc','finance.glmasref','#depglacc',errorField,
				{	colModel:[
						{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#updpayername').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#updpayername').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Deposit GL Account",
					open: function(){
						dialog_depglacc.urlParam.filterCol=['compcode','recstatus'],
						dialog_depglacc.urlParam.filterVal=['session.compcode','ACTIVE']
					}
				},'urlParam','radio','tab'
			);
			dialog_depglacc.makedialog(true);

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

			var oper = 'add';
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
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_depccode.on();
						dialog_depglacc.on();
						
					}
					if(oper!='add'){
						dialog_depccode.check(errorField);
						dialog_depglacc.check(errorField);
					
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('.my-alert').detach();
					dialog_depccode.off();
					dialog_depglacc.off();
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
				url:'util/get_table_default',
				field:'',
				table_name:'debtor.hdrtypmst',
				table_id:'hdrtype',
				filterCol:['compcode'],
				filterVal:['session.compcode'],
				sort_idno: true
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'depositType/form',
				field:'',
				oper:oper,
				table_name:'debtor.hdrtypmst',
				table_id:'hdrtype',
				saveip:'true',
				checkduplicate:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'idno', name: 'idno', width: 20,hidden:true  },
					{ label: 'Compcode', name: 'compcode', width: 40, hidden:true },
					{ label: 'Source', name: 'source', width: 50, hidden:true },
					{ label: 'Type', name: 'hdrtype', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Transaction Type', name: 'trantype', width:50, hidden:true},
					{ label: 'Description', name: 'description', width: 80, classes: 'wrap',checked:true, canSearch: true },
					{ label: 'Deposit Cost Code', name: 'depccode', width: 20,  classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail },
					{ label: 'Deposit GL Account', name: 'depglacc', width: 35, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail },
					{ label: 'Update Payer Name', name: 'updpayername', width: 30,  classes: 'wrap',formatter:formatter, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },
					{ label: 'Auto Allocation', name: 'updepisode', width: 25, classes: 'wrap' ,formatter:formatter, unformat:unformat, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },
					{ label: 'Manual Allocation', name: 'manualalloc', width: 25 ,formatter:formatter, unformat:unformat, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
					{ label: 'Record Status', name: 'recstatus', width: 35, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
					},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				sortname:'idno',
				sortorder:'desc',
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
					$("#searchForm input[name=Stext]").focus();
					fdl.set_array().reset();
				},
				
				
			});

			function formatter(cellvalue, options, rowObject){
				return parseInt(cellvalue) ? "Yes" : "No";
			}

			function unformat(cellvalue, options){
				//return parseInt(cellvalue) ? "Yes" : "No";

				if (cellvalue == 'Yes') {
					return "1";
				}
				else {
					return "0";
				}
			}
		
		//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
		function showdetail(cellvalue, options, rowObject){
			var field,table,case_;
			switch(options.colModel.name){
				case 'depccode':field=['costcode','description'];table="finance.costcenter";case_='depccode';break;
				case 'depglacc':field=['glaccno','description'];table="finance.glmasref";case_='depglacc';break;
			}
			var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

			fdl.get_array('assetregister',options,param,case_,cellvalue);
			// faster_detail_array.push(faster_detail_load('assetregister',options,param,case_,cellvalue));
			
			return cellvalue;
		}

		function unformat_showdetail(cellvalue, options, rowObject){
			return $(rowObject).attr('title');
		}

		/*////////////////////formatter status////////////////////////////////////////
				function formatterstatus(cellvalue, option, rowObject){
					if (cellvalue == 'A'){
						return 'Active';
					}

					if (cellvalue == 'D'){
						return 'Deactive';
					}

				}

			////////////////////unformatter status////////////////////////////////////////
				function unformatstatus(cellvalue, option, rowObject){
					if (cellvalue == 'Active'){
						return 'Active';
					}

					if (cellvalue == 'Deactive'){
						return 'Deactive';
					}

				}*/
				function formatterstatus_tick2(cellvalue, option, rowObject) {
					if (cellvalue == '1') {
						return `<span class="fa fa-check"></span>`;
					}else{
						return '';
					}
				}
			
			
				function unformatstatus_tick2(cellvalue, option, rowObject) {
					if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
						return '1';
					}else{
						return '0';
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view','');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
					recstatusDisable();
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$("#formdata :input[name='trantype']").val("RD");
					$("#formdata :input[name='source']").val("PB");
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	
		});
		