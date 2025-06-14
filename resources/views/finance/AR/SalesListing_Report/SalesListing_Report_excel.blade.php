<table>
    <tr>
        <td style="font-weight: bold">SELF PAID</td>
    </tr>
    <tr>
        <td style="font-weight: bold">DATE</td>
        <td style="font-weight: bold">DATE SEND</td>
        <td style="font-weight: bold">DEBTOR</td>
        <td style="font-weight: bold">DOCUMENT NO</td>
        <td style="font-weight: bold;text-align: right">AMOUNT</td>
        <td style="font-weight: bold;text-align: right">OUTAMOUNT</td>
        <td style="font-weight: bold">MRN</td>
        <td style="font-weight: bold">DEPARTMENT</td>
    </tr>
    @php($tot_selfPaid = 0)
    @foreach($dbacthdr as $obj)
        @if($obj->dm_debtortype == 'PT' || $obj->dm_debtortype == 'PR')
        <tr>
            <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
            @if(!empty($obj->datesend))
                <td>{{\Carbon\Carbon::parse($obj->datesend)->format('d/m/Y')}}</td>
            @else
                <td></td>
            @endif
            <td style="text-align: left">{{$obj->debtorcode}}</td>
            <td>{{str_pad($obj->auditno, 8, "0", STR_PAD_LEFT)}}</td>
            <td style="text-align: right">{{$obj->amount}}</td>
            <td style="text-align: right">{{$obj->outamount}}</td>
            <td style="text-align: left">{{$obj->mrn}}</td>
            <td>{{$obj->deptcode}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: left">{{$obj->name}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @php($tot_selfPaid += $obj->amount)
        @endif
    @endforeach
    <tr>
        <td style="font-weight: bold" colspan="2">TOTAL AMOUNT:</td>
        <!-- <td></td> -->
        <td></td>
        <td></td>
        <td style="text-align: right">{{$tot_selfPaid, 2}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <!-- <tr></tr> -->
    <!-- <tr></tr> -->
</table>
<table>
    <tr>
        <td style="font-weight: bold">PANEL</td>
    </tr>
    <tr>
        <td style="font-weight: bold">DATE</td>
        <td style="font-weight: bold">DATE SEND</td>
        <td style="font-weight: bold">DEBTOR</td>
        <td style="font-weight: bold">DOCUMENT NO</td>
        <td style="font-weight: bold;text-align: right">AMOUNT</td>
        <td style="font-weight: bold;text-align: right">OUTAMOUNT</td>
        <td style="font-weight: bold">MRN</td>
        <td style="font-weight: bold">DEPARTMENT</td>
    </tr>
    @php($tot_panel = 0)
    @foreach($dbacthdr as $obj)
        @if($obj->dm_debtortype !== 'PT' && $obj->dm_debtortype !== 'PR')
        <tr>
            <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
            @if(!empty($obj->datesend))
                <td>{{\Carbon\Carbon::parse($obj->datesend)->format('d/m/Y')}}</td>
            @else
                <td></td>
            @endif
            <td style="text-align: left">{{$obj->debtorcode}}</td>
            <td>{{str_pad($obj->auditno, 8, "0", STR_PAD_LEFT)}}</td>
            <td style="text-align: right">{{$obj->amount}}</td>
            <td style="text-align: right">{{$obj->outamount}}</td>
            <td style="text-align: left">{{$obj->mrn}}</td>
            <td>{{$obj->deptcode}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: left">{{$obj->name}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @php($tot_panel += $obj->amount)
        @endif
    @endforeach
    <tr>
        <td style="font-weight: bold" colspan="2">TOTAL AMOUNT:</td>
        <!-- <td></td> -->
        <td></td>
        <td></td>
        <td style="text-align: right">{{$tot_panel}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr></tr>
</table>
<!-- <table>
    <tr>
        <td style="font-weight: bold">GRAND TOTAL:</td>
        <td></td>
        <td></td>
        <td data-format="0.00" style="font-weight: bold; text-align: right;">{{number_format($totalAmount, 2, '.', ',')}}</td>
    </tr>
</table> -->