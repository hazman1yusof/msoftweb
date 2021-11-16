
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");

			hidePostClass();
			/////////////////////////validation//////////////////////////
			$.validate({
				modules : 'sanitize',
				language : {
					requiredFields: '',
				},
			});
			
			var errorField=[];
			conf = {
				onValidate : function($form) {
					if(errorField.length>0){
						console.log(errorField);
						return {
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};

			var mycurrency =new currencymode(['#avgcost']);
			var fdl = new faster_detail_load();
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
					console.log(errorField);
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
					}else{
						mycurrency.formatOn();
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
					errorField.length=0;
					parent_close_disabled(true);
					switch(oper) {
						case state = 'add':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							hideOne('#formdata');
							$("#formdata :input[name='groupcode']").prop("readonly",true);
							$("#formdata :input[name='Class']").prop("readonly",true);
							rdonly("#formdata");
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#formdata");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
						showing_which_ordialog('nocheck');
					}
					if(oper!='add'){
						showing_which_ordialog('check');
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					$('.my-alert').detach();
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
				table_name:'material.productmaster',
				table_id:'itemcode',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'productMaster/form',
				field:'',
				oper:oper,
				table_name:'material.productmaster',
				table_id:'itemcode',
				saveip:'true',
				checkduplicate:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [						
					{ label: 'Item Code', name: 'itemcode', width: 40, classes: 'wrap', canSearch: true,},
					{ label: 'Description', name: 'description', width: 70, classes: 'wrap', checked:true,canSearch: true},
					{ label: 'Group Code', name: 'groupcode', width: 30, classes: 'wrap'},
					{ label: 'Product Category', name: 'productcat', width: 30, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
					{ label: 'Class ', name: 'Class', width: 30, classes: 'wrap'},
					{ label: 'Avg Cost ', name: 'avgcost', width: 30, classes: 'wrap',  align:'right' },
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
					},
					{ label: 'idno', name: 'idno', hidden: true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname: 'idno',
				sortorder: 'desc',
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
					fdl.set_array().reset();

					var pg = $("#postGroupcode option:selected" ).val();
					var pc = $("input[name=postClass]:checked").val();
					
					if (pg == 'Please Select First') {
						$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row'], td[title='View Selected Row']").hide();	
					}else if (pg == "Stock") {
						$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row'], td[title='View Selected Row']").hide();
					}else {
						$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row'], td[title='View Selected Row']").show();
					}

					if(pc == "Pharmacy") {
						$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row'], td[title='View Selected Row']").show();
					}else if (pc == "Non-Pharmacy"){
						$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row'], td[title='View Selected Row']").show();
					}
				},
				
			});	

			////////////////////////function hide radio button ////////////////////////////////////////////////
			function hidePostClass() {
				$("label[for=postClass]").hide();
				$(":radio[name='postClass']").parent('label').hide();
			}
			function hideAssetClass(){
				$(" :input[id='postClassAsset']").hide();
				$(" :radio[id='postClassAsset']").parent('label').hide();
			}
			function hideOtherClass(){
				$(":input[id='postClassOther']").hide();
				$(":radio[id='postClassOther']").parent('label').hide();
			}

			function hideStockClass(){
				$(":input[id='postClassPharmacy']").hide();
				$(":radio[id='postClassPharmacy']").parent('label').hide();
				$(":input[id='postClassNon-Pharmacy']").hide();
				$(":radio[id='postClassNon-Pharmacy']").parent('label').hide();
			}


			////////////////////////function show radio button ////////////////////////////////////////////////
			function showPostClass() {
				$("label[for=postClass]").show();
				$(":radio[name='postClass']").parent('label').show();
			}

			function showAssetClass(){
				$(":input[id='postClassAsset']").show();
				$(":radio[id='postClassAsset']").parent('label').show();
			}

			function showOtherClass(){
				$(":input[id='postClassOther']").show();
				$(":radio[id='postClassOther']").parent('label').show();
			}

			function showStockClass(){
				$(":input[id='postClassPharmacy']").show();
				$(":radio[id='postClassPharmacy']").parent('label').show();
				$(":input[id='postClassNon-Pharmacy']").show();
				$(":radio[id='postClassNon-Pharmacy']").parent('label').show();
			}

			///////////////////////// on change //////////////////////////////////////////////////////////////

			$('#postGroupcode').on('change', function() {
				//$("#pg_jqGridPager table").hide();
				$("#jqGridplus, td[title='Edit Selected Row'], td[title='Delete Selected Row']").hide();
				var postGroupcode  = $("#postGroupcode option:selected" ).val();
				if(postGroupcode == "Asset"){
					showPostClass();
					hideOtherClass();
					hideStockClass();
					showAssetClass();
					$("#postClassAsset").prop("checked", true);

					urlParam.filterCol = ['groupcode','Class'];
					urlParam.filterVal = [postGroupcode, $("input[name=postClass]:checked").val()];
					refreshGrid('#jqGrid',urlParam);
				}else if(postGroupcode == "Others"){
					showPostClass()
					hideAssetClass();
					hideStockClass();
					showOtherClass();
					$("#postClassOther").prop("checked", true);

					urlParam.filterCol = ['groupcode','Class'];
					urlParam.filterVal = [postGroupcode, $("input[name=postClass]:checked").val()];
					refreshGrid('#jqGrid',urlParam);
				}else if(postGroupcode == "Stock"){
					showPostClass();
					$(":radio[name='postClass']").prop("checked", false);
					hideAssetClass();
					hideOtherClass();
					showStockClass();
					urlParam.filterCol = ['groupcode'];
					urlParam.filterVal = [postGroupcode];
					refreshGrid('#jqGrid',urlParam);
				}
			})

		    $("input[name=postClass]:radio").on('change click', function(){

		    	urlParam.filterCol = ['groupcode','Class'];
				urlParam.filterVal = [$("#postGroupcode option:selected" ).val(), $("input[name=postClass]:checked").val()];
				refreshGrid('#jqGrid',urlParam);

			});

			function showdetail(cellvalue, options, rowObject){
				var field,table, case_;
				var pg = $("#postGroupcode option:selected" ).val();
				var pc = $("input[name=postClass]:checked").val();
				switch(options.colModel.name){
					case 'productcat':
							if (pg == "Asset") {
								field=['assetcode','description'];
								table="finance.facode";
								case_='productcat';
							}else{
								field=['catcode','description'];
								table="material.category";
								case_='productcat';
							}
							break;
				}
				var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
			
				fdl.get_array('productMaster',options,param,case_,cellvalue);
				
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
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						emptyFormdata(errorField,'#formdata');
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
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
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
				caption:"",cursor: "pointer", id:"jqGridplus", position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$("#formdata :input[name='groupcode']").val($('#postGroupcode').val());
					$("#formdata :input[name='Class']").val($('input[name=postClass]:checked').val());
				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			//toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','adduser', 'adddate', 'upddate', 'upduser', 'computerid', 'ipaddress','recstatus']);


			/////////////////////////////////////////////////////////ordialog//////////////////////////////

			var dialog_category_asset = new ordialog(
				'productcat_asset','finance.facode','#productcat_asset',errorField,
				{	colModel:[
						{label:'Asset Code',name:'assetcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['recstatus'],
						filterVal:['ACTIVE'],
					},
					ondblClickRow: function () {
						$('#recstatus').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Category",
					open: function(){
						// dialog_category_asset.urlParam.table_name="material.category";
						// dialog_category_asset.urlParam.field=['assetcode', 'description'];
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_category_asset.makedialog();

			var dialog_category_other = new ordialog(
				'productcat_other','material.category','#productcat_other',errorField,
				{	colModel:[
						{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['recstatus','cattype', 'source', 'Class'],
						filterVal:['ACTIVE','OTHER', 'PO', 'others'],
					},
					ondblClickRow: function () {
						$('#recstatus').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Category",
					open: function(){
						// dialog_category_other.urlParam.table_name="material.category";
						// dialog_category_other.urlParam.field=['catcode', 'description'];
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_category_other.makedialog();

			var dialog_category_ph = new ordialog(
				'productcat_ph','material.category','#productcat_ph',errorField,
				{	colModel:[
						{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['recstatus','cattype', 'source', 'Class'],
						filterVal:['ACTIVE','Stock', 'PO', 'Pharmacy'],
					},
					ondblClickRow: function () {
						$('#recstatus').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Category",
					open: function(){
						// dialog_category_other.urlParam.table_name="material.category";
						// dialog_category_other.urlParam.field=['catcode', 'description'];
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_category_ph.makedialog();

			var dialog_category_nonph = new ordialog(
				'productcat_nonph','material.category','#productcat_nonph',errorField,
				{	colModel:[
						{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
					],
					urlParam: {
						filterCol:['recstatus','cattype', 'source', 'Class'],
						filterVal:['ACTIVE','Stock', 'PO', 'NON-PHARMACY'],
					},
					ondblClickRow: function () {
						$('#recstatus').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Category",
					open: function(){
						// dialog_category_other.urlParam.table_name="material.category";
						// dialog_category_other.urlParam.field=['catcode', 'description'];
					}
				},'urlParam', 'radio', 'tab'
			);
			dialog_category_nonph.makedialog();

			function showing_which_ordialog(check){
				var pg = $("#postGroupcode option:selected" ).val();
				var pc = $("input[name=postClass]:checked").val();
				dialog_category_ph.off();
				dialog_category_nonph.off();
				dialog_category_other.off();
				dialog_category_asset.off();

				$("#productcat_asset_div,#productcat_other_div,#productcat_ph_div,#productcat_nonph_div").hide();	

				if (pg == 'Please Select First') {
					$("#productcat_asset_div,#productcat_other_div,#productcat_ph_div,#productcat_nonph_div").hide();	
				}else if (pg == "Stock") {
					if(pc == "Pharmacy") {
						$("#productcat_ph_div").show();	
						dialog_category_ph.on();
						if(check == 'check'){
							$(dialog_category_ph.textfield).val(selrowData('#jqGrid').productcat);
							dialog_category_ph.check(errorField)
						}
					}else if (pc == "Non-Pharmacy"){
						$("#productcat_nonph_div").show();
						dialog_category_nonph.on();
						if(check == 'check'){
							$(dialog_category_nonph.textfield).val(selrowData('#jqGrid').productcat);
							dialog_category_nonph.check(errorField)
						}
					}
				}else if (pg == "Others") {
					$("#productcat_other_div").show();
						dialog_category_other.on();
						if(check == 'check'){
							$(dialog_category_other.textfield).val(selrowData('#jqGrid').productcat);
							dialog_category_other.check(errorField)
						}
				}else if (pg == "Asset") {
					$("#productcat_asset_div").show();
						dialog_category_asset.on();
						if(check == 'check'){
							$(dialog_category_asset.textfield).val(selrowData('#jqGrid').productcat);
							dialog_category_asset.check(errorField)
						}
				}

				
			}
});
		