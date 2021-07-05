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
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$("#reqdept").val($("#deptcode").val());
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
					dialog_reqtodept.check(errorField);
				} if (oper != 'view') {
					dialog_reqdept.on();
					dialog_reqtodept.on();
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
				dialog_reqdept.off();
				dialog_reqtodept.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////

	var recstatus_filter = [['OPEN','POSTED']];
		if($("#recstatus_use").val() == 'POSTED'){
			recstatus_filter = [['OPEN','POSTED']];
			filterCol_urlParam = ['ivreqhd.compcode'];
			filterVal_urlParam = ['session.compcode'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","recstatus", recstatus_filter[0][0]);

	var urlParam = {
		action: 'get_table_default',
		url:'/util/get_table_default',
		field:'',
		table_name: ['material.ivreqhd'],
		table_id: 'idno',
		filterCol: ['reqtodept'],
		filterVal: [$('#deptcode').val()],
		// WhereInCol:['ivreqhd.recstatus'],
		// WhereInVal: recstatus_filter,
		//fixPost: true,
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'invReq_header_save',
		url:'/inventoryRequest/form',
		field: '',
		oper: oper,
		table_name: 'material.ivreqhd',
		table_id: 'recno',
		fixPost: true,
		//returnVal: true,
	};

	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		if(cellvalue == null){
			return '';
		}else{
			return pad(str, cellvalue, true);
		}
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
			{ label: 'Record No', name: 'recno', width: 20, canSearch: true, selected: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request Department', name: 'reqdept', width: 30, canSearch: true },
			{ label: 'Request No', name: 'ivreqno', width: 25, canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request To Department', name: 'reqtodept', width: 30, classes: 'wrap' },
			{ label: 'Request Date', name: 'reqdt', width: 20, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Amount', name: 'amount', width: 20, align: 'right', formatter: 'currency' },
			{ label: 'Recstatus', name: 'recstatus', width: 20},
			//{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'Remarks', name: 'remarks', width: 50, classes: 'wrap', hidden:true},
			{ label: 'Request Type', name: 'reqtype', width: 50, hidden:true},
			{ label: 'authpersonid', name: 'authpersonid', width: 90, hidden: true },
			{ label: 'authdate', name: 'authdate', width: 40, hidden: 'true' },
			{ label: 'reqpersonid', name: 'reqpersonid', width: 50, hidden: 'true' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'idno', name: 'idno', width: 90, hidden: true },
			

			// { label: 'requestby', name: 'requestby', width: 90, hidden: true },
			// { label: 'requestdate', name: 'requestdate', width: 90, hidden: true },
			// { label: 'supportby', name: 'supportby', width: 90, hidden: true },
			// { label: 'supportdate', name: 'supportdate', width: 40, hidden: true},
			// { label: 'verifiedby', name: 'verifiedby', width: 90, hidden: true },
			// { label: 'verifieddate', name: 'verifieddate', width: 90, hidden: true },
			// { label: 'approvedby', name: 'approvedby', width: 90, hidden: true },
			// { label: 'approveddate', name: 'approveddate', width: 40, hidden: true},

			{ label: 'reopenby', name: 'reopenby', width: 40, hidden: true},
			{ label: 'reopendate', name: 'reopendate', width: 40, hidden:true},
			{ label: 'cancelby', name: 'cancelby', width: 40, hidden:true},
			{ label: 'canceldate', name: 'canceldate', width: 40, hidden:true},
			{ label: 'unit', name: 'unit', width: 40, hidden:true},
			
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			let scope = $("#recstatus_use").val();

			// $('#but_post_single_jq,#but_cancel_jq,#but_soft_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (stat == scope ) {
				$('#but_cancel_jq').show();
			//} else if ( stat == "OPEN" ){
				//$('#but_soft_cancel_jq').show();
			} else if ( stat == "CANCELLED" ){
				$('#but_reopen_jq').show();
			} else {
				if(scope.toUpperCase() == 'ALL'){
					// $('#but_post_jq').show();
					// if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0 && stat=='OPEN'){
					// 	$('#but_post_single_jq').show();
					// }else if(stat=='OPEN'){
					// 	$('#but_post_jq').show();
					// }
				}else{
					// if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0){
					// 	$('#but_post_single_jq').show();
					// }else{
					// 	$('#but_post_jq').show();
					// }
				}
			}
			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			
			$('#reqnodepan').text(selrowData("#jqGrid").ivreqno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));

			$("#pdfgen1").attr('href','./inventoryRequest/showpdf?recno='+selrowData("#jqGrid").recno);

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			//cbselect.show_hide_table();
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
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['recno','ivreqno','adduser', 'adddate', 'idno', 'upduser','upddate','deluser', 'recstatus','unit','Checkbox','queuepr_AuthorisedID']);

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

	///////////////////////////////// reqdt check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#reqdt"]);
	actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	// $("#but_cancel_jq,#but_reopen_jq,#but_soft_cancel_jq").click(function(){
		
	// 	saveParam.oper = $(this).data("oper");
	// 	let obj={
	// 		recno:selrowData('#jqGrid').recno,
	// 		_token:$('#_token').val(),
	// 		idno:selrowData('#jqGrid').idno

	// 	};
	// 	$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
	// 		refreshGrid("#jqGrid", urlParam);
	// 	}).fail(function (data) {
	// 		// alert(data.responseText);
	// 		$('#error_infront').text(data.responseText);
	// 	}).done(function (data) {
	// 		//2nd successs?
	// 	});
	// });
	// $('#jqGrid2_ilcancel').click(function(){
	// 	$(".noti").empty();
	// });

	// $("#but_post_jq,#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){
	// 	$(this).attr('disabled',true);
	// 	var self_ = this;
	// 	var idno_array = [];
	
	// 	idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
	// 	var obj={};
	// 	obj.idno_array = idno_array;
	// 	obj.oper = $(this).data('oper');
	// 	obj._token = $('#_token').val();
		
	// 	$.post( '/inventoryRequest/form', obj , function( data ) {
	// 		refreshGrid('#jqGrid', urlParam);
	// 		$(self_).attr('disabled',false);
	// 		cbselect.empty_sel_tbl();
	// 	}).fail(function(data) {
	// 		$('#error_infront').text(data.responseText);
	// 		$(self_).attr('disabled',false);
	// 	}).success(function(data){
	// 		$(self_).attr('disabled',false);
	// 	});
	// });

	$("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

		var idno = selrowData('#jqGrid').idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( '/inventoryRequest/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});


	$("#but_post_jq").click(function(){
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( '/inventoryRequest/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
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
				$('#recno').val(data.recno);
				$('#ivreqno').val(data.ivreqno);
				$('#idno').val(data.idno);//just save idno for edit later
				$('#amount').val(data.amount);

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
	///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	trandept();
	function trandept(){
		var param={
			action:'get_value_default',
			url: '/util/get_value_default',
			field:['deptcode'],
			table_name:'sysdb.department',
			filterCol:['purdept'],
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
		if ($('#Scol').val() == 'reqdt') {
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

				urlParam.searchCol=["suppcode"];
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
		var arrtemp = ['session.compcode', $('#Status option:selected').val(), $('#trandept option:selected').val()];

		var filter = arrtemp.reduce(function (a, b, c) {
			if (b.toUpperCase() == 'ALL') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['ivreqhd.compcode', 'ivreqhd.recstatus', 'ivreqhd.reqdept'], fv: [], fc: [] });

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
		url:'/inventoryRequestDetail/table',
		field: ['ivdt.compcode', 'ivdt.recno', 'ivdt.lineno_', 'ivdt.itemcode', 'p.description', 'ivdt.uomcode', 'ivdt.pouom',
		's.maxqty', 's.qtyonhand', 'ivdt.qtyrequest', 'ivdt.qtytxn', 'ivdt.qohconfirm',
		'ivdt.recstatus'],
		table_name: ['material.ivreqdt AS ivdt ', 'material.stockloc AS s', 'material.productmaster AS p'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN', 'LEFT JOIN'],
		join_onCol: ['ivdt.itemcode', 'ivdt.itemcode'],
		join_onVal: ['s.itemcode', 'p.itemcode'],
		filterCol: ['ivdt.recno', 'ivdt.compcode','ivdt.recstatus'],
		filterVal: ['', 'session.company','<>.DELETE']
	};
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/inventoryRequestDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 70, classes: 'wrap', editable: true, hidden: true },
			{
				label: 'Item Code', name: 'itemcode', width: 230, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 350, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			{
				label: 'Uom Code ReqDept', name: 'uomcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
		
			{
				label: 'Uom Code ReqMadeTo', name: 'pouom', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req Dept', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req To Dept', name: 'qohconfirm', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
		
			{
				label: 'Qty Requested', name: 'qtyrequest', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true, custom: true, custom_func: cust_rules }, edittype: "text",
				editoptions: {
					maxlength: 11,
					dataInit: function (element) {
						element.style.textAlign = 'right';
						$(element).keypress(function (e) {
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
								return false;
							}
						});
					},
				},

			},
			{
				label: 'Qty Supplied', name: 'qtytxn', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Type', name: 'recstatus', width: 100, classes: 'wrap', hidden: false, editable: true,
				editoptions: { readonly: "readonly" },
			},
			{ label: 'Remarks', name: 'remarks_button', width: 140, formatter: formatterRemarks, unformat: unformatRemarks, hidden: true },
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden: true },

		],
		autowidth: false,
		shrinkToFit: false,
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
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			setjqgridHeight(data,'jqGrid2');
			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		
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
			//$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_reqdept.check(errorField);
			dialog_reqtodept.check(errorField);
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

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;

		if(options.gid == "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
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
			errorField.length=0;
			console.log(errorField)
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

				dialog_itemcode.on();
				dialog_uomcodereqdept.on();
				dialog_uomcodereqto.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyrequest']", "#jqGrid2 input[name='qtytxn']", "#jqGrid2 input[name='qohconfirm']", "#jqGrid2 input[name='qtyonhand']"]);
			
			$("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			// $("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			// $("#jqGrid2 input[name='qtyrequest']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtyrequest']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='totamount']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});

        	// cari_gstpercent($("#jqGrid2 input[name='taxcode']").val());
		},
		aftersavefunc: function (rowid, response, options) {
			$('#totamount').val(response.responseText);
			$('#subamount').val(response.responseText);
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
			console.log(errorField)
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "/inventoryRequestDetail/form?"+
				$.param({
					action: 'invReqDetail_save',
					recno: $('#recno').val(),
					reqdept: $('#reqdept').val(),
					ivreqno: $('#ivreqno').val(),
					remarks:data.remarks,
					amount:data.amount,
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
								action: 'invReqDetail_save',
								recno: $('#recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post( "/inventoryRequestDetail/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
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

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);

		        Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_qtyrequest"]);

		        // dialog_itemcode.id_optid = ids[i];
		        // dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,function(self){
		        // 	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
		        // });

		        dialog_itemcode.id_optid = ids[i];
		        dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				    }
			    );

		        dialog_uomcode.id_optid = ids[i];
		        dialog_uomcode.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );

				dialog_taxcode.id_optid = ids[i];
		        dialog_taxcode.check(errorField,ids[i]+"_taxcode","jqGrid2",null,undefined,function(self,data){
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
				console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					return false;
				}

				// cust_rules()

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'pouom' : $("#jqGrid2 input#"+ids[i]+"_pouom").val(),
		    		'qtyrequest' : $('#'+ids[i]+"_qtyrequest").val(),
                    'amount' : data.amount,
                    'remarks' : data.remarks,
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invReqDetail_save',
				_token: $("#_token").val(),
				recno: $('#recno').val(),
				action: 'invReqDetail_save',
				ivreqno:$('#ivreqno').val(),
				reqdt:$('#reqdt').val(),
				reqdept:$('#reqdept').val(),
    		}

    		$.post( "/inventoryRequestDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				alert(dialog,data.responseText);
			}).done(function(data){
				$('#totamount').val(data);
				$('#subamount').val(data);
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
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('inventoryRequest',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
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
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group">
					<input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
				</div>
				<span class="help-block"></span>
				<div class="input-group">
					<input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden">
					<input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`>
					<input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`>
				</div>

			`);
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
		unsaved = false;
		dialog_reqdept.off();
		dialog_reqtodept.off();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			mycurrency.formatOn();
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_reqdept.on();
			dialog_reqtodept.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_reqdept.on();
		dialog_reqtodept.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac/////////////////////////////////
		function calculate_conversion_factor(event) {
			var optid = event.currentTarget.id;
			var id_optid = optid.substring(0,optid.search("_"));

			var id="#jqGrid2 #"+id_optid+"_qtyonhand";
			var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
			var name = "calculate_conversion_factor";

			let convfactor_bool = false;
			let convfactor_uomcodereqdept  = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodereqdept").val());
			let convfactor_uomcodereqto = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodereqto").val());
			let qtyonhand = parseFloat($("#jqGrid2 #"+id_optid+"_qtyonhand").val());

			var balconv = convfactor_uomcodereqdept*qtyonhand%convfactor_uomcodereqto;
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
		//start binding event on jqgrid2
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_pouom.on();
		dialog_taxcode.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
		
		// $("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

		// $("#jqGrid2 input[name='qtyrequest']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt);

		$("#jqGrid2 input[name='qtyrequest']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	// var mycurrency2 =new currencymode([]);
	// var mycurrency_np =new currencymode([],true);
	// function calculate_line_totgst_and_totamt(event) {

	// 	mycurrency2.formatOff();
	// 	mycurrency_np.formatOff();

	// 	var optid = event.currentTarget.id;
	// 	var id_optid = optid.substring(0,optid.search("_"));
       
	// 	let qtyrequest = parseFloat($("#"+id_optid+"_qtyrequest").val());
	// 	let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
	// 	let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
	// 	let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
	// 	let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());
	// 	if($("#jqGrid2 input#"+id_optid+"_taxcode").val() == ''){
	// 		gstpercent = 0;
	// 	}

	// 	var totamtperUnit = ((unitprice*qtyrequest) - (amtdisc*qtyrequest));
	// 	var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
	// 	var tot_gst = amount * (gstpercent / 100);
	// 	var totalAmount = amount + tot_gst;

	// 	var netunitprice = (unitprice-amtdisc);//?
		
	// 	$("#"+id_optid+"_tot_gst").val(tot_gst);
	// 	$("#"+id_optid+"_totamount").val(totalAmount);

	// 	$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
	// 	$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});
		
	// 	var id="#jqGrid2 #"+id_optid+"_qtyrequest";
	// 	var fail_msg = "Quantity Request must be greater than 0";
	// 	var name = "quantityrequest";
	// 	if (qtyrequest > 0) {
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

	// 	event.data.currency.formatOn();//change format to currency on each calculation
	// 	mycurrency_np.formatOn();

	// 	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	// }


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
		'reqdept', 'sysdb.department', '#reqdept', errorField,
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
				$('#reqtodept').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#reqtodept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Request Department",
			open: function(){
				dialog_reqdept.urlParam.filterCol=['recstatus', 'compcode', 'sector'];
				dialog_reqdept.urlParam.filterVal=['ACTIVE', 'session.compcode', 'session.unit'];
			}
		},'urlParam','radio','tab'
	);
	dialog_reqdept.makedialog();

	var dialog_reqtodept = new ordialog(
		'reqtodept','sysdb.department','#reqtodept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true, checked:true, or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['purdept', 'recstatus', 'compcode', 'sector'],
				filterVal:['1', 'ACTIVE','session.compcode','session.unit']
			},
			ondblClickRow: function () {
				$('#remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#remarks').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Request Made To Department",
			open: function(){
				dialog_reqtodept.urlParam.filterCol=['recstatus', 'compcode', 'sector'];
				dialog_reqtodept.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
			}
		},'urlParam','radio','tab'
	);
	dialog_reqtodept.makedialog(false);

	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p', 'material.uom AS u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label:'Item Code',name:'s_itemcode',width:150,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'p_description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Quantity On Hand',name:'s_qtyonhand',width:100,classes:'pointer'},
				{label:'UOM Code',name:'s_uomcode',width:100,classes:'pointer'},
				{label:'Max Quantity',name:'s_maxqty',width:100,classes:'pointer'},
				{label:'Average Cost', name: 'p_avgcost', width: 100, classes: 'pointer', hidden:false },
				{label:'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
			],
			urlParam: {
				filterCol:['s.compcode','s.year','s.deptcode'],
				filterVal:['session.compcode', moment($('#reqdt').val()).year(),$('#reqdept').val()]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode.gridname);
				//$("#jqGrid2 input[name='itemcode']").val(data['s_itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p_description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s_uomcode']);
				$("#jqGrid2 input[name='maxqty']").val(data['s_maxqty']);
				$("#jqGrid2 input[name='netprice']").val(data['p_avgcost']);
				$("#jqGrid2 input[name='convfactoruomcodetrdept']").val(data['u_convfactor']);
				$("#jqGrid2 input[name='qtyonhand']").val(data['s_qtyonhand']);
				
				// getQOHtxndept();
				// checkQOH();
				
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
			title:"Select Item For Stock Request",
			open:function(){
				dialog_itemcode.urlParam.fixPost="true";
				dialog_itemcode.urlParam.table_id="none_";
				dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode'];
				dialog_itemcode.urlParam.filterVal=['session.compcode',moment($('#reqdt').val()).year(),$('#reqdept').val()];
				dialog_itemcode.urlParam.join_type=['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol=['s.itemcode','u.uomcode'];
				dialog_itemcode.urlParam.join_onVal=['p.itemcode', 's.uomcode'];
				dialog_itemcode.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on ='], []];
				dialog_itemcode.urlParam.join_filterVal=[['p.compcode','p.uomcode'], []];
			}
		},'urlParam','radio','tab'
	);
	dialog_itemcode.makedialog(false);
	//false means not binding event on jqgrid2 yet, after jqgrid2 add, event will be bind

	var dialog_uomcodereqdept = new ordialog(
		'uomcode',['material.stockloc AS s','material.product AS p','material.uom AS u'],"#jqGrid2 input[name='uomcode']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'s_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Department code',name:'s_deptcode',width:80,classes:'pointer'},
				{label:'Item code',name:'s_itemcode',width:100,classes:'pointer'},
				{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer'},
				{label:'Average Cost', name: 'p_avgcost', width: 100, classes: 'pointer'},
				{label:'Quantity On Hand',name:'s_qtyonhand',width:80,classes:'pointer'},
			],
			urlParam: {
				filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
				filterVal:['session.compcode',$('#reqdept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_uomcodereqdept.gridname);
				$("#jqGrid2 input[name='uomcode']").val(data['s_uomcode']);
				$("#jqGrid2 input[name='qtyonhand']").val(data['s_qtyonhand']);
				$("#convfactoruomcodereqdept").val(data['u_convfactor']);
				$("#jqGrid2 input[name='netprice']").val(data['p_avgcost']);
				$("#jqGrid2 input[name='uomcoderecv']").val(data['s_uomcode']);
				// checkQOH(event);
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
			title:"Select UOM Code For Request Department",
			open:function(){
				dialog_uomcodereqdept.urlParam.fixPost="true";
				dialog_uomcodereqdept.urlParam.table_id="none_";
				dialog_uomcodereqdept.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcodereqdept.urlParam.filterVal=['session.compcode',$('#reqdept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()];
				dialog_uomcodereqdept.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcodereqdept.urlParam.join_onCol=['s.itemcode','s.uomcode'];
				dialog_uomcodereqdept.urlParam.join_onVal=['p.itemcode','u.uomcode'];
				dialog_uomcodereqdept.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on =']];
				dialog_uomcodereqdept.urlParam.join_filterVal=[['p.compcode','p.uomcode']];
			}
		},'urlParam','radio','tab'
	);
	dialog_uomcodereqdept.makedialog(false);

	var dialog_uomcodereqto = new ordialog(
		'pouom', ['material.uom'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true,or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer', hidden:true }
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

				let data=selrowData('#'+dialog_uomcodereqto.gridname);

				$("#jqGrid2 #"+id_optid+"_convfactoruomcodereqto").val(data['convfactor']);
				$("#jqGrid2  #"+id_optid+"_txnqty").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus();
					console.log($("#jqGrid2 input[name='txnqty']"))
					$("#jqGrid2 input[name='txnqty']").focus();
				}
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_uomcodereqto.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_uomcodereqto.urlParam.filterVal = ['session.compcode', 'ACTIVE'];

			},
			close: function () {
				// $(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		}, 'urlParam','radio','tab'
	);
	dialog_uomcodereqto.makedialog(false);

	/////////////////////////////end dialog handler/////////////////////////////////////////

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
		sortname: 'idno',
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

});

function populate_form(obj){
	//panel header
	$('#ivreqno_show').text(obj.ivreqno);
	$('#suppcode_show').text(obj.supplier_name);
}

function empty_form(){
	$('#ivreqno_show').text('');
	$('#suppcode_show').text('');
}

function reset_all_error(){

}