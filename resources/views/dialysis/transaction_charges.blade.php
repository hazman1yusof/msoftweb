<div class="eight wide tablet eleven wide computer column" style="margin:0px;">
	<div class="segment">
		<div class="ui inverted dimmer" id="loader_transaction">
		   <div class="ui large text loader">Loading</div>
		</div>
		<div class="ui small form" style="padding-top: 10px;">
		    <div class="inline field">
		     	<label>Medication Type</label>
				<select class="ui small dropdown" id="medicationtype">
				  	<option value="EPO">EPO</option>
                  	<option value="MICERRA">MICERRA</option>
				</select>
		    	<button type="button" id="medicationtype_button" class="ui primary button">Change type</button>
		    </div>
		</div>
	    <div class="ui teal segment" id="jqGrid_trans_c">
	        <h2 class="h2">Item List</h5>
		    <table id="jqGrid_trans" class="table table-striped"></table>
		    <div id="jqGrid_transPager"></div>
		</div>
	</div>
</div>