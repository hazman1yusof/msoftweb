
		
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

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

			/////////////////Object for Dialog Handler///////////////////
				//categorycode
				//dialog_assetcode=new makeDialog('finance.facode','#assetcode',['assetcode','description', 'assettype'],'Category');
				//assettype
				//dialog_assettype=new makeDialog('finance.fatype','#assettype',['assettype','description'], 'Type');
				//department
				//dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
				//location
				//dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
				//supplier
				//dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'],'Supplier');
                 var mycurrency =new currencymode(['#origcost','#purprice','#lstytddep','#cuytddep','#nbv']);
		////////////////////////////////////start dialog///////////////////////////////////////
			////////////////////////////////////start dialog///////////////////////////////////////
		var butt1=[{
				text: "Save",click: function() {
					mycurrency.formatOff();
					mycurrency.check0value(errorField);
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,{idno:selrowData("#jqGrid").idno});
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
					switch(oper) {
						case state = 'add':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							rdonly("#dialogForm");
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#dialogForm");

							break;
						case state = 'view':
							mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							getmethod_and_res(selrowData("#jqGrid").assetcode);
							getNVB();
							break;
					}
					if(oper!='view'){
						//dialog_dept.handler(errorField);
					}
					if(oper!='add'){
						toggleFormData('#jqGrid','#formdata');
						//dialog_dept.check(errorField);
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
				table_name:'finance.fatemp',
				table_id:'idno',
				sort_idno:true,
				//filterCol:['recstatus'],
				//filterVal:['A']
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				//table_name:'finance.fatemp',
				table_name:'finance.fatemp',
				table_id:'idno'
			};

			$("#jqGrid").jqGrid({
				datatype: "local",	
				 colModel: [
                    {label: 'Category', name: 'assetcode', width: 20, classes: 'wrap', canSearch: true,checked:true},
                    {label: 'Type', name: 'assettype', width: 20, classes: 'wrap',canSearch: true},
                   // {label: 'Asset No', name: 'assetno', width: 20, classes: 'wrap'},
                    { label: 'Item Code', name: 'itemcode', width: 20, classes: 'wrap', canSearch: true},
                     { label: 'Description', name: 'description', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Department', name: 'deptcode', width: 20, classes: 'wrap'},
                    { label: 'Location', name: 'loccode', width: 20, classes: 'wrap'},
                    { label: 'Invoice No', name: 'invno', width: 20, classes: 'wrap',hidden:true},
                    { label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', hidden:true},
                    { label: 'Quantity', name: 'qty', width: 20, classes: 'wrap'},
                    { label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
                    { label: 'Start Date', name:'statdate', width:20, classes:'wrap',  hidden:true},
					{ label: 'Post Date', name:'trandate', width:20, classes:'wrap',  hidden:true},
                   // { label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
                    { label: 'lstytddep', name:'lstytddep', width:20, classes:'wrap', hidden:true},
                    { label: 'cuytddep', name:'cuytddep', width:20, classes:'wrap', hidden:true},
                    { label: 'Cost', name: 'origcost', width: 20, classes: 'wrap', hidden:true},

                    { label: 'Current Cost', name: 'currentcost', width: 20, classes: 'wrap', hidden:true},
                    { label: 'SuppCode', name: 'suppcode', width: 20, classes: 'wrap'},
                    { label: 'Purchase Order No', name:'purordno',width: 20, classes:'wrap', hidden:true},
                    { label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', hidden:true},																	
					{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
                    { label: 'D/O No', name: 'delordno', width: 20, classes: 'wrap'},
                    { label: 'DO Date', name:'delorddate', width: 20, classes:'wrap', hidden:true},
                    { label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', 
						formatter:formatter, unformat:unformat, cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, },
					{ label: 'nprefid', name: 'nprefid', width: 90,hidden:true},
					{label: 'idno', name: 'idno', hidden: true},
					{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},
					{ label: 'Add User', name:'adduser', width:20, classes:'wrap',  hidden:true},
					{ label: 'Add Date', name:'adddate', width:20, classes:'wrap',  hidden:true},
                    ],
                autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
		        multiselect:true,
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

          function getmethod_and_res(assetcode){
				var param={
					action:'get_value_default',
					field:['method','residualvalue'],
					table_name:'finance.facode',
					table_id:'idno',
					filterCol:['assetcode'],
					filterVal:[assetcode],
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data)){
							$("#method").val(data.rows[0].method);
							$("#rvalue").val(data.rows[0].residualvalue);
						}
					});
			}

			function getNVB() {
				var origcost = $("#origcost").val();
				var lstytddep = $("#lstytddep").val();
				var cuytddep = $("#cuytddep").val();

				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(total.toFixed(2));
				$("#nbv").val(numeral(total).format('0,0.00'));
			}
	//});
function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");
			}


 			//////////////////////formatter//////////////////////////////////////////////////////////
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

		    $("#selectAll").click(function(){
               	$("#jqGrid").jqGrid('resetSelection');
                var ids = $("#jqGrid").jqGrid('getDataIDs');
                for (var i=0, il=ids.length; i < il; i++) {
                    $("#jqGrid").jqGrid('setSelection',ids[i], true);
                }
            });

            $("#clear").click(function(){
                $("#jqGrid").jqGrid('resetSelection');
            });

            $("#postedBut").click(function(){
                var ids = $("#jqGrid").jqGrid('getGridParam','selarrrow');
                if (ids.length>0) {
                    var arrayAll = [];
                    ids.forEach(function(element) {
                    	arrayAll.push($("#jqGrid").jqGrid('getRowData', element));
					});                 
                }

               	var param={
						action:'tagno_save',
						oper:'add',
						field:'',
						table_name:'finance.faregister',
						table_id:'idno',
						skipduplicate: true,
						returnVal:true,
					};

				$.post( "../../../../assets/php/entry.php?"+$.param(param),
					{seldata:arrayAll}, 
					function( data ) {
					}
				).fail(function(data) {
					alert('error');
				}).success(function(data){
					refreshGrid("#jqGrid",urlParam);
				});
            });




			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
				
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
			//	caption:"",cursor: "pointer",position: "first", 
				//buttonicon:"glyphicon glyphicon-trash",
				//title:"Delete Selected Row",
				//onClickButton: function(){
				//	oper='del';
					//selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					//if(!selRowId){
						//alert('Please select row');
						//return emptyFormdata(errorField,'#formdata');
					//}else{
						//saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'':selRowId});
					//}
				//},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				},

				
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
				//caption:"",cursor: "pointer",position: "first",  
				//buttonicon:"glyphicon glyphicon-edit",
				//title:"Edit Selected Row",  
				//onClickButton: function(){
					//oper='edit';
					//selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					//populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				//}, 
			//}).jqGrid('navButtonAdd',"#jqGridPager",{
			//	caption:"",cursor: "pointer",position: "first",  
				//buttonicon:"glyphicon glyphicon-plus", 
				//title:"Add New Row", 
				//onClickButton: function(){
					//oper='add';
					//$( "#dialogForm" ).dialog( "open" );
				//},

				
			});

  

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam,['cb']);
			addParamField('#jqGrid',false,saveParam,['idno']);




		});
		
