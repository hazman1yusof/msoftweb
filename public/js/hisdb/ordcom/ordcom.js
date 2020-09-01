$(document).ready(function () {

	var fdl = new faster_detail_load();
	disableForm('#form_ordcom');

	$("#new_ordcom").click(function(){
		hideatdialogForm(false);
	});
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
	var dialog_chgcode = new ordialog(
		'ordcom_chgcode','hisdb.chgmast',"#jqGrid_ordcom input[name='chgcode']",errorField,
		{	colModel:
			[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode'],
				WhereInCol:['chggroup'],
				WhereInVal:[['10','70','35','30']]
			},
			ondblClickRow:function(event){
				//$('#occup').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$('#occup').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			},
		},{
			title:"Select ChargeCode",
			open: function(){
				dialog_chgcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_chgcode.urlParam.filterVal = ['A', 'session.compcode'];
				dialog_chgcode.urlParam.WhereInCol = ['chggroup'];
				dialog_chgcode.urlParam.WhereInVal = [['10','70','35','30']];
			},
		},'urlParam','radio','tab','table'
	);
	dialog_chgcode.makedialog();

	function cust_rules(value,name){
		var temp;
		switch(name){
			case ' ':temp=$("#jqGrid input[name='recstatus']");break;
			case 'Charge Code':temp=$("#jqGrid input[name='chgcodeOrdcom']");break;
			break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'ct_chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bedmanagement',options,param,case_,cellvalue);
		
		if(cellvalue==null)return "";
		return cellvalue;
	}

	function chgcodeOrdcomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}


	/////////////////////parameter for jqgrid4 url/////////////////////////////////////////////////
	
	var urlParam_ordcom={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		fixPost:'true',
		table_name: ['hisdb.chargetrx AS ct','hisdb.chgmast AS cm'],
		join_type:['LEFT JOIN'],
		join_onCol:['cm.chgcode'],
		join_onVal:['ct.chgcode'],
		join_filterCol:[['cm.compcode on =']],
		join_filterVal:[['ct.compcode']],
		filterCol:['ct.compcode'],
		filterVal:['session.compcode'],
	};

	var addmore_jqGrid_ordcom={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqGrid_ordcom, state true kalu

	/////////////////////parameter for saving url////////////////////////////////////////////////

	// var saveParam = {
	// 	action: 'save_table_default',
	// 	url: '/ordcom/form',
	// 	field: '',
	// 	oper: oper,
	// 	table_name: ['hisdb.chargetrx AS ct','hisdb.chgmast AS cm'],
	// 	table_id: 'auditno',
	// 	saveip:'true',
	// 	checkduplicate:'true'
	// };

	$("#jqGrid_ordcom").jqGrid({
		datatype: "local",
		editurl: "/ordcom/form",
		colModel: [
			{ label: 'auditno', name: 'ct_auditno', width: 5, classes: 'wrap', hidden:true},
			{ label: 'compcode', name: 'ct_compcode', width: 5, classes: 'wrap', hidden:true},
			{ label: 'Date', name: 'ct_trxdate', width: 5, classes: 'wrap',editable:true,
				editoptions:{
					readonly: "readonly",
				}
			},
			{ label: 'Time', name: 'ct_trxtime', width: 5, classes: 'wrap',editable:true,
				editoptions:{
					readonly: "readonly",
				}},
			{ label: 'Charge Code', name: 'ct_chgcode', width: 15 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,unformat:un_showdetail,
				edittype:'custom',	editoptions:
					{ 	custom_element:chgcodeOrdcomCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Quantity', name: 'ct_quantity', width: 10,editable:true},
			{ label: 'Issue Department', name: 'ct_isudept', width: 10,editable:true,
				editoptions:{
					readonly: "readonly",
				}},
			{ label: 'Remarks', name: 'ct_remarks', width: 10},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'ct_auditno',
		sortorder: 'desc',
		pager: "#jqGridPager_ordcom",
		onSelectRow:function(rowid, selected){

		},
		loadComplete: function(){
			if(addmore_jqGrid_ordcom.more == true){$('#jqGrid_ordcom_iladd').click();}
			else{
				$('#jqGrid_ordcom').jqGrid ('setSelection', "1");
			}

			addmore_jqGrid_ordcom.edit = addmore_jqGrid_ordcom.more = false; //reset			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){			
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   
		},
		gridComplete: function(){
			fdl.set_array().reset();
			hideatdialogForm(false);
		}
	});

	// $("#jqGrid_ordcom").jqGrid('navGrid','#jqGridPager_ordcom',
	// 	{	
	// 		edit:false,view:false,add:false,del:false,search:false,
	// 		beforeRefresh: function(){
	// 			refreshGrid("#jqGrid",urlParam);
	// 		},
			
	// 	}	
	// );

	//////////////////////////My edit options ORDERCOM/////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager_ordcomDelete,#jqGridPager_ordcomRefresh, #jqGridPager_ordcomEditAll, #jqGridPager_ordcomrSaveAll, #jqGridPager_ordcomCancelAll").hide();
			dialog_chgcode.on();
			$("#jqGrid_ordcom :input[name='ct_trxdate']").focus();
			$("#jqGrid_ordcom :input[name='remarks']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ordcom_ilsave').click();
			});

			$("#jqGrid_ordcom [name='ct_trxdate']").val(moment().format('D/M/YYYY'));
			$("#jqGrid_ordcom [name='ct_trxtime']").val(moment().format('hh:mm:ss'));
			$("#jqGrid_ordcom [name='ct_isudept']").val($("#ordcom_deptcode_hide").val());

		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
			errorField.length=0;
			$("#jqGridPager_ordcomDelete,#jqGridPager_ordcomRefresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			$('#p_error').text(data.errormsg);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid_ordcom').jqGrid ('getRowData', rowid);

			let editurl = "/ordcom/form?"+
				$.param({
					mrn: selrowData('#jqGrid').mrn,
					action: 'saveForm_ordcom',
				});
			$("#jqGrid_ordcom").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////End My edit options ORDERCOM/////////////////////////////////////////////////////////
	
	/////////////////////////start grid pager ORDERCOM/////////////////////////////////////////////////////////
	$("#jqGrid_ordcom").inlineNav('#jqGridPager_ordcom', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions,
		
	}).jqGrid('navButtonAdd', "#jqGridPager_ordcom", {
		id: "jqGridPager_ordcomDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid_ordcom").jqGrid('getGridParam', 'selrow');
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
								action: 'saveForm_ordcom',
								// cheqno: $('#cheqno').val(),
								// mrn: selrowData('#jqGrid_ordcom').mrn,
							}
							$.post( "/ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){
							}).fail(function (data) {
								$('#p_error').text(data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid_ordcom", urlParam_ordcom);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{
		id: "jqGridPager_ordcomEditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			
			var ids = $("#jqGrid_ordcom").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGrid_ordcom").jqGrid('editRow',ids[i]);
			}
			hideatdialogForm(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{
		id: "jqGridPager_ordcomSaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){

			var ids = $("#jqGrid_ordcom").jqGrid('getDataIDs');

			var jqGrid_ordcom_data = [];
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid_ordcom').jqGrid('getRowData',ids[i]);

				var obj = 
				{
					'idno' : ids[i],
					'startno' : $("#jqGrid_ordcom input#"+ids[i]+"_startno").val(),
					'endno' : $("#jqGrid_ordcom input#"+ids[i]+"_endno").val(),
					'cheqqty' : $("#jqGrid_ordcom input#"+ids[i]+"_cheqqty").val()
				}

				jqGrid_ordcom_data.push(obj);
			}

			var param={
				action: 'saveForm_ordcom',
				_token: $("#_token").val(),
				mrn: selrowData('#jqGrid').mrn,
			
			}

			$.post( "/ordcom/form?"+$.param(param),{oper:'edit_all_ordcom',dataobj:jqGrid_ordcom_data}, function( data ){
			}).fail(function(data) {
				$('#p_error').text(data.responseText);
				////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm(false);
				refreshGrid("#jqGrid_ordcom",urlParam_ordcom);
			});

		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
			//$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
		
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{
		id: "jqGridPager_ordcomCancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid_ordcom",urlParam_ordcom);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager_ordcom", {
		id: "jqGridPager_ordcomRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			oper = 'add_ordcom'
			refreshGrid("#jqGrid_ordcom", urlParam_ordcom);
		},
	});

	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll,#saveDetailLabel").hide();
			$("#jqGridPager_ordcomSaveAll,#jqGridPager_ordcomCancelAll").show();
		}else if(hide){
			$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll,#jqGridPager_ordcomSaveAll,#jqGridPager_ordcomCancelAll").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll").show();
			$("#saveDetailLabel,#jqGridPager_ordcomSaveAll,#jqGrid_ordcom_iledit,#jqGridPager_ordcomCancelAll").hide();
		}
	}
	//////////////////////////////////End grid pager ORDERCOM/////////////////////////////////////////////////////////
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	populateSelect('#jqGrid_ordcom', '#searchForm');
	searchClick2('#jqGrid_ordcom', '#searchForm', urlParam_ordcom);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid_ordcom', true, urlParam_ordcom);
	
});
