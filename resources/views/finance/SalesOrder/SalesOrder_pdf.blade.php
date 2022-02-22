@extends('layouts.pdflayout')

@section('title','Sales Order')

@section('style')

hr {
    color: #0000004f;
    margin-top: 5px;
    margin-bottom: 5px
}

.add td {
	color: #c5c4c4;
	margin-top: 5px;
	margin-bottom: 5px;
}

.content {
    font-size: 14px
}
@endsection

@section('body')

<div class="container mt-5 mb-3">
	<h1>{{$title}}</h1>
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
				<p style="text-align:center;"><img src="{{url('/img/logo.jpg')}}" alt="Logo" height="75px"></p>
				<div class="d-flex flex-column"> 
					<p style="text-align:center">{{$company->name}}</p>
					<p style="text-align:center">{{$company->address1}}</p>
					<p style="text-align:center">{{$company->address2}}{{$company->address3}}</p>
					<p style="text-align:center">{{$company->address4}}</p>
				</div>
            </div>
                <hr>
			<div class="table-responsive p-2">
				<table class="table table-borderless">
					<tbody>
						<tr class="add">
							<td colspan="3"><b>Address To</b></td>
							<td><b>Document Number</b></td>
							<td><b>PO Date</b></td>
							<td><b>MRN</b></td>
							<td><b>Invoice No</b></td>
							<td><b>Invoice Date</b></td>
						</tr>
						<tr class="content">
							<td colspan="3" class="font-weight-bold">
								<p>{{$dbacthdr->debt_name}}</p>
								<p>{{$dbacthdr->cust_address1}}</p>
								<p>{{$dbacthdr->cust_address2}}</p>
								<p>{{$dbacthdr->cust_address3}}</p>
								<p>{{$dbacthdr->cust_address4}}</p>	
							</td>
							<td> <!-- auditno -->
								<p>{{str_pad($dbacthdr->auditno, 7, '0', STR_PAD_LEFT)}}</td>			
							</td>
							<td> <!-- podate -->
								<p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->podate)->format('d-m-Y')}}</p>			
							</td>
							<td> <!-- MRN -->
								<p>{{$dbacthdr->mrn}}</p>			
							</td>
							<td> <!-- invoiceno -->
								<p>{{str_pad($dbacthdr->invno, 7, '0', STR_PAD_LEFT)}}</td>					
							</td>
							<td> <!-- invoicedate -->
								<p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->entrydate)->format('d-m-Y')}}</p>			
							</td>
						</tr>
					</tbody>
				</table>
			</div>
            <hr>
			<div class="products p-2">
				<table class="table table-borderless">
					<tbody>
						<tr>
							<td colspan="5"><b>Description</b></td>
							<td><b>Uom</b></td>
							<td><b>Quantity</b></td>
							<td><b>Unit Price</b></td>
							<td><b>Tax Amt</b></td>
							<td><b>Amount</b></td>
						</tr>
						<tr >
							<td colspan="5" height="180"> <!-- description of chgcode from hisdb.chgmast-->
								@foreach ($billsum as $obj)
									<p>{{$obj->chgmast_desc}}</p>
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
					</tbody>
				</table>
			</div>

            <hr>
                <div class="address p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="add">
                                <td>ATTENTION</td>
                            </tr>
                            <tr class="content">
                                <td>
									<ol> 
										<li>Payment of this bill can be pay to any registration counter of {{$company->name}} by stating the referral invoice number.</li>
										<li>Payment can be made by cash. </li>
										<li>Only cross cheque for any registered company with Ministry of Health Malaysia is acceptable and be issue to Director of					{{$company->name}}.</li>
									 	<li>Any inquiries must be issue to : <br> 
											<p style="margin-left:10%; margin-right:10%;">{{$company->name}}</p>
											<p style="margin-left:10%; margin-right:10%;">{{$company->address1}}</p>
											<p style="margin-left:10%; margin-right:10%;">{{$company->address2}} {{$company->address3}}</p>
											<p style="margin-left:10%; margin-right:10%;">{{$company->address4}}</p></li>

									</ol>
								</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection