
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
		if($('#searchform').isValid({requiredFields:''},conf,true)){
			DataTable.clear().draw();
		}
	});

  	let intervalId = null;
  	function startProcessInterval() {
	    intervalId = setInterval(check_running_process, 5000);
	}
	function stopProcessInterval() {
	    if (intervalId !== null) {
	        clearInterval(intervalId);
	        intervalId = null;
	    }
	}
	if($('#jobdone').val() == 'false'){
		startProcessInterval();
	}

	function check_running_process() {
		$.get( './acctenq_date/table?action=check_running_process', function( data ) {
			
		},'json').done(function(data) {
	    	if(data.jobdone=='true'){
	    		stopProcessInterval();
				$('#print_process').attr('disabled',false);
				$('#print_process').html('Process');
				$('span#acctname').html(' ( '+data.type+' )');
			}else{
				$('#print_process').attr('disabled',true);
				$('#print_process').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');
				$('span#acctname').html('( - )');
			}
		});
	}

	$("#print_process").click(function() {

		if($('#searchform').isValid({requiredFields:''},conf,true)){
			$('#print_process').prop('disabled',true);
			$('#print_process').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');

			var obj = {
				_token:$('#_token').val(),
				glaccount:$('#glaccount').val(),
				fromdate:$('#fromdate').val(),
				todate:$('#todate').val(),
			}
			let href = './acctenq_date/form?action=processLink';

			$.post( href,obj, function( data ) {

			}).fail(function(data) {

			}).success(function(data){

			});
			
			startProcessInterval();
		}
	});

	$('#pdfgen1').click(function(){
		let href = './acctenq_date/table?action=download';

		window.open(href);
	});
	

	var DataTable = $('#TableGlmasTran').DataTable({
    	ajax: './acctenq_date/table?action=getdata',
    	pageLength: 30,
    	orderMulti: false,
	    responsive: true,
		scrollY: 500,
    	processing: true,
    	serverSide: true,
		paging: true,
	    columns: [
	    	{ data: 'open' ,"width": "2%","sClass": "opendetail", orderable: false},
	    	{ data: 'print' ,"width": "2%","sClass": "printdetail", orderable: false},
			{ data: 'source',"width": "2%"},
			{ data: 'trantype',"width": "2%"},
			{ data: 'auditno',"width": "4%", orderable: false},
			{ data: 'postdate'},
			{ data: 'description',"width": "35%", orderable: false},
			{ data: 'reference', orderable: false},
			{ data: 'acccode', orderable: false},
			{ data: 'dramount', "sClass": "numericCol"},
			{ data: 'cramount', "sClass": "numericCol"},
			{ data: 'id',visible:false},
		],
		columnDefs: [
			{targets: 6,
	        	createdCell: function (td, cellData, rowData, row, col) {
					$(td).append(`<span class='help-block'>`+rowData.desc_+`</span>`);
	   			}
	   		},
	   		{targets: 8,
	        	createdCell: function (td, cellData, rowData, row, col) {
	        		if(rowData.acctname == null){
						$(td).append(`<span class='help-block'>-</span>`);
	        		}else{
						$(td).append(`<span class='help-block'>`+rowData.acctname+`</span>`);
	        		}
	   			}
	   		}
		],
		drawCallback: function( settings ) {
			$('#TableGlmasTran_filter').hide();
		}
	}).on('preXhr.dt', function ( e, settings, data ) {
		mymodal.show("#TableGlmasTran_c");
		data.glaccount = $('#glaccount').val();
		data.fromdate = $('#fromdate').val();
		data.todate = $('#todate').val();
    }).on('xhr.dt', function ( e, settings, json, xhr ) {
    	
    	json.data.forEach(function(e,i){
			e.postdate = moment(e.postdate).format("DD-MM-YYYY");
			e.dramount = numeral(e.dramount).format('0,0.00');
			e.cramount = numeral(e.cramount).format('0,0.00');
    	});
    	mymodal.hide();
    });

	$('#TableGlmasTran tbody').on( 'click', 'tr', function () {
		DataTable.$('tr.bg-info').removeClass('bg-info');
		$(this).addClass('bg-info');
	});

	$('#TableGlmasTran tbody').on( 'click', 'td.printdetail', function () {
		var data = DataTable.row(this).data();

		var param={
					action:'openprint',
					id:data.id
				}

		$.get( "./acctenq_date/table?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			window.open(data.url);
		}).fail(function(data){
			alert('Error fetching data.');
		});
	});

	
	// $('#TableGlmasTran').on( 'dblclick', 'tr', function () {
	// 	console.log($(this));
	// 	// detbut.show($(this));
	// });

	$("#open_detail_dialog").dialog({
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
		},
	});

	$('#TableGlmasTran').on( 'click', 'td.opendetail', function () {
		// detbut.show($(this).closest( "tr" ));
		var rowdata = DataTable.row(this).data();
		console.log(rowdata)
	});

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
					obj.print="<i class='fa fa-print' </i>"
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

});
