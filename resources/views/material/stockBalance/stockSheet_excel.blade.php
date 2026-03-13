<table>
    <tr>
    </tr>
    @foreach($deptcode as $index => $dept)
    <tr>
        <td colspan="3" style="font-weight:bold">{{$dept->deptcode}} - {{$dept->dept_desc}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold">Item Code</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Uom Code</td>
        <td style="font-weight:bold">Closing Qty</td>
        <td style="font-weight:bold">Physical Qty</td>
        <td style="font-weight:bold">Remark</td>
    </tr>
        @foreach($array_report as $obj)
            @if($obj->deptcode == $dept->deptcode)
            <tr>
                <td>{{$obj->itemcode}}</td>
                <td>{{$obj->description}}</td>
                <td>{{$obj->uomcode}}</td>
                <td>{{$obj->close_balqty}}</td>
                <td></td>
                <td></td>
            </tr>
            @endif
        @endforeach
    <tr></tr>
    @endforeach
</table>