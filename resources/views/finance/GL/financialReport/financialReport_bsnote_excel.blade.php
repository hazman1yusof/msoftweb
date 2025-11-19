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
        <td style="font-weight:bold; text-align: left">Account Code</td>
        <td style="font-weight:bold; text-align: left">Description</td>
        <td style="font-weight:bold; text-align: left">YTD</td>
    </tr> 
    <tr></tr>
    @foreach ($glrptfmt as $obj)
        <tr>
            <td style="font-weight:bold; text-align: left">{{$obj->code}}</td>
            <td style="font-weight:bold; text-align: left">{{$obj->description}}</td>
            <td></td>
        </tr> 
        @foreach ($excel_data as $obj_ar)
            <tr>
                <td>{{$obj_ar->glaccount}}</td>
                <td>{{$obj_ar->description}}</td>
                <td>{{$obj_ar->pytd}}</td>
            </tr> 
        @endforeach

    @endforeach
</table>