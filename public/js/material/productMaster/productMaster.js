
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();
			check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");

			hidePostClass();
			
			//console.log($( "#postGroupcode option:selected" ).text());
			//console.log($( "#postGroupcode option:selected" ).val());
			
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
						return {
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};

			var mycurrency =new currencymode(['#avgcost']);
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
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
							whenEdit();
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
						dialog_category.on();
					}
					if(oper!='add'){
						dialog_category.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					//$('.alert').detach();
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
				table_name:'material.productmaster',
				table_id:'itemcode',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'material.productmaster',
				table_id:'itemcode',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [						
					{ label: 'Item Code', name: 'itemcode', width: 40, classes: 'wrap', canSearch: true, checked:true},
					{ label: 'Description', name: 'description', width: 70, classes: 'wrap', canSearch: true},
					{ label: 'Group Code', name: 'groupcode', width: 30, classes: 'wrap'},
					{ label: 'Product Category', name: 'productcat', width: 30, classes: 'wrap'},
					{ label: 'Class ', name: 'Class', width: 30, classes: 'wrap'},
					{ label: 'Avg Cost ', name: 'avgcost', width: 30, classes: 'wrap'},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
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

					//$("#jqGridplus").hide();
					pg = $("#postGroupcode option:selected" ).val();
					pc = $("input[name=postClass]:checked").val();
					
					if (pg == 'Please Select First') {
						$("#jqGridplus").hide();
					}else if (pg == "Stock") {
						$("#jqGridplus").hide();
					}else {
						$("#jqGridplus").show();
					}

					if(pc == "Pharmacy") {
						$("#jqGridplus").show();
					}else if (pc == "Non-Pharmacy"){
						$("#jqGridplus").show();
					}
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
					return "Active";
				}
				if(cellvalue == 'Deactive') { 
					return "Deactive";
				}
			}	

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
				$("#jqGridplus").hide();
				postGroupcode  = $("#postGroupcode option:selected" ).val();
				if(postGroupcode == "Asset"){
					showPostClass();
					hideOtherClass();
					hideStockClass();
					showAssetClass();
					$("#postClassAsset").prop("checked", true);
					//$("#pg_jqGridPager table").show();
					$("#jqGridplus").show();
					urlParam.filterCol = ['groupcode','Class'];
					urlParam.filterVal = [postGroupcode, $("input[name=postClass]:checked").val()];
					refreshGrid('#jqGrid',urlParam);
				}else if(postGroupcode == "Others"){
					showPostClass()
					hideAssetClass();
					hideStockClass();
					showOtherClass();
					$("#postClassOther").prop("checked", true);
					//$("#pg_jqGridPager table").show();
					$("#jqGridplus").show();
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
		    	postClass = $("input[name=postClass]:checked").val();
		    	//$("#pg_jqGridPager table").show();
		    	$("#jqGridplus").show();
		    	urlParam.filterCol = ['groupcode','Class'];
				urlParam.filterVal = [postGroupcode, $("input[name=postClass]:checked").val()];
				refreshGrid('#jqGrid',urlParam);
		    	//alert($("input[name=postClass]:checked").val());
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
						emptyFormdata(errorField,'#formdata');
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
				caption:"",cursor: "pointer", id:"jqGridplus", position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					//alert(postGroupcode);
					//alert($('input[name=postClass]:checked').val());
					postClass = $('input[name=postClass]:checked').val();
					//$("#formdata [name=groupcode][value='"+postGroupcode+"']").prop('checked', true);
					$("#formdata :input[name='groupcode']").val(postGroupcode);
					
					//$("#formdata [name=Class][value='"+postClass+"']").prop('checked', true);
					$("#formdata :input[name='Class']").val(postClass);
				},
			});

			function whenEdit(){
				console.log($("input[name=groupcode]").val());
				// groupcode  = $("input[name=groupcode]").val();
				// 	if(groupcode == "Asset"){
				// 		// dialog_category.updateField('finance.facode','#productcat',['assetcode','description'], 'Category');
				// 		// dialog_category.offHandler();
				// 		// dialog_category.handler(errorField);
				// 	} else if(groupcode == "Others") {
				// 		// dialog_category.updateField('material.category','#productcat',['catcode','description'], 'Category');
				// 		// dialog_category.offHandler();
				// 		// dialog_category.handler(errorField);
				// 	} else if(groupcode == "Stock") {
				// 		dialog_category.urlParam.filterCol=['cattype', 'source', 'recstatus'];
				// 		dialog_category.urlParam.filterVal=['Stock', 'PO', 'A'];
				// 		dialog_category.off();
				// 		dialog_category.on();
				// 	}
			}

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			//toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno','ipaddress','computerid', 'adddate', 'adduser']);


			/////////////////////////////////////////////////////////ordialog//////////////////////////////

			var dialog_category = new ordialog(
				'productcat','material.category','#productcat',errorField,
				{	colModel:[
						{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Category",
					open: function(){
						if($('#postGroupcode').val().trim() == 'Stock') {
							dialog_category.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_category.urlParam.filterVal=['Stock', 'PO', 'A'];
						}else if($('#postGroupcode').val().trim() == 'Others') {
							dialog_category.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_category.urlParam.filterVal=['Other', 'PO', 'A'];
						}else if($('#groupcode').val().trim() == 'Stock') {
							dialog_category.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_category.urlParam.filterVal=['Stock', 'PO', 'A'];
						}else if($('#groupcode').val().trim() == 'Others') {
							dialog_category.urlParam.filterCol=['cattype', 'source', 'recstatus'];
							dialog_category.urlParam.filterVal=['Other', 'PO', 'A'];
						}else if($('#postGroupcode').val().trim() == ''){
							console.log("hh");
						}else {
							dialog_category.urlParam.filterCol=['recstatus'];
							dialog_category.urlParam.filterVal=['A'];
						}
					}
				},'urlParam'
			);
			dialog_category.makedialog();
});
		