@extends('layouts.pdflayout')

@section('title','Purchase Order')

@section('style')

@page { 
	/* set page margins */ 
	margin: 0.5cm; 
	margin-top: 48px; 
}
.hideheadertop {
	width: 120%;
	text-align: center;
	position: absolute;
	top:-40px;
	left:-10px;
	height:39px;
	background:white;
	z-index:10000;
}
.footer {
	width: 100%;
	text-align: center;
	position: fixed;
	font-size: 8pt;
	bottom: 0px;
}
.header{
	width: 100%;
	text-align: center;
	position: fixed;
	font-size: 8pt;
	top:-39px;
}
.page-break {
    page-break-after: always;
}
.pagenum:before {
    content: "Page " counter(page) " of " counter(pageTotal);
}
.page-break {
	page-break-after:auto;
}
.breakNow { 
	page-break-inside:avoid; 
	page-break-after:always; 
}

@endsection

@section('body')

<div class="footer">
	<span class="pagenum"></span>
</div>

<div class="hideheadertop">
</div>

<div class="header">
	<table class="table table-bordered">
		<tr>  
			<th style="text-align:left"><b>No.</b></th>
			<th colspan="4" style="text-align:left"><b>Description</b></th>
			<th style="text-align:left"><b>UOM</b></th>
			<th style="text-align:left"><b>Quantity</b></th>
			<th style="text-align:left"><b>Unit Price</b></th>
			<th style="text-align:left"><b>Tax Amt</b></th>
			<th style="text-align:left"><b>Discount<br>Amount</b></th>
			<th style="text-align:left"><b>Nett<br>Amount</b></th>
		</tr>
	</table>
</div>

<table class="table table-bordered">
		<tbody>
			<tr id="note">
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
			</tr>
			
			<tr>
				<td colspan="2"><b>Purchase Date</b></td>
				<td colspan="4">{{\Carbon\Carbon::createFromFormat('Y-m-d',$purordhd->purdate)->format('d-m-Y')}}</td>
			</tr>

			<tr>
				<td colspan="2"><b>Contact Person.</b></td>
				<td colspan="4">
					{{$supplier->ContPers}}</td>
			</tr>
			<tr>
				<td colspan="2"><b>Contact No.</b></td>
					
				<td colspan="4">
					{{$supplier->TelNo}}
				</td>
			</tr>
	
			<tr>  
				<th style="text-align:left"><b>No.</b></th>
				<th colspan="4" style="text-align:left"><b>Description</b></th>
				<th style="text-align:left"><b>UOM</b></th>
				<th style="text-align:left"><b>Quantity</b></th>
				<th style="text-align:left"><b>Unit Price</b></th>
				<th style="text-align:left"><b>Tax Amt</b></th>
				<th style="text-align:left"><b>Discount<br>Amount</b></th>
				<th style="text-align:left"><b>Nett<br>Amount</b></th>
			</tr>

			@foreach ($purorddt as $index=>$obj)
				<tr>
					<td >
						<p>{{++$index}}</p>
					</td>
					<td colspan="4"> <!-- description -->
							<p>{{$obj->description}}</p>
							<p style="white-space: pre-wrap;">{{$obj->remarks}}</p> 
					</td>
					<td> <!-- uomcode -->
							<p>{{$obj->uomcode}}</p>
					</td>
					<td style="text-align:right"> <!-- quantity -->
							<p>{{$obj->qtyorder}}</p>
					</td>
					<td style="text-align:right"> <!-- unit price -->
							<p>{{number_format($obj->unitprice,2)}}</p>
					</td>
					<td style="text-align:right"> <!-- tax amount -->
							<p>{{number_format($obj->tot_gst,2)}}</p>
					</td>
					<td style="text-align:right"> <!-- disc amount -->
							<p>{{number_format($obj->amtdisc,2)}}</p>
					</td>
					<td style="text-align:right"> <!-- amount -->
							<p>{{number_format($obj->amount,2)}}</p>
					</td>
				</tr>
			@endforeach
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
				<td style="text-align:right"> <!-- total tax amount -->
					<p>{{number_format($total_tax,2)}}</p>
				</td>
				<td style="text-align:right"> <!-- total disc amount -->
					<p>{{number_format($total_discamt,2)}}</p>
				</td>
				<td style="text-align:right"> <!-- total nett amount -->
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
					<p><i>Please Deliver goods/services/works with original purchase order, delivery order and invoice to:</i></p><br>

					<p><b>Address:</b></p><br>
					<p>{{$deldept->description}}</p>
					<p>{{$deldept->addr1}}</p>
					<p>{{$deldept->addr2}}</p>
					<p>{{$deldept->addr3}}</p>
					<p>{{$deldept->addr4}}</p><br>

					<p><b>Contact Person:</b></p><br>
					<p>{{$deldept->contactper}}</p><br>

					<p><b>Tel No.:</b></p><br>
					<p>{{$deldept->tel}}</p><br>

					<p><b>Email:</b></p><br>
					<p>{{$deldept->email}}</p>
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