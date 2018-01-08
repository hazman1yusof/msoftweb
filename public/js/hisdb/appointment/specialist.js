
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
			});
			
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
			}
			
			var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						saveFormdata(oper);
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
			}}]
			
			var oper;
			$("#dialogForm")
			  .dialog({ 
				width: 7/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					emptyFormdata();
					dialogHandler('debtor.debtortype','debtortype',['debtortycode','description','actdebccode', 'actdebglacc','depccode','depglacc'],'Financial Class');
					dialogHandler('hisdb.billtymst','billtype',['billtype','description'], 'Bill Type IP');
					dialogHandler('hisdb.billtymst','billtypeop',['billtype','description'], 'Bill Type OP');
				},
				buttons :butt1,
			  });
			
			
			var urlParam={
				action:'get_table_default',
				field:'',
				except:['sysno','upduser','upddate','deluser','deldate'],
				table_name:'debtor.debtormast',
				table_id:'debtorcode'
			}
			
			$("#jqGrid").jqGrid({
				url: '../../../assets/php/entry.php?'+$.param(urlParam),
				datatype: "json",
				 colModel: [
					{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
					
					{label: 'Financial Class', name: 'debtortype', width: 90, classes: 'wrap',
					editable: true, editrules:{ required: true}}, 
					
					{label: 'Debtor Code', name: 'debtorcode', width: 100, classes: 'wrap', checked:true,
					canSearch: true,  
					editable: true, editrules:{ required: true}}, 
					
					{label: 'Debtor Name', name: 'name', width: 200, classes: 'wrap', canSearch: true,
					editable: true, editrules:{ required: true}}, 
					
					{label: 'Address', name: 'address1', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 2', name: 'address2', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 3', name: 'address3', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 4', name: 'address4', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'PostCode', name: 'postcode', width: 90 , hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'State Code', name: 'statecode', width: 90 ,  hidden: true},
					
					{label: 'Country', name: 'countrycode', width: 90 ,  hidden: true},
					
					{label: 'Contact', name: 'contact', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Position', name: 'position', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Tel.Office', name: 'teloffice', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Fax', name: 'fax', width: 90 ,  hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Email', name: 'email', width: 90 ,  hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Payable To', name: 'regfees', width: 90 ,  hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
                           
					{label: 'Bill Type IP', name: 'billtype', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Bill Type OP', name: 'billtypeop', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Record Status', name: 'recstatus', width: 90, hidden: true, editable:true,
					checked:true, editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Outamt', name: 'outamt', width: 90 , hidden: true},
					
					{label: 'Deposit Amount', name: 'depamt', width: 90, classes: 'wrap', editable: true ,
					editrules:{ required: true}}, 
					
					{label: 'Credit Limit', name: 'creditlimit', width: 90, hidden: true, editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Debtor CCode', name: 'actdebccode', width: 90, classes: 'wrap', editable: true,
					editrules:{ required: true}},  
					
					{label: 'Debtor Acct', name: 'actdebglacc', width: 90, classes: 'wrap', editable: true,
					editrules:{ required: true}},
					
					{label: 'Deposit CCode', name: 'depccode', width: 90, classes: 'wrap', editable: true,
					editrules:{ required: true}},
					
					{label: 'Deposit Acct', name: 'depglacc', width: 90, classes: 'wrap', editable: true,
					editrules:{ required: true}},
					
					{label: 'Otherccode', name: 'otherccode', width: 90, hidden: true},
					
					{label: 'Otheracct', name: 'otheracct', width: 90, hidden: true},
					
					{label: 'Lastupdate', name: 'lastupdate', width: 90, hidden: true, editable: true},
					
					{label: 'Lastuser', name: 'lastuser', width: 90, hidden: true, editable: true},
					
					{label: 'Debtor Group', name: 'debtorgroup', width: 90, hidden: true, editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Credit Control Group', name: 'crgroup', width: 90, hidden: true, editable: true,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Otheraddr1', name: 'otheraddr1', width: 90 , hidden: true},
					
					{label: 'Otheraddr2', name: 'otheraddr2', width: 90 , hidden: true},
					
					{label: 'Otheraddr3', name: 'otheraddr3', width: 90 , hidden: true},
					
					{label: 'Otheraddr4', name: 'otheraddr4', width: 90 , hidden: true},
					
					{label: 'Bank Acc. No', name: 'accno', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Othertel', name: 'othertel', width: 90 , hidden: true},
					
					{label: 'Request GL', name: 'requestgl', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
                            
					{label: 'Credit Term', name: 'creditterm', width: 90,  hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Adduser', name: 'adduser', width: 90 , hidden: true, editable: true},
					
					{label: 'Adddate', name: 'adddate', width: 90 , hidden: true, editable: true},
                          
					{label: 'Coverage IP', name: 'coverageip', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Coverage OP', name: 'coverageop', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}
				},
				
			});
			
			function refreshGrid(){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
			}
			
			$("#jqGrid").jqGrid('navGrid','#jqGridPager',
				{	
					view:false,edit:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refreshGrid();
					},
				},{},{},
				{	afterSubmit : function( data, postdata, oper){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];
					},
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText;
					}
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper='del';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata();
					}else{
						saveFormdata('del',{'debtorcode':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'view');
					checkInput('debtor.debtortype','debtortype',['debtortycode','description'],$('#debtortype').val());
					checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'edit');
					checkInput('debtor.debtortype','debtortype',['debtortycode','description'],$('#debtortype').val());
					checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
					enableForm('#dialogForm');
					$("#debtorcode").prop("readonly",true);
					
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
					$( "#dialogForm" ).dialog( "option", "title", "Add" );
					$("#dialogForm").dialog( "open" );
					enableForm('#dialogForm');
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});
					
			function disableForm(formName){
				$('texarea').prop("readonly",true);
				$(formName+' input').prop("readonly",true);
				$(formName+' input[type=radio]').prop("disabled",true);
			}
			
			function enableForm(formName){
				$('textarea').prop("readonly",true);
				$(formName+' input').prop("readonly",false);
				$(formName+' input[type=radio]').prop("disabled",false);
			}
					
			function populateFormdata(selRowId,state){
				if(!selRowId){
					alert('Please select row');
					return emptyFormdata();
				}
				switch(state) {
					case state = 'edit':
						$( "#dialogForm" ).dialog( "option", "title", "Edit" );
						$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
						break;
					case state = 'view':
						disableForm('#dialogForm');
						$( "#dialogForm" ).dialog( "option", "title", "View" );
						$( "#dialogForm" ).dialog( "option", "buttons", butt2 );
						break;
					default:
				}
				
				$("#dialogForm").dialog( "open" );
				rowData = $("#jqGrid").jqGrid ('getRowData', selRowId);
				$.each(rowData, function( index, value ) {
					var input=$("[name='"+index+"']");
					if(input.is("[type=radio]")){
						$("[name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
					}
				});
			}
			
			function emptyFormdata(){
				errorField.length=0;
				$('#formdata').trigger('reset');
				$('.help-block').html('');
			}
			
			function saveFormdata(oper,obj){
				if(obj==null){
					obj={null:'null'};
				}
				var param={
					action:'save_table_default',
					oper:oper,
					table_name:'debtor.debtormast',
					table_id:'debtorcode'
				};
				$.post( "../../../assets/php/entry.php?"+$.param(param), $( "#formdata" ).serialize()+'&'+$.param(obj) , function( data ) {
					
				}).fail(function(data) {
					errorText(data.responseText);
				}).success(function(data){
					$('#dialogForm').dialog('close');
					editedRow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					refreshGrid();
				});
			}
			
			function errorText(text){
				$( "#formdata" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			}
			
			var selText,Dtable,Dcols;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 100, classes: 'pointer', canSearch:true}, 
					{ label: 'Description', name: 'desc', width: 200, classes: 'pointer', canSearch:true},
					{ label: 'Debtor CCode', name: 'actdebccode', classes: 'pointer', hidden: true},
					{ label: 'Debtor Acct', name: 'actdebglacc', classes: 'pointer', hidden: true},
					{ label: 'Deposit CCode', name: 'depccode', classes: 'pointer', hidden: true},
					{ label: 'Deposit Acct', name: 'depglacc', classes: 'pointer', hidden: true},
		
				],
				width: 680,
				viewrecords: true,
				loadonce: true,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$( "#dialog" ).dialog( "close" );
					if(selText=='debtortype' ){
						$('#actdebccode').val(data['actdebccode']);
						$('#actdebglacc').val(data['actdebglacc']);
						$('#depccode').val(data['depccode']);
						$('#depglacc').val(data['depglacc']);
					}
					$('#'+selText).val(rowid);
					$('#'+selText).focus();
					$('#'+selText).parent().next().html(data['desc']);
				},
				
			});
			
			var delay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			
			populateSelect();
			function populateSelect(){
				$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['checked'])	{
						$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
						}
						else	{
							$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
						}
					}
				});
			}
			
			$('#Stext').keyup(function() {
				delay(function(){
					search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Scol').change(function(){
				search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
			});
			
			function search(Stext,Scol){
				$("#jqGrid").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(urlParam)+'&Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			}
			
			var paramD={action:'get_table_default',table_name:'',field:'',table_id:''};
			function dialogHandler(table,id,cols,title){
				$( "#"+id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					
					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
						if(value['canSearch']){
							$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
						}
					});
				});
				$("#"+id).on("blur", function(){
					checkInput(table,id,cols,$( "#"+id ).val());
				});
			}
			
			function checkInput(table,id,field,value){
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						var index = errorField.indexOf(id);
						if (index > -1) {
							errorField.splice(index, 1);
						}
						$( "#"+id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
					}else if(data.msg=='fail'){
						errorField.push(id);
						$( "#"+id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( "#"+id ).removeClass( "valid" ).addClass( "error" );
						$( "#"+id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
					}
				});
			}
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch($('#Dtext').val(),$('#dialog input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch($('#Dtext').val(),$('#dialog input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch(Dtext,Dcol){
				$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(paramD)+'&Scol='+Dcol+'&Stext='+Dtext}).trigger('reloadGrid');
			}
			
		});
		