
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
				dialog_reqdept.check(errorField);
				dialog_srcdocno.check(errorField);
			}if(oper!='view'){
				dialog_authorise.on();
				dialog_prdept.on();
				dialog_suppcode.on();
				dialog_credcode.on();
				dialog_deldept.on();
				dialog_reqdept.on();
				dialog_srcdocno.on();
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
			addmore_jqgrid2.state = false;//reset balik
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
		join_onCol:['supplier.SuppCode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['trantype','prdept'],
		filterVal:['GRN', $('#x').val()],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'delOrd_save',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'material.delordhd',
		table_id:'delordhd_recno'
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

	function searchClick2(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function() {
		delay(function(){
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#recnodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		}, 500 );
	});

	$(form+' [name=Scol]').on( "change", function() {
		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		$('#recnodepan').text("");//tukar kat depan tu
		$('#prdeptdepan').text("");
		refreshGrid("#jqGrid3",null,"kosongkan");
	});
}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'delordhd_recno', width: 12, classes: 'wrap', canSearch: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', width: 20, classes: 'wrap', canSearch:true},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 18, canSearch: true, classes: 'wrap' },
			{ label: 'GRN No', name: 'delordhd_docno', width: 15, classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: 'Received Date', name: 'delordhd_trandate', width: 20, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 25, classes: 'wrap', canSearch: true},
			{ label: 'Supplier Name', name: 'supplier_name', width: 25, classes: 'wrap', canSearch: true },
			{ label: 'Purchase Order No', name: 'delordhd_srcdocno', width: 15, classes: 'wrap', canSearch: true},
			{ label: 'DO No', name: 'delordhd_delordno', width: 15, classes: 'wrap', canSearch: true},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 20, classes: 'wrap'},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 20, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 20, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 20},
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 25, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deliverydate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:'true'},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:'true'},
			{ label: 'postedby', name: 'delordhd_postedby', width: 40, hidden:'true'},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:'true'},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:'true'},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:'true'},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:'true'},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:'true'},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:'true'},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:'true'},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:'true'},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:'true'},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:'true'},
			{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:'true'},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:'true'},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:'true'},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:'true'},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:'true'},
			{ label: 'cancelby', name: 'delordhd_cancelby', width: 40, hidden:'true'},
			{ label: 'canceldate', name: 'delordhd_canceldate', width: 40, hidden:'true'},
			{ label: 'reopenby', name: 'delordhd_reopenby', width: 40, hidden:'true'},
			{ label: 'reopendate', name: 'delordhd_reopendate', width: 40, hidden:'true'},

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
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			switch($("#scope").val()){
				case "dataentry":
					if($('#delordhd_srcdocno')=='0' && $('#delordhd_srcdocno')=='null'){
						$("label[for=delordhd_reqdept]").show();
						$("#delordhd_reqdept_parent").show();
						$("#delordhd_reqdept").attr('required',false);
					}else{
						$("label[for=delordhd_reqdept]").hide();
						$("#delordhd_reqdept_parent").hide();
						$("#delordhd_reqdept").removeAttr('required');
					}
					break;
				case "cancel": 
					if(stat=='POSTED'){
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
					break;
				case "all": 
					if($('#delordhd_srcdocno')=='0' && $('#delordhd_srcdocno')=='null'){
							$("label[for=delordhd_reqdept]").show();
							$("#delordhd_reqdept_parent").show();
							$("#delordhd_reqdept").attr('required',true);
						}else{
							$("label[for=delordhd_reqdept]").hide();
							$("#delordhd_reqdept_parent").hide();
							$("#delordhd_reqdept").removeAttr('required');
						}
					if(stat=='POSTED'){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
					}
					break;
			}

			urlParam2.filterVal[0]=selrowData("#jqGrid").delordhd_recno;
			$('#recnodepan').text(selrowData("#jqGrid").delordhd_recno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").delordhd_prdept);

			refreshGrid("#jqGrid3",urlParam2);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
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

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		saveParam.oper = $(this).data("oper");
		console.log($.param(saveParam));
		let obj={recno:selrowData('#jqGrid').delordhd_recno};
		$.post("../../../../assets/php/entry.php?" + $.param(saveParam),obj, function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});
	

/*	/////////////////////////////////HIDE REQ DEPT////////////////////////////////////////////////////////////
	function hideReqDept(){
		var delordhd_srcdocno = $('#delordhd_srcdocno').val();
		switch(delordhd_srcdocno){
			case "0":
			case "null":
				showReqDept();
				break;
			default:
				hideReqDept();
				break;
		}

		function hideReqDept(){
			$("label[for=delordhd_reqdept]").hide();
			$("#delordhd_reqdept_parent").hide();
			$("#delordhd_reqdept").removeAttr('required');
		}

		function showReqDept(){
			$("label[for=delordhd_reqdept]").show();
			$("#delordhd_reqdept_parent").show();
			$("#delordhd_reqdept").attr('required',true);
		}

	}*/

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( "../../../../assets/php/entry.php?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
		unsaved = false;
			hideatdialogForm(false);

			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){
				//$('#jqGrid2_iladd').click();
				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#delordhd_recno').val(data.recno);
				$('#delordhd_docno').val(data.docno);
				$('#delordhd_idno').val(data.idno);//just save idno for edit later

				urlParam2.filterVal[0]=data.recno; 
			/*	urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode','s.year'],[]];
				urlParam2.join_filterVal = [['skip.s.uomcode',$('#txndept').val(),moment($("#trandate").val()).year()],[]];*/
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
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
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	var supplierkatdepan = new ordialog(
		'supplierkatdepan', 'material.supplier', '#supplierkatdepan', 'errorField',
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["delordhd_suppcode"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}
	);
	supplierkatdepan.makedialog();


	
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
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode','dodt.pouom', 'dodt.suppcode','dodt.trandate','dodt.deldept','dodt.deliverydate','dodt.qtyorder','dodt.qtydelivered', 'dodt.qtytag','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax','dodt.netunitprice','dodt.amount','dodt.expdate','dodt.batchno','dodt.polineno','NULL AS remarks_button','dodt.remarks'],
		table_name:['material.delorddt dodt','material.productmaster p'],
		table_id:'lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['dodt.itemcode'],
		join_onVal:['p.itemcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		filterVal:['','session.company','<>.DELETE']
	};

	var addmore_jqgrid2={more:false,state:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
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
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'UOM Code', name: 'uomcode', width: 110, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{
				label: 'PO UOM', name: 'pouom', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},
			
			{ label: 'Quantity Order', name: 'qtyorder', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true, hidden:true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: false},edittype:"text",
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

			{ label: 'Quantity Delivered', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
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
			{ label: 'O/S Quantity', name: 'qtyOutstand', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
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
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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
			{ label: 'Net Unit Price', name: 'netunitprice', width: 100, align: 'right', classes: 'wrap', editable:true, hidden:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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
		
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden:true},
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
			if(addmore_jqgrid2.more == true)$('#jqGrid2_iladd').click();
			addmore_jqgrid2.more = false; //only addmore after save inline
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
							$("#jqGrid2").jqGrid("clearGridData", true);
							refreshGrid("#jqGrid2",urlParam2);
						}
						linenotoedit = null;
				    }
				});
			});

			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('lineno_',$(this).data('lineno_'));
				$("#remarks2").data('grid',"#jqGrid2");
				$("#dialog_remarks").dialog( "open" );
			});
		},
		afterShowForm: function (rowid) {
		    $("#expdate").datepicker();
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_itemcode.check(errorField);//have function or not??
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	var linenotoedit=null;
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-lineno_='"+rowObject[2]+"' data-remarks='"+rowObject[20]+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				$("#jqGrid2").jqGrid('setRowData', linenotoedit ,{remarks:newval});
				$(this).dialog('close');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

	var butt2_rem = 
		[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			let lineno_use = ($('#remarks2').data('lineno_')!='undefined')?$('#remarks2').data('lineno_'):linenotoedit;
			$('#remarks2').val($($('#remarks2').data('grid')).jqGrid ('getRowData', lineno_use).remarks);
			console.log(linenotoedit);
			if(linenotoedit == lineno_use){
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt1_rem);
			}else{
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt2_rem);
			}
		},
		buttons : butt2_rem
	});

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	//var addmore_jqgrid2=false // if addmore is true, add after refresh jqgrid2
	var myEditOptions = {
        keys: true,
        oneditfunc: function (rowid) {
        	//console.log(rowid);
        	linenotoedit = rowid;
        	$("#jqGrid2").find(".remarks_button[data-lineno_!='"+linenotoedit+"']").prop("disabled", true);
        	$("#jqGrid2").find(".remarks_button[data-lineno_='undefined']").prop("disabled", false);
        },
        aftersavefunc: function (rowid, response, options) {
           $('#delordhd_totamount').val(response.responseText);
           $('#delordhd_subamount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
        	mycurrency2.formatOff();
			let editurl = "../../../../assets/php/entry.php?"+
				$.param({
					action: 'delOrdDetail_save',
					docno:$('#delordhd_docno').val(),
					recno:$('#delordhd_recno').val(),
					suppcode:$('#delordhd_suppcode').val(),
					trandate:$('#delordhd_trandate').val(),
					deldept:$('#delordhd_deldept').val(),
					deliverydate:$('#delordhd_deliverydate').val(),
					remarks:selrowData('#jqGrid2').remarks//bug will happen later because we use selected row
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
			case 'pouom': field = ['pouom', 'description']; table = "material.uom"; break;
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

/*	function formatter_recvqtyonhand(cellvalue, options, rowObject) {
		var prdept = $('#prdept').val();
		var datetrandate = new Date($('#trandate').val());
		var getyearinput = datetrandate.getFullYear();

		var param = { action: 'get_value_default', field: ['qtyonhand'], table_name: 'material.stockloc' }

		param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
		param.filterVal = [getyearinput, rowObject[3], prdept, rowObject[5]];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").text(data.rows[0].qtyonhand);
			}
		});
		return "";
	}*/

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Item Code':temp=$('#itemcode');break;
			case 'UOM Code':temp=$('#uomcode');break;
			case 'PO UOM': temp = $('#pouom'); break;
			case 'Price Code':temp=$('#pricecode');break;
			case 'Tax Code':temp=$('#taxcode');break;
		}
		return(temp.parent().hasClass("has-error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
			val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input id="pouom" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function taxcodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="taxcode" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
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
		dialog_reqdept.off();
		dialog_srcdocno.off();
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
			dialog_reqdept.on();
			dialog_srcdocno.on();

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
		dialog_reqdept.on();
		dialog_srcdocno.on();
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
		dialog_pouom.on();
		dialog_taxcode.on();
		
		$("input[name='gstpercent']").val('0')//reset gst to 0

		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtydelivered']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtyOutstand']").on('blur', { currency: mycurrency2 }, calculate_quantity_outstanding);
		$("#jqGrid2 input[name='unitprice']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amtdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtyorder']").on('blur',{currency: mycurrency2}, calculate_quantity_outstanding);
		$("#jqGrid2 input[name='qtydelivered']").on('blur',calculate_conversion_factor);

		$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
		});
	});


	////////////////////////////////////////// Check Delivery Dept/////////////////////////////////////////////
/*	function getQOHPrDept(){
		var param={
			func:'getQOHPrDept',
			action:'get_value_default',
			field:['qtyonhand'],
			table_name:'material.stockloc'
		}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [moment($('#trandate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#delordhd_deldept').val(), $("#jqGrid2 input[name='uomcode']").val()];

		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			//$("#jqGrid2 input[name='recvqtyonhand']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
				//$("#jqGrid2 input[name='delordhd_deldept']").val(data.rows[0].qtyonhand);
			}else if($("#delordhd_deldept").val()!=''){
				bootbox.confirm({
				    message: "No stock location at department code: "+$('#delordhd_deldept').val()+"... Proceed? ",
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
	}*/

	function getQOHPrDept() {
		var param = {
			func: 'getQOHPrDept',
			action: 'get_value_default',
			field: ['qtyonhand'],
			table_name: 'material.stockloc'
		}
		var id="#jqGrid2 input[name='deldeptqtyonhand']"
		var fail_msg = "No Stock Location at delivery department"

		param.filterCol = ['year', 'deptcode','itemcode'];
		param.filterVal = [moment($('#trandate').val()).year(), $('#delordhd_deldept').val(), $("#jqGrid2 input[name='itemcode']").val()];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti ol').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$('.noti ol').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		});
	}

	/////////////calculate conv fac//////////////////////////////////
	 function calculate_conversion_factor(event) {

		console.log("balconv");


		var id="#jqGrid2 input[name='qtydelivered']"
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode"
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#convfactor_pouom").val());

		let qtydelivered = parseFloat($("#jqGrid2 input[name='qtydelivered']").val());

		console.log(convfactor_uom);
		console.log(convfactor_pouom);

		var balconv = convfactor_pouom*qtydelivered%convfactor_uom;

		if (balconv  == 0) {
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
			$( id ).removeClass( "error" ).addClass( "valid" );
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		} else {
			$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
			$( id ).removeClass( "valid" ).addClass( "error" );
			$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
			}
		}
		
			
		//event.data.currency.formatOn();//change format to currency on each calculation
		
	}
	///////////////////////////////////////////////////////////////////////////////

	//////////////////////////////calculate outstanding quantity/////////////////////
	function calculate_quantity_outstanding(event){
        let qtyorder = parseFloat($("#jqGrid2 input[name='qtyorder']").val());
        let qtydelivered = parseFloat($("#jqGrid2 input[name='qtydelivered']").val());

        var qtyOutstand = (qtyorder - qtydelivered);

        $("input[name='qtyOutstand']").val(qtyOutstand);

        console.log(qtyOutstand);


	}
	///////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function calculate_line_totgst_and_totamt(event){

		let qtydelivered = parseFloat($("#jqGrid2 input[name='qtydelivered']").val());
		let unitprice = parseFloat($("#jqGrid2 input[name='unitprice']").val());
		let amtdisc = parseFloat($("#jqGrid2 input[name='amtdisc']").val());
		let perdisc = parseFloat($("#jqGrid2 input[name='perdisc']").val());
		let gstpercent=parseFloat($("input[name='gstpercent']").val());

		var amount = ((unitprice*qtydelivered) - (amtdisc*qtydelivered) );
		var getDis = amount - (amount*perdisc/100);
		var tot_gst = getDis * (gstpercent / 100);
		var totalAmount = getDis + tot_gst;

		var netunitprice = (unitprice-amtdisc) + ((unitprice-amtdisc) * gstpercent/100);//?
		
		$("input[name='tot_gst']").val(tot_gst);
		$("input[name='amount']").val(totalAmount);

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
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$('#remarks2').val($('#jqGrid3').jqGrid ('getRowData', $(this).data('lineno_')).remarks);
				$("#remarks2").data('lineno_',$(this).data('lineno_'))
				$("#dialog_remarks").dialog( "open" );
			});
		},
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

	var dialog_srcdocno = new ordialog(
		'srcdocno',['material.purordhd h'],'#delordhd_srcdocno',errorField,
		{	colModel:[
				{label:'PO NO',name:'h.purordno',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Supplier Code',name:'h.suppcode',width:400,classes:'pointer',canSearch:true,or_search:true},
				//{label:'Supplier Name',name:'sp.supplier_name',width:400,classes:'pointer'},
				{label:'Request Department',name:'h.reqdept',width:400,classes:'pointer', hidden:true},
				{label:'recno',name:'h.recno',width:400,classes:'pointer', hidden:true},
				{label:'Delivery Department',name:'h.deldept',width:400,classes:'pointer', hidden:true},
				{label:'Record Status',name:'h.recstatus',width:400,classes:'pointer', hidden:true},
				{label:'Amount Discount',name:'h.amtdisc',width:400,classes:'pointer', hidden:true},
				{label:'Sub Amount',name:'h.subamount',width:400,classes:'pointer', hidden:true},
				{label:'Per Disc',name:'h.perdisc',width:400,classes:'pointer', hidden:true},
				{label:'Remarks',name:'h.remarks',width:400,classes:'pointer', hidden:true},
				{label:'Total Amount',name:'h.totamount',width:400,classes:'pointer'},
				{label:'Purchase Department',name:'h.prdept',width:400,classes:'pointer', hidden:true},
				
				],


		ondblClickRow: function () {
				let data = selrowData('#' + dialog_srcdocno.gridname);
				$("#delordhd_srcdocno").val(data['h.purordno']);
				$("#delordhd_suppcode").val(data['h.suppcode']);
				$("#delordhd_credcode").val(data['h.suppcode']);
				$("#delordhd_reqdept").val(data['h.reqdept']);
				$("#delordhd_deldept").val(data['h.deldept']);
				$("#delordhd_prdept").val(data['h.prdept']);
				$("#delordhd_perdisc").val(data['h.perdisc']);
				$("#delordhd_amtdisc").val(data['h.amtdisc']);
				$("#delordhd_totamount").val(data['h.totamount']);
				$("#delordhd_subamount").val(data['h.subamount']);
				$("#delordhd_recstatus").val(data['h.recstatus']);
				$("#delordhd_remarks").val(data['h.remarks']);

				$('#referral').val(data['h.recno']);

				var urlParam2 = {
					action: 'get_value_default',
					field: ['podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.pricecode', 'podt.itemcode', 'p.description', 'podt.uomcode', 'podt.qtyorder','podt.qtydelivered','podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc', 'podt.amtslstax', 'podt.amount','NULL AS remarks_button','podt.remarks','podt.recstatus'],
					table_name: ['material.purorddt podt', 'material.productmaster p', 'hisdb.taxmast t'],
					table_id: 'lineno_',
					join_type: ['LEFT JOIN', 'LEFT JOIN'],
					join_onCol: ['podt.itemcode','podt.taxcode'],
					join_onVal: ['p.itemcode','t.taxcode'],
					filterCol: ['podt.recno', 'podt.compcode', 'podt.recstatus'],
					filterVal: [data['h.recno'], 'session.company', '<>.DELETE']
				};

				$.get("../../../../assets/php/entry.php?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						data.rows.forEach(function(elem) {
							$("#jqGrid2").jqGrid('addRowData', elem['lineno_'] ,
								{
									compcode:elem['compcode'],
									recno:elem['recno'],
									lineno_:elem['lineno_'],
									pricecode:elem['pricecode'],
									itemcode:elem['itemcode'],
									description:elem['description'],
									uomcode:elem['uomcode'],
									qtyorder:elem['qtyorder'],
									qtydelivered:0,
									qtyOutstand:0,
									unitprice:elem['unitprice'],
									taxcode:elem['taxcode'],
									perdisc:elem['perdisc'],
									amtdisc:elem['amtdisc'],
									tot_gst:0,
									amount:elem['amount'],
									remarks_button:null,
									remarks:elem['remarks'],
								}
							);
						});

					} else {

					}
				});


				
			}

		},{
			title:"Select PO No",
			open: function(){
				$("#jqGrid2").jqGrid("clearGridData", true);
				//dialog_purreqno.urlParam.filterCol = ['reqdept'];
				//dialog_purreqno.urlParam.filterVal = [$("#purordhd_reqdept").val()];

				dialog_srcdocno.urlParam.table_id = "none_";
				dialog_srcdocno.urlParam.filterCol = ['h.prdept','h.recstatus', 'h.delordno'];
				dialog_srcdocno.urlParam.filterVal = [$("#delordhd_prdept").val(),'POSTED', '0'];
				dialog_srcdocno.urlParam.join_type = ['LEFT JOIN'];
				dialog_srcdocno.urlParam.join_onCol = ['h.recno'];
				dialog_srcdocno.urlParam.join_onVal = ['d.recno'];
				// dialog_purreqno.urlParam.join_filterCol = [['h.reqdept'],[]];
				// dialog_purreqno.urlParam.join_filterVal = [['skip.d.reqdept'],[]];

				
			}
		},'urlParam'
	);
	dialog_srcdocno.makedialog();

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

	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#delordhd_reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Department",
		}
	);
	dialog_reqdept.makedialog();

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
		'itemcode',['material.stockloc s','material.product p','hisdb.taxmast t','material.uom u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label:'Item Code',name:'s.itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'p.description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Quantity On Hand',name:'s.qtyonhand',width:100,classes:'pointer',},
				{label:'UOM Code',name:'s.uomcode',width:100,classes:'pointer'},
				{label:'Max Quantity',name:'s.maxqty',width:100,classes:'pointer'},
				{label: 'Conversion', name: 'u.convfactor', width: 50, classes: 'pointer', hidden:true },
				{ label: 'rate', name: 't.rate', width: 100, classes: 'pointer',hidden:true },

				
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p.description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#jqGrid2 input[name='taxcode']").val(data['p.TaxCode']);
				$("#jqGrid2 input[name='rate']").val(data['t.rate']);
				$("#convfactor_uom").val(data['u.convfactor']);
				
				getQOHPrDept(true);
			}
		},{
			title:"Select Item For Stock Transaction",
			open:function(){
				dialog_itemcode.urlParam.table_id="none_";
				dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode'];
				dialog_itemcode.urlParam.filterVal=['session.company', moment($('#delordhd_trandate').val()).year(), $('#delordhd_deldept').val()];
				dialog_itemcode.urlParam.join_type=['LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol=['s.itemcode','p.taxcode','u.uomcode'];
				dialog_itemcode.urlParam.join_onVal=['p.itemcode','t.taxcode','s.uomcode'];
				//dialog_itemcode.urlParam.join_filterCol=[['s.compcode', 's.uomcode'], []];
				//dialog_itemcode.urlParam.join_filterVal=[['skip.p.compcode', 'skip.p.uomcode'], []];
				dialog_itemcode.urlParam.join_filterCol = [['s.compcode','s.uomcode'],[]];
				dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode','skip.p.uomcode'],[]];
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

	var dialog_pouom = new ordialog(
		'pouom', ['material.uom'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_pouom.gridname);
				$("#jqGrid2 input[name='pouom']").val(data['uomcode']);
				$("#convfactor_pouom").val(data['convfactor']);
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.company', 'A'];
			},
			close: function () {
				$(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_pouom.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
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