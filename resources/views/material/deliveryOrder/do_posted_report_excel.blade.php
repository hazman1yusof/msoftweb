<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Post Date</td>
        <td style="font-weight:bold; text-align: left">DO No</td>
        <td style="font-weight:bold; text-align: left">Supplier Code</td>
        <td style="font-weight:bold; text-align: left">Supplier</td>
        <td style="font-weight:bold; text-align: left">Amount</td>
    </tr>
    @php($fulltot = 0)
    @foreach ($do_hd as $hd_obj)
        @php($fulltot = $fulltot + $hd_obj->totamount)
        <tr>
            <td style="text-align: left" >{{$hd_obj->postdate}}</td>
            <td style="text-align: left" >INV{{$hd_obj->delordno}}</td>
            <td style="text-align: left">{{$hd_obj->suppcode}}</td>
            <td style="text-align: left">{{$hd_obj->suppcode_desc}}</td>
            <td style="text-align: right">{{$hd_obj->totamount}}</td>
        </tr>
    @endforeach
        <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: left"></td>
        <td style="font-weight:bold; text-align: right">{{$fulltot}}</td>
    </tr>
</table>