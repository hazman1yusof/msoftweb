<table>
    <tr>
        <td style="font-weight:bold">Item Code</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Uom Code</td>
        <td style="font-weight:bold">Qty On Hand</td>
        <td style="font-weight:bold">Qty On Hand Value</td>
        <td style="font-weight:bold">Dispending Qty</td>
        <td style="font-weight:bold">Dispensing Cost</td>
        <td style="font-weight:bold">Sales Amount</td>
    </tr>
    @foreach($ivdspdt_array as $obj)
            <tr>
                <td>{{$obj['itemcode']}}</td>
                <td>{{$obj['description']}}</td>
                <td>{{$obj['uomcode']}}</td>
                <td>{{number_format($obj['qtyonhand'], 2, '.', ',')}}</td>
                <td>{{number_format($obj['qtyonhandval'], 2, '.', ',')}}</td>
                <td>{{number_format($obj['disp_qty'], 2, '.', ',')}}</td>
                <td>{{number_format($obj['disp_cost'], 2, '.', ',')}}</td>
                <td>{{number_format($obj['disp_saleamt'], 2, '.', ',')}}</td>
            </tr>
    @endforeach
</table>