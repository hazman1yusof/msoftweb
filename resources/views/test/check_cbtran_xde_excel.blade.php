<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">source</td>
            <td style="font-weight:bold">trantype</td>
            <td style="font-weight:bold">auditno</td>
            <td style="font-weight:bold">postdate</td>
            <td style="font-weight:bold">year</td>
            <td style="font-weight:bold">period</td>
            <td style="font-weight:bold">amount</td>
            <td style="font-weight:bold">remark</td>
            <td style="font-weight:bold">adduser</td>
            <td style="font-weight:bold">recptno</td>
            <td style="font-weight:bold">debtorcode</td>
            <td style="font-weight:bold">posteddate</td>
        </tr>
        <tr></tr>
        @foreach ($dbacthdr as $obj)
            <tr>
                <td >{{$obj->source}}</td>
                <td >{{$obj->trantype}}</td>
                <td >{{$obj->auditno}}</td>
                <td >{{$obj->posteddate}}</td>
                <td >{{$obj->amount}}</td>
                <td >{{$obj->remark}}</td>
                <td >{{$obj->adduser}}</td>
                <td >{{$obj->recptno}}</td>
                <td >{{$obj->debtorcode}}</td>
                <td >{{$obj->posteddate}}</td>
            </tr>
        @endforeach
        <div style="page-break-after:always">
</table>