
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var errorField=[];
$(document).ready(function () {
	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
	
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
	var mycurrency =new currencymode(['#delordhd_subamount','#delordhd_totamount', '#delordhd_TaxAmt', '#delordhd_amtdisc']);
	var radbuts=new checkradiobutton(['delordhd_taxclaimable']);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	var myattachment = new attachment_page("deliveryorder","#jqGrid","delordhd_idno");

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#delordhd_trandate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper=null;
	var unsaved = false;
	scrollto_topbtm();
	page_to_view_only($('#viewonly').val());

	// init_focus_header_footer();
	$("#dialogForm")
	  .dialog({ 
		width: 9.5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			$('#delordhd_prdept').focus();
			$('#jqGridPager2EditAll').data('click',false);
			unsaved = false;
			errorField.length=0;
			$("#jqGrid2").jqGrid("setFrozenColumns");
			parent_close_disabled(true);
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					$("#delordhd_prdept").val($("#deptcode").val());
					dialog_prdept.check(errorField);
					$('#delordhd_trantime').val(moment().format('HH:mm:ss'));
					$("input[type=radio][name='delordhd_taxclaimable'][value='NON-CLAIMABLE']").prop("checked",true);
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
				// dialog_authorise.check(errorField);
				dialog_prdept.check(errorField);
				dialog_suppcode.check(errorField);
				dialog_credcode.check(errorField);
				dialog_reqdept.check(errorField);
				dialog_deldept.check(errorField);

			}if(oper!='view'){
				backdated.set_backdate($('#delordhd_prdept').val());
				dialog_authorise.on();
				dialog_prdept.on();
				dialog_suppcode.on();
				dialog_credcode.on();
				dialog_reqdept.on();
				dialog_deldept.on();
				dialog_srcdocno.on();
			}
		},
		beforeClose: function(event, ui){
			if(unsaved){
				event.preventDefault();
				bootbox.confirm("Are you sure want to leave without save?", function(result){
					if (result == true) {
						unsaved = false;
						delete_dd($('#delordhd_idno').val());
						$("#dialogForm").dialog('close');
					}
				});
			}
		},
		close: function( event, ui ) {
			errorField.length=0;
			oper=null;
			addmore_jqgrid2.state = false;
			addmore_jqgrid2.more = false;
			//reset balik
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			$('.my-alert').detach();
			$("#formdata a").off();
			dialog_authorise.off();
			dialog_prdept.off();
			dialog_suppcode.off();
			dialog_credcode.off();
			dialog_reqdept.off();
			dialog_deldept.off();
			dialog_srcdocno.off();
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			//refreshGrid("#jqGrid2",null,"kosongkan");
			radbuts.reset();
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	var backdated = new func_backdated('#delordhd_deldept');
	backdated.getdata();

	function func_backdated(target){
		this.sequence_data;
		this.target=target;
		this.param={
			action:'get_value_default',
			url:"util/get_value_default",
			field: ['*'],
			table_name:'material.sequence',
			table_id:'idno',
			filterCol:['trantype'],
			filterVal:['DO'],
		}

		this.getdata = function(){
			var self=this;
			$.get( this.param.url+"?"+$.param(this.param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					self.sequence_data = data.rows;
				}
			});
			return this;
		}

		this.set_backdate = function(dept){
			$.each(this.sequence_data, function( index, value ) {
				if(value.dept == dept){
					var backday =  value.backday;
					var backdate = moment().subtract(backday, 'days').format('YYYY-MM-DD');
					$('#delordhd_trandate').attr('min',backdate);
				}
			});
		}
	}

	$('#delordhd_trandate').on('change',function(){
		$('#delordhd_deliverydate').val($(this).val());
	});


	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
		if($("#recstatus_use").val() == 'POSTED'){
			recstatus_filter = [['OPEN','POSTED']];
			filterCol_urlParam = ['delordhd.compcode'];
			filterVal_urlParam = ['session.compcode'];
		}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","delordhd_idno","delordhd_recstatus",recstatus_filter[0][0]);
	
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['material.delordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.SuppCode'],
		join_onVal:['delordhd.suppcode'],
		filterCol:['trantype','prdept'],
		filterVal:['GRN', $('#deptcode').val()],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'delOrd_save',
		url:'./deliveryOrder/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'material.delordhd',
		table_id:'delordhd_recno',
		checkduplicate: 'true'
	};
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		if(cellvalue == null){
			return '';
		}else{
			return pad(str, cellvalue, true);
		}
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
			{ label: 'Record No', name: 'delordhd_recno', width: 100, classes: 'wrap', canSearch: true, frozen: true},
			{ label: 'Purchase Department', name: 'delordhd_prdept', width: 170, classes: 'wrap', canSearch:true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Delivery Department', name: 'delordhd_deldept', width: 170, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'DO No', name: 'delordhd_delordno', width: 150, classes: 'wrap', canSearch: true, align: 'right'},
			{ label: 'Request Department', name: 'delordhd_reqdept', width: 170, canSearch: true, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'GRN No', name: 'delordhd_docno', width: 130, classes: 'wrap', canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Received Date', name: 'delordhd_trandate', width: 200, classes: 'wrap', canSearch: true , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Post Date', name: 'delordhd_postdate', width: 180, classes: 'wrap' , formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Supplier Code', name: 'delordhd_suppcode', width: 180, classes: 'wrap', canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Supplier Name', name: 'supplier_name', width: 250, classes: 'wrap', canSearch: false, hidden:true },
			{ label: 'Purchase Order No', name: 'delordhd_srcdocno', width: 150, classes: 'wrap', canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Invoice No', name: 'delordhd_invoiceno', width: 200, classes: 'wrap', canSearch: true},
			{ label: 'Trantype', name: 'delordhd_trantype', width: 200, classes: 'wrap', hidden: true},
			{ label: 'Total Amount', name: 'delordhd_totamount', width: 200, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'delordhd_recstatus', width: 200},
			{ label: ' ', name: 'Refresh', width: 120,formatter: formatterRefresh,unformat: unformatRemarks, hidden: true},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 120,align: "center", formatter: formatterCheckbox },		        
			{ label: 'Sub Amount', name: 'delordhd_subamount', width: 50, classes: 'wrap', hidden:true, align: 'right', formatter: 'currency' },
			{ label: 'Amount Discount', name: 'delordhd_amtdisc', width: 250, classes: 'wrap', hidden:true},
			{ label: 'perdisc', name: 'delordhd_perdisc', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Delivery Date', name: 'delordhd_deliverydate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Time', name: 'delordhd_trantime', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'respersonid', name: 'delordhd_respersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'checkpersonid', name: 'delordhd_checkpersonid', width: 40, hidden:true},
			{ label: 'checkdate', name: 'delordhd_checkdate', width: 40, hidden:true},
			{ label: 'postedby', name: 'delordhd_postedby', width: 40, hidden:true},
			{ label: 'Remarks', name: 'delordhd_remarks', width: 40, hidden:true},
			{ label: 'adduser', name: 'delordhd_adduser', width: 40, hidden:true},
			{ label: 'adddate', name: 'delordhd_adddate', width: 40, hidden:true},
			{ label: 'upduser', name: 'delordhd_upduser', width: 40, hidden:true},
			{ label: 'upddate', name: 'delordhd_upddate', width: 40, hidden:true},
			{ label: 'reason', name: 'delordhd_reason', width: 40, hidden:true},
			{ label: 'rtnflg', name: 'delordhd_rtnflg', width: 40, hidden:true},
			{ label: 'credcode', name: 'delordhd_credcode', width: 40, hidden:true},
			{ label: 'impflg', name: 'delordhd_impflg', width: 40, hidden:true},
			{ label: 'allocdate', name: 'delordhd_allocdate', width: 40, hidden:true},
			{ label: 'deluser', name: 'delordhd_deluser', width: 40, hidden:true},
			{ label: 'idno', name: 'delordhd_idno', width: 40, hidden:true},
			{ label: 'taxclaimable', name: 'delordhd_taxclaimable', width: 40, hidden:true},
			{ label: 'TaxAmt', name: 'delordhd_TaxAmt', width: 40, hidden:true},
			{ label: 'cancelby', name: 'delordhd_cancelby', width: 40, hidden:true},
			{ label: 'canceldate', name: 'delordhd_canceldate', width: 40, hidden:true},
			{ label: 'reopenby', name: 'delordhd_reopenby', width: 40, hidden:true},
			{ label: 'reopendate', name: 'delordhd_reopendate', width: 40, hidden:true},
			{ label: 'unit', name: 'delordhd_unit', width: 40, hidden:true},

		],
/*		multiselect:true,*/
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'delordhd_idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",

		/*beforeSelectRow: function (rowid, e) {
        var $self = $(this),
            iCol = $.jgrid.getCellIndex($(e.target).closest("td")[0]),
            cm = $self.jqGrid("getGridParam", "colModel"),
            localData = $self.jqGrid("getLocalRow", rowid);
        if (cm[iCol].name === "Checkbox" && e.target.tagName.toUpperCase() === "OPEN") {
            // set local grid data
            localData.Checkbox = $(e.target).is(":checked");
           alert((localData));
        }
        
        return true; // allow selection
    	},*/

		onSelectRow:function(data, rowid, selected){
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			let scope = $("#recstatus_use").val();
			

			// // $('#but_post_single_jq,#but_cancel_jq,#but_soft_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			// if (stat == scope ) {
			// 	$('#but_cancel_jq').show();
			// //} else if ( stat == "OPEN" ){
			// 	//$('#but_soft_cancel_jq').show();
			// } else if ( stat == "CANCELLED" ){
			// 	$('#but_reopen_jq').show();
			// } else {
			// 	if(scope.toUpperCase() == 'ALL'){
			// 		// $('#but_post_jq').show();
			// 		// if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0 && stat=='OPEN'){
			// 		// 	$('#but_post_single_jq').show();
			// 		// }else if(stat=='OPEN'){
			// 		// 	$('#but_post_jq').show();
			// 		// }
			// 	}else{
			// 		// if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0){
			// 		// 	$('#but_post_single_jq').show();
			// 		// }else{
			// 		// 	$('#but_post_jq').show();
			// 		// }
			// 	}
			// }

			urlParam2.filterVal[0]=selrowData("#jqGrid").delordhd_recno;
			refreshGrid("#jqGrid3",urlParam2);
			populate_form(selrowData("#jqGrid"));

			$('#recnodepan').text(selrowData("#jqGrid").delordhd_recno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").delordhd_prdept);

			$('#but_print_dtl').data('recno',selrowData("#jqGrid").delordhd_recno);

			refreshGrid("#jqGrid3",urlParam2);

			$("#pdfgen1").attr('href','./deliveryOrder/showpdf?recno='+selrowData("#jqGrid").delordhd_recno);

			$("#pdfgen2").attr('href','./deliveryOrder/showpdf?recno='+selrowData("#jqGrid").delordhd_recno);

			if_cancel_hide();
			
			if(stat=='POSTED'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").delordhd_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();

			if ($("#jqGrid").data('lastselrow') == '-1' || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
			populate_form(selrowData("#jqGrid"));

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			errorField.length = 0;
			page_to_view_only($('#viewonly').val(),function(){
				let firstrow = $("#jqGrid").getDataIDs()[0];
				$('#jqGrid tr#'+firstrow).dblclick();
			});
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
			let stat = selrowData("#jqGrid").delordhd_recstatus;

			if(stat=='POSTED'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}

			if($('#scope').val() != 'ALL'){
				$("#jqGridPager td[title='Edit Selected Row'],#jqGridPager td[title='Add New Row']").hide();
			}
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
			$("#jqGrid").data('lastselrow',selRowId);
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
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				oper='edit';
				selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
				$("#jqGrid").data('lastselrow',selRowId);
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
			$("#jqGrid").data('lastselrow','-1');
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid',false,urlParam);
	addParamField('#jqGrid',false,saveParam,['delordhd_trantype','delordhd_recno','delordhd_docno','delordhd_adduser','delordhd_adddate','delordhd_upduser','delordhd_upddate','delordhd_deluser','delordhd_idno','supplier_name','delordhd_recstatus','delordhd_unit','Refresh', 'Checkbox']);

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
	
	$("#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){

		var idno = selrowData('#jqGrid').delordhd_idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( './deliveryOrder/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});


	$("#but_post_jq").click(function(){
		$("#but_post_jq").attr('disabled',true);
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './deliveryOrder/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
			$("#but_post_jq").attr('disabled',false);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
			$("#but_post_jq").attr('disabled',false);
		}).success(function(data){
			
		});
	});

	$("#but_post2_jq").click(function(){
	
		var obj={};
		obj.idno = selrowData('#jqGrid').delordhd_idno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './deliveryOrder/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});
	

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		errorField.length = 0;
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;
		$('#delordhd_docno').val(unpadzero($('#delordhd_docno').val()));
		$('#delordhd_srcdocno').val(unpadzero($('#delordhd_srcdocno').val()));

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {

		},'json').fail(function (data) {
			// $('.noti').text(data.responseJSON.message);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			mycurrency.formatOn();
			dialog_authorise.on();
			dialog_prdept.on();
			dialog_suppcode.on();
			dialog_credcode.on();
			dialog_deldept.on();
			dialog_reqdept.on();
			dialog_srcdocno.on();
		}).done(function (data) {
			hideatdialogForm(false);

			addmore_jqgrid2.state = true;

			if(selfoper=='add'){
				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#delordhd_recno').val(data.recno);
				$('#delordhd_docno').val(data.docno);
				$('#delordhd_idno').val(data.idno);//just save idno for edit later
				$('#delordhd_totamount').val(data.totalAmount);
				$('#delordhd_adduser').val(data.adduser);
				$('#delordhd_adddate').val(data.adddate);

				urlParam2.filterVal[0]=data.recno; 
			}else if(selfoper=='edit'){
				//doesnt need to do anything
				$('#delordhd_upduser').val(data.upduser);
				$('#delordhd_upddate').val(data.upddate);
			}

			if($('#purordhd_purreqno').val() != undefined && $('#purordhd_purreqno').val().trim() == ""){
				$("#jqGrid2").jqGrid('hideCol',["qtyoutstand"]);
			}else{
				$("#jqGrid2").jqGrid('showCol',["qtyoutstand"]);
			}
			
			refreshGrid('#jqGrid2', urlParam2);
			disableForm('#formdata');

		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	// $("#dialogForm").on('click','#formdata a.input-group-addon',function(){
	// 	unsaved = true; //kalu dia change apa2 bagi prompt
	// });

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
	// trandept();
	// function trandept(){
	// 	var param={
	// 		action:'get_value_default',
	// 		url: 'util/get_value_default',
	// 		field:['deptcode'],
	// 		table_name:'sysdb.department',
	// 		filterCol:['purdept'],
	// 		filterVal:['1']
	// 	}
	// 	$.get( param.url+"?"+$.param(param), function( data ) {
			
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data)){
	// 			$.each(data.rows, function(index, value ) {
	// 				if(value.deptcode.toUpperCase()== $("#deptcode").val().toUpperCase()){
	// 					$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}else{
	// 					$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}
	// 			});
	// 			searchChange();
	// 		}
	// 	});
	// }

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		supplierkatdepan.off();
		if($('#Scol').val()=='delordhd_trandate'){
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
			supplierkatdepan.on();
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
					// $('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	supplierkatdepan.makedialog();
	
	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}
	
	searchChange(true);
	function searchChange(once=false){
		var arrtemp = ['session.compcode',  $('#Status option:selected').val(), $('#trandept option:selected').val(),'GRN'];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['delordhd.compcode','delordhd.recstatus',  'delordhd.prdept','delordhd.trantype'],fv:[],fc:[]});//tukar kat sini utk searching purreqhd.compcode','purreqhd.recstatus','purreqhd.prdept'

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;

		if(once){
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if($('#searchForm [name=Stext]').val().trim() != ''){
				let searchCol = ['delordhd_recno'];
				let searchVal = [$('#searchForm [name=Stext]').val().trim()];
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}
			once=false;
		}
		refreshGrid('#jqGrid',urlParam);
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_dtl',
		url:'./deliveryOrderDetail/table',
		field:['dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode','dodt.pouom', 'dodt.suppcode','dodt.trandate','dodt.deldept','dodt.deliverydate','dodt.qtyorder','dodt.qtydelivered', 'dodt.qtyoutstand','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks', 'dodt.unit','t.rate','dodt.idno','dodt.kkmappno'],
		table_name:['material.delorddt AS dodt','material.productmaster AS p','hisdb.taxmast AS t'],
		table_id:'lineno_',
		join_type:['LEFT JOIN','LEFT JOIN'],
		join_onCol:['dodt.itemcode','dodt.taxcode'],
		join_onVal:['p.itemcode','t.taxcode'],
		filterCol:['dodt.recno','dodt.compcode','dodt.recstatus'],
		filterVal:['','session.compcode','<>.DELETE']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, auto add after refresh jqgrid2, state true kalu
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./deliveryOrderDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable:false},			
			{ label: 'Price Code', name: 'pricecode', width: 80, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:pricecodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Description', name: 'description', width: 250, classes: 'wrap', editable:false, hidden:false},
			{ label: 'UOM Code', name: 'uomcode', width: 120, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{
				label: 'PO UOM', name: 'pouom', width: 120, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Tax Code', name: 'taxcode', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:taxcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Quantity <br/> Order', name: 'qtyorder', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Quantity <br/> Delivered', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap', editable:true,
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
			{ label: 'Balance <br/> Quantity', name: 'qtyoutstand', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Percentage Discount (%)', name: 'perdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Discount Per Unit', name: 'amtdisc', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
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
			{ label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},
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
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'netunitprice', name: 'netunitprice', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Total Line Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'amount', name: 'amount', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Expiry Date', name: 'expdate', width: 100, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				editoptions: {
                    dataInit: function (element) {
                        $(element).datepicker({
                            id: 'expdate_datePicker',
                            dateFormat: 'dd/mm/yy',
                            minDate: 1,
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
			{ label: 'Batch No', name: 'batchno', width: 170, classes: 'wrap', editable:true,maxlength: 100,},
			{ label: 'MDA/NOT/MAL', name: 'kkmappno', width: 190, classes: 'wrap', editable:true,maxlength: 100,},
			{ label: 'PO Line No', name: 'polineno', width: 75, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', hidden:true},
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'whtspc_wrap', hidden: false },
			{ label: 'unit', name: 'unit', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'idno', name: 'idno', width: 75, classes: 'wrap', hidden:true,},
			{ label: 'suppcode', name: 'suppcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'trandate', name: 'trandate', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deldept', name: 'deldept', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'deliverydate', name: 'deliverydate', width: 20, classes: 'wrap', hidden:true},
		],
		scroll: false,
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
	    rowNum: 1000000,
	    pgbuttons: false,
	    pginput: false,
	    pgtext: "",
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2],
							function(){
								fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
							}
						)
					});
				}
			});
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}else if(addmore_jqgrid2.state == true && $('#delordhd_srcdocno').val().trim().length > 0 && $('#jqGridPager2EditAll').data('click') == false){
				$('#jqGridPager2EditAll').data('click',true);
				$('#jqGridPager2EditAll').click();
			}else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		onSelectRow: function (rowid, selected) {
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		gridComplete: function(){
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array(function(){
				calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			}).reset();
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;
		},
		afterShowForm: function (rowid) {
		    $("#expdate").datepicker();
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_itemcode.check(errorField);
			// dialog_uomcode.check(errorField);
			// dialog_pouom.check(errorField);
	 	}
	}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	$("#jqGrid2").jqGrid('bindKeys');
	var updwnkey_fld;
	function updwnkey_func(event){
		var optid = event.currentTarget.id;
		var fieldname = optid.substring(optid.search("_"));
		updwnkey_fld = fieldname;
	}

	// $("#jqGrid2").keydown(function(e) {
 //      switch (e.which) {
 //        case 40: // down
 //          var $grid = $(this);
 //          var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
	// 	  $("#"+selectedRowId+updwnkey_fld).focus();

 //          e.preventDefault();
 //          break;

 //        case 38: // up
 //          var $grid = $(this);
 //          var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
	// 	  $("#"+selectedRowId+updwnkey_fld).focus();

 //          e.preventDefault();
 //          break;

 //        default:
 //          return;
 //      }
 //    });


	// $("#jqGrid2").jqGrid('setGroupHeaders', {
  	// useColSpanStyle: false, 
	//   groupHeaders:[
	// 	{startColumnName: 'description', numberOfColumns: 1, titleText: 'Item'},
	// 	{startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item'},
	//   ]
	// })

    //  try {
    //     var p = $grid.jqGrid("getGridParam"), tid = $.jgrid.jqID(p.id), colModel = p.colModel, i, n = colModel.length, cm,
    //         skipIds = [];

    //     for (i = 0; i < n; i++) {
    //         cm = colModel[i];
    //         if ($.inArray(cm.name, ["cb", "rn", "subgrid"]) >=0 || cm.frozen) {
    //             skipIds.push("#jqgh_" + tid + "_" + $.jgrid.jqID(cm.name));
    //         }
    //     }

    //     $grid.jqGrid("setGridParam", {sortable: {options: {
    //         items: skipIds.length > 0 ? ">th:not(:has(" + skipIds.join(",") + "),:hidden)" : ">th:not(:hidden)"
    //     }}});

    //     $grid.jqGrid("sortableColumns", $($grid[0].grid.hDiv).find(".ui-jqgrid-labels"));
    // } catch (e) {}

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-sm' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><span class='glyphicon glyphicon-comment' aria-hidden='true'></span> remark </button>";
	}


	function formatterRefresh(cellvalue, options, rowObject){
		return "<button class='refresh_button btn btn-success btn-xs' type='button' data-idno='"+rowObject.delordhd_idno+"' data-grid='#"+options.gid+"' ><i class='fa fa-refresh'></i></button>";
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = cbselect.recstatus;
		
		if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}
		if($('#recstatus_use').val() == 'ALL'){
			if(rowObject.delordhd_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'CANCEL'){
			if(rowObject.delordhd_recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}else if(rowObject.delordhd_recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#recstatus_use').val() == 'REOPEN'){
			if(rowObject.delordhd_recstatus == "CANCELLED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
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
				if($("#jqGridPager2SaveAll").css('display') == 'none'){
					$("#jqGrid2_ilsave").click();
				}
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
		close:function(){
			fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
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
			myfail_msg.clear_fail();
        	calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			$("#jqGrid2 input[name='pricecode']").focus().select();
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
        	$("#jqGrid2 input#"+rowid+"_pricecode").val('IV');
        	dialog_pricecode.id_optid = rowid;
	        dialog_pricecode.check(errorField,rowid+"_pricecode","jqGrid2",null,
	        	function(self){
	        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
		        },function(self){
					fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			    }
		    );

        	$("#jqGrid2 input#"+rowid+"_itemcode").focus();

        	// if($('#delordhd_srcdocno').val()!='' && $("#jqGrid2_iladd").css('display') == 'none' ){
        	// 	$("#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='itemcode'],#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='taxcode'],#jqGrid2 input[name='perdisc'],#jqGrid2 input[name='amtdisc'],#jqGrid2 input[name='pricecode']").attr('readonly','readonly');

			// }else{
				dialog_pricecode.on();//start binding event on jqgrid2
				dialog_itemcode.on();
				dialog_uomcode.on();
				dialog_pouom.on();
				dialog_taxcode.on();
			//}

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='totamount']"]);
			Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtydelivered']"]);

			$("input[name='gstpercent']").val('0')//reset gst to 0
			// mycurrency2.formatOnBlur();//make field to currency on leave cursor
			// mycurrency_np.formatOnBlur();//make field to currency on leave cursor

			$("#jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='taxcode'], #jqGrid2 input[name='perdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
			$("#jqGrid2 input[name='qtydelivered']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt);

			$("#jqGrid2 input[name='qtydelivered']").on('blur',calculate_conversion_factor);
			$("#jqGrid2 input[name='pouom']").on('blur',remove_noti);

			$("input[name='kkmappno']").keydown(function(e) {//when click tab at kkmappno, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});

			$("#jqGrid2 input#"+rowid+"_pricecode").on('focus',function(){
				let focus = $(this).data('focus');
				if(focus == undefined){
					$(this).data('focus',1);
					$("#jqGrid2 input#"+rowid+"_itemcode").focus();
				}
			});

        	// cari_gstpercent($("#jqGrid2 input[name='taxcode']").val());
        },
        aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#delordhd_delordno').val(resobj.delordno);
			$('#delordhd_recno').val(resobj.recno);
			$('#delordhd_totamount').val(resobj.totalAmount);
			$('#delordhd_subamount').val(resobj.totalAmount);
			mycurrency.formatOn();
	    	if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = resobj.recno;
	    	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
        	calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
        }, 
        errorfunc: function(rowid,response){
			errorField.length=0;
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
        beforeSaveRow: function(options, rowid) {
        	// console.log($('#jqGrid2 input[name=uomcode]').val()=='')
        	if(errorField.length>0 || $('#jqGrid2 input[name=uomcode]').val()=='')return false;
        	
        	mycurrency2.formatOff();
			mycurrency_np.formatOff();

			if(parseInt($('#jqGrid2 input[name="qtydelivered"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			
			let editurl = "./deliveryOrderDetail/form?"+
				$.param({
					action: 'delOrdDetail_save',
					idno: $('#delordhd_idno').val(),
					docno:$('#delordhd_docno').val(),
					recno:$('#delordhd_recno').val(),
					suppcode:$('#delordhd_suppcode').val(),
					trandate:$('#delordhd_trandate').val(),
					deldept:$('#delordhd_deldept').val(),
					deliverydate:$('#delordhd_deliverydate').val(),
					remarks:data.remarks,
					amount:data.amount,
					netunitprice:data.netunitprice,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
        	console.log('restore');
        	console.log(response);
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);
			$('#jqGrid2').jqGrid ('setSelection', "1");
        	calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
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
				if(selrowData('#jqGrid2').qtyorder>0){
					alert("This data cannot be deleted");
				}else{
					bootbox.confirm({
					    message: "Are you sure you want to delete this row?",
					    buttons: {confirm: {label: 'Yes', className: 'btn-danger',},cancel: {label: 'No', className: 'btn-success' }
					    },
					    callback: function (result) {
					    	if(result == true){
					    		param={
					    			_token: $("#_token").val(),
					    			action: 'delOrdDetail_save',
									recno: $('#delordhd_recno').val(),
									lineno_: selrowData('#jqGrid2').lineno_,
					    		}
					    		$.post( "./deliveryOrderDetail/form?"+$.param(param),{oper:'del'}, function( data ){
								}).fail(function(data) {
									//////////////////errorText(dialog,data.responseText);
								}).done(function(data){
									$('#delordhd_totamount').val(data);
									$('#delordhd_subamount').val(data);
									refreshGrid("#jqGrid2",urlParam2);
								});
					    	}else{
	        					$("#jqGridPager2EditAll").show();
					    	}
					    }
					});
				}
			}
		},
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
		    	var objdata = $("#jqGrid2").jqGrid ('getRowData', ids[i]);
		        $("#jqGrid2").jqGrid('editRow',ids[i]);

				if(objdata.pricecode.slice(0, objdata.pricecode.search("[<]")) == 'MS'){
			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount"]);
				}else{
			        Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amtdisc","#"+ids[i]+"_unitprice","#"+ids[i]+"_amount","#"+ids[i]+"_tot_gst", "#"+ids[i]+"_totamount"]);
					Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_qtydelivered"]);
				}

				dialog_pricecode.id_optid = ids[i];
		        // dialog_pricecode.check(errorField,ids[i]+"_pricecode","jqGrid2",null,
		        // 	function(self){
		        // 		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     },function(self){
				// 		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				//     }
			    // );

		        dialog_itemcode.id_optid = ids[i];
		        // dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		        // 	function(self){
		        // 		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     },function(self){
				// 		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
				//     }
			    // );

		        dialog_pouom.id_optid = ids[i];
		        dialog_pouom.check(errorField,ids[i]+"_pouom","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(self){
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
			    );

		        dialog_uomcode.id_optid = ids[i];
		        // dialog_uomcode.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		        // 	function(self){
			    //     	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     },function(self){
				// 		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			    //     }
			    // );

				dialog_taxcode.id_optid = ids[i];
		        dialog_taxcode.check(errorField,ids[i]+"_taxcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(data,self){
			        	if(data.rows.length > 0){
							$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
			        	}
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			        }
		        );

		        cari_gstpercent(ids[i]);
		    }
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
			var is_error = false;
		    for (var i = 0; i < ids.length; i++) {

		    	// if(check_qtydlr_qtyout(ids[i]) == false){
		    	// 	is_error = true;
		    	// 	break;
		    	// }

				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'idno' : data.idno,
		    		'pricecode' : $("#jqGrid2 input#"+ids[i]+"_pricecode").val(),
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'pouom' : $("#jqGrid2 input#"+ids[i]+"_pouom").val(),
		    		'qtyorder' : $("#jqGrid2 input#"+ids[i]+"_qtyorder").val(),
		    		'qtydelivered' : $('#'+ids[i]+"_qtydelivered").val(),
		    		'unitprice': $('#'+ids[i]+"_unitprice").val(),
		    		'taxcode' : $("#jqGrid2 input#"+ids[i]+"_taxcode").val(),
                    'perdisc' : $('#'+ids[i]+"_perdisc").val(),
                    'amtdisc' : $('#'+ids[i]+"_amtdisc").val(),
                    'tot_gst' : $('#'+ids[i]+"_tot_gst").val(),
                    'netunitprice' : data.netunitprice, //ni mungkin salah
                    'amount' : data.amount,
                    'totamount' : $("#"+ids[i]+"_totamount").val(),
                    'expdate' : $("#"+ids[i]+"_expdate").val(),
                    'batchno' : $("#"+ids[i]+"_batchno").val(),
					'kkmappno' : $("#"+ids[i]+"_kkmappno").val(),
                    'remarks' : data.remarks,
                    'unit' : $("#"+ids[i]+"_unit").val()
		    	}

		    	jqgrid2_data.push(obj);
		    }

		    if(is_error){
		    	return false;
		    }

			var param={
    			action: 'delOrdDetail_save',
				_token: $("#_token").val(),
				recno: $('#delordhd_recno').val(),
				action: 'delOrdDetail_save',
				docno:$('#delordhd_docno').val(),
				suppcode:$('#delordhd_suppcode').val(),
				trandate:$('#delordhd_trandate').val(),
				deldept:$('#delordhd_deldept').val(),
				deliverydate:$('#delordhd_deliverydate').val(),
    		}

    		$.post( "./deliveryOrderDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				//////////////////errorText(dialog,data.responseText);
				myfail_msg.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
			}).done(function(data){
				if(data){
					$('#delordhd_subamount, #delordhd_totamount').val(data);
				}
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
		var field,table,case_;
		switch(options.colModel.name){
			case 'itemcode':field=['itemcode','description'];table="material.productmaster";case_='itemcode';break;
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
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('deliveryOrder',options,param,case_,cellvalue);
		// faster_detail_array.push(faster_detail_load('deliveryOrder',options,param,case_,cellvalue));
		// 

		if(options.gid != 'jqGrid2'){
        	calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		}
		if(cellvalue == null){
			cellvalue = " "
		};
		return cellvalue;
	}

	function format_qtyoutstand(cellvalue, options, rowObject){
		var qtyoutstand = rowObject.qtyorder - rowObject.qtydelivered;
		if(qtyoutstand<0 || isNaN(qtyoutstand)) return 0;
		return qtyoutstand;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp=null;
		switch(name){
			case 'Item Code':temp=$("#jqGrid2 input[name='itemcode']");break;
			case 'UOM Code':temp=$("#jqGrid2 input[name='uomcode']");break;
			case 'PO UOM': temp = $("#jqGrid2 input[name='pouom']"); break;
			case 'Price Code':temp=$("#jqGrid2 input[name='pricecode']");break;
			case 'Tax Code':temp=$("#jqGrid2 input[name='taxcode']");break;
			case 'Quantity Delivered': temp = $("#jqGrid2 input[name='qtydelivered']"); 
				$("#jqGrid2 input[name='qtydelivered']").hasClass("error");
				break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val,opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="` + val + `" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+opt.id+`_convfactor_uom" name="convfactor_uom" type="hidden" value=`+1+`><input id="`+opt.id+`_convfactor_pouom" name="convfactor_pouom" type="hidden" value=`+1+`></div>`);
	}
	function taxcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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

	///////////Validation for delordhd_delordno////////////////////////////////////////////////////////
	
	$("#delordhd_delordno").blur(function(){
		if(oper == 'add'){
			var id = "#delordhd_delordno";
			var param={
				func:'getDONo',
				action:'get_value_default',
				url: 'util/get_value_default',
				field:['delordno'],
				table_name:'material.delordhd'
			}

			param.filterCol = ['delordno','compcode','unit'];
			param.filterVal = [$("#delordhd_delordno").val(),'session.compcode','session.unit'];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id,errorField)!==-1){
						errorField.splice($.inArray(id,errorField), 1);
					}
					$( id ).removeClass( "error" ).addClass( "valid" );
				} else {
					alert("Duplicate DO No");
					$( id ).removeClass( "valid" ).addClass( "error" );
					$( id ).val("");
					if($.inArray(id,errorField)===-1){
						errorField.push( id );
					}
				}
			});
		}
	});

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){ //actually saving the header
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_authorise.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_credcode.off();
		dialog_reqdept.off();
		dialog_deldept.off();
		dialog_srcdocno.off();
		radbuts.check();
		// errorField.length = 0;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		}else{
			mycurrency.formatOn();
			dialog_authorise.on();
			dialog_prdept.on();
			dialog_suppcode.on();
			dialog_credcode.on();
			dialog_deldept.on();
			dialog_reqdept.on();
			dialog_srcdocno.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		addmore_jqgrid2.state = false;
		dialog_authorise.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		dialog_credcode.on();
		dialog_deldept.on();
		dialog_reqdept.on();
		dialog_srcdocno.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
	});

	/////////////calculate conv fac//////////////////////////////////
	 function calculate_conversion_factor(event) {
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
		var pricecode = $("#jqGrid2 input#"+id_optid+"_pricecode").val();

		if(pricecode == 'MS'){
			return true;
		}

		var id="#jqGrid2 #"+id_optid+"_qtydelivered";
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#jqGrid2 input#"+id_optid+"_pouom_convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#jqGrid2 input#"+id_optid+"_pouom_convfactor_pouom").val());
		let qtydelivered = parseFloat($("#jqGrid2 input#"+id_optid+"_qtydelivered").val());

		var balconv = convfactor_pouom*qtydelivered%convfactor_uom;
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
		// check_qtydlr_qtyout(id_optid);
	}

	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		$(".noti").empty();

	}

	function check_qtydlr_qtyout(id_optid){
		var qtyoutstand_ = parseFloat($("#jqGrid2 input#"+id_optid+"_qtyoutstand").val());
		var qtydelivered_ = parseFloat($("#jqGrid2 input#"+id_optid+"_qtydelivered").val());
		var name = 'check_qtydlr_qtyout';

		if(qtydelivered_ > qtyoutstand_){
			$('.noti').prepend("<li data-errorid='"+name+"'>Quantity Delivered higher than Quantity Balance</li>");
			return false;
		}else{
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		}
		return true;
	}

	///////////////////////////////////////////////////////////////////////////////

	//////////////////////////////calculate outstanding quantity/////////////////////
	function calculate_quantity_outstanding(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

        let qtyorder = parseFloat($("#jqGrid2 #"+id_optid+"_qtyorder").val());
        let qtydelivered = parseFloat($("#jqGrid2 #"+id_optid+"_qtydelivered").val());

        var qtyOutstand = (qtyorder - qtydelivered);

        $("input[name='qtyOutstand']").val(qtyOutstand);
	}
	///////////////////////////////////////////////////////////////////////////////

	function onall_editfunc(){
		// if($('#delordhd_srcdocno').val()!=''){
    	// 	$("#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='itemcode'],#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='taxcode'],#jqGrid2 input[name='perdisc'],#jqGrid2 input[name='amtdisc'],#jqGrid2 input[name='pricecode']").attr('readonly','readonly');

		// }else{
		dialog_pricecode.off();//start binding event on jqgrid2
		$(dialog_pricecode.textfield).attr('disabled',true);
		dialog_itemcode.off();
		$(dialog_itemcode.textfield).attr('disabled',true);
		dialog_uomcode.off();
		$(dialog_uomcode.textfield).attr('disabled',true);
		dialog_pouom.on();
		dialog_taxcode.on();

		//}
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtydelivered'], #jqGrid2 input[name='unitprice'], #jqGrid2 input[name='amtdisc'], #jqGrid2 input[name='perdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt_all);
		$("#jqGrid2 input[name='qtydelivered']").on('blur',{currency: mycurrency_np},calculate_line_totgst_and_totamt_all);

		$("#jqGrid2 input[name='qtydelivered']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='pouom']").on('blur',remove_noti);
		// $("#jqGrid2 input[name='qtydelivered'],#jqGrid2 input[name='unitprice'],#jqGrid2 input[name='expdate'],#jqGrid2 input[name='batchno']").on('focus',updwnkey_func);
		calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
	}

	/////////////bind shift + f to btm detail///////////
	$(document).bind('keypress', function(event) {
	    if( event.which === 70 && event.altKey ) {
	        $("#saveDetailLabel").click();
	    }
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////

	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);
	function calculate_line_totgst_and_totamt(event){
		var name_from = $(event.currentTarget).attr('name');

		mycurrency2.formatOff();
		mycurrency_np.formatOff();
		
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let qtydelivered = parseFloat($("#"+id_optid+"_qtydelivered").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());

		var totamtperUnit = ((unitprice*qtydelivered) - (amtdisc*qtydelivered));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		if(isNaN(tot_gst))tot_gst = 0;

		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?

		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});


		// var id="#jqGrid2 #"+id_optid+"_qtydelivered";
		// var fail_msg = "Quantity Delivered must be greater than 0";
		// var name = "quantitydelivered";

		// if(name_from != 'taxcode'){
		// 	if(qtydelivered > 0) {
		// 		if($.inArray(id,errorField)!==-1){
		// 			errorField.splice($.inArray(id,errorField), 1);
		// 		}
		// 		$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
		// 		$( id ).removeClass( "error" ).addClass( "valid" );
		// 		$('.noti').find("li[data-errorid='"+name+"']").detach();
		// 	} else {
		// 		$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
		// 		$( id ).removeClass( "valid" ).addClass( "error" );
		// 		if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
		// 		if($.inArray(id,errorField)===-1){
		// 			errorField.push( id );
		// 		}
		// 	}
		// }

		if(event.target.name=='unitprice'){

			var id2="#jqGrid2 #"+id_optid+"_unitprice";
			var fail_msg2 = "Unitprice cannot be 0";
			var name2 = "unitprice";
			if($("input#"+id_optid+"_pricecode").val() != 'BO' && unitprice == 0 ) {
				$( id2 ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id2 ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name2+"']").length)$('.noti').prepend("<li data-errorid='"+name2+"'>"+fail_msg2+"</li>");
				if($.inArray(id2,errorField)===-1){
					errorField.push( id2 );
				}
			} else {
				if($.inArray(id2,errorField)!==-1){
					errorField.splice($.inArray(id2,errorField), 1);
				}
				$( id2 ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id2 ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name2+"']").detach();
			}
		}

		if(event.target.name=='qtydelivered'){

			var id3="#jqGrid2 #"+id_optid+"_qtydelivered";
			var fail_msg3 = "Quantity Ordered cant exceed Quantity Balanced";
			var name3 = "qtyoutstand";

			var qtybalance = parseFloat($("#"+id_optid+"_qtyoutstand").val());

			if(qtydelivered > qtybalance){
				if(!$('.noti').find("li[data-errorid='"+name3+"']").length)$('.noti').prepend("<li data-errorid='"+name3+"'>"+fail_msg3+"</li>");
				$( id3 ).val('');
			}else{
				$('.noti').find("li[data-errorid='"+name3+"']").detach();
			}
		}

		mycurrency2.formatOn();
		mycurrency_np.formatOn();

		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
	}

	function calculate_line_totgst_and_totamt_all(event){
		var name_from = $(event.currentTarget).attr('name');

		mycurrency2.formatOff();
		mycurrency_np.formatOff();
		
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		let qtydelivered = parseFloat($("#"+id_optid+"_qtydelivered").val());
		let unitprice = parseFloat($("#"+id_optid+"_unitprice").val());
		let amtdisc = parseFloat($("#"+id_optid+"_amtdisc").val());
		let perdisc = parseFloat($("#"+id_optid+"_perdisc").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val());

		var totamtperUnit = ((unitprice*qtydelivered) - (amtdisc*qtydelivered));
		var amount = totamtperUnit- (totamtperUnit*perdisc/100);
		
		var tot_gst = amount * (gstpercent / 100);
		if(isNaN(tot_gst))tot_gst = 0;

		var totalAmount = amount + tot_gst;

		var netunitprice = (unitprice-amtdisc);//?

		$("#"+id_optid+"_tot_gst").val(tot_gst);
		$("#"+id_optid+"_totamount").val(totalAmount);

		$("#jqGrid2").jqGrid('setRowData', id_optid ,{amount:amount});
		$("#jqGrid2").jqGrid('setRowData', id_optid ,{netunitprice:netunitprice});

		var all_totamt=0;
		$.each($("#jqGrid2 input[name=totamount]"), function (index, value) {
			all_totamt = parseFloat(all_totamt) + parseFloat($(this).val());
		});
		$('#delordhd_subamount, #delordhd_totamount').val(all_totamt);

		// var id="#jqGrid2 #"+id_optid+"_qtydelivered";
		// var fail_msg = "Quantity Delivered must be greater than 0";
		// var name = "quantitydelivered";

		// if(name_from != 'taxcode'){
		// 	if(qtydelivered > 0) {
		// 		if($.inArray(id,errorField)!==-1){
		// 			errorField.splice($.inArray(id,errorField), 1);
		// 		}
		// 		$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
		// 		$( id ).removeClass( "error" ).addClass( "valid" );
		// 		$('.noti').find("li[data-errorid='"+name+"']").detach();
		// 	} else {
		// 		$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
		// 		$( id ).removeClass( "valid" ).addClass( "error" );
		// 		if(!$('.noti').find("li[data-errorid='"+name+"']").length)$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
		// 		if($.inArray(id,errorField)===-1){
		// 			errorField.push( id );
		// 		}
		// 	}
		// }

		if(event.target.name=='unitprice'){

			var id2="#jqGrid2 #"+id_optid+"_unitprice";
			var fail_msg2 = "Unitprice cannot be 0";
			var name2 = "unitprice";
			if($("input#"+id_optid+"_pricecode").val() != 'BO' && unitprice == 0 ) {
				$( id2 ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id2 ).removeClass( "valid" ).addClass( "error" );
				if(!$('.noti').find("li[data-errorid='"+name2+"']").length)$('.noti').prepend("<li data-errorid='"+name2+"'>"+fail_msg2+"</li>");
				if($.inArray(id2,errorField)===-1){
					errorField.push( id2 );
				}
			} else {
				if($.inArray(id2,errorField)!==-1){
					errorField.splice($.inArray(id2,errorField), 1);
				}
				$( id2 ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id2 ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name2+"']").detach();
			}
		}

		// if(event.target.name=='qtydelivered'){

		// 	var id3="#jqGrid2 #"+id_optid+"_qtydelivered";
		// 	var fail_msg3 = "Quantity Ordered cant exceed Quantity Balanced";
		// 	var name3 = "qtyoutstand";

		// 	var qtybalance = parseFloat($("#"+id_optid+"_qtyoutstand").val());

		// 	if(qtydelivered > qtybalance){
		// 		if(!$('.noti').find("li[data-errorid='"+name3+"']").length)$('.noti').prepend("<li data-errorid='"+name3+"'>"+fail_msg3+"</li>");
		// 		$( id3 ).val('');
		// 	}else{
		// 		$('.noti').find("li[data-errorid='"+name3+"']").detach();
		// 	}
		// }

		// event.data.currency.formatOn();//change format to currency on each calculation
		mycurrency_np.formatOn();
		mycurrency2.formatOn();
		mycurrency.formatOn();
		
		fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
	}
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
		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2],
							function(){
								fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
							}
						)
					});
				}
			});

			// setjqgridHeight(data,'jqGrid3');
			$('#jqGrid3').jqGrid ('setSelection', "1");
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
	}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	$("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");

	$("#jqGrid3_panel").on('show.bs.collapse', function(){
		fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	});

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_authorise = new ordialog(
		'authorise',['sysdb.users'],"#delordhd_respersonid",errorField,
		{	colModel:
			[
				{label:'Authorize Person',name:'name',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Department',name:'dept',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true}
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#delordhd_remarks').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#delordhd_remarks').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Authorize Person",
			open: function(){
				dialog_authorise.urlParam.filterCol=['compcode','recstatus'];
				dialog_authorise.urlParam.filterVal=['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	dialog_authorise.makedialog(false);

	var dialog_prdept = new ordialog(
		'prdept','sysdb.department','#delordhd_prdept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['purdept', 'recstatus','compcode'],
						filterVal:['1', 'ACTIVE','session.compcode']
					},
			ondblClickRow: function () {
				let data = selrowData('#'+dialog_prdept.gridname);
				backdated.set_backdate(data.deptcode);
				$('#delordhd_srcdocno').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#delordhd_srcdocno').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Transaction Department",
			open: function(){
				dialog_prdept.urlParam.filterCol=['purdept', 'recstatus','compcode'];
				dialog_prdept.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_prdept.makedialog(false);

	var dialog_srcdocno = new ordialog(
		'srcdocno',['material.purordhd AS h'],'#delordhd_srcdocno',errorField,
		{	colModel:[
				{label:'Date',name:'h_purdate',width:200,classes:'pointer', formatter: dateFormatter, unformat: dateUNFormatter},
				{label:'Purchase Department',name:'h_prdept',width:400,classes:'pointer wrap', hidden:false, formatter: showdetail,unformat:un_showdetail},
				{label:'PO NO',name:'h_purordno',width:200,classes:'pointer',canSearch:true,or_search:true, formatter: padzero },
				{label:'Supplier Code',name:'h_suppcode',width:400,classes:'pointer wrap',canSearch:true,checked:true,or_search:true, formatter: showdetail,unformat:un_showdetail},
				{label:'delordno',name:'h_delordno',width:400,classes:'pointer', hidden:true},
				{label:'Request Department',name:'h_reqdept',width:400,classes:'pointer', hidden:true},
				{label:'Total Amount',name:'h_totamount',width:400,classes:'pointer', align: 'right', formatter: 'currency' },
				{label:'Recno',name:'h_recno',width:400,classes:'pointer', hidden:false, align: 'right'},
				{label:'Delivery Department',name:'h_deldept',width:400,classes:'pointer', hidden:true},
				{label:'Record Status',name:'h_recstatus',width:400,classes:'pointer', hidden:true},
				{label:'Amount Discount',name:'h_amtdisc',width:400,classes:'pointer', hidden:true},
				{label:'Sub Amount',name:'h_subamount',width:400,classes:'pointer', hidden:true},
				{label:'h_taxclaimable',name:'h_taxclaimable',width:400,classes:'pointer', hidden:true},
				{label:'Per Disc',name:'h_perdisc',width:400,classes:'pointer', hidden:true},
				{label:'Remarks',name:'h_remarks',width:400,classes:'pointer', hidden:true}
			],
			sortname: 'h_recno',
			sortorder: "desc",
			urlParam: {
						filterCol:['h.prdept','h.compcode'],
						filterVal:[$("#delordhd_prdept").val(),'session.compcode'],
						WhereInCol:['h.recstatus'],
						WhereInVal:[['PARTIAL','APPROVED']]		
					},
			gridComplete: function() {
				fdl.set_array().reset();
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_srcdocno.gridname);
				
				$("#delordhd_srcdocno").val(data['h_purordno']);
				$("#delordhd_suppcode").val(data['h_suppcode']);
				$("#delordhd_credcode").val(data['h_suppcode']);
				$("#delordhd_delordno").val(data['h_delordno']);
				$("#delordhd_reqdept").val(data['h_reqdept']);
				$("#delordhd_deldept").val(data['h_deldept']);
				$("#delordhd_prdept").val(data['h_prdept']);
				$("#delordhd_perdisc").val(data['h_perdisc']);
				$("#delordhd_amtdisc").val(data['h_amtdisc']);
				$("#delordhd_totamount").val(data['h_totamount']);
				$("#delordhd_subamount").val(data['h_subamount']);
				$("#delordhd_taxclaimable").val(data['h_taxclaimable']);
				$("#formdata input[type='radio'][name='delordhd_taxclaimable'][value='"+data['h_taxclaimable']+"']").prop('checked', true);
				// $("#delordhd_recstatus").val(data['h_recstatus']);
				$("#delordhd_remarks").val(data['h_remarks']);
				$('#referral').val(data['h_recno']);

				mycurrency.formatOn();

				dialog_suppcode.check(errorField);
				dialog_credcode.check(errorField);
				dialog_reqdept.check(errorField);
				dialog_deldept.check(errorField);

				var urlParam2 = {
					action: 'get_value_default',
					url: 'util/get_value_default',
					field: ['podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.suppcode', 'podt.purdate','podt.pricecode', 'podt.itemcode', 'p.description','podt.uomcode','podt.pouom','podt.qtyorder','podt.qtyoutstand',
							 'podt.qtydelivered','podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc','podt.amtslstax as tot_gst','podt.netunitprice','podt.totamount',
							 'podt.amount','podt.rem_but AS remarks_button','podt.remarks', 't.rate'],
					table_name: ['material.purorddt AS podt', 'material.productmaster AS p', 'hisdb.taxmast AS t'],
					table_id: 'lineno_',
					join_type: ['LEFT JOIN', 'LEFT JOIN'],
					join_onCol: ['podt.itemcode','podt.taxcode'],
					join_onVal: ['p.itemcode','t.taxcode'],
					join_filterCol: [['p.compcode ='],['t.compcode =']],
					join_filterVal: [['session.compcode'],['session.compcode']],
					filterCol: ['podt.recno', 'podt.compcode', 'podt.recstatus'],
					filterVal: [data['h_recno'], 'session.compcode', '<>.DELETE'],
					sortby:['lineno_ desc']
				};

				$.get("util/get_value_default?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						data.rows.forEach(function(elem) {
							if(elem['qtyorder'] - elem['qtydelivered'] > 0){
								$("#jqGrid2").jqGrid('addRowData', elem['lineno_'] ,
									{
										compcode:elem['compcode'],
										recno:elem['recno'],
										lineno_:elem['lineno_'],
										suppcode:elem['suppcode'],
										pricecode:elem['pricecode'],
										itemcode:elem['itemcode'],
										description:elem['description'],
										uomcode:elem['uomcode'],
										pouom:elem['pouom'],
										qtyorder:elem['qtyorder'],
										qtydelivered:0,
			    						qtyoutstand :elem['qtyoutstand'],
										unitprice:elem['unitprice'],
										taxcode:elem['taxcode'],
										perdisc:elem['perdisc'],
										amtdisc:elem['amtdisc'],
										tot_gst:0,
										rate:elem['rate'],
										netunitprice:0,
										totamount:0,
										amount:0,
										remarks_button:null,
										remarks:elem['remarks'],
									}
								);
							}
						});
						fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
						calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);

					} else {

					}
				});
			}

		},{
			title:"Select PO No",
			open: function(){
				$("#jqGrid2").jqGrid("clearGridData", true);
				dialog_srcdocno.urlParam.fixPost = "true";
				dialog_srcdocno.urlParam.filterCol = ['h.prdept','h.compcode'];
				dialog_srcdocno.urlParam.filterVal = [$("#delordhd_prdept").val(),'session.compcode'];
				dialog_srcdocno.urlParam.WhereInCol = ['h.recstatus'];
				dialog_srcdocno.urlParam.WhereInVal = [['PARTIAL','APPROVED']];
			}
		},'none'
	);
	dialog_srcdocno.makedialog();
	jqgrid_label_align_right('#' + dialog_srcdocno.gridname);

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#delordhd_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_suppcode.gridname);
				$("#delordhd_credcode").val(data['suppcode']);
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
			title:"Select Transaction Type",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcode.urlParam.filterVal=['ACTIVE','session.compcode'];
			},
			close: function(){
				$('#delordhd_delordno').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcode.makedialog();

	var dialog_credcode = new ordialog(
		'credcode','material.supplier','#delordhd_credcode',errorField,
		{	colModel:[
				{label:'Creditor Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Creditor Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
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
			title:"Select Creditor",
			open: function(){
				dialog_credcode.urlParam.filterCol=['recstatus','compcode'];
				dialog_credcode.urlParam.filterVal=['ACTIVE','session.compcode'];
			},
			close: function(){
				$('#delordhd_invoiceno').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_credcode.makedialog();

	var dialog_deldept = new ordialog(
		'deldept','sysdb.department','#delordhd_deldept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['storedept', 'recstatus','compcode'],
						filterVal:['1', 'ACTIVE', 'session.compcode']
					},
			ondblClickRow:function(){
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
			title:"Select Receiver Department",
			open: function(){
				dialog_deldept.urlParam.filterCol=['storedept', 'recstatus','compcode'];
				dialog_deldept.urlParam.filterVal=['1', 'ACTIVE', 'session.compcode'];
			},
			close: function(){
				$('#delordhd_credcode').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_deldept.makedialog();

	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#delordhd_reqdept', 'errorField',
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true,checked:true, or_search: true },
				{label:'Unit',name:'sector'},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
			ondblClickRow: function () {
				$('#depglacc').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#depglacc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Request Department",
			open: function(){
				dialog_reqdept.urlParam.filterCol=['recstatus','compcode'];
				dialog_reqdept.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_reqdept.makedialog();
	dialog_reqdept.required = false;

	var dialog_pricecode = new ordialog(
		'pricecode',['material.pricesource'],"#jqGrid2 input[name='pricecode']",errorField,
		{	colModel:
			[
				{label:'Price code',name:'pricecode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_itemcode").focus().select();

				let data = selrowData('#'+dialog_pricecode.gridname);

				if(data.pricecode == 'MS'){
					mycurrency2.array.length = 0;
					mycurrency_np.array.length = 0;
					Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='totamount']"]);

					mycurrency2.formatOnBlur();//make field to currency on leave cursor
					mycurrency_np.formatOnBlur();
				}else{
					mycurrency2.array.length = 0;
					mycurrency_np.array.length = 0;
					Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='totamount']"]);
					Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtydelivered']"]);

					mycurrency2.formatOnBlur();//make field to currency on leave cursor
					mycurrency_np.formatOnBlur();
				}
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			}
		},{
			title:"Select Price Code For Item",
			open: function(){
				dialog_pricecode.urlParam.filterCol=['compcode','recstatus'];
				dialog_pricecode.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				$("#jqGrid2 input[name='itemcode']").focus().select();
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}
		},'urlParam','radio','tab'
	);
	dialog_pricecode.makedialog(false);

	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'Quantity On Hand',name:'p_qtyonhand',width:100,classes:'pointer',},
				{label: 'UOM Code',name:'p_uomcode',width:100,classes:'pointer'},
				{label: 'Tax Code', name: 'p_TaxCode', width: 100, classes: 'pointer' },
				{label: 'Group Code', name: 'p_groupcode', width: 100, classes: 'pointer' },
				{label: 'Exp', name: 'p_expdtflg', width: 50, classes: 'pointer',formatter:formatterstatus_tick_number,unformat:unformatstatus_tick_number },
				{label: 'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
				{label: 'rate', name: 't_rate', width: 100, classes: 'pointer',hidden:true },
				{label: 'Unit', name:'p_unit', width: 50},
			],
			urlParam: {
				open:function(){

					let data = selrowData('#'+dialog_pricecode.gridname);

					if(data.pricecode == 'MS'){
						filterCol = ['p.compcode', 'p.groupcode', 'p.unit'];
						filterVal = ['session.compcode',  '<>.Stock', 'session.unit'];
					}else{
						filterCol=['s.compcode','s.itemcode','s.deptcode','s.year'];
						filterVal=['session.compcode',$("#jqGrid2 input[name='itemcode']").val(),$('#delordhd_deldept').val(),moment($('#delordhd_trandate').val()).year()];
					}

					// dialog_itemcode_init();
				},
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_itemcode.gridname);

				if(data.hasOwnProperty('p_itemcode')){
					$("#jqGrid2 #"+id_optid+"_itemcode").val(data['p_itemcode']);
				}

				$("#jqGrid2 #"+id_optid+"_uomcode").val(data['p_uomcode']);
				$("#jqGrid2 #"+id_optid+"_pouom").val(data['p_uomcode']);
				if(data['p_TaxCode'] == ''){
					$("#jqGrid2 #"+id_optid+"_taxcode").val('EP');
				}else{
					$("#jqGrid2 #"+id_optid+"_taxcode").val(data['p_TaxCode']);
				}
				$("#jqGrid2 #"+id_optid+"_rate").val(data['t_rate']);
				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['t_rate']);


				var rowid = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{description:data['p_description']});

				dialog_uomcode.id_optid = id_optid;
		        dialog_uomcode.check(errorField,id_optid+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );
			    dialog_pouom.id_optid = id_optid;
		        dialog_pouom.check(errorField,id_optid+"_pouom","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    dialog_taxcode.id_optid = id_optid;
		        dialog_taxcode.check(errorField,id_optid+"_taxcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(data,self){
			        	if(data.rows.length > 0){
							$("#jqGrid2 #"+self.id_optid+"_pouom_gstpercent").val(data.rows[0].rate);
			        	}
			        }
		        );

			    $("#jqGrid2 #"+id_optid+"_uomcode").focus().select();

			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}
			}
		},{
			title:"Select Item For Delivery Order",
			open:function(obj_){
				var pricecode = $("#jqGrid2 input#"+obj_.id_optid+"_pricecode").val();

				if(pricecode == 'IV' || pricecode == 'BO'){
					dialog_itemcode.urlParam.table_name = ['material.stockloc AS s','material.product AS p','hisdb.taxmast AS t','material.uom AS u']
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode', 's.unit'];
					dialog_itemcode.urlParam.filterVal = ['on.p.compcode', moment($('#delordhd_deliverydate').val()).year(), $('#delordhd_deldept').val(),'session.unit'];
					dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN','LEFT JOIN'];
					dialog_itemcode.urlParam.join_onCol = ['s.itemcode','p.taxcode','u.uomcode'];
					dialog_itemcode.urlParam.join_onVal = ['p.itemcode','t.taxcode','s.uomcode'];
					dialog_itemcode.urlParam.join_filterCol = [['p.uomcode on =','p.compcode =','p.recstatus =','p.unit =']];
					dialog_itemcode.urlParam.join_filterVal = [['s.uomcode','session.compcode','ACTIVE','session.unit']];

				}else{
					dialog_itemcode.urlParam.table_name = ['material.product AS p','hisdb.taxmast AS t','material.uom AS u'];
					dialog_itemcode.urlParam.fixPost = "true";
					dialog_itemcode.urlParam.table_id = "none_";
					dialog_itemcode.urlParam.filterCol = ['p.compcode', 'p.groupcode'];
					dialog_itemcode.urlParam.filterVal = ['session.compcode',  '<>.Stock'];
					dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN'];
					dialog_itemcode.urlParam.join_onCol = ['p.taxcode','u.uomcode'];
					dialog_itemcode.urlParam.join_onVal = ['t.taxcode','p.uomcode'];
					dialog_itemcode.urlParam.join_filterCol = [];
					dialog_itemcode.urlParam.join_filterVal = [];

				}

				// dialog_itemcode_init();
			},
			close: function(obj_){
				$("#jqGrid2 #"+obj_.id_optid+"_qtyrequest").focus().select();
			}
		},'none','radio','tab'//urlParam means check() using urlParam not check_input
	);
	dialog_itemcode.makedialog(false);
	

	var dialog_uomcode = new ordialog(
		'uom',['material.stockloc AS s','material.uom AS u'],"#jqGrid2 input[name='uomcode']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'u_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
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
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				
				let data=selrowData('#'+dialog_uomcode.gridname);
				if($("input#"+id_optid+"_pricecode").val() == 'MS'){
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}else{
					$("#jqGrid2 input#"+id_optid+"_uomcode").val(data.u_uomcode);
				}

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_uom").val(data['u_convfactor']);
				$("#jqGrid2 #"+id_optid+"_pouom").focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(obj_){
				var pricecode = $("#jqGrid2 input#"+obj_.id_optid+"_pricecode").val();

				if(pricecode == 'MS'){
					let newcolmodel_uom = [
							{ label: 'UOM code', name: 'u_uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
							{ label: 'Description', name: 'u_description', width: 400, classes: 'pointer', canSearch: true, or_search: true,  checked: true },
							{ label: 'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' }
						]

					$('#'+dialog_uomcode.gridname).jqGrid('setGridParam',{colModel:newcolmodel_uom});

					dialog_uomcode.urlParam.field = getfield(newcolmodel_uom);
					dialog_uomcode.urlParam.table_name = ['material.uom AS u'];
					dialog_uomcode.urlParam.fixPost="true";
					dialog_uomcode.urlParam.table_id="none_";
					dialog_uomcode.urlParam.filterCol=['compcode'];
					dialog_uomcode.urlParam.filterVal=['session.compcode'];
					dialog_uomcode.urlParam.join_type=null;
					dialog_uomcode.urlParam.join_onCol=null;
					dialog_uomcode.urlParam.join_onVal=null;
					dialog_uomcode.urlParam.join_filterCol=null;
					dialog_uomcode.urlParam.join_filterVal=null;

				}else{

					let newcolmodel_uom = [
							{label:'UOM code',name:'u_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
							{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
							{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer' },
							{label:'Department code',name:'s_deptcode',width:150,classes:'pointer'},
							{label:'Item code',name:'s_itemcode',width:150,classes:'pointer'},
						]

					$('#'+dialog_uomcode.gridname).jqGrid('setGridParam',{colModel:newcolmodel_uom});

					dialog_uomcode.urlParam.field = getfield(newcolmodel_uom);
					dialog_uomcode.urlParam.table_name = ['material.uom AS u','material.stockloc AS s'];
					dialog_uomcode.urlParam.fixPost="true";
					dialog_uomcode.urlParam.table_id="none_";
					dialog_uomcode.urlParam.filterCol=['s.compcode','s.itemcode','s.deptcode','s.year'];
					dialog_uomcode.urlParam.filterVal=['session.compcode',$("#jqGrid2 input#"+obj_.id_optid+"_itemcode").val(),$('#delordhd_deldept').val(),moment($('#delordhd_deliverydate').val()).year()];
					dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
					dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
					dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
					dialog_uomcode.urlParam.join_filterCol=[['s.compcode on =']];
					dialog_uomcode.urlParam.join_filterVal=[['u.compcode']];

				}
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
		'pouom', ['material.uom'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true,checked:true, or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer' }
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function (event) {
				//$("#jqGrid2 input[name='qtydelivered']").focus().select();
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_pouom.gridname);

				$("#jqGrid2 #"+id_optid+"_pouom_convfactor_pouom").val(data['convfactor']);
				//$("#jqGrid2 #"+id_optid+"_qtydelivered").focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				}
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.compcode', 'ACTIVE'];

			},
			close: function (obj_) {
				$("#jqGrid2 #"+obj_.id_optid+"_taxcode").focus().select();
			}
		}, 'urlParam','radio','tab'
	);
	dialog_pouom.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(event){
				//$("#jqGrid2 input[name='qtydelivered']").focus().select();
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_taxcode.gridname);

				$("#jqGrid2 #"+id_optid+"_pouom_gstpercent").val(data['rate']);
				//$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				}
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_taxcode.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_taxcode.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
			close: function(obj_){
				// if($('#jqGridPager2SaveAll').css("display") == "none"){
				// 	$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
				// }
				$("#jqGrid2 #"+obj_.id_optid+"_qtydelivered").focus().select();
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

	function if_cancel_hide(){
		if(selrowData('#jqGrid').delordhd_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#ifcancel_show').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
			$('i.fa-angle-double-up,i.fa-angle-double-down').addClass('fa-disable');
		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#ifcancel_show').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
			$('i.fa-angle-double-up,i.fa-angle-double-down').removeClass('fa-disable');
		}
	}

	// $('#jqGridDoctorNote_panel').on('shown.bs.collapse', function () {
	// 	sticky_docnotetbl(on=true);
	//     docnote_date_tbl.ajax.url( "/doctornote/table?"+$.param(dateParam_docnote) ).load(function(data){
	// 		emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
	// 		$('#docnote_date_tbl tbody tr:eq(0)').click();	//to select first row
	//     });
	// });



	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

	/////////////////////pdf//////////////
	// var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	// genpdf.printEvent();

	var barcode = new gen_barcode('#_token','#but_print_dtl',);
	barcode.init();

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( 'deliveryOrderDetail/form',obj,function( data ) {
					
			});
		}
	}

});

function populate_form(obj){
	//panel header
	$('#prdept_show').text(obj.delordhd_prdept);
	$('#grnno_show').text(padzero(obj.delordhd_docno));
	$('#suppcode_show').text(obj.supplier_name);
	
	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
	}
}

function empty_form(){

	$('#prdept_show').text('');
	$('#grnno_show').text('');
	$('#suppcode_show').text('');

}

// function init_focus_header_footer(){
// 	$('#panel_header div.panel-body').on('mouseenter',function(){
//         SmoothScrollTo('#panel_header', 300,-10);
//     });

//     $('#panel_detail div.panel-body').on('mouseenter',function(){
//         SmoothScrollTo('#panel_detail', 300,-10);
//     });
// }