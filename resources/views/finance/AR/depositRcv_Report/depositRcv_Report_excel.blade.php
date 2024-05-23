<table>
    @foreach($paymode as $pmode)
        <tr>
            <td style="font-weight: bold">Card Code:</td>
            <td style="font-weight: bold">{{$pmode->paymode}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold">RECEIPT NO</td>
            <td style="font-weight: bold">RECEIPT DATE</td>
            <td style="font-weight: bold">AMOUNT</td>
            <td style="font-weight: bold">CARD NO</td>
            <td style="font-weight: bold">EXP DATE</td>
            <td style="font-weight: bold">AUTH NO</td>
            <td style="font-weight: bold">PAYER</td>
        </tr>
        @php($tot = 0)
        @foreach ($dbacthdr as $obj)
            @if($obj->paymode == $pmode->paymode)
                <tr>
                    <td>{{$obj->recptno}}</td>
                    <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
                    <td>{{$obj->reference}}</td>
                    <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
                    <td>{{$obj->authno}}</td>
                    <td>{{$obj->payername}}</td>
                </tr>
            @php($tot += $obj->amount)
            @endif
        @endforeach
        <tr>
            <td style="font-weight: bold">Total Amount</td>
            <td></td>
            <td data-format="0.00" style="text-align: right">{{number_format($tot, 2, '.', ',')}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        <tr></tr>
    @endforeach
</table>
<table>
    <tr>
        <td style="font-weight: bold">Grand Total:</td>
        <td></td>
        <td data-format="0.00" style="font-weight: bold; text-align: right;">{{number_format($totalAmount, 2, '.', ',')}}</td>
    </tr>
</table>
