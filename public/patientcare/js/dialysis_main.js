
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	$('#calendar').fullCalendar({
		// events: events,
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

			$('#sel_date').val(date.format('YYYY-MM-DD'));
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
				id:'appt_main',
				url:'./dialysis_event',
				type:'GET'
			},
	    ]


	});

	var urlParam = {
		action: 'get_table_doctornote',
		url: './ptcare_doctornote/table',
		filterVal : [moment().format("YYYY-MM-DD")]
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'MRN', name: 'MRN', width: 9, classes: 'wrap', formatter: padzero, unformat: unpadzero, checked: true,  },
			{ label: ' ', name: 'Episno', width: 5 ,align: 'right',classes: 'wrap' },
			{ label: 'Time', name: 'reg_time', width: 10 ,classes: 'wrap', formatter: timeFormatter, unformat: timeUNFormatter},
			{ label: 'Name', name: 'Name', width: 15 ,classes: 'wrap' },
			{ label: 'Payer', name: 'payer', width: 15 ,classes: 'wrap' },
			{ label: 'I/C', name: 'Newic', width: 15 ,classes: 'wrap' },
			{ label: 'DOB', name: 'DOB', width: 12 ,classes: 'wrap' ,formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'HP', name: 'telhp', width: 13 ,classes: 'wrap' , hidden:true},
			{ label: 'Sex', name: 'Sex', width: 6 ,classes: 'wrap' },
			{ label: 'Mode', name: 'pyrmode', width: 8 ,classes: 'wrap'},
			{ label: 'Seen', name: 'doctorstatus', width: 8 ,classes: 'wrap',formatter: formatterstatus_tick},
			{ label: 'idno', name: 'idno', hidden: true, key:true},
			{ label: 'dob', name: 'dob', hidden: true },
			{ label: 'RaceCode', name: 'RaceCode', hidden: true },
			{ label: 'religion', name: 'religion', hidden: true },
			{ label: 'OccupCode', name: 'OccupCode', hidden: true },
			{ label: 'Citizencode', name: 'Citizencode', hidden: true },
			{ label: 'AreaCode', name: 'AreaCode', hidden: true },
		],
		autowidth: true,
		viewrecords: true,
		width: 900,
		height: 365,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		onSelectRow:function(rowid, selected){
			populatedialysis(selrowData('#jqGrid'),urlParam.filterVal[0]);
			hide_tran_button(false);
			urlParam_trans.mrn = selrowData('#jqGrid').MRN;
			urlParam_trans.episno = selrowData('#jqGrid').Episno;
			addmore_onadd = false;
			refreshGrid("#jqGrid_trans", urlParam_trans);

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
		},
		gridComplete: function () {
			empty_dialysis();
			empty_transaction();
			$('#checkbox_completed').prop('disabled',true);
			$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			ordercompleteInit();

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

	function formatterstatus_tick(cellvalue, option, rowObject) {
		if (cellvalue == 'SEEN') {
			return '<span class="fa fa-check" ></span>';
		}else{
			return "";
		}
	}

	function ordercompleteFormatter(cellvalue, option, rowObject) {
		if (cellvalue == '1') {
			// return '<span class="fa fa-check"></span>';
			return `<input type="checkbox" class="checkbox_completed" data-rowid="`+option.rowId+`" checked onclick="return false;">`;
		}else if (cellvalue == '0') {
			return `<input type="checkbox" class="checkbox_completed" data-rowid="`+option.rowId+`" >`;
		}
	}

	function ordercompleteUNFormatter(cellvalue, option, rowObject) {
		return $(rowObject).children('input[type=checkbox]').is("[checked]");
	}

	function visiblecancel(){
		var editing = true;
		var cont = true;

		if($('td#jqGrid_trans_ilcancel').hasClass("ui-disabled")){
			editing = false;
		}

		let records = $("#jqGrid_trans").jqGrid('getGridParam', 'records');

		if(records==1 && editing ){
			cont = false;
		}else if(records==0){
			cont = false;
		}

		return cont

	}

	function ordercompleteInit(){

		$('input[type=checkbox].checkbox_completed').on('change',function(e){
			let cont = visiblecancel();

			if(cont ==  false){
				$.alert({
				    title: 'Alert',
				    content: 'Please enter charges',
				});
				$(this).prop('checked', false);
			}else{
				let self = this;
				let rowid = $(this).data('rowid');
				let rowdata = $('#jqGrid').jqGrid ('getRowData', rowid);

				$.confirm({
				    title: 'Confirm',
				    content: 'Do you want to complete all entries?',
				    buttons: {
				        Yes:{
				        	btnClass: 'btn-blue',
				        	action: function () {
					        	var param = {
									_token: $("#_token").val(),
									action: 'change_status',
									mrn: rowdata.MRN,
									episno: rowdata.Episno,
								}

								$.post( "./change_status?"+$.param(param),{}, function( data ){
									if(data.success == 'success'){
										toastr.success('Patient status completed',{timeOut: 1000})
										refreshGrid("#jqGrid", urlParam);
									}
								},'json');
					         }

				        },
				        No: {
				        	action: function () {
								$(self).prop('checked', false);
					        },
				        }
				    }

				});

			}
		});
		
	}

});
