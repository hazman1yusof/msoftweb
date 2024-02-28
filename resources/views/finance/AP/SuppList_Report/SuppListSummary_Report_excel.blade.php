<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">GROUP</td>
            <td style="font-weight:bold">NAME</td>
            <td style="font-weight:bold">TEL</td>
            <td style="font-weight:bold">FAX</td>

        </tr>
        <tr></tr>
        @foreach ($supp_code as $obj)
            <tr>
                <td>{{$obj->SuppCode}}</td>
                <td>{{$obj->SuppGroup}}</td>               
                <td>{{$obj->Name}}</td>               
                <td>{{$obj->TelNo}}</td>               
                <td>{{$obj->Faxno}}</td>               
            </tr>
        @endforeach
</table>