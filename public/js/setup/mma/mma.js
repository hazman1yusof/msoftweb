$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					//element : $('#'+errorField[0]),
					message: ' '
				}
			}
		},
	};

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'mma-table',
		url: '/mma/table'
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'MMA Code', name: 'mmacode', width: 15, canSearch: true, checked: true, editable: true, editrules: { required: true }},
			{ label: 'Description', name: 'description', width: 80, canSearch: true, hidden:true},
			{ label: 'Description', name: 'description_show', classes: 'wrap', width: 80, checked: true, editable: true, edittype: "textarea", editrules: { required: true }, editoptions: {style: "width: -webkit-fill-available;" ,rows: 5}},
			{ label: 'Version', name: 'version', width: 20, canSearch: true},
			{ label: 'Record Status', name: 'recstatus', width: 30, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
			editoptions:{
				value:"A:ACTIVE;D:DEACTIVE"
			}},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'lastuser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'lastupdate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},

		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			populate_formMMA(selrowData("#jqGrid"));
		},
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
			if(addmore_jqgrid.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			empty_formMMA();
		},
	});

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

			// let selrow = $("#jqGrid").jqGrid ('getRowData', rowid);

			// $('textarea[name=description_show]').val(selrow.description)

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			console.log(data);

			let editurl = "/mma/form?"+
				$.param({
					action: 'mma_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
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
			$("input[name='Code']").attr('disabled','disabled');
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});

			let selrow = $("#jqGrid").jqGrid ('getRowData', rowid);

			$('textarea[name=description_show]').val(selrow.description)

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid',urlParam2,'add');
		},
		beforeSaveRow: function (options, rowid) {
			console.log(errorField)
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "/mma/form?"+
				$.param({
					action: 'mma_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};


	$("#jqGrid").inlineNav('#jqGridPager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
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
								action: 'mma_save',
								Code: $('#Code').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "/race/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
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
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});


	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		table_name: 'hisdb.mmaprice',
		table_id: 'idno',
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

	$("#jqGrid3").jqGrid({
		datatype: "local",
		editurl: "/mma/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Effective date', name: 'effectdate', width: 130, classes: 'wrap', editable:true,
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
						});
					}
				}
			},
			{ label: 'MMA Code', name: 'mmacode', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			// { label: 'Version', name: 'version', width: 100, align: 'right', classes: 'wrap', editable:true,
			// 	edittype:"text",
			// 	editoptions:{
			// 		maxlength: 100,
			// 	},
			// },
			{ label: 'MMA Consult', name: 'mmaconsult', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'MMA Surgeon', name: 'mmasurgeon', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'MMA Anaes', name: 'mmaanaes', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Fees Consult', name: 'feesconsult', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Fees Surgeon', name: 'feessurgeon', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'Fees Anaes', name: 'feesanaes', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,
				},
			},
			{ label: 'recstatus', name: 'recstatus', width: 20, classes: 'wrap', hidden:true},
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
			if(addmore_jqgrid2.more == true){$('#jqGrid3_iladd').click();}
			else{
				$('#jqGrid3').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		gridComplete: function(){

			// fdl.set_array().reset();
			// if(!hide_init){
			// 	hide_init=1;
			// 	hideatdialogForm_jqGrid3(false);
			// }
		}
	});
	// var hide_init=0;

	//////////////////////////////////////////myEditOptions2/////////////////////////////////////////////

	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").hide();

			// dialog_dtliptax.on();
			// dialog_dtloptax.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid3 input[name='feesconsult']","#jqGrid3 input[name='feessurgeon']","#jqGrid3 input[name='feesanaes']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

	//      	$("input[name='dtl_maxlimit']").keydown(function(e) {//when click tab at document, auto save
			// 	var code = e.keyCode || e.which;
			// 	if (code == '9')$('#jqGrid2_ilsave').click();
			// })
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3EditAll,#jqGridPager3Delete,#jqGridPager3Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid3',urlParam2,'add');
			$("#jqGridPager3Delete,#jqGridPager3Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid3').jqGrid ('getRowData', rowid);
			let editurl = "/mma/form?"+
				$.param({
					action: 'mma_save',
					oper: 'add',
					// chgcode: selrowData('#jqGrid').cm_chgcode,//$('#cm_chgcode').val(),
					// uom: selrowData('#jqGrid').cm_uom//$('#cm_uom').val(),
					// authorid:$('#authorid').val()
				});
			$("#jqGrid3").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			// hideatdialogForm_jqGrid3(false);
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
								action: 'mma_save',
								idno: selrowData('#jqGrid3').idno,

							}
							$.post( "/mma/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
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

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_feesconsult","#"+ids[i]+"_feessurgeon","#"+ids[i]+"_feesanaes"]);
			}
			mycurrency2.formatOnBlur();
			// onall_editfunc();
			// hideatdialogForm_jqGrid3(true,'saveallrow');
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
					'effectdate' : $("#jqGrid3 input#"+ids[i]+"_effectdate").val(),
					'mmacode' : $("#jqGrid3 input#"+ids[i]+"_mmacode").val(),
					'version' : $("#jqGrid3 input#"+ids[i]+"_version").val(),
					'mmaconsult' : $("#jqGrid3 input#"+ids[i]+"_mmaconsult").val(),
					'mmasurgeon' : $("#jqGrid3 input#"+ids[i]+"_mmasurgeon").val(),
					'mmaanaes' : $("#jqGrid3 input#"+ids[i]+"_mmaanaes").val(),
					'feesconsult' : $("#jqGrid3 input#"+ids[i]+"_feesconsult").val(),
					'feessurgeon' : $("#jqGrid3 input#"+ids[i]+"_feessurgeon").val(),
					'feesanaes' : $("#jqGrid3 input#"+ids[i]+"_feesanaes").val(),
				}

				jqgrid3_data.push(obj);
			}

			var param={
				action: 'mma_save',
				_token: $("#_token").val()
			}

			$.post( "/mma/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid3_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				// hideatdialogForm_jqGrid3(false);
				refreshGrid("#jqGrid3",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager3",{
		id: "jqGridPager3CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			// hideatdialogForm_jqGrid3(false);
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

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
});

function populate_formMMA(obj){

	//panel header
	$('#mmacode_show').text(obj.mmacode);
	$('#description_show').text(obj.description_show);	
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").show();
	
}

function empty_formMMA(){

	$('#mmacode_show').text('');
	$('#description_show').text('');
	// $("#btn_grp_edit_ti, #btn_grp_edit_ad, #btn_grp_edit_tpa").hide();
	// $("#cancel_ti, #cancel_ad, #cancel_tpa").click();

	// disableForm('#formMMA');
	// emptyFormdata(errorField_MMA,'#formMMA')

}