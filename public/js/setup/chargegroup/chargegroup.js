
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
				table_name: 'hisdb.chggroup',
				table_id: 'idno',
				sort_idno: true,
			}

			/////////////////////parameter for saving url////////////////////////////////////////////////
			var saveParam={
				action:'save_table_default',
				url:'chargegroup/form',
				fixPost:'true',
				field:'',
				oper:oper,
				table_name:'hisdb.chggroup',
				table_id:'idno',
				saveip:'true'
			};
			
			/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
			$("#jqGrid").jqGrid({
				datatype: "local",
				 colModel: [
					{ label: 'idno', name: 'idno', sorttype: 'number', hidden:true },
					{ label: 'Compcode', name: 'compcode', hidden:true},

					{ label: 'Group Code', name: 'grpcode', classes: 'wrap', width: 30, canSearch: true},
					{ label: 'Description', name: 'description', classes: 'wrap', width: 70, canSearch: true},
					{ label: 'Add User', name: 'adduser', classes: 'wrap', width: 30,checked:true},
                    { label: 'Add Update', name: 'adddate', classes: 'wrap', width: 20},
                    { label: 'Sequence Number', name: 'seqno', classes: 'wrap', width: 20,checked:true},
					
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
			// 	if($('#Scol').val()=='grpcode'){
			// 		$("input[name='Stext']").hide("fast");
			// 		$("#show_grpcode").hide("fast");
			// 		$("#show_grpcode").show("fast");
			// 	} else {
			// 		$("input[name='Stext']").show("fast");
			// 		$("#show_grpcode").hide("fast");
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

			/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
			// var urlParam2={
			// 	action:'get_table_default',
			// 	url:'/util/get_table_default',
			// 	field:['cp.effdate','cp.amt1','cp.amt2','cp.amt3','cp.costprice','cp.iptax','cp.lastuser','cp.lastupdate', 'cp.chgcode','cm.chgcode'],
			// 	table_name:['hisdb.chgmast AS cm', 'hisdb.chgprice AS cp'],
			// 	table_id:'lineno_',
			// 	join_type:['LEFT JOIN'],
			// 	join_onCol:['cp.chgcode'],
			// 	join_onVal:['cm.chgcode'],
			// 	filterCol:['cp.compcode','cp.chgcode'],
			// 	filterVal:['session.compcode','']
			// };

			// var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
			////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
			// $("#jqGrid2").jqGrid({
			// 	datatype: "local",
			// 	editurl: "/chargemasterDetail/form",
			// 	colModel: [
			// 	 	{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			// 	 	// { label: 'recno', name: 'recno', width: 20, frozen:true, classes: 'wrap', hidden:true},
			// 		{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			// 		{ label: 'Effective date', name: 'effdate', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'Price 1', name: 'amt1', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'Price 2', name: 'amt2', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'Price 3', name: 'amt3', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'Cost Price', name: 'costprice', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'Inpatient Tax', name: 'iptax', frozen:true, width: 150, classes: 'wrap', editable:false},
			// 		{ label: 'User ID', name: 'lastuser', frozen:true, width: 190, classes: 'wrap', editable:false},
			// 		{ label: 'Last Updated', name: 'lastupdate', frozen:true, width: 160, classes: 'wrap', editable:false},
			// 		{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},

			// 	],
			// 	scroll: false,
			// 	autowidth: false,
			// 	shrinkToFit: false,
			// 	multiSort: true,
			// 	viewrecords: true,
			// 	loadonce:false,
			// 	width: 1150,
			// 	height: 200,
			// 	rowNum: 30,
			// 	sortname: 'lineno_',
			// 	sortorder: "desc",
			// 	pager: "#jqGridPager2",
			// 	loadComplete: function(){
			// 		/*if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			// 		else{
			// 			$('#jqGrid2').jqGrid ('setSelection', "1");
			// 		}
			// 		addmore_jqgrid2.edit = addmore_jqgrid2.more = false;*/ //reset
			// 	},
			// 	gridComplete: function(){
			// 	/*	$("#jqGrid2").find(".remarks_button").on("click", function(e){
			// 			$("#remarks2").data('rowid',$(this).data('rowid'));
			// 			$("#remarks2").data('grid',$(this).data('grid'));
			// 			$("#dialog_remarks").dialog( "open" );
			// 		});
			// 	/*	fdl.set_array().reset();
			// 		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);*/
			// 	},
			// 	afterShowForm: function (rowid) {
			// 	    $("#expdate").datepicker();
			// 	},
			// 	beforeSubmit: function(postdata, rowid){ 
			// 	/*	dialog_itemcode.check(errorField);
			// 		dialog_uomcode.check(errorField);
			// 		dialog_pouom.check(errorField);*/
			//  	}
			// /*}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
		    //     fixPositionsOfFrozenDivs.call(this);*/
		    // });
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

			 //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
			// $("#jqGrid2").inlineNav('#jqGridPager2',{	
			// 	add:true,
			// 	edit:true,
			// 	cancel: true,
			// 	//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
			// 	restoreAfterSelect: false,
			// 	addParams: { 
			// 		addRowParams: myEditOptions
			// 	},
			// 	editParams: myEditOptions
			// }).jqGrid('navButtonAdd',"#jqGridPager2",{
			// 	id: "jqGridPager2Delete",
			// 	caption:"",cursor: "pointer",position: "last", 
			// 	buttonicon:"glyphicon glyphicon-trash",
			// 	title:"Delete Selected Row",
			// 	onClickButton: function(){
			// 		/*selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
			// 		if(!selRowId){
			// 			bootbox.alert('Please select row');
			// 		}else{
			// 			bootbox.confirm({
			// 			    message: "Are you sure you want to delete this row?",
			// 			    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
			// 			    },
			// 			    callback: function (result) {
			// 			    	if(result == true){
			// 			    		param={
			// 			    			action: 'inventoryTransactionDetail_save',
			// 							recno: $('#recno').val(),
			// 							lineno_: selrowData('#jqGrid2').lineno_,

			// 			    		}
			// 			    		$.post( "/inventoryTransactionDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
			// 						}).fail(function(data) {
			// 							//////////////////errorText(dialog,data.responseText);
			// 						}).done(function(data){
			// 							$('#amount').val(data);
			// 							refreshGrid("#jqGrid2",urlParam2);
			// 						});
			// 			    	}
			// 			    }
			// 			});
			// 		}*/
			// 	},
			// }).jqGrid('navButtonAdd',"#jqGridPager2",{
			// 	id: "saveHeaderLabel",
			// 	caption:"Header",cursor: "pointer",position: "last", 
			// 	buttonicon:"",
			// 	title:"Header"
			// }).jqGrid('navButtonAdd',"#jqGridPager2",{
			// 	id: "saveDetailLabel",
			// 	caption:"Detail",cursor: "pointer",position: "last", 
			// 	buttonicon:"",
			// 	title:"Detail"
			// });
			
			////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
			// $("#jqGrid3").jqGrid({
			// 	datatype: "local",
			// 	colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
			// 	shrinkToFit: false,
			// 	autowidth:true,
			// 	multiSort: true,
			// 	viewrecords: true,
			// 	rowNum: 30,
			// 	sortname: 'lineno_',
			// 	sortorder: "desc",
			// 	pager: "#jqGridPager3",
			// });
			// jqgrid_label_align_right("#jqGrid3");

			// var dialog_dept = new ordialog(
			// 	'dept','sysdb.department','#dept',errorField,
			// 	{	colModel:[
			// 			{label:'Dept Code',name:'deptcode',width:100,classes:'pointer',canSearch:true,or_search:true},
			// 			{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			// 			],
			// 		ondblClickRow:function(){
			// 		}	
			// 	},{
			// 		title:"Select Department",
			// 		open: function(){
			// 			dialog_dept.urlParam.filterCol = ['recstatus', 'compcode'];
			// 			dialog_dept.urlParam.filterVal = ['A', 'session.compcode'];
			// 		}
			// 	}, 'urlParam'
			// );
			// dialog_dept.makedialog();

			// var dialog_trantype = new ordialog(
			// 	'trantype','material.ivtxntype','#trantype',errorField,
			// 	{	colModel:[
			// 			{label:'Transaction Type',name:'trantype',width:100,classes:'pointer',canSearch:true,or_search:true},
			// 			{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			// 			],
			// 		ondblClickRow:function(){
			// 		}	
			// 	},{
			// 		title:"Select Transaction Type",
			// 		open: function(){
			// 			dialog_trantype.urlParam.filterCol = ['recstatus', 'compcode'];
			// 			dialog_trantype.urlParam.filterVal = ['A','session.compcode'];
			// 		}
			// 	}, 'urlParam'
			// );
			// dialog_trantype.makedialog();

		// var grpcode = new ordialog(
		// 	'grpcode', 'hisdb.chggroup', '#grpcode', 'errorField',
		// 	{
		// 		colModel: [
		// 			{ label: 'Group Code', name: 'grpcode', width: 200, classes: 'pointer', checked: true, canSearch: true, or_search: true },
		// 			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
		// 		],
		// 		ondblClickRow: function () {
		// 			let data = selrowData('#' + grpcode.gridname).grpcode;

		// 			urlParam.searchCol=["grpcode"];
		// 			urlParam.searchVal=[data];
		// 			// refreshGrid("#jqGrid3",null,"kosongkan");
		// 			refreshGrid('#jqGrid', urlParam);
		// 		},
		// 		gridComplete: function(obj){
		// 			var gridname = '#'+obj.gridname;
		// 			if($(gridname).jqGrid('getDataIDs').length == 1){
		// 				$(gridname+' tr#1').click();
		// 				$(gridname+' tr#1').dblclick();
		// 			}
		// 		}
		// 	},{
		// 		title: "Select Group Code",
		// 		open: function () {
		// 			// chggroup.urlParam.filterCol = ['recstatus'];
		// 			// chggroup.urlParam.filterVal = ['A'];
		// 		}
		// 	},'urlParam','radio','tab'
		// );
		// grpcode.makedialog();
		// grpcode.on();

		// var chgtype = new ordialog(
		// 	'chgtype', 'hisdb.chgtype', '#chgtype', 'errorField',
		// 	{
		// 		colModel: [
		// 			{ label: 'Charge Type', name: 'chgtype', width: 200, classes: 'pointer',checked: true, canSearch: true, or_search: true },
		// 			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true,  or_search: true },
		// 		],
		// 		ondblClickRow: function () {
		// 			let data = selrowData('#' + chgtype.gridname).chgtype;

		// 			urlParam.searchCol=["chgtype"];
		// 			urlParam.searchVal=[data];
		// 			// refreshGrid("#jqGrid3",null,"kosongkan");
		// 			refreshGrid('#jqGrid', urlParam);
		// 		},
		// 		gridComplete: function(obj){
		// 			var gridname = '#'+obj.gridname;
		// 			if($(gridname).jqGrid('getDataIDs').length == 1){
		// 				$(gridname+' tr#1').click();
		// 				$(gridname+' tr#1').dblclick();
		// 			}
		// 		}
		// 	},{
		// 		title: "Select Charge Type",
		// 		open: function () {
		// 			// chgtype.urlParam.filterCol = ['recstatus'];
		// 			// chgtype.urlParam.filterVal = ['A'];
		// 		}
		// 	},'urlParam','radio','tab'
		// );
		// chgtype.makedialog();
		// chgtype.on();

});