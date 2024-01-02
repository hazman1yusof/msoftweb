<table>
    <tr>
        <td style="font-weight:bold">Item Code</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Uom Code</td>
        <td style="font-weight:bold">Closing Qty</td>
        <td style="font-weight:bold">Physical Qty</td>
        <td style="font-weight:bold">Remark</td>
    </tr>
    @foreach($stockloc as $obj)
            <tr>
                <td>{{$obj->itemcode}}</td>
                <td>{{$obj->description}}</td>
                <td>{{$obj->uomcode}}</td>
                <td>{{number_format($obj->close_balqty, 2, '.', ',')}}</td>
                <td></td>
                <td></td>
            </tr>
    @endforeach
</table>