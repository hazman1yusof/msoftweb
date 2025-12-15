<table>
    <tr></tr>

    @foreach ($iv_hd as $hd_obj)
    <tr>
        <td style="font-weight:bold; text-align: left">Supplier Dept : {{$hd_obj->txndept_desc}}</td>
        <td style="font-weight:bold; text-align: left">Record No : {{$hd_obj->recno}}</td>
        <td style="font-weight:bold; text-align: left">Transaction Type : {{$hd_obj->trantype}}</td>
        @if($hd_obj->trantype == 'TUI')
        <td style="font-weight:bold; text-align: left">Sender Dept : {{$hd_obj->sndrcv_desc}}</td>
        @else
        <td style="font-weight:bold; text-align: left">Receiver Dept : {{$hd_obj->sndrcv_desc}}</td>
        @endif
        <td style="font-weight:bold; text-align: left">Total Amount</td>
        <td style="font-weight:bold; text-align: right" data-format="0.00">{{$hd_obj->header_amt}}</td>
        <td style="font-weight:bold; text-align: right" >{{$hd_obj->adduser}}</td>
    </tr>
        <tr>
            <td style="font-weight:bold; text-align: left">Date</td>
            <td style="font-weight:bold; text-align: left">Itemcode</td>
            <td style="font-weight:bold; text-align: left">Description</td>
            <td style="font-weight:bold; text-align: left">UOM</td>
            <td style="font-weight:bold; text-align: left">Quantity</td>
            <td style="font-weight:bold; text-align: left">Amount</td>
        </tr>
        @foreach ($ivtmphd as $dt_obj)
            @if($dt_obj->recno == $hd_obj->recno)
                <tr>
                    <td>{{$hd_obj->trandate}}</td>
                    <td>{{$dt_obj->itemcode}}</td>
                    <td>{{$dt_obj->itemcode_desc}}</td>
                    <td>{{$dt_obj->uomcode}}</td>
                    <td>{{$dt_obj->txnqty}}</td>
                    <td data-format="0.00">{{$dt_obj->amount}}</td>
                </tr>
            @endif
        @endforeach
        <tr></tr>
        <tr></tr>
    @endforeach
</table>