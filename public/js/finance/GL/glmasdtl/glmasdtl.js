
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$('body').show();
	/////////////////////////validation//////////////////////////
	var errorField=[];
	var mymodal = new modal();
	// var detbut = new detail_button();
	//////////////////////////////////////////////////////////////

	////////////////////object for dialog handler//////////////////

	var dialog_dept = new ordialog(
		'doctype','finance.glmasref','#glaccount',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
		},
		ondblClickRow: function () {
			$('#year').focus();
		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#year').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select GL Account",
			open: function(){
				dialog_dept.urlParam.filterCol=['compcode','recstatus'],
				dialog_dept.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_dept.makedialog(true);
	dialog_dept.on();

	////////////////////////////////////start dialog///////////////////////////////////////
	$("#dialogForm").dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			if($("#dialogForm_SalesOrder").is(":visible")){
				disableForm("#dialogForm_SalesOrder");
				$("#jqGrid2_salesorder").jqGrid('setGridWidth',Math.floor($("#jqgrid2_salesorder_c")[0].offsetWidth - 25));
				calc_jq_height_onchange('jqGrid2_salesorder');
			}
		},
		close: function( event, ui ) {
			if($('#gbox_jqGrid2_salesorder').length > 0){
				del_jqgrid('#jqGrid2_salesorder');
			}
			$('div.dialogdtl').hide();
		},
		buttons :[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}],
	});
	
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_id:'costcode',
		table_name:['finance.glmasdtl','finance.costcenter'],
		join_type:['LEFT JOIN'],
		join_onCol:['glmasdtl.costcode'],
		join_onVal:['costcenter.costcode'],
		fixPost:true,
		filterCol:null,//['glaccount','year']
		filterVal:null,
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'idno', name: 'idno', width: 10, hidden: true,key:true},
			{label: 'compcode', name: 'compcode', width: 10, hidden: true},
			{label: 'Cost code', name: 'glmasdtl_costcode', width: 90, canSearch:true, checked:true},
			{label: 'Description', name: 'costcenter_description', width: 90, canSearch:true, checked:true},
			{label: 'GL Account', name: 'glmasdtl_glaccount', width: 90, canSearch:true },
			{label: 'Year', name: 'glmasdtl_year', width: 90 },
			{label: 'Open Balance', name: 'glmasdtl_openbalance',formatter:'currency', width: 90, readonly: true, align: 'right'},
			// {label: 'Balance', name: 'glmasdtl_openbalance', width: 90, readonly:true},
			{label: 'actamount1', name: 'glmasdtl_actamount1', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount2', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount3', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount4', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount5', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount6', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount7', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount8', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount9', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount10', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount11', width: 90 , hidden: true},
			{label: 'actamount1', name: 'glmasdtl_actamount12', width: 90 , hidden: true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 200,
		height: 100,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#jqGrid').data('lastselrow',rowid);
			hidetbl(true);
			populateTable();
			getTotal();
			getBalance();
		},
		gridComplete: function(){
			let lastselrow = $('#jqGrid').data('lastselrow');
			if(lastselrow == undefined){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			}else{
				$("#jqGrid").setSelection(lastselrow);
				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			}
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},
		
	});
	$("#jqGrid").jqGrid ('setLabel', 'glmasdtl_openbalance', '', 'textalignright');

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,'edit');
		},
	})

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',false,urlParam);

	function getTotal(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
		var total=0;
		var fd_actamount=0;
		$.each(rowdata, function( index, value ) {
			if(!isNaN(parseFloat(value)) && index.indexOf('glmasdtl_actamount') !== -1){
				total+=parseFloat(value);
			}
		});
		$('#fd_total').html(numeral(total).format('0,0.00'));
	}

	function getBalance(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
		var openbal=rowdata.glmasdtl_openbalance;
		var balance=0;
		var total=0;
		var fd_actamount=0;

		$.each(rowdata, function( index, value ) {
			if(!isNaN(parseFloat(value)) && (index.indexOf('glmasdtl_actamount') && index.indexOf('glmasdtl_openbalance')) !== -1){
				balance+=parseFloat(value);
			}
		});
		// balance = parseFloat(openbal) - parseFloat(balance);
		// $('#fd_openbal').html(numeral(openbal).format('0,0.00'));
		$('#fd_balance').html(numeral(balance).format('0,0.00'));
	}

	function populateTable(){
		var latest_tblid = false;
		if($("#TableGlmasdtl td[id^='glmasdtl_actamount']").hasClass("bg-primary")){
			latest_tblid = $("#TableGlmasdtl td[class='bg-primary']").attr('id')
		}else{
			latest_tblid = "glmasdtl_actamount"+moment().format("M");
		}
		$("#TableGlmasdtl td[id^='glmasdtl_actamount']").removeClass('bg-primary');

		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
		$.each(rowData, function( index, value ) {
			if(value){
				// $('#TableGlmasdtl #'+index+' span').text(numeral(value).format('0,0.00'))
				$('#TableGlmasdtl #'+index+' span').text(value);
			}else{
				$('#TableGlmasdtl #'+index+' span').text("0.00");
			}
		});

		if(latest_tblid){
			 $("#TableGlmasdtl td#"+latest_tblid).click();
		}
	}

	$('#search').click(function(){
		urlParam.filterCol = ['glmasdtl.glaccount','glmasdtl.year'];
		urlParam.filterVal = [$('#glaccount').val(),$('#year').val()];
		refreshGrid("#jqGrid",urlParam);
		hidetbl(true);
		$("#TableGlmasdtl td[id^='glmasdtl_actamount']").removeClass('bg-primary');
		$("#TableGlmasdtl td span").text("");
		// DataTable.clear().draw();
	});

	var counter=20, moredr=true, morecr=true, DTscrollTop = 0;
	// function scroll_next1000(){
	// 	var scrolbody = $(".dataTables_scrollBody")[0];
	// 	$('#but_det').hide();
	// 	DTscrollTop = scrolbody.scrollTop;
	// 	if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
	// 		if(moredr || morecr){
	// 			mymodal.show("#TableGlmasTran_c");
	// 			getdatadr(false,counter,20);
	// 			getdatacr(false,counter,20);
	// 			counter+=20;
	// 		}
	// 	}
	// }
	
	$("#TableGlmasdtl td[id^='glmasdtl_actamount']").click(function(){
		$("#TableGlmasdtl td[id^='glmasdtl_actamount']").removeClass('bg-primary');
		$(this).addClass("bg-primary");
		DataTable.clear().draw();
		// DataTable.ajax.reload();
		hidetbl(false);
		if($(this).text().length>0){
			moredr=true;morecr=true;
			mymodal.show("#TableGlmasTran_c");
			// getdatadr(false,0,20);
			// getdatacr(false,0,20);
			// getdata();
		}else{
			hidetbl(true);
		}
	});
	

	var DataTable = $('#TableGlmasTran').DataTable({
    	ajax: './glenquiry/table?action=getdata',
    	order: [[ 10, 'desc' ]],
    	pageLength: 30,
	    responsive: true,
    	processing: true,
    	serverSide: true,
		scrollY: 500,
		paging: true,
	    columns: [
	    	{ data: 'open' ,"width": "5%", "sClass": "opendetail","searchable":false},
			{ data: 'source', "sClass": "source"},
			{ data: 'trantype', "sClass": "trantype"},
			{ data: 'auditno', "sClass": "auditno"},
			{ data: 'postdate' ,"width": "15%", "sClass": "postdate","type": "date"},
			{ data: 'description'},
			{ data: 'reference'},
			{ data: 'acccode'},
			{ data: 'dramount', "sClass": "numericCol"},
			{ data: 'cramount', "sClass": "numericCol"},
			{ data: 'id'},
		],
		columnDefs: [
			{targets: 0,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(`<i class='fa fa-folder-open-o'></i>`);
	   			}
	   		},{targets: 3,
	        	createdCell: function (td, cellData, rowData, row, col) {
	        		$(td).html(pad('00000000',cellData,true));
	   			}
	   		},{targets: 7,
	        	createdCell: function (td, cellData, rowData, row, col) {
	        		if(rowData.acctname == null){
						$(td).append(`<span class='help-block'>-</span>`);
	        		}else{
						$(td).append(`<span class='help-block'>`+rowData.acctname+`</span>`);
	        		}
	   			}
	   		},{targets: 8,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(numeral(cellData).format('0,0.00'));
	   			}
	   		},{targets: 9,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(numeral(cellData).format('0,0.00'));
	   			}
	   		},{targets: 10,visible: false
	   		}
		],
		drawCallback: function( settings ) {
			$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
		}
	}).on('xhr.dt', function ( e, settings, json, xhr ) {
        mymodal.hide();
    }).on('preXhr.dt', function ( e, settings, data ) {
        data.year = $('#year').val();
        data.costcode = selrowData("#jqGrid").glmasdtl_costcode;
        data.acc = $('#glaccount').val();
        data.period = $("td[class='bg-primary']").attr('period');
        data.searchby = $("#Scol_dtb").val();
    }).on( 'init.dt', function () {
    	$('#TableGlmasTran_filter.dataTables_filter').prepend(`
    		<label style='width: fit-content !important;display: inline-block !important;margin-right: 10px;'>Search By: </label>
	        <select id='Scol_dtb' class='search form-group form-control' style='width: fit-content !important;display: inline-block !important;margin-right: 10px;'>
	            <option value='source'>Source</option>
	            <option value='trantype'>Trantype</option>
	            <option value='auditno'>Auditno</option>
	            <option value='postdate'>Postdate</option>
	            <option value='description'>Description</option>
	            <option selected='true' value='reference'>Reference</option>
	            <option value='acccode'>Account Code</option>
	        </select>`);
        console.log( 'Table initialisation complete: '+new Date().getTime() );
    } );

	$('#TableGlmasTran tbody').on( 'click', 'tr', function () {
		DataTable.$('tr.bg-info').removeClass('bg-info');
		$(this).addClass('bg-info');
	});

	$('#TableGlmasTran').on( 'click', 'td.opendetail', function () {
		var source = $(this).siblings("td.source").text();
		var trantype = $(this).siblings("td.trantype").text();
		var auditno = $(this).siblings("td.auditno").text();
		var obj_id = {
			source: source,
			trantype: trantype,
			auditno: auditno
		}

		switch(true){
			case obj_id.source=='AP':
				dialogForm_paymentVoucher(obj_id);
				break;

			case obj_id.source=='IV' && obj_id.trantype=='DS' :
				dialogForm_OE_IN_IV_DS(obj_id);
				break;

			case obj_id.source=='OE' && obj_id.trantype=='IN' :
				dialogForm_OE_IN_IV_DS(obj_id);
				break;
		}
	});

	hidetbl(true);
	function hidetbl(hide){
		// $('#but_det').hide();
		// // counter=20
		// if(hide){
		// 	$('#TableGlmasTran_wrapper').children().first().hide();
		// 	$('#TableGlmasTran_wrapper').children().last().hide();
		// }else{
		// 	$('#TableGlmasTran_wrapper').children().first().show();
		// 	$('#TableGlmasTran_wrapper').children().last().show();
		// }
	}

	function getdatadr(fetchall,start,limit){
		var param={
					action:'get_value_default',
					url:'util/get_value_default',
					field:['source','trantype','auditno','postdate','description','reference','cracc as acccode','amount as dramount'],
					table_name:'finance.gltran',
					table_id:'auditno',
					filterCol:['drcostcode','dracc','year','period'],
					filterVal:[
						selrowData("#jqGrid").glmasdtl_costcode,
						$('#glaccount').val(),
						$('#year').val(),
						$("td[class='bg-primary']").attr('period')
						],
					sidx: 'postdate', sord:'desc'
				}
		if(!fetchall){
			param.offset=start;
			param.limit=limit;
		}
		$.get( "util/get_value_default?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			mymodal.hide();
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(obj){
					obj.open="<i class='fa fa-folder-open-o' </i>"
					obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
					obj.dramount = numeral(obj.dramount).format('0,0.00');
					obj.cramount = numeral('0').format('0,0.00');
				});
				DataTable.rows.add(data.rows).draw();
			}else{
				moredr=false;
			}
		});
	}

	function getdatacr(fetchall,start,limit){
		var param={
					action:'get_value_default',
					field:['source','trantype','auditno','postdate','description','reference','dracc as acccode','amount as cramount'],
					table_name:'finance.gltran',
					table_id:'auditno',
					filterCol:['crcostcode','cracc','year','period'],
					filterVal:[
						selrowData("#jqGrid").glmasdtl_costcode,
						$('#glaccount').val(),
						$('#year').val(),
						$("td[class='bg-primary']").attr('period')
						],
					sidx: 'postdate', sord:'desc'
				}
		if(!fetchall){
			param.offset=start;
			param.limit=limit;
		}
		$.get( "util/get_value_default?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			mymodal.hide();
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(obj){
					obj.open="<i class='fa fa-folder-open-o' </i>"
					obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
					obj.cramount = numeral(obj.cramount).format('0,0.00');
					obj.dramount = numeral('0').format('0,0.00');
				});
				DataTable.rows.add(data.rows).draw();
			}else{
				morecr=false;
			}
		});
	}

	function getdata(){
		var param={
			url: './glenquiry/table',
			action:'getdata',
			costcode:selrowData("#jqGrid").glmasdtl_costcode,
			acc:$('#glaccount').val(),
			year:$('#year').val(),
			period:$("td[class='bg-primary']").attr('period'),
			sidx: 'postdate', sord:'desc'
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			// mymodal.hide();
			// if(!$.isEmptyObject(data.rows)){
			// 	data.rows.forEach(function(obj){
			// 		obj.open="<i class='fa fa-folder-open-o' </i>"
			// 		obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
			// 		obj.cramount = numeral(obj.cramount).format('0,0.00');
			// 		obj.dramount = numeral('0').format('0,0.00');
			// 	});
			// 	DataTable.rows.add(data.rows).draw();
			// }else{
			// 	morecr=false;
			// }
		});
	}

	set_yearperiod();
	function set_yearperiod(){
		param={
			action:'get_value_default',
			field: ['year'],
			table_name:'sysdb.period',
			table_id:'idno',
			sortby:['year desc']
		}
		$.get( "util/get_value_default?"+$.param(this.param), function( data ) {
				
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(element){	
					$('#year').append("<option>"+element.year+"</option>")
				});
			}
		});
	}

});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}

function dialogForm_paymentVoucher(obj_id){
	param={
		url: './glenquiry/table',
		action:'dialogForm_paymentVoucher',
		source: obj_id.source,
		trantype: obj_id.trantype,
		auditno: obj_id.auditno,
	}

	$.get( param.url+"?"+$.param(param), function( data ) {
			
	},'json').done(function(data) {

		switch(obj_id.trantype){
			case 'PD':
				$('#pvpd_detail').hide();
				break;
			case 'PV':
				$('#pvpd_detail').show();
				break;
		}
		
		$('#dialogForm_paymentVoucher').show();
		populatedata(data.rows[0],'#formdata_paymentVoucher');
		$('#dialogForm').dialog('open');

	});
}
function dialogForm_OE_IN_IV_DS(obj_id){
	param={
		url: './glenquiry/table',
		action:'dialogForm_SalesOrder',
		source: obj_id.source,
		trantype: obj_id.trantype,
		auditno: obj_id.auditno,
	}

	$.get( param.url+"?"+$.param(param), function( data ) {
			
	},'json').done(function(data) {
		if(data.dbacthdr!=undefined){
			if(data.dbacthdr.mrn==null || data.dbacthdr.mrn=='0'){
				populate_SalesOrder(data);
			}
		}
	});
}

function populate_SalesOrder(data){
	$('#dialogForm_SalesOrder').show();
	populatedata(data.dbacthdr,'#formdata_SalesOrder');
	populate_detail(data.billsum_array,'#jqGrid2_salesorder',[
		{label: 'Item Code', name: 'chggroup', width: 200, classes: 'wrap'},
		{label: 'Item Description', name: 'description', width: 180, classes: 'wrap'},
		{label: 'UOM Code', name: 'uom', width: 150, classes: 'wrap'},
		{label: 'UOM Code<br/>Store Dept.', name: 'uom_recv', width: 150, classes: 'wrap'},
		{label: 'Tax', name: 'taxcode', width: 100, classes: 'wrap'},
		{label: 'Unit Price', name: 'unitprice', width: 100, classes: 'wrap txnum', align: 'right',
			formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
		{label: 'Unit Cost', name: 'netprice', width: 100, classes: 'wrap txnum', align: 'right',
			formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
		{label: 'Quantity', name: 'quantity', width: 100, align: 'right', classes: 'wrap txnum',
			formatter: 'integer', formatoptions: { thousandsSeparator: ",", }},
		{label: 'Quantity on Hand', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap txnum',
			formatter: 'integer', formatoptions: { thousandsSeparator: ",", }},
		{label: 'Total Amount <br>Before Tax', name: 'amount', width: 100, align: 'right', classes: 'wrap txnum',
			formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
		{label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum',
			formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
		{label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum',
			formatter: 'currency', formatoptions: { thousandsSeparator: ",", }},
		{label: 'Discount Amount', name: 'discamt', width: 100, align: 'right', classes: 'wrap txnum',
			formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
		{label: 'Tax Amount', name: 'taxamt', width: 100, align: 'right', classes: 'wrap txnum',
			formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, }},
		{label: 'Total Amount', name: 'totamount', width: 100, align: 'right', classes: 'wrap txnum',
			formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
		{label: 'idno', name: 'idno', width: 10, hidden: true, key:true },
	]);
	$('#dialogForm').dialog('open');
}

function populatedata(rowData,form){
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else{
			input.val(decodeEntities(value));
		}
	});

	switch(form){
		case '#formdata_paymentVoucher':
			$('#formdata_paymentVoucher [name="paymode"]').parent().siblings( ".help-block" ).html(rowData.paymode_desc);
			$('#formdata_paymentVoucher [name="bankcode"]').parent().siblings( ".help-block" ).html(rowData.bankcode_desc);
			$('#formdata_paymentVoucher [name="suppcode"]').parent().siblings( ".help-block" ).html(rowData.suppcode_desc);
			$('#formdata_paymentVoucher [name="payto"]').parent().siblings( ".help-block" ).html(rowData.payto_desc);
			break;
		case '#formdata_SalesOrder':
			$('#formdata_SalesOrder [name="paymode"]').parent().siblings( ".help-block" ).html(rowData.paymode_desc);
			$('#formdata_SalesOrder [name="bankcode"]').parent().siblings( ".help-block" ).html(rowData.bankcode_desc);
			$('#formdata_SalesOrder [name="suppcode"]').parent().siblings( ".help-block" ).html(rowData.suppcode_desc);
			break;
	}
}

function populate_detail(array,gridname,colModel){
	$(gridname).jqGrid({
		datatype: "local",
		colModel: colModel,
		loadonce: true,
		autowidth: true,viewrecords:true,width:200,height:200,owNum:30,hoverrows:false,
		pager: gridname+"Pager",
		loadComplete:function(data){
		},
	});

	array.forEach(function(e,i){
		$(gridname).jqGrid('addRowData',i,e);
	});
}

function del_jqgrid(gridname){
	let rowdatas = $(gridname).jqGrid('getRowData');
	rowdatas.forEach(function(e,i){
		$(gridname).jqGrid ('delRowData',i);
	});
}