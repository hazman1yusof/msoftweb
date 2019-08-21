
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

			$('.sajanktry').click(function(){
				if($(this).css('width') == '400px'){
					$('.sajanktry div').hide();
					$('.sajanktry').velocity("reverse");
				}else{
					$('.sajanktry').velocity({
						width:"400px",
						height:"120px",
					},{
						display: "block",
						duration: 400,
						easing: "swing",
						complete: function(){$('.sajanktry div').show();},
					});
				}
			});

			/////////////////////////////////// currency ///////////////////////////////
			var mycurrency =new currencymode(['#amount']);

			////////////////////object for dialog handler//////////////////
			dialog_reqdept=new makeDialog('sysdb.department','#reqdept',['deptcode','description'], 'Request Department');
			dialog_reqtodept=new makeDialog('sysdb.department','#reqtodept',['deptcode','description'], 'Request Made To.');

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
					mycurrency.formatOnBlur();
					switch(oper) {
						case state = 'add':
							$("#jqGrid2").jqGrid("clearGridData", true);
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm(true);
							enableForm('#formdata');
							rdonly('#formdata');
							break;
						case state = 'edit':
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm(true);
							enableForm('#formdata');
							rdonly('#formdata');
							break;
						case state = 'view':
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							break;
					}if(oper!='view'){
						dialog_reqdept.handler(errorField);
						dialog_reqtodept.handler(errorField);
					}if(oper!='add'){
						dialog_reqdept.check(errorField);
						dialog_reqtodept.check(errorField);
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
					$(".noti").empty();
					$("#refresh_jqGrid").click();
				},
			});
			////////////////////////////////////////end dialog///////////////////////////////////////////////////

			/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'material.ivreqhd',
				table_id:'idno',
				sort_idno:true,
				filterCol:[],
				filterVal:[],
			}
			/////////////////////parameter for saving url///////////////////////////////////////////////////////
			var saveParam={
				action:'purReq_header_save',
				field:'',
				oper:oper,
				table_name:'material.ivreqhd',
				table_id:'recno',
				returnVal:true,
			};

			/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Request No', name: 'recno', width: 10, canSearch: true,selected:true},
					{ label: 'Request Department', name: 'reqdept', width: 30, canSearch: true},
					{ label: 'RecordNo', name: 'ivreqno', width: 10, canSearch: true},
					{ label: 'Request To Department', name: 'reqtodept', width: 30, classes: 'wrap'},
					{ label: 'Request Date', name: 'reqdt', width: 20, canSearch: true, formatter: "date", formatter:dateFormatter, unformat:dateUNFormatter},
					{ label: 'Amount', name: 'amount', width: 20, align: 'right', formatter:'currency'},
					{ label: 'remarks', name: 'remarks', width: 50, classes: 'wrap'},
					{ label: 'recstatus', name: 'recstatus', width: 20},
					{ label: 'Request Type', name: 'reqtype', width: 50,hidden:'true'},
					{ label: 'authpersonid', name: 'authpersonid', width: 90, hidden:true},
					{ label: 'authdate', name: 'authdate', width: 40, hidden:'true'},
					{ label: 'reqpersonid', name: 'reqpersonid', width: 50, hidden:'true'},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
					{ label: 'idno', name: 'idno', width: 90, hidden:true},
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

				},
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

			////////////////////// set label jqGrid right ////////////////////////////////////////////////
			$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', {'text-align':'right'});

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
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
					refreshGrid("#jqGrid2",urlParam2);
					getValue();
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

			//////////add field into param, refresh grid if needed///////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser','adddate','idno']);

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
			function hideatdialogForm(hide){
				if(hide){
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").hide();
					$("#saveDetailLabel").show();
				}else{
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").show();
					$("#saveDetailLabel").hide();
				}
			}

			/////////////////////////////////dblclick///////////////////////////////////////////////////////////
			// $('#dialog').on('dblclick',function(){
			// 	//****************************** trantype 
			// 	if(selText=='#trantype'){
			// 		getTrantype();
			// 		$("#sndrcvtype").val("Choose");
			// 		//$('#sndrcvtype option').prop('selected',true);
			// 	}
			// });

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

			/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
			function saveHeader(form,selfoper,saveParam,obj){
				if(obj==null){
					obj={};
				}
				saveParam.oper=selfoper;

				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
				},'json').fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).success(function(data){
					if(selfoper=='add'){
						oper='edit';//sekali dia add terus jadi edit lepas tu
						$('#recno').val(data.recno);
						$('#ivreqno').val(data.ivreqno);
						$('#idno').val(data.idno);//just save idno for edit later
					}else if(selfoper=='edit'){
						// $("#formdata :input[name*='recno']").val(selrowData('#jqGrid').recno);
						// $('#recno').val(recno);
						// ///$('#srcdocno').val(srcdocno);
						// $('#amount').val(amount);
					}
					disableForm('#dialogForm');
					hideatdialogForm(false);
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
					searchClick2('#jqGrid','#searchForm',urlParam);
				});
			}
			///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
			trandept(urlParam)
			function trandept(urlParam){
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
						urlParam.filterCol = [];
						urlParam.filterVal = [];

						$('#Status').on('change',function(){
							if($(this).val()=='ALL'){
								urlParam.filterCol = ['recstatus','txndept'];
								urlParam.filterVal = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
							}else{

							}
							refreshGrid('#jqGrid',urlParam);
						});

						$('#trandept').on('change',function(){
							urlParam.filterCol = ['recstatus', 'txndept'];
							urlParam.filterVal = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
							refreshGrid('#jqGrid',urlParam);
						});
					}
				});
			}

			/***************************************************************************************************/

			/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
			

			var urlParam2={
				action:'get_table_default',
				field:['compcode','recno','lineno_','itemcode','uomcode'],
				//field:['compcode','recno','lineno_','itemcode','uomcode', 'txnqty', 'netprice', 'amount', 'expdate', 'batchno'],
				table_name:'material.ivtmpdt',
				table_id:'lineno_',
				filterCol:['recno', 'compcode','recstatus'],
				filterVal:['', 'session.company','A'],
				/*field:['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode','p.description', 's.qtyonhand', "(SELECT qtyonhand FROM material.stockloc WHERE itemcode = '101000051' AND deptcode = 'IT') as recvqtyonhand", 's.maxqty', 'ivt.txnqty', 'ivt.netprice', 'ivt.amount', 'ivt.expdate', 'ivt.batchno'],
				table_name:['material.ivtmpdt ivt', 'material.product p', 'material.stockloc s'],
				table_name:['material.ivtmpdt ivt', 'material.product p', 'material.stockloc s'],
				table_id:'lineno_',
				join_type:['LEFT JOIN','LEFT JOIN'],
				join_onCol:['recno', 'compcode' , 'ivt.itemcode', 'ivt.itemcode', 's.year'],
				join_onVal:['', 'session.company','p.itemcode', 's.itemcode', '2017'],*/	
			}
			////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=invTranDetail_save",
				colModel: [
				 	{ label: 'compcode', name: 'ivt_compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:true, hidden:true},
					{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:true, hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 130, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							//formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:itemcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Uom Code', name: 'uomcode', width: 110, classes: 'wrap', editable:true,
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
					{ label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',  
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
					/*{ label: 'Action', name: 'action', width : 100,  formatter: "actions", editable:false,
											formatoptions: {
											    keys: true,
											    editbutton: false,
											    delbutton: true,
											    delOptions: {
											    	mtype: 'POST',
											    	onclickSubmit: function (options, rowid) {
											    			var detVa=$("#jqGrid2").jqGrid('getRowData',rowid);
											    			recno=detVa.recno;
                        									lineno_=detVa.lineno_;
                        									itemcode = detVa.itemcode;
													        options.url = '../../../../assets/php/entry.php?' + jQuery.param({
													            action: 'invTranDetail_save',
													            recno: detVa.recno,
													            lineno_: detVa.lineno_,
													        });
													},
													afterSubmit: function (response, postdata) {
														return $('#amount').val(response.responseText);
													},
											    },
											},
					},*/				
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
						    message: "Are you sure want to cancel?",
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
						/*bootbox.confirm("Are you sure want to cancel?", function(result){
							if (result == true) {
								urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
								refreshGrid("#jqGrid2",urlParam2);
							}
							$("#jqGridPager2Delete").show();
						});*/
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
			function getValue() {
			        var ids = $("#jqGrid2").jqGrid('getDataIDs');
					for (var i = 0; i < ids.length; i++) {
					    var rowId = ids[i];
					    var rowData = $("#jqGrid2").jqGrid ('getRowData', rowId);
					    console.log(rowId);

					    var currentyear = $("#getYear").val();
					    var itemcode = $("#itemcode").val();
					    var sndrcv = $("#sndrcv").val();


					    itemcode = rowData.itemcode;
					    uomcode = rowData.uomcode;
					    recno=rowData.recno;

					   /*urlParam2.field = ['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode','p.description', 's.qtyonhand', "(SELECT qtyonhand FROM material.stockloc WHERE itemcode = '"+itemcode+"' AND deptcode = '"+sndrcv+"') as recvqtyonhand", 's.maxqty', 'ivt.txnqty', 'ivt.netprice', 'ivt.amount', 'ivt.expdate', 'ivt.batchno'];
					    urlParam2.table_name = ['material.ivtmpdt ivt', 'material.product p', 'material.stockloc s'];
					    urlParam2.table_id = 'lineno_';
					    urlParam2.join_type = ['LEFT JOIN','LEFT JOIN'];
					    urlParam2.join_onCol = ['ivt.itemcode', 'ivt.itemcode'];
					    urlParam2.join_onVal = ['p.itemcode', 's.itemcode'];
					    urlParam2.filterCol = ['ivt.recno', 'ivt.compcode', 'ivt.itemcode', 'ivt.recstatus', 's.deptcode', 's.uomcode', 's.year'];
						urlParam2.filterVal = ['', 'session.company',itemcode,'A',txndept,uomcode, currentyear];*/

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
					    urlParam2.filterCol = ['ivt.recno', 'ivt.compcode', 'ivt.recstatus'];
						urlParam2.filterVal = ['', 'session.company','A'];
						//
						/*
						SELECT ivt.compcode,ivt.recno,ivt.lineno_,ivt.itemcode,ivt.uomcode
						,p.description
						,s.qtyonhand
						,(SELECT s.qtyonhand
						FROM material.ivtmpdt ivt
						LEFT JOIN material.stockloc s ON ivt.itemcode = s.itemcode AND ivt.uomcode=s.uomcode 
						LEFT JOIN material.product p ON ivt.itemcode = p.itemcode 
						WHERE  ivt.recno = '40' AND s.deptcode = '/*sndrcv' AND s.year = '2017' AND ivt.compcode = '9A' AND ivt.recstatus = 'A') AS recvqtyonhand
						,s.maxqty
						,ivt.txnqty,ivt.netprice,ivt.amount,ivt.expdate,ivt.batchno 
						FROM material.ivtmpdt ivt
						LEFT JOIN material.stockloc s ON ivt.itemcode = s.itemcode AND ivt.uomcode=s.uomcode AND s.deptcode = 'IT' AND s.year = '2017'
						LEFT JOIN material.product p ON ivt.itemcode = p.itemcode 
						WHERE  ivt.recno = '40' AND ivt.compcode = '9A' 
						AND ivt.recstatus = 'A' 
						ORDER BY lineno_ DESC 
						*/
					}
			};
			//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
			var myEditOptions = {
		        keys: true,
		        oneditfunc: function (rowid) {
		        },
		        aftersavefunc: function (rowid, response, options) {
		           $('#amount').val(response.responseText);
		           $("#jqGridPager2Delete").show();
		        }, 
		        beforeSaveRow: function(options, rowid) { 

		        	console.log(parseFloat($("input[id*='_amount']").val()));
					var txnqty = parseInt($("input[id*='_txnqty']").val());
					var netprice = parseFloat($("input[id*='_netprice']").val());
					var amount = parseFloat($("input[id*='_amount']").val());
					
					if(amount == '0') {
						if((txnqty == '0') && (netprice == 0)){
							bootbox.alert("Transaction Quantity And Net Price Cannot Be Zero")
							return false;
						}
						if(txnqty == '0') {
							bootbox.alert("Transaction Quantity Cannot Be Zero")
							return false;
						} 
						if(netprice == 0){
							bootbox.alert("Net Price Cannot Be Zero")
							return false;
						}
					}

		        },
		    };

		    /*var delOptions = {
		    	mtype: 'POST',
				onclickSubmit: function (options, rowid) {
					var detVa=$("#jqGrid2").jqGrid('getRowData',rowid);
					recno=detVa.recno;
                    lineno_=detVa.lineno_;
					options.url = '../../../../assets/php/entry.php?' + jQuery.param({
						action: 'invTranDetail_save',
						recno: detVa.recno,
						lineno_: detVa.lineno_,
					});
				},
				afterSubmit: function (response, postdata) {
					return $('#amount').val(response.responseText);
				},
		    };*/

		    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
			$("#jqGrid2").inlineNav('#jqGridPager2',{	
				add:true,
				edit:true,
				cancel: true,
				//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
				restoreAfterSelect: false,
				addParams: { 
        			addRowParams: myEditOptions
   				},
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
						    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
						    },
						    callback: function (result) {
						    	if(result == true){
						    		var detVa=$("#jqGrid2").jqGrid('getRowData',selRowId);
									recno=detVa.recno;
                        			lineno_=detVa.lineno_;

                        			$.ajax({
                        				type: 'POST',
                        				oper:'del',
                        				url: '../../../../assets/php/entry.php?' + $.param({
            								action: 'invTranDetail_save',
            								recno: recno,
											lineno_: detVa.lineno_,
            							}),
		        						success: function (response) {
									        alert(response);
									    },
									    error: function () {
									        alert("error");
									    }
			                        });
						    	}
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
				mycurrency.check0value(errorField);
				unsaved = false;
				if($('#formdata').isValid({requiredFields:''},conf,true)){
					saveHeader("#formdata",oper,saveParam);
					unsaved = false;
					hideatdialogForm(false);
					//$("#jqGrid2_iladd").click();
				}else{
					mycurrency.formatOn();
				}
			});

			//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
			$("#saveHeaderLabel").click(function(){
				emptyFormdata(errorField,'#formdata2');
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
				//return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
						//var text = getTrantypeDetail(crdbfl,isstype);
						//console.log(text);
						/*if (data.rows[0].qtyonhand <= 0){
							alert("data <= 0");
						}else {
							alert('data > 0');
						}*/
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

							if (qtyonhand <= 0 && crdbfl == 'Out' && isstype == 'Others'){
								bootbox.alert("Quantity On Hand is less or equal zero");
								$(".noti").empty();
								$(".noti").append("<font color='red'><b>*Quantity On Hand is less or equal zero</b></font>");
								$("#jqGrid2_ilsave").hide();
								//$(".noti").append("<font color='red'><b>*Can not do "+trantype+"</b></font>");
							}else {
								//alert('data > 0');
								$(".noti").empty();
								$("#jqGrid2_ilsave").show();
							}

							/*if (crdbfl == 'Out' && isstype == 'Others') {
								alert("yes");
							}else {
								alert("no");
							}*/
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
							
							if(txnqty >= deptqtyonhand && crdbfl == 'Out' && isstype == 'Others'){
								//alert("txnqty > deptqtyonhand");  Tran Qty
								bootbox.alert("*Transaction Quantity Cannot be greater than Quantity On Hand");
								$(".noti").empty();
								$("#jqGrid2_ilsave").hide();
								$(".noti").append("<font color='red'><b>*Transaction Quantity Cannot be greater than Quantity On Hand</b></font>");
							}else{
								$(".noti").empty();
								$("#jqGrid2_ilsave").show();
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
			function getavgcost(){

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
						//inputRequiredForDateNBatchno(expdtflg);
						//$("#jqGrid2 input[name='netprice']").val(parseFloat(data.rows[0].avgcost));
					}else{
						
					}
				});
			}

			//////////////////////////////////////// jqGrid2_iladd ////////////////////////////////////////

			$("#jqGrid2_iladd").click(function(){
				unsaved = false;
				$("#jqGridPager2Delete").hide();
				console.log(recno);
				console.log(sndrcv);
				
				$("input[id*='_recno']").val(recno);
				$("input[id*='_recno']").attr('readonly','readonly');
				$("input[id*='_lineno_']").val($("#lineno_").val());
				$("input[id*='description']").attr('readonly','readonly');

				//dialog_itemcode=new makeDialog('material.product',"#jqGrid2 input[name='itemcode']",['itemcode','description'], 'Item Code','Description');
				dialog_itemcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='itemcode']",['itemcode'], 'Item Code');
				dialog_uomcode=new makeDialog('material.stockloc',"#jqGrid2 input[name='uomcode']",['uomcode'], 'Uom Code');
				//dialog_uomcode=new makeDialog('material.uom',"#jqGrid2 input[name='uomcode']",['uomcode','description'], 'Uom Code','Description');

				dialog_itemcode.handler(errorField);
				//dialog_itemcode.check(errorField);
				dialog_uomcode.handler(errorField);
				//dialog_uomcode.check(errorField);


				$("input[id*='_deptqtyonhand']").on('click',  function() { 
					console.log('asdasdsd1');
					getQOHValue();
			    });

			    $("input[id*='_batchno']").keydown(function(e) {
					//console.log('keydown called');
						var code = e.keyCode || e.which;
							if (code == '9') { // -->for tab
								$('#jqGrid2_ilsave').click();
								//refreshGrid("#jqGrid2",urlParam2);
								delay(function(){
									$('#jqGrid2_iladd').click();
								}, 1500 );
						}
				});


			});

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
				},beforeSubmit: function(postdata, rowid){ 
			 	},
			 	loadComplete: function(data) {
			 		getValue2();
			 	},
			});

			///////////////////////////////getvaluue= getdata for jgrid3/////////////////////////////////////////
			function getValue2() {
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

					   urlParam2.field = ['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode','p.description', 's.qtyonhand', "(SELECT qtyonhand FROM material.stockloc WHERE itemcode = '"+itemcode+"' AND deptcode = '"+sndrcv+"') as recvqtyonhand", 's.maxqty', 'ivt.txnqty', 'ivt.netprice', 'ivt.amount', 'ivt.expdate', 'ivt.batchno'];
					    urlParam2.table_name = ['material.ivtmpdt ivt', 'material.product p', 'material.stockloc s'];
					    urlParam2.table_id = 'lineno_';
					    urlParam2.join_type = ['LEFT JOIN','LEFT JOIN'];
					    urlParam2.join_onCol = ['ivt.itemcode', 'ivt.itemcode'];
					    urlParam2.join_onVal = ['p.itemcode', 's.itemcode'];
					    urlParam2.filterCol = ['ivt.recno', 'ivt.compcode', 'ivt.itemcode', 'ivt.recstatus', 's.deptcode', 's.uomcode', 's.year'];
						urlParam2.filterVal = ['', 'session.company',itemcode,'A',txndept,uomcode, currentyear];
					}
			};

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

					if(selText=="#reqtodept"){
						

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
						case '#reqtodept':
							paramD.filterCol=['storedept'];
							paramD.filterVal=['1'];
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
			///////////////////////////////finish->dialogHandler///part/////////////////////////////////////////

		});