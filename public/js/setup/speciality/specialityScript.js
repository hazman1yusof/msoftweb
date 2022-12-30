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
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $('#'+errorField[0]),
					message : ' '
				}
			}
		},
	};

	var fdl = new faster_detail_load();
	var err_reroll = new err_reroll('#jqGrid',['specialitycode', 'description', 'disciplinecode', 'type']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'hisdb.speciality',
		table_id:'specialitycode',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./speciality/form",
			colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true, key:true },
			{ label: 'idno', name: 'idno', width: 80, hidden:true, key:true},					
			{ label: 'Speciality Code', name: 'specialitycode', width: 20, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'Description', name: 'description', classes: 'wrap', canSearch: true, checked: true, width: 80, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'Discipline Code', name: 'disciplinecode', width: 30, classes: 'wrap',editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
				edittype:'custom',	editoptions:
					{  custom_element:disciplinecodeCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Category Type', name: 'type', width: 30, classes: 'wrap',editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', hidden: false, editable: true, edittype:"select",formatter:'select', editoptions:{value:"ACTIVE:ACTIVE;DEACTIVE:DEACTIVE"}, 
				cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
		],
		autowidth:true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce:false,
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
		var chk = ['specialitycode', 'description', 'disciplinecode', 'type'];
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

			dialog_disciplinecode.on();

			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
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

			let editurl = "./speciality/form?"+
				$.param({
					action: 'speciality_save',
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

			dialog_disciplinecode.on();

			$("input[name='specialitycode']").attr('disabled','disabled');
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
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
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./speciality/form?"+
				$.param({
					action: 'speciality_save',
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

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Discipline Code':temp=$('#disciplinecode');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'disciplinecode':field=['code','description'];table="hisdb.discipline";case_='disciplinecode';break;
			
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('speciality',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	function disciplinecodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		//val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="disciplinecode" type="text" class="form-control input-sm" data-validation="required" style="text-transform:uppercase" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
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
								_token: $("#_token").val(),
								action: 'speciality_save',
								disciplinecode: $('#disciplinecode').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./speciality/form?"+$.param(param),{oper:'del'}, function( data ){
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
	//addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	////////////////////////////////////////////////////ordialog////////////////////////////////////////

	var dialog_disciplinecode = new ordialog(
		'disciplinecode','hisdb.discipline',"#jqGrid input[name='disciplinecode']",errorField,
		{	colModel:[
				{label:'Discipline Code',name:'code',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					let data=selrowData('#'+dialog_disciplinecode.gridname);
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
				dialog_disciplinecode.urlParam.filterCol=['compcode','recstatus'];
				dialog_disciplinecode.urlParam.filterVal=['session.compcode','ACTIVE'];	
			},
			close: function(){
				$("#jqGrid input[name='type']").focus();
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_disciplinecode.makedialog();
	
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
});