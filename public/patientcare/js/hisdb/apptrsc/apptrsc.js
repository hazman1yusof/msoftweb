$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
	/////////////////////////validation//////////////////////////
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

	var Class2 = $('#Class2').val();

	if(Class2 == 'DOC'){
		$('#Scol').val('Doctor')
	}else{
		$('#Scol').val('Resource')
	}

	$("td.fc-event-container a").click(function(){
		console.log($(this));
	});

	var dialog_name = new ordialog(
		'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='resourcecode']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
            urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A'],
				join_type : ['LEFT JOIN'],
				join_onCol : ['a.resourcecode'],
				join_onVal : ['d.doctorcode'],
				join_filterCol : [['a.compcode on =']],
				join_filterVal : [['d.compcode']],
				fixPost:'true',
				filterCol : ['a.TYPE'],
				filterVal : [$('#Class2').val()],

			},
            onSelectRow: function () {
				let data = selrowData('#' + dialog_name.gridname);

				var session_param ={
					action:"get_table_default",
					url:'util/get_table_default',
					field:'*',
					table_name:'hisdb.apptsession',
					table_id:'idno',
					filterCol:['doctorcode','status'],
					filterVal:[data['a_resourcecode'],'True']
				};

				refreshGrid("#grid_session",session_param);
            },
            ondblClickRow_off:'off',
        },{
            title: "Select Doctor",
            width: 10/10 * $(window).width(),
            open: function () {

				$("#"+dialog_name.gridname).jqGrid ('setGridHeight',100);

				$("#grid_session").jqGrid ('setGridWidth', Math.floor($("#grid_session_c")[0].offsetWidth-$("#grid_session_c")[0].offsetLeft));

                var type = $('#Class2').val();
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

		td_from.addSessionInterval(interval,apptsession);
		td_to.addSessionInterval(interval,apptsession);
		session_field.addSessionInterval(interval,apptsession);

		var event_apptbook = {
			id: 'apptbook',
			url: "apptrsc/getEvent",
			type: 'GET',
			data: {
				type: 'apptbook',
				drrsc: $('#resourcecode').val()
			}
		}

		var event_appt_leave = {
			id: 'appt_leave',
			url: "apptrsc/getEvent",
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
		'case', 'hisdb.casetype', "#dialogForm input[name='case']", errorField,
		{
			colModel: [
				{ label: 'Case Code', name: 'case_code', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol : ['grpcasetype','compcode'],
				filterVal : ['REGISTER','session.compcode'],

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
		'mrn', 'hisdb.pat_mast', "#dialogForm input[name='mrn']", errorField,
		{
			colModel: [
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', formatter: padzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{	label: 'telhp', name: 'telhp', width: 200, classes: 'pointer',hidden:true},
				{	label: 'telh', name: 'telh', width: 200, classes: 'pointer',hidden:true},
				{	label: 'Newic', name: 'Newic', width: 200, classes: 'pointer',hidden:true},
			],
			urlParam: {
				filterCol : ['compcode'],
				filterVal : ['session.compcode'],

			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#addForm input[name='patname']").val(data['Name']);
				$("#addForm input[name='icnum']").val(data['Newic']);
				$("#addForm input[name='telh']").val(data['telh'].trim());
				$("#addForm input[name='telhp']").val(data['telhp'].trim());
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

	$("#addForm input[name='patname']").blur(function(){
		if($("#addForm input[name='mrn']").val() == ''){
			$("#addForm input[name='icnum']").prop('readonly',false);
		}
	});

	var dialog_doctor = new ordialog(
		'dialog_doctor', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='transfer_doctor']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
			urlParam: {
				join_type : ['LEFT JOIN'],
				join_onCol : ['a.resourcecode'],
				join_onVal : ['d.doctorcode'],
				join_filterCol : [['a.compcode on =']],
				join_filterVal : [['d.compcode']],
				fixPost:'true',
				filterCol : ['a.TYPE'],
				filterVal : [$('#Class2').val()]

			},
            ondblClickRow: function () {
				let data = selrowData('#' + dialog_name.gridname);

            	var session_param ={
					action:"get_value_default",
					url:'util/get_table_default',
					field:'*',
					table_name:'hisdb.apptsession',
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
			set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
			session_field.clear().ready().set();

			$("#addForm input[name='icnum']").prop('readonly',true);
		},
		close: function( event, ui ){
			emptyFormdata(errorField,'#addForm');
			$('#delete_but,#new_episode').hide();
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
	
	$('#calendar').fullCalendar({
		aspectRatio:  1.5,
		header: {
			left: 'prev,next today myCustomButton',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
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
					lastelement = element;
					oper = 'edit';
					$('#doctor').val(event.loccode);
					$('#mrn').val(event.mrn);
					$('#icnum').val(event.icnum);
					$('#patname').val(event.pat_name);
					$('#apptdatefr_day').val(event.start.format('YYYY-MM-DD'));
					$('#start_time').val(event.start.format('HH:mm:ss'));
					$('#end_time').val(event.end.format('HH:mm:ss'));
					$('#telh').val(event.telno);
					$('#telhp').val(event.telhp);
					$('#case').val(event.case_code);
					$('#remarks').val(event.remarks);
					$('#status').val(event.apptstatus);
					$('#idno').val(event.idno);
					$('#lastuser').val(event.lastuser);
					$('#lastupdate').val(event.lastupdate);

					$('#delete_but,#new_episode').show();

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

			$.post("apptrsc/editEvent",param, function (data) {

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

			$.post("apptrsc/editEvent",param, function (data) {

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
				url:'apptrsc/getEvent',
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
		var url = (oper == 'add')?"apptrsc/addEvent":"apptrsc/editEvent";

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
		$.post("apptrsc/delEvent", $("#addForm").serialize(), function (data) {
		}).fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
			$("#dialogForm").dialog('close');
			$('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
		});
	});

	$('#transfer_doctor_but').click(function(){
		$("#transfer_doctor_div").show();
		if($("input[name='resourcecode']").val()!=''){
			$("#transfer_date").dialog("open");
		}
	});
	
	$('#transfer_date_but').click(function(){
		$("#transfer_doctor_div").hide();
		if($("input[name='resourcecode']").val()!=''){
			$("#transfer_date").dialog("open");
		}
	});

	$("#grid_transfer_date_from").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'unique', name: 'unique', width: 80, hidden: true },
            { label: 'Rowid', name: 'rowid', width: 80, hidden: true },
            { label: 'idno', name: 'idno', width: 80, hidden: true },
            { label: 'start', name: 'start', width: 80, hidden: true },
            { label: 'new_start', name: 'new_start', width: 80, hidden: true },
            { label: 'new_end', name: 'new_start', width: 80, hidden: true },
            { label: 'time_hidden', name: 'time_hidden', width: 80, hidden: true },
            { label: 'Pick time', name: 'time', width: 80, classes: 'pointer' },
            { label: 'Patient Name', name: 'pat_name', width: 200, classes: 'pointer' },
            { label: 'Remarks', name: 'remarks', width: 200, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:50,
		sortname: 'time',
		sortorder: 'asc',
		pager: "#grid_transfer_date_from_pager",
		onSelectRow:function(){
			$("#td_but_down").data('addedEvent',selrowData("#grid_transfer_date_from"));
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
	});

	$("#grid_transfer_date_to").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'unique', name: 'unique', width: 80, hidden: true },
            { label: 'Rowid', name: 'rowid', width: 80, hidden: true },
            { label: 'idno', name: 'idno', width: 80, hidden: true },
            { label: 'start', name: 'start', width: 80, hidden: true },
            { label: 'new_start', name: 'new_start', width: 80, hidden: true },
            { label: 'new_end', name: 'new_end', width: 80, hidden: true },
            { label: 'time_hidden', name: 'time_hidden', width: 80, hidden: true },
            { label: 'Pick time', name: 'time', width: 80, classes: 'pointer' },
            { label: 'Patient Name', name: 'pat_name', width: 200, classes: 'pointer' },
            { label: 'Remarks', name: 'remarks', width: 200, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:50,
		sortname: 'time',
		sortorder: 'asc',
		pager: "#grid_transfer_date_to_pager",
		onSelectRow:function(){
			$("#td_but_up").data('addedEvent',selrowData("#grid_transfer_date_to"));
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
	});

	$("#transfer_date").dialog({
		autoOpen: false,
		width: 10 / 10 * $(window).width(),
		modal: true,
		open: function(event,ui){
			$("#grid_transfer_date_from").jqGrid ('setGridWidth', Math.floor($("#grid_transfer_date_from_c")[0].offsetWidth-$("#grid_transfer_date_from_c")[0].offsetLeft));

			$("#grid_transfer_date_to").jqGrid ('setGridWidth', Math.floor($("#grid_transfer_date_to_c")[0].offsetWidth-$("#grid_transfer_date_to_c")[0].offsetLeft));
		},
		close: function( event, ui ){
			$('#td_date_from').val('');
			$('#td_date_to').val('');
			td_from.clear();
			td_to.clear();
		}		
	});

	$("#td_date_from").datepicker({
		dateFormat: 'dd-mm-yy',
        beforeShowDay: function(date){
        	return [td_from.hadDay.includes(date.getDay())];
        },
        onSelect: function(date){
        	let sel_date = moment($('#td_date_from').val(),'DD-MM-YYYY').format("YYYY-MM-DD");

			let param = {
				type: 'apptbook',
				start: sel_date+' 00:00:00',
				end: sel_date+' 23:59:59',
				drrsc: $('#resourcecode').val()
			}

			$.get( "apptrsc/getEvent"+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if(!$.isEmptyObject(data)){
					td_from.clear().init(sel_date,data).set();
				}else{
					td_from.clear().init(sel_date).set();
				}
			});
        }
	});

	$("#td_date_to").datepicker({
		dateFormat: 'dd-mm-yy',
		beforeShowDay: function(date){
        	return [td_from.hadDay.includes(date.getDay())];
        },
        onSelect: function(date){
        	let sel_date = moment($('#td_date_to').val(),'DD-MM-YYYY').format("YYYY-MM-DD");

			let param = {
				type: 'apptbook',
				start: sel_date+' 00:00:00',
				end: sel_date+' 23:59:59',
				drrsc: $('#resourcecode').val()
			}

			$.get( "apptrsc/getEvent"+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if(!$.isEmptyObject(data)){
					td_to.clear().init(sel_date,data).set();
				}else{
					td_to.clear().init(sel_date).set();
				}
			});
        }
	});

	$("#td_but_down").click(function(){
		if(!$("#grid_transfer_date_from").jqGrid('getGridParam', 'selrow')){
			alert('Please select row');
		}else{
			if($('#td_date_to').val() != '' && $('#td_date_from').val() != ''){
				td_to.add_addedEvent($(this).data('addedEvent'));
				td_from.sub_addedEvent($(this).data('addedEvent'));
			}else{
				alert('please select date first');
			}
		}
	});

	$("#td_but_up").click(function(){
		if(!$("#grid_transfer_date_to").jqGrid('getGridParam', 'selrow')){
			alert('Please select row');
		}else{
			if($('#td_date_to').val() != '' && $('#td_date_from').val() != ''){
				td_from.add_addedEvent($(this).data('addedEvent'));
				td_to.sub_addedEvent($(this).data('addedEvent'));
			}else{
				alert('please select date first');
			}
		}
	});

	$('#td_save').click(function(){

		var arraytd = $("#grid_transfer_date_to").jqGrid ('getGridParam').data.filter(function( obj ) {
			return obj.new_start != "";
		});

		var obj = {
			"_token": $('#csrf_token').val(),
			"arraytd":arraytd
		}

		$.post("apptrsc/editEvent?type=transfer", obj, function (data) {
		}).fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
			$("#transfer_date").dialog('close');
			$('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
		});

	});

	var td_from = new td_grid("#grid_transfer_date_from");
	var td_to = new td_grid("#grid_transfer_date_to");

	function td_grid(grid){ // ni semua untuk transfer doctor dgn date
		this.apptsession,this.interval,this.date_fr,this.day_fr,this.fr_obj,this.fr_start,this.events,this.hadDay,this.grid=grid,this.addedEvents=[];

		this.addSessionInterval = function(interval,apptsession){
			this.apptsession = apptsession;
			this.interval = interval;

			var hadDay = [];
			this.apptsession.forEach(function( obj ) {
				switch(obj.days) {
					case "SUNDAY": hadDay.push(0); break;
					case "MONDAY":  hadDay.push(1); break;
					case "TUESDAY": hadDay.push(2); break;
					case "WEDNESDAY": hadDay.push(3); break;
					case "THURSDAY": hadDay.push(4); break;
					case "FRIDAY": hadDay.push(5); break;
					case "SATURDAY": hadDay.push(6); break;
				}
			});
			this.hadDay = hadDay;

			return this;
		}

		//selalunya apa yang berlaku dkt bawah
		this.add_addedEvent = function(events){

			if(!($(this.grid).jqGrid('getDataIDs').includes(events.unique)) && events.pat_name != ''){

				var rowtodel = $(this.grid).jqGrid('getRowData').find(function(obj){
					return (events.time_hidden == obj.time_hidden && obj.pat_name == "");
				});

				var seldate = moment($('#td_date_to').val()+" "+events.time_hidden,'DD-MM-YYYY HH:mm:SS');
				let new_start = seldate.format('YYYY-MM-DD HH:mm:SS');

				seldate.add(this.interval, 'minutes');
				let new_end = seldate.format('YYYY-MM-DD HH:mm:SS');

				events.new_start = new_start;
				events.new_end = new_end;

				if(rowtodel != undefined)$(this.grid).jqGrid('delRowData', rowtodel.unique);
				$(this.grid).jqGrid('addRowData', events.unique, events);
				$(this.grid).jqGrid('setGridParam',{sortname:'time'}).trigger('reloadGrid');
			}

		}

		//selalunyer apa yg berlaku dkt atas
		this.sub_addedEvent = function(events){

			if(($(this.grid).jqGrid('getDataIDs').includes(events.unique)) && events.pat_name != ''){

				var rowtoadd = {idno:'none',unique:events.unique+' -sub',rowid:events.rowid,new_start:'',start:events.start,time:events.time,time_hidden:events.time_hidden,pat_name:'',remarks:''};

				$(this.grid).jqGrid('delRowData', events.unique);
				if(events.time.indexOf("span") == -1){
					$(this.grid).jqGrid('addRowData', rowtoadd.unique, rowtoadd);
				}
				$(this.grid).jqGrid('setGridParam',{sortname:'time'}).trigger('reloadGrid');
			}

		}

		this.clear = function(){
			$(this.grid).jqGrid("clearGridData", true);
			return this;
		}

		this.init = function(date_fr,events = []){
			this.events = events;

			this.date_fr = date_fr;
			this.day_fr = moment(this.date_fr).format('dddd').toUpperCase();
			let day_fr = this.day_fr;

			this.fr_obj = this.apptsession.filter(function( obj ) {
				return obj.days == day_fr;
			});

			this.fr_start = moment(this.date_fr+" "+this.fr_obj[0].timefr1);

			return this;
		}

		this.set = function(){
			var fr_start = this.fr_start, date_fr = this.date_fr, fr_obj = this.fr_obj, interval= this.interval, events = this.events, grid = this.grid, rowid = 0;

			while(!fr_start.isSameOrAfter(date_fr+" "+fr_obj[0].timeto1)){
    			let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {idno:'none',start:fr_start.format("YYYY-MM-DD HH:mm:SS"),new_start:'',new_end:'',time_hidden:time_use,time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
    				if(fr_start.isSame(elem.start)){
						if(objuse.pat_name!=''){
    						$(grid).jqGrid('addRowData',rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"),{unique:rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"),rowid:rowid,start:fr_start.format("YYYY-MM-DD HH:mm:SS"),new_start:'',new_end:'',time_hidden:time_use,time:time_use+' <span class="label label-danger">Overlap</span>',idno:elem.idno,remarks:elem.remarks,pat_name:elem.pat_name
    						});
    						rowid++;
						}else{
	    					objuse.idno=elem.idno;
							objuse.pat_name=elem.pat_name;
	    					objuse.remarks=elem.remarks;
						}
					}
    			});

    			objuse.unique = rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS");
    			objuse.rowid = rowid;
    			$(grid).jqGrid('addRowData', rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"), objuse);
    			fr_start.add(interval, 'minutes');
    			rowid++;
        	}

        	fr_start = moment(date_fr+" "+fr_obj[0].timefr2);

        	while(!fr_start.isSameOrAfter(moment(date_fr+" "+fr_obj[0].timeto2).add(interval, 'minutes'))){
        		let time_use = fr_start.format("HH:mm:SS");
    			let objuse = {unique:rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"),idno:'none',rowid:rowid,start:fr_start.format("YYYY-MM-DD HH:mm:SS"),new_start:'',new_end:'',time_hidden:time_use,time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
    				if(fr_start.isSame(elem.start)){
						if(objuse.pat_name!=''){
    						$(grid).jqGrid('addRowData',rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"),{unique:rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"),rowid:rowid,start:fr_start.format("YYYY-MM-DD HH:mm:SS"),new_start:'',new_end:'',time_hidden:time_use,time:time_use+' <span class="label label-danger">Overlap</span>',idno:elem.idno,remarks:elem.remarks,pat_name:elem.pat_name
    						});
    						rowid++;
						}else{
							objuse.unique=rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS");
							objuse.rowid=rowid;
	    					objuse.idno=elem.idno;
							objuse.pat_name=elem.pat_name;
	    					objuse.remarks=elem.remarks;
						}
					}
    			});

    			$(grid).jqGrid('addRowData', rowid+'-'+fr_start.format("YYYY-MM-DD HH:mm:SS"), objuse);
    			fr_start = fr_start.add(interval, 'minutes');
    			rowid++;
        	}
		}
	}
	//////////////// start pasal biodata//////////////////////////
	// $('#btn_register_patient').off('click',default_click_register);
	// $('#btn_reg_proceed').off('click',default_click_proceed);

	$("#biodata_but_apptrsc").click(function(){

		// var data = $(this).data('bio_from_calander');
		var data = $(this).data('mrn_from_calander');

		if(data==undefined){
			alert('no patient biodata selected');
		}else{

			var oper = $(this).data('oper');
			populatecombo1();
	        $('#mdl_patient_info').modal({backdrop: "static"});
	        $("#btn_register_patient").data("oper",oper);

			if(oper == 'add'){
		        var first_visit_val =moment(new Date()).format('DD/MM/YYYY');
		        $('#first_visit_date').val(first_visit_val);
		        var last_visit_val =moment(new Date()).format('DD/MM/YYYY');
		        $('#last_visit_date').val(last_visit_val);
		        $('#txt_pat_episno').val('1');
		        $('#txt_pat_name').val(data.pat_name);
				$('#txt_pat_telh').val(data.telno);
				$('#txt_pat_telhp').val(data.telhp);
			}else{
				populate_data_from_mrn(data,"#frm_patient_info");
			}

		}

	});

	$('#btn_register_patient').on('click',function(){
		var data = $("#biodata_but_apptrsc").data('bio_from_calander');
        var apptbook_idno = data.idno;

        if($('#frm_patient_info').valid()){
            if($(this).data('oper') == 'add'){
                check_existing_patient(save_patient_apptrsc,{
                	"action":"apptrsc",
                	"param":['add',null,null,apptbook_idno]
                });
            }else{
	            let mrn =  $('#txt_pat_mrn').val();
	            let idno =  $('#txt_pat_idno').val();
                save_patient_apptrsc('edit',idno,mrn,apptbook_idno);
            }
        }
    });

    $('#btn_reg_proceed').on('click',function(){
		var data = $("#biodata_but_apptrsc").data('bio_from_calander');
        var apptbook_idno = data.idno;
        var checkedbox = $("#tbl_existing_record input[type='checkbox']:checked");

        if(checkedbox.closest("td").next().length>0){
            let mrn = checkedbox.data("mrn");
            let idno = checkedbox.data("idno");
            save_patient_apptrsc('edit',idno,mrn,apptbook_idno);
        }else{
            save_patient_apptrsc('add',null,null,apptbook_idno);
        }
    });

 	function save_patient_apptrsc(oper,idno,mrn="nothing",apptbook_idno){
 		var saveParam={
            action:'save_patient',
            field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','OccupCode','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno','first_visit_date','last_visit_date'],
            oper:oper,
            table_name:'hisdb.pat_mast',
            table_id:'idno',
            sysparam:null
        },_token = $('#csrf_token').val();

        if(oper=='add'){
            saveParam.sysparam = {source:'HIS',trantype:'MRN',useOn:'MRN'};
            var postobj = {_token:_token,apptbook_idno:apptbook_idno};
        }else if(oper == 'edit'){
            var postobj = {_token:_token,idno:idno,apptbook_idno:apptbook_idno,MRN:mrn};
        }

        $.post( "apptrsc/form?"+$.param(saveParam), $("#frm_patient_info").serialize()+'&'+$.param(postobj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('there is an error');
        }).success(function(data){
            $('#mdl_patient_info').modal('hide');
            $('#mdl_existing_record').modal('hide');
			$('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
        });
 	}
	//////////////////////end pasal biodata/////////////////////////

	/////////////////start pasal episode//////////////////

	$('#episode_but_apptrsc').click(function(){
		var data = $(this).data('bio_from_calander');

		if(data==undefined){
			alert('no patient biodata selected');
		}else{
	        $('#editEpisode').modal({backdrop: "static"});
	        $('#editEpisode').modal('show');
		}

		// populate_patient_episode("episode",$(this).data("rowId"));
		// populate_episode_from_episno();
  //       $('#editEpisode').modal({backdrop: "static"});
  //       $('#btn_epis_payer').data('mrn',$(this).data("mrn"));

  //       disableEpisode(true);

	});

	$('#new_episode').click(function(){
		populate_new_episode_by_mrn_apptrsc($('form#addForm input[name="mrn"]').val());
		$("#dialogForm").dialog('close');
        $('#editEpisode').modal({backdrop: "static"});
	});

	$("#editEpisode").on('hidden.bs.modal', function () {
		lastelement.dblclick();
		// $("#dialogForm").dialog('open');
    });


	//hide at the episode
	$('#div_doctor,#div_bed,#div_nok,#div_payer,#div_deposit').hide();

});

var lastelement; //utk tahu last edit mrn T.T

var epis_desc_show = new loading_desc_epis([
    {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
    {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
    {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
    {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
    {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
    {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
    {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'},
    {code:'#hid_newgl_occupcode',desc:'#txt_newgl_occupcode',id:'newgl_occupcode'},
    {code:'#hid_newgl_relatecode',desc:'#txt_newgl_relatecode',id:'newgl_relatecode'}
]);

function populate_new_episode_by_mrn_apptrsc(mrn){
	var param={
		url:'./apptrsc/table',
        action:'populate_new_episode_by_mrn_apptrsc',
        mrn:mrn
    };

    $.get( param.url+"?"+$.param(param), function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			let pat_mast = data.pat_mast;
			disableEpisode(true);

	        $('input[type="hidden"]#mrn_episode').val(pat_mast.MRN);
	        $('form#epis_header big#txt_epis_name').text(pat_mast.Name);
	        $('form#epis_header big#txt_epis_mrn').text(('0000000' + pat_mast.MRN).slice(-7));
	        $('form#epis_header input#txt_epis_type').val("OP");
	        $('form#epis_header input#txt_epis_date').val(moment().format('DD/MM/YYYY'));
	        $('form#epis_header input#txt_epis_time').val(moment().format('hh:mm:ss'));
	        $('form#form_episode button#btn_epis_payer').data('mrn',pat_mast.MRN);
	        if(pat_mast.Sex == "M"){
	            $('form#epis_header select#cmb_epis_pregnancy').val('Non-Pregnant');
	            $('form#epis_header select#cmb_epis_pregnancy').prop("disabled", true);
	        }else{
	            $('form#epis_header select#cmb_epis_pregnancy').prop("disabled", false);
	        }

	        get_billtype_default(pat_mast.MRN);

	        var episno_ = 0;

	        if(episno_ > 0){
	            get_epis_other_data(rowdata.MRN);
	        }

	        if(pat_mast.PatStatus == 1){
	            $("#episode_oper").val('edit');
	            $('#txt_epis_no').val(parseInt(episno_));
	            populate_episode_by_mrn_episno(rowdata.MRN,rowdata.Episno);
	            $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().show();
	        }else{
	            $("#episode_oper").val('add');
	            $('#txt_epis_no').val(parseInt(episno_) + 1);
	            $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().hide();
	            $('#txt_epis_dept').blur();
	        }
		}else{
		}
	});

}

 var epis_desc_show = new loading_desc_epis([
    {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
    {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
    {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
    {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
    {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
    {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
    {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'},
    {code:'#hid_newgl_occupcode',desc:'#txt_newgl_occupcode',id:'newgl_occupcode'},
    {code:'#hid_newgl_relatecode',desc:'#txt_newgl_relatecode',id:'newgl_relatecode'}
]);

epis_desc_show.load_desc();