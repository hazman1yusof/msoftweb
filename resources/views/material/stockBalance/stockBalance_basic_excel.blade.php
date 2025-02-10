<table>
    <tr>
    </tr>
    @foreach($unit as $index_unit => $unit_)
        <tr>
            <td colspan="3" style="font-weight:bold">{{$unit_->unit}} - {{$unit_->unit_desc}}</td>
        </tr>
        <tr></tr>
        @foreach($deptcode as $index => $dept)
            <tr>
                <td colspan="3" style="font-weight:bold">{{$dept->deptcode}} - {{$dept->dept_desc}}</td>
            </tr>
            <tr></tr>
            <tr>
                <td style="font-weight:bold">Dept</td>
                <td style="font-weight:bold">Itemcode</td>
                <td style="font-weight:bold">Description</td>
                <td style="font-weight:bold">Uom Code</td>
                <td style="font-weight:bold">Opening Amount</td>
                <td style="font-weight:bold">Opening Qty</td>
                <td style="font-weight:bold">Closing Qty</td>
                <td style="font-weight:bold">Closing Amount</td>
            </tr>
            @foreach($array_report as $obj)
                @if(strtoupper(trim($obj->deptcode)) == strtoupper(trim($dept->deptcode)))
                <tr>
                    <td>{{$obj->deptcode}}</td>
                    @if(is_numeric($obj->itemcode))
                    <td  data-format="0">{{$obj->itemcode}}</td>
                    @else
                    <td  data-format="@">{{$obj->itemcode}}</td>
                    @endif
                    <td>{{$obj->description}}</td>
                    <td>{{$obj->uomcode}}</td>
                    <td>{{$obj->open_balval}}</td>
                    <td>{{$obj->open_balqty}}</td>
                    <td>{{$obj->close_balqty}}</td>
                    <td>{{$obj->close_balval}}</td>
                </tr>
                @endif
            @endforeach
        <tr></tr>
        @endforeach
    @endforeach
</table>