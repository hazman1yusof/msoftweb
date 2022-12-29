$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	/////////////////////////validation//////////////////////////
	$.validate({
    	decimalSeparator : ',',
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

	var fdl = new faster_detail_load();
	var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var mycurrency2 =new currencymode([]);
	var actdateObj = new setactdate(["input[name='apacthdr_entrydate']"]);
	actdateObj.getdata().set();

	////////////////////////////source and trantype change////////////////////////////////

	$('#apacthdr_source').on('change', function() {
		if($("#apacthdr_trantype option:selected").val('PD')){
			urlParam_hdr.table_name='finance.apacthdr';
			urlParam_hdr.table_id='auditno';
			urlParam_hdr.field=['source','trantype'];
			refreshGrid('#manualAllochdr',urlParam_hdr);

		}else if($("#apacthdr_trantype option:selected").val('CN')){
			urlParam_hdr.table_name='finance.apacthdr';
			urlParam_hdr.table_id='auditno';
			urlParam_hdr.field=['source','trantype'];
			refreshGrid('#manualAllochdr',urlParam_hdr);
		}
	});

	//var err_reroll = new err_reroll('#manualAllochdr',['actdate']);
	///////////////////////////////////////////trantype//////////////////////
	var urlParam_hdr={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'finance.apacthdr',
		table_id:'auditno',
		filterCol:['source','trantype', 'outamount'],
		filterVal:[$('#apacthdr_source').val(),$('#apacthdr_trantype').val(), '>.0']
	}

	var addmore_manualAllochdr={more:false,state:true,edit:false}

	$("#manualAllochdr").jqGrid({
		datatype: "local",
		editurl: "./manualAlloc/form",
		 colModel: [
			{label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{label: 'Audit <br> No', name: 'auditno', width: 25, classes: 'wrap', hidden:false, editable:false},
			{label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', hidden:false, formatter: showdetail, unformat:un_showdetail, editable:false},
			{label: 'Doc Date', name: 'actdate', width: 100, classes: 'wrap', editable:false,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
						dataInit: function (element) {
							$(element).datepicker({
								id: 'expdate_datePicker',
								dateFormat: 'dd/mm/yy',
								minDate: "dateToday",
								//showOn: 'focus',
								changeMonth: true,
								changeYear: true,
								onSelect : function(){
									$(this).focus();
								}
							});
						}
					}
				},
			{label: 'Remark', name: 'remarks', width: 130, classes: 'wrap', hidden:false, editable:false},
			{label: 'Amount', name: 'amount', width: 45, classes: 'wrap', hidden:false, editable:false, align:"right", formatter:'currency'},
			{label: 'Out Amount', name: 'outamount', width: 45, classes: 'wrap', hidden:false, editable:false, align:"right", formatter:'currency'},
			{label: 'Post Date', name: 'recdate', width: 100, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
						dataInit: function (element) {
							$(element).datepicker({
								id: 'expdate_datePicker',
								dateFormat: 'dd/mm/yy',
								minDate: "dateToday",
								//showOn: 'focus',
								changeMonth: true,
								changeYear: true,
								onSelect : function(){
									$(this).focus();
								}
							});
						}
					}
				},
			{label: 'Total Paid', name: 'updepisode', width: 45, classes: 'wrap', hidden:false, editable:false, align:"right"},
		],
		autowidth:true,
		multiSort: true,
		shrinkToFit: true,
		viewrecords: true,
		width: 900,
		height: 200,
		rowNum: 20,
		pager: "#manualAllocpg",
		gridComplete: function(rowid){
			$("#manualAllochdr").setSelection($("#manualAllochdr").getDataIDs()[0]);
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#manualAllochdr').jqGrid ('getRowData', rowid);
				//urlParam_dtl.auditno=selrowData("#manualAllocdtl").auditno;
				refreshGrid('#manualAllocdtl', urlParam_dtl,'kosongkan');
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper=='view'){
				return false;
			}
		},
		loadComplete: function(){
			if(addmore_manualAllochdr.more == true){
				$('#manualAllochdr_iledit').click();
			}else{
				$('#manualAllochdr').jqGrid ('setSelection', "1");
			}
			addmore_manualAllochdr.edit = addmore_manualAllochdr.more = false; //reset
			//calc_jq_height_onchange("manualAllochdr");
		},
	});

	addParamField('#manualAllochdr',true,urlParam_hdr,['hdrtype','updpayername','depccode','depglacc','updepisode']);
	/////////////////////////////////////////End Transaction type////////////////////////////

	//////////////////////////////////////////myEditOptions for alloc hdr/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#manualAllocpgDelete").hide();
		
			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#manualAllochdr input[name='amount']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='actdate']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#manualAllocpg_ilsave').click();
			});
			$("#manualAllochdr input[type='text']").on('focus',function(){
				$("#manualAllochdr input[type='text']").parent().removeClass( "has-error" );
				$("#manualAllochdr input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			addmore_manualAllochdr.more=true; //only addmore after save inline
			refreshGrid('#manualAllochdr',urlParam_hdr,'edit');
			$("#manualAllocpgDelete,#manualAllocpgRefresh").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert(response.responseText);
			refreshGrid('#manualAllochdr',urlParam_hdr,'edit');
			$("#manualAllocpgDelete,#manualAllocpgRefresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			mycurrency2.formatOff();
			let data = $('#manualAllochdr').jqGrid ('getRowData', rowid);
			let editurl = "./manualAlloc/form?"+
				$.param({
					action: 'manualAlloc_save',
					oper: 'edit',
					//auditno: selrowData('#manualAllochdr').auditno,
				});
			$("#manualAllochdr").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#manualAllochdr',urlParam_hdr,'edit');
			$("#manualAllocpgDelete,#manualAllocpgRefresh").show();
		}
	};

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	jqgrid_label_align_right("#manualAllochdr");

	//////////////////////////////////////////pager manual Allochdr/////////////////////////////////////////////

		$("#manualAllochdr").inlineNav('#manualAllocpg',{	
			add:false,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions
			},
			editParams: myEditOptions
		}).jqGrid('navButtonAdd',"#manualAllocpg",{
			id: "manualAllocpgDelete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#manualAllochdr").jqGrid ('getGridParam', 'selrow');
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
					    			action: 'manualAlloc_save',
									idno: selrowData('#manualAllochdr').idno,
					    		}
					    		$.post( "./manualAlloc/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#manualAllochdr",urlParam_hdr);
								});
					    	}else{
	        					$("#manualAllocpgEditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd', "#manualAllocpg", {
			id: "manualAllocpgRefresh",
			caption: "", cursor: "pointer", position: "last",
			buttonicon: "glyphicon glyphicon-refresh",
			title: "Refresh Table",
			onClickButton: function () {
				refreshGrid("#manualAllochdr", urlParam_hdr);
			},
		});
	
	///////////////////////////////////////manualAlloc detail//////////////////////////////////////////////////////////////////////////

	var urlParam_dtl={
		action:'get_alloc_table',
		url:'paymentVoucher/table',
		auditno:'',
	};

	$("#manualAllocdtl").jqGrid({
		datatype: "local",
		editurl: "./manualAllocDetail/form",
		colModel: [
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap',},
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false},
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, 
			{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, 
		
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
		pager: "#manualAllocdtlpg",
		loadComplete: function(data){
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
		}
	});

	//////////////////////////////////////////myEditOptions2 for manualAlloc detail/////////////////////////////////////////////
	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#manualAllocdtlpgDelete,#manualAllocdtlpgRefresh").hide();
			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#manualAllocdtl input[name='stfamount']","#manualAllocdtl input[name='amount']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='stfamount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#manualAllocdtl_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_manualAllocdtl.state==true)addmore_manualAllocdtl.more=true; //only addmore after save inline
			refreshGrid('#manualAllocdtl',urlParam_dtl,'add');
			$("#manualAllocdtlpgDelete,#jmanualAllocdtlpgRefresh").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert(response.responseText);
			refreshGrid('#manualAllocdtl',urlParam_dtl,'add');
			$("#jmanualAllocdtlpgDelete,#manualAllocdtlpgRefresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false; 

			mycurrency2.formatOff();
			let data = $('#manualAllocdtl').jqGrid ('getRowData', rowid);
			let editurl = "./manualAllocDetail/form?"+
				$.param({
					action: 'manualAllocdtl_save',
					oper: 'add',
				});
			$("#manualAllocdtl").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			
		}
	};
	
		////////////////////////////////////////pager manualAllocdtl/////////////////////////////////////////////

		$("#manualAllocdtl").inlineNav('#manualAllocdtlpg',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions2
			},
			editParams: myEditOptions2
		}).jqGrid('navButtonAdd',"#manualAllocdtlpg",{
			id: "manualAllocdtlpgDelete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				selRowId = $("#manualAllocdtl").jqGrid ('getGridParam', 'selrow');
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
					    			action: 'manualAllocdtl_save',
									idno: selrowData('#manualAllocdtl').idno,

					    		}
					    		$.post( "./manualAllocDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									refreshGrid("#manualAllocdtl",urlParam_dtl);
								});
					    	}else{
	        					$("#manualAllocdtlpgEditAll").show();
					    	}
					    }
					});
				}
			},
		}).jqGrid('navButtonAdd',"#manualAllocdtlpg",{
			id: "manualAllocdtlpgCancelAll",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-remove-circle",
			title:"Cancel",
			onClickButton: function(){
				hideatdialogForm_jqGrid3(false);
				refreshGrid("#manualAllocdtl",urlParam_dtl);
			},	
		}).jqGrid('navButtonAdd', "#manualAllocdtlpg", {
			id: "manualAllocdtlpgRefresh",
			caption: "", cursor: "pointer", position: "last",
			buttonicon: "glyphicon glyphicon-refresh",
			title: "Refresh Table",
			onClickButton: function () {
				refreshGrid("#manualAllocdtl", urlParam_dtl);
			},
		});


	// //////////////////////////////////////////////////////////////
	
	// dialog_dept=new makeDialog('sysdb.department','#dept',['deptcode','description'], 'Department');

	////////////////////////////////////start dialog//////////////////////////////////////
	
	function saveFormdata(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){

		var formname = $("a[aria-expanded='true']").attr('form')

		var paymentform =  $( formname ).serializeArray();

		$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
		saveParam.oper=oper;

		let serializedForm = trimmall(form,uppercase);

		$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform) , function( data ) {
			
		}).fail(function(data) {
			errorText(dialog.substr(1),data.responseText);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
		}).success(function(data){
			if(grid!=null){
				refreshGrid(grid,urlParam,oper);
				$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
				$(dialog).dialog('close');
				if (callback !== undefined) {
					callback();
				}
			}
		});
	}


	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_receipt("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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

	$("input[name=dbacthdr_entrydate]").keydown(false);

	////////////////////////////////////start dialog////////////////////////////////////
	var oper = 'add';

	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			$("#manualAllocdtl").jqGrid ('setGridWidth', Math.floor($("#manualAllocdtl_c")[0].offsetWidth-$("#manualAllocdtl_c")[0].offsetLeft));
			$("#manualAllochdr").jqGrid ('setGridWidth', Math.floor($("#manualAllochdr_c")[0].offsetWidth-$("#manualAllochdr_c")[0].offsetLeft));
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
		
			$('.alert').detach();
			
			$("#formdata a").off();
			$("#refresh_jqGrid").click();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./manualAlloc/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	// var saveParam={	
	// 	action:'receipt_save',
	// 	url: 'receipt/form',
	// 	oper:'add',
	// 	field:'',
	// 	table_name:'debtor.dbacthdr',
	// 	table_id:'auditno',
	// 	fixPost:true,
	// 	skipduplicate: true,
	// 	returnVal:true,
	// 	sysparam:{source:'PB',trantype:'RC',useOn:'auditno'}
	// };
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 70, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 18, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'Transaction <br>Type', name: 'apacthdr_trantype', width: 25, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 30, classes: 'wrap text-uppercase', hidden:false},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 50, classes: 'wrap', hidden:true, canSearch: true},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Unit', name: 'apacthdr_unit', width: 30, hidden:false},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: true},
			{ label: 'Category Code', name: 'apacthdr_category', width: 40, hidden:false, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},		
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Outamount', name: 'apacthdr_outamount', width: 25, hidden:false, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', checked: true, hidden: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Post Date', name: 'apacthdr_recdate', width: 35, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter, hidden:true},
			{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'paymode', name: 'apacthdr_paymode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'trantype2', name: 'apacthdr_trantype2', width: 50, classes: 'wrap', hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortorder:'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		onSelectRow: function(rowid){
			// allocate("#jqGrid");
		},
		gridComplete: function(){
			// $('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid");
		}
		
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
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
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
	populateSelect('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
//	addParamField('#jqGrid',false,saveParam,['patmast_name','dbacthdr_idno','dbacthdr_amount']);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='apacthdr_suppcode';break;
			case 'apacthdr_category':field=['catcode','description'];table="material.category";case_='apacthdr_category';break;
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('manualAlloc',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy() {
		$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
			if (value['canSearch']) {
				if (value['selected']) {
					$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
				} else {
					$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
				}
			}
			searchClick2('#jqGrid', '#searchForm', urlParam);
		});
	}

	$('#Status').on('change', searchChange);

	function searchChange(){
		var arrtemp = [$('#Status option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ap.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	var payer_search = new ordialog(
		'payer_search', 'debtor.debtormast', '#payer_search', 'errorField',
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + payer_search.gridname).debtorcode;

				if($('#Scol').val() == 'db_debtorcode'){
					urlParam.searchCol=["db.debtorcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'db_payercode'){
					urlParam.searchCol=["db.payercode"];
					urlParam.searchVal=[data];
				}
				refreshGrid('#jqGrid', urlParam);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Payer",
			open: function () {
				payer_search.urlParam.filterCol = ['recstatus'];
				payer_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	payer_search.makedialog(true);
	$('#payer_search').on('keyup',ifnullsearch);

	function ifnullsearch(){
		if($('#payer_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','payer_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}	