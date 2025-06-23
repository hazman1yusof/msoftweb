<table>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center">{{$compname}}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center">BANK RECONCILIATION AS AT {{$recdate}}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center">{{$bankname}}</td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">DATE</td>
        <td style="font-weight: bold">CHEQUE NO</td>
        <td style="font-weight: bold">REFERENCE</td>
        <td style="font-weight: bold">AMOUNT</td>
    </tr>
    @foreach ($cbdtl as $obj)
        @if($obj->amount >= 0)
            <tr>
                <td style="text-align: left">{{$obj->docdate}}</td>
                <td>{{$obj->cheqno}}</td>
                <td>{{$obj->reference}}</td>
                <td>{{$obj->amount}}</td>
            </tr>
        @endif
    @endforeach
    <tr></tr>

    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">TOTAL RECONCILE DEPOSIT</td>
        <td style="font-weight: bold">{{$db_tot}}</td>
    </tr>
    <tr></tr>

    @foreach ($cbdtl as $obj)
        @if($obj->amount < 0)
            <tr>
                <td style="text-align: left">{{$obj->docdate}}</td>
                <td>{{$obj->cheqno}}</td>
                <td>{{$obj->reference}}</td>
                <td>{{$obj->amount}}</td>
            </tr>
        @endif
    @endforeach
    <tr></tr>

    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">TOTAL RECONCILED CHEQUE</td>
        <td style="font-weight: bold">{{$cr_tot}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">BALANCE AS PER BANK STATEMENT</td>
        <td style="font-weight: bold">{{$bs_bal}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">BALANCE AS PER CASH BOOK</td>
        <td style="font-weight: bold">{{$cb_bal}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">UNRECONCILED AMOUNT</td>
        <td style="font-weight: bold">{{$un_amt}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">LESS: UNPRESENTED CHEQUE</td>
        <td></td>
    </tr>
    @php
        $cbtot1 = 0;
    @endphp
    @foreach ($cb_tran as $obj)
        @if($obj->amount < 0)
        @php
            $cbtot1 = $cbtot1 + $obj->amount;
        @endphp
        <tr>
            <td style="text-align: left">{{$obj->postdate}}</td>
            <td>{{$obj->cheqno}}</td>
            <td>@if(!empty($obj->reference)){{$obj->reference}}@else{{$obj->remarks}}@endif</td>
            <td>{{abs($obj->amount)}}</td>
            <td>{{$obj->source}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->auditno}}</td>
        </tr>
        @endif
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">SUB-TOTAL</td>
        <td>{{$cbtot1}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">ADD: UNCREDITED CHEQUE</td>
        <td></td>
    </tr>
    @php
        $cbtot2 = 0;
    @endphp
    @foreach ($cb_tran as $obj)
        @if($obj->amount > 0)
        @php
            $cbtot2 = $cbtot2 + $obj->amount;
        @endphp
        <tr>
            <td style="text-align: left">{{$obj->postdate}}</td>
            <td>{{$obj->cheqno}}</td>
            <td>@if(!empty($obj->reference)){{$obj->reference}}@else{{$obj->remarks}}@endif</td>
            <td>{{$obj->amount}}</td>
            <td>{{$obj->source}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->auditno}}</td>
        </tr>
        @endif
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">SUB-TOTAL</td>
        <td>{{$cbtot2}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight: bold">CLOSING BALANCE</td>
        <td>{{$bs_bal + $cbtot1 + $cbtot2}}</td>
    </tr>
    <tr></tr>
</table>