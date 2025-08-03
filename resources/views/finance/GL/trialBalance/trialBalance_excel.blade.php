<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">{{$title1}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; text-align: left">{{$title2}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; text-align: left">{{$title3}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; text-align: left">{{$title4}}</td>
    </tr>
    <tr>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        @foreach ($array_month_name as $month_name)
        <td style="font-weight:bold; text-align: center;" colspan="2">{{$month_name}}</td>
        @endforeach
    </tr> 
    <tr>
        <td style="font-weight:bold; text-align: left">Account</td>
        <td style="font-weight:bold; text-align: left">Type</td>
        <td style="font-weight:bold; text-align: left">Description</td>
        <td style="font-weight:bold; text-align: right">Opening</td>
        @foreach ($array_month as $month)
        <td style="font-weight:bold; text-align: right">Debit</td>
        <td style="font-weight:bold; text-align: right">Credit</td>
        @endforeach
        <td style="font-weight:bold; text-align: right">YTD</td>
    </tr>      
    @php($totline = 7)
    @foreach ($glmasref as $obj_ar)
        @if($obj_ar['skip'] == 1)
            @continue
        @endif
        <tr>
            <td>{{$obj_ar['glaccno']}}</td>
            <td>{{$obj_ar['acttype']}}</td>
            <td>{{$obj_ar['description']}}</td>
            <td style="text-align: right">{{$obj_ar['tot_openbalance']}}</td>
            @foreach ($array_month as $month)
                @if($obj_ar['tot_actamount'.$month] == 0)
                    <td style="text-align: right"></td>
                    <td style="text-align: right"></td>
                @elseif($obj_ar['tot_actamount'.$month] < 0)
                    <td style="text-align: right"></td>
                    <td style="text-align: right">{{abs($obj_ar['tot_actamount'.$month])}}</td>
                @else
                    <td style="text-align: right">{{abs($obj_ar['tot_actamount'.$month])}}</td>
                    <td style="text-align: right"></td>
                @endif
            @endforeach
            <td style="text-align: right">{{$obj_ar['tot_ytd']}}</td>
            @php($totline++)
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold; text-align: left">TOTAL</td>
        <td>=SUM(D4:D{{$totline}})</td>
        @php($index=4)
        @foreach ($array_month as $month)
            <td>=SUM({{$alphabet[$index]}}4:{{$alphabet[$index]}}{{$totline}})</td>
            @php($index++)
            <td>=SUM({{$alphabet[$index]}}4:{{$alphabet[$index]}}{{$totline}})</td>
            @php($index++)
        @endforeach
        <td>=SUM({{$alphabet[$index]}}4:{{$alphabet[$index]}}{{$totline}})</td>
    </tr>
    <tr></tr>
    <tr>
        <td>{{$pnl_acc['glaccount']}}</td>
        <td></td>
        <td>{{$pnl_acc['description']}}</td>
        <td>{{$pnl_acc['openbalance']}}</td>
        @php($tot_pnl_acc=0)
        @foreach ($array_month as $month)
            @php($tot_pnl_acc = $tot_pnl_acc + $pnl_acc['actamount'.$month])
            @if($pnl_acc['actamount'.$month] == 0)
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
            @elseif($pnl_acc['actamount'.$month] < 0)
                <td style="text-align: right"></td>
                <td style="text-align: right">{{abs($pnl_acc['actamount'.$month])}}</td>
            @else
                <td style="text-align: right">{{abs($pnl_acc['actamount'.$month])}}</td>
                <td style="text-align: right"></td>
            @endif
        @endforeach
        <td style="font-weight:bold;">{{$tot_pnl_acc}}</td>
    </tr>
</table>