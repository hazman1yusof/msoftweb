
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']","input[name='si_lastcomputerid']","input[name='si_lastipaddress']","input[name='si_computerid']","input[name='si_ipaddress']","input[name='sb_lastcomputerid']","input[name='sb_lastipaddress']","input[name='sb_computerid']","input[name='sb_ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
	    modules : 'sanitize',
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

	var fdl = new faster_detail_load();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.bank',
		table_id:'bankcode',
		sort_idno:true,
	}
	
	//////////////////////////start grid/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/cheqreg/form",
		 colModel: [
			{ label: 'Bank Code', name: 'bankcode', width: 8,canSearch:true,checked:true},
			{ label: 'Bank Name', name: 'bankname', width: 20, canSearch:true},
			{ label: 'Address', name: 'address1', width: 17},
			{ label: 'Tel No', name: 'telno', width: 10},	
			{ label: 'idno', name: 'idno', hidden: true},
		],
		autowidth:true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 100,
		//rowNum: 30,
		pager: "#jqGridPager",
		loadComplete: function(){
			/*if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
				else{
						$('#jqGrid2').jqGrid ('setSelection', "1");
					}

				addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset*/
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			//$("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_cheqregdtl.filterVal[0]=selrowData("#jqGrid").bankcode;
				refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl);
				$("#pg_jqGridPager2 table").show();
			}

		},
		
	});

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	////////////////////////////cheq register detail/////////////////////////////////////////////////
	
	var urlParam_cheqregdtl={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.chqreg',
		table_id:'startno',
		filterCol:['bankcode'],
		filterVal:[$("#bankcode").val()],
		sort_idno: true,
	}

	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#gridCheqRegDetail").jqGrid({
		datatype: "local",
		editurl: "/cheqregDetail/form",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
			{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true, editable: true,},
			{ label: 'Start Number', name: 'startno', width: 20, classes: 'wrap', sorttype: 'number', editable: true,
				editrules:{required: true},edittype:"text",canSearch:true,checked:true,
				editoptions:{
					maxlength: 11,
						dataInit: function(element) {
							$(element).keypress(function(e){
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
								return false;
								}
							});
						}
				},
			},
			{ label: 'End Number', name: 'endno', width: 20, classes: 'wrap', sorttype: 'number',editable: true,
				editrules:{required: true},edittype:"text",canSearch:true,
				editoptions:{
					maxlength: 11,
						dataInit: function(element) {
							$(element).keypress(function(e){
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
								return false;
								}
							});
						}
				},
			},
			{ label: 'Cheq Qty', name: 'cheqqty', width: 30, hidden:true,},
			{ label: 'Recstatus', name: 'recstatus', width: 30, hidden:false,},
			{ label: 'Action', name: 'action', hidden: true,width :10,  formatoptions: { keys: false, editbutton: true, delbutton: true }, formatter: 'actions'},
			{label: 'idno', name: 'idno', hidden: true},
			{label: 'rn', name: 'rn', hidden: true},
		],
		autowidth:true,
		viewrecords: true,
        multiSort: true,
		loadonce: false,
		rownumbers: true,
		//sortname: 'startno',
		//sortorder:'desc',
		width: 900,
		height: 200,
		//rowNum: 30,
		sord: "desc",
		pager: "#jqGridPager2",

		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
			else{
				$('#gridCheqRegDetail').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
		},
	});

	//////////////////////////My edit options /////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("#gridCheqRegDetail :input[name='startno']").focus();
			//$("#gridCheqRegDetail :input[name='bankcode']").val(bankcode);
			//$("#gridCheqRegDetail :input[name='bankcode']").attr('readonly', 'readonly');
			$("#gridCheqRegDetail :input[name='endno']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
					if (code == '9')$('#jqGrid_ilsave').click();
					/*addmore_jqgrid.state = true;
					$('#jqGrid_ilsave').click();*/
			});

		},
		aftersavefunc: function (rowid, response, options) {
					//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
					addmore_jqgrid.more = true;
					//state true maksudnyer ada isi, tak kosong
					refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
					errorField.length=0;
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				},
				errorfunc: function(rowid,response){
					//$('#p_error').text(response.responseText);
					refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
				},
				beforeSaveRow: function (options, rowid) {
					//$('#p_error').text('');
					if(errorField.length>0)return false;

					let data = $('#gridCheqRegDetail').jqGrid ('getRowData', rowid);
					console.log(data);

					let editurl = "/cheqregDetail/form?"+
						$.param({
							action: 'cheqregDetail_save',
							//idno: selrowData('#gridCheqRegDetail').idno,
						});
					$("#gridCheqRegDetail").jqGrid('setGridParam', { editurl: editurl });
				},
				afterrestorefunc : function( response ) {
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				},
				errorTextFormat: function (data) {
					alert(data);
				}
			};

			var myEditOptions_edit = {
				keys: true,
				extraparam:{
					"_token": $("#_token").val()
				},
				oneditfunc: function (rowid) {
					$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
					$("#gridCheqRegDetail :input[name='endno']").keydown(function(e) {//when click tab at last column in header, auto save
						var code = e.keyCode || e.which;
						if (code == '9')$('#jqGrid_ilsave').click();
						/*addmore_jqgrid.state = true;
						$('#jqGrid_ilsave').click();*/
					});

				},
				aftersavefunc: function (rowid, response, options) {
					if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
					//state true maksudnyer ada isi, tak kosong
					refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
					errorField.length=0;
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				},
				errorfunc: function(rowid,response){
					$('#p_error').text(response.responseText);
					refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
				},
				beforeSaveRow: function (options, rowid) {
					$('#p_error').text('');
					if(errorField.length>0)return false;

					let data = $('#gridCheqRegDetail').jqGrid ('getRowData', rowid);
					// console.log(data);

					let editurl = "/cheqregDetail/form?"+
						$.param({
							action: 'cheqregDetail_save',
						});
					$("#gridCheqRegDetail").jqGrid('setGridParam', { editurl: editurl });
				},
				afterrestorefunc : function( response ) {
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				},
				errorTextFormat: function (data) {
					alert(data);
				}
			};

			/////////////////////////start grid pager/////////////////////////////////////////////////////////
			$("#gridCheqRegDetail").inlineNav('#jqGridPager2', {
				add: true,
				edit: true,
				cancel: true,
				//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
				restoreAfterSelect: false,
				addParams: {
					addRowParams: myEditOptions
				},
				editParams: myEditOptions_edit
			}).jqGrid('navButtonAdd', "#jqGridPager2", {
				id: "jqGridPagerDelete",
				caption: "", cursor: "pointer", position: "last",
				buttonicon: "glyphicon glyphicon-trash",
				title: "Delete Selected Row",
				onClickButton: function () {
					selRowId = $("#gridCheqRegDetail").jqGrid('getGridParam', 'selrow');
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
										action: 'cheqregDetail_save',
										//uomcode: $('#uomcode').val(),
										idno: selrowData('#gridCheqRegDetail').idno,
									}
									$.post( "/cheqregDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
									}).fail(function (data) {
										//////////////////errorText(dialog,data.responseText);
									}).done(function (data) {
										refreshGrid("#gridCheqRegDetail", urlParam_cheqregdtl);
									});
								}else{
									$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
								}
							}
						});
					}
				},
			}).jqGrid('navButtonAdd', "#jqGridPager2", {
				id: "jqGridPagerRefresh",
				caption: "", cursor: "pointer", position: "last",
				buttonicon: "glyphicon glyphicon-refresh",
				title: "Refresh Table",
				onClickButton: function () {
					refreshGrid("#gridCheqRegDetail", urlParam_cheqregdtl);
				},
			});

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
			
	//toogleSearch('#sbut1','#searchForm','on');
	populateSelect2('#gridCheqRegDetail','#searchForm');
	searchClick2('#gridCheqRegDetail','#searchForm',urlParam_cheqregdtl);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#gridCheqRegDetail',true,urlParam_cheqregdtl);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridCheqRegDetail").jqGrid ('setGridWidth', Math.floor($("#gridCheqRegDetail_c")[0].offsetWidth-$("#gridCheqRegDetail_c")[0].offsetLeft-28));
	});

});