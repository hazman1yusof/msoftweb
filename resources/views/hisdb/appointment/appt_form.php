<div id="dialog-form" style="display: " title="Appointment Detail" class="ui-front">
<form id="frmAppt" onsubmit="return checkform(this)">
	<input type="hidden" id="formstatus" name="formstatus" value="open">
<div class="col-md-6">
	<div id="apptnew" class="tab-pane fade in">
			<label class="col-md-2 control-label" for="name">Doctor: </label>
			<div class="col-md-10 selPat" style="margin-bottom:20px">
				<input id="cmb_doctor_3" disabled class="add_input form-control input-sm" name="cmb_doctor_3" placeholder="select doctor" type="text" value="" required /><a class="add">
				<input class="form-control input-sm" onclick="$('#dialog-doctor-info').dialog('open');load_doctor_info_leave();load_doctor_info_break();" type="button" value="i" />
				</a>
			</div>
			<label class="col-md-2 control-label" for="name">Date/Time:
			</label>
			<div class="col-md-10 selPat">
				<input type="text" class="form-control input-sm" id="schDateTime" style="width:200px">
				<input type="text" class="form-control input-sm" id="schTime" style="width:100px">
				<!--input id="schDateTime" class="form-control input-sm" name="schDateTime" readonly type="text" placeholder="Please select datetime from calendar" onclick="selCalendar()" required--><br>
			</div>
			<label class="col-md-2 control-label" for="status">Status</label>
			<div class="col-md-10">
				<select id="patStatus" class="form-control" name="patStatus">
				<option value="Open">Open</option>
				<option value="Attend">Attend</option>
				<option value="Cancelled">Cancelled</option>
				</select><br></div>
			<label class="col-md-2 control-label" for="name">Case:</label>
			<div class="col-md-10" style="margin-bottom:20px">
				<input id="patCase" class="add_input form-control input-sm" name="patCase" type="text"><a class="add">
			<input class="form-control input-sm" type="button" value="..."  onclick="$('#dialog-casetype').dialog('open');reload_casetype();"/>
			</a><br>
			</div>
			<label class="col-md-2 control-label" for="name">Remarks:</label>
			<div class="col-md-10">
				<textarea id="patNote" class="form-control input-sm" name="patNote" type="text"></textarea><br>
			</div>
			
			<!--label class="col-md-2 control-label" for="name">Last Update:</label>
			<div class="col-md-10">
				<input id="patLastUpdate" class="form-control input-sm" name="patLastUpdate" type="text" ><br>
			</div>
			<label class="col-md-2 control-label" for="name">Update By:</label>
			<div class="col-md-10">
				<input id="patUpdateBy" class="form-control input-sm" name="patUpdateBy" type="text" ><br>
			</div-->
		</div>
</div>
<div class="col-md-6">
	<label class="col-md-2 control-label" for="name">MRN</label>
	<div class="col-md-10 selPat">
		<input id="cmb_mrn" class="form-control" name="cmb_mrn" type="text"><br>
	</div>
	<label class="col-md-2 control-label" for="name">IC No.</label>
	<div class="col-md-10">
		<input id="patIc" class="form-control input-sm" name="patIc" type="text"><br>
	</div>
	<label class="col-md-2 control-label" for="name">Name</label>
	<div class="col-md-10">
		<input id="patName" class="form-control input-sm" name="patName" type="text" required><br>
	</div>
	
	<label class="col-md-2 control-label" for="name">Telephone</label>
	<div class="col-md-10">
		<input id="patContact" class="form-control input-sm" name="patContact" type="text" required><br>
	</div>
	<label class="col-md-2 control-label" for="name">Handphone</label>
	<div class="col-md-10">
		<input id="patHp" class="form-control input-sm" name="patHp" type="text" required><br>
	</div>
	<label class="col-md-2 control-label" for="name">Tel</label>
	<div class="col-md-10">
		<input id="patFax" class="form-control input-sm" name="patFax" type="text" required><br>
	</div>
</div>
</form>	
</div>

<script type="text/javascript">
function checkform() {
	var form = $('#frmAppt');

    // get all the inputs within the submitted form
    var inputs = form[0].getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        // only validate the inputs that have the required attribute
        if(inputs[i].hasAttribute("required")){
            if(inputs[i].value == ""){
                // found an empty field that is required
                alert("Please fill all required fields");
                return false;
            }
        }
    }
    
    //return true;
    add_appointment_info();
}


</script>