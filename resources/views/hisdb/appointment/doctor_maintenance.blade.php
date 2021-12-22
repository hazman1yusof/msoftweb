@extends('layouts.main')

@section('title', 'Doctor Maintenance ')

@section('body')
	<div class='row'>
		<input id="Type" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px" >
				 
				    <button type="button" class="btn btn-default" tabindex="2" id='TSBtn'>
				  	<span class='fa fa-clock-o fa-lg '></span> Time Session
				  </button>
				   <button type="button" class="btn btn-default" tabindex="3" id='ALBtn'>
				  	<span class='fa fa-calendar fa-lg '></span> 
				  </button>
				   <button type="button" class="btn btn-default" tabindex="4" id='PHBtn'>
				  	<span class='fa fa-calendar fa-lg '></span> Public Holiday
				  </button>
				 
				
				</div>
				
			 </fieldset> 
		</form>
		
		<div class="panel panel-default">
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
						<div id="jqGridPager"></div>
				</div>
			</div>
		</div>

	</div>
    
	 <div id="TSBox" title="Time Session" style="display:none">
    	<ul>    
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
           
		</ul>
         
				<table class="table table-sm table-bordered">
				<thead>
				<tr>
		        <th colspan="2">Morning Session</th>
		        <th colspan="2">Evening Session</th>
		        <th colspan="2"></th>
                </tr>

                <tr>
		        <th>Start Time</th>
		        <th>End Time</th>
		        <th>Start Time</th>
		        <th>End Time</th>
		        <th rowspan="2"><center><button type="button"  class="btn btn-primary btn-rounded btn-sm my-0" id='allTimeBtn'>Apply to All</center></th>
	            </tr>

	            <tr>
		        <td><input type="time" name="timefr1" id="time1" class="form-control input-sm" data-validation="required" value="08:00"></td>
		        <td><input type="time" name="timeto1" id="time2" class="form-control input-sm" data-validation="required" value="12:00"></td>
		        <td><input type="time" name="timefr2" id="time3" class="form-control input-sm" data-validation="required" value="14:00"></td>
		        <td><input type="time" name="timeto2" id="time4" class="form-control input-sm" data-validation="required" value="17:00"></td>
	            </tr>
			
				</thead>
				<tbody>
				</tbody>
				</table>
				

		<div id='gridtime_c' style="padding:15px 0 15px 0">
            <table id="gridtime" class="table table-striped"></table>
            <div id="gridtimepager"></div>
        </div>
	 	
		<button type="button" class="btn btn-primary pull-right" id="tsbutton"><span class='fa fa-save fa-lg '></span> Save Session </button>
	</div>

	<div id="PHBox" title="Public Holiday" style="display:none">	
     <!--  <ul>
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
		</ul> -->
        
			<fieldset>
				<form id="searchForm1" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div> 
						
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					
				</div>
			 </fieldset> 
		</form>
				
			 </fieldset> 
		
		<div id='gridph_c' style="padding:15px 0 0 0">
            <table id="gridph" class="table table-striped"></table>
            <div id="gridphpager"></div>
        </div>
    </div>
	

	<div id="ALBox" title="Annual Leave" style="display:none">
		<div class="row" style="padding:0">
			<label class="col-md-2 control-label">DOCTOR CODE</label>
			<span name='resourcecode' ></span>
		</div>
		<div class="row" style="padding:0">
			<label class="col-md-2 control-label">DOCTOR NAME</label>
			<span name='description' ></span>
		</div>
		<div class="row" style="padding:0">
			<label class="col-md-2 control-label" for="bg_leave">LEAVE COLOR</label>
  			<div class="form-inline">
  				<span style="font-size:3em;cursor: pointer;" id="colorpointer">
					<img src="img/paint.png" style="width:40px;border-bottom:solid;border-bottom-width:5px" alt="..." id="imgid_leave">
				</span>
  				<input type='color' id='bg_leave' name='bg_leave' class="form-control input-sm" value="#ff0000" style="width: 150px;">
  			</div>
		</div>
		<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

		<div id='gridleave_c' style="padding:15px 0 0 0">
            <table id="gridleave" class="table table-striped"></table>
            <div id="gridleavepager"></div>
        </div>
	</div>

    <div id="tsdialogForm" title="Transfer Form">
			
			<form class='form-horizontal' style='width:89%' >
			<div class="prevnext btn-group pull-right"></div>
		
			</form>

			<form class='form-horizontal' style='width:89%' id='tsformdata'>
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="doctorcode" name="doctorcode" type="hidden">
			<input id="idno" name="idno"  type="hidden">
			{{ csrf_field() }}
				<div class="form-group">
				<div class="form-group">
				
			</div>

				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="timefr1">Start Time</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="time" name="timefr1" id="timefr1" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="timeto1">End Time</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="time" name="timeto1" id="timeto1" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="timefr2">Start Time</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="time" name="timefr2" id="timefr2" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="timeto2">End Time</label>
					<div class="col-md-3">
						<div class='input-group'>
							<input type="time" name="timeto2" id="timeto2" class="form-control input-sm" data-validation="required">
							
						</div>
						
					</div>
					
				</div>
	
			</form>
		
	</div>

            
      <div id="phdialogForm" title="Transfer Form">
		
		<!-- 	<hr> -->
			<form class='form-horizontal' style='width:89%' id='phformdata'>
			{{ csrf_field() }}
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input type="hidden" name="idno">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	        

				<div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input id="datefr" name="datefr" type="text" tabindex="-1" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}"  maxlength="10" class="form-control input-sm" 
										data-validation="required">		
						  <span class="input-group-addon">
                                    <span class="glyphicon-calendar glyphicon"></span>
                                </span>						
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
					<div class='input-group'>
						<input id="dateto" name="dateto" type="text" tabindex="-1" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}" maxlength="10" class="form-control input-sm" data-validation="required" >
	                    <span class="input-group-addon">
	                        <span class="glyphicon-calendar glyphicon"></span>
	                    </span>						
					</div>
					</div>
				</div>
				</div>

				<div class="form-group">
			
					<div class="form-group">
						<label class="col-md-2 control-label" for="remark">Remark</label>   
			  			<div class="col-md-5">
			  				<textarea rows="5" id='_remark' name='remark' class="form-control input-sm text-uppercase" data-validation="required" ></textarea>
			  			</div>
					</div>
				</div>
			</form>
		</div>
          
          <div id="aldialogForm" title="Transfer Form">
		
			<form class='form-horizontal' style='width:89%' id='alformdata'>
			{{ csrf_field() }}
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="idno" name="idno" type="hidden">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<div class="form-group">
				
			</div>
                	<div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input id="datefr" name="datefr" type="text" tabindex="-1" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}"  maxlength="10" class="form-control input-sm" 
										data-validation="required">		
						  <span class="input-group-addon">
                                    <span class="glyphicon-calendar glyphicon"></span>
                                </span>						
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
					<div class='input-group'>
						<input id="dateto" name="dateto" type="text" tabindex="-1" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}" maxlength="10" class="form-control input-sm" data-validation="required" >
                    <span class="input-group-addon">
                        <span class="glyphicon-calendar glyphicon"></span>
                    </span>						
						</div>
					</div>
				</div>
				</div>
                  <div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remark</label>   
		  			<div class="col-md-5">
		  				<textarea rows="5" id='_remark' name='remark' class="form-control input-sm text-uppercase"></textarea>
		  			</div>
				</div>
				</div>
			</form>
		</div>

		  <div id="resourceAddform" title="Add Resource Form">
		
			<form class='form-horizontal' style='width:89%' id='resourceformdata'>
				{{ csrf_field() }}
				<input id="idno" name="idno" type="hidden">

				<input id="TYPE" name="TYPE" type="hidden"  value="RSC">

	            <div class="form-group">
					<label class="col-md-3 control-label" for="resourcecode">Resource Code</label>
					<div class="col-md-3">
						<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm text-uppercase" data-validation="required">
					</div>

					<label class="col-md-2 control-label" for="description">Description</label>
					<div class="col-md-4">
						<input type="text" name="description" id="description" class="form-control input-sm text-uppercase" data-validation="required">
					</div>
				</div>
			</form>
		</div>


	@endsection


@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',5);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',6);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}

				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}	
		});
	</script>

	<script src="js/hisdb/appointment/doctor_maintenanceScript.js"></script>

	
@endsection