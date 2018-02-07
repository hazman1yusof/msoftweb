@extends('layouts.main')

@section('title', 'Doctor Maintenance ')

@section('body')
	<div class='row'>
		<input id="Type" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px" >
				 
				    <button type="button" class="btn btn-default" id='TSBtn'>
				  	<span class='fa fa-clock-o fa-lg '></span> Time Session
				  </button>
				   <button type="button" class="btn btn-default" id='ALBtn'>
				  	<span class='fa fa-calendar fa-lg '></span> Leave
				  </button>
				   <button type="button" class="btn btn-default" id='PHBtn'>
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
    <ul>
			<b>DOCTOR CODE : </b><span name='resourcecode' ></span> <br><br>
			<b>DOCTOR NAME: </b><span name='description' ></span>
			
		</ul>	

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



					<!-- <label class="col-md-2 control-label" for="days">Day</label>  
					<div class="col-md-2">
					  	<select id="days" name="days" class="form-control input-sm"  >
					      <option value="MONDAY">MONDAY</option>
					      <option value="TUESDAY">TUESDAY</option>
					      <option value="WEDNESDAY">WEDNESDAY</option>
					      <option value="THURSDAY">THURSDAY</option>
					      <option value="FRIDAY">FRIDAY</option>
					      <option value="SATURDAY">SATURDAY</option>
					      <option value="SUNDAY">SUNDAY</option>
					    </select>
					    </div> -->


					    <div class="form-group">
				  <label class="col-md-2 control-label" for="days">Days</label>  
				  <div class="col-md-8">
				    <table>
                             	<tr>
                             
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Monday' >Monday</label></td>
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Tuesday' >Tuesday</label></td>
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Wednesday'>Wednesday</label></td>
								</tr>
							
				 			<tr>
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Thursday'>Thursday</label></td>
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Friday'>Friday</label></td>
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Saturday'>Saturday</label></td>
							</tr>
                            
                            <tr>
				 			
                                <td><label class="radio-inline"><input type="checkbox" name="days" value='Sunday'>Sunday</label></td>
                             
                               </tr>
                               </table>				
                </div>
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
			<input id="idno" name="idno" type="hidden">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	         

				<div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="date" name="datefr" id="datefr" class="form-control input-sm" value="<?php echo date("d-m-Y"); ?>" data-validation="required" >
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
						<input type="date" name="dateto" id="dateto" class="form-control input-sm"   data-validation="required" value="<?php echo date("d-m-Y"); ?>" >
					</div>
				</div>
				</div>

				<div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remark</label>   
						  			<div class="col-md-5">
						  				<textarea rows="5" id='_remark' name='remark' class="form-control input-sm" ></textarea>
						  			</div>
				</div>
				</div>
                
			</form>
		</div>
          
          <div id="aldialogForm" title="Transfer Form">
		
			<form class='form-horizontal' style='width:89%' id='alformdata'>
			{{ csrf_field() }}
			<input id="resourcecode" name="resourcecode" type="hidden">
			<input id="idno" name="idno"type="hidden">
			<input id="YEAR" name="YEAR" type="hidden"  value="<?php echo date("Y") ?>">
				<div class="form-group">
				<div class="form-group">
				
			</div>
                   <div class="form-group">
					<label class="col-md-2 control-label" for="datefr">From</label>
					<div class="col-md-2">
						<div class='input-group'>
							<input type="date" name="datefr" id="datefr" class="form-control input-sm" data-validation="required" value="<?php echo date("d-m-Y"); ?>">
							
						</div>
						
					</div>
					<label class="col-md-2 control-label" for="dateto">To</label>
					<div class="col-md-2">
						<input type="date" name="dateto" id="dateto" class="form-control input-sm"   data-validation="required" value="<?php echo date("d-m-Y"); ?>" >
					</div>
				</div>
				</div>
                  <div class="form-group">
				<!-- <div class="form-group">
				
			</div> -->
	

				<div class="form-group">
					<label class="col-md-2 control-label" for="remark">Remark</label>
					<div class="col-md-2">
						<div class='input-group'>
							<textarea rows="5" name="remark" id="remark" class="form-control input-sm" data-validation="required"></textarea>
							
							
						</div>
						
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
							<input type="text" name="resourcecode" id="resourcecode" class="form-control input-sm" data-validation="required" >
					</div>

					<label class="col-md-2 control-label" for="description">Description</label>
					<div class="col-md-4">
						<input type="text" name="description" id="description" class="form-control input-sm"   data-validation="required"  >
					</div>
				</div>
			</form>
		</div>


	@endsection


@section('scripts')

	<script src="js/hisdb/appointment/doctor_maintenanceScript.js"></script>
	
@endsection