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
            <tr>
                <td></td>
                <td></td>               
                <td>{{$obj->Addr1}}</td>
                <td></td>               
                <td></td>               
            </tr>
            <tr>
                <td></td>
                <td></td>               
                <td>{{$obj->Addr2}}</td>
                <td></td>               
                <td></td>               
            </tr>
            <tr>
                <td></td>
                <td></td>               
                <td>{{$obj->Addr3}}</td>
                <td></td>               
                <td></td>               
            </tr>
            <tr>
                <td></td>
                <td></td>               
                <td>{{$obj->Addr4}}</td>
                <td></td>               
                <td></td>               
            </tr>
            <tr></tr>
        @endforeach
</table>