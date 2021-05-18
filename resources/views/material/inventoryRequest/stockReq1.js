
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';

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
					}if(oper!='add'){
						dialog_reqdept.check(errorField);
						dialog_reqtodept.check(errorField);
					}if(oper!='view'){
						if(dialog_reqdept.eventstat=='off')dialog_reqdept.on();
						if(dialog_reqtodept.eventstat=='off')dialog_reqdept.on();
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
				action:'stockReq_header_save',
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
					{ label: 'Request No', name: 'ivreqno', width: 10, canSearch: true,selected:true},
					{ label: 'Request Department', name: 'reqdept', width: 30, canSearch: true},
					{ label: 'Record No', name: 'recno', width: 10, canSearch: true},
					{ label: 'Request To Department', name: 'reqtodept', width: 30, classes: 'wrap'},
					{ label: 'Request Date', name: 'reqdt', width: 20, canSearch: true, formatter: "date", formatter:dateUNFormatter},
					{ label: 'Amount', name: 'amount', width: 20, align: 'right', formatter:'currency'},
					{ label: 'Remark', name: 'remarks', width: 50, classes: 'wrap'},
					{ label: 'Status', name: 'recstatus', width: 20},
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
					(selrowData("#jqGrid").recstatus!='POSTED')?$('#div_for_but_post').show():$('#div_for_but_post').hide();
					urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
					urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					urlParam2.join_filterVal = [['skip.s.uomcode',"skip.'"+selrowData("#jqGrid").reqdept+"'","skip.'"+moment(selrowData("#jqGrid").reqdt).year()+"'"],[]];

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

					urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
					urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					urlParam2.join_filterVal = [['skip.s.uomcode',"skip.'"+selrowData("#jqGrid").reqdept+"'","skip.'"+moment(selrowData("#jqGrid").reqdt).year()+"'"],[]];

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

					urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
					urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
					urlParam2.join_filterVal = [['skip.s.uomcode',selrowData("#jqGrid").reqdept,moment(selrowData("#jqGrid").reqdt).year()],[]];
					
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
				}).done(function(data){
					if(selfoper=='add'){
						oper='edit';//sekali dia add terus jadi edit lepas tu
						$('#recno').val(data.recno);
						$('#ivreqno').val(data.ivreqno);
						$('#idno').val(data.idno);//just save idno for edit later

						urlParam2.filterVal[0]=data.recno; 
						urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
						urlParam2.join_filterVal = [['skip.s.uomcode',$('#reqdept').val(),moment($("#reqdt").val()).year()],[]];
					}else if(selfoper=='edit'){
						//doesnt need to do anything
					}
					disableForm('#formdata');
					hideatdialogForm(false);
				});
			}
			
			$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
				unsaved = true; //kalu dia change apa2 bagi prompt
			});

			///////////////////utk dropdown search By/////////////////////////////////////////////////
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

			/////////////////////////////populate data for dropdown search By////////////////////////////
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

			///////////////////////////populate data for dropdown tran dept/////////////////////////////
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
				},{fct:['recstatus','reqtodept'],fv:[],fc:[]});

				urlParam.filterCol = filter.fc;
				urlParam.filterVal = filter.fv;
				refreshGrid('#jqGrid',urlParam);
			}


			/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
			var urlParam2={
				action:'get_table_default',
				field:['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','p.description','ivt.uomcode','s.maxqty','s.qtyonhand','NULL AS recvqtyonhand','ivt.qtyrequest','ivt.qtytxn'],
				table_name:['material.ivreqdt ivt', 'material.stockloc s', 'material.product p'],
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
				editurl: "../../../../assets/php/entry.php?action=stockReq_detail_save",
				colModel: [
				 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
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
					{ label: 'Item Description', name: 'description', width: 200, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
					{ label: 'Uom Code', name: 'uomcode', width: 110, classes: 'wrap', editable:true,
							editrules:{required: true,custom:true, custom_func:cust_rules},
							formatter: showdetail,
								edittype:'custom',	editoptions:
								    {  custom_element:uomcodeCustomEdit,
								       custom_value:galGridCustomValue 	
								    },
					},
					{ label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',  
						editable:true,
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
					},
					{ label: 'Qty on Hand at Req Dept', name: 'deptqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,	
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
        			},
					{ label: 'Qty on Hand at Req To Dept', name: 'recvqtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,
						formatter:'integer',formatoptions:{thousandsSeparator: ",",},
						editrules:{required: true},editoptions:{readonly: "readonly"},
						formatter: formatter_recvqtyonhand,
					},
					{ label: 'Qty Requested', name: 'qtyrequest', width: 80, align: 'right', classes: 'wrap', 
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
					{ label: 'Qty Supplied', name: 'qtytxn', width: 100, align: 'right', classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
				],
				autowidth:true,
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
		        	addmore_jqgrid2=true; //only addmore after save inline
		        	refreshGrid('#jqGrid2',urlParam2,'add');
		        	$("#jqGridPager2Delete").show();
		        }, 
		        beforeSaveRow: function(options, rowid) {
		        	var qtyrequest = parseInt($("input[id*='qtyrequest']").val());
					var recvqtyonhand = parseInt($("input[id*='recvqtyonhand']").val());
					
					if(qtyrequest > recvqtyonhand) {
						bootbox.alert("Quantity request cannot be greater than quanntity on hand")
							return false;
					}

					let editurl = "../../../../assets/php/entry.php?"+
						$.param({
							action: 'stockReq_detail_save',
							ivreqno:$('#ivreqno').val(),
							recno:$('#recno').val(),
							reqdept:$('#reqdept').val()
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
						    			action: 'stockReq_detail_save',
        								recno: $('#recno').val(),
										lineno_: selrowData('#jqGrid2').lineno_,
						    		}
						    		$.post( "../../../../assets/php/entry.php?"+$.param(param),{oper:'del'}, function( data ){
									}).fail(function(data) {
										//////////////////errorText(dialog,data.responseText);
									}).done(function(data){
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
				var reqtodept = $('#reqtodept').val();
				var datetrandate = new Date($('#reqdt').val());
				var getyearinput = datetrandate.getFullYear();

				var param={action:'get_value_default',field:['qtyonhand'],table_name:'material.stockloc'}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
				param.filterVal = [getyearinput,rowObject[3], reqtodept,rowObject[5]];

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
			$("#saveDetailLabel").click(function(){
				mycurrency.formatOff();
				mycurrency.check0value(errorField);
				unsaved = false;
				dialog_reqdept.off();
				dialog_reqtodept.off();
				if($('#formdata').isValid({requiredFields:''},conf,true)){
					saveHeader("#formdata",oper,saveParam);
					unsaved = false;
					hideatdialogForm(false);
					$('#jqGrid2_iladd').click();
				}else{
					mycurrency.formatOn();
				}
			});

			//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
			$("#saveHeaderLabel").click(function(){
				emptyFormdata(errorField,'#formdata2');
				hideatdialogForm(true);
				dialog_reqdept.on();
				dialog_reqtodept.on();
				enableForm('#formdata');
				rdonly('#formdata');
				$(".noti").empty();
				refreshGrid("#jqGrid2",urlParam2);
			});

			////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
			$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
				unsaved = false;
				$("#jqGridPager2Delete").hide();
				dialog_itemcode.on();
				dialog_uomcode.on();

				$("input[id*='_qtyrequest']").keydown(function(e) { //ving when click tab at qtyrequest
					var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid2_ilsave').click();
				});
			});

			///////////////////////////////////////// QtyOnHand Dept ////////////////////////////////////////////
			function getQOHReqDept(from_selecting_uomcode){
				var reqdept = $('#reqdept').val();
				var datetrandate = new Date($('#reqdt').val());
				var getyearinput = datetrandate.getFullYear();

				var param={
					func:'getQOHReqDept',
					action:'get_value_default',
					field:['qtyonhand', 'maxqty'],
					table_name:'material.stockloc'
				}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode',];
				param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), reqdept, $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					$("#jqGrid2 input[name='deptqtyonhand'],#jqGrid2 input[name='maxqty']").val('');
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null && data.rows[0].maxqty!=null){
						$("#jqGrid2 input[name='deptqtyonhand']").val(data.rows[0].qtyonhand);
						$("#jqGrid2 input[name='maxqty']").val(data.rows[0].maxqty);
						if(from_selecting_uomcode)getQOHReqToDept();
					}else{
						bootbox.confirm({
						    message: "No stock location at department code: "+$('#reqdept').val()+"... Proceed? ",
						    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
						    },
						    callback: function (result) {
						    	if(!result){
						    		$("#jqGrid2_ilcancel").click();
						    	}else{
									if(from_selecting_uomcode)getQOHReqToDept();
						    	}
						    }
						});
					}
				});
			}

			///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
			function getQOHReqToDept(){
				var reqtodept = $('#reqtodept').val();
				var datetrandate = new Date($('#reqdt').val());
				var getyearinput = datetrandate.getFullYear();

				var param={
					func:'getQOHReqToDept',
					action:'get_value_default',
					field:['qtyonhand'],
					table_name:'material.stockloc'
				}

				param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
				param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), reqtodept, $("#jqGrid2 input[name='uomcode']").val()];

				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
					$("#jqGrid2 input[name='recvqtyonhand']").val('');
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
						$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
					}else{
						bootbox.confirm({
						    message: "No stock location at department code: "+$('#reqtodept').val()+"... Proceed? ",
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


			////////////////////////////////////////////////////ordialog////////////////////////////////////////
			var dialog_reqdept = new ordialog(
				'reqdept','sysdb.department','#reqdept',errorField,
				{	colModel:[
						{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Request Department",
				}
			);
			dialog_reqdept.makedialog();

			var dialog_reqtodept = new ordialog(
				'reqtodept','sysdb.department','#reqtodept',errorField,
				{	colModel:[
						{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
						]
				},{
					title:"Select Request Made To Department",
					open: function(){
						dialog_reqtodept.urlParam.filterCol=['storedept'];
						dialog_reqtodept.urlParam.filterVal=['1'];
					}
				}
			);
			dialog_reqtodept.makedialog();

			var dialog_itemcode = new ordialog(
				'itemcode',['material.stockloc s','material.product p'],"#jqGrid2 input[name='itemcode']",errorField,
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
						dialog_uomcode.check(errorField);
						getQOHReqDept(true);
					}
				},{
					title:"Select Item For Stock Request",
					open:function(){
						dialog_itemcode.urlParam.table_id="none_";
						dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','deptcode'];
						dialog_itemcode.urlParam.filterVal=['session.company',moment($('#reqdt').val()).year(),$('#reqdept').val()];
						dialog_itemcode.urlParam.join_type=['LEFT JOIN'];
						dialog_itemcode.urlParam.join_onCol=['s.itemcode'];
						dialog_itemcode.urlParam.join_onVal=['p.itemcode'];
						dialog_itemcode.urlParam.join_filterCol=[['s.compcode']];
						dialog_itemcode.urlParam.join_filterVal=[['skip.p.compcode']];
					}
				}
			);
			dialog_itemcode.makedialog(false);

			var dialog_uomcode = new ordialog(
				'uom','material.uom',"#jqGrid2 input[name='uomcode']",errorField,
				{	colModel:
					[
						{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
						{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
					],
					
				},{
					title:"Select UOM Code For Item",
					ondblClickRow:function(){
						getQOHReqDept(true);
					}
				}
			);
			dialog_uomcode.makedialog(false);

		});