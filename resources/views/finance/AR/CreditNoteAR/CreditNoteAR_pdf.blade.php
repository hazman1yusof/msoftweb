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
                            <p>Address <span style="margin-left: 27px;"> : &nbsp; {{$dbacthdr->cust_address1}} <br> 
                                        <span style="display: inline-block; margin-left: 88px;"> {{$dbacthdr->cust_address2}} <br> 
                                        {{$dbacthdr->cust_address3}} <br> 
                                        {{$dbacthdr->cust_address4}} 
                            </p>
                        </td>
                        <td>
                            <p>C/N No <span style="margin-left: 36px;"> : &nbsp; CN-{{str_pad($dbacthdr->auditno, 5, "0", STR_PAD_LEFT)}}</p>
                            <p>Date <span style="margin-left: 50px;"> : &nbsp; {{\Carbon\Carbon::parse($dbacthdr->entrydate)->format('d/m/Y')}}</p>
                            <p>Time <span style="margin-left: 47px;"> : &nbsp; {{\Carbon\Carbon::parse($dbacthdr->entrytime)->format('H:i:s')}}</p>
                            <p>Cashier <span style="margin-left: 34px;"> : &nbsp; {{$dbacthdr->entryuser}}</p>
                            <p>Pay Mode <span style="margin-left: 37px;"> : &nbsp; {{$dbacthdr->paymode}}</p>
                            <p>Amount <span style="margin-left: 33px;"> : &nbsp; {{$dbacthdr->amount}}</p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="products p-2">
                    <p>Being credit note for:</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td colspan="3"><b>DESCRIPTION</b></td>
                                <td colspan="4"><b>PARTICULAR</b></td>
                                <td><b>AMOUNT</b></td>
                            </tr>
                            <tr>
                                <!-- DESCRIPTION -->
								<td colspan="3">
                                    @foreach ($dballoc as $obj)
                                    <p>{{$obj->reftrantype}}-{{str_pad($obj->refauditno, 5, "0", STR_PAD_LEFT)}} dated {{\Carbon\Carbon::parse($obj->entrydate_hdr)->format('d/m/Y')}}</p>
                                    @endforeach
								</td>
                                <!-- PARTICULAR -->
								<td colspan="4">
                                    @if ($dballoc_dtl->reftrantype == 'DN')
                                        <p>{{$dbacthdr->remark}}</p>
                                    @elseif ($dballoc_dtl->reftrantype == 'IN')
                                        <p>{{$dbacthdr->pt_name}}</p>
                                    @endif
								</td>
                                <!-- AMOUNT -->
                                <td>
                                    @foreach ($dballoc as $obj)
                                        <p style="text-align: right;">{{number_format($obj->amount,2)}}</p>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <p>Reference <span style="margin-left: 29px;"> : &nbsp; {{$dbacthdr->reference}}</p>
                        <p>Remark <span style="margin-left: 38px;"> : &nbsp; {{$dbacthdr->remark}}</p>
                        <p>Print Date/Time : &nbsp; {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s')}}</p>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>

@endsection