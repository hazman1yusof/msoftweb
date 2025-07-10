$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

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
				console.log(errorField);
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ' '
				}]
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount','#apacthdr_amount','#apacthdr_outamount']);
	var mycurrency2 =new currencymode([]);
	var fdl = new faster_detail_load();
	
	///////////////////////////////// trandate check date validate from period//////////////////////////
	var actdateObj = new setactdate(["#apacthdr_postdate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper=null;
	var unsaved = false,counter_save=0;

	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				duplicate_documentno = false;
				unsaved = false;
				//actdateObj.getdata().set();
				counter_save=0;
				parent_close_disabled(true);
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", true);
					$("#pg_jqGridPager2 table").show();
					enableForm('#formdata');
					rdonly('#formdata');
					hideatdialogForm(true);
					if ($('#apacthdr_trantype').val() == 'DN') {
						$('#apacthdr_doctype').val('Others').hide();
						$('#apacthdr_doctype').val('Supplier').hide();
						$('#apacthdr_doctype').val('Debit_Note').show();
						
					} else {
						$('#apacthdr_doctype').val('Debit_Note').hide();
						$('#apacthdr_doctype').val('Others').show();
						$('#apacthdr_doctype').val('Supplier').show();
						
					}
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
				}
				if(oper!='view'){
					mycurrency.formatOnBlur();
					mycurrency.formatOn();
					dialog_supplier.on();
					dialog_payto.on();
				}
				if(oper!='add'){
					refreshGrid("#jqGrid2",urlParam2);
					dialog_supplier.check(errorField);
					dialog_payto.check(errorField);
				}
			
			},
			beforeClose: function(event, ui){
				mycurrency.formatOff();
				if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false;
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function( event, ui ) {
				//reset balik
				addmore_jqgrid2.state = false;
				addmore_jqgrid2.more = false;
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata',['#apacthdr_source','#apacthdr_trantype']);
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_supplier.off();
				dialog_payto.off();
				$(".noti ol").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
		if($("#recstatus_use").val() == 'POSTED'){
			recstatus_filter = [['OPEN','POSTED']];
			filterCol_urlParam = ['apacthdr.compcode'];
			filterVal_urlParam = ['session.compcode'];
		}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","apacthdr_idno","apacthdr_recstatus",recstatus_filter[0][0]);

	////////////////////padzero///////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
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
				// $('#auditno').text("");//tukar kat depan tu
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			// $('#auditno').text("");//tukar kat depan tu
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}
	
	var urlParam={
		action:'maintable',
		url:'./debitNoteAP/table',
		source:$('#apacthdr_source').val(),
		trantype:$('#apacthdr_trantype').val(),
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'debitNoteAP_save',
		url:'./debitNoteAP/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'apacthdr_auditno',
		filterCol: ['source', 'trantype'],
		filterVal: [$('#apacthdr_source').val(), $('#apacthdr_trantype').val()],
	};

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'Audit <br>No', name: 'apacthdr_auditno', width: 23, classes: 'wrap',formatter: padzero, unformat: unpadzero},
			{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap text-uppercase'},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor', name: 'apacthdr_suppcode', width: 60, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 60, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', canSearch: false, checked: false, hidden:true},
			{ label: 'Document <br> Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Rec Date', name: 'apacthdr_recdate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter, hidden: true,},
			{ label: 'Post Date', name: 'apacthdr_postdate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Document <br> No', name: 'apacthdr_document', width: 28, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Outstanding', name: 'apacthdr_outamount', width: 30 , classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase',},
			{ label: 'Last Payment<br/>Date', name: 'apalloc_allocdate', width: 30, classes: 'wrap text-uppercase',formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Remarks', name: 'apacthdr_remarks', width: 90, hidden:false, classes: 'wrap text-uppercase'},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },	
			{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'unit', name: 'apacthdr_unit', width: 40, hidden:true},
			{ label: 'compcode', name: 'apacthdr_compcode', width: 40, hidden:true},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		loadComplete: function(){
			// calc_jq_height_onchange("jqGrid");
		},
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			let scope = $("#recstatus_use").val();

			urlParam2.source = selrowData("#jqGrid").apacthdr_source;
			urlParam2.trantype = selrowData("#jqGrid").apacthdr_trantype;
			urlParam2.auditno = selrowData("#jqGrid").apacthdr_auditno;

			urlParam2_alloc.source = selrowData("#jqGrid").apacthdr_source;
			urlParam2_alloc.trantype = selrowData("#jqGrid").apacthdr_trantype;
			urlParam2_alloc.auditno = selrowData("#jqGrid").apacthdr_auditno;

			//urlParam2_alloc.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			refreshGrid("#jqGridAlloc",urlParam2_alloc);

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}

			$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);
			$('#apacthdr_idno').val(selrowData("#jqGrid").apacthdr_idno);
			
			refreshGrid("#jqGrid2",urlParam2);

			refreshGrid("#jqGrid3",urlParam2);

			populate_form(selrowData("#jqGrid"));
			//empty_form();
			if_cancel_hide();
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				
				
			}else if (stat == 'OPEN'){
				$("#jqGridPager td[title='Edit Selected Row']").click();

				if (rowid != null) {
					rowData = $('#jqGrid').jqGrid('getRowData', rowid);

				}
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq, #but_post_jq, #but_reopen_jq').hide();
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			$("#searchForm input[name=Stext]").focus();

			if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}

			populate_form(selrowData("#jqGrid"));
			fdl.set_array().reset();
			
			cbselect.refresh_seltbl();
			cbselect.show_hide_table();
			cbselect.checkbox_function_on();
		},
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'apacthdr_amount', 'Amount', { 'text-align': 'right' });
	$("#jqGrid").jqGrid('setLabel', 'apacthdr_outamount', 'Outstanding', { 'text-align': 'right' });

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2",urlParam2);
			
			if(selrowData("#jqGrid").apacthdr_recstatus == 'POSTED'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		}
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit','Checkbox', 'apacthdr_compcode']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////

	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll, #saveDetailLabel").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
		}
	}

	// $("#save").click(function(){
	// 	unsaved = false;
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
	// 	if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
	// 		saveHeader("#formdata", oper,saveParam,{idno:$('#idno').val()},'refreshGrid');
	// 		unsaved = false;
	// 		$("#dialogForm").dialog('close');
	// 	}else{
	// 		mycurrency.formatOn();
	// 	}
	// });
	
	////////////////////selected///////////////

	$('#apacthdr_doctype').on('change', function() {
		let doctype = $("#apacthdr_doctype option:selected").val();
	
		if(doctype == 'Supplier') {
			$('#ap_detail').show();
		}else if (doctype == 'Debit_Note' || doctype == 'Others') {
			$('#ap_detail').show();
		}
		
	});

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	// $("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

	// 	var idno = selrowData('#jqGrid').apacthdr_idno;
	// 	var obj={};
	// 	obj.idno = idno;
	// 	obj._token = $('#_token').val();
	// 	obj.oper = $(this).data('oper')+'_single';

	// 	$.post( './debitNoteAP/form', obj , function( data ) {
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}).fail(function(data) {
	// 		$('#error_infront').text(data.responseText);
	// 	}).success(function(data){
			
	// 	});
	// });

	$("#but_post_jq").click(function(){
		$("#but_post_jq").attr('disabled',true);
		var idno_array = [];
	
		let ids = $('#jqGrid_selection').jqGrid ('getDataIDs');
		for (var i = 0; i < ids.length; i++) {
			var data = $('#jqGrid_selection').jqGrid('getRowData',ids[i]);
	    	idno_array.push(data.apacthdr_idno);
	    }
	    
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( './debitNoteAP/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$("#but_post_jq").attr('disabled',false);
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			$("#but_post_jq").attr('disabled',false);
			
		});
	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.idno = selrowData('#jqGrid').apacthdr_idno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './debitNoteAP/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});
	
	///////////check postdate & docdate///////////////////
	$("#apacthdr_postdate,#apacthdr_actdate").blur(checkdate);

	function checkdate(nkreturn=false){
		var apacthdr_postdate = $('#apacthdr_postdate').val();
		var apacthdr_actdate = $('#apacthdr_actdate').val();

		text_success1('#apacthdr_postdate')
		text_success1('#apacthdr_actdate')
		$("#dialogForm .noti ol").empty();
		var failmsg=[];

		if(moment(apacthdr_postdate).isBefore(apacthdr_actdate)){
			failmsg.push("Post Date cannot be lower than Doc date");
			text_error1('#apacthdr_postdate')
			text_error1('#apacthdr_actdate')
		}

		if(moment(apacthdr_postdate).isAfter(moment())){
			failmsg.push("Post Date cannot be higher than today");
			text_error1('#apacthdr_postdate')
		}

		if(moment(apacthdr_actdate).isAfter(moment())){
			failmsg.push("Doc Date cannot be higher than today");
			text_error1('#apacthdr_actdate')
		}

		if($('#apacthdr_postdate').data('error') != undefined && $('#apacthdr_postdate').data('error') != 'none' && nkreturn==true){
			failmsg.push($('#apacthdr_postdate').data('error'));
			text_error1('#apacthdr_postdate');
			$('#apacthdr_postdate').focus();
		}

		if(failmsg.length){
			failmsg.forEach(function(element){
				$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
			});
			if(nkreturn)return false;
		}else{
			if(nkreturn)return true;
		}

	}
	//////////////////////////////////////////////////////

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
			hideatdialogForm(false);
			addmore_jqgrid2.state = true;
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}

			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu

				$('#apacthdr_auditno').val(data.auditno);
				$('#apacthdr_amount').val(data.totalAmount);
				$('#apacthdr_idno').val(data.idno);

				urlParam2.auditno = data.auditno;

			}else if(selfoper=='edit'){
				urlParam2.auditno = $('#apacthdr_auditno').val();
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

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action: 'get_table_dtl',
		url:'DebitNoteAPDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.taxamt AS tot_gst', 'apdt.dorecno', 'apdt.grnno', 'apdt.idno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source', 'apdt.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', $('#apacthdr_source').val(), 'DN']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteAPDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true},
            { label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: true,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom', editoptions:
                    {  
                        custom_element:deptcodeCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
			{ label: 'Category', name: 'category', width: 150, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{  
						custom_element:categoryCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
            { label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: true,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {
                        custom_element:GSTCodeCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
            { label: 'Amount Before GST', name: 'AmtB4GST', width: 95, classes: 'wrap',
                formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
                editable: true,
                align: "right",
                editrules:{required: true},edittype:"text",
                editoptions:{
                    maxlength: 12,
                    dataInit: function(element) {
                        element.style.textAlign = 'right';
                    }
                },
            },
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 95, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules:{required: true}, edittype:"text",
				editoptions:{
					maxlength: 12,
					dataInit: function(element) {
						element.style.textAlign = 'right';
					}
				},
			},
            { label: 'Amount', name: 'amount', width: 95, classes: 'wrap', 
                formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
                editable: true,
                align: "right",
                editrules:{required: true},edittype:"text",
                editoptions:{
                    readonly: "readonly",
                    maxlength: 12,
                    dataInit: function(element) {
                        element.style.textAlign = 'right';
                    },
                }
            },
            { label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', editable: false, hidden: true },
			{ label: 'No', name: 'lineno_', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', hidden: true },
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
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			setjqgridHeight(data,'jqGrid2');
			//calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_deptcode.check(errorField);
			dialog_category.check(errorField);
			dialog_GSTCode.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	//////////////////////////////////////////myEditOptions details/////////////////////////////////////////////
	
	var myEditOptions_jq2 = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
        	errorField.length=0;
			$("#jqGrid2 input[name='deptcode']").focus().select();
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_deptcode.on();//start binding event on jqgrid2
			dialog_category.on();
			dialog_GSTCode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='AmtB4GST']"]);
			
			$("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2 input[name='AmtB4GST'],#jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

        	$("input[name='amount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
			$('#apacthdr_amount').val(response.responseText);
			mycurrency.formatOn();
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
	    	errorField.length=0;
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {
        	if(errorField.length>0){
				console.log(errorField);
        		return false;
        	}
        	mycurrency2.formatOff();

			// if(parseInt($('#jqGrid2 input[name="amount"]').val()) == 0){
			// 	myerrorIt_only2('#jqGrid2 input[name="amount"]');
			// 	alert('Amount cant be 0');
			// 	return false;
			// }
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./DebitNoteAPDetail/form?"+
				$.param({
					action: 'DebitNoteAPDetail_save',
					idno: $('#apacthdr_idno').val(),
					auditno:$('#apacthdr_auditno').val(),
					amount:data.amount,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm(false);
			//$('#jqGrid2').jqGrid ('setSelection', "1");
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
			addRowParams: myEditOptions_jq2
		},
		editParams: myEditOptions_jq2
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
				    			action: 'DebitNoteAPDetail_save',
								auditno: $('#apacthdr_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
				    		}
				    		$.post( "./DebitNoteAPDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							},'json').fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
								mycurrency.formatOn();
								refreshGrid("#jqGrid2",urlParam2,'add');
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
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_AmtB4GST","#"+ids[i]+"_tot_gst","#"+ids[i]+"_amount"]);
				// cari_gstpercent2(ids[i]);

				dialog_deptcode.id_optid = ids[i];
		        dialog_deptcode.check(errorField,ids[i]+"_deptcode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    dialog_category.id_optid = ids[i];
		        dialog_category.check(errorField,ids[i]+"_category","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    dialog_GSTCode.id_optid = ids[i];
		        dialog_GSTCode.check(errorField,ids[i]+"_GSTCode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

		    }
		   	onall_editfunc();
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
				let retval = check_cust_rules("#jqGrid2",data);
				// console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					mycurrency2.formatOn();
					return false;
				}

				// if(parseInt($("#jqGrid2 input#"+ids[i]+"_amount").val()) == 0){
				// 	alert('Amount cant be 0');
				// 	mycurrency2.formatOn();
				// 	return false;
				// }

		    	var obj = 
		    	{
		    		'lineno_' : ids[i],
		    		'idno' : data.idno,
		    		'deptcode' : $("#jqGrid2 input#"+ids[i]+"_deptcode").val(),
		    		'category' : $("#jqGrid2 input#"+ids[i]+"_category").val(),
					'GSTCode' : $("#jqGrid2 input#"+ids[i]+"_GSTCode").val(),
		    		'AmtB4GST' : $('#jqGrid2 input#'+ids[i]+"_AmtB4GST").val(),
		    		'tot_gst' : $('#jqGrid2 input#'+ids[i]+"_tot_gst").val(),
		    		'amount' : $('#jqGrid2 input#'+ids[i]+"_amount").val(),
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'DebitNoteAPDetail_save',
				_token: $("#_token").val(),
				auditno: $('#apacthdr_auditno').val(),
				idno: $('#apacthdr_idno').val()
    		}

    		$.post( "./DebitNoteAPDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				$('#apacthdr_amount').val(data);
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2,'add');
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2,'add');
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

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	// var amntb4gst = parseFloat($("input[id*='_AmtB4GST']").val());
	// var amount = amntb4gst+(amntb4gst*(rate/100));//.toFixed(2);

	function cari_gstpercent2(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		let gstpercent = 0.00;
		if(data.tot_gst != ''){
			let tot_gst = data.tot_gst;
			let amntb4gst = data.AmtB4GST;
			gstpercent = (tot_gst / amntb4gst) * 100;
		}

		$("#jqGrid2 #"+id+"_gstpercent").val(gstpercent);
	}

	var mycurrency2 =new currencymode([]);
	function calculate_line_totgst_and_totamt(event){

		mycurrency.formatOff();
        mycurrency2.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let amntb4gst = parseFloat($("#jqGrid2 #"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_gstpercent").val());
		var amount = 0;

		if(gstpercent == 0){
			$("#jqGrid2  #"+id_optid+"_tot_gst").prop('disabled',true);
			tot_gst = 0;
			amount = amntb4gst;
		}else{
			$("#jqGrid2 #"+id_optid+"_tot_gst").prop('disabled',false);
			var tot_gst_real = parseFloat($("#jqGrid2 #"+id_optid+"_tot_gst").val());
			var tot_gst_rate = parseFloat(amntb4gst * (gstpercent / 100));

			if(tot_gst_real == tot_gst_rate || tot_gst_real == 0){
				amount = amntb4gst + tot_gst_rate;
				tot_gst = tot_gst_rate;
			}else{
				amount = amntb4gst + tot_gst_real;
				tot_gst = tot_gst_real;
			}

		}

		$("#jqGrid2 #"+id_optid+"_tot_gst").val(tot_gst);

		$("#jqGrid2 #"+id_optid+"_amount").val(amount);

		calculate_total_header();
		
		mycurrency.formatOn();
		mycurrency2.formatOn();
	}

	function calculate_line_totgst_and_totamt2(id_optid) {
		mycurrency.formatOff();
		mycurrency2.formatOff();
		
		let amntb4gst = parseFloat($(id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($(id_optid+"_gstpercent").val());
		var amount = 0;

		if(gstpercent == 0){
			$(id_optid+"_tot_gst").prop('disabled',true);
			tot_gst = 0;
			amount = amntb4gst;
		}else{
			$(id_optid+"_tot_gst").prop('disabled',false);
			var tot_gst_real = parseFloat($(id_optid+"_tot_gst").val());
			var tot_gst_rate = parseFloat(amntb4gst * (gstpercent / 100));

			if(tot_gst_real == tot_gst_rate || tot_gst_real == 0){
				amount = amntb4gst + tot_gst_rate;
				tot_gst = tot_gst_rate;
			}else{
				amount = amntb4gst + tot_gst_real;
				tot_gst = tot_gst_real;
			}
		}

		$(id_optid+"_tot_gst").val(tot_gst);
		
		$(id_optid+"_amount").val(amount);

		calculate_total_header();
		
		mycurrency.formatOn();
		mycurrency2.formatOn();
	}

	function calculate_total_header(){
		var rowids = $('#jqGrid2').jqGrid('getDataIDs');
		var totamt = 0

		rowids.forEach(function(e,i){
			let amt = $('#jqGrid2 input#'+e+'_amount').val();
			if(amt != undefined){
				totamt = parseFloat(totamt)+parseFloat(amt);
			}else{
				let rowdata = $('#jqGrid2').jqGrid ('getRowData',e);
				totamt = parseFloat(totamt)+parseFloat(rowdata.amount);
			}
		});

		if(!isNaN(totamt)){
			$('#apacthdr_amount').val(numeral(totamt).format('0,0.00'));
		}
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='apacthdr_suppcode';break;
			case 'apacthdr_payto':field=['suppcode','name'];table="material.supplier";case_='apacthdr_payto';break;

			//detail
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;

			//alloc
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('debitNoteAP',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	//////////////////////////formatter checkbox//////////////////////////////////////////////////
	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;
		
		if(options.gid == "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Department':temp=$('#jqGrid2 input[name="deptcode"]');break;
			case 'Category':temp=$('#jqGrid2 input[name="category"]');break;
			case 'GST Code':temp=$('#jqGrid2 input[name="GSTCode"]');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function check_cust_rules(grid,data){
		var cust_val =  true;
		Object.keys(data).every(function(v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function deptcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm text-uppercase" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function categoryCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm text-uppercase" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function GSTCodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	

		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="GSTCode" type="text" class="form-control input-sm text-uppercase" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+id_optid+`_gstpercent" name="gstpercent" type="hidden"></div>`);
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	///////////Validation for document number////////////////////////////////////////////////////////
	
	var duplicate_documentno = false;
	$("#apacthdr_document").blur(function(){
		if(oper != 'view'){
			var id = "#apacthdr_document";
			var id2 = "apacthdr_document";
			var param={
				func:'getDocNo',
				action:'get_value_default',
				url: 'util/get_value_default',
				field:['document'],
				table_name:'finance.apacthdr'
			}

			param.filterCol = ['document','recstatus','idno'];
			param.filterVal = [$("#apacthdr_document").val(),'<>.CANCELLED','<>.'+$('#apacthdr_idno').val()];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id2,errorField)!==-1){
						errorField.splice($.inArray(id2,errorField), 1);
					}
					myerrorIt_only2(id,false);
					duplicate_documentno = false;
				} else {
					bootbox.alert("Duplicate Document No");
					$( id ).removeClass( "valid" ).addClass( "error" );
					if($.inArray(id2,errorField)===-1){
						errorField.push( id2 );
					}
					myerrorIt_only2(id,true);
					$(id).data('show_error','Duplicate Document No');
					duplicate_documentno = true;
				}
			});
		}
	});

	function checkduplicate(){
		var id = "#apacthdr_document";
		if(duplicate_documentno){
			bootbox.alert("Duplicate Document No");
			return false;
		}
		return true;
	}

	////////////////////saveheader & savedetail//////////////////////////////////
	$("#saveDetailLabel").click(function () {
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_supplier.off();
		dialog_payto.off();
		errorField.length = 0;
		if(checkduplicate() && checkdate(true) && $('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_supplier.on();
			dialog_payto.on();
		}
	});

	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_supplier.on();
		dialog_payto.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti ol").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){

		$("#jqGridPager2Delete,#saveHeaderLabel").hide();

		$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
			
		});

	});	

	function onall_editfunc(){
		dialog_deptcode.on();//start binding event on jqgrid2
		dialog_category.on();
		dialog_GSTCode.on();
		
		$("#jqGrid2 input[name='amount'], #jqGrid2 input[name='AmtB4GST'], #jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		
	}

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 50,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){
			//calc_jq_height_onchange("jqGrid3");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		onSelectRow: function(data, rowid, selected) {

			// if(rowid != null) {
			// 	var rowData = $('#jqGridAlloc').jqGrid('getRowData', rowid);
			// 	urlParam2_alloc.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			// refreshGrid("#jqGridAlloc",urlParam2_alloc);
				
			//}
		},
		
	});
	jqgrid_label_align_right("#jqGrid3");

		
	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	////////////////////////////////////////////////jqGridAlloc////////////////////////////////////////////////
	var urlParam2_alloc={
		action:'get_alloc_table',
		url:'./debitNoteAP/table',
		source:'',
		trantype:'',
		auditno: ''
	};

	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, 
			{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, 
			{ label: 'Creditor', name: 'suppcode', width: 130, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Document Date', name: 'actdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Post Date', name: 'postdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Alloc Date', name: 'allocdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Document No', name: 'reference', width: 100, classes: 'wrap text-uppercase',},
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2},editrules:{required: false},editoptions:{readonly: "readonly"}},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: true, align: "right"},
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false, align: "right"},
			{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden:true},
			
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPagerAPAlloc",
		loadComplete: function(data){

			setjqgridHeight(data,'jqGridAlloc');
			// calc_jq_height_onchange("jqGridAlloc");
		},
		gridComplete: function(){
			
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridAlloc");

	$("#jqGridAPAlloc_panel").on("show.bs.collapse", function(){
		$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridAPAlloc_c")[0].offsetWidth-$("#jqGridAPAlloc_c")[0].offsetLeft-28));
	});

	////////////////////object for dialog handler///////////////////
	var dialog_supplier = new ordialog(
		'supplier','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_supplier.gridname);
				$("#apacthdr_payto").val(data['SuppCode']);
				dialog_payto.check(errorField);
				$('#apacthdr_postdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#apacthdr_postdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_supplier.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_supplier.urlParam.filterVal=['ACTIVE', 'session.compcode']
			},
			after_check: function(data,obj,id,fail){
				if(!fail){
					let SuppCode = data.rows[0].SuppCode;
					$("#apacthdr_payto").val(SuppCode);
					dialog_payto.check(errorField);
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_supplier.makedialog(true);

	var dialog_payto = new ordialog(
		'payto','material.supplier','#apacthdr_payto',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				$('#apacthdr_actdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_payto.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payto.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab');
	dialog_payto.makedialog(true);

	var dialog_deptcode = new ordialog(
		'department','sysdb.department',"#jqGrid2 input[name='deptcode']",errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_category").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_deptcode").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;

				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.deptcode.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Department Code",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_deptcode.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab');
		dialog_deptcode.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus', 'compcode','source','povalidate'],
				filterVal:['ACTIVE', 'session.compcode','CR','0']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_GSTCode").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_category").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;

				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.catcode.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{	
			title:"Select Category Code",
			open: function(){
				dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
				dialog_category.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '0'];
			},
		},'urlParam','radio','tab'
		
	);
	dialog_category.makedialog(true);

	var dialog_GSTCode = new ordialog(
		'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true, or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus','taxtype'],
				filterVal:['session.compcode','ACTIVE','Input']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				let data=selrowData('#'+dialog_GSTCode.gridname);
				$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
				$("#jqGrid2 #"+id_optid+"_AmtB4GST").focus().select();
				calculate_line_totgst_and_totamt2("#jqGrid2 #"+id_optid);
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_GSTCode").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;

				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.taxcode.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCode.urlParam.filterCol=['compcode','recstatus','taxtype'];
				dialog_GSTCode.urlParam.filterVal=['session.compcode','ACTIVE','Input'];
			},
			check_take_all_field:true,
			after_check: function(data,obj,id){
				var id_optid = id.substring(0,id.search("_"));
				if(data.rows.length>0 && !obj.ontabbing){
					$(id_optid+'_gstpercent').val(data.rows[0].rate);
					calculate_line_totgst_and_totamt2(id_optid);
					calc_jq_height_onchange("jqGrid2");
					$(id_optid+"_AmtB4GST").focus().select();
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCode.makedialog(false);

	//////////list of selection//////
	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'apacthdr_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid_selection");
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	////////////////////////////////////Pager Hide//////////////////////////////////////////////////////////////////////////
	function if_cancel_hide(){
		if(selrowData('#jqGrid').apacthdr_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#jqGridAPAlloc_panel').collapse('hide');
			$('#jqGrid3').hide();
			$('#jqGridAlloc').hide();
			$('#ifcancel_show').text(' - CANCELLED');
			$('#ifcancel_showpv').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
			$('#panel_gridpv').attr('data-target','-');

		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#panel_gridpv').collapse('show');
			$('#jqGrid3').show();
			$('#jqGridAlloc').show();
			$('#ifcancel_show').text('');
			$('#ifcancel_showpv').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
			$('#panel_gridpv').attr('data-target','#jqGrid3_panel');

		}
	}

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){		$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

});

function populate_form(obj){
	//panel header
	$('#trantype_show').text(obj.apacthdr_trantype);
	$('#document_show').text(obj.apacthdr_document);
	$('#suppcode_show').text(obj.supplier_name);

	$('#pvTrantype_show').text(obj.apacthdr_trantype);
	$('#pvDocument_show').text(obj.apacthdr_document);
	$('#pvSuppcode_show').text(obj.supplier_name);
	
	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
		$('#but_cancel_jq').show();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
		$('#but_cancel_jq').hide();
	}
}

function empty_form(){
	$('#trantype_show').text('');
	$('#document_show').text('');
	$('#suppcode_show').text('');

}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
}