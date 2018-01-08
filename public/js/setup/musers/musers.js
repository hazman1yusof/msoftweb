
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
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


	////////////////////object for dialog handler//////////////////
	dialog_dept=new makeDialog('sysdb.mgroups','#groupid',['groupid','description'], 'Group ID');

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
				dialog_dept.handler(errorField);
			}
			if(oper!='add'){
				dialog_dept.check(errorField);
			}
		},
		close: function( event, ui ) {
			emptyFormdata(errorField,'#formdata');
			$('.alert').detach();
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
		field:'',
		table_name:'sysdb.musers',
		table_id:'username'
	}

	var saveParam={
		action:'save_table_default',
		field:'',
		oper: oper,
		table_name:'sysdb.musers',
		table_id:'username'
	};

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            {label:'Username',name:'username',width:70,canSearch:true, checked:true},
            {label:'Name',name:'name',width:200,canSearch:true},
            {label:'Group',name:'groupid',width:90,canSearch:true}, 
            {label:'Department',name:'deptcode',width:100},
            {label:'programmenu',name:'programmenu',width:50,hidden:true},
            {label:'password',name:'password',width:50,hidden:true}  
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
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'username':selRowId});
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
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam);

});
