<table>
    <tr>
        <td style="font-weight:bold;text-align: left">UNIT</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold;text-align: right">QTY ON HAND</td>
        <td style="font-weight:bold;text-align: right">AVG COST</td>
        <td style="font-weight:bold;text-align: right">CURRENT PRICE</td>
        <td style="font-weight:bold;text-align: left">GROUP CODE</td>
        <td style="font-weight:bold;text-align: left">PRODUCT  <br style="mso-data-placement:same-cell;" /> CATEGORY</td>
        <td style="font-weight:bold;text-align: left">CHARGE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER CODE</td>

    </tr>
    @foreach($product as $obj)
    <tr>
        <td>{{$obj->unit}}</td>
        <td>{{$obj->itemcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyonhand, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->avgcost, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->currprice, 2, '.', ',') }}</td>
        <td>{{$obj->groupcode}}</td>
        <td>{{$obj->productcat}}</td>
        @if($obj->chgflag == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif        
        <td>{{$obj->suppcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->Name}}</td>
    </tr>
    @endforeach
</table>