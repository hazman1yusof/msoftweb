<table>
    <tr>
    </tr>
    @foreach($deptcode as $index => $dept)
    <tr>
        <td colspan="3" style="font-weight:bold">{{$dept->deptcode}} - {{$dept->description}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold">Dept</td>
        <td style="font-weight:bold">Itemcode</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Uom Code</td>
        <td style="font-weight:bold">Opening Amount</td>
        <td style="font-weight:bold">Opening Qty</td>
        <td style="font-weight:bold">GRN</td>
        <td style="font-weight:bold">TR</td>
        <td style="font-weight:bold">DS</td>
        <td style="font-weight:bold">WOF</td>
        <td style="font-weight:bold">AI</td>
        <td style="font-weight:bold">AO</td>
        <td style="font-weight:bold">PHY</td>
        <td style="font-weight:bold">OTH</td>
        <td style="font-weight:bold">Closing Qty</td>
        <td style="font-weight:bold">Closing Amount</td>
    </tr>
    @foreach($array_report as $obj)
        @if(strtoupper(trim($obj->deptcode)) == strtoupper(trim($dept->deptcode)))
        <tr>
            <td>{{$obj->deptcode}}</td>
            <td>{{$obj->itemcode}}</td>
            <td>{{$obj->description}}</td>
            <td>{{$obj->uomcode}}</td>
            <td>{{number_format($obj->open_balval, 2, '.', ',')}}</td>
            <td>{{number_format($obj->open_balqty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->grn_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->tr_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->ds_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->wof_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->ai_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->ao_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->phy_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->oth_qty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->close_balqty, 2, '.', ',')}}</td>
            <td>{{number_format($obj->close_balval, 2, '.', ',')}}</td>
        </tr>
        @endif
    @endforeach
    <tr></tr>
    @endforeach
</table>