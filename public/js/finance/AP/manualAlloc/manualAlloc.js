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
	var myfail_msg = new fail_msg_func();

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["input[name='apacthdr_entrydate']", "#recdate"]);
	actdateObj.getdata().set();

	///////////////////////////////////backdated////////////////////////////////////////////////

	var backdated = new func_backdated('#recdate');
	backdated.getdata();

	function func_backdated(target){
		this.sequence_data;
		this.target=target;
		this.param={
			action:'get_value_default',
			url:"util/get_value_default",
			field: ['*'],
			table_name:'material.sequence',
			table_id:'idno',
			filterCol:['trantype'],
			filterVal:['AL'],
		}

		this.getdata = function(){
			var self=this;
			$.get( this.param.url+"?"+$.param(this.param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					self.sequence_data = data.rows;
				}
			});
			return this;
		}

		this.set_backdate = function(dept){
			$.each(this.sequence_data, function( index, value ) {
				if(value.dept == dept){
					var backday =  value.backday;
					var backdate = moment().subtract(backday, 'days').format('YYYY-MM-DD');
					$('#recdate').attr('min',backdate);
				}
			});
		}
	}

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
			
			urlParam_hdr.source=$('#apacthdr_source').val();
			urlParam_hdr.trantype=$('#apacthdr_trantype').val();

			refreshGrid('#manualAllochdr',urlParam_hdr);
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
	
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 70, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 18, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: false},
			{ label: 'Transaction <br>Type', name: 'apacthdr_trantype', width: 25, classes: 'wrap text-uppercase', canSearch: false},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 30, classes: 'wrap text-uppercase', hidden:false},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 50, classes: 'wrap', hidden:true, canSearch: false},
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
		},
		gridComplete: function(){
			// $('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();

			if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
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

	////////////////////////////source and trantype change////////////////////////////////

	$('#apacthdr_source,#apacthdr_trantype').on('change', function() {
		urlParam_hdr.source=$('#apacthdr_source').val();
		urlParam_hdr.trantype=$('#apacthdr_trantype').val();

		refreshGrid('#manualAllochdr',urlParam_hdr);
	});

	///////////////////////////////////////////trantype//////////////////////
	var urlParam_hdr={
		action:'get_alloc_table_hdr',
		url:'./manualAlloc/table',
		field:'',
		table_name:'finance.apacthdr',
		table_id:'auditno',
		source:$('#apacthdr_source').val(),
		trantype:$('#apacthdr_trantype').val(),
		filterCol:['source','trantype', 'outamount','recstatus'],
		filterVal:[$('#apacthdr_source').val(),$('#apacthdr_trantype').val(), '>.0','APPROVED']
	}

	var addmore_manualAllochdr={more:false,state:true,edit:false}

	$("#manualAllochdr").jqGrid({
		datatype: "local",
		editurl: "./manualAlloc/form",
		 colModel: [
			{label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
			{label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{label: 'TT', name: 'trantype', width: 25, classes: 'wrap'},
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
			$('#manualAllochdr tr#'+$(this).data('lastselrow')).focus().click();
		},
		onSelectRow:function(rowid){
			if(rowid != null) {
				let rowData = $('#manualAllochdr').jqGrid ('getRowData', rowid);
				urlParam_dtl.suppcode=rowData.suppcode;
				urlParam_dtl.auditno=rowData.auditno;
				refreshGrid('#manualAllocdtl', urlParam_dtl);
			}
			$(this).data('lastselrow',rowid);
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
			// $("#manualAllocpgDelete,#manualAllocpgRefresh").show();
		}, 
		errorfunc: function(rowid,response){
			errorField.length=0;
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
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
			myfail_msg.clear_fail();
			errorField.length=0;
			refreshGrid('#manualAllochdr',urlParam_hdr,'edit');
			// $("#manualAllocpgDelete,#manualAllocpgRefresh").show();
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
	})
	// .jqGrid('navButtonAdd',"#manualAllocpg",{
	// 	id: "manualAllocpgDelete",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-trash",
	// 	title:"Delete Selected Row",
	// 	onClickButton: function(){
	// 		selRowId = $("#manualAllochdr").jqGrid ('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			bootbox.alert('Please select row');
	// 		}else{
	// 			bootbox.confirm({
	// 				message: "Are you sure you want to delete this row?",
	// 				buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
	// 				},
	// 				callback: function (result) {
	// 					if(result == true){
	// 						param={
	// 							action: 'manualAlloc_save',
	// 							idno: selrowData('#manualAllochdr').idno,
	// 						}
	// 						$.post( "./manualAlloc/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
	// 						}).fail(function(data) {
	// 							//////////////////errorText(dialog,data.responseText);
	// 						}).done(function(data){
	// 							refreshGrid("#manualAllochdr",urlParam_hdr);
	// 						});
	// 					}else{
	// 						$("#manualAllocpgEditAll").show();
	// 					}
	// 				}
	// 			});
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#manualAllocpg", {
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
		url:'manualAlloc/table',
		auditno:'',
	};

	$("#manualAllocdtl").jqGrid({
		datatype: "local",
		editurl: "./manualAlloc/form",
		colModel: [
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Invoice Date', name: 'actdate', width: 100, classes: 'wrap'},
			{ label: 'Invoice No', name: 'document', width: 100, classes: 'wrap'},
			{ label: 'Amount', name: 'amount', width: 100,align: "right", classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, 
				editable: true,editrules: { required: true },editoptions:{readonly: "readonly"}, 
			},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', 
				formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2},
				editable:true,editrules: { required: true },editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, align: "right", classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,editrules: { required: true }
			},
			{ label: 'Balance', name: 'balance', width: 100, align: "right", classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,editrules: { required: true },editoptions:{readonly: "readonly"}
			},
			{ label: 'Allocate Date', name: 'allocdate', width: 100, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editoptions: {
						dataInit: function (element) {
							$(element).datepicker({
								id: 'expdate_datePicker',
								dateFormat: 'dd/mm/yy',
								//showOn: 'focus',
								changeMonth: true,
								changeYear: true,
								onSelect : function(){
									$(this).focus();
								}
							}).datepicker("setDate", "0");
						}
					}
			},
			{ label: 'id', name: 'idno', width: 80, classes: 'wrap', hidden:true,key:true}, 
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#manualAllocdtlpg",
		loadComplete: function(data){
		},
		ondblClickRow:function(rowid){
			if($('#manualAllocdtl_iledit').is(":visible")){
				$('#manualAllocdtl_iledit').click();
				$('#manualAllocdtl').data('lastselrow',rowid);
			}
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
			myfail_msg.clear_fail();
			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#manualAllocdtl input[name='balance']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("#manualAllocdtl input[name='allocamount']").on('blur',{currency: [mycurrency2]},calculate_line_balance);

			$("input[name='stfamount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#manualAllocdtl_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// if(addmore_manualAllocdtl.state==true)addmore_manualAllocdtl.more=true; //only addmore after save inline
			refreshGrid('#manualAllochdr',urlParam_hdr,'edit');
			// $("#manualAllocdtlpgDelete,#jmanualAllocdtlpgRefresh").show();
		}, 
		errorfunc: function(rowid,response){
			errorField.length=0;
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
		},
		beforeSaveRow: function(options, rowid) {
			if(myfail_msg.fail_msg_array.length>0){
				return false;
			}

			mycurrency2.formatOff();
			let data = $('#manualAllocdtl').jqGrid ('getRowData', rowid);
			let editurl = "./manualAlloc/form?"+
				$.param({
					idno_doc: selrowData('#manualAllochdr').idno,
					action: 'manualAllocdtl_save',
				});
			$("#manualAllocdtl").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			// refreshGrid('#manualAllocdtl',urlParam_dtl,'add');
		}
	};

	function calculate_line_balance(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
       
		let src_outamount = parseFloat(selrowData('#manualAllochdr').outamount);

		let allocamount = parseFloat($("#"+id_optid+"_allocamount").val());
		let outamount = parseFloat($("#"+id_optid+"_outamount").val());

		var balance = outamount - allocamount;

		$("#"+id_optid+"_balance").val(balance);

		if(allocamount > src_outamount){
        	myfail_msg.add_fail({
				id:'src_outamount',
				textfld:"#"+id_optid+"_allocamount",
				msg:"Allocate amount cant be greater than header document outamount",
			});
		}else{
        	myfail_msg.del_fail({
				id:'src_outamount',
				textfld:"#"+id_optid+"_allocamount",
				msg:"Allocate amount cant be greater than header document outamount",
			});
		}

		if(balance<0){
        	myfail_msg.add_fail({
				id:'balance',
				textfld:"#"+id_optid+"_allocamount",
				msg:"Allocate amount cant be greater than detail document outamount",
			});
		}else{
			myfail_msg.del_fail({
				id:'balance',
				textfld:"#"+id_optid+"_allocamount",
				msg:"Allocate amount cant be greater than document outamount",
			});
		}

		event.data.currency.forEach(function(element){
			element.formatOn();
		});
	}
	
	////////////////////////////////////////pager manualAllocdtl/////////////////////////////////////////////

	$("#manualAllocdtl").inlineNav('#manualAllocdtlpg',{	
		add:false,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions2
		},
		editParams: myEditOptions2
	})
	// .jqGrid('navButtonAdd',"#manualAllocdtlpg",{
	// 	id: "manualAllocdtlpgDelete",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-trash",
	// 	title:"Delete Selected Row",
	// 	onClickButton: function(){
	// 		selRowId = $("#manualAllocdtl").jqGrid ('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			bootbox.alert('Please select row');
	// 		}else{
	// 			bootbox.confirm({
	// 				message: "Are you sure you want to delete this row?",
	// 				buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
	// 				},
	// 				callback: function (result) {
	// 					if(result == true){
	// 						param={
	// 							action: 'manualAllocdtl_save',
	// 							idno: selrowData('#manualAllocdtl').idno,

	// 						}
	// 						$.post( "./manualAlloc/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
	// 						}).fail(function(data) {
	// 							//////////////////errorText(dialog,data.responseText);
	// 						}).done(function(data){
	// 							refreshGrid("#manualAllocdtl",urlParam_dtl);
	// 						});
	// 					}else{
	// 						$("#manualAllocdtlpgEditAll").show();
	// 					}
	// 				}
	// 			});
	// 		}
	// 	},
	// })
	// .jqGrid('navButtonAdd',"#manualAllocdtlpg",{
	// 	id: "manualAllocdtlpgCancelAll",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-remove-circle",
	// 	title:"Cancel",
	// 	onClickButton: function(){
	// 		hideatdialogForm_jqGrid3(false);
	// 		refreshGrid("#manualAllocdtl",urlParam_dtl);
	// 	},	
	// })
	.jqGrid('navButtonAdd', "#manualAllocdtlpg", {
		id: "manualAllocdtlpgRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#manualAllocdtl", urlParam_dtl);
		},
	});

	////// Cn
	var urlParam2_cn={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.taxamt as tot_gst','apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source', 'apdt.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'AP', 'CN']
		
	};
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

	////////////////////////////////searching/////////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#actdate_search').on('click', searchDate);

	function whenchangetodate() {
		creditor_search.off();
		$('#creditor_search,#actdate_from,#actdate_to').val('');
		$('#creditor_search_hb').text('');
		urlParam.filterdate = null;
		removeValidationClass(['#creditor_search']);
		if($('#Scol').val()=='apacthdr_actdate'){
			$("input[name='Stext'], #creditor_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'apacthdr_suppcode' || $('#Scol').val() == 'apacthdr_payto'){
			$("input[name='Stext'],#actdate_text").hide("fast");
			$("#creditor_text").show("fast");
			creditor_search.on();
		} else {
			$("#creditor_text,#actdate_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
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

	function searchDate(){
		urlParam.filterdate = [$('#actdate_from').val(),$('#actdate_to').val()];
		refreshGrid('#jqGrid',urlParam);
	}

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

	var creditor_search = new ordialog(
		'creditor_search', 'material.supplier', '#creditor_search', 'errorField',
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + creditor_search.gridname).suppcode;

				if($('#Scol').val() == 'apacthdr_suppcode'){
					urlParam.searchCol=["ap.suppcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'apacthdr_payto'){
					urlParam.searchCol=["ap.payto"];
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
			title: "Select Creditor",
			open: function () {
				creditor_search.urlParam.filterCol = ['recstatus'];
				creditor_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	creditor_search.makedialog(true);
	$('#creditor_search').on('keyup',ifnullsearch);

	function ifnullsearch(){
		if($('#creditor_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','creditor_search');
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