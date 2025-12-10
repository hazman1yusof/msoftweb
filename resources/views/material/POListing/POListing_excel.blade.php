<table>
    <tr>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left" colspan="5">PURCHASE ORDER LISTINGS</td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: left"></td>
    </tr>
    <tr>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left" colspan="5">Date From {{$datefr}} To {{$dateto}}</td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: left"></td>
    </tr>
    <tr>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left" colspan="5">Status: {{$Status}}, Department: {{$deptcode}}</td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: left"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: right"></td>
        <td style="font-weight:bold;text-align: left"></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold;text-align: left">PURCHASE<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">DELIVERY<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">PO NO</td>
        <td style="font-weight:bold;text-align: left">PO DATE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER CODE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER NAME</td>
        <td style="font-weight:bold;text-align: left">PRICE<br style="mso-data-placement:same-cell;" />CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM DESCRIPTION</td>
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
        <td style="font-weight:bold;text-align: left">STATUS</td>
    </tr>
    @foreach($POListing as $obj)
    <tr>
        <td>{{$obj->prdept}}</td>
        <td>{{$obj->deldept}}</td>
        <td>{{str_pad($obj->purordno, 7, "0", STR_PAD_LEFT)}}</td>
        <td>{{\Carbon\Carbon::parse($obj->purdate)->format('d/m/Y')}}</td>
        <td>{{$obj->suppcode}}</td>
        <td>{{$obj->supp_name}}</td>
        <td>{{$obj->pricecode}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td>{!!$obj->pouom!!}</td>
        <td>{{$obj->taxcode}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->qtyrequest}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->qtyorder}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->qtyoutstand}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->unitprice}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->perdisc}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->amtdisc}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->tot_gst}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->totamount}}</td>
        <td>{{$obj->recstatus}}</td>
    </tr>
    @endforeach
</table>