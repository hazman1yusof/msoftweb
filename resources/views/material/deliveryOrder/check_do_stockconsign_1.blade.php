<table>
    <tr>
        <td style="font-weight:bold; text-align: left" colspan="5">Compare DO listings to Trial balance</td>
    </tr>
    <tr>
        <td style="font-weight:bold; text-align: left" colspan="5">Year: {{$year}} and Period: {{$period}} and Department: {{$deptcode}}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Department</td>
        <td style="font-weight:bold; text-align: left">Control A/c - PURCHASE STOCK (20010042) </td>
        <td style="font-weight:bold; text-align: left">Control A/c - PURCHASE CONSIGNMENT (20010044) </td>
    </tr>
    @foreach ($delorddt as $obj)
        <tr>
            <td >{{$obj->deldept}}</td>
            <td >{{$obj->_20010042}}</td>
            <td >{{$obj->_20010044}}</td>
        </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold; text-align: left">Total</td>
        <td style="font-weight:bold;">{{$_20010042}}</td>
        <td style="font-weight:bold;">{{$_20010044}}</td>
    </tr>
</table>