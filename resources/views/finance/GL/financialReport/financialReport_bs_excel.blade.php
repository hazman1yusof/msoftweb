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
        <td></td>
        <td style="font-weight:bold; text-align: left">Current MTH</td>
        <td style="font-weight:bold; text-align: left">Last MTH</td>
    </tr> 
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
            <td ></td>
            @if($obj_ar['revsign'] == 'Y')
            <td style="font-weight:bold;text-align: right;">{{$obj_ar['tot_arr']['curr_month'] * -1}}</td>
            <td style="font-weight:bold;text-align: right;">{{$obj_ar['tot_arr']['last_month'] * -1}}</td>
            @else
            <td style="font-weight:bold;text-align: right;">{{$obj_ar['tot_arr']['curr_month']}}</td>
            <td style="font-weight:bold;text-align: right;">{{$obj_ar['tot_arr']['last_month']}}</td>
            @endif
            </tr>
        @elseif($obj_ar['rowdef'] == 'D')
            <tr>
                <td style="text-align: left;">{{$obj_ar['description']}}</td>
                <td style="text-align: left;">{{$obj_ar['note']}}</td>
                @if($obj_ar['revsign'] == 'Y')
                <td style="text-align: right;">{{$obj_ar['curr_month'] * -1}}</td>
                <td style="text-align: right;">{{$obj_ar['last_month'] * -1}}</td>
                @else
                <td style="text-align: right;">{{$obj_ar['curr_month']}}</td>
                <td style="text-align: right;">{{$obj_ar['last_month']}}</td>
                @endif
            </tr>
        @endif

    @endforeach
</table>