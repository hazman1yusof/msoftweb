
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

					}
					if(oper!='add'){

					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#formdata');
					$('.alert').detach();
					//$("#formdata a").off();
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
				table_name:'hisdb.billtymst',
				table_id:'billtype'
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'hisdb.billtymst',
				table_id:'billtype'
			};
			
			//////////////////////////start grid/////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'Bill Type', name: 'billtype', width: 40, canSearch:true, checked:true},
					{label: 'opprice', name: 'opprice', width: 90, hidden: true },
					{label: 'Description', name: 'description', width: 90, canSearch:true},
					{label: 'Price', name: 'price', width: 40 },
					{label: 'Amount', name: 'amount', width: 40 },
					{label: 'Percentage', name: 'percent_', width: 40, formatter:formatter1,unformat:unformat1},
					{label: 'All Service', name: 'service', width: 40, formatter:formatter,unformat:unformat},
					{label: 'discchgcode', name: 'discchgcode', width: 90 , hidden: true},
					{label: 'ttacode', name: 'ttacode', width: 90 , hidden: true},
					{label: 'discrate', name: 'discrate', width: 90 , hidden: true},
					{label: 'recstatus', name: 'recstatus', width: 90 , hidden: true},
				],
				autowidth:true,
				viewrecords: true,
				multiSort: true,
				loadonce: false,
				width: 900,
				height: 100,
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
					if(rowid != null) {
						rowData = $('#jqGrid').jqGrid ('getRowData', rowid);
						urlParam_svc.filterVal[0]=rowData['billtype']; 
						saveParam_svc.filterVal[0]=rowData['billtype']; 
						urlParam_item.filterVal[0]=rowData['billtype'];
						$("#Fsvc :input[name='svc.billtype']").val(rowData['billtype']);
						$("#Fsvc :input[name='m.description']").val(rowData['description']);
						refreshGrid('#jqGridsvc',urlParam_svc);
						$("#pg_jqGridPager2 table").show();
						if(rowData['service']==1){
							refreshGrid('#jqGridsvc',urlParam_svc);
							$("#pg_jqGridPager2 table").hide();
						}
					}
				},
				
			});

			/////////////////////////formatter & unformat/////////////////////////////////////////////////////////

			function formatter(cellvalue, option, rowObject){
				return parseInt(cellvalue) ? "Yes" : "No";
			}

			function  unformat(cellvalue, options){
				if ((cellvalue)=='Yes'){
					return "1";
				}else{
					return "0";
				}
			}

			function formatter1(cellvalue, option, rowObject){
				return cellvalue+"%";
			}

			function  unformat1(cellvalue, options){
				return cellvalue.replace("%","");
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'billtype':selRowId});
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
			
			//toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			//searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);
			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// billtysvc //////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			dialog_ChgGroup=new makeDialog('hisdb.chggroup',"#Fsvc :input[name='svc.chggroup']",['grpcode','description'], 'Chg. Group');

			var buttsvc1=[{
				text: "Save",click: function() {
					if( $('#Fsvc').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#jqGridsvc","#Dsvc","#Fsvc",oper_svc,saveParam_svc,urlParam_svc);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];

			var oper_svc;
			$("#Dsvc")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					inputCtrl("#Dsvc","#Fsvc",oper_svc);
					if(oper_svc!='view'){
						dialog_ChgGroup.handler(errorField);
					}
					if(oper_svc=='edit'){
						$("#Fsvc a").off();
					}
					if(oper_svc!='add'){
						dialog_ChgGroup.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#Fsvc');
					$('.alert').detach();
					$("#Fsvc a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttsvc1);
					}
				},
				buttons :buttsvc1,
			  });
			
			/////////////////////parameter for jqgrid url SVC/////////////////////////////////////////////////
			var urlParam_svc={
				action:'get_table_default',
				field:'',
				table_name:['hisdb.billtysvc svc','hisdb.billtymst m'],
				table_id:'chggroup',
				join_type:['LEFT JOIN'],
				join_onCol:['svc.billtype'],
				join_onVal:['m.billtype'],
				filterCol:['svc.billtype', 'svc.compcode', 'm.compcode', 'm.service'],
				filterVal:['', 'session.company', 'session.company', '0'],
			}

			var saveParam_svc={
				action:'save_table_default',
				field:'',
				oper:oper_svc,
				table_name:'hisdb.billtysvc',
				fixPost:'true',//throw out dot in the field name
				table_id:'chggroup',
				filterCol:['billtype'],
				filterVal:[''],
			};

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$("#jqGridsvc").jqGrid({
				datatype: "local",
				 colModel: [
					{label: 'Bill Type', name: 'svc.billtype', width: 50, hidden: true},
					{label: 'Description', name: 'm.description', width: 90 , hidden: true},
					{label: 'Chg. Group', name: 'svc.chggroup', width: 90,},
					{label: 'Price', name: 'svc.price', width: 90 },
					{label: 'Amount', name: 'svc.amount', width: 90 },
					{label: 'Percentage', name: 'svc.percent_', width: 90,formatter:formatter1,unformat:unformat1},
					{label: 'All Item', name: 'svc.allitem', width: 90},
					{label: 'Discount Charge Code', name: 'svc.discchgcode', width: 90},
					{label: 'discrate', name: 'svc.discrate', width: 90 , hidden: true},
					{label: 'recstatus', name: 'svc.recstatus', width: 90 , hidden: true},
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
				pager: "#jqGridPager2",
				onPaging: function(pgButton){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#gridSuppitems").jqGrid('setSelection',editedRow,false);
					}*/
				},
				onSelectRow:function(rowid, selected){
					if(rowid != null) {
						rowData = $('#jqGridsvc').jqGrid ('getRowData', rowid);
						urlParam_item.filterVal[0]=rowData['svc.billtype'];
						urlParam_item.filterVal[2]=rowData['svc.chggroup'];  
						$("#Fitem :input[name='i.billtype']").val(rowData['svc.billtype']);
						$("#Fitem :input[name='i.chggroup']").val(rowData['svc.chggroup']);
						refreshGrid('#jqGriditem',urlParam_item);
						$("#pg_jqGridPager3 table").show();
						if(rowData['svc.allitem']=='yes'){
							refreshGrid('#jqGriditem',urlParam_item);
							$("#pg_jqGridPager3 table").hide();
						}
					}
				},
				
			});
			
			$("#jqGridsvc").jqGrid('navGrid','#jqGridPager2',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGridsvc",urlParam_svc);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper_svc='del';
					var selRowId = $("#jqGridsvc").jqGrid ('getGridParam', 'selrow');
					var billtype = $("#jqgrid").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#Fsvc');
					}else{
						saveFormdata("#jqGridsvc","#Dsvc","#Fsvc",'del',saveParam,{'svc.chggroup':selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper_svc='view';
					selRowId = $("#jqGridsvc").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGridsvc","#Dsvc","#Fsvc",selRowId,'view');
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper_svc='edit';
					selRowId = $("#jqGridsvc").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGridsvc","#Dsvc","#Fsvc",selRowId,'edit');
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper_svc='add';
					$( "#Dsvc" ).dialog( "open" );
					$("#Fsvc :input[name*='svc.billtype']").val(selrowData('#jqGrid').billtype);
					$("#Fsvc :input[name*='m.description']").val(selrowData('#jqGrid').description);
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});
			
			$("#jqGridsvc").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'svc.billtype', numberOfColumns: 8, titleText: 'Bill Type Service'},
			  ]
			});

			addParamField('#jqGridsvc',false,urlParam_svc);
			addParamField('#jqGridsvc',false,saveParam_svc,["m.description"]);

			populateSelect('#jqGridsvc');
			//populateSelect('#jqGridsvc','#searchForm2');
			//searchClick('#gridSuppitems','#searchForm2',urlParam_suppitems);
			//toogleSearch('#sbut2','#searchForm2','off');


			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////////////// billtype Item //////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			dialog_chgcode=new makeDialog('hisdb.chgmast',"#Fitem :input[name='i.chgcode']",['chgcode','description'], 'Chg. Code');

			var buttitem1=[{
				text: "Save",click: function() {
					if( $('#Fitem').isValid({requiredFields: ''}, {}, true) ) {
						saveFormdata("#jqGriditem","#Ditem","#Fitem",oper_item,saveParam_item,urlParam_item);
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}];

			var oper_item;
			$("#Ditem")
			  .dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					//inputCtrl("#Ditem","#Fitem",oper_item);
					if(oper_item!='view'){
						dialog_chgcode.handler(errorField);
					}
					if(oper_item!='add'){
						dialog_chgcode.check(errorField);
					}
				},
				close: function( event, ui ) {
					emptyFormdata(errorField,'#Fitem');
					$('.alert').detach();
					$("#Fitem a").off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",buttitem1);
					}
				},
				buttons :buttitem1,
			  });
			/////////////////////parameter for jqgrid url Item/////////////////////////////////////////////////
			var urlParam_item={
				action:'get_table_default',
				field:'',
				table_name:['hisdb.billtyitem i', 'hisdb.billtysvc svc'],
				table_id:'billtype',
				join_type:['LEFT JOIN'],
				join_onCol:['i.chggroup'],
				join_onVal:['svc.chggroup'],
				filterCol:['i.billtype', 'i.compcode', 'i.chggroup', 'svc.allitem', 'svc.compcode'],
				filterVal:['', 'session.company', '', 'no', 'session.company'],
			}

			var saveParam_item={

			}
			//////////////////////////////////////////////////////////////////////////////////////////////////

			$("#jqGriditem").jqGrid({
				datatype: "local",
				 colModel: [
				 	{label: 'Bill Type', name: 'i.billtype', width: 50, hidden: true},
					//{label: 'Description', name: 'm.description', width: 90},
					{label: 'Chg. Group', name: 'i.chggroup', width: 90,},
					{label: 'chgcode', name: 'i.chgcode', width: 90},
					{label: 'Price', name: 'i.price', width: 90 },
					{label: 'Amount', name: 'i.amount', width: 90 },
					{label: 'discrate', name: 'i.discrate', width: 90 , hidden: true},
					{label: 'recstatus', name: 'i.recstatus', width: 90 , hidden: true},
				],
				viewrecords: true,
				autowidth:true,
                multiSort: true,
				loadonce:false,
				width: 900,
				height: 100,
				rowNum: 30,
				pager: "#jqGridPager3",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager3 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#gridSuppitems").jqGrid('setSelection',editedRow,false);*/
					
				},
			});
			
			$("#jqGriditem").jqGrid('navGrid','#jqGridPager3',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGriditem",urlParam_item);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-trash", 
				onClickButton: function(){
					oper_suppitems='del';
					var selRowId = $("#jqGriditem").jqGrid ('getGridParam', 'selrow');
					var billtype = $("#jqGriditem").jqGrid ('getGridParam', 'selrow');
					//var chggroup = $("#jqGriditem").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						alert('Please select row');
						return emptyFormdata(errorField,'#Fitem');
					}else{
						saveFormdata("#jqGriditem","#Ditem","#Fitem",'del',saveParam,{"chgcode":selRowId});
					}
				}, 
				position: "first", 
				title:"Delete Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-info-sign", 
				onClickButton: function(){
					oper_item='view';
					selRowId = $("#jqGriditem").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGriditem","#Ditem","#Fitem",selRowId,'view');
				}, 
				position: "first", 
				title:"View Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-edit", 
				onClickButton: function(){
					oper_item='edit';
					selRowId = $("#jqGriditem").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGriditem","#Ditem","#Fitem",selRowId,'edit');
				}, 
				position: "first", 
				title:"Edit Selected Row", 
				cursor: "pointer"
			}).jqGrid('navButtonAdd',"#jqGridPager3",{
				caption:"", 
				buttonicon:"glyphicon glyphicon-plus", 
				onClickButton: function(){
					oper_item='add';
					$( "#Ditem" ).dialog( "open" );
					$("#Fitem :input[name*='i.billtype']").val(selrowData('#jqGrid').billtype);
					$("#Fitem :input[name*='i.chggroup']").val(selrowData('#jqGridsvc').chggroup);
				}, 
				position: "first", 
				title:"Add New Row", 
				cursor: "pointer"
			});
			
			jQuery("#jqGriditem").jqGrid('setGroupHeaders', {
			  useColSpanStyle: false, 
			  groupHeaders:[
				{startColumnName: 'i.chggroup', numberOfColumns: 6, titleText: 'Bill Type Item'},
			  ]
			});

			addParamField('#jqGriditem',false,urlParam_item);
			//addParamField('#jqGriditem',false,saveParam_item,["m.description"]);

			populateSelect('#jqGriditem');
			//searchClick('#gridSuppBonus','#searchForm3',urlParam_item);
			//toogleSearch('#sbut3','#searchForm3','off');

			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
			$("#pg_jqGridPager2 table").hide();
			$("#pg_jqGridPager3 table").hide();

			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.check=checkInput;
			}

			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));

					if(selText=="#Fitem :input[name='i.chgcode']"){
						paramD.filterCol=['chggroup'];
						paramD.filterVal=[''];
					}else{
						paramD.filterCol=null;
						paramD.filterVal=null;
					}
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
					{ label: 'Code', name: 'code', width: 200,  classes: 'pointer', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer'},
				],
				width: 500,
				autowidth: true,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
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
					$("#gridDialog").jqGrid("clearGridData", true);
					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0]; 
					paramD.filterVal[0]=rowData['svc.chggroup']; 

					
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
		