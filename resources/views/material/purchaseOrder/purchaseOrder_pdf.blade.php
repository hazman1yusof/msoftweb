@extends('layouts.pdflayout')

@section('title','Purchase Order')

@section('body')
	
<table class="table table-bordered">
		<tbody>
			
			<tr>
				<td colspan="5">
					<img src="./img/MSLetterHead.jpg" height="75px">
				</td>
				<td colspan="6" style="font-size:16px;text-align: center;padding-top: 25px">
					<p><h2>Purchase Order</h2></p>
				</td>
			</tr>
			
			<tr>
				<td colspan="5" rowspan="4" style="padding: 0">
					<p><b>Address To:</b></p> <br>
					<p>{{$supplier->SuppCode}}</p>
					<p>{{$supplier->Name}}</p>
					<p>{{$supplier->Addr1}}</p>
					<p>{{$supplier->Addr2}}</p>
					<p>{{$supplier->Addr3}}</p>
					<p>{{$supplier->Addr4}}</p>
				</td>
				<td colspan="2"><b>Purchase No.</b></td>
				<td colspan="4">{{$purordhd->prdept}}{{str_pad($purordhd->purordno, 9, '0', STR_PAD_LEFT)}}</td>
				<!-- <td colspan="3">{{$purordhd->purordno}}</td> -->
			</tr>
			
			<tr>
				<td colspan="2"><b>Purchase Date</b></td>
				<td colspan="4">{{\Carbon\Carbon::createFromFormat('Y-m-d',$purordhd->purdate)->format('d-m-Y')}}</td>
			</tr>
			
			<tr>
				<td colspan="2"><b>Contact No.</b></td>
				<td colspan="4"></td>
			</tr>

			<tr>
				<td colspan="2"><b>Page No.</b></td>
				<td colspan="4"></td>
			</tr>

			<tr>
				<td><b>No.</b></td>
				<td colspan="4"><b>Description</b></td>
				<td><b>Uom</b></td>
				<td><b>Quantity</b></td>
				<td><b>Unit Price</b></td>
				<td><b>Tax Amt</b></td>
				<td><b>Discount<br>Amount</b></td>
				<td><b>Nett<br>Amount</b></td>
			</tr>

			<tr>
				<td height=340>
					@foreach ($purorddt as $index=>$obj)
						<p>{{++$index}}</p>
					@endforeach
				</td>
				<td colspan="4"> <!-- description -->
					@foreach ($purorddt as $obj)
						<p>{{$obj->description}}</p>
						<p>{{$obj->remarks}}</p>
					@endforeach
				</td>
				<td> <!-- uomcode -->
					@foreach ($purorddt as $obj)
						<p>{{$obj->uomcode}}</p>
					@endforeach
				</td>
				<td> <!-- quantity -->
					@foreach ($purorddt as $obj)
						<p>{{$obj->qtyorder}}</p>
					@endforeach
				</td>
				<td> <!-- unit price -->
					@foreach ($purorddt as $obj)
						<p>{{number_format($obj->unitprice,2)}}</p>
					@endforeach
				</td>
				<td> <!-- tax amount -->
					@foreach ($purorddt as $obj)
						<p>{{number_format($obj->tot_gst,2)}}</p>
					@endforeach
				</td>
				<td> <!-- disc amount -->
					@foreach ($purorddt as $obj)
						<p>{{number_format($obj->amtdisc,2)}}</p>
					@endforeach
				</td>
				<td> <!-- amount -->
					@foreach ($purorddt as $obj)
						<p>{{number_format($obj->amount,2)}}</p>
					@endforeach
				</td>
			</tr>

			<tr>
				<td colspan="5">
					<p><b>Total Amount</b></p>
				</td>
				<td> 
				</td>
				<td>
				</td>
				<td>
				</td>
				<td> <!-- total tax amount -->
					<p>{{number_format($purordhd->perdisc,2)}}</p>
				</td>
				<td> <!-- total disc amount -->
					<p>{{number_format($purordhd->amtdisc,2)}}</p>
				</td>
				<td> <!-- total nett amount -->
					<p>{{number_format($purordhd->totamount,2)}}</p>
				</td>
			</tr>

			<tr>
				<td colspan="11">
					<p><b>Ringgit Malaysia</b></p>
					<p><i>{{$totamt_bm}}</i></p>
				</td>
			</tr>

			<tr>
				<td colspan="6" rowspan="2">
					<p><i>Please Deliver goods/services/works with original purchase order, delivery order and invoice to:</i></p>
					<p><b>Address</b></p>
					<p>{{$deldept->description}}</p>
					<p>{{$deldept->Addr1}}</p>
					<p>{{$deldept->Addr2}}</p>
					<p>{{$deldept->Addr3}}</p>
					<p>{{$deldept->Addr4}}</p><br>

					<p><b>Contact Person</b></p>
					<p>{{$deldept->contactper}}</p><br>

					<p><b>Tel No.</b></p>
					<p><b>Email</b></p>
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