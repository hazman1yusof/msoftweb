@extends('layouts.pdflayout')

@section('title','Debit Note AR')

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

.tdnoborder td{
    border:none;
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
                <table class="table table-borderless tdnoborder">
                    <tr><td><p>Debtor Code &nbsp; : &nbsp; {{$dbacthdr->debtorcode}}</p></td></tr>
                    <tr>
                        <td width="70%">
                            <p>Name <span style="margin-left: 39px;"> : &nbsp; {{$dbacthdr->debt_name}}</p>
                            <p>Address <span style="margin-left: 27px;"> : &nbsp; {{$dbacthdr->cust_address1}} <br> 
                                        <span style="display: inline-block; margin-left: 88px;"> {{$dbacthdr->cust_address2}} <br> 
                                        {{$dbacthdr->cust_address3}} <br> 
                                        {{$dbacthdr->cust_address4}} 
                            </p>
                        </td>
                        <td>
                            <p>Document No &nbsp; : &nbsp; DN-{{str_pad($dbacthdr->auditno, 5, "0", STR_PAD_LEFT)}}</p>
                            <p>Date <span style="margin-left: 50px;"> : &nbsp; {{\Carbon\Carbon::parse($dbacthdr->entrydate)->format('d/m/Y')}}</p>
                            <p>Reference No &nbsp; : &nbsp; {{$dbacthdr->reference}}</p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td colspan="6"><b>DOCUMENT/DETAILS <span style="margin-left: 85px;"> REMARKS</b></td>
                                <td><b>AMOUNT</b></td>
                            </tr>
                            <tr>
                                <!-- REMARKS,DEPT,DATE -->
								<td colspan="6">
									<p>REMARKS: {{$dbacthdr->remark}}</p>
                                    @foreach ($dbactdtl as $obj)
                                    <p>{{$obj->dept_description}} &nbsp; &nbsp; ({{\Carbon\Carbon::parse($obj->entrydate)->format('d/m/Y')}})</p>
                                    @endforeach
								</td>
                                <!-- AMOUNT -->
                                <td>
                                    <br>
                                    @foreach ($dbactdtl as $obj)
                                        <p style="text-align: right;">{{number_format($obj->amount,2)}}</p>
                                    @endforeach
                                </td>
                            </tr>					
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-borderless tdnoborder">
                        <tbody>
                            <tr>
                                <td colspan="7"></td>
                                <td colspan="3">
                                    <p><b><span style="margin-left: 80px;">Total</b></p>
                                </td>
                                <td>
                                    <p style="text-align: right;">{{number_format($dbacthdr->amount,2)}}</p>
                                </td>
                            </tr>		
                        </tbody>
                    </table>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <p>Print Date/Time/User:&nbsp; {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s')}} BY: {{session('username')}}</p>
                            <p>Note:</p>
                            <p>1. Please quote document number when making payments.</p>
                            <p>2. Recipient Copy is to be returned with payment.</p>
                            <p>3. All cheque / money order should be crossed and payable to <br> 
                                &nbsp; &nbsp; {{$company->name}} / ACCOUNT NO: {{$sysparam->pvalue2}}.
                            </p>
                            <p>4. This invoice must be paid within 14 days after its date of issue.</p>
                            <p>5. Please ignore this invoice if payment has been made.</p>
                            <p>6. Please inform us with payment proof for EFT / direct payment.</p>
                            <br>
                            <p>THIS IS COMPUTER GENERATED DOCUMENT. NO SIGNATURE IS REQUIRED.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>

@endsection