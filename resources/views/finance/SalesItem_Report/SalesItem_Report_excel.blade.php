<table>
    <tr>    
        <td style="font-weight:bold">DATE</td>
        <td style="font-weight:bold">CHARGE CODE</td>
        <td style="font-weight:bold">DESCRIPTION</td>
        <td style="font-weight:bold; text-align: right">QUANTITY</td>
        <td style="font-weight:bold; text-align: right">AMOUNT</td>
        <td style="font-weight:bold; text-align: right">TAX</td>
        <td style="font-weight:bold; text-align: right">TOTAL</td>
    </tr>
    @foreach($dbacthdr as $debtcode)
        
        <tr>
            <td style="font-weight:bold" colspan="3">{{$debtcode->debtorcode}} {{$debtcode->dm_desc}} ({{str_pad($debtcode->invno, 7, "0", STR_PAD_LEFT)}})</td>
        </tr>
        @php($tot = 0)
        @foreach ($billdet as $obj)
            @if($obj->debtorcode == $debtcode->debtorcode)
                <tr>
                    <td>{{\Carbon\Carbon::parse($obj->trxdate)->format('d/m/Y')}}</td>
                    <td>{{$obj->chgcode}}</td>
                    <td>{{$obj->cm_desc}}</td>
                    <td>{{$obj->quantity}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
                    <td>{{$obj->taxamount}}</td>
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->amount+$obj->taxamount, 2, '.', ',')}}</td>
                </tr>
            @php($tot += $obj->amount+$obj->taxamount)
            @endif
        @endforeach
        <tr></tr>
        <table> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight:bold">TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot, 2, '.', ',')}}</td>
        </table>
    @endforeach
    <tr></tr>
    <table> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight:bold">TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($totalAmount, 2, '.', ',')}}</td>
        </table>
</table>

