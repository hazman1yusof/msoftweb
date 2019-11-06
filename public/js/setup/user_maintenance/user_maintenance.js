
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

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
	//////////////////////////////////////////////////////////////

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
		width: 6/10 * $(window).width(),
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
				dialog_txndept.on();
				dialog_deptcode.on();
			}
			if(oper!='add'){
				setColor();
				dialog_txndept.check(errorField);
				// dialog_deptcode.check(errorField);
			}
		},
		close: function( event, ui ) {
			emptyFormdata(errorField,'#formdata');

			dialog_txndept.off();
			dialog_deptcode.off();

			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'user_maintenance',
		url:'/user_maintenance/table'
	}

	var saveParam={
		action:'save_table_default',
		url:'/user_maintenance/form'
	};

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            {label:'Username',name:'username',width:70,canSearch:true, checked:true},
            {label:'Name',name:'name',width:200,canSearch:true},
            {label:'Group',name:'groupid',width:90,canSearch:true}, 
            {label:'Department',name:'deptcode',width:100}, 
            {label:'Cashier',name:'cashier',formatter:yes_no,unformat:de_yes_no,width:60},   
            {label:'Price View',name:'priceview',formatter:yes_no,unformat:de_yes_no,width:70},   
            {label:'programmenu',name:'programmenu',width:50,hidden:true},
            {label:'password',name:'password',width:50,hidden:true},
            {label:'id',name:'id',width:50,hidden:true},
            {label:'ALcolor',name:'ALcolor',hidden:true},
            {label:'DiscPTcolor',name:'DiscPTcolor',hidden:true},
            {label:'CancelPTcolor',name:'CancelPTcolor',hidden:true},
            {label:'CurrentPTcolor',name:'CurrentPTcolor',hidden:true},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'groupid',
		sortorder:'asc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			/*if(editedRow!=0){
				$("#jqGrid").jqGrid('setSelection',editedRow,false);
			}*/
		},
		
	});

	function yes_no(cellvalue, options, rowObject){
		if(cellvalue == 1){return 'Yes';}else{return 'No';}
	}
	function de_yes_no(cellvalue, options, rowObject){
		if(cellvalue == 'Yes'){return '1';}else{return '0';}
	}

	var cntrlIsPressed = false;
	$(document).keydown(function(event){
	    if(event.which=="17") cntrlIsPressed = true;
	});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				if(cntrlIsPressed){
					var r= confirm("Data will be permanently deleted, continue?");
					if(r==true)saveFormdata("#jqGrid","#dialogForm","#formdata",'del_hard',saveParam,urlParam,null,{'username':selRowId});
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'id':selrowData('#jqGrid').id});
				}
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
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	refreshGrid('#jqGrid',urlParam);

	var dialog_txndept = new ordialog(
		'groups','sysdb.groups','#groupid',errorField,
		{	colModel:[
				{label:'Group ID',name:'groupid',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				$('#programmenu').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#programmenu').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_txndept.urlParam.filterCol=['compcode','recstatus'],
				dialog_txndept.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_txndept.makedialog();

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
				{label:'Department ID',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
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
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['recstatus'],
				dialog_deptcode.urlParam.filterVal=['A']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_deptcode.makedialog();


	// function savecolor(){
	// 	var bg_leave = $('#bg_leave').val();
	// 	$.post( "/user_maintenance/save_color", {bg_leave:bg_leave,_token:$('#csrf_token').val()} , function( data ) {
	
	// 	}).success(function(data){

	// 	});
	// }

	// function load_bg_leave(){
	// 	var urlParam={
	// 		action:'get_table_default',
	// 		url: '/util/get_value_default',
	// 		field:['pvalue1'],
	// 		table_name:'sysdb.sysparam',
	// 		filterCol:['source','trantype'],
	// 		filterVal:['HIS','ALCOLOR']
	// 	}

	// 	$.get( "util/get_value_default"+"?"+$.param(urlParam), function( data ) {
		
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data.rows)){
	// 			$('#bg_leave').val(data.rows[0].pvalue1);
	// 		}
	// 	});
	// }

	$(".colorpointer").click(function(){
		var column = $(this).data('column');
		$('#'+column).click();
	});

	$('.bg_color').change(function(){
		var column = $(this).attr('name');
		$('#pt_'+column).css('background-color',$(this).val());
	});

	function setColor(){
		$('.bg_color').each(function(){
			var column = $(this).attr('name');
			$('#pt_'+column).css('background-color',$(this).val());
		});
	}

});
