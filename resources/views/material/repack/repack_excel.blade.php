<table>
    <tr>
        <td style="font-weight:bold">REPACK OUTPUT</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold">Date</td>
        <td style="font-weight:bold">Department</td>
        <td style="font-weight:bold">Item Code</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold;text-align: right">Quantity</td>
        <td style="font-weight:bold;text-align: right">Average Cost</td>
        <td style="font-weight:bold;text-align: right">Total Amount</td>
    </tr>
    <tr>
        <td>{{\Carbon\Carbon::parse($repackhd->trandate)->format('d/m/Y')}}</td>
        <td>{{$repackhd->deptcode}}</td>
        <td>{{$repackhd->newitemcode}} <br style="mso-data-placement:same-cell;" /> {{$repackhd->hd_desc}}</td>
        <td>{!!$repackhd->uomcode!!}</td>
        <td data-format="0.00" style="text-align: right">{{number_format($repackhd->outqty, 2, '.', ',')}}</td>
        <td data-format="0.00" style="text-align: right">{{number_format($repackhd->avgcost, 2, '.', ',')}}</td>
        <td data-format="0.00" style="text-align: right">{{number_format($repackhd->amount, 2, '.', ',')}}</td>
    </tr>
<tr></tr>
    <tr>
        <td style="font-weight:bold">REPACK INPUT</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td style="font-weight:bold">Department</td>
        <td style="font-weight:bold">Item Code</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold;text-align: right">Quantity</td>
        <td style="font-weight:bold;text-align: right">Average Cost</td>
        <td style="font-weight:bold;text-align: right">Total Amount</td>
    </tr>
    @foreach($repackdt as $obj)
            <tr>
                <td></td>
                <td>{{$obj->deptcode}}</td>
                <td>{{$obj->olditemcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->dt_desc}}</td>
                <td>{!!$obj->uomcode!!}</td>
                <td data-format="0.00" style="text-align: right">{{number_format($obj->inpqty, 2, '.', ',')}}</td>
                <td data-format="0.00" style="text-align: right">{{number_format($obj->avgcost, 2, '.', ',')}}</td>
                <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
            </tr>
    @endforeach
</table>