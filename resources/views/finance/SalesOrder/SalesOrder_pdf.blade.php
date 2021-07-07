@extends('layouts.pdflayout')
@section('title','Sales Order')

@section('body')
	<table class="table table-bordered">
	  <tbody>

	  	<tr>
	      <td colspan="5">
	      	<img src="{{public_path().$company->logo1}}" height="75px">
	      </td>
	      <td colspan="5" style="font-size:16px;text-align: center;padding-top: 40px">
	      		<p><h2>SALES ORDERRRR</h2></p>
	      </td>
	    </tr>

	    <tr>
	      <td colspan="5" rowspan="4" style="padding: 0">
	      		<p><b>Address To.</b></p>
	      		<p>{{$company->address1}}</p>
	      		<p>{{$company->address2}}</p>
	      		<p>{{$company->address3}}</p>
	      		<p>{{$company->address4}}</p>
	    		
	      </td>
	      <td colspan="2"><b>PO Number</b></td>
	      <td colspan="3">{{$dbacthdr->ponum}}</td>
	    </tr>
	    <tr>
	      <td colspan="2"><b>PO Date</b></td>
	      <td colspan="3">{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->podate)->format('d-m-Y')}}</td>
	    </tr>
	    <tr>
		<td colspan="2"><b>MRN</b></td>
	      <td colspan="3">{{$dbacthdr->mrn}}</td>
	    </tr>
	    <tr>
	      <td colspan="2"><b>Page No.</b></td>
	      <td colspan="3">1/1</td>
	    </tr>
	    <tr>
	    	<td><b>No.</b></td>
	    	<td colspan="4"><b>Description</b></td>
	    	<td><b>Uom</b></td>
	    	<td><b>Quantity</b></td>
	    	<td><b>Unit Price</b></td>
	    	<td><b>Tax Amt</b></td>
	    	<td><b>Amount</b></td>
	    </tr>

	    <tr >
	    	<!-- <td height=320>
	    		@foreach ($billsum as $index=>$obj)
	    			<p>{{++$index}}</p>
	    		@endforeach
	    	</td> -->
	    	<td colspan="4"> <!-- description -->
	    		@foreach ($billsum as $obj)
	    			<p>{{$obj->description}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- uomcode -->
	    		@foreach ($billsum as $obj)
	    			<p>{{$obj->uom}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- quantity -->
	    		@foreach ($billsum as $obj)
	    			<p>{{$obj->quantity}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- unit price -->
	    		@foreach ($billsum as $obj)
	    			<p>{{number_format($obj->unitprice,2)}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!--tax amount -->
	    		@foreach ($billsum as $obj)
	    			<p>{{number_format($obj->taxamt,2)}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- amount -->
	    		@foreach ($billsum as $obj)
	    			<p>{{number_format($obj->amount,2)}}</p>
	    		@endforeach
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="5">
	    		<p><b>Ringgit Malaysia</b></p>
	    		<p><i>{{$totamt_bm}}</i></p>
	    	</td>
	    	<td colspan="5">
	    		<p><b>Total Amount</b></p>
	    		<p>{{number_format($dbacthdr->amount,2)}}</p>
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="5" rowspan="2">
	    		<p><i>Please Deliver goods/services/works with original purchase order, delivery order and invoice to:</i></p>
	    		<p><b>Address</b></p>
	    		<p>&nbsp;</p>
	    		<p>&nbsp;</p>
	    		<p>&nbsp;</p>
	    		<p><b>Contact Person</b></p>
	    		<p>&nbsp;</p>
	    		<p><b>Tel No.</b></p>
	    		<p>&nbsp;</p>
	    		<p><b>Email</b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    	<td colspan="3" height="10">
	    		<p><b>Delivered By</b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    	<td colspan="2" height="10">
	    		<p><b>Approval</b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="5">
	    		<p><b>Sign: </b></p>
	    		<p>&nbsp;</p>
	    		<p><b>Position: </b></p>
	    		<p>&nbsp;</p>
	    		<p><b>Date: </b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    </tr>


	  </tbody>
	</table>

@endsection