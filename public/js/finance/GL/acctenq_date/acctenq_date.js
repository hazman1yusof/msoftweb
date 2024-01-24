
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$('body').show();

	var conf = {
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

	/////////////////////////validation//////////////////////////
	var errorField=[];
	var mymodal = new modal();
	var detbut = new detail_button();
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
		balance = parseFloat(openbal) - parseFloat(balance)
		// $('#fd_openbal').html(numeral(openbal).format('0,0.00'));
		$('#fd_balance').html(numeral(balance).format('0,0.00'));
	}

	function populateTable(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
		$.each(rowData, function( index, value ) {
			if(value){
				$('#TableGlmasdtl #'+index+' span').text(numeral(value).format('0,0.00'))
			}else{
				$('#TableGlmasdtl #'+index+' span').text("0.00");
			}
		});
	}

	$('#search').click(function(){
		DataTable.clear().draw();
		if($('#searchform').isValid({requiredFields:''},conf,true)){
			mymodal.show("#TableGlmasTran_c");
			getdata();
			// getdatadr(false,0,20);
			// getdatacr(false,0,20);
		}
		// urlParam.filterCol = ['glmasdtl.glaccount','glmasdtl.year'];
		// urlParam.filterVal = [$('#glaccount').val(),$('#year').val()];
		// refreshGrid("#jqGrid",urlParam);
		// hidetbl(true);
		// $("#TableGlmasdtl td[id^='glmasdtl_actamount']").removeClass('bg-primary');
		// $("#TableGlmasdtl td span").text("");
	});

	var counter=20, moredr=true, morecr=true, DTscrollTop = 0;
	function scroll_next1000(){
		var scrolbody = $(".dataTables_scrollBody")[0];
		$('#but_det').hide();
		DTscrollTop = scrolbody.scrollTop;
		if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
			if(moredr || morecr){
				mymodal.show("#TableGlmasTran_c");
				getdatadr(false,counter,20);
				getdatacr(false,counter,20);
				counter+=20;
			}
		}
	}
	
	$("#TableGlmasdtl td[id^='glmasdtl_actamount']").click(function(){
		$("#TableGlmasdtl td[id^='glmasdtl_actamount']").removeClass('bg-primary');
		$(this).addClass("bg-primary");
		DataTable.clear().draw();
		$(".dataTables_scrollBody").unbind('scroll').scroll(scroll_next1000);
		hidetbl(false);
		if($(this).text().length>0){
			moredr=true;morecr=true;
			mymodal.show("#TableGlmasTran_c");
			getdatadr(false,0,20);
			getdatacr(false,0,20);
		}else{
			hidetbl(true);
		}
	});
	

	var DataTable = $('#TableGlmasTran').DataTable({
	    responsive: true,
		scrollY: 500,
		paging: false,
	    columns: [
	    	{ data: 'open' ,"width": "5%"},
			{ data: 'source'},
			{ data: 'trantype'},
			{ data: 'auditno'},
			{ data: 'postdate' ,"width": "15%"},
			{ data: 'description'},
			{ data: 'reference'},
			{ data: 'acccode'},
			{ data: 'dramount', "sClass": "numericCol"},
			{ data: 'cramount', "sClass": "numericCol"},
		],
		drawCallback: function( settings ) {
			$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
		}
	});

	$('#TableGlmasTran tbody').on( 'click', 'tr', function () {
		DataTable.$('tr.bg-info').removeClass('bg-info');
		$(this).addClass('bg-info');
	});

	
	// $('#TableGlmasTran').on( 'dblclick', 'tr', function () {
	// 	console.log($(this));
	// 	// detbut.show($(this));
	// });

	$('#TableGlmasTran').on( 'click', 'i', function () {
		detbut.show($(this).closest( "tr" ));
	});

	hidetbl(true);
	function hidetbl(hide){
		$('#but_det').hide();
		counter=20
		if(hide){
			$('#TableGlmasTran_wrapper').children().first().hide();
			$('#TableGlmasTran_wrapper').children().last().hide();
		}else{
			$('#TableGlmasTran_wrapper').children().first().show();
			$('#TableGlmasTran_wrapper').children().last().show();
		}
	}

	function getdata(){
		var param={
					action:'getdata',
					url:'./acctenq_date/table',
					glaccount:$('#glaccount').val(),
					fromdate:$('#fromdate').val(),
					todate:$('#todate').val(),
				}

		$.get( "./acctenq_date/table?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			mymodal.hide();
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(obj){
					obj.open="<i class='fa fa-folder-open-o' </i>"
					obj.postdate = moment(obj.postdate).format("DD-MM-YYYY");
					obj.dramount = numeral(obj.dramount).format('0,0.00');
					obj.cramount = numeral(obj.cramount).format('0,0.00');
				});

				DataTable.rows.add(data.rows).draw();
			}else{
				// moredr=false;
			}
		});
	}

	function getdatadr(fetchall,start,limit){
		var param={
					action:'get_value_default',
					url:'/util/get_value_default',
					field:['source','trantype','auditno','postdate','description','reference','cracc as acccode','amount as dramount'],
					table_name:'finance.gltran',
					table_id:'auditno',
					filterCol:['dracc','postdate','postdate'],
					filterVal:[$('#glaccount').val(),'>=.'+$('#fromdate').val(),'<=.'+$('#todate').val()],
					sidx: 'postdate', sord:'desc'
				}
		if(!fetchall){
			param.offset=start;
			param.limit=limit;
		}
		$.get( "/util/get_value_default?"+$.param(param), function( data ) {
				
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
					filterCol:['cracc','postdate','postdate'],
					filterVal:[$('#glaccount').val(),'>=.'+$('#fromdate').val(),'<=.'+$('#todate').val()],
					sidx: 'postdate', sord:'desc'
				}
		if(!fetchall){
			param.offset=start;
			param.limit=limit;
		}
		$.get( "/util/get_value_default?"+$.param(param), function( data ) {
				
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

	function detail_button(){
		this.pagesList = [
			{
				source:'CM',
				trantype:'FT',
				loadurl:"../../CM/bankTransfer/bankTransfer.php #dialogForm",
				urlParam:{
					action:'get_value_default',
					field: ['auditno','pvno','actdate','paymode','bankcode','cheqno','cheqdate','amount','payto','remarks'],
					table_name:'finance.apacthdr',
					table_id:'auditno',
					filterCol: ['source', 'trantype','auditno'],
					filterVal: ['CM', 'FT',''],
				}
			},{
				source:'CM',
				trantype:'DP',
				loadurl:"../../CM/Direct%20Payment/DirectPayment.php #dialogForm",
				urlParam:{
					action:'get_value_default',
					field:['*'],
					table_name:'finance.apacthdr',
					table_id:'auditno',
					filterCol: ['source', 'trantype','auditno'],
					filterVal: ['CM', 'DP', ''],
				},
				jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
					{
						id:'#jqGrid2',
						urlParam:{
							action:'get_table_default',
							field:[
								{label:'Department',name:'deptcode'},
								{label:'Category',name:'category'},
								{label:'Document',name:'document'},
								{label:'Amount Before GST',name:'AmtB4GST'},
								{label:'GST Code',name:'GSTCode'},
								{label:'Total Amount',name:'amount'}
							],
							table_name:'finance.apactdtl',
							table_id:'none_',
							filterCol:['auditno', 'recstatus','trantype','source'],
							filterVal:['', 'ACTIVE','DP','CM'],
						}
					}
				]
			},{
				source:'PB',
				trantype:'RC',
				loadurl:"../../AR/receipt/receipt.php #dialogForm",
				urlParam:{
					action:'get_value_default',
					field:["*"],
					table_name:'debtor.dbacthdr',
					table_id:'auditno',
					filterCol:['source', 'trantype','auditno'],
					filterVal:['PB', 'RC','']
				}
			},{
				source:'CM',
				trantype:'CA',
				loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
				urlParam:{
					action:'get_value_default',
					field:["*"],
					table_name:'finance.apacthdr',
					table_id:'auditno',
					filterCol:['source', 'trantype','auditno'],
					filterVal:['CM', 'CA','']
				},
				jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
					{
						id:'#jqGrid2',
						urlParam:{
							action:'get_table_default',
							field:[
								{label:'Department',name:'deptcode'},
								{label:'Category',name:'category'},
								{label:'Document',name:'document'},
								{label:'Amount Before GST',name:'AmtB4GST'},
								{label:'GST Code',name:'GSTCode'},
								{label:'Total Amount',name:'amount'}
							],
							table_name:'finance.apactdtl',
							table_id:'none_',
							filterCol:['auditno', 'recstatus','trantype','source'],
							filterVal:['', 'ACTIVE','','CM'],
						}
					}
				]
			},{
				source:'CM',
				trantype:'DA',
				loadurl:"../../CM/Credit%20Debit%20Transaction/creditDebitTrans.php #dialogForm",
				urlParam:{
					action:'get_value_default',
					field:["*"],
					table_name:'finance.apacthdr',
					table_id:'auditno',
					filterCol:['source', 'trantype','auditno'],
					filterVal:['CM', 'DA','']
				},
				jqgrid:[ //rightnow only handle 1 jqgrid inside page, change if later need more
					{
						id:'#jqGrid2',
						urlParam:{
							action:'get_table_default',
							field:[
								{label:'Department',name:'deptcode'},
								{label:'Category',name:'category'},
								{label:'Document',name:'document'},
								{label:'Amount Before GST',name:'AmtB4GST'},
								{label:'GST Code',name:'GSTCode'},
								{label:'Total Amount',name:'amount'}
							],
							table_name:'finance.apactdtl',
							table_id:'none_',
							filterCol:['auditno', 'recstatus','trantype','source'],
							filterVal:['', 'ACTIVE','','CM'],
						}
					}
				]
			}
		];

		this.show = function(obj){
			mymodal.show("body");
			var source = obj.children("td:nth-child(2)").text();
			var trantype = obj.children("td:nth-child(3)").text();
			var auditno = obj.children("td:nth-child(4)").text();
			var pageUse = this.pagesList.find(function(obj){
				return (obj.source === source && obj.trantype === trantype);
			});
			if(pageUse == undefined){
				mymodal.hide();
				alert('Unknown source: '+source+' | trantype: '+trantype+' or no selected row');
				return false;
			}
			pageUse.urlParam.filterVal[2] = auditno;

			$.get( "../../../../assets/php/entry.php?"+$.param(pageUse.urlParam), function( data ) {
				
			},'json').done(function(data) {
				mymodal.hide();
				if(!$.isEmptyObject(data.rows)){
					$( "#dialogForm" ).load( pageUse.loadurl, function(){
						populatePage(data.rows[0],'#formdata',source,trantype);
						disableForm('#formdata');
						if(source=="PB" && trantype=="RC"){
							$(".nav-tabs a[form='"+data.rows[0].paytype+"']").tab('show');
							populatePage(data.rows[0],data.rows[0].paytype,source,trantype);
							disableForm(data.rows[0].paytype);
						}
						$("#dialogForm").dialog("open");
						if(pageUse.hasOwnProperty('jqgrid')){
							pageUse.jqgrid[0].urlParam.filterVal[0] = auditno;
							pageUse.jqgrid[0].urlParam.filterVal[2] = trantype;
							jqgrid_inpage(
								pageUse.jqgrid[0].id,
								populate_colmodel(pageUse.jqgrid[0].urlParam.field),
								pageUse.jqgrid[0].urlParam
							);//change here
						}
					});
				}
			});
		}

		function populate_colmodel(field){
			console.log(field);
			var colmodel = [];
			field.forEach(function(element){
				colmodel.push({label:element.label,name:element.name,formatter:showdetail,classes: 'wrap'});
			});
			return colmodel;
		}

		function showdetail(cellvalue, options, rowObject){
			var field,table;
			switch(options.colModel.name){
				case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
				case 'category':field=['catcode','description'];table="material.category";break;
				case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";break;
				default: return cellvalue;
			}
			var param={action:'input_check',table:table,field:field,value:cellvalue};
			$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.row)){
					$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").append("<span class='help-block'>"+data.row.description+"</span>");
				}
			});
			return cellvalue;
		}

		function jqgrid_inpage(jqgrid,colmodel,urlParam){
			var jqgrid = $("#dialogForm "+jqgrid).jqGrid({
				datatype: "local",
				colModel: colmodel,
				autowidth:true,
				viewrecords: true,
				loadonce:false,
				width: 200,
				height: 200,
				rowNum: 300,
			});

			addParamField(jqgrid,true,urlParam);
		}

		function populatePage(obj,form,source,trantype){
			$.each(obj, function( index, value ) {
				if(source=="PB" && trantype=="RC")index = "dbacthdr_"+index;
				var input=$(form+" [name='"+index+"']");
				if(input.is("[type=radio]")){
					$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
				}else{
					input.val(value);
				}
			});
		}
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
