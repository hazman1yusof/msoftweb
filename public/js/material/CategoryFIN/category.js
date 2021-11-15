
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']");
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
	var err_reroll = new err_reroll('#jqGrid',['catcode', 'description', 'expacct', 'povalidate']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'material.category',
		table_id:'catcode',
		filterCol:['source', 'cattype'],
		filterVal:[$('#source2').val(), $('#cattype').val()],
		sort_idno: true,
	}

	//////////////////////////////// jQgrid /////////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/categoryfin/form",
		 colModel: [
			//{label: 'Compcode', name: 'compcode', width: 90 , hidden: true},
			{label: 'Category Code', name: 'catcode', width: 30, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},
			{label: 'Description', name: 'description', width: 80, classes: 'wrap',checked:true, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase"}},					
			{label: 'Category Type', name: 'cattype', width: 90 , hidden: true},					
			{label: 'Source', name: 'source', width: 90 , hidden: true},					
			{label: 'Stock Account', name: 'stockacct', width: 90 ,  hidden: true},					
			{label: 'COS Account', name: 'cosacct', width: 90, hidden: true,},					
			{label: 'Adjustment Account', name: 'adjacct', width: 90, hidden: true},					
			{label: 'Write Off Account', name: 'woffacct', width: 90, hidden: true},					
			{label: 'Expenses Account', name: 'expacct', width: 80, hidden: false, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:expacctCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},					
			{label: 'Loan Account', name: 'loanacct', width: 90, hidden: true},					
			{label: 'PO Validate', name: 'povalidate', width: 25, hidden: false, editable: true, edittype:"select",formatter:'select', editoptions:{value:"1:YES;0:NO"},formatter:formatter, unformat:unformat, unformat:unformat, formatter:formatterstatus_tick2, unformat:unformatstatus_tick2, classes: 'center_td' },					
			{label: 'accrualacc', name: 'accrualacc', width: 90, hidden: true},					
			{label: 'stktakeadjacct', name: 'stktakeadjacct', width: 90, hidden: true},					
			{label: 'adduser', name: 'adduser', width: 90 , hidden: true},					
			{label: 'adddate', name: 'adddate', width: 90 , hidden: true},					
			{label: 'upduser', name: 'upduser', width: 90 , hidden: true},					
			{label: 'upddate', name: 'upddate', width: 90 , hidden: true},
			{label: 'deluser', name: 'deluser', width: 90 , hidden: true},					
			{label: 'deldate', name: 'deldate', width: 90 , hidden: true},					
			{label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap', hidden: false, editable: true, edittype:"select",formatter:'select', editoptions:{value:"ACTIVE:ACTIVE;DEACTIVE:DEACTIVE"}, 
				cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{label: 'idno', name: 'idno', hidden:true, key:true},
			{label: 'computerid', name: 'computerid', width: 90, hidden:true, classes: 'wrap'},
			{label: 'ipaddress', name: 'ipaddress', width: 90, hidden:true, classes: 'wrap'},
			{label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true, classes: 'wrap'},
			{label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true, classes: 'wrap'},
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
		onSelectRow:function(rowid, selected){
			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
				else{
					$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
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

					dialog_expacct.on();

					$("input[name='description']").keydown(function(e) {//when click tab at last column in header, auto save
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

					let editurl = "/categoryfin/form?"+
						$.param({
							action: 'categoryfin_save',
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

					dialog_expacct.on();

					$("input[name='catcode']").attr('disabled','disabled');
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

					let editurl = "/categoryfin/form?"+
						$.param({
							action: 'categoryfin_save',
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

				////////////////////////formatter tick///////////////////////////////////////////////////////////
				function formatterstatus_tick2(cellvalue, option, rowObject) {
					if (cellvalue == '1') {
						return `<span class="fa fa-check"></span>`;
					}else{
						return '';
					}
				}
			
			
				function unformatstatus_tick2(cellvalue, option, rowObject) {
					if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
						return '1';
					}else{
						return '0';
					}
				}
	
				function formatter(cellvalue, options, rowObject){
					return parseInt(cellvalue) ? "YES" : "NO";
				}
	
				function unformat(cellvalue, options){
					//return parseInt(cellvalue) ? "Yes" : "No";
	
					if (cellvalue == 'YES') {
						return "1";
					}
					else {
						return "0";
					}
				}

			///////////////////////////////////////cust_rules//////////////////////////////////////////////
			function cust_rules(value,name){
				var temp;
				switch(name){
					case 'Expenses Account':temp=$('#expacct');break;
				}
				return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
			}

			function showdetail(cellvalue, options, rowObject){
				var field,table,case_;
				switch(options.colModel.name){
					case 'expacct':field=['glaccno','description'];table="finance.glmasref";case_='expacct';break;
					
				}
				var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

				fdl.get_array('categoryfin',options,param,case_,cellvalue);
				
				return cellvalue;
			}

			function expacctCustomEdit(val, opt) {
				val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
				return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="expacct" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
										action: 'categoryfin_save',
										idno: selrowData('#jqGrid').idno,
									}
									$.post( "/categoryfin/form?"+$.param(param),{oper:'del'}, function( data ){
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

	var dialog_expacct = new ordialog(
		'expacct','finance.glmasref',"#jqGrid input[name='expacct']",errorField,
		{	colModel:[
				{label:'Gl Acc No',name:'glaccno',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					let data=selrowData('#'+dialog_expacct.gridname);
					$("#stockacct").val(data['glaccno']);
					$("#cosacct").val(data['glaccno']);
					$("#adjacct").val(data['glaccno']);
					$("#woffacct").val(data['glaccno']);
					$("#loanacct").val(data['glaccno']);
					//$('#povalidate').focus();
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
				dialog_expacct.urlParam.filterCol=['compcode','recstatus'];
				dialog_expacct.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_expacct.makedialog();

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
