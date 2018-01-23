<div id="tilldet" title="Select Till">
	<form class='form-horizontal' style='width:99%' id='formTillDet' autocomplete="off">
		<div class="form-group">
			<label class="col-md-2 control-label" for="dept">Cashier</label>  
			<div class="col-md-10">
				<input id="cashier" name="cashier" type="text" class="form-control input-sm" readonly="readonly" value="{{Session::get('username')}}">
			</div>
		</div>	
		
		<div class="form-group">
			<label class="col-md-2 control-label" for="effectdate">Till</label>  
			<div class="col-md-10">
			  <div class='input-group'>
				<input id="tilldetTillcode" name="tilldetTillcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
				<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			  </div>
			  <span class="help-block"></span>
			</div>
		</div>
	</form>
</div>