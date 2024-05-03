<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">NAME</td>
            <td style="font-weight:bold">CONTACT PERSON</td>
            <td style="font-weight:bold">TEL</td>
            <td style="font-weight:bold">FAX</td>
            <td style="font-weight:bold">COMPANY ACC NO</td>

        </tr>
        <tr></tr>
        @foreach ($supp_code as $obj)
            <tr>
                <td>{{$obj->SuppCode}}</td>
                <td>{{$obj->Name}}</td>
                <td>{{$obj->ContPers}}</td>               
                <td>{{$obj->TelNo}}</td>               
                <td>{{$obj->Faxno}}</td> 
                <td>{{$obj->AccNo}}</td>                             
            </tr>
            <tr>
                <td></td>
                <td>{{$obj->Addr1}}</td>               
                <td></td>
                <td></td>               
                <td></td> 
                <td></td>                             
            </tr>
            <tr>
                <td></td>
                <td>{{$obj->Addr2}}</td>               
                <td></td>
                <td></td>               
                <td></td>  
                <td></td>                            
            </tr>
            <tr>
                <td></td>
                <td>{{$obj->Addr3}}</td>               
                <td></td>
                <td></td>               
                <td></td> 
                <td></td>                             
            </tr>
            <tr>
                <td></td>
                <td>{{$obj->Addr4}}</td>               
                <td></td>
                <td></td>               
                <td></td>  
                <td></td>                            
            </tr>
            <tr></tr>
        @endforeach
</table>