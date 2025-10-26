<!DOCTYPE html>

<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css">
	<link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="plugins/bootgrid/css/jquery.bootgrid.css">
	<!-- <link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css"> -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css">
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="plugins/form-validator/theme-default.css" />

    <link rel="stylesheet" href="plugins/css/trirand/ui.jqgrid-bootstrap.css" />
	<link rel="stylesheet" href="css/landing.css?v=1">

	<style>
		#Dtext_chgcode_dfee {
			margin-left: 10px;
	    }
	</style>

</head>

<body class="header-fixed">
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" name="rng" id="rng">
	<input type="hidden" name="ismobile" id="ismobile" value="false">
	<input type="hidden" name="epistycode_label" id="epistycode_label" value="{{$epistycode_label')}}">

    <div class="wrapper">
    	<input type="hidden" id="load_from_addupd" data-info="false" data-oper="edit">
        <input name="epistycode" id="epistycode" type="hidden" value="{{request()->get('epistycode')}}">
        @if (request()->get('epistycode') == 'IP')
        <input name="epistycode2" id="epistycode2" type="hidden" value="DC">
		@else
        <input name="epistycode2" id="epistycode2" type="hidden" value="OTC">
		@endif
        <input name="curpat" id="curpat" type="hidden" value="{{request()->get('curpat')}}">
        <input name="lastrowid" id="lastrowid" type="hidden" value="0">
        <input name="userdeptcode" id="userdeptcode" type="hidden" value="{{$userdeptcode ?? ''}}">
        <input name="userdeptdesc" id="userdeptdesc" type="hidden" value="{{$userdeptdesc ?? ''}}">
		<input type="hidden" name="billtype_def_code" id="billtype_def_code" value="{{$billtype_def_code ?? ''}}">
		<input type="hidden" name="billtype_def_desc" id="billtype_def_desc" value="{{$billtype_def_desc ?? ''}}">
        <input name="cashier" id="cashier" type="hidden" value="{{$cashier ?? '0'}}">
        <input name="lastMrn" id="lastMrn" type="hidden" >
        <input name="lastidno" id="lastidno" type="hidden" >
        <input name="isdoctor" id="isdoctor" type="hidden" value="{{Auth::user()->doctor}}">
        <input name="phardept_dflt" id="phardept_dflt" type="hidden" value="{{$phardept_dflt ?? 'PHAR'}}">
        <input name="dispdept_dflt" id="dispdept_dflt" type="hidden" value="{{$userdeptcode ?? 'PHAR'}}">
        <input name="labdept_dflt" id="labdept_dflt" type="hidden" value="{{$labdept_dflt ?? 'LAB'}}">
        <input name="raddept_dflt" id="raddept_dflt" type="hidden" value="{{$raddept_dflt ?? 'RAD'}}">
        <input name="physdept_dflt" id="physdept_dflt" type="hidden" value="{{$physdept_dflt ?? 'PHY'}}">
        <input name="rehabdept_dflt" id="rehabdept_dflt" type="hidden" value="{{$rehabsdept_dflt ?? 'REHAB'}}">
        <input name="dfeedept_dflt" id="dfeedept_dflt" type="hidden" value="{{$dfeedept_dflt ?? 'PHAR'}}">
        <input name="dietdept_dflt" id="dietdept_dflt" type="hidden" value="{{$dietdept_dflt ?? 'DIET'}}">
        <input name="pkgdept_dflt" id="pkgdept_dflt" type="hidden" value="{{$pkgdept_dflt ?? 'PHAR'}}">
        <input name="othdept_dflt" id="othdept_dflt" type="hidden" value="{{$othdept_dflt ?? 'PHAR'}}">

        <div id="info"></div>

		<div class="panel" style="padding: 5px;">
			&nbsp;&nbsp;
			@if (request()->get('curpat') == 'false')
			<button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
			&nbsp;&nbsp;
			<button id="btn_mykad" type="button" class="btn btn-default btn-md" >
			<img src="img/mykad.png" width="35" /> My Kad</button>
			&nbsp;&nbsp;
			<button id="btn_biometric" type="button" class="btn btn-default btn-md" >
			<img src="img/biometric.png" width="22" /> Biometric </button>
			&nbsp;&nbsp;
			@endif
			@if (request()->get('curpat') == 'true')
			<button id="btn_patlabel" type="button" class="btn btn-default btn-md" >
			<img src="img/labelprinter.png" width="22" /> Pat Label </button>
			@endif
		</div>

		@if (request()->get('epistycode') == 'OP' && !Session::has('isdoctor') && request()->get('curpat') == 'true')
		<div class="panel panel-default" style="position: relative;margin: 0 12px 12px 12px">
	        <div class="panel-heading collapsed" id="toggle_preepis" data-toggle="collapse" data-target="#tabpreepis" style="padding: 20px 20px 25px 20px;">

	        <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px;bottom: 3px;"></i>
	        <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px;bottom: 3px;"></i >
	        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
	            <h5><strong>PRE EPISODE</strong></h5>
	        </div> 
	        </div>

	        <div id="tabpreepis" class="panel-collapse collapse">
	        <div class="panel-body form-horizontal">
	            <div id="jqGrid_preepis_c">
	                <div class='col-md-12' style="padding:0 0 15px 0">
	                    <table id="jqGrid_preepis" class="table table-striped"></table>
	                    <div id="jqGridPager_preepis"></div>
	                </div>
	            </div>
	        </div>
            </div>
        </div>
        @endif

		<div class="panel panel-primary" style="position: relative;margin: 0 12px 12px 12px">
			<div  class="panel-heading clearfix">
				<h5><strong>
					@if (request()->get('curpat') == 'true' && request()->get('epistycode') == 'IP'){{'CURRENT IN-PATIENT'}}
					@elseif (request()->get('curpat') == 'true' && request()->get('epistycode') == 'OP'){{'CURRENT OUT-PATIENT'}}
					@else {{'PATIENT LIST'}}
					@endif
				</strong></h5>
			</div>
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="idno" data-identifier="true" data-visible="false" data-width="0%"></th>
                	<th data-column-id="no" data-formatter="col_add" data-width="4%">#</th>
                    @if (request()->get('curpat') == 'true')
                    <!-- <th data-column-id="QueueNo" data-width="3%">Queue</th> -->
                    <th data-column-id="reg_date" data-width="7%">Reg Date</th>
					@endif
                    <th data-column-id="MRN" data-type="numeric" data-formatter="col_mrn" data-width="5%">MRN</th>
                    <th data-column-id="Episno" data-type="numeric" data-width="5%">Episode</th>
                    <th data-style="dropDownItem" data-column-id="Name" data-formatter="col_name" data-width="15%">Name</th>
                    @if (request()->get('curpat') == 'true')
                    <th data-column-id="payername" data-width="15%">Payer</th>
					@endif
                    <th data-column-id="q_doctorname" data-width="15%">Doctor</th>
                    @if (request()->get('epistycode') == 'IP')
                    <th data-column-id="ward" data-width="8%">Ward</th>
                    <th data-column-id="bednum" data-width="4%">Bed No.</th>
					@endif
                	<th data-column-id="pregnant" data-formatter="col_preg" data-width="5%"></th>
                    <th data-column-id="Newic" data-width="8%">New IC</th>
                    <th data-column-id="telhp" data-width="8%">H/P</th>
                    <th data-column-id="DOB" data-formatter="col_dob" data-width="6%">DOB</th>
                    <th data-column-id="Sex" data-width="2%">Sex</th>
                    <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="2%">Age</th>
					<th data-column-id="commands" data-formatter="commands" data-sortable="false" data-width="7%"> </th>
				</tr>
				</thead>

			</table>


		</div>
		
		@include('hisdb.pat_mgmt.mdl_patient')
		@include('hisdb.pat_mgmt.mdl_episode')
		@include('hisdb.pat_mgmt.itemselector')
		@include('hisdb.pat_mgmt.patlabel')
		
		@if (request()->get('curpat') == 'true')
			
			@if (request()->get('epistycode') == 'OP')
				@if (Auth::user()->doctor == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursing_row">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="antenatal_row">
						@include('hisdb.antenatal.antenatal')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.paediatric.paediatric')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingnote.nursingnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnoteref')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.doctornote.doctornote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.requestfor.requestfor')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.admhandover.admhandover')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
					
					<!-- <div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dietorder.dietorder')
					</div> -->
				@elseif (Auth::user()->nurse == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingnote.nursingnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnoteref')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.doctornote.doctornote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.requestfor.requestfor')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.admhandover.admhandover')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
					
					<!-- <div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dietorder.dietorder')
					</div> -->
				@endif
				
				@if (Auth::user()->billing == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.ordcom.ordcom',['phase' => '1'])
					</div>
				@endif
				
				<div class='row' style="position: relative;margin: 0 12px 12px 12px">
					@include('hisdb.endConsult.endConsult',['type' => "OP",'type_desc' => "Out Patient"])
				</div>
			@endif
			
			@if (request()->get('epistycode') == 'IP')
				@if (Auth::user()->doctor == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursingED_row">
						@include('hisdb.nursingED.nursingED')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursing_row">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingActionPlan.nursingActionPlan')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingnote.nursingnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="antenatal_row">
						@include('hisdb.antenatal.antenatal')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnoteref')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.doctornote.doctornote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.requestfor.requestfor')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dietorder.dietorder')
					</div>
				@elseif (Auth::user()->nurse == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursingED_row">
						@include('hisdb.nursingED.nursingED')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingActionPlan.nursingActionPlan')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursingnote.nursingnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.clientprogressnote.clientprogressnoteref')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.doctornote.doctornote')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.requestfor.requestfor')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dietorder.dietorder')
					</div>
				@endif
				
				@if (Auth::user()->billing == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.ordcom.ordcom',['phase' => '1'])
					</div>
				@endif
				
				<div class='row' style="position: relative;margin: 0 12px 12px 12px">
					@include('hisdb.discharge.discharge',['type' => "IP",'type_desc' => "In Patient"])
				</div>
			@endif
			
		@endif
		
	<script type="text/javascript">
	    let mql = window.matchMedia("(max-width: 768px)");
	    document.getElementById("ismobile").value = mql.matches;
	</script>
	
	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
	
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.errorbars.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.navigate.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.crosshair.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.symbol.js"></script>
    
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script type="text/javascript" src="js/myjs/utility.js"></script>
	
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>
	
	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/biodata.js?v=1.1"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/episode.js?v=1.4"></script>
	
	<input type="hidden" id="user_billing" value="{{Auth::user()->billing}}">
	<input type="hidden" id="user_nurse" value="{{Auth::user()->nurse}}">
	<input type="hidden" id="user_doctor" value="{{Auth::user()->doctor}}">

	<script>
		window.message_parent_wardbook = function(data) { // inside the iframe
		    console.log(data);
		    $('#ReqFor_bed').val(data.bednum);
		    $('#ReqFor_ward').val(data.ward);
		    $('#ReqFor_room').val(data.room);
		    $('#ReqFor_bedtype').val(data.bedtype);
		};
	</script>
	
	@if (request()->get('curpat') == 'true')
		
		<!-- <script type="text/javascript" src="js/hisdb/discharge/discharge.js"></script> -->
		
		@if (request()->get('epistycode') == 'OP')
			@if (Auth::user()->doctor == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js?v=1.2"></script>
				<script type="text/javascript" src="js/hisdb/antenatal/antenatal.js"></script>
				<script type="text/javascript" src="js/hisdb/paediatric/paediatric.js"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_intake.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote.js?v=2.2"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invFBC.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCoag.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invRP.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invLFT.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invElect.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invABGVBG.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invUFEME.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCE.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCS.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_glasgow.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_pivc.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_morsefallscale.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnote.js"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnoteref.js"></script>
				<script type="text/javascript" src="js/hisdb/transaction/transaction_doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_medc.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_bpgraph.js"></script>
				<script type="text/javascript" src="js/hisdb/requestfor/requestfor.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/admhandover/admhandover.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
				<!-- <script type="text/javascript" src="js/hisdb/dietorder/dietorder.js?v=2"></script> -->
			@elseif (Auth::user()->nurse == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js?v=1.2"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_intake.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote.js?v=2.2"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invFBC.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCoag.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invRP.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invLFT.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invElect.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invABGVBG.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invUFEME.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCE.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCS.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_glasgow.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_pivc.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_morsefallscale.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnote.js"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnoteref.js"></script>
				<script type="text/javascript" src="js/hisdb/transaction/transaction_doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_medc.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_bpgraph.js"></script>
				<script type="text/javascript" src="js/hisdb/requestfor/requestfor.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/admhandover/admhandover.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
				<!-- <script type="text/javascript" src="js/hisdb/dietorder/dietorder.js?v=2"></script> -->
			@endif
			
			@if (Auth::user()->billing == 1)
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_main.js"></script>
					<!-- <script type="text/javascript" src="js/hisdb/ordcom/ordcom_phar_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_disp_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_lab_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rad_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_dfee_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phys_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rehab_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_diet_doc.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_oth_doc.js"></script> -->
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phar.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_disp.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_lab.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rad.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_dfee.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phys.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rehab.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_diet.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_oth.js"></script>
					<script type="text/javascript" src="js/hisdb/ordcom/ordcom_pkg.js"></script>
			@endif
			
			<script type="text/javascript" src="js/hisdb/endConsult/endConsult.js"></script>
		@endif
		
		@if (request()->get('epistycode') == 'IP')
			<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_bed.js"></script>
			
			@if (Auth::user()->doctor == 1)
				<script type="text/javascript" src="js/hisdb/nursingED/nursingED.js?v=1.3"></script>
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js?v=1.2"></script>
				<!-- <script type="text/javascript" src="js/hisdb/wardpanel/wardpanel.js"></script> -->
				<script type="text/javascript" src="js/hisdb/nursingActionPlan/nursingActionPlan.js?v=1.4"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_intake.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote.js?v=2.2"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invFBC.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCoag.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invRP.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invLFT.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invElect.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invABGVBG.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invUFEME.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCE.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCS.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_glasgow.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_pivc.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_morsefallscale.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_thrombo.js?v=1"></script>
				<script type="text/javascript" src="js/hisdb/antenatal/antenatal.js"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnote.js"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnoteref.js"></script>
				<script type="text/javascript" src="js/hisdb/transaction/transaction_doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_medc.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_bpgraph.js"></script>
				<script type="text/javascript" src="js/hisdb/requestfor/requestfor.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
				<script type="text/javascript" src="js/hisdb/dietorder/dietorder.js?v=2"></script>
			@elseif (Auth::user()->nurse == 1)
				<script type="text/javascript" src="js/hisdb/nursingED/nursingED.js?v=1.3"></script>
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js?v=1.2"></script>
				<!-- <script type="text/javascript" src="js/hisdb/wardpanel/wardpanel.js"></script> -->
				<script type="text/javascript" src="js/hisdb/nursingActionPlan/nursingActionPlan.js?v=1.4"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_intake.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote.js?v=2.2"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invFBC.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCoag.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invRP.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invLFT.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invElect.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invABGVBG.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invUFEME.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCE.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_invCS.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_glasgow.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_pivc.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_morsefallscale.js?v=1.1"></script>
				<script type="text/javascript" src="js/hisdb/nursingnote/nursingnote_thrombo.js?v=1"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnote.js"></script>
				<script type="text/javascript" src="js/hisdb/clientprogressnote/clientprogressnoteref.js"></script>
				<script type="text/javascript" src="js/hisdb/transaction/transaction_doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_medc.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote_bpgraph.js"></script>
				<script type="text/javascript" src="js/hisdb/requestfor/requestfor.js?v=1.6"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
				<script type="text/javascript" src="js/hisdb/dietorder/dietorder.js?v=2"></script>
			@endif
			
			@if (Auth::user()->billing == 1)
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_main.js"></script>
				<!-- <script type="text/javascript" src="js/hisdb/ordcom/ordcom_phar_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_disp_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_lab_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rad_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_dfee_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phys_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rehab_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_diet_doc.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_oth_doc.js"></script> -->
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phar.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_disp.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_lab.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rad.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_dfee.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_phys.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_rehab.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_diet.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_oth.js"></script>
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom_pkg.js"></script>
			@endif
			
			<script type="text/javascript" src="js/hisdb/discharge/discharge.js"></script>
		@endif
		
	@endif
	
	<script type="text/javascript" src="js/hisdb/pat_mgmt/patlabel.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_doctor.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_nok.js?v=1.1"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_payer.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_coverage.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_deposit.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/pat_nok.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/pat_emr.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/textfield_modal.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/landing.js?v=1.5"></script>
	
	</div>

</body>
</html>