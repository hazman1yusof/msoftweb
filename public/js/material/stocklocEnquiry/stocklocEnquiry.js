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
	//var Class2 = $('#Class2').val();
	var fdl = new faster_detail_load();
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

	var oper = 'add';
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
				
			}
			if(oper!='add'){
				toggleFormData('#jqGrid','#formdata');
				
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
		field:['s.deptcode','s.itemcode'],
		fixPost: true,
		table_name:['material.stockloc as s','material.product as p'],
		table_id:'s_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['s.itemcode'],
		join_onVal:['p.itemcode'],
		join_filterCol: [['s.compcode on =','s.uomcode on =']],
		join_filterVal: [['p.compcode','p.uomcode']]
	}
	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
            {label: 'idno', name: 's_idno', hidden: true},
            { label: 'Department Code', name: 's_deptcode', width: 20, classes: 'wrap', canSearch: true,selected:true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Item code', name: 's_itemcode', width: 20, classes: 'wrap', canSearch: true,selected:true},		
			{ label: 'Description', name: 'p_description', width: 30, classes: 'wrap', checked:true,canSearch: true,selected:true},
			{ label: 'UOM Code', name: 's_uomcode', width: 20, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Quantity on Hand', name: 's_qtyonhand', width: 20,classes: 'wrap',align: 'right'},
			{ label: 'Stock TrxType', name: 's_stocktxntype', width: 20, classes: 'wrap'},
		 	{ label: 'Min Stock Qty', name: 's_minqty', width: 15, classes: 'wrap',align: 'right'},
			{ label: 'Max Stock Qty', name: 's_maxqty', width: 15, classes: 'wrap',align: 'right'},
			{ label: 'Reorder Level', name: 's_reordlevel', width: 15, classes: 'wrap', align: 'right'},
			{ label: 'Reorder Qty', name: 's_reordqty', width: 15, classes: 'wrap',align: 'right'},
			{ label: 'DisType', name: 's_disptype', width: 10, classes: 'wrap'},
			{label: 'openbalqty', name: 's_openbalqty', width: 90 , hidden: true},
			{label: 'openbalval', name: 's_openbalval', width: 90 , hidden: true},
			{label: 'netmvval1', name: 's_netmvval1', width: 90 , hidden: true},
			{label: 'netmvqty1', name: 's_netmvqty2', width: 90 , hidden: true},
			{label: 'netmvval3', name: 's_netmvval3', width: 90 , hidden: true},
			{label: 'netmvqty3', name: 's_netmvqty3', width: 90 , hidden: true},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		sortname: 's_idno',
		sortorder: 'desc',
		loadonce:false,
		height: 124,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){

			var jg=$("#jqGrid").jqGrid('getRowData',rowid);
			if(rowid != null) {
				populateSummary('#details',selrowData('#jqGrid').s_itemcode, selrowData('#jqGrid').s_uomcode, selrowData('#jqGrid').s_deptcode);
			}

		},
		gridComplete: function(){
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
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
		$("#jqGrid").jqGrid('setLabel', 'minqty', 'Min Stock Qty', {'text-align':'right'});
		$("#jqGrid").jqGrid('setLabel', 'maxqty', 'Max Stock Qty', {'text-align':'right'});
		$("#jqGrid").jqGrid('setLabel', 'reordqty', 'Reorder Qty', {'text-align':'right'});
		$("#jqGrid").jqGrid('setLabel', 'reordlevel', 'Reorder Level', {'text-align':'right'});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 's_deptcode':field=['deptcode','description'];table="sysdb.department";case_='s_deptcode';break;
			case 's_uomcode':field=['uomcode','description'];table="material.uom";case_='s_uomcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('stocklocEnquiry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	//////////handle searching, ///////////////////////////////////////////////////
	searchClick2('#jqGrid','#searchForm',urlParam);

	///////////////////utk dropdown search By/////////////////////////////////////////////////
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
		});
	}

	$('#searchText').keyup(function() {
		delay(function(){
			searchMain($('#searchText').val(),$('#Scol').val());
		}, 500 );
	});

	$('#Scol').change(function(){
		searchMain($('#searchText').val(),$('#Scol').val());
	});

	function populateSummary(form,itemcode,uomcode,deptcode){

		emptyFormdata([],form);
		$(form+' #netmvval1').val(selrowData('#jqGrid').netmvval1);

		let param={
			action:'get_value_default',
			url:'util/get_value_default',
			field: ['openbalval','openbalqty','netmvval1','netmvqty1','netmvval2','netmvqty2','netmvval3','netmvqty3','netmvval4','netmvqty4','netmvval5','netmvqty5', 'netmvval6','netmvqty6','netmvval7','netmvqty7','netmvval8','netmvqty8','netmvval9','netmvqty9','netmvval10','netmvqty10',
			'netmvval11','netmvqty11','netmvval12','netmvqty12'],
			table_name:'material.stockloc',
			table_id:'itemcode',
			filterCol:['itemcode', 'uomcode', 'deptcode', 'year'],
			filterVal:[itemcode, uomcode, deptcode, $("#getYear").val()]
		}
		$.get( "util/get_value_default?"+$.param(param), function( data ) {
					
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				$(form+' #openbalqty').val(data.rows[0].openbalqty);
				$(form+' #openbalval').val(data.rows[0].openbalval);
				$(form+' #netmvqty1').val(data.rows[0].netmvqty1);
				$(form+' #netmvval1').val(data.rows[0].netmvval1);
				$(form+' #netmvqty2').val(data.rows[0].netmvqty2);
				$(form+' #netmvval2').val(data.rows[0].netmvval2);
				$(form+' #netmvqty3').val(data.rows[0].netmvqty3);
				$(form+' #netmvval3').val(data.rows[0].netmvval3);
				$(form+' #netmvqty4').val(data.rows[0].netmvqty4);
				$(form+' #netmvval4').val(data.rows[0].netmvval4);
				$(form+' #netmvqty5').val(data.rows[0].netmvqty5);
				$(form+' #netmvval5').val(data.rows[0].netmvval5);
				$(form+' #netmvqty6').val(data.rows[0].netmvqty6);
				$(form+' #netmvval6').val(data.rows[0].netmvval6);
				$(form+' #netmvqty7').val(data.rows[0].netmvqty7);
				$(form+' #netmvval7').val(data.rows[0].netmvval7);
				$(form+' #netmvqty8').val(data.rows[0].netmvqty8);
				$(form+' #netmvval8').val(data.rows[0].netmvval8);
				$(form+' #netmvqty9').val(data.rows[0].netmvqty9);
				$(form+' #netmvval9').val(data.rows[0].netmvval9);
				$(form+' #netmvqty10').val(data.rows[0].netmvqty10);
				$(form+' #netmvval10').val(data.rows[0].netmvval10);
				$(form+' #netmvqty11').val(data.rows[0].netmvqty11);
				$(form+' #netmvval11').val(data.rows[0].netmvval11);
				$(form+' #netmvqty12').val(data.rows[0].netmvqty12);
				$(form+' #netmvval12').val(data.rows[0].netmvval12);

	            var accumqty=0;
			    var netmvqty=0;
			    $.each(data.rows[0], function( index, value ) {
				    if(!isNaN(parseInt(value)) && index.indexOf('netmvqty') !== -1){
						accumqty+=parseInt(value);
					}
				});

				$("#accumqty").val(accumqty);

	            var accumval=0;
			    var netmvval=0;
		    
			    $.each(data.rows[0], function( index, value ) {
				    if(!isNaN(parseInt(value)) && index.indexOf('netmvval') !== -1){
						accumval+=parseInt(value);
					}
				});

				$("#accumval").val(accumval);
			}
		});
	}
	
	function searchMain(Stext,Scol){

		if(Scol == 'itemcode'){
			$('#searchText').prop('disabled',true);
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			//urlParam.filterCol=['source','trantype'];
			//urlParam.filterVal=['PB','IN'];
		}else{
			$('#searchText').prop('disabled',false);
			//urlParam.filterCol=['source'];
			//urlParam.filterVal=['PB'];

			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if(Stext.trim() != ''){
				var split = Stext.split(" "),searchCol=[],searchVal=[];
				$.each(split, function( index, value ) {
					searchCol.push(Scol);
					searchVal.push('%'+value+'%');
				});
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}
		}
     	refreshGrid('#jqGrid',urlParam);
	}

	addParamField('#jqGrid',true,urlParam);
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