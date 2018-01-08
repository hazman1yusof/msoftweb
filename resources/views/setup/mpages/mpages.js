
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

	var mymodal = new modal();
	//////////////////////////////////////////////////////////////


	////////////////////object for dialog handler//////////////////
	// dialog_dept=new makeDialog('sysdb.groups','#groupid',['groupid','description'], 'Group ID');

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
			inputCtrl("#dialogForm",'#formdata',oper,butt2)
			if(oper!='view'){
				// dialog_dept.handler(errorField);
			}
			if(oper!='add'){
				// dialog_dept.check(errorField);
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
		table_name:'sysdb.mpages',
		table_id:'pageid'
	}

	var saveParam={
		action:'save_table_default',
		field:'',
		oper: oper,
		table_name:'sysdb.mpages',
		table_id:'pageid'
	};

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            {label:'Page ID',name:'pageid',width:70,canSearch:true},
            {label:'Description',name:'description',width:200,canSearch:true},
			{label: 'Enable', name: 'null', width: 50, editable: true, edittype:"checkbox", align:'center'},
            {label:'Add User',name:'adduser',width:90},
            {label:'Add Date',name:'adddate',width:90},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			startEdit()
		},
	});

	var cntrlIsPressed = false;
	$(document).keydown(function(event){
	    if(event.which=="17") cntrlIsPressed = true;
	});

	function startEdit() {
        var ids = $("#jqGrid").jqGrid('getDataIDs');

        for (var i = 0; i < ids.length; i++) {
            $("#jqGrid").jqGrid('editRow',ids[i]);
        }
    };

    function untick_all(){
        var ids = $("#jqGrid").jqGrid('getDataIDs');

        for (var i = 0; i < ids.length; i++) {
        	$('#'+ids[i]+'_null').prop('checked',false);
        }
    };

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
	addParamField('#jqGrid',false,saveParam,['null','adddate','adduser']);


	/////////////////////////////////other function///////////////////////////////////////////////////////
	loadgroupid();
	function loadgroupid(){
		var param={
			action:'get_value_default',
			field:['groupid','description'],
			table_name:'sysdb.mgroups',
			table_id:'groupid'
		}
		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				data.rows.forEach(function(element){
					$('#groupid').append("<option value='"+element.groupid+"'>"+element.description+"</option>");
				});
			}
		});
	}

	$('#groupid').change(function(){
		loadpages_detail($(this).val());
	});

	function loadpages_detail(groupid){
		var param={
			action:'get_value_default',
			field:['idno','groupid','pageid'],
			table_name:'sysdb.mpages_detail',
			table_id:'idno',
			filterCol:['groupid'],
			filterVal:[groupid]
		}
		untick_all();
		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(element){
					$('#'+element.pageid+'_null').prop('checked',true);
				});
			}
		});
	}

	$('#msave').click(function(){
		var groupid = $('#groupid').val();
		if(groupid=='Select here ...')return false;


		mymodal.show("#jqGrid_c","#msave");
		var ids = $("#jqGrid").jqGrid('getDataIDs');
		var data_add=[],data_del=[],objtemp;

        for (var i = 0; i < ids.length; i++) {
    		objtemp = $('#jqGrid').jqGrid ('getRowData', ids[i]);
    		objtemp.null=null;
    		objtemp.groupid=groupid;
        	if($('#'+ids[i]+'_null').prop('checked')){
        		data_add.push(objtemp);
        	}else{
        		data_del.push(objtemp);
        	}
        }
		var param={
			action:'mpages_save'
		}
		$.post( "../../../../assets/php/entry.php?"+$.param(param),{data_add:data_add,data_del:data_del},'json').done(function(data) {
				mymodal.hide();
		});
	});

});
