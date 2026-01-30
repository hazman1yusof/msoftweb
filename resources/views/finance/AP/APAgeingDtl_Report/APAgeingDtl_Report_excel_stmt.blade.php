<table>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->name}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address1}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address2}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address3}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address4}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">STATEMENT OF ACCOUNT</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">AS AT DATE: {{$date_asof}}</td>
    </tr>
    <tr></tr>
    @foreach($suppcode as $index => $supp)
        <tr>
            <td colspan="2">{{$supp->name}}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td colspan="2">{{$supp->addr1}}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td colspan="2">{{$supp->addr2}}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td colspan="2">{{$supp->addr3}}</td>
            <td></td><td></td>
            <td colspan="3">DATE PRINTED: {{$datenow}} </td>
        </tr>
        <tr>
            <td colspan="2">{{$supp->addr4}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;text-align: left">DOC DATE</td>
            <td style="font-weight: bold;text-align: left">AUDIT NO</td>
            <td style="font-weight: bold;text-align: left">DOCUMENT</td>
            <td style="font-weight: bold;text-align: left">REMARK</td>
            <td style="font-weight: bold;text-align: right">BALANCE AMOUNT</td>
            <td style="font-weight: bold;text-align: right">TOTAL</td>
        </tr>
        <tr></tr>
        @php($totalAmount = 0)
        @foreach($array_report as $db_obj)
            @if($db_obj->suppcode == $supp->suppcode)
                @php($totalAmount += $db_obj->newamt)
                <tr>
                    <td>{{\Carbon\Carbon::parse($db_obj->postdate)->format('d/m/Y')}}</td>
                    <td data-format="@">{{str_pad($db_obj->auditno, 7, "0", STR_PAD_LEFT)}}</td>

                    <td data-format="@">{{$db_obj->document}}</td>
                    <td data-format="@">{{$db_obj->remarks}}</td>
                    <td style="text-align: right">{{$db_obj->newamt}}</td>
                    <td style="text-align: right">{{$totalAmount}}</td>
                </tr>
            @endif
        @endforeach

    @endforeach

    @foreach($ap_pv_main as $pv_main)
        @php($totalAmount -= $pv_main->ap1_amount)
        <tr>
            <td>{{\Carbon\Carbon::parse($pv_main->ap1_postdate)->format('d/m/Y')}}</td>
            <td data-format="@">{{str_pad($pv_main->ap1_auditno, 7, "0", STR_PAD_LEFT)}}</td>

            <td data-format="@">{{$pv_main->ap1_trantype}}-{{$pv_main->ap1_pvno}}</td>
            <td data-format="@">{{$pv_main->ap1_remarks}}</td>
            <td style="text-align: right">-{{$pv_main->ap1_amount}}</td>
            <td style="text-align: right">{{$totalAmount}}</td>
        </tr>
        @foreach($ap_pv as $pv_obj)
            @if($pv_obj->ap1_auditno == $pv_main->ap1_auditno)
                @php($totalAmount += $pv_obj->ap2_amount)
                <tr>
                    <td>{{\Carbon\Carbon::parse($pv_obj->ap2_postdate)->format('d/m/Y')}}</td>
                    <td data-format="@">{{str_pad($pv_obj->ap2_auditno, 7, "0", STR_PAD_LEFT)}}</td>

                    <td data-format="@">{{$pv_obj->ap2_document}}</td>
                    <td data-format="@">{{$pv_obj->ap2_remarks}}</td>
                    <td style="text-align: right">{{$pv_obj->ap2_amount * -1}}</td>
                    <td style="text-align: right">{{$totalAmount}}</td>
                </tr>
            @endif
        @endforeach
    @endforeach
    
    <tr></tr>
    <tr>
        <td></td>
        @foreach ($grouping as $key => $group)
            @if($key+1 < count($grouping))
            <td style="font-weight:bold; text-align: left">{{$group+1}}-{{$grouping[$key+1]}} Days</td>
            @else
            <td style="font-weight:bold; text-align: left">> {{$group}} Days</td>
            @endif
        @endforeach
        <td style="font-weight:bold; text-align: left">Total</td>
    </tr>

    <tr>
        <td></td>
        @php($total_line = 0)
    @foreach ($grouping_tot as $key => $val)
        @php($total_line = $total_line + $val)
        <td>{{$val}}</td>
    @endforeach
        <td>{{$total_line}}</td>
    </tr>

    <tr></tr>
    <div style="page-break-after: always" />

    <tr></tr>
    <tr>
        <td></td>
        <td>Note:-</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    If you do not agree with the above balance please inform us in writing within 14 days.</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    Any payment received after the close of the month will appear in next month's statement.</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    Cheque payment is valid only when cleared by our bank.</td>
    </tr>
</table>