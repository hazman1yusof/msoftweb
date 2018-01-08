<div id="dialog-transfer" class="ui-front" style="display: none" title="Transfer">
	<div class="row">
		<div class="col-sm-5">
		<h2>FROM</h2>
			<label class="col-md-2 control-label" for="name">Doctor:</label>
			<div class="col-md-10 selPat">
				<input id="docidFrom" name="docidFrom" type="hidden">
				<input id="cmbdoctorFrom" disabled class="form-control input-sm" name="qty" name="cmbdoctorFrom" placeholder="select doctor" type="text" value="" required />
			</div><br/>
			<label class="col-md-2 control-label" for="name"><br>Date/Time:</label>
			<div class="col-md-10 selPat">
				<br>
				<input id="schDateTimeFrom" class="form-control input-sm" name="schDateTimeFrom" readonly type="text" placeholder="Please select datetime from calendar" onclick="selCalendar()" required><br>
			</div>


		</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1">
		</div>
		<div class="col-sm-5">
		<h2>TO</h2>
			<label class="col-md-2 control-label" for="name">Doctor:</label>
			<div class="col-md-10 selPat">
				<input id="docidTo" name="docidTo" type="hidden">
				<input id="cmbdoctorTo" class="add_input form-control input-sm" name="qty" name="cmbdoctorTo" placeholder="select doctor" type="text" value="" required /><a class="add">
			<input class="form-control input-sm" type="button" value="..." onclick="$('#dialog-doctor-list').dialog('open');load_doctor_list_dialog();" />
			</a>
			</div><br/>
			<label class="col-md-2 control-label" for="name"><br>Date/Time:</label>
			<div class="col-md-10 selPat">
				<br>
				<input id="schDateTimeTo" class="form-control input-sm" name="schDateTimeTo" readonly type="text" placeholder="Please select datetime from calendar" onclick="selCalendar()" required><br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
<table id="sourcetable" style="display:none">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Url</th>
            <th>Country</th>
            <th>Item</th>                       
        </tr>
    </thead>
 
    <tbody>
        <tr id="sour0">
            <td>1</td>
            <td>Name 1</td>
            <td>url 1</td>
            <td>Country 1</td>
            <td>Item 1</td>
        </tr>
        <tr id="sour1">
            <td>2</td>
            <td>Name 2</td>
            <td>url 2</td>
            <td>Country 2</td>
            <td>Item 2</td>
        </tr>
        <tr id="sour2">
            <td>3</td>
            <td>Name 3</td>
            <td>url 3</td>
            <td>Country 3</td>
            <td>Item 3</td>
        </tr>
        <tr id="sour3">
            <td>4</td>
            <td>Name 4</td>
            <td>url 4</td>
            <td>Country 4</td>
            <td>Item 4</td>
        </tr>
        <tr id="sour4">
            <td>5</td>
            <td>Name 5</td>
            <td>url 5</td>
            <td>Country 5</td>
            <td>Item 5</td>
        </tr>     
    </tbody>
</table>


			<select id="sbOne" multiple="multiple" style="width:100%;height:300px">
    		</select> 
		</div>
		<div class="col-sm-1">
			<input id="left" class="form-control input-sm" type="button" value="&lt;" /><br>
			<input id="right" class="form-control input-sm" type="button" value="&gt;" /><br>
			<input id="leftall" class="form-control input-sm" type="button" value="&lt;&lt;" /><br>
			<input id="rightall" class="form-control input-sm" type="button" value="&gt;&gt;" /><br>
		</div>
		<div class="col-sm-5">
<table id="destinationtable" style="display:none">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Url</th>
            <th>Country</th>
            <th>Item</th>                       
        </tr>
    </thead>
</table>

			<select id="sbTwo" multiple="multiple" style="width:100%;height:300px">
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