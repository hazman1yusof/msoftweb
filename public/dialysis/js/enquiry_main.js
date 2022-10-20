
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var urlParam = {
		action: 'patmast_current_patient',
		url: './enquiry/table'
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno',hidden: true, key:true},
			{ label: 'MRN', name: 'MRN', width: 5, classes: 'wrap', formatter: padzero, unformat: unpadzero },
			{ label: 'Episode', name: 'Episno', width: 5 ,align: 'left',classes: 'wrap' },
			// { label: 'Time', name: 'reg_time', width: 10 ,classes: 'wrap', formatter: timeFormatter, unformat: timeUNFormatter},
			{ label: 'Name', name: 'Name', width: 30 ,classes: 'wrap' },
			{ label: 'Payer', name: 'payer', width: 20 ,classes: 'wrap' },
			{ label: 'I/C', name: 'Newic', width: 15 ,classes: 'wrap' },
			{ label: 'DOB', name: 'DOB', hidden: true},
			{ label: 'HP', name: 'telhp', hidden:true},
			{ label: 'Sex', name: 'Sex', width: 5 ,classes: 'wrap' },
			{ label: 'Last<br/>Arrival Date', name: 'arrival_date', width: 7. ,align: 'center', formatter:dateFormatter2, unformat:dateUNFormatter2},
			{ label: 'dialysis date', name: 'arrival_time',hidden: true},
			{ label: 'Arrival', name: 'arrival', width: 5 ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Complete', name: 'complete', width: 6 ,align: 'center', formatter:formatterstatus_tick, unformat:UNformatterstatus_tick},
			{ label: 'Nursing', name: 'nurse', width: 5 ,align: 'center', formatter:formatterstatus_tick2, unformat:UNformatterstatus_tick},
			{ label: 'Order', name: 'order', hidden: true},
			{ label: 'RaceCode', name: 'RaceCode', hidden: true },
			{ label: 'religion', name: 'religion', hidden: true },
			{ label: 'OccupCode', name: 'OccupCode', hidden: true },
			{ label: 'Citizencode', name: 'Citizencode', hidden: true },
			{ label: 'AreaCode', name: 'AreaCode', hidden: true },
		],
		autowidth: true,
		viewrecords: true,
		width: 900,
		height: 300,
		rowNum: 50,
		pager: "#jqGridPager",
		loadonce:false,
		onSelectRow:function(rowid, selected){
			populatenquiry(selrowData('#jqGrid'));
			closealltab();
			cleartabledata('all');
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		onSortCol: function(index, iCol, sortorder) {
		},
		gridComplete: function () {
		},
		beforeProcessing: function(data, status, xhr){
		}
	});
	jqgrid_label_align('#jqGrid');
	addParamField('#jqGrid',true,urlParam,['action']);
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			curpage=null;
			refreshGrid("#jqGrid", urlParam);
		},
	});

	searchClick_scroll("#jqGrid","#SearchForm",urlParam);

	stop_scroll_on();

});

function populatenquiry(data){
	$('span.metal').text(data.Name+' - MRN:'+data.MRN);
	$('#mrn').val(data.MRN);
	$('#episno').val(data.Episno);
}

function closealltab(except){
	var tab_arr = ["#tab_trans","#tab_daily","#tab_weekly","#tab_monthly"];
	tab_arr.forEach(function(e,i){
		if(e != except){
			$(e).collapse('hide');
		}
	});
}

function searchClick_scroll(grid,form,urlParam){
	$(form+' [name=Stext]').on( "keyup", function() {
		delay(function(){
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
		}, 500 );
	});

	$(form+' [name=Scol]').on( "change", function() {
		search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
	});
}

function formatterstatus_tick(cellvalue, option, rowObject) {
	if (cellvalue != null && cellvalue != 0 && moment().isSame(rowObject.arrival_date, 'day')) {
		return '<span class="fa fa-check" data-value="'+cellvalue+'"></span>';
	}else{
		return "";//if value is zero will capture as "" when unformat
	}
}

function formatterstatus_tick2(cellvalue, option, rowObject) {
	if (cellvalue != null && cellvalue != 0 ) {
		return '<span class="fa fa-check" data-value="'+cellvalue+'"></span>';
	}else{
		return "";//if value is zero will capture as "" when unformat
	}
}

function UNformatterstatus_tick(cellvalue, option, cell) {
	if($('span.fa', cell).data('value') == undefined){
		return 0;
	}else{
		return $('span.fa', cell).data('value');
	}
}

function stop_scroll_on(){
	$('div.paneldiv').on('mouseenter',function(){
		SmoothScrollTo('#'+$('div.mainpanel[aria-expanded=true]').parent('div.panel.panel-default').attr('id'), 300,undefined,40);
		$('body').addClass('stop-scrolling');
	});

	$('div.paneldiv').on('mouseleave',function(){
		$('body').removeClass('stop-scrolling')
	});
}

function dateFormatter2(cellvalue, options, rowObject){
	if(cellvalue == null) return '';
	return moment(cellvalue).format("DD/MM/YYYY") + '</br>' + rowObject.arrival_time + `<span data-original=`+cellvalue+`><span>`;
}

function dateUNFormatter2(cellvalue, options, rowObject){
	return $(rowObject).children('span').data('original');
}


