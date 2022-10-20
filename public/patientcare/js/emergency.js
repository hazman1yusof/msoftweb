
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	// function calanderposition(){
	// 	var width = Math.floor($("#colmd_outer")[0].offsetWidth - $("#colmd_outer")[0].offsetLeft);
	// 	$('#mydate_glpd').css('width',width);
	// 	$('#mydate_glpd').css('height',width);
	// }
	// calanderposition();

	// var gldatepicker = $('#mydate').glDatePicker({
	// 	zIndex: 0,
	// 	showAlways: true,
	// 	onClick: function(target, cell, date, data) {
	// 		urlParam.filterVal[0] = moment(date).format('YYYY-MM-DD');
	// 		refreshGrid("#jqGrid", urlParam);
	//     }
	// }).glDatePicker(true);
	$('#calendar').fullCalendar({
  		defaultView: 'month',
  		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,listMonth'
		},
		buttonText:{
			today: "Today"
		},
		contentHeight:"auto",
		dayClick: function(date, allDay, jsEvent, view) {
			$( ".fc-bg td.fc-day" ).removeClass( "selected_day" );
			$(this).addClass( "selected_day" );

			urlParam.filterVal[0] = date.format('YYYY-MM-DD');
			refreshGrid("#jqGrid", urlParam);

		},
		eventRender: function(eventObj, $el) {
			$(".fc-today-button").html('<small class="mysmall">'+moment().format('ddd')+'</small><br/><b class="myb">'+moment().format('DD')+'</b>');
			// $('div.fc-right').append('<p>sdssd</p>').insertAfter
		},
		eventClick: function(event) {
			var view = $('#calendar').fullCalendar('getView');
			if(view.type == 'listMonth'){
				urlParam.filterVal[0] = event.start.format('YYYY-MM-DD');
				refreshGrid("#jqGrid", urlParam);
			}
		},
		eventSources: [
			{	
				id: 'doctornote_event',
				url: './doctornote/table',
				type: 'GET',
				data: {
					type: 'apptbook',
					action: 'doctornote_event'
				}
			},
	    ]

	});

	var urlParam = {
		action: 'get_table_default',
		url: $('#util_tab').val(),
		field: '',
		fixPost:'true',
		table_name:['hisdb.episode as e','hisdb.pat_mast as p'],
		join_type:['LEFT JOIN'],
		join_onCol:['e.mrn'],
		join_onVal:['p.mrn'],
		filterCol:['e.reg_date'],
		filterVal:[moment().format('YYYY-MM-DD')],
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'e_idno', width: 5, hidden: true },
			{ label: 'MRN', name: 'e_mrn', width: 12, classes: 'wrap', formatter: padzero, unformat: unpadzero, canSearch: true, checked: true,  },
			{ label: 'Epis. No', name: 'e_episno', width: 10 ,canSearch: true,classes: 'wrap' },
			// { label: 'Registered Date', name: 'e_reg_date', width: 15 ,classes: 'wrap' },
			// { label: 'Registered Time', name: 'e_reg_time', width: 15 ,classes: 'wrap' },
			{ label: 'Name', name: 'p_name', width: 30 ,canSearch: true,classes: 'wrap' },
			{ label: 'Action', name: 'action', width: 30 ,canSearch: true,classes: 'wrap', formatter: formatterRemarks,unformat: unformatRemarks}
		],
		autowidth: true,
		viewrecords: true,
		width: 900,
		height: 365,
		rowNum: 30,
		onSelectRow:function(rowid, selected){
			//kalau dialysis
			// populatedialysis(selrowData('#jqGrid'),urlParam.filterVal[0]);

			//habis kalau dialysis

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		gridComplete: function () {

		},
	});
	addParamField('#jqGrid',true,urlParam,['action']);
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	function formatterRemarks(cellvalue, options, rowObject){
		return "<a class='remarks_button btn btn-success btn-xs' target='_blank' href='./upload?mrn="+rowObject.e_mrn+"&episno="+rowObject.e_episno+"' > upload </a>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

});
