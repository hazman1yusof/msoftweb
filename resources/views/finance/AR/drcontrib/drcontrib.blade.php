@extends('layouts.main')

@section('title', 'Process Doctor Fees')

@section('css')
	<style>
		table.reporttable th{
			border: none;
			text-align: right;
			padding-right: 20px;
		}
		table.reporttable td{
			padding: 5px;
		}
		
		/* body{
			background: #00808024 !important;
		}
		.container.mycontainer{
			padding-top: 5%;
		}
		.mycontainer .panel-default{
			border-color: #9bb7b7 !important;
		}
		.mycontainer .panel-default > .panel-heading{
			background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
			font-weight: bold;
		} */
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
		.mycontainer .btnvl{
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
			font-weight: bold;
		}
		.btnform .btn{
			width: -webkit-fill-available !important;
		}
	</style>
@endsection

@section('body')

	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
    <div class="container mycontainer">
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 350px;">
            <form method="get">
                <h4>Process Doctor Fees</h4>
                <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7>
				
				<div style="width: 800px;margin: 0 auto;">
					<div class="col-md-4" style="margin-left: 120px;">
						<div class="col-md-12">
							<label class="control-label" for="Scol">Date From</label>
							<input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
						
						<div class="col-md-12" style="padding-top: 30px;">
							<label class="control-label" for="Scol">Date To</label>
							<input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
					</div>
					
					<div class="col-md-4" style="margin-left: 100px;">
						<div class="panel panel-default" style="height: 137px;">
							<div class="panel-body">
								<div class='col-md-12 btnform' style="padding: 20px 0px">
									<fieldset>
										<!-- <legend>Stock Sheet :</legend> -->
										<button type="button" class="mybtn btn btn-sm btn-primary" id="process_drcontrib">
											Process Doctor Fees
										</button>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')

	<script>
		$(document).ready(function (){
			$('#process_drcontrib').click(function(){
				let obj={
					oper:'process_contrib',
					datefr:$('#datefr').val(),
					dateto:$('#dateto').val(),
					_token:$('#_token').val()
				};
				$.post( './drcontrib/form', obj , function( data ) {
		
				}).fail(function(data) {
					alert(data.responseText);
				}).done(function(data){
					alert('Process Completed');
				});
			});
			
		});
	</script>
@endsection