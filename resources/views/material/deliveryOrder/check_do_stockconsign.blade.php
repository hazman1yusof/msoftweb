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

    <tr>
        <td style="font-weight:bold; text-align: left">20010042</td>
        <td style="font-weight:bold; text-align: left" colspan="4">Control A/c - PURCHASE STOCK</td>
    </tr>

    @foreach ($excel_data as $obj)
        @if($obj->dracc == '20010042')
            <tr>
                <td >{{$obj->recno}}</td>
                <td >{{$obj->source}}</td>
                <td >{{$obj->trantype}}</td>
                <td >{{$obj->postdate}}</td>
                <td >{{$obj->drcostcode}}</td>
                <td >{{$obj->dracc}}</td>
                <td >{{$obj->crcostcode}}</td>
                <td >{{$obj->cracc}}</td>
                <td >{{$obj->newamt}}</td>
            </tr>
        @endif
    @endforeach

    <tr>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td style="font-weight:bold; text-align: left">Total</td>
        <td >{{$_20010042}}</td>
    </tr>
    <tr></tr>

    <tr>
        <td style="font-weight:bold; text-align: left">20010044</td>
        <td style="font-weight:bold; text-align: left" colspan="4">Control A/c - PURCHASE CONSIGNMENT</td>
    </tr>

    @foreach ($excel_data as $obj)
        @if($obj->dracc == '20010044')
            <tr>
                <td >{{$obj->recno}}</td>
                <td >{{$obj->source}}</td>
                <td >{{$obj->trantype}}</td>
                <td >{{$obj->postdate}}</td>
                <td >{{$obj->drcostcode}}</td>
                <td >{{$obj->dracc}}</td>
                <td >{{$obj->crcostcode}}</td>
                <td >{{$obj->cracc}}</td>
                <td >{{$obj->newamt}}</td>
            </tr>
        @endif
    @endforeach

    <tr>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td style="font-weight:bold; text-align: left">Total</td>
        <td style="font-weight:bold;">{{$_20010044}}</td>
    </tr>
    <tr></tr>
</table>