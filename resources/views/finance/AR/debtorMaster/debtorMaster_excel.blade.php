<table>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">CODE</td>
        <td style="font-weight: bold">FINANCIAL CLASS</td>
        <td style="font-weight: bold">NAME</td>
        <td style="font-weight: bold">POSTCODE</td>
        <td style="font-weight: bold">TEL OFFICE</td>
        <td style="font-weight: bold">FAX</td>
        <td style="font-weight: bold">CONTACT</td>
        <td style="font-weight: bold">EMAIL</td>
    </tr>
    <tr></tr>
    @foreach ($debtormast as $obj)
    <tr>
        <td style="text-align: left">{{$obj->debtorcode}}</td>
        <td>{{$obj->debtortype}}</td>
        <td>{{$obj->name}}</td>
        <td>{{$obj->postcode}}</td>
        <td>{{$obj->teloffice}}</td>
        <td>{{$obj->fax}}</td>
        <td>{{$obj->contact}}</td>
        <td>{{$obj->email}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address1}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address2}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address3}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address4}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    @endforeach
</table>