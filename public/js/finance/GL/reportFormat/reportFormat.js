$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
	////////////////////////////////////////validation////////////////////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function ($form){
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};
	
	var fdl = new faster_detail_load();
	var err_reroll = new err_reroll('#jqGrid',['rptname', 'description']);
	
	//////////////////////////////////////////padzero//////////////////////////////////////////
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
	
	////////////////////////////////////////searchClick2////////////////////////////////////////
	// function searchClick2(grid,form,urlParam){
	// 	$(form+' [name=Stext]').on( "keyup", function (){
	// 		delay(function (){
	// 			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 			// $('#reqnodepan').text("");  // tukar kat depan tu
	// 			// $('#reqdeptdepan').text("");
	// 			// refreshGrid("#jqGrid3",null,"kosongkan");
	// 		}, 500 );
	// 	});
		
	// 	$(form+' [name=Scol]').on( "change", function (){
	// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 		// $('#reqnodepan').text("");  // tukar kat depan tu
	// 		// $('#reqdeptdepan').text("");
	// 		// refreshGrid("#jqGrid3",null,"kosongkan");
	// 	});
	// }
	
	////////////////////////////////////utk dropdown search By////////////////////////////////////
	// searchBy();
	// function searchBy(){
	// 	$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value){
	// 		if (value['canSearch']) {
	// 			if (value['selected']) {
	// 				$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			} else {
	// 				$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			}
	// 		}
	// 	});
	// 	searchClick2('#jqGrid', '#searchForm', urlParam);
	// }
	
	////////////////////////////////////////////jqGrid////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: './util/get_table_default',
		field: '',
		table_name: 'finance.glrpthdr',
		table_id: 'idno',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
		sort_idno: true,
	}
	
	////////////////////////////////////parameter for saving url////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Report Name', name: 'rptname', width: 50, classes: 'wrap text-uppercase', canSearch: true, checked: true, editable: true, editrules: { required: false }, editoptions: { style: "text-transform: uppercase" } },
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap text-uppercase', canSearch: true, editable: true, editrules: { required: false }, editoptions: { style: "text-transform: uppercase" } },
			{ label: 'Category', name: 'rpttype', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "BALANCE SHEET:BALANCE SHEET;PROFIT & LOSS (DETAIL):PROFIT & LOSS (DETAIL);CASH FLOW:CASH FLOW"
				},
			},
			{ label: 'adduser', name: 'adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 10, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected){
			if(!err_reroll.error)$('#p_error').text('');	// hilangkan error msj after save
			
			urlParam2.rptname = selrowData("#jqGrid").rptname;
			refreshGrid("#jqGrid2",urlParam2);
		},
		loadComplete: function (){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_iladd').click();
			}else if($('#jqGrid').data('lastselrow') == 'none'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($('#jqGrid').data('lastselrow'));
				$('#jqGrid tr#' + $('#jqGrid').data('lastselrow')).focus();
			}
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false;	// reset
			if(err_reroll.error == true){
				err_reroll.reroll();
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   // hilangkan duplicate error msj after save
		},
		gridComplete: function (){
			fdl.set_array().reset();
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
		},
	});
	
	////////////////////////////////////////myEditOptions_hdr////////////////////////////////////////
	var myEditOptions_hdr = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$('#jqGrid').data('lastselrow','none');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("input[name='rpttype']").keydown(function (e){	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
			
			$("#jqGrid input[type='text']").on('focus',function (){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options){
			// if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	// only addmore after save inline
			addmore_jqgrid.more = true;	// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function (rowid,response){
			var data = JSON.parse(response.responseText)
			// $('#p_error').text(response.responseText);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			// check_cust_rules();
			
			let editurl = "./reportFormat/form?"+
				$.param({
					action: 'reportFormat_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			refreshGrid('#jqGrid',urlParam,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	//////////////////////////////////////myEditOptions_hdr_edit//////////////////////////////////////
	var myEditOptions_hdr_edit = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("input[name='rptname']").attr('disabled','disabled');
			$("input[name='rpttype']").keydown(function (e){	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
			
			$("#jqGrid input[type='text']").on('focus',function (){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options){
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	// only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam,'edit');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			// check_cust_rules();
			
			let editurl = "./reportFormat/form?"+
				$.param({
					action: 'reportFormat_save',
					idno: selrowData('#jqGrid').idno,
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			refreshGrid('#jqGrid',urlParam,'edit');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////start grid pager/////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_hdr
		},
		editParams: myEditOptions_hdr_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'reportFormat_save',
								rptname: selrowData('#jqGrid').rptname,
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./reportFormat/form?"+$.param(param),{oper:'del'}, function (data){
							}).fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								refreshGrid("#jqGrid", urlParam);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGrid", urlParam);
		},
	});
	
	////////////////////////////handle searching, its radio button and toggle////////////////////////////
	// populateSelect('#jqGrid', '#searchForm');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);
	
	/////////////////////////////add field into param, refresh grid if needed/////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	
	/////////////////////////////////////////function err_reroll/////////////////////////////////////////
	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll=function (){
			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();
			
			this.data_array.forEach(function (item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
	}
	
	//////////////////////////////////////////hide at dialogForm//////////////////////////////////////////
	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2Refresh").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll,#jqGridPager2Refresh").hide();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2Refresh").show();
			$("#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
		}
	}
	
	//////////////////////////////////////////parameter for jqgrid2 url//////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url: './reportFormatDetail/table',
		rptname: '',
	};
	
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	
	////////////////////////////////////////////////////jqgrid2////////////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./reportFormatDetail/form",
		colModel: [
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'rptname', name: 'rptname', hidden: true },
			{ label: 'No', name: 'lineno_', width: 30, classes: 'wrap', editable: true },
			{ label: 'Print Flag', name: 'printflag', width: 40, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "Y:YES;N:NO"
				}
			},
			{ label: 'Row Def', name: 'rowdef', width: 40, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "H:Header;D:Detail;S:Spacing;T0:Total",
				}
			},
			// { label: 'Code', name: 'code', width: 50, classes: 'wrap', editable: true },
			{ label: 'Code', name: 'code', width: 80, classes: 'wrap', canSearch: false, editable: true,
				editrules: { required: false, custom: true, custom_func: cust_rules }, formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: codeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Note', name: 'note', width: 30, classes: 'wrap', editable: true },
			{ label: 'Description', name: 'description', width: 80, classes: 'wrap', editable: true },
			{ label: 'Formula', name: 'formula', width: 40, classes: 'wrap', editable: true },
			{ label: 'Cost Code From', name: 'costcodefr', width: 40, classes: 'wrap', editable: true },
			{ label: 'Cost Code To', name: 'costcodeto', width: 40, classes: 'wrap', editable: true },
			{ label: 'Reverse Sign', name: 'revsign', width: 30, classes: 'wrap', editable: true },
			{ label: 'adduser', name: 'adduser', hidden: true },
			{ label: 'adddate', name: 'adddate', hidden: true },
			{ label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "asc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			
			setjqgridHeight(data,'jqGrid2');
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false;    // reset
			// calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function (){
			fdl.set_array().reset();
			if(!hide_init){
				hide_init=1;
				hideatdialogForm(false);
			}
		},
		beforeSubmit: function (postdata, rowid){
			// dialog_paymode.check(errorField);
		}
	});
	var hide_init=0;
	
	////////////////////////////////////////////set label jqGrid2 right////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");
	
	/////////////////////////////////////////////////myEditOptions/////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			// $("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").hide();
			
			dialog_code.on();
			
			unsaved = false;
			
			// By default, rowdef is set to Header. So show only description
			$("#jqGrid2 input[name='description']").show();
			$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='formula'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto'], #jqGrid2 input[name='revsign']").hide();
			
			$("select[name='rowdef']").change(function (){
				let rowdef1  = $("select[name='rowdef'] option:selected").val();
				
				if(rowdef1 == 'H'){
					$("#jqGrid2 input[name='description']").show();
					$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='formula'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto'], #jqGrid2 input[name='revsign']").hide();
				}else if(rowdef1 == 'D'){
					$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='description'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto'], #jqGrid2 input[name='revsign']").show();
					$("#jqGrid2 input[name='formula']").hide();
				}else if(rowdef1 == 'S'){
					$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='description'], #jqGrid2 input[name='formula'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto'], #jqGrid2 input[name='revsign']").hide();
				}else{	// if(rowdef1 == 'TO')
					$("#jqGrid2 input[name='description'], #jqGrid2 input[name='formula'], #jqGrid2 input[name='revsign']").show();
					$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto']").hide();
				}
			});
			
			$("input[name='revsign']").keydown(function (e){	// when click tab at revsign, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// $('#db_amount').val(response.responseText);
			// if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true;	// only addmore after save inline
			addmore_jqgrid2.more = true;	// state true maksudnyer ada isi, tak kosong
			urlParam2.rptname = selrowData('#jqGrid').rptname;
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").show();
			errorField.length=0;
		},
		errorfunc: function (rowid,response){
			alert(response.responseText);
			urlParam2.rptname = selrowData('#jqGrid').rptname;
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		beforeSaveRow: function (options, rowid){
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./reportFormatDetail/form?"+
				$.param({
					action: 'reportFormat_detail_save',
					rptname: selrowData('#jqGrid').rptname,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			errorField.length=0;
			hideatdialogForm(false);
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
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
		onClickButton: function (){
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'reportFormat_detail_save',
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./reportFormatDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()},
							function (data){
							}).fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								urlParam2.rptname = selrowData('#jqGrid').rptname;
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
		onClickButton: function (){
			errorField.length=0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				$("#jqGrid2").jqGrid('editRow',ids[i]);
				
				var rowdef1 = $("#jqGrid2 select#"+ids[i]+"_rowdef").val();
				
				if(rowdef1 == 'H'){
					$("#jqGrid2 input#"+ids[i]+"_description").show();
					$(".input-group#"+ids[i]+"_code").hide();
					$("#jqGrid2 input#"+ids[i]+"_note").hide();
					$("#jqGrid2 input#"+ids[i]+"_formula").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodefr").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodeto").hide();
					$("#jqGrid2 input#"+ids[i]+"_revsign").hide();
				}else if(rowdef1 == 'D'){
					$(".input-group#"+ids[i]+"_code").show();
					$("#jqGrid2 input#"+ids[i]+"_note").show();
					$("#jqGrid2 input#"+ids[i]+"_description").show();
					$("#jqGrid2 input#"+ids[i]+"_costcodefr").show();
					$("#jqGrid2 input#"+ids[i]+"_costcodeto").show();
					$("#jqGrid2 input#"+ids[i]+"_revsign").show();
					$("#jqGrid2 input#"+ids[i]+"_formula").hide();
				}else if(rowdef1 == 'S'){
					$(".input-group#"+ids[i]+"_code").hide();
					$("#jqGrid2 input#"+ids[i]+"_note").hide();
					$("#jqGrid2 input#"+ids[i]+"_description").hide();
					$("#jqGrid2 input#"+ids[i]+"_formula").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodefr").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodeto").hide();
					$("#jqGrid2 input#"+ids[i]+"_revsign").hide();
				}else{	// if(rowdef1 == 'TO')
					$("#jqGrid2 input#"+ids[i]+"_description").show();
					$("#jqGrid2 input#"+ids[i]+"_formula").show();
					$("#jqGrid2 input#"+ids[i]+"_revsign").show();
					$(".input-group#"+ids[i]+"_code").hide();
					$("#jqGrid2 input#"+ids[i]+"_note").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodefr").hide();
					$("#jqGrid2 input#"+ids[i]+"_costcodeto").hide();
				}
				
				if($(".input-group#"+ids[i]+"_code").is(":visible")){
					dialog_code.id_optid = ids[i];
					dialog_code.check(errorField,ids[i]+"_code","jqGrid2",null,
						function (self){
							if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
						}
					);
				}
				
				$("#jqGrid2 select#"+ids[i]+"_rowdef").change(function (){
					var rowdef1 = $(this).val();
					var rowid = $(this).attr('rowid');
					
					if(rowdef1 == 'H'){
						$("#jqGrid2 input#"+rowid+"_description").show();
						$(".input-group#"+rowid+"_code").hide();
						$("#jqGrid2 input#"+rowid+"_note").hide();
						$("#jqGrid2 input#"+rowid+"_formula").hide();
						$("#jqGrid2 input#"+rowid+"_costcodefr").hide();
						$("#jqGrid2 input#"+rowid+"_costcodeto").hide();
						$("#jqGrid2 input#"+rowid+"_revsign").hide();
					}else if(rowdef1 == 'D'){
						$(".input-group#"+rowid+"_code").show();
						$("#jqGrid2 input#"+rowid+"_note").show();
						$("#jqGrid2 input#"+rowid+"_description").show();
						$("#jqGrid2 input#"+rowid+"_costcodefr").show();
						$("#jqGrid2 input#"+rowid+"_costcodeto").show();
						$("#jqGrid2 input#"+rowid+"_revsign").show();
						$("#jqGrid2 input#"+rowid+"_formula").hide();
					}else if(rowdef1 == 'S'){
						$(".input-group#"+rowid+"_code").hide();
						$("#jqGrid2 input#"+rowid+"_note").hide();
						$("#jqGrid2 input#"+rowid+"_description").hide();
						$("#jqGrid2 input#"+rowid+"_formula").hide();
						$("#jqGrid2 input#"+rowid+"_costcodefr").hide();
						$("#jqGrid2 input#"+rowid+"_costcodeto").hide();
						$("#jqGrid2 input#"+rowid+"_revsign").hide();
					}else{	// if(rowdef1 == 'TO')
						$("#jqGrid2 input#"+rowid+"_description").show();
						$("#jqGrid2 input#"+rowid+"_formula").show();
						$("#jqGrid2 input#"+rowid+"_revsign").show();
						$(".input-group#"+rowid+"_code").hide();
						$("#jqGrid2 input#"+rowid+"_note").hide();
						$("#jqGrid2 input#"+rowid+"_costcodefr").hide();
						$("#jqGrid2 input#"+rowid+"_costcodeto").hide();
					}
				});
			}
			onall_editfunc();
			hideatdialogForm(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2SaveAll",
		caption:"",cursor: "pointer",position: "last",
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function (){
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			
			var jqgrid2_data = [];
			
			// if(errorField.length>0){
			// 	console.log(errorField)
			// 	return false;
			// }
			
			for (var i = 0; i < ids.length; i++) {
				// if(parseInt($('#'+ids[i]+"_quantity").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				// console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					// mycurrency2.formatOn();
					return false;
				}
				
				// cust_rules()
				
				var obj = 
				{
					// 'lineno_' : ids[i],
					'idno' : data.idno,
					'lineno_' : $("#jqGrid2 input#"+ids[i]+"_lineno_").val(),
					'printflag' : $("#jqGrid2 select#"+ids[i]+"_printflag").val(),
					'rowdef' : $("#jqGrid2 select#"+ids[i]+"_rowdef").val(),
					'code' : $("#jqGrid2 input#"+ids[i]+"_code").val(),
					'note' : $("#jqGrid2 input#"+ids[i]+"_note").val(),
					'description' : $("#jqGrid2 input#"+ids[i]+"_description").val(),
					'formula' : $("#jqGrid2 input#"+ids[i]+"_formula").val(),
					'costcodefr' : $("#jqGrid2 input#"+ids[i]+"_costcodefr").val(),
					'costcodeto' : $("#jqGrid2 input#"+ids[i]+"_costcodeto").val(),
					'revsign' : $("#jqGrid2 input#"+ids[i]+"_revsign").val(),
				}
				
				jqgrid2_data.push(obj);
			}
			
			var param = {
				action: 'reportFormat_detail_save',
				_token: $("#_token").val(),
				idno: selrowData('#jqGrid2').idno,
			}
			
			$.post( "./reportFormatDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function (data){
			}).fail(function (data){
				// alert(dialog,data.responseText);
			}).done(function (data){
				// mycurrency.formatOn();
				hideatdialogForm(false);
				urlParam2.rptname = selrowData('#jqGrid').rptname;
				refreshGrid("#jqGrid2",urlParam2);
			});
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last",
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function (){
			hideatdialogForm(false);
			urlParam2.rptname = selrowData('#jqGrid').rptname;
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			urlParam2.rptname = selrowData('#jqGrid').rptname;
			refreshGrid("#jqGrid2",urlParam2);
		},
	});
	
	//////////////////////////////////////////////formatter checkdetail//////////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'code':field=['code','description'];table="finance.glconsol";case_='code';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('reportFormat',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}
	
	/////////////////////////////////////////////////////cust_rules/////////////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			// jqGrid2
			case 'Code':temp=$('#code');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	////////////////////////////////////////////////////custom input////////////////////////////////////////////////////
	function codeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="code" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary" id="'+opt.id+'"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	////////////////////////////////////////////////////////edit all////////////////////////////////////////////////////////
	function onall_editfunc(){
		errorField.length=0;
		dialog_code.on();
		
		// mycurrency2.formatOnBlur();//make field to currency on leave cursor
		// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
	}
	
	////////////////////////////////////////////////////////ordialog////////////////////////////////////////////////////////
	var dialog_code = new ordialog(
		'code', 'finance.glconsol', "#jqGrid2 input[name='code']", errorField,
		{
			colModel: [
				{ label: 'Code', name: 'code', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode'],
				filterVal: ['session.compcode']
			},
			ondblClickRow: function (event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_note").focus().select();
			},
			loadComplete: function (data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_code").val();
				var rows = data.rows;
				var gridname = '#'+obj.gridname;
				
				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function (e,i){
						if(e.code.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Code",
			open: function (){
				dialog_code.urlParam.filterCol= ['compcode'];
				dialog_code.urlParam.filterVal= ['session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_code.makedialog(false);
	
	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}
	
	function check_cust_rules(grid,data){
		var cust_val =  true;
		Object.keys(data).every(function (v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}
	
	$("#jqGrid2_panel").on("show.bs.collapse", function (){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
	});
	
	$('#excelgen1').click(function (){
		window.location='./reportFormat/showExcel?rptname='+selrowData('#jqGrid').rptname;
	});
	
	$('#pdfgen1').click(function (){
		window.open('./reportFormat/showpdf?rptname='+selrowData('#jqGrid').rptname, '_blank');
	});
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
}