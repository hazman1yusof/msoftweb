<?php include_once('../../../../header.php'); ?>
<div class="row" style="border: 1px silver solid; padding: 10px 0px 10px 0px; margin: 5px 2px 20px 2px">
	<div class="col-sm-7">
		<input id="docid" name="docid" type="hidden">
		<input id="cmb_doctor" class="add_input form-control input-sm" name="qty" name="cmb_doctor" placeholder="select doctor" type="text" value="" /><a class="add">
		<input class="form-control input-sm" onclick="$('#dialog-doctor-info').dialog('open');reload_doctor_info();" type="button" value="i" />
		<input class="form-control input-sm" onclick="$('#dialog-doctor-list').dialog('open');load_doctor_list_dialog();" type="button" value="..." />
		</a></div>
	<div class="col-sm-5" style="text-align: right">
		<input id="create-appt" class="btn" type="button" value="New Appointment">&nbsp;
		<input id="new-transfer" class="btn" type="button" value="Transfer">&nbsp;
		<input class="btn" type="button" value="Search"> </div>
</div>
<!--div style="float: left">
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
	<option value="2015">2015</option>
	<option value="2016">2016</option>
	<option value="2017">2017</option>
	<option value="2018">2018</option>
	<option value="2019">2019</option>
	<option value="2020">2020</option>
	</select>
	<input class="btn sel_date" onclick="FcGoToDate()" style="width: 40px" type="button" value="Go">
</div-->
<div id="calendar">
</div>
<!--div class="row" style="border: 1px silver solid; padding: 10px 0px 10px 0px; margin: 5px 2px 20px 2px">
	<table style="font-size: 10px; margin: 10px">
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
	</table>
</div-->
<!--div id="divGrid" style="float: right; width: 100%; padding: 5px; text-align: right">
	<ul class="nav nav-tabs" style="display: none">
		<li class="active"><a data-toggle="tab" href="#search">Appoinment List</a></li>
		<li><a data-toggle="tab" href="#appt">Appointment Detail</a></li>
		<li><a data-toggle="tab" href="#apptnew">New Appointment</a></li>
	</ul>
	<div class="tab-content">
		<div id="search" class="tab-pane fade in active" style="text-align: left">
			<div id="gridDialogPager">
			</div>
			<table id="gridDialog" class="table table-striped" style="width: 100%">
			</table>
		</div>
	</div>
</div-->

<?php include_once('../../../../implementingjs.php'); ?>

<!-- JS Page Level -->
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.css" rel="stylesheet" />
<script src="../../../../assets/plugins/fullcalendar-2.6.0/lib/moment.min.js"></script>
<script src="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.min.js"></script>

<script src="doctor.2.js"></script>
<?php include_once('../../../../footer.php'); ?>