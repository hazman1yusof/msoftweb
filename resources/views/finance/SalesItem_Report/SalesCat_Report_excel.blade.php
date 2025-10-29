<table>
    <tr>    
        <td style="font-weight:bold">DATE</td>
        <td style="font-weight:bold">CHARGE TYPE</td>
        <td style="font-weight:bold">CHARGE CODE</td>
        <td style="font-weight:bold">DESCRIPTION</td>
        <td style="font-weight:bold; text-align: right">QUANTITY</td>
        <td style="font-weight:bold; text-align: right">TOT AMOUNT</td>
        <td style="font-weight:bold; text-align: right">TOT COST</td>
        <td style="font-weight:bold; text-align: right">TAX</td>
        <td style="font-weight:bold; text-align: right">TOTAL LINE</td>
        <td style="font-weight:bold; text-align: right">TOTAL</td>
    </tr>
    @php($totalAmount = 0)
    @foreach($chgtype as $type)
        <tr>
            <td style="font-weight:bold" colspan="7">{{$type->chgtype}} - {{$type->ct_desc}}</td>
        </tr>
        @php($amt = 0)
        @php($tax = 0)
        @php($cpr = 0)
        @php($tot = 0)
        @foreach ($dbacthdr as $obj)
            @if($obj->chgtype == $type->chgtype)
                <tr>
                    <td>{{\Carbon\Carbon::parse($obj->trxdate)->format('d/m/Y')}}</td>
                    <td>{{$obj->chgtype}}</td>
                    <td>{{$obj->chgcode}}</td>
                    <td>{{$obj->cm_desc}}</td>
                    <td>{{$obj->quantity}}</td>
                    <td style="text-align: right">{{$obj->amount}}</td>
                    <td style="text-align: right">{{$obj->costprice}}</td>
                    <td style="text-align: right">{{$obj->taxamount}}</td>
                    <td style="text-align: right">{{$obj->amount+$obj->taxamount}}</td>
                </tr>
                @php($amt += $obj->amount)
                @php($cpr += $obj->costprice)
                @php($tax += $obj->taxamount)
                @php($tot += $obj->amount+$obj->taxamount)
            @endif
        @endforeach
            <tr></tr>
            <table> 
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight:bold">TOTAL</td>
                <td style="text-align: right; font-weight:bold">{{$amt}}</td>
                <td style="text-align: right; font-weight:bold">{{$cpr}}</td>
                <td style="text-align: right; font-weight:bold">{{$tax}}</td>
                <td style="text-align: right;">{{$tot}}</td>
                <td style="text-align: right; font-weight:bold">{{$tot}}</td>
            </table>
        @php($totalAmount += $tot)
    @endforeach
    <tr></tr>
    <table> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight:bold">GRAND TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{$totalAmount}}</td>
        </table>
</table>

