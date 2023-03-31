@extends('layouts.pdflayout')

@section('title','Credit Note AR')

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

.tab2 {
    margin-left: 27px;
}

.tab3 {
    display: inline-block;
    margin-left: 88px;
}

.tab4 {
    margin-left: 50px;
}

.tab5 {
    margin-left: 36px;
}

.tab7 {
    margin-left: 38px;
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
                    <tr>
                        <td width="70%">
                            <p>Debtor Code &nbsp; : &nbsp; {{$dbacthdr->debtorcode}}</p>
                            <p>Debtor Name &nbsp;: &nbsp; {{$dbacthdr->debt_name}}</p>
                            <p>Address <span class="tab2"> : &nbsp; {{$dbacthdr->cust_address1}} <br> 
                                        <span class="tab3"> {{$dbacthdr->cust_address2}} <br> 
                                        {{$dbacthdr->cust_address3}} <br> 
                                        {{$dbacthdr->cust_address4}} 
                            </p>
                        </td>
                        <td>
                            <p>C/N No <span class="tab5"> : &nbsp; CN-{{str_pad($dbacthdr->auditno, 5, "0", STR_PAD_LEFT)}}</p>
                            <p>Date <span class="tab4"> : &nbsp; {{\Carbon\Carbon::parse($dbacthdr->entrydate)->format('d/m/Y')}}</p>
                            <p>Time <span class="tab4">: &nbsp; {{\Carbon\Carbon::parse($dbacthdr->entrytime)->format('H:i:s')}}</p>
                            <p>Cashier <span class="tab5">: &nbsp; {{$dbacthdr->entryuser}}</p>
                            <p>C/N By <span class="tab5"> : &nbsp; {{$dbacthdr->paymode}}</p>
                            <p>Amount <span class="tab5">: &nbsp; {{$dbacthdr->amount}}</p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="products p-2">
                    <p>Being credit note for:</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><b>DESCRIPTION</b></td>
                                <td><b>PARTICULAR</b></td>
                                <td><b>AMOUNT</b></td>
                            </tr>
                            <tr>
                                <!-- DESCRIPTION -->
								<td>
                                    @foreach ($dballoc as $obj)
                                    <p>{{$obj->reftrantype}}-{{str_pad($obj->refauditno, 5, "0", STR_PAD_LEFT)}} dated {{\Carbon\Carbon::parse($obj->entrydate_hdr)->format('d/m/Y')}}</p>
                                    @endforeach
								</td>
                                <!-- PARTICULAR -->
								<td>
                                    @if ($dballoc_dtl->reftrantype == 'DN')
                                        <p>{{$dbacthdr->remark}}</p>
                                    @elseif ($dballoc_dtl->reftrantype == 'IN')
                                        <p>{{$dbacthdr->pt_name}}</p>
                                    @endif
								</td>
                                <!-- AMOUNT -->
                                <td>
                                    @foreach ($dballoc as $obj)
                                        <p>{{number_format($obj->amount,2)}}</p>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <p>Reference <span style="margin-left: 29px;"> :&nbsp; {{$dbacthdr->reference}}</p>
                        <p>Remark <span style="margin-left: 38px;"> :&nbsp; {{$dbacthdr->remark}}</p>
                        <p>Print Date/Time :&nbsp; {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s')}}</p>
                    </div>
                </div>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="12%">
                                    <p>Reference</p>
                                    <p>Remark</p>
                                    <p>Print Date/Time</p>
                                </td>
                                <td width="3%">
                                    <p>:</p>
                                    <p>:</p>
                                    <p>:</p>
                                </td>
                                <td width="60%">
                                    <p>{{$dbacthdr->reference}}</p>
                                    <p>{{$dbacthdr->remark}}</p>
                                    <p>{{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s')}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>

@endsection