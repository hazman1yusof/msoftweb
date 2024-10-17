<table>
    <tr></tr>

    @foreach ($do_hd as $hd_obj)
    <tr>
        <td style="font-weight:bold; text-align: left">Pur Dept : {{$hd_obj->prdept_desc}}</td>
        <td style="font-weight:bold; text-align: left">DO No : {{$hd_obj->delordno}}</td>
        <td style="font-weight:bold; text-align: left">GRN No : {{$hd_obj->docno}}</td>
        <td style="font-weight:bold; text-align: left">Supplier : {{$hd_obj->suppcode_desc}}</td>
        <td style="font-weight:bold; text-align: left">Total Amount</td>
        <td style="font-weight:bold; text-align: left">{{$hd_obj->totamount}}</td>
    </tr>
        <tr>
            <td style="font-weight:bold; text-align: left">Date</td>
            <td style="font-weight:bold; text-align: left">Itemcode</td>
            <td style="font-weight:bold; text-align: left">Description</td>
            <td style="font-weight:bold; text-align: left">UOM</td>
            <td style="font-weight:bold; text-align: left">Quantity</td>
            <td style="font-weight:bold; text-align: left">Amount</td>
        </tr>
        @foreach ($delordhd as $dt_obj)
            @if($dt_obj->recno == $hd_obj->recno)
                <tr>
                    <td>{{$hd_obj->trandate}}</td>
                    <td>{{$dt_obj->itemcode}}</td>
                    <td>{{$dt_obj->itemcode_desc}}</td>
                    <td>{{$dt_obj->uomcode}}</td>
                    <td>{{$dt_obj->qtydelivered}}</td>
                    <td>{{$dt_obj->amount}}</td>
                </tr>
            @endif
        @endforeach
        <tr></tr>
        <tr></tr>
    @endforeach
</table>