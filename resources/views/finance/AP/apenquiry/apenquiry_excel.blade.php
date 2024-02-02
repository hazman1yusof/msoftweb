<table>
    <tr>
    </tr>
    @foreach ($supp_code as $scode)
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">:  {{$scode->suppcode}}</td>

        </tr>
        <tr>
            <td style="font-weight:bold">NAME</td>
            <td colspan = '3' style="font-weight:bold">:  {{$scode->supplier_name}}</td>

        </tr>
        <tr>
            <td style="font-weight:bold">ADDRESS</td>
            <td colspan = '6' style="font-weight:bold">:  {{$scode->Addr1}} {{$scode->Addr2}} {{$scode->Addr3}} {{$scode->Addr4}}</td>
        </tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold">DATE</td>
            <td style="font-weight:bold">DOCUMENT</td>
            <td style="font-weight:bold">REFERENCE</td>
            <td style="font-weight:bold; text-align: right">AMOUNT DR</td>
            <td style="font-weight:bold; text-align: right">AMOUNT CR</td>
            <td style="font-weight:bold; text-align: right">BALANCE</td>
        </tr>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td>OPENING BALANCE</td>
            <td></td>
            <td></td>
            <td data-format="0.00" style="text-align: right">{{number_format($scode->openbal, 2, '.', ',')}}</td>
        </tr>
        @php($tot_dr = 0)
        @php($tot_cr = 0)
        @php($tot_bal = 0)
        @foreach ($array_report as $obj)
            @if($obj->suppcode == $scode->suppcode)
                <tr>
                    <td>{{\Carbon\Carbon::parse($obj->postdate)->format('d/m/Y')}}</td>
                    <td>{{strtoupper($obj->trantype)}}/{{strtoupper($obj->docno)}}</td>
                    <td style="text-align: left">{{strtoupper($obj->remarks)}}</td>
                    @if(!empty($obj->amount_dr))
                        @php($tot_dr += $obj->amount_dr)
                        <td data-format="0.00" style="text-align: right">{{number_format($obj->amount_dr, 2, '.', ',')}}</td>
                    @else
                        <td data-format="0.00" style="text-align: right"></td>
                    @endif

                    @if(!empty($obj->amount_cr))
                        @php($tot_cr += $obj->amount_cr)
                        <td data-format="0.00" style="text-align: right">{{number_format($obj->amount_cr, 2, '.', ',')}}</td>
                    @else
                        <td data-format="0.00" style="text-align: right"></td>
                    @endif
                    
                    @php($tot_bal += $obj->balance)
                    <td data-format="0.00" style="text-align: right">{{number_format($obj->balance, 2, '.', ',')}}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td style="font-weight:bold">TOTAL</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_dr, 2, '.', ',')}}</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_cr, 2, '.', ',')}}</td>
            <td data-format="0.00" style="text-align: right; font-weight:bold">{{number_format($tot_bal, 2, '.', ',')}}</td>
        </tr>
        <tr></tr>
        <div style="page-break-after:always" />
    @endforeach
</table>