
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
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var mycurrency2 =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var fdl = new faster_detail_load();
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#apacthdr_actdate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper=null;
	var unsaved = false;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				unsaved = false;
				errorField.length=0;
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					//$("#apacthdr_cheqdate").blur(data['apacthdr_actdate']);
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
					backdated.set_backdate($('#apacthdr_actdate').val());
					dialog_bankcode.on();
					dialog_paymode.on();
					dialog_cheqno.on();
					dialog_suppcode.on();
					dialog_payto.on();
				}
				if(oper!='add'){
					refreshGrid("#jqGrid2",urlParam2);
					dialog_bankcode.check(errorField);
					dialog_paymode.check(errorField);
					dialog_cheqno.check(errorField);
					dialog_suppcode.check(errorField);
					dialog_payto.check(errorField);
				}
			},
			beforeClose: function(event, ui){
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
				addmore_jqgrid2.state = false;
				addmore_jqgrid2.more = false;
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata',['#apacthdr_source','#apacthdr_trantype']);
				emptyFormdata(errorField,'#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				//radbuts.reset();
				errorField.length=0;
			},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	///////////////////////////////////backdated////////////////////////////////////////////////

	var backdated = new func_backdated('#apacthdr_actdate');
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
			filterVal:['PV'],
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
					$('#apacthdr_actdate').attr('min',backdate);
				}
			});
		}
	}

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
	if($("#recstatus_use").val() == 'POSTED'){
		recstatus_filter = [['OPEN','POSTED']];
		filterCol_urlParam = ['apacthdr.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","apacthdr_idno","apacthdr_recstatus",recstatus_filter[0][0]);

	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['finance.apacthdr','material.supplier'],
		table_id:'apacthdr_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['apacthdr.suppcode'],
		filterCol: ['source', 'trantype'],
		filterVal: [$('#apacthdr_source').val(), $('#apacthdr_trantype').val()]
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'paymentVoucher_save',
		url:'./paymentVoucher/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'apacthdr_auditno',
		filterCol: ['source', 'trantype'],
		filterVal: [$('#apacthdr_source').val(), $('#apacthdr_trantype').val()],
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
			//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero},
			{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap'},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap', hidden:true},
			{ label: 'Creditor', name: 'apacthdr_suppcode', width: 60, classes: 'wrap', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap', canSearch: true, checked: true, hidden: true},
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap', canSearch: true},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap', canSearch: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Outamount', name: 'apacthdr_outamount', width: 25 ,hidden:true, classes: 'wrap'},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap',},
			{ label: 'Post Date', name: 'apacthdr_recdate', width: 35, classes: 'wrap'},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap', hidden:true},	
			{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true},
			{ label: 'unit', name: 'apacthdr_unit', width: 40, hidden:true},
			{ label: 'pvno', name: 'apacthdr_pvno', width: 50, classes: 'wrap', hidden:true},
			{ label: 'paymode', name: 'apacthdr_paymode', width: 50, classes: 'wrap', hidden:true},
			{ label: 'bankcode', name: 'apacthdr_bankcode', width: 50, classes: 'wrap', hidden:true},
			{ label: 'cheqno', name: 'apacthdr_cheqno', width: 50, classes: 'wrap', hidden:true},

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
		$('#error_infront').text('');
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
			$('#idno').val(selrowData("#jqGrid").apacthdr_idno);

			urlParam2.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;

			refreshGrid("#jqGrid3",urlParam2);
			$("#pdfgen1").attr('href','./paymentVoucher/showpdf?auditno='+selrowData("#jqGrid").apacthdr_auditno);
			if_cancel_hide();
			populate_form(selrowData("#jqGrid"));
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				$('#save').hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq').hide();
			if (oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			empty_form();

			populate_form(selrowData("#jqGrid"));
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
		},
		
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

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
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit', 'Checkbox']);

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
	$("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

		var idno = selrowData('#jqGrid').apacthdr_idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( './paymentVoucher/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

	$("#but_post_jq").click(function(){
		var idno_array = [];
		console.log($(this).data('date'))
		if($(this).data('date') == undefined || $(this).data('date') == ""){
			alert('Please enter post Date');
			return false;
		}else{
			var idno_date_array = $(this).data('date').split(",");
		}

		let ids = $('#jqGrid_selection').jqGrid ('getDataIDs');

		for (var i = 0; i < ids.length; i++) {
			var data = $('#jqGrid_selection').jqGrid('getRowData',ids[i]);
	    	var found = idno_date_array.find(function(e){
				return (e.split("_")[1] == data.apacthdr_idno);
	    	});

	    	if(found == -1){
	    		alert('Please enter post Date');
	    		return false;//return for atau return function??
	    	}else{
		    	idno_array.push({
		    		idno:data.apacthdr_idno,
		    		date:found.split("_")[0]
		    	});
	    	}
	    }
	    
		var obj={};
		obj.idno_array = idno_array;
		obj.idno_date_array = idno_date_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;

		console.log(obj);
		
		// $.post( './paymentVoucher/form', obj , function( data ) {
		// 	cbselect.empty_sel_tbl();
		// 	refreshGrid('#jqGrid', urlParam);
		// }).fail(function(data) {
		// 	$('#error_infront').text(data.responseText);
		// }).success(function(data){
			
		// });

	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.idno = selrowData('#jqGrid').apacthdr_idno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
			
		$.post(  './paymentVoucher/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

	///////////check postdate & docdate///////////////////
	$('#apacthdr_actdate').on('changeDate', function (ev) {
        $('#apacthdr_cheqdate').change(apacthdr_actdate);
    }); 

	$("#apacthdr_cheqdate,#apacthdr_actdate").blur(checkdate);

	function checkdate(nkreturn=false){
		var apacthdr_cheqdate = $('#apacthdr_cheqdate').val();
		var apacthdr_actdate = $('#apacthdr_actdate').val();
		
		$(".noti ol").empty();
		var failmsg=[];

		if(moment(apacthdr_cheqdate).isBefore(apacthdr_actdate)){
			failmsg.push("Post Date cannot be lower than Document date");
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


		let data_detail = $('#jqGrid2').jqGrid ('getRowData');
		obj.data_detail = data_detail;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {

			hideatdialogForm(false);
			
			// if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
			// 	addmore_jqgrid2.state = true;
			// 	$('#jqGrid2_iladd').click();
			// }
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#apacthdr_auditno,#auditno').val(data.auditno);
				$('#apacthdr_amount').val(data.totalAmount);
				$('#idno').val(data.idno);
				
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
				if(value['checked']){
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
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apalloc AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno','apdt.source','apdt.trantype'],
		filterVal:['session.compcode', '', 'AP','PV']
	};

	var addmore_jqgrid2={more:false,state:false,edit:true} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./paymentVoucherDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqg2},
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap',},
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
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
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false,	
				formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', 
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
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
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
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			setjqgridHeight(data,'jqGrid2');

			addmore_jqgrid2.edit = true;
			addmore_jqgrid2.more = false; //reset
		},

		gridComplete: function(){
			
		
			fdl.set_array().reset();
			if(oper == 'edit'){
				//calc bal
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
				for (var i = 0; i < ids.length; i++) {
					let data = $("#jqGrid2").jqGrid ('getRowData', ids[i]);
					let balance = parseFloat(data.outamount) - parseFloat(data.allocamount);
					$("#jqGrid2").jqGrid('setCell', ids[i], 'balance', balance);
				}

				calc_amtpaid_bal();
						
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
				for (var i = 0; i < ids.length; i++) {
					$("#jqGrid2").jqGrid('editRow',ids[i]);

					$('#jqGrid2 input#'+ids[i]+'_allocamount').on('keyup',{rowid:ids[i]},calc_amtpaid);
				}

			}

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;

			
		},
		beforeSubmit: function(postdata, rowid){ 
			/*dialog_suppcode.check(errorField);
			dialog_payto.check(errorField);
			dialog_category.check(errorField);*/
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	addParamField('#jqGrid2',false,urlParam2,['checkbox','balance','entrydate'])
	jqgrid_label_align_right("#jqGrid2");

	function checkbox_jqg2(cellvalue, options, rowObject){
		return `<input class='checkbox_jqg2' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;
	}

	function calc_amtpaid_bal(){
		$('input.checkbox_jqg2[type="checkbox"]').on('click',function(){
			let rowid = $(this).data('rowid');
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			if($(this).prop('checked')){
				$('#jqGrid2 input#'+rowid+'_allocamount').val(data.outamount);
				$("#jqGrid2").jqGrid('setCell', rowid, 'balance', 0);
			}else{
				$('#jqGrid2 input#'+rowid+'_allocamount').val(0);
				$("#jqGrid2").jqGrid('setCell', rowid, 'balance', data.outamount);
			}
		});
	}

	function calc_amtpaid(event){
		let rowid = event.data.rowid;
		let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
		var val = $(this).val();
		if(parseFloat(val) > parseFloat(data.outamount)){
			$(this).val(data.outamount);
			event.preventDefault();
		}
		if(val.match(/[^0-9\.]/)){
			event.preventDefault();
			$(this).val(val.slice(0,val.length-1));
		}

		var balance = parseFloat(data.outamount) - parseFloat($(this).val());
		$("#jqGrid2").jqGrid('setCell', rowid, 'balance', balance);


	}
	
	function formatterCheckbox(cellvalue, options, rowObject){
		let lineno_ = cbselect.lineno_;
		let recstatus = cbselect.recstatus;
		
		if(options.gid == "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject['apacthdr_idno']+"' data-idno='"+rowObject['apacthdr_idno']+"' data-rowid='"+options.rowId+"' onclick='click_selection(checkbox_selection_"+rowObject['apacthdr_idno']+");'>";
		}else if(options.gid != "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject['apacthdr_idno']+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
        	console.log(rowid);

        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

        	mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);

        	$("input[name='document']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
        	$('#apacthdr_outamount').val(response.responseText);
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
        	mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./paymentVoucherDetail/form?"+
				$.param({
					action: 'paymentVoucherDetail_save',
					idno: $('#apacthdr_idno').val(),
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
		add:false,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	//});
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "jqGridPager2Delete",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-trash",
	// 	title:"Delete Selected Row",
	// 	onClickButton: function(){
	// 		selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			bootbox.alert('Please select row');
	// 		}else{
	// 			bootbox.confirm({
	// 			    message: "Are you sure you want to delete this row?",
	// 			    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
	// 			    },
	// 			    callback: function (result) {
	// 			    	if(result == true){
	// 			    		param={
	// 			    			action: 'paymentVoucherDetail',
	// 							auditno: $('#apacthdr_auditno').val(),
	// 							lineno_: selrowData('#jqGrid2').lineno_,

	// 			    		}
	// 			    		$.post( "/paymentVoucherDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
	// 						}).fail(function(data) {
	// 							//////////////////errorText(dialog,data.responseText);
	// 						}).done(function(data){
	// 							$('#amount').val(data);
	// 							refreshGrid("#jqGrid2",urlParam2);
	// 						});
	// 			    	}else{
 //        					$("#jqGridPager2EditAll").show();
	// 			    	}
	// 			    }
	// 			});
	// 		}
	// 	},
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "jqGridPager2EditAll",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-th-list",
	// 	title:"Edit All Row",
	// 	onClickButton: function(){
	// 		mycurrency2.array.length = 0;
	// 		var ids = $("#jqGrid2").jqGrid('getDataIDs');
	// 	    for (var i = 0; i < ids.length; i++) {

	// 	        $("#jqGrid2").jqGrid('editRow',ids[i]);

	// 	        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);
	// 	    }
	// 	   	onall_editfunc();
	// 		hideatdialogForm(true,'saveallrow');
	// 	},
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "jqGridPager2SaveAll",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-download-alt",
	// 	title:"Save All Row",
	// 	onClickButton: function(){
	// 		var ids = $("#jqGrid2").jqGrid('getDataIDs');

	// 		var jqgrid2_data = [];
	// 		mycurrency2.formatOff();
	// 	    for (var i = 0; i < ids.length; i++) {

	// 			var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

	// 	    	var obj = 
	// 	    	{
	// 	    		'lineno_' : ids[i],
	// 	    		'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),
	// 	    		'reference' : data.reference,
	// 	    		'amount' : data.amount,
 //                    'unit' : $("#"+ids[i]+"_unit").val()
	// 	    	}

	// 	    	jqgrid2_data.push(obj);
	// 	    }

	// 		var param={
 //    			action: 'paymentVoucherDetail_save',
	// 			_token: $("#_token").val(),
	// 			auditno: $('#apacthdr_auditno').val()
 //    		}

 //    		$.post( "/paymentVoucherDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
	// 		}).fail(function(data) {
	// 			//////////////////errorText(dialog,data.responseText);
	// 		}).done(function(data){
	// 			// $('#amount').val(data);
	// 			hideatdialogForm(false);
	// 			refreshGrid("#jqGrid2",urlParam2);
	// 		});
	// 	},	
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "jqGridPager2CancelAll",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-remove-circle",
	// 	title:"Cancel",
	// 	onClickButton: function(){
	// 		hideatdialogForm(false);
	// 		refreshGrid("#jqGrid2",urlParam2);
	// 	},	
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "saveHeaderLabel",
	// 	caption:"Header",cursor: "pointer",position: "last", 
	// 	buttonicon:"",
	// 	title:"Header"//
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveDetailLabel",
		caption:"Save",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('paymentVoucher',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Delivery Order Number':temp=$('#document');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function documentCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="document" name="document" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
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
		if(oper == 'add'){
			var id = "#apacthdr_document";
			var param={
				func:'getDocNo',
				action:'get_value_default',
				url: 'util/get_value_default',
				field:['document'],
				table_name:'finance.apacthdr'
			}

			param.filterCol = ['document'];
			param.filterVal = [$("#apacthdr_document").val()];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id,errorField)!==-1){
						errorField.splice($.inArray(id,errorField), 1);
					}
					$( id ).removeClass( "error" ).addClass( "valid" );
				} else {
					bootbox.alert("Duplicate Document No");
					$( id ).removeClass( "valid" ).addClass( "error" );
					if($.inArray(id,errorField)===-1){
						errorField.push( id );
					}
				}
			});
		}
	});
	
	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()});
			unsaved = false;
			errorField.length=0;
			// $("#dialogForm").dialog('close');
		}else{
			mycurrency.formatOn();
		}
	});

	// $('#savepv').click(function(){
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
	// 	unsaved = false;

	// 	if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
	// 		saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()});
	// 		errorField.length=0;
	// 	}else{
	// 		mycurrency.formatOn();
	// 	}
	// });

	// function saveDetailLabel(callback=null){
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
		
	// 	unsaved = false;
	// 	if( checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {

	// 		dialog_supplier.off();
	// 		dialog_payto.off();
	// 		dialog_category.off();
	// 		saveHeader("#formdata",oper,saveParam);

	// 		errorField.length=0;
	// 	}else{
	// 		mycurrency.formatOn();
	// 	}
	// 	if(callback!=null)callback();
	// }

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_bankcode.on();
		dialog_paymode.on();
		dialog_cheqno.on();
		dialog_suppcode.on();
		dialog_payto.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){

		$("#jqGridPager2Delete,#saveHeaderLabel").hide();
		//dialog_document.on();//start binding event on jqgrid2

		$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
			
		});

	});	

	function onall_editfunc(){
		
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

			setjqgridHeight(data,'jqGrid3');
		},
		gridComplete: function(){
			
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////// object for dialog handler//////////////////////////////////////////

	var dialog_paymode = new ordialog(
		'paymode','debtor.paymode','#apacthdr_paymode',errorField,
		{	colModel:[
				{label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				$('#apacthdr_bankcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_bankcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Paymode",
			open: function(){
				//let data=selrowData('#'+dialog_paymode.gridname);
				dialog_paymode.urlParam.filterCol=['recstatus', 'compcode', 'source'],
				dialog_paymode.urlParam.filterVal=['ACTIVE', 'session.compcode', $('#apacthdr_source').val()],
				dialog_paymode.urlParam.WhereInCol=['paytype'];
        		dialog_paymode.urlParam.WhereInVal=[['Bank Draft', 'Cheque', 'Cash', 'Bank', 'Tele Transfer']];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymode.makedialog(true);

	var dialog_bankcode = new ordialog(
		'bankcode','finance.bank','#apacthdr_bankcode',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				$('#apacthdr_cheqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_cheqno').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Paymode",
			open: function(){
				dialog_bankcode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_bankcode.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_bankcode.makedialog(true);

	var dialog_cheqno = new ordialog(
		'cheqno','finance.chqtran','#apacthdr_cheqno',errorField,
		{	colModel:[
				{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				{label:'Bankcode',name:'bankcode',width:200,classes:'pointer',hidden:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','OPEN']
			},
			ondblClickRow: function () {
				$('#apacthdr_cheqdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#apacthdr_cheqdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Cheque No",
			open: function(){
				dialog_cheqno.urlParam.filterCol=['compcode','recstatus', 'bankcode'],
				dialog_cheqno.urlParam.filterVal=['session.compcode','OPEN', $('#apacthdr_bankcode').val()]
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_cheqno.makedialog(true);

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){

				$("#jqGrid2").jqGrid("clearGridData", true);

				let data=selrowData('#'+dialog_suppcode.gridname);
				$("#apacthdr_payto").val(data['suppcode']);
				$("#jqGrid2 input[name='document']").val(data['suppcode']);
				$("#jqGrid2 input[name='entrydate']").val(data['recdate']); 
				$("#jqGrid2 input[name='reference']").val(data['document']);
				$("#jqGrid2 input[name='refamount']").val(data['amount']);
				$("#jqGrid2 input[name='outamount']").val(data['outamount']);

				var urlParam2 = {
					action: 'get_value_default',
					url: 'util/get_value_default',
					field: [],
					table_name: ['finance.apacthdr'],
					filterCol: ['apacthdr.payto', 'apacthdr.compcode', 'apacthdr.recstatus', 'apacthdr.outamount'],
					filterVal: [$("#apacthdr_suppcode").val(), 'session.compcode', 'POSTED', '>.0'],
					WhereInCol: ['apacthdr.source', 'apacthdr.trantype'],
        			WhereInVal: [['AP','DF','CF','TX'],['IN','DN']],
					table_id: 'idno',
				};

				$.get("util/get_value_default?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						myerrorIt_only(dialog_suppcode.textfield,false);

						data.rows.forEach(function(elem) {
							$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
								{	
									idno:elem['idno'],
									suppcode:elem['suppcode'],
									allocdate:elem['recdate'],
									reference:elem['document'],
									refamount:elem['amount'],
									outamount:elem['outamount'],
									allocamount: 0,
									balance:elem['outamount'],
								
								}
							);
						});

						calc_amtpaid_bal();
						
						var ids = $("#jqGrid2").jqGrid('getDataIDs');
						for (var i = 0; i < ids.length; i++) {
							$("#jqGrid2").jqGrid('editRow',ids[i]);

							$('#jqGrid2 input#'+ids[i]+'_allocamount').on('keyup',{rowid:ids[i]},calc_amtpaid);
						}

					} else {
						alert("This supplier doesnt have any invoice!");
						$(dialog_suppcode.textfield).val('');
						myerrorIt_only(dialog_suppcode.textfield,true);
					}
				});
				
			},
		
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#apacthdr_payto').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcode.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcode.makedialog();


	// var dialog_suppcode = new ordialog(
	// 	'supplier',['finance.apacthdr AS a','material.supplier AS s'],'#apacthdr_suppcode',errorField,
	// 	{	colModel:[
	// 			{label:'Supplier Code',name:'a_suppcode',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
	// 			{label:'Supplier Name',name:'s_Name',width:200,classes:'pointer',canSearch:true,or_search:true},
	// 		],
	// 		urlParam: {
	// 				fixPost: true,
	// 				filterCol:['a.compcode','a.recstatus'],
	// 				filterVal:['session.compcode', 'POSTED']
	// 			},
	// 		ondblClickRow:function(){
	// 			let data=selrowData('#'+dialog_suppcode.gridname);
	// 			$("#apacthdr_payto").val(data['suppcode']);
	// 			$("#jqGrid2 input[name='document']").val(data['suppcode']);
	// 			$("#jqGrid2 input[name='entrydate']").val(data['recdate']); 
	// 			$("#jqGrid2 input[name='reference']").val(data['document']);
	// 			$("#jqGrid2 input[name='amount']").val(data['amount']);

	// 			var urlParam2 = {
	// 				action: 'get_value_default',
	// 				url: '/util/get_value_default',
	// 				field: [],
	// 				table_name: ['finance.apacthdr'],
	// 				table_id: 'idno',
	// 			};

	// 			$.get("/util/get_value_default?" + $.param(urlParam2), function (data) {
	// 			}, 'json').done(function (data) {
	// 				if (!$.isEmptyObject(data.rows)) {
	// 					data.rows.forEach(function(elem) {
	// 						$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
	// 							{
	// 								document:elem['suppcode'],
	// 								entrydate:elem['recdate'],
	// 								reference:elem['document'],
	// 								amount:elem['amount'],
	// 								outamount:elem['outamount'],
	// 								balance:elem['amount'] - elem['totamount'],
								
	// 							}
	// 						);
	// 					});
						

	// 				} else {

	// 				}
	// 			});
				
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$('#apacthdr_payto').focus();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}

			
	// 	},{
	// 		title:"Select Supplier Code",
	// 		open: function(){
	// 			dialog_suppcode.urlParam.table_name = ['finance.apacthdr AS a','material.supplier AS s'];
	// 			dialog_suppcode.urlParam.join_type = ['LEFT JOIN'];
	// 			dialog_suppcode.urlParam.join_onCol = ['a.suppcode'];
	// 			dialog_suppcode.urlParam.join_onVal = ['s.suppcode'];
	// 			dialog_suppcode.urlParam.fixPost="true";
	// 			dialog_suppcode.urlParam.table_id="none_";
	// 			dialog_suppcode.urlParam.filterCol=['a.compcode','a.recstatus', 'a.outamount'];
	// 			dialog_suppcode.urlParam.filterVal=['session.compcode', 'POSTED', '>.0' ];
	// 			dialog_suppcode.urlParam.WhereInCol=['a.source','a.trantype'];
    //     		dialog_suppcode.urlParam.WhereInVal=[['AP','DF','TX'],['IN','DN']];
	// 			}
	// 		},'urlParam','radio','tab'
	// 	);
	// dialog_suppcode.makedialog(true);

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
				//$('#apacthdr_actdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$('#apacthdr_actdate').focus();
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
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	function if_cancel_hide(){
		if(selrowData('#jqGrid').apacthdr_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#ifcancel_show').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#ifcancel_show').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

});

function populate_form(obj){

	//panel header
	$('#trantype_show').text(obj.apacthdr_trantype);
	$('#document_show').text(obj.apacthdr_document);
	$('#suppcode_show').text(obj.supplier_name);

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

function click_selection(id){
	var date_id = 'date_injqgrid_'+$(id).data('idno');
	var date_idno = $(id).data('idno');

	if($(id).is(':checked')){
		$(id).parent().prev().html( "<input class='form-control input-sm' type='date' id='"+date_id+"' data-idno='"+date_idno+"'>" )

		$('#'+date_id).change(function () {
			var date = $('#but_post_jq').data('date');
			let this_idno = $(this).data('idno');
			var date_arr;

			if(date == undefined){
		    	$('#but_post_jq').data('date',$(this).val()+'_'+this_idno);
			}else{
				date_arr = date.split(",");
				var found_idx = date_arr.findIndex(function(e,i){
					return (e.split("_")[1] == this_idno)
				});

				date_arr[found_idx] = $(this).val()+'_'+this_idno;

		    	$('#but_post_jq').data('date',date_arr.join(','));
			}
		});

	}else{
		var date = $('#but_post_jq').data('date');
		var date_arr;

		if(date != undefined){
			date_arr = date.split(",");
			var found_idx = date_arr.findIndex(function(e,i){
				return (e.split("_")[1] == date_idno)
			});

			date_arr.splice(found_idx, 1);

			if(date_arr.length == 0){
	    		$('#but_post_jq').data('date',undefined);//sini last
			}else{
	    		$('#but_post_jq').data('date',date_arr.join(','));
			}
		}
		$(id).parent().prev().html( " " )
	}
}