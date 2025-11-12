@extends('layouts.main')

@section('title', 'Item Movement Report')

@section('style')
	body{
		background: #00808024 !important;
	}
	.container.mycontainer{
		padding-top: 5%;
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
			<div class="panel panel-default" style="height: 260px;width: 90%;margin: auto;">
				<div class="panel-heading">Update PNL Account</div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12 btnform' style="padding:0px">
					 <form id="formdata">
					 	<div class="col-md-3">
						 	<label for="monthfrom">Month From</label>
							<select id="monthfrom" name="monthfrom" required aria-label="Month" class="form-control" data-validation="required">
							  <option value="" disabled selected>Choose month</option>
							  <option value="1">1</option>
							  <option value="2">2</option>
							  <option value="3">3</option>
							  <option value="4">4</option>
							  <option value="5">5</option>
							  <option value="6">6</option>
							  <option value="7">7</option>
							  <option value="8">8</option>
							  <option value="9">9</option>
							  <option value="10">10</option>
							  <option value="11">11</option>
							  <option value="12">12</option>
							</select>
						</div>

					 	<div class="col-md-3">
						 	<label for="monthto">Month To</label>
							<select id="monthto" name="monthto" required aria-label="Month" class="form-control" data-validation="required">
							  <option value="" disabled selected>Choose month</option>
							  <option value="1">1</option>
							  <option value="2">2</option>
							  <option value="3">3</option>
							  <option value="4">4</option>
							  <option value="5">5</option>
							  <option value="6">6</option>
							  <option value="7">7</option>
							  <option value="8">8</option>
							  <option value="9">9</option>
							  <option value="10">10</option>
							  <option value="11">11</option>
							  <option value="12">12</option>
							</select>
						</div>

					 	<div class="col-md-2">
						 	<label for="monthto">&nbsp;</label>
							<button id="process" type="button" class="btn btn-primary">
								Process
							</button>
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

<script>
$(document).ready(function () {
	$.validate({
      modules : 'sanitize',
      language : {
          requiredFields: 'Please Enter Value'
      },
  });
  
  var errorField=[];
  conf = {
      onValidate : function($form) {
          if(errorField.length>0){
              show_errors(errorField,'#formdata');
              return [{
                  element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
                  message : ''
              }];
          }
      },
  };

  $('#process').click(function(){
	  if($('#formdata').isValid({requiredFields:''},conf,true)){
	  	$('#process').prop('disabled',true);
	  	$.get( './updPNLAccount/table?action=process&monthfrom='+$('#monthfrom').val()+'&monthto='+$('#monthto').val(), function( data ) {
				
	  		$('#process').prop('disabled',false);
			},'json').done(function(data) {
	  		$('#process').prop('disabled',false);
			});
	  }

  });
});
		
</script>

@endsection