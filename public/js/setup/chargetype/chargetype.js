$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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

	var fdl = new faster_detail_load();
	// var err_reroll = new err_reroll('#jqGrid',['chgtype', 'description']);
		
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

	var oper='add';
	$("#dialogForm")
		.dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					enableForm('#formdata');
					rdonly('#formdata');
					frozeOnEdit("#dialogForm");
					recstatusDisable();
					break;
				case state = 'view':
					disableForm('#formdata');
					break;
			}
			if(oper!='view'){
				set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
				dialog_chggroup.on();
				dialog_ipdept.on();
				dialog_opdept.on();
				dialog_ipacccode.on();
				dialog_opacccode.on();
				dialog_otcacccode.on();
				dialog_invcategory.on();
			}
			if(oper!='add'){
				dialog_chggroup.check(errorField);
				dialog_ipdept.check(errorField);
				dialog_opdept.check(errorField);
				dialog_ipacccode.check(errorField);
				dialog_opacccode.check(errorField);
				dialog_otcacccode.check(errorField);
				dialog_invcategory.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			//$('.alert').detach();
			$('.my-alert').detach();
			dialog_chggroup.off();
			dialog_ipdept.off();
			dialog_opdept.off();
			dialog_ipacccode.off();
			dialog_opacccode.off();
			dialog_otcacccode.off();
			dialog_invcategory.off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

	var urlParam = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'hisdb.chgtype',
		table_id: 'idno',
		sort_idno: true,
		filterCol:['compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./chargetype/form',
		field:'',
		oper:oper,
		table_name:'hisdb.chgtype',
		table_id:'idno',
		saveip:'true',
		checkduplicate:'true'
	};
		
	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{ label: 'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label: 'Compcode', name: 'compcode', hidden:true},
			{ label: 'Charge Type', name: 'chgtype', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Description', name: 'description', classes: 'wrap', width: 70, canSearch: true, checked:true},
			{ label: 'Last User', name: 'upduser', classes: 'wrap', width: 30},
			{ label: 'Last Update', name: 'upddate', classes: 'wrap', width: 20},
			{ label: 'Sequence Number', name: 'seqno', classes: 'wrap', width: 20},
			{ label: 'Charge Group', name: 'chggroup', classes: 'wrap', width: 20, canSearch: true},
			{ label: 'ipacccode', name: 'ipacccode', hidden:true},
			{ label: 'opacccode', name: 'opacccode', hidden:true},
			{ label: 'otcacccode', name: 'otcacccode', hidden:true},
			{ label: 'ipdept', name: 'ipdept', hidden:true},
			{ label: 'opdept', name: 'opdept', hidden:true},
			{ label: 'invcategory', name: 'invcategory', hidden:true},
			{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:false,
			cellattr: function (rowid, cellvalue)
			{ return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"' : '' },},
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			//urlParam2.filterVal[1]=selrowData("#jqGrid").cm_chgcode;
			// refreshGrid("#jqGrid3",urlParam2);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			// $('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
		},
	});

	/////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['checked']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
		});
		searchClickChange('#jqGrid','#searchForm',urlParam);
	}

	function searchClickChange(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			}, 500 );
		});
	}

	$('#Scol').on('change', scolChange);

	function scolChange() {
		if($('#Scol').val()=='chggroup'){
			$("input[name='Stext']").hide("fast");
			$("#show_chggroup_div").show("fast");
			
			$("#show_chggroup").attr('seltext',true);
			$("input[name='Stext']").attr('seltext',false);
		} else {
			$("input[name='Stext']").show("fast");
			$("#show_chggroup_div").hide("fast");

			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });

			$("#show_chggroup").attr('seltext',false);
			$("input[name='Stext']").attr('seltext',true);
		}
		
		search('#jqGrid',$('#searchForm input[seltext=true]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
	}

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick_('#jqGrid','#searchForm',urlParam);

	function searchClick_(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				if($(form+' [name=Scol] option:selected').val() == 'description'){
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			if($(form+' [name=Scol] option:selected').val() == 'description'){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			}else{
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			}
		});
	}

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno', 'compcode', 'ipaddress', 'computerid', 'adddate', 'adduser','upduser','upddate','recstatus']);

	/////////////////////////start grid pager/////////////////////////////////////////// //////////////

	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,oper);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			let idno = selrowData('#jqGrid').idno;
			if(!idno){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':idno});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
			// refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
			// refreshGrid("#jqGrid2",urlParam2);
			recstatusDisable();
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		id: 'glyphicon-plus',
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			//console.log(rowid);
			/*linenotoedit = rowid;
			$("#jqGrid2").find(".rem_but[data-lineno_!='"+linenotoedit+"']").prop("disabled", true);
			$("#jqGrid2").find(".rem_but[data-lineno_='undefined']").prop("disabled", false);*/
		},
		aftersavefunc: function (rowid, response, options) {
			$('#amount').val(response.responseText);
			// if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			// if(addmore_jqgrid2.edit == false)linenotoedit = null; 
			//linenotoedit = null;

			// refreshGrid('#jqGrid2',urlParam2,'add');
			// $("#jqGridPager2Delete").show();
		}, 
		beforeSaveRow: function(options, rowid) {
			/*if(errorField.length>0)return false;

			let data = selrowData('#jqGrid2');
			let editurl = "/inventoryTransactionDetail/form?"+
				$.param({
					action: 'invTranDetail_save',
					docno:$('#docno').val(),
					recno:$('#recno').val(),
				});*/
			// $("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			/*hideatdialogForm(false);*/
		}
	};

	//////////////////////////////////////////////////////////////////////////////////////////////////////

	var show_chggroup = new ordialog(
		'show_chggroup', 'hisdb.chggroup', '#show_chggroup', 'errorField',
		{
			colModel: [
				{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer',  canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + show_chggroup.gridname).grpcode;

				urlParam.searchCol=["chggroup"];
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
			title: "Select Group Code",
			open: function () {
				show_chggroup.urlParam.filterCol = ['compcode','recstatus'];
				show_chggroup.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	show_chggroup.makedialog();
	show_chggroup.on();

	var dialog_chggroup= new ordialog(
		'chggroup','hisdb.chggroup','#chggroup',errorField,
		{	colModel:[
				{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#ipdept').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#ipdept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Group Code",
			open: function(){
				dialog_chggroup.urlParam.filterCol=['compcode','recstatus'];
				dialog_chggroup.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_chggroup.makedialog();

	var dialog_ipdept= new ordialog(
		'ipdept','sysdb.department','#ipdept',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#opdept').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#opdept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Department Code",
			open: function(){
				dialog_ipdept.urlParam.filterCol=['compcode','recstatus'];
				dialog_ipdept.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_ipdept.makedialog();	

	var dialog_opdept= new ordialog(
		'opdept','sysdb.department','#opdept',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#ipacccode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#ipacccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Department Code",
			open: function(){
				dialog_opdept.urlParam.filterCol=['compcode','recstatus'];
				dialog_opdept.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab',false
	);
	dialog_opdept.makedialog();

	var dialog_ipacccode= new ordialog(
		'ipacccode','finance.glmasref','#ipacccode',errorField,
		{	colModel:[
				{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#opacccode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#opacccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Glaccno",
			open: function(){
				dialog_ipacccode.urlParam.filterCol=['compcode','recstatus'];
				dialog_ipacccode.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_ipacccode.makedialog();

	var dialog_opacccode= new ordialog(
		'opacccode','finance.glmasref','#opacccode',errorField,
		{	colModel:[
				{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#otcacccode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#otcacccode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Glaccno",
			open: function(){
				dialog_opacccode.urlParam.filterCol=['compcode','recstatus'];
				dialog_opacccode.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_opacccode.makedialog();

	var dialog_otcacccode= new ordialog(
		'otcacccode','finance.glmasref','#otcacccode',errorField,
		{	colModel:[
				{label:'Glaccno',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#invcategory').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#invcategory').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Glaccno",
			open: function(){
				dialog_otcacccode.urlParam.filterCol=['compcode','recstatus'];
				dialog_otcacccode.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab'
	);
	dialog_otcacccode.makedialog();

	var dialog_invcategory= new ordialog(
		'invcategory','material.category','#invcategory',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#recstatus').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#recstatus').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Category Code",
			open: function(){
				dialog_invcategory.urlParam.filterCol=['compcode','recstatus'];
				dialog_invcategory.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam','radio','tab', false
	);
	dialog_invcategory.makedialog();
});