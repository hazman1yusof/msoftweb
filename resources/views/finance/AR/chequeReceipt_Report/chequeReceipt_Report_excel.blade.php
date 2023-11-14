<table>
    <tr>
        <td style="font-weight:bold;text-align: left">RECEIPT DATE</td>
        <td style="font-weight:bold;text-align: right">PAYER CODE</td>
        <td style="font-weight:bold;text-align: right">AMOUNT</td>
        <td style="font-weight:bold;text-align: right">PAYER</td>
        <td style="font-weight:bold;text-align: right">FC</td>
        <td style="font-weight:bold;text-align: right">MODE</td>
        <td style="font-weight:bold;text-align: right">REFERENCE</td>
        <td style="font-weight:bold;text-align: right">RECEIPT NO</td>
    </tr>
    @foreach($dbacthdr as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->payercode}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->amount, 2, '.', ',') }}</td>
        <td>{{$db_obj->payername}}</td>
        <td>{{($db_obj->dt_description)}}</td>
        <td>{{($db_obj->paymode)}}</td>
        <td>{{($db_obj->reference)}}</td>
        <td>{{($db_obj->recptno)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">TOTAL AMOUNT</td>
        <td></td>
        <td data-format="0.00" style="text-align: right">{{ number_format($totalAmount, 2, '.', ',') }}</td>
    </tr>
</table>