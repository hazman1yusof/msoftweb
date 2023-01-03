$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
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
					//element : $('#'+errorField[0]),
					message: ' '
				}
			}
		},
	};

	var err_reroll = new err_reroll('#jqGrid',['Code', 'Description']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'hisdb.racecode',
		table_id: 'idno',
		filterCol:['compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./race/form",
		colModel: [
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Race Code', name: 'Code', width: 15, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Description', name: 'Description', width: 80, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
			editoptions:{value:"ACTIVE:ACTIVE;DEACTIVE:DEACTIVE"}, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			{ label: 'lastuser', name: 'lastuser', width: 90, hidden:true},
			{ label: 'lastupdate', name: 'lastupdate', width: 90, hidden:true},

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
		onSelectRow:function(rowid, selected){
			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_iladd').click();
			}
			else{
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			if(err_reroll.error == true){
				err_reroll.reroll();
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
	});

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
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

			let editurl = "./race/form?"+
				$.param({
					action: 'race_save',
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
			$("input[name='Code']").attr('disabled','disabled');
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
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
			refreshGrid('#jqGrid',urlParam2,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			let editurl = "./race/form?"+
				$.param({
					action: 'race_save',
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
								action: 'race_save',
								Code: $('#Code').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./race/form?"+$.param(param),{oper:'del'}, function( data ){
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
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);

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
