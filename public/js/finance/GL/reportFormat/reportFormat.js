$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	////////////////////////////////////////validation////////////////////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
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
	
	///////////////////////////////////////start dialog///////////////////////////////////////
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
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
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
				} if (oper != 'add') {
					// dialog_CustomerDN.check(errorField);
				} if (oper != 'view') {
					// dialog_CustomerDN.on();
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
				addmore_jqgrid2.state = false;  // reset balik
				addmore_jqgrid2.more = false;
				// reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				// dialog_CustomerDN.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
		});
	////////////////////////////////////////end dialog////////////////////////////////////////
	
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
	// 	$(form+' [name=Stext]').on( "keyup", function() {
	// 		delay(function(){
	// 			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 			// $('#reqnodepan').text("");  // tukar kat depan tu
	// 			// $('#reqdeptdepan').text("");
	// 			// refreshGrid("#jqGrid3",null,"kosongkan");
	// 		}, 500 );
	// 	});
		
	// 	$(form+' [name=Scol]').on( "change", function() {
	// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 		// $('#reqnodepan').text("");  // tukar kat depan tu
	// 		// $('#reqdeptdepan').text("");
	// 		// refreshGrid("#jqGrid3",null,"kosongkan");
	// 	});
	// }
	
	////////////////////////////////////utk dropdown search By////////////////////////////////////
	// searchBy();
	// function searchBy() {
	// 	$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
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
	var saveParam = {
		action: 'reportFormat_header_save',
		url: './reportFormat/form',
		field: '',
		oper: oper,
		table_name: 'finance.glrpthdr',
		table_id: 'idno',
		fixPost: true,
		// returnVal: true,
	}
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Report Name', name: 'rptname', width: 50, classes: 'wrap text-uppercase', canSearch: true, checked: true },
			{ label: 'Description', name: 'description', width: 100, classes: 'wrap text-uppercase', canSearch: true },
			{ label: 'Category', name: 'rpttype', width: 100, classes: 'wrap text-uppercase', canSearch: true },
			{ label: 'adduser', name: 'adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 10, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname: 'idno',
		sortorder: 'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			// $('#error_infront').text('');
			
			urlParam2.rptname = selrowData("#jqGrid").rptname;
			refreshGrid("#jqGrid2",urlParam2);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			
			fdl.set_array().reset();
		},
		loadComplete:function(data){
			// calc_jq_height_onchange("jqGrid");
		}
	});
	
	/////////////////////////////////////////start grid pager/////////////////////////////////////////
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
			urlParam2.rptname = $('#rptname').val();
			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			urlParam2.rptname = $('#rptname').val();
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
	
	////////////////////////////handle searching, its radio button and toggle////////////////////////////
	// populateSelect('#jqGrid', '#searchForm');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);
	
	/////////////////////////////add field into param, refresh grid if needed/////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam);
	
	//////////////////////////////////////////hide at dialogForm//////////////////////////////////////////
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
	
	///////////////////////////////////////////////saveHeader///////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;
		
		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			// console.log(data);
			// alert(data.responseJSON.message);
			$('.noti').text(data.responseJSON.message);
		}).done(function (data) {
			$("#saveDetailLabel").attr('disabled',false);
			unsaved = false;
			hideatdialogForm(false);
			addmore_jqgrid2.state = true;
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			
			if (selfoper == 'add') {
				oper = 'edit';  // sekali dia add terus jadi edit lepas tu
				
				$('#idno').val(data.idno);   // just save idno for edit later
				
				urlParam2.rptname = data.rptname;
			} else if (selfoper == 'edit') {
				urlParam2.rptname = $('#rptname').val();
				// doesnt need to do anything
			}
			disableForm('#formdata');
		})
	}
	
	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; // kalau dia change apa2 bagi prompt
	});
	
	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; // kalau dia change apa2 bagi prompt
	});
	
	//////////////////////////////////////////parameter for jqgrid2 url//////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url: 'reportFormatDetail/table',
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
			{ label: 'Print Flag', name: 'printflag', width: 50, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "Y:YES;N:NO"
				}
			},
			{ label: 'Row Def', name: 'rowdef', width: 60, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "H:Header;D:Detail;S:Spacing;TO:Total",
					// dataEvents: [{
					// 	type: 'change',
					// 	fn: function (e) {
					// 		alert("");
					// 	}
					// }]
				}
			},
			// { label: 'Code', name: 'code', width: 50, classes: 'wrap', editable: true },
			{ label: 'Code', name: 'code', width: 100, classes: 'wrap', canSearch: false, editable: true,
				editrules: { required: false, custom: true, custom_func: cust_rules }, formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: codeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Note', name: 'note', width: 50, classes: 'wrap', editable: true },
			{ label: 'Description', name: 'description', width: 150, classes: 'wrap', editable: true },
			{ label: 'Formula', name: 'formula', width: 80, classes: 'wrap', editable: true },
			{ label: 'Cost Code From', name: 'costcodefr', width: 50, classes: 'wrap', editable: true },
			{ label: 'Cost Code To', name: 'costcodeto', width: 50, classes: 'wrap', editable: true },
			{ label: 'Reverse Sign', name: 'revsign', width: 50, classes: 'wrap', editable: true },
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
		rowNum: 10,
		sortname: 'lineno_',
		sortorder: "asc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			
			setjqgridHeight(data,'jqGrid2');
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false;    // reset
			calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			fdl.set_array().reset();
			// fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
		},
		beforeSubmit: function (postdata, rowid) {
			// dialog_paymode.check(errorField);
		}
	})
	
	////////////////////////////////////////////set label jqGrid2 right////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");
	
	/////////////////////////////////////////////////myEditOptions/////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			// $("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
			
			dialog_code.on();
			
			unsaved = false;
			
			// By default, rowdef is set to Header. So show only description
			$("#jqGrid2 input[name='description']").show();
			$("#jqGrid2 input[name='code'], .input-group-addon, #jqGrid2 input[name='note'], #jqGrid2 input[name='formula'], #jqGrid2 input[name='costcodefr'], #jqGrid2 input[name='costcodeto'], #jqGrid2 input[name='revsign']").hide();
			
			$("select[name='rowdef']").change(function(){
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
			
			$("input[name='revsign']").keydown(function(e) {	// when click tab at revsign, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// $('#db_amount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true;	// only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			urlParam2.rptname = $('#rptname').val();
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			urlParam2.rptname = $('#rptname').val();
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./reportFormatDetail/form?"+
				$.param({
					action: 'reportFormat_detail_save',
					idno: $('#idno').val(),
					rptname: $('#rptname').val(),
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm(false);
		},
		errorTextFormat: function (data) {
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
								action: 'reportFormat_detail_save',
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./reportFormatDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()},
							function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								urlParam2.rptname = $('#rptname').val();
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
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				$("#jqGrid2").jqGrid('editRow',ids[i]);
				
				dialog_code.id_optid = ids[i];
				dialog_code.check(errorField,ids[i]+"_code","jqGrid2",null,
					function(self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
					}
				);
				
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
				
				// $("#jqGrid2 select#"+ids[i]+"_rowdef").change(function(){
				// 	var rowdef1 = $("#jqGrid2 select#"+ids[i]+"_rowdef").val();
					
				// 	if(rowdef1 == 'D'){	// show
				// 		$(".input-group#"+ids[i]+"_code").show();
				// 	}else{	// hide
				// 		$(".input-group#"+ids[i]+"_code").hide();
				// 	}
				// });
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
				idno: $('#idno').val(),
			}
			
			$.post( "/reportFormatDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				// alert(dialog,data.responseText);
			}).done(function(data){
				// mycurrency.formatOn();
				hideatdialogForm(false);
				urlParam2.rptname = $('#rptname').val();
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
			urlParam2.rptname = $('#rptname').val();
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
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="code" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary" id="'+opt.id+'" name="code"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	///////////////////////////////////////////////////saveDetailLabel///////////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		$("#saveDetailLabel").attr('disabled',true)
		// mycurrency.formatOff();
		// mycurrency.check0value(errorField);
		unsaved = false;
		
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			// mycurrency.formatOn();
			unsaved = false;
		} else {
			// mycurrency.formatOn();
			// dialog_CustomerDN.on();
		}
	});
	
	////////////////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		// dialog_CustomerDN.on();
		
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		urlParam2.rptname = $('#rptname').val();
		refreshGrid("#jqGrid2", urlParam2);
	});
	
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
			ondblClickRow: function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_note").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_code").val();
				var rows = data.rows;
				var gridname = '#'+obj.gridname;
				
				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.code.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
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
			title: "Select Code",
			open: function(){
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
		Object.keys(data).every(function(v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}
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