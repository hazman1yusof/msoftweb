<table>
    <tr>
        <td style="font-weight:bold;text-align: left">DEPT CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold;text-align: right">QTY ON HAND</td>
        <td style="font-weight:bold;text-align: left">STOCK TRX TYPE</td>
        <td style="font-weight:bold;text-align: right">MIN STOCK QTY</td>
        <td style="font-weight:bold;text-align: right">MAX STOCK QTY</td>
        <td style="font-weight:bold;text-align: right">REORDER LEVEL</td>
        <td style="font-weight:bold;text-align: right">REORDER QTY</td>
        <td style="font-weight:bold;text-align: left">DIS TYPE</td>

    </tr>
    @foreach($stockloc as $obj)
    <tr>
        <td>{{$obj->deptcode}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyonhand, 2, '.', ',') }}</td>
        <td>{{$obj->stocktxntype}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->minqty, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->maxqty, 2, '.', ',') }}</td>
        <td>{{$obj->reordlevel}}</td>
        <td>{{$obj->reordqty}}</td>
        <td>{{$obj->disptype}}</td>
    </tr>
    @endforeach
</table>