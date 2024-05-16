<table>
    <tr>
        <td style="font-weight:bold;text-align: left">UNIT</td>
        <td style="font-weight:bold;text-align: left">DEPT CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM</td>
        <td style="font-weight:bold">EXP DATE</td>
        <td style="font-weight:bold">BATCH NO</td>
        <td style="font-weight:bold;text-align: right">BALANCE QTY</td>

    </tr>
    @foreach($stockexp as $obj)
    <tr>
        <td>{{$obj->unit}}</td>
        <td>{{$obj->deptcode}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->p_desc}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
        <td style="text-align: left">{{$obj->batchno}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->balqty, 2, '.', ',') }}</td>
       
    </tr>
    @endforeach
</table>