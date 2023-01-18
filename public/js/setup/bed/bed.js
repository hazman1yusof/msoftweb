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
                    element : $('#'+errorField[0]),
                    message : ' '
                }
            }
        },
    };

	var fdl = new faster_detail_load();
	var err_reroll = new err_reroll('#jqGrid',['bednum','bedtype','occup','room','ward','statistic','bedchgcode']);

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Bed Type':temp=$("#jqGrid input[name='bedtype']");break;
			case 'Ward':temp=$("#jqGrid input[name='ward']");break;
			case 'Bed Status':temp=$("#jqGrid input[name='occup']");break;
			// case 'Charge Code':temp=$('#bedchgcode');break;
			case 'Statistic':temp=$("#jqGrid input[name='statistic']");break;
			case ' ':temp=$("#jqGrid input[name='recstatus']");break;
				break;
		}

		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			//case 'room':field=['room','description'];table="hisdb.episode";case_='room';break;
			case 'bedtype':field=['bedtype','description'];table="hisdb.bedtype";case_='bedtype';break;
			case 'ward': field = ['deptcode', 'description']; table = "sysdb.department";case_='ward';break;
			case 'bedchgcode': field = ['chgcode', 'description']; table = "hisdb.chgmast";case_='bedchgcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		fdl.get_array('bed',options,param,case_,cellvalue);
		
		if(cellvalue==null)return "";
		return cellvalue;
	}
	
	function occupCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="occup" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function bedTypeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="bedtype" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function wardCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="ward" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function occupCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="occup" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function statCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val;
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="statistic" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function chgcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="bedchgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function recstatusCustomEdit(val, opt) {
		if(val == '<span class="fa fa-times"></span>'){
			val = 'DEACTIVE';
		}else{
			val = 'ACTIVE';
		}
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="recstatus" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		url: './bed/table',
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
		editurl: "./bed/form",
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
			{ label: 'Room', name: 'room', width: 8, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			// { label: 'Ward', name: 'ward', width: 5, canSearch: true, editable: true, editrules: { required: true }, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Ward', name: 'ward', width: 25 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{ 	custom_element:wardCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Tel Ext', name: 'tel_ext', width: 8, canSearch: false, checked: true, editable: true, editoptions: {style: "text-transform: uppercase" }},
			{ label: 'Statistic', name: 'statistic', width: 10, classes: 'wrap', formatter: stat_format, unformat:stat_unformat, canSearch: false, editable: true,editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ 	custom_element:statCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'MRN', name: 'mrn', width: 8, canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: ' ', name: 'episno', width: 5, align : 'right'},
			{ label: 'Patient Name', name: 'name', width: 30, canSearch: true, classes: 'wrap'},
			// { label: 'Charge Code', name: 'cm_chgcode', classes: 'wrap', width: 30, canSearch: true},
			{ label: 'Charge Code', name: 'bedchgcode', width: 25 , classes: 'wrap', editable:true,
				editrules:{required: false}, formatter: showdetail,
					edittype:'custom',	editoptions:
						{ 	custom_element:chgcodeCustomEdit,
							custom_value:galGridCustomValue 	
						},
			},
			// { label: ' ', name: 'recstatus', width: 10, classes: 'left_td', editable: true,formatter:formatterstatus_tick,unformat:unformatstatus_tick, editrules:{required: true,custom:true, custom_func:cust_rules},
			// 	edittype:'custom',	editoptions:
			// 	{ 	custom_element:recstatusCustomEdit,
			// 		custom_value:galGridCustomValue 	
			// 	},
			// },
			{ label: 'recstatus', name: 'recstatus', width:10, hidden: true},
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
				// refreshGrid('#jqGrid', urlParam,'kosongkan');

				if (rowData['mrn'] != '') {
					$("#jqGridPagerDelete").hide();
					$("#jqGrid_iledit").hide();
				}
				else if (rowData['mrn'] == '') {
					$("#jqGridPagerDelete").show();
					$("#jqGrid_iledit").show();
				}
			}

			if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
                $('#jqGrid_iladd').click();
            }else if($('#jqGrid').data('lastselrow') == 'none'){
				$('#jqGrid2').jqGrid ('setSelection', "1");
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
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}			
        },
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid2.state==true)addmore_jqgrid.more=true; //only addmore after save inline
			//refreshGrid('#jqGrid',urlParam,'add');
		}, 
	});

	function check_cust_rules(rowid){
		var chk = ['bednum','bedtype','occup','room','ward','statistic','bedchgcode'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid input[name='"+e+"']",false);
			}
		})
	}

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
			$("#div_occup").hide();
			$("#div_bedtype").show();
		} else if($('#Scol').val() == 'occup'){
			$("#div_bedtype").hide();
			$("#div_occup").show();	
		} else {
			$("#div_bedtype,#div_occup").hide();
		}
	}
	/////////////////////////////End populate data for search By dropdown and btn////////////////////////////

	function padzero(cellvalue, options, rowObject){
		if(cellvalue == null){
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

	function stat_format(cellvalue, options, rowObject){
		if(cellvalue == null){
			return "";
		}else if(cellvalue == '1'){
			return 'TRUE';
		}else if(cellvalue == '0'){
			return 'FALSE';
		}
	}

	function stat_unformat(cellvalue, options, rowObject){
		if(cellvalue == 'TRUE'){
			return '1';
		}else if(cellvalue == 'FALSE'){
			return '0';
		}else{
			return '';
		}
	}


	function occup(cellvalue, options, rowObject){
		if(cellvalue==undefined){
			cellvalue="";
		}
		switch(cellvalue.trim()){
			case 'OCCUPIED': return '<i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED';break;
			case 'VACANT': return '<img src="img/bedonly.png" height="10" width="14"></img> VACANT';break;
			case 'HOUSEKEEPING': return '<i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING';break;
			case 'MAINTENANCE': return '<i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE';break;
			case 'ISOLATED': return '<i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED';break;
			case 'RESERVE': return '<i class="fa fa-ban" aria-hidden="true"></i> RESERVE';break;
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
			default: return cellvalue;break;
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
			$('.selectpicker').selectpicker("refresh");
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			dialog_chargecode.on();
			dialog_stat.on();
			// dialog_recstatus.on();
			$("input[name='bedchgcode']").keydown(function(e) {//when click tab at last column in header, auto save
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
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);

			check_cust_rules();

			let editurl = "./bed/form?"+
				$.param({
					action: 'bed_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid',urlParam,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		},
	};

	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
            $('#jqGrid').data('lastselrow',rowid);
			$('.selectpicker').selectpicker("refresh");
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();
			dialog_ward.on();
			dialog_bedtype.on();
			dialog_occup.on();
			dialog_chargecode.on();
			dialog_stat.on();
			// dialog_recstatus.on();
			$("input[name='bednum']").attr('disabled','disabled');
			$("input[name='bedchgcode']").keydown(function(e) {//when click tab at last column in header, auto save
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

			check_cust_rules();

			let editurl = "./bed/form?"+
				$.param({
					action: 'bed_save',
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
								action: 'bed_save',
								bednum: $('#bednum').val(),
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./bed/form?"+$.param(param),{oper:'del'}, function( data ){
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
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + search_bedtype.gridname).bedtype;
				$("#searchForm input[name='Stext']").val($('#s_bedtype').val());

				urlParam.searchCol=["bedtype"];
				urlParam.searchVal=[data];
				refreshGrid("#jqGrid3",null,"kosongkan");
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_bedtype.urlParam.filterCol=['compcode', 'recstatus'];
				search_bedtype.urlParam.filterVal=['session.compcode', 'ACTIVE'];
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
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function (event) {

				let data = selrowData('#' + search_occup.gridname);
				$("#searchForm input[name='Stext']").val(data.description);
				console.log(data.description);
				if(data.description == 'ACTIVE' || data.description == 'DEACTIVE'){
					let val_use = (data.description == 'ACTIVE')? 'ACTIVE':'DEACTIVE';
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
				search_occup.urlParam.filterVal=['session.compcode', 'ACTIVE'];
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	search_occup.makedialog();
	search_occup.on();

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
				filterVal:['ACTIVE', 'session.compcode']
					},
			ondblClickRow:function(){
				$('#occup').focus().select();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#occup').focus().select();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Bed Type dialog",
			open: function(){
				dialog_bedtype.urlParam.filterCol = ['recstatus','compcode'];
				dialog_bedtype.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			close: function(){
				$("#jqGrid input[name='occup']").focus().select();
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
				filterCol:['recstatus','compcode','warddept','sector'],
				filterVal:['ACTIVE', 'session.compcode','1','session.unit']
					},
			ondblClickRow:function(){
				$('#tel_ext').focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#tel_ext').focus().select();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Ward Type",
			open: function(){
				dialog_ward.urlParam.filterCol = ['recstatus','compcode','warddept','sector'];
				dialog_ward.urlParam.filterVal = ['ACTIVE', 'session.compcode','1','session.unit'];
			},
			close: function(){
				$("#jqGrid input[name='tel_ext']").focus().select();
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
				filterVal:['ACTIVE', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_occup.textfield).val(selrowData("#"+dialog_occup.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#room').focus().select();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bed Status",
			open: function(){
				dialog_occup.urlParam.filterCol = ['recstatus','compcode'];
				dialog_occup.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			width:4/10 * $(window).width(),
			close: function(){
				$("#jqGrid input[name='room']").focus().select();
			}
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
				filterVal:['ACTIVE', 'session.compcode']
				},
			ondblClickRow:function(event){

				$(dialog_stat.textfield).val(selrowData("#"+dialog_stat.gridname)['description']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#bedchgcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Statistic dialog",
			open: function(){
				dialog_stat.urlParam.filterCol = ['recstatus','compcode'];
				dialog_stat.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			width:5/10 * $(window).width(),
			close: function(){
				$("#jqGrid input[name='bedchgcode']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_stat.makedialog(false);	

	var dialog_chargecode = new ordialog(
		'chgcode','hisdb.chgmast',"#jqGrid input[name='bedchgcode']",'errorField',
		{	colModel:[
				{label:'Chargecode',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE', 'session.compcode']
					},
			ondblClickRow:function(){

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					 $('#recstatus').focus().select();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Chargecode",
			open: function(){
				dialog_chargecode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_chargecode.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			close: function(){
				$("#jqGrid input[name='recstatus']").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_chargecode.makedialog();

	// var dialog_recstatus = new ordialog(
	// 	'recstatus','hisdb.bed',"#jqGrid input[name='recstatus']",errorField,
	// 	{	colModel:
	// 		[
	// 			{label:'Record Status',name:'stat',width:200,classes:'pointer left',canSearch:true,checked:true,or_search:true},
	// 			{label:'Description',name:'description',width:400,classes:'pointer', hidden: true,canSearch:false,or_search:true},
	// 		],
	// 		urlParam: {
	// 			url:'./sysparam_recstatus',
	// 			filterCol:['recstatus','compcode'],
	// 			filterVal:['ACTIVE', 'session.compcode']
	// 			},
	// 		ondblClickRow:function(event){

	// 			$(dialog_recstatus.textfield).val(selrowData("#"+dialog_recstatus.gridname)['description']);

	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				// $('#room').focus();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	},{
	// 		title:"Select Record Status",
	// 		open: function(){
	// 			dialog_recstatus.urlParam.filterCol = ['recstatus','compcode'];
	// 			dialog_recstatus.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
	// 		},
	// 		width:4/10 * $(window).width()
	// 	},'urlParam','radio','tab'
	// );
	// dialog_recstatus.makedialog(false);
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1', '#searchForm', 'on');
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