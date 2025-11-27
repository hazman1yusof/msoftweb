<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Recno</td>
        <td style="font-weight:bold; text-align: left">Trantype</td>
        <td style="font-weight:bold; text-align: right">Amount</td>
        <td style="font-weight:bold; text-align: right">GL Amount</td>
    </tr>
    @foreach ($do_hd as $hd_obj)
        <tr>
            <td >{{$hd_obj->recno}}</td>
            <td >{{$hd_obj->trantype}}</td>
            <td style="text-align: right">{{$hd_obj->totamount}}</td>
            <td style="text-align: right">{{$hd_obj->glamount}}</td>
        </tr>
    @endforeach
</table>