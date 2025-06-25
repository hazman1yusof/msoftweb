<table>
    <tr></tr>
    @foreach($debtormast as $index => $debtor)
    <tr>
        <td style="font-weight: bold">CODE</td>
        <td colspan="3" style="font-weight: bold">:  {{$debtor->debtorcode}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold">NAME</td>
        <td colspan="3" style="font-weight: bold">:  {{$debtor->name}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold">ADDRESS</td>
        <td colspan="5" style="font-weight: bold">:  {{$debtor->address1}} {{$debtor->address2}} {{$debtor->address3}} {{$debtor->address4}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold;text-align: left">DOC DATE</td>
        <td style="font-weight: bold;text-align: left">DATE SEND</td>
        <td style="font-weight: bold;text-align: left">DOCUMENT</td>
        <td style="font-weight: bold;text-align: left">REMARK</td>
        <td style="font-weight: bold;text-align: right">BALANCE AMOUNT</td>
        <td style="font-weight: bold;text-align: right">TOTAL</td>
        <td style="font-weight: bold;text-align: left">UNIT</td>
        <td style="font-weight: bold;text-align: left">REFERENCE</td>
        <td style="font-weight: bold;text-align: left">POLIKLINIK</td>
    </tr>
    <tr></tr>
    @php($totalAmount = 0)
    @foreach($array_report as $db_obj)
        @if($db_obj->debtorcode == $debtor->debtorcode)
        <tr>
            <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
            @if(!empty($db_obj->datesend))
                <td>{{\Carbon\Carbon::parse($db_obj->datesend)->format('d/m/Y')}}</td>
            @else
                <td></td>
            @endif
            <td>{{$db_obj->trantype}}/{{str_pad($db_obj->auditno, 7, "0", STR_PAD_LEFT)}}</td>
            <td style="text-align: left">{!!$db_obj->reference!!}</td>
            @if(!empty($db_obj->amount_dr))
                @php($totalAmount += $db_obj->amount_dr)
                <td style="text-align: right">{{$db_obj->amount_dr}}</td>
            @else
                @php($totalAmount += $db_obj->amount_cr)
                <td style="text-align: right">{{$db_obj->amount_cr}}</td>
            @endif
            <td style="text-align: right">{{$totalAmount}}</td>
            @if(strtoupper($db_obj->unit) == 'POLIS15')
            <td>POLIKLINIK</td>
            @else
            <td>{{$db_obj->unit}}</td>
            @endif
            <td>{!!$db_obj->real_reference!!}</td>
            @if($db_obj->trantype == 'IN')
            <td>{{$db_obj->tillcode}}</td>
            @endif
        </tr>
        @endif
    @endforeach
    <tr></tr>
    <div style="page-break-after: always" />
    @endforeach
</table>