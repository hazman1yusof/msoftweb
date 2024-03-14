
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	var editedRow=0;

	$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
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
		table_name:'finance.glcondeptgrp',
		table_id:'idno',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./consolidationCostCenter/form',
		field:'',
		oper:oper,
		table_name:'finance.glcondeptgrp',
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
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#formdata :input[rdonly]').prop("readonly",true);
			$("#save").hide();$("#cancel").hide();

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
			}

			urlParam2.filterVal[1]=selrowData("#jqGrid").code;
			$('#code').val(selrowData("#jqGrid").code);
			$('#description').val(selrowData("#jqGrid").description);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			var ids = $("#jqGrid").jqGrid('getDataIDs');
			var cl = ids[0];
			$("#jqGrid").jqGrid('setSelection', cl);
			$("#jqGridPager2_left").hide();
			$("#jqGridPager2_center").hide();
			$("#jqGridPager2_right").hide();
		},
		
	});

	$('#formdata :input[rdonly]').prop("readonly",true);

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		id: "delete",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		id: "view",
		title:"View Selected Year",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			$('#formdata :input[rdonly]').prop("readonly",true);
			$("#save").hide();
			refreshGrid("#jqGrid2",urlParam2, 'add');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		id: "cancel",
		caption:"",cursor: "pointer",position: "last",  
		buttonicon:"glyphicon glyphicon-remove-circle", 
		title:"Cancel", 
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
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			refreshGrid("#jqGrid2",urlParam2, 'add');
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
			$("#jqGridPager2_left").hide();
			$("#jqGridPager2_center").hide();
			$("#jqGridPager2_right").hide();
			emptyFormdata(errorField,'#formdata');
		},
		// gridComplete: function () {
		// 	oper = 'add';
		// 	$("#jqGridPager2_left").show();
		// }
	});

	$('#save').click(function(){
		if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			$("#add").show();$("#edit").show();
			$("#delete").show();$("#view").show();
			$("#save").hide();$("#cancel").hide();
			saveHeader("#formdata",oper,saveParam,urlParam);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			$("#jqGrid").trigger('reloadGrid');
		}
	});

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
				
				$('#idno').val(data.idno);
				$('#code').val(data.code);
				$('#description').val(data.description);

				urlParam2.filterVal[1]=data.code; 
			}else if(selfoper=='edit'){
				//doesnt need to do anything
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
	addParamField('#jqGrid',false,saveParam,['idno','adduser','upduser']);

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$("#jqGridPager_center").hide();
	$("#jqGridPager_right").hide();

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['compcode', 'idno', 'code', 'lineno_', 'costcodefr', 'costcodeto'],
		table_name:['finance.glcondept'],
		table_id:'lineno_',
		filterCol:['compcode','code'],
		filterVal:['session.compcode','']
	};

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./consolidationCostCenterDtl/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, key:true},
			{ label: 'code', name: 'code', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Cost Center From', name: 'costcodefr', width: 200, classes: 'wrap', editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"},},
			{ label: 'Cost Center To', name: 'costcodeto', width: 200, classes: 'wrap', editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
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
		sortorder: "idno",
		pager: "#jqGridPager2",
		loadComplete: function(data){

			// if(addmore_jqgrid2.more == true){
			// 	$('#jqGrid2_iladd').click();
			// }else if($('#jqGrid2').data('lastselrow') == 'none'){
			// 	$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			// }else{
			// 	$("#jqGrid2").setSelection($('#jqGrid2').data('lastselrow'));
			// 	$('#jqGrid2 tr#' + $('#jqGrid2').data('lastselrow')).focus();
			// }

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
		},
	});

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
		id: "jqGridPagerDelete2",
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
								action: 'consolidationCostCenterDtl_save',
								code: $('#code').val(),
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./consolidationCostCenterDtl/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid2", urlParam2);
							});
						}else{
							$("#jqGridPagerDelete2,#jqGridPagerRefresh2").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPagerRefresh2",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid2", urlParam2);
		},
	});

	//////////////////////////My edit options /////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			// $('#jqGrid2').data('lastselrow','none');
			$("#jqGridPagerDelete2,#jqGridPagerRefresh2").hide();
			$("jqGrid input[name='acctto']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				/*addmore_jqgrid2.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			// $("#jqGrid2 input[type='text']").on('focus',function(){
			// 	$("#jqGrid2 input[type='text']").parent().removeClass( "has-error" );
			// 	$("#jqGrid2 input[type='text']").removeClass( "error" );
			// });

		},
		aftersavefunc: function (rowid, response, options) {
			// //if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
			// addmore_jqgrid2.more = true;
			// //state true maksudnyer ada isi, tak kosong
			// refreshGrid('#jqGrid2',urlParam2,'add');
			// errorField.length=0;
			// $("#jqGridPagerDelete2,#jqGridPagerRefresh2").show();
			// var resobj = JSON.parse(response.responseText);
			// $('#code').val(resobj.code);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline

			// urlParam2.filterVal[1]=resobj.code;
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./consolidationCostCenterDtl/form?"+
				$.param({
					action: 'consolidationCostCenterDtl_save',
					code: $('#code').val(),
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPagerDelete2,#jqGridPagerRefresh2").show();
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
			$('#jqGrid').data('lastselrow',rowid);
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("jqGrid2 input[name='acctto']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				/*addmore_jqgrid2.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			refreshGrid('#jqGrid',urlParam,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./consolidationCostCenterDtl/form?"+
				$.param({
					action: 'consolidationCostCenterDtl_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid',urlParam,'edit');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

});		