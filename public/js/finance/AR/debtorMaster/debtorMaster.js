
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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
							element : $('#'+errorField[0]),
							message : ' '
						}
					}
				},
			};
			//////////////////////////////////////////////////////////////

			////////////////////object for dialog handler//////////////////
			dialog_debtortype=new makeDialog('debtor.debtortype','#debtortype',['debtortycode','description','actdebccode', 'actdebglacc','depccode','depglacc'],'Financial Class');
			dialog_billtype=new makeDialog('hisdb.billtymst','#billtype',['billtype','description'], 'Bill Type IP');
			dialog_billtypeop=new makeDialog('hisdb.billtymst','#billtypeop',['billtype','description'], 'Bill Type OP');


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
					toggleFormData('#jqGrid','#formdata',oper);
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

						dialog_debtortype.offHandler();
						dialog_debtortype.handler(errorField);
						dialog_billtype.handler(errorField);
						dialog_billtypeop.handler(errorField);
					}
					if(oper!='add'){
						dialog_debtortype.offHandler();
						dialog_debtortype.check(errorField);
						dialog_billtype.check(errorField);
						dialog_billtypeop.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
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
				table_name:'debtor.debtormast',
				table_id:'debtorcode',
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'debtor.debtormast',
				table_id:'debtorcode',
				saveip:'true'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'Financial Class', name: 'debtortype',editable: true, width: 60}, 
					{label: 'Debtor Code', name: 'debtorcode', width: 100, checked:true, canSearch: true},
					{label: 'Debtor Name', name: 'name', width: 200, classes: 'wrap', canSearch: true},
					{label: 'Address', name: 'address1',hidden: true},
					{label: 'Address 2', name: 'address2', hidden: true},
					{label: 'Address 3', name: 'address3',hidden: true},
					{label: 'Address 4', name: 'address4',hidden: true},
					{label: 'PostCode', name: 'postcode',hidden: true},
					{label: 'State Code', name: 'statecode',  hidden: true},
					{label: 'Country', name: 'countrycode',hidden: true},
					{label: 'Contact', name: 'contact', hidden: true},
					{label: 'Position', name: 'position', hidden: true},
					{label: 'Tel.Office', name: 'teloffice', hidden: true},
					{label: 'Fax', name: 'fax',  hidden: true},
					{label: 'Email', name: 'email',  hidden: true},
					{label: 'Bill Type IP', name: 'billtype', hidden: true},
					{label: 'Bill Type OP', name: 'billtypeop', hidden: true},
					{label: 'Outamt', name: 'outamt', hidden: true},
					{label: 'Deposit Amount', name: 'depamt', width: 90},
					{label: 'Credit Limit', name: 'creditlimit', hidden: true},
					{label: 'Debtor CCode', name: 'actdebccode', width: 90},
					{label: 'Debtor Acct', name: 'actdebglacc', width: 90},
					{label: 'Deposit CCode', name: 'depccode', width: 50},
					{label: 'Deposit Acct', name: 'depglacc', width: 90},
					{label: 'Otherccode', name: 'otherccode', hidden: true},
					{label: 'Otheracct', name: 'otheracct', hidden: true},
					{label: 'Debtor Group', name: 'debtorgroup', hidden: true},
					{label: 'Credit Control Group', name: 'crgroup', hidden: true},
					{label: 'Bank Acc. No', name: 'accno', hidden: true},
					{label: 'Othertel', name: 'othertel', hidden: true},
					{label: 'Request GL', name: 'requestgl', hidden: true},
					{label: 'Credit Term', name: 'creditterm',  hidden: true},
					{label: 'Coverage IP', name: 'coverageip', hidden: true},
					{label: 'Coverage OP', name: 'coverageop', hidden: true},
					{label: 'idno', name: 'idno', hidden: true},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
					{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
					{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
					{label: 'Status', name: 'recstatus', formatter: formatterstatus, unformat: unformat, classes: 'wrap', width: 50, cellattr: function(rowid, cellvalue){
						return cellvalue == 'Deactive' ? ' class="alert alert-danger"' : ''
					}},
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'debtorcode':selRowId});
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
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['depamt','idno', 'computerid', 'ipaddress']);

			///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.offHandler=function(){
					$( this.id+" ~ a" ).off();
				}
				this.check=checkInput;
				this.updateField=function(table,id,cols,title){
					this.table=table;
					this.id=id;
					this.cols=cols;
					this.title=title;
					console.log(this);
				}
			}
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
				},
				close: function( event, ui ){
					paramD.searchCol=null;
					paramD.searchVal=null;
				},
			});

			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 40,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 70, canSearch:true, classes: 'pointer'},
					{ label: 'Debtor CCode', name: 'actdebccode', classes: 'pointer', hidden: true},
					{ label: 'Debtor Acct', name: 'actdebglacc', classes: 'pointer', hidden: true},
					{ label: 'Deposit CCode', name: 'depccode', classes: 'pointer', hidden: true},
					{ label: 'Deposit Acct', name: 'depglacc', classes: 'pointer', hidden: true},
				],
				width: 680,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					if(selText=='debtortype' ){
						$('#actdebccode').val(data['actdebccode']);
						$('#actdebglacc').val(data['actdebglacc']);
						$('#depccode').val(data['depccode']);
						$('#depglacc').val(data['depglacc']);
					}
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
				},
				
			});


			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							if(value['checked']){
								$( "#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+value['label']+"</input></label>" );
							}else{
								$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
							}
						}
					});
				});
				$(id).on("blur", function(){
					self.check(errorField);
				});
			}
			
			function checkInput(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						if($.inArray(id,errorField)!==-1){
							errorField.splice($.inArray(id,errorField), 1);
						}
						$( id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
						if($.inArray(id,errorField)===-1){
							errorField.push(id);
						}
					}
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				paramD.searchCol=null;
				paramD.searchVal=null;
				Dtext=Dtext.trim();
				if(Dtext != ''){
					var split = Dtext.split(" "),searchCol=[],searchVal=[];
					$.each(split, function( index, value ) {
						searchCol.push(Dcol);
						searchVal.push('%'+value+'%');
					});
					paramD.searchCol=searchCol;
					paramD.searchVal=searchVal;
				}
				refreshGrid("#gridDialog",paramD);
			}
			///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////
		});
		