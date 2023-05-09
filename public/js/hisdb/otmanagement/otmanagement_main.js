
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var urlParam = {
	action: 'get_table_operRecList',
	url: './otmanagement/table',
	filterVal : [moment().format("YYYY-MM-DD")]
}

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
		eventAfterRender: function(event, element, view){
			let d1 = new Date(event.start.format('YYYY-MM-DD'));
			let d2 = new Date($('#sel_date').val());
			if(d1.getTime() === d2.getTime()){
				$('#no_of_pat').text(event.title.split(" ")[0]);
			}
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
				id: 'operRecList_event',
				url: './otmanagement/table',
				type: 'GET',
				data: {
					type: 'apptbook',
					action: 'operRecList_event'
				}
			},
		]
	});
	
	var istablet = $(window).width() <= 1024;
	// istablet =  true;
	
	if(istablet){
		$('#calendar_div').hide();
		$('.if_tablet').show();
		
		$('#jqgrid_div').removeClass('eleven wide tablet eleven wide computer');
		$('#jqgrid_div').addClass('sixteen wide tablet sixteen wide computer');
		
		$('#button_calendar').calendar({
			type: 'date',
			today: true,
			onChange: function(date){
			},
			onSelect: function(date,mode){
				let new_date = date.toISOString().split('T')[0];
				
				urlParam.filterVal[0] = new_date;
				
				$('#sel_date').val(new_date);
				refreshGrid("#jqGrid", urlParam);
				$('#sel_date_span').text(new_date);
			}
		});
		
		$("#jqGrid").jqGrid({
			datatype: "local",
			colModel: [
				{ label: 'Patient Name', name: 'pat_name', width: 20, classes: 'wrap' },
				{ label: 'OT Room', name: 'ot_room', width: 20, classes: 'wrap' },
				{ label: 'Surgery Date', name: 'surgery_date', width: 13, classes: 'wrap' },
				{ label: 'Unit', name: 'op_unit', width: 20, classes: 'wrap' },
				{ label: 'Type', name: 'oper_type', width: 13, classes: 'wrap' },
				{ label: 'Status', name: 'oper_status', width: 13, classes: 'wrap' },
				{ label: 'Action', width: 15, classes: 'wrap' , formatter: actionformatter},
				{ label: 'idno', name: 'idno', hidden: true, key:true },
				{ label: 'mrn', name: 'mrn', width: 7, classes: 'wrap', formatter: padzero, unformat: unpadzero, checked: true, hidden:true },
				{ label: 'Epis. No', name: 'episno', width: 5, align: 'right', classes: 'wrap', hidden:true },
				{ label: 'Name', name: 'Name', hidden: true },
				{ label: 'MRN', name: 'MRN', hidden: true },
				{ label: 'Episno', name: 'Episno', hidden: true },
				{ label: 'Sex', name: 'Sex', hidden: true },
				{ label: 'DOB', name: 'DOB', hidden: true },
				{ label: 'RaceCode', name: 'RaceCode', hidden: true },
				{ label: 'Religion', name: 'Religion', hidden: true },
				{ label: 'OccupCode', name: 'OccupCode', hidden: true },
				{ label: 'Citizencode', name: 'Citizencode', hidden: true },
				{ label: 'AreaCode', name: 'AreaCode', hidden: true },
			],
			autowidth: true,
			viewrecords: true,
			width: 900,
			height: 365,
			rowNum: 30,
			onSelectRow:function(rowid, selected){
				$('button#timer_stop').click();
				populate_otmgmt_div(selrowData('#jqGrid'));
			},
			ondblClickRow: function (rowid, iRow, iCol, e) {
			},
			gridComplete: function () {
				$('.jqgridsegment').removeClass('loading');
				$('#no_of_pat').text($("#jqGrid").getGridParam("reccount"));
				empty_otmgmt_div();
				init_editbtn_top();
			},
		});
	}else{
		$("#jqGrid").jqGrid({
			datatype: "local",
			colModel: [
				{ label: 'Patient Name', name: 'pat_name', width: 20, classes: 'wrap' },
				{ label: 'OT Room', name: 'ot_room', width: 20, classes: 'wrap' },
				{ label: 'Surgery Date', name: 'surgery_date', width: 13, classes: 'wrap' },
				{ label: 'Unit', name: 'op_unit', width: 20, classes: 'wrap', editable:true,
							edittype:'custom',	editoptions:
						    {  custom_element:op_unitCustomEdit,
						       custom_value:galGridCustomValue 	
						    }, 
				},
				{ label: 'Type', name: 'oper_type', width: 13, classes: 'wrap', editable:true,
							edittype:"select", editoptions:{value:"MAJOR:MAJOR;MINOR:MINOR"}
				},
				{ label: 'Status', name: 'oper_status', width: 13, classes: 'wrap', editable:true,
							edittype:'select', editoptions:getoper_status()
				},
				{ label: 'Action', width: 15, classes: 'wrap', formatter: actionformatter },
				{ label: 'idno', name: 'idno', hidden: true, key:true },
				{ label: 'mrn', name: 'mrn', width: 7, classes: 'wrap', formatter: padzero, unformat: unpadzero, checked: true, hidden:true },
				{ label: 'Epis. No', name: 'episno', width: 5, align: 'right', classes: 'wrap', hidden:true },
				{ label: 'Name', name: 'Name', hidden: true },
				{ label: 'MRN', name: 'MRN', hidden: true },
				{ label: 'Episno', name: 'Episno', hidden: true },
				{ label: 'Sex', name: 'Sex', hidden: true },
				{ label: 'DOB', name: 'DOB', hidden: true },
				{ label: 'RaceCode', name: 'RaceCode', hidden: true },
				{ label: 'Religion', name: 'Religion', hidden: true },
				{ label: 'OccupCode', name: 'OccupCode', hidden: true },
				{ label: 'Citizencode', name: 'Citizencode', hidden: true },
				{ label: 'AreaCode', name: 'AreaCode', hidden: true },
			],
			autowidth: true,
			viewrecords: true,
			sortorder: "apptbook.idno",
			sortorder: "desc",
			width: 900,
			height: 365,
			rowNum: 30,
			onSelectRow:function(rowid, selected){
				$('button#timer_stop').click();
				populate_otmgmt_div(selrowData('#jqGrid'));
				$("#jqGrid").data('lastidno',rowid);
				// populate_otmgmt_div(selrowData('#jqGrid'));
			},
			ondblClickRow: function (rowid, iRow, iCol, e) {
			},
			gridComplete: function () {
				$('.jqgridsegment').removeClass('loading');
				$('#no_of_pat').text($("#jqGrid").getGridParam("reccount"));
				empty_otmgmt_div();
				init_editbtn_top();
				if(!$("button#timer_play").hasClass("disabled")){
					$("#jqGrid").setSelection($("#jqGrid").data('lastidno'));
				}
			},
		});
	}
	
	// $("#jqGrid").jqGrid('setGroupHeaders', {
	// 	useColSpanStyle: true,
	// 	groupHeaders:[
	// 		{ startColumnName: 'reff_rehab', numberOfColumns: 3, titleText: '<em>Referral</em>' },
	// 	]
	// });
	addParamField('#jqGrid',true,urlParam,['action']);
	
	//////////////////////////////////////////start grid pager//////////////////////////////////////////
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
	
	function formatterstatus_tick2(cellvalue, option, rowObject) {
		if (cellvalue != null && cellvalue.toUpperCase() == 'YES') {
			return '<span class="fa fa-check" ></span>';
		}else{
			return "";
		}
	}
	
	function UNformatterstatus_tick2(cellvalue, option, rowObject) {
		if ($(rowObject).children().length) {
			return 'YES';
		}else{
			return "NO";
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
	
	$('button#timer_play').click(function(){
		timer_start_tbl();
		$('button#timer_play').addClass('disabled');
		$('button#timer_stop').removeClass('disabled');
	});
	
	$('button#timer_stop').click(function(){
		timer_stop_tbl();
		$('button#timer_play').removeClass('disabled');
		$('button#timer_stop').addClass('disabled');
	});
	
	var fetch_tbl,fetch_evt;
	timer_start_tbl();
	timer_start_evt();
	
	function timer_start_tbl(){
		fetch_tbl = setInterval(function(){
			$('.jqgridsegment').addClass('loading');
			refreshGrid("#jqGrid", urlParam);
		}, 5000);
	}
	
	function timer_start_evt(){
		fetch_evt = setInterval(function(){
			$('#calendar').fullCalendar( 'refetchEventSources', 'operRecList_event' );
		}, 5000);
	}
	
	function timer_stop_tbl(){
	  	clearInterval(fetch_tbl);
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
									mrn: rowdata.mrn,
									episno: rowdata.episno,
								}
								
								$.post( "./ptcare_change_status?"+$.param(param),{}, function( data ){
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
	
	function checkifedited(){
		if(!$('#save_doctorNote').is(':disabled')){
			if(!$('#tab_doctornote').hasClass('in')){
				$('#toggle_doctornote').click();
			}
			// gotobtm("#tab_doctornote",'#save_doctorNote','#cancel_doctorNote');
		}else if(!$('#save_dieteticCareNotes').is(':disabled')){
			if(!$('#tab_diet').hasClass('in')){
				$('#toggle_diet').click();
			}
			// gotobtm("#tab_diet",'#save_diet','#cancel_diet');
		}else if(!$('#save_phys_ncase').is(':disabled')){
			if(!$('#tab_phys').hasClass('in')){
				$('#toggle_phys').click();
			}
			// gotobtm("#save_phys_ncase",'#save_phys_ncase','#cancel_phys_ncase');
		}else{
			return false;
		}
		return true;
	}
	
	function gotobtm(tab,save,cancel){
		SmoothScrollTo(tab, 300, function(){
			$.confirm({
				closeIcon: true,
			    title: 'Confirm',
				content: 'Do you wish to save or cancel your changes?',
				buttons: {
					Save:{
						btnClass: 'btn-green',
						action: function () {
							$(save).click();
						}
					},
					Cancel: {
						action: function () {
							$(cancel).click();
						},
					},
				}
			});
		});
	}
	
	// user_groupid();
	function user_groupid(){
		var groupid = $('#user_groupid').val().toUpperCase();
		
		$('#btn_grp_edit_doctorNote, #btn_grp_edit_phys, #btn_grp_edit_phys_ncase, #btn_grp_edit_dieteticCareNotes, #btn_grp_edit_dieteticCareNotes_fup').hide();
		switch(groupid) {
			case 'DOCTOR':
				$('#btn_grp_edit_doctorNote').show();
				break;
			case 'PHYSIOTERAPHY':
				// $('#btn_grp_edit_phys').show();
				$('#btn_grp_edit_phys_ncase').show();
				break;
			case 'REHABILITATION':
				$('#btn_grp_edit_phys_ncase').show();
				break;
			case 'DIETICIAN':
				$('#btn_grp_edit_dieteticCareNotes, #btn_grp_edit_dieteticCareNotes_fup').show();
				break;
			default:
				// code block
		}
	}
	
});
var dialog_op_unit = new ordialog(
	'op_unit', 'hisdb.discipline', "#jqGrid input[name='op_unit']", 'errorField',
	{
		colModel: [
			{	label: 'Code', name: 'code', width: 100, classes: 'pointer', canSearch: true, or_search: true },
			{	label: 'Description', name: 'description', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
		],
		urlParam: {
			filterCol : ['compcode'],
			filterVal : ['session.compcode'],
		},
		ondblClickRow: function () {
			
		}
	},
	{
		title: "Select Case",
		open: function () {
			dialog_op_unit.urlParam.filterCol = ['compcode'];
			dialog_op_unit.urlParam.filterVal = ['session.compcode'];
		},
	},'urlParam','radio','tab'
);
dialog_op_unit.makedialog(false);

function actionformatter(cellvalue, options, rowObject){
	var retbut = `<div class="mini ui icon buttons" id=editbuttop_`+rowObject.idno+`>`
	  	retbut += 	  `<button type='button' class="ui blue button editbuttop" data-idno='`+rowObject.idno+`'>`
		retbut += 	    `<i class="edit icon"></i>`
		retbut += 	  `</button>`
		retbut += 	  `<button type='button' class="ui red button" data-idno='`+rowObject.idno+`'>`
		retbut += 	    `<i class="eraser icon"></i>`
		retbut += 	  `</button></div>`;

		retbut += `<div class="mini ui icon buttons" id='waitbuttop_`+rowObject.idno+`' style='display:none'>`
	  	retbut += 	  `<button type='button' class="ui green button savebuttop" data-idno='`+rowObject.idno+`'>`
		retbut += 	    `<i class="check icon"></i>`
		retbut += 	  `</button>`
		retbut += 	  `<button type='button' class="ui yellow button cancelbuttop" data-idno='`+rowObject.idno+`'>`
		retbut += 	    `<i class="times icon"></i>`
		retbut += 	  `</button></div>`;
	return retbut;
}

function init_editbtn_top(){
	$('button.editbuttop').on('click',function(e){
		var idno = $(this).data('idno');
		$('div#editbuttop_'+idno).hide();
		$('div#waitbuttop_'+idno).show();

		$("#jqGrid").jqGrid('editRow',idno);
		dialog_op_unit.on();
	});
	$('button.savebuttop').on('click',function(e){
		var idno = $(this).data('idno');

		var obj={
			_token: $('#_token').val(),
			idno: idno,
			op_unit: $('#jqGrid input[name=op_unit]').val(),
			oper_type: $('#jqGrid select[name=oper_type]').val(),
			oper_status: $('#jqGrid select[name=oper_status]').val()
		}

		$.post( "./otmanagement/form?action=edit_header_ot",obj, function( data ){
		}).fail(function(data) {
			refreshGrid("#jqGrid", urlParam,'edit');
		}).done(function(data){
			refreshGrid("#jqGrid", urlParam,'edit');
		});

		$('div#editbuttop_'+idno).show();
		$('div#waitbuttop_'+idno).hide();
	});
	$('button.cancelbuttop').on('click',function(e){
		var idno = $(this).data('idno');
		refreshGrid("#jqGrid", urlParam,'edit');
	});
}

function op_unitCustomEdit(val,opt){
	var val = getEditVal(val);
	return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="op_unit" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
}

function galGridCustomValue (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function getoper_status(){
	var string_value = "";
	otstatus_arr.forEach(function(e,i){
		string_value+=e.desc+':'+e.desc+';';
	});
	return {value:string_value.slice(0,-1)};
	return {value:"MAJOR:MAJOR;MINOR:MINOR"};
}