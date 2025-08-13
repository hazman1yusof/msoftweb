
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
				return[{
					element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message: ' '
				}];
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#purordhd_amount', '#purordhd_subamount','#purordhd_totamount']);
	var radbuts=new checkradiobutton(['purordhd_taxclaimable']);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	var myattachment = new attachment_page("purchaseorder","#jqGrid","purordhd_idno");
	var my_remark_button = new remark_button_class('#jqgrid');

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#purdate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper=null;
	var unsaved = false;
	scrollto_topbtm();
	page_to_view_only($('#viewonly').val());

	$("#dialogForm")
		.dialog({
			width: 9.5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				$('#purordhd_prdept').focus();
				$('#jqGridPager2EditAll').data('click',false);
				unsaved = false;
				errorField.length=0;
				$("#jqGrid2").jqGrid("setFrozenColumns");
				parent_close_disabled(true);
				my_remark_button.remark_btn_init(selrowData("#jqGrid"));
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", false);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$("#purordhd_prdept").val($("#deptcode").val());
						dialog_prdept.check(errorField);
						$("#purordhd_reqdept").val($("#deptcode").val());
						dialog_reqdept.check(errorField);
						$("input[type=radio][name='purordhd_taxclaimable'][value='Non-Claimable']").prop("checked",true);
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
				}if (oper != 'add') {
					dialog_credcode.check(errorField);
					dialog_prdept.check(errorField);
					dialog_suppcode.check(errorField);
					dialog_deldept.check(errorField);
					dialog_assetno.check(errorField);
					dialog_salesorder.check(errorField);

				}if (oper != 'view') {
					backdated.set_backdate($('#purordhd_prdept').val());
					dialog_reqdept.on();
					dialog_purreqno.on();
					dialog_prdept.on();
					dialog_suppcode.on();
					dialog_deldept.on();
					dialog_credcode.on();
					dialog_assetno.on();
					dialog_salesorder.on();
				}

				if(oper == 'edit'){
					dialog_reqdept.off();
					dialog_purreqno.off();
					dialog_prdept.off();
					dialog_deldept.off();
					dialog_assetno.off();
					
					$("#purordhd_reqdept,#purordhd_purreqno,#purordhd_prdept,#purordhd_deldept,#purordhd_assetno").prop('readonly',true);
					$('#dialogForm input:radio[name=purordhd_prtype]').attr('disabled',true);
				}
			},
			beforeClose: function (event, ui) {
				if (unsaved) {
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function (result) {
						if (result == true) {
							unsaved = false;
							delete_dd($('#purordhd_idno').val());
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function (event, ui) {
				errorField.length=0;
				oper=null;
				addmore_jqgrid2.state = false;
			    addmore_jqgrid2.more = false;
			    //reset balik
			    parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_reqdept.off();
				dialog_purreqno.off();
				dialog_prdept.off();
				dialog_suppcode.off();
				dialog_deldept.off();
				dialog_credcode.off();
				dialog_assetno.off();
				dialog_salesorder.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				$("#purordhd_reqdept,#purordhd_reqdept,#purordhd_purreqno").prop('readonly',false);
				$('#dialogForm input:radio[name=purordhd_prtype]').attr('disabled',false);
				refreshGrid("#jqGrid2",null,"kosongkan");
				radbuts.reset();
			},
		});
	/////////////////////////////////////////////end dialog/////////////////////////////////////////////
	
	$('#formdata input:radio[name="purordhd_prtype"]').change(function (){
		$("#assetno_div").hide();
		let prtype = $('#formdata input:radio[name=purordhd_prtype]:checked').val();
		if(prtype == 'AssetMaintenance'){
			$("#assetno_div").show();
			$("#purordhd_prdept").val($("#pcs_dept").val());
			dialog_prdept.check(errorField);
		}else if(prtype == 'Others'){
			$("#purordhd_prdept").val($("#pcs_dept").val());
			dialog_prdept.check(errorField);
		}else if(prtype == 'Asset'){
			$("#purordhd_prdept").val($("#deptcode").val());
			dialog_prdept.check(errorField);
		}else if(prtype == 'Stock'){
			$("#purordhd_prdept").val($("#deptcode").val());
			dialog_prdept.check(errorField);
		}
	});
	
	var backdated = new func_backdated('#purordhd_deldept');
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
			filterVal:['PO'],
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
					$('#purordhd_purdate').attr('min',backdate);
				}
			});
		}
	}

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////

	var recstatus_filter = [['OPEN','PREPARED']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','PREPARED','SUPPORT','INCOMPLETED','VERIFIED','APPROVED','CANCELLED','COMPLETED','PARTIAL']];
		filterCol_urlParam = ['purordhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}else if($("#recstatus_use").val() == 'SUPPORT'){
		recstatus_filter = [['PREPARED']];
		filterCol_urlParam = ['purordhd.compcode','queuepo.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'VERIFIED'){
		recstatus_filter = [['PREPARED']];
		filterCol_urlParam = ['purordhd.compcode','queuepo.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'APPROVED'){
		recstatus_filter = [['VERIFIED']];
		filterCol_urlParam = ['purordhd.compcode','queuepo.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'REOPEN'){
		recstatus_filter = [['CANCELLED']];
		filterCol_urlParam = ['purordhd.compcode','queuepo.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'CANCEL'){
		recstatus_filter = [['OPEN']];
		filterCol_urlParam = ['purordhd.compcode','queuepo.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}
	var cbselect = new checkbox_selection("#jqGrid","Checkbox","purordhd_idno","purordhd_recstatus",recstatus_filter[0][0]);

	var urlParam = {
		// action: 'get_table_default',
		// url:'util/get_table_default',
		action: 'maintable',
		url:'./purchaseOrder/table',
		scope: $('#recstatus_use').val(),
		field:'',
		fixPost: 'true',
		table_name: ['material.purordhd', 'material.supplier'],
		table_id: 'purordhd_idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['supplier.SuppCode'],
		join_onVal: ['purordhd.suppcode'],
		join_filterCol: [['supplier.compcode =']],
		join_filterVal: [['session.compcode']],
		// filterCol:['purordhd.prdept'],
		// filterVal:[$('#deptcode').val()],
		filterCol: filterCol_urlParam,
		filterVal: filterVal_urlParam,
		WhereInCol:['purordhd.recstatus'],
		WhereInVal: recstatus_filter,
				
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'purOrder_header_save',
		url:'./purchaseOrder/form',
		field: '',
		fixPost: 'true',
		oper: oper,
		table_name: 'material.purordhd',
		table_id: 'purordhd_recno',
		checkduplicate:'true'

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
				$('#ponodepan').text("");//tukar kat depan tu
				$('#prdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#ponodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record\nNo', name: 'purordhd_recno', width: 7, canSearch: true, selected: true },
			{ label: 'Purchase Department', name: 'purordhd_prdept', width: 15, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Delivery Department', name: 'purordhd_deldept', width: 15, hidden: false, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Purchase Order No', name: 'purordhd_purordno', width: 10, classes: 'wrap', align: 'right', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Req No', name: 'purordhd_purreqno', width: 20, hidden: true },
			{ label: 'DelordNo', name: 'purordhd_delordno', width: 20, width: 10, classes: 'wrap', hidden:true},
			{ label: 'Request Department', name: 'purordhd_reqdept', width: 30, hidden: true },
			{ label: 'Purchase Order <br> Date', name: 'purordhd_purdate', width: 10, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'expecteddate', name: 'purordhd_expecteddate', width: 20, formatter: dateFormatter, unformat: dateUNFormatter, hidden: true },
			{ label: 'expirydate', name: 'purordhd_expirydate', width: 20, formatter: "date", hidden: true },
			// { label: 'Supplier Code', name: 'purordhd_suppcode', width: 30, classes: 'wrap', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Supplier Code', name: 'purordhd_suppcode', width: 30, classes: 'wrap' },
			{ label: 'Supplier Name', name: 'supplier_name', width: 35, classes: 'wrap', canSearch: true, selected: true },
			{ label: 'credcode', name: 'purordhd_credcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'termsdays', name: 'purordhd_termdays', width: 20, hidden: true },
			{ label: 'subamount', name: 'purordhd_subamount', width: 30, hidden: true,align: 'right', formatter: 'currency'},
			{ label: 'amtdisc', name: 'purordhd_amtdisc', width: 30, hidden: true },
			{ label: 'perdisc', name: 'purordhd_perdisc', width: 30, hidden: true },
			{ label: 'Total Amount', name: 'purordhd_totamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'isspersonid', name: 'purordhd_isspersonid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'issdate', name: 'purordhd_issdate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'authpersonid', name: 'purordhd_authpersonid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'authdate', name: 'purordhd_authdate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Remark', name: 'purordhd_remarks', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Asset No.', name: 'purordhd_assetno', hidden: true },
			{ label: 'Status', name: 'purordhd_recstatus', width: 14 },
			{ label: 'postedby', name: 'purordhd_postedby', width: 40, hidden:true},
			{ label: 'postdate', name: 'purordhd_postdate', width: 40, hidden:true},
			{ label: 'taxclaimable', name: 'purordhd_taxclaimable', width: 40, hidden:true},
			{ label: 'adduser', name: 'purordhd_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'purordhd_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'purordhd_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'purordhd_upddate', width: 90, hidden: true },
			{ label: 'reopenby', name: 'purordhd_reopenby', width: 40, hidden:true},
			{ label: 'requestby', name: 'purordhd_requestby', width: 90, hidden: true },
			{ label: 'requestdate', name: 'purordhd_requestdate', width: 90, hidden: true },
			{ label: 'supportby', name: 'purordhd_supportby', width: 90, hidden: true },
			{ label: 'supportdate', name: 'purordhd_supportdate', width: 40, hidden: true},
			{ label: 'verifiedby', name: 'purordhd_verifiedby', width: 90, hidden: true },
			{ label: 'verifieddate', name: 'purordhd_verifieddate', width: 90, hidden: true },
			{ label: 'approvedby', name: 'purordhd_approvedby', width: 90, hidden: true },
			{ label: 'approveddate', name: 'purordhd_approveddate', width: 40, hidden: true},	
			{ label: 'reopendate', name: 'purordhd_reopendate', width: 40, hidden:true},
			{ label: 'cancelby', name: 'purordhd_cancelby', width: 40, hidden:true},
			{ label: 'canceldate', name: 'purordhd_canceldate', width: 40, hidden:true},
			{ label: 'support_remark', name: 'purordhd_support_remark', width: 40, hidden:true},
			{ label: 'verified_remark', name: 'purordhd_verified_remark', width: 40, hidden:true},
			{ label: 'approved_remark', name: 'purordhd_approved_remark', width: 40, hidden:true},
			{ label: 'cancelled_remark', name: 'purordhd_cancelled_remark', width: 40, hidden:true},
			{ label: 'purordhd_prtype', name: 'purordhd_prtype', width: 40, hidden:true},
			{ label: 'salesorder', name: 'purordhd_salesorder', width: 90, hidden: true, key:true },
			{ label: 'idno', name: 'purordhd_idno', width: 90, hidden: true, key:true },
			{ label: 'unit', name: 'purordhd_unit', width: 40, hidden:true},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox ,hidden:false},

		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'purordhd_idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (data, rowid, selected) {
            $("#gridAttch_panel").collapse('hide');
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").purordhd_recstatus;
			let scope = $("#recstatus_use").val();

			// $('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			// if (stat == scope || stat == "CANCELLED") {
			// 	$('#but_reopen_jq').show();
			// } else {
			// 	if(scope == 'ALL'){
			// 		if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0 && stat=='OPEN'){
			// 			$('#but_cancel_jq').show();
			// 		}else if(stat=='OPEN'){
			// 			$('#but_post_jq').show();
			// 		}
			// 	}else{
			// 		if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0){
			// 			$('#but_cancel_jq').show();
			// 		}else{
			// 			$('#but_post_jq').show();
			// 		}
			// 	}
			// }

			urlParam2.filterVal[0] = selrowData("#jqGrid").purordhd_recno;
			refreshGrid("#jqGrid3",urlParam2);
			populate_form(selrowData("#jqGrid"));

			urlParam_gridDoHd.filterVal[1]=selrowData("#jqGrid").purordhd_purordno;
			urlParam_gridDoHd.filterVal[2]=selrowData("#jqGrid").purordhd_prdept;
			if($('#gridDoHd_panel').attr('aria-expanded') == 'true'){
				refreshGrid('#gridDoHd', urlParam_gridDoHd);
			}
			
			$('#ponodepan').text(selrowData("#jqGrid").purordhd_purordno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").purordhd_prdept);
			
            refreshGrid("#jqGrid3", urlParam2);

            $("#pdfgen1").attr('href','./purchaseOrder/showpdf?recno='+selrowData("#jqGrid").purordhd_recno);

			$("#pdfgen2").attr('href','./purchaseOrder/showpdf?recno='+selrowData("#jqGrid").purordhd_recno);

			if(stat=='PREPARED' || stat=='SUPPORT' || stat=='INCOMPLETED' || stat=='VERIFIED' || stat=='APPROVED' || stat=='CANCELLED' || stat=='COMPLETED' || stat=='VERIFIED'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
		},

		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").purordhd_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED' || stat=='PREPARED' || stat=='APPROVED' ){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
			
		},
		gridComplete: function () {
			$('#but_post_jq,#but_cancel_jq').hide();
			if ($("#jqGrid").data('lastselrow') == '-1' || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
			populate_form(selrowData("#jqGrid"));
			//empty_form();

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			errorField.length = 0;
		},
		loadComplete: function(){
			let stat = selrowData("#jqGrid").purordhd_recstatus;
			if(stat=='PREPARED' || stat=='SUPPORT' || stat=='INCOMPLETED' || stat=='VERIFIED' || stat=='APPROVED' || stat=='CANCELLED' || stat=='COMPLETED' || stat=='VERIFIED'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
			//calc_jq_height_onchange("jqGrid");
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
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam, oper);
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
			$('#purordhd_purordno').val(padzero($('#purordhd_purordno').val()));
			refreshGrid("#jqGrid2", urlParam2);
		
			if($('#formdata input:radio[name=purordhd_prtype]:checked').val() == 'AssetMaintenance'){
				$("#assetno_div").show();
			}else{
				$("#assetno_div").hide();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			let stat = selrowData("#jqGrid").purordhd_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED' || stat=='PREPARED' || stat=='APPROVED' ){
				oper = 'edit';
				selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
				$("#jqGrid").data('lastselrow',selRowId);
				populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
				$('#purordhd_purordno').val(padzero($('#purordhd_purordno').val()));
				refreshGrid("#jqGrid2", urlParam2);
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
			
			if($('#formdata input:radio[name=purordhd_prtype]:checked').val() == 'AssetMaintenance'){
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
		onClickButton: function () {
			$("#jqGrid").data('lastselrow','-1');
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid', '#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid', false, urlParam);
	addParamField('#jqGrid', false, saveParam, ['purordhd_recno','purordhd_purordno','purordhd_adduser', 'purordhd_adddate','purordhd_upduser','purordhd_upddate','purordhd_deluser', 'purordhd_idno', 'supplier_name','purordhd_recstatus','purordhd_unit','Checkbox']);
	
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
					
					$.post( './purchaseOrder/form', obj , function( data ) {
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
			if (confirm("Are you sure to reject this purchase Order?") == true) {
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
			obj.idno_array = [selrowData('#jqGrid').purordhd_idno];
		}else{
			return false;
		}
		
		$.post( './purchaseOrder/form', obj , function( data ) {
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
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './purchaseOrder/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText)
			$(self_).attr('disabled',false);
		}).success(function(data){
			
		});
	});


	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;
		$('#purordhd_purordno').val(unpadzero($('#purordhd_purordno').val()));

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
		
		},'json').fail(function (data) {
			// $('.noti').text(data.responseText);
			
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			
			mycurrency.formatOn();
			dialog_prdept.on();
			dialog_reqdept.on();
			dialog_purreqno.on();
		    dialog_suppcode.on();
		    dialog_credcode.on();
			dialog_deldept.on();
			dialog_assetno.on();
			dialog_salesorder.on();

		}).done(function (data) {
			$("#saveDetailLabel").attr('disabled',false)
			hideatdialogForm(false);

			addmore_jqgrid2.state = true;

			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#purordhd_recno').val(data.recno);
				$('#purordhd_purordno').val(data.purordno);
				$('#purordhd_idno').val(data.idno);//just save idno for edit later
				$('#purordhd_totamount').val(data.totalAmount);
				$('#purordhd_adduser').val(data.adduser);
				$('#purordhd_adddate').val(data.adddate);

				urlParam2.filterVal[0] = data.recno;
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
				$('#purordhd_upduser').val(data.upduser);
				$('#purordhd_upddate').val(data.upddate);
			}

			if($('#purordhd_purreqno').val().trim() == ""){
				$("#jqGrid2").jqGrid('hideCol',["qtyoutstand"]);
			}else{
				$("#jqGrid2").jqGrid('showCol',["qtyoutstand"]);
			}
			
			refreshGrid('#jqGrid2', urlParam2);
			disableForm('#formdata');
			
		});
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
		});
		searchClick2('#jqGrid', '#searchForm', urlParam);
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
	
	////////////////////////////changing status and trandept trigger search////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);
	
	function whenchangetodate(){
		supplierkatdepan.off();
		if($('#Scol').val() == 'purordhd_purdate'){
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		}else if($('#Scol').val() == 'purordhd_suppcode'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
			supplierkatdepan.on();
		}else{
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

				urlParam.searchCol=["purordhd_suppcode"];
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
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	searchChange(true);
	function searchChange(once=false) {
		var arrtemp = ['session.compcode', $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b.toUpperCase() == 'ALL') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purordhd.compcode', 'purordhd.recstatus', 'purordhd.prdept'], fv:[], fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;

		if(once){
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if($('#searchForm [name=Stext]').val().trim() != ''){
				let searchCol = ['purordhd_recno'];
				let searchVal = [$('#searchForm [name=Stext]').val().trim()];
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
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
            $rows = $('>div>table.ui-jqgrid-btable>tbody>tr', this.grid.bDiv);
            $('>table.ui-jqgrid-btable>tbody>tr', this.grid.fbDiv).each(function (i) {
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
            $rows = $('>div>table.ui-jqgrid-htable>thead>tr', this.grid.hDiv);
            $('>table.ui-jqgrid-htable>thead>tr', this.grid.fhDiv).each(function (i) {
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
		url:'./purchaseOrderDetail/table',
		field: ['podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.suppcode', 'podt.purdate','podt.pricecode', 'podt.itemcode', 'p.description','podt.uomcode','podt.pouom','podt.qtyorder','podt.qtyoutstand','podt.qtyrequest','podt.qtydelivered', 'podt.perslstax', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc','podt.amtslstax as tot_gst','podt.netunitprice','podt.totamount','podt.amount','podt.rem_but AS remarks_button','podt.remarks', 'podt.unit', 't.rate'],
		table_name: ['material.purorddt AS podt', 'material.productmaster AS p', 'hisdb.taxmast AS t'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN','LEFT JOIN'],
		join_onCol: ['podt.itemcode', 'podt.taxcode'],
		join_onVal: ['p.itemcode', 't.taxcode'],
		filterCol: ['podt.recno', 'podt.compcode', 'podt.recstatus'],
		filterVal: ['', 'session.compcode', '<>.DELETE']
	};
	
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./purchaseOrderDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable:false},

			{ label: 'Price Code', name: 'pricecode', width: 80, classes: 'wrap', editable:true,
					editrules:{required: true, custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:pricecodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:false, hidden:false},
			{ label: 'UOM Code', name: 'uomcode', width: 120, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{
				label: 'PO UOM', name: 'pouom', width: 120, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Tax Code', name: 'taxcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:taxcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Quantity On Hand', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Quantity Request', name: 'qtyrequest', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Quantity Order', name: 'qtyorder', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true, 
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
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
			{ label: 'Quantity<br/>Balanced', name: 'qtyoutstand', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Percentage Discount (%)', name: 'perdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Discount Per Unit', name: 'amtdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total Line Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', hidden:true},
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'wrap', hidden: false },
			{ label: 'unit', name: 'unit', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'prdept', name: 'prdept', width: 20, classes: 'wrap', hidden:true},
			{ label: 'purordno', name: 'purordno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		],
		scroll: false,
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 250,
		rowNum: 1000000,
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
							element.callback_param[2],
							function(){
								fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
							}
						)
					});
				}
			});
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			// }
			// else if(addmore_jqgrid2.state == true && $('#purordhd_purreqno').val().trim().length > 0 && $('#jqGridPager2EditAll').data('click') == false){
			// 	$('#jqGridPager2EditAll').data('click',true);
			// 	$('#jqGridPager2EditAll').click();
			}else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.more = false; //reset
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		onSelectRow: function (rowid, selected) {
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
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
			// calculate_quantity_outstanding('#jqGrid2');

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
		beforeSubmit: function(postdata, rowid){ 
			dialog_itemcode.check(errorField);//have function or not??
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);
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


	// $("#jqGrid2").jqGrid('setGroupHeaders', {
  	// useColSpanStyle: false, 
	//   groupHeaders:[
	// 	{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
	// 	{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
	//   ]
	// })


	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

		if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}
		if($('#recstatus_use').val() == 'ALL'){
			if(rowObject.purordhd_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'SUPPORT'){
			if(rowObject.purordhd_recstatus == "PREPARED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'VERIFIED'){
			if(rowObject.purordhd_recstatus == "PREPARED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'APPROVED'){
			if(rowObject.purordhd_recstatus == "VERIFIED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'CANCEL'){
			if(rowObject.purordhd_recstatus == "OPEN" || rowObject.purordhd_recstatus == "PREPARED" || rowObject.purordhd_recstatus == "VERIFIED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'REOPEN'){
			if(rowObject.purordhd_recstatus == "CANCELLED"){
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
		close: function(){
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
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
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			myfail_msg.clear_fail();
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			if($('#dialogForm input:radio[name=purordhd_prtype]:checked').val() == 'Stock'){
				$("#jqGrid2 #"+rowid+"_pricecode").val('IV');
			}else{
				$("#jqGrid2 #"+rowid+"_pricecode").val('MS');
			}

			dialog_pricecode.on();//start binding event on jqgrid2
			dialog_itemcode.on();
			dialog_uomcode.on();
			dialog_pouom.on();
			dialog_taxcode.on();

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
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyorder']"]);
			
			// $("input[name='gstpercent']").val('0')//reset gst to 0
			// mycurrency2.formatOnBlur();//make field to currency on leave cursor
			// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtyorder']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtyorder']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom']").on('focus',remove_noti);
			
			$("input[name='totamount']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});

        	// cari_gstpercent($("#jqGrid2 input[name='taxcode']").val());
		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#purordhd_purordno').val(resobj.purordno);
			$('#purordhd_recno').val(resobj.recno);
			$('#purordhd_totamount').val(resobj.totalAmount);
			$('#purordhd_subamount').val(resobj.totalAmount);
			mycurrency.formatOn();
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = resobj.recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		errorfunc: function(rowid,response){
			errorField.length=0;
        	// alert(response.responseText);
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

			// if(myfail_msg.fail_msg_array.length>0){
			// 	return false;
			// }

			if(parseInt($('#jqGrid2 input[name="qtyorder"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./purchaseOrderDetail/form?"+
				$.param({
					action: 'purOrder_detail_save',
					idno: $('#purordhd_idno').val(),
					recno: $('#purordhd_recno').val(),
					prdept: $('#purordhd_prdept').val(),
					suppcode: $('#purordhd_suppcode').val(),
					purdate: $('#purordhd_purdate').val(),
					prdept: $('#purordhd_prdept').val(),
					purordno: $('#purordhd_purordno').val(),
					remarks:data.remarks,
					amount:data.amount,
					netunitprice:data.netunitprice,
					lineno_:data.lineno_,
				});


			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
			//calculate_conversion_factor();	
		},
		afterrestorefunc : function( response ) {
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			errorField.length=0;
			hideatdialogForm(false);
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
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
				    	if(result == true){
				    		param={
				    			_token: $("#_token").val(),
				    			action: 'purOrder_detail_save',
								recno: $('#purordhd_recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
				    		}
				    		$.post( "./purchaseOrderDetail/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#purordhd_totamount').val(data);
								$('#purordhd_subamount').val(data);
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
			mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
		    for (var i = 0; i < ids.length; i++) {
		    	var objdata = $("#jqGrid2").jqGrid ('getRowData', ids[i]);
		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		        if(objdata.pricecode.slice(0, objdata.pricecode.search("[<]")) == 'MS'){
			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount", "#"+ids[i]+"_qtyorder"]);
		        }else{
			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount"]);

			        Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_qtyorder"]);
		        }

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
		        dialog_taxcode.check(errorField,ids[i]+"_taxcode","jqGrid2",null,
		        	undefined,
		        	function(data,self){
			        	if(data.rows.length > 0){
							$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
			        	}
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
		        	}
		        );

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

				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'pricecode' : $("#jqGrid2 input#"+ids[i]+"_pricecode").val(),
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'pouom' : $("#jqGrid2 input#"+ids[i]+"_pouom").val(),
		    		'qtyorder' : $('#'+ids[i]+"_qtyorder").val(),
		    		// 'qtydelivered' : data.qtydelivered,
		    		'qtyoutstand' : $("#jqGrid2 input#"+ids[i]+"_qtyoutstand").val(),
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
    			action: 'purOrder_detail_save',
				_token: $("#_token").val(),
				recno: $('#purordhd_recno').val(),
				action: 'purOrder_detail_save',
				purordno:$('#purordhd_purordno').val(),
				suppcode:$('#purordhd_suppcode').val(),
				purdate:$('#purordhd_purdate').val(),
				prdept:$('#purordhd_prdept').val(),
				expecteddate:$('#purordhd_expecteddate').val(),
    		}

    		$.post( "./purchaseOrderDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){

				$('#purordhd_totamount').val(data);
				$('#purordhd_subamount').val(data);
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
			case 'purordhd_prdept':field=['deptcode','description'];table="sysdb.department";case_='purordhd_prdept';break;
			case 'purordhd_deldept':field=['deptcode','description'];table="sysdb.department";case_='purordhd_deldept';break;
			case 'purordhd_suppcode':field=['suppcode','name'];table="material.supplier";case_='purordhd_suppcode';break;
			case 'delordhd_prdept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_prdept';break;
			case 'delordhd_deldept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_deldept';break;
			case 'delordhd_reqdept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_reqdept';break;
			case 'h_suppcode':field=['SuppCode','Name'];table="material.supplier";case_='h_suppcode';break;
			case 'h_prdept':field=['deptcode','description'];table="sysdb.department";case_='h_prdept';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('purchaseOrder',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		return cellvalue;
	}

	function format_qtyoutstand(cellvalue, options, rowObject){
		var qtyoutstand = rowObject.qtyorder - rowObject.qtydelivered;
		if(qtyoutstand<0 || isNaN(qtyoutstand)) return 0;
		return qtyoutstand;
	}
	// function formatter_recvqtyonhand(cellvalue, options, rowObject) {
	// 	var prdept = $('#prdept').val();
	// 	var datetrandate = new Date($('#reqdt').val());
	// 	var getyearinput = datetrandate.getFullYear();

	// 	var param = { action: 'get_value_default', field: ['qtyonhand'], table_name: 'material.stockloc' }

	// 	param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
	// 	param.filterVal = [getyearinput, rowObject[3], prdept, rowObject[5]];

	// 	$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

	// 	}, 'json').done(function (data) {
	// 		if (!$.isEmptyObject(data.rows)) {
	// 			$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").text(data.rows[0].qtyonhand);
	// 		}
	// 	});
	// 	return "";
	// }

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			case 'Item Code': temp = $("jqGrid2 input[name='#itemcode']"); break;
			case 'UOM Code': temp = $("jqGrid2 input[name='#uomcode']"); break;
			case 'PO UOM': 
				temp = $("#jqGrid2 input[name='pouom']"); 
				var text = $( temp ).parent().siblings( ".help-block" ).text();
				if(text == 'Invalid Code'){
					return [false,"Please enter valid "+name+" value"];
				}

				break;
			case 'Price Code': temp = $("jqGrid2 input[name='#pricecode']"); break;
			case 'Tax Code': temp = $("jqGrid2 input[name='#taxcode']"); break;
			case 'Quantity Ordered': temp = $("#jqGrid2 input[name='qtyorder']"); 
				$("#jqGrid2 input[name='qtyorder']").hasClass("error");
				break;
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
		if($('#purordhd_purreqno').val().trim().length > 0){
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0" readonly="readonly"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}else{
			return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
		}
	}
	function pouomCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden"><input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`><input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`></div>`);
	}
	function taxcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
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
		unsaved = false;
		dialog_reqdept.off();
		dialog_purreqno.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_credcode.off();
		dialog_deldept.off();
		dialog_assetno.off();
		dialog_salesorder.off();
		radbuts.check();
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			errorField.length = 0;
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_prdept.on();
			dialog_reqdept.on();
			dialog_purreqno.on();
		    dialog_suppcode.on();
		    dialog_credcode.on();
			dialog_deldept.on();
			dialog_assetno.on();
			dialog_salesorder.on();
		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		addmore_jqgrid2.state = false;
		hideatdialogForm(true);
		dialog_reqdept.off();
		dialog_purreqno.off();
		dialog_prdept.off();
		dialog_suppcode.on();
		dialog_credcode.on();
		dialog_deldept.off();
		dialog_assetno.off();
		dialog_salesorder.on();
		
		enableForm('#formdata');
		rdonly('#formdata');
		
		$("#purordhd_reqdept,#purordhd_purreqno,#purordhd_prdept,#purordhd_deldept,#purordhd_assetno").prop('readonly',true);
		$('#dialogForm input:radio[name=purordhd_prtype]').attr('disabled',true);
		addmore_jqgrid2.state = addmore_jqgrid2.more = false;
		
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac//////////////////////////////////
	function calculate_conversion_factor(event) {

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
		var pricecode = $("#jqGrid2 input#"+id_optid+"_pricecode").val();

		if(pricecode == 'MS'){
			return true;
		}

		var id="#jqGrid2 #"+id_optid+"_qtyorder";
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#jqGrid2 input#"+id_optid+"_pouom_convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#jqGrid2 input#"+id_optid+"_pouom_convfactor_pouom").val());
		let qtyorder = parseFloat($("#jqGrid2 input#"+id_optid+"_qtyorder").val());

		console.log(convfactor_pouom)
		console.log(qtyorder)
		console.log(convfactor_uom)
		console.log(balconv)

		var balconv = convfactor_pouom*qtyorder%convfactor_uom;

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
		remove_error("#jqGrid2 #"+id_optid+"_qtyorder");
		remove_error("#jqGrid2 #"+id_optid+"_unitprice");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_pouom");
		}, 500 );


		$(".noti").empty();

	}

	//////////////////////////////calculate outstanding quantity/////////////////////
	// function calculate_quantity_outstanding(grid){
	// 	var ids = $(grid).jqGrid('getDataIDs');

	// 	var jqgrid2_data = [];
	//     for (var i = 0; i < ids.length; i++) {

	// 		var data = $(grid).jqGrid('getRowData',ids[i]);
	// 		var qtyoutstand = data.qtyorder - data.qtydelivered;

	// 		$(grid).jqGrid('setRowData', ids[i] ,{qtyoutstand:qtyoutstand});
	//     }
	// }
	///////////////////////////////////////////////////////////////////////////////


	/////////////////////////////edit all//////////////////////////////////////////////////

	function onall_editfunc(){
		errorField.length = 0;
		dialog_pricecode.off();
		$(dialog_pricecode.textfield).attr('disabled',true);
		dialog_itemcode.off();
		$(dialog_itemcode.textfield).attr('disabled',true);
		dialog_uomcode.off();
		$(dialog_uomcode.textfield).attr('disabled',true);
		dialog_pouom.on();
		dialog_taxcode.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtyorder'], #jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt_all);

		$("#jqGrid2 input[name='qtyorder']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt_all);

		$("#jqGrid2 input[name='qtyorder']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom']").on('focus',remove_noti);
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
       
		let qtyorder = parseFloat($("#"+id_optid+"_qtyorder").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());

		var totamtperUnit = ((unitprice*qtyorder) - (amtdisc*qtyorder));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		if(isNaN(tot_gst))tot_gst = 0;

		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?
		
		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});

		var id="#jqGrid2 #"+id_optid+"_qtyorder";
		var fail_msg = "Quantity Ordered must be greater than 0";
		var name = "quantityorder";

		if(name_from != 'taxcode'){
			if (qtyorder > 0) {
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

		if(event.target.name=='unitprice'){
			var id2="#jqGrid2 #"+id_optid+"_unitprice";
			var fail_msg2 = "Unitprice cannot be 0";
			var name2 = "unitprice";
			if($("input#"+id_optid+"_pricecode").val() != 'BO' && unitprice == 0 ) {
				$( id2 ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id2 ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name2+"']").length)$('.noti').prepend("<li data-errorid='"+name2+"'>"+fail_msg2+"</li>");
				if($.inArray(id2,errorField)===-1){
					errorField.push( id2 );
				}
			} else {
				if($.inArray(id2,errorField)!==-1){
					errorField.splice($.inArray(id2,errorField), 1);
				}
				$( id2 ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id2 ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name2+"']").detach();
			}
		}

		if(event.target.name=='qtyorder' && $('#purordhd_purreqno').val().trim().length >0){
			var id3="#jqGrid2 #"+id_optid+"_qtyorder";
			var fail_msg3 = "Quantity Ordered cant exceed Quantity Balanced";
			var name3 = "qtyoutstand";

			var qtybalance = parseFloat($("#"+id_optid+"_qtyoutstand").val());

			if(qtyorder > qtybalance){
				if(!$('.noti').find("li[data-errorid='"+name3+"']").length)$('.noti').prepend("<li data-errorid='"+name3+"'>"+fail_msg3+"</li>");
				// $( id3 ).val('');
			}else{
				$('.noti').find("li[data-errorid='"+name3+"']").detach();
			}
		}

		event.data.currency.formatOn();//change format to currency on each calculation
		mycurrency_np.formatOn();

		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	}

	function calculate_line_totgst_and_totamt_all(event) {
		var name_from = $(event.currentTarget).attr('name');
		
        mycurrency2.formatOff();
		mycurrency_np.formatOff();
		
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
       
		let qtyorder = parseFloat($("#"+id_optid+"_qtyorder").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());

		var totamtperUnit = ((unitprice*qtyorder) - (amtdisc*qtyorder));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		if(isNaN(tot_gst))tot_gst = 0;

		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?
		
		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});

		var all_totamt=0;
		$.each($("#jqGrid2 input[name=totamount]"), function (index, value) {
			all_totamt = parseFloat(all_totamt) + parseFloat($(this).val());
		});
		$('#purordhd_subamount, #purordhd_totamount').val(all_totamt);

		var id="#jqGrid2 #"+id_optid+"_qtyorder";
		var fail_msg = "Quantity Ordered must be greater than 0";
		var name = "quantityorder";

		if(name_from != 'taxcode'){
			if (qtyorder > 0) {
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

		if(event.target.name=='unitprice'){
			var id2="#jqGrid2 #"+id_optid+"_unitprice";
			var fail_msg2 = "Unitprice cannot be 0";
			var name2 = "unitprice";
			if($("input#"+id_optid+"_pricecode").val() != 'BO' && unitprice == 0 ) {
				$( id2 ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id2 ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name2+"']").length)$('.noti').prepend("<li data-errorid='"+name2+"'>"+fail_msg2+"</li>");
				if($.inArray(id2,errorField)===-1){
					errorField.push( id2 );
				}
			} else {
				if($.inArray(id2,errorField)!==-1){
					errorField.splice($.inArray(id2,errorField), 1);
				}
				$( id2 ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id2 ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name2+"']").detach();
			}
		}

		if(event.target.name=='qtyorder' && $('#purordhd_purreqno').val().trim().length >0){
			var id3="#jqGrid2 #"+id_optid+"_qtyorder";
			var fail_msg3 = "Quantity Ordered cant exceed Quantity Balanced";
			var name3 = "qtyoutstand";

			var qtybalance = parseFloat($("#"+id_optid+"_qtyoutstand").val());

			if(qtyorder > qtybalance){
				if(!$('.noti').find("li[data-errorid='"+name3+"']").length)$('.noti').prepend("<li data-errorid='"+name3+"'>"+fail_msg3+"</li>");
				$( id3 ).val('');
			}else{
				$('.noti').find("li[data-errorid='"+name3+"']").detach();
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
		rowNum: 1000000,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
	
		loadComplete: function(data){ //ini baru
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

			calc_jq_height_onchange("jqGrid3");
		},

		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();

			// calculate_quantity_outstanding('#jqGrid3');
		},
	}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	$("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");

	$("#jqGrid3_panel").on('show.bs.collapse', function(){
		fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	});

	///////////////////////////////////parameter for grid do///////////////////////////////////////////////////////////////
	
	var urlParam_gridDoHd={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['material.delordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.SuppCode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['trantype','srcdocno','prdept'],
		filterVal:['GRN', 'DD','DD'],
	}

	var urlParam_gridDoDt={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode','dodt.pouom', 'dodt.suppcode','dodt.trandate','dodt.deldept','dodt.deliverydate','dodt.qtyorder','dodt.qtydelivered', 'dodt.qtyoutstand','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks', 'dodt.unit','t.rate','dodt.idno', 'dodt.prdept', 'dodt.srcdocno'],
		table_name:['material.delorddt AS dodt','material.productmaster AS p','hisdb.taxmast AS t'],
		table_id:'lineno_',
		join_type:['LEFT JOIN','LEFT JOIN'],
		join_onCol:['dodt.itemcode','dodt.taxcode'],
		join_onVal:['p.itemcode','t.taxcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		filterVal:['','session.compcode','<>.DELETE']
	};

	var urlParam_gridGRTHd={
		action:'get_table_default',
		url:'./goodReturn/table',
		field:'',
		fixPost:'true',
		table_name:['material.delordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.SuppCode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['delordhd.trantype','delordhd.srcdocno','delordhd.prdept'],
		filterVal:['GRT', 'DD','DD'],
	}

	var urlParam_gridGRTDt={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode', 'dodt.pouom', 'dodt.suppcode','dodt.trandate',
		'dodt.deldept','dodt.deliverydate','dodt.qtydelivered','dodt.qtyreturned','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 
		'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks','t.rate',],
		table_name:['material.delorddt AS dodt','material.productmaster AS p','hisdb.taxmast AS t'],
		table_id:'lineno_',
		join_type:['LEFT JOIN','LEFT JOIN'],
		join_onCol:['dodt.itemcode','dodt.taxcode'],
		join_onVal:['p.itemcode','t.taxcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus','dodt.qtyreturned'],
		filterVal:['','session.compcode','<>.DELETE','>.0']
	};

	$("#gridDoHd").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record No', name: 'delordhd_recno', width: 120, classes: 'wrap', canSearch: true, frozen: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', width: 190, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 190, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'DO No', name: 'delordhd_delordno', width: 150, classes: 'wrap', canSearch: true},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 190, canSearch: true, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'GRN No', name: 'delordhd_docno', width: 150, classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero, align: 'right'},
			{ label: 'Received Date', name: 'delordhd_trandate', width: 200, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 250, classes: 'wrap', canSearch: true},
			{ label: 'Supplier Name', name: 'supplier_name', width: 250, classes: 'wrap', canSearch: true },
			{ label: 'Purchase Order No', name: 'delordhd_srcdocno', width: 150, classes: 'wrap', canSearch: true, align:'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 200, classes: 'wrap', canSearch: true},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 200, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 200, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 200},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 120,align: "center", formatter: formatterCheckbox },		        
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 250, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deliverydate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:true},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:true},
			{ label: 'postedby', name: 'delordhd_postedby', width: 40, hidden:true},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:true},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:true},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:true},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:true},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:true},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:true},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:true},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:true},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:true},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:true},
			{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:true},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:true},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:true},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:true},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:true},
			{ label: 'cancelby', name: 'delordhd_cancelby', width: 40, hidden:true},
			{ label: 'canceldate', name: 'delordhd_canceldate', width: 40, hidden:true},
			{ label: 'reopenby', name: 'delordhd_reopenby', width: 40, hidden:true},
			{ label: 'reopendate', name: 'delordhd_reopendate', width: 40, hidden:true},
			{ label: 'unit', name: 'delordhd_unit', width: 40, hidden:true},

		],
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'delordhd_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerDoHd",
		onSelectRow: function (data, rowid, selected) {
			urlParam_gridDoDt.filterVal[0]=selrowData("#gridDoHd").delordhd_recno;
			if($('#gridDoDt_panel').attr('aria-expanded') == 'true'){
				refreshGrid('#gridDoDt', urlParam_gridDoDt);
			}

			urlParam_gridGRTHd.filterVal[1]=selrowData("#gridDoHd").delordhd_docno;
			urlParam_gridGRTHd.filterVal[2]=selrowData("#gridDoHd").delordhd_prdept;
			if($('#gridGRTHd_panel').attr('aria-expanded') == 'true'){
				refreshGrid('#gridGRTHd', urlParam_gridGRTHd);
			}
		},
		gridComplete: function () {
			$("#gridDoHd").setSelection($("#gridDoHd").getDataIDs()[0]);
		},
		loadComplete: function(data){
			calc_jq_height_onchange("gridDoHd");
			fdl.set_array().reset();
		}
	});

	addParamField('#gridDoHd', false, urlParam_gridDoHd);

	////////////////////// set label gridDoHd right ////////////////////////////////////////////////
	jqgrid_label_align_right("#gridDoHd");

	$("#gridDoDt").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'No', name: 'lineno_', width: 60, classes: 'wrap', editable:false},
			
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:false, hidden:true}, 
			{ label: 'Price Code', name: 'pricecode', width: 200, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
							{  custom_element:pricecodeCustomEdit,
								custom_value:galGridCustomValue 	
							},
			},
			{ label: 'Item Code', name: 'itemcode', width: 180, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
						edittype:'custom',	editoptions:
							{  custom_element:itemcodeCustomEdit,
								custom_value:galGridCustomValue 	
							},
			},
			{ label: 'UOM Code', name: 'uomcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
							{  custom_element:uomcodeCustomEdit,
								custom_value:galGridCustomValue 	
							},
			},
			{
				label: 'PO UOM', name: 'pouom', width: 130, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Qty <br> Delivered', name: 'qtydelivered', width: 150, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true,custom:true, custom_func:cust_rules},edittype:"text",
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
			{ label: 'O/S <br> Quantity', name: 'qtyoutstand', width: 130, align: 'right', classes: 'wrap', editable:true,	
				formatter: format_qtyoutstand, formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 110, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Tax Code', name: 'taxcode', width: 180, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
							{  custom_element:taxcodeCustomEdit,
								custom_value:galGridCustomValue 	
							},
			},
			{ label: 'Percentage <br> Discount (%)', name: 'perdisc', width: 160, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Discount <br> Per Unit', name: 'amtdisc', width: 130, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Total <br> GST <br> Amount', name: 'tot_gst', width: 120, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},
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
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total <br> Line Amount', name: 'totamount', width: 130, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Expiry <br> Date', name: 'expdate', width: 150, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: 1,
							showOn: 'focus',
							changeMonth: true,
								changeYear: true,
						});
					}
				}
			},
			{ label: 'Batch No', name: 'batchno', width: 140, classes: 'wrap', editable:true,
					maxlength: 30,
			},
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Remarks', name: 'remarks_button', width: 130, formatter: formatterRemarks,unformat: unformatRemarks,hidden:true},
			{ label: 'Remarks', name: 'remarks', hidden:true},
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'whtspc_wrap'},
			{ label: 'unit', name: 'unit', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'srcdocno', name: 'srcdocno', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'prdept', name: 'prdept', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
			{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Quantity Order', name: 'qtyorder', editable:false, hidden:true},

		],
		scroll: false,
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
	    rowNum: 1000000,
	    pgbuttons: false,
	    pginput: false,
	    pgtext: "",
		sortname: 'idno',
		pager: "#jqGridPagerDoDt",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(data){
			calc_jq_height_onchange("gridDoDt");
			fdl.set_array().reset();
		}
	});

	$("#gridGRTHd").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'delordhd_recno', width: 10, classes: 'wrap', canSearch: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', width: 18, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 18, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 18, hidden: true, classes: 'wrap' },
			{ label: 'GRT No', name: 'delordhd_docno', width: 15, classes: 'wrap', align: 'right', canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: 'GRN No', name: 'delordhd_srcdocno', width: 15, classes: 'wrap', align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'PO No', name: 'do2_srcdocno', width: 15, classes: 'wrap',  align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Returned Date', name: 'delordhd_trandate', width: 20, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 25, classes: 'wrap', canSearch: true},
			{ label: 'Supplier Name', name: 'supplier_name', width: 25, classes: 'wrap', canSearch: true },
			{ label: 'DO No', name: 'delordhd_delordno', width: 15, classes: 'wrap', canSearch: true},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 20, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 20, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 20},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 25, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deliverydate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:'true'},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:'true'},
			{ label: 'postedby', name: 'delordhd_postedby', width: 40, hidden:'true'},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:'true'},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:'true'},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:'true'},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:'true'},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:'true'},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:'true'},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:'true'},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:'true'},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:'true'},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:'true'},
			{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:'true'},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:'true'},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:'true'},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:'true'},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:'true'},
			{ label: 'cancelby', name: 'delordhd_cancelby', width: 40, hidden:'true'},
			{ label: 'canceldate', name: 'delordhd_canceldate', width: 40, hidden:'true'},
			{ label: 'reopenby', name: 'delordhd_reopenby', width: 40, hidden:'true'},
			{ label: 'reopendate', name: 'delordhd_reopendate', width: 40, hidden:'true'},
			{ label: 'unit', name: 'delordhd_unit', width: 40, hidden:true},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'delordhd_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerGRTHd",
		onSelectRow:function(rowid, selected){
			urlParam_gridGRTDt.filterVal[0]=selrowData("#gridGRTHd").delordhd_recno;
			if($('#gridGRTDt_panel').attr('aria-expanded') == 'true'){
				refreshGrid('#gridGRTDt', urlParam_gridGRTDt);
			}
		},
		gridComplete: function () {
			$("#gridGRTHd").setSelection($("#gridGRTHd").getDataIDs()[0]);
		},
		loadComplete: function(data){
			calc_jq_height_onchange("gridGRTHd");
			fdl.set_array().reset();
		},
	});

	addParamField('#gridGRTHd', false, urlParam_gridGRTHd);

	////////////////////// set label gridGRTHd right ////////////////////////////////////////////////
	jqgrid_label_align_right("#gridGRTHd");

	$("#gridGRTDt").jqGrid({
		datatype: "local",
		editurl: "./goodReturnDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:true, hidden:true},
			{ label: 'Price Code', name: 'pricecode', width: 100, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'Item Code', name: 'itemcode', width: 110, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'UOM Code', name: 'uomcode', width: 110, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'POUOM', name: 'pouom', width: 120, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'GRN Quantity', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Quantity Returned', name: 'qtyreturned', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer',
				editrules:{required: true,custom:true, custom_func:cust_rules},edittype:"text",
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
			{ label: 'Unit Price', name: 'unitprice', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4},
				editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Tax Code', name: 'taxcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:taxcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Percentage Discount (%)', name: 'perdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4},
					editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Discount Per Unit', name: 'amtdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
					editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},edittype:"text",
						editoptions:{ readonly: "readonly",
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
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total Line Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{decimalPlaces: 2, thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Expiry Date', name: 'expdate', width: 100, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: { readonly: "readonly",
                    dataInit: function (element) {
                        $(element).datepicker({
                            id: 'expdate_datePicker',
                            dateFormat: 'dd/mm/yy',
                            minDate: 1,
                            showOn: 'focus',
                            changeMonth: true,
		  					changeYear: true,
                        });
                    }
                }
			},
			{ label: 'Batch No', name: 'batchno', width: 70, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" },
					maxlength: 30,
			},
		
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden:true},
			{ label: 'rate', name: 'rate', width: 60, classes: 'wrap',hidden:true},
		],
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 1000000,
		sortname: 'qtyreturned',
		sortorder: "desc",
		pager: "#jqGridPagerGRTDt",
		loadComplete: function(data){
			calc_jq_height_onchange("gridGRTDt");
			fdl.set_array().reset();
		},
		gridComplete: function(){
		}
	});

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#purordhd_reqdept', 'errorField',
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['recstatus', 'compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
			ondblClickRow: function () {
				$('#purordhd_purreqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_purreqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Request Department",
			open: function(){
				dialog_reqdept.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_reqdept.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_reqdept.makedialog();
	
	var dialog_purreqno = new ordialog(
		'purreqno',['material.purreqhd AS h'],'#purordhd_purreqno',errorField,
		{
			colModel:[
				{label:'Request No',name:'h_purreqno',width:20,classes:'pointer',canSearch:true,or_search:true},
				{label:'Request Department', name: 'h_reqdept', width: 60, classes: 'pointer',canSearch:true,checked:true,or_search:true },
				{label:'Supplier Code',name:'h_suppcode',width:60,classes:'pointer wrap', formatter: showdetail,unformat:un_showdetail},
				{label:'Purchase Department',name:'h_prdept',width:60,classes:'pointer wrap', formatter: showdetail,unformat:un_showdetail},
				{label:'PR Type',name:'h_prtype',width:60,classes:'pointer'},
				{label:'PerDisc',name:'h_perdisc',width:400,classes:'pointer',hidden:true},
				{label:'AmtDisc',name:'h_amtdisc',width:400,classes:'pointer',hidden:true},
				{label:'Total Amount',name:'h_totamount',width:40,classes:'pointer',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
				{label:'Sub Amount',name:'h_subamount',width:400,classes:'pointer',hidden:true},
				{label:'Status',name:'h_recstatus',width:400,classes:'pointer',hidden:true},
				{label:'Remark',name:'h_remarks',width:90,classes:'pointer wrap',hidden:false},
				{label:'recno',name:'h_recno',width:50,classes:'pointer',hidden:true},
				{label:'assetno',name:'h_assetno',width:50,classes:'pointer',hidden:true}
			],
			sortname: 'h_recno',
			sortorder: "desc",
			urlParam: {
				filterCol:['h.reqdept'],
				filterVal:[$("#purordhd_reqdept").val()],
				WhereInCol:['h.recstatus'],
				WhereInVal:[['PARTIAL','APPROVED']]
			},
			gridComplete: function() {
				fdl.set_array().reset();
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_purreqno.gridname);
				console.log(data);
				$("#purordhd_purreqno").val(data['h_purreqno']);
				$("#purordhd_reqdept").val(data['h_reqdept']);
				$("#purordhd_suppcode").val(data['h_suppcode']);
				$("#purordhd_credcode").val(data['h_suppcode']);
				$("#purordhd_prdept").val(data['h_prdept']);
				$("#purordhd_perdisc").val(data['h_perdisc']);
				$("#purordhd_amtdisc").val(data['h_.amtdisc']);
				$("#purordhd_totamount").val(data['h_totamount']);
				$("#purordhd_subamount").val(data['h_subamount']);
				$("#purordhd_recstatus").val("OPEN");
				$("#purordhd_remarks").val(data['h_remarks']);
				$('input[type=radio][name=purordhd_prtype][value='+data['h_prtype']+']').prop('checked',true);
				$('#referral').val(data['h_recno']);
				$("#purordhd_assetno").val(data['h_assetno']);
				
				dialog_credcode.check(errorField);
				dialog_suppcode.check(errorField);
				dialog_deldept.check(errorField);
				dialog_assetno.check(errorField);
				
				if(data['h_prtype'] == 'AssetMaintenance'){
					$("#assetno_div").show();
				}else{
					$("#assetno_div").hide();
				}
				
				mycurrency.formatOn();
				
				var urlParam2 = {
					action: 'get_value_default',
					url: 'util/get_value_default',
					field: ['prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.qtybalance', 'prdt.itemcode', 'prdt.uomcode','prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax', 'prdt.amount','rem_but AS remarks_button','prdt.remarks','prdt.recstatus'],
					table_name: ['material.purreqdt AS prdt'],
					table_id: 'lineno_',
					filterCol: ['prdt.recno', 'prdt.compcode', 'prdt.recstatus'],
					filterVal: [data['h_recno'], 'session.compcode', '<>.DELETE']
				};

				$.get("util/get_value_default?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						data.rows.forEach(function(elem) {
							$("#jqGrid2").jqGrid('addRowData', elem['lineno_'] ,
								{
									compcode:elem['compcode'],
									recno:elem['recno'],
									lineno_:elem['lineno_'],
									pricecode:elem['pricecode'],
									itemcode:elem['itemcode'],
									description:elem['description'],
									uomcode:elem['uomcode'],
									pouom:elem['pouom'],
									qtyrequest:elem['qtyrequest'],
									qtydelivered:0,
									qtyoutstand:elem['qtybalance'],
									unitprice:elem['unitprice'],
									taxcode:elem['taxcode'],
									perdisc:elem['perdisc'],
									amtdisc:elem['amtdisc'],
									tot_gst:0,
									amount:elem['amount'],
									remarks_button:null,
									remarks:elem['remarks'],
									//rate:elem['rate']
								}
							);
						});
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

					} else {

					}
				});
				
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_suppcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}

		},{
			title:"Select Request No",
			open: function(){
				$("#jqGrid2").jqGrid("clearGridData", true);

				let prtype = $('#formdata input:radio[name=purordhd_prtype]:checked').val();
				if(prtype == 'AssetMaintenance' || prtype == 'Others'){
					dialog_purreqno.urlParam.fixPost = "true";
					dialog_purreqno.urlParam.filterCol = ['h.reqdept','h.prtype'];
					dialog_purreqno.urlParam.filterVal = [$("#purordhd_reqdept").val(),prtype];
					dialog_purreqno.urlParam.WhereInCol = ['h.recstatus'];
					dialog_purreqno.urlParam.WhereInVal = [['PARTIAL','APPROVED']];
				}else{
					dialog_purreqno.urlParam.fixPost = "true";
					dialog_purreqno.urlParam.filterCol = ['h.reqdept','h.prtype','h.unit'];
					dialog_purreqno.urlParam.filterVal = [$("#purordhd_reqdept").val(),prtype,'session.unit'];
					dialog_purreqno.urlParam.WhereInCol = ['h.recstatus'];
					dialog_purreqno.urlParam.WhereInVal = [['PARTIAL','APPROVED']];
				}
			},
			close: function(){
				// $("#purordhd_suppcode").focus();
			}
		},'none'
	);
	dialog_purreqno.makedialog();

	var dialog_prdept = new ordialog(
		'prdept','sysdb.department','#purordhd_prdept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['purdept', 'recstatus', 'compcode'],
						filterVal:['1', 'ACTIVE','session.compcode']
			},
			ondblClickRow: function () {
				$('#purordhd_deldept').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_deldept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_prdept.urlParam.filterCol=['purdept', 'recstatus', 'compcode'];
				dialog_prdept.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_prdept.makedialog(false);

	var dialog_deldept = new ordialog(
		'deldept','material.deldept','#purordhd_deldept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['recstatus', 'compcode'],
						filterVal:['ACTIVE','session.compcode']
					},
					ondblClickRow: function () {
						$('#purordhd_reqdept').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#purordhd_reqdept').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Receiver Department",
			open: function(){
				dialog_deldept.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_deldept.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deldept.makedialog(false);

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#purordhd_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_suppcode.gridname);
				$("#purordhd_credcode").val(data['suppcode']);
				dialog_credcode.check(errorField);
				$('#purordhd_purdate').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#purordhd_purdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Transaction Type",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_suppcode.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcode.makedialog();

	var dialog_credcode = new ordialog(
		'credcode', 'material.supplier', '#purordhd_credcode', errorField,
		{
			colModel: [
				{ label: 'Creditor Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Creditor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true,checked:true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				$('#purordhd_purdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_purdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Creditor",
			open: function () {
				dialog_credcode.urlParam.filterCol = ['compcode','recstatus'];
				dialog_credcode.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		}, 'urlParam','radio','tab'
	);
	dialog_credcode.makedialog();

	var dialog_salesorder = new ordialog(
		'salesorder', ['debtor.dbacthdr as d','hisdb.pat_mast as p'], '#purordhd_salesorder', errorField,
		{
			colModel: [
				{ label: 'Auditno', name: 'd_auditno', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'HUKM MRN', name: 'd_mrn', width: 100, classes: 'pointer', canSearch: true,checked:true, or_search: true },
				{ label: 'Patient Name', name: 'p_Name', width: 300, classes: 'pointer'},
				{ label: 'Amount', name: 'd_amount', width: 100, classes: 'pointer'},
				{ label: 'Remark', name: 'd_remark', width: 300, classes: 'pointer'},
			],
			urlParam: {
						filterCol:['d.compcode'],
						filterVal:['session.compcode'],
						fixPost:true
					},
			ondblClickRow: function () {
				$('#purordhd_remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_purdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Sales Order",
			open: function (obj_) {
				dialog_salesorder.urlParam.table_name = ['debtor.dbacthdr as d','hisdb.pat_mast as p'];
				dialog_salesorder.urlParam.fixPost = "true";
				dialog_salesorder.urlParam.table_id = "none_";
				dialog_salesorder.urlParam.filterCol = ['d.compcode','d.deptcode','d.source','d.trantype','d.MRN'];
				dialog_salesorder.urlParam.filterVal = ['session.compcode',$('#purordhd_prdept').val(),'PB','IN','<>.NULL'];
				dialog_salesorder.urlParam.join_type = ['LEFT JOIN'];
				dialog_salesorder.urlParam.join_onCol = ['d.mrn'];
				dialog_salesorder.urlParam.join_onVal = ['p.newmrn'];
				dialog_salesorder.urlParam.join_filterCol = [['p.compcode =']];
				dialog_salesorder.urlParam.join_filterVal = [['session.compcode']];
			},
			close: function(){
				$('#purordhd_remarks').focus();
			}
		}, 'urlParam','radio','tab'
	);
	dialog_salesorder.makedialog();
	
	var dialog_assetno = new ordialog(
		'assetno', 'finance.faregister', '#purordhd_assetno', errorField,
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
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				let data = selrowData('#'+dialog_pricecode.gridname);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
					//$("#jqGrid2 input[name='itemcode']").focus().select();
				}
			}
		},{
			title:"Select Price Code For Item",
			open: function(obj_){
				let prtype = $('#dialogForm input:radio[name=purordhd_prtype]:checked').val();
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
			close: function(obj_){
				// $(dialog_pricecode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
				$("#jqGrid2 input[name='itemcode']").focus().select();
			}
		},'urlParam',jgrid2='#jqGrid2', 'tab'
	);
	dialog_pricecode.makedialog(false);


	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'Generic',name:'p_generic',width:200,classes:'pointer',canSearch:true},
				{label: 'Quantity On Hand',name:'p_qtyonhand',width:100,classes:'pointer',},
				{label: 'UOM Code',name:'p_uomcode',width:100,classes:'pointer'},
				{label: 'Tax Code', name: 'p_TaxCode', width: 100, classes: 'pointer' },
				{label: 'Group Code',name:'p_groupcode',width:100,classes:'pointer'},
				{label: 'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
				{label: 'rate', name: 't_rate', width: 100, classes: 'pointer',hidden:true },
				{label: 'Unit', name:'p_unit',hidden:true},
				
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
			ondblClickRow:function(event){
				//$("#jqGrid2 input[name='pouom']").focus().select();
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
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
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );

			    dialog_pouom.id_optid = id_optid;
		        dialog_pouom.check(errorField,id_optid+"_pouom","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );

				dialog_taxcode.id_optid = id_optid;
		        dialog_taxcode.check(errorField,id_optid+"_taxcode","jqGrid2",null,
		        	undefined,
		        	function(data,self){
			        	if(data.rows.length > 0){
							$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
			        	}
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
		        	}
		        );

				if($("input#"+id_optid+"_pricecode").val() != 'MS'){
					dialog_uomcode.urlParam.filterVal[1] = data['p_itemcode'];
				}

				//fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus();
					// $("#jqGrid2 input[name='pouom']").focus().select();
				}
			},
			loadComplete:function(data){
			}
		},{
			title:"Select Item For Purchase Order",
			open:function(obj_){
				let prtype = $('#dialogForm input:radio[name=purordhd_prtype]:checked').val();

				if(prtype == 'Stock'){
					dialog_itemcode.urlParam.table_name = ['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u']
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode', 's.unit'];
					dialog_itemcode.urlParam.filterVal = ['on.p.compcode', moment($('#purordhd_purreqdt').val()).year(), $('#purordhd_deldept').val(),'session.unit'];
					dialog_itemcode.urlParam.join_type = ['JOIN','LEFT JOIN','LEFT JOIN'];
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
				// $(dialog_itemcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
				$("#jqGrid2 #"+obj_.id_optid+"_qtyorder").focus().select();
			}
		},'none','radio','tab'//urlParam means check() using urlParam not check_input
	);
	dialog_itemcode.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom',['material.stockloc AS s','material.uom AS u'],"#jqGrid2 input[name='uomcode']",errorField,
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
				$("#jqGrid2 input[name='pouom']").focus().select();

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
					//$(obj.textfield).closest('td').next().find("input[type=text]").focus();
					//$("#jqGrid2 input[name='pouom']").focus().select();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(obj_){
				var pricecode = $("#jqGrid2 input#"+obj_.id_optid+"_pricecode").val();

				if(pricecode == 'MS'){
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
					dialog_uomcode.urlParam.filterVal=['session.compcode',$("#jqGrid2 input#"+obj_.id_optid+"_itemcode").val(),$('#purordhd_deldept').val(),moment($('#purordhd_purdate').val()).year()];
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
			},
			justb4refresh: function(obj_){
				dialog_uomcode.urlParam.searchCol2=[];
				dialog_uomcode.urlParam.searchVal2=[];
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
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked:true, or_search: true },
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
				//$("#jqGrid2 input[name='qtyorder']").focus().select();
				
			},

			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus();
					// $("#jqGrid2 input[name='qtyorder']").focus().select();
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
		}, 'urlParam', 'radio', 'tab' 
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
				//$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$(obj.textfield).closest('td').next().find("input[type=text]").focus();
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
				// 	.find("input[type=text]").focus();
				// }
				$("#jqGrid2 #"+obj_.id_optid+"_qtyorder").focus().select();
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
		sortname: 'purordhd_idno',
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

	$("#jqGrid3_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#jqGrid3_panel",100);
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		calc_jq_height_onchange("jqGrid3");
	});

	$("#gridDoHd_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#gridDoHd_panel",100);
        refreshGrid('#gridDoHd', urlParam_gridDoHd);
		$("#gridDoHd").jqGrid ('setGridWidth', Math.floor($("#gridDoHd_c")[0].offsetWidth-$("#gridDoHd_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridDoHd");
	});

	$("#gridDoDt_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#gridDoDt_panel",100);
        refreshGrid('#gridDoDt', urlParam_gridDoDt);
		$("#gridDoDt").jqGrid ('setGridWidth', Math.floor($("#gridDoDt_c")[0].offsetWidth-$("#gridDoDt_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridDoDt");
	});

	$("#gridGRTHd_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#gridGRTHd_panel",100);
        refreshGrid('#gridGRTHd', urlParam_gridGRTHd);
		$("#gridGRTHd").jqGrid ('setGridWidth', Math.floor($("#gridGRTHd_c")[0].offsetWidth-$("#gridGRTHd_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridGRTHd");
	});

	$("#gridGRTDt_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#gridGRTDt_panel",100);
        refreshGrid('#gridGRTDt', urlParam_gridGRTDt);
		$("#gridGRTDt").jqGrid ('setGridWidth', Math.floor($("#gridGRTDt_c")[0].offsetWidth-$("#gridGRTDt_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridGRTDt");
	});

	var add_fr_pr = new add_fr_pr();
	add_fr_pr.on();

	function add_fr_pr(){
		this.urlParam = {
			action: 'add_from_pr',
			oper: 'add_from_pr',
			url:'./purchaseOrder/form'
		}

		this.dialog = `<div id="dialog_add_fr_pr" title="Add From Purchase Request">
					  <div class="panel panel-default">
					    <div class="panel-body">
					    <div id='table_add_fr_pr_c' class='col-xs-12' align='center'><table id='table_add_fr_pr' class='table table-striped'></table><div id='table_add_fr_prPager'></div>
					    </div>
					  </div>
					</div>`;

		this.on = function(){
			$("html").append(this.dialog);
			var self = this;

			$("#dialog_add_fr_pr").dialog({
				autoOpen: false,
				width: 9/10 * $(window).width(),
				modal: true,
				open: function(event, ui){
					$("#table_add_fr_pr").jqGrid ('setGridWidth', Math.floor($("#table_add_fr_pr_c")[0].offsetWidth-$("#table_add_fr_pr_c")[0].offsetLeft));
					console.log(self.urlParam)
					refreshGrid("#table_add_fr_pr",self.urlParam);
				},
				close: function( event, ui ){
				},
			});

			$("#table_add_fr_pr").jqGrid({
				datatype: "local",
				colModel: [
					{ label: 'Record No', name: 'recno', width: 15, formatter: padzero, unformat: unpadzero },
					{ label: 'Purchase No', name: 'purreqno', width: 15, formatter: padzero, unformat: unpadzero },
					{ label: 'Request Department', name: 'reqdept', width: 15, classes: 'wrap' },
					{ label: 'Price Code', name: 'pricecode', width: 15, classes: 'wrap' },
					{ label: 'Item Code', name: 'itemcode', width: 30 , classes: 'wrap' },
					{ label: 'Uom Code', name: 'uomcode', width: 10},
					{ label: 'Quantity Request', name: 'qtyrequest', width: 15},
					{ label: 'Amount', name: 'amount', width: 15 },
					{ label: 'Unit Price', name: 'unitprice',align: 'right', formatter: 'currency' , width: 15},
					{ label: 'Tax Code', name: 'taxcode', width: 15},
					{ label: 'Tax Amount', name: 'amtslstax', width: 15},
					{ label: 'Total Amount', name: 'totamount',align: 'right', formatter: 'currency' , width: 15 },
					{ label: 'Remarks', name: 'remarks', width: 30 },
				],
				scroll: true,shrinkToFit: true,autowidth: true,viewrecords:true,loadonce:false,width:1000,height:200,rowNum:30,hoverrows:false,
				pager: "#table_add_fr_prPager",
				onSelectRow:function(rowid, selected){
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
				},
				loadComplete: function(data) {
			    },
				gridComplete: function() {
			    },

			});

			$("#add_fr_pr").on('click',{data:this},onClick);

			return this;
		}

		function onClick(event){
			$("#dialog_add_fr_pr").dialog('open');
		}

	}

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( 'purchaseOrderDetail/form',obj,function( data ) {
					
			});
		}
	}
});

function populate_form(obj){
	//panel header
	$('.prdept_show').text(obj.purordhd_prdept);
	$('.purordno_show').text(padzero(obj.purordhd_purordno));
	$('.suppcode_show').text(obj.supplier_name);
	
}

function empty_form(){
	$('.prdept_show').text('');
	$('.purordno_show').text('');
	$('.suppcode_show').text('');

}

function fail_msg_func(fail_msg_div=null){
	this.fail_msg_div = (fail_msg_div!=null)?fail_msg_div:'div#fail_msg';
	this.fail_msg_array=[];
	this.add_fail=function(fail_msg){
		let found=false;
		this.fail_msg_array.forEach(function(e,i){
			if(e.id == fail_msg.id){
				e.msg=fail_msg.msg;
				found=true;
			}
		});
		if(!found){
			this.fail_msg_array.push(fail_msg);
		}
		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				return false;
			}
			return true;
		});

		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.fail_msg_array = new_msg_array;
		this.pop_fail();
	}
	this.clear_fail=function(){
		this.fail_msg_array=[];
		this.pop_fail();
	}
	this.pop_fail=function(){
		var self=this;
		$(self.fail_msg_div).html('');
		this.fail_msg_array.forEach(function(e,i){
			$(self.fail_msg_div).append("<li>"+e.msg+"</li>");
		});
	}
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
		if((this.selrowdata.purordhd_support_remark != null  || this.selrowdata.purordhd_support_remark != undefined) && this.selrowdata.purordhd_support_remark != ''){
			$('i#support_remark_i').show();
			$('i#support_remark_i').data('remark',this.selrowdata.purordhd_support_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Support Remark');
		}
		if((this.selrowdata.purordhd_verified_remark != null  || this.selrowdata.purordhd_verified_remark != undefined) && this.selrowdata.purordhd_verified_remark != ''){
			$('i#verified_remark_i').show();
			$('i#verified_remark_i').data('remark',this.selrowdata.purordhd_verified_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Verified Remark');
		}
		if((this.selrowdata.purordhd_approved_remark != null  || this.selrowdata.purordhd_approved_remark != undefined) && this.selrowdata.purordhd_approved_remark != ''){
			$('i#approved_remark_i').show();
			$('i#approved_remark_i').data('remark',this.selrowdata.purordhd_approved_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Approved Remark');
		}
		if((this.selrowdata.purordhd_cancelled_remark != null  || this.selrowdata.purordhd_cancelled_remark != undefined) && this.selrowdata.purordhd_cancelled_remark != ''){
			$('i#cancelled_remark_i').show();
			$('i#cancelled_remark_i').data('remark',this.selrowdata.purordhd_cancelled_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Cancelled Remark');
		}
		$('i.my_remark').on('click',function(){
			$('#remarks_view').val($(this).data('remark'));
			$("#dialog_remarks_view").dialog( "open" );
		});
	}
}