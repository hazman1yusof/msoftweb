<table>
    <tr>
        <td style="font-weight:bold;text-align: left">REQUEST<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">PURCHASE<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">REQ NO</td>
        <td style="font-weight:bold;text-align: left">REQ DATE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER CODE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER NAME</td>
        <td style="font-weight:bold;text-align: left">PRICE<br style="mso-data-placement:same-cell;" />CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM CODE</td>
        <td style="font-weight:bold;text-align: left">PO UOM</td>
        <td style="font-weight:bold;text-align: left">TAX CODE</td>
        <td style="font-weight:bold;text-align: right">QTY REQ</td>
        <td style="font-weight:bold;text-align: right">QTY BAL</td>
        <td style="font-weight:bold;text-align: right">UNIT PRICE</td>
        <td style="font-weight:bold;text-align: right">PERCENTAGE<br style="mso-data-placement:same-cell;" />DISC (%)</td>
        <td style="font-weight:bold;text-align: right">DISC<br style="mso-data-placement:same-cell;" />PER UNIT</td>
        <td style="font-weight:bold;text-align: right">TOTAL GST</td>
        <td style="font-weight:bold;text-align: right">TOTAL AMOUNT</td>
        <td style="font-weight:bold;text-align: left">STATUS</td>
    </tr>
    @foreach($PRListing as $obj)
    <tr>
        <td>{{$obj->reqdept}}</td>
        <td>{{$obj->prdept}}</td>
        <td>{{str_pad($obj->purreqno, 7, "0", STR_PAD_LEFT)}}</td>
        <td>{{\Carbon\Carbon::parse($obj->purreqdt)->format('d/m/Y')}}</td>
        <td>{{$obj->suppcode}}</td>
        <td>{{$obj->supp_name}}</td>
        <td>{{$obj->pricecode}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td>{!!$obj->pouom!!}</td>
        <td>{{$obj->taxcode}}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtyrequest, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->qtybalance, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->unitprice, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->perdisc, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->amtdisc, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->tot_gst, 2, '.', ',') }}</td>
        <td data-format="0.00" style="text-align: right">{{ number_format($obj->totamount, 2, '.', ',') }}</td>
        <td>{{$obj->recstatus}}</td>
    </tr>
    @endforeach
</table>