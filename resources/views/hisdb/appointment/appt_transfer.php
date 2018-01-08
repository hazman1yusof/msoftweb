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

			<select id="sbOne" multiple="multiple" style="width:100%;height:300px;display:none">
    		</select> 
    		
            <div id="all_users" style="width:100%;height:300px;"></div>

		</div>
		
		<div class="col-sm-1">
           	<a href="javascript:void(0);" id="move_right">Right &raquo;</a><br /><br />
            <a href="javascript:void(0);" id="move_left">&laquo; Left</a><br><br><br>

			<!--input id="left" class="form-control input-sm" type="button" value="&lt;" /><br>
			<input id="right" class="form-control input-sm" type="button" value="&gt;" /><br>
			<input id="leftall" class="form-control input-sm" type="button" value="&lt;&lt;" /><br>
			<input id="rightall" class="form-control input-sm" type="button" value="&gt;&gt;" /><br-->
		</div>
		
		<div class="col-sm-5">
		
			<div id="selected_users"style="width:100%;height:300px;"></div>

			<select id="sbTwo" multiple="multiple" style="width:100%;height:300px;display:none">
			</select> 
			
		</div>
		<input type="button" value="View Selected users Id" id="view" class="form_button"/>
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

function call_transfer(){
		// Uncheck each checkbox on body load
		$('#all_users .selectit').each(function() {this.checked = false;});
		$('#selected_users .selectit').each(function() {this.checked = false;});
		
    	$('#all_users .selectit').click(function() {
			var userid = $(this).val();
			$('#user' + userid).toggleClass('innertxt_bg');
		});
		
		$('#selected_users .selectit').click(function() {
			var userid = $(this).val();
			$('#user' + userid).toggleClass('innertxt_bg');
		});
		
		$("#move_right").click(function() {
			var users = $('#selected_users .innertxt2').size();
			var selected_users = $('#all_users .innertxt_bg').size();

			//if (users + selected_users > 5) {
			//	alert('You can only chose maximum 5 users.');
			//	return;
			//}

			$('#all_users .innertxt_bg').each(function() {
				var user_id = $(this).attr('userid');
				$('#select' + user_id).each(function() {alert(11);this.checked = false;});

				var user_clone = $(this).clone(true);
				$(user_clone).removeClass('innertxt');
				//$(user_clone).removeClass('innertxt_bg');
				$(user_clone).addClass('innertxt2');

				$('#selected_users').append(user_clone);
				$(this).remove();
			});
		});
		
		$("#move_left").click(function() {
			$('#selected_users .innertxt_bg').each(function() {
				var user_id = $(this).attr('userid');
				$('#select' + user_id).each(function() {alert(22);this.checked = false;});
				
				var user_clone = $(this).clone(true);
				$(user_clone).removeClass('innertxt2');
				//$(user_clone).removeClass('innertxt_bg');
				$(user_clone).addClass('innertxt');
				
				$('#all_users').append(user_clone);
				$(this).remove();
			});
		});
		
		$('#view').click(function() {
			var users = '';
			$('#selected_users .innertxt2').each(function() {
				var user_id = $(this).attr('userid');
				if (users == '') 
					users += user_id;
				else
					users += ',' + user_id;
			});
			alert(users);
		});

}


</script>