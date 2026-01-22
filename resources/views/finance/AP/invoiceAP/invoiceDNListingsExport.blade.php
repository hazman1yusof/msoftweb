<table>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center;font-weight:bold;" colspan="4">{{$title1}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center;font-weight:bold;" colspan="4">{{$title2}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center;font-weight:bold;" colspan="4">{{$title3}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Doc. Date</td>
        <td style="font-weight:bold; text-align: left">Rec. Date</td>
        <td style="font-weight:bold; text-align: left">Doc. No</td>
        <td style="font-weight:bold; text-align: left">Category</td>
        <td style="font-weight:bold; text-align: left">Supplier</td>
        <td style="font-weight:bold; text-align: left">Name</td>
        <td style="font-weight:bold; text-align: left">Pay To</td>
        <td style="font-weight:bold; text-align: right">Amount</td>
        <td style="font-weight:bold; text-align: right">O/S Amount</td>
        <td style="font-weight:bold; text-align: left">Remarks</td>
        <td style="font-weight:bold; text-align: left">Unit</td>
        <td style="font-weight:bold; text-align: left">PO No</td>
        <td style="font-weight:bold; text-align: left">PO Date</td>
        <td style="font-weight:bold; text-align: left">Type</td>
    </tr>
    <tr></tr>

    @php($grandtotal1 = 0)
    @php($grandtotal2 = 0)
    @foreach ($supplier as $supp_obj)
        @php($subtotal1 = 0)
        @php($subtotal2 = 0)
        @foreach ($apacthdr as $obj)
            @if($supp_obj->suppcode == $obj->suppcode)
                @php($subtotal1 = $subtotal1 + $obj->amount)
                @php($subtotal2 = $subtotal2 + $obj->outamount)
                <tr>
                    <td style="text-align: left">{{$obj->actdate}}</td>
                    <td style="text-align: left">{{$obj->postdate}}</td>
                    <td style="text-align: left">{{$obj->document}}</td>
                    <td style="text-align: left">{{$obj->category}}</td>
                    <td style="text-align: left">{{$obj->suppcode}}</td>
                    <td style="text-align: left">{{$obj->Name}}</td>
                    <td style="text-align: left">{{$obj->payto}}</td>
                    <td style="text-align: right">{{$obj->amount}}</td>
                    <td style="text-align: right">{{$obj->outamount}}</td>
                    <td style="text-align: left">{{$obj->remarks}}</td>
                    <td style="text-align: left">{{$obj->unit}}</td>
                    <td style="text-align: left">{{$obj->apd_reference}}</td>
                    <td style="text-align: left">{{$obj->purdate}}</td>
                    <td style="text-align: left"></td>
                </tr>
            @endif
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight:bold; text-align: left">Sub Total</td>
            <td>{{$subtotal1}}</td>
            <td>{{$subtotal2}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        @php($grandtotal1 = $grandtotal1 + $subtotal1)
        @php($grandtotal2 = $grandtotal2 + $subtotal2)
    @endforeach
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold; text-align: left">Grand Total</td>
        <td>{{$grandtotal1}}</td>
        <td>{{$grandtotal2}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
</table>