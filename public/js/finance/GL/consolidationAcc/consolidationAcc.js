
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

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

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});
	
	
	
	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
	.dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper!='view'){
			}
			if(oper!='add'){
			}
		},
		close: function( event, ui ) {
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			$("#formdata a").off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.glconsol',
		table_id:'idno',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./consolidationAcc/form',
		field:'',
		oper:oper,
		table_name:'finance.glconsol',
		table_id:'idno',
		checkduplicate:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true},
			{ label: 'idno', name: 'idno', hidden: true, key:true},
			{ label: 'Code', name: 'code', hidden: false,  width: 30, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'Description', name: 'description', hidden: false,  width: 100, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname: 'idno',
		sortorder: 'desc',
		width: 900,
		height: 422,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#formdata :input[rdonly]').prop("readonly",true);

			if($('#cancel').is(':visible')){
				$("#add").show();$("#edit").show();
				$("#delete").show();$("#view").show();
				$("#save").hide();$("#cancel").hide();
			}

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);

				urlParam2.filterVal[1]=selrowData("#jqGrid").code;
				$('#code').val(selrowData("#jqGrid").code);
				$('#description').val(selrowData("#jqGrid").description);
				$('#idno').val(selrowData("#jqGrid").idno);
				$("#jqGridPager2_left").show();
				$("#jqGridPager2_center").show();
				$("#jqGridPager2_right").show();

				refreshGrid("#jqGrid2",urlParam2);
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
			// $('#formdata :input[rdonly]').prop("readonly",false);
			$("#add").hide();$("#edit").hide();
			$("#delete").hide();$("#view").hide();
			$("#save").show();$("#cancel").show();
		},
		gridComplete: function(){
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#save").hide();
				$("#jqGridPager2_left").hide();
				$("#jqGridPager2_center").hide();
				$("#jqGridPager2_right").hide();
			}
		},
		
	});

	$('#formdata :input[rdonly]').prop("readonly",true);

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam, oper);
		},
	// }).jqGrid('navButtonAdd',"#jqGridPager",{
	// 	caption:"",cursor: "pointer",position: "first", 
	// 	buttonicon:"glyphicon glyphicon-trash",
	// 	id: "delete",
	// 	title:"Delete Selected Row",
	// 	onClickButton: function(){
	// 		oper='del';
	// 		selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
	// 	},
	// }).jqGrid('navButtonAdd',"#jqGridPager",{
	// 	caption:"",cursor: "pointer",position: "first", 
	// 	buttonicon:"glyphicon glyphicon-info-sign",
	// 	id: "view",
	// 	title:"View Selected Year",  
	// 	onClickButton: function(){
	// 		oper='view';
	// 		selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
	// 		$("#jqGrid").data('lastselrow',selRowId);
	// 		$('#formdata :input[rdonly]').prop("readonly",true);
	// 		$("#save").hide();
	// 		refreshGrid("#jqGrid2",urlParam2, 'add');
	// 	},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		id: "cancel",
		caption:"",cursor: "pointer",position: "last",  
		buttonicon:"glyphicon glyphicon-remove-circle", 
		title:"Cancel", 
		onClickButton: function(){
			$('#formdata :input[rdonly]').prop("readonly",true);
			$('#formdata :input[frozeOnEdit]').prop("readonly",true);
			$("#add").show();$("#edit").show();
			$("#delete").show();$("#view").show();
			$("#save").hide();$("#cancel").hide();
			refreshGrid("#jqGrid",urlParam, 'edit');
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		id: "save",
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-save", 
		title:"Save", 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		id: "edit",
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			console.log(oper);
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#description").focus();
			$("#jqGrid").data('lastselrow',selRowId);
			$('#formdata :input[rdonly]').prop("readonly",false);
			$('#formdata :input[frozeOnEdit]').prop("readonly",true);
			$("#add").hide();$("#edit").hide();
			$("#delete").hide();$("#view").hide();
			$("#save").show();$("#cancel").show();
			refreshGrid("#jqGrid2",urlParam2);
		}, 
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'add',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#code").focus();
			$("#add").hide();$("#edit").hide();
			$("#delete").hide();$("#view").hide();
			$("#save").show();$("#cancel").show();
			$('#formdata :input[rdonly]').prop("readonly",false);
			$('#formdata :input[frozeOnEdit]').prop("readonly",false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			refreshGrid("#jqGrid2",null,"kosongkan");
		},
	});

	$('#save').click(function(){
		if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			$("#add").show();$("#edit").show();
			$("#delete").show();$("#view").show();
			$("#save").hide();$("#cancel").hide();
			saveHeader("#formdata",oper,saveParam);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			refreshGrid("#jqGrid", urlParam);
		}
	});

	// $("#description").keydown(function(e) {
	// 	var code = e.keyCode || e.which;
	// 	if (code == '9') { // -->for tab
	// 		if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
	// 			$("#add").hide();$("#edit").hide();
	// 			$("#delete").hide();$("#view").hide();
	// 			$("#save").show();$("#cancel").show();
	// 			saveHeader("#formdata",oper,saveParam,urlParam);
	// 			emptyFormdata(errorField,'#formdata');
	// 			$('.my-alert').detach();
	// 			refreshGrid("#jqGrid", urlParam);
	// 		}
	// 		addmore_jqgrid2.state = true;
	// 		$("#jqGridPager2_left").show();
	// 		$('#jqGrid2_iladd').click();
	// 	}
	// });

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj,needrefresh){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
				$("#jqGridPager2_left").show();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				refreshGrid("#jqGrid", urlParam);

				$('#idno').val(data.idno);
				$('#code').val(data.code);
				$('#description').val(data.description);

				urlParam2.filterVal[1]=data.code; 
			}else if(selfoper=='edit'){
				refreshGrid("#jqGrid", urlParam);

				//doesnt need to do anything
				$('#idno').val(data.idno);
				$('#description').val(data.description);
				urlParam2.filterVal[1]=$('#code').val(); 
			}
			disableForm('#formdata');

			if(needrefresh === 'refreshGrid'){
				refreshGrid("#jqGrid", urlParam);
			}
		});
	}

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','adduser','upduser','compcode','']);

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$("#jqGridPager_center").show();
	$("#jqGridPager_right").show();

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['compcode', 'idno', 'code', 'lineno_', 'acctfr', 'acctto'],
		table_name:['finance.glcondtl'],
		table_id:'lineno_',
		filterCol:['compcode','code'],
		filterVal:['session.compcode','']
	};

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	var addmore_jqgrid2={more:false,state:true,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./consolidationAccDtl/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, key:true},
			{ label: 'code', name: 'code', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Account From', name: 'acctfr', width: 200, classes: 'wrap', editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"},},
			{ label: 'Account To', name: 'acctto', width: 200, classes: 'wrap', editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 300,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){

			if(addmore_jqgrid2.more == true){
				$('#jqGrid2_iladd').click();
				$("#jqGridPager2_left").show();

			}else if($('#jqGrid2').data('lastselrow') == 'none'){
				$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			}else{
				$("#jqGrid2").setSelection($('#jqGrid2').data('lastselrow'));
				$('#jqGrid2 tr#' + $('#jqGrid2').data('lastselrow')).focus();
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid2_iledit").click();
		},
		gridComplete: function(){
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			}
		},
	});

	//////////////////////////My edit options /////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			//console.log(rowid);
			$('#jqGrid2').data('lastselrow','none');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").hide();
			$("jqGrid2 input[name='acctto']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				addmore_jqgrid2.state = true;
				$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {	
	    	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./consolidationAccDtl/form?"+
				$.param({
					action: 'consolidationAccDtl_save',
					code: $('#code').val(),
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
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
			$('#jqGrid2').data('lastselrow',rowid);
			$("#jqGridPager2Delete,#jqGridPager2Refresh").hide();
			$("jqGrid2 input[name='acctto']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				addmore_jqgrid2.state = false;
				$('#jqGrid2_ilsave').click();
			});
			$("#jqGrid2 input[type='text']").on('focus',function(){
				$("#jqGrid2 input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid2 input[type='text']").removeClass( "error" );
			});

		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid2.more=false; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'edit');
			errorField.length=0;
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorfunc: function(rowid,response){
			refreshGrid('#jqGrid2',urlParam2,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./consolidationAccDtl/form?"+
				$.param({
					action: 'consolidationAccDtl_save',
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid2',urlParam2,'edit');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
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
								action: 'consolidationAccDtl_save',
								code: $('#code').val(),
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./consolidationAccDtl/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid2", urlParam2);
							});
						}else{
							$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid2", urlParam2);
		},
	});



});	