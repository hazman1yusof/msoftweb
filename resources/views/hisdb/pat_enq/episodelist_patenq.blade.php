
<div class="panel panel-default" style="position: relative;">
	<div class="panel-heading clearfix collapsed position" id="toggle_episodelist" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_episodelist"></span></b><br>
		MRN: <span id="mrn_show_episodelist"></span>
        SEX: <span id="sex_show_episodelist"></span>
        DOB: <span id="dob_show_episodelist"></span>
        AGE: <span id="age_show_episodelist"></span>
        RACE: <span id="race_show_episodelist"></span>
        RELIGION: <span id="religion_show_episodelist"></span><br>
        OCCUPATION: <span id="occupation_show_episodelist"></span>
        CITIZENSHIP: <span id="citizenship_show_episodelist"></span>
        AREA: <span id="area_show_episodelist"></span> 

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#episodelist_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 60px; top: 15px;">
			<h5><b>Episode List</b></h5>
		</div>
	</div>
	<div id="episodelist_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" style="overflow-y: auto;">
			<div class='col-md-12' style="padding:0 0 15px 0" id="jqGrid_episodelist_c">
                <table id="jqGrid_episodelist" class="table table-striped"></table>
                <div id="jqGrid_episodelistPager"></div>
			</div>
		</div>
	</div>	
</div>