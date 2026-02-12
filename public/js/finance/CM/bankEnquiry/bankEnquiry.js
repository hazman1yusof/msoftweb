$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	var errorField=[];
	var mymodal = new modal();
	//var detbut = new detail_button();
	var fdl = new faster_detail_load();
	//////////////////////////////////////////////////////////////

	////////////////////object for dialog handler//////////////////

	var dialog_bankcode = new ordialog(
		'bankcode',['finance.bank'],"#bankcode",errorField,
		{	colModel:
			[
				{label:'Bank code',name:'bankcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Bank Name',name:'bankname',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(event){
				$('#year').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#year').focus();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcode.urlParam.filterCol=['compcode','recstatus'];
				dialog_bankcode.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(){
				
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_bankcode.makedialog(true);
	dialog_bankcode.on();

	////////////////////////////////////start dialog///////////////////////////////////////
	$("#dialogForm").dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
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
		fixPost: true,
		table_name:['finance.bank AS fb', 'finance.bankdtl AS fd'],
		table_id:'fd_bankcode',
		join_type:['LEFT JOIN'],
		join_onCol:['fb.bankcode'],
		join_onVal:['fd.bankcode'],
		sort_idno:true,
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
		 	{label: 'idno', name: 'fd_idno', width: 90 , hidden: true},
		 	{label: 'compcode', name: 'fd_compcode', width: 90 , hidden: true},
			{label: 'Year', name: 'fd_year', width: 60 },
			{label: 'Bank Code', name: 'fd_bankcode', width: 60, canSearch:true, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{label: 'Name', name: 'fb_bankname', width: 100, hidden: true },
			{label: 'Bank Account No', name: 'fb_bankaccount', width: 60 },
			{label: 'Open Balance', name: 'fd_openbal',formatter:'currency', width: 60, readonly: true, align: 'right'},
			// {label: 'Balance', name: 'fd_balance', width: 90, readonly:true, hidden: true},
			{label: 'actamount1', name: 'fd_actamount1', width: 90 , hidden: true},
			{label: 'actamount2', name: 'fd_actamount2', width: 90 , hidden: true},
			{label: 'actamount3', name: 'fd_actamount3', width: 90 , hidden: true},
			{label: 'actamount4', name: 'fd_actamount4', width: 90 , hidden: true},
			{label: 'actamount5', name: 'fd_actamount5', width: 90 , hidden: true},
			{label: 'actamount6', name: 'fd_actamount6', width: 90 , hidden: true},
			{label: 'actamount7', name: 'fd_actamount7', width: 90 , hidden: true},
			{label: 'actamount8', name: 'fd_actamount8', width: 90 , hidden: true},
			{label: 'actamount9', name: 'fd_actamount9', width: 90 , hidden: true},
			{label: 'actamount10', name: 'fd_actamount10', width: 90 , hidden: true},
			{label: 'actamount11', name: 'fd_actamount11', width: 90 , hidden: true},
			{label: 'actamount12', name: 'fd_actamount12', width: 90 , hidden: true},
			{label: 'Total', name: 'fd_total', width: 90, readonly:true, hidden:true},
		],

		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 200,
		height: 100,
		rowNum: 30,
		sortname: 'fd_year',
		sortorder: "desc",
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			//$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
			hidetbl(true);
			//DataTable.clear().draw();
			populateTable();
			getTotal();
			getBalance();
		},
		gridComplete: function(){
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},
		
	});
	$("#jqGrid").jqGrid('setLabel','fd_openbal','Open Balance',{'text-align':'right'});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,'edit');
		},
	})

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	
	function getTotal(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
		var total=0;
		var fd_actamount=0;
		$.each(rowdata, function( index, value ) {
			if(!isNaN(parseFloat(value)) && index.indexOf('fd_actamount') !== -1){
				total+=parseFloat(value);
			}
		});
		$('#fd_total').html(numeral(total).format('0,0.00'));
	}

	function getBalance(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
		var openbal=rowdata.fd_openbal;
		var balance=0;
		var total=0;
		var fd_actamount=0;

		$.each(rowdata, function( index, value ) {
			if(!isNaN(parseFloat(value)) && (index.indexOf('fd_actamount') && index.indexOf('fd_openbal')) !== -1){
				balance+=parseFloat(value);
			}
		});
		// balance = parseFloat(openbal) - parseFloat(balance)
		// $('#fd_openbal').html(numeral(openbal).format('0,0.00'));
		$('#fd_balance').html(numeral(balance).format('0,0.00'));
	}
	
	function populateTable(){
		var latest_tblid = false;
		if($("#TableBankEnquiry td[id^='fd_actamount']").hasClass("bg-primary")){
			latest_tblid = $("#TableBankEnquiry td[class='bg-primary']").attr('id')
		}
		$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');

		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
		$.each(rowData, function( index, value ) {
			if(value){
				$('#TableBankEnquiry #'+index+' span').text(numeral(value).format('0,0.00'));
			}else{
				$('#TableBankEnquiry #'+index+' span').text("0.00");
			}
		});

		if(latest_tblid){
			$("#TableBankEnquiry td#"+latest_tblid).click();
	   }
	}

	$('#search').click(function(){
		urlParam.filterCol = ['fd.compcode','fd.bankcode','fd.year'];
		urlParam.filterVal = ['session.compcode',$('#bankcode').val(),$('#year').val()];
		refreshGrid("#jqGrid",urlParam);
		hidetbl(true);
		$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
		$("#TableBankEnquiry td span").text("");
		DataTable.clear().draw();
	});
	

	var counter=20, moredr=true, DTscrollTop = 0;
	// function scroll_next1000(){
	// 	var scrolbody = $(".dataTables_scrollBody")[0];
	// 	$('#but_det').hide();
	// 	DTscrollTop = scrolbody.scrollTop;
	// 	if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
	// 		if(moredr){
	// 			mymodal.show("#TableBankEnquiryTran_c");
	// 			getdatadr(false,page);
	// 		}
	// 	}
	// }

	$("#TableBankEnquiry td[id^='fd_actamount']").click(function(){
		$("#TableBankEnquiry td[id^='fd_actamount']").removeClass('bg-primary');
		$(this).addClass("bg-primary");
		DataTable.clear().draw();
		DataTable.ajax.reload();
		//$(".dataTables_scrollBody").unbind('scroll').scroll(scroll_next1000);
		hidetbl(false);
		if($(this).text().length>0){
			moredr=true;
			//page=1;
			mymodal.show("#TableBankEnquiryTran_c");
			// getdatadr(false,page);
			// page=page+1;
		}else{
			hidetbl(true);
		}
	
	});

	var DataTable = $('#TableBankEnquiryTran').DataTable({
	    //responsive: true,
		ajax: './bankEnquiry/table?action=getdata',
	    scrollY: 500,
		deferRender: true,
    	scroller: true,
		paging: false,
	    columns: [
	    	{data: 'open' ,"width": "5%", "sClass": "opendetail"},
			{data: "source","sClass": "source"},
			{data: "trantype","sClass": "trantype"},
			{data: "auditno", "sClass": "auditno"},
			{data: "postdate","width": "13%", "sClass": "postdate"},
			{data: "reference"},
			{data: "cheqno" },
			{data: "amountdr", "sClass": "numericCol"},
			{data: "amountcr","sClass": "numericCol"},
		],
		columnDefs: [
			{targets: 0,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(`<i class='fa fa-folder-open-o'></i>`);
	   			}
	   		},{targets: 7,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(numeral(cellData).format('0,0.00'));
	   			}
	   		},{targets: 8,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).html(numeral(Math.abs(cellData)).format('0,0.00'));
	   			}
	   		}
		],
		drawCallback: function( settings ) {
			$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
		}
	}).on('xhr.dt', function ( e, settings, json, xhr ) {
        mymodal.hide();
    }).on('preXhr.dt', function ( e, settings, data ) {
        data.year = $('#year').val();
        data.bankcode = selrowData("#jqGrid").fd_bankcode;
        //data.bankcode = $('#bankcode').val();
        data.period = $("td[class='bg-primary']").attr('period');
	});

	$('#TableBankEnquiryTran tbody').on( 'click', 'tr', function () {
		DataTable.$('tr.bg-info').removeClass('bg-info');
		$(this).addClass('bg-info');
	});

	// $('#TableBankEnquiryTran').on( 'click', 'i', function () {
	// 	console.log($(this).closest( "tr" ));
	// 	detbut.show($(this).closest( "tr" ));
	// });

	$('#TableBankEnquiryTran').on( 'click', 'td.opendetail', function () {
		var source = $(this).siblings("td.source").text();
		var trantype = $(this).siblings("td.trantype").text();
		var auditno = $(this).siblings("td.auditno").text();
		var obj_id = {
			source: source,
			trantype: trantype,
			auditno: auditno
		}
		let src = null;
		let pdf = null;

		switch(true){
			case obj_id.source=='CM':
				if(obj_id.trantype == 'BD'){
					src = './bankInRegistration?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'BS'){
					src = './bankInRegistration?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'BQ'){
					src = './bankInRegistration?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'DP'){
					src = './directPayment?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'CA'){
					src = './creditDebitTrans?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'DA'){
					src = './creditDebitTrans?scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}
				break;
			case obj_id.source=='PB':
				if(obj_id.trantype == 'RC'){
					pdf = './receipt/showpdf?auditno='+auditno+'&source='+source+'&trantype='+trantype+'&readlauditno=true';
				}else if(obj_id.trantype == 'RF'){
					pdf = './receipt/showpdf?auditno='+auditno+'&source='+source+'&trantype='+trantype+'&readlauditno=true';
				}else if(obj_id.trantype == 'RD'){
					pdf = './receipt/showpdf?auditno='+auditno+'&source='+source+'&trantype='+trantype+'&readlauditno=true';
				}
				break;
			case obj_id.source=='AP':
				if(obj_id.trantype == 'PV'){
					src = './paymentVoucher?source=AP&scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}else if(obj_id.trantype == 'PD'){
					src = './paymentVoucher?source=AP&scope=ALL&viewonly=viewonly&source='+source+'&trantype='+trantype+'&auditno='+auditno;
				}
				break;
		}

		if(src != null){
			$('iframe#open_detail_iframe').attr('src',src);
			$("#open_detail_dialog").dialog("open");
		}else if(pdf != null){
			window.open(pdf, '_blank');
		}
	});

	$("#open_detail_dialog").dialog({
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
		},
	});

	hidetbl(true);
	function hidetbl(hide){
		// $('#but_det').hide();
		// if(hide){
		// 	$('#TableBankEnquiryTran_wrapper').children().first().hide();
		// 	$('#TableBankEnquiryTran_wrapper').children().last().hide();
		// }else{
		// 	$('#TableBankEnquiryTran_wrapper').children().first().show();
		// 	$('#TableBankEnquiryTran_wrapper').children().last().show();
		// }
	}

	function getdatadr(fetchall,page){
		var param={
					action:'get_value_default',
					url: './bankEnquiry/table',
					oper: 'getdatadr',
					bankcode : selrowData("#jqGrid").fd_bankcode,
					year : $('#year').val(),
					period : $("td[class='bg-primary']").attr('period'),
					rows: 1000,
					page: page,
					// sidx: 'NULL', sord:'asc'
				}

		$.get( param.url+"?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			mymodal.hide();
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(obj){
					obj.open="<i class='fa fa-folder-open-o fa-2x' </i>"
					obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
					if(obj.amountdr<0){
						obj.amountcr = obj.amountdr;
						obj.amountdr = null;
					}
					obj.amountcr = numeral(Math.abs(obj.amountcr)).format('0,0.00');
					obj.amountdr = numeral(obj.amountdr).format('0,0.00');
					obj.amountcr = (obj.amountcr == '0.00')?'':Math.abs(obj.amountcr);
					obj.amountdr = (obj.amountdr == '0.00')?'':obj.amountdr;
				});
				DataTable.rows.add(data.rows).draw();
			}else{
				moredr=false;
			}
		});
	}

	//////////////////////////////////////xxformatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'fd_bankcode':field=['bankcode','bankname'];table="finance.bank";case_='fd_bankcode';break;

		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('bankEnquiry',options,param,case_,cellvalue);
		
		return cellvalue;
	}

	// function detail_button(){
	
	// 	this.pagesList = [
	// 		{
	// 			source:'CM',
	// 			trantype:'FT',
	// 			loadurl:"../../CM/bankTransfer/bankTransfer.php #dialogForm",
	// 			urlParam:{
	// 				action:'get_value_default',
	// 				field: ['auditno','pvno','actdate','paymode','bankcode','cheqno','cheqdate','amount','payto','remarks'],
	// 				table_name:'finance.apacthdr',
	// 				table_id:'auditno',
	// 				filterCol: ['source', 'trantype','auditno'],
	// 				filterVal: ['CM', 'FT',''],
	// 			}
	// 		},{
	// 			source:'CM',
	// 			trantype:'DP',
	// 			loadurl:"../../CM/Direct%20Payment/DirectPayment.php #dialogForm",
	// 			urlParam:{
	// 				action:'get_value_default',
	// 				field:['*'],
	// 				table_name:'finance.apacthdr',
	// 				table_id:'auditno',
	// 				filterCol: ['source', 'trantype','auditno'],
	// 				filterVal: ['CM', 'DP', ''],
	// 			},
	// 			jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
	// 				{
	// 					id:'#jqGrid2',
	// 					urlParam:{
	// 						action:'get_table_default',
	// 						field:[
	// 							{label:'Department',name:'deptcode'},
	// 							{label:'Category',name:'category'},
	// 							{label:'Document',name:'document'},
	// 							{label:'Amount Before GST',name:'AmtB4GST'},
	// 							{label:'GST Code',name:'GSTCode'},
	// 							{label:'Total Amount',name:'amount'}
	// 						],
	// 						table_name:'finance.apactdtl',
	// 						table_id:'deptcode',
	// 						filterCol:['auditno', 'recstatus'],
	// 						filterVal:['', 'A'],
	// 					}
	// 				}
	// 			]
	// 		},{
	// 			source:'PB',
	// 			trantype:'RC',
	// 			loadurl:"../../AR/receipt/receipt.php #dialogForm",
	// 			urlParam:{
	// 				action:'get_value_default',
	// 				field:["*"],
	// 				table_name:'debtor.dbacthdr',
	// 				table_id:'auditno',
	// 				filterCol:['source', 'trantype','auditno'],
	// 				filterVal:['PB', 'RC','']
	// 			}
	// 		},{
	// 			source:'CM',
	// 			trantype:'CA',
	// 			loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
	// 			urlParam:{
	// 				action:'get_value_default',
	// 				field:["*"],
	// 				table_name:'finance.apacthdr',
	// 				table_id:'auditno',
	// 				filterCol:['source', 'trantype','auditno'],
	// 				filterVal:['CM', 'CA','']
	// 			},
	// 			jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
	// 				{
	// 					id:'#jqGrid2',
	// 					urlParam:{
	// 						action:'get_table_default',
	// 						field:[
	// 							{label:'Department',name:'deptcode'},
	// 							{label:'Category',name:'category'},
	// 							{label:'Document',name:'document'},
	// 							{label:'Amount Before GST',name:'AmtB4GST'},
	// 							{label:'GST Code',name:'GSTCode'},
	// 							{label:'Total Amount',name:'amount'}
	// 						],
	// 						table_name:'finance.apactdtl',
	// 						table_id:'deptcode',
	// 						filterCol:['auditno', 'recstatus','trantype'],
	// 						filterVal:['', 'A',''],
	// 					}
	// 				}
	// 			]
	// 		},{
	// 			source:'CM',
	// 			trantype:'DA',
	// 			loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
	// 			urlParam:{
	// 				action:'get_value_default',
	// 				field:["*"],
	// 				table_name:'finance.apacthdr',
	// 				table_id:'auditno',
	// 				filterCol:['source', 'trantype','auditno'],
	// 				filterVal:['CM', 'DA','']
	// 			},
	// 			jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
	// 				{
	// 					id:'#jqGrid2',
	// 					urlParam:{
	// 						action:'get_table_default',
	// 						field:[
	// 							{label:'Department',name:'deptcode'},
	// 							{label:'Category',name:'category'},
	// 							{label:'Document',name:'document'},
	// 							{label:'Amount Before GST',name:'AmtB4GST'},
	// 							{label:'GST Code',name:'GSTCode'},
	// 							{label:'Total Amount',name:'amount'}
	// 						],
	// 						table_name:'finance.apactdtl',
	// 						table_id:'deptcode',
	// 						filterCol:['auditno', 'recstatus','trantype'],
	// 						filterVal:['', 'A',''],
	// 					}
	// 				}
	// 			]
	// 		}
	// 	];
		
	// 	this.show=function(obj){
			
	// 			mymodal.show("body");
	// 			var source = obj.children("td:nth-child(2)").text();
	// 			var trantype = obj.children("td:nth-child(3)").text();
	// 			var auditno = obj.children("td:nth-child(4)").text();
	// 			var pageUse = this.pagesList.find(function(obj){
	// 				return (obj.source === source && obj.trantype === trantype);
	// 			});
	// 			if(pageUse == undefined){
	// 				mymodal.hide();
	// 				bootbox.alert('Unknown source: '+source+' | trantype: '+trantype+' or no selected row');
	// 				return false;
	// 			}
	// 			pageUse.urlParam.filterVal[2] = auditno;

	// 			$.get( "../../../../assets/php/entry.php?"+$.param(pageUse.urlParam), function( data ) {
					
	// 			},'json').done(function(data) {
	// 				mymodal.hide();
	// 				if(!$.isEmptyObject(data.rows)){
	// 					$( "#dialogForm" ).load( pageUse.loadurl, function(){
	// 						populatePage(data.rows[0],'#formdata',source,trantype);
	// 						disableForm('#formdata');

	// 						if(source=="PB" && trantype=="RC"){
	// 							$(".nav-tabs a[form='"+data.rows[0].paytype+"']").tab('show');
	// 							populatePage(data.rows[0],data.rows[0].paytype,source,trantype);
	// 							disableForm(data.rows[0].paytype);
	// 						}

	// 						$("#dialogForm").dialog("open");
							
	// 						if(pageUse.hasOwnProperty('jqgrid')){
	// 							pageUse.jqgrid[0].urlParam.filterVal[0] = auditno;

	// 							if(source=="CM" && trantype=="DA"){
	// 								pageUse.jqgrid[0].urlParam.filterVal[2] = "DA";
	// 							}else if(source=="CM" && trantype=="CA"){
	// 								pageUse.jqgrid[0].urlParam.filterVal[2] = "CA";
	// 							}

	// 							jqgrid_inpage(
	// 								pageUse.jqgrid[0].id,
	// 								populate_colmodel(pageUse.jqgrid[0].urlParam.field),
	// 								pageUse.jqgrid[0].urlParam
	// 							);//change here
	// 						}

	// 					});
	// 				}
	// 			});
			
	// 	}

	// 	function populate_colmodel(field){
	// 		var colmodel = [];
	// 		field.forEach(function(element){
	// 			colmodel.push({label:element.label,name:element.name,formatter:showdetail,classes: 'wrap'});
	// 		});
	// 		return colmodel;
	// 	}

	// 	function showdetail(cellvalue, options, rowObject){
	// 		var field,table;
	// 		switch(options.colModel.name){
	// 			case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
	// 			case 'category':field=['catcode','description'];table="material.category";break;
	// 			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
	// 			default: return cellvalue;
	// 		}
	// 		var param={action:'input_check',table:table,field:field,value:cellvalue};
	// 		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
				
	// 		},'json').done(function(data) {
	// 			if(!$.isEmptyObject(data.row)){
	// 				console.log(options);
	// 				$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
	// 			}
	// 		});
	// 		return cellvalue;
	// 	}

	// 	function jqgrid_inpage(jqgrid,colmodel,urlParam){
	// 		var jqgrid = $("#dialogForm "+jqgrid).jqGrid({
	// 			datatype: "local",
	// 			colModel: colmodel,
	// 			autowidth:true,
	// 			viewrecords: true,
	// 			loadonce:false,
	// 			width: 200,
	// 			height: 200,
	// 			rowNum: 300,
	// 		});

	// 		addParamField(jqgrid,true,urlParam);
	// 	}

	// 	function populatePage(obj,form,source,trantype){
	// 		$.each(obj, function( index, value ) {
	// 			if(source=="PB" && trantype=="RC")index = "dbacthdr_"+index;
	// 			var input=$(form+" [name='"+index+"']");
	// 			if(input.is("[type=radio]")){
	// 				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
	// 			}else{
	// 				input.val(value);
	// 			}
	// 		});
	// 	}
	// }

	set_yearperiod();
	function set_yearperiod(){
		param={
			action:'get_value_default',
			url: 'util/get_value_default',
			field: ['year'],
			sortby:['year desc'],
			table_name:'sysdb.period',
			table_id:'idno'
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
				
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
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}
