
	$.jgrid.defaults.responsive = true;
	$.jgrid.defaults.styleUI = 'Bootstrap';

	$(document).ready(function () {
		$("body").show();
		check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
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
				// $("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
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
						frozeOnEdit("#dialogForm");
						break;
					case state = 'view':
						disableForm('#formdata');
						$("#pg_jqGridPager2 table").hide();
						break;
				}
				if(oper!='view'){
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
					// dialog_dept.on();
					// dialog_trantype.on();
				}
				if(oper!='add'){
					///toggleFormData('#jqGrid','#formdata');
					// dialog_dept.check(errorField);
					// dialog_trantype.check(errorField);
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata');
				//$('.alert').detach();
				$('#formdata .alert').detach();
				// dialog_dept.off();
				// dialog_trantype.off();
				if(oper=='view'){
					$(this).dialog("option", "buttons",butt1);
				}
			},
			buttons :butt1,
		});
		////////////////////////////////////////end dialog///////////////////////////////////////////

		/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

		var urlParam = {
			action: 'get_table_default',
			url: '/util/get_table_default',
			field: '',
			table_name: 'hisdb.chgclass',
			table_id: 'idno',
			sort_idno: true,
			filterCol:['compcode'],
			filterVal:['session.compcode']
		}

		/////////////////////parameter for saving url////////////////////////////////////////////////
		var saveParam={
			action:'save_table_default',
			url:'chargeclass/form',
			field:'',
			oper:oper,
			table_name:'hisdb.chgclass',
			table_id:'idno',
			saveip:'true',
			checkduplicate:'true'
		};
			
		/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
		$("#jqGrid").jqGrid({
			datatype: "local",
			  colModel: [
				{ label: 'idno', name: 'idno', sorttype: 'number', hidden:true },
				{ label: 'Compcode', name: 'compcode', hidden:true},
				{ label: 'Class Code', name: 'classcode', classes: 'wrap', width: 30, canSearch: true},
				{ label: 'Description', name: 'description', classes: 'wrap', width: 70, canSearch: true},
				{ label: 'Class Level', name: 'classlevel', classes: 'wrap', width: 20,checked:true},
				{ label: 'Last User', name: 'adduser', classes: 'wrap', width: 30,checked:true},
				{ label: 'Last Update', name: 'adddate', classes: 'wrap', width: 20},
				{ label: 'Status', name:'recstatus', width:20, classes:'wrap', hidden:false,
				formatter: formatter, unformat: unformat, cellattr: function (rowid, cellvalue)
				{ return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },},
				{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
				{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
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
				//urlParam2.filterVal[1]=selrowData("#jqGrid").cm_chgcode;
				// refreshGrid("#jqGrid3",urlParam2);
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

		//////////////////////////// STATUS FORMATTER /////////////////////////////////////////////////
		
		function formatter(cellvalue, options, rowObject) {
			if (cellvalue == 'A') {
				return "Active";
			}
			if (cellvalue == 'D') {
				return "Deactive";
			}
		}

		function unformat(cellvalue, options) {
			if (cellvalue == 'Active') {
				return "A";
			}
			if (cellvalue == 'Deactive') {
				return "D";
			}
		}

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

		// $('#Scol').on('change', scolChange);

		// function scolChange() {
		// 	if($('#Scol').val()=='cm_chggroup'){
		// 		$("input[name='Stext']").hide("fast");
		// 		$("#show_chgtype,#show_chggroup").hide("fast");
		// 		$("#show_chggroup").show("fast");
		// 	} else if($('#Scol').val() == 'cm_chgtype'){
		// 		$("input[name='Stext']").hide("fast");
		// 		$("#show_chgtype,#show_chggroup").hide("fast");
		// 		$("#show_chgtype").show("fast");
		// 	} else {
		// 		$("input[name='Stext']").show("fast");
		// 		$("#show_chgtype,#show_chggroup").hide("fast");
		// 		$("input[name='Stext']").attr('type', 'text');
		// 		$("input[name='Stext']").velocity({ width: "100%" });
		// 	}
		// }

		//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
		toogleSearch('#sbut1','#searchForm','on');
		populateSelect('#jqGrid','#searchForm');
		searchClick_('#jqGrid','#searchForm',urlParam);

		function searchClick_(grid,form,urlParam){
			$(form+' [name=Stext]').on( "keyup", function() {
				delay(function(){
					if($(form+' [name=Scol] option:selected').val() == 'description'){
						search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					}else{
						search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					}
				}, 500 );
				// refreshGrid("#jqGrid3",null,"kosongkan");
			});

			$(form+' [name=Scol]').on( "change", function() {
				if($(form+' [name=Scol] option:selected').val() == 'description'){
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}else{
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				}
				// refreshGrid("#jqGrid3",null,"kosongkan");
			});
		}

		//////////add field into param, refresh grid if needed////////////////////////////////////////////////
		addParamField('#jqGrid',true,urlParam);
		addParamField('#jqGrid',false,saveParam,['idno', 'compcode', 'ipaddress', 'computerid', 'adddate', 'adduser','upduser','upddate','recstatus']);

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
				let idno = selrowData('#jqGrid').idno;
				if(!idno){
					alert('Please select row');
					return emptyFormdata(errorField,'#formdata');
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,null,{'idno':idno});
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
				// refreshGrid("#jqGrid2",urlParam2);
			},
		}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
			buttonicon:"glyphicon glyphicon-edit",
			title:"Edit Selected Row",  
			onClickButton: function(){
				oper='edit';
				selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
				// refreshGrid("#jqGrid2",urlParam2);
				recstatusDisable();
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
				// if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
				// if(addmore_jqgrid2.edit == false)linenotoedit = null; 
				//linenotoedit = null;

				// refreshGrid('#jqGrid2',urlParam2,'add');
				// $("#jqGridPager2Delete").show();
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
				// $("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
			},
			afterrestorefunc : function( response ) {
				/*hideatdialogForm(false);*/
			}
		};
	});