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
        @if(!$obj->skip)
        <tr>
            <td>{{$obj->glaccount}}</td>
            <td>{{$obj->pbalance}}</td>

            @php($diff = round($obj->pbalance,2) - 0)
            @foreach ($excel_data as $obj2)
                @if($obj->glaccount == $obj2->glaccount && $obj->costcode == $obj2->costcode)
                    <td>{{$obj2->glaccount}}</td>
                    <td>{{$obj2->pytd}}</td>
                    @php($diff = round($obj->pbalance,2) - round($obj2->pytd,2))
                @endif
            @endforeach

            @if($diff != 0)
                <td></td>
                <td></td>
                <td style="background-color: #ffff00;">{{$diff}}</td>
            @else
                <td>{{$diff}}</td>
            @endif
        </tr>
        @endif
    @endforeach
</table>

