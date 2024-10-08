
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
	var err_reroll = new err_reroll('#gridCheqRegDetail',['startno','endno']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.bank',
		table_id:'bankcode',
		filterCol:['recstatus', 'compcode'],
		filterVal:['ACTIVE', 'session.compcode'],
		sort_idno:true,
	}
	
	//////////////////////////start grid/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./cheqreg/form",
		 colModel: [
			{ label: 'Bank Code', name: 'bankcode', width: 5,canSearch:true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Bank Name', name: 'bankname', width: 10, canSearch:true, checked: true, hidden: true},
			{ label: 'Address', name: 'address1', width: 17, classes: 'wrap', formatter:formatterAddress, unformat: unformatAddress},
			{ label: 'address2', name: 'address2', width: 17, classes: 'wrap', hidden:true},
			{ label: 'address3', name: 'address3', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'postcode', name: 'postcode', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'statecode', name: 'statecode', width: 17, classes: 'wrap',  hidden:true},
			{ label: 'Tel No', name: 'telno', width: 5},	
			{ label: 'idno', name: 'idno', hidden: true},
			{ label: 'compcode', name: 'compcode', hidden: true},
		],
		autowidth:true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		pager: "#jqGridPager",
		gridComplete: function () {
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);

				$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
			empty_form();

		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_cheqregdtl.filterVal[0]=selrowData("#jqGrid").bankcode;
				populate_form(selrowData("#jqGrid"));
				refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl);
				$("#pg_jqGridPager2 table").show();
			}
		},

		
	});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam);
			},
			
		}	
	);

	//////////////////////////////////////xxformatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;

		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('cheqreg',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	/////////////////formatter address///////////////////////////
	function formatterAddress (cellvalue, options, rowObject){
		add1 = rowObject.address1;
		add2 = rowObject.address2;
		add3 = rowObject.address3;
		postcode = rowObject.postcode;
		state = rowObject.statecode;
		fulladdress =  add1 + '</br> ' + add2 + '</br> ' + add3 + ' </br>' + postcode + ' </br>' + state;
		return fulladdress;
	}

	function unformatAddress (cellvalue, options, rowObject){
		return null;
	}

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
		filterCol:['bankcode', 'compcode'],
		filterVal:[$("#bankcode").val(), 'session.compcode'],
		sort_idno: true,
	}

	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#gridCheqRegDetail").jqGrid({
		datatype: "local",
		editurl: "./cheqregDetail/form",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
			{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true,},
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
			{label: 'idno', name: 'idno', hidden: true,editable: true, key:true},
			{label: 'rn', name: 'rn', hidden: true},
		],
		autowidth:true,
		shrinkToFit: true,
		viewrecords: true,
        multiSort: true,
		loadonce: false,
		rownumbers: true,
		sortname: 'startno',
		sortorder:'desc',
		width: 900,
		height: 200,
		//rowNum: 30,
		sord: "desc",
		pager: "#jqGridPager2",
		onSelectRow:function(rowid, selected){
			$("#jqGridPagerCancelAll,#jqGridPagerSaveAll").hide();
			$("#gridCheqRegDetail_iledit").hide();
			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save

			let stat = selrowData("#gridCheqRegDetail").recstatus;
			if(stat=='DEACTIVE'){
				$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			}else{
				$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
			}
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#gridCheqRegDetail_iladd').click();}
			else{
				$("#gridCheqRegDetail").setSelection($("#gridCheqRegDetail").getDataIDs()[0]);
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll.error == true){
				err_reroll.reroll();
			}
			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   
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

			//$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll, #jqGridPagerSaveAll, #jqGridPagerCancelAll").hide();
			$("#gridCheqRegDetail :input[name='startno']").focus();
			$("#gridCheqRegDetail :input[name='endno']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
					if (code == '9')$('#gridCheqRegDetail_ilsave').click();
			});

		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			$('#p_error').text(data.errormsg);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#gridCheqRegDetail').jqGrid ('getRowData', rowid);

			let editurl = "./cheqregDetail/form?"+
				$.param({
					bankcode: selrowData('#jqGrid').bankcode,
					action: 'cheqregDetail_save',
				});
			$("#gridCheqRegDetail").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll, #jqGridPagerSaveAll, #jqGridPagerCancelAll").show();
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
			$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll, #jqGridPagerSaveAll, #jqGridPagerCancelAll").hide();
			$("#gridCheqRegDetail :input[name='startno']").focus();
			$("#gridCheqRegDetail :input[name='endno']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
					if (code == '9')$('#gridCheqRegDetail_ilsave').click();
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll").show();
		},
		errorfunc: function(rowid,response){
			//$('#p_error').text(response.responseText);
			var data = JSON.parse(response.responseText)
			$('#p_error').text(data.errormsg);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#gridCheqRegDetail').jqGrid ('getRowData', rowid);

			let editurl = "./cheqregDetail/form?"+
				$.param({
					bankcode: selrowData('#jqGrid').bankcode,
					action: 'cheqregDetail_save',
				});
			$("#gridCheqRegDetail").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll, #jqGridPagerSaveAll, #jqGridPagerCancelAll").show();
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
		editParams: myEditOptions_edit,
		
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
								//bankcode: $('#bankcode').val(),
								cheqno: $('#cheqno').val(),
								idno: selrowData('#gridCheqRegDetail').idno,
							}
							$.post( "./cheqregDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function (data) {
								$('#p_error').text(data.responseText);
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
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPagerEditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			
			var ids = $("#gridCheqRegDetail").jqGrid('getDataIDs');
		    for (var i = 0; i < ids.length; i++) {

		        $("#gridCheqRegDetail").jqGrid('editRow',ids[i]);
		    }
			hideatdialogForm(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPagerSaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){

			var ids = $("#gridCheqRegDetail").jqGrid('getDataIDs');

			var gridCheqRegDetail_data = [];
		    for (var i = 0; i < ids.length; i++) {

				var data = $('#gridCheqRegDetail').jqGrid('getRowData',ids[i]);

		    	var obj = 
		    	{
		    		'idno' : ids[i],
		    		'startno' : $("#gridCheqRegDetail input#"+ids[i]+"_startno").val(),
		    		'endno' : $("#gridCheqRegDetail input#"+ids[i]+"_endno").val(),
		    		'cheqqty' : $("#gridCheqRegDetail input#"+ids[i]+"_cheqqty").val()
		    	}

		    	gridCheqRegDetail_data.push(obj);
		    }

			var param={
    			action: 'cheqregDetail_save',
				_token: $("#_token").val(),
				bankcode: selrowData('#jqGrid').bankcode,
			
    		}

    		$.post( "/cheqregDetail/form?"+$.param(param),{oper:'edit_all',dataobj:gridCheqRegDetail_data}, function( data ){
			}).fail(function(data) {
				$('#p_error').text(data.responseText);
				////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm(false);
				refreshGrid("#gridCheqRegDetail",urlParam_cheqregdtl);
			});

		},
		afterrestorefunc : function( response ) {
			refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl,'add');
			//$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
		
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPagerCancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#gridCheqRegDetail",urlParam_cheqregdtl);
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

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////gridCheqRegDetail_iladd
	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#gridCheqRegDetail_iledit,#gridCheqRegDetail_iladd,#gridCheqRegDetail_ilcancel,#gridCheqRegDetail_ilsave,#jqGridPagerDelete,#jqGridPagerEditAll").hide();
			$("#jqGridPagerSaveAll,#jqGridPagerCancelAll").show();
		}else if(hide){
			$("#gridCheqRegDetail_iledit,#gridCheqRegDetail_iladd,#gridCheqRegDetail_ilcancel,#gridCheqRegDetail_ilsave,#jqGridPagerDelete,#jqGridPagerEditAll,#jqGridPagerSaveAll,#jqGridPagerCancelAll").hide();
		}else{
			$("#gridCheqRegDetail_iladd,#gridCheqRegDetail_ilcancel,#gridCheqRegDetail_ilsave,#jqGridPagerDelete,#jqGridPagerEditAll").show();
			$("#jqGridPagerSaveAll,#gridCheqRegDetail_iledit,#jqGridPagerCancelAll").hide();
		}
	}

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#gridCheqRegDetail',true,urlParam_cheqregdtl,['action','rn']);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridCheqRegDetail").jqGrid ('setGridWidth', Math.floor($("#gridCheqRegDetail_c")[0].offsetWidth-$("#gridCheqRegDetail_c")[0].offsetLeft-28));
		refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl);
	});


	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll=function(){
			$('#p_error').text(this.errormsg);
			var self = this;

			if(this.old_data.oper == "add"){
				$(this.jqgridname+"_iladd").click();
			}else if(this.old_data.oper == "edit" ){
				$("#gridCheqRegDetail").setSelection(this.old_data.idno);
				$(this.jqgridname+"_iledit").click();
				
			}

			this.data_array.forEach(function(item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
		

	}
	
	function populate_form(obj){

		//panel header
		$('#bankname').text(obj.bankname);	
	}

	function empty_form(){

		$('#bankname_show').text('');
		

	}
});