
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
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

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",'posted',saveParam,urlParam);
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

	var oper = 'add';
	$("#dialogForm")
	  .dialog({ 
		width: 4/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					rdonly("#formdata");
					hideOne("#formdata");
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$(this).dialog("option", "buttons",butt2);
					$('#formdata :input[hideOne]').show();
					break;
			}
			if(oper!='view'){
				dialog_deptcode.on();
			}
			if(oper!='add'){
				//FormData('#jqGrid','#formdata');
				dialog_deptcode.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('.my-alert').detach();
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
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		table_name:'material.ivtxnhd',
		table_id:'debtortycode',
		filterCol:['compcode','source','trantype'],
		filterVal:['session.compcode','IV','JTR'],
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'save_table_default',
		url:'./jtr/form',
		oper:'posted',
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
		 	{label:'idno', name:'idno', hidden: true},
			{label:'compcode', name:'compcode', hidden: true},
			{label:'recno', name:'recno'},
			{label:'source', name:'source'},
			{label:'reference', name:'reference', hidden: true},
			{label:'txndept', name:'txndept', hidden: true},
			{label:'trantype', name:'trantype'},
			{label:'docno', name:'docno'},
			{label:'srcdocno', name:'srcdocno', hidden: true},
			{label:'sndrcvtype', name:'sndrcvtype', hidden: true},
			{label:'sndrcv', name:'sndrcv', hidden: true},
			{label:'trandate', name:'trandate', hidden: true},
			{label:'datesupret', name:'datesupret', hidden: true},
			{label:'dateactret', name:'dateactret', hidden: true},
			{label:'trantime', name:'trantime', hidden: true},
			{label:'ivreqno', name:'ivreqno', hidden: true},
			{label:'amount', name:'amount', hidden: true},
			{label:'respersonid', name:'respersonid', hidden: true},
			{label:'remarks', name:'remarks'},
			{label:'recstatus', name:'recstatus', hidden: true},
			{label:'adduser', name:'adduser'},
			{label:'adddate', name:'adddate'},
			{label:'upduser', name:'upduser'},
			{label:'upddate', name:'upddate', hidden: true},
			{label:'updtime', name:'updtime', hidden: true},
			{label:'unit', name:'unit'},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
		
		
	});


	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerTracking_Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row? RECNO: "+selrowData('#jqGrid').recno,
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if(result == true){
							param = {
								idno: selrowData('#jqGrid').idno,
								recno: selrowData('#jqGrid').recno,
							}
							$.post("./jtr/form?"+$.param(param), {oper: 'cancel', _token: $("#_token").val()}, function (data){
							}).fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								refreshGrid("#jqGrid", urlParam);
							});
						}
					}
				});
			}
		},
	})

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'actdebccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'actdebglacc':field=['glaccno','description'];table="finance.glmasref";break;
			case 'depccode':field=['costcode','description'];table="finance.costcenter";break;
			case 'depglacc':field=['glaccno','name'];table="finance.glmasref";break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('debtortype',options,param,case_,cellvalue);
		return cellvalue;
	}

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);


	////////////////////////////////////ordialog/////////////////////////////////////////////////////////
	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
				{label:'Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
		},
		ondblClickRow: function () {
		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#actdebglacc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}

		},{
			title:"Select Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_deptcode.makedialog(true);

});
		