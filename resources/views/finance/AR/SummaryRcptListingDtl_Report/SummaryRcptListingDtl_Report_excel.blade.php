<table>
    <tr>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: left">TILL CODE</td>
        <td style="font-weight:bold;text-align: left">CASHIER</td>
        <td style="font-weight:bold;text-align: right">CASH</td>
        <td style="font-weight:bold;text-align: right">CARD</td>
        <td style="font-weight:bold;text-align: right">CHEQUE</td>
        <td style="font-weight:bold;text-align: right">AUTO DEBIT</td>
        <td style="font-weight:bold;text-align: right">TOTAL</td>
    </tr>
    @foreach($dbacthdr as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->tillcode}}</td>
        <td>{{$db_obj->cashier}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cheque, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->autodebit, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format(($db_obj->cash)+($db_obj->card)+($db_obj->cheque)+($db_obj->autodebit), 2, '.', ',')}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_chq, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_bank, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_all, 2, '.', ',') }}</td>
    </tr>
</table>

<table>       
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold; text-align: center">REFUND LISTING</td>
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
        <td></td>
        <td></td>
        <td></td>
    </tr> 
    <tr>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: left">TILL CODE</td>
        <td style="font-weight:bold;text-align: left">CASHIER</td>
        <td style="font-weight:bold;text-align: right">CASH</td>
        <td style="font-weight:bold;text-align: right">CARD</td>
        <td style="font-weight:bold;text-align: right">CHEQUE</td>
        <td style="font-weight:bold;text-align: right">AUTO DEBIT</td>
        <td style="font-weight:bold;text-align: right">TOTAL</td>
    </tr>

    @foreach($dbacthdr_rf as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->tillcode}}</td>
        <td>{{$db_obj->cashier}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->cheque, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($db_obj->autodebit, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format(($db_obj->cash)+($db_obj->card)+($db_obj->cheque)+($db_obj->autodebit), 2, '.', ',')}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_cash_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_card_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_chq_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_bank_ref, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($sum_all_ref, 2, '.', ',') }}</td>
    </tr>
     <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> 
    <tr>
        <td style="font-weight:bold">GRAND TOTAL</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_cash, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_card, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_chq, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_bank, 2, '.', ',') }}</td>
        <td data-format="0.00" style="font-weight:bold; text-align: right">{{ number_format($grandtotal_all, 2, '.', ',') }}</td>
    </tr>
</table>
