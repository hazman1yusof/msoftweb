
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
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
			dialog_doctype=new makeDialog('hisdb.docstatus','#doctype',['statuscode','description'],'Doctor Type');
			dialog_department=new makeDialog('finance.costcenter','#department',['costcode','description'], 'Department');
			dialog_speciality=new makeDialog('hisdb.speciality','#specialitycode',['specialitycode','description'], 'Speciality Code');
			dialog_discipline=new makeDialog('hisdb.discipline','#disciplinecode',['code','description'], 'Discipline Code');
			dialog_creditor=new makeDialog('material.supplier','#creditorcode',['SuppCode','Name'], 'Creditor');

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
							rdonly("#formdata");
							$('#formdata :input[hideOne]').show();
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();
							$(this).dialog("option", "buttons",butt2);
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
						dialog_doctype.handler(errorField);
						dialog_department.handler(errorField);
						dialog_speciality.handler(errorField);
						dialog_discipline.handler(errorField);
						dialog_creditor.handler(errorField);
					}
					if(oper!='add'){
						dialog_doctype.check(errorField);
						dialog_department.check(errorField);
						dialog_speciality.check(errorField);
						dialog_discipline.check(errorField);
						dialog_creditor.check(errorField);
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
				field: '',
				table_name:'hisdb.doctor',
				table_id:'doctorcode',
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'doctor_save',
				field:'',
				oper:oper,
				table_name:'hisdb.doctor',
				table_id:'doctorcode',
				saveip:'true'
				//sysparam: {source: 'doc'} 
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'compcode', name: 'compcode', width: 90 , hidden: true, classes: 'wrap'},
					{label: 'Doctor Code', name: 'doctorcode', width: 90, canSearch:true, checked:true, classes: 'wrap'},
					{label: 'Doctor Name', name: 'doctorname', width: 90, canSearch:true , classes: 'wrap'},
					{label: 'Costcenter', name: 'department', width: 90 , hidden: true, classes: 'wrap'},
					{label: 'Discipline Code', name: 'disciplinecode', width: 90, classes: 'wrap'},
					{label: 'Speciality Code', name: 'specialitycode', width: 90, classes: 'wrap'},
					{label: 'Doctor Type', name: 'doctype', width: 90, classes: 'wrap'},
					{label: 'Creditor', name: 'creditorcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Resign Date', name: 'resigndate', width: 90 , classes: 'wrap',hidden: true},
					{label: 'idno', name: 'idno', width: 90, classes: 'wrap',hidden: true},
					{label: 'Class', name: 'classcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Admission Right', name: 'admright', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Appointment', name: 'appointment', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Company', name: 'company', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Address', name: 'address1', width: 90 , classes: 'wrap',hidden: true},
					{label: 'address2', name: 'address2', width: 90 , classes: 'wrap',hidden: true},
					{label: 'address3', name: 'address3', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Postcode', name: 'postcode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'State', name: 'statecode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Country', name: 'countrycode', width: 90 , classes: 'wrap',hidden: true},
					{label: 'GST No', name: 'gstno', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Home', name: 'res_tel', width: 90 , classes: 'wrap',hidden: true},
					{label: 'H/Phone', name: 'tel_hp', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Office', name: 'off_tel', width: 90 , classes: 'wrap',hidden: true},	
					{label: 'Operation Theatre (OT)', name: 'operationtheatre', width: 90 , classes: 'wrap',hidden: true},
					{label: 'Status', name: 'recstatus', width: 90, classes: 'wrap',formatter: formatterstatus, unformat: unformat,
					cellattr: function(rowid, cellvalue)
					{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''},},
					{label: 'Interval Time', name: 'intervaltime', width: 90 , classes: 'wrap',hidden: true},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
				

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

					if (cellvalue == 'Deactive'){
						return 'Deactive';
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'doctorcode':selRowId});
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
			addParamField('#jqGrid',false,saveParam,['depamt']);
		});
		