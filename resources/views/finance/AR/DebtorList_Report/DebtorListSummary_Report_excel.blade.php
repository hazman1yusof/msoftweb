<table>
    <tr>
    </tr>
    <tr>
        <td style="font-weight:bold">CODE</td>
        <td style="font-weight:bold">TYPE</td>
        <td style="font-weight:bold">NAME</td>
        <td style="font-weight:bold">TEL</td>
        <td style="font-weight:bold">FAX</td>
    </tr>
    <tr></tr>
    @foreach ($debtormast as $obj)
    <tr>
        <td>{{$obj->debtorcode}}</td>
        <td>{{$obj->debtortype}}</td>
        <td>{{$obj->name}}</td>
        <td>{{$obj->teloffice}}</td>
        <td>{{$obj->fax}}</td>
    </tr>
    @endforeach
</table>