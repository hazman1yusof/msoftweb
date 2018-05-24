
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
			$("body").show();

			/////////////////////////////////////////validation//////////////////////////
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

			/////////////////////////////////// currency ///////////////////////////////
			var mycurrency =new currencymode(['#amount']);

			////////////////////object for dialog handler//////////////////
			dialog_txndept=new makeDialog('sysdb.department','#txndept',['deptcode','description'], 'Transaction Department');
			dialog_trantype=new makeDialog('material.ivtxntype','#trantype',['trantype','description'], 'Transaction Type');
			dialog_sndrcv=new makeDialog('sysdb.department','#sndrcv',['deptcode','description'], 'Receiver');

			////////////////////////////////////start dialog//////////////////////////////////////
			var oper;
			var unsaved = false;

			$("#dialogForm")
			  .dialog({ 
				width: 9.5/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					switch(oper) {
						case state = 'add':
							mycurrency.formatOnBlur();
						 	//mycurrency2.formatOnBlurnono0();
							$( this ).dialog( "option", "title", "Add Inventory DataEntry" );
							$("#jqGrid2").jqGrid("clearGridData", true);
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm();
							$("#saveDetailLabel").show();
							enableForm('#formdata');
							rdonly('#formdata');
							hideOne('#formdata');
							$("#txndept").val($("#x").val().toUpperCase());
							break;
						case state = 'edit':
							mycurrency.formatOnBlur();
							//mycurrency2.formatOnBlurnono0();
							$( this ).dialog( "option", "title", "Edit Inventory DataEntry" );
							hideatdialogForm();
							$("#saveDetailLabel").show();
							$("#pg_jqGridPager2 table").show();
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly('#formdata');
							$('#formdata :input[hideOne]').show();
							getTrantype();
							$('#sndrcvtype').prop("disabled",false);
							break;
						case state = 'view':
							mycurrency.formatOnBlur();
							//mycurrency2.formatOnBlurnono0();
							$( this ).dialog( "option", "title", "View Inventory DataEntry" );
							disableForm('#formdata');
							$('#formdata :input[hideOne]').show();// -->DP X tambah lagi
							$("#pg_jqGridPager2 table").hide();
							getTrantype();
							$('#sndrcvtype').prop("disabled",true);
							break;
					}if(oper!='view'){
						dialog_txndept.handler(errorField);
						dialog_trantype.handler(errorField);
						dialog_sndrcv.handler(errorField);
					}if(oper!='add'){
						toggleFormData('#jqGrid','#formdata');
						dialog_txndept.check(errorField);
						dialog_trantype.check(errorField);
						dialog_sndrcv.check(errorField);
					}
					},
					beforeClose: function(event, ui){
						if(unsaved){
							event.preventDefault();
							bootbox.confirm("Are you sure want to leave without save?", function(result){
								if (result == true) {
									unsaved = false
									$("#dialogForm").dialog('close');
								}
							});
						}
						
					},
					close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					emptyFormdata(errorField,'#formdata2');
					$('.alert').detach();
					$("#formdata a").off();
					///$('#jqGrid2_ilcancel').click();
					$(".noti").empty();
					//refreshGrid("#jqGrid",urlParam);
					$("#refresh_jqGrid").click();
					//$('#sndrcvtype').selectpicker('refresh');
				},
			});
			////////////////////////////////////////end dialog///////////////////////////////////////////////////

			/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'material.ivtmphd',
				table_id:'recno',
				sort_idno:true,
				filterCol:['txndept'],
				filterVal:[$('#x').val()],
			}
			/////////////////////parameter for saving url///////////////////////////////////////////////////////
			var saveParam={
				action:'invTran_save',
				field:'',
				oper:oper,
				table_name:'material.ivtmphd',
				table_id:'recno',
				sysparam:{source:'IV',trantype:'IT',useOn:'recno'},
				sysparam2:{txndept:'', trantype:'', useOn:'docno'},
				returnVal:true,
			};

			/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [						
					{ label: 'Record No', name: 'recno', width: 20, classes: 'wrap', canSearch: true,selected:true},// checked:true},
					{ label: 'Transaction Department', name: 'txndept', width: 50, classes: 'wrap'},
					{ label: 'Transaction Type', name: 'trantype', width: 35, classes: 'wrap', canSearch: true},
					{ label: 'Document No', name: 'docno', width: 30, classes: 'wrap', canSearch: true},
					{ label: 'Transaction Date', name: 'trandate', width: 50, classes: 'wrap', canSearch: true,formatter: "date", formatter:dateFormatter, unformat:dateUNFormatter},
					{ label: 'Sender/Receiver', name: 'sndrcv', width: 50, classes: 'wrap', canSearch: true},
					{ label: 'SndRcvType', name: 'sndrcvtype', width: 50, classes: 'wrap'},
					{ label: 'Amount', name: 'amount', width: 30, classes: 'wrap', align: 'right', formatter:'currency'},
					{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap',},			
					{ label: 'Request RecNo', name: 'srcdocno', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'source', name: 'source', width: 40, hidden:'true'},
					/*{ label: 'computerid', name: 'computerid', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true, classes: 'wrap'},*/
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 200,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					recno=rowid;
					amount=jg.amount;
					//srcdocno=jg.srcdocno;
					trantype=jg.trantype;
					sndrcv=jg.sndrcv;
					
					trandate=jg.trandate;
					txndept=jg.txndept;
					recstatus=jg.recstatus;

					///////////////////recno for urlParam2
					//urlParam2.filterVal[0]=rowid;
					if(rowid != null) {
						getValue('front',selrowData("#jqGrid").recno);
					}

					if (recstatus=='Open'){
						$("#postedBut").show();
					}else{
						$("#postedBut").hide();
					}
				}, 
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){ // -->DP X tambah lagi
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
				
			});

			function dateFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("DD-MM-YYYY");
			}

			function dateUNFormatter(cellvalue, options, rowObject){
				return moment(cellvalue).format("YYYY-MM-DD");

			}

			////////////////////// set label jqGrid right ////////////////////////////////////////////////
			$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', {'text-align':'right'});

			//////////hide $("#postedBut").hide(); ///////////////
			$("#postedBut").hide();

			////////////////////////////sometodo//////////////////////////////////////////////////////////
			function sometodo(){
				$('#formdata  textarea').prop("readonly",true);
				$('#formdata :input[hideOne]').show();
				$('#formdata input').prop("readonly",true);
				$('#sndrcvtype').prop("disabled",true);
				$("input[id*='_recno']").val(recno);
				$("#formdata a").off();
				$("#jqGrid2_iledit").show();
			}

			////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
			function hideatdialogForm(){
				$("#jqGrid2_iledit").hide();
				$("#jqGrid2_iladd").hide();
				$("#saveHeaderLabel").hide();
				$("#jqGridPager2Delete").hide();
			}

			/////////////////////////////show add&edit  button at jqgrid2///////////////////////////////////////
			function showGrid2AddEdit(){
				$("#jqGrid2_iledit").show();
				$("#jqGrid2_iladd").show();
				$("#jqGridPager2Delete").show();
			}

			/////////////////////////////////dblclick///////////////////////////////////////////////////////////
			$('#dialog').on('dblclick',function(){
				//****************************** trantype 
				if(selText=='#trantype'){
					getTrantype();
					$("#sndrcvtype").val("Choose");
					//$('#sndrcvtype option').prop('selected',true);
				}
			});

			/////////////////////////////////trantype////////////////////////////////////////////////////////////

			//LI LIR LO LOR TR  --> Enable Receiver N Qty On Hand Receiver else hide
			function getTrantype() {
				var trantype = $('#trantype').val();

				if (trantype == 'LI') {
						rc();
					}else if (trantype == 'LIR') {
						rc();
					}else if (trantype == 'LO') {
						rc();
					}else if (trantype == 'LOR') {
						rc();
					}else if (trantype == 'TR') {
						tr();
					}else {
						$("#jqGrid2").jqGrid('hideCol', 'recvqtyonhand');
						$("label[for=sndrcv]").hide();
						$("#sndrcv_parent").hide();

						$("label[for=sndrcvtype]").hide();
						$("#sndrcvtype_parent").hide();

						$("#sndrcvtype option[value='Department']").hide();
						$("#sndrcvtype option[value='Supplier']").show();
						$("#sndrcvtype option[value='Other']").show();

						dialog_sndrcv=new makeDialog('material.supplier','#sndrcv',['SuppCode','Name'], 'Receiver');
						dialog_sndrcv.offHandler();
						dialog_sndrcv.handler(errorField);
					}
			}

			function rc(){
				$("#jqGrid2").jqGrid('showCol', 'recvqtyonhand');
				$("label[for=sndrcv]").show();
				$("#sndrcv_parent").show();

				$("label[for=sndrcvtype]").show();
				$("#sndrcvtype_parent").show();

				$("#sndrcvtype option[value='Department']").hide();
				$("#sndrcvtype option[value='Supplier']").show();
				$("#sndrcvtype option[value='Other']").show();

				dialog_sndrcv=new makeDialog('material.supplier','#sndrcv',['SuppCode','Name'], 'Receiver');
				dialog_sndrcv.offHandler();
				dialog_sndrcv.handler(errorField);
			}

			function tr(){
				$("#jqGrid2").jqGrid('showCol', 'recvqtyonhand');
				$("label[for=sndrcv]").show();
				$("#sndrcv_parent").show();

				$("label[for=sndrcvtype]").show();
				$("#sndrcvtype_parent").show();

				$("#sndrcvtype option[value='Department']").show();
				$("#sndrcvtype option[value='Supplier']").hide();
				$("#sndrcvtype option[value='Other']").hide();


				dialog_sndrcv=new makeDialog('sysdb.department','#sndrcv',['deptcode','description'], 'Receiver');
				dialog_sndrcv.offHandler();
				dialog_sndrcv.handler(errorField);
			}

			///////////////////////////////// trandate check date validate from period////////// ////////////////
			var actdateObj = new setactdate(["#trandate"]);
			actdateObj.getdata().set();
			function setactdate(target){
				this.actdateopen=[];
				this.lowestdate;
				this.highestdate;
				this.target=target;
				this.param={
					action:'get_value_default',
					field: ['*'],
					table_name:'sysdb.period',
					table_id:'idno'
				}

				this.getdata = function(){
					var self=this;
					$.get( "../../../../assets/php/entry.php?"+$.param(this.param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data.rows)){
							self.lowestdate = data.rows[0]["datefr1"];
							self.highestdate = data.rows[data.rows.length-1]["dateto12"];
							data.rows.forEach(function(element){
								$.each(element, function( index, value ) {
									if(index.match('periodstatus') && value == 'O'){
										self.actdateopen.push({
											from:element["datefr"+index.match(/\d+/)[0]],
											to:element["dateto"+index.match(/\d+/)[0]]
										})
									}
								});
							});
						}
					});
					return this;
				}

				this.set = function(){
					this.target.forEach(function(element){
						$(element).on('change',validate_actdate);
					});
				}

				function validate_actdate(obj){
					var permission = false;
					actdateObj.actdateopen.forEach(function(element){
					 	if(moment(obj.target.value).isBetween(element.from,element.to, null, '[]')) {
							permission=true
						}else{
							(permission)?permission=true:permission=false;
						}
					});
					if(!moment(obj.target.value).isBetween(actdateObj.lowestdate,actdateObj.highestdate)){
						bootbox.alert('Date not in accounting period setup');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					}else if(!permission){
						bootbox.alert('Accounting Period Has been Closed');
						$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
					} //Accounting Period Has been Closed
						//Date not in accounting period setup
					
				}
			}

			/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
			function saveHeader(form,oper,saveParam,obj){
				if(obj==null){
					obj={};
				}
				saveParam.oper=oper;

				$.each($( "input:text" ).filter('[data-sanitize-number-format]'), function( index, value ) {
					var newnum=numeral().unformat($(value).val());
					$(value).val(newnum);
				});

				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
				},'json').fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).success(function(data){

					if(oper=='add'){
						recno = data.recno;
						////docno = data.docno;
						//sndrcv = data.sndrcv;
						//trandate = data.trandate;
						//txndept = data.txndept;
						sometodo();
						$('#recno').val(recno);
						//$('#docno').val(docno);
					}else if(oper=='edit'){
						$("#formdata :input[name*='recno']").val(selrowData('#jqGrid').recno);
						sometodo();
						$('#recno').val(recno);
						///$('#docno').val(docno);
						$('#amount').val(amount);
					}
				});
			}
			
			$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea',  function(){
					unsaved = true;
			});

			
			/***************************************************************************************************/
			///////////////////utk dropdown search By/////////////////////////////////////////////////
			searchBy();
			function searchBy(){
			$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['selected']){
							$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
						}
						else{
						$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
						}
					}
				});
			}

			console.log($('#Scol option:selected').val()); ///get selected yg first
			$('#Scol').change(function(){
				console.log($('#Scol option:selected').val());
				
			});

			///////////////////////////////////utk dropdown Status on Change/////////////////////////////////
			console.log($('#Status option:selected').val());
			$('#Status').change(function(){
				console.log($('#Status option:selected').val());
				//trandept()
				switch($('#Status option:selected').val()){
					case "All":
						urlParam.filterCol = ['txndept'];
						urlParam.filterVal = [$('#trandept option:selected').val()];
						refreshGrid('#jqGrid',urlParam);
						break;
					default:
						urlParam.filterCol = ['recstatus', 'txndept'];
						urlParam.filterVal = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
						refreshGrid('#jqGrid',urlParam);
						break;
				}
			});

			///////////////////////////////////utk dropdown trandept on change/////////////////////////////////

			$('#trandept').change(function(){
				console.log($('#trandept option:selected').val());
				switch($('#Status option:selected').val()){
					case "All":
						urlParam.filterCol = ['txndept'];
						urlParam.filterVal = [$('#trandept option:selected').val()];
						refreshGrid('#jqGrid',urlParam)
						break;
					default:
						urlParam.filterCol = ['recstatus', 'txndept'];
						urlParam.filterVal = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
						refreshGrid('#jqGrid',urlParam);
						break;
				}
				/*urlParam.filterCol = ['recstatus', 'txndept'];
				urlParam.filterVal = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
				refreshGrid('#jqGrid',urlParam);*/
			});

			///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
			trandept()
			function trandept(){
				
				var param={
					action:'get_value_default',
					field:['deptcode'],
					table_name:'sysdb.department',
					filterCol:['storedept'],
					filterVal:['1']
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data)){
						$.each(data.rows, function(index, value ) {
							if(value.deptcode.toUpperCase()== $("#x").val().toUpperCase()){
								$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
							}else{
								$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
							}
							
						});

						//console.log($('#trandept option:selected').val());
					}
					//console.log($('#trandept option:selected').val());
				});
			}

			/***************************************************************************************************/

			/////////////////////////start grid pager/////////////////////////////////////////////////////////

			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam);
				},
			})/*.jqGrid('navButtonAdd',"#jqGridPager",{
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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'recno':selRowId});
					}
				},
			})*/.jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
					getValue('else',selrowData("#jqGrid").recno);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					getValue('else',selrowData("#jqGrid").recno);
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				id: 'glyphicon-plus',
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$("#formdata :input[name='source']").val("IV");
				},
			});

			//////////handle searching, its radio button and toggle /////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed///////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser','adddate','idno']);

			

			/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
			

			var urlParam2={
				action:'get_table_default',
				field:['compcode','recno','lineno_','itemcode','uomcode'],
				//field:['compcode','recno','lineno_','itemcode','uomcode', 'txnqty', 'netprice', 'amount', 'expdate', 'batchno'],
				table_name:'material.ivtmpdt',
				table_id:'lineno_',
				filterCol:['recno', 'compcode','recstatus'],
				filterVal:['', 'session.company','A']	
			}
			////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=invTranDetail_save",
				colModel: [
				 	{ label: 'compcode', name: 'ivt_compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:true, hidden:true},
					{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:true, hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 120, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							//formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:itemcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Uom Code', name: 'uomcode', width: 100, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:uomcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Item Description', name: 'description', width: 210, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
					{ label: 'Qty on Hand at Tran Dept', name: 'deptqtyonhand', width: 90, align: 'right', classes: 'wrap', editable:true,	
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
        			},
					{ label: 'Qty on Hand at Recv Dept', name: 'recvqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								},
							},
					},
					{ label: 'Max Qty', name: 'maxqty', width: 70, align: 'right', classes: 'wrap',  
						editable:true,
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Tran Qty', name: 'txnqty', width: 80, align: 'right', classes: 'wrap', 
							editable:true,
							formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Net Price', name: 'netprice', width: 90, align: 'right', classes: 'wrap', 
						editable:true,
						formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
							editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';  
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Amount', name: 'amount', width: 90, align: "right", classes: 'wrap', 
							editable:true, 
							formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';  
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Expiry Date', name: 'expdate', width: 100, classes: 'wrap', editable:true,
						formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}, 
						//editrules:{required: true},
						editoptions: {
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'expdate_datePicker',
                                    dateFormat: 'dd/mm/yy',
                                    minDate: 1,
                                    //maxDate: new Date(2020, 0, 1),
                                    showOn: 'focus',
                                    changeMonth: true,
				  					changeYear: true,
                                });
                            }
                        }
					},
					{ label: 'Batch No', name: 'batchno', width: 75, classes: 'wrap', editable:true,
							maxlength: 30,
					},				
					{ label: 'productcat', name: 'productcat', width: 30, classes: 'wrap', hidden:true},
					{ label: 'draccno', name: 'draccno', width: 30, classes: 'wrap', hidden:true},
					{ label: 'drccode', name: 'drccode', width: 30, classes: 'wrap', hidden:true},
					{ label: 'craccno', name: 'craccno', width: 30, classes: 'wrap', hidden:true},
					{ label: 'crccode', name: 'crccode', width: 30, classes: 'wrap', hidden:true},
					{ label: 'updtime', name: 'updtime', width: 30, classes: 'wrap', hidden:true},
					{ label: 'Remarks', name: 'remarks', width: 30, classes: 'wrap', hidden:true},
					
					
				],
				//autowidth:true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 1150,
				height: 200,
				shrinkToFit: false,
				rowNum: 30,
				sortname: 'lineno_',
        		sortorder: "desc",
				//rownumbers: true,
				pager: "#jqGridPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					//$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					$( "#jqGrid2_ilcancel" ).unbind();
					$( "#jqGrid2_ilcancel" ).on( "click", function(event) {
						event.preventDefault();
						event.stopPropagation();
						bootbox.confirm({
						    message: "Are you sure want to cancel detail?",
						    buttons: {
						        confirm: {
						            label: 'Yes',
						            className: 'btn-success'
						        },
						        cancel: {
						            label: 'No',
						            className: 'btn-danger'
						        }
						    },
						    callback: function (result) {
						        if (result == true) {
							        $(".noti").empty();
									urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
									refreshGrid("#jqGrid2",urlParam2);
								}
								$("#jqGridPager2Delete").show();
						    }
						});
					});
					////////////////getValue();
				},	
				onSelectRow:function(rowid, selected){
					$("#jqGrid2 input[name='netprice']").on('keydown',  function() { 
						delay(function(){
								var txnqty = parseInt($("input[id*='_txnqty']").val());
								var deptqtyonhand = parseInt($("input[id*='_deptqtyonhand']").val());

								getTrantypeDetailForTxnqty(txnqty,deptqtyonhand)

								var netprice = parseFloat($("input[id*='netprice']").val());
								var amount = txnqty * netprice;
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(4));
						}, 1000 );
					});
					
					$("#jqGrid2 input[name='txnqty']").on('keydown',  function() { 
						delay(function(){
								var txnqty = parseInt($("input[id*='_txnqty']").val());
								var deptqtyonhand = parseInt($("input[id*='_deptqtyonhand']").val());

								getTrantypeDetailForTxnqty(txnqty,deptqtyonhand)

								var netprice = parseFloat($("input[id*='netprice']").val());
								var amount = txnqty * netprice;
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(4));
						}, 1000 );
					});
				},
				afterShowForm: function (rowid) {
				    $("#expdate").datepicker();
				},beforeSubmit: function(postdata, rowid){ 
					dialog_itemcode.check(errorField);
					dialog_uomcode.check(errorField);
			 	},
			 	loadComplete: function(data) {
			 		getValue();
			 	},
			});

			////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
			$("#jqGrid2").jqGrid('setLabel', 'deptqtyonhand', 'Qty on Hand at Tran Dept', {'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel', 'recvqtyonhand', 'Qty on Hand at Recv Dept', {'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel', 'maxqty', 'Max Qty', {'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel', 'txnqty', 'Tran Qty', {'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel', 'netprice', 'Net Price', {'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel', 'amount', 'Amount', {'text-align':'right'});

			///////////////////////////////getvaluue= getdata for jgrid2/////////////////////////////////////////
			function getValue(at,recno) {
					    var currentyear = $("#getYear").val();
					    var itemcode = $("#itemcode").val();
					    var sndrcv = $("#sndrcv").val();


					    // itemcode = rowData.itemcode;
					    // uomcode = rowData.uomcode;
					    // recno=rowData.recno;

						urlParam2.field = ['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode', 'p.description', 's.qtyonhand',
							"(SELECT s.qtyonhand FROM material.ivtmpdt ivt LEFT JOIN material.stockloc s ON ivt.itemcode = s.itemcode AND ivt.uomcode=s.uomcode LEFT JOIN material.product p ON ivt.itemcode = p.itemcode WHERE  ivt.recno = '"+recno+"' AND s.deptcode = '"+sndrcv+"' AND s.year = '"+currentyear+"' AND ivt.compcode = '9A' AND ivt.recstatus = 'A') AS recvqtyonhand"
							,'s.maxqty','ivt.txnqty','ivt.netprice','ivt.amount','ivt.expdate','ivt.batchno'],
						urlParam2.table_name = ['material.ivtmpdt ivt', 'material.stockloc s', 'material.product p'];
					    urlParam2.table_id = 'lineno_';
					    urlParam2.join_type = ['LEFT JOIN','LEFT JOIN'];
					    urlParam2.join_onCol = ['ivt.itemcode', 'ivt.itemcode'];
					    urlParam2.join_onVal = ['s.itemcode','p.itemcode'];  
					    urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					    //urlParam2.join_filterVal = [['skip.s.uomcode', 'skip.txndept', '2017'],[]];
						urlParam2.join_filterVal = [['skip.s.uomcode',"skip.'"+txndept+"'","skip.'"+currentyear+"'"],[]];
					    urlParam2.filterCol = ['recno', 'ivt.compcode', 'ivt.recstatus'];
						urlParam2.filterVal = [recno, 'session.company','A'];

					if(at=='front'){
						refreshGrid("#jqGrid3",urlParam2);
					}else{
						refreshGrid("#jqGrid2",urlParam2);
					}
			};
			//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
			var g = false;

			var myEditOptions = {
		        keys: true,
		        oneditfunc: function (rowid) {
		        },
		        aftersavefunc: function (rowid, response, options) {
		           $('#amount').val(response.responseText);
		           $("#jqGridPager2Delete").show();
		        }, 
		        beforeSaveRow: function(options, rowid) {
		        	var retval = true;
		        	var txnqty = parseFloat($("input[id*='_txnqty']").val());
		        	var netprice = parseFloat($("input[id*='_netprice']").val());

		        	getCRNIT();

		        	switch(true){
		        		case crdbfl == 'In':
		        			if (txnqty == 0){
		        				bootbox.alert("Transaction Quantity Cannot Be Zero");
		        				$("#jqGrid2_ilsave").hide();
		        				return false;
		        			}
		        			break;
		        		case crdbfl == 'Out':
		        			if (netprice == 0){
		        				bootbox.alert("Net Price Cannot Be Zero");
		        				$("#jqGrid2_ilsave").hide();
		        				return false;
		        			}
		        			break;
		        		default:
		        			$("#jqGrid2_ilsave").show();
		        			break;
		        	}
		        	
				
		        },
		    };

		    ///////////////////// get crdbfl AND isstype ///////////////////////////////////////
		    function getCRNIT(){
				console.log(crdbfl) 
			}
			
		    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
			$("#jqGrid2").inlineNav('#jqGridPager2',{	
				add:true,
				edit:true,
				cancel: true,
				//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
				restoreAfterSelect: false,
				//saveAfterSelect: true,
				//del:true,   
				addParams: { 
        			//position: "afterSelected",
        			addRowParams: myEditOptions
   				},
   				//addedrow: "last",
   				editParams: myEditOptions
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "jqGridPager2Delete",
				caption:"",cursor: "pointer",position: "last", 
				buttonicon:"glyphicon glyphicon-trash",
				title:"Delete Selected Row",
				onClickButton: function(){
					
					selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
					if(!selRowId){
						bootbox.alert('Please select row');
					}else{
						bootbox.confirm({
						    message: "Are you sure you want to delete this row?",
						    buttons: {
						        confirm: {
						        	////callback true
						            label: 'Yes',
						            className: 'btn-success',
						        },
						        cancel: {
						        	////callback false
						            label: 'No',
						            className: 'btn-danger'
						        }
						    },
						    callback: function (result) {
						    	if(result == true){

						    		var detVa=$("#jqGrid2").jqGrid('getRowData',selRowId);
									recno=detVa.recno;
                        			lineno_=detVa.lineno_;
                        			itemcode=detVa.itemcode;

                        			$.ajax({
                        				type: 'POST',
                        				data:{oper:'del', recno, lineno_},
                        				//oper:'del',
                        				//async:false,
                        				url: '../../../../assets/php/entry.php?' + jQuery.param({
            								action: 'invTranDetail_save',
            								recno: detVa.recno,
											lineno_: detVa.lineno_,
            							}),
                        				//data:{recno:recno,lineno_:lineno_,}, 
		        						success: function (response) {
		        							urlParam2.filterVal[0]=recno;
		        							$('#amount').val(response);
		        							refreshGrid("#jqGrid2",urlParam2);
									        //bootbox.alert(response);
									    },
									    error: function () {
									        bootbox.alert("error");
									    }
			                        });

						    		
						    	}
						        //console.log('This was logged in the callback: ' + result);
						    }
						});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveHeaderLabel",
				caption:"Header",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Header"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveDetailLabel",
				caption:"Detail",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Detail"
			});

			//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
			$("#saveDetailLabel").click(function(){
				mycurrency.formatOff();
				unsaved = false;
				//mycurrency.check0value(errorField);
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveHeader("#formdata", oper,saveParam);
					unsaved = false;

					$("#saveHeaderLabel").show();
					$("#saveDetailLabel").hide();
					$("#jqGrid2_iladd").show();
					$("#jqGridPager2Delete").show();
					$("#jqGrid2_iladd").click();
				}else{
					mycurrency.formatOn();
				}
			});

			//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
			$("#saveHeaderLabel").click(function(){
				emptyFormdata(errorField,'#formdata2');
				//$("#formdata a").on();
				$("#formdata a").on( 'click', "",function() {
					/*dialog_txndept.updateField('sysdb.department','#txndept',['deptcode','description'], 'Transaction Department')
					dialog_txndept.offHandler();
					dialog_txndept.handler(errorField);*/
				  	dialog_txndept.handler(errorField);
					dialog_trantype.handler(errorField);
					dialog_sndrcv.handler(errorField);
				});
				//$('#jqGrid2_ilcancel').click();
				$("#jqGrid2_iladd").hide();
				$("#jqGrid2_iledit").hide();
				$("#saveHeaderLabel").hide();
				$("#jqGridPager2Delete").hide();
				$("#saveDetailLabel").show();
				$('#formdata  textarea').prop("readonly",false);
				$('#sndrcvtype').prop("disabled",false);
				$('#formdata input').prop("readonly",false);
				$('#formdata  input[type=radio]').prop("disabled",false);
				$('#formdata  input[rdonly]').prop("readonly",true);
				$('#formdata  input[frozeOnEdit]').prop("readonly",true);
				$(".noti").empty();
				$("#jqGrid2_ilsave").show();
				//$("#formdata").unbind('click');
				
			});

			//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
			function showdetail(cellvalue, options, rowObject){
				var field,table;
				switch(options.colModel.name){
					//case 'itemcode':field=['itemcode','description'];table="material.product";break;
					case 'uomcode':field=['uomcode','description'];table="material.uom";break;
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

			///////////////////////////////////////cust_rules//////////////////////////////////////////////
			function cust_rules(value,name){
				var temp;
				switch(name){
					case 'Item Code':temp=$('#itemcode');break;
					case 'Uom Code':temp=$('#uomcode');break;
				}
				return(temp.parent().hasClass("has-error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			/////////////////////////////////////////////custom input////////////////////////////////////////////
			function itemcodeCustomEdit(val,opt){  	
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
			}

			function uomcodeCustomEdit(val,opt){  	
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function galGridCustomValue (elem, operation, value){	

				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

			///////////////////////////////////////// QtyOnHand Dept ////////////////////////////////////////////
			function getQtyOnHandDept(){
				var trandate = $('#trandate').val();
				var txndept = $('#txndept').val();
				var datetrandate = new Date(trandate);
				var getyearinput = datetrandate.getFullYear();
				console.log(trandate);
				console.log(getyearinput);
				console.log($("#jqGrid2 input[name='itemcode']").val());
				console.log($('#txndept').val());

				var param={
					action:'get_value_default',
					field:['qtyonhand', 'maxqty'],
					table_name:'material.stockloc'
				}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode',];
				param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), txndept, $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					console.log($.isEmptyObject(data) == '');
					

					if(!$.isEmptyObject(data)){
						console.log(data);
						
						$("#jqGrid2 input[name='deptqtyonhand']").val(data.rows[0].qtyonhand);
						$("#jqGrid2 input[name='maxqty']").val(data.rows[0].maxqty);

						getTrantypeDetail(data.rows[0].qtyonhand);
					}else{
						
					}
				});
			}

			/////////////////////////////////////////get trantype detail/////////////////////////////////////////
			function getTrantypeDetail(qtyonhand){
				var trantype = $('#trantype').val();
				//console.log(trantype);

				var param={
					action:'get_value_default',
					field:['crdbfl','isstype'],
					table_name:'material.ivtxntype'
				}

				param.filterCol = ['trantype'];
				param.filterVal = [trantype];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
							crdbfl = data.rows[0].crdbfl;
							isstype = data.rows[0].isstype;
							/*$(crdbfl).val(crdbfl2);
							console.log(crdbfl2);
							return crdbfl2;*/
							console.log(crdbfl);
							console.log(isstype);

							getavgcost(crdbfl,isstype);
							getCRNIT(crdbfl,isstype);

							switch(true){
								case qtyonhand <= 0 && crdbfl == 'In':
										$(".noti").empty();
										$("#jqGrid2_ilsave").show();
								break;
								case qtyonhand <= 0 && crdbfl == 'Out':
										bootbox.alert("Quantity On Hand is less or equal zero");
										$(".noti").empty();
										$(".noti").append("<font color='red'><b>*Quantity On Hand is less or equal zero</b></font>");
										$("#jqGrid2_ilsave").hide();	
								break;
								default:
									$(".noti").empty();
									$("#jqGrid2_ilsave").show();
								break;
							}
					}else{

					}
				});
			}

			//////////////////////////////////get trantype detail For Txnqty////////////////////////////////////
			function getTrantypeDetailForTxnqty(txnqty,deptqtyonhand){
				var trantype = $('#trantype').val();
				//console.log(trantype);

				var param={
					action:'get_value_default',
					field:['crdbfl','isstype'],
					table_name:'material.ivtxntype'
				}

				param.filterCol = ['trantype'];
				param.filterVal = [trantype];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
							crdbfl = data.rows[0].crdbfl;
							isstype = data.rows[0].isstype;

							console.log(crdbfl);
							console.log(isstype);

							switch(true){
								case txnqty >= deptqtyonhand && crdbfl == 'In':
									if(txnqty >= deptqtyonhand){
										$(".noti").empty();
										$("#jqGrid2_ilsave").show();
									}
								break;
								case txnqty >= deptqtyonhand && crdbfl == 'Out':
									if(txnqty >= deptqtyonhand){
										bootbox.alert("Transaction Quantity Cannot be greater than Quantity On Hand");
										$(".noti").empty();
										$("#jqGrid2_ilsave").hide();
										$(".noti").append("<font color='red'><b>*Transaction Quantity Cannot be greater than Quantity On Hand</b></font>");
									}
								break;
								default:
									$(".noti").empty();
									$("#jqGrid2_ilsave").show();
								break;
							}
					}else{

					}
				});
			}

			///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
			function getQtyOnHandRecv(){
				var trandate = $('#trandate').val();
				var sndrcv = $('#sndrcv').val();
				var datetrandate = new Date(trandate);
				var getyearinput = datetrandate.getFullYear();
				console.log(getyearinput);
				

				var param={
					action:'get_value_default',
					field:['qtyonhand'],
					table_name:'material.stockloc'
				}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
				param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), sndrcv, $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						//if(data.rows[0].qtyonhand != ''){
							$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
						//}
						//$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
					}else{
						bootbox.alert('Data Not Found At Stock Lock');
						$('#jqGrid2_ilcancel').click();
					}
				});
			}

			//////////////////////////////////////// avgcost ////////////////////////////////////////
			function getavgcost(crdbfl,isstype){

				var param={
					action:'get_value_default',
					field:['avgcost','expdtflg'],
					table_name:'material.product'
				}

				param.filterCol = ['itemcode', 'uomcode',];
				param.filterVal = [ $("#jqGrid2 input[name='itemcode']").val(), $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						console.log(data);
						$("#jqGrid2 input[name='netprice']").val(parseFloat(data.rows[0].avgcost).toFixed(4));
						console.log(data.rows[0].expdtflg)
						var expdtflg = data.rows[0].expdtflg;
						if (expdtflg == '1' && crdbfl == 'In' && isstype == 'Others') {
								$("#jqGrid2").jqGrid('setColProp', 'expdate', {editrules: {required: true}});
								$("#jqGrid2").jqGrid('setColProp', 'batchno', {editrules: {required: true}});
						}

						if (expdtflg == '0' && crdbfl == 'In' && isstype == 'Others') {
								$("#jqGrid2").jqGrid('setColProp', 'expdate', {editrules: {required: false}});
								$("#jqGrid2").jqGrid('setColProp', 'batchno', {editrules: {required: false}});
						}
					}else{
						
					}
				});
			}


			//////////////////////////////////////// jqGrid2_iladd ////////////////////////////////////////

			$("#jqGrid2_iladd").click(function(){
				unsaved = false;
				$("#jqGridPager2Delete").hide();
				$("#jqGrid2_ilsave").show();
				console.log(recno);
				console.log(sndrcv);
				
				$("input[id*='_recno']").val(recno);
				$("input[id*='_recno']").attr('readonly','readonly');
				$("input[id*='_lineno_']").val($("#lineno_").val());
				$("input[id*='description']").attr('readonly','readonly');

				dialog_itemcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='itemcode']",['itemcode'], 'Item Code');
				dialog_uomcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='uomcode']",['uomcode'], 'Uom Code');

				dialog_itemcode.handler(errorField);
				dialog_uomcode.handler(errorField);


				$("input[id*='_deptqtyonhand']").on('click',  function() { 
					console.log('asdasdsd1');
					getQOHValue();
			    });

			    $("input[id*='_batchno']").keydown(function(e) {
					//console.log('keydown called');
						var code = e.keyCode || e.which;
							if (code == '9') { // -->for tab
								//if(checkDtlb4Save()){
									$('#jqGrid2_ilsave').click();
									//refreshGrid("#jqGrid2",urlParam2);
									delay(function(){
										$('#jqGrid2_iladd').click();
									}, 1500 );
								//}
						}
				});
			});

			/*function checkDtlb4Save(){
				var txnqty = parseInt($("input[id*='_txnqty']").val());
				var netprice = parseFloat($("input[id*='_netprice']").val());
				var amount = parseFloat($("input[id*='_amount']").val());
				var retval = true;

				//getCRNIT();

				switch(true){
					case amount == '0' && crdbfl == 'In':
						if(txnqty == '0' && netprice == 0){
							retval=false;
							bootbox.confirm({
							    message: "Transaction Quantity And Net Price Cannot Be Zero. Do you want to proceed?",
							    buttons: {
							        confirm: {
							            label: 'Yes',
							            className: 'btn-success'
							        },
							        cancel: {
							            label: 'No',
							            className: 'btn-danger'
							        }
							    },
							    callback: function (result) {
							    	if(result){
							    		$('#jqGrid2_ilsave').click();
										delay(function(){
											$('#jqGrid2_iladd').click();
										}, 1500 );
							    	}
							    }
							});
						}
						///
						if(txnqty == '0') {
							retval=false;
							bootbox.confirm({
							    message: "Transaction Quantity Cannot Be Zero. Do you want to proceed?",
							    buttons: {
							        confirm: {
							            label: 'Yes',
							            className: 'btn-success'
							        },
							        cancel: {
							            label: 'No',
							            className: 'btn-danger'
							        }
							    },
							    callback: function (result) {
							    	if(result){
							    		$('#jqGrid2_ilsave').click();
										delay(function(){
											$('#jqGrid2_iladd').click();
										}, 1500 );
							    	}
							    }
							});
						} 
						if(netprice == 0){
							retval=false;
							bootbox.confirm({
							    message: "Net Price Cannot Be Zero. Do you want to proceed?",
							    buttons: {
							        confirm: {
							            label: 'Yes',
							            className: 'btn-success'
							        },
							        cancel: {
							            label: 'No',
							            className: 'btn-danger'
							        }
							    },
							    callback: function (result) {
							    	if(result){
							    		$('#jqGrid2_ilsave').click();
										delay(function(){
											$('#jqGrid2_iladd').click();
										}, 1500 );
							    	}
							    }
							});
						}
						
						break;
					case amount == '0' && crdbfl == 'Out':
						if(txnqty == '0' && netprice == 0){
							retval=false;
							bootbox.alert("Transaction Quantity And Net Price Cannot Be Zero");
							$("#jqGrid2_ilsave").hide();
						}
						if(txnqty == '0') {
							retval=false;
							bootbox.alert("Transaction Quantity Cannot Be Zero");
							$("#jqGrid2_ilsave").hide();
						} 
						if(netprice == 0){
							retval=false;
							bootbox.alert("Net Price Cannot Be Zero");
							$("#jqGrid2_ilsave").hide();
						}
						break;/////
					default:
						retval=true;
						break;
				}
			}*/


			/////////////////////////////////function db click QNH ////////////////////////////////
			function getQOHValue(){
				var trantype = $('#trantype').val();
					switch(trantype){
						case 'LI':
							includeRecv();
						break;
						case 'LIR':
							includeRecv();
						break;
						case 'LO':
							includeRecv();
						break;
						case 'LOR':
							includeRecv();
						break;
						case 'TR':
							includeRecv();
						break;
						default:
							excludeRecv();
							break;
					}

					function includeRecv(){
						getQtyOnHandDept();
						getQtyOnHandRecv();
						getavgcost();
					}

					function excludeRecv(){
						getQtyOnHandDept();
						getavgcost();
					}
				
			}

			////////////////////////////////////jqGrid2_iledit on click /////////////////////////////////////////

			$("#jqGrid2_iledit").click(function(){
				unsaved = false;
				$("#jqGridPager2Delete").hide();
				dialog_itemcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='itemcode']",['itemcode'], 'Item Code');
				dialog_uomcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='uomcode']",['uomcode'], 'Uom Code');

				dialog_itemcode.handler(errorField);
				dialog_uomcode.handler(errorField);

				$("input[id*='_deptqtyonhand']").on('click',  function() { 
					console.log("edit");
					getQOHValue();
			    });

			    $("#jqGrid2 input[name='txnqty']").on('keydown',  function() { 
					delay(function(){
						var txnqty = parseInt($("input[id*='_txnqty']").val());
						var deptqtyonhand = parseInt($("input[id*='_deptqtyonhand']").val());

						getTrantypeDetailForTxnqty(txnqty,deptqtyonhand)

						var netprice = parseFloat($("input[id*='netprice']").val());
						var amount = txnqty * netprice;
						$("#jqGrid2 input[name='amount']").val(amount.toFixed(4));
					}, 1000 );
				});

				$("#jqGrid2 input[name='netprice']").on('keydown',  function() { 
					delay(function(){
						var txnqty = parseInt($("input[id*='_txnqty']").val());
						var deptqtyonhand = parseInt($("input[id*='_deptqtyonhand']").val());

						getTrantypeDetailForTxnqty(txnqty,deptqtyonhand)

						var netprice = parseFloat($("input[id*='netprice']").val());
						var amount = txnqty * netprice;
						$("#jqGrid2 input[name='amount']").val(amount.toFixed(4));
					}, 1000 );
				});
			});

			////////////////////////////////////jqGrid2_ilsave on clcik //////////////////////////////////////
			$("#jqGrid2_ilsave").click(function(){
				unsaved = false;

				/*var txnqty = parseInt($("input[id*='_txnqty']").val());
				if(txnqty = '0'){
					$("#jqGrid2_ilsave").hide();
					alert("0");
				}*/
			});
			////////////////////////////////////jqGrid2_ilcancel on clcik //////////////////////////////////////
			$("#jqGrid2_ilcancel").click(function(){
				unsaved = true;
				$(".noti").empty();
				//$("#jqGrid2_ilsave").show();
				/*confirm("Press a button!");
				if (result == true) {
					return false;
				}*
				confirm("Are you sure want to cancel?", function(){
					
				});*/
			});

			////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
			var urlParam3={
				action:'get_table_default',
				field:['compcode','recno','lineno_','itemcode','uomcode'],
				//field:['compcode','recno','lineno_','itemcode','uomcode', 'txnqty', 'netprice', 'amount', 'expdate', 'batchno'],
				table_name:'material.ivtmpdt',
				table_id:'lineno_',
				filterCol:['recno', 'compcode','recstatus'],
				filterVal:['', 'session.company','A'],	
			}

			$("#jqGrid3").jqGrid({
				datatype: "local",
				colModel: [
				 	{ label: 'compcode', name: 'ivt_compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', hidden:true},
					{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 100, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							//formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:itemcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Uom Code', name: 'uomcode', width: 100, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:uomcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Item Description', name: 'description', width: 200, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
					{ label: 'Qty on Hand at Tran Dept', name: 'deptqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,	
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
        			},
					{ label: 'Qty on Hand at Recv Dept', name: 'recvqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								},
							},
					},
					{ label: 'Max Qty', name: 'maxqty', width: 100, align: 'right', classes: 'wrap',  
						editable:true,
						formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Tran Qty', name: 'txnqty', width: 100, align: 'right', classes: 'wrap', 
							editable:true,
							formatter:'integer', formatoptions:{thousandsSeparator: ",",},
							editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Net Price', name: 'netprice', width: 100, align: 'right', classes: 'wrap', 
						editable:true,
						formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
							editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';  
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Amount', name: 'amount', width: 100, align: "right", classes: 'wrap', 
							editable:true, 
							formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
							editrules:{required: true},edittype:"text",
								editoptions:{
								readonly: "readonly",
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';  
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
										//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Expiry Date', name: 'expdate', width: 120, classes: 'wrap', editable:true,
						formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}, 
						//editrules:{required: true},
						editoptions: {
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'expdate_datePicker',
                                    dateFormat: 'dd/mm/yy',
                                    minDate: 1,
                                    //maxDate: new Date(2020, 0, 1),
                                    showOn: 'focus',
                                    changeMonth: true,
				  					changeYear: true,
                                });
                            }
                        }
					},
					{ label: 'Batch No', name: 'batchno', width: 90, classes: 'wrap', editable:true,
							maxlength: 30,
					},				
					{ label: 'productcat', name: 'productcat', width: 30, classes: 'wrap', hidden:true},
					{ label: 'draccno', name: 'draccno', width: 30, classes: 'wrap', hidden:true},
					{ label: 'drccode', name: 'drccode', width: 30, classes: 'wrap', hidden:true},
					{ label: 'craccno', name: 'craccno', width: 30, classes: 'wrap', hidden:true},
					{ label: 'crccode', name: 'crccode', width: 30, classes: 'wrap', hidden:true},
					{ label: 'updtime', name: 'updtime', width: 30, classes: 'wrap', hidden:true},
					{ label: 'Remarks', name: 'remarks', width: 30, classes: 'wrap', hidden:true},
					
					
				],
				//autowidth:true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 1200,
				height: 200,
				shrinkToFit: true,
				rowNum: 30,
				sortname: 'lineno_',
        		sortorder: "desc",
				//rownumbers: true,
				pager: "#jqGridPager3",
				ondblClickRow: function(rowid, iRow, iCol, e){
					//$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					
				},	
				onSelectRow:function(rowid, selected){
					
				},
				afterShowForm: function (rowid) {
				    $("#expdate").datepicker();
				},
				beforeSubmit: function(postdata, rowid){ 
			 	},
			 	loadComplete: function(data) {
			 		//getValue2();
			 	},
			});

			///////////////////////////////getvaluue= getdata for jgrid3/////////////////////////////////////////
			/*function getValue2() {
			        var ids = $("#jqGrid3").jqGrid('getDataIDs');
					for (var i = 0; i < ids.length; i++) {
					    var rowId = ids[i];
					    var rowData = $("#jqGrid3").jqGrid ('getRowData', rowId);
					    console.log(rowId);

					    var currentyear = $("#getYear").val();
					    var itemcode = $("#itemcode").val();
					    var sndrcv = $("#sndrcv").val();

					    itemcode = rowData.itemcode;
					    uomcode = rowData.uomcode;
					    recno=rowData.recno;


					   urlParam3.field = ['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode', 'p.description', 's.qtyonhand',
							"(SELECT s.qtyonhand FROM material.ivtmpdt ivt LEFT JOIN material.stockloc s ON ivt.itemcode = s.itemcode AND ivt.uomcode=s.uomcode LEFT JOIN material.product p ON ivt.itemcode = p.itemcode WHERE  ivt.recno = '"+recno+"' AND s.deptcode = '"+sndrcv+"' AND s.year = '"+currentyear+"' AND ivt.compcode = '9A' AND ivt.recstatus = 'A') AS recvqtyonhand"
							,'s.maxqty','ivt.txnqty','ivt.netprice','ivt.amount','ivt.expdate','ivt.batchno'],
						urlParam3.table_name = ['material.ivtmpdt ivt', 'material.stockloc s', 'material.product p'];
					    urlParam3.table_id = 'lineno_';
					    urlParam3.join_type = ['LEFT JOIN','LEFT JOIN'];
					    urlParam3.join_onCol = ['ivt.itemcode', 'ivt.itemcode'];
					    urlParam3.join_onVal = ['s.itemcode','p.itemcode'];  
					    urlParam3.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					    //urlParam2.join_filterVal = [['skip.s.uomcode', 'skip.txndept', '2017'],[]];
						urlParam3.join_filterVal = [['skip.s.uomcode',"skip.'"+txndept+"'","skip.'"+currentyear+"'"],[]];
					    urlParam3.filterCol = ['ivt.recno', 'ivt.compcode', 'ivt.recstatus'];
						urlParam3.filterVal = ['', 'session.company','A'];
					}
			};*/

			////////////////////////////////////////////postedBut//////////////////////////////////////
			$("#postedBut").click(function(){
				var param={
						action:'invTranPost_save',
						oper:'add',
						field:'',
						table_name:'material.ivtxnhd',
						table_id:'recno',
						returnVal:true,
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
						}
					).fail(function(data) {
						bootbox.alert('Error: '+data.responseText);
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
					});
			});

			///////////////////////////////start->dialogHandler part////////////////////////////////////////////
			function makeDialog(table,id,cols,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.title=title;
				this.handler=dialogHandler;
				this.check=checkInput;
				this.offHandler=function(){
					$( this.id+" ~ a" ).off();
				}
				this.check2=checkInput2;
				this.check3=checkInput3;
				this.updateField=function(table,id,cols,title){
					this.table=table;
					this.id=id;
					this.cols=cols;
					this.title=title;
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

			var selText,Dtable,Dcols,fromdblclick=false;
			$("#gridDialog").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Code', name: 'code', width: 200,  classes: 'wrap', canSearch:true,checked:true}, 
					{ label: 'Description', name: 'description', width: 400, canSearch:true, classes: 'wrap'},
					{ label: 'holder1', name: 'holder1', classes: 'wrap', hidden:true},
					{ label: 'holder2', name: 'holder2', classes: 'wrap', hidden:true},
				],
				width: 500,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				autowidth: true,
				shrinkToFit: true,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					$(selText).val(data.code);
					$(selText).focus();
					$(selText).parent().next().html(data['description']);
					
					if(selText=="#jqGrid2 input[name='itemcode']"){
						fromdblclick = true;
						itemcode=data.itemcode;
						description=data.description;
						holder1=data.holder1;
						$("#jqGrid2 input[name='description']").val(description);
						//$("#jqGrid2 input[name='uomcode']").focus();
						$("#jqGrid2 input[name='uomcode']").val(holder1);
						$("input[id*='_deptqtyonhand']").focus();
						$("#jqGrid2 input[name='deptqtyonhand']").click();
					}

					if(selText=="#jqGrid2 input[name='uomcode']"){
						fromdblclick = true;
						$("input[id*='_deptqtyonhand']").focus();
						$("#jqGrid2 input[name='deptqtyonhand']").click();
					}
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(errorField){
				var table=this.table,id=this.id,cols=this.cols,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
					//$( "#dialog" ).dialog( "open" );
					//$( "#dialog" ).dialog( "option", "title", title );

					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];
					paramD.join_type=null;
					paramD.join_onCol=null;
					paramD.join_onVal=null;
					paramD.filterCol=null;
					paramD.filterVal=null;
					paramD.groupby=null;
					paramD.filterInCol = null;
					paramD.filterInType = null;
					paramD.filterInVal = null;

					$("#gridDialog").jqGrid('hideCol',["holder1"]);

					switch(id){
						case '#txndept':
							paramD.filterCol=['storedept', 'recstatus'];
							paramD.filterVal=['1', 'A'];
							break;
						case "#jqGrid2 input[name='uomcode']":
							var trandate = $('#trandate').val();
							var datetrandate = new Date(trandate);
							console.log(trandate);
							var getyearinput = datetrandate.getFullYear();
							var itemcode = $("#jqGrid2 input[name='itemcode']").val();
							var txndept = $('#txndept').val();

							paramD.table_name=['material.stockloc','material.uom'];
							paramD.field=['stockloc.uomcode' , 'uom.description'];
							paramD.join_type=['LEFT JOIN'];
							paramD.join_onCol=['stockloc.uomcode'];
							paramD.join_onVal=['uom.uomcode'];
							paramD.filterCol=['stockloc.compcode','stockloc.itemcode', 'stockloc.year', 'stockloc.deptcode'];
							paramD.filterVal=['session.company', itemcode, getyearinput, txndept];
							//paramD.groupby='stockloc.uomcode';

							$("#gridDialog").jqGrid('setLabel','code','UOM Code');	
							break;
						case "#jqGrid2 input[name='itemcode']":
							var trandate = $('#trandate').val();	
							var datetrandate = new Date(trandate);
							var getyearinput = datetrandate.getFullYear();
							var txndept = $('#txndept').val();	

							paramD.table_name=['material.stockloc', 'material.product'];//, 'material.uom'
							paramD.field=['stockloc.itemcode', 'product.description', 'stockloc.uomcode'];//, 'uom.description'
							paramD.join_type=['JOIN'];//, 'JOIN'
							paramD.join_onCol=['stockloc.itemcode'];//, 'stockloc.uomcode'
							paramD.join_onVal=['product.itemcode'];//, 'uom.uomcode'
							paramD.filterCol=['stockloc.compcode','stockloc.year', 'stockloc.deptcode'];
							paramD.table_id = 'none_';
							paramD.filterVal=['session.company',getyearinput, txndept];

							$("#gridDialog").jqGrid('setLabel','code','Item Code');
							$("#gridDialog").jqGrid('setLabel', 'holder1', 'Uom Code');
							$("#gridDialog").jqGrid('showCol',["holder1"]);
							break; 
						case "#sndrcv":
							var trantype = $('#trantype').val();
							var txndept = $('#txndept').val();
							console.log(txndept);
							if (trantype == 'TR') {
								paramD.filterCol=['storedept', 'recstatus'];
								paramD.filterVal=['1', 'A'];
								paramD.filterInCol=['deptcode'];
								paramD.filterInType=['NOT IN'];
								paramD.filterInVal=[[txndept]];
							}else {
								paramD.filterCol=['recstatus'];
								paramD.filterVal=['A'];
							}
							break;
						case "#trantype":
							paramD.filterInCol=['trantype'];
							paramD.filterInType=['NOT IN'];
							paramD.filterInVal=[['DS1', 'DS']];
							paramD.filterCol=['recstatus'];
							paramD.filterVal=['A'];
							break;
						default:
							paramD.filterCol=['recstatus'];
							paramD.filterVal=['A'];

							$("#gridDialog").jqGrid('setLabel','code','Code');
							$("#gridDialog").jqGrid('setLabel','desc','Description');
							// $("#gridDialog").jqGrid('hideCol',["holder1","holder3","holder2","holder5"]);
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
				$(id).on("blur", function(event){
					if(id=="#jqGrid2 input[name='itemcode']"){
						if(fromdblclick){
							fromdblclick = false;
							return false;
						}
						self.check2(errorField);
					}else if(id=="#jqGrid2 input[name='uomcode']"){
						if(fromdblclick){
							fromdblclick = false;
							return false;
						}
						self.check3(errorField);
					}else{
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
						$( id ).parent().siblings( ".help-block" ).show();
					}else if(data.msg=='fail'){
						
						if((id == '#sndrcv') && ($('#sndrcv').val()== "")) {
								$( id ).parent().removeClass( "has-success" ).removeClass( "has-error" );
								$( id ).removeClass( "valid" ).removeClass( "error" );
								$( id ).parent().siblings( ".help-block" ).hide();
						}else{
							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							$( id ).parent().siblings( ".help-block" ).html("Invalid Code ( "+field[0]+" )");
							if($.inArray(id,errorField)===-1){
								errorField.push(id);
							}
						}
					}
				});
			}

			/*function checkInput2(errorField){

				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'get_value_default'};
				var trandate = $('#trandate').val();	
				var datetrandate = new Date(trandate);
				var getyearinput = datetrandate.getFullYear();
				var txndept = $('#txndept').val();	

				param.table_name=['material.stockloc', 'material.product', 'material.uom'];
				param.field=['stockloc.itemcode', 'product.description as desc1', 'stockloc.uomcode', 'uom.description as desc2'];
				param.join_type=['JOIN', 'JOIN'];
				param.join_onCol=['stockloc.itemcode', 'stockloc.uomcode'];
				param.join_onVal=['product.itemcode', 'uom.uomcode'];
				param.filterCol=['stockloc.compcode','stockloc.year', 'stockloc.deptcode','stockloc.itemcode'];
				param.table_id = 'none_';
				param.filterVal=['session.company',getyearinput, txndept, value];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						let description=data.rows[0].desc1;
						let uom=data.rows[0].uomcode;
						$("#jqGrid2 input[name='description']").val(description);
						$("#jqGrid2 input[name='uomcode']").val(uom);
						$("input[id*='_deptqtyonhand']").focus();
						$("#jqGrid2 input[name='deptqtyonhand']").click();

						if($.inArray(id,errorField)!==-1){
							errorField.splice($.inArray(id,errorField), 1);
						}
						$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
						$( id ).removeClass( "error" ).addClass( "valid" );
						$( id ).parent().siblings( ".help-block" ).show();
					}else{
						$("#jqGrid2 input[name='description']").val('');
						$("#jqGrid2 input[name='uomcode']").val('');

						$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
						$( id ).removeClass( "valid" ).addClass( "error" );
						if($.inArray(id,errorField)===-1){
							errorField.push(id);
						}
					}
				});
			}*/

			function checkInput2(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'get_value_default'};
				var trandate = $('#trandate').val();	
				var datetrandate = new Date(trandate);
				var getyearinput = datetrandate.getFullYear();
				var txndept = $('#txndept').val();	

				param.table_name=['material.stockloc', 'material.product', 'material.uom'];
				param.field=['stockloc.itemcode', 'product.description as description', 'stockloc.uomcode', 'uom.description as desc2', 'stockloc.deptcode', 'stockloc.year'];
				param.join_type=['JOIN', 'JOIN'];
				param.join_onCol=['stockloc.itemcode', 'stockloc.uomcode'];
				param.join_onVal=['product.itemcode', 'uom.uomcode'];
				param.filterCol=['stockloc.compcode','stockloc.year', 'stockloc.deptcode','stockloc.itemcode'];
				param.table_id = 'none_';
				param.filterVal=['session.company',getyearinput, txndept, value];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						let description=data.rows[0].description;
						let deptcode = data.rows[0].deptcode;
						let year = data.rows[0].year;
						if((deptcode.equalToIgnoreCase==txndept.equalToIgnoreCase) && (year==getyearinput)){
							dialog_uomcode.handler(errorField);
							$("#jqGrid2 input[name='description']").val(description);
							$("#jqGrid2_ilsave").show();
							$("#jqGrid2 input[name='uomcode']").prop('disabled', false);
							$("#jqGrid2 input[name='txnqty']").prop('disabled', false);
							$("#jqGrid2 input[name='netprice']").prop('disabled', false);
							$("#jqGrid2 input[name='expdate']").prop('disabled', false);
							$("#jqGrid2 input[name='batchno']").prop('disabled', false);

							if($.inArray(id,errorField)!==-1){
								errorField.splice($.inArray(id,errorField), 1);
								}
								$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
								$( id ).removeClass( "error" ).addClass( "valid" );
								$( id ).parent().siblings( ".help-block" ).show();
							}
						}else{
							dialog_uomcode.offHandler();
							$("#jqGrid2 input[name='description']").val('');
							$("#jqGrid2_ilsave").hide();
							$("#jqGrid2 input[name='uomcode']").prop('disabled', true);
							$("#jqGrid2 input[name='txnqty']").prop('disabled', true);
							$("#jqGrid2 input[name='netprice']").prop('disabled', true);
							$("#jqGrid2 input[name='expdate']").prop('disabled', true);
							$("#jqGrid2 input[name='batchno']").prop('disabled', true);

							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							if($.inArray(id,errorField)===-1){
								errorField.push(id);
							}
						}
					});
			}

			function checkInput3(errorField){
				var table=this.table,id=this.id,field=this.cols,value=$( this.id ).val()
				var param={action:'get_value_default'};

				var trandate = $('#trandate').val();
				var uomcode = $('#uomcode').val();
				var datetrandate = new Date(trandate);
				var getyearinput = datetrandate.getFullYear();
				var itemcode = $("#jqGrid2 input[name='itemcode']").val();
				var txndept = $('#txndept').val();	

				param.table_name=['material.stockloc', 'material.uom'];
				param.field=['stockloc.uomcode', 'uom.description', 'stockloc.deptcode', 'stockloc.year','stockloc.itemcode'];
				param.join_type=['JOIN'];
				param.join_onCol=['stockloc.uomcode'];
				param.join_onVal=['uom.uomcode'];
				param.filterCol=['stockloc.compcode','stockloc.year', 'stockloc.deptcode','stockloc.itemcode','stockloc.uomcode'];
				param.table_id = 'none_';
				param.filterVal=['session.company',getyearinput, txndept, itemcode,value];
				//paramD.groupby='stockloc.uomcode';

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						let itemcode=data.rows[0].itemcode;
						let description=data.rows[0].description;
						let uomcode1=data.rows[0].uomcode;
						let deptcode = data.rows[0].deptcode;
						let year = data.rows[0].year;
						if((deptcode.equalToIgnoreCase==txndept.equalToIgnoreCase) && (year==getyearinput) && (uomcode==uomcode1)){
							$("#jqGrid2_ilsave").show();
							$("#jqGrid2 input[name='txnqty']").prop('disabled', false);
							$("#jqGrid2 input[name='netprice']").prop('disabled', false);
							$("#jqGrid2 input[name='expdate']").prop('disabled', false);
							$("#jqGrid2 input[name='batchno']").prop('disabled', false);
							$("#jqGrid2 input[name='deptqtyonhand']").click();

							if($.inArray(id,errorField)!==-1){
								errorField.splice($.inArray(id,errorField), 1);
								}
								$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
								$( id ).removeClass( "error" ).addClass( "valid" );
								$( id ).parent().siblings( ".help-block" ).html(description);
								$( id ).parent().siblings( ".help-block" ).show();
							}
						}else{
							$("#jqGrid2_ilsave").hide();
							$("#jqGrid2 input[name='txnqty']").prop('disabled', true);
							$("#jqGrid2 input[name='netprice']").prop('disabled', true);
							$("#jqGrid2 input[name='expdate']").prop('disabled', true);
							$("#jqGrid2 input[name='batchno']").prop('disabled', true);

							$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
							$( id ).removeClass( "valid" ).addClass( "error" );
							if($.inArray(id,errorField)===-1){
								errorField.push(id);
							}
						}
					});
			}

			$('#searchText').keyup(function() {
				delay(function(){
					Dsearch($('#searchText').val(),$('#Scol').val());
				}, 500 );
			});

			$('#Scol').change(function(){
				Dsearch($('#searchText').val(),$('#Scol').val());
			});

			function Dsearch(Stext,Scol){
				$('#searchText').prop('disabled',false);

					urlParam.searchCol=null;
					urlParam.searchVal=null;
					if(Stext.trim() != ''){
						var split = Stext.split(" "),searchCol=[],searchVal=[];
						$.each(split, function( index, value ) {
							searchCol.push(Scol);
							searchVal.push('%'+value+'%');
						});
						urlParam.searchCol=searchCol;
						urlParam.searchVal=searchVal;
					}
             refreshGrid('#jqGrid',urlParam);
			}	
			
			$('#Dtext').keyup(function() {
				delay(function(){
					Dsearch2($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
				}, 500 );
			});
			
			$('#Dcol').change(function(){
				Dsearch2($('#Dtext').val(),$('#checkForm input:radio[name=dcolr]:checked').val());
			});
			
			function Dsearch2(Dtext,Dcol){
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
			///////////////////////////////finish->dialogHandler///part/////////////////////////////////////////

		});