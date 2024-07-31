<table>
    <tr>
    <tr>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        @foreach ($array_month_name as $month_name)
        <td style="text-align: center;" colspan="2">{{$month_name}}</td>
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

    @foreach ($glmasref as $obj_ar)
    <tr>
        <td>{{$obj_ar['glaccno']}}</td>
        <td>{{$obj_ar['accgroup']}}</td>
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

    </tr>
    @endforeach
</table>