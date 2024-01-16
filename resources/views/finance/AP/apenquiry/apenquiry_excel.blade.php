<table>
    <tr>
        <td style="font-weight:bold">DATE</td>
        <td style="font-weight:bold">DOCUMENT</td>
        <td style="font-weight:bold">REFERENCE</td>
        <td style="font-weight:bold; text-align: right">AMOUNT DR</td>
        <td style="font-weight:bold; text-align: right">AMOUNT CR</td>
        <td style="font-weight:bold; text-align: right">BALANCE</td>
        <!-- <td style="font-weight:bold">TRANTYPE</td>
        <td style="font-weight:bold">supplier</td> -->
    </tr>
    <tr>
        <td></td>
        <td colspan = '2'>OPENING BALANCE</td>
        <td></td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="text-align: right"></td>
        <!-- <td></td>
        <td></td> -->
    </tr>
    @php($tot_dr = 0)
    @php($tot_cr = 0)
    @php($tot_bal = 0)

    @foreach($apacthdr as $obj)
        <tr>
            <td>{{\Carbon\Carbon::parse($obj->postdate)->format('d/m/Y')}}</td>
            <td>{{$obj->document}}</td>
            <td style="text-align: left">{{$obj->remarks}}</td>
            @if(!empty($obj->amount_dr))
                <td data-format="0.00" style="text-align: right">{{number_format($obj->amount_dr, 2, '.', ',')}}</td>
            @else
                <td data-format="0.00" style="text-align: right"></td>
            @endif

            @if(!empty($obj->amount_cr))
                <td data-format="0.00" style="text-align: right">{{number_format($obj->amount_cr, 2, '.', ',')}}</td>
            @else
                <td data-format="0.00" style="text-align: right"></td>
            @endif
            <td data-format="0.00" style="text-align: right">{{number_format($obj->balance, 2, '.', ',')}}</td>
            <!-- <td>{{$obj->trantype}}</td>
            <td>{{$obj->suppcode}}</td> -->
        </tr>
        @php($tot_dr += $obj->amount_dr)
        @php($tot_cr += $obj->amount_cr)
        @php($tot_bal += $obj->balance)
    @endforeach
    <tr></tr>
    <tr></tr>
        <table> 
            <td></td>
            <td></td>
            <td style="font-weight:bold">TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_dr, 2, '.', ',')}}</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_cr, 2, '.', ',')}}</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_bal, 2, '.', ',')}}</td>
            <!-- <td></td>
            <td></td> -->
        </table>
</table>