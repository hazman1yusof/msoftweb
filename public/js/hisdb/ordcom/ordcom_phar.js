
var urlParam_phar={
	action:'ordcom_table',
	url:'./ordcom/table',
	chggroup: $('#jqGrid_ordcom_c #ordcomtt_phar').val(),
	mrn:'',
	episno:''
};
var myfail_msg_phar = new fail_msg_func('div#fail_msg_phar');
var mycurrency_phar =new currencymode([]);
var mycurrency_np_phar =new currencymode([],true);

$(document).ready(function(){

	$("#jqGrid_phar").jqGrid({
		datatype: "local",
		editurl: "ordcom/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'TT', name: 'trxtype', width: 30, classes: 'wrap'},
			{ label: 'Date', name: 'trxdate', width: 80, classes: 'wrap',editable:true,
				// formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
				edittype: 'custom', editoptions:
				{
					custom_element: trxdateCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Dept. Code', name: 'deptcode', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: deptcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Item Code', name: 'chgcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'UOM Code', name: 'uom', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},{
				label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 80, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: uom_recvCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{
				label: 'Tax', name: 'taxcode', width: 80, classes: 'wrap', editable: true,
				editrules: { custom: true, custom_func: cust_rules_phar },
				formatter: showdetail_phar,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit_phar,
					custom_value: galGridCustomValue_phar
				},
			},
			{ label: 'Unit<br>Price', name: 'unitprce', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{
				label: 'Quantity', name: 'quantity', width: 60, align: 'right', classes: 'wrap txnum',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true },
			},
			{label: 'Cost<br>Price', name: 'cost_price', hidden: true },
			{ label: 'Total<br>Amount', name: 'amount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Discount<br>Amount', name: 'discamt', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				editrules:{required: true},editoptions:{readonly: "readonly"}},
			// { label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			// { label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', hidden: true},
			{ label: 'Tax<br>Amount', name: 'taxamount', hidden: true },
			{ label: 'Nett<br>Amount', name: 'totamount', width: 80, align: 'right', classes: 'wrap txnum', editable:true,
				formatter:totamountFormatter_phar,
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{label: 'Dosage', name: 'remark', hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'doscode', name: 'doscode', width: 80, classes: 'wrap', hidden: true },
			{ label: 'drugindicator', name: 'drugindicator', width: 80, classes: 'wrap', hidden: true },
			{ label: 'frequency', name: 'frequency', width: 80, classes: 'wrap', hidden: true },
			{ label: 'addinstruction', name: 'addinstruction', width: 80, classes: 'wrap', hidden: true },
			{ label: 'drugindicator_desc', name: 'drugindicator_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'frequency_desc', name: 'frequency_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'doscode_desc', name: 'doscode_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'addinstruction_desc', name: 'addinstruction_desc', width: 80, classes: 'wrap', hidden: true },
			{ label: 'ftxtdosage', name: 'ftxtdosage', width: 80, classes: 'wrap', hidden: true },
			{ label: 'id', name: 'id', width: 10, hidden: true, key:true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: false,
		viewrecords: true,
		loadonce: false,
		width: 1500,
		height: 200,
	    rowNum: 1000000,
	    pgbuttons: false,
	    pginput: false,
	    pgtext: "",
		sortname: 'id',
		sortorder: "desc",
		pager: "#jqGrid_phar_pager",
		gridview: true,
		rowattr:function(data){
			let trxtype = data.trxtype;
		    if (trxtype == 'PD') {
		        return {"class": "tr_pdclass"};
		    }
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_phar",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
			myfail_msg_phar.clear_fail();
			$('#qtyonhand_text_phar').text('');
			
			if($("#jqGrid_phar").data('lastselrow')==undefined||$("#jqGrid_phar").data('lastselrow')==null||$("#jqGrid_phar").data('lastselrow').includes("jqg")){
				$("#jqGrid_phar").setSelection($("#jqGrid_phar").getDataIDs()[0]);
			}else{
				$("#jqGrid_phar").setSelection($("#jqGrid_phar").data('lastselrow'));
			}
			$("#jqGrid_phar").data('lastselrow',null);
		},
		gridComplete: function(){
			fdl_ordcom.set_array().reset();
			myfail_msg_phar.clear_fail();
			$('#dosage_phar,#frequency_phar,#instruction_phar,#drugindicator_phar').prop('readonly', true);

			let justsave = $("#jqGrid_phar").data('justsave');

			if(justsave!=undefined && justsave!=null && justsave==1){
				delay(function(){
					$('#jqGrid_phar_iladd').click();
				}, 500 );
			}
			$("#jqGrid_phar").data('justsave','0');
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
		},
		onSelectRow:function(rowid){
			$('#jqGrid_phar_iledit,#jqGrid_phar_pagerDelete').hide();
			if($('#jqGrid_phar_iladd').hasClass('ui-disabled')){
				$('#jqGrid_phar_iledit,#jqGrid_phar_pagerDelete').hide();
			}else if(selrowData('#jqGrid_phar').trxtype == 'OE' || selrowData('#jqGrid_phar').trxtype == 'PK'){
				$('#jqGrid_phar_iledit,#jqGrid_phar_pagerDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGrid_phar').trxtype != 'PD'){
				$('#jqGrid_phar_iledit').click();
			}
		},
		subGridBeforeExpand(pID, id){
			if($("#jqGrid_phar").data('lastselrow')==id){
				return true;
			}else if($('#jqGrid_phar_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
		subGrid: needsubgrid(),
		subGridRowExpanded: function(subgrid_id, row_id) {
	    	var selrowdata = $('#jqGrid_phar').jqGrid ('getRowData', row_id);
			
	       	var subgrid_table_id;
	       	subgrid_table_id = subgrid_id+"_t";
	       	$("#"+subgrid_id).html(`
		       	<div id='jqgrid_detail_phar_`+row_id+`' class="panel panel-default jqgrid_detail" style="float:left;width:50%">
					<div class="panel-heading">
						<b><span>Chgcode </span>:<span class="label_d" id="jqgrid_detail_phar_chgcode_`+row_id+`"></span></b>
						<b><span>Description </span>:<span class="label_d" id="jqgrid_detail_phar_chgcode_desc_`+row_id+`"></span></b>
						<b><span>Department </span>:<span class="label_d" id="jqgrid_detail_phar_dept_`+row_id+`"></span></b><br>
					</div>
					<div class="panel-body">
						<b><span class="label_p">Doctor</span>:<span class="label_d" id="jqgrid_detail_phar_docname_`+row_id+`"></span></b><br>
						<b><span class="label_p">Cost Price</span>:<span class="label_d" id="jqgrid_detail_phar_cost_price_`+row_id+`"></span></b><br>
						<b><span class="label_p">Unit Price</span>:<span class="label_d" id="jqgrid_detail_phar_unitprice_`+row_id+`"></span></b><br>
						<b><span class="label_p">Discount Amount</span>:<span class="label_d" id="jqgrid_detail_phar_discamt_`+row_id+`"></span></b><br>
						<b><span class="label_p">Tax Amount</span>:<span class="label_d" id="jqgrid_detail_phar_taxamt_`+row_id+`"></span></b><br>
						<div class="row">
							<div class="col-md-2" style="padding:0px;min-width: 135px;">
								<b><span class="label_p">Dosage Text</span>:</b><br>
							</div>
							<div class="col-md-9" style="padding:0px">
								<input autocomplete="off" name="ftxtdosage_phar" id="ftxtdosage_phar_`+row_id+`" type="text" class="form-control input-sm" style="text-transform:uppercase;margin-bottom: 5px;">
							</div>
						</div>
					</div>
				</div>
				<div id='jqgrid_detail_phar2_`+row_id+`' class="panel panel-default jqgrid_detail" style="float:right;width:49%">
					<div class="panel-heading">
						<b>Dosage</b>
					</div>
					<div class="panel-body jqgrid_detail_dose">
						<div>
							<label class="oe_phar_label">Dose</label>
							<div class="input-group oe_phar_div" style="width: 100%;min-width: 200px;">
							<select id="dosage_phar_`+row_id+`" class="form-control input-sm" name="dosage" style="width: 100%;min-width: 200px;" optid="`+row_id+`">
							</select>
							</div>
							<input type="hidden" id="dosage_phar_code_`+row_id+`">
						</div>
						<div>
							<label class="oe_phar_label">Frequency</label>
							<div class="input-group oe_phar_div" style="width: 100%;min-width: 200px;">
							<select id="frequency_phar_`+row_id+`" class="form-control input-sm" name="frequency" style="width: 100%;min-width: 200px;" optid="`+row_id+`">
							</select>
							</div>
							<input type="hidden" id="frequency_phar_code_`+row_id+`">
						</div>
						<div>
							<label class="oe_phar_label">Instruction</label>
							<div class="input-group oe_phar_div" style="width: 100%;min-width: 200px;">
							<select id="instruction_phar_`+row_id+`" class="form-control input-sm" name="instruction" style="width: 100%;min-width: 200px;" optid="`+row_id+`">
							</select>
							</div>
							<input type="hidden" id="instruction_phar_code_`+row_id+`">
						</div>
						<div>
							<label class="oe_phar_label">Indicator</label>
							<div class="input-group oe_phar_div" style="width: 100%;min-width: 200px;">
							<select id="drugindicator_phar_`+row_id+`" class="form-control input-sm" name="drugindicator" style="width: 100%;min-width: 200px;" optid="`+row_id+`">
							</select>
							</div>
							<input type="hidden" id="drugindicator_phar_code_`+row_id+`">
						</div>
					</div>
				</div>
			`);

			dropdown_dosage_phar.on();
			dropdown_frequency_phar.on();
			dropdown_instruction_phar.on();
			dropdown_drugindicator_phar.on();

			write_detail_phar('clearall',null,row_id);
			write_detail_dosage('clearall');
			write_detail_phar([
				{span:'#jqgrid_detail_phar_chgcode_'+row_id,value:selrowdata.chgcode,rowid:row_id},
				{span:'#jqgrid_detail_phar_chgcode_desc_'+row_id,value:selrowdata.chgcode,rowid:row_id},
				{span:'#jqgrid_detail_phar_dept_'+row_id,value:selrowdata.deptcode,rowid:row_id},
				{span:'#jqgrid_detail_phar_cost_price_'+row_id,value:ret_parsefloat(selrowdata.cost_price),rowid:row_id},
				{span:'#jqgrid_detail_phar_unitprice_'+row_id,value:ret_parsefloat(selrowdata.unitprce),rowid:row_id},
				{span:'#jqgrid_detail_phar_discamt_'+row_id,value:ret_parsefloat(selrowdata.discamt),rowid:row_id},
				{span:'#jqgrid_detail_phar_taxamt_'+row_id,value:ret_parsefloat(selrowdata.taxamount),rowid:row_id},
			]);

			write_detail_dosage(selrowdata,false,row_id);
			

			calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		}
    });
	jqgrid_label_align_right("#jqGrid_phar");
	
	$("#jqGrid_phar").inlineNav('#jqGrid_phar_pager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_phar
		},
		editParams: myEditOptions_phar_edit,
			
	}).jqGrid('navButtonAdd', "#jqGrid_phar_pager", {	
		id: "jqGrid_phar_pagerDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGrid_phar").jqGrid('getGridParam', 'selrow');	
			if(selrowData('#jqGrid_phar').trxtype == 'PD'){
				return false;
			}
			if (!selRowId) {	
				alert('Please select row');
			} else {

				if (confirm("Are you sure you want to delete this row?") == true) {
				    let urlparam = {	
						action: 'order_entry',	
						oper: 'del',	
					};
					let urlobj={
						oper:'del',
						_token: $("#csrf_token").val(),
						id: selrowData('#jqGrid_phar').id
					};
					$.post( "./ordcom/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGrid_phar", urlParam_phar);	
					}).done(function (data) {	
						refreshGrid("#jqGrid_phar", urlParam_phar);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_phar_pager", {	
		id: "jqGrid_phar_pagerRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGrid_phar", urlParam_phar);	
		},	
	}).jqGrid('navButtonAdd', "#jqGrid_phar_pager", {	
		id: "jqGrid_phar_pagerFinalBill",	
		caption: "Final Bill", cursor: "pointer", position: "last",
		buttonicon: "",	
		title: "Final Bill",	
		onClickButton: function () {
			final_bill("#jqGrid_phar", urlParam_phar);
		},	
	});

});
	
var myEditOptions_phar = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_phar").data('lastselrow',rowid);
		myfail_msg_phar.clear_fail();

		collapseallsubgrid(rowid);

		var selrowdata = $('#jqGrid_phar').jqGrid ('getRowData', rowid);
		write_detail_dosage(selrowdata,true,rowid);

		errorField.length=0;
		$("#jqGrid_phar input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").hide();

		write_detail_phar('#jqgrid_detail_phar_dept','PHARMACY',rowid);
		$("#jqGrid_phar input[name='deptcode']").val($('#phardept_dflt').val());
		dialog_deptcode_phar.on();
		dialog_deptcode_phar.id_optid = rowid;
		dialog_deptcode_phar.check(errorField,rowid+"_deptcode","jqGrid_phar",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					dialog_chgcode_phar.urlParam.deptcode = data.deptcode;
	        	}
	        }
	    );

		dialog_chgcode_phar.on();
		dialog_uomcode_phar.on();
		dialog_uom_recv_phar.on();
		dialog_tax_phar.on();
		// dialog_dosage_phar.on();
		// dialog_frequency_phar.on();
		// dialog_instruction_phar.on();
		// dialog_drugindicator_phar.on();
		mycurrency_phar.array.length = 0;
		mycurrency_np_phar.array.length = 0;
		Array.prototype.push.apply(mycurrency_phar.array, ["#jqGrid_phar input[name='totamount']","#jqGrid_phar input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_phar.array, ["#jqGrid_phar input[name='quantity']"]);
		
		mycurrency_phar.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_phar.formatOnBlur();//make field to currency on leave cursor
		
		// $("#jqGrid_phar input[name='quantity']").on('keyup',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);
		$("#jqGrid_phar input[name='quantity']").on('blur',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);

		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		$("#jqGrid_phar input[name='trxdate']").on('focus',function(){
			// let focus = $(this).data('focus');
			// if(focus == undefined){
			// 	$(this).data('focus',1);
			// 	$("#jqGrid_phar input#"+rowid+"_chgcode").focus();
			// }
		});

		$("input[name='totamount']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid_phar_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	// $("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_phar.formatOff();
		mycurrency_np_phar.formatOff();

		if(parseInt($('#jqGrid_phar input[name="quantity"]').val()) == 0)return false;

		if(myfail_msg_phar.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid_();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    doscode: $("#dosage_phar_code_"+rowid).val(),
				frequency: $("#frequency_phar_code_"+rowid).val(),
				addinstruction: $("#instruction_phar_code_"+rowid).val(),
				drugindicator: $("#drugindicator_phar_code_"+rowid).val(),
				taxamount: $("#jqGrid_phar input[name='taxamount']").val(),
				discamt: $("#jqGrid_phar input[name='discamt']").val(),
				unitprce: $("#jqGrid_phar input[name='unitprce']").val(),
				ftxtdosage: $("#ftxtdosage_phar_"+rowid).val(),
				// totamount: $("#jqGrid_phar input[name='totamount']").val(),
			});
		$("#jqGrid_phar").jqGrid('setGridParam', { editurl: editurl });
		$("#jqGrid_phar").data('justsave','1');
	},
	afterrestorefunc : function( response ) {
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		myfail_msg_phar.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_phar')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_phar_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGrid_phar").data('lastselrow',rowid);
		collapseallsubgrid(rowid);
		var selrowdata = $('#jqGrid_phar').jqGrid ('getRowData', rowid);
		write_detail_dosage(selrowdata,true,rowid);

		$("#jqGrid_phar #"+rowid+"_discamt").val(selrowdata.discamt);
		$("#jqGrid_phar #"+rowid+"_taxamount").val(selrowdata.taxamount);

		myfail_msg_phar.clear_fail();
		$("#jqGrid_phar input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").hide();
		dialog_deptcode_phar.on();
		dialog_deptcode_phar.id_optid = rowid;
		dialog_deptcode_phar.skipfdl = true;
		dialog_deptcode_phar.check(errorField,rowid+"_deptcode","jqGrid_phar",null,
        	function(self){

	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					write_detail_phar('#jqgrid_detail_phar_dept',retdata['description'],self.id_optid);
	        	}
	        }
	    );

		dialog_chgcode_phar.on();
		dialog_chgcode_phar.id_optid = rowid;
		dialog_chgcode_phar.skipfdl = true;
		dialog_chgcode_phar.check(errorField,rowid+"_chgcode","jqGrid_phar",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phar input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					// $("#jqGrid_phar input[name='billtypeperct']").val(retdata['billty_percent']);
					// $("#jqGrid_phar input[name='billtypeamt']").val(retdata['billty_amount']);
					// $("#jqGrid_phar #"+rowid+"_unitprce").val(retdata['price']);
					write_detail_phar('#jqgrid_detail_phar_unitprice',retdata['price'],self.id_optid);
					write_detail_phar('#jqgrid_detail_phar_chgcode',retdata['chgcode'],self.id_optid);
					write_detail_phar('#jqgrid_detail_phar_chgcode_desc',retdata['description'],self.id_optid);
	        	}
	        }
	    );

		dialog_uomcode_phar.on();
		dialog_uomcode_phar.id_optid = rowid;
		dialog_uomcode_phar.skipfdl = true;
		dialog_uomcode_phar.check(errorField,rowid+"_uom","jqGrid_phar",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phar input[name='uom']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phar input[name='convfactor_uom']").val(retdata['convfactor']);
	        	}
	        }
	    );

		dialog_uom_recv_phar.on();
		dialog_uom_recv_phar.id_optid = rowid;
		dialog_uom_recv_phar.skipfdl = true;
		dialog_uom_recv_phar.check(errorField,rowid+"_uom_recv","jqGrid_phar",null,
        	function(self){
				self.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
				self.urlParam.price = 'PRICE2';
				self.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
				self.urlParam.billtype = $('#billtype_def_code').val();
				self.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
				self.urlParam.uom = $("#jqGrid_phar input[name='uom_recv']").val();
				self.urlParam.filterCol = ['cm.chggroup'];
				self.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phar input[name='convfactor_uom_recv']").val(retdata['convfactor']);
					$("#jqGrid_phar input[name='qtyonhand']").val(retdata['qtyonhand']);
	        	}
	        }
	    );

		dialog_tax_phar.on();
		dialog_tax_phar.id_optid = rowid;
		dialog_tax_phar.skipfdl = true;
		dialog_tax_phar.check(errorField,rowid+"_taxcode","jqGrid_phar",null,
        	function(self){
	        },function(data,self,id,fail){
	        	if(data.rows != undefined && data.rows.length>0){
	        		var retdata = data.rows[0];
					$("#jqGrid_phar #"+rowid+"_tax_rate").val(retdata['rate']);
	        	}
	        }
	    );

		// dialog_dosage_phar.on();
		// dialog_frequency_phar.on();
		// dialog_instruction_phar.on();
		// dialog_drugindicator_phar.on();

		mycurrency_phar.array.length = 0;
		mycurrency_np_phar.array.length = 0;
		Array.prototype.push.apply(mycurrency_phar.array, ["#jqGrid_phar input[name='totamount']","#jqGrid_phar input[name='amount']"]);
		Array.prototype.push.apply(mycurrency_np_phar.array, ["#jqGrid_phar input[name='quantity']"]);
		
		mycurrency_phar.formatOnBlur();//make field to currency on leave cursor
		mycurrency_np_phar.formatOnBlur();//make field to currency on leave cursor
		
		// $("#jqGrid_phar input[name='quantity']").on('keyup',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);
		$("#jqGrid_phar input[name='quantity']").on('blur',{currency: [mycurrency_phar,mycurrency_np_phar]},calculate_line_totgst_and_totamt_phar);

		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		
		$("#jqGrid_phar input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGrid_phar input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		set_ordcom_totamount(retval.totamount);
		// dialog_dosage_phar.off();
		// dialog_frequency_phar.off();
		// dialog_instruction_phar.off();
		// dialog_drugindicator_phar.off();
		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
		// dialog_dosage_phar.off();
		// dialog_frequency_phar.off();
		// dialog_instruction_phar.off();
		// dialog_drugindicator_phar.off();
    	alert(response.responseText);
    	// refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    	// $("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;
		mycurrency_phar.formatOff();
		mycurrency_np_phar.formatOff();

		if(parseInt($('#jqGrid_phar input[name="quantity"]').val()) == 0)return false;

		if(myfail_msg_phar.fail_msg_array.length>0){
			return false;
		}

		let rowdata = getrow_bootgrid_();

		let editurl = "./ordcom/form?"+
			$.param({
				action: 'order_entry',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    doscode: $("#dosage_phar_code_"+rowid).val(),
				frequency: $("#frequency_phar_code_"+rowid).val(),
				addinstruction: $("#instruction_phar_code_"+rowid).val(),
				drugindicator: $("#drugindicator_phar_code_"+rowid).val(),
				taxamount: $("#jqGrid_phar input[name='taxamount']").val(),
				discamt: $("#jqGrid_phar input[name='discamt']").val(),
				unitprce: $("#jqGrid_phar input[name='unitprce']").val(),
				ftxtdosage: $("#ftxtdosage_phar_"+rowid).val()
				// totamount: $("#jqGrid_phar input[name='totamount']").val(),
			});
		$("#jqGrid_phar").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
		// dialog_dosage_phar.off();
		// dialog_frequency_phar.off();
		// dialog_instruction_phar.off();
		// dialog_drugindicator_phar.off();
    	$("#jqGrid_phar_pagerRefresh,#jqGrid_phar_pagerDelete").show();
		myfail_msg_phar.clear_fail();
		errorField.length=0;
		// delay(function(){
		// 	fixPositionsOfFrozenDivs.call($('#jqGrid_phar')[0]);
		// }, 500 );
		calc_jq_height_onchange("jqGrid_phar",true,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
		refreshGrid('#jqGrid_phar',urlParam_phar,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};


function calculate_line_totgst_and_totamt_phar(event) {
	event.data.currency.forEach(function(element){
		element.formatOff();
	});

	var optid = event.currentTarget.id;
	var id_optid = optid.substring(0,optid.search("_"));
   
	let quantity = parseFloat($("#"+id_optid+"_quantity").val());

	if(quantity==0 || quantity==''){
		myfail_msg_phar.add_fail({
			id:'quantity',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}else{
		myfail_msg_phar.del_fail({
			id:'quantity',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity must be greater than 0",
		});
	}

	let convfactor_uom = parseFloat($("#jqGrid_phar #"+id_optid+"_convfactor_uom").val());
	let convfactor_uom_recv = parseFloat($("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val());
	var balconv = convfactor_uom*quantity%convfactor_uom_recv;

	let qtyonhand = parseFloat($("#"+id_optid+"_qtyonhand").val());
	let real_quantity = convfactor_uom*quantity;
	let st_idno = $("#jqGrid_phar #"+id_optid+"_chgcode").data('st_idno');

	if(qtyonhand<real_quantity && st_idno!=''){
		myfail_msg_phar.add_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}else{
		myfail_msg_phar.del_fail({
			id:'qtyonhand',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Quantity greater than quantity on hand",
		});
	}

	if (balconv != 0) {
		myfail_msg_phar.add_fail({
			id:'convfactor',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	} else {
		myfail_msg_phar.del_fail({
			id:'convfactor',
			textfld:"#jqGrid_phar #"+id_optid+"_quantity",
			msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
		});
	}

	let unitprce = parseFloat($("#"+id_optid+"_unitprce").val());
	let billtypeperct = 100 - parseFloat($("#"+id_optid+"_billtypeperct").val());
	let billtypeamt = parseFloat($("#"+id_optid+"_billtypeamt").val());
	let rate =  parseFloat($("#"+id_optid+"_tax_rate").val());
	if(isNaN(rate)){
		rate = 0;
	}

	var discamt = calc_discamt_main($('#jqGrid_ordcom_c #ordcomtt_phar').val(),$("#jqGrid_phar #"+id_optid+"_chgcode").val(),unitprce,quantity);
	var amount = (unitprce*quantity);

	let taxamount = (amount + discamt) * rate / 100;

	var totamount = amount + discamt + taxamount;

	$("#"+id_optid+"_discamt").val(discamt);
	$("#"+id_optid+"_amount").val(amount);
	$("#"+id_optid+"_taxamount").val(taxamount);
	$("#"+id_optid+"_totamount").val(totamount);

	write_detail_phar('#jqgrid_detail_phar_taxamt',taxamount,id_optid);
	write_detail_phar('#jqgrid_detail_phar_discamt',discamt,id_optid);
	
	var id="#jqGrid_phar #"+id_optid+"_quantity";
	var name = "quantityrequest";
	var fail_msg = "Quantity must be greater than 0";

	event.data.currency.forEach(function(element){
		element.formatOn();
	});
}

var dialog_deptcode_phar = new ordialog(
	'deptcode_phar',['sysdb.department'],"#jqGrid_phar input[name='deptcode']",errorField,
	{	colModel:
		[
			{label:'Department Code', name:'deptcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus','chgdept'],
					filterVal:['session.compcode','ACTIVE','1']
				},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}
			let data=selrowData('#'+dialog_deptcode_phar.gridname);
			dialog_chgcode_phar.urlParam.deptcode = data.deptcode;
			dialog_uomcode_phar.urlParam.deptcode = data.deptcode;
			dialog_uom_recv_phar.urlParam.deptcode = data.deptcode;

			write_detail_phar('#jqgrid_detail_phar_dept',data.description,id_optid);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Department Code",
		open:function(obj_){

			dialog_deptcode_phar.urlParam.filterCol=['compcode','recstatus','chgdept'];
			dialog_deptcode_phar.urlParam.filterVal=['session.compcode','ACTIVE','1'];
		},
		close: function(){
			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_deptcode_phar.makedialog(false);

var dialog_chgcode_phar = new ordialog(
	'chgcode_phar',['material.stockloc AS s','material.product AS p','hisdb.chgmast AS c'],"#jqGrid_phar input[name='chgcode']",errorField,
	{	colModel:
		[
			{label: 'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label: 'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
			{label: 'UOM',name:'uom',width:100,classes:'pointer',},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100,classes:'pointer',},
			{label: 'Price',name:'price',width:100,classes:'pointer'},
			{label: 'Tax',name:'taxcode',width:100,classes:'pointer'},
			{label: 'rate',name:'rate',hidden:true},
			{label: 'st_idno',name:'st_idno',hidden:true},
			{label: 'pt_idno',name:'pt_idno',hidden:true},
			{label: 'avgcost',name:'avgcost',hidden:true},
			{label: 'billty_amount',name:'billty_amount',hidden:true},
			{label: 'billty_percent',name:'billty_percent',hidden:true},
			{label: 'convfactor',name:'convfactor',hidden:true},
			
		],
		sortname: 'cm.uom',
		sortorder: 'asc',
		urlParam: {
				url:"./SalesOrderDetail/table",
				action: 'get_itemcode_price',
				url_chk: './ordcom/table',
				action_chk: 'get_itemcode_price_check',
				price : 'PRICE2',
				entrydate : moment().format('YYYY-MM-DD'),
				billtype : $('#billtype_def_code').val(),
				mrn : urlParam_phar.mrn,
				episno : urlParam_phar.episno,
				uom : $("#jqGrid_phar input[name='uom']").val(),
				deptcode : $('#phardept_dflt').val(),
				filterCol : ['cm.chggroup'],
				filterVal : [$('#jqGrid_ordcom_c #ordcomtt_phar').val()],
			},
		ondblClickRow:function(event){
			if(event.type == 'keydown'){
				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{
				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_phar.del_fail({
				id:'quantity',
				textfld:"#jqGrid_phar #"+id_optid+"_quantity",
				msg:"Quantity must be greater than 0",
			});
			myfail_msg_phar.del_fail({
				id:'qtyonhand',
				textfld:"#jqGrid_phar #"+id_optid+"_quantity",
				msg:"Quantity greater than quantity on hand",
			});
			myfail_msg_phar.del_fail({
				id:'convfactor',
				textfld:"#jqGrid_phar #"+id_optid+"_quantity",
				msg:"Please Choose Suitable UOM Code & UOM Code Store Dept",
			});
			myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
			myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_chgcode_phar.gridname);
			if(data.qtyonhand){
				$('#qtyonhand_text_phar').text('Qty on hand : '+data.qtyonhand);
			}else{
				$('#qtyonhand_text_phar').text('');
			}

			$("#jqGrid_phar #"+id_optid+"_chgcode").val(data['chgcode']);
			write_detail_phar('#jqgrid_detail_phar_chgcode',data['chgcode'],id_optid);
			write_detail_phar('#jqgrid_detail_phar_chgcode_desc',data['description'],id_optid);
			$("#jqGrid_phar #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_phar #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val(data['convfactor']);

			dialog_chgcode_phar.urlParam.uom = data['uom'];

			dialog_uomcode_phar.urlParam.chgcode = data['chgcode'];
			dialog_uomcode_phar.urlParam.uom = data['uom'];
			$("#jqGrid_phar #"+id_optid+"_uom").val(data['uom']);
			dialog_uomcode_phar.id_optid = id_optid;
			dialog_uomcode_phar.skipfdl = true;
			dialog_uomcode_phar.check(errorField,id_optid+"_uom","jqGrid_phar",null,null,
				function(data,self,id,fail){
		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_phar input[name='convfactor_uom']").val(retdata['convfactor']);
		        	}
		        }
		    );

			dialog_uom_recv_phar.urlParam.chgcode = data['chgcode'];
			dialog_uom_recv_phar.urlParam.uom = data['uom'];
			$("#jqGrid_phar #"+id_optid+"_uom_recv").val(data['uom']);
			dialog_uom_recv_phar.id_optid = id_optid;
			dialog_uom_recv_phar.skipfdl = true;
			dialog_uom_recv_phar.check(errorField,id_optid+"_uom_recv","jqGrid_phar",null,
	        	function(self){
					self.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
					self.urlParam.price = 'PRICE2';
					self.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
		        },
	        	function(data,self,id,fail){
					myfail_msg_phar.del_fail({id:'nostock_'+self.id_optid});

		        	if(data.rows != undefined && data.rows.length>0){
		        		var retdata = data.rows[0];
						$("#jqGrid_phar input[name='convfactor_uom_recv']").val(retdata['convfactor']);
						$("#jqGrid_phar input[name='qtyonhand']").val(retdata['qtyonhand']);
						write_detail_phar('#jqgrid_detail_phar_cost_price',retdata['avgcost'],self.id_optid);
						if(retdata.invflag == '1' && (retdata.st_idno == '' || retdata.st_idno == null)){
							myfail_msg_phar.add_fail({
								id:'nostock_'+self.id_optid,
								textfld:"#jqGrid_phar #"+self.id_optid+"_uom_recv",
								msg:'Selected Item ('+$("#jqGrid_phar input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_phar input[name='deptcode']").val(),
							});

							$("#jqGrid_phar #"+self.id_optid+"_convfactor_uom_recv").val('');
							$("#jqGrid_phar #"+self.id_optid+"_qtyonhand").val('');
							$("#jqGrid_phar #"+self.id_optid+"_quantity").val('');
							$("#jqGrid_phar #"+self.id_optid+"_cost_price").val('');

						}
		        	}
		        }
		    );

			$("#jqGrid_phar #"+id_optid+"_unitprce").val(data['price']);
			write_detail_phar('#jqgrid_detail_phar_unitprice',data['price'],id_optid);
			$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);

			dialog_tax_phar.check(errorField);

		},
		gridComplete: function(obj){
		},
		loadComplete:function(data){

		}
	},{
		title:"Select Item For Sales Order",
		open:function(obj_){
			let id_optid = obj_.id_optid;
			dialog_chgcode_phar.urlParam.url = "./SalesOrderDetail/table";
			dialog_chgcode_phar.urlParam.action = 'get_itemcode_price';
			dialog_chgcode_phar.urlParam.url_chk = "./ordcom/table";
			dialog_chgcode_phar.urlParam.action_chk = "get_itemcode_price_check";
			dialog_chgcode_phar.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
			dialog_chgcode_phar.urlParam.price = 'PRICE2';
			dialog_chgcode_phar.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
			dialog_chgcode_phar.urlParam.billtype = $('#billtype_def_code').val();
			dialog_chgcode_phar.urlParam.mrn = urlParam_phar.mrn;
			dialog_chgcode_phar.urlParam.episno = urlParam_phar.episno;
			dialog_chgcode_phar.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
			dialog_chgcode_phar.urlParam.uom = $("#jqGrid_phar input[name='uom']").val();
			dialog_chgcode_phar.urlParam.filterCol = ['cm.chggroup'];
			dialog_chgcode_phar.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
		},
		close: function(obj){
			$("#jqGrid_phar input[name='quantity']").focus().select();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam','radio','tab'//urlParam means check() using urlParam not check_input
);
dialog_chgcode_phar.makedialog(false);

var dialog_uomcode_phar = new ordialog(
	'uom_phar',['material.uom AS u'],"#jqGrid_phar input[name='uom']",errorField,
	{	colModel:
		[
			{label: 'UOM Code',name:'uomcode',width:100,classes:'pointer',canSearch:true,or_search:true},
			{label: 'UOM Description',name:'description',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Charge Code',name:'chgcode',width:100},
			{label: 'Charge Description',name:'chgdesc',width:300},
			{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100},
			{label: 'Price',name:'price',width:100},
			{label: 'Tax',name:'taxcode',hidden:true},
			{label: 'rate',name:'rate',hidden:true},
			{label: 'st_idno',name:'st_idno',hidden:true},
			{label: 'pt_idno',name:'pt_idno',hidden:true},
			{label: 'avgcost',name:'avgcost',hidden:true},
			{label: 'billty_amount',name:'billty_amount',hidden:true},
			{label: 'billty_percent',name:'billty_percent',hidden:true},
			{label: 'convfactor',name:'convfactor',hidden:true},
		],
		urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE'],
					url:"./SalesOrderDetail/table",
					url_chk:"./SalesOrderDetail/table",
					action: 'get_itemcode_uom',
					action_chk: 'get_itemcode_uom_check_oe',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $('#phardept_dflt').val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#jqGrid_ordcom_c #ordcomtt_phar').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			myfail_msg_phar.del_fail({id:'noprod_'+id_optid});
			myfail_msg_phar.del_fail({id:'nostock_'+id_optid});

			let data=selrowData('#'+dialog_uomcode_phar.gridname);
			dialog_chgcode_phar.urlParam.uom = data['uom'];

			$("#jqGrid_phar #"+id_optid+"_chgcode").val(data['chgcode']);
			$("#jqGrid_phar #"+id_optid+"_taxcode").val(data['taxcode']);
			$("#jqGrid_phar #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phar #"+id_optid+"_convfactor_uom").val(data['convfactor']);
			$("#jqGrid_phar #"+id_optid+"_uom").val(data['uomcode']);
			if(data['qtyonhand']!= null && parseInt(data['qtyonhand'] > 0)){
				$("#jqGrid_phar #"+id_optid+"_uom_recv").val(data['uomcode']);
			}
			$("#jqGrid_phar #"+id_optid+"_unitprce").val(data['price']);
			write_detail_phar('#jqgrid_detail_phar_unitprice',data['price'],id_optid);
			$("#jqGrid_phar #"+id_optid+"_billtypeperct").val(data['billty_percent']);
			$("#jqGrid_phar #"+id_optid+"_billtypeamt").val(data['billty_amount']);
			$("#jqGrid_phar #"+id_optid+"_quantity").val('');
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phar input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Item",
		open:function(obj_){
			let chgcode = $("#jqGrid_phar input[name=chgcode]").val();
			let chgdesc = $("#jqGrid_phar input[name=chgcode]").parent().next().text();
			$('div[role=dialog][aria-describedby=otherdialog_uom_phar] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+') '+chgdesc+' ');

			let id_optid = obj_.id_optid;

			dialog_uomcode_phar.urlParam.url = "./SalesOrderDetail/table";
			dialog_uomcode_phar.urlParam.action = 'get_itemcode_uom';
			dialog_uomcode_phar.urlParam.url_chk = "./SalesOrderDetail/table";
			dialog_uomcode_phar.urlParam.action_chk = "get_itemcode_uom_check_oe";
			dialog_uomcode_phar.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
			dialog_uomcode_phar.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
			dialog_uomcode_phar.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
			dialog_uomcode_phar.urlParam.uom = $("#jqGrid_phar input[name='uom']").val();
			dialog_uomcode_phar.urlParam.price = 'PRICE2';
			dialog_uomcode_phar.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uomcode_phar.urlParam.filterCol = ['cm.chggroup'];
			dialog_uomcode_phar.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
		},
		close: function(){
			$("#jqGrid_phar input[name='quantity']").focus().select();
			// $(dialog_uomcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uomcode_phar.makedialog(false);

var dialog_uom_recv_phar = new ordialog(
	'uom_recv_phar',['material.uom AS u'],"#jqGrid_phar input[name='uom_recv']",errorField,
	{	colModel:
		[
			{label:'UOM code',name:'uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			{label: 'Charge Code',name:'chgcode',width:100},
			{label: 'Charge Description',name:'chgdesc',width:300},
			{label: 'Inventory',name:'invflag',width:100,formatter:formatterstatus_tick2, unformat:unformatstatus_tick2},
			{label: 'Quantity On Hand',name:'qtyonhand',width:100},
			{label:'Inventory',name:'invflag',hidden:true},
			{label:'st_idno',name:'st_idno',hidden:true},
			{label:'pt_idno',name:'pt_idno',hidden:true},
			{label:'avgcost',name:'avgcost',hidden:true},
			{label:'billty_amount',name:'billty_amount',hidden:true},
			{label:'billty_percent',name:'billty_percent',hidden:true},
			{label:'convfactor',name:'convfactor',hidden:true},
			{label:'qtyonhand',name:'qtyonhand',hidden:true},
		],
		urlParam: {
					url:"./ordcom/table",
					url_chk:"./ordcom/table",
					action: 'get_itemcode_uom_recv',
					action_chk: 'get_itemcode_uom_recv_check',
					entrydate : moment().format('YYYY-MM-DD'),
					deptcode : $('#phardept_dflt').val(),
					chgcode : null,
					uom:null,
					billtype : $('#billtype_def_code').val(),
					price : 'PRICE2',
					filterCol : ['cm.chggroup'],
					filterVal : [$('#jqGrid_ordcom_c #ordcomtt_phar').val()],
				},
		ondblClickRow:function(event){

			if(event.type == 'keydown'){

				var optid = $(event.currentTarget).get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}else{

				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
				var id_optid = optid.substring(0,optid.search("_"));
			}

			let data=selrowData('#'+dialog_uom_recv_phar.gridname);

			myfail_msg_phar.del_fail({id:'nostock_'+id_optid});
			$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val(data['convfactor']);
			if(data.invflag == '1' && (data.st_idno == '' || data.st_idno == null)){
				myfail_msg_phar.add_fail({
					id:'nostock_'+id_optid,
					textfld:"#jqGrid_phar #"+id_optid+"_uom_recv",
					msg:'Selected Item ('+$("#jqGrid_phar input[name='chgcode']").val()+') doesnt have Stock location at department: '+$("#jqGrid_phar input[name='deptcode']").val(),
				});

				$("#jqGrid_phar #"+id_optid+"_convfactor_uom_recv").val('');
				$("#jqGrid_phar #"+id_optid+"_qtyonhand").val('');
				$("#jqGrid_phar #"+id_optid+"_quantity").val('');
				$("#jqGrid_phar #"+id_optid+"_cost_price").val('');
			}
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$("#jqGrid_phar input[name='qty']").focus();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select UOM Code For Dept Item",
		open:function(obj_){

			let chgcode = $("#jqGrid_phar input[name=chgcode]").val();
			let chgdesc = $("#jqGrid_phar input[name=chgcode]").parent().next().text();
			$('div[role=dialog][aria-describedby=otherdialog_uom_phar] span.ui-dialog-title').text('Select UOM Code For Item ('+chgcode+') '+chgdesc+' ');

			dialog_uom_recv_phar.urlParam.url = "./ordcom/table";
			dialog_uom_recv_phar.urlParam.action = 'get_itemcode_uom_recv';
			dialog_uom_recv_phar.urlParam.url_chk = "./ordcom/table";
			dialog_uom_recv_phar.urlParam.action_chk = "get_itemcode_uom_recv_check";
			dialog_uom_recv_phar.urlParam.entrydate = $("#jqGrid_phar input[name='trxdate']").val();
			dialog_uom_recv_phar.urlParam.chgcode = $("#jqGrid_phar input[name='chgcode']").val();
			dialog_uom_recv_phar.urlParam.deptcode = $("#jqGrid_phar input[name='deptcode']").val();
			dialog_uom_recv_phar.urlParam.price = 'PRICE2';
			dialog_uom_recv_phar.urlParam.uom = $("#jqGrid_phar input[name='uom_recv']").val();
			dialog_uom_recv_phar.urlParam.billtype = $('#billtype_def_code').val();
			dialog_uom_recv_phar.urlParam.filterCol = ['cm.chggroup'];
			dialog_uom_recv_phar.urlParam.filterVal = [$('#jqGrid_ordcom_c #ordcomtt_phar').val()];
		},
		close: function(){
			$("#jqGrid_phar input[name='quantity']").focus().select();
			// $(dialog_uomcode_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		after_check: function(data,self,id,fail){
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_uom_recv_phar.makedialog(false);

var dialog_tax_phar = new ordialog(
	'taxcode_phar',['hisdb.taxmast'],"#jqGrid_phar input[name='taxcode']",errorField,
	{	colModel:
		[
			{label:'Tax Code', name:'taxcode', width:200, classes:'pointer', canSearch:true, or_search:true},
			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
			{label:'Rate', name:'rate', width:100, classes:'pointer'},
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
			let data=selrowData('#'+dialog_tax_phar.gridname);
			$("#jqGrid_phar #"+id_optid+"_tax_rate").val(data['rate']);
			$("#jqGrid_phar input#"+id_optid+"_taxcode").val(data.taxcode);
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$(obj.textfield).closest('td').next().find("input[type=text]").focus();
			}
		}
		
	},{
		title:"Select Tax Code For Item",
		check_take_all_field : true,
		open:function(obj_){
			dialog_tax_phar.urlParam.filterCol=['compcode','recstatus'];
			dialog_tax_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
		},
		close: function(){
			$("#jqGrid_phar input[name='quantity']").focus().select();
			// $(dialog_tax_phar.textfield)			//lepas close dialog focus on next textfield 
			// 	.closest('td')						//utk dialog dalam jqgrid jer
			// 	.next()
			// 	.find("input[type=text]").focus();
		},
		justb4refresh: function(obj_){
			obj_.urlParam.searchCol2=[];
			obj_.urlParam.searchVal2=[];
		},
		justaftrefresh: function(obj_){
			$("#Dtext_"+obj_.unique).val('');
		}
	},'urlParam', 'radio', 'tab' 	
);
dialog_tax_phar.makedialog(false);

// var dialog_dosage_phar = new ordialog(
// 	'dosage_phar',['hisdb.dose'],"#jqGrid_phar input[name=dosage]",'errorField',
// 	{	colModel:
// 		[
// 			{label:'Dosage Code', name:'dosecode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'dosedesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){

// 			if(event.type == 'keydown'){

// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}else{

// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}

// 			let data=selrowData('#'+dialog_dosage_phar.gridname);
// 			$(dialog_dosage_phar.textfield).val(data.dosedesc);
// 			$('#dosage_phar_code_'+optid).val(data.dosecode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_dosage_phar.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_dosage_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		},
// 		justb4refresh: function(obj_){
// 			obj_.urlParam.searchCol2=[];
// 			obj_.urlParam.searchVal2=[];
// 		},
// 		justaftrefresh: function(obj_){
// 			$("#Dtext_"+obj_.unique).val('');
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_dosage_phar.makedialog(false);

// var dialog_frequency_phar = new ordialog(
// 	'freq_phar',['hisdb.freq'],"#jqGrid_phar input[name=frequency]",'errorField',
// 	{	colModel:
// 		[
// 			{label:'Frequency Code', name:'freqcode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'freqdesc', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			if(event.type == 'keydown'){

// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}else{

// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}
// 			let data=selrowData('#'+dialog_frequency_phar.gridname);
// 			$(dialog_frequency_phar.textfield).val(data.freqdesc);
// 			$('#frequency_phar_code_'+optid).val(data.freqcode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_frequency_phar.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_frequency_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		},
// 		justb4refresh: function(obj_){
// 			obj_.urlParam.searchCol2=[];
// 			obj_.urlParam.searchVal2=[];
// 		},
// 		justaftrefresh: function(obj_){
// 			$("#Dtext_"+obj_.unique).val('');
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_frequency_phar.makedialog(false);

// var dialog_instruction_phar = new ordialog(
// 	'instruction_phar',['hisdb.instruction'],"#jqGrid_phar input[name=instruction]",'errorField',
// 	{	colModel:
// 		[
// 			{label:'Dosage Code', name:'inscode', width:200, classes:'pointer', canSearch:true, or_search:true},
// 			{label:'Description', name:'description', width:400, classes:'pointer', canSearch:true, checked:true, or_search:true},
// 		],
// 		urlParam: {
// 					filterCol:['compcode','recstatus'],
// 					filterVal:['session.compcode','ACTIVE']
// 				},
// 		ondblClickRow:function(event){
// 			if(event.type == 'keydown'){

// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}else{

// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
// 				var id_optid = optid.substring(0,optid.search("_"));
// 			}
// 			let data=selrowData('#'+dialog_instruction_phar.gridname);
// 			$(dialog_instruction_phar.textfield).val(data.description);
// 			$('#instruction_phar_code_'+optid).val(data.inscode);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing == true){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 			}
// 		}
		
// 	},{
// 		title:"Select Tax Code For Item",
// 		open:function(obj_){
// 			dialog_instruction_phar.urlParam.filterCol=['compcode','recstatus'];
// 			dialog_instruction_phar.urlParam.filterVal=['session.compcode','ACTIVE'];
// 		},
// 		close: function(){
// 			// $(dialog_deptcode_phar.textfield)			//lepas close dialog focus on next textfield 
// 			// 	.closest('td')						//utk dialog dalam jqgrid jer
// 			// 	.next()
// 			// 	.find("input[type=text]").focus();
// 		},
// 		justb4refresh: function(obj_){
// 			obj_.urlParam.searchCol2=[];
// 			obj_.urlParam.searchVal2=[];
// 		},
// 		justaftrefresh: function(obj_){
// 			$("#Dtext_"+obj_.unique).val('');
// 		}
// 	},'urlParam', 'radio', 'tab' 	
// );
// dialog_instruction_phar.makedialog(false);

var dropdown_dosage_phar = new ordropdown("#jqGrid_phar select[name=dosage]",{
	ajax: {
	    url: './util/get_table_default',delay: 250,
	    data: function (params) {
	      var query = {
	        table_name: ['hisdb.dose'],
	        rows: 20,
			page: params.page || 1,
			sidx: null,
			sord: null,
	        field: ['dosecode','dosedesc'],
	        filterCol:['compcode','recstatus'],
	        filterVal:['session.compcode','ACTIVE']
	      }

	      if(params.term != undefined){
	      	query.searchCol = ['dosedesc'];
	      	query.searchVal = ['%'+params.term+'%'];
	      }

	      // Query parameters will be ?search=[term]&page=[page]
	      return query;
	    },
	    processResults: function (data, params) {
	    	let more=false;let result=[];
	    	let json = JSON.parse(data);
	    	let page = params.page || 1;
	    	if(page < json.total){
	    		more=true;
	    	}
	    	result = json.rows.map(function(e,i){
	    		return {id:e.dosecode,text:e.dosedesc};
	    	});
		    return {
				"results":  result,
				"pagination": {
					"more": more
				}
			};
		}
  	},
  	templateSelection: function(state){
  		var optid = $(state.element).parent('select').attr('optid');
  		$('#dosage_phar_code_'+optid).val(state.id);

  		return state.text;
  	}
});

var dropdown_frequency_phar = new ordropdown("#jqGrid_phar select[name=frequency]",{
	ajax: {
	    url: './util/get_table_default',delay: 250,
	    data: function (params) {
	      var query = {
	        table_name: ['hisdb.freq'],
	        rows: 20,
			page: params.page || 1,
			sidx: null,
			sord: null,
	        field: ['freqcode','freqdesc'],
	        filterCol:['compcode','recstatus'],
	        filterVal:['session.compcode','ACTIVE']
	      }

	      if(params.term != undefined){
	      	query.searchCol = ['freqdesc'];
	      	query.searchVal = ['%'+params.term+'%'];
	      }

	      // Query parameters will be ?search=[term]&page=[page]
	      return query;
	    },
	    processResults: function (data, params) {
	    	let more=false;let result=[];
	    	let json = JSON.parse(data);
	    	let page = params.page || 1;
	    	if(page < json.total){
	    		more=true;
	    	}
	    	result = json.rows.map(function(e,i){
	    		return {id:e.freqcode,text:e.freqdesc};
	    	});
		    return {
				"results":  result,
				"pagination": {
					"more": more
				}
			};
		}
  	},
  	templateSelection: function(state){
  		var optid = $(state.element).parent('select').attr('optid');
  		$('#frequency_phar_code_'+optid).val(state.id);

  		return state.text;
  	}
});

var dropdown_instruction_phar = new ordropdown("#jqGrid_phar select[name=instruction]",{
	ajax: {
	    url: './util/get_table_default',delay: 250,
	    data: function (params) {
	      var query = {
	        table_name: ['hisdb.instruction'],
	        rows: 20,
			page: params.page || 1,
			sidx: null,
			sord: null,
	        field: ['inscode','description'],
	        filterCol:['compcode','recstatus'],
	        filterVal:['session.compcode','ACTIVE']
	      }

	      if(params.term != undefined){
	      	query.searchCol = ['description'];
	      	query.searchVal = ['%'+params.term+'%'];
	      }

	      // Query parameters will be ?search=[term]&page=[page]
	      return query;
	    },
	    processResults: function (data, params) {
	    	let more=false;let result=[];
	    	let json = JSON.parse(data);
	    	let page = params.page || 1;
	    	if(page < json.total){
	    		more=true;
	    	}
	    	result = json.rows.map(function(e,i){
	    		return {id:e.inscode,text:e.description};
	    	});
		    return {
				"results":  result,
				"pagination": {
					"more": more
				}
			};
		}
  	},
  	templateSelection: function(state){
  		var optid = $(state.element).parent('select').attr('optid');
  		$('#instruction_phar_code_'+optid).val(state.id);

  		return state.text;
  	}
});

function needsubgrid(){
	return true;
	if($('#ordcom_phase').val() == '2'){
		return false;
	}else{
		return true;
	}
}

var dropdown_drugindicator_phar = new ordropdown("#jqGrid_phar select[name=drugindicator]",{
	ajax: {
	    url: './util/get_table_default',delay: 250,
	    data: function (params) {
	      var query = {
	        table_name: ['hisdb.drugindicator'],
	        rows: 20,
			page: params.page || 1,
			sidx: null,
			sord: null,
	        field: ['drugindcode','description'],
	        filterCol:['compcode','recstatus'],
	        filterVal:['session.compcode','ACTIVE']
	      }

	      if(params.term != undefined){
	      	query.searchCol = ['description'];
	      	query.searchVal = ['%'+params.term+'%'];
	      }

	      // Query parameters will be ?search=[term]&page=[page]
	      return query;
	    },
	    processResults: function (data, params) {
	    	let more=false;let result=[];
	    	let json = JSON.parse(data);
	    	let page = params.page || 1;
	    	if(page < json.total){
	    		more=true;
	    	}
	    	result = json.rows.map(function(e,i){
	    		return {id:e.drugindcode,text:e.description};
	    	});
		    return {
				"results":  result,
				"pagination": {
					"more": more
				}
			};
		}
  	},
  	templateSelection: function(state){
  		var optid = $(state.element).parent('select').attr('optid');
  		$('#drugindicator_phar_code_'+optid).val(state.id);

  		return state.text;
  	}
});

function trxdateCustomEdit_phar(val, opt) {
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $('<div class=""><input jqgrid="jqGrid_phar" optid="'+opt.id+'" id="'+opt.id+'" name="trxdate" type="date" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0" autocomplete="off" ></div>');
}
function itemcodeCustomEdit_phar(val, opt) {
	// val = getEditVal(val);
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	var id_optid = opt.id.substring(0,opt.id.search("_"));
	var myreturn = '<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>';

	// myreturn += `<input type='hidden' name='unitprce' id='`+id_optid+`_unitprce'>`;
	myreturn += `<div><input type='hidden' name='tax_rate' id='`+id_optid+`_tax_rate'>`;
	myreturn += `<input type='hidden' name='qtyonhand' id='`+id_optid+`_qtyonhand'>`;
	myreturn += `<input type='hidden' name='convfactor_uom' id='`+id_optid+`_convfactor_uom'>`;
	myreturn += `<input type='hidden' name='convfactor_uom_recv' id='`+id_optid+`_convfactor_uom_recv'></div>`;
	return $(myreturn);
}
function totamountFormatter_phar(val,opt,rowObject ){
	let totamount = ret_parsefloat(rowObject.amount) + ret_parsefloat(rowObject.discamt) + ret_parsefloat(rowObject.taxamount);
	return numeral(totamount).format('0,0.00');
}
function uomcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function uom_recvCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="uom_recv" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function taxcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function deptcodeCustomEdit_phar(val,opt){  	
	val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
	return $(`<div class="input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
}
function remarkCustomEdit_phar(val,opt){
	var myreturn = `<label class='oe_phar_label'>Dose</label><div class="oe_phar_div input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="dosage" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phar_label'>Frequency</label><div class="oe_phar_div input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="frequency" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phar_label'>Instruction</label><div class="oe_phar_div input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="instruction" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`;
	myreturn += `<label class='oe_phar_label'>Indicator</label><div class="oe_phar_div input-group"><input autocomplete="off" jqgrid="jqGrid_phar" optid="`+opt.id+`" id="`+opt.id+`" name="drugindicator" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>`

	return $(myreturn);
}

function galGridCustomValue_phar (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function showdetail_phar(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'chgcode':field=['chgcode','description'];table="hisdb.chgmast";case_='chgcode';break;
		case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'uom_recv':field=['uomcode','description'];table="material.uom";case_='uom';break;
		case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;
		case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	if(cellvalue != null && cellvalue.trim() != ''){
		fdl_ordcom.get_array('ordcom',options,param,case_,cellvalue);
	}
	
	if(cellvalue == null)cellvalue = " ";
	calc_jq_height_onchange("jqGrid_phar",false,parseInt($('#jqGrid_ordcom_c').prop('clientHeight'))-241);
	return cellvalue;
}

function cust_rules_phar(value, name) {
	var temp=null;
	switch (name) {
		case 'Dept. Code': temp = $("#jqGrid_phar input[name='deptcode']"); break;
		case 'Item Code': temp = $("#jqGrid_phar input[name='chgcode']"); break;
		case 'UOM Code': temp = $("#jqGrid_phar input[name='uom']"); break;
		case 'UOM Code<br/>Store Dept.': temp = $("#jqGrid_phar input[name='uom_recv']"); break;
		case 'Price Code': temp = $("#jqGrid_phar input[name='pricecode']"); break;
		case 'Tax': temp = $("#jqGrid_phar input[name='taxcode']"); break;
		case 'Quantity': temp = $("#jqGrid_phar input[name='quantity']");break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}

function write_detail_phar(span,value,rowid){
	if(span == 'clearall'){
		var lastrowdata = getrow_bootgrid_();
		$('#jqgrid_detail_phar_'+rowid+' span.label_d').text('');
		$('#jqgrid_detail_phar_docname_'+rowid).text(lastrowdata.q_doctorname);
	}else if(Array.isArray(span)){
		span.forEach(function(e,i){
			let textval = e.value;
			if(e.span.includes("#jqgrid_detail_phar_chgcode_desc")){
				let pos_1 = e.value.search("[>]")+1;
				let pos_2 = get_char_str_pos(e.value, '<', 2)
				if(e.value.search("[<]") != -1){
					textval = e.value.slice(pos_1,pos_2);
				}
				$(e.span).text(textval);
			}else if(e.span.includes("#jqgrid_detail_phar_chgcode")||e.span.includes("#jqgrid_detail_phar_dept")){
				if(e.value.search("[<]") != -1){
					textval = e.value.slice(0, e.value.search("[<]"));
				}
				$(e.span).text(textval);
			}else{ // ini je perlu format, yang len chgcode, desc dan dept
				$(e.span).text(numeral(textval).format('0,0.00'));
			}
		});
	}else{
		let textval = value;
		if(span.includes("#jqgrid_detail_phar_chgcode")||span.includes("#jqgrid_detail_phar_dept")||span.includes("#jqgrid_detail_phar_chgcode_desc")||span.includes("#jqgrid_detail_phar_docname")){
			$(span+'_'+rowid).text(textval);
		}else{ // ini je perlu format, yang len chgcode, desc dan dept
			$(span+'_'+rowid).text(numeral(textval).format('0,0.00'));
		}
	}
}

function write_detail_dosage(selrowdata,edit=false,rowid){
	if(selrowdata == 'clearall'){
		$('#dosage_phar_'+rowid+',#frequency_phar_'+rowid+',#instruction_phar_'+rowid+',#drugindicator_phar_'+rowid+',#ftxtdosage_phar_'+rowid).prop('readonly', true);

		$('#ftxtdosage_phar_'+rowid).val('');
		$('#dosage_phar_'+rowid).val('');
		$('#dosage_phar_code_'+rowid).val('');
		$('#frequency_phar_'+rowid).val('');
		$('#frequency_phar_code_'+rowid).val('');
		$('#instruction_phar_'+rowid).val('');
		$('#instruction_phar_code_'+rowid).val('');
		$('#drugindicator_phar_'+rowid).val('');
		$('#drugindicator_phar_code_'+rowid).val('');

		return 0;
	}
	if(!edit){
		$('#dosage_phar_'+rowid+',#frequency_phar_'+rowid+',#instruction_phar_'+rowid+',#drugindicator_phar_'+rowid+',#ftxtdosage_phar_'+rowid).prop('readonly', true);
	}else{
		$('#dosage_phar_'+rowid+',#frequency_phar_'+rowid+',#instruction_phar_'+rowid+',#drugindicator_phar_'+rowid+',#ftxtdosage_phar_'+rowid).prop('readonly', false);
	}
	removeValidationClass(['#dosage_phar_'+rowid,'#frequency_phar_'+rowid,'#instruction_phar_'+rowid,'#drugindicator_phar_'+rowid]);
	$('#ftxtdosage_phar_'+rowid).val(selrowdata.ftxtdosage);

	var dosage_newOption = new Option(selrowdata.doscode_desc, selrowdata.doscode, false, false);
	$('select#dosage_phar_'+rowid).append(dosage_newOption).trigger('change');
	$('#dosage_phar_code_'+rowid).val(selrowdata.doscode);

	var frequency_newOption = new Option(selrowdata.frequency_desc, selrowdata.frequency, false, false);
	$('#frequency_phar_'+rowid).append(frequency_newOption).trigger('change');
	$('#frequency_phar_code_'+rowid).val(selrowdata.frequency);

	var instruction_newOption = new Option(selrowdata.addinstruction_desc, selrowdata.addinstruction, false, false);
	$('#instruction_phar_'+rowid).append(instruction_newOption).trigger('change');
	$('#instruction_phar_code_'+rowid).val(selrowdata.addinstruction);

	var drugindicator_newOption = new Option(selrowdata.drugindicator_desc, selrowdata.drugindicator, false, false);
	$('#drugindicator_phar_'+rowid).append(drugindicator_newOption).trigger('change');
	$('#drugindicator_phar_code_'+rowid).val(selrowdata.drugindicator);
}

function collapseallsubgrid(except){
	var dataid = $("#jqGrid_phar").jqGrid('getDataIDs');
	dataid.forEach(function(e,i){
		$("#jqGrid_phar").jqGrid("collapseSubGridRow",e);
	});
	$("#jqGrid_phar").jqGrid("expandSubGridRow",except);
}

function ordropdown(id,obj){
	this.id=id;
	this.obj=obj;
	this.on = function(){
		let self = this;
		$(self.id).select2(self.obj);
	}
}