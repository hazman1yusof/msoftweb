
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';

	$(document).ready(function () {
		$("body").show();
		check_compid_exist("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
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
			
		////////////////////////////////////start dialog///////////////////////////////////////
		var butt1=[{
			text: "Save",click: function() {
				if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
					saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
				}
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

		var butt2=[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];

		var oper='add';
		$("#dialogForm")
			.dialog({ 
				width: 9/10 * $(window).width(),
				modal: true,
				autoOpen: false,
				open: function( event, ui ) {
					parent_close_disabled(true);
					$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
					/*mycurrency.formatOnBlur();
					mycurrency.formatOn();*/
					switch(oper) {
						case state = 'add':
							$("#jqGrid2").jqGrid("clearGridData", false);
							$("#pg_jqGridPager2 table").show();
							/*hideatdialogForm(true);*/
							enableForm('#formdata');
							rdonly('#formdata');
							break;
						case state = 'edit':
							$("#pg_jqGridPager2 table").show();
							/*hideatdialogForm(true);*/
							enableForm('#formdata');
							rdonly('#formdata');
							frozeOnEdit("#formdata");
							recstatusDisable("cm_recstatus");
							break;
						case state = 'view':
							disableForm('#formdata');
							$("#pg_jqGridPager2 table").hide();
							break;
					}
					if(oper!='view'){
						set_compid_from_storage("input[name='cm_lastcomputerid']", "input[name='cm_lastipaddress']", "input[name='cm_computerid']", "input[name='cm_ipaddress']");
						dialog_chggroup.on();
						dialog_chgclass.on();
						dialog_chgtype.on();
						dialog_doctorcode.on();
					}
					if(oper!='add'){
						///toggleFormData('#jqGrid','#formdata');
						dialog_chggroup.check(errorField);
						dialog_chgclass.check(errorField);
						dialog_chgtype.check(errorField);
						dialog_doctorcode.check(errorField);
					}
				},
				close: function( event, ui ) {
					parent_close_disabled(false);
					emptyFormdata(errorField,'#formdata');
					//$('.alert').detach();
					$('#formdata .alert').detach();
					dialog_chggroup.off();
					dialog_chgclass.off();
					dialog_chgtype.off();
					dialog_doctorcode.off();
					if(oper=='view'){
						$(this).dialog("option", "buttons",butt1);
					}
				},
				buttons :butt1,
			});
		////////////////////////////////////////end dialog///////////////////////////////////////////

		/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

		var urlParam={
			action:'get_table_default',
			url:'/util/get_table_default',
			field:'',
			fixPost:'true',
			table_name:['hisdb.chgmast AS CM', 'hisdb.chgclass AS CC', 'hisdb.chggroup AS CG', 'hisdb.chgtype AS CT'],
			table_id:'cm_chgcode',
			join_type:['LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN'],
			join_onCol:['cm.chgclass', 'cm.chggroup', 'cm.chgtype'],
			join_onVal:['cc.classcode', 'cg.grpcode', 'ct.chgtype'],
			filterCol:['cm.compcode'],
			filterVal:['session.compcode']
		}

		/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'save_table_default',
			url:'chargemaster/form',
			fixPost:'true',
			field:'',
			oper:oper,
			table_name:'hisdb.chgmast',
			table_id:'chgcode',
			saveip:'true'
		};
			
		/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
		$("#jqGrid").jqGrid({
			datatype: "local",
				colModel: [
					{ label: 'idno', name: 'cm_idno', sorttype: 'number', hidden:true },
					{ label: 'Compcode', name: 'cm_compcode', hidden:true},
					{ label: 'Charge Code', name: 'cm_chgcode', classes: 'wrap', width: 30, canSearch: true},
					{ label: 'Description', name: 'cm_description', classes: 'wrap', width: 60, canSearch: true},
					{ label: 'Class', name: 'cm_chgclass', classes: 'wrap', width: 20,checked:true},
					{ label: 'Class Name', name: 'cc_description', classes: 'wrap', width: 30,checked:true},
					{ label: 'Group', name: 'cm_chggroup', classes: 'wrap', width: 20, canSearch: true},
					{ label: 'Description', name: 'cg_description', classes: 'wrap', width: 40},
					{ label: 'Charge Type', name: 'cm_chgtype', classes: 'wrap', width: 30, canSearch: true},
					{ label: 'Description', name: 'ct_description', classes: 'wrap', width: 30},
					{ label: 'UOM', name: 'cm_uom', width: 30,hidden:false },
					{ label: 'Generic Name', name: 'cm_brandname', width: 60},
					
					{ label: 'cm_barcode', name: 'cm_barcode', hidden:true},
					{ label: 'cm_constype', name: 'cm_constype', hidden:true},
					{ label: 'cm_invflag', name: 'cm_invflag', hidden:true},
					{ label: 'cm_packqty', name: 'cm_packqty', hidden:true},
					{ label: 'cm_druggrcode', name: 'cm_druggrcode', hidden:true},
					{ label: 'cm_subgroup', name: 'cm_subgroup', hidden:true},
					{ label: 'cm_invgroup', name: 'cm_invgroup', hidden:true},
					{ label: 'doctorcode', name: 'doctorcode', hidden:true},
					{ label: 'deptcode', name: 'deptcode', hidden:true},
					{ label: 'cm_seqno', name: 'cm_seqno', hidden:true},
					{ label: 'cm_overwrite', name: 'cm_overwrite', hidden:true},
					{ label: 'cm_doctorstat', name: 'cm_doctorstat', hidden:true},

					{ label: 'Upd User', name: 'cm_upduser', width: 80,hidden:true}, 
					{ label: 'Upd Date', name: 'cm_upddate', width: 90,hidden:true},
					{ label: 'Status', name:'cm_recstatus', width:30, classes:'wrap', hidden:false,
					formatter: formatterstatus, unformat: unformatstatus, cellattr: function (rowid, cellvalue)
					{ return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },},
					
					{ label: 'computerid', name: 'cm_computerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'ipaddress', name: 'cm_ipaddress', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastcomputerid', name: 'cm_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
					{ label: 'lastipaddress', name: 'cm_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
				],
				autowidth:true,
				multiSort: true,
				viewrecords: true,
				loadonce:false,
				width: 900,
				height: 250,
				rowNum: 30,
				pager: "#jqGridPager",
				onSelectRow:function(rowid, selected){
					urlParam2.filterVal[1]=selrowData("#jqGrid").cm_chgcode;
					refreshGrid("#jqGrid3",urlParam2);
				},
				ondblClickRow: function(rowid, iRow, iCol, e){
					$("#jqGridPager td[title='Edit Selected Row']").click();
				},
				gridComplete: function(){
					if(oper == 'add'){
						$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
					}

					// $('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
				},
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
			});
		}

		$('#Scol').on('change', scolChange);

		function scolChange() {
			if($('#Scol').val()=='cm_chggroup'){
				$("#div_chgtype").hide();
				$("#div_chggroup").show();
			} else if($('#Scol').val() == 'cm_chgtype'){
				$("#div_chggroup").hide();
				$("#div_chgtype").show();
			} else {
				$("#div_chgtype,#div_chggroup").hide();
			}
		}

		//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		toogleSearch('#sbut1','#searchForm','on');
		populateSelect('#jqGrid','#searchForm');
		searchClick_('#jqGrid','#searchForm',urlParam);

		function searchClick_(grid,form,urlParam){
			$(form+' [name=Stext]').on( "keyup", function() {
				delay(function(){
					if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
						search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
					}else{
						search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					}
				}, 500 );
				refreshGrid("#jqGrid3",null,"kosongkan");
			});

			$(form+' [name=Scol]').on( "change", function() {
				if($(form+' [name=Scol] option:selected').val() == 'cm_description'){
					search2(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam,'cm_brandname');
				}else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}
				refreshGrid("#jqGrid3",null,"kosongkan");
			});
		}

		$('#searchForm [name=Stext]').on( "keyup", function() {
			$("#chgtype,#chggroup").val($(this).val());
		});

		//////////add field into param, refresh grid if needed////////////////////////////////////////////////
		addParamField('#jqGrid',true,urlParam);
		addParamField('#jqGrid',false,saveParam,['cm_idno', 'cm_compcode', 'cm_ipaddress', 'cm_computerid', 'cm_adddate', 'cm_adduser','cm_upduser','cm_upddate','cm_recstatus']);

		/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
		var urlParam2={
			action:'get_table_default',
			url:'/util/get_table_default',
			field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.lastuser','cp.lastupdate', 'cp.chgcode','cm.chgcode'],
			table_name:['hisdb.chgmast AS cm', 'hisdb.chgprice AS cp'],
			table_id:'lineno_',
			join_type:['LEFT JOIN'],
			join_onCol:['cp.chgcode'],
			join_onVal:['cm.chgcode'],
			filterCol:['cp.compcode','cp.chgcode'],
			filterVal:['session.compcode','']
		};

		var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
		////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
		$("#jqGrid2").jqGrid({
			datatype: "local",
			editurl: "/chargemasterDetail/form",
			colModel: [
				{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
				// { label: 'recno', name: 'recno', width: 20, frozen:true, classes: 'wrap', hidden:true},
				{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
				{ label: 'Effective date', name: 'effdate', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'Price 1', name: 'amt1', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'Price 2', name: 'amt2', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'Price 3', name: 'amt3', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'Cost Price', name: 'costprice', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'Inpatient Tax', name: 'iptax', frozen:true, width: 150, classes: 'wrap', editable:false},
				{ label: 'User ID', name: 'lastuser', frozen:true, width: 190, classes: 'wrap', editable:false},
				{ label: 'Last Updated', name: 'lastupdate', frozen:true, width: 160, classes: 'wrap', editable:false},
				{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},

			],
			scroll: false,
			autowidth: false,
			shrinkToFit: false,
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
				/*if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
				else{
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}
				addmore_jqgrid2.edit = addmore_jqgrid2.more = false;*/ //reset
			},
			gridComplete: function(){
			/*	$("#jqGrid2").find(".remarks_button").on("click", function(e){
					$("#remarks2").data('rowid',$(this).data('rowid'));
					$("#remarks2").data('grid',$(this).data('grid'));
					$("#dialog_remarks").dialog( "open" );
				});
			/*	fdl.set_array().reset();
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);*/
			},
			afterShowForm: function (rowid) {
				// $("#expdate").datepicker();
			},
			beforeSubmit: function(postdata, rowid){ 
			/*	dialog_itemcode.check(errorField);
				dialog_uomcode.check(errorField);
				dialog_pouom.check(errorField);*/
			}
			/*}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
		        fixPositionsOfFrozenDivs.call(this);*/
		});
		/*fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);*/

		/*$("#jqGrid2").jqGrid('bindKeys');
		var updwnkey_fld;
		function updwnkey_func(event){
			var optid = event.currentTarget.id;
			var fieldname = optid.substring(optid.search("_"));
			updwnkey_fld = fieldname;
		}

		$("#jqGrid2").keydown(function(e) {
			switch (e.which) {
			case 40: // down
				var $grid = $(this);
				var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
				$("#"+selectedRowId+updwnkey_fld).focus();

				e.preventDefault();
				break;

			case 38: // up
				var $grid = $(this);
				var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
				$("#"+selectedRowId+updwnkey_fld).focus();

				e.preventDefault();
				break;

			default:
				return;
			}
		});

		$("#jqGrid2").jqGrid('setGroupHeaders', {
		useColSpanStyle: false, 
			groupHeaders:[
			{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
			{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
			]
		})
		*/

		/////////////////////////start grid pager/////////////////////////////////////////////////////////

		$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
			view:false,edit:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam,oper);
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				oper='del';
				let cm_idno = selrowData('#jqGrid').cm_idno;
				if(!cm_idno){
					alert('Please select row');
					return emptyFormdata(errorField,'#formdata');
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'idno':cm_idno});
				}
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first", 
			buttonicon:"glyphicon glyphicon-info-sign",
			title:"View Selected Row",  
			onClickButton: function(){
				oper='view';
				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
				refreshGrid("#jqGrid2",urlParam2);
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
			buttonicon:"glyphicon glyphicon-edit",
			title:"Edit Selected Row",  
			onClickButton: function(){
				oper='edit';
				selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
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

		//////////////////////////////////////end grid/////////////////////////////////////////////////////////
		//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	
		var myEditOptions = {
			keys: true,
			extraparam:{
				"_token": $("#_token").val()
			},
			oneditfunc: function (rowid) {
				//console.log(rowid);
				/*linenotoedit = rowid;
				$("#jqGrid2").find(".rem_but[data-lineno_!='"+linenotoedit+"']").prop("disabled", true);
				$("#jqGrid2").find(".rem_but[data-lineno_='undefined']").prop("disabled", false);*/
			},
			aftersavefunc: function (rowid, response, options) {
				$('#amount').val(response.responseText);
				if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
				if(addmore_jqgrid2.edit == false)linenotoedit = null; 
				//linenotoedit = null;

				refreshGrid('#jqGrid2',urlParam2,'add');
				$("#jqGridPager2Delete").show();
			}, 
			beforeSaveRow: function(options, rowid) {
				/*if(errorField.length>0)return false;

				let data = selrowData('#jqGrid2');
				let editurl = "/inventoryTransactionDetail/form?"+
					$.param({
						action: 'invTranDetail_save',
						docno:$('#docno').val(),
						recno:$('#recno').val(),
					});*/
				$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
			},
			afterrestorefunc : function( response ) {
				/*hideatdialogForm(false);*/
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
				/*selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
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
									action: 'inventoryTransactionDetail_save',
									recno: $('#recno').val(),
									lineno_: selrowData('#jqGrid2').lineno_,

								}
								$.post( "/inventoryTransactionDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									$('#amount').val(data);
									refreshGrid("#jqGrid2",urlParam2);
								});
							}
						}
					});
				}*/
			},
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveHeaderLabel",
			caption:"Header",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Header"
		}).jqGrid('navButtonAdd',"#jqGridPager2",{
			id: "saveDetailLabel",
			caption:"Detail",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Detail"
		});
			
		////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
		$("#jqGrid3").jqGrid({
			datatype: "local",
			colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
			shrinkToFit: false,
			autowidth:true,
			multiSort: true,
			viewrecords: true,
			rowNum: 30,
			sortname: 'lineno_',
			sortorder: "desc",
			pager: "#jqGridPager3",
		}).inlineNav('#jqGridPager3',{	
			add:true,
			edit:true,
			cancel: true,
			//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			restoreAfterSelect: false,
			addParams: { 
				addRowParams: myEditOptions
			},
			editParams: myEditOptions
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "jqGridPager3Delete",
			caption:"",cursor: "pointer",position: "last", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				/*selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
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
									action: 'inventoryTransactionDetail_save',
									recno: $('#recno').val(),
									lineno_: selrowData('#jqGrid2').lineno_,

								}
								$.post( "/inventoryTransactionDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									$('#amount').val(data);
									refreshGrid("#jqGrid2",urlParam2);
								});
							}
						}
					});
				}*/
			},
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "saveHeaderLabel",
			caption:"Header",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Header"
		}).jqGrid('navButtonAdd',"#jqGridPager3",{
			id: "saveDetailLabel",
			caption:"Detail",cursor: "pointer",position: "last", 
			buttonicon:"",
			title:"Detail"
		});
		jqgrid_label_align_right("#jqGrid3");

		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#btn_chggroup').on( "click", function() {
			$('#chggroup ~ a').click();
		});
		var chggroup = new ordialog(
			'chggroup', 'hisdb.chggroup', '#chggroup', 'errorField',
			{
				colModel: [
					{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
					{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				],
				ondblClickRow: function () {
					let data = selrowData('#' + chggroup.gridname).grpcode;
					$("#searchForm input[name='Stext']").val($('#chggroup').val());

					urlParam.searchCol=["cm_chggroup"];
					urlParam.searchVal=[data];
					refreshGrid("#jqGrid3",null,"kosongkan");
					refreshGrid('#jqGrid', urlParam);
				}
			},{
				title: "Select Group Code",
				open: function () {
					// chggroup.urlParam.filterCol = ['recstatus'];
					// chggroup.urlParam.filterVal = ['A'];
				}
			},'urlParam','radio','tab'
		);
		chggroup.makedialog();
		chggroup.on();

		$('#btn_chgtype').on( "click", function() {
			$('#chgtype ~ a').click();
		});
		var chgtype = new ordialog(
			'chgtype', 'hisdb.chgtype', '#chgtype', 'errorField',
			{
				colModel: [
					{ label: 'Charge Type', name: 'chgtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
					{ label: 'Description', name: 'description', width: 400, classes: 'pointer', checked: true, canSearch: true,  or_search: true },
				],
				ondblClickRow: function () {
					let data = selrowData('#' + chgtype.gridname).chgtype;
					$("#searchForm input[name='Stext']").val($('#chgtype').val());

					urlParam.searchCol=["cm_chgtype"];
					urlParam.searchVal=[data];
					refreshGrid("#jqGrid3",null,"kosongkan");
					refreshGrid('#jqGrid', urlParam);
				}
			},{
				title: "Select Charge Type",
				open: function () {
					// chgtype.urlParam.filterCol = ['recstatus'];
					// chgtype.urlParam.filterVal = ['A'];
				}
			},'urlParam','radio','tab'
		);
		chgtype.makedialog();
		chgtype.on();

		var dialog_chgclass= new ordialog(
			'cm_chgclass','hisdb.chgclass','#cm_chgclass',errorField,
			{	colModel:[
					{label:'Class Code',name:'classcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				ondblClickRow: function () {
					$('#cm_constype').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_constype').focus();
					}
				}
			},
			{
				title:"Select Class Code",
				open: function(){
					dialog_chgclass.urlParam.filterCol=['compcode'];
					dialog_chgclass.urlParam.filterVal=['session.compcode'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chgclass.makedialog(true);

		var dialog_chggroup= new ordialog(
			'cm_chggroup','hisdb.chggroup','#cm_chggroup',errorField,
			{	colModel:[
					{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
			]	,
				ondblClickRow: function () {
					$('#cm_chgtype').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#cm_chgtype').focus();
					}
				}
			},
			{
				title:"Select Group Code",
				open: function(){
					dialog_chggroup.urlParam.filterCol=['compcode'];
					dialog_chggroup.urlParam.filterVal=['session.compcode'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chggroup.makedialog(true);

		var dialog_chgtype= new ordialog(
			'cm_chgtype','hisdb.chgtype','#cm_chgtype',errorField,
			{	colModel:[
					{label:'Charge Type',name:'chgtype',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				ondblClickRow: function () {
					// $('#ipdept').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						// $('#ipdept').focus();
					}
				}
			},
			{
				title:"Select Charge Type",
				open: function(){
					dialog_chgtype.urlParam.filterCol=['compcode'];
					dialog_chgtype.urlParam.filterVal=['session.compcode'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_chgtype.makedialog(true);

		var dialog_doctorcode= new ordialog(
			'doctorcode','hisdb.doctor','#doctorcode',errorField,
			{	colModel:[
					{label:'Doctor Code',name:'doctorcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Doctor Name',name:'doctorname',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				ondblClickRow: function () {
					$('#deptcode').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#deptcode').focus();
					}
				}
			},
			{
				title:"Select Doctor Code",
				open: function(){
					dialog_doctorcode.urlParam.filterCol=['compcode'];
					dialog_doctorcode.urlParam.filterVal=['session.compcode'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_doctorcode.makedialog(true);

		var dialog_deptcode= new ordialog(
			'deptcode','sysdb.department','#deptcode',errorField,
			{	colModel:[
					{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
					{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				],
				ondblClickRow: function () {
					// $('#ipdept').focus();
				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						// $('#ipdept').focus();
					}
				}
			},
			{
				title:"Select Department Code",
				open: function(){
					dialog_deptcode.urlParam.filterCol=['compcode','chgdept'];
					dialog_deptcode.urlParam.filterVal=['session.compcode','1'];
					
				}
			},'urlParam','radio','tab'
		);
		dialog_deptcode.makedialog(true);

		//////////////////////////////////////////////////////////////////////////////////////////////////////

		$("#jqGrid3_panel").on("show.bs.collapse", function(){
			$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		});

	});