<table>
    <tr>
        <td style="font-weight:bold;text-align: left">PURCHASE<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">DELIVERY<br style="mso-data-placement:same-cell;" />DEPT</td>
        <td style="font-weight:bold;text-align: left">GRT NO</td>
        <td style="font-weight:bold;text-align: left">GRN NO</td>
        <td style="font-weight:bold;text-align: left">PO NO</td>
        <td style="font-weight:bold;text-align: left">RETURNED DATE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER CODE</td>
        <td style="font-weight:bold;text-align: left">SUPPLIER NAME</td>
        <td style="font-weight:bold;text-align: left">DO NO</td>
        <td style="font-weight:bold;text-align: left">PRICE<br style="mso-data-placement:same-cell;" />CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM CODE</td>
        <td style="font-weight:bold;text-align: left">ITEM DESCRIPTION</td>
        <td style="font-weight:bold;text-align: left">UOM CODE</td>
        <td style="font-weight:bold;text-align: left">PO UOM</td>
        <td style="font-weight:bold;text-align: right">GRN QTY</td>
        <td style="font-weight:bold;text-align: right">QTY RETURNED</td>
        <td style="font-weight:bold;text-align: right">UNIT PRICE</td>
        <td style="font-weight:bold;text-align: left">TAX CODE</td>
        <td style="font-weight:bold;text-align: right">PERCENTAGE<br style="mso-data-placement:same-cell;" />DISC (%)</td>
        <td style="font-weight:bold;text-align: right">DISC<br style="mso-data-placement:same-cell;" />PER UNIT</td>
        <td style="font-weight:bold;text-align: right">TOTAL GST</td>
        <td style="font-weight:bold;text-align: right">TOTAL AMOUNT</td>
        <td style="font-weight:bold;text-align: left">EXPIRY DATE</td>
        <td style="font-weight:bold;text-align: left">BATCH NO</td>
        <td style="font-weight:bold;text-align: left">STATUS</td>
    </tr>
    @foreach($GRTListing as $obj)
    <tr>
        <td>{{$obj->prdept}}</td>
        <td>{{$obj->deldept}}</td>
        <td>{{str_pad($obj->docno, 7, "0", STR_PAD_LEFT)}}</td>
        <td>{{str_pad($obj->srcdocno, 7, "0", STR_PAD_LEFT)}}</td>

        @if(!empty($obj->do_srcdocno))
            <td>{{str_pad($obj->do_srcdocno, 7, "0", STR_PAD_LEFT)}}</td>
        @else
            <td></td>
        @endif

        <td>{{\Carbon\Carbon::parse($obj->trandate)->format('d/m/Y')}}</td>
        <td>{{$obj->suppcode}}</td>
        <td>{{$obj->supp_name}}</td>
        <td>{{$obj->delordno}}</td>
       
        <td>{{$obj->pricecode}}</td>
        <td style="text-align: left">{{$obj->itemcode}}</td>
        <td>{{$obj->description}}</td>
        <td>{!!$obj->uomcode!!}</td>
        <td>{!!$obj->pouom!!}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->qtydelivered}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->qtyreturned}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->unitprice}}</td>
        <td>{{$obj->taxcode}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->perdisc}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->amtdisc}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->tot_gst}}</td>
        <td data-format="0.00" style="text-align: right">{{$obj->totamount}}</td>
        <td>{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
        <td>{{$obj->batchno}}</td>
        <td>{{$obj->recstatus}}</td>
    </tr>
    @endforeach
</table>