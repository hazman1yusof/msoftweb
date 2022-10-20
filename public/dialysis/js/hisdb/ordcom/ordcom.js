$(document).ready(function () {

	var fdl = new faster_detail_load();
	disableForm('#form_ordcom');

	// $("#new_ordcom").click(function(){
	// 	
	// });
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
	
	var mycurrency2 =new currencymode([]);

	var dialog_isudept = new ordialog(
		'ordcom_isudept','sysdb.department',"#jqGrid_ordcom input[name='ordcom_isudept']",errorField,
		{	colModel:
			[
				{label:'Charge Code',name:'deptcode',width:200,classes:'pointer',canSearch:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','chgdept'],
				filterVal:['ACTIVE', 'session.compcode','1'],
			},
			ondblClickRow:function(event){
				$("#jqGrid_ordcom input[name='ct_taxcode']").focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid_ordcom input[name='ct_taxcode']").focus().select();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			},
			loadComplete:function(data){
			}
		},{
			title:"Select Issue Dept",
			open: function(){
				dialog_isudept.urlParam.filterCol = ['recstatus','compcode','chgdept'];
				dialog_isudept.urlParam.filterVal = ['ACTIVE', 'session.compcode','1'];
			},
			close: function(){
				$("#jqGrid_ordcom input[name='ct_taxcode']").focus().select();
			}
		},'urlParam','radio','tab','table'
	);
	dialog_isudept.makedialog();

	var dialog_taxcode = new ordialog(
		'ordcom_taxcode','hisdb.taxmast',"#jqGrid_ordcom input[name='ordcom_taxcode']",errorField,
		{	colModel:
			[
				{label:'GST Code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['recstatus','compcode','taxtype'],
				filterVal:['ACTIVE', 'session.compcode','INPUT'],
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}

				let data=selrowData('#'+dialog_taxcode.gridname);

				$("table#jqGrid_ordcom input#"+id_optid+"_ct_taxcode_gstpercent").val(data['rate']);
				$('#ct_remarks').focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#ct_remarks').focus().select();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			},
		},{
			title:"Select GST Code",
			open: function(){
				dialog_taxcode.urlParam.filterCol = ['recstatus','compcode','taxtype'];
				dialog_taxcode.urlParam.filterVal = ['ACTIVE', 'session.compcode','INPUT'];
			},
			close: function(){
				$("#jqGrid_ordcom input[name='ct_remarks']").focus().select();
			}
		},'urlParam','radio','tab','table'
	);
	dialog_taxcode.makedialog();

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Charge Code':temp=$("#jqGrid_ordcom input[name='ordcom_chgcode']");break;
			// case 'GST Code':temp=$("#jqGrid_ordcom input[name='ordcom_taxcode']");break;
			break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
			case 'ct_isudept':field=['deptcode','description'];table="sysdb.department";case_='isudept';break;
			case 'ct_taxcode':field=['taxcode','description'];table="hisdb.chargetrx";case_='taxcode';break;

		}
		var param={action:'input_check',url:'./util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bedmanagement',options,param,case_,cellvalue);
		
		if(cellvalue==null)return "";
		return cellvalue;
	}

	function chgcodeOrdcomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid_ordcom" optid="'+opt.id+'" id="'+opt.id+'" name="ordcom_chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0" disabled="disabled"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function isudeptOrdcomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid_ordcom" optid="'+opt.id+'" id="'+opt.id+'" name="ordcom_isudept" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function ct_trxtime_custom(val,opt){
		return $('<input type="time" optid="'+opt.id+'" id="'+opt.id+'" name="ct_trxtime" class="form-control input-sm" data-validation="required" value="'+val+'">');
	}

	function ct_remarks_custom(val,opt){
		return $('<textarea optid="'+opt.id+'" id="'+opt.id+'" name="ct_remarks" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="width:98%" rows="5">'+val);
	}

	function taxcodeOrdcomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid_ordcom" optid="`+opt.id+`" id="`+opt.id+`" name="ordcom_taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="` + val + `" style="z-index: 0">
			<a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
			</div>
			<span class="help-block"></span>
			<div class="input-group">
				<input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden" value="1">
			</div>
			`);
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}


	/////////////////////parameter for jqgrid4 url/////////////////////////////////////////////////

	var addmore_jqGrid_ordcom={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqGrid_ordcom, state true kalu

	/////////////////////parameter for saving url////////////////////////////////////////////////

	$("#jqGrid_ordcom").jqGrid({
		datatype: "local",
		editurl: "./ordcom/form",
		colModel: [
			{ label: 'auditno', name: 'auditno', hidden:true},
			{ label: 'compcode', name: 'compcode', hidden:true},
			// { label: 'Date', name: 'ct_trxdate', width: 100, classes: 'wrap',editable:true,
			// 	formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
			// 	editoptions: {
   //                  dataInit: function (element) {
   //                      $(element).datepicker({
   //                          id: 'ct_trxdate_datePicker',
   //                          dateFormat: 'dd/mm/yy',
   //                          minDate: 1,
   //                          showOn: 'focus',
   //                          changeMonth: true,
		 //  					changeYear: true,
			// 				onSelect : function(){
			// 					$(this).focus();
			// 				}
   //                      });
   //                  }
   //              }
			// },
			// { label: 'Time', name: 'ct_trxtime', width: 100, classes: 'wrap',editable:true,
			// 	edittype:'custom',	editoptions:
			// 		{ 	custom_element:ct_trxtime_custom,
			// 			custom_value:galGridCustomValue 	
			// 		},
			// },
				
			{ label: 'Charge Code', name: 'chgcode', width: 150 , classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,unformat:un_showdetail,
				edittype:'custom',	editoptions:
					{ 	custom_element:chgcodeOrdcomCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Price', name: 'unitprice', width: 100, formatter: 'currency', classes: 'wrap txnum', align: 'right', editable:true,
				editoptions: {
                	dataInit: function (element) {
                    $(element).attr('disabled','disabled');
                }
            }},	
			{ label: 'Quantity', name: 'quantity', width: 100, formatter: 'currency', classes: 'wrap txnum', align: 'right', editable:true},	
			// { label: 'Issue Department', name: 'ct_isudept', width: 150,editable:true,
			// 	editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,unformat:un_showdetail,
			// 	edittype:'custom',	editoptions:
			// 		{ 	custom_element:isudeptOrdcomCustomEdit,
			// 			custom_value:galGridCustomValue 	
			// 		},
			// },
			// { label: 'GST Code', name: 'ct_taxcode', width: 120 , classes: 'wrap', editable:true,
			// 	editrules:{required: true,custom:true, custom_func:cust_rules}, formatter: showdetail,unformat:un_showdetail,
			// 	edittype:'custom',	editoptions:
			// 		{ 	custom_element:taxcodeOrdcomCustomEdit,
			// 			custom_value:galGridCustomValue 	
			// 		},
			// },
			{ label: 'Amount', name: 'amount', width: 100, formatter: 'currency', classes: 'wrap txnum', align: 'right', editable:true,
				editoptions: {
                    dataInit: function (element) {
                        $(element).attr('disabled','disabled');
                    }
                }},	
			{ label: 'Remarks', name: 'remarks', hidden:false,width:300, editable:true,
				edittype:'textarea',editoptions: { rows: 4 }

			},
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'auditno',
		sortorder: 'desc',
		pager: "#jqGridPager_ordcom",
		onSelectRow:function(rowid, selected){
			// populate_form_ordcom(selrowData("#jqGrid_ordcom"));
		},
		loadComplete: function(){
			if($('#ordcom_priceview_hide').val() != '1')$(this).jqGrid('hideCol',["unitprice"]); 

			if(addmore_jqGrid_ordcom.more == true){$('#jqGrid_ordcom_iladd').click();}
			else{
				$('#jqGrid_ordcom').jqGrid ('setSelection', "1");
			}

			addmore_jqGrid_ordcom.edit = addmore_jqGrid_ordcom.more = false; //reset			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){			
			$("#jqGrid_iledit").click();
			$('#p_error').text('');   
		},
		gridComplete: function(){
			fdl.set_array().reset();
			hideatdialogForm(false);
		}
	});

	// $("#jqGrid_ordcom").jqGrid('navGrid','#jqGridPager_ordcom',
	// 	{	
	// 		edit:false,view:false,add:false,del:false,search:false,
	// 		beforeRefresh: function(){
	// 			refreshGrid("#jqGrid",urlParam);
	// 		},
			
	// 	}	
	// );

	//////////////////////////My edit options ORDERCOM/////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {

			$("#jqGridPager_ordcomDelete,#jqGridPager_ordcomRefresh, #jqGridPager_ordcomEditAll, #jqGridPager_ordcomrSaveAll, #jqGridPager_ordcomCancelAll").hide();
			dialog_isudept.on();
			dialog_taxcode.on();
			$("#jqGrid_ordcom :input[name='ct_trxdate']").focus();
			$("#jqGrid_ordcom :input[name='remarks']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ordcom_ilsave').click();
			});


			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["table#jqGrid_ordcom input[name='unitprice']","table#jqGrid_ordcom input[name='amount']","table#jqGrid_ordcom input[name='quantity']"]);
			mycurrency2.formatOnBlur();

			$("#jqGrid_ordcom input[name='quantity'], #jqGrid_ordcom input[name='ordcom_taxcode'], #jqGrid_ordcom input[name='amtdisc']").on('blur',{currency: mycurrency2},onleave_input_ordcom);

			$("#jqGrid_ordcom [name='ct_trxdate']").val(moment().format('D/M/YYYY'));
			$("#jqGrid_ordcom [name='ct_trxtime']").val(moment().format('hh:mm:ss'));
			// $("#jqGrid_ordcom [name='ordcom_isudept']").val($("#ordcom_deptcode_hide").val());

			let selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			let drname = $("#jqGrid tr#"+selrow).find("td[aria-describedby='jqGrid_admdoctor'] span.help-block").text();

			// $("#jqGrid_ordcom [name='ct_remarks']").text(drname);

			$("#jqGrid_ordcom [name='ordcom_chgcode'] ~ a").click(function(){
				let c_optid = $(this).prev('input').first().attr('optid');
				let c_id = $(this).prev('input').first().attr('id');
				if($(this).attr('disabled') != 'disabled'){
					$('#mdl_ordcom_chgcode').data('c_optid',c_optid);
					$('#mdl_ordcom_chgcode').data('c_id',c_id);
					$("#mdl_ordcom_chgcode").modal();
				}
			});

		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true;
			addmore_jqGrid_ordcom.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
			errorField.length=0;
			$("#jqGridPager_ordcomDelete,#jqGridPager_ordcomRefresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			$('#p_error').text(data.errormsg);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			// if(errorField.length>0){console.log(errorField);return false;}

			mycurrency2.formatOff();

			let editurl = "./ordcom/form?"+
				$.param({
					_token: $("#csrf_token").val(),	
					mrn: $('#mrn_ordcom').val(),
					episno: $('#episno_ordcom').val(),
					action: 'saveForm_ordcom',
				});

			$("#jqGrid_ordcom").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	//////////////////////////End My edit options ORDERCOM/////////////////////////////////////////////////////////
	
	/////////////////////////start grid pager ORDERCOM/////////////////////////////////////////////////////////
	$("#jqGrid_ordcom").inlineNav('#jqGridPager_ordcom', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions,
			
	}).jqGrid('navButtonAdd', "#jqGridPager_ordcom", {	
		id: "jqGridPager_ordcomDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_ordcom").jqGrid('getGridParam', 'selrow');	
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
								action: 'saveForm_ordcom',	
								// cheqno: $('#cheqno').val(),	
								// mrn: selrowData('#jqGrid_ordcom').mrn,	
							}	
							$.post( "./ordcom/form?"+$.param(param),{oper:'del_ordcom',"_token": $("#_token").val()}, function( data ){	
							}).fail(function (data) {	
								$('#p_error').text(data.responseText);	
							}).done(function (data) {	
								refreshGrid("#jqGrid_ordcom", urlParam_ordcom);	
							});	
						}else{	
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
						}	
					}	
				});	
			}	
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{	
		id: "jqGridPager_ordcomEditAll",	
		caption:"",cursor: "pointer",position: "last", 	
		buttonicon:"glyphicon glyphicon-th-list",	
		title:"Edit All Row",	
		onClickButton: function(){	
				
			var ids = $("#jqGrid_ordcom").jqGrid('getDataIDs');	
			for (var i = 0; i < ids.length; i++) {	
				$("#jqGrid_ordcom").jqGrid('editRow',ids[i]);	
			}	
			hideatdialogForm(true,'saveallrow');	
		},	
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{	
		id: "jqGridPager_ordcomSaveAll",	
		caption:"",cursor: "pointer",position: "last", 	
		buttonicon:"glyphicon glyphicon-download-alt",	
		title:"Save All Row",	
		onClickButton: function(){	
			var ids = $("#jqGrid_ordcom").jqGrid('getDataIDs');	
			var jqGrid_ordcom_data = [];	
			for (var i = 0; i < ids.length; i++) {	
				var data = $('#jqGrid_ordcom').jqGrid('getRowData',ids[i]);	
				var obj = 	
				{	
					'idno' : ids[i],	
					'startno' : $("#jqGrid_ordcom input#"+ids[i]+"_startno").val(),	
					'endno' : $("#jqGrid_ordcom input#"+ids[i]+"_endno").val(),	
					'cheqqty' : $("#jqGrid_ordcom input#"+ids[i]+"_cheqqty").val()	
				}	
				jqGrid_ordcom_data.push(obj);	
			}	
			var param={	
				action: 'saveForm_ordcom',	
				_token: $("#_token").val(),	
				mrn: selrowData('#jqGrid').mrn,	
				
			}	
			$.post( "./ordcom/form?"+$.param(param),{oper:'edit_all_ordcom',dataobj:jqGrid_ordcom_data}, function( data ){	
			}).fail(function(data) {	
				$('#p_error').text(data.responseText);	
				////errorText(dialog,data.responseText);	
			}).done(function(data){	
				hideatdialogForm(false);	
				refreshGrid("#jqGrid_ordcom",urlParam_ordcom);	
			});	
		},	
		afterrestorefunc : function( response ) {	
			refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');	
			//$("#jqGridPagerDelete,#jqGridPagerRefresh, #jqGridPagerEditAll").show();	
		},	
		errorTextFormat: function (data) {	
			alert(data);	
		}	
			
	}).jqGrid('navButtonAdd',"#jqGridPager_ordcom",{	
		id: "jqGridPager_ordcomCancelAll",	
		caption:"",cursor: "pointer",position: "last", 	
		buttonicon:"glyphicon glyphicon-remove-circle",	
		title:"Cancel",	
		onClickButton: function(){	
			hideatdialogForm(false);	
			refreshGrid("#jqGrid_ordcom",urlParam_ordcom);	
		},		
	}).jqGrid('navButtonAdd', "#jqGridPager_ordcom", {	
		id: "jqGridPager_ordcomRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {	
			oper = 'add_ordcom'	
			refreshGrid("#jqGrid_ordcom", urlParam_ordcom);	
		},	
	});	

	$("#jqGrid_ordcom_panel").on("shown.bs.collapse", function(){
		hideatdialogForm(false);
		refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');
		$("#jqGrid_ordcom").jqGrid ('setGridWidth', Math.floor($("#jqGrid_ordcom_c")[0].offsetWidth-$("#jqGrid_ordcom_c")[0].offsetLeft-28));
	});
});	

function onleave_input_ordcom(event){
    event.data.currency.formatOff();
	var c_optid = event.currentTarget.id;
	var id_optid = c_optid.substring(0,c_optid.search("_"));

	let qtyorder = parseFloat($("table#jqGrid_ordcom input#"+id_optid+"_quantity").val());
	let unitprice = parseFloat($("table#jqGrid_ordcom input#"+id_optid+"_unitprice").val());
	let gstpercent = parseFloat($("table#jqGrid_ordcom input#"+id_optid+"_ct_taxcode_gstpercent").val());

	var totamtperUnit = (unitprice*qtyorder);

	var tot_gst = totamtperUnit * (gstpercent / 100);
	var totalAmount = totamtperUnit + tot_gst;

	$("#"+id_optid+"_amount").val(totalAmount);

	event.data.currency.formatOn();//change format to currency on each calculation
}

function hideatdialogForm(hide,saveallrow){	
	if(saveallrow == 'saveallrow'){	
		$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll,#saveDetailLabel").hide();	
		$("#jqGridPager_ordcomSaveAll,#jqGridPager_ordcomCancelAll").show();	
	}else if(hide){	
		$("#jqGrid_ordcom_iledit,#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll,#jqGridPager_ordcomSaveAll,#jqGridPager_ordcomCancelAll").hide();	
		$("#saveDetailLabel").show();	
	}else{	
		$("#jqGrid_ordcom_iladd,#jqGrid_ordcom_ilcancel,#jqGrid_ordcom_ilsave,#saveHeaderLabel,#jqGridPager_ordcomDelete,#jqGridPager_ordcomEditAll").show();	
		$("#saveDetailLabel,#jqGridPager_ordcomSaveAll,#jqGrid_ordcom_iledit,#jqGridPager_ordcomCancelAll").hide();	
	}	
}
	
var urlParam_ordcom={
	action:'ordcom_table',
	url:'./ordcom/table',
	mrn:'',
	episno:''
	// field: '',
	// fixPost:'true',
	// table_name: ['hisdb.chargetrx AS ct','hisdb.chgmast AS cm'],
	// join_type:['LEFT JOIN'],
	// join_onCol:['cm.chgcode'],
	// join_onVal:['ct.chgcode'],
	// join_filterCol:[['cm.compcode on =']],
	// join_filterVal:[['ct.compcode']],
	// filterCol:['ct.compcode'],
	// filterVal:['session.compcode'],
};

//screen bed management//
function populate_form_ordcom(obj,rowdata){	
	//panel header	
	$('#name_show_ordcom').text(obj.name);
	$('#mrn_show_ordcom').text(("0000000" + obj.mrn).slice(-7));
	$('#sex_show_ordcom').text(obj.sex);
	$('#dob_show_ordcom').text(dob_chg(obj.dob));
	$('#age_show_ordcom').text(obj.age+ ' (YRS)');
	$('#race_show_ordcom').text(obj.race);
	$('#religion_show_ordcom').text(if_none(obj.religion));
	$('#occupation_show_ordcom').text(if_none(obj.occupation));
	$('#citizenship_show_ordcom').text(obj.citizen);
	$('#area_show_ordcom').text(obj.area);

	//formordcom	
	$('#mrn_ordcom').val(obj.mrn);	
	$("#episno_ordcom").val(obj.Episno);
	urlParam_ordcom.mrn = obj.MRN;
	urlParam_ordcom.mrn = obj.Episno;

	// document.getElementById('showOrdcom_bedmgmt').style.display = 'inline'; //to show hidden data

	var saveParam={	
        action:'get_table_ordcom',	
    }	
    var postobj={	
    	_token : $('#csrf_token').val(),	
    	mrn:obj.mrn,	
    	episno:obj.episno	
    };	
}

//screen current patient//
function populate_ordcom_currpt(obj){
	//panel header	
	$('#name_show_ordcom').text(obj.Name);
	$('#mrn_show_ordcom').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_ordcom').text((obj.Sex).toUpperCase());
	$('#dob_show_ordcom').text(dob_chg(obj.DOB));
	$('#age_show_ordcom').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_ordcom').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_ordcom').text(if_none(obj.religionDesc).toUpperCase());
	$('#occupation_show_ordcom').text(if_none(obj.occupDesc).toUpperCase());
	$('#citizenship_show_ordcom').text(if_none(obj.cityDesc).toUpperCase());
	$('#area_show_ordcom').text(if_none(obj.areaDesc).toUpperCase());

	//formordcom	
	$('#mrn_ordcom').val(obj.MRN);	
	$("#episno_ordcom").val(obj.Episno);
	urlParam_ordcom.mrn = obj.MRN;
	urlParam_ordcom.episno = obj.Episno;
	refreshGrid('#jqGrid_ordcom',urlParam_ordcom,'add');

	// document.getElementById('showOrdcom_curpt').style.display = 'inline'; //to show hidden data

	var saveParam={	
        action:'get_table_ordcom',	
	}	
	
    var postobj={	
    	_token : $('#csrf_token').val(),	
    	mrn:obj.MRN,	
    	episno:obj.Episno	
	};	
	
}

//---------------------mdl_chgcode-----------------------
var ordcom_chgcode_selecter_;
$('#mdl_ordcom_chgcode').on('show.bs.modal', function () {		//////mdl = modal, search show.bs.modal

	if(ordcom_chgcode_selecter_ == undefined){
	    ordcom_chgcode_selecter_ = new ordcom_chgcode_selecter(
	    	$(this).data('c_optid'),$(this).data('c_id')
	    );
		ordcom_chgcode_selecter_.on();
	}else{
		ordcom_chgcode_selecter_.on();
	}
});

function ordcom_chgcode_selecter(c_optid,c_id){
	this.c_optid = c_optid;
	this.c_id = c_id;

    var chgcode_table = null;

    chgcode_table = $('#chgcode_table').DataTable( {
        "ajax": "ordcom/table?action=chgcode_table",
        "paging":true,
        "pageLength": 10,
        "columns": [
            {'data': 'chgcode'},
            {'data': 'desc'},
            {'data': 'chggroup'},
            {'data': 'grpcode'},
            {'data': 'description'},
            {'data': 'amt1'}
        ],
        order: [[2, 'asc']],
        columnDefs: [{
                targets: [2,3],
                visible: false
        } ],
        rowGroup: {
            dataSrc: [ "chggroup" ],
            startRender: function ( rows, group ) {
	            return group + `<i class="arrow fa fa-angle-double-down"></i>`;
	        }
        },
        "createdRow": function( row, data, dataIndex ) {
	    	$(row).addClass( data['chggroup'] );
	    },
        "initComplete": function(settings, json) {
        }
    } );

    function ordcom_click_tr(event){
    	let c_optid = event.data.data.c_optid;
    	let c_id = event.data.data.c_id;
		var id_optid = c_optid.substring(0,c_optid.search("_"));

    	let item = chgcode_table.row( this ).data();
        if(item != undefined){
            $('input#'+c_optid).val(item["chgcode"]);
            $('input#'+id_optid+'_unitprice').val(item["amt1"]);
            $('input#'+c_optid).val(item["chgcode"]);
            if($('input#'+c_optid).parent().first().siblings('span.help-block').length == 0){
            	$('input#'+c_optid).parent().first().after("<span class='help-block'>"+item["desc"]+"</span>")
            }else{
                $('input#'+c_optid).parent().first().siblings('span.help-block').first().text(item["desc"]);
            }
            $('#mdl_ordcom_chgcode').modal('hide');
        }
    }

    this.on = function(){
	    $('#chgcode_table tbody').on('click', 'tr.dtrg-group', function () {    
	        let chggroup = $(this).children().text();
	        if($(this).data('_hidden') == undefined || $(this).data('_hidden') == 'show'){
	        	$("#chgcode_table tbody tr."+chggroup).hide();
	        	$(this).data('_hidden','hide');
	        }else if($(this).data('_hidden') == 'hide'){
	        	$("#chgcode_table tbody tr."+chggroup).show();
	        	$(this).data('_hidden','show');
	        }
	    });

    	$('#chgcode_table tbody').on('dblclick', 'tr',{data:this}, ordcom_click_tr);

	    $("#mdl_ordcom_chgcode").on('hidden.bs.modal', function () {
	        $('#chgcode_table tbody').off('hidden.bs.modal');
	        $('#chgcode_table tbody').off('click');
	        $('#chgcode_table tbody').off('dblclick');
	    });
    }

}

