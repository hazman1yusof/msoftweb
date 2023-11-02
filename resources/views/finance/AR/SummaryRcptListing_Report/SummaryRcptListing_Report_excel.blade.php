<table>    
    @foreach($dbacthdr as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->entrydate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->cash}}</td>
        <td>{{$db_obj->card}}</td>
        <td>{{$db_obj->cheque}}</td>
        <td>{{($db_obj->cash)+($db_obj->card)+($db_obj->cheque)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td>{{$sum_cash}}</td>
        <td>{{$sum_card+$sum_bank}}</td>
        <td>{{$sum_chq}}</td>
        <td>{{$sum_all}}</td>
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
        <td>{{\Carbon\Carbon::parse($db_obj->entrydate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->cash}}</td>
        <td>{{$db_obj->card}}</td>
        <td>{{$db_obj->cheque}}</td>
        <td>{{($db_obj->cash)+($db_obj->card)+($db_obj->cheque)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">SUBTOTAL</td>
        <td>{{$sum_cash_ref}}</td>
        <td>{{$sum_card_ref+$sum_bank_ref}}</td>
        <td>{{$sum_chq_ref}}</td>
        <td>{{$sum_chq_ref}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold">GRAND TOTAL</td>
        <td>{{$grandtotal_cash}}</td>
        <td>{{$grandtotal_card}}</td>
        <td>{{$grandtotal_chq}}</td>
        <td>{{$grandtotal_all}}</td>
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