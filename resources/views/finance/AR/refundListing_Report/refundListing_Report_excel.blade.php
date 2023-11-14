<table>
    @foreach ($paymode as $pmode)
        <tr>
            <td style="font-weight:bold">PAYMODE:</td>
            <td style="font-weight:bold">{{$pmode->paymode}}</td>
        </tr>
        <tr>
            <td style="font-weight:bold">RECEIPT DATE</td>
            <td style="font-weight:bold">PAYER CODE</td>
            <td style="font-weight:bold">AMOUNT</td>
            <td style="font-weight:bold">PAYER</td>
            <td style="font-weight:bold">AUTH NO</td>
            <td style="font-weight:bold">EXP DATE</td>
            <td style="font-weight:bold">FC</td>
            <td style="font-weight:bold">REFERENCE</td>
        </tr>
        @php($tot = 0)
        @foreach($dbacthdr as $obj)
            @if($obj->paymode == $pmode->paymode)
                <tr>
                    <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
                    <td>{{$obj->payercode}}</td>
                    <td style="text-align: right">{{ number_format($obj->amount, 2, '.', ',') }}</td>
                    <td>{{$obj->name}}</td>
                    <td>{{($obj->authno)}}</td>
                    <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
                    <td>{{($obj->dt_description)}}</td>
                    <td>{{($obj->recptno)}}</td>
                </tr>
            @php($tot += $obj->amount)
            @endif
        @endforeach
        <tr>
            <td style="font-weight:bold">TOTAL AMOUNT:</td>
            <td></td>
            <td style="text-align: right">{{ number_format($tot, 2, '.', ',') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        <tr></tr>
    @endforeach
</table>