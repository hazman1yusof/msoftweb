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
	var mycurrency =new currencymode(['#minlimit','#maxlimit']);

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
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					hideOne('#formdata');
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}

			if(oper == 'add'){
				$('#dtl_authorid').prop('readonly',false);
				dialog_deptcode.on();
				dialog_authorid.on();
			}
			
			if(oper=='edit'){
				$('#dtl_authorid').prop('readonly',true);
				dialog_deptcode.on();
			}
			
			if(oper=='view'){
				dialog_deptcode.check(errorField);
				dialog_authorid.check(errorField);

			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
			dialog_deptcode.off();
			dialog_authorid.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",buttItem1);
			}
		},
		buttons :butt1,
	});

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	
	var urlParam={
	   	action:'get_table_default',
		url:'/util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['material.authdtl as dtl'],
		table_id:'idno',
		filterCol:['compcode', 'cando'],
		filterVal:['session.compcode', 'ACTIVE']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////

	var saveParam = {
		action: 'save_table_default',
		url: '/authorizationDtl/form',
		field: '',
		oper: oper,
		table_name: ['material.authdtl as dtl'],
		table_id: 'idno',
		saveip:'true',
		checkduplicate:'true'
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
           	{ label: 'Author ID', name: 'dtl_authorid', width: 200, classes: 'wrap', canSearch: true, hidden:false},
			{ label: 'idno', name: 'dtl_idno', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'Type', name: 'dtl_trantype', width: 200, classes: 'wrap', canSearch: true},
			{ label: 'Department', name: 'dtl_deptcode', width: 200, classes: 'wrap', canSearch: true, editable: true},
			{ label: 'Authorize Status', name: 'dtl_recstatus', width: 150, classes: 'wrap', canSearch: true, editable: true},
			{ label: 'Recstatus', name: 'dtl_cando', width: 150, classes: 'wrap', canSearch: false, editable: true},
			{ label: 'Min Limit', name: 'dtl_minlimit', width: 200, classes: 'wrap',  align: 'right', editable: true},
			{ label: 'Max Limit', name: 'dtl_maxlimit', width: 200, classes: 'wrap', align: 'right',formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }
			},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		sortname: 'dtl_idno',
		sortorder: 'desc',
		loadonce:false,
		height: 350,
		rowNum: 80,
		pager: "#jqGridPager",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},
	});
	
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam);
			},
			
		}	
	);

	
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, { 'idno': selrowData('#jqGrid').idno });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			candoDisable();
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	function candoDisable(cando = 'cando'){
		var candovalue = $("#formdata [name='"+cando+"']:checked").val();
		if(candovalue == 'A'){
			$("#formdata input[name='"+cando+"']").prop('disabled', true);
		}else{
			$("#formdata input[name='"+cando+"']").prop('disabled', false);
		}
	}

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid',false,saveParam, ['dtl_idno','dtl_compcode','dtl_adduser','dtl_adddate','dtl_upduser','dtl_upddate','dtl_recstatus', 'dtl_cando']);

	/////////////////////dialog handler///////////////////////////////////////////////////////

	var dialog_deptcode = new ordialog(
	'deptcode','sysdb.department','#dtl_deptcode',errorField,
	{	
		colModel:[
			{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			{label:'Unit',name:'sector'},
		],
		urlParam: {
			filterCol:['storedept', 'recstatus','compcode','sector'],
			filterVal:['1', 'ACTIVE', 'session.compcode', 'session.unit']
		},
		ondblClickRow:function(){
			
		},
		gridComplete: function(obj){
			let str = $(obj.textfield).val() ? $(obj.textfield).val() : '';
			if(str.toUpperCase() == 'ALL' && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
				obj.ontabbing = false;
			}

			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}
		}
	},{
		title:"Select Department",
		open: function(){
			dialog_deptcode.urlParam.filterCol=['storedept', 'recstatus','compcode','sector'];
			dialog_deptcode.urlParam.filterVal=['1', 'ACTIVE', 'session.compcode', 'session.unit'];
		}
		
	},'none','radio','tab');
	dialog_deptcode.makedialog();

	var dialog_authorid = new ordialog(
	'authorid','material.authorise','#dtl_authorid',errorField,
	{	
		colModel:[
			{label:'Authorise ID',name:'authorid',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Name',name:'name',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
		],
		urlParam: {
			filterCol:['recstatus','compcode'],
			filterVal:['ACTIVE', 'session.compcode']
		},
		ondblClickRow:function(){
			$('#dtl_deptcode').focus();
		},
		gridComplete: function(obj){
			let str = $(obj.textfield).val() ? $(obj.textfield).val() : '';
			if(obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
				obj.ontabbing = false;
			}

			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$('#dtl_deptcode').focus();
			}
		}
	},{
		title:"Select Department",
		open: function(){
			dialog_authorid.urlParam.filterCol=['recstatus','compcode'];
			dialog_authorid.urlParam.filterVal=['ACTIVE', 'session.compcode'];
		}
	},'none','radio','tab');

	dialog_authorid.makedialog();

	
	///////////////////utk dropdown search By/////////////////////////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['selected']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
		});
	}

	$('#searchText').keyup(function() {
		delay(function(){
			searchMain($('#searchText').val(),$('#Scol').val());
		}, 500 );
	});

	$('#Scol').change(function(){
		searchMain($('#searchText').val(),$('#Scol').val());
	});

	function searchMain(Stext,Scol){

		if(Scol == 'itemcode'){
			$('#searchText').prop('disabled',true);
			urlParam.searchCol=null;
			urlParam.searchVal=null;
		}else{
			$('#searchText').prop('disabled',false);

			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if(Stext.trim() != ''){
				var split = Stext.split(" "),searchCol=[],searchVal=[];
				$.each(split, function( index, value ) {
					searchCol.push(Scol);
					searchVal.push('%'+value+'%');
				});
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}
		}
     	refreshGrid('#jqGrid',urlParam);
	}

	addParamField('#jqGrid',true,urlParam);

	
});
