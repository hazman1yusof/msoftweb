<?php include_once('../../../../header.php'); ?>

	 
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#doctor">Resources</a></li>
		<li><a data-toggle="tab" href="#holidays">Public Holidays</a></li>
	</ul>
	<div class="tab-content">
		<div id="doctor" class="tab-pane fade in active">
			<h3>Resources</h3>
			<div class='col-md-12' style="border: lime 0px solid">
				<table id="doc_grid" class="table table-striped"></table>
				<div id="doc_grid_pager"></div>
			</div>
		</div>
		<div id="holidays" class="tab-pane fade in">
			<h3>Define public holidays</h3>
			<div class='col-md-12' style="border: pink 1px dotted">
				<select id="cmb_ph_year"></select>
				<br /><br />
				<table id="ph_grid" class="table table-striped"></table>
				<div id="ph_grid_pager"></div>
			</div>
		</div>
	</div>

	<div id="session_dialog" title="Set Doctor\'s Session Time">
		<form class='form-horizontal' style='width:99%' id='session_dialog_form'>
			<div class="form-group">
				  <label class="col-md-2 control-label" for="mon_from">Monday</label>  
				  <div class="col-md-10">
				  	<input id="mon_from" name="mon_from" type="text" maxlength="5" class="form-control input-sm" style="width: 60px" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="mon_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="mon_to" name="mon_to" type="text" maxlength="5" class="form-control input-sm" style="width: 60px" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="tue_from">Tuesday</label>  
				  <div class="col-md-10">
				  	<input id="tue_from" name="tue_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="tue_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="tue_to" name="tue_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="wed_from">Wednesday</label>  
				  <div class="col-md-10">
				  	<input id="wed_from" name="wed_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="wed_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="wed_to" name="wed_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="thu_from">Thursday</label>  
				  <div class="col-md-10">
				  	<input id="thu_from" name="thu_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="thu_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="thu_to" name="thu_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="fri_from">Friday</label>  
				  <div class="col-md-10">
				  	<input id="fri_from" name="fri_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="fri_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="fri_to" name="fri_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="sat_from">Saturday</label>  
				  <div class="col-md-10">
				  	<input id="sat_from" name="sat_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="sat_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="sat_to" name="sat_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="sun_from">Sunday</label>  
				  <div class="col-md-10">
				  	<input id="sun_from" name="sun_from" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  	<!--label class="col-md-2 control-label" for="sun_to">&nbsp;to&nbsp;</label-->
				  	&nbsp;to&nbsp;
				  	<input id="sun_to" name="sun_to" type="text" maxlength="5" class="form-control input-sm" data-validation="required">
				  </div>
            </div>
		</form>
	</div>

	

<?php include_once('../../../../implementingjs.php'); ?>	

    <!-- JS Page Level -->
	<script src="setup.js"></script>
       
<?php include_once('../../../../footer.php'); ?>