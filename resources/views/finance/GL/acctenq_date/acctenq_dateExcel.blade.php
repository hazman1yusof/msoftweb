<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">{{$compname}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">GL TRANSACTION REPORT</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">GL Account : {{$glaccount}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Date From {{$fromdate}} to {{$todate}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">Src</td>
        <td style="font-weight: bold">Type</td>
        <td style="font-weight: bold">Post Date</td>
        <td style="font-weight: bold">Double Entry Cost Code</td>
        <td style="font-weight: bold">Double Entry Acct</td>
        <td style="font-weight: bold">Cost Code</td>
        <td style="font-weight: bold">Reference</td>
        <td style="font-weight: bold">Description</td>
        <td style="font-weight: bold">Debit</td>
        <td style="font-weight: bold">Credit</td>
        <td style="font-weight: bold">Balance</td>
        <td style="font-weight: bold">Document No</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">{{$firstDay}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight: bold">Opening</td>
        <td></td>
        <td></td>
        <td style="font-weight: bold">{{$total_openbal}}</td>
        <td></td>
    </tr>

    @php($total = $total_openbal)
    @php($total_dr = 0)
    @php($total_cr = 0)
    @foreach ($table as $obj)
        @if(!empty(floatval($obj->dramount)))
            @php($total = $total + $obj->dramount)
            @php($total_dr = $total_dr + $obj->dramount)
        @else
            @php($total = $total - $obj->cramount)
            @php($total_cr = $total_cr + $obj->cramount)
        @endif
        <tr>
            <td>{{$obj->source}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->postdate}}</td>
            <td>{{$obj->costcode}}</td>
            <td>{{$obj->acccode}}</td>
            <td>{{$obj->costcode_}}</td>
            <td>{{$obj->reference}}</td>
            <td>{{$obj->description}}</td>
            <td>{{$obj->dramount}}</td>
            <td>{{$obj->cramount}}</td>
            <td>{{$total}}</td>
            <td>{{$obj->auditno}}</td>
        </tr>
    @endforeach
    <tr></tr>
    
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight: bold">{{$total_dr}}</td>
        <td style="font-weight: bold">{{$total_cr}}</td>
        <td style="font-weight: bold">{{$total}}</td>
        <td></td>
    </tr>

</table>