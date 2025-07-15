$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	getYear();

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
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};
	var Class2 = $('#Class2').val();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',fixPost : "true",
		table_name:['material.product as p','material.uom as u'],
		table_id:'none_',
		join_type : ['LEFT JOIN'],
		join_onCol : ['p.uomcode'],
		join_onVal : ['u.uomcode'],
		filterCol:['p.compcode','p.unit','p.Class'],
		filterVal:['session.compcode','session.unit', $('#Class2').val()]
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            { label: 'idno', name: 'p_idno', hidden: true},
            { label: 'Unit', name: 'p_unit', width: 20},
			{ label: 'Item code', name: 'p_itemcode', width: 20, classes: 'wrap', canSearch: true},						
			{ label: 'Item Description', name: 'p_description', width: 80, classes: 'wrap', checked:true,canSearch: true},
			{ label: 'UOM Code', name: 'p_uomcode', width: 20, classes: 'wrap'},
			{ label: 'UOM Description', name: 'u_description', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Quantity on Hand', name: 'p_qtyonhand', formatter: 'currency', width: 30,classes: 'wrap',align: 'right'},
			{ label: 'Average Cost', name: 'p_avgcost', width: 30,classes: 'wrap',align: 'right'},
			{ label: 'Current Price', name: 'p_currprice', width: 30, classes: 'wrap',align: 'right'},

			
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		height: 124,
		sortname: 'p_idno',
		sortorder: 'desc',
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			$('#itemcodedtl').val(selrowData("#jqGrid").p_itemcode);
			$('#itemcodedtl_').html(selrowData("#jqGrid").p_description);

			$('#uomcodedtl').val(selrowData("#jqGrid").p_uomcode);
			$('#uomcodedtl_').html(selrowData("#jqGrid").u_description);

			if(rowid != null) {
				urlParam2.filterVal[0]=selrowData("#jqGrid").p_itemcode; 
				urlParam2.filterVal[1]=selrowData("#jqGrid").p_uomcode;
				urlParam2.filterVal[2]=$("#getYear").val();
				refreshGrid('#detail',urlParam2);

				urlParam3.filterVal[0]=selrowData("#jqGrid").p_itemcode;
			}
		},
		gridComplete: function (rowid) {
			if($("#jqGrid").jqGrid('getGridParam', 'selrow') == null){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection( $("#jqGrid").jqGrid('getGridParam', 'selrow'));
			}
			$("#searchForm input[name=Stext]").focus();
			
			if(rowid == null) {
				refreshGrid("#detail",null,"kosongkan");
				refreshGrid("#itemExpiry",null,"kosongkan");
			}
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},

	});
	
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam,'edit');
			},
			
		}	
	);

	 $("#jqGrid").jqGrid('setLabel', 'p_qtyonhand', 'Quantity on Hand', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'p_avgcost', 'Average Cost', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'p_currprice', 'Current Price', {'text-align':'right'});

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		// field:['idno','unit','deptcode','d_description','stocktxntype','uomcode','qtyonhand','openbalval','itemcode','netmvval1','netmvval2','netmvval3','netmvval4','netmvval5','netmvval6','netmvval7','netmvval8','netmvval9','netmvval10','netmvval11','netmvval12','computerid'],
		table_name:['material.stockloc as s', 'sysdb.department as d'],
		join_type : ['LEFT JOIN'],
		join_onCol : ['s.deptcode'],
		join_onVal : ['d.deptcode'],
		table_id:'idno',fixPost : "true",
		filterCol:['s.itemcode', 's.uomcode','s.year','s.compcode','s.unit'],
		filterVal:['', '',$("#getYear").val(), 'session.compcode', 'session.unit'],
	}

	$("#detail").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 's_idno', width: 40, classes: 'wrap', hidden:true},
		 	{ label: 'Dept Code', name: 's_deptcode', width: 40, classes: 'wrap'},
		 	{ label: 'Description', name: 'd_description', width: 43, classes: 'wrap'},
			{ label: 'Unit', name: 's_unit', width: 30, classes: 'wrap', hidden:false},
			{ label: 'TrxType', name: 's_stocktxntype', width: 40, classes: 'wrap',formatter: TrxType,unformat: un_TrxType},
			{ label: 'UOM Code', name: 's_uomcode', width: 40, classes: 'wrap'},
			{ label: 'Quantity on Hand', name: 's_qtyonhand', width: 40, classes: 'wrap',align: 'right', formatter: 'currency'},
			{ label: 'itemcode', name: 's_itemcode', width: 40, classes: 'wrap',hidden:true},
			{ label: 'Stock Value', name: 's_rackno', width: 40, classes: 'wrap',align: 'right', formatter: 'number', formatoptions: {decimalSeperator: '.',devimalPlaces:2,defaultValue: '0.0000'}},
			{ label: 'openbalval', name: 's_openbalval', hidden:true},
			{ label: 'netmvval1', name: 's_netmvval1', hidden:true},
			{ label: 'netmvval2', name: 's_netmvval2', hidden:true},
			{ label: 'netmvval3', name: 's_netmvval3', hidden:true},
			{ label: 'netmvval4', name: 's_netmvval4', hidden:true},
			{ label: 'netmvval5', name: 's_netmvval5', hidden:true},
			{ label: 'netmvval6', name: 's_netmvval6', hidden:true},
			{ label: 'netmvval7', name: 's_netmvval7', hidden:true},
			{ label: 'netmvval8', name: 's_netmvval8', hidden:true},
			{ label: 'netmvval9', name: 's_netmvval9', hidden:true},
			{ label: 'netmvval10', name: 's_netmvval10', hidden:true},
			{ label: 'netmvval11', name: 's_netmvval11', hidden:true},
			{ label: 'netmvval12', name: 's_netmvval12', hidden:true},
			//{ label: 'idno', name: 'idno', width: 30, classes: 'wrap', hidden:true},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		height: 124,
		rowNum: 30,
		width: 700,
		pager: "#jqGridPager2",
        
        gridComplete:function(rowdata){
        	var rowid= 1;
        	$("#detail").jqGrid('getRowData').forEach(function(element){
        		getStockvalue(rowid,element);
        		rowid++;
        	});

			if($("#detail").jqGrid('getGridParam', 'selrow') == null){
				$("#detail").setSelection($("#detail").getDataIDs()[0]);
				refreshGrid("#itemExpiry",null,"kosongkan");
			}else{
				$("#detail").setSelection( $("#detail").jqGrid('getGridParam', 'selrow'));
				refreshGrid('#itemExpiry',urlParam3);
			}

		},

		onSelectRow:function(rowid,selected){
			var jq=$('#detail').jqGrid('getRowData',rowid);
			

			if(rowid != null) {
				urlParam3.filterVal[0]=selrowData('#detail').s_itemcode;
				urlParam3.filterVal[1]=selrowData('#detail').s_uomcode;
				urlParam3.filterVal[2]=selrowData('#detail').s_deptcode;
				urlParam3.filterVal[5]=$("#getYear").val();
				$('#deptcodedtl').val(selrowData("#detail").s_deptcode);
				$('#deptcodedtl_').html(selrowData("#detail").d_description);
			}

			refreshGrid('#itemExpiry',urlParam3);
		},
		loadComplete: function(){
			calc_jq_height_onchange("detail");
		},
	});
     

	function TrxType(cellvalue, options, rowObject){
		if(cellvalue != null || cellvalue != undefined ){
			if(cellvalue.toUpperCase() == 'TR'){
				return 'STOCK';
			}else if(cellvalue.toUpperCase() == 'IS'){
				return 'ISSUED';
			}else{
				return cellvalue;
			}
		}
		return '';
	}

	function un_TrxType(cellvalue, options, rowObject){
		if(cellvalue.toUpperCase() == 'STOCK'){
			return 'TR';
		}else if(cellvalue.toUpperCase() == 'ISSUED'){
			return 'IS';
		}else{
			return cellvalue;
		}
	}

	$( "#Syear" ).change(function(){
		let year = $(this).val();
		$("#getYear").val(year);

     	refreshGrid('#jqGrid',urlParam);
	});

	
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam);
			},
		}	
	);

	$("#detail").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
	
    var urlParam3={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['expdate','unit','batchno','balqty','uomcode','itemcode','deptcode'],
		table_name:'material.stockexp',
		table_id:'itemcode',
		sort_itemcode:true,
		filterCol:['itemcode','uomcode','deptcode','compcode','unit','year'],
		filterVal:['','','','session.compcode','session.unit',$("#getYear").val()],
		sortby:['expdate asc']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	$("#itemExpiry").jqGrid({
		datatype: "local",
		 colModel: [
            //{label: 'idno', name: 'idno', hidden: true},
			{ label: 'Unit', name: 'unit', width: 30, classes: 'wrap', hidden:false},
			{ label: 'Expiry Date', name: 'expdate', width: 40, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Batch No', name: 'batchno', width: 40, classes: 'wrap'},
			{ label: 'Balance Quantity',align: 'right', name: 'balqty', width: 40, classes: 'wrap', formatter: 'currency'},
			{ label: 'deptcode', name: 'deptcode', width: 30, classes: 'wrap', hidden:true},
			{ label: 'itemcode', name: 'itemcode', width: 30, classes: 'wrap', hidden:true},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		height: 124,
		rowNum: 30,
		width:450,
		pager: "#jqGridPager3",

		onSelectRow:function(rowid, selected){
			var jg=$("#itemExpiry").jqGrid('getRowData',rowid);
			var itemcodedtl = $('#itemcode').val();

		},
		loadComplete: function(){
			calc_jq_height_onchange("itemExpiry");
		},
	});

	function getStockvalue(rowid,element) {
		var openbalval = ret_parsefloat(element.s_openbalval);
		var netmvval1 = ret_parsefloat(element.s_netmvval1);
		var netmvval2 = ret_parsefloat(element.s_netmvval2);
		var netmvval3 = ret_parsefloat(element.s_netmvval3);
		var netmvval4 = ret_parsefloat(element.s_netmvval4);
		var netmvval5 = ret_parsefloat(element.s_netmvval5);
		var netmvval6 = ret_parsefloat(element.s_netmvval6);
		var netmvval7 = ret_parsefloat(element.s_netmvval7);
		var netmvval8 = ret_parsefloat(element.s_netmvval8);
		var netmvval9 = ret_parsefloat(element.s_netmvval9);
		var netmvval10 = ret_parsefloat(element.s_netmvval10);
		var netmvval11 = ret_parsefloat(element.s_netmvval11);
		var netmvval12 = ret_parsefloat(element.s_netmvval12);

		var total = openbalval + netmvval1 + netmvval2 + netmvval3 + netmvval4 + netmvval5 + netmvval6 + netmvval7 + netmvval8+ netmvval9 + netmvval10 + netmvval11 + netmvval12;

		$('#detail').jqGrid('setRowData', rowid, {s_rackno:total});
	}


	$("#detailMovement").click(function(){
		if(selrowData("#detail").s_deptcode != undefined){
			$("#detailMovementDialog" ).dialog( "open" );
		}else{
			alert('Select department code');
		}
	});

	$("#detailMovementDialog").dialog({
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			DataTable.clear().draw();
			// getdtlmov(false,0,20);
		},
		close: function( event, ui ) {
		},
	});

    $("#itemExpiry").jqGrid('setLabel', 'balqty', 'Balance', {'text-align':'right'});



    //////////////////////////////// TABLE DETAIL MOVEMENT/////////////////////////////////////////////////

   

	var counter=20, moremov=true, DTscrollTop = 0;
	function scroll_next1000(){
		var scrolbody = $(".dataTables_scrollBody")[0];
		$('#but_det').hide();
		DTscrollTop = scrolbody.scrollTop;
		if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
			if(moremov){
				getdtlmov(false,counter,20);
				counter+=20;
			}
		}
	}

	var DataTable = $('#TableDetailMovement').DataTable({
    	order: [[ 0, 'asc' ]],
    	ordering: true,
		responsive: true,
		scrollY: 500,
		paging: false,
		columns: [
			{ data: 'id' ,"width": "5%",visible:false},
			{ data: 'open', sClass: "open"},
			{ data: 'trandate',"type": "date"},
			{ data: 'trantype', sClass: "trantype"},
			{ data: 'description'},
			{ data: 'dept'},
			{ data: 'qtyin', className: "text-right"},
			{ data: 'qtyout', className: "text-right"},
			{ data: 'balquan', className: "text-right"},
			{ data: 'netprice', className: "text-right"},
			{ data: 'amount', className: "text-right"},
			{ data: 'balance', className: "text-right"},
			// { data: 'docno', sClass: "text-right docno"},
			{ data: 'recno', sClass: "text-right recno"},
			{ data: 'mrn', className: "text-right"},
			{ data: 'episno', className: "text-right"},
			{ data: 'adduser'},
			{ data: 'trantime', className: "text-center"},
			
		],
		columnDefs: [
			{ "orderable": false, "targets": [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15] },
			// { "bVisible": false, "aTargets": [0] }
		],
		drawCallback: function( settings ) {
			$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
		},
		initComplete: function( settings, json ) {
	    	$('div#TableDetailMovement_filter.dataTables_filter').hide();
	  	}
	});

	function getdtlmov(fetchall,start,limit){
		// let mon_from = $('#monthfrom').val();
		// let yr_from = $('#yearfrom').val();
		// let mon_to = $('#monthto').val();
		// let yr_to = $('#yearto').val();
		let openbalqty = numeral().unformat($('#openbalqty').val());
		let openbalval = numeral().unformat($('#openbalval').val());

		var param={
					oper:'detailMovement',
					action:'get_value_default',
					url:'./itemEnquiry/form',
					itemcode:selrowData("#detail").s_itemcode,
					deptcode:selrowData("#detail").s_deptcode,
					uomcode:selrowData("#detail").s_uomcode,
					trandate_from:$('#datefrom').val(),
					trandate_to:$('#dateto').val()
				}
		$.get( "./itemEnquiry/form?"+$.param(param), function( data ) {
				
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				let accumamt = ret_parsefloat(openbalval);
				let accumqty = parseInt(openbalqty);
				
				data.rows.forEach(function(obj,id){

					obj.id=id;
					obj.open="<i class='fa fa-folder-open-o fa-2x' data-id="+obj.id+"></i>";
					obj.trandate = moment(obj.trandate).format("DD-MM-YYYY");
					obj.dept = '';
					obj.lineno_=obj.lineno_;
					
					// obj.trantype = '-';
					obj.qtyin = '-';
					obj.qtyout = '-';
					obj.balquan = '-';
					obj.avgcost = numeral(obj.avgcost).format('0,0.00');

					if(obj.det_mov=="deptcode"){

						if (obj.crdbfl.toUpperCase() == 'IN'){
							accumamt = accumamt + ret_parsefloat(obj.amount);
							accumqty = accumqty + parseInt(obj.txnqty);
							obj.balquan = numeral(accumqty).format('0,0');
							obj.netprice = numeral(obj.netprice).format('0,0.00');
							obj.balance = numeral(accumamt).format('0,0.00');
							obj.amount = numeral(obj.amount).format('0,0.00');

							obj.description =  obj.description.toUpperCase();
							obj.dept = obj.deptcode;
							obj.qtyin = numeral(obj.txnqty).format('0,0');
							obj.qtyout = '';
						}else if (obj.crdbfl.toUpperCase() == 'OUT'){
							accumamt = accumamt - ret_parsefloat(obj.amount);
							accumqty = accumqty - parseInt(obj.txnqty);
							obj.balquan = numeral(accumqty).format('0,0');
							obj.netprice = numeral(obj.netprice).format('0,0.00');
							obj.balance = numeral(accumamt).format('0,0.00');
							if(numeral(obj.amount)>0){
								obj.amount = '- '+numeral(obj.amount).format('0,0.00');
							}else{
								obj.amount = numeral(Math.abs(obj.amount)).format('0,0.00');
							}

							obj.description =  obj.description.toUpperCase();
							if(obj.description == 'TRANSFER'){
								obj.description = 'TRANSFER TO'
							}
							obj.dept = obj.sndrcv;
							obj.qtyin = '';
							obj.qtyout = numeral(obj.txnqty).format('0,0');
						}

					}else{

						if (obj.crdbfl.toUpperCase() == 'IN'){
							accumamt = accumamt - ret_parsefloat(obj.amount);
							accumqty = accumqty - parseInt(obj.txnqty);
							obj.balquan = numeral(accumqty).format('0,0');
							obj.netprice = numeral(obj.netprice).format('0,0.00');
							obj.balance = numeral(accumamt).format('0,0.00');
							// obj.amount = '- '+numeral(obj.amount).format('0,0.00');
							
							if(numeral(obj.amount)>0){
								obj.amount = '- '+numeral(obj.amount).format('0,0.00');
							}else{
								obj.amount = numeral(Math.abs(obj.amount)).format('0,0.00');
							}

							obj.description =  obj.description.toUpperCase();
							obj.dept = obj.deptcode;
							obj.qtyin = '';
							obj.qtyout = numeral(obj.txnqty).format('0,0');
						}else if (obj.crdbfl.toUpperCase() == 'OUT'){
							accumamt = accumamt + ret_parsefloat(obj.amount);
							accumqty = accumqty + parseInt(obj.txnqty);
							obj.balquan = numeral(accumqty).format('0,0');
							obj.netprice = numeral(obj.netprice).format('0,0.00');
							obj.balance = numeral(accumamt).format('0,0.00');
							obj.amount = numeral(obj.amount).format('0,0.00');

							obj.description =  obj.description.toUpperCase();
							if(obj.description == 'TRANSFER'){
								obj.description = 'TRANSFER FROM'
							}
							obj.dept = obj.deptcode;
							obj.qtyin = numeral(obj.txnqty).format('0,0');
							obj.qtyout =  '';
						}

					}

				});

				DataTable.rows.add(data.rows.slice().reverse()).draw();
			}else{
				moremov=false;
			}
		});
	}


	function populateSummary(itemcode,uomcode,deptcode){

		let mon_from = moment($('#datefrom').val(), "YYYY/MM/DD").format("MM");
		let yr_from = moment($('#datefrom').val(), "YYYY/MM/DD").format("YYYY");

		let mon_to = moment($('#dateto').val(), "YYYY/MM/DD").format("MM");
		let yr_to = moment($('#dateto').val(), "YYYY/MM/DD").format("YYYY");;

		let param={
			action:'get_value_default',
			url:'util/get_value_default',
			field: ['openbalval','openbalqty','netmvval1','netmvqty1','netmvval2','netmvqty2','netmvval3','netmvqty3','netmvval4','netmvqty4','netmvval5','netmvqty5', 'netmvval6','netmvqty6','netmvval7','netmvqty7','netmvval8','netmvqty8','netmvval9','netmvqty9','netmvval10','netmvqty10',
			'netmvval11','netmvqty11','netmvval12','netmvqty12'],
			table_name:'material.stockloc',
			table_id:'itemcode',
			filterCol:['itemcode', 'uomcode', 'deptcode', 'year'],
			filterVal:[itemcode, uomcode, deptcode, moment($('#datefrom').val(), "YYYY/MM/DD").format("YYYY")]
		}
		$.get( "util/get_value_default?"+$.param(param), function( data ) {
					
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
	            var accumqty=ret_parsefloat(data.rows[0].openbalqty);
	            if(isNaN(accumqty)){
	            	accumqty = 0;
	            }
	            var accumval=ret_parsefloat(data.rows[0].openbalval);
	            if(isNaN(accumval)){
	            	accumval = 0;
	            }
	            var monthfrom = parseInt(moment($('#datefrom').val(), "YYYY/MM/DD").format("MM"));

	            if(monthfrom>0){
				    $.each(data.rows[0], function( index, value ) {
						var lastChar = parseInt(index.match(/\d+/g));

						if(lastChar<monthfrom){
							if(index.startsWith("netmvval")){
								accumval = accumval + ret_parsefloat(value);
							}

							if(index.startsWith("netmvqty")){
								accumqty = accumqty + ret_parsefloat(value);
							}
						}
					});
	            }

	            if(isNaN(accumqty)){
	            	accumqty = 0;
	            }if(isNaN(accumval)){
	            	accumval = 0;
	            }

				$("#openbalqty").val(numeral(accumqty).format('0,0.00'));
				$("#openbalval").val(numeral(accumval).format('0,0.00'));


				getdtlmov()
			}
		});
	}

    $('#search').click(function(){
    	DataTable.clear().draw();
		populateSummary($('#itemcodedtl').val(),$('#uomcodedtl').val(),$('#deptcodedtl').val());
	});

	$('#print').click(function(){
		window.open('./itemEnquiry/table?action=print_excel&itemcode='+$('#itemcodedtl').val()+'&deptcode='+$("#deptcodedtl").val()+'&uomcode='+$("#uomcodedtl").val()+'&trandate_from='+$("#datefrom").val()+'&trandate_to='+$("#dateto").val(), '_blank');
	});

	// set_yearperiod();
	// function set_yearperiod(){
	// 	param={
	// 		action:'get_value_default',
	// 		field: ['year'],
	// 		table_name:'sysdb.period',
	// 		table_id:'idno',
	// 		sortby:['year desc']
	// 	}
	// 	$.get( "util/get_value_default?"+$.param(this.param), function( data ) {
				
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data.rows)){
	// 			data.rows.forEach(function(element){	
	// 				$('#yearfrom').append("<option>"+element.year+"</option>")
	// 				$('#yearto').append("<option>"+element.year+"</option>")
	// 			});
	// 		}
	// 	});

	// 	$('select#monthfrom').val(moment().format('MM'));
	// 	$('select#monthto').val(moment().format('MM'));
	// }




	//////////handle searching, its radio button and toggle /////////////////////////////////////////////// 
	toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	toogleSearch('#sbut2','#searchForm2','off');
	populateSelect('#detail','#searchForm2');
	searchClick('#detail','#searchForm2',urlParam2);

	toogleSearch('#sbut3','#searchForm3','off');
	populateSelect('#itemExpiry','#searchForm3');
	searchClick('#itemExpiry','#searchForm3',urlParam3);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#detail',true,urlParam2);
	
	//addParamField('#jqGrid',false,saveParam,['idno']);
	//addParamField('#detail',false,urlParam2,['idno']);

	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$('#TableDetailMovement').on('click','td.open', function () {
		var tr = $(this).parent('tr');
		var row = DataTable.row(tr).data();
		let src = null;
		let pdf = null;

		switch(row.trantype){
			case 'DS' :
				if(row.mrn != '-' && row.episno != '-'){
					src = './reprintBill?viewonly=viewonly&auditno='+row.recno+'&lineno_='+row.lineno_;
				}else{
					src = './SalesOrder?scope=ALL&viewonly=viewonly&auditno='+row.recno;
				}
				break;
			case 'GRN' :
				src = './deliveryOrder?scope=ALL&viewonly=viewonly&recno='+row.recno;
				break;
			case 'TUI' :
				pdf = './inventoryTransaction/showpdf?recno='+row.recno;
				break;
			case 'TUO' :
				pdf = './inventoryTransaction/showpdf?recno='+row.recno;
				break;
		}

		if(src != null){
			$('iframe#open_detail_iframe').attr('src',src);
			$("#open_detail_dialog").dialog("open");
		}else if(pdf != null){
			window.open(pdf, '_blank')
		}
	});



	// $('#TableDetailMovement').on('click','tbody td', function() {

	//   	var recno = $(this).children('i').data('recno');
	// 	var trantype = $(this).children('i').data('trantype');

    //     $('iframe#open_detail_iframe').attr('src','./itemEnquiry/table?action=open_detail&recno='+recno+'&trantype='+trantype);
	// 	$("#open_detail_dialog").dialog("open");
	// })
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

$("#open_detail_dialog").dialog({
	width: 9/10 * $(window).width(),
	modal: true,
	autoOpen: false,
	open: function( event, ui ) {
	},
	close: function( event, ui ) {
	},
});

function dialogForm_SalesOrder(obj_id){
	param={
		url: './itemEnquiry/table',
		action:'dialogForm_SalesOrder',
		trantype: obj_id.trantype,
		docno: obj_id.docno,
	}

	$.get( param.url+"?"+$.param(param), function( data ) {
			
	},'json').done(function(data) {
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
	});
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

function getYear(){
	let param={
		action:'get_table_default',
		url:'util/get_table_default',
		field: ['*'],
		table_name:'sysdb.period',
		table_id:'idno',
		filterCol:['compcode'],
		filterVal:['session.compcode'],
	}

	$.get( "util/get_value_default?"+$.param(param), function( data ) {
					
	},'json').done(function(data) {
		if(!$.isEmptyObject(data.rows)){
			var lastyear;
			data.rows.forEach(function(e,i){
				lastyear = e.year;
				if(i == data.rows.length - 1){
					$( "#Syear" ).prepend( "<option val="+e.year+" selected>"+e.year+"</option>" );
				}else{
					$( "#Syear" ).prepend( "<option val="+e.year+">"+e.year+"</option>" );
				}
			});
		}
	});
}
