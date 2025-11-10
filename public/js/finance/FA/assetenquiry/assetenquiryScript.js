$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
	stop_scroll_on();

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
	var mycurrency =new currencymode(['#origcost','#purprice','#lstytddep','#cuytddep','#nbv']);
	var showeditfunc = new showeditfunc();
	var myfail_msg_wo = new fail_msg_func('div#fail_msg_wo');

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam,{idno:selrowData("#jqGrid").idno});
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

 	/// BUTTON FOR ENQDTL FORM HEADER OUTSIDE ///
	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		$("#btn_save").data("oper","add");
		if( $('#formdata_dtl').isValid({requiredFields: ''}, conf, true) ) {
			saveFormdata("#jqGrid","#gridEnquirydtl_panel","#form_enquirydtl",oper,saveParam,urlParam,{idno:selrowData("#jqGrid").idno});
			unsaved = false;
		}else{
				mycurrency.formatOn();
		}
	},{
		text: "btn_cancel",click: function() {
			// $(this).dialog('close');
		}
	});

	$('#btn_save').on('click');


	var oper;
	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			switch(oper) {
				case state = 'add':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					rdonly("#dialogForm");
					break;
				case state = 'edit':
					mycurrency.formatOnBlur();
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#dialogForm");
					getNVB();
					//$("#assetno").val('');
					break;
				case state = 'view':
					mycurrency.formatOn();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$(this).dialog("option", "buttons",butt2);
					getmethod_and_res(selrowData("#jqGrid").assetcode);
					getRate(selrowData("#jqGrid").assetcode);
					getNVB();
					break;
			}
			if(oper!='view'){
			}
			if(oper!='add'){
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			$("#formdata a").off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	$("#msgBox").dialog({
    	autoOpen : false, 
    	modal : true,
		width: 8/10 * $(window).width(),
		open: function(){
			addParamField("#gridhist",true,urlParamhist);
			$("#gridhist").jqGrid ('setGridWidth', Math.floor($("#gridhist_c")[0].offsetWidth-$("#gridhist_c")[0].offsetLeft));

		},  
    	buttons: [{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
    });

    $("#histbut").click(function(){
    	var selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
    	if(!selRowId){
    		bootbox.alert('Please select row');
    	}else{

    		$("span[name='assetno']").text(selrowData('#jqGrid').assetno);
        	$("span[name='description']").text(selrowData('#jqGrid').description);
			
    		urlParamhist.filterVal[0] = selrowData('#jqGrid').assetno;
			$("#msgBox").dialog("open");
    	}
    });

    var urlParamhist = {
		action:'get_table_default',
		url: 'util/get_table_default',
		field:['assetno','assetcode','assettype','deptcode','olddeptcode','curloccode','oldloccode','trandate','adduser'],
		table_name:'finance.fatran',
		table_id:'deptcode',
		filterCol:['assetno'],
		filterVal:[''],
	}

	/////histbut utk apa ? nak tukar newloccode &newdeptcode boleh tak?
    $("#gridhist").jqGrid({
		datatype: "local",
		colModel: [
			{label: 'Current Dept', name: 'deptcode', classes: 'wrap'},
			{label: 'Prev Dept', name: 'olddeptcode', classes: 'wrap'},
			{label: 'Current Loc', name: 'curloccode', classes: 'wrap'},
			{label: 'Prev Loc', name: 'oldloccode', classes: 'wrap'},
			{label: 'Trandate', name: 'trandate', classes: 'wrap',formatter:dateFormatter},
			{label: 'Entered By', name: 'adduser', classes: 'wrap'},
			{label: 'Entered Date', name: 'adddate', classes: 'wrap',},
		],
			
		autowidth:true,
		viewrecords: true,
		loadonce:false,
		width: 200,
		height: 200,
		rowNum: 300,
		sortname:'idno',
        sortorder:'desc',
		pager: "#gridhistpager",
	});

	$('#searchForm [name=Scol]').on( "change",whenchangetodate);

	function whenchangetodate() {
		$(assetcode_depan.textfield+'_div,'+assettype_depan.textfield+'_div').hide();
		$(assetcode_depan.textfield+','+assettype_depan.textfield).val(' ');
		removeValidationClass([assetcode_depan.textfield,assettype_depan.textfield]);
		assettype_depan.off();
		assetcode_depan.off();
		$("input[name='Stext']").show();
		if($('input[type=radio][name=dcolr][value=assetcode]').is(':checked')){
			$("input[name='Stext']").hide();
			$(assetcode_depan.textfield+'_div').show();
			assetcode_depan.on();
		} else if($('input[type=radio][name=dcolr][value=assettype]').is(':checked')){
			$("input[name='Stext']").hide();
			$(assettype_depan.textfield+'_div').show();
			assettype_depan.on();
		} 
	}

	var assettype_depan = new ordialog(
		'assettype_depan', 'finance.fatype', '#assettype_depan', 'errorField',
		{
			colModel: [
				{ label: 'Asset Type', name: 'assettype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + assettype_depan.gridname).assettype;

				urlParam.searchCol=["assettype"];
				urlParam.searchVal=[data];
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
			title: "Select Asset Type",
			open: function () {
				assettype_depan.urlParam.filterCol = ['recstatus'];
				assettype_depan.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	assettype_depan.makedialog();

	var assetcode_depan = new ordialog(
		'assetcode_depan', 'finance.facode', '#assetcode_depan', 'errorField',
		{
			colModel: [
				{ label: 'Asset Code', name: 'assetcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + assetcode_depan.gridname).assetcode;
				urlParam.searchCol=["assetcode"];
				urlParam.searchVal=[data];
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
			title: "Select Asset Code",
			open: function () {
				assetcode_depan.urlParam.filterCol = ['recstatus'];
				assetcode_depan.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	assetcode_depan.makedialog();

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var urlParam={
		action:'get_table',
		url: './assetenquiry/table',
		field:'',
		table_name:'finance.faregister',
		table_id:'idno',
		sort_idno:true,
	}

	var saveParam={
		action:'assetenquiry_save',
		url:'./assetenquiry/form',
		field:'',
		//fixPost:'true',
		oper:oper,
		table_name:'finance.faregister',
		table_id:'idno'
	};

	/////////////////////parameter for main jqgrid url/////////////////////////////////////////////////
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true},
			{ label: 'Type', name: 'assettype', width: 6, classes: 'wrap',canSearch: true},		
			{ label: 'Category', name: 'assetcode', width: 10, classes: 'wrap', canSearch: true,formatter: showdetail, unformat:un_showdetail},		
			{ label: 'Asset No', name: 'assetno', width: 11, canSearch: true, classes: 'wrap'},
			{ label: 'Item Code', name: 'itemcode', width: 8, classes: 'wrap',hidden:true},
			{ label: 'Description', name: 'description_show', width: 28, classes: 'wrap'},
			{ label: 'Description', name: 'description', canSearch: true,checked:true,hidden:true},
			{ label: 'Serial No', name: 'serialno',classes: 'wrap',hidden:true},
			{ label: 'Lotno', name: 'lotno', width: 20,classes: 'wrap',hidden:true},
			{ label: 'Casisno', name: 'casisno', width: 20, classes: 'wrap',hidden:true},
			{ label: 'Engineno', name: 'engineno', width: 20, classes: 'wrap',hidden:true},
			{ label: 'Dept', name: 'currdeptcode', width: 12, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'Location', name: 'currloccode', width: 12, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'Invoice No', name: 'invno', width: 8, classes: 'wrap',hidden:true},
            { label: 'Invoice Date', name:'invdate', width: 8, classes:'wrap', hidden:true},
            { label: 'Qty', name: 'qty', width: 6,  align: 'right',classes: 'wrap'},
            { label: 'Cost', name: 'origcost', width: 10, classes: 'wrap', align: 'right',formatter:'currency'},
			{ label: 'Accumulated<br>Depreciation', name:'dep_calc', width:10, classes:'wrap',align: 'right', formatter: 'currency'},
			{ label: 'NBV', name:'nbv_calc', width:10, classes:'wrap',align: 'right', formatter: 'currency'},
            { label: 'Start Date', name:'statdate', width:20, classes:'wrap',  hidden:true},
			{ label: 'Post Date', name:'trandate', width:20, classes:'wrap',  hidden:true},
            { label: 'Accum Prev', name:'lstytddep', width:20, classes:'wrap', hidden:true},
            { label: 'Accum YTD', name:'cuytddep', width:20, classes:'wrap', hidden:true},
			{ label: 'NBV', name:'nbv', width:20, classes:'wrap', hidden:true, formatter: 'currency'},
			{ label: 'Method', name:'method', width:20, classes:'wrap', hidden:true},
			{ label: 'Residual Value', name:'residualvalue', width:20, classes:'wrap', hidden:true},
            { label: 'Supplier Code', name: 'suppcode', width: 15, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'Purchase Order No', name:'purordno',width: 8, classes:'wrap', hidden:true},
            { label: 'Purchase Date', name:'purdate', width: 8, classes:'wrap', hidden:true},
			{ label: 'Purchase Price', name:'purprice', width: 8, classes:'wrap', hidden:true},
            { label: 'D/O No', name: 'delordno', width: 12, classes: 'wrap'},
            { label: 'DO Date', name:'delorddate', classes:'wrap', hidden:true},
			{ label: 'Record Status', name: 'recstatus', width: 10, classes: 'wrap', hidden:false},
			{ label: 'nprefid', name: 'nprefid', width: 90,hidden:true},
			{ label: 'idno', name: 'idno', hidden: true,key:true},
			{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},
			{ label: 'Add User', name:'adduser', width:20, classes:'wrap',  hidden:true},
			{ label: 'Add Date', name:'adddate', width:20, classes:'wrap',  hidden:true},
            
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
		},
		onSelectRow:function(rowid, selected){
			if (rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				if(rowData.recstatus == 'ACTIVE'){
					$('#writeoff_btn').show();
				}else{
					if(rowData.trantype.toUpperCase() == 'FUL'){
						$('#writeoff_btn').show();
					}else{
						$('#writeoff_btn').hide();
					}
				}

				//var rowData = $('#jqGrid2').jqGrid('getRowData', rowid);

				refreshGrid('#jqGrid2', urlParam2,'kosongkan');
				//$('textarea#description').val(data['p_description']+'\n'+data['dodt_remarks']);
				$("#jqGridEnquiryDtl2_c, #jqGridtransferFA_c").hide();
				if(rowData['assetno'] != '') {//kalau assetno ada
					urlParam2.filterVal[1] = selrowData('#jqGrid').assetno;
					urlParam3.filterVal[1] = selrowData('#jqGrid').assetno;
					refreshGrid('#jqGrid2', urlParam2); //asset movement
					refreshGrid('#jqGrid3', urlParam3); //asset serial list
					$("#pg_jqGridPager3 table, #jqGridEnquiryDtl2_c, #jqGridtransferFA_c").show();
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").hide();

					populateFormdata("#jqGrid","","#formEnquiryDtl2",rowid,'view');
					populateFormdata("#jqGrid","","#formtransferFA",rowid,'view');
					populate_EnquiryDtl2AE(selrowData("#jqGrid"));
					populate_transferAE(selrowData("#jqGrid"));
					populate_form_movementAE(selrowData("#jqGrid"));
					populate_form_SerialAE(selrowData("#jqGrid"));

					if(parseInt(selrowData('#jqGrid').qty)<=1){
						$('#jqGrid3_c').hide();
					}else{
						$('#jqGrid3_c').show();
					}
					
				}else{
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").show();
				}
				
				$("#pdfgen1").attr('href','./assetenquiry/showpdf?assetno='+selrowData("#jqGrid").assetno);
			}
		},
		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2],
							function(){
								fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
							}
						)
					});
				}
			});
			$('#jqGrid').jqGrid ('setSelection', $('#jqGrid').jqGrid ('getDataIDs')[0]);
			fdl.set_array().reset();
			calc_jq_height_onchange("jqGrid");
		}
	});

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;

		switch(options.colModel.name){
			//case 'bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'currdeptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'suppcode':field=['SuppCode','name'];table="material.supplier";case_='suppcode';break;
			case 'olddeptcode':field=['deptcode','description'];table="sysdb.department";case_='olddeptcode';break;
			case 'currloccode':field=['loccode','description'];table="sysdb.location";case_='loccode';break;
			case 'itemcode':field=['itemcode','description'];table="finance.faregister";case_='itemcode';break;
			case 'assetcode':field=['assetcode','description'];table="finance.facode";case_='assetcode';break;
			case 'assettype':field=['assettype','description'];table="finance.fatype";case_='assettype';break;
			case 'trf_currdeptcode':field=['deptcode','description'];table="sysdb.department";case_='trf_currdeptcode';break;
			case 'trf_currloccode':field=['loccode','description'];table="sysdb.location";case_='trf_currloccode';break;
			case 'trf_department':field=['deptcode','description'];table="sysdb.department";case_='trf_department';break;
			case 'trf_loccode':field=['loccode','description'];table="sysdb.location";case_='trf_loccode';break;

			//////asset movement header/////
			case 'oldloccode':field=['loccode','description'];table="sysdb.location";case_='oldloccode';break;
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'curloccode':field=['loccode','description'];table="sysdb.location";case_='curloccode';break;
		
			default: return cellvalue;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('assetenquiry',options,param,case_,cellvalue);
		delay(function(){
			calc_jq_height_onchange(options.gid);
		}, 500 );
		
		if(cellvalue==null)return "";
		return cellvalue;
	}

	function getNVB() {
		var origcost = parseFloat($("#formdata input[name='origcost']").val());
		var lstytddep = parseFloat($("#formdata input[name='lstytddep']").val());
		var cuytddep = parseFloat($("#formdata input[name='cuytddep']").val());

		var total = origcost - lstytddep - cuytddep;
		console.log(total)
		$("#formdata input[name='nbv']").val(total.toFixed(2));
	}

	$("#origcost").keydown(function(e) {
		delay(function(){
			var origcost = $("#origcost").val();
			var lstytddep = $("#lstytddep").val();
			var cuytddep = $("#cuytddep").val();

			if($("#origcost").val() == '') {
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(total.toFixed(2));
			}
			else{
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(total.toFixed(2));
			}
		}, 1000 );
	});

	$("#lstytddep").keydown(function(e) {
		delay(function(){
			var origcost = currencyRealval("#origcost");
			var lstytddep = currencyRealval("#lstytddep");
			var cuytddep = currencyRealval("#cuytddep");

			if($("#lstytddep").val() == '') {
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(numeral(total).format('0,0.00'));
			}
			else{
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(numeral(total).format('0,0.00'));
			}
		}, 1000 );
	});

	$("#cuytddep").keydown(function(e) {
		delay(function(){
			var origcost = currencyRealval("#origcost");
			var lstytddep = currencyRealval("#lstytddep");
			var cuytddep = currencyRealval("#cuytddep");

			if($("#cuytddep").val() == '') {
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(numeral(total).format('0,0.00'));
			}
			else{
				total = origcost - lstytddep - cuytddep;
				$("#nbv").val(numeral(total).format('0,0.00'));
			}
		}, 1000 );
	});

	function getmethod_and_res(assetcode){
		var param={
			action:'get_value_default',
			field:['method','residualvalue'],
			table_name:'finance.facode',
			table_id:'idno',
			filterCol:['assetcode'],
			filterVal:[assetcode],
		}
		$.get( "util/get_value_default"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data)){
					$("#method").val(data.rows[0].method);
					$("#rvalue").val(data.rows[0].residualvalue);
				}
			});
	}

	function getRate(assetcode){
		var param={
			action:'get_value_default',
			field:['rate'],
			table_name:'finance.facode',
			table_id:'idno',
			filterCol:['assetcode'],
			filterVal:[assetcode],
		}
		$.get( "util/get_value_default"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data)){
					$("#rate").val(data.rows[0].rate);
					//$("#rvalue").val(data.rows[0].residualvalue);
				}
			});
	}

	function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".short-full")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
	
	////////////////////////////formatter//////////////////////////////////////////////////////////
	function dateFormatter(cellvalue, options, rowObject){
		return moment(cellvalue).format("DD-MM-YYYY");
	}
       
   $("#jqGrid").jqGrid('setLabel', 'origcost', 'Cost', {'text-align':'right'});
   $("#jqGrid").jqGrid('setLabel', 'qty', 'Qty', {'text-align':'right'});

	//////////////////////////////////////////myEditOptions jqGrid1/////////////////////////////////////////////

	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
        	//console.log(rowid);
        	/*linenotoedit = rowid;
        	$("#jqGrid2").find(".rem_but[data-assetlineno!='"+linenotoedit+"']").prop("disabled", true);
        	$("#jqGrid2").find(".rem_but[data-assetlineno='undefined']").prop("disabled", false);*/
        },
        aftersavefunc: function (rowid, response, options) {
           $('#amount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	if(addmore_jqgrid2.edit == false)linenotoedit = null; 
        	//linenotoedit = null;

        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
        	/*if(errorField.length>0)return false;

			let data = selrowData('#jqGrid2');
			let editurl = "./inventoryTransactionDetail/form?"+
				$.param({
					action: 'invTranDetail_save',
					docno:$('#docno').val(),
					recno:$('#recno').val(),
				});*/
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			/*hideatdialogForm(false);*/
	    }
    };

	//////////////////////////////////////////myEditOptions jqGrid2 ASSET MOVEMENT /////////////////////////////////////////////
	var myEditOptions_jqGrid2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			//if(addmore_jqgrid2.edit == false)linenotoedit = null; 
			//linenotoedit = null;

			refreshGrid('#jqGrid',urlParam2,'add');
			refreshGrid('#jqGrid2',urlParam2,'add');
	 	},
	};
				
	//////////////////////////////////////////myEditOptions jqGrid3 ASSET SERIAL LIST/////////////////////////////////////////////
    var myEditOptions_jqGrid3 = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
			dialog_loccode_jq3.on();
			dialog_deptcode_jq3.on();
        	/*linenotoedit = rowid;
        	$("#jqGrid2").find(".rem_but[data-assetlineno!='"+linenotoedit+"']").prop("disabled", true);
        	$("#jqGrid2").find(".rem_but[data-assetlineno='undefined']").prop("disabled", false);*/
        },
        aftersavefunc: function (rowid, response, options) {
           	$('#amount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	if(addmore_jqgrid2.edit == false)linenotoedit = null; 
        	//linenotoedit = null;

        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
			// let data = selrowData('#jqGrid2');
			// let editurl = "./assetenquiry/form?"+
			// 	$.param({
			// 		oper: 'comp_edit',
			// 		_token:$('#_token').val()
			// 	});
			// $("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			/*hideatdialogForm(false);*/
	    }
    };

	/////////////////////////start grid1 pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	});
	//////////////////////////////////////end grid1/////////////////////////////////////////////////////////

	/////////////////////////start grid pager 1/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,oper);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			let idno = selrowData('#jqGrid').idno;
			if(!idno){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':idno});
			}
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
			recstatusDisable();
		}, 
	});

	function populate_form_movementAE(rowdata){
		$('#category_show_movementAE').text(selrowData("#jqGrid").category);
		$('#assetno_show_movementAE').text(selrowData("#jqGrid").assetno);
		$('#description_show_movementAE').html('Description: '+selrowData("#jqGrid").description);
		set_seemore.set();
	}

	function empty_form_movementAE(){
		$('#category_show_movementAE').text('');
		$('#assetno_show_movementAE').text('');
		$('#description_show_movementAE').html('');
		button_state_movementAE('empty');
	}

	function populate_form_SerialAE(rowdata){
		$('#category_show_SerialAE').text(selrowData("#jqGrid").category);
		$('#assetno_show_SerialAE').text(selrowData("#jqGrid").assetno);
		$('#description_show_SerialAE').html('Description: '+selrowData("#jqGrid").description);
		set_seemore.set();
	}

	function empty_form_SerialAE(){
		$('#category_show_SerialAE').text('');
		$('#assetno_show_SerialAE').text('');
		$('#description_show_SerialAE').html('');
		button_state_SerialAE('empty');
	}

	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////
	
	/////////////////////////////parameter for saving jqgrid2 url Asset Movement///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['ft.assetcode','ft.assettype','ft.assetno','ft.auditno','ft.trandate','ft.trantype','ft.amount','ft.deptcode','ft.olddeptcode','ft.curloccode','ft.oldloccode','ft.idno'],
		table_name:['finance.fatran AS ft',' finance.faregister AS fr'],
		table_id:'idno',
		join_type:['LEFT JOIN'],
		join_onCol:['ft.assetcode'],
		join_onVal:['fr.assetcode'],
        join_filterCol : [['ft.assettype on =','ft.assetno on =']],
        join_filterVal : [['fr.assettype','fr.assetno']],
		filterCol:['ft.compcode','ft.assetno'],
		filterVal:['session.compcode','']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
	
	//////////////////////////////////////////////start jqgrid2 Asset Movement Header//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'No.', name:'auditno', width:30, classes:'wrap', align: 'right'},
			{ label: 'Tran Date', name:'trandate', width:50, classes:'wrap'},
			{ label: 'Tran Type', name:'trantype', width:50, classes:'wrap'},
			{ label: 'Amount', name:'amount', width:80, classes:'wrap', align: 'right'},
			{ label: 'Old Department', name: 'olddeptcode', width: 120, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Old Location', name: 'oldloccode', width: 120, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'New Department', name: 'deptcode', width: 120, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'New Location', name: 'curloccode', width: 120, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},

		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'trandate desc, trantype desc',
        // sortorder:'desc',
		pager: "#jqGridPager2",
		onSelectRow:function(rowid, selected){
			// populate_form_movementAE(selrowData("#jqGrid_trf"));
		},
		loadComplete: function(data){
			setjqgridHeight(data,'jqGrid2');
			calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
		/*	$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});*/
		/*	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);*/
		fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		   // $("#expdate").datepicker();
		},
		beforeSubmit: function(postdata, rowid){ 
		/*	dialog_itemcode.check(errorField);
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);*/
	 	}
    });

	//////////////////////////////////////////start pager jqgrid2 ASSET MOVEMENT HEADER/////////////////////////////////////////////
	// $("#jqGrid2").inlineNav('#jqGridPager2',{	
	// 	add:false,
	// 	edit:false,
	// 	cancel: false,
	// 	restoreAfterSelect: false,
	// 	// addParams: { 
	// 	// 	addRowParams: myEditOptions_jqGrid2
	// 	// },
	// 	// editParams: myEditOptions_jqGrid2
	// });

	/////////////////////////////////// end pagergrid2 /////////////////////////////////////////////////////

	/////////////////////////////parameter for jqgrid3 url Asset Serial List///////////////////////////////////////////////
	var urlParam3={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['fc.assetcode','fc.assettype','fr.description','fc.assetno','fc.assetlineno','fc.loccode','fc.deptcode','fc.idno','fc.trackingno','fc.bem_no','fc.ppmschedule'],
		table_name:['finance.facompnt AS fc',' finance.faregister AS fr'],
		table_id:'idno',
		join_type:['LEFT JOIN'],
		join_onCol:['fc.assetno'],
		join_onVal:['fr.assetno'],
		join_filterCol:[['fc.assetcode on =','fc.assettype on =']],
		join_filterVal:[['fr.assetcode','fr.assettype']],
		filterCol:['fc.compcode','fc.assetno'],
		filterVal:['session.compcode','']
	};

	var addmore_jqgrid3={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid3, state true kalu

	$("#jqGridtransferFA_panel2")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			dialog_deptcode.on();
			dialog_loccode.on();
			frozeOnEdit("#formtransferFA2");
		},
		close: function( event, ui ) {
			dialog_deptcode.off();
			dialog_loccode.off();
		},
		buttons :[{
			text: "Save",click: function() {
				if( $('#formtransferFA2').isValid({requiredFields: ''}, conf, true) ) {
					var saveParam={
				        action:'save_table_transferFA_compnt',
				        oper:'transfer2'
				    }
				    var postobj={
				    	idno_fr : selrowData('#jqGrid').idno,
				    	idno_fc : selrowData('#jqGrid3').idno,
				    	_token : $('#_token').val(),
				    };

					values = $("#formtransferFA2").serializeArray();

				    $.post( "./assettransfer2/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
						refreshGrid('#jqGrid3', urlParam3);
				    	$("#jqGridtransferFA_panel2").dialog('close');
				        
				    },'json').fail(function(data) {
						refreshGrid('#jqGrid3', urlParam3);
				    	$("#jqGridtransferFA_panel2").dialog('close');
				        // alert('there is an error');
				    }).success(function(data){
						refreshGrid('#jqGrid3', urlParam3);
				    	$("#jqGridtransferFA_panel2").dialog('close');
				    });
				}
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}],
	  });
	
	//////////////////////////////////////////////start jqgrid3 Asset Serial List//////////////////////////////////////////////
	

	$("#jqGrid3").jqGrid({
		datatype: "local",
		editurl: "./assetenquiry/form?action=comp_edit",
		colModel: [
			{ label: 'Asset Code', name:'assetcode', width:5, classes:'wrap'},
			{ label: 'Asset Type', name:'assettype', width:5, classes:'wrap'},
			{ label: 'description', name:'description', hidden:true},
			{ label: 'Asset No', name:'assetno', width:5, classes:'wrap'},
			{ label: 'Line No', name: 'assetlineno', width: 3, classes: 'wrap'},
			{ label: 'Department Code', name: 'deptcode', width: 7, classes: 'wrap', editable:true,
			editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{  custom_element:deptcodeCustomEdit,
					   custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Location Code', name: 'loccode', width: 7, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:loccodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Tracking No', name: 'trackingno', width: 7, classes: 'wrap', editable:true},
			{ label: 'BEM No', name: 'bem_no', width: 7, classes: 'wrap', editable:true},
			{ label: 'PPM', name: 'ppmschedule', width: 7, classes: 'wrap', editable:true},
			{ label: 'idno', name: 'idno', width: 7, classes: 'wrap', hidden:true, key:true},
			{ label: 'Edit', name: 'compcode', width: 10, formatter:show_edit},

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
		// sortorder: 'assetlineno',
		pager: "#jqGridPager3",
		onSelectRow:function(rowid, selected){
			// populate_form_SerialAE(selrowData("#jqGrid_trf"));
		},
		loadComplete: function(data){
			/*if(addmore_jqgrid3.more == true){$('#jqGrid3_iladd').click();}
			else{
				$('#jqGrid3').jqGrid ('setSelection', "1");
			}
			addmore_jqgrid3.edit = addmore_jqgrid3.more = false;*/ //reset
			setjqgridHeight(data,'jqGrid3');
        	showeditfunc.off().on();
			calc_jq_height_onchange("jqGrid3");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		   // $("#expdate").datepicker();
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid3_iledit").click();
		},
	});

	function show_edit(cellvalue, options, rowObject){
        let idno = rowObject.idno;

		return `<button title="Edit" type="button" class="btn btn-xs btn-warning btn-md btn_edit" data-idno=`+idno+`><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>&nbsp;&nbsp;<button title="Save" type="button" class="btn btn-xs btn-success btn-md btn_save" data-idno=`+idno+` disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>&nbsp;&nbsp;<button title="Cancel" type="button" class="btn btn-xs btn-danger btn-md btn_cancel" data-idno=`+idno+` disabled><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>`;
	}

	$("#jqGrid2_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#jqGrid2_panel",100);
		refreshGrid('#jqGrid2', urlParam2);
		calc_jq_height_onchange("jqGrid2");
	});


	function showeditfunc(){
		this.on = function(){
			$('#jqGrid3 .btn_edit').on('click',{data:this},onedit);
			$('#jqGrid3 .btn_save').on('click',{data:this},onsave);
			$('#jqGrid3 .btn_cancel').on('click',{data:this},oncancel);
		}

		this.off= function(){
			$('#jqGrid3 .btn_edit').off('click',onedit);
			return this;
		}
		this.lastsel = 0;

		function onedit(event){
			var obj = event.data.data;
			let idno = $(this).data('idno');
			if(obj.lastSel!=undefined){
				oncancel(null,obj.lastSel)
				$('#jqGrid3').restoreRow(obj.lastSel);
			}
			$('#jqGrid3').jqGrid ('setSelection', idno);
			obj.lastSel = idno
			$('#jqGrid3_iledit').click();
			$('#jqGrid3 .btn_edit[data-idno='+idno+']').prop('disabled',true);
			$('#jqGrid3 .btn_save[data-idno='+idno+']').prop('disabled',false);
			$('#jqGrid3 .btn_cancel[data-idno='+idno+']').prop('disabled',false);
		}

		function onsave(event){
			$('#jqGrid3_ilsave').click();
			$('#jqGrid3 .btn_edit').prop('disabled',false);
			$('#jqGrid3 .btn_save').prop('disabled',true);
			$('#jqGrid3 .btn_cancel').prop('disabled',true);

			refreshGrid("#jqGrid",urlParam);
		}

		function oncancel(event,idno_=null){
			let idno = null;
			if(event!=null){
				var obj = event.data.data;
				idno = $(this).data('idno');
			}else{
				idno = idno_
			}
			$('#jqGrid3_ilcancel').click();
			$('#jqGrid3 .btn_edit[data-idno='+idno+']').prop('disabled',false);
			$('#jqGrid3 .btn_save[data-idno='+idno+']').prop('disabled',true);
			$('#jqGrid3 .btn_cancel[data-idno='+idno+']').prop('disabled',true);
		}

	}

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Location Code':temp=$('#jqGrid3 input[name=loccode]');break;
			case 'Department Code':temp=$('#jqGrid3 input[name=deptcode]');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function loccodeCustomEdit(val,opt){
		console.log(val)
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="loccode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function deptcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}



	var dialog_loccode_jq3 = new ordialog(
		'loccode3','sysdb.location',"#jqGrid3 input[name='loccode']",'errorField',
		{	colModel:
			[
				{label:'Location',name:'loccode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(event){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},{
			title:"Select location",
			open: function(){
				dialog_loccode_jq3.urlParam.filterCol=['compcode','recstatus'];
				dialog_loccode_jq3.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				
			}
		},'urlParam','radio','tab'
	);
	dialog_loccode_jq3.makedialog();

	var dialog_deptcode_jq3 = new ordialog(
		'deptcode3','sysdb.department',"#jqGrid3 input[name='deptcode']",'errorField',
		{	colModel:
			[
				{label:'Location',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(event){
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},{
			title:"Select department",
			open: function(){
				dialog_deptcode_jq3.urlParam.filterCol=['compcode','recstatus'];
				dialog_deptcode_jq3.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode_jq3.makedialog();
		
	//////////////////////////////////////////start pager jqgrid3 ASSET SERIAL LIST/////////////////////////////////////////////
	$("#jqGrid3").inlineNav('#jqGridPager3',{	
		add:false,
		edit:true,
		cancel:true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_jqGrid3
		},
		editParams: myEditOptions_jqGrid3
	});
	// .jqGrid('navButtonAdd',"#jqGridPager3",{
	// 	id: "jqGridPager3transfer",
	// 	caption:"",cursor: "pointer",position: "last", 
	// 	buttonicon:"glyphicon glyphicon-transfer",
	// 	title:"transfer",
	// 	onClickButton: function(){
	// 		var selRowId = $("#jqGrid3").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 		}else{
	// 			var data = selrowData("#jqGrid3")
	// 			$("#jqGridtransferFA_panel2 input[name='assetno']").val(data.assetno);
	// 			$("#jqGridtransferFA_panel2 input[name='description']").val(data.description);
	// 			$("#jqGridtransferFA_panel2 input[name='assetcode']").val(data.assetcode);
	// 			$("#jqGridtransferFA_panel2 input[name='assettype']").val(data.assettype);
	// 			$("#jqGridtransferFA_panel2 input[name='deptcode']").val(data.deptcode);
	// 			$("#jqGridtransferFA_panel2 input[name='loccode']").val(data.loccode);
	// 			$("#jqGridtransferFA_panel2").dialog('open');
	// 		}
	// 	},			
	// });

	
	
	/////////////////////////////////// end pagergrid3 /////////////////////////////////////////////////////
	
	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam,['description_show','dep_calc','nbv_calc']);
	addParamField('#jqGrid', false, saveParam, ['idno','adduser','adddate','upduser','upddate']);
	// addParamField('#jqGrid2',true,urlParam2);
	// addParamField('#jqGrid3',true,urlParam3);

	$("#jqGrid2_panel").on("show.bs.collapse", function(){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	////////seemore untuk panel header/////////////////
	var set_seemore = new set_seemore(['show_movementAE','show_enquiryAE','show_transferAE','show_SerialAE']);
	function set_seemore(array){
		this.array = array;

		this.set = function(){
			this.array.forEach(function(i,e){
				if ($('#description_'+i).prop('scrollHeight') - 1 > $('#description_'+i).prop('clientHeight')){
					$('#seemore_'+i).show();
				}else{
					$('#seemore_'+i).hide();
				}

				$('#seemore_'+i).unbind('click');
				$('#seemore_'+i).click(function(){
					var show = $(this).data('show');
					if(show == false){
						$(this).data('show',true);
						$(this).text('see less')
						$('#description_'+i).css('max-height','200px');
					}else{
						$(this).data('show',false);
						$(this).text('see more')
						$('#description_'+i).css('max-height','16px');
					}
				});

			});
		}
	}

	$('#jqGridEnquiryDtl2_panel').on('shown.bs.collapse', function () {
		SmoothScrollTo('#'+$('div.mainpanel[aria-expanded=true]').parent('div.panel.panel-default').attr('id'), 300,0,undefined);
	});

	$("#dialog_writeoff")
	  .dialog({
		width: 8/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			myfail_msg_wo.clear_fail();
			let data = selrowData('#jqGrid');

			$('#assetno_wo').val(data.assetno);
			$('#desc_wo').val(data.description);
			$('#origcost_wo').val(data.origcost);
			$('#date_wo').val(moment().format('YYYY-MM-DD'));
			$('#accum_wo').val(data.dep_calc);
			$('#nbv_wo').val(data.nbv_calc);

		},
		close: function( event, ui ) {
			refreshGrid("#jqGrid", urlParam);
			myfail_msg_wo.clear_fail();

			$('#assetno_wo').val('');
			$('#desc_wo').val('');
			$('#origcost_wo').val('');
			$('#date_wo').val(moment().format('YYYY-MM-DD'));
			$('#accum_wo').val('');
			$('#nbv_wo').val('');
			$('#remarks_wo').val('');
		}
	  });

	$('#writeoff_btn').click(function(){
		$("#dialog_writeoff").dialog('open');
	});

	$('#save_wo').click(function(){
		myfail_msg_wo.clear_fail();

    	if($('#formdata_wo').isValid({requiredFields:''},conf,true)){
    		$('#save_wo').attr('disabled',true);

			let seldata = selrowData('#jqGrid');

			var param={
				action:'writeoff_act',
				url: './assetenquiry/table',
				idno_h: seldata.idno,
				assetno_wo:$('#assetno_wo').val(),
				desc_wo:$('#desc_wo').val(),
				origcost_wo:$('#origcost_wo').val(),
				date_wo:$('#date_wo').val(),
				accum_wo:$('#accum_wo').val(),
				nbv_wo:$('#nbv_wo').val(),
				remarks_wo:$('#remarks_wo').val(),
			}
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			}).fail(function(data) {
				myfail_msg_wo.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
				$('#save_wo').attr('disabled',false);
			}).success(function(data) {

				$('#save_wo').attr('disabled',false);
				$("#dialog_writeoff").dialog('close');
			});
    	}
	});

});


function stop_scroll_on(){
	$('div.paneldiv').on('mouseenter',function(){
		SmoothScrollTo('#'+$('div.mainpanel[aria-expanded=true]').parent('div.panel.panel-default').attr('id'), 300,0,undefined);
		$('body').addClass('stop-scrolling');
	});

	$('div.paneldiv').on('mouseleave',function(){
		$('body').removeClass('stop-scrolling')
	});
}

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight<80){
// 		scrollHeight = 80;
// 	}else if(scrollHeight>300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }