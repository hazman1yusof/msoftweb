
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

			// dialog_deptcode=new makeDialog('finance.fatran','#deptcode',['assetno','deptcode','olddeptcode','curloccode','oldloccode','trandate','auditno'], 'Particulars of Asset Movement');
		 //   	dialog_deptcode.handler(errorField);

			var mycurrency =new currencymode(['#origcost','#purprice','#lstytddep','#cuytddep','#nbv']);
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
							//$("#assetno").val('');

							break;
						case state = 'view':
							mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							getmethod_and_res(selrowData("#jqGrid").assetcode);
							getRate(selrowData("#jqGrid").assetcode);
							getNVB();
							break;
					}
					if(oper!='view'){
						//dialog_deptcode.handler(errorField);
					}
					if(oper!='add'){
						toggleFormData('#jqGrid','#formdata');
						//dialog_dept.check(errorField);
						//dialog_deptcode.handler(errorField);

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

			$("#msgBox").dialog({
            	autoOpen : false, 
            	modal : true,
				width: 8/10 * $(window).width(),
				open: function(){
					addParamField("#gridhist",true,urlParamhist);
					$("#gridhist").jqGrid ('setGridWidth', Math.floor($("#gridhist_c")[0].offsetWidth-$("#gridhist_c")[0].offsetLeft));
				},  
            	buttons: [{
					text: "Cancel",click: function() {
						$(this).dialog('close');
					}
				}]
            });

            $("#histbut").click(function(){
            	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
            	if(!selRowId){
            		bootbox.alert('Please select row');
            	}else{

            		$("span[name='assetno']").text(selrowData('#jqGrid').assetno);
	            	$("span[name='description']").text(selrowData('#jqGrid').description);
					
            		urlParamhist.filterVal[0] = selrowData('#jqGrid').assetno;
					$("#msgBox").dialog("open");
            	}
            });

            var urlParamhist = {

            
				action:'get_table_default',
				field:['assetno','assetcode','assettype','deptcode','olddeptcode','curloccode','oldloccode','trandate','adduser'],
				table_name:'finance.fatran',
				table_id:'deptcode',
				filterCol:['assetno'],
				filterVal:[''],
			}

            $("#gridhist").jqGrid({
				datatype: "local",
				colModel: [
					//{label: 'Assetno', name: 'assetno', classes: 'wrap'},
					//{label: 'Category', name: 'assetcode', classes: 'wrap'},
					//{label: 'Type', name: 'assettype', classes: 'wrap'},
					{label: 'Current Dept', name: 'deptcode', classes: 'wrap',formatter:showdetail},
					{label: 'Prev Dept', name: 'olddeptcode', classes: 'wrap',formatter:showdetail},
					{label: 'Current Loc', name: 'curloccode', classes: 'wrap',formatter:showdetail},
					{label: 'Prev Loc', name: 'oldloccode', classes: 'wrap',formatter:showdetail},
					{label: 'Trandate', name: 'trandate', classes: 'wrap',formatter:dateFormatter},
					{label: 'Entered By', name: 'adduser', classes: 'wrap'},
					{label: 'Entered Date', name: 'adddate', classes: 'wrap',},
					],
					
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
				sortname:'idno',
		        sortorder:'desc',
				pager: "#gridhistpager",
			});
 
			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'finance.faregister',
				table_id:'idno',
				sort_idno:true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.faregister',
				table_id:'idno'
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					//{ label: 'compcode', name: 'compcode', width: 40, hidden:true},		
					{label: 'Type', name: 'assettype', width: 10, classes: 'wrap',canSearch: true},		
					 {label: 'Category', name: 'assetcode', width: 15, classes: 'wrap', canSearch: true,checked:true},		
					 {label: 'Asset No', name: 'assetno', width: 10, classes: 'wrap'},
					{label: 'Item Code', name: 'itemcode', width: 20, classes: 'wrap',hidden:true},
					{ label: 'Description', name: 'description', width: 40, classes: 'wrap', canSearch: true},
					{ label: 'Serial No', name: 'serialno', width: 20,classes: 'wrap',hidden:true},
					{ label: 'Lotno', name: 'lotno', width: 20,classes: 'wrap',hidden:true},
					{ label: 'Casisno', name: 'casisno', width: 20, classes: 'wrap',hidden:true},
					{ label: 'Engineno', name: 'engineno', width: 20, classes: 'wrap',hidden:true},
					{ label: 'Department', name: 'deptcode', width: 20, classes: 'wrap'},
                    { label: 'Location', name: 'loccode', width: 20, classes: 'wrap'},
                    { label: 'Invoice No', name: 'invno', width: 20, classes: 'wrap',hidden:true},
                    { label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', hidden:true},
                    { label: 'Quantity', name: 'qty', width: 20,  align: 'right',classes: 'wrap'},
                    //{ label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
                    { label: 'Start Date', name:'statdate', width:20, classes:'wrap',  hidden:true},
					{ label: 'Post Date', name:'trandate', width:20, classes:'wrap',  hidden:true},
                   // { label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
                    { label: 'lstytddep', name:'lstytddep', width:20, classes:'wrap', hidden:true},
                    { label: 'cuytddep', name:'cuytddep', width:20, classes:'wrap', hidden:true},
                    { label: 'Cost', name: 'origcost', width: 20, classes: 'wrap', align: 'right',formatter:'currency'},
                    { label: 'SuppCode', name: 'suppcode', width: 20, classes: 'wrap'},
                    { label: 'Purchase Order No', name:'purordno',width: 20, classes:'wrap', hidden:true},
                    { label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', hidden:true},																	
					{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
                    { label: 'D/O No', name: 'delordno', width: 20, classes: 'wrap'},
                    { label: 'DO Date', name:'delorddate', width: 20, classes:'wrap', hidden:true},
                    //{ label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
					{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', hidden:true,
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

			function showdetail(cellvalue, options, rowObject){
				var field,table;
				switch(options.colModel.name){
					case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
					case 'olddeptcode':field=['deptcode','description'];table="sysdb.department";break;
					case 'loccode':field=['catcode','description'];table="material.category";break;
					case 'suppcode':field=['taxcode','description'];table="hisdb.taxmast";break;
					default: return cellvalue;
				}
				var param={action:'input_check',table:table,field:field,value:cellvalue};
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.row)){
						$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
					}
				});
				return cellvalue;
			}

			function getNVB() {
				var origcost = $("#origcost").val();
				var lstytddep = $("#lstytddep").val();
				var cuytddep = $("#cuytddep").val();

				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(total.toFixed(2));
			}
			
			

			$("#origcost").keydown(function(e) {
					delay(function(){
						var origcost = $("#origcost").val();
						var lstytddep = $("#lstytddep").val();
						var cuytddep = $("#cuytddep").val();

						if($("#origcost").val() == '') {
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(total.toFixed(2));
						}
						else{
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(total.toFixed(2));
						}
						}, 1000 );
			});

			$("#lstytddep").keydown(function(e) {
					delay(function(){
						var origcost = currencyRealval("#origcost");
						var lstytddep = currencyRealval("#lstytddep");
						var cuytddep = currencyRealval("#cuytddep");

						if($("#lstytddep").val() == '') {
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(numeral(total).format('0,0.00'));
						}
						else{
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(numeral(total).format('0,0.00'));
						}
						}, 1000 );
			});

			$("#cuytddep").keydown(function(e) {
					delay(function(){
						var origcost = currencyRealval("#origcost");
						var lstytddep = currencyRealval("#lstytddep");
						var cuytddep = currencyRealval("#cuytddep");

						if($("#cuytddep").val() == '') {
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(numeral(total).format('0,0.00'));
						}
						else{
							total = origcost - lstytddep - cuytddep;
							$("#nbv").val(numeral(total).format('0,0.00'));
						}
					}, 1000 );
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

			function getRate(assetcode){
				var param={
					action:'get_value_default',
					field:['rate'],
					table_name:'finance.facode',
					table_id:'idno',
					filterCol:['assetcode'],
					filterVal:[assetcode],
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data)){
							$("#rate").val(data.rows[0].rate);
							//$("#rvalue").val(data.rows[0].residualvalue);
						}
					});
			}
			
			////////////////////////////formatter//////////////////////////////////////////////////////////
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
			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("DD-MM-YYYY");
			}
               
           $("#jqGrid").jqGrid('setLabel', 'origcost', 'Cost', {'text-align':'right'});
           $("#jqGrid").jqGrid('setLabel', 'qty', 'Quantity', {'text-align':'right'});
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
			toogleSearch('#sbut1','#searchForm','on');
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['idno']);

			       
               

			
		});
		