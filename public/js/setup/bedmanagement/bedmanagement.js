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
					message: ' '
				}
			}
		},
	};
	
	var fdl = new faster_detail_load();
	$("#jqGrid_trf_c, #jqGridTriageInfo_c, #jqGridWard_c, #jqGridDietOrder_c, #jqGridDischgSummary_c").hide();

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Bed Type':temp=$("#jqGrid input[name='bedtype']");break;
			case 'Ward':temp=$("#jqGrid input[name='ward']");break;
			case 'Bed Status':temp=$("#jqGrid input[name='occup']");break;
			case 'Statistic':temp=$("#jqGrid input[name='statistic']");break;
			case ' ':temp=$("#jqGrid input[name='recstatus']");break;
			break;
		}
		console.log(temp);
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'b_bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='b_bedtype';break;
			case 'ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
			case 'admdoctor': field = ['doctorcode', 'doctorname']; table = "hisdb.doctor";case_='doccode';break;
			case 'ba_ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ba_ward';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bedmanagement',options,param,case_,cellvalue);
		
		if(cellvalue==null)return "";
		return cellvalue;
	}

	function occupCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="occup" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function bedTypeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="bedtype" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function bedTypeTRFCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="bedtype" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function wardCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="ward" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function wardTRFCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="ba_ward" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function statCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="statistic" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function recstatusCustomEdit(val, opt) {
		if(val == '<span class="fa fa-times"></span>'){
			val = 'D';
		}else{
			val = 'A';
		}
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="recstatus" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table',
		url: '/bedmanagement/table',
		field: '',
		table_name: 'hisdb.bed as b',
		sort_idno: true,
		filterCol:['b.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/bedmanagement/form",
		colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'Bed No', name: 'bednum', width: 10, canSearch: true, checked: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Bed Type', name: 'bedtype', width: 5, canSearch: true, editable: true, editrules: { required: true }, formatter: showdetail, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Bed Type', name: 'bedtype', width: 15, classes: 'wrap', editable:true, canSearch: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{	custom_element:bedTypeCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			// { label: 'Status', name: 'occup', width: 5, canSearch: true, formatter: formatteroccup, unformat: unformatoccup, classes: 'wrap'},
			{ label: 'Bed Status', name: 'occup', width: 20, classes: 'wrap', canSearch: true, editable: true,formatter:occup,unformat:occup_unformat, editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ 	custom_element:occupCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Room', name: 'room', width: 10, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Ward', name: 'ward', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Ward', name: 'ward', width: 25 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{ 	custom_element:wardCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Tel Ext', name: 'tel_ext', width: 8, canSearch: false, checked: true, editable: true, editoptions: {style: "text-transform: uppercase" }},
			//{ label: 'Statistic', name: 'statistic', width: 15, canSearch: true, editable: true, edittype:"select", editrules: { required: true }, editoptions: {value:'TRUE:TRUE;FALSE:FALSE' },formatter:truefalseFormatter,unformat:truefalseUNFormatter},
			{ label: 'Statistic', name: 'statistic', width: 15, classes: 'wrap', canSearch: false, editable: true,editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ 	custom_element:statCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'MRN', name: 'mrn', width: 8, canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: ' ', name: 'episno', width: 5},
			{ label: 'Patient Name', name: 'name', width: 25, canSearch: true, classes: 'wrap'},
			{ label: 'Doctor Code', name: 'admdoctor', width: 20, canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: ' ', name: 'recstatus', width: 8, classes: 'center_td', editable: true,formatter:formatterstatus_tick,unformat:unformatstatus_tick, editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
				{ 	custom_element:recstatusCustomEdit,
					custom_value:galGridCustomValue 	
				},
			},
			{ label: 'id', name: 'idno', width:10, hidden: true, key:true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'lastuser', name: 'lastuser', width: 90, hidden:true},
			{ label: 'lastupdate', name: 'lastupdate', width: 90, hidden:true},
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
		onSelectRow:function(rowid, selected){

			if (rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid_trf', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table, #jqGrid_trf_c, #jqGridTriageInfo_c, #jqGridWard_c, #jqGridDietOrder_c, #jqGridDischgSummary_c").hide();
				if(rowData['mrn'] != '') {//kalau mrn ada
					urlParam2.filterVal[0] = selrowData('#jqGrid').mrn;
					refreshGrid('#jqGrid_trf', urlParam2);
					$("#pg_jqGridPager3 table, #jqGrid_trf_c, #jqGridTriageInfo_c, #jqGridWard_c, #jqGridDietOrder_c, #jqGridDischgSummary_c").show();
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").hide();

					populate_triage(selrowData("#jqGrid"));
					populate_formWard(selrowData("#jqGrid"));
					populate_dietOrder(selrowData("#jqGrid"));
					populate_dischgSummary(selrowData("#jqGrid"));
					populate_form_trf(selrowData("#jqGrid"));
					
				}else{
					$("#jqGridPagerDelete,#jqGrid_iledit,#jqGrid_ilcancel,#jqGrid_ilsave").show();
				}
			}


		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
			button_state_ti('triage');
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			if (rowid != null) {
				rowData = $('#jqGrid').jqGrid('getRowData', rowid);

				if (rowData['mrn'] == '') {
					$("#jqGrid_iledit").click();
				}
			}
			// $("#jqGrid_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			fdl.set_array().reset();
			statistics();
			empty_form_trf();
		},
	});

	/////////////////////////////Start populate data for search By dropdown and btn////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['selected']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
		});
	}
	$('#Scol').on('change', scolChange);

	function scolChange() {
		if($('#Scol').val()=='bedtype'){
			$("#div_statistic").hide();
			$("#div_occup,#show_doc").hide();
			$("#div_doc").hide();			
			$("#div_bedtype,#searchForm input[name='Stext']").show();
		} else if($('#Scol').val() == 'statistic'){
			$("#div_bedtype").hide();
			$("#div_occup,#show_doc").hide();
			$("#div_doc").hide();			
			$("#div_statistic,#searchForm input[name='Stext']").show();
		} else if($('#Scol').val() == 'occup'){
			$("#div_bedtype").hide();
			$("#div_statistic,#show_doc").hide();
			$("#div_doc").hide();			
			$("#div_occup,#searchForm input[name='Stext']").show();	
		} else if($('#Scol').val() == 'admdoctor'){
			$("#div_statistic,#div_bedtype,#div_occup,#div_doc,#searchForm input[name='Stext']").hide();
			$('#show_doc').show();
		} else {
			$("#div_statistic,#div_bedtype,#div_occup,#div_doc,#show_doc").hide();
			$("#searchForm input[name='Stext']").show();
		}
	}
	/////////////////////////////End populate data for search By dropdown and btn////////////////////////////

	function padzero(cellvalue, options, rowObject){
		if(cellvalue == null || cellvalue == 0 ){
			return "";
		}
		let padzero = 6, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	function occup(cellvalue, options, rowObject){
		switch(cellvalue.trim()){
			case 'OCCUPIED': return '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED';break;
			case 'VACANT': return '<img src="img/bedonly.png" height="10" width="14"></img> VACANT';break;
			case 'HOUSEKEEPING': return '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING';break;
			case 'MAINTENANCE': return '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE';break;
			case 'ISOLATED': return '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED';break;
			case 'RESERVE': return '<i class="fa fa-ban" aria-hidden="true"></i> RESERVE';break;
			case 'TOTAL BED': return '<i class="fa fa-bed" aria-hidden="true"></i> TOTAL BED';break;
			default: return cellvalue;break;
		}
	}

	function occup_unformat(cellvalue, options, rowObject){
		switch(cellvalue){
			case '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED': return 'OCCUPIED';break;
			case '<img src="img/bedonly.png" height="10" width="14"></img> VACANT': return 'VACANT';break;
			case '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING': return 'HOUSEKEEPING';break;
			case '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE': return 'MAINTENANCE';break;
			case '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED': return 'ISOLATED';break;
			case '<i class="fa fa-ban" aria-hidden="true"></i> RESERVE': return 'RESERVE';break;
			case '<i class="fa fa-bed" aria-hidden="true"></i> TOTAL BED': return 'TOTAL BED';break;						
			default: return cellvalue;break;
		}
	}

	statistics();
	function statistics(){
		$.get( "/bedmanagement/statistic", function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$('#stat_vacant').text(data.vacant);
				$('#stat_occupied').text(data.occupied);
				$('#stat_housekeeping').text(data.housekeeping);
				$('#stat_maintenance').text(data.maintenance);
				$('#stat_isolated').text(data.isolated);
				$('#stat_active').text(data.active);
				$('#stat_deactive').text(data.deactive);
				$('#stat_reserve').text(data.reserve);
				$('#stat_totalbed').text(data.totalbed);
			}
		});

	}

	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			dialog_stat.on();
			dialog_recstatus.on();
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

			let editurl = "/bedmanagement/form?"+
				$.param({
					action: 'bedmanagement_save',
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
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			dialog_stat.on();
			dialog_recstatus.on();
			$("input[name='bednum']").attr('disabled','disabled');
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
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam2,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);

			let editurl = "/bedmanagement/form?"+
				$.param({
					action: 'bedmanagement_save',
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
		add: false,
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
								action: 'bedmanagement_save',
								Code: $('#Code').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "/bedmanagement/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid", urlParam,'edit');
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
			refreshGrid("#jqGrid", urlParam,'edit');
		},
	});

	//////////////////// Start Dialog for Search By btn////////////////////////////////////////////////////////////////////////////////

	$('#btn_bedtype').on( "click", function() {
		$('#s_bedtype ~ a').click();
	});
	var search_bedtype = new ordialog(
		'search_bedtype', 'hisdb.bedtype', '#s_bedtype', 'errorField',
		{
			colModel: [
				{ label: 'Bed Type', name: 'bedtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + search_bedtype.gridname).bedtype;
				$("#searchForm input[name='Stext']").val($('#s_bedtype').val());

				urlParam.searchCol=["bedtype"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam,'edit');
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_bedtype.urlParam.filterCol=['compcode', 'recstatus'];
				search_bedtype.urlParam.filterVal=['session.compcode', 'A'];
			}
		},'urlParam','radio','tab'
	);
	search_bedtype.makedialog();
	search_bedtype.on();
	
	
	$('#btn_occup').on( "click", function() {
		$('#occup ~ a').click();
	});
	var search_occup = new ordialog(
		'search_occup', 'sysdb.department', '#occup', 'errorField',
		{
			colModel: [
				{ label: 'Bed Status', name: 'bedcode', width: 200, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'description', name: 'description', hidden:true},
			],
			urlParam: {
				url:'./sysparam_bed_status',
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function (event) {

				let data = selrowData('#' + search_occup.gridname);
				$("#searchForm input[name='Stext']").val(data.description);
				console.log(data.description);
				if(data.description == 'ACTIVE' || data.description == 'DEACTIVE'){
					let val_use = (data.description == 'ACTIVE')? 'A':'D';
					urlParam.searchCol=["recstatus"];
					urlParam.searchVal=[val_use];
				}else{
					urlParam.searchCol=["occup"];
					urlParam.searchVal=[data.description];
				}

				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Bed Status search",
			open: function () {
				search_occup.urlParam.filterCol=['compcode', 'recstatus'];
				search_occup.urlParam.filterVal=['session.compcode', 'A'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	search_occup.makedialog();
	search_occup.on();

	$('#btn_doc').on( "click", function() {
		$('#doc ~ a').click();
	});
	var search_doc = new ordialog(
		'search_doc', 'hisdb.doctor', '#doc', 'errorField',
		{
			colModel: [
				{ label: 'Doctor Code', name: 'doctorcode', width: 20, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'doctorname', width: 80, classes: 'pointer',checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + search_doc.gridname).doctorcode;
				$("#searchForm input[name='Stext']").val($('#doc').val());

				urlParam.searchCol=["admdoctor"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select MRN search",
			open: function () {
				search_doc.urlParam.filterCol=['compcode', 'recstatus'];
				search_doc.urlParam.filterVal=['session.compcode', 'A'];
			}
		},'urlParam','radio','tab'
	);
	search_doc.makedialog();
	search_doc.on();

	////////////////////// End Dialog for Search By btn//////////////////////

	////////////////////// Start Dialog for jqGrid1//////////////////////////
	
	var dialog_bedtype = new ordialog(
		'bedtype','hisdb.bedtype',"#jqGrid input[name='bedtype']",errorField,
		{	colModel:[
				{label:'Bedtype',name:'bedtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
					},
			ondblClickRow:function(){
				$('#occup').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#occup').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bed Type dialog",
			open: function(){
				dialog_bedtype.urlParam.filterCol = ['recstatus','compcode'];
				dialog_bedtype.urlParam.filterVal = ['A', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_bedtype.makedialog(false);

	var dialog_ward = new ordialog(
		'ward','sysdb.department',"#jqGrid input[name='ward']",errorField,
		{	colModel:[
				{label:'Ward',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','warddept'],
				filterVal:['A', 'session.compcode','1']
					},
			ondblClickRow:function(){
				$('#tel_ext').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#tel_ext').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Ward Type",
			open: function(){
				dialog_ward.urlParam.filterCol = ['recstatus','compcode','warddept'];
				dialog_ward.urlParam.filterVal = ['A', 'session.compcode','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_ward.makedialog();

	var dialog_occup = new ordialog(
		'occup','sysdb.department',"#jqGrid input[name='occup']",errorField,
		{	colModel:
			[
				{label:'Bed Status',name:'bedcode',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
			],
			urlParam: {
				url:'./sysparam_bed_status',
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_occup.textfield).val(selrowData("#"+dialog_occup.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#room').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bed Status",
			open: function(){
				dialog_occup.urlParam.filterCol = ['recstatus','compcode'];
				dialog_occup.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_occup.makedialog(false);

	var dialog_stat = new ordialog(
		'statistic','hisdb.bed',"#jqGrid input[name='statistic']",errorField,
		{	colModel:
			[
				{label:'Statistic',name:'stat',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
			],
			urlParam: {
				url:'./sysparam_stat',
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_stat.textfield).val(selrowData("#"+dialog_stat.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#mrn').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Statistic dialog",
			open: function(){
				dialog_stat.urlParam.filterCol = ['recstatus','compcode'];
				dialog_stat.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:5/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_stat.makedialog(false);	

	var dialog_recstatus = new ordialog(
		'recstatus','hisdb.bed',"#jqGrid input[name='recstatus']",errorField,
		{	colModel:
			[
				{label:'Record Status',name:'stat',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
			],
			urlParam: {
				url:'./sysparam_recstatus',
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_recstatus.textfield).val(selrowData("#"+dialog_recstatus.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#room').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Record Status",
			open: function(){
				dialog_recstatus.urlParam.filterCol = ['recstatus','compcode'];
				dialog_recstatus.urlParam.filterVal = ['A', 'session.compcode'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_recstatus.makedialog(false);	
	//////////////////////////////////////end grid 1/////////////////////////////////////////////////////////

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		fixPost:'true',
		table_name: ['hisdb.bedalloc AS ba','hisdb.bed AS b'],
		join_type:['LEFT JOIN'],
		join_onCol:['b.bednum'],
		join_onVal:['ba.bednum'],
		join_filterCol:[['b.compcode on =']],
		join_filterVal:[['ba.compcode']],
		filterCol:['ba.mrn','ba.compcode'],
		filterVal:['','session.compcode'],
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu

	////////////////////////////////////////////////jqgrid_trf//////////////////////////////////////////////

	$("#jqGrid_trf").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'ba_compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Start Date', name: 'ba_asdate', width: 5, classes: 'wrap'},
			{ label: 'Start Time', name: 'ba_astime', width: 5, classes: 'wrap'},
            { label: 'Bed No', name: 'ba_bednum', width: 7},
			{ label: 'Room', name: 'ba_room', width: 10},
			{ label: 'Bed Type', name: 'b_bedtype', width: 15, classes: 'wrap', editable:true, canSearch: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,unformat:un_showdetail,
				edittype:'custom',	editoptions:
					{	custom_element:bedTypeTRFCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Ward', name: 'ba_ward', width: 15 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,unformat:un_showdetail,
				edittype:'custom',	editoptions:
					{ 	custom_element:wardTRFCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'idno', name: 'ba_idno', width: 20, hidden:true},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'ba_idno',
		sortorder: 'desc',
		pager: "#jqGridPager3",
		onSelectRow:function(rowid, selected){
			console.log(selrowData("#jqGrid_trf"));
			populate_form_trf(selrowData("#jqGrid_trf"));
		},
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid_trf_iladd').click();}
			else{
				$('#jqGrid_trf').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			
		},
		gridComplete: function(){

			fdl.set_array().reset();
			// if(!hide_init){
			// 	hide_init=1;
			// 	hideatdialogForm_jqGrid_trf(false);
			// }
		}
	});

	//////////////////////////////////////end grid 2/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1', '#searchForm', 'on');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam,);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);
	addParamField('#jqGrid_trf', false, urlParam2);
	//addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);

	$("#jqGrid_trf_panel").on("show.bs.collapse", function(){
		$("#jqGrid_trf").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trf_c")[0].offsetWidth-$("#jqGrid_trf_c")[0].offsetLeft-28));
	});

	/////////////////////////////transer/////////////////////////

	var dialog_bed_trf = new ordialog(
		'trf_bednum','hisdb.bed','#trf_bednum',errorField,
		{	colModel:[
	            { label: 'Bed No', name: 'bednum',classes:'pointer', width: 7,canSearch:true,checked:true,or_search:true},
	            { label: 'Ward', name: 'ward',classes:'pointer', width: 7,canSearch:true,or_search:true},
				{ label: 'Room', name: 'room',classes:'pointer', width: 10,canSearch:true,or_search:true},
				{ label: 'Bed Type', name: 'bedtype',classes:'pointer', width: 15, classes: 'wrap',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','occup'],
				filterVal:['session.compcode','A','<>.OCCUPIED']
			},
			ondblClickRow:function(){
				$(dialog_bed_trf.textfield).parent().next().html('');
				let data=selrowData('#'+dialog_bed_trf.gridname);
				$('#trf_room').val(data.room);
				$('#trf_ward').val(data.ward);
				$('#trf_bedtype').val(data.bedtype);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#purordhd_purdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Type",
			open: function(){
				dialog_bed_trf.urlParam.filterCol=['compcode','recstatus','occup'];
				dialog_bed_trf.urlParam.filterVal=['session.compcode','A','<>.OCCUPIED'];
			}
		},'urlParam','radio','tab'
	);
	dialog_bed_trf.makedialog();

	var dialog_lodger_trf = new ordialog(
		'chgcode','hisdb.chgmast',"#trf_lodger",errorField,
		{	colModel:[
				{label:'Chargecode',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['A', 'session.compcode']
					},
			ondblClickRow:function(){

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#tel_ext').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Chargecode",
			open: function(){
				dialog_lodger_trf.urlParam.filterCol = ['recstatus','compcode'];
				dialog_lodger_trf.urlParam.filterVal = ['A', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_lodger_trf.makedialog();

	button_state_trf('empty');
	function button_state_trf(state){
		switch(state){
			case 'empty':
				disableForm('#form_trf');
				$("#toggle_trf").removeAttr('data-toggle');
				$('#save_trf').data('oper','add');
				$('#save_trf,#cancel_trf,#edit_trf').attr('disabled',true);
				break;
			case 'edit':
				disableForm('#form_trf');
				$("#toggle_trf").attr('data-toggle','collapse');
				$('#save_trf').data('oper','edit');
				$("#edit_trf").attr('disabled',false);
				$('#save_trf,#cancel_trf').attr('disabled',true);
				dialog_lodger_trf.off();
				dialog_bed_trf.off();
				break;
			case 'wait':
				enableForm('#form_trf',['ba_asdate','ba_astime','ba_bednum','ba_ward','ba_room','bedtype','trf_aedate','trf_aetime','trf_room','trf_ward','trf_bedtype']);
				$("#toggle_trf").attr('data-toggle','collapse');
				$("#save_trf,#cancel_trf").attr('disabled',false);
				$('#edit_trf').attr('disabled',true);
				dialog_lodger_trf.on();
				dialog_bed_trf.on();
				break;
		}
	}

	$("#edit_trf").click(function(){
		button_state_trf('wait');
	});

	$("#save_trf").click(function(){
		disableForm('#form_trf');
		if( $('#form_trf').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_trf(function(){
				$("#cancel_trf").click();
				refreshGrid('#jqGrid_trf', urlParam2);
			});
		}else{
			enableForm('#form_trf');
		}
	});

	$("#cancel_trf").click(function(){
		button_state_trf($('#save_trf').data('oper'));
	});

	function populate_form_trf(rowdata){
		$('#name_show').text(selrowData("#jqGrid").name);
		$('#bednum_show').text(selrowData("#jqGrid").bednum);	
		autoinsert_rowdata("#form_trf",rowdata);
		$('input[name=trf_aedate]').val(moment().format('YYYY-MM-DD'));
		$('input[name=trf_aetime]').val(moment().format('HH:mm:ss'));
		button_state_trf('edit');
	}

	function empty_form_trf(){
		$('#name_show').text('');
		$('#bednum_show').text('');
		button_state_trf('empty');
	}

	function autoinsert_rowdata(form,rowData){
		$.each(rowData, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if(input.is("[type=checkbox]")){
				if(value==1){
					$(form+" [name='"+index+"']").prop('checked', true);
				}
			}else{
				input.val(value);
			}
		});
	}

	function saveForm_trf(){
		
	}

	function saveForm_trf(callback){
		var saveParam={
	        oper:'transfer_form'
	    }
	    var postobj={
	    	_token : $('#_token').val(),
	    	name: selrowData("#jqGrid").name,
	    	mrn : selrowData("#jqGrid").mrn,
			episno : selrowData("#jqGrid").episno,
			admdoctor : selrowData("#jqGrid").admdoctor,
			idno : selrowData("#jqGrid").idno,
	    };

	    var values = $("#form_trf").serializeArray();


	    $.post( "/bedmanagement/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}
});