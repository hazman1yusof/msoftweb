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
	var err_reroll = new err_reroll('#jqGrid',['mmacode', 'description']);
	var mycurrency2 =new currencymode([]);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
        url:'mmamaintenance/table',
		field:'',
		table_name:'hisdb.mmamaster',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: '/mmamaintenance/form',
		colModel: [
			{ label: 'id', name: 'idno', width:5, hidden: true, key:true},
			{ label: 'compcode', name: 'compcode', hidden:true},						
			{ label: 'MMA Code', name: 'mmacode', width: 30, classes: 'wrap', canSearch: true, editable: true,editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Description', name: 'description', classes: 'wrap', width: 100, canSearch: true, checked: true, editable: true, edittype: "textarea", editrules: { required: true }, 
				editoptions: 
					{style: "width: -webkit-fill-available; text-transform: uppercase" ,rows: 5}
			},
			{ label: 'Version', name: 'version', width: 30, canSearch: true, align: 'right'},
			{ label: 'Add User', name: 'adduser', width: 40, hidden:false},
			{ label: 'Add Date', name: 'adddate', width: 50, hidden:false},
			{ label: 'Upd User', name: 'upduser', width: 40, hidden:false},
			{ label: 'Upd Date', name: 'upddate', width: 50, hidden:false},
			{ label: 'Computer ID', name: 'computerid', width: 40, hidden:false},
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'Status', name: 'recstatus', width: 30, classes: 'wrap', editable: true, edittype:"select",formatter:'select', 
				editoptions:{
					value:"ACTIVE:ACTIVE;DEACTIVE:DEACTIVE"},
					cellattr: function(rowid, cellvalue)
							{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
			populate_formMMA(selrowData("#jqGrid"));

			urlParam2.filterVal[1]=selrowData("#jqGrid").mmacode;
			refreshGrid("#jqGrid2",urlParam2);

			// if (rowid != null) {
			// 	var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
			// 	refreshGrid('#jqGrid2', urlParam2,'kosongkan');
			// 	$("#jqGrid2_mmadtlpanel").hide();
			// 	if(rowData['mmacode'] != '') {//kalau mmacode ada
			// 		urlParam2.filterVal[0] = selrowData('#jqGrid').mmacode;
			// 		refreshGrid('#jqGrid2', urlParam2); //mmadtl
			// 		$("#jqGrid2_mmadtlpanel").show();

			// 		populate_formMMA(selrowData("#jqGrid"));
			// 	}else{
			// 		$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").show();
			// 	}
			// }
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
		var mmacode = $("#jqGrid input[name='mmacode']").val();
		var description = $("#jqGrid textarea[name='description']").val();

		if(mmacode.trim().length <= 0){
			myerrorIt_only("#jqGrid input[name='mmacode']",true);
		}else{
			myerrorIt_only("#jqGrid input[name='mmacode']",false);
		}

		if(description.trim().length <= 0){
			myerrorIt_only("#jqGrid input[name='description']",true);
		}else{
			myerrorIt_only("#jqGrid input[name='description']",false);
		}
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
            $("#description").focus().select();
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();

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

			let editurl = "/mmamaintenance/form?"+
				$.param({
					action: 'mmamaintenance_save',
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

	//////////////////////////////////////////myEditOptions_edit////////////////////////////////////////////////
	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid').data('lastselrow',rowid);
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			$("#description").focus().select();
			$("input[name='mmacode']").attr('disabled','disabled');
			$("select[name='recstatus']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
			});

			let selrow = $("#jqGrid").jqGrid ('getRowData', rowid);

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
			refreshGrid('#jqGrid',urlParam,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);

			check_cust_rules();

			let editurl = "/mmamaintenance/form?"+
				$.param({
					action: 'mmamaintenance_save',
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

	/////////////////////////start inline jqgrid pager/////////////////////////////////////////////////////////
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
								action: 'mmamaintenance_save',
								mmacode: $('#mmacode').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "/mmamaintenance/form?"+$.param(param),{oper:'del'}, function( data ){
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

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		table_name: 'hisdb.mmaprice',
		table_id: 'idno',
		filterCol:['compcode','mmacode'],
		filterVal:['session.compcode','']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
		////////////////////////////////////////////////jqgrid2 MMA Dtl//////////////////////////////////////////////

	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/mmamaintenanceDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			// { label: 'MMA Code', name: 'mmacode', width: 100, classes: 'wrap', canSearch: true, editable: true, edittype:"text", 
			// 	editrules: { required: true }, 
			// 		editoptions: {maxlength : 30, style: "text-transform: uppercase" }
			// },
			{ label: 'Effective date', name: 'effectdate', width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'MMA Consult', name: 'mmaconsult', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'MMA Surgeon', name: 'mmasurgeon', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'MMA Anaes', name: 'mmaanaes', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'Fees Consult', name: 'feesconsult', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'Fees Surgeon', name: 'feessurgeon', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'Fees Anaes', name: 'feesanaes', width: 100, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100, style: "text-transform: uppercase"
				},
			},
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		gridComplete: function(){

			fdl.set_array().reset();
			if(!hide_init){
				hide_init=1;
				hideatdialogForm_jqGrid2(false);
			}
		}
	});
	var hide_init=0;
	////////////////////////////////////////////////end jqgrid2 MMA dtl//////////////////////////////////////////////

	//////////////////////////////////////////myEditOptions2 for MMA dtl/////////////////////////////////////////////
	var myEditOptions2 = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").hide();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='mmaconsult']","#jqGrid2 input[name='mmasurgeon']","#jqGrid2 input[name='mmaanaes']","#jqGrid2 input[name='feesconsult']","#jqGrid2 input[name='feessurgeon']","#jqGrid2 input[name='feesanaes']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			$("input[name='mmacode']").attr('disabled','disabled');
			$("input[name='feesanaes']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").show();
		}, 
		errorfunc: function(rowid,response){
			$(".noti").text(response.responseText);
			// alert(response.responseText);
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		beforeSaveRow: function(options, rowid) {

			//if(errorField.length>0)return false;  

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "/mmamaintenanceDetail/form?"+
				$.param({
					action: 'mmamaintenanceDetail_save',
					oper: 'add',
					mmacode: selrowData('#jqGrid').mmacode,//$('#cm_chgcode').val(),
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm_jqGrid2(false);
		}
	};
	//////////////////////////////////////////end myEditOptions2 for MMA dtl/////////////////////////////////////////////

	//////////////////////////////////////////pager jqgrid2 MMA dtl/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions2
		},
		editParams: myEditOptions2
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if(result == true){
							param={
								action: 'mmamaintenanceDetail_save',
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "/mmamaintenanceDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								refreshGrid("#jqGrid2",urlParam2);
							});
						}else{
							$("#jqGridPager2EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {

				$("#jqGrid2").jqGrid('editRow',ids[i]);

				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_mmaconsult","#"+ids[i]+"_mmasurgeon","#"+ids[i]+"_mmaanaes","#"+ids[i]+"_feesconsult","#"+ids[i]+"_feessurgeon","#"+ids[i]+"_feesanaes"]);
			}
			mycurrency2.formatOnBlur();
			//onall_editfunc();
			hideatdialogForm_jqGrid2(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid2").jqGrid('getDataIDs');

			var jqgrid2_data = [];
			mycurrency2.formatOff();
			for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				var obj = 
				{
					'idno' : data.idno,
					'effectdate' : $("#jqGrid2 input#"+ids[i]+"_effectdate").val(),
					'mmaconsult' : $("#jqGrid2 input#"+ids[i]+"_mmaconsult").val(),
					'mmasurgeon' : $("#jqGrid2 input#"+ids[i]+"_mmasurgeon").val(),
					'mmaanaes' : $("#jqGrid2 input#"+ids[i]+"_mmaanaes").val(),
					'feesconsult' : $("#jqGrid2 input#"+ids[i]+"_feesconsult").val(),
					'feessurgeon' : $("#jqGrid2 input#"+ids[i]+"_feessurgeon").val(),
					'feesanaes' : $("#jqGrid2 input#"+ids[i]+"_feesanaes").val()
				}

				jqgrid2_data.push(obj);
			}

			var param={
				action: 'mmamaintenanceDetail_save',
				_token: $("#_token").val()
			}

			$.post( "/mmamaintenanceDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				hideatdialogForm_jqGrid2(false);
				refreshGrid("#jqGrid2",urlParam2);
			});
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm_jqGrid2(false);
			refreshGrid("#jqGrid2",urlParam2);
		},	
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid2", urlParam2);
		},
	});

	function hideatdialogForm_jqGrid2(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
		}
	}
	//////////////////////////////////////////end pager jqgrid2 MMA Dtl/////////////////////////////////////////////

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

	$("#jqGrid2_mmadtlpanel").on("show.bs.collapse", function(){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_mmadtl")[0].offsetWidth-$("#jqGrid2_mmadtl")[0].offsetLeft-28));
	});

});

function populate_formMMA(obj){
	console.log(obj);
	if(obj.idno.trim().length == 0){
		$('#mmacode_show').text('');
		$('#description_show').text('');	
	}else{
		$('#mmacode_show').text(obj.mmacode);
		$('#description_show').text(obj.description);	
	}	
}

function empty_formMMA(){

	$('#mmacode_show').text('');
	$('#description_show').text('');
}