$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function() {

	$('#hide_content').show();
	$.validate({
		form : '#addForm',
		modules : 'logic',
		language : {
            requiredFields: ''
        },
	});

	$('#addForm').validate({
        rules: {
            telno: {
              require_from_group: [1, ".phone-group"]
            },
            telhp: {
              require_from_group: [1, ".phone-group"]
            }
        },
    });	// patient form validation
	
	var errorField=[];
	conf = {
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	var d = new Date();
	var oper = 'add';

	var dialog_name = new ordialog(
		'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='resourcecode']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
			urlParam: {
				url : "./util/get_table_default",
				filterCol:['sp.compcode'],
				filterVal:['session.compcode'],
			},
            onSelectRow: function () {
				let data = selrowData('#' + dialog_name.gridname);

				var session_param ={
					action:"get_table_default",
					url:"./util/get_table_default",
					field:'*',
					table_name:'apptsession',
					table_id:'idno',
					filterCol:['doctorcode','status'],
					filterVal:[data['a_resourcecode'],'True']
				};

				refreshGrid("#grid_session",session_param);
            },
            ondblClickRow_off:'off',
        },{
            title: "Select Doctor",
            width: 9/10 * $(window).width(),
            open: function () {

				$("#"+dialog_name.gridname).jqGrid ('setGridHeight',100);

				$("#grid_session").jqGrid ('setGridWidth', Math.floor($("#grid_session_c")[0].offsetWidth-$("#grid_session_c")[0].offsetLeft));

                var type = $('#Class2').val();
				dialog_name.urlParam.url = "./util/get_table_default";
				dialog_name.urlParam.join_type = ['LEFT JOIN'];
				dialog_name.urlParam.join_onCol = ['a.resourcecode'];
				dialog_name.urlParam.join_onVal = ['d.doctorcode'];
				dialog_name.urlParam.join_filterCol = [['a.compcode on =']];
				dialog_name.urlParam.join_filterVal = [['d.compcode']];
				dialog_name.urlParam.fixPost='true';
				dialog_name.urlParam.filterCol = ['a.TYPE'];
				dialog_name.urlParam.filterVal = [type];
			},
			close: function () {
				
			}
        }, 'urlParam'
    );
	dialog_name.makedialog(true);

	$("#"+dialog_name.dialogname+" .panel-body").append(`
		<button type='button' id='selecting_doctor' class="btn btn-primary" style="margin-top:10px; margin-left:15px">Select This Doctor</button>
		<div id='grid_session_c' class='col-xs-12' align='center' style='padding-top:10px;'>
			<table id='grid_session' class='table table-striped'></table>
			<div id='grid_session_pager'></div>
		</div>
		`);

	function onBlur(event){
		var idtopush = $(event.currentTarget).siblings("input[type='text']").end().attr('id');
		var jqgrid = $(event.currentTarget).siblings("input[type='text']").end().attr('jqgrid');
		var optid = (event.data.data.urlParam.hasOwnProperty('optid'))? event.data.data.urlParam.optid:null;

		if(event.data.data.checkstat!='none'){
			event.data.data.check(event.data.data.errorField,idtopush,jqgrid,optid);
		}
	}

	$('#selecting_doctor').click(function(){
		$(dialog_name.textfield).off('blur',onBlur);
		$(dialog_name.textfield).val(selrowData("#"+dialog_name.gridname)[getfield(dialog_name.field)[0]]);
		$(dialog_name.textfield).parent().next().html(selrowData("#"+dialog_name.gridname)[getfield(dialog_name.field)[1]]);
		$('#transfer_doctor_from').val(selrowData("#"+dialog_name.gridname)[getfield(dialog_name.field)[0]]);

		let data = selrowData('#' + dialog_name.gridname);
		let interval = data['d_intervaltime'];
		let apptsession = $("#grid_session").jqGrid('getRowData');
		$('.fc-myCustomButton-button').show();

		// td_from.addSessionInterval(interval,apptsession);
		// td_to.addSessionInterval(interval,apptsession);
		session_field.addSessionInterval(interval,apptsession);

		var event_apptbook = {
			id: 'apptbook',
			url: "appointment/getEvent",
			type: 'GET',
			data: {
				type: 'apptbook',
				drrsc: $('#resourcecode').val()
			}
		}

		var event_appt_leave = {
			id: 'appt_leave',
			url: "appointment/getEvent",
			type: 'GET',
			data: {
				type: 'appt_leave',
				drrsc: $('#resourcecode').val()
			},
			color: $('#ALCOLOR').val(),
        	rendering: 'background'
		}

		$('#calendar').fullCalendar( 'removeEventSource', 'apptbook');
		$('#calendar').fullCalendar( 'removeEventSource', 'appt_leave');
		$('#calendar').fullCalendar( 'addEventSource', event_apptbook);
		$('#calendar').fullCalendar( 'addEventSource', event_appt_leave);

		parent_change_title('Doctor Appointment of '+data['a_description']);

		$(dialog_name.textfield).focus();
		$("#"+dialog_name.dialogname).dialog( "close" );
		$("#"+dialog_name.gridname).jqGrid("clearGridData", true);
		$(dialog_name.textfield).on('blur',{data:dialog_name,errorField:errorField},onBlur);
	});

	$("#grid_session").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Day', name: 'days', width: 80, classes: 'pointer' },
            { label: 'From Morning', name: 'timefr1', width: 100, classes: 'pointer', formatter: timeFormatter, unformat: timeUNFormatter},
            { label: 'To Morning', name: 'timeto1', width: 100, classes: 'pointer', formatter: timeFormatter, unformat: timeUNFormatter },
            { label: 'From Evening', name: 'timefr2', width: 100, classes: 'pointer', formatter: timeFormatter, unformat: timeUNFormatter },
            { label: 'To Evening', name: 'timeto2', width: 100, classes: 'pointer', formatter: timeFormatter, unformat: timeUNFormatter },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
		pager: "#grid_session_pager",
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
	});

	var dialog_case = new ordialog(
		'case', 'casetype', "#dialogForm input[name='case']", errorField,
		{
			colModel: [
				{ label: 'Case Code', name: 'case_code', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			],
			urlParam: {
				url : "./util/get_table_default",
				filterCol:['grpcasetype','compcode'],
				filterVal:['REGISTER','session.compcode'],
			},
		},
		{
			title: "Select Case",
            width: 10/10 * $(window).width(),
			open: function () {
				dialog_case.urlParam.filterCol = ['grpcasetype','compcode'];
				dialog_case.urlParam.filterVal = ['REGISTER','session.compcode'];
			},
		}, 'urlParam'
	);
	dialog_case.makedialog(true);

	var dialog_mrn = new ordialog(
		'mrn', 'pat_mast', "#dialogForm input[name='mrn']", errorField,
		{
			colModel: [
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', formatter: padzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				{	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
				{	label: 'Newic', name: 'Newic', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam: {
				url : "./util/get_table_default",
				filterCol:['grpcasetype','compcode'],
				filterVal:['REGISTER','session.compcode'],
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#addForm input[name='patname']").val(data['Name']);
				$("#addForm input[name='icnum']").val(data['Newic']);
				$("#addForm input[name='telh']").val(data['telh']);
				$("#addForm input[name='telhp']").val(data['telhp']);
				$(dialog_mrn.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Case",
			open: function () {
				dialog_mrn.urlParam.filterCol = ['compcode'];
				dialog_mrn.urlParam.filterVal = ['session.compcode'];
			},
		}, 'urlParam'
	);
	dialog_mrn.makedialog(false);

	if ( !$("#patname").is('[readonly]') ) {
		dialog_mrn.on();
	}

	var dialog_doctor = new ordialog(
		'dialog_doctor', ['apptresrc AS a', 'doctor AS d'], "input[name='transfer_doctor']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
			urlParam: {
				url : "./util/get_table_default",
				filterCol:['grpcasetype','compcode'],
				filterVal:['REGISTER','session.compcode'],
			},
            ondblClickRow: function () {
				let data = selrowData('#' + dialog_name.gridname);

            	var session_param ={
					action:"get_value_default",
					url:$('#util_tab').val(),
					field:'*',
					table_name:'apptsession',
					table_id:'idno',
					filterCol:['doctorcode','status'],
					filterVal:[data['a_resourcecode'],'True']
				};
			}
        },{
            title: "Select Doctor",
            open: function () {
                var type = $('#Class2').val();
				dialog_doctor.urlParam.join_type = ['LEFT JOIN'];
				dialog_doctor.urlParam.join_onCol = ['a.resourcecode'];
				dialog_doctor.urlParam.join_onVal = ['d.doctorcode'];
				dialog_doctor.urlParam.join_filterCol = [['a.compcode on =']];
				dialog_doctor.urlParam.join_filterVal = [['d.compcode']];
				dialog_doctor.urlParam.fixPost='true';
				dialog_doctor.urlParam.filterCol = ['a.TYPE'];
				dialog_doctor.urlParam.filterVal = [type];
			}
        }, 'urlParam'
    );
	dialog_doctor.makedialog(true);


	$("#dialogForm").dialog({
		autoOpen: false,
		width: 10 / 10 * $(window).width(),
		modal: true,
		open: function(event,ui){
			session_field.clear().ready().set();
			$("#addForm input[name='icnum']").prop('readonly',true);
		},
		close: function( event, ui ){
			emptyFormdata(errorField,'#addForm');
			$('#delete_but').hide();
		}		
	});

	var session_field = new session_field();

	$("#start_time_dialog").dialog({
    	autoOpen : false, 
    	modal : true,
		width: 10/10 * $(window).width(),
		open: function(){
			$("#grid_start_time").jqGrid ('setGridWidth', Math.floor($("#grid_start_time_c")[0].offsetWidth-$("#grid_start_time_c")[0].offsetLeft));
		},
		close:function(){
		}
    });

	$("#start_time ~ a").click(function(){
		$("#start_time_dialog").dialog("open");
	});

	$("#grid_start_time").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'timehidden', name: 'timehidden', width: 80, hidden: true },
            { label: 'Pick time', name: 'time', width: 80, classes: 'pointer' },
            { label: 'Patient Name', name: 'pat_name', width: 200, classes: 'pointer' },
            { label: 'Remarks', name: 'remarks', width: 200, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:400,owNum:30,
		// pager: "#grid_start_time_pager",
		onSelectRow:function(){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let time = selrowData("#grid_start_time").timehidden;
			$('#start_time').val(time);
			$('#end_time').val(moment($('#apptdatefr_day').val()+" "+time).add(session_field.interval, 'minutes').format("HH:mm:SS"));
			$("#start_time_dialog").dialog('close');
		},
	});

	function session_field(){
		this.apptsession;
		this.interval;
		this.date_fr;
		this.day_fr;
		this.fr_obj;
		this.fr_start;
		this.events=[];

		this.addSessionInterval = function(interval,apptsession){
			this.apptsession = apptsession;

			let temp_bussHour=[];
			this.apptsession.forEach(function( obj ) {
				var temp_obj = {dow:[],start:obj.timefr1,end:obj.timeto2};
				switch(obj.days) {
					case "SUNDAY": temp_obj.dow.push(0); break;
					case "MONDAY":  temp_obj.dow.push(1); break;
					case "TUESDAY": temp_obj.dow.push(2); break;
					case "WEDNESDAY": temp_obj.dow.push(3); break;
					case "THURSDAY": temp_obj.dow.push(4); break;
					case "FRIDAY": temp_obj.dow.push(5); break;
					case "SATURDAY": temp_obj.dow.push(6); break;
				}

				temp_bussHour.push(temp_obj);
			});

			$('#calendar').fullCalendar('option', {
			   	businessHours: temp_bussHour
			});

			this.interval = interval;
			return this;
		}

		this.clear = function(){
			$("#grid_start_time").jqGrid("clearGridData", true);
			return this;
		}

		this.ready = function(){
			this.events =  $('#calendar').fullCalendar('clientEvents');

			this.date_fr = $('#apptdatefr_day').val();
			this.day_fr = moment(this.date_fr).format('dddd').toUpperCase();
			let day_fr = this.day_fr;
			this.fr_obj = this.apptsession.filter(function( obj ) {
				return obj.days == day_fr;
			});
			this.fr_start = moment(this.date_fr+" "+this.fr_obj[0].timefr1);

			return this;
		}

		this.set = function(){
			var fr_start = this.fr_start, date_fr = this.date_fr, fr_obj = this.fr_obj, interval= this.interval, events = this.events;

			var rowid = 0;
			while(!fr_start.isSameOrAfter(date_fr+" "+fr_obj[0].timeto1)){
    			let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {timehidden:time_use,time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					if(elem.start.isSame(fr_start)){
						if(objuse.pat_name!=''){
							rowid = rowid+1;
    						$("#grid_start_time").jqGrid('addRowData', rowid,{
    							timehidden:time_use,time:time_use+' <span class="label label-danger">Overlap</span>',remarks:elem.remarks,pat_name:elem.pat_name
    						});
						}else{
							objuse.pat_name=elem.pat_name;
	    					objuse.remarks=elem.remarks;
						}
					}
    			});

				rowid = rowid+1;
    			$("#grid_start_time").jqGrid('addRowData', rowid,objuse);
    			fr_start.add(interval, 'minutes');
        	}

        	fr_start = moment(date_fr+" "+fr_obj[0].timefr2);

        	while(!fr_start.isSameOrAfter(moment(date_fr+" "+fr_obj[0].timeto2).add(interval, 'minutes'))){
        		let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {timehidden:time_use,time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					if(elem.start.isSame(fr_start)){
						if(objuse.pat_name!=''){
							rowid = rowid+1;
    						$("#grid_start_time").jqGrid('addRowData', rowid,{
    							timehidden:time_use,time:time_use+' <span class="label label-danger">Overlap</span>',remarks:elem.remarks,pat_name:elem.pat_name
    						});
						}else{
							objuse.pat_name=elem.pat_name;
	    					objuse.remarks=elem.remarks;
						}
					}
    			});

				rowid = rowid+1;
    			$("#grid_start_time").jqGrid('addRowData', rowid,objuse);
    			fr_start = fr_start.add(interval, 'minutes');
        	}
		}
	}

	$("#apptdatefr_day,#apptdateto_day").change(function(){
		session_field.clear().ready().set();
	});



	var event_apptbook = {
		id: 'apptbook',
		url: "appointment/getEvent",
		type: 'GET',
		data: {
			type: 'apptbook',
			drrsc: $("input[name='resourcecode']").val()
		}
	}

	var event_appt_leave = {
		id: 'appt_leave',
		url: "appointment/getEvent",
		type: 'GET',
		data: {
			type: 'appt_leave',
			drrsc: $("input[name='resourcecode']").val()
		},
		color: $('#ALCOLOR').val(),
    	rendering: 'background'
	}

	$('#calendar').fullCalendar( 'removeEventSource', 'apptbook');
	$('#calendar').fullCalendar( 'removeEventSource', 'appt_leave');
	$('#calendar').fullCalendar( 'addEventSource', event_apptbook);
	$('#calendar').fullCalendar( 'addEventSource', event_appt_leave);

	$('#calendar').fullCalendar({
		aspectRatio:  2.5,
		header: {
			left: 'prev,next today myCustomButton',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listYear'
		},
		customButtons: {
	        myCustomButton: {
	            text: 'Make Appointment',
	            click: function() {
	            	oper='add';

					var temp = $('#resourcecode').val();
					var start = $(".fc-myCustomButton-button").data("start");

					$('#dialogForm #doctor').val(temp);
					$('#apptdatefr_day').val(moment(start).format('YYYY-MM-DD'));
					
					$("#dialogForm").dialog("open");
            	}
        	}
    	},
		defaultDate: d,
		navLinks: false,
  		viewRender: function(view, element) {
  			let start = view.start;
  			if(view.name == 'agendaDay'){
  				$(".fc-myCustomButton-button").data( "start", start );
  				var events = $('#calendar').fullCalendar('clientEvents');
				$(".fc-myCustomButton-button").show();
				events.forEach(function(elem,id){
					if(elem.allDay){
						let elem_end = (elem.end==null)?elem.start:elem.end;
						if(start.isBetween(elem.start,elem_end, null, '[)')){
							$(".fc-myCustomButton-button").hide();
						}
					}
				});
				if(!start.isSameOrAfter(moment().subtract(1, 'days'))){
					$(".fc-myCustomButton-button").hide();
				}
  			}
  		},
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		selectable: true,
		selectHelper: true,
		timezone: 'local',
		select: function(start, end, jsEvent, view, resource) {
			$('#calendar').fullCalendar( 'gotoDate', start )
			$(".fc-myCustomButton-button").data( "start", start );
			var events = $('#calendar').fullCalendar( 'clientEvents');
			$(".fc-myCustomButton-button").show();
			events.forEach(function(elem,id){
				if(elem.allDay){
					let elem_end = (elem.end==null)?elem.start:elem.end;
					if(start.isBetween(elem.start,elem_end, null, '[)')){
						$(".fc-myCustomButton-button").hide();
					}
				}
			});
			if(!start.isSameOrAfter(moment().subtract(1, 'days'))){
				$(".fc-myCustomButton-button").hide();
			}

		},
		eventRender: function(event, element) {
			if(event.source.id == "apptbook"){
				element.attr('data-toggle','tooltip')
				element.attr('data-placement','bottom')
				let mrn_ = (event.mrn == null)?'00000':pad('0000000',event.mrn,true);
				let remarks_ = (event.remarks == null)?' ':event.remarks;
				let mytitle = mrn_+' - '
								+event.pat_name+' - '
								+event.case_desc+' - '
								+remarks_;
				element.attr('title',mytitle)
				element.tooltip();

				element.bind('dblclick', function() {
					oper = 'edit';
					$('#doctor').val(event.loccode);
					$('#mrn').val(event.mrn);
					$('#icnum').val(event.icnum);
					$('#patname').val(event.pat_name);
					$('#apptdatefr_day').val(event.start.format('YYYY-MM-DD'));
					$('#start_time').val(event.start.format('HH:mm:ss'));
					$('#end_time').val(event.end.format('HH:mm:ss'));
					$('#telno').val(event.telno);
					$('#telhp').val(event.telhp);
					$('#case').val(event.case_code);
					$('#remarks').val(event.remarks);
					$('#status').val(event.apptstatus);
					$('#idno').val(event.idno);
					$('#lastuser').val(event.lastuser);
					$('#lastupdate').val(event.lastupdate);

					$('#delete_but').show();

					$("#dialogForm").dialog('open');
				});

				element.on('click', function() {
					$('td.fc-event-container a').removeClass('selected');
					$(this).addClass('selected');

					if(event.mrn == null){
						$('#biodata_but_apptrsc').data('oper','add');
					}else{
						$('#biodata_but_apptrsc').data('oper','edit');
					}

					// $('#biodata_but_apptrsc').data('bio_from_calander',event);
					// $('#episode_but_apptrsc').data('bio_from_calander',event);

				});
			}
			if(event.source.rendering == 'background'){
				element.append(event.title);
			}
		},
		eventAfterAllRender: function(view){
			var events = $('#calendar').fullCalendar( 'clientEvents');
			var date_array = [];
			var date_obj = [];
			events.forEach(function(e,i){
				let got = date_array.indexOf(e.start.date());
				if(got == -1){
					date_array.push(e.start.date());
					date_obj.push({
						format_date:e.start.format("YYYY-MM-DD"),
						date:e.start.date(),
						count:1
					});
				}else{
					date_obj[got].count = date_obj[got].count + 1;
				}
			});

			$("table tr td.fc-day-top span.ui.mini.teal.ribbon.label").remove();

			date_obj.forEach(function(e,i){
				$("table tr td.fc-day-top[data-date='"+e.format_date+"']").append("<span class='ui mini teal ribbon label'>"+e.count+" patients </span>");
			});
		},
		timeFormat: 'h(:mm)a',
		eventDrop: function(event, delta, revertFunc) {
			var param={
				'event_drop':'event_drop',
				'idno':event.idno,
				'start':event.start.format("YYYY-MM-DD HH:mm:SS"),
				'end':event.start.clone().add(session_field.interval, 'minutes').format("YYYY-MM-DD HH:mm:SS"),
				'_token': $('#csrf_token').val()
			};

			$.post("appointment/editEvent",param, function (data) {

			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				
			});
		},
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
			var param={
				'event_drop':'event_drop',
				'idno':event.idno,
				'start':event.start.format("YYYY-MM-DD HH:mm:SS"),
				'end':event.start.clone().add(session_field.interval, 'minutes').format("YYYY-MM-DD HH:mm:SS"),
				'_token': $('#csrf_token').val()
			};

			$.post("appointment/editEvent",param, function (data) {

			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				
			});
		},
		displayEventTime:true,
		selectConstraint : "businessHours",
		eventSources: [
			{	
				id:'apptbook'
			},
			{	
				id:'appt_ph',
				url:'appointment/getEvent',
				type:'GET',
				data:{
					type:'appt_ph'
				}, 
            	textColor: 'black',
            	rendering: 'background'
			},
			{	
				id:'appt_leave'
			}
	    ]
	});
	$('.fc-myCustomButton-button').hide();


	var oper = 'add';
	$('#submit').click(function(){
		var url = (oper == 'add')?"appointment/addEvent":"appointment/editEvent";

		if( $('#addForm').isValid({requiredFields: ''}, conf, true) ) {
			$.post(url, $("#addForm").serialize(), function (data) {
			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				$("#dialogForm").dialog('close');
				$('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
			});
		}
	});

	$('#delete_but').click(function(){
		$.post("appointment/delEvent", $("#addForm").serialize(), function (data) {
		}).fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
			$("#dialogForm").dialog('close');
			$('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
		});
	});


});