
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		language: {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					//element : $('#'+errorField[0]),
					message: ' '
				}
			}
		},
	};

	var fdl = new faster_detail_load();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: '/util/get_table_default',
		field: '',
		table_name: 'sysdb.sector',
		table_id: 'sectorcode',
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/unit/form",
		colModel: [
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key:true},
			{ label: 'Unit', name: 'sectorcode', width: 20, classes: 'wrap', canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'Description', name: 'description', width: 80, classes: 'wrap', canSearch: true, checked:true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{ label: 'Section', name: 'regioncode', width: 50, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:unitCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', hidden: false, editable: true, edittype:"select",formatter:'select', editoptions:{value:"A:ACTIVE;D:DEACTIVE"}, 
				cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'computerid', name: 'computerid', width: 90, hidden:true},
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
				else{
						$('#jqGrid2').jqGrid ('setSelection', "1");
					}

				addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
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
					$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

					dialog_regioncode.on();

					$("input[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
						var code = e.keyCode || e.which;
						if (code == '9')$('#jqGrid_ilsave').click();
						/*addmore_jqgrid.state = true;
						$('#jqGrid_ilsave').click();*/
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
					$('#p_error').text(response.responseText);
					refreshGrid('#jqGrid',urlParam,'add');
				},
				beforeSaveRow: function (options, rowid) {
					$('#p_error').text('');
					if(errorField.length>0)return false;

					let data = $('#jqGrid').jqGrid ('getRowData', rowid);
					console.log(data);

					let editurl = "/unit/form?"+
						$.param({
							action: 'unit_save',
						});
					$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
				},
				afterrestorefunc : function( response ) {
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
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
					$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

					dialog_regioncode.on();

					$("input[name='sectorcode']").attr('disabled','disabled');
					$("input[name='description']").keydown(function(e) {//when click tab at last column in header, auto save
						var code = e.keyCode || e.which;
						if (code == '9')$('#jqGrid_ilsave').click();
						/*addmore_jqgrid.state = true;
						$('#jqGrid_ilsave').click();*/
					});

				},
				aftersavefunc: function (rowid, response, options) {
					if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
					//state true maksudnyer ada isi, tak kosong
					refreshGrid('#jqGrid',urlParam,'add');
					errorField.length=0;
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
				},
				errorfunc: function(rowid,response){
					$('#p_error').text(response.responseText);
					refreshGrid('#jqGrid',urlParam,'add');
				},
				beforeSaveRow: function (options, rowid) {
					$('#p_error').text('');
					if(errorField.length>0)return false;

					let data = $('#jqGrid').jqGrid ('getRowData', rowid);
					// console.log(data);

					let editurl = "/unit/form?"+
						$.param({
							action: 'unit_save',
						});
					$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
				},
				afterrestorefunc : function( response ) {
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
					case 'Section':temp=$('#regioncode');break;
				}
				return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			function showdetail(cellvalue, options, rowObject){
				var field,table,case_;
				switch(options.colModel.name){
					case 'regioncode':field=['regioncode','description'];table="sysdb.region";case_='regioncode';break;
					
				}
				var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

				fdl.get_array('unit',options,param,case_,cellvalue);
				
				return cellvalue;
			}

			function unitCustomEdit(val, opt) {
				val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
				return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="regioncode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
										action: 'unit_save',
										regioncode: $('#regioncode').val(),
										idno: selrowData('#jqGrid').idno,
									}
									$.post( "/unit/form?"+$.param(param),{oper:'del'}, function( data ){
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

	var dialog_regioncode = new ordialog(
		'regioncode','sysdb.region',"#jqGrid input[name='regioncode']",errorField,
		{	colModel:[
				{label:'Region Code',name:'regioncode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
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
							$('#'+obj.dialogname).dialog('close');regioncode
						}
					}
		},{
			title:"Select Region Code",
			open: function(){
				dialog_regioncode.urlParam.filterCol=['compcode','recstatus'],
				dialog_regioncode.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_regioncode.makedialog(true);

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	//toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus', 'computerid', 'ipaddress']);
});
