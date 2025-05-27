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
	var mycurrency =new currencymode(['#gljnlhdr_creditAmt','#gljnlhdr_debitAmt','#gljnlhdr_different']);
	var mycurrency2 =new currencymode([]);
	var fdl = new faster_detail_load();
	// var myattachment = new attachment_page("invoiceap","#jqGrid","gljnlhdr_idno");
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#gljnlhdr_postdate"]);
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
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					gljnlhdr_docdate_getYM();
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
				}
				if(oper!='add'){
					refreshGrid("#jqGrid2",urlParam2);
				}

			},
			beforeClose: function(event, ui){
				mycurrency.formatOff();
				if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false;
							// delete_dd($('#gljnlhdr_idno').val());
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
				emptyFormdata(errorField,'#formdata',['#gljnlhdr_source','#gljnlhdr_trantype']);
				emptyFormdata(errorField,'#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
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
			filterCol_urlParam = ['gljnlhdr.compcode'];
			filterVal_urlParam = ['session.compcode'];
		}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","gljnlhdr_idno","gljnlhdr_recstatus",recstatus_filter[0][0]);
	
	var urlParam={
		action:'maintable',
		url:'./journalEntry/table',
		source:'GL',
		trantype:'JNL',
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'journalEntry_save',
		url:'./journalEntry/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.gljnlhdr',
		table_id:'gljnlhdr_auditno',
		filterCol: ['source', 'trantype'],
		filterVal: ['GL', 'JNL'],
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

	// function searchClick(grid,form,urlParam){
	// 	$(form+' [name=Stext]').on( "keyup", function() {
	// 		delay(function(){
	// 			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 			// $('#auditno').text("");//tukar kat depan tu
	// 			refreshGrid("#jqGrid3",null,"kosongkan");
	// 		}, 500 );
	// 	});

	// 	$(form+' [name=Scol]').on( "change", function() {
	// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 		// $('#auditno').text("");//tukar kat depan tu
	// 		refreshGrid("#jqGrid3",null,"kosongkan");
	// 	});
	// }

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Audit No', name: 'gljnlhdr_auditno', width: 10, classes: 'wrap text-uppercase', canSearch: true,formatter: padzero, unformat: unpadzero},
			{ label: 'Description', name: 'gljnlhdr_description', width: 40, classes: 'wrap text-uppercase',canSearch: true},
			{ label: 'Doc Date', name: 'gljnlhdr_docdate', width: 20, classes: 'wrap text-uppercase', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Different', name: 'gljnlhdr_different', width: 20, classes: 'wrap text-uppercase',align: 'right', formatter:'currency'},
			{ label: 'Status', name: 'gljnlhdr_recstatus', width: 20, classes: 'wrap text-uppercase',},
			// { label: ' ', name: 'Checkbox',sortable:false, width: 15,align: "center", formatter: formatterCheckbox },	
			{ label: 'adduser', name: 'gljnlhdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'gljnlhdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'gljnlhdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'gljnlhdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'gljnlhdr_source', width: 40, hidden:'true'},
			{ label: 'debitAmt', name: 'gljnlhdr_debitAmt', width: 40, hidden:'true'},
			{ label: 'creditAmt', name: 'gljnlhdr_creditAmt', width: 40, hidden:'true'},
            { label: 'trantype', name: 'gljnlhdr_trantype', width: 40, hidden:'true'},
            { label: 'docno', name: 'gljnlhdr_docno', width: 40, hidden:'true'},
            { label: 'year', name: 'gljnlhdr_year', width: 40, hidden:'true'},
            { label: 'period', name: 'gljnlhdr_period', width: 40, hidden:'true'},
			{ label: 'postdate', name: 'gljnlhdr_postdate', width: 28, hidden:true, classes: 'wrap text-uppercase', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'idno', name: 'gljnlhdr_idno', width: 40, hidden:'true', key:true},
			{ label: 'unit', name: 'gljnlhdr_unit', width: 40, hidden:'true'},
			{ label: 'compcode', name: 'gljnlhdr_compcode', width: 40, hidden:'true'},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'gljnlhdr_idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",

		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
		},
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			$('#save').hide();
			let stat = selrowData("#jqGrid").gljnlhdr_recstatus;
			let scope = $("#recstatus_use").val();

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid2', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}

			$('#auditnodepan').text(selrowData("#jqGrid").gljnlhdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").gljnlhdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").gljnlhdr_document);
			$('#gljnlhdr_idno').val(selrowData("#jqGrid").gljnlhdr_idno);

			urlParam2.filterVal[1]=selrowData("#jqGrid").gljnlhdr_auditno;
			refreshGrid("#jqGrid3",urlParam2);
			populate_form(selrowData("#jqGrid"));
			if_cancel_hide();

			// $("#attcahment_page").attr('href','./attachment_upload/?page=invoiceap&idno='+selrowData("#jqGrid").gljnlhdr_idno);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").gljnlhdr_recstatus;
			
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
	jqgrid_label_align_right("#jqGrid");
	jqgrid_label_align_right("#jqGrid2");

	$("#jqGrid").jqGrid('setLabel', 'different', 'Different', { 'text-align': 'right' });
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

			if(selrowData("#jqGrid").gljnlhdr_recstatus == 'POSTED'){
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
	// populateSelect('#jqGrid','#searchForm');
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['gljnlhdr_idno','gljnlhdr_auditno','gljnlhdr_adduser','gljnlhdr_adddate','gljnlhdr_upduser','gljnlhdr_upddate','gljnlhdr_recstatus', 'gljnlhdr_unit', 'gljnlhdr_idno','gljnlhdr_compcode']);

	// $("#save").click(function(){
	// 	unsaved = false;
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
	// 	if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
	// 		saveHeader("#formdata", oper,saveParam,{idno:$('#gljnlhdr_idno').val()},'refreshGrid');
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
	
	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

		var idno = selrowData('#jqGrid').gljnlhdr_idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( './journalEntry/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

	$("#but_post_jq").click(function(){
		var idno_array = [];
	
		let ids = $('#jqGrid_selection').jqGrid ('getDataIDs');
		for (var i = 0; i < ids.length; i++) {
			var data = $('#jqGrid_selection').jqGrid('getRowData',ids[i]);
	    	idno_array.push(data.gljnlhdr_auditno);
	    }
	    
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( './journalEntry/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.auditno = selrowData('#jqGrid').gljnlhdr_auditno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './journalEntry/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});
	
	///////////retrieve month & year///////////////////
	$('#gljnlhdr_docdate').on('change',function(){
		gljnlhdr_docdate_getYM();
	});
	function gljnlhdr_docdate_getYM(){
		var month = moment($('#gljnlhdr_docdate').val()).format("M");
		var year = moment($('#gljnlhdr_docdate').val()).format("Y");
		$('#gljnlhdr_period').val(month);
		$('#gljnlhdr_year').val(year);
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
			mycurrency.formatOn();
			hideatdialogForm(false);

			addmore_jqgrid2.state = true;

			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#gljnlhdr_auditno,#auditno').val(data.auditno);
				$('#gljnlhdr_idno').val(data.idno);
				
				urlParam2.filterVal[1]=data.auditno;
			}else if(selfoper=='edit'){
				urlParam2.filterVal[1]=$('#gljnlhdr_auditno').val();
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
	// $('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	// $('#actdate_search').on('click', searchDate);

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
		},{fct:['gljnlhdr.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	// function whenchangetodate() {
	// 	creditor_search.off();
	// 	$('#creditor_search,#actdate_from,#actdate_to').val('');
	// 	$('#creditor_search_hb').text('');
	// 	urlParam.filterdate = null;
	// 	removeValidationClass(['#creditor_search']);
	// 	if($('#Scol').val()=='gljnlhdr_actdate'){
	// 		$("input[name='Stext'], #creditor_text").hide("fast");
	// 		$("#actdate_text").show("fast");
	// 	} else if($('#Scol').val() == 'gljnlhdr_suppcode' || $('#Scol').val() == 'gljnlhdr_payto'){
	// 		$("input[name='Stext'],#actdate_text").hide("fast");
	// 		$("#creditor_text").show("fast");
	// 		creditor_search.on();
	// 	} else {
	// 		$("#creditor_text,#actdate_text").hide("fast");
	// 		$("input[name='Stext']").show("fast");
	// 		$("input[name='Stext']").velocity({ width: "100%" });
	// 	}
	// }

	////////////////////////////populate data for dropdown search By////////////////////////////
	// searchBy();
	// function searchBy() {
	// 	$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
	// 		if (value['canSearch']) {
	// 			if (value['selected']) {
	// 				$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			} else {
	// 				$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			}
	// 		}
	// 		searchClick2('#jqGrid', '#searchForm', urlParam);
	// 	});
	// }

	// function searchDate(){
	// 	urlParam.filterdate = [$('#actdate_from').val(),$('#actdate_to').val()];
	// 	refreshGrid('#jqGrid',urlParam);
	// }

	// var creditor_search = new ordialog(
	// 	'creditor_search', 'material.supplier', '#creditor_search', 'errorField',
	// 	{
	// 		colModel: [
	// 			{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
	// 			{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
	// 		],
	// 		urlParam: {
	// 					filterCol:['compcode','recstatus'],
	// 					filterVal:['session.compcode','ACTIVE']
	// 				},
	// 		ondblClickRow: function () {
	// 			let data = selrowData('#' + creditor_search.gridname).suppcode;

	// 			if($('#Scol').val() == 'gljnlhdr_suppcode'){
	// 				urlParam.searchCol=["ap.suppcode"];
	// 				urlParam.searchVal=[data];
	// 			}else if($('#Scol').val() == 'gljnlhdr_payto'){
	// 				urlParam.searchCol=["ap.payto"];
	// 				urlParam.searchVal=[data];
	// 			}
	// 			refreshGrid('#jqGrid', urlParam);
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				// $('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	},{
	// 		title: "Select Creditor",
	// 		open: function () {
	// 			creditor_search.urlParam.filterCol = ['recstatus'];
	// 			creditor_search.urlParam.filterVal = ['ACTIVE'];
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// creditor_search.makedialog(true);
	// $('#creditor_search').on('keyup',ifnullsearch);

	// function ifnullsearch(){
	// 	if($('#creditor_search').val() == ''){
	// 		urlParam.searchCol=[];
	// 		urlParam.searchVal=[];
	// 		$('#jqGrid').data('inputfocus','creditor_search');
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}
	// }

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['jdtl.compcode','jdtl.source','jdtl.trantype','jdtl.auditno','jdtl.lineno_','jdtl.costcode','jdtl.glaccount','jdtl.description','jdtl.drcrsign','jdtl.amount','jdtl.unit'],
		table_name:['finance.gljnldtl AS jdtl'],
		table_id:'lineno_',
		filterCol:['jdtl.compcode','jdtl.auditno', 'jdtl.recstatus','jdtl.source'],
		filterVal:['session.compcode', '', '<>.DELETE', $('#gljnlhdr_source').val()]
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./journalEntryDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'unit', name: 'unit', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false, key:true}, //canSearch: true, checked: true},
			{ label: 'Cost Code', name: 'costcode', width: 100, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{ custom_element:costcodeCustomEdit,
					custom_value:galGridCustomValue },
			},
            { label: 'Account Code', name: 'glaccount', width: 100, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{ custom_element:glaccCustomEdit,
					custom_value:galGridCustomValue },
			},
			{ label: 'Description', name: 'description', width: 200, classes: 'wrap', editable: true, edittype:"text",editrules:{required: true}},
            { label: 'DR/CR', name: 'drcrsign', width: 60, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "DR:DR;CR:CR"
				}
			},
			{ label: 'Amount', name: 'amount', width: 100, classes: 'wrap',
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
			dialog_costcode.check(errorField);
			dialog_glacc.check(errorField);
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
			dialog_costcode.on();
			dialog_glacc.on();

        	mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);

        	$("input[name='amount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
			var resobj = JSON.parse(response.responseText);
			// $('#gljnlhdr_auditno').val(resobj.auditno);
        	$('#gljnlhdr_creditAmt').val(numeral(resobj.totalAmountCR).format('0,0'));
        	$('#gljnlhdr_debitAmt').val(numeral(resobj.totalAmountDR).format('0,0'));
        	$('#gljnlhdr_different').val(numeral(resobj.different).format('0,0'));
        	mycurrency.formatOn();
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			$('#jqGrid2_iladd').click();
			// urlParam2.filterVal[1]=resobj.auditno;
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {

        	if(errorField.length>0)return false;

        	mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./journalEntryDetail/form?"+
				$.param({
					action: 'journalEntryDetail_save',
					idno: $('#gljnlhdr_idno').val(),
					auditno:$('#gljnlhdr_auditno').val(),
					// totalAmountDR:$('#gljnlhdr_debitAmt').val(),
					// totalAmountCR:$('#gljnlhdr_creditAmt').val(),
					// totalAmountDR:data.totalAmountDR,
					// totalAmountCR:data.totalAmountCR,
					// different:data.different,
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
				    			action: 'journalEntryDetail_save',
								auditno: $('#gljnlhdr_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
				    		}
				    		$.post( "./journalEntryDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							},'json').fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								// $('#gljnlhdr_amount').val(data.totalAmount);
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
					'idno' : data.idno,
		    		'costcode' : $("#jqGrid2 input#"+ids[i]+"_costcode").val(),
		    		'glaccount' : $("#jqGrid2 input#"+ids[i]+"_glaccount").val(),
		    		'description' : $("#jqGrid2 input#"+ids[i]+"_description").val(),
		    		'drcrsign' : $("#jqGrid2 select#"+ids[i]+"_drcrsign").val(),
		    		'amount' : $("#jqGrid2 input#"+ids[i]+"_amount").val(),
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'journalEntryDetail_save',
				_token: $("#_token").val(),
				auditno: $('#gljnlhdr_auditno').val(),
				// idno: $('#idno').val()

    		}

    		$.post( "./journalEntryDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
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
			case 'costcode':field=['costcode','description'];table="finance.costcenter";case_='costcode';break;
			case 'glaccount': field = ['glaccno', 'description']; table = "finance.glmasref";case_='glaccount';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('journalEntry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Cost Code':temp=$("#jqGrid2 input[name='costcode']");break;
			case 'Account Code':temp=$("#jqGrid2 input[name='glaccount']");break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function costcodeCustomEdit(val,opt){
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="costcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function glaccCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="glaccount" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		
		if($('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		
			saveHeader("#formdata",oper,saveParam,{idno:$('#gljnlhdr_idno').val()});
			unsaved = false;
		}else{
			mycurrency.formatOn();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti, .noti2 ol").empty();
		refreshGrid("#jqGrid2",urlParam2,'add');
		// errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////	
	function onall_editfunc(){
		dialog_costcode.on();
		dialog_glacc.on();
				
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
		},
		gridComplete: function(){
			fdl.set_array().reset();
			$('#jqGrid3').jqGrid ('setSelection', "1");
		},
	});

	jqgrid_label_align_right("#jqGrid3");

	////////////////////object for dialog handler///////////////////

	var dialog_costcode = new ordialog(
		'costcode',['finance.costcenter'],"#jqGrid2 input[name='costcode']", 'errorField',
		{	
			colModel:[
				{label:'Cost Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
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
				$("#jqGrid2 #"+id_optid+"_glaccount").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_costcode").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;

				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.costcode.toUpperCase() == searchfor.toUpperCase().trim()){
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
			title:"Select Cost Code",
			open: function(){
				dialog_costcode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_costcode.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
		},'urlParam','radio','tab'
	);
        dialog_costcode.makedialog(true);

	var dialog_glacc = new ordialog(
		'glaccount',['finance.glmasdtl as glm', 'finance.glmasref as glms'],"#jqGrid2 input[name='glaccount']", 'errorField',
		{	
			colModel:[
				{label:'GL Acc No',name:'glm_glaccount',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'glms_description',width:300,classes:'pointer',checked:true,canSearch:true,or_search:true}, 
				{label:'costcode',name:'glm_costcode',width:400,classes:'pointer', hidden:true},
				{label:'year',name:'glm_year',width:400,classes:'pointer', hidden:true},
				
			],
			urlParam: {
				filterCol:['glm.compcode','glm.recstatus', 'glm.costcode', 'glm.year'],
				filterVal:['session.compcode','ACTIVE',$("#jqGrid2 input[name='costcode']").val(),$('#gljnlhdr_year').val()]
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_description").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_glaccount").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;

				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.glaccount.toUpperCase() == searchfor.toUpperCase().trim()){
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
			title:"Select DO No",
			open: function(){
				dialog_glacc.urlParam.table_name = ['finance.glmasdtl as glm', 'finance.glmasref as glms']
				dialog_glacc.urlParam.fixPost = "true";
				dialog_glacc.urlParam.table_id = "none_";
				dialog_glacc.urlParam.filterCol = ['glm.compcode', 'glm.recstatus','glm.costcode', 'glm.year'];
				dialog_glacc.urlParam.filterVal = ['on.glms.compcode', 'ACTIVE',$("#jqGrid2 input[name='costcode']").val(),$('#gljnlhdr_year').val()];
				dialog_glacc.urlParam.join_type = ['LEFT JOIN'];
				dialog_glacc.urlParam.join_onCol = ['glm.glaccount'];
				dialog_glacc.urlParam.join_onVal = ['glms.glaccno'];
				// dialog_glacc.urlParam.join_filterCol = [['s.uomcode on =','s.compcode =','p.recstatus =']];
				// dialog_glacc.urlParam.join_filterVal = [['p.uomcode','session.compcode','ACTIVE']];
			}
		},'urlParam','radio','tab'
	);
	dialog_glacc.makedialog(true);

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'gljnlhdr_idno',
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
		if(selrowData('#jqGrid').gljnlhdr_recstatus.trim().toUpperCase() == 'CANCELLED'){
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

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( 'journalEntry/form',obj,function( data ) {
					
			});
		}
	}

});

function populate_form(obj){

	//panel header
	$('#trantype_show').text(obj.gljnlhdr_trantype);
	$('#auditno_show').text(padzero(obj.gljnlhdr_auditno));
	
	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
	}
}

function empty_form(){

	$('#trantype_show').text('');
	$('#auditno_show').text('');

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