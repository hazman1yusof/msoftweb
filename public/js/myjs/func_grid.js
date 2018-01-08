/*jQuery(document).ready(function() 
{
    
	$( "#create-new" ).button().on( "click", function() {
        $("#txt_doc").html($("#cmb_doctor").val());
        $("#txt_appt").html($("#schDateTime").val());
        $("#txt_name").html($("#patName").val());
        $("#txt_ic").html($("#patIc").val());
        $("#txt_mrn").html($("#cmb_mrn").val());
        $("#txt_tel").html($("#patContact").val());
        $("#txt_hp").html($("#patHp").val());
	});

	$( "#create-transfer" ).button().on( "click", function() {
		Doctor.init_save();
	});

	$( "#btn-cancel" ).button().on( "click", function() {
		$('.nav-tabs li:eq(0) a').tab('show'); 
	});
    
	$('#cmb_patient').change( function(){
        $("#patIc").val('');
        $("#patName").val('');
        $("#patContact").val('');
        Doctor.init_patient($('#cmb_patient').val());	        	
    });        


	$("#cmb_mrn").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'mrn'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.patient, function(m) {
	                return {
	                    label: m.MRN +' | '+ m.Name,
	                    value: m.MRN
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	$("#cmb_mrn").val(ui.item.value);
	    	Doctor.init_patient(ui.item.value);
	    	return false;
	    }
	});

	$("#patName").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'Name'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.patient, function(m) {
	                return {
	                    label: m.Newic+' | '+m.Name,
	                    value: m.Newic
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	$("#cmb_mrn").val(ui.item.value);
	    	$("#patName3").val(ui.item.label);
	    	Doctor.init_patient(ui.item.value);
	    	return false;
	    }
	});

	$("#patIc").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'Newic'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.patient, function(m) {
	                return {
	                    label: m.Newic+' | '+m.Name,
	                    value: m.Newic
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	//$("#cmb_mrn").val(ui.item.value);
	    	$("#patIc").val(ui.item.label);
	    	Doctor.init_patient(ui.item.value);
	    	return false;
	    }
	});

	$("#cmb_doctor-3").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_doctor_list", {
	            term: request.term
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.doctor, function(m) {
	                return {
	                    label: m.description,
	                    value: m.resourcecode
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	$("#cmb_doctor").val(ui.item.label);
	    	$("#docid").val(ui.item.value);
	    	$("#schDateTime-3").val('');
	    	Doctor.init_doctor(ui.item.value);
	    	return false;
	    }
	});

	$("#cmbdoctorTo").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_doctor_list", {
	            searchPhrase: request.term,
	            id:""
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.description,
	                    value: m.id
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    
			$('#sbTwo')
			    .find('option')
			    .remove()
			    .end()
			    .append('<option value=""></option>')
			    .val('')
			;		    
	    
	    	$("#cmbdoctorTo").val(ui.item.label);
	    	$("#docidTo").val(ui.item.value);
	    	Doctor.init_appt_lst('sbTwo',ui.item.value);
	    	return false;
	    }
	});

	$("#patCase").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_casetype&id=", {
	            id: request.term
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.casetype, function(m) {
	                return {
	                    label: m.description,
	                    value: m.case_code
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	console.log(ui.item);
	    	return false;
	    }
	});


	//for transfer ready
	
	call_transfer();
	
	//end transfer ready


});
*/

$(document).ready(function () {
	console.log('1');

    Doctor.init_load();

	$("#cmb_doctor").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_doctor_list", {
	            searchPhrase: request.term,
	            id:""
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.description,
	                    value: m.id
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	$("#cmb_doctor").val(ui.item.label);
	    	$("#docid").val(ui.item.value);
	    	Doctor.init_doctor(ui.item.value);
	    	//$('#calendar').fullCalendar('changeView', 'month');
	    	
	    	//load_doctor_info_leave();
	    	//reload_doctor_info();
	    	console.log(33);
	    	return false;
	    }
	});

	$("#patDoc").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_doctor_list", {
	            searchPhrase: request.term,
	            id:""
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.description,
	                    value: m.id
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    console.log(ui.item);
	    	$("#patDoc").val(ui.item.label);
	    	$("#patDoc-id").val(ui.item.value);
	    	//Doctor.init_doctor(ui.item.value);
	    	//$('#calendar').fullCalendar('changeView', 'month');
	    	
	    	//load_doctor_info_leave();
	    	//reload_doctor_info();
	    	console.log(44);
	    	return false;
	    }
	});

	$("#patCase").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_casetype&id=", {
	            searchPhrase: request.term
	        }, function(data) {
	        console.log(data);
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.description,
	                    value: m.case_code
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	console.log(ui.item);
	    	$("#patCase").val(ui.item.label);
	    	$("#patCaseid").val(ui.item.value);
	    	return false;
	    }
	});

	$("#cmb_mrn").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'mrn'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.mrn +' | '+ m.name,
	                    value: m.mrn
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	console.log(ui.item);
	    	$("#cmb_mrn").val(ui.item.value);
	    	Doctor.init_patient_dtl('mrn',ui.item.value);
	    	return false;
	    }
	});

	$("#patIc").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'newic'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.newic +' | '+ m.name,
	                    value: m.newic
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	console.log(ui.item);
	    	$("#patIc").val(ui.item.value);
	    	Doctor.init_patient_dtl('newic',ui.item.value);
	    	return false;
	    }
	});

	$("#patName").autocomplete({
		source: function(request, response) {
	        $.getJSON("/msoftweb/assets/php/entry_appt.php?action=get_all_patient", {
	            term: request.term,
	            typ: 'name'
	        }, function(data) {
	            var array = data.error ? [] : $.map(data.rows, function(m) {
	                return {
	                    label: m.newic +' | '+ m.name,
	                    value: m.name
	                };
	            });
	            response(array);
	        });
	    },
	    select: function (event, ui) {
	    	console.log(ui.item);
	    	$("#patName").val(ui.item.value);
	    	Doctor.init_patient_dtl('name',ui.item.value);
	    	return false;
	    }
	});

	jQuery("#gridDialog").jqGrid({ 
		//url:"/msoftweb/assets/php/entry_appt.php?action=get_appointment_list&id=&start=&end=&typ=grid", 
		//data: data.appointment,
		datatype: "json", 
		colNames:['ID','Time','MRN','Patient Name','Status','Patient IC','Doctor'], 
		colModel:[ {name:'id',index:'id', hidden: true}, {name:'start',index:'start',width:'70px'}, {name:'mrn',index:'mrn',width:'50px'},{name:'pat_name',index:'pat_name',width:'200px'}, {name:'title',index:'title',width:'100px'},{name:'noic',index:'noic'}, {name:'doctor',index:'doctor', hidden: true}, ], 
		rowNum:20, 
		rowList:[10,20,30], 
		pager: '#gridDialogPager', 
		toppager: true,
		sortname: 'id', 
		viewrecords: true, 
		sortorder: "desc",
		height: $(window).height()-45,
		//height: "300px",
		autowidth: true,
		shrinkToFit: false,
		ondblClickRow: function(rowId) {
	        var rowData = jQuery(this).getRowData(rowId); 
	        var jobNumber = rowData['id'];
            $("#dialog-form").dialog("open");
	        Doctor.init_appointment(rowData['id']);
	        $('.nav-tabs li:eq(0) a').tab('show'); 
	    }
	});

    $('#schDateTime').datepicker({
    	currentText: '',
		controlType: 'select',
		onSelect: function(dateText, inst) {
			validate_apptform('date');
		}
	});
	
	$('#schTime').timepicker({
		addSliderAccess: true,
		stepMinute: 30,
		hourMin: 7,
		hourMax: 20,
		sliderAccessArgs: { touchonly: false },
		onSelect: function(){
			validate_apptform('date');
		}
	});

	$("#dialog-casetype").dialog({
		autoOpen: false,
		maxWidth:600,
		maxHeight: 500,
		width: 600,
		height: 500,
		modal: true,
		buttons: {
			Close: function() {
				$(this).dialog("close");
			}
	    },
		close: function() {}
	});

	$("#dialog-doctor-info").dialog({
		autoOpen: false,
		maxWidth:600,
		maxHeight: 500,
		width: 600,
		height: 500,
		modal: true,
		buttons: {
			Close: function() {
				$(this).dialog("close");
			}
	    },
		close: function() {}
	});
	$("#dialog-doctor-list").dialog({
		autoOpen: false,
		maxWidth:600,
		maxHeight: 500,
		width: 600,
		height: 500,
		modal: true,
		buttons: {
			Close: function() {
				$(this).dialog("close");
			}
	    },
		close: function() {}
	});
	
	//set datetime using datetimepicker
/*    $(".date-time").datetimepicker({
		controlType: 'select',
		oneLine: true,
    	dateFormat: "yy-mm-dd",
    	//timeFormat: 'HH:mm',
        ampm: false
    });
*/		
	//populate current date
/*    var date = new Date();
    $('#test').datetimepicker('setDate', date);
*/
/*	$(function() {
		$("#dialog-form").dialog({
			autoOpen: false,
			minWidth:600,
			minHeight: 500,
			width: $(window).width()-200,
			height: $(window).height()-10,
			modal: true,
			buttons: {
				"Create": function() {
					console.log('save data');
					checkform();
					
				},
				Cancel: function() {
					$(this).dialog("close");
				}
		    },
			close: function() {}
		});
		
		$("#dialog-transfer").dialog({
			autoOpen: false,
			maxWidth:$(window).width(),
			maxHeight: $(window).height(),
			width: $(window).width(),
			height: $(window).height(),
			modal: true,
			buttons: {
				"Update": function() {
					GetAllId();},
				Close: function() {
					$(this).dialog("close");
				}
		    },
			close: function() {}
		});
		
		$("#dialog-doctor-info").dialog({
			autoOpen: false,
			maxWidth:600,
			maxHeight: 500,
			width: 600,
			height: 500,
			modal: true,
			buttons: {
				Close: function() {
					$(this).dialog("close");
				}
		    },
			close: function() {}
		});
		$("#dialog-doctor-list").dialog({
			autoOpen: false,
			maxWidth:600,
			maxHeight: 500,
			width: 600,
			height: 500,
			modal: true,
			buttons: {
				Close: function() {
					$(this).dialog("close");
				}
		    },
			close: function() {}
		});
		$("#dialog-casetype").dialog({
			autoOpen: false,
			maxWidth:600,
			maxHeight: 500,
			width: 600,
			height: 500,
			modal: true,
			buttons: {
				Close: function() {
					$(this).dialog("close");
				}
		    },
			close: function() {}
		});
	});

    $("#create-appt")
	    .button()
	    .click(function() {
	        if($('#docid').val() == ''){
	        	alert('please select doctor!');
	        	return;
	        }
        
	        document.getElementById("frmAppt").reset();
	        $("#formstatus").val('new');
	        //$('#schDateTime').datepicker({title:'Select Date'});
	        
	        $('#schDateTime').datepicker({
	        	currentText: '',
				controlType: 'select',
				onClose: function(dateText, inst) {
					validate_apptform('date');
				}
			});
			
			$('#schTime').timepicker({
				addSliderAccess: true,
				stepMinute: 30,
				hourMin: 7,
				hourMax: 20,
				sliderAccessArgs: { touchonly: false }
			});
			
	        $("#dialog-form").dialog("open");
 	        $('#cmb_doctor_3').val($('#cmb_doctor').val());
   	});

    $("#new-transfer")
	    .button()
	    .click(function() {
	    	console.log($('#docid').val());
	    
	        if($('#docid').val() == ''){
	        	alert('please select doctor!');
	        	return;
	        }
		
		$('#sbOne')
		 .empty();
		
		$('#sbTwo')
		 .empty()
	
	    $('#schDateTimeFrom').datepicker({
	    	currentText: '',
			controlType: 'select',
			onClose: function(dateText, inst) {
				//validate_apptform('date');
				$('#all_users').html('');
				
				Doctor.init_appt_lst('sbOne',$('#docid').val(),dateText);
				console.log('schDateTimeFrom');
			}
		});
				

        $('#schDateTimeTo').datepicker({
        	currentText: '',
			controlType: 'select',
			onClose: function(dateText, inst) {
				//validate_apptform('date');
				$('#selected_users').html('');
				
				Doctor.init_appt_lst('sbTwo',$('#docidTo').val(),dateText);
				console.log('schDateTimeTo');
			}
		});
		
        $("#dialog-transfer").dialog("open");                
        $('#cmbdoctorFrom').val($('#cmb_doctor').val());
        console.log('goto appt llst');
    });


    $(function () {
        function moveItems(origin, dest) {
            $(origin).find(':selected').appendTo(dest);
        }

        function moveAllItems(origin, dest) {
            $(origin).children().appendTo(dest);
        }

        $('#left').click(function () {
            moveItems('#sbTwo', '#sbOne');
        });

        $('#right').on('click', function () {
            moveItems('#sbOne', '#sbTwo');
        });

        $('#leftall').on('click', function () {
            moveAllItems('#sbTwo', '#sbOne');
        });

        $('#rightall').on('click', function () {
            moveAllItems('#sbOne', '#sbTwo');
        });
    });
*/
});

function load_grid_2(){
console.log('load_grid_2');
	
	var spacesToAdd = 5;
	var biggestLength = 0;
	$("#sbOne option").each(function(){
	var len = $(this).text().length;
	    if(len > biggestLength){
	        biggestLength = len;
	    }
	});
	
	var padLength = biggestLength + spacesToAdd;
	$("#sbOne option").each(function(){
	    var parts = $(this).text().split('+');
	    var strLength = parts[0].length;
	    for(var x=0; x<(padLength-strLength); x++){
	        parts[0] = parts[0]+' '; 
	    }
	    $(this).text(parts[0].replace(/ /g, '\u00a0')+'+'+parts[1]).text;
	});
	
	
	var addedrows = new Array();
}
 
function load_grid() {
	console.log('load_grid');
	
    $( "#sourcetable tbody tr" ).on( "click", function( event ) {
   
    var ok = 0;
    var theid = $( this ).attr('id').replace("sour","");    
 
    var newaddedrows = new Array();
     
    for (index = 0; index < addedrows.length; ++index) {
 
        // if already selected then remove
        if (addedrows[index] == theid) {
                
            $( this ).css( "background-color", "#ffccff" );
             
            // remove from second table :
            var tr = $( "#dest" + theid );
            tr.css("background-color","#FF3700");
            tr.fadeOut(400, function(){
                tr.remove();
            });
             
            //addedrows.splice(theid, 1);   
             
            //the boolean
            ok = 1;
        } else {
         
            newaddedrows.push(addedrows[index]);
        } 
    }   
     
    addedrows = newaddedrows;
     
    // if no match found then add the row :
    if (!ok) {
        // retrieve the id of the element to match the id of the new row :
         
         
        addedrows.push( theid);
         
        $( this ).css( "background-color", "#cacaca" );
                 
        $('#destinationtable tr:last').after('<tr id="dest' + theid + '"><td>'
                                       + $(this).find("td").eq(0).html() + '</td><td>'
                                       + $(this).find("td").eq(1).html() + '</td><td>'
                                       + $(this).find("td").eq(2).html() + '</td><td>'
                                       + $(this).find("td").eq(3).html() + '</td><td>'
                                       + $(this).find("td").eq(4).html() + '</td></tr>');         
         
    }
 
     
    });
}  




    function reload_doctor_info(){
		jQuery("#gridTBreak").jqGrid().setGridParam({url : '/msoftweb/assets/php/entry_appt.php?action=get_doctor_break&id='+$('#docid').val()}).trigger("reloadGrid")
		jQuery("#gridLRec").jqGrid().setGridParam({url : '/msoftweb/assets/php/entry_appt.php?action=get_doctor_leave&id='+$('#docid').val()}).trigger("reloadGrid")
   }
    
    function reload_casetype(){
		jQuery("#gridCaseType").jqGrid().setGridParam({url : '/msoftweb/assets/php/entry_appt.php?action=get_casetype&id='}).trigger("reloadGrid")
   }
   

    
	function load_doctor_list_dialog(){
            $("#gridDocList").jqGrid({
                url: '/msoftweb/assets/php/entry_appt.php?action=get_appointment_list&id=&start=&end=&typ=grid',
                mtype: "GET",
                datatype: "json",
                page: 1,
                colModel: [
                    {   label : "Code",
						sorttype: 'integer',
						name: 'OrderID', 
						key: true, 
						width: 150
					},
					{
						label : "Remark",
                        name: 'Name',
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
							   $(element).attr("autocomplete","off").typeahead({ 
								   appendTo : "body",
									source: function(query, proxy) {
										$.ajax({
											url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/autocompletepbs.php?callback=?&acelem=ShipName',
											dataType: "jsonp",
											data: {term: query},
											success : proxy
										});
									}
							   });
                            }
                        }
                    }
                ],
				loadonce: true,
				viewrecords: true,
				shrinkToFit: true,
                //width: 100+"%",
                height: 250,
                rowNum: 10,
                pager: "#gridPagerDocList"
            });
			// activate the build in navigator
            $('#gridDocList').navGrid("#gridPagerDocList", {                
                search: true, // show search button on the toolbar
                add: false,
                edit: false,
                del: false,
                refresh: true
            });
	}


