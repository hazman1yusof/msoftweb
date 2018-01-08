
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
							element : $(errorField[0]),
							message : ' '
						}
					}
				},
			};
			
			////////////////////////////////////start dialog///////////////////////////////////////
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
						//saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,'#searchForm',$(tabform).serializeArray());
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
						//dialog_dept.handler(errorField);
					}
					if(oper!='add'){
						toggleFormData('#jqGrid','#formdata');
						//dialog_dept.check(errorField);
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
				table_name:'finance.apacthdr',
				table_id:'auditno',
				filterCol: ['recstatus'],
				filterVal: ['P'],
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.glmasref',
				table_id:'glaccount'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
					
					//{ label: 'Audit No', name: 'auditno', width: 25, classes: 'wrap',  },
					{ label: 'System Auto No', name: 'auditno', width: 25, classes: 'wrap',
						formatter: function (cellValue, options, rowObject) {
									return rowObject[9] + ' - ' + rowObject[10] + ' - ' + cellValue;
                        	}
					},
					{ label: 'Pv No', name: 'pvno', width: 40, canSearch: true, checked: true},
					{ label: 'Date', name: 'actdate', width: 25, classes: 'wrap', 
						//formatter : 'date', formatoptions : {newformat : 'd/m/Y'}
					},
					{ label: 'Bank Code', name: 'bankcode', width: 40, classes: 'wrap', },
					{ label: 'Cheq No', name: 'cheqno', width: 40, classes: 'wrap', canSearch: true,},
					{ label: 'Pay To', name: 'payto', width: 35, classes: 'wrap', canSearch: true},
					
					{ label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency'} ,//unformat:unformat2}
					{ label: 'Remarks', name: 'remarks', width: 40, classes: 'wrap',hidden:'true'},
					//{ label: 'Entered By', name: 'adduser', width: 35, classes: 'wrap',hidden:'true'},
					//{ label: 'Entered Date', name: 'adddate', width: 40, classes: 'wrap',hidden:'true'},
					//{ label: 'Payment Mode', name: 'paymode', width: 25, classes: 'wrap'},
					{ label: 'Cheq Date', name: 'cheqdate', width: 40, hidden:'true'},
					{ label: 'source', name: 'source', width: 40,hidden:'true'},
				 	{ label: 'trantype', name: 'trantype', width: 40,hidden:'true'},
					//{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap',formatter:formatter},
					
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
				onSelectRow:function(rowid, selected){
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					auditno=rowid;
					trantype=jg.trantype;

					
					if(trantype=="DP"){
						alert("dp");
						//$("#dialogFormDP").dialog( "open" );
					}
					if(trantype=="FT"){
						alert("FT");
						//$("#dialogFormFT").dialog( "open" );
					}
				},
				
			});

			/////////////////////////////////formater ////////////////////////////////////////////////////////

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
						saveFormdata("#jqGrid","#dialogForm","#formdata", oper,saveParam,urlParam);
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
			addParamField('#jqGrid',false,saveParam);
		});
		