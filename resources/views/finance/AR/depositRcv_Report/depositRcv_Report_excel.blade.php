<table>
    <tr>
        <td style="font-weight: bold">RECEIPT NO</td>
        <td style="font-weight: bold">RECEIPT DATE</td>
        <td style="font-weight: bold">PAYER CODE</td>
        <td style="font-weight: bold">PAYER</td>
        <td style="font-weight: bold;text-align: right">AMOUNT</td>
        <td style="font-weight: bold">PAYMODE</td>
        <td style="font-weight: bold">REFERENCE NO</td>
        <td style="font-weight: bold">EXPIRY DATE</td>
    </tr>
    @php($tot = 0)
    @foreach($dbacthdr as $obj)
    <tr>
        <td>{{$obj->recptno}}</td>
        <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
        <td style="text-align: left">{{$obj->payercode}}</td>
        <td>{{$obj->name}}</td>
        <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
        <td>{{$obj->paymode}}</td>
        <td>{{$obj->reference}}</td>
        @if($obj->paytype == '#F_TAB-CARD')
            <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
        @else
            <td></td>
        @endif
    </tr>
    @php($tot += $obj->amount)
    @endforeach
    <tr>
        <td style="font-weight: bold" colspan="2">TOTAL AMOUNT:</td>
        <!-- <td></td> -->
        <td></td>
        <td></td>
        <td data-format="0.00" style="text-align: right">{{number_format($tot, 2, '.', ',')}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr></tr>
</table>
<!-- <table>
    <tr>
        <td style="font-weight: bold">GRAND TOTAL:</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="font-weight: bold; text-align: right;">{{number_format($totalAmount, 2, '.', ',')}}</td>
    </tr>
</table> -->