<div class="panel panel-default" style="position: relative;margin-bottom: 0px;">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_ordcom"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 25px;" 

	>
		<!-- <button type="button" class="btn btn-default" id="new_ordcom">
			<span class="fa fa-plus-square-o"></span> Order
		</button> -->
	</div>
	<div class="panel-heading clearfix collapsed position" id="toggle_ordcom" style="height: 65px;" @if($phase != '2') data-toggle="collapse" data-target="#jqGrid_ordcom_panel" @endif>
		<span id="ordcom_panel_title" style="display: none;">
			<b>NAME: <span id="name_show_ordcom"></span></b><br>
			MRN: <span id="mrn_show_ordcom"></span>
			BILL TYPE: <span id="billtype_show_ordcom"></span>
			SEX: <span id="sex_show_ordcom"></span>
			DOB: <span id="dob_show_ordcom"></span>
			AGE: <span id="age_show_ordcom"></span>
			RACE: <span id="race_show_ordcom"></span>
			RELIGION: <span id="religion_show_ordcom"></span><br>
			OCCUPATION: <span id="occupation_show_ordcom"></span>
			CITIZENSHIP: <span id="citizenship_show_ordcom"></span>
			AREA: <span id="area_show_ordcom"></span>
		</span>
		
		@if($phase == '2')
			<input type="hidden" id="ordcom_phase" value="2">
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel" ></i >
		@else
			<input type="hidden" id="ordcom_phase" value="0">
			<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
			<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		@endif
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 100px; top: 5px;">
			<h5 style="margin-bottom: 5px;">Order Entry</h5>
			<span><b>Total Amount : </b></span>
			<span id="cyclebill_totmat"></span>
			<br><span id="cyclebill_invno"></span>
		</div>				
	</div>
	<div id="jqGrid_ordcom_panel" class="panel-collapse collapse">
        <iframe style="display:block; border:none; height:95vh; width:95vw;" id="ordcom_iframe_MR"></iframe>
	</div>	
</div>