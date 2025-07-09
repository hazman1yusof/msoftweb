<table>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" style="font-weight:bold;text-align: left">TILL ENQUIRY</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2" style="text-align: left">CASHIER : {{strtoupper($tilldetl->cashier)}}</td>
        @if(!empty($tilldetl->closedate))
            <td colspan="2" style="text-align: left">CLOSE DATE : {{\Carbon\Carbon::parse($tilldetl->closedate)->format('d/m/Y')}}</td>
        @else
            <td colspan="2" style="text-align: left">OPEN DATE : {{\Carbon\Carbon::parse($tilldetl->opendate)->format('d/m/Y')}}</td>
        @endif
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">TILL CODE : {{strtoupper($tilldetl->tillcode)}}</td>
        @if(!empty($tilldetl->closedate))
            <td colspan="2" style="text-align: left">CLOSE TIME : {{\Carbon\Carbon::parse($tilldetl->closetime)->format('h:i A')}}</td>
        @else
            <td colspan="2" style="text-align: left">OPEN TIME : {{\Carbon\Carbon::parse($tilldetl->opentime)->format('h:i A')}}</td>
        @endif
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold;text-align: left">PAYER CODE</td>
        <td style="font-weight:bold;text-align: left">FC</td>
        <td style="font-weight:bold;text-align: left">PAYER</td>
        <td style="font-weight:bold;text-align: left">MODE</td>
        <td style="font-weight:bold;text-align: left">REFERENCE</td>
        <td style="font-weight:bold;text-align: left">RECEIPT NO</td>
        <td style="font-weight:bold;text-align: left">RECEIPT DATE</td>
        <td style="font-weight:bold;text-align: right">AMOUNT</td>
    </tr>
    @foreach($dbacthdr as $obj)
    <tr>
        <td>{{$obj->payercode}}</td>
        <td>{{$obj->dt_description}}</td>
        <td>{{$obj->dm_name}}</td>
        <td>{{$obj->paymode}}</td>
        <td>{{$obj->reference}}</td>
        <td>{{$obj->recptno}}</td>
        <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$obj->amount}}</td>
    </tr>
    @endforeach

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align: left">Total Amount</td>
        <td>{{$totalAmount}}</td>
    </tr>

    <tr></tr>
    <tr>
        <td colspan="2" style="font-weight: bold">SUMMARY</td>
    </tr>

    <tr>
        <td style="font-weight:bold;text-align: left">Pay Type</td>
        <td style="font-weight:bold;text-align: left">Amount Collected</td>
        <td style="font-weight:bold;text-align: left">Amount Refund</td>
        <td style="font-weight:bold;text-align: left">Total</td>
    </tr>

    <tr>
        <td style="font-weight:bold;text-align: left">Cash</td>
        <td data-format="0.00" style="text-align: right">{{$sum_cash}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_cash_ref}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_cash - $sum_cash_ref}}</td>
    </tr>

    <tr>
        <td style="font-weight:bold;text-align: left">Cheque</td>
        <td data-format="0.00" style="text-align: right">{{$sum_chq}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_chq_ref}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_chq - $sum_chq_ref}}</td>
    </tr>

    <tr>
        <td style="font-weight:bold;text-align: left">Card</td>
        <td data-format="0.00" style="text-align: right">{{$sum_card}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_card_ref}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_card - $sum_card_ref}}</td>
    </tr>

    @foreach ($dbacthdr_card_unique as $obj)
        @php($amount_RC = 0)
        @php($amount_RF = 0)

        @foreach ($dbacthdr_card as $obj_b)
            @if($obj_b->paymode == $obj->paymode && ($obj->trantype == 'RC' || $obj->trantype == 'RD'))
                @php($amount_RC = $amount_RC + $obj_b->amount)
            @endif

            @if($obj_b->paymode == $obj->paymode && $obj->trantype == 'RF')
                @php($amount_RF = $amount_RF + $obj_b->amount)
            @endif
        @endforeach

        <tr>
            <td style="font-weight:bold;text-align: left;font-style: italic;">-{{$obj->paymode}}</td>
            <td data-format="0.00" style="text-align: right;font-style: italic;">{{$amount_RC}}</td>
            <td data-format="0.00" style="text-align: right;font-style: italic;">{{$amount_RF}}</td>
            <td data-format="0.00" style="text-align: right;font-style: italic;">{{$amount_RC - $amount_RF}}</td>
        </tr>
    @endforeach

    <tr>
        <td style="font-weight:bold;text-align: left">Auto Debit</td>
        <td data-format="0.00" style="text-align: right">{{$sum_bank}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_bank_ref}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_bank - $sum_bank_ref}}</td>
    </tr>

    <tr>
        <td style="font-weight:bold;text-align: left">Total</td>
        <td data-format="0.00" style="text-align: right">{{$sum_all}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_all_ref}}</td>
        <td data-format="0.00" style="text-align: right">{{$sum_all - $sum_all_ref}}</td>
    </tr>
</table>