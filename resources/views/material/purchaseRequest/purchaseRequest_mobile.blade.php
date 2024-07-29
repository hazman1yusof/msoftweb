@extends('layouts.authdtl_mobile')

@section('title', 'Purchase Request Mobile')

@section('css')
<style>
	.ui.segment, .ui.segments .segment {
    font-size: 0.85rem;
	}
	#main_container{
		padding-top: 10px;
	}
	#main_segment{
		height: 80vh;
	}
	#mygrid span{
		padding-left: 4px;
	}
	#mygrid .column{
		padding: 5px 15px;
	}
	#mygrid .column.cont1{
		padding: 5px 0px 5px 15px;
	}
	#mygrid .column.cont2{
		padding: 5px 15px 5px 0px;
	}
	#mygrid .row{
		padding: 0px;
	}
</style>
@endsection

@section('content')
<div id="main_container" class="ui container">
	<div class="ui segments" id="main_segment">
	  <div class="ui secondary segment" id="main_title">
	    <b>Purchase Request</b>
	  </div>
	  <div class="ui attached segment" id="main_segment" style="overflow:scroll;">
	  	<h3>Header</h3>
	  	<div class="ui grid" id="mygrid">
	  		<div class="row">
		  		<div class="eight wide column ">
		  		 <b>Record No:</b><span>{{$purreqhd->recno}}</span>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <b>Request No:</b><span>{{$purreqhd->purreqno}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="eight wide column cont1">
		  			<b>Request Department:</b>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <span>{{$purreqhd->reqdept_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="eight wide column cont1">
		  			<b>Purchase Department:</b>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <span>{{$purreqhd->prdept_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="four wide column cont1">
		  			<b>Supplier:</b>
		  		</div>
		  		<div class="twelve wide column cont2">
		  		 <span>{{$purreqhd->suppcode_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="eight wide column">
		  		 <b>Date:</b><span>{{\Carbon\Carbon::parse($purreqhd->purreqdt)->format('d-m-Y')}}</span>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <b>Total Amount:</b><span>{{$purreqhd->totamount}}</span>
		  		</div>
	  		</div>
	  	</div>

	  	<div class="ui divider"></div>
	  	<h3>Detail</h3>
	  </div>
		<div class="ui two bottom attached buttons">
		    <div class="ui negative button">Reject</div>
		    <div class="ui positive button">Support</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
</script>
@endsection