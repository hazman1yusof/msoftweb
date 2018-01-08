
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
					$("#delordhd_prdept").val($("#x").val());
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
				dialog_authorise.check(errorField);
				dialog_prdept.check(errorField);
				dialog_suppcode.check(errorField);
				dialog_credcode.check(errorField);
				dialog_deldept.check(errorField);
			}if(oper!='view'){
				dialog_authorise.on();
				dialog_prdept.on();
				dialog_suppcode.on();
				dialog_credcode.on();
				dialog_deldept.on();
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
			/*dialog_authorise.off();
			dialog_prdept.off();
			dialog_suppcode.off();
			dialog_credcode.off();
			dialog_deldept.off();*/
			$(".noti").empty();
			$("#refresh_jqGrid").click();
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		field:'',
		fixPost:'true',
		table_name:['material.delordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		sort_idno:true,
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['trantype'],
		filterVal:['GRN'],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'delOrd_save',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'material.delordhd',
		table_id:'recno'
	};
	function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'delordhd_recno', width: 10, classes: 'wrap', canSearch: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', width: 20, classes: 'wrap', canSearch:true},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 15, canSearch: true, classes: 'wrap' },
			{ label: 'GRN No', name: 'delordhd_docno', width: 15, classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: 'Received Date', name: 'delordhd_trandate', width: 20, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Supplier Name', name: 'supplier_name', width: 25, classes: 'wrap'},
			{ label: 'Purchase Order No', name: 'delordhd_srcdocno', width: 15, classes: 'wrap', canSearch: true},
			{ label: 'DO No', name: 'delordhd_delordno', width: 15, classes: 'wrap', canSearch: true},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 20, classes: 'wrap'},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 20, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 20, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 20},
			{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 25, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deldate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:'true'},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:'true'},
			{ label: 'suppperson', name: 'delordhd_suppperson', width: 40, hidden:'true'},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:'true'},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:'true'},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:'true'},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:'true'},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:'true'},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:'true'},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:'true'},
			//{ label: 'reqdept', name: 'delordhd_reqdept', width: 40, hidden:'true'},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:'true'},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:'true'},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:'true'},
			{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:'true'},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:'true'},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:'true'},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:'true'},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:'true'},

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
			(selrowData("#jqGrid").recstatus!='POSTED')?$('#div_for_but_post').show():$('#div_for_but_post').hide(); //tunjuk kalu belum posted lagi

			urlParam2.filterVal[0]=selrowData("#jqGrid").delordhd_recno;
			$('#recnodepan').text(selrowData("#jqGrid").delordhd_recno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").delordhd_prdept);

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
	addParamField('#jqGrid',false,saveParam,['delordhd_adduser','delordhd_adddate','delordhd_upduser','delordhd_upddate','delordhd_deluser','delordhd_idno','supplier_name']);

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
				$('#delordhd_recno').val(data.recno);
				$('#delordhd_docno').val(data.docno);
				$('#delordhd_idno').val(data.idno);//just save idno for edit later

				urlParam2.filterVal[0]=data.recno; 
				urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
				urlParam2.join_filterVal = [['skip.s.uomcode',$('#txndept').val(),moment($("#trandate").val()).year()],[]];
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
			$('#jqGrid2_iladd').click();
			disableForm('#formdata');
			hideatdialogForm(false);

			},'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
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

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if($('#Scol').val()=='delordhd_trandate'){
			$("input[name='Stext']").attr('type','date');
			$("input[name='Stext']").velocity({width: "250px"});
			$("input[name='Stext']").on('change', searchbydate);
		}else{
			$("input[name='Stext']").attr('type','text');
			$("input[name='Stext']").velocity({width: "100%"});
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	
	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}
	
	function searchChange(){
		var arrtemp = ['skip.supplier.CompCode',  $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['delordhd.compcode','delordhd.recstatus', 'delordhd.prdept','txndept'],fv:[],fc:[]});//tukar kat sini utk searching purreqhd.compcode','purreqhd.recstatus','purreqhd.prdept'

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}


	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.remarks','dodt.uomcode', 'dodt.qtyorder','dodt.qtydelivered','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax','dodt.amount','dodt.expdate','dodt.batchno','dodt.polineno'],
		table_name:['material.delorddt dodt','material.productmaster p'],
		table_id:'lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['dodt.itemcode'],
		join_onVal:['p.itemcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		filterVal:['','session.company','A']
	};
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "../../../../assets/php/entry.php?action=delOrdDetail_save",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:true, hidden:true},
			{ label: 'Price Code', name: 'pricecode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:pricecodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},

			{ label: 'Remarks', name: 'remarks', width: 200, classes: 'wrap', editable:true},
			{ label: 'UOM Code', name: 'uomcode', width: 110, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'O/S Quantity', name: 'qtyorder', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Quantity Delivered', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true}
			},
			{ label: 'Unit Price', name: 'unitprice', width: 90, align: 'right', classes: 'wrap', 
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
			{ label: 'Tax Code', name: 'taxcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:taxcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Percentage Discount (%)', name: 'perdisc', width: 90, align: 'right', classes: 'wrap', 
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
			{ label: 'Discount Per Unit', name: 'amtdisc', width: 90, align: 'right', classes: 'wrap', 
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
			{ label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},
			},
			{ label: 'Total Line Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
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
			},
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false},
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
							$(".noti").empty();hideatdialogForm(false);
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
			dialog_itemcode.check(errorField);//have function or not??
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
           $('#delordhd_totamount').val(response.responseText);
           $('#delordhd_subamount').val(response.responseText);
        	addmore_jqgrid2=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
        	mycurrency2.formatOff();
			let editurl = "../../../../assets/php/entry.php?"+
				$.param({
					action: 'delOrdDetail_save',
					docno:$('#delordhd_docno').val(),
					recno:$('#delordhd_recno').val()
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
				    			action: 'delOrdDetail_save',
								recno: $('#delordhd_recno').val(),
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
			// case 'itemcode':field=['itemcode','description'];table="material.product";break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";break;
			case 'pricecode':field=['pricecode','description'];table="material.pricesource";break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";break;
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
		let year=($('#delordhd_trandate').val().trim()!='')?moment($('#delordhd_trandate').val()).year():selrowData('#jqGrid').delordhd_trandate;
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
			case 'Price Code':temp=$('#pricecode');break;
			case 'Tax Code':temp=$('#taxcode');break;
		}
		return(temp.parent().hasClass("has-error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val,opt){
		// val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	<--nak buang decription kat bawah 
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}
	function pricecodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function taxcodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="taxcode" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		dialog_authorise.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_credcode.off();
		dialog_deldept.off();
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		}else{
			mycurrency.formatOn();
			dialog_authorise.on();
			dialog_prdept.on();
			dialog_suppcode.on();
			dialog_credcode.on();
			dialog_deldept.on();

		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_authorise.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		dialog_credcode.on();
		dialog_deldept.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////

	var mycurrency2 =new currencymode(["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']"]);

	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
		unsaved = false;
		$("#jqGridPager2Delete").hide();
		dialog_pricecode.on();//start binding event on jqgrid2
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_taxcode.on();
		$("input[name='gstpercent']").val('0')//reset gst to 0

		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtydelivered']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='unitprice']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amtdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtyorder']").on('blur',{currency: mycurrency2});

		$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
		});
	});


	////////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
	/*function getQOHsndrcv(){
		var param={
			func:'getQOHsndrcv',
			action:'get_value_default',
			field:['qtyonhand'],
			table_name:'material.stockloc'
		}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [moment($('#delordhd_trandate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#sndrcv').val(), $("#jqGrid2 input[name='uomcode']").val()];

		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			$("#jqGrid2 input[name='recvqtyonhand']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
				$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
			}else if($("#sndrcv").val()!=''){
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
			}else{
				
			}
		});
	}

/*	//////////////////////////////calculate outstanding quantity/////////////////////
	function calculate_oustanding(event){
		let qtyorder=parseInt($("#jqGrid2 input[name='qtyorder']").val());
	}
	///////////////////////////////////////////////////////////////////////////////*/

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function calculate_line_totgst_and_totamt(event){
		let qtydelivered=parseInt($("#jqGrid2 input[name='qtydelivered']").val());
		let unitprice=parseFloat($("input[name='unitprice']").val());
		let amtdisc=parseFloat($("input[name='amtdisc']").val());
		let gstpercent=parseFloat($("input[name='gstpercent']").val());
		let perdisc=parseFloat($("input[name='perdisc']").val());

		// var tot_perdisc = ((unitprice * qtydelivered) * perdisc) / 100; 
		var tot_gst = gstpercent / 100 * (unitprice-amtdisc) * qtydelivered;
		var amount = ((unitprice*perdisc/100-amtdisc) * qtydelivered) + tot_gst;

		var netunitprice = (unitprice-amtdisc) + ((unitprice-amtdisc) * gstpercent/100);//?

		$("input[name='tot_gst']").val(tot_gst);
		$("input[name='amount']").val(amount);
		event.data.currency.formatOn();//change format to currency on each calculation
	}

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
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
	var dialog_authorise = new ordialog(
		'authorise',['material.authorise'],"#delordhd_respersonid",errorField,
		{	colModel:
			[
				{label:'Authorize Person',name:'authorid',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Name',name:'name',width:400,classes:'pointer',canSearch:true,or_search:true},
			]
		},{
			title:"Authorize Person",
			open: function(){
				dialog_authorise.urlParam.filterCol=['compcode','recstatus'];
				dialog_authorise.urlParam.filterVal=['session.company','A'];
			}
		},'urlParam'
	);
	dialog_authorise.makedialog();

	var dialog_prdept = new ordialog(
		'prdept','sysdb.department','#delordhd_prdept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				]
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_prdept.urlParam.filterCol=['purdept', 'recstatus'];
				dialog_prdept.urlParam.filterVal=['1', 'A'];
			}
		},'urlParam'
	);
	dialog_prdept.makedialog();

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#delordhd_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_suppcode.gridname);
				$("#delordhd_credcode").val(data['suppcode']);
			}
		},{
			title:"Select Transaction Type",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus'];
				dialog_suppcode.urlParam.filterVal=['A'];
			}
		},'urlParam'
	);
	dialog_suppcode.makedialog();

	var dialog_credcode = new ordialog(
		'credcode','material.supplier','#delordhd_credcode',errorField,
		{	colModel:[
				{label:'Creditor Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Creditor Name',name:'name',width:400,classes:'pointer',canSearch:true,or_search:true},
			]
		},{
			title:"Select Creditor",
			open: function(){
				dialog_credcode.urlParam.filterCol=['recstatus'];
				dialog_credcode.urlParam.filterVal=['A'];
			}
		},'urlParam'
	);
	dialog_credcode.makedialog();

	var dialog_deldept = new ordialog(
		'deldept','sysdb.department','#delordhd_deldept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				]
		},{
			title:"Select Receiver Department",
			open: function(){
				dialog_deldept.urlParam.filterCol=['storedept', 'recstatus'];
				dialog_deldept.urlParam.filterVal=['1', 'A'];
			}
		},'urlParam'
	);
	dialog_deldept.makedialog();

	var dialog_pricecode = new ordialog(
		'pricecode',['material.pricesource'],"#jqGrid2 input[name='pricecode']",errorField,
		{	colModel:
			[
				{label:'Price code',name:'pricecode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			]
		},{
			title:"Select Price Code For Item",
			open: function(){
				dialog_pricecode.urlParam.filterCol=['compcode','recstatus'];
				dialog_pricecode.urlParam.filterVal=['session.company','A'];
			},
			close: function(){
				$(dialog_pricecode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'
	);
	dialog_pricecode.makedialog(false);

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
				//getQOHsndrcv();//getavgcost();
			}
		},{
			title:"Select Item For Stock Transaction",
			open:function(){
				dialog_itemcode.urlParam.table_id="none_";
				dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode'];
				dialog_itemcode.urlParam.filterVal=['session.company',moment($('#delordhd_trandate').val()).year(),$('#delordhd_deldept').val()];
				dialog_itemcode.urlParam.join_type=['LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol=['s.itemcode'];
				dialog_itemcode.urlParam.join_onVal=['p.itemcode'];
				dialog_itemcode.urlParam.join_filterCol=[['s.compcode']];
				dialog_itemcode.urlParam.join_filterVal=[['skip.p.compcode']];
				//dialog_itemcode.urlParam.join_filterCol = [['s.compcode','s.uomcode'],[]];
				//dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode','skip.p.uomcode'],[]];
			},
			close: function(){
				$(dialog_itemcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'//urlParam means check() using urlParam not check_input
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
				dialog_uomcode.urlParam.filterVal=['session.company',$('#delordhd_deldept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#delordhd_trandate').val()).year()];
				dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
				dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol=[['s.compcode']];
				dialog_uomcode.urlParam.join_filterVal=[['skip.u.compcode']];
			},
			close: function(){
				$(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'
	);
	dialog_uomcode.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'rate',name:'rate',width:400,classes:'pointer',hidden:true},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_taxcode.gridname);
				$('#gstpercent').val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_taxcode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_taxcode.urlParam.filterVal=['session.company','A', 'Input'];
			},
			close: function(){
				$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'
	);
	dialog_taxcode.makedialog(false);

	var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();

});