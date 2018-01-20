
<style>
	#gridAllo_c input[type='text'][rowid]{
		height: 30%;
		padding: 4px 12px 4px 12px;
	}
	#alloText{width:9%;}#alloText{width:60%;}#alloCol{width: 30%;}
	#alloCol, #alloText{
		display: inline-block;
		height: 70%;
		padding: 4px 12px 4px 12px;
	}
	#alloSearch{
		border-style: solid;
		border-width: 0px 1px 1px 1px;
		padding-top: 5px;
		padding-bottom: 5px;
		border-radius: 0px 0px 5px 5px;
		background-color: #f8f8f8;
		border-color: #e7e7e7;
	}
</style>

<div id="allocateDialog" title="Create Allocation">
	<form id='formallo'>
		<div class='col-md-9'>
			<div class="col-md-6">
				<label class="control-label">Documnet Type</label>
				<input id="AlloDtype" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-6">
				<label class="control-label">Documnet No.</label>
				<input id="AlloDno" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-12">
				<label class="control-label">Debtor</label>
				<input id="AlloDebtor" type="text" class="form-control input-sm" readonly>
				<span class="help-block" id="AlloDebtor2"></span>
			</div>

			<div class="col-md-12">
				<label class="control-label">Payer</label>
				<input id="AlloPayer" type="text" class="form-control input-sm" readonly>
				<span class="help-block" id="AlloPayer2"></span>
			</div>

			<div class="col-md-6">
				<label class="control-label">Document Amount</label>
				<input id="AlloAmt" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-6">
				<label class="control-label">Document O/S</label>
				<input id="AlloOutamt" type="text" class="form-control input-sm" readonly>
			</div>
		</div>

		<div class='col-md-3'>
			
				<div class="col-md-12"><hr>
					<label class="control-label">Balance after allocate</label>
					<input id="AlloBalance" type="text" class="form-control input-sm" readonly>
				</div>

				<div class="col-md-12">
					<label class="control-label">Total allocate</label>
					<input id="AlloTotal" type="text" class="form-control input-sm" readonly><hr>
				</div>
		</div>
	</form>

	<div class='col-md-12' id='gridAllo_c' style="padding:0">
		<hr>
        <table id="gridAllo" class="table table-striped"></table>
        <div id="pagerAllo"></div>
    </div>

	<div class="col-md-10 col-md-offset-1" id="alloSearch">
		<label class="control-label" id='alloLabel'>Search</label>
		<input id="alloText" type="text" class="form-control input-sm">
		<select class="form-control" id="alloCol">
			<option value="invno" >invoice no</option>
			<option value="auditno" >auditno</option>
			<option value="mrn" >mrn</option>
			<option value="recptno" >docno</option>
			<option value="newic" >newic</option>
			<option value="staffid" >staffid</option>
			<option value="batchno" >batchno</option>
		</select>
	</div>
</div>