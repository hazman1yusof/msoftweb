		
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

		/////////////////Object for Dialog Handler////////////////////////////////////////////////////

				//department
				dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
				//location
				dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
		
		////////////////////////////////////start dialog///////////////////////////////////////
		
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam2,urlParam,null,{idno:selrowData('#jqGrid').idno});

						saveOnly(saveParam2,
						{
							'deptcode' : $("#deptcode").val(),
							'olddeptcode' : $("#currdeptcode").val(),
							'trantype' : 'TRF',
							'trandate' : $("#trandate").val(),
							'curloccode' : $("#loccode").val(),
							'oldloccode' : $("#currloccode").val(),
							'assetno' : $("#assetno").val()

						});
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
							rdonly("#dialogForm");
							hideOne('#formdata');
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							$("#loccode").val('');
							$("#deptcode").val('');
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						dialog_deptcode.handler(errorField);
						dialog_loccode.handler(errorField);
					}
					if(oper!='add'){
						//dialog_deptcode.check(errorField);
						//dialog_loccode.check(errorField);
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
				table_name:'finance.faregister',
				table_id:'idno',
				sort_idno:true,
				filterCol:['recstatus'],
				filterVal:['A']
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:['deptcode','loccode','trandate'],
				oper:oper,
				table_name:'finance.faregister',
				table_id:'idno'
			};

			var saveParam2={
				action:'save_table_default',
				field:['auditno','deptcode','olddeptcode','trantype','trandate','curloccode','oldloccode','assetno'],
				oper:'add',
				table_name:'finance.fatran',
				table_id:'idno',
				sysparam: {source: 'FA', trantype: 'TRF', useOn: 'auditno'},

			};

			$("#jqGrid").jqGrid({
				datatype: "local",	
				 colModel: [
				 	{label: 'Tagging No', name: 'assetno', width: 10, canSearch: true, checked: true},
                    {label: 'Item Code', name:'itemcode', width: 20, },
                    {label: 'Category', name: 'assetcode', width: 20, classes: 'wrap', canSearch: true,checked:true},
                    {label: 'Type', name:'assettype', width: 20, classes: 'wrap', canSearch: true, checked:true},
                    {label: 'Department', name:'deptcode', width: 20, },
                    {label: 'Location', name:'loccode', width: 20, classes: 'wrap',},
                    {label: 'Description', name:'description', width: 40, classes: 'wrap'},
                    {label: 'idno', name: 'idno', hidden: true},
                    {label: 'Transfer Date', name:'trandate', formatter:dateFormatter, hidden:true},
                    ],
                autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
		        multiselect:false,
				pager: "#jqGridPager",
				onSelectRow: function(){
					$('#currdeptcode').val(selrowData('#jqGrid').deptcode);
					$('#currloccode').val(selrowData('#jqGrid').loccode);
					$('#assetno').val(selrowData('#jqGrid').assetno);
					$('#description').val(selrowData('#jqGrid').description);
					
				},
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

			////////////////////////////////////////////////////////////////////////////////////////


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

			////////////////////////////// DATE FORMATTER ////////////////////////////////////////

			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");
			}

			////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////
            $("#msgBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 3/10 * $(window).width()
            	buttons: [{
					text: "OK",click: function() {
						$(this).dialog('close');
						oper='edit';
	    				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
	    				$('#trandateNew').attr('max', moment().format('YYYY-M-D'));
						populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					}
				},{
					text: "Cancel",click: function() {
						$(this).dialog('close');
					}
				}]
            });

            $("#transferButn").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		alert('Please select row');
            	}else{
	            	$("span[name='itemcode']").text(selrowData('#jqGrid').itemcode);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					$("#msgBox").dialog("open");
            	}
            });
            /////////////////////////////////////////////////////////////////////////////////////////////////
    //        
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
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			

			function saveOnly(saveParam,obj){
				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $.param(obj));
			}

		});
		
