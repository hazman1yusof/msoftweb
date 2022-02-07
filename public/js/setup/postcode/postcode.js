$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};

	var fdl = new faster_detail_load();
	var err_reroll = new err_reroll('#jqGrid',['pc_postcode', 'pc_place_name','pc_district']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: ['hisdb.postcode AS PC', 'hisdb.state AS ST', 'hisdb.country AS CN'],
		table_id: 'pc_compcode',
		sort_idno: true,
		fixPost: 'true',
		join_type:['LEFT JOIN', 'LEFT JOIN'],
		join_onCol:['pc.statecode', 'pc.countrycode'],
		join_onVal:['st.StateCode', 'cn.Code'],
		filterCol:['pc.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./postcode/form",
		colModel: [
            { label: 'compcode', name: 'pc_compcode', hidden: true },
            { label: 'Postode', name: 'pc_postcode', width: 15, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" } },
            { label: 'Place Name', name: 'pc_place_name', width: 30, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" } },
            { label: 'District', name: 'pc_district', width: 60, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" } },
            { label: 'State', name: 'st_StateCode', width: 15, canSearch: true, editable: true, 
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,unformat:un_showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:StateCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Country', name: 'cn_Code', width: 15, canSearch: true, editable: true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,unformat:un_showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:CountryCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Status', name: 'pc_recstatus', width: 15, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
                    value:"ACTIVE:ACTIVE;DEACTIVE:DEACTIVE"},
                    cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
                },
			{ label: 'id', name: 'pc_idno', width:10, hidden: true, key:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			{ label: 'lastuser', name: 'lastuser', width: 90, hidden:true},
			{ label: 'lastupdate', name: 'lastupdate', width: 90, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname: 'pc_idno',
		sortorder: 'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_iladd').click();
			}else if($('#jqGrid').data('lastselrow') == 'none'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($('#jqGrid').data('lastselrow'));
				$('#jqGrid tr#' + $('#jqGrid').data('lastselrow')).focus();
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll.error == true){
				err_reroll.reroll();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save				
		},
		gridComplete: function () {
			fdl.set_array().reset();
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}	
		},
	});

	function check_cust_rules(rowid){
		var chk = ['pc_postcode','pc_place_name','pc_district','st_StateCode','cn_Code'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid input[name='"+e+"']",false);
			}
		})
	}

	//////////////////////////My edit options /////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid').data('lastselrow','none');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_state.on();
			dialog_country.on();

			$("input[name='pc_recstatus']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			//$('#p_error').text(response.responseText);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			console.log(data);

			check_cust_rules();

			let editurl = "./postcode/form?"+
				$.param({
					action: 'postcode_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid',urlParam,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////My edit options edit /////////////////////////////////////////////////////////
	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_state.on();
			dialog_country.on();

			$("input[name='pc_postcode']").attr('disabled','disabled');
			$("input[name='pc_recstatus']").keydown(function(e) {//when click tab at totamount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam2,'add');
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			check_cust_rules();

			let editurl = "./postcode/form?"+
				$.param({
					action: 'postcode_save',
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

	/////////////////////////start jqgrid pager/////////////////////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
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
								action: 'postcode_save'
							}
							$.post( "./postcode/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid", urlParam);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll=function(){

			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();

			this.data_array.forEach(function(item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}		
	}
	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'State':temp=$("#jqGrid input[name='state']");break;
			case 'Country':temp=$("#jqGrid input[name='country']");break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'st_StateCode':field=['StateCode','Description'];table="hisdb.state";case_='expacct';break;
			case 'cn_Code':field=['Code','Description'];table="hisdb.country";case_='expacct';break;

		}

		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('postcode',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	function StateCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="state" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function CountryCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="country" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	var dialog_state = new ordialog(
		'state','hisdb.state',"#jqGrid input[name='state']",errorField,
		{	colModel:[
				{label:'State Code',name:'StateCode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
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
							//$('#povalidate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Account Code",
			open: function(){
				dialog_state.urlParam.filterCol=['compcode','recstatus'];
				dialog_state.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				$("#jqGrid input[name='country']").focus();
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_state.makedialog();

	var dialog_country = new ordialog(
		'country','hisdb.country',"#jqGrid input[name='country']",errorField,
		{	colModel:[
				{label:'Country Code',name:'Code',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
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
							$('#pc_recstatus').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Account Code",
			open: function(){
				dialog_country.urlParam.filterCol=['compcode','recstatus'];
				dialog_country.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				$("#jqGrid input[name='pc_recstatus']").focus();
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_country.makedialog();
});