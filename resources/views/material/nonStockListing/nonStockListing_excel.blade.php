<table>
    <tr>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold;text-align: right">QTY ON HAND</td>
        <td style="font-weight:bold;text-align: right">AVG COST</td>
        <td style="font-weight:bold;text-align: right">CURRENT PRICE</td>
    </tr>
    @foreach($product as $obj)
    <tr>
        <td>{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyonhand, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->avgcost, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->currprice, 2, '.', ',') }}</td>
    </tr>
    @endforeach
</table>