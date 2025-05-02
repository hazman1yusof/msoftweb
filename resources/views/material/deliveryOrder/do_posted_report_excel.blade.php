<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Date</td>
        <td style="font-weight:bold; text-align: left">Itemcode</td>
        <td style="font-weight:bold; text-align: left">Description</td>
        <td style="font-weight:bold; text-align: left">UOM</td>
        <td style="font-weight:bold; text-align: left">Quantity</td>
        <td style="font-weight:bold; text-align: left">Amount</td>
        <td style="font-weight:bold; text-align: left">Total Amount</td>
    </tr>
    @php($fulltot = 0)
    @foreach ($do_hd as $hd_obj)
    @php($fulltot = $fulltot + $hd_obj->totamount)
    <tr>
        <td style="font-weight:bold; text-align: left">Delivery Dept : {{$hd_obj->deldept_desc}}</td>
        <td style="font-weight:bold; text-align: left">DO No : {{$hd_obj->delordno}}</td>
        <td style="font-weight:bold; text-align: left">GRN No : {{$hd_obj->docno}}</td>
        <td style="font-weight:bold; text-align: left">Supplier : {{$hd_obj->suppcode_desc}}</td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
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
    <tr>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left">Total Amount</td>
        <td style="font-weight:bold; text-align: right">{{$hd_obj->totamount}}</td>
    </tr>
        <tr></tr>
    @endforeach
        <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left">Full Total Amount</td>
        <td style="font-weight:bold; text-align: right">{{$fulltot}}</td>
    </tr>
</table>