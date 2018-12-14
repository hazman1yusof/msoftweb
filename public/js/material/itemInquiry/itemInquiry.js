$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	
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
	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			parent_close_disabled(true);
			switch(oper) {
				case state = 'add':
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					hideOne('#formdata');
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					$('#formdata :input[hideOne]').show();
					break;
				case state = 'view':
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					$('#formdata :input[hideOne]').show();
					$(this).dialog("option", "buttons",butt2);
					break;
			}
			if(oper!='view'){
				//dialog_dept.handler(errorField);
			}
			if(oper!='add'){
				toggleFormData('#jqGrid','#formdata');
				//dialog_dept.check(errorField);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			$('#formdata .alert').detach();
			$("#formdata a").off();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'material.product',
		table_id:'idno',
		sort_idno:true,
		filterCol:['compcode','unit','Class'],
		filterVal:['session.compcode','session.unit', $('#Class2').val()]
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            { label: 'idno', name: 'idno', hidden: true},
            { label: 'Unit', name: 'unit', width: 20},
			{ label: 'Item code', name: 'itemcode', width: 20, classes: 'wrap', canSearch: true},						
			{ label: 'Item Description', name: 'description', width: 80, classes: 'wrap', checked:true,canSearch: true},
			{ label: 'UOM Code', name: 'uomcode', width: 20, classes: 'wrap'},
			{ label: 'Quantity on Hand', name: 'qtyonhand', width: 30,classes: 'wrap',align: 'right'},
			{ label: 'Average Cost', name: 'avgcost', width: 30,classes: 'wrap',align: 'right'},
			{ label: 'Current Price', name: 'currprice', width: 30, classes: 'wrap',align: 'right'},

			
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		height: 124,
		sortname: 'idno',
		sortorder: 'desc',
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			if(rowid != null) {
				urlParam2.filterVal[0]=selrowData("#jqGrid").itemcode; 
				urlParam2.filterVal[1]=selrowData("#jqGrid").uomcode;
				refreshGrid('#detail',urlParam2);

				urlParam3.filterVal[0]=selrowData("#jqGrid").itemcode;
									
				refreshGrid('#itemExpiry',urlParam3);
			}
		},
	});
	
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',
		{	
			edit:false,view:false,add:false,del:false,search:false,
			beforeRefresh: function(){
				refreshGrid("#jqGrid",urlParam);
			},
			
		}	
	);

	 $("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Quantity on Hand', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'avgcost', 'Average Cost', {'text-align':'right'});
     $("#jqGrid").jqGrid('setLabel', 'currprice', 'Current Price', {'text-align':'right'});

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['idno','unit','deptcode','stocktxntype','uomcode','qtyonhand','openbalval','itemcode','netmvval1','netmvval2','netmvval3','netmvval4','netmvval5','netmvval6','netmvval7','netmvval8','netmvval9','netmvval10','netmvval11','netmvval12','computerid'],
		table_name:'material.stockloc',
		table_id:'idno',
		filterCol:['itemcode', 'uomcode','year','compcode','unit'],
		filterVal:['', '',$("#getYear").val(), 'session.compcode', 'session.unit'],
	}

	$("#detail").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, classes: 'wrap', hidden:true},
		 	{ label: 'Department Code', name: 'deptcode', width: 40, classes: 'wrap'},
			{ label: 'Unit', name: 'unit', width: 30, classes: 'wrap', hidden:false},
			{ label: 'Stock TrxType', name: 'stocktxntype', width: 40, classes: 'wrap'},
			{ label: 'UOM Code', name: 'uomcode', width: 40, classes: 'wrap'},
			{ label: 'Quantity on Hand', name: 'qtyonhand', width: 40, classes: 'wrap',align: 'right'},
			{ label: 'itemcode', name: 'itemcode', width: 40, classes: 'wrap',hidden:true},
			{ label: 'Stock Value', name: 'rackno', width: 40, classes: 'wrap', formatter: 'number', formatoptions: {decimalSeperator: '.',devimalPlaces:2,defaultValue: '0.0000'}},
			{ label: 'openbalval', name: 'openbalval', hidden:true},
			{ label: 'netmvval1', name: 'netmvval1', hidden:true},
			{ label: 'netmvval2', name: 'netmvval2', hidden:true},
			{ label: 'netmvval3', name: 'netmvval3', hidden:true},
			{ label: 'netmvval4', name: 'netmvval4', hidden:true},
			{ label: 'netmvval5', name: 'netmvval5', hidden:true},
			{ label: 'netmvval6', name: 'netmvval6', hidden:true},
			{ label: 'netmvval7', name: 'netmvval7', hidden:true},
			{ label: 'netmvval8', name: 'netmvval8', hidden:true},
			{ label: 'netmvval9', name: 'netmvval9', hidden:true},
			{ label: 'netmvval10', name: 'netmvval10', hidden:true},
			{ label: 'netmvval11', name: 'netmvval11', hidden:true},
			{ label: 'netmvval12', name: 'netmvval12', hidden:true},
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
		},

		onSelectRow:function(rowid,selected){
			var jq=$('#detail').jqGrid('getRowData',rowid);
			urlParam3.filterVal[0]=selrowData('#detail').itemcode;
			urlParam3.filterVal[1]=selrowData('#detail').uomcode;
			urlParam3.filterVal[2]=selrowData('#detail').deptcode;

			refreshGrid('#itemExpiry',urlParam3);
		}
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
		/*filterCol:['itemcode', 'uomcode','deptcode'],
		filterVal:['', '',''],*/
		filterCol:['itemcode','uomcode','deptcode','compcode','unit'],
		filterVal:['','','','session.compcode','session.unit'],
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
			{ label: 'Balance Quantity', name: 'balqty', width: 40, classes: 'wrap'},
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
			var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			
		},
	});

	function getStockvalue(rowid,element) {
		var openbalval = parseFloat(element.openbalval);
		var netmvval1 = parseFloat(element.netmvval1);
		var netmvval2 = parseFloat(element.netmvval2);
		var netmvval3 = parseFloat(element.netmvval3);
		var netmvval4 = parseFloat(element.netmvval4);
		var netmvval5 = parseFloat(element.netmvval5);
		var netmvval6 = parseFloat(element.netmvval6);
		var netmvval7 = parseFloat(element.netmvval7);
		var netmvval8 = parseFloat(element.netmvval8);
		var netmvval9 = parseFloat(element.netmvval9);
		var netmvval10 = parseFloat(element.netmvval10);
		var netmvval11 = parseFloat(element.netmvval11);
		var netmvval12 = parseFloat(element.netmvval12);

		var total = openbalval + netmvval1 + netmvval2 + netmvval3 + netmvval4 + netmvval5 + netmvval6 + netmvval7 + netmvval8+ netmvval9 + netmvval10 + netmvval11 + netmvval12;

		$('#detail').jqGrid('setRowData', rowid, {rackno:total});
	}

	var detailMovement=[{
		id: 'detailMovement',
		text: "Detail Movement",click: function() {
			$("#detailMovementDialog" ).dialog( "open" );
		}
	}];


	$("#detailMovementDialog")
		.dialog({
		width: 6/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			rdonly("#detailMovementDialog");
			set_compid_from_storage("input[name='computerid']", "input[name='ipaddress']");
		},
		close: function( event, ui ) {
			emptyFormdata([],'#dmFormdata');
			$('.alert').detach();
			$("#dmFormdata a").off();
		},
		//buttons :addNew2,
	});


    $("#itemExpiry").jqGrid('setLabel', 'balqty', 'Balance Quantity', {'text-align':'right'});

    //////////////////////////////// TABLE DETAIL MOVEMENT/////////////////////////////////////////////////

    function populateTable(){
		selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
		rowData = $("#jqGrid").jqGrid ('getRowData', selrow);
		$.each(rowData, function( index, value ) {
			if(value){
				$('#TableDetailMovement #'+index+' span').text(numeral(value).format('0,0.00'))
			}else{
				$('#TableDetailMovement #'+index+' span').text("0.00");
			}
		});
	}

	var counter=20, moredr=true, morecr=true, DTscrollTop = 0;
		function scroll_next1000(){
			var scrolbody = $(".dataTables_scrollBody")[0];
			$('#but_det').hide();
			DTscrollTop = scrolbody.scrollTop;
			if (scrolbody.scrollHeight - scrolbody.scrollTop === scrolbody.clientHeight) {
				if(moredr || morecr){
					mymodal.show("#TableDetailMovement");
					getdatadr(false,counter,20);
					getdatacr(false,counter,20);
					counter+=20;
				}
			}
	}


	var DataTable = $('#TableDetailMovement').DataTable({
		responsive: true,
		scrollY: 500,
		paging: false,
		columns: [
			{ data: 'trandate' ,"width": "5%"},
			{ data: 'trantype'},
			
		],
		drawCallback: function( settings ) {
			$(".dataTables_scrollBody")[0].scrollTop = DTscrollTop;
		}
	});

			
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
	//addParamField('#jqGrid',false,saveParam,['idno']);
	//addParamField('#detail',false,urlParam2,['idno']);

	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();
});
