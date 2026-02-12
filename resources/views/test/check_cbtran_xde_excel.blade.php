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
            <td style="font-weight:bold">remarks</td>
            <td style="font-weight:bold">upduser</td>
            <td style="font-weight:bold">reference</td>
            <td style="font-weight:bold">recondate</td>
            <td style="font-weight:bold">recptno</td>
        </tr>
        <tr></tr>
        @foreach ($cbtran as $obj)
            <tr>
                <td >{{$obj->source}}</td>
                <td >{{$obj->trantype}}</td>
                <td >{{$obj->auditno}}</td>
                <td >{{$obj->postdate}}</td>
                <td >{{$obj->year}}</td>
                <td >{{$obj->period}}</td>
                <td >{{$obj->amount}}</td>
                <td >{{$obj->remarks}}</td>
                <td >{{$obj->upduser}}</td>
                <td >{{$obj->reference}}</td>
                <td >{{$obj->recondate}}</td>
                <td >{{$obj->recptno}}</td>
            </tr>
        @endforeach
        <div style="page-break-after:always">
</table>