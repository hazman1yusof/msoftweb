<?php 
	include_once('../../../../assets/php/sschecker.php'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
<link href="../../../../assets/plugins/form-validator/theme-default.css" rel="stylesheet" />
<link href="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
<link href="../../../../assets/plugins/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/css/trirand/ui.jqgrid-bootstrap.css" rel="stylesheet" />
<link href="../../../../assets/plugins/searchCSS/stylesSearch.css" rel="stylesheet">
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.css" rel="stylesheet" />
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.print.css" media="print" rel="stylesheet" />
<style>
.wrap {
	word-wrap: break-word;
}
.ui-th-column {
	word-wrap: break-word;
	white-space: normal !important;
	vertical-align: top !important;
}
.radio-inline + .radio-inline {
	margin-left: 0;
}
.radio-inline {
	margin-right: 10px;
}
::-webkit-scrollbar {
	width: 6px; /* for vertical scrollbars */;
	height: 6px; /* for horizontal scrollbars */
}
::-webkit-scrollbar-track {
	background: rgba(0, 0, 0, 0.1);
}
::-webkit-scrollbar-thumb {
	background: rgba(0, 0, 0, 0.5);
}
h2 {
	font-size: 24px;
}
.custom-combobox {
	position: relative;
	display: inline-block;
}
.custom-combobox-toggle {
	position: absolute;
	top: 0;
	bottom: 0;
	margin-left: -1px;
	padding: 0;
}
.custom-combobox-input {
	margin: 0;
	padding: 5px 5px;
}
.auto-style1 {
	text-decoration: underline;
}
</style>
<meta charset="utf-8" />
<title>Appointment - Doctor</title>
</head>

<body>

<div style="background-color: #99CCFF; height: 45px; display: none">
	<div style="float: left; width: 40%; padding: 5px">
		<div style="height: 40px">
		</div>
	</div>
	<div style="float: right; width: 60%; padding: 5px; text-align: right">
		<div style="height: 40px">
			<!--button id="create-new">New Appointment</button--></div>
	</div>
</div>

<div style="float: left; width: 100%; padding: 5px">
	<div style="padding: 15px; height:60px; text-align: left; margin: 10px 5px; border: thin silver solid">
		<div id="dvmenu" style="float: left; width:60%">		
			<input id="docid" name="docid" type="hidden">
			<input id="cmb_doctor" class="form-control input-sm" name="cmb_doctor" placeholder="select doctor" type="text"><br>
			<select id="cmb_day" style="display:none"><option value="1">1</option><option value="2">2</option></select>
			<select id="cmb_month">
			<option value="January">January</option>
			<option value="February">February</option>
			<option value="March">March</option>
			<option value="April">April</option>
			<option value="May">May</option>
			<option value="June">June</option>
			<option value="July">July</option>
			<option value="August">August</option>
			<option value="September">September</option>
			<option value="October">October</option>
			<option value="November">November</option>
			<option value="December">December</option>
			</select>
			<select id="cmb_year"><option value="2015">2015</option><option value="2016">2016</option></select>
			<input type="button" onclick="FcGoToDate()" value="Goto Date">
	
		</div>
		<div id="dvmenu2" style="float: right; width:40%; text-align:right">		
			<input type="button" id="create-appt" class="btn" value="New Appointment">&nbsp; <input id="new-transfer" type="button" class="btn" value="Transfer">&nbsp; <input type="button" class="btn" value="Search">
		</div>
		<!--h5>Appointment</h5>
		<input type="button" value="Show today appointment" onclick="Doctor.init_load();"><br>
		<input id="schDateTime" class="form-control input-sm" name="schDateTime" placeholder="*date selected from calendar" readonly type="hidden"><br>
		<input id="patIc" class="form-control input-sm" name="patIc" placeholder="select patient IC" type="text"><br-->
	</div>
			


</div>

<div style="float: left; width: 100%; padding: 5px">
	<div id="calendar">
	</div>
</div>
<div style="float: right; width: 100%; padding: 5px; text-align: right" id="divGrid">
	<ul class="nav nav-tabs" style="display:none">
		<li class="active"><a data-toggle="tab" href="#search">Appoinment List</a></li>
		<li><a data-toggle="tab" href="#appt">Appointment Detail</a></li>
		<li><a data-toggle="tab" href="#apptnew">New Appointment</a></li>
	</ul>
	<div class="tab-content">
		<div id="search" class="tab-pane fade in active" style="text-align: left">
			<!--h4>Search Criteria</h4-->
			
			<div id="gridDialogPager">
			</div>
			<table id="gridDialog" class="table table-striped" style="width: 100%">
			</table>
		</div>
		<div id="appt" class="tab-pane fade in">
		
			<!--label class="col-md-2 control-label" for="name">Status</label>  
			<div class="col-md-10">
				<select id="cmb_status" class="form-control" name="cmb_status">
				<option value="Open">Open</option>
				</select><br>
			</div--><label class="col-md-2 control-label" for="name">Date/Time:
			</label>
			<div class="col-md-10 selPat">
				<input id="schDateTime-2" class="form-control input-sm" name="schDateTime-2" readonly type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Doctor: </label>
			<div class="col-md-10 selPat">
				<input id="cmb_doctor-2" class="form-control input-sm" name="cmb_doctor-2" readonly type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="status">Status</label>
			<div class="col-md-10">
				<select id="patStatus" class="form-control" name="patStatus">
				<option value="Open">Open</option>
				<option value="Attend">Attend</option>
				<option value="Cancel">Cancel</option>
				</select><br></div>
			<label class="col-md-2 control-label" for="name">MRN</label>
			<div class="col-md-10 selPat">
				<input id="cmb_mrn" class="form-control" name="cmb_mrn" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">IC No.</label>
			<div class="col-md-10">
				<input id="patIc-2" class="form-control input-sm" name="patIc-2" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Name</label>
			<div class="col-md-10">
				<input id="patName" class="form-control input-sm" data-validation="required" name="patName" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for=""></label>
			<div class="col-md-10">
				<table>
					<tr>
						<td><label class="col-md-4 control-label" for="name">Telephone</label>
						<div class="col-md-8">
							<input id="patContact" class="form-control input-sm" data-validation="required" name="patContact" type="text"><br>
						</div>
						</td>
						<td><label class="col-md-4 control-label" for="name">Handphone</label>
						<div class="col-md-8">
							<input id="patHp" class="form-control input-sm" data-validation="required" name="patHp" type="text"><br>
						</div>
						</td>
					</tr>
					<tr>
						<td><label class="col-md-4 control-label" for="name">Tel. 
						Office</label>
						<div class="col-md-8">
							<input id="patFax" class="form-control input-sm" data-validation="required" name="patFax" type="text"><br>
						</div>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<label class="col-md-2 control-label" for="name">Case</label>
			<div class="col-md-10">
				<input id="patCase" class="form-control input-sm" data-validation="required" name="patCase" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Remarks</label>
			<div class="col-md-10">
				<textarea id="patNote" class="form-control input-sm" data-validation="required" name="patNote" type="text"></textarea><br>
			</div>
			<!--label class="col-md-2 control-label" for="name">Contact No.</label>
			<div class="col-md-10">
			<input id="patContact" name="patContact" type="text" class="form-control input-sm" data-validation="required"><br>
			</div-->
			<h5 style="padding-right: 15px">
			<!--button id="create-new" name="create-new">New Appointment</button>
			<button id="btn-cancel">Check Appointment</button-->
			<input type="button" value="Update">
			</h5>
		</div>
		<div id="apptnew" class="tab-pane fade in">
			<label class="col-md-2 control-label" for="name">Doctor: </label>
			<div class="col-md-10 selPat">
				<input id="cmb_doctor-3" class="form-control input-sm" name="cmb_doctor-3" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Date/Time:
			</label>
			<div class="col-md-10 selPat">
				<input id="schDateTime-3" class="form-control input-sm" name="schDateTime-3" readonly type="text" placeholder="Please select datetime from calendar"><br>
			</div>
			<label class="col-md-2 control-label" for="status">Status</label>
			<div class="col-md-10">
				<select id="patStatus" class="form-control" name="patStatus">
				<option value="Open">Open</option>
				<option value="Attend">Attend</option>
				<option value="Cancelled">Cancelled</option>
				</select><br></div>
			<label class="col-md-2 control-label" for="name">MRN</label>
			<div class="col-md-10 selPat">
				<input id="cmb_mrn-3" class="form-control" name="cmb_mrn-3" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">IC No.</label>
			<div class="col-md-10">
				<input id="patIc-3" class="form-control input-sm" name="patIc-3" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Name</label>
			<div class="col-md-10">
				<input id="patName-3" class="form-control input-sm" data-validation="required" name="patName-3" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for=""></label>
			<div class="col-md-10">
				<table>
					<tr>
						<td><label class="col-md-4 control-label" for="name">Telephone</label>
						<div class="col-md-8">
							<input id="patContact-3" class="form-control input-sm" data-validation="required" name="patContact-3" type="text"><br>
						</div>
						</td>
						<td><label class="col-md-4 control-label" for="name">Handphone</label>
						<div class="col-md-8">
							<input id="patHp-3" class="form-control input-sm" data-validation="required" name="patHp-3" type="text"><br>
						</div>
						</td>
					</tr>
					<tr>
						<td><label class="col-md-4 control-label" for="name">Tel. 
						Office</label>
						<div class="col-md-8">
							<input id="patFax-3" class="form-control input-sm" data-validation="required" name="patFax-3" type="text"><br>
						</div>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<label class="col-md-2 control-label" for="name">Case</label>
			<div class="col-md-10">
				<input id="patCase" class="form-control input-sm" data-validation="required" name="patCase" type="text"><br>
			</div>
			<label class="col-md-2 control-label" for="name">Remarks</label>
			<div class="col-md-10">
				<textarea id="patNote" class="form-control input-sm" data-validation="required" name="patNote" type="text"></textarea><br>
			</div>
			<!--label class="col-md-2 control-label" for="name">Contact No.</label>
			<div class="col-md-10">
			<input id="patContact" name="patContact" type="text" class="form-control input-sm" data-validation="required"><br>
			</div-->
			<h5 style="padding-right: 15px">
			<input type="button" value="Save">
			<!--button id="create-new" name="create-new">New Appointment</button>
			<button id="btn-cancel">Check Appointment</button--></h5>
		</div>
	</div>
</div>
<?php include_once('appt_form.php'); ?>
<?php include_once('appt_transfer.php'); ?>

<script src="../../../../assets/plugins/jquery.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/i18n/grid.locale-en.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/jquery.jqGrid.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/form-validator/jquery.form-validator.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery.dialogextend.js" type="text/ecmascript"></script>
<!-- JS Implementing Plugins -->
<script src="../../../../assets/plugins/fullcalendar-2.6.0/lib/moment.min.js"></script>
<script src="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.min.js"></script>
<!-- JS Customization -->
<script src="../../../../assets/js/doctor.js"></script>
<script src="../../../../assets/js/cmbautoselect.js"></script>
<script src="http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js"></script>
<!-- JS Page Level --><span class="auto-style1">
<script>
    jQuery(document).ready(function() 
    {
        Doctor.init_load();
        //Doctor.init_calendar();
        //Doctor.init_doctor();
        
			$( "#create-new" ).button().on( "click", function() {
                $("#txt_doc").html($("#cmb_doctor").val());
                $("#txt_appt").html($("#schDateTime").val());
                $("#txt_name").html($("#patName").val());
                $("#txt_ic").html($("#patIc").val());
                $("#txt_mrn").html($("#cmb_mrn").val());
                $("#txt_tel").html($("#patContact").val());
                $("#txt_hp").html($("#patHp").val());

				$( "#dialog1" ).dialog( "open" );
			});
	
			$( "#create-transfer" ).button().on( "click", function() {
				Doctor.init_save();
			});

			$( "#btn-cancel" ).button().on( "click", function() {
/*	            $("#patIc").val('');
	            $("#patName").val('');
	            //$("#patAddr").val('');
	            $("#patContact").val('');
	            $('#cmb_patient option[eq=0]').attr("selected", "selected");
				$("#btn-cancel").hide();
				$("#create-transfer").html('Save');
*/				
				$('.nav-tabs li:eq(0) a').tab('show'); 
			});

		$("#dialog1").dialog(
		{
		    autoOpen: false, 
		    modal: true,
		    width: 400,
		    height: 400,
		    open: function() {
		        $('.ui-widget-overlay').addClass('custom-overlay');
		    },
		    close: function() {
		        $('.ui-widget-overlay').removeClass('custom-overlay');
		    }            
		});
        
        
		$('#cmb_patient').change( function(){
            $("#patIc").val('');
            $("#patName").val('');
            //$("#patAddr").val('');
            $("#patContact").val('');
	        Doctor.init_patient($('#cmb_patient').val());	        	
	    });        


		$("#cmb_mrn").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_patient", {
		            term: request.term,
		            typ: 'mrn'
		        }, function(data) {
		            var array = data.error ? [] : $.map(data.patient, function(m) {
		                return {
		                    label: m.Name,
		                    value: m.MRN
		                };
		            });
		            response(array);
		        });
		    },
		    select: function (event, ui) {
		    	$("#cmb_mrn").val(ui.item.value);
		    	$("#patName").val(ui.item.label);
		    	Doctor.init_patient(ui.item.value);
		    	return false;
		    }
		});
	
		$("#patName").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_patient", {
		            term: request.term,
		            typ: 'Name'
		        }, function(data) {
		            var array = data.error ? [] : $.map(data.patient, function(m) {
		                return {
		                    label: m.Name,
		                    value: m.Newic
		                };
		            });
		            response(array);
		        });
		    },
		    select: function (event, ui) {
		    	$("#cmb_mrn").val(ui.item.value);
		    	$("#patName").val(ui.item.label);
		    	Doctor.init_patient(ui.item.value);
		    	return false;
		    }
		});

		$("#patIc").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_patient", {
		            term: request.term,
		            typ: 'Newic'
		        }, function(data) {
		            var array = data.error ? [] : $.map(data.patient, function(m) {
		                return {
		                    label: m.Newic,
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

		$("#patIc3").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_patient", {
		            term: request.term,
		            typ: 'Newic'
		        }, function(data) {
		            var array = data.error ? [] : $.map(data.patient, function(m) {
		                return {
		                    label: m.Newic,
		                    value: m.Newic
		                };
		            });
		            response(array);
		        });
		    },
		    select: function (event, ui) {
		    	//$("#cmb_mrn").val(ui.item.value);
		    	$("#patIc").val(ui.item.label);
		    	$("#patIc-3").val(ui.item.label);
		    	Doctor.init_patient_dtl(ui.item.value);
		    	return false;
		    }
		});

		$("#cmb_doctor").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_doctor", {
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
		    	Doctor.init_doctor(ui.item.value);
		    	$('#calendar').fullCalendar('changeView', 'month');
		    	
		    	return false;
		    }
		});

		$("#cmb_doctor-3").autocomplete({
			source: function(request, response) {
		        $.getJSON("/_research/webms/assets/php/entry_appt.php?action=get_all_doctor", {
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

	});


$(document).ready(function () {
            $(function() {
            $("#dialog-form").dialog({
                autoOpen: false,
                    maxWidth:600,
                    maxHeight: 500,
                    width: 600,
                    height: 500,
                    modal: true,
                    buttons: {
                    "Create": function() {
                    $(this).dialog("close");
                    },
                    Cancel: function() {
                    $(this).dialog("close");
                    }
                },
                    close: function() {
                }
                });

            $("#dialog-transfer").dialog({
                autoOpen: false,
                    maxWidth:600,
                    maxHeight: 500,
                    width: 600,
                    height: 500,
                    modal: true,
                    buttons: {
                    "Create": function() {
                    $(this).dialog("close");
                    },
                    Cancel: function() {
                    $(this).dialog("close");
                    }
                },
                    close: function() {
                }
                });
            });

            $("#create-appt")
            .button()
            .click(function() {
                if($('#cmb_doctor').val() == ''){
                	alert('please select doctor!');
                	return;
                }
                $("#dialog-form").dialog("open");
                $('#cmb_doctor_3').val($('#cmb_doctor').val());
                
            });

            $("#new-transfer")
            .button()
            .click(function() {
                $("#dialog-transfer").dialog("open");                
            });
        
        });
        
</script>
</span>

</body>

</html>
