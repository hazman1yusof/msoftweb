$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	////////////////////////////////////////validation////////////////////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(["#grandtot","#jqGrid input[name='avgcost']","#jqGrid input[name='amount']"]);
	var mycurrency2 =new currencymode([]);
	var fdl = new faster_detail_load();

    ///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#jqGrid input[name='trandate']"]);
	actdateObj.getdata().set();
	
	//////////////////////////////////////////padzero//////////////////////////////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}
	
	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}
	
	////////////////////////////////////////searchClick2////////////////////////////////////////
	// function searchClick2(grid,form,urlParam){
	// 	$(form+' [name=Stext]').on( "keyup", function() {
	// 		delay(function(){
	// 			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 			// $('#reqnodepan').text("");  // tukar kat depan tu
	// 			// $('#reqdeptdepan').text("");
	// 			// refreshGrid("#jqGrid3",null,"kosongkan");
	// 		}, 500 );
	// 	});
		
	// 	$(form+' [name=Scol]').on( "change", function() {
	// 		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	// 		// $('#reqnodepan').text("");  // tukar kat depan tu
	// 		// $('#reqdeptdepan').text("");
	// 		// refreshGrid("#jqGrid3",null,"kosongkan");
	// 	});
	// }
	
	////////////////////////////////////utk dropdown search By////////////////////////////////////
	// searchBy();
	// function searchBy() {
	// 	$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
	// 		if (value['canSearch']) {
	// 			if (value['selected']) {
	// 				$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			} else {
	// 				$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
	// 			}
	// 		}
	// 	});
	// 	searchClick2('#jqGrid', '#searchForm', urlParam);
	// }
	
	////////////////////////////////////////////jqGrid////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		url: './util/get_table_default',
		field: '',
		table_name: 'material.repackhd',
		table_id: 'idno',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
		sort_idno: true,
	}
	
	////////////////////////////////////parameter for saving url////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'recno', name: 'recno', width: 10, hidden: true, key: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
            { label: 'Date', name: 'trandate', width: 40, classes: 'wrap', editable:true,
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
            {label: 'Department', name: 'deptcode', width: 80, hidden: false, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:deptcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},	
            {label: 'Itemcode', name: 'newitemcode', width: 100, hidden: false, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
            // { label: 'UOM', name: 'uomcode', width: 40, align: 'right', classes: 'wrap', editable:true,
			// 	editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
			// 		edittype:'custom',	
			// 		editoptions:{  
			// 			custom_element:uomcodeCustomEdit,
			// 			custom_value:galGridCustomValue,
			// 			readonly: "readonly"	
			// 		},

            // },
			{ label: 'UOM', name: 'uomcode', width: 40, formatter: showdetail, editable:true,editoptions: {
				dataInit: function (element) {
					$(element).attr('disabled','true');
					$(element).val($('#uomcode').val());
				}
			}},
			{ label: 'Quantity', name: 'outqty', width: 60, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true,custom:true, custom_func:cust_rules},edittype:"text",
						editoptions:{
						maxlength: 12,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
									}
							});
						}
					},
			},
        	{ label: 'Average Cost', name: 'avgcost', width: 60, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
            { label: 'Total Amount', name: 'amount', width: 60, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},
            { label: 'Status', name: 'recstatus', width: 60, align: 'left', classes: 'wrap', editable:true,
				edittype:"text",
				editoptions:{
					maxlength: 100,readonly: "readonly"
				},
			},	
			{ label: 'adduser', name: 'adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 10, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			// if(!err_reroll.error)$('#p_error').text('');	// hilangkan error msj after save
			
			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			refreshGrid("#jqGrid2",urlParam2);
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
			
			addmore_jqgrid.edit = addmore_jqgrid.more = false;	// reset
			// if(err_reroll.error == true){
			// 	err_reroll.reroll();
			// }
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
			// $('#p_error').text('');   // hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			fdl.set_array().reset();
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
		},
	});

    ////////////////////////////////////////////set label jqGrid right////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");
	
	////////////////////////////////////////myEditOptions_hdr////////////////////////////////////////
	var myEditOptions_hdr = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			$('#jqGrid').data('lastselrow','none');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

            dialog_deptcode.on();
			dialog_newitemcode.on();

			$("input[name='outqty']").keydown(function(e) {	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
			
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	// only addmore after save inline
			addmore_jqgrid.more = true;	// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			// $('#p_error').text(response.responseText);
			// err_reroll.old_data = data.request;
			// err_reroll.error = true;
			// err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			// $('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			//check_cust_rules();
			
			let editurl = "./repack/form?"+
				$.param({
					action: 'repack_save',
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
	
	//////////////////////////////////////myEditOptions_hdr_edit//////////////////////////////////////
	var myEditOptions_hdr_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid, data) {
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			$("#jqGrid input[name='uomcode']").val(data.uomcode);
            dialog_deptcode.on();
			dialog_newitemcode.on();

			$("input[name='outqty']").keydown(function(e) {	// when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				// addmore_jqgrid.state = true;
				// $('#jqGrid_ilsave').click();
			});
			
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;	// only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			//$('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			//$('#p_error').text('');
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			//check_cust_rules();
			
			let editurl = "./repack/form?"+
				$.param({
					action: 'repack_save',
					idno: selrowData('#jqGrid').idno,
					uomcode:data.uomcode,
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
	
	/////////////////////////////////////////start grid pager/////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_hdr
		},
		editParams: myEditOptions_hdr_edit
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
								action: 'repack_save',
								recno: selrowData('#jqGrid').recno,
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./repack/form?"+$.param(param),{oper:'del'}, function( data ){
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
	
	////////////////////////////handle searching, its radio button and toggle////////////////////////////
	// populateSelect('#jqGrid', '#searchForm');
	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);
	
	/////////////////////////////add field into param, refresh grid if needed/////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	
	/////////////////////////////////////////function err_reroll/////////////////////////////////////////
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
	
	//////////////////////////////////////////hide at dialogForm//////////////////////////////////////////
	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2Refresh").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll,#jqGridPager2Refresh").hide();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2Refresh").show();
			$("#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
		}
	}
	
	//////////////////////////////////////////parameter for jqgrid2 url//////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_default',
		url:'util/get_table_default',
		table_name:['material.repackdt'],
		table_id:'idno',
		filterCol:['recno','compcode'],
		filterVal:['', 'session.compcode']
	};
	
	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	
	////////////////////////////////////////////////////jqgrid2////////////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./repackDetail/form",
		colModel: [
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'recno', name: 'recno', hidden: true },
			{ label: 'No', name: 'lineno_', width: 30, classes: 'wrap', editable: true, hidden:true},
            { label: 'Department', name: 'deptcode', width: 80, hidden: false, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:deptcodedtlCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},	
            { label: 'Itemcode', name: 'olditemcode', width: 100, hidden: false, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:olditemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'UOM', name: 'uomcode', width: 40, formatter: showdetail, editable:true,editoptions: {
				dataInit: function (element) {
					$(element).attr('disabled','true');
					$(element).val($('#uomcode').val());
				}
			}},		
			{ label: 'Quantity', name: 'inpqty', width: 60, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true,custom:true, custom_func:cust_rules},edittype:"text",
						editoptions:{
						maxlength: 12,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
									}
							});
						}
					},
			},
        	{ label: 'Average Cost', name: 'avgcost', width: 60, align: 'right', classes: 'wrap', editable:true,
				edittype:"text",formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editoptions:{
					maxlength: 100,readonly: "readonly",
					dataInit: function(element) {
						element.style.textAlign = 'right';
						$(element).keypress(function(e){
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								return false;
								}
						});
					}
				},
			},
            { label: 'Total Amount', name: 'amount', width: 60, align: 'right', classes: 'wrap', editable:true,
				edittype:"text", formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editoptions:{
					maxlength: 100,readonly: "readonly",
					dataInit: function(element) {
						element.style.textAlign = 'right';
						$(element).keypress(function(e){
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								return false;
								}
						});
					}
				},
			},
			{ label: 'adduser', name: 'adduser', hidden: true },
			{ label: 'adddate', name: 'adddate', hidden: true },
			{ label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'lineno_',
		sortorder: "asc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			
			setjqgridHeight(data,'jqGrid2');
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false;    // reset
			// calc_jq_height_onchange("jqGrid2");
			calc_grandtot();
		},
		gridComplete: function (){
			fdl.set_array().reset();
			if(!hide_init){
				hide_init=1;
				hideatdialogForm(false);
			}
		},
		beforeSubmit: function (postdata, rowid){
			// dialog_paymode.check(errorField);
		}
	});
	var hide_init=0;
	
	////////////////////////////////////////////set label jqGrid2 right////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");
	
	/////////////////////////////////////////////////myEditOptions/////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			mycurrency2.formatOn();

			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").hide();
			
			dialog_deptcodedtl.on();
			dialog_olditemcode.on();
			
			unsaved = false;

			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']","#jqGrid2 input[name='avgcost']"]);
			$("#jqGrid2 input[name='inpqty']","#jqGrid2 input[name='avgcost']","#jqGrid2 input[name='amount']").on('keyup',{currency: mycurrency2},calculate_totamount);

			$("input[name='inpqty']").keydown(function(e) {	// when click tab at revsign, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			// $('#db_amount').val(response.responseText);
			// if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true;	// only addmore after save inline
			addmore_jqgrid2.more = true;	// state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
			refreshGrid("#jqGrid",urlParam);
			$("#jqGridPager2EditAll,#jqGridPager2Delete,#jqGridPager2Refresh").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./repackDetail/form?"+
				$.param({
					action: 'repack_detail_save',
					recno: selrowData('#jqGrid').recno,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm(false);
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	
	/////////////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
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
								action: 'repack_detail_save',
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./repackDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()},
							function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
								refreshGrid("#jqGrid2", urlParam2);
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
			errorField.length=0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				$("#jqGrid2").jqGrid('editRow',ids[i]);

				dialog_deptcodedtl.id_optid = ids[i];
		        dialog_deptcodedtl.check(errorField,ids[i]+"_deptcode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				    }
			    );

		        dialog_olditemcode.id_optid = ids[i];
		        dialog_olditemcode.check(errorField,ids[i]+"_olditemcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );
			}
			$("#jqGrid2 input[name='inpqty']","#jqGrid2 input[name='avgcost']","#jqGrid2 input[name='amount']").on('blur',{currency: mycurrency2},calculate_totamount);			
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
			mycurrency2.array.length = 0;

			for (var i = 0; i < ids.length; i++) {
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				// console.log(retval);
				
				var obj = 
				{
					// 'lineno_' : ids[i],
					'idno' : data.idno,
					'lineno_' : $("#jqGrid2 input#"+ids[i]+"_lineno_").val(),
					'deptcode' : $("#jqGrid2 select#"+ids[i]+"_deptcode").val(),
					'olditemcode' : $("#jqGrid2 select#"+ids[i]+"_olditemcode").val(),
					'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
					'inpqty' : $("#jqGrid2 input#"+ids[i]+"_inpqty").val(),
				
				}
				
				jqgrid2_data.push(obj);
			}
			
			var param = {
				action: 'repack_detail_save',
				_token: $("#_token").val(),
				idno: selrowData('#jqGrid2').idno,
				recno: $('#recno').val(),
			}
			
			$.post( "/repackDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				// alert(dialog,data.responseText);
			}).done(function(data){
				mycurrency.formatOn();
				hideatdialogForm(false);
				urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
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
			urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			urlParam2.filterVal[0] = selrowData('#jqGrid').recno;
			refreshGrid("#jqGrid2",urlParam2);
		},
	});
	
	/////////////////////////calculate amount////////////////////
	function calculate_totamount(event){
		// event.data.currency.formatOff();
		// var mycurrency2 =new currencymode([]);
		// mycurrency2.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
	
		let inpqty = parseFloat($("#jqGrid2 #"+id_optid+"_inpqty").val());
		let avgcost = parseFloat($("#jqGrid2 #"+id_optid+"_avgcost").val());
		let totamount =  inpqty * avgcost;
	
		$("#jqGrid2 #"+id_optid+"_amount").val(totamount);
	
		$("#jqGrid2 #"+id_optid+"_inpqty").val(numeral($("#jqGrid2 #"+id_optid+"_inpqty").val()).format('0,0'));
		// event.data.currency.formatOn();
	}

	function calc_grandtot(){
		var ids = $("#jqGrid2").jqGrid('getDataIDs');

		var jqGrid2_data = [];
		for (var i = 0; i < ids.length; i++) {

			var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

			var obj = 
			{
				'amount' : data.amount,
			}

			jqGrid2_data.push(obj);
		}

		var grdtot=0;
		for (var i = 0; i < jqGrid2_data.length; i++) {
			grdtot=parseFloat(grdtot)+parseFloat(jqGrid2_data[i].amount);
		}
		
		$("#grandtot").val(grdtot);
		mycurrency.formatOn();

		// var chgprice_amt1 = selrowData("#jqGridPkg3").amt1;

		// $('span.error_pkgmast').html('');
		// if(parseFloat(chgprice_amt1) != parseFloat(grdprice1)){
		// 	$('span.error_pkgmast').html('Total Package Price is not equal with Charge Price Amount');
		// }

	}

	//////////////////////////////////////////////formatter checkdetail//////////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
            case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'newitemcode':field=['itemcode','description'];table="material.productmaster";case_='newitemcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'deptcodedtl':field=['code','description'];table="finance.glconsol";case_='deptcodedtl';break;
			case 'olditemcode':field=['itemcode','description'];table="material.productmaster";case_='olditemcode';break;

		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('repack',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}
	
	/////////////////////////////////////////////////////cust_rules/////////////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			// jqGrid
			case 'Department':temp=$('#deptcode');break;
			case 'Itemcode':temp=$('#newitemcode');break;
			case 'UOM':temp=$('#uomcode');break;
			case 'Department':temp=$("#jqGrid2 input[name='deptcode']");break;
			case 'Itemcode':temp=$("#jqGrid2 input[name='olditemcode']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	////////////////////////////////////////////////////custom input////////////////////////////////////////////////////
	function deptcodeCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

    function itemcodeCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="newitemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

    function deptcodedtlCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

    function olditemcodeCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="olditemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	////////////////////////////////////////////////////////edit all////////////////////////////////////////////////////////
	function onall_editfunc(){
		errorField.length=0;
		dialog_deptcode.on();
		dialog_newitemcode.on();
		dialog_deptcodedtl.on();
		dialog_olditemcode.on();

		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		$("#jqGrid2 input[name='inpqty']","#jqGrid2 input[name='avgcost']","#jqGrid2 input[name='amount']").on('blur',{currency: mycurrency2},calculate_totamount);
	}
	
	////////////////////////////////////////////////////////ordialog////////////////////////////////////////////////////////
	var dialog_deptcode = new ordialog(
		'deptcode',['material.stockloc AS s', 'sysdb.department AS d'],"#jqGrid input[name='deptcode']",'errorField',
		{
			colModel:[
				{label:'Department',name:'s_deptcode',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Description', name: 'd_description', width: 400, classes: 'pointer', canSearch: true, or_search: true},
				// {label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','year'],
				filterVal:['ACTIVE','session.compcode',moment($('#trandate').val()).year()],
			},
			ondblClickRow: function () {
				$("#jqGrid input[name='newitemcode']").focus();
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
				// dialog_deptcode.urlParam.table_name = ['material.stockloc AS s', 'sysdb.department AS d'];
				dialog_deptcode.urlParam.fixPost = "true";
				dialog_deptcode.urlParam.table_id="none_";
				dialog_deptcode.urlParam.url = "./repack/table";
				dialog_deptcode.urlParam.action = "get_deptcode";
				dialog_deptcode.urlParam.filterCol=['recstatus','compcode','year'];
				dialog_deptcode.urlParam.filterVal=['ACTIVE','session.compcode',moment($('#trandate').val()).year()];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(true);
	
	var dialog_newitemcode = new ordialog(
		'newitemcode',['material.stockloc AS s', 'material.product AS p'],"#jqGrid input[name='newitemcode']",'errorField',
		{
			colModel:[
				{label:'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Description', name: 'p_description', width: 400, classes: 'pointer', canSearch: true, or_search: true},
				{label:'uom',name:'s_uomcode', hidden:true},
			],
			urlParam: {
				filterCol:['deptcode','recstatus','compcode','year'],
				filterVal:[$("#jqGrid input[name='deptcode']").val(),'ACTIVE','session.compcode',moment($('#trandate').val()).year()],
			},
			ondblClickRow: function (event) {
				// $("#itemto").val('ZZZ').prop('readonly',true);
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_newitemcode.gridname);
				$("#jqGrid input#"+id_optid+"_uomcode").val(data.s_uomcode);
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
			title:"Select Item Code",
			open: function(){
				// dialog_newitemcode.urlParam.table_name = ['material.stockloc AS s', 'material.product AS p'];
				dialog_newitemcode.urlParam.fixPost = "true";
				dialog_newitemcode.urlParam.table_id="none_";
				dialog_newitemcode.urlParam.url = "./repack/table";
				dialog_newitemcode.urlParam.action = "get_itemcode";
				dialog_newitemcode.urlParam.filterCol=['deptcode','recstatus','compcode','year'];
				dialog_newitemcode.urlParam.filterVal=[$("#jqGrid input[name='deptcode']").val(),'ACTIVE','session.compcode',moment($('#trandate').val()).year()];
				// dialog_newitemcode.urlParam.join_type=['LEFT JOIN'];
				// dialog_newitemcode.urlParam.join_onCol=['s.itemcode'];
				// dialog_newitemcode.urlParam.join_onVal=['p.itemcode'];
				// dialog_newitemcode.urlParam.join_filterCol=[['s.uomcode on =']];
				// dialog_newitemcode.urlParam.join_filterVal=[['p.uomcode']];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_newitemcode.makedialog(true);

	var dialog_deptcodedtl = new ordialog(
		'deptcodedtl',['material.stockloc AS s', 'sysdb.department AS d'],"#jqGrid2 input[name='deptcode']",'errorField',
		{
			colModel:[
				{label:'Department',name:'s_deptcode',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Description', name: 'd_description', width: 400, classes: 'pointer', canSearch: true,checked:true, or_search: true},
				// {label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','year'],
				filterVal:['ACTIVE','session.compcode',moment($("#jqGrid input[name='trandate']").val()).year()],
			},
			ondblClickRow: function () {
				$("#jqGrid2 input[name='olditemcode']").focus();
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
				dialog_deptcodedtl.urlParam.table_name = ['material.stockloc AS s', 'sysdb.department AS d'];
				dialog_deptcodedtl.urlParam.fixPost = "true";
				dialog_deptcodedtl.urlParam.table_id="none_";
				dialog_deptcodedtl.urlParam.url = "./repackDetail/table";
				dialog_deptcodedtl.urlParam.action = "get_deptcodedtl";
				dialog_deptcodedtl.urlParam.filterCol=['recstatus','compcode','year'];
				dialog_deptcodedtl.urlParam.filterVal=['ACTIVE','session.compcode',moment($("#jqGrid input[name='trandate']").val()).year()];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcodedtl.makedialog(true);
	
	var dialog_olditemcode = new ordialog(
		'olditemcode',['material.stockloc AS s', 'material.product AS p'],"#jqGrid2 input[name='olditemcode']",'errorField',
		{
			colModel:[
				{label:'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Description', name: 'p_description', width: 400, classes: 'pointer', canSearch: true,checked:true, or_search: true},
				{label:'uom',name:'s_uomcode', hidden:true},
				{label:'avgcost',name:'p_avgcost', hidden:false},
				{label:'p_uom',name:'p_uomcode', hidden:false},
			],
			urlParam: {
				filterCol:['deptcode','recstatus','compcode','year'],
				filterVal:[$("#jqGrid2 input[name='deptcode']").val(),'ACTIVE','session.compcode',moment($("#jqGrid input[name='trandate']").val()).year()],
			},
			ondblClickRow: function (event) {
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_olditemcode.gridname);
				$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.s_uomcode);
				$("#jqGrid2 input#"+id_optid+"_avgcost").val(data.p_avgcost);

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
			title:"Select Item Code",
			open: function(){
				// dialog_newitemcode.urlParam.table_name = ['material.stockloc AS s', 'material.product AS p'];
				dialog_olditemcode.urlParam.fixPost = "true";
				dialog_olditemcode.urlParam.table_id="none_";
				dialog_olditemcode.urlParam.url = "./repackDetail/table";
				dialog_olditemcode.urlParam.action = "get_itemcodedtl";
				dialog_olditemcode.urlParam.filterCol=['deptcode','recstatus','compcode','year'];
				dialog_olditemcode.urlParam.filterVal=[$("#jqGrid2 input[name='deptcode']").val(),'ACTIVE','session.compcode',moment($("#jqGrid input[name='trandate']").val()).year()];
				// dialog_newitemcode.urlParam.join_type=['LEFT JOIN'];
				// dialog_newitemcode.urlParam.join_onCol=['s.itemcode'];
				// dialog_newitemcode.urlParam.join_onVal=['p.itemcode'];
				// dialog_newitemcode.urlParam.join_filterCol=[['s.uomcode on =']];
				// dialog_newitemcode.urlParam.join_filterVal=[['p.uomcode']];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_olditemcode.makedialog(true);
	
	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}
	
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
	
	$("#jqGrid2_panel").on("show.bs.collapse", function(){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
	});
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
}

