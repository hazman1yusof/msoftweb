$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$('body').show();
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
								element : $(errorField[0]),
								message : ' '
							}
						}
					},
				};
			//////////////////////////////////////////////////////////////
				
		////////////////////////////////////start dialog///////////////////////////////////////

		// dialog_assetcode=new makeDialog('finance.facode','#assetcode',['assetcode','description','assettype','method','residualvalue'],'Category');
		var dialog_assetcode= new ordialog(
			'assetcode','finance.facode','#assetcode',errorField,
			{	colModel:[
				    {label:'Assetcode',name:'assetcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
					{label:'AssetType',name:'assettype',width:100,classes:'pointer',hidden:true},
					{label:'Method',name:'method',width:100,classes:'pointer',hidden:true},
					{label:'Residualvalue',name:'residualvalue',width:100,classes:'pointer',hidden:true},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_assetcode.gridname);
				$('#assettype').val(data['assettype']);		
				$('#method').val(data['method']);
				$('#rvalue').val(data['residualvalue']);
			}},
			{
				title:"Select Category",
				open: function(){
					dialog_assetcode.urlParam.filterCol=['compcode'];
					dialog_assetcode.urlParam.filterVal=['9A'];
				}
			},'urlParam'
		);
		dialog_assetcode.makedialog();

		// dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
		var dialog_deptcode= new ordialog(
			'deptcode','sysdb.department','#deptcode',errorField,
			{	colModel:[
				    {label:'Deptcode',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
			]},
			{
				title:"Select Department",
				open: function(){
					dialog_deptcode.urlParam.filterCol=['compcode'],
					dialog_deptcode.urlParam.filterVal=['9A']
				}
			},'urlParam'
		);
		dialog_deptcode.makedialog();

		// dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
		var  dialog_loccode= new ordialog(
			'loccode','sysdb.location','#loccode',errorField,
			{	colModel:[
				    {label:'Loccode',name:'loccode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},

			]
			},
			{
				title:"Select Location",
				open: function(){
					dialog_loccode.urlParam.filterCol=['compcode'],
					dialog_loccode.urlParam.filterVal=['9A']
				}
			},'urlParam'
		);
		dialog_loccode.makedialog();		

		// dialog_delordno=new makeDialog('material.delordhd','#delordhd',['Delordhd','Suppcode'],'Delordno');
	    var dialog_delordno= new ordialog(
			'delordno','material.delordhd','#delordno',errorField,
			{	colModel:[
				    {label:'Delordno',name:'delordno',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Suppcode',name:'suppcode',width:300,classes:'pointer',canSearch:true,or_search:true},
							
					]
			},{
				title:"Select Delordno",
				open: function(){
					dialog_delordno.urlParam.filterCol=['compcode'],
					dialog_delordno.urlParam.filterVal=['9A']
				}
			},'urlParam'
		);
		dialog_delordno.makedialog();

		// dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'],'Supplier');
		var  dialog_suppcode= new ordialog(
			'suppcode','material.supplier','#suppcode',errorField,
			{	colModel:[
				    {label:'SuppCode',name:'suppcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Name',name:'name',width:300,classes:'pointer',canSearch:true,or_search:true},

			]
			},{
				title:"Select Supplier",
				open: function(){
					dialog_suppcode.urlParam.filterCol=['compcode'],
					dialog_suppcode.urlParam.filterVal=['9A']
				}
			},'urlParam'
		);
		dialog_suppcode.makedialog();
		
		// dialog_itemcode=new makeDialog('material.product','#itemcode',['itemcode','description'],'itemcode');
		var dialog_itemcode= new ordialog(
			'itemcode','material.product','#itemcode',errorField,
			{	colModel:[
					{label:'Itemcode',name:'itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
					
					]
			},{
				title:"Select Itemcode",
				open: function(){
					dialog_itemcode.urlParam.filterCol=['compcode','groupcode'],
					dialog_itemcode.urlParam.filterVal=['9A','asset']
				}
			},'urlParam'
		);
		dialog_itemcode.makedialog();
	
		// dialog_assettype=new makeDialog('finance.fatype','#assettype',['assettype','description'], 'Type');	
		/*var  dialog_assettype= new ordialog(
			'assettype','finance.fatype','#assettype',errorField,
			{	colModel:[
				    {label:'AssetType',name:'assettype',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},

			]
			},{
				title:"Select Type",
				open: function(){
					dialog_assettype.urlParam.filterCol=['compcode'];
					dialog_assettype.urlParam.filterVal=['9A'];
				}
			},'urlParam'
		);
<<<<<<< HEAD
		dialog_deptcode.makedialog();
		
		// dialog_assetcode=new makeDialog('finance.facode','#assetcode',['assetcode','description','assettype','method','residualvalue'],'Category');
		var dialog_assetcode= new ordialog(
			'assetcode','finance.facode','#assetcode',errorField,
			{	colModel:[
				    {label:'Assetcode',name:'assetcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
					{label:'AssetType',name:'assettype',width:100,classes:'pointer',hidden:true},
					{label:'Method',name: 'method',width:100,classes:'pointer',hidden:true},
					{label:'Residualvalue',name:'residualvalue',width:100,classes:'pointer',hidden:true},

			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_assetcode.gridname);
				$('#assettype').val(data['assettype']);
				$('#method').val(data['method']);
				$('#rvalue').val(data['residualvalue']);
			
				
			}
=======
		dialog_assettype.makedialog();*/

		var butt1=[{
				text: "Save",click: function() {
					if( $('#formdata').isValid({requiredFields: ''}, conf, true) && checkdate_asset()) {
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
					}
				}
			},{
			//var butt1=[{
				//text: "Save",click: function() {
				//	mycurrency.formatOff();
					//mycurrency.check0value(errorField);
					//if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
						//saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,null,{idno:selrowData("#jqGrid").idno});
					//}else{
						//mycurrency.formatOn();
					//}
				//}
			//},{

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
							//mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Add" );
							enableForm('#formdata');
							rdonly("#dialogForm");
							break;
						case state = 'edit':
							//mycurrency.formatOnBlur();
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#dialogForm");

							break;
						case state = 'view':
							//mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							getmethod_and_res(selrowData("#jqGrid").assetcode);
							getNVB();
							//getOrigCost();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_itemcode.on();
						dialog_delordno.on();
						dialog_assetcode.on();
						//dialog_assettype.on();
						dialog_suppcode.on();
						dialog_deptcode.on();
						dialog_loccode.on();
						//dialog_dept.handler(errorField);
					}
					if(oper!='add'){
						dialog_itemcode.check(errorField);
						dialog_delordno.check(errorField);
						dialog_assetcode.check(erorField);
						//dialog_assettype.check(errorfield);
						dialog_suppcode.check(errorfield);
						dialog_deptcode.check(errorfield);
						dialog_loccode.check(errorfield);
						//toggleFormData('#jqGrid','#formdata');
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
				url: '/util/get_table_default',
				field:'',
				table_name:'finance.fatemp', 
				table_id:'idno',				
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				url:'assetregister/form',
				oper:oper,
				table_name:'finance.fatemp',
				table_id:'idno' 				
				
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
					{ label: 'Idno', name: 'idno', width: 8, sorttype: 'text', classes: 'wrap', checked: true}, 
					{ label: 'Category', name: 'assetcode', width: 15, sorttype: 'text', classes: 'wrap' },
					{ label: 'Asset Type', name: 'assettype', width: 15, sorttype: 'text', classes: 'wrap'},
					{ label: 'Department', name: 'deptcode', width: 15, sorttype: 'text', classes: 'wrap'},			
					{ label: 'Location', name: 'loccode', width: 40, sorttype: 'text', classes: 'wrap', hidden:true},					
					{ label: 'Supplier', name: 'suppcode', width: 20, sorttype: 'text', classes: 'wrap'},	
					{ label: 'DO No', name:'delordno',width: 15, sorttype:'text', classes:'wrap'},					
					{ label: 'Invoice No', name:'invno', width: 20,sorttype:'text', classes:'wrap', canSearch: true},
					{ label: 'Purchase Order No', name:'purordno',width: 20, sorttype:'text', classes:'wrap', hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 15, sorttype: 'text', classes: 'wrap', canSearch: true},
					//{ label: 'Description', name: 'description', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true,},
					{ label: 'DO Date', name:'delorddate', width: 20, classes:'wrap',formatter:dateFormatter, hidden:true},
					{ label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
					{ label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},																	
					{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
					{ label: 'Original Cost', name:'origcost', width: 20, classes:'wrap', hidden:true},
					{ label: 'Current Cost', name:'currentcost', width:20, classes:'wrap', hidden:true},
					{ label: 'Quantity', name:'qty', width:20, classes:'wrap', hidden:true},
					{ label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
					{ label: 'Delivery Order Line No', name:'lineno_', width:20, classes:'wrap', hidden:true},
					//method
					//residual value
					{ label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'Post Date', name:'trandate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					//accumprev
					{ label: 'Accum Prev', name:'lstytddep', width:20, classes:'wrap', hidden:true},
					//accumytd
					{ label: 'Accum YTD', name:'cuytddep', width:20, classes:'wrap', hidden:true},
					//nbv
					{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:true},
					{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},

								
				
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname: 'idno',
				sortorder: 'desc',
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='View Selected Row']").click();
				},
				gridComplete: function(){
					if (oper == 'add') {
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
						}
	
						$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
				},				
			});

			////////////////////////////// DATE FORMATTER ////////////////////////////////////////

			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");
			}

			////////////////////////////////////////////////////////////////////////////////////////

			/*/////////////inBetween date///////////
			function validate_actdate(event){
					
					if(!moment(obj.target.value).isBetween(actdateObj.lowestdate,actdateObj.highestdate)){
						bootbox.alert('Date not in accounting period setup');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					}else if(!permission){
						bootbox.alert('Accounting Period Has been Closed');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					} //Accounting Period Has been Closed
						//Date not in accounting period setup
					
				}

			////////////////////////////	*/	
			///////////////////////// REGISTER TYPE SELECTION///////////////////////////////////////

			$("input[name=regtype]:radio").on('change', function(){
				regtype  = $("input[name=regtype]:checked").val();
				if(regtype == 'P'){
					disableField();
				}else if(regtype == 'D') {
					enableField();
					
				}
			});

			function disableField() {
				$("#invno").prop('readonly',true);
				$("#delordno").closest( "div" ).show('fast');
				$("#delorddate").prop('readonly',true);
				$("#invdate").prop('readonly',true);
				$("#docno").prop('readonly',true);
				//$("#description").prop('readonly',true);
				$("#purordno").prop('readonly',true);
				$("#purdate").prop('readonly',true);
				$("#purprice").prop('readonly',true);
				$("#origcost").prop('readonly',true);
				$("#currentcost").prop('readonly',true);
				$("#qty").prop('readonly',true);
			}

			function enableField() {
				$("#invno").prop('readonly',false);
				$("#delordno").closest( "div" ).hide('fast');
				$("#delorddate").prop('readonly',false);
				$("#invdate").prop('readonly',false);
				$("#docno").prop('readonly',false);
				//$("#description").prop('readonly',false);
				$("#purordno").prop('readonly',false);
				$("#purdate").prop('readonly',false);
				$("#purprice").prop('readonly',false);
				$("#origcost").prop('readonly',false);
				$("#currentcost").prop('readonly',false);
				$("#qty").prop('readonly',false);
			}

			// function getNVB() { 
			// 	var origcost = $("#origcost").val();
			// 	var lstytddep = $("#lstytddep").val();
			// 	var cuytddep = $("#cuytddep").val();

			// 	total = origcost - lstytddep - cuytddep;
			// 	$("#nbv").val(total.toFixed(2));
			// }
			
			// function getOrigCost() {
			// 	//var origcost = $("#origcost").val();
			// 	var netunitprice = $("#netunitprice").val();
			// 	var prortdisc = $("#prortdisc").val();

			// 	total = netunitprice - prortdisc ;
			// 	$("#origcost").val(total.toFixed(2));
			// }

			// $("#origcost").keydown(function(e) {
			// 		delay(function(){
			// 			var origcost = $("#origcost").val();
			// 			var lstytddep = $("#lstytddep").val();
			// 			var cuytddep = $("#cuytddep").val();

			// 			if($("#origcost").val() == '') {
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(total.toFixed(2));
			// 			}
			// 			else{
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(total.toFixed(2));
			// 			}
			// 		}, 1000 );
			// });

			// $("#lstytddep").keydown(function(e) {
			// 		delay(function(){
			// 			var origcost = currencyRealval("#origcost");
			// 			var lstytddep = currencyRealval("#lstytddep");
			// 			var cuytddep = currencyRealval("#cuytddep");

			// 			if($("#lstytddep").val() == '') {
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(numeral(total).format('0,0.00'));
			// 			}
			// 			else{
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(numeral(total).format('0,0.00'));
			// 			}
			// 		}, 1000 );
			// });

			// $("#cuytddep").keydown(function(e) {
			// 		delay(function(){
			// 			var origcost = currencyRealval("#origcost");
			// 			var lstytddep = currencyRealval("#lstytddep");
			// 			var cuytddep = currencyRealval("#cuytddep");

			// 			if($("#cuytddep").val() == '') {
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(numeral(total).format('0,0.00'));
			// 			}
			// 			else{
			// 				total = origcost - lstytddep - cuytddep;
			// 				$("#nbv").val(numeral(total).format('0,0.00'));
			// 			}
			// 		}, 1000 );
			// });


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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selRowId});
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
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam);
			
			function checkInput(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'input_check',table:table,field:field,value:value};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(data.msg=='success'){
						if($.inArray(id,errorField)!==-1){
							errorField.splice($.inArray(id,errorField), 1);
						}
						$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
						$( id ).removeClass( "error" ).addClass( "valid" );
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

			function getinvdate(document){
				var param={
					action:'get_value_default',
					field:['actdate'],
					table_name:'finance.apacthdr',
					table_id:'auditno',
					filterCol:['document'],
					filterVal:[document],
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data)){
							$('#invdate').val(moment(data.rows[0].actdate).format("YYYY-MM-DD"));
						}
					});
			}		

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

			function checkdate_asset(){
				var delorddate = $('#delorddate').val();
				var invdate = $('#invdate').val();
				var purdate = $('#purdate').val();

				var error = false,failmsg='';

				if(moment(invdate).isBefore(delorddate)){
					error = true;
					alert("Invoice date cannot be lower than Delivery Order date");
				}else{
					error = false;
				}

				if(moment(purdate).isAfter(invdate) && moment(purdate).isAfter(delorddate) ){
					error = true;
					alert("Purchase date cannot be greater than Invoice date and Delovery Order date");
				}else{
					error=false;
				}

				if(error){
					console.log(failmsg)
					return false;
				}else{
					console.log(failmsg)
					return true;
				}

			}
		
		}
	
	);