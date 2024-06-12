<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold">CODE</td>
        <td style="font-weight:bold">TYPE</td>
        <td style="font-weight:bold">NAME</td>
        <td style="font-weight:bold">TEL</td>
        <td style="font-weight:bold">FAX</td>
        <td style="font-weight:bold">Date Created</td>
    </tr>
    <tr></tr>
    @foreach ($debtormast as $obj)
    <tr>
        <td style="text-align: left">{{$obj->debtorcode}}</td>
        <td>{{$obj->debtortype}}</td>
        <td>{{$obj->name}}</td>
        <td>{{$obj->teloffice}}</td>
        <td>{{$obj->fax}}</td>
        <td>{{\Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address1}}</td>
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
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$obj->address3}}</td>
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
    </tr>
    <tr></tr>
    @endforeach
</table>