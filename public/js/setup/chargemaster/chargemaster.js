
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
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
	
	var mycurrency =new currencymode(['#grandtot1','#grandtot2','#grandtot3']);	
	var mycurrency2 =new currencymode([]);
	var fdl = new faster_detail_load();
	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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

	var oper='add';
	$("#dialogForm")
		.dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			
			$("#jqGrid2_c,#jqGridPkg2_c").hide();
			parent_close_disabled(true);

			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();

					hideatdialogForm(true);
					$("#jqGrid2_c").show();
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));

					enableForm('#formdata',['uom_product']);
					rdonly('#formdata');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					enableForm('#formdata',['uom_product']);
					rdonly('#formdata');
					frozeOnEdit("#formdata");
					recstatusDisable("recstatus");

					if(selrowData('#jqGrid').chgtype == 'pkg' || selrowData('#jqGrid').chgtype == 'PKG' ){
						hideatdialogForm_jqGridPkg2(true)
						refreshGrid("#jqGridPkg2",urlParam2);
						$("#jqGridPkg2_c").show();
						$("#jqGridPkg2").jqGrid ('setGridWidth', Math.floor($("#jqGridPkg2_c")[0].offsetWidth-$("#jqGridPkg2_c")[0].offsetLeft));
					} else {
						hideatdialogForm(true);
						refreshGrid("#jqGrid2",urlParam2);
						$("#jqGrid2_c").show();
						$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					}
					
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table, #pg_jqGridPagerPkg2 table").hide();

					if(selrowData('#jqGrid').chgtype == 'pkg' || selrowData('#jqGrid').chgtype == 'PKG' ){
						hideatdialogForm_jqGridPkg2(true)
						refreshGrid("#jqGridPkg2",urlParam2);
						$("#jqGridPkg2_c").show();
						$("#jqGridPkg2").jqGrid ('setGridWidth', Math.floor($("#jqGridPkg2_c")[0].offsetWidth-$("#jqGridPkg2_c")[0].offsetLeft));
					} else {
						hideatdialogForm(true);
						refreshGrid("#jqGrid2",urlParam2);
						$("#jqGrid2_c").show();
						$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					}

					break;
			}
			if(oper!='view'){
				check_chgclass_on_open();
				dialog_chggroup.on();
				dialog_chgclass.on();
				dialog_chgtype.on();
				dialog_doctorcode.on();
				dialog_deptcode.on();
				dialog_uom.on();
			}
			if(oper!='add'){
				///toggleFormData('#jqGrid','#formdata');
				dialog_chggroup.check(errorField);
				dialog_chgclass.check(errorField);
				dialog_chgtype.check(errorField);
				dialog_doctorcode.check(errorField);
				dialog_deptcode.check(errorField);
				dialog_uom.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			//$('.alert').detach();
			$(".noti").empty();
			$('.my-alert').detach();
			dialog_chggroup.off();
			dialog_chgclass.off();
			dialog_chgtype.off();
			dialog_doctorcode.off();
			dialog_deptcode.off();
			dialog_uom.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		// buttons :butt1,
	});

	/////////////////////////////////charge price list dialog///////////////////////////////////
	$( "#priceListDialog" ).dialog({
		autoOpen: false,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function(){
		
		},
		close: function( event, ui ){
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata_priceList');
		},
		buttons:
		[
		{
			text: "Generate PDF",click: function() {
				window.open('./chargemaster/showpdf?chggroup_from='+$('#chggroup_from').val()+'&chggroup_to='+$("#chggroup_to").val()+'&chgcode_from='+$("#chgcode_from").val()+'&chgcode_to='+$("#chgcode_to").val(),  '_blank'); 
			}
		},
		{
			text: "Generate Excel",click: function() {
				window.location='./chargemaster/showExcel?chggroup_from='+$('#chggroup_from').val()+'&chggroup_to='+$("#chggroup_to").val()+'&chgcode_from='+$("#chgcode_from").val()+'&chgcode_to='+$("#chgcode_to").val();
			}
		},{
			text: "Close",click: function() {
				$(this).dialog('close');
				emptyFormdata(errorField,'#formdata_priceList');
			}
		}],
	});

	$('#pdfgen_excel').click(function(){
		$( "#priceListDialog" ).dialog( "open" );
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////////////////pkg deal list dialog///////////////////////////////////
	$("#pkgDealDialog").dialog({
		autoOpen: false,
		// width: 3/6 * $(window).width(),
		modal: true,
		open: function( event, ui ){

			$("#formdata_pkgDeal input[name='pkgcodePkg']").val(selrowData("#jqGrid").chgcode);
			$("#formdata_pkgDeal input[name='effectdate']").val(selrowData("#jqGridPkg3").effdate);
		},
		close: function( event, ui ){
			parent_close_disabled(false);
			// emptyFormdata(errorField,'#formdata_pkgDeal');
		},
	});
			
	$('#pdfgenPkg').click(function(){
		window.open('./chargemaster/showpdfPkg?pkgcodePkg='+$('#pkgcodePkg').val()+'&effectdate='+$("#effectdate").val(), '_blank'); 
	});

	$('#excelPkg').click(function(){
		window.location='./chargemaster/showExcelPkg?pkgcodePkg='+$('#pkgcodePkg').val()+'&effectdate='+$("#effectdate").val();
	});

	$('#pdfgen_excelPkg').click(function(){
		$( "#pkgDealDialog" ).dialog( "open" );
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

	var urlParam={
		action:'maintable',
		url:'./chargemaster/table',
		field:'',
		fixPost:'true',
		table_name:['hisdb.chgmast AS cm', 'hisdb.chgclass AS cc', 'hisdb.chggroup AS cg', 'hisdb.chgtype AS ct'],
		table_id:'chgcode',
		join_type:['LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN'],
		join_onCol:['cm.chgclass', 'cm.chggroup', 'cm.chgtype'],
		join_onVal:['cc.classcode', 'cg.grpcode', 'ct.chgtype'],
		filterCol:['cm.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./chargemaster/form',
		fixPost:'true',
		field:'',
		idnoUse:'idno',
		oper:oper,
		table_name:'hisdb.chgmast',
		table_id:'chgcode',
		saveip:'true',
		checkduplicate:'true'
	};

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#showpkgcode').text("");//tukar kat depan tu
				$('#showpkgdesc').text("");
				refreshGrid("#jqGrid4",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#showpkgcode').text("");//tukar kat depan tu
			$('#showpkgdesc').text("");
			refreshGrid("#jqGrid4",null,"kosongkan");
		});
	}
	
	/////////////////////////////////// Charge Master Header //////////////////////////////////////////////////////////
	///////////////////////////////////////// jqgrid //////////////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{ label: 'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label: 'Compcode', name: 'compcode', hidden:true},
			{ label: 'Charge Code', name: 'chgcode', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Description', name: 'description', classes: 'wrap', width: 60, canSearch: true, checked:true},
			{ label: 'Inventory', name: 'invflag', classes: 'wrap', width: 28, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },
			{ label: 'Class', name: 'chgclass', classes: 'wrap', width: 20},
			{ label: 'Class Name', name: 'cc_description', classes: 'wrap', width: 30},
			{ label: 'Group', name: 'chggroup', classes: 'wrap', width: 20, canSearch: true},
			{ label: 'Description', name: 'cg_description', classes: 'wrap', width: 40},
			{ label: 'Charge Type', name: 'chgtype', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Description', name: 'ct_description', classes: 'wrap', width: 30},
			{ label: 'UOM', name: 'uom', width: 30, formatter: showdetail, unformat: un_showdetail, hidden:false},
			{ label: 'UOM Product', name: 'uom_product', width: 30, formatter: showdetail, unformat: un_showdetail, hidden:false},
			{ label: 'Generic Name', name: 'brandname', width: 60, canSearch: true},
			{ label: 'barcode', name: 'barcode', hidden:true},
			{ label: 'constype', name: 'constype', hidden:true},
			{ label: 'packqty', name: 'packqty', hidden:true},
			{ label: 'druggrcode', name: 'druggrcode', hidden:true},
			{ label: 'subgroup', name: 'subgroup', hidden:true},
			{ label: 'stockcode', name: 'stockcode', hidden:true},
			{ label: 'invgroup', name: 'invgroup', hidden:true},
			{ label: 'costcode', name: 'costcode', hidden:true},
			{ label: 'revcode', name: 'revcode', hidden:true},
			{ label: 'seqno', name: 'seqno', hidden:true},
			{ label: 'overwrite', name: 'overwrite', hidden:true},
			{ label: 'doctorstat', name: 'doctorstat', hidden:true},
			{ label: 'Upd User', name: 'upduser', width: 80,hidden:true}, 
			{ label: 'Upd Date', name: 'upddate', width: 90,hidden:true},
			{ label: 'Status', name:'recstatus', width:30, classes:'wrap', hidden:false,
			cellattr: function (rowid, cellvalue)
			{ return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"' : '' },},
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			urlParam2.filterVal[2]=selrowData("#jqGrid").chgcode;
			urlParam2.filterVal[3]=selrowData("#jqGrid").uom;
			refreshGrid("#jqGrid3",urlParam2);

			$("#jqGrid4_c,#jqGridPkg3_c,#click_row").hide();
			if(selrowData('#jqGrid').chgtype == 'pkg' || selrowData('#jqGrid').chgtype == 'PKG' ){
				refreshGrid("#jqGridPkg3",urlParam2);
				$("#jqGridPkg3_c").show();
				$("#jqGrid3_c").hide();
				// hideatdialogForm_jqGrid4(true);

			} else {
				refreshGrid("#jqGrid3",urlParam2);
				$("#jqGrid3_c").show();
				$("#jqGrid4_c,#jqGridPkg3_c,#click_row").hide();
			}

			$('#showpkgcode').text(selrowData("#jqGrid").chgcode);//tukar kat depan tu
			$('#showpkgdesc').text(selrowData("#jqGrid").description);

			$('#pkgcode').val(selrowData("#jqGrid").chgcode);
			$('#description').val(selrowData("#jqGrid").description);
			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			// if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			// }

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
		},
		beforeRequest: function(){
			refreshGrid("#jqGrid3",null,"kosongkan");
			refreshGrid("#jqGridPkg3",null,"kosongkan");
		}
	});

	////////////////////unformatter status////////////////////////////////////////
	function formatterstatus_tick2(cellvalue, option, rowObject) {
		if (cellvalue == '1') {
			return `<span class="fa fa-check"></span>`;
		}else{
			return '';
		}
	}

	function unformatstatus_tick2(cellvalue, option, rowObject) {
		if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
			return '1';
		}else{
			return '0';
		}
	}

	function formatter(cellvalue, options, rowObject){
		return parseInt(cellvalue) ? "Yes" : "No";
	}

	function unformat(cellvalue, options){
		//return parseInt(cellvalue) ? "Yes" : "No";

		if (cellvalue == 'Yes') {
			return "1";
		}
		else {
			return "0";
		}
	}
	/////////////////////////////populate data for dropdown search By////////////////////////////
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
		});
	}

	$('#Scol').on('change', scolChange);

	function scolChange() {
		if($('#Scol').val()=='chggroup'){
			$("#div_chgtype").hide();
			$("#div_chggroup").show();
		} else if($('#Scol').val() == 'chgtype'){
			$("#div_chggroup").hide();
			$("#div_chgtype").show();
		} else {
			$("#div_chgtype,#div_chggroup").hide();
		}
	}

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	// populateSelect('#jqGrid','#searchForm');
	searchClick_('#jqGrid','#searchForm',urlParam);

	function searchClick_(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				// if($(form+' [name=Scol] option:selected').val() == 'description'){
				// 	search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'brandname');
				// }else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				// }
			}, 500 );
			refreshGrid("#jqGrid3",null,"kosongkan");
		});

		// $(form+' [name=Scol]').on( "change", function() {
		// 	if($(form+' [name=Scol] option:selected').val() == 'description'){
		// 		search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'brandname');
		// 	}else{
		// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		// 	}
		// 	refreshGrid("#jqGrid3",null,"kosongkan");
		// });
	}

	$('#searchForm [name=Stext]').on( "keyup", function() {
		$("#ct_chgtype,#cg_chggroup").val($(this).val());
	});

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','ct_description', 'cc_description','cg_description', 'compcode', 'computerid', 'adddate', 'adduser','upduser','upddate']);

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

	function hideatdialogForm_jqGridPkg2(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGridPkg2_iledit,#jqGridPkg2_iladd,#jqGridPkg2_ilcancel,#jqGridPkg2_ilsave,#Pkg2saveHeaderLabel,#jqGridPagerPkg2Delete,#jqGridPagerPkg2EditAll,#Pkg2saveDetailLabel").hide();
			$("#jqGridPagerPkg2SaveAll,#jqGridPagerPkg2CancelAll").show();

		}else if(hide){
			$("#jqGridPkg2_iledit,#jqGridPkg2_iladd,#jqGridPkg2_ilcancel,#jqGridPkg2_ilsave,#Pkg2saveHeaderLabel,#jqGridPagerPkg2Delete,#jqGridPagerPkg2EditAll,#jqGridPagerPkg2SaveAll,#jqGridPagerPkg2CancelAll").hide();
			$("#Pkg2saveDetailLabel").show();
		}else{
			$("#jqGridPkg2_iladd,#jqGridPkg2_ilcancel,#jqGridPkg2_ilsave,#Pkg2saveHeaderLabel,#jqGridPagerPkg2Delete,#jqGridPagerPkg2EditAll").show();
			$("#Pkg2saveDetailLabel,#jqGridPagerPkg2SaveAll,#jqGridPkg2_iledit,#jqGridPagerPkg2CancelAll").hide();
		}
		
	}

	function hideatdialogForm_jqGrid3(hide,saveallrow){
		if(saveallrow == 'saveallrow'){

			$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").hide();
			$("#jqGridPager3SaveAll,#jqGridPager3CancelAll").show();
		}else if(hide){

			$("#jqGrid3_iledit,#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3SaveAll,#jqGridPager3CancelAll,#jqGridPager3Refresh").hide();
		}else{

			$("#jqGrid3_iladd,#jqGrid3_ilcancel,#jqGrid3_ilsave,#jqGridPager3Delete,#jqGridPager3EditAll,#jqGridPager3Refresh").show();
			$("#jqGridPager3SaveAll,#jqGrid3_iledit,#jqGridPager3CancelAll").hide();
		}
		
	}

	function hideatdialogForm_jqGridPkg3(hide,saveallrow){
		if(saveallrow == 'saveallrow'){

			$("#jqGridPkg3_iledit,#jqGridPkg3_iladd,#jqGridPkg3_ilcancel,#jqGridPkg3_ilsave,#jqGridPagerPkg3Delete,#jqGridPagerPkg3EditAll,#jqGridPagerPkg3Refresh").hide();
			$("#jqGridPagerPkg3SaveAll,#jqGridPagerPkg3CancelAll").show();
		}else if(hide){

			$("#jqGridPkg3_iledit,#jqGridPkg3_iladd,#jqGridPkg3_ilcancel,#jqGridPkg3_ilsave,#jqGridPagerPkg3Delete,#jqGridPagerPkg3EditAll,#jqGridPagerPkg3SaveAll,#jqGridPagerPkg3CancelAll,#jqGridPagerPkg3Refresh").hide();
		}else{
			$("#jqGridPkg3_iladd,#jqGridPkg3_ilcancel,#jqGridPkg3_ilsave,#jqGridPagerPkg3Delete,#jqGridPagerPkg3EditAll,#jqGridPagerPkg3Refresh").show();
			$("#jqGridPagerPkg3SaveAll,#jqGridPkg3_iledit,#jqGridPagerPkg3CancelAll").hide();
		}
		
	}

	function hideatdialogForm_jqGrid4(hide,saveallrow){
		if(saveallrow == 'saveallrow'){

			$("#jqGrid4_iledit,#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4Refresh,#jqGridPager4Header,#jqGridPager4Detail").hide();
			$("#jqGridPager4SaveAll,#jqGridPager4CancelAll").show();
		}else if(hide){

			$("#jqGrid4_iledit,#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4SaveAll,#jqGridPager4CancelAll,#jqGridPager4Refresh,#jqGridPager4Header").hide();
			$("#jqGridPager4Detail").show();
		}else{

			$("#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4Refresh,#jqGridPager4Header").show();
			$("#jqGridPager4SaveAll,#jqGrid4_iledit,#jqGridPager4CancelAll,#jqGridPager4Detail").hide();
		}
		
	}

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			// if($("#chgtype").val()=="PKG" || $("#chgtype").val()=="pkg"){
			// 	obj={recstatus:'DEACTIVE'};
			// 	saveParam.field.push("recstatus");
			// }else{
				obj={};
			// }
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {

		},'json').fail(function (data) {
			$(".noti").text(data.responseText);
			// alert(data.responseText);
		}).done(function (data) {
			unsaved = false;

			if($("#chgtype").val()=="PKG" || $("#chgtype").val()=="pkg"){
				hideatdialogForm_jqGridPkg2(false);
			}else{
				hideatdialogForm(false);
			}

			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				if($("#chgtype").val()=="PKG" || $("#chgtype").val()=="pkg"){
					$('#jqGridPkg2_iladd').click();
				}else{
					$('#jqGrid2_iladd').click();
				}
			}
			if(selfoper=='add'){
				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#idno').val(data.idno);
				$('#computerid').val(data.computerid);
				$('#lastcomputerid').val(data.lastcomputerid);
				
				urlParam2.filterVal[2]=$('#chgcode').val();
				urlParam2.filterVal[3]=$('#uom').val();
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
			disableForm('#formdata');
			refreshGrid("#jqGrid",urlParam);

		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	/////////////////////////////////Charge Price Detail/////////////////////////////////////////////////
	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.optax','cp.adduser','cp.adddate','cp.chgcode','cm.chgcode','cp.idno','cp.autopull','cp.addchg','cp.pkgstatus','cp.recstatus','cp.uom'],
		table_name:['hisdb.chgprice AS cp', 'hisdb.chgmast AS cm'],
		table_id:'lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['cp.chgcode'],
		join_onVal:['cm.chgcode'],
        join_filterCol : [['cm.uom on =']],
        join_filterVal : [['cp.uom']],
		filterCol:['cp.compcode','cp.unit','cp.chgcode','cp.uom'],
		filterVal:['session.compcode','session.unit','','']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
	var addmore_jqgrid3={more:false,state:true,edit:false}
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Inpatient Tax', name: 'iptax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:iptaxCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Outpatient Tax', name: 'optax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:optaxCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Price 1', name: 'amt1', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 2', name: 'amt2', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 3', name: 'amt3', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Cost Price', name: 'costprice', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
					dataInit: function (element) {
						$(element).attr('disabled','true');
						$(element).val($('#uom').val());
					}
			}},
			{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
					dataInit: function (element) {
						if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
						$(element).attr('disabled','true');
					}
			}},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true}
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
		pager: "#jqGridPager2",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
			fdl.set_array().reset();
			
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_deptcodedtl.check(errorField);
		}
	});

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Inpatient Tax':temp=$("input[name='iptax']");break;
			case 'Outpatient Tax':temp=$("input[name='optax']");break;
			case 'Charge Code':temp=$("input[name='chgcode']");break;
			case 'Department':temp=$("input[name='issdept']");break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'iptax':field=['taxcode','description'];table="hisdb.taxmast";case_='iptax';break;
			case 'optax': field = ['taxcode', 'description']; table = "hisdb.taxmast";case_='optax';break;
			case 'chgcode': field = ['chgcode', 'description']; table = "hisdb.chgmast";case_='chgcode';break;
			case 'issdept': field = ['deptcode', 'description']; table = "sysdb.department";case_='issdept';break;
			case 'uom_product': field = ['uomcode', 'description']; table = "material.uom";case_='uom';break;
			case 'uom': field = ['uomcode', 'description']; table = "material.uom";case_='uom';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('chargemaster',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		
		return cellvalue;
	}

	function iptaxCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function optaxCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function iptaxPkg2CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGridPkg2" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function optaxPkg2CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGridPkg2" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function iptax3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function optax3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid3" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function iptaxPkg3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGridPkg3" optid="'+opt.id+'" id="'+opt.id+'" name="iptax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function optaxPkg3CustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGridPkg3" optid="'+opt.id+'" id="'+opt.id+'" name="optax" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function chgcodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid4" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function issdeptCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid4" optid="'+opt.id+'" id="'+opt.id+'" name="issdept" type="text" class="form-control input-sm" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	//////////////////////////////////start grid pager for Charge Master Header//////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
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
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
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
			$("#uom").val(selrowData('#jqGrid').uom);
			refreshGrid("#jqGrid2",urlParam2);
			refreshGrid("#jqGridPkg2",urlParam2);
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		id: 'glyphicon-plus',
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////////////////////////////////////Charge Price Detail//////////////////////////////////////////
	//////////////////////////////////////myEditOptions for jqGrid2/////////////////////////////////////////

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_optax.on();
			dialog_iptax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amt1']","#jqGrid2 input[name='amt2']","#jqGrid2 input[name='amt3']","#jqGrid2 input[name='costprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert();
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: $('#chgcode').val(),
					uom: $('#uom').val(),
					// authorid:$('#authorid').val()
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
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGrid2').idno,

							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
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

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			}
			mycurrency2.formatOnBlur();
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
					'idno' : data.idno,
					'effdate' : $("#jqGrid2 input#"+ids[i]+"_effdate").val(),
					'amt1' : $("#jqGrid2 input#"+ids[i]+"_amt1").val(),
					'amt2' : $("#jqGrid2 input#"+ids[i]+"_amt2").val(),
					'amt3' : $("#jqGrid2 input#"+ids[i]+"_amt3").val(),
					'costprice' : $("#jqGrid2 input#"+ids[i]+"_costprice").val(),
					'iptax' : $("#jqGrid2 input#"+ids[i]+"_iptax").val(),
					'optax' : $("#jqGrid2 input#"+ids[i]+"_optax").val()
				}

				jqgrid2_data.push(obj);
			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val()
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2);
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

	/////////////////////////////////////////////////Package/////////////////////////////////////////////////
	////////////////////////////////////////////////jqGridPkg2///////////////////////////////////////////////
	$("#jqGridPkg2").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effdate', width: 70, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Inpatient Tax', name: 'iptax', width: 60, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:iptaxPkg2CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Outpatient Tax', name: 'optax', width: 60, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:optaxPkg2CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Price 1', name: 'amt1', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 2', name: 'amt2', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 3', name: 'amt3', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Cost Price', name: 'costprice', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
					dataInit: function (element) {
						$(element).attr('disabled','true');
						$(element).val($('#uom').val());
					}
			}},
			{ label: 'Auto Pull', name: 'autopull', width: 40, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"1:YES;0:NO"
				}
			},
			{ label: 'Charge If More', name: 'addchg', width: 40, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"1:YES;0:NO"
				}
			},
			{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
					dataInit: function (element) {
						if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
						$(element).attr('disabled','true');
					}
			}},
			{ label: 'Package Status', name: 'pkgstatus', hidden:true, width: 60},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true}
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
		pager: "#jqGridPagerPkg2",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGridPkg2_iladd').click();}
			else{
				$('#jqGridPkg2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
			fdl.set_array().reset();
			
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_deptcodedtl.check(errorField);
		}
	});

	//////////////////////////////////////////myEditOptionsPkg2/////////////////////////////////////////////

	var myEditOptionsPkg2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPagerPkg2EditAll,#Pkg2saveHeaderLabel,#jqGridPagerPkg2Delete").hide();

			dialog_pkg2iptax.on();
			dialog_pkg2optax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGridPkg2 input[name='amt1']","#jqGridPkg2 input[name='amt2']","#jqGridPkg2 input[name='amt3']","#jqGridPkg2 input[name='costprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridPkg2_ilsave').click();
			})
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGridPkg2',urlParam2,'add');
			$("#jqGridPagerPkg2EditAll,#jqGridPagerPkg2Delete").show();
		}, 
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGridPkg2',urlParam2,'add');
			$("#jqGridPagerPkg2Delete").show();
		},
		beforeSaveRow: function(options, rowid) {
			mycurrency2.formatOff()
			//if(errorField.length>0)return false;  

			let data = $('#jqGridPkg2').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: $('#chgcode').val(),
					uom: $('#uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGridPkg2").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGridPkg2(false);
		}
	};
	
	//////////////////////////////////////////pager jqGridPkg2/////////////////////////////////////////////
	
	$("#jqGridPkg2").inlineNav('#jqGridPagerPkg2',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptionsPkg2
		},
		editParams: myEditOptionsPkg2
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "jqGridPagerPkg2Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGridPkg2").jqGrid ('getGridParam', 'selrow');
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
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGridPkg2').idno,

							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGridPkg2",urlParam2);
							});
						}else{
							$("#jqGridPagerPkg2EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "jqGridPagerPkg2EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGridPkg2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGridPkg2").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			}
			mycurrency2.formatOnBlur();
			onall_editfunc();
			hideatdialogForm_jqGridPkg2(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "jqGridPagerPkg2SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGridPkg2").jqGrid('getDataIDs');

			var jqGridPkg2_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGridPkg2').jqGrid('getRowData',ids[i]);
				var obj = 
				{
					'idno' : data.idno,
					'effdate' : $("#jqGridPkg2 input#"+ids[i]+"_effdate").val(),
					'amt1' : $("#jqGridPkg2 input#"+ids[i]+"_amt1").val(),
					'amt2' : $("#jqGridPkg2 input#"+ids[i]+"_amt2").val(),
					'amt3' : $("#jqGridPkg2 input#"+ids[i]+"_amt3").val(),
					'costprice' : $("#jqGridPkg2 input#"+ids[i]+"_costprice").val(),
					'iptax' : $("#jqGridPkg2 input#"+ids[i]+"_iptax").val(),
					'optax' : $("#jqGridPkg2 input#"+ids[i]+"_optax").val(),
					'autopull' : $("#jqGridPkg2 select#"+ids[i]+"_autopull").val(),
					'addchg' : $("#jqGridPkg2 select#"+ids[i]+"_addchg").val()
				}

				jqGridPkg2_data.push(obj);
			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val()
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqGridPkg2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGridPkg2(false);
				refreshGrid("#jqGridPkg2",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "jqGridPagerPkg2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGridPkg2(false);
			refreshGrid("#jqGridPkg2",urlParam2);
		},	
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "Pkg2saveHeaderLabel",
		caption:"Header",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Header"
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg2",{
		id: "Pkg2saveDetailLabel",
		caption:"Detail",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Detail"
	});

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel,#Pkg2saveDetailLabel").click(function () {
		unsaved = false;
		// dialog_authorid.off();
		// dialog_deptcodehd.off();
		//radbuts.check();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		} else {
			// dialog_authorid.on();
			// dialog_deptcodehd.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel,#Pkg2saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		hideatdialogForm_jqGridPkg2(true);
		// dialog_authorid.on();
		// dialog_deptcodehd.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
		refreshGrid("#jqGridPkg2", urlParam2);
	});

	function onall_editfunc(jqgrid="none"){
		dialog_optax.on();
		dialog_iptax.on();
		dialog_dtliptax.on();
		dialog_dtloptax.on();
		dialog_pkg2iptax.on();
		dialog_pkg2optax.on();
		dialog_pkg3iptax.on();
		dialog_pkg3optax.on();
		dialog_dtlchgcode.on();
		dialog_issdept.on();

		mycurrency2.formatOnBlur();//make field to currency on leave cursor

		if(jqgrid == "jqGrid4"){
			$("#jqGrid4 input[name='quantity'],#jqGrid4 input[name='pkgprice1'],#jqGrid4 input[name='pkgprice2'],#jqGrid4 input[name='pkgprice3']").on('blur',jqgrid4_calc_totprice);
		}
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////Chg Price Detail//////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

	$("#jqGrid3").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Inpatient Tax', name: 'iptax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:iptax3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Outpatient Tax', name: 'optax', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:optax3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Price 1', name: 'amt1', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 2', name: 'amt2', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 3', name: 'amt3', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Cost Price', name: 'costprice', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},				
			{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
					dataInit: function (element) {
						$(element).attr('disabled','true');
						$(element).val(selrowData('#jqGrid').uom);
					}
			}},
			{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
					dataInit: function (element) {
						if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
						$(element).attr('disabled','true');
					}
			}},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},

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
		pager: "#jqGridPager3",
		loadComplete: function(){
			if(addmore_jqgrid3.more == true){$('#jqGrid3_iladd').click();}
			else{
				$('#jqGrid3').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid3.edit = addmore_jqgrid3.more = false; //reset
		},
		gridComplete: function(){

			fdl.set_array().reset();
			if(!hide_init){
				hide_init=1;
				hideatdialogForm_jqGrid3(false);
			}
		}
	});
	var hide_init=0;

	//////////////////////////////////////////myEditOptions2 for Charge Price/////////////////////////////////////////////
	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").hide();

			dialog_dtliptax.on();
			dialog_dtloptax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='amt1']","#jqGrid3 input[name='amt2']","#jqGrid3 input[name='amt3']","#jqGrid3 input[name='costprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid3_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid3.state==true)addmore_jqgrid3.more=true; //only addmore after save inline
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert(response.responseText);
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3Delete,#jqGridPager3Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: selrowData('#jqGrid').chgcode,//$('#chgcode').val(),
					uom: selrowData('#jqGrid').uom//$('#uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGrid3(false);
		}
	};

	//////////////////////////////////////////pager jqgrid3/////////////////////////////////////////////

	$("#jqGrid3").inlineNav('#jqGridPager3',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions2
		},
		editParams: myEditOptions2
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid3").jqGrid ('getGridParam', 'selrow');
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
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGrid3').idno,

							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGrid3",urlParam2);
							});
						}else{
							$("#jqGridPager3EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid3").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGrid3").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			}
			mycurrency2.formatOnBlur();
			onall_editfunc();
			hideatdialogForm_jqGrid3(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid3").jqGrid('getDataIDs');

			var jqgrid3_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid3').jqGrid('getRowData',ids[i]);
				var obj = 
				{
					'idno' : data.idno,
					'effdate' : $("#jqGrid3 input#"+ids[i]+"_effdate").val(),
					'amt1' : $("#jqGrid3 input#"+ids[i]+"_amt1").val(),
					'amt2' : $("#jqGrid3 input#"+ids[i]+"_amt2").val(),
					'amt3' : $("#jqGrid3 input#"+ids[i]+"_amt3").val(),
					'costprice' : $("#jqGrid3 input#"+ids[i]+"_costprice").val(),
					'iptax' : $("#jqGrid3 input#"+ids[i]+"_iptax").val(),
					'optax' : $("#jqGrid3 input#"+ids[i]+"_optax").val()
				}

				jqgrid3_data.push(obj);
			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val()
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid3_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGrid3(false);
				refreshGrid("#jqGrid3",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGrid3(false);
			refreshGrid("#jqGrid3",urlParam2);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		id: "jqGridPager3Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid3", urlParam2);
		},
	});

	/////////////////////////////////////////////////Package/////////////////////////////////////////////////
	////////////////////////////////////////////////jqGridPkg3///////////////////////////////////////////////

	$("#jqGridPkg3").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effdate', width: 60, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Inpatient Tax', name: 'iptax', width: 60, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:iptaxPkg3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Outpatient Tax', name: 'optax', width: 60, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:optaxPkg3CustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Price 1', name: 'amt1', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 2', name: 'amt2', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Price 3', name: 'amt3', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Cost Price', name: 'costprice', width: 50, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},				
			{ label: 'UOM', name: 'uom', width: 80, formatter: showdetail, editable:true,editoptions: {
					dataInit: function (element) {
						$(element).attr('disabled','true');
						$(element).val(selrowData('#jqGrid').uom);
					}
			}},
			{ label: 'AutoPull', name: 'autopull', width: 40, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"1:YES;0:NO"
				}
			},
			{ label: 'Charge If More', name: 'addchg', width: 40, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"1:YES;0:NO"
				}
			},
			{ label: 'Status', name: 'recstatus', width: 100, classes: 'wrap', editable:true,editoptions: {
					dataInit: function (element) {
						if($(element).attr('id').search("jqg") != -1)$(element).val('ACTIVE');
						$(element).attr('disabled','true');
					}
			}},
			{ label: 'Package Status', name: 'pkgstatus', hidden:true, width: 60},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
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
		pager: "#jqGridPagerPkg3",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGridPkg3_iladd').click();}
			else{
				$('#jqGridPkg3').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		onSelectRow:function(rowid, selected){
			urlParam4.filterVal[1] = selrowData("#jqGrid").chgcode;
			urlParam4.filterVal[2] = moment(selrowData("#jqGridPkg3").effdate, "DD/MM/YYYY").format("YYYY-MM-DD");

			var rowdata = selrowData("#jqGridPkg3");

			// $("#formdata4 [name='autopull'][value='"+rowdata.autopull+"']").prop('checked', true);
			// $("#formdata4 [name='addchg'][value='"+rowdata.addchg+"']").prop('checked', true);

			refreshGrid("#jqGrid4",urlParam4);
			$("#jqGrid4_c,#click_row").show();
			hideatdialogForm_jqGrid4(true);
		},
		gridComplete: function(){

			fdl.set_array().reset();
			if(!hide_init_pkg){
				hide_init_pkg=1;
				hideatdialogForm_jqGridPkg3(false);
			}
		},
		beforeRequest: function(){
			refreshGrid("#jqGrid4",null,"kosongkan");
		}
	});
	var hide_init_pkg=0;

	//////////////////////////////////////////myEditOptionsPkg3/////////////////////////////////////////////

	var myEditOptionsPkg3 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPagerPkg3EditAll,#jqGridPagerPkg3Delete,#jqGridPagerPkg3Refresh").hide();

			dialog_pkg3iptax.on();
			dialog_pkg3optax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGridPkg3 input[name='amt1']","#jqGridPkg3 input[name='amt2']","#jqGridPkg3 input[name='amt3']","#jqGridPkg3 input[name='costprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='costprice']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridPkg3_ilsave').click();
			})
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid3.state==true)addmore_jqgrid3.more=true; //only addmore after save inline
			refreshGrid('#jqGridPkg3',urlParam2,'add');
			$("#jqGridPagerPkg3EditAll,#jqGridPagerPkg3Delete,#jqGridPagerPkg3Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGridPkg3',urlParam2,'add');
			$("#jqGridPagerPkg3Delete,#jqGridPagerPkg3Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGridPkg3').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: selrowData('#jqGrid').chgcode,//$('#chgcode').val(),
					uom: selrowData('#jqGrid').uom,
					

					//$('#uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGridPkg3").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGridPkg3(false);
		}
	};

	//////////////////////////////////////////pager jqGridPkg3/////////////////////////////////////////////

	$("#jqGridPkg3").inlineNav('#jqGridPagerPkg3',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptionsPkg3
		},
		editParams: myEditOptionsPkg3
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg3",{
		id: "jqGridPagerPkg3Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGridPkg3").jqGrid ('getGridParam', 'selrow');
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
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGridPkg3').idno,

							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGridPkg3",urlParam2);
							});
						}else{
							$("#jqGridPagerPkg3EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg3",{
		id: "jqGridPagerPkg3EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGridPkg3").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGridPkg3").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amt1","#"+ids[i]+"_amt2","#"+ids[i]+"_amt3","#"+ids[i]+"_costprice"]);
			}
			mycurrency2.formatOnBlur();
			onall_editfunc();
			hideatdialogForm_jqGridPkg3(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg3",{
		id: "jqGridPagerPkg3SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGridPkg3").jqGrid('getDataIDs');

			var jqGridPkg3_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGridPkg3').jqGrid('getRowData',ids[i]);
				var obj = 
				{
					'idno' : data.idno,
					'effdate' : $("#jqGridPkg3 input#"+ids[i]+"_effdate").val(),
					'amt1' : $("#jqGridPkg3 input#"+ids[i]+"_amt1").val(),
					'amt2' : $("#jqGridPkg3 input#"+ids[i]+"_amt2").val(),
					'amt3' : $("#jqGridPkg3 input#"+ids[i]+"_amt3").val(),
					'costprice' : $("#jqGridPkg3 input#"+ids[i]+"_costprice").val(),
					'iptax' : $("#jqGridPkg3 input#"+ids[i]+"_iptax").val(),
					'optax' : $("#jqGridPkg3 input#"+ids[i]+"_optax").val(),
					'autopull' : $("#jqGridPkg3 select#"+ids[i]+"_autopull").val(),
					'addchg' : $("#jqGridPkg3 select#"+ids[i]+"_addchg").val()
				}

				jqGridPkg3_data.push(obj);

			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val()
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqGridPkg3_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGridPkg3(false);
				refreshGrid("#jqGridPkg3",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPagerPkg3",{
		id: "jqGridPagerPkg3CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGridPkg3(false);
			refreshGrid("#jqGridPkg3",urlParam2);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPagerPkg3", {
		id: "jqGridPagerPkg3Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGridPkg3", urlParam2);
		},
	});

	/////////////////////////////parameter for jqgrid4 url///////////////////////////////////////////////
	var urlParam4={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['pd.compcode','pd.chgcode','pd.quantity','pd.actprice1','pd.actprice2','pd.actprice3','pd.pkgprice1','pd.pkgprice2','pd.pkgprice3','pd.totprice1','pd.totprice2','pd.totprice3','pd.effectdate','pd.pkgcode','pd.idno','pd.recstatus','pd.uom', 'pd.issdept'],
		table_name:['hisdb.pkgdet AS pd'],
		table_id:'lineno_',
		filterCol:['pd.compcode','pd.pkgcode','pd.effectdate','pd.recstatus'],
		filterVal:['session.compcode','','','ACTIVE']
	};

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////Package Deal Maintenance Details//////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////jqgrid4////////////////////////////////////////////////////////

	$("#jqGrid4").jqGrid({
		datatype: "local",
		editurl: "./chargemasterDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Charge Code', name: 'chgcode', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:chgcodeCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Department', name: 'issdept', width: 60, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:issdeptCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'UOM', name: 'uom', width: 40, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'Quantity', name: 'quantity', width: 80, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Act Price', name: 'actprice1', width: 80, align: 'right', classes: 'wrap', editable:false
			},
			{ label: 'Package Price', name: 'pkgprice1', width: 80, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Total', name: 'totprice1', width: 80, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'Act Price 2', name: 'actprice2', width: 150, align: 'right', classes: 'wrap', editable:false, hidden:true
			},
			{ label: 'Price 2', name: 'pkgprice2', width: 150, align: 'right', classes: 'wrap', editable:true, hidden:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Tot Price 2', name: 'totprice2', width: 150, align: 'right', classes: 'wrap', editable:true, hidden:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'Act Price 3', name: 'actprice3', width: 150, align: 'right', classes: 'wrap', editable:false, hidden:true
			},
			{ label: 'Price 3', name: 'pkgprice3', width: 150, align: 'right', classes: 'wrap', editable:true, hidden:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Tot Price 3', name: 'totprice3', width: 150, align: 'right', classes: 'wrap', editable:true, hidden:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'pkgcode', name: 'pkgcode', hidden:true},
			{ label: 'Effective date', name: 'effectdate', hidden:true},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
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
		pager: "#jqGridPager4",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid4_iladd').click();}
			else{
				$('#jqGrid4').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
			jqgrid4_calc_grandtot();
		},
		gridComplete: function(){

			fdl.set_array().reset();
			if(!hide_init_jq4){
				hide_init_jq4=1;
				hideatdialogForm_jqGrid4(false);
			}

			calc_jq_height_onchange("jqGrid4",true);
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_deptcodedtl.check(errorField);
		}
	});
	var hide_init_jq4=0;
	
	////////////////////// set label jqGrid4 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid4");

	//////////////////////////////////////////myEditOptions3/////////////////////////////////////////////

	var myEditOptions3 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val(),
			"pkg_dtl": "pkg_dtl"
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager4EditAll,#jqGridPager4Delete,#jqGridPager4Refresh,#jqGridPager4Header").hide();

			dialog_dtlchgcode.on();
			dialog_issdept.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid4 input[name='quantity']","#jqGrid4 input[name='actprice1']","#jqGrid4 input[name='price']","#jqGrid4 input[name='totprice']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor


			$("#jqGrid4 input[name='quantity'],#jqGrid4 input[name='pkgprice1'],#jqGrid4 input[name='pkgprice2'],#jqGrid4 input[name='pkgprice3']").on('blur',jqgrid4_calc_totprice);

			$("input[name='pkgprice3']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid4_ilsave').click();
			})
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid3.state==true)addmore_jqgrid3.more=true; //only addmore after save inline
			refreshGrid('#jqGrid4',urlParam4,'add');
			$("#jqGridPager4EditAll,#jqGridPager4Delete,#jqGridPager4Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid4',urlParam4,'add');
			$("#jqGridPager4Delete,#jqGridPager4Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid4').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					pkgcode: selrowData("#jqGrid").chgcode,//$('#chgcode').val(),
					effectdate:selrowData("#jqGridPkg3").effdate,//$('#uom').val(),
					actprice1:selrowData('#jqGrid4').actprice1,
					actprice2:selrowData('#jqGrid4').actprice2,
					actprice3:selrowData('#jqGrid4').actprice3,


					// authorid:$('#authorid').val()
				});
			$("#jqGrid4").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGrid4(false);
		}
	};

	//////////////////////////////////////////pager jqgrid4/////////////////////////////////////////////

	$("#jqGrid4").inlineNav('#jqGridPager4',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions3
		},
		editParams: myEditOptions3
	}).jqGrid('navButtonAdd',"#jqGridPager4",{
		id: "jqGridPager4Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid4").jqGrid ('getGridParam', 'selrow');
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
								action: 'chargemasterDetail_save',
								idno: selrowData('#jqGrid4').idno,
								_token: $("#_token").val(),
								"pkg_dtl": "pkg_dtl"
							}
							$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGrid4",urlParam4);
							});
						}else{
							$("#jqGridPager4EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager4",{
		id: "jqGridPager4EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid4").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGrid4").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_quantity","#"+ids[i]+"_actprice1","#"+ids[i]+"_price","#"+ids[i]+"_totprice"]);
			}
			mycurrency2.formatOnBlur();
			onall_editfunc('jqGrid4');
			hideatdialogForm_jqGrid4(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager4",{
		id: "jqGridPager4SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid4").jqGrid('getDataIDs');

			var jqGrid4_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid4').jqGrid('getRowData',ids[i]);
				var obj = 
				{	
					'idno' : data.idno,
					'chgcode' : $("#jqGrid4 input#"+ids[i]+"_chgcode").val(),
					'issdept' : $("#jqGrid4 input#"+ids[i]+"_issdept").val(),
					'uom' : $("#jqGrid4 input#"+ids[i]+"_uom").val(),
					'quantity' : $("#jqGrid4 input#"+ids[i]+"_quantity").val(),
					'actprice1' : selrowData('#jqGrid4').actprice1,
					'pkgprice1' : $("#jqGrid4 input#"+ids[i]+"_pkgprice1").val(),
					'totprice1' : $("#jqGrid4 input#"+ids[i]+"_totprice1").val(),
					'actprice2' : selrowData('#jqGrid4').actprice2,
					'pkgprice2' : $("#jqGrid4 input#"+ids[i]+"_pkgprice2").val(),
					'totprice2' : $("#jqGrid4 input#"+ids[i]+"_totprice2").val(),
					'actprice3' : selrowData('#jqGrid4').actprice3,
					'pkgprice3' : $("#jqGrid4 input#"+ids[i]+"_pkgprice3").val(),
					'totprice3' : $("#jqGrid4 input#"+ids[i]+"_totprice3").val(),
				}

				jqGrid4_data.push(obj);
			}

			var param={
				action: 'chargemasterDetail_save',
				_token: $("#_token").val(),
				pkgcode: selrowData("#jqGrid").chgcode,//$('#chgcode').val(),
				effectdate:selrowData("#jqGridPkg3").effdate,//$('#uom').val(),
				"pkg_dtl": "pkg_dtl"
			}

			$.post( "./chargemasterDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqGrid4_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGrid4(false);
				refreshGrid("#jqGrid4",urlParam4);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager4",{
		id: "jqGridPager4CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGrid4(false);
			refreshGrid("#jqGrid4",urlParam4);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		id: "jqGridPager4Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid4", urlParam4);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		id: "jqGridPager4Header",
		caption: "Header", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Header",
		
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		id: "jqGridPager4Detail",
		caption: "Detail", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Detail",
		onClickButton: function () {
			console.log(selrowData("#jqGridPkg3"));
			var param={
				url: './chargemaster/form',
				oper: 'add_pkgmast',
			}
			var obj={
				_token : $('#_token').val(),
				idno: selrowData("#jqGridPkg3").idno,
				// autopull: $("#formdata4 input:radio[name='autopull']:checked").val(),
				// addchg: $("#formdata4 input:radio[name='addchg']:checked").val(),
			}
			
			$.post( param.url+"?"+$.param(param),obj, function( data ) {
			
			},'json').fail(function(data) {
				alert(data.responseText);
			}).success(function(data){
				if($('#jqGrid4').jqGrid('getGridParam', 'reccount') < 1){
					addmore_jqgrid2.state = true;
					$('#jqGrid4_iladd').click();
					$("#jqGridPager4_left").show();
				}
				hideatdialogForm_jqGrid4(false);
			});
		},
	});

	//////////////////////////////////////////jqGridPager4Detail////////////////////////////////////////////
	

	//////////////////////////////////////////jqGridPager4Header////////////////////////////////////////////
	$("#jqGridPager4Header").click(function () {
		emptyFormdata(errorField, '#formdata4');
		hideatdialogForm_jqGrid4(true);
		enableForm('#formdata4');
	});

	function jqgrid4_calc_totprice(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let quantity = parseFloat($("#"+id_optid+"_quantity").val());
		let pkgprice1 = parseFloat($("#"+id_optid+"_pkgprice1").val());
		let totprice1 = pkgprice1 * quantity;
		let pkgprice2 = parseFloat($("#"+id_optid+"_pkgprice2").val());
		let totprice2 = pkgprice2 * quantity;
		let pkgprice3 = parseFloat($("#"+id_optid+"_pkgprice3").val());
		let totprice3 = pkgprice3 * quantity;

		if(!isNaN(totprice1))$("#"+id_optid+"_totprice1").val(totprice1);
		if(!isNaN(totprice2))$("#"+id_optid+"_totprice2").val(totprice2);
		if(!isNaN(totprice3))$("#"+id_optid+"_totprice3").val(totprice3);

	}

	function jqgrid4_calc_grandtot(){
		var ids = $("#jqGrid4").jqGrid('getDataIDs');

		var jqGrid4_data = [];
		for (var i = 0; i < ids.length; i++) {

			var data = $('#jqGrid4').jqGrid('getRowData',ids[i]);

			var obj = 
			{
				'totprice1' : data.totprice1,
				'totprice2' : data.totprice2,
				'totprice3' : data.totprice3,
			}

			jqGrid4_data.push(obj);
		}

		var grdprice1=grdprice2=grdprice3=0;
		for (var i = 0; i < jqGrid4_data.length; i++) {
			grdprice1=parseFloat(grdprice1)+parseFloat(jqGrid4_data[i].totprice1);
			grdprice2=parseFloat(grdprice2)+parseFloat(jqGrid4_data[i].totprice2);
			grdprice3=parseFloat(grdprice3)+parseFloat(jqGrid4_data[i].totprice3);
		}
		
		$("#grandtot1").val(grdprice1);
		$("#grandtot2").val(grdprice2);
		$("#grandtot3").val(grdprice3);
		mycurrency.formatOn();

		var chgprice_amt1 = selrowData("#jqGridPkg3").amt1;

		$('span.error_pkgmast').html('');
		if(parseFloat(chgprice_amt1) != parseFloat(grdprice1)){
			$('span.error_pkgmast').html('Total Package Price is not equal with Charge Price Amount');
		}

	}

	/////////////////////////////dialog handler priceList////////////////////////////////////////
	var chggroup_from = new ordialog(
		'chggroup_from','hisdb.chggroup','#chggroup_from','errorField',
		{	
			colModel:[
				{label:'Charge Group',name:'grpcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Charge Group",
			open: function(){
				chggroup_from.urlParam.filterCol=['compcode','recstatus'];
				chggroup_from.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('chggroup_to',errorField)!==-1){
						errorField.splice($.inArray('chggroup_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	chggroup_from.makedialog(true);

	var chggroup_to = new ordialog(
		'chggroup_to','hisdb.chggroup','#chggroup_to',errorField,
		{	
			colModel:[
				{label:'Charge Group',name:'grpcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Charge Group",
			open: function(){
				chggroup_to.urlParam.filterCol=['compcode','recstatus'];
				chggroup_to.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('chggroup_to',errorField)!==-1){
						errorField.splice($.inArray('chggroup_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	chggroup_to.makedialog(true);

	var chgcode_from = new ordialog(
		'chgcode_from','hisdb.chgmast','#chgcode_from','errorField',
		{	
			colModel:[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Charge Code",
			open: function(){
				chgcode_from.urlParam.filterCol=['compcode','recstatus'];
				chgcode_from.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('chgcode_to',errorField)!==-1){
						errorField.splice($.inArray('chgcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	chgcode_from.makedialog(true);

	var chgcode_to = new ordialog(
		'chgcode_to','hisdb.chgmast','#chgcode_to',errorField,
		{	
			colModel:[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Charge Code",
			open: function(){
				chgcode_to.urlParam.filterCol=['compcode','recstatus'];
				chgcode_to.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('chgcode_to',errorField)!==-1){
						errorField.splice($.inArray('chgcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	chgcode_to.makedialog(true);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$('#btn_chggroup').on( "click", function() {
		$('#cg_chggroup ~ a').click();
	});
	var cg_chggroup = new ordialog(
		'cg_chggroup', 'hisdb.chggroup', '#cg_chggroup', 'errorField',
		{
			colModel: [
				{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + cg_chggroup.gridname).grpcode;
				$("#searchForm input[name='Stext']").val($('#cg_chggroup').val());

				urlParam.searchCol=["chggroup"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Group Code",
			open: function () {
				cg_chggroup.urlParam.filterCol=['compcode', 'recstatus'];
				cg_chggroup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	cg_chggroup.makedialog();
	cg_chggroup.on();

	$('#btn_chgtype').on( "click", function() {
		$('#ct_chgtype ~ a').click();
	});
	var ct_chgtype = new ordialog(
		'ct_chgtype', 'hisdb.chgtype', '#ct_chgtype', 'errorField',
		{
			colModel: [
				{ label: 'Charge Type', name: 'chgtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true,  or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + ct_chgtype.gridname).chgtype;
				$("#searchForm input[name='Stext']").val($('#ct_chgtype').val());

				urlParam.searchCol=["chgtype"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Charge Type",
			open: function () {
				ct_chgtype.urlParam.filterCol=['compcode', 'recstatus'];
				ct_chgtype.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	ct_chgtype.makedialog();
	ct_chgtype.on();

	var dialog_chgclass= new ordialog(
		'chgclass','hisdb.chgclass','#chgclass',errorField,
		{	colModel:[
				{label:'Class Code',name:'classcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data=selrowData('#'+dialog_chgclass.gridname);
				if(data.classcode == 'C'){
					$('#constype').data('validation','required')
					$('#constype').attr('disabled',false)
					$('#constype').val('A')
					$('#constype option[value=""]').hide()
					dialog_doctorcode.required = true;
					$('#constype').focus();
				}else{
					$('#constype').data('validation','')
					$('#constype').attr('disabled',true)
					$('#constype').val('')
					$('#constype option[value=""]').show()
					dialog_doctorcode.required = false;
					$('#chggroup').focus();
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#constype').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Class Code",
			open: function(){
				dialog_chgclass.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chgclass.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_chgclass.makedialog(true);
	$('#chgclass').blur(function(){
		let textval = $(dialog_chgclass.textfield).val();
		if(textval == 'C'){
			$('#constype').data('validation','required')
			$('#constype').attr('disabled',false)
			$('#constype').val('A')
			$('#constype option[value=""]').hide()
			dialog_doctorcode.required = true;
			$('#constype').focus();
			text_error1('#constype');
		}else{
			$('#constype').data('validation','')
			$('#constype').attr('disabled',true)
			$('#constype').val('')
			$('#constype option[value=""]').show()
			dialog_doctorcode.required = false;
			$('#chggroup').focus();
		}
	});
	function check_chgclass_on_open(){
		let textval = $(dialog_chgclass.textfield).val();
		if(textval == 'C'){
			$('#constype').data('validation','required')
			$('#constype').attr('disabled',false)
			$('#constype').val('A')
			$('#constype option[value=""]').hide()
			dialog_doctorcode.required = true;
		}else{
			$('#constype').data('validation','')
			$('#constype').attr('disabled',true)
			$('#constype').val('')
			$('#constype option[value=""]').show()
			dialog_doctorcode.required = false;
		}
	}

	var dialog_chggroup= new ordialog(
		'chggroup','hisdb.chggroup','#chggroup',errorField,
		{	colModel:[
				{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#chgtype').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#chgtype').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Group Code",
			open: function(){
				dialog_chggroup.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chggroup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_chggroup.makedialog(true);

	var dialog_chgtype= new ordialog(
		'chgtype','hisdb.chgtype','#chgtype',errorField,
		{	colModel:[
				{label:'Charge Type',name:'chgtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#invgroup').focus();

				let data=selrowData('#'+dialog_chgtype.gridname);

				if(data.chgtype == 'pkg' || data.chgtype == 'PKG' ){
					hideatdialogForm_jqGridPkg2(true)
					$("#jqGridPkg2_c").show();
					$("#jqGrid2_c").hide();
					if(oper=='edit')refreshGrid("#jqGridPkg2",urlParam2);
					$("#jqGridPkg2").jqGrid ('setGridWidth', Math.floor($("#jqGridPkg2_c")[0].offsetWidth-$("#jqGridPkg2_c")[0].offsetLeft));
				} else {
					hideatdialogForm(true);
					$("#jqGrid2_c").show();
					$("#jqGridPkg2_c").hide();
					if(oper=='edit')refreshGrid("#jqGridPkg2",urlParam2);
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				}
				
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#invgroup').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Charge Type",
			open: function(){
				dialog_chgtype.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_chgtype.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_chgtype.makedialog(true);

	$('#chgtype').blur(function(){
		let textval = $(dialog_chgtype.textfield).val();
		// if(textval == 'pkg' || textval == 'PKG'){
		// 	$('#recstatus').val('DEACTIVE');
		// 	$("#formdata [name='recstatus'][value='DEACTIVE']").prop('checked', true);
		// }else{
			$('#recstatus').val('ACTIVE');
			$("#formdata [name='recstatus'][value='ACTIVE']").prop('checked', true);
		// }
	});

	var dialog_doctorcode= new ordialog(
		'costcode','hisdb.doctor','#costcode',errorField,
		{	colModel:[
				{label:'Doctor Code',name:'doctorcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Doctor Name',name:'doctorname',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode', 'recstatus'],
				filterVal:['session.compcode', 'ACTIVE']
			},
			ondblClickRow: function () {
				$('#revcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#revcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Doctor Code",
			open: function(){
				dialog_doctorcode.urlParam.filterCol=['compcode', 'recstatus'];
				dialog_doctorcode.urlParam.filterVal=['session.compcode', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_doctorcode.makedialog(true);

	var dialog_deptcode= new ordialog(
		'revcode','sysdb.department','#revcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','chgdept', 'recstatus'],
				filterVal:['session.compcode','1', 'ACTIVE']
			},
			ondblClickRow: function () {
				$('#seqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#seqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Department Code",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode','chgdept', 'recstatus'];
				dialog_deptcode.urlParam.filterVal=['session.compcode','1', 'ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_deptcode.makedialog(true);

	var dialog_iptax = new ordialog(
		'iptax','hisdb.taxmast',"#jqGrid2 input[name='iptax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#optax').focus();
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
			title:"Select Tax Master",
			open: function(){
				dialog_iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_iptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid2 input[name='optax']").focus();
			}
		},'urlParam','radio','tab'
	);
			
	dialog_iptax.makedialog();

	var dialog_optax = new ordialog(
		'optax','hisdb.taxmast',"#jqGrid2 input[name='optax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#amt1').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#amt1').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_optax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_optax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid2 input[name='amt1']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_optax.makedialog();

	var dialog_dtliptax = new ordialog(
		'dtl_iptax','hisdb.taxmast',"#jqGrid3 input[name='iptax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#dtl_optax').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#dtl_optax').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_dtliptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_dtliptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid3 input[name='optax']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtliptax.makedialog();

	var dialog_dtloptax = new ordialog(
		'dtl_optax','hisdb.taxmast',"#jqGrid3 input[name='optax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#amt1').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#amt1').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_dtloptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_dtloptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGrid3 input[name='amt1']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtloptax.makedialog();

	var dialog_pkg2iptax = new ordialog(
		'pkg2_iptax','hisdb.taxmast',"#jqGridPkg2 input[name='iptax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#pkg2_optax').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#pkg2_optax').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_pkg2iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_pkg2iptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGridPkg2 input[name='optax']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_pkg2iptax.makedialog();

	var dialog_pkg2optax = new ordialog(
		'pkg2_optax','hisdb.taxmast',"#jqGridPkg2 input[name='optax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#amt1').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#amt1').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_pkg2optax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_pkg2optax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGridPkg2 input[name='amt1']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_pkg2optax.makedialog();

	var dialog_pkg3iptax = new ordialog(
		'pkg3_iptax','hisdb.taxmast',"#jqGridPkg3 input[name='iptax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
					},
			ondblClickRow:function(){
				$('#pkg3_optax').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#pkg3_optax').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_pkg3iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_pkg3iptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGridPkg3 input[name='optax']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_pkg3iptax.makedialog();

	var dialog_pkg3optax = new ordialog(
		'pkg3_optax','hisdb.taxmast',"#jqGridPkg3 input[name='optax']",errorField,
		{	colModel:[
				{label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true},
				{label:'Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','Output']
			},
			ondblClickRow:function(){
				$('#amt1').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#amt1').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Tax Master",
			open: function(){
				dialog_pkg3optax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_pkg3optax.urlParam.filterVal = ['ACTIVE', 'session.compcode','Output'];
			},
			close: function(){
				$("#jqGridPkg3 input[name='amt1']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_pkg3optax.makedialog();

	var dialog_dtlchgcode = new ordialog(
		'chgcode','hisdb.chgmast',"#jqGrid4 input[name='chgcode']",errorField,
		{	colModel:[
				{label:'Charge Code',name:'chgcode',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,or_search:true},
				{label:'UOM',name:'uom',width:50,classes:'pointer'},
				{label:'Dept',width:80,name:'deptcode', hidden:false},
				{label:'Qty On Hand',width:80,name:'qtyonhand', hidden:false},
				{label:'Real Amt',width:50,name:'amt1', hidden:false},
				{label:'chgprice_amt2',name:'chgprice_amt2', hidden:true},
				{label:'chgprice_amt3',name:'chgprice_amt3', hidden:true},
			],
			urlParam: {
				url:'./chargemaster/chgpricelatest',
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
			ondblClickRow:function(event){
				
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}
				
				let data=selrowData('#'+dialog_dtlchgcode.gridname);

				$("#jqGrid4 input#"+id_optid+"_uom").val(data.uom);
				$("#jqGrid4 input#"+id_optid+"_issdept").val(data.deptcode);
				dialog_issdept.check(errorField);

				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice1:data.amt1});
				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice2:data.chgprice_amt2});
				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice3:data.chgprice_amt3});

			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							// $('#lastuser').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Charge Code",
			open: function(){
				dialog_dtlchgcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_dtlchgcode.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			close: function(){
				$("#jqGrid4 input[name='issdept']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtlchgcode.makedialog();

	var dialog_issdept = new ordialog(
		'issdept','sysdb.department',"#jqGrid4 input[name='issdept']",errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus', 'chgdept'],
					filterVal:['session.compcode','ACTIVE', '1']
					},
			ondblClickRow:function(){
				$("#jqGrid4 input[name='quantity']").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$("#jqGrid4 input[name='quantity']").focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Department Code",
			open: function(){
				dialog_issdept.urlParam.filterCol=['recstatus', 'compcode', 'chgdept'],
				dialog_issdept.urlParam.filterVal=['ACTIVE', 'session.compcode', '1']
				}
		},'urlParam','radio','tab'
	);
	dialog_issdept.makedialog(true);

	var dialog_uom = new ordialog(
		'uom','material.uom','#uom',errorField,
		{	colModel:[
				{label:'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
				if(oper == 'add'){
					get_chg_productmaster($('#chgcode').val(),$(dialog_uom.textfield).val());
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#suppcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}		
		},
		{
			title:"Select UOM",
			min_search_length:1,
			open: function(){
				dialog_uom.urlParam.filterCol = ['recstatus','compcode'];
				dialog_uom.urlParam.filterVal = [ 'ACTIVE','session.compcode'];	
			}
		},'urlParam','radio','notab',false
	);
	dialog_uom.makedialog(true);

	//////////////////////////////////////////////////////////////////////////////////////////////////////


	$("#jqGrid4_c,#jqGridPkg3_c,#click_row").hide();

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	$("#jqGridPkg3_panel").on("show.bs.collapse", function(){
		$("#jqGridPkg3").jqGrid ('setGridWidth', Math.floor($("#jqGridPkg3_c")[0].offsetWidth-$("#jqGridPkg3_c")[0].offsetLeft-28));
	});

	$("#jqGrid4_panel").on("show.bs.collapse", function(){
		$("#jqGrid4").jqGrid ('setGridWidth', Math.floor($("#jqGrid4_c")[0].offsetWidth-$("#jqGrid4_c")[0].offsetLeft-28));
	});

	function get_chg_productmaster(itemcode,uom){
		var param={
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['uom','invflag','packqty','druggrcode','subgroup','stockcode','chgclass','chggroup','chgtype','invgroup'],
			table_name:'material.product',
			filterCol:['compcode','itemcode','uomcode'],
			filterVal:['session.compcode',itemcode,uom]
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data) && data.rows.length > 0){
				$('#druggrcode').val(data.rows[0].druggrcode);
				$('#packqty').val(data.rows[0].packqty);
				$('#stockcode').val(data.rows[0].stockcode);
				$('#subgroup').val(data.rows[0].subgroup);
				$('#chgclass').val(data.rows[0].chgclass);
				$('#chggroup').val(data.rows[0].chggroup);
				$('#chgtype').val(data.rows[0].chgtype);
				$('#invgroup').val(data.rows[0].invgroup);

				
				dialog_chggroup.check(errorField);
				dialog_chgclass.check(errorField);
				dialog_chgtype.check(errorField);
			}else{
				$('#druggrcode').val('');
				$('#packqty').val('');
				$('#stockcode').val('');
				$('#subgroup').val('');
				$('#chgclass').val('');
				$('#chggroup').val('');
				$('#chgtype').val('');
				$('#invgroup').val('');
			}
		});	
	}

});