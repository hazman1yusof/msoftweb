$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	var editedRow=0;

	$(document).ready(function () {
		$("body").show();
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

		var fdl = new faster_detail_load();

		////////////////////////////////////start dialog///////////////////////////////////////
		var butt1=[{
			text: "Save",click: function() {
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) 
				{
					if ($('#rate').val() <=0) ///check field value 'rate' before save
					{
						alert('Value of Rate (%p.a) cannot be less than or equal to 0');
						return 'rate';
					}
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
						recstatusDisable();
						break;
					case state = 'view':
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata');
						$(this).dialog("option", "buttons",butt2);
						break;

					} if(oper!='view'){
						dialog_assettype.on();
						dialog_deptcode.on();
						dialog_glassetcode.on();
						dialog_glasset.on();
						dialog_gldepccode.on();
						dialog_gldep.on();
						dialog_glprovccode.on();
						dialog_glprovdep.on();
						dialog_glglossccode.on();
						dialog_glgainloss.on();
						dialog_glrevccode.on();
						dialog_glrevaluation.on();

					} if(oper!='add'){
						// toggleFormData('#jqGrid','#formdata');
						dialog_assettype.check(errorField);
						dialog_deptcode.check(errorField);
						dialog_glassetcode.check(errorField);
						dialog_glasset.check(errorField);
						dialog_gldepccode.check(errorField);
						dialog_gldep.check(errorField);
						dialog_glprovccode.check(errorField);
						dialog_glprovdep.check(errorField);
						dialog_glglossccode.check(errorField);
						dialog_glgainloss.check(errorField);
						dialog_glrevccode.check(errorField);
						dialog_glrevaluation.check(errorField);
						
						
					}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				$('.my-alert').detach();
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
			url: 'util/get_table_default',
			field:'',
			table_name:'finance.facode',
			table_id:'assetcode',
			sort_idno: true,
			filterCol:['compcode'],
			filterVal:['session.compcode'],
		}

		/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'save_table_default',
			url:"./assetcategory/form",
			field:'',
			oper:oper,
			table_name:'finance.facode',
			table_id:'assetcode',
			checkduplicate:'true'
		};
		
		$("#jqGrid").jqGrid({
			datatype: "local",
				colModel: [
				{ label: 'idno', name: 'idno', width: 20, hidden:true },
				{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
				{ label: 'Category', name: 'assetcode', width: 10, sorttype: 'text', classes: 'wrap', canSearch: true},
				{ label: 'Description', name: 'description', width: 35, sorttype: 'text',canSearch: true, classes: 'wrap', checked:true },
				{ label: 'Type', name: 'assettype', width: 80, sorttype: 'text', classes: 'wrap', hidden:true},
				{ label: 'Rate (%p.a)', name: 'rate', width: 10, align: 'right'},
				{ label: 'Department', name: 'deptcode', width: 20, sorttype: 'text',classes: 'wrap',formatter: showdetail,unformat:un_showdetail},
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
				{
					label: 'Status', name: 'recstatus', width: 8, cellattr: function (rowid, cellvalue) 
					{
						return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"' : ''
					},
				},

				{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
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
			loadComplete: function(){
				if(oper == 'add'){
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				}
	
				$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				$("#searchForm input[name=Stext]").focus();
				fdl.set_array().reset();
			},	
			gridComplete: function(){
				if(editedRow!=0){
					$("#jqGrid").jqGrid('setSelection',editedRow,false);
				}
				
				fdl.set_array().reset();
			},			
		});

		function showdetail(cellvalue, options, rowObject){
			var field,table, case_;
			switch(options.colModel.name){
				case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			}
			var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
			fdl.get_array('assetcategoryScript',options,param,case_,cellvalue);
			
			if(cellvalue == null)cellvalue = " ";
			return cellvalue;
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
				let idno = selrowData('#jqGrid').idno;
				if(!idno){
					alert('Please select row');
					return emptyFormdata(errorField,'#formdata');
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':idno});
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
				recstatusDisable();
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
		addParamField('#jqGrid',false,saveParam, ['idno','adduser','adddate','upduser','upddate','recstatus' ]);


		var dialog_assettype = new ordialog(
			'assettype','finance.fatype','#assettype',errorField,
			{	colModel:[
					{label:'Asset Type',name:'assettype',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},						
				],
				urlParam: {
					filterCol:['recstatus'],
					filterVal:['ACTIVE']
				},
				ondblClickRow: function () {
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#deptcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
			},
			{
				title:"Select Asset Type",
				open: function(){
					dialog_assettype.urlParam.filterCol=['recstatus'],
					dialog_assettype.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_assettype.makedialog();
		
		var dialog_deptcode = new ordialog(
			'deptcode','sysdb.department','#deptcode',errorField,
			{	colModel:[
					{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Cost Code',name:'costcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#tagnextno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Department",
				open: function(){
					dialog_deptcode.urlParam.filterCol=['recstatus'],
					dialog_deptcode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_deptcode.makedialog();

		var dialog_glassetcode = new ordialog(
			'glassetcode','finance.costcenter','#glassetccode',errorField,
			{	colModel:[
					{label:'Costcode',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glasset').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Asset",
				open: function(){
					dialog_glassetcode.urlParam.filterCol=['recstatus'],
					dialog_glassetcode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glassetcode.makedialog();

		var dialog_glasset = new ordialog(
			'glasset','finance.glmasref','#glasset',errorField,
			{	colModel:[
					{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#gldepccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Asset",
				open: function(){
					dialog_glasset.urlParam.filterCol=['recstatus'],
					dialog_glasset.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glasset.makedialog();

		var dialog_gldepccode = new ordialog(
			'gldepccode','finance.costcenter','#gldepccode',errorField,
			{	colModel:[
					{label:'Costcode',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#gldep').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Depreciation Code",
				open: function(){
					dialog_gldepccode.urlParam.filterCol=['recstatus'],
					dialog_gldepccode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_gldepccode.makedialog();
		// dialog_gldepccode=new makeDialog('finance.costcenter','#gldepccode',['costcode','description'], 'Depreciation');

		var  dialog_gldep  = new ordialog(
			'gldep','finance.glmasref','#gldep',errorField,
			{	colModel:[
					{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glprovccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Depreciation Code",
				open: function(){
					dialog_gldep.urlParam.filterCol=['recstatus'],
					dialog_gldep.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_gldep.makedialog();

		// dialog_gldep=new makeDialog('finance.glmasref','#gldep',['glaccno','description'], 'Depreciation');

		var dialog_glprovccode  = new ordialog(
			'glprovccode','finance.costcenter','#glprovccode',errorField,
			{	colModel:[
					{label:'Costcode',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glprovdep').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Provision for Depr",
				open: function(){
					dialog_glprovccode.urlParam.filterCol=['recstatus'],
					dialog_glprovccode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glprovccode.makedialog();
		// dialog_glprovccode=new makeDialog('finance.costcenter','#glprovccode',['costcode','description'], 'Provision for Depr');

		var dialog_glprovdep  = new ordialog(
			'glprovdep','finance.glmasref','#glprovdep',errorField,
			{	colModel:[
					{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glglossccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Provision for Depr",
				open: function(){
					dialog_glprovdep.urlParam.filterCol=['recstatus'],
					dialog_glprovdep.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glprovdep.makedialog();
		// dialog_glprovdep=new makeDialog('finance.glmasref','#glprovdep',['glaccno','description'], 'Provision for Depr');
		
		var dialog_glglossccode = new ordialog(
			'glglossccode','finance.costcenter','#glglossccode',errorField,
			{	colModel:[
					{label:'Costcode',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glgainloss').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Gain ",
				open: function(){
					dialog_glglossccode.urlParam.filterCol=['recstatus'],
					dialog_glglossccode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glglossccode.makedialog();
		// dialog_glglossccode=new makeDialog('finance.costcenter','#glglossccode',['costcode','description'], 'Gain');

		var dialog_glgainloss  = new ordialog(
			'glgainloss','finance.glmasref', '#glgainloss',errorField,
			{	colModel:[
					{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glrevccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Gain ",
				open: function(){
					dialog_glgainloss.urlParam.filterCol=['recstatus'],
					dialog_glgainloss.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glgainloss.makedialog();
		// dialog_glgainloss=new makeDialog('finance.glmasref','#glgainloss',['glaccno','description'], 'Gain');

		var dialog_glrevccode = new ordialog(
			'glrevccode','finance.costcenter','#glrevccode',errorField,
			{	colModel:[
					{label:'Costcode',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glrevaluation').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Loss",
				open: function(){
					dialog_glrevccode.urlParam.filterCol=['recstatus'],
					dialog_glrevccode.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glrevccode.makedialog();
		// dialog_glrevccode=new makeDialog('finance.costcenter','#glrevccode',['costcode','description'], 'Loss');


		var dialog_glrevaluation = new ordialog(
			'glrevaluation','finance.glmasref','#glrevaluation',errorField,
			{	colModel:[
					{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Compcode',name:'compcode',width:400,hidden:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE']
					},
					ondblClickRow:function(){
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#glprovccode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
			},
			{
				title:"Select Loss",
				open: function(){
					dialog_glrevaluation.urlParam.filterCol=['recstatus'],
					dialog_glrevaluation.urlParam.filterVal=['ACTIVE']
				}
			},'urlParam'
		);
		dialog_glrevaluation.makedialog();
		// dialog_glrevaluation=new makeDialog('finance.glmasref','#glrevaluation',['glaccno','description'], 'Loss');

	});