<div class="row">
	<div class="col-md-6 col-md-offset-3" style="padding: 10px 0px;">
		<div class="btn-group" role="group" aria-label="..." style="float:right;">
		  <button type="button" class="btn btn-default" id="btn_epno_mclt">MC List</button>
		  <button type="button" class="btn btn-default" id="btn_epno_gomc">MC</button>
		  <button type="button" class="btn btn-default" id="btn_epno_save">Save</button>
		  <button type="button" class="btn btn-default" id="btn_epno_canl">Cancel</button>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3" style="padding: 20px;
	    background: #e5f9ff;
	    border: 0.1em solid #d6d6ff;
	    border-radius: 4px;">

		<form id="form_medc" style="padding: 10px" autocomplete="off">
			
				<p style="padding-left: 50px;">Serial No: <input type="text" name="serialno" readonly class="form-control" style="width: 50px !important;"></p>
				<p>I hereby certify that i have examined</p>
				<p>Mr/Miss/Mrs : <input type="text" name="name" readonly class="form-control"></p>
				<p style="padding-left: 50px;">From : <input type="text" name="patfrom" class="form-control"></p>
				<p>And find that he/she will be unfit for duty for <input type="text" name="mccnt" class="form-control" style="width: 50px !important;"> days</p>
				<p style="padding-left: 50px;">day from <input type="date" name="datefrom" class="form-control"> to <input type="date" name="dateto" class="form-control"></p>
				<p>Boleh bertugas semula pada / Can resume his/her duty on <input type="date" name="dateresume" class="form-control"></p>
				<p>Dikehendaki datang semula pada /</p>
				<p>Is required to come for re-examination on <input type="date" name="datereexam" class="form-control"></p>
			
		</form>

		<div id="mclist_medc" style="display:none">
	    	<table id="mclist_table">
	    		<thead>
	    			<tr>
	    				<td>Id</td>
	    				<td>Date from</td>
	    				<td>Date to</td>
	    				<td>MRN</td>
	    				<td>Episode</td>
	    				<td>Added By</td>
	    				<td>Added Date</td>
	    			</tr>
	    		</thead>
	    		<tbody>
	    		</tbody>
	    	</table>
	    </div>

    </div>
</div>