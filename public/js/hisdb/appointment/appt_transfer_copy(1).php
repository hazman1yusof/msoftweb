<div id="dialog-transfer" class="ui-front" style="display: none" title="Transfer">
	<div class="row">
		<div class="col-sm-5">
			<label class="col-md-2 control-label" for="name">Doctor:</label>
			<div class="col-md-10 selPat">
				<input id="docidFrom" name="docidFrom" type="hidden">
				<input id="cmbdoctorFrom" disabled class="form-control input-sm" name="qty" name="cmbdoctorFrom" placeholder="select doctor" type="text" value="" required />
			</div><br/>
			<label class="col-md-2 control-label" for="name">Date/Time:</label>
			<div class="col-md-10 selPat">
				<input id="schDateTimeFrom" class="form-control input-sm" name="schDateTimeFrom" readonly type="text" placeholder="Please select datetime from calendar" onclick="selCalendar()" required><br>
			</div>


		</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1">
		</div>
		<div class="col-sm-5">
			<label class="col-md-2 control-label" for="name">Doctor:</label>
			<div class="col-md-10 selPat">
				<input id="docidTo" name="docidTo" type="hidden">
				<input id="cmbdoctorTo" class="add_input form-control input-sm" name="qty" name="cmbdoctorTo" placeholder="select doctor" type="text" value="" required /><a class="add">
			<input class="form-control input-sm" type="button" value="..." />
			</a>
			</div><br/>
			<label class="col-md-2 control-label" for="name">Date/Time:</label>
			<div class="col-md-10 selPat">
				<input id="schDateTimeTo" class="form-control input-sm" name="schDateTimeTo" readonly type="text" placeholder="Please select datetime from calendar" onclick="selCalendar()" required><br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<select id="sbOne" multiple="multiple" style="width:100%;height:300px">
			    <!--option value="1">Pacific/Auckland +12:00 </option>
			    <option value="2">Australia/Brisbane +10:00 </option>
			     <option value="3">Aust +10:00 </option>
			    <option value="3">A +10:00 </option-->
    		</select> 
		</div>
		<div class="col-sm-1">
			<input id="left" class="form-control input-sm" type="button" value="&lt;" /><br>
			<input id="right" class="form-control input-sm" type="button" value="&gt;" /><br>
			<input id="leftall" class="form-control input-sm" type="button" value="&lt;&lt;" /><br>
			<input id="rightall" class="form-control input-sm" type="button" value="&gt;&gt;" /><br>
		</div>
		<div class="col-sm-5">
			<select id="sbTwo" multiple="multiple" style="width:100%;height:300px">
			<!--option value="6" disabled="disabled">Zeta</option>
			<option value="7">Eta</option-->
			</select> 
		</div>
	</div>
</div>
<script type="text/javascript">
function GetAllId(){
	$("#sbTwo option").each(function()
	{
	    console.log($(this).val());
	    // Add $(this).val() to your list
	});
}
</script>