<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Auditno</td>
        <td style="font-weight:bold; text-align: left">Source</td>
        <td style="font-weight:bold; text-align: left">Trantype</td>
        <td style="font-weight:bold; text-align: right">Date</td>
        <td style="font-weight:bold; text-align: right">drcostcode</td>
        <td style="font-weight:bold; text-align: right">dracc</td>
        <td style="font-weight:bold; text-align: right">crcostcode</td>
        <td style="font-weight:bold; text-align: right">cracc</td>
        <td style="font-weight:bold; text-align: right">amount</td>
    </tr>
    @foreach ($excel_data as $obj)
        <tr>
            <td >{{$obj->recno}}</td>
            <td >{{$obj->source_}}</td>
            <td >{{$obj->trantype}}</td>
            <td >{{$obj->postdate}}</td>
            <td >{{$obj->drcostcode}}</td>
            <td >{{$obj->dracc}}</td>
            <td >{{$obj->crcostcode}}</td>
            <td >{{$obj->cracc}}</td>
            <td >{{$obj->newamt}}</td>
        </tr>
    @endforeach
</table>