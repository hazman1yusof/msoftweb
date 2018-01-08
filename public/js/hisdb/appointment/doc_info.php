<div id="dialog-doctor-info" class="ui-front" style="display: none" title="Doctor Info">
	<div style="float: right; width: 100%; padding: 5px; text-align: right">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#tbreak">Time Break</a></li>
			<li><a data-toggle="tab" href="#lrecord">Leave Record</a></li>
		</ul>
		<div class="tab-content">
			<div id="tbreak" class="tab-pane fade in active" style="text-align: left;padding:10px">
				<div id="gridPagerTBreak">
				</div>
				<table id="gridTBreak">
				</table>
			</div>
			<div id="lrecord" class="tab-pane fade in active" style="text-align: left;padding:10px">
				<div id="gridPagerLRec">
				</div>
				<table id="gridLRec">
				</table>
			</div>
		</div>
	</div>
</div>
<div id="dialog-doctor-list" class="ui-front" style="display: none" title="List of Doctor">
	<div style="float: right; width: 100%; padding: 5px; text-align: right">
		<table id="grid-selection-doclist" class="table table-condensed table-hover table-striped">
		    <thead>
		        <tr>
		            <th data-column-id="id" data-identifier="true">Id</th>
		            <th data-column-id="description">Name</th>
		        </tr>
		    </thead>
		</table>
	</div>
</div>
<div id="dialog-casetype" class="ui-front" style="display: none" title="List Case Type">
	<div style="float: right; width: 100%; padding: 5px; text-align: right">
		<table id="grid-selection-casetype" class="table table-condensed table-hover table-striped">
		    <thead>
		        <tr>
		            <th data-column-id="case_code" data-identifier="true" data-type="numeric">ID</th>
		            <th data-column-id="description">Sender</th>
		        </tr>
		    </thead>
		</table>
	</div>
</div>
