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
    @foreach($debtormast as $index => $debtor)
        <tr>
            <td colspan="2">{{$debtor->name}}</td>
            <td></td><td></td>
            <td colspan="3">CREDIT TERM: {{$debtor->creditterm}} </td>
        </tr>
        <tr>
            <td colspan="2">{{$debtor->address1}}</td>
            <td></td><td></td>
            <td colspan="3">CREDIT LIMIT: {{$debtor->creditlimit}} </td>
        </tr>
        <tr>
            <td colspan="2">{{$debtor->address2}}</td>
            <td></td><td></td>
            <td colspan="3">DEBTOR CODE: {{$debtor->debtorcode}} </td>
        </tr>
        <tr>
            <td colspan="2">{{$debtor->address3}}</td>
            <td></td><td></td>
            <td colspan="3">DATE PRINTED: {{$datenow}} </td>
        </tr>
        <tr>
            <td colspan="2">{{$debtor->address4}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;text-align: left">DOC DATE</td>
            <td style="font-weight: bold;text-align: left">DATE SEND</td>
            <td style="font-weight: bold;text-align: left">AUDIT NO</td>
            <td style="font-weight: bold;text-align: left">DOCUMENT</td>
            <td style="font-weight: bold;text-align: left">REMARK</td>
            <td style="font-weight: bold;text-align: right">BALANCE AMOUNT</td>
            <td style="font-weight: bold;text-align: right">TOTAL</td>
            <td style="font-weight: bold;text-align: left">UNIT</td>
            <td style="font-weight: bold;text-align: left">REFERENCE</td>
            <td style="font-weight: bold;text-align: left">POLIKLINIK</td>
        </tr>
        <tr></tr>
        @php($totalAmount = 0)
        @foreach($array_report as $db_obj)
            @if($db_obj->debtorcode == $debtor->debtorcode)
            @php($grouping_tot[$db_obj->group] = $grouping_tot[$db_obj->group] + $db_obj->newamt)
            <tr>
                <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
                @if(!empty($db_obj->datesend))
                    <td>{{\Carbon\Carbon::parse($db_obj->datesend)->format('d/m/Y')}}</td>
                @else
                    <td></td>
                @endif
                <td data-format="@">{{str_pad($db_obj->auditno, 7, "0", STR_PAD_LEFT)}}</td>
                <td data-format="@">{{$db_obj->doc_no}}</td>

                @if(is_numeric($db_obj->reference))
                <td  data-format="0">{{$db_obj->reference}}</td>
                @else
                <td  data-format="@">{{$db_obj->reference}}</td>
                @endif

                @if(in_array($db_obj->trantype, ['IN','DN','BC','RF']))
                    @php($totalAmount += $db_obj->newamt)
                    <td style="text-align: right">{{$db_obj->newamt}}</td>
                @else
                    @php($totalAmount += $db_obj->newamt)
                    <td style="text-align: right">{{$db_obj->newamt}}</td>
                @endif
                <td style="text-align: right">{{$totalAmount}}</td>
                @if(strtoupper($db_obj->unit) == 'POLIS15')
                <td>POLIKLINIK</td>
                @else
                <td>{{$db_obj->unit}}</td>
                @endif

                @if(is_numeric($db_obj->reference))
                <td  data-format="0">{{$db_obj->reference}}</td>
                @else
                <td  data-format="@">{{$db_obj->reference}}</td>
                @endif
                
                @if($db_obj->trantype == 'IN')
                <td>{{$db_obj->tillcode}}</td>
                @endif
            </tr>
            @endif
        @endforeach

        <tr></tr>
        @foreach($db_rc_main as $rc_main)
            @if($rc_main->db1_debtorcode == $debtor->debtorcode)
                @php($totalAmount -= $rc_main->db1_amount)
                <tr>
                    <td>{{\Carbon\Carbon::parse($rc_main->db1_posteddate)->format('d/m/Y')}}</td>
                    <td></td>
                    <td data-format="@">{{$rc_main->db1_auditno}}</td>
                    <td data-format="@">{{$rc_main->db1_recptno}}</td>
                    <td data-format="0">{{$rc_main->db1_recptno}}</td>
                    <td style="text-align: right">-{{$rc_main->db1_amount}}</td>
                    <td style="text-align: right">{{$totalAmount}}</td>
                    <td>{{$rc_main->db1_unit}}</td>
                    <td data-format="0">{{$rc_main->db1_reference}}</td>
                </tr>
                @foreach($db_rc as $rc_obj)
                    @if($rc_obj->db1_auditno == $rc_main->db1_auditno)
                        @php($totalAmount += $rc_obj->da_allocamount)
                        <tr>
                            <td>{{\Carbon\Carbon::parse($rc_obj->db2_posteddate)->format('d/m/Y')}}</td>
                            <td></td>
                            <td data-format="@">{{$rc_obj->db2_auditno}}</td>
                            <td data-format="@">{{$rc_obj->db2_trantype}}/{{$rc_obj->db2_invno}}</td>
                            <td data-format="0">{{$rc_obj->pm_name}}</td>
                            <td style="text-align: right">{{$rc_obj->da_allocamount}}</td>
                            <td style="text-align: right">{{$totalAmount}}</td>
                            @if(strtoupper($rc_obj->db2_unit) == 'POLIS15')
                            <td>POLIKLINIK</td>
                            @else
                            <td>{{$rc_obj->db2_unit}}</td>
                            @endif
                            <td data-format="0">{{$rc_obj->db2_reference}}</td>
                            <td>{{$rc_obj->db2_tillcode}}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
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
    @endforeach
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