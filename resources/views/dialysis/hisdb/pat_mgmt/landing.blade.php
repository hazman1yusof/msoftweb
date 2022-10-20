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

	<style type="text/css" class="init">
		.preloader {
            width: 100%;
            height: 100%;
            top: 0;
            position: fixed;
            z-index: 99999;
            background: #fff;
        }
        .cssload-speeding-wheel {
            position: absolute;
            top: calc(50% - 3.5px);
            left: calc(50% - 3.5px);
            width: 31px;
            height: 31px;
            margin: 0 auto;
            border: 2px solid rgba(97,100,193,0.98);
            border-radius: 50%;
            border-left-color: transparent;
            border-right-color: transparent;
            animation: cssload-spin 425ms infinite linear;
            -o-animation: cssload-spin 425ms infinite linear;
            -ms-animation: cssload-spin 425ms infinite linear;
            -webkit-animation: cssload-spin 425ms infinite linear;
            -moz-animation: cssload-spin 425ms infinite linear;
        }
        @keyframes cssload-spin {
          100%{ transform: rotate(360deg); transform: rotate(360deg); }
        }

        @-o-keyframes cssload-spin {
          100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
        }

        @-ms-keyframes cssload-spin {
          100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
        }

        @-webkit-keyframes cssload-spin {
          100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
        }

        @-moz-keyframes cssload-spin {
          100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
        }

		#mdl_accomodation,#mdl_reference,#mdl_bill_type,#mdl_epis_pay_mode,#bs-guarantor,#mdl_new_gl{
			display: none; z-index: 120;background-color: rgba(0, 0, 0, 0.3);
		}
		.smallmodal{
			width: 40% !important; margin: auto !important;margin-top:10% !important;
		}
		.smallmodal > .modal-content{
			border: 3px solid darkblue !important;min-height: fit-content !important;
		}
		tr.dtrg-group{
			font-size: 15px;
		}
		.uppercase, .odd, .even{
		  	text-transform:uppercase
		}

		small, input[type=text]{
		  	text-transform:uppercase
		}

		.td_nowhitespace{
			white-space: normal !important;
		}

		.modal {
		  padding: 0 !important;
		}

		.modal .modal-dialog {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
		}

		.modal .modal-dialog .half {
			width: 50% !important;
			height: 50% !important;
			margin: 0;
			padding: 0;
		}

		.modal .modal-content {
			height: auto;
			min-height: 100%;
			border: 0 none;
			border-radius: 0;
			box-shadow: none;
		}

		.modal-header {
			min-height: 16.42857143px;
			padding: 5px;
			border-bottom: 1px solid #e5e5e5;
		}

		.modal-body {
			position: relative;
			/*padding: 10px;*/
		}

		.modal-backdrop{
			z-index: 99 !important;
			background-color: #fff !important;
			opacity: 1 !important;
		}

		.form-group{
			margin-bottom: 5px;
		}
		
		.form-mandatory{
			background-color: lightyellow;
		}
		
		.form-disabled{
			background-color: #DDD;
			color: #999;
		}

		.justbc{
			background-color: #dff0d8 !important;
		}
		label.error{
			color: rgb(169, 68, 66);
		}
		#mykad_reponse{
			color: rgb(169, 68, 66);
			font-weight: bolder;
		}
		.addressinp{
			margin-bottom: 5px;
		}
		.panel-heading.collapsed .fa-angle-double-up,
		.panel-heading .fa-angle-double-down {
			display: none;
		}

		.panel-heading.collapsed .fa-angle-double-down,
		.panel-heading .fa-angle-double-up {
			display: inline-block;
		}

		i.fa {
			/*cursor: pointer;*/
			float: right;
			/*position: absolute;
		    right: 12px;
		    top: 23px;*/
		}

		div.wrapper > div.row > div.panel.panel-default > div.panel-heading.clearfix > i.fa {
			cursor: pointer;
			/*float: right;*/
			position: absolute;
		    right: 12px;
		    top: 23px;
		}

		.ui-widget-overlay.ui-front{
			z-index: 100 !important;
		}

		.search{
			width: 40% !important;
		}

		.panel-primary {
		    border-color: #5bc0de;
		}

		.panel-primary > .panel-heading {
		    color: #fff;
		    background-color: #5bc0de;
		    border-color: #5BC0DC;
			background-image: linear-gradient(to bottom, #5bc0de 0%, #5bc0de 100%);
		}

		.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
		    word-wrap: break-word;
		    white-space: pre-line !important;
		    vertical-align: top !important;
		}

		tr.yellow{
			background-color:yellow !important;
		}

		input.yellow{
			color: black !important;
			border-color:#9e9e00 !important;
			background-color:yellow !important;
		}

		a.yellow{
			color: #9e9e00 !important;
		    background-color: #fdffe2 !important;
		    border-color: #9e9e00 !important;
		}

		tr.red{
			color:white;
			background-color:red !important;
		}

		input.red{
			color: white !important;
			border-color:red !important;
			background-color:red !important;
		}

		a.red{
			color: red !important;
		    background-color: #ffe5e5 !important;
		    border-color: red !important;
		}

		tr.green{
			color:white;
			background-color:green !important;
		}
		
		input.green{
			color: white !important;
			border-color:green !important;
			background-color:green !important;
		}

		a.green{
			color: #3c763d !important;
		    background-color: #dff0d8 !important;
		    border-color: #3c763d !important;
		}

		fieldset.mycss {
		    border: 1px groove #ddd !important;
		    padding: 0 1.4em 1.4em 1.4em !important;
		    margin: 0 0 1.5em 0 !important;
		    -webkit-box-shadow:  0px 0px 0px 0px #000;
		            box-shadow:  0px 0px 0px 0px #000;
		}

	    fieldset.mycss > legend {
	        font-size: 1.2em !important;
	        font-weight: bold !important;
	        text-align: left !important;
	        width:auto;
	        padding:0 10px;
	        border-bottom:none;
	    }

	    input.myerror{
	    	border-color: #b94a48 !important;
		    background-position: right 5px center !important;
		    background-repeat: no-repeat !important;
		    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%) !important;
		    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%) !important;
	    }

	    .sticky_div{
	    	position: fixed;
	    	top: 100px;
	    	left: 16px;
	    }

	    .panel-heading.clearfix.collapsed.position{
	    	padding: 4px;
	    	border: 0px;
	    }

	    .highlight{
	    	background-color: #dfdfdf;
	    	margin: 20px;
			border-radius: 10px;
	    }

	    table#docnote_date_tbl tr.selected{
	    	background-color: #dfdfdf;
	    }

	    button.command-otc-episode, button.command-episode{
	    	border-color: #744747;
	    	padding: 1px 5px 1px 5px;
	    }

		.wrap{
			word-wrap: break-word;
			white-space: pre-line !important;
			vertical-align: top !important;
		}

		@media screen and (max-width: 1024px) {
		  .hiddentab {
		    display: none;
		  }
		}

	</style>

</head>
<script type="text/javascript">
  //   var desc_show = null;

  //   function mykadscantype(){
  //   	return $("#patientBox").data('scantype');
  //   }

  //   function closemodalfp(){
  //      $('#mdl_biometric').modal('hide');
  //      if($("#patientBox").data('gotpat') == true){
  //      		$("#patientBox").click();
  //      }
  //   }

  //   function populatefromfp(obj){
  //   	$("#patientBox").data('gotpat',true);
  //       $('#first_visit_date').val(moment().format('DD/MM/YYYY'));
  //       $('#last_visit_date').val(moment().format('DD/MM/YYYY'));

  //       $('#txt_pat_name').val(obj.name);
  //       $('#txt_pat_newic').val(obj.icnum).blur();
  //       if(obj.gender == 'P' || obj.gender == 'F' || obj.gender.toUpperCase() == 'FEMALE' || obj.gender.toUpperCase() == 'PEREMPUAN'){
  //       	$('#cmb_pat_sex').val('F');
  //       }else if(obj.gender == 'L' || obj.gender == 'M' || obj.gender.toUpperCase() == 'MALE' || obj.gender.toUpperCase() == 'LELAKI'){
  //       	$('#cmb_pat_sex').val('M');
  //       }

  //       $('#hid_ID_Type').val("O");
  //       $('#txt_ID_Type').val("OWN IC");

  //       // var olddob = obj.dob;
		// // newdob = [olddob.slice(0, 4), '-', olddob.slice(4,6), '-', olddob.slice(6)].join('');

  // //       $('#txt_pat_dob').val(newdob);
  // //       $('#txt_pat_age').val(gettheage(newdob));
  //       $('#hid_RaceCode').val(obj.race);

  //       $('#hid_Religion').val(obj.religion);
  //       // $('#cmb_pat_category').val('LOCAL');
  //       $('#hid_pat_citizen').val(obj.citizenship);

  //       $('#txt_pat_curradd1').val(obj.address1);
  //       $('#txt_pat_curradd2').val(obj.address2);
  //       $('#txt_pat_curradd3').val(obj.address3);
  //       $('#txt_pat_currpostcode').val(obj.postcode);
  //       $("img#photobase64").attr('src','data:image/png;base64,'+obj.base64);

  //       mykad_check_existing_patient(function(obj){
  //       	$('.search-field').val(obj.MRN);
  //       	$('#btn_register_episode').data('mrn',obj.MRN);
  //       	$('#Scol').val('MRN');
  //           $("#grid-command-buttons").bootgrid('reload');
  //       });

  //       auto_save('race',{
  //           _token : $('#csrf_token').val(),
  //       	table_name: 'hisdb.racecode',
  //       	code_name: 'Code',
  //       	desc_name: 'Description',
  //       	Code: obj.race,
  //       	Description: obj.race,
  //       },function(){desc_show.load_sp_desc('race','pat_mast/get_entry?action=get_patient_race')});

  //       auto_save('religioncode',{
  //           _token : $('#csrf_token').val(),
  //       	table_name: 'hisdb.religion',
  //       	code_name: 'Code',
  //       	desc_name: 'Description',
  //       	Code: obj.religion,
  //       	Description: obj.religion,
  //       },function(){desc_show.load_sp_desc('religioncode','pat_mast/get_entry?action=get_patient_religioncode')});

  //       auto_save('citizencode',{
  //           _token : $('#csrf_token').val(),
  //       	table_name: 'hisdb.citizen',
  //       	code_name: 'Code',
  //       	desc_name: 'Description',
  //       	Code: obj.citizenship,
  //       	Description: obj.citizenship,
  //       },function(){desc_show.load_sp_desc('citizencode','pat_mast/get_entry?action=get_patient_citizen')});

  //       desc_show.write_desc();
  //   }

  //   function auto_save(id,obj,callback){
  //       if(desc_show.get_desc(obj.Code,id) == "N/A"){
  //       	$.post( './pat_mast/auto_save', obj , function( data ) {
	 //        }).fail(function(data) {
	 //            console.log(data.responseText);
	 //        }).success(function(data){
	 //        	callback();
	 //        });
  //       }
  //   }
</script>

<body>
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
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
        <input name="lastMrn" id="lastMrn" type="hidden" >
        <input name="lastidno" id="lastidno" type="hidden" >

        <div id="info"></div>

		<div class="panel" style="padding: 5px;">
			&nbsp;&nbsp;
			<button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
			&nbsp;&nbsp;
			<button id="btn_mykad" type="button" class="btn btn-default btn-md" >
			<img src="img/mykad.png" width="35" /> My Kad</button>
			&nbsp;&nbsp;
			<a id="btn_biometric2" type="button" class="btn btn-default btn-md" target="_blank" href='http://localhost/mycard/public/read_mykad' >
			<img src="img/biometric.png" width="22" /> Biometric </a>
			&nbsp;&nbsp;
			<button id="btn_discharge" type="button" class="btn btn-danger btn-md" style="display:none;"><span class="fa fa-paper-plane" aria-hidden="true"></span> Discharge</button>
		</div>

		@if (!Session::has('isdoctor') && request()->get('curpat') == 'true')
		<div class="panel panel-default" style="position: relative;margin: 0 12px 12px 12px">
	        <div class="panel-heading clearfix collapsed" id="toggle_preepis" data-toggle="collapse" data-target="#tabpreepis" style="padding: 20px 20px 20px 20px;">

	        <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px;top: 6px;"></i>
	        <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px;top: 6px;"></i >
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
					@if (request()->get('curpat') == 'true'){{'CURRENT PATIENT'}}
					@else {{'PATIENT LIST'}}
					@endif
				</strong></h5>
			</div>
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="mrn" data-formatter="col_add" data-width="4%">#</th>
                    @if (request()->get('curpat') == 'true')
                    <th data-column-id="QueueNo" data-width="3%">Queue</th>
                    <th data-column-id="reg_date" data-width="7%">Reg Date</th>
					@endif
                    <th data-column-id="MRN" data-type="numeric" data-formatter="col_mrn" data-width="5%">MRN</th>
                    <th data-style="dropDownItem" data-column-id="Name" data-formatter="col_name" data-width="20%">Name</th>
                	<th data-column-id="pregnant" data-formatter="col_preg" data-width="5%"></th>
                    <th data-column-id="q_doctorname" data-width="20%" data-css-class="hiddentab" data-header-css-class="hiddentab">Doctor</th>
                    <th data-column-id="Newic" data-width="8%">New IC</th>
                    <th data-column-id="telhp" data-width="8%">H/P</th>
                    <th data-column-id="DOB" data-formatter="col_dob" data-width="6%" data-css-class="hiddentab" data-header-css-class="hiddentab">DOB</th>
                    <th data-column-id="Sex" data-width="2%">Sex</th>
                    <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="2%">Age</th>
					<th data-column-id="commands" data-formatter="commands" data-sortable="false" data-width="7%">Info &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type</th>
				</tr>
				</thead>

			</table>


		</div>


		@include('hisdb.pat_mgmt.mdl_patient')
		@include('hisdb.pat_mgmt.mdl_episode')
		@include('hisdb.pat_mgmt.itemselector')


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
						@include('hisdb.doctornote.doctornote')
					</div>

					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
				@elseif (Auth::user()->nurse == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
				@endif

				@if (Auth::user()->billing == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.ordcom.ordcom')
					</div>
				@endif

				<div class='row' style="position: relative;margin: 0 12px 12px 12px">
					@include('hisdb.discharge.discharge',['type' => "OP",'type_desc' => "Out Patient"])
				</div>

			@endif

			@if (request()->get('epistycode') == 'IP')
				@if (Auth::user()->doctor == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="nursing_row">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>

					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.wardpanel.wardpanel')
					</div>

					<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="antenatal_row">
						@include('hisdb.antenatal.antenatal')
					</div>

					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.doctornote.doctornote')
					</div>

					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.dieteticCareNotes.dieteticCareNotes')
					</div>
				@elseif (Auth::user()->nurse == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
					</div>
					
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.wardpanel.wardpanel')
					</div>
				@endif

				@if (Auth::user()->billing == 1)
					<div class='row' style="position: relative;margin: 0 12px 12px 12px">
						@include('hisdb.ordcom.ordcom')
					</div>
				@endif

				<div class='row' style="position: relative;margin: 0 12px 12px 12px">
					@include('hisdb.discharge.discharge',['type' => "IP",'type_desc' => "In Patient"])
				</div>
				
			@endif


		@endif
		

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
    
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script type="text/javascript" src="js/myjs/utility.js"></script>


	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/biodata.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/episode.js"></script>

	<input type="hidden" id="user_billing" value="{{Auth::user()->billing}}">
	<input type="hidden" id="user_nurse" value="{{Auth::user()->nurse}}">
	<input type="hidden" id="user_doctor" value="{{Auth::user()->doctor}}">
	<input type="hidden" id="user_dept" value="{{Session::get('dept')}}">

	<input type="hidden" name="rng" id="rng">

	@if (request()->get('curpat') == 'true')
		<script type="text/javascript" src="js/hisdb/discharge/discharge.js"></script>


		@if (request()->get('epistycode') == 'OP')
			@if (Auth::user()->doctor == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js"></script>
				<script type="text/javascript" src="js/hisdb/antenatal/antenatal.js"></script>
				<script type="text/javascript" src="js/hisdb/paediatric/paediatric.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
			@elseif (Auth::user()->nurse == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js"></script>
			@endif

			@if (Auth::user()->billing == 1)
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom.js"></script>
			@endif
		@endif

		@if (request()->get('epistycode') == 'IP')
			@if (Auth::user()->doctor == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js"></script>
				<script type="text/javascript" src="js/hisdb/wardpanel/wardpanel.js"></script>
				<script type="text/javascript" src="js/hisdb/antenatal/antenatal.js"></script>
				<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js"></script>
				<script type="text/javascript" src="js/hisdb/dieteticCareNotes/dieteticCareNotes.js"></script>
			@elseif (Auth::user()->nurse == 1)
				<script type="text/javascript" src="js/hisdb/nursing/nursing.js"></script>
				<script type="text/javascript" src="js/hisdb/wardpanel/wardpanel.js"></script>
			@endif

			@if (Auth::user()->billing == 1)
				<script type="text/javascript" src="js/hisdb/ordcom/ordcom.js"></script>
			@endif
		@endif
	@endif


	<!-- <script type="text/javascript" src="js/hisdb/pat_mgmt/epis_doctor.js"></script> -->
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_dialysis.js"></script>
	<!-- <script type="text/javascript" src="js/hisdb/pat_mgmt/epis_nok.js"></script> -->
	<!-- <script type="text/javascript" src="js/hisdb/pat_mgmt/epis_bed.js"></script> -->
	<script type="text/javascript" src="js/hisdb/pat_mgmt/pat_nok.js"></script>
	<!-- <script type="text/javascript" src="js/hisdb/pat_mgmt/pat_emr.js"></script> -->
	<script type="text/javascript" src="js/hisdb/pat_mgmt/landing.js"></script>

	</div>

</body>
</html>