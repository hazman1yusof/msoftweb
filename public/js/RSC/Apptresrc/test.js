
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function(){ 
			{
		    $("#myTab li:eq(1) a").tab('show');
		};


		
			/////////////////////////validation//////////////////////////
		/*	$.validate({
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
			}; */
			//////////////////////////////////////////////////////////////
												
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
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'hisdb.apptresrc',
				table_id:'resourcecode'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [

				{ label: 'Compcode', name: 'compcode', hidden:true},	
					{ label: 'type', name: 'type', width: 90 , hidden: true},							
					{ label: 'Doctor Code', name: 'resourcecode', width: 10, checked:true, canSearch: true},						
					{ label: 'Desription', name: 'description', width: 10, canSearch: true},					
					{ label: 'Comment', name: 'comment', width: 10, classes: 'wrap'},
					{ label: 'Add Date', name: 'adddate', width: 80, classes: 'wrap' , hidden:true},
					{ label: 'Add User', name: 'adduser', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'Update Date', name: 'upddate', width: 90, classes: 'wrap' , hidden:true},
					{ label: 'record status', name: 'recstatus', width: 90, hidden:true, classes: 'wrap',cellattr: function(rowid, cellvalue){
						return cellvalue == 'D' ? ' class="alert alert-danger"' : ''},},
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
				
			});

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
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['depamt']);
			});
		