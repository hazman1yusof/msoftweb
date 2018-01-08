
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
		var editedRow=0;

		$(document).ready(function () {
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

			////////////////////object for dialog handler//////////////////
			dialog_bankcode=new makeDialog('finance.bank','#bankcode',['bankcode','bankname'], 'Bank Code','Bank Name');
			//dialog_payto=new makeDialog('material.supplier','#payto',['SuppCode','Name'], 'Pay To','Description');

			var mycurrency =new currencymode(['#amount']);

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
							$( this ).dialog( "option", "title", "Add" );
							$("#jqGrid2").jqGrid("clearGridData", true);
							$("#jqGrid2").jqGrid('hideCol', 'action');
							$("#jqGrid2_iledit").hide();
							$("#jqGrid2_iladd").hide();
							$("#saveHeaderLabel").show();
							$("#saveDetailLabel").hide();
							$("#pg_jqGridPager2 table").show();
							enableForm('#formdata');
							rdonly('#formdata');
							hideOne('#formdata');
							break;

						case state = 'edit':
						mycurrency.formatOn();
							$( this ).dialog( "option", "title", "Edit" );
							$("#jqGrid2").jqGrid('hideCol', 'action');
							$("#jqGrid2_iledit").hide();
							$("#jqGrid2_iladd").hide();
							$("#saveHeaderLabel").show();
							$("#saveDetailLabel").hide();
							$("#pg_jqGridPager2 table").show();
							enableForm('#formdata');
							frozeOnEdit("#dialogForm");
							rdonly('#formdata');
							$('#formdata :input[hideOne]').show();
							break;

						case state = 'view':
						mycurrency.formatOn();
							$( this ).dialog( "option", "title", "View" );
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#jqGrid2").jqGrid('hideCol', 'action');
							break;
					}
					if(oper!='view'){
						dialog_bankcode.handler(errorField);
						
					}
					if(oper!='add'){
						dialog_bankcode.check(errorField);
					}
					if(oper =='edit'){
						if(recstatus == 'Posted') {
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#formdata a").off();
						}

						else if(recstatus == 'Cancel') {
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							$("#formdata a").off();
						}
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
					// $('#jqGrid2_ilcancel').click();
					$("#refresh_jqGrid").click();
					
				},
			  });

			  
			var actdateObj = new setactdate(["#actdate"]);
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

			///format currency///

			function currencymode(arraycurrency){
				this.array = arraycurrency;
				this.formatOn = function(){
					$.each(this.array, function( index, value ) {
						$(value).val(numeral($(value).val()).format('0,0.00'));
					});
				}
				this.formatOnBlur = function(){
					$.each(this.array, function( index, value ) {
						currencyBlur(value);
					});
				}
				this.formatOff = function(){
					$.each(this.array, function( index, value ) {
						$(value).val(currencyRealval(value));
					});
				}

				this.check0value = function(errorField){
					$.each(this.array, function( index, value ) {
						if($(value).val()=='0' || $(value).val()=='0.00'){
							$(value).val('');
						}
					});
				}

				function currencyBlur(v){
					$(v).on( "blur", function(){
						$(v).val(numeral($(v).val()).format('0,0.00'));
					});
				}

				function currencyRealval(v){
					return numeral().unformat($(v).val());
				}
			}

			/////////////////////


			////////////////////////////////////////end dialog///////////////////////////////////////////

			/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
				var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'finance.apacthdr',
				table_id:'idno',
				sort_idno:true,
				filterCol:['trantype'],
				filterVal:['CA']
				}
				// filterInCol:['trantype'],
				// filterInType:['IN'],
				// filterInVal:[['CA','DA']]

			/////////////////////parameter for saving url////////////////////////////////////////////////
				var saveParam={
				action:'cdHeaderSave',
				field:'',
				oper:oper,
				table_name:'finance.apacthdr',
				table_id:'idno',
				sysparam:{source:'CM',trantype:'CA',useOn:'auditno'},
				sysparam2:{source:'HIS',trantype:'PV',useOn:'pvno'},
				skipduplicate:true,
				returnVal:true,
				
			};

			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
				 	//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
					{ label: 'Audit No', name: 'auditno', width: 16, classes: 'wrap', canSearch: true, checked: true},
					{ label: 'TT', name: 'trantype', width: 10},
					{ label: 'Bank Code', name: 'bankcode', width: 35, classes: 'wrap', canSearch: true},
					
					{ label: 'Reference', name: 'refsource', width: 43, classes: 'wrap',},
					{ label: 'Post Date', name: 'actdate', width: 25, classes: 'wrap'},
					{ label: 'Amount', name: 'amount', width: 28, classes: 'wrap', formatter:'currency'} ,//unformat:unformat2}
					{ label: 'Remarks', name: 'remarks', width: 43, classes: 'wrap',},
					{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap',formatter:formatter},
					{ label: 'Entered By', name: 'adduser', width: 20, classes: 'wrap'},
					{ label: 'Entered Date', name: 'adddate', width: 40, classes: 'wrap'},
					{ label: 'GST', name: 'TaxClaimable', width: 40},
					{ label: 'Pv No', name: 'pvno', width: 40, hidden:true},
					{ label: 'source', name: 'source', width: 40, hidden:true},
				 	{ label: 'idno', name: 'idno', width: 40, hidden:'true'},
				 	{ label: 'upduser', name: 'upduser', width: 35, classes: 'wrap', hidden:true},
					{ label: 'upddate', name: 'upddate', width: 40, classes: 'wrap', hidden:true},
					
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					var jg=$("#jqGrid").jqGrid('getRowData',rowid);
					auditno=jg.auditno;
					pvno=jg.pvno;
					amount=jg.amount;
					recstatus=jg.recstatus;

					
					urlParam2.filterVal[0]=jg.auditno;
					
					//////////////// hide/show button
					if (recstatus=='Open'){
						$("#postedBut").show();
						$("#cancelBut").show();
						$("#jqGridplus2").show();
					}

					else if (recstatus==='Posted'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#jqGridplus2").hide();
						
					} 

					else if (recstatus==='Cancel'){
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#jqGridplus2").hide();
						
					} 


					else {
						$("#postedBut").hide();
						$("#cancelBut").hide();
						$("#jqGridplus2").show();
					}
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					//refreshGrid('#jqGrid',urlParam);
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					adj = $("#adjustment option:selected" ).val();

					if (adj == "CA" && adj == "DA") {
						$("#jqGridplus").hide();
					} else {
						$("#jqGridplus").show();
					}

					if(oper == 'edit'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

					adj = $("#adjustment option:selected" ).val();

					if (adj == "CA" && adj == "DA") {
						$("#jqGridplus2").hide();
					} else {
						$("#jqGridplus2").show();
					}

				},

				
			});
			
			/////////////////////////formatter & unformat/////////////////////////////////////////////////////////
			function formatter(cellvalue, options, rowObject){
				if(cellvalue == 'O'){
					return "Open";
				}else if(cellvalue == 'P') { 
					return "Posted";
				}else if (cellvalue == 'C'){
					return "Cancel";
				}
			}

			///check radio button///
			function checkradiobutton(radiobuttons){
				this.radiobuttons=radiobuttons;
				this.check = function(){
					$.each(this.radiobuttons, function( index, value ) {
						var checked = $("input[name="+value+"]:checked").val();
					    if(!checked){
					     	$("label[for="+value+"]").css('color', 'red');
					     	$(":radio[name='"+value+"']").parent('label').css('color', 'red');
						}else{
							$("label[for="+value+"]").css('color', '#444444');
							$(":radio[name='"+value+"']").parent('label').css('color', '#444444');
						}
					});
				}
			}

			var radbuts=new checkradiobutton(['TaxClaimable']);

			function textcolourradio(textcolour){
				this.textcolour=textcolour;
				this.check = function(){
					$.each(this.textcolour, function( index, value ) {
						$("label[for="+value+"]").css('color', '#444444');
						$(":radio[name="+value+"]").parent('label').css('color', '#444444');
					});
				}
			}

			var textCol=new textcolourradio(['TaxClaimable']);
			////////////////////////


			$('#adjustment').on('change', function() {
				adjustment1  = $("#adjustment option:selected" ).val();
				$("#jqGridplus").hide();
				urlParam.filterCol = ['trantype'];
				urlParam.filterVal = [adjustment1];
				saveParam.sysparam.trantype = adjustment1;
				refreshGrid('#jqGrid',urlParam);
			})

			/////////////////////////////// for Button /////////////////////////////////////////////////////////
			var adtNo
			function sometodo(){
				$("#jqGrid2_iledit").show();
				$("#jqGrid2").jqGrid('showCol', 'action');
				$('#formdata  textarea').prop("readonly",true);
				$('#formdata :input[hideOne]').show();
				$('#formdata input').prop("readonly",true);
				$('#formdata  input[type=radio]').prop("disabled",true);
				$("input[id*='_auditno']").val(auditno);
				$("#formdata a").off();
			}

			function saveHeader(form,oper,saveParam,obj){//saveonly
				if(obj==null){
					obj={};
				}
				saveParam.oper=oper;
				if(oper=='add')saveParam.field.splice(saveParam.field.indexOf('idno'),1);

				$.each($( "input:text" ).filter('[data-sanitize-number-format]'), function( index, value ) {
					var newnum=numeral().unformat($(value).val());
					$(value).val(newnum);
				});

				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
				},'json').fail(function(data) {
					errorText(dialog,data.responseText);
				}).success(function(data){
					
					if(oper=='add'){
						
						auditno = data.auditno;
						pvno = data.pvno;
						sometodo();
						$('#auditno').val(auditno);
						$('#pvno').val(pvno);
					}else if(oper=='edit'){
						$("#formdata :input[name*='auditno']").val(selrowData('#jqGrid').auditno);
						sometodo();
						$('#auditno').val(auditno);
						$('#pvno').val(pvno);
						$('#amount').val(amount);
					}
				});
			}

			$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea',  function(){
					unsaved = true;
			});

			$("#postedBut").hide();
			$("#cancelBut").hide();

			$("#postedBut").click(function(){
				var param={
						action:'cdreg_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'CM',trantype:'CA',useOn:'auditno'}
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
						$("#cancelBut").hide();
					});
			});

			$("#cancelBut").click(function(){
					var param={
						action:'cancel_save',
						oper:'add',
						field:'',
						table_name:'finance.cbtran',
						table_id:'auditno',
						skipduplicate: true,
						returnVal:true,
						sysparam:{source:'PB',trantype:'TN',useOn:'auditno'}
					};

					$.post( "../../../../assets/php/entry.php?"+$.param(param),
						{seldata:selrowData("#jqGrid")}, 
						function( data ) {
							
						}
					).fail(function(data) {
						bootbox.alert('error');
					}).success(function(data){
						refreshGrid("#jqGrid",urlParam);
						$("#postedBut").hide();
						$("#cancelBut").hide();

					});
				});

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

					urlParam2.filterVal[3]=$("#adjustment option:selected" ).val();
					$('#recstatus').text("Status: "+selrowData("#jqGrid").recstatus);
					refreshGrid("#jqGrid2",urlParam2);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",id:"jqGridplus2",position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');

					urlParam2.filterVal[3]=$("#adjustment option:selected" ).val();
					$('#recstatus').text("Status: "+selrowData("#jqGrid").recstatus);
					refreshGrid("#jqGrid2",urlParam2);
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",id:"jqGridplus",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					adjustment1  = $("#adjustment option:selected" ).val();
					$('#recstatus').text("");
					if(adjustment1 == 'CA') {
						$( "#dialogForm" ).dialog( "option", "title", "Credit Transaction" );
						$("#formdata :input[name='source']").val("CM");
						$("#formdata :input[name='trantype']").val("CA");
					}else if(adjustment1 == 'DA') {
						$( "#dialogForm" ).dialog( "option", "title", "Debit Transaction" );
						$("#formdata :input[name='source']").val("CM");
					    $("#formdata :input[name='trantype']").val("DA");
					}
					
				},
			});

			function formatstatus(status){
				switch(status){
					case 'P': return "POSTED";
					case 'O': return "OPEN";
					case 'C': return "CANCEL";
				}
			}

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser','adddate']);

			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////////////////////////////////grid2/////////////////////////////////////////////////////////
			var operDetail;

			var urlParam2={
				action:'get_table_default',
				field:['compcode','source','trantype','auditno','lineno_','deptcode','category','document', 'AmtB4GST', 'GSTCode', 'amount'],
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				filterCol:['auditno','recstatus','source','trantype'],
				filterVal:['', 'A','CM',''],
			}

			var saveParam2={
				action:'save_table_default',
				field:'',
				table_name:'finance.apactdtl',
				table_id:'lineno_',
				skipduplicate:true,
				lineno:{useOn:'auditno',useVal:'',useBy:'lineno_'},
				filterCol:['auditno'],
				filterVal:[''],
			}

			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=cdDetail_save",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
				 	{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:true},
					{ label: 'Line No', name: 'lineno_', width: 20, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
					{ label: 'Department', name: 'deptcode', width: 25, classes: 'wrap', canSearch: true, editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:deptcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Category', name: 'category', width: 25, edittype:'text', classes: 'wrap', editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:categoryCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Document', name: 'document', width: 29, classes: 'wrap', editable: true,
								//editrules:{required: true},
								edittype:"text",
					},
					{ label: 'Amount Before GST', name: 'AmtB4GST', width: 25, classes: 'wrap', 
								formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
								editable: true,
								align: "right",
								editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'GST Code', name: 'GSTCode', width: 25, edittype:'text', classes: 'wrap', editable: true,
								editrules:{required: true,custom:true, custom_func:cust_rules},
								formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:GSTCodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap', 
								formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
								editable: true,
								align: "right",
								editrules:{required: true},edittype:"text",
								editoptions:{
								maxlength: 12,
								dataInit: function(element) {
									element.style.textAlign = 'right';
									$(element).keypress(function(e){
										if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Action', name: 'action', width : 9,  formatter: "actions", editable:false,
											formatoptions: {
											    keys: true,
											    editbutton: false,
											    delbutton: true,
											    delOptions: {
											    	mtype: 'POST',
											    	onclickSubmit: function (options, rowid) {
											    			var detVa=$("#jqGrid2").jqGrid('getRowData',rowid);
											    			auditno=detVa.auditno;
                        									lineno_=detVa.lineno_;
													        options.url = '../../../../assets/php/entry.php?' + jQuery.param({
													            action: 'cdDetail_save',
													            auditno: detVa.auditno,
													            lineno_: detVa.lineno_,
													            source: detVa.source,
													            trantype: detVa.trantype
													        });
													},
													afterSubmit: function (response, postdata) {
														return $('#amount').val(response.responseText);
													},
											    },
											}
					},
				],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 200,
				shrinkToFit: true,
				rowNum: 30,
				sortname: 'lineno_',
        		sortorder: "desc",
        		onClose: function () {
        			console.log('before show form');
				},

				//rownumbers: true,
				pager: "#jqGridPager2",
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager2 td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					$( "#jqGrid2_ilcancel" ).unbind();
					$( "#jqGrid2_ilcancel" ).on( "click", function(event) {
						event.preventDefault();
						event.stopPropagation();
						bootbox.confirm("Are you sure want to cancel?", function(result){
							if (result == true) {
								refreshGrid("#jqGrid2",urlParam2);
							}
						});
					});
				},

				onSelectRow:function(rowid, selected){
					$("#jqGrid2 input[name='AmtB4GST']").on('keydown',  function() { 
						delay(function(){
							if($("#jqGrid2 input[name='GSTCode']").val() == '') {
								var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst;
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
							}else{
								var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
							}
							}, 1000 );
					});

					$("#jqGrid2 input[name='GSTCode']").on('keydown',  function(e) { 
						var code = e.keyCode || e.which;
							if (code == '9') { // -->for tab
								delay(function(){
									var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
								var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
								$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
								}, 1000 );
							}
					});

					$('#dialog').on('dblclick',function(){
						if(selText=="#jqGrid2 input[name='GSTCode']" ){
							var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
							var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);
							$("#jqGrid2 input[name='amount']").val(amount.toFixed(2));
						}
					});
				},
						
			});

			$("#jqGrid2").jqGrid('setLabel','AmtB4GST','Amount Before GST',{'text-align':'right'});
			$("#jqGrid2").jqGrid('setLabel','amount','Amount',{'text-align':'right'});
	
			function cust_rules(value,name){
				var temp;
				switch(name){
					case 'Department':temp=$('#deptcode');break;
					case 'Category':temp=$('#category');break;
					case 'GST Code':temp=$('#GSTCode');break;
				}
				return(temp.parent().hasClass("has-error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			////////formatter checkdetail//////////
			function showdetail(cellvalue, options, rowObject){
				var field,table;
				switch(options.colModel.name){
					case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
					case 'category':field=['catcode','description'];table="material.category";break;
					case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
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
			
			///////custom input/////
			function deptcodeCustomEdit(val,opt){  		
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function categoryCustomEdit(val,opt){  
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="category" name="category" type="text" class="form-control input-sm" data-validation="required" value="'+val+'"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function GSTCodeCustomEdit(val,opt){  
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="GSTCode" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
			}

			function galGridCustomValue (elem, operation, value){	
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

			var myEditOptions = {
		        keys: true,
		        oneditfunc: function (rowid) {
		        },
		        aftersavefunc: function (rowid, response, options) {
		           $('#amount').val(response.responseText);
		           //console.log(response);
		        },
		    };

			$("#jqGrid2").inlineNav('#jqGridPager2',{	
				add:true,
				edit:true,
				addParams: { 
        			addRowParams: myEditOptions
   				},
   				editParams: myEditOptions

			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveHeaderLabel",
				caption:"Detail",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Detail"
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "saveDetailLabel",
				caption:"Header",cursor: "pointer",position: "last", 
				buttonicon:"",
				title:"Header"
			});

			$("#saveHeaderLabel").click(function(){
				radbuts.check();
				unsaved = false;
				mycurrency.formatOff();
				mycurrency.check0value(errorField);
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveHeader("#formdata", oper,saveParam,{idno:selrowData('#jqGrid').idno});
					unsaved = false;

					$("#saveHeaderLabel").hide();
					$("#saveDetailLabel").show();
					$("#jqGrid2_iladd").show();
					$("#jqGrid2_iladd").click();
				}else{
					mycurrency.formatOn();
				}
			});

			$("#saveDetailLabel").click(function(){
				$("#formdata a").on('click',function(){
					dialog_bankcode.handler(errorField);
				});
				// $('#jqGrid2_ilcancel').click();
				$("#jqGrid2_iladd").hide();
				$("#saveHeaderLabel").show();
				$("#saveDetailLabel").hide();
				$("#jqGrid2_iledit").hide();
				$('#formdata  textarea').prop("readonly",false);
				$('#formdata input').prop("readonly",false);
				$('#formdata  input[type=radio]').prop("disabled",false);
				$('#formdata  input[rdonly]').prop("readonly",true);
				$('#formdata  input[frozeOnEdit]').prop("readonly",true);
			});
			$("#jqGrid2_iladd").click(function(){
				unsaved = false;

				$("#formdata :input[name='amount']").val($("#amount").val());
				$("input[id*='_auditno']").val(auditno);
				$("input[id*='_auditno']").attr('readonly','readonly');
				$("input[id*='_source']").val($("#source").val());
				$("input[id*='_trantype']").val($("#trantype").val());
				$("input[id*='_lineno_']").val($("#lineno_").val());

				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', '--', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', '--', 'Category');
				dialog_GSTCode=new makeDialog('hisdb.taxmast',"#jqGrid2 input[name='GSTCode']",['taxcode','description','rate'],'GST Code','Description', 'Rate', 'GST Code');
					
				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);
				dialog_GSTCode.handler(errorField);

				$("input[id*='_amount']").keydown(function(e) {
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

			$("#jqGrid2_iledit").click(function(){
				dialog_deptcode=new makeDialog('sysdb.department',"#jqGrid2 input[name='deptcode']",['deptcode','description'],'Department Code','Description', 'Department');
				dialog_category=new makeDialog('material.category',"#jqGrid2 input[name='category']",['catcode','description'],'Category Code','Description', 'Category');
				dialog_GSTCode=new makeDialog('hisdb.taxmast',"#jqGrid2 input[name='GSTCode']",['taxcode','description','rate'],'GST Code','Description', 'Rate', 'GST Code');

				dialog_deptcode.handler(errorField);
				dialog_category.handler(errorField);
				dialog_GSTCode.handler(errorField);
			});

			$("#jqGrid2_ilsave").click(function(){
				unsaved = false;
				
			});
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////end grid2/////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
			function makeDialog(table,id,cols,setLabel1,setLabel2,setLabel3,title){
				this.table=table;
				this.id=id;
				this.cols=cols;
				this.setLabel1=setLabel1;
				this.setLabel2=setLabel2;
				this.setLabel3=setLabel3;
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
				}
			}

			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 7/10 * $(window).width(),
				modal: true,
				open: function(){
					$("#gridDialog").jqGrid ('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth-$("#gridDialog_c")[0].offsetLeft));
					if(selText=="#jqGrid2 input[name='GSTCode']"){
						paramD.filterCol=['taxtype'];
						paramD.filterVal=['Input'];
					}else if(selText=="#jqGrid2 input[name='category']"){
						paramD.filterCol=['source', 'cattype'];
						paramD.filterVal=['RC', 'Other'];
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
					{ label: 'Description', name: 'desc', width: 400, canSearch:true, classes: 'pointer',},
					{ label: 'Rate', name: 'rate', width: 400, classes: 'pointer',},
				],
				width: 500,
				viewrecords: true,
				loadonce: false,
                multiSort: true,
				rowNum: 30,
				shrinkToFit: true,
				pager: "#gridDialogPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
					var data=$("#gridDialog").jqGrid ('getRowData', rowid);
					$("#gridDialog").jqGrid("clearGridData", true);
					$("#dialog").dialog( "close" );
					$(selText).val(rowid);
					$(selText).focus();
					$(selText).parent().next().html(data['desc']);
					
					if(selText=="#jqGrid2 input[name='GSTCode']"){
						rate=data.rate;

					}
				},
				
			});

			var paramD={action:'get_table_default',table_name:'',field:'',table_id:'',filter:''};
			function dialogHandler(){
				var table=this.table,id=this.id,cols=this.cols,setLabel1=this.setLabel1,setLabel2=this.setLabel2,setLabel3=this.setLabel3,title=this.title,self=this;
				$( id+" ~ a" ).on( "click", function() {
					selText=id,Dtable=table,Dcols=cols,
					$("#gridDialog").jqGrid("clearGridData", true);
				
					$( "#gridDialog" ).jqGrid( "setLabel", "code", setLabel1);
					$( "#gridDialog" ).jqGrid( "setLabel", "desc", setLabel2);
					$( "#dialog" ).dialog( "option", "title", title );
					if (selText=="#jqGrid2 input[name='GSTCode']"){
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 7/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").jqGrid('showCol', 'desc');
						$("#gridDialog").jqGrid('showCol', 'rate');
					}
					else{
						$( "#dialog" ).dialog({
								autoOpen: false,
								width: 7/10 * $(window).width(),
								modal: true,
						});	
						$("#gridDialog").jqGrid('showCol', 'desc');
						$("#gridDialog").jqGrid('hideCol', 'rate');
					}
					$( "#dialog" ).dialog( "open" );

					paramD.table_name=table;
					paramD.field=cols;
					paramD.table_id=cols[0];

					$("#gridDialog").jqGrid('setGridParam',{datatype:'json',url:'../../../../assets/php/entry.php?'+$.param(paramD)}).trigger('reloadGrid');
					$('#Dtext').val('');$('#Dcol').html('');
					
					$.each($("#gridDialog").jqGrid('getGridParam','colModel'), function( index, value ) {

				
							if(value['canSearch']){
								if(value['checked']){
									$( "#Dcol" ).append("<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' checked>"+setLabel1+"</input></label>" );
								}else{
									$("#Dcol" ).append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+cols[index]+"' >"+setLabel2+"</input></label>" );
								}
							}
						//}

						
					});
				});
				$(id).on("blur", function(){
					self.check();
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
						if(id== "#jqGrid2 input[name='GSTCode']") {
							taxcode=data.row.taxcode;
							rate=data.row.rate;
						}
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