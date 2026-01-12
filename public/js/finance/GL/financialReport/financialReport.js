$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	set_yearperiod();
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

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		filterCol:['compcode', 'recstatus'],
		filterVal:['session.compcode', 'ACTIVE'],
		table_name:'sysdb.sector',
		table_id:'idno',
		sort_idno:true,
	}

	$('input[type=radio][name=Class]').change(function(){
		var thisval = $(this).val();
		switch(thisval){
			case 'All':
				$('div.divto,#row_sector').show();
				$('#row_dept').hide();
				break;
			case 'Department':
				$('#row_dept').show();
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				$('div.divto,#row_sector').hide();
				break;
			case 'Units':
				$('div.divto').show();
				$('#row_dept,#row_sector').hide();
				break;
			case 'Variance':
				$('div.divto').hide();
				$('#row_dept,#row_sector').hide();
				break;
		}
	});

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label:'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label:'compcode', name: 'compcode', hidden:true},
			{ label:'Unit',name:'sectorcode',width:200,classes:'pointer', canSearch: true, or_search: true },
			{ label:'Description',name:'description',width:500,classes:'pointer', canSearch: true, checked: true, or_search: true },
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 550,
		height: 150,
		rowNum: 50,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
		},
		gridComplete: function(){
		},
	});
	addParamField('#jqGrid',true,urlParam);

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	var urlParam2={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		filterCol:['compcode', 'recstatus'],
		filterVal:['session.compcode', 'ACTIVE'],
		table_name:'sysdb.department',
		table_id:'idno',
		sort_idno:true,
	}
	$("#jqGrid2").jqGrid({
		datatype: "local",
		colModel: [
			{ label:'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label:'compcode', name: 'compcode', hidden:true},
			{ label:'Department Code',name:'deptcode',width:200,classes:'pointer', canSearch: true, or_search: true },
			{ label:'Description',name:'description',width:500,classes:'pointer', canSearch: true, checked: true, or_search: true },
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 550,
		height: 150,
		rowNum: 50,
		pager: "#jqGrid2Pager",
		onSelectRow:function(rowid, selected){
		},
		gridComplete: function(){
		},
	});
	addParamField('#jqGrid2',true,urlParam2);

	$("#jqGrid2").jqGrid('navGrid','#jqGrid2Pager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid2",urlParam2);
		},
	});
	
    /////////////////////////////////////dialog handler///////////////////////////////
	var reporttype = new ordialog(
		'reporttype','finance.glrpthdr','#reportname','errorField',
		{	
			colModel:[
				{label:'Report Name',name:'rptname',width:200,classes:'pointer', canSearch: true, or_search: true, checked: true },
				{label:'Report Type',name:'rpttype',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Report Description',name:'description',width:400,classes:'pointer', canSearch: true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
			},
			ondblClickRow: function () {
				let data=selrowData('#'+reporttype.gridname);

				$("#checkbs").show();
				$("#checkbs_span").text(data['rpttype']);

				if(data['rpttype'] == 'PROFIT & LOSS (DETAIL)'){
					$("#reporttype").val(1);
					$('div.divto,div.topradio').show();
				}else if(data['rpttype'] == 'BALANCE SHEET'){
					$("#reporttype").val(2);
					$('div.divto,div.topradio').hide();
				}else{
					$("#reporttype").val(3);
					$('div.divto,div.topradio').show();
				}
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
			title:"Select Supplier Group",
			open: function(){
				reporttype.urlParam.filterCol=['compcode'];
				reporttype.urlParam.filterVal=['session.compcode'];
			},
			close: function(obj_){
			},
		},'urlParam','radio','tab'
	);
	reporttype.makedialog(true);

	$('#summary_excel').click(function(){

		if($('#reportname').val()==''){
			alert('Report type is Null!');
			return 0;
		}
		var reporttype = $("#reporttype").val();

		window.open('./financialReport/table?action=genexcel&monthfrom='+$("#monthfrom").val()+'&monthto='+$("#monthto").val()+'&yearfrom='+$("#yearfrom").val()+'&yearto='+$("#yearto").val()+'&reportname='+$("#reportname").val()+'&reporttype='+$("#reporttype").val()+'&Class='+$('input:radio[name=Class]:checked').val(), '_blank');
	});

	$("#checkbs").click(function() {
		let action = 'check';
		let year = $('#yearfrom').val();
		let month = $('#monthfrom').val();
		let monthto = $('#monthto').val();
		let rptname = $("#reportname").val();
		let url = './financialReport/table?action='+action+'&year='+year+'&month='+month+'&monthto='+monthto+'&rptname='+rptname+'&datatables=exclude';

		window.open(url, '_blank');
	});

});

function set_yearperiod(){
	// param={
	// 	action:'get_value_default',
	// 	field: ['year'],
	// 	table_name:'sysdb.period',
	// 	table_id:'idno',
	// 	sortby:['year desc']
	// }
	// $.get( "util/get_value_default?"+$.param(this.param), function( data ) {
			
	// },'json').done(function(data) {
	// 	if(!$.isEmptyObject(data.rows)){
	// 		data.rows.forEach(function(element){	
	// 			$('#yearfrom').append("<option>"+element.year+"</option>")
	// 			$('#yearto').append("<option>"+element.year+"</option>")
	// 		});
	// 	}
	// });

	$('select#monthfrom').val(moment().format('M'));
	$('select#monthto').val(moment().format('M'));
}