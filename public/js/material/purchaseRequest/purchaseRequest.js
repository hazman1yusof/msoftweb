
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
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
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$("#purreqhd_reqdept").val($("#x").val());
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
					dialog_reqdept.check(errorField);
					dialog_prdept.check(errorField);
					dialog_suppcode.check(errorField);
				} if (oper != 'view') {
					dialog_reqdept.on();
					dialog_prdept.on();
					dialog_suppcode.on();
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
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.alert').detach();
				$("#formdata a").off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url:'/util/get_table_default',
		field:'',
		table_name: ['material.purreqhd', 'material.supplier'],
		table_id: 'purreqhd_idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['supplier.SuppCode'],
		join_onVal: ['purreqhd.suppcode'],
		filterCol: ['purreqhd.compcode'],
		filterVal: ['session.compcode'],
		fixPost: true,
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'purReq_header_save',
		url:'/purchaseRequest/form',
		field: '',
		oper: oper,
		table_name: 'material.purreqhd',
		table_id: 'purreqhd_recno',
		fixPost: true,
		//returnVal: true,
	};

	 function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
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
				//$('#ponodepan').text("");//tukar kat depan tu
				$('#prdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		//	$('#ponodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record No', name: 'purreqhd_recno', width: 10, canSearch: true, selected: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request Department', name: 'purreqhd_reqdept', width: 15, canSearch: true, classes: 'wrap' },
			{ label: 'Purchase Department', name: 'purreqhd_prdept', width: 15, classes: 'wrap' },
			{ label: 'Request No', name: 'purreqhd_purreqno', width: 10, canSearch: true },
			{ label: 'PO No', name: 'purreqhd_purordno', width: 10, formatter: padzero, unformat: unpadzero },
			{ label: 'Request Date', name: 'purreqhd_purreqdt', width: 15, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Supplier Code', name: 'purreqhd_suppcode', width: 15, canSearch: true },
			{ label: 'Supplier Name', name: 'supplier_name', width: 30, canSearch: true, classes: 'wrap' },
			{ label: 'Amount', name: 'purreqhd_totamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Remark', name: 'purreqhd_remarks', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Status', name: 'purreqhd_recstatus', width: 15 },
			{ label: 'PerDiscount', name: 'purreqhd_perdisc', width: 90, hidden: true },
			{ label: 'AmtDiscount', name: 'purreqhd_amtdisc', width: 90, hidden: true },
			{ laebl: 'Subamount', name: 'purreqhd_subamount', width: 90, hidden: true },
			// { label: 'authpersonid', name: 'authpersonid', width: 90, hidden: true },
			// { label: 'authdate', name: 'authdate', width: 40, hidden: 'true' },
			{ label: 'reqpersonid', name: 'purreqhd_reqpersonid', width: 50, hidden: true },
			{ label: 'adduser', name: 'purreqhd_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'purreqhd_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'purreqhd_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'purreqhd_upddate', width: 90, hidden: true },
			{ label: 'idno', name: 'purreqhd_idno', width: 90, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'purreqhd_idno',
		sortorder:'desc',
		width: 900,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			let stat = selrowData("#jqGrid").purreqhd_recstatus;
			switch ($("#scope").val()) {
				case "dataentry":
					break;
				case "cancel":
					if (stat == 'POSTED') {
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					} else if (stat == "CANCELLED") {
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					} else {
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
					break;
				case "all":
					if (stat == 'POSTED') {
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					} else if (stat == "CANCELLED") {
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					} else {
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
					}
					break;
			}
			urlParam2.filterVal[0] = selrowData("#jqGrid").purreqhd_recno;
			// urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
			// urlParam2.join_filterVal = [['skip.s.uomcode', "skip.'" + selrowData("#jqGrid").reqdept + "'", "skip.'" + moment(selrowData("#jqGrid").reqdt).year() + "'"], []];
			$('#recnodepan').text(selrowData("#jqGrid").purreqhd_recno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},

	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', { 'text-align': 'right' });

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
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');

			/*	urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
				urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
				urlParam2.join_filterVal = [['skip.s.uomcode', "skip.'" + selrowData("#jqGrid").reqdept + "'", "skip.'" + moment(selrowData("#jqGrid").reqdt).year() + "'"], []];
			*/
			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');

			/*	urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
				urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
				urlParam2.join_filterVal = [['skip.s.uomcode', selrowData("#jqGrid").reqdept, moment(selrowData("#jqGrid").reqdt).year()], []];
			*/
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
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['purreqhd_adduser', 'purreqhd_adddate', 'purreqhd_idno', 'supplier_name']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
	function hideatdialogForm(hide) {
		if (hide) {
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").hide();
			$("#saveDetailLabel").show();
		} else {
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").show();
			$("#saveDetailLabel").hide();
		}
	}

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function () {
		saveParam.oper = $(this).data("oper");
		let obj={recno:selrowData('#jqGrid').purreqhd_recno,_token:$('#_token').val()};
		$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			},'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			unsaved = false;
			hideatdialogForm(false);

			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}

			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#purreqhd_recno').val(data.recno);
				$('#purreqhd_purreqno').val(data.purreqno);
				$('#purreqhd_idno').val(data.idno);//just save idno for edit later

				urlParam2.filterVal[0] = data.recno;
				/*urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
				urlParam2.join_filterVal = [['skip.s.uomcode', $('#reqdept').val(), moment($("#reqdt").val()).year()], []];*/
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
			}
			disableForm('#formdata');
		/*	hideatdialogForm(false);

		}, 'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?;*/
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
	///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	trandept();
	function trandept(){
		var param={
			action:'get_value_default',
			url: '/util/get_value_default',
			field:['deptcode'],
			table_name:'sysdb.department',
			filterCol:['storedept'],
			filterVal:['1']
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$.each(data.rows, function(index, value ) {
					if(value.deptcode.toUpperCase()== $("#deptcode").val().toUpperCase()){
						$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
					}else{
						$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
					}
				});
			}
		});
	}
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
			ondblClickRow: function () {
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["purreqhd_suppcode"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	function searchChange() {
		var arrtemp = ['session.compcode', $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b == 'All') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purreqhd.compcode', 'purreqhd.recstatus', 'purreqhd.prdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
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
		action: 'get_table_default',
		field: ['prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax', 'prdt.amount', 'NULL AS remarks_button', 'prdt.remarks', 'prdt.recstatus'],
		table_name: ['material.purreqdt prdt', 'material.productmaster p'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN', 'LEFT JOIN'],
		join_onCol: ['prdt.itemcode'],
		join_onVal: ['p.itemcode'],
		filterCol: ['prdt.recno', 'prdt.compcode', 'prdt.recstatus'],
		filterVal: ['', 'session.company', '<>.DELETE']
	};
	var addmore_jqgrid2={more:false,state:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/purchaseRequestDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable: true, hidden: true },
			{
				label: 'Price Code', name: 'pricecode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pricecodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Item Code', name: 'itemcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 180, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			{
				label: 'Uom Code', name: 'uomcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'PO UOM', name: 'pouom', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
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
				label: 'Unit Price', name: 'unitprice', width: 80, classes: 'wrap', align: 'right',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules: { required: true }
			},
			{
				label: 'Tax Code', name: 'taxcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
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
				editrules: { required: true },
			},
			{
				label: 'Total Line Amount', name: 'amount', width: 80, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{ label: 'Remarks', name: 'remarks_button', width: 80, formatter: formatterRemarks, unformat: unformatRemarks },
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
		],
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		/*gridComplete: function () {

			$("#jqGrid2_ilcancel").off();
			$("#jqGrid2_ilcancel").on("click", function (event) {
				event.preventDefault();
				event.stopPropagation();
				bootbox.confirm({
					message: "Are you sure want to cancel?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success' },
						cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							$(".noti").empty();
							refreshGrid("#jqGrid2", urlParam2);
						}
						linenotoedit = null;
					}
				});
			});

			$("#jqGrid2").find(".remarks_button").on("click", function (e) {
				$("#remarks2").data('lineno', $(this).data('lineno'));
				$("#remarks2").data('grid', "#jqGrid2");
				$("#dialog_remarks").dialog("open");
			});
		},*/
		gridComplete: function(){
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			//calculate_quantity_outstanding('#jqGrid2');
		},
		afterShowForm: function (rowid) {
			$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_itemcode.check(errorField);
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);
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
		oneditfunc: function (rowid) {
			linenotoedit = rowid;
			$("#jqGrid2").find(".remarks_button[data-lineno!='" + linenotoedit + "']").prop("disabled", true);
			$("#jqGrid2").find(".remarks_button[data-lineno='undefined']").prop("disabled", false);
		},
		aftersavefunc: function (rowid, response, options) {
			$('#purreqhd_totamount').val(response.responseText);
			$('#purreqhd_subamount').val(response.responseText);
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid2', urlParam2, 'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			mycurrency2.formatOff();
			let editurl = "../../../../assets/php/entry.php?" +
				$.param({
					action: 'purReq_detail_save',
					//docno:$('#delordhd_docno').val(),
					recno: $('#purreqhd_recno').val(),
					reqdept: $('#purreqhd_reqdept').val(),
					purreqno: $('#purreqhd_purreqno').val(),
					remarks: selrowData('#jqGrid2').remarks//bug will happen later because we use selected row

				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
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
								action: 'purReq_detail_save',
								recno: $('#purreqhd_recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post("../../../../assets/php/entry.php?" + $.param(param), { oper: 'del' }, function (data) {
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#amount').val(data);
								refreshGrid("#jqGrid2", urlParam2);
							});
						}
					}
				});
			}
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
	function showdetail(cellvalue, options, rowObject) {
		var field, table;
		switch (options.colModel.name) {
			//case 'itemcode':field=['itemcode','description'];table="material.product";break;
			case 'uomcode': field = ['uomcode', 'description']; table = "material.uom"; break;
			case 'pricecode': field = ['pricecode', 'description']; table = "material.pricesource"; break;
			case 'taxcode': field = ['taxcode', 'description']; table = "hisdb.taxmast"; break;
		}
		var param = { action: 'input_check', table: table, field: field, value: cellvalue };
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.row)) {
				$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").append("<span class='help-block'>" + data.row.description + "</span>");
			}
		});
		return cellvalue;
	}



	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp;
		switch (name) {
			case 'Item Code': temp = $('#itemcode'); break;
			case 'Uom Code': temp = $('#uomcode'); break;
			case 'Price Code': temp = $('#pricecode'); break;
			case 'Tax Code': temp = $('#taxcode'); break;
		}
		return (temp.parent().hasClass("has-error")) ? [false, "Please enter valid " + name + " value"] : [true, ''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function pricecodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function itemcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val; //kalu takde desc kat bawah buang slice
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function uomcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function pouomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="pouom" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function taxcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="taxcode" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
	}

	function galGridCustomValue(elem, operation, value) {
		if (operation == 'get') {
			return $(elem).find("input").val();
		}
		else if (operation == 'set') {
			$('input', elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_reqdept.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
			saveHeader("#formdata", oper, saveParam);
		} else {
			mycurrency.formatOn();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////

	var mycurrency2 = new currencymode(["#jqGrid2 input[name='amtdisc']", "#jqGrid2 input[name='unitprice']", "#jqGrid2 input[name='amount']", "#jqGrid2 input[name='tot_gst']"]);

	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_reqdept.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function () {
		unsaved = false;
		$("#jqGridPager2Delete").hide();
		dialog_pricecode.on();
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_taxcode.on();
		dialog_pouom.on();
		$("input[name='gstpercent']").val('0')//reset gst to 0


		mycurrency2.formatOnBlur();

		$("#jqGrid2 input[name='qtyrequest']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='unitprice']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amtdisc']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='taxcode']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='perdisc']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);

		$("input[name='tot_gst']").keydown(function (e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9') $('#jqGrid2_ilsave').click();
		});

		/*	$("input[id*='_amount']").keydown(function (e) {
				var code = e.keyCode || e.which;
				if (code == '9') $('#jqGrid2_ilsave').click();
		});*/
	});

	// function calculate_line_totgst_and_totamt(event) {
	// 	let qtyrequest = parseFloat($("#jqGrid2 input[name='qtyrequest']").val());
	// 	let unitprice = parseFloat($("#jqGrid2 input[name='unitprice']").val());
	// 	let amtdisc = parseFloat($("#jqGrid2 input[name='amtdisc']").val());
	// 	let gstpercent = parseFloat($("input[name='gstpercent']").val());
	// 	let perdisc = parseFloat($("#jqGrid2 input[name='perdisc']").val());

	// 	var amount = ((unitprice * qtyrequest) - (amtdisc * qtyrequest)) - ((100 - perdisc) / 100);
	// 	var tot_gst = amount * (gstpercent / 100);
	// 	var totalAmount = amount + tot_gst;

	// 	console.log(unitprice * perdisc / 100);
	// 	// var netunitprice = (unitprice - amtdisc) + ((unitprice - amtdisc) * gstpercent / 100);//?

	// 	$("#jqGrid2 input[name='tot_gst']").val(tot_gst);
	// 	$("#jqGrid2 input[name='amount']").val(totalAmount);
	// 	event.data.currency.formatOn();//change format to currency on each calculation
	// }

	function calculate_convfactor(event){
	}

	function calculate_line_totgst_and_totamt(event) {


		let convfactor_uom = parseFloat($("#convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#convfactor_pouom").val());

		let qtyrequest = parseFloat($("#jqGrid2 input[name='qtyrequest']").val());
		let unitprice = parseFloat($("#jqGrid2 input[name='unitprice']").val());
		let amtdisc = parseFloat($("#jqGrid2 input[name='amtdisc']").val());
		let gstpercent = parseFloat($("input[name='gstpercent']").val());
		let perdisc = parseFloat($("#jqGrid2 input[name='perdisc']").val());

		var realqty = convfactor_pouom*qtyrequest/convfactor_uom;

		var amount = ((unitprice * realqty) - (amtdisc * realqty));
		var getDis = amount - (amount * perdisc / 100);
		var tot_gst = getDis * (gstpercent / 100);
		var totalAmount = getDis + tot_gst;

		// var tot_gst = gstpercent / 100 * (unitprice - amtdisc) * qtyorder;
		// var amount = ((unitprice * perdisc / 100 - amtdisc) * qtyorder) + tot_gst;

		//var netunitprice = (unitprice - amtdisc) + ((unitprice - amtdisc) * gstpercent / 100);//?

		$("input[name='tot_gst']").val(tot_gst);
		$("input[name='amount']").val(totalAmount);
		event.data.currency.formatOn();//change format to currency on each calculation

	}

	// function calculate_line_totgst_and_totamt(event) {
	// 	let qtyrequest = parseFloat($("#jqGrid2 input[name='qtyrequest']").val());
	// 	let unitprice = parseFloat($("#jqGrid2 input[name='unitprice']").val());
	// 	let amtdisc = parseFloat($("#jqGrid2 input[name='amtdisc']").val());
	// 	let gstpercent = parseFloat($("input[name='gstpercent']").val());
	// 	let perdisc = parseFloat($("input[name='perdisc']").val());

	// 	var amount = ((unitprice * qtyrequest) - (amtdisc * qtyrequest)) - ((100 - perdisc) / 100);
	// 	var tot_gst = amount * (gstpercent / 100);
	// 	var totalAmount = amount + tot_gst;

	// 	$("input[name='tot_gst']").val(tot_gst);
	// 	$("input[name='amount']").val(totalAmount);
	// 	event.data.currency.formatOn();//change format to currency on each calculation

	// }

	// ///////////////////////////////////////// QtyOnHand Dept ////////////////////////////////////////////
	// function getQOHReqDept(from_selecting_uomcode) {
	// 	var reqdept = $('#reqdept').val();
	// 	var datetrandate = new Date($('#reqdt').val());
	// 	var getyearinput = datetrandate.getFullYear();

	// 	var param = {
	// 		func: 'getQOHReqDept',
	// 		action: 'get_value_default',
	// 		field: ['qtyonhand', 'maxqty'],
	// 		table_name: 'material.stockloc'
	// 	}

	// 	param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode',];
	// 	param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), reqdept, $("#jqGrid2 input[name='uomcode']").val()];

	// 	$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {
	// 		$("#jqGrid2 input[name='deptqtyonhand'],#jqGrid2 input[name='maxqty']").val('');
	// 	}, 'json').done(function (data) {
	// 		if (!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand != null && data.rows[0].maxqty != null) {
	// 			$("#jqGrid2 input[name='deptqtyonhand']").val(data.rows[0].qtyonhand);
	// 			$("#jqGrid2 input[name='maxqty']").val(data.rows[0].maxqty);
	// 			if (from_selecting_uomcode) getQOHprdept();
	// 		} else {
	// 			bootbox.confirm({
	// 				message: "No stock location at department code: " + $('#reqdept').val() + "... Proceed? ",
	// 				buttons: {
	// 					confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
	// 				},
	// 				callback: function (result) {
	// 					if (!result) {
	// 						$("#jqGrid2_ilcancel").click();
	// 					} else {
	// 						if (from_selecting_uomcode) getQOHprdept();
	// 					}
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	// ///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
	// function getQOHprdept() {
	// 	var prdept = $('#prdept').val();
	// 	var datetrandate = new Date($('#purreqdt').val());
	// 	var getyearinput = datetrandate.getFullYear();

	// 	var param = {
	// 		func: 'getQOHprdept',
	// 		action: 'get_value_default',
	// 		field: ['qtyonhand'],
	// 		table_name: 'material.stockloc'
	// 	}

	// 	param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
	// 	param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), prdept, $("#jqGrid2 input[name='uomcode']").val()];

	// 	$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {
	// 		$("#jqGrid2 input[name='recvqtyonhand']").val('');
	// 	}, 'json').done(function (data) {
	// 		if (!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand != null) {
	// 			$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
	// 		} else {
	// 			bootbox.confirm({
	// 				message: "No stock location at department code: " + $('#prdept').val() + "... Proceed? ",
	// 				buttons: {
	// 					confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
	// 				},
	// 				callback: function (result) {
	// 					if (!result) {
	// 						$("#jqGrid2_ilcancel").click();
	// 					} else {

	// 					}
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam', 'colModel'),
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
		gridComplete: function () {
			$("#jqGrid3").find(".remarks_button").on("click", function (e) {
				$("#remarks2").data('lineno', $(this).data('lineno'));
				$("#remarks2").data('grid', "#jqGrid3");
				$("#dialog_remarks").dialog("open");
			});
		},
	});
	jqgrid_label_align_right("#jqGrid3");


	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#purreqhd_reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Department",
		}
	);
	dialog_reqdept.makedialog();

	var dialog_prdept = new ordialog(
		'prdept', 'sysdb.department', '#purreqhd_prdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Made To Department",
			open: function () {
				dialog_prdept.urlParam.filterCol = ['purdept'];
				dialog_prdept.urlParam.filterVal = ['1'];
			}
		}, 'urlParam'
	);
	dialog_prdept.makedialog();

	var dialog_suppcode = new ordialog(
		'suppcode', 'material.supplier', '#purreqhd_suppcode', errorField,
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}, 'urlParam'
	);
	dialog_suppcode.makedialog();

	var dialog_pricecode = new ordialog(
		'pricecode', ['material.pricesource'], "#jqGrid2 input[name='pricecode']", errorField,
		{
			colModel:
			[
				{ label: 'Price code', name: 'pricecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Price Code For Item",
			open: function () {
				dialog_pricecode.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pricecode.urlParam.filterVal = ['session.company', 'A'];
			},
			close: function () {
				$(dialog_pricecode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_pricecode.makedialog(false);

	var dialog_itemcode = new ordialog(
		'itemcode', ['material.stockloc s', 'material.product p', 'hisdb.taxmast t', 'material.uom u'], "#jqGrid2 input[name='itemcode']", errorField,
		{
			colModel:
			[
				{ label: 'Item Code', name: 's.itemcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'p.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Quantity On Hand', name: 's.qtyonhand', width: 100, classes: 'pointer', },
				{ label: 'UOM Code', name: 's.uomcode', width: 100, classes: 'pointer' },
				// { label: 'Max Quantity', name: 's.maxqty', width: 100, classes: 'pointer' },
				{ label: 'Tax Code', name: 'p.TaxCode', width: 100, classes: 'pointer' },
				{ label: 'rate', name: 't.rate', width: 100, classes: 'pointer' },
				{ label: 'Conversion', name: 'u.convfactor', width: 50, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p.description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#jqGrid2 input[name='taxcode']").val(data['p.TaxCode']);
				$("#jqGrid2 input[name='taxcode']").focus();
				$('#gstpercent').val(data['t.rate']);
				$("#convfactor_uom").val(data['u.convfactor']);
			}
		}, {
			title: "Select Item For Purchase Request",
			open: function () {
				dialog_itemcode.urlParam.table_id = "none_";
				dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode'];
				dialog_itemcode.urlParam.filterVal = ['session.company', moment($('#purreqhd_purdate').val()).year(), $('#purreqhd_reqdept').val()];
				dialog_itemcode.urlParam.join_type = ['LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol = ['s.itemcode', 'p.taxcode', 's.uomcode'];
				dialog_itemcode.urlParam.join_onVal = ['p.itemcode', 't.taxcode', 'u.uomcode'];
				dialog_itemcode.urlParam.join_filterCol = [['s.compcode', 's.uomcode'], []];
				dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode', 'skip.p.uomcode'], []];
			}
		}, 'urlParam'
	);
	dialog_itemcode.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom', ['material.stockloc s', 'material.uom u'], "#jqGrid2 input[name='uomcode']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 's.uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'u.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Department code', name: 's.deptcode', width: 150, classes: 'pointer' },
				{ label: 'Item code', name: 's.itemcode', width: 150, classes: 'pointer' },
				{ label: 'Conversion', name: 'u.convfactor', width: 50, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_uomcode.gridname);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#convfactor_uom").val(data['u.convfactor']);
			}

		}, {
			title: "Select UOM Code For Item",
			open: function () {
				dialog_uomcode.urlParam.table_id = "none_";
				dialog_uomcode.urlParam.filterCol = ['s.compcode', 's.deptcode', 's.itemcode', 's.year'];
				dialog_uomcode.urlParam.filterVal = ['session.company', $('#purreqhd_reqdept').val(), $("#jqGrid2 input[name='itemcode']").val(), moment($('#purreqhd_purdate').val()).year()];
				dialog_uomcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol = ['s.uomcode'];
				dialog_uomcode.urlParam.join_onVal = ['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol = [['s.compcode']];
				dialog_uomcode.urlParam.join_filterVal = [['skip.u.compcode']];
			},
			close: function () {
				$(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_uomcode.makedialog(false);

	var dialog_pouom = new ordialog(
		'pouom', ['material.uom'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 50, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_pouom.gridname);
				$("#jqGrid2 input[name='pouom']").val(data['uomcode']);
				$("#convfactor_pouom").val(data['convfactor']);
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.company', 'A'];
			},
			close: function () {
				$(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_pouom.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode', ['hisdb.taxmast'], "#jqGrid2 input[name='taxcode']", errorField,
		{
			colModel:
			[
				{ label: 'Tax code', name: 'taxcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'rate', name: 'rate', width: 400, classes: 'pointer', hidden: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_taxcode.gridname);
				$('#gstpercent').val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			}
		}, {
			title: "Select Tax Code For Item",
			open: function () {
				dialog_taxcode.urlParam.filterCol = ['taxtype', 'compcode', 'recstatus'];
				dialog_taxcode.urlParam.filterVal = ['input', 'session.company', 'A'];
			},
			close: function () {
				$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_taxcode.makedialog(false);

});