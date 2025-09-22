<table>
    <tr>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: right">CASH</td>
        <td style="font-weight:bold;text-align: right">CARD</td>
        <td style="font-weight:bold;text-align: right">CHEQUE</td>
        <td style="font-weight:bold;text-align: right">TOTAL</td>
    </tr>
    @foreach($dbacthdr as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cheque, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format(($db_obj->cash)+($db_obj->card)+($db_obj->cheque), 2, '.', ',')}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_card+$sum_bank, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_chq, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_all, 2, '.', ',') }}</td>
    </tr>
</table>
<table>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold; text-align: center">REFUND LISTING</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold">DATE</td>
        <td style="font-weight:bold">CASH</td>
        <td style="font-weight:bold">CARD</td>
        <td style="font-weight:bold">CHEQUE</td>
        <td style="font-weight:bold">TOTAL</td>
    </tr>
    @foreach($dbacthdr_rf as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cheque, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format(($db_obj->cash)+($db_obj->card)+($db_obj->cheque), 2, '.', ',')}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_cash_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_card_ref+$sum_bank_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_chq_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_all_ref, 2, '.', ',') }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold">GRAND TOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_chq, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_all, 2, '.', ',') }}</td>
    </tr>
</table>
<table>
    <tr>
        <td>Checked By  :</td>
        <td></td>
        <td></td>
        <td>Verified By :    </td>
        <td></td>
    </tr>
</table>