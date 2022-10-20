/* Write here your custom javascript codes */
function dblclick(event){
	//code for dblclick
	prevTime = typeof currentTime === 'undefined' || currentTime === null
        ? new Date().getTime() - 1000000
        : currentTime;
    currentTime = new Date().getTime();

	if (currentTime - prevTime < 500)
	{
	    //double click call back
	    console.log("this is double click 2");
	}
}

function appt_day_list(ty,date){
	if(ty == 1){
	console.log('Clicked on: ' + date.format());;

		if($('#docid').val() != ''){
			jQuery("#gridDialog").jqGrid().setGridParam({url : "/msoftweb/assets/php/entry_appt.php?action=get_appointment_list&id="+$('#docid').val()+"&start="+date.format()+"&end="+date.format()+"&typ=grid"}).trigger("reloadGrid")
		}
	}else{

		if($('#docid').val() != ''){
			$('.nav-tabs a[href="#search"]').tab('show');
			jQuery("#gridDialog").jqGrid().setGridParam({url : "/msoftweb/assets/php/entry_appt.php?action=get_appointment_list&id="+$('#docid').val()+"&start="+date+"&end="+date+"&typ=grid"}).trigger("reloadGrid")
		}
	}
}

var Doctor = function () {

	function first_load(){
	
		var today=new Date();		
		
		$('#cmb_month').val(today.getMonth());
		$('#cmb_year').val(today.getFullYear());
	
		$('#cmb_doctor-2').val('');
		$('#cmb_doctor').val('');
		$('#docid').val('');
		
		var tempVar = "";
		var EventtempVar = "";
		var lastdateclick = '';
		var lastdateview = '';
		
		$('#calendar').fullCalendar({
		    loading: function (bool) {
		    	$.LoadingOverlay("show");

				setTimeout(function(){
					$.LoadingOverlay("hide");
				}, 1000);
		    },
		    eventAfterAllRender: function (view) {
			    $.LoadingOverlay("hide");
			    //populate_all_doctor('ent01');
		    },
    			header: {
				left: '',
				center: 'title',
				right: 'prev,next'
			},
			height: $(window).height()-230,
			editable: false,
			
			slotDuration: "00:30:00",
			fixedWeekCount: true,
			eventLimit: 2, // allow "more" link when too many events
    		events: "",	
    		eventColor: 'white',		
			eventRender: function (event, element, view) {	
				element.find(".fc-time").remove();	
				element.find('span.fc-title').html(element.find('span.fc-title').text());
			},
		    dayClick: function(date, jsEvent, view) {
		    	console.log('day click');
		    	dblclick();
		    	
		    	appt_day_list(1,date);
		    },
		    eventClick: function(event, jsEvent, view) {
		    	console.log('event click');
		    	dblclick();
		    	
		    	dttime = moment(event.start).format('YYYY-MM-DD');
		    	appt_day_list(2,dttime);
		    },
   		});

    }
    
    function populate_all_doctor(doc_id)
    {

		    var events = {
	            url: "/msoftweb/assets/php/entry_appt.php",
		        type: 'GET',
		        data: {
	            	action: "get_appointment_list",
	            	typ: 'doctor',
	                id: doc_id
		        }
		    }
		    $('#calendar').fullCalendar( 'removeEventSource', events);
		    $('#calendar').fullCalendar( 'addEventSource', events);  
    }

    function populate_all_patient(pat_id)
    {
	        var events = {
	            url: "/msoftweb/assets/php/entry_appt.php",
	            type: 'GET',
	            data: {
	            	action: "get_appointment_list3",
	            	typ: 'patient',
	                id: pat_id
	            }
	        };
	        	
	        $('#calendar').fullCalendar( 'removeEventSource', events);
	        $('#calendar').fullCalendar( 'addEventSource', events);         
	        $('#calendar').fullCalendar( 'refetchEvents' );
	        
	        jQuery("#gridDialog").jqGrid().setGridParam({url : "/msoftweb/assets/php/entry_appt.php?action=get_appointment_list&id="+pat_id+"&start=&end=&typ=gridpatient"}).trigger("reloadGrid")
    }


    function populate_patient_dtl(typ,pat_id)
    {
        $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=get_patient_dtl&typ="+typ+"&term="+pat_id, function(data)
        {
            $.each(data.rows, function (index, value) 
            {
            console.log(value);
                $("#patIc").val(value.newic);
                $("#cmb_mrn").val(value.mrn);
                $("#patName").val(value.name);
                $("#patContact").val(value.tel_h);
                $("#patHp").val(value.tel_hp);
                //not available in pat_mast //$("#patFax").val(value.faxno);
            });
        });
        
    }

function activaTab(tab){
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};

    function populate_appt_dtl(appt_id)
    {
    	console.log('f populate appt dtl');
    	
        $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=get_appointment_info&apptid="+appt_id, function(data)
        {
			$('#li-apptdtl').show();
			$('#div-calendar').hide();
			$('#li-calendar').show();
	    	window.scrollTo(0, 0);
			
			activaTab('apptdtl');
			        
            $.each(data.appt, function (index, value) 
            {
				$('#span-timedisplay').html('('+value.apptdate+')');
				$('#span-mrndisplay').html('('+value.mrn+')');
				
                $("#sysno").val(value.sysno);
                $("#patDoc-id").val(value.location);
                $("#patDoc").val(value.description);
            	
                $("#schDateTime").val(value.apptdate);
                $("#schDateTime").val(value.apptdate);
                $("#schTime").val(value.appttime);
                $("#patStatus").val(value.apptstatus);
                
		    	$("#patCase").val(value.casedesc);
		    	$("#patCaseid").val(value.case_code);
		    	$("#patNote").val(value.patNote);
                $("#patIc").val(value.patIc);
                $("#cmb_mrn").val(value.mrn);
                $("#patName").val(value.pat_name);
                $("#patContact").val(value.telno);
                $("#patHp").val(value.telhp);
                $("#patFax").val(value.faxno);
            });
            
            if(appt_id == '')
            	$('#btn-appt-type').val('Save');
            else
            	$('#btn-appt-type').val('Update');
        });

    }

    function get_appt_lst(frm,docid,dt)
    {
    	if(docid == '')
    		return;
    		
        $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=get_appt_lst&docid="+docid+"&dt="+dt, function(data)
        {
            $.each(data.appt_lst, function (index, value) 
            {
            	if(frm == 'sbTwo'){
	                $("#selected_users").append('<div id="user'+value.sysno+'" userid="'+value.sysno+'" class="innertxt2">'+
	                	'<strong>'+value.pat_name+'</strong>'+
					    '<ul>'+
	                    '<li>Date/Time: '+value.apptdate+' '+value.appttime+'</li>'+
	                    '<li>User IC: '+value.icnum+'</li>'+
	                    '<li>Tel No.: '+value.telno+'</li>'+
	                    '</ul>'+
	                	'</div><div class="float_break"></div> ');
	            }else{
	                $("#all_users").append('<div id="user'+value.sysno+'" userid="'+value.sysno+'" class="innertxt">'+
	                	'<strong>'+value.pat_name+'</strong>'+
					    '<ul>'+
	                    '<li>Date/Time: '+value.apptdate+' '+value.appttime+'</li>'+
	                    '<li>User IC: '+value.icnum+'</li>'+
	                    '<li>Tel No.: '+value.telno+'</li>'+
	                    '<li style="padding-top:5px;"><input type="checkbox" id="select123" value="'+value.sysno+'" class="selectit" /><label for="select'+value.sysno+'">&nbsp;&nbsp;Select it.</label></li>'+
	                    '</ul>'+
	                	'</div>                <div class="float_break"></div> ');

                }
            });
            
            if(frm != 'sbTwo'){
	            call_transfer();
	        }
            
        });
    }
    
   
    function get_appointment_list(doc_id)
    {
    	if(doc_id == '')
    		return;
    		
        $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=get_appointment_list4&docid="+doc_id, function(data)
        {
            //console.log(data.appointment);
            populate_all_calendar(data.appointment);

        });
    }
    
   
    return {

        init_calendar: function (doc_id) {
        	//_cleanup();
        	populate_all_calendar('');
        },
        
        init_doctor: function (doc_id) {
            populate_all_doctor(doc_id);
        //    populate_all_patient();
        },
        
        init_available: function (utime,doc_id) {
            get_doctor_available(utime,doc_id);
        },
        
        init_patient: function (pat_id) {
        	populate_all_patient(pat_id);
        	//populate_patient_dtl(pat_id);
        },
        
        init_patient_dtl: function (typ,pat_id){        
        	populate_patient_dtl(typ,pat_id);
        },
        
        init_appointment: function (typ,appt_id) {
        	populate_appt_dtl(typ,appt_id);
        },

        init_save: function () {
        	save_appt_dtl();
        },
        
        init_appt_lst: function (frm,docid,dt) {
        	get_appt_lst(frm,docid,dt);
        },

        init_load: function () {        
        	first_load();
        }
    };

}();

function appt_cleanup(){
	console.log('data cleanup');
    $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=appt_cleanup",
    function(data)
    {
        console.log('done cleanup');
        populate_all_calendar('');
    });
}

function get_doctor_available(unixtime,id){
	console.log('data cleanup');
    $.getJSON( "/msoftweb/assets/php/entry_appt.php?action=get_doctor_available&tm="+unixtime+"&id="+id,
    function(data)
    {
        console.log(data.doctor_available);
        //populate_all_calendar('');
    });
}



function FcGoToDate(){
	var monthNames = ["January", "February", "March", "April", "May", "June",
	  "July", "August", "September", "October", "November", "December"
	];
	
	cmbdt = new Date('01 '+monthNames[$('#cmb_month').val()]+' '+$('#cmb_year').val());

	$('#calendar').fullCalendar( 'gotoDate', cmbdt );
	$('#calendar').fullCalendar('changeView', 'month');

}

function selCalendar(){
	$("#dialog-form").dialog("close");
	
}

function validate_apptform(frm){
	if(frm == 'date'){
		if($('#schDateTime').val()!='' && $('#schTime').val()!=''){
			var selectedDate = $('#schDateTime').val()+' '+$('#schTime').val();		
		    var today = new Date();
		    
		    console.log((selectedDate) +'|'+ (today));
		    console.log(Date.parse(selectedDate) +'|'+ Date.parse(today));
		    
		    if (Date.parse(selectedDate) > Date.parse(today)) {
		        Doctor.init_available(Date.parse(selectedDate),$("#docid").val());
		    } else {
		        alert('Selected date cannot be backdated');
		    }
		}	
	}
}

function add_appointment_info(){

	console.log($("#schDateTime").val().split("T")[0]+' | '+$("#schDateTime").val().split("T")[1]);

	var url = '/msoftweb/assets/php/entry_appt.php?action=add_appointment_info';
	var data = $("#frmAppt").serialize();
	
    if (confirm('Are you sure you want to save these details?')) {	
		$.getJSON(url, data, function (data, status) {
			if(data.result != ''){
				alert(data.result.msg);
			}else{
				alert('error getting result!');
			}
		})
		.success(function(data) { //if(data.result.err == ''){
			console.log('update successfully');
			//location.href = 'form_srequest.php?wrno='+data.result.id+'&ws='+$('#ws').val();
		//} 
		})
		.error(function() { alert("error getting network connection"); })
	}
}
	
function update_appointment_info(){
	if($('#btn-appt-type').val() == 'Save')
		add_appointment_info();
	else{
		var url = '/msoftweb/assets/php/entry_appt.php?action=update_appointment_info';
		var data = $("#frmAppt").serialize();
		
	    if (confirm('Are you sure to update ?')) {	
			$.getJSON(url, data, function (data, status) {
			console.log(data);
			console.log(status);
				if(data.result != ''){
					alert(data.result.msg);
					console.log('reload calendar');
					$('#div-calendar').show();
					$('#docid').val($('#patDoc-id').val());
					Doctor.init_doctor($('#patDoc-id').val());
					$('#gridDialog').trigger( 'reloadGrid' );
				}else{
					alert('error getting result!');
				}
			})
			.success(function(data) { //if(data.result.err == ''){
				console.log('update successfully');
				//location.href = 'form_srequest.php?wrno='+data.result.id+'&ws='+$('#ws').val();
			//} 
			})
			.error(function() { alert("error getting network connection"); })
		}
	}
}