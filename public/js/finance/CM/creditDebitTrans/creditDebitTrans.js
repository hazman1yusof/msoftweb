
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
			
	//////////////////////////////////////////////////////////////

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount']);
	var fdl = new faster_detail_load();

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
			parent_close_disabled(true);
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
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					$('#formdata :input[hideOne]').show();
					break;
			}
			if(oper!='view'){
				dialog_bankcode.on();
			}

			if(oper!='add'){
				dialog_bankcode.check(errorField);
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
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.my-alert').detach();
			$("#formdata a").off();
			dialog_bankcode.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
			radbuts.reset();
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
		url:'./creditDebitTrans/table',
		trantype:$('#adjustment').val(),
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'cdHeader_Save',
		url:'creditDebitTrans/form',
		field:'',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'idno',
		checkduplicate:'false',
	};
		
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 40, hidden:true},
			{ label: 'Trantype', name: 'trantype', width: 13},
			{ label: 'Bank Code', name: 'bankcode', width: 35, classes: 'wrap', checked: true, canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Audit No', name: 'auditno', width: 16, classes: 'wrap', canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Reference', name: 'refsource', width: 43, classes: 'wrap', canSearch: true},
			{ label: 'Post Date', name: 'actdate', width: 25, classes: 'wrap', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'amount', width: 28, classes: 'wrap', align:'right', formatter:'currency'},
			{ label: 'Remarks', name: 'remarks', width: 43, classes: 'wrap',},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap'},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 15,align: "center", formatter: formatterCheckbox },	
			{ label: 'Entered By', name: 'adduser', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Entered Date', name: 'adddate', width: 40, classes: 'wrap', hidden:true},
			{ label: 'GST', name: 'TaxClaimable', width: 40, hidden:true},
			{ label: 'PV No', name: 'pvno', width: 40, hidden:true},
			{ label: 'source', name: 'source', width: 40, hidden:true},
		 	{ label: 'idno', name: 'idno', width: 40, hidden:'true', key:true},
		 	{ label: 'upduser', name: 'upduser', width: 35, classes: 'wrap', hidden:true},
			{ label: 'upddate', name: 'upddate', width: 40, classes: 'wrap', hidden:true},
		 	
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
			populate_formCDT(selrowData("#jqGrid"));
			let recstatus = selrowData("#jqGrid").recstatus;
			if(recstatus=='OPEN'){
				$('#but_cancel_jq,#but_post_jq').show();
				$("#jqGridplus2").show();			
			}else if(recstatus=="POSTED"){
				$('#but_post_jq').hide();
				$('#but_cancel_jq').show();
				$("#jqGridplus2").hide();
			}else if (recstatus == "CANCELLED"){
				$('#but_cancel_jq,#but_post_jq').hide();	
				$("#jqGridplus2").hide();		
			}
			
			urlParam2.filterVal[1]=selrowData("#jqGrid").auditno;
			refreshGrid("#jqGrid3",urlParam2);
			$("#pdfgen1").attr('href','./creditDebitTrans/showpdf?auditno='+selrowData("#jqGrid").auditno);
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
			empty_formCDT();
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
			}else{
				$("#searchForm input[name=Stext]").focus();
			}

			cbselect.refresh_seltbl();
			cbselect.show_hide_table();
			cbselect.checkbox_function_on();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
		},
	});
	
	$('#adjustment').on('change', function() {

		// if($(this).val() == 'CA'){
		// 	$('#top_heading').text('Credit Header');
		// 	$('#btm_heading').text('Credit Detail');
		// }else{
		// 	$('#top_heading').text('Debit Header');
		// 	$('#btm_heading').text('Debit Detail');
		// }

		let adjustment1  = $("#adjustment option:selected" ).val();
		$("#jqGridplus").show();
		urlParam.trantype = $('#adjustment').val();
		urlParam.filterCol = ['trantype'];
		urlParam.filterVal = [$('#adjustment').val()];
		urlParam2.filterVal[4] = $('#adjustment').val();
		refreshGrid('#jqGrid',urlParam);
	})

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");
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
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"jqGridplus2", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2",urlParam2);

			if(selrowData("#jqGrid").recstatus == 'POSTED'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"jqGridplus", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$( "#dialogForm" ).dialog( "open" );
			let adjustment1  = $("#adjustment option:selected" ).val();
			$('#recstatus').text("");
			if(adjustment1 == 'CA') {
				$( "#dialogForm" ).dialog( "option", "title", "Credit Transaction" );
				$("#formdata :input[name='source']").val("CM");
				$("#formdata :input[name='trantype']").val("CA");
			}else if(adjustment1 == 'DA') {
				$( "#dialogForm" ).dialog( "option", "title", "Debit Transaction" );
				$("#formdata :input[name='source']").val("CM");
			    $("#formdata :input[name='trantype']").val("DA");
			}
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	// populateSelect('#jqGrid','#searchForm');
	//searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

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
	// $("#but_cancel_jq,#but_post_jq").click(function(){
	// 	saveParam.oper = $(this).data('oper');
	// 	let obj={
	// 		auditno:selrowData('#jqGrid').auditno,
	// 		_token:$('#_token').val(),
	// 	};
	// 	$.post("creditDebitTrans/form?" + $.param(saveParam),obj,function (data) { 
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

	// 	$.post( './creditDebitTrans/form', obj , function( data ) {
	// 		refreshGrid('#jqGrid', urlParam);
	// 	}).fail(function(data) {
	// 		//$('#error_infront').text(data.responseText);
	// 	}).success(function(data){
			
	// 	});
	// });

	$("#but_post_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( 'creditDebitTrans/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
			cbselect.empty_sel_tbl();
		}).fail(function(data) {
			$('#error_infront').show().text(data.responseText);
			$(self_).attr('disabled',false);
		}).success(function(data){
			$(self_).attr('disabled',false);
		});
	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.auditno = selrowData('#jqGrid').auditno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './creditDebitTrans/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});
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
			unsaved = false;
			hideatdialogForm(false);
			addmore_jqgrid2.state = true;
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#auditno').val(data.auditno);
				$('#idno').val(data.idno);
				$('#pvno').val(data.pvno);
				$('#amount').val(data.amount);//just save idno for edit later
				
				urlParam2.filterVal[1]=data.auditno;
			}else if(selfoper=='edit'){
				urlParam2.filterVal[1]=$('#auditno').val();
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
	
	////////////////////////////////searching/////////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#actdate_search').on('click', searchDate);

	function whenchangetodate() {
		bankcode_search.off();
		$('#bankcode_search,#actdate_from,#actdate_to').val('');
		$('#bankcode_search_hb').text('');
		urlParam.filterdate = null;
		removeValidationClass(['#bankcode_search']);

		if($('#Scol').val()=='actdate'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'], #bankcode_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'bankcode'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'],#actdate_text").hide("fast");
			$("#bankcode_text").show("fast");
			bankcode_search.on();
		} else {
			$("#bankcode_text,#actdate_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}

		if($('#Scol').val()=='actdate' || $('#Scol').val() == 'bankcode'){
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

	function ifnullsearch(){
		if($('#bankcode_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','bankcode_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.taxamt AS tot_gst', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno', 'apactdtl.idno'],
		table_name:['finance.apactdtl AS apactdtl'],
		table_id:'lineno_',
		filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', $('#adjustment').val()]
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./creditDebitTransDetail/form",
		colModel: [
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, //canSearch: true, checked: true},
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{  
						custom_element:deptcodeCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Category', name: 'category', width: 100, classes: 'wrap', editable: true,
						editrules:{required: true,custom:true, custom_func:cust_rules},
						formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:categoryCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Document', name: 'document', width: 100, classes: 'wrap', editable: true},
			{ label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
					edittype:'custom',	editoptions:
						{
							custom_element:GSTCodeCustomEdit,
						    custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 80, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					//readonly: "readonly",
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
			
			{ label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules:{required: true}, edittype:"text",
				editoptions:{
					//readonly: "readonly",
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
			{ label: 'Amount', name: 'amount', width: 80, classes: 'wrap', 
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
			
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_deptcode.check(errorField);
			dialog_category.check(errorField);
			dialog_GSTCode.check(errorField);
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
        	$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
        	errorField.length=0;
			$("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGrid2 input[name='GSTCode']").val('EP');
			dialog_GSTCode.check(errorField);

        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_deptcode.on();//start binding event on jqgrid2
			dialog_category.on();
			dialog_GSTCode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='AmtB4GST']"]);
			
			$("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2 input[name='AmtB4GST'], #jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

        	$("input[name='amount']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
        	$('#amount').val(response.responseText);
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

			if(parseInt($('#jqGrid2 input[name="amount"]').val()) == 0){
				myerrorIt_only('#jqGrid2 input[name="amount"]');
				alert('Amount cant be 0');
				return false;
			}

			mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./creditDebitTransDetail/form?"+
				$.param({
					action: 'creditDebitTransDetail_save',
					idno: $('#idno').val(),
					auditno:$('#auditno').val(),
					trantype:$('#trantype').val(),
					amount:data.amount,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm(false);
			// $('#jqGrid2').jqGrid ('setSelection', "1");
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
				    			action: 'creditDebitTransDetail_save',
								auditno: $('#auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
								trantype: $('#trantype').val(),

				    		}
				    		$.post( "./creditDebitTransDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
								mycurrency.formatOn();
								refreshGrid("#jqGrid2",urlParam2);
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

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_AmtB4GST","#"+ids[i]+"_tot_gst","#"+ids[i]+"_amount"]);				cari_gstpercent(ids[i]);

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
				//console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					mycurrency2.formatOn();
					return false;
				}

				if(parseInt($("#jqGrid2 input#"+ids[i]+"_amount").val()) == 0){
					alert('Amount cant be 0');
					mycurrency2.formatOn();
					return false;
				}

		    	var obj = 
		    	{
					'lineno_' : ids[i],
		    		'idno' : data.idno,
		    		'deptcode' : $("#jqGrid2 input#"+ids[i]+"_deptcode").val(),
		    		'category' : $("#jqGrid2 input#"+ids[i]+"_category").val(),
					'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),
					'GSTCode' : $("#jqGrid2 input#"+ids[i]+"_GSTCode").val(),
		    		'AmtB4GST' : $('#jqGrid2 input#'+ids[i]+"_AmtB4GST").val(),
		    		'tot_gst' : $('#jqGrid2 input#'+ids[i]+"_tot_gst").val(),
		    		'amount' : $('#jqGrid2 input#'+ids[i]+"_amount").val(),
		    	}

		    	jqgrid2_data.push(obj);
				console.log(jqgrid2_data);
		    }

			var param={
    			action: 'creditDebitTransDetail_save',
				_token: $("#_token").val(),
				auditno: $('#auditno').val(),
				idno: $('#idno').val(),
    			trantype: $('#trantype').val(),
    		}

    		$.post( "./creditDebitTransDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				$('#amount').val(data);
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2, 'add');
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2, 'add');
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
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;

			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';case_='bankcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('creditDebitTrans',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	//////////////////////////formatter checkbox//////////////////////////////////////////////////
	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

		if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}

		if($('#recstatus_use').val() == 'ALL'){
			if(rowObject.recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'CANCEL'){
			if(rowObject.recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}else if(rowObject.recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
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
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
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
		radbuts.check();
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
			dialog_bankcode.on();
		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_bankcode.on();
		textCol.check();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});

	//////////////////////radio button error//////////////////////////
	var radbuts=new checkradiobutton(['TaxClaimable']);

	function textcolourradio(textcolour){
		this.textcolour=textcolour;
		this.check = function(){
			$.each(this.textcolour, function( index, value ) {
				$("label[for="+value+"]").css('color', '#444444');
				$(":radio[name="+value+"]").parent('label').css('color', '#444444');
			});
		}
	}

	var textCol=new textcolourradio(['TaxClaimable']);
	///////////////////////////////////////////////////////////////////////////////

	function onall_editfunc(){
		// if($('#auditno').val()!=''){
    	// 	$("#jqGrid2 input[name='deptcode'],#jqGrid2 input[name='category'],#jqGrid2 input[name='document'],#jqGrid2 input[name='AmtB4GST'],#jqGrid2 input[name='GSTCode'],#jqGrid2 input[name='amount']").attr('readonly','readonly');

		// }else{
		// 	dialog_deptcode.on();//start binding event on jqgrid2
		// 	dialog_category.on();
		// 	dialog_GSTCode.on();

		// }

		dialog_deptcode.on();//start binding event on jqgrid2
		dialog_category.on();
		dialog_GSTCode.on();

		$("#jqGrid2 input[name='amount'], #jqGrid2 input[name='AmtB4GST'], #jqGrid2 input[name='tot_gst']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
	}

	/////////////bind shift + f to btm detail///////////
	$(document).bind('keypress', function(event) {
	    if( event.which === 70 && event.altKey ) {
	        $("#saveDetailLabel").click();
	    }
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	
	function cari_gstpercent(id){
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
			// setjqgridHeight(data,'jqGrid3');
			// calc_jq_height_onchange("jqGrid3");
		},
		gridComplete: function(){
			
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	////////////////////object for dialog handler/////////////////

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
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcode.makedialog(true);

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department',"#jqGrid2 input[name='deptcode']",errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
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
				dialog_deptcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab');
	dialog_deptcode.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
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
			}
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
				filterCol:['compcode','recstatus', 'taxtype'],
				filterVal:['session.compcode','ACTIVE', 'Input']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_AmtB4GST").focus().select();
				let data=selrowData('#'+dialog_GSTCode.gridname);
				$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
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
				dialog_GSTCode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCode.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
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

function populate_formCDT(obj){
	//panel header
	$('#bankcode_show').text(obj.bankcode);
	$('#refsource_show').text(obj.refsource);	
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();
}

function empty_formCDT(){
	$('#bankcode_show').text('');
	$('#refsource_show').text('');
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").hide();
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	// disableForm('#formCDT');
	// emptyFormdata(errorField_MMA,'#formCDT')
}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}
