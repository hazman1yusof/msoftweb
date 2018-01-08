
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			
			// $( "#dialog" ).dialog({
			// 	autoOpen: false,
			// 	width: 7/10 * $(window).width(),
			// 	modal: true,
			// });
			
			// $.validate({
			// 	language : {
			// 		requiredFields: ''
			// 	},
			// });
			// var errorField=[];
			// conf = {
			// 	onValidate : function($form) {
			// 		if(errorField.length>0){
			// 			return {
			// 				element : $('#'+errorField[0]),
			// 				message : ' '
			// 			}
			// 		}
			// 	},
			// }
			

			// function disableForm(formName){
			// 	$('textarea').prop("readonly",true);
			// 	$(formName+' input').prop("readonly",true);
			// 	$(formName+' input[type=radio]').prop("disabled",true);
			// }
			
			// function enableForm(formName, grid_id){
			// 	$('textarea').prop("readonly",true);
			// 	$(formName+' input').prop("readonly",false);
			// 	$(formName+' input[type=radio]').prop("disabled",false);
			// }

			// Session time dialog properties

			

			var btn1=[{
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
			
			var btn2=[{
				text: "Close",click: function() {
					$(this).dialog('close');
			}}]
			
			var oper;
			$("#session_dialog")
			  .dialog({ 
				width: 7/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					// emptyFormdata();
					// dialogHandler('debtor.debtortype','debtortype',['debtortycode','description','actdebccode', 'actdebglacc','depccode','depglacc'],'Financial Class');
					// dialogHandler('hisdb.billtymst','billtype',['billtype','description'], 'Bill Type IP');
					// dialogHandler('hisdb.billtymst','billtypeop',['billtype','description'], 'Bill Type OP');
				},
				buttons :btn1,
			  });
			

			// doctor list grid
			
			var doc_grid_param={
				action:'get_table_default',
				field:'',
				except:['sysno','upduser','upddate','deluser','deldate'],
				table_name:'hisdb.personal',
				table_id:'staffno'
			}
			
			$("#doc_grid").jqGrid({
				url: '../../../../assets/php/entry.php?'+$.param(doc_grid_param),
				datatype: "json",
				 colModel: [
					{label: 'compcode', name: 'compcode', width: 90 , hidden: true},

					{label: 'Staff No', name: 'staffno', width: 30 },					
					
					{label: 'Name', name: 'name', width: 120, classes: 'wrap', checked:true,
					canSearch: true,
					editable: true, editrules:{ required: true}},

					{label: 'Initial', name: 'initname', width: 20, classes: 'wrap', checked:true,
					canSearch: true,  
					editable: true, editrules:{ required: true}}, 				
					
					{label: 'Address', name: 'address1', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 2', name: 'address2', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 3', name: 'address3', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Address 4', name: 'address4', width: 90 , hidden: true, editable: true, 
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'DoB', name: 'dob', width: 90 , hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Nationality', name: 'nationality', width: 90 ,  hidden: true},
					
					{label: 'Country', name: 'countrycode', width: 90 ,  hidden: true},
					
					{label: 'Visa No', name: 'visano', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Passport No', name: 'passportno', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					{label: 'Mobile No', name: 'phoneno', width: 90, hidden: true, editable: true ,
					editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Fax', name: 'fax', width: 90 ,  hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Email', name: 'email', width: 90 ,  hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Payable To', name: 'regfees', width: 90 ,  hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
                           
					// {label: 'Bill Type IP', name: 'billtype', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Bill Type OP', name: 'billtypeop', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Record Status', name: 'recstatus', width: 90, hidden: true, editable:true,
					// checked:true, editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Outamt', name: 'outamt', width: 90 , hidden: true},
					
					// {label: 'Deposit Amount', name: 'depamt', width: 90, classes: 'wrap', editable: true ,
					// editrules:{ required: true}}, 
					
					// {label: 'Credit Limit', name: 'creditlimit', width: 90, hidden: true, editable: true,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Debtor CCode', name: 'actdebccode', width: 90, classes: 'wrap', editable: true,
					// editrules:{ required: true}},  
					
					// {label: 'Debtor Acct', name: 'actdebglacc', width: 90, classes: 'wrap', editable: true,
					// editrules:{ required: true}},
					
					// {label: 'Deposit CCode', name: 'depccode', width: 90, classes: 'wrap', editable: true,
					// editrules:{ required: true}},
					
					// {label: 'Deposit Acct', name: 'depglacc', width: 90, classes: 'wrap', editable: true,
					// editrules:{ required: true}},
					
					// {label: 'Otherccode', name: 'otherccode', width: 90, hidden: true},
					
					// {label: 'Otheracct', name: 'otheracct', width: 90, hidden: true},
					
					// {label: 'Lastupdate', name: 'lastupdate', width: 90, hidden: true, editable: true},
					
					// {label: 'Lastuser', name: 'lastuser', width: 90, hidden: true, editable: true},
					
					// {label: 'Debtor Group', name: 'debtorgroup', width: 90, hidden: true, editable: true,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Credit Control Group', name: 'crgroup', width: 90, hidden: true, editable: true,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Otheraddr1', name: 'otheraddr1', width: 90 , hidden: true},
					
					// {label: 'Otheraddr2', name: 'otheraddr2', width: 90 , hidden: true},
					
					// {label: 'Otheraddr3', name: 'otheraddr3', width: 90 , hidden: true},
					
					// {label: 'Otheraddr4', name: 'otheraddr4', width: 90 , hidden: true},
					
					// {label: 'Bank Acc. No', name: 'accno', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Othertel', name: 'othertel', width: 90 , hidden: true},
					
					// {label: 'Request GL', name: 'requestgl', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
                            
					// {label: 'Credit Term', name: 'creditterm', width: 90,  hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Adduser', name: 'adduser', width: 90 , hidden: true, editable: true},
					
					// {label: 'Adddate', name: 'adddate', width: 90 , hidden: true, editable: true},
                          
					// {label: 'Coverage IP', name: 'coverageip', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},
					
					// {label: 'Coverage OP', name: 'coverageop', width: 90, hidden: true, editable: true ,
					// editrules:{ required: true, edithidden: true, hidedlg: true}},				
					
					{label: 'Actions', name:'act', width:50, classes: 'wrap', checked:true, sortable:false},
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#doc_grid_pager",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#doc_grid_pager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(editedRow!=0){
						$("#doc_grid").jqGrid('setSelection',editedRow,false);
					}

					var ids = jQuery("#doc_grid").jqGrid('getDataIDs');
					for(var i=0;i < ids.length;i++){
						var cl = ids[i];
						sestime = "<input type='button' value='Session Time' onclick=\"$('#session_dialog').dialog( 'open' );\"  />"; 
						offtime = "<input type='button' value='Out of Office' onclick=\"jQuery('#doc_grid').saveRow('"+cl+"');\"  />";
						jQuery("#doc_grid").jqGrid('setRowData',ids[i],{act:sestime + '&nbsp;&nbsp;&nbsp;&nbsp;' + offtime});
					}
				},
				
			});
			
			function refresh_doc_grid(){
				$("#doc_grid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
			}
			
			$("#doc_grid").jqGrid('navGrid','#doc_grid_pager',
				{	
					view:false,edit:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refresh_doc_grid();
					},
				},{},{},
				{	afterSubmit : function( data, postdata, oper){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];
					},
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText;
					}
			}).jqGrid('navButtonAdd',"#doc_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper='del';
					selRowId = $("#doc_grid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata();
					}else{
						saveFormdata('del',{'staffno':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#doc_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper='view';
					selRowId = $("#doc_grid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'view');
					// checkInput('hisdb.staffno','staffno',['debtortycode','description'],$('#debtortype').val());
					// checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					// checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#doc_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper='edit';
					selRowId = $("#doc_grid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'edit');
					// checkInput('debtor.debtortype','debtortype',['debtortycode','description'],$('#debtortype').val());
					// checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					// checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
					enableForm('#dialogForm');
					// $("#debtorcode").prop("readonly",true);
					
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#doc_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "option", "buttons", btn1 );
					$( "#dialogForm" ).dialog( "option", "title", "Add" );
					$("#dialogForm").dialog( "open" );
					enableForm('#dialogForm');
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});

			function open_session_time_dialog(grid_id)
			{
				$('#session_dialog').dialog( 'option', 'buttons', btn1 );
				$("#session_dialog").dialog( "option", "title", "Set Doctor's Session Time" );
				$("#session_dialog").dialog( "open" );
			}
			
					
			// function populateFormdata(selRowId,state){
			// 	if(!selRowId){
			// 		alert('Please select row');
			// 		return emptyFormdata();
			// 	}
			// 	switch(state) {
			// 		case state = 'edit':
			// 			$( "#dialogForm" ).dialog( "option", "title", "Edit" );
			// 			$( "#dialogForm" ).dialog( "option", "buttons", butt1 );
			// 			break;
			// 		case state = 'view':
			// 			disableForm('#dialogForm');
			// 			$( "#dialogForm" ).dialog( "option", "title", "View" );
			// 			$( "#dialogForm" ).dialog( "option", "buttons", butt2 );
			// 			break;
			// 		default:
			// 	}
				
			// 	$("#dialogForm").dialog( "open" );
			// 	rowData = $("#doc_grid").jqGrid ('getRowData', selRowId);
			// 	$.each(rowData, function( index, value ) {
			// 		var input=$("[name='"+index+"']");
			// 		if(input.is("[type=radio]")){
			// 			$("[name='"+index+"'][value='"+value+"']").prop('checked', true);
			// 		}else{
			// 			input.val(value);
			// 		}
			// 	});
			// }
			
			// function emptyFormdata(){
			// 	errorField.length=0;
			// 	$('#formdata').trigger('reset');
			// 	$('.help-block').html('');
			// }
			
			// function saveFormdata(oper,obj){
			// 	if(obj==null){
			// 		obj={null:'null'};
			// 	}
			// 	var param={
			// 		action:'save_table_default',
			// 		oper:oper,
			// 		table_name:'debtor.debtormast',
			// 		table_id:'debtorcode'
			// 	};
			// 	$.post( "../../../../assets/php/entry.php?"+$.param(param), $( "#formdata" ).serialize()+'&'+$.param(obj) , function( data ) {
					
			// 	}).fail(function(data) {
			// 		errorText(data.responseText);
			// 	}).success(function(data){
			// 		$('#dialogForm').dialog('close');
			// 		editedRow = $("#doc_grid").jqGrid ('getGridParam', 'selrow');
			// 		refreshGrid();
			// 	});
			// }
			
			// function errorText(text){
			// 	$( "#formdata" ).prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> "+text+"</div>");
			// }
			
			// var selText,Dtable,Dcols;
			// $("#gridDialog").jqGrid({
			// 	datatype: "local",
			// 	colModel: [
			// 		{ label: 'Code', name: 'code', width: 100, classes: 'pointer', canSearch:true}, 
			// 		{ label: 'Description', name: 'desc', width: 200, classes: 'pointer', canSearch:true},
			// 		{ label: 'Debtor CCode', name: 'actdebccode', classes: 'pointer', hidden: true},
			// 		{ label: 'Debtor Acct', name: 'actdebglacc', classes: 'pointer', hidden: true},
			// 		{ label: 'Deposit CCode', name: 'depccode', classes: 'pointer', hidden: true},
			// 		{ label: 'Deposit Acct', name: 'depglacc', classes: 'pointer', hidden: true},
		
			// 	],
			// 	width: 680,
			// 	viewrecords: true,
			// 	loadonce: true,
   //              multiSort: true,
			// 	rowNum: 30,
			// 	pager: "#gridDialogPager",
			// 	ondblClickRow: function(rowid, iRow, iCol, e){
			// 		var data=$("#gridDialog").jqGrid ('getRowData', rowid);
			// 		$( "#dialog" ).dialog( "close" );
			// 		if(selText=='debtortype' ){
			// 			$('#actdebccode').val(data['actdebccode']);
			// 			$('#actdebglacc').val(data['actdebglacc']);
			// 			$('#depccode').val(data['depccode']);
			// 			$('#depglacc').val(data['depglacc']);
			// 		}
			// 		$('#'+selText).val(rowid);
			// 		$('#'+selText).focus();
			// 		$('#'+selText).parent().next().html(data['desc']);
			// 	},
				
			// });
			
			// var delay = (function(){
			// 	var timer = 0;
			// 	return function(callback, ms){
			// 		clearTimeout (timer);
			// 		timer = setTimeout(callback, ms);
			// 	};
			// })();
			
			// populateSelect();
			// function populateSelect(){
			// 	$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			// 		if(value['canSearch']){
			// 			if(value['checked'])	{
			// 			$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
			// 			}
			// 			else	{
			// 				$("#Scol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"'>"+value['label']+"</input></label>" );
			// 			}
			// 		}
			// 	});
			// }
			
			// $('#Stext').keyup(function() {
			// 	delay(function(){
			// 		search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
			// 	}, 500 );
			// });
			
			// $('#Scol').change(function(){
			// 	search($('#Stext').val(),$('#searchForm input:radio[name=dcolr]:checked').val());
			// });
			
			// function search(Stext,Scol){
			// 	$("#doc_grid").jqGrid('setGridParam',{datatype:'json',url:'../../../assets/php/entry.php?'+$.param(urlParam)+'&Scol='+Scol+'&Stext='+Stext}).trigger('reloadGrid');
			// }
			
			// var paramD={action:'get_table_default',table_name:'',field:'',table_id:''};
			// function dialogHandler(table,id,cols,title){
			// 	$( "#"+id+" ~ a" ).on( "click", function() {
			// 		selText=id,Dtable=table,Dcols=cols,
			// 		$( "#dialog" ).dialog( "open" );
			// 		$( "#dialog" ).dialog( "option", "title", title );
			// 		paramD.table_name=table;
			// 		paramD.field=cols;
			// 		paramD.table_id=cols[0];
					
			// 		$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
			// 		$('#Dtext').val('');$('#Dcol').html('');
					
			// 		$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {
			// 			if(value['canSearch']){
			// 				$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+value['label']+"</input></label>" );
			// 			}
			// 		});
			// 	});
			// 	$("#"+id).on("blur", function(){
			// 		checkInput(table,id,cols,$( "#"+id ).val());
			// 	});
			// }
			
			// function checkInput(table,id,field,value){
			// 	var param={action:'input_check',table:table,field:field,value:value};
			// 	$.get( "../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
			// 	},'json').done(function(data) {
			// 		if(data.msg=='success'){
			// 			var index = errorField.indexOf(id);
			// 			if (index > -1) {
			// 				errorField.splice(index, 1);
			// 			}
			// 			$( "#"+id ).parent().siblings( ".help-block" ).html(data.row[field[1]]);
			// 		}else if(data.msg=='fail'){
			// 			errorField.push(id);
			// 			$( "#"+id ).parent().removeClass( "has-success" ).addClass( "has-error" );
			// 			$( "#"+id ).removeClass( "valid" ).addClass( "error" );
			// 			$( "#"+id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
			// 		}
			// 	});
			// }
			
			// $('#Dtext').keyup(function() {
			// 	delay(function(){
			// 		Dsearch($('#Dtext').val(),$('#dialog input:radio[name=dcolr]:checked').val());
			// 	}, 500 );
			// });
			
			// $('#Dcol').change(function(){
			// 	Dsearch($('#Dtext').val(),$('#dialog input:radio[name=dcolr]:checked').val());
			// });
			
			// function Dsearch(Dtext,Dcol){
			// 	$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)+'&Scol='+Dcol+'&Stext='+Dtext}).trigger('reloadGrid');
			// }


			// public holiday grid

			var curr_yr = new Date().getFullYear();

			for (var i = curr_yr - 3; i <= curr_yr + 3; i++) 
			{
				var sltd = "";
				if (i == curr_yr) { sltd = "selected"; }
				$("#cmb_ph_year").append('<option value="'+i+'" '+sltd+'>'+i+'</option>');
			}			

			var ph_grid_param={
				action:'get_table_default',
				field:['compcode', 'remark', 'datefr', 'dateto'],
				except:['sysno','upduser','upddate','deluser','deldate'],
				table_name:'hisdb.apptph',
				table_id:'compcode'
			}
			
			$("#ph_grid").jqGrid({
				url: '../../../../assets/php/entry.php?'+$.param(ph_grid_param),
				datatype: "json",
				 colModel: [
					{label: 'compcode', name: 'compcode', width: 90 , hidden: true},
					
					{label: 'Event Name', name: 'remark', width: 150, classes: 'wrap', checked:true,
					canSearch: true,
					editable: true, editrules:{ required: true}}, 
					
					{label: 'Date From', name: 'datefr', width: 50, classes: 'wrap', checked:true,
					canSearch: true,  
					editable: true, editrules:{ required: true}},

					{label: 'Date End', name: 'dateto', width: 50, classes: 'wrap', checked:true,
					canSearch: true,  
					editable: true, editrules:{ required: true}},
										
					// {label: 'Lastupdate', name: 'lastupdate', width: 90, hidden: true, editable: true},
					
					// {label: 'Lastuser', name: 'lastuser', width: 90, hidden: true, editable: true},
					
					// {label: 'Adduser', name: 'adduser', width: 90 , hidden: true, editable: true},
					
					// {label: 'Adddate', name: 'adddate', width: 90 , hidden: true, editable: true},
					
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#ph_grid_pager",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#ph_grid_pager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(editedRow!=0){
						$("#ph_grid").jqGrid('setSelection',editedRow,false);
					}
				},
				
			});

			function refresh_ph_grid(){
				$("#ph_grid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
			}
			
			$("#ph_grid").jqGrid('navGrid','#ph_grid_pager',
				{	
					view:false,edit:false,add:false,del:false,search:false,
					beforeRefresh: function(){
						refresh_ph_grid();
					},
				},{},{},
				{	afterSubmit : function( data, postdata, oper){
						$("#jqGrid").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
						return [true,'',''];
					},
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText;
					}
			}).jqGrid('navButtonAdd',"#ph_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper='del';
					selRowId = $("#ph_grid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata();
					}else{
						saveFormdata('del',{'staffno':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#ph_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper='view';
					selRowId = $("#ph_grid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'view');
					// checkInput('hisdb.staffno','staffno',['debtortycode','description'],$('#debtortype').val());
					// checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					// checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#ph_grid_pager",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper='edit';
					selRowId = $("#ph_grid").jqGrid ('getGridParam', 'selrow');
					populateFormdata(selRowId,'edit');
					// checkInput('debtor.debtortype','debtortype',['debtortycode','description'],$('#debtortype').val());
					// checkInput('hisdb.billtymst','billtype',['billtype','description'], $('#billtype').val());
					// checkInput('hisdb.billtymst','billtypeop',['billtype','description'], $('#billtypeop').val());
					enableForm('#dialogForm');
					// $("#debtorcode").prop("readonly",true);
					
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#ph_grid_pager",{
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



			
		});
		