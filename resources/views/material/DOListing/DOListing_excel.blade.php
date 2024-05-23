<table>
    <tr>
        <td style="font-weight:bold;text-align: left">PURCHASE<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">DELIVERY<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">PO NO</td>
        <td style="font-weight:bold;text-align: left">PO DATE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER CODE</td>
        <td style="font-weight:bold;text-align: left">PRICE<br style="mso-data-placement:same-cell;" />CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">UOM CODE</td>
        <td style="font-weight:bold;text-align: left">PO UOM</td>
        <td style="font-weight:bold;text-align: left">TAX CODE</td>
        <td style="font-weight:bold;text-align: right">QTY REQ</td>
        <td style="font-weight:bold;text-align: right">QTY ORDER</td>
        <td style="font-weight:bold;text-align: right">QTY BAL</td>
        <td style="font-weight:bold;text-align: right">UNIT PRICE</td>
        <td style="font-weight:bold;text-align: right">PERCENTAGE<br style="mso-data-placement:same-cell;" />DISC (%)</td>
        <td style="font-weight:bold;text-align: right">DISC<br style="mso-data-placement:same-cell;" />PER UNIT</td>
        <td style="font-weight:bold;text-align: right">TOTAL GST</td>
        <td style="font-weight:bold;text-align: right">TOTAL AMOUNT</td>
    </tr>
    @foreach($POListing as $obj)
    <tr>
        <td>{{$obj->prdept}}</td>
        <td>{{$obj->deldept}}</td>
        <td>{{str_pad($obj->purordno, 7, "0", STR_PAD_LEFT)}}</td>
        <td>{{\Carbon\Carbon::parse($obj->purdate)->format('d/m/Y')}}</td>
        <td>{{$obj->suppcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->supp_name}}</td>
        <td>{{$obj->pricecode}}</td>
        <td>{{$obj->itemcode}} <br style="mso-data-placement:same-cell;" /> {{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td>{!!$obj->pouom!!}</td>
        <td>{{$obj->taxcode}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyrequest, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyorder, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyoutstand, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->unitprice, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->perdisc, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->amtdisc, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->tot_gst, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->totamount, 2, '.', ',') }}</td>
    </tr>
    @endforeach
</table>