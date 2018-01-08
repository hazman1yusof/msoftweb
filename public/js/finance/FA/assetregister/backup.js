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

			/////////////////Object for Dialog Handler///////////////////
				//categorycode
				dialog_assetcode=new makeDialog('finance.facode','#assetcode',['assetcode','description','assettype','method','residualvalue'],'Category');
			//linkage with assetco	//assettype
				dialog_assettype=new makeDialog('finance.fatype','#assettype',['assettype','description'], 'Type');
				//department
				dialog_deptcode=new makeDialog('sysdb.department','#deptcode',['deptcode','description'], 'Department');
				//location
				dialog_loccode=new makeDialog('sysdb.location','#loccode',['loccode','description'],'Location');
				//supplier
				dialog_suppcode=new makeDialog('material.supplier','#suppcode',['SuppCode','Name'],'Supplier');
				//delivery ordno
				dialog_delordno=new makeDialog('material.delordhd','#delordno',['delordno','suppcode'], 'Delivery Order No');
				//itemcode
				dialog_itemcode=new makeDialog('material.product','#itemcode',['itemcode','description'],'Item');
				
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
							rdonly("#dialogForm");
							break;
						case state = 'edit':
							$( this ).dialog( "option", "title", "Edit" );
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly("#dialogForm");
							break;
						case state = 'view':
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$(this).dialog("option", "buttons",butt2);
							getmethod_and_res(selrowData("#jqGrid").assetcode);
							break;
					}
					if(oper!='view'){                            
						dialog_assetcode.handler(errorField);
						//dialog_assettype.handler(errorField);
						dialog_deptcode.handler(errorField);
						dialog_loccode.handler(errorField);
						dialog_suppcode.handler(errorField);
						dialog_delordno.handler(errorField);
						dialog_itemcode.handler(errorField);
						
					}
					if(oper!='add'){
						//toggleFormData('#jqGrid','#formdata');
						dialog_assetcode.check(errorField);
						//dialog_assettype.check(errorField);
						dialog_deptcode.check(errorField);
						dialog_loccode.check(errorField);
						dialog_suppcode.check(errorField);
						dialog_delordno.check(errorField);
						//dialog_itemcode.check(errorField);
						
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
				table_name:'finance.fatemp',
				table_id:'idno',
				sort_idno:true, 
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				field:'',
				oper:oper,
				table_name:'finance.fatemp',
				table_id:'idno'       
			};
			
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
					{ label: 'Idno', name: 'idno', width: 8, sorttype: 'text', classes: 'wrap', canSearch: true, checked: true}, 
					{ label: 'Category', name: 'assetcode', width: 20, sorttype: 'text', classes: 'wrap' },
					{ label: 'Asset Type', name: 'assettype', width: 20, sorttype: 'text', classes: 'wrap'},
					{ label: 'Department', name: 'deptcode', width: 20, sorttype: 'text', classes: 'wrap'},	
					{ label: 'Item Code', name: 'itemcode', width: 20, sorttype: 'text', classes: 'wrap' },			
					{ label: 'Location', name: 'loccode', width: 40, sorttype: 'text', classes: 'wrap', hidden:true  },					
					{ label: 'Supplier', name: 'suppcode', width: 40, sorttype: 'text', classes: 'wrap', hidden:true },	
					{ label: 'Description', name: 'description', width: 40, sorttype: 'text', classes: 'wrap', canSearch: true,},						
					{ label: 'DO No', name:'delordno',width: 20, classes:'wrap', hidden:true},
					{ label: 'Purchase Order No', name:'purordno',width: 20, classes:'wrap', hidden:true},
					{ label: 'DO Date', name:'delorddate', width: 20, classes:'wrap',formatter:dateFormatter, hidden:true},
					{ label: 'Invoice No', name:'invno', width: 20, classes:'wrap', hidden:true},
					{ label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'GRN No', name:'docno', width: 20, classes:'wrap',hidden:true},
					{ label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', formatter:dateFormatter, hidden:true},																	
					{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
					{ label: 'Original Cost', name:'origcost', width: 20, classes:'wrap', hidden:true},
					{ label: 'Current Cost', name:'currentcost', width:20, classes:'wrap', hidden:true},
					{ label: 'Quantity', name:'qty', width:20, classes:'wrap', hidden:true},
					//uom
					{ label: 'Individual Tagging', name:'individualtag', width:20, classes:'wrap', hidden:true},
					//method
					//residual value
					{ label: 'Start Date', name:'statdate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					{ label: 'Post Date', name:'trandate', width:20, classes:'wrap', formatter:dateFormatter, hidden:true},
					//accumprev
					//accumytd
					//ntv
					{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:true},
					{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},
								
				
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
					$("#jqGridPager td[title='View Selected Row']").click();
				},
				gridComplete: function(){
					/*if(editedRow!=0){
						$("#jqGrid").jqGrid('setSelection',editedRow,false);
					}*/
				},
				
			});

			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");
			}

			////////////////////////////////////////////////////////////////////////////////////////
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
				$("#description").prop('readonly',true);
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
				$("#description").prop('readonly',false);
				$("#purordno").prop('readonly',false);
				$("#purdate").prop('readonly',false);
				$("#purprice").prop('readonly',false);
				$("#origcost").prop('readonly',false);
				$("#currentcost").prop('readonly',false);
				$("#qty").prop('readonly',false);
			}

			$("#nbv").on('click',function(){
				var origcost = $("#origcost").val();
				var accprev = $("#accprev").val();
				var accytd = $("#accytd").val();

				total = origcost - accprev - accytd;

				$("#nbv").val(total.toFixed(2));

			});



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
					{ label: 'Description', name: 'desc', width: 400, classes: 'pointer', canSearch:true},
					{ label: 'holder1', name: 'holder1',  hidden:true},
					{ label: 'holder2', name: 'holder2',  hidden:true},
					{ label: 'holder3', name: 'holder3',  hidden:true},
					{ label: 'holder4', name: 'holder4',  hidden:true},
					{ label: 'holder5', name: 'holder5',  hidden:true},
					{ label: 'holder6', name: 'holder6',  hidden:true},
					{ label: 'holder7', name: 'holder7',  hidden:true},
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

					if(selText=="#assetcode"){
						assettype=data.holder1;
						method=data.holder2;
						residualvalue=data.holder3;
						$("#assettype").val(data.holder1);
						$("#method").val(data.holder2);
						$("#rvalue").val(data.holder3);
						$("#deptcode").focus();

					}else if(selText=="#delordno"){
						$('#invno').val(data.holder2);
						$('#delorddate').val(moment(data.holder3).format("YYYY-MM-DD"));
						$('#docno').val(data.holder4+' '+data.holder5);
						$('#purordno').val(data.holder4+' '+data.holder6);
						$('#recno').val(data.holder7);
						$('#purdate').val(moment().format('YYYY-MM-DD'));
						getinvdate(data.holder2);
					}else if(selText=="#itemcode"){

						$('#description').val(data.desc);
						$('#purprice').val(data.holder2);
						$('#origcost').val(data.holder2);
						$('#currentcost').val(data.holder2);
						$('#qty').val(data.holder1);
						//$('#accprev').val(data.holder3);
						//$('#accytd').val(data.holder4)
						//$('#nbv').val((('#origcost').val())-(('#accprev').val())-(('#accytd').val()));
					}
						
				},
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					paramD.filterCol=null;
					paramD.filterVal=null;
					paramD.join_type=null;
					paramD.join_onCol=null;
					paramD.join_onVal=null;

					switch(id){
						case '#delordno':
							paramD.field=['delordno','suppcode','totamount','invoiceno','deldate','prdept','docno','srcdocno','recno'];
							paramD.filterCol=['suppcode','recstatus'];
							paramD.filterVal=[$('#suppcode').val(),'POSTED'];

							$("#gridDialog").jqGrid('setLabel','holder3','DO. Date');
							$("#gridDialog").jqGrid('setLabel','desc','Supplier Code');
							$("#gridDialog").jqGrid('setLabel','holder1','Total amt.');
							$("#gridDialog").jqGrid('showCol',["holder1","holder3"]);

							break;
						case '#itemcode':
							if($("input[name=regtype]:checked").val() == "P"){
								paramD.table_id='itemcode'
								paramD.field=['product.itemcode','product.description','delorddt.qtydelivered','delorddt.amount','delorddt.idno'];
								paramD.table_name=['material.delorddt','material.product','material.delordhd','finance.facode'];
								paramD.join_type=['LEFT JOIN','LEFT JOIN','LEFT JOIN'];
								paramD.join_onCol=['delorddt.itemcode','delorddt.recno','product.productcat'];
								paramD.join_onVal=['product.itemcode','delordhd.recno','facode.assetcode'];
								paramD.filterCol=['delorddt.recno','facode.assetcode'];
								paramD.filterVal=[$('#recno').val(),$('#assetcode').val()];
							}else{
								paramD.table_id='itemcode';
								paramD.table_name='material.product';
								paramD.field=['itemcode','description'];
							}

								$("#gridDialog").jqGrid('setLabel','code','Code');
								$("#gridDialog").jqGrid('setLabel','desc','Description');
								$("#gridDialog").jqGrid('hideCol',["holder1","holder3"]);

							break;
						default:
							paramD.filterCol=null;
							paramD.filterVal=null;

							$("#gridDialog").jqGrid('setLabel','code','Code');
							$("#gridDialog").jqGrid('setLabel','desc','Description');
							$("#gridDialog").jqGrid('hideCol',["holder1","holder3"]);
							break;
					}

					$( "#dialog" ).dialog( "open" );
					$( "#dialog" ).dialog( "option", "title", title );
					
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
					if(id!='#itemcode'){
						self.check(errorField);
					}
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
	});
