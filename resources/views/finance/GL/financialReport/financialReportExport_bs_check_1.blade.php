<table>
    <tr>
        <td style="font-weight:bold; text-align: left">Check for Month {{$month}} and year {{$year}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">BS GL Account</td>
        <td style="font-weight:bold; text-align: left">pbalance</td>
        <td style="font-weight:bold; text-align: left">rpt_glaccount</td>
        <td style="font-weight:bold; text-align: left">rpt_balance</td>
    </tr>
    @foreach ($glmasref as $obj)
        <tr>
            <td>{{$obj->glaccount}}</td>
            <td>{{$obj->pbalance}}</td>

            @foreach ($excel_data as $obj2)
                @if($obj->glaccount == $obj2->glaccount)
                    <td>{{$obj->glaccount}}</td>
                    <td>{{$obj->pbalance}}</td>
                @endif
            @endforeach

        </tr>
    @endforeach
</table>