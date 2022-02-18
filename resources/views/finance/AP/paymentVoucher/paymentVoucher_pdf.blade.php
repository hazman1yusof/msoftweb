@extends('layouts.pdflayout')

@section('title','Payment Voucher')

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
								<td colspan="3"><b>Pay To</b></td>
								<td><b>Voucher Number</b></td>
								<td><b>Paymode</b></td>
								<td><b>Date</b></td>
								<td><b>Bank Acc No</b></td>
                            </tr>
                           
                            <tr class="content">
                                <td colspan="3" class="font-weight-bold">
									<p>{{$apacthdr->suppname}}</p>
									
								</td>
								<td> <!-- voucher no -->
                                	<p>{{$apacthdr->pvno}}</p>			
								</td>
								<td> <!-- paymode -->
									<p>{{$apacthdr->paymode}}</p>			
								</td>
                                <td> <!-- date -->
									<p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$apacthdr->actdate)->format('d-m-Y')}}</p>			
								</td>
								<td> <!-- bank acc no -->
									<p>{{$apacthdr->bankaccno}}</p>				
								</td>
							
                            </tr>
                        </tbody>
                    </table>

					<table class="table table-borderless">
                        <tbody>
							<tr>
								<td colspan="3"><b>Remark:</b></td>
									<p>{{$apacthdr->remarks}}</p>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <tbody>
							<tr>
								<td><b>Invoice Date</b></td>
								<td><b>Invoice No</b></td>
								<td colspan="4"><b>Description</b></td>
								<td><b>Amount</b></td>
							</tr>
							<tr >
								<td> <!-- invoice date -->
									@foreach ($apalloc as $obj)
									<p>{{$obj->allocdate}}</p>
									@endforeach
								</td>
								<td> <!-- invoice no -->
									@foreach ($apalloc as $obj)
										<p>{{$obj->reference}}</p>
									@endforeach
								</td>
								<td colspan="4" height="80"> <!-- description -->
									@foreach ($apalloc as $obj)
										<p>{{$obj->remarks}}</p>
									@endforeach
								</td>
								<td> <!-- amount -->
									@foreach ($apalloc as $obj)
										<p>{{number_format($obj->refamount,2)}}</p>
									@endforeach
								</td>
							</tr>					
                        </tbody>
                    </table>
					<table class="table table-borderless">
                        <tbody>
							<tr>
								<td colspan="2">
									<p><b>Ringgit Malaysia</b></p>
									<p><i>{{$totamt_bm}}</i></p>
								</td>
								<td colspan="2">
									<p><b>Total Amount</b></p>
									<p>{{number_format($apacthdr->amount,2)}}</p>
								</td>
							</tr>		
						</tbody>
					</table>
					<table class="table table-borderless">
                        <tbody>
							<tr>
								<td colspan ="2">
									<p><b>Prepared By:</b></p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>________________</p>
									<p>Name:</p>
								</td>
								<td colspan ="2">
									<p><b>Checked By:</b></p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>________________</p>
									<p>Name:</p>
								</td>
								<td colspan ="2">
									<p><b>Approved By:</b></p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>________________</p>
									<p>Name:</p>
								</td>
								<td colspan ="2">
									<p><b>Signatories:</b></p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>________________</p>
									<p>Name:</p>
								</td>
								<td colspan ="2">
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>________________</p>
									<p>Name:</p>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-borderless">
                        <tbody>
							<tr>
								<td colspan ="4">
									<p><b>Received By:</b></p>
									<p>&nbsp;</p>
									<p>Name:</p>
									<p>&nbsp;</p>
									<p>IC or Passport:</p>
									<p>&nbsp;</p>
									<p>Signature:</p>
									<p>&nbsp;</p>
								</td>
							</tr>
                        </tbody>
                    </table>
					<table class="table table-borderless">
                        <tbody>
							<tr>
								<td colspan ="4">
									<p><b>DR</b> {{$apacthdr->suppcode}} {{$apacthdr->suppname}} {{number_format($apacthdr->amount,2)}}</p>
									<p><b>CR</b> {{$apacthdr->bankcode}} {{$apacthdr->bankname}} {{number_format($apacthdr->amount,2)}}</p>
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