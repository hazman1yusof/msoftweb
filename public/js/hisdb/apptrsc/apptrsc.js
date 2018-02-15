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

	if(Class2 == 'DOC'){
		$('#Scol').val('Doctor')
	}else{
		$('#Scol').val('Resource')
	}

	var apptsession = null, interval = null;
	var dialog_name = new ordialog(
		'resourcecode', ['hisdb.apptresrc AS a', 'hisdb.doctor AS d'], "input[name='resourcecode']", errorField,
        {
            colModel: [
                { label: 'Resource Code', name: 'a_resourcecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'a_description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Interval Time', name: 'd_intervaltime', width: 400, classes: 'pointer', hidden:true},
            ],
            ondblClickRow: function () {
				$('.fc-myCustomButton-button').show();
				let data = selrowData('#' + dialog_name.gridname);
				interval = data['d_intervaltime'];

				var param ={
					action:"get_value_default",
					url:'/util/get_value_default',
					field:'*',
					table_name:'hisdb.apptsession',
					table_id:'idno',
					filterCol:['doctorcode'],
					filterVal:[data['a_resourcecode']]
				};

				$.get("/util/get_value_default", param, function (data) {
				},'json').done(function (data) {
					if(!$.isEmptyObject(data.rows)){
						apptsession = data.rows;
						session_field.addSessionInterval(interval,apptsession);
					}
				});
			}
        },{
            title: "Select Doctor",
            open: function () {
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
				var events = {
					url: "apptrsc/getEvent",
					type: 'GET',
					data: {
						drrsc: $('#resourcecode').val()
					}
				}
		
				$('#calendar').fullCalendar( 'removeEventSource', events);
				$('#calendar').fullCalendar( 'addEventSource', events);
				$('#calendar').fullCalendar( 'refetchEvents' );
			}
        }, 'urlParam'
    );
	dialog_name.makedialog(true);

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
				// var test = $('#Class2').val();
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
				{	label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', canSearch: true, or_search: true },
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
				// var test = $('#Class2').val();
				dialog_mrn.urlParam.filterCol = ['compcode'];
				dialog_mrn.urlParam.filterVal = ['9A'];
			},
		}, 'urlParam'
	);
	dialog_mrn.makedialog(true);

	$("body").show();
	var d = new Date();

	$("#dialogForm").dialog({
		autoOpen: false,
		width: 9.5 / 10 * $(window).width(),
		modal: true
	});

	var session_field = new session_field();
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

		this.addSessionInterval = function(interval,apptsession){
			this.apptsession = apptsession;
			this.interval = interval;
			this.interval_hour = interval.split(":")[0];
			this.interval_minute = interval.split(":")[1];

			return this;
		}

		this.clear = function(){
			$("#apptdatefr_time").find('option').remove();
			$("#apptdateto_time").find('option').remove();

			return this;
		}

		this.ready = function(){
			this.date_fr_1 = $('#apptdatefr_day').val();
			this.date_fr_2 = $('#apptdateto_day').val();
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
			var fr1_1_start = this.fr1_1_start, date_fr_1 = this.date_fr_1, fr_1_obj = this.fr_1_obj, interval_hour= this.interval_hour,interval_minute = this.interval_minute, fr1_2_start = this.fr1_2_start, date_fr_2 = this.date_fr_2, fr_2_obj = this.fr_2_obj;

			while(!fr1_1_start.isSameOrAfter(date_fr_1+" "+fr_1_obj[0].timeto1)){
    			let time_use = fr1_1_start.format("HH:mm:SS");
    			$("#apptdatefr_time").append("<option value='"+time_use+"'>"+time_use+"</option>");
    			fr1_1_start = fr1_1_start.add(interval_hour, 'hours');
    			fr1_1_start = fr1_1_start.add(interval_minute, 'minutes');
        	}

        	fr1_1_start = moment(date_fr_1+" "+fr_1_obj[0].timefr2);

        	while(!fr1_1_start.isSameOrAfter(date_fr_1+" "+fr_1_obj[0].timeto2)){
    			let time_use = fr1_1_start.format("HH:mm:SS");
    			$("#apptdatefr_time").append("<option value='"+time_use+"'>"+time_use+"</option>");
    			fr1_1_start = fr1_1_start.add(interval_hour, 'hours');
    			fr1_1_start = fr1_1_start.add(interval_minute, 'minutes');
        	}

        	while(!fr1_2_start.isSameOrAfter(date_fr_2+" "+fr_2_obj[0].timeto1)){
    			let time_use = fr1_2_start.format("HH:mm:SS");
    			$("#apptdateto_time").append("<option value='"+time_use+"'>"+time_use+"</option>");
    			fr1_2_start = fr1_2_start.add(interval_hour, 'hours');
    			fr1_2_start = fr1_2_start.add(interval_minute, 'minutes');
        	}

        	fr1_2_start = moment(date_fr_2+" "+fr_2_obj[0].timefr2);

        	while(!fr1_2_start.isSameOrAfter(date_fr_2+" "+fr_2_obj[0].timeto2)){
    			let time_use = fr1_2_start.format("HH:mm:SS");
    			$("#apptdateto_time").append("<option value='"+time_use+"'>"+time_use+"</option>");
    			fr1_2_start = fr1_2_start.add(interval_hour, 'hours');
    			fr1_2_start = fr1_2_start.add(interval_minute, 'minutes');
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
	            	session_field.ready().set();

					var temp = $('#resourcecode').val();
					var start = $(".fc-myCustomButton-button").data( "start");
					var end = $(".fc-myCustomButton-button").data("end");

					$('#dialogForm #doctor').val(temp);
					
					$('#dialogForm #start').datetimepicker({
						format: 'YYYY-MM-DD HH:mm:ss',
						stepping: 15
					});
						
					$('#dialogForm #end').datetimepicker({
						format: 'YYYY-MM-DD HH:mm:ss',
						stepping: 15
					});
						
					// $('#dialogForm #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
					// $('#dialogForm #end').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
					
					$("#dialogForm").dialog("open");
            	}
        	}
    	},
		// defaultDate: '2016-01-12',
		defaultDate: d,
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		selectable: true,
		selectHelper: true,
		select: function(start, end) {
			$('#calendar').fullCalendar('changeView', 'agendaDay', moment(start).format('YYYY-MM-DD'));
			$(".fc-myCustomButton-button").data( "start", start );
			$(".fc-myCustomButton-button").data( "end", end );
		},
		eventRender: function(event, element) {
			console.log(element);
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
				drrsc:''
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

	$('#submitEdit').click(function(){
		$.post("apptrsc/editEvent", $("#editForm").serialize(), function (data) {
		}).fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
			emptyFormdata(errorField,"#addForm");
			$("#ModalEdit").modal('hide');
			
			var events = {
							url: "apptrsc/getEvent",
							type: 'GET',
							data: {
								drrsc: $('#resourcecode').val()
							}
						}
				
			$('#calendar').fullCalendar( 'removeEventSource', events);
			$('#calendar').fullCalendar( 'addEventSource', events);
			$('#calendar').fullCalendar( 'refetchEvents' );
		});
	});

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
				$('#calendar').fullCalendar( 'refetchEvents' );
			});
		}
	});
	
});


