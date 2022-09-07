@extends('hisdb.mykadfp.mykadfpmain')

@section('style')

@endsection

@section('css')

@endsection

@section('title', 'Biometric Scanner')

@section('content')
<script type="text/javascript">
	var mykadscantype = null
	function setscantype(type){
		$('#biometric_menu,#mykad_menu').hide();
		mykadscantype = type;
		if(type=='mykad'){
			$('#mykad_menu').show();
		}else if(type=='biometric'){
			$('#biometric_menu').show();
		}
	}
</script>

<div class="ui center aligned grid">

	<div class="row">
	<div class="ten wide column">
    <div class="ui teal attached segment">
    	<div class="ui menu" id="biometric_menu" style="display:none;">
		  <div class="right menu">
		    <a class="header item" id="readbiometric">
		      Scan Biometric
		    </a>
		    <a class="item" name="download">
		      Download PDF
		    </a>
		    <a class="item" name="closemodalfp">
		      <i class="times icon"></i>Close
		    </a>
		    </div>
		</div>

		<div class="ui menu" id="mykad_menu" style="display:none;">
		  <div class="right menu">
		    <a class="header item" id="readmykad">
		      Scan Mykad
		    </a>
		    <a class="header item" id="readmykid">
		      Scan Mykid
		    </a>
		    <a class="header item" name="download">
		      Download PDF
		    </a>
		    <a class="header item" name="closemodalfp">
		      <i class="times icon"></i>Close
		    </a>
		    </div>
		</div>

    	<input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">
    	<form class="ui form" id="myform">

    	<div class="ui grid">
			<div class="five wide column">
	    		<div class="ui rounded small image bordered">
				  <img src="{{ asset('img/no-image.gif') }}" id="image" defaultsrc="{{ asset('img/no-image.gif') }}">
				</div>
			</div>
			<div class="ten wide column">
			      <div class="field">
			    	<label>Full Name</label>
			        <input type="text" readonly name="name" placeholder="Full Name">
			      </div>
			      <div class="two fields">
				      <div class="field">
				    	<label>MyKad Number</label>
				        <input type="text" readonly name="icnum" placeholder="MyKad Number">
				      </div>
				      <div class="field">
				    	<label>Gender</label>
				        <input type="text" readonly name="gender" placeholder="Gender">
				      </div>
			      </div>
			      <div class="two fields">
				      <div class="field">
				    	<label>Date Of Birth</label>
				        <input type="text" readonly name="dob" placeholder="Date Of Birth">
				      </div>
				      <div class="field">
				    	<label>Birth Place</label>
				        <input type="text" readonly name="birthplace" placeholder="Birth Place">
				      </div>
			      </div>

			</div>
    	</div>

		  <div class="field" style="padding-top:15px;">
		    <div class="three fields">
		      <div class="field">
		    	<label>Race</label>
		        <input type="text" readonly name="race" placeholder="Race">
		      </div>
		      <div class="field">
		    	<label>Citizenship</label>
		        <input type="text" readonly name="citizenship" placeholder="Citizenship">
		      </div>
		      <div class="field">
		    	<label>Religion</label>
		        <input type="text" readonly name="religion" placeholder="Religion">
		      </div>
		    </div>
		  </div>

		  <h4 class="ui dividing header">Address</h4>
		  <div class="field">
		    <div class="inline fields">
		      <div class="eleven wide field">
		        <input type="text" readonly name="address1" placeholder="Address 1">
		      </div>
		      <div class="five wide field"><label>City</label>
		        <input type="text" readonly name="city" placeholder="City">
		      </div>
		    </div>
	      </div>
		  <div class="field">
		    <div class="inline fields">
		      <div class="eleven wide field">
		        <input type="text" readonly name="address2" placeholder="Address 2">
		      </div>
		      <div class="five wide field"><label>State</label>
		        <input type="text" readonly name="state" placeholder="State">
		      </div>
		    </div>
	      </div>
		  <div class="field">
		    <div class="inline fields">
		      <div class="eleven wide field">
		        <input type="text" readonly name="address3" placeholder="Address 3">
		      </div>
		      <div class="five wide field"><label>Postcode</label>
		        <input type="text" readonly name="postcode" placeholder="Postcode">
		      </div>
		    </div>
	      </div>
		      

		</form>

    </div>
	</div>
	</div>


	<div class="ui basic modal" id="read">
	  <div class="ui icon header">
	  	<i class="violet fingerprint icon" style="display:none"></i>
	    <i class="red times icon" style="display:none"></i>
	  	<i class="green check icon" style="display:none"></i>
	    <i class="yellow exclamation triangle icon" style="display:none"></i>
	    <span id="msg">Processing.. Please Wait..</span>
	  </div>
	</div>
<!-- 
	<div class="ui basic modal" id="success">
	  <div class="ui icon header">
	    <i class="green check icon"></i>Success : <span id="succmsg"></span>
	  </div>
	</div>

	<div class="ui basic modal" id="fail">
	  <div class="ui icon header">
	    <i class="red times icon"></i>Fail : <span id="failmsg"></span>
	  </div>
	</div> -->

</div>

@endsection

@section('scripts')
		<script src="js/hisdb/mykadfp/mykadFP.js"></script>
		<script src="plugins/printthis/printThis.js"></script>
@endsection