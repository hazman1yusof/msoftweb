<table>
    <tr>
        <td style="font-weight:bold">TRAN DATE</td>
        <td style="font-weight:bold;text-align: left">DOC NO</td>
        <td style="font-weight:bold;text-align: left">TRAN DEPT</td>
        <td style="font-weight:bold;text-align: left">SENDER/<br style="mso-data-placement:same-cell;" />RECEIVER</td>
        <td style="font-weight:bold;text-align: left">ITEMCODE</td>
        <td style="font-weight:bold;text-align: left">DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM CODE<br style="mso-data-placement:same-cell;" />TRAN DEPT</td>
        <td style="font-weight:bold;text-align: right">QOH<br style="mso-data-placement:same-cell;" />TRAN DEPT</td>
        <td style="font-weight:bold;text-align: left">UOM CODE<br style="mso-data-placement:same-cell;" />RECV DEPT</td>
        <td style="font-weight:bold;text-align: right">QOH<br style="mso-data-placement:same-cell;" />RECV DEPT</td>
        <td style="font-weight:bold;text-align: right">TRAN QTY</td>
        <td style="font-weight:bold;text-align: right">QTY REQ</td>
        <td style="font-weight:bold;text-align: right">NET PRICE</td>
        <td style="font-weight:bold;text-align: right">AMOUNT</td>
        <td style="font-weight:bold;text-align: right">EXP DATE</td>
        <td style="font-weight:bold;text-align: right">BATCH NO</td>


    </tr>
    @foreach($ivtxn as $obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($obj->trandate)->format('d/m/Y')}}</td>
        <td>{{str_pad($obj->docno, 7, "0", STR_PAD_LEFT)}}</td>
        <td>{{$obj->txndept}}</td>
        <td>{{$obj->sndrcv}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td style="text-align: right">{{$obj->qtyonhand}}</td>
        <td>{!!$obj->uomcoderecv!!}</td>
        <td style="text-align: right">{{$obj->qtyonhandrecv}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->txnqty, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyrequest, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->netprice, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->amount, 2, '.', ',') }}</td>
        <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
        <td>{{$obj->batchno}}</td>
    @endforeach
</table>