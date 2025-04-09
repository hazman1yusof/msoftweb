$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	
	/////////////////////////validation//////////////////////////
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
					message : ''
				}];
			}
		},
	};

	$("body").click(function(){
		$('#error_infront').text('');
	});
			
	//////////////////////////////////////////////////////////////

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#clsBnkStatmnt','#closeAmtStamnt','#cashBkBal','#unReconAmt']);
	var fdl = new faster_detail_load();
	var myallocation = new Allocation();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper;
	var unsaved = false;

	$("#dialogForm").dialog({ 
		width: $(window).width(),
		height: $(window).height(),
		// position: { my: "center", at: "top", of: window },
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);

			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft));
			$('#clsBnkStatmnt').val($('#closeAmtStamnt').val());

			mycurrency.formatOff();
			
			urlParam_2.bankcode = $('#bankcode_').val();
			urlParam_2.recdate = $('#recdate').val();
			urlParam_2.clsBnkStatmnt = $('#clsBnkStatmnt').val();
			urlParam_3.bankcode = $('#bankcode_').val();
			urlParam_3.recdate = $('#recdate').val();

			mycurrency.formatOnBlur();
			mycurrency.formatOn();

			myallocation.renewAllo();

			refreshGrid("#jqGrid2", urlParam_2);
			refreshGrid("#jqGrid3", urlParam_3);

		},
		beforeClose: function(event, ui){
		},
		close: function( event, ui ) {
			refreshGrid("#jqGrid", urlParam);
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	$('#newReconBtn').click(function(){
		$('#newReconBtn,#searchReconBtn,#bankcode2').prop('disabled',true);
		$('#bankcode1,#recdate,#closeAmtStamnt,bankcode_div_new').prop('disabled',false);
		$('#addReconBtn,#saveReconBtn,#cancelReconBtn,#bankcode_div_new').show();
		$('#bankcode2,#bankname,#recdate,#closeAmtStamnt,#cashBkBal,#unReconAmt').val('');
		$('#bankcode_div_search').hide();
		dialog_bankcode1.on();
		dialog_bankcode2.off();
	});

	$('#searchReconBtn').click(function(){
		$('#newReconBtn,#searchReconBtn,#recdate,#closeAmtStamnt,#bankcode1,bankcode_div_new').prop('disabled',true);
		$('#bankcode2,#bankcode_div_search').prop('disabled',false);
		$('#bankcode2,#bankname,#recdate,#closeAmtStamnt,#cashBkBal,#unReconAmt').val('');
		$('#addReconBtn,#saveReconBtn,#cancelReconBtn,#bankcode_div_search').show();
		$('#bankcode_div_new').hide();
		dialog_bankcode2.on();
		dialog_bankcode1.off();
	});

	$('#cancelReconBtn').click(function(){
		$('#newReconBtn,#searchReconBtn').prop('disabled',false);
		$('#bankcode1,#bankcode2,#recdate,#closeAmtStamnt').prop('disabled',true);
		$('#bankcode1,#bankcode2,#bankname,#recdate,#closeAmtStamnt,#cashBkBal,#unReconAmt').val('');
		$('#addReconBtn,#saveReconBtn,#cancelReconBtn').hide();
		dialog_bankcode1.off();
		dialog_bankcode2.off();
		refreshGrid("#jqGrid",null,"kosongkan");
	});

	$('#addReconBtn').click(function(){
		// if($('#formdata2').isValid({requiredFields:''},conf,true) && $('#formdata').isValid({requiredFields:''},conf,true)){
			$("#dialogForm").dialog('open');
		// }
	});

	/////////////////////recstatus filter for checkbox/////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
	if($("#recstatus_use").val() == 'POSTED'){
		recstatus_filter = [['OPEN','POSTED']];
		filterCol_urlParam = ['compcode'];
		filterVal_urlParam = ['session.compcode'];
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

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./bankRecon/table',
		bankcode:null,
		recdate:null,
		oper:'init'
	}

	$('#bankcode1').change(function(){
		$('#bankcode_').val($(this).val());
		urlParam.bankcode = $('#bankcode_').val();
		urlParam.recdate = $('#recdate').val();
		urlParam.oper='init';
		refreshGrid("#jqGrid", urlParam);
	});

	$('#recdate').change(function(){
		urlParam.bankcode = $('#bankcode_').val();
		urlParam.recdate = $('#recdate').val();
		urlParam.oper='init';
		refreshGrid("#jqGrid", urlParam);
	});

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={
		action:'saveheader',
		url:'./bankRecon/form',
		field:'',
		oper:oper,
	};
		
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{label: 'idno', name: 'idno', hidden:true, key: true },
			{label: 'compcode', name: 'compcode', hidden:true },
			{label: 'auditno', name: 'auditno', hidden:true },
			{label: 'Date', name: 'docdate', width: 20 },
			{label: 'year', name: 'year', hidden:true },
			{label: 'period', name: 'period', hidden:true },
			{label: 'remarks', name: 'remarks', hidden:true },
			{label: 'Reference', name: 'reference', width: 100, classes : 'wrap text-uppercase' },
			{label: 'amount', name: 'amount', width: 20 },
			{label: 'lastuser', name: 'lastuser', hidden:true },
			{label: 'lastupdate', name: 'lastupdate', hidden:true },
			{label: 'bitype', name: 'bitype', hidden:true },
			{label: 'stat', name: 'stat', width: 20 , hidden:true },
			{label: 'refsrc', name: 'refsrc', hidden:true },
			{label: 'reftrantype', name: 'reftrantype', hidden:true },
			{label: 'refauditno', name: 'refauditno', hidden:true },
			{label: 'recstatus', name: 'recstatus', hidden:true },
			{label: 'bankcode', name: 'bankcode', hidden:true },
			{label: 'cheqno', name: 'cheqno', hidden:true },
		],
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function(rowid, selected) {
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function(){
		},
		loadComplete: function(data){
			mycurrency.formatOff();
			$('#cashBkBal').val(data.cbrecdtl_sumamt);
			$('#unReconAmt').val(parseFloat($('#closeAmtStamnt').val()) - parseFloat(data.cbrecdtl_sumamt));
			calc_jq_height_onchange("jqGrid",false,parseInt($('#panel_default_c').prop('clientHeight'))-180);
			mycurrency.formatOn();
		},			
	});
		
	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', { 'text-align': 'right' });

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',false,urlParam);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;

			case 'payto':field=['suppcode','name'];table="material.supplier";case_='payto';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
		}
		var param={action:'input_check',url:'./util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('bankInRegistration',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Department':temp=$('#jqGrid2 input[name="deptcode"]');break;
			case 'Category':temp=$('#jqGrid2 input[name="category"]');break;
			case 'GST Code':temp=$('#jqGrid2 input[name="GSTCode"]');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	var dialog_bankcode1 = new ordialog(
		'bankcode1','finance.bank','#bankcode1',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#bankcode_').val(selrowData("#"+dialog_bankcode1.gridname).bankcode);
				$('#bankcode1').parent('.input-group').next('.help-block').html('');
				$('#bankname').val(selrowData("#"+dialog_bankcode1.gridname).bankname);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcode1.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcode1.urlParam.filterVal=['session.compcode','ACTIVE']
			},
			close:function(){
				$('#recdate').select().focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcode1.makedialog(false);

	var dialog_bankcode2 = new ordialog(
		'bankcode2','finance.cbrechdr','#bankcode2',errorField,
		{	colModel:[
				{label:'Document Date',name:'recdate',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Closing Bank Statement',name:'openamt',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','bankcode'],
				filterVal:['session.compcode','']
			},
			ondblClickRow: function () {
				$('#bankcode_').val($('#bankcode_search option:selected').val());
				$('#bankcode2').val($('#bankcode_search option:selected').val());
				$('#bankcode2').parent('.input-group').next('.help-block').html('');
				$('#bankname').val($('#bankcode_search option:selected').text());

				let recdate = moment(selrowData("#"+dialog_bankcode2.gridname).recdate, 'YYYY-MM-DD');
				$('#recdate').val(recdate.format('YYYY-MM'));
				$('#closeAmtStamnt').val(selrowData("#"+dialog_bankcode2.gridname).openamt);

				urlParam.bankcode = $('#bankcode_').val();
				urlParam.recdate = $('#recdate').val();
				urlParam.oper='init';
				refreshGrid("#jqGrid", urlParam);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#cheqno').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcode2.urlParam.filterCol=['compcode','bankcode'],
				dialog_bankcode2.urlParam.filterVal=['session.compcode',$('#bankcode_search option:selected').val()]
			},
			close:function(){
				$('#recdate').select().focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcode2.makedialog(false);

	var bankcode_div_append = `<label>Bank Code: </label><select class="form-control" id="bankcode_search" style="width: 300px;margin-bottom: 10px;">`;
	bankcode_obj.forEach(function(e,i){
		bankcode_div_append += `<option value="`+e.bankcode+`">`+e.bankname+`</option>`;
	});
	bankcode_div_append += `</select>`;
	$('#otherdialog_bankcode2').prepend(bankcode_div_append);

	$('#bankcode_search').change(function(){
		dialog_bankcode2.urlParam.filterVal[1] = $('#bankcode_search option:selected').val();
		refreshGrid("#"+dialog_bankcode2.gridname,dialog_bankcode2.urlParam);
	});

	var urlParam_2={
		action:'cbrecdtl_tbl',
		url:'./bankRecon/table',
		bankcode:null,
		recdate:null
	}
		
	$("#jqGrid2").jqGrid({
		datatype: "local",
		colModel: [
			{label: 'idno', name: 'idno', hidden:true, key: true },
			{label: 'compcode', name: 'compcode', hidden:true },
			{label: 'auditno', name: 'auditno', hidden:true },
			{label: 'Date', name: 'docdate', width: 20 },
			{label: 'year', name: 'year', hidden:true },
			{label: 'period', name: 'period', hidden:true },
			{label: 'Reference', name: 'reference', width: 100, classes : 'wrap text-uppercase' },
			{label: 'Amount', name: 'amount', width: 20 },
			{label: 'lastuser', name: 'lastuser', hidden:true },
			{label: 'lastupdate', name: 'lastupdate', hidden:true },
			{label: 'bitype', name: 'bitype', hidden:true },
			{label: 'Reference', name: 'remarks', hidden:true},
			{label: 'Status', name: 'stat', hidden:true },
			{label: 'refsrc', name: 'refsrc', hidden:true },
			{label: 'reftrantype', name: 'reftrantype', hidden:true },
			{label: 'refauditno', name: 'refauditno', hidden:true },
			{label: 'recstatus', name: 'recstatus', hidden:true },
			{label: 'bankcode', name: 'bankcode', hidden:true },
			{label: 'cheqno', name: 'cheqno', hidden:true},
			{label:' ', name:'mytick', width:10, formatter: formatterCheckbox2  },
		],
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGrid2Pager",
		onSelectRow: function(rowid, selected) {
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function(){
			$("#jqGrid2_c input[type='checkbox']").on('click',function(){
				var idno = $(this).data("idno");
				var rowdata = $("#jqGrid2").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					if(!myallocation.alloInArray1(idno)){
						myallocation.addAllo1(idno);
					}
				}else{
					if(myallocation.alloInArray1(idno)){
						myallocation.deleteAllo1(idno);
					}
				}
			});

			delay(function(){
	        	// $("#alloText").focus();//AlloTotal
	        	myallocation.retickallotogrid(1);
			}, 100 );
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#dialogForm').prop('clientHeight'))-200);
		},			
	});

	$("#jqGrid2").jqGrid('navGrid', '#jqGrid2Pager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid2", urlParam_2);
		},
	});

	var urlParam_3={
		action:'cbtran_tbl',
		url:'./bankRecon/table',
		bankcode:null,
		recdate:null
	}

	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: [
			{ label:'idno', name:'idno', hidden:true, key:true },
			{ label:'Date', name:'postdate', width:30 },
			{ label:'Reference', name:'reference', width:80, classes : 'wrap text-uppercase' },
			{ label:'Amount', name:'amount', width:20  },
			{ label:'compcode', name:'compcode', hidden:true },
			{ label:'bankcode', name:'bankcode', hidden:true },
			{ label:' ', name:'source', width:10 },
			{ label:' ', name:'trantype', width:10 },
			{ label:' ', name:'auditno', width:20 },
			{ label:'year', name:'year', hidden:true },
			{ label:'period', name:'period', hidden:true },
			{ label:'cheqno', name:'cheqno', hidden:true },
			{ label:'remarks', name:'remarks', hidden:true },
			{ label:'upduser', name:'upduser', hidden:true },
			{ label:'upddate', name:'upddate', hidden:true },
			{ label:'bitype', name:'bitype', hidden:true },
			{ label:'reference', name:'reference', hidden:true },
			{ label:'recstatus', name:'recstatus', hidden:true },
			{ label:'refsrc', name:'refsrc', hidden:true },
			{ label:'reftrantype', name:'reftrantype', hidden:true },
			{ label:'refauditno', name:'refauditno', hidden:true },
			{ label:'Status', name:'reconstatus', hidden:true },
			{ label:' ', name:'mytick', width:10, formatter: formatterCheckbox3 },
		],
		autowidth:true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGrid3Pager",
		onSelectRow: function(rowid, selected) {
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function(){
			$("#jqGrid3_c input[type='checkbox']").on('click',function(){
				var idno = $(this).data("idno");
				var rowdata = $("#jqGrid3").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					if(!myallocation.alloInArray2(idno)){
						myallocation.addAllo2(idno);
					}
				}else{
					if(myallocation.alloInArray2(idno)){
						myallocation.deleteAllo2(idno);
					}
				}
			});

			delay(function(){
	        	// $("#alloText").focus();//AlloTotal
	        	myallocation.retickallotogrid(2);
			}, 100 );

		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid3",true,parseInt($('#dialogForm').prop('clientHeight'))-200);
		},			
	});

	$("#jqGrid3").jqGrid('navGrid', '#jqGrid3Pager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid3", urlParam_3);
		},
	});

	$('#alloCol').change(function(){
		if($(this).val() == 'postdate'){
			$('#alloDate').show();
			$('#alloText').hide();
		}else{
			$('#alloText').show();
			$('#alloDate').hide();
		}
	});

	AlloSearch();
	function AlloSearch(){
		$("#alloText").on( "keyup", function() {
			if($('#alloState').val() == 'cashbook'){
				delay(function(){
					search('#jqGrid3',$("#alloText").val(),$("#alloCol").val(),urlParam_3);
				}, 800 );
			}else if($('#alloState').val() == 'bankstmnt'){
				delay(function(){
					search('#jqGrid2',$("#alloText").val(),$("#alloCol").val(),urlParam_2);
				}, 800 );
			}
		});

		$("#alloDate").on( "change", function() {
			if($('#alloState').val() == 'cashbook'){
				search('#jqGrid3',$("#alloDate").val(),$("#alloCol").val(),urlParam_3);
			}else if($('#alloState').val() == 'bankstmnt'){
				search('#jqGrid2',$("#alloDate").val(),$("#alloCol").val(),urlParam_2);
			}
		});

		// $("#alloCol").on( "change", function() {
		// 	search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
		// });

		$('#resetAlloBtn').click(function(){
			$("#alloText").val('');
			$("#alloDate").val('');
			search('#jqGrid3','','',urlParam_3);
			search('#jqGrid2','','',urlParam_2);
		});
	}

	$('#btn_cbrecdtl_add').click(function(){
		$('#btn_cbrecdtl_add').prop('disabled',true);
		mycurrency.formatOff();
		let idno_array = [];
		myallocation.arrayAllo2.forEach(function(e,i){
			idno_array.push(e.idno);
		});

		var obj={
			_token : $('#_token').val(),
			clsBnkStatmnt : $('#clsBnkStatmnt').val(),
			idno_array : idno_array,
			recdate : $('#recdate').val(),
			bankcode : $('#bankcode_').val()
		}

		$.post( "./bankRecon/form?oper=cbrecdtl_add", obj , function( data ) {
			
		},'json').fail(function (data) {
			mycurrency.formatOn();
			alert(data.responseText);
			$('#btn_cbrecdtl_add').prop('disabled',false);
		}).done(function (data) {
			mycurrency.formatOn();
			refreshGrid("#jqGrid2",urlParam_2);
			refreshGrid("#jqGrid3",urlParam_3);
			$('#btn_cbrecdtl_add').prop('disabled',false);
		});
	});

	$('#btn_cbrecdtl_del').click(function(){
		$('#btn_cbrecdtl_del').prop('disabled',true);
		mycurrency.formatOff();
		let idno_array = [];
		myallocation.arrayAllo1.forEach(function(e,i){
			idno_array.push(e.idno);
		});

		var obj={
			_token : $('#_token').val(),
			clsBnkStatmnt : $('#clsBnkStatmnt').val(),
			idno_array : idno_array,
			recdate : $('#recdate').val(),
			bankcode : $('#bankcode_').val()
		}

		$.post( "./bankRecon/form?oper=cbrecdtl_del", obj , function( data ) {
			
		},'json').fail(function (data) {
			mycurrency.formatOn();
			alert(data.responseText);
			$('#btn_cbrecdtl_del').prop('disabled',false);
		}).done(function (data) {
			mycurrency.formatOn();
			refreshGrid("#jqGrid2",urlParam_2);
			refreshGrid("#jqGrid3",urlParam_3);
			$('#btn_cbrecdtl_del').prop('disabled',false);
		});
	});

    function Allocation(){
		this.arrayAllo1=[];
		this.arrayAllo2=[];

		this.renewAllo = function(){
			this.arrayAllo1.length = 0;
			this.arrayAllo2.length = 0;

			this.updateAlloField();
		}
		this.initAllo = function(){
			// this.arrayAllo.length = 0;
			// let self = this;
			// let rowdata = $('#jqGrid2').jqGrid('getRowData');

			// rowdata.forEach(function(e,i){
			// 	let comamt = parseFloat(e.refcomrate) * parseFloat(e.refamount) / 100;
			// 	if(isNaN(comamt)){
			// 		comamt = 0;
			// 	}
			// 	e.commamt = parseFloat(comamt).toFixed(4);

			// 	self.arrayAllo.push({idno:e.idno,obj:e});
			// });
			// console.log(this.arrayAllo);

			this.updateAlloField();
		}
		this.addAllo1 = function(idno){
			var obj=getlAlloFromGrid(1,idno);

			this.arrayAllo1.push({idno:idno,obj:obj});

			this.updateAlloField();
		}
		this.addAllo2 = function(idno){
			var obj=getlAlloFromGrid(2,idno);

			this.arrayAllo2.push({idno:idno,obj:obj});

			this.updateAlloField();
		}
		this.deleteAllo1 = function(idno){
			var self=this;

			$.each(self.arrayAllo1, function( index, obj ) {
				if(obj.idno==idno){
					self.arrayAllo1.splice(index, 1);
					return false;
				}
			});

			this.updateAlloField();
		}
		this.deleteAllo2 = function(idno){
			var self=this;

			$.each(self.arrayAllo2, function( index, obj ) {
				if(obj.idno==idno){
					self.arrayAllo2.splice(index, 1);
					return false;
				}
			});
			
			this.updateAlloField();
		}
		
		this.alloInArray1 = function(idno){
			var retval=false;
			$.each(this.arrayAllo1, function( index, obj ) {
				if(obj.idno==idno){
					retval=true;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.alloInArray2 = function(idno){
			var retval=false;
			$.each(this.arrayAllo2, function( index, obj ) {
				if(obj.idno==idno){
					retval=true;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.retickallotogrid = function(jq){
			var self=this;
			if(jq == 1){
				$.each(this.arrayAllo1, function( index, obj ) {
					$("input#checkbox_selection2_"+obj.idno).prop('checked', true);
				});
				}else if(jq==2){
				$.each(this.arrayAllo2, function( index, obj ) {
					$("input#checkbox_selection3_"+obj.idno).prop('checked', true);
				});
			}
		}
		this.updateAlloField = function(){
			console.log(this.arrayAllo1);
			console.log(this.arrayAllo2);
			// var self=this;
			// this.alloTotal = 0;
			// let totalallo = 0;
			// let totalcom = 0;

			// $.each(this.arrayAllo, function( index, obj ) {
			// 	let amt = parseFloat(obj.obj.refamount).toFixed(4);
			// 	let com = parseFloat(obj.obj.commamt).toFixed(4);

			// 	$("#jqGrid2").jqGrid('setRowData', obj.idno ,{commamt:com});

			// 	totalcom = parseFloat(totalcom) + parseFloat(com);
			// 	totalallo = parseFloat(totalallo) + parseFloat(amt);
			// });
			// $('#dtlamt').val(totalallo);
			// // $('#commamt').val(totalcom);
			// mycurrency.formatOn();
			// this.alloBalance = this.outamt - this.alloTotal;

			// $("#AlloTotal").val(this.alloTotal);
			// $("#AlloBalance").val(this.alloBalance);
			// if(this.alloBalance<0){
			// 	$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
			// 	alert("Balance cannot in negative values");
			// }else{
			// 	$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
			// }
			// allocurrency.formatOn();
		}

		function getlAlloFromGrid(jq,idno){
			if(jq == 1){
				var temp=$("#jqGrid2").jqGrid ('getRowData', idno);
			}else if(jq == 2){
				var temp=$("#jqGrid3").jqGrid ('getRowData', idno)
			}

			return temp;
		}
	}
});

function formatterCheckbox2(cellvalue, options, rowObject){
	return "<input type='checkbox' name='checkbox_selection2' id='checkbox_selection2_"+rowObject.idno+"' data-idno='"+rowObject.idno+"' >";
}

function formatterCheckbox3(cellvalue, options, rowObject){
	return "<input type='checkbox' name='checkbox_selection3' id='checkbox_selection3_"+rowObject.idno+"' data-idno='"+rowObject.idno+"' >";
}