@extends('layouts.main')

@section('title', 'Current Patient')

@section('body')

	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<div class='row'>
		<input id="Epistycode" name="Epistycode" type="hidden" value="{{Request::get('epistycode')}}">
		<form id="searchForm" class="formclass" style='width:99%;position: relative;'>
			<fieldset>
				<div class="ScolClass" style="padding:0 0 0 15px">
					<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px;padding-top: 25px" >
					<button type="button" class="btn btn-default" id='mrn_but_currentPt'>
						<span class='fa fa-user fa-lg'></span> MRN Update
					</button>
					<button type="button" class="btn btn-default" id='episode_but_currentPt' data-oper='add'>
						<span class='fa fa-h-square fa-lg'></span> Episode Update
					</button>
				    <button id="adjustment_but_currentPt" type="button" class="btn btn-default">
				    	<span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Adjustment
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
	<!-- 	<div class="form-group">
		<div class="col-md-4" style="padding:0 0 15px 0">
		<br />
		<p><strong>HOME ADDRESS</strong></p>														
		<div class="panel panel-default">
		<ul class="nav nav-tabs">
		<li class="active"><a href="#addr_current" data-toggle="tab">Current</a></li>
		<li><a href="#addr_office" data-toggle="tab">Office</a></li>
		<li><a href="#addr_home" data-toggle="tab">Home</a></li>
		</ul>
		<div class="panel-body">
		<div class="tab-pane fade in active" id="addr_current">
		<br />
		<div class="col-md-4">
		<p>Current Address</p>
		</div>
		<div class="col-md-8">
		<p><input name="Address1" id="txt_pat_curradd1" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address2" id="txt_pat_curradd2" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address3" id="txt_pat_curradd3" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-4">
		<p>Postcode<input name="Postcode" id="txt_pat_currpostcode" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-12">
		<p></p>
		</div>
	
		</div>
	</div>
	</div>
	</div>
	</div>
    <div class="form-group">
		<div class="col-md-4" style="padding:0 0 15px 0">
		<br />
		<p><strong>OFFICE ADDRESS</strong></p>														
		<div class="panel panel-default">
		<ul class="nav nav-tabs">
		<li class="active"><a href="#addr_current" data-toggle="tab">Current</a></li>
		<li><a href="#addr_office" data-toggle="tab">Office</a></li>
		<li><a href="#addr_home" data-toggle="tab">Home</a></li>
		</ul>
		<div class="panel-body">
		<div class="tab-pane fade in active" id="addr_current">
		<br />
		<div class="col-md-4">
		<p>Current Address</p>
		</div>
		<div class="col-md-8">
		<p><input name="Address1" id="txt_pat_curradd1" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address2" id="txt_pat_curradd2" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address3" id="txt_pat_curradd3" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-4">
		<p>Postcode<input name="Postcode" id="txt_pat_currpostcode" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-12">
		<p></p>
		</div>
	
		</div>
	</div>
	</div>
	</div>
	</div>
	 <div class="form-group">
		<div class="col-md-4" style="padding:0 0 15px 0">
		<br />
		<p><strong>PAYER INFORMATION</strong></p>														
		<div class="panel panel-default">
		<ul class="nav nav-tabs">
		<li class="active"><a href="#addr_current" data-toggle="tab">Current</a></li>
		<li><a href="#addr_office" data-toggle="tab">Office</a></li>
		<li><a href="#addr_home" data-toggle="tab">Home</a></li>
		</ul>
		<div class="panel-body">
		<div class="tab-pane fade in active" id="addr_current">
		<br />
		<div class="col-md-4">
		<p>Current Address</p>
		</div>
		<div class="col-md-8">
		<p><input name="Address1" id="txt_pat_curradd1" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address2" id="txt_pat_curradd2" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-8">
		<p><input name="Address3" id="txt_pat_curradd3" class="form-control form-mandatory" type="text" /></p>
		</div>
		<div class="col-md-4">
		<p></p>
		</div>
		<div class="col-md-4">
		<p>Postcode<input name="Postcode" id="txt_pat_currpostcode" class="form-control form-mandatory" type="text" required /></p>
		</div>
		<div class="col-md-12">
		<p></p>
		</div>
	
		</div>
	</div>
	</div>
	</div>
	</div> -->

	</div>

	<div id="adjustmentform" title="Adjustment" >
		<form class='form-horizontal' style='width:99%' id='adjustmentformdata'>
		{{ csrf_field() }}
			<input type="hidden" name="idno">
				<div class="form-group">
				  <label class="col-md-2 control-label" for="episno">Episode No</label>  
                      <div class="col-md-2">
                      <input id="episno" name="episno" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>

			<div class="form-group">
                  <label class="col-md-2 control-label" for="epistycode">Type</label>  
                      <div class="col-md-2">
                      <input id="epistycode" name="epistycode" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                   <div class="col-md-2">
				      <input id="epistycode" name="epistycode" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				   </div>     
			</div>
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
                      <div class="col-md-2">
                      <input id="bedtype" name="bedtype" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                   <div class="col-md-2">
					<input id="bedtype" name="bedtype" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				   </div>      
			</div>    
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="bed">Bed</label>  
                      <div class="col-md-2">
                      <input id="bed" name="bed" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                  <label class="col-md-1 control-label" for="room">Room</label>
			          <div class="col-md-2">
				      <input type="text" name="room" id="room" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
            </div> 
        
        <hr>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="reg_date">Reg Date</label>  
                      <div class="col-md-2">
                      <input id="reg_date" name="reg_date" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
                  <label class="col-md-1 control-label" for="reg_by">Register By</label>
			          <div class="col-md-2">
				      <input type="text" name="reg_by" id="reg_by" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>
			      <label class="col-md-1 control-label" for="reg_time">Time</label>
			          <div class="col-md-2">
				      <input type="text" name="reg_time" id="reg_time" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
			</div>

			<div class="form-group">
			      <label class="col-md-2 control-label" for="qdate">Discharge Date</label>  
                      <div class="col-md-2">
                      <input id="qdate" name="qdate" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
                  <label class="col-md-1 control-label" for="qdate">Discharge By</label>
			          <div class="col-md-2">
				      <input type="text" name="qdate" id="qdate" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>
			      <label class="col-md-1 control-label" for="qtime">Time</label>
			          <div class="col-md-2">
				      <input type="text" name="qtime" id="qtime" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
			</div>
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="description">Destination</label>  
                      <div class="col-md-2">
                      <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                      <div class="col-md-3">
					  <input id="description" name="description" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				      </div>      
			</div>    
          
	  </form>

	</div>

@endsection


@section('scripts')
 

	<script src="js/hisdb/currentPt/currentPt.js"></script>
	
@endsection