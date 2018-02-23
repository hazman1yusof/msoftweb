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

	$("body").show();
	var d = new Date();

	var Class2 = $('#Class2').val();

	if(Class2 == 'DOC'){
		$('#Scol').val('Doctor')
	}else{
		$('#Scol').val('Resource')
	}

	var dialog_name = new ordialog(
		'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='resourcecode']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
            onSelectRow: function (rowid, selected) {
				let data = selrowData('#' + dialog_name.gridname);

				var session_param ={
					action:"get_table_default",
					url:'/util/get_table_default',
					field:'*',
					table_name:'hisdb.apptsession',
					table_id:'idno',
					filterCol:['doctorcode'],
					filterVal:[data['a_resourcecode']]
				};

				refreshGrid("#grid_session",session_param);
            },
            ondblClickRow: function () {
				let data = selrowData('#' + dialog_name.gridname);
				let interval = data['d_intervaltime'];
				let apptsession = $("#grid_session").jqGrid('getRowData');
				$('.fc-myCustomButton-button').show();

				session_field.addSessionInterval(interval,apptsession);

				var events = {
					url: "apptrsc/getEvent",
					type: 'GET',
					data: {
						drrsc: $('#resourcecode').val()
					}
				}
				$('#calendar').fullCalendar( 'removeEventSource', events);
				$('#calendar').fullCalendar( 'addEventSource', events);
			}
        },{
            title: "Select Doctor",
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
		<div id='grid_session_c' class='col-xs-12' align='center' style='padding-top:10px;'>
			<table id='grid_session' class='table table-striped'></table>
			<div id='grid_session_pager'></div>
		</div>
		`);

	$("#grid_session").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Day', name: 'days', width: 80, classes: 'pointer' },
            { label: 'From Morning', name: 'timefr1', width: 100, classes: 'pointer' },
            { label: 'To Morning', name: 'timeto1', width: 100, classes: 'pointer' },
            { label: 'From Evening', name: 'timefr2', width: 100, classes: 'pointer' },
            { label: 'To Evening', name: 'timeto2', width: 100, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
		pager: "#grid_session_pager",
		onSelectRow:function(rowid, selected){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
	});

	var dialog_case = new ordialog(
		'case', 'hisdb.casetype', "#dialogForm input[name='case']", errorField,
		{
			colModel: [
				{ label: 'Case Code', name: 'case_code', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		},
		{
			title: "Select Case",
			open: function () {
				dialog_case.urlParam.filterCol = ['compcode'];
				dialog_case.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_case.makedialog(true);

	var dialog_mrn = new ordialog(
		'mrn', 'hisdb.pat_mast', "#dialogForm input[name='mrn']", errorField,
		{
			colModel: [
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', formatter: padzero, unformat: unpadzero, canSearch: true, or_search: true },
				{	label: 'Name', name: 'Name', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_mrn.gridname);
				$("#addForm input[name='patname']").val(data['Name']);
				$(dialog_mrn.textfield).parent().next().text(" ");
			}
		},
		{
			title: "Select Case",
			open: function () {
				dialog_mrn.urlParam.filterCol = ['compcode'];
				dialog_mrn.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_mrn.makedialog(true);


	$("#dialogForm").dialog({
		autoOpen: false,
		width: 9.5 / 10 * $(window).width(),
		modal: true
	});

	var session_field = new session_field();

	$("#start_time_dialog").dialog({
    	autoOpen : false, 
    	modal : true,
		width: 8/10 * $(window).width(),
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
            { label: 'Pick time', name: 'time', width: 80, classes: 'pointer' },
            { label: 'Patient Name', name: 'pat_name', width: 200, classes: 'pointer' },
            { label: 'Remarks', name: 'remarks', width: 200, classes: 'pointer' },
        ],
		autowidth:true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,
		pager: "#grid_start_time_pager",
		onSelectRow:function(rowid, selected){
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$('#start_time').val(rowid);
			$('#end_time').val(moment($('#apptdatefr_day').val()+" "+rowid).subtract(1, 'minutes').format("HH:mm:SS"));
			$("#start_time_dialog").dialog('close');
		},
	});

	function session_field(){
		this.apptsession;
		this.interval;
		this.interval_hour;
		this.interval_minute;
		this.date_fr_1;
		this.date_fr_2;
		this.day_fr_1;
		this.day_fr_2;
		this.fr_1_obj;
		this.fr_2_obj;
		this.fr1_1_start;
		this.fr1_2_start;
		this.events=[];

		this.addSessionInterval = function(interval,apptsession){
			this.apptsession = apptsession;
			this.interval = interval;

			return this;
		}

		this.clear = function(){
			$("#grid_start_time").jqGrid("clearGridData", true);
			$("#grid_end_time").jqGrid("clearGridData", true);
			return this;
		}

		this.ready = function(){
			console.log($('#calendar').fullCalendar( 'clientEvents'));
			this.date_fr_1 = $('#apptdatefr_day').val();
			this.date_fr_2 = $('#apptdatefr_day').val();
			this.day_fr_1 = moment(this.date_fr_1).format('dddd').toUpperCase();
			this.day_fr_2 = moment(this.date_fr_2).format('dddd').toUpperCase();
			let day_fr_1 = this.day_fr_1;
			let day_fr_2 = this.day_fr_2;
			this.fr_1_obj = this.apptsession.filter(function( obj ) {return obj.days == day_fr_1;});
			this.fr_2_obj = this.apptsession.filter(function( obj ) {return obj.days == day_fr_2;});
			this.fr1_1_start = moment(this.date_fr_1+" "+this.fr_1_obj[0].timefr1);
			this.fr1_2_start = moment(this.date_fr_2+" "+this.fr_2_obj[0].timefr1);

			return this;
		}

		this.set = function(){
			var fr1_1_start = this.fr1_1_start, date_fr_1 = this.date_fr_1, fr_1_obj = this.fr_1_obj, interval= this.interval, fr1_2_start = this.fr1_2_start, date_fr_2 = this.date_fr_2, fr_2_obj = this.fr_2_obj, events = this.events;

			while(!fr1_1_start.isSameOrAfter(date_fr_1+" "+fr_1_obj[0].timeto1)){
    			let time_use = fr1_1_start.format("HH:mm:SS");
    			let objuse = {time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					console.log(elem);
					if(fr1_1_start.isBetween(elem.start.local(),elem.end.local(), null, '[]')){
	    				objuse.pat_name=(objuse.pat_name=='')?elem.pat_name:objuse.pat_name+','+elem.pat_name;
	    				objuse.remarks=(objuse.remarks=='')?elem.remarks:objuse.remarks+','+elem.remarks;
					}
    			});

    			$("#grid_start_time").jqGrid('addRowData', time_use,objuse);

    			fr1_1_start = fr1_1_start.add(interval, 'minutes');
        	}

        	fr1_1_start = moment(date_fr_1+" "+fr_1_obj[0].timefr2);

        	while(!fr1_1_start.isSameOrAfter(moment(date_fr_1+" "+fr_1_obj[0].timeto2).add(interval, 'minutes'))){
        		let time_use = fr1_1_start.format("HH:mm:SS");
    			let objuse = {time:time_use,pat_name:'',remarks:''}

    			events.forEach(function(elem,id){
					if(fr1_1_start.isBetween(elem.start.local(),elem.end.local(), null, '[]')){
	    				objuse.pat_name=(objuse.pat_name=='')?elem.pat_name:objuse.pat_name+','+elem.pat_name;
	    				objuse.remarks=(objuse.remarks=='')?elem.remarks:objuse.remarks+','+elem.remarks;
					}
    			});

    			$("#grid_start_time").jqGrid('addRowData', time_use,objuse);

    			fr1_1_start = fr1_1_start.add(interval, 'minutes');
        	}
		}
	}

	$("#apptdatefr_day,#apptdateto_day").change(function(){
		session_field.clear().ready().set();
	});
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today myCustomButton',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		customButtons: {
	        myCustomButton: {
	            text: 'Add',
	            click: function() {
	            	oper='add';
					var temp = $('#resourcecode').val();
					var start = $(".fc-myCustomButton-button").data( "start");
					var end = $(".fc-myCustomButton-button").data("end");

					$('#dialogForm #doctor').val(temp);

					$('#apptdatefr_day').val(moment(start).format('YYYY-MM-DD'));
					$('#apptdateto_day').val(moment(start).format('YYYY-MM-DD'));

	            	session_field.clear().ready().set();
					
					$("#dialogForm").dialog("open");
            	}
        	}
    	},
		defaultDate: d,
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		selectable: true,
		selectHelper: true,
		select: function(start, end) {
			// $('#calendar').fullCalendar('changeView', 'agendaDay', moment(start).format('YYYY-MM-DD'));
			$(".fc-myCustomButton-button").data( "start", start );
			$(".fc-myCustomButton-button").data( "end", end );
		},
		eventAfterRender: function( event, element, view ) { 
			console.log(event);
		},
		eventRender: function(event, element) {
			console.log(event);
			element.bind('dblclick', function() {
				oper = 'edit';

				$("#dialogForm").dialog('open');
				$("#addForm input").each(function(){
					var input=$(this);
					input.val(event[$(this).name]);
			});
				// $.each(rowData, function( index, value ) {
				// 	var input=$(form+" [name='"+index+"']");
				// 	if(input.is("[type=radio]")){
				// 		$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
				// 	}else{
				// 		input.val(value);
				// 	}
				// });

				// $('#ModalEdit #id').val(event.idno);
				// $('#ModalEdit #title').val(event.title);
				// $('#ModalEdit #color').val(event.color);
				// $('#ModalEdit').modal('show');
			});
		},
		eventDrop: function(event, delta, revertFunc) { // si changement de position

			edit(event);

		},
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

			edit(event);

		},
		events: {
			url:'apptrsc/getEvent',
			type:'GET',
			data:{
				drrsc:'',
			}
		}
	});
	$('.fc-myCustomButton-button').hide();
	
	function edit(event){
		start = event.start.format('YYYY-MM-DD HH:mm:ss');
		if(event.end){
			end = event.end.format('YYYY-MM-DD HH:mm:ss');
		}else{
			end = start;
		}
		
		id =  event.id;
		
		Event = [];
		Event[0] = id;
		Event[1] = start;
		Event[2] = end;
		
		$.ajax({
			url: 'editEventDate.php',
			type: "POST",
			data: {Event:Event},
			success: function(rep) {
				if(rep == 'OK'){
					// alert('Saved');
				}else{
					alert('Could not be saved. try again.'); 
				}
			}
		});
	}
	
	var oper = 'add';
	$('#submit').click(function(){
		if( $('#editForm').isValid({requiredFields: ''}, conf, true) ) {
			$.post("apptrsc/addEvent?oper="+oper, $("#addForm").serialize(), function (data) {
			}).fail(function (data) {
				//////////////////errorText(dialog,data.responseText);
			}).done(function (data) {
				$("#dialogForm").dialog('close');
				var events = {
								url: "apptrsc/getEvent",
								type: 'GET',
								data: {
									drrsc: $('#resourcecode').val()
								}
							}
					
				$('#calendar').fullCalendar( 'removeEventSource', events);
				$('#calendar').fullCalendar( 'addEventSource', events); 
			});
		}
	});
	
});


