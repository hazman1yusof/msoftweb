
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
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

					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					enableForm('#formdata');
					rdonly('#formdata');
					frozeOnEdit("#formdata");
					recstatusDisable("cm_recstatus");

					if(selrowData('#jqGrid').cm_chgtype == 'pkg' || selrowData('#jqGrid').cm_chgtype == 'PKG' ){
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

					if(selrowData('#jqGrid').cm_chgtype == 'pkg' || selrowData('#jqGrid').cm_chgtype == 'PKG' ){
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
				set_compid_from_storage("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
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
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['hisdb.chgmast AS cm', 'hisdb.chgclass AS cc', 'hisdb.chggroup AS cg', 'hisdb.chgtype AS ct'],
		table_id:'cm_chgcode',
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
		idnoUse:'cm_idno',
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
			{ label: 'idno', name: 'cm_idno', sorttype: 'number', hidden:true },
			{ label: 'Compcode', name: 'cm_compcode', hidden:true},
			{ label: 'Charge Code', name: 'cm_chgcode', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Description', name: 'cm_description', classes: 'wrap', width: 60, canSearch: true, checked:true},
			{ label: 'Class', name: 'cm_chgclass', classes: 'wrap', width: 20},
			{ label: 'Class Name', name: 'cc_description', classes: 'wrap', width: 30},
			{ label: 'Group', name: 'cm_chggroup', classes: 'wrap', width: 20, canSearch: true},
			{ label: 'Description', name: 'cg_description', classes: 'wrap', width: 40},
			{ label: 'Charge Type', name: 'cm_chgtype', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Description', name: 'ct_description', classes: 'wrap', width: 30},
			{ label: 'UOM', name: 'cm_uom', width: 30, formatter: showdetail, unformat: un_showdetail, hidden:false},
			{ label: 'Generic Name', name: 'cm_brandname', width: 60},
			{ label: 'cm_barcode', name: 'cm_barcode', hidden:true},
			{ label: 'cm_constype', name: 'cm_constype', hidden:true},
			{ label: 'cm_invflag', name: 'cm_invflag', hidden:true},
			{ label: 'cm_packqty', name: 'cm_packqty', hidden:true},
			{ label: 'cm_druggrcode', name: 'cm_druggrcode', hidden:true},
			{ label: 'cm_subgroup', name: 'cm_subgroup', hidden:true},
			{ label: 'cm_stockcode', name: 'cm_stockcode', hidden:true},
			{ label: 'cm_invgroup', name: 'cm_invgroup', hidden:true},
			{ label: 'cm_costcode', name: 'cm_costcode', hidden:true},
			{ label: 'cm_revcode', name: 'cm_revcode', hidden:true},
			{ label: 'cm_seqno', name: 'cm_seqno', hidden:true},
			{ label: 'cm_overwrite', name: 'cm_overwrite', hidden:true},
			{ label: 'cm_doctorstat', name: 'cm_doctorstat', hidden:true},
			{ label: 'Upd User', name: 'cm_upduser', width: 80,hidden:true}, 
			{ label: 'Upd Date', name: 'cm_upddate', width: 90,hidden:true},
			{ label: 'Status', name:'cm_recstatus', width:30, classes:'wrap', hidden:false,
			cellattr: function (rowid, cellvalue)
			{ return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"' : '' },},
			{ label: 'computerid', name: 'cm_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'cm_ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'cm_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'cm_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
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
			urlParam2.filterVal[1]=selrowData("#jqGrid").cm_chgcode;
			refreshGrid("#jqGrid3",urlParam2);

			$("#jqGrid4_c,#jqGridPkg3_c,#click_row").hide();
			if(selrowData('#jqGrid').cm_chgtype == 'pkg' || selrowData('#jqGrid').cm_chgtype == 'PKG' ){
				refreshGrid("#jqGridPkg3",urlParam2);
				$("#jqGridPkg3_c").show();
				$("#jqGrid3_c").hide();
			} else {
				refreshGrid("#jqGrid3",urlParam2);
				$("#jqGrid3_c").show();
				$("#jqGrid4_c,#jqGridPkg3_c,#click_row").hide();
			}

			$('#showpkgcode').text(selrowData("#jqGrid").cm_chgcode);//tukar kat depan tu
			$('#showpkgdesc').text(selrowData("#jqGrid").cm_description);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
		},
		beforeRequest: function(){
			refreshGrid("#jqGrid3",null,"kosongkan");
			refreshGrid("#jqGridPkg3",null,"kosongkan");
		}
	});

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
		if($('#Scol').val()=='cm_chggroup'){
			$("#div_chgtype").hide();
			$("#div_chggroup").show();
		} else if($('#Scol').val() == 'cm_chgtype'){
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
				if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
					search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
				}else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}
			}, 500 );
			refreshGrid("#jqGrid3",null,"kosongkan");
		});

		// $(form+' [name=Scol]').on( "change", function() {
		// 	if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
		// 		search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
		// 	}else{
		// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		// 	}
		// 	refreshGrid("#jqGrid3",null,"kosongkan");
		// });
	}

	$('#searchForm [name=Stext]').on( "keyup", function() {
		$("#chgtype,#chggroup").val($(this).val());
	});

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['cm_idno','ct_description', 'cc_description','cg_description', 'cm_compcode', 'cm_ipaddress', 'cm_computerid', 'cm_adddate', 'cm_adduser','cm_upduser','cm_upddate']);

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

			$("#jqGrid4_iledit,#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4Refresh").hide();
			$("#jqGridPager4SaveAll,#jqGridPager4CancelAll").show();
		}else if(hide){

			$("#jqGrid4_iledit,#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4SaveAll,#jqGridPager4CancelAll,#jqGridPager4Refresh").hide();
		}else{

			$("#jqGrid4_iladd,#jqGrid4_ilcancel,#jqGrid4_ilsave,#jqGridPager4Delete,#jqGridPager4EditAll,#jqGridPager4Refresh").show();
			$("#jqGridPager4SaveAll,#jqGrid4_iledit,#jqGridPager4CancelAll").hide();
		}
		
	}

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			if($("#cm_chgtype").val()=="PKG" || $("#cm_chgtype").val()=="pkg"){
				obj={cm_recstatus:'DEACTIVE'};
				saveParam.field.push("cm_recstatus");
			}else{
				obj={};
			}
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {

		},'json').fail(function (data) {
			$(".noti").text(data.responseText);
			// alert(data.responseText);
		}).done(function (data) {
			unsaved = false;

			if($("#cm_chgtype").val()=="PKG" || $("#cm_chgtype").val()=="pkg"){
				hideatdialogForm_jqGridPkg2(false);
			}else{
				hideatdialogForm(false);
			}

			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				if($("#cm_chgtype").val()=="PKG" || $("#cm_chgtype").val()=="pkg"){
					$('#jqGridPkg2_iladd').click();
				}else{
					$('#jqGrid2_iladd').click();
				}
			}
			if(selfoper=='add'){
				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#idno').val(data.idno);
				
				urlParam2.filterVal[1]=$('#cm_chgcode').val();
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
		filterCol:['cp.compcode','cp.chgcode'],
		filterVal:['session.compcode','']
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
						$(element).val($('#cm_uom').val());
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
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'iptax':field=['taxcode','description'];table="hisdb.taxmast";case_='iptax';break;
			case 'optax': field = ['taxcode', 'description']; table = "hisdb.taxmast";case_='optax';break;
			case 'chgcode': field = ['chgcode', 'description']; table = "hisdb.chgmast";case_='chgcode';break;
			case 'cm_uom': field = ['uomcode', 'description']; table = "material.uom";case_='cm_uom';break;
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
			let cm_idno = selrowData('#jqGrid').cm_idno;
			if(!cm_idno){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':cm_idno});
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
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit',['cm_uom']);
			$("#cm_uom").val(selrowData('#jqGrid').cm_uom);
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
					chgcode: $('#cm_chgcode').val(),
					uom: $('#cm_uom').val(),
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
						$(element).val($('#cm_uom').val());
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

			//if(errorField.length>0)return false;  

			let data = $('#jqGridPkg2').jqGrid ('getRowData', rowid);
			let editurl = "./chargemasterDetail/form?"+
				$.param({
					action: 'chargemasterDetail_save',
					oper: 'add',
					chgcode: $('#cm_chgcode').val(),
					uom: $('#cm_uom').val(),
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
						$(element).val(selrowData('#jqGrid').cm_uom);
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
					chgcode: selrowData('#jqGrid').cm_chgcode,//$('#cm_chgcode').val(),
					uom: selrowData('#jqGrid').cm_uom//$('#cm_uom').val(),
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
						$(element).val(selrowData('#jqGrid').cm_uom);
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
			urlParam4.filterVal[1] = selrowData("#jqGrid").cm_chgcode;
			urlParam4.filterVal[2] = moment(selrowData("#jqGridPkg3").effdate, "DD/MM/YYYY").format("YYYY-MM-DD");

			refreshGrid("#jqGrid4",urlParam4);
			$("#jqGrid4_c,#click_row").show();
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
					chgcode: selrowData('#jqGrid').cm_chgcode,//$('#cm_chgcode').val(),
					uom: selrowData('#jqGrid').cm_uom,
					

					//$('#cm_uom').val(),
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
		field:['pd.chgcode','pd.quantity','pd.actprice1','pd.actprice2','pd.actprice3','pd.pkgprice1','pd.pkgprice2','pd.pkgprice3','pd.totprice1','pd.totprice2','pd.totprice3','pd.effectdate','pd.pkgcode','pd.idno','pd.recstatus'],
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
			{ label: 'Quantity', name: 'quantity', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Act Price 1', name: 'actprice1', width: 150, align: 'right', classes: 'wrap', editable:false
			},
			{ label: 'Price 1', name: 'pkgprice1', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Tot Price 1', name: 'totprice1', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'Act Price 2', name: 'actprice2', width: 150, align: 'right', classes: 'wrap', editable:false,
			},
			{ label: 'Price 2', name: 'pkgprice2', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Tot Price 2', name: 'totprice2', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
			{ label: 'Act Price 3', name: 'actprice3', width: 150, align: 'right', classes: 'wrap', editable:false,
			},
			{ label: 'Price 3', name: 'pkgprice3', width: 150, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Tot Price 3', name: 'totprice3', width: 150, align: 'right', classes: 'wrap', editable:true,
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
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_deptcodedtl.check(errorField);
		}
	});
	var hide_init_jq4=0;

	//////////////////////////////////////////myEditOptions3/////////////////////////////////////////////

	var myEditOptions3 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val(),
			"pkg_dtl": "pkg_dtl"
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager4EditAll,#jqGridPager4Delete,#jqGridPager4Refresh").hide();

			dialog_dtlchgcode.on();

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
					pkgcode: selrowData("#jqGrid").cm_chgcode,//$('#cm_chgcode').val(),
					effectdate:selrowData("#jqGridPkg3").effdate,//$('#cm_uom').val(),
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
				pkgcode: selrowData("#jqGrid").cm_chgcode,//$('#cm_chgcode').val(),
				effectdate:selrowData("#jqGridPkg3").effdate,//$('#cm_uom').val(),
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

	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$('#btn_chggroup').on( "click", function() {
		$('#chggroup ~ a').click();
	});
	var chggroup = new ordialog(
		'chggroup', 'hisdb.chggroup', '#chggroup', 'errorField',
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
				let data = selrowData('#' + chggroup.gridname).grpcode;
				$("#searchForm input[name='Stext']").val($('#chggroup').val());

				urlParam.searchCol=["cm_chggroup"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Group Code",
			open: function () {
				chggroup.urlParam.filterCol=['compcode', 'recstatus'];
				chggroup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	chggroup.makedialog();
	chggroup.on();

	$('#btn_chgtype').on( "click", function() {
		$('#chgtype ~ a').click();
	});
	var chgtype = new ordialog(
		'chgtype', 'hisdb.chgtype', '#chgtype', 'errorField',
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
				let data = selrowData('#' + chgtype.gridname).chgtype;
				$("#searchForm input[name='Stext']").val($('#chgtype').val());

				urlParam.searchCol=["cm_chgtype"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Charge Type",
			open: function () {
				chgtype.urlParam.filterCol=['compcode', 'recstatus'];
				chgtype.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	chgtype.makedialog();
	chgtype.on();

	var dialog_chgclass= new ordialog(
		'cm_chgclass','hisdb.chgclass','#cm_chgclass',errorField,
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
					$('#cm_constype').data('validation','required')
					$('#cm_constype').attr('disabled',false)
					$('#cm_constype').val('A')
					$('#cm_constype option[value=""]').hide()
					dialog_doctorcode.required = true;
					$('#cm_constype').focus();
				}else{
					$('#cm_constype').data('validation','')
					$('#cm_constype').attr('disabled',true)
					$('#cm_constype').val('')
					$('#cm_constype option[value=""]').show()
					dialog_doctorcode.required = false;
					$('#cm_chggroup').focus();
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cm_constype').focus();
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
	$('#cm_chgclass').blur(function(){
		let textval = $(dialog_chgclass.textfield).val();
		if(textval == 'C'){
			$('#cm_constype').data('validation','required')
			$('#cm_constype').attr('disabled',false)
			$('#cm_constype').val('A')
			$('#cm_constype option[value=""]').hide()
			dialog_doctorcode.required = true;
			$('#cm_constype').focus();
			text_error1('#cm_constype');
		}else{
			$('#cm_constype').data('validation','')
			$('#cm_constype').attr('disabled',true)
			$('#cm_constype').val('')
			$('#cm_constype option[value=""]').show()
			dialog_doctorcode.required = false;
			$('#cm_chggroup').focus();
		}
	});
	function check_chgclass_on_open(){
		let textval = $(dialog_chgclass.textfield).val();
		if(textval == 'C'){
			$('#cm_constype').data('validation','required')
			$('#cm_constype').attr('disabled',false)
			$('#cm_constype').val('A')
			$('#cm_constype option[value=""]').hide()
			dialog_doctorcode.required = true;
		}else{
			$('#cm_constype').data('validation','')
			$('#cm_constype').attr('disabled',true)
			$('#cm_constype').val('')
			$('#cm_constype option[value=""]').show()
			dialog_doctorcode.required = false;
		}
	}

	var dialog_chggroup= new ordialog(
		'cm_chggroup','hisdb.chggroup','#cm_chggroup',errorField,
		{	colModel:[
				{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#cm_chgtype').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cm_chgtype').focus();
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
		'cm_chgtype','hisdb.chgtype','#cm_chgtype',errorField,
		{	colModel:[
				{label:'Charge Type',name:'chgtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#cm_invgroup').focus();

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
					$('#cm_invgroup').focus();
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

	$('#cm_chgtype').blur(function(){
		let textval = $(dialog_chgtype.textfield).val();
		if(textval == 'pkg' || textval == 'PKG'){
			$('#cm_recstatus').val('DEACTIVE');
			$("#formdata [name='cm_recstatus'][value='DEACTIVE']").prop('checked', true);
		}else{
			$('#cm_recstatus').val('ACTIVE');
			$("#formdata [name='cm_recstatus'][value='ACTIVE']").prop('checked', true);
		}
	});

	var dialog_doctorcode= new ordialog(
		'cm_costcode','hisdb.doctor','#cm_costcode',errorField,
		{	colModel:[
				{label:'Doctor Code',name:'doctorcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Doctor Name',name:'doctorname',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode', 'recstatus'],
				filterVal:['session.compcode', 'ACTIVE']
			},
			ondblClickRow: function () {
				$('#cm_revcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cm_revcode').focus();
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
		'cm_revcode','sysdb.department','#cm_revcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','chgdept', 'recstatus'],
				filterVal:['session.compcode','1', 'ACTIVE']
			},
			ondblClickRow: function () {
				$('#cm_seqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cm_seqno').focus();
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
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'chgprice_amt1',name:'chgprice_amt1', hidden:true},
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

				// $("#jqGrid4 [name='quantity']");
				console.log(data.chgprice_amt1);

				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice1:data.chgprice_amt1});
				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice2:data.chgprice_amt2});
				$("#jqGrid4").jqGrid('setRowData', id_optid ,{actprice3:data.chgprice_amt3});

				// $("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['rate']);

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
				$("#jqGrid4 input[name='quantity']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_dtlchgcode.makedialog();

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
				console.log(oper);
				if(oper == 'add'){
					get_chg_productmaster($('#cm_chgcode').val(),$(dialog_uom.textfield).val());
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
			field:['cm_uom','cm_invflag','cm_packqty','cm_druggrcode','cm_subgroup','cm_stockcode','cm_chgclass','cm_chggroup','cm_chgtype','cm_invgroup'],
			table_name:'material.product',
			filterCol:['compcode','itemcode','uomcode'],
			filterVal:['session.compcode',itemcode,uom]
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data) && data.rows.length > 0){
				$('#cm_druggrcode').val(data.rows[0].cm_druggrcode);
				$('#cm_packqty').val(data.rows[0].cm_packqty);
				$('#cm_stockcode').val(data.rows[0].cm_stockcode);
				$('#cm_subgroup').val(data.rows[0].cm_subgroup);
				$('#cm_chgclass').val(data.rows[0].cm_chgclass);
				$('#cm_chggroup').val(data.rows[0].cm_chggroup);
				$('#cm_chgtype').val(data.rows[0].cm_chgtype);
				$('#cm_invgroup').val(data.rows[0].cm_invgroup);

				
				dialog_chggroup.check(errorField);
				dialog_chgclass.check(errorField);
				dialog_chgtype.check(errorField);
			}else{
				$('#cm_druggrcode').val('');
				$('#cm_packqty').val('');
				$('#cm_stockcode').val('');
				$('#cm_subgroup').val('');
				$('#cm_chgclass').val('');
				$('#cm_chggroup').val('');
				$('#cm_chgtype').val('');
				$('#cm_invgroup').val('');
			}
		});	
	}

});