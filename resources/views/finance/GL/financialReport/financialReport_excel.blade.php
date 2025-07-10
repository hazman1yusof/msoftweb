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
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Description</td>
        @foreach ($array_month_name as $month_name)
        <td style="font-weight:bold; text-align: center;">{{$month_name}}</td>
        @endforeach
        <td style="font-weight:bold; text-align: center">TOTAL YTD</td>
    </tr> 
    @php($totline = 7)
    @foreach ($excel_data as $obj_ar)
        @if($obj_ar['rowdef'] == 'H')
            <tr>
                <td style="font-weight:bold; text-align: left">{{$obj_ar['description']}}</td>
            </tr>
        @elseif($obj_ar['rowdef'] == 'S')
            <tr><td></td></tr>
        @elseif($obj_ar['rowdef'] == 'T' || $obj_ar['rowdef'] == 'T0')
            <tr>
            <td style="font-weight:bold;text-align: left;">{{$obj_ar['description']}}</td>
            @foreach ($array_month as $month)
                @if($obj_ar['revsign'] == 'Y')
                <td style="text-align: right;">{{abs($obj_ar['tot_arr']['sum_desc'.$month])}}</td>
                @else
                <td style="text-align: right;">{{$obj_ar['tot_arr']['sum_desc'.$month] * -1}}</td>
                @endif
            @endforeach
            @if($obj_ar['revsign'] == 'Y')
            <td style="text-align: right;">{{abs($obj_ar['tot_arr']['ytd'])}}</td>
            @else
            <td style="text-align: right;">{{$obj_ar['tot_arr']['ytd'] * -1}}</td>
            @endif
            </tr>
        @elseif($obj_ar['rowdef'] == 'D')
            <tr>
            <td style="text-align: left;">{{$obj_ar['description']}}</td>
            @foreach ($array_month as $month)
                @if($obj_ar['revsign'] == 'Y')
                <td style="text-align: right;">{{abs($obj_ar['tot_actamount'.$month])}}</td>
                @else
                <td style="text-align: right;">{{$obj_ar['tot_actamount'.$month] * -1}}</td>
                @endif
            @endforeach
            @if($obj_ar['revsign'] == 'Y')
            <td style="text-align: right;">{{abs($obj_ar['tot_ytd'])}}</td>
            @else
            <td style="text-align: right;">{{$obj_ar['tot_ytd'] * -1}}</td>
            @endif
            </tr>
        @endif

    @endforeach
</table>