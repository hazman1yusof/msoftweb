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
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold; text-align: left">Opening Amount</td>
        <td style="font-weight:bold; text-align: left">Current Amount</td>
        <td style="font-weight:bold; text-align: left">YTD</td>
    </tr> 
    <tr></tr>
    @foreach ($glrptfmt as $obj)
        <tr>
            <td style="font-weight:bold; text-align: left">{{$obj->note}}</td>
            <td style="font-weight:bold; text-align: left">{{$obj->code}}</td>
            <td style="font-weight:bold; text-align: left">{{$obj->description}}</td>
            <td></td>
        </tr> 
        @php($ytd_tot = 0)
        @foreach ($excel_data as $obj_ar)
            @if($obj_ar->note == $obj->note)
            @php($ytd_tot = $ytd_tot + $obj_ar->pytd)
            <tr>
                <td></td>
                <td style="text-align: left">{{$obj_ar->glaccount}}</td>
                <td style="text-align: left">{{$obj_ar->description}}</td>
                <td>{{$obj_ar->plastmonth}}</td>
                <td>{{$obj_ar->pcurrmonth}}</td>
                <td>{{$obj_ar->pytd}}</td>
            </tr>
            @endif
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-weight:bold">{{$ytd_tot}}</td>
        </tr>
    @endforeach
</table>