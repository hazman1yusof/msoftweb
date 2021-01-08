$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

var urlParam={
	action:'get_table_default',
	url: '/util/get_table_default',
	field:'',
	table_name:'finance.faregister',
	table_id:'idno',
	sort_idno:true,
}

$(document).ready(function () {
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	var mycurrency =new currencymode(['#origcost','#purprice','#lstytddep','#cuytddep','#nbv']);
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

 	/// BUTTON FOR ENQDTL FORM OUTSIDE ///
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
		url: '/util/get_table_default',
		field:['assetno','assetcode','assettype','deptcode','olddeptcode','curloccode','oldloccode','trandate','adduser'],
		table_name:'finance.fatran',
		table_id:'deptcode',
		filterCol:['assetno'],
		filterVal:[''],
	}

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

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'assetenquiry_save',
		url:'/assetenquiry/form',
		field:'',
		//fixPost:'true',
		oper:oper,
		table_name:'finance.faregister',
		table_id:'idno'
	};

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Type', name: 'assettype', width: 10, classes: 'wrap',canSearch: true},		
			{ label: 'Category', name: 'assetcode', width: 15, classes: 'wrap', canSearch: true},		
			{ label: 'Asset No', name: 'assetno', width: 10, classes: 'wrap'},
			{ label: 'Item Code', name: 'itemcode', width: 20, classes: 'wrap',hidden:true},
			{ label: 'Description', name: 'description', width: 40, classes: 'wrap', canSearch: true,checked:true},
			{ label: 'Serial No', name: 'serialno', width: 20,classes: 'wrap',hidden:true},
			{ label: 'Lotno', name: 'lotno', width: 20,classes: 'wrap',hidden:true},
			{ label: 'Casisno', name: 'casisno', width: 20, classes: 'wrap',hidden:true},
			{ label: 'Engineno', name: 'engineno', width: 20, classes: 'wrap',hidden:true},
			{ label: 'Department', name: 'deptcode', width: 20, classes: 'wrap'},
            { label: 'Location', name: 'loccode', width: 20, classes: 'wrap'},
            { label: 'Invoice No', name: 'invno', width: 20, classes: 'wrap',hidden:true},
            { label: 'Invoice Date', name:'invdate', width: 20, classes:'wrap', hidden:true},
            { label: 'Quantity', name: 'qty', width: 20,  align: 'right',classes: 'wrap'},
            { label: 'Start Date', name:'statdate', width:20, classes:'wrap',  hidden:true},
			{ label: 'Post Date', name:'trandate', width:20, classes:'wrap',  hidden:true},
            { label: 'lstytddep', name:'lstytddep', width:20, classes:'wrap', hidden:true},
            { label: 'cuytddep', name:'cuytddep', width:20, classes:'wrap', hidden:true},
            { label: 'Cost', name: 'origcost', width: 20, classes: 'wrap', align: 'right',formatter:'currency'},
            { label: 'SuppCode', name: 'suppcode', width: 20, classes: 'wrap'},
            { label: 'Purchase Order No', name:'purordno',width: 20, classes:'wrap', hidden:true},
            { label: 'Purchase Date', name:'purdate', width: 20, classes:'wrap', hidden:true},
			{ label: 'Purchase Price', name:'purprice', width: 20, classes:'wrap', hidden:true},
            { label: 'D/O No', name: 'delordno', width: 20, classes: 'wrap'},
            { label: 'DO Date', name:'delorddate', width: 20, classes:'wrap', hidden:true},
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', hidden:true, cellattr: function(rowid, cellvalue)
				{
					return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''
				}, 
			},
			{ label: 'nprefid', name: 'nprefid', width: 90,hidden:true},
			{label: 'idno', name: 'idno', hidden: true},
			{ label: 'Tran Type', name:'trantype', width:20, classes:'wrap', hidden:true},
			{ label: 'Add User', name:'adduser', width:20, classes:'wrap',  hidden:true},
			{ label: 'Add Date', name:'adddate', width:20, classes:'wrap',  hidden:true},
            
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
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
		},

		// onSelectRow:function(rowid, selected){
		// 	urlParam2.filterVal[1]=selrowData("#jqGrid").assetno;
		// 	refreshGrid("#jqGrid2",urlParam2);

		// 	populateFormdata("#jqGrid","","#form_enquirydtl",rowid,'view');
		// },
		onSelectRow:function(rowid, selected){
			if (rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid2', urlParam2,'kosongkan');
				$("#jqGridEnquiryDtl2_c, #jqGridtransferFA_c").hide();
				if(rowData['assetno'] != '') {//kalau assetno ada
					urlParam2.filterVal[0] = selrowData('#jqGrid').assetno;
					refreshGrid('#jqGrid2', urlParam2);
					$("#pg_jqGridPager3 table, #jqGridEnquiryDtl2_c, #jqGridtransferFA_c").show();
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").hide();

					populateFormdata("#jqGrid","","#formEnquiryDtl2",rowid,'view');
					populateFormdata("#jqGrid","","#formtransferFA",rowid,'view');
					populate_EnquiryDtl2AE(selrowData("#jqGrid"));
					populate_transferAE(selrowData("#jqGrid"));
					populate_form_movementAE(selrowData("#jqGrid"));
					// populate_doctorNote(selrowData("#jqGrid"));
					// populate_dietOrder(selrowData("#jqGrid"));
					
				}else{
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").show();
				}
			}
		},
		loadComplete: function(){
			// $('#jqGrid').jqGrid ('setSelection', $('#jqGrid').jqGrid ('getDataIDs')[0]);
			//button_state_ti('triage');
		}
		
	});

	function showdetail(cellvalue, options, rowObject){
		var field,table;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
			case 'olddeptcode':field=['deptcode','description'];table="sysdb.department";break;
			case 'loccode':field=['catcode','description'];table="material.category";break;
			case 'suppcode':field=['taxcode','description'];table="hisdb.taxmast";break;
			case 'itemcode':field=['itemcode','description'];table="finance.faregister";case_='itemcode';break;
			case 'assetcode': field = ['assetcode', 'description']; table = "finance.faregister";case_='assetcode';break;
			case 'assettype': field = ['assettype', 'assettype']; table = "finance.faregister";case_='assettype';break;

			case 'trf_currdeptcode': field = ['deptcode', 'description']; table = "sysdb.department";case_='trf_currdeptcode';break;
			case 'trf_currloccode': field = ['deptcode', 'description']; table = "sysdb.location";case_='trf_currloccode';break;
			case 'trf_department': field = ['deptcode', 'description']; table = "sysdb.department";case_='trf_department';break;
			case 'trf_loccode': field = ['deptcode', 'description']; table = "sysdb.location";case_='trf_loccode';break;
			default: return cellvalue;
		}
		var param={action:'input_check',table:table,field:field,value:cellvalue};
		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.row)){
				$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
			}
		});
		return cellvalue;
	}

	function getNVB() {
		var origcost = $("#origcost").val();
		var lstytddep = $("#lstytddep").val();
		var cuytddep = $("#cuytddep").val();

		total = origcost - lstytddep - cuytddep;
		$("#nbv").val(total.toFixed(2));
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
		$.get( "/util/get_value_default"+$.param(param), function( data ) {
				
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
		$.get( "/util/get_value_default"+$.param(param), function( data ) {
				
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
   $("#jqGrid").jqGrid('setLabel', 'qty', 'Quantity', {'text-align':'right'});

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

        	refreshGrid('#jqGrid2',urlParam2,'add');
        	$("#jqGridPager2Delete").show();
        }, 
        beforeSaveRow: function(options, rowid) {
        	/*if(errorField.length>0)return false;

			let data = selrowData('#jqGrid2');
			let editurl = "/inventoryTransactionDetail/form?"+
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

	/////////////////////////start grid1 pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	});
	//////////////////////////////////////end grid1/////////////////////////////////////////////////////////


	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['fr.trandate','fr.trantype','ft.amount','fr.deptcode','ft.curloccode','ft.olddeptcode','ft.oldloccode','fr.idno'],
		table_name:['finance.fatran AS ft',' finance.faregister AS fr'],
		table_id:'idno',
		join_type:['LEFT JOIN'],
		join_onCol:['ft.assetno'],
		join_onVal:['fr.assetno'],
		filterCol:['ft.compcode','ft.assetno'],
		filterVal:['session.compcode','']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
	//////////////////////////////////////////////start jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Tran Date', name:'trandate', width:100, classes:'wrap'},
			{ label: 'Tran Type', name:'trantype', width:120, classes:'wrap'},
			{ label: 'Amount', name:'amount', width:100, classes:'wrap'},
			{ label: 'Department', name: 'deptcode', width: 120, classes: 'wrap'},
			{ label: 'Current Location', name: 'curloccode', width: 120, classes: 'wrap'},
			{ label: 'Prev Department', name: 'olddeptcode', width: 120, classes: 'wrap'},
			{ label: 'Prev Location', name: 'oldloccode', width: 120, classes: 'wrap'},
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
		sortname: 'idno',
		// sortorder: "fr.trandate",
		pager: "#jqGridPager2",
		onSelectRow:function(rowid, selected){
			populate_form_movementAE(selrowData("#jqGrid_trf"));
		},
		loadComplete: function(data){
			/*if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false;*/ //reset
			setjqgridHeight(data,'jqGrid2');
		},
		gridComplete: function(){
		/*	$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
		/*	fdl.set_array().reset();
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);*/
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
		$('#description_show_movementAE').text(selrowData("#jqGrid").description);
	}

	function empty_form_movementAE(){
		$('#category_show_movementAE').text('');
		$('#assetno_show_movementAE').text('');
		$('#description_show_movementAE').text('');
		button_state_movementAE('empty');
	}

	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////

	 //////////////////////////////////////////start pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:false,
		edit:false,
		cancel: false,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	});

	/////////////////////////////////// end pagergrid2 /////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','adduser','adddate','upduser','upddate']);
	// addParamField('#jqGrid2',true,urlParam2);

	$("#jqGrid2_panel").on("show.bs.collapse", function(){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
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
});
