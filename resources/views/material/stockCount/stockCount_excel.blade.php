<table>
    <tr>
        <td style="font-weight:bold">Line No.</td>
        <td style="font-weight:bold">ItemCode</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Expiry Date</td>
        <td style="font-weight:bold">UOM Code</td>
        <td style="font-weight:bold">Freeze Qty</td>
        <td style="font-weight:bold">Dispense Qty</td>
        <td style="font-weight:bold">Physical Qty</td>
        <td style="font-weight:bold">Variance Qty</td>
        <td style="font-weight:bold">W.Avg Cost</td>
        <td style="font-weight:bold">Variance Value</td>
    </tr>
    @php($totalvv = 0)
    @foreach($phycntdt as $obj)
            <tr>
                <td>{{$obj->lineno_ + 1}}</td>
                <td>{{$obj->itemcode}}</td>
                <td>{{$obj->description}}</td>
                <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
                <td>{{$obj->uomcode}}</td>
                <td>{{number_format($obj->thyqty, 2, '.', ',')}}</td>
                <td>{{number_format(0, 2, '.', ',')}}</td>
                <td>{{number_format($obj->phyqty, 2, '.', ',')}}</td>
                <td>{{number_format($obj->dspqty, 2, '.', ',')}}</td>
                <td>{{number_format($obj->unitcost, 2, '.', ',')}}</td>
                <td>{{number_format($obj->unitcost * $obj->dspqty, 2, '.', ',')}}</td>
            </tr>
        @php($totalvv = $totalvv + $obj->unitcost * $obj->dspqty)
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold">TOTAL:</td>
        <td style="text-align: right">{{ number_format($totalvv, 2, '.', ',') }}</td>
    </tr>
</table>