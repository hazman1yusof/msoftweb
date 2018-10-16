
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
				console.log(errorField);
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
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
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
					//$("#apacthdr_ttype").val($("#trantype").val());
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
					dialog_supplier.check(errorField);
					dialog_payto.check(errorField);
					dialog_category.check(errorField);
					dialog_department.check(errorField);
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
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;
			//reset balik
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.alert').detach();
			$("#formdata a").off();
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
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
		url:'/util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['finance.apacthdr','material.supplier'],
		table_id:'apacthdr_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['apacthdr.suppcode'],
		filterCol: ['source'],
		filterVal: ['AP'],
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'invoiceAP_save',
		url:'/invoiceAP/form',
		field:'',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'idno'
	};
	function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
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

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'invoiceAP_save',
		url:'/invoiceAP/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'idno'
	};

	$("#jqGrid").jqGrid({
	datatype: "local",
	colModel: [
		//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
		{ label: 'Audit No', name: 'apacthdr_auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero},
		{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap'},
		{ label: 'Creditor', name: 'apacthdr_suppcode', width: 20, classes: 'wrap', canSearch: true},
		{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap', canSearch: true},
		{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap'},
		{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap',align: 'right'},
		{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap',},
		{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap', hidden:true},
		{ label: 'Doc Date', name: 'apacthdr_recdate', width: 25, classes: 'wrap', hidden:true},
		{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'source', name: 'apacthdr_source', width: 40, hidden:'true'},
		{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:'true'},
		{ label: 'doctype', name: 'apacthdr_doctype', width: 40, hidden:'true'},
	],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'apacthdr_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			switch($("#scope").val()){
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
					if(stat=='POSTED'){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else{
						$('#but_post_jq').show();
						$('but_cancel_jq,#but_reopen_jq').hide();
					}
					break;
			}

			$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);

			$('#auditno').val(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			urlParam2.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			console.log(urlParam2.filterVal);

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

	/*/////////////////////////////// for Button /////////////////////////////////////////////////////////
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

		////////////////////selected///////////////

	$('#apacthdr_ttype').on('change', function() {
		let ttype1 = $("#apacthdr_ttype option:selected" ).val();
		if(ttype1 == 'Supplier' || ttype1 == 'Others') {
			if (ttype1 == 'Supplier') {
				$doctype = 'Supplier';
			} else
				$doctype = 'Others';
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("IN");
		}else if(ttype1 == 'Debit_Note') {
			$doctype = 'Debit_Note';
			
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("DN");
		}
////////////////////selection of category///////////////
title:"Select Category Code",
			open: function(){
					if (($doctype =="Supplier")) {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '1'];
					}else {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '0'];
					}
				}
*/
	////////////////////selected///////////////

	$('#apacthdr_ttype').on('change', function() {
		let ttype1 = $("#apacthdr_ttype option:selected" ).val();
		if(ttype1 == 'Supplier' || ttype1 == 'Others') {
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("IN");
		}else if(ttype1 == 'Debit_Note') {
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("DN");
		}
		
		if(($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
			$('#save').hide();
			$('#ap_parent').show();
		}else {
			$('#save').show();
			$('#ap_parent').hide();
		}
	});
	
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
				//return emptyFormdata('#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'idno': selrowData('#jqGrid').idno });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		saveParam.oper = 'posted';
		let obj={
			auditno:selrowData('#jqGrid').apacthdr_auditno,
			_token:$('#_token').val(),
			idno:selrowData('#jqGrid').apacthdr_idno
		};
		$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');


	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name']);

	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata", oper,saveParam,{idno:selrowData('#jqGrid').apacthdr_idno});
			unsaved = false;
			$("#dialogForm").dialog('close');
			}else{
				mycurrency.formatOn();
		}
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			//alert(data.responseJSON.message);
			dialog_supplier.on();
			dialog_payto.on();
			dialog_category.on();
			dialog_department.on();
		}).done(function (data) {

			unsaved = false;
			hideatdialogForm(false);
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				//sometodo();
				//$('#pvno').val(data.pvno);
				$('#auditno').val(data.auditno);
				$('#amount').val(data.amount);//just save idno for edit later
				
				//urlParam.filterVal[0]=data.auditno;
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
			disableForm('#formdata');
			
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
		field:['apdt.compcode','apdt.source','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source'],
		filterVal:['session.compcode', '', '<>.DELETE', 'AP']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/invoiceAPDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
			// { label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
			{ label: 'Delivery Order Number', name: 'document', width: 200, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{ custom_element:documentCustomEdit,
					custom_value:galGridCustomValue },
			},
			{ label: 'Purchase Order Number', name: 'reference', width: 200, edittype:'text', classes: 'wrap',  
				editable:true,
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount', name: 'AmtB4GST', width: 100, classes: 'wrap',
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
			{ label: 'Tax Claim', name: 'GSTCode', width: 200, edittype:'text', classes: 'wrap',  
				editable:true,
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Tax Amount', name: 'amount', width: 100, classes: 'wrap', 
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
			{ label: 'Record No', name: 'dorecno', width: 100, classes: 'wrap', editable: true,
				//editrules:{required: true},
				edittype:"text",
			},
			{ label: 'GRN No', name: 'grnno', width: 100, classes: 'wrap', editable: true,
				//editrules:{required: true},
				edittype:"text",
			},
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
			if(addmore_jqgrid2.edit == true){
			var linenotoedit_new = parseInt(linenotoedit)+1;
				if($.inArray(String(linenotoedit_new),$('#jqGrid2').jqGrid ('getDataIDs')) != -1){
					$('#jqGrid2').jqGrid ('setSelection', String(linenotoedit_new));
					$('#jqGrid2_iledit').click();
				}
			}
			else if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
			// $( "#jqGrid2_ilcancel" ).off();
			// $( "#jqGrid2_ilcancel" ).on( "click", function(event) {
			// 	event.preventDefault();
			// 	event.stopPropagation();
			// 	bootbox.confirm({
			// 	    message: "Are you sure want to cancel?",
			// 	    buttons: {
			// 	        confirm: { label: 'Yes',className: 'btn-success'},
			// 	        cancel: {label: 'No',className: 'btn-danger'}
			// 		},
			// 		callback: function (result) {
			// 			if (result == true) {
			// 				$(".noti").empty();
			// 				$("#jqGrid2").jqGrid("clearGridData", true);
			// 				refreshGrid("#jqGrid2",urlParam2);
			// 			}
			// 			linenotoedit = null;
			// 	    }
			// 	});
			// });

			
		},
		/*afterShowForm: function (rowid) {
		    $("#expdate").datepicker();
		},*/
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
        	//console.log(rowid);
        	/*linenotoedit = rowid;
        	$("#jqGrid2").find(".rem_but[data-lineno_!='"+linenotoedit+"']").prop("disabled", true);
        	$("#jqGrid2").find(".rem_but[data-lineno_='undefined']").prop("disabled", false);*/
        },
        aftersavefunc: function (rowid, response, options) {
           $('#amount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	if(addmore_jqgrid2.edit == false)linenotoedit = null; 
        	//linenotoedit = null;
        	console.log(urlParam2);
        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
        	if(errorField.length>0)return false;

			let data = selrowData('#jqGrid2');
			let editurl = "/invoiceAPDetail/form?"+
				$.param({
					action: 'invoiceAPDetail_save',
					auditno:$('#auditno').val(),
					/*recno:$('#recno').val(),*/
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
				    			action: 'invoiceAPDetail_save',
								auditno: $('#auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,

				    		}
				    		$.post( "/invoiceAPDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
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
			case 'document':field=['delordno','srcdocno'];table="material.delordhd";break;
			//case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.rows[0].description+"</span>");
			}
		});
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Delivery Order Number':temp=$('#document');break;
			//case 'Tax Claim':temp=$('#GSTCode');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function documentCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="document" name="document" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	/*function GSTCodeCustomEdit(val,opt){  	
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="GSTCode" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}*/

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
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			saveHeader("#formdata",oper,saveParam);
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
	});

	function saveDetailLabel(callback=null){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			saveHeader("#formdata",oper,saveParam);
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
		if(callback!=null)callback();
	}

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
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
		unsaved = false;
		$("#jqGridPager2Delete,#saveHeaderLabel").hide();
		dialog_document.on();//start binding event on jqgrid2
		//dialog_gstcode.on();
		/*$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
		$("#jqGrid2 input[name='qtyonhandrecv']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='qtyonhand']").on('blur',checkQOH);*/
		$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
		});
	});	

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

	////////////////////object for dialog handler///////////////////
	var dialog_supplier = new ordialog(
		'supplier','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_supplier.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_supplier.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_supplier.makedialog();

	var dialog_payto = new ordialog(
		'payto','material.supplier','#apacthdr_payto',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_payto.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payto.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_payto.makedialog();

	var dialog_category = new ordialog(
		'category','material.category','#apacthdr_category',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			title:"Select Category Code",
			open: function(){
					if (($("#apacthdr_ttype").val()=="Supplier")) {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '1'];
					}else {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', '9A', 'CR', '0'];
					}
				}

				//}		
				
			},'urlParam'
		);
	dialog_category.makedialog();

	var dialog_department = new ordialog(
		'department','sysdb.department','#apacthdr_deptcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]
		},{
			title:"Select Department Code",
			open: function(){
				dialog_department.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_department.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_department.makedialog();

	var dialog_document = new ordialog(
		'document',['material.delordhd'],"#jqGrid2 input[name='document']", errorField,
		{	colModel:[
				{label:'DO No',name:'delordno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'GRN No',name:'docno',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Post Date',name:'postdate',width:400,classes:'pointer', hidden:true},
				{label:'pono',name:'srcdocno',width:400,classes:'pointer', hidden:true},
				{label:'amount',name:'totamount',width:400,classes:'pointer', hidden:true},
				{label:'tax claim',name:'taxclaimable',width:400,classes:'pointer', hidden:true},
				{label:'tax amount',name:'TaxAmt',width:400,classes:'pointer', hidden:true},
				{label:'record no',name:'recno',width:400,classes:'pointer', hidden:true},
			],

			ondblClickRow: function () {
				let data = selrowData('#' + dialog_document.gridname);
				$("#jqGrid2 input[name='document']").val(data['delordno']);
				$("#jqGrid2 input[name='reference']").val(data['srcdocno']);
				$("#jqGrid2 input[name='amount']").val(data['totamount']);
				$("#jqGrid2 input[name='GTSCode']").val(data['taxclaimable']);
				$("#jqGrid2 input[name='AmtB4GST']").val(data['TaxAmt']);
				$("#jqGrid2 input[name='dorecno']").val(data['recno']);
				$("#jqGrid2 input[name='grnno']").val(data['docno']);
				$("#jqGrid2 input[name='entrydate']").val(data['postdate']);
			}
		},{
			title:"Select DO No",
			open: function(){
				dialog_document.urlParam.filterCol=['compcode', 'recstatus','suppcode'];
				dialog_document.urlParam.filterVal=['9A', 'POSTED',$("#apacthdr_suppcode").val()];
				}
			},'urlParam'
		);
	dialog_document.makedialog(false);

	/*var dialog_gstcode = new ordialog(
		'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
		{	colModel:[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			]
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_gstcode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_gstcode.urlParam.filterVal=['A', '9A']
				}
			},'urlParam'
		);
	dialog_gstcode.makedialog();*/



var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();
});
