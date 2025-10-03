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
        <td data-format="0.00" style="text-align: right">{{ $db_obj->cash}}</td>
        <td data-format="0.00" style="text-align: right">{{ $db_obj->card}}</td>
        <td data-format="0.00" style="text-align: right">{{ $db_obj->cheque}}</td>
        <td data-format="0.00" style="text-align: right">{{ ($db_obj->cash)+($db_obj->card)+($db_obj->cheque)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_cash}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_card+$sum_bank}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_chq}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_all}}</td>
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
        <td data-format="0.00" style="text-align: right">{{ $db_obj->cash}}</td>
        <td data-format="0.00" style="text-align: right">{{ $db_obj->card}}</td>
        <td data-format="0.00" style="text-align: right">{{ $db_obj->cheque}}</td>
        <td data-format="0.00" style="text-align: right">{{ ($db_obj->cash)+($db_obj->card)+($db_obj->cheque)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_cash_ref}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_card_ref+$sum_bank_ref}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_chq_ref}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $sum_all_ref}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold">GRAND TOTAL</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $grandtotal_cash}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $grandtotal_card}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $grandtotal_chq}}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ $grandtotal_all}}</td>
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