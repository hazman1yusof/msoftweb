<table>
    <tr>
        <td>{{$debtorcode}}</td>
    </tr>
    <tr>
        <td colspan="4">{{$debtorname}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: left">DOCUMENT</td>
        <td style="font-weight:bold;text-align: left">REFERENCE</td>
        <td style="font-weight:bold;text-align: right">AMOUNT DR</td>
        <td style="font-weight:bold;text-align: right">AMOUNT CR</td>
        <td style="font-weight:bold;text-align: right">BALANCE</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td>OPENING BALANCE</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="text-align: right">{{number_format($openbal, 2, '.', ',')}}</td>
    </tr>
    @php($totalAmount_dr = 0)
    @php($totalAmount_cr = 0)
    @foreach($array_report as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->trantype}}</td>
        <td style="text-align: left">{{$db_obj->reference}}</td>
        @if(!empty($db_obj->amount_dr))
            <td data-format="0.00" style="text-align: right">{{number_format($db_obj->amount_dr, 2, '.', ',')}}</td>
        @else
            <td></td>
        @endif
        @if(!empty($db_obj->amount_cr))
            <td data-format="0.00" style="text-align: right">{{number_format($db_obj->amount_cr, 2, '.', ',')}}</td>
        @else
            <td></td>
        @endif
        <td data-format="0.00" style="text-align: right">{{number_format($db_obj->balance, 2, '.', ',')}}</td>
    </tr>
    @php($totalAmount_dr += $db_obj->amount_dr)
    @php($totalAmount_cr += $db_obj->amount_cr)
    @endforeach
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold">TOTAL</td>
        <td data-format="0.00" style="text-align: right">{{number_format($totalAmount_dr, 2, '.', ',')}}</td>
        <td data-format="0.00" style="text-align: right">{{number_format($totalAmount_cr, 2, '.', ',')}}</td>
        <td data-format="0.00" style="text-align: right"> </td>
    </tr>
</table>