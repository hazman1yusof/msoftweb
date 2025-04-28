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
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">Src</td>
        <td style="font-weight: bold">Type</td>
        <td style="font-weight: bold">Post Date</td>
        <td style="font-weight: bold">Cost Code</td>
        <td style="font-weight: bold">Double Entry Acct</td>
        <td style="font-weight: bold">Reference</td>
        <td style="font-weight: bold">Description</td>
        <td style="font-weight: bold">Debit</td>
        <td style="font-weight: bold">Credit</td>
        <td style="font-weight: bold">Balance</td>
        <td style="font-weight: bold">Document No</td>
        <td style="font-weight: bold">Units</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->source}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->postdate}}</td>
            <td>{{$obj->costcode}}</td>
            <td>{{$obj->acccode}}</td>
            <td>{{$obj->reference}}</td>
            <td>{{$obj->description}}</td>
            <td>{{$obj->dramount}}</td>
            <td>{{$obj->cramount}}</td>
            <td>{{$obj->cramount}}</td>
            <td>{{$obj->auditno}}</td>
            <td>{{$obj->auditno}}</td>
        </tr>
    @endforeach
    <tr></tr>
</table>