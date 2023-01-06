
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
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
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	//////////////////////////////////////////////////////////////

	var fdl = new faster_detail_load();
	var mycurrency =new currencymode(['#stfamount','#amount', '#drprcnt', '#stfpercent']);	
	var mycurrency2 =new currencymode(['#stfamount','#amount', '#drprcnt', '#stfpercent']);

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				if($("form#formdata [name='appointment'][value='1']").is(":checked")){
					if($('#intervaltime').val().trim() == ''){
						alert('interval time required if appointment is true');
					}else{
						saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
					}
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
				}
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

	var oper = 'add';
	$("#dialogForm")
		.dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			toggleFormData('#jqGrid','#formdata');
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					$('#intervaltime').val(60);
					rdonly("#formdata");
					hideOne("#formdata");
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly("#formdata");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper!='view'){
				set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
				dialog_doctype.on();
				dialog_department.on();
				dialog_speciality.on();
				dialog_discipline.on();
				dialog_creditor.on();
			}
			if(oper!='add'){
				dialog_doctype.check(errorField);
				dialog_department.check(errorField);
				dialog_speciality.check(errorField);
				dialog_discipline.check(errorField);
				dialog_creditor.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('#formdata .alert').detach();
			dialog_doctype.off();
			dialog_department.off();
			dialog_speciality.off();
			dialog_discipline.off();
			dialog_creditor.off();
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
		url:'util/get_table_default',
		field: '',
		table_name:'hisdb.doctor',
		table_id:'doctorcode',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
		sort_idno: true,
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'doctor_save',
		url:'./doctor/form',
		field:'',
		oper:oper,
		table_name:'hisdb.doctor',
		table_id:'doctorcode',
		saveip:'true',
		checkduplicate:'true'
		//sysparam: {source: 'doc'} 
	};
	
	$("#jqGrid").jqGrid({
		datatype: "local",
			colModel: [
			{label: 'compcode', name: 'compcode', width: 90 , hidden: true, classes: 'wrap'},
			{label: 'Doctor Code', name: 'doctorcode', width: 40, canSearch:true, classes: 'wrap'},
			{label: 'Doctor Name', name: 'doctorname', width: 90, canSearch:true , classes: 'wrap', checked:true},
			{label: 'Login ID', name: 'loginid', width: 30, canSearch:true, classes: 'wrap'},
			{label: 'Costcenter', name: 'department', width: 90 , hidden: true, classes: 'wrap'},
			{label: 'Discipline Code', name: 'disciplinecode', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
			{label: 'Speciality Code', name: 'specialitycode', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
			{label: 'Doctor Type', name: 'doctype', width: 30, classes: 'wrap',  formatter: showdetail,unformat:un_showdetail},
			{label: 'Creditor', name: 'creditorcode', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Resign Date', name: 'resigndate', width: 90 , classes: 'wrap',hidden: true},
			{label: 'idno', name: 'idno', width: 90, classes: 'wrap',hidden: true},
			{label: 'Class', name: 'classcode', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Admission Right', name: 'admright', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Appointment', name: 'appointment', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Company', name: 'company', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Address', name: 'address1', width: 90 , classes: 'wrap',hidden: true},
			{label: 'address2', name: 'address2', width: 90 , classes: 'wrap',hidden: true},
			{label: 'address3', name: 'address3', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Postcode', name: 'postcode', width: 90 , classes: 'wrap',hidden: true},
			{label: 'State', name: 'statecode', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Country', name: 'countrycode', width: 90 , classes: 'wrap',hidden: true},
			{label: 'GST No', name: 'gstno', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Home', name: 'res_tel', width: 90 , classes: 'wrap',hidden: true},
			{label: 'H/Phone', name: 'tel_hp', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Office', name: 'off_tel', width: 90 , classes: 'wrap',hidden: true},	
			{label: 'Operation Theatre (OT)', name: 'operationtheatre', width: 90 , classes: 'wrap',hidden: true},
			{label: 'Status', name: 'recstatus', width: 20, classes: 'wrap', formatterstatus:formatterstatus, unformatstatus:unformatstatus, 
					cellattr: function(rowid, cellvalue)
					{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''},
			},
			{label: 'Interval Time', name: 'intervaltime', width: 90 , classes: 'wrap',hidden: true},
			{label: 'mmcid', name: 'mmcid', width: 90 , classes: 'wrap',hidden: true},
			{label: 'apcid', name: 'apcid', width: 90 , classes: 'wrap',hidden: true},
			{label: 'adduser', name: 'adduser', width: 90, hidden:true},
			{label: 'adddate', name: 'adddate', width: 90, hidden:true},
			{label: 'upduser', name: 'upduser', width: 90, hidden:true},
			{label: 'upddate', name: 'upddate', width: 90, hidden:true},
			{label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
		

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname:'idno',
		sortorder:'desc',
		loadonce:false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
		},
		onSelectRow:function(rowid, selected){
			urlParam2.filterVal[2]=selrowData("#jqGrid").doctorcode;
			refreshGrid("#jqGrid2",urlParam2);
			
		},
		
		
	});

	//////////////////////////// STATUS FORMATTER /////////////////////////////////////////////////
	
	function formatterstatus(cellvalue, options, rowObject) {
		if (cellvalue == 'ACTIVE') {
			return "ACTIVE";
		}
		if (cellvalue == 'DEACTIVE') {
			return "DEACTIVE";
		}
	}

	function unformatstatus(cellvalue, options) {
		if (cellvalue == 'ACTIVE') {
			return "ACTIVE";
		}
		if (cellvalue == 'DEACTIVE') {
			return "DEACTIVE";
		}
	}

	function formatter1(cellvalue, option, rowObject) {
		return cellvalue + "%";
	}

	function unformat1(cellvalue, options) {
		return cellvalue.replace("%", "");
	}

	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'disciplinecode':field=['code','description'];table="hisdb.discipline";case_='disciplinecode';break;
			case 'specialitycode':field=['specialitycode','description'];table="hisdb.speciality";case_='specialitycode';break;
			case 'doctype':field=['statuscode','description'];table="hisdb.docstatus";case_='doctype';break;
			case 'chgcode': field = ['chgcode', 'description']; table = "hisdb.chgmast";case_='chgcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('doctorScript',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	function chgcodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Charge Code':temp=$("input[name='chgcode']");break;
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{ 'idno': selrowData('#jqGrid').idno,'doctorcode': selrowData('#jqGrid').doctorcode });
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
			recstatusDisable();

		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	/////////////////////////////////////////////Doctor Detail/////////////////////////////////////////////
	///////////////////////////////////////parameter for jqgrid2 url///////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['dc.compcode', 'dc.lineno_', 'dc.chgcode', 'dc.drcode', 'dc.effdate', 'dc.epistype', 'dc.drprcnt', 'dc.amount', 'dc.stfamount', 'dc.stfpercent', 'dc.idno', 'd.doctorcode'],
		table_name:['debtor.drcontrib AS dc', 'hisdb.doctor AS d'],
		table_id:'lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['dc.drcode'],
		join_onVal:['d.doctorcode'],
		filterCol:['dc.compcode', 'dc.unit','d.doctorcode'],
		filterVal:['session.compcode', 'session.unit','']
	};

	var addmore_jqGrid2={more:false,state:true,edit:false}
	////////////////////////////////////////////////jqgrid2////////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./doctorContribution/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, frozen:true, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'drcode', name: 'drcode', width: 40, frozen:true, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Charge Code', name: 'chgcode', width: 150, classes: 'wrap', editable:true,
				editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
					edittype:'custom',	editoptions:
						{  custom_element:chgcodeCustomEdit,
						   custom_value:galGridCustomValue 	
						},
			},
			{ label: 'Effective date', name: 'effdate', width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							//showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function(){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Type', name: 'epistype', width: 100, classes: 'wrap', editable:true,
				edittype: "select",
				editoptions: {
					value: "IP:IP;OP:OP",
				}
			},
			{ label: 'Patient %', name: 'drprcnt', width: 100, align: 'right', classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
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
			{ label: 'Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
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
			{ label: 'Staff %', name: 'stfpercent', width: 100, align: 'right', classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
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
			{ label: 'Amount', name: 'stfamount', width: 100, align: 'right', classes: 'wrap', 	
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
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
			if(addmore_jqGrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqGrid2.edit = addmore_jqGrid2.more = false; //reset
			
		},
		gridComplete: function(){
			fdl.set_array().reset();
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid2_iledit").click();
			$('#p_error').text('');   //hilangkan duplicate error msj after save
		}
	});
	var hide_init=0;
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////////////////////////////Doctor Detail/////////////////////////////////////////////////
	///////////////////////////////////////////myEditOptions for jqGrid2///////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid2').data('lastselrow','none');	
			$("#jqGridPager2Delete,#jqGridPager2Refresh").hide();

			dialog_chgcode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='stfamount']","#jqGrid2 input[name='amount']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			$("input[name='stfamount']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				/*addmore_jqGrid2.state = true;
				$('#jqGrid2_ilsave').click();*/
			});
			$("#jqGrid2 input[type='text']").on('focus',function(){
				$("#jqGrid2 input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid2 input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqGrid2.state == true)addmore_jqGrid2.more=true; //only addmore after save inline
			addmore_jqGrid2.more = true; //state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'add');
			errorField.length=0;
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText)
			//$('#p_error').text(response.responseText);
			err_reroll.old_data = data.request;
			err_reroll.error = true;
			err_reroll.errormsg = data.errormsg;
			refreshGrid('#jqGrid2',urlParam2,'add');
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./doctorContribution/form?"+
				$.param({
					action: 'doctorContribution_save',
					oper: 'add',
					drcode: selrowData('#jqGrid').doctorcode,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	///////////////////////////////////////////myEditOptions_edit for jqGrid2///////////////////////////////////////////
	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGridPager2Delete,#jqGridPager2Refresh").hide();
			errorField.length = 0;

			dialog_chgcode.on();

			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='stfamount']","#jqGrid2 input[name='amount']"]);

			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			
			$("input[name='stfamount']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				/*addmore_jqGrid2.state = true;
				$('#jqGrid2_ilsave').click();*/
			});
			$("#jqGrid2 input[type='text']").on('focus',function(){
				$("#jqGrid2 input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid2 input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqGrid2.state == true)addmore_jqGrid2.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'edit');
			errorField.length=0;
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorfunc: function(rowid,response){
			// console.log(response);
			alert('Error: '+response.responseText);
			// $('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam);
			refreshGrid('#jqGrid2',urlParam2);
		},
		beforeSaveRow: function (options, rowid) {
			$('#p_error').text('');
			if(errorField.length>0)return false;

			mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./doctorContribution/form?"+
				$.param({
					action: 'doctorContribution_save',
					drcode: selrowData('#jqGrid2').drcode,
					idno: selrowData('#jqGrid2').idno,
					lineno_: selrowData('#jqGrid2').lineno_
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid2',urlParam2,'edit');
			$("#jqGridPager2Delete,#jqGridPager2Refresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	////////////////////////////////////////////////pager jqgrid2////////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
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
								action: 'doctorContribution_save',
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./doctorContribution/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								alert('Error: '+data.responseText);
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
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid2", urlParam2);
		},
	});
	
	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus']);

	////////////////////object for dialog handler//////////////////

	var dialog_doctype = new ordialog(
		'docstatus','hisdb.docstatus','#doctype',errorField,
		{	colModel:[
				{label:'Status Code',name:'statuscode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#doctorname').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#doctorname').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Doctor Type",
			open: function(){
				dialog_doctype.urlParam.filterCol=['recstatus'],
				dialog_doctype.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_doctype.makedialog();

	var dialog_department = new ordialog(
		'costcenter','finance.costcenter','#department',errorField,
		{	colModel:[
				{label:'Cost Code',name:'costcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#specialitycode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#specialitycode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Department",
			open: function(){
				dialog_department.urlParam.filterCol=['recstatus'],
				dialog_department.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_department.makedialog();


	var dialog_speciality = new ordialog(
		'speciality','hisdb.speciality','#specialitycode',errorField,
		{	colModel:[
				{label:'Speciality Code',name:'specialitycode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#disciplinecode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#disciplinecode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Speciality",
			open: function(){
				dialog_speciality.urlParam.filterCol=['recstatus'],
				dialog_speciality.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_speciality.makedialog();
	
	var dialog_discipline = new ordialog(
		'discipline','hisdb.discipline','#disciplinecode',errorField,
		{	colModel:[
				{label:'Discipline Code',name:'code',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#creditorcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#creditorcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Discipline",
			open: function(){
				dialog_discipline.urlParam.filterCol=['recstatus'],
				dialog_discipline.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_discipline.makedialog();
	
	var dialog_creditor = new ordialog(
		'supplier','material.supplier','#creditorcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Supplier Name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#classcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#classcode').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_creditor.urlParam.filterCol=['recstatus'],
				dialog_creditor.urlParam.filterVal=['ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_creditor.makedialog();

	var dialog_chgcode = new ordialog(
		'chgcode','hisdb.chgmast',"#jqGrid2 input[name='chgcode']",errorField,
		{	colModel:[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE', 'session.compcode']
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

			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							// $('#lastuser').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Charge Code",
			open: function(){
				dialog_chgcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_chgcode.urlParam.filterVal = ['ACTIVE', 'session.compcode'];
			},
			close: function(){
				//$("#jqGrid2 input[name='quantity']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_chgcode.makedialog();

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGrid2_panel").on("show.bs.collapse", function(){
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-18));
	});

});
		