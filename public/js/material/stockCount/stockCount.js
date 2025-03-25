
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////////////////////validation//////////////////////////
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
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ' '
				}]
			}
		},
	};

	/////////////////////////////////// currency /////////////////////////////////////////
	var mycurrency =new currencymode(["#jqGrid2 input[name='unitcost'"]);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();

	////////////////////////////////////start dialog/////////////////////////////////////
	var oper;
	var unsaved = false;
	scrollto_topbtm();

	$("#dialogForm")
	  .dialog({ 
		width: 9.5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			urlParam2.searchCol2=urlParam2.searchVal2=urlParam2.searchCol=urlParam2.searchVal=null;
            $("#jqGridPager2EditAll").show();
            $("#jqGrid2_ilsave,#jqGridPager2CancelAll,#jqGridPager2SaveAll").hide();
			unsaved = false;
			errorField.length=0;
			parent_close_disabled(true);
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					disableForm('#formdata');
                    enableForm('#formdata2');
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					break;
			}if(oper!='add'){

				dialog_itemcodefrom.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'];

				dialog_itemcodeto.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'];

				dialog_srcdept.check(errorField);

				dialog_rackno.urlParam.filterCol=['deptcode', 'recstatus','compcode','year'];
				dialog_rackno.urlParam.filterVal=[$("#srcdept").val(), 'ACTIVE','session.compcode',moment().year()];
				dialog_rackno.check(errorField);

				dialog_itemcodefrom.check(errorField);
				if($('#itemto').val() != 'ZZZ'){
					dialog_itemcodeto.check(errorField);
				}
				if(selrowData("#jqGrid").recstatus.toUpperCase() != 'OPEN'){
            		$("#jqGridPager2EditAll").hide();
				}

			}if(oper!='view'){
				dialog_srcdept.on();
				dialog_rackno.on();
				dialog_itemcodefrom.on();
				dialog_itemcodeto.on();

			}
		},
		beforeClose: function(event, ui){
			if(unsaved){
				event.preventDefault();
				bootbox.confirm("Are you sure want to leave without save?", function(result){
					if (result == true) {
						unsaved = false;
						delete_dd($('#idno').val());
						$("#dialogForm").dialog('close');
					}
				});
			}
		},
		close: function( event, ui ) {
			addmore_jqgrid2.state = false;//reset balik
			addmore_jqgrid2.more = false;
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			dialog_srcdept.off();
			dialog_rackno.off();
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			errorField.length=0;
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
		},
	});

	$("#upload_dialog")
		.dialog({ 
		width: 3/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			$("#warn_upld").hide();
			$(".ui-dialog-titlebar-close").show();
			$('#uploadbutton').prop('disabled',false);
		},
		close: function( event, ui ) {
			$("#refresh_jqGrid").click();
		}
	});

	$('#pdfupd').click(function(){
		$("#upload_dialog").dialog('open');
	});

	$("#formContent").submit(function(e){
		$('#uploadbutton').prop('disabled',true);
		$(".ui-dialog-titlebar-close").hide();
		$("#warn_upld").show();
	    $('#warn_upld').html($('#warn_upld').data('def_txt'));
	    e.preventDefault();
	    var formdata = new FormData(this);

		$.ajax({
			url: "./stockCount/form",
			type: "POST",
			data: formdata,
    		dataType: "json",
			mimeTypes:"multipart/form-data",
			contentType: false,
			cache: false,
			processData: false
		}).done(function( obj ) {
			if(obj.res == 'success'){
				$(".ui-dialog-titlebar-close").show();
		    	$('#warn_upld').html(obj.msg);
				$('#uploadbutton').prop('disabled',false);
			}else{
				$(".ui-dialog-titlebar-close").show();
		    	$('#warn_upld').html(obj.msg);
				$('#uploadbutton').prop('disabled',false);
			}
		}).fail(function(obj) {
			$(".ui-dialog-titlebar-close").show();
	    	$('#warn_upld').html(obj.msg);
			$('#uploadbutton').prop('disabled',false);
	  	});
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","recstatus");

	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field: [],
		table_name:['material.phycnthd'],
		filterCol:['compcode'],
		filterVal:['session.compcode']
		
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'stockCount_save',
		url:'./stockCount/form',
		field:'',
		oper:oper,
		table_name:'material.phycnthd',
		table_id:'recno'
	};

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'recno', width: 15, classes: 'wrap', canSearch: true,selected:true, formatter: padzero, unformat: unpadzero},
			{ label: 'Stock Department', name: 'srcdept', width: 30, classes: 'wrap text-uppercase', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Document No', name: 'docno', width: 20, classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero},
			{ label: 'Item From', name: 'itemfrom', width: 30, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Item To', name: 'itemto', width: 30, classes: 'wrap text-uppercase', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Freezed Date', name: 'frzdate', width: 20, classes: 'wrap', canSearch: true, formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'} },
			{ label: 'Freezed Time', name: 'frztime', width: 20, classes: 'wrap', },
			{ label: 'Phy. Count Date', name: 'phycntdate', width: 20, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
            { label: 'Created By', name: 'adduser', width: 20},
            { label: 'Status', name: 'recstatus', width: 20, classes: 'wrap'},	
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox, hidden:false},
			{ label: 'remarks', name: 'remarks', width: 90, hidden:true, classes: 'wrap'},
            { label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'idno', name: 'idno', width: 90, hidden:true},
            { label: 'rackno', name: 'rackno', width: 90, hidden:true},
            { label: 'phycnttime', name: 'phycnttime', width: 90, hidden:true},
			{ label: 'respersonid', name: 'respersonid', width: 90, hidden:true},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			urlParam2.filterVal[0]=selrowData("#jqGrid").recno;
			$('#recno_upld').val(selrowData("#jqGrid").recno);
			
			populate_form(selrowData("#jqGrid"));

			$('#txndeptdepan').text(selrowData("#jqGrid").srcdept);//tukar kat depan tu
			$('#docnodepan').text(selrowData("#jqGrid").docno);

			refreshGrid("#jqGrid3",urlParam2);

			$("#pdfgen1").attr('href','./stockCount/showExcel?recno='+selrowData("#jqGrid").recno);
			$("#pdfgen2").attr('href','./stockCount/showExcel?recno='+selrowData("#jqGrid").recno);

			$("#pdfgen_excel").attr('href','./stockCount/showExcel?recno='+selrowData("#jqGrid").recno);

			// $("#pdfgen_excel_import").attr('href','./stockCount/table?action=import_excel&recno='+selrowData("#jqGrid").recno);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
            
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
			populate_form(selrowData("#jqGrid"));

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			//empty_form();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
		},
		
	});

    /////////////////////padzero////////////////////////////////////////////
    function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	////////////////////// set label jqGrid right ////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,oper);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
			refreshGrid("#jqGrid2",urlParam2);
            $('#jqGridPager2EditAll').data('click',true);
		}, 
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	refreshGrid('#jqGrid',urlParam);
	addParamField('#jqGrid',false,saveParam,['upddate','upduser','adduser','adddate','idno','docno','recno','compcode','recstatus','Checkbox', 'frzdate', 'frztime', 'respersonid']);

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		var idno_array = [];
		$('#but_post_jq').prop('disabled',true);
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;

		$.post('stockCount/form',obj,function (data) {
			cbselect.empty_sel_tbl();
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			$('#but_post_jq').prop('disabled',false);
			alert(data.responseText);
		}).done(function (data) {
			$('#but_post_jq').prop('disabled',false);
			//2nd successs?
		});
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			// alert(data.responseJSON.message);
			myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			dialog_srcdept.on();
			dialog_rackno.on();
			dialog_itemcodefrom.on();
			dialog_itemcodeto.on();

		}).done(function (data) {
			
			addmore_jqgrid2.state = false;
			
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#recno').val(data.recno);
				$('#docno').val(data.docno);
				$('#idno').val(data.idno);//just save idno for edit later
				$('#adduser').val(data.adduser);
				$('#adddate').val(data.adddate);
				$('#frzdate').val(data.frzdate);
				$('#frztime').val(data.frztime);
				$('#respersonid').val(data.respersonid);
				urlParam2.filterVal[0] = data.recno; 
			}else if(selfoper=='edit'){
				//doesnt need to do anything
				$('#upduser').val(data.upduser);
				$('#upddate').val(data.upddate);
			}
			refreshGrid('#jqGrid2', urlParam2);
			disableForm('#formdata');
			
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	/////////////////////////////populate data for dropdown search By////////////////////////////
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
			searchClick2('#jqGrid','#searchForm',urlParam);
		});
	}

    //////////////////////////////////searchClick2/////////////////////////////////////////////
	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#recnodepan').text("");//tukar kat depan tu
				$('#prdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#recnodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if($('#Scol').val()=='frzdate'){
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	var supplierkatdepan = new ordialog(
		'supplierkatdepan', 'material.supplier', '#supplierkatdepan', 'errorField',
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["suppcode"];
				urlParam.searchVal=[data];
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
			title: "Select Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['ACTIVE'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}
	
	function searchChange(){
		var arrtemp = ['session.compcode',  $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ivt.compcode','ivt.recstatus','ivt.txndept'],fv:[],fc:[]});//tukar kat sini utk searching purreqhd.compcode','purreqhd.recstatus','purreqhd.prdept'

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		table_name:['material.phycntdt'],
		table_id:'idno',
		filterCol:['recno', 'compcode'],
		filterVal:['', 'session.compcode']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./stockCountDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'idno', name: 'idno',hidden:true},
		 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:false, hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 30, classes: 'wrap', editable:false},
			{ label: 'Item Code', name: 'itemcode', width: 180, classes: 'wrap', editable:false,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'UOM Code', name: 'uomcode', width: 60, classes: 'wrap', editable:false,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodetrdeptCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Unit Cost', name: 'unitcost', width: 80, align: 'right', classes: 'wrap', editable:false,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'System<br>Quantity', name: 'thyqty', width: 80, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
            { label: 'Physical<br>Quantity', name: 'phyqty', width: 80, align: 'right', classes: 'wrap', editable:true,	
                formatter:'integer',formatoptions:{thousandsSeparator: ",",},
                editrules:{required: true},
            },
            { label: 'Variance<br>Quantity', name: 'vrqty', width: 80, align: 'right', classes: 'wrap', editable:true,	
                // formatter:vrqty_formatter,
                editrules:{required: true},editoptions:{readonly: "readonly"},
            },
            { label: 'Remark', name: 'remark', width: 100, align: 'right', classes: 'wrap', editable:true,
                editrules:{required: true},edittype:"textarea", editoptions:{rows:"3"},
            },
			{ label: 'Expiry Date', name: 'expdate', width: 80, classes: 'wrap', editable:false,
			formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editrules:{required: false,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:expdateCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Batch No', name: 'batchno', width: 80, classes: 'wrap', editable:false,
					maxlength: 30,
			},
		],
		autowidth: false,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 100,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
		},
		gridComplete: function(){
			fdl.set_array().reset();
			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		afterShowForm: function (rowid) {
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	addParamField('#jqGrid2',false,urlParam2);

	count_Search("#jqGrid2",urlParam2);
	function count_Search(grid,urlParam){
		$("#count_Text").on( "keyup", function() {
			delay(function(){
				search(grid,$("#count_Text").val(),$("#count_Col").val(),urlParam);
			}, 500 );
			urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
		});

		$("#count_Col").on( "change", function() {
			search(grid,$("#count_Text").val(),$("#count_Col").val(),urlParam);
			urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
		});
	}

    //////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			$("#jqGrid2 input[name='pricecode']").focus().select();
        	$("#jqGridPager2EditAll").show();

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='unitcost']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='thyqty']", "#jqGrid2 input[name='phyqty']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2 input[name='unitcost']").on('blur',{currency: mycurrency2});
			$("#jqGrid2 input[name='thyqty']", "#jqGrid2 input[name='phyqty']").on('keyup',{currency: mycurrency_np});

			// $("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
			// 	var code = e.keyCode || e.which;
			// 	if (code == '9')$('#jqGrid2_ilsave').click();
			// });

        },
        aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			mycurrency.formatOn();
	    	if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=false; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = resobj.recno;
	    	refreshGrid('#jqGrid2',urlParam2);
	    	$("#jqGridPager2EditAll").hide();
			errorField.length=0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
        }, 
        errorfunc: function(rowid,response){
			errorField.length=0;
        	// alert(response.responseText);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
        },
        restoreAfterError : false,
        beforeSaveRow: function(options, rowid) {
       	
        	mycurrency2.formatOff();
			mycurrency_np.formatOff();

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			
			let editurl = "./stockCountDetail/form?"+
				$.param({
					action: 'stockCountDetail_save',
					idno: $('#idno').val(),
					docno:$('#docno').val(),
					recno:$('#recno').val(),
					srcdept:$('#srcdept').val(),
					phycntdate:$('#phycntdate').val(),
					phyqty:data.phyqty,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			$('#jqGrid2').jqGrid ('setSelection', "1");
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
	    }
    };

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:false,
		edit:false,
		cancel: false,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
        editParams: myEditOptions

    }).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
		    for (var i = 0; i < ids.length; i++) {
		    	let rowdata = $('#jqGrid2').jqGrid ('getRowData', ids[i]);

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		    }

            $("#jqGridPager2CancelAll,#jqGridPager2SaveAll").show();
            $("#jqGridPager2EditAll,#jqGridPager2_center").hide();

			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='phyqty']"]);
			// mycurrency_np.formatOnBlur();

			$("#jqGrid2 input[name='phyqty']").on('blur',{currency: mycurrency_np},calculate_vrqty);

		},
    }).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			myfail_msg.clear_fail();
			var ids = $("#jqGrid2").jqGrid('getDataIDs');

			var jqgrid2_data = [];
			mycurrency_np.formatOff();
		    for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

		    	var obj = 
		    	{
		    		'idno' : data.idno,
		    		'lineno_' : data.lineno_,
		    		'itemcode' : data.itemcode,
		    		'thyqty' : $("#jqGrid2 #"+ids[i]+"_thyqty").val(),
		    		'phyqty' : $("#jqGrid2 #"+ids[i]+"_phyqty").val(),
		    		'vrqty' : $("#jqGrid2 #"+ids[i]+"_vrqty").val(),
		    		'remark' : $("#jqGrid2 #"+ids[i]+"_remark").val(),
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'StockCount_save',
				_token: $("#_token").val(),
				recno: $('#recno').val()
    		}

    		$.post( "./stockCount/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
				myfail_msg.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
			}).done(function(data){
				
	            $("#jqGridPager2EditAll,#jqGridPager2_center").show();
	            $("#jqGrid2_ilsave,#jqGridPager2CancelAll,#jqGridPager2SaveAll").hide();
				refreshGrid("#jqGrid2",urlParam2);
			});
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			refreshGrid("#jqGrid2",urlParam2);

            $("#jqGridPager2EditAll,#jqGridPager2_center").show();
            $("#jqGridPager2CancelAll,#jqGridPager2SaveAll").hide();

		},
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,  case_;
		switch(options.colModel.name){
			case 'srcdept':field=['deptcode','description'];table="sysdb.department";case_='srcdept';break;
			case 'itemfrom':field=['itemcode','description'];table="material.product";case_='itemfrom';break;
			case 'itemto':field=['itemcode','description'];table="material.product";case_='itemto';break;

			case 'itemcode':field=['itemcode','description'];table="material.product";case_='itemcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('stockCount',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;
		if(options.gid == "jqGrid" && rowObject.recstatus == 'OPEN'){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject.idno+"' data-idno='"+rowObject.idno+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid" && rowObject.recstatus == 'OPEN'){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject.idno+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp=null;
		switch(name){
			case 'Item Code':temp=$('#itemcode');break;
			case 'UOM Code':temp=$('#uomcode');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val,opt){
		// val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function uomcodetrdeptCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefine") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function expdateCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefine") ? "" : val.slice(0, val.search("[<]"));
		 return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'"  name="expdate" type="text" class="form-control input-sm" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
		
	}
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_srcdept.off();
			dialog_rackno.off();
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		}else{
			mycurrency.formatOn();
		}
	});

	function saveDetailLabel(callback=null){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_srcdept.off();
			dialog_rackno.off();
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			saveHeader("#formdata",oper,saveParam);
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
		if(callback!=null)callback();
	}

	////////////////////////////////////////remove_noti////////////////////////////
	
	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		remove_error("#jqGrid2 #"+id_optid+"_uomcoderecv");
		remove_error("#jqGrid2 #"+id_optid+"_txnqty");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_uomcoderecv");
		}, 500 );


		$(".noti").empty();

	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",

		onSelectRow:function(rowid, selected){
		},

		gridComplete:function(){
			fdl.set_array().reset();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid3");
		},
	});
	
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_srcdept = new ordialog(
		'srcdept','sysdb.department','#srcdept',errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
			},
			ondblClickRow: function () {
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
			title:"Select Department",
			open: function(){
				dialog_srcdept.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dialog_srcdept.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_srcdept.makedialog(true);
	
	var dialog_rackno = new ordialog(
		'rackno','material.stockloc','#rackno','errorField',
		{
			colModel:[
				{label:'Rack No',name:'rackno',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'itemcode',name:'itemcode', hidden:true},
				// {label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['deptcode', 'recstatus','compcode','year'],
				filterVal:[$("#srcdept").val(), 'ACTIVE','session.compcode',moment().year()],
			},
			ondblClickRow: function () {
				$("#itemto").val('ZZZ').prop('readonly',true);
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
			title:"Select Rack No",
			open: function(){
				dialog_rackno.urlParam.url = "./stockFreeze/table";
				dialog_rackno.urlParam.action = "get_rackno";
				dialog_rackno.urlParam.filterCol=['deptcode', 'recstatus','compcode','year'];
				dialog_rackno.urlParam.filterVal=[$("#srcdept").val(), 'ACTIVE','session.compcode',moment().year()];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_rackno.makedialog(true);
	
	var dialog_itemcodefrom = new ordialog(
		'itemfrom',['material.stockloc AS s','material.product AS p', 'material.stockexp AS e'],"#itemfrom",errorField,

		{	
			colModel:[
				{label: 'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'s_uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'deptcode',name:'s_deptcode',width:200,classes:'pointer',hidden:true},
				{label: 'year',name:'s_year',width:100,classes:'pointer', hidden:true},
				{label: 'unitcost',name:'p_currprice',width:100,classes:'pointer', hidden:true},
				{label: 'thyqty',name:'e_balqty',width:100,classes:'pointer', hidden:true},
				{label: 'expdate',name:'e_expdate',width:100,classes:'pointer', hidden:true},
				{label: 'batchno',name:'e_batchno',width:100,classes:'pointer', hidden:true},

			],
			urlParam: {
				fixPost : "true",
				filterCol:['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'],//,'sector'
				filterVal:['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'],
				join_type:['LEFT JOIN', 'LEFT JOIN'],
				join_onCol:['s.itemcode', 's.itemcode'],
				join_onVal:['p.itemcode', 'e.itemcode'],
				join_filterCol:[['s.compcode on =','s.uomcode on =','s.unit on ='],['s.compcode on =','s.uomcode on =','s.unit on =','s.deptcode on =','s.year on =']],
				join_filterVal:[['p.compcode','p.uomcode','p.unit'],['e.compcode','e.uomcode','e.unit','e.deptcode','e.year']],
			},
			ondblClickRow: function () {
	
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
			title:"Select Department",
			open: function(){
				dialog_itemcodefrom.urlParam.table_name = ['material.stockloc AS s','material.product AS p', 'material.stockexp AS e'];
				dialog_itemcodefrom.urlParam.fixPost = "true";
				dialog_itemcodefrom.urlParam.table_id = "none_";
				dialog_itemcodefrom.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment($('#s_itemcode').val()).year(),'session.unit'];//, 'session.unit'
				dialog_itemcodefrom.urlParam.join_type = ['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcodefrom.urlParam.join_onCol = ['s.itemcode', 's.itemcode'];
				dialog_itemcodefrom.urlParam.join_onVal = ['p.itemcode', 'e.itemcode'];
				dialog_itemcodefrom.urlParam.join_filterCol = [['s.compcode on =','s.uomcode on =','s.unit on ='],['s.compcode on =','s.uomcode on =','s.unit on =','s.deptcode on =','s.year on =']];
				dialog_itemcodefrom.urlParam.join_filterVal = [['p.compcode','p.uomcode','p.unit'],['e.compcode','e.uomcode','e.unit','e.deptcode','e.year']];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_itemcodefrom.makedialog(true);
	
	var dialog_itemcodeto = new ordialog(
		'itemto',['material.stockloc AS s','material.product AS p', 'material.stockexp AS e'],"#itemto",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'s_uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'deptcode',name:'s_deptcode',width:200,classes:'pointer',hidden:true},
				{label: 'year',name:'s_year',width:100,classes:'pointer', hidden:true},
				{label: 'unitcost',name:'p_currprice',width:100,classes:'pointer', hidden:true},
				{label: 'thyqty',name:'e_balqty',width:100,classes:'pointer', hidden:true},
				{label: 'expdate',name:'e_expdate',width:100,classes:'pointer', hidden:true},
				{label: 'batchno',name:'e_batchno',width:100,classes:'pointer', hidden:true},

			],
			urlParam: {
				fixPost : "true",
				filterCol:['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'],//,'sector'
				filterVal:['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'],
				join_type:['LEFT JOIN', 'LEFT JOIN'],
				join_onCol:['s.itemcode', 's.itemcode'],
				join_onVal:['p.itemcode', 'e.itemcode'],
				join_filterCol:[['s.compcode on =','s.uomcode on =','s.unit on ='],['s.compcode on =','s.uomcode on =','s.unit on =','s.deptcode on =','s.year on =']],
				join_filterVal:[['p.compcode','p.uomcode','p.unit'],['e.compcode','e.uomcode','e.unit','e.deptcode','e.year']],
			},
			ondblClickRow: function () {
				$("#jqGrid2").jqGrid("clearGridData", true);
				let itemfrom = $('#itemfrom').val();
				let itemto = $('#itemto').val();

			
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
			title:"Select Department",
			open: function(){
				dialog_itemcodeto.urlParam.table_name = ['material.stockloc AS s','material.product AS p', 'material.stockexp AS e'];
				dialog_itemcodeto.urlParam.fixPost = "true";
				dialog_itemcodeto.urlParam.table_id = "none_";
				dialog_itemcodeto.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment($('#s_itemcode').val()).year(),'session.unit'];//, 'session.unit'
				dialog_itemcodeto.urlParam.join_type = ['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcodeto.urlParam.join_onCol = ['s.itemcode', 's.itemcode'];
				dialog_itemcodeto.urlParam.join_onVal = ['p.itemcode', 'e.itemcode'];
				dialog_itemcodeto.urlParam.join_filterCol = [['s.compcode on =','s.uomcode on =','s.unit on ='],['s.compcode on =','s.uomcode on =','s.unit on =','s.deptcode on =','s.year on =']];
				dialog_itemcodeto.urlParam.join_filterVal = [['p.compcode','p.uomcode','p.unit'],['e.compcode','e.uomcode','e.unit','e.deptcode','e.year']];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_itemcodeto.makedialog(true);

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid_selection");
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function errorIt(name,errorField,fail,fail_msg){
		let id = "#jqGrid2 input[name='"+name+"']";
		if(!fail){
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
			$( id ).removeClass( "error" ).addClass( "valid" );
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		}else{
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			}
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
	
	function check_cust_rules(grid,data){
		var cust_val =  true;
		Object.keys(data).every(function(v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( './inventoryTransactionDetail/form',obj,function( data ) {
					
			});
		}
	}

});

function populate_form(obj){
	//panel header
	$('#srcdept_show').text(obj.srcdept);
	$('#docno_show').text(padzero(obj.docno));
}

function empty_form(){
	$('#srcdept_show').text('');
	$('#docno_show').text('');
}

function vrqty_formatter(cellvalue, options, rowObject){
	let thyqty = rowObject.thyqty;
	if(thyqty == null){
		thyqty = 0;
	}
	let phyqty = rowObject.phyqty;
	if(phyqty == null){
		phyqty = 0;
	}

	return parseInt(phyqty) - parseInt(thyqty);
}

function calculate_vrqty(event){
	// event.data.currency.formatOff();
	
	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));

	let phyqty = parseFloat($("#jqGrid2 #"+id_optid+"_phyqty").val());
	let thyqty = parseFloat($("#jqGrid2 #"+id_optid+"_thyqty").val());
	let vrqty =  phyqty - thyqty;

	$("#jqGrid2 #"+id_optid+"_vrqty").val(vrqty);


	$("#jqGrid2 #"+id_optid+"_phyqty").val(numeral($("#jqGrid2 #"+id_optid+"_phyqty").val()).format('0,0'));
	// event.data.currency.formatOn();
}

