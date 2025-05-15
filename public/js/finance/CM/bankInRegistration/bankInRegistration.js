$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	
	/////////////////////////validation//////////////////////////
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
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	$("body").click(function(){
		$('#error_infront').text('');
	});
			
	//////////////////////////////////////////////////////////////

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount','#refcomrate','#commamt','#netamount','#dtlamt']);
	var fdl = new faster_detail_load();
	var myallocation = new Allocation();

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#actdate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper;
	var unsaved = false;

	$("#dialogForm").dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			urlParam2.edit='false';
			parent_close_disabled(true);
			actdateObj.getdata().set();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly("#formdata");
					hideOne("#formdata");
					setpaymodeused();
					dialog_bankcode.on();
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata',['bankcode','paymode','unit']);
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					setpaymodeused('edit');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					$('#formdata :input[hideOne]').show();
					setpaymodeused('view');
					break;
			}
			// if(oper=='edit'){
			// }

			if(oper!='add'){
				dialog_bankcode.check(errorField);
			}
		},

		beforeClose: function(event, ui){
			mycurrency.formatOff();
			// if(unsaved){
			// 	event.preventDefault();
			// 	bootbox.confirm("Are you sure want to leave without save?", function(result){
			// 		if (result == true) {
			// 			unsaved = false;
			// 			$("#dialogForm").dialog('close');
			// 		}
			// 	});
			// }
		},

		close: function( event, ui ) {
			urlParam2.edit='false';
			// addmore_jqgrid2.state = false;
			// addmore_jqgrid2.more = false;
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.my-alert').detach();
			$("#formdata a").off();
			dialog_bankcode.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
			// radbuts.reset();
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////recstatus filter for checkbox/////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
	if($("#recstatus_use").val() == 'POSTED'){
		recstatus_filter = [['OPEN','POSTED']];
		filterCol_urlParam = ['compcode'];
		filterVal_urlParam = ['session.compcode'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","recstatus",recstatus_filter[0][0]);

	////////////////////padzero///////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		if(cellvalue == '' || cellvalue == null ){
			return '';
		}
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

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./bankInRegistration/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'saveheader',
		url:'./bankInRegistration/form',
		field:'',
		oper:oper,
	};
		
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true},
			{ label: 'compcode', name: 'compcode', width: 10 , hidden: true  },
			{ label: 'Bank Code', name: 'bankcode', width: 30 , checked:true, canSearch: true, classes : 'wrap text-uppercase', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Payer', name: 'payto', width: 18, classes : 'wrap text-uppercase', canSearch: true, formatter: showdetail,unformat:un_showdetail },
			{ label: 'Reference', name: 'reference', width: 30},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', canSearch: false, checked: false, hidden:true},
			{ label: 'Audit No', name: 'auditno', width: 20,  canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Paymode', name: 'paymode', width: 15},
			{ label: 'Post Date', name: 'actdate', width: 15, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'amount', width: 20,  align: 'right',formatter:'currency'},
			{ label: 'Commision', name: 'commamt', width: 20,  align: 'right',formatter:'currency'},
			{ label: 'Tot Detail Amt', name: 'totBankinAmt', width: 20,  align: 'right',formatter:'currency'},
			{ label: 'Status', name: 'recstatus', width: 15, classes: 'wrap text-uppercase'},
			{ label: 'Payment Mode', name: 'paymode', width: 30, hidden:true },
			{ label: 'Cheque No', name: 'cheqno', width: 40, hidden:true},//formatter:formatterCheqnno, unformat:unformatterCheqnno
			{ label: 'Entered By', name: 'adduser', width: 35,  hidden:true},
			{ label: 'Entered Date', name: 'adddate', width: 40,  hidden:true},
			{ label: 'GST', name: 'TaxClaimable', width: 40, hidden:true},
			{ label: 'Cheq Date', name: 'cheqdate', width: 40, hidden:true},
			{ label: 'source', name: 'source', width: 40, hidden:true},
		 	{ label: 'trantype', name: 'trantype', width: 40, hidden:true},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },	
		 	
		],
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function(rowid, selected) {
			// let recstatus = selrowData("#jqGrid").recstatus;
			// if(recstatus=='OPEN'){
			// 	$('#but_cancel_jq,#but_post_jq').show();			
			// }else if(recstatus=="POSTED"){
			// 	$('#but_post_jq').hide();
			// 	$('#but_cancel_jq').show();
			// }else if (recstatus == "CANCELLED"){
			// 	$('#but_cancel_jq,#but_post_jq').hide();			
			// }
			
			urlParam2.idno=selrowData("#jqGrid").idno;
			refreshGrid("#jqGrid3",urlParam2);
			$("#pdfgen1").attr('href','./bankInRegistration/showpdf?auditno='+selrowData("#jqGrid").auditno);

			populate_form(selrowData("#jqGrid"));
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				$('#save').hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function(){
			$('#but_cancel_jq,#but_post_jq').hide();
			
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			
			$("#searchForm input[name=Stext]").focus();
			
			if($('#jqGrid').data('inputfocus') == 'bankcode_search'){
				$("#bankcode_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#bankcode_search_hb').text('');
				removeValidationClass(['#bankcode_search']);
				
			}else if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
				
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			
			fdl.set_array().reset();
			cbselect.refresh_seltbl();
			cbselect.show_hide_table();
			cbselect.checkbox_function_on();
			populate_form(selrowData("#jqGrid"));
			//empty_form()
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
		},
			
	});
		
	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', { 'text-align': 'right' });
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
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');

			$("#jqGrid2").data('initAllo','true');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');

			$("#jqGrid2").data('initAllo','true');
			refreshGrid("#jqGrid2",urlParam2);

			if(selrowData("#jqGrid").recstatus == 'POSTED'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
			}
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

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress', 'supplier_name','Checkbox']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////

	function hideatdialogForm(hide,saveallrow){
		$('#allo_search_div').hide();
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel,#saveAndPost").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll,#saveAndPost").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iladd,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveAndPost").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll,#jqGrid2_ilcancel,#jqGrid2_ilsave").hide();
			$('#allo_search_div').show();
		}
	}

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	// $("#but_cancel_jq,#but_post_jq").click(function(){
	// 	saveParam.oper = $(this).data('oper');
	// 	let obj={
	// 		idno:selrowData('#jqGrid').idno,
	// 		auditno:selrowData('#jqGrid').auditno,
	// 		_token:$('#_token').val(),
	// 	};
	// 	$.post("directPayment/form?" + $.param(saveParam),obj,function (data) {
	// 		refreshGrid("#jqGrid", urlParam);
	// 	}).fail(function (data) {
	// 		alert(data.responseText);
	// 	}).done(function (data) {
	// 		//2nd successs?
	// 	});
	// });

	// $("#but_cancel_jq,#but_post_jq").click(function(){
	// 	var idno = selrowData('#jqGrid').idno;
	// 	var obj={};
	// 	obj.idno = idno;
	// 	obj._token = $('#_token').val();
	// 	obj.oper = "posted";

	// 	$.post( './directPayment/form', obj , function( data ) {
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}).fail(function(data) {
	// 		//$('#error_infront').text(data.responseText);
	// 	}).success(function(data){
			
	// 	});
	// });
	$("#but_post_jq").click(function(){
		$(this).prop('disabled',true);
		var self_ = this;
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( 'bankInRegistration/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
			cbselect.empty_sel_tbl();
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
			$(self_).prop('disabled',false);
		}).success(function(data){
			$(self_).prop('disabled',false);
		});
	});

	// $("#but_post2_jq").click(function(){
	
	// 	var obj={};
	// 	obj.auditno = selrowData('#jqGrid').auditno;
	// 	obj.oper = $(this).data('oper');
	// 	obj._token = $('#_token').val();
	// 	oper=null;
		
	// 	$.post( './directPayment/form', obj , function( data ) {
	// 		cbselect.empty_sel_tbl();
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}).fail(function(data) {
	// 		$('#error_infront').text(data.responseText);
	// 	}).success(function(data){
			
	// 	});
	// });

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj,needrefresh){
		$('#saveDetailLabel').prop('disabled',true);
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			alert(data.responseText);
			$('#saveDetailLabel').prop('disabled',false);
		}).done(function (data) {
			mycurrency.formatOn();
			unsaved = false;
			$('#saveDetailLabel').prop('disabled',false);
			hideatdialogForm(false);
			// addmore_jqgrid2.state = true;
			// if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
			// 	$('#jqGrid2_iladd').click();
			// }
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#auditno').val(data.auditno);
				$('#idno').val(data.idno);
				$('#pvno').val(data.pvno);
				//$('#amount').val(data.amount);//just save idno for edit later
				
				urlParam2.edit='true';
				urlParam2.idno=data.idno;
				myallocation.renewAllo($('#amount').val());
				refreshGrid("#jqGrid2", urlParam2);
			}else if(selfoper=='edit'){
				urlParam2.edit='true';
				urlParam2.idno=data.idno;
				refreshGrid("#jqGrid2", urlParam2);
				//doesnt need to do anything
			}
			disableForm('#formdata',['amount','commamt']);

			// if(needrefresh === 'refreshGrid'){
			// 	refreshGrid("#jqGrid", urlParam);
			// }
			
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
		bankcode_search.off();
		creditor_search.off();
		$('#bankcode_search,#creditor_search,#actdate_from,#actdate_to').val('');
		$('#bankcode_search_hb').text('');
		$('#creditor_search_hb').text('');
		urlParam.filterdate = null;
		removeValidationClass(['#bankcode_search, #creditor_search']);

		if($('#Scol').val()=='actdate'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'], #bankcode_text, #creditor_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'bankcode'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'],#actdate_text, #creditor_text").hide("fast");
			$("#bankcode_text").show("fast");
			bankcode_search.on();
		} else if($('#Scol').val() == 'payto'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'],#actdate_text, #bankcode_text").hide("fast");
			$("#creditor_text").show("fast");
			creditor_search.on();
		} else {
			$("#bankcode_text,#actdate_text, #creditor_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}

		if($('#Scol').val()=='actdate' || $('#Scol').val() == 'bankcode' || $('#Scol').val() == 'payto'){
			refreshGrid('#jqGrid', urlParam);
		}else{
			search('#jqGrid',$('#searchForm [name=Stext]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
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
			searchClick2('#jqGrid', '#searchForm', urlParam, false);
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

	var bankcode_search = new ordialog(
		'bankcode_search', 'finance.bank', '#bankcode_search', 'errorField',
		{
			colModel: [
				{ label: 'Bank Code', name: 'bankcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'bankname', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + bankcode_search.gridname).bankcode;

				if($('#Scol').val() == 'bankcode'){
					urlParam.searchCol=["ap.bankcode"];
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
			title: "Select Bankcode",
			open: function () {
				bankcode_search.urlParam.filterCol = ['compcode', 'recstatus'];
				bankcode_search.urlParam.filterVal = ['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	bankcode_search.makedialog(true);
	$('#bankcode_search').on('keyup',ifnullsearch);

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

				if($('#Scol').val() == 'payto'){
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
		if($(this).val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'maintable',
		edit:'false',
		url:'./bankInRegistrationDetail/table',
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./bankInRegistrationDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, key:true},
		 	{ label: 'No', name: 'lineno_', width: 10, classes: 'wrap', editable:false, hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
		 	{ label: 'Ref Type', name: 'reftype', width: 20, classes: 'wrap', editable:false},
		 	{ label: 'Ref Auditno', name: 'allocauditno', width: 20, classes: 'wrap', editable:false},
		 	{ label: 'Pay Type', name: 'refpaymode', width: 20, classes: 'wrap', editable:false},
		 	{ label: 'Document', name: 'refrecptno', width: 20, classes: 'wrap', editable:false},
			{ label: 'Doc Date', name: 'refdocdate', width: 20, classes: 'wrap', editable:false},
			{ label: 'Amount', name: 'refamount', width: 20, classes: 'wrap', editable:false},
			{ label: 'Comm Rate', name: 'refcomrate', width: 20, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Bank In', name: 'cb_idno', width: 20, editable: false, formatter: formatterCheckbox2},
			{ label: 'Comm Amt', name: 'commamt', width: 20, classes: 'wrap', editable:false},
			{ label: 'Reference', name: 'refreference', width: 20, classes: 'wrap', editable:false},
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', hidden:true},
			{ label: 'Category', name: 'category', width: 100, hidden:true},
			{ label: 'Document', name: 'document', width: 100, hidden:true},
			{ label: 'GST Code', name: 'GSTCode', width: 100, hidden:true},
			{ label: 'Amount Before GST', name: 'AmtB4GST', hidden:true},
			{ label: 'Amount', name: 'amount', width: 80, hidden:true},
			{ label: 'commamt', name: 'commamt', width: 80, hidden:true},
			{ label: 'totBankinAmt', name: 'totBankinAmt', width: 80, hidden:true},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		// sortname: 'lineno_',
		// sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if($('#jqGrid2').data("initAllo") == 'true'){
				$('#jqGrid2').data("initAllo",'false');
				myallocation.initAllo();
			}
		},
		gridComplete: function(){
			$("#jqGrid2_c input[type='checkbox']").on('click',function(){
				var idno = $(this).data("idno");
				var rowdata = $("#jqGrid2").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					if(!myallocation.alloInArray(idno)){
						myallocation.addAllo(idno,rowdata.outamount,0);
					}
				}else{
					if(myallocation.alloInArray(idno)){
						myallocation.deleteAllo(idno);
					}
				}
			});

			// $("#jqGrid2_c input[type='text'][name='text_selection2']").on('change',function(){
			// 	var idno = $(this).data("idno");
			// 	var rowdata = $("#jqGrid2").jqGrid ('getRowData', idno);
			// 	if($('#checkbox_selection2_'+idno).prop("checked") == true){
			// 		if(myallocation.alloInArray(idno)){
			// 			myallocation.editAllo(idno,rowdata.outamount,0);
			// 		}
			// 	}
			// });

			delay(function(){
	        	// $("#alloText").focus();//AlloTotal
	        	myallocation.retickallotogrid();
			}, 100 );
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	function startEdit() {
        var ids = $("#jqGrid2").jqGrid('getDataIDs');

        for (var i = 0; i < ids.length; i++) {
            $("#jqGrid2").jqGrid('editRow',ids[i]);
        }
    };

    function Allocation(){
		this.arrayAllo=[];
		this.alloBalance=0;
		this.alloTotal=0;
		this.outamt=0;
		this.allo_error=[];

		this.renewAllo = function(os){
			this.arrayAllo.length = 0;
			this.alloTotal=0;
			this.alloBalance=parseFloat(os);
			this.outamt=parseFloat(os);

			// this.updateAlloField();
		}
		this.initAllo = function(){
			this.arrayAllo.length = 0;
			let self = this;
			let rowdata = $('#jqGrid2').jqGrid('getRowData');

			mycurrency.formatOff();
			rowdata.forEach(function(e,i){
				let comamt = parseFloat(e.refcomrate) * parseFloat(e.refamount) / 100;
				if(isNaN(comamt)){
					comamt = 0;
				}
				e.commamt = parseFloat(comamt).toFixed(4);

				self.arrayAllo.push({idno:e.idno,obj:e});
			});
			// console.log(this.arrayAllo);

			this.updateAlloField();
		}
		this.addAllo = function(idno,paid,bal){
			var obj=getlAlloFromGrid(idno);

			mycurrency.formatOff();
			this.arrayAllo.push({idno:idno,obj:obj});

			let com_hdr = $('#commamt').val();
			let new_com_hdr = parseFloat(com_hdr) + parseFloat(obj.commamt);
			$('#commamt').val(new_com_hdr);
			// console.log(this.arrayAllo);
			
			// $(fieldID).on('change',[idno,self.arrayAllo,self.allo_error],onchangeField);

			this.updateAlloField();
		}
		this.deleteAllo = function(idno){
			var self=this;
			let comamt_del = 0;
			mycurrency.formatOff();
			$.each(self.arrayAllo, function( index, obj ) {
				if(obj.idno==idno){
					comamt_del = obj.obj.commamt;
					self.arrayAllo.splice(index, 1);
					return false;
				}
			});

			let com_hdr = $('#commamt').val();
			let new_com_hdr = parseFloat(com_hdr) - parseFloat(comamt_del);
			$('#commamt').val(new_com_hdr);

			// console.log(this.arrayAllo);
			this.updateAlloField();
		}
		// this.editAllo = function(idno,paid,bal){
		// 	let refcomrate = $('#text_comrate_'+idno).val();

		// 	var alloIndex = getIndex(this.arrayAllo,idno);
		// 	let obj = this.arrayAllo[alloIndex].obj;
		// 	let commamt = parseFloat(refcomrate) * parseFloat(obj.refamount) / 100;


		// 	this.arrayAllo[alloIndex].obj.refcomrate = refcomrate;
		// 	this.arrayAllo[alloIndex].obj.commamt = parseFloat(commamt).toFixed(4);
		// 	// console.log(this.arrayAllo);
			
		// 	// // $(fieldID).on('change',[idno,self.arrayAllo,self.allo_error],onchangeField);

		// 	this.updateAlloField();
		// }

		function getIndex(array,idno){
			var retval=0;
			$.each(array, function( index, obj ) {
				if(obj.idno==idno){
					retval=index;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.alloInArray = function(idno){
			var retval=false;
			$.each(this.arrayAllo, function( index, obj ) {
				if(obj.idno==idno){
					retval=true;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.retickallotogrid = function(){
			var self=this;
			$.each(this.arrayAllo, function( index, obj ) {
				$("input#checkbox_selection2_"+obj.idno).prop('checked', true);
				$("#jqGrid2").jqGrid('setRowData', obj.idno ,{commamt:obj.obj.commamt});
				// $('#text_comrate_'+obj.idno).val(obj.obj.refcomrate)
				// $("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo],onchangeField);
				// if(obj.obj.amtpaid != " "){
				// 	$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
				// 	setbal(obj.idno,obj.obj.amtbal);
				// }
			});
		}
		this.updateAlloField = function(){
			// console.log(this.arrayAllo);
			// var self=this;
			this.alloTotal = 0;
			let totalallo = 0;
			let totalcom = 0;

			$.each(this.arrayAllo, function( index, obj ) {
				let amt = parseFloat(obj.obj.refamount).toFixed(4);
				let com = parseFloat(obj.obj.commamt).toFixed(4);

				$("#jqGrid2").jqGrid('setRowData', obj.idno ,{commamt:com});

				totalcom = parseFloat(totalcom) + parseFloat(com);
				totalallo = parseFloat(totalallo) + parseFloat(amt);
			});
			$('#dtlamt').val(totalallo);
			// $('#commamt').val(totalcom);
			mycurrency.formatOn();
			// this.alloBalance = this.outamt - this.alloTotal;

			// $("#AlloTotal").val(this.alloTotal);
			// $("#AlloBalance").val(this.alloBalance);
			// if(this.alloBalance<0){
			// 	$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
			// 	alert("Balance cannot in negative values");
			// }else{
			// 	$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
			// }
			// allocurrency.formatOn();
		}

		function updateAllo(idno,amtpaid,arrayAllo){
			$.each(arrayAllo, function( index, obj ) {
				if(obj.idno==idno){
					obj.obj.amtpaid=amtpaid;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
		}

		function getlAlloFromGrid(idno){
			var temp=$("#jqGrid2").jqGrid ('getRowData', idno);
			let comamt = parseFloat(temp.refcomrate) * parseFloat(temp.refamount) / 100;
			if(isNaN(comamt)){
				comamt = 0;
			}
			temp.commamt = parseFloat(comamt).toFixed(4);;

			return temp;
		}

		function adderror_allo(array,idno){
			if($.inArray(idno,array)===-1){//xjumpa
				array.push(idno);
			}
		}

		function delerror_allo(array,idno){
			if($.inArray(idno,array)!==-1){//jumpa
				array.splice($.inArray(idno,array), 1);
			}
		}
	}

	$('#alloCol').change(function(){
		console.log($(this).val());
		if($(this).val() == 'posteddate'){
			$('#alloDate').show();
			$('#alloText').hide();
		}else{
			$('#alloText').show();
			$('#alloDate').hide();
		}
	});

	AlloSearch("#jqGrid2",urlParam2);
	function AlloSearch(grid,urlParam){
		$("#alloText").on( "keyup", function() {
			delay(function(){
				search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
			}, 800 );
		});

		$("#alloDate").on( "change", function() {
			search(grid,$("#alloDate").val(),$("#alloCol").val(),urlParam);
		});

		$("#alloCol").on( "change", function() {
			search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
		});

		$('#resetAlloBtn').click(function(){
			$("#alloText").val('');
			$("#alloDate").val('');
			search(grid,'','',urlParam);
		});
	}

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		view:false,edit:false,add:false,del:false,search:false,
		// //to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		// restoreAfterSelect: false,
		// addParams: { 
		// 	addRowParams: myEditOptions
		// },
		// editParams: myEditOptions
	})
	.jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveAndPost",
		caption:"Save",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Save"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveHeaderLabel",
		caption:"Header",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Header"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveDetailLabel",
		caption:"Save",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Save"
	});
	
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

	function formattertext(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

		if(urlParam2.edit == 'false'){
			return cellvalue;
		}else if(urlParam2.edit == 'true'){
			return "<input class='form-control input-sm' data-rate='"+cellvalue+"' type='text' name='text_selection2' id='text_comrate_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}
	}

	function unformattertext(cellvalue, options, td){
		if(urlParam2.edit == 'false'){
			return cellvalue;
		}else if(urlParam2.edit == 'true'){
			return $(td).children('input').val();
		}
	}

	function formatterCheckbox2(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;


		if(urlParam2.edit == 'false'){
			return '';
		}else if(urlParam2.edit == 'true'){
			return "<input type='checkbox' name='checkbox_selection2' id='checkbox_selection2_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}
		
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;

			case 'payto':field=['suppcode','name'];table="material.supplier";case_='payto';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
		}
		var param={action:'input_check',url:'./util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('bankInRegistration',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
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

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		// radbuts.check();
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_bankcode.off();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		} else {
			mycurrency.formatOn();
			// dialog_bankcode.on();
		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		// dialog_bankcode.on();
		enableForm('#formdata',['bankcode','paymode','unit']);
		rdonly('#formdata');
		$(".noti").empty();
		urlParam2.edit='false';
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});

	$('#saveAndPost').click(function(){
		$(this).prop('disabled',true);
		mycurrency.formatOff();
		let idno_array = [];
		let rate_array = [];
		myallocation.arrayAllo.forEach(function(e,i){
			idno_array.push(e.idno);
			rate_array.push(e.obj.refcomrate);
		});

		var obj={
			_token : $('#_token').val(),
			amount : $('#amount').val(),
			comamt : $('#commamt').val(),
			dtlamt : $('#dtlamt').val(),
			idno_array : idno_array,
			rate_array : rate_array
		}

		$.post( "./bankInRegistrationDetail/form?oper=saveandpost&idno="+$('#idno').val(), obj , function( data ) {
			
		},'json').fail(function (data) {
			mycurrency.formatOn();
			alert(data.responseText);
			$(this).prop('disabled',false);
		}).done(function (data) {
			mycurrency.formatOn();
			urlParam2.edit='true';
			refreshGrid("#jqGrid2",urlParam2);
			$('#auditno').val(data);
			hideatdialogForm(false);
			$(this).prop('disabled',false);
		});
	});

	// var radbuts=new checkradiobutton(['TaxClaimable']);

	// function textcolourradio(textcolour){
	// 	this.textcolour=textcolour;
	// 	this.check = function(){
	// 		$.each(this.textcolour, function( index, value ) {
	// 			$("label[for="+value+"]").css('color', '#444444');
	// 			$(":radio[name="+value+"]").parent('label').css('color', '#444444');
	// 		});
	// 	}
	// }

	// var textCol=new textcolourradio(['TaxClaimable']);
	///////////////////////////////////////////////////////////////////////////////

	function onall_editfunc(){
		$("#jqGrid2 input[name='amount'], #jqGrid2 input[name='AmtB4GST'], #jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		//$("#jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_edited_gst);
		
	}

	/////////////bind shift + f to btm detail///////////
	$(document).bind('keypress', function(event) {
	    if( event.which === 70 && event.altKey ) {
	        $("#saveDetailLabel").click();
	    }
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
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

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		// sortname: 'lineno_',
		// sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){
			//setjqgridHeight(data,'jqGrid3');
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid3");
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	////////////////////object for dialog handler//////////////////

	var dialog_bankcode = new ordialog(
		'bankcode','finance.bank','#bankcode',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#cheqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcode.urlParam.filterVal=['session.compcode','ACTIVE']
			},
			close:function(){
				$('#amount').select().focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcode.makedialog(false);

	var dialog_payer = new ordialog(
		'payer2','finance.cardcent','#payer2',errorField,
		{	colModel:[
				{label:'Card code',name:'cardcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Payment Mode",
			open: function(){
				dialog_payer.urlParam.filterCol=['compcode','recstatus'],
				dialog_payer.urlParam.filterVal=['session.compcode','ACTIVE']
			},
			close:function(){
				$('#unit').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_payer.makedialog(false);

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','CR', 'Other', 'ACTIVE']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_document").focus().select();
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
			title:"Select Category",
			open: function(){
				dialog_category.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_category.urlParam.filterVal=['session.compcode','CR', 'Other', 'ACTIVE']
			},
		},'urlParam','radio','tab'
	);
	dialog_category.makedialog(true);

	var dialog_GSTCode = new ordialog(
		'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
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

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	$("#paymode").change(function(){
		$('#payer2').data('validation','');
		if($(this).val() == 'CARD'){
			$('#payer1_div').hide();
			$('#payer2_div').show();
			dialog_payer.on();
			$('#amount_label').html('Card Amount');
			$('#payer2').data('validation','required');
			$('#commamt').prop("disabled",false);

		}else if($(this).val() == 'CASH'){
			$('#payer1_div').show();
			$('#payer2_div').hide();
			dialog_payer.off();
			$('#amount_label').html('Cash Amount');
			$('#commamt').val('').prop("disabled",true);
		}else{
			$('#payer1_div').show();
			$('#payer2_div').hide();
			dialog_payer.off();
			$('#amount_label').html('Cheque Amount');
			$('#commamt').val('').prop("disabled",true);
		}
	});

	function setpaymodeused(oper='add'){
		if(oper == 'add'){
			$('#payer1_div').show();
			$('#payer2_div').hide();
			dialog_payer.off();
		}else{
			var paymode = $("#paymode").val();
			if(paymode == 'CARD'){
				$('#payer1_div').hide();
				$('#payer2_div').show();
				dialog_payer.on();
				$('#payer2').val(selrowData("#jqGrid").payto);
				$('#amount_label').html('Card Amount');
				$('#commamt').prop("disabled",false);
			}else if(paymode == 'CASH'){
				$('#payer1_div').show();
				$('#payer2_div').hide();
				dialog_payer.off();
				$('#payer1').val(selrowData("#jqGrid").payto);
				$('#amount_label').html('Cash Amount');
				$('#commamt').val('').prop("disabled",true);
			}else{
				$('#payer1_div').show();
				$('#payer2_div').hide();
				dialog_payer.off();
				$('#payer1').val(selrowData("#jqGrid").payto);
				$('#amount_label').html('Cheque Amount');
				$('#commamt').val('').prop("disabled",true);
			}
		}
	}

	////////////////////////////////////jqGrid_selection////////////////////////////////////
	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'idno',
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
});

function populate_form(obj){
	//panel header
	$('#bankcode_show').text(obj.bankcode);
	$('#payto_show').text(obj.supplier_name);
}

function empty_form(){
	$('#bankcode_show').text('');
	$('#payto_show').text('');
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
		