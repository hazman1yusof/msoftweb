
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	$('body').click(function(){
		$('#error_infront').text('');
	});
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

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#amount']);
	var radbuts=new checkradiobutton(['delordhd_taxclaimable']);
	var fdl = new faster_detail_load();
	var cbselect = new checkbox_selection("#jqGrid","Checkbox","delordhd_idno","delordhd_recstatus");

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper;
	var unsaved = false;

	$("#dialogForm")
	  .dialog({ 
		width: 9.5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
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
					$("#delordhd_deldept").val($("#deptcode").val());
					dialog_deldept.check(errorField);
					$('#delordhd_trantime').val(moment().format('HH:mm:ss'));
					$("input[type=radio][name='delordhd_taxclaimable'][value='NON-CLAIMABLE']").prop("checked",true);
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					$('#delordhd_deldept,#delordhd_hdrtype,#delordhd_debtorcode,#pm_newmrn,#delordhd_paymode').prop("readonly",true);
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					break;
			}if(oper!='add'){
				// dialog_paymodeAR.check(errorField);
				dialog_CustomerSO.check(errorField);
				dialog_deldept.check(errorField);
				dialog_mrn.check(errorField);
				dialog_billtypeSO.check(errorField);
			}if(oper=='add'){
				// dialog_paymodeAR.on();
				dialog_CustomerSO.on();
				dialog_deldept.on();
				dialog_mrn.on();
				dialog_billtypeSO.on();
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
		},
		close: function( event, ui ) {
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;//reset balik
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			// $('.alert').detach();
			$('.my-alert').detach();
			// $("#formdata a").off();
			// dialog_paymodeAR.off();
			dialog_CustomerSO.off();
			dialog_deldept.off();
			dialog_mrn.off();
			dialog_billtypeSO.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
			radbuts.reset();
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	// var backdated = new func_backdated('#delordhd_prdept');
	// backdated.getdata();

	// function func_backdated(target){
	// 	this.sequence_data;
	// 	this.target=target;
	// 	this.param={
	// 		action:'get_value_default',
	// 		url:"util/get_value_default",
	// 		field: ['*'],
	// 		table_name:'material.sequence',
	// 		table_id:'idno',
	// 		filterCol:['trantype'],
	// 		filterVal:['GRT'],
	// 	}

	// 	this.getdata = function(){
	// 		var self=this;
	// 		$.get( this.param.url+"?"+$.param(this.param), function( data ) {
				
	// 		},'json').done(function(data) {
	// 			if(!$.isEmptyObject(data.rows)){
	// 				self.sequence_data = data.rows;
	// 			}
	// 		});
	// 		return this;
	// 	}

	// 	this.set_backdate = function(dept){
	// 		$.each(this.sequence_data, function( index, value ) {
	// 			if(value.dept == dept){
	// 				var backday =  value.backday;
	// 				console.log('backday: '+backday);
	// 				var backdate = moment().subtract(backday, 'days').format('YYYY-MM-DD');
	// 				console.log(backdate);
	// 				$('#delordhd_trandate').attr('min',backdate);
	// 			}
	// 		});
	// 	}
	// }

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
		if($("#recstatus_use").val() == 'POSTED'){
			recstatus_filter = [['OPEN','POSTED']];
			filterCol_urlParam = ['delordhd.compcode'];
			filterVal_urlParam = ['session.compcode'];
		}

	var urlParam={
		action:'get_table_default',
		url:'./goodReturnCredit/table',
		field:'',
		fixPost:'true',
		table_name:['material.delordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.SuppCode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['delordhd.compcode','delordhd.trantype'],
		filterVal:['session.compcode','GRC'],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'goodReturn_save',
		url:'./goodReturnCredit/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'material.delordhd',
		table_id:'delordhd_recno',
		checkduplicate:'true'
	};
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
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

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'delordhd_recno', width: 12, classes: 'wrap', canSearch: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', hidden:true},
			{ label: 'Store Department', name: 'delordhd_deldept', width: 18, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 18, hidden: true, classes: 'wrap' },
			{ label: 'GRT No', name: 'delordhd_docno', width: 15, classes: 'wrap', canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'CN No', name: 'delordhd_cnno', width: 15, classes: 'wrap', canSearch: true, align: 'right'},
			{ label: 'Date', name: 'delordhd_trandate', width: 20, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'HUKM MRN', name: 'pm_newmrn', width: 25, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'mrn', name: 'delordhd_mrn', hidden:true},
			{ label: 'Customer', name: 'delordhd_debtorcode', width: 25, classes: 'wrap', canSearch: true},
			{ label: 'DO No', name: 'delordhd_delordno', hidden:true},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 20, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 20, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 20},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 25, classes: 'wrap',hidden:true},
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 25, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deliverydate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:'true'},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:'true'},
			{ label: 'postedby', name: 'delordhd_postedby', width: 40, hidden:'true'},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:'true'},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:'true'},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:'true'},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:'true'},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:'true'},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:'true'},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:'true'},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:'true'},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:'true'},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:'true'},
			{ label: 'postdate', name: 'delordhd_postdate', width: 40, hidden:'true'},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:'true'},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:'true'},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:'true'},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:'true'},
			{ label: 'cancelby', name: 'delordhd_cancelby', width: 40, hidden:'true'},
			{ label: 'canceldate', name: 'delordhd_canceldate', width: 40, hidden:'true'},
			{ label: 'reopenby', name: 'delordhd_reopenby', width: 40, hidden:'true'},
			{ label: 'reopendate', name: 'delordhd_reopendate', width: 40, hidden:'true'},
			{ label: 'unit', name: 'delordhd_unit', width: 40, hidden:true},
			{ label: 'hdrtype', name: 'delordhd_hdrtype', width: 40, hidden:true},
			{ label: 'paymode', name: 'delordhd_paymode', width: 40, hidden:true},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'delordhd_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			/*switch($("#scope").val()){
				case "dataentry":
					$("label[for=delordhd_reqdept]").hide();
					$("#delordhd_reqdept_parent").hide();
					$("#delordhd_reqdept").removeAttr('required');	
				break;
				case "cancel": 
					if(stat=='POSTED'){
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
				break;
				case "all": 
					if(stat=='POSTED'){
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
					}
				break;
			}*/

			let scope = $("#recstatus_use").val();

			if (stat == scope) {
				$('#but_reopen_jq').show();
				$('#but_post_single_jq,#but_cancel_jq').hide();
			} else if (stat == "CANCELLED") {
				$('#but_reopen_jq').show();
				$('#but_post_single_jq,#but_cancel_jq').hide();
			} else {
				if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0){
					$('#but_cancel_jq,#but_post_single_jq').show();
				}
				$('#but_reopen_jq').hide();
			}

			urlParam2.idno=selrowData("#jqGrid").delordhd_idno;
			refreshGrid("#jqGrid3",urlParam2);
			populate_form(selrowData("#jqGrid"));

			$("#pdfgen1").attr('href','./goodReturnCredit/showpdf?recno='+selrowData("#jqGrid").delordhd_recno);

			$("#pdfgen2").attr('href','./goodReturnCredit/showpdf?recno='+selrowData("#jqGrid").delordhd_recno);

			$('#recnodepan').text(selrowData("#jqGrid").delordhd_recno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").delordhd_deldept);

			// refreshGrid("#jqGrid3",urlParam2);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			if(stat=='OPEN'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			populate_form(selrowData("#jqGrid"));
			//empty_form();

			fdl.set_array().reset();
			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();

		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},
	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

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
			$('#delordhd_docno').val(padzero($('#delordhd_docno').val()));
			$('#delordhd_srcdocno').val(padzero($('#delordhd_srcdocno').val()));
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			if(stat=='OPEN'){
				oper='edit';
				selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
				populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
				$('#delordhd_docno').val(padzero($('#delordhd_docno').val()));
				$('#delordhd_srcdocno').val(padzero($('#delordhd_srcdocno').val()));
				refreshGrid("#jqGrid2",urlParam2);
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
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
	addParamField('#jqGrid',false,urlParam);
	addParamField('#jqGrid',false,saveParam,['delordhd_trantype','delordhd_recno','delordhd_docno','delordhd_adduser','delordhd_adddate','delordhd_upduser','delordhd_upddate','delordhd_deluser','delordhd_idno','supplier_name','delordhd_recstatus','delordhd_unit','Refresh', 'Checkbox','do2_srcdocno']);

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
	$("#but_post_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './goodReturnCredit/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
			$(self_).attr('disabled',false);
		}).success(function(data){
			
		});
	});
	

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;
		$('#delordhd_docno').val(unpadzero($('#delordhd_docno').val()));
		// $('#delordhd_srcdocno').val(unpadzero($('#delordhd_srcdocno').val()));

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
		
		},'json').fail(function (data) {
			$("#saveDetailLabel").attr('disabled',false);
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
				$('#delordhd_recno').val(data.recno);
				$('#delordhd_docno').val(data.docno);
				$('#delordhd_idno').val(data.idno);//just save idno for edit later
				$('#delordhd_totamount').val(data.totalAmount);

				urlParam2.idno=data.idno; 
			}else if(selfoper=='edit'){
				//doesnt need to do anything
			}
			disableForm('#formdata');
			$("#saveDetailLabel").attr('disabled',false);
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
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
				searchChange();
			}
		});
	}

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if($('#Scol').val()=='delordhd_trandate'){
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

				urlParam.searchCol=["delordhd_suppcode"];
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
			title: "Select Purchase Department",
			open: function () {
				supplierkatdepan.urlParam.filterCol = ['recstatus'];
				supplierkatdepan.urlParam.filterVal = ['ACTIVE'];
			}
		}
	);
	supplierkatdepan.makedialog();
	
	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}
	
	function searchChange(){
		var arrtemp = ['session.compcode',  $('#Status option:selected').val(), $('#trandept option:selected').val(),'GRC'];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['delordhd.compcode','delordhd.recstatus', 'delordhd.prdept','delordhd.trantype'],fv:[],fc:[]});//tukar kat sini utk searching purreqhd.compcode','purreqhd.recstatus','purreqhd.prdept'

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default_dtl',
		url:'./goodReturnCredit/table',
		idno:null,
		// field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode', 'dodt.pouom', 'dodt.suppcode','dodt.trandate',
		// 'dodt.deldept','dodt.deliverydate','dodt.qtydelivered','dodt.qtyreturned','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 
		// 'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks','t.rate',],
		// table_name:['material.delorddt AS dodt','material.productmaster AS p','hisdb.taxmast AS t'],
		// table_id:'lineno_',
		// join_type:['LEFT JOIN','LEFT JOIN'],
		// join_onCol:['dodt.itemcode','dodt.taxcode'],
		// join_onVal:['p.itemcode','t.taxcode'],
		// filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		// filterVal:['','session.compcode','<>.DELETE']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./goodReturnCreditDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'idno', name: 'idno', width: 20, key: true, hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', hidden:true},
			// { label: 'Price Code', name: 'pricecode', width: 80, classes: 'wrap', editable:true,
			// 		editrules:{required: true,custom:true, custom_func:cust_rules},
			// 			edittype:'custom',	editoptions:
			// 			    {  custom_element:pricecodeCustomEdit,
			// 			       custom_value:galGridCustomValue 	
			// 			    },
			// },
			{ label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:false},
			{ label: 'UOM Code', name: 'uomcode', width: 120, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},

			{ label: 'Quantity Returned', name: 'qtyreturned', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer',
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

			{ label: 'Unit Price', name: 'unitprice', width: 100, align: 'right', classes: 'wrap', editable:true,
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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

			{ label: 'Tax Code', name: 'taxcode', width: 130, classes: 'wrap', editable:true, editoptions: { readonly: "readonly" }},
			{ label: 'Percentage Discount (%)', name: 'perdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4},
					editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Discount Per Unit', name: 'amtdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
					editrules:{required: true},edittype:"text",
						editoptions:{readonly: "readonly",
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
			{ label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},edittype:"text",
						editoptions:{ readonly: "readonly",
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
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total Line Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{decimalPlaces: 2, thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Expiry Date', name: 'expdate', width: 100, classes: 'wrap', hidden:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: { readonly: "readonly",
                    dataInit: function (element) {
                        $(element).datepicker({
                            id: 'expdate_datePicker',
                            dateFormat: 'dd/mm/yy',
                            minDate: 1,
                            showOn: 'focus',
                            changeMonth: true,
		  					changeYear: true,
                        });
                    }
                }
			},
			{ label: 'Batch No', name: 'batchno', width: 70, classes: 'wrap', hidden:true, editoptions: { readonly: "readonly" },
					maxlength: 30,
			},
		
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden:true},
			{ label: 'rate', name: 'rate', width: 60, classes: 'wrap',hidden:true},
		],
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 1000000,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){
				$('#jqGrid2_iladd').click();
			}else{
				// $('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		    $("#expdate").datepicker();
		},
		beforeSubmit: function(postdata, rowid){ 
			dialog_itemcode.check(errorField);
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				let rowid = $('#remarks2').data('rowid');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks:newval});
				$(this).dialog('close');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

	var butt2_rem = 
		[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			let rowid = $('#remarks2').data('rowid');
			let grid = $('#remarks2').data('grid');
			$('#remarks2').val($(grid).jqGrid('getRowData', rowid).remarks);
			let exist = $("#jqGrid2 #"+rowid+"_pouom_convfactor_uom").length;
			if(grid == '#jqGrid3' || exist==0){ // lepas ni letak or not edit mode
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt1_rem);
			}
		},
		buttons : butt2_rem
	});

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {
			calc_jq_height_onchange("jqGrid2");
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

        	unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='totamount']"]);
			
			$("input[name='gstpercent']").val($("#jqGrid2 input[name='rate']").val())//reset gst to 0
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor

			// dialog_pricecode.on();//start binding event on jqgrid2
			dialog_chggroup.on();
			dialog_uomcode.on();
			dialog_pouom.on();
			dialog_taxcode.on();

			$("#jqGrid2 input[name='qtyreturned'], #jqGrid2 input[name='itemcode'], #jqGrid2 input[name='pouom'], #jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='tot_gst'], #jqGrid2 input[name='totamount']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

			// $("#jqGrid2 input[name='qtyreturned']").on('blur',calculate_conversion_factor);

			$("input[name='unitprice']").keydown(function(e) {//when click tab at batchno, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});

        	cari_gstpercent(rowid);
        },
        aftersavefunc: function (rowid, response, options) {
			$('#delordhd_totamount').val(response.responseText);
			$('#delordhd_subamount').val(response.responseText);
	    	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {
        	console.log(errorField)
        	if(errorField.length>0)return false;

        	mycurrency2.formatOff();
        	let qtyreturned = $("#jqGrid2 #"+rowid+"_qtyreturned").val();
        	if(qtyreturned==0)return false;
        	if(qtyreturned=='')return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);

			let editurl = "./goodReturnCreditDetail/form?"+
				$.param({
					action: 'goodReturnDetail_save',
					docno:$('#delordhd_docno').val(),
					recno:$('#delordhd_recno').val(),
					suppcode:$('#delordhd_suppcode').val(),
					trandate:$('#delordhd_trandate').val(),
					deldept:$('#delordhd_deldept').val(),
					doidno:$('#delordhd_idno').val(),
					deliverydate:$('#delordhd_deliverydate').val(),
					remarks:data.remarks,
					amount:data.amount,
					netunitprice:data.netunitprice,//bug will happen later because we use selected row
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
								action: 'goodReturnDetail_save',
								doidno: $('#delordhd_idno').val(),
								idno: selrowData('#jqGrid2').idno,
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post( "./goodReturnCreditDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, 
							function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#delordhd_totamount').val(data);
								$('#delordhd_subamount').val(data);

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
		var field,table,case_;
		switch(options.colModel.name){
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'pouom': field = ['uomcode', 'description']; table = "material.uom";case_='pouom';break;
			case 'pricecode':field=['pricecode','description'];table="material.pricesource";case_='pricecode';break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
			case 'delordhd_prdept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_prdept';break;
			case 'delordhd_deldept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_deldept';break;
			case 'delordhd_reqdept':field=['deptcode','description'];table="sysdb.department";case_='delordhd_reqdept';break;
			case 'delordhd_suppcode':field=['suppcode','name'];table="material.supplier";case_='delordhd_suppcode';break;
			case 'h_suppcode':field=['SuppCode','Name'];table="material.supplier";case_='h_suppcode';break;
			case 'h_prdept':field=['deptcode','description'];table="sysdb.department";case_='h_prdept';break;
			case 'pm_newmrn': field = ['newmrn','name'];table = "hisdb.pat_mast";case_ = 'mrn';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		fdl.get_array('goodReturnCredit',options,param,case_,cellvalue);
		delay(function(){
			calc_jq_height_onchange(options.gid);
		}, 500 );
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Item Code':temp=$('#itemcode');break;
			case 'UOM Code':temp=$('#uomcode');break;
			case 'PO UOM': temp = $('#pouom'); break;
			case 'Price Code':temp=$('#pricecode');break;
			case 'Tax Code':temp=$('#taxcode');break;
			case 'Quantity Returned': temp = $("#jqGrid2 input[name='qtyreturned']"); 
				$("#jqGrid2 input[name='qtyreturned']").hasClass("error");
				break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function taxcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="`+val+`" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
			</div>
			<span class="help-block"></span>
				<div class="input-group">
					<input id="`+opt.id+`_gstpercent" name="gstpercent" type="hidden">
					<input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`>
					<input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`>
				</div>
			`);
	}

	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;
		if(options.gid == "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
		}else if(options.gid != "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}


	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){ //actually saving the header
		$("#saveDetailLabel").attr('disabled',true);
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		// dialog_paymodeAR.off();
		dialog_CustomerSO.off();
		dialog_deldept.off();
		dialog_mrn.off();
		errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		}else{
			$("#saveDetailLabel").attr('disabled',false);
			mycurrency.formatOn();
			// dialog_paymodeAR.on();
			dialog_CustomerSO.on();
			dialog_deldept.on();
			dialog_mrn.on();

		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		// dialog_paymodeAR.on();
		dialog_CustomerSO.on();
		dialog_deldept.on();
		dialog_mrn.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$('#delordhd_deldept,#delordhd_hdrtype,#delordhd_debtorcode,#pm_newmrn,#delordhd_paymode').prop("readonly",true);
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////

	/////////////calculate conv fac//////////////////////////////////
	function calculate_conversion_factor(event) {
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		var id="#jqGrid2 #"+id_optid+"_qtyreturned"
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode"
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#jqGrid2 #"+id_optid+"_taxcode_convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#jqGrid2 #"+id_optid+"_taxcode_convfactor_pouom").val());
		let qtyreturned = parseFloat($("#jqGrid2 #"+id_optid+"_qtyreturned").val());

		var balconv = convfactor_pouom*qtyreturned%convfactor_uom;

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
	///////////////////////////////////////////////////////////////////////////////

	/*//////////////////////////////calculate qtyreturned /////////////////////
	function calculate_quantity_returned(event){
        let qtyreturned = parseFloat($("#jqGrid2 input[name='qtyreturned']").val());
        let qtydelivered = parseFloat($("#jqGrid2 input[name='qtydelivered']").val());

        var qtyreturn = (qtydelivered - qtyreturned);

        $("input[name='qtyreturned']").val(qtyreturn);

        console.log(qtyreturn);
	}*/
	///////////////////////////////////////////////////////////////////////////////
	function onall_editfunc(){
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtyreturned'], #jqGrid2 input[name='itemcode'], #jqGrid2 input[name='pouom'], #jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc'], #jqGrid2 input[name='tot_gst'], #jqGrid2 input[name='totamount']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

		// $("#jqGrid2 input[name='qtyreturned']").on('blur',calculate_conversion_factor);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////

	var mycurrency2 =new currencymode([]);
	function calculate_line_totgst_and_totamt(event){
		mycurrency2.formatOff();

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let qtyreturned = parseFloat($("#jqGrid2 #"+id_optid+"_qtyreturned").val());
		let unitprice = parseFloat($("#jqGrid2 #"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#jqGrid2 #"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#jqGrid2 #"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());
		let qtydelivered = parseFloat($("#jqGrid2 #"+id_optid+"_qtydelivered").val());

		var totamtperUnit = ((unitprice*qtyreturned) - (amtdisc*qtyreturned));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		if(isNaN(tot_gst))tot_gst = 0;
		
		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?

		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});

		mycurrency2.formatOn();

	}

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 10000,
		sortname: 'qtyreturned',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){
			/*data.rows.forEach(function(element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});*/
			// if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			// else{
			// 	$('#jqGrid2').jqGrid ('setSelection', "1");
			// }


			// addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			calc_jq_height_onchange("jqGrid3");
		},
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGrid3");


	$("#jqGrid3_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#jqGrid3_panel",100);
		refreshGrid("#jqGrid3",urlParam2);
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
		calc_jq_height_onchange("jqGrid3");
	});



	////////////////////////////////////////////////////ordialog////////////////////////////////////////

	var dialog_deldept = new ordialog(
		'deldept','sysdb.department','#delordhd_deldept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['chgdept','storedept','compcode','recstatus'],
						filterVal:['1','1','session.compcode','ACTIVE']
					},
					ondblClickRow: function () {
						$('#delordhd_credcode').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#delordhd_credcode').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Receiver Department",
			open: function(){
				dialog_deldept.urlParam.filterCol=['chgdept','storedept', 'recstatus','compcode'];
				dialog_deldept.urlParam.filterVal=['1','1', 'ACTIVE', 'session.compcode'];
			},
			close: function(obj_){
				$("#delordhd_customer").focus().select();
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_deldept.makedialog();

	var dialog_CustomerSO = new ordialog(
		'customer', 'debtor.debtormast', '#delordhd_debtorcode', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label: 'Bill Type', name: 'billtypeop', width: 100, classes: 'pointer'},
				{ label: 'idno', name: 'idno',hidden:true},
				{ label: 'name', name: 'name',hidden:true},
				{ label: 'debtortype', name: 'debtortype',hidden:true},
				{ label: 'address1', name: 'address1',hidden:true},
				{ label: 'address2', name: 'address2',hidden:true},
				{ label: 'address3', name: 'address3',hidden:true},
				{ label: 'postcode', name: 'postcode',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_CustomerSO.gridname);
				$('#delordhd_hdrtype').val(data['billtypeop']);
				dialog_billtypeSO.check(errorField);
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
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerSO.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerSO.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			},
			close: function(obj_){
				$("#pm_newmrn").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog();

	var dialog_billtypeSO = new ordialog(
		'billtype', 'hisdb.billtymst', '#delordhd_hdrtype', errorField,
		{
			colModel: [
				{ label: 'Bill type', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label: 'Bill type', name: 'price', width: 200, classes: 'pointer' },
				{ label: 'Effective Date<br/>From', name: 'effdatefrom',formatter: dateFormatter, unformat: dateUNFormatter, width: 150, classes: 'pointer' },
				{ label: 'Effective Date<br/>To', name: 'effdateto',formatter: dateFormatter, unformat: dateUNFormatter, width: 150, classes: 'pointer' },
			],
			urlParam: {
				url:"./SalesOrder/table",
				action: 'get_hdrtype',
				url_chk: "./SalesOrder/table",
				action_chk: "get_hdrtype_check",
				filterCol:[],
				filterVal:[],
			},
			// urlParam: {
			// 	filterCol:['compcode','recstatus','opprice'],
			// 	filterVal:['session.compcode','ACTIVE','1']
			// },
			ondblClickRow: function () {
				// let data = selrowData('#' + dialog_CustomerSO.gridname);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#db_mrn').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Billtype",
			open: function(){
				dialog_billtypeSO.urlParam.url = "./SalesOrder/table";
				dialog_billtypeSO.urlParam.action = 'get_hdrtype';
				dialog_billtypeSO.urlParam.url_chk = "./SalesOrder/table";
				dialog_billtypeSO.urlParam.action_chk = "get_hdrtype_check";
				dialog_billtypeSO.urlParam.filterCol=[];
				dialog_billtypeSO.urlParam.filterVal=[];
			},
			close: function(obj_){
				$("#pm_newmrn").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_billtypeSO.makedialog();

	var dialog_mrn = new ordialog(
		'dialog_mrn', 'hisdb.pat_mast', '#pm_newmrn', 'errorField',
		{
			colModel: [
				{ label: 'HUKM MRN', name: 'NewMrn', width: 200, classes: 'pointer', canSearch: true},
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
				{ label: 'idno', name: 'idno', hidden:true},
				{ label: 'newmrn', name: 'newmrn', hidden:true},
				{ label: 'address1', name: 'address1', hidden:true},
				{ label: 'address2', name: 'address2', hidden:true},
				{ label: 'address3', name: 'address3', hidden:true},
				{ label: 'postcode', name: 'postcode', hidden:true},
			],
			urlParam: {
				filterCol:['compcode','ACTIVE'],
				filterVal:['session.compcode','1']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$('#delordhd_mrn').val(data.MRN)
				$('#delordhd_remarks').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#db_termdays').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select MRN",
			open: function(){
				dialog_mrn.urlParam.filterCol=['compcode', 'ACTIVE'];
				dialog_mrn.urlParam.filterVal=['session.compcode', '1'];
			},
			close: function(obj_){
				$("#delordhd_paymode").focus().select();
			}
		},'none','radio','tab'
	);
	dialog_mrn.makedialog();

	// var dialog_paymodeAR = new ordialog(
	// 	'delordhd_paymode','debtor.paymode',"#delordhd_paymode",errorField,
	// 	{
	// 		colModel: [
	// 			{ label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true },
	// 			{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
	// 			{ label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true },
	// 		],
	// 		urlParam: {
	// 			filterCol:['compcode','recstatus', 'source', 'paytype'],
	// 			filterVal:['session.compcode','ACTIVE', 'AR', 'Credit Note']
	// 		},
	// 		ondblClickRow:function(){
	// 			$('#db_remark').focus();
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$('#db_remark').focus();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	},{
	// 		title:"Select Paymode",
	// 		open: function(){
	// 			dialog_paymodeAR.urlParam.filterCol=['compcode','recstatus', 'source', 'paytype'],
	// 			dialog_paymodeAR.urlParam.filterVal=['session.compcode','ACTIVE', 'AR', 'Credit Note'];
	// 			},
	// 		close: function(obj_){
	// 			$("#delordhd_remarks").focus().select();
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_paymodeAR.makedialog(true);

	var dialog_chggroup = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label: 'Charge Code',name:'chgcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'description',width:220,classes:'pointer',canSearch:true,checked:true,or_search:true},
                {label: 'Generic',name:'generic',width:220,classes:'pointer',canSearch:true},
				{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
				{label: 'UOM',name:'uom',width:100,classes:'pointer',},
				{label: 'Quantity On Hand',name:'qtyonhand',width:100,classes:'pointer',},
				{label: 'Price',name:'price',width:100,classes:'pointer'},
				{label: 'Tax',name:'taxcode',width:100,classes:'pointer'},
                {label: 'overwrite',name:'overwrite',hidden:true},
				{label: 'rate',name:'rate',hidden:true},
				{label: 'st_idno',name:'st_idno',hidden:true},
				{label: 'pt_idno',name:'pt_idno',hidden:true},
				{label: 'billty_amount',name:'billty_amount',hidden:true},
				{label: 'billty_percent',name:'billty_percent',hidden:true},
				{label: 'convfactor',name:'convfactor',hidden:true},
				
			],
			urlParam: {
					url:"./SalesOrderDetail/table",
					action: 'get_itemcode_price',
					// url_chk: './SalesOrderDetail/table',
					// action_chk: 'get_itemcode_price_check',
					entrydate : $('#delordhd_trandate').val(),
					billtype : $('#delordhd_hdrtype').val(),
					deptcode : $('#delordhd_deldept').val(),
					price : $('#pricebilltype').val(),
					filterCol:[],
					filterVal:[]
				},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_chggroup.gridname);

				var rowid = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{description:data['description']});
				$("#jqGrid2 #"+id_optid+"_uomcode").val(data['uom']);
				$("#jqGrid2 #"+id_optid+"_unitprice").val(data['price']);
				$("#jqGrid2 #"+id_optid+"_taxcode").val(data['taxcode']);

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='qtyreturned']").focus().select();
				}
			},
			loadComplete:function(data){

			}
		},{
			title:"Select Item For Sales Order",
			open:function(obj_){
				let id_optid = obj_.id_optid;

				dialog_chggroup.urlParam.url = "./SalesOrderDetail/table";
				dialog_chggroup.urlParam.action = 'get_itemcode_price';
				// dialog_chggroup.urlParam.url_chk = "./SalesOrderDetail/table";
				// dialog_chggroup.urlParam.action_chk = "get_itemcode_price_check";
				dialog_chggroup.urlParam.entrydate = $('#delordhd_trandate').val();
				dialog_chggroup.urlParam.chgcode = $("#jqGrid2 #"+id_optid+"_itemcode").val();
				dialog_chggroup.urlParam.uom = $("#jqGrid2 #"+id_optid+"_uomcode").val();
				dialog_chggroup.urlParam.billtype = $('#delordhd_hdrtype').val();
				dialog_chggroup.urlParam.deptcode = $('#delordhd_deldept').val();
				dialog_chggroup.urlParam.price = $('#pricebilltype').val();
				dialog_chggroup.urlParam.filterCol = [];
				dialog_chggroup.urlParam.filterVal = [];
			},
			close: function(obj_){
				$("#jqGrid2 #"+obj_.id_optid+"_itemcode").parent().siblings( ".help-block" ).html("");
				$("#jqGrid2 input[name='qtyreturned']").focus().select();
			},
			after_check:function(data,self,id,fail,errorField){
				$( id ).parent().siblings( ".help-block" ).html('');
			}
		},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
	);
	dialog_chggroup.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom',['material.stockloc AS s','material.uom AS u'],"#jqGrid2 input[name='uomcode']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'u_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' },
				{label:'Department code',name:'s_deptcode',width:150,classes:'pointer'},
				{label:'Item code',name:'s_itemcode',width:150,classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
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
				
				let data=selrowData('#'+dialog_uomcode.gridname);
				if($("input#"+id_optid+"_pricecode").val() == 'MS'){
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}else{
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val(data['u_convfactor']);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(){
				
				// dialog_uomcode.urlParam.fixPost="true";
				// dialog_uomcode.urlParam.table_id="none_";
				// dialog_uomcode.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				// dialog_uomcode.urlParam.filterVal=['session.compcode',$('#delordhd_deldept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#delordhd_trandate').val()).year()];
				// dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
				// dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
				// dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
				// dialog_uomcode.urlParam.join_filterCol=[['s.compcode on =']];
				// dialog_uomcode.urlParam.join_filterVal=[['u.compcode']];
			},
			close: function(){
				// $(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_uomcode.makedialog(false);

	var dialog_pouom = new ordialog(
		'pouom', ['material.uom '], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked:true},
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer' }
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function (event) {
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}

				let data=selrowData('#'+dialog_pouom.gridname);

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val(data['convfactor']);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.compcode', 'ACTIVE'];

			},
			close: function () {
				// $(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		}, 'urlParam','radio','tab'
	);
	dialog_pouom.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
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

				$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_taxcode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_taxcode.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
			close: function(){
				if($('#jqGridPager2SaveAll').css("display") == "none"){
					$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
				}
				
			}
		},'urlParam','radio','tab'
	);
	dialog_taxcode.makedialog(false);

	function cari_gstpercent(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		$("#jqGrid2 #"+id+"_pouom_gstpercent").val(data.rate);
	}

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'delordhd_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			console.log(rowid);
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid_selection");
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
});

function populate_form(obj){
	//panel header
	$('#prdept_show').text(obj.delordhd_deldept);
	$('#grtno_show').text(padzero(obj.delordhd_docno));
	$('#suppcode_show').text(obj.supplier_name);
}

function empty_form(){
	$('#prdept_show').text('');
	$('#grtno_show').text('');
	$('#suppcode_show').text('');
	
}


function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue == '1') {
		return `<span class="fa fa-check"></span>`;
	}else{
		return '';
	}
}

function unformatstatus_tick2(cellvalue, option, rowObject) {
	if ($(rowObject).children('span').attr('class') == 'fa fa-check') {
		return '1';
	}else{
		return '0';
	}
}
