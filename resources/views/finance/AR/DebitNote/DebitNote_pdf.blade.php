@extends('layouts.pdflayout')

@section('title','Debit Note')

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
                <div class="card">
                    <div class="card-body">
                        <p>Debtor Code: {{$dbacthdr->debtorcode}}</p>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">Name: {{$dbacthdr->debt_name}}</p>
                                    <p class="card-text">Address: {{$dbacthdr->cust_address1}} 
                                    <br>{{$dbacthdr->cust_address2}} {{$dbacthdr->cust_address3}} {{$dbacthdr->cust_address4}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">Document No: DN-{{$dbacthdr->auditno}}</p>
                                    <p class="card-text">Date: {{$dbacthdr->entrydate}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td colspan="3"><b>DOCUMENT/DETAILS</b></td>
                                <td colspan="3"><b>REMARKS</b></td>
                                <td><b>AMOUNT</b></td>
                            </tr>
                            <tr>
                                <!-- Remark -->
								<td colspan="6">
									<p>Remark: {{$dbacthdr->remark}}</p>
                                    @foreach ($dbactdtl as $obj)
                                    <p>{{$obj->dept_description}} &nbsp; &nbsp; {{$obj->entrydate}}</p>
                                    @endforeach
								</td>
                                <td> <!-- amount -->
                                    @foreach ($dbactdtl as $obj)
                                        <p>{{number_format($obj->amount,2)}}</p>
                                    @endforeach
                                </td>
                            </tr>					
                        </tbody>
                    </table>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <p><b>Ringgit Malaysia</b></p>
                                    <p><i>{{$totamt_eng}}</i></p>
                                </td>
                                <td colspan="2">
                                    <p><b>Total Amount</b></p>
                                    <p>{{number_format($dbacthdr->amount,2)}}</p>
                                </td>
                            </tr>		
                        </tbody>
                    </table>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <p>Print Date/Time/User: {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')}} &nbsp; {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i')}} &nbsp; BY {{session('username')}}</p>
                            <p>Note:</p>
                            <p>1. Please quote document number when making payments.</p>
                            <p>2. Recipient Copy is to be returned with payment.</p>
                            <p>3. All cheque / money order should be crossed and payable to <br> 
                                &nbsp; {{$company->name}} / ACCOUNT NO: .
                            </p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>

@endsection