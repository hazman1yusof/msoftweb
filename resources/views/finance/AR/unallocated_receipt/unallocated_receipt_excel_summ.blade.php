<table>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">{{$comp_name}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Unallocated Receipt</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">As at : {{$date_at}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Date</td>
        <td style="font-weight:bold; text-align: left">Receipt No</td>
        <td style="font-weight:bold; text-align: left">Payer</td>
        <td style="font-weight:bold; text-align: left">Name</td>
        <td style="font-weight:bold; text-align: right">Amount</td>
        <td style="font-weight:bold; text-align: right">Outstanding</td>
    </tr>     
    @foreach ($dbacthdr as $obj)
    @if($obj->pamt != 0)
    <tr>
        <td>{{$obj->posteddate}}</td>
        <td>{{$obj->recptno}}</td>
        <td>{{$obj->payercode}}</td>
        <td>{{$obj->dm_name}}</td>
        <td>{{$obj->amount}}</td>
        <td>{{$obj->pamt}}</td>
    </tr>
    @endif
    @endforeach
    <tr></tr>
</table>