
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules: 'sanitize',
		language: {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				show_errors(errorField,'#formdata');
				return [{
					element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message: ' '
				}]
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#purreqhd_amtdisc', '#purreqhd_subamount','#purreqhd_totamount']);
	var sequence = new Sequences('PR','#purreqhd_purreqdt');
	sequence.set($("#deptcode").val()).get();
	var fdl = new faster_detail_load();
	var myattachment = new attachment_page("purchaserequest","#jqGrid","purreqhd_idno");
	var my_remark_button = new remark_button_class('#jqgrid');
	var myfail_msg = new fail_msg_func();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper = null;
	var unsaved = false;
	scrollto_topbtm();

	$("#dialogForm")
		.dialog({
			width: 9.5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				unsaved = false;
				errorField.length=0;
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				$('#dialogForm #purreqhd_reqdept,#dialogForm input:radio[name=purreqhd_prtype]').attr('disabled',false);
				switch (oper) {
					case state = 'add':
						my_remark_button.remark_btn_off();
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$("#purreqhd_reqdept").val($("#deptcode").val());
						dialog_reqdept.check(errorField);
						$("#purreqhd_prdept").val($("#deptcode").val());
						dialog_prdept.check(errorField);
						$('#purreqhd_purreqdt').val(moment().format('YYYY-MM-DD'));
						break;
					case state = 'edit':
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$('#dialogForm #purreqhd_reqdept,#dialogForm input:radio[name=purreqhd_prtype]').attr('disabled',true);
						break;
					case state = 'view':
						disableForm('#formdata');
						$("#pg_jqGridPager2 table").hide();
						break;
				}if (oper != 'add') {
					my_remark_button.remark_btn_init(selrowData("#jqGrid"));
					dialog_reqdept.check(errorField);
					dialog_prdept.check(errorField);
					dialog_suppcode.check(errorField);
					dialog_assetno.check(errorField);
				} if (oper != 'view') {
					dialog_reqdept.on();
					dialog_prdept.on();
					dialog_suppcode.on();
					dialog_assetno.on();
				} if(oper == 'edit'){
					dialog_reqdept.off();
				}
			},
			beforeClose: function (event, ui) {
				if (unsaved) {
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function (result) {
						if (result == true) {
							unsaved = false;
							delete_dd($('#purreqhd_idno').val());
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function (event, ui) {
				addmore_jqgrid2.state = false;//reset balik
			    addmore_jqgrid2.more = false;
			    //reset balik
			    parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_reqdept.off();
				dialog_prdept.off();
				dialog_suppcode.off();
				dialog_assetno.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
		});
	/////////////////////////////////////////////end dialog/////////////////////////////////////////////
	
	$('#formdata input:radio[name="purreqhd_prtype"]').click(function (){
		$("#assetno_div").hide();
		let prtype = $('#formdata input:radio[name=purreqhd_prtype]:checked').val();
		if(prtype == 'AssetMaintenance'){
			$("#assetno_div").show();
			$("#purreqhd_prdept").val($("#pcs_dept").val());
			dialog_prdept.check(errorField);
			$("#purreqhd_reqdept").val($("#deptcode").val());
			dialog_reqdept.check(errorField);
		}else if(prtype == 'Others'){
			$("#purreqhd_prdept").val($("#pcs_dept").val());
			dialog_prdept.check(errorField);
			$("#purreqhd_reqdept").val($("#deptcode").val());
			dialog_reqdept.check(errorField);
		}else if(prtype == 'Asset'){
			$("#purreqhd_prdept").val($("#deptcode").val());
			dialog_prdept.check(errorField);
			$("#purreqhd_reqdept").val($("#deptcode").val());
			dialog_reqdept.check(errorField);
		}else if(prtype == 'Stock'){
			$("#purreqhd_prdept").val($("#deptcode").val());
			dialog_prdept.check(errorField);
			$("#purreqhd_reqdept").val($("#deptcode").val());
			dialog_reqdept.check(errorField);
		}
	});
	
	//////////////////////////////////////parameter for jqgrid url//////////////////////////////////////

	var recstatus_filter = [['OPEN','PREPARED','PARTIAL']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','PREPARED','SUPPORT','INCOMPLETED','VERIFIED','APPROVED','CANCELLED','COMPLETED','PARTIAL','RECOMMENDED1','RECOMMENDED2']];
		filterCol_urlParam = ['purreqhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}else if($("#recstatus_use").val() == 'SUPPORT'){
		recstatus_filter = [['PREPARED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'VERIFIED'){
		recstatus_filter = [['SUPPORT']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'RECOMMENDED1'){
		recstatus_filter = [['VERIFIED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID','purreqhd.outamount'];
		filterVal_urlParam = ['session.compcode','session.username','>=.10000'];//sysparam IV, PRLIMIT
	}else if($("#recstatus_use").val() == 'RECOMMENDED2'){
		recstatus_filter = [['RECOMMENDED1']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID','purreqhd.outamount'];
		filterVal_urlParam = ['session.compcode','session.username','>=.50000'];//sysparam IV, PRLIMIT
	}else if($("#recstatus_use").val() == 'APPROVED'){
		recstatus_filter = [['VERIFIED','RECOMMENDED1','RECOMMENDED2']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'REOPEN'){
		recstatus_filter = [['CANCELLED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'CANCEL'){
		recstatus_filter = [['OPEN']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}


	var cbselect = new checkbox_selection("#jqGrid","Checkbox","purreqhd_idno","purreqhd_recstatus");

	var urlParam = {
		// action: 'get_table_default',
		// url:'util/get_table_default',
		action: 'maintable',
		url:'./purchaseRequest/table',
		scope: $('#recstatus_use').val(),
		field:'',
		table_name: ['material.purreqhd', 'material.supplier'],
		table_id: 'purreqhd_idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['supplier.SuppCode'],
		join_onVal: ['purreqhd.suppcode'],
		filterCol: filterCol_urlParam,
		filterVal: filterVal_urlParam,
		WhereInCol:['purreqhd.recstatus'],
		WhereInVal: recstatus_filter,
		fixPost: true,
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'purReq_header_save',
		url:'./purchaseRequest/form',
		field: '',
		oper: oper,
		table_name: 'material.purreqhd',
		table_id: 'purreqhd_recno',
		fixPost: true,
		//returnVal: true,
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
				$('#reqnodepan').text("");//tukar kat depan tu
				$('#reqdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#reqnodepan').text("");//tukar kat depan tu
			$('#reqdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Type', name: 'purreqhd_prtype', width: 10},
			{ label: 'Record No', name: 'purreqhd_recno', width: 10, canSearch: true, selected: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request Department', name: 'purreqhd_reqdept', width: 15, canSearch: true, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Purchase Department', name: 'purreqhd_prdept', width: 15, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Request No', name: 'purreqhd_purreqno', width: 10, canSearch: true, align:'right', formatter: padzero, unformat: unpadzero  },
			// { label: 'Authorise ID', name: 'queuepr_AuthorisedID', width: 10, canSearch: true , hidden: true},
			{ label: 'PO No', name: 'purreqhd_purordno', width: 10, formatter: padzero, unformat: unpadzero,hidden: true },
			{ label: 'Request Date', name: 'purreqhd_purreqdt', width: 15, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Supplier Code', name: 'purreqhd_suppcode', width: 30, canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Supplier Name', name: 'supplier_name', width: 30, canSearch: true, classes: 'wrap', hidden:true },
			{ label: 'Amount', name: 'purreqhd_totamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Remark', name: 'purreqhd_remarks', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Asset No.', name: 'purreqhd_assetno', hidden: true },
			{ label: 'Status', name: 'purreqhd_recstatus', width: 15 },
			{ label: 'PerDiscount', name: 'purreqhd_perdisc', width: 90, hidden: true },
			{ label: 'AmtDiscount', name: 'purreqhd_amtdisc', width: 90, hidden: true },
			{ label: 'Subamount', name: 'purreqhd_subamount', width: 90, hidden: true },
			{ label: 'authpersonid', name: 'purreqhd_authpersonid', width: 90, hidden: true },
			{ label: 'authdate', name: 'purreqhd_authdate', width: 40, hidden: true },
			{ label: 'reqpersonid', name: 'purreqhd_reqpersonid', width: 50, hidden: true },
			{ label: 'adduser', name: 'purreqhd_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'purreqhd_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'purreqhd_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'purreqhd_upddate', width: 90, hidden: true },
			{ label: 'reopenby', name: 'purreqhd_reopenby', width: 40, hidden: true},
			{ label: 'requestby', name: 'purreqhd_requestby', width: 90, hidden: true },
			{ label: 'requestdate', name: 'purreqhd_requestdate', width: 90, hidden: true },
			{ label: 'recommended1by', name: 'purreqhd_recommended1by', width: 90, hidden: true },
			{ label: 'recommended1date', name: 'purreqhd_recommended1date', width: 90, hidden: true },
			{ label: 'recommended2by', name: 'purreqhd_recommended2by', width: 90, hidden: true },
			{ label: 'recommended2date', name: 'purreqhd_recommended2date', width: 90, hidden: true },
			{ label: 'supportby', name: 'purreqhd_supportby', width: 90, hidden: true },
			{ label: 'supportdate', name: 'purreqhd_supportdate', width: 40, hidden: true},
			{ label: 'verifiedby', name: 'purreqhd_verifiedby', width: 90, hidden: true },
			{ label: 'verifieddate', name: 'purreqhd_verifieddate', width: 90, hidden: true },
			{ label: 'approvedby', name: 'purreqhd_approvedby', width: 90, hidden: true },
			{ label: 'approveddate', name: 'purreqhd_approveddate', width: 40, hidden: true},
			{ label: 'reopendate', name: 'purreqhd_reopendate', width: 40, hidden:true},
			{ label: 'support_remark', name: 'purreqhd_support_remark', width: 40, hidden:true},
			{ label: 'verified_remark', name: 'purreqhd_verified_remark', width: 40, hidden:true},
			{ label: 'approved_remark', name: 'purreqhd_approved_remark', width: 40, hidden:true},
			{ label: 'cancelled_remark', name: 'purreqhd_cancelled_remark', width: 40, hidden:true},
			{ label: 'recommended1_remark', name: 'purreqhd_recommended1_remark', width: 40, hidden:true},
			{ label: 'recommended2_remark', name: 'purreqhd_recommended2_remark', width: 40, hidden:true},
			{ label: 'prtype', name: 'purreqhd_prtype', width: 40, hidden:true},
			{ label: 'cancelby', name: 'purreqhd_cancelby', width: 40, hidden:true},
			{ label: 'canceldate', name: 'purreqhd_canceldate', width: 40, hidden:true},
			{ label: 'unit', name: 'purreqhd_unit', width: 40, hidden:true},
			{ label: 'idno', name: 'purreqhd_idno', width: 90, hidden: true,key:true },
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'purreqhd_idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
            $("#gridAttch_panel").collapse('hide');
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").purreqhd_recstatus;
			let scope = $("#recstatus_use").val();
			// if(scope == 'ALL' && stat == "CANCELLED"){
			// 	$('#but_reopen_jq').show();
			// }else{
			// 	$('#but_reopen_jq').hide();
			// }

			// $('#but_post_single_jq,#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			// if (stat == scope || stat == "CANCELLED") {
			// 	$('#but_reopen_jq').show();
			// } else {
			// 	if(scope == 'ALL'){
			// 		if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0 && stat=='OPEN'){
			// 			$('#but_cancel_jq,#but_post_single_jq').show();
			// 		}else if(stat=='OPEN'){
			// 			$('#but_post_jq').show();
			// 		}
			// 	}else{
			// 		if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0){
			// 			$('#but_cancel_jq,#but_post_single_jq').show();
			// 		}else{
			// 			$('#but_post_jq').show();
			// 		}
			// 	}
			// }
			urlParam2.filterVal[0] = selrowData("#jqGrid").purreqhd_recno;
			
			$('#reqnodepan').text(selrowData("#jqGrid").purreqhd_purreqno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));

			$("#pdfgen1").attr('href','./purchaseRequest/showpdf?recno='+selrowData("#jqGrid").purreqhd_recno);

			$("#pdfgen2").attr('href','./purchaseRequest/showpdf?recno='+selrowData("#jqGrid").purreqhd_recno);
			
			if(stat=='PREPARED' || stat=='SUPPORT' || stat=='INCOMPLETED' || stat=='VERIFIED' || stat=='APPROVED' || stat=='CANCELLED' || stat=='COMPLETED' || stat=='RECOMMENDED1' || stat=='RECOMMENDED2' || stat=='PARTIAL'){
				if($('#scope').val()=='VERIFIED' && stat=='SUPPORT'){
					$("#jqGridPager td[title='Edit Selected Row']").show();
				}else{
					$("#jqGridPager td[title='Edit Selected Row']").hide();
				}
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").purreqhd_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED' || stat=='SUPPORT' || stat=='PREPARED' || stat=='VERIFIED' || stat=='APPROVED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				if($('#scope').val()=='VERIFIED' && stat=='SUPPORT'){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				}else{
					$("#jqGridPager td[title='View Selected Row']").click();
				}
			}
		},
		gridComplete: function () {
			cbselect.show_hide_table();
			if ($("#jqGrid").data('lastselrow') == '-1' || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			$("#searchForm input[name=Stext]").focus();
			populate_form(selrowData("#jqGrid"));
			fdl.set_array().reset();

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
						let stat = selrowData("#jqGrid").purreqhd_recstatus;

			if(stat=='PREPARED' || stat=='SUPPORT' || stat=='INCOMPLETED' || stat=='VERIFIED' || stat=='APPROVED' || stat=='CANCELLED' || stat=='COMPLETED' || stat=='RECOMMENDED1' || stat=='RECOMMENDED2' || stat=='PARTIAL'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
			if($('#scope').val() != 'ALL'){
				$("#jqGridPager td[title='Edit Selected Row'],#jqGridPager td[title='Add New Row']").hide();
			}
			if($('#scope').val() == 'CANCEL'){
				$('#trandeptSearch').hide();

			}
		},
	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////


	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#jqGrid", urlParam, oper);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function (){
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');
			$('#purreqhd_purreqno').val(padzero($('#purreqhd_purreqno').val()));
			refreshGrid("#jqGrid2", urlParam2);
		
			if($('#formdata input:radio[name=purreqhd_prtype]:checked').val() == 'AssetMaintenance'){
				$("#assetno_div").show();
			}else{
				$("#assetno_div").hide();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function (){
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			$('#purreqhd_purreqno').val(padzero($('#purreqhd_purreqno').val()));
			refreshGrid("#jqGrid2", urlParam2);
			
			if($('#formdata input:radio[name=purreqhd_prtype]:checked').val() == 'AssetMaintenance'){
				$("#assetno_div").show();
			}else{
				$("#assetno_div").hide();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function (){
			$("#jqGrid").data('lastselrow','-1');
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid', '#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid', false, urlParam);
	addParamField('#jqGrid', false, saveParam, ['purreqhd_recno','purreqhd_purordno','purreqhd_adduser', 'purreqhd_adddate', 'purreqhd_idno', 'supplier_name','purreqhd_purreqno','purreqhd_upduser','purreqhd_upddate','purreqhd_deluser', 'purreqhd_recstatus','purreqhd_unit','Checkbox','purreqhd_recommended1by','purreqhd_recommended1date','purreqhd_recommended2by','purreqhd_recommended2date','purreqhd_support_remark','purreqhd_verified_remark','purreqhd_approved_remark','purreqhd_cancelled_remark','purreqhd_recommended1_remark','purreqhd_recommended2_remark']);

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

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////]
	$("#dialog_remarks_oper").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			$('#remarks_oper').val('');
		},
		close: function( event, ui ) {
			$("#but_cancel_jq").attr('disabled',false);
		},
		buttons : [{
			text: "Submit",click: function() {
				$("#but_cancel_jq").attr('disabled',true);
				if($('#remarks_oper').val() == ''){
					alert('Remarks for rejection is required!');
				}else{
					$(this).attr('disabled',true);
					var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
					var obj={};
					
					obj.idno_array = idno_array;
					obj.oper = 'reject';
					obj.remarks = $("#remarks_oper").val();
					obj._token = $('#_token').val();
					oper=null;
					
					$.post( './purchaseRequest/form', obj , function( data ) {
						refreshGrid('#jqGrid', urlParam);
						$(this).attr('disabled',true);
						cbselect.empty_sel_tbl();
					}).fail(function(data) {
						$('#error_infront').text(data.responseText);
						$(this).attr('disabled',true);
					}).success(function(data){
						$(this).attr('disabled',true);
					});
					$(this).dialog('close');
				}
			}
			},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	$("#but_cancel_jq").click(function(){
		$("#but_cancel_jq").attr('disabled',true);
		if($(this).data('oper') == 'cancel'){
			if (confirm("Are you sure to reject this purchase request?") == true) {
				$("#dialog_remarks_oper").dialog( "open" );
			}
		}
	});

	$("#but_cancel_from_reject_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		
		obj.idno_array = idno_array;
		obj.oper = $(self_).data('oper');//cancel_from_reject
		obj._token = $('#_token').val();
		oper=null;

		if(confirm("Are you sure you want to cancel this Document?") == true) {
			obj.idno_array = [selrowData('#jqGrid').purreqhd_idno];
		}else{
			return false
		}
		
		$.post( './purchaseRequest/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
			cbselect.empty_sel_tbl();
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
			$(self_).attr('disabled',false);
		}).success(function(data){
			$(self_).attr('disabled',false);
		});
	});

	$("#but_post_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		
		obj.idno_array = idno_array;
		obj.oper = $(self_).data('oper');
		obj._token = $('#_token').val();
		oper=null;

		// if($(this).data('oper') == 'reopen'){
		// 	if(confirm("Are you sure you want to reopen this Purchase Request?") == true) {
		// 		obj.idno_array = [selrowData('#jqGrid').purreqhd_idno];
		// 	}else{
		// 		return false
		// 	}
		// }

		var scope = $('#scope').val().toUpperCase();
		var need_remark_array = ["SUPPORT", "VERIFIED", "APPROVED", "RECOMMENDED1", "RECOMMENDED2"];

		var need_remark = need_remark_array.includes(scope);

		if(need_remark){
			$("#dialog_remarks_status").dialog( "open" );
		}else{
			$.post( './purchaseRequest/form', obj , function( data ) {
				refreshGrid('#jqGrid', urlParam);
				$(self_).attr('disabled',false);
				cbselect.empty_sel_tbl();
			}).fail(function(data) {
				$('#error_infront').text(data.responseText);
				$(self_).attr('disabled',false);
			}).success(function(data){
				$(self_).attr('disabled',false);
			});	
		}
		
	});

	$("#dialog_remarks_status").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			$('#remarks_status').val('');
		},
		close: function( event, ui ) {
			$("#but_post_jq").attr('disabled',false);
		},
		buttons : [{
			text: "Submit",click: function() {
					$("#but_post_jq").attr('disabled',true);
					$(this).attr('disabled',true);
					var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
					var obj={};
					
					obj.idno_array = idno_array;
					obj.oper = $("#but_post_jq").data('oper');
					obj.remarks = $("#remarks_status").val();
					obj._token = $('#_token').val();
					oper=null;
			
					$.post( './purchaseRequest/form', obj , function( data ) {
						refreshGrid('#jqGrid', urlParam);
						$(self_).attr('disabled',false);
						cbselect.empty_sel_tbl();
					}).fail(function(data) {
						$('#error_infront').text(data.responseText);
						$(self_).attr('disabled',false);
					}).success(function(data){
						$(self_).attr('disabled',false);
					});
					$(this).dialog('close');
				}
			},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			},'json')
		.fail(function (data) {
			// $('.noti').text(data.responseJSON.message);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			mycurrency.formatOn();
			dialog_reqdept.on();
			dialog_prdept.on();
			dialog_suppcode.on();
			dialog_assetno.on();
		}).done(function (data) {
			$("#saveDetailLabel").attr('disabled',false);
			hideatdialogForm(false);

			addmore_jqgrid2.state = true;
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}

			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#purreqhd_recno').val(data.recno);
				$('#purreqhd_purreqno').val(data.purreqno);
				$('#purreqhd_idno').val(data.idno);//just save idno for edit later
				$('#purreqhd_totamount').val(data.totalAmount);
				$('#purreqhd_adduser').val(data.adduser);
				$('#purreqhd_adddate').val(data.adddate);

				urlParam2.filterVal[0] = data.recno;
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
				$('#purreqhd_upduser').val(data.upduser);
				$('#purreqhd_upddate').val(data.upddate);
			}

			refreshGrid('#jqGrid2', urlParam2);
			disableForm('#formdata');
		})
	}

	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	// $("#dialogForm").on('click','#formdata a.input-group-addon',function(){
	// 	unsaved = true; //kalu dia change apa2 bagi prompt
	// });

	///////////////////utk dropdown search By/////////////////////////////////////////////////
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
	///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	// trandept();
	// function trandept(){
	// 	var param={
	// 		action:'get_value_default',
	// 		url: 'util/get_value_default',
	// 		field:['deptcode'],
	// 		table_name:'sysdb.department',
	// 		filterCol:['purdept'],
	// 		filterVal:['1']
	// 	}
	// 	$.get( param.url+"?"+$.param(param), function( data ) {
			
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data)){
	// 			$.each(data.rows, function(index, value ) {
	// 				if(value.deptcode.toUpperCase()== $("#deptcode").val().toUpperCase()){
	// 					$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}else{
	// 					$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}
	// 			});
	// 			searchChange();
	// 		}
	// 	});
	// }
////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if ($('#Scol').val() == 'purreqhd_purdate') {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	var supplierkatdepan = new ordialog(
		'supplierkatdepan', 'material.supplier', '#supplierkatdepan', 'errorField',
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
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["purreqhd_suppcode"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
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
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['compcode','recstatus'];
				dialog_suppcode.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	searchChange(true);
	function searchChange(once=false) {
		cbselect.empty_sel_tbl();
		var arrtemp = ['session.compcode', $('#Status option:selected').val(), $('#trandept option:selected').val()];

		var filter = arrtemp.reduce(function (a, b, c) {
			if (b.toUpperCase() == 'ALL') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purreqhd.compcode', 'purreqhd.recstatus', 'purreqhd.reqdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;

		if(once){
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if($('#searchForm [name=Stext]').val().trim() != ''){
				let searchCol = ['purreqhd_recno'];
				let searchVal = ['%'+$('#searchForm [name=Stext]').val().trim()+'%'];
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}

			if($("#recstatus_use").val() == 'APPROVED'){
				urlParam.filterCol[1] = null; 
				urlParam.filterVal[1] = null; 
				urlParam.WhereInCol = ['purreqhd.recstatus'];
				urlParam.WhereInVal = [['VERIFIED','RECOMMENDED1','RECOMMENDED2']];
			}
			once=false;
		}

		refreshGrid('#jqGrid', urlParam);
	}

	resizeColumnHeader = function () {
        var rowHight, resizeSpanHeight,
        // get the header row which contains
        headerRow = $(this).closest("div.ui-jqgrid-view")
            .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");

        // reset column height
        headerRow.find("span.ui-jqgrid-resize").each(function () {
            this.style.height = "";
        });

        // increase the height of the resizing span
        resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
        headerRow.find("span.ui-jqgrid-resize").each(function () {
            this.style.cssText = resizeSpanHeight;
        });

        // set position of the dive with the column header text to the middle
        rowHight = headerRow.height();
        headerRow.find("div.ui-jqgrid-sortable").each(function () {
            var ts = $(this);
            ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
        });
    },
    fixPositionsOfFrozenDivs = function () {
        var $rows;
        if (typeof this.grid.fbDiv !== "undefined") {
            $rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
            $(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i) {
                var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                if ($(this).hasClass("jqgrow")) {
                    $(this).height(rowHight);
                    rowHightFrozen = $(this).height();
                    if (rowHight !== rowHightFrozen) {
                        $(this).height(rowHight + (rowHight - rowHightFrozen));
                    }
                }
            });
            $(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
            $(this.grid.fbDiv).css($(this.grid.bDiv).position());
        }
        if (typeof this.grid.fhDiv !== "undefined") {
            $rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
            $(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i) {
                var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                $(this).height(rowHight);
                rowHightFrozen = $(this).height();
                if (rowHight !== rowHightFrozen) {
                    $(this).height(rowHight + (rowHight - rowHightFrozen));
                }
            });
            $(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
            $(this.grid.fhDiv).css($(this.grid.hDiv).position());
        }
    },
    fixGboxHeight = function () {
        var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
            pagerHeight = $(this.p.pager).outerHeight();

        $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
        gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
        pagerHeight = $(this.p.pager).outerHeight();
        $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
    }


	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url:'./purchaseRequestDetail/table',
		field: ['prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.qtybalance', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 't.rate'],
		table_name: ['material.purreqdt AS prdt', 'material.productmaster AS p', 'hisdb.taxmast AS t'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN', 'LEFT JOIN'],
		join_onCol: ['prdt.itemcode', 'prdt.taxcode'],
		join_onVal: ['p.itemcode', 't.taxcode'],
		filterCol: ['prdt.recno', 'prdt.compcode', 'prdt.recstatus'],
		filterVal: ['', 'session.compcode', '<>.DELETE']
	};
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./purchaseRequestDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false},
			{
				label: 'Price Code', name: 'pricecode', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },editoptions: { readonly: "readonly" },
				edittype: 'custom', editoptions:
				{
					custom_element: pricecodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable: false, editoptions: { readonly: "readonly" } },
			{
				label: 'UOM Code', name: 'uomcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'PO UOM', name: 'pouom', width: 100, classes: 'wrap', editable: true,
				editrules: { required: false, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Tax Code', name: 'taxcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: false, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Quantity Request', name: 'qtyrequest', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{
				label: 'Quantity Purchase',hidden: true, name: 'qtyapproved', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Quantity Balance', name: 'qtybalance', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Unit Price', name: 'unitprice', width: 80, classes: 'wrap', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules: { required: true }
			},
			{
				label: 'Percentage Discount', name: 'perdisc', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules: { required: true }, edittype: "text",
				editoptions: {
					maxlength: 12,
					dataInit: function (element) {
						element.style.textAlign = 'right';
						$(element).keypress(function (e) {
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								return false;
							}
						});
					}
				},
			},
			{
				label: 'Discount Per Unit', name: 'amtdisc', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules: { required: true }, edittype: "text",
				editoptions: {
					maxlength: 12,
					dataInit: function (element) {
						element.style.textAlign = 'right';
						$(element).keypress(function (e) {
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								return false;
							}
						});
					}
				},
			},
			{
				label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{
				label: 'Total Line Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Remarks', name: 'remarks_button', width: 80, formatter: formatterRemarks, unformat: unformatRemarks },
			{ label: 'Remarks', name: 'remarks', hidden: true },
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'wrap', hidden: false },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'unit', name: 'unit', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'prdept', name: 'prdept', width: 20, classes: 'wrap', hidden:true},
			{ label: 'purordno', name: 'purordno', width: 20, classes: 'wrap', hidden:true},
		],
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10000,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
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
			// console.log(addmore_jqgrid2);
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$(".noti").empty();
		},
		onSelectRow: function (rowid, selected) {
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			myfail_msg.clear_fail();
		},
		gridComplete: function(){
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array(function(){
				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			}).reset();
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			//calculate_quantity_outstanding('#jqGrid2');
			
			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;
		},
		afterShowForm: function (rowid) {
			$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_reqdept.check(errorField);
			dialog_prdept.check(errorField);
			dialog_suppcode.check(errorField);
			dialog_assetno.check(errorField);
		}

		}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	// $("#jqGrid2").jqGrid('bindKeys');
	// 	var updwnkey_fld;
	// 	function updwnkey_func(event){
	// 		var optid = event.currentTarget.id;
	// 		var fieldname = optid.substring(optid.search("_"));
	// 		updwnkey_fld = fieldname;
	// 	}

	// 	$("#jqGrid2").keydown(function(e) {
	//       switch (e.which) {
	//         case 40: // down
	//           var $grid = $(this);
	//           var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
	// 		  $("#"+selectedRowId+updwnkey_fld).focus();

	//           e.preventDefault();
	//           break;

	//         case 38: // up
	//           var $grid = $(this);
	//           var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
	// 		  $("#"+selectedRowId+updwnkey_fld).focus();

	//           e.preventDefault();
	//           break;

	//         default:
	//           return;
	//       }
	//     });


	$("#jqGrid2").jqGrid('setGroupHeaders', {
  	useColSpanStyle: false, 
	  groupHeaders:[
		{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
		{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
	  ]
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}


	function unformatRemarks(cellvalue, options, rowObject) {
		return null;
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

		if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}
		if($('#recstatus_use').val() == 'ALL'){
			if(rowObject.purreqhd_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'SUPPORT'){
			if(rowObject.purreqhd_recstatus == "PREPARED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'VERIFIED'){
			if(rowObject.purreqhd_recstatus == "SUPPORT"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'RECOMMENDED1'){
			if(rowObject.purreqhd_recstatus == "VERIFIED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'RECOMMENDED2'){
			if(rowObject.purreqhd_recstatus == "RECOMMENDED1"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'APPROVED'){
			if(rowObject.purreqhd_recstatus == "VERIFIED" || rowObject.purreqhd_recstatus == "RECOMMENDED1" || rowObject.purreqhd_recstatus == "RECOMMENDED2"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'CANCEL'){
			if(rowObject.purreqhd_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'REOPEN'){
			if(rowObject.purreqhd_recstatus == "CANCELLED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				let rowid = $('#remarks2').data('rowid');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks:newval});
				if($("#jqGridPager2SaveAll").css('display') == 'none'){
					$("#jqGrid2_ilsave").click();
				}
				$(this).dialog('close');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

	var butt2_rem = 
		[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];
	

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			let rowid = $('#remarks2').data('rowid');
			let grid = $('#remarks2').data('grid');
			$('#remarks2').val($(grid).jqGrid('getRowData', rowid).remarks);
			let exist = $("#jqGrid2 #"+rowid+"_pouom_convfactor_uom").length;
			if(grid == '#jqGrid3' || exist==0){ // lepas ni letak or not edit mode
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt1_rem);
			}
		},
		buttons : butt2_rem
	});
	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			myfail_msg.clear_fail();
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_pricecode.on();//start binding event on jqgrid2
			dialog_itemcode.on();
			dialog_uomcode.on();
			dialog_pouom.on();
			dialog_taxcode.on();

			if($('#dialogForm input:radio[name=purreqhd_prtype]:checked').val() == 'Stock'){
				$("#jqGrid2 #"+rowid+"_pricecode").val('IV');
			}else{
				$("#jqGrid2 #"+rowid+"_pricecode").val('MS');
			}

			dialog_pricecode.id_optid = rowid;
	        dialog_pricecode.check(errorField,rowid+"_pricecode","jqGrid2",null,
	        	function(self){
		        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
		        },function(data){
			    }
		    );

		    $("#jqGrid2 input[name='pricecode']").on('focus',function(){
				let focus = $(this).data('focus');
				if(focus == undefined){
					$(this).data('focus',1);
					$("#jqGrid2 input#"+rowid+"_itemcode").focus();
				}
			});

			// mycurrency2.array.length = 0;
			// mycurrency_np.array.length = 0;
			// Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='totamount']"]);
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyrequest']"]);
			
			// $("input[name='gstpercent']").val('0')//reset gst to 0
			// mycurrency2.formatOnBlur();//make field to currency on leave cursor
			// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtyrequest']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtyrequest']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='totamount']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);

        	// cari_gstpercent($("#jqGrid2 input[name='taxcode']").val());
		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#purreqhd_purreqno').val(resobj.purreqno);
			$('#purreqhd_recno').val(resobj.recno);
			$('#purreqhd_totamount').val(resobj.totalAmount);
			$('#purreqhd_subamount').val(resobj.totalAmount);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong

			urlParam2.filterVal[0] = resobj.recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$(".noti").empty();
		},
		errorfunc: function(rowid,response){
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
		beforeSaveRow: function (options, rowid) {
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./purchaseRequestDetail/form?"+
				$.param({
					action: 'purReq_detail_save',
					idno: $('#purreqhd_idno').val(),
					recno: $('#purreqhd_recno').val(),
					reqdept: $('#purreqhd_reqdept').val(),
					purreqno: $('#purreqhd_purreqno').val(),
					remarks:data.remarks,
					amount:data.amount,
					netunitprice:data.netunitprice,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$(".noti").empty();
	    },
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

	//////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'purReq_detail_save',
								recno: $('#purreqhd_recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post( "./purchaseRequestDetail/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#purreqhd_totamount').val(data);
								$('#purreqhd_subamount').val(data);
								refreshGrid("#jqGrid2", urlParam2);
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
			errorField.length=0;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			dialog_pricecode.renull_search();
		    for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount"]);

		        Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_qtyrequest"]);

		        // dialog_itemcode.id_optid = ids[i];
		        // dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,function(self){
		        // 	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
		        // });

		        dialog_itemcode.id_optid = ids[i];
		        // dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		        // 	function(self){
		        // 		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     },function(self){
				// 		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				//     }
			    // );

		        dialog_uomcode.id_optid = ids[i];
		        dialog_uomcode.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );

				dialog_taxcode.id_optid = ids[i];
		        dialog_taxcode.check(errorField,ids[i]+"_taxcode","jqGrid2",null,undefined,function(data,self){
		        	if(data.rows.length > 0){
						$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
		        	}
					fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
		        });

		        cari_gstpercent(ids[i]);
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
			mycurrency_np.formatOff();

			if(errorField.length>0){
				console.log(errorField)
				return false;
			}

		    for (var i = 0; i < ids.length; i++) {
				if(parseInt($('#'+ids[i]+"_qtyrequest").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				if(retval[0]!= true){
					alert(retval[1]);
					return false;
				}

				// cust_rules()

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'pricecode' : $("#jqGrid2 input#"+ids[i]+"_pricecode").val(),
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'pouom' : $("#jqGrid2 input#"+ids[i]+"_pouom").val(),
		    		'qtyrequest' : $('#'+ids[i]+"_qtyrequest").val(),
		    		'unitprice': $('#'+ids[i]+"_unitprice").val(),
		    		'taxcode' : $("#jqGrid2 input#"+ids[i]+"_taxcode").val(),
                    'perdisc' : $('#'+ids[i]+"_perdisc").val(),
                    'amtdisc' : $('#'+ids[i]+"_amtdisc").val(),
                    'tot_gst' : $('#'+ids[i]+"_tot_gst").val(),
                    'netunitprice' : data.netunitprice, //ni mungkin salah
                    'amount' : data.amount,
                    'totamount' : $("#"+ids[i]+"_totamount").val(),
                    'remarks' : data.remarks,
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'purReq_detail_save',
				_token: $("#_token").val(),
				recno: $('#purreqhd_recno').val(),
				action: 'purReq_detail_save',
				purreqno:$('#purreqhd_purreqno').val(),
				suppcode:$('#purreqhd_suppcode').val(),
				purdate:$('#purreqhd_purdate').val(),
				reqdept:$('#purreqhd_reqdept').val(),
				purreqdt:$('#purreqhd_purreqdt').val(),
    		}

    		$.post( "./purchaseRequestDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				alert(dialog,data.responseText);
			}).done(function(data){
				$('#purreqhd_totamount').val(data);
				$('#purreqhd_subamount').val(data);
				mycurrency.formatOn();
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
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveHeaderLabel",
		caption: "Header", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Header"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveDetailLabel",
		caption: "Detail", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'itemcode':field=['itemcode','description'];table="material.productmaster";case_='itemcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'pouom': field = ['uomcode', 'description']; table = "material.uom";case_='pouom';break;
			case 'pricecode':field=['pricecode','description'];table="material.pricesource";case_='pricecode';break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
			case 'purreqhd_suppcode':field=['suppcode','name'];table="material.supplier";case_='purreqhd_suppcode';break;
			case 'purreqhd_reqdept':field=['deptcode','description'];table="sysdb.department";case_='purreqhd_reqdept';break;
			case 'purreqhd_prdept':field=['deptcode','description'];table="sysdb.department";case_='purreqhd_prdept';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('purchaseRequest',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			case 'Item Code': temp = $("#jqGrid2 input[name='itemcode']"); break;
			case 'UOM Code': temp = $("#jqGrid2 input[name='uomcode']"); break;
			case 'PO UOM': 
				temp = $("#jqGrid2 input[name='pouom']"); 
				var text = $( temp ).parent().siblings( ".help-block" ).text();
				if(text == 'Invalid Code'){
					return [false,"Please enter valid "+name+" value"];
				}

				break;
			case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']"); break;
			case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']"); break;
			case 'Quantity Request': temp = $("#jqGrid2 input[name='qtyrequest']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];

	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val,opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden"><input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`><input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`></div>`);
	}
	function taxcodeCustomEdit(val,opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
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
		$("#saveDetailLabel").attr('disabled',true)
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = true;
		dialog_reqdept.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_assetno.off();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			mycurrency.formatOn();
			unsaved = true;
		} else {
			mycurrency.formatOn();
			dialog_reqdept.on();
			dialog_prdept.on();
			dialog_suppcode.on();
			dialog_assetno.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function (){
		emptyFormdata(errorField, '#formdata2');
		addmore_jqgrid2.state = false;
		hideatdialogForm(true);
		// dialog_reqdept.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		dialog_assetno.on();
		
		enableForm('#formdata');
		rdonly('#formdata');
		$('#dialogForm #purreqhd_reqdept,#dialogForm input:radio[name=purreqhd_prtype]').attr('disabled',true);
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac//////////////////////////////////
	function calculate_conversion_factor(event) {

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		if($("#jqGrid2 #"+id_optid+"_pricecode").val() == 'MS'){
			return true;
		}
		
		if($("#jqGrid2 #"+id_optid+"_pouom").val().trim() == ''){
			return true;
		}

		var id="#jqGrid2 #"+id_optid+"_qtyrequest";
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val());
		let qtyrequest = parseFloat($("#jqGrid2 #"+id_optid+"_qtyrequest").val());

		var balconv = convfactor_pouom*qtyrequest%convfactor_uom;

		if (balconv  == 0) {
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		} else {
			$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
			}
		}
		
	}

	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		remove_error("#jqGrid2 #"+id_optid+"_pouom");
		remove_error("#jqGrid2 #"+id_optid+"_qtyrequest");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_pouom");
		}, 500 );


		$(".noti").empty();

	}

	/////////////////////////////edit all//////////////////////////////////////////////////

	function onall_editfunc(){
		errorField.length=0;
		dialog_pricecode.off();//start binding event on jqgrid2
		$(dialog_pricecode.textfield).attr('disabled',true);
		dialog_itemcode.off();
		$(dialog_itemcode.textfield).attr('disabled',true);
		dialog_uomcode.off();
		$(dialog_uomcode.textfield).attr('disabled',true);
		dialog_pouom.on();
		dialog_taxcode.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt_all);

		$("#jqGrid2 input[name='qtyrequest']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt_all);

		$("#jqGrid2 input[name='qtyrequest']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);
	function calculate_line_totgst_and_totamt(event) {
		var name_from = $(event.currentTarget).attr('name');

		mycurrency2.formatOff();
		mycurrency_np.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
       
		let qtyrequest = parseFloat($("#"+id_optid+"_qtyrequest").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());
		if($("#jqGrid2 input#"+id_optid+"_taxcode").val() == ''){
			gstpercent = 0;
		}

		var totamtperUnit = ((unitprice*qtyrequest) - (amtdisc*qtyrequest));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?
		
		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});
		
		var id="#jqGrid2 #"+id_optid+"_qtyrequest";
		var fail_msg = "Quantity Request must be greater than 0";
		var name = "quantityrequest";

		if(name_from != 'taxcode'){
			if (qtyrequest > 0) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		}

		// var id="#jqGrid2 #"+id_optid+"_unitprice";
		// var fail_msg = "Please enter Unit Price";
		// var name = "unitprice";

		// if(name_from != 'taxcode'){
		// 	if (unitprice != 0) {
		// 		if($.inArray(id,errorField)!==-1){
		// 			errorField.splice($.inArray(id,errorField), 1);
		// 		}
		// 		$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
		// 		$( id ).removeClass( "error" ).addClass( "valid" );
		// 		$('.noti').find("li[data-errorid='"+name+"']").detach();
		// 	} else {
		// 		$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
		// 		$( id ).removeClass( "valid" ).addClass( "error" );
		// 		if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
		// 		if($.inArray(id,errorField)===-1){
		// 			errorField.push( id );
		// 		}
		// 	}
		// }

		// mycurrency2.formatOn();//change format to currency on each calculation
		// mycurrency_np.formatOn();

		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	}

	function calculate_line_totgst_and_totamt_all(event) {
		var name_from = $(event.currentTarget).attr('name');

		mycurrency2.formatOff();
		mycurrency_np.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
       
		let qtyrequest = parseFloat($("#"+id_optid+"_qtyrequest").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());
		if($("#jqGrid2 input#"+id_optid+"_taxcode").val() == ''){
			gstpercent = 0;
		}

		var totamtperUnit = ((unitprice*qtyrequest) - (amtdisc*qtyrequest));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?
		
		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});

		///new
		var all_totamt=0;
		$.each($("#jqGrid2 input[name=totamount]"), function (index, value) {
			all_totamt = parseFloat(all_totamt) + parseFloat($(this).val());
		});
		$('#purreqhd_subamount, #purreqhd_totamount').val(all_totamt);
		
		var id="#jqGrid2 #"+id_optid+"_qtyrequest";
		var fail_msg = "Quantity Request must be greater than 0";
		var name = "quantityrequest";

		if(name_from != 'taxcode'){
			if (qtyrequest > 0) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		}

		var id="#jqGrid2 #"+id_optid+"_unitprice";
		var fail_msg = "Please enter Unit Price";
		var name = "unitprice";

		if(name_from != 'taxcode'){
			if (unitprice != 0) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		}

		event.data.currency.formatOn();//change format to currency on each calculation
		mycurrency_np.formatOn();
		mycurrency2.formatOn();
		mycurrency.formatOn();

		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	}


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

		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){//ini baru
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});

			setjqgridHeight(data,'jqGrid3');
			//calc_jq_height_onchange("jqGrid3");
		},
	
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();

			//calculate_quantity_outstanding('#jqGrid3');
		},
	}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	$("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");


	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#purreqhd_reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#purreqhd_prdept').focus();
				let data=selrowData('#'+dialog_reqdept.gridname);
				sequence.set(data['deptcode']).get();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purreqhd_prdept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Request Department",
			open: function(){
				dialog_reqdept.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_reqdept.urlParam.filterVal=['ACTIVE', 'session.compcode',];
			}
		},'urlParam','radio','tab'
	);
	dialog_reqdept.makedialog();

	var dialog_prdept = new ordialog(
		'prdept','sysdb.department','#purreqhd_prdept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true, checked:true, or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['purdept', 'recstatus', 'compcode'],
				filterVal:['1', 'ACTIVE','session.compcode']
			},
			ondblClickRow: function () {
				$('#purreqhd_suppcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purreqhd_suppcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Purchase Department",
			open: function(){
				dialog_prdept.urlParam.filterCol=['purdept', 'recstatus', 'compcode'];
				dialog_prdept.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_prdept.makedialog(false);
	
	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#purreqhd_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#purreqhd_perdisc').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purreqhd_perdisc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_suppcode.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab',true
	);
	dialog_suppcode.makedialog();
	
	var dialog_assetno = new ordialog(
		'assetno', 'finance.faregister', '#purreqhd_assetno', errorField,
		{
			colModel: [
				{ label: 'Asset No', name: 'assetno', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
				{ label: 'Asset Type', name: 'assettype', width: 200, classes: 'pointer' },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				// $('#purreqhd_prdept').focus();
				let data = selrowData('#'+dialog_assetno.gridname);
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#purreqhd_prdept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Asset No.",
			open: function(){
				dialog_assetno.urlParam.filterCol = ['compcode','recstatus'];
				dialog_assetno.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		}, 'urlParam','radio','tab'
	);
	dialog_assetno.makedialog();
	
	var dialog_pricecode = new ordialog(
		'pricecode',['material.pricesource'],"#jqGrid2 input[name='pricecode']",errorField,
		{	colModel:
			[
				{label:'Price code',name:'pricecode',width:200,classes:'pointer',canSearch:true,or_search:true},
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

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				}
			}
		},{
			title:"Select Price Code For Item",
			open: function(){
				let prtype = $('#dialogForm input:radio[name=purreqhd_prtype]:checked').val();
				if(prtype == 'Stock'){
					dialog_pricecode.urlParam.filterCol=['compcode','recstatus'];
					dialog_pricecode.urlParam.filterVal=['session.compcode','ACTIVE'];
					dialog_pricecode.urlParam.WhereInCol=['pricecode'];
					dialog_pricecode.urlParam.WhereInVal=[['IV','BO']];
				}else{
					dialog_pricecode.urlParam.filterCol=['compcode','recstatus'];
					dialog_pricecode.urlParam.filterVal=['session.compcode','ACTIVE'];
					dialog_pricecode.urlParam.whereNotInCol=['pricecode'];
					dialog_pricecode.urlParam.whereNotInVal=[['IV','BO']];
				}
			},
			close: function(){
			}
		},'urlParam','radio','tab'
	);
	dialog_pricecode.makedialog(false);


	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'Quantity On Hand',name:'p_qtyonhand',width:100,classes:'pointer',},
				{label: 'UOM Code',name:'p_uomcode',width:100,classes:'pointer'},
				{label: 'Tax Code', name: 'p_TaxCode', width: 100, classes: 'pointer' },
				{label: 'Group Code',name:'p_groupcode',width:100,classes:'pointer'},
				{label: 'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
				{label: 'rate', name: 't_rate', width: 100, classes: 'pointer',hidden:true },
				{label: 'Unit', name:'p_unit',hidden:true },
				
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

				let data=selrowData('#'+dialog_itemcode.gridname);

				if(data.hasOwnProperty('p_itemcode')){
					$("#jqGrid2 #"+id_optid+"_itemcode").val(data['p_itemcode']);
				}

				$("#jqGrid2 #"+id_optid+"_uomcode").val(data['p_uomcode']);
				$("#jqGrid2 #"+id_optid+"_pouom").val(data['p_uomcode']);
				if(data['p_TaxCode'] == ''){
					$("#jqGrid2 #"+id_optid+"_taxcode").val('EP');
				}else{
					$("#jqGrid2 #"+id_optid+"_taxcode").val(data['p_TaxCode']);
				}
				$("#jqGrid2 #"+id_optid+"_rate").val(data['t_rate']);
				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['t_rate']);

				var rowid = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{description:data['p_description']});

				dialog_uomcode.id_optid = id_optid;
		        dialog_uomcode.check(errorField,id_optid+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );
			    dialog_pouom.id_optid = id_optid;
		        dialog_pouom.check(errorField,id_optid+"_pouom","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );
				dialog_taxcode.id_optid = id_optid;
		        dialog_taxcode.check(errorField,id_optid+"_taxcode","jqGrid2",null,undefined,function(data,self){
		        	if(data.rows.length > 0){
						$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
		        	}
		        });


			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			},
			loadComplete:function(data){
			}
		},{
			title:"Select Item For Purchase Request",
			open:function(obj_){
				let prtype = $('#dialogForm input:radio[name=purreqhd_prtype]:checked').val();
				
				if(prtype == 'Stock'){
					dialog_itemcode.urlParam.table_name = ['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u']
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode', 's.unit'];
					dialog_itemcode.urlParam.filterVal = ['on.p.compcode', moment($('#purreqhd_purreqdt').val()).year(), $('#purreqhd_reqdept').val(),'session.unit'];
					dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN','LEFT JOIN'];
					dialog_itemcode.urlParam.join_onCol = ['s.itemcode','p.taxcode','u.uomcode'];
					dialog_itemcode.urlParam.join_onVal = ['p.itemcode','t.taxcode','s.uomcode'];
					dialog_itemcode.urlParam.join_filterCol = [['p.uomcode on =','p.compcode =','p.recstatus =','p.unit =']];
					dialog_itemcode.urlParam.join_filterVal = [['s.uomcode','session.compcode','ACTIVE','session.unit']];
					dialog_itemcode.urlParam.WhereInCol=['p.groupcode'];
					dialog_itemcode.urlParam.WhereInVal=[['STOCK','CONSIGNMENT']];
				}else if(prtype == 'Asset'){
					dialog_itemcode.urlParam.table_name = ['material.product AS p','hisdb.taxmast AS t','material.uom AS u'];
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['p.compcode','p.recstatus','p.unit'];
					dialog_itemcode.urlParam.filterVal = ['session.compcode','ACTIVE','ALL'];
					dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN'];
					dialog_itemcode.urlParam.join_onCol = ['t.taxcode','u.uomcode'];
					dialog_itemcode.urlParam.join_onVal = ['p.taxcode','p.uomcode'];
					dialog_itemcode.urlParam.join_filterCol = [];
					dialog_itemcode.urlParam.join_filterVal = [];
					dialog_itemcode.urlParam.WhereInCol=['p.groupcode'];
					dialog_itemcode.urlParam.WhereInVal=[['ASSET']];
				}else{
					dialog_itemcode.urlParam.table_name = ['material.product AS p','hisdb.taxmast AS t','material.uom AS u'];
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['p.compcode','p.recstatus','p.unit'];
					dialog_itemcode.urlParam.filterVal = ['session.compcode','ACTIVE','ALL'];
					dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN'];
					dialog_itemcode.urlParam.join_onCol = ['t.taxcode','u.uomcode'];
					dialog_itemcode.urlParam.join_onVal = ['p.taxcode','p.uomcode'];
					dialog_itemcode.urlParam.join_filterCol = [];
					dialog_itemcode.urlParam.join_filterVal = [];
					dialog_itemcode.urlParam.WhereInCol=['p.groupcode'];
					dialog_itemcode.urlParam.WhereInVal=[['OTHERS']];
				}
			},
			close: function(obj_){
				$("#jqGrid2 #"+obj_.id_optid+"_qtyrequest").focus().select();
			}
		},'urlParam','radio','tab',true//urlParam means check() using urlParam not check_input
	);
	dialog_itemcode.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom',['material.uom AS u','material.stockloc AS s'],"#jqGrid2 input[name='uomcode']",'errorField',
		{	colModel:
			[
				{label:'UOM code',name:'u_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' },
				{label:'Department code',name:'s_deptcode',width:150,classes:'pointer'},
				{label:'Item code',name:'s_itemcode',width:150,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
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


				let data=selrowData('#'+dialog_uomcode.gridname);
				if($("input#"+id_optid+"_pricecode").val() == 'MS'){
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}else{
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val(data['u_convfactor']);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(obj_){
				var pricecode = $("#jqGrid2 input#"+obj_.id_optid+"_pricecode").val();

				if(pricecode == 'MS'){
					$("#jqGrid2 input#"+obj_.id_optid+"_pricecode").val();

					let newcolmodel_uom = [
							{ label: 'UOM code', name: 'u_uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
							{ label: 'Description', name: 'u_description', width: 400, classes: 'pointer', canSearch: true, or_search: true,  checked: true },
							{ label: 'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' }
						]

					$('#'+dialog_uomcode.gridname).jqGrid('setGridParam',{colModel:newcolmodel_uom});

					dialog_uomcode.urlParam.field = getfield(newcolmodel_uom);
					dialog_uomcode.urlParam.table_name = ['material.uom AS u'];
					dialog_uomcode.urlParam.fixPost="true";
					dialog_uomcode.urlParam.table_id="none_";
					dialog_uomcode.urlParam.filterCol=['compcode'];
					dialog_uomcode.urlParam.filterVal=['session.compcode'];
					dialog_uomcode.urlParam.join_type=null;
					dialog_uomcode.urlParam.join_onCol=null;
					dialog_uomcode.urlParam.join_onVal=null;
					dialog_uomcode.urlParam.join_filterCol=null;
					dialog_uomcode.urlParam.join_filterVal=null;

				}else{

					let newcolmodel_uom = [
							{label:'UOM code',name:'u_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
							{label: 'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' },
							{label:'Department code',name:'s_deptcode',width:150,classes:'pointer'},
							{label:'Item code',name:'s_itemcode',width:150,classes:'pointer'},
						]

					$('#'+dialog_uomcode.gridname).jqGrid('setGridParam',{colModel:newcolmodel_uom});

					dialog_uomcode.urlParam.field = getfield(newcolmodel_uom);
					dialog_uomcode.urlParam.table_name = ['material.uom AS u','material.stockloc AS s'];
					dialog_uomcode.urlParam.fixPost="true";
					dialog_uomcode.urlParam.table_id="none_";
					dialog_uomcode.urlParam.filterCol=['s.compcode','s.itemcode','s.deptcode','s.year'];
					dialog_uomcode.urlParam.filterVal=['session.compcode',$("#jqGrid2 input#"+obj_.id_optid+"_itemcode").val(),$('#purreqhd_reqdept').val(),moment($('#purreqhd_purreqdt').val()).year()];
					dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
					dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
					dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
					dialog_uomcode.urlParam.join_filterCol=[['s.compcode on =']];
					dialog_uomcode.urlParam.join_filterVal=[['u.compcode']];

				}
			},
			close: function(){
				// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		},'urlParam', 'radio', 'tab' 	
	);
	dialog_uomcode.makedialog(false);

	var dialog_pouom = new ordialog(
		'pouom', ['material.uom '], "#jqGrid2 input[name='pouom']", 'errorField',
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer' }
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function (event) {
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}
				

				let data=selrowData('#'+dialog_pouom.gridname);

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val(data['convfactor']);
			},

			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			}
			

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.compcode', 'ACTIVE'];

			},
			close: function (obj_) {
				$("#jqGrid2 #"+obj_.id_optid+"_taxcode").focus().select();
			}
		}, 'urlParam', 'radio', 'tab', false
	);
	dialog_pouom.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
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
				

				let data=selrowData('#'+dialog_taxcode.gridname);

				$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				}
			}
			
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_taxcode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_taxcode.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
			close: function(obj_){
				// if($('#jqGridPager2SaveAll').css("display") == "none"){
				// 	$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").first().focus();
				// }
				$("#jqGrid2 #"+obj_.id_optid+"_qtyrequest").focus().select();
			},
			after_check: function(obj_,data){
			}
		},'urlParam', 'radio', 'tab', false
	);
	dialog_taxcode.makedialog(false);
	dialog_taxcode.check_take_all_field = true;

	function cari_gstpercent(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		$("#jqGrid2 #"+id+"_pouom_gstpercent").val(data.rate);
	}

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'purreqhd_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid_selection");
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

	/*var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();*/

	/*var barcode = new gen_barcode('#_token','#but_print_dtl',);
	barcode.init();*/

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

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

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( 'purchaseRequestDetail/form',obj,function( data ) {
					
			});
		}
	}

});

function populate_form(obj){
	//panel header
	$('#purreqno_show').text(padzero(obj.purreqhd_purreqno));
	$('#suppcode_show').text(obj.supplier_name);
}

function empty_form(){
	$('#purreqno_show').text('');
	$('#suppcode_show').text('');
}

function remark_button_class(grid){
	$("#dialog_remarks_view").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
			$('#remarks_view').val('');
		},
		buttons : [{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	this.grid=grid;
	this.selrowdata;

	this.remark_btn_init = function(selrowdata){
		this.selrowdata = selrowdata;
		$('i.my_remark').hide();
		$('i.my_remark').off('click');
		if((this.selrowdata.purreqhd_support_remark != null  || this.selrowdata.purreqhd_support_remark != undefined) && this.selrowdata.purreqhd_support_remark != ''){
			$('i#support_remark_i').show();
			$('i#support_remark_i').data('remark',this.selrowdata.purreqhd_support_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Support Remark');
		}
		if((this.selrowdata.purreqhd_verified_remark != null  || this.selrowdata.purreqhd_verified_remark != undefined) && this.selrowdata.purreqhd_verified_remark != ''){
			$('i#verified_remark_i').show();
			$('i#verified_remark_i').data('remark',this.selrowdata.purreqhd_verified_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Verified Remark');
		}
		if((this.selrowdata.purreqhd_recommended1_remark != null  || this.selrowdata.purreqhd_recommended1_remark != undefined) && this.selrowdata.purreqhd_recommended1_remark != ''){
			$('i#recommended1_remark_i').show();
			$('i#recommended1_remark_i').data('remark',this.selrowdata.purreqhd_recommended1_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Recommended 1 Remark');
		}
		if((this.selrowdata.purreqhd_recommended2_remark != null  || this.selrowdata.purreqhd_recommended2_remark != undefined) && this.selrowdata.purreqhd_recommended2_remark != ''){
			$('i#recommended2_remark_i').show();
			$('i#recommended2_remark_i').data('remark',this.selrowdata.purreqhd_recommended2_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Recommended 2 Remark');
		}
		if((this.selrowdata.purreqhd_approved_remark != null  || this.selrowdata.purreqhd_approved_remark != undefined) && this.selrowdata.purreqhd_approved_remark != ''){
			$('i#approved_remark_i').show();
			$('i#approved_remark_i').data('remark',this.selrowdata.purreqhd_approved_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Approved Remark');
		}
		if((this.selrowdata.purreqhd_cancelled_remark != null  || this.selrowdata.purreqhd_cancelled_remark != undefined) && this.selrowdata.purreqhd_cancelled_remark != ''){
			$('i#cancelled_remark_i').show();
			$('i#cancelled_remark_i').data('remark',this.selrowdata.purreqhd_cancelled_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Cancelled Remark');
		}
		$('i.my_remark').on('click',function(){
			$('#remarks_view').val($(this).data('remark'));
			$("#dialog_remarks_view").dialog( "open" );
		});
	}

	this.remark_btn_off = function(){
		$('i.my_remark').hide();
		$('i.my_remark').off('click');
	}
}