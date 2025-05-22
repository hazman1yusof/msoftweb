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
        <td style="font-weight: bold;text-align: left">REFERENCE</td>
        <td style="font-weight: bold;text-align: right">AMOUNT DR</td>
        <td style="font-weight: bold;text-align: right">AMOUNT CR</td>
        <td style="font-weight: bold;text-align: right">BALANCE</td>
    </tr>
    <tr></tr>
    @php($totalAmount_dr = 0)
    @php($totalAmount_cr = 0)
    @foreach($array_report as $db_obj)
        @if($db_obj->debtorcode == $debtor->debtorcode)
        <tr>
            <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
            @if(!empty($db_obj->datesend))
                <td>{{\Carbon\Carbon::parse($db_obj->datesend)->format('d/m/Y')}}</td>
            @else
                <td></td>
            @endif
            <td>{{$db_obj->trantype}}/{{str_pad($db_obj->auditno, 5, "0", STR_PAD_LEFT)}}</td>
            <td style="text-align: left">{{$db_obj->reference}}</td>
            @if(!empty($db_obj->amount_dr))
                @php($totalAmount_dr += $db_obj->amount_dr)
                <td data-format="0.00" style="text-align: right">{{number_format($db_obj->amount_dr, 2, '.', ',')}}</td>
            @else
                <td></td>
            @endif
            @if(!empty($db_obj->amount_cr))
                @php($totalAmount_cr += $db_obj->amount_cr)
                <td data-format="0.00" style="text-align: right">{{number_format($db_obj->amount_cr, 2, '.', ',')}}</td>
            @else
                <td></td>
            @endif
            <td data-format="0.00" style="text-align: right">{{number_format($db_obj->balance, 2, '.', ',')}}</td>
        </tr>
        @endif
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight: bold">TOTAL</td>
        <td data-format="0.00" style="text-align: right; font-weight: bold">{{number_format($totalAmount_dr, 2, '.', ',')}}</td>
        <td data-format="0.00" style="text-align: right; font-weight: bold">{{number_format($totalAmount_cr, 2, '.', ',')}}</td>
        <td></td>
    </tr>
    <tr></tr>
    <div style="page-break-after: always" />
    @endforeach
</table>