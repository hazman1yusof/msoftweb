<?php 
	include_once('../../../../assets/php/sschecker.php'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
<link href="../../../../assets/plugins/form-validator/theme-default.css" rel="stylesheet" />
<link href="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
<link href="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui-timepicker-addon.css" rel="stylesheet">
<link href="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery.bootgrid.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/css/trirand/ui.jqgrid-bootstrap.css" rel="stylesheet" />
<link href="../../../../assets/plugins/searchCSS/stylesSearch.css" rel="stylesheet">
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.css" rel="stylesheet" />
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.print.css" media="print" rel="stylesheet" />
<!--link href="../../../../assets/plugins/bootstrap-datepicker-1.5.1-dist/css/bootstrap-datepicker.min.css" rel="stylesheet" /-->
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
.add_input {
	width: 80%;
	display: inline-block;
	vertical-align: bottom;
}
.add input {
	/*background:url(add.png);*/
    display: inline-block;
	width: 30px; /*border-radius: 0px 5px 5px 0px;*/;
	font-weight: 700;
}
.sel_date {
	width: 150px;
	display: inline-block;
}
.custSideTip {
	position: fixed !important;
	right: 0 !important;
	max-width: 200px !important;
	background-color: white;
}
.ui-datepicker
	{ width: 17em; padding: .2em .2em 0; z-index:99999; }
	
.fc-time-grid .fc-slats td {
    height: 4.5em;
}
.fc-more-cell,
.fc-more {
	font-size:0em; !important;
}
.fc-event{
	color:black;
}
.fc-content{
	border:black 1px solid;
}

span.fc-title {
    padding: 5px !important;
    text-overflow: ellipsis;
    white-space: pre-line;
}

</style>
<link href="../../../../assets/css/test.css" rel="stylesheet" />

<meta charset="utf-8" />
<title>Appointment - Doctor</title>
</head>

<body>
<div style="width: 100%; padding: 5px">
	<div class="row" style="border: 1px silver solid; padding: 5px 0px 5px 0px; margin: 5px 2px 5px 2px">
		<div class="col-sm-7">
			<input id="docid" name="docid" type="hidden">
			<input id="cmb_doctor" class="add_input form-control input-sm" name="cmb_doctor" name="qty" placeholder="select doctor" type="text" value="" /><a class="add">
			<input class="form-control input-sm" onclick="$('#dialog-doctor-info').dialog('open');reload_doctor_info();" type="button" value="i" />
			<input class="form-control input-sm" onclick="$('#dialog-doctor-list').dialog('open');load_doctor_list_dialog();" type="button" value="..." />
			</a>
			<!--input id="cmb_doctor" class="form-control input-sm" name="cmb_doctor" placeholder="select doctor" type="text"><br-->
		</div>
		<div class="col-sm-5" style="text-align: right">
			<input id="create-appt" class="btn" type="button" value="New Appointment">&nbsp;
			<input id="new-transfer" class="btn" type="button" value="Transfer">&nbsp;
			<input class="btn" type="button" value="Search"> </div>
	</div>
	<div style="float: left">
		<select id="cmb_month" class="sel_date form-control input-sm" style="width: 100px">
		<option value="0">January</option>
		<option value="1">February</option>
		<option value="2">March</option>
		<option value="3">April</option>
		<option value="4">May</option>
		<option value="5">June</option>
		<option value="6">July</option>
		<option value="7">August</option>
		<option value="8">September</option>
		<option value="9">October</option>
		<option value="10">November</option>
		<option value="11">December</option>
		</select>
		<select id="cmb_year" class="sel_date form-control input-sm" style="width: 70px">
		<option value="2013">2013</option>
		<option value="2014">2014</option>
		<option value="2015">2015</option>
		<option value="2016">2016</option>
		<option value="2017">2017</option>
		<option value="2018">2018</option>
		<option value="2019">2019</option>
		<option value="2020">2020</option>
		</select>
		<input class="btn sel_date" onclick="FcGoToDate()" style="width: 40px" type="button" value="Go">
	</div>
	<div id="calendar">
	</div>
</div>

<!--table style="font-size: 10px; margin: 10px">
	<tr style="text-align: center;">
		<td rowspan="2" style="vertical-align: top; font-weight: bold">Legend:</td>
		<td style="width: 50px">
		<img alt="Open" src="../../../../assets/img/icon/i-open.jpg" width="20px" /></td>
		<td style="width: 50px">
		<img alt="Open" src="../../../../assets/img/icon/i-attend.jpg" width="20px" /></td>
		<td style="width: 50px">
		<img alt="Open" src="../../../../assets/img/icon/i-xattend.jpg" width="20px" /></td>
		<td style="width: 50px">
		<img alt="Open" src="../../../../assets/img/icon/i-cancel.jpg" width="20px" /></td>
	</tr>
	<tr style="text-align: center">
		<td>Open</td>
		<td>Attend</td>
		<td>Not Attend</td>
		<td>Cancel</td>
	</tr>
</table-->
<div id="divGrid" style="float: right; width: 100%; padding: 5px; text-align: right">
	<ul class="nav nav-tabs" style="display: none">
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
	</div>
</div>
<?php 
	include_once('appt_form.php'); 
	include_once('appt_transfer.php');
	include_once('doc_info.php');
?>
<script src="../../../../assets/plugins/jquery.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/i18n/grid.locale-en.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/jquery.jqGrid.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui-timepicker-addon.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery.bootgrid.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/form-validator/jquery.form-validator.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery.dialogextend.js" type="text/ecmascript"></script>
<!-- JS Implementing Plugins -->
<script src="../../../../assets/plugins/fullcalendar-2.6.0/lib/moment.min.js"></script>
<script src="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.min.js"></script>
<!-- JS Customization -->
<script src="../../../../assets/js/doctor.js"></script>
<script src="../../../../assets/js/cmbautoselect.js"></script>
<script src="http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js"></script>
<script src="../../../../assets/js/func_grid.js"></script>
<!--script src="../../../../assets/plugins/bootstrap-datepicker-1.5.1-dist/js/bootstrap-datepicker.min.js"></script-->
<!-- JS Page Level -->



</body>

</html>
