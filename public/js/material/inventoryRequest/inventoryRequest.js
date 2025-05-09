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
	var mycurrency = new currencymode(['#amount']);
	var fdl = new faster_detail_load();
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
				$('#jqGridPager2EditAll').data('click',false);
				unsaved = false;
				errorField.length=0;
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
							unsaved = false;
							delete_dd($('#idno').val());
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

		$( "#reportDialog" ).dialog({
			autoOpen: false,
			width: 5/10 * $(window).width(),
			modal: true,
			open: function(){
			
			},
			close: function( event, ui ){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_report');
			},
			buttons:
			[
			{
				text: "Generate PDF",click: function() {
					window.open('./inventoryRequest/showpdf?reqdept_from='+$('#reqdept_from').val()+'&reqdept_to='+$("#reqdept_to").val()+'&datefrom='+$("#datefrom").val()+'&dateto='+$("#dateto").val(),  '_blank'); 
				}
			},
			{
				text: "Generate Excel",click: function() {
					window.location='./inventoryRequest/showExcel?reqdept_from='+$('#reqdept_from').val()+'&reqdept_to='+$("#reqdept_to").val()+'&datefrom='+$("#datefrom").val()+'&dateto='+$("#dateto").val();
				}
			},{
				text: "Close",click: function() {
					$(this).dialog('close');
					emptyFormdata(errorField,'#formdata_report');
				}
			}],
		});
	
		$('#pdfgen_excel').click(function(){
			$( "#reportDialog" ).dialog( "open" );
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////

	var recstatus_filter = [['OPEN','POSTED']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','POSTED']];
		filterCol_urlParam = ['ivreqhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","recstatus", recstatus_filter[0][0]);

	var urlParam = {
		action: 'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name: ['material.ivreqhd'],
		table_id: 'idno',
		filterCol: ['reqdept','compcode'],
		filterVal: [$('#deptcode').val(),'session.compcode'],
		WhereInCol:['ivreqhd.recstatus'],
		WhereInVal: recstatus_filter,
		//fixPost: true,
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'invReq_header_save',
		url:'./inventoryRequest/form',
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
			{ label: 'Request Department', name: 'reqdept', width: 30, canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Request No', name: 'ivreqno', width: 25, canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero },
			{ label: 'Request To Department', name: 'reqtodept', width: 30, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Request Date', name: 'reqdt', width: 20, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'amount', width: 20, align: 'right', formatter: 'currency', hidden:true},
			{ label: 'Recstatus', name: 'recstatus', width: 20},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
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
			{ label: 'postedby', name: 'postedby', width: 90, hidden: true },
			{ label: 'postdate', name: 'postdate', width: 90, hidden: true },
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
            $("#pdfgen_excel_each").attr('href','./inventoryRequest/table?action=showpdf_each&idno='+selrowData("#jqGrid").idno);
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").recstatus;
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

			//$("#pdfgen1").attr('href','./inventoryRequest/showpdf?recno='+selrowData("#jqGrid").recno);

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").recstatus;
			if(stat=='OPEN'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			populate_form(selrowData("#jqGrid"));
			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			fdl.set_array().reset();	
		},
		loadComplete: function(){
			// calc_jq_height_onchange("jqGrid");
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
	addParamField('#jqGrid', false, saveParam, ['recno','ivreqno','adduser', 'adddate', 'idno', 'upduser','upddate','deluser', 'recstatus','unit','Checkbox']);

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

	///////////////////////////////////////save POSTED,CANCEL,REOPEN////////////////////////////////////
	$("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

		var idno = selrowData('#jqGrid').idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( './inventoryRequest/form', obj , function( data ) {
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
		
		$.post( './inventoryRequest/form', obj , function( data ) {
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
				$('#adduser').val(data.adduser);
				$('#adddate').val(data.adddate);

				urlParam2.filterVal[0] = data.recno;
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
				$('#upduser').val(data.upduser);
				$('#upddate').val(data.upddate);
			}
			
			refreshGrid('#jqGrid2', urlParam2);
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
			url: 'util/get_value_default',
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

	searchChange(true);
	function searchChange(init=false) {
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
		if(init){
			refreshGrid('#jqGrid', urlParam);
		}
	}

	// resizeColumnHeader = function () {
    //     var rowHight, resizeSpanHeight,
    //     // get the header row which contains
    //     headerRow = $(this).closest("div.ui-jqgrid-view")
    //         .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");

    //     // reset column height
    //     headerRow.find("span.ui-jqgrid-resize").each(function () {
    //         this.style.height = "";
    //     });

    //     // increase the height of the resizing span
    //     resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
    //     headerRow.find("span.ui-jqgrid-resize").each(function () {
    //         this.style.cssText = resizeSpanHeight;
    //     });

    //     // set position of the dive with the column header text to the middle
    //     rowHight = headerRow.height();
    //     headerRow.find("div.ui-jqgrid-sortable").each(function () {
    //         var ts = $(this);
    //         ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
    //     });
    // },
    // fixPositionsOfFrozenDivs = function () {
    //     var $rows;
    //     if (typeof this.grid.fbDiv !== "undefined") {
    //         $rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
    //         $(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i) {
    //             var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
    //             if ($(this).hasClass("jqgrow")) {
    //                 $(this).height(rowHight);
    //                 rowHightFrozen = $(this).height();
    //                 if (rowHight !== rowHightFrozen) {
    //                     $(this).height(rowHight + (rowHight - rowHightFrozen));
    //                 }
    //             }
    //         });
    //         $(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
    //         $(this.grid.fbDiv).css($(this.grid.bDiv).position());
    //     }
    //     if (typeof this.grid.fhDiv !== "undefined") {
    //         $rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
    //         $(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i) {
    //             var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
    //             $(this).height(rowHight);
    //             rowHightFrozen = $(this).height();
    //             if (rowHight !== rowHightFrozen) {
    //                 $(this).height(rowHight + (rowHight - rowHightFrozen));
    //             }
    //         });
    //         $(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
    //         $(this.grid.fhDiv).css($(this.grid.hDiv).position());
    //     }
    // },
    // fixGboxHeight = function () {
    //     var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
    //         pagerHeight = $(this.p.pager).outerHeight();

    //     $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
    //     gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
    //     pagerHeight = $(this.p.pager).outerHeight();
    //     $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
    // }


	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url:'util/get_table_default',
		field: ['ivdt.compcode', 'ivdt.recno', 'ivdt.lineno_', 'ivdt.itemcode', 'ivdt.uomcode', 'ivdt.pouom',
		'ivdt.maxqty', 'ivdt.qtyonhand', 'ivdt.qtyrequest', 'ivdt.qtytxn', 'ivdt.qtybalance', 'ivdt.qohconfirm','ivdt.netprice',
		'ivdt.recstatus','ivdt.expdate','ivdt.batchno'],
		table_name: ['material.ivreqdt AS ivdt '],
		table_id: 'lineno_',
		// join_type: ['LEFT JOIN', 'LEFT JOIN'],
		// join_onCol: ['ivdt.itemcode', 'ivdt.itemcode'],
		// join_onVal: ['s.itemcode', 'p.itemcode'],
		// join_filterCol : [['ivdt.reqdept on =', 'ivdt.uomcode on =', 's.year =']],
        // join_filterVal : [['s.deptcode','s.uomcode',moment().year()]],
		filterCol: ['ivdt.recno', 'ivdt.compcode','ivdt.recstatus'],
		filterVal: ['', 'session.compcode','<>.DELETE']
	};
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./inventoryRequestDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 70, classes: 'wrap', editable: false, hidden: true },
			{
				label: 'Item Code', name: 'itemcode', width: 250, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 350, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" }, hidden:true},
			{
				label: 'Uom Code ReqDept', name: 'uomcode', width: 125, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Uom Code ReqMadeTo', name: 'pouom', width: 130, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Max Qty', name: 'maxqty', width: 50, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req Dept', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			// {
			// 	label: 'Qty on Hand at Req To Dept', name: 'qohconfirm', width: 100, align: 'right', classes: 'wrap',
			// 	editable: true,
			// 	formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
			// 	editrules: { required: false }, editoptions: { readonly: "readonly" },
			// },
			{
				label: 'Qty Requested', name: 'qtyrequest', width: 80, align: 'right', classes: 'wrap',
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
				label: 'Qty Balance', name: 'qtybalance', width: 80, align: 'right', classes: 'wrap', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty Supplied', name: 'qtytxn', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Net Price', name: 'netprice', width: 80, align: 'right', classes: 'wrap', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Type', name: 'recstatus', width: 100, classes: 'wrap', hidden: true, editable: false,
				editoptions: { readonly: "readonly" },
			},
			{ label: 'Expiry Date', name: 'expdate', width: 80, classes: 'wrap', editable:true,
			formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},editoptions:{readonly: "readonly"},
					editrules:{required: false,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:expdateCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Batch No', name: 'batchno', width: 100, classes: 'wrap', editable:true,editoptions:{readonly: "readonly"},
					maxlength: 100,
			},

		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 100,
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
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			setjqgridHeight(data,'jqGrid2');
			
			addmore_jqgrid2.edit = false;addmore_jqgrid2.more = false; //reset
	
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			
		},
		
		gridComplete: function(){
			fdl.set_array(function(){
        		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			}).reset();
			//calculate_quantity_outstanding('#jqGrid2');

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
				if(text.search("jqg") != -1)return false;return true;
			});

			if(result.length == 0 && oper=='edit')unsaved = true;
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_itemcode.check(errorField);
			dialog_uomcodereqdept.check(errorField);
			dialog_uomcodereqto.check(errorField);
		}

		// }).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        // fixPositionsOfFrozenDivs.call(this);
    });
		// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

		// $("#jqGrid2").jqGrid('bindKeys');
		// 	var updwnkey_fld;
		// 	function updwnkey_func(event){
		// 		var optid = event.currentTarget.id;
		// 		var fieldname = optid.substring(optid.search("_"));
		// 		updwnkey_fld = fieldname;
		// 	}

		// 	$("#jqGrid2").keydown(function(e) {
		// 	switch (e.which) {
		// 		case 40: // down
		// 		var $grid = $(this);
		// 		var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
		// 		$("#"+selectedRowId+updwnkey_fld).focus();

		// 		e.preventDefault();
		// 		break;

		// 		case 38: // up
		// 		var $grid = $(this);
		// 		var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
		// 		$("#"+selectedRowId+updwnkey_fld).focus();

		// 		e.preventDefault();
		// 		break;

		// 		default:
		// 		return;
		// 	}
		// 	});


		// $("#jqGrid2").jqGrid('setGroupHeaders', {
		// useColSpanStyle: false, 
		// groupHeaders:[
		// 	{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
		// 	{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
		// ]
		// });

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks/////////////////////////////////////////////////

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
			if(rowObject.recstatus == "OPEN" || rowObject.recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
	}

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			myfail_msg.clear_fail();
			errorField.length=0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_itemcode.on();
			dialog_uomcodereqdept.on();
			dialog_uomcodereqto.on();
			dialog_expdate.on();

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyrequest']", "#jqGrid2 input[name='qtytxn']", "#jqGrid2 input[name='qohconfirm']", "#jqGrid2 input[name='qtyonhand']"]);
			
			// $("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
	
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='qtyrequest']").keydown(function(e) {//when click tab at qtyrequest, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});

		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#recno').val(resobj.recno);
			$('#ivreqno').val(resobj.ivreqno);
			// $('#totamount').val(response.responseText);
			// $('#subamount').val(response.responseText);
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
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
		beforeSaveRow: function (options, rowid) {
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0){
	        	myfail_msg.add_fail({
					id:'qtyrequest_is_zero',
					textfld:'#jqGrid2 input[name="qtyrequest"]',
					msg:'Quantity Request cant be 0',
				});
				return false;
			}else{
				myfail_msg.del_fail({
					id:'qtyrequest_is_zero',
					textfld:null,
					msg:null,
				});
			}

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);

			let editurl = "./inventoryRequestDetail/form?"+
				$.param({
					action: 'invReqDetail_save',
					idno: $('#idno').val(),
					recno: $('#recno').val(),
					reqdept: $('#reqdept').val(),
					reqdt: $('#reqdt').val(),
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
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
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
							$.post( "./inventoryRequestDetail/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid2", urlParam2);
								$('#amount').val(data);
								
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

		        dialog_itemcode.id_optid = ids[i];
		        dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
        				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
				    }
			    );

		        dialog_uomcodereqdept.id_optid = ids[i];
		        dialog_uomcodereqdept.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
        				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			        }
			    );

				dialog_uomcodereqto.id_optid = ids[i];
		        dialog_uomcodereqto.check(errorField,ids[i]+"_pouom","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
        				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			        }
			    );

			    dialog_expdate.id_optid = ids[i];
		        // dialog_expdate.check(errorField,ids[i]+"_expdate","jqGrid2",null,
		        // 	function(self){
			    //     	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     },function(self){
        		// 		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			    //     }
			    // );

		       // cari_gstpercent(ids[i]);
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

			// if(errorField.length>0){
			// 	console.log(errorField)
			// 	return false;
			// }

		    for (var i = 0; i < ids.length; i++) {
				if(parseInt($('#'+ids[i]+"_qtyrequest").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				// let retval = check_cust_rules("#jqGrid2",data);
				// console.log(retval);
				// if(retval[0]!= true){
				// 	alert(retval[1]);
				// 	return false;
				// }

				// cust_rules()

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'pouom' : $("#jqGrid2 input#"+ids[i]+"_pouom").val(),
		    		'qtyrequest' : $('#'+ids[i]+"_qtyrequest").val(),
					'qtyonhand' : $('#'+ids[i]+"_qtyonhand").val(),
					'qohconfirm' : $('#'+ids[i]+"_qohconfirm").val(),
                    'expdate' : $("#jqGrid2 input#"+ids[i]+"_expdate").val(),
                    'batchno' : $("#"+ids[i]+"_batchno").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invReqDetail_save',
				_token: $("#_token").val(),
				recno: $('#recno').val(),
				ivreqno:$('#ivreqno').val(),
				reqdt:$('#reqdt').val(),
				reqdept:$('#reqdept').val(),
				qtyonhand:$('#qtyonhand').val(),
    		}

    		$.post( "./inventoryRequestDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				alert(dialog,data.responseText);
			}).done(function(data){
				// $('#totamount').val(data);
				// $('#subamount').val(data);
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
			case 'reqdept':field=['deptcode','description'];table="sysdb.department";case_='reqdept';break;
			case 'reqtodept':field=['deptcode','description'];table="sysdb.department";case_='reqtodept';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('inventoryRequest',options,param,case_,cellvalue);

		if(options.gid != 'jqGrid2'){
        	calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		}
		
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
				</div><span class="help-block"></span>
				<div style='display:none'>
					<input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden">
					<input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`>
					<input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`>
				</div>

			`);
	}
	function expdateCustomEdit(val,opt){
		val = getEditVal(val);
		 return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'"  name="expdate" type="text" class="form-control input-sm" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
		
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
		addmore_jqgrid2.state = false;
		dialog_reqdept.on();
		dialog_reqtodept.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac/////////////////////////////////
		// function calculate_conversion_factor(event) {
		// 	var optid = event.currentTarget.id;
		// 	var id_optid = optid.substring(0,optid.search("_"));

		// 	var id="#jqGrid2 #"+id_optid+"_qtyonhand";
		// 	var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		// 	var name = "calculate_conversion_factor";

		// 	let convfactor_bool = false;
		// 	let convfactor_uomcodereqdept  = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodereqdept").val());
		// 	let convfactor_uomcodereqto = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodereqto").val());
		// 	let qtyonhand = parseFloat($("#jqGrid2 #"+id_optid+"_qtyonhand").val());

		// 	var balconv = convfactor_uomcodereqdept*qtyonhand%convfactor_uomcodereqto;
		// 	if (balconv  == 0) {
		// 		if($.inArray(id,errorField)!==-1){
		// 			errorField.splice($.inArray(id,errorField), 1);
		// 		}
		// 		$('.noti').find("li[data-errorid='"+name+"']").detach();
		// 	} else {
		// 		$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
		// 		if($.inArray(id,errorField)===-1){
		// 			errorField.push( id );
		// 		}
		// 	}
		// }

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
		dialog_itemcode.off();
		$(dialog_itemcode.textfield).attr('disabled',true);
		dialog_uomcodereqdept.off();
		$(dialog_uomcodereqdept.textfield).attr('disabled',true);
		dialog_uomcodereqto.off();
		$(dialog_uomcodereqto.textfield).attr('disabled',true);
		
		dialog_expdate.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor
	
		$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);
	
	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 100,
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
			
			calc_jq_height_onchange("jqGrid3");
			
		},
	
		gridComplete: function(){
			
			fdl.set_array().reset();

		},
	// }).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
    //     fixPositionsOfFrozenDivs.call(this);
    });
	// fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	// $("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");

	//////////////////////////////dialog handler report/////////////////////////////////////////////////
	var reqdept_from = new ordialog(
		'reqdept_from', 'sysdb.department', '#reqdept_from', 'errorField',
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'storedept'],
				filterVal:['session.compcode','ACTIVE','1']
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
		}, {
			title: "Select Request Department",
			open: function(){
				reqdept_from.urlParam.filterCol=['recstatus', 'compcode', 'storedept'];
				reqdept_from.urlParam.filterVal=['ACTIVE', 'session.compcode', '1'];
			},close: function(){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('reqdept_to',errorField)!==-1){
						errorField.splice($.inArray('reqdept_to',errorField), 1);
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
	reqdept_from.makedialog(true);

	var reqdept_to = new ordialog(
		'reqdept_to', 'sysdb.department', '#reqdept_to', 'errorField',
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'storedept'],
				filterVal:['session.compcode','ACTIVE','1']
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
		}, {
			title: "Select Request Department",
			open: function(){
				reqdept_to.urlParam.filterCol=['recstatus', 'compcode', 'storedept'];
				reqdept_to.urlParam.filterVal=['ACTIVE', 'session.compcode', '1'];
			},close: function(){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('reqdept_to',errorField)!==-1){
						errorField.splice($.inArray('reqdept_to',errorField), 1);
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
	reqdept_to.makedialog(true);
	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'storedept'],
				filterVal:['session.compcode','ACTIVE','1']
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
				dialog_reqdept.urlParam.filterCol=['recstatus', 'compcode', 'storedept'];
				dialog_reqdept.urlParam.filterVal=['ACTIVE', 'session.compcode', '1'];
			},close: function(){
			},
			after_check: function(obj_,data){
				$('html').removeClass("has-success");
			}
		},'urlParam','radio','tab'
	);
	dialog_reqdept.makedialog();

	var dialog_reqtodept = new ordialog(
		'reqtodept','sysdb.department','#reqtodept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true, checked:true, or_search:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus', 'compcode'],
				filterVal:['1', 'ACTIVE','session.compcode']
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
				dialog_reqtodept.urlParam.filterCol=['storedept','recstatus', 'compcode'];
				dialog_reqtodept.urlParam.filterVal=['1','ACTIVE','session.compcode'];
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
				{label:'Max Quantity',name:'s_maxqty',width:100,classes:'pointer', hidden:true},
				{label:'Average Cost', name: 'p_avgcost', width: 100, classes: 'pointer', hidden:false },
				{label:'Exp', name: 'p_expdtflg', width: 50, classes: 'pointer',formatter:formatterstatus_tick_number,unformat:unformatstatus_tick_number },
				{label:'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
				{label:'Unit', name: 's_unit', width: 80, classes: 'pointer' },
			],
			urlParam: {
				filterCol:['s.compcode','s.year','s.deptcode'],
				filterVal:['session.compcode', moment($('#reqdt').val()).year(),$('#reqtodept').val()]
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

				let data=selrowData('#'+dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s_itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p_description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s_uomcode']);
				$("#jqGrid2 input[name='pouom']").val(data['s_uomcode']);

				dialog_uomcodereqdept.urlParam.filterVal=['session.compcode',$('#reqtodept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()];
				dialog_uomcodereqdept.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcodereqdept.urlParam.fixPost="true";
				dialog_uomcodereqdept.check(errorField);

				dialog_uomcodereqto.urlParam.filterVal=['session.compcode',$('#reqtodept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()];
				dialog_uomcodereqto.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcodereqto.urlParam.fixPost="true";
				dialog_uomcodereqto.check(errorField);

				$("#jqGrid2 input[name='maxqty']").val(data['s_maxqty']);
				$("#jqGrid2 input[name='netprice']").val(data['p_avgcost']);
				$("#jqGrid2 input[name='convfactoruomcodetrdept']").val(data['u_convfactor']);
				$("#jqGrid2 input[name='qtyonhand']").val(data['s_qtyonhand']);
				$("#jqGrid2 #"+id_optid+"_qtyrequest").focus().select();
				
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='pouom']").focus().select();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},{
			title:"Select Item For Stock Request",
			open:function(){
				dialog_itemcode.urlParam.fixPost="true";
				dialog_itemcode.urlParam.table_id="none_";
				dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode','s.unit'];
				dialog_itemcode.urlParam.filterVal=['session.compcode',moment($('#reqdt').val()).year(),$('#reqtodept').val(),'notnull'];
				dialog_itemcode.urlParam.join_type=['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol=['s.itemcode','u.uomcode'];
				dialog_itemcode.urlParam.join_onVal=['p.itemcode', 's.uomcode'];
				dialog_itemcode.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on =','p.recstatus =','s.unit on ='], []];
				dialog_itemcode.urlParam.join_filterVal=[['p.compcode','p.uomcode','ACTIVE','p.unit'], []];
			},
			close: function(obj){
				let id_optid = obj.id_optid;
				console.log($("#jqGrid2 #"+id_optid+"_qtyrequest"));
				$("#jqGrid2 #"+id_optid+"_qtyrequest").focus().select();
			},
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
				fixPost:"true",
				filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
				filterVal:['session.compcode',$('#reqtodept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()],
				join_type:['LEFT JOIN','LEFT JOIN'],
				join_onCol:['s.itemcode','s.uomcode'],
				join_onVal:['p.itemcode','u.uomcode'],
				join_filterCol:[['s.compcode on =', 's.uomcode on =']],
				join_filterVal:[['p.compcode','p.uomcode']],
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

				let data=selrowData('#'+dialog_uomcodereqdept.gridname);

				$("#jqGrid2 #"+id_optid+"_uomcode").val(data['s_uomcode']);
				$("#jqGrid2 #"+id_optid+"_qtyonhand").val(data['s_qtyonhand']);
				$("#"+id_optid+"_convfactoruomcodereqdept").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_netprice").val(data['p_avgcost']);
				$("#jqGrid2 #"+id_optid+"_uomcoderecv").val(data['s_uomcode']);
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
			open:function(obj){
				dialog_uomcodereqdept.urlParam.fixPost="true";
				dialog_uomcodereqdept.urlParam.table_id="none_";
				dialog_uomcodereqdept.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcodereqdept.urlParam.filterVal=['session.compcode',$('#reqtodept').val(),$("#jqGrid2 input#"+obj.id_optid+"_itemcode").val(),moment($('#reqdt').val()).year()];
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
		'pouom',['material.stockloc AS s','material.product AS p','material.uom AS u'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
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
					fixPost:"true",
					filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
					filterVal:['session.compcode',$('#reqdept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#reqdt').val()).year()],
					join_type:['LEFT JOIN','LEFT JOIN'],
					join_onCol:['s.itemcode','s.uomcode'],
					join_onVal:['p.itemcode','u.uomcode'],
					join_filterCol:[['s.compcode on =', 's.uomcode on =']],
					join_filterVal:[['p.compcode','p.uomcode']],
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
				
				$("#jqGrid2  #"+id_optid+"_txnqty").focus().select();
				
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='qtyrequest']").focus().select();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}	
			},
			close: function(){
				$("#jqGrid2 input[name='qtyrequest']").focus().select();
			},

		}, {
			title: "Select PO UOM Code For Item",
			open: function (obj) {
				dialog_uomcodereqto.urlParam.fixPost="true";
				dialog_uomcodereqto.urlParam.table_id="none_";
				dialog_uomcodereqto.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcodereqto.urlParam.filterVal=['session.compcode',$('#reqdept').val(),$("#jqGrid2 input#"+obj.id_optid+"_itemcode").val(),moment($('#reqdt').val()).year()];
				dialog_uomcodereqto.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcodereqto.urlParam.join_onCol=['s.itemcode','s.uomcode'];
				dialog_uomcodereqto.urlParam.join_onVal=['p.itemcode','u.uomcode'];
				dialog_uomcodereqto.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on =']];
				dialog_uomcodereqto.urlParam.join_filterVal=[['p.compcode','p.uomcode']];

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


	var dialog_expdate = new ordialog(
		'expdate',['material.stockexp'],"#jqGrid2 input[name='expdate']",'errorField',
		{	colModel:
			[
				{label:'Expiry Date',name:'expdate',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true,},
				{label:'Batch No',name:'batchno',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Quantity',name:'balqty',width:400,classes:'pointer'},
				{label:'itemcode', name: 'itemcode', width: 50, classes: 'pointer', hidden:true },
				{label:'uomcode', name: 'uomcode', width: 50, classes: 'pointer', hidden:true },
				{label:'deptcode', name: 'deptcode', width: 50, classes: 'pointer', hidden:true },
			],
			urlParam: {
				filterCol:['compcode','year','deptcode', 'uomcode', 'itemcode'],
				filterVal:['session.compcode',moment($('#reqdt').val()).year(),$("#reqtodept").val(), $("#jqGrid2 input[name='uomcode']").val(), $("#jqGrid2 input[name='itemcode']").val()]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_expdate.gridname);
				$("#jqGrid2 input[name='batchno']").val(data['batchno']);
			},
		/*	gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}*/
		},{
			title:"Select Expiry Date",
			open: function(obj){
				dialog_expdate.urlParam.filterCol=['compcode','year','deptcode', 'uomcode', 'itemcode'];
				dialog_expdate.urlParam.filterVal=['session.compcode',moment($('#reqdt').val()).year(),$("#reqtodept").val(),$("#jqGrid2 input#"+obj.id_optid+"_uomcode").val(), $("#jqGrid2 input#"+obj.id_optid+"_itemcode").val()];
			
			}
		},'urlParam','radio','tab'
	);
	dialog_expdate.makedialog(false);

	/////////////////////////////end dialog handler/////////////////////////////////////////

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'idno',
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
			$.post( 'inventoryRequestDetail/form',obj,function( data ) {
					
			});
		}
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