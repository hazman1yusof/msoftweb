<table>
    <tr>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: right">PAYER CODE</td>
        <td style="font-weight:bold;text-align: right">NAME</td>
        <td style="font-weight:bold;text-align: right">BILL AMOUNT</td>
        <td style="font-weight:bold;text-align: right">BILL NO</td>
        <td style="font-weight:bold;text-align: right">CASH</td>
        <td style="font-weight:bold;text-align: right">CARD</td>
        <td style="font-weight:bold;text-align: right">CHEQUE</td>
        <td style="font-weight:bold;text-align: right">TT</td> <!-- auto debit -->
        <td style="font-weight:bold;text-align: right">CREDIT NOTE</td>
        <td style="font-weight:bold;text-align: right">RECEIPT NO</td>
    </tr>
    @foreach($array_report as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->entrydate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->payercode}}</td>
        <td>{{$db_obj->dm_name}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->amount, 2, '.', ',') }}</td>
        <td>{{$db_obj->auditno}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cash_amount, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->card_amount, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cheque_amount, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->tt_amount, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cn_amount, 2, '.', ',') }}</td>
        <td>{{($db_obj->recptno)}}</td>
    </tr>
    @endforeach
</table>