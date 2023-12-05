
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

	///////////////////////////////// trandate check date validate from period////////////
	var itemcodeObj = new setactdate(["#frzdate"]);
	itemcodeObj.getdata().set();

	////////////////////////////////////start dialog/////////////////////////////////////
	var oper;
	var unsaved = false;

	$("#dialogForm")
	  .dialog({ 
		width: 9.5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			$('#srcdept').focus();
			$('#jqGridPager2EditAll').data('click',false);
			unsaved = false;
			errorField.length=0;
			parent_close_disabled(true);
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", true);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					break;
			}if(oper!='add'){

				dialog_itemcodefrom.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'];

				dialog_itemcodeto.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode', $('#srcdept').val(), moment().year(),'session.unit'];

				dialog_srcdept.check(errorField);
				dialog_itemcodefrom.check(errorField);
				dialog_itemcodeto.check(errorField);

			}if(oper!='view'){
				dialog_srcdept.on();
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
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			errorField.length=0;
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
		},
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
		action:'stockFreeze_save',
		url:'./stockFreeze/form',
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
			{ label: 'Freezed Date', name: 'frzdate', width: 20, classes: 'wrap', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Freezed Time', name: 'frztime', width: 20, classes: 'wrap', formatter: timeFormatter, unformat: timeUNFormatter},
			{ label: 'Phy. Count Date', name: 'phycntdate', width: 20, align: 'right', classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
            { label: 'Created By', name: 'adduser', width: 20},
            { label: 'Status', name: 'recstatus', width: 20, classes: 'wrap'},	
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
			{ label: 'remarks', name: 'remarks', width: 90, hidden:true, classes: 'wrap'},
            { label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
			// { label: 'source', name: 'source', width: 40, hidden:'true'},
			{ label: 'idno', name: 'idno', width: 90, hidden:true},
            { label: 'rackno', name: 'rackno', width: 90, hidden:true},
            { label: 'phycnttime', name: 'phycnttime', width: 90, hidden:true},
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
			
			populate_form(selrowData("#jqGrid"));

			$('#txndeptdepan').text(selrowData("#jqGrid").txndept);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").trantype);
			$('#docnodepan').text(selrowData("#jqGrid").docno);


			refreshGrid("#jqGrid3",urlParam2);

			$("#pdfgen1").attr('href','./inventoryTransaction/showpdf?recno='+selrowData("#jqGrid").recno);

			$("#pdfgen2").attr('href','./inventoryTransaction/showpdf?recno='+selrowData("#jqGrid").recno);

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
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		id: 'glyphicon-plus',
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	refreshGrid('#jqGrid',urlParam);
	addParamField('#jqGrid',false,saveParam,['upddate','upduser','adduser','adddate','idno','docno','recno','compcode','recstatus','Checkbox']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
	function hideatdialogForm(hide,saveallrow){
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

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;

		$.post('stockFreeze/form',obj,function (data) {
			cbselect.empty_sel_tbl();
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
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
			dialog_itemcodefrom.on();
			dialog_itemcodeto.on();

		}).done(function (data) {
			hideatdialogForm(false);
			
			addmore_jqgrid2.state = true;
			
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#recno').val(data.recno);
				$('#docno').val(data.docno);
				$('#idno').val(data.idno);//just save idno for edit later
				$('#adduser').val(data.adduser);
				$('#adddate').val(data.adddate);
				
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

	///////////////////////////populate data for dropdown tran dept/////////////////////////////
	trandept();
	function trandept(){
		var param={
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['deptcode'],
			table_name:'sysdb.department',
			filterCol:['storedept'],
			filterVal:['1']
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$.each(data.rows, function(index, value ) {
					if(value.deptcode.toUpperCase()== $("#deptcode").val().toUpperCase()){
						$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
					}else{
						$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
					}
				});
			}
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
		editurl: "./stockFreezeDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:false, hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Item Code', name: 'itemcode', width: 200, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'UOM Code', name: 'uomcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodetrdeptCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Unit Cost', name: 'unitcost', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'System<br>Quantity', name: 'thyqty', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
            { label: 'Physical<br>Quantity', name: 'phyqty', width: 100, align: 'right', classes: 'wrap', editable:true,	
                formatter:'integer',formatoptions:{thousandsSeparator: ",",},
                editrules:{required: true},editoptions:{readonly: "readonly"},
            },
            { label: 'Variance<br>Quantity', name: 'vrqty', width: 100, align: 'right', classes: 'wrap', editable:true,	
                formatter:vrqty_formatter,
                editrules:{required: true},editoptions:{readonly: "readonly"},
            },
			{ label: 'Expiry Date', name: 'expdate', width: 130, classes: 'wrap', editable:true,
			formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editrules:{required: false,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:expdateCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Batch No', name: 'batchno', width: 170, classes: 'wrap', editable:true,
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
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
			// if(addmore_jqgrid2.edit == true){
			// var linenotoedit_new = parseInt(linenotoedit)+1;
			// 	if($.inArray(String(linenotoedit_new),$('#jqGrid2').jqGrid ('getDataIDs')) != -1){
			// 		$('#jqGrid2').jqGrid ('setSelection', String(linenotoedit_new));
			// 		$('#jqGrid2_iledit').click();
			// 	}
			// }
			// else if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			// else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
			// 	$('#jqGrid2_iladd').click();
			// }else{
			// 	$('#jqGrid2').jqGrid ('setSelection', "1");
			// }

			// addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			// calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			fdl.set_array().reset();
			// $( "#jqGrid2_ilcancel" ).off();
			// $( "#jqGrid2_ilcancel" ).on( "click", function(event) {
			// 	event.preventDefault();
			// 	event.stopPropagation();
			// 	bootbox.confirm({
			// 	    message: "Are you sure want to cancel?",
			// 	    buttons: {
			// 	        confirm: { label: 'Yes',className: 'btn-success'},
			// 	        cancel: {label: 'No',className: 'btn-danger'}
			// 		},
			// 		callback: function (result) {
			// 			if (result == true) {
			// 				$(".noti").empty();
			// 				$("#jqGrid2").jqGrid("clearGridData", true);
			// 				refreshGrid("#jqGrid2",urlParam2);
			// 			}
			// 			linenotoedit = null;
			// 	    }
			// 	});
			// });

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;
		},
		afterShowForm: function (rowid) {
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	addParamField('#jqGrid2',false,urlParam2,['vrqty']);

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2");
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='unitcost']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='thyqty']", "#jqGrid2 input[name='phyqty']"]);
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			mycurrency_np.formatOnBlur();//make field to currency on leave cursor
	
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='batchno']").keydown(function(e) {//when click tab at qtyrequest, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});

		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#recno').val(resobj.recno);
			$('#docno').val(resobj.docno);
			$('#amount').val(resobj.totalAmount);
			// $('#totamount').val(response.responseText);
			// $('#subamount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = resobj.recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
			errorField.length=0;
        	// alert(response.responseText);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
		beforeSaveRow: function (options, rowid) {
        	if(errorField.length>0)return false;
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			// if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);

			let editurl = "./inventoryTransactionDetail/form?"+
				$.param({					
					action: 'invTranDetail_save',
					idno: $('#idno').val(),
					docno:$('#docno').val(),
					recno:$('#recno').val(),
					sndrcv:$('#sndrcv').val(),
					txndept:$('#txndept').val(),
					trandate:$('#trandate').val(),
					trantype:$('#trantype').val(),
					lineno_:data.lineno_,
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		 afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);
	    },
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
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
				    			action: 'stockFreezeDetail_save',
								recno: $('#recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
				    		}
				    		$.post( "./stockFreezeDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								// $('#amount').val(data);
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
			errorField.length=0;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			// var ids = $("#jqGrid2").jqGrid('getDataIDs');
		    // for (var i = 0; i < ids.length; i++) {

		    //     $("#jqGrid2").jqGrid('editRow',ids[i]);

		    //     Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);

		    //     dialog_itemcode.id_optid = ids[i];
		    //     dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		    //     	function(self){
		    //     		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			//         }
			//     );

			// 	dialog_expdate.id_optid = ids[i];
		    //     dialog_expdate.check('errorField',ids[i]+"_expdate","jqGrid2",null,
		    //     	function(self){
		    //     		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			//         }
			//     );

		    //     dialog_uomcoderecv.id_optid = ids[i];
		    //     dialog_uomcoderecv.check(errorField,ids[i]+"_uomcoderecv","jqGrid2",null,
		    //     	function(self){
			//         	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			//         }
			//     );

			// 	dialog_uomcodetrdept.id_optid = ids[i];
		    //     dialog_uomcodetrdept.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		    //     	function(self){
			//         	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			//         }
			//     );

		    //    // cari_gstpercent(ids[i]);
		    // }
		    onall_editfunc();
			hideatdialogForm(true,'saveallrow');
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
			mycurrency_np.formatOff();

			if(errorField.length>0){
				return false;
			}

		    for (var i = 0; i < ids.length; i++) {
			//	if(parseInt($('#'+ids[i]+"_qtyrequest").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				if(retval[0]!= true){
					alert(retval[1]);
					return false;
				}

				// cust_rules()

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'uomcoderecv' : $("#jqGrid2 input#"+ids[i]+"_uomcoderecv").val(),
		    		'netprice' : $('#'+ids[i]+"_netprice").val(),
		    		'qtyonhand' : $('#'+ids[i]+"_qtyonhand").val(),
		    		'qtyonhandrecv' : $('#'+ids[i]+"_qtyonhandrecv").val(),
		    		'txnqty' : $('#'+ids[i]+"_txnqty").val(),
                    'unit' : $("#"+ids[i]+"_unit").val(),
		    		'amount' : $('#'+ids[i]+"_amount").val(),
		    		'batchno' : $('#'+ids[i]+"_batchno").val(),
		    		'expdate' : $('#'+ids[i]+"_expdate").val(),

		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invTranDetail_save',
				_token: $("#_token").val(),
				recno: $('#recno').val(),
				docno:$('#docno').val(),
				sndrcv:$('#sndrcv').val(),
				txndept:$('#txndept').val(),
				trandate:$('#trandate').val(),
				
    		}

    		$.post( "./stockFreezeDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				alert(dialog,data.responseText);
			}).done(function(data){
				// $('#totamount').val(data);
				// $('#subamount').val(data);
				mycurrency.formatOn();
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			});
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2);
		},				
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveHeaderLabel",
		caption:"Header",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Header"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveDetailLabel",
		caption:"Save",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Save"
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
	
		fdl.get_array('stockFreeze',options,param,case_,cellvalue);
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
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			saveHeader("#formdata",oper,saveParam);
			//generate_stock_freeze();
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
			dialog_itemcodefrom.off();
			dialog_itemcodeto.off();

			saveHeader("#formdata",oper,saveParam);
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
		if(callback!=null)callback();
	}

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_srcdept.on();
		dialog_itemcodefrom.on();
		dialog_itemcodeto.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	// $("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
	// 	$("#jqGridPager2Delete,#saveHeaderLabel").hide();
	// 	dialog_itemcode.on();//start binding event on jqgrid2
	// 	expdate_stat($('#isstype').val(),$('#crdbfl').val());
	// 	$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
	// 	$("#jqGrid2 input[name='qtyonhandrecv']").on('blur',calculate_conversion_factor);
	// 	$("#jqGrid2 input[name='qtyonhand']").on('blur',checkQOH);
	// 	$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
	// 		var code = e.keyCode || e.which;
	// 		if (code == '9')$('#jqGrid2_ilsave').click();
	// 	});
	// });

	/////////////calculate conv fac/////////////////////////////////
	 function calculate_conversion_factor(event) {
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		var id="#jqGrid2 #"+id_optid+"_qtyonhand";
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uomcodetrdept = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodetrdept").val());
		let convfactor_uomcoderecv = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcoderecv").val());
		let qtyonhand = parseFloat($("#jqGrid2 #"+id_optid+"_qtyonhand").val());

		var balconv = convfactor_uomcodetrdept*qtyonhand%convfactor_uomcoderecv;
		if (balconv  == 0) {
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		} else {
			$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
			}
		}
	}

	////////////////////////////////////////calculate amount////////////////////////////
	function calculate_amount_and_other(event){

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		var fail=false,fail_msg="";
		let qtyonhand=parseInt($("#jqGrid2 #"+id_optid+"_qtyonhand").val());
		let txnqty=parseInt($("#jqGrid2 #"+id_optid+"_txnqty").val());
		let netprice=parseFloat($("#jqGrid2 #"+id_optid+"_netprice").val());

		let crdbfl=$('#crdbfl').val();
		let isstype=$('#isstype').val();
		if(event.target.name=='txnqty'){
			switch(crdbfl){
				case "Out":
					if(event.target.value >= qtyonhand && isstype=='Others'){
						fail_msg = "Transaction Quantity Cannot be greater than Quantity On Hand";
						event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					}else if(qtyonhand<event.target.value){
						fail_msg = "Transaction quantity exceed quantity on hand";
						event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					}else if(qtyonhand<event.target.value && isstype=='Transfer'){
						fail_msg = "Transaction quantity exceed quantity on hand";
						event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					}
					break;
				case "In":
					if(event.target.name == 0 && isstype=='Others'){
						fail_msg = "Transaction Quantity Cannot Be Zero";
						event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					}
					break;
				default:
					break;
			}
		}else{
			if(crdbfl=='Out'&&event.target.value==0){
				fail_msg = "Net Price Cannot Be Zero";
				event.target.value='0.00';fail=true;
			}
		}
		errorIt(event.target.name,errorField,fail,fail_msg);
		let amount=txnqty*netprice;
		$("#jqGrid2 #"+id_optid+"_amount").val(amount.toFixed(4));
	}

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

	/////////////////////////////edit all//////////////////////////////////////////////////

	// function onall_editfunc(){
	
	// 	errorField.length=0;
	// 	//start binding event on jqgrid2
	// 	dialog_itemcode.on();
	// 	dialog_expdate.on();
	// 	dialog_uomcoderecv.on();
	// 	dialog_uomcodetrdept.on();
		
	// 	mycurrency2.formatOnBlur();//make field to currency on leave cursor
	// 	mycurrency_np.formatOnBlur();//make field to currency on leave cursor


	// 	$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
	
	// 	// $("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='uomcoderecv'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);
	// }

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
				// $("#jqGrid2").jqGrid("clearGridData", true);

				// // let itemcodefrom = $('#itemfrom').val();
				// // let itemcodeto = $('#itemto').val();

				// let data=selrowData('#'+dialog_itemcodefrom.gridname);
				// 	$("#jqGrid2 input[name='itemcode']").val(data['itemcode']);
				// 	$("#jqGrid2 input[name='uomcode']").val(data['uomcode']); 
				// 	$("#jqGrid2 input[name='unitcost']").val(data['currprice']);
				// 	$("#jqGrid2 input[name='thyqty']").val(data['balqty']);
				// 	$("#jqGrid2 input[name='batchno']").val(data['batchno']);
				// 	$("#jqGrid2 input[name='expdate']").val(data['expdate']);

				// var urlParam_ = {
				// 	action: 'get_value_default',
				// 	url: 'util/get_value_default',
				// 	field:['s.deptcode', 's.itemcode', 's.uomcode', 'p.description', 'p.currprice', 'e.balqty','e.expdate', 'e.batchno'],
				// 	table_name:['material.stockloc AS s', 'material.product AS p', 'material.stockexp AS e'],
				// 	join_type:['LEFT JOIN', 'LEFT JOIN'],
				// 	join_onCol:['s.itemcode', 's.itemcode'],
				// 	join_onVal:['p.itemcode', 'e.itemcode'],
				// 	filterCol:['s.compcode', 's.deptcode', 's.year', 's.itemcode'],
				// 	filterVal:['session.compcode',$('#srcdept').val(), moment($('#s_itemcode').val()).year(), moment($('#s_itemcode').val()).isBetween($('#itemfrom').val(), $('#itemto').val(), null, [])],
				// 	table_id: 'idno',
				// 	// srcdept:$('#srcdept').val(),
				// 	// itemcodefrom:$('#itemfrom').val(),
				// 	// itemcodeto:$('#itemto').val(),
				// };

				// $.get("util/get_value_default?" + $.param(urlParam_), function (data) {
				// }, 'json').done(function (data) {
				// 	if (!$.isEmptyObject(data.rows)) {
				// 		// myerrorIt_only2($("#apacthdr_suppcode").val(),false);

				// 		data.rows.forEach(function(elem) {
				// 			$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
				// 				{	
				// 					idno:elem['idno'],
				// 					itemcode:elem['itemcode'],
				// 					uomcode:elem['uomcode'],
				// 					unitcost:elem['currprice'],
				// 					thyqty:elem['balqty'],
				// 					phyqty:0,
				// 					vrqty:parseFloat(elem['thyqty']) - parseFloat(elem['phyqty']),
				// 					batchno:elem['batchno'],
				// 					expdate:elem['expdate'],
								
				// 				}
				// 			);
				// 		});

				// 		// var ids = $("#jqGrid2").jqGrid('getDataIDs');
				// 		// for (var i = 0; i < ids.length; i++) {
				// 		// 	var rowdata = $("#jqGrid2").jqGrid('getRowData',ids[i]);
							
				// 		// 	$("#jqGrid2").jqGrid('editRow',ids[i]);
				// 		// }

				// 	} else {
				// 	}
				// });
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

				// let data=selrowData('#'+dialog_itemcodeto.gridname);
				// 	$("#jqGrid2 input[name='itemcode']").val(data['itemcode']);
				// 	$("#jqGrid2 input[name='uomcode']").val(data['uomcode']); 
				// 	$("#jqGrid2 input[name='unitcost']").val(data['currprice']);
				// 	$("#jqGrid2 input[name='thyqty']").val(data['balqty']);
				// 	$("#jqGrid2 input[name='batchno']").val(data['batchno']);
				// 	$("#jqGrid2 input[name='expdate']").val(data['expdate']);

				// var urlParam_ = {
				// 	action: 'get_value_default',
				// 	url: 'util/get_value_default',
				// 	field:['s.deptcode', 's.itemcode', 's.uomcode', 'p.description', 'p.currprice', 'e.balqty','e.expdate', 'e.batchno'],
				// 	table_name:['material.stockloc AS s', 'material.product AS p', 'material.stockexp AS e'],
				// 	join_type:['LEFT JOIN', 'LEFT JOIN'],
				// 	join_onCol:['s.itemcode', 's.itemcode'],
				// 	join_onVal:['p.itemcode', 'e.itemcode'],
				// 	filterCol:['s.compcode', 's.deptcode', 's.year'],
				// 	filterVal:['session.compcode',$('#srcdept').val(), moment($('#s_itemcode').val()).year()],
				// 	WhereInCol: ['s.itemcode'],
				// 	WhereInVal: [moment($('#s_itemcode').val()).isBetween($('#itemfrom').val(), $('#itemto').val(), null, [])],
				// 	table_id: 'idno',
				// 	srcdept:$('#srcdept').val(),
				// 	itemcodefrom:$('#itemfrom').val(),
				// 	itemcodeto:$('#itemto').val(),
				// };

				// $.get("util/get_value_default?" + $.param(urlParam_), function (data) {
				// }, 'json').done(function (data) {
				// 	if (!$.isEmptyObject(data.rows)) {
				// 		// myerrorIt_only2($("#apacthdr_suppcode").val(),false);

				// 		data.rows.forEach(function(elem) {
				// 			$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
				// 				{	
				// 					idno:elem['idno'],
				// 					itemcode:elem['itemcode'],
				// 					uomcode:elem['uomcode'],
				// 					unitcost:elem['currprice'],
				// 					thyqty:elem['balqty'],
				// 					phyqty:0,
				// 					vrqty:parseFloat(elem['thyqty']) - parseFloat(elem['phyqty']),
				// 					batchno:elem['batchno'],
				// 					expdate:elem['expdate'],
								
				// 				}
				// 			);
				// 		});

				// 		// var ids = $("#jqGrid2").jqGrid('getDataIDs');
				// 		// for (var i = 0; i < ids.length; i++) {
				// 		// 	var rowdata = $("#jqGrid2").jqGrid('getRowData',ids[i]);
							
				// 		// 	$("#jqGrid2").jqGrid('editRow',ids[i]);
				// 		// }

				// 	} else {
				// 	}
				// });
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
	$('#txndept_show').text(obj.txndept);
	$('#trantype_show').text(obj.trantype);
	$('#docno_show').text(padzero(obj.docno));
	
}

function empty_form(){
	$('#txndept_show').text('');
	$('#trantype_show').text('');
	$('#docno_show').text('');

}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+25);
}

function fail_msg_func(fail_msg_div=null){
	this.fail_msg_div = (fail_msg_div!=null)?fail_msg_div:'div#fail_msg';
	this.fail_msg_array=[];
	this.add_fail=function(fail_msg){
		let found=false;
		this.fail_msg_array.forEach(function(e,i){
			if(e.id == fail_msg.id){
				e.msg=fail_msg.msg;
				found=true;
			}
		});
		if(!found){
			this.fail_msg_array.push(fail_msg);
		}
		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.pop_fail();
	}
	this.del_fail=function(fail_msg){
		var new_msg_array = this.fail_msg_array.filter(function(e,i){
			if(e.id == fail_msg.id){
				return false;
			}
			return true;
		});

		if(fail_msg.textfld !=null){
			myerrorIt_only(fail_msg.id,true);
		}
		this.fail_msg_array = new_msg_array;
		this.pop_fail();
	}
	this.clear_fail=function(){
		this.fail_msg_array=[];
		this.pop_fail();
	}
	this.pop_fail=function(){
		var self=this;
		$(self.fail_msg_div).html('');
		this.fail_msg_array.forEach(function(e,i){
			$(self.fail_msg_div).append("<li>"+e.msg+"</li>");
		});
	}
}

function generate_stock_freeze(){
	// $("#jqGrid2").jqGrid("clearGridData", true);

	let $itemfrom = $('#itemfrom').val();
	let $itemto = $('#itemto').val();

	var urlParam_ = {
		action: 'get_dtl_itemcode',
		url: 'stockFreeze/table',
		field:['s.deptcode', 's.itemcode', 's.uomcode', 'p.description', 'p.currprice', 'e.balqty','e.expdate', 'e.batchno'],
		table_name:['material.stockloc AS s', 'material.product AS p', 'material.stockexp AS e'],		
		join_type:['LEFT JOIN', 'LEFT JOIN',],
		join_onCol:['s.itemcode', 's.itemcode'],
		join_onVal:['p.itemcode', 'e.itemcode'],
		filterCol:['s.compcode', 's.deptcode', 's.year', 's.itemcode'],
		filterVal:['session.compcode',$('#srcdept').val(), moment($('#s_itemcode').val()).year(), moment($('#s_itemcode').val()).isBetween($('#itemfrom').val(), $('#itemto').val(), null, [])],
		table_id: 'idno',
		// srcdept:$('#srcdept').val(),
		// itemcodefrom:$('#itemfrom').val(),
		// itemcodeto:$('#itemto').val(),
	};

	$.get("./stockFreeze/table?" + $.param(urlParam_), function (data) {
	}, 'json').done(function (data) {
		if (!$.isEmptyObject(data.rows)) {
			// myerrorIt_only2($("#apacthdr_suppcode").val(),false);

			data.rows.forEach(function(elem) {
				$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
					{	
						idno:elem['idno'],
						itemcode:elem['itemcode'],
						uomcode:elem['uomcode'],
						unitcost:elem['currprice'],
						thyqty:elem['balqty'],
						phyqty:0,
						vrqty:parseFloat(elem['thyqty']) - parseFloat(elem['phyqty']),
						batchno:elem['batchno'],
						expdate:elem['expdate'],
					
					}
				);
			});

			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				var rowdata = $("#jqGrid2").jqGrid('getRowData',ids[i]);
				
			// 	$("#jqGrid2").jqGrid('editRow',ids[i]);
			}

		} else {
		}
	});
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

	return parseInt(thyqty) - parseInt(phyqty);
}

