<table>
    @foreach($dbacthdr as $db_obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
        <td>{{$db_obj->payercode}}</td>
        <td data-format="0.00">{{$db_obj->amount}}</td>
        <td>{{$db_obj->payername}}</td>
        <td>{{($db_obj->dt_description)}}</td>
        <td>{{($db_obj->paymode)}}</td>
        <td>{{($db_obj->reference)}}</td>
        <td>{{($db_obj->recptno)}}</td>
    </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold">TOTAL AMOUNT</td>
        <td></td>
        <td data-format="0.00">{{$totalAmount}}</td>
    </tr>
</table>