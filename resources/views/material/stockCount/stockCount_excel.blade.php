<table>
    <tr>
        <td style="font-weight:bold">Line No.</td>
        <td style="font-weight:bold">ItemCode</td>
        <td style="font-weight:bold">Description</td>
        <td style="font-weight:bold">Batchno</td>
        <td style="font-weight:bold">Expiry Date</td>
        <td style="font-weight:bold">UOM Code</td>
        <td style="font-weight:bold">Freeze Qty</td>
        <td style="font-weight:bold">Physical Qty</td>
        <td style="font-weight:bold">Variance Qty</td>
        <td style="font-weight:bold">W.Avg Cost</td>
        <td style="font-weight:bold">Variance Value</td>
        <td style="font-weight:bold">Put count Quantity here!</td>
    </tr>
    @php($totalvv = 0)
    @foreach($phycntdt as $obj)
        <?php
            $var = $obj->phyqty - $obj->thyqty;
            $var_val = $var * $obj->unitcost;

            if(empty($obj->expdate)){
                $expdate = null;
            }else{
                $expdate = \Carbon\Carbon::parse($obj->expdate)->format('d/m/Y');
            }
        ?>

            <tr>
                <td>{{$obj->lineno_}}</td>
                @if(is_numeric($obj->itemcode))
                <td  data-format="0">{{$obj->itemcode}}</td>
                @else
                <td  data-format="@">{{$obj->itemcode}}</td>
                @endif
                <td>{!!preg_replace("/[^A-Za-z0-9 ]/", '', $obj->description)!!}</td>
                <td>{{$obj->batchno}}</td>
                <td>{{$expdate}}</td>
                <td>{{$obj->uomcode}}</td>
                <td>{{$obj->thyqty}}</td>
                <td>{{$obj->phyqty}}</td>
                <td>{{$var}}</td>
                <td>{{$obj->unitcost}}</td>
                <td>{{$var_val}}</td>
                <td></td>
            </tr>
        @php($totalvv = $totalvv + $var_val)
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
        <td style="text-align: right">{{ $totalvv }}</td>
        <td></td>
    </tr>
</table>