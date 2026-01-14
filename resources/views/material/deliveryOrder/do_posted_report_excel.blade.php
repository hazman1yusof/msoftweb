<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">compcode</td>
        <td style="font-weight:bold; text-align: left">username</td>
        <td style="font-weight:bold; text-align: left">job_id</td>
        <td style="font-weight:bold; text-align: left">recno</td>
        <td style="font-weight:bold; text-align: left">grnno</td>
        <td style="font-weight:bold; text-align: left">trandate</td>
        <td style="font-weight:bold; text-align: left">pono</td>
        <td style="font-weight:bold; text-align: left">delordno</td>
        <td style="font-weight:bold; text-align: left">deldept</td>
        <td style="font-weight:bold; text-align: left">grn_amt</td>
        <td style="font-weight:bold; text-align: left">grt_amt</td>
        <td style="font-weight:bold; text-align: left">invoice_amt</td>
        <td style="font-weight:bold; text-align: left">total_bal</td>
        <td style="font-weight:bold; text-align: left">suppcode</td>
        <td style="font-weight:bold; text-align: left">suppname</td>
        <td style="font-weight:bold; text-align: left">invoiceno</td>
        <td style="font-weight:bold; text-align: left">inv_postdate</td>
    </tr>
    @php($fulltot = 0)
    @foreach ($do_hd as $hd_obj)
        @php($fulltot = $fulltot + $hd_obj->totamount)
        <tr>
            <td style="text-align: left" >{{$hd_obj->compcode}}</td>
            <td style="text-align: left" ></td>
            <td style="text-align: left" ></td>
            <td style="text-align: left" >{{$hd_obj->recno}}</td>
            <td style="text-align: left" >{{$hd_obj->grnno}}</td>
            <td style="text-align: left" >{{$hd_obj->trandate}}</td>
            <td style="text-align: left" >{{$hd_obj->pono}}</td>
            <td style="text-align: left" >{{$hd_obj->delordno}}</td>
            <td style="text-align: left" >{{$hd_obj->deldept}}</td>
            <td style="text-align: left" >{{$hd_obj->totamount}}</td>
            <td style="text-align: left" ></td>
            <td style="text-align: left" ></td>
            <td style="text-align: left" ></td>
            <td style="text-align: left">{{$hd_obj->suppcode}}</td>
            <td style="text-align: left">{{$hd_obj->suppcode_desc}}</td>
            <td style="text-align: left" >INV{{$hd_obj->delordno}}</td>
            <td style="text-align: right">{{$hd_obj->postdate}}</td>
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