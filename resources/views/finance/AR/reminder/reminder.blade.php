@extends('layouts.main')

@section('title', 'Reminder')

@section('style')
	body{
		background: #00808024 !important;
	}
	.container.mycontainer{
		padding-top: 10px;
	}
	.mycontainer .panel-default {
	    border-color: #9bb7b7 !important;
	}
	.mycontainer .panel-default > .panel-heading {
	    background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
    	font-weight: bold;
	}
	.mycontainer .mybtnpdf{
		background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
    	color: #af2525;
    	border-color: #af2525;
    	margin-bottom: 5px;
	}
	.mycontainer .mybtnxls{
		background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
	    color: darkgreen;
	    border-color: darkgreen;
    	margin-bottom: 20px;
	}
	.mycontainer .btnvl {
	  	border-left: 1px solid #386e6e;
	    width: 0px;
	    padding: 0px;
	    height: 32px;
	    cursor: unset;
	    margin: 0px 7px;
	}
	legend{
		margin-bottom: 5px !important;
		font-size: 12px !important;
		font-weight:bold;
	}
@endsection('style')

@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="width: 90%;margin: auto;">
				<div class="panel-heading" >Reminder
					<button id="print_btn" type="button" class="btn btn-sm btn-info pull-right" style="margin-top: -6px; margin-right: 5px;">
						Print
					</button>
					<button id="cancel_btn" type="button" class="btn btn-sm btn-default pull-right" style="margin-top: -6px; margin-right: 5px;" disabled>
						Cancel
					</button>
					<button id="save_btn" type="button" class="btn btn-sm btn-default pull-right" style="margin-top: -6px; margin-right: 5px;" disabled>
						Save
					</button>
					<button id="edit_btn" type="button" class="btn btn-sm btn-default pull-right" style="margin-top: -6px; margin-right: 5px;">
						Edit
					</button>
				</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 	<form id="formdata">
						 	<div class="col-md-5">
							 	<label for="monthfrom">Date</label>
								<input name='date' id='date' type="date" class="form-control" data-validation="required" value="{{ now()->toDateString() }}">
							</div>

						 	<div class="col-md-6">
							 	<label for="monthto">Debtor</label>
								<div class='input-group'>
	                  <input id="debtorcode" name="debtorcode" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value">
	                  <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	              </div>
	              <span class="help-block"></span>
							</div>

						 	<div class="col-md-8" style="padding-top: 10px;">
						 		<textarea class="form-control input-sm" rows="35" name="comment_" id="comment_" readonly>{{$comment_}}</textarea>
						 	</div>


						 	<div class="col-md-4" style="padding-top: 10px;">

						 		<div class="col-md-12">
							 		<div class="col-md-5">
								 		<input type="radio" id="remind1" name="trantype" value="remind1" checked>
									  <label for="remind1">Normal</label>
							 		</div>
							 		<span id="span_1" class="span_tt">
								 		<div class="col-md-4" style="padding:0px">
											<input name='days1' id='days1' type="input" class="form-control input-sm days" value="14" data-validation="">
								 		</div>
								 		<div class="col-md-2" style="padding-top:6px">
											<b>Days</b>
								 		</div>
								 	</span>
								</div>

						 		<div class="col-md-12">
								  <div class="col-md-5">
								 		<input type="radio" id="remind2" name="trantype" value="remind2">
									  <label for="remind2">1st</label>
							 		</div>
							 		<span id="span_2" style="display:none" class="span_tt">
								 		<div class="col-md-4" style="padding:0px">
											<input name='days2' id='days2' type="input" class="form-control input-sm days" value="14" data-validation="">
								 		</div>
								 		<div class="col-md-2" style="padding-top:6px">
											<b>Days</b>
								 		</div>
								 	</span>
								</div>

						 		<div class="col-md-12">
							 		<div class="col-md-5">
								 		<input type="radio" id="remind3" name="trantype" value="remind3">
									  <label for="remind3">2nd</label>
							 		</div>
							 		<span id="span_3" style="display:none" class="span_tt">
								 		<div class="col-md-4" style="padding:0px">
											<input name='days3' id='days3' type="input" class="form-control input-sm days" value="14" data-validation="">
								 		</div>
								 		<div class="col-md-2" style="padding-top:6px">
											<b>Days</b>
								 		</div>
								 	</span>
								</div>

						 		<div class="col-md-12">
							 		<div class="col-md-5">
								 		<input type="radio" id="remind4" name="trantype" value="remind4">
									  <label for="remind4">Demand</label>
							 		</div>
							 		<span id="span_4" style="display:none" class="span_tt">
								 		<div class="col-md-4" style="padding:0px">
											<input name='days4' id='days4' type="input" class="form-control input-sm days" value="14" data-validation="">
								 		</div>
								 		<div class="col-md-2" style="padding-top:6px">
											<b>Days</b>
								 		</div>
								 	</span>
								</div>

						 	</div>

					  </form>
					</div>
				</div>
			</div> 
		</div>
  </div> 
</div>
		
@endsection

@section('scripts')
<script src="js/finance/AR/reminder/reminder.js?v=1.1"></script>
@endsection