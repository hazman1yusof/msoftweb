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
		height: 83vh;
	}
	#mygrid span{
/*		padding-left: 4px;*/
	}
	#mygrid .column{
		padding: 5px 0px;
	}
	#mygrid .column.cont1{
		padding: 5px 0px 5px 0px;
	}
	#mygrid .column.cont2{
		padding: 5px 0px 5px 0px;
	}
	#mygrid .row{
		padding: 0px;
	}
	.ui.grid {
	   margin: 0rem; 
	}
	span.span_txt{
		color: #0057b1;
    display: block;
    margin-top: -5px;
	}
</style>
@endsection

@section('content')
<div id="main_container" class="ui container">
	<div class="ui segments" id="main_segment">
	  <div class="ui secondary segment" id="main_title">
	    <h3>Purchase Request</h3>
	  </div>
	  <div class="ui attached segment" id="main_segment" style="overflow:auto;">
	  	<h3>Header</h3>
	  	<div class="ui grid" id="mygrid">
	  		<div class="row">
		  		<div class="eight wide column ">
		  		 <b>Record No:</b><span> {{str_pad($purreqhd->recno, 7, '0', STR_PAD_LEFT)}}</span>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <b>Request No:</b><span> {{str_pad($purreqhd->purreqno, 7, '0', STR_PAD_LEFT)}}</span>
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
		  		<div class="three wide column cont1">
		  			<b>Supplier:</b>
		  		</div>
		  		<div class="twelve wide column cont2">
		  		 <span>{{$purreqhd->suppcode_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="three wide column cont1">
		  			<b>Remarks:</b>
		  		</div>
		  		<div class="twelve wide column cont2">
		  		 <span>{{$purreqhd->remarks}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="eight wide column">
		  		 <b>Date:</b><span> {{\Carbon\Carbon::parse($purreqhd->purreqdt)->format('d-m-Y')}}</span>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <b>Total Amount:</b><span> {{number_format($purreqhd->totamount,2)}}</span>
		  		</div>
	  		</div>
	  	</div>
	  	<div class="ui divider"></div>
	  	<h3>Detail</h3>
	  	<table class="ui selectable celled striped table">
			  <tbody>
			  	@foreach($purreqdt as $purdt)
			    <tr>
			      <td><b>Line No.</b> <span class="span_txt">{{$purdt->lineno_}}</span></td>
			      <td><b>Item Code</b> <span class="span_txt">{{$purdt->itemcode}}</span></td>
			      <td><b>Description</b> <span class="span_txt">{{$purdt->itemcode_desc}}</span></td>
			      <td><b>UOM</b> <span class="span_txt">{{$purdt->uomcode}} - {{$purdt->uom_desc}}</span></td>
			      <td><b>PO UOM</b> <span class="span_txt">{{$purdt->pouom}} - {{$purdt->pouom_desc}}</span></td>
			      <td><b>Quantity Request</b> <span class="span_txt">{{$purdt->qtyrequest}}</span></td>
			      <td><b>Unit Price</b> <span class="span_txt">{{number_format($purdt->unitprice,2)}}</span></td>
			      <td><b>Total Amount</b> <span class="span_txt">{{number_format($purdt->totamount,2)}}</span></td>
			      <td><b>remarks</b> <span class="span_txt">{{$purdt->remarks}}</span></td>
			    </tr>
			    @endforeach
			  </tbody>
			</table>
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