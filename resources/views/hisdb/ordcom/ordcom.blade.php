<div class="panel panel-default" style="position: relative;" id="jqGrid_ordcom_c">

<div class="panel-heading clearfix collapsed position" id="toggle_ordcom" style="position: sticky;top: 0px;z-index: 3;">
		<b>Name: <span id="name_show_ordcom"></span></b><br>
		MRN: <span id="mrn_show_ordcom"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 110px; top: 15px;">
			<h5>Order Communication Detail</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_ordcom"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 15px;" 

		>
		<button type="button" class="btn btn-default" id="new_ordcom">
			<span class="fa fa-plus-square-o"></span> Order
		</button>
	</div>

	<div id="jqGrid_ordcom_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<input id="ordcom_deptcode_hide" type="hidden" value="{{Auth::user()->deptcode}}">
				<table id="jqGrid_ordcom" class="table table-striped"></table>
				<div id="jqGridPager_ordcom"></div>

			</div>
		</div>
	</div>	
</div>