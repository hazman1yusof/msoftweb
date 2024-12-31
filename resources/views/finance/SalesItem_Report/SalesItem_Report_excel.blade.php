<table>
    <tr>    
        <td style="font-weight:bold">DATE</td>
        <td style="font-weight:bold">CHARGE CODE</td>
        <td style="font-weight:bold">DESCRIPTION</td>
        <td style="font-weight:bold; text-align: right">QUANTITY</td>
        <td style="font-weight:bold; text-align: right">TOT AMOUNT</td>
        <td style="font-weight:bold; text-align: right">TOT COST</td>
        <td style="font-weight:bold; text-align: right">TAX</td>
        <td style="font-weight:bold; text-align: right">TOTAL</td>
    </tr>
    @php($totalAmount = 0)
    @foreach($invno_array as $invno)
        @php($amt = 0)
        @php($tax = 0)
        @php($cpr = 0)
        @php($tot = 0)
        @foreach ($dbacthdr as $obj)
            @if($invno == $obj->invno)
                @if($amt == 0)
                <tr>
                    <td style="font-weight:bold" colspan="7">{{$obj->debtorcode}} {{$obj->dm_desc}} ({{str_pad($obj->invno, 7, "0", STR_PAD_LEFT)}}) {{$obj->pm_name}}</td>
                </tr>
                @endif
                <tr>
                    <td>{{\Carbon\Carbon::parse($obj->trxdate)->format('d/m/Y')}}</td>
                    <td>{{$obj->chgcode}}</td>
                    <td>{{$obj->cm_desc}}</td>
                    <td>{{$obj->quantity}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->costprice, 2, '.', ',')}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->taxamount, 2, '.', ',')}}</td>
                    <td data-format="0.00" style="text-align: right">{{$obj->amount+$obj->taxamount}}</td>
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
                <td style="font-weight:bold">TOTAL</td>
                <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($amt, 2, '.', ',')}}</td>
                <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($cpr, 2, '.', ',')}}</td>
                <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tax, 2, '.', ',')}}</td>
                <td data-format="0.00" style="text-align: right; font-weight:bold">{{$tot}}</td>
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
            <td style="font-weight:bold">GRAND TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{$totalAmount}}</td>
        </table>
</table>

