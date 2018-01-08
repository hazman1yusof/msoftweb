
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

			$('.sajanktry').click(function(event){
				event.stopPropagation();
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

			///////////////////////////////// trandate check date validate from period////////// ////////////////
			var actdateObj = new setactdate(["#trandate"]);
			actdateObj.getdata().set();

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
							$("#txndept").val($("#x").val().toUpperCase());
							break;
						case state = 'edit':
							$("#pg_jqGridPager2 table").show();
							hideatdialogForm(true);
							enableForm('#formdata');
							rdonly('#formdata');
							inputTrantypeValue();
							break;
						case state = 'view':
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							inputTrantypeValue();
							break;
					}if(oper!='add'){
						dialog_trantype.check(errorField);
						dialog_txndept.check(errorField);
						dialog_sndrcv.check(errorField);
					}if(oper!='view'){
						dialog_trantype.on();
						dialog_txndept.on();
						dialog_sndrcv.on();
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
					dialog_trantype.off();
					dialog_txndept.off();
					dialog_sndrcv.off();
					$(".noti").empty();
					$("#refresh_jqGrid").click();
				},
			});
			////////////////////////////////////////end dialog///////////////////////////////////////////////////
			

			/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
			var urlParam={
				action:'get_table_default',
				field:'',
				table_name:'material.ivtmphd',
				table_id:'idno',
				sort_idno:true,
				filterCol:[],
				filterVal:[],
			}
			/////////////////////parameter for saving url///////////////////////////////////////////////////////
			var saveParam={
				action:'invTran_save',
				field:'',
				oper:oper,
				table_name:'material.ivtmphd',
				table_id:'recno'
			};

			/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'Record No', name: 'recno', width: 20, classes: 'wrap', canSearch: true,selected:true},
					{ label: 'Tran Dept', name: 'txndept', width: 25, classes: 'wrap'},
					{ label: 'Tran Type', name: 'trantype', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Document No', name: 'docno', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Transaction Date', name: 'trandate', width: 20, classes: 'wrap', canSearch: true},
					{ label: 'Sender/Receiver', name: 'sndrcv', width: 27, classes: 'wrap', canSearch: true},
					{ label: 'SndRcvType', name: 'sndrcvtype', width: 27, classes: 'wrap'},
					{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
					{ label: 'Status', name: 'recstatus', width: 15, classes: 'wrap',},			
					{ label: 'Request RecNo', name: 'srcdocno', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'remarks', name: 'remarks', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
					{ label: 'source', name: 'source', width: 40, hidden:'true'},
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
					(selrowData("#jqGrid").recstatus!='POSTED')?$('#div_for_but_post').show():$('#div_for_but_post').hide();
					urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
					urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					urlParam2.join_filterVal = [['skip.s.uomcode',"skip.'"+selrowData("#jqGrid").txndept+"'","skip.'"+moment(selrowData("#jqGrid").trandate).year()+"'"],[]];

					refreshGrid("#jqGrid3",urlParam2);
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

			////////////////////// set label jqGrid right ////////////////////////////////////////////////////
			jqgrid_label_align_right("#jqGrid2");

			////////////////////// set label jqGrid right ////////////////////////////////////////////////
			$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', {'text-align':'right'});

			/////////////////////////start grid pager/////////////////////////////////////////////////////////

			$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
				view:false,edit:false,add:false,del:false,search:false,
				beforeRefresh: function(){
					refreshGrid("#jqGrid",urlParam,oper);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first", 
				buttonicon:"glyphicon glyphicon-info-sign",
				title:"View Selected Row",  
				onClickButton: function(){
					oper='view';
					selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
					refreshGrid("#jqGrid2",urlParam2);
				},
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
				buttonicon:"glyphicon glyphicon-edit",
				title:"Edit Selected Row",  
				onClickButton: function(){
					oper='edit';
					selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
					populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
					refreshGrid("#jqGrid2",urlParam2);
				}, 
			}).jqGrid('navButtonAdd',"#jqGridPager",{
				caption:"",cursor: "pointer",position: "first",  
				buttonicon:"glyphicon glyphicon-plus", 
				id: 'glyphicon-plus',
				title:"Add New Row", 
				onClickButton: function(){
					oper='add';
					$( "#dialogForm" ).dialog( "open" );
				},
			});

			//////////handle searching, its radio button and toggle /////////////////////////////////////////////
			populateSelect('#jqGrid','#searchForm');

			//////////add field into param, refresh grid if needed///////////////////////////////////////////////
			addParamField('#jqGrid',true,urlParam);
			addParamField('#jqGrid',false,saveParam,['adduser','adddate','idno']);

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

			/////////////////////////////////trantype////////////////////////////////////////////////////////////

			//LI LIR LO LOR TR  --> Enable Receiver N Qty On Hand Receiver else hide
			function inputTrantypeValue(){
				var trantype = $('#trantype').val();

						switch(trantype){
							case "LI":
							case "LIR":
							case "LO":
							case "LOR":
								exceptTR();
								break;
							case "TR":
								forTR();
								break;
							default:
								$("#jqGrid2").jqGrid('hideCol', 'recvqtyonhand');
								$("label[for=sndrcv]").hide();
								$("#sndrcv_parent").hide();

								$("label[for=sndrcvtype]").hide();
								$("#sndrcvtype_parent").hide();
								break;
						}

						function forTR(){
							$("#jqGrid2").jqGrid('showCol', 'recvqtyonhand');
							$("label[for=sndrcv]").show();
							$("#sndrcv_parent").show();

							$("label[for=sndrcvtype]").show();
							$("#sndrcvtype_parent").show();
							$("#sndrcvtype option[value='Department']").show();
							$("#sndrcvtype option[value='Supplier']").hide();
							$("#sndrcvtype option[value='Other']").hide();
						}

						function exceptTR(){
							$("#jqGrid2").jqGrid('showCol', 'recvqtyonhand');
							$("label[for=sndrcv]").show();
							$("#sndrcv_parent").show();

							$("label[for=sndrcvtype]").show();
							$("#sndrcvtype_parent").show();
							$("#sndrcvtype option[value='Department']").hide();
							$("#sndrcvtype option[value='Supplier']").show();
							$("#sndrcvtype option[value='Other']").show();
						}
			}

			/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
			function saveHeader(form,selfoper,saveParam,obj){
				if(obj==null){
					obj={};
				}
				saveParam.oper=selfoper;

				$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
				},'json').fail(function(data) {
					//////////////////errorText(dialog,data.responseText);
				}).done(function(data){
					if(selfoper=='add'){
						oper='edit';//sekali dia add terus jadi edit lepas tu
						$('#recno').val(data.recno);
						$('#docno').val(data.docno);
						$('#idno').val(data.idno);//just save idno for edit later

						urlParam2.filterVal[0]=data.recno; 
						urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
						urlParam2.join_filterVal = [['skip.s.uomcode',$('#txndept').val(),moment($("#trandate").val()).year()],[]];
					}else if(selfoper=='edit'){
						//doesnt need to do anything
					}
					$('#jqGrid2_iladd').click();
					disableForm('#formdata');
					hideatdialogForm(false);
				});
			}
			
			$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
				unsaved = true; //kalu dia change apa2 bagi prompt
			});

			/////////////////////////////populate data for dropdown search By////////////////////////////
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

			///////////////////////////populate data for dropdown tran dept/////////////////////////////
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
					}
				});
			}

			////////////////////////////changing status and trandept trigger search////////////////////////
			$('#Status').on('change',searchChange);
			$('#trandept').on('change',searchChange);
			function searchChange(){
				var arrtemp = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
				var filter = arrtemp.reduce(function(a,b,c){
					if(b=='All'){
						return a;
					}else{
						a.fc = a.fc.concat(a.fct[c]);
						a.fv = a.fv.concat(b);
						return a;
					}
				},{fct:['recstatus','txndept'],fv:[],fc:[]});//tukar kat sini utk searching

				urlParam.filterCol = filter.fc;
				urlParam.filterVal = filter.fv;
				refreshGrid('#jqGrid',urlParam);
			}


			/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
			var urlParam2={
				action:'get_table_default',
				field:['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','ivt.uomcode', 'p.description', 's.qtyonhand','NULL AS recvqtyonhand','s.maxqty','ivt.txnqty','ivt.netprice','ivt.amount','ivt.expdate','ivt.batchno'],
				table_name:['material.ivtmpdt ivt', 'material.stockloc s', 'material.productmaster p'],
				table_id:'lineno_',
				join_type:['LEFT JOIN','LEFT JOIN'],
				join_onCol:['ivt.itemcode', 'ivt.itemcode'],
				join_onVal:['s.itemcode','p.itemcode'],
				filterCol:['ivt.recno', 'ivt.compcode', 'ivt.recstatus'],
				filterVal:['', 'session.company','A']
			};

			////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
			$("#jqGrid2").jqGrid({
				datatype: "local",
				editurl: "../../../../assets/php/entry.php?action=invTranDetail_save",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
				 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:true, hidden:true},
					{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:true, hidden:true},
					{ label: 'Item Code', name: 'itemcode', width: 130, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
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
					{ label: 'Qty on Hand at Req Dept', name: 'deptqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,	
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
        			},
					{ label: 'Qty on Hand at Req To Dept', name: 'recvqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
						formatter: formatter_recvqtyonhand,
					},
					{ label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',  
						editable:true,
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: false},editoptions:{readonly: "readonly"},
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
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', editable:true,
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
					},
					{ label: 'Expiry Date', name: 'expdate', width: 100, classes: 'wrap', editable:true,
						formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
						editoptions: {
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'expdate_datePicker',
                                    dateFormat: 'dd/mm/yy',
                                    minDate: 1,
                                    showOn: 'focus',
                                    changeMonth: true,
				  					changeYear: true,
                                });
                            }
                        }
					},
					{ label: 'Batch No', name: 'batchno', width: 75, classes: 'wrap', editable:true,
							maxlength: 30,
					}
				],
				autowidth: false,
				shrinkToFit: false,
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
					if(addmore_jqgrid2)$('#jqGrid2_iladd').click();
					addmore_jqgrid2 = false; //only addmore after save inline
				},
				gridComplete: function(){
					$( "#jqGrid2_ilcancel" ).off();
					$( "#jqGrid2_ilcancel" ).on( "click", function(event) {
						event.preventDefault();
						event.stopPropagation();
						bootbox.confirm({
						    message: "Are you sure want to cancel?",
						    buttons: {
						        confirm: { label: 'Yes',className: 'btn-success'},
						        cancel: {label: 'No',className: 'btn-danger'}
							},
							callback: function (result) {
								if (result == true) {
									$(".noti").empty();
									refreshGrid("#jqGrid2",urlParam2);
								}
						    }
						});
					});
				},
				afterShowForm: function (rowid) {
				    $("#expdate").datepicker();
				},
				beforeSubmit: function(postdata, rowid){ 
					dialog_itemcode.check(errorField);
					dialog_uomcode.check(errorField);
			 	}
			});

			////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
			jqgrid_label_align_right("#jqGrid2");

			//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
			var addmore_jqgrid2=false // if addmore is true, add after refresh jqgrid2
			var myEditOptions = {
		        keys: true,
		        oneditfunc: function (rowid) {
		        },
		        aftersavefunc: function (rowid, response, options) {
		           $('#amount').val(response.responseText);
		        	addmore_jqgrid2=true; //only addmore after save inline
		        	refreshGrid('#jqGrid2',urlParam2,'add');
		        	$("#jqGridPager2Delete").show();
		        }, 
		        beforeSaveRow: function(options, rowid) {
					let editurl = "../../../../assets/php/entry.php?"+
						$.param({
							action: 'invTranDetail_save',
							docno:$('#docno').val(),
							recno:$('#recno').val()
        				});
					$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
		        },
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
						    			action: 'invTranDetail_save',
        								recno: $('#recno').val(),
										lineno_: selrowData('#jqGrid2').lineno_,
						    		}
						    		$.post( "../../../../assets/php/entry.php?"+$.param(param),{oper:'del'}, function( data ){
									}).fail(function(data) {
										//////////////////errorText(dialog,data.responseText);
									}).done(function(data){
										$('#amount').val(data);
										refreshGrid("#jqGrid2",urlParam2);
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

			function formatter_recvqtyonhand(cellvalue, options, rowObject){
				let year=($('#trandate').val().trim()!='')?moment($('#trandate').val()).year():selrowData('#jqGrid').trandate;
				let txndept=($('#txndept').val().trim()!='')?$('#txndept').val():selrowData('#jqGrid').txndept;
				var param={action:'get_value_default',field:['qtyonhand'],table_name:'material.stockloc'}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
				param.filterVal = [year,rowObject[3], txndept,rowObject[4]];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {

				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").text(data.rows[0].qtyonhand);
					}
				});
				return "";
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
				// val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
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

			//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
			$("#saveDetailLabel").click(function(){ //actually saving the header
				mycurrency.formatOff();
				mycurrency.check0value(errorField);
				unsaved = false;
				dialog_txndept.off();
				dialog_trantype.off();
				dialog_sndrcv.off();
				if($('#formdata').isValid({requiredFields:''},conf,true)){
					saveHeader("#formdata",oper,saveParam);
					unsaved = false;
				}else{
					mycurrency.formatOn();
				}
				getTrantypeDetail();
			});

			//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
			$("#saveHeaderLabel").click(function(){
				emptyFormdata(errorField,'#formdata2');
				hideatdialogForm(true);
				dialog_txndept.on();
				dialog_trantype.on();
				dialog_sndrcv.on();
				enableForm('#formdata');
				rdonly('#formdata');
				$(".noti").empty();
				refreshGrid("#jqGrid2",urlParam2);
			});

			////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
			$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
				unsaved = false;
				$("#jqGridPager2Delete").hide();
				dialog_itemcode.on();//start binding event on jqgrid2
				dialog_uomcode.on();
				$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',calculate_amount_and_other);

				$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid2_ilsave').click();
				});
			});

			///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
			function getQOHsndrcv(){
				var param={
					func:'getQOHsndrcv',
					action:'get_value_default',
					field:['qtyonhand'],
					table_name:'material.stockloc'
				}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
				param.filterVal = [moment($('#trandate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#sndrcv').val(), $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					$("#jqGrid2 input[name='recvqtyonhand']").val('');
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
						$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
					}else{
						bootbox.confirm({
						    message: "No stock location at department code: "+$('#sndrcv').val()+"... Proceed? ",
						    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
						    },
						    callback: function (result) {
						    	if(!result){
						    		$("#jqGrid2_ilcancel").click();
						    	}else{
									
						    	}
						    }
						});
					}
				});
			}

			////////////////////////////////////// get trantype detail////////////////////////
			function getTrantypeDetail(){
				var param={
					func:'getTrantypeDetail',
					action:'get_value_default',
					field:['crdbfl','isstype'],
					table_name:'material.ivtxntype'
				}

				param.filterCol = ['trantype'];
				param.filterVal = [$('#trantype').val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$('#crdbfl').val(data.rows[0].crdbfl);
						$('#isstype').val(data.rows[0].isstype);
					}else{
						alert('no crdbfl or isstype for trantype: '+$('#trantype').val());
					}
				});
			}

			////////////////////////////////////////calculate amount////////////////////////////
			function calculate_amount_and_other(event){
				let deptqtyonhand=parseInt($("#jqGrid2 input[name='deptqtyonhand']").val());
				let txnqty=parseInt($("input[name='txnqty']").val());
				let netprice=parseFloat($("input[name='netprice']").val());
				let crdbfl=$('#crdbfl').val();
				if(event.target.name=='txnqty'){
					if(deptqtyonhand<event.target.value){
						bootbox.alert("Transaction quantity exceed quantity on hand");
						event.target.value='';return false;
					}
					if(crdbfl=='In'&&event.target.value==0){
						bootbox.alert("Transaction Quantity Cannot Be Zero");
						$("#jqGrid2_ilsave").hide();
						event.target.value='';return false;
					}else{
						$("#jqGrid2_ilsave").show();
					}
				}else{
					if(crdbfl=='Out'&&event.target.value==0){
						bootbox.alert("Net Price Cannot Be Zero");
						event.target.value='';return false;
					}else{
						$("#jqGrid2_ilsave").show();
					}
				}
				let amount=txnqty*netprice;
				$("#jqGrid2 input[name='amount']").val(amount.toFixed(4));
			}

			////////////////////////////////////// get average cost////////////////////////
			function getavgcost(){
				let crdbfl = $('#crdbfl').val();
				let isstype = $('#isstype').val();
				var param={
					func:'getavgcost',
					action:'get_value_default',
					field:['avgcost','expdtflg'],
					table_name:'material.product'
				}

				param.filterCol = ['itemcode', 'uomcode',];
				param.filterVal = [ $("#jqGrid2 input[name='itemcode']").val(), $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						$("#jqGrid2 input[name='netprice']").val(parseFloat(data.rows[0].avgcost).toFixed(4));
						let expdtflg = data.rows[0].expdtflg;
						if (expdtflg == '1' && crdbfl == 'In' && isstype == 'Others') {
								$("#jqGrid2").jqGrid('setColProp', 'expdate', {editrules: {required: true}});
								$("#jqGrid2").jqGrid('setColProp', 'batchno', {editrules: {required: true}});
						}
						if (expdtflg == '0' && crdbfl == 'In' && isstype == 'Others') {
								$("#jqGrid2").jqGrid('setColProp', 'expdate', {editrules: {required: false}});
								$("#jqGrid2").jqGrid('setColProp', 'batchno', {editrules: {required: false}});
						}
					}else{
						alert('cant find avgcost and expdtflg for itemcode: '+$("#jqGrid2 input[name='itemcode']").val()+' and uom: '+$("#jqGrid2 input[name='uomcode']").val());
					}
				});
			}

			////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
			$("#jqGrid3").jqGrid({
				datatype: "local",
				colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
				autowidth:true,
				multiSort: true,
				viewrecords: true,
				rowNum: 30,
				sortname: 'lineno_',
        		sortorder: "desc",
				pager: "#jqGridPager3",
			});
			jqgrid_label_align_right("#jqGrid3");

			////////////////////////////////////////////but_post_jq//////////////////////////////////////
			$("#but_post_jq").click(function(){
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
						$("#but_post_jq").hide();
					});
			});




			////////////////////////////////////////////////////ordialog////////////////////////////////////////
			var dialog_txndept = new ordialog(
				'txndept','sysdb.department','#txndept',errorField,
				{	colModel:[
						{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Transaction Department",
					open: function(){
						dialog_txndept.urlParam.filterCol=['storedept', 'recstatus'];
						dialog_txndept.urlParam.filterVal=['1', 'A'];
					}
				}
			);
			dialog_txndept.makedialog();

			var dialog_trantype = new ordialog(
				'trantype','material.ivtxntype','#trantype',errorField,
				{	colModel:[
						{label:'Transaction type',name:'trantype',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						],
						ondblClickRow:function(){
						inputTrantypeValue();
						}	
				},{
					title:"Select Transaction Type",
					open: function(){
						dialog_trantype.urlParam.filterInCol=['trantype'];
						dialog_trantype.urlParam.filterInType=['NOT IN'];
						dialog_trantype.urlParam.filterInVal=[['DS1', 'DS']];
						dialog_trantype.urlParam.filterCol=['recstatus'];
						dialog_trantype.urlParam.filterVal=['A'];
					}
				}
			);
			dialog_trantype.makedialog();

			var dialog_sndrcv = new ordialog(
				'sndrcv','sysdb.department','#sndrcv',errorField,
				{	colModel:[
						{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Receiver Department",
					open: function(){
						if($('#trantype').val().trim() == 'TR') {
							dialog_sndrcv.urlParam.filterCol=['storedept', 'recstatus'];
							dialog_sndrcv.urlParam.filterVal=['1', 'A'];
							dialog_sndrcv.urlParam.filterInCol=['deptcode'];
							dialog_sndrcv.urlParam.filterInType=['NOT IN'];
							dialog_sndrcv.urlParam.filterInVal=[[$('#txndept').val()]];
						}else {
							dialog_sndrcv.urlParam.filterCol=['recstatus'];
							dialog_sndrcv.urlParam.filterVal=['A'];
						}
					}
				}
			);
			dialog_sndrcv.makedialog();

			var dialog_itemcode = new ordialog(
				'itemcode',['material.stockloc s','material.productmaster p'],"#jqGrid2 input[name='itemcode']",errorField,
				{	colModel:
					[
						{label:'Item Code',name:'s.itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'p.description',width:400,classes:'pointer',canSearch:true,or_search:true},
						{label:'Quantity On Hand',name:'s.qtyonhand',width:100,classes:'pointer',},
						{label:'UOM Code',name:'s.uomcode',width:100,classes:'pointer'},
						{label:'Max Quantity',name:'s.maxqty',width:100,classes:'pointer'},
					],
					ondblClickRow:function(){
						let data=selrowData('#'+dialog_itemcode.gridname);
						$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
						$("#jqGrid2 input[name='description']").val(data['p.description']);
						$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
						$("#jqGrid2 input[name='maxqty']").val(data['s.maxqty']);
						$("#jqGrid2 input[name='deptqtyonhand']").val(data['s.qtyonhand']);
						getQOHsndrcv();getavgcost();
					}
				},{
					title:"Select Item For Stock Transaction",
					open:function(){
						dialog_itemcode.urlParam.table_id="none_";
						dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode'];
						dialog_itemcode.urlParam.filterVal=['session.company',moment($('#reqdt').val()).year(),$('#txndept').val()];
						dialog_itemcode.urlParam.join_type=['LEFT JOIN'];
						dialog_itemcode.urlParam.join_onCol=['s.itemcode'];
						dialog_itemcode.urlParam.join_onVal=['p.itemcode'];
						dialog_itemcode.urlParam.join_filterCol=[['s.compcode']];
						dialog_itemcode.urlParam.join_filterVal=[['skip.p.compcode']];
					}
				},'urlParam'
			);
			dialog_itemcode.makedialog(false);
			//false means not binding event on jqgrid2 yet, after jqgrid2 add, event will be bind

			var dialog_uomcode = new ordialog(
				'uom',['material.stockloc s','material.uom u'],"#jqGrid2 input[name='uomcode']",errorField,
				{	colModel:
					[
						{label:'UOM code',name:'s.uomcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'u.description',width:400,classes:'pointer',canSearch:true,or_search:true},
						{label:'Department code',name:'s.deptcode',width:150,classes:'pointer'},
						{label:'Item code',name:'s.itemcode',width:150,classes:'pointer'},
					],
					ondblClickRow:function(){
						let data=selrowData('#'+dialog_uomcode.gridname);
						$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
					}
					
				},{
					title:"Select UOM Code For Item",
					open:function(){
						dialog_uomcode.urlParam.table_id="none_";
						dialog_uomcode.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
						dialog_uomcode.urlParam.filterVal=['session.company',$('#txndept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()];
						dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
						dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
						dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
						dialog_uomcode.urlParam.join_filterCol=[['s.compcode']];
						dialog_uomcode.urlParam.join_filterVal=[['skip.u.compcode']];
					}
				},'urlParam'
			);
			dialog_uomcode.makedialog(false);

			var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
			genpdf.printEvent();

		});