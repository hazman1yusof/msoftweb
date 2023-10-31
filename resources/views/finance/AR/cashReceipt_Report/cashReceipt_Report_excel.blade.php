<table>
    @foreach ($dbacthdr as $obj)
        <tr>
            <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
            <td>{{$obj->payercode}}</td>
            <td data-format="0.00">{{number_format($obj->amount,2)}}</td>
            <td>{{$obj->payername}}</td>
            <td>{{$obj->dt_description}}</td>
            <td>{{$obj->paymode}}</td>
            <td>RECEIPT NO: {{$obj->recptno}}</td>
        </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">Total Amount</td>
        <td></td>
        <td data-format="0.00" style="text-align: right;">{{number_format($totalAmount,2)}}</td>
    </tr>
</table>
