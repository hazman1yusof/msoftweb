<table>
    <tr>
        <td style="font-weight:bold;text-align: left">UNIT</td>
        <td style="font-weight:bold;text-align: left">DATE</td>
        <td style="font-weight:bold;text-align: left">PAYER CODE</td>
        <td style="font-weight:bold;text-align: left">NAME</td>
        <td style="font-weight:bold;text-align: right">BILL AMOUNT</td>
        <td style="font-weight:bold;text-align: right">AUDITNO</td>
        <td style="font-weight:bold;text-align: right">INV NO</td>
        <td style="font-weight:bold;text-align: right">CASH</td>
        <td style="font-weight:bold;text-align: right">CARD</td>
        <td style="font-weight:bold;text-align: right">CHEQUE</td>
        <td style="font-weight:bold;text-align: right">BANK</td> <!-- auto debit -->
        <td style="font-weight:bold;text-align: right">CREDIT NOTE</td>
        <td style="font-weight:bold;text-align: left">RECEIPT NO</td>
    </tr>
    @php($rowcount = 1)
    @foreach($main_db as $main_obj)
    <tr>
        <td>{{$main_obj->unit}}</td>
        <td>{{\Carbon\Carbon::parse($main_obj->posteddate)->format('d/m/Y')}}</td>
        <td style="text-align: left">{{$main_obj->payercode}}</td>
        @if(!empty($main_obj->pmt_name))
        <td>{{$main_obj->pmt_name}}</td>
        @else
        <td>{{$main_obj->dm_name}}</td>
        @endif
        <td>{{ $main_obj->amount }}</td>
        <td>{{$main_obj->auditno}}</td>
        <td>{{$main_obj->invno}}</td>
    </tr>
    @php($rowcount++)

        @foreach($dbacthdr as $db_obj)
            @if($db_obj->source==$main_obj->source && $db_obj->trantype==$main_obj->trantype && $db_obj->auditno==$main_obj->auditno && !empty($db_obj->db_amount))
            <tr>
                <td></td>
                <td>{{\Carbon\Carbon::parse($db_obj->posteddate)->format('d/m/Y')}}</td>
                <td style="text-align: left">{{$db_obj->payercode}}</td>
                <td>{{$db_obj->dm_name}}</td>
                <td></td>
                <td></td>
                <td></td>
                @if(strtoupper($db_obj->pm_paytype) == 'CASH')
                <td>{{ $db_obj->db_amount }}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                @elseif(strtoupper($db_obj->pm_paytype) == 'CARD')
                <td>0</td>
                <td>{{ $db_obj->db_amount }}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                @elseif(strtoupper($db_obj->pm_paytype) == 'CHEQUE')
                <td>0</td>
                <td>0</td>
                <td>{{ $db_obj->db_amount }}</td>
                <td>0</td>
                <td>0</td>
                @elseif(strtoupper($db_obj->pm_paytype) == 'BANK')
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>{{ $db_obj->db_amount }}</td>
                <td>0</td>
                @elseif(strtoupper($db_obj->pm_paytype) == 'CREDIT NOTE')
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>{{ $db_obj->db_amount }}</td>
                @endif
                <td>{{($db_obj->recptno)}}</td>
            </tr>
            @php($rowcount++)
            @endif
        @endforeach

    @endforeach
    <tr></tr>
    @php($rowcount++)

    @foreach($dbacthdr_arex as $main_obj)
        <tr>
            <td></td>
            <td>{{\Carbon\Carbon::parse($main_obj->posteddate)->format('d/m/Y')}}</td>
            <td style="text-align: left">{{$main_obj->payercode}}</td>
            @if(!empty($main_obj->pmt_name))
            <td>{{$main_obj->pmt_name}}</td>
            @else
            <td>{{$main_obj->dm_name}}</td>
            @endif
            <td>{{ $main_obj->amount }}</td>
            <td>{{$main_obj->auditno}}</td>
            <td>{{$main_obj->invno}}</td>
            @if(strtoupper($main_obj->pm_paytype) == 'CASH')
            <td>{{ $main_obj->amount_minus }}</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            @elseif(strtoupper($main_obj->pm_paytype) == 'CARD')
            <td>0</td>
            <td>{{ $main_obj->amount_minus }}</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            @elseif(strtoupper($main_obj->pm_paytype) == 'CHEQUE')
            <td>0</td>
            <td>0</td>
            <td>{{ $main_obj->amount_minus }}</td>
            <td>0</td>
            <td>0</td>
            @elseif(strtoupper($main_obj->pm_paytype) == 'BANK')
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>{{ $main_obj->amount_minus }}</td>
            <td>0</td>
            @elseif(strtoupper($main_obj->pm_paytype) == 'CREDIT NOTE')
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>{{ $main_obj->amount_minus }}</td>
            @endif
            <td>{{($main_obj->recptno)}}</td>
        </tr>

        @php($rowcount++)
    @endforeach

    @if(!empty($dbacthdr_arex))
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold">TOTAL</td>
        <td style="font-weight:bold">=SUM(E2:E{{$rowcount}})</td>
        <td></td>
        <td></td>
        <td style="font-weight:bold">=SUM(H2:H{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(I2:I{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(J2:J{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(K2:K{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(L2:L{{$rowcount}})</td>
        <td></td>
    </tr>
    @endif
</table>