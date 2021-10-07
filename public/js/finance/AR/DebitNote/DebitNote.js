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
				return {
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#amount']);
	var fdl = new faster_detail_load();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper = null;
	var unsaved = false;

	$("#dialogForm")
		.dialog({
			width: 9.5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						//$("#purreqhd_reqdept").val($("#x").val());
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
					dialog_CustomerSO.check(errorField);
				} if (oper != 'view') {
					dialog_CustomerSO.on();
				}
			},
			beforeClose: function (event, ui) {
				if (unsaved) {
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function (result) {
						if (result == true) {
							unsaved = false
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
				dialog_CustomerSO.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////

	var recstatus_filter = [['OPEN','REQUEST']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','REQUEST','SUPPORT','INCOMPLETED','VERIFIED','APPROVED','CANCELLED']];
		filterCol_urlParam = ['purreqhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}else if($("#recstatus_use").val() == 'SUPPORT'){
		recstatus_filter = [['REQUEST']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'VERIFIED'){
		recstatus_filter = [['SUPPORT']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'APPROVED'){
		recstatus_filter = [['VERIFIED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","recstatus");

	
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	
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
	var urlParam = {
		action: 'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name: ['debtor.dbacthdr as db','debtor.debtormast as dm'],
		table_id: 'idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['db.debtorcode'],
		join_onVal: ['dm.debtorcode'],
		filterCol: ['source','trantype'],
		filterVal: ['PB','DN'],
		// WhereInCol:['purreqhd.recstatus'],
		// WhereInVal: recstatus_filter,
		fixPost: true,
	}

	var saveParam = {
		action: 'DebitNote_header_save',
		url:'/DebitNote/form',
		field: '',
		oper: oper,
		table_name: 'debtor.dbacthdr',
		table_id: 'idno',
		fixPost: true,
		//returnVal: true,
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			{ label: 'db_debtorcode', name: 'db_debtorcode', hidden: true},
			{ label: 'Customer', name: 'dm_name', width: 50, canSearch: true, classes: 'wrap' },
			{ label: 'Docdate', name: 'db_entrydate', width: 15},
			{ label: 'Debit No', name: 'db_auditno', width: 12, align: 'right', canSearch: true},
			//{ label: 'Debit No', name: 'db_invno', width: 15, canSearch: true, formatter: padzero5, unformat: unpadzero },
			{ label: 'Sector', name: 'db_units', width: 15, canSearch: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 10, formatter: padzero5, unformat: unpadzero },
			{ label: 'Amount', name: 'db_amount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'db_recstatus', width: 15 },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
			{ label: 'Payer Code', name: 'db_payercode', width: 15, canSearch: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'trantype', name: 'db_trantype', width: 20, hidden: true },
			{ label: 'lineno_', name: 'db_lineno_', width: 20, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'outamount', name: 'db_outamount', width: 20, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'mrn', name: 'db_mrn', width: 10, hidden: true },
			{ label: 'units', name: 'db_units', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'db_posteddate', name: 'db_posteddate',hidden: true,},
			{ label: 'Department Code', name: 'db_deptcode', width: 15, canSearch: true, hidden: true },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			{ label: 'remarks', name: 'db_remark', width: 10, hidden: true },
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'db_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").db_recstatus;
			let scope = $("#recstatus_use").val();

			urlParam2.source = selrowData("#jqGrid").db_source;
			urlParam2.trantype = selrowData("#jqGrid").db_trantype;
			urlParam2.auditno = selrowData("#jqGrid").db_auditno;
			
			$('#reqnodepan').text(selrowData("#jqGrid").purreqhd_purreqno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));

			$("#pdfgen1").attr('href','./DebitNote/showpdf?auditno='+selrowData("#jqGrid").db_auditno);

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").db_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			cbselect.show_hide_table();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			populate_form(selrowData("#jqGrid"));
			fdl.set_array().reset();

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
		},

	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

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
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');
			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2", urlParam2);
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

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid', '#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam,['Checkbox']);
	addParamField('#jqGrid', false, saveParam, ['purreqhd_recno','purreqhd_purordno','purreqhd_adduser', 'purreqhd_adddate', 'db_mrn', 'supplier_name','purreqhd_purreqno','purreqhd_upduser','purreqhd_upddate','purreqhd_deluser', 'purreqhd_recstatus','purreqhd_unit','Checkbox','queuepr_AuthorisedID']);

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

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$('#jqGrid2_ilcancel').click(function(){
		$(".noti").empty();
	});

	$("#but_post_jq,#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( 'DebitNote/form', obj , function( data ) {
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

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			},'json')
		.fail(function (data) {
			alert(data.responseJSON.message);
			$('.noti').text(data.responseJSON.message);
		}).done(function (data) {
			$("#saveDetailLabel").attr('disabled',false)
			unsaved = false;
			hideatdialogForm(false);

			addmore_jqgrid2.state = true;
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}

			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#purreqhd_recno').val(data.recno);
				$('#purreqhd_purreqno').val(data.purreqno);
				$('#idno').val(data.idno);//just save idno for edit later
				$('#purreqhd_totamount').val(data.totalAmount);

				urlParam2.filterVal[0] = data.recno;
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
			}
			disableForm('#formdata');
		})
	}

	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

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

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	//$('#trandept').on('change', searchChange);

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

	function searchChange() {
		cbselect.empty_sel_tbl();
		var arrtemp = ['session.compcode', $('#Status option:selected').val(), $('#trandept option:selected').val()];  //ni apeni trandept guna tak///

		var filter = arrtemp.reduce(function (a, b, c) {
			if (b.toUpperCase() == 'ALL') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purreqhd.compcode', 'purreqhd.recstatus', 'purreqhd.prdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;
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
		url:'DebitNoteDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		field:['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document', 'dbactdtl.AmtB4GST', 'dbactdtl.GSTCode', 'dbactdtl.amount', 'dbactdtl.dorecno', 'dbactdtl.grnno', 'dbactdtl.amtslstax as tot_gst'],
		table_name:['debtor.dbactdtl AS dbactdtl'],
		table_id:'lineno_',
		filterCol:['dbactdtl.compcode','dbactdtl.auditno', 'dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', 'RC']
	};
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/DebitNoteDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
            { label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: true,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {  
                        custom_element:deptcodeCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
            { label: 'Category', name: 'category', width: 150, edittype:'text', classes: 'wrap', editable: true,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {  
                        custom_element:categoryCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
            { label: 'Document', name: 'document', width: 150, classes: 'wrap', editable: true,
                //editrules:{required: true},
                edittype:"text",
				editoptions: {style: "text-transform: uppercase"},
            },
            { label: 'GST Code', name: 'GSTCode', width: 100, edittype:'text', classes: 'wrap', editable: true,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {
                        custom_element:GSTCodeCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
            { label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap',
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
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
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
            { label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true
            },
            { label: 'Amount', name: 'amount', width: 90, classes: 'wrap', 
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
			{ label: 'No', name: 'lineno_', width: 10, classes: 'wrap', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 20, classes: 'wrap', hidden: true },
		],
		autowidth: false,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			/// console.log(addmore_jqgrid2); ///
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			setjqgridHeight(data,'jqGrid2');			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		
		gridComplete: function(){

			fdl.set_array().reset();
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

		},
		beforeSubmit: function (postdata, rowid) {
			dialog_billtypeSO.check(errorField);
			//dialog_mrn.check(errorField);
			dialog_CustomerSO.check(errorField);
			dialog_approvedbySO.check(errorField);
		}

		}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	$("#jqGrid2").jqGrid('bindKeys');
		var updwnkey_fld;
		function updwnkey_func(event){
			var optid = event.currentTarget.id;
			var fieldname = optid.substring(optid.search("_"));
			updwnkey_fld = fieldname;
		}

		$("#jqGrid2").keydown(function(e) {
	      switch (e.which) {
	        case 40: // down
	          var $grid = $(this);
	          var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
			  $("#"+selectedRowId+updwnkey_fld).focus();

	          e.preventDefault();
	          break;

	        case 38: // up
	          var $grid = $(this);
	          var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
			  $("#"+selectedRowId+updwnkey_fld).focus();

	          e.preventDefault();
	          break;

	        default:
	          return;
	      }
	    });


	$("#jqGrid2").jqGrid('setGroupHeaders', {
  	useColSpanStyle: false, 
	  groupHeaders:[
		{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
		//{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
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

		if(options.gid == "jqGrid"){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
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
				$( "#dialog_remarks").dialog("option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks").dialog("option", "buttons", butt1_rem);
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
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_deptcode.on();
			dialog_category.on();
			dialog_GSTCode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='AmtB4GST']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='amount']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyonhand']","#jqGrid2 input[name='quantity']"]);
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);

			$("input[name='amount']").keydown(function(e) {//when click tab at amount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});

		},
		aftersavefunc: function (rowid, response, options) {
			$('#db_amount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
		beforeSaveRow: function (options, rowid) {
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="quantity"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "/DebitNoteDetail/form?"+
				$.param({
					action: 'debitnote_detail_save',
					source: $('#db_source').val(),
					trantype: $('#db_trantype').val(),
					auditno: $('#db_auditno').val(),
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		 afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);
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
								action: 'debitnote_detail_save',
								source: $('#db_source').val(),
								trantype: $('#db_trantype').val(),
								auditno: $('#db_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post( "/DebitNoteDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, 
							function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#db_amount').val(data);
								$('#amount').val(data);
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
		    for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst"]);

		        Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_quantity"]);

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
				if(parseInt($('#'+ids[i]+"_quantity").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					return false;
				}

				// cust_rules()

		    	var obj = 
		    	{
                    'lineno_' : ids[i],
		    		'idno' : data.idno,
		    		'deptcode' : $("#jqGrid2 input#"+ids[i]+"_deptcode").val(),
		    		'category' : $("#jqGrid2 input#"+ids[i]+"_category").val(),
		    		'document' : $('#'+ids[i]+"_document").val(),
		    		'GSTCode' : $("#jqGrid2 input#"+ids[i]+"_GSTCode").val(),
		    		'AmtB4GST' : $('#'+ids[i]+"_AmtB4GST").val(),
		    		'tot_gst' : $('#'+ids[i]+"tot_gst").val(),
		    		'amount' : ('#'+ids[i]+"_amount").val(),
                    'unit' : $("#"+ids[i]+"_unit").val(),
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'debitnote_detail_save',
				_token: $("#_token").val(),
				auditno: $('#db_auditno').val(),
				source: $('#db_source').val(),
				trantype: $('#db_trantype').val()
    		}

    		$.post( "/DebitNoteDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				alert(dialog,data.responseText);
			}).done(function(data){
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
            case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
			case 'category':field=['catcode','description'];table="material.category";break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;

			case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
			case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('DebitNote',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}



	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
            case 'Department':temp=$('#deptcode');break;
			case 'Category':temp=$('#category');break;
			case 'GST Code':temp=$('#GSTCode');break;

			case 'Item Code': temp = $("#jqGrid2 input[name='chggroup']"); break;
			case 'UOM Code': temp = $("#jqGrid2 input[name='uom']"); break;
			case 'PO UOM': 
				temp = $("#jqGrid2 input[name='pouom']"); 
				var text = $( temp ).parent().siblings( ".help-block" ).text();
				if(text == 'Invalid Code'){
					return [false,"Please enter valid "+name+" value"];
				}

				break;
			case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']"); break;
			case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']"); break;
			case 'Quantity Request': temp = $("#jqGrid2 input[name='quantity']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];

	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>
			<span><input id="`+opt.id+`_discamt" name="discamt" type="hidden"></span>
			<span><input id="`+opt.id+`_rate" name="rate" type="hidden"></span>`);
	}
	function deptcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" id="deptcode" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}
    function categoryCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" id="category" name="category" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}
	function GSTCodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	

		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $(`<div class="input-group">
			<input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="GSTCode" type="text" class="form-control input-sm" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
		</div>
		<span class="help-block"></span>
		<div class="input-group">
			<input id="`+id_optid+`_gstpercent" name="gstpercent" type="hidden">
		</div>`);
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

		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			mycurrency.formatOn();
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_CustomerSO.on();
			//dialog_mrn.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_CustomerSO.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac//////////////////////////////////

	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		remove_error("#jqGrid2 #"+id_optid+"_pouom");
		remove_error("#jqGrid2 #"+id_optid+"_quantity");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_pouom");
		}, 500 );


		$(".noti").empty();

	}

	/////////////////////////////edit all//////////////////////////////////////////////////

	function onall_editfunc(){

		errorField.length=0;
		dialog_deptcode.on();
		dialog_category.on();
		dialog_GSTCode.on();
		dialog_uomcode.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
		
		$("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='tot_gst'],#jqGrid2 input[name='tot_gst']").on('blur',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);

		$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function cari_gstpercent(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		$("#jqGrid2 #"+id+"_gstpercent").val(data.rate);
	}

	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);
	function calculate_line_totgst_and_totamt(event) {

		console.log(event.data.currency)
		event.data.currency.forEach(function(element){
			element.formatOff();
		});

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let AmountB4GST = parseFloat($("#"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_gstpercent").val());

		var tot_gst = AmountB4GST * (gstpercent / 100);
		var amount = AmountB4GST + tot_gst;

		$("#"+id_optid+"tot_gst").val(tot_gst); //total tax

		$("#jqGrid2 #"+id_optid+"_tot_gst").val(tot_gst) //total gst amount
		$("#jqGrid2 #"+id_optid+"_amount").val(amount) //total amount

		event.data.currency.forEach(function(element){
			element.formatOn();
		});

		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

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
	var dialog_deptcode = new ordialog(
		'db_deptcode', 'sysdb.department', "#jqGrid2 input[name='deptcode']", errorField,
		{
			colModel: [
				{ label: 'SectorCode', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
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
					$('#category').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Units",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_deptcode.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','RC', 'Other', 'ACTIVE']
			},
			ondblClickRow: function () {
				$('#document').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#document').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Category",
			open: function(){
				dialog_category.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_category.urlParam.filterVal=['session.compcode','RC', 'Other', 'ACTIVE']
			}
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
				console.log(optid)
				let data=selrowData('#'+dialog_GSTCode.gridname);

				$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
				$(dialog_GSTCode.textfield).closest('td').next().has("input[type=text]").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#AmtB4GST').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCode.urlParam.filterVal=['session.compcode','ACTIVE', 'OUTPUT'];
			},
			close: function(){
				if($('#jqGridPager2SaveAll').css("display") == "none"){
					$(dialog_GSTCode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
				}
				
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCode.makedialog(false);

	var dialog_CustomerSO = new ordialog(
		'customer', 'debtor.debtormast', '#db_debtorcode', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#db_hdrtype').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#db_hdrtype').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerSO.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerSO.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog();

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
		sortname: 'db_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
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

});

function populate_form(obj){
	//panel header
	$('#DebitNo_show').text(obj.db_auditno);
	$('#CustName_show').text(obj.dm_name);
}

function empty_form(){
	$('#DebitNo_show').text('');
	$('#CustName_show').text('');
}

function reset_all_error(){

}

function get_billtype(){
	this.param={
		action:'get_value_default',
		url:"util/get_value_default",
		field: ['*'],
		filterCol:['compcode','billtype'],
		filterVal:['session.compcode',$("#formdata input[name='db_hdrtype']").val()],
		table_name:'hisdb.billtymst',
		table_id:'idno'
	}

	$.get( this.param.url+"?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				let data_ = data.rows[0];
				var rowids = $('#jqGrid2').jqGrid('getDataIDs');
				rowids.forEach(function(e,i){
					$('#jqGrid2').jqGrid('setRowData', e, {amtbilltype:data_.percent_,percbilltype:data_.amount});
				});

				$('#pricebilltype').val(data_.price);
				$("#jqGrid2 input[name='percbilltype']").val(data_.percent_);
				$("#jqGrid2 input[name='amtbilltype']").val(data_.amount);
			}
		});
}