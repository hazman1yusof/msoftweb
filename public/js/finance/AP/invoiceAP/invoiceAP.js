$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount','#apacthdr_amount','#apactdtl_outamt']);
	var mycurrency2 =new currencymode([]);
	var fdl = new faster_detail_load();
	var myattachment = new attachment_page("invoiceap","#jqGrid","apacthdr_idno");
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
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
				unsaved = false;
				actdateObj.getdata().set();
				counter_save=0;
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				$("#jqGrid2_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_oth_c")[0].offsetWidth-$("#jqGrid2_oth_c")[0].offsetLeft));
				$('select#apacthdr_doctype').prop('disabled',false);
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					hideatdialogForm_oth(true);
					enableForm('#formdata');
					rdonly('#formdata');
					if ($('#apacthdr_trantype').val() == 'DN') {
						$('#apacthdr_doctype').val('Others').hide();
						$('#apacthdr_doctype').val('Supplier').hide();
						$('#save').show();
						$('#ap_detail').hide();
					} else {
						$('#apacthdr_doctype').val('Others').show();
						$('#apacthdr_doctype').val('Supplier').show();
						$('#save').hide();
						$('#ap_detail').show();
					}
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					hideatdialogForm_oth(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					break;
				}
				if(oper!='view'){
					dialog_supplier.on();
					dialog_payto.on();
					dialog_category.on();
					dialog_department.on();
				}
				if(oper!='add'){
					refreshGrid("#jqGrid2",urlParam2);
					refreshGrid("#jqGrid2_oth",urlParam2_oth);
					dialog_supplier.check(errorField);
					dialog_payto.check(errorField);
					dialog_category.check(errorField);
					dialog_department.check(errorField);

					$('select#apacthdr_doctype').prop('disabled',true);
				}
				init_jq2();
			},
			beforeClose: function(event, ui){
				mycurrency.formatOff();
				if($('#apactdtl_outamt').val() != $('#apacthdr_amount').val()  && $('#apacthdr_doctype').val() == "Supplier" && counter_save==0 && oper!='view'){
					event.preventDefault();
					bootbox.confirm({
					    message: "Total Detail Amount is not equal with Invoice Amount. <br> Do you want to proceed?",
					    buttons: { confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					    },
					    callback: function (result) {
					    	if(result == true){
								counter_save=1;
								$("#dialogForm").dialog('close');
					    	}else{
					    		if($('#saveHeaderLabel').is(":visible")){
					    			$("#saveHeaderLabel").click();
					    		}
					    		mycurrency.formatOn();
					    	}
					    }
					});
				}else if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false;
							delete_dd($('#apacthdr_idno').val());
							$("#dialogForm").dialog('close');
						}
					});
				}
				
			},
			close: function( event, ui ) {
				addmore_jqgrid2.state = false;
				addmore_jqgrid2.more = false;
				addmore_jqgrid2_oth.state = false;
				addmore_jqgrid2_oth.more = false;
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata',['#apacthdr_source','#apacthdr_trantype']);
				emptyFormdata(errorField,'#formdata2');
				emptyFormdata(errorField,'#formdata2_oth');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_supplier.off();
				dialog_payto.off();
				dialog_category.off();
				dialog_department.off();
				$(".noti, .noti2 ol").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				//radbuts.reset();
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
	
	var urlParam={
		action:'maintable',
		url:'./invoiceAP/table',
		source:$('#apacthdr_source').val(),
		trantype:$('#apacthdr_trantype').val(),
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'invoiceAP_save',
		url:'./invoiceAP/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'apacthdr_auditno',
		filterCol: ['source', 'trantype'],
		filterVal: [$('#apacthdr_source').val(), $('#apacthdr_trantype').val()],
	};

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

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 23, classes: 'wrap text-uppercase',formatter: padzero, unformat: unpadzero},
			{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap text-uppercase'},
			{ label: 'Doc<br/>Type', name: 'apacthdr_doctype', width: 20, classes: 'text-uppercase', hidden:false},
			{ label: 'Creditor', name: 'apacthdr_suppcode', width: 62, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Creditor Name', name: 'supplier_name', width: 40, classes: 'wrap text-uppercase', checked: true, hidden:true},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase',canSearch: true, hidden:true},
			{ label: 'Document<br/> Date', name: 'apacthdr_actdate', width: 28, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Post<br/> Date', name: 'apacthdr_postdate', width: 28, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Rec<br/> Date', name: 'apacthdr_recdate', width: 28, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter, hidden:true},
			{ label: 'Document No', name: 'apacthdr_document', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 32, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'apacthdr_category', width: 32, hidden:false, classes: 'wrap',formatter: showdetail, unformat:un_showdetail},
			{ label: 'Amount', name: 'apacthdr_amount', width: 28, classes: 'wrap text-uppercase',align: 'right', formatter:'currency'},
			{ label: 'outamt', name: 'apactdtl_outamt', width: 25 , classes: 'wrap text-uppercase',hidden:true,},
			{ label: 'Outstanding', name: 'apacthdr_outamount', width: 32, classes: 'wrap text-uppercase',align: 'right',formatter:'currency'},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 28, classes: 'wrap text-uppercase',},
			{ label: 'Last Payment<br/>Date', name: 'apalloc_allocdate', width: 30, classes: 'wrap text-uppercase',formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Remarks', name: 'apacthdr_remarks', width: 40, hidden:false, classes: 'text-uppercase'},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 15,align: "center", formatter: formatterCheckbox },	
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:'true'},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:'true', key:true},
			{ label: 'unit', name: 'apacthdr_unit', width: 40, hidden:'true'},
			{ label: 'compcode', name: 'apacthdr_compcode', width: 40, hidden:'true'},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'apacthdr_idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",

		loadComplete: function(){
		},
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			$('#save').hide();
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			let scope = $("#recstatus_use").val();

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid2', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}

			$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);
			$('#apacthdr_idno').val(selrowData("#jqGrid").apacthdr_idno);

			urlParam2.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			urlParam2_oth.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			urlparampv.idno=selrowData("#jqGrid").apacthdr_idno;
			populate_form(selrowData("#jqGrid"));
			if_cancel_hide();
			refreshGrid("#jqgridpv",urlparampv);

			$('#jqGrid3_c,#jqGrid3_oth_c').hide();
			if(selrowData("#jqGrid").apacthdr_doctype == 'Supplier'){
				$('#jqGrid3_c').show();
				refreshGrid("#jqGrid3",urlParam2);
			}else{
				$('#jqGrid3_oth_c').show();
				refreshGrid("#jqGrid3_oth",urlParam2_oth,'add');
			}

			$("#attcahment_page").attr('href','./attachment_upload/?page=invoiceap&idno='+selrowData("#jqGrid").apacthdr_idno);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				//$('#save').hide();
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
	jqgrid_label_align_right("#jqGrid");
	jqgrid_label_align_right("#jqGrid2");

	$("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Qty On Hand', { 'text-align': 'right' });
	/////////////////////////////////////////////////////
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
			refreshGrid("#jqGrid2",urlParam2,'add');
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
			refreshGrid("#jqGrid2",urlParam2,'add');

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
	addParamField('#jqGrid', false, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit', 'apacthdr_idno','Checkbox','apacthdr_compcode']);

	// $("#save").click(function(){
	// 	unsaved = false;
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
	// 	if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
	// 		saveHeader("#formdata", oper,saveParam,{idno:$('#apacthdr_idno').val()},'refreshGrid');
	// 		unsaved = false;
	// 		$("#dialogForm").dialog('close');
	// 	}else{
	// 		mycurrency.formatOn();
	// 	}
	// });

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

	function hideatdialogForm_oth(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_oth_iledit,#jqGrid2_oth_iladd,#jqGrid2_oth_ilcancel,#jqGrid2_oth_ilsave,#saveHeaderLabel_oth,#jqGridPager2Delete_oth,#jqGridPager2EditAll_oth,#saveDetailLabel_oth").hide();
			$("#jqGridPager2SaveAll_oth,#jqGridPager2CancelAll_oth").show();
		}else if(hide){
			$("#jqGrid2_oth_iledit,#jqGrid2_oth_iladd,#jqGrid2_oth_ilcancel,#jqGrid2_oth_ilsave,#saveHeaderLabel_oth,#jqGridPager2Delete_oth,#jqGridPager2EditAll_oth,#jqGridPager2SaveAll_oth,#jqGridPager2CancelAll_oth").hide();
			$("#saveDetailLabel_oth").show();
		}else{
			$("#jqGrid2_oth_iladd,#jqGrid2_oth_ilcancel,#jqGrid2_oth_ilsave,#saveHeaderLabel_oth,#jqGridPager2Delete_oth,#jqGridPager2EditAll_oth").show();
			$("#saveDetailLabel_oth,#jqGridPager2SaveAll_oth,#jqGrid2_oth_iledit,#jqGridPager2CancelAll_oth").hide();
		}
	}
	
	////////////////////selected///////////////
	$('#apacthdr_doctype').on('change', function() {
		let doctype = $("#apacthdr_doctype option:selected").val();
		init_jq2();
	});
	
	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	// $("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

	// 	var idno = selrowData('#jqGrid').apacthdr_idno;
	// 	var obj={};
	// 	obj.idno = idno;
	// 	obj._token = $('#_token').val();
	// 	obj.oper = $(this).data('oper')+'_single';

	// 	$.post( './invoiceAP/form', obj , function( data ) {
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}).fail(function(data) {
	// 		$('#error_infront').text(data.responseText);
	// 	}).success(function(data){
			
	// 	});
	// });

	$("#but_post_jq").click(function(){
		var idno_array = [];
	
		let ids = $('#jqGrid_selection').jqGrid ('getDataIDs');
		for (var i = 0; i < ids.length; i++) {
			var data = $('#jqGrid_selection').jqGrid('getRowData',ids[i]);
	    	idno_array.push(data.apacthdr_auditno);
	    }
	    
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( './invoiceAP/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.auditno = selrowData('#jqGrid').apacthdr_auditno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './invoiceAP/form', obj , function( data ) {
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
		$("#dialogForm .noti2 ol").empty();
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

		if(failmsg.length){
			failmsg.forEach(function(element){
				$('#dialogForm .noti2 ol').prepend('<li>'+element+'</li>');
			});
			if(nkreturn)return false;
		}else{
			if(nkreturn)return true;
		}

	}

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
			mycurrency.formatOn();
			hideatdialogForm(false);
			hideatdialogForm_oth(false)
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				addmore_jqgrid2_oth.state = true;
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#apacthdr_auditno,#auditno').val(data.auditno);
				$('#apacthdr_idno').val(data.idno);
				//$('#apacthdr_outamount').val(data.amount);//just save idno for edit later
				
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

	////////////////////////////searching////////////////////////////
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

	searchChange(true);
	function searchChange(once=false){
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

		// urlParam.filterCol = filter.fc;
		// urlParam.filterVal = filter.fv;
		// urlParam.WhereInCol = null;
		// urlParam.WhereInVal = null;

		// if(once){
		// 	urlParam.searchCol=null;
		// 	urlParam.searchVal=null;
		// 	if($('#searchForm [name=Stext]').val().trim() != ''){
		// 		let searchCol = ['purreqhd_recno'];
		// 		let searchVal = ['%'+$('#searchForm [name=Stext]').val().trim()+'%'];
		// 		urlParam.searchCol=searchCol;
		// 		urlParam.searchVal=searchVal;
		// 	}

		// 	if($("#recstatus_use").val() == 'APPROVED'){
		// 		urlParam.filterCol[1] = null; 
		// 		urlParam.filterVal[1] = null; 
		// 		urlParam.WhereInCol = ['purreqhd.recstatus'];
		// 		urlParam.WhereInVal = [['VERIFIED','RECOMMENDED1','RECOMMENDED2']];
		// 	}
		// 	once=false;
		// }

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
		action:'get_table_default',
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source'],
		filterVal:['session.compcode', '', '<>.DELETE', $('#apacthdr_source').val()]
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./invoiceAPDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false, key:true}, //canSearch: true, checked: true},
			{ label: 'Delivery Order Number', name: 'document', width: 200, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: false,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ custom_element:documentCustomEdit,
					custom_value:galGridCustomValue },
			},
	
			{ label: 'Purchase Order Number', name: 'reference', width: 200, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",formatter: padzero, unformat: unpadzero,
			},
			{ label: 'Amount', name: 'amount', width: 100, classes: 'wrap',
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
			{ label: 'Tax Claim', name: 'GSTCode', width: 200, edittype:'text', hidden:true, classes: 'wrap',  
				editable:true,
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Tax Amount', name: 'AmtB4GST', width: 100, classes: 'wrap', hidden:true, 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: false},edittype:"text",
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
			{ label: 'Record No', name: 'dorecno', width: 100, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",
			},
			{ label: 'GRN No', name: 'grnno', width: 100, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",formatter: padzero, unformat: unpadzero,
			},
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",
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
		loadComplete: function(data){
			// if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			// else{
			// 	$('#jqGrid2').jqGrid ('setSelection', "1");
			// }

			setjqgridHeight(data,'jqGrid2');

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset

			//calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			fdl.set_array().reset();

			// unsaved = false;
			// var ids = $("#jqGrid2").jqGrid('getDataIDs');
			// var result = ids.filter(function(text){
			// 					if(text.search("jqg") != -1)return false;return true;
			// 				});
			// if(result.length == 0 && oper=='edit')unsaved = true;
			
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
			dialog_document.on();//start binding event on jqgrid2

        	mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);

        	$("input[name='document']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
			var resobj = JSON.parse(response.responseText);
			$('#apacthdr_auditno').val(resobj.auditno);
        	$('#apacthdr_amount').val(resobj.totalAmount);
        	$('#apactdtl_outamt').val(resobj.totalAmount);
        	mycurrency.formatOn();
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline

			urlParam2.filterVal[1]=resobj.auditno;
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
        }, 
        errorfunc: function(rowid,response){
			errorField.length=0;
			$('#dialogForm .noti2 ol').prepend('<li>'+response.responseText+'</li>');
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
        beforeSaveRow: function(options, rowid) {

        	if(errorField.length>0)return false;

        	mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./invoiceAPDetail/form?"+
				$.param({
					action: 'invoiceAPDetail_save',
					idno: $('#apacthdr_idno').val(),
					auditno:$('#apacthdr_auditno').val(),
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			hideatdialogForm(false);
			$('#jqGrid2').jqGrid ('setSelection', "1");
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
				    			action: 'invoiceAPDetail_save',
								auditno: $('#apacthdr_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
								document: selrowData('#jqGrid2').document,

				    		}
				    		$.post( "./invoiceAPDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							},'json').fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#apacthdr_amount').val(data.totalAmount);
								$('#apactdtl_outamt').val(data.totalAmount);
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

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);
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

		    	var obj = 
		    	{
		    		'lineno_' : ids[i],
		    		'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),
		    		'reference' : data.reference,
		    		'amount' : data.amount,
		    		'dorecno' : data.dorecno,
		    		'grnno' : data.grnno,
					'deptcode' : data.deptcode,
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invoiceAPDetail_save',
				_token: $("#_token").val(),
				auditno: $('#apacthdr_auditno').val()
    		}

    		$.post( "./invoiceAPDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				// $('#amount').val(data);
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

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='apacthdr_suppcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'pouom': field = ['uomcode', 'description']; table = "material.uom";case_='pouom';break;
			case 'pricecode':field=['pricecode','description'];table="material.pricesource";case_='pricecode';break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
			case 'apacthdr_deptcode':field=['deptcode','description'];table="sysdb.department";case_='apacthdr_deptcode';break;
			case 'apacthdr_category':field=['catcode','description'];table="material.category";case_='apacthdr_category';break;
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;

			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('invoiceAP',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	function format_qtyoutstand(cellvalue, options, rowObject){
		var qtyoutstand = rowObject.qtyorder - rowObject.qtydelivered;
		if(qtyoutstand<0 || isNaN(qtyoutstand)) return 0;
		return qtyoutstand;
	}

	//////////////////////////formatter checkbox//////////////////////////////////////////////////
	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;


		if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}

		if($('#recstatus_use').val() == 'ALL'){
			if(rowObject.apacthdr_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'CANCEL'){
			if(rowObject.apacthdr_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}

			if(rowObject.apacthdr_recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
	}

	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'> remark</i> </button>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Delivery Order Number':temp=$('#jqGrid2_oth input[name="document"]');break;
			case 'Department':temp=$('#jqGrid2_oth input[name="deptcode"]');break;
			case 'Category':temp=$('#jqGrid2_oth input[name="category"]');break;
			case 'GST Code':temp=$('#jqGrid2_oth input[name="GSTCode"]');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function documentCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="document" name="document" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function itemcodeCustomEdit(val, opt) {
		val = val;
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group">
					<input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="` + val + `" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
				</div>
				<span class="help-block"></span>
			`);
	}
	function taxcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
	}

	function deptcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_oth" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm text-uppercase" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function categoryCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_oth" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm text-uppercase" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function GSTCodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	

		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2_oth" optid="`+opt.id+`" id="`+opt.id+`" name="GSTCode" type="text" class="form-control input-sm text-uppercase" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+id_optid+`_gstpercent" name="gstpercent" type="hidden"></div>`);
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
	
	$("#apacthdr_document").blur(function(){
		check_suppcode_duplicate();
	});

	function check_suppcode_duplicate(){
		if(oper == 'add' && $("#apacthdr_document").val().trim() != '' && $("#apacthdr_suppcode").val().trim() != '' ){
			var id = "#apacthdr_document";
			var id2 = "apacthdr_document";
			var param={
				func:'getDocNo',
				action:'get_value_default',
				url: 'util/get_value_default',
				field:['document'],
				table_name:'finance.apacthdr'
			}

			param.filterCol = ['document','suppcode','recstatus','compcode'];
			param.filterVal = [$("#apacthdr_document").val(),$('#apacthdr_suppcode').val(),'<>.CANCELLED','session.compcode'];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id2,errorField)!==-1){
						errorField.splice($.inArray(id2,errorField), 1);
					}
					myerrorIt_only(id,false);
				} else {
					var supp_name = $('#apacthdr_suppcode').parent().next().text();
					bootbox.alert("Duplicate Document No for <b>"+supp_name+'</b>');
					if($.inArray(id2,errorField)===-1){
						errorField.push( id2 );
					}
					myerrorIt_only(id,true);
					$(id).data('show_error','Duplicate Document No');
				}
			});
		}
	}
	
	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			saveHeader("#formdata",oper,saveParam,{idno:$('#apacthdr_idno').val()});
			unsaved = false;
		}else{
			mycurrency.formatOn();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_supplier.on();
		dialog_payto.on();
		dialog_category.on();
		dialog_department.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti, .noti2 ol").empty();
		refreshGrid("#jqGrid2",urlParam2,'add');
		// errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////	
	function onall_editfunc(){
		
		dialog_document.on();//start binding event on jqgrid2
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		
	}
	
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
		loadComplete: function(data){
			//setjqgridHeight(data,'jqGrid3');
			//calc_jq_height_onchange("jqGrid3");
		},
		onSelectRow: function(data, rowid, selected) {
			if(rowid != null) {
				var rowData = $('#gridDo').jqGrid('getRowData', rowid);
				urlParam_gridDo.filterVal[0]=selrowData("#jqGrid3").dorecno;

				refreshGrid('#gridDo', urlParam_gridDo);
			}
		},

		gridComplete: function(){
			fdl.set_array().reset();
			$('#jqGrid3').jqGrid ('setSelection', "1");
		},
	});

	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////jqgridpv//////////////////////////////////////////////

	var urlparampv ={
		action:'get_pv_detail',
		url:'./invoiceAP/table',
		idno:''
	}

	$("#jqgridpv").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Audit No', name: 'auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero},
			{ label: 'TT', name: 'trantype', width: 10, classes: 'wrap'},
			{ label: 'Creditor', name: 'suppcode', width: 60, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
			{ label: 'Document Date', name: 'actdate', width: 25, classes: 'wrap',  formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Document No', name: 'document', width: 25, classes: 'wrap', },
			{ label: 'Alloc Amount', name: 'allocamount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'O/S Amount', name: 'outamount', width: 25, classes: 'wrap',align: 'right', formatter:'currency', hidden:true},
			{ label: 'PV Amount', name: 'amount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Status', name: 'recstatus', width: 25, classes: 'wrap',},
			{ label: 'Post Date', name: 'recdate', width: 35, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerpv",
		loadComplete: function(data){
			// setjqgridHeight(data,'jqgridpv');
			// calc_jq_height_onchange("jqgridpv");
		},
		onSelectRow: function(data, rowid, selected) {
		},

		gridComplete: function(){
			// fdl.set_array().reset();
		},
	});

	jqgrid_label_align_right("#jqgridpv");

	///////////////////////////////////parameter for grid do///////////////////////////////////////////////////////////////
	var urlParam_gridDo={
		action:'get_table_dtl',
		url:'./deliveryOrderDetail/table',
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode','dodt.pouom', 'dodt.suppcode','dodt.trandate','dodt.deldept','dodt.deliverydate','dodt.qtyorder','dodt.qtydelivered', 'dodt.qtyoutstand','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks', 'dodt.unit','t.rate','dodt.idno'],
		table_name:['material.delorddt AS dodt','material.productmaster AS p','hisdb.taxmast AS t'],
		table_id:'lineno_',
		join_type:['LEFT JOIN','LEFT JOIN'],
		join_onCol:['dodt.itemcode','dodt.taxcode'],
		join_onVal:['p.itemcode','t.taxcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		filterVal:['','session.compcode','<>.DELETE']
	};

	//////////////////////////////////////////////start jqgrid4 delivery order//////////////////////////////////////////////
	$("#gridDo").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'No', name: 'lineno_', width: 60, classes: 'wrap', editable:false},
			
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:false},
			{ label: 'Price Code', name: 'pricecode', width: 200, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:pricecodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Code', name: 'itemcode', width: 170, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'UOM Code', name: 'uomcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{
				label: 'PO UOM', name: 'pouom', width: 130, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Qty <br> Delivered', name: 'qtydelivered', width: 130, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true,custom:true, custom_func:cust_rules},edittype:"text",
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
			{ label: 'O/S <br> Quantity', name: 'qtyoutstand', width: 130, align: 'right', classes: 'wrap', editable:true,	
				formatter: format_qtyoutstand, formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 100, align: 'right', classes: 'wrap', 
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
			{ label: 'Tax Code', name: 'taxcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:taxcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Percentage <br> Discount (%)', name: 'perdisc', width: 150, align: 'right', classes: 'wrap', 
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
			{ label: 'Discount <br> Per Unit', name: 'amtdisc', width: 130, align: 'right', classes: 'wrap', 
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
			{ label: 'Total <br> GST Amount', name: 'tot_gst', width: 110, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},
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
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total <br> Line Amount', name: 'totamount', width: 130, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Expiry <br> Date', name: 'expdate', width: 130, classes: 'wrap', editable:true,
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
			{ label: 'Batch No', name: 'batchno', width: 120, classes: 'wrap', editable:true,
					maxlength: 30,
			},
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Remarks', name: 'remarks_button', width: 130, formatter: formatterRemarks,unformat: unformatRemarks,hidden:true},
			{ label: 'Remarks', name: 'remarks', hidden:true},
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'whtspc_wrap', hidden:true},
			{ label: 'unit', name: 'unit', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Quantity Order', name: 'qtyorder', editable:false, hidden:true},

		],
		scroll: false,
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'idno',
		pager: "#jqGridPager4",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});
			// setjqgridHeight(data,'jqGrid3');
        	// //showeditfunc.off().on();
			// calc_jq_height_onchange("gridDo");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid3_iledit").click();
		},
	});

	/////////////////////////////parameter for jqgrid2 other url///////////////////////////////////////////////
	var urlParam2_oth={
		action: 'get_table_dtl',
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source'],
		filterVal:['session.compcode', '', '<>.DELETE', $('#apacthdr_source').val()]
	};

	var addmore_jqgrid2_oth={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2 other//////////////////////////////////////////////
	$("#jqGrid2_oth").jqGrid({
		datatype: "local",
		editurl: "./invoiceAPDetail/form2",
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
			{ label: 'Category', name: 'category', width: 150, classes: 'wrap', hidden: true, editable: false,},
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
                    readonly: "readonly",
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
		pager: "#jqGridPager2_oth",
		loadComplete: function(data){
			if(addmore_jqgrid2_oth.more == true){$('#jqGrid2_oth_iladd').click();}
			else{
				$('#jqGrid2_oth').jqGrid('setSelection', "1");
			}
			addmore_jqgrid2_oth.edit = addmore_jqgrid2_oth.more = false; //reset
			setjqgridHeight(data,'jqGrid2_oth');
			//calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_deptcodeOth.check(errorField);
			dialog_GSTCodeOth.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	//////////////////////////////////////////myEditOptions details/////////////////////////////////////////////
	
	var myEditOptions_oth = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
			// $("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
        	errorField.length=0;
			$("#jqGrid2 input[name='deptcode']").focus().select();
        	$("#jqGridPager2EditAll_oth,#saveHeaderLabel_oth,#jqGridPager2Delete_oth").hide();

			dialog_deptcodeOth.on();//start binding event on jqgrid2
			dialog_GSTCodeOth.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2_oth input[name='amount']","#jqGrid2_oth input[name='tot_gst']","#jqGrid2_oth input[name='AmtB4GST']"]);
			
			$("#jqGrid2_oth input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2_oth input[name='AmtB4GST'],#jqGrid2_oth input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

        	$("#jqGrid2_oth input[name='amount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_oth_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
			var resobj = JSON.parse(response.responseText);
			$('#apacthdr_auditno').val(resobj.auditno);
        	$('#apacthdr_amount').val(resobj.totalAmount);
        	$('#apactdtl_outamt').val(resobj.totalAmount);

        	mycurrency.formatOn();
        	if(addmore_jqgrid2_oth.state==true)addmore_jqgrid2_oth.more=true; //only addmore after save inline

			urlParam2_oth.filterVal[1]=resobj.auditno;
        	refreshGrid('#jqGrid2_oth',urlParam2_oth,'add');
	    	$("#jqGridPager2EditAll_oth,#jqGridPager2Delete_oth").show();
        }, 
        errorfunc: function(rowid,response){
			errorField.length=0;
			$('#dialogForm .noti2 ol').prepend('<li>'+response.responseText+'</li>');
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
        beforeSaveRow: function(options, rowid) {
        	if(errorField.length>0){
				console.log(errorField);
        		return false;
        	}
        	mycurrency2.formatOff();

			if(parseInt($('#jqGrid2_oth input[name="amount"]').val()) == 0){
				myerrorIt_only2('#jqGrid2_oth input[name="amount"]');
				alert('Amount cant be 0');
				return false;
			}
			
			let data = $('#jqGrid2_oth').jqGrid ('getRowData', rowid);
			let editurl = "./invoiceAPDetail/form?"+
				$.param({
					action: 'InvoiceAPDetail_save_oth',
					idno: $('#apacthdr_idno').val(),
					auditno:$('#apacthdr_auditno').val(),
					amount:data.amount,
					lineno_:data.lineno_,
				});
			$("#jqGrid2_oth").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm_oth(false);
			//$('#jqGrid2').jqGrid ('setSelection', "1");
	    }
    };

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2_oth").inlineNav('#jqGridPager2_oth',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_oth
		},
		editParams: myEditOptions_oth
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "jqGridPager2Delete_oth",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid2_oth").jqGrid ('getGridParam', 'selrow');
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
				    			action: 'InvoiceAPDetail_save_oth',
								auditno: $('#apacthdr_auditno').val(),
								lineno_: selrowData('#jqGrid2_oth').lineno_,
				    		}
				    		$.post( "./invoiceAPDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							},'json').fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
								mycurrency.formatOn();
								refreshGrid("#jqGrid2_oth",urlParam2_oth,'add');
							});
				    	}else{
        					$("#jqGridPager2EditAll_oth").show();
				    	}
				    }
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "jqGridPager2EditAll_oth",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid2_oth").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2_oth").jqGrid('editRow',ids[i]);

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_AmtB4GST","#"+ids[i]+"_tot_gst","#"+ids[i]+"_amount"]);
				// cari_gstpercent2(ids[i]);

				dialog_deptcodeOth.id_optid = ids[i];
		        dialog_deptcodeOth.check(errorField,ids[i]+"_deptcode","jqGrid2_oth",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    dialog_GSTCodeOth.id_optid = ids[i];
		        dialog_GSTCodeOth.check(errorField,ids[i]+"_GSTCode","jqGrid2_oth",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

		    }
		   	onall_editfunc_oth();
			hideatdialogForm_oth(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "jqGridPager2SaveAll_oth",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid2_oth").jqGrid('getDataIDs');

			var jqgrid2_oth_data = [];
			mycurrency2.formatOff();
		    for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid2_oth').jqGrid('getRowData',ids[i]);
				// let retval = check_cust_rules("#jqGrid2_oth",data);
				// console.log(retval);
				// if(retval[0]!= true){
				// 	alert(retval[1]);
				// 	mycurrency2.formatOn();
				// 	return false;
				// }

				if(parseInt($("#jqGrid2_oth input#"+ids[i]+"_amount").val()) == 0){
					alert('Amount cant be 0');
					mycurrency2.formatOn();
					return false;
				}

		    	var obj = 
		    	{
		    		'lineno_' : ids[i],
		    		'idno' : data.idno,
		    		'deptcode' : $("#jqGrid2_oth input#"+ids[i]+"_deptcode").val(),
		    		'category' : $("#jqGrid2_oth input#"+ids[i]+"_category").val(),
					'GSTCode' : $("#jqGrid2_oth input#"+ids[i]+"_GSTCode").val(),
		    		'AmtB4GST' : $('#jqGrid2_oth input#'+ids[i]+"_AmtB4GST").val(),
		    		'tot_gst' : $('#jqGrid2_oth input#'+ids[i]+"_tot_gst").val(),
		    		'amount' : $('#jqGrid2_oth input#'+ids[i]+"_amount").val(),
		    	}

		    	jqgrid2_oth_data.push(obj);
		    }

			var param={
    			action: 'InvoiceAPDetail_save_oth',
				_token: $("#_token").val(),
				auditno: $('#apacthdr_auditno').val(),
				idno: $('#apacthdr_idno').val()
    		}

    		$.post( "./invoiceAPDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_oth_data}, function( data ){
			}).fail(function(data) {
				errorField.length=0;
				$('#dialogForm .noti2 ol').prepend('<li>'+data.responseText+'</li>');
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				$('#apacthdr_amount').val(data);
				hideatdialogForm_oth(false);
				refreshGrid("#jqGrid2_oth",urlParam2_oth,'add');
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "jqGridPager2CancelAll_oth",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_oth(false);
			refreshGrid("#jqGrid2_oth",urlParam2_oth,'add');
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "saveHeaderLabel_oth",
		caption:"Header",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Header"
	}).jqGrid('navButtonAdd',"#jqGridPager2_oth",{
		id: "saveDetailLabel_oth",
		caption:"Detail",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Detail"
	});

	//////////////////////////////////////////saveDetailLabel oth////////////////////////////////////////////
	$("#saveDetailLabel_oth").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			saveHeader("#formdata",oper,saveParam,{idno:$('#apacthdr_idno').val()});
			unsaved = false;
		}else{
			mycurrency.formatOn();
		}
	});

	//////////////////////////////////////////saveHeaderLabel oth////////////////////////////////////////////
	$("#saveHeaderLabel_oth").click(function(){
		emptyFormdata(errorField,'#formdata2_oth');
		hideatdialogForm_oth(true);
		dialog_supplier.on();
		dialog_payto.on();
		dialog_category.on();
		dialog_department.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti, .noti2 ol").empty();
		refreshGrid("#jqGrid2_oth",urlParam2_oth,'add');
		// errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////	
	function onall_editfunc_oth(){
		
		dialog_deptcodeOth.on();//start binding event on jqgrid2
		dialog_GSTCodeOth.on();

		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		
	}
	////////////////////////////////////////////////jqgrid3 oth//////////////////////////////////////////////
	$("#jqGrid3_oth").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_oth").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		// sortname: 'lineno_',
		// sortorder: "desc",
		pager: "#jqGridPager3_oth",
		loadComplete: function(data){
			//setjqgridHeight(data,'jqGrid3');
			//calc_jq_height_onchange("jqGrid3");
		},
		onSelectRow: function(data, rowid, selected) {
			if(rowid != null) {
				var rowData = $('#gridDo').jqGrid('getRowData', rowid);
				urlParam_gridDo.filterVal[0]=selrowData("#jqGrid3_oth").dorecno;

				refreshGrid('#gridDo', urlParam_gridDo);
			}
		},

		gridComplete: function(){
			fdl.set_array().reset();
			$('#jqGrid3_oth').jqGrid ('setSelection', "1");
		},
	});

	jqgrid_label_align_right("#jqGrid3_oth");

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
				$('#apacthdr_recdate').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#apacthdr_recdate').focus();
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
				close: function(){
					check_suppcode_duplicate();
				}
			},'urlParam','radio','tab'
		);
	dialog_supplier.makedialog(true);

	$('#apacthdr_suppcode').blur(function(){
		$('#apacthdr_payto').val($('#apacthdr_suppcode').val()).blur();
	});

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
							$('#apacthdr_actdate').focus();
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
			},'urlParam','radio','tab'
		);
	dialog_payto.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category','#apacthdr_category',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus', 'compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
		
			ondblClickRow:function(){
				$('#apacthdr_deptcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_deptcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{	
			title:"Select Category Code",
			open: function(){
					if (($('#apacthdr_doctype').val()=="Supplier")) {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '1'];
					}else {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '0'];
					}
				}

			},'urlParam','radio','tab'
		
	);
	dialog_category.makedialog(true);

	var dialog_department = new ordialog(
		'department','sysdb.department','#apacthdr_deptcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				$('#apacthdr_remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_remarks').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Department Code",
			open: function(){
				dialog_department.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_department.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_department.makedialog(true);

	var dialog_document = new ordialog(
		'document',['material.delordhd'],"#jqGrid2 input[name='document']", errorField,
		{	colModel:[
				{label:'DO No',name:'delordno',width:300,classes:'pointer',canSearch:true,or_search:true},
				{label:'PO No',name:'srcdocno',width:300,classes:'pointer',formatter: padzero, unformat: unpadzero}, 
				{label:'GRN No',name:'docno',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true,formatter: padzero, unformat: unpadzero},
				{label:'Delivery Date',name:'deliverydate',width:200,classes:'pointer', formatter: dateFormatter, unformat: dateUNFormatter },
				{label:'Amount',name:'amount',width:300,classes:'pointer',formatter: 'currency', align:'right'},
				{label:'Purchase Dept',name:'prdept',width:300,classes:'pointer', hidden:false},
				{label:'tax claim',name:'taxclaimable',width:400,classes:'pointer', hidden:true},
				{label:'tax amount',name:'TaxAmt',width:400,classes:'pointer', hidden:true},
				{label:'record no',name:'recno',width:400,classes:'pointer', hidden:true},
				{label:'suppcode',name:'suppcode',width:400,classes:'pointer', hidden:true},
				

			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},

			ondblClickRow: function () {
				let data = selrowData('#' + dialog_document.gridname);
				console.log(data);
				$("#jqGrid2 input[name='document']").val(data['delordno']);
				$("#jqGrid2 input[name='reference']").val(data['srcdocno']);
				$("#jqGrid2 input[name='amount']").val(data['amount']);
				$("#jqGrid2 input[name='GSTCode']").val(data['taxclaimable']);
				$("#jqGrid2 input[name='AmtB4GST']").val(data['TaxAmt']);
				$("#jqGrid2 input[name='dorecno']").val(data['recno']);
				$("#jqGrid2 input[name='grnno']").val(data['docno']);
				$("#jqGrid2 input[name='entrydate']").val(data['deliverydate']);
				$("#jqGrid2 input[name='deptcode']").val(data['prdept']);

				addmore_jqgrid2.state = true;
				$('#jqGrid2_ilsave').click();

			}
		},{
			title:"Select DO No",
			open: function(){
				dialog_document.urlParam.url = "./invoiceAP/table";
				dialog_document.urlParam.action = "document";
				dialog_document.urlParam.suppcode =  $("#apacthdr_suppcode").val();
				dialog_document.urlParam.postdate =  $("#apacthdr_postdate").val();

			}
		},'none'
	);
	dialog_document.makedialog(true);

	var dialog_deptcodeOth = new ordialog(
		'department_oth','sysdb.department',"#jqGrid2_oth input[name='deptcode']",errorField,
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
				$("#jqGrid2_oth #"+id_optid+"_category").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2_oth input#"+obj.id_optid+"_deptcode").val()
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
				dialog_deptcodeOth.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_deptcodeOth.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab');
		dialog_deptcodeOth.makedialog(true);

	var dialog_GSTCodeOth = new ordialog(
		'GSTCode_oth',['hisdb.taxmast'],"#jqGrid2_oth input[name='GSTCode']",errorField,
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
				let data=selrowData('#'+dialog_GSTCodeOth.gridname);
				$("#jqGrid2_oth #"+id_optid+"_gstpercent").val(data['rate']);
				$("#jqGrid2_oth #"+id_optid+"_AmtB4GST").focus().select();
				calculate_line_totgst_and_totamt2(id_optid);
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2_oth input#"+obj.id_optid+"_GSTCode").val()
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
				dialog_GSTCodeOth.urlParam.filterCol=['compcode','recstatus','taxtype'];
				dialog_GSTCodeOth.urlParam.filterVal=['session.compcode','ACTIVE','Input'];
			},
			check_take_all_field:true,
			after_check: function(data,obj,id){
				var id_optid = id.substring(0,id.search("_"));
				if(data.rows.length>0 && !obj.ontabbing){
					$("#jqGrid2_oth #"+id_optid+'_gstpercent').val(data.rows[0].rate);
					calculate_line_totgst_and_totamt2(id_optid);
					calc_jq_height_onchange("jqGrid2_oth");
					$(id_optid+"_AmtB4GST").focus().select();
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCodeOth.makedialog(false);

	var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();

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
			console.log(rowdata);
		},
		gridComplete: function(){
			
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){		$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	////////////////////////////////////Pager Hide//////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	function if_cancel_hide(){
		if(selrowData('#jqGrid').apacthdr_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#jqGrid3Oth_panel').collapse('hide');
			$('#gridDo_panel').collapse('hide');
			$('#gridPV_panel').collapse('hide');
			$('#gridAttch_panel').collapse('hide');
			$('#gridDo').hide();
			$('#jqgridpv').hide();
			$('#attach_iframe').hide();
			$('#ifcancel_show').text(' - CANCELLED');
			$('#Othifcancel_show').text(' - CANCELLED');
			$('#ifcancel_showDo').text(' - CANCELLED');
			$('#ifcancel_showPV').text(' - CANCELLED');
			$('#ifcancel_showattach').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
			$('#panel_jqGrid3Oth').attr('data-target','-');
			$('#panel_gridDo').attr('data-target','-')
			$('#panel_gridpv').attr('data-target','-')
			$('#panel_gridattach').attr('data-target','-')

		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#jqGrid3Oth_panel').collapse('show');
			$('#gridDo_panel').collapse('show');
			$('#gridPV_panel').collapse('show');
			// $('#gridAttch_panel').collapse('show');
			$('#gridDo').show();
			$('#jqgridpv').show();
			$('#attach_iframe').show();
			$('#ifcancel_show').text('');
			$('#Othifcancel_show').text('');
			$('#ifcancel_showDo').text('');
			$('#ifcancel_showPV').text('');
			$('#ifcancel_showattach').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
			$('#panel_jqGrid3Oth').attr('data-target','#jqGrid3Oth_panel');
			$('#panel_gridDo').attr('data-target','#gridDo_panel');
			$('#panel_gridattach').attr('data-target','#gridAttch_panel');
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_oth_panel").on("show.bs.collapse", function(){
		$("#jqGrid3_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_oth_c")[0].offsetWidth-$("#jqGrid3_oth_c")[0].offsetLeft-28));
	});

	$("#gridDo_panel").on("show.bs.collapse", function(){
		$("#gridDo").jqGrid ('setGridWidth', Math.floor($("#gridDo_c")[0].offsetWidth-$("#gridDo_c")[0].offsetLeft-28));
	});

	$("#gridPV_panel").on("show.bs.collapse", function(){
		$("#jqgridpv").jqGrid ('setGridWidth', Math.floor($("#gridPV_c")[0].offsetWidth-$("#gridPV_c")[0].offsetLeft-28));
	});

	function init_jq2(){
		if(oper == 'add'){
			if($('#apacthdr_doctype').val() == 'Supplier'){
				$('#save').hide();
				$('#ap_detail').show();
				$('#apOth_detail').hide();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}else{
				$('#save').hide();
				$('#ap_detail').hide();
				$('#apOth_detail').show();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_oth_c")[0].offsetWidth-$("#jqGrid2_oth_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}
		}

		if(oper == 'edit'){
			if($('#apacthdr_doctype').val() == 'Supplier'){
				$('#save').hide();
				$('#ap_detail').show();
				$('#apOth_detail').hide();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}else{
				$('#save').hide();
				$('#ap_detail').hide();
				$('#apOth_detail').show();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_oth_c")[0].offsetWidth-$("#jqGrid2_oth_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}
		}

		if(oper == 'view'){
			if($('#apacthdr_doctype').val() == 'Supplier'){
				$('#save').hide();
				$('#ap_detail').show();
				$('#apOth_detail').hide();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}else{
				$('#save').hide();
				$('#ap_detail').hide();
				$('#apOth_detail').show();
				$('#apactdtl_outamt').prop('readonly',true);
				$("#jqGrid2_oth").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_oth_c")[0].offsetWidth-$("#jqGrid2_oth_c")[0].offsetLeft-28));
				$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
			}
		}
	}

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( 'invoiceAP/form',obj,function( data ) {
					
			});
		}
	}

	function calculate_line_totgst_and_totamt(event){

		mycurrency.formatOff();
	    mycurrency2.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let amntb4gst = parseFloat($("#jqGrid2_oth input#"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2_oth input#"+id_optid+"_gstpercent").val());
		var amount = 0;

		if(gstpercent == 0){
			$("#jqGrid2_oth  input#"+id_optid+"_tot_gst").prop('disabled',true);
			tot_gst = 0;
			amount = amntb4gst;
		}else{
			$("#jqGrid2_oth input#"+id_optid+"_tot_gst").prop('disabled',false);
			var tot_gst_real = parseFloat($("#jqGrid2_oth input#"+id_optid+"_tot_gst").val());
			var tot_gst_rate = parseFloat(amntb4gst * (gstpercent / 100));

			if(tot_gst_real == tot_gst_rate || tot_gst_real == 0){
				amount = amntb4gst + tot_gst_rate;
				tot_gst = tot_gst_rate;
			}else{
				amount = amntb4gst + tot_gst_real;
				tot_gst = tot_gst_real;
			}

		}

		$("#jqGrid2_oth input#"+id_optid+"_tot_gst").val(tot_gst);

		$("#jqGrid2_oth input#"+id_optid+"_amount").val(amount);

		// calculate_total_header();
		
		mycurrency.formatOn();
		mycurrency2.formatOn();
	}

	function calculate_line_totgst_and_totamt2(id_optid) {
		mycurrency.formatOff();
		mycurrency2.formatOff();
		
		let amntb4gst = parseFloat($("#jqGrid2_oth input#"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2_oth input#"+id_optid+"_gstpercent").val());
		var amount = 0;

		if(gstpercent == 0){
			$("#jqGrid2_oth input#"+id_optid+"_tot_gst").prop('disabled',true);
			tot_gst = 0;
			amount = amntb4gst;
		}else{
			$("#jqGrid2_oth input#"+id_optid+"_tot_gst").prop('disabled',false);
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

		$("#jqGrid2_oth input#"+id_optid+"_tot_gst").val(tot_gst);
		
		$("#jqGrid2_oth input#"+id_optid+"_amount").val(amount);

		// calculate_total_header();
		
		mycurrency.formatOn();
		mycurrency2.formatOn();
	}

});

function populate_form(obj){

	//panel header
	$('#trantype_show').text(obj.apacthdr_trantype);
	$('#document_show').text(obj.apacthdr_document);
	$('#suppcode_show').text(obj.supplier_name);

	$('#Othtrantype_show').text(obj.apacthdr_trantype);
	$('#Othdocument_show').text(obj.apacthdr_document);
	$('#Othsuppcode_show').text(obj.supplier_name);

	$('#doTrantype_show').text(obj.apacthdr_trantype);
	$('#doDocument_show').text(obj.apacthdr_document);
	$('#doSuppcode_show').text(obj.supplier_name);

	$('#pvTrantype_show').text(obj.apacthdr_trantype);
	$('#pvDocument_show').text(obj.apacthdr_document);
	$('#pvSuppcode_show').text(obj.supplier_name);

	$('#attachTrantype_show').text(obj.apacthdr_trantype);
	$('#attachDocument_show').text(obj.apacthdr_document);
	$('#attachSuppcode_show').text(obj.supplier_name);
	
	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
	}
}

function empty_form(){

	$('#trantype_show').text('');
	$('#document_show').text('');
	$('#suppcode_show').text('');

}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
}