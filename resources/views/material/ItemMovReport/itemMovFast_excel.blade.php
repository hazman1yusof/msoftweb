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
        @if($deptcode=='FKWSTR')
        <td style="font-weight:bold">TUO Qty</td>
        <td style="font-weight:bold">TUO Cost</td>
        @endif
    </tr>
    @foreach($ivdspdt_array as $obj)
            <tr>
                <td>{{$obj['itemcode']}}</td>
                <td>{{$obj['description']}}</td>
                <td>{{$obj['uomcode']}}</td>
                <td>{{$obj['qtyonhand']}}</td>
                <td>{{$obj['qtyonhandval']}}</td>
                <td>{{$obj['disp_qty']}}</td>
                <td>{{$obj['disp_cost']}}</td>
                <td>{{$obj['disp_saleamt']}}</td>
                @if($deptcode=='FKWSTR')
                <td>{{$obj['txndt_qty']}}</td>
                <td>{{$obj['txndt_cost']}}</td>
                @endif
            </tr>
    @endforeach
</table>