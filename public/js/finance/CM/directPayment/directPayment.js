
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();/*
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");*/
	
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

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#apacthdr_amount']);
	var radbuts=new checkradiobutton(['TaxClaimable']);

	////////////////////////////////////start dialog//////////////////////////////////////

	var oper;
		$("#dialogForm")
			.dialog({ 
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
			parent_close_disabled(true);
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
						//set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
						dialog_paymode.on();
						dialog_bankcodefrom.on();
						dialog_bankcodeto.on();
						dialog_cheqno.on();
						
					}
					if(oper!='add'){
						dialog_paymode.check(errorField);
						dialog_bankcodefrom.check(errorField);
						dialog_bankcodeto.check(errorField);
						dialog_cheqno.check(errorField);
					
					}
				},
				close: function( event, ui ) {
					addmore_jqgrid2.state = false;
					addmore_jqgrid2.more = false;
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					emptyFormdata(errorField,'#formdata2');
					$('.my-alert').detach();
					dialog_paymode.off();
					dialog_bankcodefrom.off();
					dialog_bankcodeto.off();
					dialog_cheqno.off();
					$(".noti").empty();
					$("#refresh_jqGrid").click();
					refreshGrid("#jqGrid2",null,"kosongkan");
					//radbuts.reset();
					errorField.length=0;
				},
			});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
		var urlParam={
			action:'get_table_default',
			url:'util/get_table_default',
			field:'',
			table_name:'finance.apacthdr',
			table_id:'apacthdr_auditno',
			filterCol: ['source', 'trantype'],
			filterVal: ['CM', 'DP'],
			sort_idno: true
		}

	/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'dpHeader_Save',
			url:'directPayment/form',
			field:'',
			oper:oper,
			table_name:'finance.apacthdr',
			table_id:'apacthdr_auditno',
			sysparam:{source:'CM',trantype:'DP',useOn:'auditno'},
			sysparam2:{source:'HIS',trantype:'PV',useOn:'pvno'},
			saveip:'true',
			checkduplicate:'true'
		};
			
		$("#jqGrid").jqGrid({
			datatype: "local",
			colModel: [
				{label: 'compcode', name: 'apacthdr_compcode', width: 10 , hidden: true,  classes: 'wrap'},
				{ label: 'Audit No', name: 'apacthdr_auditno', width: 27, classes: 'wrap', canSearch: true, checked: true},
				{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 35, classes: 'wrap', canSearch: true},
				{ label: 'Pay To', name: 'apacthdr_payto', width: 35, classes: 'wrap',},
				{ label: 'Post Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap'},
				{ label: 'Amount', name: 'apacthdr_amount', width: 30, classes: 'wrap', formatter:'currency'} ,//unformat:unformat2}
				{ label: 'Remarks', name: 'apacthdr_remarks', width: 40, classes: 'wrap',},
				{ label: 'Status', name: 'apacthdr_recstatus', width: 20, classes: 'wrap',formatter:formatterPost, unformat:unformatterPost,},
				{ label: 'Entered By', name: 'apacthdr_adduser', width: 35, classes: 'wrap',},
				{ label: 'Entered Date', name: 'apacthdr_adddate', width: 40, classes: 'wrap',},
				{ label: 'Payment Mode', name: 'apacthdr_paymode', width: 25, classes: 'wrap'},
				{ label: 'Cheq No', name: 'apacthdr_cheqno', width: 40, classes: 'wrap',formatter:formatterCheqnno, unformat:unformatterCheqnno},
				{ label: 'GST', name: 'apacthdr_TaxClaimable', width: 40},
				{ label: 'Pv No', name: 'apacthdr_pvno', width: 40, hidden:'true'},
				{ label: 'Cheq Date', name: 'apacthdr_cheqdate', width: 40, hidden:'true'},
				{ label: 'source', name: 'apacthdr_source', width: 40, hidden:'true'},
			 	{ label: 'trantype', name: 'apacthdr_trantype', width: 40, hidden:'true'},
			 	{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:'true'},
			],
				autowidth:true,
                multiSort: true,
				viewrecords: true,
				loadonce:false,
				sortname:'idno',
				sortorder:'desc',
				width: 900,
				height: 350,
				rowNum: 30,
				pager: "#jqGridPager",
				ondblClickRow: function(rowid, iRow, iCol, e){
				let stat = selrowData("#jqGrid").apacthdr_recstatus;
					if(stat=='POSTED'){
						$("#jqGridPager td[title='View Selected Row']").click();
						$('#save').hide();
					}else{
						$("#jqGridPager td[title='Edit Selected Row']").click();
					}
				},
				gridComplete: function(){
					$('#but_cancel_jq,#but_post_jq').hide();
						if (oper == 'add' || oper == null) {
							$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
						}
						$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
				},
				onSelectRow: function(rowid, selected) {
				let recstatus = selrowData("#jqGrid").apacthdr_recstatus;
					if(recstatus=='OPEN'){
						$('#but_cancel_jq,#but_post_jq').show();
						
					}else if(recstatus=="POSTED"){
						$('#but_post_jq').hide();
						$('#but_cancel_jq').show();
					}else if (recstatus == "CANCELLED"){
						$('#but_cancel_jq,#but_post_jq').hide();
						
					}

					/*$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
					$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
					$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);
					$('#idno').val(selrowData("#jqGrid").apacthdr_idno);*/

					urlParam2.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;

					refreshGrid("#jqGrid3",urlParam2);
				}
		});
			
		////////////////////formatter status////////////////////////////////////////
			function formatterPost(cellvalue, option, rowObject){
				if (cellvalue == 'O'){
					return 'Open';
				}

				else if (cellvalue == 'P'){
					return 'Posted';
				}

				else if (cellvalue == 'C'){
					return 'Cancel';
				}
			}

			function  unformatterPost(cellvalue, options){
				if(cellvalue == 'Open'){
					return "O";
				}else if(cellvalue == 'Posted') { 
					return "P";
				}else if (cellvalue == 'Cancel'){
					return "C";
				}
			}

			function formatterCheqnno  (cellValue, options, rowObject) {
				return rowObject[15] != "CHEQUE" ? "<span cheqno='"+cellValue+"'></span>" : "<span cheqno='"+cellValue+"'>"+cellValue+"</span>";
			}

			function unformatterCheqnno (cellValue, options, rowObject) {
				return $(rowObject).find('span').attr('cheqno');
			}


			////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
			jqgrid_label_align_right("#jqGrid2");	

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
						saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
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
					recstatusDisable();
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
					$("#formdata :input[name='source']").val("CM");
					$("#formdata :input[name='trantype']").val("DP");

				},
			});

			//////////////////////////////////////end grid/////////////////////////////////////////////////////////
			
			//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
			populateSelect('#jqGrid','#searchForm');
			searchClick('#jqGrid','#searchForm',urlParam);

			//////////add field into param, refresh grid if needed////////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['apacthdr_idno','apacthdr_compcode','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','apacthdr_computerid','apacthdr_ipaddress']);

			////////////////////////////////hide at dialogForm///////////////////////////////////////////////////

			function hideatdialogForm(hide,saveallrow){
				if(saveallrow == 'saveallrow'){
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
					$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
				}else if(hide){
					$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
					$("#saveDetailLabel").show();
				}else{
					$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
					$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
				}
			}

			///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
				$("#but_cancel_jq,#but_post_jq").click(function(){
					saveParam.oper = $(this).data('oper');
					let obj={
						auditno:selrowData('#jqGrid').apacthdr_auditno,
						_token:$('#_token').val(),
					};
					$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
						refreshGrid("#jqGrid", urlParam);
					}).fail(function (data) {
						alert(data.responseText);
					}).done(function (data) {
						//2nd successs?
					});
				});

			/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
			function saveHeader(form,selfoper,saveParam,obj,needrefresh){
				if(obj==null){
					obj={};
				}
				saveParam.oper=selfoper;

				$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
					
				},'json').fail(function (data) {
					alert(data.responseText);
				}).done(function (data) {

					unsaved = false;
					hideatdialogForm(false);
					
					if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
						addmore_jqgrid2.state = true;
						$('#jqGrid2_iladd').click();
					}
					if(selfoper=='add'){

						oper='edit';//sekali dia add terus jadi edit lepas tu
						
						$('#apacthdr_auditno,#auditno').val(data.auditno);
						$('#idno').val(data.idno);
						// $('#apacthdr_outamount').val(data.outamount);//just save idno for edit later
						
						urlParam2.filterVal[1]=data.auditno;
					}else if(selfoper=='edit'){
						urlParam2.filterVal[1]=$('#apacthdr_auditno').val();
						//doesnt need to do anything
					}
					disableForm('#formdata');

					if(needrefresh === 'refreshGrid'){
						refreshGrid("#jqGrid", urlParam);
					}
					
				});
			}
			
			$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
				unsaved = true; //kalu dia change apa2 bagi prompt
			});

			$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
				unsaved = true; //kalu dia change apa2 bagi prompt
			});
			
			////////////////////////////populate data for dropdown search By////////////////////////////
			searchBy();
			function searchBy(){
				$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
					if(value['canSearch']){
						if(value['selected']){
							$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
						}else{
							$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
						}
					}
					searchClick2('#jqGrid','#searchForm',urlParam);
				});
			}

			/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
			var urlParam2={
				action:'get_table_default',
				url:'/util/get_table_default',
				field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno'],
				table_name:['finance.apactdtl AS apactdtl'],
				table_id:'lineno_',
				filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
				filterVal:['session.compcode', '', '<>.DELETE', 'CM', 'DP']
			};

			var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
			////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "/directPaymentDetail/form",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
					{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
					{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
					{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:true},
					{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
					{ label: 'Department', name: 'deptcode', width: 25, classes: 'wrap', canSearch: true, editable: true,
						editrules:{required: true,custom:true, custom_func:cust_rules},
						formatter: showdetail,
						edittype:'custom',	editoptions:
							{  
								custom_element:deptcodeCustomEdit,
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
					{ label: 'Amount Before GST', name: 'AmtB4GST', width: 100, classes: 'wrap',
						formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
						editable: true,
						align: "right",
						editrules:{required: true},edittype:"text",
						editoptions:{
							readonly: "readonly",
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
								{
									custom_element:GSTCodeCustomEdit,
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
				],
				autowidth: true,
				shrinkToFit: true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 1150,
				height: 200,
				rowNum: 30,
				sortname: 'lineno_',
				sortorder: "desc",
				pager: "#jqGridPager2",
				loadComplete: function(){
					if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
					else{
						$('#jqGrid2').jqGrid ('setSelection', "1");
					}

					addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
				},
				gridComplete: function(){
				
					fdl.set_array().reset();
					
				},
				beforeSubmit: function(postdata, rowid){ 
					dialog_supplier.check(errorField);
					dialog_payto.check(errorField);
					dialog_category.check(errorField);
					dialog_department.check(errorField);
			 	}
			});

			////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
			jqgrid_label_align_right("#jqGrid2");

			
			//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
			
			var myEditOptions = {
		        keys: true,
		        extraparam:{
				    "_token": $("#_token").val()
		        },
		        oneditfunc: function (rowid) {

		        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

		        	$("input[name='amount']").keydown(function(e) {//when click tab at document, auto save
						var code = e.keyCode || e.which;
						if (code == '9')$('#jqGrid2_ilsave').click();
					})
		        },
		        aftersavefunc: function (rowid, response, options) {
		        	$('#apacthdr_amount').val(response.responseText);
		        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
		        	refreshGrid('#jqGrid2',urlParam2,'add');
			    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
		        }, 
		        errorfunc: function(rowid,response){
		        	alert(response.responseText);
		        	refreshGrid('#jqGrid2',urlParam2,'add');
			    	$("#jqGridPager2Delete").show();
		        },
		        beforeSaveRow: function(options, rowid) {

		        	//if(errorField.length>0)return false;

					let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
					let editurl = "/directPaymentDetail/form?"+
						$.param({
							action: 'directPaymentDetail_save',
							auditno:$('#apacthdr_auditno').val(),
							amount:data.amount,
						});
					$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
		        },
		        afterrestorefunc : function( response ) {
					hideatdialogForm(false);
			    }
		    };

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
						    		param={
						    			action: 'directPaymentDetail_save',
										auditno: $('#apacthdr_auditno').val(),
										lineno_: selrowData('#jqGrid2').lineno_,

						    		}
						    		$.post( "/directPaymentDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
									}).fail(function(data) {
										//////////////////errorText(dialog,data.responseText);
									}).done(function(data){
										$('#amount').val(data);
										refreshGrid("#jqGrid2",urlParam2);
									});
						    	}else{
		        					$("#jqGridPager2EditAll").show();
						    	}
						    }
						});
					}
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "jqGridPager2EditAll",
				caption:"",cursor: "pointer",position: "last", 
				buttonicon:"glyphicon glyphicon-th-list",
				title:"Edit All Row",
				onClickButton: function(){
					/*mycurrency2.array.length = 0;
					var ids = $("#jqGrid2").jqGrid('getDataIDs');
				    for (var i = 0; i < ids.length; i++) {

				        $("#jqGrid2").jqGrid('editRow',ids[i]);

				        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);
				    }*/
				   // onall_editfunc();
					hideatdialogForm(true,'saveallrow');
				},
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "jqGridPager2SaveAll",
				caption:"",cursor: "pointer",position: "last", 
				buttonicon:"glyphicon glyphicon-download-alt",
				title:"Save All Row",
				onClickButton: function(){
					var ids = $("#jqGrid2").jqGrid('getDataIDs');

					var jqgrid2_data = [];
					mycurrency2.formatOff();
				    for (var i = 0; i < ids.length; i++) {

						var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

				    	var obj = 
				    	{
				    		'lineno_' : ids[i],
				    		'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),/*
				    		'reference' : data.reference,*/
				    		'amount' : data.amount,
		                    'unit' : $("#"+ids[i]+"_unit").val()
				    	}

				    	jqgrid2_data.push(obj);
				    }

					var param={
		    			action: 'directPaymentDetail_save',
						_token: $("#_token").val(),
						auditno: $('#apacthdr_auditno').val()
		    		}

		    		$.post( "/directPaymentDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
					}).fail(function(data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function(data){
						// $('#amount').val(data);
						hideatdialogForm(false);
						refreshGrid("#jqGrid2",urlParam2);
					});
				},	
			}).jqGrid('navButtonAdd',"#jqGridPager2",{
				id: "jqGridPager2CancelAll",
				caption:"",cursor: "pointer",position: "last", 
				buttonicon:"glyphicon glyphicon-remove-circle",
				title:"Cancel",
				onClickButton: function(){
					hideatdialogForm(false);
					refreshGrid("#jqGrid2",urlParam2);
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

			//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
			function showdetail(cellvalue, options, rowObject){
				var field, table, case_;
				switch(options.colModel.name){
					case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
					case 'category':field=['catcode','description'];table="material.category";break;
					case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
				}
				var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
			
				fdl.get_array('directPayment',options,param,case_,cellvalue);
				return cellvalue;
			}

			///////////////////////////////////////cust_rules//////////////////////////////////////////////
			function cust_rules(value,name){
				var temp;
				switch(name){
					case 'Department':temp=$('#deptcode');break;
					case 'Category':temp=$('#category');break;
					case 'GST Code':temp=$('#GSTCode');break;
				}
				return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			/////////////////////////////////////////////custom input////////////////////////////////////////////
			function deptcodeCustomEdit(val,opt){
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
			}

			function categoryCustomEdit(val,opt){
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="category" name="category" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');

			function GSTCodeCustomEdit(val,opt){
				val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
				return $('<div class="input-group"><input id="GSTCode" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
			}


			function galGridCustomValue (elem, operation, value){
				if(operation == 'get') {
					return $(elem).find("input").val();
				} 
				else if(operation == 'set') {
					$('input',elem).val(value);
				}
			}

			//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
			$("#saveDetailLabel").click(function(){
				mycurrency.formatOff();
				mycurrency.check0value(errorField);
				unsaved = false;
				
				if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				
					dialog_paymode.off();
					dialog_bankcodefrom.off();
					dialog_bankcodeto.off();
					dialog_cheqno.off();
					saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()});
					errorField.length=0;
				}else{
					mycurrency.formatOn();
				}
			});

			//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
			$("#saveHeaderLabel").click(function(){
				emptyFormdata(errorField,'#formdata2');
				hideatdialogForm(true);
				dialog_paymode.on();
				dialog_bankcodefrom.on();
				dialog_bankcodeto.on();
				dialog_cheqno.on();
				enableForm('#formdata');
				rdonly('#formdata');
				$(".noti").empty();
				refreshGrid("#jqGrid2",urlParam2);
				errorField.length=0;
			});


			////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
			$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){

				unsaved = false;
				$("#jqGridPager2Delete,#saveHeaderLabel").hide();
				dialog_document.on();//start binding event on jqgrid2

				$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid2_ilsave').click();
					
				});

			});	

			////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
			$("#jqGrid3").jqGrid({
				datatype: "local",
				colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
				shrinkToFit: true,
				autowidth:true,
				multiSort: true,
				viewrecords: true,
				rowNum: 30,
				sortname: 'lineno_',
				sortorder: "desc",
				pager: "#jqGridPager3",
				gridComplete: function(){
					
					fdl.set_array().reset();
				},
			});
			jqgrid_label_align_right("#jqGrid3");



			////////////////////object for dialog handler//////////////////

			var dialog_paymode = new ordialog(
				'paymode','debtor.paymode','#paymode',errorField,
				{	colModel:[
						{label:'Pay Mode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus', 'source'],
						filterVal:['session.compcode','A', 'CM']
					},
					ondblClickRow: function () {
						$('#bankcode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#bankcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Payment",
					open: function(){
						dialog_paymode.urlParam.filterCol=['compcode','recstatus', 'source'],
						dialog_paymode.urlParam.filterVal=['session.compcode','A', 'CM']
					}
				},'urlParam','radio','tab'
			);
			dialog_paymode.makedialog(true);

			var dialog_bankcodefrom = new ordialog(
				'bankcode','finance.bank','#bankcode',errorField,
				{	colModel:[
						{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#cheqno').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#cheqno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Bank Code",
					open: function(){
						dialog_bankcodefrom.urlParam.filterCol=['compcode','recstatus'],
						dialog_bankcodefrom.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_bankcodefrom.makedialog(true);

			var dialog_bankcodeto = new ordialog(
				'payto','finance.bank','#payto',errorField,
				{	colModel:[
						{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
						{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#remarks').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#remarks').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Bank Code Pay To",
					open: function(){
						dialog_bankcodeto.urlParam.filterCol=['compcode','recstatus'],
						dialog_bankcodeto.urlParam.filterVal=['session.compcode','A']
					}
				},'urlParam','radio','tab'
			);
			dialog_bankcodeto.makedialog(true);

			var dialog_cheqno = new ordialog(
				'cheqno','finance.chqtran','#cheqno',errorField,
				{	colModel:[
						{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
						
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						$('#cheqdate').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#cheqdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Cheque No",
					open: function(){
						dialog_cheqno.urlParam.filterCol=['compcode','stat', 'bankcode'],
						dialog_cheqno.urlParam.filterVal=['session.compcode','A', $('#bankcode').val()]
					}
				},'urlParam','radio','tab'
			);
			dialog_cheqno.makedialog(true);

			var dialog_deptcode = new ordialog(
				'deptcode','sysdb.department',"#jqGrid2 input[name='deptcode']",errorField,
				{	colModel:[
						{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
						{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true},
						
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						//$('#cheqdate').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							//$('#cheqdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Department Code",
					open: function(){
						dialog_deptcode.urlParam.filterCol=['compcode','stat', 'bankcode'],
						dialog_deptcode.urlParam.filterVal=['session.compcode','A', $('#bankcode').val()]
					}
				},'urlParam','radio','tab'
			);
			dialog_deptcode.makedialog(true);

			var dialog_category = new ordialog(
				'category','material.category',"#jqGrid2 input[name='category']",errorField,
				{	colModel:[
						{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
						{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true},
						
					],
					urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
					ondblClickRow: function () {
						//$('#cheqdate').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							//$('#cheqdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
				},{
					title:"Select Category",
					open: function(){
						dialog_category.urlParam.filterCol=['compcode','stat', 'bankcode'],
						dialog_category.urlParam.filterVal=['session.compcode','A', $('#bankcode').val()]
					}
				},'urlParam','radio','tab'
			);
			dialog_category.makedialog(true);

			var dialog_GSTCode = new ordialog(
			'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
			{	colModel:
				[
					{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
					{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
				],
				urlParam: {
							filterCol:['compcode','recstatus'],
							filterVal:['session.compcode','A']
						},
				ondblClickRow:function(event){
					if(event.type == 'keydown'){

						var optid = $(event.currentTarget).get(0).getAttribute("optid");
						var id_optid = optid.substring(0,optid.search("_"));

						$(event.currentTarget).parent().next().html('');
					}else{

						var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
						var id_optid = optid.substring(0,optid.search("_"));

						$(event.currentTarget).parent().next().html('');
					}

					let data=selrowData('#'+dialog_GSTCode.gridname);

					$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['rate']);
					$(dialog_GSTCode.textfield).closest('td').next().has("input[type=text]").focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$(obj.textfield).closest('td').next().find("input[type=text]").focus();
					}
				}
			},{
				title:"Select Tax Code For Item",
				open: function(){
					dialog_GSTCode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
					dialog_GSTCode.urlParam.filterVal=['session.compcode','A', 'Input'];
				},
				close: function(){
					if($('#jqGridPager2SaveAll').css("display") == "none"){
						$(dialog_GSTCode.textfield)			//lepas close dialog focus on next textfield 
						.closest('td')						//utk dialog dalam jqgrid jer
						.next()
						.find("input[type=text]").focus();
					}
					
				}
			},'urlParam','radio','tab'
		);
		dialog_GSTCode.makedialog(false);

		$("#jqGrid3_panel").on("show.bs.collapse", function(){
			$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		});
	
});
		