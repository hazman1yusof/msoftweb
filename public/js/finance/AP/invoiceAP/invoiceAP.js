
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
				console.log(errorField);
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount','#apacthdr_amount','#apacthdr_outamount']);
	var fdl = new faster_detail_load();
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#apacthdr_recdate"]);
	actdateObj.getdata().set();

	
	////////////////////////////////////start dialog//////////////////////////////////////

	var oper;
	var unsaved = false,counter_save=0;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				counter_save=0;
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
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
				}
				if(oper!='view'){
					dialog_supplier.on();
					dialog_payto.on();
					dialog_category.on();
					dialog_department.on();
				}
				if(oper!='add'){
					dialog_supplier.check(errorField);
					dialog_payto.check(errorField);
					dialog_category.check(errorField);
					dialog_department.check(errorField);
				}
			},
			beforeClose: function(event, ui){
				if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false
							$("#dialogForm").dialog('close');
						}
					});
				}

				console.log($('#apacthdr_outamount').val());
				console.log($('#apacthdr_amount').val());
				console.log($('#apacthdr_outamount').val() != $('#apacthdr_amount').val());

				mycurrency.formatOff();
				if($('#apacthdr_outamount').val() != $('#apacthdr_amount').val() && counter_save==0){
					event.preventDefault();
					bootbox.confirm({
					    message: "Total Detail Amount is not equal with Invoice Amount. <br> Do you want to proceed?",
					    buttons: { confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
					    },
					    callback: function (result) {
					    	if(result == true){
								counter_save=1;
								$("#dialogForm").dialog('close');
					    	}else{
					    		if($('#saveHeaderLabel').is(":visible")){
					    			$("#saveHeaderLabel").click();
					    		}
					    		mycurrency.formatOn()
					    	}
					    }
					});
				}
				
			},
			close: function( event, ui ) {
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;
			//reset balik
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.my-alert').detach();
			$("#formdata a").off();
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
			//radbuts.reset();
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['finance.apacthdr','material.supplier'],
		table_id:'apacthdr_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['apacthdr.suppcode'],
		filterCol: ['source'],
		filterVal: ['AP'],
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'invoiceAP_save',
		url:'/invoiceAP/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'apacthdr_auditno'
	};

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

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				// $('#auditno').text("");//tukar kat depan tu
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			// $('#auditno').text("");//tukar kat depan tu
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
	datatype: "local",
	colModel: [
		//{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
		{ label: 'Audit No', name: 'apacthdr_auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero},
		{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap'},
		{ label: 'Creditor', name: 'apacthdr_suppcode', width: 20, classes: 'wrap', canSearch: true},
		{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap', canSearch: true},
		{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap', canSearch: true},
		{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap'},
		{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
		{ label: 'Outamount', name: 'apacthdr_outamount', width: 25 ,hidden:true, classes: 'wrap'},
		{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap',},
		{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap', hidden:true},
		{ label: 'Doc Date', name: 'apacthdr_recdate', width: 25, classes: 'wrap', hidden:true},
		{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
		{ label: 'source', name: 'apacthdr_source', width: 40, hidden:'true'},
		{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:'true'},
		{ label: 'unit', name: 'apacthdr_unit', width: 40, hidden:'true'},

	],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'apacthdr_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
		let recstatus = selrowData("#jqGrid").apacthdr_recstatus;
			if(recstatus=='OPEN'){
				$('#but_cancel_jq,#but_post_jq').show();
				
			}else if(recstatus=="POSTED"){
				$('#but_post_jq').hide();
				$('#but_cancel_jq').show();
			}else if (recstatus == "CANCELLED"){
				$('#but_cancel_jq,#but_post_jq').hide();
				
			}

			$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);
			$('#idno').val(selrowData("#jqGrid").apacthdr_idno);

			urlParam2.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;

			refreshGrid("#jqGrid3",urlParam2);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
				$('#save').hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},
		

	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit']);

	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata", oper,saveParam,{idno:$('#idno').val()},'refreshGrid');
			unsaved = false;
			$("#dialogForm").dialog('close');
		}else{
				mycurrency.formatOn();
		}
	});

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

	
	////////////////////selected///////////////

	$('#apacthdr_ttype').on('change', function() {
		let ttype1 = $("#apacthdr_ttype option:selected" ).val();
	
		if(ttype1 == 'Supplier' || ttype1 == 'Others') {
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("IN");
			
		}else if(ttype1 == 'Debit_Note') {
			$("#formdata :input[name='apacthdr_source']").val("AP");
			$("#formdata :input[name='apacthdr_trantype']").val("DN");
		}
		
		if(($("#apacthdr_ttype option:selected" ).text()=='Supplier')){
			$('#save').hide();
			$('#ap_parent').show();
		}else {
			$('#save').show();
			$('#ap_parent').hide();
		}
	});
	
	
	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq").click(function(){
		saveParam.oper = $(this).data('oper');
		let obj={
			auditno:selrowData('#jqGrid').apacthdr_auditno,
			_token:$('#_token').val(),
		};
		$.post(saveParam.url+"?" + $.param(saveParam),obj,function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	///////////check postdate & docdate///////////////////
	$("#apacthdr_recdate,#apacthdr_actdate").blur(checkdate);

	function checkdate(nkreturn=false){
		var apacthdr_recdate = $('#apacthdr_recdate').val();
		var apacthdr_actdate = $('#apacthdr_actdate').val();

		$(".noti ol").empty();
		var failmsg=[];

		if(moment(apacthdr_recdate).isBefore(apacthdr_actdate)){
			failmsg.push("Post Date cannot be lower than Document date");
		}

		if(failmsg.length){
			failmsg.forEach(function(element){
				$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
			});
			if(nkreturn)return false;
		}else{
			if(nkreturn)return true;
		}

	}
	//////////////////////////////////////////////////////

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj,needrefresh){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {

			unsaved = false;
			hideatdialogForm(false);
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#apacthdr_auditno,#auditno').val(data.auditno);
				$('#idno').val(data.idno);
				// $('#apacthdr_outamount').val(data.outamount);//just save idno for edit later
				
				urlParam2.filterVal[1]=data.auditno;
			}else if(selfoper=='edit'){
				urlParam2.filterVal[1]=$('#apacthdr_auditno').val();
				//doesnt need to do anything
			}
			disableForm('#formdata');

			if(needrefresh === 'refreshGrid'){
				refreshGrid("#jqGrid", urlParam);
			}
			
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	////////////////////////////populate data for dropdown search By////////////////////////////
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

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source'],
		filterVal:['session.compcode', '', '<>.DELETE', 'AP']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "/invoiceAPDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:true}, //canSearch: true, checked: true},
			{ label: 'Delivery Order Number', name: 'document', width: 200, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
					{ custom_element:documentCustomEdit,
					custom_value:galGridCustomValue },
			},
			{ label: 'Purchase Order Number', name: 'reference', width: 200, edittype:'text', classes: 'wrap',  
				editable:true,
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount', name: 'amount', width: 100, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
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
			{ label: 'Tax Claim', name: 'GSTCode', width: 200, edittype:'text', hidden:true, classes: 'wrap',  
				editable:true,
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Tax Amount', name: 'AmtB4GST', width: 100, classes: 'wrap', hidden:true, 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
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
			{ label: 'Record No', name: 'dorecno', width: 100, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",
			},
			{ label: 'GRN No', name: 'grnno', width: 100, classes: 'wrap', editable: true,editoptions:{readonly: "readonly"},
				edittype:"text",
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
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
		},
		gridComplete: function(){
			
		
			fdl.set_array().reset();
			
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_supplier.check(errorField);
			dialog_payto.check(errorField);
			dialog_category.check(errorField);
			dialog_department.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	
	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {

        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

        	$("input[name='document']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
        	$('#apacthdr_outamount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {

        	//if(errorField.length>0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "/invoiceAPDetail/form?"+
				$.param({
					action: 'invoiceAPDetail_save',
					auditno:$('#apacthdr_auditno').val(),
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			hideatdialogForm(false);
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
				    			action: 'invoiceAPDetail_save',
								auditno: $('#apacthdr_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,

				    		}
				    		$.post( "/invoiceAPDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
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
			/*mycurrency2.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
		    for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);
		    }*/
		   // onall_editfunc();
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
		    for (var i = 0; i < ids.length; i++) {

				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

		    	var obj = 
		    	{
		    		'lineno_' : ids[i],
		    		'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),
		    		'reference' : data.reference,
		    		'amount' : data.amount,
		    		'dorecno' : data.dorecno,
		    		'grnno' : data.grnno,
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invoiceAPDetail_save',
				_token: $("#_token").val(),
				auditno: $('#apacthdr_auditno').val()
    		}

    		$.post( "/invoiceAPDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function(data){
				// $('#amount').val(data);
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
		caption:"Detail",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'document':field=['delordno','srcdocno'];table="material.delordhd";case_='uomcode';break;
		}
		var param={action:'input_check',url:'/util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('invoiceAP',options,param,case_,cellvalue);
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Delivery Order Number':temp=$('#document');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function documentCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="document" name="document" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	///////////Validation for document number////////////////////////////////////////////////////////
	
	$("#apacthdr_document").blur(function(){
		if(oper == 'add'){
			var id = "#apacthdr_document";
			var param={
				func:'getDocNo',
				action:'get_value_default',
				url: '/util/get_value_default',
				field:['document'],
				table_name:'finance.apacthdr'
			}

			param.filterCol = ['document'];
			param.filterVal = [$("#apacthdr_document").val()];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id,errorField)!==-1){
						errorField.splice($.inArray(id,errorField), 1);
					}
					$( id ).removeClass( "error" ).addClass( "valid" );
				} else {
					bootbox.alert("Duplicate Document No");
					$( id ).removeClass( "valid" ).addClass( "error" );
					if($.inArray(id,errorField)===-1){
						errorField.push( id );
					}
				}
			});
		}
	});
	
	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		
			dialog_supplier.off();
			dialog_payto.off();
			dialog_category.off();
			dialog_department.off();
			saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()});
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
	});

	// function saveDetailLabel(callback=null){
	// 	mycurrency.formatOff();
	// 	mycurrency.check0value(errorField);
		
	// 	unsaved = false;
	// 	if( checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {

	// 		dialog_supplier.off();
	// 		dialog_payto.off();
	// 		dialog_category.off();
	// 		dialog_department.off();
	// 		saveHeader("#formdata",oper,saveParam);

	// 		errorField.length=0;
	// 	}else{
	// 		mycurrency.formatOn();
	// 	}
	// 	if(callback!=null)callback();
	// }

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_supplier.on();
		dialog_payto.on();
		dialog_category.on();
		dialog_department.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){

		unsaved = false;
		$("#jqGridPager2Delete,#saveHeaderLabel").hide();
		dialog_document.on();//start binding event on jqgrid2

		$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
			
		});

	});	

	/*function onall_editfunc(){
		
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		
		
		
	}
*/
	
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
		gridComplete: function(){
			
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	////////////////////object for dialog handler///////////////////
	var dialog_supplier = new ordialog(
		'supplier','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_supplier.gridname);
				$("#apacthdr_payto").val(data['SuppCode']);
				$('#apacthdr_recdate').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#apacthdr_recdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_supplier.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_supplier.urlParam.filterVal=['A', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_supplier.makedialog(true);

	var dialog_payto = new ordialog(
		'payto','material.supplier','#apacthdr_payto',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},
			ondblClickRow:function(){
				$('#apacthdr_actdate').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#apacthdr_actdate').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_payto.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payto.urlParam.filterVal=['A', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_payto.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category','#apacthdr_category',errorField,
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			urlParam: {
					open: function(){
						if (($('#apacthdr_ttype').val()=="Supplier")) {
							filterCol:['recstatus', 'compcode', 'source', 'povalidate'];
							filterVal:['A', 'session.compcode', 'CR', '1'];
						} else {
							filterCol:['recstatus', 'compcode', 'source', 'povalidate'];
							filterVal:['A', 'session.compcode', 'CR', '0'];
						}	
					}	
			},
		
			ondblClickRow:function(){
				$('#apacthdr_deptcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_deptcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{	
			title:"Select Category Code",
			open: function(){
					if (($('#apacthdr_ttype').val()=="Supplier")) {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', 'session.compcode', 'CR', '1'];
					}else {
						dialog_category.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
						dialog_category.urlParam.filterVal=['A', 'session.compcode', 'CR', '0'];
					}
				}

			},'urlParam','radio','tab'
		
	);
	dialog_category.makedialog(true);

	var dialog_department = new ordialog(
		'department','sysdb.department','#apacthdr_deptcode',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','A']
					},
			ondblClickRow:function(){
				$('#apacthdr_remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_remarks').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Department Code",
			open: function(){
				dialog_department.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_department.urlParam.filterVal=['A', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_department.makedialog(true);

	var dialog_document = new ordialog(
		'document',['material.delordhd'],"#jqGrid2 input[name='document']", errorField,
		{	colModel:[
				{label:'DO No',name:'delordno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'GRN No',name:'docno',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Delivery Date',name:'deliverydate',width:400,classes:'pointer', formatter: dateFormatter, unformat: dateUNFormatter },
				{label:'PO No',name:'srcdocno',width:400,classes:'pointer', hidden:false},
				{label:'Amount',name:'amount',width:400,classes:'pointer',formatter: 'currency'},
				{label:'tax claim',name:'taxclaimable',width:400,classes:'pointer', hidden:true},
				{label:'tax amount',name:'TaxAmt',width:400,classes:'pointer', hidden:true},
				{label:'record no',name:'recno',width:400,classes:'pointer', hidden:true},
				{label:'suppcode',name:'suppcode',width:400,classes:'pointer', hidden:true},

			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
					},

			ondblClickRow: function () {
				let data = selrowData('#' + dialog_document.gridname);
				$("#jqGrid2 input[name='document']").val(data['delordno']);
				$("#jqGrid2 input[name='reference']").val(data['srcdocno']);
				$("#jqGrid2 input[name='amount']").val(data['amount']);
				$("#jqGrid2 input[name='GSTCode']").val(data['taxclaimable']);
				$("#jqGrid2 input[name='AmtB4GST']").val(data['TaxAmt']);
				$("#jqGrid2 input[name='dorecno']").val(data['recno']);
				$("#jqGrid2 input[name='grnno']").val(data['docno']);
				$("#jqGrid2 input[name='entrydate']").val(data['deliverydate']);

				addmore_jqgrid2.state = true;
				$('#jqGrid2_ilsave').click();

			}
		},{
			title:"Select DO No",
			open: function(){
				dialog_document.urlParam.url = "/invoiceAP/table";
				dialog_document.urlParam.suppcode =  $("#apacthdr_suppcode").val();

			}
		},'none'
	);
	dialog_document.makedialog(false);

	var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent();

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

});
