<table>
    <tr>
        <td style="font-weight:bold">REQUEST DATE</td>
        <td style="font-weight:bold">STATUS</td>
        <td style="font-weight:bold;text-align: left">REQUEST NO</td>
        <td style="font-weight:bold;text-align: left">REQUEST<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">REQUEST<br style="mso-data-placement:same-cell;" />TO DEPT</td>
        <td style="font-weight:bold;text-align: left">ITEMCODE</td>
        <td style="font-weight:bold;text-align: left">UOM CODE</td>
        <td style="font-weight:bold;text-align: right">MAX QTY</td>
        <td style="font-weight:bold;text-align: right">QOH AT<br style="mso-data-placement:same-cell;" />REQ DEPT</td>
        <td style="font-weight:bold;text-align: right">QTY REQ</td>
        <td style="font-weight:bold;text-align: right">QTY BAL</td>
        <td style="font-weight:bold;text-align: right">QTY SUPPLIED</td>
        <td style="font-weight:bold;text-align: right">NET PRICE</td>


    </tr>
    @foreach($ivrequest as $obj)
    <tr>
        <td>{{\Carbon\Carbon::parse($obj->reqdt)->format('d/m/Y')}}</td>
        <td>{{$obj->recstatus}}</td>
        <td>{{$obj->ivreqno}}</td>
        <td>{{$obj->reqdept}}</td>
        <td>{{$obj->reqtodept}}</td>
        <td>{{$obj->itemcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyonhand, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyrequest, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtybalance, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtytxn, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->netprice, 2, '.', ',') }}</td>
    </tr>
    @endforeach
</table>