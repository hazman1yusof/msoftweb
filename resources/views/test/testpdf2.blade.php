@extends('layouts.pdflayout')


@section('body')
	<table class="table table-bordered">
	  <tbody>

	  	<tr>
	      <td colspan="4">
	      	<img src="http://laravel.test/img/MSLetterHead.jpg" height="75px">
	      </td>
	      <td colspan="4" style="text-align: center;">
	      		<p><h2>Purchase Order</h2></p>
	      </td>
	    </tr>

	    <tr>
	      <td colspan="4" rowspan="4" style="padding: 0">
	      		<p><b>Address To.</b></p>
	    		<p>1</p>
	    		<p>2</p>
	    		<p>3</p>
	    		<p>4</p>
	    		<p>5</p>
	      </td>
	      <td colspan="2"><b>Purchase No.</b></td>
	      <td colspan="2">{{$purreqhd->purreqno}}</td>
	    </tr>
	    <tr>
	      <td colspan="2"><b>Purchase Date</b></td>
	      <td colspan="2">{{\Carbon\Carbon::createFromFormat('Y-m-d',$purreqhd->purreqdt)->format('d-m-Y')}}</td>
	    </tr>
	    <tr>
	      <td colspan="2"><b>Contract No.</b></td>
	      <td colspan="2"></td>
	    </tr>
	    <tr>
	      <td colspan="2"><b>Page No.</b></td>
	      <td colspan="2"></td>
	    </tr>
	    <tr>
	    	<td><b>No.</b></td>
	    	<td colspan="3"><b>Description</b></td>
	    	<td><b>Uom</b></td>
	    	<td><b>Quantity</b></td>
	    	<td><b>Unit Price</b></td>
	    	<td><b>Amount</b></td>
	    </tr>

	    <tr >
	    	<td height=330>
	    		<p>1</p>
	    		<p>2</p>
	    		<p>3</p>
	    		<p>4</p>
	    		<p>5</p>
	    		<p>6</p>
	    		<p>7</p>
	    		<p>8</p>
	    		<p>9</p>
	    		<p>10</p>
	    		<p>11</p>
	    		<p>12</p>
	    		<p>13</p>
	    		<p>14</p>
	    		<p>15</p>
	    		<p>16</p>
	    		<p>17</p>
	    		<p>18</p>
	    		<p>19</p>
	    		<p>20</p>
	    		<p>21</p>
	    		<p>22</p>
	    		<p>23</p>
	    		<p>24</p>
	    		<p>25</p>
	    	</td>
	    	<td colspan="3"> <!-- description -->
	    		@foreach ($purreqdt as $obj)
	    			<p>{{$obj->description}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- uomcode -->
	    		@foreach ($purreqdt as $obj)
	    			<p>{{$obj->uom_desc}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- quantity -->
	    		@foreach ($purreqdt as $obj)
	    			<p>{{$obj->qtyrequest}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- unit price -->
	    		@foreach ($purreqdt as $obj)
	    			<p>{{number_format($obj->unitprice,2)}}</p>
	    		@endforeach
	    	</td>
	    	<td> <!-- amount -->
	    		@foreach ($purreqdt as $obj)
	    			<p>{{number_format($obj->amount,2)}}</p>
	    		@endforeach
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="4">
	    		<p><b>Ringgit Malaysia</b></p>
	    		<p><i>{{$totamt_bm}}</i></p>
	    	</td>
	    	<td colspan="4">
	    		<p><b>Total Amount</b></p>
	    		<p>{{number_format($purreqhd->totamount,2)}}</p>
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="4" rowspan="2">
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
	    	<td colspan="2" height="10">
	    		<p><b>Delivered By</b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    	<td colspan="2" height="10">
	    		<p><b>Approval</b></p>
	    		<p>&nbsp;</p>
	    	</td>
	    </tr>

	    <tr>
	    	<td colspan="4">
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